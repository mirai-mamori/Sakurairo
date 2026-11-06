<?php

declare(strict_types=1);
//TODO: 打个标记 下次一定改静态类（
namespace Sakura\API;

class Captcha
{
    private $captchaText;
    private $captchaResult;

    /**
     * CAPTCHA constructor.
     */
    public function __construct()
    {
        $this->captchaText = '';
        $this->captchaResult = '';
    }

    /**
     * create_captcha
     *
     * @return void
     */
    private function create_captcha(): void
    {
        // 4 位随机数字：答案空间 9000，避免旧实现 0-198 的答案被低成本枚举。
        $this->captchaText = (string) random_int(1000, 9999);
        $this->captchaResult = $this->captchaText;
    }

    /**
     * 解析代理感知的客户端 IP。
     *
     * 优先调用主题的 get_the_user_ip()（它遵循 sakura_trust_x_forwarded_for），
     * 主题函数缺失或返回非法值时回退到 REMOTE_ADDR；结果可经
     * `sakurairo_captcha_client_ip` 过滤器覆盖。返回值始终是合法 IP 或空串。
     *
     * 注意：get_the_user_ip() 默认信任 X-Forwarded-For，只有站点位于可信的
     * 反向代理/CDN（如 Cloudflare）之后才安全。直接暴露的站点应显式关闭：
     * add_filter('sakura_trust_x_forwarded_for', '__return_false');
     * 否则攻击者可伪造该请求头绕过本限流（每个伪造 IP 都是独立桶）。
     *
     * @return string
     */
    private function client_ip(): string
    {
        $ip = '';
        if (function_exists('get_the_user_ip')) {
            try {
                $ip = (string) get_the_user_ip();
            } catch (\Throwable $e) {
                $ip = '';
            }
        }
        if (!filter_var($ip, FILTER_VALIDATE_IP)) {
            $ip = isset($_SERVER['REMOTE_ADDR'])
                ? sanitize_text_field(wp_unslash((string) $_SERVER['REMOTE_ADDR']))
                : '';
        }

        /**
         * Filters the client IP used for captcha mint throttling.
         *
         * Return a stable identifier (normally an IP) when the default detection
         * is wrong, or an empty string to disable per-IP throttling entirely.
         * The returned value must be a valid IP address to be used.
         *
         * @param string $ip Detected client IP, or '' when unknown.
         */
        $ip = (string) apply_filters('sakurairo_captcha_client_ip', $ip);

        return filter_var($ip, FILTER_VALIDATE_IP) ? $ip : '';
    }

    /**
     * 按客户端 IP 的固定窗口铸造限流（10 分钟）。
     *
     * REST 端点与 wp-login.php / 注册 / 找回密码页面共用同一个桶：
     * 避免绕过 REST 直接反复刷新登录页无限铸造 120s transient。
     *
     * @return bool true 允许铸造；false 已超限。
     */
    private function can_mint(): bool
    {
        $ip = $this->client_ip();
        if ($ip === '') {
            // 无法确定客户端 IP 时不限流，避免误伤所有访客（与旧实现一致）。
            return true;
        }

        /**
         * Filters the maximum number of captcha mints per client IP per 10 minutes.
         *
         * @param int $limit Maximum mints. Default 30.
         */
        $limit = (int) apply_filters('sakurairo_captcha_mint_limit', 30);
        $limit = max(1, $limit);
        $key = 'sakurairo_captcha_mint_' . md5($ip);
        $count = (int) get_transient($key);

        if ($count >= $limit) {
            return false;
        }

        set_transient($key, $count + 1, 600);

        return true;
    }

    /**
     * create_captcha_img
     *
     * @return array
     */
    public function create_captcha_img(): array
    {
        // 铸造限流内聚在本方法：REST 端点和登录/注册/找回密码页面共用同一 IP 桶。
        // 被限流时不生成答案、不写 transient，直接返回结构化错误（code !== 0）。
        if (!$this->can_mint()) {
            return [
                'code' => 1,
                'data' => '',
                'msg'  => __('Too many captcha requests. Please try again later.', 'sakurairo'),
                'id'   => '',
                'time' => 0,
            ];
        }

        //动态计算验证码难度
        $level = iro_opt('iro_captcha_level') / 100;
        $conf = array(
          'noise' => (int)(700 + 500 * $level),
          'curves' => (int)(8 + 6 * $level),
          'quality' => (int)(100 - 40 * $level)
        );
        
        //创建验证码
        $this->create_captcha();
        $captcha_answer = (string) $this->captchaResult;
        // 自 wordpress 6.4.0 起，弃用了STYLESHEETPATH定义. 为确保子主题能正常使用，应当使用get_template_directory()接口
        $font = get_template_directory() . '/inc/KumoFont.ttf';
        
        //创建画布
        $image = imagecreatetruecolor(210, 60);
        //填充背景色
        $color = imagecolorallocate($image, mt_rand(200, 255), mt_rand(200, 255), mt_rand(200, 255));
        imagefill($image, 0, 0, $color);

        //绘制文字
        $chars = str_split($this->captchaText);
        for ($i = 0; $i < count($chars); $i++) {
            $char = $chars[$i];
            $color = imagecolorallocate($image, mt_rand(0, 150), mt_rand(0, 150), mt_rand(0, 150));
            $x = 30 * $i + 10;
            $y = 30 + mt_rand(-5, 5);
            //加减符号不倾斜 并加大字体
            $size = ($i === 2) ? 30 : 20;
            $angle = ($i === 2) ? 0 : mt_rand(-25, 25);
            imagettftext($image, $size, $angle, $x, $y, $color, $font, $char);
        }
        
        //添加噪点
        for ($i = 0; $i < $conf['noise']; $i++) {
            $color = imagecolorallocate($image, mt_rand(0, 255), mt_rand(0, 255), mt_rand(0, 255));
            imagesetpixel($image, mt_rand(0, 210), mt_rand(0, 60), $color);
        }
        
        // 添加贝塞尔曲线
        for ($i = 0; $i < $conf['curves']; $i++) {
            $color = imagecolorallocate($image, mt_rand(50,150), mt_rand(50, 150), mt_rand(50, 150));
            
            // 贝塞尔曲线控制点
            $x1 = mt_rand(0, 210);
            $x2 = mt_rand(0, 210);
            $cx1 = mt_rand(0, 210);
            $cx2 = mt_rand(0, 210);
            $y1 = mt_rand(0, 60);
            $y2 = mt_rand(0, 60);
            $cy1 = mt_rand(0, 60);
            $cy2 = mt_rand(0, 60);
            
            // 绘制贝塞尔曲线
            for ($t = 0; $t <= 1; $t += 0.01) {
                $xt = (int)((1 - $t) * (1 - $t) * (1 - $t) * $x1 + 3 * (1 - $t) * (1 - $t) * $t * $cx1 + 3 * (1 - $t) * $t * $t * $cx2 + $t * $t * $t * $x2);
                $yt = (int)((1 - $t) * (1 - $t) * (1 - $t) * $y1 + 3 * (1 - $t) * (1 - $t) * $t * $cy1 + 3 * (1 - $t) * $t * $t * $cy2 + $t * $t * $t * $y2);
                imagesetpixel($image, $xt, $yt, $color);
            }
        }
        //启用高斯模糊，进一步降低清晰度
        imagefilter($image, IMG_FILTER_GAUSSIAN_BLUR);
        
        $timestamp = time();
        $token = wp_generate_password(32, false, false);
        // 答案只保存在服务端；客户端只拿到一次性 token，无法自行伪造验证结果。
        set_transient(
            'sakurairo_captcha_' . $token,
            array(
                'answer' => $captcha_answer,
                'time'   => $timestamp,
            ),
            120
        );
        //打开缓存区
        ob_start();
        //降低图片质量
        imagejpeg($image, null, $conf['quality']);
        //输出图片
        $captchaimg =  ob_get_contents();
        //销毁缓存区
        ob_end_clean();
        //销毁图片(释放资源)
        imagedestroy($image);
        // 以json格式输出
        $captchaimg = 'data:image/png;base64,' . base64_encode($captchaimg);
        return [
            'code' => 0,
            'data' => $captchaimg,
            'msg' => '',
            'id' => $token,
            'time' => $timestamp,
        ];
    }


    /**
     * check_captcha
     *
     * 验证码答案保存在服务端 transient 中，客户端提交的 id 只是一次性 token；
     * 因此无法像旧实现那样在客户端自行计算 password_hash 后通过校验。
     *
     * @param  string $captcha   用户输入的答案
     * @param  int    $timestamp 验证码生成时间
     * @param  string $id        服务端签发的一次性 token
     * @return array
     */
    public function check_captcha(string $captcha, int $timestamp, string $id): array
    {
        if (!preg_match('/^[A-Za-z0-9]{20,64}$/', $id) || !ctype_digit((string) $timestamp)) {
            return [
                'code' => 3,
                'data' => '',
                'msg'  => __('Bad Request.', 'sakurairo'),
            ];
        }

        if (!preg_match('/^[0-9]{4}$/', $captcha)) {
            return [
                'code' => 3,
                'data' => '',
                'msg'  => __('Look like you forgot to enter the captcha.', 'sakurairo'),
            ];
        }

        $stored = get_transient('sakurairo_captcha_' . $id);

        if (!is_array($stored) || !isset($stored['answer'], $stored['time'])) {
            return [
                'code' => 3,
                'data' => '',
                'msg'  => __('Bad Request.', 'sakurairo'),
            ];
        }

        $current_time = time();

        if ((int) $stored['time'] !== $timestamp) {
            return [
                'code' => 3,
                'data' => '',
                'msg'  => __('Have you modified the captcha code data? Or refresh the captcha and try again?', 'sakurairo'),
            ];
        }

        if ($timestamp < $current_time - 60 || $timestamp > $current_time) {
            return [
                'code' => 2,
                'data' => '',
                'msg'  => __('Captcha timeout.', 'sakurairo'),
            ];
        }

        if (hash_equals((string) $stored['answer'], (string) $captcha)) {
            delete_transient('sakurairo_captcha_' . $id);

            return [
                'code' => 5,
                'data' => '',
                'msg'  => __('Captcha check passed.', 'sakurairo'),
            ];
        }

        // token 一次性使用：答错立即失效，避免在答案空间内反复猜测。
        delete_transient('sakurairo_captcha_' . $id);

        return [
            'code' => 1,
            'data' => '',
            'msg'  => __('Captcha incorrect.', 'sakurairo'),
        ];
    }
}
