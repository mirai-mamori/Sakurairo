<?php
include_once('all_opt.php');
$text_logo = iro_opt('text_logo');
function print_social_zone(string $social_display_icon, array $all_opt)
{
 if (iro_opt('cover_random_graphs_switch', 'true')){ ?>
    <li id="bg-pre"><img src="<?=$social_display_icon?>pre.png" /></li>
<?php }
if (iro_opt('wechat')) { ?>
<li class="wechat"><a href="#" title="wechat"><img src="<?=$social_display_icon?>wechat.png" /></a>
    <div class="wechatInner">
        <img src="<?=iro_opt('wechat', ''); ?>" alt="WeChat">
    </div>
</li>
<?php }
    foreach ($all_opt as $key => $value) {
        if (!empty($value['link'])) {
            switch(true){
                case isset($value['icon']):
                    $img_url = $social_display_icon.$value['icon'].'.png';
                    break;
                case isset($value['img']):
                    $img_url = $value['img'];
                    break;
                default:
                    $img_url = $social_display_icon.$key.'.png';
            }
            echo '<li><a href="',$value['link'],'" target="_blank" class="social-', $value['class'] ?? $key ,'" title="' , $value['title'] ?? $key , '"><img src="' , $img_url , '" /></a></li>';
        }
    }
    ?>
<?php if (iro_opt('email_name') && iro_opt('email_domain')) { ?>
<li><a onclick="mail_me()" class="social-wangyiyun" title="E-mail"><img
            src="<?php echo iro_opt('social_display_icon'); ?>/mail.png" /></a></li>
<?php } ?>
<?php if (iro_opt('cover_random_graphs_switch', 'true')) : ?>
<li id="bg-next"><img src="<?=$social_display_icon?>next.png" /></li>
<?php endif; ?>
<?php
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
        <?php elseif (iro_opt('personal_avatar')) : ?>
        <div class="header-tou"><a href="<?php bloginfo('url'); ?>"><img
                    src="<?=iro_opt('personal_avatar', ''); ?>"></a></div>
        <?php else : ?>
        <div class="header-tou"><a href="<?php bloginfo('url'); ?>"><img
                    src="https://cdn.jsdelivr.net/gh/Fuukei/Public_Repository@latest/vision/tsubame/avatar.jpg"></a>
        </div>
        <?php endif; ?>
        <div class="header-info">
            <!-- 首页一言打字效果 -->
            <?php if (iro_opt('signature_typing', 'true')) : ?>
            <script src="https://cdn.jsdelivr.net/npm/typed.js@2.0.11/lib/typed.min.js"></script>
            <?php if (iro_opt('signature_typing_marks', 'true')) : ?><i class="fa fa-quote-left"></i><?php endif; ?>
            <span class="element">疯狂造句中......</span>
            <?php if (iro_opt('signature_typing_marks', 'true')) : ?><i class="fa fa-quote-right"></i><?php endif; ?>
            <span class="element"></span>
            <script>
            var typed = new Typed('.element', {
                strings: ["给时光以生命，给岁月以文明",
                <?php echo iro_opt('signature_typing_text', ''); ?>, ], //输入内容, 支持html标签
                typeSpeed: 140, //打字速度
                backSpeed: 50, //回退速度
                loop: false, //是否循环
                loopCount: Infinity,
                showCursor: true //是否开启光标
            });
            </script>
            <?php endif; ?>
            <p><?php echo iro_opt('signature_text', 'Hi, Mashiro?'); ?></p>
            <?php if (iro_opt('infor_bar_style') == "v2") : ?>
            <div class="top-social_v2">
                <?php print_social_zone($social_display_icon,$all_opt); ?>
            </div>
            <?php endif; ?>
        </div>
        <?php if (iro_opt('infor_bar_style') == "v1") : ?>
        <div class="top-social">
            <?php print_social_zone($social_display_icon,$all_opt); ?>
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