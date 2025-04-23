class Timeline {
    constructor() {
        this.timelineRoot = null;
        this.modalMask = null;
        this.modalContent = null;
        this.modalClose = null;
        this.boundHandleClick = this.handleClick.bind(this);
        this.boundHandleModalClose = this.handleModalClose.bind(this);
        this.boundHandleMaskClick = this.handleMaskClick.bind(this);
    }

    init() {
        this.timelineRoot = document.getElementById('timeline-root');
        this.modalMask = document.getElementById('timeline-modal-mask');
        this.modalContent = document.getElementById('timeline-modal-content');
        this.modalClose = document.getElementById('timeline-modal-close');

        if (!this.timelineRoot || !this.modalMask || !this.modalContent || !this.modalClose) return;

        this.bindEvents();
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
        const months = JSON.parse(card.dataset.months);
        
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
        });

        let html = `
            <h2 class="timeline-modal-title">${year}年度总览</h2>
            <div class="timeline-modal-stats-grid">
                <div class="timeline-modal-statbox">
                    <i class="stat-icon fas fa-file-alt"></i>
                    <span class="stat-label">文章数</span>
                    <span class="stat-value">${totalStats.posts}</span>
                    <div class="stat-tooltip">
                        文章：${typeStats.article.posts} 篇<br>
                        说说：${typeStats.shuoshuo.posts} 篇
                    </div>
                </div>
                <div class="timeline-modal-statbox">
                    <i class="stat-icon fas fa-eye"></i>
                    <span class="stat-label">阅读量</span>
                    <span class="stat-value">${totalStats.views}</span>
                    <div class="stat-tooltip">
                        文章：${typeStats.article.views} 次<br>
                        说说：${typeStats.shuoshuo.views} 次
                    </div>
                </div>
                <div class="timeline-modal-statbox">
                    <i class="stat-icon fas fa-font"></i>
                    <span class="stat-label">总字数</span>
                    <span class="stat-value">${totalStats.words}</span>
                    <div class="stat-tooltip">
                        文章：${typeStats.article.words} 字<br>
                        说说：${typeStats.shuoshuo.words} 字
                    </div>
                </div>
                <div class="timeline-modal-statbox">
                    <i class="stat-icon fas fa-comments"></i>
                    <span class="stat-label">评论数</span>
                    <span class="stat-value">${totalStats.comments}</span>
                    <div class="stat-tooltip">
                        文章：${typeStats.article.comments} 条<br>
                        说说：${typeStats.shuoshuo.comments} 条
                    </div>
                </div>
            </div>`;

        // 添加月份文章列表
        Object.entries(months).forEach(([month, posts]) => {
            if (posts.length) {
                html += `
                    <div class="timeline-modal-month-group">
                        <h3 class="timeline-modal-month-title">${month}月</h3>
                        <div class="timeline-modal-post-list">`;
                
                posts.forEach(post => {
                    const date = new Date(post.post_date);
                    const formattedDate = `${date.getMonth() + 1}-${date.getDate()}`;
                    const postType = post.meta.type === 'shuoshuo' ? ' [说说]' : '';
                    
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
}

// 初始化事件监听
document.addEventListener('DOMContentLoaded', initTimeline);
document.addEventListener('pjax:complete', initTimeline);
