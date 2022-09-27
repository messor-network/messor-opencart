"use strict";(self["webpackChunkfull_with_router_v0_2"]=self["webpackChunkfull_with_router_v0_2"]||[]).push([[491],{3672:function(){},6761:function(){},5279:function(t,e,l){var s=l(6252),n=l(3577),i=l(2262),a=l(7061),c=l(8682),_={class:"ms-page-box"},o={class:"ms-page-box__item"},u={class:"ms-page-box__title"},r={class:"ms-page-box__title-icon"},d=["src"],m={class:"ms-page-box__title-txt"},g={class:"ms-page-box__content"},p={class:"text-center"};e["Z"]=(0,s.aZ)({__name:"BigSkippedFiles",props:{dataObject:{type:Object,required:!0}},setup:function(t){return function(e,v){return(0,s.wg)(),(0,s.iD)("div",_,[(0,s._)("div",o,[(0,s._)("div",u,[(0,s._)("span",r,[(0,s._)("img",{src:l(8731),alt:""},null,8,d)]),(0,s._)("span",m,(0,n.zw)((0,i.SU)(a.I)("mcl_big_files_skipped")),1),(0,s.Wm)(c.Z,{"tooltip-text":(0,i.SU)(a.I)("mcl_i_big_files_skipped")},null,8,["tooltip-text"])]),(0,s._)("div",g,[(0,s._)("table",null,[(0,s._)("tbody",null,[((0,s.wg)(!0),(0,s.iD)(s.HY,null,(0,s.Ko)(Object.entries(t.dataObject),(function(t){var e=t[0],l=t[1];return(0,s.wg)(),(0,s.iD)("tr",{key:e},[(0,s._)("td",null,(0,n.zw)(e),1),(0,s._)("td",p,(0,n.zw)(l),1)])})),128))])])])])])}}})},8611:function(t,e,l){var s=l(655),n=l(6252),i=l(3577),a=l(2262),c=l(7061),_=l(9354),o=l(3860),u={class:"ms-page-box"},r={class:"ms-page-box__item"},d={class:"ms-page-box__title"},m={class:"ms-page-box__title-icon"},g=["src"],p={class:"ms-page-box__title-txt"},v={class:"ms-page-box__content"},f={class:"dangerous-table"},x={class:"text-center"},w={class:"text-center"},E={class:"text-center"},I={class:"text-center"},b={class:"text-center"},h={class:"text-center"},S={class:"ms-shadow-link"},y={class:"text-center"},k=["innerHTML"],U={class:"text-center"},Z=["data-template","onClick"],z={class:"text-center"},A={key:0,class:"dangerous-table__error"},L=["src"],C=["onClick"],N={key:1},D=(0,n._)("use",{"xlink:href":"#icon-remove"},null,-1),P=[D],R=["onClick"],H={key:1},T=(0,n._)("use",{"xlink:href":"#icon-close-thin"},null,-1),X=[T],M=(0,n._)("span",{class:"ms-btn-icon-with-txt__title"},"Exclude",-1),G={class:"dangerous-table-details",colspan:"6"};e["Z"]=(0,n.aZ)({__name:"DangerousFilesView",props:{scanResult:{type:Object,required:!0}},setup:function(t){var e=this,D=t,T=[{title:"PHP",key:"found_malwares_array"},{title:"PHP",key:"found_danger_array"},{title:"CGI",key:"found_cgi_array"},{title:"SHTML",key:"found_shtml_array"},{title:"C",key:"found_c_array"},{title:"HTACCESS",key:"found_htaccess_array"}],j=function(t,l,n){return(0,s.mG)(e,void 0,Promise,(function(){var e,i,a;return(0,s.Jh)(this,(function(s){switch(s.label){case 0:return n.loading={remove:!0},[4,(0,_.Yd)(l)];case 1:return e=s.sent(),i=e.success,a=e.message,i?(n.loading.remove=null,D.scanResult.result[t][l]=void 0):(n.loading.remove=null,n.error=a),[2]}}))}))},V=function(t,l,n){return(0,s.mG)(e,void 0,Promise,(function(){var e,i,a;return(0,s.Jh)(this,(function(s){switch(s.label){case 0:return n.loading={exclude:!0},[4,(0,_.DV)(l)];case 1:return e=s.sent(),i=e.success,a=e.message,i?(n.loading.exclude=null,D.scanResult.result[t][l]=void 0):(n.loading.exclude=null,n.error=a),[2]}}))}))};return function(e,s){return(0,n.wg)(),(0,n.iD)("div",u,[(0,n._)("div",r,[(0,n._)("div",d,[(0,n._)("span",m,[(0,n._)("img",{src:l(2254),alt:""},null,8,g)]),(0,n._)("span",p,(0,i.zw)((0,a.SU)(c.I)("mcl_dangerous_files")),1)]),(0,n._)("div",v,[(0,n._)("table",f,[(0,n._)("thead",null,[(0,n._)("tr",null,[(0,n._)("th",x,(0,i.zw)((0,a.SU)(c.I)("mcl_file_type")),1),(0,n._)("th",null,(0,i.zw)((0,a.SU)(c.I)("mcl_danger_path")),1),(0,n._)("th",w,(0,i.zw)((0,a.SU)(c.I)("mcl_danger_total")),1),(0,n._)("th",E,(0,i.zw)((0,a.SU)(c.I)("mcl_danger_comment")),1),(0,n._)("th",I,(0,i.zw)((0,a.SU)(c.I)("mcl_danger_detects")),1),(0,n._)("th",b,(0,i.zw)((0,a.SU)(c.I)("mcl_danger_remove")),1)])]),(0,n._)("tbody",null,[((0,n.wg)(),(0,n.iD)(n.HY,null,(0,n.Ko)(T,(function(e){var s,_;return(0,n.wg)(),(0,n.iD)(n.HY,null,[((0,n.wg)(!0),(0,n.iD)(n.HY,null,(0,n.Ko)(Object.entries((null===(_=null===(s=t.scanResult)||void 0===s?void 0:s.result)||void 0===_?void 0:_[e.key])||{}),(function(t){var s,_,u=t[0],r=t[1];return(0,n.wg)(),(0,n.iD)(n.HY,{key:u},[r?((0,n.wg)(),(0,n.iD)("tr",{key:0,class:(0,i.C_)({active:r["show-details"]})},[(0,n._)("td",h,[(0,n._)("span",S,(0,i.zw)(e.title),1)]),(0,n._)("td",null,(0,i.zw)(u),1),(0,n._)("td",y,(0,i.zw)(r.items.length),1),(0,n._)("td",{class:"text-center",innerHTML:r.type},null,8,k),(0,n._)("td",U,[(0,n._)("button",{"data-template":u,class:"ms-arrow-link ms-tooltip-list",onClick:function(){return r["show-details"]=!r["show-details"]}},(0,i.zw)((0,a.SU)(c.I)("mcl_danger_show")),9,Z)]),(0,n._)("td",z,[r.error?((0,n.wg)(),(0,n.iD)("div",A,[(0,n._)("img",{src:l(5194),alt:""},null,8,L),(0,n._)("span",null,(0,i.zw)(r.error),1)])):((0,n.wg)(),(0,n.iD)("button",{key:1,class:"ms-btn-icon ms-btn-icon-remove",onClick:function(){return j(e.key,u,r)}},[(null===(s=r.loading)||void 0===s?void 0:s.remove)?((0,n.wg)(),(0,n.j4)(o["default"],{key:0})):((0,n.wg)(),(0,n.iD)("svg",N,P))],8,C)),(0,n._)("button",{class:"ms-btn-icon ms-btn-icon-with-txt ms-btn-icon-exclude",onClick:function(){return V(e.key,u,r)}},[(null===(_=r.loading)||void 0===_?void 0:_.exclude)?((0,n.wg)(),(0,n.j4)(o["default"],{key:0})):((0,n.wg)(),(0,n.iD)("svg",H,X)),M],8,R)])],2)):(0,n.kq)("",!0),(null===r||void 0===r?void 0:r["show-details"])?((0,n.wg)(),(0,n.iD)("tr",{key:1,class:(0,i.C_)({active:r["show-details"]})},[(0,n._)("td",G,[(0,n._)("ul",null,[((0,n.wg)(!0),(0,n.iD)(n.HY,null,(0,n.Ko)(r.items,(function(t){return(0,n.wg)(),(0,n.iD)("li",{key:t},(0,i.zw)(t),1)})),128))])])],2)):(0,n.kq)("",!0)],64)})),128))],64)})),64))])])])])])}}})},852:function(t,e,l){var s=l(6252),n=l(3577),i=l(2262),a=l(7061),c=l(8682),_={class:"ms-page-box"},o={class:"ms-page-box__item"},u={class:"ms-page-box__title"},r={class:"ms-page-box__title-icon"},d=["src"],m={class:"ms-page-box__title-txt"},g={class:"ms-page-box__content"},p={class:"text-left"};e["Z"]=(0,s.aZ)({__name:"ErrorDirsView",props:{dataArray:{type:Array,required:!0}},setup:function(t){return function(e,v){return(0,s.wg)(),(0,s.iD)("div",_,[(0,s._)("div",o,[(0,s._)("div",u,[(0,s._)("span",r,[(0,s._)("img",{src:l(8731),alt:""},null,8,d)]),(0,s._)("span",m,(0,n.zw)((0,i.SU)(a.I)("mcl_errors_dirs")),1),(0,s.Wm)(c.Z,{"tooltip-text":(0,i.SU)(a.I)("mcl_i_error_open")},null,8,["tooltip-text"])]),(0,s._)("div",g,[(0,s._)("table",null,[(0,s._)("tbody",null,[((0,s.wg)(!0),(0,s.iD)(s.HY,null,(0,s.Ko)(t.dataArray,(function(t){return(0,s.wg)(),(0,s.iD)("tr",{key:t},[(0,s._)("td",p,(0,n.zw)(t.trim()),1)])})),128))])])])])])}}})},5:function(t,e,l){var s=l(6252),n=l(3577),i=l(2262),a=l(7061),c=l(2944),_=l(9033),o=l(4605),u=l(1197),r=l(6308),d=l(7343),m=function(t){return(0,s.dD)("data-v-7e955654"),t=t(),(0,s.Cn)(),t},g={class:"ms-wrapper"},p={class:"ms-container"},v={class:"ms-page"},f=m((function(){return(0,s._)("svg",{viewBox:"0 0 24 24",xmlns:"http://www.w3.org/2000/svg"},[(0,s._)("path",{d:"M13 12l5-5-1-1-5 5-5-5-1 1 5 5-5 5 1 1 5-5 5 5 1-1z",fill:"#fff"})],-1)})),x=[f],w={class:"ms-page-row"},E={class:"ms-page-col ms-page-col-fluid ms-page-col-columned"},I={class:"ms-page-box ms-page-box-xs"},b={class:"ms-page-box__item"},h={class:"ms-page-circle-graph"},S={class:"circular-chart",viewBox:"0 0 36 36"},y=m((function(){return(0,s._)("path",{class:"circle-bg",d:"M18 2.0845 a 15.9155 15.9155 0 0 1 0 31.831 a 15.9155 15.9155 0 0 1 0 -31.831"},null,-1)})),k=["stroke-dasharray"],U={key:0,class:"percentage",x:"18",y:"17"},Z={class:"percentage-hint",x:"18",y:"24"},z={class:"ms-page-box"},A={class:"ms-page-box__item"},L={class:"ms-page-box__title"},C={class:"ms-page-box__title-icon"},N=["src"],D={class:"ms-page-box__title-txt"},P={key:1,class:"ms-page-box__title-icon"},R=["src"],H={class:"ms-page-box__title-txt"},T={class:"ms-page-box__content"},X={class:"table-md table-desktop"},M={key:0,class:"text-danger"},G={key:1,class:"text-success"},j={class:"table-md table-mobile"},V={class:"text-center"},O={class:"text-center text-success"},F={class:"text-center"},B={class:"text-center text-success"},q={class:"ms-page-col ms-page-col-fluid"},W={class:"ms-page-col"},Y={class:"ms-page-col"};e["Z"]=(0,s.aZ)({__name:"ResultPage",props:{scanResult:{type:Object,required:!0}},emits:["close"],setup:function(t,e){var m=e.emit,f=function(){m("close")};return function(e,m){var J,K,$,Q,tt,et,lt,st,nt,it,at,ct,_t,ot,ut,rt,dt,mt,gt,pt,vt,ft;return(0,s.wg)(),(0,s.iD)("div",g,[(0,s._)("div",p,[(0,s._)("div",v,[(0,s._)("button",{class:"result-page__close-button",title:"Close",type:"button",onClick:f},x),(0,s._)("div",w,[(0,s._)("div",E,[(0,s._)("div",I,[(0,s._)("div",b,[(0,s._)("div",h,[((0,s.wg)(),(0,s.iD)("svg",S,[y,(0,s._)("path",{class:"circle","stroke-dasharray":"".concat(null!==(K=null===(J=t.scanResult)||void 0===J?void 0:J.circle.percent)&&void 0!==K?K:"0",", 100"),d:"M18 2.0845 a 15.9155 15.9155 0 0 1 0 31.831 a 15.9155 15.9155 0 0 1 0 -31.831"},null,8,k),(null===($=t.scanResult)||void 0===$?void 0:$.circle.total_detects)>0?((0,s.wg)(),(0,s.iD)("text",U,(0,n.zw)(null!==(tt=null===(Q=t.scanResult)||void 0===Q?void 0:Q.circle.total_detects)&&void 0!==tt?tt:""),1)):(0,s.kq)("",!0),(0,s._)("text",Z,(0,n.zw)(null!==(lt=null===(et=t.scanResult)||void 0===et?void 0:et.circle.total_files)&&void 0!==lt?lt:""),1)]))])])]),(0,s._)("div",z,[(0,s._)("div",A,[(0,s._)("div",L,[(null===(st=t.scanResult)||void 0===st?void 0:st.circle.total_detects)>0?((0,s.wg)(),(0,s.iD)(s.HY,{key:0},[(0,s._)("span",C,[(0,s._)("img",{src:l(5565),alt:""},null,8,N)]),(0,s._)("span",D,(0,n.zw)((0,i.SU)(a.I)("mcl_detect_path")),1)],64)):((0,s.wg)(),(0,s.iD)("span",P,[(0,s._)("img",{src:l(5565),alt:""},null,8,R),(0,s._)("span",H,(0,n.zw)((0,i.SU)(a.I)("mcl_detect_path_no")),1)]))]),(0,s._)("div",T,[(0,s._)("table",X,[(0,s._)("thead",null,[(0,s._)("tr",null,[(0,s._)("th",null,[(0,s.Uk)((0,n.zw)(null!==(it=null===(nt=t.scanResult)||void 0===nt?void 0:nt.config.PATH)&&void 0!==it?it:"")+" ",1),(0,s._)("small",null,(0,n.zw)((0,i.SU)(a.I)("mcl_path")),1)]),(0,s._)("th",null,[(null===(at=t.scanResult)||void 0===at?void 0:at.circle.total_detects)>0?((0,s.wg)(),(0,s.iD)("span",M,(0,n.zw)((0,i.SU)(a.I)("mcl_detect")),1)):((0,s.wg)(),(0,s.iD)("span",G,(0,n.zw)((0,i.SU)(a.I)("mcl_detect_no")),1)),(0,s._)("small",null,(0,n.zw)((0,i.SU)(a.I)("mcl_status")),1)]),(0,s._)("th",null,[(0,s.Uk)((0,n.zw)((0,i.SU)(a.I)("mcl_database_version"))+" "+(0,n.zw)(null!==(_t=null===(ct=t.scanResult)||void 0===ct?void 0:ct.config.signature_version)&&void 0!==_t?_t:"")+" ",1),(0,s._)("small",null,(0,n.zw)((0,i.SU)(a.I)("mcl_database")),1)]),(0,s._)("th",null,[(0,s.Uk)((0,n.zw)(null!==(ut=null===(ot=t.scanResult)||void 0===ot?void 0:ot.work_time)&&void 0!==ut?ut:"")+" ",1),(0,s._)("small",null,(0,n.zw)((0,i.SU)(a.I)("mcl_scan_time")),1)])])])]),(0,s._)("table",j,[(0,s._)("tbody",null,[(0,s._)("tr",null,[(null===(rt=t.scanResult)||void 0===rt?void 0:rt.circle.total_detects)<0?((0,s.wg)(),(0,s.iD)(s.HY,{key:0},[(0,s._)("td",V,(0,n.zw)((0,i.SU)(a.I)("mcl_status_first_letter")),1),(0,s._)("td",O,(0,n.zw)((0,i.SU)(a.I)("mcl_detect_no")),1)],64)):((0,s.wg)(),(0,s.iD)(s.HY,{key:1},[(0,s._)("td",F,(0,n.zw)((0,i.SU)(a.I)("mcl_status_first_letter")),1),(0,s._)("td",B,(0,n.zw)((0,i.SU)(a.I)("mcl_detect")),1)],64))])])])])])])]),(0,s._)("div",q,[(0,s.Wm)(c.Z,{"scan-result":t.scanResult},null,8,["scan-result"])]),(0,s._)("div",W,[(0,s.Wm)(_.Z,{"scan-result":t.scanResult},null,8,["scan-result"]),(0,s.Wm)(o.Z,{"data-object":null===(mt=null===(dt=t.scanResult)||void 0===dt?void 0:dt.result)||void 0===mt?void 0:mt.found_symlink_array},null,8,["data-object"]),(0,s.Wm)(u.Z,{"data-object":null===(pt=null===(gt=t.scanResult)||void 0===gt?void 0:gt.result)||void 0===pt?void 0:pt.big_files_array},null,8,["data-object"])]),(0,s._)("div",Y,[(0,s.Wm)(r.Z,{"scan-result":t.scanResult},null,8,["scan-result"]),(0,s.Wm)(d.Z,{"data-array":(null===(ft=null===(vt=t.scanResult)||void 0===vt?void 0:vt.result)||void 0===ft?void 0:ft.error_dirs_array)||[]},null,8,["data-array"])])])])])])}}})},9758:function(t,e,l){var s=l(655),n=l(6252),i=l(3577),a=l(2262),c=l(7061),_=l(9354),o=l(8682),u=l(3256),r={class:"ms-page-box"},d={class:"ms-page-box__item"},m={class:"ms-page-box__title"},g={class:"ms-page-box__title-icon"},p=["src"],v={class:"ms-page-box__title-txt"},f={class:"ms-page-box__content"},x={class:"text-center"},w={class:"text-left"},E={class:"text-center"},I=["onClick"],b={key:1},h=(0,n._)("use",{"xlink:href":"#icon-remove"},null,-1),S=[h],y={class:"text-left"},k=(0,n._)("td",{class:"text-center"},"-",-1);e["Z"]=(0,n.aZ)({__name:"SkippedAndExcludedFilesView",props:{scanResult:{type:Object,required:!0}},setup:function(t){var e=this,h=t,U=function(t){return(0,s.mG)(e,void 0,Promise,(function(){var e;return(0,s.Jh)(this,(function(l){switch(l.label){case 0:return t.loading=!0,[4,(0,_.g$)(t.path)];case 1:return e=l.sent().success,e&&(t.loading=!1,h.scanResult.result.exclude_files_array=h.scanResult.result.exclude_files_array.filter((function(e){return e.path!==t.path}))),[2]}}))}))};return function(e,s){var _,h,Z,z;return(0,n.wg)(),(0,n.iD)("div",r,[(0,n._)("div",d,[(0,n._)("div",m,[(0,n._)("span",g,[(0,n._)("img",{src:l(8731),alt:""},null,8,p)]),(0,n._)("span",v,(0,i.zw)((0,a.SU)(c.I)("mcl_skip_files_skipped")),1),(0,n.Wm)(o.Z,{"tooltip-text":(0,a.SU)(c.I)("mcl_i_skip_files")},null,8,["tooltip-text"])]),(0,n._)("div",f,[(0,n._)("table",null,[(0,n._)("thead",null,[(0,n._)("tr",null,[(0,n._)("th",null,(0,i.zw)((0,a.SU)(c.I)("mcl_status_first_letter")),1),(0,n._)("th",x,(0,i.zw)((0,a.SU)(c.I)("mcl_danger_remove")),1)])]),(0,n._)("tbody",null,[((0,n.wg)(!0),(0,n.iD)(n.HY,null,(0,n.Ko)((null===(h=null===(_=t.scanResult)||void 0===_?void 0:_.result)||void 0===h?void 0:h.exclude_files_array)||[],(function(t){return(0,n.wg)(),(0,n.iD)("tr",{key:t},[(0,n._)("td",w,(0,i.zw)(t.path.trim()),1),(0,n._)("td",E,[(0,n._)("button",{class:"ms-btn-icon ms-btn-icon-remove",onClick:function(){return U(t)}},[t.loading?((0,n.wg)(),(0,n.j4)(u.Z,{key:0})):((0,n.wg)(),(0,n.iD)("svg",b,S))],8,I)])])})),128)),((0,n.wg)(!0),(0,n.iD)(n.HY,null,(0,n.Ko)((null===(z=null===(Z=t.scanResult)||void 0===Z?void 0:Z.result)||void 0===z?void 0:z.skip_files_array)||[],(function(t){return(0,n.wg)(),(0,n.iD)("tr",{key:t},[(0,n._)("td",y,(0,i.zw)(t.trim()),1),k])})),128))])])])])])}}})},7508:function(t,e,l){var s=l(6252),n=l(3577),i=l(2262),a=l(7061),c={class:"ms-page-box"},_={class:"ms-page-box__item"},o={class:"ms-page-box__title"},u={class:"ms-page-box__title-icon"},r=["src"],d={class:"ms-page-box__title-txt"},m={class:"ms-page-box__content"},g={class:"text-center"},p={class:"text-center"},v={class:"text-center"},f=(0,s._)("td",null,"PHP files",-1),x={class:"text-center"},w={class:"text-center"},E={class:"text-center"},I=(0,s._)("td",null,"CGI files",-1),b={class:"text-center"},h={class:"text-center"},S=(0,s._)("td",{class:"text-center"},null,-1),y=(0,s._)("td",null,"SHTML files",-1),k={class:"text-center"},U={class:"text-center"},Z=(0,s._)("td",{class:"text-center"},null,-1),z=(0,s._)("td",null,"htaccess files",-1),A={class:"text-center"},L={class:"text-center"},C=(0,s._)("td",{class:"text-center"},null,-1),N=(0,s._)("td",null,".C files",-1),D={class:"text-center"},P={class:"text-center"},R=(0,s._)("td",{class:"text-center"},null,-1),H={class:"text-center"},T=(0,s._)("td",{class:"text-center"},null,-1),X=(0,s._)("td",{class:"text-center"},null,-1),M={class:"text-center"},G=(0,s._)("td",{class:"text-center"},null,-1),j=(0,s._)("td",{class:"text-center"},null,-1),V={class:"text-center"},O=(0,s._)("td",{class:"text-center"},null,-1),F=(0,s._)("td",{class:"text-center"},null,-1),B={class:"text-center"},q=(0,s._)("td",{class:"text-center"},null,-1),W=(0,s._)("td",{class:"text-center"},null,-1);e["Z"]=(0,s.aZ)({__name:"StatisticView",props:{scanResult:{type:Object,required:!0}},setup:function(t){return function(e,Y){var J,K,$,Q,tt,et,lt,st,nt,it,at,ct,_t,ot,ut,rt,dt,mt,gt,pt;return(0,s.wg)(),(0,s.iD)("div",c,[(0,s._)("div",_,[(0,s._)("div",o,[(0,s._)("span",u,[(0,s._)("img",{src:l(8731),alt:""},null,8,r)]),(0,s._)("span",d,(0,n.zw)((0,i.SU)(a.I)("mcl_statistics")),1)]),(0,s._)("div",m,[(0,s._)("table",null,[(0,s._)("thead",null,[(0,s._)("tr",null,[(0,s._)("th",null,(0,n.zw)((0,i.SU)(a.I)("mcl_file_type")),1),(0,s._)("th",g,(0,n.zw)((0,i.SU)(a.I)("mcl_statistics_checked")),1),(0,s._)("th",p,(0,n.zw)((0,i.SU)(a.I)("mcl_statistics_found")),1),(0,s._)("th",v,(0,n.zw)((0,i.SU)(a.I)("mcl_statistics_danger")),1)])]),(0,s._)("tbody",null,[(0,s._)("tr",null,[f,(0,s._)("td",x,(0,n.zw)(null!==(K=null===(J=t.scanResult)||void 0===J?void 0:J.result.total_php)&&void 0!==K?K:""),1),(0,s._)("td",w,(0,n.zw)(Object.keys(null===($=t.scanResult)||void 0===$?void 0:$.result.found_malwares_array).length),1),(0,s._)("td",E,(0,n.zw)(Object.keys(null===(Q=t.scanResult)||void 0===Q?void 0:Q.result.found_danger_array).length),1)]),(0,s._)("tr",null,[I,(0,s._)("td",b,(0,n.zw)(null!==(et=null===(tt=t.scanResult)||void 0===tt?void 0:tt.result.total_cgi)&&void 0!==et?et:""),1),(0,s._)("td",h,(0,n.zw)(Object.keys(null===(lt=t.scanResult)||void 0===lt?void 0:lt.result.found_cgi_array).length),1),S]),(0,s._)("tr",null,[y,(0,s._)("td",k,(0,n.zw)(null!==(nt=null===(st=t.scanResult)||void 0===st?void 0:st.result.total_shtml)&&void 0!==nt?nt:""),1),(0,s._)("td",U,(0,n.zw)(Object.keys(null===(it=t.scanResult)||void 0===it?void 0:it.result.found_shtml_array).length),1),Z]),(0,s._)("tr",null,[z,(0,s._)("td",A,(0,n.zw)(null!==(ct=null===(at=t.scanResult)||void 0===at?void 0:at.result.total_htaccess)&&void 0!==ct?ct:""),1),(0,s._)("td",L,(0,n.zw)(Object.keys(null===(_t=t.scanResult)||void 0===_t?void 0:_t.result.found_htaccess_array).length),1),C]),(0,s._)("tr",null,[N,(0,s._)("td",D,(0,n.zw)(null!==(ut=null===(ot=t.scanResult)||void 0===ot?void 0:ot.result.total_c)&&void 0!==ut?ut:""),1),(0,s._)("td",P,(0,n.zw)(Object.keys(null===(rt=t.scanResult)||void 0===rt?void 0:rt.result.found_c_array).length),1),R]),(0,s._)("tr",null,[(0,s._)("td",null,(0,n.zw)((0,i.SU)(a.I)("mcl_statistics_big_files")),1),(0,s._)("td",H,(0,n.zw)(Object.keys(null===(dt=t.scanResult)||void 0===dt?void 0:dt.result.big_files_array).length),1),T,X]),(0,s._)("tr",null,[(0,s._)("td",null,(0,n.zw)((0,i.SU)(a.I)("mcl_statistics_skip_files")),1),(0,s._)("td",M,(0,n.zw)(Object.keys(null===(mt=t.scanResult)||void 0===mt?void 0:mt.result.skip_files_array).length),1),G,j]),(0,s._)("tr",null,[(0,s._)("td",null,(0,n.zw)((0,i.SU)(a.I)("mcl_statistics_sym_links")),1),(0,s._)("td",V,(0,n.zw)(Object.keys(null===(gt=t.scanResult)||void 0===gt?void 0:gt.result.found_symlink_array).length),1),O,F]),(0,s._)("tr",null,[(0,s._)("td",null,(0,n.zw)((0,i.SU)(a.I)("mcl_statistics_error_opendir")),1),(0,s._)("td",B,(0,n.zw)(Object.keys(null===(pt=t.scanResult)||void 0===pt?void 0:pt.result.error_dirs_array).length),1),q,W])])])])])])}}})},6092:function(t,e,l){var s=l(6252),n=l(3577),i=l(2262),a=l(7061),c=l(8682),_={class:"ms-page-box"},o={class:"ms-page-box__item"},u={class:"ms-page-box__title"},r={class:"ms-page-box__title-icon"},d=["src"],m={class:"ms-page-box__title-txt"},g={class:"ms-page-box__content"},p={class:"text-center"},v={class:"text-center"};e["Z"]=(0,s.aZ)({__name:"SymlinksView",props:{dataObject:{type:Object,required:!0}},setup:function(t){return function(e,f){return(0,s.wg)(),(0,s.iD)("div",_,[(0,s._)("div",o,[(0,s._)("div",u,[(0,s._)("span",r,[(0,s._)("img",{src:l(8195),alt:""},null,8,d)]),(0,s._)("span",m,(0,n.zw)((0,i.SU)(a.I)("mcl_symlink")),1),(0,s.Wm)(c.Z,{"tooltip-text":(0,i.SU)(a.I)("mcl_i_symlink_files")},null,8,["tooltip-text"])]),(0,s._)("div",g,[(0,s._)("table",null,[(0,s._)("thead",null,[(0,s._)("tr",null,[(0,s._)("th",null,(0,n.zw)((0,i.SU)(a.I)("mcl_danger_path")),1),(0,s._)("th",p,(0,n.zw)((0,i.SU)(a.I)("mcl_symlink_link")),1)])]),(0,s._)("tbody",null,[((0,s.wg)(!0),(0,s.iD)(s.HY,null,(0,s.Ko)(Object.entries(t.dataObject),(function(t){var e=t[0],l=t[1];return(0,s.wg)(),(0,s.iD)("tr",{key:e},[(0,s._)("td",null,(0,n.zw)(e),1),(0,s._)("td",v,(0,n.zw)(l),1)])})),128))])])])])])}}})},1711:function(t,e,l){var s=l(655),n=l(6252),i=l(2262),a=l(9963),c=l(3577),_=l(7061),o=l(9354),u=l(1093),r=l(8104),d=l(4312),m=l(8127),g=l(9027),p=l(6866),v=l(434),f=(0,n._)("span",{class:"ms-search__title"},"Path",-1),x={class:"ms-search"},w={for:"request-data__path"},E=["onClick"],I={class:"ms-settings-page__group"},b={class:"ms-settings-page__group-title"},h={class:"ms-settings-page__group-col"},S={class:"ms-settings-page__group-col"},y={class:"ms-settings-page__group-col ms-settings-page__group-col-fluid"},k={class:"ms-settings-page__group"},U={class:"ms-settings-page__group-title"},Z={class:"ms-settings-page__group-col"},z={class:"ms-settings-page__group-col"},A={class:"ms-settings-page__group-col ms-settings-page__group-col-fluid"},L={class:"ms-settings-page__group"},C={class:"ms-settings-page__group-title"},N={class:"ms-settings-page__group-col"},D={class:"ms-settings-page__group-col"},P={class:"ms-settings-page__group-col ms-settings-page__group-col-fluid"};e["Z"]=(0,n.aZ)({__name:"MalwareCleaner",setup:function(t){var e=this,R=(0,i.qj)({PATH:"",EXTENSIONS_PHP:"",EXTENSIONS_CGI:"",SIGNATURE_FILE:"",MAX_NEED_DETECTS:10,MAX_FILESIZE_PHP_ENABLE:!1,MAX_FILESIZE_CGI_ENABLE:!1,MAX_FILESIZE_MB:1,EXCLUDE:"",EXCLUDE_FILES:"",signature_version:"",SIGNATURE_PHP:"",SIGNATURE_CGI:""}),H=(0,i.iH)(null),T=(0,i.iH)(!0),X=(0,i.iH)(!0),M=function(){return(0,s.mG)(e,void 0,Promise,(function(){var t,e,l,n,i,a;return(0,s.Jh)(this,(function(c){switch(c.label){case 0:return T.value=!0,[4,(0,o.V2)(R)];case 1:return t=c.sent(),t&&(e=t.result,l=(0,s._T)(t,["result"]),n=e.exclude_files_array,i=(0,s._T)(e,["exclude_files_array"]),a=(0,s.pi)((0,s.pi)({},i),{exclude_files_array:n.map((function(t){return{path:t,loading:!1}}))}),H.value=(0,s.pi)({result:a},l)),T.value=!1,[2]}}))}))},G=function(){return(0,s.mG)(e,void 0,Promise,(function(){var t;return(0,s.Jh)(this,(function(e){switch(e.label){case 0:return[4,(0,m.Nv)("extension/module/messor/MCLApi","check_license")];case 1:return t=e.sent().data,X.value=t.accepted,[2]}}))}))},j=function(){return(0,s.mG)(e,void 0,Promise,(function(){var t,e,l,n;return(0,s.Jh)(this,(function(s){switch(s.label){case 0:return[4,G()];case 1:return s.sent(),[4,(0,o.wu)()];case 2:return t=s.sent(),t?(e=t.config,R.PATH=e.PATH,R.EXTENSIONS_PHP=e.EXTENSIONS_PHP.join(" "),R.EXTENSIONS_CGI=e.EXTENSIONS_CGI.join(" "),R.SIGNATURE_FILE=e.SIGNATURE_FILE,R.MAX_NEED_DETECTS=e.MAX_NEED_DETECTS,R.MAX_FILESIZE_PHP_ENABLE=!!+e.MAX_FILESIZE_PHP_ENABLE,R.MAX_FILESIZE_CGI_ENABLE=!!+e.MAX_FILESIZE_CGI_ENABLE,R.MAX_FILESIZE_MB=e.MAX_FILESIZE_MB,R.EXCLUDE=e.EXCLUDE,R.EXCLUDE_FILES=(null===(n=null===(l=e.EXCLUDE_FILES)||void 0===l?void 0:l.filter(Boolean))||void 0===n?void 0:n.join("\n"))||"",R.signature_version=e.signature_version,T.value=!1,[2]):[2]}}))}))},V=function(){H.value=null};return(0,n.bv)((function(){return(0,s.mG)(e,void 0,void 0,(function(){return(0,s.Jh)(this,(function(t){switch(t.label){case 0:return(0,_.g)(),[4,j()];case 1:return t.sent(),[2]}}))}))})),function(t,e){return(0,n.wg)(),(0,n.iD)(n.HY,null,[X.value?(0,n.kq)("",!0):((0,n.wg)(),(0,n.j4)(r.Z,{key:0,"api-route":"extension/module/messor/MCLApi",onClose:e[0]||(e[0]=function(t){return X.value=!0})})),T.value?((0,n.wg)(),(0,n.j4)(d.Z,{key:1})):(0,n.kq)("",!0),H.value?((0,n.wg)(),(0,n.j4)(u.Z,{key:2,"scan-result":H.value,onClose:V},null,8,["scan-result"])):((0,n.wg)(),(0,n.j4)(g.Z,{key:3,"image-url":l(9518),"sub-title":(0,i.SU)(_.I)("mcl_description"),title:"Malware Cleaner"},{"default-settings":(0,n.w5)((function(){return[f,(0,n._)("div",x,[(0,n._)("label",w,[(0,n.wy)((0,n._)("input",{id:"request-data__path","onUpdate:modelValue":e[1]||(e[1]=function(t){return R.PATH=t}),class:"ms-search__input",name:"PATH",type:"search"},null,512),[[a.nr,R.PATH]])]),(0,n._)("button",{class:"ms-search__btn",type:"submit",onClick:(0,a.iM)(M,["prevent","stop"])},(0,c.zw)((0,i.SU)(_.I)("mcl_button_scan")),9,E)])]})),"professional-settings":(0,n.w5)((function(){return[(0,n._)("div",I,[(0,n._)("span",b,(0,c.zw)((0,i.SU)(_.I)("mcl_setting_global_text")),1),(0,n._)("div",h,[(0,n.Wm)(p.Z,{modelValue:R.MAX_NEED_DETECTS,"onUpdate:modelValue":e[2]||(e[2]=function(t){return R.MAX_NEED_DETECTS=t}),name:"MAX_NEED_DETECTS",type:"number","field-id":"request-data__max_need_detects",title:"".concat((0,i.SU)(_.I)("mcl_setting_max_detect"),":"),"tooltip-text":(0,i.SU)(_.I)("mcl_i_max_detect")},null,8,["modelValue","title","tooltip-text"])]),(0,n._)("div",S,[(0,n.Wm)(p.Z,{modelValue:R.MAX_FILESIZE_MB,"onUpdate:modelValue":e[3]||(e[3]=function(t){return R.MAX_FILESIZE_MB=t}),name:"MAX_FILESIZE_MB",type:"number","field-id":"request-data__max_filesize_mb",title:"".concat((0,i.SU)(_.I)("mcl_setting_max_file_size"),":"),"tooltip-text":(0,i.SU)(_.I)("mcl_i_max_file_size")},null,8,["modelValue","title","tooltip-text"])]),(0,n._)("div",y,[(0,n.Wm)(p.Z,{modelValue:R.EXCLUDE_FILES,"onUpdate:modelValue":e[4]||(e[4]=function(t){return R.EXCLUDE_FILES=t}),name:"EXCLUDE_FILES",type:"textarea","field-id":"request-data__exclude_files",title:"".concat((0,i.SU)(_.I)("mcl_setting_exclude_scan_file"),":"),"tooltip-text":(0,i.SU)(_.I)("mcl_i_exclude_file_scan")},null,8,["modelValue","title","tooltip-text"])])]),(0,n._)("div",k,[(0,n._)("span",U,(0,c.zw)((0,i.SU)(_.I)("mcl_setting_php_settings")),1),(0,n._)("div",Z,[(0,n.Wm)(p.Z,{modelValue:R.EXTENSIONS_PHP,"onUpdate:modelValue":e[5]||(e[5]=function(t){return R.EXTENSIONS_PHP=t}),name:"EXTENSIONS_PHP",type:"text","field-id":"request-data__extensions_php",title:"".concat((0,i.SU)(_.I)("mcl_setting_extension"),":"),"tooltip-text":(0,i.SU)(_.I)("mcl_i_list_extensions_file")},null,8,["modelValue","title","tooltip-text"])]),(0,n._)("div",z,[(0,n.Wm)(p.Z,{modelValue:R.MAX_FILESIZE_PHP_ENABLE,"onUpdate:modelValue":e[6]||(e[6]=function(t){return R.MAX_FILESIZE_PHP_ENABLE=t}),name:"MAX_FILESIZE_PHP_ENABLE",type:"checkbox","field-id":"request-data__max_filesize_php_enable","check-box-title":(0,i.SU)(_.I)("mcl_setting_yes"),title:"".concat((0,i.SU)(_.I)("mcl_setting_check_file_size"),":"),"tooltip-text":(0,i.SU)(_.I)("mcl_i_check_file_size")},null,8,["modelValue","check-box-title","title","tooltip-text"])]),(0,n._)("div",A,[(0,n.Wm)(p.Z,{modelValue:R.SIGNATURE_PHP,"onUpdate:modelValue":e[7]||(e[7]=function(t){return R.SIGNATURE_PHP=t}),name:"SIGNATURE_PHP",type:"textarea","field-id":"request-data__signature_php",title:"".concat((0,i.SU)(_.I)("mcl_setting_my_signatures"),":"),"tooltip-text":(0,i.SU)(_.I)("mcl_i_add_signatures")},null,8,["modelValue","title","tooltip-text"])])]),(0,n._)("div",L,[(0,n._)("span",C,(0,c.zw)((0,i.SU)(_.I)("mcl_setting_cgi_settings")),1),(0,n._)("div",N,[(0,n.Wm)(p.Z,{modelValue:R.EXTENSIONS_CGI,"onUpdate:modelValue":e[8]||(e[8]=function(t){return R.EXTENSIONS_CGI=t}),name:"EXTENSIONS_CGI",type:"text","field-id":"request-data__extensions_cgi",title:"".concat((0,i.SU)(_.I)("mcl_setting_extension"),":"),"tooltip-text":(0,i.SU)(_.I)("mcl_i_list_extensions_file")},null,8,["modelValue","title","tooltip-text"])]),(0,n._)("div",D,[(0,n.Wm)(p.Z,{modelValue:R.MAX_FILESIZE_CGI_ENABLE,"onUpdate:modelValue":e[9]||(e[9]=function(t){return R.MAX_FILESIZE_CGI_ENABLE=t}),name:"EXTENSIONS_CGI",type:"checkbox","field-id":"request-data__max_filesize_cgi_enable","check-box-title":(0,i.SU)(_.I)("mcl_setting_yes"),title:"".concat((0,i.SU)(_.I)("mcl_setting_check_file_size"),":"),"tooltip-text":(0,i.SU)(_.I)("mcl_i_check_file_size")},null,8,["modelValue","check-box-title","title","tooltip-text"])]),(0,n._)("div",P,[(0,n.Wm)(p.Z,{modelValue:R.SIGNATURE_CGI,"onUpdate:modelValue":e[10]||(e[10]=function(t){return R.SIGNATURE_CGI=t}),name:"SIGNATURE_CGI",type:"textarea","field-id":"request-data__signature_cgi",title:"".concat((0,i.SU)(_.I)("mcl_setting_my_signatures"),":"),"tooltip-text":(0,i.SU)(_.I)("mcl_i_add_signatures")},null,8,["modelValue","title","tooltip-text"])])])]})),_:1},8,["image-url","sub-title"])),(0,n.Wm)(v.Z)],64)}}})},9354:function(t,e,l){l.d(e,{DV:function(){return o},V2:function(){return c},Yd:function(){return _},g$:function(){return u},wu:function(){return a}});var s=l(655),n=l(8127),i="extension/module/messor/MCLApi";function a(){return(0,s.mG)(this,void 0,Promise,(function(){var t;return(0,s.Jh)(this,(function(e){switch(e.label){case 0:return[4,(0,n.Nv)(i,"main")];case 1:return t=e.sent(),[2,(null===t||void 0===t?void 0:t.data)||null]}}))}))}function c(t){return(0,s.mG)(this,void 0,Promise,(function(){var e,l;return(0,s.Jh)(this,(function(a){switch(a.label){case 0:return e=(0,s.pi)((0,s.pi)({},t),{MAX_FILESIZE_PHP_ENABLE:Number(t.MAX_FILESIZE_PHP_ENABLE).toString(),MAX_FILESIZE_CGI_ENABLE:Number(t.MAX_FILESIZE_CGI_ENABLE).toString()}),[4,(0,n.Nv)(i,"result",e)];case 1:return l=a.sent(),[2,(null===l||void 0===l?void 0:l.data)||null]}}))}))}function _(t){return(0,s.mG)(this,void 0,Promise,(function(){var e;return(0,s.Jh)(this,(function(l){switch(l.label){case 0:return[4,(0,n.Nv)(i,"remove",{remove:t})];case 1:return e=l.sent(),[2,{success:"ok"===(null===e||void 0===e?void 0:e.status.toLocaleLowerCase()),message:e.data.text}]}}))}))}function o(t){return(0,s.mG)(this,void 0,Promise,(function(){var e;return(0,s.Jh)(this,(function(l){switch(l.label){case 0:return[4,(0,n.Nv)(i,"exclude",{exclude:t})];case 1:return e=l.sent(),[2,{success:"ok"===(null===e||void 0===e?void 0:e.status.toLocaleLowerCase()),message:e.data.text}]}}))}))}function u(t){return(0,s.mG)(this,void 0,Promise,(function(){var e;return(0,s.Jh)(this,(function(l){switch(l.label){case 0:return[4,(0,n.Nv)(i,"remove_of_exclude",{remove_of_exclude:t})];case 1:return e=l.sent(),[2,{success:"ok"===(null===e||void 0===e?void 0:e.status.toLocaleLowerCase()),message:e.data.text}]}}))}))}},1197:function(t,e,l){var s=l(577);const n=s.Z;e["Z"]=n},2944:function(t,e,l){var s=l(8845);l(6320);const n=s.Z;e["Z"]=n},7343:function(t,e,l){var s=l(1742);const n=s.Z;e["Z"]=n},1093:function(t,e,l){var s=l(7599),n=(l(4070),l(3744));const i=(0,n.Z)(s.Z,[["__scopeId","data-v-7e955654"]]);e["Z"]=i},6308:function(t,e,l){var s=l(3827);const n=s.Z;e["Z"]=n},9033:function(t,e,l){var s=l(8639);const n=s.Z;e["Z"]=n},4605:function(t,e,l){var s=l(8358);const n=s.Z;e["Z"]=n},1213:function(t,e,l){l.r(e);var s=l(9095);const n=s.Z;e["default"]=n},6320:function(t,e,l){l(3672)},4070:function(t,e,l){l(6761)},577:function(t,e,l){l.d(e,{Z:function(){return s.Z}});var s=l(5279)},8845:function(t,e,l){l.d(e,{Z:function(){return s.Z}});var s=l(8611)},1742:function(t,e,l){l.d(e,{Z:function(){return s.Z}});var s=l(852)},7599:function(t,e,l){l.d(e,{Z:function(){return s.Z}});var s=l(5)},3827:function(t,e,l){l.d(e,{Z:function(){return s.Z}});var s=l(9758)},8639:function(t,e,l){l.d(e,{Z:function(){return s.Z}});var s=l(7508)},8358:function(t,e,l){l.d(e,{Z:function(){return s.Z}});var s=l(6092)},9095:function(t,e,l){l.d(e,{Z:function(){return s.Z}});var s=l(1711)},5194:function(t,e,l){t.exports=l.p+"image/messor/icon-info.3f9d3b3c.svg"},8195:function(t,e,l){t.exports=l.p+"image/messor/icon-link.6ce7c7b2.svg"},8731:function(t,e,l){t.exports=l.p+"image/messor/icon-stats.b2a3664e.svg"},5565:function(t,e,l){t.exports=l.p+"image/messor/icon-check.99837cc5.svg"},9518:function(t,e,l){t.exports=l.p+"image/messor/messor-gif.0a12f1e5.svg"}}]);