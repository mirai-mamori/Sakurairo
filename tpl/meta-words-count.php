<?php
function get_meta_words_count()
{
    $id = get_the_ID();
    $words_count = get_post_meta(get_the_ID(), 'post_words_count', true);
    if(!$words_count) {
        $words_count = count_post_words($id);
    }
    return sprintf(_n("%s Word", "%s Words", $words_count, "sakurairo"), $words_count);
}
