<?php

namespace Sakura\API;

class MyAnimeList
{
	private $username;
	private $sort;

	public function __construct()
	{
		$this->username = iro_opt('my_anime_list_username');
		$this->sort = iro_opt('my_anime_list_sort');
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
		$sort = $this->sort;
		switch ($sort) {
			case 1: // Status and Last Updated
				$sort = 'order=16&order2=5&status=7';
				break;
			case 2: // Last Updated
				$sort = 'order=5&status=7';
				break;
			case 3: // Status
				$sort = 'order=16&status=7';
				break;
		}
		$url = "https://myanimelist.net/animelist/$username/load.json?$sort";
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

	public function get_all_items()
	{
		$resp = $this->get_data();
		if ($resp === false)
		{
			return "<div>" . __('Backend error', 'sakurairo') . "</div>";
		}
		else
		{
			$item_count = count($resp);
			$total_episodes = 0;
			foreach ((array)$resp as $item)
			{
				$html .= MyAnimeList::get_item_details($item);
				$total_episodes += $item['num_watched_episodes'];
			}
			$top_info = '<br><div id="bangumi-pagination"><span>' .
			            __('Following ', 'sakurairo') . $item_count . __(' anime.', 'sakurairo') .
			            __(' Watched ', 'sakurairo') . $total_episodes . __(' episodes.', 'sakurairo') .
			            '</span></div>';
			$html = $top_info . $html . '</div>';
			return $html;
		}
	}

	private static function get_item_details(array $item)
	{

		return '<div class="column">' .
		       '<a class="bangumi-item" href="https://myanimelist.net' . $item['anime_url'] . '/" target="_blank" rel="nofollow">'
		       .lazyload_img(MyAnimeList::get_image($item['anime_image_path']),'bangumi-image',array('alt'=>$item['anime_title'])).
		       '<div class="bangumi-info">' .
		       '<h3 class="bangumi-title" title="' . $item['anime_title'] . '">' . $item['anime_title'] . '</h2>' .
		       '<div class="bangumi-summary"> ' . $item['anime_title_eng'] . ' </div>' .
		       '<div class="bangumi-status">' .
		       MyAnimeList::bangumi_status($item) .
		       '</div>' .
		       '</div>' .
		       '</a>' .
		       '</div>';
	}

	private static function get_image(string $image_path)
	{
		preg_match('/\/anime(.*?)\./', $image_path, $output);
		return "https://cdn.myanimelist.net/images/anime/$output[1].jpg";
	}

	private static function bangumi_status(array $item)
	{
		switch($item['status'])
		{
			case 2: // Completed
			{
				return '<div class="bangumi-status-bar" style="width: 100%; background: #5cb85c;"></div>' .
				       '<p>' .
				       __('Finished ', 'sakurairo') . $item['num_watched_episodes'] . '/' . $item['anime_num_episodes'] .
				       '</p>';
			}
			case 1: // Watching
			{
				return '<div class="bangumi-status-bar" style="width: '.
				       MyAnimeList::status_percent($item) .
				       '%; background: #0275d8;"></div>' .
				       '<p>' .
				       __('Watching ', 'sakurairo') . $item['num_watched_episodes'] . '/' . $item['anime_num_episodes'] .
				       '</p>';
			}
			case 6: // Plan to watch
			{
				return '<div class="bangumi-status-bar" style="width: 100%; background: #969ea4;"></div>' .
				       '<p>' .
				       __('Planning to Watch ', 'sakurairo') .
				       '</p>';
			}
			case 4: // Dropped
			{
				return '<div class="bangumi-status-bar" style="width: '.
				       MyAnimeList::status_percent($item) .
				       '%; background: #d9534f;"></div>' .
				       '<p>' .
				       __('Dropped ', 'sakurairo') . $item['num_watched_episodes'] . '/' . $item['anime_num_episodes'] .
				       '</p>';
			}
			case 3: // On Hold
			{
				return '<div class="bangumi-status-bar" style="width: '.
				       MyAnimeList::status_percent($item) .
				       '%; background: #f0ad4e;"></div>' .
				       '<p>' .
				       __('Paused ', 'sakurairo') . $item['num_watched_episodes'] . '/' . $item['anime_num_episodes'] .
				       '</p>';
			}
			default: // TODO: other possible status code
			{
				return '';
			}
		}
	}

	private static function status_percent(array $item)
	{
		if ($item['anime_num_episodes'] == 0)
		{
			return 0;
		}
		else
		{
			return ($item['num_watched_episodes']/$item['anime_num_episodes']) * 100;
		}
	}
}