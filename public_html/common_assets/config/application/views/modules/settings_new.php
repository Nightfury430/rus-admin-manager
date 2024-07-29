<div class="row wrapper border-bottom white-bg page-heading">
    <div class="col-lg-10">
        <h2><?php echo $lang_arr['modules_settings_heading']?></h2>
    </div>
    <div class="col-lg-2">

    </div>
</div>

<form id="sub_form" action="<?php echo site_url('handles/items_add/') ?>">
    <div class="wrapper wrapper-content  animated fadeInRight">

        <div class="row">
            <div class="col-lg-12">
                <div class="tabs-container">
                    <ul class="nav nav-tabs">
                        <li><a class="nav-link active" data-toggle="tab" href="#defaults"><?php echo $lang_arr['default_params']?></a></li>
                        <li><a class="nav-link" data-toggle="tab" href="#mass_operations"><?php echo $lang_arr['mass_operations']?></a></li>
                    </ul>
                    <div class="tab-content">
                        <div id="defaults" class="tab-pane active">
                            <div class="panel-body">

                                <div class="form-group row">
                                    <div class="col-12">
                                        <b>Основные параметры</b>
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <label class="col-sm-2 col-form-label">
                                        Отступ полок, мм
                                    </label>
                                    <div class="col-sm-10">
                                        <input v-model="settings.order_mail" type="text" class="form-control">
                                        <span class="form-text m-b-none">Отступ полок от переднего края модуля</span>
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <label class="col-sm-2 col-form-label">
                                        Отступ фасада от края, мм
                                    </label>
                                    <div class="col-sm-10">
                                        <input v-model="settings.order_mail" type="text" class="form-control">
                                        <span class="form-text m-b-none">Отступ фасада от края модуля</span>
                                    </div>
                                </div>



                                <div class="form-group row">
                                    <div class="col-12">
                                        <b>Основные параметры (верхние модули)</b>
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <label class="col-sm-2 col-form-label">
                                        Глубина корпуса, мм
                                    </label>
                                    <div class="col-sm-10">
                                        <input v-model="settings.order_mail" type="text" class="form-control">
                                        <span class="form-text m-b-none">Глубина корпуса модулей</span>
                                    </div>
                                </div>


                                <div class="form-group row">
                                    <div class="col-12">
                                        <b>Основные параметры (нижние модули)</b>
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <label class="col-sm-2 col-form-label">
                                        Глубина корпуса, мм
                                    </label>
                                    <div class="col-sm-10">
                                        <input v-model="settings.order_mail" type="text" class="form-control">
                                        <span class="form-text m-b-none">Глубина корпуса модулей</span>
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <label class="col-sm-2 col-form-label">
                                        Глубина столешницы, мм
                                    </label>
                                    <div class="col-sm-10">
                                        <input v-model="settings.order_mail" type="text" class="form-control">
                                        <span class="form-text m-b-none">Глубина столешницы (при наличии)</span>
                                    </div>
                                </div>


                                <div class="form-group row">
                                    <label class="col-sm-2 col-form-label">
                                        Отступ от стены, мм
                                    </label>
                                    <div class="col-sm-10">
                                        <input v-model="settings.order_mail" type="text" class="form-control">
                                        <span class="form-text m-b-none">Технологический отступ от стены</span>
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <label class="col-sm-2 col-form-label">
                                        Ширина верхних планок, мм
                                    </label>
                                    <div class="col-sm-10">
                                        <input v-model="settings.order_mail" type="text" class="form-control">
                                        <span class="form-text m-b-none">Ширина верхних планок</span>
                                    </div>
                                </div>


                                <div class="form-group row">
                                    <div class="col-12">
                                        <b>Основные параметры (угловые нижние модули)</b>
                                    </div>
                                </div>


                                <div class="form-group row">
                                    <label class="col-sm-2 col-form-label">
                                        Отступ от стены сбоку, мм
                                    </label>
                                    <div class="col-sm-10">
                                        <input v-model="settings.order_mail" type="text" class="form-control">
                                        <span class="form-text m-b-none">Технологический отступ от стены сбоку модуля</span>
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <label class="col-sm-2 col-form-label">
                                        Ширина приставной части ДСП, мм
                                    </label>
                                    <div class="col-sm-10">
                                        <input v-model="settings.order_mail" type="text" class="form-control">
                                        <span class="form-text m-b-none">Ширина приставной части ДСП (указывать обязательно, используется в рассчетах)</span>
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <label class="col-sm-2 col-form-label">
                                        Использовать приставную часть ДСП
                                    </label>
                                    <div class="col-sm-10">
                                        <label class="switch">
                                            <input :true-value="1" false-value="0" v-model="settings.price_enabled" type="checkbox">
                                            <span class="slider round"></span>
                                        </label>
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <label class="col-sm-2 col-form-label">
                                        Ширина приставной части фальшфасад, мм
                                    </label>
                                    <div class="col-sm-10">
                                        <input v-model="settings.order_mail" type="text" class="form-control">
                                        <span class="form-text m-b-none">Ширина приставной части фальшфасад</span>
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <label class="col-sm-2 col-form-label">
                                        Материал приставной части фальшфасада
                                    </label>
                                    <div class="col-sm-10">
                                        <select v-model="settings.default_kitchen_model" class="form-control">
                                            <option selected value="">Фасад</option>
                                            <option>Корпус</option>
                                        </select>
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <label class="col-sm-2 col-form-label">
                                        Ширина боковой фасадной планки, мм
                                    </label>
                                    <div class="col-sm-10">
                                        <input v-model="settings.order_mail" type="text" class="form-control">
                                        <span class="form-text m-b-none">Ширина боковой фасадной планки (указывать обязательно, используется в рассчетах)</span>
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <label class="col-sm-2 col-form-label">
                                        Использовать боковую фасадную планку
                                    </label>
                                    <div class="col-sm-10">
                                        <label class="switch">
                                            <input checked :true-value="1" false-value="0" v-model="settings.price_enabled" type="checkbox">
                                            <span class="slider round"></span>
                                        </label>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-sm-2 col-form-label">
                                        Расстояние от боковой фасадной планки до края модуля, мм
                                    </label>
                                    <div class="col-sm-10">
                                        <input v-model="settings.order_mail" type="text" class="form-control">
                                        <span class="form-text m-b-none">Расстояние от боковой фасадной планки до края модуля</span>
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <label class="col-sm-2 col-form-label">
                                        Использовать переднюю приставную планку
                                    </label>
                                    <div class="col-sm-10">
                                        <label class="switch">
                                            <input checked :true-value="1" false-value="0" v-model="settings.price_enabled" type="checkbox">
                                            <span class="slider round"></span>
                                        </label>
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <label class="col-sm-2 col-form-label">
                                        Использовать заднюю приставную планку
                                    </label>
                                    <div class="col-sm-10">
                                        <label class="switch">
                                            <input checked :true-value="1" false-value="0" v-model="settings.price_enabled" type="checkbox">
                                            <span class="slider round"></span>
                                        </label>
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <label class="col-sm-2 col-form-label">
                                        Расстояние от задней приставной планки до края модуля, мм
                                    </label>
                                    <div class="col-sm-10">
                                        <input v-model="settings.order_mail" type="text" class="form-control">
                                        <span class="form-text m-b-none">Расстояние от задней приставной планки до края модуля</span>
                                    </div>
                                </div>








                                <div class="row">
                                   <div class="col-12">
                                       <div class="hr-line-dashed"></div>
                                   </div>
                                </div>





                            </div>
                        </div>
                        <div id="mass_operations" class="tab-pane">
                            <div class="panel-body">
                                <div class="form-group row">
                                    <div class="col-12">
                                        <b>Основные параметры (верхние модули)</b>
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <label class="col-sm-3 col-form-label">
                                        Изменить высоту модулей
                                    </label>
                                    <div class="col-sm-4">
                                        <input v-model="settings.order_mail" type="text" class="form-control">
                                        <span class="form-text m-b-none">Текущая высота модулей, мм</span>
                                    </div>
                                    <label class="col-sm-1 text-center col-form-label">
                                        на
                                    </label>
                                    <div class="col-sm-4">
                                        <input v-model="settings.order_mail" type="text" class="form-control">
                                        <span class="form-text m-b-none">Новая высота модулей, мм</span>
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>


        <div class="row">
            <div class="col-lg-12">
                <div class="ibox ">
                    <div class="ibox-content">
                        <div class="form-group row">
                            <div class="col-sm-4 col-sm-offset-2">
                                <a class="btn btn-white btn-sm" ><?php echo $lang_arr['cancel']?></a>
                                <button class="btn btn-primary btn-sm" type="submit"><?php echo $lang_arr['add']?></button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>

</form>