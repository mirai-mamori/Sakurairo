<?php
/**
 * Module: 说说（Shuoshuo）
 *
 * 范围（前端展示 + 后台编辑放在同一模块）：
 * - CPT 参数定制（隐藏默认菜单、精简 supports）
 * - 前台查询（归档不混说说；说说归档分页）
 * - 后台：快速发送 / 管理列表 / 行内保存
 * - 前台归档 AJAX 加载更多
 *
 * 前端时间轴模板：主题根目录 archive-shuoshuo.php（WordPress 模板层级要求放在根目录）
 */

if (!defined('ABSPATH')) {
    exit;
}

/** CPT：隐藏默认菜单，精简 supports（不与分类/评论绑定） */
add_filter('register_post_type_args', function ($args, $post_type) {
    if ($post_type !== 'shuoshuo') {
        return $args;
    }
    $args['show_in_menu'] = false;
    $args['supports'] = array('editor', 'author', 'thumbnail', 'custom-fields');
    $args['taxonomies'] = array();
    return $args;
}, 10, 2);

/**
 * 前台查询：
 * - 说说归档：只出说说，走 archive-shuoshuo.php 时间轴
 * - 普通归档/分类/作者页只出 post（不把说说混进时间线）
 *
 * priority 5 先记下原始 post_type；priority 20 在主题
 * customize_query_functions 之后恢复/覆盖，避免被改成 post+shuoshuo
 * 或误判成 post 归档导致说说一条都查不到。
 */
add_action('pre_get_posts', function ($query) {
    if (!$query->is_main_query() || is_admin()) {
        return;
    }
    $pt = $query->get('post_type');
    if ($pt === 'shuoshuo' || (is_array($pt) && in_array('shuoshuo', $pt, true) && count($pt) === 1)) {
        $query->set('personal_is_shuoshuo_archive', true);
    }
}, 5);

add_action('pre_get_posts', function ($query) {
    if (!$query->is_main_query() || is_admin()) {
        return;
    }

    if ($query->get('personal_is_shuoshuo_archive')) {
        $query->set('post_type', array('shuoshuo'));
        $query->set('posts_per_page', 10);
        return;
    }

    // 其它自定义类型归档保持原样，不强制改成 post
    if ($query->is_post_type_archive()) {
        return;
    }

    // 普通归档/分类/作者页：只出文章
    if ($query->is_archive() || $query->is_category() || $query->is_author()) {
        $query->set('post_type', array('post'));
    }
}, 20);

// ========== 后台：说说管理 ==========

add_action('admin_menu', function () {
    add_menu_page(
        '说说',
        '说说',
        'publish_posts',
        'shuoshuo-quick',
        'shuoshuo_quick_post_page',
        'dashicons-format-status',
        6
    );
    add_submenu_page('shuoshuo-quick', '快速发送说说', '快速发送说说', 'publish_posts', 'shuoshuo-quick');
    add_submenu_page('shuoshuo-quick', '管理说说', '管理说说', 'publish_posts', 'shuoshuo-manage', 'shuoshuo_manage_page');
});

/**
 * 组装说说 WP 块格式内容（文字 + 图片 URL 列表）
 */
function personal_shuoshuo_build_content($text, array $images) {
    $content = '';
    $text = (string) $text;
    if (strlen(trim($text)) > 0) {
        $content .= '<!-- wp:paragraph -->' . "\n";
        $content .= '<p>' . nl2br(wp_kses_post($text)) . '</p>' . "\n";
        $content .= '<!-- /wp:paragraph -->' . "\n";
    }
    foreach ($images as $url) {
        $url = esc_url_raw($url);
        if ($url === '') {
            continue;
        }
        $content .= '<!-- wp:image {"sizeSlug":"large"} -->' . "\n";
        $content .= '<figure class="wp-block-image size-large"><img src="' . esc_url($url) . '" alt=""/></figure>' . "\n";
        $content .= '<!-- /wp:image -->' . "\n";
    }
    return $content;
}

/**
 * 从说说内容里拆出正文与图片 URL 列表
 *
 * text_html  — 前台时间轴正文（保留段落等 HTML，去掉图片）
 * text_plain — 后台预览用纯文本
 */
function personal_shuoshuo_parse_content($raw) {
    $img_urls = array();
    if (preg_match_all('/<img[^>]+src=["\']([^"\']+)["\']/', (string) $raw, $m)) {
        $img_urls = $m[1];
    }
    $text_html = preg_replace('/<!--.*?-->/s', '', (string) $raw);
    $text_html = preg_replace('/<figure[^>]*>.*?<\/figure>/s', '', $text_html);
    $text_html = preg_replace('/<img[^>]*>/', '', $text_html);
    $text_html = trim($text_html);
    return array(
        'text_html'  => $text_html,
        'text_plain' => trim(strip_tags($text_html)),
        'images'     => $img_urls,
    );
}

function shuoshuo_quick_post_page() {
    if (!current_user_can('publish_posts')) {
        wp_die('权限不足');
    }

    $message = '';
    $error = '';

    if (isset($_POST['shuoshuo_submit']) && check_admin_referer('shuoshuo_quick_post')) {
        $text = isset($_POST['shuoshuo_content']) ? wp_kses_post(wp_unslash($_POST['shuoshuo_content'])) : '';
        $images = isset($_POST['shuoshuo_images']) ? array_map('esc_url_raw', array_filter((array) wp_unslash($_POST['shuoshuo_images']))) : array();
        $images = array_slice($images, 0, 9);

        if (empty(trim($text)) && empty($images)) {
            $error = '内容不能为空！';
        } else {
            $post_id = wp_insert_post(array(
                'post_type'    => 'shuoshuo',
                'post_content' => personal_shuoshuo_build_content($text, $images),
                'post_status'  => 'publish',
                'post_title'   => '',
            ));
            if ($post_id) {
                $message = '发布成功！';
            } else {
                $error = '发布失败，请重试。';
            }
        }
    }
    ?>
    <div class="wrap">
        <h1>📝 快速发送说说</h1>
        <?php if ($message): ?>
            <div class="notice notice-success"><p><?php echo esc_html($message); ?></p></div>
        <?php endif; ?>
        <?php if ($error): ?>
            <div class="notice notice-error"><p><?php echo esc_html($error); ?></p></div>
        <?php endif; ?>
        <form method="post" style="max-width: 700px; margin-top: 20px;">
            <?php wp_nonce_field('shuoshuo_quick_post'); ?>
            <label style="font-weight:600;display:block;margin-bottom:6px;">文字内容</label>
            <textarea name="shuoshuo_content" rows="5" style="width:100%;font-size:15px;padding:10px;border:1px solid #ddd;border-radius:4px;" placeholder="写点什么..."></textarea>

            <div style="margin-top:16px;">
                <label style="font-weight:600;display:block;margin-bottom:6px;">图片（最多9张，填URL链接）</label>
                <div id="shuoshuo-image-fields"></div>
                <button type="button" id="shuoshuo-add-img" class="button" style="margin-top:8px;">＋ 添加图片</button>
            </div>

            <p style="margin-top: 20px;">
                <input type="submit" name="shuoshuo_submit" class="button button-primary button-hero" value="发布说说" />
            </p>
        </form>
    </div>
    <script>
    (function() {
        var container = document.getElementById('shuoshuo-image-fields');
        var addBtn = document.getElementById('shuoshuo-add-img');

        function renumber() {
            var rows = container.children;
            for (var i = 0; i < rows.length; i++) {
                var label = rows[i].querySelector('.shuoshuo-img-idx');
                if (label) {
                    label.textContent = '#' + (i + 1);
                }
            }
        }

        addBtn.addEventListener('click', function() {
            if (container.children.length >= 9) {
                alert('最多9张图片');
                return;
            }
            var row = document.createElement('div');
            row.style.cssText = 'display:flex;gap:8px;align-items:center;margin-bottom:6px;';
            row.innerHTML =
                '<span class="shuoshuo-img-idx" style="color:#999;font-size:12px;min-width:20px;"></span>' +
                '<input type="url" name="shuoshuo_images[]" placeholder="https://example.com/image.jpg" style="flex:1;padding:6px 10px;border:1px solid #ddd;border-radius:4px;font-size:13px;">' +
                '<button type="button" class="button">✕</button>';
            row.querySelector('button').addEventListener('click', function() {
                row.remove();
                renumber();
            });
            container.appendChild(row);
            renumber();
        });
    })();
    </script>
    <?php
}

function shuoshuo_manage_page() {
    if (!current_user_can('publish_posts')) {
        wp_die('权限不足');
    }

    if (isset($_POST['shuoshuo_bulk_delete']) && check_admin_referer('shuoshuo_manage')) {
        $ids = isset($_POST['ids']) ? array_map('intval', (array) wp_unslash($_POST['ids'])) : array();
        if (!empty($ids)) {
            $deleted = 0;
            foreach ($ids as $id) {
                $post = get_post($id);
                if (!$post || $post->post_type !== 'shuoshuo' || !current_user_can('delete_post', $id)) {
                    continue;
                }
                wp_delete_post($id, true);
                $deleted++;
            }
            echo '<div class="notice notice-success"><p>已删除 ' . esc_html($deleted) . ' 条说说。</p></div>';
        }
    }

    $paged = isset($_GET['paged']) ? max(1, intval($_GET['paged'])) : 1;
    $per_page = 20;
    $query = new WP_Query(array(
        'post_type'      => 'shuoshuo',
        'posts_per_page' => $per_page,
        'paged'          => $paged,
        'orderby'        => 'date',
        'order'          => 'DESC',
    ));
    $total = $query->found_posts;
    $total_pages = $query->max_num_pages;
    ?>
    <div class="wrap">
        <h1>管理说说 <a href="?page=shuoshuo-quick" class="page-title-action">发布新说说</a></h1>
        <p style="color:#666;">共 <?php echo esc_html($total); ?> 条说说</p>
        <form method="post" id="shuoshuo-manage-form">
            <?php wp_nonce_field('shuoshuo_manage'); ?>
            <table class="wp-list-table widefat fixed striped" style="max-width: 900px;">
                <thead>
                    <tr>
                        <th style="width:40px;"><input type="checkbox" onclick="jQuery('.shuoshuo-cb').prop('checked',this.checked)"></th>
                        <th>内容</th>
                        <th style="width:160px;">发布时间</th>
                        <th style="width:100px;">操作</th>
                    </tr>
                </thead>
                <tbody>
                <?php if ($query->have_posts()): while ($query->have_posts()): $query->the_post(); ?>
                    <?php
                    $parsed = personal_shuoshuo_parse_content(get_the_content());
                    $text_only = $parsed['text_plain'];
                    $edit_imgs = $parsed['images'];
                    $preview = mb_strlen($text_only) > 100 ? mb_substr($text_only, 0, 100) . '...' : $text_only;
                    if (!empty($edit_imgs)) {
                        $preview .= ' [图' . count($edit_imgs) . ']';
                    }
                    ?>
                    <tr id="shuoshuo-row-<?php the_ID(); ?>">
                        <td><input type="checkbox" class="shuoshuo-cb" name="ids[]" value="<?php the_ID(); ?>"></td>
                        <td class="shuoshuo-cell-content"><span class="shuoshuo-preview"><?php echo esc_html($preview); ?></span></td>
                        <td class="shuoshuo-cell-date"><?php echo esc_html(get_the_date('Y-m-d H:i')); ?></td>
                        <td>
                            <a href="javascript:void(0)" class="shuoshuo-edit-btn" data-id="<?php the_ID(); ?>">快速编辑</a> |
                            <a href="<?php echo esc_url(get_delete_post_link()); ?>" onclick="return confirm('确定删除？')">删除</a>
                        </td>
                    </tr>
                    <tr id="shuoshuo-edit-<?php the_ID(); ?>" class="shuoshuo-edit-row" style="display:none;">
                        <td colspan="4" style="padding:12px 16px;background:#f9f9f9;">
                            <div style="max-width:700px;">
                                <label style="font-weight:600;display:block;margin-bottom:6px;">文字内容</label>
                                <textarea class="shuoshuo-edit-text" rows="3" style="width:100%;font-size:13px;padding:8px;border:1px solid #ddd;border-radius:4px;"><?php echo esc_textarea($text_only); ?></textarea>
                                <div style="margin-top:10px;">
                                    <label style="font-weight:600;display:block;margin-bottom:6px;">图片链接</label>
                                    <div class="shuoshuo-edit-img-list">
                                        <?php $img_idx = 0; foreach ($edit_imgs as $url): $img_idx++; ?>
                                        <div style="display:flex;gap:6px;align-items:center;margin-bottom:4px;">
                                            <span class="shuoshuo-img-idx" style="color:#999;font-size:12px;min-width:24px;">#<?php echo (int) $img_idx; ?></span>
                                            <input type="url" class="shuoshuo-edit-img-url" value="<?php echo esc_attr($url); ?>" style="flex:1;padding:5px 8px;border:1px solid #ddd;border-radius:4px;font-size:12px;">
                                            <button type="button" class="button shuoshuo-remove-img">✕</button>
                                        </div>
                                        <?php endforeach; ?>
                                    </div>
                                    <button type="button" class="button shuoshuo-add-img-btn" data-id="<?php the_ID(); ?>" data-count="<?php echo count($edit_imgs); ?>" style="margin-top:4px;font-size:12px;">＋ 添加图片</button>
                                </div>
                                <div style="display:flex;gap:12px;align-items:center;margin-top:12px;flex-wrap:wrap;">
                                    <div>
                                        <label style="font-weight:600;">发布时间</label>
                                        <input type="datetime-local" class="shuoshuo-edit-date" value="<?php echo esc_attr(get_the_date('Y-m-d\TH:i')); ?>" style="padding:6px 8px;border:1px solid #ddd;border-radius:4px;">
                                    </div>
                                    <div style="display:flex;gap:8px;align-items:flex-end;margin-left:auto;">
                                        <button type="button" class="button button-primary shuoshuo-save-btn" data-id="<?php the_ID(); ?>">保存</button>
                                        <button type="button" class="button shuoshuo-cancel-btn" data-id="<?php the_ID(); ?>">取消</button>
                                        <span class="shuoshuo-save-status" id="shuoshuo-status-<?php the_ID(); ?>" style="font-size:12px;color:#46b450;margin-left:8px;"></span>
                                    </div>
                                </div>
                            </div>
                        </td>
                    </tr>
                <?php endwhile; else: ?>
                    <tr><td colspan="4" style="text-align:center;padding:30px;">还没有说说，去<a href="?page=shuoshuo-quick">发布</a>一条吧~</td></tr>
                <?php endif; wp_reset_postdata(); ?>
                </tbody>
            </table>
            <p style="margin-top:15px;">
                <input type="submit" name="shuoshuo_bulk_delete" class="button" value="批量删除选中" onclick="return confirm('确定删除选中的说说？')">
            </p>
        </form>
        <?php if ($total_pages > 1): ?>
        <div class="tablenav bottom" style="max-width:900px;">
            <div class="tablenav-pages">
                <?php
                echo paginate_links(array(
                    'base'      => add_query_arg('paged', '%#%'),
                    'format'    => '',
                    'current'   => $paged,
                    'total'     => $total_pages,
                    'prev_text' => '«',
                    'next_text' => '»',
                ));
                ?>
            </div>
        </div>
        <?php endif; ?>
    </div>
    <script>
    jQuery(function($) {
        $(document).on('click', '.shuoshuo-edit-btn', function() {
            var id = $(this).data('id');
            $('#shuoshuo-row-' + id).hide();
            $('#shuoshuo-edit-' + id).show();
        });
        $(document).on('click', '.shuoshuo-cancel-btn', function() {
            var id = $(this).data('id');
            $('#shuoshuo-edit-' + id).hide();
            $('#shuoshuo-row-' + id).show();
        });
        $(document).on('click', '.shuoshuo-add-img-btn', function() {
            var list = $(this).siblings('.shuoshuo-edit-img-list');
            var cnt = list.find('input').length;
            if (cnt >= 9) { alert('最多9张'); return; }
            var num = cnt + 1;
            list.append('<div style="display:flex;gap:6px;align-items:center;margin-bottom:4px;">' +
                '<span class="shuoshuo-img-idx" style="color:#999;font-size:12px;min-width:24px;">#' + num + '</span>' +
                '<input type="url" class="shuoshuo-edit-img-url" placeholder="https://example.com/img.jpg" style="flex:1;padding:5px 8px;border:1px solid #ddd;border-radius:4px;font-size:12px;">' +
                '<button type="button" class="button shuoshuo-remove-img">✕</button></div>');
            list.find('.shuoshuo-img-idx').each(function(i) { $(this).text('#' + (i+1)); });
        });
        $(document).on('click', '.shuoshuo-remove-img', function() {
            var list = $(this).closest('.shuoshuo-edit-img-list');
            $(this).parent().remove();
            list.find('.shuoshuo-img-idx').each(function(i) { $(this).text('#' + (i+1)); });
        });
        $(document).on('click', '.shuoshuo-save-btn', function() {
            var id = $(this).data('id');
            var btn = $(this);
            var status = $('#shuoshuo-status-' + id);
            var text = $('#shuoshuo-edit-' + id + ' .shuoshuo-edit-text').val();
            var date = $('#shuoshuo-edit-' + id + ' .shuoshuo-edit-date').val();
            var imgs = [];
            $('#shuoshuo-edit-' + id + ' .shuoshuo-edit-img-url').each(function() {
                var v = $(this).val().trim();
                if (v) imgs.push(v);
            });

            btn.prop('disabled', true).text('保存中...');
            status.text('');

            $.post('<?php echo esc_js(admin_url('admin-ajax.php')); ?>', {
                action: 'shuoshuo_inline_save',
                id: id,
                text: text,
                images: imgs,
                date: date,
                _wpnonce: '<?php echo esc_js(wp_create_nonce('shuoshuo_inline_save')); ?>'
            }, function(res) {
                btn.prop('disabled', false).text('保存');
                if (res.success) {
                    status.text('✓ 已保存').css('color', '#46b450');
                    setTimeout(function() {
                        var preview = text.trim();
                        if (preview.length > 100) preview = preview.substring(0, 100) + '...';
                        if (imgs.length) preview += ' [图' + imgs.length + ']';
                        $('#shuoshuo-row-' + id + ' .shuoshuo-preview').text(preview);
                        var d = new Date(date);
                        var m = d.getMonth() + 1, dy = d.getDate();
                        var h = d.getHours().toString().padStart(2, '0'), mi = d.getMinutes().toString().padStart(2, '0');
                        $('#shuoshuo-row-' + id + ' .shuoshuo-cell-date').text(d.getFullYear() + '-' + m + '-' + dy + ' ' + h + ':' + mi);
                        $('#shuoshuo-edit-' + id).hide();
                        $('#shuoshuo-row-' + id).show();
                    }, 600);
                } else {
                    status.text('✗ 保存失败').css('color', '#dc3232');
                }
            }).fail(function() {
                btn.prop('disabled', false).text('保存');
                status.text('✗ 网络错误').css('color', '#dc3232');
            });
        });
    });
    </script>
    <?php
}

// ========== 前台：归档加载更多 ==========

function shuoshuo_load_more() {
    $paged = isset($_POST['paged']) ? max(1, intval($_POST['paged'])) : 1;
    $current_month = isset($_POST['current_month']) ? sanitize_text_field(wp_unslash($_POST['current_month'])) : '';

    $query = new WP_Query(array(
        'post_type'      => 'shuoshuo',
        'posts_per_page' => 20,
        'paged'          => $paged,
        'orderby'        => 'date',
        'order'          => 'DESC',
    ));
    $html = '';
    $last_month = $current_month;

    if ($query->have_posts()) {
        ob_start();
        while ($query->have_posts()) {
            $query->the_post();
            $this_year_month = get_the_date('Y年n月');
            $is_new_month = ($this_year_month !== $current_month);
            $current_month = $this_year_month;
            $last_month = $this_year_month;

            $parsed = personal_shuoshuo_parse_content(get_the_content());
            $text_content = $parsed['text_html'];
            $img_urls = $parsed['images'];

            if ($is_new_month) {
                echo '<div class="timeline-month-marker"><span class="timeline-month-label">' . esc_html($this_year_month) . '</span></div>';
            }
            ?>
            <div class="timeline-item" id="shuoshuo-<?php the_ID(); ?>">
                <div class="timeline-content">
                    <div class="timeline-meta">
                        <div class="timeline-avatar">
                            <img src="<?php echo esc_url(get_avatar_profile_url(get_the_author_meta('ID'))); ?>" alt="" width="36" height="36">
                        </div>
                        <div>
                            <span class="timeline-author"><?php the_author(); ?></span>
                            <span class="timeline-date"><?php the_time('n月j日 H:i'); ?></span>
                        </div>
                    </div>
                    <?php if (!empty($text_content)): ?>
                    <div class="timeline-body">
                        <?php echo wp_kses_post($text_content); ?>
                    </div>
                    <?php endif; ?>
                    <?php if (!empty($img_urls)): ?>
                    <div class="timeline-images shuoshuo-img-grid-<?php echo (int) min(count($img_urls), 9); ?>">
                        <?php foreach ($img_urls as $url): ?>
                            <div class="timeline-img-item">
                                <img src="<?php echo esc_url($url); ?>" alt="" loading="lazy" onclick="shuoshuoLightbox(this.src)">
                            </div>
                        <?php endforeach; ?>
                    </div>
                    <?php endif; ?>
                </div>
            </div>
            <?php
        }
        $html = ob_get_clean();
    }
    wp_reset_postdata();

    wp_send_json_success(array(
        'html'       => $html,
        'last_month' => $last_month,
    ));
}
add_action('wp_ajax_shuoshuo_load_more', 'shuoshuo_load_more');
add_action('wp_ajax_nopriv_shuoshuo_load_more', 'shuoshuo_load_more');

// ========== 后台：行内保存 ==========

function shuoshuo_inline_save() {
    if (!current_user_can('publish_posts')) {
        wp_send_json_error('权限不足');
    }
    check_ajax_referer('shuoshuo_inline_save');

    $id = isset($_POST['id']) ? intval($_POST['id']) : 0;
    $post = get_post($id);
    if (!$post || $post->post_type !== 'shuoshuo' || !current_user_can('edit_post', $id)) {
        wp_send_json_error('权限不足');
    }
    $text = isset($_POST['text']) ? wp_kses_post(wp_unslash($_POST['text'])) : '';
    $images = isset($_POST['images']) ? array_map('esc_url_raw', array_filter((array) wp_unslash($_POST['images']))) : array();
    $images = array_slice($images, 0, 9);
    $date = isset($_POST['date']) ? sanitize_text_field(wp_unslash($_POST['date'])) : '';

    $post_data = array(
        'ID'           => $id,
        'post_content' => personal_shuoshuo_build_content($text, $images),
    );
    if (!empty($date)) {
        $ts = strtotime($date);
        if ($ts !== false) {
            $post_data['post_date'] = date('Y-m-d H:i:s', $ts);
        }
    }

    $result = wp_update_post($post_data, true);
    if (is_wp_error($result)) {
        wp_send_json_error($result->get_error_message());
    }
    wp_send_json_success();
}
add_action('wp_ajax_shuoshuo_inline_save', 'shuoshuo_inline_save');
