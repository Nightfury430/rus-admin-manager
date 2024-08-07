<div class="wrapper wrapper-content  animated fadeInRight">
    <div class="row">
        <div class="col-lg-12">
            <div class="card ">
                <div class="card-header">
                    <h5><?php echo $lang_arr['basic_params'] ?></h5>
                </div>
                <div class="card-body">
                    <form ref="sub_form" id="sub_form" @submit="check_form" data-action="<?php echo site_url('settings/update_settings/')?>">
                        <div v-if="options_ready">
                        <div class="form-group  row">
                            <label class="col-sm-2 col-form-label">
	                            <?php echo $lang_arr['order_email'] ?>
                            </label>
                            <div class="col-sm-10">
                                <input v-model="settings.order_mail" type="text" class="form-control" placeholder="example1@example.ru,example2@.ru">
                                <span class="form-text m-b-none">Адреса email, на которые будут приходить заявки. Можно указать несколько email через запятую</span>
                            </div>
                        </div>
                        <div class="hr-line-dashed"></div>
                        <div class="form-group row">
                            <label class="col-sm-2 col-form-label"><?php echo $lang_arr['site_url'] ?></label>
                            <div class="col-sm-10">
                                <input v-model="settings.site_url" type="text" class="form-control" placeholder="https://www.site.ru">
                                <span class="form-text m-b-none">Адрес сайта, на котором установлен конструктор</span>
                            </div>
                        </div>
                        <div class="hr-line-dashed"></div>
                        <div class="form-group  row">
                            <label class="col-sm-2 col-form-label"><?php echo $lang_arr['address_line'] ?></label>
                            <div class="col-sm-10">
                                <input v-model="settings.address_line" type="text" class="form-control" placeholder="example1@example.ru, +7 (888) 888-88-88">
                            </div>
                        </div>
                        <div class="hr-line-dashed"></div>
                        <div class="form-group row">
                            <label class="col-sm-2 col-form-label"><?php echo $lang_arr['logo'] ?></label>
                            <div class="col-sm-10">
                                <pp_image :t_width="180" :t_height="180" :p_width="180" :p_height="180" @e_update="settings.logo=$event" :src="settings.logo"></pp_image>
<!--                                <div class="icon_block">-->
<!--                                    <img @click="$refs.fileman.data_mode = 'images'" data-toggle="modal" data-target="#filemanager" style="max-width: 125px" :src="correct_url(settings.logo)" alt="">-->
<!--                                    <i @click="$refs.fileman.data_mode = 'images'" data-toggle="modal" data-target="#filemanager" class="fa fa-folder-open open_file" aria-hidden="true"></i>-->
<!--                                    <i v-if="settings.logo != ''" @click="settings.logo = ''" class="fa fa-trash delete_file" aria-hidden="true"></i>-->
<!--                                </div>-->
                            </div>
                        </div>
                        <div v-if="can_logo_top" class="form-group row">
                            <label class="col-sm-2 col-form-label"><?php echo $lang_arr['logo_top'] ?></label>
                            <div class="col-sm-10">
                                <pp_image :t_width="100" :t_height="25" :p_width="100" :p_height="25" @e_update="settings.logo_top=$event" :src="settings.logo_top"></pp_image>
                            </div>
                        </div>
                        <div class="hr-line-dashed"></div>

<!--                        <div class="form-group  row">-->
<!--                            <label class="col-sm-2 col-form-label">-->
<!--                                --><?php //echo $lang_arr['interface_version'] ?>
<!---->
<!--                            </label>-->
<!--                            <div class="col-sm-10">-->
<!--                                <select v-model="settings.interface" class="form-control">-->
<!--                                    <option value="1">--><?php //echo $lang_arr['interface_version_old'] ?><!--</option>-->
<!--                                    <option value="2">--><?php //echo $lang_arr['interface_version_new'] ?><!--</option>-->
<!--                                </select>-->
<!--                            </div>-->
<!--                            <div class="col-sm-12">-->
<!--                                <a target="_blank" href="--><?php //echo $this->config->item('const_path') . 'index_new.php' ?><!--">Предпросмотр конструктора в новом интерфейсе</a>-->
<!--                            </div>-->
<!--                        </div>-->
<!--                        <div class="hr-line-dashed"></div>-->

                        <div class="form-group  row">
                            <label class="col-sm-2 col-form-label">
                                <?php echo $lang_arr['kitchen_constructor_code']?>
                            </label>
                            <div class="col-sm-10">
                                <textarea id="code" class="form-control"  rows="5"><div style="height: 100%; width: 100%" id="planplace_container"></div><script>document.addEventListener('DOMContentLoaded', function (){var params=decodeURIComponent(window.location.search.substring(1)); var planplace_container=document.getElementById('planplace_container'); var iframe=document.createElement('iframe'); iframe.id='planplace'; iframe.allowFullscreen=true; iframe.style.width="100%"; iframe.style.height="100%"; iframe.style.minHeight="700px"; iframe.style.border=0; iframe.src='<?php echo $this->config->item('const_path') ?>'; if (params !=='') iframe.src=iframe.src + "?" + params; planplace_container.appendChild(iframe);})</script>                                </textarea>
                                <div class="copy btn btn-white" data-clipboard-target="#code"><i class="fa fa-copy"></i> <?php echo $lang_arr['copy'] ?></div>
                            </div>
                        </div>
                        <div class="hr-line-dashed"></div>
                            <div v-if="can_vk">
                                <div class="form-group  row">
                                    <label class="col-sm-2 col-form-label">Id приложения Вконтакте</label>
                                    <div class="col-sm-10">
                                        <input v-model="settings.vk_appid" type="text" class="form-control" placeholder="0000000">
                                    </div>
                                </div>
                                <div class="form-group  row">
                                    <label class="col-sm-2 col-form-label">
                                        <?php echo $lang_arr['vk_iframe_address']?>
                                    </label>
                                    <div class="col-sm-10">
                                        <textarea id="soc_link" class="form-control"  rows="1"><?php echo $this->config->item('const_path') ?></textarea>
                                        <div class="copy btn btn-white" data-clipboard-target="#soc_link"><i class="fa fa-copy"></i> <?php echo $lang_arr['copy'] ?></div>
                                    </div>
                                </div>
                                <div class="hr-line-dashed"></div>
                            </div>
                        </div>
                        <div class="form-group row">
                            <div class="col-sm-4 col-sm-offset-2">
                                <button class="btn btn-primary btn-sm" type="submit"><?php echo $lang_arr['save']?></button>
                            </div>
                        </div>

<!--                        <div class="modal inmodal" id="filemanager" tabindex="-1" role="dialog">-->
<!--                            <div class="modal-dialog modal-xl">-->
<!--                                <div class="modal-content">-->
<!--                                    <div class="modal-header">-->
<!--                                        <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">×</span><span class="sr-only">--><?php //echo $lang_arr['ok'] ?><!--</span></button>-->
<!--                                        <h5 class="modal-title">Выбрать файл</h5>-->
<!--                                    </div>-->
<!--                                    <div class="modal-body">-->
<!--                                        <filemanager ref="fileman" @select_file="sel_file($event)"></filemanager>-->
<!--                                    </div>-->
<!--                                    <div class="modal-footer">-->
<!--                                        <button type="button" class="btn btn-white" data-dismiss="modal">--><?php //echo $lang_arr['ok'] ?><!--</button>-->
<!--                                    </div>-->
<!--                                </div>-->
<!--                            </div>-->
<!--                        </div>-->

                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- <script src="/common_assets/libs/vue.min.js"></script>
<script src="/common_assets/admin_js/vue/kitchen/account_settings.js?<?php echo md5(date('m-d-Y-His A e'));?>"></script>
<script src="/common_assets/admin_js/vue/filemanager2.js?<?php echo md5(date('m-d-Y-His A e'));?>"></script> -->

<!-- Clipboard -->
<!-- <script src="/common_assets/theme/js/plugins/clipboard/clipboard.min.js"></script> -->