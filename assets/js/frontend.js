(window.webpackJsonp=window.webpackJsonp||[]).push([[3],{74:function(e,t,n){"use strict";n.r(t);var r=n(0),o={name:"App"},s=n(1),i=Object(s.a)(o,(function(){var e=this.$createElement,t=this._self._c||e;return t("div",{attrs:{id:"vue-frontend-app"}},[t("h2",[this._v("Frontend App")]),this._v(" "),t("router-link",{attrs:{to:"/"}},[this._v("Home")]),this._v(" "),t("router-link",{attrs:{to:"/profile"}},[this._v("Profile")]),this._v(" "),t("router-view")],1)}),[],!1,null,null,null).exports,a=n(5),l={name:"Home",data:()=>({msg:"Welcome to Your Vue.js Frontend App"})},u=Object(s.a)(l,(function(){var e=this.$createElement,t=this._self._c||e;return t("div",{staticClass:"hello"},[t("span",[this._v(this._s(this.msg))])])}),[],!1,null,"e39eabd6",null).exports,p={name:"Profile",data:()=>({})},c=Object(s.a)(p,(function(){var e=this.$createElement;return(this._self._c||e)("div",{staticClass:"profile"},[this._v("\n  The Profile Page\n")])}),[],!1,null,"5433d439",null).exports;r.default.use(a.a);var h=new a.a({routes:[{path:"/",name:"Home",component:u},{path:"/profile",name:"Profile",component:c}]});r.default.config.productionTip=!1,new r.default({el:"#vue-frontend-app",router:h,render:e=>e(i)})}},[[74,0,1]]]);