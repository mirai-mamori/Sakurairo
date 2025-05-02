<?php
$exhibition = iro_opt('exhibition');
$exhibition = is_array($exhibition) ? $exhibition : [];

// 获取组件配置
$components = iro_opt("capsule_components",[]);
$components = is_array($components) ? $components : [];

// 如果组件为空且没有展示内容，则提前退出
if (empty($components) && empty($exhibition)) {
    // 没有任何内容要显示，可以直接返回空白
    echo '</div>'; // 关闭可能存在的父容器
    return;
}

// 预定义组件数组，仅在需要时填充内容
$square_cards = [];
$site_stats = []; // 只在需要时获取站点统计

// 检查是否至少有一个组件被启用
$has_valid_components = !empty($components);

// 仅当有组件需要显示时才获取站点统计信息
if ($has_valid_components) {
    $site_stats = get_site_stats();
    
    // 动态填充需要使用的组件
    if (in_array('post_count', $components)) {
        $square_cards['post_count'] = [
            'icon'    => 'fa-regular fa-file-lines',
            'label'   => __('Content Count', 'sakurairo'),
            'value'   => number_format($site_stats['post_count']) . ' ' . __('posts', 'sakurairo'),
            'enabled' => true
        ];
    }
    
    if (in_array('comment_count', $components)) {
        $square_cards['comment_count'] = [
            'icon'    => 'fa-regular fa-comment',
            'label'   => __('Comment Count', 'sakurairo'),
            'value'   => number_format($site_stats['comment_count']) . ' ' . __('comments', 'sakurairo'),
            'enabled' => true
        ];
    }
    
    if (in_array('view_count', $components)) {
        $square_cards['view_count'] = [
            'icon'    => 'fa-regular fa-eye',
            'label'   => __('Visitor Count', 'sakurairo'),
            'value'   => number_format($site_stats['visitor_count']) . ' ' . __('visits', 'sakurairo'),
            'enabled' => true
        ];
    }
    
    if (in_array('link_count', $components)) {
        $square_cards['link_count'] = [
            'icon'    => 'fa-solid fa-link',
            'label'   => __('Link Count', 'sakurairo'),
            'value'   => number_format($site_stats['link_count']) . ' ' . __('links', 'sakurairo'),
            'enabled' => true
        ];
    }
    
    if (in_array('author_count', $components)) {
        $square_cards['author_count'] = [
            'icon'    => 'fa-solid fa-users',
            'label'   => __('Author Count', 'sakurairo'),
            'value'   => number_format($site_stats['author_count']) . ' ' . __('authors', 'sakurairo'),
            'enabled' => true
        ];
    }
    
    if (in_array('total_words', $components)) {
        $square_cards['total_words'] = [
            'icon'    => 'fa-solid fa-font',
            'label'   => __('Total Words', 'sakurairo'),
            'value'   => number_format($site_stats['total_words']) . ' ' . __('words', 'sakurairo'),
            'enabled' => true
        ];
    }
    
    if (in_array('blog_days', $components)) {
        $square_cards['blog_days'] = [
            'icon'    => 'fa-solid fa-calendar-days',
            'label'   => __('Blog Running', 'sakurairo'),
            'value'   => number_format($site_stats['blog_days']) . ' ' . __('days', 'sakurairo'),
            'enabled' => true
        ];
    }
    
    if (in_array('admin_online', $components)) {
        $square_cards['admin_online'] = [
            'icon'    => 'fa-solid fa-user-clock',
            'label'   => __('Last Online', 'sakurairo'),
            'value'   => format_time_diff($site_stats['admin_last_online_diff']),
            'enabled' => true
        ];
    }
    
    if (in_array('announcement', $components)) {
        $square_cards['announcement'] = [
            'icon'         => 'fa-solid fa-bullhorn',
            'label'        => iro_opt('stat_announcement_text', __('Latest Announcement', 'sakurairo')),
            'value'        => '',
            'enabled' => true
        ];
    }
    
    if (in_array('random_link', $components)) {
        $square_cards['random_link'] = [
            'enabled' => true
        ];
    }
}

// 判断博客运行天数徽章级别
function get_blog_days_medal($days) {
    $thresholds = [
        'gold' => 1500,
        'silver' => 500,
        'bronze' => 100
    ];
    
    if ($days >= $thresholds['gold']) {
        return [
            'type' => 'gold',   
            'label' => __('Gold Blogger','sakurairo'),
            'current' => $days,
            'next' => null,
            'progress' => 100,
            'threshold' => $thresholds['gold'],
            'achievement' => __('The string of creation has vibrated the membrane, each resonance weaving the warp and weft of spacetime thoughts.','sakurairo')
        ];
    }
    if ($days >= $thresholds['silver']) {
        $progress = min(100, ($days - $thresholds['silver']) / ($thresholds['gold'] - $thresholds['silver']) * 100);
        return [
            'type' => 'silver', 
            'label' => __('Silver Blogger','sakurairo'),
            'current' => $days,
            'next' => $thresholds['gold'],
            'progress' => $progress,
            'threshold' => $thresholds['silver'],
            'achievement' => __('The wheel of creation has engaged the gears, each component calibrating the temporal precision of your blog.','sakurairo'),
            'next_level' => sprintf(__('%s more days to next level','sakurairo'), number_format($thresholds['gold'] - $days))
        ];
    }
    if ($days >= $thresholds['bronze']) {
        $progress = min(100, ($days - $thresholds['bronze']) / ($thresholds['silver'] - $thresholds['bronze']) * 100);
        return [
            'type' => 'bronze', 
            'label' => __('Bronze Blogger','sakurairo'),
            'current' => $days,
            'next' => $thresholds['silver'],
            'progress' => $progress,
            'threshold' => $thresholds['bronze'],
            'achievement' => __('The ship of creation has set sail, each wave carving the depth markers of your blog\'s journey.','sakurairo'),
            'next_level' => sprintf(__('%s more days to next level','sakurairo'), number_format($thresholds['silver'] - $days))
        ];
    }
    return null;
}

// 判断访客数量徽章级别
function get_visitor_count_medal($visits) {
    $thresholds = [
        'gold' => 40000,
        'silver' => 15000,
        'bronze' => 5000
    ];
    
    if ($visits >= $thresholds['gold']) {
        return [
            'type' => 'gold',   
            'label' => __('Gold Popularity','sakurairo'),
            'current' => $visits,
            'next' => null,
            'progress' => 100,
            'threshold' => $thresholds['gold'],
            'achievement' => __('You have planted a garden of wisdom, its fragrance now discovered by distant butterflies.','sakurairo')
        ];
    }
    if ($visits >= $thresholds['silver']) {
        $progress = min(100, ($visits - $thresholds['silver']) / ($thresholds['gold'] - $thresholds['silver']) * 100);
        return [
            'type' => 'silver', 
            'label' => __('Silver Popularity','sakurairo'),
            'current' => $visits,
            'next' => $thresholds['gold'],
            'progress' => $progress,
            'threshold' => $thresholds['silver'],
            'achievement' => __('You have illuminated the star chart of reading, each piece of writing flowing through the galaxy.','sakurairo'),
            'next_level' => sprintf(__('%s more visits to next level','sakurairo'), number_format($thresholds['gold'] - $visits))
        ];
    }
    if ($visits >= $thresholds['bronze']) {
        $progress = min(100, ($visits - $thresholds['bronze']) / ($thresholds['silver'] - $thresholds['bronze']) * 100);
        return [
            'type' => 'bronze', 
            'label' => __('Bronze Popularity','sakurairo'),
            'current' => $visits,
            'next' => $thresholds['silver'],
            'progress' => $progress,
            'threshold' => $thresholds['bronze'],
            'achievement' => __('You have built a harbor of knowledge, the ripples of thought continuously spreading.','sakurairo'),
            'next_level' => sprintf(__('%s more visits to next level','sakurairo'), number_format($thresholds['silver'] - $visits))
        ];
    }
    return null;
}

// 判断友情链接数量徽章级别
function get_link_count_medal($links) {
    $thresholds = [
        'gold' => 80,
        'silver' => 40,
        'bronze' => 20
    ];
    
    if ($links >= $thresholds['gold']) {
        return [
            'type' => 'gold',   
            'label' => __('Gold Friendship','sakurairo'),
            'current' => $links,
            'next' => null,
            'progress' => 100,
            'threshold' => $thresholds['gold'],
            'achievement' => __('The world of friendship has no boundaries, the starry river will mark your position.','sakurairo')
        ];
    }
    if ($links >= $thresholds['silver']) {
        $progress = min(100, ($links - $thresholds['silver']) / ($thresholds['gold'] - $thresholds['silver']) * 100);
        return [
            'type' => 'silver', 
            'label' => __('Silver Friendship','sakurairo'),
            'current' => $links,
            'next' => $thresholds['gold'],
            'progress' => $progress,
            'threshold' => $thresholds['silver'],
            'achievement' => __('The network of friendship has been laid out, each resonance will eventually echo.','sakurairo'),
            'next_level' => sprintf(__('%s more links to next level','sakurairo'), number_format($thresholds['gold'] - $links))
        ];
    }
    if ($links >= $thresholds['bronze']) {
        $progress = min(100, ($links - $thresholds['bronze']) / ($thresholds['silver'] - $thresholds['bronze']) * 100);
        return [
            'type' => 'bronze', 
            'label' => __('Bronze Friendship','sakurairo'),
            'current' => $links,
            'next' => $thresholds['silver'],
            'progress' => $progress,
            'threshold' => $thresholds['bronze'],
            'achievement' => __('The journey of making friends has just begun, each encounter marks a new chapter.','sakurairo'),
            'next_level' => sprintf(__('%s more links to next level','sakurairo'), number_format($thresholds['silver'] - $links))
        ];
    }
    return null;
}

// 判断文章总字数徽章级别
function get_words_count_medal($words) {
    $thresholds = [
        'gold' => 50000,
        'silver' => 10000,
        'bronze' => 2000
    ];
    
    if ($words >= $thresholds['gold']) {
        return [
            'type' => 'gold',   
            'label' => __('Gold Author','sakurairo'),
            'current' => $words,
            'next' => null,
            'progress' => 100,
            'threshold' => $thresholds['gold'],
            'achievement' => __('The power of words has blossomed in your hands, rich creations have gathered into an ocean.','sakurairo')
        ];
    }
    if ($words >= $thresholds['silver']) {
        $progress = min(100, ($words - $thresholds['silver']) / ($thresholds['gold'] - $thresholds['silver']) * 100);
        return [
             'type' => 'silver', 
            'label' => __('Silver Author','sakurairo'),
            'current' => $words,
            'next' => $thresholds['gold'],
            'progress' => $progress,
            'threshold' => $thresholds['silver'],
            'achievement' => __('You have woven an extensive web of thoughts, the streams of inspiration are rushing into rivers.','sakurairo'),
            'next_level' => sprintf(__('%s more words to next level','sakurairo'), number_format($thresholds['gold'] - $words))
        ];
    }
    if ($words >= $thresholds['bronze']) {
        $progress = min(100, ($words - $thresholds['bronze']) / ($thresholds['silver'] - $thresholds['bronze']) * 100);
        return [
            'type' => 'bronze', 
            'label' => __('Bronze Author','sakurairo'),
            'current' => $words,
            'next' => $thresholds['silver'],
            'progress' => $progress,
            'threshold' => $thresholds['bronze'],
            'achievement' => __('Sparks of words dance at your fingertips, as the faint light of thoughts quietly illuminates.','sakurairo'),
            'next_level' => sprintf(__('%s more words to next level','sakurairo'), number_format($thresholds['silver'] - $words))
        ];
    }
    return null;
}

// 统计徽章信息
$medal_levels = [];
// 只有启用了相关组件且启用了徽章胶囊功能时才计算徽章
$show_medal_capsules = iro_opt('show_medal_capsules', true);
$need_medals = $show_medal_capsules && (
    in_array('blog_days', $components) ||
    in_array('view_count', $components) ||
    in_array('link_count', $components) ||
    in_array('total_words', $components)
);

// 只有当需要显示徽章时才计算徽章级别
if ($need_medals) {
    if (in_array('blog_days', $components)) {
        $m = get_blog_days_medal($site_stats['blog_days']);
        if ($m) $medal_levels['blog_days'] = $m;
    }
    
    if (in_array('view_count', $components)) {
        $m = get_visitor_count_medal($site_stats['visitor_count']);
        if ($m) $medal_levels['view_count'] = $m;
    }
    
    if (in_array('link_count', $components)) {
        $m = get_link_count_medal($site_stats['link_count']);
        if ($m) $medal_levels['link_count'] = $m;
    }
    
    if (in_array('total_words', $components)) {
        $m = get_words_count_medal($site_stats['total_words']);
        if ($m) $medal_levels['total_words'] = $m;
    }
}

?>

<div class="exhibition-area-container">
    <h1 class="fes-title"> 
        <i class="<?php echo iro_opt('exhibition_area_icon', 'fa-solid fa-laptop'); ?>" aria-hidden="true"></i> <?php echo iro_opt('exhibition_area_title', '展示'); ?> 
    </h1>
      <!-- Bento布局容器 -->
       <div class="bento-grid">
        <!-- 胶囊 -->
        <?php if (!empty($components) && !empty($square_cards)): ?>
        <div class="stat-capsules-container">
            <?php 
            // 优化：只迭代存在于square_cards中的组件
            foreach($components as $component):
                if (!isset($square_cards[$component])) {
                    continue; // 跳过不存在的组件
                }
                
                switch ($component):
                    case 'post_count':
                    case 'comment_count':
                    case 'author_count':
                    case 'admin_online':
                        ?>
                        <div class="stat-capsule">
                          <i class="<?php echo $square_cards[$component]['icon']; ?>"></i>
                          <div class="capsule-content">
                            <span class="capsule-label"><?php echo $square_cards[$component]['label']; ?></span>
                            <span class="capsule-value"><?php echo $square_cards[$component]['value']; ?></span>
                          </div>
                        </div>
                        <?php
                        break;

                    case 'view_count':
                    case 'link_count':
                    case 'total_words':
                    case 'blog_days':
                        // 如果有对应徽章，就渲染徽章，否则渲染普通卡片
                        if (isset($medal_levels[$component])): 
                            $medal = $medal_levels[$component]; ?>
                            <div class="stat-capsule medal-capsule <?php echo $medal['type']; ?>" 
                                 data-medal-type="<?php echo $component; ?>"
                                 data-medal-level="<?php echo $medal['type']; ?>"
                                 <?php if (isset($medal['achievement'])) : ?>
                                 data-achievement="<?php echo esc_attr($medal['achievement']); ?>"
                                 <?php endif; ?>
                                 <?php if (isset($medal['next_level'])) : ?>
                                 data-next-level="<?php echo esc_attr($medal['next_level']); ?>"
                                 <?php endif; ?>
                                 <?php if (isset($medal['progress'])) : ?>
                                 data-progress="<?php echo esc_attr($medal['progress']); ?>"
                                 <?php endif; ?>>
                                <div class="medal-particles-container">
                                    <div class="medal-particles"></div>
                                </div>
                                <i class="fa-solid fa-medal"></i>
                                <div class="capsule-content">
                                    <span class="capsule-label"><?php echo $medal['label']; ?></span>
                                    <span class="capsule-value">
                                    <?php echo $square_cards[$component]['value']; ?>
                                    </span>
                                </div>
                                <div class="medal-info-tooltip">
                                    <div class="medal-achievement">
                                        <?php echo isset($medal['achievement']) ? $medal['achievement'] : ''; ?>
                                    </div>
                                    <?php if (isset($medal['next_level'])) : ?>
                                    <div class="medal-next-level">
                                        <?php echo $medal['next_level']; ?>
                                    </div>
                                    <?php endif; ?>
                                </div>
                            </div>
                        <?php else: ?>
                            <div class="stat-capsule">
                                <i class="<?php echo $square_cards[$component]['icon']; ?>"></i>
                                <div class="capsule-content">
                                <span class="capsule-label"><?php echo $square_cards[$component]['label']; ?></span>
                                <span class="capsule-value"><?php echo $square_cards[$component]['value']; ?></span>
                                </div>
                            </div>
                        <?php endif;
                        break;

                    case 'random_link':
                        if (isset($site_stats['random_link']) && !empty($site_stats['random_link'])):
                            $rl = $site_stats['random_link'];
                            $img = isset($rl->link_image) && !empty($rl->link_image) ? $rl->link_image : 
                                   'https://weavatar.com/avatar/?s=80&d=mm&r=g'; ?>
                            <div class="stat-capsule link-capsule">
                            <div class="link-avatar"><img src="<?php echo esc_url($img); ?>" /></div>
                            <div class="link-info">
                                <a href="<?php echo esc_url($rl->link_url); ?>" target="_blank">
                                <?php echo esc_html($rl->link_name); ?>
                                </a>
                                <span class="link-description"><?php _e('Meet my friend!','sakurairo'); ?></span>
                            </div>
                            </div>
                        <?php endif;
                        break;

                    case 'announcement':
                        // 优化：只有当标签存在时才处理
                        if (isset($square_cards['announcement']) && isset($square_cards['announcement']['label'])): 
                            $text = $square_cards['announcement']['label'];
                            // 强制两行显示
                            $lines = preg_split('/<br\s*\/?>|\n/',$text,2);
                            if (count($lines)<2) {
                                $len = mb_strlen($lines[0]);
                                $half = ceil($len/2);
                                $first = mb_substr($lines[0],0,$half);
                                $second= mb_substr($lines[0],$half);
                            } else {
                                list($first,$second) = $lines;
                            } ?>
                            <div class="stat-capsule announcement-capsule">
                            <i class="<?php echo $square_cards['announcement']['icon']; ?>"></i>
                            <div class="capsule-content">
                                <span class="announcement-line first-line"><?php echo $first; ?></span>
                                <span class="announcement-line second-line"><?php echo $second; ?></span>
                            </div>
                            </div>
                        <?php endif;
                        break;
                endswitch; 
            endforeach; ?>
        </div>
        <?php endif; ?>

        <!-- 展示卡片 -->
        <?php 
        if (!empty($exhibition)) : 
            // 默认图片路径缓存，避免重复调用iro_opt
            $default_img_base = null;
            
            foreach ($exhibition as $item) : 
                // 跳过非数组项
                if (!is_array($item)) {
                    continue; 
                }
                
                // 使用更高效的数组访问方式
                $link = $item['link'] ?? '#';
                $title = $item['title'] ?? '';
                $description = $item['description'] ?? '';
                $img = $item['img'] ?? '';
                
                // 如果没有图片，使用默认图片
                if (empty($img)) {
                    if ($default_img_base === null) {
                        $default_img_base = iro_opt('vision_resource_basepath', 'https://s.nmxc.ltd/sakurairo_vision/@3.0/');
                    }
                    $img = $default_img_base . 'series/exhibition1.webp';
                }
                ?>
                <div class="bento-item bento-medium">
                    <div class="card-title-wrapper">
                        <h3 class="bento-card-title"><?php echo esc_html($title); ?></h3>
                    </div>
                    <a href="<?php echo esc_url($link); ?>" target="_blank" rel="external nofollow" class="card-link">
                        <div class="card-image">
                            <img src="<?php echo esc_url($img); ?>" alt="<?php echo esc_attr($title); ?>" loading="lazy">
                        </div>
                        <div class="card-info">
                            <?php if (!empty($description)) : ?>
                            <p class="card-description"><?php echo esc_html($description); ?></p>
                            <?php endif; ?>
                        </div>
                    </a>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>

    </div>
</div>