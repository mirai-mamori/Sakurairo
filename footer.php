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
  <?php comments_template('', true); ?>
</div><!-- #page Pjax container-->
  <footer id="colophon" class="site-footer" role="contentinfo">
    <div class="site-info" theme-info="Sakurairo v<?php echo esc_html(IRO_VERSION); ?>">
      <div class="img-preload" style="display:none">
        <img alt="loading_svg" src="<?php echo esc_url(iro_opt('load_nextpage_svg')); ?>">
      </div>
      <div class="footer-content">
          <?php if (iro_opt('footer_yiyan')): ?>
            <p id="footer_yiyan" class="hitokoto"></p>
          <?php endif; ?>
          <?php if (!empty(iro_opt('footer_info', ''))): ?>
            <p class="footer_info"><?php echo iro_opt('footer_info', ''); ?></p>
          <?php endif; ?>
            <?php if (iro_opt('footer_load_occupancy', 'true')): ?>
              <p class="site-stats">
                <?php printf(
                  esc_html__('Load Time %.3f seconds | %d Query | RAM Usage %.2f MB ', 'sakurairo'),
                  timer_stop(0, 3), get_num_queries(), memory_get_peak_usage() / 1024 / 1024
                ); ?>
              </p>
          <?php endif; ?>
          
          <?php if (iro_opt('footer_upyun', 'true')): ?>
            <p class="cdn-provider">
              <span>本网站由</span>
              <a href="https://www.upyun.com/?utm_source=lianmeng&utm_medium=referral" target="_blank">
                <img alt="upyun-logo" src="https://s.nmxc.ltd/sakurairo_vision/@3.0/options/upyun_logo.webp" />
              </a>
              <span>提供 CDN 加速 / 云存储 服务</span>
            </p>
          <?php endif; ?>
        </div>
      
      <div class="theme-info">
          <?php if (iro_opt('footer_sakura', 'true')): ?>
            <div class="sakura-icon">
              <svg width="30px" height="30px" t="1682340134496" class="sakura-svg" viewBox="0 0 1049 1024" version="1.1" xmlns="http://www.w3.org/2000/svg" p-id="5240">
                <path d="M525.58396628 573.34694353s268.83106938-2.62915481 309.36387092 193.24287089l-76.46458293 21.90962291 12.92667757 84.13295086a214.05701289 214.05701289 0 0 1-96.84053193-4.82011663A224.79272784 224.79272784 0 0 1 525.58396628 578.38615666z" fill="#EE9ca7" p-id="5241"></path>
                <path d="M552.75189802 512.4381922s131.45773592-233.7756732 321.63325979-170.89505575L854.2283053 418.66500728l79.31283344 30.89256828a215.59068679 215.59068679 0 0 1-52.58309388 81.50379604 224.57363215 224.57363215 0 0 1-325.35789552-14.67944718z" fill="#EE9ca7" p-id="5242"></path>
                <path d="M508.49446078 494.0341093S317.00435871 306.48774025 426.77156822 139.31731943l69.4535037 38.78003191L547.4935884 109.30113636a214.05701289 214.05701289 0 0 1 65.72886796 71.86356201 225.01182435 225.01182435 0 0 1-98.37420505 310.67844912z" fill="#EE9ca7" p-id="5243"></path>
                <path d="M473.21996809 525.58396628S242.2925454 661.64272234 109.30113636 512.4381922l55.43134521-57.18411482-53.45947909-65.72886795a213.61882069 213.61882069 0 0 1 86.32391269-43.81924506 224.79272784 224.79272784 0 0 1 274.527572 175.27698099z" fill="#EE9ca7" p-id="5244"></path>
                <path d="M481.76472043 566.55496s72.0826585 258.31445093-106.4807652 348.14390364l-40.31370582-68.13892627-78.21735252 34.17901099a212.30424328 212.30424328 0 0 1-20.15685331-94.64956933 224.57363215 224.57363215 0 0 1 241.00584894-219.09622602z" fill="#EE9ca7" p-id="5245"></path>
              </svg>
            </div>
          <?php endif; ?>
        <a href="https://github.com/mirai-mamori/Sakurairo" rel="noopener" target="_blank">Theme Sakurairo</a>
        <a href="https://docs.fuukei.org/" rel="noopener" target="_blank">by Fuukei</a>
      </div>
    </div><!-- .site-info -->
  </footer><!-- #colophon -->
  </section><!-- #section -->
  
  <button id="moblieGoTop" title="<?php esc_attr_e('Go to top', 'sakurairo'); ?>"><i class="fa-solid fa-caret-up fa-lg"></i></button>
  <button id="changskin" title="<?php esc_attr_e('Control Panel', 'sakurairo'); ?>"><i class="fa-solid fa-compass-drafting fa-lg fa-flip"></i></button>

  <!-- search start -->
  <dialog class="dialog-search-form">
    <form class="js-search search-form" method="get" action="<?php echo esc_url(home_url()); ?>" role="search">
      <div class="search-input">
        <input id="search-input" class="text-input" type="search" name="s" placeholder="<?php esc_attr_e('Want to find something?', 'sakurairo'); ?>" required>
      </div>
      <?php if (iro_opt('live_search')): ?>
        <div class="ins-section-wrapper">
          <a id="Ty" href="#"></a>
          <div class="ins-section-container" id="PostlistBox"></div>
        </div>
      <?php endif; ?>
      <div class="search_close"></div>
    </form>
  </dialog>
  <!-- search end -->

<?php wp_footer(); ?>
<div class="skin-menu no-select">
  <?php if (iro_opt('sakura_widget')): ?>
    <aside id="iro-widget" class="widget-area" role="complementary">
      <div class="sakura_widget">
        <?php if (function_exists('dynamic_sidebar') && dynamic_sidebar('sakura_widget')): endif; ?>
      </div>
    </aside>
  <?php endif; ?>
  <div class="theme-controls row-container">
    <?php if (iro_opt('widget_daynight', 'true')): ?>
      <ul class="menu-list">
        <li id="white-bg" title="<?php esc_attr_e('Light Mode', 'sakurairo'); ?>">
          <i class="fa-regular fa-sun"></i>
        </li><!--Default-->
        <li id="dark-bg" title="<?php esc_attr_e('Dark Mode', 'sakurairo'); ?>">
          <i class="fa-regular fa-moon"></i>
        </li><!--Night-->
      </ul>
    <?php endif; ?>
    <?php if (array_search(1, $reception_background) !== false): ?>
      <ul class="menu-list" title="<?php esc_attr_e('Toggle Page Background Image', 'sakurairo'); ?>">
        <?php
        $bgIcons = [
          ['heart_shaped', 'fa-regular fa-heart', 'diy1-bg'],
          ['star_shaped', 'fa-regular fa-star', 'diy2-bg'],
          ['square_shaped', 'fa-brands fa-delicious', 'diy3-bg'],
          ['lemon_shaped', 'fa-regular fa-lemon', 'diy4-bg']
        ];
        
        foreach ($bgIcons as $bgIcon) {
          if ($reception_background[$bgIcon[0]] == '1') {
            echo '<li id="' . esc_attr($bgIcon[2]) . '">';
            echo '<i class="' . esc_attr($bgIcon[1]) . '"></i>';
            echo '</li>';
          }
        }
        ?>
      </ul>
    <?php endif; ?>
    <?php if (iro_opt('widget_font', 'true')): ?>  
      <div class="font-family-controls row-container">
        <button type="button" class="control-btn-serif selected" title="<?php esc_attr_e('Switch To Font A', 'sakurairo'); ?>" data-name="serif">
          <i class="fa-solid fa-font fa-lg"></i>
        </button>
        <button type="button" class="control-btn-sans-serif" title="<?php esc_attr_e('Switch To Font B', 'sakurairo'); ?>" data-name="sans-serif">
          <i class="fa-solid fa-bold fa-lg"></i>
        </button>
      </div>
    <?php endif; ?>
  </div>
</div>
<?php if (iro_opt('aplayer_server') != 'off'): ?>
  <div id="aplayer-float" style="z-index: 100;" class="aplayer"
    data-id="<?php echo esc_attr(iro_opt('aplayer_playlistid', '')); ?>"
    data-server="<?php echo esc_attr(iro_opt('aplayer_server')); ?>"
    data-preload="<?php echo esc_attr(iro_opt('aplayer_preload')); ?>"
    data-type="playlist"
    data-fixed="true"
    data-order="<?php echo esc_attr(iro_opt('aplayer_order')); ?>"
    data-volume="<?php echo esc_attr(iro_opt('aplayer_volume', '')); ?>"
    data-theme="<?php echo esc_attr(iro_opt('theme_skin')); ?>">
  </div>
<?php endif; ?>

<?php echo iro_opt('footer_addition', ''); ?>
</body>
<?php if (iro_opt("reception_background_blur",false)): // 使用独立遮罩，防止大面积子元素fixed等定位方式失效?>
  <div class="background_blur current_blur"></div>
  <style>
    .background_blur {
      position: fixed;
      top: 0;
      left: 0;
      height: 100vh;
      width: 100vw;
      transition: none;
      pointer-events: none;
      z-index: 0;
    }
    .current_blur {
      -webkit-backdrop-filter: saturate(120%) blur(8px);
      backdrop-filter: saturate(120%) blur(8px);
    }
  </style>
  <?php //site wrapper会被pjax刷新导致目标丢失，所以放function?>
    <script>
      let blur_object_on_page = document.querySelector(".background_blur");
      function switch_blur_object (){
        let blur_object_at_home = document.querySelector(".site.wrapper");
        if (_iro.land_at_home) {
          blur_object_on_page.classList.remove("current_blur");
          blur_object_at_home.classList.add("current_blur");
        } else {
          blur_object_on_page.classList.add("current_blur");
          blur_object_at_home.classList.remove("current_blur");
        }
      }
      switch_blur_object();
      document.addEventListener("pjax:complete",switch_blur_object);
    </script>
<?php endif; ?>
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
      z-index: 0;
    }
    #main-container {
      position: relative;
      z-index: 1;
    }
  </style>
  <div id="particles-js"></div>
  <script type="application/json" id="particles-js-cfg"><?php echo iro_opt('particles_json', ''); ?></script>
  <?php endif; ?>
</html>