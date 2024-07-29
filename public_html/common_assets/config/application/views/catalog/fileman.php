<script src="/common_assets/libs/vue.min.js"></script>
<script src="/common_assets/admin_js/vue/filemanager2.js?<?php echo md5(date('m-d-Y-His A e'));?>"></script>

<div class="ibox">
    <div class="ibox-content">
        <div id="bp_app">
            <filemanager></filemanager>
        </div>
    </div>
</div>



<script>

    function init_vue() {
        app = new Vue({
            el: '#bp_app',
            components:{
            },
            data: {
                url: glob.base_url + '/catalog/' + 'filemanager_tree',
                folder_url: glob.base_url + '/catalog/' + 'create_folder',
                file_url: glob.base_url + '/catalog/' + 'upload_files',
                remove_url: glob.base_url + '/catalog/' + 'remove_files',

                fm_mode: 'images',
                fm_item: null,
                fm_prop: null,
                fm_update_mat: false,
            },
            computed:{

            },
            created: function(){

            },
            mounted(){


            },
            methods: {

            }
        });
    }


    document.addEventListener('Glob_ready', async function () {
        init_vue();
    })


</script>
