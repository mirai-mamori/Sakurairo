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
        post_list_show_animation();
        copy_code_block();
        web_audio();
        coverVideoIni();
        checkskinSecter();
        load_bangumi();
    }
}

function setCookie(name, value, days) {
    let expires = "";
    if (days) {
        let date = new Date();
        date.setTime(date.getTime() + (days * 24 * 60 * 60 * 1000));
        expires = "; expires=" + date.toUTCString();
    }
    document.cookie = name + mashiro_option.cookie_version_control + "=" + (value || "") + expires + "; path=/";
}

function getCookie(name) {
    let nameEQ = name + mashiro_option.cookie_version_control + "=",
        ca = document.cookie.split(';');
    for (let i = 0; i < ca.length; i++) {
        let c = ca[i];
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
            ele.src = 'https://sdn.geekzu.org/avatar/?s=80&d=mm&r=g';
            break;
        default:
            ele.src = 'https://view.moezx.cc/images/2018/05/13/image-404.png';
    }
}

function slideToogle(el, duration = 1000, mode = '', callback) {
    let dom = el;
    dom.status = dom.status || getComputedStyle(dom, null)['display'];
    let flag = dom.status != 'none';
    if ((flag == 1 && mode == "show") || (flag == 0 && mode == "hide")) return;
    dom.status = flag ? 'none' : 'block';
    dom.style.transition = 'height ' + duration / 1000 + 's';
    dom.style.overflow = 'hidden';
    clearTimeout(dom.tagTimer);
    dom.tagTimer = dom.tagTimer || null
    dom.style.display = 'block';
    dom.tagHeight = dom.tagHeight || dom.clientHeight + 'px'
    dom.style.display = '';
    dom.style.height = flag ? dom.tagHeight : "0px"
    setTimeout(() => {
        dom.style.height = flag ? "0px" : dom.tagHeight
    }, 0)
    dom.tagTimer = setTimeout(() => {
        dom.style.display = flag ? 'none' : 'block'
        dom.style.transition = '';
        dom.style.overflow = '';
        dom.style.height = '';
        dom.status = dom.tagHeight = null;
    }, duration)
    if (callback) callback();
}

function post_list_show_animation() {
    if (document.getElementsByTagName('article')[0]?.classList.contains("post-list-thumb")) {
        let options = {
            root: null,
            threshold: [0.66]
        },
        io = new IntersectionObserver(callback, options),
        articles = document.getElementsByClassName('post-list-thumb');

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
        for (let a = 0; a < articles.length; a++) {
            io.observe(articles[a]);
        }
    }
}
mashiro_global.font_control = new function () {
    this.change_font = function () {
        if (document.body.classList.contains("serif")){
        //if ($("body").hasClass("serif")) {
            document.body.classList.remove("serif");
            document.getElementsByClassName("control-btn-serif")[0]?.classList.remove("selected");
            document.getElementsByClassName("control-btn-sans-serif")[0]?.classList.remove("selected");
            // $("body").removeClass("serif");
            // $(".control-btn-serif").removeClass("selected");
            // $(".control-btn-sans-serif").addClass("selected");
            setCookie("font_family", "sans-serif", 30);
        } else {
            document.body.classList.add("serif");
            document.getElementsByClassName("control-btn-serif")[0]?.classList.add("selected");
            document.getElementsByClassName("control-btn-sans-serif")[0]?.classList.remove("selected");
            // $("body").addClass("serif");
            // $(".control-btn-serif").addClass("selected");
            // $(".control-btn-sans-serif").removeClass("selected");
            setCookie("font_family", "serif", 30);
            if (document.body.clientWidth <= 860) {
                addComment.createButterbar("将从网络加载字体，流量请注意");
            }
        }
    }
    this.ini = function () {
        if (document.body.clientWidth > 860) {
            if (!getCookie("font_family") || getCookie("font_family") == "serif")
            document.body.classList.add("serif");
                // $("body").addClass("serif");
        }
        if (getCookie("font_family") == "sans-serif") {
            document.body.classList.remove("sans-serif");
            document.getElementsByClassName("control-btn-serif")[0]?.classList.remove("selected");
            document.getElementsByClassName("control-btn-sans-serif")[0]?.classList.add("selected");
            // $("body").removeClass("sans-serif");
            // $(".control-btn-serif").removeClass("selected");
            // $(".control-btn-sans-serif").addClass("selected");
        }
    }
}
mashiro_global.font_control.ini();

function code_highlight_style() {
    let pre = document.getElementsByTagName("pre");
    let code = document.querySelectorAll("pre code");
    function gen_top_bar(i) {
        let attributes = {
            'autocomplete': 'off',
            'autocorrect': 'off',
            'autocapitalize': 'off',
            'spellcheck': 'false',
            'contenteditable': 'false',
            'design': 'by Mashiro'
        },
        ele_name = pre[i]?.children[0]?.className,
             lang = ele_name.substr(0, ele_name.indexOf(" ")).replace('language-', ''),
             code_a = code[i];
             if (lang.toLowerCase() == "hljs") lang = code_a.className.replace('hljs', '') ? code_a.className.replace('hljs', '') : "text";
        pre[i].classList.add("highlight-wrap");
        for (let t in attributes) {
            pre[i].setAttribute(t,attributes[t]);
        }
        code_a.setAttribute('data-rel', lang.toUpperCase());
    }
    for (let i = 0; i < code.length; i++) {
        hljs.highlightBlock(code[i]);
    }
    // code.forEach((block) => {
    //     hljs.highlightBlock(block);
    // })
    for (let i = 0; i < pre.length; i++) {
        gen_top_bar(i);
    }
    hljs.initLineNumbersOnLoad();
    document.getElementsByClassName("entry-content")[0]?.addEventListener("click",function(e){
        if (!e.target.classList.contains("highlight-wrap"))return;
        e.target.classList.toggle("code-block-fullscreen");
        document.getElementsByTagName("html")[0].classList.toggle('code-block-fullscreen-html-scroll');
    })
}
try {
    code_highlight_style();
} catch (e) {}

if (Poi.reply_link_version == 'new') {
    document.getElementsByClassName("comments-main")[0]?.addEventListener("click", function (e) {
        if (e.target.classList.contains("comment-reply-link")) {
            e.preventDefault();
            e.stopPropagation();
            let data_commentid = e.target.getAttribute("data-commentid");
            addComment.moveForm("comment-" + data_commentid, data_commentid, "respond", this.getAttribute("data-postid"));
        }
    })
    // $('body').on('click', '.comment-reply-link', function () {
    //     addComment.moveForm("comment-" + $(this).attr('data-commentid'), $(this).attr('data-commentid'), "respond", $(this).attr('data-postid'));
    //     return false;
    // });
}
let ready = function(fn){
    if (typeof fn !== 'function') return;
    if (document.readyState==='complete') {
        return fn();
    }
    document.addEventListener('DOMContentLoaded', fn, false);
};

function attach_image() {
    let cached = document.getElementsByClassName("insert-image-tips")[0],
        upload_img = document.getElementById('upload-img-file');
    upload_img?.addEventListener("change", (function () {
        if (this.files.length > 10) {
            addComment.createButterbar("每次上传上限为10张.<br>10 files max per request.");
            return 0;
        }
        for (let i = 0; i < this.files.length; i++) {
            if (this.files[i].size >= 5242880) {
                alert('图片上传大小限制为5 MB.\n5 MB max per file.\n\n「' + this.files[i].name + '」\n\n这张图太大啦~请重新上传噢！\nThis image is too large~Please reupload!');
                return;
            }
        }
        for (let i = 0; i < this.files.length; i++) {
            let f = this.files[i],
                formData = new FormData(),
                xhr = new XMLHttpRequest();
            formData.append('cmt_img_file', f);
            xhr.addEventListener('loadstart', function () {
                cached.innerHTML = '<i class="fa fa-spinner rotating" aria-hidden="true"></i>';
                addComment.createButterbar("上传中...<br>Uploading...");
            });
            xhr.open("POST", Poi.api + 'sakura/v1/image/upload?_wpnonce=' + Poi.nonce, true);
            xhr.send(formData);
            xhr.onreadystatechange = function () {
                if (xhr.readyState == 4 && (xhr.status == 200 || xhr.status == 304)) {
                    cached.innerHTML = '<i class="fa fa-check" aria-hidden="true"></i>';
                    setTimeout(function () {
                        cached.innerHTML = '<i class="fa fa-picture-o" aria-hidden="true"></i>';
                    }, 1000);
                    let res = JSON.parse(xhr.responseText);
                    if (res.status == 200) {
                        let get_the_url = res.proxy;
                        document.getElementById("upload-img-show").insertAdjacentHTML('afterend', '<img class="lazyload upload-image-preview" src="https://cdn.jsdelivr.net/gh/Fuukei/Public_Repository@latest/vision/theme/colorful/load/inload.svg" data-src="' + get_the_url + '" onclick="window.open(\'' + get_the_url + '\')" onerror="imgError(this)" />');
                        lazyload();
                        addComment.createButterbar("图片上传成功~<br>Uploaded successfully~");
                        grin(get_the_url, type = 'Img');
                    } else {
                        addComment.createButterbar("上传失败！<br>Uploaded failed!<br> 文件名/Filename: " + f.name + "<br>code: " + res.status + "<br>" + res.message, 3000);
                    }
                } else if (xhr.readyState == 4) {
                    cached.innerHTML = '<i class="fa fa-times" aria-hidden="true" style="color:red"></i>';
                    alert("上传失败，请重试.\nUpload failed, please try again.");
                    setTimeout(function () {
                        cached.innerHTML = '<i class="fa fa-picture-o" aria-hidden="true"></i>';
                    }, 1000);
                }
            }
        };
    }));
}

// function attach_image() {
//     var cached = $('.insert-image-tips');
//     $('#upload-img-file').change(function () {
//         if (this.files.length > 10) {
//             addComment.createButterbar("每次上传上限为10张.<br>10 files max per request.");
//             return 0;
//         }
//         for (i = 0; i < this.files.length; i++) {
//             if (this.files[i].size >= 5242880) {
//                 alert('图片上传大小限制为5 MB.\n5 MB max per file.\n\n「' + this.files[i].name + '」\n\n这张图太大啦~\nThis image is too large~');
//             }
//         }
//         for (var i = 0; i < this.files.length; i++) {
//             var f = this.files[i];
//             var formData = new FormData();
//             formData.append('cmt_img_file', f);
//             $.ajax({
//                 url: Poi.api + 'sakura/v1/image/upload?_wpnonce=' + Poi.nonce,
//                 type: 'POST',
//                 processData: false,
//                 contentType: false,
//                 data: formData,
//                 beforeSend: function (xhr) {
//                     cached.html('<i class="fa fa-spinner rotating" aria-hidden="true"></i>');
//                     addComment.createButterbar("上传中...<br>Uploading...");
//                 },
//                 success: function (res) {
//                     cached.html('<i class="fa fa-check" aria-hidden="true"></i>');
//                     setTimeout(function () {
//                         cached.html('<i class="fa fa-picture-o" aria-hidden="true"></i>');
//                     }, 1000);
//                     if (res.status == 200) {
//                         var get_the_url = res.proxy;
//                         $('#upload-img-show').append('<img class="lazyload upload-image-preview" src="https://cdn.jsdelivr.net/gh/Fuukei/Public_Repository@latest/vision/theme/colorful/load/inload.svg" data-src="' + get_the_url + '" onclick="window.open(\'' + get_the_url + '\')" onerror="imgError(this)" />');
//                         lazyload();
//                         addComment.createButterbar("图片上传成功~<br>Uploaded successfully~");
//                         grin(get_the_url, type = 'Img');
//                     } else {
//                         addComment.createButterbar("上传失败！<br>Uploaded failed!<br> 文件名/Filename: "+f.name+"<br>code: "+res.status+"<br>"+res.message, 3000);
//                     }
//                 },
//                 error: function (jqXHR, textStatus, errorThrown) {
//                     cached.html('<i class="fa fa-times" aria-hidden="true" style="color:red"></i>');
//                     alert("上传失败，请重试.\nUpload failed, please try again.");
//                     setTimeout(function () {
//                         cached.html('<i class="fa fa-picture-o" aria-hidden="true"></i>');
//                     }, 1000);
//                     // console.info(jqXHR.responseText);
//                     // console.info(jqXHR.status);
//                     // console.info(jqXHR.readyState);
//                     // console.info(jqXHR.statusText);
//                     // console.info(textStatus);
//                     // console.info(errorThrown);
//                 }
//             })
//         }
//     });
// }

function clean_upload_images() {
    document.getElementById("upload-img-show").innerHTML = '';
}

// function clean_upload_images() {
//     $('#upload-img-show').html('');
// }
function add_upload_tips() {
    let form_subit = document.querySelector('.form-submit #submit');
    if (form_subit == null) return;
    form_subit.insertAdjacentHTML('afterend', '<div class="insert-image-tips popup"><i class="fa fa-picture-o" aria-hidden="true"></i><span class="insert-img-popuptext" id="uploadTipPopup">上传图片</span></div><input id="upload-img-file" type="file" accept="image/*" multiple="multiple" class="insert-image-button">');
    attach_image();
    let file_subit = document.getElementById('upload-img-file'),
        hover = document.getElementsByClassName('insert-image-tips')[0],
        Tip = document.getElementById('uploadTipPopup');
    file_subit?.addEventListener("mouseenter", function () {
        hover.classList.toggle('insert-image-tips-hover');
        Tip.classList.toggle('show');
    });
    file_subit?.addEventListener("mouseleave", function () {
        hover.classList.toggle('insert-image-tips-hover');
        Tip.classList.toggle('show');
    });
}

// function add_upload_tips() {
//     $('<div class="insert-image-tips popup"><i class="fa fa-picture-o" aria-hidden="true"></i><span class="insert-img-popuptext" id="uploadTipPopup">上传图片</span></div><input id="upload-img-file" type="file" accept="image/*" multiple="multiple" class="insert-image-button">').insertAfter($(".form-submit #submit"));
//     attach_image();
//     $("#upload-img-file").hover(function () {
//         $(".insert-image-tips").addClass("insert-image-tips-hover");
//         $("#uploadTipPopup").addClass("show");
//     }, function () {
//         $(".insert-image-tips").removeClass("insert-image-tips-hover");
//         $("#uploadTipPopup").removeClass("show");
//     });
// }

function click_to_view_image() {
    let comment_inline = document.getElementsByClassName('comment_inline_img');
    if (comment_inline.length == 0) return;
    document.getElementsByClassName("comments-main")[0]?.addEventListener("click", function (e) {
        if (e.target.classList.contains("comment_inline_img")) {
            let temp_url = e.target.src;
            window.open(temp_url);
        }
    })
}
click_to_view_image();

// function click_to_view_image() {
//     $(".comment_inline_img").click(function () {
//         var temp_url = this.src;
//         window.open(temp_url);
//     });
// }
// click_to_view_image();

function original_emoji_click() {
    document.querySelector(".menhera-container")?.addEventListener("click", function (e) {
        if (e.target.classList.contains("emoji-item")) {
            grin(e.target.innerText, "custom", "`", "` ");
        }
    })
    // $(".emoji-item").click(function () {
    //     grin($(this).text(), type = "custom", before = "`", after = "` ");
    // });
}
original_emoji_click();

// function showPopup(ele) {
//     var popup = ele.querySelector("#thePopup");
//     popup.classList.toggle("show");
// }

function cmt_showPopup(ele) {
    let popup = ele.querySelector("#thePopup");
    popup.classList.add("show");
    ele.querySelector("input").onblur = ()=>{
        popup.classList.remove("show");
    }
    // var popup = $(ele).find("#thePopup");
    // popup.addClass("show");
    // $(ele).find("input").blur(function () {
    //     popup.removeClass("show");
    // });
}

function scrollBar() {
    if (document.body.clientWidth > 860) {
        window.addEventListener("scroll",()=>{
            let s = document.documentElement.scrollTop || document.body.scrollTop,
            a = document.documentElement.scrollHeight || document.body.scrollHeight,
            b = window.innerHeight, c,
            result = parseInt(s / (a - b) * 100),
            cached = document.getElementById('bar');
        cached.style.width = result + "%";
        switch (true) {
            case (result <= 19): c = '#cccccc'; break;
            case (result <= 39): c = '#50bcb6'; break;
            case (result <= 59): c = '#85c440'; break;
            case (result <= 79): c = '#f2b63c'; break;
            case (result <= 99): c = '#FF0000'; break;
            case (result == 100): c = '#5aaadb'; break;
            default:c = "orange";
        }
        cached.style.background = c;
        let f = document.querySelector(".toc-container");
        if (f != null) {
            f.style.height = document.querySelector(".site-content")?.getBoundingClientRect(outerHeight)["height"] + "px";
        }
        document.querySelector(".skin-menu")?.classList.remove("show");
        })
        // $(window).scroll(function () {
        //     var s = $(window).scrollTop(),
        //         a = $(document).height(),
        //         b = $(window).height(),
        //         result = parseInt(s / (a - b) * 100),
        //         cached = $("#bar");
        //     cached.css("width", result + "%");
        //     if (false) {
        //         if (result >= 0 && result <= 19)
        //             cached.css("background", "#cccccc");
        //         if (result >= 20 && result <= 39)
        //             cached.css("background", "#50bcb6");
        //         if (result >= 40 && result <= 59)
        //             cached.css("background", "#85c440");
        //         if (result >= 60 && result <= 79)
        //             cached.css("background", "#f2b63c");
        //         if (result >= 80 && result <= 99)
        //             cached.css("background", "#FF0000");
        //         if (result == 100)
        //             cached.css("background", "#5aaadb");
        //     } else {
        //         cached.css("background", "orange");
        //     }
        //     $(".toc-container").css("height", $(".site-content").outerHeight());
        //     $(".skin-menu").removeClass('show');
        // });
    }
}

function checkskinSecter() {
    if (mashiro_global.variables.skinSecter === false) {
        let pattern = document.querySelector(".pattern-center"),
            headertop = document.querySelector(".headertop-bar");
        pattern?.classList.remove("pattern-center");
        pattern?.classList.add("headertop-bar-sakura");
        headertop?.classList.remove("headertop-bar");
        headertop?.classList.add("headertop-bar-sakura");
        //$(".pattern-center").removeClass('pattern-center').addClass('pattern-center-sakura');
        //$(".headertop-bar").removeClass('headertop-bar').addClass('headertop-bar-sakura');
    } else {
        let pattern = document.querySelector(".pattern-center-sakura"),
            headertop = document.querySelector(".headertop-bar-sakura");
        pattern?.classList.remove("pattern-center-sakura");
        pattern?.classList.add("'pattern-center");
        headertop?.classList.remove("headertop-bar-sakura");
        headertop?.classList.add("headertop-bar");
        // $(".pattern-center-sakura").removeClass('pattern-center-sakura').addClass('pattern-center');
        // $(".headertop-bar-sakura").removeClass('headertop-bar-sakura').addClass('headertop-bar');
    }
}

function checkBgImgCookie() {
    let bgurl = getCookie("bgImgSetting");
    if (!bgurl) {
        document.getElementById("white-bg").click();
        //$("#white-bg").click();
    } else {
        document.getElementById(bgurl).click();
        //$("#" + bgurl).click();
    }
}
function checkDarkModeCookie() {
    let dark = getCookie("dark"),
        today = new Date()
        cWidth = document.body.clientWidth;
    if (!dark) {
        if ((today.getHours() > 21 || today.getHours() < 7) && mashiro_option.darkmode) {
            setTimeout(function () {
                document.getElementById("dark-bg").click();
                //$("#dark-bg").click();
            }, 100);
            console.log('夜间模式开启');
        } else {
            if (cWidth > 860) {
                setTimeout(function () {
                    checkBgImgCookie();
                }, 100);
                console.log('夜间模式关闭');
            } else {
                document.getElementsByTagName("html")[0].style.background = "unset";
                document.body.classList.remove("dark");
                document.getElementById("moblieDarkLight").innerHTML='<i class="fa fa-moon-o" aria-hidden="true"></i>';
                //$("html").css("background", "unset");
                //$("body").removeClass("dark");
                //$("#moblieDarkLight").html('<i class="fa fa-moon-o" aria-hidden="true"></i>');
                setCookie("dark", "0", 0.33);
            }
        }
    } else {
        if (dark == '1' && (today.getHours() >= 22 || today.getHours() <= 6) && mashiro_option.darkmode) {
            setTimeout(function () {
                document.getElementById("dark-bg").click();
                // $("#dark-bg").click();
            }, 100);
            console.log('夜间模式开启');
        } else if (dark == '0' || today.getHours() < 22 || today.getHours() > 6) {
            if (cWidth > 860) {
                setTimeout(function () {
                    checkBgImgCookie();
                }, 100);
                console.log('夜间模式关闭');
            } else {
                document.getElementsByTagName("html")[0].style.background = "unset";
                document.body.classList.remove("dark");
                document.getElementById("moblieDarkLight").innerHTML='<i class="fa fa-moon-o" aria-hidden="true"></i>';
                // $("html").css("background", "unset");
                // $("body").removeClass("dark");
                //$("#moblieDarkLight").html('<i class="fa fa-moon-o" aria-hidden="true"></i>');
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
    if (document.body.classList.contains("dark")){
        document.getElementsByTagName("html")[0].style.background = "unset";
        document.body.classList.remove("dark");
        document.getElementById("moblieDarkLight").innerHTML='<i class="fa fa-moon-o" aria-hidden="true"></i>';
        setCookie("dark", "0", 0.33);
    } else {
        document.getElementsByTagName("html")[0].style.background = "#333333";
        document.getElementById("moblieDarkLight").innerHTML='<i class="fa fa-sun-o" aria-hidden="true"></i>';
        document.body.classList.add("dark");
        setCookie("dark", "1", 0.33);
    }
    // if ($("body").hasClass("dark")) {
    //     $("html").css("background", "unset");
    //     $("body").removeClass("dark");
    //     $("#moblieDarkLight").html('<i class="fa fa-moon-o" aria-hidden="true"></i>');
    //     setCookie("dark", "0", 0.33);
    // } else {
    //     $("html").css("background", "#333333");
    //     $("#moblieDarkLight").html('<i class="fa fa-sun-o" aria-hidden="true"></i>');
    //     $("body").addClass("dark");
    //     setCookie("dark", "1", 0.33);
    // }
}

function no_right_click() {
    document.getElementById("primary")?.addEventListener("contextmenu", function (e) {
        if (e.target.nodeName.toLowerCase() == "img") {
            e.preventDefault();
            e.stopPropagation();
        }
    })
    // $('.post-thumb img').bind('contextmenu', function (e) {
    //     return false;
    // });
}

no_right_click();

ready(function () {
    //$(document).ready(function () {
    function cover_bg() {
        if (document.body.clientWidth < 860 && mashiro_option.random_graphs_mts == true) {
            document.querySelector(".centerbg").style.backgroundImage = "url(" + mashiro_option.cover_api + "?type=mobile" + ")";
            //$(".centerbg").css("background-image", "url(" + mashiro_option.cover_api + "?type=mobile" + ")");
        } else {
            document.querySelector(".centerbg").style.backgroundImage = "url(" + mashiro_option.cover_api + ")";
            //$(".centerbg").css("background-image", "url(" + mashiro_option.cover_api + ")");
        }
    }
    cover_bg();
    let checkskin_bg = (a)=>a=="none" ? "" : a;

    function changeBG() {
        //var cached = $(".menu-list");
        let cached = document.querySelectorAll(".menu-list li");
        //     li = cached.getElementsByTagName("li");
        cached.forEach(e => {
            e.addEventListener("click",function(){

        //cached.find("li").each(function () {
            let tagid = this.id;
        //    cached.on("click", "#" + tagid, function () {
                if (tagid == "white-bg" || tagid == "dark-bg") {
                    mashiro_global.variables.skinSecter = true;
                    checkskinSecter();
                } else {
                    mashiro_global.variables.skinSecter = false;
                    checkskinSecter();
                }
                if (tagid == "dark-bg") {
                    document.getElementsByTagName("html")[0].style.background = "#333333";
                    document.getElementsByClassName("site-content")[0].style.backgroundColor = "#333333";
                    document.body.classList.add("dark");
                    // $("html").css("background", "#333333");
                    // $(".site-content").css("background-color", "#fff");
                    // $("body").addClass("dark");
                    setCookie("dark", "1", 0.33);
                } else{
                    document.getElementsByTagName("html")[0].style.background = "unset";
                    document.getElementsByClassName("site-content")[0].style.backgroundColor = "rgba(255, 255, 255, .8)";
                    document.body.classList.remove("dark");
                    // $("html").css("background", "unset");
                    // $("body").removeClass("dark");
                    // $(".site-content").css("background-color", "rgba(255, 255, 255, .8)");
                    setCookie("dark", "0", 0.33);
                    setCookie("bgImgSetting", tagid, 30);
                }
                let temp;
                switch (tagid) {
                    case "white-bg":
                        temp = mashiro_option.skin_bg0;
                        document.body.classList.remove("dynamic");
                        //$("body").css("background-image", "url(" + checkskin_bg(mashiro_option.skin_bg0) + ")");
                        //$("body").removeClass("dynamic");
                        break;
                    case "diy1-bg":
                        temp = mashiro_option.skin_bg1;
                        //$("body").css("background-image", "url(" + checkskin_bg(mashiro_option.skin_bg1) + ")");
                        break;
                    case "diy2-bg":
                        temp = mashiro_option.skin_bg2;
                        //$("body").css("background-image", "url(" + checkskin_bg(mashiro_option.skin_bg2) + ")");
                        break;
                    case "diy3-bg":
                        temp = mashiro_option.skin_bg3;
                        //$("body").css("background-image", "url(" + checkskin_bg(mashiro_option.skin_bg3) + ")");
                        break;
                    case "diy4-bg":
                        temp = mashiro_option.skin_bg4;
                        //$("body").css("background-image", "url(" + checkskin_bg(mashiro_option.skin_bg4) + ")");
                        break;
                }
                document.body.style.backgroundImage = `url(${temp})`;
                closeSkinMenu();
        });
    });
    }
    changeBG();

    function closeSkinMenu() {
        document.querySelector(".skin-menu").classList.remove("show");
        //$(".skin-menu").removeClass('show');
        setTimeout(function () {
            if (document.querySelector(".changeSkin-gear")!=null){
            document.querySelector(".changeSkin-gear").style.visibility = "visible";
            }
            //$(".changeSkin-gear").css("visibility", "visible");
        }, 300);
    }
    document.querySelector("#changskin").addEventListener("click",function(){
    // })
    // $("#changskin").click(function () {
        document.querySelector(".skin-menu").classList.toggle("show");
        //$(".skin-menu").toggleClass('show');
    }) 
    document.querySelector(".skin-menu #close-skinMenu")?.addEventListener("click",function(){
        closeSkinMenu();
        }) 
    // $(".skin-menu #close-skinMenu").click(function () {
    //     closeSkinMenu();
    // });
    add_upload_tips();
});
let bgn = 1;

function nextBG() {
    if(document.body.clientWidth < 860 && mashiro_option.random_graphs_mts == true){
        document.querySelector(".centerbg").style.backgroundImage = "url(" + mashiro_option.cover_api + "?type=mobile&" + bgn + ")";
        //$(".centerbg").css("background-image", "url(" + mashiro_option.cover_api + "?type=mobile&" + bgn + ")");
    }else{
        document.querySelector(".centerbg").style.backgroundImage = "url(" + mashiro_option.cover_api + "?" + bgn + ")";
        //$(".centerbg").css("background-image", "url(" + mashiro_option.cover_api + "?" + bgn + ")");
    }
    bgn = bgn + 1;
}

function preBG() {
    bgn = bgn - 1;
    if(document.body.clientWidth < 860 && mashiro_option.random_graphs_mts == true){
        document.querySelector(".centerbg").style.backgroundImage = "url(" + mashiro_option.cover_api + "?type=mobile&" + bgn + ")";
        //$(".centerbg").css("background-image", "url(" + mashiro_option.cover_api + "?type=mobile&" + bgn + ")");
    }else{
        document.querySelector(".centerbg").style.backgroundImage = "url(" + mashiro_option.cover_api + "?" + bgn + ")";
        //$(".centerbg").css("background-image", "url(" + mashiro_option.cover_api + "?" + bgn + ")");
    }
}
//$(document).ready(function () {
ready(function(){
    document.getElementById("bg-next").onclick = () =>{nextBG()};
    document.getElementById("bg-pre").onclick = () =>{preBG()};
    // $("#bg-next").click(function () {
    //     nextBG();
    // });
    // $("#bg-pre").click(function () {
    //     preBG();
    // });
});

function topFunction() {
    window.scrollTo({
        top: 0,
        behavior: "smooth"
    });
    // $('body,html').animate({
    //     scrollTop: 0
    // })
}

function timeSeriesReload(flag) {
    let archives = document.getElementById('archives');
    if (archives == null) return;
    let al_li = archives.getElementsByClassName('al_mon');
    if (flag == true) {
        archives.addEventListener("click", function (e) {
            if (e.target.classList.contains("al_mon")) {
                slideToogle(e.target.nextElementSibling, 500);
                e.preventDefault();
            }
        })
        lazyload();
    } else {
        (function () {
            let al_expand_collapse = document.getElementById('al_expand_collapse');
            al_expand_collapse.style.cursor = "s-resize";
            for (let i = 0; i < al_li.length; i++) {
                let a = al_li[i],
                    num = a.nextElementSibling.getElementsByTagName('li').length;
                a.style.cursor = "s-resize";
                a.querySelector('#post-num').textContent = num;
            }
            let al_post_list = archives.getElementsByClassName("al_post_list"),
                al_post_list_f = al_post_list[0];
            for (let i = 0; i < al_post_list.length; i++) {
                slideToogle(al_post_list[i], 500, 'hide', function () {
                    slideToogle(al_post_list_f, 500, 'show');
                })
            }
            archives?.addEventListener("click", function (e) {
                if (e.target.classList.contains("al_mon")) {
                    slideToogle(e.target.nextElementSibling, 500);
                    e.preventDefault();
                }
            })
            if (document.body.clientWidth > 860) {
                for (let i = 0; i < al_post_list.length; i++) {
                    let el = al_post_list[i];
                    el.parentNode.addEventListener('mouseover', function () {
                        slideToogle(el, 500, 'show');
                        return false;
                    })
                }
                if (false) {
                    for (let i = 0; i < al_post_list.length; i++) {
                        let el = al_post_list[i];
                        el.parentNode.addEventListener('mouseover', function () {
                            slideToogle(el, 500, 'hide');
                            return false;
                        })
                    }
                }
                let al_expand_collapse_click = 0;
                al_expand_collapse.addEventListener('click', function () {
                    if (al_expand_collapse_click == 0) {
                        for (let i = 0; i < al_post_list.length; i++) {
                            let el = al_post_list[i];
                            slideToogle(el, 500, 'show');
                        };
                        al_expand_collapse_click++;
                    } else if (al_expand_collapse_click == 1) {
                        for (let i = 0; i < al_post_list.length; i++) {
                            let el = al_post_list[i];
                            slideToogle(el, 500, 'hide');
                        };
                        al_expand_collapse_click--;
                    }
                });
            }
        })();
    }
}

// function timeSeriesReload(flag) {
//     var cached = $('#archives');
//     if (flag == true) {
//         cached.find('span.al_mon').click(function () {
//             $(this).next().slideToggle(400);
//             return false;
//         });
//         lazyload();
//     } else {
//         (function () {
//             $('#al_expand_collapse,#archives span.al_mon').css({
//                 cursor: "s-resize"
//             });
//             cached.find('span.al_mon').each(function () {
//                 var num = $(this).next().children('li').length;
//                 $(this).children('#post-num').text(num);
//             });
//             var $al_post_list = cached.find('ul.al_post_list'),
//                 $al_post_list_f = cached.find('ul.al_post_list:first');
//             $al_post_list.hide(1, function () {
//                 $al_post_list_f.show();
//             });
//             cached.find('span.al_mon').click(function () {
//                 $(this).next().slideToggle(400);
//                 return false;
//             });
//             if (document.body.clientWidth > 860) {
//                 cached.find('li.al_li').mouseover(function () {
//                     $(this).children('.al_post_list').show(400);
//                     return false;
//                 });
//                 if (false) {
//                     cached.find('li.al_li').mouseout(function () {
//                         $(this).children('.al_post_list').hide(400);
//                         return false;
//                     });
//                 }
//             }
//             var al_expand_collapse_click = 0;
//             $('#al_expand_collapse').click(function () {
//                 if (al_expand_collapse_click == 0) {
//                     $al_post_list.each(function(index){
//                         var $this = $(this),
//                         s = setTimeout(function() {
//                             $this.show(400);
//                         }, 50 * index);
//                     });
//                     al_expand_collapse_click++;
//                 } else if (al_expand_collapse_click == 1) {
//                     $al_post_list.each(function(index){
//                         var $this = $(this),
//                         h = setTimeout(function() {
//                             $this.hide(400);
//                         }, 50 * index);
//                     });
//                     al_expand_collapse_click--;
//                 }
//             });
//         })();
//     }
// }
timeSeriesReload();

/*视频feature*/
function coverVideo() {
    let video = addComment.I("coverVideo"),
        btn = addComment.I("coverVideo-btn");

    if (video.paused) {
        video.play();
        try {
            btn.innerHTML = '<i class="fa fa-pause" aria-hidden="true"></i>';
        } catch{};
        //console.info('play:coverVideo()');
    } else {
        video.pause();
        try {
            btn.innerHTML = '<i class="fa fa-play" aria-hidden="true"></i>';
        } catch {};
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
    let video = addComment.I('coverVideo'),
        video_src = document.getElementById("coverVideo").getAttribute("data-src");
    if (Hls.isSupported()) {
        let hls = new Hls();
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
function loadJS(url, callback) {
    let script = document.createElement("script"),
        fn = callback || function () { };
    script.type = "text/javascript";
    script.onload = function () {
        fn();
    };
    script.src = url;
    document.head.appendChild(script);
}

function coverVideoIni() {
    if (document.getElementsByTagName('video')[0]?.classList.contains('hls')) {
        if (mashiro_global.variables.has_hls) {
            loadHls();
        } else {
            //不保证可用 需测试
            loadJS("https://cdn.jsdelivr.net/gh/mashirozx/Sakura@3.3.3/cdn/js/src/16.hls.js", function () {
                loadHls();
                mashiro_global.variables.has_hls = true;
            })
        }
    }
}

// function coverVideoIni() {
//     if ($('video').hasClass('hls')) {
//         if (mashiro_global.variables.has_hls){
//             loadHls();
//         }else{
//             $.getScript("https://cdn.jsdelivr.net/gh/mashirozx/Sakura@3.3.3/cdn/js/src/16.hls.js", function(){
//                 loadHls();
//                 mashiro_global.variables.has_hls = true;
//               });
//         }
//         //console.info('ini:coverVideoIni()');
//     }
// }
function copy_code_block() {
    let ele = document.querySelectorAll("pre code");
    for (let j = 0; j < ele.length; j++) {
        ele[j].setAttribute('id', 'hljs-' + j);
        ele[j].insertAdjacentHTML('afterend', '<a class="copy-code" href="javascript:" data-clipboard-target="#hljs-' + j + '" title="拷贝代码"><i class="fa fa-clipboard" aria-hidden="true"></i>');
    };
    let clipboard = new ClipboardJS('.copy-code');
}

// function copy_code_block() {
//     $('pre code').each(function (i, block) {
//         $(block).attr({
//             id: 'hljs-' + i
//         });
//         $(this).after('<a class="copy-code" href="javascript:" data-clipboard-target="#hljs-' + i + '" title="拷贝代码"><i class="fa fa-clipboard" aria-hidden="true"></i></a>');
//     });
//     var clipboard = new ClipboardJS('.copy-code');
// }

function tableOfContentScroll(flag) {
    if (document.body.clientWidth <= 1200) {
        return;
    } else if (document.querySelector("div.have-toc") == null && document.querySelector("div.has-toc") == null) {
        let ele = document.getElementsByClassName("toc-container")[0];
        if (ele != null){
            ele.remove();
            ele = null;
        }
    //} else if ($("div").hasClass("have-toc") == false && $("div").hasClass("has-toc") == false) {
        //$(".toc-container").remove();
    } else {
        if (flag) {
            let id = 1,
            heading_fix = mashiro_option.entry_content_theme == "sakura" ? (document.querySelector("article.type-post") != null ? (document.querySelector("div.pattern-attachment-img") != null ? -75 : 200) : 375) : window.innerHeight / 2;
        let _els = document.querySelectorAll('.entry-content,.links');
            for(let i = 0;i<_els.length;i++){
                let _el = _els[i].querySelectorAll('h1,h2,h3,h4,h5');
                for(let j = 0 ;j<_el.length;j++){
                    _el[j].id = "toc-head-" + id;
                    id++;
                }
            }
        tocbot.init({
            tocSelector: '.toc',
            contentSelector: ['.entry-content', '.links'],
            headingSelector: 'h1, h2, h3, h4, h5',
            headingsOffset: heading_fix - window.innerHeight / 2,
        });
            // var id = 1,
            //     heading_fix = mashiro_option.entry_content_style == "sakurairo" ? $("article").hasClass("type-post") ? $("div").hasClass("pattern-attachment-img") ? -75 : 200 : 375 : window.innerHeight / 2;
            // $(".entry-content , .links").children("h1,h2,h3,h4,h5").each(function () {
            //     var hyphenated = "toc-head-" + id;
            //     this.id = hyphenated;
            //     id++;
            // });
            // tocbot.init({
            //     tocSelector: '.toc',
            //     contentSelector: ['.entry-content', '.links'],
            //     headingSelector: 'h1, h2, h3, h4, h5',
            //     headingsOffset: heading_fix - window.innerHeight / 2,
            // });
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
    let motionEles = [".bili", ".menhera", ".tieba"];
    for (let i=0;i<montionEles.length;i++) {
        document.querySelector(motionEles[i] + '-bar').classList.remove('on-hover');
        document.querySelector(motionEles[i] + '-container').style.display = 'none';
    }
    document.querySelector(ele + '-bar').classList.add("on-hover");
    document.querySelector(ele + '-container').style.display = 'block';
    // var motionEles = [".bili", ".menhera", ".tieba"];
    // for (var i in motionEles) {
    //     $(motionEles[i] + '-bar').removeClass("on-hover");
    //     $(motionEles[i] + '-container').css("display", "none");
    // }
    // $(ele + '-bar').addClass("on-hover");
    // $(ele + '-container').css("display", "block");
}
$('.comt-addsmilies').click(function () {
    $('.comt-smilies').toggle();
})
$('.comt-smilies a').click(function () {
    $(this).parent().hide();
})

function smileBoxToggle() {
    document.getElementById("emotion-toggle")?.addEventListener('click', function () {
        let emotion_toggle_off = document.querySelector('.emotion-toggle-off'),
            emotion_toggle_on = document.querySelector('.emotion-toggle-on'),
            emotion_box = document.querySelector('.emotion-box');
        emotion_toggle_off.classList.toggle("emotion-hide");
        emotion_toggle_on.classList.toggle("emotion-show");
        emotion_box.classList.toggle("emotion-box-show");
    })
    // $(document).ready(function () {
    //     $("#emotion-toggle").click(function () {
    //         $(".emotion-toggle-off").toggle(0);
    //         $(".emotion-toggle-on").toggle(0);
    //         $(".emotion-box").toggle(160);
    //     });
    // });
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
        let htmlData = "# 商业转载请联系作者获得授权，非商业转载请注明出处。<br>" + "# For commercial use, please contact the author for authorization. For non-commercial use, please indicate the source.<br>" + "# 协议(License)：署名-非商业性使用-相同方式共享 4.0 国际 (CC BY-NC-SA 4.0)<br>" + "# 作者(Author)：" + mashiro_option.author_name + "<br>" + "# 链接(URL)：" + window.location.href + "<br>" + "# 来源(Source)：" + mashiro_option.site_name + "<br><br>" + window.getSelection().toString().replace(/\r\n/g, "<br>");;
        let textData = "# 商业转载请联系作者获得授权，非商业转载请注明出处。\n" + "# For commercial use, please contact the author for authorization. For non-commercial use, please indicate the source.\n" + "# 协议(License)：署名-非商业性使用-相同方式共享 4.0 国际 (CC BY-NC-SA 4.0)\n" + "# 作者(Author)：" + mashiro_option.author_name + "\n" + "# 链接(URL)：" + window.location.href + "\n" + "# 来源(Source)：" + mashiro_option.site_name + "\n\n" + window.getSelection().toString().replace(/\r\n/g, "\n");
        if (event.clipboardData) {
            event.clipboardData.setData("text/html", htmlData);
            event.clipboardData.setData("text/plain", textData);
        } else if (window.clipboardData) {
            return window.clipboardData.setData("text", textData);
        }
    }
}
add_copyright();
ready(()=>{
    getqqinfo();
});

if (mashiro_option.float_player_on) {
    function aplayerF() {
        'use strict';
        let aplayers = [],
            loadMeting = function () {
                function a(a, b) {
                    let c = {
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
                        let d = {};
                        for (let e in c) {
                            let f = e.toLowerCase();
                            (a.dataset.hasOwnProperty(f) || a.dataset.hasOwnProperty(e) || null !== c[e]) && (d[e] = a.dataset[f] || a.dataset[e] || c[e], ('true' === d[e] || 'false' === d[e]) && (d[e] = 'true' == d[e]))
                        }
                        aplayers.push(new APlayer(d))
                    }
                    for (let f = 0; f < aplayers.length; f++) try {
                        aplayers[f].lrc.hide();
                    } catch (a) {
                        console.log(a)
                    }
                    let lrcTag = 1;
                    $(".aplayer.aplayer-fixed").click(function () {
                        if (lrcTag == 1) {
                            for (let f = 0; f < aplayers.length; f++) try {
                                aplayers[f].lrc.show();
                            } catch (a) {
                                console.log(a)
                            }
                        }
                        lrcTag = 2;
                    });
                    let apSwitchTag = 0;
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
        if ((is_get_by_qq == false || emailAddressFlag != emailAddress) && emailAddress != '') {
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
    let secondary = document.getElementById("secondary");
    if (document.body.clientWidth > 860) {
            let show_hide = document.querySelector(".show-hide");
            show_hide?.addEventListener("click", function () {
                secondary?.classList.toggle("active");
            });
    } else {
            secondary?.parentNode.removeChild(secondary);
    }
    // if (document.body.clientWidth > 860) {
    //     $('.show-hide').on('click', function() {
    //         $("#secondary").toggleClass("active")
    //     });
    // }else{
    //     $("#secondary").remove();
    // }
}
setTimeout(function () {
    activate_widget();
}, 100);

function load_bangumi() {
    let section = document.getElementsByTagName("section"),_flag=false;
    for (let i=0;i<section.length;i++){
        if(section[i].classList.contains("bangumi")){
            _flag = true;
        }
    }
    if(_flag){
            document.addEventListener('click', function (e) {
                let target = e.target;
                if (target === document.querySelector("#bangumi-pagination a")) {
                    let bgpa = document.querySelector("#bangumi-pagination a");
                    bgpa.classList.add("loading");
                    bgpa.textContent = "";
                    let xhr = new XMLHttpRequest();
                    xhr.open('POST', target.href + "&_wpnonce=" + Poi.nonce, true);
                    xhr.onreadystatechange = function () {
                        if (xhr.readyState == 4 && xhr.status == 200) {
                            let html = JSON.parse(xhr.responseText),
                                bfan = document.getElementById("bangumi-pagination"),
                                row = document.getElementsByClassName("row")[0];
                            bfan.parentNode.removeChild(bfan);
                            row.insertAdjacentHTML('beforeend', html);
                        } else {
                            bgpa.classList.remove("loading");
                            bgpa.innerHTML = '<i class="fa fa-exclamation-triangle" aria-hidden="true"></i> ERROR ';
                        }
                    };
                    xhr.send();
                }
            });
}
    // if ($("section").hasClass("bangumi")) {
    //     $('body').on('click', '#bangumi-pagination a', function () {
    //         $("#bangumi-pagination a").addClass("loading").text("");
    //         var xhr = new XMLHttpRequest();
    //         xhr.open('POST', this.href + "&_wpnonce=" + Poi.nonce, true);
    //         xhr.onreadystatechange = function() {
    //             if (xhr.readyState == 4 ) {
    //                 if(xhr.status == 200){
    //                     var html = JSON.parse(xhr.responseText);
    //                     $("#bangumi-pagination").remove();
    //                     $(".row").append(html);
    //                 }else{
    //                     $("#bangumi-pagination a").removeClass("loading").html('<i class="fa fa-exclamation-triangle" aria-hidden="true"></i> ERROR ');
    //                 }
    //             }
    //         };
    //         xhr.send();
    //         return false;
    //     });
    // }
}


mashiro_global.ini.normalize();
loadCSS(mashiro_option.jsdelivr_css_src);
loadCSS(mashiro_option.entry_content_style_src);
loadCSS("https://at.alicdn.com/t/font_679578_qyt5qzzavdo39pb9.css");
loadCSS("https://cdn.jsdelivr.net/npm/aplayer@1.10.1/dist/APlayer.min.css");

var home = location.href,
    s = $('#bgvideo')[0],
    Siren = {
        MN: function () {
            document.querySelector(".iconflat")?.addEventListener("click", function () {
                document.body.classList.toggle("navOpen");
                document.getElementById("main-container").classList.toggle("open");
                document.getElementById("mo-nav").classList.toggle("open");
                document.querySelector(".openNav").classList.toggle("open");
            });
            // $('.iconflat').on('click', function () {
            //     $('body').toggleClass('navOpen');
            //     $('#main-container,#mo-nav,.openNav').toggleClass('open');
            // });
        },
        MNH: function () {
            if (document.body.classList.contains("navOpen")) {
                document.body.classList.toggle("navOpen");
                document.getElementById("main-container").classList.toggle("open");
                document.getElementById("mo-nav").classList.toggle("open");
                document.querySelector(".openNav").classList.toggle("open");
            }
            // if ($('body').hasClass('navOpen')) {
            //     $('body').toggleClass('navOpen');
            //     $('#main-container,#mo-nav,.openNav').toggleClass('open');
            // }
        },
        splay: function () {
            let video_btn = document.getElementById("video-btn");
                video_btn?.classList.add("video-pause");
                video_btn?.classList.remove("video-play");
                try{
                video_btn.style.display = "";
                document.querySelector(".video-stu").style.bottom = "-100px";
                document.querySelector(".focusinfo").style.top = "-999px";
                }catch{}
            try {
                for (let i = 0; i < ap.length; i++) {
                    try {
                        ap[i].destroy()
                    } catch {}
                }
            } catch {}
            try {
                hermitInit()
            } catch { }
            s.play();
            // $('#video-btn').addClass('video-pause').removeClass('video-play').show();
            // $('.video-stu').css({
            //     "bottom": "-100px"
            // });
            // $('.focusinfo').css({
            //     "top": "-999px"
            // });
            // try {
            //     for (var i = 0; i < ap.length; i++) {
            //         try {
            //             ap[i].destroy()
            //         } catch (e) {}
            //     }
            // } catch (e) {}
            // try {
            //     hermitInit()
            // } catch (e) {}
            // s.play();
        },
        spause: function () {
                let video_btn = document.getElementById("video-btn");
                video_btn?.classList.add("video-play");
                video_btn?.classList.remove("video-pause");
                try{
                document.querySelector(".focusinfo").style.top = "49.3%";
                }catch{}
            s.pause();
            // $('#video-btn').addClass('video-play').removeClass('video-pause');
            // $('.focusinfo').css({
            //     "top": "49.3%"
            // });
            // s.pause();
        },
        liveplay: function () {
            if (s.oncanplay != undefined && document.querySelector(".haslive") != null) {
                if (document.querySelector(".videolive") != null) {
                    Siren.splay();
                }
            }
            // if (s.oncanplay != undefined && $('.haslive').length > 0) {
            //     if ($('.videolive').length > 0) {
            //         Siren.splay();
            //     }
            // }
        },
        livepause: function () {
            if (s.oncanplay != undefined && document.querySelector(".haslive") != null) {
                Siren.spause();
                let video_stu = document.getElementsByClassName("video-stu")[0];
                video_stu.style.bottom = "0px";
                video_stu.innerHTML = "已暂停 ...";
            }
            // if (s.oncanplay != undefined && $('.haslive').length > 0) {
            //     Siren.spause();
            //     $('.video-stu').css({
            //         "bottom": "0px"
            //     }).html('已暂停 ...');
            // }
        },
        addsource: function () {
            let video_stu = document.getElementsByClassName("video-stu")[0];
            video_stu.innerHTML = "正在载入视频 ...";
            video_stu.style.bottom = "0px";
            let t = Poi.movies.name.split(","),
                _t = t[Math.floor(Math.random() * t.length)],
                bgvideo = document.getElementById("bgvideo");
            bgvideo.setAttribute("src", Poi.movies.url + '/' + _t + '.mp4');
            bgvideo.setAttribute("video-name", _t);
            // $('.video-stu').html('正在载入视频 ...').css({
            //     "bottom": "0px"
            // });
            // var t = Poi.movies.name.split(","),
            //     _t = t[Math.floor(Math.random() * t.length)];
            // $('#bgvideo').attr('src', Poi.movies.url + '/' + _t + '.mp4');
            // $('#bgvideo').attr('video-name', _t);
        },
        LV: function () {
            let _btn = document.getElementById("video-btn");
            _btn.addEventListener("click", function () {
                if (this.classList.contains("loadvideo")) {
                    this.classList.add("video-pause");
                    this.classList.remove("loadvideo");
                    Siren.addsource();
                    s.oncanplay = function () {
                        Siren.splay();
                        document.getElementById("video-add").style.display = "block";
                        _btn.classList.add("videolive", "haslive");
                    }
                } else {
                    if (this.classList.contains("video-pause")) {
                        Siren.spause();
                        _btn.classList.remove("videolive");
                        document.getElementsByClassName("video-stu")[0].style.bottom = "0px";
                        document.getElementsByClassName("video-stu")[0].innerHTML = "已暂停 ...";
                    } else {
                        Siren.splay();
                        _btn.classList.add("videolive");
                    }
                }
                s.onended = function () {
                    document.getElementById("bgvideo")?.setAttribute("src", "");
                    document.getElementById("video-add").style.display = "none";
                    _btn?.classList.add("loadvideo");
                    _btn?.classList.remove("video-pause", "videolive", "haslive");
                    document.querySelector(".focusinfo").style.top = "49.3%";
                }
            });
            document.getElementById("video-add").addEventListener("click", function () {
                Siren.addsource();
            });
            // var _btn = $('#video-btn');
            // _btn.on('click', function () {
            //     if ($(this).hasClass('loadvideo')) {
            //         $(this).addClass('video-pause').removeClass('loadvideo').hide();
            //         Siren.addsource();
            //         s.oncanplay = function () {
            //             Siren.splay();
            //             $('#video-add').show();
            //             _btn.addClass('videolive').addClass('haslive');
            //         }
            //     } else {
            //         if ($(this).hasClass('video-pause')) {
            //             Siren.spause();
            //             _btn.removeClass('videolive');
            //             $('.video-stu').css({
            //                 "bottom": "0px"
            //             }).html('已暂停 ...');
            //         } else {
            //             Siren.splay();
            //             _btn.addClass('videolive');
            //         }
            //     }
            //     s.onended = function () {
            //         $('#bgvideo').attr('src', '');
            //         $('#video-add').hide();
            //         _btn.addClass('loadvideo').removeClass('video-pause').removeClass('videolive').removeClass('haslive');
            //         $('.focusinfo').css({
            //             "top": "49.3%"
            //         });
            //     }
            // });
            // $('#video-add').on('click', function () {
            //     Siren.addsource();
            // });
        },
        AH: function () {
            if (Poi.windowheight == 'auto') {
                if (document.querySelector(".main-title") != null) {
                    let _height = document.documentElement.clientHeight + "px";
                    document.getElementById("centerbg").style.height = "100vh";
                    document.getElementById("bgvideo").style.minHeight = "100vh";
                }
            } else {
                document.querySelector(".headertop")?.classList.add("headertop-bar");
            }
                // if ($('h1.main-title').length > 0) {
                //     var _height = $(window).height() + "px";
                //     $('#centerbg').css({
                //         'height': '100vh'
                //     });
                //     $('#bgvideo').css({
                //         'min-height': '100vh'
                //     });
                // }
            // } else {
            //     $('.headertop').addClass('headertop-bar');
            // }
        },
        PE: function () {
            if (document.querySelector(".headertop") != null) {
                let headertop = document.querySelector(".headertop"),
                    blank = document.querySelector(".blank");
                if (document.querySelector(".main-title") != null) {
                    try {
                        blank.style.paddingTop = "0px";
                    } catch (e) { }
                    headertop.style.height = "auto";
                    headertop.style.display = "";
                    if (Poi.movies.live == 'open') Siren.liveplay();
                } else {
                    try {
                        blank.style.paddingTop = "75px";
                    } catch (e) {}
                    headertop.style.height = "0px";
                    headertop.style.display = "none";
                    Siren.livepause();
                }
            }
            // if ($('.headertop').length > 0) {
            //     if ($('h1.main-title').length > 0) {
            //         $('.blank').css({
            //             "padding-top": "0px"
            //         });
            //         $('.headertop').css({
            //             "height": "auto"
            //         }).show();
            //         if (Poi.movies.live == 'open') Siren.liveplay();
            //     } else {
            //         $('.blank').css({
            //             "padding-top": "75px"
            //         });
            //         $('.headertop').css({
            //             "height": "0px"
            //         }).hide();
            //         Siren.livepause();
            //     }
            // }
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
            /*if (mashiro_option.baguetteBoxON) {
                baguetteBox.run('.entry-content', {
                    captions: function (element) {
                        return element.getElementsByTagName('img')[0].alt;
                    },
                    ignoreClass: 'fancybox',
                });
            }*/
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
            document.body.addEventListener('click', function (e) {
                if (e.target.parentNode == document.getElementById("comments-navi") && e.target.nodeName.toLowerCase() == "a") {
                    e.preventDefault();
                    e.stopPropagation();
                    let _this = e.target,
                        path = _this.pathname,
                        _xhr = new XMLHttpRequest();
                    _xhr.open("GET", _this.getAttribute('href'), true);
                    _xhr.responseType = "document";
                    _xhr.onloadstart = () => {
                        let comments_navi = document.getElementById("comments-navi"),
                            commentwrap = document.querySelector("ul.commentwrap"),
                            loading_comments = document.getElementById("loading-comments"),
                            comments_list = document.getElementById("comments-list-title");
                        comments_navi.parentNode.removeChild(comments_navi);
                        commentwrap.parentNode.removeChild(commentwrap);
                        loading_comments.style.display="block";
                        slideToogle(loading_comments, 500, "show");
                        window.scrollTo({
                            top: comments_list.getBoundingClientRect().top + window.pageYOffset - comments_list.clientTop - 65,
                            behavior: "smooth"
                        });
                    }
                    _xhr.onreadystatechange = function () {
                        if (_xhr.readyState == 4 && _xhr.status == 200) {
                            let json = _xhr.response,
                                result = json.querySelector("ul.commentwrap"),
                                nextlink = json.getElementById("comments-navi"),
                                loading_comments = document.getElementById("loading-comments");
                            slideToogle(loading_comments, 200, "hide");
                            document.getElementById("loading-comments").insertAdjacentHTML('afterend', result.outerHTML);
                            document.querySelector("ul.commentwrap").insertAdjacentHTML('afterend', nextlink.outerHTML);
                            lazyload();
                            if (window.gtag) {
                                gtag('config', Poi.google_analytics_id, {
                                    'page_path': path
                                });
                            }
                            code_highlight_style();
                            click_to_view_image();
                            let commentwrap = document.querySelector("ul.commentwrap");
                            window.scrollTo({
                                top: commentwrap?.getBoundingClientRect().top+ window.pageYOffset - commentwrap?.clientTop - 200,
                                behavior: "smooth"
                            });
                        }
                    }
                    _xhr.send();
                }
            });
            // $body = (window.opera) ? (document.compatMode == "CSS1Compat" ? $('html') : $('body')) : $('html,body');
            // $('body').on('click', '#comments-navi a', function (e) {
            //     e.preventDefault();
            //     var path = $(this)[0].pathname;
            //     $.ajax({
            //         type: "GET",
            //         url: $(this).attr('href'),
            //         beforeSend: function () {
            //             $('#comments-navi').remove();
            //             $('ul.commentwrap').remove();
            //             $('#loading-comments').slideDown();
            //             $body.animate({
            //                 scrollTop: $('#comments-list-title').offset().top - 65
            //             }, 800);
            //         },
            //         dataType: "html",
            //         success: function (out) {
            //             result = $(out).find('ul.commentwrap');
            //             nextlink = $(out).find('#comments-navi');
            //             $('#loading-comments').slideUp('fast');
            //             $('#loading-comments').after(result.fadeIn(500));
            //             $('ul.commentwrap').after(nextlink);
            //             lazyload();
            //             if (window.gtag) {
            //                 gtag('config', Poi.google_analytics_id, {
            //                     'page_path': path
            //                 });
            //             }
            //             code_highlight_style();
            //             click_to_view_image();
            //         }
            //     });
            // });
        },
        IA: function () {
            POWERMODE.colorful = true;
            POWERMODE.shake = false;
            document.body.addEventListener('input', POWERMODE)
        },
        GT: function () {
            let mb_to_top = document.querySelector("#moblieGoTop"),
                changskin = document.querySelector("#changskin");
            window.addEventListener("scroll",()=>{
                let scroll = document.documentElement.scrollTop || document.body.scrollTop;
                if(scroll > 20){
                    mb_to_top.style.transform = "scale(1)";
                    changskin.style.transform = "scale(1)";
                }else{
                    mb_to_top.style.transform = "scale(0)";
                    changskin.style.transform = "scale(0)";
                }
            })
            mb_to_top.onclick = function() {
                topFunction();
            }
        }
    }
    if (Poi.pjax) {
        new Pjax({
            selectors: ["#page","title",".footer-device"],
            elements: [
              "a:not([target='_top']):not(.comment-reply-link):not(#pagination a):not(#comments-navi a):not(.user-menu-option a):not(.header-user-avatar a):not(.emoji-item)",
              ".search-form",
              ".s-search",
            ],
            timeout: 8000,
            history: true,
            cacheBust: false,
          });
          document.addEventListener("pjax:send",()=>{
            let normal = document.getElementsByClassName("normal-cover-video");
            if(normal.length > 0){
                for(let a;a<normal.length;a++){
                    normal[a].pause();
                    normal[a].src = '';
                    normal[a].load = '';
                }
            }
            document.getElementById("bar").style.width = "0%";
            if (mashiro_option.NProgressON) NProgress.start();
            Siren.MNH();
        });
        document.addEventListener("pjax:complete",function(){
                Siren.AH();
                Siren.PE();
                Siren.CE();
                //Siren.XLS();
                if (mashiro_option.NProgressON) NProgress.done();
                mashiro_global.ini.pjax();
                let loading = document.getElementById("loading");
                    loading?.classList.add("hide");
                    loading?.classList.remove("show");
                if (Poi.codelamp == 'open') {
                    self.Prism.highlightAll(event)
                };
                if (document.querySelector(".js-search.is-visible")) {
                    document.getElementsByClassName("js-toggle-search")[0]?.classList.toggle("is-active");
                    document.getElementsByClassName("js-search")[0]?.classList.toggle("is-visible");
                    document.getElementsByTagName("html")[0].style.overflowY = "unset";
                }
            });
            document.addEventListener("pjax:success",function(){
                if (window.gtag){
                    gtag('config', Poi.google_analytics_id, {
                        'page_path': window.location.pathname
                    });
                }
            });
        // $(document).pjax('a[target!=_top]', '#page', {
        //     fragment: '#page',
        //     timeout: 8000,
        // }).on('pjax:beforeSend', () => { //离开页面停止播放
        //     $('.normal-cover-video').each(function () {
        //         this.pause();
        //         this.src = '';
        //         this.load = '';
        //     });
        // }).on('pjax:send', function () {
        //     $("#bar").css("width", "0%");
        //     if (mashiro_option.NProgressON) NProgress.start();
        //     Siren.MNH();
        // }).on('pjax:complete', function () {
        //     Siren.AH();
        //     Siren.PE();
        //     Siren.CE();
        //     if (mashiro_option.NProgressON) NProgress.done();
        //     mashiro_global.ini.pjax();
        //     $("#loading").fadeOut(500);
        //     if (Poi.codelamp == 'open') {
        //         self.Prism.highlightAll(event)
        //     };
        // }).on('pjax:end', function() {
        //     if (window.gtag){
        //         gtag('config', Poi.google_analytics_id, {
        //             'page_path': window.location.pathname
        //         });
        //     }
        // }).on('submit', '.search-form,.s-search', function (event) {
        //     event.preventDefault();
        //     $.pjax.submit(event, '#page', {
        //         fragment: '#page',
        //         timeout: 8000,
        //     });
        //     if ($('.js-search.is-visible').length > 0) {
        //         $('.js-toggle-search').toggleClass('is-active');
        //         $('.js-search').toggleClass('is-visible');
        //         $('html').css('overflow-y', 'unset');
        //     }
        // });
        window.addEventListener('popstate', function (e) {
            Siren.AH();
            Siren.PE();
            Siren.CE();
            timeSeriesReload(true);
            post_list_show_animation();
        }, false);
    }
ready(function () {
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
    console.log("%c Mashiro %c", "background:#24272A; color:#ffffff", "", "https://2heng.xin/");
    console.log("%c Github %c", "background:#24272A; color:#ffffff", "", "https://github.com/mashirozx");
});
let isWebkit = navigator.userAgent.toLowerCase().indexOf('webkit') > -1,
    isOpera = navigator.userAgent.toLowerCase().indexOf('opera') > -1,
    isIe = navigator.userAgent.toLowerCase().indexOf('msie') > -1;
if ((isWebkit || isOpera || isIe) && document.getElementById && window.addEventListener) {
    window.addEventListener('hashchange', function () {
        let id = location.hash.substring(1),
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
    let coverOffset = document.getElementById("content").getBoundingClientRect().top + window.pageYOffset;
    //var coverOffset = $('#content').offset().top;
    window.scrollTo({
        top: coverOffset,
        behavior: "smooth"
    });
    // $('html,body').animate({
    //     scrollTop: coverOffset
    // },
    // 600);
}

window.onload = function() {
    document.getElementsByTagName("html")[0].style.overflowY = "unset";
    let preload = document.getElementById("preload");
    if(!preload) return;
    preload.classList.add('hide');
    preload.classList.remove('show');
    setTimeout('preload.parentNode.removeChild(preload)',666);
    //$("html").css('overflow-y', 'unset');
    //$("#preload").fadeOut();
    //setTimeout('$("#preload").remove()', 666);
}



function web_audio() {
    if (mashiro_option.audio) {
    let _was = (a,b)=>a.classList.contains(b);
    window.AudioContext = window.AudioContext || window.webkitAudioContext,
        function () {
            if (window.AudioContext) {
                let e = new AudioContext,
                    t = "880 987 1046 987 1046 1318 987 659 659 880 784 880 1046 784 659 659 698 659 698 1046 659 1046 1046 1046 987 698 698 987 987 880 987 1046 987 1046 1318 987 659 659 880 784 880 1046 784 659 698 1046 987 1046 1174 1174 1174 1046 1046 880 987 784 880 1046 1174 1318 1174 1318 1567 1046 987 1046 1318 1318 1174 784 784 880 1046 987 1174 1046 784 784 1396 1318 1174 659 1318 1046 1318 1760 1567 1567 1318 1174 1046 1046 1174 1046 1174 1567 1318 1318 1760 1567 1318 1174 1046 1046 1174 1046 1174 987 880 880 987 880".split(" "),//天空之城
                    /*t = "329.628 329.628 349.228 391.995 391.995 349.228 329.628 293.665 261.626 261.626 293.665 329.628 329.628 293.665 293.665 329.628 329.628 349.228 391.995 391.995 349.228 329.628 293.665 261.626 261.626 293.665 329.628 293.665 261.626 261.626 293.665 293.665 329.628 261.626 293.665 329.628 349.228 329.628 261.626 293.665 329.628 349.228 329.628 293.665 261.626 293.665 195.998 329.628 329.628 349.228 391.995 391.995 349.228 329.628 293.665 261.626 261.626 293.665 329.628 293.665 261.626 261.626".split(" "),欢乐颂*/
                    i = 0,
                    o = 1,
                    a = "♪ ♩ ♫ ♬ ♭ € § ¶ ♯".split(" "),
                    n = !1
                document.body.addEventListener("mouseout",function(z){
                    s = z.target;
                    if(_was(s,"site-title") || _was(s,"site-branding") || _was(s,"searchbox") || _was(s,"changeSkin-gear") || _was(s.parentNode,"menu-list") || s.id=="moblieGoTop"){
                   // console.log(s);
                // $("site-title, #moblieGoTop, .site-branding, .searchbox, .changeSkin-gear, .menu-list>li#star-bg").mouseenter(function (s) {
                    let r = t[i]
                    r || (i = 0, r = t[i]), i += o
                    let c = e.createOscillator(),
                        l = e.createGain();
                    if (c.connect(l), l.connect(e.destination), c.type = "sine", c.frequency.value = r, l.gain.setValueAtTime(0, e.currentTime), l.gain.linearRampToValueAtTime(1, e.currentTime + .01), c.start(e.currentTime), l.gain.exponentialRampToValueAtTime(.001, e.currentTime + 1), c.stop(e.currentTime + 1), n = !0) {
                        let d = Math.round(7 * Math.random()),
                            dom = document.createElement("b");
                            dom.textContent = a[d],
                            //u = $("<b/>").text(a[d]),
                            h = z.pageX,
                            p = z.pageY - 5;
                           // console.log(u);
                            dom.style.zIndex = "99999";
                            dom.style.top = p - 100 + "px";
                            dom.style.left = h + "px";
                            dom.style.position = "absolute";
                            dom.style.color = "#FF6EB4";
                        // u.css({
                        //     "z-index": 99999,
                        //     top: p - 20,
                        //     left: h,
                        //     position: "absolute",
                        //     color: "#FF6EB4"
                        // }), 
                        document.body.appendChild(dom);
                        dom.animate([
                            {top: p +"px"},
                            {opacity: 0}
                        ],{
                            duration:1500
                        })
                        setTimeout(()=>{
                            dom.remove();
                        },1500)
                        // function () {
                        //     u.remove()
                        // }), 
                        z.stopPropagation()
                    }
                    n = !1
                }})
            }
        }()
}
}
