<?php
/**
 * Module: 本地音乐库
 *
 * 曲库目录默认 /var/www/html/wp-content/bgm（可在设置中改）。
 * 歌词优先级：同名 .lrc → 音频内嵌标签（USLT 等）→ 空。
 * 输出 APlayer 列表结构，接到现有悬浮播放器（meting_api_url）。
 */

if (!defined('ABSPATH')) {
    exit;
}

function personal_local_music_root() {
    $dir = iro_opt('local_music_dir', '/var/www/html/wp-content/bgm');
    $dir = str_replace('\\', '/', (string) $dir);
    $dir = rtrim($dir, '/');
    return $dir === '' ? '/var/www/html/wp-content/bgm' : $dir;
}

function personal_local_music_extensions() {
    return array('mp3', 'flac', 'm4a', 'mp4', 'aac', 'ogg', 'oga', 'wav', 'webm');
}

/**
 * 相对路径 → 绝对路径；拒绝穿越曲库根之外
 */
function personal_local_music_abs($rel) {
    $rel = str_replace('\\', '/', (string) $rel);
    $rel = ltrim($rel, '/');
    while (strpos($rel, './') === 0) {
        $rel = substr($rel, 2);
    }
    if ($rel === '' || strpos($rel, '..') !== false) {
        return false;
    }
    $root = realpath(personal_local_music_root());
    if ($root === false || !is_dir($root)) {
        return false;
    }
    $abs = realpath($root . '/' . $rel);
    if ($abs === false) {
        return false;
    }
    $rootNorm = rtrim(str_replace('\\', '/', $root), '/') . '/';
    $absNorm = str_replace('\\', '/', $abs);
    if (strpos($absNorm, $rootNorm) !== 0 && $absNorm !== rtrim($rootNorm, '/')) {
        return false;
    }
    return $abs;
}

function personal_local_music_id($rel) {
    return rtrim(strtr(base64_encode($rel), '+/', '-_'), '=');
}

function personal_local_music_rel_from_id($id) {
    $id = (string) $id;
    $id = strtr($id, '-_', '+/');
    $pad = strlen($id) % 4;
    if ($pad > 0) {
        $id .= str_repeat('=', 4 - $pad);
    }
    $rel = base64_decode($id, true);
    if ($rel === false) {
        return false;
    }
    return str_replace('\\', '/', $rel);
}

/**
 * 递归扫描曲库，返回相对路径列表（按路径排序，稳定）
 */
function personal_local_music_scan() {
    $root = realpath(personal_local_music_root());
    if ($root === false || !is_dir($root)) {
        return array();
    }
    $exts = personal_local_music_extensions();
    $found = array();
    $iterator = new RecursiveIteratorIterator(
        new RecursiveDirectoryIterator($root, FilesystemIterator::SKIP_DOTS),
        RecursiveIteratorIterator::LEAVES_ONLY
    );
    foreach ($iterator as $file) {
        if (!$file->isFile()) {
            continue;
        }
        $ext = strtolower(pathinfo($file->getFilename(), PATHINFO_EXTENSION));
        if (!in_array($ext, $exts, true)) {
            continue;
        }
        $abs = str_replace('\\', '/', $file->getPathname());
        $rel = ltrim(substr($abs, strlen(rtrim(str_replace('\\', '/', $root), '/'))), '/');
        $found[] = $rel;
    }
    natcasesort($found);
    return array_values($found);
}

/**
 * 文件名启发式：Artist - Title.ext
 */
function personal_local_music_parse_filename($rel) {
    $base = pathinfo($rel, PATHINFO_FILENAME);
    if (strpos($base, ' - ') !== false) {
        list($artist, $name) = explode(' - ', $base, 2);
        return array(trim($name) !== '' ? trim($name) : $base, trim($artist));
    }
    return array($base, '');
}

/**
 * 从 getID3 数组里取出「第一个有意义的字符串」
 * 兼容: "text" / array(0=>"text") / array("data"=>"text") / array("text"=>"text")
 */
/**
 * 强制把 &#20013; / &#x4e2d; / &amp;#20013; / &#10; 等还原成 UTF-8 文本
 * （getID3、htmlentities、部分转发层会把中文/换行实体化）
 */
function personal_local_music_decode_entities($s) {
    $s = (string) $s;
    if ($s === '') {
        return '';
    }
    for ($i = 0; $i < 8; $i++) {
        $prev = $s;
        // 先拆掉常见的二次包裹
        $s = str_replace(array('&amp;#', '&#038;#'), '&#', $s);
        $s = html_entity_decode($s, ENT_QUOTES | ENT_HTML5, 'UTF-8');
        // 手动兜底：缺分号 / html_entity_decode 漏网
        $s = preg_replace_callback('/&\#0*(\d+);?/', function ($m) {
            $n = intval($m[1], 10);
            if ($n > 0 && $n < 0x110000 && function_exists('mb_chr')) {
                $ch = mb_chr($n, 'UTF-8');
                return $ch !== false ? $ch : $m[0];
            }
            return $m[0];
        }, $s);
        $s = preg_replace_callback('/&\#x0*([0-9a-fA-F]+);?/i', function ($m) {
            $n = intval($m[1], 16);
            if ($n > 0 && $n < 0x110000 && function_exists('mb_chr')) {
                $ch = mb_chr($n, 'UTF-8');
                return $ch !== false ? $ch : $m[0];
            }
            return $m[0];
        }, $s);
        // 换行/空白实体
        $s = str_replace(array('&#10;', '&#13;', '&#9;', '&#xA;', '&#xD;'), array("\n", "\r", "\t", "\n", "\r"), $s);
        $s = str_replace(array('&#010;', '&#013;'), array("\n", "\r"), $s);
        if ($s === $prev) {
            break;
        }
    }
    return $s;
}

function personal_local_music_clean_text($s) {
    $s = personal_local_music_decode_entities($s);
    $s = personal_local_music_decode_entities($s);
    return trim($s);
}

function personal_local_music_tag_string($value) {
    if (is_string($value)) {
        $value = personal_local_music_clean_text($value);
        return $value === '' ? '' : $value;
    }
    if (!is_array($value)) {
        return '';
    }
    foreach (array('data', 'text', 'value', 'lyric', 'description', 0) as $k) {
        if (!isset($value[$k])) {
            continue;
        }
        $s = personal_local_music_tag_string($value[$k]);
        // USLT 的 description 常为空，不要拿它当正文
        if ($s !== '' && !($k === 'description' && strlen($s) < 3)) {
            return $s;
        }
    }
    // list of strings
    foreach ($value as $item) {
        if (is_string($item) && trim($item) !== '') {
            return personal_local_music_clean_text($item);
        }
    }
    return '';
}

function personal_local_music_is_lyric_key($key) {
    $key = strtolower((string) $key);
    $needles = array(
        'unsynchronised_lyrics', 'unsynchronized_lyrics', 'unsyncedlyrics',
        'unsynced_lyrics', 'unsynced lyrics', 'lyrics', 'lyric',
        'uslt', 'sylt', 'synchronised_lyrics', 'synchronized_lyrics',
        'syncedlyrics', 'synced_lyrics', 'unsyncedlyrics',
    );
    foreach ($needles as $n) {
        if ($key === $n || strpos($key, $n) !== false) {
            return true;
        }
    }
    return false;
}

/**
 * 深度搜索嵌入歌词（USLT / LYRICS / unsynchronised_lyrics 等）
 */
function personal_local_music_find_embedded_lrc($node, &$best, $parentKey = '') {
    if (!is_array($node)) {
        return;
    }
    foreach ($node as $key => $value) {
        $keyStr = (string) $key;
        if (personal_local_music_is_lyric_key($keyStr) || personal_local_music_is_lyric_key($parentKey)) {
            $text = personal_local_music_tag_string($value);
            // 忽略像是 URL 的误匹配；长度用解码后比较，避免实体串“更长”而胜出
            if ($text !== '' && strlen($text) > 1 && !preg_match('#^https?://#i', $text)) {
                if (strlen($text) > strlen($best)) {
                    $best = $text;
                }
            }
        }
        if (is_array($value)) {
            personal_local_music_find_embedded_lrc($value, $best, $keyStr);
        }
    }
}

/**
 * 同一时间戳的多行歌词合并为一行：原文（译文）
 * 双语 LRC 常见写法 [00:10]原文\n[00:10]译文，播放器高度不够会只露出第二行
 */
function personal_local_music_merge_dual_lrc($lrc) {
    $lrc = (string) $lrc;
    if ($lrc === '' || strpos($lrc, '[') === false) {
        return $lrc;
    }
    $meta = array();
    $buckets = array(); // sec => array(stamp, texts)
    $lines = preg_split('/\r\n|\r|\n/', $lrc);
    $time_re = '/\[(\d{1,3}):(\d{2})(?:[.:](\d{1,3}))?\]/';

    foreach ($lines as $line) {
        $trim = trim($line);
        if ($trim === '') {
            continue;
        }
        if (!preg_match($time_re, $trim)) {
            if (preg_match('/^\[[A-Za-z]+:.*\]$/', $trim)) {
                $meta[] = $trim;
            }
            continue;
        }
        preg_match_all($time_re, $trim, $all, PREG_SET_ORDER);
        $text = trim(preg_replace($time_re, '', $trim));
        if ($text === '') {
            continue;
        }
        $m = $all[0];
        $sec = intval($m[1]) * 60 + intval($m[2]);
        if (isset($m[3]) && $m[3] !== '') {
            $frac = str_pad($m[3], 3, '0');
            $sec += floatval('0.' . $frac);
        }
        $key = sprintf('%.3f', $sec);
        if (!isset($buckets[$key])) {
            $buckets[$key] = array('stamp' => $m[0], 'sec' => $sec, 'texts' => array());
        }
        if (!in_array($text, $buckets[$key]['texts'], true)) {
            $buckets[$key]['texts'][] = $text;
        }
    }

    if (empty($buckets)) {
        return $lrc;
    }

    ksort($buckets, SORT_NUMERIC);
    $out = $meta;
    foreach ($buckets as $row) {
        $texts = $row['texts'];
        $orig = array_shift($texts);
        if (!empty($texts)) {
            $orig .= '（' . implode(' / ', $texts) . '）';
        }
        $out[] = $row['stamp'] . $orig;
    }
    return implode("\n", $out) . "\n";
}

function personal_local_music_normalize_lrc($lrc) {
    $lrc = personal_local_music_decode_entities((string) $lrc);
    $lrc = personal_local_music_decode_entities($lrc);
    // ID3 偶发 UTF-16/BOM 残留
    if (strncmp($lrc, "\xEF\xBB\xBF", 3) === 0) {
        $lrc = substr($lrc, 3);
    }
    if (strncmp($lrc, "\xFF\xFE", 2) === 0 || strncmp($lrc, "\xFE\xFF", 2) === 0) {
        if (function_exists('mb_convert_encoding')) {
            $enc = strncmp($lrc, "\xFF\xFE", 2) === 0 ? 'UTF-16LE' : 'UTF-16BE';
            $lrc = mb_convert_encoding($lrc, 'UTF-8', $enc);
        }
    }
    $lrc = str_replace("\r\n", "\n", $lrc);
    $lrc = str_replace("\r", "\n", $lrc);
    $lrc = trim($lrc);
    if ($lrc === '') {
        return '';
    }
    // 已是 LRC 时间轴：合并同时间戳的原文/译文
    if (preg_match('/\[\d{1,3}:\d{2}([:\.]\d{1,3})?\]/', $lrc)) {
        return personal_local_music_merge_dual_lrc($lrc);
    }
    // 纯文本歌词 → 生成可展示的时间轴
    $converted = '';
    $i = 0;
    foreach (explode("\n", $lrc) as $line) {
        if (trim($line) === '') {
            continue;
        }
        $converted .= sprintf("[%02d:%02d.00]%s\n", (int) floor($i / 60), $i % 60, $line);
        $i++;
    }
    return $converted;
}

function personal_local_music_read_id3($abs) {
    $out = array(
        'name'       => '',
        'artist'     => '',
        'cover_raw'  => '',
        'cover_mime' => '',
        'lrc'        => '',
    );
    if (!defined('ABSPATH') || !is_readable($abs)) {
        return $out;
    }
    $getid3_lib = ABSPATH . WPINC . '/ID3/getid3.php';
    if (!file_exists($getid3_lib)) {
        return $out;
    }
    require_once $getid3_lib;
    if (!class_exists('getID3')) {
        return $out;
    }
    try {
        $getID3 = new getID3();
        $getID3->option_md5_data = false;
        $getID3->option_sha1_data = false;
        $getID3->option_tags_html = true;
        $info = $getID3->analyze($abs);
    } catch (Exception $e) {
        return $out;
    }

    // 标题 / 艺术家：优先原始 comments（未实体化），再回退
    $flat = array();
    foreach (array('comments', 'tags', 'comments_html') as $section) {
        if (empty($info[$section]) || !is_array($info[$section])) {
            continue;
        }
        foreach ($info[$section] as $group) {
            if (!is_array($group)) {
                continue;
            }
            foreach ($group as $k => $v) {
                $ks = strtolower((string) $k);
                if (!isset($flat[$ks])) {
                    $flat[$ks] = $v;
                }
            }
        }
    }
    foreach (array('title', 'name') as $k) {
        $s = personal_local_music_tag_string(isset($flat[$k]) ? $flat[$k] : '');
        if ($s !== '') {
            $out['name'] = personal_local_music_decode_entities(wp_strip_all_tags($s));
            break;
        }
    }
    foreach (array('artist', 'author', 'albumartist', 'album_artist') as $k) {
        $s = personal_local_music_tag_string(isset($flat[$k]) ? $flat[$k] : '');
        if ($s !== '') {
            $out['artist'] = personal_local_music_decode_entities(wp_strip_all_tags($s));
            break;
        }
    }

    // 嵌入歌词：深度搜索 USLT / LYRICS / unsynchronised_lyrics …
    $lrc = '';
    personal_local_music_find_embedded_lrc($info, $lrc);
    $lrc = personal_local_music_decode_entities($lrc);
    $lrc = personal_local_music_normalize_lrc($lrc);
    if ($lrc !== '') {
        $out['lrc'] = $lrc;
    }

    // 内嵌封面（原始字节，playlist 不再内联 data URI）
    if (!empty($info['comments']['picture'][0]['data'])) {
        $pic = $info['comments']['picture'][0];
        $out['cover_raw'] = $pic['data'];
        $out['cover_mime'] = !empty($pic['image_mime']) ? $pic['image_mime'] : 'image/jpeg';
    } elseif (!empty($info['id3v2']['APIC'][0]['data'])) {
        $pic = $info['id3v2']['APIC'][0];
        $out['cover_raw'] = $pic['data'];
        $out['cover_mime'] = !empty($pic['image_mime']) ? $pic['image_mime'] : 'image/jpeg';
    }

    return $out;
}

/**
 * 歌词：同名 .lrc 优先，否则内嵌，再空
 */
function personal_local_music_lyric($rel) {
    $abs = personal_local_music_abs($rel);
    if ($abs === false) {
        return '';
    }
    // 1) 同名 .lrc
    $lrcPath = preg_replace('/\.[^.]+$/', '.lrc', $abs);
    if (is_readable($lrcPath)) {
        $lrc = personal_local_music_decode_entities(file_get_contents($lrcPath));
        $lrc = personal_local_music_normalize_lrc($lrc);
        if ($lrc !== '') {
            return $lrc;
        }
    }
    // 2) 嵌入标签（USLT 等）
    $id3 = personal_local_music_read_id3($abs);
    return $id3['lrc'];
}

function personal_local_music_cover($rel, $id3 = null) {
    $abs = personal_local_music_abs($rel);
    if ($abs === false) {
        return '';
    }
    if ($id3 === null) {
        $id3 = personal_local_music_read_id3($abs);
    }
    // 内嵌封面走 REST，避免把整张 JPEG base64 塞进 playlist JSON
    if (!empty($id3['cover_raw']) && !empty($id3['cover_mime'])) {
        return personal_local_music_file_url($rel, 'embedcover');
    }
    $base = preg_replace('/\.[^.]+$/', '', str_replace('\\', '/', $rel));
    foreach (array('jpg', 'jpeg', 'png', 'webp', 'gif') as $ext) {
        $coverRel = $base . '.' . $ext;
        if (is_readable(personal_local_music_abs($coverRel))) {
            return personal_local_music_file_url($coverRel, 'raw');
        }
    }
    return '';
}

function personal_local_music_rest_base() {
    return rest_url('sakura/v1/local-music');
}

function personal_local_music_file_url($rel, $type = 'url', $name = '') {
    $base = personal_local_music_rest_base();
    $q = array(
        'type' => $type,
        'id'   => personal_local_music_id($rel),
    );
    if ($name !== '') {
        $q['name'] = $name;
    }
    // 登录态下 REST cookie 鉴权需要 _wpnonce(wp_rest)，否则 LRC/音频 403
    if (function_exists('wp_create_nonce')) {
        $q['_wpnonce'] = wp_create_nonce('wp_rest');
    }
    return add_query_arg($q, $base);
}

/**
 * 读 playlist.json（若存在），否则扫描目录
 * playlist.json: [ {"file":"a.mp3","name":"","artist":""}, ... ]
 * 或 [ "a.mp3", "sub/b.mp3" ]
 */
function personal_local_music_tracks() {
    $root = personal_local_music_root();
    $playlistFile = $root . '/playlist.json';
    $tracks = array();

    if (is_readable($playlistFile)) {
        $raw = file_get_contents($playlistFile);
        $json = json_decode($raw, true);
        if (is_array($json)) {
            foreach ($json as $row) {
                if (is_string($row)) {
                    $rel = str_replace('\\', '/', $row);
                } elseif (is_array($row) && !empty($row['file'])) {
                    $rel = str_replace('\\', '/', $row['file']);
                } else {
                    continue;
                }
                $rel = ltrim($rel, '/');
                if (strpos($rel, '..') !== false) {
                    continue;
                }
                $abs = personal_local_music_abs($rel);
                if ($abs === false || !is_file($abs)) {
                    continue;
                }
                $tracks[] = array(
                    'rel'    => $rel,
                    'name'   => isset($row['name']) ? (string) $row['name'] : '',
                    'artist' => isset($row['artist']) ? (string) $row['artist'] : '',
                );
            }
        }
    }

    if (empty($tracks)) {
        foreach (personal_local_music_scan() as $rel) {
            $tracks[] = array('rel' => $rel, 'name' => '', 'artist' => '');
        }
    }

    return $tracks;
}

function personal_local_music_playlist() {
    $list = array();
    foreach (personal_local_music_tracks() as $track) {
        $rel = $track['rel'];
        $id3 = personal_local_music_read_id3(personal_local_music_abs($rel));
        list($fname, $fartist) = personal_local_music_parse_filename($rel);
        $name = $track['name'] !== '' ? $track['name'] : ($id3['name'] !== '' ? $id3['name'] : $fname);
        $artist = $track['artist'] !== '' ? $track['artist'] : ($id3['artist'] !== '' ? $id3['artist'] : $fartist);
        $list[] = array(
            'name'   => personal_local_music_clean_text($name),
            'artist' => personal_local_music_clean_text($artist),
            'url'    => personal_local_music_file_url($rel, 'url'),
            'cover'  => personal_local_music_cover($rel, $id3),
            'lrc'    => personal_local_music_file_url($rel, 'lyric'),
            // 便于调试/二次开发
            'id'     => personal_local_music_id($rel),
        );
    }
    return $list;
}

// ---------- REST ----------

add_action('rest_api_init', function () {
    register_rest_route('sakura/v1', '/local-music', array(
        'methods'             => 'GET',
        'callback'            => 'personal_local_music_rest',
        'permission_callback' => '__return_true',
    ));
});

/**
 * 裸输出（绕过 WP_REST_Response 的二次 JSON 编码 / \u 转义）
 */
function personal_local_music_emit($body, $contentType, $cache = 'max-age=300') {
    while (ob_get_level() > 0) {
        ob_end_clean();
    }
    header('Content-Type: ' . $contentType);
    header('Cache-Control: ' . $cache);
    header('X-Content-Type-Options: nosniff');
    echo $body;
    exit;
}

function personal_local_music_rest(WP_REST_Request $request) {
    $type = sanitize_text_field($request->get_param('type'));
    $id = sanitize_text_field($request->get_param('id'));

    if ($type === 'playlist' || $type === '') {
        $data = personal_local_music_playlist();
        $json = json_encode($data, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_PARTIAL_OUTPUT_ON_ERROR);
        if ($json === false) {
            $json = '[]';
        }
        personal_local_music_emit($json, 'application/json; charset=utf-8', 'max-age=300');
    }

    $rel = personal_local_music_rel_from_id($id);
    if ($rel === false) {
        return new WP_REST_Response(array('message' => 'Bad id'), 400);
    }

    if ($type === 'lyric') {
        // 与网易云 Meting 成功路径完全一致：
        // set_data(LRC 字符串) → WP JSON 编码 → APlayer JSON.parse
        $lrc = personal_local_music_lyric($rel);
        $lrc = personal_local_music_decode_entities($lrc);
        $lrc = personal_local_music_normalize_lrc($lrc);
        $lrc = personal_local_music_decode_entities($lrc);
        $lrc = personal_local_music_normalize_lrc($lrc);
        if ($lrc === '') {
            $lrc = "[00:00.000]此歌曲暂无歌词，请您欣赏";
        }
        $response = new WP_REST_Response();
        $response->set_headers(array(
            'cache-control' => 'max-age=60',
            'Content-Type'  => 'text/plain; charset=utf-8',
        ));
        $response->set_data($lrc);
        return $response;
    }

    if ($type === 'embedcover') {
        $abs = personal_local_music_abs($rel);
        $id3 = $abs ? personal_local_music_read_id3($abs) : array();
        if (empty($id3['cover_raw'])) {
            return new WP_REST_Response(array('message' => 'No cover'), 404);
        }
        personal_local_music_emit($id3['cover_raw'], $id3['cover_mime'] ?: 'image/jpeg', 'max-age=86400');
    }

    if ($type === 'url' || $type === 'pic' || $type === 'raw') {
        $abs = personal_local_music_abs($rel);
        if ($type === 'pic' && $abs !== false) {
            // id 为音频路径时，name 为同目录封面文件名
            $name = sanitize_file_name((string) $request->get_param('name'));
            if ($name !== '') {
                $relDir = dirname(str_replace('\\', '/', $rel));
                $coverRel = ($relDir === '.' || $relDir === '') ? $name : $relDir . '/' . $name;
                $coverAbs = personal_local_music_abs($coverRel);
                if ($coverAbs !== false) {
                    $abs = $coverAbs;
                }
            }
        }

        if ($abs === false || !is_file($abs)) {
            return new WP_REST_Response(array('message' => 'Not found'), 404);
        }

        $mime = wp_check_filetype($abs)['type'];
        if (empty($mime)) {
            $mime = 'application/octet-stream';
        }
        $size = filesize($abs);
        $fp = fopen($abs, 'rb');
        if (!$fp) {
            return new WP_REST_Response(array('message' => 'Unreadable'), 403);
        }

        // 支持 Range，便于 <audio> 拖动进度
        $start = 0;
        $end = $size > 0 ? $size - 1 : 0;
        header('Content-Type: ' . $mime);
        header('Accept-Ranges: bytes');
        header('Cache-Control: public, max-age=86400');
        if (isset($_SERVER['HTTP_RANGE'])) {
            if (preg_match('/bytes=(\d*)-(\d*)/', $_SERVER['HTTP_RANGE'], $m)) {
                if ($m[1] !== '') {
                    $start = intval($m[1]);
                }
                if ($m[2] !== '') {
                    $end = intval($m[2]);
                }
                if ($end >= $size) {
                    $end = $size > 0 ? $size - 1 : 0;
                }
                if ($start > $end || $start >= $size) {
                    header('HTTP/1.1 416 Range Not Satisfiable');
                    fclose($fp);
                    exit;
                }
                header('HTTP/1.1 206 Partial Content');
                header('Content-Range: bytes ' . $start . '-' . $end . '/' . $size);
            }
        }
        header('Content-Length: ' . ($end - $start + 1));
        fseek($fp, $start);
        $remaining = $end - $start + 1;
        while ($remaining > 0 && !feof($fp)) {
            $chunk = fread($fp, min(8192, $remaining));
            if ($chunk === false) {
                break;
            }
            echo $chunk;
            $remaining -= strlen($chunk);
        }
        fclose($fp);
        exit;
    }

    return new WP_REST_Response(array('message' => 'Unknown type'), 400);
}

// 前端接入：inc/swicher.php 在 aplayer_server=local 时把 meting_api_url
// 指到 rest_url('sakura/v1/local-music')，APlayer 原有 playlist/url/lrc/pic 流程不变。
