<?php 
/**
 * Template Name: Timearchive Template
 */
get_header();
?>
<link href="https://fonts.googleapis.com/css2?family=Noto+Serif+SC:wght@900&display=swap" rel="stylesheet">
<style>
    /* 基础布局 */
    .site-content {
        max-width: 900px;
    }

    span.linkss-title {
        font-size: 30px;
        text-align: center;
        display: block;
        margin: 6.5% 0 7.5%;
        letter-spacing: 2px;
        font-weight: var(--global-font-weight);
    }

    /* 时间线容器 */
    .timeline-root {
        margin: 5rem auto 6rem auto;
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 2.5rem;
        padding: 0 1rem;
    }

    /* 年份卡片 */
    .timeline-year-card {
        background: rgba(255,255,255,0.7);
        border-radius: 1.2rem;
        box-shadow: 0 4px 24px 0 rgba(0,0,0,0.07);
        padding: 1.2rem 1.2rem 1.2rem 1.2rem;
        display: flex;
        flex-direction: column;
        position: relative;
        min-width: 0;
        cursor: pointer;
        transition: background 0.45s cubic-bezier(.4,2,.6,1),
                    box-shadow 0.45s cubic-bezier(.4,2,.6,1),
                    border-color 0.45s cubic-bezier(.4,2,.6,1),
                    color 0.45s cubic-bezier(.4,2,.6,1),
                    transform 0.3s cubic-bezier(.4,2,.6,1);
        overflow: visible;
        border: 1.5px solid #e0e0e0;
    }
    .timeline-year-card:hover {
        box-shadow: 0 8px 48px 0 rgba(0,0,0,0.13);
        transform: translateY(-2px) scale(1.02);
        background: rgba(255,255,255,0.92);
        border-color: var(--theme-skin-matching, #505050);
        transition: background 0.25s cubic-bezier(.4,2,.6,1),
                    box-shadow 0.25s cubic-bezier(.4,2,.6,1),
                    border-color 0.25s cubic-bezier(.4,2,.6,1),
                    color 0.25s cubic-bezier(.4,2,.6,1),
                    transform 0.3s cubic-bezier(.4,2,.6,1);
    }
    body.dark .timeline-year-card {
        background: rgba(30,30,30,0.92);
        box-shadow: 0 4px 24px 0 rgba(0,0,0,0.25);
        border: 1.5px solid #333;
        color: var(--dark-text-primary, #fff);
        transition: background 0.45s cubic-bezier(.4,2,.6,1),
                    box-shadow 0.45s cubic-bezier(.4,2,.6,1),
                    border-color 0.45s cubic-bezier(.4,2,.6,1),
                    color 0.45s cubic-bezier(.4,2,.6,1),
                    transform 0.3s cubic-bezier(.4,2,.6,1);
    }
    body.dark .timeline-year-card:hover {
        background: rgba(40,40,40,0.98);
        box-shadow: 0 8px 48px 0 rgba(0,0,0,0.32);
        border-color: var(--theme-skin-matching, #fff);
        transform: translateY(-2px) scale(1.02);
        transition: background 0.25s cubic-bezier(.4,2,.6,1),
                    box-shadow 0.25s cubic-bezier(.4,2,.6,1),
                    border-color 0.25s cubic-bezier(.4,2,.6,1),
                    color 0.25s cubic-bezier(.4,2,.6,1),
                    transform 0.3s cubic-bezier(.4,2,.6,1);
    }
    .timeline-year-header {
        display: flex;
        align-items: flex-start;
        justify-content: space-between;
        width: 100%;
        margin-bottom: 0.5rem;
    }
    .timeline-year-number {
        font-size: 1.5rem;
        font-family: 'Noto Serif SC';
        font-weight: 900; 
        font-variation-settings: 'wght' 900; /* 添加变量字体控制 */
        color: var(--theme-skin, #222);
        line-height: 1;
        flex-shrink: 0;
    }

    /* 深色模式下的年份数字样式 */
    body.dark .timeline-year-number {
        color: rgba(255,255,255,0.8);
    }
    .timeline-year-count {
        font-size: 1rem;
        color: #888;
        font-weight: 400;
        margin-left: 0.5rem;
    }
    .timeline-year-calendar {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        grid-template-rows: repeat(3, 1fr);
        gap: 1rem;
        margin-top: 0.5rem;
        width: 100%;
        justify-items: center;
        align-items: center;
    }
    .timeline-year-calendar-month {
        width: 2.3rem;
        height: 2.3rem;
        border-radius: 50%;
        background: #eaeaea;
        color: #bbb;
        font-size: 1rem;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: 500;
        transition: background 0.35s cubic-bezier(.4,2,.6,1),
                    color 0.35s cubic-bezier(.4,2,.6,1),
                    box-shadow 0.35s cubic-bezier(.4,2,.6,1),
                    filter 0.35s cubic-bezier(.4,2,.6,1),
                    opacity 0.35s cubic-bezier(.4,2,.6,1),
                    border-color 0.35s cubic-bezier(.4,2,.6,1);
        border: 1.5px solid #e0e0e0;
        box-shadow: none;
        user-select: none;
        opacity: 0.7;
        filter: brightness(1);
        will-change: background, color, box-shadow, filter, opacity, border;
    }
    .timeline-year-calendar-month.active {
        background: var(--theme-skin-matching, #505050);
        color: #fff;
        box-shadow: 0 2px 8px 0 rgba(80,80,80,0.10);
        opacity: 1;
        filter: brightness(1.08) drop-shadow(0 2px 6px rgba(80,80,80,0.10));
        animation: monthActivePop 0.5s cubic-bezier(.4,2,.6,1);
        border: 1.5px solid var(--theme-skin-matching, #505050);
    }
    @keyframes monthActivePop {
        0% { transform: scale(0.7); opacity: 0.3; }
        60% { transform: scale(1.15); opacity: 1; }
        100% { transform: scale(1); opacity: 1; }
    }

    /* 弹窗遮罩和内容 */    /* 模态框遮罩层 */
    .timeline-modal-mask {
        height: 100vh;
        width: 100vw;
        max-height: none;
        max-width: none;
        margin: 0;
        border: none;
        position: fixed;
        z-index: 9999;
        left: 0; 
        top: 0; 
        right: 0; 
        bottom: 0;
        background: rgba(0,0,0,0.25);
        display: flex;
        align-items: center;
        justify-content: center;
        opacity: 0;
        pointer-events: none;
        transition: opacity 0.3s cubic-bezier(.4,2,.6,1);
    }

    .timeline-modal-mask.active {
        opacity: 1;
        pointer-events: auto;
    }

    /* 模态框基础样式 */
    .timeline-modal {
        background: #fff;
        border-radius: 1.2rem;
        box-shadow: 0 8px 48px 0 rgba(0,0,0,0.18);
        max-width: 520px;
        width: 92vw;
        max-height: 80vh;
        overflow: auto;
        padding: 1rem;
        position: relative;
        transform: translateY(40px) scale(0.98);
        opacity: 0;
        transition: all 0.35s cubic-bezier(.4,2,.6,1);
        animation: timelineModalIn 0.35s cubic-bezier(.4,2,.6,1);
        z-index: 9999;
    }
    .timeline-modal-mask.active .timeline-modal {
        opacity: 1;
        transform: translateY(0) scale(1);
    }
    @keyframes timelineModalIn {
        0% { opacity: 0; transform: translateY(40px) scale(0.98); }
        100% { opacity: 1; transform: translateY(0) scale(1); }
    }
    .timeline-modal-close {
        position: absolute;
        right: 1rem;
        top: 1rem;
        width: 2.2rem;
        height: 2.2rem;
        border-radius: 50%;
        background: #f2f2f2;
        color: #888;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1rem;
        cursor: pointer;
        transition: background 0.2s;
        z-index: 2;
    }
    .timeline-modal-close:hover {
        background: #e0e0e0;
        color: #222;
    }
    .timeline-modal-title {
        font-family: 'Noto Serif SC';
        font-size: 1.6rem;
        font-weight: 900;
        font-variation-settings: 'wght' 900; /* 添加变量字体控制 */
        letter-spacing: 0.04em;
        color: var(--theme-skin, #222);
        border-radius: 1.1rem 1.1rem 0 0;
        margin: 0;
        padding: 0 0 0.55rem 0.55rem;
        box-shadow: 0 2px 12px 0 rgba(80,80,80,0.04);
    }
    
    /* 统计数据布局 */
    .timeline-modal-stats-grid {
        display: grid;
        gap: 0.7rem;
        margin-bottom: 1.5rem;
        grid-template-columns: repeat(4, 1fr);
        padding: 0 10px;
    }
    
    /* 统计数据卡片 */    
    .timeline-modal-statbox {
        background: rgba(0,0,0,0.04);
        border-radius: 1.1rem;
        padding: 1.1rem 1rem;
        min-width: 110px;
        text-align: center;
        display: flex;
        flex-direction: column;
        align-items: center;
        transition: transform 0.2s, box-shadow 0.2s;
        border: 1.5px solid rgba(0,0,0,0.08);
        position: relative;
        z-index: 3;
    }
    
    .timeline-modal-statbox .stat-tooltip {
        position: absolute;
        bottom: 100%;
        left: 50%;
        transform: translateX(-50%) translateY(-10px);
        background: rgba(0,0,0,0.85);
        color: #fff;
        padding: 0.4rem 0.6rem;
        border-radius: 0.5rem;
        font-size: 0.7rem;
        white-space: nowrap;
        pointer-events: none;
        opacity: 0;
        visibility: hidden;
        transition: all 0.25s cubic-bezier(.4,2,.6,1);
        box-shadow: 0 4px 12px rgba(0,0,0,0.15);
        z-index: 999;
    }
    
    .timeline-modal-statbox .stat-tooltip::after {
        content: '';
        position: absolute;
        top: 100%;
        left: 50%;
        transform: translateX(-50%);
        border: 6px solid transparent;
        border-top-color: rgba(0,0,0,0.85);
    }
    
    .timeline-modal-statbox:hover .stat-tooltip {
        opacity: 1;
        visibility: visible;
        transform: translateX(-50%) translateY(0);
    }
    .timeline-modal-statbox:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 16px rgba(0,0,0,0.12);
    }
    .timeline-modal-statbox .stat-icon {
        font-size: 1.15rem;
        color: var(--theme-skin, #505050);
        margin-bottom: 0.4rem;
        opacity: 0.7;
    }
    .timeline-modal-statbox .stat-label {
        font-size: 0.92rem;
        color: #666;
        margin-bottom: 0.3rem;
        font-weight: 500;
    }
    .timeline-modal-statbox .stat-value {
        font-size: 1.4rem;
        font-weight: 800;
        color: var(--theme-skin, #222);
        line-height: 1.2;
    }
    .timeline-modal-month-group {
        margin: 0 0.5rem 1rem 0.5rem;
    }
    .timeline-modal-month-title {
        font-size: 1.15rem;
        font-weight: 700;
        color: var(--theme-skin, #505050);
        margin-bottom: 1rem;
        padding-left: 1rem;
        position: relative;
    }
    .timeline-modal-month-title::before {
        content: '';
        position: absolute;
        left: 0;
        top: 50%;
        width: 3px;
        height: 1.2em;
        background: var(--theme-skin-matching, #505050);
        transform: translateY(-50%);
        border-radius: 3px;
        opacity: 0.7;
    }
    .timeline-modal-post-list {
        display: grid;
        gap: 0.8rem;
    }
    .timeline-modal-post-item {
        display: flex;
        align-items: center;
        gap: 1rem;
        padding: 0.9rem 1.2rem;
        border-radius: 1rem;
        background: rgba(0,0,0,0.03);
        position: relative;
        transition: all 0.3s cubic-bezier(.4,2,.6,1);
        border: 1px solid rgba(0,0,0,0.08);
        box-shadow: 0 2px 8px rgba(0,0,0,0.02);
        text-decoration: none;
        cursor: pointer;
        color: inherit;
    }
    .timeline-modal-post-item:hover {
        background: rgba(0,0,0,0.05);
        transform: translateX(4px);
        box-shadow: 0 2px 12px rgba(0,0,0,0.08);
        text-decoration: none;
    }
    .timeline-modal-post-title {
        flex: 1;
        font-weight: 500;
        font-size: 0.95rem;
        color: var(--theme-skin, #222);
        line-height: 1.5;
    }
    .timeline-modal-post-date {
        font-size: 0.85rem;
        color: #888;
        background: rgba(0,0,0,0.03);
        padding: 0.3rem 0.6rem;
        border-radius: 0.5rem;
        letter-spacing: 0.05em;
    }
    body.dark .timeline-modal-month-title {
        color: #fff;
    }
    body.dark .timeline-modal-post-date {
        color: #aaa;
        background: rgba(255,255,255,0.1);
    }
    
    /* 深色模式 - 模态框基础样式 */
    body.dark .timeline-modal {
        background: rgba(25,25,25,0.95);
        backdrop-filter: blur(20px);
        -webkit-backdrop-filter: blur(20px);
        border: 1px solid rgba(255,255,255,0.08);
        box-shadow: 0 8px 32px rgba(0,0,0,0.4);
    }
    
    /* 深色模式 - 遮罩层 */
    body.dark .timeline-modal-mask {
        background: rgba(0,0,0,0.4);
    }

    body.dark .timeline-modal-close {
        background: rgba(255,255,255,0.1);
        color: rgba(255,255,255,0.6);
        border: 1px solid rgba(255,255,255,0.08);
    }
    
    body.dark .timeline-modal-close:hover {
        background: rgba(255,255,255,0.15);
        color: rgba(255,255,255,0.9);
        border-color: rgba(255,255,255,0.15);
    }
    
    body.dark .timeline-modal-title {
        color: rgba(255,255,255,0.9);
    }
    
    body.dark .timeline-modal-statbox {
        background: rgba(255,255,255,0.04);
        border: 1.5px solid rgba(255,255,255,0.08);
        backdrop-filter: blur(10px);
        -webkit-backdrop-filter: blur(10px);
    }
    
    body.dark .timeline-modal-statbox:hover {
        background: rgba(255,255,255,0.06);
        border-color: rgba(255,255,255,0.12);
        box-shadow: 0 4px 20px rgba(0,0,0,0.3);
        transform: translateY(-2px);
    }
    
    body.dark .timeline-modal-statbox .stat-icon {
        color: rgba(255,255,255,0.7);
        text-shadow: 0 2px 4px rgba(0,0,0,0.2);
    }
    
    body.dark .timeline-modal-statbox .stat-label {
        color: rgba(255,255,255,0.5);
    }
    
    body.dark .timeline-modal-statbox .stat-value {
        color: rgba(255,255,255,0.95);
        text-shadow: 0 2px 8px rgba(0,0,0,0.2);
    }
    
    body.dark .timeline-modal-month-title {
        color: rgba(255,255,255,0.9);
    }
    
    body.dark .timeline-modal-month-title::before {
        background: var(--theme-skin-matching, rgba(255,255,255,0.8));
        box-shadow: 0 2px 8px rgba(0,0,0,0.2);
    }
    
    body.dark .timeline-modal-post-list {
        gap: 0.7rem;
    }
    
    body.dark .timeline-modal-post-item {
        background: rgba(255,255,255,0.03);
        border: 1px solid rgba(255,255,255,0.06);
        backdrop-filter: blur(8px);
        -webkit-backdrop-filter: blur(8px);
    }
    
    body.dark .timeline-modal-post-item:hover {
        background: rgba(255,255,255,0.05);
        border-color: rgba(255,255,255,0.1);
        box-shadow: 0 2px 16px rgba(0,0,0,0.25);
        transform: translateX(4px) scale(1.01);
    }
    
    body.dark .timeline-modal-post-title {
        color: rgba(255,255,255,0.9);
    }
    
    body.dark .timeline-modal-post-date {
        color: rgba(255,255,255,0.5);
        background: rgba(255,255,255,0.08);
        border: 1px solid rgba(255,255,255,0.06);
    }
    
    /* 自定义滚动条 */
    /* 深色模式滚动条 */
    body.dark .timeline-modal::-webkit-scrollbar {
        width: 8px;
        height: 8px;
    }
    
    /* 滚动条轨道 */
    body.dark .timeline-modal::-webkit-scrollbar-track {
        background: rgba(255,255,255,0.02);
        border-radius: 4px;
    }
    
    /* 滚动条滑块 */
    body.dark .timeline-modal::-webkit-scrollbar-thumb {
        background: rgba(255,255,255,0.1);
        border-radius: 4px;
        transition: all 0.3s ease;
    }
    
    /* 滚动条悬停效果 */
    body.dark .timeline-modal::-webkit-scrollbar-thumb:hover {
        background: rgba(255,255,255,0.15);
    }
    
    /* 动画效果 */
    /* 深色模式专用动画 */
    body.dark .timeline-modal {
        animation: darkTimelineModalIn 0.35s cubic-bezier(.4,2,.6,1);
    }
    
    @keyframes darkTimelineModalIn {
        0% { 
            opacity: 0;
            transform: translateY(40px) scale(0.98);
            filter: brightness(0.8);
        }
        100% { 
            opacity: 1;
            transform: translateY(0) scale(1);
            filter: brightness(1);
        }
    }
    
    /* 月份圆点样式 */
    /* 深色模式 - 默认状态 */
    body.dark .timeline-year-calendar-month {
        background: rgba(255,255,255,0.08);
        color: #aaa;
        border: 1.5px solid #333;
        box-shadow: none;
        opacity: 0.8;
        filter: brightness(1.05);
    }

    /* 深色模式 - 激活状态 */
    body.dark .timeline-year-calendar-month.active {
        background: var(--theme-skin-matching, #fff);
        color: #222;
        box-shadow: 0 2px 20px -10px var(--theme-skin-matching, #fff);
        opacity: 1;
        filter: brightness(1.12) drop-shadow(0 2px 6px rgba(255,255,255,0.05));
        border: 1.5px solid var(--theme-skin-matching, #fff);
        animation: monthActivePop 0.5s cubic-bezier(.4,2,.6,1);
    }
        
    /* 响应式布局适配 */
    /* 平板布局 */
    @media (max-width: 900px) {
        .timeline-root {
            grid-template-columns: repeat(2, 1fr);
        }
        .site-content {
            padding: 0 2% !important;
        }
    }

    /* 移动端布局 */
    @media (max-width: 600px) {
        .timeline-root {
            grid-template-columns: 1fr;
        }
        .timeline-modal-stats-grid {
            grid-template-columns: repeat(2, 1fr);
        }
        .timeline-modal {
            max-height: 82.5vh !important;
        }
        .timeline-year-calendar{
           grid-template-columns: repeat(6, 1fr);
           grid-template-rows: repeat(2, 1fr);
        }
        .timeline-year-number{
           font-size: 1.6rem;
        }
    }

    .timeline-modal::-webkit-scrollbar-track {
        margin: 5px;
    }

</style>
<?php 
    if (!iro_opt('patternimg') || !get_post_thumbnail_id(get_the_ID())) { 
    ?>
        <span class="linkss-title"><?php the_title(); ?></span>
    <?php 
    } 
    ?>
<div class="timeline-root" id="timeline-root">
<?php
$years = get_transient('time_archive');
if (!$years) {
    $years = get_archive_info();
    set_transient('time_archive',$time_archive,86400);
}
foreach ($years as $year => $months) {
    $postCount = array_sum(array_map('count', $months));
    $activeMonths = array_fill(1, 12, false);
    foreach ($months as $m => $arr) {
        $activeMonths[(int)$m] = count($arr) > 0;
    }
    // 年份卡片
    echo '<section class="timeline-year-card" tabindex="0" data-year="' . $year . '" data-months="'. $year .'">';
    echo '<div class="timeline-year-header">';
    echo '<span class="timeline-year-number">' . $year . '</span>';
    echo '<span class="timeline-year-count">' . $postCount . ' ' . __('Posts', 'sakurairo') . '</span>';
    echo '</div>';
    // 月份日历
    echo '<div class="timeline-year-calendar">';
    for ($i = 1; $i <= 12; $i++) {
        $active = $activeMonths[$i] ? 'active' : '';
        echo '<span class="timeline-year-calendar-month ' . $active . '">' . $i . '</span>';
    }
    echo '</div>';
    echo '</section>';
}
?>
<dialog id="timeline-modal-mask" class="timeline-modal-mask"><div class="timeline-modal"><span class="timeline-modal-close" id="timeline-modal-close">×</span><div id="timeline-modal-content" data-archiveapi=<?php echo rest_url('sakura/v1/archive_info');?>></div></div></dialog>
</div>
<?php get_footer(); ?>
