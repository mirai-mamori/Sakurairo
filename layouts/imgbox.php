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
                <a href="javascript:void(0);" title="<?= strtolower($icon_data['icon']) ?>" onclick="<?= $icon_data['action'] ?>">
                    <?php if (iro_opt('social_display_icon') === 'display_icon/remix_iconfont'): ?>
                        <i class="remix_social icon-<?= esc_attr($icon_data['icon']) ?>"></i>
                    <?php else: ?>
                        <img loading="lazy" src="<?= iro_opt('vision_resource_basepath') . iro_opt('social_display_icon') . '/' . esc_attr($icon_data['icon']) . '.webp' ?>" />
                    <?php endif; ?>
                </a>
            <div class="inner">
                <?= $icon_data['inner'] ?>
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
            <li><a href="<?= $value['link']; ?>" target="_blank" class="social-<?= $value['class'] ?? $key ?>" title="<?= $title ?>">
                <?php if (iro_opt('social_display_icon') === 'display_icon/remix_iconfont'): ?>
                    <i class="remix_social icon-<?= $key ?>"></i>
                <?php else: ?>
                    <img alt="<?= $title ?>" loading="lazy" src="<?= $img_url ?>" />
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
                <img loading="lazy" alt="E-mail" src="<?php echo iro_opt('vision_resource_basepath').iro_opt('social_display_icon').'/' . 'mail.webp'; ?>" />
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
        <div class="slider-container">
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
            document.addEventListener("DOMContentLoaded", () => {
                const slider = document.querySelector(".slider");
                const sliderWrapper = document.querySelector(".slider-wrapper");
                const sliderContainer = document.querySelector(".slider-container");
                const prevBtn = document.querySelector(".prev");
                const nextBtn = document.querySelector(".next");
                let items = <?= $social_icons_json ?>.map(html => {
                    let temp = document.createElement("div");
                    temp.innerHTML = html.trim();
                    return temp.firstChild;
                }); // 取得所有社交图标
                let sliderWidth, a, currentIndex = 0;
                const itemWidth = 35; // 单个图标宽度
                const gap = 10; // 图标间距
                const itemTotalWidth = itemWidth + gap; // 图标与间距的总宽度
                let direction = "right";

                // 更新视图，显示 items[currentIndex] 到 items[currentIndex + a]
                function updateSlider() {
                    slider.innerHTML = ""; // 清空现有 li
                    sliderWidth = sliderWrapper.clientWidth;
                    a = Math.floor((sliderWidth - 35) / itemTotalWidth); // 计算 a
                    a = Math.max(1, Math.min(a, items.length)); // a 至少为 1 且不超过总图标数

                    // 只添加 currentIndex 开始的 a 个元素
                    for (let i = currentIndex; i < currentIndex + a && i < items.length; i++) {
                        let li = items[i]; // 获取现有的图标
                        li.classList.remove("fade-in-left", "fade-in-right"); // 先移除可能存在的动画类
            
                        // 根据方向应用动画
                        if (direction === "left") {
                            li.classList.add("fade-in-left");
                        } else {
                            li.classList.add("fade-in-right");
                        }

                        slider.appendChild(li);
                    }
                }

                function checkSliderButtonsVisibility() {
                    if (a >= items.length) {
                        // 如果一次能显示的图标数量大于等于总数量，隐藏按钮
                        sliderContainer.classList.add('enough-space'); 
                        sliderContainer.classList.remove('not-enough-space');
                    } else {
                        // 需要滚动时显示按钮
                        sliderContainer.classList.remove('enough-space'); 
                        sliderContainer.classList.add('not-enough-space');
                    }
                }

                // 绑定 next 按钮
                nextBtn.addEventListener("click", () => {
                    if (currentIndex + a < items.length) {
                        currentIndex += a;
                    } else {
                        currentIndex = 0; // 如果已经在最后，则跳转到首个元素
                    }
                    direction = "right";
                    updateSlider();
                });

                // 绑定 prev 按钮
                prevBtn.addEventListener("click", () => {
                    if (currentIndex - a >= 0) {
                        currentIndex -= a;
                    } else {
                        /**
                         * 求最后一组首个元素的索引，计算方式为 起始索引=⌊(元素总量−1)/组容量⌋×组容量
                         * 使用位运算取整更快，有下面三种方法：
                         * 1、按位或，即 (((items.length - 1) / a) | 0) * a
                         * 2、双按位非，即 (~~((items.length - 1) / a)) * a
                         * 3、无符号右移，即 (((items.length - 1) / a) >> 0) * a
                         * 但以上方法仅适用于非负数，虽然在此处需取整的数值符合条件，但 Math.floor 方法最安全，特此说明，请勿修改
                         */
                        currentIndex = Math.floor((items.length - 1) / a) * a; // 如果已经在首个，则跳转到最后一组的首个元素
                    }
                    direction = "left";
                    updateSlider();
                });

                // 窗口大小变化时，重新计算 a 并更新
                window.addEventListener("resize", () => {
                    // 重新检查按钮的可见性
                    checkSliderButtonsVisibility(); 
                    // 直接滑动到最前面
                    currentIndex = 0;
                    updateSlider();
                });

                // 延迟执行，确保 CSS 计算完成
                requestAnimationFrame(() => {
                    updateSlider();
                    checkSliderButtonsVisibility();
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
<figure id="centerbg" class="centerbg">
    <?php if (iro_opt('infor_bar')) { ?>
        <div class="focusinfo">
            <?php if (isset($text_logo['text']) && iro_opt('text_logo_options', 'true')) : ?>
                <h1 class="center-text glitch is-glitching Ubuntu-font" data-text="<?=$text_logo['text']; ?>">
                    <?php echo $text_logo['text']; ?></h1>
            <?php else : ?>
                <div class="header-tou"><a href="<?php bloginfo('url'); ?>"><img alt="avatar" src="<?=iro_opt('personal_avatar', '') ?: iro_opt('vision_resource_basepath','https://s.nmxc.ltd/sakurairo_vision/@2.7/').'series/avatar.webp'?>"></a>
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
                    <p><?php echo iro_opt('signature_text', 'Hi, Mashiro?'); ?></p>
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
