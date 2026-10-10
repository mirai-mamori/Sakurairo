<?php
include(get_template_directory().'/layouts/all_opt.php');
$text_logo = iro_opt('text_logo');
if (iro_opt('social_display_icon', '') === 'display_icon/remix_iconfont'): ?>
    <link rel="stylesheet" href="<?=iro_opt('vision_resource_basepath'); ?>display_icon/remix_iconfont/remix_social.css">
<?php endif;
$print_social_zone = function() use ($all_opt): void {

    // 所有社交图标
    $social_icons_array = [];

    // 具有 Inner 的社交图标
    $social_icons_with_inner = [];

    // 微信
    $wechat_qrcode_switch = iro_opt('wechat_qrcode_switch');
    $wechat_qrcode = iro_opt('wechat_qrcode');
    $wechat_id = iro_opt('wechat_id');
    $wechat_copy_switch = iro_opt('wechat_copy_switch');
    $wechat_url = iro_opt('wechat_url');

    // QQ
    $qq_qrcode_switch = iro_opt('qq_qrcode_switch');
    $qq_qrcode = iro_opt('qq_qrcode');
    $qq_id = iro_opt('qq_id');
    $qq_copy_switch = iro_opt('qq_copy_switch');
    $qq_url = iro_opt('qq_url');

    // 处理微信
    if ($wechat_qrcode_switch && $wechat_qrcode) {
        $social_icons_with_inner[] = [
            'icon' => 'wechat',
            'inner' => '<img src="' . esc_url($wechat_qrcode) . '" alt="WeChat QR Code">',
            'action' => $wechat_copy_switch && $wechat_id ? 'copyWeChatID(event, this)' : ($wechat_url ? "redirectToURL(event, '" . esc_url($wechat_url) . "')" : 'doNothing(event)'),
        ];
    } elseif (!$wechat_qrcode_switch && $wechat_id) {
        $social_icons_with_inner[] = [
            'icon' => 'wechat',
            'inner' => '<span>' . esc_html($wechat_id) . '</span>',
            'action' => $wechat_copy_switch ? 'copyWeChatID(event, this)' : ($wechat_url ? "redirectToURL(event, '" . esc_url($wechat_url) . "')" : 'doNothing(event)'),
        ];
    }

    // 处理 QQ
    if ($qq_qrcode_switch && $qq_qrcode) {
        $social_icons_with_inner[] = [
            'icon' => 'qq',
            'inner' => '<img src="' . esc_url($qq_qrcode) . '" alt="QQ QR Code">',
            'action' => $qq_copy_switch && $qq_id ? 'copyQQID(event, this)' : ($qq_url ? "redirectToURL(event, '" . esc_url($qq_url) . "')" : 'doNothing(event)'),
        ];
    } elseif (!$qq_qrcode_switch && $qq_id) {
        $social_icons_with_inner[] = [
            'icon' => 'qq',
            'inner' => '<span>' . esc_html($qq_id) . '</span>',
            'action' => $qq_copy_switch ? 'copyQQID(event, this)' : ($qq_url ? "redirectToURL(event, '" . esc_url($qq_url) . "')" : 'doNothing(event)'),
        ];
    }

    if (!empty($social_icons_with_inner)):
        foreach ($social_icons_with_inner as $icon_data):
            ob_start(); ?>
            <li class="socialIconWithInner">
                <a href="javascript:void(0);" title="<?= esc_attr(strtolower($icon_data['icon'])) ?>" onclick="<?= esc_attr($icon_data['action']) ?>">
                    <?php if (iro_opt('social_display_icon') === 'display_icon/remix_iconfont'): ?>
                        <i class="remix_social icon-<?= esc_attr($icon_data['icon']) ?>"></i>
                    <?php else: ?>
                        <img loading="lazy" src="<?= esc_url(iro_opt('vision_resource_basepath') . iro_opt('social_display_icon') . '/' . $icon_data['icon'] . '.webp') ?>" />
                    <?php endif; ?>
                </a>
            <div class="inner">
                <?= wp_kses_post($icon_data['inner']) ?>
            </div>
            </li>
        <?php
        $social_icons_array[] = ob_get_clean();
        endforeach;
    endif;

    // 从 all_opt.php 引入其余设置并输出图标
    foreach ($all_opt as $key => $value):
        if (!empty($value['link'])):
            $img_url = $value['img'] ?? (iro_opt('vision_resource_basepath').iro_opt('social_display_icon').'/' . ($value['icon'] ?? $key) . '.webp');
            $title = $value['title'] ?? $key;
            ob_start(); ?>
            <li><a href="<?= esc_url($value['link']); ?>" target="_blank" class="social-<?= esc_attr($value['class'] ?? $key) ?>" title="<?= esc_attr($title) ?>">
                <?php if (iro_opt('social_display_icon') === 'display_icon/remix_iconfont'): ?>
                    <i class="remix_social icon-<?= $key ?>"></i>
                <?php else: ?>
                    <img alt="<?= esc_attr($title) ?>" loading="lazy" src="<?= esc_url($img_url) ?>" />
                <?php endif; ?>
            </a></li>
        <?php
        $social_icons_array[] = ob_get_clean();
        endif;
    endforeach;

    // 输出邮箱图标
    if (iro_opt('email_name') && iro_opt('email_domain')):
    ob_start(); ?>
        <li><a onclick="mail_me()" class="social-wangyiyun" title="E-mail">
            <?php if (iro_opt('social_display_icon') === 'display_icon/remix_iconfont'): ?>
                <i class="remix_social icon-mail"></i>
            <?php else: ?>
                <img loading="lazy" alt="E-mail" src="<?php echo esc_url(iro_opt('vision_resource_basepath').iro_opt('social_display_icon').'/' . 'mail.webp'); ?>" />
            <?php endif; ?>
        </a></li>
    <?php
    $social_icons_array[] = ob_get_clean();
    endif;

    // 输出自定义社交网络图标
    $diysocialicons = iro_opt('diysocialicons');
    $diysocialicons = is_array($diysocialicons) ? $diysocialicons : [];
    foreach ($diysocialicons as $key => $item) { 
        if (!is_array($item)) {
            continue; 
        }
        $link = isset($item['link']) ? $item['link'] : '#';
        $title = isset($item['title']) ? $item['title'] : '';
        $img = isset($item['img']) ? $item['img'] : '';
    ob_start(); ?>
        <li><a href="<?= $link ?>" target="_blank" title="<?= $title ?>"><img alt="<?= $title ?>" loading="lazy" src="<?= $img ?>" /></a></li>
    <?php
        $social_icons_array[] = ob_get_clean();
    }
						  
    // 转换数组备用
    $social_icons_json = json_encode($social_icons_array, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE); 

    if (iro_opt('infor_bar_style') === 'v1') {
        // 如果是 v1 样式，则直接输出
        foreach ($social_icons_array as $icon) {
            echo $icon . "\n";
        }
    } else { ?>
        <!-- 社交图标栏 -->
        <div class="slider-container enough-space">
            <div class="prev">
                <i class="fa-solid fa-chevron-left"></i>
            </div>
            <div class="slider-wrapper">
                <ul class="slider"></ul>
            </div>
            <div class="next">
                <i class="fa-solid fa-chevron-right"></i>
            </div>
        </div>
        <script>
            function initTopSocialSlider() {
                const slider = document.querySelector(".slider");
                const sliderWrapper = document.querySelector(".slider-wrapper");
                const sliderContainer = document.querySelector(".slider-container");
                const prevBtn = document.querySelector(".prev");
                const nextBtn = document.querySelector(".next");

                if (!slider || !sliderWrapper || !sliderContainer || !prevBtn || !nextBtn) {
                    return;
                }

                if (sliderContainer.dataset.initialized === 'true') {
                    return;
                }
                sliderContainer.dataset.initialized = 'true';

                let items = <?= $social_icons_json ?>.map(html => {
                    let temp = document.createElement("div");
                    temp.innerHTML = html.trim();
                    return temp.firstChild;
                });
                let currentIndex = 0;
                const itemWidth = 35;
                const gap = 10;
                const itemTotalWidth = itemWidth + gap;
                let direction = "right";
                let cachedVisibleCount = null;
                let cachedButtonState = null;

                function calculateVisibleCount() {
                    const availableWidth = sliderContainer.clientWidth - prevBtn.offsetWidth - nextBtn.offsetWidth;
                    let count = Math.floor((availableWidth - 2.5 * gap) / itemTotalWidth);
                    return Math.max(1, Math.min(count, items.length));
                }

                function updateButtonVisibility(visibleCount) {
                    const shouldShowButtons = visibleCount < items.length;

                    if (shouldShowButtons) {
                        sliderContainer.classList.remove('enough-space');
                        sliderContainer.classList.add('not-enough-space');
                    } else {
                        sliderContainer.classList.add('enough-space');
                        sliderContainer.classList.remove('not-enough-space');
                    }

                    cachedButtonState = shouldShowButtons;
                }

                function renderSlider(visibleCount) {
                    slider.innerHTML = "";

                    const wrapperWidth = visibleCount * itemWidth + (visibleCount + 1) * gap;
                    sliderWrapper.style.width = wrapperWidth + 'px';

                    for (let i = currentIndex; i < currentIndex + visibleCount && i < items.length; i++) {
                        let li = items[i];
                        li.classList.remove("fade-in-left", "fade-in-right");

                        if (direction === "left") {
                            li.classList.add("fade-in-left");
                        } else {
                            li.classList.add("fade-in-right");
                        }

                        slider.appendChild(li);
                    }
                }

                function updateSlider() {
                    const visibleCount = calculateVisibleCount();
                    renderSlider(visibleCount);
                    updateButtonVisibility(visibleCount);
                    cachedVisibleCount = visibleCount;
                }

                function handleResize() {
                    const newVisibleCount = calculateVisibleCount();

                    if (newVisibleCount !== cachedVisibleCount) {
                        currentIndex = 0;
                        renderSlider(newVisibleCount);
                        cachedVisibleCount = newVisibleCount;
                    }

                    updateButtonVisibility(newVisibleCount);
                }

                prevBtn.addEventListener("click", () => {
                    if (currentIndex - cachedVisibleCount >= 0) {
                        currentIndex -= cachedVisibleCount;
                    } else {
                        currentIndex = Math.floor((items.length - 1) / cachedVisibleCount) * cachedVisibleCount;
                    }
                    direction = "left";
                    renderSlider(cachedVisibleCount);
                });

                nextBtn.addEventListener("click", () => {
                    if (currentIndex + cachedVisibleCount < items.length) {
                        currentIndex += cachedVisibleCount;
                    } else {
                        currentIndex = 0;
                    }
                    direction = "right";
                    renderSlider(cachedVisibleCount);
                });

                window.addEventListener("resize", handleResize);

                requestAnimationFrame(() => {
                    requestAnimationFrame(() => {
                        updateSlider();
                    });
                });
            }

            document.addEventListener("DOMContentLoaded", initTopSocialSlider);
            document.addEventListener("pjax:complete", () => {
                const sliderContainer = document.querySelector(".slider-container");
                if (sliderContainer) {
                    delete sliderContainer.dataset.initialized;
                }

                requestAnimationFrame(() => {
                    requestAnimationFrame(() => {
                        initTopSocialSlider();
                    });
                });
            });
        </script>
    <?php
    }
?>
	<script>
		// 复制微信号，基于 copyID 的封装函数
		function copyWeChatID(event, element) {
			copyID(event, element, "<?= esc_js($wechat_id) ?>", "WeChat ID");
		}

		// 复制 QQ 号，基于 copyID 的封装函数
		function copyQQID(event, element) {
			copyID(event, element, "<?= esc_js($qq_id) ?>", "QQ ID");
		}

		// 复制通用函数
		function copyID(event, element, id, label) {
			event.preventDefault();
			if (!id) {
				alert(label + " is not set!");
				return;
			}

			navigator.clipboard.writeText(id).then(function () {
				var parentElement = element.closest('.socialIconWithInner');
				var existingCopiedMessage = parentElement.querySelector('.copied-message');
				if (existingCopiedMessage) {
					existingCopiedMessage.remove(); // 移除先前可能存在的复制消息
				}

				var inner = parentElement.querySelector('.inner');
				if (inner) {
					inner.style.display = 'none'; // 先隐藏原有 Inner
				}

				var copiedMessage = document.createElement("div");
				copiedMessage.className = "inner copied-message";
				copiedMessage.innerHTML = '<span>Copied!</span>';
				parentElement.appendChild(copiedMessage);
				setTimeout(function () {
					copiedMessage.remove();
					if (inner) {
						inner.style.display = '';
					}
				}, 2000); // 显示已复制的提示消息两秒，随后移除并显示原有 Inner
			}).catch(function (err) {
				console.error("Failed to copy: ", err);
				alert("Failed to copy " + label + ".");
			});
		}

		// 在新标签页打开链接
		function redirectToURL(event, url) {
			event.preventDefault();
			if (url) {
				window.open(url, '_blank');
			}
		}

		// 什么也不做
		function doNothing(event) {
			event.preventDefault();
		}
	</script>
<?php
}
?>

<div id="banner_wave_1"></div>
<div id="banner_wave_2"></div>
<figure id="centerbg" class="centerbg" style="<?php echo iro_opt('cover_full_screen',true) ? "height: 100vh;" : ''; ?>">
    <?php if (iro_opt('infor_bar')) { ?>
        <div class="focusinfo">
            <?php if (isset($text_logo['text']) && iro_opt('text_logo_options', 'true')) : ?>
                <h1 class="center-text glitch is-glitching Ubuntu-font" data-text="<?=$text_logo['text']; ?>">
                    <?php echo $text_logo['text']; ?></h1>
            <?php else : ?>
                <div class="header-tou"><a href="<?php bloginfo('url'); ?>"><img alt="avatar" src="<?=iro_opt('personal_avatar', '') ?: iro_opt('vision_resource_basepath','https://s.nmxc.ltd/sakurairo_vision/@3.0/').'series/avatar.webp'?>"></a>
            </div>
            <?php endif; ?>
                <div class="header-info">
                    <!-- 首页一言打字效果 -->
                    <?php if (iro_opt('signature_typing', 'true')) : ?>
                    <?php if (iro_opt('signature_typing_marks', 'true')) : ?><i class="fa-solid fa-quote-left"></i><?php endif; ?>
                    <span class="element" style="text-align: center; max-width: 70%;"><?=iro_opt('signature_typing_placeholder','疯狂造句中......')?></span>
                    <?php if (iro_opt('signature_typing_marks', 'true')) : ?><i class="fa-solid fa-quote-right"></i><?php endif; ?>
                    <span class="element"></span>
                    <script type="application/json" id="typed-js-initial">
                    <?= iro_opt('signature_typing_json', ''); ?>
                    </script>
                    <?php endif; ?>
                    <p><?php echo esc_html(iro_opt('signature_text', 'Hi, Mashiro?')); ?></p>
                    <?php if (iro_opt('infor_bar_style') === 'v2') : ?>
                        <div class="top-social_v2">
                            <?php $print_social_zone(); ?>
                        </div>
                    <?php endif; ?>
                </div>               

            <?php if (iro_opt('infor_bar_style') === 'v1') : ?>
                <div class="top-social">
                    <?php $print_social_zone(); ?>
                </div>
            <?php endif; ?>

        </div>
    <?php } ?>
</figure>
<?php
echo bgvideo();
?>
<!-- 首页下拉箭头 -->
<?php if (iro_opt('drop_down_arrow', 'true')) : ?>
<div class="headertop-down" onclick="headertop_down()"><span><svg t="1682342753354" class="homepage-downicon" viewBox="0 0 1843 1024" version="1.1" xmlns="http://www.w3.org/2000/svg" p-id="21355" width="80px" height="80px"><path d="M1221.06136021 284.43250057a100.69380037 100.69380037 0 0 1 130.90169466 153.0543795l-352.4275638 302.08090944a100.69380037 100.69380037 0 0 1-130.90169467 0L516.20574044 437.48688007A100.69380037 100.69380037 0 0 1 647.10792676 284.43250057L934.08439763 530.52766665l286.97696258-246.09516608z" fill="<?php echo iro_opt('drop_down_arrow_color'); ?>" p-id="21356"></path></svg></span></div>
<?php endif; ?>
