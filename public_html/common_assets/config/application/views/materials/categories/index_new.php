<div class="col-sm-12 col-md-12" id="content" data-url="<?php echo site_url('materials/get_materials_ajax/') ?>" xmlns:v-on="http://www.w3.org/1999/xhtml">

    <ul class="cat_list">
        <li  v-for="cat in categories">
            <span v-on:click="expand_cat(cat.id)">{{cat.name}}</span>
            <div v-if="cat.categories.length">
                <ul v-if="cat_id === cat.id" >
                    <li v-for="subcat in cat.categories">
                        <span v-on:click="expand_subcat(subcat.id)">{{subcat.name}}</span>

                        <table v-if="subcat_id === subcat.id">
                            <tr>
                                <td><?php echo $lang_arr['material']?></td>
                                <td><?php echo $lang_arr['name']?></td>
                                <td><?php echo $lang_arr['code']?></td>
                                <td><?php echo $lang_arr['category']?></td>
                                <td><?php echo $lang_arr['is_visible']?></td>
                                <td><?php echo $lang_arr['actions']?></td>
                            </tr>
                            <tr v-for="item in current_materials">
                                <td>
                                    <div v-if="item.map">
                                        <img src="{{item.map}}">
                                    </div>

                                    <div v-if="!item.map">
                                        <div style="width: 40px; height: 40px;" v-bind:style="{ background: item.color}"></div>
                                    </div>

                                </td>
                                <td>{{item.name}}</td>
                                <td>{{item.code}}</td>
                                <td>{{subcat.name}}</td>
                                <td>{{item.active}}</td>
                                <td>Редактировать Удалить</td>
                            </tr>
                        </table>


                    </li>

                </ul>
            </div>

        </li>
    </ul>




</div>


<script src="https://cdn.jsdelivr.net/npm/vue"></script>
<script src="https://unpkg.com/axios/dist/axios.min.js"></script>
<script>


    function bt(data) {
        let tree = data.filter(function (obj) {
            if (obj.parent === '0') obj.categories = [];
            return obj.parent === '0'
        });

        let th = get_hash(tree);

        for (let i = 0; i < data.length; i++) {

            if (data[i].parent in th) {

                th[data[i].parent].categories.push(data[i])

            }
        }



        return tree;
    }

    function get_hash(data) {
        return data.reduce(function (map, obj) {
            map[obj.id] = obj;
            return map;
        }, {});
    }

    app = new Vue({
        el: '#content',
        data: {
            cat_id: null,
            subcat_id: null,
            categories: bt(<?php echo json_encode( $materials_categories )?>),
            current_materials: null,
            errors: [],
            name: null,
            icon: null,
            active: "1",
            project: null
        },
        methods: {
            expand_cat: function (id) {
                if(this.cat_id === id) {
                    this.cat_id = null;
                } else{
                    this.cat_id = id;
                }

            },
            expand_subcat: function (id) {

                let scope = this;
                this.subcat_id = id;
                console.log(id)
                axios.get(
                    $('#content').attr('data-url') + id,
                ).then(function (response) {

                        console.log(response.data)


                    scope.current_materials = response.data

                        // if (msg.data.error) {
                        //
                        // }
                        //
                        // if (msg.data.success) {
                        //     console.log(success)
                        // }

                    }).catch(function () {
                    alert('Unknown error')
                });

            },
            check_form: function (e) {

                let scope = this;

                this.errors = [];

                if (!this.name) {
                    this.errors.push('<?php echo $lang_arr['project_name_error']?>');
                }
                if (!this.icon) {
                    this.errors.push('<?php echo $lang_arr['project_icon_error']?>');
                } else{
                    if(this.$refs.icon.files[0].size > 100 * 1024) this.errors.push('<?php echo $lang_arr['too_big_icon']?>');
                }

                if (!this.project) {
                    this.errors.push('<?php echo $lang_arr['no_project_file']?>');
                } else {
                    if(get_file_extension(this.project) !== 'dbs') this.errors.push('<?php echo $lang_arr['wrong_file_extension']?>');
                }

                if (!this.errors.length) {
                    let formData = new FormData();
                    formData.append('icon', this.$refs.icon.files[0]);
                    formData.append('project', this.$refs.project.files[0]);

                    formData.append('name', this.name);
                    formData.append('active', this.active);

                    axios.post(this.$el.action,
                        formData,
                        {
                            headers: {
                                'Content-Type': 'multipart/form-data'
                            }
                        }
                    ).then(function (msg) {

                        if(msg.data.error) {
                            for (let i = 0; i < msg.data.error.length; i++){
                                scope.errors.push(msg.data.error[i])
                            }
                        }

                        if(msg.data.success){
                            window.location.href = scope.success;
                        }

                    }).catch(function () {
                        alert('Unknown error')
                    });
                }

                e.preventDefault();
                return false;

            }
        }
    })



</script>









