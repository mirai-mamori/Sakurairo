<?php

namespace Sakura\API;

class Steam
{
    private $key;
    private $id;
    private $covercdn;
    private $store;
    public function __construct()
    {
        $this->id = iro_opt('steam_id');
        $this->key = iro_opt('steam_key');
        $this->covercdn = iro_opt('steam_covercdn');
        $this->store = iro_opt('steam_store');
    }

    /**
     * @author Ummio
     */

    function fetch_api()
    {
        $id = $this->id;
        $key = $this->key;
        $url = "https://api.steampowered.com/IPlayerService/GetOwnedGames/v1/?key=$key&steamid=$id&include_appinfo=1&include_played_free_games=1&include_free_games=1";

        $response = wp_remote_get($url);
        $data = json_decode($response["body"], true);
        
        // 按最后游玩时间排序
        if (isset($data['response']['games'])) {
            usort($data['response']['games'], function($a, $b) {
                return ($b['rtime_last_played'] ?? 0) - ($a['rtime_last_played'] ?? 0);
            });
        }
        return $data;
    }

    public function get_steam_items()
    {
        $resp = $this->fetch_api();
        $games = $resp['response']['games'];
        $html = "";
        foreach ($games as $game) {
            $playtime = $this->format_playtime($game['playtime_forever']);
            // 如果未游玩则不加载游戏时间
            $last_played = ($game['playtime_forever'] > 0) ? $this->format_last_played($game['rtime_last_played'] ?? 0) : '';
            $html .= $this->game_items($game, $playtime, $last_played);
        }
        return $html;
    }
    
    private function game_items(array $game, $playtime, $last_played)
    {
    return
        '<a class="steam-card" href="' . esc_url($this->get_steam_store($game['appid'])) . '" target="_blank" rel="nofollow">' .
        '<img src="' . esc_url($this->get_steam_covercdn() . '/store_item_assets/steam/apps/' . $game['appid'] . '/header.jpg') . 
        '" alt="' . esc_attr($game['name']) . '" loading="lazy">' .
        '<div class="steam-info">' .
        '<div class="steam-title" title="' . esc_attr($game['name']) . '">' . esc_html($game['name']) . '</div>' .
        '<div class="steam-desc">游玩时长: ' . $playtime . '</div>' .
        ($last_played ? '<div class="steam-desc">上次启动: ' . $last_played . '</div>' : '') .
        '</div>' .
        '</a>';
    }
    
    private function get_steam_covercdn(): string 
    {
        switch($this->covercdn) {
            case 'steamchina':
                return 'https://shared.cdn.steamchina.queniuam.com';
            case 'steamakamai':
                return 'https://shared.akamai.steamstatic.com';
            case 'steamfastly':
                return 'https://shared.fastly.steamstatic.com';
            case 'steamcloudflare':
                return 'https://shared.cloudflare.steamstatic.com';
        }
    }

    private function get_steam_store($appid)
    {
        switch($this->store) {
            case 'steam':
                return 'https://store.steampowered.com/app/' . $appid;
            case 'xiaoheihe':
                return 'https://www.xiaoheihe.cn/app/topic/game/pc/' . $appid;
            case 'steamdb':
                return 'https://steamdb.info/app/' . $appid;
        }
    }

    private function format_playtime($minutes)
    {
        if ($minutes == 0) {
            return __('尚未游玩', 'sakurairo');
        }
        $hours = floor($minutes / 60);
        if ($hours < 1) {
            return $minutes . __('分钟', 'sakurairo');
        }
        $play_hours = rtrim(rtrim(number_format($minutes / 60, 1), '0'), '.');
        return $play_hours . __('小时', 'sakurairo');
    }

    private function format_last_played($timestamp)
    {
        if ($timestamp == 0) {
            return '';
        }
        return date('Y-m-d H:i:s', $timestamp);
    }
}