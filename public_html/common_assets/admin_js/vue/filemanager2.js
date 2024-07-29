let tree_item = `
    <li style="list-style: none">
        <div>
          <span @click="toggle" v-if="item.children.length">
            <i style="margin-left: -13px; cursor:pointer;" v-if="is_folder" class="fa" :class="{'fa-chevron-right': !item.is_open, 'fa-chevron-down': item.is_open}"></i>
          </span>
          <span style="cursor: pointer" @click="select_folder_t" @dblclick="toggle">
              <i class="fa" :class="{'fa-folder-o': !is_current, 'fa-folder-open-o': is_current}"></i>
              {{ item.name }}
          </span>
        </div>
        <ul style="padding-left: 15px" v-show="item.is_open" v-if="is_folder">
          <tree-item
            class="item"
            v-for="(child, index) in item.children"
            :key="index"
            :item="child"
            :current="current"
            @select_folder="select_folder"
            @toggle="$emit('toggle', $event)"
            @add_item="$emit('add_item', $event)"
          ></tree-item>
         
        </ul>
      </li>
`

Vue.component("tree-item", {
    template: tree_item,
    props: ['item', 'current'],
    // props:{
    //     item: Object,
    //     current: Number
    // },
    data: function() {
        return {
            is_open: false
        };
    },
    computed: {
        is_folder: function() {
            // return this.item.children && this.item.children.length;
            return this.item.type =='folder' && this.item.children.length;
        },
        is_current: function () {
            return this.item.id == this.current
        }
    },
    methods: {
        toggle: function() {


            if(!this.item.is_open) Vue.set(this.item, 'is_open', false)
            if (this.is_folder) {
                this.is_open = !this.is_open;
                this.item.is_open = !this.item.is_open;
                this.$emit("toggle", this.item);
            }
        },
        select_folder_t: function(){
            this.select_folder(this.item)
        },
        select_folder: function(item){
            this.$emit("select_folder", item);
            this.is_open = true;
            Vue.set(this.item, 'is_open', true)
        },
        make_folder: function() {
            if (!this.is_folder) {
                this.$emit("make_folder", this.item);
                this.is_open = true;
            }
        }
    }
});


let filemanager_template = `
<div style="position: relative">
    <div class="row form-group">
        <div class="col-4">
            <select v-if="type != 'common'" @change="change_type($event.target.value)" class="form-control form-control-xs">
                <option selected value="files">Модели</option>
                <option value="view_files">Ранее загруженные модели (только выбор)</option>
                <option value="view_images">Ранее загруженные текстуры и иконки (только выбор)</option>
            </select>
        </div>
        <div class="col-8">
            <ol v-if="sel_item" class="breadcrumb">
                        <li @click="select_folder(tree)" class="breadcrumb-item">
                            <a  href="#"><i class="fa fa-home"></i></a>
                        </li>
                        
                        <li v-for="(it, index) in bread" class="breadcrumb-item" :class="{active: index == bread.length-1}">
                            <a @click="select_folder_bread(it, index)">{{it}}</a>
                        </li>
                       
            </ol>
        </div>
    </div>
    <div class="row form-group">
        <div class="col-4" style="height: 45vh; overflow-y: scroll; overflow-x: auto">
            <ul>
                <tree-item
                        class="item"
                        :item="tree"
                        :current="current_item"
                        @toggle="toggle"
                        @make_folder="make_folder"
                        @select_folder="select_folder"
                        @add_item="add_item"
                ></tree-item>
            </ul>
            
        </div>
        <div class="col-8" style="height: 45vh; overflow-y: scroll; overflow-x: auto">
        
            <div class="mb-2" v-if="tree.can_upload == true">
                <button class="btn btn-xs btn-outline btn-success"  @click="modal_show('add')" type="button">
                <i class="fa fa-folder-o"></i>
                создать папку
                </button>
                <button class="btn btn-xs btn-outline btn-success" @click="$refs.fmgr_upload.click()" type="button">
                <i class="fa fa-file-o"></i>
                загрузить файлы
                </button> 
            </div>
        
            <table class="table table-hover">
                <thead v-if="sel_item.id !=0">
                <tr>
                    <th @click="step_up" style="cursor: pointer"><i style="margin-right: 5px" class="fa fa-arrow-up"></i>...</th>
                    <th style="width: 10px"></th>
                    <th style="width: 10px"></th>
                </tr>
                </thead>
                <tbody>
                <tr v-if="sel_item.children" v-for="folder in sel_item.children">
                   <td style="cursor: pointer" @click="select_folder(folder)"><i style="margin-right: 5px" class="fa fa-folder-o"></i>{{folder.name}}</td>
                   <td style="width: 10px"><a v-show="tree.can_upload == true" @click="modal_show('rename', folder)" style="float: right; margin-right: 10px"><i class="fa fa-edit"></i></a></td>
                   <td style="width: 10px"><a v-show="tree.can_upload == true" @click="remove_file(folder, 1)" style="float: right"><i class="fa fa-trash"></i></a></td>
                   
                </tr>
                <tr v-if="sel_item.files" v-show="$options.modes[data_mode].includes(file.ext)" v-for="file in sel_item.files">
                   <td style="cursor: pointer" @click="select_file(file)">
                       <i style="margin-right: 5px" class="fa fa-file-o"></i>
                       {{file.name}}
                   </td>
                   <td style="width: 10px"><a v-show="tree.can_upload == true" @click="modal_show('rename', file)" style="float: right; margin-right: 10px"><i class="fa fa-edit"></i></a></td>
                   <td style="width: 10px"><a v-show="tree.can_upload == true" @click="remove_file(file)" style="float: right"><i class="fa fa-trash"></i></a></td>
                </tr>
                </tbody>
            </table>
        
           
        </div>
    </div>    
    <div class="hidden">
        <input ref="fmgr_upload" type="file" @change="upload_files($event)" :accept="$options.ext[data_mode]" multiple>    
    </div>
    <div v-if="modals.show == 1" style="position: absolute; left: 0; right: 0; top:0; bottom: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.42);">
        <div style="position:absolute; width: 100%; max-width: 500px; left: 0; right: 0; top: 10px; margin: 0 auto; background: #ffffff">
               <div class="ibox ">
                    <div class="ibox-title">
                        <h5 class="text-center">{{$options.modal_title[modals.mode]}}</h5>
                        <div class="ibox-tools">
                            <a @click="modal_close" class="close-link">
                                <i class="fa fa-times"></i>
                            </a>
                        </div>
                    </div>
                    <div class="ibox-content">
                        <div class="row">
                            <div class="col-sm-12 text-center ">
                                 <div>
                                     <input v-model="modals.name" type="text" placeholder="Введите название" class="form-control">
                                     <div style="height: 30px">
                                        <span v-show="modals.error" class="text-danger m-b-none">{{$options.errors[modals.error]}}</span>
                                     </div>
                                 </div>
                            </div>
                        </div>
                    </div>
                    <div class="text-center">
                        <button @click="modal_close" class="btn btn-white btn-sm" type="button">Отмена</button>
                        <button @click="modal_apply" class="btn btn-primary btn-sm" type="button">Применить</button>
                    </div>
                      
                </div>
        </div>
</div>
    
</div>
`

Vue.component("filemanager", {
    template: filemanager_template,
    props: {
        base_url: {
            default: 'null',
            type: String
        },
        url: {
            default: '/catalog/filemanager_tree',
            type: String
        },
        rename_url: {
            default: '/catalog/filemanager_rename',
            type: String
        },
        create_url: {
            default: '/catalog/filemanager_create_folder',
            type: String
        },
        file_url: {
            default: '/catalog/filemanager_upload',
            type: String
        },
        remove_url: {
            default: '/catalog/filemanager_remove',
            type: String
        },
        type: {
            default: 'files',
            type: String
        },
        mode: {
            default: 'all',
            type: String
        }
    },
    data: function() {
        return {
            tree: {
                name: 'files',
                type: 'folder',
                path: '/',
                is_open: true,
                got_data: true,
                can_upload: true,
                id: 0,
                children: [

                ],
                files: [],
            },
            sel_item: {
                name: 'files'
            },
            current_item: -1,
            bread: [],
            modals: {
                show: 0,
                mode:'add',
                item: null,
                name: '',
                error: null
            },

            data_base_url: null,
            data_mode: null

        };
    },
    created: async function(){

        this.$options.modal_title = {
            add: 'Создать каталог',
            rename: 'Переименовать'
        }

        this.$options.errors = {
            'is_blank': 'Название не может быть пустым',
            'is_invalid': 'Название может содержать только латинские символы и цифры, а также символы "-" и "_"',
            'is_exists': 'Каталог с таким названием уже существует',
            'error_max_size': 'Файл больше 50Мб',
            'wrong_ext': 'Недопустимое расширение'
        }

        this.$options.url = glob.base_url + '/catalog/filemanager_tree'

        if(this.base_url === 'null'){
            this.data_base_url = document.getElementById('ajax_base_url').value
        } else {
            this.data_base_url = this.base_url
        }

        if(this.type == 'common') this.tree.name = 'assets'

        this.data_mode = this.mode;

        this.$options.modes = {
            'images': JSON.stringify(['jpg', 'jpeg', 'png', 'gif', 'hdr']),
            'models': JSON.stringify(['fbx']),
            'all': JSON.stringify(['jpg', 'jpeg', 'png', 'gif', 'hdr', 'fbx'])
        }

        this.$options.ext = {
            'images': 'image/jpeg,image/png,image/gif, .hdr',
            'models': '.fbx',
            'all': 'image/jpeg,image/png,image/gif,.hdr, .fbx'
        }


        await this.xhr2(this.tree, this.data_base_url + this.url)


        Vue.set(this, 'sel_item', this.tree)

    },

    watch: {
        mode(val) {

        },
    },
    computed: {
        is_folder: function() {
            return this.item.children && this.item.children.length;
        }
    },
    methods: {

        change_type: async function(type){

            this.type = type;

            let obj = {
                name: 'files',
                type: 'folder',
                path: '/',
                is_open: true,
                got_data: true,
                can_upload: true,
                id: 0,
                children: [],
                files: [],
            }

            switch (type) {
                case 'files':
                    Vue.set(this, 'tree', obj)
                    break;
                case 'view_files':
                    obj.name = 'models';
                    obj.can_upload = false;
                    Vue.set(this, 'tree', obj)
                    break;
                case 'view_images':
                    obj.name = 'images';
                    obj.can_upload = false;
                    Vue.set(this, 'tree', obj)
                    break;

            }

            await this.xhr2(this.tree, this.data_base_url + this.url);
            Vue.set(this, 'sel_item', this.tree)
            this.select_folder(this.tree)

        },

        modal_show: function(mode, item){
            this.modals.show = 1;
            this.modals.mode = mode

            if(!item){
                this.modals.name = '';
            } else {
                this.modals.item = item;

                if(item.ext){
                    this.modals.name = copy_object(item.name).replace('.' + item.ext, '');
                } else {
                    this.modals.name = item.name;
                }
            }

        },
        modal_close: function(){
            this.modals.show = 0;
            this.modals.name = '';
            this.modals.item = null;
            this.modals.error = null;
        },
        modal_apply: async function(){

            let result;

            if(this.modals.item){
                result = await this.rename(this.modals.item)
            } else {
                result = await this.create(this.modals.name)
            }

            if(result == ''){
                this.modal_close();
                await this.xhr2(this.sel_item, this.data_base_url + this.url);
            } else {
                this.modals.error = result;
            }

        },
        create: async function(name){
            let js_errors = this.test_string(name)
            if(js_errors) return js_errors

            let form_data = new FormData();
            form_data.append('type', this.type)
            form_data.append('save_dir', this.sel_item.path)
            form_data.append('name', name)

            return await promise_request_post(this.data_base_url + this.create_url, form_data)
        },
        rename: async function(){
            let old_path = this.modals.item.path;
            let new_path = this.modals.name;

            let spl = old_path.split('/');

            let old_name = spl.pop();
            let path = spl.join('/')


            if(old_name == new_path){
                this.modal_close();
                return
            }

            let js_errors = this.test_string(new_path)
            if(js_errors) return js_errors

            let new_name = path + '/' + new_path;

            if(this.modals.item.type == 'file'){
                new_name += '.' + this.modals.item.ext;
            }

            let temp = {
                old_path: old_path,
                new_path: new_name
            }


            let form_data = new FormData();
            form_data.append('type', this.type)
            form_data.append('old_path', old_path)
            form_data.append('new_path', new_name)

            return await promise_request_post(this.data_base_url + this.rename_url, form_data)
        },

        test_string: function(string){

            let str = string.trim();

            if(str == '') return 'is_blank';

            let patt = /^(\w|\.|-)+$/;

            if(!patt.test(str)) return 'is_invalid';

            return false

        },

        test_filename: function(string){


            let split_arr = string.split('.');
            if(split_arr.length > 2) return 'is_invalid';

            let str = split_arr[0].trim();

            if(str == '') return 'is_blank';

            let patt = /^(\w|\.|-)+$/;

            if(!patt.test(str)) return 'is_invalid';

            return false

        },


        xhr2: async function(item, url){
            let form_data = new FormData();


            if(item.id != 0)  form_data.append('current_dir', item.path)

            form_data.append('mode', this.$options.modes[this.data_mode])

            form_data.append('type', this.type)



            let response = await promise_request_post(url, form_data)
            response.folders.sort(function (a,b) {
                return alphanum(a.name, b.name)
            })
            response.files.sort(function (a,b) {
                return alphanum(a.name, b.name)
            })


            Vue.set(item, 'children', response.folders);
            if(!item.files) item.files = [];
            Vue.set(item, 'files', response.files)
            item.got_data = true
        },



        toggle: async function(item){
            await this.xhr2(item, this.data_base_url + this.url);
        },
        select_folder: async function(item){
            this.current_item = item.id
            await this.xhr2(item, this.data_base_url + this.url);
            this.sel_item = item;
            let split = item.path.split('/')
            if(split[0] == ''){
                this.bread = [];
            } else {
                this.bread = split;
            }


        },
        select_folder_bread: async function(name, index){
            let arr = copy_object(this.bread);
            arr.length = index+1

            let curr_item = this.tree;

            for (let i = 0; i < arr.length; i++){
                for (let j = 0; j < curr_item.children.length; j++){
                    if(curr_item.children[j].name == arr[i]){
                        curr_item = curr_item.children[j]
                        break;
                    }
                }

            }

            await this.select_folder(curr_item)
        },
        step_up: async function(){
            let arr = this.sel_item.path.split('/');

            arr.pop()

            if(!arr.length){
                await this.select_folder(this.tree)
                return;
            }

            let curr_item = this.tree;

            for (let i = 0; i < arr.length; i++){
                for (let j = 0; j < curr_item.children.length; j++){
                    if(curr_item.children[j].name == arr[i]){
                        curr_item = curr_item.children[j]
                        break;
                    }
                }
            }

            await this.select_folder(curr_item)
        },



        upload_files: async function(event){
            let files = event.target.files;

            let not_uploaded = [];

            let form_data = new FormData();
            let c = 0;
            for (let i = 0; i < files.length; i++){
                if(!this.test_filename(files[i].name)){
                    c++
                    form_data.append(i, files[i])
                } else {
                    not_uploaded.push(files[i].name)
                }
            }


            let path = '';
            if(!this.sel_item.path){
                path = this.tree.path
            } else {
                path = this.sel_item.path
            }


            form_data.append('save_dir', path)
            form_data.append('type', this.type)
            if(c > 0){
                let res = await promise_request_post(this.data_base_url + this.file_url, form_data)
                console.log(res)
                if(res.errors.length){
                    let str = '';

                    for (let i = 0; i < res.errors.length; i++){
                        str += this.$options.errors[res.errors[i].error] + ': ' + res.errors[i].name + '<br>'
                    }

                    toastr.error(str);

                }

                if(res.uploaded.length){
                    let str = '';

                    for (let i = 0; i < res.uploaded.length; i++){
                        str += 'Загружен' + ': ' + res.uploaded[i] + '<br>'
                    }

                    toastr.success(str);

                }



                await this.xhr2(this.sel_item, this.data_base_url + this.url);
            }

            if(not_uploaded.length){
                let fnames = not_uploaded.join(',')
                alert('Следующие файлы не были загружены, так как не соотвтетствуют требованиям (' + this.$options.errors['is_invalid'] + '): ' + fnames);
            }



            event.target.value = '';

        },

        remove_file: async function(file, dir){
            let scope = this;
            show_warning_yes_no(async function () {
                let form_data = new FormData();
                form_data.append('path', file.path)
                if(dir) form_data.append('is_dir', 1)
                form_data.append('type', scope.type)

                await promise_request_post(scope.data_base_url + scope.remove_url, form_data)
                await scope.xhr2(scope.sel_item, scope.data_base_url + scope.url);
            })
        },

        // rename: async function(item, is_file){
        //     let scope = this;
        //
        //     if(!is_file) is_file = 0;
        //
        //     let arr = scope.folder_url.split('/');
        //     arr[arr.length - 1] = 'rename_folder';
        //     let url = arr.join('/')
        //
        //     let al = prompt("Введите название", item.name);
        //     if (al === "") {
        //         alert("Название не может быть пустым");
        //     }
        //     if(!check_foldername(al)){
        //         alert('Название может содержать только латинские символы');
        //     }
        //
        //     let new_path = item.path.slice(0, item.path.lastIndexOf('/')) + '/' + al
        //     new_path = new_path.toLowerCase();
        //     al = al.toLowerCase();
        //
        //
        //
        //
        //     let form_data = new FormData();
        //     form_data.append('old_name', item.path)
        //     form_data.append('new_name', new_path)
        //     form_data.append('name', al)
        //     form_data.append('is_file', is_file)
        //
        //     let result = await promise_request_post(url, form_data)
        //     console.log(result)
        //     await scope.xhr(scope.sel_item);
        //
        //
        // },
        make_folder: function(item) {
            Vue.set(item, "children", []);
            this.add_item(item);
        },
        add_item: function(item) {
            item.children.push({
                name: "new stuff"
            });
        },
        select_file: function (file) {

            let sel = copy_object(file)
            console.log(sel)
            if(this.type == 'common'){
                sel.path = '/common_assets/assets/' + sel.path
            }



            switch (this.type) {
                case 'files':
                    sel.true_path = '/files/' + sel.path
                    break;
                case 'view_files':
                    sel.true_path = '/models/' + sel.path
                    break;
                case 'view_images':
                    sel.true_path = '/images/' + sel.path
                    break;
                case 'common':
                    sel.true_path =  sel.path
                    break;
            }

            this.$emit("select_file", sel);
        }
    }
});

function alphanum(a, b) {
    function chunkify(t) {
        var tz = [];
        var x = 0, y = -1, n = 0, i, j;

        while (i = (j = t.charAt(x++)).charCodeAt(0)) {
            var m = (i == 46 || (i >=48 && i <= 57));
            if (m !== n) {
                tz[++y] = "";
                n = m;
            }
            tz[y] += j;
        }
        return tz;
    }

    var aa = chunkify(a);
    var bb = chunkify(b);

    for (x = 0; aa[x] && bb[x]; x++) {
        if (aa[x] !== bb[x]) {
            var c = Number(aa[x]), d = Number(bb[x]);
            if (c == aa[x] && d == bb[x]) {
                return c - d;
            } else return (aa[x] > bb[x]) ? 1 : -1;
        }
    }
    return aa.length - bb.length;
}
