<?php // phpcs:disable PSR1.Files.SideEffects.FoundWithSymbols

if (PHP_SAPI !== 'cli') {
    http_response_code(404);
    exit;
}

require_once __DIR__ . '/annotation-response.php';

function annotation_api_response($content, $finish_reason = 'stop')
{
    return json_encode([
        'choices' => [[
            'finish_reason' => $finish_reason,
            'message' => [
                'content' => $content,
            ],
        ]],
    ], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
}

function assert_same($expected, $actual, $message = '')
{
    if ($expected !== $actual) {
        throw new RuntimeException(
            ($message === '' ? '' : $message . ': ')
            . 'expected ' . var_export($expected, true)
            . ', got ' . var_export($actual, true)
        );
    }
}

$tests = [
    'uses bounded output and retry limits' => function () {
        assert_same(4096, \IROChatGPT\ANNOTATION_OUTPUT_TOKEN_LIMIT);
        assert_same(1, \IROChatGPT\ANNOTATION_MAX_RETRY_ATTEMPTS);
        assert_same(2, \IROChatGPT\ANNOTATION_RETRY_PARTS);
    },
    'parses a valid annotation object' => function () {
        $result = \IROChatGPT\parse_annotation_response(
            annotation_api_response('{"术语":"解释"}'),
            0,
            200
        );

        assert_same(null, $result['error_code']);
        assert_same(['术语' => '解释'], $result['annotations']);
        assert_same('stop', $result['finish_reason']);
    },
    'parses JSON inside a Markdown fence' => function () {
        $content = "```json\n{\"term\":\"explanation\"}\n```";
        $result = \IROChatGPT\parse_annotation_response(annotation_api_response($content));

        assert_same(null, $result['error_code']);
        assert_same(['term' => 'explanation'], $result['annotations']);
    },
    'accepts compatible responses without a finish reason' => function () {
        $response = json_encode([
            'choices' => [[
                'message' => ['content' => '{"term":"explanation"}'],
            ]],
        ]);
        $result = \IROChatGPT\parse_annotation_response($response);

        assert_same(null, $result['error_code']);
        assert_same(null, $result['finish_reason']);
    },
    'reports cURL failures without parsing a body' => function () {
        $result = \IROChatGPT\parse_annotation_response('', 28, 0);

        assert_same('curl_error', $result['error_code']);
        assert_same([], $result['annotations']);
        assert_same('cURL request failed', $result['error_message']);
    },
    'reports an empty successful response' => function () {
        $result = \IROChatGPT\parse_annotation_response('', 0, 200);

        assert_same('empty_response', $result['error_code']);
        assert_same([], $result['annotations']);
    },
    'reports HTTP failures without exposing the response body' => function () {
        $secret = 'sensitive-response-content';
        $result = \IROChatGPT\parse_annotation_response($secret, 0, 429);

        assert_same('http_error', $result['error_code']);
        assert_same([], $result['annotations']);
        assert_same(false, str_contains($result['error_message'], $secret));
    },
    'reports model API failures without exposing the response body' => function () {
        $secret = 'sensitive-provider-message';
        $response = json_encode(['error' => ['message' => $secret]]);
        $result = \IROChatGPT\parse_annotation_response($response);

        assert_same('model_error', $result['error_code']);
        assert_same([], $result['annotations']);
        assert_same(false, str_contains($result['error_message'], $secret));
    },
    'reports malformed outer JSON' => function () {
        $result = \IROChatGPT\parse_annotation_response('{invalid');

        assert_same('response_json_invalid', $result['error_code']);
        assert_same([], $result['annotations']);
    },
    'reports a missing model choice' => function () {
        $result = \IROChatGPT\parse_annotation_response('{"choices":[]}');

        assert_same('model_response_missing', $result['error_code']);
        assert_same([], $result['annotations']);
    },
    'reports missing model message content' => function () {
        $response = json_encode([
            'choices' => [[
                'finish_reason' => 'stop',
                'message' => [],
            ]],
        ]);
        $result = \IROChatGPT\parse_annotation_response($response);

        assert_same('model_response_missing', $result['error_code']);
        assert_same([], $result['annotations']);
        assert_same('stop', $result['finish_reason']);
    },
    'rejects output stopped by the token limit' => function () {
        $response = annotation_api_response('{"term":"complete but truncated"}', 'length');
        $result = \IROChatGPT\parse_annotation_response($response);

        assert_same('model_output_truncated', $result['error_code']);
        assert_same([], $result['annotations']);
        assert_same('length', $result['finish_reason']);
    },
    'rejects other non-success finish reasons' => function () {
        $response = annotation_api_response('{"term":"filtered"}', 'content_filter');
        $result = \IROChatGPT\parse_annotation_response($response);

        assert_same('model_finish_reason', $result['error_code']);
        assert_same([], $result['annotations']);
        assert_same('content_filter', $result['finish_reason']);
    },
    'rejects invalid finish reason types' => function () {
        $response = json_encode([
            'choices' => [[
                'finish_reason' => ['unexpected'],
                'message' => ['content' => '{"term":"explanation"}'],
            ]],
        ]);
        $result = \IROChatGPT\parse_annotation_response($response);

        assert_same('model_finish_reason', $result['error_code']);
        assert_same([], $result['annotations']);
        assert_same(null, $result['finish_reason']);
    },
    'redacts unknown finish reasons from diagnostics' => function () {
        $secret = 'provider-secret-finish-reason';
        $response = annotation_api_response('{"term":"explanation"}', $secret);
        $result = \IROChatGPT\parse_annotation_response($response);

        assert_same('model_finish_reason', $result['error_code']);
        assert_same([], $result['annotations']);
        assert_same('other', $result['finish_reason']);
        assert_same(false, str_contains($result['error_message'], $secret));
    },
    'rejects truncated annotation JSON' => function () {
        $result = \IROChatGPT\parse_annotation_response(
            annotation_api_response('{"term":"truncated"')
        );

        assert_same('annotation_json_missing', $result['error_code']);
        assert_same([], $result['annotations']);
    },
    'rejects malformed annotation JSON' => function () {
        $result = \IROChatGPT\parse_annotation_response(
            annotation_api_response('{"term": }')
        );

        assert_same('annotation_json_invalid', $result['error_code']);
        assert_same([], $result['annotations']);
    },
    'rejects annotation values that are not strings' => function () {
        $result = \IROChatGPT\parse_annotation_response(
            annotation_api_response('{"term":{"nested":"value"}}')
        );

        assert_same('annotation_schema_invalid', $result['error_code']);
        assert_same([], $result['annotations']);
    },
    'rejects annotation lists instead of extracting an inner object' => function () {
        $result = \IROChatGPT\parse_annotation_response(
            annotation_api_response('[{"term":"explanation"}]')
        );

        assert_same('annotation_schema_invalid', $result['error_code']);
        assert_same([], $result['annotations']);
    },
    'rejects annotation keys that cannot remain strings' => function () {
        $result = \IROChatGPT\parse_annotation_response(
            annotation_api_response('{"1":"numeric term"}')
        );

        assert_same('annotation_schema_invalid', $result['error_code']);
        assert_same([], $result['annotations']);
    },
    'combines successful segment responses' => function () {
        $response_results = [
            \IROChatGPT\parse_annotation_response(annotation_api_response('{"first":"valid"}')),
            \IROChatGPT\parse_annotation_response(annotation_api_response('{"second":"also valid"}')),
        ];
        $combined = \IROChatGPT\combine_annotation_response_results($response_results);

        assert_same(null, $combined['error_code']);
        assert_same(['first' => 'valid', 'second' => 'also valid'], $combined['annotations']);
        assert_same(null, $combined['failed_segment']);
    },
    'splits a truncated segment into two smaller paragraph groups' => function () {
        $segment = "first paragraph\n\nsecond paragraph\n\nthird paragraph";
        $parts = \IROChatGPT\split_annotation_segment_for_retry($segment);

        assert_same(2, count($parts));
        assert_same($segment, implode("\n\n", $parts));
        assert_same(true, strlen($parts[0]) < strlen($segment));
        assert_same(true, strlen($parts[1]) < strlen($segment));
    },
    'splits a single UTF-8 paragraph without corrupting characters' => function () {
        $parts = \IROChatGPT\split_annotation_segment_for_retry('甲乙。丙丁戊');

        assert_same(['甲乙。', '丙丁戊'], $parts);
        assert_same('甲乙。丙丁戊', implode('', $parts));
    },
    'plans exactly two retry requests for each truncated segment' => function () {
        $segments = [
            "first half\n\nsecond half",
            "third half\n\nfourth half",
        ];
        $truncated = \IROChatGPT\annotation_response_failure(
            'model_output_truncated',
            'Model output reached its token limit',
            'length'
        );
        $plan = \IROChatGPT\build_annotation_retry_plan($segments, [$truncated, $truncated]);

        assert_same(null, $plan['error_code']);
        assert_same(2, count($plan['retry_segments']));
        assert_same(2, count($plan['retry_segments'][0]));
        assert_same(2, count($plan['retry_segments'][1]));
    },
    'does not retry non-token-limit failures' => function () {
        $segments = ['first segment', 'second segment'];
        $results = [
            \IROChatGPT\parse_annotation_response(annotation_api_response('{"first":"valid"}')),
            \IROChatGPT\annotation_response_failure('http_error', 'API returned HTTP status 500'),
        ];
        $plan = \IROChatGPT\build_annotation_retry_plan($segments, $results);

        assert_same('non_retryable_failure', $plan['error_code']);
        assert_same([], $plan['retry_segments']);
        assert_same(1, $plan['failed_segment']);
    },
    'does not retry a segment that cannot be split safely' => function () {
        $truncated = \IROChatGPT\annotation_response_failure(
            'model_output_truncated',
            'Model output reached its token limit',
            'length'
        );
        $plan = \IROChatGPT\build_annotation_retry_plan(['甲'], [$truncated]);

        assert_same('retry_split_failed', $plan['error_code']);
        assert_same([], $plan['retry_segments']);
        assert_same(0, $plan['failed_segment']);
    },
    'replaces one truncated response after both smaller retries succeed' => function () {
        $truncated = \IROChatGPT\annotation_response_failure(
            'model_output_truncated',
            'Model output reached its token limit',
            'length'
        );
        $initial_results = [
            \IROChatGPT\parse_annotation_response(annotation_api_response('{"existing":"valid"}')),
            $truncated,
        ];
        $retry_results = [1 => [
            \IROChatGPT\parse_annotation_response(annotation_api_response('{"first":"valid"}')),
            \IROChatGPT\parse_annotation_response(annotation_api_response('{"second":"also valid"}')),
        ]];
        $applied = \IROChatGPT\apply_annotation_retry_results($initial_results, $retry_results);
        $combined = \IROChatGPT\combine_annotation_response_results($applied['response_results']);

        assert_same(null, $applied['error_code']);
        assert_same(null, $combined['error_code']);
        assert_same(
            ['existing' => 'valid', 'first' => 'valid', 'second' => 'also valid'],
            $combined['annotations']
        );
    },
    'stops after the bounded retry if a smaller response is still truncated' => function () {
        $truncated = \IROChatGPT\annotation_response_failure(
            'model_output_truncated',
            'Model output reached its token limit',
            'length'
        );
        $retry_results = [[
            \IROChatGPT\parse_annotation_response(annotation_api_response('{"first":"valid"}')),
            $truncated,
        ]];
        $applied = \IROChatGPT\apply_annotation_retry_results([$truncated], $retry_results);

        assert_same('retry_failed', $applied['error_code']);
        assert_same(0, $applied['failed_segment']);
    },
    'fails the whole operation after a later invalid response' => function () {
        $stored_annotations = ['existing' => 'preserved'];
        $response_results = [
            \IROChatGPT\parse_annotation_response(annotation_api_response('{"first":"valid"}')),
            \IROChatGPT\parse_annotation_response(annotation_api_response('{"second":"truncated"')),
        ];
        $combined = \IROChatGPT\combine_annotation_response_results($response_results);

        if ($combined['error_code'] === null) {
            $stored_annotations = $combined['annotations'];
        }

        assert_same('segment_failure', $combined['error_code']);
        assert_same([], $combined['annotations']);
        assert_same(1, $combined['failed_segment']);
        assert_same(['existing' => 'preserved'], $stored_annotations);
    },
];

$failures = [];
foreach ($tests as $description => $test) {
    try {
        $test();
        echo ". $description\n";
    } catch (Throwable $error) {
        $failures[$description] = $error->getMessage();
        echo "F $description\n";
    }
}

echo count($tests) . ' tests, ' . count($failures) . " failures\n";
foreach ($failures as $description => $message) {
    echo "\n$description\n$message\n";
}

exit(count($failures) === 0 ? 0 : 1);
