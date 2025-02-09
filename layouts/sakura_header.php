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

  .menu-wrapper .sakura_nav .menu{
    justify-content: <?php echo (iro_opt('nav_option_distribution')??'right'); //菜单选项所处位置?>;
  }

  nav ul li {
    margin: 0 <?php echo ($nav_style['option_spacing']??'14px'); //选项间距，用于居中和分散时自定义确保美观?>px;
  }
</style>

<header class="site-header no-select" role="banner">

  <?php //logo开始
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
    <?php wp_nav_menu(['depth' => 2, 'theme_location' => 'primary', 'container' => 'nav', 'container_class' => 'sakura_nav']); //菜单?>
  </div>

  <?php
  if (iro_opt('nav_menu_search') == '1') { //是否开启搜索框?>
    <div class="searchbox js-toggle-search"><i class="fa-solid fa-magnifying-glass"></i></div>
  <?php } ?>

  <?php $enable_random_graphs = (bool)iro_opt('cover_switch', true) && (bool)iro_opt('cover_random_graphs_switch', true); //是否允许更换背景
  if ($enable_random_graphs): ?>
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
          if (window.location.pathname === '/' && !window.location.search){
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
  <?php endif; ?>

  <?php header_user_menu(); //用户栏?>

  <script><?php //置顶时添加底色 ?>
    window.addEventListener('scroll', function() {
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