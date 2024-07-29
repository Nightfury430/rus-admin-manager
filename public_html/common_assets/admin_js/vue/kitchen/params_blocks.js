
let c_data;
let i_data;

let types = {
    'number':{
        name: 'Число',
        type: 'number',
        value: 0,
        params: {
            min: 1,
            max: '',
            step: 1,
        }
    },
    'select':{
        name: 'Выбор из списка',
        type: 'select',
        value: 0,
        params: {
            price_mode: 'add',
            price_target: 'single',
            options: [
                {
                    name: '',
                    value: '',
                    price: ''
                }
            ]
        }
    },
    'boolean':{
        name: 'Да/Нет',
        type: 'boolean',
        value: 1,
        params: {
            price_mode: 'add',
            price_target: 'single',
            price: 0
        }
    }
}

document.addEventListener('Glob_ready', async function () {
    if(glob.item_id != 0){
        i_data = await promise_request(glob.base_url + '/catalog/'+ glob.i_method_name +'/' + glob.controller_name + '/' + glob.item_id);
        i_data['data'] = JSON.parse(i_data['data']);
    }

    init_vue();
})


function init_vue() {
    app = new Vue({
        el: '#sub_form',
        components: {
        },
        data: {
            item: {
                id: 0,
                active: 1,
                name: '',
                data: [],
                description:'',
                order: 10000
            },
        },
        computed: {
            types: function () {
                let res = [];
                Object.keys(types).forEach((k)=>{
                    res.push({
                        name: types[k].name,
                        key: k
                    })
                })
                return res;
            }
        },
        mounted: function () {
        },
        beforeCreate: function(){
        },
        created: function () {
            this.$options.types = types;

            if(glob.item_id != 0){
                Vue.set(this, 'item', i_data)
            }

        },
        beforeMount: async function () {

        },
        methods: {
            opt_name_input: function(opt){
                opt.value = opt.name;
            },
            change_type: function(index){
                let item = copy_object(types[this.item.data[index].type].params);
                item.name = this.item.data[index].name;
                Vue.set(this.item.data[index], 'params', item)
            },
            add_option: function(index){
                this.item.data[index].params.options.push(copy_object(types['select'].params.options[0]))
            },
            add_item: function(){
                let item = copy_object(types['select'])
                item.name = '';
                this.item.data.push(item)
            },
            remove_param: function(index){
                this.item.data.splice(index, 1)
            },
            submit: async function (e) {
                e.preventDefault();

                let fdata = new FormData();


                let data = copy_object(this.item);

                fdata.append('data', JSON.stringify(data));

                let res = await promise_request_post(this.$refs.form.action, fdata)
                location.href = this.$refs.success_url.value;

                return false;
            }
        }
    });
}




