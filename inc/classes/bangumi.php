<?php

namespace Sakura\API;

class BangumiAPI
{
    // 每页条目数，50 是 bangumi api 的上限
    const PAGE_LIMIT = 50;
    // 安全锁：最多拉取的页数。避免有人追番太多，导致太多调用卡死
    const MAX_PAGES = 10;
    // 安全锁：串行拉取的时间预算（秒）。留出余量，避免撞上 php 的 max_execution_time
    const TIME_BUDGET = 10.0;
    // 未能完整拉取时的缓存时长。短暂缓存，既不让残缺数据占住 30 天，也不至于每次请求都重新拉取
    const PARTIAL_CACHE_TTL = 900;

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
        $this->cache_content = get_transient('bangumi_cache');
    }

    public function getCollections()
    {

        $collDataArr = [];

        $collDataArr = $this->fetchCollections();

        $collArr = $this->get_data($collDataArr);

        return $collArr;
    }

    private function get_data($collDataArr)
    {
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

    private function fetchCollections()
    {
        $bangumi_cache = iro_opt('bangumi_cache', true);
        $cache_key = 'bangumi_cache';
        $collDataArr = [];

        if ($bangumi_cache) {
            $cachedData = get_transient($cache_key);
            $collData = $cachedData ?json_decode($cachedData, true) : null;
    
            if (!isset($collData['data']) || !is_array($collData['data'])) {
                $collData = null;
            }
        } else {
            $collData = null;
        }
    
        if ($collData === null) {
            $collData = $this->fetchAllPages();

            if ($bangumi_cache) {
                if ($collData['complete']) {
                    auto_update_cache($cache_key, json_encode($collData));
                } elseif (!empty($collData['data'])) {
                    // 拉取不完整（中途某页失败或超出时间预算），只短暂缓存。
                    // 缓存默认有效期是 30 天，把残缺的列表写进去会一直错到下个月
                    $this->update_cache($cache_key, json_encode($collData), self::PARTIAL_CACHE_TTL);
                }
                // 一条都没拿到时不写缓存，留给下次请求重试
            }
        }

        // 过滤符合条件的数据。
        // subject_type 校验保留：升级前写入的旧缓存是不带 subject_type 参数拉取的，
        // 只信任接口参数会让书籍/游戏/音乐出现在追番页，直到旧缓存过期
        if (isset($collData['data']) && is_array($collData['data'])) {
            $collDataArr = array_filter($collData['data'], function($item) {
                return in_array($item['type'] ?? 0, [2, 3]) && ($item['subject_type'] ?? 2) == 2;
            });
        }

        return $collDataArr;
    }

    /**
     * 分页拉取全部追番收藏。
     * 返回 bangumi api 的数据形式，另附 complete 标记本次是否拉全。
     */
    private function fetchAllPages()
    {
        $dataList = [];
        $total = 0;
        $complete = false;
        $deadline = microtime(true) + self::TIME_BUDGET;

        for ($i = 0; $i < self::MAX_PAGES; $i++) {
            // 时间预算在发请求前检查，保证最坏耗时不超过 预算 + 单次超时
            if ($i > 0 && microtime(true) > $deadline) {
                error_log('BangumiAPI: time budget exceeded, fetched ' . count($dataList) . ' of ' . $total);
                break;
            }

            $offset = self::PAGE_LIMIT * $i;
            // 只关注番剧，交给接口过滤（subject_type=2）
            $url = $this->collectionApi . '?subject_type=2&limit=' . self::PAGE_LIMIT . '&offset=' . $offset;
            $pageData = json_decode($this->http_get_contents($url), true);

            // 任意一页拿不到数据都算拉取失败，第 1 页也一样要记日志
            if (!isset($pageData['data']) || !is_array($pageData['data'])) {
                error_log('BangumiAPI: fetchCollections failed at offset=' . $offset);
                break;
            }

            // 把新获取到的列表合并到 $dataList 中
            $dataList = array_merge($dataList, $pageData['data']);
            $total = isset($pageData['total']) ? (int)$pageData['total'] : count($dataList);

            // 已经拉满，或者这页不足一页（说明是最后一页）
            if (count($dataList) >= $total || count($pageData['data']) < self::PAGE_LIMIT) {
                $complete = true;
                break;
            }

            if ($i === self::MAX_PAGES - 1) {
                // 触发安全锁。这是预期内的截断，按正常结果缓存，只记日志
                $complete = true;
                error_log('BangumiAPI: reached page limit, fetched ' . count($dataList) . ' of ' . $total);
            }
        }

        // 整理数据，整合为 bangumi api 的返回形式。
        // total 用实际条目数，保证和 data 自洽
        return [
            'data' => $dataList,
            'limit' => self::PAGE_LIMIT,
            'offset' => 0,
            'total' => count($dataList),
            'complete' => $complete,
        ];
    }

    /**
     * 与 auto_update_cache 写同样的三个 key，但允许指定有效期。
     */
    private function update_cache($key, $content, $duration)
    {
        set_transient($key, $content, $duration);
        set_transient($key . '_expire', time() + $duration, $duration);
        set_transient($key . '_duration', $duration, DAY_IN_SECONDS * 30);
    }

    private function http_get_contents($url)
    {
        $response = wp_remote_get($url, [
            'user-agent' => 'mirai-mamori/Sakurairo(https://github.com/mirai-mamori/Sakurairo):WordPressTheme',
            'timeout' => 15     // 设置超时时间为15秒，默认值是5秒；和bilibili模板保持一致
            ]);
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
                $progress = (!empty($item['eps']) && $item['eps'] > 0)
                    ? ($item['ep_status'] / $item['eps']) * 100
                    : 0;
                $html .= '<div class="bangumi-status-bar" style="width: ' . esc_attr($progress) . '%"></div>';
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
        return '<a class="pagination-next" data-href="' . esc_url($href) . '"><i class="fa-solid fa-guitar"></i> ' . __('Load more...', 'sakurairo') . '</a>';
    }
}
