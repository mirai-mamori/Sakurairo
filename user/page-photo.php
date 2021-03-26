<?php
/**
Template Name: 相册模板
 */
get_header();
?>
<style>
    .container-siroi {
        display: flex;
        flex-wrap: wrap;
        justify-content: space-around;
    }

    .siroi-wrap {
        margin: 10px;
        transform: perspective(800px);
        transform-style: preserve-3d;
        cursor: pointer;
    }

    .siroi-wrap:hover .siroi-info {
        transform: translateY(0);
    }

    .siroi-wrap:hover .siroi-info p {
        opacity: 1;
    }

    .siroi-wrap:hover .siroi-info,
    .siroi-wrap:hover .siroi-info p {
        transition: 0.6s cubic-bezier(0.23, 1, 0.32, 1);
    }

    .siroi-wrap:hover .siroi-info:after {
        transition: 5s cubic-bezier(0.23, 1, 0.32, 1);
        opacity: 1;
        transform: translateY(0);
    }

    .siroi-wrap:hover .siroi-bg {
        transition: 0.6s cubic-bezier(0.23, 1, 0.32, 1), opacity 5s cubic-bezier(0.23, 1, 0.32, 1);
        opacity: 0.8;
    }

    .siroi-wrap:hover .siroi {
        transition: 0.6s cubic-bezier(0.23, 1, 0.32, 1), box-shadow 2s cubic-bezier(0.23, 1, 0.32, 1);
        box-shadow: rgba(255, 255, 255, 0.2) 0 0 40px 5px, white 0 0 0 1px, rgba(0, 0, 0, 0.66) 0 30px 60px 0, inset #333 0 0 0 5px, inset white 0 0 0 6px;
    }

    .siroi {
        position: relative;
        /* flex: 0 0 240px;
        width: 240px;
        height: 320px; */
        background-color: #333;
        overflow: hidden;
        border-radius: 10px;
        box-shadow: rgba(0, 0, 0, 0.66) 0 30px 60px 0, inset #333 0 0 0 5px, inset rgba(255, 255, 255, 0.5) 0 0 0 6px;
        transition: 1s cubic-bezier(0.445, 0.05, 0.55, 0.95);
    }

    .siroi-bg {
        opacity: 0.5;
        position: absolute;
        top: -20px;
        left: -20px;
        width: 100%;
        height: 100%;
        padding: 20px;
        background-repeat: no-repeat;
        background-position: center;
        background-size: cover;
        transition: 1s cubic-bezier(0.445, 0.05, 0.55, 0.95), opacity 5s 1s cubic-bezier(0.445, 0.05, 0.55, 0.95);
        pointer-events: none;
    }

    .siroi-info {
        padding: 20px;
        position: absolute;
        bottom: 0;
        color: #fff;
        transform: translateY(40%);
        transition: 0.6s 1.6s cubic-bezier(0.215, 0.61, 0.355, 1);
    }

    .siroi-info p {
        opacity: 0;
        text-shadow: black 0 2px 3px;
        transition: 0.6s 1.6s cubic-bezier(0.215, 0.61, 0.355, 1);
    }

    .siroi-info * {
        position: relative;
        z-index: 1;
    }

    .siroi-info:after {
        content: "";
        position: absolute;
        top: 0;
        left: 0;
        z-index: 0;
        width: 100%;
        height: 100%;
        background-image: linear-gradient(to bottom, transparent 0%, rgba(0, 0, 0, 0.6) 100%);
        background-blend-mode: overlay;
        opacity: 0;
        transform: translateY(100%);
        transition: 5s 1s cubic-bezier(0.445, 0.05, 0.55, 0.95);
    }

    .siroi-info h1 {
        font-family: "Playfair Display";
        font-size: 36px;
        font-weight: 700;
        text-shadow: rgba(0, 0, 0, 0.5) 0 10px 10px;
    }
</style>
<span class="linkss-title">
    <?php the_title();?>
</span>

<div id="siroi-photo" class="container-siroi">
    <template v-if="showError === false">
        <siroi v-for="img in imgs" v-bind:data-image="img.img" v-bind:vertical="function (){
                if(img.vertical == null || img.vertical === true){
                    return true;
                }else {
                    return false;
                }
            }()">
            <h1 slot="header">{{ img.header }}</h1>
            <p slot="content">{{ img.info }}</p>
        </siroi>
    </template>
    <template v-if="showError">
        <div>
            <h1>未上传图片或图片设置异常</h1>
            <p>请在模板页设置如下类似如下格式的 img 标签,支持本地上传和外部引入</p>
            <p>如下代码会被渲染成 <a href="https://www.siroi.top/photo/" target="_blank">展示页</a></p>
            <pre>

        &lt; src="https://www.wahaotu.com/uploads/allimg/202010/1602912171649821.jpg" alt="" data-header="标题"
             data-info="信息" &gt;
        &lt; img src="https://www.wahaotu.com/uploads/allimg/202010/1602912171649821.jpg" alt="" &gt;
        &lt; img src="https://www.wahaotu.com/uploads/allimg/202010/1602912171649821.jpg" alt="" data-info="信息" &gt;
        &lt; img src="https://www.wahaotu.com/uploads/allimg/202010/1602912171649821.jpg" alt="" vertical=false
             data-info="信息" &gt;
        &lt; img src="https://www.wahaotu.com/uploads/allimg/202010/1602912171649821.jpg" alt="" vertical=false
             data-header="标题" data-info="信息" &gt;
            </pre>
            <ol> img 参数说明
                <li>
                    data-header:标题
                </li>
                <li>
                    data-info:内容
                </li>
                <li>
                    vertical:是否竖向排列(默认竖向即vertical=true)
                </li>
            </ol>
        </div>
    </template>
</div>
<script src="https://cdnjs.cloudflare.com/ajax/libs/vue/2.0.1/vue.min.js"></script>
<script src="https://cdn.staticfile.org/axios/0.18.0/axios.min.js"></script>
<script>

    Vue.config.devtools = false;

    Vue.component('siroi', {
        template: `
    <div class="siroi-wrap"
      @mousemove="handleMouseMove"
      @mouseenter="handleMouseEnter"
      @mouseleave="handleMouseLeave"
      ref="siroi">
      <div class="siroi"
        :style="siroiStyle" ref="siroichild">
        <div class="siroi-bg" :style="[siroiBgTransform, siroiBgImage]"></div>
        <div class="siroi-info">
          <slot name="header"></slot>
          <slot name="content"></slot>
        </div>
      </div>
    </div>`,
        props: ['dataImage', 'vertical'],
        mounted() {
            if (this.vertical) {
                this.$refs.siroichild.style.width = "240px";
                this.$refs.siroichild.style.height = "320px";
            } else {
                this.$refs.siroichild.style.width = "320px";
                this.$refs.siroichild.style.height = "240px";
            }
            this.width = this.$refs.siroi.offsetWidth;
            this.height = this.$refs.siroi.offsetHeight;
        },
        data: () => ({
            width: 0,
            height: 0,
            mouseX: 0,
            mouseY: 0,
            mouseLeaveDelay: null
        }),
        computed: {
            mousePX() {
                return this.mouseX / this.width;
            },
            mousePY() {
                return this.mouseY / this.height;
            },
            siroiStyle() {
                const rX = this.mousePX * 30;
                const rY = this.mousePY * -30;
                return {
                    transform: `rotateY(${rX}deg) rotateX(${rY}deg)`
                };
            },
            siroiBgTransform() {
                const tX = this.mousePX * -40;
                const tY = this.mousePY * -40;
                return {
                    transform: `translateX(${tX}px) translateY(${tY}px)`
                }
            },
            siroiBgImage() {
                if (this.vertical) {
                    return {
                        width: "280px",
                        height: "360px",
                        backgroundImage: `url(${this.dataImage})`
                    }
                } else {
                    return {
                        width: "360px",
                        height: "280px",
                        backgroundImage: `url(${this.dataImage})`
                    }
                }

            }
        },
        methods: {
            handleMouseMove(e) {
                this.mouseX = e.pageX - this.$refs.siroi.offsetLeft - this.width / 2;
                this.mouseY = e.pageY - this.$refs.siroi.offsetTop - this.height / 2;
            },
            handleMouseEnter() {
                clearTimeout(this.mouseLeaveDelay);
            },
            handleMouseLeave() {
                this.mouseLeaveDelay = setTimeout(() => {
                    this.mouseX = 0;
                    this.mouseY = 0;
                }, 1000);
            }
        }
    });

    const app = new Vue({
        el: '#siroi-photo',
        data: {
            imgs: null,
            showError: false,
        },
        mounted() {
            axios
                .get('/wp-admin/admin-ajax.php?action=getPhoto&post=' +<?php echo get_queried_object() -> ID ?>)
                .then(obj => {
                    if (obj.data.code === 200) {
                        this.imgs = obj.data.imgs;
                        this.showError = this.imgs.length > 0 ? false : true;
                    }
                })
                .catch(function (error) { // 请求失败处理
                    console.log(error);
                });
        }
    });

</script>

<?php
get_footer();
?>