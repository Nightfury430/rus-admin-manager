
let global_options = {};
let c_data;
let i_data;

document.addEventListener('Glob_ready', async function () {

    // i_data = await promise_request(glob.base_url + '/catalog/'+ glob.i_method_name +'/' + glob.controller_name);
    // console.log(i_data)

    init_vue();
})


function init_vue() {



    app = new Vue({
        el: '#sub_form',
        data: {
            item: {
                id: 0,
                name: '',
                icon: '',
                src: '',
                params: '',
                active: 1,
                order: 10000
            },
            file_target: ''
        },
        computed: {

        },
        mounted: function () {

        },
        beforeCreate: function(){

        },
        created: async function () {
            if(glob.item_id && glob.item_id != 0){
                let item =  await promise_request(glob.base_url + '/catalog/'+ glob.i_method_name +'/' + glob.controller_name + '/' + glob.item_id);
                item.params = JSON.parse(item.params);
                console.log(item)

                Vue.set(this, 'item', item)
            }
        },
        beforeMount: async function () {

        },
        methods: {
            check_image: function(key){
                return this.item[key] != '' && this.item[key] != null;
            },
            remove_image: function(key){
                this.item[key] = '';
            },
            sel_file: function (file) {
                this.item[this.file_target] = file.true_path.substr(1);
                $('#material_filemanager').modal('hide');
            },
            correct_url: function (path) {
                if (path == '' || path == null) return '/common_assets/images/placeholders/256x256.png'
                let date_time = new Date().getTime();
                if (path.indexOf('common_assets') > -1) {
                    return path + '?' + date_time
                } else {
                    return glob.acc_url + path + '?' + date_time;
                }
            },

            submit: async function (e) {
                e.preventDefault();

                let data = copy_object(this.item);

                if(data.id == 0) delete data.id;

                let formData = new FormData();
                formData.append('data', JSON.stringify(data));

                let url = this.$refs.form.action;
                console.log(url);

                let res = await promise_request_post_raw(url, formData)

                location.href = this.$refs.success_url.value



            }
        }
    });
}


function power_of_2(n) {
    if (typeof n !== 'number')
        return 'Not a number';

    return n && (n & (n - 1)) === 0;
}


function to_int(val) {
    let result = 0;
    if (typeof val === "boolean") {
        if (val === false) {
            result = 0;
        } else {
            result = 1;
        }
    }

    if (typeof val === "string") {
        if (val.toLowerCase() === "false" || val.toLowerCase() === "0" || val.toLowerCase() === "") {
            result = 0;
        } else {
            result = 1;
        }
    }

    if (typeof val === "number") {
        if (val === 0) {
            result = 0;
        } else {
            result = 1;
        }
    }

    return result;
}
