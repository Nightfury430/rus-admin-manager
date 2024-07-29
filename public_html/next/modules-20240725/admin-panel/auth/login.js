import{ref}from"../vendor/vue.esm-browser.js";import{App,JsonApi}from"../shared/admin-shared.js";const LoginRoot={template:`
        <ap-page :page-title="$t('pages.login')">
            <template v-slot:app-top>
                <nav class="main-nav">
                    <ul></ul>
                </nav>
                <top-prefs no-account></top-prefs>
            </template>

            <div class="main-content">
                <div class="page main-block">
                    <login-form></login-form>
                </div>
            </div>
        </ap-page>
    `},LoginForm={setup(){const t=ref(""),n=ref(""),e=ref([]);async function s(){e.value=[];const i={email:t.value,password:n.value},s=await JsonApi.Post("/next/admin-panel/api/auth/login",i);console.log(s);const o=JsonApi.GetErrorsForForm(s);if(o.length>0){e.value=o;return}}return{email:t,password:n,errors:e,onSubmit:s}}};App.Mount(LoginRoot,{LoginForm}),App.Run()