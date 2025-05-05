<?php
// 定时更新Bilibili收藏夹数据的类

namespace Sakura\API;

class BilibiliFavListCron {
    // 缓存有效期常量（12小时 = 43200秒）
    const CACHE_EXPIRY = 43200;
    
    /**
     * 初始化定时任务
     */
    public static function init() {
        // 添加缓存定时刷新钩子
        add_action('bilibili_favlist_update_cron', array(__CLASS__, 'update_all_favlist_data'));
        
        // 注册激活钩子
        register_activation_hook(__FILE__, array(__CLASS__, 'schedule_updates'));
        
        // 注册停用钩子
        register_deactivation_hook(__FILE__, array(__CLASS__, 'clear_scheduled_updates'));
        
        // 确保定时任务已设置（避免手动调用init时的问题）
        self::ensure_scheduled_event();
    }
    
    /**
     * 确保定时任务已正确设置
     */
    public static function ensure_scheduled_event() {
        if (!wp_next_scheduled('bilibili_favlist_update_cron')) {
            wp_schedule_event(time(), 'twicedaily', 'bilibili_favlist_update_cron');
        }
    }
    
    /**
     * 设置定时更新
     */
    public static function schedule_updates() {
        // 清除可能存在的旧定时任务
        self::clear_scheduled_updates();
        
        // 设置新定时任务, 每12小时运行一次
        wp_schedule_event(time(), 'twicedaily', 'bilibili_favlist_update_cron');
    }
    
    /**
     * 清除定时更新
     */
    public static function clear_scheduled_updates() {
        $timestamp = wp_next_scheduled('bilibili_favlist_update_cron');
        if ($timestamp) {
            wp_unschedule_event($timestamp, 'bilibili_favlist_update_cron');
        }
    }
    
    /**
     * 刷新所有收藏夹数据并更新缓存
     */
    public static function update_all_favlist_data() {
        $uid = iro_opt('bilibili_id');
        if (empty($uid)) {
            error_log("BilibiliFavListCron: UID is empty, cannot update favlist data");
            return false;
        }
        
        try {
            // 初始化Bilibili API处理类
            $bilibili_api = new BilibiliFavList();
            
            // 获取所有收藏夹列表
            $folders_data = $bilibili_api->fetch_folder_api();
            if (!$folders_data || !isset($folders_data['data']) || !isset($folders_data['data']['list'])) {
                error_log("BilibiliFavListCron: Failed to fetch folders list");
                return false;
            }
            
            // 保存收藏夹列表到缓存
            $folders = $folders_data['data'];
            self::save_cache('bilibili_favlist_folders', $folders);
            
            // 更新每个收藏夹的第一页内容
            foreach ($folders['list'] as $folder) {
                $folder_id = $folder['id'];
                $folder_data = $bilibili_api->fetch_folder_item_api($folder_id, 1);
                
                if ($folder_data && isset($folder_data['data'])) {
                    self::save_cache('bilibili_favlist_' . $folder_id . '_1', $folder_data['data']);
                    
                    // 记录日志
                    error_log("BilibiliFavListCron: Updated cache for folder " . $folder['title'] . " (ID: $folder_id)");
                }
            }
            
            return true;
        } catch (\Exception $e) {
            error_log("BilibiliFavListCron: Error updating favlist data: " . $e->getMessage());
            return false;
        }
    }
    
    /**
     * 保存数据到WordPress缓存
     */
    private static function save_cache($key, $data) {
        set_transient($key, $data, self::CACHE_EXPIRY);
        
        // 设置过期时间记录
        $expire_time = time() + self::CACHE_EXPIRY;
        set_transient($key . '_expire', $expire_time, self::CACHE_EXPIRY);
    }
    
    /**
     * 获取缓存
     */
    public static function get_cache($key) {
        return get_transient($key);
    }
    
    /**
     * 获取缓存过期时间
     */
    public static function get_cache_expiry($key) {
        $expire = get_transient($key . '_expire');
        if ($expire === false) {
            return 0; // 已过期或不存在
        }
        return max(0, $expire - time());
    }
}
