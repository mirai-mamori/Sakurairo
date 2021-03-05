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

$mashiro_logo = iro_opt('mashiro_logo');
$reception_background = iro_opt('reception_background');
?>
	</div><!-- #content -->
	<?php 
			comments_template('', true); 
	?>
</div><!-- #page Pjax container-->
	<footer id="colophon" class="site-footer" role="contentinfo">
		<div class="site-info" theme-info="Sakurairo v<?php echo SAKURA_VERSION; ?>">
			<div class="footertext">
				<div class="img-preload">
					<img src="<?php echo iro_opt('load_nextpage_svg'); ?>"><!-- 加载下一部分圈圈 -->
				</div>
				<?php if (iro_opt('footer_sakura_icon', 'true')): ?>
				<i class="iconfont icon-sakura rotating" style="color: <?php echo iro_opt('theme_skin_matching'); ?>;display:inline-block;font-size:26px"></i>
				<?php endif; ?></p>
				<p style="color: #666666;"><?php echo iro_opt('footer_info', ''); ?></p>
			</div>
			<div class="footer-device">
			<p style="font-family: 'Ubuntu', sans-serif;">
					<span style="color: #b9b9b9;">
						<?php /* 能保留下面两个链接吗？算是我一个小小的心愿吧~ */ ?>
						<?php if (iro_opt('footer_random_word', 'true')): ?>
                        <script type="text/javascript" src="https://api.btstu.cn/yan/api.php?charset=utf-8&encode=js" ></script>
						<div id="yan"><script>text()</script></div>
                        <?php endif; ?></p>
						<?php if (iro_opt('footer_load_occupancy', 'true')): ?>
                        <?php printf(' 耗时 %.3f 秒 | 查询 %d 次 | 内存 %.2f MB',timer_stop( 0, 3 ),get_num_queries(),memory_get_peak_usage() / 1024 / 1024);?>
                        <?php endif; ?></p>
						Theme <a href="https://github.com/mirai-mamori/Sakurairo" target="_blank" id="site-info" >Sakurairo</a>  by <a href="https://iro.tw" target="_blank" id="site-info" >Fuukei</a> 
					</span>
				</p>
			</div>
		</div><!-- .site-info -->
	</footer><!-- #colophon -->
	<div class="openNav no-select">
		<div class="iconflat no-select">	 
			<div class="icon"></div>
		</div>
		<div class="site-branding">
			<?php if (iro_opt('iro_logo')){ ?>
			<div class="site-title">
			<?php }else{ ?>
			<h1 class="site-title"></h1>
			<?php } ?>
		</div>
	</div><!-- m-nav-bar -->
	</section><!-- #section -->
	<!-- m-nav-center -->
	<div id="mo-nav">
		<div class="m-avatar">
			<?php $ava = iro_opt('personal_avatar') ? iro_opt('personal_avatar') :'https://cdn.jsdelivr.net/gh/Fuukei/Public_Repository@latest/vision/ultramarine/avatar.jpg'; ?>
			<img src="<?php echo $ava ?>">
		</div>
		<div class="m-search">
			<form class="m-search-form" method="get" action="<?php echo home_url(); ?>" role="search">
				<input class="m-search-input" type="search" name="s" placeholder="<?php _e('Search...', 'sakurairo') /*搜索...*/?>" required>
			</form>
		</div>
		<?php wp_nav_menu( array( 'depth' => 2, 'theme_location' => 'primary', 'container' => false ) ); ?>
	</div><!-- m-nav-center end -->
	<a class="cd-top faa-float animated "></a>
	<button id="moblieGoTop" title="Go to top"><i class="fa fa-chevron-up" aria-hidden="true"></i></button>
    <button id="changskin" style="bottom: 15px;"><i class="iconfont icon-gear inline-block rotating"></i></button>
	<!-- search start -->
	<form class="js-search search-form search-form--modal" method="get" action="<?php echo home_url(); ?>" role="search">
		<div class="search-form__inner">
		<?php if(iro_opt('live_search')){ ?>
			<div class="micro">
				<i class="iconfont icon-search"></i>
				<input id="search-input" class="text-input" type="search" name="s" placeholder="<?php _e('Want to find something?', 'sakurairo') /*想要找点什么呢*/?>" required>
			</div>
			<div class="ins-section-wrapper">
                <a id="Ty" href="#"></a>
                <div class="ins-section-container" id="PostlistBox"></div>
            </div>
		<?php }else{ ?>
			<div class="micro">
				<p class="micro mb-"><?php _e('Want to find something?', 'sakurairo') /*想要找点什么呢*/?></p>
				<i class="iconfont icon-search"></i>
				<input class="text-input" type="search" name="s" placeholder="<?php _e('Search', 'sakurairo') ?>" required>
			</div>
		<?php } ?>
		</div>
		<div class="search_close"></div>
	</form>
	<!-- search end -->
<?php wp_footer(); ?>
<div class="skin-menu no-select">
<?php if(iro_opt('style_menu_display') == 'full'): ?>
</p>Style
<?php endif; ?>
    <div class="theme-controls row-container">
        <ul class="menu-list">
            <li id="white-bg">
                <i class="fa fa-television" aria-hidden="true"></i>
			</li><!--Default-->
			<?php if($reception_background['heart_shaped'] == '1'): ?>
            <li id="diy1-bg">
			    <i class="fa fa-heart-o" aria-hidden="true"></i>
			</li><!--Diy1-->
			<?php endif; ?>
			<?php if($reception_background['star_shaped'] == '1'): ?>
            <li id="diy2-bg">
                <i class="fa fa-star-o" aria-hidden="true"></i>
			</li><!--Diy2-->
			<?php endif; ?>
			<?php if($reception_background['square_shaped'] == '1'): ?>
            <li id="diy3-bg">
			    <i class="fa fa-delicious" aria-hidden="true"></i>
			</li><!--Diy3-->
			<?php endif; ?>
			<?php if($reception_background['lemon_shaped'] == '1'): ?>
            <li id="diy4-bg">
			    <i class="fa fa-lemon-o" aria-hidden="true"></i>
			</li><!--Diy4-->
			<?php endif; ?>
            <li id="dark-bg">
                <i class="fa fa-moon-o" aria-hidden="true"></i>
            </li><!--Night-->
        </ul>
	</div>
	<?php if(iro_opt('style_menu_display') == 'full'): ?></p>
		Font
    <div class="font-family-controls row-container">
        <button type="button" class="control-btn-serif selected" data-mode="serif" 
                onclick="mashiro_global.font_control.change_font()"><i class="fa fa-font" aria-hidden="true"></i></button>
        <button type="button" class="control-btn-sans-serif" data-mode="sans-serif" 
                onclick="mashiro_global.font_control.change_font()"><i class="fa fa-bold" aria-hidden="true"></i></button>
	</div>
	<?php endif; ?>
</div>
<?php if (iro_opt('sakura_widget')) : ?>
	<aside id="secondary" class="widget-area" role="complementary" style="left: -400px;">
    <div class="heading"><?php _e('Widgets') /*小工具*/ ?></div>
    <div class="sakura_widget">
	<?php if (function_exists('dynamic_sidebar') && dynamic_sidebar('sakura_widget')) : endif; ?>
	</div>
	<div class="show-hide-wrap"><button class="show-hide"><svg xmlns="http://www.w3.org/2000/svg" version="1.1" viewBox="0 0 32 32"><path d="M22 16l-10.105-10.6-1.895 1.987 8.211 8.613-8.211 8.612 1.895 1.988 8.211-8.613z"></path></svg></button></div>
    </aside>
<?php endif; ?>
<?php if (iro_opt('aplayer_server') != 'off'): ?>
    <div id="aplayer-float" style="z-index: 100;"
	    class="aplayer"
        data-id="<?php echo iro_opt('aplayer_playlistid', ''); ?>"
        data-server="<?php echo iro_opt('aplayer_server'); ?>"
        data-type="playlist"
        data-fixed="true"
        data-volume="<?php echo iro_opt('aplayer_volume', ''); ?>"
        data-theme="<?php echo iro_opt('theme_skin'); ?>">
    </div>
<?php endif; ?>

<!-- 飘落动效 -->
<?php if (iro_opt('falling_effects') != 'off'): ?>
<script type="text/javascript" src="https://cdn.jsdelivr.net/gh/Fuukei/Public_Repository@latest/static/js/<?php echo iro_opt('falling_effects'); ?>.js"></script>
<?php endif; ?>

<!-- 首页波浪特效 -->
<?php if (iro_opt('wave_effects', 'true')): ?>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/gh/Fuukei/Public_Repository@latest/static/css/bolang.css">
<?php endif; ?>

<!-- Live2D看板娘 -->
<?php if (iro_opt('live2d_options', 'true')): ?>
<script type="text/javascript" src="https://cdn.jsdelivr.net/gh/<?php echo iro_opt('live2d_custom_user'); ?>/live2d-widget@<?php echo iro_opt('live2d_custom_user_ver'); ?>/autoload.js"></script>
<?php endif; ?>

<!-- logo字体部分 -->
<link rel="stylesheet" href="<?php echo $mashiro_logo['font_link']; ?>" media="all">

<!-- 收缩、展开 -->
<script>jQuery(document).ready(
function(jQuery){
jQuery('.collapseButton').click(function(){
jQuery(this).parent().parent().find('.xContent').slideToggle('slow');
});
});</script>


</body>
</html>
