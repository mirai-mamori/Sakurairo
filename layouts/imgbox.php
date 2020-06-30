<?php

//https://api.mashiro.top/cover

?>
<style>.header-info::before{display: none !important;opacity: 0 !important;}</style>
<div id="banner_wave_1"></div><div id="banner_wave_2"></div>
<figure id="centerbg" class="centerbg">
<?php if ( !akina_option('infor-bar') ){ ?>
	<div class="focusinfo">
        <?php if (akina_option('focus_logo_text')):?>
        <h1 class="center-text glitch is-glitching Ubuntu-font" data-text="<?php echo akina_option('focus_logo_text', ''); ?>"><?php echo akina_option('focus_logo_text', ''); ?></h1>
   		<?php elseif (akina_option('focus_logo')):?>
	     <div class="header-tou"><a href="<?php bloginfo('url');?>" ><img src="<?php echo akina_option('focus_logo', ''); ?>"></a></div>
	  	<?php else :?>
         <div class="header-tou" ><a href="<?php bloginfo('url');?>"><img src="https://cdn.jsdelivr.net/gh/mirai-mamori/web-img/img/avatar.jpg"></a></div>	
      	<?php endif; ?>
		<div class="header-info">
			<!-- 首页一言打字效果 -->
			<?php if (akina_option('dazi', '1')): ?>
			<script src="https://cdn.jsdelivr.net/npm/typed.js@2.0.11/lib/typed.min.js"></script>
			<?php if (akina_option('dazi_yh', '1')): ?><i class="fa fa-quote-left"></i><?php endif; ?>
			<span class="element">疯狂造句中......</span>
			<?php if (akina_option('dazi_yh', '1')): ?><i class="fa fa-quote-right"></i><?php endif; ?>
			<span class="element"></span>
			<script>
            var typed = new Typed('.element', {
              strings: ["给时光以生命，给岁月以文明",<?php echo akina_option('dazi_a', ''); ?>,], //输入内容, 支持html标签
              typeSpeed: 140, //打字速度
              backSpeed: 50, //回退速度
              loop: false,//是否循环
              loopCount: Infinity,
              showCursor: true//是否开启光标
            });
            </script>
            <?php endif; ?>
            <p><?php echo akina_option('admin_des', 'Hi, Mashiro?'); ?></p>
            <?php if (akina_option('infor-bar-style')=="v2"): ?>
            <div class="top-social_v2">
                <li id="bg-pre"><img src="<?php echo akina_option('webweb_img'); ?>/sns/pre.png"/></li>
                <?php if (akina_option('github')){ ?>
                <li><a href="<?php echo akina_option('github', ''); ?>" target="_blank" class="social-github" title="github"><img src="<?php echo akina_option('webweb_img'); ?>/sns/github.png"/></a></li>
                <?php } ?>	
                <?php if (akina_option('sina')){ ?>
                <li><a href="<?php echo akina_option('sina', ''); ?>" target="_blank" class="social-sina" title="sina"><img src="<?php echo akina_option('webweb_img'); ?>/sns/weibo.png"/></a></li>
                <?php } ?>
                <?php if (akina_option('telegram')){ ?>
                <li><a href="<?php echo akina_option('telegram', ''); ?>" target="_blank" class="social-lofter" title="telegram"><img src="<?php echo akina_option('webweb_img'); ?>/sns/tg.png"/></a></li>
                <?php } ?>	
                <?php if (akina_option('qq')){ ?>
                <li class="qq"><a href="<?php echo akina_option('qq', ''); ?>" title="Initiate chat ?"><img src="<?php echo akina_option('webweb_img'); ?>/sns/qq.png"/></a></li>
                <?php } ?>	
                <?php if (akina_option('qzone')){ ?>
                <li><a href="<?php echo akina_option('qzone', ''); ?>" target="_blank" class="social-qzone" title="qzone"><img src="<?php echo akina_option('webweb_img'); ?>/sns/qz.png"/></a></li>
                <?php } ?>
                <?php if (akina_option('wechat')){ ?>
                <li class="wechat"><a href="#"><img src="<?php echo akina_option('webweb_img'); ?>/sns/wechat.png"/></a>
                    <div class="wechatInner">
                        <img src="<?php echo akina_option('wechat', ''); ?>" alt="WeChat">
                    </div>
                </li>
                <?php } ?> 
                <?php if (akina_option('lofter')){ ?>
                <li><a href="<?php echo akina_option('lofter', ''); ?>" target="_blank" class="social-lofter" title="lofter"><img src="<?php echo akina_option('webweb_img'); ?>/sns/lofter.png"/></a></li>
                <?php } ?>	
                <?php if (akina_option('bili')){ ?>
                <li><a href="<?php echo akina_option('bili', ''); ?>" target="_blank" class="social-bili" title="bilibili"><img src="<?php echo akina_option('webweb_img'); ?>/sns/bilibili.png"/></a></li>
                <?php } ?>
                <?php if (akina_option('youku')){ ?>
                <li><a href="<?php echo akina_option('youku', ''); ?>" target="_blank" class="social-youku" title="youku"><img src="<?php echo akina_option('webweb_img'); ?>/sns/youku.png"/></a></li>
                <?php } ?>
                <?php if (akina_option('wangyiyun')){ ?>
                <li><a href="<?php echo akina_option('wangyiyun', ''); ?>" target="_blank" class="social-wangyiyun" title="CloudMusic"><img src="<?php echo akina_option('webweb_img'); ?>/sns/ncm.png"/></a></li>
                <?php } ?>
                <?php if (akina_option('twitter')){ ?>
                <li><a href="<?php echo akina_option('twitter', ''); ?>" target="_blank" class="social-wangyiyun" title="Twitter"><img src="<?php echo akina_option('webweb_img'); ?>/sns/tw.png"/></a></li>
                <?php } ?>	
                <?php if (akina_option('facebook')){ ?>
                <li><a href="<?php echo akina_option('facebook', ''); ?>" target="_blank" class="social-wangyiyun" title="Facebook"><img src="<?php echo akina_option('webweb_img'); ?>/sns/fb.png"/></a></li>
                <?php } ?>	
                <?php if (akina_option('jianshu')){ ?>
                <li><a href="<?php echo akina_option('jianshu', ''); ?>" target="_blank" class="social-wangyiyun" title="Jianshu"><img src="<?php echo akina_option('webweb_img'); ?>/sns/book.png"/></a></li>
                <?php } ?>
                <?php if (akina_option('zhihu')){ ?>
                <li><a href="<?php echo akina_option('zhihu', ''); ?>" target="_blank" class="social-wangyiyun" title="Zhihu"><img src="<?php echo akina_option('webweb_img'); ?>/sns/zhihu.png"/></a></li>
                <?php } ?>	
                <?php if (akina_option('csdn')){ ?>
                <li><a href="<?php echo akina_option('csdn', ''); ?>" target="_blank" class="social-wangyiyun" title="CSDN"><img src="<?php echo akina_option('webweb_img'); ?>/sns/csdn.png"/></a></li>
                <?php } ?>		
                <?php if (akina_option('email_name') && akina_option('email_domain')){ ?>
                <li><a onclick="mail_me()" class="social-wangyiyun" title="E-mail"><img src="<?php echo akina_option('webweb_img'); ?>/sns/mail.png"/></a></li>
                <?php } ?>	
                <li id="bg-next"><img src="<?php echo akina_option('webweb_img'); ?>/sns/next.png"/></li>	
            </div>
            <?php endif; ?>
        </div>
        <?php if (akina_option('infor-bar-style')=="v1"): ?>
		<div class="top-social">
		<li id="bg-pre"><img src="<?php echo akina_option('webweb_img'); ?>/sns/pre.png"/></li>
		<?php if (akina_option('github')){ ?>
		<li><a href="<?php echo akina_option('github', ''); ?>" target="_blank" class="social-github" title="github"><img src="<?php echo akina_option('webweb_img'); ?>/sns/github.png"/></a></li>
		<?php } ?>	
		<?php if (akina_option('sina')){ ?>
		<li><a href="<?php echo akina_option('sina', ''); ?>" target="_blank" class="social-sina" title="sina"><img src="<?php echo akina_option('webweb_img'); ?>/sns/weibo.png"/></a></li>
		<?php } ?>
		<?php if (akina_option('telegram')){ ?>
		<li><a href="<?php echo akina_option('telegram', ''); ?>" target="_blank" class="social-lofter" title="telegram"><img src="<?php echo akina_option('webweb_img'); ?>/sns/tg.png"/></a></li>
		<?php } ?>	
		<?php if (akina_option('qq')){ ?>
		<li class="qq"><a href="<?php echo akina_option('qq', ''); ?>" title="Initiate chat ?"><img src="<?php echo akina_option('webweb_img'); ?>/sns/qq.png"/></a></li>
		<?php } ?>	
		<?php if (akina_option('qzone')){ ?>
		<li><a href="<?php echo akina_option('qzone', ''); ?>" target="_blank" class="social-qzone" title="qzone"><img src="<?php echo akina_option('webweb_img'); ?>/sns/qz.png"/></a></li>
		<?php } ?>
		<?php if (akina_option('wechat')){ ?>
		<li class="wechat"><a href="#"><img src="<?php echo akina_option('webweb_img'); ?>/sns/wechat.png"/></a>
			<div class="wechatInner">
				<img src="<?php echo akina_option('wechat', ''); ?>" alt="WeChat">
			</div>
		</li>
		<?php } ?> 
		<?php if (akina_option('lofter')){ ?>
		<li><a href="<?php echo akina_option('lofter', ''); ?>" target="_blank" class="social-lofter" title="lofter"><img src="<?php echo akina_option('webweb_img'); ?>/sns/lofter.png"/></a></li>
		<?php } ?>	
		<?php if (akina_option('bili')){ ?>
		<li><a href="<?php echo akina_option('bili', ''); ?>" target="_blank" class="social-bili" title="bilibili"><img src="<?php echo akina_option('webweb_img'); ?>/sns/bilibili.png"/></a></li>
		<?php } ?>
		<?php if (akina_option('youku')){ ?>
		<li><a href="<?php echo akina_option('youku', ''); ?>" target="_blank" class="social-youku" title="youku"><img src="<?php echo akina_option('webweb_img'); ?>/sns/youku.png"/></a></li>
		<?php } ?>
		<?php if (akina_option('wangyiyun')){ ?>
		<li><a href="<?php echo akina_option('wangyiyun', ''); ?>" target="_blank" class="social-wangyiyun" title="CloudMusic"><img src="<?php echo akina_option('webweb_img'); ?>/sns/ncm.png"/></a></li>
		<?php } ?>
		<?php if (akina_option('twitter')){ ?>
		<li><a href="<?php echo akina_option('twitter', ''); ?>" target="_blank" class="social-wangyiyun" title="Twitter"><img src="<?php echo akina_option('webweb_img'); ?>/sns/tw.png"/></a></li>
		<?php } ?>	
		<?php if (akina_option('facebook')){ ?>
		<li><a href="<?php echo akina_option('facebook', ''); ?>" target="_blank" class="social-wangyiyun" title="Facebook"><img src="<?php echo akina_option('webweb_img'); ?>/sns/fb.png"/></a></li>
		<?php } ?>	
		<?php if (akina_option('jianshu')){ ?>
		<li><a href="<?php echo akina_option('jianshu', ''); ?>" target="_blank" class="social-wangyiyun" title="Jianshu"><img src="<?php echo akina_option('webweb_img'); ?>/sns/book.png"/></a></li>
		<?php } ?>
		<?php if (akina_option('zhihu')){ ?>
		<li><a href="<?php echo akina_option('zhihu', ''); ?>" target="_blank" class="social-wangyiyun" title="Zhihu"><img src="<?php echo akina_option('webweb_img'); ?>/sns/zhihu.png"/></a></li>
		<?php } ?>	
		<?php if (akina_option('csdn')){ ?>
		<li><a href="<?php echo akina_option('csdn', ''); ?>" target="_blank" class="social-wangyiyun" title="CSDN"><img src="<?php echo akina_option('webweb_img'); ?>/sns/csdn.png"/></a></li>
		<?php } ?>		
		<?php if (akina_option('email_name') && akina_option('email_domain')){ ?>
		<li><a onclick="mail_me()" class="social-wangyiyun" title="E-mail"><img src="<?php echo akina_option('webweb_img'); ?>/sns/mail.png"/></a></li>
		<?php } ?>	
		<li id="bg-next"><img src="<?php echo akina_option('webweb_img'); ?>/sns/next.png"/></li>	
	  	</div>
        <?php endif; ?>
	</div>
	<?php } ?>
</figure>
<?php
echo bgvideo(); //BGVideo 
?>
<!-- 首页下拉箭头 -->
<?php if (akina_option('godown', '1')): ?>
<div class="headertop-down faa-float animated" onclick="headertop_down()"><span><i class="fa fa-chevron-down" aria-hidden="true" style="color:<?php echo akina_option('godown_skin'); ?>"></i></span></div>
<?php endif; ?>
