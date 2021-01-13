<?php 

/**
 Template Name: 文章模版
 */

get_header(); 

?>

	<?php while(have_posts()) : the_post(); ?>
	
	<article <?php post_class("post-item"); ?>>
		<?php the_content(); ?>
		<div id="archives-temp">  
		<?php if(!iro_opt('patternimg') || !get_post_thumbnail_id(get_the_ID())) { ?>
        <h2><?php the_title();?></h2>
        <?php } ?>	
    <div id="archives-content">      
    <?php       
        $the_query = new WP_Query( 'posts_per_page=-1&ignore_sticky_posts=1' );      
        $year=0; $mon=0; $i=0; $j=0;      
        $all = array();      
        $output = '';      
        while ( $the_query->have_posts() ) : $the_query->the_post();      
            $year_tmp = get_the_time('Y');      
            $mon_tmp = get_the_time('n');      
            $y=$year; $m=$mon;      
            if ($mon != $mon_tmp && $mon > 0) $output .= '</div></div>';      
            if ($year != $year_tmp) { // 输出年份      
                $year = $year_tmp;      
                $all[$year] = array();      
            }      
            if ($mon != $mon_tmp) { // 输出月份      
                $mon = $mon_tmp;      
                array_push($all[$year], $mon);      
                $output .= "<div class='archive-title' id='arti-$year-$mon'><span class='ar-time'><i class='iconfont icon-calendar'></i></span><h3>$year-$mon</h3><div class='archives archives-$mon' id='monlist' data-date='$year-$mon'>";      
            }      
            $output .= '<span class="ar-circle"></span><div class="arrow-left-ar"></div><div class="brick"><a href="'.get_permalink() .'"><span class="time"><i class="iconfont icon-time"></i>'.get_the_time('n-d').'</span>'.get_the_title() .'<em>('. get_comments_number('0', '1', '%') .')</em></a></div>';      
        endwhile;      
        wp_reset_postdata();      
        $output .= '</div></div>';      
        echo $output;  		         
    ?>   
		</article>
	<?php endwhile; ?>
	
<?php get_footer(); ?>


