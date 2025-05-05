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
        transition: transform 0.25s ease, box-shadow 0.25s ease;
        display: flex;
        flex-direction: column;
        height: 100%;
        box-shadow: 0 1px 3px rgba(0,0,0,0.1);
        cursor: pointer;
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
    
    /* 加载中状态 */
    .fav-loading {
        display: flex;
        justify-content: center;
        align-items: center;
        min-height: 200px;
    }
    
    .spinner {
        width: 40px;
        height: 40px;
        border: 3px solid rgba(0, 0, 0, 0.1);
        border-radius: 50%;
        border-top-color: var(--theme-skin-matching, #505050);
        animation: spin 1s ease-in-out infinite;
    }
    
    .refresh-btn{
        gap:10px;
    }

    @keyframes spin {
        to { transform: rotate(360deg); }
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
    
    @keyframes fadeIn {
        from { opacity: 0; }
        to { opacity: 1; }
    }
    
    .fav-item {
        opacity: 0;
        animation: fadeIn 0.4s ease forwards;
    }
    
    .fav-item.visible {
        opacity: 1;
    }
    
    /* 加载器效果 */
    .fav-item-thumb-placeholder {
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background-color: #f0f0f0;
        display: flex;
        align-items: center;
        justify-content: center;
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

        <?php if (!empty($bgm)) : ?>
            <div id="bilibili-favlist-app">
                <!-- 加载状态 -->
                <div class="fav-loading">
                    <div class="spinner"></div>
                </div>
                
                <!-- 收藏夹内容将通过JS动态加载 -->
            </div>
        <?php else : ?>
            <div class="row">
                <p> <?php _e("Please fill in the Bilibili UID in Sakura Options.", "sakurairo"); ?></p>
            </div>
        <?php endif; ?>
    </article>
<?php endwhile; ?>
<script>
(function() {
    // 更高效的清理函数，使用Map跟踪事件绑定
    const eventTracking = new Map();
    
    const cleanupBilibiliFavList = () => {
        // 使用Map追踪和移除事件，避免重复绑定和遗漏清理
        eventTracking.forEach((handler, key) => {
            const [element, eventName] = key.split('::');
            if (element === 'window') {
                window.removeEventListener(eventName, handler);
            } else if (element === 'document') {
                document.removeEventListener(eventName, handler);
            }
        });
        eventTracking.clear();
    };
    
    // 安全地添加事件监听器，避免重复绑定
    const addTrackedEvent = (element, eventName, handler) => {
        const key = `${element === window ? 'window' : element === document ? 'document' : 'element'}::${eventName}`;
        
        // 如果已存在旧的处理函数，先移除
        if (eventTracking.has(key)) {
            const oldHandler = eventTracking.get(key);
            if (element === window) {
                window.removeEventListener(eventName, oldHandler);
            } else if (element === document) {
                document.removeEventListener(eventName, oldHandler);
            }
        }
        
        // 添加新的事件处理并追踪
        if (element === window) {
            window.addEventListener(eventName, handler);
        } else if (element === document) {
            document.addEventListener(eventName, handler);
        }
        
        eventTracking.set(key, handler);
    };
    
    // Bilibili收藏夹应用初始化函数 - 使用箭头函数提高一致性
    const initBilibiliFavList = async () => {
        // 首先清理可能存在的旧资源
        cleanupBilibiliFavList();
        
        // 应用主容器
        const app = document.getElementById('bilibili-favlist-app');
        if (!app) return; // 快速失败
        
        try {
            // 定义REST API路径，使用const提高性能和可靠性
            const restApiUrl = '<?php echo esc_url_raw(rest_url('sakura/v1')); ?>';
            const wpnonce = '<?php echo wp_create_nonce('wp_rest'); ?>';
            
            if (!restApiUrl) {
                throw new Error('初始化失败：REST API路径不可用');
            }
            
            // 调试信息 - 保持在开发环境，生产环境可移除
            console.log('Bilibili收藏夹应用初始化，API: ', restApiUrl);
              // 状态管理
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
                fromCache: false,           // 数据是否来自缓存
                cacheExpiresIn: 0,          // 缓存剩余有效期（秒）
                cacheAge: 0,                // 缓存已存在时间（秒）
                cache: new Map()            // 本地缓存对象
            };
            
            // 缓存助手函数
            const cache = {
                getKey: (folderId, page) => `folder_${folderId}_page_${page}`,
                set: (folderId, page, data) => {
                    const key = cache.getKey(folderId, page);
                    state.cache.set(key, {
                        timestamp: Date.now(),
                        data: data
                    });
                },
                get: (folderId, page) => {
                    const key = cache.getKey(folderId, page);
                    return state.cache.get(key);
                },                isValid: (cachedItem) => {
                    if (!cachedItem) return false;
                    // 缓存12小时有效，与后端保持一致
                    return (Date.now() - cachedItem.timestamp) < 12 * 60 * 60 * 1000;
                }
            };
              // 初始化应用
            async function initApp() {
                try {
                    console.log('初始化应用开始');
                    // 获取所有收藏夹信息
                    await fetchAllFolders();
                    
                    // 渲染界面
                    renderApp();
                    
                    // 绑定事件处理
                    bindEvents();
                    
                    console.log('初始化应用完成');
                } catch (error) {
                    console.error('初始化失败:', error);
                    let errorMsg = error.message || '未知错误';
                    showError(`加载收藏夹失败: ${errorMsg}<br>请刷新页面重试`);
                }
            }      // 获取所有收藏夹数据
            async function fetchAllFolders(forceRefresh = false) {
                // 如果不是强制刷新，尝试从本地存储恢复数据
                if (!forceRefresh) {
                    try {
                        const savedData = localStorage.getItem('bilibili_favlist_folders');
                        if (savedData) {
                            const parsed = JSON.parse(savedData);
                            // 如果有缓存的数据且没过期（12小时内，与后端同步）
                            if (parsed && parsed.timestamp && (Date.now() - parsed.timestamp < 12 * 60 * 60 * 1000)) {
                                console.log('从本地存储恢复收藏夹列表');
                                state.folders = parsed.folders;
                                if (state.folders?.length) {
                                    // 默认选中第一个收藏夹
                                    state.currentFolder = state.currentFolder || state.folders[0].id;
                                    // 加载收藏夹的内容，但优先使用缓存
                                    await fetchFolderItems(state.currentFolder, 1, forceRefresh);
                                    return; // 如果成功恢复数据，直接返回
                                }
                            }
                        }
                    } catch (e) {
                        console.warn('恢复本地缓存失败', e);
                        // 继续执行网络请求
                    }
                }
                  // 使用 WordPress REST API 的完整路径
                const restApiUrl = '<?php echo esc_url_raw(rest_url('sakura/v1')); ?>';
                const endpoint = `${restApiUrl}/favlist/bilibili/folders`;
                
                try {
                    // 构建URL
                    let url = `${endpoint}?_wpnonce=<?php echo wp_create_nonce('wp_rest'); ?>`;
                    
                    // 强制刷新时添加时间戳避免缓存
                    if (forceRefresh) {
                        url += `&_t=${Date.now()}`; 
                    }
                    
                    // 添加必要的 nonce 用于 WordPress REST API 安全验证
                    const response = await fetch(url, {
                        headers: {
                            'Cache-Control': forceRefresh ? 'no-cache, no-store, must-revalidate' : 'max-age=86400',
                            'Pragma': forceRefresh ? 'no-cache' : 'cache'
                        }
                    });
                    
                    if (!response.ok) throw new Error('网络请求失败');
                    
                    const data = await response.json();
                    if (data.code !== 0) throw new Error(data.message || '获取收藏夹失败');
                    
                    state.folders = data.data.list;
                    if (state.folders?.length) {
                        // 保存到本地存储
                        try {
                            localStorage.setItem('bilibili_favlist_folders', JSON.stringify({
                                timestamp: Date.now(),
                                folders: state.folders
                            }));
                        } catch (e) {
                            console.warn('保存到本地存储失败', e);
                        }
                        
                        // 默认选中第一个收藏夹
                        state.currentFolder = state.folders[0].id;
                        // 加载第一个收藏夹的内容
                        await fetchFolderItems(state.currentFolder, 1);
                    }
                } catch (error) {
                    console.error('获取收藏夹失败:', error);
                    showError('加载收藏夹失败，请刷新页面重试');
                    // 标记需要在页面返回时重新加载
                    needReloadOnReturn = true;
                } finally {
                    state.loading = false;
                }
            }    // 获取收藏夹内容
            async function fetchFolderItems(folderId, page = 1, forceRefresh = false) {
                state.loading = true;
                renderApp();
                
                // 如果不是强制刷新，检查是否有有效缓存
                if (!forceRefresh) {
                    const cachedData = cache.get(folderId, page);
                    if (cache.isValid(cachedData)) {
                        console.log('使用缓存的收藏夹内容', { folderId, page });
                        const folderData = cachedData.data;
                        state.currentItems = folderData.medias || [];
                        state.totalPages = Math.ceil(folderData.info.media_count / state.pageSize);
                        state.currentPage = page;
                        state.currentFolder = folderId;
                        state.fromCache = true; // 来自前端缓存
                        state.cacheAge = Math.floor((Date.now() - cachedData.timestamp)/1000); // 缓存年龄（秒）
                        state.loading = false;
                        renderApp();
                        return;
                    }
                }
                
                // 没有缓存、缓存已过期或强制刷新，执行网络请求
                await fetchFolderItemsFromNetwork(folderId, page, forceRefresh);
            }
              // 从网络获取收藏夹内容并更新缓存
            async function fetchFolderItemsFromNetwork(folderId, page = 1, forceRefresh = false) {
                // 使用 WordPress REST API 的完整路径
                const restApiUrl = '<?php echo esc_url_raw(rest_url('sakura/v1')); ?>';
                const endpoint = `${restApiUrl}/favlist/bilibili`;
                const wpnonce = '<?php echo wp_create_nonce('wp_rest'); ?>';
                
                try {
                    console.log('从网络获取收藏夹内容', { folderId, page, forceRefresh });
                    
                    // 构建URL
                    let url = `${endpoint}?folder_id=${folderId}&page=${page}&_wpnonce=${wpnonce}`;
                    
                    // 强制刷新时添加时间戳参数
                    if (forceRefresh) {
                        url += `&_t=${Date.now()}`;
                    }
                    
                    const response = await fetch(url, {
                        // 添加缓存控制
                        headers: {
                            'Cache-Control': forceRefresh ? 'no-cache, no-store, must-revalidate' : 'max-age=43200',
                            'Pragma': forceRefresh ? 'no-cache' : 'cache'
                        }
                    });
                    
                    if (!response.ok) throw new Error('网络请求失败');
                    
                    const data = await response.json();
                    if (data.code !== 0) throw new Error(data.message || '获取收藏内容失败');
                    
                    const folderData = data.data;
                    
                    // 更新缓存
                    cache.set(folderId, page, folderData);
                    
                    // 更新状态
                    state.currentItems = folderData.medias || [];
                    state.totalPages = Math.ceil(folderData.info.media_count / state.pageSize);
                    state.currentPage = page;
                    state.currentFolder = folderId;
                    state.fromCache = data.cache_info?.from_cache || false;
                    state.cacheExpiresIn = data.cache_info?.expires_in || 0;
                    
                    // 成功获取数据，添加时间戳记录上次数据更新时间
                    state.lastDataUpdate = Date.now();
                    
                    // 尝试保存到本地存储，为下次访问准备
                    try {                        const key = `bilibili_favlist_${folderId}_${page}`;
                        // 保存到localStorage时记录当前时间戳，用于缓存有效期判断(12小时)
                        localStorage.setItem(key, JSON.stringify({
                            timestamp: Date.now(),
                            data: folderData
                        }));
                    } catch (e) {
                        console.warn('保存到本地存储失败', e);
                    }
                } catch (error) {
                    console.error('获取收藏夹内容失败:', error);
                    showError('加载收藏内容失败，请重试');
                    // 请求失败时，标记需要在页面返回时重新加载
                    needReloadOnReturn = true;
                } finally {
                    state.loading = false;
                    renderApp();
                }
            }
              // 后台自动刷新缓存功能已移除，改为由服务器端定时更新
            // 现在前端只负责读取缓存并展示数据
            
            // 显示错误信息
            function showError(message) {
                state.error = message;
                state.loading = false;
                renderApp();
            }
            
            // 渲染整个应用
            function renderApp() {
                if (state.loading && !state.currentItems.length) {
                    app.innerHTML = `
                        <div class="fav-loading">
                            <div class="spinner"></div>
                        </div>
                    `;
                    return;
                }
                
                if (state.error) {
                    app.innerHTML = `
                        <div class="fav-empty">
                            <p>${state.error}</p>
                            <button class="page-btn retry-btn">重试</button>
                        </div>
                    `;
                    return;
                }
                
                if (!state.folders.length) {
                    app.innerHTML = `
                        <div class="fav-empty">
                            <p>没有找到收藏夹</p>
                        </div>
                    `;
                    return;
                }
                
                // 渲染收藏夹选择器和内容
                let html = renderFolderSelector();
                html += renderCurrentFolder();
                
                app.innerHTML = html;
            }              // 渲染收藏夹选择器(胶囊式)
            function renderFolderSelector() {
                return `
                    <div class="fav-tabs">
                        ${state.folders.map(folder => `
                            <div class="fav-tab ${folder.id === state.currentFolder ? 'active' : ''}" 
                                 data-folder-id="${folder.id}">
                                ${folder.title}
                                <span class="fav-tab-count">${folder.media_count}</span>
                            </div>
                        `).join('')}
                        <div class="fav-tab refresh-btn" title="强制刷新数据">
                            <svg viewBox="0 0 24 24" width="16" height="16" stroke="currentColor" stroke-width="2" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M3 2v6h6"></path>
                                <path d="M21 12A9 9 0 0 0 6 5.3L3 8"></path>
                                <path d="M21 22v-6h-6"></path>
                                <path d="M3 12a9 9 0 0 0 15 6.7l3-2.7"></path>
                            </svg>
                            <span class="refresh-text">刷新</span>
                        </div>
                    </div>
                `;
            }
              // 渲染当前收藏夹内容
            function renderCurrentFolder() {
                const currentFolder = state.folders.find(folder => folder.id === state.currentFolder);
                if (!currentFolder) return '';
                
                let html = `
                    <div class="fav-section">                
                        <div class="fav-content">
                            ${state.loading ? `
                                <div class="fav-loading">
                                    <div class="spinner"></div>
                                </div>
                            ` : renderFolderContent()}
                        </div>
                    </div>
                `;
                
                return html;
            }              // 渲染收藏夹内容
            function renderFolderContent() {
                if (!state.currentItems.length) {
                    return `
                        <div class="fav-empty">
                            <p>该收藏夹暂无内容</p>
                        </div>
                    `;
                }
                
                let html = `
                    <div class="fav-grid">
                        ${state.currentItems.map(item => renderFavItem(item)).join('')}
                    </div>
                    ${renderPagination()}
                `;
                
                return html;
            }    // 渲染单个收藏项 - 改进的现代卡片设计，使用弹出式视频播放
            function renderFavItem(item) {
                // 使用数据属性存储视频信息，而不是直接跳转
                let bvid = item.bvid || '';
                let cover = item.cover.replace("http://", "https://");
                let pubdate = item.pubdate ? formatDate(item.pubdate * 1000) : '';
                
            return `
                    <div class="fav-item" data-bvid="${bvid}" data-title="${item.title}" data-up="${item.upper?.name || '未知'}" tabindex="0" role="button" aria-label="播放视频: ${item.title}">
                        <div class="fav-item-content-wrapper">
                            <div class="fav-item-thumb">
                                <div class="fav-item-thumb-placeholder">
                                    <div class="spinner" style="width:24px;height:24px;"></div>
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
            }                // 格式化日期
            function formatDate(timestamp) {
                const date = new Date(timestamp);
                const now = new Date();
                const diffDays = Math.floor((now - date) / (1000 * 60 * 60 * 24));
                
                if (diffDays < 7) {
                    if (diffDays === 0) {
                        return '今天';
                    } else if (diffDays === 1) {
                        return '昨天';
                    } else {
                        return `${diffDays}天前`;
                    }
                } else {
                    return `${date.getFullYear()}-${String(date.getMonth() + 1).padStart(2, '0')}-${String(date.getDate()).padStart(2, '0')}`;
                }
            }
            
            // 格式化剩余时间
            function formatTimeLeft(seconds) {
                if (seconds < 60) {
                    return `${seconds}秒`;
                } else if (seconds < 3600) {
                    return `${Math.floor(seconds / 60)}分钟`;
                } else if (seconds < 86400) {
                    return `${Math.floor(seconds / 3600)}小时${Math.floor((seconds % 3600) / 60)}分钟`;
                } else {
                    return `${Math.floor(seconds / 86400)}天${Math.floor((seconds % 86400) / 3600)}小时`;
                }
            }
            
            // 渲染分页
            function renderPagination() {
                if (state.totalPages <= 1) return '';
                
                let paginationHtml = '<div class="fav-pagination">';
                
                // 上一页按钮
                paginationHtml += `
                    <button class="page-btn prev-btn" ${state.currentPage <= 1 ? 'disabled' : ''}>
                        上一页
                    </button>
                `;
                
                // 页码按钮
                const maxPagesToShow = 5;
                let startPage = Math.max(1, state.currentPage - Math.floor(maxPagesToShow / 2));
                let endPage = Math.min(state.totalPages, startPage + maxPagesToShow - 1);
                
                // 调整startPage确保显示正确数量的页码
                if (endPage - startPage + 1 < maxPagesToShow) {
                    startPage = Math.max(1, endPage - maxPagesToShow + 1);
                }
                
                // 第一页
                if (startPage > 1) {
                    paginationHtml += `<button class="page-btn page-num" data-page="1">1</button>`;
                    if (startPage > 2) {
                        paginationHtml += `<button class="page-btn page-ellipsis" disabled>...</button>`;
                    }
                }
                
                // 页码
                for (let i = startPage; i <= endPage; i++) {
                    paginationHtml += `
                        <button class="page-btn page-num ${i === state.currentPage ? 'active' : ''}" data-page="${i}">
                            ${i}
                        </button>
                    `;
                }
                
                // 最后页
                if (endPage < state.totalPages) {
                    if (endPage < state.totalPages - 1) {
                        paginationHtml += `<button class="page-btn page-ellipsis" disabled>...</button>`;
                    }
                    paginationHtml += `<button class="page-btn page-num" data-page="${state.totalPages}">${state.totalPages}</button>`;
                }
                
                // 下一页按钮
                paginationHtml += `
                    <button class="page-btn next-btn" ${state.currentPage >= state.totalPages ? 'disabled' : ''}>
                        下一页
                    </button>
                `;
                
                paginationHtml += '</div>';
                return paginationHtml;
            }    // 绑定事件
            function bindEvents() {
                // 添加键盘事件支持，使视频卡片可以通过Enter或Space键激活
                app.addEventListener('keydown', function(e) {
                    if ((e.key === 'Enter' || e.key === ' ') && e.target.classList.contains('fav-item')) {
                        e.preventDefault(); // 阻止空格键滚动页面
                        e.target.click(); // 触发点击事件
                    }
                });
                
                // 使用事件委托处理所有点击事件
                app.addEventListener('click', async function(e) {
                    // 强制刷新按钮
                    if (e.target.closest('.refresh-btn')) {
                        console.log('点击强制刷新按钮');
                        const refreshBtn = e.target.closest('.refresh-btn');
                        
                        // 防止重复点击
                        if (refreshBtn.classList.contains('refreshing')) return;
                        
                        // 添加刷新中状态
                        refreshBtn.classList.add('refreshing');
                        refreshBtn.querySelector('.refresh-text').textContent = '刷新中...';
                          try {
                            // 强制从网络重新获取数据
                            // 先清除localStorage缓存
                            if (state.currentFolder) {
                                localStorage.removeItem(`bilibili_favlist_${state.currentFolder}_${state.currentPage}`);
                            }
                            localStorage.removeItem('bilibili_favlist_folders');
                            
                            // 清除内存缓存
                            state.cache = new Map();
                            
                            // 重新获取数据，传入true表示强制刷新
                            await fetchAllFolders(true);
                            
                            console.log('强制刷新完成');
                        } catch (error) {
                            console.error('强制刷新失败:', error);
                            showError('刷新失败，请稍后重试');
                        } finally {
                            // 恢复按钮状态
                            refreshBtn.classList.remove('refreshing');
                            refreshBtn.querySelector('.refresh-text').textContent = '刷新';
                        }
                        return;
                    }
                    
                    // 收藏夹胶囊切换
                    if (e.target.classList.contains('fav-tab') || e.target.parentElement.classList.contains('fav-tab')) {
                        const tabEl = e.target.classList.contains('fav-tab') ? e.target : e.target.parentElement;
                        
                        // 忽略刷新按钮的点击（已在上面处理）
                        if (tabEl.classList.contains('refresh-btn')) return;
                        
                        const folderId = parseInt(tabEl.dataset.folderId, 10);
                        
                        if (folderId !== state.currentFolder) {
                            console.log('切换收藏夹:', folderId);
                            await fetchFolderItems(folderId, 1);
                            
                            // 平滑滚动到内容区域
                            const contentTop = app.querySelector('.fav-section-header');
                            if (contentTop) {
                                window.scrollTo({
                                    top: contentTop.offsetTop - 20,
                                    behavior: 'smooth'
                                });
                            }
                        }
                        return;
                    }
                    
                    // 页码按钮
                    if (e.target.classList.contains('page-num')) {
                        const page = parseInt(e.target.dataset.page, 10);
                        if (page !== state.currentPage) {
                            await fetchFolderItems(state.currentFolder, page);
                            
                            // 平滑滚动到内容区域
                            const contentTop = app.querySelector('.fav-section-header');
                            if (contentTop) {
                                window.scrollTo({
                                    top: contentTop.offsetTop - 20,
                                    behavior: 'smooth'
                                });
                            }
                        }
                    }
                    
                    // 上一页
                    if (e.target.classList.contains('prev-btn') && !e.target.disabled) {
                        if (state.currentPage > 1) {
                            await fetchFolderItems(state.currentFolder, state.currentPage - 1);
                            
                            // 平滑滚动到内容区域
                            const contentTop = app.querySelector('.fav-section-header');
                            if (contentTop) {
                                window.scrollTo({
                                    top: contentTop.offsetTop - 20,
                                    behavior: 'smooth'
                                });
                            }
                        }
                    }
                    
                    // 下一页
                    if (e.target.classList.contains('next-btn') && !e.target.disabled) {
                        if (state.currentPage < state.totalPages) {
                            await fetchFolderItems(state.currentFolder, state.currentPage + 1);
                            
                            // 平滑滚动到内容区域
                            const contentTop = app.querySelector('.fav-section-header');
                            if (contentTop) {
                                window.scrollTo({
                                    top: contentTop.offsetTop - 20,
                                    behavior: 'smooth'
                                });
                            }
                        }
                    }
                    
                    // 重试按钮
                    if (e.target.classList.contains('retry-btn')) {
                        console.log('点击重试按钮');
                        state.error = null;
                        state.loading = true;
                        renderApp();
                        try {
                            await initApp();
                        } catch (error) {
                            console.error('重试失败:', error);
                            showError(`重试失败: ${error.message}`);
                        }
                    }
                });
            }
            
            // 启动应用
            initApp();
              // 监听页面可见性变化，在返回页面时刷新内容    // 页面可见性相关变量
            let lastVisibilityChange = Date.now();
            let needReloadOnReturn = false;
            const RELOAD_THRESHOLD = 12 * 60 * 60 * 1000; // 12小时阈值，与缓存时间一致
            
            // 优化的页面可见性处理
            document.addEventListener('visibilitychange', function() {
                const now = Date.now();
                
                if (document.visibilityState === 'hidden') {
                    // 页面离开时记录时间
                    lastVisibilityChange = now;
                } else if (document.visibilityState === 'visible') {
                    // 页面返回时，只有满足以下条件才重新加载:
                    // 1. 已经加载过内容 (state.folders.length > 0)
                    // 2. 离开的时间超过阈值 (5分钟) 或已经标记需要重新加载
                    const timeAway = now - lastVisibilityChange;
                    if (state.folders.length > 0 && (timeAway > RELOAD_THRESHOLD || needReloadOnReturn)) {
                        console.log('页面返回，重新加载内容', { timeAway: timeAway/1000 + '秒' });
                        fetchFolderItems(state.currentFolder, state.currentPage);
                        needReloadOnReturn = false;
                    }
                }
            });
            
            // 添加滚动时的视差和动画效果
            const observer = new IntersectionObserver((entries) => {
                entries.forEach(entry => {
                    if (entry.isIntersecting) {
                        entry.target.classList.add('visible');
                        observer.unobserve(entry.target);
                    }
                });
            }, {
                threshold: 0.1,
                rootMargin: '0px 0px -50px 0px'
            });
            
            // 监听元素可见性变化，添加动画
            function setupScrollEffects() {
                if (!app) return;
                
                const items = app.querySelectorAll('.fav-item');
                items.forEach((item, index) => {
                    // 添加延迟动画类
                    item.style.animationDelay = `${index * 0.05}s`;
                    observer.observe(item);
                });
            }
              // 当DOM更新时应用滚动效果及图片懒加载
            const appObserver = new MutationObserver(() => {
                setupScrollEffects();
                // 懒加载新添加的图片
                app.querySelectorAll('img.fav-thumb-img[data-src]').forEach(img => {
                    imgObserver.observe(img);
                });
            });
            
            // 监听app容器的变化
            appObserver.observe(app, { childList: true, subtree: true });
              // 初始应用滚动效果
            setupScrollEffects();
            
            // 设置图片懒加载
            function setupImageLazyLoading() {
                // 创建新的Observer实例
                const imgObserver = new IntersectionObserver((entries, observer) => {
                    entries.forEach(entry => {
                        if (entry.isIntersecting) {
                            const img = entry.target;
                            const src = img.getAttribute('data-src');
                            if (src) {
                                // 先监听加载事件
                                img.onload = function() {
                                    img.classList.add('loaded');
                                    const placeholder = img.previousElementSibling;
                                    if (placeholder && placeholder.classList.contains('fav-item-thumb-placeholder')) {
                                        placeholder.innerHTML = '';
                                    }
                                };
                                
                                // 再监听错误事件
                                img.onerror = function() {
                                    console.log('图片加载失败，设置占位图：', src);
                                    img.src = 'data:image/svg+xml,%3Csvg xmlns="http://www.w3.org/2000/svg" width="100" height="100" viewBox="0 0 24 24"%3E%3Cpath fill="%23ddd" d="M21 19V5c0-1.1-.9-2-2-2H5c-1.1 0-2 .9-2 2v14c0 1.1.9 2 2 2h14c1.1 0 2-.9 2-2V5c0-1.1-.9-2-2-2zm-4.86 8.86l-3 3.87L9 13.14 6 17h12l-3.86-5.14z"/%3E%3C/svg%3E';
                                    img.classList.add('loaded');
                                    img.classList.add('error');
                                    
                                    const placeholder = img.previousElementSibling;
                                    if (placeholder && placeholder.classList.contains('fav-item-thumb-placeholder')) {
                                        placeholder.innerHTML = '<svg fill="#ccc" width="32" height="32" viewBox="0 0 24 24"><path d="M19 5v14H5V5h14m0-2H5c-1.1 0-2 .9-2 2v14c0 1.1.9 2 2 2h14c1.1 0-2-.9 2-2V5c0-1.1-.9-2-2-2zm-4.86 8.86l-3 3.87L9 13.14 6 17h12l-3.86-5.14z"/></svg>';
                                    }
                                };
                                
                                // 最后设置图片源
                                img.src = src;
                                img.removeAttribute('data-src');
                            }
                            imgObserver.unobserve(img);
                        }
                    });
                }, {
                    threshold: 0.05,
                    rootMargin: '100px' // 增加预加载区域，提前更多像素开始加载图片
                });
                  // 获取所有带data-src属性的图片
                const lazyImages = document.querySelectorAll('img[data-src]');
                if (lazyImages.length > 0) {
                    console.log('设置懒加载图片:', lazyImages.length);
                    lazyImages.forEach(img => imgObserver.observe(img));
                }
                
                return imgObserver;
            }
            
            // 设置图片懒加载
            const imgObserver = setupImageLazyLoading();
              // 性能优化：防抖函数
            function debounce(func, wait = 50) {
                let timeout;
                return function(...args) {
                    clearTimeout(timeout);
                    timeout = setTimeout(() => func.apply(this, args), wait);
                };
            }
              // 设置滚动动画效果
            function setupScrollAnimations() {
                // 优化滚动处理
                window._bilifav_scroll_handler = debounce(() => {
                    const items = document.querySelectorAll('.fav-item:not(.visible)');
                    items.forEach(item => {
                        const rect = item.getBoundingClientRect();
                        if (rect.top <= window.innerHeight * 0.85) {
                            item.classList.add('visible');
                        }
                    });
                }, 20);
                
                // 清除可能存在的旧事件
                window.removeEventListener('scroll', window._bilifav_scroll_handler);
                
                // 监听滚动事件以触发动画
                window.addEventListener('scroll', window._bilifav_scroll_handler);
                  // 初始触发一次
                window._bilifav_scroll_handler();
            }
            
            // 设置滚动动画
            setupScrollAnimations();

            // 创建和设置视频弹窗
            function setupVideoModal() {
                // 检查是否已存在模态框，避免多次创建
                let existingModal = document.querySelector('.video-modal');
                if (existingModal) {
                    return existingModal; // 如果已存在则直接返回
                }
                
                // 创建视频弹窗
                const videoModal = document.createElement('div');
                videoModal.className = 'video-modal';
                videoModal.innerHTML = `
                    <div class="video-modal-container">
                        <div class="video-modal-header">
                            <h3 class="video-modal-title"></h3>
                            <div class="video-modal-close">
                                <svg viewBox="0 0 24 24" width="24" height="24" stroke="currentColor" stroke-width="2" fill="none" stroke-linecap="round" stroke-linejoin="round"><line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line></svg>
                            </div>
                        </div>            
                        <div class="video-modal-body">
                            <iframe class="video-modal-iframe" src="" frameborder="0" scrolling="no" sandbox="allow-top-navigation allow-same-origin allow-forms allow-scripts" allowfullscreen></iframe>
                        </div>
                        <div class="video-modal-info">
                            <div class="video-modal-up">
                                <span class="video-modal-up-name"></span>
                            </div>
                            <a class="video-modal-open" href="" target="_blank">
                                <svg viewBox="0 0 24 24" width="16" height="16" stroke="currentColor" stroke-width="2" fill="none" stroke-linecap="round" stroke-linejoin="round"><path d="M18 13v6a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h6"></path><polyline points="15 3 21 3 21 9"></polyline><line x1="10" y1="14" x2="21" y2="3"></line></svg>
                                在B站打开
                            </a>
                        </div>
                    </div>
                `;
                document.body.appendChild(videoModal);
                
                // 关闭视频弹窗的函数
                const closeVideoModal = () => {
                    // 添加关闭动画
                    const container = videoModal.querySelector('.video-modal-container');
                    container.style.transform = 'translateY(20px)';
                    container.style.opacity = '0';
                    
                    // 延迟移除活动类以允许动画播放
                    setTimeout(() => {
                        videoModal.classList.remove('active');
                        // 恢复背景滚动
                        document.body.style.overflow = '';
                        // 暂停视频
                        const iframe = videoModal.querySelector('.video-modal-iframe');
                        iframe.src = "";
                    }, 300); // 与CSS过渡时间匹配
                };
                
                // 点击关闭按钮
                videoModal.querySelector('.video-modal-close').addEventListener('click', closeVideoModal);
                
                // 点击弹窗外部关闭
                videoModal.addEventListener('click', (e) => {
                    if (e.target === videoModal) {
                        closeVideoModal();
                    }
                });
                  // 按ESC键关闭
                window._bilifav_esc_handler = (e) => {
                    if (e.key === 'Escape' && videoModal.classList.contains('active')) {
                        closeVideoModal();
                    }
                };
                
                // 清除可能存在的旧事件监听器
                document.removeEventListener('keydown', window._bilifav_esc_handler);
                document.addEventListener('keydown', window._bilifav_esc_handler);
                
                return videoModal;
            }
            
            // 设置视频模态框
            const videoModal = setupVideoModal();
              // 添加收藏项点击事件，打开视频弹窗
            function setupVideoItemsClickEvent() {
                // 使用事件委托处理视频项点击
                // 保存处理函数引用，以便清理
                window._bilifav_click_handler = (e) => {
                    const favItem = e.target.closest('.fav-item');
                    if (favItem) {
                        const bvid = favItem.getAttribute('data-bvid');
                        if (bvid) {
                            e.preventDefault();
                            
                            // 获取视频模态框（如果不存在则会创建一个）
                            const videoModal = setupVideoModal();
                            
                            // 设置弹窗内容
                            const title = favItem.getAttribute('data-title');
                            const upName = favItem.getAttribute('data-up');
                            
                            videoModal.querySelector('.video-modal-title').textContent = title;
                            videoModal.querySelector('.video-modal-up-name').textContent = 'UP: ' + upName;
                            videoModal.querySelector('.video-modal-open').href = `https://www.bilibili.com/video/${bvid}`;
                            
                            // 获取iframe
                            const iframe = videoModal.querySelector('.video-modal-iframe');
                            
                            // 使用bilibili播放器设置iframe源
                            iframe.src = `https://player.bilibili.com/player.html?bvid=${bvid}&page=1&autoplay=1&danmaku=1`;
                            
                            // 显示弹窗
                            videoModal.classList.add('active');
                            setTimeout(() => {
                                videoModal.querySelector('.video-modal-container').style.transform = 'translateY(0)';
                                videoModal.querySelector('.video-modal-container').style.opacity = '1';
                            }, 10);
                        }
                    }
                };
                
                // 清除可能存在的旧事件监听器
                document.removeEventListener('click', window._bilifav_click_handler);
                
                // 添加点击事件到document，使用事件委托
                document.addEventListener('click', window._bilifav_click_handler);
            }
            
            // 设置视频项点击事件
            setupVideoItemsClickEvent();
        } catch (error) {
            console.error('初始化过程中发生错误:', error);
            // 显示用户友好的错误信息
            const app = document.getElementById('bilibili-favlist-app');
            if (app) {
                app.innerHTML = `
                    <div class="fav-empty">
                        <p>加载收藏夹失败: ${error.message || '未知错误'}</p>
                        <button class="page-btn retry-btn" onclick="window.location.reload()">重新加载</button>
                    </div>
                `;
            }
        }
    };
    
    // 在页面加载完成时初始化，并处理PJAX
    const init = () => {
        // 优先使用更现代的事件监听器
        document.addEventListener('DOMContentLoaded', initBilibiliFavList);
        
        // 支持PJAX切换
        document.addEventListener('pjax:complete', () => {
            // 延迟执行以确保DOM已更新
            setTimeout(() => {
                const app = document.getElementById('bilibili-favlist-app');
                if (app) {
                    initBilibiliFavList().catch(error => {
                        console.error('PJAX后初始化失败:', error);
                    });
                }
            }, 100);
        });
    };
    
    // 启动应用
    init();
})();
</script>

<?php
get_footer();
