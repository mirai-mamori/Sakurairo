<?php
include(get_stylesheet_directory().'/layouts/all_opt.php');
$text_logo = iro_opt('text_logo');
$print_social_zone = function() use ($all_opt,$social_display_icon):void{
    // 左箭头
    if (iro_opt('cover_random_graphs_switch', 'true')):?>
        <li id="bg-pre"><img src="<?=$social_display_icon?>pre.png" loading="lazy" alt="<?=__('Previous','sakurairo')?>"/></li>
    <?php
    endif;
    // 微信
    if (iro_opt('wechat')):?>
        <li class="wechat"><a href="#" title="WeChat"><img loading="lazy" src="<?=$social_display_icon?>wechat.png" /></a>
            <div class="wechatInner">
                <img loading="lazy" src="<?=iro_opt('wechat', '')?>" alt="WeChat">
            </div>
        </li>
    <?php
    endif;
    // 大体(all_opt.php)
    foreach ($all_opt as $key => $value):
        if (!empty($value['link'])):
            // 显然 这里的逻辑可以看看all_opt的结构（
            $img_url = $value['img'] ?? ($social_display_icon . ($value['icon'] ?? $key) . '.png');
            $title = $value['title'] ?? $key;
            ?>
            <li><a href="<?=$value['link'];?>" target="_blank" class="social-<?=$value['class'] ?? $key?>" title="<?=$title?>"><img alt="<?=$title?>" loading="lazy" src="<?=$img_url?>" /></a></li>
        <?php
        endif;
    endforeach;
    // 邮箱
    if (iro_opt('email_name') && iro_opt('email_domain')):?>
        <li><a onclick="mail_me()" class="social-wangyiyun" title="E-mail"><img loading="lazy"
        alt="E-mail"
                    src="<?=iro_opt('vision_resource_basepath')?><?=iro_opt('social_display_icon')?>/mail.png" /></a></li>
    <?php
    endif;
    // 右箭头
    if (iro_opt('cover_random_graphs_switch', 'true')):?>
        <li id="bg-next"><img loading="lazy" src="<?=$social_display_icon?>next.png" alt="<?=__('Next','sakurairo')?>"/></li>
    <?php endif;
}
?>
<?php
/*未定义的伪类 */
/* <style>
.header-info::before {
    display: none !important;
    opacity: 0 !important;
}
</style> */
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
                <div class="header-tou"><a href="<?php bloginfo('url'); ?>"><img loading="lazy" src="<?=iro_opt('personal_avatar', '') ?: iro_opt('vision_resource_basepath','https://s.nmxc.ltd/sakurairo_vision/@2.5/').'series/avatar.webp'?>"></a>
                </div>
            <?php endif; ?>
            <div class="header-info">
                <!-- 首页一言打字效果 -->
                <?php if (iro_opt('signature_typing', 'true')) : ?>
                <?php if (iro_opt('signature_typing_marks', 'true')) : ?><i class="fa fa-quote-left"></i><?php endif; ?>
                <span class="element"><?=iro_opt('signature_typing_placeholder','疯狂造句中......')?></span>
                <?php if (iro_opt('signature_typing_marks', 'true')) : ?><i class="fa fa-quote-right"></i><?php endif; ?>
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
        </div>
    <?php } ?>
</figure>
<?php
echo bgvideo(); //BGVideo 
?>
<!-- 首页下拉箭头 -->
<?php if (iro_opt('drop_down_arrow', 'true')) : ?>
<div class="headertop-down faa-float animated" onclick="headertop_down()"><span><i class="fa fa-chevron-down"
            aria-hidden="true" style="color:<?php echo iro_opt('drop_down_arrow_color'); ?>"></i></span></div>
<?php endif; ?>