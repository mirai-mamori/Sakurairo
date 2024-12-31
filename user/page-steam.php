<?php
/**
 Template Name: Steam库模板
 */
get_header(); 
?>
<meta name="referrer" content="same-origin">
<style>
.comments{display: none}
.site-content{max-width:1280px}

.container {
    display: flex;
    flex-wrap: wrap;
    gap: 20px;
    padding: 20px;
    margin: 0 auto;
    justify-content: flex-start;
}

.anime-card {
    width: 276px;
    background: #fff;
    border-radius: 8px;
    box-shadow: 0 2px 8px rgba(0,0,0,0.1);
    overflow: hidden;
    transition: transform 0.2s;
    flex-shrink: 0;
}

.anime-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 4px 12px rgba(0,0,0,0.2);
}

.anime-image {
    width: 276px;
    height: 129px;
    overflow: hidden;
}

.anime-image img {
    width: 100%;
    height: 100%;
    object-fit: cover;
}

.anime-info {
    padding: 12px;
}

.anime-title {
    font-size: 14px;
    font-weight: bold;
    margin-bottom: 8px;
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
}

.anime-desc {
    font-size: 12px;
    color: #666;
    display: -webkit-box;
    -webkit-line-clamp: 3;
    -webkit-box-orient: vertical;
    overflow: hidden;
}
</style>
</head>

<?php while(have_posts()) : the_post(); ?>
<?php if(!iro_opt('patternimg') || !get_post_thumbnail_id(get_the_ID())) { ?>
<span class="linkss-title"><?php the_title();?></span>
<?php } ?>

<article <?php post_class("post-item"); ?>>
    <?php the_content( '', true ); ?>
    <section class="steam-library">
        <?php if (iro_opt('steam_id') && iro_opt('steam_key')):?>
            <div class="container">
            <?php
            // Steam API 配置
            $steamid = iro_opt('steam_id');
            $key = iro_opt('steam_key');

            // 构建Steam API URL
            $url = sprintf(
                'https://api.steampowered.com/IPlayerService/GetOwnedGames/v1/?key=%s&steamid=%s&include_appinfo=true',
                urlencode($key),
                urlencode($steamid)
            );

            // 获取Steam数据
            $response = wp_remote_get($url, array('timeout' => 10));

            if (!is_wp_error($response) && wp_remote_retrieve_response_code($response) === 200) {
                $data = json_decode(wp_remote_retrieve_body($response), true);

                if (isset($data['response']['games']) && !empty($data['response']['games'])) {
                    // 按最后游玩时间排序
                    usort($data['response']['games'], function($a, $b) {
                        return $b['rtime_last_played'] - $a['rtime_last_played'];
                    });

                    foreach ($data['response']['games'] as $game) : ?>
                        <div class="anime-card">
                            <a href="<?php echo esc_url('https://store.steampowered.com/app/' . $game['appid']); ?>" target="_blank">
                                <div class="anime-image">
                                    <img src="<?php echo esc_url('https://shared.fastly.steamstatic.com/store_item_assets/steam/apps/' . $game['appid'] . '/header.jpg'); ?>" 
                                         alt="<?php echo esc_attr($game['name']); ?>">
                                </div>
                            </a>
                            <div class="anime-info">
                                <div class="anime-title"><?php echo esc_html($game['name']); s?></div>
                                <?php
                                $playtime = $game['playtime_forever'];
                                if ($playtime > 120) {
                                    $hours = number_format($playtime / 60, 2, '.', '');
                                    printf('<div class="anime-desc">%s</div>', esc_html(sprintf('游戏时长: %s 小时', $hours)));
                                } else {
                                    printf('<div class="anime-desc">%s</div>', esc_html(sprintf('游戏时长: %d 分钟', $playtime)));
                                }
                                
                                $lastPlayed = wp_date('Y-m-d H:i:s', $game['rtime_last_played']);
                                if ($rtime_last_played > 0) {
                                    printf('<div class="anime-desc">%s</div>', esc_html(sprintf('上次启动: %s', $lastPlayed)));
                                } else {
                                    printf('<div class="anime-desc">%s</div>', esc_html(sprintf('尚未游玩')));
                                }
                                ?>
                            </div>
                        </div>
                    <?php endforeach;
                } else {
                    echo '<p>' . esc_html__('没有游戏数据', 'sakurairo') . '</p>';
                }
            } else {
                echo '<p>' . esc_html__('无法连接到Steam API, 可能是信息填写错误', 'sakurairo') . '</p>';
                
            }
            ?>
            </div>
        <?php else: ?>
            <div class="row">
                <p><?php _e("请在主题设置填写Steam 64ID和Steam API Key", "sakurairo"); ?></p>
            </div>
        <?php endif; ?>
    </section>
</article>
<?php endwhile; ?>

<?php
get_footer();
?>