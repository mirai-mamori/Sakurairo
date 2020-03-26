<?php
/**
template name: B站追番模版
*/
get_header(); ?>
<style type="text/css">
.theme-dark .yue a:not([data-fancybox=gallery]):not(.post-like):not(.edit-button){border-color:#aaa}
.yue a:not([data-fancybox=gallery]):not(.post-like):not(.edit-button){border-bottom:1px solid rgba(0,0,0,.2)}
.yue a{word-wrap:break-word;word-break:break-all}
.theme-dark a,.theme-dark a:hover{color:#b0b0b0}
.bilibili {
    display: flex;
    flex-wrap: wrap;
    justify-content: center
}
.bgm-item{background-color: rgba(253,253,253,0.30);border-radius:7px;height: 580px;float: left;display:block;margin:10px;width:300px;position:relative;box-shadow:0 0 6px rgba(0,0,0,.2);transition:.3s ease box-shadow;border:none!important;text-decoration:none!important;float:unset!important;}
.bgm-item-thumb{opacity: 0.7;border-top-left-radius: 7px;border-top-right-radius: 7px;transition: all 0.5s;width:100%;padding-top:120%;background-position:center;background-repeat:no-repeat;background-size:cover}
.bgm-item-info{height: 28%;display:flex;flex-direction:column;align-items:center;padding:.5rem;overflow:hidden}
.bgm-item-title{text-overflow:ellipsis;font-size:14px;color:#EEAD9E;font-weight:700;font-style:normal;}
.bgm-item-info>*{display:block;margin:0 auto;max-width:100%}
.bgm-item-statusBar-container{bottom:-22px;width: 97%;margin:.2rem auto;padding:.2em;background:rgba(0,0,0,.2);position:relative;z-index:0;color:#333;font-size:12px;font-weight: 400;font-style: italic;text-align: center;overflow: hidden;}
.bgm-item-statusBar{position:absolute;height:100%;background:#ffb6c1;left:0;top:0;z-index:-1}
.bgm-item:hover{background-color: rgba(253,253,253,0.90);box-shadow: 0 0 6px rgba(0,0,0,.8);}
.bgm-item:hover .bgm-item-thumb{transform:scale(1.02);opacity: 1;}
.bgm-item-titlemain {text-overflow: ellipsis;color: #f36886;font-weight: 400;font-size: 18px;text-shadow: 0 0 3px #fa8282;margin-bottom:8px;}
.page-header{text-align: center;}
.page-header{border-bottom: 0px solid #EE9CA7;}
.page-header h1 small {font-size: 18px;color: #EEAD9E;}
.page-header h1 {color: #ee9ca7;font-weight: 800;}
</style>

<div id="container" class="container" >
    <div class="page-header">
        <h1>我的追番
         <?php
             require_once ("bilibili/bilibiliAnime.php");
             $bili=new bilibiliAnime((akina_option('bilibili_id')),(akina_option('bilibili_cookie')));
            echo "<small>当前已追".$bili->sum."部</small></h1></div><div class=\"bilibili\">";
            function precentage($str1,$str2)
            {
                if(is_numeric($str1) && is_numeric($str2)) return $str1/$str2*100;
                else if ($str1=="0") return 0;
                else return 100;
            }
            for($i=0;$i<$bili->sum;$i++)
            {
                echo "<a class=\"bgm-item\" href=\"https://www.bilibili.com/bangumi/play/ss".$bili->season_id[$i]."/ \" target=\"_blank\"><div class=\"bgm-item-thumb\" style=\"background-image:url(".$bili->image_url[$i].")\"></div><div class=\"bgm-item-info\"><span class=\"bgm-item-titlemain\">".$bili->title[$i]."</span><span class=\"bgm-item-title\">".$bili->evaluate[$i]."</span></div><div class=\"bgm-item-statusBar-container\"><div class=\"bgm-item-statusBar\" style=\"width:".precentage($bili->progress[$i],$bili->total[$i])."%\"></div>".$bili->progress[$i]."/". $bili->total[$i]."</div></a>";
            }
        ?>
    </div>
  </div>

<?php get_footer(); ?>
