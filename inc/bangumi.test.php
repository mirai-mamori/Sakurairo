<?php

namespace Sakura\API;

$GLOBALS['bangumi_test_pages'] = [
    'https://api.bgm.tv/v0/users/test-user/collections?offset=0&limit=30' => [
        'data' => array_merge(
            [
                [
                    'type' => 2,
                    'subject_type' => 2,
                    'ep_status' => 12,
                    'subject' => [
                        'name' => 'Anime 1',
                        'name_cn' => '动画 1',
                        'date' => '2024-01-01',
                        'short_summary' => 'Summary 1',
                        'id' => 1,
                        'images' => ['large' => 'https://example.com/1.jpg'],
                        'eps' => 12,
                    ],
                ],
            ],
            array_fill(0, 29, [
                'type' => 1,
                'subject_type' => 1,
                'ep_status' => 0,
                'subject' => [
                    'name' => 'Book',
                    'name_cn' => '',
                    'date' => '',
                    'short_summary' => '',
                    'id' => 999,
                    'images' => ['large' => 'https://example.com/book.jpg'],
                    'eps' => 0,
                ],
            ])
        ),
        'total' => 31,
    ],
    'https://api.bgm.tv/v0/users/test-user/collections?offset=30&limit=30' => [
        'data' => [
            [
                'type' => 3,
                'subject_type' => 2,
                'ep_status' => 24,
                'subject' => [
                    'name' => 'Anime 2',
                    'name_cn' => '动画 2',
                    'date' => '2024-02-02',
                    'short_summary' => 'Summary 2',
                    'id' => 2,
                    'images' => ['large' => 'https://example.com/2.jpg'],
                    'eps' => 24,
                ],
            ],
        ],
        'total' => 31,
    ],
];
$GLOBALS['bangumi_test_cache'] = null;

function iro_opt($key, $default = null)
{
    return false;
}

function get_transient($key)
{
    return $GLOBALS['bangumi_test_cache'];
}

function auto_update_cache($key, $value)
{
    $GLOBALS['bangumi_test_cache'] = $value;
}

function wp_remote_get($url, $args = [])
{
    if (isset($GLOBALS['bangumi_test_pages'][$url])) {
        return [
            'response' => ['code' => 200],
            'body' => json_encode($GLOBALS['bangumi_test_pages'][$url]),
        ];
    }

    return [
        'response' => ['code' => 404],
        'body' => json_encode(['error' => 'Not Found']),
    ];
}

function is_wp_error($response)
{
    return false;
}

function wp_remote_retrieve_response_code($response)
{
    return $response['response']['code'];
}

function wp_remote_retrieve_body($response)
{
    return $response['body'];
}

function __($text, $domain = null)
{
    return $text;
}

function esc_url($text)
{
    return $text;
}

function esc_attr($text)
{
    return $text;
}

function esc_html($text)
{
    return $text;
}

function rest_url($path = '')
{
    return 'https://example.com/wp-json/' . ltrim($path, '/');
}

function urlencode($value)
{
    return \urlencode($value);
}

function wp_json_encode($value)
{
    return json_encode($value);
}

require_once __DIR__ . '/classes/bangumi.php';

class TestSuite
{
    private array $test_cases = [];

    public function it(string $descr, callable $fn)
    {
        $this->test_cases[$descr] = $fn;
        return $this;
    }

    public function run()
    {
        $errors = [];
        foreach ($this->test_cases as $descr => $test_case) {
            echo $descr . ' ';
            try {
                $test_case();
                echo ".\n";
            } catch (\Exception $e) {
                $errors[$descr][] = $e;
                echo "F\n";
            }
        }

        echo count($this->test_cases) . ' cases, ' . count($errors) . " Failures:\n";
        foreach ($errors as $descr => $caseErrors) {
            echo $descr . "\n";
            foreach ($caseErrors as $e) {
                echo $e . "\n";
            }
            echo "\n";
        }
    }

    public static function strictEqual($actual, $expect)
    {
        if ($actual !== $expect) {
            throw new \Exception('actual: ' . var_export($actual, true) . ', expect: ' . var_export($expect, true));
        }
    }
}

$suite = new TestSuite();
$suite->it('BangumiAPI fetches and merges all collection pages', function () {
    $api = new BangumiAPI('test-user');
    $collections = $api->getCollections();

    TestSuite::strictEqual(count($collections), 2);
    TestSuite::strictEqual($collections[0]['name'], 'Anime 1');
    TestSuite::strictEqual($collections[1]['name'], 'Anime 2');
})
    ->it('BangumiList renders later-page anime after aggregation', function () {
        $list = new BangumiList();
        $html = $list->get_bgm_items('test-user', 1);

        TestSuite::strictEqual(strpos($html, 'Anime 1') !== false, true);
        TestSuite::strictEqual(strpos($html, 'Anime 2') !== false, true);
    })
    ->run();
