<?php
$exhibition = iro_opt('exhibition');
$exhibition = is_array($exhibition) ? $exhibition : [];

// 获取站点统计信息 - 使用缓存提高性能
function get_site_stats() {
    // 尝试从缓存获取数据
    $cached_stats = get_transient('sakurairo_site_stats');
    if ($cached_stats !== false) {
        return $cached_stats;
    }

    global $wpdb;

    $posts_stat = get_transient('time_archive');
    
    $total_posts = 0;
    $total_words = 0;
    $total_authors = 0;
    $total_comments =0;
    $total_views = 0;
    $first_post_date = null;

    foreach ($posts_stat as $year => $months) {
        foreach ($months as $month => $posts) {
            foreach ($posts as $post) {
                $total_posts++;
    
                // 字数
                if (isset($post['meta']['words'])) {
                    preg_match('/\d+/', $post['meta']['words'], $matches);
                    $total_words += isset($matches[0]) ? intval($matches[0]) : 0;
                }
    
                // 作者
                $authors[$post['post_author']] = true;
    
                // 评论数
                $total_comments += intval($post['comment_count']);
    
                // 浏览数
                if (isset($post['meta']['views'])) {
                    $total_views += intval($post['meta']['views']);
                }
    
                // 最早发表时间
                $post_time = strtotime($post['post_date']);
                if ($first_post_date === null || $post_time < $first_post_date) {
                    $first_post_date = $post_time;
                }
            }
        }
    }

    $total_authors = count($authors);
    $first_post_date = date('Y-m-d H:i:s', $first_post_date);
    
    // 友情链接数量
    $link_count = count(get_bookmarks(['hide_invisible' => true]));
    
    // 随机友情链接
    $random_link = get_bookmarks([
        'hide_invisible' => true,
        'orderby' => 'rand',
        'limit' => 1
    ]);
    $random_link_data = !empty($random_link) ? $random_link[0] : null;
    
    // 获取第一篇文章的发布日期
    $first_post_date = $wpdb->get_var("
        SELECT post_date 
        FROM $wpdb->posts 
        WHERE post_status = 'publish' AND post_type = 'post' 
        ORDER BY post_date ASC 
        LIMIT 1
    ");
    
    // 计算从第一篇文章发布到现在的天数
    $blog_days = 0;
    if ($first_post_date) {
        $first_post_datetime = new DateTime($first_post_date);
        $now = new DateTime();
        $interval = $now->diff($first_post_datetime);
        $blog_days = $interval->days;
    }
      // 获取管理员上次上线时间
    $admin_last_online = 0;
    $admin_last_online_diff = 0;
    
    // 查询最近的管理员文章修改或评论时间
    // 先检查最近的文章修改
    $recent_post_time = $wpdb->get_var("
        SELECT post_modified 
        FROM $wpdb->posts 
        WHERE post_status = 'publish' 
        AND post_type IN ('post', 'page') 
        ORDER BY post_modified DESC 
        LIMIT 1
    ");
    
    // 检查最近的评论
    $recent_comment_time = $wpdb->get_var("
        SELECT comment_date 
        FROM $wpdb->comments 
        WHERE user_id IN (
            SELECT ID FROM $wpdb->users AS u 
            INNER JOIN $wpdb->usermeta AS um 
            ON u.ID = um.user_id 
            WHERE um.meta_key = '{$wpdb->prefix}capabilities' 
            AND um.meta_value LIKE '%administrator%'
        )
        AND comment_approved = '1' 
        ORDER BY comment_date DESC 
        LIMIT 1
    ");
    
    // 获取元数据中存储的最后登录时间
    $admin_users = get_users(['role' => 'administrator', 'number' => 1]);
    $meta_last_online = '';
    if (!empty($admin_users)) {
        $meta_last_online = get_user_meta($admin_users[0]->ID, 'last_online', true);
    }
    
    // 比较所有时间，取最近的一个
    $times = array_filter([$recent_post_time, $recent_comment_time, $meta_last_online]);
    
    if (!empty($times)) {
        $admin_last_online = max($times);
        
        // 计算时间差
        $last_online_timestamp = strtotime($admin_last_online);
        $current_timestamp = current_time('timestamp');
        
        // 计算以分钟为单位的时间差
        $admin_last_online_diff = max(0, floor(($current_timestamp - $last_online_timestamp) / 60));
    } else {
        // 如果没有找到任何活动记录，使用当前时间
        $admin_last_online = current_time('mysql');
        $admin_last_online_diff = 0;
    }
    
    $stats = [
        'post_count' => $total_posts,
        'comment_count' => $total_comments,
        'visitor_count' => $total_views,
        'link_count' => $link_count,
        'random_link' => $random_link_data,
        'first_post_date' => $first_post_date,
        'blog_days' => $blog_days,
        'admin_last_online' => $admin_last_online,
        'admin_last_online_diff' => $admin_last_online_diff,
        'author_count' => $total_authors,
        'total_words' => $total_words,
    ];
    
    // 缓存数据1小时
    set_transient('sakurairo_site_stats', $stats, 3600);
    
    return $stats;
}

// 获取站点统计信息
$site_stats = get_site_stats();
$components = iro_opt("display_components");
// 准备显示信息
$square_cards = [
    [
        'id' => 'posts',
        'icon' => 'fa-regular fa-file-lines',
        'label' => __('Content Count', 'sakurairo'),
        'value' => number_format($site_stats['post_count']) . ' ' . __('posts', 'sakurairo'),
        'enabled' => in_array('post_count',$components)
    ],
    [
        'id' => 'comments',
        'icon' => 'fa-regular fa-comment',
        'label' => __('Comment Count', 'sakurairo'),
        'value' => number_format($site_stats['comment_count']) . ' ' . __('comments', 'sakurairo'),
        'enabled' => in_array('comment_count',$components)
    ],
    [
        'id' => 'visitors',
        'icon' => 'fa-regular fa-eye',
        'label' => __('Visitor Count', 'sakurairo'),
        'value' => number_format($site_stats['visitor_count']) . ' ' . __('visits', 'sakurairo'),
        'enabled' => in_array('view_count',$components)
    ],
    [
        'id' => 'links',
        'icon' => 'fa-solid fa-link',
        'label' => __('Link Count', 'sakurairo'),
        'value' => number_format($site_stats['link_count']) . ' ' . __('links', 'sakurairo'),
        'enabled' => in_array('link_count',$components)
    ],
    [
        'id' => 'authors',
        'icon' => 'fa-solid fa-users',
        'label' => __('Author Count', 'sakurairo'),
        'value' => number_format($site_stats['author_count']) . ' ' . __('authors', 'sakurairo'),
        'enabled' => in_array('author_count',$components)
    ],
    [
        'id' => 'total_words',
        'icon' => 'fa-solid fa-font',
        'label' => __('Total Words', 'sakurairo'),
        'value' => number_format($site_stats['total_words']) . ' ' . __('words', 'sakurairo'),
        'enabled' => in_array('total_words',$components)
    ],
    [
        'id' => 'blog_days',
        'icon' => 'fa-solid fa-calendar-days',
        'label' => __('Blog Running', 'sakurairo'),
        'value' => number_format($site_stats['blog_days']) . ' ' . __('days', 'sakurairo'),
        'enabled' => in_array('blog_days',$components)
    ],
    [
        'id' => 'admin_online',
        'icon' => 'fa-solid fa-user-clock',
        'label' => __('Last Online', 'sakurairo'),
        'value' => format_time_diff($site_stats['admin_last_online_diff']),
        'enabled' => in_array('admin_online',$components)
    ],
    [
        'id' => 'announcement',
        'icon' => 'fa-solid fa-bullhorn',
        'label' => in_array('stat_announcement_text', __('Latest Announcement', 'sakurairo')),
        'value' => '',
        'enabled' => in_array('random_link',$components),
        'is_multiline' => true
    ]
];

// 随机友链卡片开关
$show_random_link = iro_opt('show_stat_random_link', true);

// 是否显示徽章胶囊功能
$show_medal_capsules = iro_opt('show_medal_capsules', true);

// 判断博客运行天数徽章级别
function get_blog_days_medal($days) {
    if ($days >= 1000) {
        return ['type' => 'gold', 'label' => __('Gold Blogger', 'sakurairo')];
    } elseif ($days >= 500) {
        return ['type' => 'silver', 'label' => __('Silver Blogger', 'sakurairo')];
    } elseif ($days >= 100) {
        return ['type' => 'bronze', 'label' => __('Bronze Blogger', 'sakurairo')];
    }
    return null; // 未达到条件
}

// 判断访客数量徽章级别
function get_visitor_count_medal($visits) {
    if ($visits >= 30000) {
        return ['type' => 'gold', 'label' => __('Gold Popularity', 'sakurairo')];
    } elseif ($visits >= 10000) {
        return ['type' => 'silver', 'label' => __('Silver Popularity', 'sakurairo')];
    } elseif ($visits >= 2000) {
        return ['type' => 'bronze', 'label' => __('Bronze Popularity', 'sakurairo')];
    }
    return null; // 未达到条件
}

// 判断友情链接数量徽章级别
function get_link_count_medal($links) {
    if ($links >= 50) {
        return ['type' => 'gold', 'label' => __('Gold Friendship', 'sakurairo')];
    } elseif ($links >= 30) {
        return ['type' => 'silver', 'label' => __('Silver Friendship', 'sakurairo')];
    } elseif ($links >= 10) {
        return ['type' => 'bronze', 'label' => __('Bronze Friendship', 'sakurairo')];
    }
    return null; // 未达到条件
}

// 判断文章总字数徽章级别
function get_words_count_medal($words) {
    if ($words >= 10000) {
        return ['type' => 'gold', 'label' => __('Gold Author', 'sakurairo')];
    } elseif ($words >= 5000) {
        return ['type' => 'silver', 'label' => __('Silver Author', 'sakurairo')];
    } elseif ($words >= 1000) {
        return ['type' => 'bronze', 'label' => __('Bronze Author', 'sakurairo')];
    }
    return null; // 未达到条件
}

// 获取徽章信息
$blog_days_medal = get_blog_days_medal($site_stats['blog_days']);
$visitor_count_medal = get_visitor_count_medal($site_stats['visitor_count']);
$link_count_medal = get_link_count_medal($site_stats['link_count']);
$words_count_medal = get_words_count_medal($site_stats['total_words']);

// 创建一个关联数组用于跟踪哪些胶囊因为对应徽章而需要关闭
$disabled_capsules = [];
if ($show_medal_capsules) {
    // 如果徽章功能开启，根据徽章存在情况禁用对应的普通胶囊
    if ($blog_days_medal) $disabled_capsules['blog_days'] = true;
    if ($visitor_count_medal) $disabled_capsules['visitors'] = true;
    if ($link_count_medal) $disabled_capsules['links'] = true;
    if ($words_count_medal) $disabled_capsules['total_words'] = true;
}

// 函数用于对徽章按等级进行排序（金牌 > 银牌 > 铜牌）
function sort_medals_by_rank($medals) {
    // 对徽章类型进行排序权重
    $rank_weights = [
        'gold' => 3,
        'silver' => 2,
        'bronze' => 1
    ];
    
    // 按类型排序
    usort($medals, function($a, $b) use ($rank_weights) {
        if (!isset($a['medal']) || !isset($b['medal'])) {
            return 0;
        }
        $rank_a = $rank_weights[$a['medal']['type']] ?? 0;
        $rank_b = $rank_weights[$b['medal']['type']] ?? 0;
        return $rank_b - $rank_a; // 降序排列，高等级在前
    });
    
    return $medals;
}

// 将所有徽章信息整合到一个数组中以便排序
$all_medals = [];
// 检查博客天数奖章：只有当普通胶囊启用时才显示对应奖章
if ($blog_days_medal && iro_opt('show_stat_blog_days', true)) {
    $all_medals[] = [
        'id' => 'blog_days',
        'medal' => $blog_days_medal,
        'stat_value' => number_format($site_stats['blog_days']),
        'text' => __('days', 'sakurairo')
    ];
}
// 检查访客数奖章：只有当普通胶囊启用时才显示对应奖章
if ($visitor_count_medal && iro_opt('show_stat_visitors', true)) {
    $all_medals[] = [
        'id' => 'visitor_count',
        'medal' => $visitor_count_medal,
        'stat_value' => number_format($site_stats['visitor_count']),
        'text' => __('visits', 'sakurairo')
    ];
}
// 检查链接数奖章：只有当普通胶囊启用时才显示对应奖章
if ($link_count_medal && iro_opt('show_stat_links', true)) {
    $all_medals[] = [
        'id' => 'link_count',
        'medal' => $link_count_medal,
        'stat_value' => number_format($site_stats['link_count']),
        'text' => __('links', 'sakurairo')
    ];
}
// 检查文字数奖章：只有当普通胶囊启用时才显示对应奖章
if ($words_count_medal && iro_opt('show_stat_total_words', true)) {
    $all_medals[] = [
        'id' => 'words_count',
        'medal' => $words_count_medal,
        'stat_value' => number_format($site_stats['total_words']),
        'text' => __('words', 'sakurairo')
    ];
}

// 对徽章按等级进行排序
$sorted_medals = sort_medals_by_rank($all_medals);

?>
<style>

</style>

<div class="exhibition-area-container">
    <h1 class="fes-title"> 
        <i class="<?php echo iro_opt('exhibition_area_icon', 'fa-solid fa-laptop'); ?>" aria-hidden="true"></i> <?php echo iro_opt('exhibition_area_title', '展示'); ?> 
    </h1>
      <!-- Bento布局容器 -->
       <div class="bento-grid">
        <!-- 胶囊 -->
        <div class="stat-capsules-container">
            <?php 
            // 1. 首先显示奖章胶囊（如果启用）
            if ($show_medal_capsules && !empty($sorted_medals)): 
                // 使用排序后的徽章数组动态显示徽章，高等级的徽章优先显示
                foreach ($sorted_medals as $medal_data): 
            ?>
            <div class="stat-capsule medal-capsule <?php echo $medal_data['medal']['type']; ?>">
                <i class="fa-solid fa-medal" aria-hidden="true"></i>
                <div class="capsule-content">
                    <span class="capsule-label"><?php echo $medal_data['medal']['label']; ?></span>
                    <span class="capsule-value"><?php echo $medal_data['stat_value']; ?> <?php echo $medal_data['text']; ?></span>
                </div>
            </div>
            <?php 
                endforeach; 
            endif; 
            
            // 2. 然后显示公告胶囊
            foreach ($square_cards as $card) :
                // 检查是否为公告胶囊且启用
                $is_announcement = isset($card['is_multiline']) && $card['is_multiline'];
                if (!$card['enabled'] || !$is_announcement) continue;
            ?>
            <div class="stat-capsule announcement-capsule">
                <i class="<?php echo $card['icon']; ?>" aria-hidden="true"></i>
                <div class="capsule-content">
                    <?php 
                    $announcement_text = $card['label'];
                    $lines = preg_split('/<br\s*\/?>|\n/', $announcement_text, 2);
                    
                    // 判断是否只有一行文本
                    if (count($lines) === 1 || empty($lines[1])) {
                        // 如果只有一行，强制分成两行显示
                        $text_length = mb_strlen($lines[0]);
                        $half_length = ceil($text_length / 2);
                        $first_line = mb_substr($lines[0], 0, $half_length);
                        $second_line = mb_substr($lines[0], $half_length);
                    } else {
                        // 有两行，正常显示
                        $first_line = $lines[0];
                        $second_line = $lines[1];
                    }
                    ?>
                    <span class="announcement-line first-line"><?php echo $first_line; ?></span>
                    <span class="announcement-line second-line"><?php echo $second_line; ?></span>
                </div>
            </div>
            <?php 
            endforeach;
            
            // 3. 接着显示普通胶囊
            foreach ($square_cards as $card) :
                // 只显示普通胶囊（非公告），且启用，且未被徽章功能禁用
                $is_announcement = isset($card['is_multiline']) && $card['is_multiline'];
                if (!$card['enabled'] || $is_announcement || ($show_medal_capsules && isset($disabled_capsules[$card['id']]))) continue;
            ?>
            <div class="stat-capsule">
                <i class="<?php echo $card['icon']; ?>" aria-hidden="true"></i>
                <div class="capsule-content">
                    <span class="capsule-label"><?php echo $card['label']; ?></span>
                    <span class="capsule-value"><?php echo $card['value']; ?></span>
                </div>
            </div>
            <?php 
            endforeach;
            
            // 4. 最后显示随机友链胶囊
            if ($show_random_link && !empty($site_stats['random_link'])) : 
                $random_link = $site_stats['random_link'];
                $link_image = !empty($random_link->link_image) ? $random_link->link_image : iro_opt('vision_resource_basepath', 'https://s.nmxc.ltd/sakurairo_vision/@3.0/') . 'basic/default_avatar.jpg';
            ?>
            <div class="stat-capsule link-capsule">
                <div class="link-avatar">
                    <img src="<?php echo esc_url($link_image); ?>" alt="<?php echo esc_attr($random_link->link_name); ?>">
                </div>
                <div class="link-info">
                    <a href="<?php echo esc_url($random_link->link_url); ?>" target="_blank" class="link-name" rel="external nofollow"><?php echo esc_html($random_link->link_name); ?></a>
                    <span class="link-description"><?php echo __('Meet my friend!', 'sakurairo'); ?></span>
                </div>
            </div>
            <?php endif; ?>
        </div>
          
        <!-- 展示卡片 -->
        <?php 
        if (!empty($exhibition)) : 
            foreach ($exhibition as $item) : 
                if (!is_array($item)) {
                    continue; 
                }
                
                $link = isset($item['link']) ? $item['link'] : '#';
                $title = isset($item['title']) ? $item['title'] : '';
                $description = isset($item['description']) ? $item['description'] : '';
                $img = isset($item['img']) ? $item['img'] : '';
                
                // 如果没有图片，使用默认图片
                if (empty($img)) {
                    $img = iro_opt('vision_resource_basepath', 'https://s.nmxc.ltd/sakurairo_vision/@3.0/') . 'basic/default_display_img.jpg';
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