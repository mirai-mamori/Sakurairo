<?php

namespace Sakura\API;

class Images
{
    private $chevereto_api_key;
    private $imgur_client_id;
    private $smms_client_id;

    public function __construct() {
        $this->chevereto_api_key = akina_option('chevereto_api_key');
        $this->imgur_client_id = akina_option('imgur_client_id');
        $this->smms_client_id = akina_option('smms_client_id');
    }


    /**
     * Chevereto upload interface
     */
    public function Chevereto_API($image) {
        $upload_url = akina_option('cheverto_url') . '/api/1/upload';
        $args = array(
            'body' => array(
                'source' => base64_encode($image),
                'key' => $this->chevereto_api_key,
            ),
        );

        $response = wp_remote_post($upload_url, $args);
        $reply = json_decode($response["body"]);

        if ($reply->status_txt == 'OK' && $reply->status_code == 200) {
            $status = 200;
            $success = true;
            $message = "success";
            $link = $reply->image->image->url;
            $proxy = akina_option('cmt_image_proxy') . $link;
        } else {
            $status = $reply->status_code;
            $success = false;
            $message = $reply->error->message;
            $link = 'https://view.moezx.cc/images/2019/10/28/default_d_h_large.gif';
            $proxy = akina_option('cmt_image_proxy') . $link;
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
        $upload_url = akina_option('imgur_upload_image_proxy');
        $args = array(
            'headers' => array(
                'Authorization' => 'Client-ID ' . $this->imgur_client_id,
            ),
            'body' => array(
                'image' => base64_encode($image),
            ),
        );

        $response = wp_remote_post($upload_url, $args);
        $reply = json_decode($response["body"]);

        if ($reply->success && $reply->status == 200) {
            $status = 200;
            $success = true;
            $message = "success";
            $link = $reply->data->link;
            $proxy = akina_option('cmt_image_proxy') . $link;
        } else {
            $status = $reply->status;
            $success = false;
            $message = $reply->data->error;
            $link = 'https://view.moezx.cc/images/2019/10/28/default_d_h_large.gif';
            $proxy = akina_option('cmt_image_proxy') . $link;
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
        $upload_url = "https://sm.ms/api/v2/upload";
        $filename = $image['cmt_img_file']['name'];
        $filedata = $image['cmt_img_file']['tmp_name'];
        $Boundary = wp_generate_password();
        $bits = file_get_contents($filedata);

        $args = array(
            "headers" => "Content-Type: multipart/form-data; boundary=$Boundary\r\n\r\nAuthorization: Basic $client_id\r\n\r\nUser-Agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/78.0.3904.97",
            "body" => "--$Boundary\r\nContent-Disposition: form-data; name=\"smfile\"; filename=\"$filename\"\r\n\r\n$bits\r\n\r\n--$Boundary--"
        );

        $response = wp_remote_post($upload_url, $args);
        $reply = json_decode($response["body"]);

        if ($reply->success && $reply->code == 'success') {
            $status = 200;
            $success = true;
            $message = $reply->message;
            $link = $reply->data->url;
            $proxy = akina_option('cmt_image_proxy') . $link;
        } else if (preg_match("/Image upload repeated limit/i", $reply->message, $matches)) {
            $status = 200; // sm.ms 接口不规范，建议检测到重复的情况下返回标准化的 code，并单独把 url 放进一个字段
            $success = true;
            $message = $reply->message;
            $link = str_replace('Image upload repeated limit, this image exists at: ', '', $reply->message);
            $proxy = akina_option('cmt_image_proxy') . $link;
        } else {
            $status = 400;
            $success = false;
            $message = $reply->message;
            $link = 'https://view.moezx.cc/images/2019/10/28/default_d_h_large.gif';
            $proxy = akina_option('cmt_image_proxy') . $link;
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

    public static function cover_gallery() {
        if (akina_option('cover_cdn_options') == "type_2") {
            $img_array = glob(get_template_directory() . "/manifest/gallary/*.{gif,jpg,png}", GLOB_BRACE);
            $img = array_rand($img_array);
            $imgurl = trim($img_array[$img]);
            $imgurl = str_replace(get_template_directory(), get_template_directory_uri(), $imgurl);
        } elseif (akina_option('cover_cdn_options') == "type_3") {
            $imgurl = akina_option('cover_cdn');
        } else {
            global $sakura_image_array;
            $img_array = json_decode($sakura_image_array, true);
            $img = array_rand($img_array);
            $img_domain = akina_option('cover_cdn') ? akina_option('cover_cdn') : get_template_directory_uri();
            if (strpos($_SERVER['HTTP_ACCEPT'], 'image/webp') !== false) {
                $imgurl = $img_domain . "/manifest/" . $img_array[$img]["webp"][0];
            } else {
                $imgurl = $img_domain . "/manifest/" . $img_array[$img]["jpeg"][0];
            }
        }
        return $imgurl;
    }

    public static function mobile_cover_gallery() {
        if (akina_option('cover_cdn_options') == "type_2") {
            $img_array = glob(get_template_directory() . "/manifest/gallary/*.{gif,jpg,png}", GLOB_BRACE);
            $img = array_rand($img_array);
            $imgurl = trim($img_array[$img]);
            $imgurl = str_replace(get_template_directory(), get_template_directory_uri(), $imgurl);
        } elseif (akina_option('cover_cdn_options') == "type_3") {
          //$imgurl = akina_option('cover_cdn');
           $imgurl = akina_option('cover_cdn_mobile');
        } else {
            global $sakura_mobile_image_array;
            $img_array = json_decode($sakura_mobile_image_array, true);
            $img = array_rand($img_array);
            $img_domain = akina_option('cover_cdn') ? akina_option('cover_cdn') : get_template_directory_uri();
            if (strpos($_SERVER['HTTP_ACCEPT'], 'image/webp')) {
              $imgurl = $img_domain . "/manifest/" . $img_array[$img]["webp"][0];
            } else {
              $imgurl = $img_domain . "/manifest/" . $img_array[$img]["jpeg"][0];
            }
        }
        return $imgurl;
    }

    public static function feature_gallery() {
        if (akina_option('post_cover_options') == "type_2") {
            $imgurl = akina_option('post_cover');
        } else {
            $imgurl = self::cover_gallery();
        }
        return $imgurl;
    }
    
}