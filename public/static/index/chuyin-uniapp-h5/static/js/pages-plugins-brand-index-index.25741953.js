(window["webpackJsonp"]=window["webpackJsonp"]||[]).push([["pages-plugins-brand-index-index"],{"0a8d":function(t,a,e){"use strict";Object.defineProperty(a,"__esModule",{value:!0}),a.default=void 0;var n={props:{src:{type:String,default:"empty"},empty_text:{type:String,default:"没有搜索内容"},textIcon:{type:String,default:"icon-sousuojieguoweikong"}},data:function(){return{typeSrc:{empty:"/static/emptyData.png"}}},computed:{setSrc:function(){return this.typeSrc[this.src]}}};a.default=n},"10b9":function(t,a,e){"use strict";e.r(a);var n=e("a8f2");for(var i in n)"default"!==i&&function(t){e.d(a,t,(function(){return n[t]}))}(i);var o,r,d,c,l=e("f0c5"),s=Object(l["a"])(n["default"],o,r,!1,null,null,null,!1,d,c);a["default"]=s.exports},"11c1":function(t,a,e){"use strict";e.r(a);var n=e("0a8d"),i=e.n(n);for(var o in n)"default"!==o&&function(t){e.d(a,t,(function(){return n[t]}))}(o);a["default"]=i.a},"291e":function(t,a,e){var n=e("24fb");a=n(!1),a.push([t.i,'@charset "UTF-8";\n/* 页面左右间距 */\n/*远素间距*/\n/* 文字尺寸 */\n/*文字颜色*/\n/* 边框颜色 */\n/* 图片加载中颜色 */\n/* 行为相关颜色 */uni-page-body[data-v-639a55e8]{background:#f3f3f3}body.?%PAGE?%[data-v-639a55e8]{background:#f3f3f3}',""]),t.exports=a},"317d":function(t,a,e){var n=e("24fb");a=n(!1),a.push([t.i,".uni-load-more[data-v-fa82f31c]{display:-webkit-box;display:-webkit-flex;display:flex;-webkit-box-orient:horizontal;-webkit-box-direction:normal;-webkit-flex-direction:row;flex-direction:row;height:%?80?%;-webkit-box-align:center;-webkit-align-items:center;align-items:center;-webkit-box-pack:center;-webkit-justify-content:center;justify-content:center}.uni-load-more__text[data-v-fa82f31c]{font-size:%?28?%;color:#999}.uni-load-more__img[data-v-fa82f31c]{height:24px;width:24px;margin-right:10px}.uni-load-more__img .load[data-v-fa82f31c]{position:absolute}.uni-load-more__img .load .child_load[data-v-fa82f31c]{width:6px;height:2px;border-top-left-radius:1px;border-bottom-left-radius:1px;background:#999;position:absolute;opacity:.2;-webkit-transform-origin:50%;transform-origin:50%;-webkit-animation:load-data-v-fa82f31c 1.56s ease infinite;animation:load-data-v-fa82f31c 1.56s ease infinite}.uni-load-more__img .load .child_load[data-v-fa82f31c]:nth-child(1){-webkit-transform:rotate(90deg);transform:rotate(90deg);top:2px;left:9px}.uni-load-more__img .load .child_load[data-v-fa82f31c]:nth-child(2){-webkit-transform:rotate(180deg);transform:rotate(180deg);top:11px;right:0}.uni-load-more__img .load .child_load[data-v-fa82f31c]:nth-child(3){-webkit-transform:rotate(270deg);transform:rotate(270deg);bottom:2px;left:9px}.uni-load-more__img .load .child_load[data-v-fa82f31c]:nth-child(4){top:11px;left:0}.load1[data-v-fa82f31c],\n.load2[data-v-fa82f31c],\n.load3[data-v-fa82f31c]{height:24px;width:24px}.load2[data-v-fa82f31c]{-webkit-transform:rotate(30deg);transform:rotate(30deg)}.load3[data-v-fa82f31c]{-webkit-transform:rotate(60deg);transform:rotate(60deg)}.load1 .child_load[data-v-fa82f31c]:nth-child(1){-webkit-animation-delay:0s;animation-delay:0s}.load2 .child_load[data-v-fa82f31c]:nth-child(1){-webkit-animation-delay:.13s;animation-delay:.13s}.load3 .child_load[data-v-fa82f31c]:nth-child(1){-webkit-animation-delay:.26s;animation-delay:.26s}.load1 .child_load[data-v-fa82f31c]:nth-child(2){-webkit-animation-delay:.39s;animation-delay:.39s}.load2 .child_load[data-v-fa82f31c]:nth-child(2){-webkit-animation-delay:.52s;animation-delay:.52s}.load3 .child_load[data-v-fa82f31c]:nth-child(2){-webkit-animation-delay:.65s;animation-delay:.65s}.load1 .child_load[data-v-fa82f31c]:nth-child(3){-webkit-animation-delay:.78s;animation-delay:.78s}.load2 .child_load[data-v-fa82f31c]:nth-child(3){-webkit-animation-delay:.91s;animation-delay:.91s}.load3 .child_load[data-v-fa82f31c]:nth-child(3){-webkit-animation-delay:1.04s;animation-delay:1.04s}.load1 .child_load[data-v-fa82f31c]:nth-child(4){-webkit-animation-delay:1.17s;animation-delay:1.17s}.load2 .child_load[data-v-fa82f31c]:nth-child(4){-webkit-animation-delay:1.3s;animation-delay:1.3s}.load3 .child_load[data-v-fa82f31c]:nth-child(4){-webkit-animation-delay:1.43s;animation-delay:1.43s}@-webkit-keyframes load-data-v-fa82f31c{0%{opacity:1}100%{opacity:.2}}",""]),t.exports=a},"39c7":function(t,a,e){"use strict";Object.defineProperty(a,"__esModule",{value:!0}),a.default=void 0;var n={name:"uni-load-more",props:{status:{type:String,default:"more"},showIcon:{type:Boolean,default:!0},color:{type:String,default:"#777777"},contentText:{type:Object,default:function(){return{contentdown:"上拉显示更多",contentrefresh:"正在加载...",contentnomore:"没有更多数据了"}}}},data:function(){return{}}};a.default=n},"3c58":function(t,a,e){"use strict";e.r(a);var n=e("e87d"),i=e("5bc4");for(var o in i)"default"!==o&&function(t){e.d(a,t,(function(){return i[t]}))}(o);e("e634");var r,d=e("f0c5"),c=Object(d["a"])(i["default"],n["b"],n["c"],!1,null,"fa82f31c",null,!1,n["a"],r);a["default"]=c.exports},"411b":function(t,a,e){var n=e("24fb");a=n(!1),a.push([t.i,'@charset "UTF-8";\n/* 页面左右间距 */\n/*远素间距*/\n/* 文字尺寸 */\n/*文字颜色*/\n/* 边框颜色 */\n/* 图片加载中颜色 */\n/* 行为相关颜色 */.empty-data[data-v-77f59f3b]{display:-webkit-box;display:-webkit-flex;display:flex;-webkit-box-align:center;-webkit-align-items:center;align-items:center;-webkit-box-pack:center;-webkit-justify-content:center;justify-content:center;-webkit-box-orient:vertical;-webkit-box-direction:normal;-webkit-flex-direction:column;flex-direction:column;padding-bottom:%?120?%;color:#c0c4cc}.empty-data .iconfont[data-v-77f59f3b]{font-size:%?120?%!important}.empty-data .empty-title[data-v-77f59f3b]{font-size:%?28?%;color:#c0c4cc}',""]),t.exports=a},"42ac":function(t,a,e){"use strict";var n=e("4ea4");Object.defineProperty(a,"__esModule",{value:!0}),a.default=void 0;n(e("31b2"));var i={data:function(){return{initLoading:0,loading:0}},watch:{loading:function(t,a){t>0?uni.showLoading({mask:!0,title:"加载中"}):uni.hideLoading()},initLoading:function(t,a){}},created:function(){},methods:{}};a.default=i},"46f5":function(t,a,e){var n=e("291e");"string"===typeof n&&(n=[[t.i,n,""]]),n.locals&&(t.exports=n.locals);var i=e("4f06").default;i("46c83d3a",n,!0,{sourceMap:!1,shadowMode:!1})},"5bc4":function(t,a,e){"use strict";e.r(a);var n=e("39c7"),i=e.n(n);for(var o in n)"default"!==o&&function(t){e.d(a,t,(function(){return n[t]}))}(o);a["default"]=i.a},7051:function(t,a,e){var n=e("317d");"string"===typeof n&&(n=[[t.i,n,""]]),n.locals&&(t.exports=n.locals);var i=e("4f06").default;i("62466d64",n,!0,{sourceMap:!1,shadowMode:!1})},7058:function(t,a,e){"use strict";e.r(a);var n=e("9cec"),i=e.n(n);for(var o in n)"default"!==o&&function(t){e.d(a,t,(function(){return n[t]}))}(o);a["default"]=i.a},7645:function(t,a,e){var n=e("411b");"string"===typeof n&&(n=[[t.i,n,""]]),n.locals&&(t.exports=n.locals);var i=e("4f06").default;i("bd120050",n,!0,{sourceMap:!1,shadowMode:!1})},"79de":function(t,a,e){"use strict";e.r(a);var n=e("caf1"),i=e("7058");for(var o in i)"default"!==o&&function(t){e.d(a,t,(function(){return i[t]}))}(o);e("d9e9");var r,d=e("f0c5"),c=Object(d["a"])(i["default"],n["b"],n["c"],!1,null,"639a55e8",null,!1,n["a"],r);a["default"]=c.exports},"9cec":function(t,a,e){"use strict";var n=e("4ea4");e("4de4"),e("c975"),e("d3b7"),e("ac1f"),Object.defineProperty(a,"__esModule",{value:!0}),a.default=void 0,e("96cf");var i=n(e("1da1")),o=(e("2f62"),n(e("9fc0"))),r=(e("b116"),n(e("10b9"))),d=n(e("d90f")),c=n(e("3c58")),l={components:{emptyData:d.default,uniLoadMore:c.default},mixins:[r.default],data:function(){return{errorMsg:"",authCode:"",brand_list:[],brand_category_list:[],category_id:-1,scrollLeft:0,itemWidth:0,loadingType:"more"}},onPullDownRefresh:function(){this.loadData(),setTimeout((function(){uni.stopPullDownRefresh()}),1e3)},onLoad:function(){var t=this;this.initLoadingFn((0,i.default)(regeneratorRuntime.mark((function a(){return regeneratorRuntime.wrap((function(a){while(1)switch(a.prev=a.next){case 0:return a.next=2,t.loadData("init");case 2:case"end":return a.stop()}}),a)}))))},methods:{loadData:function(t){var a=this;return(0,i.default)(regeneratorRuntime.mark((function e(){var n,i,r;return regeneratorRuntime.wrap((function(e){while(1)switch(e.prev=e.next){case 0:return e.prev=0,e.next=3,a.loadingFn((0,o.default)("/api/plugins/index","POST",{pluginsname:"brand",pluginscontrol:"index",pluginsaction:"index"}));case 3:n=e.sent,i=n.data,r=i.brand_category_list,r.unshift({name:"全部",id:-1}),t&&(a.primary_brand_list=JSON.stringify(i.brand_list)),a.brand_category_list=r||[],a.brand_list=i.brand_list||[],a.loadingType="nomore",e.next=17;break;case 13:e.prev=13,e.t0=e["catch"](0),a.authCode=e.t0.code,a.errorMsg=e.t0.msg;case 17:case"end":return e.stop()}}),e,null,[[0,13]])})))()},b_category:function(t,a){var e=this;return(0,i.default)(regeneratorRuntime.mark((function n(){var o;return regeneratorRuntime.wrap((function(n){while(1)switch(n.prev=n.next){case 0:if(e.category_id=t.id,0!=e.itemWidth&&0!=e.sitemWidth){n.next=6;break}return n.next=4,e.getElRect("category_sitem","itemWidth","width");case 4:return n.next=6,e.getElRect("s_category_item","sitemWidth","width");case 6:e.scrollLeft=a*e.sitemWidth+e.itemWidth/2-e.itemWidth/2,o=JSON.parse(e.primary_brand_list),e.loadingFn((0,i.default)(regeneratorRuntime.mark((function t(){return regeneratorRuntime.wrap((function(t){while(1)switch(t.prev=t.next){case 0:return t.next=2,o.filter((function(t){return t.brand_category_ids.indexOf(e.category_id)>-1||-1==e.category_id}));case 2:e.brand_list=t.sent;case 3:case"end":return t.stop()}}),t)}))));case 9:case"end":return n.stop()}}),n)})))()},getElRect:function(t,a){var e=this,n=arguments.length>2&&void 0!==arguments[2]?arguments[2]:"height";new Promise((function(i,o){var r=uni.createSelectorQuery().in(e);r.select("."+t).fields({size:!0},(function(o){o?(e[a]=o[n],i()):setTimeout((function(){e.getElRect(t)}),10)})).exec()}))}}};a.default=l},a8f2:function(t,a,e){"use strict";e.r(a);var n=e("42ac"),i=e.n(n);for(var o in n)"default"!==o&&function(t){e.d(a,t,(function(){return n[t]}))}(o);a["default"]=i.a},a9bb:function(t,a,e){"use strict";var n;e.d(a,"b",(function(){return i})),e.d(a,"c",(function(){return o})),e.d(a,"a",(function(){return n}));var i=function(){var t=this,a=t.$createElement,e=t._self._c||a;return e("v-uni-view",{staticClass:"empty-data flex1",staticStyle:{width:"100%",height:"100%"}},[e("v-uni-text",{staticClass:"iconfont",class:t.textIcon}),e("v-uni-text",{staticClass:"empty-title"},[t._v(t._s(t.empty_text))])],1)},o=[]},caf1:function(t,a,e){"use strict";e.d(a,"b",(function(){return i})),e.d(a,"c",(function(){return o})),e.d(a,"a",(function(){return n}));var n={uniLoadMore:e("3c58").default},i=function(){var t=this,a=t.$createElement,e=t._self._c||a;return-10==t.authCode?e("v-uni-view",{staticClass:"disPlayFlex",staticStyle:{width:"100%",height:"100%"}},[e("empty-data",{staticStyle:{width:"100%"},attrs:{empty_text:t.errorMsg}})],1):e("v-uni-view",{staticClass:"content"},[e("v-uni-view",{staticClass:"brand_head disPlayFlex"},[e("v-uni-scroll-view",{staticClass:"flex1 disPlayFlex category_sitem",attrs:{"scroll-x":!0,"scroll-with-animation":!0,"scroll-left":t.scrollLeft}},t._l(t.brand_category_list,(function(a,n){return e("v-uni-view",{key:n,staticClass:"s_category_item",class:{active:t.category_id==a.id},on:{click:function(e){e.stopPropagation(),arguments[0]=e=t.$handleEvent(e),t.b_category(a,n)}}},[e("v-uni-text",[t._v(t._s(a.name))])],1)})),1)],1),e("v-uni-view",{staticClass:"brand_contents disPlayFlex mb20"},[t.brand_list.length>0?t._l(t.brand_list,(function(a,n){return e("v-uni-view",{key:n,staticClass:"brand_items pr10",class:{mt10:0!=n&&1!=n}},[e("v-uni-navigator",{staticClass:"bg-white disPlayFlex flexDireColumn",attrs:{url:"/pages/goods-search/goods-search?brand_id="+a.id,"hover-class":"none"}},[e("v-uni-image",{attrs:{src:a.logo,mode:"aspectFit"}}),e("v-uni-view",{staticClass:"br-t-dashed disPlayFlex flexDireColumn"},[e("v-uni-text",{staticClass:"single-text name tc"},[t._v(t._s(a.name))]),e("v-uni-text",{staticClass:"multi-text desc"},[t._v(t._s(a.describe))])],1)],1)],1)})):[e("empty-data",{staticStyle:{width:"100%","margin-top":"200upx"},attrs:{empty_text:"没有搜索内容"}})]],2),t.brand_list.length>0?e("uni-load-more",{attrs:{status:t.loadingType}}):t._e()],1)},o=[]},d66b:function(t,a,e){"use strict";var n=e("7645"),i=e.n(n);i.a},d90f:function(t,a,e){"use strict";e.r(a);var n=e("a9bb"),i=e("11c1");for(var o in i)"default"!==o&&function(t){e.d(a,t,(function(){return i[t]}))}(o);e("d66b");var r,d=e("f0c5"),c=Object(d["a"])(i["default"],n["b"],n["c"],!1,null,"77f59f3b",null,!1,n["a"],r);a["default"]=c.exports},d9e9:function(t,a,e){"use strict";var n=e("46f5"),i=e.n(n);i.a},e634:function(t,a,e){"use strict";var n=e("7051"),i=e.n(n);i.a},e87d:function(t,a,e){"use strict";var n;e.d(a,"b",(function(){return i})),e.d(a,"c",(function(){return o})),e.d(a,"a",(function(){return n}));var i=function(){var t=this,a=t.$createElement,e=t._self._c||a;return e("v-uni-view",{staticClass:"uni-load-more"},[e("v-uni-view",{directives:[{name:"show",rawName:"v-show",value:"loading"===t.status&&t.showIcon,expression:"status === 'loading' && showIcon"}],staticClass:"uni-load-more__img"},[e("v-uni-view",{staticClass:"load1 load"},[e("v-uni-view",{staticClass:"child_load",style:{background:t.color}}),e("v-uni-view",{staticClass:"child_load",style:{background:t.color}}),e("v-uni-view",{staticClass:"child_load",style:{background:t.color}}),e("v-uni-view",{staticClass:"child_load",style:{background:t.color}})],1),e("v-uni-view",{staticClass:"load2 load"},[e("v-uni-view",{staticClass:"child_load",style:{background:t.color}}),e("v-uni-view",{staticClass:"child_load",style:{background:t.color}}),e("v-uni-view",{staticClass:"child_load",style:{background:t.color}}),e("v-uni-view",{staticClass:"child_load",style:{background:t.color}})],1),e("v-uni-view",{staticClass:"load3 load"},[e("v-uni-view",{staticClass:"child_load",style:{background:t.color}}),e("v-uni-view",{staticClass:"child_load",style:{background:t.color}}),e("v-uni-view",{staticClass:"child_load",style:{background:t.color}}),e("v-uni-view",{staticClass:"child_load",style:{background:t.color}})],1)],1),e("v-uni-text",{staticClass:"uni-load-more__text",style:{color:t.color}},[t._v(t._s("more"===t.status?t.contentText.contentdown:"loading"===t.status?t.contentText.contentrefresh:t.contentText.contentnomore))])],1)},o=[]}}]);