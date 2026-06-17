<?php

namespace Sakura\API;

class QQ
{
    public static function get_qq_info($qq) {
        $response = wp_remote_get('https://api.qjqq.cn/api/qqinfo?qq=' . urlencode($qq), array('timeout' => 5));
        if (is_wp_error($response) || wp_remote_retrieve_response_code($response) !== 200) {
            return array(
                'status' => 404,
                'success' => false,
                'message' => 'QQ number not exist.'
            );
        }
        $name = json_decode(wp_remote_retrieve_body($response), true);
        if ($name && isset($name['code']) && $name['code'] == 200) {
            $output = array(
                'status' => 200,
                'success' => true,
                'message' => 'success',
                'avatar' => 'https://q2.qlogo.cn/headimg_dl?dst_uin=' . $qq . '&spec=100',
                'name' => isset($name['name']) ? $name['name'] : '',
            );
        } else {
            $output = array(
                'status' => 404,
                'success' => false,
                'message' => 'QQ number not exist.'
            );
        }
        return $output;
    }

    public static function get_qq_avatar($encrypted) {
        global $sakura_privkey;
        if (!isset($sakura_privkey) || empty($encrypted)) {
            return false;
        }

        // 解码：urldecode → base64_decode → 分离 IV 和密文
        $decoded = base64_decode(urldecode($encrypted));
        if ($decoded === false) {
            return false;
        }

        $iv_length = openssl_cipher_iv_length('aes-128-cbc');
        if (strlen($decoded) <= $iv_length) {
            return false;
        }

        // 提取前 16 字节作为 IV，与 change_avatar 加密端一致
        $iv = substr($decoded, 0, $iv_length);
        $ciphertext = substr($decoded, $iv_length);

        $qq_number = openssl_decrypt($ciphertext, 'aes-128-cbc', $sakura_privkey, 0, $iv);
        if ($qq_number === false) {
            return false;
        }

        // 验证解密结果为纯数字 QQ 号
        if (!preg_match('/^\d{3,}$/', $qq_number)) {
            return false;
        }

        return 'https://q2.qlogo.cn/headimg_dl?dst_uin=' . $qq_number . '&spec=100';
    }
}
