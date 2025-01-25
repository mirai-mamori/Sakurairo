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
        $n1 = mt_rand(10, 99);
        $n2 = mt_rand(10, 99);
        if (mt_rand(0, 1)) {
            //加法
            $this->captchaText = "{$n1}+{$n2}=?";
            $this->captchaResult = $n1 + $n2;
        } else {
            //减法(避免负数)
            $min = min($n1, $n2);
            $max = max($n1, $n2);
            $this->captchaText = "{$max}-{$min}=?";
            $this->captchaResult = $max - $min;
        }
    }

    /**
     * crypt_captcha
     *
     * @return string
     */
    private function crypt_captcha(): string
    {
        //return md5($this->captchCode);
        return password_hash($this->captchaResult, PASSWORD_DEFAULT);
        // return wp_hash_password($this->captchCode);
    }

    /**
     * verify_captcha
     *
     * @param  string $captcha
     * @param  string $hash
     * @return bool
     */
    public function verify_captcha(string $captcha, string $hash): bool
    {
        //return md5($captcha) == $hash ? true : false;
        return password_verify($captcha, $hash);
        // return wp_check_password($captcha, $hash);
    }

    /**
     * create_captcha_img
     *
     * @return array
     */
    public function create_captcha_img(): array
    {
        //动态计算验证码难度
        $level = iro_opt('iro_captcha_level') / 100;
        $conf = array(
          'noise' => (int)(700 + 500 * $level),
          'curves' => (int)(8 + 6 * $level),
          'quality' => (int)(100 - 40 * $level)
        );
        
        //创建验证码
        $this->create_captcha();
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
        $this->captchaResult .= $timestamp;
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
            'id' => $this->crypt_captcha(),
            'time' => $timestamp,
        ];
    }


    /**
     * check_captcha
     *
     * @param  string $captcha
     * @return array
     */
    public function check_captcha(string $captcha, int $timestamp, string $id): array
    {
        $currentTime = time();
        $timeThreshold = $currentTime - 60;
        if (!isset($timestamp) || !isset($id) || !preg_match('/^[\w$.\/]+$/', $id) || !ctype_digit((string)$timestamp)) {
            $code = 3;
            $msg = __('Bad Request.',"sakurairo");//非法请求
        } elseif (!preg_match('/^(?:(?!199)(?:[1-9]\d?|1\d{2}|0))$/', $captcha)) {
            //匹配非0 ~ 198
            $code = 3;
            $msg = __("Look like you forgot to enter the captcha.","sakurairo");//请输入正确的验证码!
        } elseif ($timestamp < $timeThreshold) {
            $code = 2;
            $msg =  __("Captcha timeout.","sakurairo");//超时!
        } elseif ($timestamp >= $timeThreshold && $timestamp <= $currentTime) {
            if ($this->verify_captcha($captcha . $timestamp, $id)) {
                $code = 5;
                $msg = __("Captcha check passed.","sakurairo");//'验证码正确!'
            } else {
                $code = 1;
                $msg = __("Captcha incorrect.","sakurairo");//'验证码错误!'
            }
        } else {
            $code = 1;
            $msg = __("An error has occurred.","sakurairo");//'错误!'
        }
        return [
            'code' => $code,
            'data' => '',
            'msg' => $msg
        ];
    }
}
