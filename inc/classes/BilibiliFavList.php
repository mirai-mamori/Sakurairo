<?php

namespace Sakura\API;

class BilibiliFavList
{
	private $uid;

	public function __construct()
	{
		$this->uid = iro_opt('bilibili_id');
	}

	function fetch_folder_api()
	{
		$uid = $this->uid;
		$url = "https://api.bilibili.com/x/v3/fav/folder/created/list-all?up_mid=$uid&jsonp=jsonp";
		$args = array(
			'headers' => array(
				'Host' => 'api.bilibili.com',
				'User-Agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/78.0.3904.97'
			)
		);
		$response = wp_remote_get($url, $args);
		if(is_array($response)){
			return json_decode($response["body"], true);
		}else{
			return false;
		}
	}

	function fetch_folder_item_api(int $folder_id, int $page)
	{
		$url = "https://api.bilibili.com/x/v3/fav/resource/list?media_id=$folder_id&pn=$page&ps=9&platform=web&jsonp=jsonp";
		$args = array(
			'headers' => array(
				'Host' => 'api.bilibili.com',
				'User-Agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/78.0.3904.97'
			)
		);
		$response = wp_remote_get($url, $args);
		if(is_array($response)){
			return json_decode($response["body"], true);
		}else{
			return false;
		}
	}

	public function get_folders()
	{
		$resp = $this->fetch_folder_api();
		if ($resp === false)
		{
			return "<div>" . __('Backend error', 'sakurairo') . "</div>";
		}
		$folders_data = $resp['data'];
		$folders = $folders_data['list'];
		$html = '<span>Folder count: ' . $folders_data['count'] . '</span></br>';
		foreach ((array)$folders as $folder){
			$html .= BilibiliFavList::folder_display($folder['id']);
		}
		return $html . "</br><span>end placeholder</span>";
	}

	public function folder_display(int $folder_id)
	{
		$folder_resp = $this->fetch_folder_item_api($folder_id, 1);
		$folder_content_info = $folder_resp['data']['info'];
		$html = '<div class="folder"><div class="folder-top">'.
		        lazyload_img(str_replace('http://', 'https://', $folder_content_info['cover']),'item-image',array('alt'=>$folder_content_info['title'])).
		        '<div class="folder-detail"><h3>' . $folder_content_info['title'] . '</h3>'.
		        '<p>' . __('Video count: ', 'sakurairo') . $folder_content_info['media_count'] . '</p>'.
		        '<button class="expand-button">Expand placeholder</button></div></div>'.
		        '<hr><div class="folder-content">';
		return $html . $this->load_folder_items($folder_id) . '</div></div></br>';
	}

	public function load_folder_items(int $folder_id, $page = 1)
	{
		$folder_resp = $this->fetch_folder_item_api($folder_id, $page);
		$folder_content = $folder_resp['data']['medias'];
		$html = '';
		foreach ((array)$folder_content as $item) {
			$html .= BilibiliFavList::folder_item_display($item);
		}
		if ($folder_resp['data']['has_more']){
			$load = BilibiliFavList::load_more(rest_url('sakura/v1/favlist/bilibili') . '?page=' . ++$page . '&folder_id=' . $folder_id);
		}else{
			$load = 'end';
		}
		return $html . $load;
	}

	private static function folder_item_display(array $item)
	{
		if ($item['type'] == 24){
			$link = $item['link'];
		}else{
			$link = "https://www.bilibili.com/video/" . $item['bvid'];
		}
		return '<div class="column"><a class="folder-item" href="' . $link . '" target="_blank" rel="nofollow">'.
		       lazyload_img(str_replace('http://', 'https://', $item['cover']),'item-image',array('alt'=>$item['title'])).
		       '<div class="item-info"><h3 class="item-title" title="' . $item['title'] . '">' . $item['title'] . '</h3>'.
		       '<div class="item-intro" title="' . $item['intro'] . '">' . $item['intro'] . '</div>'.
		       '</div></a></div>';
	}

	private static function load_more($href)
	{
		return '<a class="load-more" data-href="' . $href . '"><i class="fa fa-bolt" aria-hidden="true"></i> LOAD MORE </a>';
	}
}