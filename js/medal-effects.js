// 初始化函数：同时支持DOMContentLoaded和PJAX加载情况
function init_medal_effects_main() {
    // 先清除可能存在的旧监听器和效果
    const oldModals = document.querySelectorAll('#medal-detail-modal');
    oldModals.forEach(modal => modal.remove());
    
    // 使用全局对象初始化所有效果
    initMedalEffects();
    
    // 检测设备是否支持视差效果
    if (window.matchMedia('(hover: hover)').matches) {
        initParallaxEffect();
    }
    
    // 为金牌添加闪光效果
    addShineEffect();
}

// 在页面首次加载时执行
document.addEventListener('DOMContentLoaded', init_medal_effects_main);

// 兼容PJAX：在页面内容更新后重新初始化
document.addEventListener('pjax:complete', init_medal_effects_main);

function initMedalEffects() {
    const medals = document.querySelectorAll('.medal-capsule');
    
    if (!medals.length) return;
    
    // 初始化每个徽章
    medals.forEach(medal => {
        // 创建粒子效果
        createParticles(medal);
        
        // 清除之前的click事件监听器（避免PJAX环境下重复绑定）
        const newMedal = medal.cloneNode(true);
        medal.parentNode.replaceChild(newMedal, medal);
        
        // 添加点击事件，显示成就详细信息
        newMedal.addEventListener('click', function() {
            showMedalDetails(this);
        });
        
        // 延迟加载动画，确保CSS过渡效果能够正确执行
        setTimeout(() => {
            animateMedalEntry(newMedal);
        }, 100 + Math.random() * 300);
    });
}

// 创建粒子效果
function createParticles(medal) {
    const container = medal.querySelector('.medal-particles');
    if (!container) return;
    
    const medalType = medal.getAttribute('data-medal-level') || 'bronze';
    let particleColor;
    
    // 根据徽章类型设置粒子颜色
    switch(medalType) {
        case 'gold':
            particleColor = 'rgba(255, 215, 0, 0.8)';
            break;
        case 'silver':
            particleColor = 'rgba(192, 192, 192, 0.8)';
            break;
        case 'bronze':
        default:
            particleColor = 'rgba(205, 127, 50, 0.8)';
            break;
    }
    
    // 创建10-15个粒子
    const particleCount = 10 + Math.floor(Math.random() * 5);
    for (let i = 0; i < particleCount; i++) {
        const particle = document.createElement('span');
        particle.className = 'medal-particle';
        
        // 设置粒子样式
        Object.assign(particle.style, {
            position: 'absolute',
            width: (2 + Math.random() * 3) + 'px',
            height: (2 + Math.random() * 3) + 'px',
            background: particleColor,
            borderRadius: '50%',
            opacity: 0.3 + Math.random() * 0.7,
            top: Math.random() * 100 + '%',
            left: Math.random() * 100 + '%',
            transition: 'transform ' + (1.5 + Math.random() * 2) + 's ease-out, opacity 1s ease-out',
            pointerEvents: 'none',
            boxShadow: '0 0 3px ' + particleColor
        });
        
        container.appendChild(particle);
        
        // 设置粒子悬浮动画
        medal.addEventListener('mouseenter', () => {
            setTimeout(() => {
                Object.assign(particle.style, {
                    transform: `translate3d(
                        ${-20 + Math.random() * 40}px, 
                        ${-20 + Math.random() * 40}px, 
                        0
                    )`,
                    opacity: Math.random() * 0.6
                });
            }, Math.random() * 100);
        });
        
        medal.addEventListener('mouseleave', () => {
            setTimeout(() => {
                Object.assign(particle.style, {
                    transform: 'translate3d(0, 0, 0)',
                    opacity: 0.3 + Math.random() * 0.7
                });
            }, Math.random() * 100);
        });
    }
}

// 显示徽章详细信息的交互
function showMedalDetails(medal) {
    // 获取徽章数据
    const medalType = medal.getAttribute('data-medal-type');
    const medalLevel = medal.getAttribute('data-medal-level');
    const achievement = medal.getAttribute('data-achievement');
    const nextLevel = medal.getAttribute('data-next-level');
    const progress = medal.getAttribute('data-progress');
    
    // 给徽章一个"按下"的视觉反馈
    medal.style.transform = 'scale(0.98)';
    setTimeout(() => {
        medal.style.transform = '';
    }, 200);
    
    // 如果目标浏览器支持，添加触觉反馈
    if ('vibrate' in navigator) {
        navigator.vibrate(30);
    }
    
    // 检查是否已存在模态框，如果存在则移除
    let existingModal = document.getElementById('medal-detail-modal');
    if (existingModal) {
        existingModal.remove();
    }
    
    // 创建模态框
    const modal = document.createElement('div');
    modal.id = 'medal-detail-modal';
    modal.className = 'medal-detail-modal ' + medalLevel;
    
    // 创建模态框内容
    const modalContent = document.createElement('div');
    modalContent.className = 'medal-modal-content';
    
    // 添加徽章图标
    const medalIcon = document.createElement('div');
    medalIcon.className = 'modal-medal-icon';
    medalIcon.innerHTML = '<i class="fa-solid fa-medal"></i>';
    // 添加标题和成就描述
    const medalTitle = document.createElement('div');
    medalTitle.className = 'modal-medal-title';
    
    // 添加标题内容
    medalTitle.innerHTML = `
        <h2>${medal.querySelector('.capsule-label').textContent}</h2>
    `;
    // 添加成就描述
    const achievementElem = document.createElement('div');
    achievementElem.className = 'modal-medal-achievement';
    
    // 使用PHP传递的achievement内容
    achievementElem.textContent = achievement || '';
    
    // 添加进度信息
    const progressContainer = document.createElement('div');
    progressContainer.className = 'modal-medal-progress-container';
      if (progress && progress < 100) {
        const roundedProgress = Math.round(progress);
        progressContainer.innerHTML = `
            <div class="modal-medal-progress">
                <div class="modal-medal-progress-bar" style="width: ${progress}%"></div>
                <span class="progress-percentage">${roundedProgress}%</span>
            </div>
            <div class="modal-medal-next-level">${nextLevel || ''}</div>
        `;    } else if (medalLevel === 'gold') {
        const text = (typeof medalI18n !== 'undefined') ? medalI18n.maxLevelText : 'Maximum level reached';
        progressContainer.innerHTML = `
            <div class="modal-medal-max-level">${text}</div>
        `;
    }
    
    // 添加粒子效果背景
    const particlesContainer = document.createElement('div');
    particlesContainer.className = 'modal-particles-container';
    
    // 组装模态框
    modalContent.appendChild(medalIcon);
    modalContent.appendChild(medalTitle);
    modalContent.appendChild(achievementElem);
    modalContent.appendChild(progressContainer);
    
    modal.appendChild(particlesContainer);
    modal.appendChild(modalContent);
    
    // 添加关闭按钮
    const closeButton = document.createElement('button');
    closeButton.className = 'modal-close-button';
    closeButton.innerHTML = '&times;';
    closeButton.onclick = function() {
        closeModal(modal);
    };
    modalContent.appendChild(closeButton);
    
    // 添加到页面
    document.body.appendChild(modal);
    
    // 创建模态框背景粒子
    createModalParticles(particlesContainer, medalLevel);
    
    // 动画显示模态框
    setTimeout(() => {
        modal.classList.add('active');
    }, 10);
    
    // 点击模态框外部关闭
    modal.addEventListener('click', function(e) {
        if (e.target === modal) {
            closeModal(modal);
        }
    });
    
    // 添加ESC键关闭
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            closeModal(modal);
        }
    });
}

// 关闭模态框
function closeModal(modal) {
    modal.classList.remove('active');
    modal.classList.add('closing');
    
    // 移除模态框
    setTimeout(() => {
        modal.remove();
    }, 300);
}

// 为模态框创建粒子效果
function createModalParticles(container, medalType) {
    let particleColor;
    let particleCount = 20;
    
    switch(medalType) {
        case 'gold':
            particleColor = 'rgba(255, 215, 0, 0.8)';
            particleCount = 30; // 金牌特殊效果，更多粒子
            break;
        case 'silver':
            particleColor = 'rgba(192, 192, 192, 0.8)';
            break;
        case 'bronze':
        default:
            particleColor = 'rgba(205, 127, 50, 0.8)';
            break;
    }
    
    for (let i = 0; i < particleCount; i++) {
        const particle = document.createElement('span');
        particle.className = 'modal-particle';
        
        // 随机大小和位置
        const size = 3 + Math.random() * 5;
        // 生成唯一的动画名称
        const animationName = `float-particle-${Math.random().toString(36).substr(2, 9)}`;
        
        // 创建自定义动画
        const xMove = -20 + Math.random() * 40;
        const yMove = -20 + Math.random() * 40;
        const styleSheet = document.styleSheets[0];
        const animationRule = `
            @keyframes ${animationName} {
                0% { transform: translate(0, 0); }
                50% { transform: translate(${xMove/2}px, ${yMove/2}px); }
                100% { transform: translate(${xMove}px, ${yMove}px); }
            }
        `;
        
        try {
            styleSheet.insertRule(animationRule, styleSheet.cssRules.length);
        } catch (e) {
            // 如果不能直接插入，则创建style标签
            const style = document.createElement('style');
            style.textContent = animationRule;
            document.head.appendChild(style);
        }
        
        // 设置粒子样式
        Object.assign(particle.style, {
            position: 'absolute',
            width: size + 'px',
            height: size + 'px',
            background: particleColor,
            borderRadius: '50%',
            opacity: 0.1 + Math.random() * 0.5,
            top: Math.random() * 100 + '%',
            left: Math.random() * 100 + '%',
            boxShadow: '0 0 ' + (size/2) + 'px ' + particleColor,
            animation: `${animationName} ${3 + Math.random() * 5}s ease-in-out infinite alternate`,
            animationDelay: `-${Math.random() * 5}s`
        });
        
        container.appendChild(particle);
    }
}

// 徽章入场动画
function animateMedalEntry(medal) {
    medal.style.opacity = '1';
    medal.style.contentVisibility = 'visible';
    medal.style.transform = 'translateY(0)';
}

// 初始化视差效果
function initParallaxEffect() {
    const medals = document.querySelectorAll('.medal-capsule');
    
    medals.forEach(medal => {
        // 设置3D变换样式
        medal.style.transform = 'perspective(1000px)';
        medal.style.transformStyle = 'preserve-3d';
        
        // 获取徽章各元素
        const icon = medal.querySelector('i');
        const content = medal.querySelector('.capsule-content');
        const particles = medal.querySelector('.medal-particles');
        
        if (icon) icon.style.transform = 'translateZ(5px)';
        if (content) content.style.transform = 'translateZ(3px)';
        
        // 添加鼠标移动视差效果
        medal.addEventListener('mousemove', function(e) {
            const rect = this.getBoundingClientRect();
            const x = e.clientX - rect.left; // x position within the element
            const y = e.clientY - rect.top;  // y position within the element
            const centerX = rect.width / 2;
            const centerY = rect.height / 2;
            
            // 计算相对中心的位置（-1到1的范围）
            const deltaX = (x - centerX) / centerX;
            const deltaY = (y - centerY) / centerY;
            
            // 应用旋转和位移效果，较小的角度让效果更细微
            this.style.transform = `
                perspective(1000px)
                rotateY(${deltaX * 5}deg)
                rotateX(${-deltaY * 5}deg)
                scale3d(1.05, 1.05, 1.05)
            `;
            
            // 为子元素应用不同程度的位移，创造视差效果
            if (icon) {
                icon.style.transform = `
                    translateX(${deltaX * 6}px)
                    translateY(${deltaY * 4}px)
                    translateZ(15px)
                `;
            }
            
            if (content) {
                content.style.transform = `
                    translateX(${deltaX * 3}px)
                    translateY(${deltaY * 2}px)
                    translateZ(5px)
                `;
            }
            
            if (particles) {
                particles.style.transform = `
                    translateX(${-deltaX * 2}px)
                    translateY(${-deltaY * 2}px)
                    translateZ(-5px)
                `;
            }
        });
        
        // 鼠标离开时恢复原状
        medal.addEventListener('mouseleave', function() {
            this.style.transform = 'perspective(1000px)';
            if (icon) icon.style.transform = 'translateZ(5px)';
            if (content) content.style.transform = 'translateZ(3px)';
            if (particles) particles.style.transform = 'translateZ(-5px)';
        });
    });
}

// 为徽章添加自然光效果
function addShineEffect() {
    const goldMedals = document.querySelectorAll('.medal-capsule.gold');

    // 定义CSS动画规则
    const styleSheet = document.styleSheets[0];
    const animationRules = `
        /* 环境光效果动画 - 更明显的强度和位置变化 */
        @keyframes ambient-light {
            0% { opacity: 0.4; background-position: 20% 20%; background-size: 140% 140%; }
            20% { opacity: 0.7; background-position: 45% 30%; background-size: 165% 165%; }
            40% { opacity: 0.5; background-position: 50% 45%; background-size: 155% 155%; }
            60% { opacity: 0.75; background-position: 15% 40%; background-size: 170% 170%; }
            80% { opacity: 0.6; background-position: 25% 15%; background-size: 160% 160%; }
            100% { opacity: 0.4; background-position: 20% 20%; background-size: 140% 140%; }
        }
        /* 环境光颜色变化动画 - 更强烈的颜色变化效果 */
        @keyframes ambient-color {
            0% { filter: hue-rotate(0deg) brightness(1) contrast(1); }
            25% { filter: hue-rotate(8deg) brightness(1.15) contrast(1.05); }
            50% { filter: hue-rotate(3deg) brightness(1.1) contrast(0.98); }
            75% { filter: hue-rotate(-6deg) brightness(0.95) contrast(1.08); }
            100% { filter: hue-rotate(0deg) brightness(1) contrast(1); }
        }
        /* 光斑动画 - 更强烈的闪烁与变换 */        @keyframes light-fleck-1 {
            0%, 100% { opacity: 0.4; transform: scale(1) translate(0, 0); filter: brightness(1); }
            20% { opacity: 0.75; transform: scale(1.04) translate(2px, -1px); filter: brightness(1.15); }
            50% { opacity: 0.5; transform: scale(0.98) translate(-1px, -2px); filter: brightness(0.95); }
            80% { opacity: 0.65; transform: scale(1.02) translate(1px, 2px); filter: brightness(1.1); }
        }
        @keyframes light-fleck-2 {
            0%, 100% { opacity: 0.45; transform: scale(1) translate(0, 0); filter: brightness(1.05); }
            30% { opacity: 0.6; transform: scale(1.03) translate(-2px, 2px); filter: brightness(1.15); }
            60% { opacity: 0.4; transform: scale(0.97) translate(2px, 1px); filter: brightness(0.98); }
            85% { opacity: 0.7; transform: scale(1.01) translate(-1px, -2px); filter: brightness(1.2); }
        }
    `;

    try {
        styleSheet.insertRule(animationRules, styleSheet.cssRules.length);
    } catch (e) {
        // 如果直接插入失败，则创建style标签
        const style = document.createElement('style');
        style.textContent = animationRules;
        document.head.appendChild(style);
    }

    goldMedals.forEach(medal => {
        // 确保徽章有正确的定位
        if (window.getComputedStyle(medal).position === 'static') {
            medal.style.position = 'relative';
        }

        // 检查是否已经有光效，有则移除（防止PJAX重复添加）
        const existingEffects = medal.querySelectorAll('.medal-shine, .medal-ambient, .medal-glow, .medal-fleck');
        existingEffects.forEach(effect => effect.remove());

        // 添加动态环境光效果 - 模拟自然环境光照
        const ambient = document.createElement('div');
        ambient.className = 'medal-ambient';
        Object.assign(ambient.style, {
            position: 'absolute',
            top: '-20%',
            left: '-20%',
            width: '140%',
            height: '140%',
            background: 'radial-gradient(circle at 30% 30%, rgba(255,236,150,0.75) 0%, rgba(255,220,100,0.35) 35%, rgba(255,215,0,0.15) 65%, rgba(255,215,0,0) 80%)',
            backgroundSize: '150% 150%', // 使背景更大以便移动
            borderRadius: 'inherit',
            pointerEvents: 'none',
            zIndex: '2',
            mixBlendMode: 'color-dodge', // 更明显的混合模式
            animation: 'ambient-light 9s ease-in-out infinite, ambient-color 12s ease-in-out infinite',
            opacity: '0.8',
            transition: 'transform 0.5s ease-out, background 0.5s ease'
        });

        // 添加鼠标互动效果，让环境光随鼠标移动
        medal.addEventListener('mousemove', (e) => {
            const rect = medal.getBoundingClientRect();
            const x = e.clientX - rect.left; // 鼠标在元素内的X坐标
            const y = e.clientY - rect.top;  // 鼠标在元素内的Y坐标

            // 将坐标转换为百分比 (0%-100%)
            const xPercent = Math.round((x / rect.width) * 100);
            const yPercent = Math.round((y / rect.height) * 100);                // 更新环境光位置，光源更加明显且突出
            ambient.style.background = `radial-gradient(circle at ${xPercent}% ${yPercent}%, 
                rgba(255,246,180,0.9) 0%, 
                rgba(255,226,100,0.45) 30%, 
                rgba(255,215,0,0.2) 60%,
                rgba(255,215,0,0) 85%)`;
                
            // 添加更明显的变换效果，增强动态感
            ambient.style.transform = `scale(1.15) translate(${(xPercent-50)/20}%, ${(yPercent-50)/20}%)`;
              // 光斑随鼠标移动的自然位置调整，每个光斑以不同比例移动
            const flecks = medal.querySelectorAll('.medal-fleck');
            flecks.forEach((fleck, idx) => {
                // 每个光斑以不同的灵敏度和方向随鼠标移动，增加差异性
                const xFactor = idx === 0 ? 0.05 : 0.03;
                const yFactor = idx === 0 ? 0.02 : 0.04;
                const xMove = (xPercent - 50) * xFactor;
                const yMove = (yPercent - 50) * yFactor;
                
                // 保留原来的动画，仅添加位置偏移
                fleck.style.transform = `translate(${xMove}px, ${yMove}px)`;
            });
        });            // 鼠标离开时恢复动画，保持显著的光效
        medal.addEventListener('mouseleave', () => {
            ambient.style.background = 'radial-gradient(circle at 30% 30%, rgba(255,236,150,0.75) 0%, rgba(255,220,100,0.35) 35%, rgba(255,215,0,0.15) 65%, rgba(255,215,0,0) 80%)';
            ambient.style.transform = 'scale(1) translate(0%, 0%)';
            ambient.style.animation = 'ambient-light 9s ease-in-out infinite, ambient-color 12s ease-in-out infinite';
            
            // 恢复光斑的原始位置，保持各自独立的动画
            const flecks = medal.querySelectorAll('.medal-fleck');
            flecks.forEach(fleck => {
                fleck.style.transform = '';
            });
        });

        medal.appendChild(ambient);        // 添加随机光斑点 - 进一步减少数量，只保留关键位置
        const fleckPositions = [
            { top: '25%', left: '20%', size: '15%' },
            { top: '65%', left: '70%', size: '13%' }
        ];

        fleckPositions.forEach((pos, i) => {
            const fleck = document.createElement('div');
            fleck.className = 'medal-fleck';
            Object.assign(fleck.style, {
                position: 'absolute',
                top: pos.top,
                left: pos.left,
                width: pos.size,
                height: pos.size,                borderRadius: '50%',
                background: 'radial-gradient(ellipse at center, rgba(255,255,220,0.9) 0%, rgba(255,255,200,0.4) 40%, rgba(255,255,200,0) 100%)',
                pointerEvents: 'none',
                zIndex: '3',
                filter: 'blur(1.8px)',
                opacity: '0.75',                animation: `light-fleck-${i % 3 + 1} ${8 + i * 3}s ease-in-out infinite`,
                mixBlendMode: 'lighten',
                boxShadow: '0 0 10px rgba(255, 255, 200, 0.45)'
            });

            medal.appendChild(fleck);
        });

        // 添加金属纹理的额外样式，增加质感和光泽效果
        if (!medal.style.boxShadow) {
            medal.style.boxShadow = 'inset 0 0 20px rgba(255, 215, 0, 0.55), 0 7px 15px rgba(0, 0, 0, 0.2)';
        }

        // 添加微妙的脉动光效，模拟自然光线变化
        const pulseEffect = document.createElement('div');
        pulseEffect.className = 'medal-pulse';

        Object.assign(pulseEffect.style, {
            position: 'absolute',
            top: '-10%',
            left: '-10%',
            width: '120%',
            height: '120%',
            borderRadius: 'inherit',
            background: 'radial-gradient(circle at 35% 35%, rgba(255,236,150,0.2) 0%, rgba(255,215,0,0.1) 60%, rgba(255,215,0,0) 85%)',
            pointerEvents: 'none',
            zIndex: '1',
            mixBlendMode: 'screen',
            animation: 'pulse-animation 5s ease-in-out infinite',
            opacity: '0.8',
            filter: 'blur(4px)'
        });

        // 添加更明显的脉动动画
        try {
            styleSheet.insertRule(`
                @keyframes pulse-animation {
                    0%, 100% { transform: scale(1); opacity: 0.7; background-position: 30% 30%; }
                    25% { transform: scale(1.04); opacity: 0.8; background-position: 45% 25%; }
                    50% { transform: scale(1.06); opacity: 0.85; background-position: 50% 45%; }
                    75% { transform: scale(1.03); opacity: 0.75; background-position: 25% 50%; }
                }
            `, styleSheet.cssRules.length);
        } catch (e) {
            const style = document.createElement('style');
            style.textContent = `
                @keyframes pulse-animation {
                    0%, 100% { transform: scale(1); opacity: 0.6; background-position: 35% 35%; }
                    25% { transform: scale(1.02); opacity: 0.65; background-position: 40% 30%; }
                    50% { transform: scale(1.03); opacity: 0.7; background-position: 45% 40%; }
                    75% { transform: scale(1.01); opacity: 0.65; background-position: 30% 45%; }
                }
            `;
            document.head.appendChild(style);
        }

        medal.appendChild(pulseEffect);
    });
}

// 添加到全局初始化
if (typeof window.sakurairo_effects === 'undefined') {
    window.sakurairo_effects = {};
}
window.sakurairo_effects.medals = {
    init: initMedalEffects,
    parallax: initParallaxEffect,
    shine: addShineEffect,
    // 提供一个全局重置函数，用于PJAX环境
    reload: function() {
        this.init();
        this.parallax();
        this.shine();
    }
};
