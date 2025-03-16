/**
 * Sakurairo文章注释功能
 * 识别并展示文章中的复杂名词注释
 */
(function() {
    'use strict';
    
    // 创建注释弹出层
    function createAnnotationPopup() {
        // 检查是否已经存在弹出层
        let popup = document.getElementById('iro-annotation-popup');
        if (popup) {
            return popup;
        }
        
        // 创建弹出层元素
        popup = document.createElement('div');
        popup.id = 'iro-annotation-popup';
        popup.className = 'iro-annotation-popup';
        popup.innerHTML = '<span class="close">×</span><div class="term"></div><div class="explanation"></div>';
        document.body.appendChild(popup);
        
        // 关闭按钮点击事件
        popup.querySelector('.close').addEventListener('click', function() {
            popup.style.display = 'none';
        });
        
        return popup;
    }
    
    // 处理注释标记点击事件
    function handleAnnotationClick(event) {
        event.preventDefault();
        event.stopPropagation();
        
        const term = this.dataset.term;
        if (window.iroAnnotations && window.iroAnnotations[term]) {
            const explanation = window.iroAnnotations[term];
            
            const popup = createAnnotationPopup();
            
            popup.querySelector('.term').textContent = term;
            popup.querySelector('.explanation').textContent = explanation;
            
            const rect = this.getBoundingClientRect();
            popup.style.top = (window.pageYOffset + rect.bottom + 10) + 'px';
            popup.style.left = (rect.left - 50) + 'px';
            popup.style.display = 'block';
        }
    }
    
    // 点击其他区域关闭弹窗
    function handleDocumentClick(event) {
        const popup = document.getElementById('iro-annotation-popup');
        if (popup && !event.target.closest('.iro-term-annotation, .iro-annotation-popup')) {
            popup.style.display = 'none';
        }
    }
    
    // 初始化注释功能
    function initAnnotations() {
        // 创建弹出层
        createAnnotationPopup();
        
        // 添加文档点击事件监听器
        document.removeEventListener('click', handleDocumentClick);
        document.addEventListener('click', handleDocumentClick);
        
        // 为所有注释标记添加点击事件
        const annotationMarks = document.querySelectorAll('.iro-term-annotation');
        annotationMarks.forEach(mark => {
            mark.removeEventListener('click', handleAnnotationClick);
            mark.addEventListener('click', handleAnnotationClick);
        });
    }
    
    // 导出初始化函数给全局使用
    window.iroInitAnnotations = initAnnotations;
    
    // 在DOM加载完成后初始化
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', initAnnotations);
    } else {
        initAnnotations();
    }
    
    // 适配pjax
    document.addEventListener('pjax:complete', initAnnotations);
})();
