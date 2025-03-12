/**
 * 友情链接提交功能
 * 
 * 处理友情链接提交表单的前端逻辑，包括表单验证、提交和用户反馈
 */

// 多语言翻译

function i18n_form () {
    let sakura_links = {
        // 简体中文
        'zh_CN': {
            // 按钮文本
            submitting: '提交中...',
            
            // 常规错误提示
            server_error: '服务器错误：',
            connection_error: '连接错误，请稍后重试',
            timeout_error: '请求超时，请稍后重试',
            
            // 成功提示
            submission_success: '友情链接提交成功，请等待审核！',
            
            // 验证错误提示
            name_required: '请输入网站名称',
            url_required: '请输入网站地址',
            description_required: '请输入网站描述',
            image_required: '请输入网站图片地址',
            email_required: '请输入联系邮箱',
            captcha_required: '请输入验证码',
            invalid_url: '请输入有效的网站地址',
            invalid_email: '请输入有效的邮箱地址',
            security_error: '安全验证失败，请刷新页面后重试',
            
            // 验证码相关
            captcha_load_error: '验证码加载失败，请刷新重试',
            refresh_captcha: '刷新验证码'
        },
        
        // 繁体中文
        'zh_TW': {
            // 按钮文本
            submitting: '提交中...',
            
            // 常规错误提示
            server_error: '伺服器錯誤：',
            connection_error: '連接錯誤，請稍後重試',
            timeout_error: '請求超時，請稍後重試',
            
            // 成功提示
            submission_success: '友情鏈接提交成功，請等待審核！',
            
            // 验证错误提示
            name_required: '請輸入網站名稱',
            url_required: '請輸入網站地址',
            description_required: '請輸入網站描述',
            image_required: '請輸入網站圖片地址',
            email_required: '請輸入聯繫郵箱',
            captcha_required: '請輸入驗證碼',
            invalid_url: '請輸入有效的網站地址',
            invalid_email: '請輸入有效的郵箱地址',
            security_error: '安全驗證失敗，請刷新頁面後重試',
            
            // 验证码相关
            captcha_load_error: '驗證碼加載失敗，請刷新重試',
            refresh_captcha: '刷新驗證碼'
        },
        
        // 日语
        'ja': {
            // 按钮文本
            submitting: '送信中...',
            
            // 常规错误提示
            server_error: 'サーバーエラー：',
            connection_error: '接続エラー、後でもう一度お試しください',
            timeout_error: 'リクエストがタイムアウトしました、後でもう一度お試しください',
            
            // 成功提示
            submission_success: 'リンク申請が成功しました。審査をお待ちください！',
            
            // 验证错误提示
            name_required: 'サイト名を入力してください',
            url_required: 'サイトURLを入力してください',
            description_required: 'サイトの説明を入力してください',
            image_required: 'サイト画像URLを入力してください',
            email_required: 'メールアドレスを入力してください',
            captcha_required: '認証コードを入力してください',
            invalid_url: '有効なURLを入力してください',
            invalid_email: '有効なメールアドレスを入力してください',
            security_error: 'セキュリティ検証に失敗しました、ページを更新して再試行してください',
            
            // 验证码相关
            captcha_load_error: '認証コードの読み込みに失敗しました、更新して再試行してください',
            refresh_captcha: '認証コードを更新'
        }
    };

    // 获取页面语言，默认简体中文
    let currentLang = 'zh_CN';
    try {
        // 优先从全局变量获取
        if (_iro.language) {
            currentLang = _iro.language;
        } 
        // 从HTML标签获取
        else if (document.documentElement.lang) {
            const htmlLang = document.documentElement.lang;
            currentLang = htmlLang.replace(/-/g, '_');
        }
        
        // 从URL参数获取
        const urlParams = new URLSearchParams(window.location.search);
        if (urlParams.has('lang')) {
            currentLang = urlParams.get('lang');
        }
    } catch (e) {
        // 出错时使用默认语言
    }

    const validLangs = ['zh_CN', 'zh_TW', 'ja'];
    const lang = validLangs.includes(currentLang) ? currentLang : 'zh_CN';
    const i18n = sakura_links[lang] || sakura_links['zh_CN'];

    return i18n;
}

// 全局初始化函数，支持PJAX环境
function initLinkSubmission() {
    const i18n = i18n_form();
    const submitButton = document.getElementById('openLinkModal');
    const linkModal = document.getElementById('linkModal');
    const closeButton = document.querySelector('.link-modal-close');
    const linkForm = document.getElementById('linkSubmissionForm');
    const captchaImg = document.getElementById('captchaImg');
    
    // 如果相关元素不存在，优雅退出
    if (!linkForm) {
        return;
    }

    let patternTitle = document.querySelector('.pattern-header span');
    if (patternTitle) { // 移动按钮到标题后方
        patternTitle.parentNode.insertBefore(submitButton, patternTitle.nextElementSibling);
        submitButton.style.display = 'block';
        submitButton.style.margin = '0 auto';
    }

    // 存储滚动状态
    let isScrollDisabled = false;
    let scrollPosition = 0;

    // 禁用页面滚动
    const disableScroll = () => {
        if (isScrollDisabled) return;
        scrollPosition = window.pageYOffset;
        document.body.style.overflow = 'hidden';
        document.body.style.position = 'fixed';
        document.body.style.width = '100%';
        document.body.style.top = `-${scrollPosition}px`;
        isScrollDisabled = true;
        // 存储状态到 sessionStorage，以便在页面重新加载时检查
        try {
            sessionStorage.setItem('scrollDisabled', 'true');
            sessionStorage.setItem('scrollPosition', scrollPosition.toString());
        } catch (e) {
            console.warn('Failed to save scroll state to sessionStorage');
        }
    };

    // 启用页面滚动
    const enableScroll = () => {
        if (!isScrollDisabled && !sessionStorage.getItem('scrollDisabled')) return;
        
        // 获取存储的滚动位置
        const storedPosition = sessionStorage.getItem('scrollPosition');
        if (storedPosition !== null) {
            scrollPosition = parseInt(storedPosition, 10);
        }
        
        document.body.style.removeProperty('overflow');
        document.body.style.removeProperty('position');
        document.body.style.removeProperty('width');
        document.body.style.removeProperty('top');
        window.scrollTo(0, scrollPosition);
        isScrollDisabled = false;
        
        // 清除存储的状态
        try {
            sessionStorage.removeItem('scrollDisabled');
            sessionStorage.removeItem('scrollPosition');
        } catch (e) {
            console.warn('Failed to clear scroll state from sessionStorage');
        }
    };

    // 清理函数 - 用于页面卸载时
    const cleanup = () => {
        enableScroll();
        // 移除所有事件监听器
        if (submitButton) {
            submitButton.removeEventListener('click', handleModalOpen);
        }
        if (closeButton) {
            closeButton.removeEventListener('click', handleModalClose);
        }
        if (linkModal) {
            window.removeEventListener('click', handleOutsideClick);
        }
        // 移除所有页面级事件监听器
        window.removeEventListener('beforeunload', cleanup);
        window.removeEventListener('pagehide', cleanup);
        window.removeEventListener('unload', cleanup);
        document.removeEventListener('visibilitychange', handleVisibilityChange);
    };

    // 处理页面可见性变化
    const handleVisibilityChange = () => {
        if (document.visibilityState === 'hidden') {
            enableScroll();
        }
    };

    // 事件处理函数
    const handleModalOpen = (e) => {
        e.preventDefault();
        linkModal.style.display = 'block';
        disableScroll();
        loadCaptcha();
    };

    const handleModalClose = () => {
        linkModal.style.display = 'none';
        enableScroll();
    };

    const handleOutsideClick = (e) => {
        if (e.target === linkModal) {
            linkModal.style.display = 'none';
            enableScroll();
        }
    };

    // 初始化时检查并恢复滚动状态
    if (sessionStorage.getItem('scrollDisabled')) {
        enableScroll();
    }

    // 初始化模态框
    if (submitButton && linkModal) {
        submitButton.addEventListener('click', handleModalOpen);
    }

    // 关闭模态框
    if (closeButton && linkModal) {
        closeButton.addEventListener('click', handleModalClose);
    }

    // 点击模态框外部关闭
    if (linkModal) {
        window.addEventListener('click', handleOutsideClick);
    }

    // 添加多个页面卸载相关的事件监听
    window.addEventListener('beforeunload', cleanup);
    window.addEventListener('pagehide', cleanup);
    window.addEventListener('unload', cleanup);
    document.addEventListener('visibilitychange', handleVisibilityChange);

    // 为PJAX环境添加清理
    document.addEventListener('pjax:beforeReplace', cleanup);
    document.addEventListener('akina:pjax:beforeReplace', cleanup);
    
    // 监听所有可能的链接点击
    document.addEventListener('click', (e) => {
        const link = e.target.closest('a');
        if (link && link.href && !link.target && !e.ctrlKey && !e.shiftKey && !e.metaKey && !e.altKey) {
            enableScroll();
        }
    }, true);

    // 监听表单提交
    document.addEventListener('submit', () => {
        enableScroll();
    }, true);

    // 刷新验证码（点击验证码图片时）
    if (captchaImg) {
        captchaImg.addEventListener('click', () => {
            loadCaptcha();
        });
    }

    // 页面加载完成后立即加载验证码
    setTimeout(loadCaptcha, 500);

    // 表单提交
    let isSubmitting = false;
    let submissionTimeout = null;

    if (linkForm) {
        linkForm.addEventListener('submit', (e) => {
            e.preventDefault();
            
            // 防止重复提交
            if (isSubmitting) {
                return;
            }
            
            // 重置状态
            clearTimeout(submissionTimeout);
            isSubmitting = true;
            
            // 验证表单
            if (!validateForm()) {
                isSubmitting = false;
                return;
            }
            
            // 禁用提交按钮
            const submitButton = linkForm.querySelector('.link-form-submit');
            if (submitButton) {
                submitButton.disabled = true;
            }
            
            displayStatus('info', i18n.submitting);
            
            // 准备表单数据
            const formData = new FormData(linkForm);
            formData.append('action', 'link_submission');
            
            // 设置提交超时
            submissionTimeout = setTimeout(() => {
                if (isSubmitting) {
                    isSubmitting = false;
                    if (submitButton) {
                        submitButton.disabled = false;
                    }
                    displayStatus('error', i18n.timeout_error);
                    loadCaptcha();
                }
            }, 15000);
            
            // 获取ajaxurl，如果未定义则从当前页面构建
            const ajaxUrl = _iro.ajaxurl || (window.location.origin + '/wp-admin/admin-ajax.php');
            
            // 发送AJAX请求
            fetch(ajaxUrl, {
                method: 'POST',
                body: formData,
                credentials: 'same-origin'
            })
            .then(response => {
                clearTimeout(submissionTimeout);
                
                // 捕获响应文本以便调试
                return response.text().then(text => {
                    // 检查响应状态
                    if (!response.ok) {
                        throw new Error(`${i18n.server_error} ${response.status}`);
                    }
                    
                    // 尝试解析JSON
                    try {
                        return JSON.parse(text);
                    } catch (e) {
                        throw new Error('服务器返回了无效的JSON响应');
                    }
                });
            })
            .then(data => {
                isSubmitting = false;
                if (submitButton) {
                    submitButton.disabled = false;
                }
                
                if (data.success) {
                    // 成功提交 - 优先使用服务器返回的消息，如果没有则使用前端翻译
                    displayStatus('success', data.data.message || i18n.submission_success);
                    linkForm.reset();
                } else {
                    // 提交失败
                    displayStatus('error', data.data.message || i18n.server_error);
                    loadCaptcha();
                }
            })
            .catch(error => {
                isSubmitting = false;
                clearTimeout(submissionTimeout);
                
                if (submitButton) {
                    submitButton.disabled = false;
                }
                
                displayStatus('error', error.message || i18n.connection_error);
                loadCaptcha();
            });
        });
    }
}

// 验证表单
function validateForm() {
    const siteName = document.getElementById('siteName');
    const siteUrl = document.getElementById('siteUrl');
    const siteDescription = document.getElementById('siteDescription');
    const siteImage = document.getElementById('siteImage');
    const contactEmail = document.getElementById('contactEmail');
    const captcha = document.getElementById('yzm');
    const timestampInput = document.getElementById('timestamp');
    const idInput = document.getElementById('captchaId');
    const nonceInput = document.querySelector('input[name="link_submission_nonce"]');
    
    // 检查是否所有必要元素都存在
    if (!siteName || !siteUrl || !siteDescription || !siteImage || !contactEmail || !captcha || !timestampInput || !idInput || !nonceInput) {
        displayStatus('error', i18n.security_error);
        return false;
    }
    
    // 检查必填字段
    if (!siteName.value.trim()) {
        displayStatus('error', i18n.name_required);
        return false;
    }
    
    if (!siteUrl.value.trim()) {
        displayStatus('error', i18n.url_required);
        return false;
    }
    
    if (!siteDescription.value.trim()) {
        displayStatus('error', i18n.description_required);
        return false;
    }
    
    if (!siteImage.value.trim()) {
        displayStatus('error', i18n.image_required);
        return false;
    }
    
    if (!contactEmail.value.trim()) {
        displayStatus('error', i18n.email_required);
        return false;
    }
    
    if (!captcha.value.trim()) {
        displayStatus('error', i18n.captcha_required);
        return false;
    }
    
    // 校验nonce和验证码字段
    if (!nonceInput.value) {
        displayStatus('error', i18n.security_error);
        return false;
    }
    
    if (!timestampInput.value || !idInput.value) {
        displayStatus('error', i18n.captcha_load_error);
        return false;
    }
    
    // 检查URL格式 - 确保以http或https开头
    let siteUrlValue = siteUrl.value.trim();
    if (!/^https?:\/\//i.test(siteUrlValue)) {
        siteUrlValue = 'https://' + siteUrlValue;
        siteUrl.value = siteUrlValue;
    }
    
    const urlPattern = /^https?:\/\/([a-zA-Z0-9-]+\.)+[a-zA-Z]{2,}(\/[^\s]*)?$/;
    if (!urlPattern.test(siteUrlValue)) {
        displayStatus('error', i18n.invalid_url);
        return false;
    }
    
    // 检查图片URL格式 - 确保以http或https开头
    let siteImageValue = siteImage.value.trim();
    if (!/^https?:\/\//i.test(siteImageValue)) {
        siteImageValue = 'https://' + siteImageValue;
        siteImage.value = siteImageValue;
    }
    
    if (!urlPattern.test(siteImageValue)) {
        displayStatus('error', i18n.invalid_url);
        return false;
    }
    
    // 检查邮箱格式
    const emailPattern = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    if (!emailPattern.test(contactEmail.value.trim())) {
        displayStatus('error', i18n.invalid_email);
        return false;
    }
    
    // 防止XSS攻击 - 检查输入值是否包含可疑内容
    const xssPattern = /<script[^>]*>[\s\S]*?<\/script>|<[^>]*on\w+\s*=|javascript:|data:/i;
    if (
        xssPattern.test(siteName.value) || 
        xssPattern.test(siteDescription.value) || 
        xssPattern.test(siteUrlValue) || 
        xssPattern.test(siteImageValue) || 
        xssPattern.test(contactEmail.value)
    ) {
        displayStatus('error', i18n.security_error);
        return false;
    }
    
    return true;
}

// 加载验证码
function loadCaptcha() {
    const captchaImg = document.getElementById('captchaImg');
    const captchaInput = document.getElementById('yzm');
    const timestampInput = document.getElementById('timestamp');
    const idInput = document.getElementById('captchaId');
    
    // 元素检查
    if (!captchaImg || !captchaInput || !timestampInput || !idInput) {
        return;
    }
    
    // 显示加载中状态
    captchaImg.src = 'data:image/svg+xml;base64,PHN2ZyB4bWxucz0iaHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmciIHdpZHRoPSIzMCIgaGVpZ2h0PSIzMCIgdmlld0JveD0iMCAwIDM4IDM4IiBzdHJva2U9IiM2NjYiPjxnIGZpbGw9Im5vbmUiIGZpbGwtcnVsZT0iZXZlbm9kZCI+PGcgdHJhbnNmb3JtPSJ0cmFuc2xhdGUoMSAxKSIgc3Ryb2tlLXdpZHRoPSIyIj48Y2lyY2xlIHN0cm9rZS1vcGFjaXR5PSIuMyIgY3g9IjE4IiBjeT0iMTgiIHI9IjE4Ii8+PHBhdGggZD0iTTM2IDE4YzAtOS45NC04LjA2LTE4LTE4LTE4Ij48YW5pbWF0ZVRyYW5zZm9ybSBhdHRyaWJ1dGVOYW1lPSJ0cmFuc2Zvcm0iIHR5cGU9InJvdGF0ZSIgZnJvbT0iMCAxOCAxOCIgdG89IjM2MCAxOCAxOCIgZHVyPSIxcyIgcmVwZWF0Q291bnQ9ImluZGVmaW5pdGUiLz48L3BhdGg+PC9nPjwvZz48L3N2Zz4=';
    captchaInput.value = '';
    
    // 获取验证码API地址
    let endpointUrl = _iro.captcha_endpoint || '';
    if (!endpointUrl) {
        const endpointInput = document.getElementById('captcha-endpoint');
        if (endpointInput) {
            endpointUrl = endpointInput.value;
        } else {
            displayStatus('error', i18n.captcha_load_error);
            return;
        }
    }
    
    // 添加时间戳防止缓存
    const timestamp = new Date().getTime();
    const endpoint = endpointUrl + (endpointUrl.includes('?') ? '&' : '?') + 't=' + timestamp;
    
    fetch(endpoint, {
        method: 'GET',
        credentials: 'same-origin',
        cache: 'no-store',
        headers: {
            'Accept': 'application/json'
        }
    })
    .then(response => {
        if (!response.ok) {
            throw new Error(i18n.captcha_load_error);
        }
        return response.text();
    })
    .then(responseText => {
        try {
            const data = JSON.parse(responseText);
            
            // 首先检查API返回的code，不为0表示API自身报错
            if (data.code !== undefined && data.code !== 0) {
                throw new Error(data.msg || i18n.captcha_load_error);
            }
            
            // 检查必要字段是否存在
            if (!data.data || !data.id || !data.time) {
                throw new Error(i18n.captcha_load_error);
            }
            
            // 判断data是否为完整的data:image URL，如果是则直接使用，否则将其视为Base64数据
            const imgSrc = data.data.startsWith('data:image') ? data.data : 'data:image/jpeg;base64,' + data.data;
            
            // 设置验证码图片
            captchaImg.src = imgSrc;
            captchaImg.classList.remove('error');
            
            // 设置ID和时间戳
            timestampInput.value = String(data.time);
            idInput.value = String(data.id);
        } catch (e) {
            throw new Error(i18n.captcha_load_error);
        }
    })
    .catch(error => {
        captchaImg.classList.add('error');
        captchaImg.src = 'data:image/svg+xml;base64,PHN2ZyB4bWxucz0iaHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmciIHdpZHRoPSI1MCIgaGVpZ2h0PSI1MCIgdmlld0JveD0iMCAwIDI0IDI0IiBmaWxsPSJub25lIiBzdHJva2U9IiNlNzRjM2MiIHN0cm9rZS13aWR0aD0iMiIgc3Ryb2tlLWxpbmVjYXA9InJvdW5kIiBzdHJva2UtbGluZWpvaW49InJvdW5kIj48Y2lyY2xlIGN4PSIxMiIgY3k9IjEyIiByPSIxMCI+PC9jaXJjbGU+PGxpbmUgeDE9IjE1IiB5MT0iOSIgeDI9IjkiIHkyPSIxNSI+PC9saW5lPjxsaW5lIHgxPSI5IiB5MT0iOSIgeDI9IjE1IiB5Mj0iMTUiPjwvbGluZT48L3N2Zz4=';
        displayStatus('error', i18n.captcha_load_error);
        setTimeout(loadCaptcha, 3000);
    });
}

// 显示状态消息
function displayStatus(type, message) {
    const statusElement = document.getElementById('formStatus');
    
    if (!statusElement || !message) {
        return;
    }
    
    // 清理消息内容，防止XSS
    const sanitizedMessage = message.replace(/[<>"'&]/g, (c) => {
        return {'<': '&lt;', '>': '&gt;', '"': '&quot;', "'": '&#39;', '&': '&amp;'}[c];
    });
    
    // 设置消息类型和内容
    statusElement.className = 'form-status';
    statusElement.classList.add(type === 'success' ? 'success-msg' : 'error-msg');
    statusElement.textContent = sanitizedMessage;
    statusElement.style.display = 'block';
    
    // 成功消息自动隐藏
    if (type === 'success') {
        setTimeout(() => {
            if (statusElement) {
                statusElement.style.display = 'none';
            }
        }, 5000);
    }
}

// 页面加载完成时初始化链接提交功能
document.addEventListener('DOMContentLoaded', initLinkSubmission);

// PJAX环境下的初始化
document.addEventListener('pjax:complete', initLinkSubmission);
document.addEventListener('akina:pjax:end', initLinkSubmission);
