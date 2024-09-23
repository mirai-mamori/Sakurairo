<?php
/**
 * Template part for displaying posts.
 *
 * @link https://codex.wordpress.org/Template_Hierarchy
 *
 * @package Akina
 */

?>

<?php 
if (iro_opt('author_profile_quote') == '1') {
	$author_description = get_the_author_meta( 'description' ) ? get_the_author_meta( 'description' ) :iro_opt('author_profile_quote_text', 'Carpe Diem and Do what I like');
?>
<?php } ?>

<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
	<?php if(should_show_title()) { ?>
	<div class="Extendfull">
  	<?php the_post_thumbnail('full');
    require get_template_directory() . '/tpl/single-entry-header.php';
    ?>
	</div>
	<?php } ?>
	<!-- .entry-header -->
	<div class="entry-content">
		<?php the_content( '', true ); ?>
		<?php
			wp_link_pages( array(
				'before' => '<div class="page-links">' . __( 'Pages:', 'ondemand' ),
				'after'  => '</div>',
			) );
		?>
	</div><!-- .entry-content -->
	<footer class="post-footer">
    <div class="post-lincenses">
        <a href="https://creativecommons.org/licenses/by-nc-sa/4.0/deed.zh" target="_blank" rel="nofollow">
            <i class="fa-brands fa-creative-commons"></i>
        </a>
    </div>
    <?php the_reward(); ?>
    <section class="author-profile">
        <?php
            $author_id = get_the_author_meta('ID');
            $author_url = esc_url(get_author_posts_url($author_id));
            $author_name = get_the_author();
        ?>
        <div class="info" itemprop="author" itemscope="" itemtype="http://schema.org/Person">
            <a href="<?= $author_url; ?>" class="profile gravatar">
                <img class="fa-spin" style="--fa-animation-duration: 15s;" src="<?php echo get_avatar_profile_url(); ?>" itemprop="image" alt="<?= $author_name; ?>" height="30" width="30">
            </a>
        </div>
        <div class="meta">
            <a href="<?= $author_url; ?>" itemprop="url" rel="author"><?= $author_name; ?></a>						
        </div>
        <?php if($author_description){?>
            <div class="desc">
                <i class="fa-solid fa-feather" aria-hidden="true"></i><?= $author_description ?>
            </div>
        <?php } ?>
    </section>
    <div class="post-modified-time">
        <i class="fa-solid fa-calendar-day" aria-hidden="true"></i><?php _e('Last updated on ', 'sakurairo'); echo get_the_modified_time('Y-m-d'); ?>
    </div>
    <div class="post-tags">
        <?php if ( has_tag() ) { 
            echo '<i class="fa-solid fa-tag" aria-hidden="true"></i> '; 
            the_tags('', ' ', ' ');
        } else { 
            echo '<i class="fa-solid fa-tag" aria-hidden="true"></i> '; 
            _e('Nothing~', 'sakurairo');
        } ?>
    </div>
</footer><!-- .entry-footer -->
</article><!-- #post-## -->
