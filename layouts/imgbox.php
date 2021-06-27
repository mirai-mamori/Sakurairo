<?php

$text_logo = iro_opt('text_logo');

?>
<style>.header-info::before{display: none !important;opacity: 0 !important;}</style>
<div id="banner_wave_1"></div><div id="banner_wave_2"></div>
<figure id="centerbg" class="centerbg">
<?php if (iro_opt('infor_bar') ){ ?>
	<div class="focusinfo">
        <?php if ($text_logo['text'] && iro_opt('text_logo_options', 'true')):?>
        <h1 class="center-text glitch is-glitching Ubuntu-font" data-text="<?php echo $text_logo['text']; ?>"><?php echo $text_logo['text']; ?></h1>
   		<?php elseif (iro_opt('personal_avatar')):?>
	     <div class="header-tou"><a href="<?php bloginfo('url');?>" ><img src="<?php echo iro_opt('personal_avatar', ''); ?>"></a></div>
	  	<?php else :?>
         <div class="header-tou" ><a href="<?php bloginfo('url');?>"><img src="https://cdn.jsdelivr.net/gh/Fuukei/Public_Repository@latest/vision/hyouryu/avatar.jpg"></a></div>	
      	<?php endif; ?>
		<div class="header-info">
			<!-- 首页一言打字效果 -->
			<?php if (iro_opt('signature_typing', 'true')): ?>
			<script src="https://cdn.jsdelivr.net/npm/typed.js@2.0.11/lib/typed.min.js"></script>
			<?php if (iro_opt('signature_typing_marks', 'true')): ?><i class="fa fa-quote-left"></i><?php endif; ?>
			<span class="element">疯狂造句中......</span>
			<?php if (iro_opt('signature_typing_marks', 'true')): ?><i class="fa fa-quote-right"></i><?php endif; ?>
			<span class="element"></span>
			<script>
            var typed = new Typed('.element', {
              strings: ["给时光以生命，给岁月以文明",<?php echo iro_opt('signature_typing_text', ''); ?>,], //输入内容, 支持html标签
              typeSpeed: 140, //打字速度
              backSpeed: 50, //回退速度
              loop: false,//是否循环
              loopCount: Infinity,
              showCursor: true//是否开启光标
            });
            </script>
            <?php endif; ?>
            <p><?php echo iro_opt('signature_text', 'Hi, Mashiro?'); ?></p>
            <?php if (iro_opt('infor_bar_style')=="v2"): ?>
            <div class="top-social_v2">
                <?php if (iro_opt('cover_random_graphs_switch', 'true')): ?>
                <li id="bg-pre"><img src="<?php echo iro_opt('social_display_icon'); ?>/pre.png"/></li>
                <?php endif; ?>
                <?php if (iro_opt('wechat')){ ?>
                <li class="wechat"><a href="#" title="wechat"><img src="<?php echo iro_opt('social_display_icon'); ?>/wechat.png"/></a>
                    <div class="wechatInner">
                        <img src="<?php echo iro_opt('wechat', ''); ?>" alt="WeChat">
                    </div>
                </li>
                <?php } ?> 
                <?php if (iro_opt('qq')){ ?>
                <li class="qq"><a href="<?php echo iro_opt('qq', ''); ?>" title="Initiate chat ?"><img src="<?php echo iro_opt('social_display_icon'); ?>/qq.png"/></a></li>
                <?php } ?>	
                <?php if (iro_opt('bili')){ ?>
                <li><a href="<?php echo iro_opt('bili', ''); ?>" target="_blank" class="social-bili" title="bilibili"><img src="<?php echo iro_opt('social_display_icon'); ?>/bilibili.png"/></a></li>
                <?php } ?>
                <?php if (iro_opt('wangyiyun')){ ?>
                <li><a href="<?php echo iro_opt('wangyiyun', ''); ?>" target="_blank" class="social-wangyiyun" title="CloudMusic"><img src="<?php echo iro_opt('social_display_icon'); ?>/ncm.png"/></a></li>
                <?php } ?>
                <?php if (iro_opt('sina')){ ?>
                <li><a href="<?php echo iro_opt('sina', ''); ?>" target="_blank" class="social-sina" title="sina"><img src="<?php echo iro_opt('social_display_icon'); ?>/weibo.png"/></a></li>
                <?php } ?>
                <?php if (iro_opt('github')){ ?>
                <li><a href="<?php echo iro_opt('github', ''); ?>" target="_blank" class="social-github" title="github"><img src="<?php echo iro_opt('social_display_icon'); ?>/github.png"/></a></li>
                <?php } ?>	
                <?php if (iro_opt('telegram')){ ?>
                <li><a href="<?php echo iro_opt('telegram', ''); ?>" target="_blank" class="social-lofter" title="telegram"><img src="<?php echo iro_opt('social_display_icon'); ?>/tg.png"/></a></li>
                <?php } ?>	
                <?php if (iro_opt('steam')){ ?>
                <li><a href="<?php echo iro_opt('steam', ''); ?>" target="_blank" class="social-wangyiyun" title="Steam"><img src="<?php echo iro_opt('social_display_icon'); ?>/st.png"/></a></li>
                <?php } ?>
                <?php if (iro_opt('zhihu')){ ?>
                <li><a href="<?php echo iro_opt('zhihu', ''); ?>" target="_blank" class="social-wangyiyun" title="Zhihu"><img src="<?php echo iro_opt('social_display_icon'); ?>/zhihu.png"/></a></li>
                <?php } ?>	
                <?php if (iro_opt('qzone')){ ?>
                <li><a href="<?php echo iro_opt('qzone', ''); ?>" target="_blank" class="social-qzone" title="qzone"><img src="<?php echo iro_opt('social_display_icon'); ?>/qz.png"/></a></li>
                <?php } ?>
                <?php if (iro_opt('lofter')){ ?>
                <li><a href="<?php echo iro_opt('lofter', ''); ?>" target="_blank" class="social-lofter" title="lofter"><img src="<?php echo iro_opt('social_display_icon'); ?>/lofter.png"/></a></li>
                <?php } ?>	
                <?php if (iro_opt('youku')){ ?>
                <li><a href="<?php echo iro_opt('youku', ''); ?>" target="_blank" class="social-youku" title="youku"><img src="<?php echo iro_opt('social_display_icon'); ?>/youku.png"/></a></li>
                <?php } ?>
                <?php if (iro_opt('linkedin')){ ?>
                <li><a href="<?php echo iro_opt('linkedin', ''); ?>" target="_blank" class="social-wangyiyun" title="LinkedIn"><img src="<?php echo iro_opt('social_display_icon'); ?>/lk.png"/></a></li>
                <?php } ?>		
                <?php if (iro_opt('twitter')){ ?>
                <li><a href="<?php echo iro_opt('twitter', ''); ?>" target="_blank" class="social-wangyiyun" title="Twitter"><img src="<?php echo iro_opt('social_display_icon'); ?>/tw.png"/></a></li>
                <?php } ?>	
                <?php if (iro_opt('facebook')){ ?>
                <li><a href="<?php echo iro_opt('facebook', ''); ?>" target="_blank" class="social-wangyiyun" title="Facebook"><img src="<?php echo iro_opt('social_display_icon'); ?>/fb.png"/></a></li>
                <?php } ?>	
                <?php if (iro_opt('csdn')){ ?>
                <li><a href="<?php echo iro_opt('csdn', ''); ?>" target="_blank" class="social-wangyiyun" title="CSDN"><img src="<?php echo iro_opt('social_display_icon'); ?>/csdn.png"/></a></li>
                <?php } ?>
                <?php if (iro_opt('jianshu')){ ?>
                <li><a href="<?php echo iro_opt('jianshu', ''); ?>" target="_blank" class="social-wangyiyun" title="Jianshu"><img src="<?php echo iro_opt('social_display_icon'); ?>/book.png"/></a></li>
                <?php } ?>
                <?php if (iro_opt('socialdiy1')){ ?>
                <li><a href="<?php echo iro_opt('socialdiy1', ''); ?>" target="_blank" class="social-wangyiyun" title="DIY1"><img src="<?php echo iro_opt('socialdiyp1'); ?>"/></a></li>
                <?php } ?>
                <?php if (iro_opt('socialdiy2')){ ?>
                <li><a href="<?php echo iro_opt('socialdiy2', ''); ?>" target="_blank" class="social-wangyiyun" title="DIY2"><img src="<?php echo iro_opt('socialdiyp2'); ?>"/></a></li>
                <?php } ?>							
                <?php if (iro_opt('email_name') && iro_opt('email_domain')){ ?>
                <li><a onclick="mail_me()" class="social-wangyiyun" title="E-mail"><img src="<?php echo iro_opt('social_display_icon'); ?>/mail.png"/></a></li>
                <?php } ?>	
                <?php if (iro_opt('cover_random_graphs_switch', 'true')): ?>
                <li id="bg-next"><img src="<?php echo iro_opt('social_display_icon'); ?>/next.png"/></li>
                <?php endif; ?>	
            </div>
            <?php endif; ?>
        </div>
        <?php if (iro_opt('infor_bar_style')=="v1"): ?>
		<div class="top-social">
        <?php if (iro_opt('cover_random_graphs_switch', 'true')): ?>
		<li id="bg-pre"><img src="<?php echo iro_opt('social_display_icon'); ?>/pre.png"/></li>
        <?php endif; ?>
        <?php if (iro_opt('wechat')){ ?>
		<li class="wechat"><a href="#" title="wechat"><img src="<?php echo iro_opt('social_display_icon'); ?>/wechat.png"/></a>
			<div class="wechatInner">
				<img src="<?php echo iro_opt('wechat', ''); ?>" alt="WeChat">
			</div>
		</li>
		<?php } ?> 
        <?php if (iro_opt('qq')){ ?>
		<li class="qq"><a href="<?php echo iro_opt('qq', ''); ?>" title="Initiate chat ?"><img src="<?php echo iro_opt('social_display_icon'); ?>/qq.png"/></a></li>
		<?php } ?>	
        <?php if (iro_opt('bili')){ ?>
		<li><a href="<?php echo iro_opt('bili', ''); ?>" target="_blank" class="social-bili" title="bilibili"><img src="<?php echo iro_opt('social_display_icon'); ?>/bilibili.png"/></a></li>
		<?php } ?>
        <?php if (iro_opt('wangyiyun')){ ?>
		<li><a href="<?php echo iro_opt('wangyiyun', ''); ?>" target="_blank" class="social-wangyiyun" title="CloudMusic"><img src="<?php echo iro_opt('social_display_icon'); ?>/ncm.png"/></a></li>
		<?php } ?>
        <?php if (iro_opt('sina')){ ?>
		<li><a href="<?php echo iro_opt('sina', ''); ?>" target="_blank" class="social-sina" title="sina"><img src="<?php echo iro_opt('social_display_icon'); ?>/weibo.png"/></a></li>
		<?php } ?>
		<?php if (iro_opt('github')){ ?>
		<li><a href="<?php echo iro_opt('github', ''); ?>" target="_blank" class="social-github" title="github"><img src="<?php echo iro_opt('social_display_icon'); ?>/github.png"/></a></li>
		<?php } ?>	
		<?php if (iro_opt('telegram')){ ?>
		<li><a href="<?php echo iro_opt('telegram', ''); ?>" target="_blank" class="social-lofter" title="telegram"><img src="<?php echo iro_opt('social_display_icon'); ?>/tg.png"/></a></li>
		<?php } ?>	
        <?php if (iro_opt('steam')){ ?>
        <li><a href="<?php echo iro_opt('steam', ''); ?>" target="_blank" class="social-wangyiyun" title="Steam"><img src="<?php echo iro_opt('social_display_icon'); ?>/st.png"/></a></li>
        <?php } ?>
        <?php if (iro_opt('zhihu')){ ?>
		<li><a href="<?php echo iro_opt('zhihu', ''); ?>" target="_blank" class="social-wangyiyun" title="Zhihu"><img src="<?php echo iro_opt('social_display_icon'); ?>/zhihu.png"/></a></li>
		<?php } ?>	
		<?php if (iro_opt('qzone')){ ?>
		<li><a href="<?php echo iro_opt('qzone', ''); ?>" target="_blank" class="social-qzone" title="qzone"><img src="<?php echo iro_opt('social_display_icon'); ?>/qz.png"/></a></li>
		<?php } ?>
		<?php if (iro_opt('lofter')){ ?>
		<li><a href="<?php echo iro_opt('lofter', ''); ?>" target="_blank" class="social-lofter" title="lofter"><img src="<?php echo iro_opt('social_display_icon'); ?>/lofter.png"/></a></li>
		<?php } ?>	
		<?php if (iro_opt('youku')){ ?>
		<li><a href="<?php echo iro_opt('youku', ''); ?>" target="_blank" class="social-youku" title="youku"><img src="<?php echo iro_opt('social_display_icon'); ?>/youku.png"/></a></li>
		<?php } ?>
        <?php if (iro_opt('linkedin')){ ?>
        <li><a href="<?php echo iro_opt('linkedin', ''); ?>" target="_blank" class="social-wangyiyun" title="LinkedIn"><img src="<?php echo iro_opt('social_display_icon'); ?>/lk.png"/></a></li>
        <?php } ?>
		<?php if (iro_opt('twitter')){ ?>
		<li><a href="<?php echo iro_opt('twitter', ''); ?>" target="_blank" class="social-wangyiyun" title="Twitter"><img src="<?php echo iro_opt('social_display_icon'); ?>/tw.png"/></a></li>
		<?php } ?>	
		<?php if (iro_opt('facebook')){ ?>
		<li><a href="<?php echo iro_opt('facebook', ''); ?>" target="_blank" class="social-wangyiyun" title="Facebook"><img src="<?php echo iro_opt('social_display_icon'); ?>/fb.png"/></a></li>
		<?php } ?>	
        <?php if (iro_opt('csdn')){ ?>
		<li><a href="<?php echo iro_opt('csdn', ''); ?>" target="_blank" class="social-wangyiyun" title="CSDN"><img src="<?php echo iro_opt('social_display_icon'); ?>/csdn.png"/></a></li>
		<?php } ?>
		<?php if (iro_opt('jianshu')){ ?>
		<li><a href="<?php echo iro_opt('jianshu', ''); ?>" target="_blank" class="social-wangyiyun" title="Jianshu"><img src="<?php echo iro_opt('social_display_icon'); ?>/book.png"/></a></li>
		<?php } ?>
        <?php if (iro_opt('socialdiy1')){ ?>
        <li><a href="<?php echo iro_opt('socialdiy1', ''); ?>" target="_blank" class="social-wangyiyun" title="DIY1"><img src="<?php echo iro_opt('socialdiyp1'); ?>"/></a></li>
        <?php } ?>
        <?php if (iro_opt('socialdiy2')){ ?>
        <li><a href="<?php echo iro_opt('socialdiy2', ''); ?>" target="_blank" class="social-wangyiyun" title="DIY2"><img src="<?php echo iro_opt('socialdiyp2'); ?>"/></a></li>
        <?php } ?>	
		<?php if (iro_opt('email_name') && iro_opt('email_domain')){ ?>
		<li><a onclick="mail_me()" class="social-wangyiyun" title="E-mail"><img src="<?php echo iro_opt('social_display_icon'); ?>/mail.png"/></a></li>
		<?php } ?>	
        <?php if (iro_opt('cover_random_graphs_switch', 'true')): ?>
		<li id="bg-next"><img src="<?php echo iro_opt('social_display_icon'); ?>/next.png"/></li>
        <?php endif; ?>	
	  	</div>
        <?php endif; ?>
	</div>
	<?php } ?>
</figure>
<?php
echo bgvideo(); //BGVideo 
?>
<!-- 首页下拉箭头 -->
<?php if (iro_opt('drop_down_arrow', 'true')): ?>
<div class="headertop-down faa-float animated" onclick="headertop_down()"><span><i class="fa fa-chevron-down" aria-hidden="true" style="color:<?php echo iro_opt('drop_down_arrow_color'); ?>"></i></span></div>
<?php endif; ?>
