<?php

namespace Sakura\API;

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
        $bangumi_cache = iro_opt('bangumi_cache', true);

        if ($bangumi_cache) {
            $cached_content = iro_opt('bangumi_cache_content');
            if ($cached_content) {

                $collArr = json_decode($cached_content, true);

                if (is_array($collArr) && isset($collArr[0]['name'])) {
                    return $collArr;
                    //验证数据格式是否正确（用户自行获取的未清洗）
                }
            }
        }

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

        if ($bangumi_cache) {
            iro_opt_update('bangumi_cache_content', stripslashes(json_encode($collArr, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE)));
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
        $response = wp_remote_get($url, ['user-agent' => 'mirai-mamori/Sakurairo(https://github.com/mirai-mamori/Sakurairo):WordPressTheme']);
        if (!is_wp_error($response) && wp_remote_retrieve_response_code($response) === 200) {
            return wp_remote_retrieve_body($response);
        }

        return json_encode(['error' => is_wp_error($response) ? $response->get_error_message() : 'An unknown error occurred.']);
    }
}

class BangumiList
{
    public function get_bgm_items($userID, $page = 1)
    {
        if (empty($userID)) {
            return '<p>' . __('Bangumi ID not set.', 'sakurairo') . '</p>';
        }

        try {
            $bgmAPI = new BangumiAPI($userID);
            $collections = $bgmAPI->getCollections(true, true);

            if (empty($collections)) {
                return '<p>' . __('No data', 'sakurairo') . '</p>';
            }

            $total = count($collections); // 总条目数
            $perPage = 12; // 每页条目数
            $totalPages = ceil($total / $perPage); // 总页数
            $offset = ($page - 1) * $perPage;
            $collections = array_slice($collections, $offset, $perPage); // 当前页数据

            $html = '';
            foreach ($collections as $item) {
                $html .= '<div class="column">';
                $html .= '<a class="bangumi-item" href="' . esc_url($item['url']) . '" target="_blank" rel="nofollow">';
                $html .= '<img class="lazyload bangumi-image" data-src="' . esc_url($item['images']) . '" alt="' . esc_attr($item['name']) . '" onerror="imgError(this)" src="' . esc_url($item['images']) . '">';
                $html .= '<noscript><img class="bangumi-image" src="' . esc_url($item['images']) . '" alt="' . esc_attr($item['name']) . '"></noscript>';
                $html .= '<div class="bangumi-info">';
                $html .= '<h3 class="bangumi-title" title="' . esc_attr($item['name_cn'] ?: $item['name']) . '">' . esc_html($item['name_cn'] ?: $item['name']) . '</h3>';
                $html .= '<div class="bangumi-date">' . __('Release date: ', 'sakurairo') . esc_html($item['date']) . '</div>';
                $html .= '<div class="bangumi-status">';
                $html .= '<div class="bangumi-status-bar" style="width: ' . esc_attr(($item['ep_status'] / $item['eps']) * 100) . '%"></div>';
                $html .= '<p>' . __('Watch progress: ', 'sakurairo') . esc_html($item['ep_status'] . '/' . $item['eps']) . '</p>';
                $html .= '<div class="bangumi-summary">' . esc_html($item['summary'] ?: __('No introduction yet', 'sakurairo')) . '</div>';
                $html .= '</div></div></a></div>';
            }

            // 分页
            if ($page < $totalPages) {
                $nextPageUrl = rest_url('sakura/v1/bangumi') . '?userID=' . urlencode($userID) . '&page=' . ($page + 1);
                $html .= '<div id="template-pagination">' . self::anchor_pagination_next($nextPageUrl) . '</div>';
            }

            return $html;
        } catch (\Exception $e) {
            return '<p>' . __('An error occured: ', 'sakurairo') . esc_html($e->getMessage()) . '</p>';
        }
    }

    private static function anchor_pagination_next(string $href)
    {
        return '<a class="pagination-next" data-href="' . esc_url($href) . '"><i class="fa-solid fa-bolt-lightning"></i> ' . __('Load more...', 'sakurairo') . '</a>';
    }
}
