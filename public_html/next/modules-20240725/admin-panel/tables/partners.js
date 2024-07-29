import{inject}from"../vendor/vue.esm-browser.js";import{App,JsonApi}from"../shared/admin-shared.js";import*as Table from"../shared/table.js";const PartnersRoot={setup(){const{t:e}=inject("lang"),t={getData(e){return JsonApi.Post("/next/admin-panel/api/clients/get-partners",e)},saveData(e){return JsonApi.Post("/next/admin-panel/api/clients/save-partners",e)},deleteData(e){return JsonApi.Post("/next/admin-panel/api/clients/delete-partners",e)}},n=[{name:"id",label:e("fields.id"),type:"uid",show:"hide",edit:"none"},{name:"title",label:e("fields.title"),type:"string"},{name:"description",label:e("fields.description"),type:"text",width:2},{name:"value",label:e("fields.value"),type:"int",show:"hide",edit:"new"}];return{dataProvider:t,fields:n}},template:`
        <ap-page :page-title="$t('pages.partners')">
            <div class="main-content wide">
                <div class="page">
                    <table-container :data-provider="dataProvider" :fields="fields"></table-container>
                </div>
            </div>
        </ap-page>
    `};App.Mount(PartnersRoot,Table.getTableComponents()),App.Run()