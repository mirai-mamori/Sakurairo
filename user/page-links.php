<?php 

/*
 Template Name: Friendly Links Template
 */

get_header(); 

?>
<style>

	span.linkss-title {
		font-size: 30px;
		text-align: center;
		display: block;
		margin: 6.5% 0 7.5%;
		letter-spacing: 2px;
		font-weight: var(--global-font-weight);
	}

	.links {
		margin-bottom: 80px;
	}

	.links ul {
		margin-top: 50px;
		width: 100%;
		display: inline-block;
	}

	.links ul li {
		width: 22%;
		float: left;
		box-shadow: 0 1px 30px -4px var(--friend-link-shadow);
		background: rgba(255, 255, 255, 0.5);
		padding: 12px;
		margin: 12px;
		position: relative;
		overflow: hidden;
		border-radius: 10px;
		border: 1.5px solid #FFFFFF;
	}

	.links ul li:hover {
		box-shadow: 0 1px 20px 10px var(--friend-link-shadow);
		background: rgba(255, 255, 255, 0.8);
	}

	.links ul li:hover:before {
		width: 180%;
	}

	.links ul li img {
		float: left;
		padding: 1px;
		opacity: 1;
		transform: rotate(0);
		-webkit-transform: rotate(0);
		margin: 3px 3px 0;
		width: 90px;
		height: 90px;
		border-radius: 100%;
		box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
	}

	.links ul li:hover img {
		transform: rotate(360deg);
		-webkit-transform: rotate(360deg);
	}

	.link-title {
		font-weight: 600;
		color: #6D6D6D;
		padding-left: 10px;
		border-left: none;
		border-color: var(--theme-skin);
		margin: 50px 0 10px;
		text-underline-offset: 10px;
		text-decoration: underline solid var(--friend-link-title, #ffeeeB);
	}

	span.sitename {
		font-size: 20px;
		margin-top: 84px;
		margin-left: 8px;
		margin-right: 8px;
		color: #505050;
		padding-bottom: 10px;
		display: block;
		transition: all 0.4s ease-in-out;
		-webkit-transition: all 0.4s ease-in-out;
		overflow: hidden;
		text-overflow: ellipsis;
		white-space: nowrap;
		font-weight: var(--global-font-weight);
		text-underline-offset: 10px;
		text-decoration: underline wavy var(--friend-link-title, #ffeeeB);
	}

	.linkdes {
		font-size: 14px;
		margin-left: 8px;
		margin-right: 8px;
		text-overflow: ellipsis;
		color: #505050;
		overflow: hidden;
		white-space: nowrap;
		line-height: 30px;
		transition: all 0.4s ease-in-out;
		-webkit-transition: all 0.4s ease-in-out;
	}

	/* Dark mode styles */
	body.dark .links ul li {
		box-shadow: var(--dark-shadow-normal);
		background: var(--dark-bg-secondary);
		border: 1.5px solid var(--dark-border-color);
	}

	body.dark .links ul li:hover {
		box-shadow: 0 1px 30px -2px var(--friend-link-title) !important;
		background: var(--dark-bg-hover);
	}

	body.dark .links ul li img {
		box-shadow: 0 4px 12px var(--dark-header-shadow);
	}

	body.dark .link-title {
		color: var(--dark-text-secondary) !important;
	}

	body.dark .linkdes,
	body.dark span.sitename {
		color: var(--dark-text-primary);
	}

	/* 链接上限提示横幅样式 */
	.link-limit-notice {
		background-color: #fff3cd;
		color: #856404;
		padding: 12px 20px;
		margin-bottom: 30px;
		border-radius: 6px;
		border-left: 4px solid #ffeeba;
		box-shadow: 0 1px 10px rgba(0, 0, 0, 0.1);
		text-align: center;
		max-width: 800px;
		margin-left: auto;
		margin-right: auto;
	}

	body.dark .link-limit-notice {
		background-color: rgba(255, 193, 7, 0.2);
		color: #ffc107;
		border-left-color: #ffc107;
	}

	.submit-link-btn:disabled {
		background-color: #cccccc;
		cursor: not-allowed;
		opacity: 0.7;
	}

	/* Responsive styles */
	@media (max-width: 860px) {
		.links ul li {
			width: 44%;
			max-width: 860px;
		}

		.links ul li:hover {
			width: 44%;
		}

		.links ul li:before {
			display: none;
		}
	}

	/* Link submission modal styles */
	.title-container {
		display: flex;
		align-items: center;
		justify-content: center;
		position: relative;
		margin: 6.5% 0 7.5%;
	}

	.linkss-title {
		margin: 0;
	}

	.submit-link-btn {
		background-color: var(--theme-skin-matching);
		color: #fff;
		border: 2px solid var(--theme-skin-matching);
		border-radius: 20px;
		padding: 6px 15px;
		margin-left: 15px;
		font-size: 14px;
		cursor: pointer;
		transition: all 0.3s ease;
		box-shadow: 0 1px 6px rgba(0, 0, 0, 0.1);
		font-family: <?php echo esc_attr(iro_opt('global_default_font')); ?>;
	}

	.submit-link-btn:hover {
		background-color: #fff;
		color: var(--theme-skin-matching);
		border: 2px solid var(--theme-skin-matching);
		box-shadow: 0 1px 10px rgba(0, 0, 0, 0.2);
	}

	.link-modal {
		display: none;
		position: fixed;
		z-index: 9999;
		left: 0;
		top: 0;
		width: 100%;
		height: 100%;
		background-color: rgba(0, 0, 0, 0.1);
		backdrop-filter: blur(10px);
		-webkit-backdrop-filter: blur(10px);
	}

	.link-modal-content {
		background-color: #ffffffe6;
		margin: 5% auto;
		padding: 25px;
		border-radius: 10px;
		max-width: 500px;
		width: 80%;
		box-shadow: 0 5px 20px rgba(0, 0, 0, 0.2);
		position: relative;
		animation: modalFadeIn 0.3s ease;
	}

	body.dark .link-modal-content {
		background-color: #1a1a1ae6;
		color: var(--dark-text-primary);
	}

	@keyframes modalFadeIn {
		from {opacity: 0; transform: translateY(-20px);}
		to {opacity: 1; transform: translateY(0);}
	}

	.link-modal-close {
		position: absolute;
		top: 15px;
		right: 20px;
		font-size: 24px;
		cursor: pointer;
		color: #888;
		transition: all 0.2s ease;
	}

	.link-modal-close:hover {
		color: var(--theme-skin);
	}

	.link-form-group {
		margin-bottom: 10px;
	}

	.link-form-group label {
		display: block;
		margin-bottom: 5px;
		font-weight: 500;
	}

	.link-form-group input,
	.link-form-group textarea,
	.captcha-container input {
		width: 100%;
		padding: 7px 12px;
		border: 1px solid #ddd;
		border-radius: 6px;
		font-size: 14px;
		background-color: rgba(255, 255, 255, 0.9);
		transition: all 0.3s ease;
	}

	body.dark .link-form-group input,
	body.dark .link-form-group textarea,
	body.dark .captcha-container input{
		background-color: var(--dark-bg-primary);
		border-color: var(--dark-border-color);
		color: var(--dark-text-primary);
	}

	body.dark .submit-link-btn:hover,
	body.dark .link-form-submit:hover{
		background-color: var(--dark-bg-primary);
	}

	.link-form-group input:focus,
	.link-form-group textarea:focus {
		border-color: var(--theme-skin);
		outline: none;
	}

	.link-form-submit {
		background-color: var(--theme-skin-matching);
		color: #fff;
		border: 3px solid var(--theme-skin-matching);
		border-radius: 30px;
        padding: 14px 29px;
        font-size: 20px;
		cursor: pointer;
		transition: all 0.3s ease;
		display: block;
		box-shadow: none;
		float: right;
	}

	.link-form-submit:hover {
		background-color: #fff;
		color: var(--theme-skin-matching);
		border: 3px solid var(--theme-skin-matching);
	}

	.form-status {
		padding: 10px;
		margin: 10px 0;
		border-radius: 5px;
		display: none;
	}

	.success-msg {
		background-color: rgba(76, 175, 80, 0.2);
		color: #4CAF50;
	}

	.error-msg {
		background-color: rgba(244, 67, 54, 0.2);
		color: #F44336;
	}

	.captcha-container {
		margin-bottom: 20px;
		display: flex;
		align-items: center;
		gap: 10px;
	}
	
	.captcha-container img {
		border-radius: 6px;
		height: 40px;
	}
	
	.captcha-container input {
		flex: 1;
	}
</style>
	<?php while (have_posts()) : the_post(); ?>
		<?php $post = get_post(); ?>
		<?php 
		// 检查待审核链接数量是否达到上限
		$pending_links_limit_reached = function_exists('sakurairo_check_pending_links_limit') ? sakurairo_check_pending_links_limit() : false;
		?>
		<?php if (!iro_opt('patternimg') || !get_post_thumbnail_id(get_the_ID())) : ?>
			<div class="title-container">
				<span class="linkss-title"><?php echo esc_html(get_the_title()); ?></span>
				<button class="submit-link-btn" id="openLinkModal" <?php echo $pending_links_limit_reached ? 'disabled' : ''; ?>>
					<?php _e('Submit Link', 'sakurairo'); ?>
				</button>
			</div>
			<?php if ($pending_links_limit_reached) : ?>
			<div class="link-limit-notice">
				<?php _e('Sorry, we are not accepting new link submissions at this time due to backlog. Please try again later.', 'sakurairo'); ?>
			</div>
			<?php endif; ?>
		<?php endif; ?>
		<article <?php post_class("post-item"); ?>>
			<?php if (iro_opt('article_auto_toc', 'true') && check_title_tags($post->post_content)) : //加载目录 ?>
				<div class="has-toc have-toc"></div>
			<?php endif; ?>
			<div class="entry-content">
				<?php the_content('', true); ?>
			</div>			
			<div class="links">
				<?php echo get_link_items(); ?>
			</div>
		</article>
		<?php get_template_part('layouts/sidebox'); //加载目录容器 ?> 
	<?php endwhile; ?>

	<!-- Link Submission Modal -->
	<div id="linkModal" class="link-modal">
		<div class="link-modal-content">
			<span class="link-modal-close">&times;</span>
			<h2 style="margin: 0;"><?php _e('Submit Your Link', 'sakurairo'); ?></h2>
			<p style="margin-top: 0.3em;"><?php _e('Please fill out the form below to submit your website link.', 'sakurairo'); ?></p>
			
			<div id="formStatus" class="form-status"></div>
			
			<form id="linkSubmissionForm">
				<div class="link-form-group">
					<label for="siteName"><?php _e('Site Name', 'sakurairo'); ?>*</label>
					<input type="text" id="siteName" name="siteName" required>
				</div>
				
				<div class="link-form-group">
					<label for="siteUrl"><?php _e('Site URL', 'sakurairo'); ?>*</label>
					<input type="url" id="siteUrl" name="siteUrl" placeholder="https://" required>
				</div>
				
				<div class="link-form-group">
					<label for="siteDescription"><?php _e('Site Description', 'sakurairo'); ?>*</label>
					<textarea id="siteDescription" name="siteDescription" rows="2" required></textarea>
				</div>
				
				<div class="link-form-group">
					<label for="siteImage"><?php _e('Site Logo/Avatar URL', 'sakurairo'); ?>*</label>
					<input type="url" id="siteImage" name="siteImage" placeholder="https://" required>
				</div>
				
				<div class="link-form-group">
					<label for="contactEmail"><?php _e('Your Email', 'sakurairo'); ?>*</label>
					<input type="email" id="contactEmail" name="contactEmail" required>
				</div>
				
				<div class="captcha-container">
					<img id="captchaImg" src="data:image/svg+xml;base64,PHN2ZyB4bWxucz0iaHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmciIHdpZHRoPSIzMCIgaGVpZ2h0PSIzMCIgdmlld0JveD0iMCAwIDM4IDM4IiBzdHJva2U9IiM2NjYiPjxnIGZpbGw9Im5vbmUiIGZpbGwtcnVsZT0iZXZlbm9kZCI+PGcgdHJhbnNmb3JtPSJ0cmFuc2xhdGUoMSAxKSIgc3Ryb2tlLXdpZHRoPSIyIj48Y2lyY2xlIHN0cm9rZS1vcGFjaXR5PSIuMyIgY3g9IjE4IiBjeT0iMTgiIHI9IjE4Ii8+PHBhdGggZD0iTTM2IDE4YzAtOS45NC04LjA2LTE4LTE4LTE4Ij48YW5pbWF0ZVRyYW5zZm9ybSBhdHRyaWJ1dGVOYW1lPSJ0cmFuc2Zvcm0iIHR5cGU9InJvdGF0ZSIgZnJvbT0iMCAxOCAxOCIgdG89IjM2MCAxOCAxOCIgZHVyPSIxcyIgcmVwZWF0Q291bnQ9ImluZGVmaW5pdGUiLz48L3BhdGg+PC9nPjwvZz48L3N2Zz4=" alt="验证码" title="点击刷新验证码">
					<input type="text" id="yzm" name="yzm" placeholder="<?php _e('Verification Code', 'sakurairo'); ?>" required>
					<input type="hidden" name="timestamp" id="timestamp" value="">
					<input type="hidden" name="id" id="captchaId" value="">
					<input type="hidden" id="captcha-endpoint" value="<?php echo esc_url(rest_url('sakura/v1/captcha/create')); ?>">
				</div>
				
				<?php wp_nonce_field('link_submission_nonce', 'link_submission_nonce'); ?>
				<button type="submit" class="link-form-submit"><i class="fa-solid fa-paper-plane"></i></button>
			</form>
		</div>
	</div>

<!-- 直接引入友情链接JavaScript -->
<script>
    // 定义全局变量
    window.ajaxurl = "<?php echo esc_url(admin_url('admin-ajax.php')); ?>";
    // 设置语言（获取当前WordPress语言）
    window.current_lang = "<?php echo esc_js(str_replace('-', '_', get_locale())); ?>";
    // 验证码API地址 - 确保生成正确的绝对URL
    window.captcha_endpoint = "<?php echo esc_url(home_url('/wp-json/sakura/v1/captcha/create')); ?>";
</script>
<!-- 添加缓存控制，确保加载最新版本的JS -->
<?php global $core_lib_basepath; ?>
<script src="<?php echo esc_url($core_lib_basepath . '/js/link-submission.js?ver=' . IRO_VERSION); ?>"></script>

<?php
get_footer();
?>
