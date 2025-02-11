<?php
include(get_stylesheet_directory().'/layouts/all_opt.php');
$text_logo = iro_opt('text_logo');
if (iro_opt('social_display_icon', '') === 'display_icon/remix_iconfont'): ?>
    <link rel="stylesheet" href="<?=iro_opt('vision_resource_basepath'); ?>display_icon/remix_iconfont/remix_social.css">
<?php endif;
$print_social_zone = function() use ($all_opt): void {

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

    $social_icons = [];

    // 处理微信
    if ($wechat_qrcode_switch && $wechat_qrcode) {
        $social_icons[] = [
            'class' => 'socialIconQRCodeInner',
            'icon' => 'wechat',
            'inner' => '<img class="socialIconQRCodeInner-img" src="' . esc_url($wechat_qrcode) . '" alt="WeChat QR Code">',
            'action' => $wechat_copy_switch && $wechat_id ? 'copyWeChatID(event, this)' : ($wechat_url ? "redirectToURL(event, '" . esc_url($wechat_url) . "')" : 'doNothing(event)'),
        ];
    } elseif (!$wechat_qrcode_switch && $wechat_id) {
        $social_icons[] = [
            'class' => 'socialIconTextInner',
            'icon' => 'wechat',
            'inner' => '<span class="socialIconTextInner-span">' . esc_html($wechat_id) . '</span>',
            'action' => $wechat_copy_switch ? 'copyWeChatID(event, this)' : ($wechat_url ? "redirectToURL(event, '" . esc_url($wechat_url) . "')" : 'doNothing(event)'),
        ];
    }

    // 处理QQ
    if ($qq_qrcode_switch && $qq_qrcode) {
        $social_icons[] = [
            'class' => 'socialIconQRCodeInner',
            'icon' => 'qq',
            'inner' => '<img class="socialIconQRCodeInner-img" src="' . esc_url($qq_qrcode) . '" alt="QQ QR Code">',
            'action' => $qq_copy_switch && $qq_id ? 'copyQQID(event, this)' : ($qq_url ? "redirectToURL(event, '" . esc_url($qq_url) . "')" : 'doNothing(event)'),
        ];
    } elseif (!$qq_qrcode_switch && $qq_id) {
        $social_icons[] = [
            'class' => 'socialIconTextInner',
            'icon' => 'qq',
            'inner' => '<span class="socialIconTextInner-span">' . esc_html($qq_id) . '</span>',
            'action' => $qq_copy_switch ? 'copyQQID(event, this)' : ($qq_url ? "redirectToURL(event, '" . esc_url($qq_url) . "')" : 'doNothing(event)'),
        ];
    }

    if (!empty($social_icons)):
        foreach ($social_icons as $icon_data): ?>
            <li class="socialIconWithInner">
                <a href="javascript:void(0);" title="<?= ucfirst($icon_data['icon']) ?>" onclick="<?= $icon_data['action'] ?>">
                    <?php if (iro_opt('social_display_icon') === 'display_icon/remix_iconfont'): ?>
                        <i class="remix_social icon-<?= esc_attr($icon_data['icon']) ?>"></i>
                    <?php else: ?>
                        <img loading="lazy" src="<?= iro_opt('vision_resource_basepath') . iro_opt('social_display_icon') . '/' . esc_attr($icon_data['icon']) . '.webp' ?>" />
                    <?php endif; ?>
                </a>
                <?php if (!empty($icon_data['class'])): ?>
                    <div class="<?= esc_attr($icon_data['class']) ?>">
                        <?= $icon_data['inner'] ?>
                    </div>
                <?php endif; ?>
            </li>
        <?php endforeach;
    endif;
?>

	<script>
		function copyWeChatID(event, element) {
			copyID(event, element, "<?= esc_js($wechat_id) ?>", "WeChat ID");
		}

		function copyQQID(event, element) {
			copyID(event, element, "<?= esc_js($qq_id) ?>", "QQ ID");
		}

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
					existingCopiedMessage.remove();
				}

				var inner = parentElement.querySelector('.socialIconTextInner, .socialIconQRCodeInner');
				if (inner) {
					inner.style.display = 'none';
				}

				var copiedMessage = document.createElement("div");
				copiedMessage.className = "socialIconTextInner copied-message";
				copiedMessage.innerHTML = '<span class="socialIconTextInner-span">Copied!</span>';
				parentElement.appendChild(copiedMessage);
				setTimeout(function () {
					copiedMessage.remove();
					if (inner) {
						inner.style.display = '';
					}
				}, 2000);
			}).catch(function (err) {
				console.error("Failed to copy: ", err);
				alert("Failed to copy " + label + ".");
			});
		}

		function redirectToURL(event, url) {
			event.preventDefault();
			if (url) {
				window.open(url, '_blank');
			}
		}

		function doNothing(event) {
			event.preventDefault();
		}
	</script>

	<?php
    // 大体(all_opt.php)
    foreach ($all_opt as $key => $value):
        if (!empty($value['link'])):
            $img_url = $value['img'] ?? (iro_opt('vision_resource_basepath').iro_opt('social_display_icon').'/' . ($value['icon'] ?? $key) . '.webp');
            $title = $value['title'] ?? $key;
            ?>
            <li><a href="<?= $value['link']; ?>" target="_blank" class="social-<?= $value['class'] ?? $key ?>" title="<?= $title ?>">
                <?php if (iro_opt('social_display_icon') === 'display_icon/remix_iconfont' && $key !== 'socialdiy1' && $key !== 'socialdiy2'): ?>
                    <i class="remix_social icon-<?= $key ?>"></i>
                <?php else: ?>
                    <img alt="<?= $title ?>" loading="lazy" src="<?= $img_url ?>" />
                <?php endif; ?>
            </a></li>
        <?php
        endif;
    endforeach;

    // 邮箱
    if (iro_opt('email_name') && iro_opt('email_domain')): ?>
        <li><a onclick="mail_me()" class="social-wangyiyun" title="E-mail">
            <?php if (iro_opt('social_display_icon') === 'display_icon/remix_iconfont'): ?>
                <i class="remix_social icon-mail"></i>
            <?php else: ?>
                <img loading="lazy" alt="E-mail" src="<?php echo iro_opt('vision_resource_basepath').iro_opt('social_display_icon').'/' . 'mail.webp'; ?>" />
            <?php endif; ?>
        </a></li>
    <?php
    endif;

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
                    <span class="element"><?=iro_opt('signature_typing_placeholder','疯狂造句中......')?></span>
                    <?php if (iro_opt('signature_typing_marks', 'true')) : ?><i class="fa-solid fa-quote-right"></i><?php endif; ?>
                    <span class="element"></span>
                    <script type="application/json" id="typed-js-initial">
                    <?= iro_opt('signature_typing_json', ''); ?>
                    </script>
                    <!-- var typed = new Typed('.element', {
                            strings: ["给时光以生命，给岁月以文明", ], //输入内容, 支持html标签
                            typeSpeed: 140, //打字速度
                            backSpeed: 50, //回退速度
                            loop: false, //是否循环
                            loopCount: Infinity,
                            showCursor: true //是否开启光标
                        }); -->
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

            <?php if (iro_opt('cover_random_graphs_switch', 'true')): ?>
            <div class="bg-switch">
            <li id="bg-next" style="display: flex; gap: 6px; align-items: center; letter-spacing: 1px;"><i class="fa-solid fa-dice"></i><?= __('Change', 'sakurairo') ?></li>
            </div>
            <?php endif; ?>

        </div>
    <?php } ?>
</figure>
<?php
echo bgvideo(); //BGVideo 
?>
<!-- 首页下拉箭头 -->
<?php if (iro_opt('drop_down_arrow', 'true')) : ?>
<div class="headertop-down" onclick="headertop_down()"><span><svg t="1682342753354" class="homepage-downicon" viewBox="0 0 1843 1024" version="1.1" xmlns="http://www.w3.org/2000/svg" p-id="21355" width="80px" height="80px"><path d="M1221.06136021 284.43250057a100.69380037 100.69380037 0 0 1 130.90169466 153.0543795l-352.4275638 302.08090944a100.69380037 100.69380037 0 0 1-130.90169467 0L516.20574044 437.48688007A100.69380037 100.69380037 0 0 1 647.10792676 284.43250057L934.08439763 530.52766665l286.97696258-246.09516608z" fill="<?php echo iro_opt('drop_down_arrow_color'); ?>" p-id="21356"></path></svg></span></div>
<?php endif; ?>