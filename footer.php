<?php
/**
 * The template for displaying the footer.
 *
 * Contains the closing of the #content div and all content after.
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package Sakura
 */

$reception_background = iro_opt('reception_background');
?>
	</div><!-- #content -->
	<?php 
			comments_template('', true); 
	?>
</div><!-- #page Pjax container-->
	<footer id="colophon" class="site-footer" role="contentinfo">
		<div class="site-info" theme-info="Sakurairo v<?php echo IRO_VERSION; ?>">
			<div class="footertext">
				<div class="img-preload">
					<img src="<?php echo iro_opt('load_nextpage_svg'); ?>"><!-- 加载下一部分圈圈 -->
				</div>
				<div class="sakura-icon" style="width:max-content;height:max-content;margin: auto;">
				<svg width="30px" height="30px" t="1682340134496" class="sakura-svg" viewBox="0 0 1049 1024" version="1.1" xmlns="http://www.w3.org/2000/svg" p-id="5240"><path d="M525.58396628 573.34694353s268.83106938-2.62915481 309.36387092 193.24287089l-76.46458293 21.90962291 12.92667757 84.13295086a214.05701289 214.05701289 0 0 1-96.84053193-4.82011663A224.79272784 224.79272784 0 0 1 525.58396628 578.38615666z" fill="#EE9ca7" p-id="5241"></path><path d="M552.75189802 512.4381922s131.45773592-233.7756732 321.63325979-170.89505575L854.2283053 418.66500728l79.31283344 30.89256828a215.59068679 215.59068679 0 0 1-52.58309388 81.50379604 224.57363215 224.57363215 0 0 1-325.35789552-14.67944718z" fill="#EE9ca7" p-id="5242"></path><path d="M508.49446078 494.0341093S317.00435871 306.48774025 426.77156822 139.31731943l69.4535037 38.78003191L547.4935884 109.30113636a214.05701289 214.05701289 0 0 1 65.72886796 71.86356201 225.01182435 225.01182435 0 0 1-98.37420505 310.67844912z" fill="#EE9ca7" p-id="5243"></path><path d="M473.21996809 525.58396628S242.2925454 661.64272234 109.30113636 512.4381922l55.43134521-57.18411482-53.45947909-65.72886795a213.61882069 213.61882069 0 0 1 86.32391269-43.81924506 224.79272784 224.79272784 0 0 1 274.527572 175.27698099z" fill="#EE9ca7" p-id="5244"></path><path d="M481.76472043 566.55496s72.0826585 258.31445093-106.4807652 348.14390364l-40.31370582-68.13892627-78.21735252 34.17901099a212.30424328 212.30424328 0 0 1-20.15685331-94.64956933 224.57363215 224.57363215 0 0 1 241.00584894-219.09622602z" fill="#EE9ca7" p-id="5245"></path></svg>
				</div>
				<p style="color: #666666;"><?php echo iro_opt('footer_info', ''); ?></p>
			</div>
			<div class="footer-device function_area">
					<?php if(iro_opt('footer_yiyan')){ ?>
						<p id="footer_yiyan"></p>
						<?php } ?>
					<span style="color: #b9b9b9;">
						<?php /* 能保留下面两个链接吗？算是我一个小小的心愿吧~ */ ?>
						<?php if (iro_opt('footer_load_occupancy', 'true')): ?>
                        <?php printf(
                            _x( 'Load Time %.3f seconds | %d Query | RAM Usage %.2f MB ', 'footer load occupancy', 'sakurairo' ),
                            timer_stop( 0, 3 ),get_num_queries(),memory_get_peak_usage() / 1024 / 1024);
                        ?>
                        <?php endif; ?>
						<?php if (iro_opt('footer_upyun', 'true')): ?>
							本网站由 <a href="https://www.upyun.com/?utm_source=lianmeng&utm_medium=referral" target="_blank"> <img alt="upyun-logo" src="https://s.nmxc.ltd/sakurairo_vision/@2.6/options/upyun_logo.webp"  style="display:inline-block;vertical-align:middle;width:60px;height:30px;"/> 提供 CDN 加速 / 云存储 服务
                        <?php endif; ?>
                        <br>
						<a href="https://github.com/mirai-mamori/Sakurairo" rel="noopener" target="_blank" id="site-info" >Theme Sakurairo</a><a href="https://fuukei.org/" rel="noopener" target="_blank" id="site-info" > by Fuukei</a> 
					</span>
			</div>
		</div><!-- .site-info -->
	</footer><!-- #colophon -->
	</section><!-- #section -->
	<!-- m-nav-center -->
	<div id="mo-nav">
		<div class="m-avatar">
			<?php 
			$personal_avatar = iro_opt('personal_avatar');
			$iro_logo = iro_opt('iro_logo');
			$ava = iro_opt('personal_avatar') ? $personal_avatar: ($iro_logo ?: iro_opt('vision_resource_basepath','https://s.nmxc.ltd/sakurairo_vision/@2.6/').'series/avatar.webp'); ?>
			<img src="<?php echo $ava ?>">
		</div>
		<div class="m-search">
			<form class="m-search-form" method="get" action="<?php echo home_url(); ?>" role="search">
				<input class="m-search-input" type="search" name="s" placeholder="<?php _e('Search...', 'sakurairo') /*搜索...*/?>" required>
			</form>
		</div>
		<?php wp_nav_menu( array( 'depth' => 2, 'theme_location' => 'primary', 'container' => false ) ); ?>
	</div><!-- m-nav-center end -->
	<button id="moblieGoTop" title="<?=__('Go to top','sakurairo');?>"><i class="fa-solid fa-caret-up fa-lg"></i></button>
  <button id="changskin" title="<?=__('Control Panel','sakurairo');?>" ><i class="fa-solid fa-compass-drafting fa-lg fa-flip" style="--fa-animation-duration: 3s;"></i></button>
	<!-- search start -->
	<form class="js-search search-form search-form--modal" method="get" action="<?php echo home_url(); ?>" role="search">
		<div class="search-form__inner">
		<?php if(iro_opt('live_search')){ ?>
			<div class="micro">
				<input id="search-input" class="text-input" type="search" name="s" placeholder="<?php _e('Want to find something?', 'sakurairo') /*想要找点什么呢*/?>" required>
			</div>
			<div class="ins-section-wrapper">
                <a id="Ty" href="#"></a>
                <div class="ins-section-container" id="PostlistBox"></div>
            </div>
		<?php }else{ ?>
			<div class="micro">
				<p class="micro mb-"><?php _e('Want to find something?', 'sakurairo') /*想要找点什么呢*/?></p>
				<input class="text-input" type="search" name="s" placeholder="<?php _e('Search', 'sakurairo') ?>" required>
			</div>
		<?php } ?>
		</div>
		<div class="search_close"></div>
	</form>
	<!-- search end -->
<?php wp_footer(); ?>
<div class="skin-menu no-select">
<?php if (iro_opt('sakura_widget')) : ?>
	<aside id="iro-widget" class="widget-area" role="complementary">
    <div class="sakura_widget">
	<?php if (function_exists('dynamic_sidebar') && dynamic_sidebar('sakura_widget')) : endif; ?>
	</div>
  </aside>
<?php endif; ?>
        <?php if (iro_opt('widget_shuo', 'true')) : ?>    
        <?php
            $args = array(
                    'post_type' => 'shuoshuo',
                    'post_status' => 'publish',
                    'posts_per_page' => 1
                    );
            $shuoshuo_query = new WP_Query($args);
        ?>
        <?php while ($shuoshuo_query->have_posts()) : $shuoshuo_query->the_post(); ?>
            <div class="footer-shuo">
            <p><?php echo strip_tags(get_the_content()); ?></p>
			<p class="footer-shuotime"><i class="fa-regular fa-clock"></i> <?php the_time('Y/n/j G:i'); ?></p>
            </div>
        <?php endwhile; ?>
            <?php wp_reset_postdata(); ?>
        <?php endif; ?>  
  <div class="theme-controls row-container">
  <?php if (iro_opt('widget_daynight', 'true')): ?>
    <ul class="menu-list">
      <li id="white-bg" title="<?=__('Light Mode','sakurairo');?>" >
        <i class="fa-solid fa-display fa-sm"></i>
      </li><!--Default-->
      <li id="dark-bg" title="<?=__('Dark Mode','sakurairo');?>" >
        <i class="fa-regular fa-moon"></i>
      </li><!--Night-->
    </ul>
  <?php endif; ?>
  <?php if(array_search(1, $reception_background) !== false): ?>
	<ul class="menu-list" title="<?=__('Toggle Page Background Image','sakurairo');?>">
	  <?php
      $bgIcons = [
        ['heart_shaped', 'fa-regular fa-heart', 'diy1-bg'],
        ['star_shaped', 'fa-regular fa-star', 'diy2-bg'],
        ['square_shaped', 'fa-brands fa-delicious', 'diy3-bg'],
        ['lemon_shaped', 'fa-regular fa-lemon', 'diy4-bg']
      ];
      
      foreach ($bgIcons as $bgIcon) {
        if ($reception_background[$bgIcon[0]] == '1') {
          echo '<li id="' . $bgIcon[2] . '">';
          echo '<i class="' . $bgIcon[1] . '"></i>';
          echo '</li>';
        }
      }
      ?>
  </ul>
  <?php endif; ?>
  <?php if (iro_opt('widget_font', 'true')): ?>  
	<div class="font-family-controls row-container">
    <button type="button" class="control-btn-serif selected" title="<?=__('Switch To Font A','sakurairo');?>" data-name="serif">
      <i class="fa-solid fa-font fa-lg"></i>
    </button>
    <button type="button" class="control-btn-sans-serif" title="<?=__('Switch To Font B','sakurairo');?>" data-name="sans-serif">
      <i class="fa-solid fa-bold fa-lg"></i>
    </button>
  </div>
  <?php endif; ?>
  </div>
</div>
<?php if (iro_opt('aplayer_server') != 'off'): ?>
    <div id="aplayer-float" style="z-index: 100;"
	    class="aplayer"
        data-id="<?php echo iro_opt('aplayer_playlistid', ''); ?>"
        data-server="<?php echo iro_opt('aplayer_server'); ?>"
		data-preload="<?php echo iro_opt('aplayer_preload'); ?>"
        data-type="playlist"
        data-fixed="true"
		data-order="<?php echo iro_opt('aplayer_order'); ?>"
        data-volume="<?php echo iro_opt('aplayer_volume', ''); ?>"
        data-theme="<?php echo iro_opt('theme_skin'); ?>">
    </div>
<?php endif; ?>

<!-- 首页波浪特效 -->
<?php if (iro_opt('wave_effects', 'true')): ?>
<link rel="stylesheet" href="<?php global $shared_lib_basepath;echo $shared_lib_basepath?>/css/wave.css">
<?php endif; ?>

<?php
echo iro_opt('footer_addition', '');
?>
</body>
<!-- Particles动效 -->
<?php if (iro_opt('particles_effects', 'true')): ?>
<style>
  #particles-js {
  width: 100%;
  height: 100%;
  position: fixed;
  left: 0;
  right: 0;
  top: 0;
  bottom: 0;
  z-index: -1;
}
</style>
<div id="particles-js"></div>
<script type="application/json" id="particles-js-cfg"><?=iro_opt('particles_json','')?></script>
<?php endif; ?>
</html>
