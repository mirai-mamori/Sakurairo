<?php
function render_meta_category(){
	$the_cat = get_the_category();
	$categorized = isset($the_cat[0]);
	?><span><i class="fa-regular fa-folder"></i>
						<?php
						if($categorized){
							?>
							<a href="<?=esc_url(get_category_link($the_cat[0]->cat_ID  ?? ''))?>">
								<?=$the_cat[0]->cat_name ?? '未分类'?>
							</a>
							<?php
						}else{
							?>
							未分类
							<?php
						}
						?>
	</span>
	<?php
}