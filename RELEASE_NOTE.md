# 版本发布记录
## 2.3 ツバメ　燕
### 重要特性
#### 前台登陆
* **登陆界面:** 超过3秒登陆界面的预加载动画会自行消失 ([78e307d](https://github.com/mirai-mamori/Sakurairo/commit/78e307dd40e51437a07e1e7a1aae1fea36740408)), closes [#357](https://github.com/mirai-mamori/Sakurairo/issues/357)
* **登陆界面:** *重大更改* 使用body来显示登陆界面背景；使登陆面板更加可读与美观；移动不变的样式定义 ([3777cee](https://github.com/mirai-mamori/Sakurairo/commit/3777cee0852bfe2edc46a202bbdf20536692f09c))
#### 视频支持
* 支持使用视频作为文章封面缩略图 ([f6f5257](https://github.com/mirai-mamori/Sakurairo/commit/f6f5257d3f41c842f5eae29d1839adbdf1333084))
* **video:** 完善懒加载支持 ([786aecd](https://github.com/Fuukei/Sakurairo_Scripts/commit/786aecd3e4a30186065f65e77efb2628cafb5c3a))
* **封面视频:** 选项cover_video_loop实际效果与描述不一致 ([6bf46ce](https://github.com/mirai-mamori/Sakurairo/commit/6bf46ce89ed0ffb1deb2e1bbc1d338897d4b9d43))
* **文章封面视频:** 静音按钮 ([bd54d8e](https://github.com/Fuukei/Sakurairo_Scripts/commit/bd54d8e63338590188fe103a2f05e92165f347c3))
#### 新灯箱插件LightGallery
使用方法请参照[官方文档](https://www.lightgalleryjs.com/docs/getting-started/)

相关commit：[bf17260](https://github.com/Fuukei/Sakurairo_Scripts/commit/bf172602d990549aa5847457468eacee83393802)
#### \<meta>标签[theme-color](https://developer.mozilla.org/en-US/docs/Web/HTML/Element/meta/name/theme-color) 支持
在支持的浏览器（如[Safari 15](https://developer.apple.com/documentation/safari-release-notes/safari-15-release-notes#HTML))上，标题栏颜色将随前台背景图的主题颜色变化（使用[@kotorik/palette](https://www.npmjs.com/package/@kotorik/palette)取色）

从前台背景图取色失败时将使用主题设置的颜色值。
* meta **theme-color** 使用主题设置的颜色值 ([6e50fcb](https://github.com/mirai-mamori/Sakurairo/commit/6e50fcb43ec8b406646db6fda2b6822db9e984d0))

#### 体验改进
* 可以在HTML标签中加入```data-no-pjax```属性来避免pjax加载。([7290f23](https://github.com/Fuukei/Sakurairo_Scripts/commit/7290f23a0f1e8308086a31af8225347698ae5b76))
* 添加事件ajax_post_loaded ([2016881](https://github.com/Fuukei/Sakurairo_Scripts/commit/2016881d1dc1f9df7410604bcd9d64d38ffd61a1))
* *重大更改* site-header z-index过高 ([90a48a0](https://github.com/mirai-mamori/Sakurairo/commit/90a48a07a38af93cb2a67ff90e7dea1d1b9d41cf))
* *重大更改* 登录界面不再加载jQuery ([8bf5ef8](https://github.com/mirai-mamori/Sakurairo/commit/8bf5ef8c30867fb7dc40086e6fc145d15fcb2bcb))
* pjax下登录/登出操作体验问题 ([6ea0daf](https://github.com/mirai-mamori/Sakurairo/commit/6ea0dafd47d4563eda56a69da15889c589142a8a))
* pjax下登录跳转网址不更新 ([8c1c8ac](https://github.com/Fuukei/Sakurairo_Scripts/commit/8c1c8ac4d694e438749b5bb76ebbdba70e85271c))
* **设置项** 在<head>注入HTML代码 ([1b9eaa7](https://github.com/mirai-mamori/Sakurairo/commit/1b9eaa7723391e7a2e3e6aa3151c87f86501567e))

***
  
*以下是commit信息*
  
### 错误修复

* 潜在的逻辑错误 ([cc63233](https://github.com/mirai-mamori/Sakurairo/commit/cc6323315b18a07daf190e0354d2d51b2f32d077))
* 未提供选项"gfonts_api"的默认值导致错误 ([ceae39a](https://github.com/mirai-mamori/Sakurairo/commit/ceae39a6133ce703b4aed15983b2ec588996339a))
* 文章封面视频在移动设备体验不佳 ([53e215e](https://github.com/mirai-mamori/Sakurairo/commit/53e215e83730effecad1fb9b77e0d4e8489ab768))
* 文章有视频封面时重复显示文章标题 ([c52832a](https://github.com/mirai-mamori/Sakurairo/commit/c52832adb19e5d8dfbd333802f7d6281cca68df4))
* **登陆界面:** 背景界面虚化不工作 ([f9eb67a](https://github.com/mirai-mamori/Sakurairo/commit/f9eb67a56049fa03c64a6aef198dc5a6f60b4a39))
* **aplayer:** lyric ([c9403bf](https://github.com/mirai-mamori/Sakurairo/commit/c9403bf36d04641795bdb1feaaff48b50084a804))
* **APlayer:** 歌词被遮挡 ([29a851d](https://github.com/mirai-mamori/Sakurairo/commit/29a851d3cb674b1c541b6b587346b9174f6bda0d)), closes [#365](https://github.com/mirai-mamori/Sakurairo/issues/365)
* 本地图支持jpeg ([f2e733c](https://github.com/mirai-mamori/Sakurairo/commit/f2e733cc15cff5101bb1b3a1ec3e046da1592497))
* 没有显示adminbar时依然在加载相关字体 ([bada5ae](https://github.com/mirai-mamori/Sakurairo/commit/bada5aea35baad0c624c76c1350f1f53f03b2739))
* 使用兼容php 7的语法 ([5e64960](https://github.com/mirai-mamori/Sakurairo/commit/5e6496025e7a2dfc940998b64238d38cbe7f35ce)), closes [#348](https://github.com/mirai-mamori/Sakurairo/issues/348)
* 字体加载不正确 ([1cc1ac7](https://github.com/mirai-mamori/Sakurairo/commit/1cc1ac7c63ef3d09f5a6ac8aa11c5d97146a6043))
* className标记不正确 ([afe8809](https://github.com/mirai-mamori/Sakurairo/commit/afe8809302a9552f3dc2c6136b4b44e334576ed0))
* fonts设置 ([97adf18](https://github.com/mirai-mamori/Sakurairo/commit/97adf1820f3aa8aed8017a879fcb4f1ed4688d62))
* mashiro_logo 的字体资源在关闭相关选项时仍然加载 ([0aaf71f](https://github.com/mirai-mamori/Sakurairo/commit/0aaf71ff0e4a879939dc6a1df112956383a86094)), closes [#360](https://github.com/mirai-mamori/Sakurairo/issues/360)
* pjax下登录/登出操作体验问题 ([6ea0daf](https://github.com/mirai-mamori/Sakurairo/commit/6ea0dafd47d4563eda56a69da15889c589142a8a))
* Safari下导航栏mashiro logo意外换行 ([e6afc43](https://github.com/mirai-mamori/Sakurairo/commit/e6afc433ef2fa2f71cca739d328296cc915f3889)), closes [#345](https://github.com/mirai-mamori/Sakurairo/issues/345)
* safari下文章的发布时间meta意外换行 ([69dd265](https://github.com/mirai-mamori/Sakurairo/commit/69dd265160bcfacfe909eb8212bc9a4619983299)), closes [#345](https://github.com/mirai-mamori/Sakurairo/issues/345)
* **封面视频:** 选项cover_video_loop实际效果与描述不一致 ([6bf46ce](https://github.com/mirai-mamori/Sakurairo/commit/6bf46ce89ed0ffb1deb2e1bbc1d338897d4b9d43))
* **相册模板:** 允许部分非关键属性为空 ([c666e8f](https://github.com/mirai-mamori/Sakurairo/commit/c666e8fc043388244bf4093ff2209572bac04cb8))
* **coverBackground:** 缓存类型不正确时没有fallback ([25f7815](https://github.com/Fuukei/Sakurairo_Scripts/commit/25f78156e08de2106d13ffc384ab24ca0bb4fb29))
* **coverBackground:** getCurrentBG返回有误 ([f782b39](https://github.com/Fuukei/Sakurairo_Scripts/commit/f782b3909f68ece581cf80ad3c3a823dabfc8d38)), closes [#367](https://github.com/Fuukei/Sakurairo_Scripts/issues/367)
* pjax下登录跳转网址不更新 ([8c1c8ac](https://github.com/Fuukei/Sakurairo_Scripts/commit/8c1c8ac4d694e438749b5bb76ebbdba70e85271c))


### 特性变更

* **文章封面视频:** 保障视频自动播放 默认静音 ([260ecb8](https://github.com/mirai-mamori/Sakurairo/commit/260ecb87d7c24a88b6988be3a58450ab43a1a14e))
* 懒加载非Hls的封面视频 ([575cb7a](https://github.com/mirai-mamori/Sakurairo/commit/575cb7a9e5114f881e26681c50b23122d0d63bf9))
* 设置项 在<head>注入HTML代码 ([1b9eaa7](https://github.com/mirai-mamori/Sakurairo/commit/1b9eaa7723391e7a2e3e6aa3151c87f86501567e))
* 体验优化 标题栏头像暗色模式鼠标悬停时背景不协调 ([c42ab8e](https://github.com/mirai-mamori/Sakurairo/commit/c42ab8e21b7e9cb8410196eb32188bf3b00da8ca))
* 统一视频封面与图片封面的样式 ([2be4280](https://github.com/mirai-mamori/Sakurairo/commit/2be4280a61c4e0657253b37ec42258130ab425f3))
* 未设置封面图片时依然可以使用文章视频封面 ([d86e153](https://github.com/mirai-mamori/Sakurairo/commit/d86e1531ef5771f0316c6eabda1c3676f9673d7f))
* 现在登录不会打开新选项卡，并且会重定向回登录前界面 ([6d45f24](https://github.com/mirai-mamori/Sakurairo/commit/6d45f242a0ebc03fa2719b9a88db625e709af1ba))
* 支持使用视频作为文章封面缩略图 ([f6f5257](https://github.com/mirai-mamori/Sakurairo/commit/f6f5257d3f41c842f5eae29d1839adbdf1333084))
* 重大更改 site-header z-index过高 ([90a48a0](https://github.com/mirai-mamori/Sakurairo/commit/90a48a07a38af93cb2a67ff90e7dea1d1b9d41cf))
* lightGallery ([772283f](https://github.com/mirai-mamori/Sakurairo/commit/772283ff812c78e8580d4153722db2d859a28739))
* use_as_thumb == 'only' ([7d64ec9](https://github.com/mirai-mamori/Sakurairo/commit/7d64ec9d50ab3605ce8495cbc9552ec637365131))
* **登陆界面:** 超过3秒登陆界面的预加载动画会自行消失 ([78e307d](https://github.com/mirai-mamori/Sakurairo/commit/78e307dd40e51437a07e1e7a1aae1fea36740408)), closes [#357](https://github.com/mirai-mamori/Sakurairo/issues/357)
* **登陆界面:** 重大更改 使用body来显示登陆界面背景；使登陆面板更加可读与美观；移动不变的样式定义 ([3777cee](https://github.com/mirai-mamori/Sakurairo/commit/3777cee0852bfe2edc46a202bbdf20536692f09c))
* **封面视频:** 删除不必要的输出 ([e196bdc](https://github.com/mirai-mamori/Sakurairo/commit/e196bdc8b962107bfa554ca176250f899ab98f0c))
* **封面视频:** 未启用时不输出相关HTML ([1bd61fc](https://github.com/mirai-mamori/Sakurairo/commit/1bd61fcf0dcf901cf4563d8b180137d266bc1080))
* **aplayer:** update Meting 1.5.7 -> 1.5.11 ([3205e88](https://github.com/mirai-mamori/Sakurairo/commit/3205e885ed6bc0df0cef7ded6b93f5570cea028d))
* **page:404:** 删除上报代码 ([ed5b9b0](https://github.com/mirai-mamori/Sakurairo/commit/ed5b9b0d5e816be48eb02a5b0ceabfd128ae8bac))
* meta **theme-color** 使用主题设置的颜色值 ([6e50fcb](https://github.com/mirai-mamori/Sakurairo/commit/6e50fcb43ec8b406646db6fda2b6822db9e984d0))
* **文章封面视频:** 静音按钮 ([bd54d8e](https://github.com/Fuukei/Sakurairo_Scripts/commit/bd54d8e63338590188fe103a2f05e92165f347c3))
* 不必要的?? ([c147c7a](https://github.com/Fuukei/Sakurairo_Scripts/commit/c147c7ace14f457a518e5ffe985c122150b69a63))
* 私密评论确认过程中取消时不再alert ([b6d3e31](https://github.com/Fuukei/Sakurairo_Scripts/commit/b6d3e319a94156a5c825e2b81bed039bad81cbdd))
* 添加事件ajax_post_loaded ([2016881](https://github.com/Fuukei/Sakurairo_Scripts/commit/2016881d1dc1f9df7410604bcd9d64d38ffd61a1))
* 支持在任意位置的hls视频播放 ([281763c](https://github.com/Fuukei/Sakurairo_Scripts/commit/281763c70d9aca7bc43b78c313360ef1b5ab3285))
* lightGallery ([bf17260](https://github.com/Fuukei/Sakurairo_Scripts/commit/bf172602d990549aa5847457468eacee83393802))
* **themecolor** 转换到laba色彩空间 ([114b0ca](https://github.com/Fuukei/Sakurairo_Scripts/commit/114b0ca693a4af240e520dc48625955e1165959e))
* **aplayer:** 使用自建css ([e2a81a4](https://github.com/Fuukei/Sakurairo_Scripts/commit/e2a81a46f4c58b5d83d5aa6f9200ca35f3d9aad5)), closes [mirai-mamori/Sakurairo#352](https://github.com/mirai-mamori/Sakurairo/issues/352)
* **video:** 完善懒加载支持 ([786aecd](https://github.com/Fuukei/Sakurairo_Scripts/commit/786aecd3e4a30186065f65e77efb2628cafb5c3a))
* **video:** 优先检查浏览器是否原生支持Hls ([00c594e](https://github.com/Fuukei/Sakurairo_Scripts/commit/00c594e6a6418d270353d3e1d3e3756bcb6c8527))
* no pjax attribute ([7290f23](https://github.com/Fuukei/Sakurairo_Scripts/commit/7290f23a0f1e8308086a31af8225347698ae5b76))


### 性能提升

* 毋须再加载jQuery ([8bf5ef8](https://github.com/mirai-mamori/Sakurairo/commit/8bf5ef8c30867fb7dc40086e6fc145d15fcb2bcb))
