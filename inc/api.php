<?php

/**
 * Router
 */
add_action('rest_api_init', function () {
    register_rest_route('sakura/v1', '/image/upload', array(
        'methods' => 'POST',
        'callback' => 'upload_image',
    ));
    register_rest_route('sakura/v1', '/cache_search/json', array(
        'methods' => 'GET',
        'callback' => 'cache_search_json',
    ));
    register_rest_route('sakura/v1', '/image/cover', array(
        'methods' => 'GET',
        'callback' => 'cover_gallery',
    ));
    register_rest_route('sakura/v1', '/image/feature', array(
        'methods' => 'GET',
        'callback' => 'feature_gallery',
    ));
    register_rest_route('sakura/v1', '/database/update', array(
        'methods' => 'GET',
        'callback' => 'update_database',
    ));
    register_rest_route('sakura/v1', '/qqinfo/json', array(
        'methods' => 'GET',
        'callback' => 'get_qq_info',
    ));
    register_rest_route('sakura/v1', '/qqinfo/avatar', array(
        'methods' => 'GET',
        'callback' => 'get_qq_avatar',
    ));
});

/**
 * QQ info
 * https://sakura.2heng.xin/wp-json/sakura/v1/qqinfo/json
 */
function get_qq_info(WP_REST_Request $request)
{
    if (!check_ajax_referer('wp_rest', '_wpnonce', false)) {
        $output = array(
            'status' => 403,
            'success' => false,
            'message' => 'Unauthorized client.'
        );
    } elseif ($_GET['qq']) {
        $qq = $_GET['qq'];
        /** 
         * TODO: 设置host，国外服务器默认解析的不是国内IP，可能无法获取数据
         * 182.254.92.32 r.qzone.qq.com
         * 参考：https://www.php.net/manual/zh/function.file-get-contents.php#108309
         */
        $get_info = file_get_contents('http://r.qzone.qq.com/fcg-bin/cgi_get_portrait.fcg?get_nick=1&uins=' . $qq);
        $get_info = mb_convert_encoding($get_info, "UTF-8", "GBK");
        $name = json_decode(substr($get_info, 17, -1), true);
        if ($name) {
            $output = array(
                'status' => 200,
                'success' => true,
                'message' => 'success',
                'avatar' => 'https://q.qlogo.cn/headimg_dl?dst_uin=' . $qq . '&spec=100',
                'name' => $name[$qq][6],
            );
        } else {
            $output = array(
                'status' => 404,
                'success' => false,
                'message' => 'QQ number not exist.'
            );
        }
    } else {
        $output = array(
            'status' => 400,
            'success' => false,
            'message' => 'Bad Request'
        );
    }

    $result = new WP_REST_Response($output, $output['status']);
    $result->set_headers(array('Content-Type' => 'application/json'));
    return $result;
}

/**
 * Image uploader response
 */
function upload_image(WP_REST_Request $request)
{
    // see: https://developer.wordpress.org/rest-api/requests/

    // handle file params $file === $_FILES
    /**
     * curl \
     *   -F "filecomment=This is an img file" \
     *   -F "cmt_img_file=@screenshot.jpg" \
     *   https://dev.2heng.xin/wp-json/sakura/v1/image/upload
     */
    // $file = $request->get_file_params();
    if (!check_ajax_referer('wp_rest', '_wpnonce', false)) {
        $output = array('status' => 403,
            'success' => false,
            'message' => 'Unauthorized client.',
            'link' => "https://view.moezx.cc/images/2019/11/14/step04.md.png",
            'proxy' => akina_option('cmt_image_proxy') . "https://view.moezx.cc/images/2019/11/14/step04.md.png",
        );
        $result = new WP_REST_Response($output, 403);
        $result->set_headers(array('Content-Type' => 'application/json'));
        return $result;
    }

    switch (akina_option("img_upload_api")) {
        case 'imgur':
            $image = file_get_contents($_FILES["cmt_img_file"]["tmp_name"]);
            $API_Request = Imgur_API($image);
            break;
        case 'smms':
            $image = $_FILES;
            $API_Request = SMMS_API($image);
            break;
        case 'chevereto':
            $image = file_get_contents($_FILES["cmt_img_file"]["tmp_name"]);
            $API_Request = Chevereto_API($image);
            break;
    }

    $result = new WP_REST_Response($API_Request, $API_Request['status']);
    $result->set_headers(array('Content-Type' => 'application/json'));
    return $result;
}

/**
 * Chevereto upload interface
 */
function Chevereto_API($image)
{
    $upload_url = akina_option('cheverto_url') . '/api/1/upload';
    $args = array(
        'body' => array(
            'source' => base64_encode($image),
            'key' => akina_option('chevereto_api_key'),
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
function Imgur_API($image)
{
    $client_id = akina_option('imgur_client_id');
    $upload_url = akina_option('imgur_upload_image_proxy');
    $args = array(
        'headers' => array(
            'Authorization' => 'Client-ID ' . $client_id,
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
function SMMS_API($image)
{
    $client_id = akina_option('smms_client_id');
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

/*
 * 定制实时搜索 rest api
 * @rest api接口路径：https://sakura.2heng.xin/wp-json/sakura/v1/cache_search/json
 * @可在cache_search_json()函数末尾通过设置 HTTP header 控制 json 缓存时间
 */
function cache_search_json()
{
    global $more;
    $vowels = array("[", "{", "]", "}", "<", ">", "\r\n", "\r", "\n", "-", "'", '"', '`', " ", ":", ";", '\\', "  ", "toc");
    $regex = <<<EOS
/<\/?[a-zA-Z]+("[^"]*"|'[^']*'|[^'">])*>|begin[\S\s]*\/begin|hermit[\S\s]*\/hermit|img[\S\s]*\/img|{{.*?}}|:.*?:/m
EOS;
    $more = 1;

    $posts = new WP_Query('posts_per_page=-1&post_status=publish&post_type=post');
    while ($posts->have_posts()): $posts->the_post();
        $output .= '{"type":"post","link":"' . get_permalink() . '","title":' . json_encode(get_the_title()) . ',"comments":"' . get_comments_number('0', '1', '%') . '","text":' . json_encode(str_replace($vowels, " ", preg_replace($regex, ' ', apply_filters( 'the_content', get_the_content())))) . '},';
    endwhile;
    wp_reset_postdata();

    $pages = new WP_Query('posts_per_page=-1&post_status=publish&post_type=page');
    while ($pages->have_posts()): $pages->the_post();
        $output .= '{"type":"page","link":"' . get_permalink() . '","title":' . json_encode(get_the_title()) . ',"comments":"' . get_comments_number('0', '1', '%') . '","text":' . json_encode(str_replace($vowels, " ", preg_replace($regex, ' ', apply_filters( 'the_content', get_the_content())))) . '},';
    endwhile;
    wp_reset_postdata();

    $tags = get_tags();
    foreach ($tags as $tag) {
        $output .= '{"type":"tag","link":"' . get_term_link($tag) . '","title":' . json_encode($tag->name) . ',"comments":"","text":""},';
    }

    $categories = get_categories();
    foreach ($categories as $category) {
        $output .= '{"type":"category","link":"' . get_term_link($category) . '","title":' . json_encode($category->name) . ',"comments":"","text":""},';
    }
    if (akina_option('live_search_comment')) {
        $comments = get_comments();
        foreach ($comments as $comment) {
            $is_private = get_comment_meta($comment->comment_ID, '_private', true);
            if ($is_private) {
                $output .= '{"type":"comment","link":"' . get_comment_link($comment) . '","title":' . json_encode(get_the_title($comment->comment_post_ID)) . ',"comments":"","text":' . json_encode($comment->comment_author . "：" . __("The comment is private", "sakura") /*该评论为私密评论*/) . '},';
                continue;
            } else {
                $output .= '{"type":"comment","link":"' . get_comment_link($comment) . '","title":' . json_encode(get_the_title($comment->comment_post_ID)) . ',"comments":"","text":' . json_encode(str_replace($vowels, " ", preg_replace($regex, " ", $comment->comment_author . "：" . $comment->comment_content))) . '},';
            }
        }
    }

    $output = substr($output, 0, strlen($output) - 1);

    $data = '[' . $output . ']';
    $result = new WP_REST_Response(json_decode($data), 200);
    $result->set_headers(
        array(
            'Content-Type' => 'application/json',
            'Cache-Control' => 'max-age=3600', // json 缓存控制
        )
    );

    return $result;
}

/*
 * 随机封面图 rest api
 * @rest api接口路径：https://sakura.2heng.xin/wp-json/sakura/v1/image/cover
 */
function cover_gallery() {
    if(akina_option('cover_cdn_options')=="type_2"){
        $img_array = glob(get_template_directory() . "/manifest/gallary/*.{gif,jpg,png}",GLOB_BRACE);
        $img = array_rand($img_array);
        $imgurl = trim($img_array[$img]);
        $imgurl = str_replace(get_template_directory(), get_template_directory_uri(), $imgurl);
    }elseif(akina_option('cover_cdn_options')=="type_3"){
        $imgurl = akina_option('cover_cdn');
    }else{
        global $sakura_image_array;
        $img_array = json_decode($sakura_image_array, true);
        $img = array_rand($img_array);
        $img_domain = akina_option('cover_cdn') ? akina_option('cover_cdn') : get_template_directory_uri();
        if(strpos($_SERVER['HTTP_ACCEPT'], 'image/webp')) {
            $imgurl = $img_domain . "/manifest/" . $img_array[$img]["webp"][0];
        } else {
            $imgurl = $img_domain . "/manifest/" . $img_array[$img]["jpeg"][0];
        }
    }
    $data = array('cover image');
    $response = new WP_REST_Response($data);
    $response->set_status(302);
    $response->header('Location', $imgurl);
    return $response;
}

/*
 * 随机文章特色图 rest api
 * @rest api接口路径：https://sakura.2heng.xin/wp-json/sakura/v1/image/feature
 */
function feature_gallery() {
    return cover_gallery();
}

/*
 * update database rest api
 * @rest api接口路径：https://sakura.2heng.xin/wp-json/sakura/v1/database/update
 */
function update_database() {
    if(akina_option('cover_cdn_options')=="type_1"){
        global $wpdb;
        $sakura_table_name = $wpdb->base_prefix.'sakura';
        $img_domain = akina_option('cover_cdn') ? akina_option('cover_cdn') : get_template_directory();
        $manifest = file_get_contents($img_domain . "/manifest/manifest.json");
        if($manifest) {
            $manifest = array(
                "mate_key" => "manifest_json",
                "mate_value" => $manifest
            );
            $time = array(
                "mate_key" => "json_time",
                "mate_value" => date("Y-m-d H:i:s",time())
            );
    
            $wpdb->query("DELETE FROM  $sakura_table_name WHERE `mate_key` ='manifest_json'");
            $wpdb->query("DELETE FROM  $sakura_table_name WHERE `mate_key` ='json_time'");
            $wpdb->insert($sakura_table_name,$manifest);
            $wpdb->insert($sakura_table_name,$time);
            $output = "manifest.json has been stored into database.";
        }else{
            $output = "manifest.json not found, please ensure your url ($img_domain) is corrent.";
        }
        $result = new WP_REST_Response($output, 200);
        return $result;
    }else{
        return new WP_REST_Response("Invalid access", 200);
    }
}

/**
 * QQ头像链接解密
 * https://sakura.2heng.xin/wp-json/sakura/v1/qqinfo/avatar
 */
function get_qq_avatar(){
    global $sakura_privkey;
    $encrypted=$_GET["qq"];
    if(isset($encrypted)){
        $iv = str_repeat($sakura_privkey, 2);
        $encrypted = base64_decode(urldecode($encrypted));
        $qq_number = openssl_decrypt($encrypted, 'aes-128-cbc', $sakura_privkey, 0, $iv);
        preg_match('/^\d{3,}$/', $qq_number, $matches);
        $imgurl='https://q2.qlogo.cn/headimg_dl?dst_uin='.$matches[0].'&spec=100';
        if(akina_option('qq_avatar_link')=='type_2'){
            $imgdata = file_get_contents($imgurl);
            header("Content-type: image/jpeg");
            header("Cache-Control: max-age=86400");
            echo $imgdata;
        }else{
            $response = new WP_REST_Response();
            $response->set_status(301);
            $response->header('Location', $imgurl);
            return $response;
        }
    } 
}
