<?php
header("Content-Type: text/css; charset=UTF-8");
header("Cache-Control: public, max-age=86400");
header("Expires: " . gmdate("D, d M Y H:i:s", time() + 86400) . " GMT");

$style_files = [
    '../style.css',
    'dark.css',
    'responsive.css',
    'animation.css',
    'templates.css'
];

if (isset($_GET['sakura_header'])) {
    $style_files[] = 'sakura_header.css';
}
if (isset($_GET['wave'])) {
    $style_files[] = 'wave.css';
}
if (isset($_GET['github'])) {
    $style_files[] = './content-style/github.css';
}
if (isset($_GET['sakura'])) {
    $style_files[] = './content-style/sakura.css';
}

$minify = isset($_GET['minify']); // 是否压缩

function compressCSS($css) {
    // 移除注释、换行和多余空格
    $css = preg_replace("/\/\*.*?\*\//s", "", $css); // 移除注释
    $css = preg_replace("/\s*([{};:,])\s*/", "$1", $css); // 移除空格
    $css = preg_replace("/;}/", "}", $css); // 修正分号
    return trim($css);
}

$output = "";
foreach ($style_files as $style) {
    $file_path = __DIR__ . '/' . $style;
    if (file_exists($file_path)) {
        $content = file_get_contents($file_path);
        
        // 添加文件名注释
        $output .= "\n/* === " . basename($style) . " === */\n";
        
        if ($minify) {
            $output .= compressCSS($content);
        } else {
            $output .= $content;
        }
    }
}

echo $output;
?>