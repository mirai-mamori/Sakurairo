<?php
if (!function_exists('render_meta_views')) {
    function render_meta_views() {
        ?><span><i class="fa-regular fa-eye"></i><?= esc_html(get_post_views(get_the_ID()) . ' ' . _n('Hit', 'Hits', get_post_views(get_the_ID()), 'sakurairo'))/*热度*/ ?></span><?php
    }
}

// @related inc/post-metadata.php
if (!function_exists('render_article_meta')) {
    function render_article_meta() {
        $article_meta_display_options = iro_opt("article_meta_displays", array("post_views", "comment_count", "category"));
        foreach ($article_meta_display_options as $key) {
            switch ($key) {
                case "author":
                    require_once get_stylesheet_directory() . '/tpl/meta-author.php';
                    render_author_meta();
                    break;
                case "category":
                    require_once get_stylesheet_directory() . '/tpl/meta-category.php';
                    echo get_meta_category_html();
                    break;
                case "comment_count":
                    require_once get_stylesheet_directory() . '/tpl/meta-comments.php';
                    render_meta_comments();
                    break;
                case "post_views":
                    render_meta_views();
                    break;
                case "post_words_count":
                    require_once get_stylesheet_directory() . '/tpl/meta-words-count.php';
                    $str = get_meta_words_count();
                    if ($str) {
                        ?><span><i class="fa-regular fa-pen-to-square"></i><?= esc_html($str) ?></span><?php
                    }
                    break;
                case "reading_time":
                    require_once get_stylesheet_directory() . '/tpl/meta-ert.php';
                    $str = get_meta_estimate_reading_time();
                    if ($str) {
                        ?><span title="<?= esc_attr(__("Estimate Reading Time", "sakurairo")) ?>"><i class="fa-solid fa-hourglass"></i><?= esc_html($str) ?></span><?php
                    }
                default:
            }
        }
    }
}

if (!function_exists('get_post_cover_html')) {
    function get_post_cover_html() {
        global $post;
        $use_as_thumb = get_post_meta(get_the_ID(), 'use_as_thumb', true); //'true','only',(default)
        $cover_type = ($use_as_thumb == 'true' || $use_as_thumb == 'only') ? get_post_meta(get_the_ID(), 'cover_type', true) : '';
        $cover_html = "";
        switch ($cover_type) {
            case 'hls':
                $video_cover = get_post_meta(get_the_ID(), 'video_cover', true);
                $cover_html = '<video class="hls" poster="' . esc_url(iro_opt('load_out_svg')) . '#lazyload-blur" src="' . esc_url($video_cover) . '" loop muted="true" disablePictureInPicture disableRemotePlayback playsinline>'
                    . __('Your browser does not support HTML5 video.', 'sakurairo')
                    . '</video>';
                break;
            case 'normal':
                $video_cover = get_post_meta(get_the_ID(), 'video_cover', true);
                $cover_html = '<video class="lazyload" poster="' . esc_url(iro_opt('load_out_svg')) . '#lazyload-blur" data-src="' . esc_url($video_cover) . '" autoplay loop muted="true" disablePictureInPicture disableRemotePlayback playsinline>'
                    . __('Your browser does not support HTML5 video.', 'sakurairo')
                    . '</video>';
                break;
            default:
                $post_img = '';
                if (has_post_thumbnail()) {
                    $post_thumbnail_id = get_post_thumbnail_id($post->ID);
                    $large_image_url = wp_get_attachment_image_src($post_thumbnail_id, 'large');
                    if ($large_image_url == false) {
                        $large_image_url = wp_get_attachment_image_src($post_thumbnail_id, 'medium');
                        if ($large_image_url == false) {
                            $large_image_url = wp_get_attachment_image_src($post_thumbnail_id);
                            if ($large_image_url == false) {
                                $post_img = DEFAULT_FEATURE_IMAGE();
                            }
                        }
                    }
                    $post_img = $large_image_url[0] ?? DEFAULT_FEATURE_IMAGE('th');
                } else {
                    $post_img = DEFAULT_FEATURE_IMAGE('th');
                }
                $cover_html = '<img alt="post_img" class="lazyload" src="' . esc_url(iro_opt('load_out_svg')) . '#lazyload-blur" data-src="' . esc_url($post_img) . '"/>';
                break;
        }
        return $cover_html;
    }
}

        // Determine post type
        $post_type = get_post_type();
        if ($post_type == 'shuoshuo') {
            // shuoshuo 样式
            $cover_html = get_post_cover_html();
            $emotion = get_post_meta(get_the_ID(), 'emotion', true);
            if (empty($emotion)) {
                $emotion = '\f86d'; // 默认值
            }
            $emotion_color = get_post_meta(get_the_ID(), 'emotion_color', true);
            if (empty($emotion_color)) {
                $emotion_color = iro_opt('theme_skin_matching'); 
            }
            $unique_id = 'shuoshuo-' . get_the_ID();
            ?>
            <article class="shuoshuo-item" id="<?php echo esc_attr($unique_id); ?>">
                <div class="shuoshuo-content-wrapper">
                    <div class="shuoshuo-avatar">
                        <a href="<?php echo esc_url(get_author_posts_url(get_the_author_meta('ID'))); ?>">
                            <img src="<?php echo esc_url(get_avatar_profile_url(get_the_author_meta('ID'))); ?>" class="avatar avatar-48" width="48" height="48">
                        </a>
                        <style>
                            #<?php echo esc_attr($unique_id); ?> .shuoshuo-avatar::after {
                                content: "<?php echo esc_attr($emotion); ?>";
                                font-family:'FontAwesome';
                                position: absolute;
                                bottom: 0;
                                right: -3px;
                                background-color: <?php echo esc_attr($emotion_color); ?>;
                                color: #fff;
                                padding: 3px 6px;
                                border-radius: 30px;
                                font-size: 12px;
                            }
                        </style>
                    </div>
                    <div class="shuoshuo-wrapper">
                        <div class="shuoshuo-meta">
                            <span class="shuoshuo-author-name">
                                <a href="<?php echo esc_url(get_author_posts_url(get_the_author_meta('ID'))); ?>">
                                    <?php the_author(); ?>
                                </a>
                            </span>
                            <span class="shuoshuo-title"><h3><?php the_title(); ?> </h3></span>
                            <div class="shuoshuo-info">
                                <?php if (get_comments_number() > 0) : ?>
                                    <span class="shuoshuo-comments"><i class="fa-regular fa-comment"></i> <?php comments_number('0', '1', '%'); ?> </span>
                                <?php endif; ?>
                                <span class="shuoshuo-date"><i class="fa-regular fa-clock"> </i> <?php the_time('Y-m-d H:i'); ?> </span>
                            </div>
                        </div>
                        <a href="<?php the_permalink(); ?>" class="shuoshuo-content-link">
                            <div class="shuoshuo-content">
                                <?php the_content(); ?>
                            </div>
                        </a>
                    </div>
                </div>
            </article>
            <?php
        } else {
            // 原文章样式
            $cover_html = get_post_cover_html();

            // 摘要字数限制
            $ai_excerpt = get_post_meta($post->ID, POST_METADATA_KEY, true);
            $excerpt = has_excerpt();
            ?>
            <article class="post post-list-thumb" itemscope="" itemtype="http://schema.org/BlogPosting">
                <div class="post-thumb">
                    <a href="<?php the_permalink(); ?>">
                        <?php echo $cover_html; ?>
                    </a>
                </div><!-- thumbnail-->
                <div class="post-date">
                    <i class="fa-regular fa-clock"></i><?= esc_html(poi_time_since(strtotime($post->post_date))) ?>
                    <?php if (is_sticky()) : ?>
                        &nbsp;<div class="post-top"><i class="fa-solid fa-chess-queen"></i><?php _e("Sticky", "sakurairo") ?></div>
                    <?php endif ?>
                </div>
                <div class="post-meta">
                    <?php render_article_meta() ?>
                </div>
                <?php $title_style = get_post_meta(get_the_ID(), 'title_style', true); ?>
                <div class="post-title" style="<?php echo esc_attr($title_style); ?>">
                    <a href="<?php the_permalink(); ?>">
                        <h3><?php the_title(); ?></h3>
                    </a>
                </div>
                <div class="post-excerpt">
                    <?php if (!empty($ai_excerpt) && empty($excerpt)) { ?>
                        <div class="ai-excerpt-tip"><i class="fa-solid fa-atom"></i><?php _e("AI Excerpt", "sakurairo") ?></div>
                    <?php } ?>
                    <?php if (empty($ai_excerpt)) { ?>
                        <div class="ai-excerpt-tip"><i class="fa-solid fa-bars-staggered"></i><?php _e("Excerpt", "sakurairo") ?></div>
                    <?php } ?>
                    <?php the_excerpt() ?>
                </div>
            </article>
            <?php
        }