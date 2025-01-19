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
                $this->log .= __("Unable to create directory: {$this->image_dir}. Please check permissions.", "sakurairo") . '<br>';
                return $this->log;
            }
        }

        if (!is_dir($this->image_folder)) {
            if (!mkdir($this->image_folder, 0755, true)) {
                $this->log .= __("Unable to create directory: {$this->image_folder}. Please check permissions.", "sakurairo") . '<br>';
                return $this->log;
            }
        }

        if (!is_dir($this->backup_folder)) {
            if (!mkdir($this->backup_folder, 0755, true)) {
                $this->log .= __("Unable to create directory: {$this->backup_folder}. Please check permissions.", "sakurairo") . '<br>';
                return $this->log;
            }
        }

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
                    if ($width / $height < 9 / 10) {
                        $imageFiles['long'][] = $filePath;
                    } else {
                        $imageFiles['wide'][] = $filePath;
                    }
                }
            }
        }

        //保存索引
        file_put_contents($this->image_list, json_encode($imageFiles));

        $this->log .= __("Successfully initialized the index.", "sakurairo") . '<br>';
        return $this->log;
    }

    //webp优化步骤
    public function webp() {
        $this->log = '';
        $allowedExtensions = ['jpg', 'jpeg', 'png', 'webp', 'gif'];

        if (!is_dir($this->backup_folder) || count(scandir($this->backup_folder)) <= 2) { //检查backup目录
            
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

        $files = scandir($this->backup_folder);
        foreach ($files as $file) {
            $backupPath = $this->backup_folder . '/' . $file;
            $fileExtension = strtolower(pathinfo($file, PATHINFO_EXTENSION));
            $webpPath = $this->image_folder . '/' . pathinfo($file, PATHINFO_FILENAME) . '.webp';

            if (!in_array($fileExtension, $allowedExtensions)) {
                continue;
            }

            if (file_exists($webpPath)) {
                $this->log .= __("Skipped file: {$file}, a file with the same name already exists.", "sakurairo") . '<br>'; //跳过同名以支持断点恢复
                continue;
            }

            $this->convert_to_webp($backupPath, $file);

        }

        $this->log .= __("All images have been compressed to WebP format. The original files are stored in the 'backup' folder.<br> Please confirm correctness before reinitializing the index.<br>", "sakurairo") . '<br>';

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
                $this->log .= __("Unsupported file type: $filename .", "sakurairo") . '<br>';
        }

        if ($image) {
            $webpPath = $this->image_folder . '/' . pathinfo($filename, PATHINFO_FILENAME) . '.webp';
            imagewebp($image, $webpPath, 80);
            imagedestroy($image);
            $this->log .= __("Successfully converted to WebP: $filename .", "sakurairo") . '<br>';
            return $this->log;
        } else {
            $this->log .= __("Failed to convert file: $filename .", "sakurairo") . '<br>';
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