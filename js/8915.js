(globalThis.webpackChunksakurairo_scripts=globalThis.webpackChunksakurairo_scripts||[]).push([[8915],{8915:(e,t,n)=>{function l(e){const t=[].forEach,n=[].some,l="undefined"!=typeof window&&document.body,o=" ";let s,r=!0;function i(n,l){const s=l.appendChild(function(n){const l=document.createElement("li"),s=document.createElement("a");e.listItemClass&&l.setAttribute("class",e.listItemClass);e.onClick&&(s.onclick=e.onClick);e.includeTitleTags&&s.setAttribute("title",n.textContent);e.includeHtml&&n.childNodes.length?t.call(n.childNodes,(e=>{s.appendChild(e.cloneNode(!0))})):s.textContent=n.textContent;return s.setAttribute("href",`${e.basePath}#${n.id}`),s.setAttribute("class",`${e.linkClass+o}node-name--${n.nodeName}${o}${e.extraLinkClasses}`),l.appendChild(s),l}(n));if(n.children.length){const e=c(n.isCollapsed);n.children.forEach((t=>{i(t,e)})),s.appendChild(e)}}function c(t){const n=e.orderedList?"ol":"ul",l=document.createElement(n);let s=e.listClass+o+e.extraListClasses;return t&&(s=s+o+e.collapsibleClass,s=s+o+e.isCollapsedClass),l.setAttribute("class",s),l}function a(t){let n=0;return null!==t&&(n=t.offsetTop,e.hasInnerContainers&&(n+=a(t.offsetParent))),n}function d(e,t){return e&&e.className!==t&&(e.className=t),e}function u(t){return t&&-1!==t.className.indexOf(e.collapsibleClass)&&-1!==t.className.indexOf(e.isCollapsedClass)?(d(t,t.className.replace(o+e.isCollapsedClass,"")),u(t.parentNode.parentNode)):t}function f(){let t;return t=e.scrollContainer&&document.querySelector(e.scrollContainer)?document.querySelector(e.scrollContainer):document.documentElement||l,t}function h(){const e=f();return e?.scrollTop||0}function m(t,l=h()){let o;return n.call(t,((n,s)=>{if(a(n)>l+e.headingsOffset+10){return o=t[0===s?s:s-1],!0}if(s===t.length-1)return o=t[t.length-1],!0})),o}return{enableTocAnimation:function(){r=!0},disableTocAnimation:function(t){const n=t.target||t.srcElement;"string"==typeof n.className&&-1!==n.className.indexOf(e.linkClass)&&(r=!1)},render:function(e,t){const n=c(!1);if(t.forEach((e=>{i(e,n)})),s=e||s,null!==s)return s.firstChild&&s.removeChild(s.firstChild),0===t.length?s:s.appendChild(n)},updateToc:function(n,l){e.positionFixedSelector&&function(){const t=h(),n=document.querySelector(e.positionFixedSelector);"auto"===e.fixedSidebarOffset&&(e.fixedSidebarOffset=s.offsetTop),t>e.fixedSidebarOffset?-1===n.className.indexOf(e.positionFixedClass)&&(n.className+=o+e.positionFixedClass):n.className=n.className.replace(o+e.positionFixedClass,"")}();const i=n,c=l?.target?.getAttribute("href")||null,a=!(!c||"#"!==c.charAt(0))&&function(t){const n=f(),l=n?.querySelector(`#${t}`),o=l.offsetTop>n.offsetHeight-1.4*n.clientHeight-e.bottomModeThreshold;return o}(c.replace("#",""));if((r||a)&&s&&i.length>0){const n=m(i),l=s.querySelector(`.${e.activeLinkClass}`),r=n.id.replace(/([ #;&,.+*~':"!^$[\]()=>|/\\@])/g,"\\$1"),f=window.location.hash.replace("#","");let h=r;c&&a?h=c.replace("#",""):f&&f!==r&&(h=f);const p=s.querySelector(`.${e.linkClass}[href="${e.basePath}#${h}"]`);if(l===p)return;const C=s.querySelectorAll(`.${e.linkClass}`);t.call(C,(t=>{d(t,t.className.replace(o+e.activeLinkClass,""))}));const g=s.querySelectorAll(`.${e.listItemClass}`);t.call(g,(t=>{d(t,t.className.replace(o+e.activeListItemClass,""))})),p&&-1===p.className.indexOf(e.activeLinkClass)&&(p.className+=o+e.activeLinkClass);const S=p?.parentNode;S&&-1===S.className.indexOf(e.activeListItemClass)&&(S.className+=o+e.activeListItemClass);const b=s.querySelectorAll(`.${e.listClass}.${e.collapsibleClass}`);t.call(b,(t=>{-1===t.className.indexOf(e.isCollapsedClass)&&(t.className+=o+e.isCollapsedClass)})),p?.nextSibling&&-1!==p.nextSibling.className.indexOf(e.isCollapsedClass)&&d(p.nextSibling,p.nextSibling.className.replace(o+e.isCollapsedClass,"")),u(p?.parentNode.parentNode)}},getCurrentlyHighlighting:function(){return r},getTopHeader:m,getScrollTop:h,updateUrlHashForHeader:function(t){const n=h(),l=m(t,n);if(!l||n<5)"#"!==window.location.hash&&""!==window.location.hash&&window.history.pushState(null,null,"#");else if(l&&!function(){const t=f();return h()+t.clientHeight>t.offsetHeight-e.bottomModeThreshold}()){const e=`#${l.id}`;window.location.hash!==e&&window.history.pushState(null,null,e)}}}}n.r(t),n.d(t,{default:()=>b,destroy:()=>m,init:()=>h,refresh:()=>p});const o={tocSelector:".js-toc",tocElement:null,contentSelector:".js-toc-content",contentElement:null,headingSelector:"h1, h2, h3",ignoreSelector:".js-toc-ignore",hasInnerContainers:!1,linkClass:"toc-link",extraLinkClasses:"",activeLinkClass:"is-active-link",listClass:"toc-list",extraListClasses:"",isCollapsedClass:"is-collapsed",collapsibleClass:"is-collapsible",listItemClass:"toc-list-item",activeListItemClass:"is-active-li",collapseDepth:0,scrollSmooth:!0,scrollSmoothDuration:420,scrollSmoothOffset:0,scrollEndCallback:function(e){},headingsOffset:1,throttleTimeout:50,positionFixedSelector:null,positionFixedClass:"is-position-fixed",fixedSidebarOffset:"auto",includeHtml:!1,includeTitleTags:!1,onClick:function(e){},orderedList:!0,scrollContainer:null,skipRendering:!1,headingLabelCallback:!1,ignoreHiddenElements:!1,headingObjectCallback:null,basePath:"",disableTocScrollSync:!1,tocScrollingWrapper:null,tocScrollOffset:30,enableUrlHashUpdateOnScroll:!1,bottomModeThreshold:30};function s(e){const t=[].reduce;function n(e){return e[e.length-1]}function l(e){return+e.nodeName.toUpperCase().replace("H","")}function o(t){if(!function(e){try{return e instanceof window.HTMLElement||e instanceof window.parent.HTMLElement}catch(t){return e instanceof window.HTMLElement}}(t))return t;if(e.ignoreHiddenElements&&(!t.offsetHeight||!t.offsetParent))return null;const n=t.getAttribute("data-heading-label")||(e.headingLabelCallback?String(e.headingLabelCallback(t.innerText)):(t.innerText||t.textContent).trim()),o={id:t.id,children:[],nodeName:t.nodeName,headingLevel:l(t),textContent:n};return e.includeHtml&&(o.childNodes=t.childNodes),e.headingObjectCallback?e.headingObjectCallback(o,t):o}return{nestHeadingsArray:function(l){return t.call(l,(function(t,l){const s=o(l);return s&&function(t,l){const s=o(t),r=s.headingLevel;let i=l,c=n(i),a=r-(c?c.headingLevel:0);for(;a>0&&(c=n(i),!c||r!==c.headingLevel);)c&&void 0!==c.children&&(i=c.children),a--;r>=e.collapseDepth&&(s.isCollapsed=!0),i.push(s)}(s,t.nest),t}),{nest:[]})},selectHeadings:function(t,n){let l=n;e.ignoreSelector&&(l=n.split(",").map((function(t){return`${t.trim()}:not(${e.ignoreSelector})`})));try{return t.querySelectorAll(l)}catch(e){return console.warn(`Headers not found with selector: ${l}`),null}}}}function r(e){var t=e.duration,n=e.offset;if("undefined"!=typeof window&&"undefined"!=typeof location){var l=location.hash?o(location.href):location.href;document.body.addEventListener("click",(function(s){var r;"a"!==(r=s.target).tagName.toLowerCase()||!(r.hash.length>0||"#"===r.href.charAt(r.href.length-1))||o(r.href)!==l&&o(r.href)+"#"!==l||s.target.className.indexOf("no-smooth-scroll")>-1||"#"===s.target.href.charAt(s.target.href.length-2)&&"!"===s.target.href.charAt(s.target.href.length-1)||-1===s.target.className.indexOf(e.linkClass)||function(e,t){var n,l,o=window.pageYOffset,s={duration:t.duration,offset:t.offset||0,callback:t.callback,easing:t.easing||u},r=document.querySelector('[id="'+decodeURI(e).split("#").join("")+'"]')||document.querySelector('[id="'+e.split("#").join("")+'"]'),i="string"==typeof e?s.offset+(e?r&&r.getBoundingClientRect().top||0:-(document.documentElement.scrollTop||document.body.scrollTop)):e,c="function"==typeof s.duration?s.duration(i):s.duration;function a(e){l=e-n,window.scrollTo(0,s.easing(l,o,i,c)),l<c?requestAnimationFrame(a):d()}function d(){window.scrollTo(0,o+i),"function"==typeof s.callback&&s.callback()}function u(e,t,n,l){return(e/=l/2)<1?n/2*e*e+t:-n/2*(--e*(e-2)-1)+t}requestAnimationFrame((function(e){n=e,a(e)}))}(s.target.hash,{duration:t,offset:n,callback:function(){var e,t;e=s.target.hash,(t=document.getElementById(e.substring(1)))&&(/^(?:a|select|input|button|textarea)$/i.test(t.tagName)||(t.tabIndex=-1),t.focus())}})}),!1)}function o(e){return e.slice(0,e.lastIndexOf("#"))}}let i,c,a,d,u,f={};function h(e){f=function(...e){const t={};for(let n=0;n<e.length;n++){const l=e[n];for(const e in l)C.call(l,e)&&(t[e]=l[e])}return t}(o,e||{}),f.scrollSmooth&&(f.duration=f.scrollSmoothDuration,f.offset=f.scrollSmoothOffset,r(f)),i=l(f),c=s(f),m();const t=function(e){try{return e.contentElement||document.querySelector(e.contentSelector)}catch(t){return console.warn(`Contents element not found: ${e.contentSelector}`),null}}(f);if(null===t)return;const n=S(f);if(null===n)return;if(a=c.selectHeadings(t,f.headingSelector),null===a)return;const h=c.nestHeadingsArray(a).nest;if(f.skipRendering)return this;i.render(n,h);let p=!1;d=g((e=>{if(i.updateToc(a),!f.disableTocScrollSync&&!p&&function(e){const t=e.tocScrollingWrapper||e.tocElement||document.querySelector(e.tocSelector);if(t&&t.scrollHeight>t.clientHeight){const n=t.querySelector(`.${e.activeListItemClass}`);if(n){const l=n.offsetTop-e.tocScrollOffset;t.scrollTop=l>0?l:0}}}(f),f.enableUrlHashUpdateOnScroll){i.getCurrentlyHighlighting()&&i.updateUrlHashForHeader(a)}const t=e?.target?.scrollingElement&&0===e.target.scrollingElement.scrollTop;(e&&(0===e.eventPhase||null===e.currentTarget)||t)&&(i.updateToc(a),f.scrollEndCallback&&f.scrollEndCallback(e))}),f.throttleTimeout),d(),window.onhashchange=window.onscrollend=e=>{d()},f.scrollContainer&&document.querySelector(f.scrollContainer)?(document.querySelector(f.scrollContainer).addEventListener("scroll",d,!1),document.querySelector(f.scrollContainer).addEventListener("resize",d,!1)):(document.addEventListener("scroll",d,!1),document.addEventListener("resize",d,!1));let b=null;u=g((e=>{p=!0,f.scrollSmooth&&i.disableTocAnimation(e),i.updateToc(a,e),b&&clearTimeout(b),b=setTimeout((()=>{i.enableTocAnimation()}),f.scrollSmoothDuration),setTimeout((()=>{p=!1}),f.scrollSmoothDuration+100)}),f.throttleTimeout),f.scrollContainer&&document.querySelector(f.scrollContainer)?document.querySelector(f.scrollContainer).addEventListener("click",u,!1):document.addEventListener("click",u,!1)}function m(){const e=S(f);null!==e&&(f.skipRendering||e&&(e.innerHTML=""),f.scrollContainer&&document.querySelector(f.scrollContainer)?(document.querySelector(f.scrollContainer).removeEventListener("scroll",d,!1),document.querySelector(f.scrollContainer).removeEventListener("resize",d,!1),i&&document.querySelector(f.scrollContainer).removeEventListener("click",u,!1)):(document.removeEventListener("scroll",d,!1),document.removeEventListener("resize",d,!1),i&&document.removeEventListener("click",u,!1)))}function p(e){m(),h(e||f)}const C=Object.prototype.hasOwnProperty;function g(e,t,n){let l,o;return t||(t=250),function(...s){const r=n||this,i=+new Date;l&&i<l+t?(clearTimeout(o),o=setTimeout((()=>{l=i,e.apply(r,s)}),t)):(l=i,e.apply(r,s))}}function S(e){try{return e.tocElement||document.querySelector(e.tocSelector)}catch(t){return console.warn(`TOC element not found: ${e.tocSelector}`),null}}const b={destroy:m,init:h,refresh:p}}}]);
//# sourceMappingURL=8915.js.map