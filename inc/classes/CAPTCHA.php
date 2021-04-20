<?php

namespace Sakura\API;

class CAPTCHA
{
    private $font;
    private $timestamp;
    private $uniqid;
    /**
     * CAPTCHA constructor.
     */
    public function __construct() {
        $this->font = dirname(dirname(__FILE__)) . '/KumoFont.ttf';
        $this->timestamp = time();
        $this->uniqid = uniqid();
    }
    
    /**
     * create_CAPTCHA
     *
     * @param  string $time
     * @param  string $iqid 
     * @return string
     */    
    private function create_CAPTCHA(string $time,string $iqid) {
        mt_srand(hexdec($iqid)+$time);
        $arr = array_merge(range('a', 'z'), range('A', 'Z'), range(0, 9));
        shuffle( $arr );
        $rand_keys = array_rand( $arr, 5 );
        $str = '';
        foreach ( $rand_keys as $value ) {
            $str .= $arr[$value];
        }
        return $str;
    }

    /**
     * create_captcha_img
     *
     * @return array
     */
    public function create_captcha_img() {
        //创建画布
        $img = imagecreatetruecolor(120, 40);
        setcookie('timestamp',$this->timestamp,$this->timestamp+60,'/');
        setcookie('id',$this->uniqid,$this->timestamp+60,'/');
        //填充背景色
        $backcolor = imagecolorallocate($img, mt_rand(200, 255), mt_rand(200, 255), mt_rand(0, 255));
        imagefill($img, 0, 0, $backcolor);
        
        //创建验证码
        $str = $this->create_CAPTCHA($this->timestamp,$this->uniqid);
        //绘制文字
        for ( $i = 1; $i <= 5; $i++ ) {
            $span = floor(50 / 2.5);
            $stringcolor = imagecolorallocate($img, mt_rand(0, 255), mt_rand(0, 100), mt_rand(0, 80));
            $file = $this->font;
            imagefttext( $img, 25, 2, $i*$span, 30, $stringcolor, $file, $str[$i-1] );
        }

        //添加干扰线
        for ( $i = 1; $i <= 8; $i++ ) {
            $linecolor = imagecolorallocate( $img, mt_rand( 0, 150 ), mt_rand( 0, 250 ), mt_rand( 0, 255 ) );
            imageline( $img, mt_rand( 0, 179 ), mt_rand( 0, 39 ), mt_rand( 0, 179 ), mt_rand( 0, 39 ), $linecolor );
        }

        //添加干扰点
        for ( $i = 1; $i <= 180*40*0.02; $i++ ) {
            $pixelcolor = imagecolorallocate( $img, mt_rand( 100, 150 ), mt_rand( 0, 120 ), mt_rand( 0, 255 ) );
            imagesetpixel( $img, mt_rand( 0, 179 ), mt_rand( 0, 39 ), $pixelcolor );
        }

        //打开缓存区
        ob_start ();
        imagepng($img);
        //输出图片
        $captchaimg =  ob_get_contents();
        //销毁缓存区
        ob_end_clean();
        //销毁图片(释放资源)
        imagedestroy($img);
        // 以json格式输出
        $captchaimg = 'data:image/png;base64,' . base64_encode($captchaimg);
        $json = array(
            'code' => 0,
            'data' => $captchaimg,
            'msg' => ''
        ); 
        return $json;
    }

    
    /**
     * check_CAPTCHA
     *
     * @param  string $captcha
     * @return json
     */
    public function check_CAPTCHA(string $captcha){
        $temp = time();
        $temp1 = $temp-60;
        if (!isset($_COOKIE["timestamp"]) || !isset($_COOKIE['id']) || !ctype_xdigit($_COOKIE['id']) || !ctype_digit($_COOKIE["timestamp"])){
            $code = 3;
            $msg = '非法请求';
        }
        elseif (!$captcha || isset($captcha{5}) || !isset($captcha{4})){
            $code = 3;
            $msg = '请输入正确的验证码!';
        }
        elseif($_COOKIE["timestamp"] < $temp1){
            $code = 2;
            $msg = '超时!';
        }
        elseif($_COOKIE["timestamp"] >= $temp1 && $_COOKIE["timestamp"] <= $temp){
            $comparison = $this->create_CAPTCHA($_COOKIE['timestamp'],$_COOKIE['id']);
            if (strtolower($captcha) === strtolower($comparison)){
                $code = 5;
                $msg = '验证码正确!';
            }else{
                $code = 1;
                $msg = '验证码错误!';
        }
        }else{
            $code = 1;
            $msg = '错误!';
        }
        $json = array(
            'code' => $code,
            'data' => '',
            'msg' => $msg
        ); 
        return $json;
    }
}