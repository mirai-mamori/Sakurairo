<?php

namespace IROChatGPT {

    const ANNOTATION_OUTPUT_TOKEN_LIMIT = 4096;
    const ANNOTATION_MAX_RETRY_ATTEMPTS = 1;
    const ANNOTATION_RETRY_PARTS = 2;

    function annotation_response_failure($error_code, $error_message, $finish_reason = null)
    {
        $error_message = preg_replace('/\s+/', ' ', (string) $error_message);

        return [
            'annotations' => [],
            'error_code' => $error_code,
            'error_message' => substr($error_message, 0, 200),
            'finish_reason' => $finish_reason,
        ];
    }

    function is_string_annotation_map($annotations)
    {
        if (!is_array($annotations)) {
            return false;
        }

        foreach ($annotations as $term => $explanation) {
            if (!is_string($term) || !is_string($explanation)) {
                return false;
            }
        }

        return true;
    }

    /**
     * Combine parsed segment responses atomically.
     */
    function combine_annotation_response_results($response_results)
    {
        $annotations = [];

        foreach ($response_results as $index => $response_result) {
            if (
                !is_array($response_result)
                || !array_key_exists('error_code', $response_result)
                || $response_result['error_code'] !== null
                || !array_key_exists('annotations', $response_result)
                || !is_string_annotation_map($response_result['annotations'])
            ) {
                return [
                    'annotations' => [],
                    'error_code' => 'segment_failure',
                    'failed_segment' => $index,
                ];
            }

            $annotations = array_merge($annotations, $response_result['annotations']);
        }

        return [
            'annotations' => $annotations,
            'error_code' => null,
            'failed_segment' => null,
        ];
    }

    /**
     * Split one truncated input into exactly two smaller UTF-8-safe segments.
     */
    function split_annotation_segment_for_retry($segment)
    {
        $segment = trim((string) $segment);
        if ($segment === '') {
            return [];
        }

        $paragraphs = preg_split('/\n\s*\n/u', $segment, -1, PREG_SPLIT_NO_EMPTY);
        if (is_array($paragraphs) && count($paragraphs) > 1) {
            $target_length = strlen($segment) / ANNOTATION_RETRY_PARTS;
            $left = [];
            $right = $paragraphs;
            $left_length = 0;

            while (count($right) > 1) {
                $next = $right[0];
                if (!empty($left) && $left_length + strlen($next) > $target_length) {
                    break;
                }

                $left[] = array_shift($right);
                $left_length += strlen($next) + 2;
            }

            $parts = [
                trim(implode("\n\n", $left)),
                trim(implode("\n\n", $right)),
            ];
        } else {
            $characters = preg_split('//u', $segment, -1, PREG_SPLIT_NO_EMPTY);
            if (!is_array($characters) || count($characters) < ANNOTATION_RETRY_PARTS) {
                return [];
            }

            $character_count = count($characters);
            $midpoint = (int) ceil($character_count / ANNOTATION_RETRY_PARTS);
            $search_radius = (int) floor($character_count / 4);

            for ($offset = 0; $offset <= $search_radius; $offset++) {
                foreach ([$midpoint + $offset, $midpoint - $offset] as $candidate) {
                    if ($candidate <= 0 || $candidate >= $character_count) {
                        continue;
                    }

                    if (preg_match('/[\s。！？.!?；;,，]/u', $characters[$candidate - 1]) === 1) {
                        $midpoint = $candidate;
                        break 2;
                    }
                }
            }

            $parts = [
                implode('', array_slice($characters, 0, $midpoint)),
                implode('', array_slice($characters, $midpoint)),
            ];
        }

        if (count($parts) !== ANNOTATION_RETRY_PARTS || in_array('', $parts, true)) {
            return [];
        }

        return $parts;
    }

    /**
     * Plan one bounded retry only for token-limit failures.
     */
    function build_annotation_retry_plan($segments, $response_results)
    {
        $retry_segments = [];

        foreach ($segments as $index => $segment) {
            if (!isset($response_results[$index]) || !is_array($response_results[$index])) {
                return [
                    'retry_segments' => [],
                    'error_code' => 'response_missing',
                    'failed_segment' => $index,
                ];
            }

            $error_code = array_key_exists('error_code', $response_results[$index])
                ? $response_results[$index]['error_code']
                : 'response_invalid';
            if ($error_code === null) {
                continue;
            }

            if ($error_code !== 'model_output_truncated') {
                return [
                    'retry_segments' => [],
                    'error_code' => 'non_retryable_failure',
                    'failed_segment' => $index,
                ];
            }

            $parts = split_annotation_segment_for_retry($segment);
            if (count($parts) !== ANNOTATION_RETRY_PARTS) {
                return [
                    'retry_segments' => [],
                    'error_code' => 'retry_split_failed',
                    'failed_segment' => $index,
                ];
            }

            $retry_segments[$index] = $parts;
        }

        return [
            'retry_segments' => $retry_segments,
            'error_code' => null,
            'failed_segment' => null,
        ];
    }

    /**
     * Replace truncated initial responses with one successful retry result each.
     */
    function apply_annotation_retry_results($initial_results, $retry_results)
    {
        foreach ($initial_results as $index => $initial_result) {
            $error_code = is_array($initial_result) && array_key_exists('error_code', $initial_result)
                ? $initial_result['error_code']
                : 'response_invalid';

            if ($error_code === null) {
                continue;
            }

            if ($error_code !== 'model_output_truncated' || !isset($retry_results[$index])) {
                return [
                    'response_results' => $initial_results,
                    'error_code' => 'retry_failed',
                    'failed_segment' => $index,
                ];
            }

            $combined_retry = combine_annotation_response_results($retry_results[$index]);
            if ($combined_retry['error_code'] !== null) {
                return [
                    'response_results' => $initial_results,
                    'error_code' => 'retry_failed',
                    'failed_segment' => $index,
                ];
            }

            $initial_results[$index] = [
                'annotations' => $combined_retry['annotations'],
                'error_code' => null,
                'error_message' => null,
                'finish_reason' => 'stop',
            ];
        }

        return [
            'response_results' => $initial_results,
            'error_code' => null,
            'failed_segment' => null,
        ];
    }

    /**
     * Parse one OpenAI-compatible annotation response without WordPress dependencies.
     */
    function parse_annotation_response($response, $curl_errno = 0, $http_status = 200)
    {
        if ($curl_errno !== 0) {
            return annotation_response_failure('curl_error', 'cURL request failed');
        }

        if ($http_status < 200 || $http_status >= 300) {
            return annotation_response_failure(
                'http_error',
                sprintf('API returned HTTP status %d', $http_status)
            );
        }

        if (!is_string($response) || trim($response) === '') {
            return annotation_response_failure('empty_response', 'API returned an empty response');
        }

        $result = json_decode($response, true);
        if (json_last_error() !== JSON_ERROR_NONE) {
            return annotation_response_failure(
                'response_json_invalid',
                'API response is not valid JSON: ' . json_last_error_msg()
            );
        }

        if (!is_array($result)) {
            return annotation_response_failure('response_json_invalid', 'API response is not a JSON object');
        }

        if (isset($result['error'])) {
            return annotation_response_failure('model_error', 'Model API returned an error');
        }

        if (!isset($result['choices'][0]) || !is_array($result['choices'][0])) {
            return annotation_response_failure('model_response_missing', 'Model response does not contain a choice');
        }

        $choice = $result['choices'][0];
        $finish_reason = null;
        if (array_key_exists('finish_reason', $choice) && $choice['finish_reason'] !== null) {
            if (!is_string($choice['finish_reason'])) {
                return annotation_response_failure(
                    'model_finish_reason',
                    'Model returned an invalid finish reason'
                );
            }

            $finish_reason = $choice['finish_reason'];
            $finish_reason = preg_replace('/\s+/', ' ', $finish_reason);
            $finish_reason = substr($finish_reason, 0, 50);
            if (!in_array($finish_reason, ['stop', 'length', 'content_filter', 'tool_calls', 'function_call'], true)) {
                $finish_reason = 'other';
            }
        }

        if ($finish_reason === 'length') {
            return annotation_response_failure(
                'model_output_truncated',
                'Model output reached its token limit',
                $finish_reason
            );
        }

        if ($finish_reason !== null && $finish_reason !== 'stop') {
            return annotation_response_failure(
                'model_finish_reason',
                'Model did not report a successful stop',
                $finish_reason
            );
        }

        if (
            !isset($choice['message'])
            || !is_array($choice['message'])
            || !isset($choice['message']['content'])
            || !is_string($choice['message']['content'])
        ) {
            return annotation_response_failure(
                'model_response_missing',
                'Model response does not contain message content',
                $finish_reason
            );
        }

        $annotation_json = trim($choice['message']['content']);
        if (preg_match('/^```(?:json)?\s*(.*?)\s*```$/is', $annotation_json, $matches)) {
            $annotation_json = trim($matches[1]);
        }

        if (
            $annotation_json === ''
            || substr($annotation_json, 0, 1) !== '{'
            || substr($annotation_json, -1) !== '}'
        ) {
            $decoded_content = json_decode($annotation_json);
            if (json_last_error() === JSON_ERROR_NONE && !is_object($decoded_content)) {
                return annotation_response_failure(
                    'annotation_schema_invalid',
                    'Annotations must be a JSON object',
                    $finish_reason
                );
            }

            return annotation_response_failure(
                'annotation_json_missing',
                'Model content does not contain a complete JSON object',
                $finish_reason
            );
        }

        $annotation_object = json_decode($annotation_json);
        if (json_last_error() !== JSON_ERROR_NONE) {
            return annotation_response_failure(
                'annotation_json_invalid',
                'Annotation JSON is invalid: ' . json_last_error_msg(),
                $finish_reason
            );
        }

        if (!is_object($annotation_object)) {
            return annotation_response_failure(
                'annotation_schema_invalid',
                'Annotations must be a JSON object',
                $finish_reason
            );
        }

        $annotations = get_object_vars($annotation_object);
        if (!is_string_annotation_map($annotations)) {
            return annotation_response_failure(
                'annotation_schema_invalid',
                'Annotation terms and explanations must be strings',
                $finish_reason
            );
        }

        return [
            'annotations' => $annotations,
            'error_code' => null,
            'error_message' => null,
            'finish_reason' => $finish_reason,
        ];
    }
}
