<?php
/**
 * Module: 归档缓存手动刷新
 *
 * - 仅管理员可见「刷新归档」按钮（游客不可见）
 * - 按钮通过 personal_archive_page_footer 输出在归档模板内容区内
 *   （不要挂在 wp_footer：本主题 Pjax 局部刷新时不会重新跑 wp_footer）
 * - 访问 ?flush_archive=1 时删除 time_archive transient 并回跳
 */

if (!defined('ABSPATH')) {
    exit;
}

add_action('template_redirect', function () {
    if (!isset($_GET['flush_archive'])) {
        return;
    }
    if (!current_user_can('manage_options')) {
        return;
    }
    delete_transient('time_archive');
    delete_transient('sakurairo_site_stats');
    wp_safe_redirect(remove_query_arg('flush_archive'));
    exit;
}, 5);

add_action('personal_archive_page_footer', function () {
    if (!current_user_can('manage_options')) {
        return;
    }
    $url = esc_url(add_query_arg('flush_archive', '1'));
    ?>
    <div style="text-align:center;padding:24px 0 8px;">
        <a href="<?php echo $url; ?>"
           style="display:inline-block;padding:6px 18px;border-radius:16px;font-size:12px;color:#999;background:rgba(0,0,0,0.03);text-decoration:none;transition:all 0.2s;"
           onmouseover="this.style.color='#667eea';this.style.background='rgba(102,126,234,0.08)'"
           onmouseout="this.style.color='#999';this.style.background='rgba(0,0,0,0.03)'">🔄 刷新归档</a>
    </div>
    <?php
});
