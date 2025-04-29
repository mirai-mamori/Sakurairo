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

    $posts_stat = get_transient('time_archive');

    function archive_data_check($data) {
        if (!is_array($data) || empty($data)) {
            return false;
        }
        foreach ($data as $year => $months) {
            if (!is_array($months) || empty($months)) {
                continue;
            }
            foreach ($months as $month => $posts) {
                if (!is_array($posts) || empty($posts)) {
                    continue;
                }
                if (isset($posts[0]['ID'], $posts[0]['meta']['words'], $posts[0]['post_author'])) {
                    return true;
                }
            }
        }
        return false;
    }
    if (!archive_data_check($posts_stat)) {
        $posts_stat = get_archive_info();
    }    
    
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
    // 第一篇文章的发布日期
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
    
    // 计算从第一篇文章发布到现在的天数
    $blog_days = 0;
    if ($first_post_date) {
        $first_post_datetime = new DateTime($first_post_date);
        $now = new DateTime();
        $interval = $now->diff($first_post_datetime);
        $blog_days = $interval->days;
    }

    function get_latest_admin_online_time() {
        $admins = get_users(['role' => 'administrator']);
        $latest_time = 0;
        $latest_admin = null;
    
        foreach ($admins as $admin) {
            $last_online = get_user_meta($admin->ID, 'last_online', true);
            if ($last_online) {
                $timestamp = strtotime($last_online);
                if ($timestamp > $latest_time) {
                    $latest_time = $timestamp;
                    $latest_admin = [
                        'user' => $admin,
                        'time' => $last_online
                    ];
                }
            }
        }
    
        return $latest_admin;
    }
    
    $latest_admin_info = get_latest_admin_online_time();
    
    if (!empty($latest_admin_info)) {
        $admin_last_online = $latest_admin_info['time']; // ✅ 正确取时间字符串
        $last_online_timestamp = strtotime($admin_last_online);
        $current_timestamp = current_time('timestamp');
    
        // 计算以分钟为单位的时间差
        $admin_last_online_diff = max(0, floor(($current_timestamp - $last_online_timestamp) / 60));
    } else {
        // 没有记录，使用当前时间
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
$components = iro_opt("display_components",[]);

// 准备显示信息
$square_cards = [
    'post_count'       => [
        'icon'    => 'fa-regular fa-file-lines',
        'label'   => __('Content Count', 'sakurairo'),
        'value'   => number_format($site_stats['post_count']) . ' ' . __('posts', 'sakurairo'),
        'enabled' => in_array('post_count', $components)
    ],
    'comment_count'    => [
        'icon'    => 'fa-regular fa-comment',
        'label'   => __('Comment Count', 'sakurairo'),
        'value'   => number_format($site_stats['comment_count']) . ' ' . __('comments', 'sakurairo'),
        'enabled' => in_array('comment_count', $components)
    ],
    'view_count'    => [
        'icon'    => 'fa-regular fa-eye',
        'label'   => __('Visitor Count', 'sakurairo'),
        'value'   => number_format($site_stats['visitor_count']) . ' ' . __('visits', 'sakurairo'),
        'enabled' => in_array('view_count', $components)
    ],
    'link_count'       => [
        'icon'    => 'fa-solid fa-link',
        'label'   => __('Link Count', 'sakurairo'),
        'value'   => number_format($site_stats['link_count']) . ' ' . __('links', 'sakurairo'),
        'enabled' => in_array('link_count', $components)
    ],
    'author_count'     => [
        'icon'    => 'fa-solid fa-users',
        'label'   => __('Author Count', 'sakurairo'),
        'value'   => number_format($site_stats['author_count']) . ' ' . __('authors', 'sakurairo'),
        'enabled' => in_array('author_count', $components)
    ],
    'total_words' => [
        'icon'    => 'fa-solid fa-font',
        'label'   => __('Total Words', 'sakurairo'),
        'value'   => number_format($site_stats['total_words']) . ' ' . __('words', 'sakurairo'),
        'enabled' => in_array('total_words', $components)
    ],
    'blog_days'   => [
        'icon'    => 'fa-solid fa-calendar-days',
        'label'   => __('Blog Running', 'sakurairo'),
        'value'   => number_format($site_stats['blog_days']) . ' ' . __('days', 'sakurairo'),
        'enabled' => in_array('blog_days', $components)
    ],
    'admin_online'=> [
        'icon'    => 'fa-solid fa-user-clock',
        'label'   => __('Last Online', 'sakurairo'),
        'value'   => format_time_diff($site_stats['admin_last_online_diff']),
        'enabled' => in_array('admin_online', $components)
    ],
    'announcement'=> [
        'icon'         => 'fa-solid fa-bullhorn',
        'label'        => iro_opt('stat_announcement_text', __('Latest Announcement', 'sakurairo')),
        'value'        => '',
        'enabled'      => in_array('announcement', $components)
    ],
    'random_link'=> [
        'enabled'      => in_array('random_link', $components),
    ]
];

// 是否显示徽章胶囊功能
$show_medal_capsules = iro_opt('show_medal_capsules', true);

// 判断博客运行天数徽章级别
function get_blog_days_medal($days) {
    if ($days >= 1000) return ['type'=>'gold',   'label'=>__('Gold Blogger','sakurairo')];
    if ($days >= 500)  return ['type'=>'silver', 'label'=>__('Silver Blogger','sakurairo')];
    if ($days >= 100)  return ['type'=>'bronze', 'label'=>__('Bronze Blogger','sakurairo')];
    return null;
}

// 判断访客数量徽章级别
function get_visitor_count_medal($visits) {
    if ($visits >= 30000) return ['type'=>'gold',   'label'=>__('Gold Popularity','sakurairo')];
    if ($visits >= 10000) return ['type'=>'silver', 'label'=>__('Silver Popularity','sakurairo')];
    if ($visits >= 2000)  return ['type'=>'bronze', 'label'=>__('Bronze Popularity','sakurairo')];
    return null;
}

// 判断友情链接数量徽章级别
function get_link_count_medal($links) {
    if ($links >= 50) return ['type'=>'gold',   'label'=>__('Gold Friendship','sakurairo')];
    if ($links >= 30) return ['type'=>'silver', 'label'=>__('Silver Friendship','sakurairo')];
    if ($links >= 10) return ['type'=>'bronze', 'label'=>__('Bronze Friendship','sakurairo')];
    return null;
}

// 判断文章总字数徽章级别
function get_words_count_medal($words) {
    if ($words >= 10000) return ['type'=>'gold',   'label'=>__('Gold Author','sakurairo')];
    if ($words >= 5000)  return ['type'=>'silver', 'label'=>__('Silver Author','sakurairo')];
    if ($words >= 1000)  return ['type'=>'bronze', 'label'=>__('Bronze Author','sakurairo')];
    return null;
}

// 统计徽章信息
$medal_levels = [];
if ($show_medal_capsules) {
    $m = get_blog_days_medal($site_stats['blog_days']);
    if ($m) $medal_levels['blog_days'] = $m;
    $m = get_visitor_count_medal($site_stats['visitor_count']);
    if ($m) $medal_levels['view_count'] = $m;
    $m = get_link_count_medal($site_stats['link_count']);
    if ($m) $medal_levels['link_count'] = $m;
    $m = get_words_count_medal($site_stats['total_words']);
    if ($m) $medal_levels['total_words'] = $m;
}

?>

<div class="exhibition-area-container">
    <h1 class="fes-title"> 
        <i class="<?php echo iro_opt('exhibition_area_icon', 'fa-solid fa-laptop'); ?>" aria-hidden="true"></i> <?php echo iro_opt('exhibition_area_title', '展示'); ?> 
    </h1>
      <!-- Bento布局容器 -->
       <div class="bento-grid">
        <!-- 胶囊 -->
        <div class="stat-capsules-container">
            <?php foreach($components as $component) :
                switch ($component):
                    case 'post_count':
                    case 'comment_count':
                    case 'author_count':
                    case 'admin_online':
                        if ($square_cards[$component]['enabled']): // 理论上选项中不添加相关组件后就不会case到，因为没有?>
                            <div class="stat-capsule">
                              <i class="<?php echo $square_cards[$component]['icon']; ?>"></i>
                              <div class="capsule-content">
                                <span class="capsule-label"><?php echo $square_cards[$component]['label']; ?></span>
                                <span class="capsule-value"><?php echo $square_cards[$component]['value']; ?></span>
                              </div>
                            </div>
                          <?php endif;
                          break;

                    case 'view_count':
                    case 'link_count':
                    case 'total_words':
                    case 'blog_days':
                        // 如果有对应徽章，就渲染徽章，否则渲染普通卡片
                        if (isset($medal_levels[$component])): 
                            $medal = $medal_levels[$component]; ?>
                            <div class="stat-capsule medal-capsule <?php echo $medal['type']; ?>">
                            <i class="fa-solid fa-medal"></i>
                            <div class="capsule-content">
                                <span class="capsule-label"><?php echo $medal['label']; ?></span>
                                <span class="capsule-value">
                                <?php 
                                    // 徽章下依旧显示对应的数值
                                    echo $square_cards[$component]['value']; 
                                ?>
                                </span>
                            </div>
                            </div>
                        <?php else: 
                            if ($square_cards[$component]['enabled']): ?>
                            <div class="stat-capsule">
                                <i class="<?php echo $square_cards[$component]['icon']; ?>"></i>
                                <div class="capsule-content">
                                <span class="capsule-label"><?php echo $square_cards[$component]['label']; ?></span>
                                <span class="capsule-value"><?php echo $square_cards[$component]['value']; ?></span>
                                </div>
                            </div>
                        <?php   endif;
                        endif;
                        break;

                    case 'random_link':
                        if ($square_cards[$component]['enabled'] && !empty($site_stats['random_link'])):
                            $rl = $site_stats['random_link'];
                            $img = $rl->link_image ?: 
                                    iro_opt('vision_resource_basepath').'/basic/default_avatar.jpg'; ?>
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
                        if ($square_cards['announcement']['enabled']): 
                            // 强制两行显示
                            $text = $square_cards['announcement']['label'];
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
                endswitch; ?>
            <?php endforeach; ?>
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