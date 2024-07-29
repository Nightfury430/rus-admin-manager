import{inject}from"../vendor/vue.esm-browser.js";import{App,JsonApi}from"../shared/admin-shared.js";import*as Table from"../shared/table.js";const TariffsRoot={setup(){const{t:e}=inject("lang"),t={getData(e){return JsonApi.Post("/next/admin-panel/api/clients/get-tariffs",e)},saveData(e){return JsonApi.Post("/next/admin-panel/api/clients/save-tariffs",e)},deleteData(e){return JsonApi.Post("/next/admin-panel/api/clients/delete-tariffs",e)}},n=[{name:"id",label:e("fields.id"),type:"uid",show:"hide",edit:"none"},{name:"title",label:e("fields.title"),type:"string"},{name:"description",label:e("fields.description"),type:"text",width:2},{name:"alias",label:e("fields.alias"),type:"string",show:"hide",edit:"new"},{name:"value",label:e("fields.value"),type:"int",show:"hide",edit:"new"},{name:"enabled",label:e("fields.enabled"),type:"bool",show:"hide",default:1}];return{dataProvider:t,fields:n}},template:`
        <ap-page :page-title="$t('pages.tariffs')">
            <div class="main-content wide">
                <div class="page">
                    <div class="table-top-text"><p>{{ $t('tariffs.titleWarning', [$t('fields.title')]) }}</p></div>
                    <table-container :data-provider="dataProvider" :fields="fields"></table-container>
                </div>
            </div>
        </ap-page>
    `};App.Mount(TariffsRoot,Table.getTableComponents()),App.Run()