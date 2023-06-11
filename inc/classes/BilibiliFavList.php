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
		$url = "https://api.bilibili.com/x/v3/fav/folder/created/list-all?up_mid=$uid&jsonp=jsonp";
		$args = array(
			'headers' => $this->headers
		);
		$response = wp_remote_get($url, $args);
		if (is_array($response)) {
			$result = json_decode($response["body"], true);
			if ($result) {
				return $result;
			}
		}
		error_log("BilibiliFavList: fetch_folder_api failed, response dump:" . var_export($response, true));
		return false;
	}

	function fetch_folder_item_api(int $folder_id, int $page)
	{
		$url = "https://api.bilibili.com/x/v3/fav/resource/list?media_id=$folder_id&pn=$page&ps=9&platform=web&jsonp=jsonp";
		$args = array(
			'headers' => $this->headers
		);
		$response = wp_remote_get($url, $args);
		if (is_array($response)) {
			$result = json_decode($response["body"], true);
			if ($result) {
				return $result;
			}
		}
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
		$folder_content_info = $folder_resp['data']['info'];
		$html = '<div class="folder"><div class="folder-top">' .
			lazyload_img(str_replace('http://', 'https://', $folder_content_info['cover']), 'folder-img', array('alt' => $folder_content_info['title'])) .
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
		// TODO: add lazyload to item-image with typescript
		return '<div class="column"><a class="folder-item" href="' . $link . '" target="_blank" rel="nofollow">' .
			'<img class="item-image" src="' . $item['cover'] . '">' .
			'<div class="item-info"><h3 class="item-title" title="' . $item['title'] . '">' . $item['title'] . '</h3>' .
			'<div class="item-intro" title="' . $item['intro'] . '">' . $item['intro'] . '</div>' .
			'</div></a></div>';
	}

	private static function load_more($href)
	{
		return '<a class="load-more" data-href="' . $href . '"><i class="fa-solid fa-bolt-lightning"></i>' . __('Load More', 'sakurairo') . '</a>';
	}
}
