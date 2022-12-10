<?php
/**
 * @Author: fuukei
 * @Date:   2022-03-13 18:16:15
 * @Last Modified by: cocdeshijie
 * @Last Modified time: 2022-04-16 13:27:30
 */


/**
 * Classes
 */
include_once('classes/Aplayer.php');
include_once('classes/Bilibili.php');
include_once('classes/Cache.php');
include_once('classes/Images.php');
include_once('classes/QQ.php');
include_once('classes/Captcha.php');
include_once('classes/MyAnimeList.php');
include_once('classes/BilibiliFavList.php');
use Sakura\API\Images;
use Sakura\API\QQ;
use Sakura\API\Cache;
use Sakura\API\Captcha;
/**
 * Router
 */
add_action('rest_api_init', function () {
    register_rest_route('sakura/v1', '/image/upload', array(
        'methods' => 'POST',
        'callback' => 'upload_image',
        'permission_callback'=>'__return_true'
    ));
    register_rest_route('sakura/v1', '/cache_search/json', array(
        'methods' => 'GET',
        'callback' => 'cache_search_json',
        'permission_callback'=>'__return_true'
    ));
    register_rest_route('sakura/v1', '/image/cover', array(
        'methods' => 'GET',
        'callback' => 'cover_gallery',
        'permission_callback'=>'__return_true'
    ));
    register_rest_route('sakura/v1', '/image/feature', array(
        'methods' => 'GET',
        'callback' => 'feature_gallery',
        'permission_callback'=>'__return_true'
    ));
    // register_rest_route('sakura/v1', '/database/update', array(
    //     'methods' => 'GET',
    //     'callback' => 'update_database',
    //     'permission_callback'=>'__return_true'
    // ));
    register_rest_route('sakura/v1', '/qqinfo/json', array(
        'methods' => 'GET',
        'callback' => 'get_qq_info',
        'permission_callback'=>'__return_true'
    ));
    register_rest_route('sakura/v1', '/qqinfo/avatar', array(
        'methods' => 'GET',
        'callback' => 'get_qq_avatar',
        'permission_callback'=>'__return_true'
    ));
    register_rest_route('sakura/v1', '/bangumi/bilibili', array(
        'methods' => 'POST',
        'callback' => 'bgm_bilibili',
        'permission_callback'=>'__return_true'
    ));
	register_rest_route('sakura/v1', '/favlist/bilibili', array(
		'methods' => 'POST',
		'callback' => 'favlist_bilibili',
        'permission_callback'=>'__return_true'
	));
    register_rest_route('sakura/v1', '/meting/aplayer', array(
        'methods' => 'GET',
        'callback' => 'meting_aplayer',
        'permission_callback'=>'__return_true'
    ));
    register_rest_route('sakura/v1', '/captcha/create', array(
        'methods' => 'GET',
        'callback' => 'create_CAPTCHA',
        'permission_callback'=>'__return_true'
    ));
});

/**
 * Image uploader response
 */
function upload_image(WP_REST_Request $request) {
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
            'link' => "https://s.nmxc.ltd/sakurairo_vision/@2.5/basic/step04.md.png",
            'proxy' => iro_opt('comment_image_proxy') . "https://s.nmxc.ltd/sakurairo_vision/@2.5/basic/step04.md.png",
        );
        $result = new WP_REST_Response($output, 403);
        $result->set_headers(array('Content-Type' => 'application/json'));
        return $result;
    }
    $images = new \Sakura\API\Images();
    switch (iro_opt("img_upload_api")) {
        case 'imgur':
            $image = file_get_contents($_FILES["cmt_img_file"]["tmp_name"]);
            $API_Request = $images->Imgur_API($image);
            break;
        case 'smms':
            $image = $_FILES;
            $API_Request = $images->SMMS_API($image);
            break;
        case 'chevereto':
            $image = file_get_contents($_FILES["cmt_img_file"]["tmp_name"]);
            $API_Request = $images->Chevereto_API($image);
            break;
        case 'lsky':
            $image = $_FILES;
            $API_Request = $images->LSKY_API($image);
            break;
    }

    $result = new WP_REST_Response($API_Request, $API_Request['status']);
    $result->set_headers(array('Content-Type' => 'application/json'));
    return $result;
}


/*
 * 随机封面图 rest api
 * @rest api接口路径：https://sakura.2heng.xin/wp-json/sakura/v1/image/cover
 */
function cover_gallery() {
    $type = $_GET['type'] ?? '';
    // $type = in_array('type',$_GET) ? $_GET['type']:'';
    if ($type === 'mobile' && iro_opt('random_graphs_mts')){
        $imgurl = Images::mobile_cover_gallery();
    }else{
        $imgurl = Images::cover_gallery();
    }
    if (!$imgurl['status']){
        return new WP_REST_Response(
            array(
                'status' => 500,
                'success' => false,
                'message' => $imgurl['msg']
            ),
            500
        );
    }
    $data = array('cover image');
    $response = new WP_REST_Response($data);
    $response->set_status(302);
    $response->header('Location', $imgurl['url']);
    return $response;
}

/*
 * 随机文章特色图 rest api
 * @rest api接口路径：https://sakura.2heng.xin/wp-json/sakura/v1/image/feature
 */
function feature_gallery() {
    $size = isset($_GET['size']) ? (in_array($_GET['size'], ['source','th']) ? $_GET['size'] : 'source') : 'source';
    $imgurl = Images::feature_gallery($size);
    if (!$imgurl['status']){
        return new WP_REST_Response(
            array(
                'status' => 500,
                'success' => false,
                'message' => $imgurl['msg']
            ),
            500
        );
    }
    $data = array('feature image');
    $response = new WP_REST_Response($data);
    $response->set_status(302);
    $response->header('Location', $imgurl['url']);
    return $response;
}

/*
 * update database rest api
 * @rest api接口路径：https://sakura.2heng.xin/wp-json/sakura/v1/database/update
 */
// function update_database() {
//     if (iro_opt('random_graphs_options') == "webp_optimization") {
//         $output = Cache::update_database();
//         $result = new WP_REST_Response($output, 200);
//         return $result;
//     } else {
//         return new WP_REST_Response("Invalid access", 200);
//     }
// }

/*
 * 定制实时搜索 rest api
 * @rest api接口路径：https://sakura.2heng.xin/wp-json/sakura/v1/cache_search/json
 * @可在cache_search_json()函数末尾通过设置 HTTP header 控制 json 缓存时间
 */
function cache_search_json() {
    if (!check_ajax_referer('wp_rest', '_wpnonce', false)) {
        $output = array(
            'status' => 403,
            'success' => false,
            'message' => 'Unauthorized client.'
        );
        $result = new WP_REST_Response($output, 403);
    } else {
        $output = Cache::search_json();
        $result = new WP_REST_Response($output, 200);
    }
    $result->set_headers(
        array(
            'Content-Type' => 'application/json',
            'Cache-Control' => 'max-age=3600', // json 缓存控制
        )
    );
    return $result;
}

/**
 * QQ info
 * https://sakura.2heng.xin/wp-json/sakura/v1/qqinfo/json
 */
function get_qq_info(WP_REST_Request $request) {
    if (!check_ajax_referer('wp_rest', '_wpnonce', false)) {
        $output = array(
            'status' => 403,
            'success' => false,
            'message' => 'Unauthorized client.'
        );
    } elseif ($_GET['qq']) {
        $qq = $_GET['qq'];
        $output = QQ::get_qq_info($qq);
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
 * QQ头像链接解密
 * https://sakura.2heng.xin/wp-json/sakura/v1/qqinfo/avatar
 */
function get_qq_avatar() {
    $encrypted = $_GET["qq"];
    $imgurl = QQ::get_qq_avatar($encrypted);
    if (iro_opt('qq_avatar_link') == 'type_2') {
        $imgdata = file_get_contents($imgurl);
        $response = new WP_REST_Response();
        $response->set_headers(array(
            'Content-Type' => 'image/jpeg',
            'Cache-Control' => 'max-age=86400'
        ));
        echo $imgdata;
    } else {
        $response = new WP_REST_Response();
        $response->set_status(301);
        $response->header('Location', $imgurl);
    }
    return $response;
}

function bgm_bilibili() {
    if (!check_ajax_referer('wp_rest', '_wpnonce', false)) {
        $output = array(
            'status' => 403,
            'success' => false,
            'message' => 'Unauthorized client.'
        );
        $response = new WP_REST_Response($output, 403);
    } else {
        $page = $_GET["page"] ?: 2;
        $bgm = new \Sakura\API\Bilibili();
        $html = preg_replace("/\s+|\n+|\r/", ' ', $bgm->get_bgm_items($page));
        $response = new WP_REST_Response($html, 200);
    }
	$page = $_GET["page"] ?: 2;
	$bgm = new \Sakura\API\Bilibili();
	$html = preg_replace("/\s+|\n+|\r/", ' ', $bgm->get_bgm_items($page));
	$response = new WP_REST_Response($html, 200);
    return $response;
}

function favlist_bilibili() {
	if (!check_ajax_referer('wp_rest', '_wpnonce', false)) {
		$output = array(
			'status' => 403,
			'success' => false,
			'message' => 'Unauthorized client.'
		);
		$response = new WP_REST_Response($output, 403);
	} else {
		$page = $_GET["page"] ?: 2;
		$folder_id = $_GET["folder_id"];
		$bgm = new \Sakura\API\BilibiliFavList();
		$html = preg_replace("/\s+|\n+|\r/", ' ', $bgm->load_folder_items($folder_id, $page));
		$response = new WP_REST_Response($html, 200);
	}
	return $response;
}

function meting_aplayer() {
    $type = $_GET['type'];
    $id = $_GET['id'];
    if(in_array('_wpnonce',$_GET))    $wpnonce = $_GET['_wpnonce'];
    if(in_array('meting_nonce',$_GET)) $meting_nonce = $_GET['meting_nonce'];
    if ((isset($wpnonce) && !check_ajax_referer('wp_rest', $wpnonce, false)) || (isset($meting_nonce) && !wp_verify_nonce($meting_nonce, $type . '#:' . $id))) {
        $output = array(
            'status' => 403,
            'success' => false,
            'message' => 'Unauthorized client.'
        );
        $response = new WP_REST_Response($output, 403);
    } else {
        $Meting_API = new \Sakura\API\Aplayer();
        $data = $Meting_API->get_data($type, $id);
        if ($type === 'playlist') {
            $response = new WP_REST_Response($data, 200);
            $response->set_headers(array('cache-control' => 'max-age=3600'));
        } elseif ($type === 'lyric') {
            $response = new WP_REST_Response();
            $response->set_headers(array('cache-control' => 'max-age=3600'));
            $response->set_headers(array('Content-Type' => 'text/plain; charset=utf-8'));
            $response->set_data($data);
        } else {
            $response = new WP_REST_Response();
            $response->set_status(301);
            $response->header('Location', $data);
        }
    }
    return $response;
}

function create_CAPTCHA(){
    $CAPTCHA = new Captcha();
    $response = new WP_REST_Response($CAPTCHA->create_captcha_img());
    $response->set_status(200);
    $response->set_headers(array('Content-Type' => 'application/json'));
    return $response;
}