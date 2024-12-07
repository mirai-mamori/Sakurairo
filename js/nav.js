// 导航栏长度限制
    function initNavWidth() {
        const nav = document.querySelector('nav');
        const checkWidth = () => {
            if (nav.offsetWidth > 1200) {
                nav.style.overflowX = 'hidden';
                nav.style.maxWidth = '1200px';
            } else {
                nav.style.overflowX = '';
                nav.style.maxWidth = '';
            }
        };
        checkWidth();
        window.addEventListener('resize', checkWidth);
    }

    document.addEventListener('DOMContentLoaded', initNavWidth);
    document.addEventListener('pjax:complete', initNavWidth);

// 定义DOM元素
const DOM = {
    bgNext: document.getElementById("bg-next"),
    navSearchWrapper: document.querySelector(".nav-search-wrapper"),
    searchbox: document.querySelector(".searchbox.js-toggle-search"),
    divider: document.querySelector(".nav-search-divider"),
};

// 定义动画参数
const ANIMATION = {
    easing: "cubic-bezier(0.34, 1.56, 0.64, 1)",
    duration: "0.6s",
    durationMs: 600,
    offset: {
        entering: 6.5,
        leaving: 3.5,
        divider: 4,
    },
};

// 状态管理器
const StateManager = {
    init() {
        if (sessionStorage.getItem("bgNextState")) return this.getState();

        const state = {
            lastPageWasHome: false,
            isTransitioning: false,
            firstLoad: true,
            initialized: false,
        };
        this.setState(state);
        return state;
    },

    getState() {
        return JSON.parse(sessionStorage.getItem("bgNextState"));
    },

    setState(state) {
        sessionStorage.setItem("bgNextState", JSON.stringify(state));
    },

    update(changes) {
        this.setState({
            ...this.getState(),
            ...changes,
        });
    },
};

// 设置动画过渡
const setTransitions = () => {
    DOM.bgNext.style.transition = `all ${ANIMATION.duration} ${ANIMATION.easing}`;
    DOM.navSearchWrapper.style.transition = `all ${ANIMATION.duration} ${ANIMATION.easing}`;

    if (DOM.searchbox) {
        DOM.searchbox.style.transition = `transform ${ANIMATION.duration} ${ANIMATION.easing}`;
    }

    if (DOM.divider) {
        DOM.divider.style.transition = !DOM.searchbox
            ? `all ${ANIMATION.duration} ${ANIMATION.easing}`
            : `transform ${ANIMATION.duration} ${ANIMATION.easing}`;
    }
};

// 初始化元素状态
const initElementStates = (isEntering, bgNextWidth, initialWidth, isFirstLoad = false) => {
    DOM.navSearchWrapper.style.width = initialWidth + "px";
    DOM.bgNext.style.cssText = `
        display: block;
        opacity: ${isEntering ? "0" : "1"};
        transform: translateX(${isEntering ? "20px" : "0"});
        transition: none;
    `;

    if (!DOM.searchbox && DOM.divider) {
        if (isEntering && !isFirstLoad) {
            DOM.divider.style.cssText = `
                display: block;
                opacity: 0;
                transform: translateX(${isEntering ? "20px" : "0"});
                transition: none;
            `;
        }
    }

    if (isEntering && !isFirstLoad) {
        setInitialPositions(bgNextWidth);
    }
};

// 设置初始位置
const setInitialPositions = (bgNextWidth) => {
    const offset = ANIMATION.offset.entering;
    if (DOM.searchbox) {
        DOM.searchbox.style.cssText = `
            transform: translateX(${bgNextWidth + offset}px);
            transition: none;
        `;
    }
    if (DOM.divider) {
        if (!DOM.searchbox) {
            DOM.divider.style.cssText = `
                display: block;
                opacity: 0;
                transform: translateX(${bgNextWidth + offset}px);
                transition: none;
            `;
        } else {
            DOM.divider.style.cssText = `
                transform: translateX(${bgNextWidth + offset}px);
                transition: none;
            `;
        }
    }
};

// 执行动画
const animateElements = (isEntering, bgNextWidth, initialWidth) => {
    const enteringOffset = ANIMATION.offset.entering;
    const leavingOffset = ANIMATION.offset.leaving;
    const dividerOffset = ANIMATION.offset.divider;

    requestAnimationFrame(() => {
        setTransitions();

        DOM.bgNext.style.opacity = isEntering ? "1" : "0";
        DOM.bgNext.style.transform = `translateX(${isEntering ? "0" : "20px"})`;
        DOM.navSearchWrapper.style.width = `${
            initialWidth + (isEntering ? bgNextWidth : -bgNextWidth)
        }px`;

        if (!isEntering) {
            if (DOM.searchbox) {
                DOM.searchbox.style.transform = `translateX(${bgNextWidth + leavingOffset}px)`;
            }
            if (DOM.divider) {
                if (!DOM.searchbox) {
                    DOM.divider.style.opacity = "0";
                    DOM.divider.style.transform = `translateX(${bgNextWidth + leavingOffset}px)`;
                } else {
                    DOM.divider.style.transform = `translateX(${bgNextWidth + leavingOffset}px)`;
                }
            }
        } else {
            if (DOM.searchbox) {
                DOM.searchbox.style.transform = "translateX(0)";
            }
            if (DOM.divider) {
                if (!DOM.searchbox) {
                    DOM.divider.style.opacity = "1";
                    DOM.divider.style.transform = `translateX(${dividerOffset}px)`;
                } else {
                    DOM.divider.style.transform = "translateX(0)";
                }
            }
        }
    });
};

// 页面过渡处理
const handlePageTransition = (isHomePage, state) => {
    if (isHomePage !== state.lastPageWasHome) {
        // 创建一个测量容器
        const measureContainer = document.createElement('div');
        measureContainer.style.cssText = `
            position: fixed;
            visibility: hidden;
            pointer-events: none;
            left: -9999px;
            top: 0;
        `;
        document.body.appendChild(measureContainer);

        // 克隆并准备bg-next元素用于测量
        const clone = DOM.bgNext.cloneNode(true);
        clone.style.cssText = `
            display: block;
            opacity: 0;
            position: static;
            margin: ${window.getComputedStyle(DOM.bgNext).margin};
            padding: ${window.getComputedStyle(DOM.bgNext).padding};
            border: ${window.getComputedStyle(DOM.bgNext).border};
            box-sizing: border-box;
        `;
        measureContainer.appendChild(clone);

        // 使用 getBoundingClientRect 获取精确宽度
        const bgNextWidth = Math.ceil(clone.getBoundingClientRect().width);
        document.body.removeChild(measureContainer);

        // 获取初始宽度时也使用 getBoundingClientRect
        const initialWidth = Math.ceil(DOM.navSearchWrapper.getBoundingClientRect().width);
        
        animateTransition(isHomePage, state, bgNextWidth, initialWidth);
    } else {
        DOM.bgNext.style.display = isHomePage ? "block" : "none";
        if (!isHomePage && !DOM.searchbox && DOM.divider) {
            DOM.divider.style.display = "none";
        }
        if (isHomePage) {
            DOM.bgNext.style.opacity = "1";
            DOM.bgNext.style.transform = "translateX(0)";
        }
    }

    state.lastPageWasHome = isHomePage;
    StateManager.setState(state);
};

// 执行过渡动画
const animateTransition = (isEntering, state, bgNextWidth, initialWidth) => {
    if (state.isTransitioning) return;

    StateManager.update({
        isTransitioning: true,
    });

    initElementStates(isEntering, bgNextWidth, initialWidth);

    [DOM.bgNext, DOM.navSearchWrapper, DOM.searchbox, DOM.divider].forEach((el) => {
        if (el) void el.offsetWidth;
    });

    requestAnimationFrame(() => {
        setTransitions();
        animateElements(isEntering, bgNextWidth, initialWidth);

        setTimeout(() => {
            if (!isEntering) {
                DOM.bgNext.style.display = "none";
                DOM.navSearchWrapper.style.width = "auto";
                if (!DOM.searchbox && DOM.divider) {
                    DOM.divider.style.display = "none";
                }
                [DOM.searchbox, DOM.divider].forEach((el) => {
                    if (el) {
                        el.style.transition = "none";
                        el.style.transform = "";
                    }
                });
            }
            StateManager.update({
                isTransitioning: false,
            });
        }, ANIMATION.durationMs);
    });
};

// 显示或隐藏bgNext元素
const showBgNext = () => {
    const isHomePage =
        location.pathname === "/" || location.pathname === "/index.php";
    const state = StateManager.getState();

    if (state.isTransitioning) return;

    if (state.firstLoad) {
        if (!state.initialized) {
            state.initialized = true;
            StateManager.setState(state);

            if (isHomePage) {
                setTimeout(() => {
                    requestAnimationFrame(() => {
                        const clone = DOM.bgNext.cloneNode(true);
                        clone.style.cssText =
                            "display:block;opacity:0;position:fixed;pointer-events:none;";
                        document.body.appendChild(clone);
                        const bgNextWidth = clone.offsetWidth;
                        document.body.removeChild(clone);

                        const initialWidth = DOM.navSearchWrapper.offsetWidth;

                        DOM.bgNext.style.cssText = `
                            display: block;
                            opacity: 0;
                            transform: translateX(20px);
                            transition: none;
                        `;

                        if (DOM.searchbox) {
                            DOM.searchbox.style.cssText = `
                                transform: translateX(${bgNextWidth + ANIMATION.offset.entering}px);
                                transition: none;
                            `;
                        }

                        if (DOM.divider) {
                            DOM.divider.style.cssText = `
                                transform: translateX(${bgNextWidth + ANIMATION.offset.entering}px);
                                transition: none;
                                ${!DOM.searchbox ? "opacity: 0;" : ""}
                            `;
                        }

                        DOM.navSearchWrapper.style.width = initialWidth + "px";

                        void DOM.bgNext.offsetWidth;
                        void DOM.navSearchWrapper.offsetWidth;
                        if (DOM.searchbox) void DOM.searchbox.offsetWidth;
                        if (DOM.divider) void DOM.divider.offsetWidth;

                        requestAnimationFrame(() => {
                            state.firstLoad = false;
                            StateManager.setState(state);
                            animateElements(true, bgNextWidth, initialWidth);
                        });
                    });
                }, 100);
                return;
            }
        }

        state.firstLoad = false;
        StateManager.setState(state);

        if (!isHomePage) {
            DOM.bgNext.style.display = "none";
            if (!DOM.searchbox && DOM.divider) {
                DOM.divider.style.display = "none";
            }
        }
        return;
    }

    handlePageTransition(isHomePage, state);
};

// 初始化文章标题行为
const initArticleTitleBehavior = () => {
    DOM.navSearchWrapper.style.overflow = "unset";

    if (window._searchWrapperState) {
        const navTitle = DOM.navSearchWrapper.querySelector(".nav-article-title");
        if (navTitle) {
            navTitle.remove();
        }
        delete DOM.navSearchWrapper.dataset.scrollswap;
        DOM.navSearchWrapper.style.setProperty("--dw", "0");
        window._searchWrapperState = null;
    }

    if (!_iro.land_at_home) {
        const searchWrapperState = {
            state: false,
            navElement: null,
            navTitle: null,
            entryTitle: null,
            titlePadding: 20,
            scrollTimeout: null,
            hideTimeout: null,
            headerElement: null,

            init() {
                this.navTitle = DOM.navSearchWrapper.querySelector(".nav-article-title");
                this.entryTitle = document.querySelector(".entry-title");
                this.navElement = DOM.navSearchWrapper.querySelector("nav");
                this.header = document.querySelector("header");

                if (!this.navTitle) {
                    this.navTitle = document.createElement("div");
                    this.navTitle.classList.add("nav-article-title");
                    this.navTitle.style.opacity = "0";
                    DOM.navSearchWrapper.firstElementChild.insertAdjacentElement(
                        "afterend",
                        this.navTitle
                    );

                    this.header.addEventListener("mouseenter", () => {
                        if (this.hideTimeout) {
                            clearTimeout(this.hideTimeout);
                            this.hideTimeout = null;
                        }
                        if (this.entryTitle && this.entryTitle.getBoundingClientRect().top < 0) {
                            this.hide();
                        }
                    });

                    this.header.addEventListener("mouseleave", () => {
                        if (this.hideTimeout) {
                            clearTimeout(this.hideTimeout);
                        }
                        if (this.entryTitle && this.entryTitle.getBoundingClientRect().top < 0) {
                            this.hideTimeout = setTimeout(() => {
                                this.show();
                                this.hideTimeout = null;
                            }, 3000);
                        }
                    });

                    this.navElement.addEventListener("transitionend", (event) => {
                        if (event.target !== this.navElement && event.target !== this.header) return;
                        this.navTitle.style.opacity = window.getComputedStyle(this.navElement).transform == "none" ? "0" : "1";
                        if (document.querySelector(".entry-title")) {
                            DOM.navSearchWrapper.style.overflow = window.getComputedStyle(this.navElement).transform === "none" ? "unset" : "hidden";
                        }
                    });

                    this.navElement.addEventListener("transitionstart", (event) => {
                        if (event.target !== this.navElement && event.target !== this.header) return;
                        if (document.querySelector(".entry-title")) {
                            DOM.navSearchWrapper.style.overflow = "hidden";
                        }
                        this.navTitle.style.opacity = "1";
                    });
                }
                this.updateTitle();
            },

            updateTitle() {
                if (this.entryTitle) {
                    this.navTitle.textContent = this.entryTitle.textContent;
                    this.navTitle.style.display = "block";
                } else {
                    this.navTitle.style.display = "none";
                }
            },

            show() {
                if (this.state || !this.entryTitle) return;
                const navSearchWrapper = DOM.navSearchWrapper;
                navSearchWrapper.dataset.scrollswap = "true";

                requestAnimationFrame(() => {
                    const tempNav = document.createElement('div');
                    tempNav.style.cssText = `
                        position: absolute;
                        visibility: hidden;
                        white-space: nowrap;
                        display: flex;
                        align-items: center;
                    `;
                    document.body.appendChild(tempNav);

                    const menuItems = Array.from(this.navElement.children);
                    menuItems.forEach(item => {
                        const clone = item.cloneNode(true);
                        const computedStyle = window.getComputedStyle(item);
                        clone.style.cssText = Array.from(computedStyle).reduce((str, prop) => {
                            return `${str}${prop}:${computedStyle.getPropertyValue(prop)};`;
                        }, '');
                        tempNav.appendChild(clone);
                    });
                    
                    const actualNavWidth = tempNav.scrollWidth;
                    
                    const tempTitle = document.createElement('div');
                    tempTitle.style.cssText = `
                        position: absolute;
                        visibility: hidden;
                        white-space: nowrap;
                        font-size: ${window.getComputedStyle(this.navTitle).fontSize};
                        font-family: ${window.getComputedStyle(this.navTitle).fontFamily};
                        font-weight: ${window.getComputedStyle(this.navTitle).fontWeight};
                    `;
                    tempTitle.textContent = this.navTitle.textContent;
                    document.body.appendChild(tempTitle);
                    
                    const actualTitleWidth = tempTitle.scrollWidth;

                    const compensationSpace = 18;
                    const deltaWidth = actualTitleWidth - actualNavWidth + compensationSpace;
                    
                    navSearchWrapper.style.setProperty("--dw", `${deltaWidth}px`);

                    document.body.removeChild(tempNav);
                    document.body.removeChild(tempTitle);
                });
                this.state = true;
            },

            hide() {
                if (!this.state) return;
                const navSearchWrapper = DOM.navSearchWrapper;
                delete navSearchWrapper.dataset.scrollswap;
                navSearchWrapper.style.setProperty("--dw", "0");
                if (document.querySelector(".entry-title")) {
                    navSearchWrapper.style.overflow = "unset";
                }
                this.state = false;
            },

            handleScroll() {
                if (this.scrollTimeout) {
                    clearTimeout(this.scrollTimeout);
                }
                this.scrollTimeout = setTimeout(() => {
                    if (
                        this.entryTitle &&
                        this.entryTitle.getBoundingClientRect().top < 0
                    ) {
                        this.show();
                    } else {
                        this.hide();
                    }
                }, 20);
            },
        };

        searchWrapperState.init();

        const scrollHandler = () => searchWrapperState.handleScroll();
        window.addEventListener("scroll", scrollHandler, { passive: true });
        window.addEventListener("resize", () => searchWrapperState.show(), {
            passive: true,
        });

        scrollHandler();

        window._searchWrapperState = searchWrapperState;
    } else {
        requestAnimationFrame(() => {
            DOM.navSearchWrapper.style.overflow = "unset";
        });
    }
};

// 初始化所有动画
const initAnimations = () => {
    StateManager.init();
    showBgNext();
    initArticleTitleBehavior();
};

// 事件监听
document.addEventListener("pjax:send", () => {
    StateManager.update({
        lastPageWasHome:
            location.pathname === "/" || location.pathname === "/index.php",
    });
});

document.addEventListener("pjax:complete", () => {
    requestAnimationFrame(() => {
        showBgNext();
        if (window._searchWrapperState) {
            window._searchWrapperState.init();
            window._searchWrapperState.handleScroll();
        } else {
            initArticleTitleBehavior();
        }
    });
});

// DOM内容加载完成后初始化动画
document.addEventListener("DOMContentLoaded", initAnimations);