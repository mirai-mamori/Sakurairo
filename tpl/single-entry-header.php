<header class="entry-header">
    <h1 class="entry-title"><?php the_title(); ?></h1>
    <p class="entry-census">
        <?php

        $meta_display = iro_opt("article_meta_show_in_head", array("last_edit_time_relative", "post_views"));
        foreach ($meta_display as $meta_key) {
            $content;
            switch ($meta_key) {
                case "author":
                    require_once get_stylesheet_directory() . '/tpl/meta-author.php';
                    render_author_meta();
                    break;
                case "category":
                    require_once get_stylesheet_directory() . '/tpl/meta-category.php';
                    render_meta_category();
                    break;
                case "comment_count":
                    require_once get_stylesheet_directory() . '/tpl/meta-comments.php';
                    render_meta_comments();
                    break;
                case "post_views":
                    $content = get_post_views(get_the_ID()) . ' ' . _n('View', 'Views', get_post_views(get_the_ID()), 'sakurairo');/*次阅读*/
                    break;
                case "post_words_count":
                    require_once get_stylesheet_directory() . '/tpl/meta-words-count.php';
                    $content = get_meta_words_count();
                    break;
                case "reading_time":
                    require_once get_stylesheet_directory() . '/tpl/meta-ert.php';
                    $content = __("Estimate Reading Time", "sakurairo") . ": " . get_meta_estimate_reading_time();
                    break;
                case "last_edit_time_relative":
                    $content = poi_time_since(strtotime($post->post_date));
                    break;
            }
            if ($content) { ?>
                <span><?= $content ?></span>
        <?php }
        }
        ?>
    </p>
    <hr>
</header>