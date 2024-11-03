<?php

get_header();

?>
<div class="author_info">
    <div class="avatar" data-post-count="<?php echo count_user_posts(get_the_author_meta('ID'), 'post'); ?>">
        <?php echo get_avatar(get_the_author_meta('ID')); ?>
    </div>
    <div class="author-center">
        <h3><?php the_author(); ?></h3>
        <div class="description">
            <?php 
            $description = get_the_author_meta('description');
            echo $description ? nl2br($description) : __("No personal profile set yet", "sakurairo"); 
            ?>
        </div>
    </div>
</div>
<style>
	.author_info .avatar::after {
        content: attr(data-post-count) " \f044"; /* 添加字体图标 */
        font-family: 'FontAwesome'; /* 确保使用FontAwesome字体 */
        position: absolute;
        right: -8px;
        bottom: 16px;
        background-color: #fff;
        padding: 5px;
        border-radius: 5px;
        font-size: 12px;
        color: var(--theme-skin-matching, #505050);
        box-shadow: 0 1px 30px -4px #e8e8e8;
        background: rgba(255, 255, 255, 0.7);
        padding: 2px 8px;
        -webkit-transition: all 0.6s ease-in-out;
        transition: all 0.6s ease-in-out;
        border-radius: 16px;
        border: 1px solid #FFFFFF;
		backdrop-filter: saturate(180%) blur(10px);
		webkit-backdrop-filter: saturate(180%) blur(10px);
    }
</style>
<div id="primary" class="content-area">
    <main id="main" class="site-main" role="main">

        <?php if (have_posts()) : ?>
            <?php get_template_part('tpl/content', 'thumb'); ?>
            <div class="clearer"></div>
        <?php else : ?>
            <?php get_template_part('tpl/content', 'none'); ?>
        <?php endif; ?>

    </main><!-- #main -->
    <?php if (iro_opt('pagenav_style') == 'ajax') : ?>
        <div id="pagination"><?php next_posts_link(__(' Previous', 'sakurairo')); ?></div>
        <div id="add_post"><span id="add_post_time" style="visibility: hidden;" title="<?php echo iro_opt('page_auto_load', ''); ?>"></span></div>
    <?php else : ?>
        <nav class="navigator">
            <?php previous_posts_link('<i class="fa-solid fa-angle-left"></i>'); ?><?php next_posts_link('<i class="fa-solid fa-angle-right"></i>'); ?>
        </nav>
    <?php endif; ?>
</div><!-- #primary -->

<?php
get_footer();
?>