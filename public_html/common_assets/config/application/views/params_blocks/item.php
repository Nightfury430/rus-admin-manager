<?php $contr_name = 'params_blocks'; ?>
<form @submit="submit" ref="form" id="sub_form" action="<?php echo site_url($contr_name . '/item_add/') ?><?php if (isset($id)) echo $id ?>">
    <input ref="success_url" id="form_success_url" value="<?php echo site_url('/catalog/items/' . $contr_name) ?>" type="hidden">
    <input id="controller_name" value="<?php echo $contr_name ?>" type="hidden">
    <?php if (isset($id)): ?>
        <input id="item_id" value="<?php echo $id ?>" type="hidden">
    <?php endif; ?>

    <div v-cloak class="wrapper wrapper-content  animated fadeInRight">
        <div class="row">
            <div class="col-lg-12">
                <div class="nav-align-top nav-tabs-shadow mb-6">
                    <ul class="nav nav-tabs" role="tablist">
                        <li class="nav-item">
                            <button
                            type="button"
                            class="nav-link active"
                            role="tab"
                            data-bs-toggle="tab"
                            data-bs-target="#basic_params_tab"
                            aria-controls="basic_params_tab"
                            aria-selected="true">
                                <?php echo $lang_arr['basic_params'] ?>
                            </button>
                        </li>
                        <li class="nav-item">
                            <button
                            type="button"
                            class="nav-link"
                            role="tab"
                            data-bs-toggle="tab"
                            data-bs-target="#model_params_tab"
                            aria-controls="model_params_tab"
                            aria-selected="true">
                                <?php echo $lang_arr['view_parameters'] ?>
                            </button>
                        </li>
                    </ul>
                    <div class="tab-content">
                        <div id="basic_params_tab" class="tab-pane fade show active" role="tabpanel">
                            <div class="panel-body">
                                <fieldset>
                                    <div class="form-group row mb-3">
                                        <label class="col-sm-2 col-form-label"><?php echo $lang_arr['name'] ?></label>
                                        <div class="col-sm-10">
                                            <input type="text" v-model="item.name" class="form-control" >
                                        </div>
                                    </div>
                                    <div class="form-group row mb-3">
                                        <label class="col-sm-2 col-form-label"><?php echo $lang_arr['active'] ?></label>
                                        <div class="col-sm-10">
                                            <label class="switch">
                                                <input v-bind:true-value="1" v-bind:false-value="0" v-model="item.active" type="checkbox">
                                                <span class="slider round"></span>
                                            </label>
                                        </div>
                                    </div>
                                    <div class="form-group row mb-3">
                                        <label class="col-sm-2 col-form-label"><?php echo $lang_arr['order'] ?></label>
                                        <div class="col-sm-10">
                                            <input type="number" v-model.number="item.order" class="form-control" id="order" name="order">
                                        </div>
                                    </div>
                                </fieldset>
                            </div>
                        </div>
                        <div id="model_params_tab" class="tab-pane fade">
                            <div class="panel-body">
                                <div v-for="(param, index) in item.data" style="position: relative" class="row card-body">
                                    <div class="col-12">
                                        <div class="form-group row mb-3">
                                            <label class="col-sm-2 col-form-label">Тип</label>
                                            <div class="col-sm-10">
                                                <select @change="change_type(index)" v-model="param.type" class="form-control">
                                                    <option v-for="opt in types" :value="opt.key">{{opt.name}}</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-12">
                                        <div v-if="param.type == 'number'">
                                            <div class="form-group row mb-3">
                                                <label class="col-sm-2 col-form-label">Название</label>
                                                <div class="col-sm-10">
                                                    <input type="text" v-model="param.name" class="form-control" >
                                                </div>
                                            </div>

                                            <div class="form-group row mb-3">
                                                <label class="col-sm-2 col-form-label">Значение по умолчанию</label>
                                                <div class="col-sm-10">
                                                    <input type="number" v-model="param.value" class="form-control" >
                                                </div>
                                            </div>

                                            <div class="form-group row mb-3">
                                                <label class="col-sm-2 col-form-label">Минимальное значение</label>
                                                <div class="col-sm-10">
                                                    <input type="number" v-model="param.params.min" class="form-control" >
                                                </div>
                                            </div>

                                            <div class="form-group row mb-3">
                                                <label class="col-sm-2 col-form-label">Максимальное значение</label>
                                                <div class="col-sm-10">
                                                    <input type="number" v-model="param.params.max" class="form-control" >
                                                </div>
                                            </div>

                                            <div class="form-group row mb-3">
                                                <label class="col-sm-2 col-form-label">Шаг</label>
                                                <div class="col-sm-10">
                                                    <input type="number" v-model="param.params.step" class="form-control" >
                                                </div>
                                            </div>
                                        </div>
                                        <div v-if="param.type == 'select'">
                                            <div class="form-group row mb-3">
                                                <label class="col-sm-2 col-form-label">Тип рассчетов</label>
                                                <div class="col-sm-10">
                                                    <select v-model="param.params.price_mode" class="form-control">
                                                        <option value="add">Изменение цены</option>
                                                        <option value="replace">Замена цены</option>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="form-group row mb-3">
                                                <label class="col-sm-2 col-form-label">Название</label>
                                                <div class="col-sm-10">
                                                    <input type="text" v-model="param.name" class="form-control" >
                                                </div>
                                            </div>
                                            <div class="row">
                                                <label class="col-sm-2 col-form-label">Список значений</label>
                                                <div class="col-10">
                                                    <div v-for="opt in param.params.options">
                                                        <div class="form-group row mb-3">
                                                            <div class="col-4">
                                                                <label class="mb-0">Название</label>
                                                                <input @input="opt_name_input(opt)" type="text" v-model="opt.name" class="input-sm form-control" >
                                                            </div>
                                                            <div class="col-4">
                                                                <label class="mb-0">Значение</label>
                                                                <input type="text" v-model="opt.value" class="input-sm form-control" >
                                                            </div>
                                                            <div class="col-4">
                                                                <label class="mb-0">Цена</label>
                                                                <input type="number" v-model="opt.price" class="input-sm form-control" >
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="form-group row mb-3">
                                                <div class="col-2"></div>
                                                <div class="col-10">
                                                    <button @click="add_option(index)" type="button" class="btn btn-xs btn-w-m btn-success  ">Добавить</button>
                                                </div>
                                            </div>
                                            <div class="form-group row mb-3">
                                                <label class="col-sm-2 col-form-label">Значение по умолчанию</label>
                                                <div class="col-sm-10">
                                                    <select v-model="param.value" class="form-control">
                                                        <option v-for="opt in param.params.options" :value="opt.value">{{opt.name}}</option>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                        <div v-if="param.type == 'boolean'">
                                            <div class="form-group row mb-3">
                                                <label class="col-sm-2 col-form-label">Название</label>
                                                <div class="col-sm-10">
                                                    <input type="text" v-model="param.name" class="form-control" >
                                                </div>
                                            </div>
                                            <div class="form-group row mb-3">
                                                <label class="col-sm-2 col-form-label">Тип рассчетов</label>
                                                <div class="col-sm-10">
                                                    <select v-model="param.params.price_mode" class="form-control">
                                                        <option value="add">Изменение цены</option>
                                                        <option value="replace">Замена цены</option>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="form-group row mb-3">
                                                <label class="col-sm-2 col-form-label">Значение по умолчанию</label>
                                                <div class="col-sm-10">
                                                    <label class="switch">
                                                        <input :true-value="1" :false-value="0" v-model="param.value" type="checkbox">
                                                        <span class="slider round"></span>
                                                    </label>
                                                </div>
                                            </div>
                                            <div class="form-group row mb-3">
                                                <label class="col-sm-2 col-form-label">Цена</label>
                                                <div class="col-sm-10">
                                                    <input type="number" v-model="param.params.price" class="form-control" >
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <button @click="remove_param(index)" title="<?php echo $lang_arr['delete'] ?>"  style="position: absolute; right: -22px; top:2px; width: 30px; padding: 5px 5px 5px 4px;" type="button" class="del_btn btn btn-w btn-danger  ">
                                        <span class="glyphicon glyphicon-trash"></span>
                                    </button>
                                </div>
                                <button @click="add_item()" type="button" class="btn btn-w-m btn-primary  ">Добавить</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-12">
                <div class="card ">
                    <div class="card-body">
                        <div class="form-group row mb-3">
                            <div class="col-sm-4">
                                <a class="btn btn-white btn-sm" href="<?php echo site_url('catalog/items/' . $contr_name) ?>"><?php echo $lang_arr['cancel'] ?></a>
                                <?php if (isset($id)): ?>
                                    <button class="btn btn-primary btn-sm" type="submit"><?php echo $lang_arr['save'] ?></button>
                                <?php else: ?>
                                    <button class="btn btn-primary btn-sm" type="submit"><?php echo $lang_arr['add'] ?></button>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</form>
<script src="/common_assets/libs/vue.min.js"></script>
<script src="/common_assets/admin_js/vue/kitchen/params_blocks.js"></script>