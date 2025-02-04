<?php
//Sakura样式导航栏
?>
<style>
  .site-header {
    gap: 3px;
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

  .site-header.bg,
  .site-header:hover {
    background: rgba(255, 255, 255, 0.7);
    border-bottom: 1.5px solid #FFFFFF;
    width: 100%;
    left: 0;
    top: 0;
    border-radius: 0px !important;
    -webkit-transition: all 1s ease;
    transition: border-bottom 0.3s ease;
    transition: all 1s ease;
  }

  .site-branding {
    border-radius: 0px;
    background: rgba(0, 0, 0, 0);
    border: 0px;
    height: 75px;
    line-height: 75px;
    backdrop-filter: none;
    box-shadow: none;
  }

  .site-branding img {
    max-height: 90px;
    border-radius: 0px;
  }

  .site-title-logo {
    display: flex;
    justify-content: center;
    max-height: none;
  }

  .menu-wrapper {
    width: 100%;
  }

  .site-branding:hover,
  .site-title,
  .site-title:hover {
    background-color: rgba(0, 0, 0, 0);
  }

  .site-title {
    font-size: 38px;
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
    justify-content: <?php echo iro_opt('nav_menu_distribution'); //菜单选项所处位置?>;
  }

  .menu-wrapper .nav_menu_hide {
    display: none;
  }

  .menu-wrapper .nav_menu_display {
    display: flex;
    animation: fadeInLeft 2s;
  }

  .site-header .menu-wrapper nav .menu {
    <?php
    if ($nav_menu_display == 'fold') {
      echo "width: 92%;";
    } else {
      echo "width: 100%;";
    }
    ?>
  }

  nav ul,
  nav ul li {
    cursor: default;
  }

  nav ul li {
    margin: 0 <?php echo iro_opt('menu_option_spacing'); //选项间距，用于居中和分散时自定义确保美观?>px;
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

  nav .menu>li .sub-menu {
    white-space: nowrap;
    top: 110%;
  }

  .sub-menu li a:hover:after {
    max-width: 0%;
  }

  nav .menu>li .sub-menu li {
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

  :after,
  :before {
    box-sizing: inherit;
  }

  .site-header #show-nav {
    margin-bottom: 20px;
    margin-left: 0px;
    margin-right: 8px;
  }

  .header-user-avatar img{
    box-shadow: none;
  }

  .header-user-menu {
    right: -11px;
    top: 44px;
    position: absolute;
    width: 110px;
    background: 0 0;
    visibility: hidden;
    overflow: hidden;
    box-shadow: 0 1px 40px -8px rgba(0, 0, 0, 0.2);
    border-radius: 15px;
    text-align: center;
    transition: all 0.5s 0.1s;
    opacity: 0;
    transform: translateY(-20px);
  }

  .searchbox.js-toggle-search i {
    margin: 17px 0;
    border-radius: 10px !important;
    border: 2px solid rgba(0, 0, 0, 0);
    font-size: 18px;
    font-weight: 900;
  }

  .searchbox.js-toggle-search i:hover,
  .bg-switch i:hover {
    color: var(--theme-skin-matching);
    -webkit-transition: all 0.3s ease-in-out;
    transition: all 0.3s ease-in-out;
    border: 2px solid var(--theme-skin-matching);
    background-color: rgba(0, 0, 0, 0);
  }

  .bg-switch i{
    border: 2px solid rgba(0, 0, 0, 0);
    border-radius: 10px !important;
  }

  .header-user-avatar img {
    max-width: none;
  }

  body.dark .site-header.bg,body.dark .site-header:hover {
    background-color: rgba(38, 38, 38, 0.8) !important;
    border-bottom: 1.5px solid #7d7d7d30;
  }

  body.dark .site-branding {
    background: rgba(0, 0, 0, 0) !important;
    box-shadow: none;
    border: none;
  }

  body.dark .site-header #show-nav .line, body.dark .site-header.bg nav ul li a, 
  body.dark .site-header:hover nav ul li a,
  body.dark .site-header.bg .searchbox.js-toggle-search i, body.dark .site-header.bg .bg-switch i,
  body.dark .site-header:hover .searchbox.js-toggle-search i, body.dark .site-header:hover .bg-switch i{
    color: #CCCCCC !important;
  }

  body.dark .site-header.bg #show-nav .line,body.dark .site-header:hover #show-nav .line{
    background: #CCCCCC;
  }

  body.dark .searchbox.js-toggle-search i:hover, body.dark .bg-switch i:hover {
    color: var(--theme-skin-dark);
    border: 2px solid var(--theme-skin-dark);
  }

  @keyframes fadeInLeft {
    0% {
      -moz-transform: translateX(100%);
      -ms-transform: translateX(100%);
      -webkit-transform: translateX(100%);
      transform: translateX(100%);
      opacity: 0;
    }

    50% {
      -moz-transform: translateX(100%);
      -ms-transform: translateX(100%);
      -webkit-transform: translateX(100%);
      transform: translateX(100%);
      opacity: 0;
    }

    100% {
      -moz-transform: translateX(0%);
      -ms-transform: translateX(0%);
      -webkit-transform: translateX(0%);
      transform: translateX(0%);
      opacity: 1;
    }
  }

  @media (max-width: 860px) {
    .site-header {
      height: 60px;
    }
  }
</style>
<header class="site-header no-select" role="banner">
  <?php //logo开始
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
  <?php endif; //logo结束?>
  <div class="menu-wrapper">
    <?php //菜单开始
    $nav_menu_display = iro_opt('nav_menu_display'); //决定菜单是否展开
    $container_class = 'sakura_nav';
    if ($nav_menu_display == 'fold') {
      $container_class = 'sakura_nav nav_menu_hide';
    }
    ?>
    <?php wp_nav_menu(['depth' => 2, 'theme_location' => 'primary', 'container' => 'nav', 'container_class' => $container_class]); ?>
  </div>
  <?php if ($nav_menu_display == 'fold') { ?>
    <div id="show-nav" class="showNav">
      <div class="line line1"></div>
      <div class="line line2"></div>
      <div class="line line3"></div>
    </div>
  <?php } //菜单结束?>
  <?php
  if (iro_opt('nav_menu_search') == '1') { ?>
    <div class="searchbox js-toggle-search"><i class="fa-solid fa-magnifying-glass"></i></div>
  <?php } ?>

  <?php $enable_random_graphs = (bool)iro_opt('cover_switch', true) && (bool)iro_opt('cover_random_graphs_switch', true);
  if ($enable_random_graphs): ?>
    <div class="bg-switch" id="bg-next">
      <i class="fa-solid fa-dice" aria-hidden="true"></i>
      <span class="screen-reader-text">
        <?php esc_html_e('Random Background', 'sakurairo'); ?>
      </span>
    </div>
  <?php endif; ?>

  <?php header_user_menu(); ?>
  </div>
  <script>
    window.addEventListener('scroll', function() {
      const header = document.querySelector('.site-header');
      // 检查位置
      if (window.scrollY > 0) {
        header.classList.add('bg');
      } else {
        header.classList.remove('bg');
      }
    });
    document.addEventListener('DOMContentLoaded', function() {
      // 监听#show-Nav的点击事件
      const showNavBtn = document.getElementById('show-nav');
      if (showNavBtn) {
        showNavBtn.addEventListener('click', function() {
          const menuElement = document.querySelector('.sakura_nav');
          if (menuElement) {
            if (menuElement.classList.contains('nav_menu_display')) {
              menuElement.classList.replace('nav_menu_display', 'nav_menu_hide');
            } else {
              menuElement.classList.replace('nav_menu_hide', 'nav_menu_display');
            }
          }
        });
      }
    });
  </script>
</header>