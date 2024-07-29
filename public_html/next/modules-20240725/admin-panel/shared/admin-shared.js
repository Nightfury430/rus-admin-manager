import{createApp,ref,isReactive,isRef,toRaw,unref,watch,nextTick,inject,onMounted,provide,reactive}from"../vendor/vue.esm-browser.js";import jsCookie from"../vendor/js.cookie.js";export class Config{static App={SupportedLangs:["en","ru"],popupContainer:".wrap"}}export class GlobalState{static state=new Map;static Set(e,t){this.state.set(e,t)}static Get(e){return this.state.get(e)}}export class EnvUtils{static Ready(e){if(document.readyState!=="loading"){e();return}document.addEventListener("DOMContentLoaded",e)}static DetectLanguage(e){const o=()=>{const e=window.navigator,t=[],n=["language","userLanguage","browserLanguage","systemLanguage"];for(let s=0;s<n.length;s++)e[n[s]]&&t.push(e[n[s]]);if(e.languages)for(let n=0;n<e.languages.length;n++)e.languages[n]&&t.push(e.languages[n]);return t},s=o(),i=e[0]||"en";let t=i;const n=[];for(let t=0;t<e.length;t++)n.push(e[t].toLowerCase());for(let a=0;a<s.length;a++){let o=s[a].toLowerCase(),i=n.indexOf(o);if(i>-1){t=e[i];break}if(o.indexOf("-")<0)continue;if(o=o.split("-",1)[0],i=n.indexOf(o),i>-1){t=e[i];break}}return t}static GetTheme(){const e=window.matchMedia("(prefers-color-scheme: dark)").matches;return e?"dark":"light"}static SetCookie(e,t,n){jsCookie.set(e,t,n)}static GetCookie(e){return jsCookie.get(e)}static DeleteCookie(e,t,n){jsCookie.remove(e,t,n)}}export class EnvFeatures{static cache=new Map;static async CacheFeature(e,t){if(this.cache.has(e))return this.cache.get(e);const n=await t();return this.cache.set(e,n),n}static async StorageAvailable(e){return await this.CacheFeature(`StorageAvailable(${e})`,()=>{try{const n=window[e],t="__storage_test__";return n.setItem(t,t),n.removeItem(t),!0}catch{}return!1})}static async LocalStorage(){return await this.StorageAvailable("localStorage")}static async IndexedDb(){const e=(e,t,n)=>{const s=e.deleteDatabase(t);s.onsuccess=()=>n(!0),s.onerror=()=>n(!1)},t=async()=>new Promise(t=>{try{const s=window.indexedDB,o="__storage_test__",n=s.open(o);n.onsuccess=()=>{e(s,o,t)},n.onerror=()=>{if(n.error&&n.error.name==="InvalidStateError"){t(!1);return}e(s,o,t)}}catch{t(!1)}});return this.CacheFeature("IndexedDb",async()=>{try{const e=await t();return e===!0}catch{}return!1})}static async WebGL2(){return await this.CacheFeature("WebGL2",()=>{try{const e=document.createElement("canvas");return!!(window.WebGL2RenderingContext&&e.getContext("webgl2"))}catch{}return!1})}}export class Utils{static KebabCase(e){return e.replace(/([a-z])([A-Z])/g,"$1-$2").replace(/\s+/g,"-").toLowerCase()}static Debounce(e,t=1e3){let n;return(...s)=>{clearTimeout(n),n=setTimeout(()=>e.apply(this,s),t)}}static Clamp(e,t,n){return e<t?t:e>n?n:e}static Unref(e){return isRef(e)?unref(e):isReactive(e)?toRaw(e):e}static Trim(e){return typeof e=="string"?e.trim():e}static WaitMs(e){return new Promise(t=>setTimeout(t,e))}static ParseInt(e,t=0){if(!e)return t;const n=parseInt(e,10);return Number.isInteger(n)?n:t}static DateAdd(e,t){let n=new Date(e.getTime());if(!t)return n;const s=n.getDate();let o=n.getFullYear()+(t.year||0),i=n.getMonth()+(t.month||0);n.setDate(1),n.setFullYear(o,i);const a=new Date(n.getFullYear(),n.getMonth()+1,0).getDate();let r=Math.min(s,a)+(t.day||0);return n.setDate(r),n}static DateParseUTC(e){if(!e)return null;let t=null;try{const s=e.trim("Z").split("T",2),o=s[0].split("-",3),n=s.length>1?s[1].split(":",3):[],i=Utils.ParseInt(o[0],0),a=Utils.ParseInt(o[1],1),r=Utils.ParseInt(o[2],1);if(n[2]){n[2]=n[2];const e=n[2].split(".",2);e.length>1&&(n[2]=e[0],n[3]=e[1])}const c=Utils.ParseInt(n[0],0),l=Utils.ParseInt(n[1],0),d=Utils.ParseInt(n[2],0),u=Utils.ParseInt(n[3],0);t=new Date(Date.UTC(i,a-1,r,c,l,d,u))}catch{}return t}static FormatDateAndTime(e){if(!e)return"";const t=`${e.getFullYear()}`.padStart(4,"0"),n=`${e.getMonth()+1}`.padStart(2,"0"),s=`${e.getDate()}`.padStart(2,"0"),o=`${e.getHours()}`.padStart(2,"0"),i=`${e.getMinutes()}`.padStart(2,"0"),a=`${e.getSeconds()}`.padStart(2,"0");return`${t}-${n}-${s} ${o}:${i}:${a}`}}export class Theme{static isLocalStorage=!1;static async Init(){this.isLocalStorage=await EnvFeatures.LocalStorage(),this.UpdateThemeAttr(this.GetTheme())}static GuessEnvTheme(){return EnvUtils.GetTheme()==="dark"?"dark":"light"}static IsDark(e){return(!e||!["auto","dark","light"].includes(e))&&(e="auto"),e==="auto"&&(e=Theme.GuessEnvTheme()),e==="dark"}static GetTheme(){let e=this.GetStoredTheme();return(!e||!["auto","dark","light"].includes(e))&&(e="auto"),e}static SetTheme(e){let t=e?e.toLowerCase():null;["auto","dark","light"].includes(t)||(t=null),this.SetStoredTheme(t),this.UpdateThemeAttr(t)}static GetStoredTheme(){return this.isLocalStorage?localStorage.getItem("ap-theme"):null}static SetStoredTheme(e){if(!this.isLocalStorage)return;if(!e||e==="auto"){localStorage.removeItem("ap-theme");return}localStorage.setItem("ap-theme",e)}static UpdateThemeAttr(e){const n=document.documentElement;let t=e;if((!t||t==="auto")&&(t=this.GuessEnvTheme()),t==="dark"){n.classList.add("dark-theme");return}n.classList.remove("dark-theme")}}export class Lang{static supportedLangs=Config.App.SupportedLangs;static currentLang=null;static versions={};static data={};static dataCache={};static t(e,t){let n=Lang.data[e]||"";if(!t)return n.replace(/{\w+}/gm,"");let s=Array.isArray(t)?e=>{try{return t[parseInt(e,10)]||""}catch{}return""}:e=>t[e]||"";return n.replace(/{\w+}/gm,e=>s(e.slice(1,-1)))}static GetCurrentLang(){return this.currentLang}static SetLang(e){if(!e||!Lang.IsValidLang(e))return;const t=this.currentLang;this.currentLang=e,document.documentElement.setAttribute("lang",e),t!==e&&(this.SetStoredLang(e),t&&location.reload())}static InitLangData(){const t=this.GetStoredLang(),e=Lang.GetLangFromCurrentUrl(this.supportedLangs)||t||EnvUtils.DetectLanguage(this.supportedLangs)||"en";this.currentLang=e,document.documentElement.setAttribute("lang",e),(!t||t!==e)&&this.SetStoredLang(e);for(const e of this.supportedLangs)this.versions[e]="1";const n=document.querySelectorAll("meta[data-pp-lang]");for(const e of n){let t=e.getAttribute("data-pp-lang"),s=e.getAttribute("data-pp-version")||"1";if(!t)continue;this.versions[t]=s}}static async LoadLang(){const e=this.currentLang;if(!e)return;if(!Lang.IsValidLang(e))return;if(this.dataCache[e])return;const t=this.versions[e];try{const o="GET",i={"Content-Type":"application/json"},a=`/next/assets/admin-panel/lang/${e}.json?${t}`,n=await fetch(a,{method:o,headers:i});if(n.status<200||n.status>=400)return;const s=await n.json();if(!s)return;this.dataCache[e]=s}catch{}this.data=this.dataCache[e]}static GetLangFromCurrentUrl(e){const o=!1;if(!o)return;const{pathname:n}=window.location;if(!n)return null;const s=n.split("/");if(s.length<2)return null;const t=s[1];return Lang.IsValidLang(t)?e&&!e.includes(t)?null:t:null}static IsValidLang(e){return/^[a-z]{2}(-[A-Z]{2})?$/.test(e)}static SetStoredLang(e){EnvUtils.SetCookie("ap-lang",e,{sameSite:"Strict",expires:365})}static GetStoredLang(){return EnvUtils.GetCookie("ap-lang")}}export const t=Lang.t;export const LangVuePlugin={install:e=>{e.config.globalProperties.$t=Lang.t;const t={getSupportedLangs:()=>Config.App.SupportedLangs,getCurrentLang:()=>Lang.GetCurrentLang(),setLang:e=>Lang.SetLang(e),t:Lang.t};e.provide("lang",t)}};export class App{static state="none";static onReady=[];static Run(){if(this.state!=="none")return;this.state="loading",EnvUtils.Ready(async()=>{await Theme.Init(),await this.InitLang(),this.state="running";const e=this.onReady;this.onReady=null;for(const t of e)try{await t()}catch(e){console.error(e)}})}static Ready(e){if(this.state==="running"){e();return}this.onReady.push(e)}static Mount(e,t,n=".app-mount"){this.Ready(()=>{const s=createApp(e||{}),o=(e,t)=>{if(!t)return;for(const n in t){if(!Object.hasOwnProperty.call(t,n))continue;const s=t[n];try{s.template||(s.template=`#v-${n}`)}catch{}e.component(n,s)}};o(s,Components),o(s,t),s.use(LangVuePlugin),s.mount(n)})}static async InitLang(){Lang.InitLangData(),await Lang.LoadLang()}}export class JsonApi{static async Get(e){return await JsonApi.Call(e,"GET")}static async Post(e,t){return await JsonApi.Call(e,"POST",t)}static async Call(e,t,n){const s={status:"fail"};try{const a={"Content-Type":"application/json"};t==="POST"&&(a["X-PP-CSRF-PROTECTION"]="1");let r={method:t,headers:a};n&&(r.body=JSON.stringify(n));const i=await fetch(e,r);if(s.status=i.status,i.status===401&&(s.authRequired=!0),this.handleRefreshRedirect(i))return s.redirect=!0,s;const o=await i.json();o&&(o.status&&(s.status=o.status),o.error&&typeof o.error!="number"?s.error=o.error:o.messages&&(s.error=o.messages.error?o.messages.error:o.messages),o.data&&(s.data=o.data))}catch{}return s.ok=s.status==="success",s}static handleRefreshRedirect(e){const t=e.headers;if(!t||!t.has("refresh"))return!1;try{let e=t.get("refresh");e=e.split(";",2)[1].split("=",2)[1];const n=new URL(e);n.origin.toLowerCase()===window.location.origin.toLowerCase()&&window.location.replace(n)}catch{return!1}return!0}static GetErrorsForForm(e,t="Request failed!"){const n=e;if(!n||n.redirect)return[];if(n.error){if(n.error.validation)return typeof n.error.validation=="string"?[n.error.validation]:n.error.validation;if(typeof n.error=="string")return[n.error]}return n.ok?[]:[t]}}export const Components={ApPage:{props:{pageTitle:String},setup(e){const{t:s}=inject("lang"),o="/next/assets/admin-panel/icons/icons.svg";provide("iconsSvgPath",o);const n=ref(Theme.GetTheme());provide("theme",{theme:n,setTheme:i});const t=e.pageTitle;onMounted(()=>{document.title=t;let e=s("headTitle")||"";t&&(e+=` / ${t}`),document.title=e});function i(e){Theme.SetTheme(e),n.value=e}return{pageTitle:t}},template:`
            <section class="app-top">
                <slot name="app-top">
                    <nav class="main-nav">
                        <ul>
                            <li><a href="/next/admin-panel">{{ $t('pages.clients') }}</a></li>
                            <li><a href="/next/admin-panel/users">{{ $t('pages.users') }}</a></li>
                            <li><a href="/next/admin-panel/partners">{{ $t('pages.partners') }}</a></li>
                            <li><a href="/next/admin-panel/tariffs">{{ $t('pages.tariffs') }}</a></li>
                            <li><a href="/next/admin-panel/services">{{ $t('pages.services') }}</a></li>
                            <li><a href="/next/admin-panel/info">{{ $t('pages.info') }}</a></li>
                        </ul>
                    </nav>
                    <top-prefs></top-prefs>
                </slot>
            </section>

            <header class="app-header">
                <slot name="app-header">
                    <h2 class="page-title">{{ pageTitle }}</h2>
                </slot>
            </header>

            <main class="app-main">
                <slot></slot>
            </main>

            <footer class="app-footer">
                <slot name="app-header">
                    <p>{{ $t('footer.copyright', [(new Date()).getFullYear()]) }}</p>
                </slot>
            </footer>
        `},TopPrefs:{props:{noAccount:Boolean},setup(){const t=inject("iconsSvgPath"),{t:e}=inject("lang"),{theme:n,setTheme:s}=inject("theme"),o=[{id:"auto",label:e("theme.auto")},{id:"light",label:e("theme.light")},{id:"dark",label:e("theme.dark")}];return{iconsSvgPath:t,theme:n,themeOptions:o,setTheme:s}},template:`
            <div class="top-prefs">
                <lang-switch></lang-switch>
                <div class="prefs">
                    <svg class="icon" aria-hidden="true" focusable="false">
                        <use v-bind:xlink:href="\`\${iconsSvgPath}#parameters\`"></use>
                    </svg>

                    <input type="checkbox"/>

                    <div class="prefs-panel">
                        <div>
                            <switcher @switched="setTheme" :class="{'theme-switch': true}" :label="$t('theme')" :current="theme" :options="themeOptions">
                            </switcher>
                        </div>
                        <div v-if="!$props.noAccount">
                            <a href="/next/admin-panel/logout">{{ $t('pages.logout') }}</a>
                        </div>
                    </div>
                </div>
            </div>
        `},LangSwitch:{setup(){const{getSupportedLangs:e,setLang:t}=inject("lang"),n=e();function s(e){if(!e||!e.target)return;const n=e.target.getAttribute("data-lang");t(n)}return{langs:n,clickHandler:s}},template:`
            <ul class="app-langs">
                <li v-for="lang in langs" :key="lang">
                    <button :data-lang="lang" @click="clickHandler">{{ $t('lang.' + lang) || lang }}</button>
                </li>
            </ul>
        `},Switcher:{props:{type:String,label:String,options:Array,current:[String,Number,Array]},emits:["switched"],setup(e,{emit:t}){const s=e.type==="checkbox"||e.type==="multiple";let n=e.current;typeof e.current!="number"&&(n=n||(s?[]:"")),s&&!Array.isArray(n)&&(n=[n]);const o=ref(n);return watch(o,e=>{t("switched",Utils.Unref(e))}),{optionsSelected:o}},template:`
            <div v-if="type === 'select'" class="switch type-select">
                <label v-if="label" class="label">{{ label }}</label>
                <select v-model="optionsSelected" class="small">
                    <option v-for="option in options" :key="option.id" :value="option.id">
                        {{ option.label }}
                    </option>
                </select>
            </div>
            <div v-else-if="type === 'checkbox'" class="switch type-checkbox">
                <label v-if="label" class="label">{{ label }}</label>
                <input type="checkbox" :value="options[0].id" true-value="options[0].id" v-model="optionsSelected" />
            </div>
            <div v-else-if="type === 'multiple'" class="switch type-multiple">
                <label v-if="label" class="label">{{ label }}</label>
                <template v-for="option in options" :key="option.id">
                    <label class="button alt small" :class="{ accent: optionsSelected.includes(option.id) }">
                        <input type="checkbox" :value="option.id" v-model="optionsSelected" />
                        <span>{{ option.label }}</span>
                    </label>
                </template>
            </div>
            <div v-else class="switch type-default">
                <label v-if="label" class="label">{{ label }}</label>
                <template v-for="option in options" :key="option.id">
                    <label class="button alt small" :class="{ accent: optionsSelected === option.id }">
                        <input type="radio" :value="option.id" v-model="optionsSelected" />
                        <span>{{ option.label }}</span>
                    </label>
                </template>
            </div>
        `},Popup:{props:{preventClose:Boolean,closeHandler:Function,buttons:Array},setup(e){const t=ref(null),s=ref(null),n=ref(!1),{theme:o}=inject("theme"),i=ref({"dark-theme":Theme.IsDark(o.value)});watch(o,e=>{i.value={"dark-theme":Theme.IsDark(e)}}),watch(n,e=>{if(!e)return;nextTick(()=>{s.value&&(s.value.scrollTop=0)})});function a(){return e.closeHandler||(()=>!0)}function c(){d()}function l(t){if(t&&t.target&&t.target.nodeName==="DIALOG"){if(e.preventClose)return;if(!a()(null))return;r();return}let n=t&&t.target;if(n&&n.nodeName!=="BUTTON"&&(n=n.closest("button")),n=n&&n.value,t&&!n)return;if(!a()(n))return;r()}function d(){t.value&&t.value.showModal(),n.value=!0}function r(){t.value&&t.value.close(),n.value=!1}return{themeClass:i,dialogRef:t,contentRef:s,visible:n,show:c,close:l}},template:`
            <dialog ref="dialogRef" class="popup" :class="themeClass" @cancel.prevent="close" @click="close">
                <div v-if="visible">
                    <header v-if="$slots.header">
                        <slot name="header"></slot>
                    </header>
                    <section ref="contentRef">
                        <slot></slot>
                    </section>
                    <footer>
                        <slot name="footer">
                            <button v-for="button in buttons" :key="button.value"
                                :class="button.class" :value="button.value" @click.stop.prevent="close">
                                {{ button.label }}
                            </button>
                        </slot>
                    </footer>
                </div>
            </dialog>
        `},Pager:{props:{setPageHandler:Function,current:Number,max:Number,buttonClass:[String,Array]},setup(e){const n=e.setPageHandler,t=e.buttonClass||[],i=typeof t=="string"?t.split(" "):t,s=ref(o());watch([()=>e.current,()=>e.max],()=>{s.value=o()});function o(){const t=e.current,s=e.max,o=2;if(!isFinite(t+o)||!isFinite(s)||s<2||t<1)return[];if(t>s)return[];const n=[];if(t-o<=2)for(let e=1;e<=t;e++)n.push(e);else{n.push(1),n.push(null);for(let e=t-o;e<=t;e++)n.push(e)}if(t+o>=s-1)for(let e=t+1;e<=s;e++)n.push(e);else{for(let e=t+1;e<=t+o;e++)n.push(e);n.push(null),n.push(s)}return n.map(e=>e===null?{key:"d",type:"divider"}:e===t?{key:`c${e}`,type:"current",label:`${e}`}:{key:`${e}`,type:"button",label:`${e}`})}function a(e){if(!n)return;let t=e&&e.target;if(t&&t.nodeName!=="BUTTON"&&(t=t.closest("button")),!t)return;const s=parseInt(t.getAttribute("data-page"),10);n(s)}return{buttonClass:i,pages:s,setPageHandler:a}},template:`
            <div class="pager">
                <p>
                    <template v-for="page in pages" :key="page.key">
                        <span v-if="page.type === 'divider'" class="divider">...</span>
                        <span v-else-if="page.type === 'current'" class="current">{{ page.label }}</span>
                        <button v-else :class="buttonClass" :data-page="page.key" @click="setPageHandler">{{ page.label }}</button>
                    </template>
                </p>
            </div>
        `}}