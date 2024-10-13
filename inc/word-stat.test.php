<?php
require_once __DIR__ . '/word-stat.php';

class TestSuite
{
    private array $test_cases = array();
    public function it(string $descr, callable $fn)
    {
        $this->test_cases[$descr] = $fn;
        return $this;
    }
    public function run()
    {
        $errors = array();
        foreach ($this->test_cases as $descr => $test_case) {
            echo $descr . " ";
            try {
                $test_case();
                echo ".";
            } catch (Exception $e) {
                if (isset($errors[$descr])) {
                    array_push($errors[$descr], $e);
                } else {
                    $errors[$descr] = array($e);
                }
                echo "F";
            } finally {
                echo "\n";
            }
        }
        echo count($this->test_cases) . " cases, " . count($errors) . " Failures:\n";
        foreach ($errors as $descr => $errors) {
            echo $descr . "\n";
            foreach ($errors as $e) {
                echo $e . "\n";
            }
            echo "\n";
        }
    }
    public static function strictEqual($actual, $expect)
    {
        if ($actual !== $expect) {
            throw new Exception("actual: $actual, expect: $expect");
        }
        return static::class;
    }
}
$suite = new TestSuite();
$suite->it("word_stat('') == 0", function () {
    assert(word_stat('') == 0);
})
    ->it("将连续数字看作一个单词", function () {
        TestSuite::strictEqual(word_stat('1234567890 1234567890'), 2);
    })
    ->it("连续字母视为一个单词", function () {
        TestSuite::strictEqual(word_stat('Hello World'), 2)
            ::strictEqual(word_stat('Hello  World'), 2)
            ::strictEqual(word_stat('Hello World '), 2)
            ::strictEqual(word_stat(' Hello World'), 2)
            ::strictEqual(word_stat('Hello,World'), 2);
    })
    ->it("数字与字母混合视为一个单词", function () {
        TestSuite::strictEqual(word_stat(("i18n")), 1);
    })
    ->it("每个汉字视为一个单词", function () {
        TestSuite::strictEqual(word_stat('你好，世界'), 4)
            ::strictEqual(word_stat('你好，世界'), 4)
            ::strictEqual(word_stat('〡〢〣'), 3)
            ::strictEqual(word_stat('𰀀'), 1); // CJK Extension G (Unicode 13)
    })
    ->it("每个假名视为一个单词", function () {
        TestSuite::strictEqual(word_stat('こんにちは'), 5)
            ::strictEqual(word_stat('カタナ'), 3);
    })
    ->it("每个谚文视为一个单词", function () {
        TestSuite::strictEqual(word_stat('안녕하세요'), 5);
    })
    ->it("忽略Emoji", function () {
        TestSuite::strictEqual(word_stat('👋🌍'), 0);
    })
    ->it("混合计算", function () {
        TestSuite::strictEqual(word_stat('Hello こんにちは 你好 1234567890'), 9);
    })
    ->run();
