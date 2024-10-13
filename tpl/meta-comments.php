<?php function render_meta_comments(){
	?><span class="comments-number">
	<i class="fa-regular fa-comment"></i> 
	<?php comments_popup_link(__("NOTHING", "sakurairo"), __("1 Comment", "sakurairo")/*条评论*/, '% ' . __("Comments", "sakurairo")/*条评论*/, '', __("Comment Closed", "sakurairo")
		/**评论关闭 */
	); ?></span><?php
}