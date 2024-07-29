<script src="/common_assets/libs/vue.min.js"></script>
<script src="/common_assets/admin_js/vue/coupe_project_settings.js?<?php echo md5(date('m-d-Y-His A e'));?>"></script>
<script src="https://unpkg.com/vue-multiselect@2.1.0"></script>
<link rel="stylesheet" href="https://unpkg.com/vue-multiselect@2.1.0/dist/vue-multiselect.min.css">


<div class="col-sm-12 col-md-12" id="content">
	<h3>Доступные материалы и материалы по умолчанию</h3>

	<form ref="sub_form" id="sub_form" @submit="check_form" data-action="<?php echo site_url('project_settings/coupe_project_settings_update/')?>">


		<div class="panel panel-default">

			<div class="panel-body">

				<div v-if="show_success_message" v-cloak id="success" class="alert alert-success"  >
					<p>Настройки успешно сохранены</p>
				</div>


				<div v-if="errors.length" v-cloak id="errors" class="alert alert-danger error_msg"  >
					<ul>
						<li v-for="error in errors">
							{{ error }}
						</li>
					</ul>
				</div>


                <div class="panel panel-default">
                    <div class="panel-heading">
                        <h4 class="panel-title">Материалы корпуса</h4>
                    </div>
                    <div class="panel-body">

                        <div class="form-group">
                            <label class="typo__label"><?php echo $lang_arr['available_corpus_materials']?>*</label>
                            <multiselect
                                    v-model="available_materials_corpus_value"
                                    :options="available_materials_corpus_options"
                                    :searchable="true"
                                    :close-on-select="false"
                                    :show-labels="false"
                                    :multiple="true"
                                    :hide-selected="true"
                                    label="name"
                                    track-by="id"
                                    @input="available_materials_corpus_change"
                                    placeholder="">
                                <span slot="noResult"><?php echo $lang_arr['no_results']?></span>
                                <span slot="placeholder"><?php echo $lang_arr['choose_available_materials']?></span>
                            </multiselect>
                        </div>



                        <div class="form-group">
                            <label class="typo__label">Материал корпуса по умолчанию*</label>
                            <multiselect
                                    v-model="default_corpus_material_value"
                                    :options="default_corpus_material_options"
                                    :searchable="true"
                                    :close-on-select="true"
                                    :show-labels="false"
                                    :multiple="false"
                                    :hide-selected="false"
                                    group-values="items"
                                    group-label="name"

                                    :disabled="available_materials_corpus_value.length == 0"
                                    label="name"
                                    track-by="id"
                                    placeholder="">

                                <template slot="singleLabel" slot-scope="props">
                                    <span v-if="props.option.params != null">
                                    <img class="option_img" v-if="props.option.params.params.map != null" class="option__image" :src="props.option.params.params.map">
                                    <span class="option_color" v-if="props.option.params.params.map == null" v-bind:style="{ background: props.option.params.params.color}"></span>
                                    <span class="option__title">{{ props.option.name }}</span>
                                    <span class="option__category">{{ props.option.cat_name }}</span>
                                                                                </span>

                                </template>

                                <template slot="option" slot-scope="props">

                                    <div class="option__desc">
                                    <span v-if="props.option.params != null">

                                        <img class="option_img" v-if="props.option.params.params.map != null" class="option__image" :src="props.option.params.params.map">
                                        <span class="option_color" v-if="props.option.params.params.map == null" v-bind:style="{  background: props.option.params.params.color}"></span>
                                        <span class="option__title">{{ props.option.name }}</span>
                                        <span class="option__category">{{ props.option.cat_name }}</span>
                                                                                                                        </span>

                                    </div>
                                </template>
                                <span slot="noResult"><?php echo $lang_arr['no_results']?></span>
                                <span slot="placeholder">Выберите материал</span>
                            </multiselect>
                        </div>

                        <div class="panel panel-default mt-4">
                            <div class="panel-heading">
                                <h4 class="panel-title">Цены на материалы корпуса (м2)</h4>
                            </div>
                            <div class="panel-body">
                                <div class="form-group" v-for="item in available_materials_corpus_value">
                                    <div class="row mb-2">
                                        <div style="font-weight: bold" class="col-4">{{item.name}}</div>
                                        <div class="col-4"><input v-model="prices.corpus[item.id]" class="form-control" type="number" min="0" step="0.01"></div>
                                    </div>


                                    <div class="form-group row" v-for="s_item in item.categories">
                                        <div class="col-4">{{s_item.name}}</div>
                                        <div class="col-4"><input v-model="prices.corpus[s_item.id]" class="form-control" type="number" min="0" step="0.01"></div>
                                    </div>
                                </div>
                            </div>
                        </div>



                    </div>
                </div>




                <div class="panel panel-default">
                    <div class="panel-heading">
                        <h4 class="panel-title">Материалы дверей</h4>
                    </div>
                    <div class="panel-body">

                        <div class="form-group">
                            <label class="typo__label">Доступные материалы дверей*</label>
                            <multiselect
                                    v-model="available_materials_doors_value"
                                    :options="available_materials_doors_options"
                                    :searchable="true"
                                    :close-on-select="false"
                                    :show-labels="false"
                                    :multiple="true"
                                    :hide-selected="true"
                                    label="name"
                                    track-by="id"
                                    @input="available_materials_doors_change"
                                    placeholder="">
                                <span slot="noResult"><?php echo $lang_arr['no_results']?></span>
                                <span slot="placeholder"><?php echo $lang_arr['choose_available_materials']?></span>
                            </multiselect>
                        </div>

                        <div class="panel panel-default mt-4">
                            <div class="panel-heading">
                                <h4 class="panel-title">Цены на материалы дверей (м2)</h4>
                            </div>
                            <div class="panel-body">
                                <div class="form-group" v-for="item in available_materials_doors_value">
                                    <div class="row mb-2">
                                        <div style="font-weight: bold" class="col-4">{{item.name}}</div>
                                        <div class="col-4"><input v-model="prices.doors[item.id]" class="form-control" type="number" min="0" step="0.01"></div>
                                    </div>


                                    <div class="form-group row" v-for="s_item in item.categories">
                                        <div class="col-4">{{s_item.name}}</div>
                                        <div class="col-4"><input v-model="prices.doors[s_item.id]"  class="form-control" type="number" min="0" step="0.01"></div>
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>

                <div class="panel panel-default">
                    <div class="panel-heading">
                        <h4 class="panel-title">Материал задней стенки</h4>
                    </div>
                    <div class="panel-body">

                        <div class="form-group">
                            <label class="typo__label">Материал задней стенки*</label>
                            <multiselect
                                    v-model="default_back_wall_material_value"
                                    :options="default_back_wall_material_options"
                                    :searchable="true"
                                    :close-on-select="true"
                                    :show-labels="false"
                                    :multiple="false"
                                    :hide-selected="false"

                                    label="name"
                                    track-by="id"
                                    placeholder="">

                                <template slot="singleLabel" slot-scope="props">
                                    <span v-if="props.option.params != null">
                                    <img class="option_img" v-if="props.option.params.params.map != null" class="option__image" :src="props.option.params.params.map">
                                    <span class="option_color" v-if="props.option.params.params.map == null" v-bind:style="{ background: props.option.params.params.color}"></span>
                                    <span class="option__title">{{ props.option.name }}</span>
                                    <span class="option__category">{{ props.option.cat_name }}</span>
                                    </span>
                                </template>

                                <template slot="option" slot-scope="props">

                                    <div class="option__desc">
                                        <span v-if="props.option.params != null">
                                        <img class="option_img" v-if="props.option.params.params.map != null" class="option__image" :src="props.option.params.params.map">
                                        <span class="option_color" v-if="props.option.params.params.map == null" v-bind:style="{ background: props.option.params.params.color}"></span>
                                        <span class="option__title">{{ props.option.name }}</span>
                                        <span class="option__category">{{ props.option.cat_name }}</span>
                                        </span>
                                    </div>
                                </template>
                                <span slot="noResult"><?php echo $lang_arr['no_results']?></span>
                                <span slot="placeholder">Выберите материал</span>
                            </multiselect>
                        </div>

                        <div class="panel panel-default mt-4">
                            <div class="panel-heading">
                                <h4 class="panel-title">Цена на материал задней стенки (м2)</h4>
                            </div>
                            <div class="panel-body">
                                <div class="form-group row">
                                    <div class="col-4">{{default_back_wall_material_value.name}}</div>
                                    <div class="col-4"><input v-model="prices.back_wall"  class="form-control" type="number" min="0" step="0.01"></div>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>


                <div class="panel panel-default">
                    <div class="panel-heading">
                        <h4 class="panel-title">Стоимость штанги (р / м.п.)</h4>
                    </div>
                    <div class="panel-body">



                        <div class="panel panel-default mt-4">
                            <div class="panel-heading">
                                <h4 class="panel-title">Цена на штангу (р / м.п.)</h4>
                            </div>
                            <div class="panel-body">
                                <div class="form-group row">
                                    <div class="col-12"><input v-model="prices.rail"  class="form-control" type="number" min="0" step="0.01"></div>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>


                <div class="panel panel-default">
                    <div class="panel-heading">
                        <h4 class="panel-title">Стоимость направлюящих ящика (р / комплект)</h4>
                    </div>
                    <div class="panel-body">



                        <div class="panel panel-default mt-4">
                            <div class="panel-heading">
                                <h4 class="panel-title">Цена на комплект направляющих (р / комплект)</h4>
                            </div>
                            <div class="panel-body">
                                <div class="form-group row">
                                    <div class="col-12"><input v-model="prices.locker"  class="form-control" type="number" min="0" step="0.01"></div>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>

            </div>
		</div>



		<input class="btn btn-success" type="submit" name="submit" value="<?php echo $lang_arr['save']?>"/>
		<a class="btn btn-danger" href="<?php echo site_url('templates/index/') ?>"><?php echo $lang_arr['cancel']?></a>


		<input id="categories_json" type="hidden" value="<?php xecho(json_encode($categories))?>">
		<input id="materials_json" type="hidden" value="<?php xecho(json_encode($materials))?>">
        <input id="settings_json" type="hidden" value="<?php xecho(json_encode($settings))?>">
        <input id="const_path" type="hidden" value="<?php echo $this->config->item('const_path')?>">


	</form>
</div>

<style>
    .option_color{
        width: 40px;
        height: 40px;
        display: inline-block;
        vertical-align: middle;
    }
    .option_img{
        width: 40px;
        height: 40px;
        display: inline-block;
    }

    .option__title{
        display: inline-block;
        vertical-align: middle;
    }

    .option__category{
        float: right;
        display: inline-block;
        vertical-align: middle;
        font-weight: bold;
        margin-top: 11px;
    }

    .multiselect{
        height: 40px;
    }

</style>
