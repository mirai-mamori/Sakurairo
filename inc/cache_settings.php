<?php
// 缓存设置页

add_action('admin_menu', function(){
    add_submenu_page(
        null,
        __('Cache Settings','sakurairo'),
        __('Cache Settings','sakurairo'),
        'manage_options',
        'sakurairo_cache_setting',
        'sakurairo_cache_page',
    );
});

function sakurairo_cache_page() {
    // 获取缓存值
    $bangumi_cache = get_transient('bangumi_cache');
    $steam_cache = get_transient('steam_cache');

    $bangumi_duration = get_transient('bangumi_cache_duration');
    if ($bangumi_duration === false) {
         $bangumi_duration = 0;
    }
    $steam_duration = get_transient('steam_cache_duration');
    if ($steam_duration === false) {
         $steam_duration = 0;
    }

    // 过期时间
    $bangumi_expire_time = get_transient('bangumi_cache_expire') ?: 0;
    $steam_expire_time   = get_transient('steam_cache_expire') ?: 0;

    // 剩余时间
    $bangumi_remaining = ($bangumi_expire_time > time()) ? $bangumi_expire_time - time() : 0;
    $steam_remaining = ($steam_expire_time > time()) ? $steam_expire_time - time() : 0;

    ?>
    <?php if (isset($_GET['updated'])) : ?>
        <div class="updated notice is-dismissible"><p><?php echo __('Cache Updated!','sakurairo'); ?></p></div>
    <?php endif; ?>

    <h1><?php __('Cache Settings','sakurairo'); ?></h1>
    <h2><?php echo __('Cache settings','sakurairo') ?></h2>
    <p><?php echo __('If your server network environment is poor, you can click the link below to manually obtain the response content and fill it in','sakurairo') ?></p>
    <p><?php echo __('If the content is empty or incorrect, the server will try to automatically pull it','sakurairo') ?></p>
    <p><?php echo __('The link target will be automatically updated after the relevant settings are saved','sakurairo') ?></p>
    <p><?php echo __('The unit is seconds. And it will take effect permanently when it was set to 0.','sakurairo') ?></p>
    <p><a href="./admin.php?iro_act=bangumi" target="_blank">Bangumi</a> | <a href="./admin.php?iro_act=mal" target="_blank">MAL</a> | <a href="./admin.php?iro_act=steam_library" target="_blank">SteamLibrary</a> </p>
    <div class="wrap">
        <form method="post" action="<?php echo admin_url('admin-post.php'); ?>">
            <?php wp_nonce_field('sakurairo_cache_update', 'sakurairo_cache_update'); ?>
            <input type="hidden" name="action" value="sakurairo_cache_setting_update">
            <table class="form-table">
                <tr valign="top">
                    <th scope="row"><?php echo __('Cache expire time','sakurairo') ?></th>
                    <td>
                        <input type="number" name="bangumi_time" class = "remaining" value="<?php echo esc_attr($bangumi_remaining); ?>" />
                    </td>
                </tr>
                <tr valign="top">
                    <th scope="row"><?php echo __('Bangumi Cache','sakurairo') ?></th>
                    <td>
                        <textarea name="bangumi_content" rows="10" cols="50"><?php echo esc_textarea($bangumi_cache); ?></textarea>
                    </td>
                </tr>
                <tr valign="top">
                    <th scope="row"><?php echo __('Cache expire time','sakurairo') ?></th>
                    <td>
                        <input type="number" name="steam_time" class = "remaining" value="<?php echo esc_attr($steam_remaining); ?>" />
                    </td>
                </tr>
                <tr valign="top">
                    <th scope="row"><?php echo __('Steam Cache','sakurairo') ?></th>
                    <td>
                        <textarea name="steam_content" rows="10" cols="50"><?php echo esc_textarea($steam_cache); ?></textarea>
                    </td>
                </tr>
            </table>
            <?php submit_button(); ?>
        </form>
    </div>
    <script>
        document.addEventListener("DOMContentLoaded", function () {
            let inputs = document.querySelectorAll("input.remaining");
            
            function updateCountdown() {
                inputs.forEach(input => {
                    if (document.activeElement === input) return;

                    let value = parseInt(input.value, 10);

                    if (input.dataset.expired === "true") return;

                    if (isNaN(value) || value <= 0) {
                        input.value = 0;
                        input.dataset.expired = "true";
                    } else {
                        input.value = value - 1;
                    }
                });
            }

            setInterval(updateCountdown, 1000);
        });
    </script>
    <?php
}

function sakurairo_cache_setting_update() {
    if (isset($_POST['submit'])) {
        // 检查权限
        if (!current_user_can('manage_options')) {
            return;
        }

        // 验证nonce
        check_admin_referer('sakurairo_cache_update', 'sakurairo_cache_update');

        // 获取数据
        $bangumi_duration = isset($_POST['bangumi_time']) ? intval($_POST['bangumi_time']) : 0;
        $bangumi_content  = isset($_POST['bangumi_content']) ? sanitize_textarea_field( wp_unslash($_POST['bangumi_content']) ) : '';
        $steam_duration   = isset($_POST['steam_time'])   ? intval($_POST['steam_time'])   : 0;
        $steam_content    = isset($_POST['steam_content'])  ? sanitize_textarea_field( wp_unslash($_POST['steam_content']) ) : '';
        
        $bangumi_expire_time = time() + $bangumi_duration;
        $steam_expire_time   = time() + $steam_duration;

        // 设置缓存
        set_transient('bangumi_cache', $bangumi_content, $bangumi_duration);
        set_transient('bangumi_cache_expire', $bangumi_expire_time, $bangumi_duration);
        set_transient('bangumi_cache_duration', $bangumi_duration, DAY_IN_SECONDS * 30);

        set_transient('steam_cache', $steam_content, $steam_duration);
        set_transient('steam_cache_expire', $steam_expire_time, $steam_duration);
        set_transient('steam_cache_duration', $steam_duration, DAY_IN_SECONDS * 30);

        // 重定向
        wp_redirect(add_query_arg(['page' => 'sakurairo_cache_setting', 'updated' => 'true'], admin_url('admin.php')));
        exit;
    }
}
add_action('admin_post_sakurairo_cache_setting_update', 'sakurairo_cache_setting_update');

function auto_update_cache($name, $content) {
    $name = $name;
    $content = $content;
    $duration = 2592000;

    set_transient($name, $content, $duration);

    $expire_time = time() + $duration;
    set_transient($name.'_expire', $expire_time, $duration);

    set_transient($name.'_duration', $duration, DAY_IN_SECONDS * 30);
}