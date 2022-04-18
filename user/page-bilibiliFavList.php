<?php

/**
Template Name: 哔哩哔哩收藏模板
 */
get_header();
?>
<meta name="referrer" content="same-origin">
<style>
    .comments{display: none}
    .site-content{max-width:1280px}
</style>
<!-- TODO: remove jquery requirement -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js" ></script>
</head>

<?php while(have_posts()) : the_post(); ?>
	<?php if(!iro_opt('patternimg') || !get_post_thumbnail_id(get_the_ID())) { ?>
		<span class="linkss-title"><?php the_title();?></span>
	<?php } ?>
	<article <?php post_class("post-item"); ?>>
		<?php the_content(); ?>

			<?php if (iro_opt('bilibili_id') ):?>
            <section class="fav-list">
				<?php
				$bgm = new \Sakura\API\BilibiliFavList();
				echo $bgm->get_folders();
				?>
				<?php else: ?>
					<div class="row">
						<p> <?php _e("Please fill in the Bilibili UID in Sakura Options.","sakura"); ?></p>
					</div>
				<?php endif; ?>
            </section>

        <!-- TODO: remove jquery requirement -->
        <script>
            $('.expand-button').click(function () {
                if ($(this).closest('.folder').css('max-height') == '200px'){
                    $(this).closest('.folder').css('max-height', 200 + $(this).closest('.folder').find('.folder-content')[0].scrollHeight + 'px');
                } else {
                    $(this).closest('.folder').css('max-height', '200px');
                }
            });
        </script>
        <!-- load more -->
        <script>
            $('.folder-content').on('click', '.load-more',function () {
                var href = $(this).data('href') + "&_wpnonce=" + _iro.nonce;
                fetch(href, {method: 'POST'})
                    .then((response) => {
                        return response.json();
                    })
                    .then((html) => {
                        $(this).closest('.folder-content').append(html);
                        $(this).closest('.folder').css('max-height', 200 + $(this).closest('.folder').find('.folder-content')[0].scrollHeight + 'px');
                        $(this).remove();
                    });
                return false;
            });

        </script>


	</article>
<?php endwhile; ?>

<style>
.fav-list{
    margin: 0 -10px -20px;
    flex-wrap: wrap;
    padding: 1rem 3%;
    justify-content: center;
}

.folder {
    border: 1px solid gray;
    overflow: hidden;
    transition: max-height .5s ease-out;
    max-height: 200px;
    border-radius: 10px;
}

.folder-img {
    height: 200px;
    object-fit: cover;
    width: 100%;
}

.folder-top{
    height: 200px;
    display: block;
    position: relative;
}

.folder-detail{
    height: 100%;
    top: 0;
    left: 0;
    right: 0;
    position: absolute;
    text-align: center;
    background: rgba(0, 0, 0, .5);
}

.expand-button {
    background-color: transparent;
    height: 50px;
    align-self: center;
}

.folder hr{
    margin: 0;
    background: grey;
}

.folder-content{
    padding: 5px;
    display: block;
}

.column{
    margin-bottom: 20px;
    padding: 0 5px;
    transition: .5s;
    max-width: 100%;
    flex: 0 0 10%;
}

.folder-item{
    border: 1px solid gray;
    height: 0;
    color: #fff;
    display: block;
    overflow: hidden;
    text-align: center;
    position: relative;
    padding-bottom: 60%;
    box-shadow: 0 0 10px rgb(0 0 0 / 10%), 0 5px 20px rgb(0 0 0 / 20%);
    border-radius: 10px;
}

.item-img{
    width: 100%;
    user-select: none;
    object-fit: cover;
    transition: filter 2s;
}

.item-info{
    height: 40%;
    top: 0;
    left: 0;
    right: 0;
    padding: 10px;
    position: absolute;
    background: rgba(0, 0, 0, .5);
    transition: transform 1s;
    transform: translateY(150%);
}

.item-title{
    overflow: hidden;
    white-space: nowrap;
    text-overflow: ellipsis;
    margin-top: 0;
}



@media screen and (min-width: 600px){
    .folder-img {
        height: 200px;
        object-fit: cover;
        width: auto;
    }
    .folder-detail{
        height: 200px;
        display: flex;
        flex-direction: column;
        justify-content: center;
        position: inherit;
    }
    .folder-top{
        display: flex;
        position: relative;
    }
    .folder-detail{
        align-self: center;
        width: 100%;
        text-align: center;
    }
    .folder-content{
        padding: 15px;
        display: flex;
        flex-wrap: wrap;
    }
    .column{
        max-width: 33.33333%;
        flex: 0 0 33.33333%;
    }
}


</style>
<?php
get_footer();


