<?php
if (!defined('ABSPATH')) {
  exit;
}
?>
<div class="mo_toc_panel">
  <?php if (iro_opt('mobile_menu_user_avatar', 'true')): ?>
  <div class="mo-avatar">
    <?php
    global $current_user;
    if (is_user_logged_in()) {
      wp_get_current_user();
      echo '<img alt="avatar" src="' . get_avatar_url($current_user->ID, 64) . '">';
    } elseif (iro_opt('unlisted_avatar')) {
      echo '<img alt="avatar" src="' . esc_url(iro_opt('unlisted_avatar')) . '">';
    } else {
        echo '<i class="fa-solid fa-circle-user"></i>';
    }
    ?>
  </div>
  <?php endif; ?>

  <?php 
  global $current_user;
  wp_get_current_user();
  if (is_user_logged_in()) {?>
  <div class="user-menu">
    <div class="user-name">
      <span><?php echo $current_user->display_name; ?></span>
    </div>
    <div class="user-menu-option">
      <?php if (current_user_can('manage_options')) { ?>
        <a href="<?php bloginfo('url'); ?>/wp-admin/" target="_blank"><?php _e('Dashboard', 'sakurairo')/*管理中心*/ ?></a>
        <a href="<?php bloginfo('url'); ?>/wp-admin/post-new.php" target="_blank"><?php _e('New post', 'sakurairo')/*撰写文章*/ ?></a>
      <?php } ?>
      <a href="<?php bloginfo('url'); ?>/wp-admin/profile.php" target="_blank"><?php _e('Profile', 'sakurairo')/*个人资料*/ ?></a>
      <a href="<?php echo wp_logout_url(get_bloginfo('url')); ?>" target="_top" data-no-pjax><?php _e('Sign out', 'sakurairo')/*退出登录*/ ?></a>
    </div>
  </div>

  <?php
  } else {
    global $wp;
    $login_url = iro_opt('exlogin_url') ? iro_opt('exlogin_url') : wp_login_url(iro_opt('login_urlskip') ? '' : add_query_arg($wp->query_vars, home_url($wp->request)));
  ?>

    <div class="mo-user-menu">
      <div class="user-name no-logged">
        <a id="login-link" href="<?= $login_url ?>" data-no-pjax style="font-weight:bold;text-decoration:none"><?php _e('Log in', 'sakurairo')/*登录*/ ?></a>
        <?php if (get_option('users_can_register')) { ?>
          <a style="font-weight:bold;text-decoration:none" href="<?php echo wp_registration_url() ?>"><?php _e('Register') ?></a>
        <?php } ?>
      </div>
    </div>

  <?php } ?>

  <div class="mo-menu-search">
    <form class="search-form" method="get" action="<?php echo esc_url(home_url()); ?>" role="search">
      <input class="search-input" type="search" name="s" placeholder="<?php esc_attr_e('Search...', 'sakurairo'); ?>" required>
    </form>
  </div>

  <?php get_template_part('layouts/sidebox'); //加载目录容器 ?> 

</div>