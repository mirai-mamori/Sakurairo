<?php

namespace Sakura\API;

class Images
{
    private $chevereto_api_key;
    private $imgur_client_id;
    private $smms_client_id;
    private $lsky_api_key;

    public function __construct() {
        $this->chevereto_api_key = iro_opt('chevereto_api_key');
        $this->lsky_api_key = iro_opt('lsky_api_key');
        $this->imgur_client_id = iro_opt('imgur_client_id');
        $this->smms_client_id = iro_opt('smms_client_id');
    }

    /**
     * LSky Pro upload interface
     */
    public function LSKY_API($image) {
        $upload_url = iro_opt('lsky_url') . '/api/v1/upload';
        $filename = $image['cmt_img_file']['name'];
        $filedata = $image['cmt_img_file']['tmp_name'];
        $Boundary = wp_generate_password();
        $bits = file_get_contents($filedata);
        $args = array(
            'headers' => array(
                'Authorization' => 'Bearer ' . $this->lsky_api_key,
                'Accept' => 'application/json',
                'Content-Type' => 'multipart/form-data; boundary='.$Boundary,
            ),
            'body' => "--$Boundary\r\nContent-Disposition: form-data; name=\"file\"; filename=\"$filename\"\r\n\r\n$bits\r\n\r\n--$Boundary--"
        );

        $response = wp_remote_post($upload_url, $args);
        $reply = json_decode($response['body']);

        if ($reply->status == true) {
            $status = 200;
            $success = true;
            $message = 'success';
            $link = $reply->data->links->url;
            $proxy = iro_opt('comment_image_proxy') . $link;
        } else {
            $status = 400;
            $success = false;
            $message = $reply->message;
            $link = 'https://s.nmxc.ltd/sakurairo_vision/@2.5/basic/default_d_h_large.gif';
            $proxy = iro_opt('comment_image_proxy') . $link;
        }
        $output = array(
            'status' => $status,
            'success' => $success,
            'message' => $message,
            'link' => $link,
            'proxy' => $proxy,
        );
        return $output;
    }

    /**
     * Chevereto upload interface
     */
    public function Chevereto_API($image) {
        $upload_url = iro_opt('cheverto_url') . '/api/1/upload';
        $args = array(
            'body' => array(
                'source' => base64_encode($image),
                'key' => $this->chevereto_api_key,
            ),
        );

        $response = wp_remote_post($upload_url, $args);
        $reply = json_decode($response['body']);

        if ($reply->status_txt == 'OK' && $reply->status_code == 200) {
            $status = 200;
            $success = true;
            $message = 'success';
            $link = $reply->image->image->url;
            $proxy = iro_opt('comment_image_proxy') . $link;
        } else {
            $status = $reply->status_code;
            $success = false;
            $message = $reply->error->message;
            $link = 'https://s.nmxc.ltd/sakurairo_vision/@2.5/basic/default_d_h_large.gif';
            $proxy = iro_opt('comment_image_proxy') . $link;
        }
        $output = array(
            'status' => $status,
            'success' => $success,
            'message' => $message,
            'link' => $link,
            'proxy' => $proxy,
        );
        return $output;
    }

    /**
     * Imgur upload interface
     */
    public function Imgur_API($image) {
        $upload_url = iro_opt('imgur_upload_image_proxy');
        $args = array(
            'headers' => array(
                'Authorization' => 'Client-ID ' . $this->imgur_client_id,
            ),
            'body' => array(
                'image' => base64_encode($image),
            ),
        );

        $response = wp_remote_post($upload_url, $args);
        $reply = json_decode($response['body']);

        if ($reply->success && $reply->status == 200) {
            $status = 200;
            $success = true;
            $message = 'success';
            $link = $reply->data->link;
            $proxy = iro_opt('comment_image_proxy') . $link;
        } else {
            $status = $reply->status;
            $success = false;
            $message = $reply->data->error;
            $link = 'https://s.nmxc.ltd/sakurairo_vision/@2.5/basic/default_d_h_large.gif';
            $proxy = iro_opt('comment_image_proxy') . $link;
        }
        $output = array(
            'status' => $status,
            'success' => $success,
            'message' => $message,
            'link' => $link,
            'proxy' => $proxy,
        );
        return $output;
    }

    /**
     * smms upload interface
     */
    public function SMMS_API($image) {
        $client_id = $this->smms_client_id;
        $upload_url = 'https://sm.ms/api/v2/upload';
        $filename = $image['cmt_img_file']['name'];
        $filedata = $image['cmt_img_file']['tmp_name'];
        $Boundary = wp_generate_password();
        $bits = file_get_contents($filedata);

        $args = array(
            'headers' => "Content-Type: multipart/form-data; boundary=$Boundary\r\n\r\nAuthorization: Basic $client_id\r\n\r\nUser-Agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/78.0.3904.97",
            'body' => "--$Boundary\r\nContent-Disposition: form-data; name=\"smfile\"; filename=\"$filename\"\r\n\r\n$bits\r\n\r\n--$Boundary--"
        );

        $response = wp_remote_post($upload_url, $args);
        $reply = json_decode($response['body']);

        if ($reply->success && $reply->code == 'success') {
            $status = 200;
            $success = true;
            $message = $reply->message;
            $link = $reply->data->url;
            $proxy = iro_opt('comment_image_proxy') . $link;
        } else if (preg_match("/Image upload repeated limit/i", $reply->message, $matches)) {
            $status = 200; // sm.ms 接口不规范，建议检测到重复的情况下返回标准化的 code，并单独把 url 放进一个字段
            $success = true;
            $message = $reply->message;
            $link = str_replace('Image upload repeated limit, this image exists at: ', '', $reply->message);
            $proxy = iro_opt('comment_image_proxy') . $link;
        } else {
            $status = 400;
            $success = false;
            $message = $reply->message;
            $link = 'https://s.nmxc.ltd/sakurairo_vision/@2.5/basic/default_d_h_large.gif';
            $proxy = iro_opt('comment_image_proxy') . $link;
        }
        $output = array(
            'status' => $status,
            'success' => $success,
            'message' => $message,
            'link' => $link,
            'proxy' => $proxy,
        );
        return $output;
    }

    public static function cover_gallery($size = 'source') {
        if (iro_opt('random_graphs_options') == 'local') {
            $img_array = glob(STYLESHEETPATH . '/manifest/gallary/*.{gif,jpg,jpeg,png}', GLOB_BRACE);
            if (count($img_array) == 0){
                return ['status'=>False,'msg'=>'ERROR：请联系管理员查看gallary目录中是否存在图片！'];
            }
            $img = array_rand($img_array);
            $imgurl = trim($img_array[$img]);
            $imgurl = str_replace(STYLESHEETPATH, get_template_directory_uri(), $imgurl);
        } elseif (iro_opt('random_graphs_options') == 'external_api') {
            $imgurl = iro_opt('random_graphs_link');
        } else {
            // global $sakura_image_array;
            $temp = file_get_contents(STYLESHEETPATH . "/manifest/manifest.json");
            // $img_array = json_decode($sakura_image_array, true);
            $img_array = json_decode($temp, true);
            if (!$img_array){
                return ['status'=>False,'msg'=>'ERROR：请联系管理员查看manifest.json中是否存在图片！'];
            }
            $array_keys = array_keys($img_array);
            $img = array_rand($array_keys);
            if (iro_opt('random_graphs_link',NULL) && iro_opt('random_graphs_link') != home_url( '/')) {
                $img_domain = iro_opt('random_graphs_link');
            } else {
                $img_domain = get_template_directory_uri();
            }
            $format = strpos($_SERVER['HTTP_ACCEPT'], 'image/webp') ? 'webp' : 'jpeg';
            $imgurl = "{$img_domain}/manifest/{$format}/{$array_keys[$img]}.{$size}.{$format}";
        }
        return ['status'=>True,'url'=>$imgurl];
    }

    public static function mobile_cover_gallery() {
        if (iro_opt('random_graphs_options') == 'local') {
            $img_array = glob(STYLESHEETPATH . '/manifest/gallary/*.{gif,jpg,jpeg,png}', GLOB_BRACE);
            if (count($img_array) == 0){
                return ['status'=>False,'msg'=>'没有找到图片，请联系管理员检查gallary目录下是否存在图片'];
            }
            $img = array_rand($img_array);
            $imgurl = trim($img_array[$img]);
            $imgurl = str_replace(STYLESHEETPATH, get_template_directory_uri(), $imgurl);
        } elseif (iro_opt('random_graphs_options') == 'external_api') {
          //$imgurl = iro_opt('random_graphs_link');
           $imgurl = iro_opt('random_graphs_link_mobile');
        } else {
            // global $sakura_mobile_image_array;
            $temp = file_get_contents(STYLESHEETPATH . '/manifest/manifest_mobile.json');
            // $img_array = json_decode($sakura_mobile_image_array, true);
            $img_array = json_decode($temp, true);
            if (!$img_array){
                return ['status'=>False,'msg'=>'没有找到图片，请联系管理员检查manifest_mobile.json下是否存在图片'];
            }
            $array_keys = array_keys($img_array);
            $img = array_rand($array_keys);
            // $img_domain = iro_opt('random_graphs_link_mobile') ?: get_template_directory_uri();
            if (iro_opt('random_graphs_link_mobile',NULL) && iro_opt('random_graphs_link_mobile') != home_url( '/')) {
                $img_domain = iro_opt('random_graphs_link_mobile');
            } else {
                $img_domain = get_template_directory_uri();
            }
            $format = strpos($_SERVER['HTTP_ACCEPT'], 'image/webp') ? 'webp' : 'jpeg';
            $imgurl = "{$img_domain}/manifest/{$format}/{$array_keys[$img]}.source.{$format}";
        }
        return ['status'=>True,'url'=>$imgurl];
    }

    public static function feature_gallery($size = 'source') {
        if (iro_opt('post_cover_options') == 'type_2') {
            return ['status'=>True,'url'=>iro_opt('post_cover')];
        } else {
            $imgurl = self::cover_gallery($size);
        }
        return $imgurl;
    }
    
}