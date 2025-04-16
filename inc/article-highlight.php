<?php
class ColorAnalyzer {
    /**
     * 将 RGB 转换为 HSL
     * 返回数组 [h, s, l]，其中 h 为角度（0~360），s 和 l 为百分比（0~100）
     */
    public static function rgbToHsl($r, $g, $b) {
        $r /= 255;
        $g /= 255;
        $b /= 255;
        $max = max($r, $g, $b);
        $min = min($r, $g, $b);
        $h = 0;
        $s = 0;
        $l = ($max + $min) / 2;

        if ($max == $min) {
            $h = 0;
            $s = 0;
        } else {
            $delta = $max - $min;
            $s = $l > 0.5 ? $delta / (2 - $max - $min) : $delta / ($max + $min);
            if ($max == $r) {
                $h = (($g - $b) / $delta) + ($g < $b ? 6 : 0);
            } elseif ($max == $g) {
                $h = (($b - $r) / $delta) + 2;
            } else {
                $h = (($r - $g) / $delta) + 4;
            }
            $h *= 60;
        }
        return [$h, $s * 100, $l * 100];
    }

    /**
     * 将 HSL 转换为 RGB
     * 输入：h（0~360），s、l（0~100），返回 [r, g, b]（0~255）
     */
    public static function hslToRgb($h, $s, $l) {
        $s /= 100;
        $l /= 100;
        if ($s == 0) {
            $r = $g = $b = $l;
        } else {
            $h /= 360;
            $q = $l < 0.5 ? $l * (1 + $s) : $l + $s - $l * $s;
            $p = 2 * $l - $q;
            $r = self::hue2rgb($p, $q, $h + 1/3);
            $g = self::hue2rgb($p, $q, $h);
            $b = self::hue2rgb($p, $q, $h - 1/3);
        }
        return [round($r * 255), round($g * 255), round($b * 255)];
    }

    private static function hue2rgb($p, $q, $t) {
        if ($t < 0) $t += 1;
        if ($t > 1) $t -= 1;
        if ($t < 1/6) return $p + ($q - $p) * 6 * $t;
        if ($t < 1/2) return $q;
        if ($t < 2/3) return $p + ($q - $p) * (2/3 - $t) * 6;
        return $p;
    }

    // 辅助函数：将值限制在 $min ~ $max 之间
    public static function clamp($value, $min, $max) {
        return max($min, min($value, $max));
    }

    /**
     * 分析图片数据，返回符合约束条件的主题色，格式为 rgba
     *
     * 约束条件：
     * 1. 统计量化后的颜色（容差 16），并仅考虑亮度（l）在 20～80 的颜色。
     * 2. 若所有符合亮度约束的颜色总像素占比低于 10%，则选取全图最常见颜色，并将其亮度调整为 65。
     * 3. 饱和度调整为区间 [30, 65]（低于 30设为30，高于65设为65）。
     */
    public static function getThemeColor($image_data) {
        $im = imagecreatefromstring($image_data);
        if (!$im) {
            return false;
        }

        $width = imagesx($im);
        $height = imagesy($im);

        $color_counts = [];
        $color_hsl = [];  // 存储每个量化颜色对应的 HSL 值
        $total_alpha = 0;
        $pixel_count = 0;
        $tolerance = 16;
        $qualifying_pixel_count = 0;  // 亮度在 [20,80] 的像素数

        for ($x = 0; $x < $width; $x++) {
            for ($y = 0; $y < $height; $y++) {
                $color_index = imagecolorat($im, $x, $y);
                $rgba = imagecolorsforindex($im, $color_index);
                $r = $rgba['red'];
                $g = $rgba['green'];
                $b = $rgba['blue'];
                $alpha = isset($rgba['alpha']) ? $rgba['alpha'] : 0;

                // 根据容差量化颜色
                $r_quant = floor($r / $tolerance) * $tolerance;
                $g_quant = floor($g / $tolerance) * $tolerance;
                $b_quant = floor($b / $tolerance) * $tolerance;
                $key = "$r_quant,$g_quant,$b_quant";

                // 如果还未转换该颜色，则计算其 HSL 值
                if (!isset($color_hsl[$key])) {
                    list($h, $s, $l) = self::rgbToHsl($r_quant, $g_quant, $b_quant);
                    $color_hsl[$key] = ['h' => $h, 's' => $s, 'l' => $l];
                }
                $hsl = $color_hsl[$key];

                // 判断该颜色的亮度是否符合要求（20～80）
                $qualify = ($hsl['l'] >= 20 && $hsl['l'] <= 80);

                if (!isset($color_counts[$key])) {
                    $color_counts[$key] = 0;
                }
                $color_counts[$key]++;

                if ($qualify) {
                    $qualifying_pixel_count++;
                }

                $total_alpha += $alpha;
                $pixel_count++;
            }
        }
        imagedestroy($im);

        if ($pixel_count === 0) {
            return false;
        }

        // 判断符合条件的颜色总像素是否占比 >= 10%
        $qualifying_ratio = $qualifying_pixel_count / $pixel_count;
        $fallback = false;  // 是否需要回退

        if ($qualifying_ratio >= 0.10) {
            // 从符合亮度约束的颜色中选取出现次数最多的颜色
            $max_count = 0;
            $dominant_key = null;
            foreach ($color_counts as $key => $count) {
                $hsl = $color_hsl[$key];
                if ($hsl['l'] >= 20 && $hsl['l'] <= 80) {
                    if ($count > $max_count) {
                        $max_count = $count;
                        $dominant_key = $key;
                    }
                }
            }
            // 若没有找到，则回退
            if ($dominant_key === null) {
                $dominant_key = array_keys($color_counts, max($color_counts))[0];
                $fallback = true;
            }
        } else {
            // 若符合条件的像素总面积不足 10%，则取全图最常见的颜色，
            // 并在后续将其亮度调整为 65
            $dominant_key = array_keys($color_counts, max($color_counts))[0];
            $fallback = true;
        }

        list($dom_r, $dom_g, $dom_b) = explode(',', $dominant_key);
        list($h, $s, $l) = self::rgbToHsl($dom_r, $dom_g, $dom_b);

        // 保存原始亮度值，用于后续判断
        $original_l = $l;

        if ($fallback) {
            // 回退时，将亮度设置为 65
            $l = 65;
        } else {
            // 否则确保亮度在 20～80 之间
            $l = self::clamp($l, 20, 80);
        }
        
        // 新增：根据原始颜色调整亮度
        // 趋近于黑色时增加亮度
        if ($original_l < 30) {
            $l = max($l, 50); // 确保亮度至少为40
        }
        // 趋近于白色时降低亮度
        elseif ($original_l > 70) {
            $l = min($l, 60); // 确保亮度最高为65
        }
        
        // 饱和度调整为 30～65 之间（取高值约束，即不足30则设为30，超过65则设为65）
        $s = self::clamp($s, 40, 65);

        // 转换回 RGB
        list($final_r, $final_g, $final_b) = self::hslToRgb($h, $s, $l);

        // 计算平均 alpha，并转换为 0~255 范围（GD 中 alpha 范围 0 不透明～127 全透明）
        $avg_alpha = round($total_alpha / $pixel_count);
        $alpha_converted = round((127 - $avg_alpha) * 255 / 127);

        return "rgba($final_r, $final_g, $final_b, $alpha_converted)";
    }
}

function get_image_theme_color($input) {
    // 获取图片数据
    $parsed_url = parse_url($input);
    if ($parsed_url && isset($parsed_url['scheme']) && isset($parsed_url['host'])) {

        error_log('获取图片' . $input);

        $parsed_url = parse_url($input);
        if ($parsed_url && isset($parsed_url['path'])) {
            // 将路径拆分成各个部分并编码
            $segments = explode('/', $parsed_url['path']);
            foreach ($segments as &$segment) {
                // 仅对非空的部分编码
                if ($segment !== '') {
                    $segment = rawurlencode($segment);
                }
            }
            $parsed_url['path'] = implode('/', $segments);
        }
        // 重新组装 URL
        $input = (isset($parsed_url['scheme']) ? $parsed_url['scheme'] . '://' : '')
                 . (isset($parsed_url['user']) ? $parsed_url['user'] 
                 . (isset($parsed_url['pass']) ? ':' . $parsed_url['pass'] : '') . '@' : '')
                 . (isset($parsed_url['host']) ? $parsed_url['host'] : '')
                 . (isset($parsed_url['port']) ? ':' . $parsed_url['port'] : '')
                 . (isset($parsed_url['path']) ? $parsed_url['path'] : '')
                 . (isset($parsed_url['query']) ? '?' . $parsed_url['query'] : '')
                 . (isset($parsed_url['fragment']) ? '#' . $parsed_url['fragment'] : '');

        error_log('编码结果为' . $input);
        if (function_exists('wp_get_remote_content')) {
            $image_data = wp_get_remote_content($input);
        } else {
            $image_data = file_get_contents($input);
        }
    } else {
        if (file_exists($input)) {
            $image_data = file_get_contents($input);
        } else {
            return false; // 文件不存在
        }
    }

    if (!$image_data) {
        error_log('失败');
        return false; // 读取图片数据失败
    }
    return ColorAnalyzer::getThemeColor($image_data);
}

// 监听文章保存
add_action('save_post', function ($post_id) {
    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
        return;
    }
    // 获取文章特色图片
    $thumbnail_id = get_post_thumbnail_id($post_id);
    $image_url = $thumbnail_id ? wp_get_attachment_url($thumbnail_id) : '';

    $theme_color = ($image_url) ? get_image_theme_color($image_url) : 'false';

    error_log('计算结果为' . $theme_color);
    update_post_meta($post_id, 'post_theme_color_meta', [
        'theme_color' => $theme_color,
    ]);
});

function get_post_theme_color($post_id) {
    // 读取已存储的 meta 数据
    $meta = get_post_meta($post_id, 'post_theme_color_meta', true);
    $meta = is_array($meta) ? $meta : [];

    // 已有
    if (!empty($meta['theme_color'])) {
        return $meta['theme_color'];
    }

    // 没有则获取
    $thumbnail_id = get_post_thumbnail_id($post_id);
    $image_url = $thumbnail_id ? wp_get_attachment_url($thumbnail_id) : '';

    // 没有特色图片
    if (!$image_url) {
        update_post_meta($post_id, 'post_theme_color_meta', [
            'theme_color' => 'false',
        ]);
        return 'false';
    }

    $theme_color = get_image_theme_color($image_url);
    if ($theme_color === false) {
        $theme_color = 'false';
    }

    // 更新结果
    update_post_meta($post_id, 'post_theme_color_meta', [
        'image_url'   => $image_url,
        'theme_color' => $theme_color,
    ]);

    return $theme_color;
}
?>
