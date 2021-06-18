<?php 
/**
* Template Name: 登录页面模版
 */

get_header();
?>
	<div id="primary" class="content-area">
		<main id="main" class="site-main" role="main">
		<?php if(!is_user_logged_in()){ ?>
			<div class="ex-login">
				<div class="ex-login-title">
					<p><img src="<?php echo iro_opt('unlisted_avatar'); ?>"></p>
				</div>
				<form action="<?php echo home_url(); ?>/wp-login.php" method="post">  
					<p><input type="text" name="log" id="log" value="<?php echo $_POST['log'] ?: null; ?>" size="25" placeholder="Name" required /></p>
					<p><input type="password" name="pwd" id="pwd" value="<?php echo $_POST['pwd'] ?: null; ?>" size="25" placeholder="Password" required /></p>
					<?php if (iro_opt('captcha_switch','false')){
						echo '<p><img id="captchaimg" width="120" height="40" src=""><input type="text" name="yzm" id="yzm" class="input" value="" size="20" tabindex="4" placeholder="请输入验证码" required></p>';
					}?>
					<p class="forgetmenot"><label for="rememberme"><input name="rememberme" type="checkbox" id="rememberme" value="forever" <?php checked( $rememberme ); ?> /> <?php esc_html_e( 'Remember_Me' ); ?></label></p>
					<input type="hidden" name="redirect_to" value="<?php echo $_SERVER['REQUEST_URI']; ?>" />
					<input class="button login-button" name="submit" type="submit" value="登 入">
				</form>
				<div class="ex-new-account" style="padding: 0;"><p>请先注册！Register first, plz!</p><p><a href="<?php echo iro_opt('exregister_url') ?: bloginfo('url'); ?>" target="_blank">Register</a>|<a href="<?php echo site_url(); ?>/wp-login.php?action=lostpassword" target="_blank">Lost your password?</a></p></div>
			</div>
			<script>
				const get_captcha = ele=>fetch("<?php echo rest_url('sakura/v1/captcha/create')?>")
				.then(response=>response.json())
				.then(json=>{
					ele.src = json['data'];
					document.querySelector("input[name='timestamp']").value = json["time"];
                	document.querySelector("input[name='id']").value = json["id"];
				}),
					captcha = document.getElementById("captchaimg");
				captcha && captcha.addEventListener("click",e=>get_captcha(e.target));
				captcha && get_captcha(captcha);
			</script>
		<?php }else{ echo Exuser_center(); } ?>
		</main><!-- #main -->
	</div><!-- #primary -->
<?php
get_footer();
