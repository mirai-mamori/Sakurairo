<?php
/**
Template Name: 相册模板(React)
 */
get_header();
?>
<span class="linkss-title">
    <?php the_title();?>
</span>

<style>
      .container-sakurairo {
        display: flex;
        flex-wrap: wrap;
        justify-content: space-around;
      }

      .sakurairo-wrap {
        margin: 10px;
        transform: perspective(800px);
        transform-style: preserve-3d;
        cursor: pointer;
      }

      .sakurairo-wrap:hover .sakurairo-info {
        transform: translateY(0);
      }

      .sakurairo-wrap:hover .sakurairo-info p {
        opacity: 1;
      }

      .sakurairo-wrap:hover .sakurairo-info,
      .sakurairo-wrap:hover .sakurairo-info p {
        transition: 0.6s cubic-bezier(0.23, 1, 0.32, 1);
      }

      .sakurairo-wrap:hover .sakurairo-info:after {
        transition: 5s cubic-bezier(0.23, 1, 0.32, 1);
        opacity: 1;
        transform: translateY(0);
      }

      .sakurairo-wrap:hover .sakurairo-bg {
        transition: 0.6s cubic-bezier(0.23, 1, 0.32, 1),
          opacity 5s cubic-bezier(0.23, 1, 0.32, 1);
        opacity: 0.8;
      }

      .sakurairo-wrap:hover .sakurairo {
        transition: 0.6s cubic-bezier(0.23, 1, 0.32, 1),
          box-shadow 2s cubic-bezier(0.23, 1, 0.32, 1);
        box-shadow: rgba(255, 255, 255, 0.2) 0 0 40px 5px, white 0 0 0 1px,
          rgba(0, 0, 0, 0.66) 0 30px 60px 0, inset #333 0 0 0 5px,
          inset white 0 0 0 6px;
      }

      .sakurairo {
        position: relative;
        /* flex: 0 0 240px;
            width: 240px;
            height: 320px; */
        background-color: #333;
        overflow: hidden;
        border-radius: 10px;
        box-shadow: rgba(0, 0, 0, 0.66) 0 30px 60px 0, inset #333 0 0 0 5px,
          inset rgba(255, 255, 255, 0.5) 0 0 0 6px;
        transition: 1s cubic-bezier(0.445, 0.05, 0.55, 0.95);
      }

      .sakurairo-bg {
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
        transition: 1s cubic-bezier(0.445, 0.05, 0.55, 0.95),
          opacity 5s 1s cubic-bezier(0.445, 0.05, 0.55, 0.95);
        pointer-events: none;
      }

      .sakurairo-info {
        padding: 20px;
        position: absolute;
        bottom: 0;
        color: #fff;
        transform: translateY(40%);
        transition: 0.6s 1.6s cubic-bezier(0.215, 0.61, 0.355, 1);
      }

      .sakurairo-info p {
        opacity: 0;
        text-shadow: black 0 2px 3px;
        transition: 0.6s 1.6s cubic-bezier(0.215, 0.61, 0.355, 1);
      }

      .sakurairo-info * {
        position: relative;
        z-index: 1;
      }

      .sakurairo-info:after {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        z-index: 0;
        width: 100%;
        height: 100%;
        background-image: linear-gradient(
          to bottom,
          transparent 0%,
          rgba(0, 0, 0, 0.6) 100%
        );
        background-blend-mode: overlay;
        opacity: 0;
        transform: translateY(100%);
        transition: 5s 1s cubic-bezier(0.445, 0.05, 0.55, 0.95);
      }

      .sakurairo-info h1 {
        font-family: 'Playfair Display';
        font-size: 36px;
        font-weight: 700;
        text-shadow: rgba(0, 0, 0, 0.5) 0 10px 10px;
      }
</style>
<div id="main-photo"></div>

<script src="https://cdn.bootcdn.net/ajax/libs/react/17.0.2/umd/react.development.min.js"></script>
  <script src="https://cdn.bootcdn.net/ajax/libs/react-dom/17.0.2/umd/react-dom.development.js"></script>
  <script src="https://cdn.bootcdn.net/ajax/libs/babel-standalone/7.0.0-beta.3/babel.js"></script>
  <script type="text/babel">

    const Info = (props) => {
        const {isShow} = props;
        return (
            isShow &&
            <div>
            <h1>未上传图片或图片设置异常</h1>
            <p>请在模板页设置如下类似如下格式的 img 标签,支持本地上传和外部引入</p>
            <p>如下代码会被渲染成 <a href="https://www.sakurairo.top/photo/" target="_blank">展示页</a></p>
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
        )
    }

    const Photo = (props) => {
      const { useEffect, useState, useRef } = React;
      const { header, img, info, vertical, index, url } = props;

      const show = (header || info);

      let [mouseX, setMouseX] = useState(0);
      let [mouseY, setMouseY] = useState(0);
      let [width, setWidth] = useState(0);
      let [height, setHeight] = useState(0);
      let [left, setLeft] = useState(0);
      let [top, setTop] = useState(0);
      let wRef = useRef(null);
      let childRef = useRef(null);

      let mouseLeaveDelay = null;

      useEffect(() => {
        childRef.current.style.width = vertical === 'false' ? '320px' : '240px';
        childRef.current.style.height =
          vertical === 'false' ? '240px' : '320px';
      }, []);

      useEffect(() => {
          let w_w = wRef.current ? wRef.current.offsetWidth : 0;
          let w_h = wRef.current ? wRef.current.offsetHeight : 0;
          let w_l = wRef.current ? wRef.current.offsetLeft :0;
          let w_t = wRef.current ? wRef.current.offsetTop :0;
          setHeight(w_h);
          setWidth(w_w);
          setTop(w_t);
          setLeft(w_l);
      }, [wRef.current]);

      const sakurairoStyle = () => {
        const rX = (mouseX / width) * 30;
        const rY =  (mouseY / height) * -30;
        return {
          transform: `rotateY(${rX}deg) rotateX(${rY}deg)`,
        };
      };

      const sakurairoBgTransform = () => {
        const tX = (mouseX / width) * -40;
        const tY =  (mouseY / height) * -40;
        return {
          transform: `translateX(${tX}px) translateY(${tY}px)`,
        };
      };
      const sakurairoBgImage = () => {
        if (!vertical) {
          return {
            width: '280px',
            height: '360px',
            backgroundImage: `url(${img})`,
          };
        } else {
          return {
            width: '360px',
            height: '280px',
            backgroundImage: `url(${img})`,
          };
        }
      };

      const handleMouseMove = (e) => {
        setMouseX(e.pageX - left - width / 2);
        setMouseY(e.pageY - top - height / 2);
      };

      const handleMouseEnter = () => {
        clearTimeout(mouseLeaveDelay);
      };

      const handleMouseLeave = () => {
        mouseLeaveDelay = setTimeout(() => {
          setMouseX(0);
          setMouseY(0);
        }, 1000);
      };

      const handleClick = () => {
        if(url){
            window.open(url)
        }
      };

      return (
        <div
          className="sakurairo-wrap"
          onMouseMove={handleMouseMove}
          onMouseEnter={handleMouseEnter}
          onMouseLeave={handleMouseLeave}
          onClick = {handleClick}
          ref={wRef}
        >
          <div className="sakurairo" style={sakurairoStyle()} ref={childRef}>
            <div
              className="sakurairo-bg"
              style={{...sakurairoBgTransform(),...sakurairoBgImage()}}
            ></div>
            {show && <div className="sakurairo-info">
                { header && <h1>{ header }</h1>}
                { info && <p> { info } </p>}
            </div>}
          </div>
        </div>
      );
    };

    const Main = () => {
	  let id = <?php echo get_queried_object()->ID ?> ;
      const { useEffect, useState } = React;
      let [imgList, setImgList] = useState(null);
      let [show,setShow] = useState(false);
      useEffect(() => {
          fetch(`/wp-admin/admin-ajax.php?action=getPhoto&post=${id}`).then(res=>res.json()).then(res=>{
              if(res.code===200 && res.imgs.length > 0){
                  setImgList(res.imgs)
              }else{
                  setShow(true)
              }
          })
      }, []);

      return (
          <div id="sakurairo-photo" className="container-sakurairo">
          <Info isShow={show}/>
        {imgList instanceof Array && imgList.length > 0 &&
        imgList.map((value, index) => (
          <Photo
            header={value.header}
            img={value.img}
            info={value.info}
            vertical={value.vertical}
            key={index}
            index={index}
            url={value.url}
          />
        ))}
    </div>
      );
    };

    ReactDOM.render(<Main />, document.getElementById('main-photo'));
  </script>