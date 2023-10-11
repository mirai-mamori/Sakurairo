<?php

namespace Sakura\API;

class QQ
{
    public static function get_qq_info($qq) {
        $get_info = file_get_contents('https://api.qjqq.cn/api/qqinfo?qq=' . $qq);
        $name = json_decode($get_info, true);
        if ($name) {
            if ($name['code'] == 200){
                $output = array(
                    'status' => 200,
                    'success' => true,
                    'message' => 'success',
                    'avatar' => 'https://q2.qlogo.cn/headimg_dl?dst_uin=' . $qq . '&spec=100',
                    'name' => $name['name'],
                );
            }
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
        if (isset($encrypted)) {
            $iv = str_repeat($sakura_privkey, 2);
            $encrypted = base64_decode(urldecode($encrypted));
            $qq_number = openssl_decrypt($encrypted, 'aes-128-cbc', $sakura_privkey, 0, $iv);
            preg_match('/^\d{3,}$/', $qq_number, $matches);
            return 'https://q2.qlogo.cn/headimg_dl?dst_uin=' . $matches[0] . '&spec=100';
        }
    }
}
