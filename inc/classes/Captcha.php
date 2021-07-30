<?php

declare(strict_types=1);

namespace Sakura\API;

class Captcha
{
    private $font;
    private $captchCode;
    private $timestamp;

    /**
     * CAPTCHA constructor.
     */
    public function __construct()
    {
        $this->font = dirname(dirname(__FILE__)) . '/KumoFont.ttf';
        $this->timestamp = time();
        $this->captchCode = '';
    }

    /**
     * create_captcha
     *
     * @return void
     */
    private function create_captcha(): void
    {
        $dict = 'abcdefhjkmnpqrstuvwxy12345678';
        for ($i = 0; $i < 5; $i++) {
            $fontcontent = substr($dict, mt_rand(0, strlen($dict)-1), 1);
            $this->captchCode .= $fontcontent;
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
        return password_hash($this->captchCode, PASSWORD_DEFAULT);
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
    }

    /**
     * create_captcha_img
     *
     * @return array
     */
    public function create_captcha_img(): array
    {
        //创建画布
        $img = imagecreatetruecolor(120, 40);
        //setcookie('timestamp',$this->timestamp,$this->timestamp+60,'/');
        //setcookie('id',$this->uniqid,$this->timestamp+60,'/');
        //填充背景色
        $backcolor = imagecolorallocate($img, mt_rand(200, 255), mt_rand(200, 255), mt_rand(0, 255));
        imagefill($img, 0, 0, $backcolor);

        //创建验证码
        $this->create_captcha();
        //绘制文字
        for ($i = 1; $i <= 5; $i++) {
            $span = 20;
            $stringcolor = imagecolorallocate($img, mt_rand(0, 255), mt_rand(0, 100), mt_rand(0, 80));
            $file = $this->font;
            imagefttext($img, 25, 2, $i * $span, 30, $stringcolor, $file, $this->captchCode[$i - 1]);
        }

        //添加干扰线
        for ($i = 1; $i <= 8; $i++) {
            $linecolor = imagecolorallocate($img, mt_rand(0, 150), mt_rand(0, 250), mt_rand(0, 255));
            imageline($img, mt_rand(0, 179), mt_rand(0, 39), mt_rand(0, 179), mt_rand(0, 39), $linecolor);
        }

        //添加干扰点
        for ($i = 1; $i <= 180 * 40 * 0.02; $i++) {
            $pixelcolor = imagecolorallocate($img, mt_rand(100, 150), mt_rand(0, 120), mt_rand(0, 255));
            imagesetpixel($img, mt_rand(0, 179), mt_rand(0, 39), $pixelcolor);
        }

        //打开缓存区
        ob_start();
        imagepng($img);
        //输出图片
        $captchaimg =  ob_get_contents();
        //销毁缓存区
        ob_end_clean();
        //销毁图片(释放资源)
        imagedestroy($img);
        // 以json格式输出
        $captchaimg = 'data:image/png;base64,' . base64_encode($captchaimg);
        return [
            'code' => 0,
            'data' => $captchaimg,
            'msg' => '',
            'id' => $this->crypt_captcha(),
            'time' => $this->timestamp,
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
        $temp = time();
        $temp1 = $temp - 60;
        if (!isset($timestamp) || !isset($id) || !preg_match('/^[\w$.\/]+$/', $id) || !ctype_digit($timestamp)) {
            $code = 3;
            $msg = '非法请求';
        } elseif (!$captcha || isset($captcha[5]) || !isset($captcha[4])) {
            $code = 3;
            $msg = '请输入正确的验证码!';
        } elseif ($timestamp < $temp1) {
            $code = 2;
            $msg = '超时!';
        } elseif ($timestamp >= $temp1 && $timestamp <= $temp) {
            if ($this->verify_captcha($captcha, $id) == true) {
                $code = 5;
                $msg = '验证码正确!';
            } else {
                $code = 1;
                $msg = '验证码错误!';
            }
        } else {
            $code = 1;
            $msg = '错误!';
        }
        return [
            'code' => $code,
            'data' => '',
            'msg' => $msg
        ];
    }
}
