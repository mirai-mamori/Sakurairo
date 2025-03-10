<?php
/**
 * Template Name: Archive Template
 */

get_header();
?>
<style>
    #archives-temp h2 {
        font-weight: 400;
        color: var(--theme-skin, #505050);
        text-align: center;
    }

    #archives-temp h3 {
        letter-spacing: 10px;
        font-style: italic;
        font-size: 20px;
        font-weight: var(--global-font-weight, 400);
        color: var(--theme-skin, #505050);
        margin-left: 35px;
        margin-top: 50px;
    }

    #archives-temp h2 i {
        padding: 10px;
        font-size: 20px;
    }

    #archives-temp {
        margin-top: 50px;
        margin-bottom: 100px;
    }

    #archives-temp h3:hover {
        color: var(--theme-skin-matching);
        cursor: pointer;
    }

    #archives-content {
        position: relative;
        margin-top: 10%;
    }

    span.ar-circle {
        height: 10px;
        width: 10px;
        background: var(--theme-skin, #505050);
        display: inline-block;
        position: absolute;
        left: -5px;
        margin-top: 3.2%;
        border-radius: 100px;
    }

    .arrow-left-ar {
        width: 0;
        height: 0;
        display: none;
        float: left;
        margin-top: 10px;
        border-left: 20px solid transparent;
        border-bottom: 20px solid #F5F5F5;
        margin-left: 11px;
    }

    .brick a {
        color: #7D7D7D;
        padding: 20px 20px;
        margin-bottom: 20px;
        display: block;
        letter-spacing: 0;
        box-shadow: 0 1px 30px -4px #e8e8e8;
        background: rgba(255, 255, 255, 0.5);
        border-radius: 10px;
    }

    .brick a:hover {
        color: var(--theme-skin-matching);
        box-shadow: 0 1px 20px 10px #e8e8e8;
        background: rgba(255, 255, 255, 0.8);
    }

    .brick {
        margin-left: 30px;
    }

    .brick em {
        font-style: normal;
        margin-left: 5px;
    }

    span.time {
        float: right;
        color: #7d7d7d;
    }

    .time i {
        margin: 3px;
    }

    body.dark #archives-temp h3,
    body.dark #archives-temp h2 {
        color: var(--dark-text-primary);
    }

    body.dark #archives-temp h3:hover {
        color: var(--theme-skin-dark);
    }

    body.dark span.ar-circle {
        background-color: var(--theme-skin-dark);
    }

    body.dark .brick a {
        color: var(--dark-text-tertiary);
        box-shadow: var(--dark-shadow-normal);
        background: var(--dark-bg-secondary);
        border: 1.5px solid var(--dark-border-color);
    }

    body.dark .brick a:hover {
        color: var(--theme-skin-dark);
        box-shadow: 0 1px 30px -2px var(--theme-skin-dark) !important;
        background: var(--dark-bg-hover);
    }
</style>

<?php
while (have_posts()) : the_post(); ?>

    <article <?php post_class("post-item"); ?>>
        <?php the_content('', true); ?>
        <div id="archives-temp">
            <?php if (!iro_opt('patternimg') || !get_post_thumbnail_id(get_the_ID())) { ?>
                <h2><i class="fa-solid fa-calendar-day"></i><?php the_title(); ?></h2>
            <?php } ?>
            <div id="archives-content">
                <?php
                $the_query = new WP_Query([
                    'posts_per_page' => -1,
                    'ignore_sticky_posts' => 1
                ]);

                $all = [];
                $output = '';
                $year = 0;
                $mon = 0;

                while ($the_query->have_posts()) : $the_query->the_post();
                    $year_tmp = get_the_time('Y');
                    $mon_tmp = get_the_time('n');

                    if ($mon != $mon_tmp && $mon > 0) {
                        $output .= '</div></div>';
                    }

                    if ($year != $year_tmp) {
                        $year = $year_tmp;
                        $all[$year] = [];
                    }

                    if ($mon != $mon_tmp) {
                        $mon = $mon_tmp;
                        $all[$year][] = $mon;
                        $output .= "<div class='archive-title' id='arti-$year-$mon'><h3>$year-$mon</h3><div class='archives archives-$mon' id='monlist' data-date='$year-$mon'>";
                    }

                    $output .= '<span class="ar-circle"></span><div class="arrow-left-ar"></div><div class="brick"><a href="' . get_permalink() . '"><span class="time"><i class="fa-regular fa-clock"></i>' . get_the_time('n-d') . '</span>' . get_the_title() . '<em>(' . get_comments_number('0', '1', '%') . ')</em></a></div>';
                endwhile;

                wp_reset_postdata();
                $output .= '</div></div>';
                echo $output;
                ?>
            </div>
        </div>
    </article>

<?php endwhile;

get_footer();
?>
