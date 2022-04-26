(function(){"use strict";var v=function(){var e=this,t=e.$createElement,n=e._self._c||t;return n("k-field",{staticClass:"keyword-wrapper",attrs:{disabled:e.disabled,help:e.help,label:e.label,required:e.required,when:e.when}},[n("k-input",{attrs:{theme:"field",type:"text",value:e.value},on:{input:e.onInput}},[n("k-button",{class:["k-input-icon-button keywordcheck",e.color],attrs:{slot:"icon",link:e.url,tooltip:e.$t("open"),tabindex:"-1",target:"_blank",text:e.scoreDyn},slot:"icon"})],1),e.paid?e._e():n("div",{staticClass:"keywordlink"},[e._v("powered by "),n("a",{attrs:{href:"https://www.seobility.net/en/?ref=kirby3-seobility-plugin",target:"_blank"}},[e._v("Seobility.net")])])],1)},p=[],E="";function d(e,t,n,a,i,r,c,U){var s=typeof e=="function"?e.options:e;t&&(s.render=t,s.staticRenderFns=n,s._compiled=!0),a&&(s.functional=!0),r&&(s._scopeId="data-v-"+r);var l;if(c?(l=function(o){o=o||this.$vnode&&this.$vnode.ssrContext||this.parent&&this.parent.$vnode&&this.parent.$vnode.ssrContext,!o&&typeof __VUE_SSR_CONTEXT__!="undefined"&&(o=__VUE_SSR_CONTEXT__),i&&i.call(this,o),o&&o._registeredComponents&&o._registeredComponents.add(c)},s._ssrRegister=l):i&&(l=U?function(){i.call(this,(s.functional?this.parent:this).$root.$options.shadowRoot)}:i),l)if(s.functional){s._injectStyles=l;var j=s.render;s.render=function(z,_){return l.call(_),j(z,_)}}else{var f=s.beforeCreate;s.beforeCreate=f?[].concat(f,l):[l]}return{exports:e,options:s}}const k={props:{disabled:Boolean,help:String,label:String,required:Boolean,when:String,value:String,score:Number,url:String,loading:{type:Boolean,default:!1},paid:{type:Boolean,default:!1}},watch:{hasChanges(){this.hasChanges||this.syncContent()}},computed:{hasChanges(){return this.$store.getters["content/hasChanges"]()},scoreDyn(){return this.hasChanges||this.loading?"":this.score},color(){if(!this.score||this.hasChanges)return"white";if(this.score<=30)return"red";if(this.score<=80)return"yellow";if(this.score>80)return"green"}},methods:{onInput(e){this.$emit("input",e)},syncContent(){let t=this.$store.getters["content/id"]().split("?"),n=t[0],a=!1;if(t.length>1){let i=new URLSearchParams(t[1]);for(let r of i.entries())r[0]=="language"&&(a=r[1])}this.loading=!0,this.$api.get("seobility/keywordcheck",{id:n,lang:a}).then(i=>{this.score=i.score,this.url=i.url,this.loading=!1}).catch(i=>{this.loading=!1})}}},h={};var m=d(k,v,p,!1,b,null,null,null);function b(e){for(let t in h)this[t]=h[t]}var y=function(){return m.exports}(),S=function(){var e=this,t=e.$createElement,n=e._self._c||t;return n("div",{staticClass:"ranking-wrapper"},[e.rank===void 0?n("k-field",{attrs:{disabled:e.disabled,help:e.help,label:e.headline,required:e.required,when:e.when}},[n("k-button",{class:["ranking",e.loading?"loading":""],attrs:{icon:e.loading?"loader":void 0,disabled:e.hasChanges},on:{click:e.onClick}},[e._v(" "+e._s(e.loading?e.progress:e.label)+" ")])],1):e._e(),e.rank!==void 0?n("k-info-field",{staticClass:"ranking-info",attrs:{label:e.headline,theme:e.theme,text:e.info}}):e._e()],1)},C=[],F="";const w={props:{disabled:Boolean,help:String,headline:String,label:String,progress:String,notranked:{type:String,default:"Page is not ranked."},rank:Number,when:String,loading:{type:Boolean,default:!1}},computed:{hasChanges(){return this.$store.getters["content/hasChanges"]()},info(){return this.rank?"<div class='table'><div>#"+this.rank+"</div><div><b>"+this.title+"</b><br>"+this.description+"</div></div>":this.notranked},theme(){return this.rank>0?"info":"negative"}},methods:{onClick(){let t=this.$store.getters["content/id"]().split("?"),n=t[0],a=!1;if(t.length>1){let i=new URLSearchParams(t[1]);for(let r of i.entries())r[0]=="language"&&(a=r[1])}this.loading=!0,this.$api.get("seobility/ranking",{id:n,lang:a}).then(i=>{this.rank=i.rank,this.title=i.title,this.description=i.description,this.loading=!1}).catch(i=>{this.rank=void 0,this.loading=!1})}}},u={};var $=d(w,S,C,!1,I,null,null,null);function I(e){for(let t in u)this[t]=u[t]}var R=function(){return $.exports}(),x=function(){var e=this,t=e.$createElement,n=e._self._c||t;return n("div",{staticClass:"termsuggestion-wrapper"},[n("k-field",{class:["termsuggestion-field",e.hasSuggestions?"nobutton":""],attrs:{disabled:e.disabled,help:e.help,label:e.headline,required:e.required,when:e.when}},[e.hasSuggestions?e._e():n("k-button",{class:["termsuggestion",e.loading?"loading":""],attrs:{icon:e.loading?"loader":void 0,disabled:e.hasChanges},on:{click:e.onClick}},[e._v(" "+e._s(e.loading?e.progress:e.label)+" ")])],1),e.more!==void 0&&e.more.length?n("k-info-field",{staticClass:"termsuggestion-info more",attrs:{theme:"positive",text:e.moreWithIcon}}):e._e(),e.less!==void 0&&e.less.length?n("k-info-field",{staticClass:"termsuggestion-info less",attrs:{theme:"negative",text:e.lessWithIcon}}):e._e(),e.ok!==void 0&&e.ok.length?n("k-info-field",{staticClass:"termsuggestion-info ok",attrs:{theme:"info",text:e.okWithIcon}}):e._e()],1)},q=[],M="";const B={props:{disabled:Boolean,help:String,headline:String,label:String,progress:String,less:String,ok:String,more:String,when:String,hasSuggestions:{type:Boolean,default:!1},loading:{type:Boolean,default:!1}},computed:{hasChanges(){return this.$store.getters["content/hasChanges"]()},moreWithIcon(){return'<div class="table"><div><svg data-size="32"><use xlink:href="#icon-add"></use></svg></div><div>'+this.more.join(", ")+"</div></div>"},lessWithIcon(){return'<div class="table"><div><svg data-size="32"><use xlink:href="#icon-remove"></use></svg></div><div>'+this.less.join(", ")+"</div></div>"},okWithIcon(){return'<div class="table"><div><svg data-size="32"><use xlink:href="#icon-check"></use></svg></div><div>'+this.ok.join(", ")+"</div></div>"}},methods:{onClick(){let t=this.$store.getters["content/id"]().split("?"),n=t[0],a=!1;if(t.length>1){let i=new URLSearchParams(t[1]);for(let r of i.entries())r[0]=="language"&&(a=r[1])}this.loading=!0,this.$api.get("seobility/termsuggestion",{id:n,lang:a}).then(i=>{this.more=i.more,this.less=i.less,this.ok=i.ok,this.hasSuggestions=!0,this.loading=!1}).catch(i=>{this.hasSuggestions=!1,this.loading=!1})}}},g={};var T=d(B,x,q,!1,W,null,null,null);function W(e){for(let t in g)this[t]=g[t]}var N=function(){return T.exports}();window.panel.plugin("bnomei/seobility",{fields:{keywordcheck:y,ranking:R,termsuggestion:N},icons:{loader:'<g fill="none" fill-rule="evenodd"><g transform="translate(1 1)" stroke-width="1.75"><circle cx="7" cy="7" r="7.2" stroke="#000" stroke-opacity=".2"/><path d="M14.2,7c0-4-3.2-7.2-7.2-7.2" stroke="#000"><animateTransform attributeName="transform" type="rotate" from="0 7 7" to="360 7 7" dur="1s" repeatCount="indefinite"/></path></g></g>'}})})();
