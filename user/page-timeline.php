<?php 

/**
 Template Name: Timeline Template
 */

get_header();

?>
<style>
    header.timeline-header {
        position: relative;
        text-align: center;
        margin-top: 7.5%;
        margin-bottom: 6.5%;
        color: #9C9C9C;
    }

    .art-content #archives .al_mon_list {
        position: relative;
        padding: 10px 0;
        display: inline-block;
        vertical-align: middle;
    }

    .art-content #archives .al_mon_list .al_mon {
        position: relative;
        color: var(--theme-skin, #505050);
        padding: 0;
        border-radius: 0;
        margin: 0;
        background: 0 0;
        font-weight: 400;
    }

    .art-content #archives .al_mon_list span {
        padding: 0;
        border-radius: 0;
        margin: 0;
        color: var(--theme-skin, #505050);
        background: 0 0;
        font-weight: 400;
    }

    .art .art-content .al_mon_list .al_post_list>li:before {
        position: absolute;
        left: 116px;
        background: #fff;
        height: 12px;
        width: 12px;
        border-radius: 6px;
        top: 6px;
        content: "";
        -webkit-box-shadow: 1px 1px 1px #bbb;
        box-shadow: 1px 1px 1px #bbb;
    }

    .art .art-content .al_mon_list .al_post_list>li:after {
        position: absolute;
        left: 118px;
        background: var(--theme-skin, #505050);
        height: 8px;
        width: 8px;
        border-radius: 6px;
        top: 8px;
        content: "";
    }

    .art-content #archives .al_mon_list .al_mon:before {
        position: absolute;
        left: 113px;
        background: #fff;
        height: 18px;
        width: 18px;
        border-radius: 9px;
        top: 3px;
        content: "";
        -webkit-box-shadow: 1px 1px 1px #bbb;
        box-shadow: 1px 1px 1px #bbb;
    }

    .art-content #archives .al_mon_list .al_mon:after {
        position: absolute;
        left: 116px;
        background: var(--theme-skin, #505050);
        height: 12px;
        width: 12px;
        border-radius: 6px;
        top: 6px;
        content: "";
    }

    .art-content #archives .al_mon_list:before {
        max-height: 100%;
        height: 100%;
        width: 4px;
        background: var(--theme-skin, #505050);
        position: absolute;
        left: 120px;
        content: "";
        top: 0;
    }

    .art .art-content #archives a:before,
    .art .art-content .al_mon_list li:before {
        content: none;
    }

    #archives ul {
        left: -10px;
    }

    #archives h3 {
        margin-top: 0;
        margin-bottom: 0;
    }

    .art .art-content #archives a {
        color: #000;
        font-weight: 400;
    }

    .art .art-content #archives .al_year {
        padding-left: 85px;
    }

    .art .art-content .al_mon_list .al_post_list>li {
        position: relative;
        color: var(--theme-skin, #505050);
        padding-left: 140px;
    }

    .art-content #archives .al_mon,
    .art-content #archives .al_mon_list .al_post_list{
        display: block;
    }

    .art .art-content .al_mon_list {
        width: 100%;
    }

    body.dark .art-content #archives .al_mon_list .al_mon,
    body.dark .art-content #archives .al_mon_list span,
    body.dark .art .art-content #archives a {
        color: var(--dark-text-secondary);
    }

    body.dark .art .art-content #archives .al_year {
        color: var(--dark-text-primary);
    }

    body.dark .art .art-content #archives a:hover,
    body.dark .art .art-content #archives a:hover {
        color: var(--article-theme-highlight,var(--theme-skin-matching));
    }
</style>

   	<div id="main">
		<header class="timeline-header"><h1 class="cat-title">时光轴</h1> <span class="cat-des"><p>TimeLine</p> </span></header>
        <div id="main-part">
			<?php if (have_posts()) : the_post(); update_post_caches($posts); ?>
            <article class="art">
                <div class="art-main">
                    <div class="art-content">
                        <?php the_content( '', true ); memory_archives_list(); ?>
					</div>
				</div>
			</article>
			<?php endif; ?>
        </div>
    </div>
<?php get_footer(); 
