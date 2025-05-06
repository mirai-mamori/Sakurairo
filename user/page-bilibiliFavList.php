<?php
/*
  Template Name: Bilibili FavList Template
*/
get_header();
?>

<style>
    span.linkss-title {
        font-size: 30px;
        text-align: center;
        display: block;
        margin: 6.5% 0 7.5%;
        letter-spacing: 2px;
        font-weight: var(--global-font-weight);
    }
    .site-content {
        max-width: 1280px;
    }

    .fav-grid {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 24px;
        padding: 0 1rem;
        margin-bottom: 50px;
    }
    .fav-section {
        margin-bottom: 30px;
    }
    .fav-content {
        position: relative;
        min-height: 200px;
    }
    .fav-item {
        position: relative;
        border-radius: 16px;
        overflow: hidden;
        background: #fff;
        display: flex;
        flex-direction: column;
        height: 100%;
        box-shadow: 0 1px 3px rgba(0,0,0,0.1);
        cursor: pointer;
        transition: all 0.4s ease; 
        transform: translateY(0);
        opacity: 1;
    }
    
    .fav-item:hover {
        transform: translateY(-6px);
        box-shadow: 0 12px 30px rgba(0,0,0,0.2);
    }
    
    .fav-item:focus, 
    .fav-item:focus-within {
        outline: 2px solid var(--theme-skin-matching, #505050);
        box-shadow: 0 5px 15px rgba(0,0,0,0.15);
    }
    
    .fav-item-content-wrapper {
        display: flex;
        flex-direction: column;
        height: 100%;
    }
    .fav-item-thumb {
        position: relative;
        padding-top: 56.25%;
        overflow: hidden;
        background: #f0f0f0;
        border-radius: 8px 8px 0 0;
    }
    
    .fav-item-thumb img {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        object-fit: cover;
    }
    
    .fav-item-title-area {
        position: relative;
        padding: 16px 16px 8px;
        background: linear-gradient(to bottom, rgba(0,0,0,0.6) 0%, rgba(0,0,0,0) 100%);
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        z-index: 1;
    }
    
    .fav-item-title {
        font-size: 1.1rem;
        font-weight: 700;
        margin: 0;
        line-height: 1.3;
        overflow: hidden;
        text-overflow: ellipsis;
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        color: #fff;
        text-shadow: 0 1px 3px rgba(0,0,0,0.3);
    }

    .fav-item-up {
        position: absolute;
        bottom: 0;
        right: 0;
        padding: 6px 10px;
        margin: 5px;
        background: linear-gradient(to left, rgba(0,0,0,0.7) 0%, rgba(0,0,0,0.3) 100%, transparent);
        color: #fff;
        font-size: 0.8rem;
        border-radius: 8px;
        z-index: 2;
        max-width: 70%;
        text-align: right;
    }

    .fav-item-up .up-name {
        font-weight: 500;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
        text-shadow: 0 1px 2px rgba(0,0,0,0.5);
        display: block;
    }
    .fav-item-desc-wrapper {
        padding: 14px 16px;
        height: calc(3rem + 28px);
        box-sizing: border-box;
        overflow: hidden;
        position: relative;
    }
    
    .fav-item-desc {
        font-size: 0.9rem;
        color: #555;
        overflow: hidden;
        text-overflow: ellipsis;
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        line-height: 1.5;
        max-height: 3rem;
        word-break: break-word;
        margin: 0;
    }
    .fav-pagination {
        display: flex;
        justify-content: center;
        margin: 40px 0 20px;
        gap: 6px;
    }
    
    .page-btn {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        min-width: 40px;
        height: 40px;
        padding: 0 12px;
        border-radius: 40px;
        background: #fff;
        color: #333;
        font-size: 0.95rem;
        font-weight: 500;
        cursor: pointer;
        transition: all 0.15s ease;
        border: 1px solid #e5e5e5;
    }
    
    .page-btn.prev-btn, .page-btn.next-btn {
        padding: 0 20px;
    }
    
    .page-btn:hover {
        border-color: var(--theme-skin-matching, #505050);
        color: var(--theme-skin-matching, #505050);
    }
    
    .page-btn.active {
        background: var(--theme-skin-matching, #505050);
        color: #fff;
        border-color: var(--theme-skin-matching, #505050);
    }
    
    .page-btn:disabled {
        opacity: 0.4;
        cursor: not-allowed;
        border-color: #e5e5e5;
        color: #999;
    }
    
    body.dark .page-btn {
        background: #2d2d2d;
        border-color: #444;
        color: #e0e0e0;
    }
    
    body.dark .page-btn:hover {
        border-color: var(--theme-skin-dark, #eee);
        color: var(--theme-skin-dark, #eee);
    }
    
    body.dark .page-btn.active {
        background: var(--theme-skin-dark, #eee);
        color: #222;
    }
    .fav-item-skeleton .fav-item-thumb-placeholder {
        background-color: #f0f0f0; 
        display: block; 
    }
    
    body.dark .fav-item-skeleton .fav-item-desc {
        background: #3a3a3a;
    }
    body.dark .fav-item-skeleton .fav-item-thumb-placeholder {
        background-color: #2a2a2a;
    }

    .refresh-btn{
        gap:10px;
    }
    .fav-empty {
        text-align: center;
        padding: 40px 0;
        color: #888;
    }
    .video-modal {
        position: fixed;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: rgba(0, 0, 0, 0.85);
        z-index: 9999;
        display: flex;
        align-items: center;
        justify-content: center;
        opacity: 0;
        visibility: hidden;
        transition: opacity 0.3s ease;
        backdrop-filter: blur(5px);
    }
    
    .video-modal.active {
        opacity: 1;
        visibility: visible;
    }
    
    .video-modal-container {
        width: 85%;
        max-width: 960px;
        background: #fff;
        border-radius: 12px;
        overflow: hidden;
        transform: translateY(20px);
        opacity: 0;
        transition: transform 0.3s ease, opacity 0.3s ease;
    }
    
    .video-modal-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 16px 20px;
        border-bottom: 1px solid #eee;
    }
    
    .video-modal-title {
        margin: 0;
        font-size: 1.2rem;
        font-weight: 600;
        color: #333;
    }
    
    .video-modal-close {
        width: 36px;
        height: 36px;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 50%;
        background: #f5f5f5;
        cursor: pointer;
        color: #666;
        transition: all 0.2s ease;
    }
    
    .video-modal-close:hover {
        background: #e5e5e5;
        color: #333;
    }
    
    .video-modal-body {
        position: relative;
        padding-top: 56.25%;
        width: 100%;
        background: #000;
    }
    
    .video-modal-iframe {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        border: none;
    }

    .video-modal-info {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 16px 20px;
        background: #fafafa;
    }
    
    .video-modal-up-name {
        font-weight: 500;
        color: #555;
    }
    
    .video-modal-open {
        display: flex;
        align-items: center;
        gap: 8px;
        color: var(--theme-skin-matching);
        text-decoration: none;
        font-size: 0.9rem;
        padding: 8px 12px;
        border-radius: 4px;
        transition: all 0.2s ease;
    }
    .fav-item {
        cursor: pointer;
        position: relative;
    }
    
    .fav-item-play-btn {
        position: absolute;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
        width: 60px;
        height: 60px;
        background: rgba(0, 0, 0, 0.5);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        opacity: 0;
        transition: all 0.3s ease;
        z-index: 3;
    }
    
    .fav-item:hover .fav-item-play-btn {
        opacity: 1;
        transform: translate(-50%, -50%) scale(1.1);
    }
    body.dark .video-modal-container {
        background: #2a2a2a;
    }
    
    body.dark .video-modal-header {
        border-bottom: 1px solid #3a3a3a;
    }
    
    body.dark .video-modal-title {
        color: #e0e0e0;
    }
    
    body.dark .video-modal-close {
        background: #3a3a3a;
        color: #ccc;
    }
    
    body.dark .video-modal-close:hover {
        background: #444;
        color: #fff;
    }
    
    body.dark .video-modal-info {
        background: #222;
    }
    
    body.dark .video-modal-up-name {
        color: #ccc;
    }
    @media (max-width: 1100px) {
        .fav-grid {
            grid-template-columns: repeat(2, 1fr);
            gap: 20px;
        }
        
        .fav-item-title {
            font-size: 1rem;
        }
    }
      @media (max-width: 768px) {        
        .fav-tabs {
            gap: 8px;
            padding: 0;
        }
        
        .fav-tab {
            padding: 8px 14px;
            font-size: 0.9rem;
        }
        
        .fav-pagination {
            flex-wrap: wrap;
        }
    }
    
    @media (max-width: 560px) {
        .fav-grid {
            grid-template-columns: 1fr;
            gap: 16px;
        }
        
        .fav-item-title{
            line-height: 1.5;
        }

        .fav-item-thumb {
            padding-top: 56.25%;
        }
        
        .page-btn {
            min-width: 36px;
            height: 36px;
            font-size: 0.85rem;
        }
        
        .page-btn.prev-btn, .page-btn.next-btn {
            padding: 0 12px;
        }

        .video-modal-header {
            padding: 12px 15px;
        }
        .video-modal-title {
            font-size: 1rem;
            margin-right: 10px;
        }
        .video-modal-close {
            flex-shrink: 0;
        }
    }
    body.dark .fav-item {
        background: #2d2d2d;
    }
    
    body.dark .fav-item-title {
        color: #e0e0e0;
    }
    
    body.dark .fav-item-up {
        background: linear-gradient(to left, rgba(0,0,0,0.8) 0%, rgba(0,0,0,0.4) 100%, transparent);
    }

    body.dark .fav-item-desc {
        color: #aaa;
    }
    
    body.dark .page-btn {
        background: #3a3a3a;
        color: #e0e0e0;
    }
    
    body.dark .spinner {
        border-color: rgba(255, 255, 255, 0.1);
        border-top-color: var(--theme-skin-dark, #eee);
    }
    .fav-tabs {
        margin: 30px 0;
        display: flex;
        flex-wrap: wrap;
        justify-content: center;
        gap: 10px;
        padding: 0 1rem;
    }
    
    .fav-tab {
        padding: 10px 18px;
        border-radius: 30px;
        background-color: rgba(255, 255, 255, 0.7);
        box-shadow: 0 1px 3px rgba(0,0,0,0.1);
        color: #555;
        font-size: 0.95rem;
        font-weight: 500;
        cursor: pointer;
        transition: all 0.25s ease;
        border: 1px solid transparent;
        display: flex;
        align-items: center;
        white-space: nowrap;
    }
    
    .fav-tab:hover {
        background-color: rgba(255, 255, 255, 0.9);
        box-shadow: 0 5px 15px rgba(0,0,0,0.15);
        transform: translateY(-2px);
    }
    
    .fav-tab.active {
        background-color: var(--theme-skin-matching, #505050);
        color: #fff;
        box-shadow: 0 5px 15px rgba(0, 0, 0, 0.15);
    }
    
    .fav-tab-count {
        background: rgba(0, 0, 0, 0.1);
        border-radius: 12px;
        padding: 2px 8px;
        font-size: 0.8rem;
        margin-left: 8px;
    }
    
    .fav-tab.active .fav-tab-count {
        background: rgba(255, 255, 255, 0.25);
    }
    
    body.dark .fav-tab.active {
        background-color: var(--theme-skin-matching, #505050);
    }

    body.dark .fav-tab.active:hover {
        background-color: var(--theme-skin-matching, #505050);
    }

    body.dark .fav-tab {
        background-color: var(--dark-bg-secondary);
        color: #e0e0e0;
    }
    
    body.dark .fav-tab:hover {
        background-color: var(--dark-bg-hover);
    }
    
    body.dark .fav-tab-count {
        background: rgba(255, 255, 255, 0.1);
    }
    .fav-item.fav-item-enter {
        opacity: 0;
        transform: translateY(20px);
    }
    .fav-item.fav-item-exit {
        opacity: 0;
        transform: translateY(-20px);
        pointer-events: none;
    }
    .fav-item-thumb-placeholder {
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background-color: #f0f0f0;
        display: block;
        z-index: 0;
    }
    
    .fav-item-thumb img {
        z-index: 1;
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        object-fit: cover;
        transition: opacity 0.3s ease;
        opacity: 0;
    }
    
    .fav-item-thumb img.loaded {
        opacity: 1;
    }
    
    .fav-item-thumb img.error {
        opacity: 0.7;
    }
    
    body.dark .fav-item-thumb-placeholder {
        background-color: #2a2a2a;
    }
</style>
</head>

<?php while (have_posts()) : the_post(); ?>
    <?php $bgm = (iro_opt('bilibili_id')) ? new \Sakura\API\BilibiliFavList() : null; ?>
    <?php if (!empty($bgm) && (!iro_opt('patternimg') || !get_post_thumbnail_id(get_the_ID()))) : ?>
        <span class="linkss-title"><?php the_title(); ?></span>
    <?php endif; ?>
    <article <?php post_class("post-item"); ?>>
        <?php the_content('', true); ?>

        <?php if (!empty($bgm)) : ?>            
            <div id="bilibili-favlist-app">
                <!-- 初始加载状态使用预占位卡片 -->
                <div class="fav-section">
                    <div class="fav-content">
                        <div class="fav-grid">
                            <?php for ($i = 0; $i < 9; $i++): ?>                            
                                <div class="fav-item fav-item-skeleton">
                                    <div class="fav-item-content-wrapper">
                                        <div class="fav-item-thumb">
                                            <div class="fav-item-thumb-placeholder"></div>
                                        </div>
                                    <div class="fav-item-desc-wrapper">
                                        <div class="fav-item-desc"></div>
                                        <div class="fav-item-desc"></div>
                                    </div>
                                </div>
                            </div>
                            <?php endfor; ?>
                        </div>
                    </div>
                </div>
            </div>

            <dialog class="video-modal">
                <div class="video-modal-container">
                    <div class="video-modal-header">
                        <h3 class="video-modal-title"></h3>
                        <div class="video-modal-close" role="button" aria-label="关闭视频播放器">
                            <svg viewBox="0 0 24 24" width="24" height="24" stroke="currentColor" stroke-width="2" fill="none" stroke-linecap="round" stroke-linejoin="round"><line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line></svg>
                        </div>
                    </div>
                    <div class="video-modal-body">
                        <iframe class="video-modal-iframe" src="about:blank" frameborder="0" scrolling="no" sandbox="allow-scripts allow-same-origin allow-presentation allow-forms" allow="autoplay; fullscreen" allowfullscreen></iframe>
                    </div>
                    <div class="video-modal-info">
                        <div class="video-modal-up">
                            <span class="video-modal-up-name"></span>
                        </div>
                        <a class="video-modal-open" href="" target="_blank" rel="noopener noreferrer">
                            <svg viewBox="0 0 24 24" width="16" height="16" stroke="currentColor" stroke-width="2" fill="none" stroke-linecap="round" stroke-linejoin="round"><path d="M18 13v6a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h6"></path><polyline points="15 3 21 3 21 9"></polyline><line x1="10" y1="14" x2="21" y2="3"></line></svg>
                            在B站打开
                        </a>
                    </div>
                </div>
            </dialog>
        <?php else : ?>
            <div class="row">
                <p> <?php _e("Please fill in the Bilibili UID in Sakura Options.", "sakurairo"); ?></p>
            </div>
        <?php endif; ?>
    </article>
<?php endwhile; ?>

<?php
get_footer();
