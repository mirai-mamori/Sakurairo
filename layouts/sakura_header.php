<?php
//Sakura样式导航栏
?>
<style>
.site-header {
    width: 100%;
    height: 75px;
	display: flex;
	justify-content: left;
    top: 0;
    left: 0;
    background: 0 0;
    -webkit-transition: all 1s ease;
    transition: all 1s ease;
    position: fixed;
    z-index: 999;
    border-radius: 0px;
	border-bottom: 1.5px solid rgba(0, 0, 0, 0);
}
.site-header.bg,.site-header:hover {
	background: rgba(255, 255, 255, .7);
	border-bottom: 1.5px solid #FFFFFF;
	transition: all 0.6s ease-in-out;
	width: 100%;
	left: 0;
	top: 0;
	border-radius: 0px !important;
	transition: border-bottom 0.3s ease;
	-webkit-transition: all 1s ease;
	transition: all 1s ease;
}
.site-branding{
	border-radius: 0px;
	background: rgba(0, 0, 0, 0);
	border: 0px;
}
.site-branding img{
	max-height: none;
}
.site-title-logo {
    display: flex;
	justify-content: center;
}
.menu-wrapper{
	width: 100%;
}
.site-branding {
  height: 75px;
  line-height: 75px;
  backdrop-filter: none;
  box-shadow: none;
}
.site-branding:hover,.site-title,.site-title:hover {
	background-color: rgba(0, 0, 0, 0);
}
.site-title {
	font-size: 2vw;
	border-radius: 50px;
	transition: all 0.4s ease-in-out;
}
.site-title:hover {
	border-radius: 50px;
	transition: all 0.4s ease-in-out;
	color: var(--theme-skin-matching);
	background-color: rgba(0, 0, 0, 0);
}
.site-title img {
  	margin-top: 17px;
}
.menu-wrapper .menu {
	display: flex;
	justify-content: <?php echo iro_opt('nav_menu_distribution'); ?>;
}
.site-top .lower nav {
<?php if ($nav_menu_display == 'fold') {
	echo "width: 92%;";
} else {
	echo "width: 100%;";
};?>
}
nav ul,nav ul li {
	cursor: default;
}
nav ul li {
	margin: 0 <?php echo iro_opt('menu_option_spacing'); ?>px;
	padding: 10px 0;
	-webkit-transition: all 1s ease;
  	transition: all 1s ease;
}
nav ul li>a:hover:after {
    max-width: 100%;
}
nav .menu {
	animation: fadeInLeft 2s;
}
nav .menu > li .sub-menu{
	white-space: nowrap;
	top: 110%;
}
.sub-menu li a:hover:after{
	max-width: 0%;
}
nav .menu > li .sub-menu li{
	padding: 10px 0;
}
nav ul li a:after {
    content: "";
    display: block;
    position: absolute;
    bottom: -5px;
    height: 4px;
    background-color: var(--theme-skin-matching, #505050);
    width: 100%;
    border-radius: 30px;
    max-width: 0;
    transition: max-width 0.3s cubic-bezier(0.4, 0, 0.2, 1);
}
:after, :before {
    box-sizing: inherit;
}
.menu-wrapper #show-nav {
	margin-top: 18px;
}
.header-user-menu {
  right: -11px;
  top: 44px;
  position: absolute;
  width: 110px;
  background: 0 0;
  visibility: hidden;
  overflow: hidden;
  box-shadow: 0 1px 40px -8px rgba(0, 0, 0, .2);
  border-radius: 15px;
  text-align: center;
  transition: all .5s 0.1s;
  opacity: 0;
  transform: translateY(-20px);
}
.searchbox.js-toggle-search i{
  margin: 17px 0;
  border-radius: 10px !important;
  border: 2px solid rgba(0, 0, 0, 0);
  font-size: 18px;
  font-weight: 900;
}
.searchbox.js-toggle-search i:hover,.bg-switch i:hover {
	color: var(--theme-skin-matching);
    -webkit-transition: all .3s ease-in-out;
    transition: all .3s ease-in-out;
    border: 2px solid var(--theme-skin-matching);
	background-color: rgba(0, 0, 0, 0);
}
.header-user-avatar img{
	max-width: none;
}
@keyframes fadeInLeft {
	0% {
		-moz-transform: translateX(100%);
		-ms-transform: translateX(100%);
		-webkit-transform: translateX(100%);
		transform: translateX(100%);
		opacity: 0
	}
 
	50% {
		-moz-transform: translateX(100%);
		-ms-transform: translateX(100%);
		-webkit-transform: translateX(100%);
		transform: translateX(100%);
		opacity: 0
	}
 
	100% {
		-moz-transform: translateX(0%);
		-ms-transform: translateX(0%);
		-webkit-transform: translateX(0%);
		transform: translateX(0%);
		opacity: 1
	}
}
@media (max-width:860px) {
.site-header {
  height: 60px;
}}
</style>
<header class="site-header no-select" role="banner">
	<!-- Logo Start -->
	<?php
	$nav_text_logo = iro_opt('nav_text_logo');
        if (iro_opt('iro_logo') || !empty($nav_text_logo['text'])): ?>
            <div class="site-branding">
                <a href="<?= esc_url(home_url('/')); ?>">
                    <?php if (iro_opt('iro_logo')): ?>
                        <div class="site-title-logo">
                            <img alt="<?= esc_attr(get_bloginfo('name')); ?>"
                                src="<?= esc_url(iro_opt('iro_logo')); ?>"
                                width="auto" height="auto"
                                loading="lazy"
                                decoding="async">
                        </div>
                    <?php endif; ?>
                    <?php if (!empty($nav_text_logo['text'])): ?>
                        <div class="site-title">
                            <?= esc_html($nav_text_logo['text']); ?>
                        </div>
                    <?php endif; ?>
                </a>
            </div>
        <?php endif;?>
		<!-- LOGO部分 -->
		<!-- 菜单开始 -->
		<div class="menu-wrapper"> <!-- 菜单容器 -->
			<?php $nav_menu_display = iro_opt('nav_menu_display'); //菜单是否展开 ?>
			<?php if ($nav_menu_display == 'fold') { ?>
				<div id="show-nav" class="showNav">
					<div class="line line1"></div>
					<div class="line line2"></div>
					<div class="line line3"></div>
				</div>
			<?php } ?>
				<?php wp_nav_menu(['depth' => 2, 'theme_location' => 'primary', 'container' => 'nav', 'container_class' => 'sakura_nav']); ?>
		</div>
		<!-- 菜单结束 -->
		<?php
		if (iro_opt('nav_menu_search') == '1') { ?>
			<div class="searchbox js-toggle-search"><i class="fa-solid fa-magnifying-glass"></i></div>
		<?php } ?>
		
		<?php header_user_menu(); ?>
	</div>
	<script>
		window.addEventListener('scroll', function () {
			const header = document.querySelector('.site-header');
			// 检查位置
			if (window.scrollY > 0) {
				header.classList.add('bg');
			} else {
				header.classList.remove('bg');
			}
		});
	</script>
</header>