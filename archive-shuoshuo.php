<?php
/**
 * 说说归档页面 - 时间轴样式（无限滚动）
 *
 * 属于 user/modules/shuoshuo.php 的前端模板。
 * WordPress 模板层级要求本文件位于主题根目录。
 */

get_header();
global $wp_query;
$max_pages = $wp_query->max_num_pages;
?>

<div id="primary" class="content-area">
<main id="main" class="site-main" role="main">

<?php if (have_posts()) : ?>

    <header class="page-header" style="text-align:center;padding:30px 0 10px;">
        <p style="color:#999;font-size:14px;margin:0;">共 <?php echo $wp_query->found_posts; ?> 条动态</p>
    </header>

    <div class="shuoshuo-timeline" id="shuoshuo-timeline">
        <?php
        $current_year_month = '';
        while (have_posts()) : the_post();
            $this_year_month = get_the_date('Y年n月');
            $is_new_month = ($this_year_month !== $current_year_month);
            $current_year_month = $this_year_month;

            $raw_content = get_the_content();
            $img_urls = array();
            if (preg_match_all('/<img[^>]+src=["\']([^"\']+)["\']/', $raw_content, $m)) {
                $img_urls = $m[1];
            }
            $text_content = preg_replace('/<!--.*?-->/s', '', $raw_content);
            $text_content = preg_replace('/<img[^>]*>/', '', $text_content);
            $text_content = trim($text_content);
        ?>

            <?php if ($is_new_month): ?>
                <div class="timeline-month-marker">
                    <span class="timeline-month-label"><?php echo esc_html($this_year_month); ?></span>
                </div>
            <?php endif; ?>

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
                    <div class="timeline-images shuoshuo-img-grid-<?php echo min(count($img_urls), 9); ?>">
                        <?php foreach ($img_urls as $url): ?>
                            <div class="timeline-img-item">
                                <img src="<?php echo esc_url($url); ?>" alt="" loading="lazy" onclick="shuoshuoLightbox(this.src)">
                            </div>
                        <?php endforeach; ?>
                    </div>
                    <?php endif; ?>
                </div>
            </div>

        <?php endwhile; ?>
    </div>

    <?php if ($max_pages > 1): ?>
    <div id="shuoshuo-loadmore" style="text-align:center;padding:30px 0;">
        <span id="shuoshuo-loadmore-btn" style="display:inline-block;background:#667eea;color:#fff;padding:10px 28px;border-radius:24px;font-size:14px;cursor:pointer;">更早的说说</span>
    </div>
    <?php endif; ?>
    <div id="shuoshuo-end" style="text-align:center;padding:30px 0;display:none;color:#999;font-size:13px;">— 没有更多了 —</div>

    <!-- 图片灯箱 -->
    <div id="shuoshuo-lightbox" style="display:none;position:fixed;top:0;left:0;width:100%;height:100%;background:rgba(0,0,0,0.85);z-index:99999;cursor:pointer;align-items:center;justify-content:center;" onclick="this.style.display='none'">
        <img id="shuoshuo-lightbox-img" src="" style="max-width:90%;max-height:90%;border-radius:8px;">
    </div>

    <script>
    function shuoshuoLightbox(src) {
        document.getElementById('shuoshuo-lightbox-img').src = src;
        document.getElementById('shuoshuo-lightbox').style.display = 'flex';
    }

    (function(){
        var page = 1, maxPage = <?php echo $max_pages; ?>, loading = false;
        var month = '<?php echo esc_js($current_year_month); ?>';
        var btn = document.getElementById('shuoshuo-loadmore-btn');
        var endMsg = document.getElementById('shuoshuo-end');
        var wrap = document.getElementById('shuoshuo-loadmore');
        var timeline = document.getElementById('shuoshuo-timeline');

        if (!btn) return;

        btn.addEventListener('click', function() {
            if (loading || page >= maxPage) return;
            loading = true;
            btn.textContent = '加载中...';
            btn.style.opacity = '0.6';

            var xhr = new XMLHttpRequest();
            xhr.open('POST', '<?php echo admin_url("admin-ajax.php"); ?>', true);
            xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
            xhr.onreadystatechange = function() {
                if (xhr.readyState !== 4) return;
                loading = false;
                if (xhr.status === 200) {
                    try {
                        var res = JSON.parse(xhr.responseText);
                        if (res.success && res.data.html && res.data.html.trim().length > 0) {
                            timeline.insertAdjacentHTML('beforeend', res.data.html);
                            month = res.data.last_month || month;
                            page++;
                            if (page >= maxPage) {
                                endMsg.style.display = 'block';
                                wrap.style.display = 'none';
                            } else {
                                btn.textContent = '更早的说说';
                                btn.style.opacity = '1';
                            }
                        } else {
                            endMsg.style.display = 'block';
                            wrap.style.display = 'none';
                        }
                    } catch(e) {
                        btn.textContent = '更早的说说';
                        btn.style.opacity = '1';
                    }
                } else {
                    btn.textContent = '更早的说说';
                    btn.style.opacity = '1';
                }
            };
            xhr.send('action=shuoshuo_load_more&paged=' + (page + 1) + '&current_month=' + encodeURIComponent(month));
        });
    })();
    </script>

<?php else : ?>
    <div style="text-align:center;padding:80px 0;color:#999;">
        <p style="font-size:48px;">📝</p>
        <p>还没有说说~</p>
    </div>
<?php endif; ?>

</main>
</div>

<style>
.shuoshuo-timeline {
    max-width: 680px;
    margin: 0 auto;
    padding: 0 20px;
}

/* 月份标记 */
.timeline-month-marker {
    text-align: center;
    margin: 36px 0 20px;
    position: relative;
}

.timeline-month-marker:first-child {
    margin-top: 0;
}

.timeline-month-label {
    display: inline-block;
    background: var(--shuoshuo-accent, #667eea);
    color: #fff;
    padding: 5px 20px;
    border-radius: 20px;
    font-size: 13px;
    font-weight: 600;
    letter-spacing: 1px;
    box-shadow: 0 2px 8px rgba(102,126,234,0.3);
}

/* 单条说说 */
.timeline-item {
    margin-bottom: 20px;
}

.timeline-content {
    background: var(--shuoshuo-card-bg, #fff);
    border-radius: 12px;
    padding: 16px 18px;
    box-shadow: 0 1px 6px var(--shuoshuo-shadow, rgba(0,0,0,0.06));
    border: 1px solid var(--shuoshuo-card-border, transparent);
    transition: box-shadow 0.2s;
}

.timeline-content:hover {
    box-shadow: 0 4px 16px var(--shuoshuo-shadow-hover, rgba(0,0,0,0.1));
}

.timeline-meta {
    display: flex;
    align-items: center;
    gap: 10px;
    margin-bottom: 10px;
}

.timeline-avatar img {
    width: 36px;
    height: 36px;
    border-radius: 50%;
    border: 2px solid var(--shuoshuo-avatar-border, #fff);
    box-shadow: 0 1px 4px rgba(0,0,0,0.12);
    object-fit: cover;
}

.timeline-author {
    font-weight: 600;
    font-size: 14px;
    color: var(--shuoshuo-text, #333);
}

.timeline-date {
    font-size: 12px;
    color: var(--shuoshuo-text-sub, #999);
}

.timeline-body {
    font-size: 15px;
    line-height: 1.7;
    color: var(--shuoshuo-text-body, #444);
    word-break: break-word;
}

.timeline-body p { margin: 0 0 8px; }
.timeline-body p:last-child { margin-bottom: 0; }

/* 图片九宫格 */
.timeline-images {
    display: grid;
    gap: 5px;
    margin-top: 10px;
    border-radius: 10px;
    overflow: hidden;
}

.shuoshuo-img-grid-1 { grid-template-columns: 1fr; max-width: 320px; }
.shuoshuo-img-grid-2 { grid-template-columns: 1fr 1fr; max-width: 400px; }
.shuoshuo-img-grid-3 { grid-template-columns: 1fr 1fr 1fr; max-width: 420px; }
.shuoshuo-img-grid-4 { grid-template-columns: 1fr 1fr; max-width: 400px; }
.shuoshuo-img-grid-5,
.shuoshuo-img-grid-6,
.shuoshuo-img-grid-7,
.shuoshuo-img-grid-8,
.shuoshuo-img-grid-9 { grid-template-columns: 1fr 1fr 1fr; max-width: 420px; }

.timeline-img-item {
    position: relative;
    padding-top: 100%;
    overflow: hidden;
    background: var(--shuoshuo-img-bg, #f5f5f5);
    cursor: pointer;
}

.timeline-img-item img {
    position: absolute;
    top: 0; left: 0;
    width: 100%; height: 100%;
    object-fit: cover;
    transition: transform 0.2s;
}

.timeline-img-item:hover img { transform: scale(1.03); }

.shuoshuo-img-grid-1 .timeline-img-item {
    padding-top: 0;
    max-height: 380px;
}

.shuoshuo-img-grid-1 .timeline-img-item img {
    position: static;
    height: auto;
    max-height: 380px;
}

/* ====== 亮色模式 ====== */
:root {
    --shuoshuo-accent: #667eea;
    --shuoshuo-card-bg: #fff;
    --shuoshuo-card-border: transparent;
    --shuoshuo-avatar-border: #fff;
    --shuoshuo-text: #333;
    --shuoshuo-text-body: #444;
    --shuoshuo-text-sub: #999;
    --shuoshuo-shadow: rgba(0,0,0,0.06);
    --shuoshuo-shadow-hover: rgba(0,0,0,0.1);
    --shuoshuo-img-bg: #f5f5f5;
}

/* ====== 暗色模式 ====== */
body.dark-theme,
body.dark,
body.night,
body[data-theme="dark"],
body.wp-dark-mode-active,
body.dark-mode,
body.jw-dark-mode {
    --shuoshuo-accent: #818cf8;
    --shuoshuo-card-bg: #2a2a2e;
    --shuoshuo-card-border: #3a3a3e;
    --shuoshuo-avatar-border: #3a3a3e;
    --shuoshuo-text: #e4e4e7;
    --shuoshuo-text-body: #d4d4d8;
    --shuoshuo-text-sub: #a1a1aa;
    --shuoshuo-shadow: rgba(0,0,0,0.25);
    --shuoshuo-shadow-hover: rgba(0,0,0,0.35);
    --shuoshuo-img-bg: #333;
}

@media (max-width: 600px) {
    .shuoshuo-timeline { padding: 0 14px; }
    .timeline-content { padding: 14px; }
    .timeline-body { font-size: 14px; }
}
</style>

<?php get_footer(); ?>
