<?php
/**
 * Custom function
 * @Siren
*/

// 允许分类、标签描述添加html代码
remove_filter('pre_term_description', 'wp_filter_kses');
remove_filter('term_description', 'wp_kses_data');
// 去除顶部工具栏
show_admin_bar(false);

function get_edit_html():string{
  global $user_ID;
  if ($user_ID && current_user_can('level_10')) {
    return '<span class="bull">·</span><a href="' . get_edit_post_link() . '">'.__("EDIT","sakurairo").'</a>';
}
  return '';
}

/*
 * 视频
 */
function bgvideo(){
  $dis=null;
  if(!iro_opt('cover_video'))return '';
  if(!iro_opt('cover_full_screen')) $dis = 'display:none;';
  $html = '<div id="video-container" style="'.$dis.'">'; 
  $html .= '<video id="bgvideo" class="video" preload="auto"></video>';
  $html .= '<div id="video-btn" class="loadvideo videolive"></div>';
  $html .= '<div id="video-add"></div>';
  $html .= '<div class="video-stu"></div>';
  $html .= '</div>';
  return $html;
}


/*
 * 使用本地图片作为头像，防止外源抽风问题
 */
function get_avatar_profile_url():string{ 
  $avatar = iro_opt('personal_avatar') ?: get_avatar_url(get_the_author_meta( 'ID' ));
  return $avatar;
}


/*
 * 随机图
 */
function get_random_bg_url():string{
  return rest_url('sakura/v1/image/feature').'?'.rand(1,1000);
}


/*
 * 订制时间样式
 * poi_time_since(strtotime($post->post_date_gmt));
 * poi_time_since(strtotime($comment->comment_date_gmt), true );
 * 如果中途修改过Linux系统时间则继续使用GMT可能出现时差问题!!
 * poi_time_since(strtotime($post->post_date));
 * poi_time_since(strtotime($comment->comment_date), true );
 */
function poi_time_since( $older_date, $comment_date = false, $text = false ) {
  $chunks = array(
    array( 24 * 60 * 60, __( ' days ago', 'sakurairo' ) ),/*天前*/
    array( 60 * 60 , __( ' hours ago', 'sakurairo' ) ),/*小时前*/
    array( 60 , __( ' minutes ago', 'sakurairo' ) ),/*分钟前*/
    array( 1, __( ' seconds ago', 'sakurairo' ) )/*秒前*/
  );
  $newer_date = time() - (iro_opt('time_zone_fix')*60*60);
  $since = abs( $newer_date - $older_date );
  if($text){
    $output = '';
  }else{
    $output = __('Posted on ','sakurairo')/*发布于*/;
  }

  if ( $since < 30 * 24 * 60 * 60 ) {
    foreach( $chunks as $chunk ) {
      $seconds = $chunk[0];
      $name    = $chunk[1];
      if ( ( $count = floor( $since / $seconds ) ) != 0 ) {
        break;
      }
    }
    $output .= $count . $name;
  } else {
    $output .= $comment_date ? date( 'Y-m-d H:i', $older_date ) : date( 'Y-m-d', $older_date );
  }

  return $output;
}


/*
 * 首页不显示指定的分类文章
 */
if(iro_opt('classify_display')){
  function classify_display($query){
    $source = iro_opt('classify_display');
    $cats = explode(',', $source);
    $cat = '';
    if ( $query->is_home ) {
      foreach($cats as $k => $v) {
        $cat .= '-'.$v.','; //重组字符串
      }
      $cat = trim($cat,',');
      $query->set( 'cat', $cat);
    }
    return $query;
  }
  add_filter( 'pre_get_posts', 'classify_display' ); 
}


/*
 * 评论添加@
 */
function comment_add_at( $comment_text, $comment = '') {
  if( isset($comment->comment_parent) && $comment->comment_parent > 0) {
      if(substr($comment_text, 0, 3) === "<p>") 
        $comment_text = str_replace(substr($comment_text, 0, 3), '<p><a href="#comment-' . $comment->comment_parent . '" class="comment-at">@'.get_comment_author( $comment->comment_parent ) . '</a>&nbsp;', $comment_text);
      else
        $comment_text = '<a href="#comment-' . $comment->comment_parent . '" class="comment-at">@'.get_comment_author( $comment->comment_parent ) . '</a>&nbsp;' . $comment_text;
  }
  return $comment_text;
}
add_filter( 'comment_text' , 'comment_add_at', 20, 2);


/*
 * Ajax评论
 */
if ( version_compare( $GLOBALS['wp_version'], '4.4-alpha', '<' ) ) { wp_die(__('Please upgrade wordpress to version 4.4+','sakurairo')); }/*请升级到4.4以上版本*/
// 提示
if(!function_exists('siren_ajax_comment_err')) {
    function siren_ajax_comment_err($t) {
        header('HTTP/1.0 500 Internal Server Error');
        header('Content-Type: text/plain;charset=UTF-8');
        echo $t;
        exit;
    }
}
// 机器评论验证
function siren_robot_comment(){
  if ( !$_POST['no-robot'] && !is_user_logged_in()) {
     siren_ajax_comment_err('上车请刷卡。<br>Please comfirm you are not a robot.');
  }
}
if(iro_opt('not_robot')) add_action('pre_comment_on_post', 'siren_robot_comment');
// 纯英文评论拦截
function scp_comment_post( $incoming_comment ) {
  // 为什么要拦自己呢？
  global $user_ID; 
  if( $user_ID && current_user_can('level_10') ) {
    return( $incoming_comment );
  } elseif(!preg_match('/[一-龥]/u', $incoming_comment['comment_content'])){
    siren_ajax_comment_err('写点汉字吧。You should add some Chinese words.');
  }
  return( $incoming_comment );
}
// add_filter('preprocess_comment', 'scp_comment_post');
// 国际化很重要
// 评论提交
if(!function_exists('siren_ajax_comment_callback')) {
    function siren_ajax_comment_callback(){
      $comment = wp_handle_comment_submission( wp_unslash( $_POST ) );
      if( is_wp_error( $comment ) ) {
        $data = $comment->get_error_data();
        if ( !empty( $data ) ) {
          siren_ajax_comment_err($comment->get_error_message());
        } else {
          if(count($_POST)<=1){
                  siren_ajax_comment_err("你好像提交了一个空表单。");
          }else{
            siren_ajax_comment_err(join(',',array_keys($comment->errors)));
                 }
        }
      }
      $user = wp_get_current_user();
      do_action('set_comment_cookies', $comment, $user);
      $GLOBALS['comment'] = $comment; //根据你的评论结构自行修改，如使用默认主题则无需修改
      ?>
      <li <?php comment_class(); ?> id="comment-<?php echo esc_attr(comment_ID()); ?>">
        <div class="contents">
          <div class="comment-arrow">
            <div class="main shadow">
                <div class="profile">
                  <a href="<?php comment_author_url(); ?>"><?php echo get_avatar( $comment->comment_author_email, '80', '', get_comment_author() ); ?></a>
                </div>
                <div class="commentinfo">
                  <section class="commeta">
                    <div class="left">
                      <h4 class="author"><a href="<?php comment_author_url(); ?>"><?php echo get_avatar( $comment->comment_author_email, '80', '', get_comment_author() ); ?><?php comment_author(); ?> <span class="isauthor" title="<?php esc_attr_e('Author', 'sakurairo'); ?>"></span></a></h4>
                    </div>
                    <div class="right">
                      <div class="info"><time datetime="<?php comment_date('Y-m-d'); ?>"><?php echo poi_time_since(strtotime($comment->comment_date_gmt), true );//comment_date(get_option('date_format')); ?></time></div>
                    </div>
                  </section>
                </div>
                <div class="body">
                  <?php comment_text(); ?>
                </div>
            </div>
            <div class="arrow-left"></div>
          </div>
        </div>
      </li>
      <?php die();
    }
}
add_action('wp_ajax_nopriv_ajax_comment', 'siren_ajax_comment_callback');
add_action('wp_ajax_ajax_comment', 'siren_ajax_comment_callback');


/*
 * 前台登录
 */
// 指定登录页面
if(iro_opt('exlogin_url')){
  add_action('login_enqueue_scripts','login_protection');
  function login_protection(){
    if($_GET['word'] != 'press'){
      $admin_url = iro_opt('exlogin_url');
      wp_redirect( $admin_url );
      exit;
    }
  }
}

//attention: 目前仅有登陆模板在用这个函数捏，登陆模板是一个要deprecate的状态
// 登录跳转
function Exuser_center(){ ?>
  <script type='text/javascript'>
    <?php
/* var URL;
var TYPE; */ //不要污染全局命名空间啊啊啊啊啊啊啊啊啊啊啊啊啊啊啊啊啊啊啊啊啊啊啊啊啊啊啊啊啊啊啊啊啊
?>
function gopage(url,descr) {
    function cb(sec) {
        document.getElementById('login-showtime').innerHTML = '<?= __("Login successful, ","sakurairo")/*空降成功*/?>'
        + sec + '<?= __("seconds later automatically transfer to","sakurairo")/*秒后自动转到*/?>' + descr;
        if (sec == 0) { window.location = url; } else { window.setTimeout(() => { cb(sec - 1); }, 1000); }
    }
    window.setTimeout(() => { cb(5); }, 1000); //倒计时秒数在这捏
}
  </script>  
  <?php if(current_user_can('level_10')){ ?>
  <div class="admin-login-check">
    <?php 
    echo login_ok(); 
     if(iro_opt('login_urlskip')){ ?>
     <script>gopage("<?php bloginfo('url'); ?>/wp-admin/","<?=__('dashboard','sakurairo')/*管理后台*/?>");</script>
     <?php } ?>
  </div>
  <?php }else{ ?>
  <div class="user-login-check">
    <?php 
    echo login_ok(); 
     if(iro_opt('login_urlskip')){?>
     <script>gopage("<?php bloginfo('url'); ?>","<?=__('home','sakurairo')/*主页*/?>");</script>
        <?php } ?>
  </div>
<?php 
  }
}

// 登录成功
function login_ok(){ 
  global $current_user;
  wp_get_current_user();
?>
  <p class="ex-login-avatar"><a href="http://cn.gravatar.com/" title="<?php _e('Change avatar','sakurairo')/*更换头像*/?>" target="_blank" rel="nofollow"><?php echo get_avatar( $current_user->user_email, '110' ); ?></a></p>
  <p class="ex-login-username"><?php _e('Hello, ','sakurairo')/*你好，*/?><strong><?php echo $current_user->display_name; ?></strong></p>
  <?php if($current_user->user_email){echo '<p>'.$current_user->user_email.'</p>';} ?>
  <p id="login-showtime"></p>
  <p class="ex-logout">
    <a href="<?php bloginfo('url'); ?>" title="<?php _e('Home','sakurairo')/*首页*/?>"><?php _e('Home','sakurairo')/*首页*/?></a>
    <?php if(current_user_can('level_10')){  ?>
    <a href="<?php bloginfo('url'); ?>/wp-admin/" title="<?php _e('Manage','sakurairo')/*后台*/?>" target="_top"><?php _e('Manage','sakurairo')/*后台*/?></a> 
    <?php } ?>
    <a href="<?php echo wp_logout_url(get_bloginfo('url')); ?>" title="<?php _e('Logout','sakurairo')/*登出*/?>" target="_top"><?php _e('Sign out? ','sakurairo')/*登出？*/?></a>
  </p>
<?php 
}


/*
 * 文章，页面头部背景图
 */
function the_headPattern(){
  $t = ''; // 标题
  $full_image_url = wp_get_attachment_image_src(get_post_thumbnail_id(get_the_ID()), 'full');
  if(is_single()){
    $full_image_url = !empty($full_image_url) ? $full_image_url[0] : null;
    if (have_posts()) : while (have_posts()) : the_post();
    $center = 'single-center';
    $header = 'single-header';
    //$ava = iro_opt('personal_avatar', '') ? iro_opt('personal_avatar', '') : get_avatar_url(get_the_author_meta('user_email'));
        $edit_this_post_link = get_edit_html();
    $t .= the_title( '<h1 class="entry-title">', '</h1>', false);
    $t .= '<span class="toppic-line"></span><p class="entry-census"><span><a href="'. esc_url(get_author_posts_url(get_the_author_meta('ID'),get_the_author_meta( 'user_nicename' ))) .'">'.get_avatar(get_the_author_meta('ID'),64) .'</a></span><span><a href="'. esc_url(get_author_posts_url(get_the_author_meta('ID'),get_the_author_meta( 'user_nicename' ))) .'">'. get_the_author() .'</a></span><span class="bull">·</span>'. poi_time_since(get_post_time('U', true),false,true) .'<span class="bull">·</span>'. get_post_views(get_the_ID()) .' '._n("View","Views",get_post_views(get_the_ID()),"sakurairo")/*次阅读*/.$edit_this_post_link.'</p>';
    endwhile; endif;
  }elseif(is_page()){
    $full_image_url = !empty($full_image_url) ? $full_image_url[0] : null;
    $t .= the_title( '<h1 class="entry-title">', '</h1>', false);
  }elseif(is_archive()){
    $full_image_url = z_taxonomy_image_url();
    $des = category_description() ? category_description() : ''; // 描述
    $t .= '<h1 class="cat-title">'.single_cat_title('', false).'</h1>';
    $t .= ' <span class="cat-des">'.$des.'</span>';
  }elseif(is_search()){
    $full_image_url = get_random_bg_url();
    $t .= '<h1 class="entry-title search-title"> '.sprintf( __( "Search results for \" %s \"","sakurairo" ), get_search_query()) ./*关于“ '.get_search_query().' ”的搜索结果*/'</h1>';
  }
  if(!iro_opt('patternimg')) $full_image_url = false;
  if(!is_home() && $full_image_url) : ?>
  <div class="pattern-center-blank"></div>
  <div class="pattern-center <?php if(is_single()){echo $center;} ?>">
    <div class="pattern-attachment bg lazyload" style="background-image: url(<?php echo iro_opt('load_out_svg'); ?>)" data-src="<?php echo $full_image_url; ?>"> </div>
    <header class="pattern-header <?php if(is_single()){echo $header;} ?>"><?php echo $t; ?></header>
  </div>
  <?php else :
    echo '<div class="blank"></div>';
  endif;
}

/**
 * 文章封面视频
 * @param isHls 
 */
function the_video_headPattern(bool $isHls = false)
{
    $t = ''; // 标题
    $thubm_image_urls = wp_get_attachment_image_src(get_post_thumbnail_id(get_the_ID()), 'thumbnail');

    $video_cover = get_post_meta(get_the_ID(), 'video_cover', true);
    $video_cover_thumb = get_post_meta(get_the_ID(), 'video_cover_thumb', true);
    // 检查这个字段是否有值
    if (empty($video_cover_thumb)) { //如果值为空，输出默认值
        $video_poster_attr = "";
    } else {
        $video_poster_attr = ' poster="' . $video_cover_thumb . '" ';
    }
    $thubm_image_url = !empty($thubm_image_urls) ? $thubm_image_urls[0] : null;
    if (is_single()) {
        while (have_posts()) {
            the_post();
            $center = 'single-center';
            $header = 'single-header';
            //$ava = iro_opt('personal_avatar', '') ? iro_opt('personal_avatar', '') : get_avatar_url(get_the_author_meta('user_email'));
            $edit_this_post_link = get_edit_html();
            $btn_playControl = '<button id="cv-pc" class="coverVideo-btn" onclick="coverVideo()"><i class="fa fa-pause" aria-hidden="true"></i></button>';
/*             $btn_volumeControl = '<button id="cv-vc" class="coverVideo-btn" onclick="coverVideoMute()"><i class="fa fa-volume-off" aria-hidden="true"></i></button>';
 */            $t .= the_title('<h1 class="entry-title">', $btn_playControl./* $btn_volumeControl. */'</h1>', false);
            $t .= '<p class="entry-census"><span><a href="' 
            . esc_url(get_author_posts_url(get_the_author_meta('ID'), get_the_author_meta('user_nicename'))) . '">' 
            . get_avatar(get_the_author_meta('ID'), 64) . '</a></span><span><a href="' 
            . esc_url(get_author_posts_url(get_the_author_meta('ID'), get_the_author_meta('user_nicename'))) . '">' 
            . get_the_author() . '</a></span><span class="bull">·</span>' 
            . poi_time_since(get_post_time('U', true), false, true) . '<span class="bull">·</span>' 
            . get_post_views(get_the_ID()) . ' ' 
            . _n("View", "Views", get_post_views(get_the_ID()), "sakurairo")/*次阅读*/ . $edit_this_post_link . '</p>';
        }
    } elseif (is_page()) {
        $t .= the_title('<h1 class="entry-title">', '</h1>', false);
    } elseif (is_archive()) {
        $thubm_image_url = iro_opt('load_out_svg');
        $des = category_description() ? category_description() : ''; // 描述
        $t .= '<h1 class="cat-title">' . single_cat_title('', false) . '</h1>';
        $t .= ' <span class="cat-des">' . $des . '</span>';
    } elseif (is_search()) {
        $thubm_image_url = iro_opt('load_out_svg');
        $t .= '<h1 class="entry-title search-title"> ' . sprintf(__("Search results for \" %s \"", "sakurairo"), get_search_query()) ./*关于“ '.get_search_query().' ”的搜索结果*/ '</h1>';
    }
    $thubm_image_url = $thubm_image_url . "#lazyload-blur";
    $thubm_image_url = str_replace(iro_opt('image_cdn'), 'https://cdn.2heng.xin/', $thubm_image_url);
    if (!is_home()) { ?>
        <div class="pattern-center-blank"></div>
        <div class="pattern-center <?php if (is_single()) : echo $center;endif; ?>">
            <div class="pattern-attachment">
                <?php
                if ($isHls) {
                ?>
                    <video loop playsinline muted id="coverVideo" class="hls" <?php echo $video_poster_attr; ?> data-src="<?php echo $video_cover; ?>"></video>
                <?php
                } else {
                ?>
                    <video autoplay loop playsinline muted id="coverVideo" class="normal-cover-video" <?php echo $video_poster_attr; ?>>
                        <source src="<?php echo $video_cover; ?>" type="video/mp4">
                        <?php _e('Your browser does not support HTML5 video.','sakurairo')?>
                    </video>
                <?php
                }
                ?>
            </div>
            <header class="pattern-header <?php if (is_single()) : echo $header;
                                            endif; ?>">
                <?php echo $t; ?>
            </header>
        </div>
<?php } else {
        echo '<div class="blank"></div>';
    }
}


/*
 * 导航栏用户菜单
 */
function header_user_menu(){
  global $current_user;wp_get_current_user(); 
  if(is_user_logged_in()){
    $ava = iro_opt('personal_avatar') ? iro_opt('personal_avatar') : get_avatar_url( $current_user->user_email );
    ?>
    <div class="header-user-avatar">
      <img class="faa-spin animated-hover" src="<?php echo get_avatar_url( $current_user->ID, 64 );/*$ava;*/ ?>" width="30" height="30">
      <div class="header-user-menu">
        <div class="header-user-name">
          <?php _e("Signed in as","sakurairo")?> 
          <div class="header-user-name-u"><?php echo $current_user->display_name; ?></div>
        </div>
        <div class="user-menu-option">
          <?php if (current_user_can('level_10')) { ?>
            <a href="<?php bloginfo('url'); ?>/wp-admin/" target="_blank"><?php _e('Dashboard','sakurairo')/*管理中心*/?></a>
            <a href="<?php bloginfo('url'); ?>/wp-admin/post-new.php" target="_blank"><?php _e('New post','sakurairo')/*撰写文章*/?></a>
          <?php } ?>
          <a href="<?php bloginfo('url'); ?>/wp-admin/profile.php" target="_blank"><?php _e('Profile','sakurairo')/*个人资料*/?></a>
          <a href="<?php echo wp_logout_url(get_bloginfo('url')); ?>" target="_top" data-no-pjax><?php _e('Sign out','sakurairo')/*退出登录*/?></a>
        </div>
      </div>
    </div>
  <?php
  }else{ 
    $ava = iro_opt('unlisted_avatar');
    global $wp;
    //https://wordpress.stackexchange.com/questions/274569/how-to-get-url-of-current-page-displayed
    //可以测试一下对不同的固定链接的兼容性
    $login_url = iro_opt('exlogin_url') ? iro_opt('exlogin_url') : wp_login_url(add_query_arg( $wp->query_vars, home_url( $wp->request ) ));
  ?>
  <div class="header-user-avatar">
    <a href="<?php echo $login_url; ?>">
      <img class="faa-shake animated-hover" src="<?php echo $ava; ?>" width="30" height="30">
    </a>
    <div class="header-user-menu">
 <div class="header-user-name no-logged">  
   <a id="login-link" href="<?php echo $login_url; ?>" data-no-pjax style="font-weight:bold;text-decoration:none"><?php _e('Log in','sakurairo')/*登录*/?></a>  
      </div>
    </div>
  </div>
  <?php 
  }
}


/*
 * 获取相邻文章缩略图
 * 特色图 -> 文章图 -> 首页图
 */
// 上一篇
function get_prev_thumbnail_url() { 
  $prev_post = get_previous_post(); 
  if (!$prev_post) {
    return get_random_bg_url(); // 首页图
  } else if ( has_post_thumbnail($prev_post->ID) ) { 
    $img_src = wp_get_attachment_image_src( get_post_thumbnail_id( $prev_post->ID ), 'large'); 
    return $img_src[0] ?? null; // 特色图
  } 
  else { 
    $content = $prev_post->post_content; 
    preg_match_all('/<img.*?(?: |\\t|\\r|\\n)?src=[\'"]?(.+?)[\'"]?(?:(?: |\\t|\\r|\\n)+.*?)?>/sim', $content, $strResult, PREG_PATTERN_ORDER); 
    $n = count($strResult[1]); 
    if($n > 0){ 
      return $strResult[1][0];  // 文章图
    }else{
      return get_random_bg_url(); // 首页图
    } 
  } 
}

// 下一篇
function get_next_thumbnail_url() { 
  $next_post = get_next_post(); 
  if( $next_post instanceof WP_Post){
     if ( has_post_thumbnail($next_post->ID) ) { 
    $img_src = wp_get_attachment_image_src( get_post_thumbnail_id( $next_post->ID ), 'large'); 
    return $img_src[0] ?? null; 
  } 
  else { 
    $content = $next_post->post_content; 
    preg_match_all('/<img.*?(?: |\\t|\\r|\\n)?src=[\'"]?(.+?)[\'"]?(?:(?: |\\t|\\r|\\n)+.*?)?>/sim', $content, $strResult, PREG_PATTERN_ORDER); 
    $n = count($strResult[1]); 
    if($n > 0){ 
      return $strResult[1][0];   
    }else{
      return get_random_bg_url();
    } 
  } 
  }
}

/**
 * 文章摘要
 */
function changes_post_excerpt_more( $more ) {
    return ' ...';
}
function changes_post_excerpt_length( $length ) {
    return 65;
}
add_filter( 'excerpt_more', 'changes_post_excerpt_more' );
add_filter( 'excerpt_length', 'changes_post_excerpt_length', 999 );


/*
 * SEO优化
 */
// 外部链接自动加nofollow
add_filter( 'the_content', 'siren_auto_link_nofollow');
function siren_auto_link_nofollow( $content ) {
  $regexp = "<a\s[^>]*href=(\"??)([^\" >]*?)\\1[^>]*>";
  if(preg_match_all("/$regexp/siU", $content, $matches, PREG_SET_ORDER)) {
    if( !empty($matches) ) {
      $srcUrl = get_option('siteurl');
      foreach($matches as $result){
        $tag = $result[0];
        $tag2 = $result[0];
        $url = $result[0];
        $noFollow = '';
        $pattern = '/target\s*=\s*"\s*_blank\s*"/';
        preg_match($pattern, $tag2, $match, PREG_OFFSET_CAPTURE);
        if( count($match) < 1 )
            $noFollow .= ' target="_blank" ';
        $pattern = '/rel\s*=\s*"\s*[n|d]ofollow\s*"/';
        preg_match($pattern, $tag2, $match, PREG_OFFSET_CAPTURE);
        if( count($match) < 1 )
            $noFollow .= ' rel="nofollow" ';
        $pos = strpos($url,$srcUrl);
        if ($pos === false) {
            $tag = rtrim ($tag,'>');
            $tag .= $noFollow.'>';
            $content = str_replace($tag2,$tag,$content);
        }
      }
    }
  }
   
  $content = str_replace(']]>', ']]>', $content);
  return $content;
}

// 图片自动加标题
add_filter('the_content', 'siren_auto_images_alt');
function siren_auto_images_alt($content) {
  global $post;
  $pattern ="/<a(.*?)href=('|\")(.*?).(bmp|gif|jpeg|jpg|png)('|\")(.*?)>/i";
  $replacement = '<a$1href=$2$3.$4$5 alt="'.$post->post_title.'" title="'.$post->post_title.'"$6>';
  $content = preg_replace($pattern, $replacement, $content);
  return $content;
}

// 分类页面全部添加斜杠，利于SEO
function siren_nice_trailingslashit($string, $type_of_url) {
    if ( $type_of_url != 'single' )
      $string = trailingslashit($string);
    return $string;
}
add_filter('user_trailingslashit', 'siren_nice_trailingslashit', 10, 2);


// 去除链接显示categroy
add_action( 'load-themes.php',  'no_category_base_refresh_rules');
add_action('created_category', 'no_category_base_refresh_rules');
add_action('edited_category', 'no_category_base_refresh_rules');
add_action('delete_category', 'no_category_base_refresh_rules');
function no_category_base_refresh_rules() {
  global $wp_rewrite;
  $wp_rewrite -> flush_rules();
}
 
// Remove category base
add_action('init', 'no_category_base_permastruct');
function no_category_base_permastruct() {
  global $wp_rewrite, $wp_version;
  if (version_compare($wp_version, '3.4', '<')) {
    
  } else {
    $wp_rewrite -> extra_permastructs['category']['struct'] = '%category%';
  }
}
// Add our custom category rewrite rules
add_filter('category_rewrite_rules', 'no_category_base_rewrite_rules');
function no_category_base_rewrite_rules($category_rewrite) {
  //var_dump($category_rewrite); // For Debugging
  $category_rewrite = array();
  $categories = get_categories(array('hide_empty' => false));
  foreach ($categories as $category) {
    $category_nicename = $category -> slug;
    if ($category -> parent == $category -> cat_ID)// recursive recursion
      $category -> parent = 0;
    elseif ($category -> parent != 0)
      $category_nicename = get_category_parents($category -> parent, false, '/', true) . $category_nicename;
    $category_rewrite['(' . $category_nicename . ')/(?:feed/)?(feed|rdf|rss|rss2|atom)/?$'] = 'index.php?category_name=$matches[1]&feed=$matches[2]';
    $category_rewrite['(' . $category_nicename . ')/page/?([0-9]{1,})/?$'] = 'index.php?category_name=$matches[1]&paged=$matches[2]';
    $category_rewrite['(' . $category_nicename . ')/?$'] = 'index.php?category_name=$matches[1]';
  }
  // Redirect support from Old Category Base
  global $wp_rewrite;
  $old_category_base = get_option('category_base') ? get_option('category_base') : 'category';
  $old_category_base = trim($old_category_base, '/');
  $category_rewrite[$old_category_base . '/(.*)$'] = 'index.php?category_redirect=$matches[1]';
 
  //var_dump($category_rewrite); // For Debugging
  return $category_rewrite;
}
 
// Add 'category_redirect' query variable
add_filter('query_vars', 'no_category_base_query_vars');
function no_category_base_query_vars($public_query_vars) {
  $public_query_vars[] = 'category_redirect';
  return $public_query_vars;
}
 
// Redirect if 'category_redirect' is set
add_filter('request', 'no_category_base_request');
function no_category_base_request($query_vars) {
  //print_r($query_vars); // For Debugging
  if (isset($query_vars['category_redirect'])) {
    $catlink = trailingslashit(get_option('home')) . user_trailingslashit($query_vars['category_redirect'], 'category');
    status_header(301);
    header("Location: $catlink");
    exit();
  }
  return $query_vars;
}
// 去除链接显示categroy END ~


/**
 * 更改作者页链接为昵称显示
 */
// Replace the user name using the nickname, query by user ID
add_filter( 'request', 'siren_request' );
function siren_request( $query_vars ){
    if ( array_key_exists( 'author_name', $query_vars ) ) {
        global $wpdb;
        $author_id = $wpdb->get_var( $wpdb->prepare( "SELECT user_id FROM {$wpdb->usermeta} WHERE meta_key='nickname' AND meta_value = %s", $query_vars['author_name'] ) );
        if ( $author_id ) {
            $query_vars['author'] = $author_id;
            unset( $query_vars['author_name'] );    
        }
    }
    return $query_vars;
}

/*
 * 私密评论
 * @bigfa
 */
function siren_private_message_hook($comment_content , $comment){
    $comment_ID = $comment->comment_ID;
    $parent_ID = $comment->comment_parent;
    $parent_email = get_comment_author_email($parent_ID);
    $is_private = get_comment_meta($comment_ID,'_private',true);
    $email = $comment->comment_author_email;
    $current_commenter = wp_get_current_commenter();
    if ( $is_private ) $comment_content = '#私密# ' . $comment_content;
    if ( $current_commenter['comment_author_email'] == $email || $parent_email == $current_commenter['comment_author_email'] || current_user_can('delete_user') ) return $comment_content;
    if ( $is_private ) return '<i class="fa fa-lock" aria-hidden="true"></i> '.__("The comment is private","sakurairo")/*该评论为私密评论*/;
    return $comment_content;
}
add_filter('get_comment_text','siren_private_message_hook',10,2);

function siren_mark_private_message($comment_id){
    if ( isset($_POST['is-private']) ) {
        update_comment_meta($comment_id,'_private','true');
    }
}
add_action('comment_post', 'siren_mark_private_message');


/*
 * 删除后台某些版权和链接
 * @wpdx
 */
add_filter('admin_title', 'wpdx_custom_admin_title', 10, 2);
function wpdx_custom_admin_title($admin_title, $title){
    return $title.' &lsaquo; '.get_bloginfo('name');
}
//去掉Wordpress LOGO
function remove_logo($wp_toolbar) {
    $wp_toolbar->remove_node('wp-logo');
}
add_action('admin_bar_menu', 'remove_logo', 999);

//去掉Wordpress 底部版权
function change_footer_admin () {return '';}  
add_filter('admin_footer_text', 'change_footer_admin', 9999);  
function change_footer_version() {return '';}  
add_filter( 'update_footer', 'change_footer_version', 9999);

//去掉Wordpres挂件
function disable_dashboard_widgets() {   
    //remove_meta_box('dashboard_recent_comments', 'dashboard', 'normal');//近期评论 
    //remove_meta_box('dashboard_recent_drafts', 'dashboard', 'normal');//近期草稿
    remove_meta_box('dashboard_primary', 'dashboard', 'core');//wordpress博客  
    remove_meta_box('dashboard_secondary', 'dashboard', 'core');//wordpress其它新闻  
    remove_meta_box('dashboard_right_now', 'dashboard', 'core');//wordpress概况  
    //remove_meta_box('dashboard_incoming_links', 'dashboard', 'core');//wordresss链入链接  
    //remove_meta_box('dashboard_plugins', 'dashboard', 'core');//wordpress链入插件  
    //remove_meta_box('dashboard_quick_press', 'dashboard', 'core');//wordpress快速发布   
}  
add_action('admin_menu', 'disable_dashboard_widgets');


/**
 * 获取用户UA信息
 */
// 浏览器信息
function siren_get_browsers(string $ua):array{
  $title = 'Unknow';
  $icon = 'unknown';
  if (strpos($ua, 'Chrome')){
    if (strpos($ua, 'Edg') && preg_match('#Edg/([0-9]+)#i', $ua, $matches)){
      $title = 'Edge '. $matches[1];
      $icon = 'edge';
    }elseif (strpos($ua, '360EE')) {
      $title = '360 Browser ';
      $icon = '360se';
    }elseif (strpos($ua, 'OPR') && preg_match('#OPR/([0-9]+)#i', $ua, $matches)) {
      $title = 'Opera '. $matches[1];
      $icon = 'opera';
    }elseif (preg_match('#Chrome/([0-9]+)#i', $ua, $matches)) {
      $title = 'Chrome '. $matches[1];
      $icon = 'chrome';
    }
  }elseif (strpos($ua, 'Firefox') && preg_match('#Firefox/([0-9]+)#i', $ua, $matches)){
    $title = 'Firefox '. $matches[1];
    $icon = 'firefox';
  }elseif (strpos($ua, 'Safari') && preg_match('#Safari/([0-9]+)#i', $ua, $matches)){
    $title = 'Safari '. $matches[1];
    $icon = 'safari';
  }
  
  return [
    'title' => $title,
    'icon' => $icon
  ];
}

// 操作系统信息
function siren_get_os(string $ua):array{
  $title = 'Unknow';
  $icon = 'unknown';
  // UA样式决定strpos返回值不可能为0 所以不需要考虑为0的情况
  if (strpos($ua, 'Win')) {
    if (strpos($ua, 'Windows NT 10.0')){
      $title = "Windows 10/11";
      $icon = "win10-11";
    }elseif (strpos($ua, 'Windows NT 6.1')) {
      $title = "Windows 7";
      $icon = "win7";
    }elseif (strpos($ua, 'Windows NT 6.2')) {
      $title = "Windows 8";
      $icon = "win8";
    }elseif (strpos($ua, 'Windows NT 6.3')) {
      $title = "Windows 8.1";
      $icon = "win8";
    }
  }elseif (strpos($ua, 'iPhone OS') && preg_match('#iPhone OS ([0-9]+)#i', $ua, $matches)) {// 1.2 修改成 iphone os 来判断 
    $title = "iOS ".$matches[1];
    $icon = "apple";
  }elseif (strpos($ua, 'Android') && preg_match('/Android.([0-9. _]+)/i', $ua, $matches)) {
    if(count(explode(7,$matches[1]))>1) $matches[1] = 'Lion '.$matches[1];
    elseif(count(explode(8,$matches[1]))>1) $matches[1] = 'Mountain Lion '.$matches[1];
    $title= $matches[0];
    $icon = "android";
  }elseif (strpos($ua, 'Mac OS') && preg_match('/Mac OS X.([\d. _]+)/i', $ua, $matches)) {
    $mac_ver =  intval(explode('_',$matches[1])[1]);
    $mac_code_name = '';
    $has_x = $mac_ver <12;
    $mac_code_list = ['Cheetah','Puma','Jaguar','Panther','Tiger','Leopard','Snow Leopard','Lion','Mountain Lion','Mavericks','Yosemite','El Capitan','Sierra','High Sierra','Mojave','Catalina or Higher'];    // 总16个,后续请在最后添加并且修改该条注释.
    if (isset($mac_code_list[$mac_ver])) {
      $mac_code_name = $mac_code_list[$mac_ver];
    }
    $matches[1] = $mac_code_name.' '.$matches[1];
    $title = 'macOS '.($has_x?'X':''.' ').str_replace('_','.',$matches[1]);
    $icon = "apple";
  }elseif (strpos($ua, 'Macintosh')) {
    $title = "macOS";
    $icon = "apple";
  }elseif (strpos($ua, 'Linux')) {
    $title = 'Linux';
    $icon = 'linux';
  }
  return [
    'title' => $title,
    'icon' => $icon
  ];
}

function siren_get_useragent(string $ua):string{
  if(iro_opt('comment_useragent')){
    // $imgurl = get_bloginfo('template_directory') . '/images/ua/';
    $imgurl = iro_opt('vision_resource_basepath').'ua/';
    $browser = siren_get_browsers($ua);
    $os = siren_get_os($ua);
    return '&nbsp;&nbsp;<span class="useragent-info">( <img src="'. $imgurl.$browser['icon'] .'.svg">&nbsp;'. $browser['title'] .'&nbsp;&nbsp;<img src="'. $imgurl.$os['icon'] .'.svg">&nbsp;'. $os['title'] .' )</span>';
  }
  return '';
}

// UA 显示移动定制
function mobile_get_useragent_icon(string $ua):string{
  if(iro_opt('comment_useragent')){
    $imgurl = iro_opt('vision_resource_basepath').'ua/';
    $browser = siren_get_browsers($ua);
    $os = siren_get_os($ua);
    return '<span class="useragent-info-m">( <img src="'. $imgurl.$browser['icon'] .'.svg">&nbsp;&nbsp;<img src="'. $imgurl.$os['icon'] .'.svg"> )</span>';
  }
  return '';
}

/*
 * 打赏
 */
 function the_reward(){
  $alipay = iro_opt('alipay_code');
  $wechat = iro_opt('wechat_code');
  if($alipay || $wechat){
  $alipay =  $alipay ? '<li class="alipay-code"><img src="'.$alipay.'"></li>' : '';
  $wechat = $wechat ? '<li class="wechat-code"><img src="'.$wechat.'"></li>' : '';
  ?>
  <div class="single-reward">
    <div class="reward-open"> <i class="fa fa-usd" aria-hidden="true"></i>
      <div class="reward-main">
        <ul class="reward-row">
          <?php echo $alipay.$wechat; ?>
        </ul>
      </div>
    </div>
  </div>
  <?php
  }
}
