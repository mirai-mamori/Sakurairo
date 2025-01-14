<?php

namespace Sakura\API;

class gallery
{
    private $image_dir;
    private $image_list;
    private $image_folder;
    private $backup_folder;

    //初始化工作目录
    public function __construct() {
        $upload_dir = wp_get_upload_dir()['basedir'];
        $this->image_dir = $upload_dir . '/iro_gallery';
        $this->image_list = $this->image_dir . '/imglist.json';
        $this->image_folder = $this->image_dir . '/img';
        $this->backup_folder = $this->image_dir . '/backup';
        //创建目录
        $this->init_dirs();
    }

    private function init_dirs() {

        if (!is_dir($this->image_dir)) {
            if (!mkdir($this->image_dir, 0755, true)) {
                die("无法创建目录：{$this->image_dir}。请检查权限。");
            }
        }

        if (!is_dir($this->image_folder)) {
            if (!mkdir($this->image_folder, 0755, true)) {
                die("无法创建目录：{$this->image_folder}。请检查权限。");
            }
        }

        if (!is_dir($this->backup_folder)) {
            if (!mkdir($this->backup_folder, 0755, true)) {
                die("无法创建目录：{$this->backup_folder}。请检查权限。");
            }
        }

        if (!file_exists($this->image_list)) {
            if (!touch($this->image_list)) {
                die("无法创建文件：{$this->image_list}。请检查权限。");
            }
        }
    }

    //处理请求
    public function handle_request($request) {
        //获取参数
        $param = $request->get_param('action');
        $nonce = $request->get_param('_wpnonce');

        if ($param && method_exists($this, $param)) {
            if (wp_verify_nonce($nonce, 'gallery')) {
                return $this->$param($request);
            } else {
                return 'Access denied.';
            }
        }

        //默认动作
        return $this->get_image();
    }

    // 生成索引并进行分拣
    private function init($action) {
        $allowedExtensions = ['jpg', 'jpeg', 'bmp', 'png', 'webp', 'gif'];
        $imageFiles = ['long' => [], 'wide' => []];

        if (is_dir($this->image_folder)) {
            $files = scandir($this->image_folder);
            foreach ($files as $file) {
                $filePath = $this->image_folder . '/' . $file;
                $fileExtension = pathinfo($file, PATHINFO_EXTENSION);

                if (in_array(strtolower($fileExtension), $allowedExtensions)) {
                    $imageSize = getimagesize($filePath);
                    $width = $imageSize[0];
                    $height = $imageSize[1];

                    $filePath = '/iro_gallery/img/' . $file;

                    //根据比例分拣图片
                    if ($width / $height < 3 / 4) {
                        $imageFiles['long'][] = $filePath;
                    } else {
                        $imageFiles['wide'][] = $filePath;
                    }
                }
            }
        }

        //保存索引
        file_put_contents($this->image_list, json_encode($imageFiles));

        if(!$action){
            return;
        }

        return $this->get_image();  //初始化完成后开始工作
    }

    //webp优化步骤
    public function webp($request) {
        $files = scandir($this->image_folder);
        $allowedExtensions = ['jpg', 'jpeg', 'png', 'webp', 'gif'];

        foreach ($files as $file) {
            $filePath = $this->image_folder . '/' . $file;
            $fileExtension = pathinfo($file, PATHINFO_EXTENSION);

            if (in_array(strtolower($fileExtension), $allowedExtensions)) {
                //备份原图
                $backupPath = $this->backup_folder . '/' . $file;
                rename($filePath, $backupPath);

                //压缩图片为 WebP 格式
                $this->convert_to_webp($backupPath, $file);
            }
        }

        $this->init(false);
        echo "所有图片已压缩为webp，原图在backup文件夹。";
        exit;
    }

    //webp优化方法
    private function convert_to_webp($source, $filename) {
        $image = null;
        $extension = strtolower(pathinfo($source, PATHINFO_EXTENSION));

        switch ($extension) {
            case 'jpg':
            case 'jpeg':
                $image = imagecreatefromjpeg($source);
                break;
            case 'png':
                $image = imagecreatefrompng($source);
                break;
            case 'gif':
                $image = imagecreatefromgif($source);
                break;
            case 'webp':
                $image = imagecreatefromwebp($source);
                break;
            }


        if ($image) {
            $webpPath = $this->image_folder . '/' . pathinfo($filename, PATHINFO_FILENAME) . '.webp';
            imagewebp($image, $webpPath, 80);
            imagedestroy($image);
        }
    }

    //获取图片
    private function get_image() {
        $imgParam = isset($_GET['img']) ? $_GET['img'] : null;
        $imageList = json_decode(file_get_contents($this->image_list), true);

        if (!empty($imageList)) {
            //img=l优先返回竖版图片
            if ($imgParam == 'l' && !empty($imageList['long'])) {
                $random_image = $imageList['long'][array_rand($imageList['long'])];
            } else {
                $all_images = array_merge($imageList['long'] ?? [], $imageList['wide'] ?? []);
                if (!empty($all_images)) {
                    $random_image = $all_images[array_rand($all_images)];
                } else {
                    echo "没有图片。请确保文件夹中有图片。";
                    exit;
                }
            }

            $random_image = wp_get_upload_dir()['baseurl'] . $random_image;

            wp_redirect($random_image, 302);
            exit;
        } else {
            echo "没有图片。请确保文件夹中有图片。";
            exit;
        }
    }
}
