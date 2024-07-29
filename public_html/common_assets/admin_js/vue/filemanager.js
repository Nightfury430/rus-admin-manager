let tree_item = `
    <li style="list-style: none">
        <div>
          <span @click="toggle" v-if="item.children.length">
            <i style="margin-left: -13px;" v-if="is_folder" class="fa" :class="{'fa-chevron-right': !item.is_open, 'fa-chevron-down': item.is_open}"></i>
          </span>
          <span @click="select_folder" @dblclick="toggle">
              <i class="fa fa-folder-o"></i>
              {{ item.name }}
          </span>
        </div>
        <ul style="padding-left: 15px" v-show="item.is_open" v-if="is_folder">
          <tree-item
            class="item"
            v-for="(child, index) in item.children"
            :key="index"
            :item="child"
            @toggle="$emit('toggle', $event)"
            @select_folder="$emit('select_folder', $event)"
            @add_item="$emit('add_item', $event)"
          ></tree-item>
         
        </ul>
      </li>
`

Vue.component("tree-item", {
    template: tree_item,
    props: {
        item: Object
    },
    data: function() {
        return {
            is_open: false
        };
    },
    computed: {
        is_folder: function() {
            // return this.item.children && this.item.children.length;
            return this.item.type =='folder' && this.item.children.length;
        }
    },
    methods: {
        toggle: function() {

            console.log('toggle')

            if(!this.item.is_open) Vue.set(this.item, 'is_open', false)
            if (this.is_folder) {
                this.is_open = !this.is_open;
                this.item.is_open = !this.item.is_open;
                this.$emit("toggle", this.item);
            }
        },
        select_folder: function(){

            this.$emit("select_folder", this.item);
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
<div>
    <div class="row form-group">
        <div class="col-4" style="height: 45vh; overflow-y: scroll; overflow-x: auto">
    
            <ul>
                <tree-item
                        class="item"
                        :item="tree"
                        @toggle="toggle"
                        @make_folder="make_folder"
                        @select_folder="select_folder"
                        @add_item="add_item"
                ></tree-item>
            </ul>
            
        </div>
        <div class="col-8" style="height: 45vh; overflow-y: scroll; overflow-x: auto">
        
            <div>
                <button data-toggle="model" data-target="#fmgr_create_cat" @click="create_folder" type="button">создать папку</button>
                <button  @click="$refs.fmgr_upload.click()" type="button">загрузить файлы</button> 
            </div>
        
            <div v-if="sel_item.children" v-for="folder in sel_item.children">
                <a @click="select_folder(folder)"><i class="fa fa-folder-o"></i> {{folder.name}}</a>
                <a @click="remove_file(folder, 1)" style="float: right"><i class="fa fa-trash"></i></a>
                <a @click="rename(folder)" style="float: right; margin-right: 10px"><i class="fa fa-edit"></i></a>
            </div>
            <div v-if="sel_item.files" v-show="$options.modes[mode].includes(file.ext)" v-for="file in sel_item.files">
                <a @click="select_file(file)" ><i class="fa fa-file-o"></i>{{file.name}}</a> 
                <a @click="remove_file(file)" style="float: right"><i class="fa fa-trash"></i></a>
                <a @click="rename(file, 1)" style="float: right; margin-right: 10px"><i class="fa fa-edit"></i></a>
            </div>
        </div>
    </div>    
    <div class="hidden">
        <input ref="fmgr_upload" type="file" @change="upload_files($event)" :accept="$options.ext[mode]" multiple>    
    </div>
   
    
</div>
`

Vue.component("filemanager", {
    template: filemanager_template,
    props: {
        url: String,
        folder_url: String,
        file_url: String,
        remove_url: String,
        mode: String
    },
    created: async function(){

        this.$options.modes = {
            'images': JSON.stringify(['jpg', 'jpeg', 'png', 'gif']),
            'models': JSON.stringify(['fbx']),
            'all': [],
        }

        this.$options.ext = {
            'images': 'image/jpeg,image/png,image/gif',
            'models': '.fbx',
        }

        let form_data = new FormData();
        form_data.append('mode', this.$options.modes[this.mode])

        let data = await promise_request_post(this.url, form_data)

        console.log(copy_object(data))
        data.categories.sort(function (a,b) {
            return alphanum(a.name, b.name)
        })
        data.files.sort(function (a,b) {
            return alphanum(a.name, b.name)
        })

        for (let i = 0; i < data.categories.length; i++){
            if(!data.categories[i].children) data.categories[i].children = [];
            if(!data.categories[i].files) data.categories[i].files = [];
            this.tree.children.push(data.categories[i])
        }

        for (let i = 0; i < data.files.length; i++){
            this.tree.files.push(data.files[i])
        }

        Vue.set(this, 'sel_item', this.tree)

    },
    data: function() {
        return {
            tree: {
                name: 'root',
                type: 'folder',
                path: '/common_assets/models',
                is_open: true,
                got_data: true,
                children: [

                ],
                files: [],
            },
            sel_item: {
                name: 'root'
            },
        };
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

        xhr: async function(item){
            let form_data = new FormData();
            if(item.name != 'root')  form_data.append('save_dir', item.path)
            form_data.append('mode', this.$options.modes[this.mode])
            let data = await promise_request_post(this.url, form_data)
            // item.children = data.categories
            data.categories.sort(function (a,b) {
                return alphanum(a.name, b.name)
            })
            data.files.sort(function (a,b) {
                return alphanum(a.name, b.name)
            })
            Vue.set(item, 'children', data.categories);

            if(!item.files) item.files = [];
            // for (let i = 0; i < data.files.length; i++){
            //     item.files.push(data.files[i])
            // }
            Vue.set(item, 'files', data.files)
            item.got_data = true
        },

        toggle: async function(item){
            await this.xhr(item);
        },
        select_folder: async function(item){
            console.log('select_folder')
            await this.xhr(item);

            console.log(copy_object(item))
            this.sel_item = item;
        },

        create_folder: async function(){
            let scope = this;
            swal({
                title: "Введите название",
                type: "input",
                showCancelButton: true,
                closeOnConfirm: true,
                cancelButtonText: glob.lang['no'],
                confirmButtonText: glob.lang['yes'],
            }, async function(inputValue) {
                if (inputValue === false) return false;
                if (inputValue === "") {
                    swal.showInputError("Название не может быть пустым");
                    return false
                }

                if(!check_foldername(inputValue)){
                    swal.showInputError('Название может содержать только латинские символы');
                    return false
                }

                let form_data = new FormData();
                form_data.append('save_dir', scope.sel_item.path)
                form_data.append('name', inputValue)

                let result = await promise_request_post(scope.folder_url, form_data)
                console.log(result)
                await scope.xhr(scope.sel_item);

                console.log(inputValue)

                // swal("Nice!", "You wrote: " + inputValue, "success");
            });

            console.log(this.sel_item)
        },
        upload_files: async function(event){
            let files = event.target.files;

            let not_uploaded = [];

            let form_data = new FormData();
            let c = 0;
            for (let i = 0; i < files.length; i++){
                if(check_filename(files[i])){
                    c++
                    form_data.append(i, files[i])
                }
            }

            let path = '';
            if(!this.sel_item.path){
                path = this.tree.path
            } else {
                path = this.sel_item.path
            }

            form_data.append('save_dir', path)

            if(c > 0){
                let result = await promise_request_post(this.file_url, form_data)
            }
            await this.xhr(this.sel_item);

            event.target.value = '';

        },
        remove_file: async function(file, dir){
            let scope = this;
            show_warning_yes_no(async function () {
                let form_data = new FormData();
                form_data.append('path', file.path)
                if(dir) form_data.append('is_dir', 1)

                await promise_request_post(scope.remove_url, form_data)
                await scope.xhr(scope.sel_item);
            })
        },
        rename: async function(item, is_file){
            let scope = this;

            if(!is_file) is_file = 0;

            let arr = scope.folder_url.split('/');
            arr[arr.length - 1] = 'rename_folder';
            let url = arr.join('/')

            let al = prompt("Введите название", item.name);
            if (al === "") {
                alert("Название не может быть пустым");
            }
            if(!check_foldername(al)){
                alert('Название может содержать только латинские символы');
            }

            let new_path = item.path.slice(0, item.path.lastIndexOf('/')) + '/' + al
            new_path = new_path.toLowerCase();
            al = al.toLowerCase();




            let form_data = new FormData();
            form_data.append('old_name', item.path)
            form_data.append('new_name', new_path)
            form_data.append('name', al)
            form_data.append('is_file', is_file)

            let result = await promise_request_post(url, form_data)
            console.log(result)
            await scope.xhr(scope.sel_item);


        },
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
            this.$emit("select_file", file);
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
