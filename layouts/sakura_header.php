<?php
//Sakura样式导航栏
?>
<?php 
$nav_style = iro_opt('sakura_nav_style');
$nav_text_logo = iro_opt('nav_text_logo');
?>
<style>
  .site-header.bg,
  .site-header:hover {
    backdrop-filter: blur( <?php echo ($nav_style['blurry']??'10'); //模糊度 ?>px);
  }

  <?php if (!empty($nav_text_logo['font_name'])){ ?>
  .site-branding a{
    font-family: <?php echo $nav_text_logo['font_name']; ?> !important;
  }
  <?php } ?>

  .menu-wrapper .sakura_nav .menu{
    justify-content: <?php echo ($nav_style['distribution']??'right'); //菜单选项所处位置?>;
  }

  nav ul li {
    margin: 0 <?php echo ($nav_style['option_spacing']??'14px'); //选项间距，用于居中和分散时自定义确保美观?>px;
  }

  <?php //sakurairo classic 基于 sakura样式再层叠
  if(iro_opt('choice_of_nav_style') == 'sakurairo') { ?>
  .site-header{
    border-radius: 15px !important;
    width: 95%;
    height: 60px;
    left: 2.5% ;
    top: 2.5% ;
    border: 1.5px solid transparent !important;
    border-bottom: 1.5px solid transparent !important;
  }

  .site-header.bg{
    top: 0!important;
    left: 0!important;
    width: 100% !important;
    border-radius: 0 !important;
    border-bottom: 1.5px solid #FFFFFF !important;
  }

  .site-header:hover{
    top: 2.5%;
  }

  body.dark .site-header:hover {
    border-bottom: solid transparent !important;
  }

  body.dark .site-header.bg{
    border-bottom: 1.5px solid #7d7d7d30 !important;
  }

  @media (max-width: 860px) {
  }
  <?php } ?>
</style>

<header class="site-header no-select" role="banner">

  <div class="openNav no-select">
      <div class="iconflat no-select" style="padding: 30px;">
          <div class="icon"></div>
      </div>
  </div>

  <?php //logo开始
  if (iro_opt('iro_logo') || !empty($nav_text_logo['text'])): ?>
    <div class="site-branding">
      <a href="<?php echo esc_url(home_url('/')); ?>">
        <?php if (iro_opt('iro_logo')): ?>
          <div class="site-title-logo">
            <img alt="<?php echo esc_attr(get_bloginfo('name')); ?>"
              src="<?php echo esc_url(iro_opt('iro_logo')); ?>"
              width="auto" height="auto"
              loading="lazy"
              decoding="async">
          </div>
        <?php endif; ?>
        <?php if (!empty($nav_text_logo['text'])): ?>
          <div class="site-title">
          <?php echo esc_html($nav_text_logo['text']); ?>
          </div>
        <?php endif; ?>
      </a>
    </div>
  <?php endif; //logo结束?>

  <div id="mo-nav" class="menu-wrapper"><?php //手机端导航栏复用，样式应动态调整 ?>
  
  <div class="mo-user-avatar"><?php //移动端结构开始
    global $current_user;
        if (is_user_logged_in()) {
          wp_get_current_user();
          echo '<img alt="m-avatar" src="' . get_avatar_url($current_user->ID, 64) . '">';
        } elseif (iro_opt('unlisted_avatar')) {
          echo '<img alt="m-avatar" src="' . esc_url(iro_opt('unlisted_avatar')) . '">';
        } else {
            echo '<i class="fa-solid fa-circle-user" style="font-size: 40px;"></i>';
        }
      ?>
    </div>
    <?php m_user_menu() //移动端结构结束?>
    
    <?php wp_nav_menu(['depth' => 2, 'theme_location' => 'primary', 'container' => 'nav', 'container_class' => 'sakura_nav']); //菜单?>

    <?php
    if (iro_opt('nav_menu_search') == '1') { //是否开启搜索框?>
      <div class="searchbox js-toggle-search"><i class="fa-solid fa-magnifying-glass"></i></div>
    <?php } ?>

    <?php
    if (iro_opt('cover_switch', true) == true && iro_opt('cover_random_graphs_switch', true) == true): ?>
      <div class="bg-switch" id="bg-next">
        <i class="fa-solid fa-dice" aria-hidden="true"></i>
        <span class="screen-reader-text">
          <?php esc_html_e('Random Background', 'sakurairo'); ?>
        </span>
      </div>
    <?php //仅在主页展示背景切换功能 ?>
    <script>
      function bgImageSwitcher() {
        const bgImageSwitcher = document.getElementById('bg-next');
        if (!bgImageSwitcher) {
          return;
        } else {
          const isHomePage = window.location.pathname === '/';
          const inCustomize = new URLSearchParams(window.location.search).has('customize_theme');
        
        if (isHomePage && (!window.location.search || inCustomize)) {
            bgImageSwitcher.classList.remove('hide-state');
          } else {
            bgImageSwitcher.classList.add('hide-state');
          }
        }
      }
    bgImageSwitcher()
    <?php if (iro_opt('poi_pjax')){ //pjax开启时，页面切换后重新加载?>
      document.addEventListener('pjax:complete', () => {
      bgImageSwitcher();
    });
    <?php } ?>
    </script>
    <?php endif; //选项全在menu-wrapper中，防止bg-switch隐藏宽度变化导致brand缩放?>
  </div>

  <?php
    if (iro_opt('nav_menu_search') == '1') { //搜索框，移动端专用?>
      <div class="searchbox js-toggle-search mo-search"><i class="fa-solid fa-magnifying-glass"></i></div>
  <?php } ?>
  
  <?php header_user_menu(); //用户栏?>

  <script><?php //置顶时添加底色 ?>
    var header = document.querySelector('.site-header');
    window.addEventListener('scroll', function() {
      // 检查位置
      if (window.scrollY > 0) {
        header.classList.add('bg');
      } else {
        if (window.innerWidth < 860) {
          return
        } else {
          header.classList.remove('bg');
        } 
      }
    });
    if (window.innerWidth < 860) {
      header.classList.add('bg');
    }
  </script>

</header>