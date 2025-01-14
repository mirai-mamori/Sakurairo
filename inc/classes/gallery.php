<?php

namespace Sakura\API;

class gallery
{
    private $image_dir;
    private $image_list;
    private $image_folder;
    private $backup_folder;
    private $log = '';

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
                $this->log .= "无法创建目录：{$this->image_dir}。请检查权限。<br>";
                $this->log .= "Unable to create directory: {$this->image_dir}. Please check permissions.<br>";
                return $this->log;
            }
        }

        if (!is_dir($this->image_folder)) {
            if (!mkdir($this->image_folder, 0755, true)) {
                $this->log .= "无法创建目录：{$this->image_folder}。请检查权限。<br>";
                $this->log .= "Unable to create directory: {$this->image_folder}. Please check permissions.<br>";
                return $this->log;
            }
        }

        if (!is_dir($this->backup_folder)) {
            if (!mkdir($this->backup_folder, 0755, true)) {
                $this->log .= "无法创建目录：{$this->backup_folder}。请检查权限。<br>";
                $this->log .= "Unable to create directory: {$this->backup_folder}. Please check permissions.<br>";
                return $this->log;
            }
        }

        if (!file_exists($this->image_list)) {
            if (!touch($this->image_list)) {
                $this->log .= "无法创建文件：{$this->image_list}。请检查权限。<br>";
                $this->log .= "Unable to create file: {$this->image_list}. Please check permissions.<br>";
                return $this->log;
            }
        }
    }

    //生成索引并进行分拣
    public function init($jump = false) {
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

        if (!$jump) {
            $this->log .= "初始化索引成功<br>";
            $this->log .= "Index initialization successful.<br>";
            return $this->log;
        }

        return $this->get_image();  //初始化完成后开始工作
    }

    //webp优化步骤
    public function webp() {
        $this->log = '';
        $files = scandir($this->image_folder);
        $allowedExtensions = ['jpg', 'jpeg', 'png', 'webp', 'gif'];

        $this->log .= "开始转换<br>";
        $this->log .= "Start to convert.<br>";

        foreach ($files as $file) {
            $filePath = $this->image_folder . '/' . $file;
            $fileExtension = pathinfo($file, PATHINFO_EXTENSION);

            if (in_array(strtolower($fileExtension), $allowedExtensions)) {
                //备份原图
                $backupPath = $this->backup_folder . '/' . $file;
                if (!rename($filePath, $backupPath)) {
                    $this->log .= "无法备份文件：$file<br>";
                    $this->log .= "Unable to backup file: $file<br>";
                }

                //压缩图片为 WebP 格式
                $this->convert_to_webp($backupPath, $file);
            }
        }

        $this->log .= "所有图片已压缩为webp，原图在backup文件夹。<br>";
        $this->log .= "All images have been compressed to webp. The original images are in the backup folder.<br>";
        $this->log .= "请确认无误后重新初始化索引。<br>";
        $this->log .= "Please re-initlize the index after ensure the result.<br>";

        return $this->log;
    }

    //webp优化方法
    private function convert_to_webp($source, $filename) {
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
            default:
                $this->log .= "不支持的文件类型：$filename<br>";
                $this->log .= "Unsupported file type: $filename<br>";
        }

        if ($image) {
            $webpPath = $this->image_folder . '/' . pathinfo($filename, PATHINFO_FILENAME) . '.webp';
            imagewebp($image, $webpPath, 80);
            imagedestroy($image);
            $this->log .= "已成功转换为 WebP：$filename<br>";
            $this->log .= "Successfully converted to WebP: $filename<br>";
            return $this->log;
        } else {
            $this->log .= "无法转换文件：$filename<br>";
            $this->log .= "Unable to convert file: $filename<br>";
            return $this->log;
        }
    }

    //获取图片
    public function get_image() {
        $imgParam = isset($_GET['img']) ? sanitize_text_field($_GET['img']) : '';
        $imageList = json_decode(file_get_contents($this->image_list), true);

        if (!empty($imageList)) {
            //img参数优先获取long或wide
            if ($imgParam == 'l' && !empty($imageList['long'])) {
                $random_image = $imageList['long'][array_rand($imageList['long'])];
            } else {
                if ($imgParam == 'w' && !empty($imageList['wide'])) {
                    $random_image = $imageList['wide'][array_rand($imageList['wide'])];
                } else {
                    $all_images = array_merge($imageList['long'] ?? [], $imageList['wide'] ?? []);
                    if (!empty($all_images)) {
                        $random_image = $all_images[array_rand($all_images)];
                    } else {
                        $this->log .= "没有图片。请确保文件夹中有图片。<br>";
                        $this->log .= "No images available. Please ensure there are images in the folder.<br>";
                        return $this->log;
                    }
                }
            }

            $random_image = wp_get_upload_dir()['baseurl'] . $random_image;

            wp_redirect($random_image, 302);
            exit;
        } else {
            $this->log .= "没有图片。请确保文件夹中有图片。<br>";
            $this->log .= "No images available. Please ensure there are images in the folder.<br>";
            return $this->log;
        }
    }
}
?>