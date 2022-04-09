<?php

namespace Sakura\API;

class MyAnimeList {
	private $username;

	public function __construct()
	{
		$this->username = iro_opt('my_anime_list_username');
	}

	/**
	 * Get anime list with username from https://myanimelist.net/
	 * @author siroi <mrgaopw@hotmail.com>
	 * @author KotoriK
	 * @author cocdeshijie <cocdeshijie@berkeley.edu>
	 */
	function get_data()
	{
		$username = $this->username;
		$url = "https://myanimelist.net/animelist/$username/load.json";
		$args = array(
			'headers' => array(
				'Host' => 'myanimelist.net',
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

	function get_all_items()
	{
		$resp = $this->get_data();
		if ($resp === false)
		{
			return '<div>Backend Error QwQ</div>';
		}
		else
		{
			$item_count = count($resp);
			$next = '<span>Following ' . $item_count . ' seasons of anime!</span>';
			$html = "";
			foreach ((array)$resp as $item)
			{
				$html .= MyAnimeList::get_item_details($item);
			}
			$html .= '</div><br><div id="bangumi-pagination">' . $next . '</div>';
			return $html;
		}
	}

	private static function get_item_details(array $item)
	{
		return '<div class="column">' .
		       '<a class="bangumi-item" href="https://myanimelist.net' . $item['anime_url'] . '/" target="_blank" rel="nofollow">'
		       .lazyload_img(str_replace('http://', 'https://', $item['anime_image_path']),'bangumi-image',array('alt'=>$item['anime_title'])).
		       '<div class="bangumi-info">' .
		       '<h3 class="bangumi-title" title="' . $item['anime_title'] . '">' . $item['anime_title'] . '</h2>'
		       . '<div class="bangumi-summary"> ' . $item['anime_title_eng'] . ' </div>' .
		       '<div class="bangumi-status">'
		       . '<div class="bangumi-status-bar" style="width: 100%"></div>'
		       . '<p>' . 'placeholder' .  '</p>'
		       . '</div>'
		       . '</div>'
		       . '</a>'
		       . '</div>';
	}



}