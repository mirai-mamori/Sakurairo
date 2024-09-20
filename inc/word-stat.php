<?php
if (!function_exists("word_stat")) {

    // CJK和英文字词计数
    function word_stat(string $text)
    {
        $sum = 0;
        // 英文单词：按单词个数计算 排除Lm Lo; 一组连续数字视为一个单词计算
        $res = preg_match_all('/[\d\p{Lu}\p{Ll}\p{Lt}]+/u', $text);
        if ($res !== false) {
            $sum += $res;
        }
        // 按字符个数计算的：汉字、假名、谚文
        $res = preg_match_all('/[\p{Han}\p{Katakana}\p{Hiragana}\p{Hangul}]/u', $text);
        if ($res !== false) {
            $sum += $res;
        }
        return $sum;
    }
}
