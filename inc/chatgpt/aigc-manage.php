<?php
namespace IROChatGPT;

// 安全检查
if (!defined('ABSPATH')) {
    exit;
}

/**
 * 注册管理页面
 */
function register_annotations_admin_page() {
    add_submenu_page(
        'edit.php',                  // 父菜单slug
        __('Article Annotations Management', 'sakurairo'),               // 页面标题
        __('Article Annotations Management', 'sakurairo'),               // 菜单标题
        'manage_options',            // 所需权限
        'iro-term-annotations',      // 菜单slug
        __NAMESPACE__ . '\render_annotations_admin_page' // 回调函数
    );
}
add_action('admin_menu', __NAMESPACE__ . '\register_annotations_admin_page');

/**
 * 渲染管理页面
 */
function render_annotations_admin_page() {
    // 处理表单提交
    $message = '';
    $message_type = '';
    
    if (isset($_POST['generate_annotations']) && isset($_POST['post_id']) && check_admin_referer('iro_generate_annotations')) {
        $post_id = intval($_POST['post_id']);
        $result = generate_annotations_for_post($post_id);
        
        if ($result) {
            $message = __('Successfully generated annotations for the post!', 'sakurairo');
            $message_type = 'success';
        } else {
            $message = __('Failed to generate annotations. Please check API settings or view error logs.', 'sakurairo');
            $message_type = 'error';
        }
    } elseif (isset($_POST['delete_annotations']) && isset($_POST['post_id']) && check_admin_referer('iro_generate_annotations')) {
        $post_id = intval($_POST['post_id']);
        delete_post_meta($post_id, 'iro_chatgpt_annotations');
        $message = __('Annotations for the post have been deleted.', 'sakurairo');
        $message_type = 'warning';
    } elseif (isset($_POST['test_save']) && isset($_POST['post_id']) && check_admin_referer('iro_generate_annotations')) {
        $post_id = intval($_POST['post_id']);
        $test_data = array(
            __('Example Term', 'sakurairo') => __('This is a example explanation', 'sakurairo')
        );
        
        echo '<div class="notice notice-info is-dismissible"><p>' . sprintf(__('Attempting to save test data to post %d...', 'sakurairo'), $post_id) . '</p>';
        
        $save_result = update_post_meta($post_id, 'iro_chatgpt_annotations', $test_data);
        
        if ($save_result === false) {
            echo '<p>' . __('Save failed!', 'sakurairo') . '</p>';
        } else {
            echo '<p>' . sprintf(__('Save successful! Update ID: %s', 'sakurairo'), $save_result) . '</p>';
            
            // 尝试读取
            $read_result = get_post_meta($post_id, 'iro_chatgpt_annotations', true);
            echo '<p>' . sprintf(__('Read result: %s', 'sakurairo'), (is_array($read_result) ? __('Success (array, contains ', 'sakurairo') . count($read_result) . __(' items)', 'sakurairo') : __('Failed', 'sakurairo'))) . '</p>';
            
            // 查询数据库状态
            global $wpdb;
            $query = $wpdb->prepare("SELECT * FROM {$wpdb->postmeta} WHERE post_id = %d AND meta_key = %s", $post_id, 'iro_chatgpt_annotations');
            $result = $wpdb->get_results($query);
            
            echo '<p>' . sprintf(__('Database query result: %s', 'sakurairo'), (count($result) > 0 ? __('Found ', 'sakurairo') . count($result) . __(' records', 'sakurairo') : __('No records found', 'sakurairo'))) . '</p>';
        }
        
        echo '</div>';
    }
    
    // 查询带有注释的文章
    $annotated_posts = get_posts_with_annotations();
    
    // 查询所有文章和页面
    $query_args = [
        'post_type' => ['post', 'page'],
        'posts_per_page' => -1,
        'orderby' => 'date',
        'order' => 'DESC'
    ];
    $all_posts = get_posts($query_args);
    
    // 渲染页面
    ?>
    <div class="wrap">
        <h1><?php _e('Article Annotations Management', 'sakurairo'); ?></h1>
        
        <?php if (!empty($message)): ?>
            <div class="notice notice-<?php echo $message_type; ?> is-dismissible">
                <p><?php echo $message; ?></p>
            </div>
        <?php endif; ?>
        
        <!-- 调试信息 -->
        <div class="card" style="max-width:800px; margin-bottom:20px; padding:10px 20px; background-color:#f7f7f7;">
            <h2><?php _e('System Information', 'sakurairo'); ?></h2>
            <p><strong><?php _e('ChatGPT API Settings Status', 'sakurairo'); ?></strong></p>
            <pre style="background:#eee; padding:10px; overflow:auto;">
<?php _e('API Endpoint: ', 'sakurairo'); ?><?php echo iro_opt('chatgpt_endpoint', __('Not set', 'sakurairo')); ?>
<?php _e('API Key: ', 'sakurairo'); ?><?php echo empty(iro_opt('chatgpt_access_token')) ? __('Not set', 'sakurairo') : __('Set (Length: ', 'sakurairo') . strlen(iro_opt('chatgpt_access_token')) . ')'; ?>
<?php _e('Model: ', 'sakurairo'); ?><?php echo iro_opt('chatgpt_model', __('Not set', 'sakurairo')); ?>
            </pre>
        </div>
        
        <div class="card" style="max-width:800px; margin-bottom:20px; padding:10px 20px;">
            <h2><?php _e('About Article Annotations', 'sakurairo'); ?></h2>
            <p><?php _e('This feature uses ChatGPT to analyze the content of the post, identify complex or specialized terms, and automatically generate explanatory annotations. The annotations will be displayed in the post, and visitors can click to view explanations.', 'sakurairo'); ?></p>
            <p><strong><?php _e('Note:', 'sakurairo'); ?></strong> <?php _e('This feature requires a valid OpenAI API key.', 'sakurairo'); ?></p>
            <?php 
            // 检查API设置
            $api_key = iro_opt('chatgpt_access_token', '');
            
            if (empty($api_key)) {
                echo '<p style="color:red;">' . __('API key not set, please configure the ChatGPT API key in the theme settings first.', 'sakurairo') . '</p>';
            }
            ?>
        </div>
        
        <!-- 为文章生成注释 -->
        <div class="postbox" style="max-width:800px; margin-bottom:20px; padding:10px 20px;">
            <h2><?php _e('Generate Annotations for Post', 'sakurairo'); ?></h2>
            <form method="post">
                <?php wp_nonce_field('iro_generate_annotations'); ?>
                <table class="form-table">
                    <tr>
                        <th><label for="post_id"><?php _e('Select Post', 'sakurairo'); ?></label></th>
                        <td>
                            <select name="post_id" id="post_id" required>
                                <option value=""><?php _e('-- Select Post --', 'sakurairo'); ?></option>
                                <?php foreach ($all_posts as $p): ?>
                                    <option value="<?php echo $p->ID; ?>"><?php echo $p->post_title; ?> (ID: <?php echo $p->ID; ?>)</option>
                                <?php endforeach; ?>
                            </select>
                        </td>
                    </tr>
                </table>
                <p class="submit">
                    <input type="submit" name="generate_annotations" class="button button-primary" value="<?php _e('Generate Annotations', 'sakurairo'); ?>">
                    <input type="submit" name="delete_annotations" class="button" value="<?php _e('Delete Annotations', 'sakurairo'); ?>" onclick="return confirm('<?php _e('Are you sure you want to delete the annotation data for this post?', 'sakurairo'); ?>');">
                    <input type="submit" name="test_save" class="button" value="<?php _e('Create empty data', 'sakurairo'); ?>" title="<?php _e('Create empty terms for your custom article', 'sakurairo'); ?>">
                </p>
            </form>
        </div>
        
        <!-- 已生成注释的文章列表 -->
        <div class="postbox" style="max-width:800px; padding:10px 20px;">
            <h2><?php _e('Posts with Generated Annotations', 'sakurairo'); ?></h2>
            <?php if (empty($annotated_posts)): ?>
                <p><?php _e('Currently, no posts contain annotation data.', 'sakurairo'); ?></p>
            <?php else: ?>
                <table class="wp-list-table widefat fixed striped">
                    <thead>
                        <tr>
                            <th><?php _e('Post Title', 'sakurairo'); ?></th>
                            <th><?php _e('Number of Annotations', 'sakurairo'); ?></th>
                            <th><?php _e('Actions', 'sakurairo'); ?></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($annotated_posts as $post): ?>
                            <?php 
                            $annotations = get_post_meta($post->ID, 'iro_chatgpt_annotations', true);
                            $annotation_count = is_array($annotations) ? count($annotations) : 0;
                            ?>
                            <tr>
                                <td>
                                    <a href="<?php echo get_permalink($post->ID); ?>" target="_blank">
                                        <?php echo $post->post_title; ?>
                                    </a>
                                </td>
                                <td><?php echo $annotation_count; ?></td>
                                <td>
                                    <a href="#" class="view-annotations" data-post-id="<?php echo $post->ID; ?>"><?php _e('View Annotations', 'sakurairo'); ?></a> | 
                                    <a href="<?php echo get_edit_post_link($post->ID); ?>" target="_blank"><?php _e('Edit Post', 'sakurairo'); ?></a> | 
                                    <form method="post" style="display:inline;">
                                        <?php wp_nonce_field('iro_generate_annotations'); ?>
                                        <input type="hidden" name="post_id" value="<?php echo $post->ID; ?>">
                                        <input type="hidden" name="debug" value="1">
                                        <button type="submit" name="debug_annotations" class="button-link"><?php _e('Debug Information', 'sakurairo'); ?></button>
                                    </form>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            <?php endif; ?>
        </div>
        
        <?php
        // 处理调试请求
        if (isset($_POST['debug_annotations']) && isset($_POST['post_id']) && check_admin_referer('iro_generate_annotations')) {
            $post_id = intval($_POST['post_id']);
            $post = get_post($post_id);
            
            echo '<div class="notice notice-info is-dismissible">';
            echo '<h3>' . sprintf(__('Annotations Debug Information for post "%s" (ID: %d)', 'sakurairo'), esc_html($post->post_title), $post_id) . '</h3>';
            
            // 获取注释数据
            $annotations = get_post_meta($post_id, 'iro_chatgpt_annotations', true);
            
            echo '<p><strong>' . __('Data Type:', 'sakurairo') . '</strong> ' . gettype($annotations) . '</p>';
            
            if (is_array($annotations)) {
                echo '<p><strong>' . __('Number of Annotations:', 'sakurairo') . '</strong> ' . count($annotations) . '</p>';
                echo '<p><strong>' . __('Annotation Data:', 'sakurairo') . '</strong></p>';
                echo '<pre style="background:#f5f5f5; padding:10px; max-height:300px; overflow:auto;">';
                print_r($annotations);
                echo '</pre>';
            } else {
                echo '<p><strong>' . __('Annotation Data:', 'sakurairo') . '</strong> ' . var_export($annotations, true) . '</p>';
            }
            
            // 检查数据库记录
            global $wpdb;
            $meta_records = $wpdb->get_results($wpdb->prepare(
                "SELECT * FROM {$wpdb->postmeta} WHERE post_id = %d AND meta_key = 'iro_chatgpt_annotations'",
                $post_id
            ));
            
            echo '<p><strong>' . __('Database Records:', 'sakurairo') . '</strong> ' . count($meta_records) . ' ' . __('records', 'sakurairo') . '</p>';
            
            if (!empty($meta_records)) {
                foreach ($meta_records as $record) {
                    echo '<p><strong>' . __('Record ID:', 'sakurairo') . '</strong> ' . $record->meta_id . '</p>';
                    echo '<p><strong>' . __('Original Value:', 'sakurairo') . '</strong></p>';
                    echo '<div style="background:#f5f5f5; padding:10px; max-height:150px; overflow:auto;">';
                    echo esc_html(substr($record->meta_value, 0, 1000)) . (strlen($record->meta_value) > 1000 ? '...' : '');
                    echo '</div>';
                }
            }
            
            echo '</div>';
        }
        ?>
        
        <!-- 注释预览模态框 -->
        <div id="annotation-modal" style="display:none; position:fixed; z-index:100000; left:0; top:0; width:100%; height:100%; overflow:auto; background-color:rgba(0,0,0,0.4);">
            <div style="background-color:#fefefe; margin:10% auto; padding:20px; border:1px solid #888; width:60%; max-width:800px;">
                <span style="color:#aaa; float:right; font-size:28px; font-weight:bold; cursor:pointer;" id="close-modal">&times;</span>
                <h2 id="modal-title"><?php _e('Post Annotations: ', 'sakurairo'); ?></h2>
                <form id="annotations-form">
                    <input type="hidden" name="post_id" id="current-post-id" value="">
                    <table id="annotations-table" class="wp-list-table widefat fixed striped">
                        <thead>
                            <tr>
                                <th><?php _e('Term', 'sakurairo'); ?></th>
                                <th><?php _e('Explanation', 'sakurairo'); ?></th>
                                <th><?php _e('Actions', 'sakurairo'); ?></th>
                            </tr>
                        </thead>
                        <tbody>
                        </tbody>
                    </table>
                    <p>
                        <button type="button" id="add-annotation"><?php _e('Add Annotation', 'sakurairo'); ?></button>
                        <button type="submit" id="save-annotations"><?php _e('Save Annotations', 'sakurairo'); ?></button>
                    </p>
                </form>
            </div>
        </div>
        
        <script>
        document.addEventListener('DOMContentLoaded', function() {
            // 用于展示编辑表单的函数
            function displayAnnotations(title, annotations, postId) {
                document.getElementById('modal-title').textContent = <?php echo json_encode(__('Post Annotations: ', 'sakurairo')); ?> + title;
                document.getElementById('current-post-id').value = postId;
                let tbody = document.querySelector('#annotations-table tbody');
                tbody.innerHTML = ''; // 清空原有内容
                
                // 根据已有注释渲染行
                for (const term in annotations) {
                    let tr = document.createElement('tr');
                    tr.innerHTML = `<td><input type="text" name="term[]" value="${term}" /></td>
                                    <td><textarea name="explanation[]">${annotations[term]}</textarea></td>
                                    <td><button type="button" class="delete-row"><?php echo __('Delete', 'sakurairo'); ?></button></td>`;
                    tbody.appendChild(tr);
                }
                document.getElementById('annotation-modal').style.display = 'block';
            }

            // 查看注解
            document.querySelectorAll('.view-annotations').forEach(function(link) {
                link.addEventListener('click', function(e) {
                    e.preventDefault();
                    const postId = this.dataset.postId;
                    const xhr = new XMLHttpRequest();
                    xhr.open('GET', ajaxurl + '?action=get_post_annotations&post_id=' + postId);
                    xhr.onload = function() {
                        if (xhr.status === 200) {
                            try {
                                const response = JSON.parse(xhr.responseText);
                                if (response.success) {
                                    displayAnnotations(response.data.title, response.data.annotations, postId);
                                }
                            } catch (e) {
                                console.error('Parsing response failed', e);
                            }
                        }
                    };
                    xhr.send();
                });
            });

            // 添加新行
            document.getElementById('add-annotation').addEventListener('click', function() {
                let tbody = document.querySelector('#annotations-table tbody');
                let tr = document.createElement('tr');
                tr.innerHTML = `<td><input type="text" name="term[]" value="" /></td>
                                <td><textarea name="explanation[]"></textarea></td>
                                <td><button type="button" class="delete-row"><?php echo __('Delete', 'sakurairo'); ?></button></td>`;
                tbody.appendChild(tr);
            });

            // 删除
            document.querySelector('#annotations-table tbody').addEventListener('click', function(e) {
                if (e.target && e.target.classList.contains('delete-row')) {
                    e.target.closest('tr').remove();
                }
            });

            // 提交
            document.getElementById('annotations-form').addEventListener('submit', function(e) {
                e.preventDefault();
                const postId = document.getElementById('current-post-id').value;
                const terms = Array.from(document.querySelectorAll('input[name="term[]"]')).map(input => input.value.trim());
                const explanations = Array.from(document.querySelectorAll('textarea[name="explanation[]"]')).map(textarea => textarea.value.trim());
                
                // 组装数据
                let annotations = {};
                for (let i = 0; i < terms.length; i++) {
                    if (terms[i] !== '') {  // 排除空内容
                        annotations[terms[i]] = explanations[i];
                    }
                }
                
                // 请求
                const xhr = new XMLHttpRequest();
                xhr.open('POST', ajaxurl + '?action=update_post_annotations');
                xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
                xhr.onload = function() {
                    if (xhr.status === 200) {
                        try {
                            const response = JSON.parse(xhr.responseText);
                            if (response.success) {
                                alert('<?php echo __('Annotations updated successfully.', 'sakurairo'); ?>');
                                document.getElementById('annotation-modal').style.display = 'none';
                            } else {
                                alert(response.data || '<?php echo __('Update failed.', 'sakurairo'); ?>');
                            }
                        } catch (e) {
                            console.error('Parsing response failed', e);
                        }
                    }
                };
                // 构造参数
                const params = `post_id=${postId}&annotations=${encodeURIComponent(JSON.stringify(annotations))}&_wpnonce=<?php echo wp_create_nonce('update_post_annotations_nonce'); ?>`;
                xhr.send(params);
            });

            // 关闭模态框
            document.getElementById('close-modal').addEventListener('click', function() {
                document.getElementById('annotation-modal').style.display = 'none';
            });
            
            // 点击模态框外部关闭
            window.addEventListener('click', function(event) {
                const modal = document.getElementById('annotation-modal');
                if (event.target === modal) {
                    modal.style.display = 'none';
                }
            });
        });
        </script>
    </div>
    <?php
}

/**
 * 获取所有带有注释的文章，使用直接数据库查询确保准确性
 */
function get_posts_with_annotations() {
    global $wpdb;
    
    $query = "
        SELECT p.* 
        FROM {$wpdb->posts} p
        INNER JOIN {$wpdb->postmeta} pm ON p.ID = pm.post_id
        WHERE pm.meta_key = 'iro_chatgpt_annotations'
        AND p.post_status = 'publish'
        GROUP BY p.ID
        ORDER BY p.post_date DESC
    ";
    
    return $wpdb->get_results($query);
}

/**
 * 为文章生成注释
 */
function generate_annotations_for_post($post_id) {
    $post = get_post($post_id);
    if (!$post) {
        error_log("IROChatGPT: 找不到ID为 {$post_id} 的文章");
        return false;
    }
    
    // 使用正确的选项名获取API密钥
    $api_key = iro_opt('chatgpt_access_token', '');
    if (empty($api_key)) {
        error_log("IROChatGPT: API密钥未设置");
        return false;
    }
    
    // 处理文章内容
    $content = wp_strip_all_tags($post->post_content);
    if (strlen($content) < 100) {
        error_log("IROChatGPT: 文章内容太短，不进行注释");
        return false;
    }
    
    // 调用ChatGPT API生成注释
    error_log("IROChatGPT: 开始为文章 {$post_id} 生成注释");
    $annotations = call_chatgpt_for_annotations($content);
    
    if (empty($annotations) || !is_array($annotations)) {
        error_log("IROChatGPT: API返回无效数据，未能生成注释");
        return false;
    }
    
    error_log("IROChatGPT: 成功获取注释数据，准备保存。注释数量: " . count($annotations));
    
    // 保存前先验证注释是否有效
    if (!is_array($annotations)) {
        error_log("IROChatGPT: 注释数据不是数组格式");
        return false;
    }
    
    // 直接测试保存结果
    $save_result = update_post_meta($post_id, 'iro_chatgpt_annotations', $annotations);
    
    if ($save_result === false) {
        error_log("IROChatGPT: 保存注释到自定义字段失败");
        return false;
    } else {
        error_log("IROChatGPT: 成功保存注释到自定义字段，更新ID: {$save_result}");
        
        // 再次验证数据是否已正确保存
        $saved_data = get_post_meta($post_id, 'iro_chatgpt_annotations', true);
        if (empty($saved_data)) {
            error_log("IROChatGPT: 注释似乎已保存但无法读取，可能存在权限问题");
        } else {
            error_log("IROChatGPT: 验证成功，注释已正确保存。注释数量: " . (is_array($saved_data) ? count($saved_data) : 0));
        }
        
        return true;
    }
}

/**
 * AJAX处理函数：获取文章注释
 */
function ajax_get_post_annotations() {
    $post_id = isset($_GET['post_id']) ? intval($_GET['post_id']) : 0;
    
    if (!$post_id) {
        wp_send_json_error(__('Invalid post ID', 'sakurairo'));
    }
    
    $post = get_post($post_id);
    if (!$post) {
        wp_send_json_error(__('Post not found', 'sakurairo'));
    }
    
    $annotations = get_post_meta($post_id, 'iro_chatgpt_annotations', true);
    error_log("IROChatGPT: 从文章 {$post_id} 获取注释数据: " . print_r($annotations, true));
    
    if (empty($annotations)) {
        wp_send_json_error(__('This post does not have any annotation data', 'sakurairo'));
    }
    
    wp_send_json_success([
        'title' => $post->post_title,
        'annotations' => $annotations
    ]);
}
add_action('wp_ajax_get_post_annotations', __NAMESPACE__ . '\ajax_get_post_annotations');

/**
 * AJAX处理函数：更新文章注释数据
 */
function ajax_update_post_annotations() {
    // 权限和 nonce 检查
    if (!current_user_can('manage_options')) {
        wp_send_json_error(__('Access denied', 'sakurairo'));
    }
    if (!isset($_POST['_wpnonce']) || !wp_verify_nonce($_POST['_wpnonce'], 'update_post_annotations_nonce')) {
        wp_send_json_error(__('Access denied', 'sakurairo'));
    }

    $post_id = isset($_POST['post_id']) ? intval($_POST['post_id']) : 0;
    if (!$post_id) {
        wp_send_json_error(__('Invalid post ID', 'sakurairo'));
    }
    
    // 获取并解析注释数据
    $annotations = isset($_POST['annotations']) ? json_decode(stripslashes($_POST['annotations']), true) : null;
    if (!is_array($annotations)) {
        wp_send_json_error(__('Invalid annotations data', 'sakurairo'));
    }
    
    // 更新文章注释数据
    $result = update_post_meta($post_id, 'iro_chatgpt_annotations', $annotations);
    if ($result === false) {
        wp_send_json_error(__('Failed to update annotations', 'sakurairo'));
    }
    
    wp_send_json_success(__('Annotations updated successfully', 'sakurairo'));
}
add_action('wp_ajax_update_post_annotations', __NAMESPACE__ . '\ajax_update_post_annotations');


/**
 * 在文章编辑页面添加元框，显示注释数据
 */
function add_annotations_meta_box() {
    add_meta_box(
        'iro_annotations_meta_box',    // 元框ID
        __('Post Annotation Data', 'sakurairo'),                // 标题
        __NAMESPACE__ . '\render_annotations_meta_box', // 回调函数
        ['post', 'page'],              // 显示在文章和页面类型
        'normal',                      // 显示位置
        'default'                      // 优先级
    );
}
add_action('add_meta_boxes', __NAMESPACE__ . '\add_annotations_meta_box');

/**
 * 渲染注释数据元框内容
 */
function render_annotations_meta_box($post) {
    $annotations = get_post_meta($post->ID, 'iro_chatgpt_annotations', true);
    
    if (empty($annotations) || !is_array($annotations)) {
        echo '<p>' . sprintf(__('This post currently does not have any annotation data. You can generate annotations on the <a href="%s">Article Annotations Management</a> page.', 'sakurairo'), admin_url('tools.php?page=iro-term-annotations')) . '</p>';
        return;
    }
    
    echo '<div style="max-height: 300px; overflow-y: auto;">';
    echo '<table class="widefat fixed striped">';
    echo '<thead><tr><th>' . __('Term', 'sakurairo') . '</th><th>' . __('Explanation', 'sakurairo') . '</th></tr></thead>';
    echo '<tbody>';
    foreach ($annotations as $term => $explanation) {
        echo '<tr>';
        echo '<td>' . esc_html($term) . '</td>';
        echo '<td>' . esc_html($explanation) . '</td>';
        echo '</tr>';
    }
    echo '</tbody></table>';
    echo '<p><a href="' . admin_url('tools.php?page=iro-term-annotations') . '" class="button">' . __('Edit in Annotations Management Page', 'sakurairo') . '</a></p>';
    echo '</div>';
}
