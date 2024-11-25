<?php

/**
 * The header for our theme.
 *
 * This is the template that displays all of the <head> section and everything up until <div id="content">
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package Sakurairo
 */

// Prevent direct access to the file
if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

$nav_text_logo = iro_opt('nav_text_logo');
$vision_resource_basepath = iro_opt('vision_resource_basepath');
header('X-Frame-Options: SAMEORIGIN');
?>
<!DOCTYPE html>
<!-- 
            ◢＼　 ☆　　 ／◣
           ∕　　﹨　╰╮∕　　﹨
           ▏　　～～′′～～ 　｜
           ﹨／　　　　　　 　＼∕
           ∕ 　　●　　　 ●　＼
        ＝＝　○　∴·╰╯　∴　○　＝＝
           ╭──╮　　　　　╭──╮
  ╔═ ∪∪∪═Mashiro&Hitomi═∪∪∪═╗
-->
<html <?php language_attributes(); ?>>

<head>
    <meta name="theme-color">
    <meta charset="<?php bloginfo('charset'); ?>">
    <meta content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0" name="viewport">
    <link rel="stylesheet" href="https://s4.zstatic.net/ajax/libs/font-awesome/6.6.0/css/all.min.css" type="text/css" media="all" />
    <?php
    if (iro_opt('iro_meta')) {
        $keywords = iro_opt('iro_meta_keywords');
        $description = iro_opt('iro_meta_description');
        if (is_singular()) {
            $tags = get_the_tags();
            if ($tags) {
                $keywords = implode(',', array_column($tags, 'name'));
            }
            if (!empty($post->post_content)) {
                $description = trim(mb_strimwidth(preg_replace('/\s+/', ' ', strip_tags($post->post_content)), 0, 240, '…'));
            }
        }
        if (is_category()) {
            $categories = get_the_category();
            if ($categories) {
                $keywords = implode(',', array_column($categories, 'name'));
            }
            $description = trim(category_description()) ?: $description;
        }
    ?>
        <meta name="description" content="<?= esc_attr($description); ?>" />
        <meta name="keywords" content="<?= esc_attr($keywords); ?>" />
    <?php } ?>
    <link rel="shortcut icon" href="<?= esc_url(iro_opt('favicon_link', '')); ?>" />
    <meta http-equiv="x-dns-prefetch-control" content="on">
    <?php
    if (is_home()) {
        global $core_lib_basepath;
    ?>
        <link id="entry-content-css" rel="prefetch" as="style" href="<?= esc_url($core_lib_basepath . '/css/theme/' . (iro_opt('entry_content_style') == 'sakurairo' ? 'sakura' : 'github') . '.css?ver=' . IRO_VERSION) ?>" />
        <link rel="prefetch" as="script" href="<?= esc_url($core_lib_basepath . '/js/page.js?ver=' . IRO_VERSION) ?>" />
    <?php
    }
    ?>
    <?php wp_head(); ?>
    <link rel="alternate" type="application/rss+xml" title="<?php bloginfo('name'); ?>｜<?php bloginfo('description'); ?>" href="<?php bloginfo('rss2_url'); ?>" />
    <link rel="stylesheet" href="https://<?= esc_attr(iro_opt('gfonts_api', 'fonts.googleapis.com')); ?>/css?family=Noto+Serif+SC|Noto+Sans+SC|Dela+Gothic+One|Fira+Code<?= esc_attr(iro_opt('gfonts_add_name')); ?>&display=swap" media="all">
    <?php if (iro_opt('google_analytics_id')) : ?>
        <!-- Global site tag (gtag.js) - Google Analytics -->
        <script async src="https://www.googletagmanager.com/gtag/js?id=<?= esc_attr(iro_opt('google_analytics_id')); ?>"></script>
        <script>
            window.dataLayer = window.dataLayer || [];

            function gtag() {
                dataLayer.push(arguments)
            }
            gtag('js', new Date());
            gtag('config', '<?= esc_attr(iro_opt('google_analytics_id')); ?>');
        </script>
    <?php endif; ?>
    <?= iro_opt("site_header_insert"); ?>

    <?php if (iro_opt('poi_pjax')) {
        $script_leep_loading_list = iro_opt("pjax_keep_loading");
        if (strlen($script_leep_loading_list) > 0) :
    ?>
            <script>
                const srcs = `<?php echo iro_opt("pjax_keep_loading"); ?>`;
                document.addEventListener("pjax:complete", () => {
                    srcs.split(/[\n,]+/).forEach(path => {
                        path = path.trim();
                        if (!path) return;
                        if (path.endsWith('.js')) {
                            const script = document.createElement('script');
                            script.src = path;
                            script.async = true;
                            document.body.appendChild(script);
                        } else if (path.endsWith('.css')) {
                            const style = document.createElement('link');
                            style.rel = 'stylesheet';
                            style.href = path;
                            document.head.appendChild(style);
                        }
                    })
                });
            </script>
    <?php endif;
    } ?>

</head>

<body <?php body_class(); ?>>
    <?php if (iro_opt('preload_animation', 'true')) : ?>
        <div id="preload">
            <li data-id="3" class="active">
                <div id="preloader_3"></div>
            </li>
        </div>
    <?php endif; ?>
    <div class="scrollbar" id="bar"></div>
    <header class="site-header no-select" role="banner">
        <?php
        // Logo Section - Only process if logo or text is configured
        if (iro_opt('iro_logo') || !empty($nav_text_logo['text'])): ?>
            <div class="site-branding">
                <a href="<?= esc_url(home_url('/')); ?>">
                    <?php if (iro_opt('iro_logo')): ?>
                        <div class="site-title-logo">
                            <img alt="<?= esc_attr(get_bloginfo('name')); ?>"
                                src="<?= esc_url(iro_opt('iro_logo')); ?>"
                                width="auto" height="auto"
                                loading="lazy"
                                decoding="async">
                        </div>
                    <?php endif; ?>
                    <?php if (!empty($nav_text_logo['text'])): ?>
                        <div class="site-title">
                            <?= esc_html($nav_text_logo['text']); ?>
                        </div>
                    <?php endif; ?>
                </a>
            </div>
        <?php endif;

        // Cache commonly used options
        $show_search = (bool)iro_opt('nav_menu_search');
        $show_user_avatar = (bool)iro_opt('nav_menu_user_avatar');
        $enable_random_graphs = (bool)iro_opt('cover_switch', true) && (bool)iro_opt('cover_random_graphs_switch', true);
        ?>

        <!-- Navigation and Search Section -->
        <div class="nav-search-wrapper">
            <!-- Nav menu -->
            <div class="nav-center-space">
            <nav>
                <?php 
                wp_nav_menu([
                    'depth' => 2,
                    'theme_location' => 'primary',
                    'container' => false
                    ]); 
                ?>
            </nav>
            </div>

            <!-- Nav width control -->
            <script>
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
            </script>

            <!-- Search and background switch -->
            <?php if ($enable_random_graphs || $show_search): ?>
                <div class="nav-search-divider"></div>
            <?php endif; ?>

            <?php if ($show_search): ?>
                <div class="searchbox js-toggle-search">
                    <i class="fa-solid fa-magnifying-glass" aria-hidden="true"></i>
                    <span class="screen-reader-text">
                        <?php esc_html_e('Search', 'sakurairo'); ?>
                    </span>
                </div>
            <?php endif; ?>

            <?php if ($enable_random_graphs): ?>
                <div class="bg-switch" id="bg-next" style="display:none">
                    <i class="fa-solid fa-dice" aria-hidden="true"></i>
                    <span class="screen-reader-text">
                        <?php esc_html_e('Random Background', 'sakurairo'); ?>
                    </span>
                </div>
                
                <script>
                    // DOM and Animation constants
                    const DOM = {
                        bgNext: document.getElementById("bg-next"),
                        navSearchWrapper: document.querySelector(".nav-search-wrapper"),
                        searchbox: document.querySelector(".searchbox.js-toggle-search"),
                        divider: document.querySelector(".nav-search-divider"),
                    };

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

                    // State management
                    const StateManager = {
                        init() {
                            if (sessionStorage.getItem("bgNextState")) return this.getState();

                            // 优化首次加载状态
                            const state = {
                                lastPageWasHome: false, // 重要：首次加载时设为false以触发进入动画
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

                    // Animation methods
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

                    const handlePageTransition = (isHomePage, state) => {
                        if (isHomePage !== state.lastPageWasHome) {
                            const clone = DOM.bgNext.cloneNode(true);
                            clone.style.cssText =
                                "display:block;opacity:0;position:fixed;pointer-events:none;";
                            document.body.appendChild(clone);
                            const bgNextWidth = clone.offsetWidth;
                            document.body.removeChild(clone);

                            const initialWidth = DOM.navSearchWrapper.offsetWidth;
                            animateTransition(isHomePage, state, bgNextWidth, initialWidth);
                        } else {
                            // 处理直接状态
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

                    const animateTransition = (isEntering, state, bgNextWidth, initialWidth) => {
                        if (state.isTransitioning) return;

                        StateManager.update({
                            isTransitioning: true,
                        });

                        // 初始化元素状态
                        initElementStates(isEntering, bgNextWidth, initialWidth);

                        // 强制回流
                        [DOM.bgNext, DOM.navSearchWrapper, DOM.searchbox, DOM.divider].forEach(
                            (el) => {
                                if (el) void el.offsetWidth;
                            }
                        );

                        // 执行动画
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

                    const initElementStates = (
                        isEntering,
                        bgNextWidth,
                        initialWidth,
                        isFirstLoad = false
                    ) => {
                        // 确保元素样式重置
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

                    const animateElements = (isEntering, bgNextWidth, initialWidth) => {
                        const enteringOffset = ANIMATION.offset.entering;
                        const leavingOffset = ANIMATION.offset.leaving;
                        const dividerOffset = ANIMATION.offset.divider;

                        requestAnimationFrame(() => {
                            // 设置过渡效果
                            setTransitions();

                            // 执行动画
                            DOM.bgNext.style.opacity = isEntering ? "1" : "0";
                            DOM.bgNext.style.transform = `translateX(${isEntering ? "0" : "20px"})`;
                            DOM.navSearchWrapper.style.width = `${
                                initialWidth + (isEntering ? bgNextWidth : -bgNextWidth)
                            }px`;

                            if (!isEntering) {
                                if (DOM.searchbox) {
                                    DOM.searchbox.style.transform = `translateX(${
                                        bgNextWidth + leavingOffset
                                    }px)`;
                                }
                                if (DOM.divider) {
                                    if (!DOM.searchbox) {
                                        DOM.divider.style.opacity = "0";
                                        DOM.divider.style.transform = `translateX(${
                                            bgNextWidth + leavingOffset
                                        }px)`;
                                    } else {
                                        DOM.divider.style.transform = `translateX(${
                                            bgNextWidth + leavingOffset
                                        }px)`;
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

                    const showBgNext = () => {
                        const isHomePage =
                            location.pathname === "/" || location.pathname === "/index.php";
                        const state = StateManager.getState();

                        if (state.isTransitioning) return;

                        // 处理首次加载
                        if (state.firstLoad) {
                            if (!state.initialized) {
                                state.initialized = true;
                                StateManager.setState(state);

                                // 首页处理
                                if (isHomePage) {
                                    // 延迟执行以确保DOM完全加载
                                    setTimeout(() => {
                                        requestAnimationFrame(() => {
                                            const clone = DOM.bgNext.cloneNode(true);
                                            clone.style.cssText =
                                                "display:block;opacity:0;position:fixed;pointer-events:none;";
                                            document.body.appendChild(clone);
                                            const bgNextWidth = clone.offsetWidth;
                                            document.body.removeChild(clone);

                                            const initialWidth = DOM.navSearchWrapper.offsetWidth;

                                            // 首次加载时重置所有元素初始状态
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

                                            // 强制回流
                                            void DOM.bgNext.offsetWidth;
                                            void DOM.navSearchWrapper.offsetWidth;
                                            if (DOM.searchbox) void DOM.searchbox.offsetWidth;
                                            if (DOM.divider) void DOM.divider.offsetWidth;

                                            // 延迟一帧执行动画
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

                            // 非首页保持不变
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

                    // Event listeners
                    document.addEventListener("pjax:send", () => {
                        StateManager.update({
                            lastPageWasHome:
                                location.pathname === "/" || location.pathname === "/index.php",
                        });
                    });

                    document.addEventListener("pjax:complete", () => {
                        requestAnimationFrame(showBgNext);
                    });

                    // Initialize
                    StateManager.init();
                    showBgNext();
                    const _space =  DOM.navSearchWrapper.querySelector('.nav-center-space')
                    _space.addEventListener('transitionstart',(e)=>{
                                       e.currentTarget.style.overflow = 'hidden'
                                    })
                    _space.addEventListener('transitionend',(e)=>{
                                        e.currentTarget.style.overflow = ''
                                    })
                    // Article title behavior 

                    const initArticleTitleBehavior = () => {
                        const searchWrapperState = {
                                state: false,
                                navElement: null,
                                navTitle: null,
                                entryTitle: null,
                                titlePadding: 20,
                                scrollTimeout: null,
                                navSpace:null,
                                obs:null,
                                init() {
                                    if(this.obs){
                                        this.obs.disconnect()
                                    }
                                    this.navSpace = DOM.navSearchWrapper.querySelector('.nav-center-space')
                                    this.navTitle =
                                        DOM.navSearchWrapper.querySelector(".nav-article-title");
                                    this.entryTitle = document.querySelector(".entry-title");
                                    this.navElement = DOM.navSearchWrapper.querySelector("nav");
 
                                    if(!this.entryTitle) return
                                    if (!this.navTitle) {
                                        this.navTitle = document.createElement("div");
                                        this.navTitle.classList.add("nav-article-title");
                                        DOM.navSearchWrapper.firstElementChild.insertAdjacentElement(
                                            "beforeend",
                                            this.navTitle
                                        );
                                    }
                                    this.navTitle.textContent = this.entryTitle.textContent;
                                    this.obs = new IntersectionObserver(this.handleIntersection.bind(searchWrapperState))
                                    this.obs.observe(this.entryTitle)
                                },

                                show() {
                                    const navSearchWrapper = DOM.navSearchWrapper;

                                    const contentWidth = this.navTitle.scrollWidth;
                                        const navWidth = navSearchWrapper.querySelector("nav").offsetWidth;
                                        const deltaWidth = Math.max(0, contentWidth - navWidth);
                                        navSearchWrapper.style.setProperty("--dw", `${deltaWidth}px`);
                                        
                                        navSearchWrapper.dataset.scrollswap = "swap";
                                    this.state = true;
                                },

                                hide() {
                                    const navSearchWrapper = DOM.navSearchWrapper;
                                    navSearchWrapper.dataset.scrollswap = "";
                                    navSearchWrapper.style.setProperty("--dw", "0");
                                    this.state = false;
                                },

                                updateState() {
                                    if (this.entryTitle&&
                                    this.entryTitle.getBoundingClientRect().top < 0) {
                                            this.show();
                                        } else {
                                            this.hide();
                                        }
                                },
                                handleIntersection(entries){
                                    if(entries[0].isIntersecting){
                                        this.hide()
                                    }else{
                                        this.show()
                                    }
                                }
                            };  
                            searchWrapperState.init()

                            return searchWrapperState

                    };
                    /**
                     * @type {ReturnType<typeof initArticleTitleBehavior>}
                     */
                    let state
                    // Handle both first load and PJAX navigation
                    document.addEventListener("DOMContentLoaded", ()=>{
                        if(!_iro.land_at_home) {
                            state = initArticleTitleBehavior()
                        }
                    },{once:true});
                    document.addEventListener("pjax:complete", () => {
                                    if(!_iro.land_at_home) {
                                        if(state){
                                            state.init()
                                            state.updateState()
                                        }else{
                                            state = initArticleTitleBehavior()
                                        }
                                    }else{
                                        state?.updateState()
                                    }
                            });
                </script>
            <?php endif; ?>
        </div>

        <!-- User Menu Section -->
        <?php if ($show_user_avatar): ?>
            <div class="user-menu-wrapper">
                <?php header_user_menu(); ?>
            </div>
        <?php endif; ?>
    </header>
    <div class="openNav no-select">
        <div class="iconflat no-select" style="padding: 30px;">
            <div class="icon"></div>
        </div>
    </div><!-- m-nav-bar -->
    <section id="main-container">
        <?php
        if (iro_opt('cover_switch')) {
            $filter = iro_opt('random_graphs_filter');
        ?>
            <div class="headertop <?= esc_attr($filter); ?>">
                <?php get_template_part('layouts/imgbox'); ?>
            </div>
        <?php } ?>
        <div id="page" class="site wrapper">
            <?php
            $use_as_thumb = get_post_meta(get_the_ID(), 'use_as_thumb', true); //'true','only',(default)
            if ($use_as_thumb != 'only') {
                $cover_type = get_post_meta(get_the_ID(), 'cover_type', true);
                if ($cover_type == 'hls') {
                    the_video_headPattern(true);
                } elseif ($cover_type == 'normal') {
                    the_video_headPattern(false);
                } else {
                    the_headPattern();
                }
            } else {
                the_headPattern();
            } ?>
            <div id="content" class="site-content">