import{inject,provide,reactive,ref,toRaw,unref,watch}from"../vendor/vue.esm-browser.js";import*as VueDatePicker from"../vendor/vue-datepicker.js";import{JsonApi,Utils,Theme}from"./admin-shared.js";export const TableContainer={props:{dataProvider:Object,fields:Array,filters:Object,pager:Object,rowActions:Object,langProvider:Object,popupContainer:String,iconsSvgPath:String,rowTopComponent:Object,rowBottomComponent:Object},setup(e){const d=Object.assign({saveData:()=>!0,deleteData:()=>!0},e.dataProvider),i=e.fields,m=Object.assign({search:"",sort:{field:"id",asc:!1},fields:{}},e.filters);for(const e of i)m.fields.hasOwnProperty(e.name)||(m.fields[e.name]={});const C=Object.assign({currentPage:1,perPage:10,totalPages:1,softDelete:!1},e.pager);if(!d.getData)throw new Error("TableContainer: dataProvider parameter must be defined with getData method!");if(!i)throw new Error("TableContainer: fields parameter must be defined!");const M=Object.assign({edit:{action:e=>!0},delete:{action:e=>!0}},e.rowActions),c=ref(i),O=ref([]),w=ref([]),p=ref({show:[],hide:[]});k();const s=reactive(m),t=ref(C),a=ref(!0),r=ref([]),y=ref(0),l=ref([]);let n;if(e.langProvider&&e.langProvider.t)n=e.langProvider.t,provide("lang",{t:n});else{const e=inject("lang");n=e.t}const v={header:"",message:"",preventClose:!1,buttons:[{class:"button accent",label:n("ok"),value:"ok"}],actionHandler:()=>!0},j=ref(v),f=ref(null),u=ref(null),_=ref({}),b=ref({});let o=!0;provide("dataProvider",d),provide("fields",{fields:c,setFields:A,fieldsShow:O,fieldsHide:w,fieldsWidth:p}),provide("filters",{filters:s,setSearch:L,setSortField:$,setSortAsc:T,setFilterValue:z,setFiltersValues:D,resetFilters:B}),provide("pager",{pager:t,setCurrentPage:N}),provide("rowActions",{rowActions:M,callAction:S}),provide("rows",r),provide("errors",l),provide("popup",{popupContainer:e.popupContainer||".wrap",confirmPopupDefaultOptions:v,confirmPopupRef:f,confirmPopup:j,editPopupRef:u,editPopup:_,editPopupData:b,showConfirmPopup:h});const x=e.iconsSvgPath||"/next/assets/admin-panel/icons/icons.svg";provide("iconsSvgPath",x);const P=Utils.Debounce(()=>{console.log({filters:s,pager:t}),g(o),o=!1},700);watch([s,()=>t.value.currentPage,()=>t.value.perPage],P,{immediate:!0});function E(e){const r=e=>{const t=e.width?e.width:1;return typeof t=="number"?Math.min(4,t):t==="full"?4:1},n=4,o=e.length;if(o===0)return[];if(o===1)return[n];let i=0,s=0,a=0,t=[[]];for(let c=0;c<o;c++)s=r(e[c]),i+=s,i>n&&(a+=1,t.push([]),i=s),t[a].push(s);for(let e=0;e<t.length;e++){const s=t[e].length;s===2&&t[e][0]===1&&t[e][1]===1&&(t[e]=[2,2]);const o=t[e].reduce((e,t)=>e+t,0);o<n&&(t[e][s-1]+=n-o)}return t.flat()}function k(){const e=c.value.filter(e=>!e.show),t=c.value.filter(e=>e.show==="hide");p.value.show=E(e),p.value.hide=E(t),O.value=e,w.value=t}function A(e){c.value=e,k()}function R(){A(i)}function L(e){s.search=Utils.Trim(e),o=!0,t.value.totalPages=1,t.value.currentPage=1}function $(e){s.sort.field=e}function T(e){s.sort.asc=e}function z(e,n){s.fields[e].value=Utils.Trim(n),o=!0,t.value.totalPages=1,t.value.currentPage=1}function D(e){s.fields=e,o=!0,t.value.totalPages=1,t.value.currentPage=1}function N(e){if(!e||typeof e!="number")return;const n=document.querySelector(".table-actions");n&&n.scrollIntoView({behavior:"smooth"}),t.value.currentPage=e}function h(e){if(!e)return;const t=Object.assign({},v,e);j.value=t,f.value&&f.value.show()}function S(e,t,s,o){if(!e)return;if(e!=="add"&&!t)return;const a=M[e];if(a&&a.action&&!a.action(t))return;if(e==="edit"&&t.id){F(e,t,s);return}if(e==="add"){const t={};for(const e of i){if(e.name==="id")continue;t[e.name]=e.default||e.type==="int"||e.type==="bool"?0:""}o&&o(e,t),F(e,t,s);return}if(e==="delete"&&t.id){h({message:n("table.deletePopup.title"),buttons:[{class:"button error",label:n("delete"),value:"delete"},{class:"button alt",label:n("cancel"),value:"cancel"}],actionHandler:e=>(e==="delete"&&I(t.id,s),!0)});return}}function F(e,t,s){const o=e!=="edit",r=o?i.filter(e=>e.send||e.name!=="id"&&e.show!=="none"&&e.show!=="filter"&&(!e.edit||e.edit==="new")):i.filter(e=>e.send||e.name==="id"||e.show!=="none"&&e.show!=="filter"&&(!e.edit||e.edit==="edit")),a={};for(const e of r)a[e.name]={value:t[e.name]};b.value=a,_.value={isNew:o,header:n(o?"table.editPopup.titleNew":"table.editPopup.title"),buttons:[{class:"button accent",label:n("save"),value:"save"},{class:"button alt",label:n("cancel"),value:"cancel"}],actionHandler:t=>t!=="save"||(H(e,s),!1)},u.value&&u.value.show()}async function H(e,t){const s=Utils.Unref(b.value),o={};for(const e in s)Object.hasOwnProperty.call(s,e)&&(o[e]=s[e].value);const a=await d.saveData({rows:[o],dateTimeZone:Intl.DateTimeFormat().resolvedOptions().timeZone}),i=JsonApi.GetErrorsForForm(a);if(i.length>0){h({header:n("error"),message:i[0],buttons:[{class:"button error",label:n("ok"),value:"ok"}]});return}if(u.value&&u.value.close(),t&&!t(e,[o]))return;g(!0)}async function I(e,s){a.value=!0;const i=await d.deleteData({ids:[e]}),o=JsonApi.GetErrorsForForm(i);if(o.length>0){h({header:n("error"),message:o[0],buttons:[{class:"button error",label:n("ok"),value:"ok"}]});return}if(s&&!s("delete",[e])){a.value=!1;return}if(!t.value.softDelete&&r.value.length<=1){const e=t.value.currentPage;if(e>1){t.value.currentPage=e-1,t.value.totalPages-=1;return}}g(!0)}function B(){const e=Utils.Unref(s.fields);for(const t in e)s.fields[t].value="";s.search="",o=!0,t.value.totalPages=1,t.value.currentPage=1}function V(){r.value=[],l.value=[],R(),s=m,t.value=C,o=!0,t.value.totalPages=1,t.value.currentPage=1}async function g(e=!1){a.value=!0,l.value=[];const i={filters:toRaw(s),pager:unref(t),dateTimeZone:Intl.DateTimeFormat().resolvedOptions().timeZone};e&&(i.count=!0);const n=await d.getData(i);console.log(n);const u=JsonApi.GetErrorsForForm(n);if(u.length>0){r.value=[],l.value=u,a.value=!1;return}const o=c.value.filter(e=>e.type==="date").map(e=>e.name),h=n.data&&n.data.rows?n.data.rows:[];o.length>0&&h.forEach(e=>{for(let t=0;t<o.length;t++)if(e[o[t]]){const n=Utils.DateParseUTC(e[o[t]]);n&&(e[o[t]]=Utils.FormatDateAndTime(n))}}),r.value=h,n.data&&n.data.hasOwnProperty("count")&&(y.value=n.data.count,t.value.totalPages=Math.ceil(n.data.count/t.value.perPage)),a.value=!1}return{iconsSvgPath:x,fetching:a,rows:r,totalRowsCount:y,errors:l,rowTopComponent:e.rowTopComponent,rowBottomComponent:e.rowBottomComponent,reset:V,callAction:S}},template:`
        <div class="table-container">
            <slot name="t-filters">
                <table-filters>
                    <template v-slot:f-search>
                        <slot name="f-search"></slot>
                    </template>
                    <template v-slot:f-sort>
                        <slot name="f-sort"></slot>
                    </template>
                    <template v-slot:f-toggle-fields>
                        <slot name="f-toggle-fields"></slot>
                    </template>
                    <template v-slot:f-active-filters>
                        <slot name="f-active-filters"></slot>
                    </template>
                </table-filters>
            </slot>

            <div class="table-actions">
                <span>{{ $t('table.actions.title') }}:</span>

                <button class="button" @click="callAction('add')">
                    <svg class="icon" aria-hidden="true" focusable="false">
                        <use v-bind:xlink:href="\`\${iconsSvgPath}#add\`"></use>
                    </svg>
                    {{ $t('table.actions.create') }}
                </button>
            </div>

            <div class="table-alerts" v-if="errors.length > 0">
                <p v-for="error in errors">
                    {{ error }}
                </p>
            </div>

            <div class="table-info" v-if="totalRowsCount">
                <span>{{ $t('table.info.foundRecords') }}: {{ totalRowsCount }}</span>
            </div>

            <div class="table-rows" :class="{ 'fetching': fetching }">
                <template v-for="row in rows" :key="row.id">
                    <slot name="t-row">
                        <component v-if="rowTopComponent" :is="rowTopComponent" :row="row"></component>
                        <table-row :row="row"></table-row>
                        <component v-if="rowBottomComponent" :is="rowBottomComponent" :row="row"></component>
                    </slot>
                </template>
                <div v-if="!fetching && rows.length === 0" class="center">
                    <p class="alert">{{ $t('table.rows.noneFound') }}</p>
                </div>
            </div>

            <slot name="t-pager">
                <table-pager></table-pager>
            </slot>

            <table-edit-popup></table-edit-popup>
            <table-confirm-popup></table-confirm-popup>
        </div>
    `};export const TableFilters={setup(){const d=inject("iconsSvgPath"),{t:n}=inject("lang"),{fields:r,setFields:k}=inject("fields"),t=r.value.filter(e=>e.show!=="none"),o=ref(t.filter(e=>e.show!=="filter"&&!e.show).map(e=>e.name)),x=t.filter(e=>e.show!=="filter").map(e=>({id:e.name,label:e.label}));watch(o,e=>{const t=r.value.map(e=>({...e})).map(t=>t.show==="none"?t:e.includes(t.name)?(t.hasOwnProperty("show")&&delete t.show,t):{...t,show:"hide"});k(t)});const{filters:a,setSearch:c,setSortField:p,setSortAsc:f,setFilterValue:u,setFiltersValues:h,resetFilters:m}=inject("filters"),e=ref(i(a.fields)),s=ref(null);provide("adv-filters",{advancedPopupRef:s,reloadFilters:l,applyFilters:A,toggleFields:t,filtersFields:e,filterChanged:y,filterExactlyChanged:_,dateFilterChanged:w,dateModeChanged:O});function g(e){o.value=e}function v(e){c(e)}function b(e){p(e)}function j(e){f(e==="asc")}function y(t,n,s){if(s){e.value[t].value2=Utils.Trim(n);return}e.value[t].value=Utils.Trim(n)}function _(t,n){e.value[t].exactly=n.length>0}function w(t,n){e.value[t].dateFilter=n}function O(t,n){e.value[t].dateMode=n}function i(e){const t={...Utils.Unref(e)};for(const e in t)Object.hasOwnProperty.call(t,e)&&(t[e]={...t[e]});return t}function C(t){if(t==="_search_"){c("");return}u(t,""),e.value[t].value=""}function E(){m(),l()}function l(){const t=i(a.fields),n=e.value;for(const e in n)Object.hasOwnProperty.call(t,e)&&(n[e]={...t[e]})}function A(){h(i(e))}function S(){s.value&&s.value.show()}function M(t){const n=e.value[Utils.Unref(t).name].value;return typeof n!="number"&&!n}function F(t){const s=Utils.Unref(t),o=e.value[s.name].value;if(s.provider){const e=s.provider.get(o||"");return e?e.label:`(${n("unknown")})`}if(s.type==="bool")return n(o?"yes":"no");if(s.type==="date"){const t=e.value[s.name];return t.dateFilter==="[]"?[o,t.value2].join(" - "):t.dateFilter==="<"||t.dateFilter===">"?`${t.dateFilter} ${o}`:o}return o}return{iconsSvgPath:d,toggleFields:t,toggleFieldsSelected:o,toggleFieldsOptions:x,filters:a,toggleFieldsChanged:g,searchChanged:v,sortFieldChanged:b,sortAscChanged:j,emptyFilter:C,resetButtonHandler:E,showAdvancedFilters:S,isEmptyValue:M,display:F}},template:`
        <div class="table-filters">
            <slot name="f-search">
                <div class="filters-search">
                    <div class="main-search">
                        <label>
                            <span>{{ $t('table.search.title') }}:</span>
                            <input :value="filters.search" @input="searchChanged($event.target.value)" class="input" />
                        </label>

                        <button @click="showAdvancedFilters" class="button small">
                            <svg class="icon" aria-hidden="true" focusable="false">
                                <use v-bind:xlink:href="\`\${iconsSvgPath}#settings\`"></use>
                            </svg>
                            {{ $t('table.search.advanced') }}
                        </button>

                        <table-filters-popup></table-filters-popup>
                    </div>
                </div>
            </slot>
            <details class="filters-sort-details">
                <summary>{{ $t('table.sort.sorting') }} / {{ $t('table.toggleFields.title') }}</summary>

                <slot name="f-sort">
                    <div class="filters-sort">
                        <switcher @switched="sortFieldChanged" :label="$t('table.sort.sortBy') + ':'" :current="filters.sort.field" :options="toggleFieldsOptions">
                        </switcher>

                        <switcher @switched="sortAscChanged" :label="$t('table.sort.sortOrder') + ':'" :current="filters.sort.asc ? 'asc' : 'desc'" :options="[
                            { id: 'asc', label: $t('table.sort.asc') },
                            { id: 'desc', label: $t('table.sort.desc') },
                        ]"></switcher>
                    </div>
                </slot>
                <slot name="f-toggle-fields">
                    <div class="filters-toggle-fields">
                        <switcher @switched="toggleFieldsChanged" type="multiple" :label="$t('table.toggleFields.title') + ':'" :current="toggleFieldsSelected" :options="toggleFieldsOptions">
                        </switcher>
                    </div>
                </slot>
            </details>
            <slot name="f-active-filters">
                <div class="filters-active">
                    <span>{{ $t('table.activeFilters.title') }}:</span>

                    <button @click="resetButtonHandler" class="button error small">
                        {{ $t('reset') }}
                    </button>

                    <button v-if="filters.search" @click="emptyFilter('_search_')" class="button accent small">
                        <span>{{ $t('table.activeFilters.search') }}:</span> {{ filters.search }}
                        <svg class="icon" aria-hidden="true" focusable="false">
                            <use v-bind:xlink:href="\`\${iconsSvgPath}#close\`"></use>
                        </svg>
                    </button>

                    <template v-for="field in toggleFields" :key="field.name">
                        <template v-if="!isEmptyValue(field)">
                            <button @click="emptyFilter(field.name)" class="button accent small">
                                <span>{{ field.label }}:</span> {{ display(field) }}
                                <svg class="icon" aria-hidden="true" focusable="false">
                                    <use v-bind:xlink:href="\`\${iconsSvgPath}#close\`"></use>
                                </svg>
                            </button>
                        </template>
                    </template>
                </div>
            </slot>
        </div>
    `};export const TableRow={props:{row:Object},setup(){const e=inject("iconsSvgPath"),{fieldsShow:t,fieldsHide:n,fieldsWidth:s}=inject("fields"),{callAction:o}=inject("rowActions");return{iconsSvgPath:e,fieldsShow:t,fieldsHide:n,fieldsWidth:s,callAction:o}},template:`
        <div class="table-row">
            <div class="row-actions">
                <button class="button small" @click="callAction('edit', row)">
                    <svg class="icon" aria-hidden="true" focusable="false">
                        <use v-bind:xlink:href="\`\${iconsSvgPath}#edit\`"></use>
                    </svg>
                    {{ $t('edit') }}
                </button>
                <button class="button error small" @click="callAction('delete', row)">
                    <svg class="icon" aria-hidden="true" focusable="false">
                        <use v-bind:xlink:href="\`\${iconsSvgPath}#close\`"></use>
                    </svg>
                    {{ $t('delete') }}
                </button>
            </div>
            <div class="fields-show" :class="['count-' + fieldsShow.length]">
                <template v-for="(field, i) in fieldsShow" :key="field.name">
                    <slot name="t-field-show">
                        <table-field :field="field" :width="fieldsWidth.show[i]" :value="row[field.name]"></table-field>
                    </slot>
                </template>
            </div>
            <div class="fields-hide" v-if="fieldsHide.length > 0" :class="['count-' + fieldsHide.length]">
                <details>
                    <div class="content">
                        <template v-for="(field, i) in fieldsHide" :key="field.name">
                            <slot name="t-field-hide">
                                <table-field :field="field" :width="fieldsWidth.hide[i]" :value="row[field.name]"></table-field>
                            </slot>
                        </template>
                    </div>
                    <summary></summary>
                </details>
            </div>
        </div>
    `};export const TableField={props:{field:Object,width:Number,value:[String,Number,Boolean,Array,Object]},setup(){const{t:e}=inject("lang");function t(t,n){const s=Utils.Unref(t),o=Utils.Unref(n);if(s.provider){const t=s.provider.get(o||"");return t?t.label:`(${e("unknown")})`}return s.type==="bool"?e(o?"yes":"no"):o}return{display:t}},template:`
        <div class="table-field" :class="[
                \`w-\${width}\`,
                field.type ? \`type-\${field.type}\` : 'type-default',
                value === '' || value === null ? 'empty-field' : '',
            ]">
            <div><span>{{ field.label }}</span></div>
            <div>
                <pre v-if="field.type === 'uid' || field.type === 'id'">{{ display(field, value) }}</pre>
                <template v-else-if="field.type === 'text' && value">
                    <p v-for="line in \`\${display(field, value)}\`.split(\`
\`)">{{ line }}</p>
                </template>
                <span v-else>{{ display(field, value) }}</span>
            </div>
        </div>
    `};export const TablePager={setup(){const{pager:e,setCurrentPage:t}=inject("pager");return{pager:e,setCurrentPage:t}},template:`
        <div class="table-pager">
            <pager :set-page-handler="setCurrentPage" :current="pager.currentPage" :max="pager.totalPages"></pager>
        </div>
    `};export const TableConfirmPopup={setup(){const{popupContainer:e,confirmPopupRef:t,confirmPopup:n}=inject("popup");return{popupContainer:e,confirmPopupRef:t,confirmPopup:n}},template:`
        <teleport :to="popupContainer">
            <popup ref="confirmPopupRef" :prevent-close="confirmPopup.preventClose" :close-handler="confirmPopup.actionHandler" :buttons="confirmPopup.buttons">
                <template v-if="confirmPopup.header" v-slot:header>
                    <p>{{ confirmPopup.header }}</p>
                </template>

                <p class="center">{{ confirmPopup.message }}</p>
            </popup>
        </teleport>
    `};export const TableEditPopup={setup(){const{fields:n}=inject("fields"),{popupContainer:u,editPopupRef:d,editPopup:o,editPopupData:i}=inject("popup"),{t:e,getCurrentLang:r}=inject("lang"),{theme:a}=inject("theme"),c=r(),l=Theme.IsDark(a.value),s=ref([]);watch(()=>o.value.isNew,e=>{s.value=e?n.value.filter(e=>e.name!=="id"&&e.show!=="none"&&e.show!=="filter"&&(!e.edit||e.edit==="new")):n.value.filter(e=>e.show!=="none"&&e.show!=="filter"&&(!e.edit||e.edit==="edit"))});const t=new Date,h=[{label:e("datepicker.now"),value:t},{label:e("datepicker.yesterday"),value:Utils.DateAdd(t,{day:-1})},{label:e("datepicker.tomorrow"),value:Utils.DateAdd(t,{day:1})},{label:"- "+e("datepicker.week"),value:Utils.DateAdd(t,{day:-7})},{label:"+ "+e("datepicker.week"),value:Utils.DateAdd(t,{day:7})},{label:"- "+e("datepicker.month"),value:Utils.DateAdd(t,{month:-1})},{label:"+ "+e("datepicker.month"),value:Utils.DateAdd(t,{month:1})},{label:"- 6 "+e("datepicker.months"),value:Utils.DateAdd(t,{month:-6})},{label:"+ 6 "+e("datepicker.months"),value:Utils.DateAdd(t,{month:6})},{label:"- 12 "+e("datepicker.months"),value:Utils.DateAdd(t,{year:-1})},{label:"+ 12 "+e("datepicker.months"),value:Utils.DateAdd(t,{year:1})}],m={clearable:!1,teleportCenter:!0,autoApply:!0,yearRange:[2010,2100],startDate:new Date,enableSeconds:!0,dark:l,locale:c,selectText:e("datepicker.select"),cancelText:e("datepicker.cancel"),nowButtonLabel:e("datepicker.now")};function f(e,t){i.value[e].value=Utils.Trim(t)}return{popupContainer:u,editPopupRef:d,editPopup:o,editFields:s,editPopupData:i,fieldChanged:f,presetDates:h,datePickerProps:m}},template:`
        <teleport :to="popupContainer">
            <popup class="wide" ref="editPopupRef" :close-handler="editPopup.actionHandler" :buttons="editPopup.buttons">
                <template v-if="editPopup.header" v-slot:header>
                    <p>{{ editPopup.header }}</p>
                </template>

                <div class="edit-row fields">
                    <div v-for="field in editFields" :key="field.name" class="field" :class="['type-' + field.type]">
                        <label>
                            <span>{{ field.label }}</span>
                            <switcher v-if="field.provider" type="select" :current="editPopupData[field.name].value || ''" @switched="data => fieldChanged(field.name, data)" :options="field.provider.getAll()"></switcher>
                            <textarea v-else-if="field.type === 'text'" :value="editPopupData[field.name].value" @input="fieldChanged(field.name, $event.target.value)" class="input small"></textarea>
                            <switcher v-else-if="field.type === 'bool'" :current="editPopupData[field.name].value" @switched="data => fieldChanged(field.name, data)" :options="[
                                { id: 1, label: $t('yes') },
                                { id: 0, label: $t('no') },
                            ]"></switcher>
                            <input v-else :value="editPopupData[field.name].value" @input="fieldChanged(field.name, $event.target.value)" class="input small" />
                        </label>
                        <vue-date-picker v-if="field.type === 'date'"
                            v-model="editPopupData[field.name].value"
                            model-type="yyyy-MM-dd HH:mm:ss"
                            format="yyyy-MM-dd HH:mm:ss"
                            :preset-dates="presetDates"
                            v-bind="datePickerProps">
                        </vue-date-picker>
                    </div>
                </div>
            </popup>
        </teleport>
    `};export const TableFiltersPopup={setup(){const{advancedPopupRef:o,reloadFilters:b,applyFilters:s,toggleFields:v,filtersFields:d,filterChanged:i,filterExactlyChanged:a,dateFilterChanged:r,dateModeChanged:c}=inject("adv-filters"),{popupContainer:l}=inject("popup"),{t:e,getCurrentLang:u}=inject("lang"),{theme:h}=inject("theme"),m=u(),f=Theme.IsDark(h.value),p=[{class:"button accent",label:e("apply"),value:"ok"},{class:"button alt",label:e("cancel"),value:"cancel"}],g={clearable:!1,teleportCenter:!0,autoApply:!0,yearRange:[2010,2100],startDate:new Date,dark:f,locale:m,selectText:e("datepicker.select"),cancelText:e("datepicker.cancel"),nowButtonLabel:e("datepicker.now")},t=new Date,n={day:[{label:e("datepicker.now"),value:t},{label:e("datepicker.yesterday"),value:Utils.DateAdd(t,{day:-1})},{label:e("datepicker.tomorrow"),value:Utils.DateAdd(t,{day:1})},{label:"- "+e("datepicker.week"),value:Utils.DateAdd(t,{day:-7})},{label:"+ "+e("datepicker.week"),value:Utils.DateAdd(t,{day:7})},{label:"- "+e("datepicker.month"),value:Utils.DateAdd(t,{month:-1})},{label:"+ "+e("datepicker.month"),value:Utils.DateAdd(t,{month:1})},{label:"- 6 "+e("datepicker.months"),value:Utils.DateAdd(t,{month:-6})},{label:"+ 6 "+e("datepicker.months"),value:Utils.DateAdd(t,{month:6})},{label:"- 12 "+e("datepicker.months"),value:Utils.DateAdd(t,{year:-1})},{label:"+ 12 "+e("datepicker.months"),value:Utils.DateAdd(t,{year:1})}],month:[{label:e("current"),value:t},{label:e("previous"),value:Utils.DateAdd(t,{month:-1})},{label:e("next"),value:Utils.DateAdd(t,{month:1})},{label:"- 6 "+e("datepicker.months"),value:Utils.DateAdd(t,{month:-6})},{label:"+ 6 "+e("datepicker.months"),value:Utils.DateAdd(t,{month:6})},{label:"- 12 "+e("datepicker.months"),value:Utils.DateAdd(t,{year:-1})},{label:"+ 12 "+e("datepicker.months"),value:Utils.DateAdd(t,{year:1})}],year:[]},j=[{id:"exactly",label:e("table.exactly")}],y=[{id:"=",label:"="},{id:"<",label:"<"},{id:">",label:">"},{id:"[]",label:"[ ]"}],_=[{id:"exactly",label:e("datepicker.exactly")},{id:"day",label:e("datepicker.day")},{id:"month",label:e("datepicker.month")},{id:"year",label:e("datepicker.year")}];function w(e){return e!=="ok"?(b(),!0):(s(),!0)}function O(e,t){return e.dateFilter==="[]"?t==="day":e.dateMode===t}function x(e){return!e||e.dateFilter==="[]"?"yyyy-MM-dd HH:mm":e.dateMode==="day"?"yyyy-MM-dd":e.dateMode==="month"?"yyyy-MM":e.dateMode==="year"?"yyyy":"yyyy-MM-dd HH:mm"}function C(e){return e&&e.dateMode==="year"?n.year:e&&e.dateMode==="month"?n.month:n.day}return{popupContainer:l,popupButtons:p,exactlySwitcherOptions:j,dateFilterOptions:y,dateModeOptions:_,popupHandler:w,advancedPopupRef:o,applyFilters:s,toggleFields:v,filtersFields:d,filterChanged:i,filterExactlyChanged:a,dateFilterChanged:r,dateModeChanged:c,getDateFormat:x,datePickerProps:g,isDateMode:O,getPresetDates:C}},template:`
        <teleport :to="popupContainer">
            <popup class="wide" ref="advancedPopupRef" :close-handler="popupHandler" :buttons="popupButtons">
                <template v-slot:header>
                    <p>{{ $t('table.searchPopup.title') }}</p>
                </template>

                <div class="advanced-search fields">
                    <div v-for="field in toggleFields" :key="field.name" class="field" :class="['type-' + field.type]">
                        <label>
                            <span>{{ field.label }}</span>
                            <switcher v-if="field.provider" :current="filtersFields[field.name].value" @switched="data => filterChanged(field.name, data)" type="select" :options="field.provider.getAll()"></switcher>
                            <textarea v-else-if="field.type === 'text'" :value="filtersFields[field.name].value" @input="filterChanged(field.name, $event.target.value)" class="input small"></textarea>
                            <switcher v-else-if="field.type === 'bool'" :current="filtersFields[field.name].value" @switched="data => filterChanged(field.name, data)" :options="[
                                { id: 1, label: $t('yes') },
                                { id: 0, label: $t('no') },
                                { id: '', label: $t('notSet') },
                            ]"></switcher>
                            <input v-else-if="field.type === 'date' && filtersFields[field.name].dateFilter === '[]'" :value="filtersFields[field.name].value" @input="filterChanged(field.name, $event.target.value)" class="input small" :placeholder="$t('datepicker.from')" />
                            <input v-else :value="filtersFields[field.name].value" @input="filterChanged(field.name, $event.target.value)" class="input small" />
                        </label>
                        <vue-date-picker v-if="field.type === 'date'"
                            v-model="filtersFields[field.name].value"
                            :model-type="getDateFormat(filtersFields[field.name])"
                            :format="getDateFormat(filtersFields[field.name])"
                            :preset-dates="getPresetDates(filtersFields[field.name])"
                            :month-picker="isDateMode(filtersFields[field.name], 'month')"
                            :year-picker="isDateMode(filtersFields[field.name], 'year')"
                            v-bind="datePickerProps">
                        </vue-date-picker>
                        <switcher v-if="!field.provider && (field.type === 'string' || field.type === 'text' || field.type === 'uid')" class="exactly-toggle" @switched="data => filterExactlyChanged(field.name, data)" type="multiple" :current="filtersFields[field.name].exactly ? 'exactly' : ''" :options="exactlySwitcherOptions">
                        </switcher>
                        <switcher v-else-if="!field.provider && (field.type === 'date')" class="filter-modifier" @switched="data => dateFilterChanged(field.name, data)" type="select" :current="filtersFields[field.name].dateFilter || '='" :options="dateFilterOptions">
                        </switcher>
                        <switcher v-if="!field.provider && (field.type === 'date') && filtersFields[field.name].dateFilter !== '[]'" class="filter-date-mode" @switched="data => dateModeChanged(field.name, data)" :current="filtersFields[field.name].dateMode || 'exactly'" :options="dateModeOptions">
                        </switcher>

                        <template v-if="!field.provider && (field.type === 'date') && filtersFields[field.name].dateFilter === '[]'">
                        <label>
                            <span></span>
                            <input :value="filtersFields[field.name].value2" @input="filterChanged(field.name, $event.target.value2, true)" class="input small" :placeholder="$t('datepicker.to')" />
                        </label>
                        <vue-date-picker
                            v-model="filtersFields[field.name].value2"
                            :model-type="getDateFormat(filtersFields[field.name])"
                            :format="getDateFormat(filtersFields[field.name])"
                            :preset-dates="getPresetDates(filtersFields[field.name])"
                            v-bind="datePickerProps">
                        </vue-date-picker>
                        </template>
                    </div>
                </div>
            </popup>
        </teleport>
    `};export function getTableComponents(){return{TableContainer,TableFilters,TableRow,TableField,TablePager,TableConfirmPopup,TableEditPopup,TableFiltersPopup,VueDatePicker:VueDatePicker.default}}