

<div class="row wrapper border-bottom white-bg page-heading">
    <div class="col-lg-10">
        <h2>Конструктор шкафов купе</h2>
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

<div class="wrapper wrapper-content  animated fadeInRight">

    <div class="row">
        <div class="col-lg-12">
            <div class="ibox ">
                <div class="ibox-title">
                    <h5><?php echo $lang_arr['basic_params'] ?></h5>
                </div>
                <div class="ibox-content">

                    <form  ref="sub_form" id="sub_form" @submit="check_form" data-action="<?php echo site_url('settings/coupe_update_account_settings/')?>">


                    <div v-if="errors.length" v-cloak id="errors" class="alert alert-danger error_msg"  >
                        <ul>
                            <li v-for="error in errors">
                                {{ error }}
                            </li>
                        </ul>
                    </div>



                    <div class="form-group  row">
                        <label class="col-sm-2 col-form-label">E-mail для заявок*</label>
                        <div class="col-sm-10">
                            <input v-model="order_mail" data-value="<?php xecho($settings['order_mail'])?>" type="text" class="form-control" name="order_mail" id="order_mail" placeholder="example1@example.ru,example2@.ru">
                        </div>
                    </div>
                    <div class="hr-line-dashed"></div>


                    <div class="form-group  row">
                        <label class="col-sm-2 col-form-label">Сайт установки*</label>
                        <div class="col-sm-10">
                            <input v-model="site_url" data-value="<?php xecho($settings['site_url'])?>" type="text" class="form-control" name="site_url" id="site_url" placeholder="https://www.site.ru">
                        </div>
                    </div>
                    <div class="hr-line-dashed"></div>


                    <div class="form-group  row">
                        <label class="col-sm-2 col-form-label">Телефон, адрес</label>
                        <div class="col-sm-10">
                            <input v-model="address_line" data-value="<?php xecho($settings['address_line'])?>" type="text" class="form-control" name="address_line" id="address_line" placeholder="example1@example.ru, +7 (888) 888-88-88">
<!--                            <span class="form-text m-b-none">A block of help text that breaks onto a new line and may extend beyond one line.</span>-->
                        </div>
                    </div>
                    <div class="hr-line-dashed"></div>


                    <div class="form-group  row">
                        <label class="col-sm-2 col-form-label">Id приложения Вконтакте</label>
                        <div class="col-sm-10">
                            <input v-model="vk_appid" data-value="<?php xecho($settings['vk_appid'])?>" type="text" class="form-control" name="vk_appid" id="vk_appid" placeholder="0000000">
                        </div>
                    </div>
                    <div class="hr-line-dashed"></div>

                        <div class="form-group  row">
                            <label class="col-sm-2 col-form-label">
                                <?php echo $lang_arr['vk_iframe_address']?>
                            </label>
                            <div class="col-sm-10">
                                <textarea id="soc_link" class="form-control"  rows="1"><?php echo $this->config->item('const_path') . 'config/index.php/main_coupe' ?></textarea>
                                <div class="copy btn btn-white" data-clipboard-target="#soc_link"><i class="fa fa-copy"></i> <?php echo $lang_arr['copy'] ?></div>
                            </div>
                        </div>
                        <div class="hr-line-dashed"></div>

                    <div class="form-group  row">
                        <label class="col-sm-2 col-form-label">Логотип</label>
                        <div class="col-sm-10">
                            <div class="row">
                                <div class="col-sm-5">
                                    <input ref="logo" name="logo" id="logo" type="file" accept="image/jpeg,image/png" @change="logo_preview"/>
                                </div>
                                <div class="col-sm-5">
                                    <div>
                                        <img id="logo_preview" style="max-width: 125px; height: auto" data-value="<?php xecho($settings['logo']) ?>" v-if="logo_url" :src="logo_url"/>
                                    </div>

                                    <div style="margin-top: 5px" v-if="logo_url" @click="delete_logo()" class="btn btn-xs btn-danger">Удалить логотип</div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="hr-line-dashed"></div>


                        <div class="form-group  row">
                            <label class="col-sm-2 col-form-label">Код конструктора шкафов-купе</label>
                            <div class="col-sm-10">
                                <textarea id="code_coupe" class="form-control"  rows="5"><div style="height: 100%; width: 100%" id="p_container_coupe"></div><script>document.addEventListener('DOMContentLoaded', function (){var params=decodeURIComponent(window.location.search.substring(1)); var p_container=document.getElementById('p_container_coupe'); var iframe=document.createElement('iframe'); iframe.id='p_coupe'; iframe.allowFullscreen=true; iframe.style.width="100%"; iframe.style.height="100%"; iframe.style.minHeight="700px"; iframe.style.border=0; iframe.src='<?php echo $this->config->item('const_path') . 'config/index.php/main_coupe' ?>'; if (params !=='') iframe.src=iframe.src + "?" + params; p_container.appendChild(iframe);})</script></textarea>
                                <div class="copy btn btn-white" data-clipboard-target="#code_coupe"><i class="fa fa-copy"></i> Копировать</div>
                            </div>
                        </div>
                        <div class="hr-line-dashed"></div>

                        <div class="form-group row">
                            <div class="col-sm-4 col-sm-offset-2">
                                <button class="btn btn-primary btn-sm" type="submit">Сохранить</button>
                            </div>
                        </div>

                    </form>
                </div>
            </div>
        </div>
    </div>

</div>

<script src="/common_assets/libs/vue.min.js"></script>
<script src="/common_assets/admin_js/vue/coupe_account_settings.js?<?php echo md5(date('m-d-Y-His A e'));?>"></script>

<!-- Clipboard -->
<script src="/common_assets/theme/js/plugins/clipboard/clipboard.min.js"></script>
