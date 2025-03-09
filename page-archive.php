<?php
/**
 * Template Name: Archive Template
 */

get_header();

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
