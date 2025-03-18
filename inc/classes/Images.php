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
     * 返回默认的错误图片SVG（支持国际化）
     */
    private function getDefaultErrorImage() {
        // 获取国际化文本
        $error_text = __('Image Failed to Load', 'sakurairo');
        return 'data:image/svg+xml;utf8,' . urlencode('<svg xmlns="http://www.w3.org/2000/svg" width="300" height="200" viewBox="0 0 300 200"><rect x="5" y="5" width="290" height="190" rx="10" fill="#f8f9fa" stroke="#ddd"/><circle cx="150" cy="70" r="30" fill="#ff6b6b"/><text x="150" y="80" font-family="Arial" font-size="30" text-anchor="middle" fill="#fff">!</text><text x="150" y="130" font-family="Arial" font-size="16" text-anchor="middle" fill="#555">' . $error_text . '</text></svg>');
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
            $link = $this->getDefaultErrorImage();
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
            $link = $this->getDefaultErrorImage();
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
            $link = $this->getDefaultErrorImage();
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
            $link = $this->getDefaultErrorImage();
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
}
