<?php
// 如果需要密码则直接返回
if (post_password_required()) {
    return;
}

// 定义辅助函数生成表情面板
function get_smilies_panel() {
    $smilies_list = iro_opt('smilies_list');
    if (!$smilies_list) {
        return '';
    }
    
    $bilibili_smilies = $tieba_smilies = $menhera_smilies = $custom_smilies = '';
    $bilibili_push_smilies = $tieba_push_smilies = $menhera_push_smilies = $custom_push_smilies = '';

    if (in_array('bilibili', $smilies_list)) {
        $bilibili_smilies = '<th class="bili-bar">bilibili~</th>';
        $bilibili_push_smilies = '<div class="bili-container motion-container" style="display:none;">' . push_bili_smilies() . '</div>';
    }
    if (in_array('tieba', $smilies_list)) {
        $tieba_smilies = '<th class="tieba-bar">Tieba</th>';
        $tieba_push_smilies = '<div class="tieba-container motion-container" style="display:none;">' . push_tieba_smilies() . '</div>';
    }
    if (in_array('yanwenzi', $smilies_list)) {
        $menhera_smilies = '<th class="menhera-bar">(=・ω・=)</th>';
        $menhera_push_smilies = '<div class="menhera-container motion-container" style="display:none;">' . push_emoji_panel() . '</div>';
    }
    if (in_array('custom', $smilies_list)) {
        $custom_smilies = '<th class="custom-bar"> ' . iro_opt('smilies_name') . ' </th>';
        $custom_push_smilies = '<div class="custom-container motion-container" style="display:none;">' . push_custom_smilies() . '</div>';
    }
    // 根据第一个选项设置默认展示
    switch ($smilies_list[0]) {
        case "bilibili":
            $bilibili_smilies = '<th class="bili-bar on-hover">bilibili~</th>';
            $bilibili_push_smilies = '<div class="bili-container motion-container" style="display:block;">' . push_bili_smilies() . '</div>';
            break;
        case "tieba":
            $tieba_smilies = '<th class="tieba-bar on-hover">Tieba</th>';
            $tieba_push_smilies = '<div class="tieba-container motion-container" style="display:block;">' . push_tieba_smilies() . '</div>';
            break;
        case "yanwenzi":
            $menhera_smilies = '<th class="menhera-bar on-hover">(=・ω・=)</th>';
            $menhera_push_smilies = '<div class="menhera-container motion-container" style="display:block;">' . push_emoji_panel() . '</div>';
            break;
        case "custom":
            $custom_smilies = '<th class="custom-bar on-hover"> ' . iro_opt('smilies_name') . ' </th>';
            $custom_push_smilies = '<div class="custom-container motion-container" style="display:block;">' . push_custom_smilies() . '</div>';
            break;
    }
    return '<div id="emotion-toggle" class="no-select" onclick="openEmoji()">
                <i class="fa-regular fa-face-kiss-wink-heart"></i>
            </div>
            <div class="emotion-box no-select">
                <div class="emotion-header no-select">' . __("Woooooow ヾ(≧∇≦*)ゝ", "sakurairo") . '</div>
                <table class="motion-switcher-table">
                    <tr>' .
                        $bilibili_smilies .
                        $tieba_smilies .
                        $menhera_smilies .
                        $custom_smilies .
                    '</tr>
                </table>' .
                $bilibili_push_smilies .
                $tieba_push_smilies .
                $menhera_push_smilies .
                $custom_push_smilies .
            '</div>';
}
?>

<?php if (comments_open()) : ?>
<section id="comments" class="comments">
    <!-- 评论区域标题及折叠通知 -->
    <div class="commentwrap comments-hidden<?php echo iro_opt('comment_area') == 'fold' ? ' comments-fold' : ''; ?>">
        <div class="notification">
            <i class="fa-regular fa-comment"></i>
            <?php _e('view comments', 'sakurairo'); /*查看评论*/ ?> -
            <span class="noticom"><?php comments_number('NOTHING', '1' . __(" comment", "sakurairo"), '%' . __(" comments", "sakurairo")); ?></span>
        </div>
    </div>
    
    <div class="comments-main">
        <h3 id="comments-list-title">
            Comments <span class="noticom"><?php comments_number('NOTHING', '1' . __(" comment", "sakurairo"), '%' . __(" comments", "sakurairo")); ?></span>
        </h3>
        <div id="loading-comments"><span></span></div>
        
        <?php if (have_comments()) : ?>
            <ul class="commentwrap">
                <?php wp_list_comments(array(
                    "type" => "comment",
                    "callback" => "akina_comment_format",
                    "reverse_top_level" => get_option('comment_order') == 'asc' ? null : true
                )); ?>
            </ul>
            <nav id="comments-navi">
                <?php paginate_comments_links('prev_text=« Older&next_text=Newer »'); ?>
            </nav>
        <?php else : ?>
            <?php if (comments_open()) : ?>
                <div class="commentwrap">
                    <div class="notification-hidden">
                        <i class="fa-regular fa-comment"></i>
                        <?php _e('no comment', 'sakurairo'); /*暂无评论*/ ?>
                    </div>
                </div>
            <?php endif; ?>
        <?php endif; ?>

        <?php
        if (comments_open()) {
			if (iro_opt('not_robot')) {
				include_once('inc/classes/Captcha.php');
				$img = new Sakura\API\Captcha;
				$test = $img->create_captcha_img();

				$captcha_url = rest_url('sakura/v1/captcha/create');
			
				$robot_comments = '
					<label for="captcha" class="comment-captcha">
						<img id="captchaimg" width="120" height="40" src="' . htmlspecialchars($test['data'], ENT_QUOTES, 'UTF-8') . '">
						<input type="text" name="yzm" id="yzm" class="input" value="" size="20" tabindex="4" placeholder="请输入验证码">
						<input type="hidden" name="timestamp" value="' . htmlspecialchars($test['time'], ENT_QUOTES, 'UTF-8') . '">
						<input type="hidden" name="id" value="' . htmlspecialchars($test['id'], ENT_QUOTES, 'UTF-8') . '">
					</label>
				<script>
					const captchaimg = document.getElementById("captchaimg");
					if (captchaimg) {
						captchaimg.addEventListener("click", function (e) {
							fetch("' . addslashes($captcha_url) . '")
								.then(resp => resp.json())
								.then(json => {
									e.target.src = json["data"];
									document.querySelector("input[name=\'timestamp\']").value = json["time"];
									document.querySelector("input[name=\'id\']").value = json["id"];
								})
								.catch(error => console.error("获取验证码失败:", error));
						});
					}
				</script>';
			} else {
				$robot_comments = null;
			}
            $private_ms = iro_opt('comment_private_message')
                ? '<label class="siren-checkbox-label"><input class="siren-checkbox-radio" type="checkbox" name="is-private"><span class="siren-is-private-checkbox siren-checkbox-radioInput"></span>' . __('Comment in private', 'sakurairo') . '</label>'
                : '';
            $mail_notify = iro_opt('mail_notify')
                ? '<label class="siren-checkbox-label"><input class="siren-checkbox-radio" type="checkbox" name="mail-notify"><span class="siren-mail-notify-checkbox siren-checkbox-radioInput"></span>' . __('Comment reply notify', 'sakurairo') . '</label>'
                : '';
            // 调用辅助函数生成表情面板
            $smilies_panel = get_smilies_panel();

            $args = array(
                'id_form'           => 'commentform',
                'id_submit'         => 'submit',
                'title_reply'       => '',
                'title_reply_to'    => '<div class="graybar"><i class="fa-regular fa-comment"></i>' . __('Leave a Reply to', 'sakurairo') . ' %s</div>',
                'cancel_reply_link' => __('Cancel Reply', 'sakurairo'),
                'label_submit'      => esc_attr(iro_opt('comment_submit_button_text')),
                'comment_field'     => '<p style="font-style:italic"><a href="https://segmentfault.com/markdown" target="_blank">
                                            <i class="fa-brands fa-markdown" style="color:var(--article-theme-highlight,var(--theme-skin-matching));"></i>
                                        </a> Markdown Supported while <i class="fa-solid fa-code"></i> Forbidden</p>
                                        <div class="comment-textarea">
                                            <textarea placeholder="' . esc_attr(iro_opt('comment_placeholder_text')) . '" name="comment" class="commentbody" id="comment" rows="5" tabindex="4"></textarea>
                                            <label class="input-label">' . esc_html(iro_opt('comment_placeholder_text')) . '</label>
                                        </div>
                                        <div id="upload-img-show"></div>',
                'submit_button'     => '<div class="form-submit">
                                            <input name="submit" type="submit" id="submit" class="submit" value=" ' . esc_attr(iro_opt('comment_submit_button_text')) . ' ">' . $smilies_panel . '
                                        </div>',
                'comment_notes_after'  => '',
                'comment_notes_before' => '',
                'fields'            => apply_filters('comment_form_default_fields', array(
                    'avatar' => '<div class="cmt-info-container"><div class="comment-user-avatar">
                                    <img alt="comment_user_avatar" src="' . iro_opt('vision_resource_basepath', 'https://s.nmxc.ltd/sakurairo_vision/@2.7/') . 'basic/avatar.jpeg">
                                    <div class="socila-check qq-check"><i class="fa-brands fa-qq"></i></div>
                                    <div class="socila-check gravatar-check"><i class="fa-regular fa-face-smile-wink"></i></div>
                                 </div>',
                    'author' => '<div class="popup cmt-popup cmt-author">
                                    <input type="text" placeholder="' . __("Nickname or QQ number", "sakurairo") . ' ' . ($req ? '(' . __("Name* ", "sakurairo") . ')' : '') . '" name="author" id="author" value="' . esc_attr($comment_author) . '" size="22" autocomplete="off" tabindex="1" ' . ($req ? "aria-required='true'" : '') . ' />
                                    <span class="popuptext" style="margin-left: -115px;width: 230px;">' . __("Auto pull nickname and avatar with a QQ num. entered", "sakurairo") . '</span>
                                 </div>',
                    'email'  => '<div class="popup cmt-popup">
                                    <input type="text" placeholder="' . __("email", "sakurairo") . ' ' . ($req ? '(' . __("Must* ", "sakurairo") . ')' : '') . '" name="email" id="email" value="' . esc_attr($comment_author_email) . '" size="22" tabindex="1" autocomplete="off" ' . ($req ? "aria-required='true'" : '') . ' />
                                    <span class="popuptext" style="margin-left: -65px;width: 130px;">' . __("You will receive notification by email", "sakurairo") . '</span>
                                 </div>',
                    'url'    => '<div class="popup cmt-popup">
                                    <input type="text" placeholder="' . __("Site", "sakurairo") . '" name="url" id="url" value="' . esc_attr($comment_author_url) . '" size="22" autocomplete="off" tabindex="1" />
                                    <span class="popuptext" style="margin-left: -55px;width: 110px;">' . __("Advertisement is forbidden 😀", "sakurairo") . '</span>
                                 </div></div>',
                    'qq'     => '<input type="text" placeholder="QQ" name="new_field_qq" id="qq" value="' . esc_attr($comment_author_url) . '" style="display:none" autocomplete="off"/><!--此栏不可见-->',
					'checks' => '<div class="comment-checks">' . $robot_comments . $private_ms . $mail_notify ,//此处不闭合，和保存信息在一层级一起闭合
                ))
            );

			function comment_cookies_check_lable($field) {
				$field = '
							<label class="siren-checkbox-label">
								<input class="siren-checkbox-radio id="wp-comment-cookies-consent" name="wp-comment-cookies-consent" type="checkbox" value="yes">
								<span class="siren-mail-notify-checkbox siren-checkbox-radioInput"></span>
								' . __('Save your private info', 'sakurairo') . '
							</label></div>
						 ';
				return $field;
			}
			add_filter('comment_form_field_cookies', 'comment_cookies_check_lable');

            comment_form($args);
        }
        ?>

    </div>
    
    <!-- 评论面板拖拽与呼出脚本 -->
    <script>
    function openEmoji() {
        const toggleBtn = document.getElementById('emotion-toggle');
        const emojiPanel = document.querySelector('.emotion-box');
        if (!emojiPanel) return;

        const dragged = emojiPanel.getAttribute('dragged');
        if (!dragged) {
            const btnRect = toggleBtn.getBoundingClientRect();
            const panelWidth = emojiPanel.offsetWidth;
            const panelHeight = emojiPanel.offsetHeight;
            let leftPos = btnRect.left + window.scrollX + (btnRect.width / 2) - (panelWidth / 2);
            let topPos = btnRect.bottom + window.scrollY;
            if (topPos + panelHeight > window.innerHeight + window.scrollY) {
                topPos = btnRect.top - panelHeight + window.scrollY;
            }
            emojiPanel.style.left = `${leftPos}px`;
            emojiPanel.style.top = `${topPos}px`;
        }
        emojiPanel.classList.toggle('open');
        if (!emojiPanel.hasAttribute('initialized')) {
            initEmojiPanel();
            emojiPanel.setAttribute('initialized', 'true');
        }
    }

    function initEmojiPanel() {
        const emojiPanel = document.querySelector('.emotion-box');
        const header = document.querySelector('.emotion-header');
        if (!emojiPanel || !header) return;

        let offsetX = 0, offsetY = 0, isDragging = false;

        function startDrag(e) {
            isDragging = true;
            const event = e.touches ? e.touches[0] : e;
            offsetX = event.clientX - emojiPanel.offsetLeft;
            offsetY = event.clientY - emojiPanel.offsetTop;
            emojiPanel.style.transition = "none";
            if (e.touches) e.preventDefault();
        }
        function moveDrag(e) {
            if (!isDragging) return;
            const event = e.touches ? e.touches[0] : e;
            const left = event.clientX - offsetX;
            const top = event.clientY - offsetY;
            emojiPanel.style.left = `${left}px`;
            emojiPanel.style.top = `${top}px`;
            emojiPanel.setAttribute('dragged', 'true');
            if (e.touches) e.preventDefault();
        }
        function endDrag() {
            isDragging = false;
            emojiPanel.style.transition = "transform 0.3s ease-in-out";
        }

        header.addEventListener('mousedown', startDrag);
        document.addEventListener('mousemove', moveDrag);
        document.addEventListener('mouseup', endDrag);
        header.addEventListener('touchstart', startDrag, { passive: false });
        document.addEventListener('touchmove', moveDrag, { passive: false });
        document.addEventListener('touchend', endDrag);
        document.addEventListener('click', function (e) {
            const toggleBtn = document.getElementById('emotion-toggle');
            if (!emojiPanel.contains(e.target) && !toggleBtn.contains(e.target)) {
                emojiPanel.classList.remove('open');
            }
        });
    }
    </script>
</section>
<?php endif; ?>
