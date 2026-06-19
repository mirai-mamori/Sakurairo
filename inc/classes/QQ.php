<?php

namespace Sakura\API;

class QQ
{
    /**
     * Get QQ user info from external API.
     * Used when user actively inputs QQ number in comment form.
     *
     * @param string $qq QQ number (digits only, 3+ chars)
     * @return array{status: int, success: bool, message: string, avatar?: string, name?: string}
     */
    public static function get_qq_info($qq) {
        $qq = sanitize_text_field($qq);
        if (empty($qq) || !preg_match('/^\d{3,}$/', $qq)) {
            return array(
                'status' => 400,
                'success' => false,
                'message' => 'Invalid QQ number format.'
            );
        }

        $response = wp_remote_get(
            'https://api.qjqq.cn/api/qqinfo?qq=' . urlencode($qq),
            array('timeout' => 5)
        );

        if (is_wp_error($response) || wp_remote_retrieve_response_code($response) !== 200) {
            return array(
                'status' => 404,
                'success' => false,
                'message' => 'QQ number not exist.'
            );
        }

        $name = json_decode(wp_remote_retrieve_body($response), true);
        if (!is_array($name) || !isset($name['code']) || $name['code'] != 200) {
            return array(
                'status' => 404,
                'success' => false,
                'message' => 'QQ number not exist.'
            );
        }

        return array(
            'status' => 200,
            'success' => true,
            'message' => 'success',
            'avatar' => 'https://q2.qlogo.cn/headimg_dl?dst_uin=' . urlencode($qq) . '&spec=100',
            'name' => isset($name['name']) ? $name['name'] : '',
        );
    }

    /**
     * Get QQ avatar URL by comment ID (qlogo direct).
     * Reads QQ number from comment meta, returns qlogo URL.
     *
     * @param int $comment_id WordPress comment ID
     * @return string|false Avatar URL on success, false on failure
     */
    public static function get_qq_avatar_url($comment_id) {
        $comment_id = intval($comment_id);
        if ($comment_id <= 0) {
            return false;
        }

        $qq_number = get_comment_meta($comment_id, 'new_field_qq', true);
        $qq_number = sanitize_text_field($qq_number);
        if (empty($qq_number) || !preg_match('/^\d{3,}$/', $qq_number)) {
            return false;
        }

        return 'https://q2.qlogo.cn/headimg_dl?dst_uin=' . urlencode($qq_number) . '&spec=100';
    }

    /**
     * Get QQ avatar URL by comment ID (ptlogin2 fallback).
     * Uses ptlogin2 API to resolve avatar URL, better CDN compatibility.
     *
     * @param int $comment_id WordPress comment ID
     * @return string|false Avatar URL on success, false on failure
     */
    public static function get_qq_avatar_url_ptlogin2($comment_id) {
        $comment_id = intval($comment_id);
        if ($comment_id <= 0) {
            return false;
        }

        $qq_number = get_comment_meta($comment_id, 'new_field_qq', true);
        $qq_number = sanitize_text_field($qq_number);
        if (empty($qq_number) || !preg_match('/^\d{3,}$/', $qq_number)) {
            return false;
        }

        $response = wp_remote_get(
            'https://ptlogin2.qq.com/getface?appid=1006102&imgtype=3&uin=' . urlencode($qq_number),
            array('timeout' => 5)
        );
        if (is_wp_error($response) || wp_remote_retrieve_response_code($response) !== 200) {
            return false;
        }

        $body = wp_remote_retrieve_body($response);
        if (!preg_match('/:\"([^\"]*)\"/i', $body, $matches) || empty($matches[1])) {
            return false;
        }

        return esc_url_raw($matches[1]);
    }

    /**
     * Get QQ avatar binary data by comment ID with file caching.
     *
     * Caching strategy:
     * 1. File cache (wp-content/cache/qq-avatars/) — avoids DB bloat
     * 2. Cache key by QQ number — same QQ across different comments shares one cache
     * 3. TTL: 7 days — avatar rarely changes
     *
     * On cache miss: fetches from qlogo via wp_remote_get(), stores, returns.
     * On fetch failure or file cache unavailable: returns false
     *     (caller returns HTTP 404, browser triggers onerror → imgError() → missing avatar).
     *
     * @param int $comment_id WordPress comment ID
     * @return string|false Binary image data on success, false on failure
     */
    public static function get_qq_avatar_data($comment_id) {
        $comment_id = intval($comment_id);
        if ($comment_id <= 0) {
            return false;
        }

        $qq_number = get_comment_meta($comment_id, 'new_field_qq', true);
        $qq_number = sanitize_text_field($qq_number);
        if (empty($qq_number) || !preg_match('/^\d{3,}$/', $qq_number)) {
            return false;
        }

        // Cache key by QQ number — same QQ across different comments shares one cache entry
        $cache_key = md5($qq_number);
        $cache_dir = WP_CONTENT_DIR . '/cache/qq-avatars';
        $cache_file = $cache_dir . '/' . $cache_key . '.jpg';

        // 1. Try file cache (7 day TTL)
        if (file_exists($cache_file) && (time() - filemtime($cache_file)) < 7 * DAY_IN_SECONDS) {
            $data = @file_get_contents($cache_file);
            if ($data !== false) {
                return $data;
            }
        }

        // 2. Fetch from qlogo
        $imgurl = 'https://q2.qlogo.cn/headimg_dl?dst_uin=' . urlencode($qq_number) . '&spec=100';
        $response = wp_remote_get(esc_url_raw($imgurl), array('timeout' => 10));
        if (is_wp_error($response) || wp_remote_retrieve_response_code($response) !== 200) {
            return false;
        }

        $imgdata = wp_remote_retrieve_body($response);
        if (empty($imgdata)) {
            return false;
        }

        // 3. Store to file cache (best-effort, failure is non-fatal)
        if (wp_mkdir_p($cache_dir)) {
            @file_put_contents($cache_file, $imgdata);
        }
        // File cache unavailable — return data anyway, just without caching.
        // Next request will fetch from qlogo again. Browser Cache-Control still helps.

        return $imgdata;
    }
}
