<?php


/**
 * The main template file.
 *
 * This is the most generic template file in a WordPress theme
 * and one of the two required files for a theme (the other being style.css).
 * It is used to display a page when nothing more specific matches a query.
 * E.g., it puts together the home page when no home.php file exists.
 *
 * @link https://codex.wordpress.org/Template_Hierarchy
 *
 * @package Akina
 */
get_header();
?>



<?php if (iro_opt('bulletin_board') == '1') {
	$text = iro_opt('bulletin_text');
?>
	<div class="notice" style="margin-top:60px">
		<?php if (iro_opt('bulletin_board_icon', 'true')) : ?>
			<div class="notice-icon"><?php _e('Notice','sakurairo') ?></div>
		<?php endif; ?>
		<?php if (strlen($text) > 142) { ?>
			<marquee align="middle" behavior="scroll" loop="-1" scrollamount="6" style="margin: 0 8px 0 20px; display: block;" onMouseOut="this.start()" onMouseOver="this.stop()">
				<div class="notice-content"><?php echo $text; ?></div>
			</marquee>
		<?php } else { ?>
			<div class="notice-content"><?php echo $text; ?></div>
		<?php } ?>
	</div>
<?php } ?>

<?php
if (iro_opt('exhibition_area') == '1') {
	if (iro_opt('exhibition_area_style') == 'left_and_right') {
		get_template_part('layouts/feature_v2');
	} else {
		get_template_part('layouts/feature');
	}
}
?>

<div id="primary" class="content-area">
	<main id="main" class="site-main" role="main">
		<h1 class="main-title"> <i class="<?php echo iro_opt('post_area_icon', 'fa-regular fa-bookmark'); ?>" aria-hidden="true"></i> <br> <?php echo iro_opt('post_area_title', '文章'); ?> </h1>
		<?php
		if (have_posts()) :

			if (is_home() && !is_front_page()) : ?>
				<header>
					<h1 class="page-title screen-reader-text"><?php single_post_title(); ?></h1>
				</header>

			<?php
			endif;
			/* Start the Loop */
			if (iro_opt('post_list_style') == 'akinastyle') {
				while (have_posts()) : the_post();
					get_template_part('tpl/content', get_post_format());
				endwhile;
			} else {
				get_template_part('tpl/content', 'thumb');
			}
			?>
		<?php else : get_template_part('tpl/content', 'none');
		endif; ?>
	</main><!-- #main -->
	<?php if (iro_opt('pagenav_style') == 'ajax') { ?>
		<div id="pagination"><?php next_posts_link(__(' Previous', 'sakurairo')); ?></div>
		<div id="add_post"><span id="add_post_time" style="visibility: hidden;" title="<?php echo iro_opt('page_auto_load', ''); ?>"></span></div>
	<?php } else { ?>
		<nav class="navigator">
			<?php previous_posts_link('<i class="fa-solid fa-angle-left"></i>') ?><?php next_posts_link('<i class="fa-solid fa-angle-right"></i>') ?>
		</nav>
	<?php } ?>
</div><!-- #primary -->
<?php
get_footer();
