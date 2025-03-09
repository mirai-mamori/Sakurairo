<?php
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}
?>

<div class="mo_toc_panel">
    <?php // 用户栏 ?>
    <div class="mo-user-options">
        <?php
        if ( is_user_logged_in() ) { // 已登录
            global $current_user;
            wp_get_current_user();
                ?>

                <div class="mo-avatar">
                    <img alt="avatar" src="<?php echo get_avatar_url( $current_user->ID, 64 ); ?>">
                </div>

                <div class="mo-user-menu">

                    <div class="mo-user-name">
                        <span><?php echo esc_html( $current_user->display_name ); ?></span>
                    </div>

                    <div class="user-menu-option">

                        <?php if ( current_user_can( 'manage_options' ) ) : ?>
                            <a href="<?php echo esc_url( get_admin_url() ); ?>" target="_blank"><?php _e( 'Dashboard', 'sakurairo' ); /*管理中心*/ ?></a>
                            <a href="<?php echo esc_url( admin_url( 'post-new.php' ) ); ?>" target="_blank"><?php _e( 'New post', 'sakurairo' ); /*撰写文章*/ ?></a>

                        <?php endif; ?>

                        <a href="<?php echo esc_url( get_edit_profile_url( $current_user->ID ) ); ?>" target="_blank"><?php _e( 'Profile', 'sakurairo' ); /*个人资料*/ ?></a>
                        <a href="<?php echo esc_url( wp_logout_url( home_url() ) ); ?>" target="_top" data-no-pjax><?php _e( 'Sign out', 'sakurairo' ); /*退出登录*/ ?></a>
                    
                    </div>

                </div>

            <?php
        } else {
            if (!iro_opt( 'hide_login_portal', false ) && iro_opt('nav_user_menu',true) ) { // 未登录，开启用户栏且未开启隐藏入口?>
                <div class="mo-avatar">
                    <?php if ( iro_opt( 'unlisted_avatar' ) ) { ?>

                        <img alt="avatar" src="<?php echo esc_url( iro_opt( 'unlisted_avatar' ) ); ?>">
                        
                        <?php } else { ?>

                        <i class="fa-solid fa-circle-user"></i>

                    <?php } ?>
                    
                </div>

                <div class="mo-user-menu">
                    <div class="mo-user-name no-logged">

                        <?php
                        global $wp;
                        $login_url = wp_login_url( iro_opt( 'login_urlskip' ) ? '' : add_query_arg( $wp->query_vars, home_url( $wp->request ) ) );
                        ?>

                        <a id="login-link" href="<?php echo esc_url( $login_url ); ?>" data-no-pjax style="font-weight:bold;text-decoration:none">
                            <?php _e( 'Log in', 'sakurairo' ); ?>
                        </a>

                        <?php if ( get_option( 'users_can_register' ) ) : ?>
                            <a style="font-weight:bold;text-decoration:none" href="<?php echo esc_url( wp_registration_url() ); ?>">
                                <?php _e( 'Register' ); ?>
                            </a>
                        <?php endif; ?>

                    </div>
                </div>
            <?php
            }
        }
        ?>
    </div>
    <?php // 目录区域 ?>
    <div class="mo_toc"></div>
</div>