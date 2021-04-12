<?php
function hack_pv($word="View"){
    return '<span class="meta-page-view" data-path="'.parse_url(get_permalink(get_the_ID()))['path'].'">'
    .get_post_views(get_the_ID()) . ' ' 
    . _n($word, $word.'s', get_post_views(get_the_ID()), 'sakurairo')/*次阅读*/ .'</span>';
}
?>