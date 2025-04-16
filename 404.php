<?php
/**
 * The template for displaying 404 pages (not found).
 *
 * @link https://codex.wordpress.org/Creating_an_Error_404_Page
 *
 * @package Sakurairo
 */

add_action('wp_head', function() {
    wp_dequeue_style('wp-block-library');
    wp_dequeue_style('wp-block-library-theme');
    wp_dequeue_style('entry-content');
}, 5);

// 获取主题颜色
$theme_matching_color = iro_opt('theme_skin_matching', '#8e95fb'); 

// 使用主题提供的函数获取随机背景图片
$random_bg_url = DEFAULT_FEATURE_IMAGE();
?>
<!DOCTYPE html>
<html <?php language_attributes(); ?>>

<head>
    <meta charset="<?php bloginfo('charset'); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="shortcut icon" href="<?php echo esc_url(iro_opt('favicon_link', '')); ?>" />
    <link rel="stylesheet" href="<?php echo (iro_opt('fontawesome_source','https://s4.zstatic.net/ajax/libs/font-awesome/6.7.2/css/all.min.css') ?? 'https://s4.zstatic.net/ajax/libs/font-awesome/6.7.2/css/all.min.css')?>" type="text/css" media="all" />
    <title>404 - <?php echo esc_html(get_bloginfo('name')); ?></title>
    <?php wp_head(); ?>
    <style>
        .page-404 {
            --primary: <?php echo esc_attr($theme_matching_color); ?>;
            --text: #303030;
            --background: #f5f7fa;
            --card-bg: #ffffff;
            --shadow: 0 20px 60px rgba(0, 0, 0, 0.08);
            
            display: flex;
            align-items: center;
            justify-content: center;
            min-height: 100vh;
            margin: 0;
            padding: 20px;
            background-image: url('<?php echo esc_url($random_bg_url); ?>');
            background-position: center center;
            background-size: cover;
            background-repeat: no-repeat;
            background-attachment: fixed;
            position: relative;
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif;
            color: var(--text);
        }

        .page-404-container {
            width: 100%;
            max-width: 580px;
            background-color: rgba(255, 255, 255, 0.85);
            box-shadow: var(--shadow);
            border-radius: 16px;
            overflow: hidden;
            position: relative;
            padding: 40px;
            text-align: center;
            z-index: 1;
            -webkit-backdrop-filter: saturate(180%) blur(10px);
            backdrop-filter: saturate(180%) blur(10px);
        }
        
        .page-404-header {
            position: relative;
            margin-bottom: 30px;
        }
        
        .page-404-number {
            font-size: 120px;
            font-weight: 700;
            line-height: 1;
            margin: 0;
            background-color: var(--primary);
            -webkit-background-clip: text;
            background-clip: text;
            color: transparent;
            letter-spacing: -5px;
        }
        
        .page-404-title {
            font-size: 28px;
            font-weight: 600;
            margin-top: 0;
            margin-bottom: 20px;
            color: var(--text);
        }
        
        .page-404-message {
            font-size: 16px;
            line-height: 1.6;
            color: #666;
            margin-bottom: 30px;
        }
        
        .page-404-actions {
            display: flex;
            flex-wrap: wrap;
            justify-content: center;
            gap: 15px;
            margin-bottom: 35px;
        }
        
        .page-404-button {
            box-shadow: 0 1px 30px -4px #e8e8e8;
            color: #505050;
            background: rgba(255, 255, 255, 0.6);
            border-radius: 10px;
            border: 1px solid #FFFFFF;
            font-size: 14px;
            font-weight: 500;
            cursor: pointer;
            transition: all 0.3s ease;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            padding: 10px 20px;
            max-width: 60px;
            height: 42px;
        }
        
        .page-404-button:hover {
            color: #FFFFFF;
            background-color: var(--primary);
            border: 1px solid var(--primary);
            box-shadow: 0 1px 20px 10px #e8e8e8;
        }

        .page-404-search-form {
            position: relative;
            display: flex;
        }
        
        .page-404-search-input {
            flex: 1;
            padding: 10px 12px !important;
            font-size: 14px;
            width: 100%;
            box-shadow: 0 1px 30px -4px #e8e8e8 !important;
            color: #505050 !important;
            background: rgba(255, 255, 255, 0.6) !important;
            border-radius: 10px !important;
            border: 1px solid #FFFFFF !important;
            height: 42px;
        }
        
        .page-404-search-input:focus {
            outline: none;
            border-color: var(--primary);
            box-shadow: 0 0 0 3px rgba(142, 149, 251, 0.2);
        }
        
        .page-404-footer {
            margin-top: 30px;
            font-size: 13px;
            color: #999;
        }
        
        @media (max-width: 580px) {
            .page-404-container {
                padding: 30px 20px;
            }
            
            .page-404-number {
                font-size: 90px;
            }
            
            .page-404-title {
                font-size: 24px;
            }
        }
    </style>
</head>

<body class="page-404">
    <div class="page-404-container">
        <div class="page-404-header">
            <h1 class="page-404-number">404</h1>
        </div>
        
        <h2 class="page-404-title"><?php _e('Page Not Found', 'sakurairo'); ?></h2>
        
        <p class="page-404-message">
            <?php _e('The page you are looking for might have been removed, had its name changed, or is temporarily unavailable.', 'sakurairo'); ?>
        </p>
        
        <div class="page-404-actions">
            <a id="golast" href="javascript:history.go(-1);" class="page-404-button">
                <i class="fa-solid fa-rotate-left"></i>
            </a>
            <a id="gohome" href="<?php echo esc_url(home_url('/')); ?>" class="page-404-button">
                <i class="fa-solid fa-house"></i>
            </a>
            <form class="page-404-search-form" method="get" action="<?php echo esc_url(home_url('/')); ?>" role="search">
                <input class="page-404-search-input" type="search" name="s" placeholder="<?php _e('Search...', 'sakurairo'); ?>" required>
            </form>
        </div>
        
        <div class="page-404-footer">
            <?php echo esc_html(get_bloginfo('name')); ?>
        </div>
    </div>
    <?php wp_footer(); ?>
</body>

</html>
