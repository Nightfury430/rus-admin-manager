<form id="sub_form" @submit.prevent="check_form" enctype="multipart/form-data" method="post" accept-charset="UTF-8" action="<?php echo site_url('templates/edit_ajax/'.$item['id']) ?>">

<div class="wrapper wrapper-content  animated fadeInRight">
    <div class="row">
        <div class="col-lg-12">
            <div class="ibox ">
                <div class="ibox-title">
                    <h5><?php echo $lang_arr['basic_params']?></h5>
                </div>
                <div class="ibox-content">
                    <div class="alert alert-info" role="alert">
                        <p>Требования к иконке</p>
                        <p>Рекомендуемая ширина иконки 280px</p>
                        <ul>
                            <li>Размер файла не более 100Кб</li>
                            <li>Ширина изображения не более 300px</li>
                            <li>Высота изображения не более 300px</li>
                        </ul>
                    </div>

                    <div v-if="errors.length" v-cloak id="errors" class="alert alert-danger error_msg"  >
                        <ul>
                            <li v-for="error in errors">
                                {{ error }}
                            </li>
                        </ul>
                    </div>

                    <div class="form-group">
                        <label for="name"><?php echo $lang_arr['name']?>*</label>
                        <input v-model="name" value="<?php echo $item['name']?>" type="text" class="form-control" name="name" placeholder="<?php echo $lang_arr['name']?>">
                    </div>

                    <div class="form-group">
                        <p><b><?php echo $lang_arr['current_icon']?></b></p>
                        <img style="max-width: 300px; height: auto" src="<?php echo $this->config->item('const_path') . $item['icon']?>?<?php echo md5(date('m-d-Y-His A e'));?>">
                    </div>

                    <div class="form-group">
                        <label for="icon"><?php echo $lang_arr['change_icon']?></label>
                        <input v-model="icon" ref="icon" type="file" name="icon" id="icon" class="form-control" accept="image/jpeg,image/png,image/gif">
                        <div v-if="icon" v-cloak class="alert alert-warning"><?php echo $lang_arr['template_icon_warning']?> </div>
                    </div>

                    <div class="form-group">
                        <label for="project"><?php echo $lang_arr['change_project_file']?></label>
                        <input v-model="project" ref="project" type="file" name="project" id="project" class="form-control" accept=".dbs, .json">
                        <div v-if="project" v-cloak class="alert alert-warning"><?php echo $lang_arr['change_project_warning']?> </div>
                    </div>

                    <div class="form-group">
                        <label for="active"><?php echo $lang_arr['active']?></label>
                        <select v-model="active" class="form-control" name="active" id="active">
                            <option <?php if($item['active']==1) echo 'selected'?> value="1"><?php echo $lang_arr['yes']?></option>
                            <option <?php if($item['active']==0) echo 'selected'?> value="0"><?php echo $lang_arr['no']?></option>
                        </select>
                    </div>

                    <div class="hr-line-dashed"></div>

                    <div class="form-group row">
                        <div class="col-sm-4 col-sm-offset-2">
                            <a class="btn btn-white btn-sm" href="<?php echo site_url('templates/index/') ?>"><?php echo $lang_arr['cancel']?></a>
                            <button class="btn btn-primary btn-sm" type="submit"><?php echo $lang_arr['save']?></button>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
</div>

</form>




<script src="/common_assets/libs/vue.min.js"></script>
<script src="https://unpkg.com/axios/dist/axios.min.js"></script>

<script>
    $('#document').ready(function () {

        const app = new Vue({
            el: '#sub_form',
            data: {
                success: "<?php echo site_url('templates/index/') ?>",
                errors: [],
                name: "<?php echo $item['name']?>",
                icon: null,
                active: "<?php echo $item['active']?>",
                project: null
            },
            methods: {
                check_form: function (e) {

                    e.preventDefault();

                    let scope = this;

                    this.errors = [];

                    if (!this.name) {
                        this.errors.push('<?php echo $lang_arr['project_name_error']?>');
                    }


                    if (this.icon) {
                        if (this.$refs.icon.files[0].size > 100 * 1024) this.errors.push('<?php echo $lang_arr['too_big_icon']?>');
                    }

                    if (this.project) {
                        if(get_file_extension(this.project) !== 'dbs') this.errors.push('<?php echo $lang_arr['wrong_file_extension']?>');
                    }

                    if (!this.errors.length) {
                        let formData = new FormData();
                        if (this.icon) {
                            formData.append('icon', this.$refs.icon.files[0]);
                        }
                        if (this.project) {
                            formData.append('project', this.$refs.project.files[0]);
                        }


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

    })

    function get_file_extension(fname) {
        return fname.slice((Math.max(0, fname.lastIndexOf(".")) || Infinity) + 1);
    }


</script>