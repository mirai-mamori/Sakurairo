<?php
/**
 * The template for displaying all pages.
 *
 * This is the template that displays all pages by default.
 * Please note that this is the WordPress construct of pages
 * and that other 'pages' on your WordPress site may use a
 * different template.
 *
 * @link https://codex.wordpress.org/Template_Hierarchy
 *
 * @package Akina
 */

get_header(); ?>
<?php
wp_enqueue_script('r17', "https://cdn.jsdelivr.net/npm/react@17.0.1/umd/react.production.min.js", array(), null, true);
wp_enqueue_script('r17-dom', "https://cdn.jsdelivr.net/npm/react-dom@17.0.1/umd/react-dom.production.min.js", array(), null, true);
wp_enqueue_script('post',  "https://cdn.jsdelivr.net/gh/kotorik/yukicat-attach@s1.4/dist/post.js", array('r17', 'r17-dom'), null, true);
?>
	<div id="primary" class="content-area">
		<main id="main" class="site-main" role="main">

			<?php
			while ( have_posts() ) : the_post();

				get_template_part( 'tpl/content', 'page' );

			endwhile; // End of the loop.
			?>

		</main><!-- #main -->
	</div><!-- #primary -->

<?php
get_footer();
