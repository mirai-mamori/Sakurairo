/*！
 * Sakura theme application bundle
 * @author Mashiro
 * @url https://2heng.xin
 * @date 2019.8.3
 */
mashiro_global.variables = new function () {
    this.has_hls = false;
    this.skinSecter = true;
}
mashiro_global.ini = new function () {
    this.normalize = function () { // initial functions when page first load (首次加载页面时的初始化函数)
        lazyload();
        social_share();
        post_list_show_animation();
        copy_code_block();
        web_audio();
        coverVideoIni();
        checkskinSecter();
        scrollBar();
        load_bangumi();
    }
    this.pjax = function () { // pjax reload functions (pjax 重载函数)
        pjaxInit();
        social_share();
        post_list_show_animation();
        copy_code_block();
        web_audio();
        coverVideoIni();
        checkskinSecter();
        load_bangumi();
    }
}

function setCookie(name, value, days) {
    var expires = "";
    if (days) {
        var date = new Date();
        date.setTime(date.getTime() + (days * 24 * 60 * 60 * 1000));
        expires = "; expires=" + date.toUTCString();
    }
    document.cookie = name + mashiro_option.cookie_version_control + "=" + (value || "") + expires + "; path=/";
}

function getCookie(name) {
    var nameEQ = name + mashiro_option.cookie_version_control + "=";
    var ca = document.cookie.split(';');
    for (var i = 0; i < ca.length; i++) {
        var c = ca[i];
        while (c.charAt(0) == ' ') c = c.substring(1, c.length);
        if (c.indexOf(nameEQ) == 0) return c.substring(nameEQ.length, c.length);
    }
    return null;
}

function removeCookie(name) {
    document.cookie = name + mashiro_option.cookie_version_control + '=; Max-Age=-99999999;';
}

function imgError(ele, type) {
    switch (type) {
        case 1:
            ele.src = 'https://view.moezx.cc/images/2017/12/30/Transparent_Akkarin.th.jpg';
            break;
        case 2:
            ele.src = 'https://gravatar.shino.cc/avatar/?s=80&d=mm&r=g';
            break;
        default:
            ele.src = 'https://view.moezx.cc/images/2018/05/13/image-404.png';
    }
}

function post_list_show_animation() {
    if ($("article").hasClass("post-list-thumb")) {
        var options = {
            root: null,
            threshold: [0.66]
        }
        var io = new IntersectionObserver(callback, options);
        var articles = document.querySelectorAll('.post-list-thumb');

        function callback(entries) {
            entries.forEach((article) => {
                if (!window.IntersectionObserver) {
                    article.target.style.willChange = 'auto';
                    if( article.target.classList.contains("post-list-show") === false){
                        article.target.classList.add("post-list-show");
                    }
                } else {
                    if (article.target.classList.contains("post-list-show")) {
                        article.target.style.willChange = 'auto';
                        io.unobserve(article.target)
                    } else {
                        if (article.isIntersecting) {
                            article.target.classList.add("post-list-show");
                            article.target.style.willChange = 'auto';
                            io.unobserve(article.target)
                        }
                    }
                }
            })
        }
        articles.forEach((article) => {
            io.observe(article)
        })
    }
}
mashiro_global.font_control = new function () {
    this.change_font = function () {
        if ($("body").hasClass("serif")) {
            $("body").removeClass("serif");
            $(".control-btn-serif").removeClass("selected");
            $(".control-btn-sans-serif").addClass("selected");
            setCookie("font_family", "sans-serif", 30);
        } else {
            $("body").addClass("serif");
            $(".control-btn-serif").addClass("selected");
            $(".control-btn-sans-serif").removeClass("selected");
            setCookie("font_family", "serif", 30);
            if (document.body.clientWidth <= 860) {
                addComment.createButterbar("将从网络加载字体，流量请注意");
            }
        }
    }
    this.ini = function () {
        if (document.body.clientWidth > 860) {
            if (!getCookie("font_family") || getCookie("font_family") == "serif")
                $("body").addClass("serif");
        }
        if (getCookie("font_family") == "sans-serif") {
            $("body").removeClass("sans-serif");
            $(".control-btn-serif").removeClass("selected");
            $(".control-btn-sans-serif").addClass("selected");
        }
    }
}
mashiro_global.font_control.ini();

function social_share_limit(){
    
    if ($(".top-social").length > 0 || $(".top-social_v2").length > 0){
        $(".top-social").length > 0 ? a = $(".top-social li") : a = $(".top-social_v2 li");
        for (var i=a.length-2;i>=11;i--){
            a[i].remove();
        }
        if(document.body.clientWidth<=860){
            for (var i=a.length-2;i>=10;i--){
                a[i].remove();
            }
        }
        if(document.body.clientWidth<=425){
            for (var i=a.length-2;i>=5;i--){
                a[i].remove();
            }
            num = 1;
        }
    }
    }
    social_share_limit();


function code_highlight_style() {
    function gen_top_bar(i) {
        var attributes = {
            'autocomplete': 'off',
            'autocorrect': 'off',
            'autocapitalize': 'off',
            'spellcheck': 'false',
            'contenteditable': 'false',
            'design': 'by Mashiro'
        }
        var ele_name = $('pre:eq(' + i + ')')[0].children[0].className;
        var lang = ele_name.substr(0, ele_name.indexOf(" ")).replace('language-', '');
        if (lang.toLowerCase() == "hljs") var lang = "text";
        $('pre:eq(' + i + ')').addClass('highlight-wrap');
        for (var t in attributes) {
            $('pre:eq(' + i + ')').attr(t, attributes[t]);
        }
        $('pre:eq(' + i + ') code').attr('data-rel', lang.toUpperCase());
    }
    $('pre code').each(function (i, block) {
        hljs.highlightBlock(block);
    });
    for (var i = 0; i < $('pre').length; i++) {
        gen_top_bar(i);
    }
    hljs.initLineNumbersOnLoad();
    $('pre').on('click', function (e) {
        if (e.target !== this) return;
        $(this).toggleClass('code-block-fullscreen');
        $('html').toggleClass('code-block-fullscreen-html-scroll');
    });
}
try {
    code_highlight_style();
} catch (e) {}

if (Poi.reply_link_version == 'new') {
    $('body').on('click', '.comment-reply-link', function () {
        addComment.moveForm("comment-" + $(this).attr('data-commentid'), $(this).attr('data-commentid'), "respond", $(this).attr('data-postid'));
        return false;
    });
}

function attach_image() {
    var cached = $('.insert-image-tips');
    $('#upload-img-file').change(function () {
        if (this.files.length > 10) {
            addComment.createButterbar("每次上传上限为10张.<br>10 files max per request.");
            return 0;
        }
        for (i = 0; i < this.files.length; i++) {
            if (this.files[i].size >= 5242880) {
                alert('图片上传大小限制为5 MB.\n5 MB max per file.\n\n「' + this.files[i].name + '」\n\n这张图太大啦~\nThis image is too large~');
            }
        }
        for (var i = 0; i < this.files.length; i++) {
            var f = this.files[i];
            var formData = new FormData();
            formData.append('cmt_img_file', f);
            $.ajax({
                url: Poi.api + 'sakura/v1/image/upload?_wpnonce=' + Poi.nonce,
                type: 'POST',
                processData: false,
                contentType: false,
                data: formData,
                beforeSend: function (xhr) {
                    cached.html('<i class="fa fa-spinner rotating" aria-hidden="true"></i>');
                    addComment.createButterbar("上传中...<br>Uploading...");
                },
                success: function (res) {
                    cached.html('<i class="fa fa-check" aria-hidden="true"></i>');
                    setTimeout(function () {
                        cached.html('<i class="fa fa-picture-o" aria-hidden="true"></i>');
                    }, 1000);
                    if (res.status == 200) {
                        var get_the_url = res.proxy;
                        $('#upload-img-show').append('<img class="lazyload upload-image-preview" src="https://cdn.jsdelivr.net/gh/mirai-mamori/web-img/loadimg/inload.svg" data-src="' + get_the_url + '" onclick="window.open(\'' + get_the_url + '\')" onerror="imgError(this)" />');
                        lazyload();
                        addComment.createButterbar("图片上传成功~<br>Uploaded successfully~");
                        grin(get_the_url, type = 'Img');
                    } else {
                        addComment.createButterbar("上传失败！<br>Uploaded failed!<br> 文件名/Filename: "+f.name+"<br>code: "+res.status+"<br>"+res.message, 3000);
                    }
                },
                error: function (jqXHR, textStatus, errorThrown) {
                    cached.html('<i class="fa fa-times" aria-hidden="true" style="color:red"></i>');
                    alert("上传失败，请重试.\nUpload failed, please try again.");
                    setTimeout(function () {
                        cached.html('<i class="fa fa-picture-o" aria-hidden="true"></i>');
                    }, 1000);
                    // console.info(jqXHR.responseText);
                    // console.info(jqXHR.status);
                    // console.info(jqXHR.readyState);
                    // console.info(jqXHR.statusText);
                    // console.info(textStatus);
                    // console.info(errorThrown);
                }
            })
        }
    });
}

function clean_upload_images() {
    $('#upload-img-show').html('');
}

function add_upload_tips() {
    $('<div class="insert-image-tips popup"><i class="fa fa-picture-o" aria-hidden="true"></i><span class="insert-img-popuptext" id="uploadTipPopup">上传图片</span></div><input id="upload-img-file" type="file" accept="image/*" multiple="multiple" class="insert-image-button">').insertAfter($(".form-submit #submit"));
    attach_image();
    $("#upload-img-file").hover(function () {
        $(".insert-image-tips").addClass("insert-image-tips-hover");
        $("#uploadTipPopup").addClass("show");
    }, function () {
        $(".insert-image-tips").removeClass("insert-image-tips-hover");
        $("#uploadTipPopup").removeClass("show");
    });
}

function click_to_view_image() {
    $(".comment_inline_img").click(function () {
        var temp_url = this.src;
        window.open(temp_url);
    });
}
click_to_view_image();

function original_emoji_click() {
    $(".emoji-item").click(function () {
        grin($(this).text(), type = "custom", before = "`", after = "` ");
    });
}
original_emoji_click();

function showPopup(ele) {
    var popup = ele.querySelector("#thePopup");
    popup.classList.toggle("show");
}

function cmt_showPopup(ele) {
    var popup = $(ele).find("#thePopup");
    popup.addClass("show");
    $(ele).find("input").blur(function () {
        popup.removeClass("show");
    });
}

function scrollBar() {
    if (document.body.clientWidth > 860) {
        $(window).scroll(function () {
            var s = $(window).scrollTop(),
                a = $(document).height(),
                b = $(window).height(),
                result = parseInt(s / (a - b) * 100),
                cached = $("#bar");
            cached.css("width", result + "%");
            if (false) {
                if (result >= 0 && result <= 19)
                    cached.css("background", "#cccccc");
                if (result >= 20 && result <= 39)
                    cached.css("background", "#50bcb6");
                if (result >= 40 && result <= 59)
                    cached.css("background", "#85c440");
                if (result >= 60 && result <= 79)
                    cached.css("background", "#f2b63c");
                if (result >= 80 && result <= 99)
                    cached.css("background", "#FF0000");
                if (result == 100)
                    cached.css("background", "#5aaadb");
            } else {
                cached.css("background", "orange");
            }
            $(".toc-container").css("height", $(".site-content").outerHeight());
            $(".skin-menu").removeClass('show');
        });
    }
}

function checkskinSecter() {
    if (mashiro_global.variables.skinSecter === false) {
        $(".pattern-center").removeClass('pattern-center').addClass('pattern-center-sakura');
        $(".headertop-bar").removeClass('headertop-bar').addClass('headertop-bar-sakura');
    } else {
        $(".pattern-center-sakura").removeClass('pattern-center-sakura').addClass('pattern-center');
        $(".headertop-bar-sakura").removeClass('headertop-bar-sakura').addClass('headertop-bar');
    }
}

function checkBgImgCookie() {
    var bgurl = getCookie("bgImgSetting");
    if (!bgurl) {
        $("#white-bg").click();
    } else {
        $("#" + bgurl).click();
    }
}
function checkDarkModeCookie() {
    var dark = getCookie("dark"),
        today = new Date()
        cWidth = document.body.clientWidth;
    if (!dark) {
        if ((today.getHours() > 21 || today.getHours() < 7) && mashiro_option.darkmode) {
            setTimeout(function () {
                $("#dark-bg").click();
            }, 100);
            console.log('夜间模式开启');
        } else {
            if (cWidth > 860) {
                setTimeout(function () {
                    checkBgImgCookie();
                }, 100);
                console.log('夜间模式关闭');
            } else {
                $("html").css("background", "unset");
                $("body").removeClass("dark");
                $("#moblieDarkLight").html('<i class="fa fa-moon-o" aria-hidden="true"></i>');
                setCookie("dark", "0", 0.33);
            }
        }
    } else {
        if (dark == '1' && (today.getHours() >= 22 || today.getHours() <= 6) && mashiro_option.darkmode) {
            setTimeout(function () {
                $("#dark-bg").click();
            }, 100);
            console.log('夜间模式开启');
        } else if (dark == '0' || today.getHours() < 22 || today.getHours() > 6) {
            if (cWidth > 860) {
                setTimeout(function () {
                    checkBgImgCookie();
                }, 100);
                console.log('夜间模式关闭');
            } else {
                $("html").css("background", "unset");
                $("body").removeClass("dark");
                $("#moblieDarkLight").html('<i class="fa fa-moon-o" aria-hidden="true"></i>');
                setCookie("dark", "0", 0.33);
            }
        }
    }
}
if (!getCookie("darkcache") && (new Date().getHours() > 21 || new Date().getHours() < 7)) {
    removeCookie("dark");
    setCookie("darkcache", "cached", 0.4);
}
setTimeout(function() {
    checkDarkModeCookie();
}, 100);

function mobile_dark_light() {
    if ($("body").hasClass("dark")) {
        $("html").css("background", "unset");
        $("body").removeClass("dark");
        $("#moblieDarkLight").html('<i class="fa fa-moon-o" aria-hidden="true"></i>');
        setCookie("dark", "0", 0.33);
    } else {
        $("html").css("background", "#31363b");
        $("#moblieDarkLight").html('<i class="fa fa-sun-o" aria-hidden="true"></i>');
        $("body").addClass("dark");
        setCookie("dark", "1", 0.33);
    }
}

function no_right_click() {
    $('.post-thumb img').bind('contextmenu', function (e) {
        return false;
    });
}
no_right_click();
$(document).ready(function () {
    function checkskin_bg(a) {
        return a == "none" ? "" : a
    }

    function changeBG() {
        var cached = $(".menu-list");
        cached.find("li").each(function () {
            var tagid = this.id;
            cached.on("click", "#" + tagid, function () {
                if (tagid == "white-bg" || tagid == "dark-bg") {
                    mashiro_global.variables.skinSecter = true;
                    checkskinSecter();
                } else {
                    mashiro_global.variables.skinSecter = false;
                    checkskinSecter();
                }
                if (tagid == "dark-bg") {
                    $("html").css("background", "#31363b");
                    $(".site-content").css("background-color", "#fff");
                    $("body").addClass("dark");
                    setCookie("dark", "1", 0.33);
                } else{
                    $("html").css("background", "unset");
                    $("body").removeClass("dark");
                    $(".site-content").css("background-color", "rgba(255, 255, 255, .8)");
                    setCookie("dark", "0", 0.33);
                    setCookie("bgImgSetting", tagid, 30);
                }
                switch (tagid) {
                    case "white-bg":
                        $("body").css("background-image", "url(" + checkskin_bg(mashiro_option.skin_bg0) + ")");
                        $(".site-content").css("background-color", "#fff");
                        break;
                    case "sakura-bg":
                        $("body").css("background-image", "url(" + checkskin_bg(mashiro_option.skin_bg1) + ")");
                        $(".site-content").css("background-color", "rgba(255, 255, 255, .8)");
                        break;
                    case "gribs-bg":
                        $("body").css("background-image", "url(" + checkskin_bg(mashiro_option.skin_bg2) + ")");
                        $(".site-content").css("background-color", "rgba(255, 255, 255, .8)");
                        break;
                    case "pixiv-bg":
                        $("body").css("background-image", "url(" + checkskin_bg(mashiro_option.skin_bg3) + ")");
                        $(".site-content").css("background-color", "rgba(255, 255, 255, .8)");
                        break;
                    case "KAdots-bg":
                        $("body").css("background-image", "url(" + checkskin_bg(mashiro_option.skin_bg4) + ")");
                        $(".site-content").css("background-color", "rgba(255, 255, 255, .8)");
                        break;
                    case "totem-bg":
                        $("body").css("background-image", "url(" + checkskin_bg(mashiro_option.skin_bg5) + ")");
                        $(".site-content").css("background-color", "rgba(255, 255, 255, .8)");
                        break;
                    case "bing-bg":
                        $("body").css("background-image", "url(" + checkskin_bg(mashiro_option.skin_bg6) + ")");
                        $(".site-content").css("background-color", "rgba(255, 255, 255, .8)");
                        break;
                    // case "dark-bg":
                    //     $("body").css("background-image", "url(" + checkskin_bg(mashiro_option.skin_bg7) + ")");
                    //     break;
                }
                closeSkinMenu();
            });
        });
    }
    changeBG();

    function closeSkinMenu() {
        $(".skin-menu").removeClass('show');
        setTimeout(function () {
            $(".changeSkin-gear").css("visibility", "visible");
        }, 300);
    }
    $("#changskin").click(function () {
        $(".skin-menu").toggleClass('show');
    }) 
    $(".skin-menu #close-skinMenu").click(function () {
        closeSkinMenu();
    });
    add_upload_tips();
});
var bgn = 1;

function nextBG() {
    $(".centerbg").css("background-image", "url(" + mashiro_option.cover_api + "?" + bgn + ")");
    bgn = bgn + 1;
}

function preBG() {
    bgn = bgn - 1;
    $(".centerbg").css("background-image", "url(" + mashiro_option.cover_api + "?" + bgn + ")");
}
$(document).ready(function () {
    $("#bg-next").click(function () {
        nextBG();
    });
    $("#bg-pre").click(function () {
        preBG();
    });
});

function topFunction() {
    $('body,html').animate({
        scrollTop: 0
    })
}

function timeSeriesReload(flag) {
    var cached = $('#archives');
    if (flag == true) {
        cached.find('span.al_mon').click(function () {
            $(this).next().slideToggle(400);
            return false;
        });
        lazyload();
    } else {
        (function () {
            $('#al_expand_collapse,#archives span.al_mon').css({
                cursor: "s-resize"
            });
            cached.find('span.al_mon').each(function () {
                var num = $(this).next().children('li').length;
                $(this).children('#post-num').text(num);
            });
            var $al_post_list = cached.find('ul.al_post_list'),
                $al_post_list_f = cached.find('ul.al_post_list:first');
            $al_post_list.hide(1, function () {
                $al_post_list_f.show();
            });
            cached.find('span.al_mon').click(function () {
                $(this).next().slideToggle(400);
                return false;
            });
            if (document.body.clientWidth > 860) {
                cached.find('li.al_li').mouseover(function () {
                    $(this).children('.al_post_list').show(400);
                    return false;
                });
                if (false) {
                    cached.find('li.al_li').mouseout(function () {
                        $(this).children('.al_post_list').hide(400);
                        return false;
                    });
                }
            }
            var al_expand_collapse_click = 0;
            $('#al_expand_collapse').click(function () {
                if (al_expand_collapse_click == 0) {
                    $al_post_list.each(function(index){
                        var $this = $(this),
                        s = setTimeout(function() {
                            $this.show(400);
                        }, 50 * index);
                    });
                    al_expand_collapse_click++;
                } else if (al_expand_collapse_click == 1) {
                    $al_post_list.each(function(index){
                        var $this = $(this),
                        h = setTimeout(function() {
                            $this.hide(400);
                        }, 50 * index);
                    });
                    al_expand_collapse_click--;
                }
            });
        })();
    }
}
timeSeriesReload();

/*视频feature*/
function coverVideo() {
    var video = addComment.I("coverVideo");
    var btn = addComment.I("coverVideo-btn");

    if (video.paused) {
        video.play();
        try {
            btn.innerHTML = '<i class="fa fa-pause" aria-hidden="true"></i>';
        } catch (e) {};
        //console.info('play:coverVideo()');
    } else {
        video.pause();
        try {
            btn.innerHTML = '<i class="fa fa-play" aria-hidden="true"></i>';
        } catch (e) {};
        //console.info('pause:coverVideo()');
    }
}

function killCoverVideo() {
    var video = addComment.I("coverVideo");
    var btn = addComment.I("coverVideo-btn");

    if (video.paused) {
        //console.info('none:killCoverVideo()');
    } else {
        video.pause();
        try {
            btn.innerHTML = '<i class="fa fa-play" aria-hidden="true"></i>';
        } catch (e) {};
        //console.info('pause:killCoverVideo()');
    }
}

function loadHls(){
    var video = addComment.I('coverVideo');
    var video_src = $('#coverVideo').attr('data-src');
    if (Hls.isSupported()) {
        var hls = new Hls();
        hls.loadSource(video_src);
        hls.attachMedia(video);
        hls.on(Hls.Events.MANIFEST_PARSED, function () {
            video.play();
        });
    } else if (video.canPlayType('application/vnd.apple.mpegurl')) {
        video.src = video_src;
        video.addEventListener('loadedmetadata', function () {
            video.play();
        });
    }
}

function coverVideoIni() {
    if ($('video').hasClass('hls')) {
        if (mashiro_global.variables.has_hls){
            loadHls();
        }else{
            $.getScript("https://cdn.jsdelivr.net/gh/mashirozx/Sakura@3.3.3/cdn/js/src/16.hls.js", function(){
                loadHls();
                mashiro_global.variables.has_hls = true;
              });
        }
        //console.info('ini:coverVideoIni()');
    }
}

function copy_code_block() {
    $('pre code').each(function (i, block) {
        $(block).attr({
            id: 'hljs-' + i
        });
        $(this).after('<a class="copy-code" href="javascript:" data-clipboard-target="#hljs-' + i + '" title="拷贝代码"><i class="fa fa-clipboard" aria-hidden="true"></i></a>');
    });
    var clipboard = new ClipboardJS('.copy-code');
}

function tableOfContentScroll(flag) {
    if (document.body.clientWidth <= 1200) {
        return;
    } else if ($("div").hasClass("have-toc") == false && $("div").hasClass("has-toc") == false) {
        $(".toc-container").remove();
    } else {
        if (flag) {
            var id = 1,
                heading_fix = mashiro_option.entry_content_theme == "sakurairo" ? $("article").hasClass("type-post") ? $("div").hasClass("pattern-attachment-img") ? -75 : 200 : 375 : window.innerHeight / 2;
            $(".entry-content , .links").children("h1,h2,h3,h4,h5").each(function () {
                var hyphenated = "toc-head-" + id;
                this.id = hyphenated;
                id++;
            });
            tocbot.init({
                tocSelector: '.toc',
                contentSelector: ['.entry-content', '.links'],
                headingSelector: 'h1, h2, h3, h4, h5',
                headingsOffset: heading_fix - window.innerHeight / 2,
            });
        }
    }
}
tableOfContentScroll(flag = true);
var pjaxInit = function () {
    add_upload_tips();
    no_right_click();
    click_to_view_image();
    original_emoji_click();
    mashiro_global.font_control.ini();
    $("p").remove(".head-copyright");
    try {
        code_highlight_style();
    } catch (e) {};
    try {
        getqqinfo();
    } catch (e) {};
    lazyload();
    $("#to-load-aplayer").click(function () {
        try {
            reloadHermit();
        } catch (e) {};
        $("div").remove(".load-aplayer");
    });
    if ($("div").hasClass("aplayer")) {
        try {
            reloadHermit();
        } catch (e) {};
    }
    $('.iconflat').css('width', '50px').css('height', '50px');
    $('.openNav').css('height', '50px');
    $("#bg-next").click(function () {
        nextBG();
    });
    $("#bg-pre").click(function () {
        preBG();
    });
    smileBoxToggle();
    timeSeriesReload();
    add_copyright();
    tableOfContentScroll(flag = true);
}
$(document).on("click", ".sm", function () {
    var msg = "您真的要设为私密吗？";
    if (confirm(msg) == true) {
        $(this).commentPrivate();
    } else {
        alert("已取消");
    }
});
$.fn.commentPrivate = function () {
    if ($(this).hasClass('private_now')) {
        alert('您之前已设过私密评论');
        return false;
    } else {
        $(this).addClass('private_now');
        var idp = $(this).data('idp'),
            actionp = $(this).data('actionp'),
            rateHolderp = $(this).children('.has_set_private');
        var ajax_data = {
            action: "siren_private",
            p_id: idp,
            p_action: actionp
        };
        $.post("/wp-admin/admin-ajax.php", ajax_data, function (data) {
            $(rateHolderp).html(data);
        });
        return false;
    }
};

POWERMODE.colorful = true;
POWERMODE.shake = false;
document.body.addEventListener('input', POWERMODE);

function motionSwitch(ele) {
    var motionEles = [".bili", ".menhera", ".tieba"];
    for (var i in motionEles) {
        $(motionEles[i] + '-bar').removeClass("on-hover");
        $(motionEles[i] + '-container').css("display", "none");
    }
    $(ele + '-bar').addClass("on-hover");
    $(ele + '-container').css("display", "block");
}
$('.comt-addsmilies').click(function () {
    $('.comt-smilies').toggle();
})
$('.comt-smilies a').click(function () {
    $(this).parent().hide();
})

function smileBoxToggle() {
    $(document).ready(function () {
        $("#emotion-toggle").click(function () {
            $(".emotion-toggle-off").toggle(0);
            $(".emotion-toggle-on").toggle(0);
            $(".emotion-box").toggle(160);
        });
    });
}
smileBoxToggle();

function grin(tag, type, before, after) {
    var myField;
    if (type == "custom") {
        tag = before + tag + after;
    } else if (type == "Img") {
        tag = '[img]' + tag + '[/img]';
    } else if (type == "Math") {
        tag = ' {{' + tag + '}} ';
    } else if (type == "tieba") {
        tag = ' ::' + tag + ':: ';
    } else {
        tag = ' :' + tag + ': ';
    }
    if (addComment.I('comment') && addComment.I('comment').type == 'textarea') {
        myField = addComment.I('comment');
    } else {
        return false;
    }
    if (document.selection) {
        myField.focus();
        sel = document.selection.createRange();
        sel.text = tag;
        myField.focus();
    } else if (myField.selectionStart || myField.selectionStart == '0') {
        var startPos = myField.selectionStart;
        var endPos = myField.selectionEnd;
        var cursorPos = endPos;
        myField.value = myField.value.substring(0, startPos) + tag + myField.value.substring(endPos, myField.value.length);
        cursorPos += tag.length;
        myField.focus();
        myField.selectionStart = cursorPos;
        myField.selectionEnd = cursorPos;
    } else {
        myField.value += tag;
        myField.focus();
    }
}

function add_copyright() {
    document.body.addEventListener("copy", function (e) {
        if (window.getSelection().toString().length > 30 && mashiro_option.clipboardCopyright) {
            setClipboardText(e);
        }
        addComment.createButterbar("复制成功！<br>Copied to clipboard successfully!", 1000);
    });

    function setClipboardText(event) {
        event.preventDefault();
        var htmlData = "# 商业转载请联系作者获得授权，非商业转载请注明出处。<br>" + "# For commercial use, please contact the author for authorization. For non-commercial use, please indicate the source.<br>" + "# 协议(License)：署名-非商业性使用-相同方式共享 4.0 国际 (CC BY-NC-SA 4.0)<br>" + "# 作者(Author)：" + mashiro_option.author_name + "<br>" + "# 链接(URL)：" + window.location.href + "<br>" + "# 来源(Source)：" + mashiro_option.site_name + "<br><br>" + window.getSelection().toString().replace(/\r\n/g, "<br>");;
        var textData = "# 商业转载请联系作者获得授权，非商业转载请注明出处。\n" + "# For commercial use, please contact the author for authorization. For non-commercial use, please indicate the source.\n" + "# 协议(License)：署名-非商业性使用-相同方式共享 4.0 国际 (CC BY-NC-SA 4.0)\n" + "# 作者(Author)：" + mashiro_option.author_name + "\n" + "# 链接(URL)：" + window.location.href + "\n" + "# 来源(Source)：" + mashiro_option.site_name + "\n\n" + window.getSelection().toString().replace(/\r\n/g, "\n");
        if (event.clipboardData) {
            event.clipboardData.setData("text/html", htmlData);
            event.clipboardData.setData("text/plain", textData);
        } else if (window.clipboardData) {
            return window.clipboardData.setData("text", textData);
        }
    }
}
add_copyright();
$(function () {
    getqqinfo();
});

if (mashiro_option.float_player_on) {
    function aplayerF() {
        'use strict';
        var aplayers = [],
            loadMeting = function () {
                function a(a, b) {
                    var c = {
                        container: a,
                        audio: b,
                        mini: null,
                        fixed: null,
                        autoplay: !1,
                        mutex: !0,
                        lrcType: 3,
                        listFolded: 1,
                        preload: 'auto',
                        theme: '#2980b9',
                        loop: 'all',
                        order: 'list',
                        volume: null,
                        listMaxHeight: null,
                        customAudioType: null,
                        storageName: 'metingjs'
                    };
                    if (b.length) {
                        b[0].lrc || (c.lrcType = 0);
                        var d = {};
                        for (var e in c) {
                            var f = e.toLowerCase();
                            (a.dataset.hasOwnProperty(f) || a.dataset.hasOwnProperty(e) || null !== c[e]) && (d[e] = a.dataset[f] || a.dataset[e] || c[e], ('true' === d[e] || 'false' === d[e]) && (d[e] = 'true' == d[e]))
                        }
                        aplayers.push(new APlayer(d))
                    }
                    for (var f = 0; f < aplayers.length; f++) try {
                        aplayers[f].lrc.hide();
                    } catch (a) {
                        console.log(a)
                    }
                    var lrcTag = 1;
                    $(".aplayer.aplayer-fixed").click(function () {
                        if (lrcTag == 1) {
                            for (var f = 0; f < aplayers.length; f++) try {
                                aplayers[f].lrc.show();
                            } catch (a) {
                                console.log(a)
                            }
                        }
                        lrcTag = 2;
                    });
                    var apSwitchTag = 0;
                    $(".aplayer.aplayer-fixed .aplayer-body").addClass("ap-hover");
                    $(".aplayer-miniswitcher").click(function () {
                        if (apSwitchTag == 0) {
                            $(".aplayer.aplayer-fixed .aplayer-body").removeClass("ap-hover");
                            $("#secondary").addClass("active");
                            apSwitchTag = 1;
                        } else {
                            $(".aplayer.aplayer-fixed .aplayer-body").addClass("ap-hover");
                            $("#secondary").removeClass("active");
                            apSwitchTag = 0;
                        }
                    });
                }
                var b = mashiro_option.meting_api_url + '?server=:server&type=:type&id=:id&_wpnonce=' + Poi.nonce;
                'undefined' != typeof meting_api && (b = meting_api);
                for (var f = 0; f < aplayers.length; f++) try {
                    aplayers[f].destroy()
                } catch (a) {
                    console.log(a)
                }
                aplayers = [];
                for (var c = document.querySelectorAll('.aplayer'), d = function () {
                        var d = c[e],
                            f = d.dataset.id;
                        if (f) {
                            var g = d.dataset.api || b;
                            g = g.replace(':server', d.dataset.server), g = g.replace(':type', d.dataset.type), g = g.replace(':id', d.dataset.id);
                            var h = new XMLHttpRequest;
                            h.onreadystatechange = function () {
                                if (4 === h.readyState && (200 <= h.status && 300 > h.status || 304 === h.status)) {
                                    var b = JSON.parse(h.responseText);
                                    a(d, b)
                                }
                            }, h.open('get', g, !0), h.send(null)
                        } else if (d.dataset.url) {
                            var i = [{
                                name: d.dataset.name || d.dataset.title || 'Audio name',
                                artist: d.dataset.artist || d.dataset.author || 'Audio artist',
                                url: d.dataset.url,
                                cover: d.dataset.cover || d.dataset.pic,
                                lrc: d.dataset.lrc,
                                type: d.dataset.type || 'auto'
                            }];
                            a(d, i)
                        }
                    }, e = 0; e < c.length; e++) d()
            };
        document.addEventListener('DOMContentLoaded', loadMeting, !1);
    }
    if (document.body.clientWidth > 860) {
        aplayerF();
    }
}

function getqqinfo() {
    var is_get_by_qq = false,
        cached = $('input');
    if (!getCookie('user_qq') && !getCookie('user_qq_email') && !getCookie('user_author')) {
        cached.filter('#qq,#author,#email,#url').val('');
    }
    if (getCookie('user_avatar') && getCookie('user_qq') && getCookie('user_qq_email')) {
        $('div.comment-user-avatar img').attr('src', getCookie('user_avatar'));
        cached.filter('#author').val(getCookie('user_author'));
        cached.filter('#email').val(getCookie('user_qq') + '@qq.com');
        cached.filter('#qq').val(getCookie('user_qq'));
        if (mashiro_option.qzone_autocomplete) {
            cached.filter('#url').val('https://user.qzone.qq.com/' + getCookie('user_qq'));
        }
        if (cached.filter('#qq').val()) {
            $('.qq-check').css('display', 'block');
            $('.gravatar-check').css('display', 'none');
        }
    }
    var emailAddressFlag = cached.filter('#email').val();
    cached.filter('#author').on('blur', function () {
        var qq = cached.filter('#author').val(),
            $reg = /^[1-9]\d{4,9}$/;
        if ($reg.test(qq)) {
            $.ajax({
                type: 'get',
                url: mashiro_option.qq_api_url + '?qq=' + qq + '&_wpnonce=' + Poi.nonce,
                dataType: 'json',
                success: function (data) {
                    cached.filter('#author').val(data.name);
                    cached.filter('#email').val($.trim(qq) + '@qq.com');
                    if (mashiro_option.qzone_autocomplete) {
                        cached.filter('#url').val('https://user.qzone.qq.com/' + $.trim(qq));
                    }
                    $('div.comment-user-avatar img').attr('src', 'https://q2.qlogo.cn/headimg_dl?dst_uin=' + qq + '&spec=100');
                    is_get_by_qq = true;
                    cached.filter('#qq').val($.trim(qq));
                    if (cached.filter('#qq').val()) {
                        $('.qq-check').css('display', 'block');
                        $('.gravatar-check').css('display', 'none');
                    }
                    setCookie('user_author', data.name, 30);
                    setCookie('user_qq', qq, 30);
                    setCookie('is_user_qq', 'yes', 30);
                    setCookie('user_qq_email', qq + '@qq.com', 30);
                    setCookie('user_email', qq + '@qq.com', 30);
                    emailAddressFlag = cached.filter('#email').val();
                    /***/
                    $('div.comment-user-avatar img').attr('src', data.avatar);
                    setCookie('user_avatar', data.avatar, 30);
                },
                error: function () {
                    cached.filter('#qq').val('');
                    $('.qq-check').css('display', 'none');
                    $('.gravatar-check').css('display', 'block');
                    $('div.comment-user-avatar img').attr('src', get_gravatar(cached.filter('#email').val(), 80));
                    setCookie('user_qq', '', 30);
                    setCookie('user_email', cached.filter('#email').val(), 30);
                    setCookie('user_avatar', get_gravatar(cached.filter('#email').val(), 80), 30);
                    /***/
                    cached.filter('#qq,#email,#url').val('');
                    if (!cached.filter('#qq').val()) {
                        $('.qq-check').css('display', 'none');
                        $('.gravatar-check').css('display', 'block');
                        setCookie('user_qq', '', 30);
                        $('div.comment-user-avatar img').attr('src', get_gravatar(cached.filter('#email').val(), 80));
                        setCookie('user_avatar', get_gravatar(cached.filter('#email').val(), 80), 30);
                    }
                }
            });
        }
    });
    if (getCookie('user_avatar') && getCookie('user_email') && getCookie('is_user_qq') == 'no' && !getCookie('user_qq_email')) {
        $('div.comment-user-avatar img').attr('src', getCookie('user_avatar'));
        cached.filter('#email').val(getCookie('user_email'));
        cached.filter('#qq').val('');
        if (!cached.filter('#qq').val()) {
            $('.qq-check').css('display', 'none');
            $('.gravatar-check').css('display', 'block');
        }
    }
    cached.filter('#email').on('blur', function () {
        var emailAddress = cached.filter('#email').val();
        if (is_get_by_qq == false || emailAddressFlag != emailAddress) {
            $('div.comment-user-avatar img').attr('src', get_gravatar(emailAddress, 80));
            setCookie('user_avatar', get_gravatar(emailAddress, 80), 30);
            setCookie('user_email', emailAddress, 30);
            setCookie('user_qq_email', '', 30);
            setCookie('is_user_qq', 'no', 30);
            cached.filter('#qq').val('');
            if (!cached.filter('#qq').val()) {
                $('.qq-check').css('display', 'none');
                $('.gravatar-check').css('display', 'block');
            }
        }
    });
    if (getCookie('user_url')) {
        cached.filter('#url').val(getCookie('user_url'));
    }
    cached.filter('#url').on('blur', function () {
        var URL_Address = cached.filter('#url').val();
        cached.filter('#url').val(URL_Address);
        setCookie('user_url', URL_Address, 30);
    });
    if (getCookie('user_author')) {
        cached.filter('#author').val(getCookie('user_author'));
    }
    cached.filter('#author').on('blur', function () {
        var user_name = cached.filter('#author').val();
        cached.filter('#author').val(user_name);
        setCookie('user_author', user_name, 30);
    });
}

function mail_me() {
    var mail = "mailto:" + mashiro_option.email_name + "@" + mashiro_option.email_domain;
    window.open(mail);
}

function activate_widget(){
    if (document.body.clientWidth > 860) {
        $('.show-hide').on('click', function() {
            $("#secondary").toggleClass("active")
        });
    }else{
        $("#secondary").remove();
    }
}
setTimeout(function () {
    activate_widget();
}, 100);

function load_bangumi() {
    if ($("section").hasClass("bangumi")) {
        $('body').on('click', '#bangumi-pagination a', function () {
            $("#bangumi-pagination a").addClass("loading").text("");
            var xhr = new XMLHttpRequest();
            xhr.open('POST', this.href + "&_wpnonce=" + Poi.nonce, true);
            xhr.onreadystatechange = function() {
                if (xhr.readyState == 4 && xhr.status == 200) {
                    var html = JSON.parse(xhr.responseText);
                    $("#bangumi-pagination").remove();
                    $(".row").append(html);
                }else{
                    $("#bangumi-pagination a").removeClass("loading").html('<i class="fa fa-exclamation-triangle" aria-hidden="true"></i> ERROR ');
                }
            };
            xhr.send();
            return false;
        });
    }
}


mashiro_global.ini.normalize();
loadCSS(mashiro_option.jsdelivr_css_src);
loadCSS(mashiro_option.entry_content_theme_src);
loadCSS("https://at.alicdn.com/t/font_679578_qyt5qzzavdo39pb9.css");
loadCSS("https://cdn.jsdelivr.net/npm/aplayer@1.10.1/dist/APlayer.min.css");
loadCSS("https://fonts.loli.net/css?family=Noto+SerifMerriweather|Merriweather+Sans|Source+Code+Pro|Ubuntu:400,700|Noto+Serif+SC");
(function webpackUniversalModuleDefinition(b, a) {
    if (typeof exports === "object" && typeof module === "object") {
        module.exports = a()
    } else {
        if (typeof define === "function" && define.amd) {
            define([], a)
        } else {
            if (typeof exports === "object") {
                exports.POWERMODE = a()
            } else {
                b.POWERMODE = a()
            }
        }
    }
})(this, function () {
    return (function (c) {
        var b = {};

        function a(e) {
            if (b[e]) {
                return b[e].exports
            }
            var d = b[e] = {
                exports: {},
                id: e,
                loaded: false
            };
            c[e].call(d.exports, d, d.exports, a);
            d.loaded = true;
            return d.exports
        }
        a.m = c;
        a.c = b;
        a.p = "";
        return a(0)
    })([function (j, e, a) {
        var b = document.createElement("canvas");
        b.width = window.innerWidth;
        b.height = window.innerHeight;
        b.style.cssText = "position:fixed;top:0;left:0;pointer-events:none;z-index:999999";
        window.addEventListener("resize", function () {
            b.width = window.innerWidth;
            b.height = window.innerHeight
        });
        document.body.appendChild(b);
        var c = b.getContext("2d");
        var l = [];
        var k = 0;
        m.shake = true;

        function h(o, n) {
            return Math.random() * (n - o) + o
        }

        function g(n) {
            if (m.colorful) {
                var o = h(0, 360);
                return "hsla(" + h(o - 10, o + 10) + ", 100%, " + h(50, 80) + "%, " + 1 + ")"
            } else {
                return window.getComputedStyle(n).color
            }
        }

        function f() {
            var o = document.activeElement;
            var n;
            if (o.tagName === "TEXTAREA" || (o.tagName === "INPUT" && o.getAttribute("type") === "text")) {
                var p = a(1)(o, o.selectionStart);
                n = o.getBoundingClientRect();
                return {
                    x: p.left + n.left,
                    y: p.top + n.top,
                    color: g(o)
                }
            }
            var r = window.getSelection();
            if (r.rangeCount) {
                var q = r.getRangeAt(0);
                var s = q.startContainer;
                if (s.nodeType === document.TEXT_NODE) {
                    s = s.parentNode
                }
                n = q.getBoundingClientRect();
                return {
                    x: n.left,
                    y: n.top,
                    color: g(s)
                }
            }
            return {
                x: 0,
                y: 0,
                color: "transparent"
            }
        }

        function d(o, p, n) {
            return {
                x: o,
                y: p,
                alpha: 1,
                color: n,
                velocity: {
                    x: -1 + Math.random() * 2,
                    y: -3.5 + Math.random() * 2
                }
            }
        }

        function m() {
            var n = f();
            var p = 5 + Math.round(Math.random() * 10);
            while (p--) {
                l[k] = d(n.x, n.y, n.color);
                k = (k + 1) % 500
            }
            if (m.shake) {
                var o = 1 + 2 * Math.random();
                var q = o * (Math.random() > 0.5 ? -1 : 1);
                var r = o * (Math.random() > 0.5 ? -1 : 1);
                document.body.style.marginLeft = q + "px";
                document.body.style.marginTop = r + "px";
                setTimeout(function () {
                    document.body.style.marginLeft = "";
                    document.body.style.marginTop = ""
                }, 75)
            }
        }
        m.colorful = false;

        function i() {
            requestAnimationFrame(i);
            c.clearRect(0, 0, b.width, b.height);
            for (var n = 0; n < l.length; ++n) {
                var o = l[n];
                if (o.alpha <= 0.1) {
                    continue
                }
                o.velocity.y += 0.075;
                o.x += o.velocity.x;
                o.y += o.velocity.y;
                o.alpha *= 0.96;
                c.globalAlpha = o.alpha;
                c.fillStyle = o.color;
                c.fillRect(Math.round(o.x - 1.5), Math.round(o.y - 1.5), 3, 3)
            }
        }
        requestAnimationFrame(i);
        j.exports = m
    }, function (b, a) {
        (function () {
            var e = ["direction", "boxSizing", "width", "height", "overflowX", "overflowY", "borderTopWidth", "borderRightWidth", "borderBottomWidth", "borderLeftWidth", "borderStyle", "paddingTop", "paddingRight", "paddingBottom", "paddingLeft", "fontStyle", "fontVariant", "fontWeight", "fontStretch", "fontSize", "fontSizeAdjust", "lineHeight", "fontFamily", "textAlign", "textTransform", "textIndent", "textDecoration", "letterSpacing", "wordSpacing", "tabSize", "MozTabSize"];
            var d = window.mozInnerScreenX != null;

            function c(k, m, l) {
                var h = l && l.debug || false;
                if (h) {
                    var j = document.querySelector("#input-textarea-caret-position-mirror-div");
                    if (j) {
                        j.parentNode.removeChild(j)
                    }
                }
                var i = document.createElement("div");
                i.id = "input-textarea-caret-position-mirror-div";
                document.body.appendChild(i);
                var o = i.style;
                var f = window.getComputedStyle ? getComputedStyle(k) : k.currentStyle;
                o.whiteSpace = "pre-wrap";
                if (k.nodeName !== "INPUT") {
                    o.wordWrap = "break-word"
                }
                o.position = "absolute";
                if (!h) {
                    o.visibility = "hidden"
                }
                e.forEach(function (p) {
                    o[p] = f[p]
                });
                if (d) {
                    if (k.scrollHeight > parseInt(f.height)) {
                        o.overflowY = "scroll"
                    }
                } else {
                    o.overflow = "hidden"
                }
                i.textContent = k.value.substring(0, m);
                if (k.nodeName === "INPUT") {
                    i.textContent = i.textContent.replace(/\s/g, "\u00a0")
                }
                var n = document.createElement("span");
                n.textContent = k.value.substring(m) || ".";
                i.appendChild(n);
                var g = {
                    top: n.offsetTop + parseInt(f.borderTopWidth),
                    left: n.offsetLeft + parseInt(f.borderLeftWidth)
                };
                if (h) {
                    n.style.backgroundColor = "#aaa"
                } else {
                    document.body.removeChild(i)
                }
                return g
            }
            if (typeof b != "undefined" && typeof b.exports != "undefined") {
                b.exports = c
            } else {
                window.getCaretCoordinates = c
            }
        }())
    }])
});

var home = location.href,
    s = $('#bgvideo')[0],
    Siren = {
        MN: function () {
            $('.iconflat').on('click', function () {
                $('body').toggleClass('navOpen');
                $('#main-container,#mo-nav,.openNav').toggleClass('open');
            });
        },
        MNH: function () {
            if ($('body').hasClass('navOpen')) {
                $('body').toggleClass('navOpen');
                $('#main-container,#mo-nav,.openNav').toggleClass('open');
            }
        },
        splay: function () {
            $('#video-btn').addClass('video-pause').removeClass('video-play').show();
            $('.video-stu').css({
                "bottom": "-100px"
            });
            $('.focusinfo').css({
                "top": "-999px"
            });
            try {
                for (var i = 0; i < ap.length; i++) {
                    try {
                        ap[i].destroy()
                    } catch (e) {}
                }
            } catch (e) {}
            try {
                hermitInit()
            } catch (e) {}
            s.play();
        },
        spause: function () {
            $('#video-btn').addClass('video-play').removeClass('video-pause');
            $('.focusinfo').css({
                "top": "49.3%"
            });
            s.pause();
        },
        liveplay: function () {
            if (s.oncanplay != undefined && $('.haslive').length > 0) {
                if ($('.videolive').length > 0) {
                    Siren.splay();
                }
            }
        },
        livepause: function () {
            if (s.oncanplay != undefined && $('.haslive').length > 0) {
                Siren.spause();
                $('.video-stu').css({
                    "bottom": "0px"
                }).html('已暂停 ...');
            }
        },
        addsource: function () {
            $('.video-stu').html('正在载入视频 ...').css({
                "bottom": "0px"
            });
            var t = Poi.movies.name.split(","),
                _t = t[Math.floor(Math.random() * t.length)];
            $('#bgvideo').attr('src', Poi.movies.url + '/' + _t + '.mp4');
            $('#bgvideo').attr('video-name', _t);
        },
        LV: function () {
            var _btn = $('#video-btn');
            _btn.on('click', function () {
                if ($(this).hasClass('loadvideo')) {
                    $(this).addClass('video-pause').removeClass('loadvideo').hide();
                    Siren.addsource();
                    s.oncanplay = function () {
                        Siren.splay();
                        $('#video-add').show();
                        _btn.addClass('videolive').addClass('haslive');
                    }
                } else {
                    if ($(this).hasClass('video-pause')) {
                        Siren.spause();
                        _btn.removeClass('videolive');
                        $('.video-stu').css({
                            "bottom": "0px"
                        }).html('已暂停 ...');
                    } else {
                        Siren.splay();
                        _btn.addClass('videolive');
                    }
                }
                s.onended = function () {
                    $('#bgvideo').attr('src', '');
                    $('#video-add').hide();
                    _btn.addClass('loadvideo').removeClass('video-pause').removeClass('videolive').removeClass('haslive');
                    $('.focusinfo').css({
                        "top": "49.3%"
                    });
                }
            });
            $('#video-add').on('click', function () {
                Siren.addsource();
            });
        },
        AH: function () {
            if (Poi.windowheight == 'auto') {
                if ($('h1.main-title').length > 0) {
                    var _height = $(window).height() + "px";
                    $('#centerbg').css({
                        'height': '100vh'
                    });
                    $('#bgvideo').css({
                        'min-height': '100vh'
                    });
                }
            } else {
                $('.headertop').addClass('headertop-bar');
            }
        },
        PE: function () {
            if ($('.headertop').length > 0) {
                if ($('h1.main-title').length > 0) {
                    $('.blank').css({
                        "padding-top": "0px"
                    });
                    $('.headertop').css({
                        "height": "auto"
                    }).show();
                    if (Poi.movies.live == 'open') Siren.liveplay();
                } else {
                    $('.blank').css({
                        "padding-top": "75px"
                    });
                    $('.headertop').css({
                        "height": "0px"
                    }).hide();
                    Siren.livepause();
                }
            }
        },
        CE: function () {
            $('.comments-hidden').show();
            $('.comments-main').hide();
            $('.comments-hidden').click(function () {
                $('.comments-main').slideDown(500);
                $('.comments-hidden').hide();
            });
            $('.archives').hide();
            $('.archives:first').show();
            $('#archives-temp h3').click(function () {
                $(this).next().slideToggle('fast');
                return false;
            });
            if (mashiro_option.baguetteBoxON) {
                baguetteBox.run('.entry-content', {
                    captions: function (element) {
                        return element.getElementsByTagName('img')[0].alt;
                    },
                    ignoreClass: 'fancybox',
                });
            }
            $('.js-toggle-search').on('click', function () {
                $('.js-toggle-search').toggleClass('is-active');
                $('.js-search').toggleClass('is-visible');
                $('html').css('overflow-y', 'hidden');
                if (mashiro_option.live_search) {
                    var QueryStorage = [];
                    search_a(Poi.api + "sakura/v1/cache_search/json?_wpnonce=" + Poi.nonce);

                    var otxt = addComment.I("search-input"),
                        list = addComment.I("PostlistBox"),
                        Record = list.innerHTML,
                        searchFlag = null;
                    otxt.oninput = function () {
                        if (searchFlag = null) {
                            clearTimeout(searchFlag);
                        }
                        searchFlag = setTimeout(function () {
                            query(QueryStorage, otxt.value, Record);
                            div_href();
                        }, 250);
                    };

                    function search_a(val) {
                        if (sessionStorage.getItem('search') != null) {
                            QueryStorage = JSON.parse(sessionStorage.getItem('search'));
                            query(QueryStorage, $("#search-input").val(), Record);
                            div_href();
                        } else {
                            var _xhr = new XMLHttpRequest();
                            _xhr.open("GET", val, true)
                            _xhr.send();
                            _xhr.onreadystatechange = function () {
                                if (_xhr.readyState == 4 && _xhr.status == 200) {
                                    json = _xhr.responseText;
                                    if (json != "") {
                                        sessionStorage.setItem('search', json);
                                        QueryStorage = JSON.parse(json);
                                        query(QueryStorage, otxt.value, Record);
                                        div_href();
                                    }
                                }
                            }
                        }
                    }
                    if (!Object.values) Object.values = function (obj) {
                        if (obj !== Object(obj))
                            throw new TypeError('Object.values called on a non-object');
                        var val = [],
                            key;
                        for (key in obj) {
                            if (Object.prototype.hasOwnProperty.call(obj, key)) {
                                val.push(obj[key]);
                            }
                        }
                        return val;
                    }

                    function Cx(arr, q) {
                        q = q.replace(q, "^(?=.*?" + q + ").+$").replace(/\s/g, ")(?=.*?");
                        i = arr.filter(
                            v => Object.values(v).some(
                                v => new RegExp(q + '').test(v)
                            )
                        );
                        return i;
                    }

                    function div_href() {
                        $(".ins-selectable").each(function () {
                            $(this).click(function () {
                                $("#Ty").attr('href', $(this).attr('href'));
                                $("#Ty").click();
                                $(".search_close").click();
                            });
                        });
                    }

                    function search_result(keyword, link, fa, title, iconfont, comments, text) {
                        if (keyword) {
                            var s = keyword.trim().split(" "),
                                a = title.indexOf(s[s.length - 1]),
                                b = text.indexOf(s[s.length - 1]);
                            title = a < 60 ? title.slice(0, 80) : title.slice(a - 30, a + 30);
                            title = title.replace(s[s.length - 1], '<mark class="search-keyword"> ' + s[s.length - 1].toUpperCase() + ' </mark>');
                            text = b < 60 ? text.slice(0, 80) : text.slice(b - 30, b + 30);
                            text = text.replace(s[s.length - 1], '<mark class="search-keyword"> ' + s[s.length - 1].toUpperCase() + ' </mark>');
                        }
                        return '<div class="ins-selectable ins-search-item" href="' + link + '"><header><i class="fa fa-' + fa + '" aria-hidden="true"></i>' + title + '<i class="iconfont icon-' + iconfont + '"> ' + comments + '</i>' + '</header><p class="ins-search-preview">' + text + '</p></div>';
                    }

                    function query(B, A, z) {
                        var x, v, s, y = "",
                            w = "",
                            u = "",
                            r = "",
                            p = "",
                            F = "",
                            H = "",
                            G = '<section class="ins-section"><header class="ins-section-header">',
                            D = "</section>",
                            E = "</header>",
                            C = Cx(B, A.trim());
                        for (x = 0; x < Object.keys(C).length; x++) {
                            H = C[x];
                            switch (v = H.type) {
                                case "post":
                                    w = w + search_result(A, H.link, "file", H.title, "mark", H.comments, H.text);
                                    break;
                                case "tag":
                                    p = p + search_result("", H.link, "tag", H.title, "none", "", "");
                                    break;
                                case "category":
                                    r = r + search_result("", H.link, "folder", H.title, "none", "", "");
                                    break;
                                case "page":
                                    u = u + search_result(A, H.link, "file", H.title, "mark", H.comments, H.text);
                                    break;
                                case "comment":
                                    F = F + search_result(A, H.link, "comment", H.title, "none", "", H.text);
                                    break
                            }
                        }
                        w && (y = y + G + "文章" + E + w + D), u && (y = y + G + "页面" + E + u + D), r && (y = y + G + "分类" + E + r + D), p && (y = y + G + "标签" + E + p + D), F && (y = y + G + "评论" + E + F + D), s = addComment.I("PostlistBox"), s.innerHTML = y
                    }
                }
            });
            $('.search_close').on('click', function () {
                if ($('.js-search').hasClass('is-visible')) {
                    $('.js-toggle-search').toggleClass('is-active');
                    $('.js-search').toggleClass('is-visible');
                    $('html').css('overflow-y', 'unset');
                }
            });
            $('#show-nav').on('click', function () {
                if ($('#show-nav').hasClass('showNav')) {
                    $('#show-nav').removeClass('showNav').addClass('hideNav');
                    $('.site-top .lower nav').addClass('navbar');
                } else {
                    $('#show-nav').removeClass('hideNav').addClass('showNav');
                    $('.site-top .lower nav').removeClass('navbar');
                }
            });
            $("#loading").click(function () {
                $("#loading").fadeOut(500);
            });
        },
        NH: function () {
            if(document.body.clientWidth > 860){
                var h1 = 0;
                $(window).scroll(function () {
                    var s = $(document).scrollTop(),
                        cached = $('.site-header');
                    if (s == h1) {
                        cached.removeClass('yya');
                    }
                    if (s > h1) {
                        cached.addClass('yya');
                    }
            });
            }
        },
        XLS: function () {
            $body = (window.opera) ? (document.compatMode == "CSS1Compat" ? $('html') : $('body')) : $('html,body');
            var load_post_timer;
            var intersectionObserver = new IntersectionObserver(function (entries) {
                if (entries[0].intersectionRatio <= 0) return;
                var page_next = $('#pagination a').attr("href");
                var load_key = addComment.I("add_post_time");
                if (page_next != undefined && load_key) {
                    var load_time = addComment.I("add_post_time").title;
                    if (load_time != "233") {
                        console.log("%c 自动加载时倒计时 %c", "background:#9a9da2; color:#ffffff; border-radius:4px;", "", "", load_time);
                        load_post_timer = setTimeout(function () {
                            load_post();
                        }, load_time * 1000);
                    }
                }
            });
            intersectionObserver.observe(
                document.querySelector('.footer-device')
            );
            $('body').on('click', '#pagination a', function () {
                clearTimeout(load_post_timer);
                load_post();
                return false;
            });

            function load_post() {
                $('#pagination a').addClass("loading").text("");
                $.ajax({
                    type: "POST",
                    url: $('#pagination a').attr("href") + "#main",
                    success: function (data) {
                        result = $(data).find("#main .post");
                        nextHref = $(data).find("#pagination a").attr("href");
                        $("#main").append(result.fadeIn(500));
                        $("#pagination a").removeClass("loading").text("Previous");
                        $('#add_post span').removeClass("loading").text("");
                        lazyload();
                        post_list_show_animation();
                        if (nextHref != undefined) {
                            $("#pagination a").attr("href", nextHref);
                            //加载完成上滑
                            var tempScrollTop = $(window).scrollTop();
                            $(window).scrollTop(tempScrollTop);
                            $body.animate({
                                scrollTop: tempScrollTop + 300
                                
                            }, 666)
                        } else {
                            $("#pagination").html("<span>很高兴你翻到这里，但是真的没有了...</span>");
                        }
                    }
                });
                return false;
            }
        },
        XCS: function () {
            var __cancel = jQuery('#cancel-comment-reply-link'),
                __cancel_text = __cancel.text(),
                __list = 'commentwrap';
            jQuery(document).on("submit", "#commentform", function () {
                jQuery.ajax({
                    url: Poi.ajaxurl,
                    data: jQuery(this).serialize() + "&action=ajax_comment",
                    type: jQuery(this).attr('method'),
                    beforeSend: addComment.createButterbar("提交中(Commiting)...."),
                    error: function (request) {
                        var t = addComment;
                        t.createButterbar(request.responseText);
                    },
                    success: function (data) {
                        jQuery('textarea').each(function () {
                            this.value = ''
                        });
                        var t = addComment,
                            cancel = t.I('cancel-comment-reply-link'),
                            temp = t.I('wp-temp-form-div'),
                            respond = t.I(t.respondId),
                            post = t.I('comment_post_ID').value,
                            parent = t.I('comment_parent').value;
                        if (parent != '0') {
                            jQuery('#respond').before('<ol class="children">' + data + '</ol>');
                        } else if (!jQuery('.' + __list).length) {
                            if (Poi.formpostion == 'bottom') {
                                jQuery('#respond').before('<ol class="' + __list + '">' + data + '</ol>');
                            } else {
                                jQuery('#respond').after('<ol class="' + __list + '">' + data + '</ol>');
                            }
                        } else {
                            if (Poi.order == 'asc') {
                                jQuery('.' + __list).append(data);
                            } else {
                                jQuery('.' + __list).prepend(data);
                            }
                        }
                        t.createButterbar("提交成功(Succeed)");
                        lazyload();
                        code_highlight_style();
                        click_to_view_image();
                        clean_upload_images();
                        cancel.style.display = 'none';
                        cancel.onclick = null;
                        t.I('comment_parent').value = '0';
                        if (temp && respond) {
                            temp.parentNode.insertBefore(respond, temp);
                            temp.parentNode.removeChild(temp)
                        }
                    }
                });
                return false;
            });
            addComment = {
                moveForm: function (commId, parentId, respondId) {
                    var t = this,
                        div, comm = t.I(commId),
                        respond = t.I(respondId),
                        cancel = t.I('cancel-comment-reply-link'),
                        parent = t.I('comment_parent'),
                        post = t.I('comment_post_ID');
                    __cancel.text(__cancel_text);
                    t.respondId = respondId;
                    if (!t.I('wp-temp-form-div')) {
                        div = document.createElement('div');
                        div.id = 'wp-temp-form-div';
                        div.style.display = 'none';
                        respond.parentNode.insertBefore(div, respond)
                    }!comm ? (temp = t.I('wp-temp-form-div'), t.I('comment_parent').value = '0', temp.parentNode.insertBefore(respond, temp), temp.parentNode.removeChild(temp)) : comm.parentNode.insertBefore(respond, comm.nextSibling);
                    jQuery("body").animate({
                        scrollTop: jQuery('#respond').offset().top - 180
                    }, 400);
                    parent.value = parentId;
                    cancel.style.display = '';
                    cancel.onclick = function () {
                        var t = addComment,
                            temp = t.I('wp-temp-form-div'),
                            respond = t.I(t.respondId);
                        t.I('comment_parent').value = '0';
                        if (temp && respond) {
                            temp.parentNode.insertBefore(respond, temp);
                            temp.parentNode.removeChild(temp);
                        }
                        this.style.display = 'none';
                        this.onclick = null;
                        return false;
                    };
                    try {
                        t.I('comment').focus();
                    } catch (e) {}
                    return false;
                },
                I: function (e) {
                    return document.getElementById(e);
                },
                clearButterbar: function (e) {
                    if (jQuery(".butterBar").length > 0) {
                        jQuery(".butterBar").remove();
                    }
                },
                createButterbar: function (message, showtime) {
                    var t = this;
                    t.clearButterbar();
                    jQuery("body").append('<div class="butterBar butterBar--center"><p class="butterBar-message">' + message + '</p></div>');
                    if (showtime > 0) {
                        setTimeout("jQuery('.butterBar').remove()", showtime);
                    } else {
                        setTimeout("jQuery('.butterBar').remove()", 6000);
                    }
                }
            };
        },
        XCP: function () {
            $body = (window.opera) ? (document.compatMode == "CSS1Compat" ? $('html') : $('body')) : $('html,body');
            $('body').on('click', '#comments-navi a', function (e) {
                e.preventDefault();
                var path = $(this)[0].pathname;
                $.ajax({
                    type: "GET",
                    url: $(this).attr('href'),
                    beforeSend: function () {
                        $('#comments-navi').remove();
                        $('ul.commentwrap').remove();
                        $('#loading-comments').slideDown();
                        $body.animate({
                            scrollTop: $('#comments-list-title').offset().top - 65
                        }, 800);
                    },
                    dataType: "html",
                    success: function (out) {
                        result = $(out).find('ul.commentwrap');
                        nextlink = $(out).find('#comments-navi');
                        $('#loading-comments').slideUp('fast');
                        $('#loading-comments').after(result.fadeIn(500));
                        $('ul.commentwrap').after(nextlink);
                        lazyload();
                        if (window.gtag) {
                            gtag('config', Poi.google_analytics_id, {
                                'page_path': path
                            });
                        }
                        code_highlight_style();
                        click_to_view_image();
                    }
                });
            });
        },
        IA: function () {
            POWERMODE.colorful = true;
            POWERMODE.shake = false;
            document.body.addEventListener('input', POWERMODE)
        },
        GT: function () {
            var cwidth = document.body.clientWidth,
                mb_to_top = document.querySelector("#moblieGoTop"),
                changskin = document.querySelector("#changskin");

            $(window).scroll(function() {
                    if ($(this).scrollTop() > 20) {
                        mb_to_top.style.transform = "scale(1)";
                        changskin.style.transform = "scale(1)";
                    } else {
                        mb_to_top.style.transform = "scale(0)";
                        changskin.style.transform = "scale(0)";
                    }
            
            });
            mb_to_top.onclick = function() {
                topFunction();
            }
        }
    }
$(function () {
    Siren.AH();
    Siren.PE();
    Siren.NH();
    Siren.GT();
    Siren.XLS();
    Siren.XCS();
    Siren.XCP();
    Siren.CE();
    Siren.MN();
    Siren.IA();
    Siren.LV();
    if (Poi.pjax) {
        $(document).pjax('a[target!=_top]', '#page', {
            fragment: '#page',
            timeout: 8000,
        }).on('pjax:beforeSend', () => { //离开页面停止播放
            $('.normal-cover-video').each(function () {
                this.pause();
                this.src = '';
                this.load = '';
            });
        }).on('pjax:send', function () {
            $("#bar").css("width", "0%");
            if (mashiro_option.NProgressON) NProgress.start();
            Siren.MNH();
        }).on('pjax:complete', function () {
            Siren.AH();
            Siren.PE();
            Siren.CE();
            if (mashiro_option.NProgressON) NProgress.done();
            mashiro_global.ini.pjax();
            $("#loading").fadeOut(500);
            if (Poi.codelamp == 'open') {
                self.Prism.highlightAll(event)
            };
            if ($('.ds-thread').length > 0) {
                if (typeof DUOSHUO !== 'undefined') {
                    DUOSHUO.EmbedThread('.ds-thread');
                } else {
                    $.getScript("//static.duoshuo.com/embed.js");
                }
            }
        }).on('pjax:end', function() {
            if (window.gtag){
                gtag('config', Poi.google_analytics_id, {
                    'page_path': window.location.pathname
                });
            }
        }).on('submit', '.search-form,.s-search', function (event) {
            event.preventDefault();
            $.pjax.submit(event, '#page', {
                fragment: '#page',
                timeout: 8000,
            });
            if ($('.js-search.is-visible').length > 0) {
                $('.js-toggle-search').toggleClass('is-active');
                $('.js-search').toggleClass('is-visible');
                $('html').css('overflow-y', 'unset');
            }
        });
        window.addEventListener('popstate', function (e) {
            Siren.AH();
            Siren.PE();
            Siren.CE();
            timeSeriesReload(true);
            post_list_show_animation();
        }, false);
    }
    $.fn.postLike = function () {
        if ($(this).hasClass('done')) {
            return false;
        } else {
            $(this).addClass('done');
            var id = $(this).data("id"),
                action = $(this).data('action'),
                rateHolder = $(this).children('.count');
            var ajax_data = {
                action: "specs_zan",
                um_id: id,
                um_action: action
            };
            $.post(Poi.ajaxurl, ajax_data, function (data) {
                $(rateHolder).html(data);
            });
            return false;
        }
    };
    $(document).on("click", ".specsZan", function () {
        $(this).postLike();
    });
    console.log("%c Mashiro %c", "background:#24272A; color:#ffffff", "", "https://2heng.xin/");
    console.log("%c Github %c", "background:#24272A; color:#ffffff", "", "https://github.com/mashirozx");
});
var isWebkit = navigator.userAgent.toLowerCase().indexOf('webkit') > -1,
    isOpera = navigator.userAgent.toLowerCase().indexOf('opera') > -1,
    isIe = navigator.userAgent.toLowerCase().indexOf('msie') > -1;
if ((isWebkit || isOpera || isIe) && document.getElementById && window.addEventListener) {
    window.addEventListener('hashchange', function () {
        var id = location.hash.substring(1),
            element;
        if (!(/^[A-z0-9_-]+$/.test(id))) {
            return;
        }
        element = addComment.I(id);
        if (element) {
            if (!(/^(?:a|select|input|button|textarea)$/i.test(element.tagName))) {
                element.tabIndex = -1;
            }
            element.focus();
        }
    }, false);
}

/* 首页下拉箭头 */
function headertop_down() {
    var coverOffset = $('#content').offset().top;
    $('html,body').animate({
        scrollTop: coverOffset
    },
    600);
}

window.onload = function() {
    $("html").css('overflow-y', 'unset');
    $("#preload").fadeOut();
    setTimeout('$("#preload").remove()', 666);
}



var cached_body = $("body");
function web_audio() {
    if (mashiro_option.audio) {
    window.AudioContext = window.AudioContext || window.webkitAudioContext,
        function () {
            if (window.AudioContext) {
                var e = new AudioContext,
                    t = "880 987 1046 987 1046 1318 987 659 659 880 784 880 1046 784 659 659 698 659 698 1046 659 1046 1046 1046 987 698 698 987 987 880 987 1046 987 1046 1318 987 659 659 880 784 880 1046 784 659 698 1046 987 1046 1174 1174 1174 1046 1046 880 987 784 880 1046 1174 1318 1174 1318 1567 1046 987 1046 1318 1318 1174 784 784 880 1046 987 1174 1046 784 784 1396 1318 1174 659 1318 1046 1318 1760 1567 1567 1318 1174 1046 1046 1174 1046 1174 1567 1318 1318 1760 1567 1318 1174 1046 1046 1174 1046 1174 987 880 880 987 880".split(" "),//天空之城
                    /*t = "329.628 329.628 349.228 391.995 391.995 349.228 329.628 293.665 261.626 261.626 293.665 329.628 329.628 293.665 293.665 329.628 329.628 349.228 391.995 391.995 349.228 329.628 293.665 261.626 261.626 293.665 329.628 293.665 261.626 261.626 293.665 293.665 329.628 261.626 293.665 329.628 349.228 329.628 261.626 293.665 329.628 349.228 329.628 293.665 261.626 293.665 195.998 329.628 329.628 349.228 391.995 391.995 349.228 329.628 293.665 261.626 261.626 293.665 329.628 293.665 261.626 261.626".split(" "),欢乐颂*/
                    i = 0,
                    o = 1,
                    a = "♪ ♩ ♫ ♬ ♭ € § ¶ ♯".split(" "),
                    n = !1
                $("site-title, #moblieGoTop, .site-branding, .searchbox, .changeSkin-gear, .menu-list>li#star-bg").mouseenter(function (s) {
                    var r = t[i]
                    r || (i = 0, r = t[i]), i += o
                    var c = e.createOscillator(),
                        l = e.createGain()
                    if (c.connect(l), l.connect(e.destination), c.type = "sine", c.frequency.value = r, l.gain.setValueAtTime(0, e.currentTime), l.gain.linearRampToValueAtTime(1, e.currentTime + .01), c.start(e.currentTime), l.gain.exponentialRampToValueAtTime(.001, e.currentTime + 1), c.stop(e.currentTime + 1), n = !0) {
                        var d = Math.round(7 * Math.random()),
                            u = $("<b/>").text(a[d]),
                            h = s.pageX,
                            p = s.pageY - 5
                        u.css({
                            "z-index": 99999,
                            top: p - 20,
                            left: h,
                            position: "absolute",
                            color: "#FF6EB4"
                        }), cached_body.append(u), u.animate({
                            top: p - 180,
                            opacity: 0
                        }, 1500, function () {
                            u.remove()
                        }), s.stopPropagation()
                    }
                    n = !1
                })
            }
        }()
}
}
