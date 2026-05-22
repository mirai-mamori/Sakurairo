<?php

namespace Sakura\API;

class Presence
{
    private const MAP_KEY = 'sakurairo_presence_map';
    private const RATE_LIMIT_SECONDS = 5;

    public static function get_ttl(): int
    {
        $ttl = (int) iro_opt('footer_online_count_ttl', 90);
        return max(60, min(120, $ttl));
    }

    public static function get_interval(): int
    {
        $interval = (int) iro_opt('footer_online_count_interval', 5);
        return max(3, min(60, $interval));
    }

    private static function get_map(): array
    {
        $map = get_transient(self::MAP_KEY);
        return is_array($map) ? $map : [];
    }

    private static function save_map(array $map): void
    {
        $ttl = self::get_ttl();
        set_transient(self::MAP_KEY, $map, $ttl + 60);
    }

    private static function prune_map(array $map): array
    {
        $cutoff = time() - self::get_ttl();
        foreach ($map as $id => $ts) {
            if (!is_int($ts) || $ts < $cutoff) {
                unset($map[$id]);
            }
        }
        return $map;
    }

    public static function get_count(): int
    {
        return count(self::prune_map(self::get_map()));
    }

    private static function client_ip(): string
    {
        if (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
            $parts = explode(',', sanitize_text_field(wp_unslash($_SERVER['HTTP_X_FORWARDED_FOR'])));
            return trim($parts[0]);
        }
        return isset($_SERVER['REMOTE_ADDR'])
            ? sanitize_text_field(wp_unslash($_SERVER['REMOTE_ADDR']))
            : '0.0.0.0';
    }

    private static function rate_limit_ok(): bool
    {
        $key = 'sakurairo_presence_rl_' . md5(self::client_ip());
        if (get_transient($key)) {
            return false;
        }
        set_transient($key, 1, self::RATE_LIMIT_SECONDS);
        return true;
    }

    public static function sanitize_presence_id(?string $id): ?string
    {
        if (!$id || !is_string($id)) {
            return null;
        }
        $id = strtolower(trim($id));
        if (preg_match('/^[a-f0-9]{8}-[a-f0-9]{4}-4[a-f0-9]{3}-[89ab][a-f0-9]{3}-[a-f0-9]{12}$/', $id)) {
            return $id;
        }
        return null;
    }

    public static function generate_presence_id(): string
    {
        return wp_generate_uuid4();
    }

    private static function touch(string $presence_id): array
    {
        $map = self::prune_map(self::get_map());
        $map[$presence_id] = time();
        self::save_map($map);
        return $map;
    }

    private static function remove(string $presence_id): void
    {
        $map = self::get_map();
        unset($map[$presence_id]);
        self::save_map(self::prune_map($map));
    }

    public static function no_store_headers(): void
    {
        if (!headers_sent()) {
            header('Cache-Control: no-store, no-cache, must-revalidate, max-age=0');
            header('Pragma: no-cache');
        }
    }

    public static function ping(\WP_REST_Request $request): \WP_REST_Response
    {
        self::no_store_headers();

        if (!self::rate_limit_ok()) {
            return new \WP_REST_Response(
                [
                    'count' => self::get_count(),
                    'ttl' => self::get_ttl(),
                    'rate_limited' => true,
                ],
                200
            );
        }

        $presence_id = self::sanitize_presence_id($request->get_param('presence_id'));
        if (!$presence_id) {
            $presence_id = self::generate_presence_id();
        }

        self::touch($presence_id);

        return new \WP_REST_Response(
            [
                'count' => self::get_count(),
                'ttl' => self::get_ttl(),
                'presence_id' => $presence_id,
            ],
            200
        );
    }

    public static function count_endpoint(\WP_REST_Request $request): \WP_REST_Response
    {
        self::no_store_headers();
        return new \WP_REST_Response(
            [
                'count' => self::get_count(),
                'ttl' => self::get_ttl(),
            ],
            200
        );
    }

    public static function leave(\WP_REST_Request $request): \WP_REST_Response
    {
        self::no_store_headers();
        $presence_id = self::sanitize_presence_id($request->get_param('presence_id'));
        if ($presence_id) {
            self::remove($presence_id);
        }
        return new \WP_REST_Response(
            [
                'count' => self::get_count(),
            ],
            200
        );
    }

    public static function stream_sse(\WP_REST_Request $request): void
    {
        self::no_store_headers();
        nocache_headers();

        if (function_exists('header_remove')) {
            @header_remove('Content-Type');
        }

        header('Content-Type: text/event-stream; charset=UTF-8');
        header('X-Accel-Buffering: no');

        $interval = self::get_interval();
        $max_runtime = (int) ini_get('max_execution_time');
        if ($max_runtime <= 0 || $max_runtime > 120) {
            $max_runtime = 90;
        }
        $deadline = time() + $max_runtime - 5;

        while (time() < $deadline) {
            if (connection_aborted()) {
                break;
            }
            $payload = wp_json_encode([
                'count' => self::get_count(),
                'ttl' => self::get_ttl(),
            ]);
            echo 'data: ' . $payload . "\n\n";
            if (ob_get_level()) {
                ob_flush();
            }
            flush();
            sleep($interval);
        }
        exit;
    }
}
