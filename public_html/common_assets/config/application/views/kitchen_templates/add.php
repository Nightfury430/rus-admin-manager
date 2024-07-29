<div class="row wrapper border-bottom white-bg page-heading">
    <div class="col-lg-10">
        <h2><?php echo $lang_arr['add_project']?></h2>
        <!--        <ol class="breadcrumb">-->
        <!--            <li class="breadcrumb-item">-->
        <!--                <a href="index.html">Home</a>-->
        <!--            </li>-->
        <!--            <li class="breadcrumb-item">-->
        <!--                <a>Tables</a>-->
        <!--            </li>-->
        <!--            <li class="breadcrumb-item active">-->
        <!--                <strong>Code Editor</strong>-->
        <!--            </li>-->
        <!--        </ol>-->
    </div>
    <div class="col-lg-2">

    </div>
</div>

<form id="sub_form" @submit="check_form" enctype="multipart/form-data" method="post" accept-charset="UTF-8" action="<?php echo site_url('templates/add_ajax/') ?>">

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
                        <input v-model="name" type="text" class="form-control" name="name" placeholder="<?php echo $lang_arr['name']?>">
                    </div>

                    <div class="form-group">
                        <label for="icon"><?php echo $lang_arr['icon']?>*</label>
                        <input v-model="icon" ref="icon" type="file" name="icon" id="icon" accept="image/jpeg,image/png,image/gif">
                    </div>

                    <div class="form-group">
                        <label for="icon"><?php echo $lang_arr['project_file']?>*</label>
                        <input v-model="project" ref="project" type="file" name="project" id="project" accept=".dbs, .json">
                    </div>

                    <div class="form-group">
                        <label for="active"><?php echo $lang_arr['active']?></label>
                        <select v-model="active" class="form-control" name="active" id="active">
                            <option selected value="1"><?php echo $lang_arr['yes']?></option>
                            <option value="0"><?php echo $lang_arr['no']?></option>
                        </select>
                    </div>

                    <div class="hr-line-dashed"></div>

                    <div class="form-group row">
                        <div class="col-sm-4 col-sm-offset-2">
                            <a class="btn btn-white btn-sm" href="<?php echo site_url('templates/index/') ?>"><?php echo $lang_arr['cancel']?></a>
                            <button class="btn btn-primary btn-sm" type="submit"><?php echo $lang_arr['add']?></button>
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
                name: null,
                icon: null,
                active: "1",
                project: null
            },
            methods: {
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

    })

    function get_file_extension(fname) {
        return fname.slice((Math.max(0, fname.lastIndexOf(".")) || Infinity) + 1);
    }


</script>