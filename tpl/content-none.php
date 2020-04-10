<?php
/**
 * Template part for displaying a message that posts cannot be found.
 *
 * @link https://codex.wordpress.org/Template_Hierarchy
 *
 * @package Akina
 */

?>

<section class="no-results not-found">
	<header class="page-header">
		<h1 class="page-title"><?php _e( 'There is nothing here！', 'sakurairo' ); /*没有找到任何东西*/?></h1>
	</header><!-- .page-header -->

	<div class="page-content">
		<?php
		if ( is_home() && current_user_can( 'publish_posts' ) ) : ?>

			<p><?php printf( wp_kses( __( 'Ready to post your first article? <a href="%1$s">Click here to start</a>.', 'sakurairo' ), array( 'a' => array( 'href' => array() ) ) ), esc_url( admin_url( 'post-new.php' ) ) ); /*准备好发布你的第一篇文章了么？ <a href="%1$s">点击这里开始</a>.*/?></p>

		<?php elseif ( is_search() ) : ?>
           <div class="sorry">
			<p><?php _e( 'Didn\'t find what you want, look at the other ones.', 'sakurairo' ); /*没有找到你想要的，看看其他的吧。*/?></p>
			<div class="sorry-inner">
			<ul class="search-no-reasults">
				<?php 
				$result = $wpdb->get_results("SELECT ID,post_title FROM $wpdb->posts where post_status='publish' and post_type='post' ORDER BY ID DESC LIMIT 0 , 20");
				foreach ($result as $post) {
				setup_postdata($post);
				$postid = $post->ID;
				$title = $post->post_title;
				?>
				<li><a href="<?php echo get_permalink($postid); ?>" title="<?php echo $title ?>"><?php echo $title ?></a> </li>
				<?php } ?>
			</ul>
			</div>
			</div>
			
			<?php else : ?>

			<p><?php _e( 'We didn\'t seem to find what you want. Maybe you can search for it.', 'sakurairo' ); /*我们似乎没有找到你想要的东西. 或许你可以搜索一下试试.*/?></p>
			<?php

		endif; ?>
	</div><!-- .page-content -->
</section><!-- .no-results -->
