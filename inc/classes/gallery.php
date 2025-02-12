<?php

// 内建随机图api
// 工作目录在wp-content/uploads/iro_gallery

namespace Sakura\API;

class gallery
{
    private $image_dir;
    private $image_list;
    private $image_folder;
    private $backup_folder;
    private $log = '';

    //定义工作目录
    public function __construct() {
        $upload_dir = wp_get_upload_dir()['basedir'];
        $this->image_dir = $upload_dir . '/iro_gallery';
        $this->image_list = $this->image_dir . '/imglist.json';
        $this->image_folder = $this->image_dir . '/img';
        $this->backup_folder = $this->image_dir . '/backup';
        //创建目录和索引
        $this->init_dirs();
    }

    private function init_dirs() {
        //初始化工作目录
        $dirs = [$this->image_dir, $this->image_folder, $this->backup_folder];

        foreach ($dirs as $dir) {
            if (!is_dir($dir) && !mkdir($dir, 0755, true)) {
                $this->log .= __("Unable to create directory: $dir. Please check permissions.", "sakurairo") . '<br>';
                return $this->log;
            }
        }
        //初始化索引
        if (!file_exists($this->image_list)) {
            if (!touch($this->image_list)) {
                $this->log .= __("Unable to create file: {$this->image_list}. Please check permissions.", "sakurairo") . '<br>';
                return $this->log;
            }
        }
    }

    //生成索引并进行分拣
    public function init() {
        $allowedExtensions = ['jpg', 'jpeg', 'bmp', 'png', 'webp', 'gif'];
        $imageFiles = ['long' => [], 'wide' => []];

        $allFiles = $this->get_all_files($this->image_folder);

        foreach ($allFiles as $filePath) {
            if (in_array(strtolower(pathinfo($filePath, PATHINFO_EXTENSION)), $allowedExtensions)) {
                //获取图片信息进行分拣
                $imageSize = @getimagesize($filePath);

                if ($imageSize === false) {
                    continue;
                }

                $width = $imageSize[0];
                $height = $imageSize[1];

                $filePath = str_replace($this->image_folder, '/iro_gallery/img', $filePath);

                //根据比例分拣图片
                if ($width / $height < 9 / 10) {
                    $imageFiles['long'][] = $filePath;
                } else {
                    $imageFiles['wide'][] = $filePath;
                }
            }
        }

        //保存索引
        file_put_contents($this->image_list, json_encode($imageFiles));

        $this->log .= __("Successfully initialized the index.", "sakurairo") . '<br>';
        return $this->log;
    }

    //遍历目录方法
    private function get_all_files($directory) {
        $result = [];
        $files = scandir($directory);

        foreach ($files as $file) {
            if ($file === '.' || $file === '..') {
                continue;
            }

            $filePath = $directory . '/' . $file;
            if (is_dir($filePath)) {
                $result = array_merge($result, $this->get_all_files($filePath));
            } else {
                $result[] = $filePath;
            }
        }

        return $result;
    }

    //webp优化步骤
    public function webp() {
        $this->log = '';
        $allowedExtensions = ['jpg', 'jpeg', 'png', 'webp', 'gif'];

        //检查backup目录是否有内容
        if (!is_dir($this->backup_folder) || count(scandir($this->backup_folder)) <= 2) {
            //没有则执行备份步骤
            if (!rename($this->image_folder, $this->backup_folder)) {
                $this->log .= __("The target directory is not accessible. Please check the permission settings.", "sakurairo") . '<br>';
                return $this->log;
            }
            if (!mkdir($this->image_folder, 0755, true)) {
                $this->log .= __("The target directory is not accessible. Please check the permission settings.", "sakurairo") . '<br>';
                return $this->log;
            }
            $this->log .= __("Successfully backed up images from the 'img' folder to the 'backup' folder.", "sakurairo") . '<br>';
        } else {
            $this->log .= __("Detected content in the 'backup' folder. Verifying and attempting to restore conversion operations.", "sakurairo") . '<br>';
        }

        $allFiles = $this->get_all_files($this->backup_folder);

        foreach ($allFiles as $backupPath) {
            if (!in_array(strtolower(pathinfo($backupPath, PATHINFO_EXTENSION)), $allowedExtensions)) {
                continue;
            }

            //生成 WebP 文件的相对路径和目标路径
            $relativePath = str_replace($this->backup_folder . '/', '', $backupPath);  //相对路径
            $pathInfo = pathinfo($relativePath);
            $webpPath = $this->image_folder . '/' . $pathInfo['dirname'] . '/' . $pathInfo['filename'] . '.webp';

            //跳过已存在的WebP文件(从上个断点继续转换)
            if (file_exists($webpPath)) {
                $this->log .= __("Skipped file: {$relativePath}, a webp image with the same name already exists.", "sakurairo") . '<br>';
                continue;
            }

            //确保目标子目录存在
            $targetDir = dirname($webpPath);
            if (!is_dir($targetDir)) {
                mkdir($targetDir, 0755, true);
            }

            //转换文件
            $this->convert_to_webp($backupPath, $webpPath);
        }

        $this->log .= __("All images have been compressed to WebP format. The original files are stored in the 'backup' folder.<br> Please confirm correctness before reinitializing the index.<br>", "sakurairo") . '<br>';

        return $this->log;
    }

    //webp优化方法
    private function convert_to_webp($source, $webpPath) {
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
                $this->log .= __("Unsupported file type: $source .", "sakurairo") . '<br>';
        }

        if ($image) {
            imagewebp($image, $webpPath, 80);
            imagedestroy($image);
            $this->log .= __("Successfully converted to WebP: $source .", "sakurairo") . '<br>';
            return $this->log;
        } else {
            $this->log .= __("Failed to convert file: $source .", "sakurairo") . '<br>';
            return $this->log;
        }
    }

    //获取图片
    public function get_image() {
        $imgParam = isset($_GET['img']) ? sanitize_text_field($_GET['img']) : '';
        $imageList = json_decode(file_get_contents($this->image_list), true);

        if (empty($imageList)) {
            $this->init(true);
        }

        $error_info = array(
            'status' => 500,
            "success" => false,
            'message' => __("No images found. Please contact the administrator to check if images exist in the 'iro_gallary' directory and ensure the directory is readable and writable.", "sakurairo") . '<br>',
        );
        $error = new \WP_REST_Response($error_info, 500);
        $error->set_status(500);

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
                        return $error;
                    }
                }
            }

            $random_image = wp_get_upload_dir()['baseurl'] . $random_image;

            wp_redirect($random_image, 302);
            exit;
        } else {
            return $error;
        }
    }
}
?>