<?php

namespace Sakura\API;

define('BGMLIST_VER', '1.1.4');

class BangumiAPI
{
    private $apiUrl = 'https://api.bgm.tv';
    private $userID;
    private $collectionApi;

    public function __construct($userID)
    {
        if (empty($userID)) {
            throw new \InvalidArgumentException('User ID is required.');
        }

        $this->userID = $userID;
        $this->collectionApi = $this->apiUrl . '/v0/users/' . $this->userID . '/collections';
    }

    public function getCollections($isWatching = true, $isWatched = true)
    {
        $collDataArr = [];

        if ($isWatching) {
            $collDataArr = array_merge($collDataArr, $this->fetchCollections(3));
        }

        if ($isWatched) {
            $collDataArr = array_merge($collDataArr, $this->fetchCollections(2));
        }

        $collArr = [];
        foreach ($collDataArr as $value) {
            $collArr[] = [
                'name' => $value['subject']['name'],
                'name_cn' => $value['subject']['name_cn'],
                'date' => $value['subject']['date'],
                'summary' => $value['subject']['short_summary'],
                'url' => 'https://bgm.tv/subject/' . $value['subject']['id'],
                'images' => $value['subject']['images']['large'] ?? '',
                'eps' => $value['subject']['eps'] ?? 0,
                'ep_status' => $value['ep_status'] ?? 0,
            ];
        }

        return $collArr;
    }

    private function fetchCollections($type)
    {
        $collOffset = 0;
        $collDataArr = [];

        do {
            $response = $this->http_get_contents($this->collectionApi . '?subject_type=2&type=' . $type . '&limit=50&offset=' . $collOffset);
            $collData = json_decode($response, true);

            if (isset($collData['data'])) {
                $collDataArr = array_merge($collDataArr, $collData['data']);
            }

            $collOffset += 50;
        } while (!empty($collData['data']) && $collOffset < ($collData['total'] ?? 0));

        return $collDataArr;
    }

    private function http_get_contents($url)
    {
        $response = wp_remote_get($url, ['user-agent' => 'mirai-mamori/Sakurairo/' . BGMLIST_VER . ' (WordPressTheme)']);
        if (!is_wp_error($response) && wp_remote_retrieve_response_code($response) === 200) {
            return wp_remote_retrieve_body($response);
        }

        return json_encode(['error' => is_wp_error($response) ? $response->get_error_message() : 'An unknown error occurred.']);
    }
}

class BangumiList
{
    public function get_bgm_items($userID)
    {
        if (empty($userID)) {
            return '<p>未配置 Bangumi ID</p>';
        }

        try {
            $bgmAPI = new BangumiAPI($userID);
            $collections = $bgmAPI->getCollections();

            if (empty($collections)) {
                return '<p>没有追番数据</p>';
            }

            $html = '';
foreach ($collections as $item) {
    //.column 
    $html .= '<div class="column">';
    $html .= '<a class="bangumi-item" href="' . esc_url($item['url']) . '" target="_blank" rel="nofollow">';
    
    // 图片
    $html .= '<img class="lazyload bangumi-image" data-src="' . esc_url($item['images']) . '" alt="' . esc_attr($item['name']) . '" onerror="imgError(this)" src="' . esc_url($item['images']) . '">';
    $html .= '<noscript><img class="bangumi-image" src="' . esc_url($item['images']) . '" alt="' . esc_attr($item['name']) . '"></noscript>';
    
    // 标题和信息
    $html .= '<div class="bangumi-info">';
    $html .= '<h3 class="bangumi-title" title="' . esc_attr($item['name_cn'] ?: $item['name']) . '">' . esc_html($item['name_cn'] ?: $item['name']) . '</h3>';
    $html .= '<div class="bangumi-date">上映日期: ' . esc_html($item['date']) . '</div>';
    $html .= '<div class="bangumi-status">';
    $html .= '<div class="bangumi-status-bar" style="width: ' . esc_attr(($item['ep_status'] / $item['eps']) * 100) . '%"></div>';
    $html .= '<p>已观看集数: ' . esc_html($item['ep_status'] . '/' . $item['eps']) . '</p>';
    $html .= '<div class="bangumi-summary">';
    $html .= '' . esc_html($item['summary'] ?: '暂无简介') . '</div>';
    $html .= '</div>';
    $html .= '</div>'; // .bangumi-info
    $html .= '</a>'; // .bangumi-item
    $html .= '</div>'; // .column
}

return $html;


            return $html;
        } catch (\Exception $e) {
            return '<p>发生错误: ' . esc_html($e->getMessage()) . '</p>';
        }
    }
}
