<?php 
/**
 * AUTHOR PROFILE
 */
if (iro_opt('author_profile') == '1') {
?>
	<section class="author-profile">
		<div class="info" itemprop="author" itemscope="" itemtype="http://schema.org/Person">
			<a href="<?php echo esc_url(get_author_posts_url(get_the_author_meta('ID'))); ?>" class="profile gravatar"><img src="<?php echo get_avatar_profile_url(); ?>" itemprop="image" alt="<?php the_author(); ?>" height="70" width="70"></a>
			<div class="meta">
				<span class="title"><?php esc_html_e('Author', 'akina'); ?></span>	
				<h3 itemprop="name">
					<a href="<?php echo esc_url(get_author_posts_url(get_the_author_meta('ID'))); ?>" itemprop="url" rel="author"><?php the_author(); ?></a>
				</h3>						
			</div>
		</div>
		<hr>
		<p><i class="iconfont icon-write"></i><?php echo get_the_author_meta( 'description' ) ? get_the_author_meta( 'description' ) :iro_opt('author_profile_text', 'Carpe Diem and Do what I like'); ?></p>
	</section>

<?php } ?>