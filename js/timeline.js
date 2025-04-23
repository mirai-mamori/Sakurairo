// 多语言翻译对象
const timelineTranslations = {
    'zh-CN': {
        yearOverview: '年度总览',
        articleCount: '投稿数',
        articles: '文章',
        shuoshuo: '说说',
        readCount: '阅读量',
        times: '次',
        wordCount: '字数',
        characters: '字',
        commentCount: '评论数',
        comments: '条',
        month: '月'
    },
    'zh-TW': {
        yearOverview: '年度總覽',
        articleCount: '投稿數',
        articles: '文章',
        shuoshuo: '說說',
        readCount: '閱讀量',
        times: '次',
        wordCount: '字數',
        characters: '字',
        commentCount: '評論數',
        comments: '條',
        month: '月'
    },
    'en': {
        yearOverview: 'Year Overview',
        articleCount: 'Posts',
        articles: 'Articles',
        shuoshuo: 'Shuoshuo',
        readCount: 'Views',
        times: 'times',
        wordCount: 'Words',
        characters: 'chars',
        commentCount: 'Comments',
        comments: '',
        month: ''
    },
    'ja': {
        yearOverview: '年間概要',
        articleCount: '投稿数',
        articles: '記事',
        shuoshuo: 'つぶやき',
        readCount: '閲覧数',
        times: '回',
        wordCount: '文字数',
        characters: '字',
        commentCount: 'コメント数',
        comments: '件',
        month: '月'
    },    'fr': {
        yearOverview: 'Aperçu annuel',
        articleCount: 'Articles',
        articles: 'Articles',
        shuoshuo: 'Shuoshuo',
        readCount: 'Vues',
        times: 'fois',
        wordCount: 'Mots',
        characters: 'caractères',
        commentCount: 'Commentaires',
        comments: '',
        month: ''
    }
};

class Timeline {
    constructor() {
        this.timelineRoot = null;
        this.modalMask = null;
        this.modalContent = null;
        this.modalClose = null;
        this.boundHandleClick = this.handleClick.bind(this);
        this.boundHandleModalClose = this.handleModalClose.bind(this);
        this.boundHandleMaskClick = this.handleMaskClick.bind(this);

        // 改进语言检测逻辑
        const htmlLang = (document.documentElement.lang || '').toLowerCase();
        console.log('Detected HTML lang:', htmlLang);
        
        // 支持更多中文变体
        const zhVariants = ['zh-hans', 'zh-cn', 'zh', 'zh-hans-cn'];
        const twVariants = ['zh-hant', 'zh-tw', 'zh-hant-tw'];
        
        // 更新语言匹配逻辑
        if (zhVariants.some(variant => htmlLang.includes(variant))) {
            this.currentLang = 'zh-CN';
        } else if (twVariants.some(variant => htmlLang.includes(variant))) {
            this.currentLang = 'zh-TW';
        } else if (htmlLang.startsWith('ja')) {
            this.currentLang = 'ja';
        } else if (htmlLang.startsWith('fr')) {
            this.currentLang = 'fr';
        } else {
            this.currentLang = 'en';
        }
            
        console.log('Selected language:', this.currentLang);
    }

    init() {
        this.timelineRoot = document.getElementById('timeline-root');
        this.modalMask = document.getElementById('timeline-modal-mask');
        this.modalContent = document.getElementById('timeline-modal-content');
        this.modalClose = document.getElementById('timeline-modal-close');

        this.contentAPI = this.modalContent.dataset.archiveAPI;
        this.contents = '';

        if (!this.timelineRoot || !this.modalMask || !this.modalContent || !this.modalClose) return;

        this.bindEvents();
    }

    async FetchContents() {
        try {
            const response = await fetch(this.contentAPI);
            this.contents = JSON.parse(response);
        } catch (error) {
            console.warn("获取内容失败，重试中...", error);
            try {
                const response = await fetch(this.contentAPI);
                this.contents = JSON.parse(response);
            } catch (retryError) {
                console.error("获取内容失败:", retryError);
            }
        }
    }

    destroy() {
        if (this.timelineRoot) {
            this.timelineRoot.removeEventListener('click', this.boundHandleClick);
        }
        if (this.modalClose) {
            this.modalClose.removeEventListener('click', this.boundHandleModalClose);
        }
        if (this.modalMask) {
            this.modalMask.removeEventListener('click', this.boundHandleMaskClick);
        }
    }

    bindEvents() {
        this.timelineRoot.addEventListener('click', this.boundHandleClick);
        this.modalClose.addEventListener('click', this.boundHandleModalClose);
        this.modalMask.addEventListener('click', this.boundHandleMaskClick);
    }

    handleClick(e) {
        const card = e.target.closest('.timeline-year-card');
        if (!card) return;

        const year = card.dataset.year;
        const months = this.contents[card.dataset.months];
        
        this.showYearModal(year, months);
    }

    handleModalClose() {
        this.modalMask.classList.remove('active');
    }

    handleMaskClick(e) {
        if (e.target === this.modalMask) {
            this.modalMask.classList.remove('active');
        }
    }

    showYearModal(year, months) {
        let totalStats = {
            posts: 0,
            views: 0,
            words: 0,
            comments: 0
        };
        
        let typeStats = {
            shuoshuo: {
                posts: 0,
                views: 0,
                words: 0,
                comments: 0
            },
            article: {
                posts: 0,
                views: 0,
                words: 0,
                comments: 0
            }
        };

        // 计算统计数据
        Object.values(months).forEach(monthPosts => {
            monthPosts.forEach(post => {
                const type = post.meta.type || 'article';
                const views = parseInt(post.meta.views) || 0;
                const words = parseInt(post.meta.words) || 0;
                const comments = parseInt(post.comment_count) || 0;

                totalStats.posts++;
                totalStats.views += views;
                totalStats.words += words;
                totalStats.comments += comments;

                typeStats[type].posts++;
                typeStats[type].views += views;
                typeStats[type].words += words;
                typeStats[type].comments += comments;
            });
        });        const t = timelineTranslations[this.currentLang];
        let html = `
            <h2 class="timeline-modal-title">${year} ${t.yearOverview}</h2>
            <div class="timeline-modal-stats-grid">
                <div class="timeline-modal-statbox">
                    <i class="stat-icon fas fa-file-alt"></i>
                    <span class="stat-label">${t.articleCount}</span>
                    <span class="stat-value">${totalStats.posts}</span>                    <div class="stat-tooltip">
                        ${t.articles}：${typeStats.article.posts}<br>
                        ${t.shuoshuo}：${typeStats.shuoshuo.posts}
                    </div>
                </div>
                <div class="timeline-modal-statbox">
                    <i class="stat-icon fas fa-eye"></i>
                    <span class="stat-label">${t.readCount}</span>
                    <span class="stat-value">${totalStats.views}</span>
                    <div class="stat-tooltip">${t.articles}：${typeStats.article.views} ${t.times}<br>
                        ${t.shuoshuo}：${typeStats.shuoshuo.views} ${t.times}
                    </div>
                </div>
                <div class="timeline-modal-statbox">
                    <i class="stat-icon fas fa-font"></i>
                    <span class="stat-label">${t.wordCount}</span>
                    <span class="stat-value">${totalStats.words}</span>
                    <div class="stat-tooltip">${t.articles}：${typeStats.article.words} ${t.characters}<br>
                        ${t.shuoshuo}：${typeStats.shuoshuo.words} ${t.characters}
                    </div>
                </div>
                <div class="timeline-modal-statbox">
                    <i class="stat-icon fas fa-comments"></i>
                    <span class="stat-label">${t.commentCount}</span>
                    <span class="stat-value">${totalStats.comments}</span>                    <div class="stat-tooltip">
                        ${t.articles}：${typeStats.article.comments} ${t.comments}<br>
                        ${t.shuoshuo}：${typeStats.shuoshuo.comments} ${t.comments}
                    </div>
                </div>
            </div>`;

        // 添加月份文章列表
        Object.entries(months).forEach(([month, posts]) => {
            if (posts.length) {
                html += `                    <div class="timeline-modal-month-group">
                        <h3 class="timeline-modal-month-title">${month}${t.month}</h3>
                        <div class="timeline-modal-post-list">`;
                
                posts.forEach(post => {
                    const date = new Date(post.post_date);
                    const formattedDate = `${date.getMonth() + 1}-${date.getDate()}`;
                    const postType = post.meta.type === 'shuoshuo' ? ` [${t.shuoshuo}]` : '';
                    
                    html += `
                        <a href="${post.guid}" class="timeline-modal-post-item">
                            <span class="timeline-modal-post-title">${post.post_title}${postType}</span>
                            <span class="timeline-modal-post-date">${formattedDate}</span>
                        </a>`;
                });
                
                html += `</div></div>`;
            }
        });

        this.modalContent.innerHTML = html;
        this.modalMask.classList.add('active');
    }
}

// 创建单例实例
let timelineInstance = null;

function initTimeline() {
    if (timelineInstance) {
        timelineInstance.destroy();
    }
    timelineInstance = new Timeline();
    timelineInstance.init();
    async () => await timelineInstance.FetchContents();
}

// 初始化事件监听
document.addEventListener('DOMContentLoaded', initTimeline);
document.addEventListener('pjax:complete', initTimeline);
