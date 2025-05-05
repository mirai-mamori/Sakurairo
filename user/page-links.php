<?php 
/*
  Template Name: Friendly Links Template
*/
get_header(); 
?>

<div class="links-page">
<style>
/* 基础变量 - 使用类选择器作用域 */
.links-page {
    --link-card-bg: rgba(255, 255, 255, 0.5);
    --link-card-bg-hover: rgba(255, 255, 255, 0.8);
    --link-card-border: 1.5px solid #FFFFFF;
    --link-card-border-radius: 10px;
    --link-card-text: #505050;
    --link-card-shadow: #e8e8e8;
    --link-card-shadow-hover: #e8e8e8;
    --form-bg: #ffffffe6;
    --form-input-border: #ddd;
    --modal-bg: rgba(0, 0, 0, 0.1);
}

/* 标题样式 */
span.linkss-title {
    font-size: 30px;
    text-align: center;
    display: block;
    margin: 6.5% 0 7.5%;
    letter-spacing: 2px;
    font-weight: var(--global-font-weight);
}

/* 布局样式 */
.links {
    margin-bottom: 80px;
}

.links ul {
    margin-top: 50px;
    width: 100%;
    display: inline-flex;
    gap: 20px;
    flex-wrap: wrap;
    cursor: auto;
}

/* 链接卡片基础样式 */
.links ul li {
    width: 23.1%;
    float: left;
    box-shadow: 0 1px 30px -4px var(--link-card-shadow);
    background: var(--link-card-bg);
    padding: 12px;
    position: relative;
    overflow: hidden;
    border-radius: var(--link-card-border-radius);
    border: var(--link-card-border);
    transition: all 0.3s ease;
}

/* 链接卡片悬停效果 */
.links ul li:hover {
    box-shadow: 0 1px 20px 10px var(--link-card-shadow-hover);
    background: var(--link-card-bg-hover);
}

.links ul li:hover:before {
    width: 180%;
}

/* 卡片内部图片样式 */
.link-avatar-wrapper {
    position: relative;
    display: inline-block;
    margin: 3px 3px 0;
}

.links ul li img {
    float: left;
    opacity: 1;
    transform: rotate(0);
    -webkit-transform: rotate(0);
    width: 90px;
    height: 90px;
    border-radius: 100%;
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
    transition: transform 0.5s ease;
}

/* 链接状态指示器 */
.links ul li.link-status-success .link-avatar-wrapper::after,
.links ul li.link-status-failure .link-avatar-wrapper::after {
    content: '';
    position: absolute;
    bottom: 5px;
    right: 5px;
    width: 14px;
    height: 14px;
    border-radius: 50%;
    z-index: 2;
    border: 2px solid #fff;
}

.links ul li.link-status-success .link-avatar-wrapper::after {
    background-color: #46b450;
    box-shadow: 0 0 5px rgba(70, 180, 80, 0.7);
}

.links ul li.link-status-failure .link-avatar-wrapper::after {
    background-color: #dc3232;
    box-shadow: 0 0 5px rgba(220, 50, 50, 0.7);
}

.links ul li:hover img {
    transform: rotate(360deg);
    -webkit-transform: rotate(360deg);
}

/* 标题栏样式 */
.link-title {
	font-size: 20px;
	font-weight: 600;
	color: var(--theme-skin);
	margin: 50px 0 10px;
	position: relative;
	display: inline-block;
}

.link-title::after {
	content: '';
	position: absolute;
	bottom: -2px;
	right: 0;
	width: 70%;
	height: 0.7em;
	background-color: var(--theme-skin-matching);
	opacity: 0.4;
	z-index: 0;
	border-radius: 30px;
	transition: all 0.3s ease;
}

.link-title:hover::after {
	width: 85%;
	opacity: 0.7;
}

/* 站点名称样式 */
span.sitename {
    font-size: 20px;
	margin: 8px 8px 0 8px;
    color: var(--link-card-text);
    display: block;
    transition: all 0.4s ease-in-out;
    overflow: hidden;
    text-overflow: ellipsis;
    white-space: nowrap;
    font-weight: var(--global-font-weight);
}

/* 链接描述样式 */
.linkdes {
    font-size: 14px;
    margin-left: 8px;
    margin-right: 8px;
    text-overflow: ellipsis;
    color: var(--link-card-text);
    overflow: hidden;
    white-space: nowrap;
    line-height: 30px;
    transition: all 0.4s ease-in-out;
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

/* 提交按钮基础样式和禁用状态 */
.submit-link-btn {
    background-color: var(--theme-skin-matching);
    color: #fff;
    border: 2px solid var(--theme-skin-matching);
    border-radius: 20px;
    padding: 6px 15px;
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

.submit-link-btn:disabled {
    background-color: #cccccc;
    cursor: not-allowed;
    opacity: 0.7;
}

/* ============ 响应式设计 ============ */
/* 平板设备 */
@media (max-width: 1024px) and (min-width: 861px) {
    .links ul li {
        width: 31.7%;
    }
}

/* 移动设备 */
@media (max-width: 860px) {
    .links ul li {
        width: 47%;
        max-width: 860px;
    }
    
    .links ul li:hover {
        width: 47%;
    }
    
    .links ul li:before {
        display: none;
    }
    
    span.linkss-title {
        font-size: 24px;
        margin: 10% 0 8%;
    }
}

/* 小型移动设备 */
@media (max-width: 480px) {
    .submit-link-btn {
        padding: 5px 12px;
        font-size: 12px;
    }
    
    .link-modal-content {
        width: 95%;
        padding: 15px;
    }
}

/* ============ 深色模式 ============ */
body.dark .links-page {
    --link-card-bg: var(--dark-bg-secondary);
    --link-card-bg-hover: var(--dark-bg-hover);
    --link-card-border: 1.5px solid var(--dark-border-color);
    --link-card-text: var(--dark-text-primary);
    --form-bg: #1a1a1ae6;
    --form-input-border: var(--dark-border-color);
}

body.dark .links ul li {
    box-shadow: var(--dark-shadow-normal);
}

body.dark .links ul li:hover {
    box-shadow: 0 1px 30px -2px var(--friend-link-title) !important;
}

body.dark .links ul li img {
    box-shadow: 0 4px 12px var(--dark-header-shadow);
}

body.dark .link-title {
    color: var(--dark-text-secondary) !important;
}

body.dark .link-limit-notice {
    background-color: rgba(255, 193, 7, 0.2);
    color: #ffc107;
    border-left-color: #ffc107;
}

body.dark .submit-link-btn:hover,
body.dark .link-form-submit:hover {
    background-color: var(--dark-bg-primary);
}

/* ============ 表单样式 ============ */
/* 标题容器 */
.title-container {
    display: flex;
    align-items: center;
    justify-content: center;
    position: relative;
    margin: 3% 0 1.5%;
    gap: 15px;
}

.linkss-title {
    margin: 0;
}

/* 模态框样式 */
.link-modal {
    display: none;
    position: fixed;
    align-items: center;
    z-index: 9999;
    left: 0;
    top: 0;
    width: 100vw;
    height: 100vh;
    background-color: var(--modal-bg);
    backdrop-filter: blur(10px);
    -webkit-backdrop-filter: blur(10px);
}

.link-modal-content {
    display: block;
    background-color: var(--form-bg);
    margin: 5% auto;
    padding: 25px;
    border-radius: 10px;
    max-width: 500px;
    width: 80%;
    height: 80%;
    box-shadow: 0 5px 20px rgba(0, 0, 0, 0.2);
    position: relative;
    animation: modalFadeIn 0.3s ease;
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

/* 表单元素样式 */
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
    border: 1px solid var(--form-input-border);
    border-radius: 6px;
    font-size: 14px;
    background-color: rgba(255, 255, 255, 0.9);
    transition: all 0.3s ease;
}

body.dark .link-form-group input,
body.dark .link-form-group textarea,
body.dark .captcha-container input {
    background-color: var(--dark-bg-primary);
    color: var(--dark-text-primary);
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

/* 表单状态提示 */
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

/* 验证码容器 */
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

<?php
$link_align = iro_opt('friend_link_align', 'left');
if ($link_align == 'right' || $link_align == 'center') {
	echo "span.sitename {
		margin-bottom: 0px;
		margin-top: 8px;
	}
	li.link-item {
		text-align: {$link_align};
	}
	.links ul li img {
		float: none;
	}
	.link-avatar-wrapper {
		display: block;
		text-align: {$link_align};
		margin: 0 auto;
	}";
	
	if ($link_align == 'center') {
		echo ".link-avatar-wrapper {
			width: fit-content;
		}";
	}
}
?>
</style>

	<?php while (have_posts()) : the_post(); ?>
		<?php $post = get_post(); ?>
		<?php 
		$friend_link_form = (iro_opt('friend_link_form',true)) ?? true;
		// 检查待审核链接数量是否达到上限
		$pending_links_limit_reached = function_exists('sakurairo_check_pending_links_limit') ? sakurairo_check_pending_links_limit() : false;

			function submit_button_struct($pending_links_limit_reached) { // 按钮结构?>
				<button class="submit-link-btn" id="openLinkModal" <?php echo $pending_links_limit_reached ? 'disabled' : ''; ?> 
				<?php if (iro_opt('patternimg') && get_post_thumbnail_id(get_the_ID())) { // 只有当真正有头图时才应用动画效果 ?>
					style="display:block;animation:homepage-load-animation 2s;"
				<?php } ?>>
					<?php _e('Submit Link', 'sakurairo'); ?>
				</button>
			<?php }

			function too_many_pending_links_notice() { // 提示结构?>
				<div class="link-limit-notice">
					<?php _e('Sorry, we are not accepting new link submissions at this time due to backlog. Please try again later.', 'sakurairo'); ?>
				</div>
			<?php } ?>

		<?php if (!iro_opt('patternimg') || !get_post_thumbnail_id(get_the_ID())) { //没有头图?>

			<div class="title-container">
				<span class="linkss-title"><?php echo esc_html(get_the_title()); ?></span>
				<?php if ($friend_link_form) {
                    submit_button_struct($pending_links_limit_reached);
                } ?>
			</div>
            
			<?php if ($friend_link_form && $pending_links_limit_reached) { 
                too_many_pending_links_notice(); 
            }
			
		 	} else { // 有头图

			if ($friend_link_form) {
				submit_button_struct($pending_links_limit_reached);
				if ($pending_links_limit_reached) { too_many_pending_links_notice(); }
			}

		} ?>
		
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

<?php if ($friend_link_form) : //表单开始?>

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
					<img id="captchaImg" src="" alt="验证码" title="点击刷新验证码">
					<input type="text" id="yzm" name="yzm" placeholder="<?php _e('Verification Code', 'sakurairo'); ?>" required>
					<input type="hidden" name="timestamp" id="timestamp" value="">
					<input type="hidden" name="id" id="captchaId" value="">
				</div>
				
				<?php wp_nonce_field('link_submission_nonce', 'link_submission_nonce'); ?>
				<button type="submit" class="link-form-submit"><i class="fa-solid fa-paper-plane"></i></button>
			</form>
		</div>
	</div>
<?php endif; // 表单结束?>
</div><!-- .links-page 结束 -->
<?php
get_footer();
?>
