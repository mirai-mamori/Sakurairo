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
		background-color: var(--theme-skin, #FE9600);
		color: white;
		border: none;
		border-radius: 20px;
		padding: 6px 15px;
		margin-left: 15px;
		font-size: 14px;
		cursor: pointer;
		transition: all 0.3s ease;
		box-shadow: 0 1px 6px rgba(0, 0, 0, 0.1);
	}

	.submit-link-btn:hover {
		background-color: var(--theme-skin-matching, #FE9600);
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
		background-color: rgba(0, 0, 0, 0.5);
		backdrop-filter: blur(5px);
		-webkit-backdrop-filter: blur(5px);
	}

	.link-modal-content {
		background-color: white;
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
		background-color: var(--dark-bg-secondary);
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
		color: var(--theme-skin, #FE9600);
	}

	.link-form-group {
		margin-bottom: 20px;
	}

	.link-form-group label {
		display: block;
		margin-bottom: 8px;
		font-weight: 500;
	}

	.link-form-group input,
	.link-form-group textarea {
		width: 100%;
		padding: 10px 12px;
		border: 1px solid #ddd;
		border-radius: 6px;
		font-size: 14px;
		background-color: rgba(255, 255, 255, 0.9);
		transition: all 0.3s ease;
	}

	body.dark .link-form-group input,
	body.dark .link-form-group textarea {
		background-color: var(--dark-bg-primary);
		border-color: var(--dark-border-color);
		color: var(--dark-text-primary);
	}

	.link-form-group input:focus,
	.link-form-group textarea:focus {
		border-color: var(--theme-skin, #FE9600);
		box-shadow: 0 0 5px rgba(254, 150, 0, 0.3);
		outline: none;
	}

	.link-form-submit {
		background-color: var(--theme-skin, #FE9600);
		color: white;
		border: none;
		border-radius: 6px;
		padding: 10px 20px;
		font-size: 16px;
		cursor: pointer;
		transition: all 0.3s ease;
		display: block;
		width: 100%;
	}

	.link-form-submit:hover {
		background-color: var(--theme-skin-matching, #FE9600);
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
		<?php if (!iro_opt('patternimg') || !get_post_thumbnail_id(get_the_ID())) : ?>
			<div class="title-container">
				<span class="linkss-title"><?php echo esc_html(get_the_title()); ?></span>
				<button class="submit-link-btn" id="openLinkModal"><?php _e('Submit Link', 'sakurairo'); ?></button>
			</div>
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
			<h2><?php _e('Submit Your Link', 'sakurairo'); ?></h2>
			<p><?php _e('Please fill out the form below to submit your website link.', 'sakurairo'); ?></p>
			
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
					<img id="captchaImg" src="">
					<input type="text" id="yzm" name="yzm" placeholder="<?php _e('Verification Code', 'sakurairo'); ?>" required>
					<input type="hidden" name="timestamp" id="timestamp">
					<input type="hidden" name="id" id="captchaId">
				</div>
				
				<?php wp_nonce_field('link_submission_nonce', 'link_submission_nonce'); ?>
				<button type="submit" class="link-form-submit"><?php _e('Submit', 'sakurairo'); ?></button>
			</form>
		</div>
	</div>

<script>
<?php $captcha_url = rest_url('sakura/v1/captcha/create'); ?>
document.addEventListener('DOMContentLoaded', function() {
	let ajaxurl = '/wp-admin/admin-ajax.php'
	// Modal functionality
	let modal = document.getElementById('linkModal');
	let openBtn = document.getElementById('openLinkModal');
	let closeBtn = document.querySelector('.link-modal-close');
	let form = document.getElementById('linkSubmissionForm');
	let statusDiv = document.getElementById('formStatus');
	let captchaImg = document.getElementById('captchaImg');
	let timestampInput = document.getElementById('timestamp');
	let captchaIdInput = document.getElementById('captchaId');
	
	// Open modal
	openBtn.addEventListener('click', function() {
		modal.style.display = 'block';
		document.body.style.overflow = 'hidden';
		loadCaptcha();
	});

	captchaImg.addEventListener('click',loadCaptcha);

	function loadCaptcha() {
		fetch("<?php echo $captcha_url //验证码接口?>")
			.then(resp => resp.json())
			.then(json => {
				captchaImg.src = json["data"];
				timestampInput.value = json["time"];
				captchaIdInput.value = json["id"];
			})
			.catch(error => console.error("获取验证码失败:", error));
	};
	
	// Close modal
	closeBtn.addEventListener('click', function() {
		modal.style.display = 'none';
		document.body.style.overflow = 'auto';
	});
	
	// Close modal if clicked outside
	window.addEventListener('click', function(event) {
		if (event.target === modal) {
			modal.style.display = 'none';
			document.body.style.overflow = 'auto';
		}
	});
	
	// Form submission
	form.addEventListener('submit', function(event) {
		event.preventDefault();
		
		// Validate form
		let siteName = document.getElementById('siteName').value.trim();
		let siteUrl = document.getElementById('siteUrl').value.trim();
		let siteDescription = document.getElementById('siteDescription').value.trim();
		let siteImage = document.getElementById('siteImage').value.trim();
		let contactEmail = document.getElementById('contactEmail').value.trim();
		let captchaCode = document.getElementById('yzm').value.trim();
		
		// Basic validation
		if (!siteName || !siteUrl || !siteDescription || !siteImage || !contactEmail || !captchaCode) {
			showStatus('error', '<?php _e('Please fill in all required fields', 'sakurairo'); ?>');
			return;
		}
		
		// URL validation
		let urlPattern = /^(https?:\/\/)?(www\.)?[-a-zA-Z0-9@:%._\+~#=]{1,256}\.[a-zA-Z0-9()]{1,6}\b([-a-zA-Z0-9()@:%_\+.~#?&//=]*)$/;
		if (!urlPattern.test(siteUrl)) {
			showStatus('error', '<?php _e('Please enter a valid URL', 'sakurairo'); ?>');
			return;
		}
		
		// Email validation
		let emailPattern = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
		if (!emailPattern.test(contactEmail)) {
			showStatus('error', '<?php _e('Please enter a valid email address', 'sakurairo'); ?>');
			return;
		}
		
		// Prepare form data for submission
		let formData = new FormData();
		formData.append('action', 'link_submission');
		formData.append('siteName', siteName);
		formData.append('siteUrl', siteUrl);
		formData.append('siteDescription', siteDescription);
		formData.append('siteImage', siteImage);
		formData.append('contactEmail', contactEmail);
		formData.append('yzm', captchaCode);
		formData.append('timestamp', timestampInput.value);
		formData.append('id', captchaIdInput.value);
		formData.append('link_submission_nonce', document.getElementById('link_submission_nonce').value);
		
		// Disable submit button
		let submitButton = form.querySelector('button[type="submit"]');
		submitButton.disabled = true;
		submitButton.innerText = '<?php _e('Submitting...', 'sakurairo'); ?>';
		
		// Send form data via AJAX
		fetch(ajaxurl, {
			method: 'POST',
			body: formData,
			credentials: 'same-origin'
		})
		.then(response => response.json())
		.then(data => {
			if (data.success) {
				showStatus('success', data.data.message);
				form.reset();
				loadCaptcha(); // Reload captcha after successful submission
				setTimeout(() => {
					modal.style.display = 'none';
					document.body.style.overflow = 'auto';
				}, 3000);
			} else {
				showStatus('error', data.data.message);
				loadCaptcha(); // Reload captcha after failed submission
			}
		})
		.catch(error => {
			console.error('Error:', error);
			showStatus('error', '<?php _e('An error occurred. Please try again later.', 'sakurairo'); ?>');
			loadCaptcha();
		})
		.finally(() => {
			submitButton.disabled = false;
			submitButton.innerText = '<?php _e('Submit', 'sakurairo'); ?>';
		});
	});
	
	// Show status message
	function showStatus(type, message) {
		statusDiv.className = 'form-status ' + (type === 'success' ? 'success-msg' : 'error-msg');
		statusDiv.textContent = message;
		statusDiv.style.display = 'block';
		
		// Auto hide after 5 seconds
		setTimeout(() => {
			statusDiv.style.display = 'none';
		}, 5000);
	}
});
</script>

<?php
get_footer();
?>
