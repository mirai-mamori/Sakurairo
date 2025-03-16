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
        'tools.php',                 // 父菜单slug
        '文章注释管理',               // 页面标题
        '文章注释管理',               // 菜单标题
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
            $message = '成功为文章生成注释！';
            $message_type = 'success';
        } else {
            $message = '生成注释失败，请检查API设置或查看错误日志。';
            $message_type = 'error';
        }
    } elseif (isset($_POST['delete_annotations']) && isset($_POST['post_id']) && check_admin_referer('iro_generate_annotations')) {
        $post_id = intval($_POST['post_id']);
        delete_post_meta($post_id, 'iro_chatgpt_annotations');
        $message = '已删除文章的注释数据。';
        $message_type = 'warning';
    } elseif (isset($_POST['test_save']) && isset($_POST['post_id']) && check_admin_referer('iro_generate_annotations')) {
        $post_id = intval($_POST['post_id']);
        $test_data = array(
            '测试术语1' => '这是一个测试解释1',
            '测试术语2' => '这是一个测试解释2'
        );
        
        echo '<div class="notice notice-info is-dismissible"><p>尝试保存测试数据到文章 ' . $post_id . '...</p>';
        
        $save_result = update_post_meta($post_id, 'iro_chatgpt_annotations', $test_data);
        
        if ($save_result === false) {
            echo '<p>保存失败!</p>';
        } else {
            echo '<p>保存成功! 更新ID: ' . $save_result . '</p>';
            
            // 尝试读取
            $read_result = get_post_meta($post_id, 'iro_chatgpt_annotations', true);
            echo '<p>读取结果: ' . (is_array($read_result) ? '成功 (数组，包含 ' . count($read_result) . ' 项)' : '失败') . '</p>';
            
            // 查询数据库状态
            global $wpdb;
            $query = $wpdb->prepare("SELECT * FROM {$wpdb->postmeta} WHERE post_id = %d AND meta_key = %s", $post_id, 'iro_chatgpt_annotations');
            $result = $wpdb->get_results($query);
            
            echo '<p>数据库查询结果: ' . (count($result) > 0 ? '找到 ' . count($result) . ' 条记录' : '未找到记录') . '</p>';
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
        <h1>文章注释管理</h1>
        
        <?php if (!empty($message)): ?>
            <div class="notice notice-<?php echo $message_type; ?> is-dismissible">
                <p><?php echo $message; ?></p>
            </div>
        <?php endif; ?>
        
        <!-- 调试信息 -->
        <div class="card" style="max-width:800px; margin-bottom:20px; padding:10px 20px; background-color:#f7f7f7;">
            <h2>系统信息</h2>
            <p><strong>ChatGPT API设置状态</strong></p>
            <pre style="background:#eee; padding:10px; overflow:auto;">
API端点: <?php echo iro_opt('chatgpt_endpoint', '未设置'); ?>
API密钥: <?php echo empty(iro_opt('chatgpt_access_token')) ? '未设置' : '已设置 (长度: ' . strlen(iro_opt('chatgpt_access_token')) . ')'; ?>
模型: <?php echo iro_opt('chatgpt_model', '未设置'); ?>
            </pre>
        </div>
        
        <div class="card" style="max-width:800px; margin-bottom:20px; padding:10px 20px;">
            <h2>关于文章注释</h2>
            <p>本功能使用ChatGPT分析文章内容，识别复杂或专业术语，并自动为其生成解释注释。注释将显示在文章中，访客可点击查看解释。</p>
            <p><strong>注意：</strong>此功能需要有效的OpenAI API密钥。</p>
            <?php 
            // 检查API设置
            $api_key = iro_opt('chatgpt_access_token', '');
            
            if (empty($api_key)) {
                echo '<p style="color:red;">未设置API密钥，请先在主题设置中配置ChatGPT API密钥。</p>';
            }
            ?>
        </div>
        
        <!-- 为文章生成注释 -->
        <div class="postbox" style="max-width:800px; margin-bottom:20px; padding:10px 20px;">
            <h2>为文章生成注释</h2>
            <form method="post">
                <?php wp_nonce_field('iro_generate_annotations'); ?>
                <table class="form-table">
                    <tr>
                        <th><label for="post_id">选择文章</label></th>
                        <td>
                            <select name="post_id" id="post_id" required>
                                <option value="">-- 选择文章 --</option>
                                <?php foreach ($all_posts as $p): ?>
                                    <option value="<?php echo $p->ID; ?>"><?php echo $p->post_title; ?> (ID: <?php echo $p->ID; ?>)</option>
                                <?php endforeach; ?>
                            </select>
                        </td>
                    </tr>
                </table>
                <p class="submit">
                    <input type="submit" name="generate_annotations" class="button button-primary" value="生成注释">
                    <input type="submit" name="delete_annotations" class="button" value="删除注释" onclick="return confirm('确定要删除此文章的注释数据吗？');">
                    <input type="submit" name="test_save" class="button" value="测试保存数据" title="测试直接保存一些数据到文章自定义字段">
                </p>
            </form>
        </div>
        
        <!-- 已生成注释的文章列表 -->
        <div class="postbox" style="max-width:800px; padding:10px 20px;">
            <h2>已生成注释的文章</h2>
            <?php if (empty($annotated_posts)): ?>
                <p>目前没有文章包含注释数据。</p>
            <?php else: ?>
                <table class="wp-list-table widefat fixed striped">
                    <thead>
                        <tr>
                            <th>文章标题</th>
                            <th>注释数量</th>
                            <th>操作</th>
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
                                    <a href="#" class="view-annotations" data-post-id="<?php echo $post->ID; ?>">查看注释</a> | 
                                    <a href="<?php echo get_edit_post_link($post->ID); ?>" target="_blank">编辑文章</a> | 
                                    <form method="post" style="display:inline;">
                                        <?php wp_nonce_field('iro_generate_annotations'); ?>
                                        <input type="hidden" name="post_id" value="<?php echo $post->ID; ?>">
                                        <input type="hidden" name="debug" value="1">
                                        <button type="submit" name="debug_annotations" class="button-link">调试信息</button>
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
            echo '<h3>文章 "' . esc_html($post->post_title) . '" (ID: ' . $post_id . ') 的注释调试信息</h3>';
            
            // 获取注释数据
            $annotations = get_post_meta($post_id, 'iro_chatgpt_annotations', true);
            
            echo '<p><strong>数据类型:</strong> ' . gettype($annotations) . '</p>';
            
            if (is_array($annotations)) {
                echo '<p><strong>注释数量:</strong> ' . count($annotations) . '</p>';
                echo '<p><strong>注释数据:</strong></p>';
                echo '<pre style="background:#f5f5f5; padding:10px; max-height:300px; overflow:auto;">';
                print_r($annotations);
                echo '</pre>';
            } else {
                echo '<p><strong>注释数据:</strong> ' . var_export($annotations, true) . '</p>';
            }
            
            // 检查数据库记录
            global $wpdb;
            $meta_records = $wpdb->get_results($wpdb->prepare(
                "SELECT * FROM {$wpdb->postmeta} WHERE post_id = %d AND meta_key = 'iro_chatgpt_annotations'",
                $post_id
            ));
            
            echo '<p><strong>数据库记录:</strong> ' . count($meta_records) . ' 条</p>';
            
            if (!empty($meta_records)) {
                foreach ($meta_records as $record) {
                    echo '<p><strong>记录ID:</strong> ' . $record->meta_id . '</p>';
                    echo '<p><strong>原始值:</strong></p>';
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
                <h2 id="modal-title">文章注释</h2>
                <div id="modal-content"></div>
            </div>
        </div>
        
        <script>
        document.addEventListener('DOMContentLoaded', function() {
            // 查看注释详情
            document.querySelectorAll('.view-annotations').forEach(function(link) {
                link.addEventListener('click', function(e) {
                    e.preventDefault();
                    const postId = this.dataset.postId;
                    
                    // AJAX请求获取注释数据
                    const xhr = new XMLHttpRequest();
                    xhr.open('GET', ajaxurl + '?action=get_post_annotations&post_id=' + postId);
                    xhr.onload = function() {
                        if (xhr.status === 200) {
                            try {
                                const response = JSON.parse(xhr.responseText);
                                if (response.success) {
                                    displayAnnotations(response.data.title, response.data.annotations);
                                }
                            } catch (e) {
                                console.error('解析响应失败', e);
                            }
                        }
                    };
                    xhr.send();
                });
            });
            
            // 显示注释模态框
            function displayAnnotations(title, annotations) {
                document.getElementById('modal-title').textContent = '文章注释: ' + title;
                
                let content = '<table class="wp-list-table widefat fixed striped">';
                content += '<thead><tr><th>术语</th><th>解释</th></tr></thead><tbody>';
                
                for (const term in annotations) {
                    content += `<tr><td><strong>${term}</strong></td><td>${annotations[term]}</td></tr>`;
                }
                
                content += '</tbody></table>';
                document.getElementById('modal-content').innerHTML = content;
                document.getElementById('annotation-modal').style.display = 'block';
            }
            
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
        wp_send_json_error('无效的文章ID');
    }
    
    $post = get_post($post_id);
    if (!$post) {
        wp_send_json_error('找不到文章');
    }
    
    $annotations = get_post_meta($post_id, 'iro_chatgpt_annotations', true);
    error_log("IROChatGPT: 从文章 {$post_id} 获取注释数据: " . print_r($annotations, true));
    
    if (empty($annotations)) {
        wp_send_json_error('该文章没有注释数据');
    }
    
    wp_send_json_success([
        'title' => $post->post_title,
        'annotations' => $annotations
    ]);
}
add_action('wp_ajax_get_post_annotations', __NAMESPACE__ . '\ajax_get_post_annotations');

/**
 * 在文章编辑页面添加元框，显示注释数据
 */
function add_annotations_meta_box() {
    add_meta_box(
        'iro_annotations_meta_box',    // 元框ID
        '文章注释数据',                // 标题
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
        echo '<p>该文章暂无注释数据。您可以在 <a href="' . admin_url('tools.php?page=iro-term-annotations') . '">文章注释管理</a> 页面生成注释。</p>';
        return;
    }
    
    echo '<div style="max-height: 300px; overflow-y: auto;">';
    echo '<table class="widefat fixed striped">';
    echo '<thead><tr><th>术语</th><th>解释</th></tr></thead>';
    echo '<tbody>';
    foreach ($annotations as $term => $explanation) {
        echo '<tr>';
        echo '<td>' . esc_html($term) . '</td>';
        echo '<td>' . esc_html($explanation) . '</td>';
        echo '</tr>';
    }
    echo '</tbody></table>';
    echo '<p><a href="' . admin_url('tools.php?page=iro-term-annotations') . '" class="button">在注释管理页面编辑</a></p>';
    echo '</div>';
}