<?php
function get_meta_estimate_reading_time()
{
    $words_count = get_post_meta(get_the_ID(), 'post_words_count', true);
    if ($words_count) {
        $ert = round($words_count / 220);
        if ($ert  < 1) {
            return __("Less than 1 minute", "sakurairo");
        } else if ($ert > 60) {
            $hour = round($ert / 60);
            return sprintf(_n('%s Hour', '%s Hours', $hour, "sakurairo"), number_format_i18n($hour));
        } else {
            return sprintf(_n('%s Minute', '%s Minutes', $ert, "sakurairo"), number_format_i18n($ert));
        }
    }
}
