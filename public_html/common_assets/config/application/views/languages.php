
<script src="/common_assets/libs/exceljs.min.js"></script>
<script src="/common_assets/libs/vue.min.js"></script>
<script src="/common_assets/admin_js/vue/kitchen_languages.js?<?php echo md5(date('m-d-Y-His A e'));?>"></script>
<!--<script src="https://unpkg.com/papaparse@5.1.1/papaparse.min.js"></script>-->


<div class="row wrapper border-bottom white-bg page-heading">
    <div class="col-lg-10">
        <h2><?php echo $lang_arr['languages']?></h2>
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


<form ref="sub_form" id="sub_form" @submit="check_form" data-action="<?php echo site_url('languages/update_data_ajax/custom')?>">



<input type="hidden" id="lang" value="<?php echo $this->config->item('ini')['language']['language']?>">
<input type="hidden" id="lang_data" value="<?php echo site_url('languages/get_data_ajax/custom')?>">
<input type="hidden" id="lang_data_front" value="<?php echo site_url('languages/get_data_ajax_front/custom')?>">



<div class="wrapper wrapper-content  animated fadeInRight">
    <div class="row">
        <div class="col-lg-12">



            <div class="ibox">
                <div class="ibox-content">
                    <div class="checkbox">
                        <label>
                            <input v-model="use_custom_lang" type="checkbox"> <?php echo $lang_arr['use_own_language']?>
                        </label>
                    </div>
                    <div class="tabs-container">
                        <input class="hidden" @change="import_xls()" ref="imp" type="file" accept=".xlsx">
                        <ul class="nav nav-tabs">
                            <li><a class="nav-link active" data-toggle="tab" href="#back"><?php echo $lang_arr['backend_language'] ?></a></li>
                            <li><a class="nav-link" data-toggle="tab" href="#front"><?php echo $lang_arr['frontend_language'] ?></a></li>
                        </ul>
                        <div class="tab-content">
                            <div id="back" class="tab-pane active">
                                <div class="panel-body">
                                    <div class="form-group">
                                        <div class="btn btn-sm btn-primary" @click="export_xls('back')" role="button"><?php echo $lang_arr['export_xls']?></div>
                                        <div class="btn btn-sm btn-primary" @click="$refs.imp.click()"  role="button"><?php echo $lang_arr['import_xls']?></div>
                                    </div>

                                    <table  class="table table-bordered table-hover">
                                        <tr><th class="w-50"><?php echo $lang_arr['original_language']?></th><th class="w-50"><?php echo $lang_arr['translated_language']?></th></tr>
                                        <tr v-for="(name, index) in lang.original">
                                            <td class="w-50">{{name}}</td>
                                            <td class="w-50">
                                                <input v-model="lang.custom[index]" class="form-control" type="text">
                                            </td>
                                        </tr>
                                    </table>
                                </div>
                            </div>
                            <div id="front" class="tab-pane">
                                <div class="panel-body">
                                    <div class="form-group">
                                        <div class="btn btn-sm btn-primary" @click="export_xls('back')" role="button"><?php echo $lang_arr['export_xls']?></div>
                                        <div class="btn btn-sm btn-primary" @click="$refs.imp.click()"  role="button"><?php echo $lang_arr['import_xls']?></div>
                                    </div>
                                    <table  class="table table-bordered table-hover">
                                        <tr><th class="w-50"><?php echo $lang_arr['original_language']?></th><th class="w-50"><?php echo $lang_arr['translated_language']?></th></tr>
                                        <tr v-for="(name, index) in lang.original_front">
                                            <td class="w-50">{{name}}</td>
                                            <td class="w-50">
                                                <input v-model="lang.custom_front[index]" class="form-control" type="text">
                                            </td>
                                        </tr>
                                    </table>
                                </div>
                            </div>

                        </div>
                    </div>




                    <div v-if="show_success_message" v-cloak id="success" class="alert alert-success"  >
                        <p><?php echo $lang_arr['success']?></p>
                    </div>
                </div>
            </div>



            <div class="ibox ">
                <div class="ibox-content">

                    <div class="hr-line-dashed"></div>

                    <div class="form-group row">
                        <div class="col-sm-4 col-sm-offset-2">
                            <button id="submit_button" class="btn btn-primary btn-sm" type="submit"><?php echo $lang_arr['save']?></button>
                        </div>
                    </div>
                </div>
            </div>



        </div>
    </div>

</div>
</form>


