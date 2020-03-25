<?php 
/**
 Template Name: Login
 */

get_header();
?>
	<div id="primary" class="content-area">
		<main id="main" class="site-main" role="main">
		<?php if(!is_user_logged_in()){ ?>
			<div class="ex-login">
				<div class="ex-login-title">
					<p><img src="https://cdn.jsdelivr.net/gh/moezx/cdn@3.2.9/img/Sakura/images/none.png"></p>
				</div>
				<form action="<?php echo home_url(); ?>/wp-login.php" method="post">  
					<p><input type="text" name="log" id="log" value="<?php echo $_POST['log']; ?>" size="25" placeholder="Name" required /></p>
					<p><input type="password" name="pwd" id="pwd" value="<?php echo $_POST['pwd']; ?>" size="25" placeholder="Password" required /></p>
					<p class="forgetmenot"><label for="rememberme"><input name="rememberme" type="checkbox" id="rememberme" value="forever" <?php checked( $rememberme ); ?> /> <?php esc_html_e( 'Remember Me' ); ?></label></p>
					<input type="hidden" name="redirect_to" value="<?php echo $_SERVER['REQUEST_URI']; ?>" />
					<input class="button login-button" name="submit" type="submit" value="登 入">
				</form>
				<div class="ex-new-account" style="padding: 0;"><p>请先注册！Register first, plz!</p><p><a href="<?php echo akina_option('exregister_url') ? akina_option('exregister_url') : bloginfo('url'); ?>" target="_blank">Register</a>|<a href="<?php echo site_url(); ?>/wp-login.php?action=lostpassword" target="_blank">Lost your password?</a></p></div>
			</div>
		<?php }else{ echo Exuser_center(); } ?>
		</main><!-- #main -->
	</div><!-- #primary -->
<?php
get_footer();
