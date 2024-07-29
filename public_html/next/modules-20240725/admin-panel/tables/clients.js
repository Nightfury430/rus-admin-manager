import{inject,ref,shallowRef,watch}from"../vendor/vue.esm-browser.js";import{App,GlobalState,JsonApi,Utils}from"../shared/admin-shared.js";import*as Table from"../shared/table.js";const ClientsSubaccountsTab={props:{clientTitle:String,row:Object,data:[null,Array],loadData:[null,Function]},setup(e){const y=e.clientTitle,o=ref(e.data),f=e.loadData||(()=>{}),{fields:u}=inject("fields"),{callAction:a}=inject("rowActions"),l=inject("iconsSvgPath"),{t}=inject("lang"),{confirmPopupDefaultOptions:c,confirmPopupRef:s,confirmPopup:d}=inject("popup"),r=["name","login","folder","active","date_created","date_end","deleted","is_test","comments"],h=r.map(e=>u.value.find(t=>t.name===e));return watch(()=>e.data,e=>{o.value=e}),{title:y,data:o,fieldsShow:h,iconsSvgPath:l,callAction:a,addAction:m,onActionComplete:i,resetPassword:g,sendCreateEmail:b};function m(){a("add",null,i,p)}function i(e,t){return!!t&&!!t.length&&(f("subs"),!1)}function p(t,n){if(t!=="add")return;const s=e.row,o=["login","tariff","date_end","partner","active","is_phys","in_crm","is_test"];for(const e of o)n[e]=s[e];n.master=s.ci_id}function g(e){n({message:t("clients.resetPasswordPopup.title"),buttons:[{class:"button error",label:t("clients.execute"),value:"execute"},{class:"button alt",label:t("cancel"),value:"cancel"}],actionHandler:t=>t!=="execute"||(v(e),!1)})}async function v(e){s.value&&s.value.close();const o={id:e.id},i=await JsonApi.Post("/next/admin-panel/api/clients/reset-password",o),a=JsonApi.GetErrorsForForm(i);if(a.length>0){n({header:t("error"),message:t("errorOccurred"),buttons:[{class:"button error",label:t("ok"),value:"ok"}]});return}n({message:t("clients.requestSent"),buttons:[{class:"button error",label:t("ok"),value:"ok"}]})}function b(e){n({message:t("clients.sendEmailPopup.title"),buttons:[{class:"button error",label:t("clients.execute"),value:"execute"},{class:"button alt",label:t("cancel"),value:"cancel"}],actionHandler:t=>t!=="execute"||(j("account-created",e),!1)})}async function j(e,o){if(s.value&&s.value.close(),e!=="account-created")return;const i={type:e,id:o.id},a=await JsonApi.Post("/next/admin-panel/api/clients/send-email",i),r=JsonApi.GetErrorsForForm(a);if(r.length>0){n({header:t("error"),message:t("errorOccurred"),buttons:[{class:"button error",label:t("ok"),value:"ok"}]});return}n({message:t("clients.sendEmailPopup.successText"),buttons:[{class:"button error",label:t("ok"),value:"ok"}]})}function n(e){if(!e)return;const t=Object.assign({},c,e);d.value=t,s.value&&s.value.show()}},template:`
        <div>
            <p class="title">{{ $t('clients.subaccountsTitle') }} {{ title }}</p>

            <div>
                <button class="button" @click="addAction">
                    <svg class="icon" aria-hidden="true" focusable="false">
                        <use v-bind:xlink:href="\`\${iconsSvgPath}#add\`"></use>
                    </svg>
                    {{ $t('table.actions.create') }}
                </button>
            </div>

            <div v-if="data === null">
                <p>{{ $t('clients.loadingData') }}</p>
            </div>
            <div v-else-if="data.length === 0">
                <p>{{ $t('table.rows.noneFound') }}</p>
            </div>
            <div v-else>
                <div v-for="row in data" :key="row.id">
                    <div class="row-actions">
                        <button class="button small" @click="resetPassword(row)">
                            {{ $t('clients.actions.resetPassword') }}
                        </button>
                        <button class="button small" @click="sendCreateEmail(row)">
                            {{ $t('clients.actions.sendCreateEmail') }}
                        </button>
                        <button class="button small" @click="callAction('edit', row, onActionComplete)">
                            <svg class="icon" aria-hidden="true" focusable="false">
                                <use v-bind:xlink:href="\`\${iconsSvgPath}#edit\`"></use>
                            </svg>
                            {{ $t('edit') }}
                        </button>
                        <button class="button error small" @click="callAction('delete', row, onActionComplete)">
                            <svg class="icon" aria-hidden="true" focusable="false">
                                <use v-bind:xlink:href="\`\${iconsSvgPath}#close\`"></use>
                            </svg>
                            {{ $t('delete') }}
                        </button>
                    </div>

                    <div class="fields-show">
                        <template v-for="field in fieldsShow" :key="field.name">
                            <table-field :field="field" :width="field.name === 'comments' ? 4 : 1" :value="row[field.name]"></table-field>
                        </template>
                    </div>
                </div>
            </div>
        </div>
    `},ClientsServicesTab={props:{clientTitle:String,row:Object,data:[null,Array]},setup(e){const d=e.row,o=e.clientTitle,n=ref(e.data),{t}=inject("lang"),i=inject("iconsSvgPath"),a=fieldDataProvider("services"),s=[{name:"value",label:t("fields.value"),type:"string"},{name:"date_created",label:t("fields.dateCreated"),type:"date"},{name:"date_suspended",label:t("services.fields.dateSuspended"),type:"date"},{name:"date_end",label:t("fields.dateEnd"),type:"date"}],r={name:"service_id",label:t("fields.title"),type:"string",provider:a};watch(()=>e.data,e=>{n.value=e});function c(e,n){const s=Utils.Unref(e),o=Utils.Unref(n);if(s.provider){const e=s.provider.get(o||"");return e?e.label:`(${t("unknown")})`}return s.type==="bool"?t(o?"yes":"no"):o}function l(){}return{title:o,data:n,fieldsShow:s,fieldTitle:r,iconsSvgPath:i,display:c,callAction:l}},template:`
        <div>
            <p class="title">{{ $t('clients.servicesTitle') }} {{ title }}</p>

            <div v-if="data === null">
                <p>{{ $t('clients.loadingData') }}</p>
            </div>
            <div v-else-if="data.length === 0">
                <p>{{ $t('table.rows.noneFound') }}</p>
            </div>
            <div v-else>
                <div v-for="row in data" :key="row.id" class="fields-show">
                    <div class="row-actions">
                        <span class="left-part">{{ $t('clients.service') }}: {{ display(fieldTitle, row['service_id']) }}</span>

                        <button class="button small hidden" @click="callAction('edit', row)">
                            <svg class="icon" aria-hidden="true" focusable="false">
                                <use v-bind:xlink:href="\`\${iconsSvgPath}#edit\`"></use>
                            </svg>
                            {{ $t('edit') }}
                        </button>
                        <button class="button error small hidden" @click="callAction('delete', row)">
                            <svg class="icon" aria-hidden="true" focusable="false">
                                <use v-bind:xlink:href="\`\${iconsSvgPath}#close\`"></use>
                            </svg>
                            {{ $t('delete') }}
                        </button>
                    </div>

                    <template v-for="field in fieldsShow" :key="field.name">
                        <table-field :field="field" :width="1" :value="row[field.name]"></table-field>
                    </template>
                </div>
            </div>
        </div>
    `},ClientsActionsTab={props:{clientTitle:String,row:Object},setup(e){const o=e.row,i=e.clientTitle,{t}=inject("lang"),{confirmPopupDefaultOptions:a,confirmPopupRef:n,confirmPopup:r}=inject("popup");function c(){s({message:t("clients.resetPasswordPopup.title"),buttons:[{class:"button error",label:t("clients.execute"),value:"execute"},{class:"button alt",label:t("cancel"),value:"cancel"}],actionHandler:e=>e!=="execute"||(l(),!1)})}async function l(){n.value&&n.value.close();const e={id:o.id},i=await JsonApi.Post("/next/admin-panel/api/clients/reset-password",e),a=JsonApi.GetErrorsForForm(i);if(a.length>0){s({header:t("error"),message:t("errorOccurred"),buttons:[{class:"button error",label:t("ok"),value:"ok"}]});return}s({message:t("clients.requestSent"),buttons:[{class:"button error",label:t("ok"),value:"ok"}]})}function d(){s({message:t("clients.sendEmailPopup.title"),buttons:[{class:"button error",label:t("clients.execute"),value:"execute"},{class:"button alt",label:t("cancel"),value:"cancel"}],actionHandler:e=>e!=="execute"||(u("account-created"),!1)})}async function u(e){if(n.value&&n.value.close(),e!=="account-created")return;const i={type:e,id:o.id},a=await JsonApi.Post("/next/admin-panel/api/clients/send-email",i),r=JsonApi.GetErrorsForForm(a);if(r.length>0){s({header:t("error"),message:t("errorOccurred"),buttons:[{class:"button error",label:t("ok"),value:"ok"}]});return}s({message:t("clients.sendEmailPopup.successText"),buttons:[{class:"button error",label:t("ok"),value:"ok"}]})}function s(e){if(!e)return;const t=Object.assign({},a,e);r.value=t,n.value&&n.value.show()}return{row:o,title:i,resetPassword:c,sendCreateEmail:d}},template:`
        <div>
            <p class="title">{{ $t('clients.actionsTitle') }} {{ title }}</p>

            <div>
                <p>
                    {{ $t('clients.actions.resetPassword') }}

                    <button class="button small" @click="resetPassword">
                        {{ $t('clients.execute') }}
                    </button>
                </p>
                <p>
                    {{ $t('clients.actions.sendCreateEmail') }}

                    <button class="button small" @click="sendCreateEmail">
                        {{ $t('clients.execute') }}
                    </button>
                </p>
            </div>
        </div>
    `},ClientsRowTop={props:{row:Object},setup(e){const t=shallowRef(e.row),o=ref(0),n=shallowRef(null),s=shallowRef(null),{t:c}=inject("lang");watch(()=>e.row,e=>{t.value=e});const h=[["",""],["subs-tab",ClientsSubaccountsTab],["services-tab",ClientsServicesTab],["actions-tab",ClientsActionsTab]];let i=[t.value.name,t.value.login].filter(Boolean).join(" / ").trim();return i||(i=`${c("fields.id")} ${t.value.id}`),{row:t,activeTab:o,subaccounts:n,services:s,tabs:h,clientTitle:i,tabButtonHandler:l,getTabData:m,loadData:u};async function l(e){if(o.value=e,e===0)return;if(e===1){if(n.value!==null)return;await r();return}if(e===2){if(s.value!==null)return;await d();return}if(e===3)return}async function r(){if(n.value=null,t.value.master!==0){n.value=[];return}const e={pager:!1,filters:{sort:{field:"id",asc:!1},fields:{master:{value:t.value.ci_id}}}},s=["date_created","date_end","deleted"],{errors:o,rows:i}=await a("/next/admin-panel/api/clients/get-clients",e,s);n.value=o.length>0?null:i}async function d(){if(s.value=null,!t.value.id){s.value=[];return}const e={pager:!1,filters:{sort:{field:"id",asc:!1},fields:{client_id:{value:t.value.id}}}},n=["date_created","date_suspended","date_end"],{errors:o,rows:i}=await a("/next/admin-panel/api/clients/get-client-services",e,n);s.value=o.length>0?null:i}async function u(e){e==="subs"&&await r()}async function a(e,t,n){t&&t.filters&&(t.filters.dateTimeZone=Intl.DateTimeFormat().resolvedOptions().timeZone);const s=await JsonApi.Post(e,t),o=JsonApi.GetErrorsForForm(s);if(o.length>0)return{errors:o,rows:[]};const i=s.data&&s.data.rows?s.data.rows:[];return n&&n.length>0&&i.forEach(e=>{for(let t=0;t<n.length;t++)if(e[n[t]]){const s=Utils.DateParseUTC(e[n[t]]);s&&(e[n[t]]=Utils.FormatDateAndTime(s))}}),{errors:[],rows:i}}function m(){return o.value===1?n.value:o.value===2?s.value:null}},template:`
        <div class="table-row-top">
            <ul class="tabs-buttons">
                <li><button class="button alt" :class="{ 'accent': activeTab === 0 }" @click="tabButtonHandler(0)">{{ $t('data') }}</button></li>
                <li><button class="button alt" :class="{ 'accent': activeTab === 1 }" @click="tabButtonHandler(1)">{{ $t('subaccounts') }}</button></li>
                <li><button class="button alt" :class="{ 'accent': activeTab === 2 }" @click="tabButtonHandler(2)">{{ $t('services') }}</button></li>
                <li><button class="button alt" :class="{ 'accent': activeTab === 3 }" @click="tabButtonHandler(3)">{{ $t('actions') }}</button></li>
            </ul>
        </div>

        <div class="table-row-tab" :class="{ 'active': activeTab !== 0 }">
            <component
                v-if="activeTab !== 0"
                :is="tabs[activeTab][1]"
                :class="[tabs[activeTab][0]]"
                :row="row"
                :client-title="clientTitle"
                :data="getTabData()"
                :load-data="loadData">
            </component>
        </div>
    `},ClientsRoot={setup(){const{t:e}=inject("lang"),n={getData(e){return JsonApi.Post("/next/admin-panel/api/clients/get-clients",e)},saveData(e){return JsonApi.Post("/next/admin-panel/api/clients/save-clients",e)},deleteData(e){return JsonApi.Post("/next/admin-panel/api/clients/delete-clients",e)}},s={softDelete:!0},o=fieldDataProvider("partners"),t=fieldDataProvider("tariffs"),i=fieldDataProvider("services"),a=[{name:"id",label:e("fields.id"),type:"uid",show:"hide",edit:"none"},{name:"ci_id",label:"ci_id",type:"int",show:"none",edit:"none"},{name:"name",label:e("fields.name"),type:"string"},{name:"login",label:e("fields.login"),type:"string"},{name:"email",label:e("fields.email"),type:"string"},{name:"phone",label:e("clients.fields.phone"),type:"string"},{name:"contact",label:e("clients.fields.contact"),type:"string"},{name:"inn",label:e("clients.fields.inn"),type:"string"},{name:"site",label:e("clients.fields.site"),type:"string",show:"hide"},{name:"region",label:e("clients.fields.region"),type:"string",show:"hide"},{name:"tariff",label:e("clients.fields.tariff"),type:"int",show:"hide",provider:t},{name:"last_tariff",label:e("clients.fields.lastTariff"),type:"int",show:"hide",provider:t},{name:"last_tariff_date",label:e("clients.fields.lastTariffDate"),type:"date",show:"hide"},{name:"date_created",label:e("fields.dateCreated"),type:"date",show:"hide",edit:"none"},{name:"date_end",label:e("fields.dateEnd"),type:"date",show:"hide"},{name:"deleted",label:e("fields.deletedAt"),type:"date",show:"hide",edit:"none"},{name:"server",label:e("clients.fields.server"),type:"string",show:"hide"},{name:"folder",label:e("clients.fields.folder"),type:"string",show:"hide",edit:"new"},{name:"login_id",label:e("clients.fields.loginId"),type:"string",show:"hide"},{name:"partner",label:e("clients.fields.partner"),type:"int",show:"hide",provider:o},{name:"active",label:e("fields.active"),type:"bool",show:"hide",default:0},{name:"is_phys",label:e("clients.fields.isPhys"),type:"bool",show:"hide",default:0},{name:"in_crm",label:e("clients.fields.inCrm"),type:"bool",show:"hide",default:0},{name:"is_test",label:e("clients.fields.isTest"),type:"bool",show:"hide",default:0},{name:"master",label:"master",type:"int",show:"none",send:!0,default:0},{name:"comments",label:e("fields.comments"),type:"text",width:2},{name:"service_id",label:e("services")+" - "+e("fields.title"),type:"string",show:"filter",provider:i},{name:"service_value",label:e("services")+" - "+e("fields.value"),type:"string",show:"filter"},{name:"service_date_created",label:e("services")+" - "+e("fields.dateCreated"),type:"date",show:"filter"},{name:"service_date_suspended",label:e("services")+" - "+e("services.fields.dateSuspended"),type:"date",show:"filter"},{name:"service_date_end",label:e("services")+" - "+e("fields.dateEnd"),type:"date",show:"filter"}];return{dataProvider:n,pager:s,fields:a,clientsRowTop:ClientsRowTop}},template:`
        <ap-page :page-title="$t('pages.clients')">
            <div class="main-content wide">
                <div class="page">
                    <table-container
                        :data-provider="dataProvider"
                        :pager="pager"
                        :fields="fields"
                        :row-top-component="clientsRowTop">
                    </table-container>
                </div>
            </div>
        </ap-page>
    `};function fieldDataProvider(e){return{data:GlobalState.Get(e),get(e){return this.data.get(e)},getAll(){return Array.from(this.data.values())}}}App.Ready(async()=>{const t={tables:["partners","tariffs","services"]},e=await JsonApi.Post("/next/admin-panel/api/clients/get-table-data",t);if(!e.ok||!e.data)return;const n=e.data.partners.rows.map(e=>[e.value,{id:e.value,label:e.title}]),s=e.data.tariffs.rows.map(e=>[e.value,{id:e.value,label:e.title}]),o=e.data.services.rows.map(e=>[e.id,{id:e.id,label:e.title}]);GlobalState.Set("partners",new Map([["",{id:"",label:""}],...n])),GlobalState.Set("tariffs",new Map([["",{id:"",label:""}],...s])),GlobalState.Set("services",new Map([["",{id:"",label:""}],...o]))}),App.Mount(ClientsRoot,{...Table.getTableComponents(),ClientsSubaccountsTab,ClientsServicesTab,ClientsRowTop}),App.Run()