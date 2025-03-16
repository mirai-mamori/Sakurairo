<?php
/**
 * ChatGPT集成功能主文件
 */
namespace IROChatGPT;

// 安全检查
if (!defined('ABSPATH')) {
    exit;
}

// 移除之前添加的选项同步文件(如果存在)
// 改为直接使用与摘要功能相同的选项

// 加载基本钩子和功能
require_once dirname(__FILE__) . '/hooks.php';

// 加载管理页面
require_once dirname(__FILE__) . '/aigc-manage.php';
