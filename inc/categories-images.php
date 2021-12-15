<?php

define('Z_IMAGE_PLACEHOLDER', get_random_bg_url());

add_action('admin_init', 'z_init');
function z_init() {
	$z_taxonomies = get_taxonomies();
	if (is_array($z_taxonomies)) {
		$zci_options = get_option('zci_options');
		$excluded_taxonomies = $zci_options['excluded_taxonomies'] ?? [];
		
	    foreach ($z_taxonomies as $z_taxonomy) {
			if (in_array($z_taxonomy, $excluded_taxonomies))
				continue;
	        add_action($z_taxonomy.'_add_form_fields', 'z_add_texonomy_field');
			add_action($z_taxonomy.'_edit_form_fields', 'z_edit_texonomy_field');
			add_filter( 'manage_edit-' . $z_taxonomy . '_columns', 'z_taxonomy_columns' );
			add_filter( 'manage_' . $z_taxonomy . '_custom_column', 'z_taxonomy_column', 10, 3 );
	    }
	}
}

add_action( 'admin_head', 'z_add_style' );
function z_add_style() {
	echo '<style media="screen">
		th.column-thumb {width:60px;}
		.form-field img.taxonomy-image {border:1px solid #eee;max-width:300px;max-height:300px;}
		.inline-edit-row fieldset .thumb label span.title {width:48px;height:48px;border:1px solid #eee;display:inline-block;}
		.column-thumb span {width:48px;height:48px;border:1px solid #eee;display:inline-block;}
		.inline-edit-row fieldset .thumb img,.column-thumb img {width:48px;height:48px;}
	</style>';
}

// 添加在添加窗体中的图像字段
function z_add_texonomy_field() {
	if (get_bloginfo('version') >= 3.5) // worpdress 最低版本3.5
		wp_enqueue_media();
	else {
		wp_enqueue_style('thickbox');
		wp_enqueue_script('thickbox');
	}
	
	echo '<div class="form-field">
		<label for="taxonomy_image">' . __('category/tag image', 'sakurairo') /*分类/标签图像*/. '</label>
		<input type="text" name="taxonomy_image" id="taxonomy_image" value="" />
		<br/>
		<button class="z_upload_image_button button">' . __('add image', 'sakurairo') /*添加图像*/. '</button>
	</div>'.z_script();
}

// 在编辑窗体中添加图像字段
function z_edit_texonomy_field($taxonomy) {
	if (get_bloginfo('version') >= 3.5)
		wp_enqueue_media();
	else {
		wp_enqueue_style('thickbox');
		wp_enqueue_script('thickbox');
	}
	
	if (z_taxonomy_image_url( $taxonomy->term_id, NULL, TRUE ) == Z_IMAGE_PLACEHOLDER) 
		$image_url = "";
	else
		$image_url = z_taxonomy_image_url( $taxonomy->term_id, NULL, TRUE );
	echo '<tr class="form-field">
		<th scope="row" valign="top"><label for="taxonomy_image">' .__('category/tag image', 'sakurairo') /*分类/标签图像*/. '</label></th>
		<td><img class="taxonomy-image" src="' . z_taxonomy_image_url( $taxonomy->term_id, 'medium', TRUE ) . '"/><br/><input type="text" name="taxonomy_image" id="taxonomy_image" value="'.$image_url.'" /><br />
		<button class="z_upload_image_button button">' . __('add image', 'sakurairo') /*添加图像*/. '</button>
		<button class="z_remove_image_button button">' . __('remove image', 'sakurairo') /*删除图像*/. '</button>
		</td>
	</tr>'.z_script();
}

// 上传图像
function z_script() {
	return '<script type="text/javascript">
	    jQuery(document).ready(function($) {
			var wordpress_ver = "'.get_bloginfo("version").'", upload_button;
			$(".z_upload_image_button").click(function(event) {
				upload_button = $(this);
				var frame;
				if (wordpress_ver >= "3.5") {
					event.preventDefault();
					if (frame) {
						frame.open();
						return;
					}
					frame = wp.media();
					frame.on( "select", function() {
						// Grab the selected attachment.
						var attachment = frame.state().get("selection").first();
						frame.close();
						if (upload_button.parent().prev().children().hasClass("tax_list")) {
							upload_button.parent().prev().children().val(attachment.attributes.url);
							upload_button.parent().prev().prev().children().attr("src", attachment.attributes.url);
						}
						else
							$("#taxonomy_image").val(attachment.attributes.url);
					});
					frame.open();
				}
				else {
					tb_show("", "media-upload.php?type=image&amp;TB_iframe=true");
					return false;
				}
			});
			
			$(".z_remove_image_button").click(function() {
				$(".taxonomy-image").attr("src", "'.Z_IMAGE_PLACEHOLDER.'");
				$("#taxonomy_image").val("");
				$(this).parent().siblings(".title").children("img").attr("src","' . Z_IMAGE_PLACEHOLDER . '");
				$(".inline-edit-col :input[name=\'taxonomy_image\']").val("");
				return false;
			});
			
			if (wordpress_ver < "3.5") {
				window.send_to_editor = function(html) {
					imgurl = $("img",html).attr("src");
					if (upload_button.parent().prev().children().hasClass("tax_list")) {
						upload_button.parent().prev().children().val(imgurl);
						upload_button.parent().prev().prev().children().attr("src", imgurl);
					}
					else
						$("#taxonomy_image").val(imgurl);
					tb_remove();
				}
			}
			
			$(".editinline").click(function() {	
			    var tax_id = $(this).parents("tr").attr("id").substr(4);
			    var thumb = $("#tag-"+tax_id+" .thumb img").attr("src");

				if (thumb != "' . Z_IMAGE_PLACEHOLDER . '") {
					$(".inline-edit-col :input[name=\'taxonomy_image\']").val(thumb);
				} else {
					$(".inline-edit-col :input[name=\'taxonomy_image\']").val("");
				}
				
				$(".inline-edit-col .title img").attr("src",thumb);
			});
	    });
	</script>';
}

// 添加到钩子
add_action('edit_term','z_save_taxonomy_image');
add_action('create_term','z_save_taxonomy_image');
function z_save_taxonomy_image($term_id) {
    if(isset($_POST['taxonomy_image']))
        update_option('z_taxonomy_image'.$term_id, $_POST['taxonomy_image'], NULL);
}

// 附件地址
function z_get_attachment_id_by_url($image_src) {
    global $wpdb;
    $query = $wpdb->prepare("SELECT ID FROM $wpdb->posts WHERE guid = %s", $image_src);
    $id = $wpdb->get_var($query);
    return (!empty($id)) ? $id : NULL;
}

// 我不知道
function z_taxonomy_image_url($term_id = NULL, $size = 'full', $return_placeholder = FALSE) {
	if (!$term_id) {
		if (is_category())
			$term_id = get_query_var('cat');
		elseif (is_tag())
			$term_id = get_query_var('tag_id');
		elseif (is_tax()) {
			$current_term = get_term_by('slug', get_query_var('term'), get_query_var('taxonomy'));
			$term_id = $current_term->term_id;
		}
	}
	
    $taxonomy_image_url = get_option('z_taxonomy_image'.$term_id);
    if(!empty($taxonomy_image_url)) {
	    $attachment_id = z_get_attachment_id_by_url($taxonomy_image_url);
	    if(!empty($attachment_id)) {
	    	$taxonomy_image_url = wp_get_attachment_image_src($attachment_id, $size);
		    $taxonomy_image_url = $taxonomy_image_url[0];
	    }
	}

    if ($return_placeholder)
		return ($taxonomy_image_url != '') ? $taxonomy_image_url : Z_IMAGE_PLACEHOLDER;
	else
		return $taxonomy_image_url;
}

function z_quick_edit_custom_box($column_name, $screen, $name) {
	if ($column_name == 'thumb') 
		echo '<fieldset>
		<div class="thumb inline-edit-col">
			<label>
				<span class="title"><img src="" alt="暂无"/></span>
				<span class="input-text-wrap"><input type="text" name="taxonomy_image" value="" class="tax_list" /></span>
				<span class="input-text-wrap">
					<button class="z_upload_image_button button">' . __('add image', 'sakurairo') /*添加图像*/. '</button>
					<button class="z_remove_image_button button">' . __('remove image', 'sakurairo') /*删除图像*/. '</button>
				</span>
			</label>
		</div>
	</fieldset>';
}

/**
 * 添加类别管理
 */
function z_taxonomy_columns( $columns ) {
	$new_columns = array();
	if(isset($columns)){
		$new_columns['cb'] = $columns['cb'];
		unset( $columns['cb'] );
	}
	$new_columns['thumb'] = __('image', 'sakurairo')/*图像*/;
	return array_merge( $new_columns, $columns );
}

/**
 * 图像列表
 */
function z_taxonomy_column( $columns, $column, $id ) {
	if ( $column == 'thumb' )
		$columns = '<span><img src="' . z_taxonomy_image_url($id, 'thumbnail', TRUE) . '" alt="' . __('no image', 'sakurairo') /*暂无*/. '" class="wp-post-image" /></span>';
	
	return $columns;
}

// 将“插入帖子”更改为“使用此图片”
function z_change_insert_button_text($safe_text, $text) {
    return str_replace("Insert into Post", "Use this image", $text);
}

// 列表图片样式
if ( strpos( $_SERVER['SCRIPT_NAME'], 'edit-tags.php' ) > 0 ) {
	add_action('quick_edit_custom_box', 'z_quick_edit_custom_box', 10, 3);
	add_filter("attribute_escape", "z_change_insert_button_text", 10, 2);
}
