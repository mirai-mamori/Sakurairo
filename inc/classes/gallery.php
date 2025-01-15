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
                $this->log .= "无法创建目录：{$this->image_dir}。请检查权限。<br>";
                return $this->log;
            }
        }

        if (!is_dir($this->image_folder)) {
            if (!mkdir($this->image_folder, 0755, true)) {
                $this->log .= "无法创建目录：{$this->image_folder}。请检查权限。<br>";
                return $this->log;
            }
        }

        if (!is_dir($this->backup_folder)) {
            if (!mkdir($this->backup_folder, 0755, true)) {
                $this->log .= "无法创建目录：{$this->backup_folder}。请检查权限。<br>";
                return $this->log;
            }
        }

        if (!file_exists($this->image_list)) {
            if (!touch($this->image_list)) {
                $this->log .= "无法创建文件：{$this->image_list}。请检查权限。<br>";
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
                    if ($width / $height < 9 / 16) {
                        $imageFiles['long'][] = $filePath;
                    } else {
                        $imageFiles['wide'][] = $filePath;
                    }
                }
            }
        }

        //保存索引
        file_put_contents($this->image_list, json_encode($imageFiles));

        $this->log .= "初始化索引成功<br>";
        return $this->log;
    }

    //webp优化步骤
    public function webp() {
        $this->log = '';
        $files = scandir($this->image_folder);
        $allowedExtensions = ['jpg', 'jpeg', 'png', 'webp', 'gif'];

        $this->log .= "开始转换<br>";

        foreach ($files as $file) {
            $filePath = $this->image_folder . '/' . $file;
            $fileExtension = pathinfo($file, PATHINFO_EXTENSION);

            if (in_array(strtolower($fileExtension), $allowedExtensions)) {
                //备份原图
                $backupPath = $this->backup_folder . '/' . $file;
                if (!rename($filePath, $backupPath)) {
                    $this->log .= "无法备份文件：$file<br>";
                    $this->log .= "文件权限配置有误，操作终止，请确保所有图片均可读写，部分图片可能已移动至backup目录<br>";
                    return $this->log;
                }

                //压缩图片为 WebP 格式
                $this->convert_to_webp($backupPath, $file);
            }
        }

        $this->log .= "所有图片已压缩为webp，原图在backup文件夹。<br>";
        $this->log .= "请确认无误后重新初始化索引。<br>";

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
        }

        if ($image) {
            $webpPath = $this->image_folder . '/' . pathinfo($filename, PATHINFO_FILENAME) . '.webp';
            imagewebp($image, $webpPath, 80);
            imagedestroy($image);
            $this->log .= "已成功转换为 WebP：$filename<br>";
            return $this->log;
        } else {
            $this->log .= "无法转换文件：$filename<br>";
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
            'message' => '没有找到图片，请联系管理员检查iro_gallary目录下是否存在图片且目录可读写'
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