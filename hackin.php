<?php
function hack_pv($word="View"){
    return '<span class="meta-page-view" data-path="'.get_permalink(get_the_ID()).'">'
    .get_post_views(get_the_ID()) . ' ' 
    . _n($word, $word.'s', get_post_views(get_the_ID()), 'sakurairo')/*次阅读*/ .'</span>';
}
?>