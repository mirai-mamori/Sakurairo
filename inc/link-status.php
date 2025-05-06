<?php
/**
 * 友情链接状态检测
 * 
 * 提供友情链接状态检测的管理页面和手动检测功能
 */

// 防止直接访问
if (!defined('ABSPATH')) {
    exit;
}

/**
 * 添加友情链接状态检测菜单
 */
function sakurairo_add_link_status_menu() {
    add_submenu_page(
        'link-manager.php',
        __('Friend Links Status', 'sakurairo'),
        __('Links Status', 'sakurairo'),
        'manage_options',
        'sakurairo-link-status',
        'sakurairo_link_status_page'
    );
}
add_action('admin_menu', 'sakurairo_add_link_status_menu');

/**
 * 友情链接状态检测页面
 */
function sakurairo_link_status_page() {
    // 检查用户权限
    if (!current_user_can('manage_options')) {
        return;
    }
    
    // 处理手动检测请求
    if (isset($_POST['check_all_links']) && check_admin_referer('sakurairo_check_links_nonce')) {
        // 手动触发检测所有链接
        sakurairo_manual_check_all_links();
        echo '<div class="notice notice-success is-dismissible"><p>' . __('All links check has been triggered. Please refresh this page in a few moments to see the results.', 'sakurairo') . '</p></div>';
    } elseif (isset($_POST['check_link']) && isset($_POST['link_id']) && check_admin_referer('sakurairo_check_link_nonce')) {
        // 手动检测单个链接
        $link_id = intval($_POST['link_id']);
        $link = get_bookmark($link_id);
        if ($link) {
            sakurairo_check_single_link_status($link);
            echo '<div class="notice notice-success is-dismissible"><p>' . sprintf(__('Link "%s" has been checked. Refresh to see the results.', 'sakurairo'), esc_html($link->link_name)) . '</p></div>';
        }
    } elseif (isset($_POST['reset_link_status']) && isset($_POST['link_id']) && check_admin_referer('sakurairo_reset_link_nonce')) {
        // 重置链接状态
        $link_id = intval($_POST['link_id']);
        delete_post_meta($link_id, '_link_check_status');
        delete_post_meta($link_id, '_link_check_time');
        delete_post_meta($link_id, '_link_failure_count');
        delete_post_meta($link_id, '_link_status_code');
        delete_post_meta($link_id, '_link_error_message');
        echo '<div class="notice notice-success is-dismissible"><p>' . __('Link status has been reset.', 'sakurairo') . '</p></div>';
    }
    
    // 获取所有可见的友情链接
    $links = get_bookmarks(array(
        'hide_invisible' => false, // 获取所有链接，包括不可见的
    ));
    
    // 获取上次检查时间
    $last_check_time = get_option('sakurairo_link_check_last_time', '');
    
    // 输出页面
    ?>
    <div class="wrap">
        <h1><?php echo esc_html__('Friend Links Status', 'sakurairo'); ?></h1>
        
        <div class="notice notice-info">
            <p>
                <?php 
                if (!empty($last_check_time)) {
                    echo sprintf(__('Last automatic check: %s', 'sakurairo'), $last_check_time);
                } else {
                    echo __('No automatic check has been performed yet.', 'sakurairo');
                }
                ?>
            </p>
            <p><?php _e('The system automatically checks a batch of links (5 links) every week to avoid overloading the server.', 'sakurairo'); ?></p>
        </div>
        
        <form method="post" action="">
            <?php wp_nonce_field('sakurairo_check_links_nonce'); ?>
            <p>
                <input type="submit" name="check_all_links" class="button button-primary" value="<?php echo esc_attr__('Check All Links Now', 'sakurairo'); ?>" onclick="return confirm('<?php echo esc_js(__('This will check all links and may take some time. Continue?', 'sakurairo')); ?>');" />
                <span class="description"><?php _e('This will check all links in batches of 5 with a delay between batches to avoid overloading the server.', 'sakurairo'); ?></span>
            </p>
        </form>
        
        <hr>
        
        <h2><?php echo esc_html__('Links Status', 'sakurairo'); ?></h2>
        
        <table class="wp-list-table widefat fixed striped">
            <thead>
                <tr>
                    <th><?php _e('Link Name', 'sakurairo'); ?></th>
                    <th><?php _e('URL', 'sakurairo'); ?></th>
                    <th><?php _e('Status', 'sakurairo'); ?></th>
                    <th><?php _e('Last Check', 'sakurairo'); ?></th>
                    <th><?php _e('Failures', 'sakurairo'); ?></th>
                    <th><?php _e('Details', 'sakurairo'); ?></th>
                    <th><?php _e('Actions', 'sakurairo'); ?></th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($links)) : ?>
                    <tr>
                        <td colspan="7"><?php _e('No links found.', 'sakurairo'); ?></td>
                    </tr>
                <?php else : ?>
                    <?php foreach ($links as $link) : 
                        $link_id = $link->link_id;
                        $check_status = get_post_meta($link_id, '_link_check_status', true);
                        $check_time = get_post_meta($link_id, '_link_check_time', true);
                        $failure_count = intval(get_post_meta($link_id, '_link_failure_count', true));
                        $status_code = get_post_meta($link_id, '_link_status_code', true);
                        $error_message = get_post_meta($link_id, '_link_error_message', true);
                        
                        // 状态显示
                        if (empty($check_status)) {
                            $status_html = '<span class="status-unknown">' . __('Not checked', 'sakurairo') . '</span>';
                        } elseif ($check_status === 'success') {
                            $status_html = '<span class="status-success">' . __('Success', 'sakurairo') . '</span>';
                        } else {
                            $status_html = '<span class="status-failure">' . __('Failure', 'sakurairo') . '</span>';
                        }
                        
                        // 详细信息
                        $details = '';
                        if (!empty($status_code)) {
                            $details .= sprintf(__('Status Code: %s', 'sakurairo'), $status_code) . '<br>';
                        }
                        if (!empty($error_message)) {
                            $details .= sprintf(__('Error: %s', 'sakurairo'), esc_html($error_message));
                        }
                    ?>
                    <tr>
                        <td>
                            <?php echo esc_html($link->link_name); ?>
                            <?php if ($link->link_visible === 'N') : ?>
                                <span class="dashicons dashicons-hidden" title="<?php _e('Not visible', 'sakurairo'); ?>"></span>
                            <?php endif; ?>
                        </td>
                        <td><a href="<?php echo esc_url($link->link_url); ?>" target="_blank"><?php echo esc_url($link->link_url); ?></a></td>
                        <td><?php echo $status_html; ?></td>
                        <td><?php echo !empty($check_time) ? $check_time : __('Never', 'sakurairo'); ?></td>
                        <td><?php echo $failure_count; ?></td>
                        <td><?php echo $details; ?></td>
                        <td>
                            <form method="post" action="" style="display:inline;">
                                <?php wp_nonce_field('sakurairo_check_link_nonce'); ?>
                                <input type="hidden" name="link_id" value="<?php echo $link_id; ?>" />
                                <input type="submit" name="check_link" class="button button-small" value="<?php echo esc_attr__('Check Now', 'sakurairo'); ?>" />
                            </form>
                            
                            <form method="post" action="" style="display:inline;">
                                <?php wp_nonce_field('sakurairo_reset_link_nonce'); ?>
                                <input type="hidden" name="link_id" value="<?php echo $link_id; ?>" />
                                <input type="submit" name="reset_link_status" class="button button-small" value="<?php echo esc_attr__('Reset Status', 'sakurairo'); ?>" />
                            </form>
                            
                            <a href="<?php echo admin_url('link.php?action=edit&link_id=' . $link_id); ?>" class="button button-small"><?php _e('Edit', 'sakurairo'); ?></a>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
    
    <style>
        .status-success {
            color: #46b450;
            font-weight: bold;
        }
        .status-failure {
            color: #dc3232;
            font-weight: bold;
        }
        .status-unknown {
            color: #ffb900;
        }
    </style>
    <?php
}

/**
 * 手动检测所有链接（分批进行）
 */
function sakurairo_manual_check_all_links() {
    // 获取所有可见的友情链接
    $links = get_bookmarks(array(
        'hide_invisible' => false, // 获取所有链接，包括不可见的
    ));
    
    if (empty($links)) {
        return;
    }
    
    // 设置批次大小
    $batch_size = 5;
    $total_links = count($links);
    $total_batches = ceil($total_links / $batch_size);
    
    // 创建一个定时任务，分批检测链接
    for ($batch = 0; $batch < $total_batches; $batch++) {
        $timestamp = time() + ($batch * 60); // 每批次间隔1分钟
        wp_schedule_single_event($timestamp, 'sakurairo_check_links_batch', array($batch, $batch_size));
    }
    
    // 记录开始检测的时间
    update_option('sakurairo_link_check_started', current_time('mysql'));
}

/**
 * 执行单批次链接检测
 */
function sakurairo_check_links_batch($batch, $batch_size) {
    // 获取所有可见的友情链接
    $links = get_bookmarks(array(
        'hide_invisible' => false, // 获取所有链接，包括不可见的
    ));
    
    if (empty($links)) {
        return;
    }
    
    // 计算当前批次的起始和结束索引
    $start_index = $batch * $batch_size;
    $end_index = min($start_index + $batch_size, count($links));
    
    // 获取当前批次的链接
    $current_batch_links = array_slice($links, $start_index, $end_index - $start_index);
    
    // 检查每个链接的状态
    foreach ($current_batch_links as $link) {
        sakurairo_check_single_link_status($link);
    }
    
    // 如果是最后一批，更新检查完成时间
    if ($end_index >= count($links)) {
        update_option('sakurairo_link_check_last_time', current_time('mysql'));
    }
}
add_action('sakurairo_check_links_batch', 'sakurairo_check_links_batch', 10, 2);

/**
 * 添加管理页面样式
 */
function sakurairo_link_status_admin_styles() {
    $screen = get_current_screen();
    if ($screen && $screen->id === 'link-manager_page_sakurairo-link-status') {
        ?>
        <style>
            .wp-list-table .column-status {
                width: 10%;
            }
            .wp-list-table .column-last_check {
                width: 15%;
            }
            .wp-list-table .column-failures {
                width: 8%;
            }
            .wp-list-table .column-actions {
                width: 20%;
            }
        </style>
        <?php
    }
}
add_action('admin_head', 'sakurairo_link_status_admin_styles');

// 添加链接优先级字段
add_action('link_category_add_form_fields', function($taxonomy) {
    ?>
    <div class="form-field">
        <label for="term_priority"><?php __("priority","sakurairo"); ?></label>
        <input type="number" name="term_priority" id="term_priority" value="0" min="0" />
        <p><?php echo __("The higher the value, the higher the priority (default is 0).","sakurairo"); ?></p>
    </div>
    <?php
});

add_action('link_category_edit_form_fields', function($term, $taxonomy) {
    $priority = get_term_meta($term->term_id, 'term_priority', true);
    ?>
    <tr class="form-field">
        <th scope="row"><label for="term_priority"><?php __("priority","sakurairo"); ?></label></th>
        <td>
            <input type="number" name="term_priority" id="term_priority" 
                   value="<?php echo esc_attr($priority ?: 0); ?>" 
                   min="0" />
            <p class="description"><?php echo __("The higher the value, the higher the priority (default is 0).","sakurairo"); ?></p>
        </td>
    </tr>
    <?php
}, 10, 2); 

add_filter('manage_edit-link_category_columns', function($columns) {
    $columns['priority'] = __('priority', 'sakurairo');
    return $columns;
});

add_filter('manage_link_category_custom_column', function($content, $column_name, $term_id) {
    if ($column_name === 'priority') {
        $priority = get_term_meta($term_id, 'term_priority', true);
        return $priority ?: 0;
    }
    return $content;
}, 10, 3);

add_filter('manage_edit-link_category_sortable_columns', function($columns) {
    $columns['priority'] = 'term_priority';
    return $columns;
});

add_action('created_link_category', function($term_id) {
    if (isset($_POST['term_priority'])) {
        update_term_meta($term_id, 'term_priority', intval($_POST['term_priority']));
    }
});

add_action('edited_link_category', function($term_id) {
    if (isset($_POST['term_priority'])) {
        update_term_meta($term_id, 'term_priority', intval($_POST['term_priority']));
    }
});