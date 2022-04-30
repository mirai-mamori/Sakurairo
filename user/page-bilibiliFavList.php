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

        <script>
            let expandButton = document.querySelectorAll('.expand-button');
            expandButton.forEach(
                function(elem) {
                    elem.addEventListener('click', function() {
                        let folder = elem.closest('.folder');
                        let folderStyle = getComputedStyle(folder);
                        if (folderStyle.maxHeight == '200px'){
                            let folderContent = folder.querySelector(".folder-content");
                            folder.style.maxHeight = 200 + folderContent.scrollHeight + 'px';
                        } else {
                            folder.style.maxHeight = '200px';
                        }
                    }, true);
                }
            );

            window.addEventListener('resize', function() {
                setTimeout( () => {
                    expandButton.forEach(
                        function(elem) {
                            let folder = elem.closest('.folder');
                            let folderStyle = getComputedStyle(folder);
                            if (folderStyle.maxHeight != '200px'){
                                let folderContent = folder.querySelector(".folder-content");
                                folder.style.maxHeight = 200 + folderContent.scrollHeight + 'px';
                            }
                        }
                    );
                }, 500);
            }, true);

            document.addEventListener('click',function(e){
                if(e.target && e.target.classList.contains('load-more')){
                    let elem = e.target;
                    let href = elem.getAttribute('data-href') + "&_wpnonce=" + _iro.nonce;
                    fetch(href, {method: 'POST'})
                        .then((response) => {
                            return response.json();
                        })
                        .then((html) => {
                            var htmlObject = document.createElement('div');
                            htmlObject.innerHTML = html;
                            while (htmlObject.childNodes.length > 0) {
                                elem.parentNode.appendChild(htmlObject.childNodes[0]);
                            }
                            let folder = elem.closest('.folder');
                            let folderContent = folder.querySelector(".folder-content");
                            folder.style.maxHeight = 200 + folderContent.scrollHeight + 'px';
                            elem.remove();
                        });
                }
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

.folder{
    border: 1px solid gray;
    overflow: hidden;
    transition: max-height .5s ease-out;
    max-height: 200px;
    border-radius: 10px;

    color: #fff;
    box-shadow: 0 0 10px rgb(0 0 0 / 10%), 0 5px 20px rgb(0 0 0 / 20%);
}

.folder-img{
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

.expand-button{
    background-color: transparent;
    height: 50px;
    align-self: center;
    font-size: 16px;
    border: 0;
    outline: 0;
    background-color: rgba(255, 255, 255, 0.6);
    color: var(--theme-skin,#505050);
    padding: 15px;
    border-radius: var(--style_menu_radius,12px);
    box-shadow: 0 0 2px 0 rgb(0 0 0 / 12%), 0 2px 2px 0 rgb(0 0 0 / 24%);
    transition: all 0.8s ease !important;
}

.folder hr{
    margin: 0;
    background: grey;
}

.folder-content{
    padding: 5px;
    display: flex;
    justify-content: center;
    flex-wrap: wrap;
}

.column{
    margin-bottom: 20px;
    padding: 0 5px;
    transition: .5s;
    max-width: 100%;
    flex: 0 0 100%;
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

.item-intro{
    overflow: hidden;
    white-space: nowrap;
    text-overflow: ellipsis;
}

.load-more{
    flex: 0 0 100%;
    text-align: center;
    padding-top: 5px;
    padding-bottom: 5px;
}



@media screen and (min-width: 600px){
    .folder-img{
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
        align-self: center;
        width: 100%;
        text-align: center;
        background: rgba(0, 0, 0, .3);
    }
    .folder-top{
        display: flex;
        position: relative;
    }
    .folder-content{
        padding: 15px;
    }
    .column{
        max-width: 33.33333%;
        flex: 0 0 33.33333%;
    }
    .load-more{
        padding-top: 10px;
        padding-bottom: 10px;
    }
}

body.dark .expand-button {
    background-color: rgba(38, 38, 38, 0.6);
    color: var(--theme-skin-dark);
}

</style>
<?php
get_footer();


