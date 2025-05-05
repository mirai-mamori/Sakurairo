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

    /* 收藏夹列表容器 - 强制3列网格布局 */
    .fav-grid {
        display: grid;
        grid-template-columns: repeat(3, 1fr); /* 强制3列布局 */
        gap: 24px; /* 调整间距 */
        padding: 0 1rem;
        margin-bottom: 50px;
    }
      /* 收藏夹内容区 */
    .fav-section {
        margin-bottom: 30px;
    }
    
    /* 收藏夹内容区域 */
    .fav-content {
        position: relative;
        min-height: 200px;
    }
    /* 视频卡片样式 */
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

    /* 封面图片右下方的UP主信息 */
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
    /* 添加父容器用于包装描述内容，确保line-clamp正常生效 */
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
        max-height: 3rem; /* 显式设置最大高度 */
        word-break: break-word;
        margin: 0;
    }
    /* 分页区域 - 更现代的设计 */
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
    
    /* 预占位卡片样式增强 */
    .fav-item-skeleton .fav-item-thumb-placeholder {
        background-color: #f0f0f0; 
        display: block; 
    }
    
    /* 暗色模式下的预占位卡片 */
    body.dark .fav-item-skeleton .fav-item-desc {
        background: #3a3a3a;
    }
    
    /* 暗色模式下的骨架屏缩略图占位符 */
    body.dark .fav-item-skeleton .fav-item-thumb-placeholder {
        background-color: #2a2a2a;
    }

    .refresh-btn{
        gap:10px;
    }

    /* 为空状态 */
    .fav-empty {
        text-align: center;
        padding: 40px 0;
        color: #888;
    }
    
    /* 视频弹窗样式 */
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
    
    /* 为视频卡片添加鼠标交互效果 */
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
    
    /* 暗色模式适配 */
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
    
    /* 增强的响应式样式 */
    @media (max-width: 1100px) {
        .fav-grid {
            grid-template-columns: repeat(2, 1fr); /* 平板视图改为2列 */
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
            grid-template-columns: 1fr; /* 手机视图改为1列 */
            gap: 16px;
        }
        
        .fav-item-title{
            line-height: 1.5;
        }

        .fav-item-thumb {
            padding-top: 56.25%; /* 保持16:9比例 */
        }
        
        .page-btn {
            min-width: 36px;
            height: 36px;
            font-size: 0.85rem;
        }
        
        .page-btn.prev-btn, .page-btn.next-btn {
            padding: 0 12px;
        }

        /* 防止关闭按钮在标题过长时变形 */
        .video-modal-header {
            padding: 12px 15px; /* 稍微减少内边距 */
        }
        .video-modal-title {
            font-size: 1rem; /* 稍微减小标题字号 */
            margin-right: 10px; /* 增加与关闭按钮的间距 */
        }
        .video-modal-close {
            flex-shrink: 0; /* 防止按钮收缩 */
        }
    }
      /* 暗色模式 */
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
    
    /* 收藏夹胶囊选择器样式 */
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
    
    /* 定义进入动画的初始状态 */
    .fav-item.fav-item-enter {
        opacity: 0;
        transform: translateY(20px);
    }
    
    /* 定义退出动画的结束状态 */
    .fav-item.fav-item-exit {
        opacity: 0;
        transform: translateY(-20px);
        pointer-events: none; /* 防止退出动画期间的交互 */
    }
    
    /* 加载器效果 */
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
        opacity: 0; /* 初始不可见 */
    }
    
    .fav-item-thumb img.loaded {
        opacity: 1; /* 加载后可见 */
    }
    
    .fav-item-thumb img.error {
        opacity: 0.7; /* 错误状态显示但透明度较低 */
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

        <?php if (!empty($bgm)) : ?>            <div id="bilibili-favlist-app">
                <!-- 初始加载状态使用预占位卡片 -->
                <div class="fav-section">
                    <div class="fav-content">
                        <div class="fav-grid">
                            <!-- 生成9个预占位卡片作为加载状态 -->
                            <?php for ($i = 0; $i < 9; $i++): ?>                            <div class="fav-item fav-item-skeleton">
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
        <?php else : ?>
            <div class="row">
                <p> <?php _e("Please fill in the Bilibili UID in Sakura Options.", "sakurairo"); ?></p>
            </div>
        <?php endif; ?>
    </article>
<?php endwhile; ?>
<script>
 // Pass admin status to JavaScript
 const isAdmin = <?php echo json_encode(current_user_can('manage_options')); ?>;

 (function() {
    // --- Scope variables for handlers and observers ---
    let visibilityHandler = null;
    let scrollHandler = null;
    let escHandler = null;
    let videoClickHandler = null;
    let appKeydownHandler = null;
    let appClickHandler = null;
    let appObserverInstance = null;
    let imgObserverInstance = null;
    let videoModalInstance = null; // Store reference to the modal element
    let isInitialized = false; // Flag to prevent multiple initializations without cleanup

    // --- Cleanup Function ---
    const cleanup = () => {
        // console.log('Cleaning up Bilibili FavList...');

        // Remove event listeners
        if (visibilityHandler) {
            document.removeEventListener('visibilitychange', visibilityHandler);
            visibilityHandler = null;
        }
        if (scrollHandler) {
            window.removeEventListener('scroll', scrollHandler);
            scrollHandler = null;
        }
        if (escHandler) {
            document.removeEventListener('keydown', escHandler);
            escHandler = null;
        }
        if (videoClickHandler) {
            document.removeEventListener('click', videoClickHandler); // Removed from document
            videoClickHandler = null;
        }
        // Remove listeners attached to the app container if it exists
        const app = document.getElementById('bilibili-favlist-app');
        if (app) {
            if (appKeydownHandler) {
                app.removeEventListener('keydown', appKeydownHandler);
                appKeydownHandler = null;
            }
            if (appClickHandler) {
                app.removeEventListener('click', appClickHandler);
                appClickHandler = null;
            }
        }


        // Disconnect observers
        if (appObserverInstance) {
            appObserverInstance.disconnect();
            appObserverInstance = null;
        }
        if (imgObserverInstance) {
            imgObserverInstance.disconnect();
            imgObserverInstance = null;
        }

        // Close and remove video modal if it exists
        if (videoModalInstance) {
            const closeBtn = videoModalInstance.querySelector('.video-modal-close');
            if (closeBtn) {
                 // Manually trigger close logic if needed, or just remove
                 const iframe = videoModalInstance.querySelector('.video-modal-iframe');
                 if (iframe) iframe.src = ""; // Stop video
            }
            videoModalInstance.remove(); // Remove modal from DOM
            videoModalInstance = null;
            // Restore body overflow if it was changed
            document.body.style.overflow = '';
        }

        // Reset initialization flag
        isInitialized = false;
        // console.log('Cleanup complete.');
    };

    // Bilibili收藏夹应用初始化函数
    const initBilibiliFavList = async () => {
        // Prevent re-initialization if already initialized without cleanup
        if (isInitialized) {
            // console.log('Already initialized, skipping.');
            return;
        }

        // Ensure cleanup runs first, especially important for PJAX
        cleanup();

        const app = document.getElementById('bilibili-favlist-app');
        if (!app) {
            // console.log('Target element #bilibili-favlist-app not found. Aborting initialization.');
            return; // Target element not found, do nothing
        }

        // console.log('Initializing Bilibili FavList...');
        isInitialized = true; // Set flag

        try {
            const restApiUrl = '<?php echo esc_url_raw(rest_url('sakura/v1')); ?>';
            const wpnonce = '<?php echo wp_create_nonce('wp_rest'); ?>';

            if (!restApiUrl) {
                throw new Error('初始化失败：REST API路径不可用');
            }

            // console.log('Bilibili收藏夹应用初始化，API: ', restApiUrl);
            const state = {
                folders: [],
                currentFolder: null,
                loading: true,
                pageSize: 12,
                currentItems: [],
                currentPage: 1,
                totalPages: 0,
                error: null,
                lastDataUpdate: 0,
                fromCache: false,
                cacheExpiresIn: 0,
                cacheAge: 0,
                cache: new Map()
            };

            const cache = {
                getKey: (folderId, page) => `folder_${folderId}_page_${page}`,
                set: (folderId, page, data) => {
                    const key = cache.getKey(folderId, page);
                    state.cache.set(key, {
                        timestamp: Date.now(),
                        data: data
                    });
                    // Also save to localStorage for persistence across sessions/refreshes
                    try {
                        const lsKey = `bilibili_favlist_${folderId}_${page}`;
                        localStorage.setItem(lsKey, JSON.stringify({
                            timestamp: Date.now(),
                            data: data
                        }));
                    } catch (e) {
                        // console.warn('保存到本地存储失败', e);
                    }
                },
                get: (folderId, page) => {
                    const key = cache.getKey(folderId, page);
                    let item = state.cache.get(key);
                    // If not in memory cache, try localStorage
                    if (!item) {
                         try {
                            const lsKey = `bilibili_favlist_${folderId}_${page}`;
                            const savedData = localStorage.getItem(lsKey);
                            if (savedData) {
                                const parsed = JSON.parse(savedData);
                                // Check if localStorage data is valid (within 12 hours)
                                if (parsed && parsed.timestamp && (Date.now() - parsed.timestamp < 12 * 60 * 60 * 1000)) {
                                    // console.log('Restored item from localStorage', { folderId, page });
                                    // Load into memory cache
                                    state.cache.set(key, parsed);
                                    item = parsed;
                                } else {
                                    // Remove expired data from localStorage
                                    localStorage.removeItem(lsKey);
                                }
                            }
                        } catch (e) {
                            // console.warn('从本地存储恢复失败', e);
                        }
                    }
                    return item;
                },
                isValid: (cachedItem) => {
                    if (!cachedItem) return false;
                    return (Date.now() - cachedItem.timestamp) < 12 * 60 * 60 * 1000; // 12 hours
                }
            };

            async function initApp() {
                try {
                    // console.log('初始化应用开始');
                    await fetchAllFolders();
                    renderApp();
                    bindEvents(); // Bind events after rendering
                    setupScrollEffects(); // Setup effects after rendering
                    imgObserverInstance = setupImageLazyLoading(); // Setup lazy loading and store observer
                    setupVideoModal(); // Setup modal structure
                    setupVideoItemsClickEvent(); // Setup click listener for video items
                    setupScrollAnimations(); // Setup scroll animations
                    // console.log('初始化应用完成');
                } catch (error) {
                    // console.error('初始化失败:', error);
                    let errorMsg = error.message || '未知错误';
                    showError(`加载收藏夹失败: ${errorMsg}<br>请刷新页面重试`);
                }
            }

            async function fetchAllFolders(forceRefresh = false) {
                state.loading = true; // Set loading state
                renderApp(); // Render loading state immediately

                if (!forceRefresh) {
                    try {
                        const savedData = localStorage.getItem('bilibili_favlist_folders');
                        if (savedData) {
                            const parsed = JSON.parse(savedData);
                            if (parsed && parsed.timestamp && (Date.now() - parsed.timestamp < 12 * 60 * 60 * 1000)) {
                                // console.log('从本地存储恢复收藏夹列表');
                                state.folders = parsed.folders;
                                if (state.folders?.length) {
                                    state.currentFolder = state.currentFolder || state.folders[0].id;
                                    // Don't set loading to false here, let fetchFolderItems handle it
                                    await fetchFolderItems(state.currentFolder, 1, false); // Use cache if available
                                    return;
                                }
                            } else {
                                localStorage.removeItem('bilibili_favlist_folders'); // Remove expired
                            }
                        }
                    } catch (e) {
                        // console.warn('恢复本地缓存失败', e);
                    }
                }

                const endpoint = `${restApiUrl}/favlist/bilibili/folders`;
                try {
                    let url = `${endpoint}?_wpnonce=${wpnonce}`;
                    if (forceRefresh) {
                        url += `&_t=${Date.now()}`;
                    }

                    const response = await fetch(url, {
                        headers: {
                            'Cache-Control': forceRefresh ? 'no-cache, no-store, must-revalidate' : 'max-age=43200', // Align with backend?
                            'Pragma': forceRefresh ? 'no-cache' : undefined // Pragma is deprecated
                        }
                    });

                    if (!response.ok) throw new Error(`网络请求失败 (${response.status})`);

                    const data = await response.json();
                    if (data.code !== 0) throw new Error(data.message || '获取收藏夹列表失败');

                    state.folders = data.data.list || []; // Ensure it's an array
                    if (state.folders?.length) {
                        try {
                            localStorage.setItem('bilibili_favlist_folders', JSON.stringify({
                                timestamp: Date.now(),
                                folders: state.folders
                            }));
                        } catch (e) {
                            // console.warn('保存收藏夹列表到本地存储失败', e);
                        }
                        state.currentFolder = state.folders[0].id;
                        await fetchFolderItems(state.currentFolder, 1, forceRefresh); // Fetch items for the first folder
                    } else {
                        // Handle case with no folders
                        state.loading = false;
                        state.currentItems = [];
                        state.totalPages = 0;
                        renderApp(); // Render empty state
                    }
                } catch (error) {
                    // console.error('获取收藏夹列表失败:', error);
                    showError('加载收藏夹列表失败，请刷新页面重试');
                    state.loading = false; // Ensure loading is false on error
                    renderApp(); // Render error state
                }
                // No finally block needed here as loading state is handled within success/error paths
            }

            async function fetchFolderItems(folderId, page = 1, forceRefresh = false) {
                state.loading = true;
                state.error = null; // Clear previous errors
                state.currentPage = page; // Update current page immediately for UI feedback
                state.currentFolder = folderId; // Update current folder
                renderApp(); // Show loading state (skeleton) for the specific folder/page

                if (!forceRefresh) {
                    const cachedData = cache.get(folderId, page);
                    if (cache.isValid(cachedData)) {
                        // console.log('使用缓存的收藏夹内容', { folderId, page });
                        const folderData = cachedData.data;
                        state.currentItems = folderData.medias || [];
                        state.totalPages = Math.ceil((folderData.info?.media_count || 0) / state.pageSize);
                        state.fromCache = true;
                        state.cacheAge = Math.floor((Date.now() - cachedData.timestamp) / 1000);
                        state.loading = false;
                        renderApp(true); // Render cached content, indicate it's an update
                        // 移除这里的效果重置，由 renderApp 处理
                        // setTimeout(() => { ... }, 0);
                        return;
                    }
                }

                await fetchFolderItemsFromNetwork(folderId, page, forceRefresh);
            }

            async function fetchFolderItemsFromNetwork(folderId, page = 1, forceRefresh = false) {
                const endpoint = `${restApiUrl}/favlist/bilibili`;
                try {
                    // console.log('从网络获取收藏夹内容', { folderId, page, forceRefresh });
                    let url = `${endpoint}?folder_id=${folderId}&page=${page}&_wpnonce=${wpnonce}`;
                    if (forceRefresh) {
                        url += `&_t=${Date.now()}`;
                    }

                    const response = await fetch(url, {
                        headers: {
                            'Cache-Control': forceRefresh ? 'no-cache, no-store, must-revalidate' : 'max-age=43200',
                            'Pragma': forceRefresh ? 'no-cache' : undefined
                        }
                    });

                    if (!response.ok) throw new Error(`网络请求失败 (${response.status})`);

                    const data = await response.json();
                    if (data.code !== 0) throw new Error(data.message || '获取收藏内容失败');

                    const folderData = data.data;
                    cache.set(folderId, page, folderData); // Update cache

                    state.currentItems = folderData.medias || [];
                    state.totalPages = Math.ceil((folderData.info?.media_count || 0) / state.pageSize);
                    state.fromCache = data.cache_info?.from_cache || false;
                    state.cacheExpiresIn = data.cache_info?.expires_in || 0;
                    state.lastDataUpdate = Date.now();

                } catch (error) {
                    // console.error('获取收藏夹内容失败:', error);
                    showError(`加载收藏内容失败: ${error.message}. 请重试`);
                    state.currentItems = []; // Clear items on error
                    state.totalPages = 0;
                } finally {
                    state.loading = false;
                    renderApp(true); // Render results or error, indicate it's an update
                    // 移除这里的效果重置，由 renderApp 处理
                    // setTimeout(() => { ... }, 0);
                }
            }

            function showError(message) {
                state.error = message;
                state.loading = false;
                // Don't render here, let the caller handle rendering
            }

            function renderApp(isUpdate = false) { // 添加 isUpdate 参数
                 if (!app) return;

                 const EXIT_ANIMATION_DURATION = 400; // 基础退出动画时间 (ms)
                 const EXIT_STAGGER_DELAY = 50; // 每个卡片退出的交错延迟 (ms)
                 const ENTER_STAGGER_DELAY = 30; // 每个卡片进入的交错延迟 (ms) - 更短
                 const folderSelectorHtml = state.folders.length > 0 ? renderFolderSelector() : '';
                 let contentHtml = '';

                 if (state.error) {
                    contentHtml = `
                        <div class="fav-section">
                            <div class="fav-content">
                                <div class="fav-empty">
                                    <p>${state.error}</p>
                                    <button class="page-btn retry-btn">重试</button>
                                </div>
                            </div>
                        </div>
                    `;
                 } else if (state.loading && !state.currentItems.length) {
                     contentHtml = renderLoadingSkeleton();
                 } else if (!state.folders.length && !state.loading) {
                     contentHtml = `
                         <div class="fav-section">
                             <div class="fav-content">
                                 <div class="fav-empty">
                                     <p>没有找到收藏夹或UID配置错误。</p>
                                      <button class="page-btn retry-btn">重试</button>
                                 </div>
                             </div>
                         </div>
                     `;
                 } else {
                     contentHtml = renderCurrentFolder(); // renderCurrentFolder 现在只返回内容部分的 HTML
                 }

                 const existingContent = app.querySelector('.fav-section');
                 // 检查之前的内容是否是骨架屏
                 const wasSkeleton = existingContent && existingContent.querySelector('.fav-item-skeleton');
                 // 仅在成功更新内容且之前不是骨架屏时应用动画
                 const needsAnimation = isUpdate && existingContent && !wasSkeleton && !state.loading && !state.error;

                 const renderNewContent = () => {
                     app.innerHTML = folderSelectorHtml + contentHtml; // 渲染包括 tabs 和新内容

                     // Re-bind events and apply effects to new content
                     bindAppContainerEvents();
                     if (!state.loading && !state.error && state.currentItems.length > 0) {
                         const newItems = app.querySelectorAll('.fav-item');
                         newItems.forEach((item, index) => { // 为新项目添加入场动画和交错
                             item.classList.add('fav-item-enter');
                             // 添加入场交错
                             item.style.transitionDelay = `${index * ENTER_STAGGER_DELAY}ms`;
                             requestAnimationFrame(() => { // 触发 CSS transition
                                 // Ensure item still exists before removing class
                                 if (item && item.parentNode) {
                                     item.classList.remove('fav-item-enter');
                                 }
                             });
                         });
                         // 重新设置懒加载和滚动动画
                         if (imgObserverInstance) imgObserverInstance.disconnect();
                         imgObserverInstance = setupImageLazyLoading();
                         setupScrollAnimations(); // 重新设置滚动动画监听
                     } else {
                         // 如果是骨架屏或错误状态，可能不需要特定的进入动画或效果
                         if (imgObserverInstance) imgObserverInstance.disconnect(); // 确保旧的观察器被移除
                         imgObserverInstance = null;
                         if (scrollHandler) window.removeEventListener('scroll', scrollHandler); // 移除旧的滚动监听
                         scrollHandler = null;
                     }
                 };

                 if (needsAnimation) {
                     const itemsToExit = existingContent.querySelectorAll('.fav-item');
                     if (itemsToExit.length > 0) {
                         // 计算总退出时间 (最后一个元素完成动画的时间点)
                         const totalExitDuration = EXIT_ANIMATION_DURATION + (itemsToExit.length - 1) * EXIT_STAGGER_DELAY;

                         // 应用交错退出动画
                         itemsToExit.forEach((item, index) => {
                             // 使用 requestAnimationFrame 确保类添加和延迟设置在同一帧或后续帧
                             requestAnimationFrame(() => {
                                 // 确保元素仍然存在
                                 if (item && item.parentNode) {
                                     item.style.transitionDelay = `${index * EXIT_STAGGER_DELAY}ms`;
                                     item.classList.add('fav-item-exit');
                                     // 在动画结束后移除元素上的延迟，以防干扰后续操作
                                     setTimeout(() => {
                                         if (item && item.parentNode) { // 再次检查
                                             item.style.transitionDelay = '';
                                         }
                                     }, EXIT_ANIMATION_DURATION + index * EXIT_STAGGER_DELAY);
                                 }
                             });
                         });

                         // 稍微提前调用 renderNewContent，让进入动画开始时，退出动画接近尾声
                         // 例如，在最后一个元素开始退出动画后不久，或者总时间的 80-90% 处
                         const waitTimeForNewContent = Math.max(EXIT_ANIMATION_DURATION, totalExitDuration - EXIT_STAGGER_DELAY * 2); // 保证至少等待基础动画时间，并提前一点

                         // 等待计算出的时间后渲染新内容
                         setTimeout(renderNewContent, waitTimeForNewContent);
                     } else {
                         // 如果没有旧项目（例如从空状态更新），直接渲染
                         renderNewContent();
                     }
                 } else {
                     // 初始加载、加载骨架屏、显示错误、从错误重试或从骨架屏更新时，直接渲染
                     renderNewContent();
                 }
            }
            function renderLoadingSkeleton() {
                let skeletonHtml = '<div class="fav-section"><div class="fav-content"><div class="fav-grid">';
                for (let i = 0; i < state.pageSize; i++) {
                    skeletonHtml += `
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
                    `;
                }
                skeletonHtml += '</div></div></div>';
                return skeletonHtml;
            }

            function renderFolderSelector() {
                let refreshButtonHtml = '';
                // Only add refresh button if the user is an admin
                if (isAdmin) {
                    refreshButtonHtml = `
                        <div class="fav-tab refresh-btn" title="强制刷新数据 (仅管理员可见)">
                            <svg viewBox="0 0 24 24" width="16" height="16" stroke="currentColor" stroke-width="2" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M3 2v6h6"></path>
                                <path d="M21 12A9 9 0 0 0 6 5.3L3 8"></path>
                                <path d="M21 22v-6h-6"></path>
                                <path d="M3 12a9 9 0 0 0 15 6.7l3-2.7"></path>
                            </svg>
                            <span class="refresh-text">刷新</span>
                        </div>
                    `;
                }

                return `
                    <div class="fav-tabs">
                        ${state.folders.map(folder => `
                            <div class="fav-tab ${folder.id === state.currentFolder ? 'active' : ''}"
                                 data-folder-id="${folder.id}">
                                ${folder.title}
                                <span class="fav-tab-count">${folder.media_count}</span>
                            </div>
                        `).join('')}
                        ${refreshButtonHtml}
                    </div>
                `;
            }

            function renderCurrentFolder() {
                const currentFolder = state.folders.find(folder => folder.id === state.currentFolder);

                let contentHtml = '';
                // 移除 state.loading 的判断，骨架屏由 renderApp 控制
                if (!state.currentItems.length && !state.loading) { // 仅在非加载状态且无内容时显示空状态
                    contentHtml = `<div class="fav-empty"><p>该收藏夹暂无内容</p></div>`;
                } else if (state.currentItems.length) { // 仅在有内容时渲染网格和分页
                    contentHtml = `
                        <div class="fav-grid">
                            ${state.currentItems.map(item => renderFavItem(item)).join('')}
                        </div>
                        ${renderPagination()}
                    `;
                }
                // 如果 state.loading 为 true 且 state.currentItems 为空，renderApp 会渲染骨架屏，这里不需要额外处理

                return `
                    <div class="fav-section">
                        <div class="fav-content">
                            ${contentHtml}
                        </div>
                    </div>
                `;
            }

            function renderFolderContent() {
                if (!state.currentItems.length) {
                    return `<div class="fav-empty"><p>该收藏夹暂无内容</p></div>`;
                }
                return `
                    <div class="fav-grid">
                        ${state.currentItems.map(item => renderFavItem(item)).join('')}
                    </div>
                    ${renderPagination()}
                `;
            }

            function renderFavItem(item) {
                // ... existing renderFavItem code ...
                 let bvid = item.bvid || '';
                // Ensure cover uses HTTPS and handle potential missing cover
                let cover = item.cover ? item.cover.replace(/^http:/, 'https:') : 'data:image/gif;base64,R0lGODlhAQABAIAAAAAAAP///yH5BAEAAAAALAAAAAABAAEAAAIBRAA7'; // Placeholder for missing cover
                let pubdate = item.pubdate ? formatDate(item.pubdate * 1000) : ''; // Handle missing pubdate

                return `
                    <div class="fav-item" data-bvid="${bvid}" data-title="${item.title}" data-up="${item.upper?.name || '未知'}" tabindex="0" role="button" aria-label="播放视频: ${item.title}">
                        <div class="fav-item-content-wrapper">
                            <div class="fav-item-thumb">
                                <div class="fav-item-thumb-placeholder">
                                    <!-- 移除 spinner -->
                                </div>
                                <img data-src="${cover}" class="fav-thumb-img" src="data:image/gif;base64,R0lGODlhAQABAIAAAAAAAP///yH5BAEAAAAALAAAAAABAAEAAAIBRAA7" referrerpolicy="no-referrer" alt="${item.title}">
                                <div class="fav-item-title-area">
                                    <h3 class="fav-item-title" title="${item.title}">${item.title}</h3>
                                </div>
                                <div class="fav-item-up">
                                    <span class="up-name">UP: ${item.upper?.name || '未知'}</span>
                                </div>
                                <div class="fav-item-play-btn">
                                    <svg viewBox="0 0 24 24" width="36" height="36" stroke="currentColor" stroke-width="2" fill="none" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"></circle><polygon points="10 8 16 12 10 16 10 8" fill="currentColor"></polygon></svg>
                                </div>
                            </div>
                            <div class="fav-item-desc-wrapper">
                                <div class="fav-item-desc" title="${item.intro || ''}">${item.intro || '暂无简介'}</div>
                            </div>
                        </div>
                    </div>
                `;
            }

            function formatDate(timestamp) {
                // ... existing formatDate code ...
                 const date = new Date(timestamp);
                const now = new Date();
                const diffDays = Math.floor((now - date) / (1000 * 60 * 60 * 24));

                if (diffDays < 1) { return '今天'; }
                if (diffDays === 1) { return '昨天'; }
                if (diffDays < 7) { return `${diffDays}天前`; }
                // Simplified date format
                return `${date.getFullYear()}-${String(date.getMonth() + 1).padStart(2, '0')}-${String(date.getDate()).padStart(2, '0')}`;
            }

            function formatTimeLeft(seconds) {
                // ... existing formatTimeLeft code ...
                 if (seconds < 60) { return `${seconds}秒`; }
                 if (seconds < 3600) { return `${Math.floor(seconds / 60)}分钟`; }
                 if (seconds < 86400) { return `${Math.floor(seconds / 3600)}小时${Math.floor((seconds % 3600) / 60)}分钟`; }
                 return `${Math.floor(seconds / 86400)}天${Math.floor((seconds % 86400) / 3600)}小时`;
            }

            function renderPagination() {
                // ... existing renderPagination code ...
                 if (state.totalPages <= 1) return '';

                let paginationHtml = '<div class="fav-pagination">';
                const currentPage = state.currentPage;
                const totalPages = state.totalPages;

                // Prev Button
                paginationHtml += `<button class="page-btn prev-btn" ${currentPage <= 1 ? 'disabled' : ''} data-page="${currentPage - 1}">上一页</button>`;

                // Page numbers logic (simplified example)
                const maxPagesToShow = 5;
                let startPage = Math.max(1, currentPage - Math.floor(maxPagesToShow / 2));
                let endPage = Math.min(totalPages, startPage + maxPagesToShow - 1);

                if (endPage - startPage + 1 < maxPagesToShow) {
                    startPage = Math.max(1, endPage - maxPagesToShow + 1);
                }

                if (startPage > 1) {
                    paginationHtml += `<button class="page-btn page-num" data-page="1">1</button>`;
                    if (startPage > 2) paginationHtml += `<button class="page-btn page-ellipsis" disabled>...</button>`;
                }

                for (let i = startPage; i <= endPage; i++) {
                    paginationHtml += `<button class="page-btn page-num ${i === currentPage ? 'active' : ''}" data-page="${i}">${i}</button>`;
                }

                if (endPage < totalPages) {
                    if (endPage < totalPages - 1) paginationHtml += `<button class="page-btn page-ellipsis" disabled>...</button>`;
                    paginationHtml += `<button class="page-btn page-num" data-page="${totalPages}">${totalPages}</button>`;
                }

                // Next Button
                paginationHtml += `<button class="page-btn next-btn" ${currentPage >= totalPages ? 'disabled' : ''} data-page="${currentPage + 1}">下一页</button>`;

                paginationHtml += '</div>';
                return paginationHtml;
            }

            // Bind events delegated to the app container
            function bindAppContainerEvents() {
                 if (!app) return;

                 // Remove previous listeners first to prevent duplicates if called multiple times
                 if (appKeydownHandler) app.removeEventListener('keydown', appKeydownHandler);
                 if (appClickHandler) app.removeEventListener('click', appClickHandler);

                 // Keyboard accessibility for fav items
                 appKeydownHandler = function(e) {
                    if ((e.key === 'Enter' || e.key === ' ') && e.target.classList.contains('fav-item')) {
                        e.preventDefault();
                        // Find the closest fav-item and trigger its click logic (handled by appClickHandler)
                         const favItem = e.target.closest('.fav-item');
                         if(favItem) {
                             handleFavItemClick(favItem);
                         }
                    }
                 };
                 app.addEventListener('keydown', appKeydownHandler);

                 // Main click handler using event delegation
                 appClickHandler = async function(e) {
                    const target = e.target;
                    const REFRESH_COOLDOWN = 60 * 1000; // 60 seconds cooldown

                    // Refresh button (Check isAdmin again for safety, though button shouldn't exist if not admin)
                    const refreshBtn = target.closest('.refresh-btn');
                    if (isAdmin && refreshBtn && !refreshBtn.classList.contains('refreshing')) {
                        const lastRefreshTs = parseInt(localStorage.getItem('bilibili_last_refresh_ts') || '0', 10);
                        const now = Date.now();
                        const remainingTime = REFRESH_COOLDOWN - (now - lastRefreshTs);
                        const refreshTextSpan = refreshBtn.querySelector('.refresh-text');

                        if (remainingTime > 0) {
                            // console.log(`刷新冷却中，剩余 ${Math.ceil(remainingTime / 1000)} 秒`);
                            if (refreshTextSpan) {
                                const originalText = refreshTextSpan.textContent;
                                refreshTextSpan.textContent = `请稍后 (${Math.ceil(remainingTime / 1000)}s)`;
                                // Temporarily disable? Or just show text. Let's just show text.
                                setTimeout(() => {
                                    // Check if the button still exists and text hasn't changed
                                    const currentBtn = app.querySelector('.refresh-btn');
                                    const currentTextSpan = currentBtn?.querySelector('.refresh-text');
                                    if (currentTextSpan && currentTextSpan.textContent.startsWith('请稍后')) {
                                        currentTextSpan.textContent = '刷新';
                                    }
                                }, remainingTime);
                            }
                            return; // Exit if in cooldown
                        }

                        // console.log('点击强制刷新按钮');
                        localStorage.setItem('bilibili_last_refresh_ts', now.toString()); // Set cooldown timestamp
                        refreshBtn.classList.add('refreshing');
                        if (refreshTextSpan) refreshTextSpan.textContent = '刷新中...';

                        try {
                            // Clear relevant localStorage and memory cache
                            localStorage.removeItem('bilibili_favlist_folders');
                            if (state.currentFolder) {
                                // Clear cache for all pages of the current folder? Or just current page?
                                // Let's clear all for simplicity on manual refresh
                                for (let i = 1; i <= state.totalPages; i++) {
                                     localStorage.removeItem(`bilibili_favlist_${state.currentFolder}_${i}`);
                                     state.cache.delete(cache.getKey(state.currentFolder, i));
                                }
                                // Also clear the current page just in case totalPages was 0
                                localStorage.removeItem(`bilibili_favlist_${state.currentFolder}_${state.currentPage}`);
                                state.cache.delete(cache.getKey(state.currentFolder, state.currentPage));
                            }
                            state.cache = new Map(); // Clear memory cache completely

                            await fetchAllFolders(true); // Force refresh folders and first page
                        } catch (error) {
                            // console.error('强制刷新失败:', error);
                            showError('刷新失败，请稍后重试');
                            renderApp(); // Re-render to show error
                        } finally {
                            // Ensure button state is reset even if renderApp was called
                            const currentRefreshBtn = app.querySelector('.refresh-btn');
                            if (currentRefreshBtn) {
                                currentRefreshBtn.classList.remove('refreshing');
                                const currentTextSpan = currentRefreshBtn.querySelector('.refresh-text');
                                if (currentTextSpan) currentTextSpan.textContent = '刷新';
                            }
                        }
                        return;
                    }

                    // Folder tab
                    const tabEl = target.closest('.fav-tab:not(.refresh-btn)');
                    if (tabEl) {
                        const folderId = parseInt(tabEl.dataset.folderId, 10);
                        if (folderId !== state.currentFolder && !state.loading) {
                            // console.log('切换收藏夹:', folderId);
                            await fetchFolderItems(folderId, 1);
                            return;
                        }
                        return;
                    }

                    // Pagination buttons (prev, next, page number)
                    const pageBtn = target.closest('.page-btn[data-page]');
                    if (pageBtn && !pageBtn.disabled && !pageBtn.classList.contains('active')) {
                        const page = parseInt(pageBtn.dataset.page, 10);
                         if (page !== state.currentPage && !state.loading) {
                            await fetchFolderItems(state.currentFolder, page);
                            scrollToTop();
                        }
                        return;
                    }

                    // Retry button
                    const retryBtn = target.closest('.retry-btn');
                    if (retryBtn) {
                        // console.log('点击重试按钮');
                        state.error = null;
                        state.loading = true;
                        renderApp(); // Show loading state
                        try {
                            // Re-run the initialization logic for the current state or full init?
                            // Let's try fetching folders again.
                            await fetchAllFolders(false); // Try fetching folders (will use cache if valid)
                        } catch (error) {
                            // console.error('重试失败:', error);
                            showError(`重试失败: ${error.message}`);
                            renderApp(); // Show error again
                        }
                        return;
                    }

                     // Favorite item click (handled by global listener now)
                     // Moved to setupVideoItemsClickEvent
                 };
                 app.addEventListener('click', appClickHandler);
            }

             // Separate function to bind non-delegated events (window, document)
            function bindGlobalEvents() {
                 // Visibility change
                 let lastVisibilityChange = Date.now();
                 let needReloadOnReturn = false;
                 const RELOAD_THRESHOLD = 5 * 60 * 1000; // 5 minutes threshold for reload on return

                 visibilityHandler = function() {
                     const now = Date.now();
                     const app = document.getElementById('bilibili-favlist-app'); // Get app element reference inside handler

                     if (document.visibilityState === 'hidden') {
                         lastVisibilityChange = now;
                     } else if (document.visibilityState === 'visible') {
                         // Ensure app exists in the current document before proceeding
                         if (!app || !document.body.contains(app)) {
                             return; // App element not found or detached, likely during PJAX transition
                         }

                         const timeAway = now - lastVisibilityChange;
                         // Check initialization, app presence, loading state, and time threshold
                         if (isInitialized && !state.loading && state.folders.length > 0 && (timeAway > RELOAD_THRESHOLD || needReloadOnReturn)) {
                             // console.log('页面返回，重新加载当前收藏夹内容', { timeAway: timeAway / 1000 + '秒' });
                             // Fetch current folder/page, force refresh = false (use cache if valid)
                             fetchFolderItems(state.currentFolder, state.currentPage, false);
                             needReloadOnReturn = false;
                         }
                     }
                 };
                 document.addEventListener('visibilitychange', visibilityHandler);

                 // Scroll animations (handled by setupScrollAnimations)

                 // ESC key for modal (handled by setupVideoModal)
            }

            // Bind all events
            function bindEvents() {
                bindAppContainerEvents(); // Bind events delegated to the app container
                bindGlobalEvents(); // Bind window/document level events
            }

            // Helper to scroll to the top of the content area
            function scrollToTop() {
                const contentArea = app.querySelector('.fav-section'); // Or app itself
                if (contentArea) {
                    window.scrollTo({
                        top: contentArea.offsetTop - 80, // Adjust offset as needed
                        behavior: 'smooth'
                    });
                }
            }

            // --- Effects and Lazy Loading ---

            function setupScrollEffects() {
                 if (!app) return;
                 // Simple fade-in effect using Intersection Observer
                 const itemObserver = new IntersectionObserver((entries) => {
                     entries.forEach(entry => {
                         if (entry.isIntersecting) {
                             entry.target.classList.add('visible');
                             itemObserver.unobserve(entry.target);
                         }
                     });
                 }, { threshold: 0.1, rootMargin: '0px 0px -50px 0px' });

                 const items = app.querySelectorAll('.fav-item:not(.visible)');
                 items.forEach((item, index) => {
                     item.style.animationDelay = `${index * 0.05}s`; // Keep delay
                     itemObserver.observe(item);
                 });

                 // Store observer reference if needed for cleanup, though unobserve might be enough
                 // If items are added dynamically, this needs to be called again.
            }

            const appMutationCallback = (mutationsList, observer) => {
                 for(const mutation of mutationsList) {
                     if (mutation.type === 'childList') {
                         // New nodes added?
                         mutation.addedNodes.forEach(node => {
                             // If new fav-items were added, apply scroll effects and lazy loading
                             if (node.nodeType === 1) {
                                 if (node.classList.contains('fav-item')) {
                                     setupScrollEffects(); // Re-apply to potentially new items
                                 }
                                 // Check for images within the added node
                                 node.querySelectorAll('img.fav-thumb-img[data-src]').forEach(img => {
                                     if (imgObserverInstance) imgObserverInstance.observe(img);
                                 });
                             }
                         });
                     }
                 }
            };


            // Debounce function
            function debounce(func, wait = 50) {
                let timeout;
                return function(...args) {
                    clearTimeout(timeout);
                    timeout = setTimeout(() => func.apply(this, args), wait);
                };
            }

            function setupScrollAnimations() {
                // Remove previous handler if exists
                if (scrollHandler) {
                    window.removeEventListener('scroll', scrollHandler);
                }

                scrollHandler = debounce(() => {
                    if (!app) return;
                    // 选择没有 'fav-item-exit' 类的项目来应用 'visible'
                    const items = app.querySelectorAll('.fav-item:not(.visible):not(.fav-item-exit)');
                    const viewportHeight = window.innerHeight;
                    items.forEach(item => {
                        const rect = item.getBoundingClientRect();
                        // Trigger when item is 85% in view from the bottom
                        if (rect.top < viewportHeight * 0.90 && rect.bottom > viewportHeight * 0.1) {
                             // .visible 类现在可以只用于标记是否已滚动到视图，而不是控制入场动画
                             item.classList.add('visible');
                        }
                    });
                }, 50); // Adjust debounce wait time as needed

                window.addEventListener('scroll', scrollHandler);
                // Initial check
                requestAnimationFrame(scrollHandler); // 使用 rAF 确保在布局后执行初始检查
            }


            function setupImageLazyLoading() {
                // Disconnect previous observer if exists
                if (imgObserverInstance) {
                    imgObserverInstance.disconnect();
                }

                const observer = new IntersectionObserver((entries, obs) => {
                    entries.forEach(entry => {
                        if (entry.isIntersecting) {
                            const img = entry.target;
                            const src = img.getAttribute('data-src');
                            if (src) {
                                const placeholder = img.previousElementSibling; // Assuming placeholder is sibling

                                img.onload = () => {
                                    img.classList.add('loaded');
                                    if (placeholder && placeholder.classList.contains('fav-item-thumb-placeholder')) {
                                        placeholder.style.display = 'none'; // Hide placeholder
                                    }
                                };
                                img.onerror = () => {
                                    // console.warn('图片加载失败:', src);
                                    img.classList.add('loaded', 'error'); // Mark as loaded but with error
                                     if (placeholder && placeholder.classList.contains('fav-item-thumb-placeholder')) {
                                         placeholder.innerHTML = '<svg fill="#ccc" width="32" height="32" viewBox="0 0 24 24"><path d="M19 5v14H5V5h14m0-2H5c-1.1 0-2 .9-2 2v14c0 1.1.9 2 2 2h14c1.1 0 2-.9 2-2V5c0-1.1-.9-2-2-2zm-4.86 8.86l-3 3.87L9 13.14 6 17h12l-3.86-5.14z"/></svg>'; // Show error icon
                                         placeholder.style.display = 'flex'; // Ensure placeholder is visible
                                     }
                                    // Optionally set a fallback image src
                                    // img.src = 'path/to/fallback.jpg';
                                };
                                img.src = src;
                                img.removeAttribute('data-src');
                            }
                            obs.unobserve(img); // Unobserve after processing
                        }
                    });
                }, { threshold: 0.05, rootMargin: '150px' }); // Increased rootMargin

                if (app) {
                    app.querySelectorAll('img.fav-thumb-img[data-src]').forEach(img => observer.observe(img));
                }
                return observer; // Return the observer instance
            }


            // --- Video Modal ---
             function setupVideoModal() {
                 // Remove existing modal first during re-init
                 const existingModal = document.querySelector('.video-modal');
                 if (existingModal) {
                     existingModal.remove();
                 }

                 videoModalInstance = document.createElement('div');
                 videoModalInstance.className = 'video-modal';
                 videoModalInstance.innerHTML = `
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
                 `;
                 document.body.appendChild(videoModalInstance);

                 const closeVideoModal = () => {
                     if (!videoModalInstance) return;
                     const container = videoModalInstance.querySelector('.video-modal-container');
                     const iframe = videoModalInstance.querySelector('.video-modal-iframe');

                     container.style.transform = 'translateY(20px)';
                     container.style.opacity = '0';

                     setTimeout(() => {
                         if (videoModalInstance) { // Check again as it might be cleaned up
                            videoModalInstance.classList.remove('active');
                            document.body.style.overflow = '';
                            if (iframe) iframe.src = "about:blank"; // Stop video by resetting src
                         }
                     }, 300);
                 };

                 videoModalInstance.querySelector('.video-modal-close').addEventListener('click', closeVideoModal);
                 videoModalInstance.addEventListener('click', (e) => {
                     if (e.target === videoModalInstance) {
                         closeVideoModal();
                     }
                 });

                 // Remove previous ESC handler if exists
                 if (escHandler) {
                     document.removeEventListener('keydown', escHandler);
                 }
                 escHandler = (e) => {
                     if (e.key === 'Escape' && videoModalInstance && videoModalInstance.classList.contains('active')) {
                         closeVideoModal();
                     }
                 };
                 document.addEventListener('keydown', escHandler);

                 return videoModalInstance; // Return the created modal element
             }

             // Centralized function to handle fav item click/activation
             function handleFavItemClick(favItem) {
                 const bvid = favItem.getAttribute('data-bvid');
                 if (bvid && videoModalInstance) { // Ensure modal exists
                     const title = favItem.getAttribute('data-title');
                     const upName = favItem.getAttribute('data-up');

                     videoModalInstance.querySelector('.video-modal-title').textContent = title;
                     videoModalInstance.querySelector('.video-modal-up-name').textContent = 'UP: ' + upName;
                     videoModalInstance.querySelector('.video-modal-open').href = `https://www.bilibili.com/video/${bvid}`;

                     const iframe = videoModalInstance.querySelector('.video-modal-iframe');
                     // Use HTTPS for player URL, add autoplay=1
                     iframe.src = `https://player.bilibili.com/player.html?bvid=${bvid}&page=1&autoplay=1&danmaku=1`;

                     videoModalInstance.classList.add('active');
                     document.body.style.overflow = 'hidden'; // Prevent background scroll

                     // Trigger animation
                     setTimeout(() => {
                         if (videoModalInstance) { // Check if modal still exists
                            const container = videoModalInstance.querySelector('.video-modal-container');
                            if (container) {
                                container.style.transform = 'translateY(0)';
                                container.style.opacity = '1';
                            }
                         }
                     }, 10); // Small delay for transition
                 }
             }


             function setupVideoItemsClickEvent() {
                 // Remove previous listener if exists
                 if (videoClickHandler) {
                     document.removeEventListener('click', videoClickHandler);
                 }

                 // Use event delegation on the document body for fav item clicks
                 videoClickHandler = (e) => {
                     const favItem = e.target.closest('.fav-item');
                     if (favItem) {
                         e.preventDefault(); // Prevent default action if it's a link/button
                         handleFavItemClick(favItem);
                     }
                 };
                 document.addEventListener('click', videoClickHandler);
             }

            // --- Initialization ---
            await initApp(); // Start the application logic

            // Setup MutationObserver to watch for dynamic content changes (if needed)
            // Disconnect previous observer if exists
            if (appObserverInstance) {
                appObserverInstance.disconnect();
            }
            appObserverInstance = new MutationObserver(appMutationCallback);
            if (app) {
                appObserverInstance.observe(app, { childList: true, subtree: true });
            }


        } catch (error) {
            // console.error('初始化过程中发生顶层错误:', error);
            const app = document.getElementById('bilibili-favlist-app');
            if (app) {
                app.innerHTML = `
                    <div class="fav-empty">
                        <p>加载收藏夹时发生严重错误: ${error.message || '未知错误'}</p>
                        <button class="page-btn retry-btn">重试</button>
                    </div>
                `;
                 // Bind retry button listener even in case of top-level error
                 app.querySelector('.retry-btn')?.addEventListener('click', () => {
                     // console.log('Retrying after top-level error...');
                     initBilibiliFavList(); // Attempt re-initialization
                 });
            }
            isInitialized = false; // Reset flag on error
        }
    };

    // --- PJAX Integration ---
    const init = () => {
        // Initial load
        if (document.readyState === 'loading') {
            document.addEventListener('DOMContentLoaded', initBilibiliFavList);
        } else {
            initBilibiliFavList(); // Already loaded
        }

        // PJAX listeners
        document.addEventListener('pjax:send', () => {
            // console.log('PJAX send: Cleaning up...');
            cleanup(); // Clean up before navigating away
        });

        document.addEventListener('pjax:complete', () => {
            // console.log('PJAX complete: Initializing...');
            // Use setTimeout to ensure the DOM is fully ready after PJAX replaces content
            setTimeout(() => {
                 initBilibiliFavList();
            }, 100); // Delay might need adjustment
        });
    };

    // Start
    init();

})();

</script>

<?php
get_footer();
