(function(){"use strict";var f=function(){var e=this,n=e.$createElement,s=e._self._c||n;return s("k-field",{staticClass:"keyword-wrapper",attrs:{disabled:e.disabled,help:e.help,label:e.label,required:e.required,when:e.when}},[s("k-input",{attrs:{theme:"field",type:"text",value:e.value},on:{input:e.onInput}},[s("k-button",{class:["k-input-icon-button keywordcheck",e.color],attrs:{slot:"icon",link:e.url,tooltip:e.$t("open"),tabindex:"-1",target:"_blank",text:e.scoreDyn},slot:"icon"})],1)],1)},_=[],b="";function p(e,n,s,l,r,a,c,m){var t=typeof e=="function"?e.options:e;n&&(t.render=n,t.staticRenderFns=s,t._compiled=!0),l&&(t.functional=!0),a&&(t._scopeId="data-v-"+a);var o;if(c?(o=function(i){i=i||this.$vnode&&this.$vnode.ssrContext||this.parent&&this.parent.$vnode&&this.parent.$vnode.ssrContext,!i&&typeof __VUE_SSR_CONTEXT__!="undefined"&&(i=__VUE_SSR_CONTEXT__),r&&r.call(this,i),i&&i._registeredComponents&&i._registeredComponents.add(c)},t._ssrRegister=o):r&&(o=m?function(){r.call(this,(t.functional?this.parent:this).$root.$options.shadowRoot)}:r),o)if(t.functional){t._injectStyles=o;var C=t.render;t.render=function(w,d){return o.call(d),C(w,d)}}else{var u=t.beforeCreate;t.beforeCreate=u?[].concat(u,o):[o]}return{exports:e,options:t}}const g={props:{disabled:Boolean,help:String,label:String,required:Boolean,when:String,value:String,score:Number,url:String,loading:{type:Boolean,default:!1}},watch:{hasChanges(){this.hasChanges||this.syncContent()}},computed:{hasChanges(){return this.$store.getters["content/hasChanges"]()},scoreDyn(){return this.hasChanges||this.loading?"":this.score},color(){if(!this.score||this.hasChanges)return"white";if(this.score<=50)return"red";if(this.score<=80)return"yellow";if(this.score>80)return"green"}},methods:{onInput(e){this.$emit("input",e)},syncContent(){let n=this.$store.getters["content/id"]().split("?"),s=n[0],l=!1;if(n.length>1){let r=new URLSearchParams(n[1]);for(let a of r.entries())a[0]=="language"&&(l=a[1])}this.$api.get("seobility/keywordcheck",{id:s,lang:l}).then(r=>{this.score=r.score,this.url=r.url,this.loading=!1}).catch(r=>{this.loading=!1})}}},h={};var v=p(g,f,_,!1,y,null,null,null);function y(e){for(let n in h)this[n]=h[n]}var k=function(){return v.exports}();window.panel.plugin("bnomei/seobility",{fields:{keywordcheck:k},icons:{loader:'<g fill="none" fill-rule="evenodd"><g transform="translate(1 1)" stroke-width="1.75"><circle cx="7" cy="7" r="7.2" stroke="#000" stroke-opacity=".2"/><path d="M14.2,7c0-4-3.2-7.2-7.2-7.2" stroke="#000"><animateTransform attributeName="transform" type="rotate" from="0 7 7" to="360 7 7" dur="1s" repeatCount="indefinite"/></path></g></g>'}})})();
