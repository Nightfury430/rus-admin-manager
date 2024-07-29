import{ref}from"../vendor/vue.esm-browser.js";import{App,JsonApi}from"../shared/admin-shared.js";const InitRoot={template:`
        <ap-page :page-title="$t('pages.init')">
            <template v-slot:app-top>
                <nav class="main-nav">
                    <ul></ul>
                </nav>
                <top-prefs no-account></top-prefs>
            </template>

            <div class="main-content">
                <div class="page main-block">
                    <create-super-user-form></create-super-user-form>
                </div>
            </div>
        </ap-page>
    `},CreateSuperUserForm={setup(){const e=ref(""),t=ref(""),n=ref(""),s=ref([]);async function o(){n.value="",s.value=[];const a={email:e.value,password:t.value},o=await JsonApi.Post("/next/admin-panel/api/users/create-super-user",a);console.log(o);const i=JsonApi.GetErrorsForForm(o);if(i.length>0){s.value=i;return}e.value="",t.value="",n.value="User created!"}return{email:e,password:t,message:n,errors:s,onSubmit:o}}};App.Mount(InitRoot,{CreateSuperUserForm}),App.Run()