<?php

namespace Sakura\API;

class BilibiliFavList
{
	private $uid;
	private $headers;
	public function __construct()
	{
		$this->uid = iro_opt('bilibili_id');
		$this->headers = array(
			'Host' => 'api.bilibili.com',
			'User-Agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/78.0.3904.97',
			"Cookie" => iro_opt('bilibili_cookie')
		);
	}
	function fetch_folder_api()
	{
		$uid = $this->uid;
		if (empty($uid)) {
			error_log("BilibiliFavList: UID is empty");
			return false;
		}
		
		$url = "https://api.bilibili.com/x/v3/fav/folder/created/list-all?up_mid=$uid&jsonp=jsonp";
		$args = array(
			'headers' => $this->headers,
			'timeout' => 15 // 增加超时时间
		);
		
		$response = wp_remote_get($url, $args);
		
		// 检查请求是否成功
		if (is_wp_error($response)) {
			error_log("BilibiliFavList: fetch_folder_api WP_Error: " . $response->get_error_message());
			return false;
		}
		
		// 检查响应状态码
		$status_code = wp_remote_retrieve_response_code($response);
		if ($status_code !== 200) {
			error_log("BilibiliFavList: fetch_folder_api HTTP error: $status_code");
			return false;
		}
		
		if (is_array($response)) {
			$body = wp_remote_retrieve_body($response);
			$result = json_decode($body, true);
			
			if (json_last_error() !== JSON_ERROR_NONE) {
				error_log("BilibiliFavList: fetch_folder_api JSON decode error: " . json_last_error_msg());
				return false;
			}
			
			if ($result && isset($result['code']) && $result['code'] === 0) {
				return $result;
			} else {
				$error_message = isset($result['message']) ? $result['message'] : 'Unknown error';
				$error_code = isset($result['code']) ? $result['code'] : 'Unknown code';
				error_log("BilibiliFavList: fetch_folder_api Bilibili API error: Code $error_code, Message: $error_message");
				return false;
			}
		}
		
		error_log("BilibiliFavList: fetch_folder_api failed, response dump:" . var_export($response, true));
		return false;
	}	function fetch_folder_item_api(int $folder_id, int $page)
	{
		if (empty($folder_id)) {
			error_log("BilibiliFavList: folder_id is empty");
			return false;
		}
		
		// 修改页面大小为12，以便更好的网格布局
		$url = "https://api.bilibili.com/x/v3/fav/resource/list?media_id=$folder_id&pn=$page&ps=12&platform=web&jsonp=jsonp";
		$args = array(
			'headers' => $this->headers,
			'timeout' => 15 // 增加超时时间
		);
		
		$response = wp_remote_get($url, $args);
		
		// 检查请求是否成功
		if (is_wp_error($response)) {
			error_log("BilibiliFavList: fetch_folder_item_api WP_Error: " . $response->get_error_message());
			return false;
		}
		
		// 检查响应状态码
		$status_code = wp_remote_retrieve_response_code($response);
		if ($status_code !== 200) {
			error_log("BilibiliFavList: fetch_folder_item_api HTTP error: $status_code");
			return false;
		}
		
		if (is_array($response)) {
			$body = wp_remote_retrieve_body($response);
			$result = json_decode($body, true);
			
			if (json_last_error() !== JSON_ERROR_NONE) {
				error_log("BilibiliFavList: fetch_folder_item_api JSON decode error: " . json_last_error_msg());
				return false;
			}
			
			if ($result && isset($result['code']) && $result['code'] === 0) {
				return $result;
			} else {
				$error_message = isset($result['message']) ? $result['message'] : 'Unknown error';
				$error_code = isset($result['code']) ? $result['code'] : 'Unknown code';
				error_log("BilibiliFavList: fetch_folder_item_api Bilibili API error: Code $error_code, Message: $error_message");
				return false;
			}
		}
		
		error_log("BilibiliFavList: fetch_folder_item_api failed, response dump:" . var_export($response, true));
		return false;
	}

	public function get_folders()
	{
		$resp = $this->fetch_folder_api();
		if ($resp === false) {
			return "<div>" . __('Backend error', 'sakurairo') . "</div>";
		}
		$folders_data = $resp['data'];
		if ($folders_data === null) {
			error_log("BilibiliFavList: no data fetched. Try apply cookie.");
			return "<div>" . __('Backend error', 'sakurairo') . "</div>";
		}
		$folders = $folders_data['list'];
		$html = '';
		foreach ((array)$folders as $folder) {
			$render_result = $this->folder_display($folder['id']);
			if ($render_result) {
				$html .= $render_result;
			} else {
				error_log("BilibiliFavList: folder_display failed, folder_id: " . $folder['id']);
				$html .= '<div class="folder"><div class="folder-top">'
					. '<div class="folder-detail"><h3>' . $folder['title'] . '</h3>'
					. __('Backend error', 'sakurairo') . "</div></div></div>";
			}
		}
		return $html;
	}
	public function folder_display(int $folder_id)
	{
		$folder_resp = $this->fetch_folder_item_api($folder_id, 1);
		if (!$folder_resp) {
			return false;
		}
		
		// 我们不再生成HTML，而是返回原始数据供前端JS处理
		// 此处保留函数以避免兼容性问题，但我们的新UI不再使用此函数的输出
		$folder_content_info = $folder_resp['data']['info'];
		$html = '<div class="folder"><div class="folder-top">' .
			lazyload_img(str_replace('http://', 'https://', $folder_content_info['cover']), 'folder-img', array('alt' => $folder_content_info['title'],'referrerpolicy'=>"no-referrer")) .
			'<div class="folder-detail"><h3>' . $folder_content_info['title'] . '</h3>' .
			'<p>' . __('Item count: ', 'sakurairo') . $folder_content_info['media_count'] . '</p>' .
			'<button class="expand-button">' . __('Expand', 'sakurairo') . '</button></div></div>' .
			'<hr><div class="folder-content">';
		$load = BilibiliFavList::load_more(rest_url('sakura/v1/favlist/bilibili') . '?page=1' . '&folder_id=' . $folder_id);
		return $html . $load . '</div></div></br>';
	}

	public function load_folder_items(int $folder_id, $page = 1)
	{
		$folder_resp = $this->fetch_folder_item_api($folder_id, $page);
		$folder_content = $folder_resp['data']['medias'];
		$html = '';
		foreach ((array)$folder_content as $item) {
			$html .= BilibiliFavList::folder_item_display($item);
		}
		if ($folder_resp['data']['has_more']) {
			$load = BilibiliFavList::load_more(rest_url('sakura/v1/favlist/bilibili') . '?page=' . ++$page . '&folder_id=' . $folder_id);
		} else {
			$load = '<a class="load-more"><i class="fa-solid fa-ban"></i>' . __('All item has been loaded.', 'sakurairo') . '</a>';
		}
		return $html . $load;
	}

	private static function folder_item_display(array $item)
	{
		if ($item['type'] == 24) {
			$link = $item['link'];
		} else {
			$link = "https://www.bilibili.com/video/" . $item['bvid'];
		}

		$item['cover'] = str_replace("http://","https://",$item['cover']);
		
		// TODO: add lazyload to item-image with typescript
		return '<div class="column"><a class="folder-item" href="' . $link . '" target="_blank" rel="nofollow">' .
			'<img class="item-image" referrerpolicy="no-referrer" src="' . $item['cover'] . '">' .
			'<div class="item-info"><h3 class="item-title" title="' . $item['title'] . '">' . $item['title'] . '</h3>' .
			'<div class="item-intro" title="' . $item['intro'] . '">' . $item['intro'] . '</div>' .
			'</div></a></div>';
	}

	private static function load_more($href)
	{
		return '<a class="load-more" data-href="' . $href . '"><i class="fa-solid fa-guitar"></i>' . __('Load More', 'sakurairo') . '</a>';
	}
}
