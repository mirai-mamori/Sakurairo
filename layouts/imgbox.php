<?php

//https://api.mashiro.top/cover

?>
<figure id="centerbg" class="centerbg">
<?php if ( !akina_option('focus_infos') ){ ?>
	<div class="focusinfo">
        <?php if (akina_option('focus_logo_text')):?>
        <h1 class="center-text glitch is-glitching Ubuntu-font" data-text="<?php echo akina_option('focus_logo_text', ''); ?>"><?php echo akina_option('focus_logo_text', ''); ?></h1>
   		<?php elseif (akina_option('focus_logo')):?>
	     <div class="header-tou"><a href="<?php bloginfo('url');?>" ><img src="<?php echo akina_option('focus_logo', ''); ?>"></a></div>
	  	<?php else :?>
         <div class="header-tou" ><a href="<?php bloginfo('url');?>"><img src="https://cdn.jsdelivr.net/gh/mirai-mamori/web-img/img/avatar.jpg"></a></div>	
      	<?php endif; ?>
		<div class="header-info">
            <p><?php echo akina_option('admin_des', 'Hi, Mashiro?'); ?></p>
            <?php if (akina_option('social_style')=="v2"): ?>
            <div class="top-social_v2">
                <li id="bg-pre"><img class="flipx" src="https://cdn.jsdelivr.net/gh/mirai-mamori/web-img/snsimg/for.png"/></li>
                <?php if (akina_option('github')){ ?>
                <li><a href="<?php echo akina_option('github', ''); ?>" target="_blank" class="social-github" title="github"><img src="https://cdn.jsdelivr.net/gh/mirai-mamori/web-img/snsimg/Github.png"/></a></li>
                <?php } ?>	
                <?php if (akina_option('sina')){ ?>
                <li><a href="<?php echo akina_option('sina', ''); ?>" target="_blank" class="social-sina" title="sina"><img src="https://cdn.jsdelivr.net/gh/mirai-mamori/web-img/snsimg/weibo.png"/></a></li>
                <?php } ?>
                <?php if (akina_option('telegram')){ ?>
                <li><a href="<?php echo akina_option('telegram', ''); ?>" target="_blank" class="social-lofter" title="telegram"><img src="https://cdn.jsdelivr.net/gh/mirai-mamori/web-img/snsimg/tg.png"/></a></li>
                <?php } ?>	
                <?php if (akina_option('qq')){ ?>
                <li class="qq"><a href="<?php echo akina_option('qq', ''); ?>" title="Initiate chat ?"><img src="https://cdn.jsdelivr.net/gh/mirai-mamori/web-img/snsimg/qq.png"/></a></li>
                <?php } ?>	
                <?php if (akina_option('qzone')){ ?>
                <li><a href="<?php echo akina_option('qzone', ''); ?>" target="_blank" class="social-qzone" title="qzone"><img src="https://cdn.jsdelivr.net/gh/mirai-mamori/web-img/snsimg/qz.png"/></a></li>
                <?php } ?>
                <?php if (akina_option('wechat')){ ?>
                <li class="wechat"><a href="#"><img src="https://cdn.jsdelivr.net/gh/mirai-mamori/web-img/snsimg/wechat.png"/></a>
                    <div class="wechatInner">
                        <img src="<?php echo akina_option('wechat', ''); ?>" alt="WeChat">
                    </div>
                </li>
                <?php } ?> 
                <?php if (akina_option('lofter')){ ?>
                <li><a href="<?php echo akina_option('lofter', ''); ?>" target="_blank" class="social-lofter" title="lofter"><img src="https://cdn.jsdelivr.net/gh/mirai-mamori/web-img/snsimg/lofter.png"/></a></li>
                <?php } ?>	
                <?php if (akina_option('bili')){ ?>
                <li><a href="<?php echo akina_option('bili', ''); ?>" target="_blank" class="social-bili" title="bilibili"><img src="https://cdn.jsdelivr.net/gh/mirai-mamori/web-img/snsimg/bili.png"/></a></li>
                <?php } ?>
                <?php if (akina_option('youku')){ ?>
                <li><a href="<?php echo akina_option('youku', ''); ?>" target="_blank" class="social-youku" title="youku"><img src="https://cdn.jsdelivr.net/gh/mirai-mamori/web-img/snsimg/youku.png"/></a></li>
                <?php } ?>
                <?php if (akina_option('wangyiyun')){ ?>
                <li><a href="<?php echo akina_option('wangyiyun', ''); ?>" target="_blank" class="social-wangyiyun" title="CloudMusic"><img src="https://cdn.jsdelivr.net/gh/mirai-mamori/web-img/snsimg/ncm.png"/></a></li>
                <?php } ?>
                <?php if (akina_option('twitter')){ ?>
                <li><a href="<?php echo akina_option('twitter', ''); ?>" target="_blank" class="social-wangyiyun" title="Twitter"><img src="https://cdn.jsdelivr.net/gh/mirai-mamori/web-img/snsimg/tw.png"/></a></li>
                <?php } ?>	
                <?php if (akina_option('facebook')){ ?>
                <li><a href="<?php echo akina_option('facebook', ''); ?>" target="_blank" class="social-wangyiyun" title="Facebook"><img src="https://cdn.jsdelivr.net/gh/mirai-mamori/web-img/snsimg/fb.png"/></a></li>
                <?php } ?>	
                <?php if (akina_option('jianshu')){ ?>
                <li><a href="<?php echo akina_option('jianshu', ''); ?>" target="_blank" class="social-wangyiyun" title="Jianshu"><img src="https://cdn.jsdelivr.net/gh/mirai-mamori/web-img/snsimg/book.png"/></a></li>
                <?php } ?>
                <?php if (akina_option('zhihu')){ ?>
                <li><a href="<?php echo akina_option('zhihu', ''); ?>" target="_blank" class="social-wangyiyun" title="Zhihu"><img src="https://cdn.jsdelivr.net/gh/mirai-mamori/web-img/snsimg/zhihu.png"/></a></li>
                <?php } ?>	
                <?php if (akina_option('csdn')){ ?>
                <li><a href="<?php echo akina_option('csdn', ''); ?>" target="_blank" class="social-wangyiyun" title="CSDN"><img src="https://cdn.jsdelivr.net/gh/mirai-mamori/web-img/snsimg/csdn.png"/></a></li>
                <?php } ?>		
                <?php if (akina_option('email_name') && akina_option('email_domain')){ ?>
                <li><a onclick="mail_me()" class="social-wangyiyun" title="E-mail"><img src="https://cdn.jsdelivr.net/gh/mirai-mamori/web-img/snsimg/mail.png"/></a></li>
                <?php } ?>	
                <li id="bg-next"><img src="https://cdn.jsdelivr.net/gh/mirai-mamori/web-img/snsimg/for.png"/></li>	
            </div>
            <?php endif; ?>
        </div>
        <?php if (akina_option('social_style')=="v1"): ?>
		<div class="top-social">
		<li id="bg-pre"><img class="flipx" src="https://cdn.jsdelivr.net/gh/mirai-mamori/web-img/snsimg/for.png"/></li>
		<?php if (akina_option('github')){ ?>
		<li><a href="<?php echo akina_option('github', ''); ?>" target="_blank" class="social-github" title="github"><img src="https://cdn.jsdelivr.net/gh/mirai-mamori/web-img/snsimg/Github.png"/></a></li>
		<?php } ?>	
		<?php if (akina_option('sina')){ ?>
		<li><a href="<?php echo akina_option('sina', ''); ?>" target="_blank" class="social-sina" title="sina"><img src="https://cdn.jsdelivr.net/gh/mirai-mamori/web-img/snsimg/weibo.png"/></a></li>
		<?php } ?>
		<?php if (akina_option('telegram')){ ?>
		<li><a href="<?php echo akina_option('telegram', ''); ?>" target="_blank" class="social-lofter" title="telegram"><img src="https://cdn.jsdelivr.net/gh/mirai-mamori/web-img/snsimg/tg.png"/></a></li>
		<?php } ?>	
		<?php if (akina_option('qq')){ ?>
		<li class="qq"><a href="<?php echo akina_option('qq', ''); ?>" title="Initiate chat ?"><img src="https://cdn.jsdelivr.net/gh/mirai-mamori/web-img/snsimg/qq.png"/></a></li>
		<?php } ?>	
		<?php if (akina_option('qzone')){ ?>
		<li><a href="<?php echo akina_option('qzone', ''); ?>" target="_blank" class="social-qzone" title="qzone"><img src="https://cdn.jsdelivr.net/gh/mirai-mamori/web-img/snsimg/qz.png"/></a></li>
		<?php } ?>
		<?php if (akina_option('wechat')){ ?>
		<li class="wechat"><a href="#"><img src="https://cdn.jsdelivr.net/gh/mirai-mamori/web-img/snsimg/wechat.png"/></a>
			<div class="wechatInner">
				<img src="<?php echo akina_option('wechat', ''); ?>" alt="WeChat">
			</div>
		</li>
		<?php } ?> 
		<?php if (akina_option('lofter')){ ?>
		<li><a href="<?php echo akina_option('lofter', ''); ?>" target="_blank" class="social-lofter" title="lofter"><img src="https://cdn.jsdelivr.net/gh/mirai-mamori/web-img/snsimg/lofter.png"/></a></li>
		<?php } ?>	
		<?php if (akina_option('bili')){ ?>
		<li><a href="<?php echo akina_option('bili', ''); ?>" target="_blank" class="social-bili" title="bilibili"><img src="https://cdn.jsdelivr.net/gh/mirai-mamori/web-img/snsimg/bili.png"/></a></li>
		<?php } ?>
		<?php if (akina_option('youku')){ ?>
		<li><a href="<?php echo akina_option('youku', ''); ?>" target="_blank" class="social-youku" title="youku"><img src="https://cdn.jsdelivr.net/gh/mirai-mamori/web-img/snsimg/youku.png"/></a></li>
		<?php } ?>
		<?php if (akina_option('wangyiyun')){ ?>
		<li><a href="<?php echo akina_option('wangyiyun', ''); ?>" target="_blank" class="social-wangyiyun" title="CloudMusic"><img src="https://cdn.jsdelivr.net/gh/mirai-mamori/web-img/snsimg/ncm.png"/></a></li>
		<?php } ?>
		<?php if (akina_option('twitter')){ ?>
		<li><a href="<?php echo akina_option('twitter', ''); ?>" target="_blank" class="social-wangyiyun" title="Twitter"><img src="https://cdn.jsdelivr.net/gh/mirai-mamori/web-img/snsimg/tw.png"/></a></li>
		<?php } ?>	
		<?php if (akina_option('facebook')){ ?>
		<li><a href="<?php echo akina_option('facebook', ''); ?>" target="_blank" class="social-wangyiyun" title="Facebook"><img src="https://cdn.jsdelivr.net/gh/mirai-mamori/web-img/snsimg/fb.png"/></a></li>
		<?php } ?>	
		<?php if (akina_option('jianshu')){ ?>
		<li><a href="<?php echo akina_option('jianshu', ''); ?>" target="_blank" class="social-wangyiyun" title="Jianshu"><img src="https://cdn.jsdelivr.net/gh/mirai-mamori/web-img/snsimg/book.png"/></a></li>
		<?php } ?>
		<?php if (akina_option('zhihu')){ ?>
		<li><a href="<?php echo akina_option('zhihu', ''); ?>" target="_blank" class="social-wangyiyun" title="Zhihu"><img src="https://cdn.jsdelivr.net/gh/mirai-mamori/web-img/snsimg/zhihu.png"/></a></li>
		<?php } ?>	
		<?php if (akina_option('csdn')){ ?>
		<li><a href="<?php echo akina_option('csdn', ''); ?>" target="_blank" class="social-wangyiyun" title="CSDN"><img src="https://cdn.jsdelivr.net/gh/mirai-mamori/web-img/snsimg/csdn.png"/></a></li>
		<?php } ?>		
		<?php if (akina_option('email_name') && akina_option('email_domain')){ ?>
		<li><a onclick="mail_me()" class="social-wangyiyun" title="E-mail"><img src="https://cdn.jsdelivr.net/gh/mirai-mamori/web-img/snsimg/mail.png"/></a></li>
		<?php } ?>	
		<li id="bg-next"><img src="https://cdn.jsdelivr.net/gh/mirai-mamori/web-img/snsimg/for.png"/></li>	
	  	</div>
        <?php endif; ?>
	</div>
	<?php } ?>
</figure>
<?php
echo bgvideo(); //BGVideo 
