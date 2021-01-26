<?php
/**
 * The template for displaying all single posts.
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/#single-post
 *
 * @package Akina
 */

get_header(); ?>
<?php
wp_enqueue_script('r17', "https://cdn.jsdelivr.net/npm/react@17.0.1/umd/react.production.min.js", array(), null, true);
wp_enqueue_script('r17-dom', "https://cdn.jsdelivr.net/npm/react-dom@17.0.1/umd/react-dom.production.min.js", array(), null, true);
wp_enqueue_script('post',  "https://cdn.jsdelivr.net/gh/kotorik/yukicat-attach@s1.1/dist/post.js", array('r17', 'r17-dom'), null, true);
?>
	<div id="primary" class="content-area">
		<main id="main" class="site-main" role="main">
		<?php
		while ( have_posts() ) : the_post();
			get_template_part( 'tpl/content', 'single' );
			get_template_part('layouts/sidebox');
			get_template_part('layouts/post','nextprev');  
            if(iro_opt('author_profile')){ 
                get_template_part('layouts/authorprofile');
            }
		endwhile; // End of the loop.
		?>
		</main><!-- #main -->
	</div><!-- #primary -->
<?php
get_footer();
