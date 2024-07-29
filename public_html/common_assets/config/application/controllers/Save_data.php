<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Save_data extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->load->helper('url_helper');
        $this->load->model('materials_model');
        $this->load->model('glass_model');
        $this->load->model('facades_model');
        $this->load->model('templates_model');
        $this->load->model('tech_model');
        $this->load->model('comms_model');
        $this->load->model('interior_model');
        $this->load->model('modules_model');
        $this->load->model('handles_model');
        $this->load->model('kitchen_models_model');
        $this->load->model('module_sets_model');
        $this->load->model('project_settings_model');
        $this->load->model('prices_model');
        $this->load->model('constructor_model');
        $this->load->library('session');
        $this->load->model('settings_model');
        $this->load->model('common_model');
    }

    public function index()
    {

        if(isset($_POST['save'])){

            if(isset($_POST['sync_key'])){
                if($_POST['sync_key'] != crypt($this->config->item('username'), 'RTKtGEh%d$f4`r6w1*zCsdg53sq234qs56')){
                    echo 'keys don\'t match';
                    exit;
                }
            } else {
                if(!$this->session->username || $this->session->username != $this->config->item('username')){
                    redirect('login', 'refresh');
                }
            }



            $errors = array();

            $this->load->helper('file');

            $prices = $this->prices_model->get();

            $constructor_settings = $this->constructor_model->get();
            if(!empty($constructor_settings['settings'])){
                $constructor_settings = json_decode($constructor_settings['settings'], true);
            }



            $constructor_settings['default_language'] = $this->settings_model->get_settings()['default_language'];



	        if(!empty($constructor_settings['pdf_password'])) $constructor_settings['pdf_password'] = md5(md5($constructor_settings['pdf_password']));



	        if($this->config->item('username') == 'nevolin333@yandex.ru'){
		        $constructor_settings['no_email_pdf'] = 1;
		        $constructor_settings['no_email_image'] = 1;
	        }

	        $email_settings = $this->common_model->get_row('Email');

			$constructor_settings['email'] = array();
			$constructor_settings['email']['use_custom_form'] = $email_settings['use_custom_form'];
			if($email_settings['use_custom_form'] == 1){
				$constructor_settings['email']['custom_form_data'] = json_decode($email_settings['custom_form_data']);
			}




	        $constructor_settings['pdf_settings'] = $this->common_model->get_row('Report');
//			$constructor_settings['modules_settings'] = json_decode($this->common_model->get_row('Common_settings')['modules_settings']);

            $ps = $this->project_settings_model->get_settings();
            if(isset($ps['settings'])){
                $ps = json_decode($ps['settings'], true);

            }else {
                $ps['available_materials_back_wall'] = json_decode($ps['available_materials_back_wall'], true);
                $ps['available_corpus_thickness'] = json_decode($ps['available_corpus_thickness'], true);
            }

            if(!isset($ps['available_materials_glass_shelves'])) $ps['available_materials_glass_shelves'] = [];
            if(!isset($ps['selected_cornice_model'])) $ps['selected_cornice_model'] = 0;
            if(!isset($ps['available_shelves_thickness'])) $ps['available_shelves_thickness'] = [16];
            if(!isset($ps['default_shelves_thickness'])) $ps['default_shelves_thickness'] = 16;

            $materials = $this->materials_model->get_all_active_items();

//            print_pre($materials);

            $res = array();
            foreach ($materials as $material){

                if(isset($material['data'])){
                    $m = json_decode($material['data']);
                    $m->id = $material['id'];
                } else {
                    $m = new stdClass();
                    $m->params = new stdClass();
                    $m->add_params = new stdClass();

                    $m->id = (int)$material['id'];
                    $m->name = $material['name'];
                    $m->code = $material['code'];
                    $m->category = (int)$material['category'];
                    $m->type = 'Standart';

                    if(isset($material['color'])) $m->params->color = $material['color'];
                    if(isset($material['map'])) $m->params->map = $material['map'];
                    if(isset($material['roughness'])) $m->params->roughness = (float)$material['roughness'];
                    if(isset($material['metalness'])) $m->params->metalness = (float)$material['metalness'];
                    if(isset($material['transparent'])) $m->params->transparent = (boolean)$material['transparent'];

                    if(isset($material['real_width'])) $m->add_params->real_width = (float)$material['real_width'];
                    if(isset($material['real_height'])) $m->add_params->real_height = (float)$material['real_height'];
                    if(isset($material['stretch_width'])) $m->add_params->stretch_width = (boolean)$material['stretch_width'];
                    if(isset($material['stretch_height'])) $m->add_params->stretch_height = (boolean)$material['stretch_height'];
                    if(isset($material['wrapping'])) $m->add_params->wrapping = $material['wrapping'];
                }



                $res[] = $m;
                unset($m);
            }
            unset($materials);

//            $categories = array_non_empty_items($this->materials_model->get_active_categories());
            $categories = $this->materials_model->get_categories();
            $res_cat = array();
            foreach ($categories as $cat){
                $c = new stdClass();

                $c->id = (int)$cat['id'];
                $c->name = $cat['name'];
                $c->active = $cat['active'];
                if(isset($cat['image']))$c->image = $cat['image'];
                if(isset($cat['parent'])) $c->parent = (int)$cat['parent'];

                $res_cat[] = $c;
                unset($c);
            }
            unset($categories);

            $mat_result = new stdClass();

            $mat_result->categories = $res_cat;
            $mat_result->items = $res;





            $glass_materials = $this->glass_model->get_all_active_items();
            $gres_mats = array();


            foreach ($glass_materials as $material){
                $gres_mats[] = json_decode($material['params']);
            }

//            $gres_categories = array_non_empty_items($this->glass_model->get_active_categories());
            $gres_categories = $this->glass_model->get_categories();
            $gres_cat = array();
            foreach ($gres_categories as $cat){
                $c = new stdClass();

                $c->id = (int)$cat['id'];
                $c->name = $cat['name'];
                $c->active = $cat['active'];
                if(isset($cat['image']))$c->image = $cat['image'];
                if(isset($cat['parent'])) $c->parent = (int)$cat['parent'];

                $gres_cat[] = $c;
            }

            $glass_result = new stdClass();
            $glass_result->categories = $gres_cat;
            $glass_result->items = $gres_mats;

	        $kitchen_models_catalog = array();
	        if($constructor_settings['shop_mode'] == 1){
		        $kitchen_models = $this->kitchen_models_model->get_all_active_items();

		        if($constructor_settings['shop_mode'] == 1 && count($kitchen_models) < 1){
			        $errors[] = 'Нет ни одной добавленной модели кухни. Добавьте модели в разделе "Модели кухни" или выключите режим дилера';
			        echo json_encode( array('errors' => $errors), JSON_UNESCAPED_UNICODE|JSON_PRETTY_PRINT);
			        exit;
		        }


		        foreach ($kitchen_models as &$item){

		            $dec_flag = 0;

		            if($item['settings']){
                        $item = json_decode($item['settings'], true);
                        $dec_flag = 1;
                    }



			        $it = new stdClass();

			        $it->id = (int)$item['id'];
			        $it->name = $item['name'];
			        $it->category = $item['category'];
			        $it->order = $item['order'];
			        $it->icon = $item['icon'];
			        $it->is_kitchen_model = true;
			        $it->door_offset = (int)$item['door_offset'];
			        $it->shelve_offset = (int)$item['shelve_offset'];
			        $it->corpus_thickness = (int)$item['corpus_thickness'];
			        $it->tabletop_thickness = (int)$item['tabletop_thickness'];
			        $it->bottom_modules_height = (int)$item['bottom_modules_height'];
			        $it->bottom_as_top_facade_models = (bool)$item['bottom_as_top_facade_models'];
			        $it->bottom_as_top_facade_materials = (bool)$item['bottom_as_top_facade_materials'];
			        $it->bottom_as_top_corpus_materials = (bool)$item['bottom_as_top_corpus_materials'];
			        $it->cokol_as_corpus = (bool)$item['cokol_as_corpus'];
			        $it->cokol_height = (int)$item['cokol_height'];
			        $it->is_cokol_active = (bool)$item['cokol_active'];

			        $it->cornice_available = (int)$item['cornice_available'];
			        $it->cornice_active = (bool)$item['cornice_active'];
			        $it->selected_cornice_model = $ps['selected_cornice_model'];

			        $it->models = new stdClass();
			        $it->models->top = (int)$item['facades_models_top'];
			        $it->models->bottom = (int)$item['facades_models_bottom'];

			        $it->materials = new stdClass();
			        $it->materials->top = new stdClass();
			        $it->materials->bottom = new stdClass();

                    $selected_facade_top = $this->facades_model->get_item((int)$item['facades_models_top']);
                    $selected_facade_bottom = $this->facades_model->get_item((int)$item['facades_models_bottom']);



                    if(!$selected_facade_top) {
                        $selected_facade_top = $this->facades_model->get_first_active_item();
                            $item['facades_models_top'] = $selected_facade_top['id'];
                        $it->models->top =  (int)$item['facades_models_top'];
                    }


                    if(!$selected_facade_bottom) {
                        $selected_facade_bottom = $this->facades_model->get_first_active_item();
                        $item['facades_models_bottom'] = $selected_facade_bottom['id'];
                        $it->models->bottom = (int)$item['facades_models_bottom'];
                    }




			        $top_facades_materials = json_decode($this->facades_model->get_item((int)$item['facades_models_top'])['materials']);
			        $bottom_facades_materials = json_decode($this->facades_model->get_item((int)$item['facades_models_bottom'])['materials']);


			        if(!empty($top_facades_materials)){
                        foreach ($top_facades_materials as $key => $var) {
                            if($this->materials_model->check_category_active((int)$var)){
                                $top_facades_materials[$key] = (int)$var;
                            } else {
                                unset($top_facades_materials[$key]);
                            }
                        }
                    }
                    if(!empty($bottom_facades_materials)){
                        foreach ($bottom_facades_materials as $key => $var) {

                            foreach ($bottom_facades_materials as $key => $var) {

                                if($this->materials_model->check_category_active((int)$var)){
                                    $bottom_facades_materials[$key] = (int)$var;
                                } else {
                                    unset($bottom_facades_materials[$key]);
                                }
                            }
                        }
                    }





			        $it->materials->top->facades = $top_facades_materials;
                    if($dec_flag){
                        $it->materials->top->corpus = ($item['corpus_materials_top']);
                    } else {
                        $it->materials->top->corpus = json_decode($item['corpus_materials_top']);
                    }



			        $it->materials->bottom->facades = $bottom_facades_materials;
                    if($dec_flag){
                        $it->materials->bottom->corpus = ($item['corpus_materials_top']);
                    } else {
                        $it->materials->bottom->corpus = json_decode($item['corpus_materials_top']);
                    }


                    if($dec_flag){
                        $it->materials->cokol = ($item['cokol_materials']);
                    } else {
                        $it->materials->cokol = json_decode($item['cokol_materials']);
                    }


                    if($dec_flag){
                        $it->materials->tabletop = ($item['tabletop_materials']);
                    } else {
                        $it->materials->tabletop = json_decode($item['tabletop_materials']);
                    }



			        $it->materials->walls =$ps['available_materials_walls'];
			        $it->materials->floor =$ps['available_materials_floor'];

                    if($dec_flag){
                        $it->materials->wall_panel = ($item['wallpanel_materials']);
                    } else {
                        $it->materials->wall_panel = json_decode($item['wallpanel_materials']);
                    }



			        $it->selected_materials = new stdClass();
			        $it->selected_materials->top = new stdClass();
			        $it->selected_materials->bottom = new stdClass();

			        $it->selected_materials->top->facades = (int)$item['facades_selected_material_top'];
			        $it->selected_materials->top->corpus = (int)$item['selected_corpus_material_top'];

			        $it->selected_materials->bottom->facades = (int)$item['facades_selected_material_bottom'];
			        $it->selected_materials->bottom->corpus = (int)$item['selected_corpus_material_bottom'];

			        $it->selected_materials->cokol = (int)$item['selected_cokol_material'];
			        $it->selected_materials->tabletop = (int)$item['selected_tabletop_material'];
			        $it->selected_materials->wall_panel = (int)$item['selected_wallpanel_material'];

			        $it->selected_materials->walls = (int)$ps['selected_material_walls'];
			        $it->selected_materials->floor = (int)$ps['selected_material_floor'];

			        if(isset($item['pat_material'])){
				        $it->selected_materials->pat = (int)$item['pat_material'];
			        }


			        if(isset($item['fixed_materials'])){
                        if($dec_flag){
                            $it->fixed_materials = ( '['. $item['fixed_materials'] . ']' );
                        } else {
                            $it->fixed_materials = json_decode( '['. $item['fixed_materials'] . ']' );
                        }

			        }


			        if(isset($item['glass_materials'])){

                        if($dec_flag){
                            $it->materials->glass = ($item['glass_materials']);
                        } else {
                            $it->materials->glass = json_decode($item['glass_materials']);
                        }


			        }

			        if(isset($item['selected_glass_material'])) {
				        $it->selected_materials->glass = (int)$item['selected_glass_material'];
			        }

			        $it->handle = new stdClass();
			        $it->handle->orientation = $item['handle_orientation'];
			        $it->handle->lockers_position = $item['handle_lockers_position'];
			        $it->handle->selected_model = (int)$item['handle_selected_model'];
			        $it->handle->preferable_size = (int)$item['handle_preferable_size'];
			        $it->handle->no_handle = (int)$item['no_handle'];

			        $handle_model = $this->handles_model->get_item((int)$item['handle_selected_model']);


			        if((int)$item['no_handle'] != 1){
				        if(empty($handle_model)){
					        $errors[] = 'Не выбрана или удалена ручки в модели кухни ' . $it->name;
					        echo json_encode( array('errors' => $errors), JSON_UNESCAPED_UNICODE|JSON_PRETTY_PRINT);
					        exit;
				        }

                        $h_mod = new stdClass();

                        $h_mod->id = (int)$handle_model['id'];
                        $h_mod->category = (int)$handle_model['category'];
                        $h_mod->name = $handle_model['name'];
                        $h_mod->icon = $handle_model['icon'];
                        $h_mod->model = $handle_model['model'];
                        $h_mod->type = $handle_model['type'];
                        $h_mod->size_index = (int)$item['handle_preferable_size'];
                        $h_mod->material = json_decode($handle_model['material']);
                        $h_mod->sizes = json_decode($handle_model['variants']);

                        $it->handle->model = $h_mod;
			        }







			        $it->hinges = new stdClass();
			        $it->hinges->id = 0;
			        $it->hinges->name = 'С доводчиком';
			        $it->hinges->icon = '/common_assets/images/with_dovod.jpg';
			        $it->hinges->door_hinges_price = 1000;
			        $it->hinges->locker_hinges_price = 1000;
			        $it->hinges->dovodchik = true;

			        $it->wall_panel = new stdClass();
			        $it->wall_panel->active = (bool)$item['wallpanel_active'];
			        $it->wall_panel->height = (int)$item['wallpanel_height'];

			        $it->modules_set_id = (int)$item['available_modules'];





			        $set = $this->module_sets_model->get_set((int)$item['available_modules']);


			        if(empty($set)){
				        $errors[] = 'Не назначен набор модулей на модель кухни ' . $it->name;
				        echo json_encode( array('errors' => $errors), JSON_UNESCAPED_UNICODE|JSON_PRETTY_PRINT);
				        exit;
			        }




			        $it->available_selection = new stdClass();
			        $it->available_selection->allow_facades_materials_select = $item['allow_facades_materials_select'];

			        if(isset($item['allow_glass_material_select'])) {
				        $it->available_selection->allow_glass_material_select = $item['allow_glass_material_select'];
			        }

			        $it->available_selection->allow_corpus_materials_select = $item['allow_corpus_materials_select'];
			        $it->available_selection->allow_cokol_materials_select = $item['allow_cokol_materials_select'];
			        $it->available_selection->allow_tabletop_materials_select = $item['allow_tabletop_materials_select'];
			        $it->available_selection->allow_wallpanel_materials_select = $item['allow_wallpanel_materials_select'];
			        $it->available_selection->allow_handles_select = $item['allow_handles_select'];

			        if($constructor_settings['multiple_facades_mode'] == 1){

			            if($dec_flag == 1){
                            $it->prices_data = $item['price_data'];
                        } else {
                            $it->prices_data = json_decode($item['price_data'], true);
                        }



				        if(isset($it->prices_data)){
					        foreach ($it->prices_data as $fac_dat){
						        foreach ($fac_dat['modules'] as $fac_dat_mod){
							        foreach ($fac_dat_mod['variants'] as $fac_data_mod_vars){
								        $fac_data_mod_vars['price'] = intval($fac_data_mod_vars['price']);
							        }
						        }
					        }
				        }

                        if ($dec_flag == 1) {
                            $tmp = $item['facades_categories'];
                        } else {
                            $tmp = json_decode($item['facades_categories']);
                        }



				        if($tmp[0] === null){
					        $errors[] = 'В кухне ' . $it->name . ' не указаны категории доступных фасадов. Укажите категории фасадов или выключите режим нескольких фасадов в модели кухни <br>';
				        }

				        $tmp2 = array();

				        foreach ($tmp as $fcat){
					        $tmp2 = array_merge($tmp2, $this->facades_model->get_items($fcat,0,1000));
				        }


//                    $tmp = $this->facades_model->get_items();

				        $facade_sets = $tmp2;
				        $fs = array();
				        foreach ($facade_sets as $cat){
					        $c = new stdClass();

					        $c_mats = json_decode($cat['materials']);



					        foreach ($c_mats as $key => $var) {

						        if($this->materials_model->check_category_active((int)$var)){
							        $c_mats[$key] = (int)$var;
						        } else {
							        unset($c_mats[$key]);
						        }
					        }


					        if($cat['data'] != null){

						        $c = json_decode($cat['data']);
                                $c->active = $cat['active'];

					        } else {

						        $c->id   = (int) $cat['id'];
						        $c->name = $cat['name'];

						        if ( isset( $cat['description'] ) ) {
							        $c->description = $cat['description'];
						        }
						        $c->category  = (int) $cat['category'];
						        $c->icon      = $cat['icon'];
						        $c->materials = $c_mats;

						        $c->facades = new stdClass();

						        $c->full          = json_decode( $cat['full'] );
						        $c->window        = json_decode( $cat['window'] );
						        $c->frame         = json_decode( $cat['frame'] );
						        $c->radius        = json_decode( $cat['radius_full'] );
						        $c->radius_window = json_decode( $cat['radius_window'] );
						        $c->radius_frame  = json_decode( $cat['radius_frame'] );

						        if ( isset( $cat['parent'] ) ) {
							        $c->parent = (int) $cat['parent'];
						        }
					        }
					        $fs[] = $c;
				        }

				        $it->facade_sets = $fs;






				        $tmp2 = array();

				        foreach ($tmp as $fcat){
					        $tmp2[] = $this->facades_model->get_category($fcat);
					        $tmp2 = array_merge($tmp2, $this->facades_model->get_category_children($fcat));
				        }




				        $facade_categories = $tmp2;
				        $fac_cat = array();



                            foreach ($facade_categories as $cat){

                                if(!$cat) continue;

                                $c = new stdClass();

                                $c->id = (int)$cat['id'];
                                $c->name = $cat['name'];
                                if(isset($cat['image']))$c->image = $cat['image'];
                                if(isset($cat['description'])){
                                    $c->description = $cat['description'];
                                }
                                if(isset($cat['parent'])) $c->parent = (int)$cat['parent'];

                                $fac_cat[] = $c;
                                unset($c);
                            }


				        $it->facade_categories = $facade_categories;
				        unset($facade_categories);

			        }





			        $kitchen_models_catalog[] = $it;
			        unset($it);
		        }
		        unset($kitchen_models);

		        if($constructor_settings['shop_mode'] == 1 && count($errors) > 0 ) {
			        echo json_encode( array('errors' => $errors), JSON_UNESCAPED_UNICODE|JSON_PRETTY_PRINT);
			        exit;
		        }
	        }


	        $module_sets_catalog = array();


            if($constructor_settings['shop_mode'] == 1 ) {
                $module_sets = $this->module_sets_model->get_sets();

                foreach ($module_sets as $set) {

                    $module_sets_catalog[$set['id']] = array(
                        "categories" => array(),
                        "items" => array()
                    );


                    $set_cats = $this->module_sets_model->get_categories_by_set_id($set['id']);
                    $set_modules = $this->module_sets_model->get_all_items_by_set_id($set['id']);


                    foreach ($set_cats as $s_cat) {
                        $c = new stdClass();

                        $c->id = (int)$s_cat['id'];
                        $c->name = $s_cat['name'];
                        $c->active = $s_cat['active'];
                        if (isset($s_cat['parent']) && $s_cat['parent'] != 0) $c->parent = (int)$s_cat['parent'];

                        $module_sets_catalog[$set['id']]['categories'][] = $c;
                        unset($c);
                    }


                    foreach ($set_modules as $s_mod) {
                        $m = new stdClass();

                        $m->id = (int)$s_mod['id'];
                        $m->category = (int)$s_mod['category'];
                        $m->icon = $s_mod['icon'];
                        $m->active = $s_mod['active'];


                        $pars = json_decode($s_mod['params'],true)['params'];

                        $m->params = $pars;
                        $pars = null;
                        if (!isset($m->params['variants'][0])) {
                            $errors[] = 'У модуля ' . $set['name'] . ' нет ни одного размера';
                        }

                        $module_sets_catalog[$set['id']]['items'][] = $m;
                        unset($m);
                    }

                    unset($set_cats);
                    unset($set_modules);
                }

            }



//            $facade_categories = array_non_empty_items($this->facades_model->get_active_categories());
            $facade_categories = $this->facades_model->get_categories();
            $fac_cat = array();
            foreach ($facade_categories as $cat){

                $c = new stdClass();

                $c->id = (int)$cat['id'];
                $c->name = $cat['name'];
                $c->active = $cat['active'];
                if(isset($cat['image']))$c->image = $cat['image'];
                if(isset($cat['description'])){
                    $c->description = $cat['description'];
                }
                if(isset($cat['parent'])) $c->parent = (int)$cat['parent'];

                $fac_cat[] = $c;
                unset($c);
            }
            unset($facade_categories);




            $facade_sets = $this->facades_model->get_all_items();
            $fs = array();
            foreach ($facade_sets as $cat){
                $c = array();



                $c_mats = json_decode($cat['materials']);

                if(!isset($c_mats)) {continue;}


                foreach ($c_mats as $key => $var) {

                    if($this->materials_model->check_category_active((int)$var)){
                        $c_mats[$key] = (int)$var;
                    } else {
                        unset($c_mats[$key]);
                    }
                }



                if($cat['data'] != null){

                    $json = json_decode($cat['data'], true);
                	$c = $json;
                    $c['active'] = $cat['active'];
                    $json = null;

                } else {
	                $c['id'] = (int)$cat['id'];
	                $c['name'] = $cat['name'];

	                $c['category'] = (int)$cat['category'];
	                $c['icon'] = $cat['icon'];
	                $c['materials'] = $c_mats;

	                $c['facades'] = new stdClass();

                    $json = json_decode($cat['full']);
	                $c['full'] = $json;
                    $json = null;



	                $c['window'] = json_decode($cat['window']);
	                $c['frame'] = json_decode($cat['frame']);
	                $c['radius'] = json_decode($cat['radius_full']);
	                $c['radius_window'] = json_decode($cat['radius_window']);
	                $c['radius_frame'] = json_decode($cat['radius_frame']);

	                if(isset($cat['parent'])) $c->parent = (int)$cat['parent'];
                }








                $fs[] = $c;
                unset($c);
            }
            unset($facade_sets);




            $templates = $this->templates_model->get_all_active();




            $tech_categories = $this->tech_model->get_categories();
            $tech_items = $this->tech_model->get_all_items();

            $tech_catalog = new stdClass();

            $tech_catalog->categories = array();
            $tech_catalog->items = array();

            foreach ($tech_categories as $cat){
                $c = new stdClass();

                $c->id = (int)$cat['id'];
                $c->name = $cat['name'];
                $c->active = $cat['active'];
                if(isset($cat['image']))$c->image = $cat['image'];
                if(isset($cat['parent']) && $cat['parent'] != 0) $c->parent = (int)$cat['parent'];

                $tech_catalog->categories[] = $c;
                unset($c);
            }

            foreach ($tech_items as $item){

	            if($item['model_data'] != null){
		            $it = json_decode($item['model_data']);
		            $it->active = $item['active'];
		            $tech_catalog->items[] = $it;
		            continue;
	            }

                $it = new stdClass();

                $it->id = (int)$item['id'];
                $it->name = $item['name'];
                $it->category = (int)$item['category'];
                $it->model = $item['model'];
                $it->icon = $item['icon'];
                $it->cabinet_group = $item['group'];
                $it->draggable = true;
                $it->material = json_decode($item['material']);
                $it->variants = json_decode($item['variants']);

                if($item['wall_panel'] == 1){
                    $it->wall_panel = true;
                } else {
                    $it->wall_panel = false;
                }

                if($item['drag_mode'] == null){
                    $it->drag_mode = 'common';
                } else {
                    $it->drag_mode = $item['drag_mode'];
                }


                if($item['sizes_available'] == null){
                    $it->sizes_available = 'true';
                } else {
                    if($item['sizes_available'] == 1){
                        $it->sizes_available = true;
                    } else {
                        $it->sizes_available = false;
                    }
                }

                $tech_catalog->items[] = $it;
                unset($it);
            }

            unset($tech_categories);
            unset($tech_items);




            $comms_categories = $this->comms_model->get_categories();
            $comms_items = $this->comms_model->get_all_active_items();

            $comms_catalog = new stdClass();

            $comms_catalog->categories = array();
            $comms_catalog->items = array();

            foreach ($comms_categories as $cat){



                $c = new stdClass();

                $c->id = (int)$cat['id'];
                $c->name = $cat['name'];
                $c->active = $cat['active'];
                if(isset($cat['image']))$c->image = $cat['image'];
                if(isset($cat['parent']) && $cat['parent'] != 0) $c->parent = (int)$cat['parent'];

                $comms_catalog->categories[] = $c;
                unset($c);
            }

            foreach ($comms_items as $item){

	            if($item['model_data'] != null){
		            $it = json_decode($item['model_data']);
                    $it->active = $item['active'];
		            $comms_catalog->items[] = $it;
		            continue;
	            }

                $it = new stdClass();

                $it->id = (int)$item['id'];
                $it->name = $item['name'];
                $it->category = (int)$item['category'];
                $it->model = $item['model'];
                $it->icon = $item['icon'];
                $it->cabinet_group = $item['group'];
                $it->draggable = true;
                $it->material = json_decode($item['material']);
                $it->variants = json_decode($item['variants']);

                if($item['wall_panel'] == 1){
                    $it->wall_panel = true;
                } else {
                    $it->wall_panel = false;
                }

                if($item['drag_mode'] == null){
                    $it->drag_mode = 'common';
                } else {
                    $it->drag_mode = $item['drag_mode'];
                }

                if($item['sizes_available'] == null){
                    $it->sizes_available = 'true';
                } else {
                    if($item['sizes_available'] == 1){
                        $it->sizes_available = true;
                    } else {
                        $it->sizes_available = false;
                    }
                }

                $comms_catalog->items[] = $it;
                unset($it);
            }

            unset($comms_categories);
            unset($comms_items);




            $interior_categories = $this->interior_model->get_categories();
            $interior_items = $this->interior_model->get_all_active_items();

            $interior_catalog = new stdClass();

            $interior_catalog->categories = array();
            $interior_catalog->items = array();

            foreach ($interior_categories as $cat){
                $c = new stdClass();

                $c->id = (int)$cat['id'];
                $c->name = $cat['name'];
                $c->active = $cat['active'];
                if(isset($cat['image']))$c->image = $cat['image'];
                if(isset($cat['parent']) && $cat['parent'] != 0) $c->parent = (int)$cat['parent'];

                $interior_catalog->categories[] = $c;
                unset($c);
            }

            foreach ($interior_items as $item){

            	if($item['model_data'] != null){
            		$it = json_decode($item['model_data']);
                    $it->active = $item['active'];
		            $interior_catalog->items[] = $it;
					continue;
	            }

                $it = new stdClass();

                $it->id = (int)$item['id'];
                $it->name = $item['name'];
                $it->code = $item['code'];
                $it->category = (int)$item['category'];
                $it->model = $item['model'];
                $it->icon = $item['icon'];
                $it->cabinet_group = $item['group'];
                $it->draggable = true;
                $it->material = json_decode($item['material']);
                $it->variants = json_decode($item['variants']);

                if($item['wall_panel'] == 1){
                    $it->wall_panel = true;
                } else {
                    $it->wall_panel = false;
                }

                if($item['drag_mode'] == null){
                    $it->drag_mode = 'common';
                } else {
                    $it->drag_mode = $item['drag_mode'];
                }

                if($item['sizes_available'] == null){
                    $it->sizes_available = 'true';
                } else {
                    if($item['sizes_available'] == 1){
                        $it->sizes_available = true;
                    } else {
                        $it->sizes_available = false;
                    }
                }


                $interior_catalog->items[] = $it;
                unset($it);
            }

            unset($interior_items);
            unset($interior_categories);




            $modules_categories = $this->modules_model->get_categories();
            $modules_items = $this->modules_model->get_all_active_items();

            $modules_catalog = new stdClass();

            $modules_catalog->categories = array();
            $modules_catalog->items = array();

            foreach ($modules_categories as $cat){
                $c = new stdClass();

                $c->id = (int)$cat['id'];
                $c->name = $cat['name'];
                $c->active = $cat['active'];
                if(isset($cat['image']))$c->image = $cat['image'];
                if(isset($cat['parent']) && $cat['parent'] != 0) $c->parent = (int)$cat['parent'];

                $modules_catalog->categories[] = $c;
                unset($c);
            }

            foreach ($modules_items as $item){
                $it = new stdClass();

                $it->id = (int)$item['id'];
                $it->active = (int)$item['active'];
                $it->category = (int)$item['category'];
                $it->icon = $item['icon'];
                $it->active = $item['active'];
                $it->template_id = (int)$item['template_id'];
                $it->params = json_decode($item['params'])->params;

                $modules_catalog->items[] = $it;
                unset($it);
            }
            unset($modules_categories);
            unset($modules_items);




//            $handles_categories = $this->handles_model->get_active_categories();
            $handles_categories = $this->handles_model->get_categories();
            $handles_items = $this->handles_model->get_all_active_items();

            $handles_catalog = new stdClass();

            $handles_catalog->categories = array();
            $handles_catalog->items = array();

            foreach ($handles_categories as $cat){
                $c = new stdClass();

                $c->id = (int)$cat['id'];
                $c->name = $cat['name'];
                $c->active = $cat['active'];
                if(isset($cat['image']))$c->image = $cat['image'];
                if(isset($cat['parent']) && $cat['parent'] != 0) $c->parent = (int)$cat['parent'];

                $handles_catalog->categories[] = $c;
                unset($c);
            }

            foreach ($handles_items as $item){
                $it = new stdClass();

                $it->id = (int)$item['id'];
                $it->category = (int)$item['category'];
                $it->name = $item['name'];
                $it->icon = $item['icon'];
                $it->model = $item['model'];
                $it->type = $item['type'];
                $it->material = json_decode($item['material']);
                $it->sizes = json_decode($item['variants']);

                $handles_catalog->items[] = $it;
                unset($it);
            }
            unset($handles_categories);
            unset($handles_items);

            $common_settings = '';


            if($constructor_settings['shop_mode'] == 1){

                $flag = 1;

                foreach ($kitchen_models_catalog as $km){

                    if($km->id == $constructor_settings['default_kitchen_model']){
                        $project_settings = $km;
                        $flag = 0;
                    }


                }

                if($flag == 1 && count($kitchen_models_catalog) > 0){
                    $project_settings = $kitchen_models_catalog[0];
                }



                $common_settings = $this->build_project_settings_common();




            } else {

                $constructor_settings['multiple_facades_mode'] = 0;

	            if(!$this->facades_model->check_active((int)$ps['selected_facade_model'])){
		            $errors[] = 'Не выбрана фрезеровка по умолчанию в разделе "Доступные декоры" или выбранная фрезеровка не активна';
		            echo json_encode( array('errors' => $errors), JSON_UNESCAPED_UNICODE|JSON_PRETTY_PRINT);
		            exit;
	            }

	            $selected_facade = $this->facades_model->get_item((int)$ps['selected_facade_model']);

	            if($selected_facade == null){
		            $errors[] = 'Не выбрана фрезеровка по умолчанию в разделе "Доступные декоры" или выбранная фрезеровка не активна';
		            echo json_encode( array('errors' => $errors), JSON_UNESCAPED_UNICODE|JSON_PRETTY_PRINT);
		            exit;
	            }

                $c_mats = json_decode($selected_facade['materials']);
                foreach ($c_mats as $key => $var) {

                    if($this->materials_model->check_category_active((int)$var)){
                        $c_mats[$key] = (int)$var;
                    } else {
                        unset($c_mats[$key]);
                    }
                }



                $project_settings = new stdClass();
                $project_settings->is_kitchen_model = 0;
                $project_settings->door_offset = 2;
                $project_settings->shelve_offset = 10;
                $project_settings->corpus_thickness = 16;
                $project_settings->tabletop_thickness = (int)$ps['tabletop_thickness'];
                $project_settings->bottom_modules_height = 720;
                $project_settings->bottom_as_top_facade_models = true;
                $project_settings->bottom_as_top_facade_materials = true;
                $project_settings->bottom_as_top_corpus_materials = true;
                $project_settings->cokol_as_corpus = (int)$ps['cokol_as_corpus'];;
                $project_settings->cokol_height = (int)$ps['cokol_height'];

                $project_settings->models = new stdClass();
                $project_settings->models->top = (int)$ps['selected_facade_model'];
                $project_settings->models->bottom = (int)$ps['selected_facade_model'];

                $tmp_facmod = $this->facades_model->get_item((int)$ps['selected_facade_model']);


                if($tmp_facmod['active'] != 1){
                    $errors[] = 'Не выбрана фрезеровка по умолчанию в разделе "Доступные декоры" или выбранная фрезеровка не активна';
	                echo json_encode( array('errors' => $errors), JSON_UNESCAPED_UNICODE|JSON_PRETTY_PRINT);
	                exit;
                }

                $project_settings->materials = new stdClass();
                $project_settings->materials->top = new stdClass();
                $project_settings->materials->top->facades = $c_mats;
                $project_settings->materials->top->corpus = ($ps['available_materials_corpus']);
                $project_settings->materials->top->back_wall_material = ($ps['available_materials_back_wall']);

                $project_settings->materials->bottom = new stdClass();
                $project_settings->materials->bottom->facades = $c_mats;
                $project_settings->materials->bottom->corpus = ($ps['available_materials_corpus']);
                $project_settings->materials->bottom->back_wall_material = ($ps['available_materials_back_wall']);


                $project_settings->materials->cokol = ($ps['available_materials_cokol']);

                if(isset($ps['available_materials_glass'])){
                    $project_settings->materials->glass = ($ps['available_materials_glass']);
                }
                $project_settings->materials->glass_shelves = ($ps['available_materials_glass_shelves']);


                $project_settings->materials->tabletop = ($ps['available_materials_tabletop']);
                $project_settings->materials->walls = ($ps['available_materials_walls']);
                $project_settings->materials->floor = ($ps['available_materials_floor']);
                $project_settings->materials->wall_panel = ($ps['available_materials_wallpanel']);

//                print_r($project_settings);


                $project_settings->selected_materials = new stdClass();
                $project_settings->selected_materials->top = new stdClass();
                $project_settings->selected_materials->top->facades = (int)$ps['selected_material_facade'];
                $project_settings->selected_materials->top->corpus = (int)$ps['selected_material_corpus'];

                $project_settings->selected_materials->bottom = new stdClass();
                $project_settings->selected_materials->bottom->facades = (int)$ps['selected_material_facade'];
                $project_settings->selected_materials->bottom->corpus = (int)$ps['selected_material_corpus'];

                $project_settings->selected_materials->cokol = (int)$ps['selected_material_cokol'];
                if(isset($ps['selected_material_glass'])) {
                    $project_settings->selected_materials->glass = (int)$ps['selected_material_glass'];
                }

                $project_settings->selected_materials->tabletop = (int)$ps['selected_material_tabletop'];
                $project_settings->selected_materials->walls = (int)$ps['selected_material_walls'];
                $project_settings->selected_materials->floor = (int)$ps['selected_material_floor'];

                $project_settings->handle = new stdClass();
                $project_settings->handle->orientation = $ps['handle_orientation'];
                $project_settings->handle->lockers_position = $ps['handle_lockers_position'];
                $project_settings->handle->selected_model = (int)$ps['handle_selected_model'];
                $project_settings->handle->preferable_size = (int)$ps['handle_preferable_size'];

                $handle_model = $this->handles_model->get_item((int)$ps['handle_selected_model']);

	            if($handle_model == 0){
		            $project_settings->handle->no_handle = 1;

                    $project_settings->handle->model = new stdClass();
                    $project_settings->handle->model->icon = '';
                    $project_settings->handle->model->model = '';

	            } else {
                    $h_mod = new stdClass();

                    $h_mod->id = (int)$handle_model['id'];
                    $h_mod->category = (int)$handle_model['category'];
                    $h_mod->name = $handle_model['name'];
                    $h_mod->icon = $handle_model['icon'];
                    $h_mod->model = $handle_model['model'];
                    $h_mod->type = $handle_model['type'];
                    $h_mod->size_index = (int)$ps['handle_preferable_size'];
                    $h_mod->material = json_decode($handle_model['material']);
                    $h_mod->sizes = json_decode($handle_model['variants']);

                    $project_settings->handle->model = $h_mod;
                }






                $project_settings->wall_panel = new stdClass();
                $project_settings->wall_panel->active = (boolean)$ps['wallpanel_active'];
                $project_settings->wall_panel->height = (int)$ps['wallpanel_height'];


                $project_settings->available_corpus_thickness = ($ps['available_corpus_thickness']);
                $project_settings->default_corpus_thickness = $ps['default_corpus_thickness'];

                $project_settings->available_shelves_thickness = ($ps['available_shelves_thickness']);
                $project_settings->default_shelves_thickness = $ps['default_shelves_thickness'];


                $project_settings->selected_cornice_model = $ps['selected_cornice_model'];


                if($this->config->item('username') == 'mebvesta@yandex.ru') $project_settings->selected_materials->pat = 1101;

//	            if($this->config->item('sub_account')) $this->config->set_item('sub_account', false);

            }

            $tabletop_plints = new stdClass();

//            $tabletop_plints->categories = array();
//            $tabletop_plints->items = array();

            $tabletop_plints->categories = $this->common_model->get_data_all_by_order('Tabletop_plinth_categories', 'ASC');
            $tabletop_plints->items = $this->common_model->get_data_all_by_order('Tabletop_plinth_items', 'ASC');
            foreach ($tabletop_plints->items as &$item){
                $item = json_decode($item['data'], true);
                unset($item['data']);
            }


            $cornice = new stdClass();
            $cornice->categories = array();
            $cornice->items = array();

            $cornice->categories = $this->common_model->get_data_all_by_order('Cornice_categories', 'ASC');
            $cornice->items = $this->common_model->get_data_all_by_order('Cornice_items', 'ASC');

            foreach ($cornice->items as &$item){
                $item = json_decode($item['data'], true);
                unset($item['data']);
            }




            $washes = new stdClass();

            $washes->categories = array();
            $washes->items = array();



            $washes->categories = $this->common_model->get_data_all_by_order('Washes_categories', 'ASC');
            $washes->items = $this->common_model->get_data_all_by_order('Washes_items', 'ASC');

            foreach ($washes->items as &$item){
                $item = json_decode($item['data'], true);
//                $item['category'] = json_decode($item['category'], true);
                unset($item['data']);
            }


            $tabletop = new stdClass();
            $tabletop->categories = array();
            $tabletop->items = array();

            $tabletop->categories = $this->common_model->get_data_all_by_order('Tabletop_categories', 'ASC');
            $tabletop->items = $this->common_model->get_data_all_by_order('Tabletop_items', 'ASC');

            foreach ($tabletop->items as &$item){
                $item = json_decode($item['data'], true);
                unset($item['data']);
            }

            $cokol = new stdClass();
            $cokol->categories = array();
            $cokol->items = array();

            $cokol->categories = $this->common_model->get_data_all_by_order('Cokol_categories', 'ASC');
            $cokol->items = $this->common_model->get_data_all_by_order('Cokol_items', 'ASC');

            foreach ($cokol->items as &$item){
                $item = json_decode($item['data'], true);
                unset($item['data']);
            }


            $catalogue = new stdClass();

            $catalogue->categories = array();
            $catalogue->items = array();

            $catalogue->categories = $this->common_model->get_data_all_by_order('Catalogue_categories', 'ASC');
            $catalogue->items = $this->common_model->get_data_all_by_order('Catalogue_items', 'ASC');

            foreach ($catalogue->items as &$item){
                $item['params'] = json_decode($item['data'], true);
                $item['category'] = json_decode($item['category'], true);
                unset($item['data']);
            }

            $bardesks = new stdClass();

            $bardesks->categories = array();
            $bardesks->items = array();

            $bardesks->categories = $this->common_model->get_data_all_by_order('Bardesks_categories', 'ASC');
            $bardesks->items = $this->common_model->get_data_all_by_order('Bardesks_items', 'ASC');

            foreach ($bardesks->items as &$item){
                $item['params'] = json_decode($item['data'], true);
                $item['category'] = json_decode($item['category'], true);
                unset($item['data']);
            }


            $model3d = new stdClass();

            $model3d->categories = array();
            $model3d->items = array();

            $model3d->categories = $this->common_model->get_data_all_by_order('Model3d_categories', 'ASC');
            $model3d->items = $this->common_model->get_data_all_by_order('Model3d_items', 'ASC');

            $mat_types = $this->common_model->get_data_all_by_order('Material_types_items', 'ASC');
            $mat_types_result = [];
            foreach ($mat_types as $type){
                $mat_types_result[$type['key']] = json_decode($type['data'], true);
            }


            foreach ($model3d->items as &$item){
                $item['params'] = json_decode($item['data'], true);
//                $item['category'] = json_decode($item['category'], true);
                unset($item['data']);
            }

            $lockers = new stdClass();
            $lockers->categories = array();
            $lockers->items = array();

            $lockers->items = $this->common_model->get_data_all_by_order('Lockers_items', 'ASC');

            foreach ($lockers->items as &$item){
                $item = json_decode($item['data'], true);
//                $item['category'] = json_decode($item['category'], true);
                unset($item['data']);
            }



            $params_blocks = $this->common_model->get_data_all_by_order('Params_blocks_items', 'ASC');

            foreach ($params_blocks as &$item){
                $item['data'] = json_decode($item['data'], true);
            }

            $backgrounds = $this->common_model->get_data_all_by_order('Background_items', 'ASC');

            foreach ($backgrounds as &$item){
                $item['params'] = json_decode($item['params'], true);
            }



            if( !$this->materials_model->check_mat_active($project_settings->selected_materials->top->facades)  ){

                if($constructor_settings['shop_mode'] == 1){
                    $errors[] = 'Не выбран материал фасадов в модели кухни по умолчанию или выбранный материал не активен';
                } else {
                    $errors[] = 'Не выбран материал фасадов в разделе "Доступные декоры" или выбранный материал не активен';
                }


            }

            if( !$this->materials_model->check_mat_active($project_settings->selected_materials->top->corpus)  ){

                if($constructor_settings['shop_mode'] == 1){
                    $errors[] = 'Не выбран материал корпуса в модели кухни или выбранный материал не активен';

                } else {
                    $errors[] = 'Не выбран материал корпуса в разделе "Доступные декоры" или выбранный материал не активен';

                }

            }

            if( !$this->materials_model->check_mat_active($project_settings->selected_materials->bottom->facades)  ){

                if($constructor_settings['shop_mode'] == 1){
                    $errors[] = 'Не выбран материал фасадов в модели кухни или выбранный материал не активен';

                } else {
                    $errors[] = 'Не выбран материал фасадов в разделе "Доступные декоры" или выбранный материал не активен';

                }

            }

            if( !$this->materials_model->check_mat_active($project_settings->selected_materials->bottom->corpus)  ){
                if($constructor_settings['shop_mode'] == 1){
                    $errors[] = 'Не выбран материал корпуса в модели кухни или выбранный материал не активен';

                } else {
                    $errors[] = 'Не выбран материал корпуса в разделе "Доступные декоры" или выбранный материал не активен';

                }

            }

            if( !$this->materials_model->check_mat_active($project_settings->selected_materials->cokol)  ){

                if($constructor_settings['shop_mode'] == 1){
                    $errors[] = 'Не выбран материал цоколя в модели кухни или выбранный материал не активен';

                } else {
                    $errors[] = 'Не выбран материал цоколя в разделе "Доступные декоры" или выбранный материал не активен. Также проверьте, если выставлена настройка "Цоколь в цвет корпуса" - в доступных категориях материалов цоколя должны быть доступны категории корпуса';

                }
            }

            if( !$this->materials_model->check_mat_active($project_settings->selected_materials->tabletop)  ){
                if($constructor_settings['shop_mode'] == 1){
                    $errors[] = 'Не выбран материал столешницы в модели кухни или выбранный материал не активен';
                } else {
                    $errors[] = 'Не выбран материал столешницы в разделе "Доступные декоры" или выбранный материал не активен';
                }
            }

            if( !$this->materials_model->check_mat_active($project_settings->selected_materials->walls)  ){
                if($constructor_settings['shop_mode'] == 1){
                    $errors[] = 'Не выбран материал стен в разделе "Доступные декоры" или выбранный материал не активен';
                } else {
                    $errors[] = 'Не выбран материал стен в разделе "Доступные декоры" или выбранный материал не активен';
                }
            }

            if( !$this->materials_model->check_mat_active($project_settings->selected_materials->floor)  ){
                if($constructor_settings['shop_mode'] == 1){
                    $errors[] = 'Не выбран материал пола в разделе "Доступные декоры" или выбранный материал не активен';
                } else {
                    $errors[] = 'Не выбран материал пола в разделе "Доступные декоры" или выбранный материал не активен';
                }
            }



            if( count($errors) > 0 ){
                echo json_encode( array('errors' => $errors), JSON_UNESCAPED_UNICODE|JSON_PRETTY_PRINT);
            } else {


	            if($this->config->item('ini')['language']['language'] !== 'default'){

		            $constructor_settings['default_language'] = 'custom';

		            $this->load->model('languages_model');

		            $lang_front = get_default_lang_front();




		            $custom_lang = json_decode($this->languages_model->get_front($this->config->item('ini')['language']['language']));
		            foreach ($lang_front as $key=>$value){
			            if(isset($custom_lang->$key)) {
				            if(!empty($custom_lang->$key)) $lang_front[$key] = $custom_lang->$key;
			            }
		            }


		            write_file(dirname(FCPATH).'/data/language.json', json_encode($lang_front));

	            } else {
		            $constructor_settings['default_language'] = $this->settings_model->get_settings()['default_language'];
	            }



	            if(!$this->config->item('sub_account')) $this->config->set_item('sub_account', false);
	            write_file(dirname(FCPATH).'/data/kitchen_templates.json', json_encode($templates));

                if($this->config->item('sub_account') != true){
                    if(basename(dirname(FCPATH)) == 'antarescompany2.ru'){

                        write_file(dirname(FCPATH).'/data/constructor_settings.json', json_encode($constructor_settings));


                    } else {


                        if(file_exists(dirname(FCPATH).'/data/module_sets')) removeDirectory(dirname(FCPATH).'/data/module_sets');

                        mkdir(dirname(FCPATH).'/data/module_sets');

                        foreach ($module_sets_catalog as $id=>$set){
                            file_put_contents(dirname(FCPATH).'/data/module_sets/' . $id . '.json', json_encode($set, JSON_UNESCAPED_UNICODE));
                        }


                        write_file(dirname(FCPATH).'/data/prices.json', $prices['data']);
                        write_file(dirname(FCPATH).'/data/constructor_settings.json', json_encode($constructor_settings));
                        write_file(dirname(FCPATH).'/data/materials.json', json_encode($mat_result));
                        write_file(dirname(FCPATH).'/data/glass_materials.json', json_encode($glass_result));
                        write_file(dirname(FCPATH).'/data/params_blocks.json', json_encode($params_blocks));
                        write_file(dirname(FCPATH).'/data/backgrounds.json', json_encode($backgrounds));


                        write_file(dirname(FCPATH).'/data/kitchen_models.json', json_encode($kitchen_models_catalog, JSON_UNESCAPED_UNICODE));


                        write_file(dirname(FCPATH).'/data/facade_categories.json', json_encode($fac_cat));
                        write_file(dirname(FCPATH).'/data/facade_sets.json', json_encode($fs));
//                    write_file(dirname(FCPATH).'/data/kitchen_templates.json', json_encode($templates));
                        write_file(dirname(FCPATH).'/data/tech_catalog.json', json_encode($tech_catalog));
                        write_file(dirname(FCPATH).'/data/comms_catalog.json', json_encode($comms_catalog));
                        write_file(dirname(FCPATH).'/data/interior_catalog.json', json_encode($interior_catalog));


                        write_file(dirname(FCPATH).'/data/modules.json', json_encode($modules_catalog));

                        if (strpos($this->config->item('const_path'), '/clients/dev/') !== false) {
                            write_file($_SERVER['DOCUMENT_ROOT'] . '/common_assets/data/modules_common.json', json_encode($modules_catalog));
                        }

                        write_file(dirname(FCPATH).'/data/catalog.json', json_encode($catalogue));
                        write_file(dirname(FCPATH).'/data/bardesks.json', json_encode($bardesks));
                        write_file(dirname(FCPATH).'/data/cornice.json', json_encode($cornice));
                        write_file(dirname(FCPATH).'/data/tabletop.json', json_encode($tabletop));
                        write_file(dirname(FCPATH).'/data/cokol.json', json_encode($cokol));
                        write_file(dirname(FCPATH).'/data/washes.json', json_encode($washes));
                        write_file(dirname(FCPATH).'/data/models3d.json', json_encode($model3d));
                        write_file(dirname(FCPATH).'/data/lockers.json', json_encode($lockers));
                        write_file(dirname(FCPATH).'/data/mat_types.json', json_encode($mat_types_result));


                        write_file(dirname(FCPATH).'/data/handles.json', json_encode($handles_catalog));
                        write_file(dirname(FCPATH).'/data/project_settings.json', json_encode($project_settings));
                        write_file(dirname(FCPATH).'/data/project_settings_common.json', json_encode($common_settings));

                        write_file(dirname(FCPATH).'/data/tabletop_plints.json', json_encode($tabletop_plints, JSON_UNESCAPED_UNICODE));

                        if(!$this->config->item('sub_accounts')) $this->config->set_item('sub_accounts', array());



                        if($this->config->item('sub_accounts')[0] != ''){
                            if( count($this->config->item('sub_accounts')) > 0 ){

                                $sub_kitchen_models_result = array();
                                foreach ($kitchen_models_catalog as $item){
                                    $item->icon = $this->config->item('const_path') . $item->icon;

                                    if($item->handle->no_handle !=1){
                                        if (strpos($item->handle->model->icon, 'common_assets') === false) {
                                            $item->handle->model->icon = $this->config->item( 'const_path' ) . $item->handle->model->icon;
                                        }
                                        if (strpos($item->handle->model->model, 'common_assets') === false) {
                                            $item->handle->model->model = $this->config->item('const_path') . $item->handle->model->model;
                                        }

                                        if( isset($item->handle->model->material->params->map) ){
                                            if (strpos($item->handle->model->material->params->map, 'common_assets') === false) {
                                                $item->handle->model->material->params->map = $this->config->item( 'const_path' ) . $item->handle->model->material->params->map;
                                            }
                                        }
                                    }
//                                    foreach ($item->available_modules->items as $module){
//                                        if (strpos($module->icon, 'common_assets') === false) {
//                                            $module->icon = $this->config->item('const_path') . $module->icon;
//                                        }
//                                    }
                                    if( isset($item->facade_sets) ){
                                        foreach ($item->facade_sets as $facade){


                                            if(isset($facade->types)){

                                                if (strpos($facade->icon, 'common_assets') === false) {
                                                    $facade->icon = $this->config->item('const_path') . $facade->icon;
                                                }

                                                foreach ($facade->types as $type){

                                                    if (strpos($type->icon, 'common_assets') === false) {
                                                        $type->icon = $this->config->item('const_path') . $type->icon;
                                                    }

                                                    foreach ($type->items as $size){

                                                        if (strpos($size->model, 'common_assets') === false) {
                                                            $size->model = $this->config->item('const_path') . $size->model;
                                                        }

                                                    }
                                                }

                                            } else {
                                                if (strpos($facade->icon, 'common_assets') === false) {
                                                    $facade->icon = $this->config->item('const_path') . $facade->icon;
                                                }


                                                if($facade->full != null){
                                                    foreach ($facade->full as $model){
                                                        if (strpos($model->model, 'common_assets') === false) {
                                                            $model->model = $this->config->item('const_path') . $model->model;
                                                        }
                                                    }
                                                }

                                                if($facade->window != null){
                                                    foreach ($facade->window as $model){
                                                        if (strpos($model->model, 'common_assets') === false) {
                                                            $model->model = $this->config->item('const_path') . $model->model;
                                                        }
                                                    }
                                                }

                                                if($facade->frame != null){
                                                    foreach ($facade->frame as $model){
                                                        if (strpos($model->model, 'common_assets') === false) {
                                                            $model->model = $this->config->item('const_path') . $model->model;
                                                        }
                                                    }
                                                }

                                                if($facade->radius != null){

                                                    foreach ($facade->radius as $model){
                                                        if (strpos($model->model, 'common_assets') === false) {
                                                            $model->model = $this->config->item('const_path') . $model->model;
                                                        }
                                                    }
                                                }

                                                if($facade->radius_window != null){
                                                    foreach ($facade->radius_window as $model){
                                                        if (strpos($model->model, 'common_assets') === false) {
                                                            $model->model = $this->config->item('const_path') . $model->model;
                                                        }
                                                    }
                                                }

                                                if($facade->radius_frame != null){
                                                    foreach ($facade->radius_frame as $model){
                                                        if (strpos($model->model, 'common_assets') === false) {
                                                            $model->model = $this->config->item('const_path') . $model->model;
                                                        }
                                                    }
                                                }
                                            }




                                        }
                                    }

                                    $sub_kitchen_models_result[] = $item;
                                }
                                unset($kitchen_models_catalog);


                                $sub_modules_sets_result = array();
                                foreach ($module_sets_catalog as $id=>$item){
                                    foreach ($item['items'] as $module){
                                        if (strpos($module->icon, 'common_assets') === false) {
                                            $module->icon = $this->config->item('const_path') . $module->icon;
                                        }
                                    }
                                    $sub_modules_sets_result[$id] = $item;
                                }
                                unset($module_sets_catalog);

                                $sub_mat_result = new stdClass();
                                $sub_mat_result->items = array();
                                $sub_mat_result->categories = $mat_result->categories;
                                foreach ($mat_result->items as $item){
                                    if( isset($item->params->map) ){
                                        if (strpos($item->params->map, 'common_assets') === false) {
                                            $item->params->map = $this->config->item('const_path') . $item->params->map;
                                        }
                                    }
                                    if( isset($item->params->normalMap) ){
                                        if (strpos($item->params->normalMap, 'common_assets') === false) {
                                            $item->params->normalMap = $this->config->item('const_path') . $item->params->normalMap;
                                        }
                                    }
                                    if( isset($item->params->alphaMap) ){
                                        if (strpos($item->params->alphaMap, 'common_assets') === false) {
                                            $item->params->alphaMap = $this->config->item('const_path') . $item->params->alphaMap;
                                        }
                                    }
                                    if( isset($item->params->roughnessMap) ){
                                        if (strpos($item->params->roughnessMap, 'common_assets') === false) {
                                            $item->params->roughnessMap = $this->config->item('const_path') . $item->params->roughnessMap;
                                        }
                                    }
                                    $sub_mat_result->items[] = $item;
                                }
                                unset($mat_result);

                                $sub_glass_result = new stdClass();
                                $sub_glass_result->items = array();
                                $sub_glass_result->categories = $glass_result->categories;
                                foreach ($glass_result->items as $item){
                                    if( isset($item->params->map) ){
                                        if (strpos($item->params->map, 'common_assets') === false) {
                                            $item->params->map = $this->config->item( 'const_path' ) . $item->params->map;
                                        }
                                    }
                                    $sub_glass_result->items[] = $item;
                                }
                                unset($glass_result);

                                $sub_facade_sets_result = array();

                                foreach ($fs as $item){

                                    if(isset($item['types'])){
                                        if (strpos($item['icon'], 'common_assets') === false) {
                                            $item['icon'] = $this->config->item('const_path') . $item['icon'];
                                        }

                                        foreach ($item['types'] as &$type){

                                            if (strpos($type['icon'], 'common_assets') === false) {
                                                $type['icon'] = $this->config->item('const_path') . $type['icon'];
                                            }

                                            foreach ($type['items'] as &$size){

                                                if (strpos($size['model'], 'common_assets') === false) {
                                                    $size['model'] = $this->config->item('const_path') . $size['model'];
                                                }

                                            }
                                        }

                                        $sub_facade_sets_result[] = $item;

                                    } else {
                                        if (strpos($item['icon'], 'common_assets') === false) {
                                            $item['icon'] = $this->config->item('const_path') . $item['icon'];
                                        }


                                        if(isset($item['full'])){
                                            foreach ($item['full'] as $model){

                                                if(isset($model->model)){
                                                    if (strpos($model->model, 'common_assets') === false) {
                                                        $model->model = $this->config->item('const_path') . $model->model;
                                                    }
                                                }


                                            }
                                        }

                                        if(isset($item['window'])){
                                            foreach ($item['window'] as $model){
                                                if(isset($model->model)) {
                                                    if (strpos($model->model, 'common_assets') === false) {
                                                        $model->model = $this->config->item('const_path') . $model->model;
                                                    }
                                                }
                                            }
                                        }

                                        if(isset($item['frame'])){
                                            foreach ($item['frame'] as $model){
                                                if (strpos($model->model, 'common_assets') === false) {
                                                    $model->model = $this->config->item( 'const_path' ) . $model->model;
                                                }
                                            }
                                        }

                                        if(isset($item['radius'])){
                                            foreach ($item['radius'] as $model){
                                                if (strpos($model->model, 'common_assets') === false) {
                                                    $model->model = $this->config->item('const_path') . $model->model;
                                                }
                                            }
                                        }

                                        if(isset($item['radius_window'])){
                                            foreach ($item['radius_window'] as $model){
                                                if (strpos($model->model, 'common_assets') === false) {
                                                    $model->model = $this->config->item('const_path') . $model->model;
                                                }
                                            }
                                        }

                                        if(isset($item['radius_frame'])){
                                            foreach ($item['radius_frame'] as $model){
                                                if (strpos($model->model, 'common_assets') === false) {
                                                    $model->model = $this->config->item('const_path') . $model->model;
                                                }
                                            }
                                        }


                                        $sub_facade_sets_result[] = $item;
                                    }


                                }
                                unset($fs);


                                $sub_cornice_result = new StdClass();
                                $sub_cornice_result->categories = $cornice->categories;
                                $sub_cornice_result->items = array();
                                foreach ($cornice->items as $item){
                                    $sub_cornice_result->items[] = $item;
                                }
                                unset($cornice);

                                $sub_tp_result = new StdClass();
                                $sub_tp_result->categories = $tabletop_plints->categories;
                                $sub_tp_result->items = array();
                                foreach ($tabletop_plints->items as $item){
                                    $sub_tp_result->items[] = $item;
                                }
                                unset($tabletop_plints);



                                $sub_washes_result = new StdClass();
                                $sub_washes_result->categories = $washes->categories;
                                $sub_washes_result->items = array();
                                foreach ($washes->items as $item){
                                    if (strpos($item['icon'], 'common_assets') === false) {
                                        $item['icon'] = $this->config->item('const_path') . $item['icon'];
                                    }
                                    if (strpos($item['model'], 'common_assets') === false) {
                                        $item['model'] = $this->config->item('const_path') . $item['model'];
                                    }
                                    $sub_washes_result->items[] = $item;

                                }
                                unset($washes);

                                $sub_tabletop_result = new StdClass();
                                $sub_tabletop_result->categories = $tabletop->categories;
                                $sub_tabletop_result->items = array();
                                foreach ($tabletop->items as $item){
                                    $sub_tabletop_result->items[] = $item;
                                }
                                unset($tabletop);

                                $sub_kitchen_templates_result = array();
                                foreach ($templates as $item){
                                    $item['icon'] = $this->config->item('const_path') . $item['icon'];
                                    $item['file'] = $this->config->item('const_path') . $item['file'];
                                    $sub_kitchen_templates_result[] = $item;
                                }
                                unset($templates);

                                $sub_tech_catalog_result = new stdClass();
                                $sub_tech_catalog_result->items = array();
                                $sub_tech_catalog_result->categories = $tech_catalog->categories;
                                foreach ($tech_catalog->items as $item){

                                    if (strpos($item->icon, 'common_assets') === false) {
                                        $item->icon = $this->config->item('const_path') . $item->icon;
                                    }
                                    if(isset($item->model)){
                                        if (strpos($item->model, 'common_assets') === false) {
                                            $item->model = $this->config->item('const_path') . $item->model;
                                        }
                                    }

                                    if( isset($item->material->params->map) ){
                                        if (strpos($item->material->params->map, 'common_assets') === false) {
                                            $item->material->params->map = $this->config->item('const_path') . $item->material->params->map;
                                        }
                                    }

                                    if(isset($item->variants)){
                                        foreach ($item->variants as $var){
                                            if(isset($var->model) && $var->model != ''){
                                                if (strpos($var->model, 'common_assets') === false) {
                                                    $var->model = $this->config->item('const_path') . $var->model;
                                                }
                                            }
                                        }
                                    }



                                    $sub_tech_catalog_result->items[] = $item;
                                }
                                unset($tech_catalog);

                                $sub_comms_catalog_result = new stdClass();
                                $sub_comms_catalog_result->items = array();
                                $sub_comms_catalog_result->categories = $comms_catalog->categories;
                                foreach ($comms_catalog->items as $item){

                                    if (strpos($item->icon, 'common_assets') === false) {
                                        $item->icon = $this->config->item('const_path') . $item->icon;
                                    }
                                    if( isset($item->model) ) {
                                        if (strpos($item->model, 'common_assets') === false) {
                                            $item->model = $this->config->item('const_path') . $item->model;
                                        }
                                    }
                                    if( isset($item->material->params->map) ){
                                        if (strpos($item->material->params->map, 'common_assets') === false) {
                                            $item->material->params->map = $this->config->item('const_path') . $item->material->params->map;
                                        }
                                    }

                                    if(isset($item->variants)){
                                        foreach ($item->variants as $var){
                                            if(isset($var->model) && $var->model != ''){
                                                if (strpos($var->model, 'common_assets') === false) {
                                                    $var->model = $this->config->item('const_path') . $var->model;
                                                }
                                            }
                                        }
                                    }

                                    $sub_comms_catalog_result->items[] = $item;
                                }
                                unset($comms_catalog);

                                $sub_interior_catalog_result = new stdClass();
                                $sub_interior_catalog_result->items = array();
                                $sub_interior_catalog_result->categories = $interior_catalog->categories;
                                foreach ($interior_catalog->items as $item){

                                    if (strpos($item->icon, 'common_assets') === false) {
                                        $item->icon = $this->config->item('const_path') . $item->icon;
                                    }

                                    if(isset($item->model)){
                                        if (strpos($item->model, 'common_assets') === false) {
                                            $item->model = $this->config->item('const_path') . $item->model;
                                        }
                                    }

                                    if( isset($item->material->params->map) ){
                                        if (strpos($item->material->params->map, 'common_assets') === false) {
                                            $item->material->params->map = $this->config->item('const_path') . $item->material->params->map;
                                        }
                                    }

                                    if(isset($item->variants)){
                                        foreach ($item->variants as $var){
                                            if(isset($var->model) && $var->model != ''){
                                                if (strpos($var->model, 'common_assets') === false) {
                                                    $var->model = $this->config->item('const_path') . $var->model;
                                                }
                                            }
                                        }
                                    }

                                    $sub_interior_catalog_result->items[] = $item;
                                }
                                unset($interior_catalog);

                                $sub_modules_result = new stdClass();
                                $sub_modules_result->items = array();
                                $sub_modules_result->categories = $modules_catalog->categories;
                                foreach ($modules_catalog->items as $item){
                                    if (strpos($item->icon, 'common_assets') === false) {
                                        $item->icon = $this->config->item('const_path') . $item->icon;
                                    }
                                    $sub_modules_result->items[] = $item;
                                }
                                unset($modules_catalog);

                                $sub_handles_result = new stdClass();
                                $sub_handles_result->items = array();
                                $sub_handles_result->categories = $handles_catalog->categories;
                                foreach ($handles_catalog->items as $item){
                                    if (strpos($item->icon, 'common_assets') === false) {
                                        $item->icon = $this->config->item('const_path') . $item->icon;
                                    }
                                    if (strpos($item->model, 'common_assets') === false) {
                                        $item->model = $this->config->item('const_path') . $item->model;
                                    }
                                    if( isset($item->material->params->map) ){
                                        if (strpos($item->material->params->map, 'common_assets') === false) {
                                            $item->material->params->map = $this->config->item('const_path') . $item->material->params->map;
                                        }
                                    }
                                    $sub_handles_result->items[] = $item;
                                }

                                $sub_project_settings = $project_settings;

                                if($constructor_settings['shop_mode'] == 0) {
                                    if (strpos($sub_project_settings->handle->model->icon, 'common_assets') === false) {
                                        $sub_project_settings->handle->model->icon = $this->config->item( 'const_path' ) . $sub_project_settings->handle->model->icon;
                                    }
                                    if (strpos($sub_project_settings->handle->model->model, 'common_assets') === false) {
                                        $sub_project_settings->handle->model->model = $this->config->item( 'const_path' ) . $sub_project_settings->handle->model->model;
                                    }
                                    if (isset($sub_project_settings->handle->model->material->params->map)){
                                        if (strpos($sub_project_settings->handle->model->material->params->map, 'common_assets') === false) {
                                            $sub_project_settings->handle->model->material->params->map = $this->config->item( 'const_path' ) . $sub_project_settings->handle->model->material->params->map;
                                        }
                                    }

                                }


                                foreach ($this->config->item('sub_accounts') as $sub_acc){



                                    if(!file_exists(dirname(dirname(FCPATH)) . '/' . $sub_acc)) continue;
                                    if($sub_acc == null) continue;


                                    $tmp = file_get_contents(dirname(dirname(FCPATH)) . '/' . $sub_acc.'/data/constructor_settings.json');
                                    $tmp = json_decode($tmp, true);


                                    $sub_constructor_settings = $constructor_settings;



                                    $sub_constructor_settings['custom_order_url'] = $tmp['custom_order_url'];
                                    if(isset($tmp['price_modificator'])){
                                        $sub_constructor_settings['price_modificator'] = $tmp['price_modificator'];
                                    } else {
                                        $sub_constructor_settings['price_modificator'] = 1;
                                    }

                                    if(isset($tmp['price_modificator_acc'])){
                                        $sub_constructor_settings['price_modificator_acc'] = $tmp['price_modificator_acc'];
                                    } else {
                                        $sub_constructor_settings['price_modificator_acc'] = 1;
                                    }

                                    if(isset($tmp['price_modificator_furni'])){
                                        $sub_constructor_settings['price_modificator_furni'] = $tmp['price_modificator_furni'];
                                    } else {
                                        $sub_constructor_settings['price_modificator_furni'] = 1;
                                    }

                                    if(isset($tmp['default_language'])){
                                        $sub_constructor_settings['default_language'] = $tmp['default_language'];
                                    }



                                    if(isset($tmp['pdf_use_password'])) $sub_constructor_settings['pdf_use_password'] = $tmp['pdf_use_password'];
                                    if(isset($tmp['pdf_password'])) $sub_constructor_settings['pdf_password'] = $tmp['pdf_password'];
                                    if(isset($tmp['email'])) $sub_constructor_settings['email'] = $tmp['email'];





                                    foreach ($sub_kitchen_models_result as $it){

                                        if(isset($it->prices_data)){
                                            foreach ($it->prices_data as $fac_dat){
                                                foreach ($fac_dat['modules'] as $fac_dat_mod){
                                                    foreach ($fac_dat_mod['variants'] as $fac_data_mod_vars){
//                                                    $fac_data_mod_vars->price = intval($fac_data_mod_vars->price) * $sub_constructor_settings['price_modificator'];
                                                        $fac_data_mod_vars['price'] = intval($fac_data_mod_vars['price']);
                                                    }
                                                }
                                            }
                                        }

                                    }


                                    if($sub_acc === 'antarescompany2.ru'){
                                        if(file_exists(dirname(dirname(FCPATH)) . '/' . $sub_acc.'/data/module_sets')){
                                            removeDirectory(dirname(dirname(FCPATH)) . '/' . $sub_acc.'/data/module_sets');
                                        }

                                        mkdir(dirname(dirname(FCPATH)) . '/' . $sub_acc.'/data/module_sets');

                                        $ant2_sub_modules_sets_result = $sub_modules_sets_result;

                                        foreach ($ant2_sub_modules_sets_result as $id=>$set){

                                            if(file_exists(dirname(dirname(FCPATH)) . '/' . $sub_acc.'/data/modules_sets_active/' . $id . '.json')){
                                                $visibility_hash = json_decode(file_get_contents(dirname(dirname(FCPATH)) . '/' . $sub_acc.'/data/modules_sets_active/' . $id . '.json'), true);

                                                foreach ($set['items'] as $mod){
                                                    if(isset($visibility_hash[$mod->id])) $mod->active = $visibility_hash[$mod->id];
                                                }
                                            }

                                            file_put_contents(dirname(dirname(FCPATH)) . '/' . $sub_acc.'/data/module_sets/' . $id . '.json', json_encode($set));
                                        }
                                        file_put_contents(dirname(dirname(FCPATH)) . '/' . $sub_acc.'/data/sets.json', json_encode($module_sets));

                                        write_file(dirname(dirname(FCPATH)) . '/' . $sub_acc.'/data/prices.json', $prices['data']);
                                        write_file(dirname(dirname(FCPATH)) . '/' . $sub_acc.'/data/cornice.json', json_encode($sub_cornice_result));
                                        write_file(dirname(dirname(FCPATH)) . '/' . $sub_acc.'/data/tabletop.json', json_encode($sub_tabletop_result));
                                        file_put_contents(dirname(dirname(FCPATH)) . '/' . $sub_acc.'/data/tabletop_plints.json', json_encode($sub_tp_result));
                                        write_file(dirname(dirname(FCPATH)) . '/' . $sub_acc.'/data/materials.json', json_encode($sub_mat_result));
                                        write_file(dirname(dirname(FCPATH)) . '/' . $sub_acc.'/data/glass_materials.json', json_encode($sub_glass_result));
                                        write_file(dirname(dirname(FCPATH)) . '/' . $sub_acc.'/data/facade_categories.json', json_encode($fac_cat));
                                        write_file(dirname(dirname(FCPATH)) . '/' . $sub_acc.'/data/facade_sets.json', json_encode($sub_facade_sets_result));
                                        write_file(dirname(dirname(FCPATH)) . '/' . $sub_acc.'/data/tech_catalog.json', json_encode($sub_tech_catalog_result));
                                        write_file(dirname(dirname(FCPATH)) . '/' . $sub_acc.'/data/comms_catalog.json', json_encode($sub_comms_catalog_result));
                                        write_file(dirname(dirname(FCPATH)) . '/' . $sub_acc.'/data/interior_catalog.json', json_encode($sub_interior_catalog_result));
                                        write_file(dirname(dirname(FCPATH)) . '/' . $sub_acc.'/data/handles.json', json_encode($handles_catalog));
                                        write_file(dirname(dirname(FCPATH)) . '/' . $sub_acc.'/data/project_settings.json', json_encode($sub_project_settings));
                                        write_file(dirname(dirname(FCPATH)) . '/' . $sub_acc.'/data/project_settings_common.json', json_encode($common_settings));
                                        write_file(dirname(dirname(FCPATH)) . '/' . $sub_acc.'/data/modules.json', json_encode($sub_modules_result));
                                        write_file(dirname(dirname(FCPATH)) . '/' . $sub_acc.'/data/washes.json', json_encode($sub_washes_result));


                                        write_file(dirname(dirname(FCPATH)) . '/' . $sub_acc.'/data/kitchen_models.json', json_encode($sub_kitchen_models_result));



                                    } else {

                                        if(file_exists(dirname(dirname(FCPATH)) . '/' . $sub_acc.'/data/module_sets')){
                                            removeDirectory(dirname(dirname(FCPATH)) . '/' . $sub_acc.'/data/module_sets');
                                        }

                                        mkdir(dirname(dirname(FCPATH)) . '/' . $sub_acc.'/data/module_sets');

                                        foreach ($sub_modules_sets_result as $id=>$set){
                                            file_put_contents(dirname(dirname(FCPATH)) . '/' . $sub_acc.'/data/module_sets/' . $id . '.json', json_encode($set));
                                        }

                                        write_file(dirname(dirname(FCPATH)) . '/' . $sub_acc.'/data/prices.json', $prices['data']);
                                        write_file(dirname(dirname(FCPATH)) . '/' . $sub_acc.'/data/cornice.json', json_encode($sub_cornice_result));
                                        write_file(dirname(dirname(FCPATH)) . '/' . $sub_acc.'/data/tabletop.json', json_encode($sub_tabletop_result));
                                        file_put_contents(dirname(dirname(FCPATH)) . '/' . $sub_acc.'/data/tabletop_plints.json', json_encode($sub_tp_result));
                                        write_file(dirname(dirname(FCPATH)) . '/' . $sub_acc.'/data/constructor_settings.json', json_encode($sub_constructor_settings));
                                        write_file(dirname(dirname(FCPATH)) . '/' . $sub_acc.'/data/materials.json', json_encode($sub_mat_result));
                                        write_file(dirname(dirname(FCPATH)) . '/' . $sub_acc.'/data/glass_materials.json', json_encode($sub_glass_result));
                                        write_file(dirname(dirname(FCPATH)) . '/' . $sub_acc.'/data/kitchen_models.json', json_encode($sub_kitchen_models_result));



                                        write_file(dirname(dirname(FCPATH)) . '/' . $sub_acc.'/data/facade_categories.json', json_encode($fac_cat));
                                        write_file(dirname(dirname(FCPATH)) . '/' . $sub_acc.'/data/facade_sets.json', json_encode($sub_facade_sets_result));

                                        write_file(dirname(dirname(FCPATH)) . '/' . $sub_acc.'/data/tech_catalog.json', json_encode($sub_tech_catalog_result));
                                        write_file(dirname(dirname(FCPATH)) . '/' . $sub_acc.'/data/comms_catalog.json', json_encode($sub_comms_catalog_result));
                                        write_file(dirname(dirname(FCPATH)) . '/' . $sub_acc.'/data/interior_catalog.json', json_encode($sub_interior_catalog_result));
                                        write_file(dirname(dirname(FCPATH)) . '/' . $sub_acc.'/data/modules.json', json_encode($sub_modules_result));
                                        write_file(dirname(dirname(FCPATH)) . '/' . $sub_acc.'/data/handles.json', json_encode($handles_catalog));
                                        write_file(dirname(dirname(FCPATH)) . '/' . $sub_acc.'/data/project_settings.json', json_encode($sub_project_settings));
                                        write_file(dirname(dirname(FCPATH)) . '/' . $sub_acc.'/data/project_settings_common.json', json_encode($common_settings));
                                        write_file(dirname(dirname(FCPATH)) . '/' . $sub_acc.'/data/washes.json', json_encode($sub_washes_result));
                                    }
                                }
                            }
                        }
                    }
                } else {



                    $tmp = file_get_contents(dirname(FCPATH).'/data/constructor_settings.json');
                    $tmp = json_decode($tmp, true);
                    $tmp['custom_order_url'] = $constructor_settings['custom_order_url'];
                    $tmp['start_with_project_select'] = $constructor_settings['start_with_project_select'];
                    $tmp['price_modificator'] = $constructor_settings['price_modificator'];
                    $tmp['price_modificator_acc'] = $constructor_settings['price_modificator_acc'];
                    $tmp['price_modificator_furni'] = $constructor_settings['price_modificator_furni'];
                    $tmp['pdf_use_password'] = $constructor_settings['pdf_use_password'];
                    $tmp['pdf_password'] = $constructor_settings['pdf_password'];
                    $tmp['email'] = $constructor_settings['email'];

	                if($this->config->item('ini')['language']['language'] !== 'default'){
		                $tmp['default_language'] = 'custom';
	                } else {
                        $tmp['default_language'] = $this->settings_model->get_settings()['default_language'];
	                }

                    write_file(dirname(FCPATH).'/data/constructor_settings.json', json_encode($tmp));
	                if($this->config->item('username') === 'info@discontmebel.com') {
		                write_file( dirname( FCPATH ) . '/data/kitchen_templates.json', json_encode( $templates ) );
	                }

                }





                $this->save_accs();


                echo json_encode( array('success' => 'Настройки успешно сохранены') );
            }


//            echo memory_get_peak_usage(true);
        }

    }


    private function build_project_settings_common(){


        $ps = $this->project_settings_model->get_settings();

        if(isset($ps['settings'])){
            $ps = json_decode($ps['settings'], true);
        } else {
            $ps['available_materials_back_wall'] = json_decode($ps['available_materials_back_wall'], true);
            $ps['available_corpus_thickness'] = json_decode($ps['available_corpus_thickness'], true);
        }
        if(!isset($ps['available_materials_glass_shelves'])) $ps['available_materials_glass_shelves'] = [];
        if(!isset($ps['selected_cornice_model'])) $ps['selected_cornice_model'] = 0;
        if(!isset($ps['available_shelves_thickness'])) $ps['available_shelves_thickness'] = [16];
        if(!isset($ps['default_shelves_thickness'])) $ps['default_shelves_thickness'] = 16;

        $selected_facade = $this->facades_model->get_item((int)$ps['selected_facade_model']);
        if(!$selected_facade){
            $selected_facade = $this->facades_model->get_first_active_item();
            $ps['selected_facade_model'] = $selected_facade['id'];
        }



        $c_mats = json_decode($selected_facade['materials']);
        foreach ($c_mats as $key => $var) {
            if($this->materials_model->check_category_active((int)$var)){
                $c_mats[$key] = (int)$var;
            } else {
                unset($c_mats[$key]);
            }
        }





        $project_settings = new stdClass();
        $project_settings->is_kitchen_model = 0;
        $project_settings->door_offset = 2;
        $project_settings->shelve_offset = 10;
        $project_settings->corpus_thickness = 16;
        $project_settings->tabletop_thickness = (int)$ps['tabletop_thickness'];
        $project_settings->bottom_modules_height = 720;
        $project_settings->bottom_as_top_facade_models = true;
        $project_settings->bottom_as_top_facade_materials = true;
        $project_settings->bottom_as_top_corpus_materials = true;
        $project_settings->cokol_as_corpus = true;
        $project_settings->cokol_height = (int)$ps['cokol_height'];

        $project_settings->models = new stdClass();
        $project_settings->models->top = (int)$ps['selected_facade_model'];
        $project_settings->models->bottom = (int)$ps['selected_facade_model'];

        $tmp_facmod = $this->facades_model->get_item((int)$ps['selected_facade_model']);


        if($tmp_facmod['active'] != 1){
            $errors[] = 'Не выбрана фрезеровка по умолчанию в разделе "Доступные материалы" или выбранная фрезеровка не активна';
	        echo json_encode( array('errors' => $errors), JSON_UNESCAPED_UNICODE|JSON_PRETTY_PRINT);
	        exit;
        }


        $project_settings->materials = new stdClass();
        $project_settings->materials->top = new stdClass();
        $project_settings->materials->top->facades = $c_mats;
        $project_settings->materials->top->corpus = ($ps['available_materials_corpus']);
        $project_settings->materials->top->back_wall_material = ($ps['available_materials_back_wall']);



        $project_settings->materials->bottom = new stdClass();
        $project_settings->materials->bottom->facades = $c_mats;
        $project_settings->materials->bottom->corpus = ($ps['available_materials_corpus']);
        $project_settings->materials->bottom->back_wall_material = ($ps['available_materials_back_wall']);



        $project_settings->materials->cokol = ($ps['available_materials_cokol']);

        if(isset($ps['available_materials_glass'])){
            $project_settings->materials->glass = ($ps['available_materials_glass']);
        }

        $project_settings->materials->glass_shelves = ($ps['available_materials_glass_shelves']);



        $project_settings->materials->tabletop = ($ps['available_materials_tabletop']);
        $project_settings->materials->walls = ($ps['available_materials_walls']);
        $project_settings->materials->floor = ($ps['available_materials_floor']);
        $project_settings->materials->wall_panel = ($ps['available_materials_wallpanel']);

//                print_r($project_settings);


        $project_settings->selected_materials = new stdClass();
        $project_settings->selected_materials->top = new stdClass();
        $project_settings->selected_materials->top->facades = (int)$ps['selected_material_facade'];
        $project_settings->selected_materials->top->corpus = (int)$ps['selected_material_corpus'];

        $project_settings->selected_materials->bottom = new stdClass();
        $project_settings->selected_materials->bottom->facades = (int)$ps['selected_material_facade'];
        $project_settings->selected_materials->bottom->corpus = (int)$ps['selected_material_corpus'];

        $project_settings->selected_materials->cokol = (int)$ps['selected_material_cokol'];
        if(isset($ps['selected_material_glass'])) {
            $project_settings->selected_materials->glass = (int)$ps['selected_material_glass'];
        }

        $project_settings->selected_materials->tabletop = (int)$ps['selected_material_tabletop'];
        $project_settings->selected_materials->walls = (int)$ps['selected_material_walls'];
        $project_settings->selected_materials->floor = (int)$ps['selected_material_floor'];

        $project_settings->handle = new stdClass();
        $project_settings->handle->orientation = $ps['handle_orientation'];
        $project_settings->handle->lockers_position = $ps['handle_lockers_position'];
        $project_settings->handle->selected_model = (int)$ps['handle_selected_model'];
        $project_settings->handle->preferable_size = (int)$ps['handle_preferable_size'];

        $handle_model = $this->handles_model->get_item((int)$ps['handle_selected_model']);

        if($handle_model == 0){
        	$project_settings->handle->no_handle = 1;
        }


        $h_mod = new stdClass();

        $h_mod->id = (int)$handle_model['id'];
        $h_mod->category = (int)$handle_model['category'];
        $h_mod->name = $handle_model['name'];
        $h_mod->icon = $handle_model['icon'];
        $h_mod->model = $handle_model['model'];
        $h_mod->type = $handle_model['type'];
        $h_mod->size_index = (int)$ps['handle_preferable_size'];
        $h_mod->material = json_decode($handle_model['material']);
        $h_mod->sizes = json_decode($handle_model['variants']);

        $project_settings->handle->model = $h_mod;




        $project_settings->wall_panel = new stdClass();
        $project_settings->wall_panel->active = (boolean)$ps['wallpanel_active'];
        $project_settings->wall_panel->height = (int)$ps['wallpanel_height'];


        $project_settings->selected_cornice_model = $ps['selected_cornice_model'];

        $project_settings->available_corpus_thickness = ($ps['available_corpus_thickness']);
        $project_settings->default_corpus_thickness = $ps['default_corpus_thickness'];

        $project_settings->available_shelves_thickness = ($ps['available_shelves_thickness']);
        $project_settings->default_shelves_thickness = $ps['default_shelves_thickness'];

        return $project_settings;
    }


    public function save_coupe(){

	    if($this->config->item('ini')['coupe']['available']!=1){
	    	echo 'Конструктор шкафов-купе не доступен на вашем тарифе';
	    	exit;
	    }


	    if(!$this->input->post('save_coupe')) return;

	    $save_path = dirname(FCPATH).'/data_coupe/';


	    $errors = array();

	    $settings = $this->settings_model->get();

		if(empty($settings['order_mail'])){
			$settings['order_mail'] = $this->settings_model->get_settings()['order_mail'];
		}

	    unset($settings['order_mail']);
	    unset($settings['site_url']);
	    unset($settings['vk_appid']);






	    $materials = $this->materials_model->coupe_get_all_active_items();
	    $gres_mats = array();


	    foreach ($materials as $material){
	    	$tmp = json_decode($material['params']);

		    if(isset($tmp->params->map)){
			    if($tmp->params->map != ''){
				    if (strpos($tmp->params->map, 'common_assets') === false) $tmp->params->map = $this->config->item('const_path') .$tmp->params->map;
			    }
		    }
		    if(isset($tmp->params->icon)){
			    if($tmp->params->icon != ''){
				    if (strpos($tmp->params->icon, 'common_assets') === false) $tmp->params->icon = $this->config->item('const_path') .$tmp->params->icon;
			    }
		    }

		    if(isset($tmp->params->alphaMap)){
			    if($tmp->params->alphaMap != ''){
				    if (strpos($tmp->params->alphaMap, 'common_assets') === false) $tmp->params->alphaMap = $this->config->item('const_path') .$tmp->params->alphaMap;
			    }
		    }

		    if(!isset($tmp->id)) $tmp->id = (int)$material['id'];
		    if(!isset($tmp->category)) $tmp->category = json_decode($material['category']);
		    if(!isset($tmp->name)) $tmp->name = $material['name'];
		    if(!isset($tmp->code)) $tmp->code = $material['code'];


		    $gres_mats[] = $tmp;
	    }

	    $gres_categories = array_non_empty_items($this->materials_model->coupe_get_active_categories());
	    $gres_cat = array();
	    foreach ($gres_categories as $cat){
		    $c = new stdClass();

		    $c->id = (int)$cat['id'];
		    $c->name = $cat['name'];
            if(isset($cat['image']))$c->image = $cat['image'];
		    if(isset($cat['parent'])) $c->parent = (int)$cat['parent'];

		    $gres_cat[] = $c;
	    }


	    $mat_result = new stdClass();
	    $mat_result->categories = $gres_cat;
	    $mat_result->items = $gres_mats;


	    $coupe_constructor_settings = $this->constructor_model->get_coupe();

	    $ps = $this->project_settings_model->get_coupe();


	    $project_settings = new stdClass();
	    $project_settings->profile = new stdClass();
	    $project_settings->profile->thickness = 20;


	    $project_settings->available_materials = new stdClass();
	    $project_settings->available_materials->corpus = json_decode($ps['available_materials_corpus']);
	    $project_settings->available_materials->profile = json_decode($ps['available_materials_profile']);
	    $project_settings->available_materials->doors = json_decode($ps['available_materials_doors']);
	    $project_settings->available_materials->walls = json_decode($ps['available_materials_doors']);
	    $project_settings->available_materials->floor = json_decode($ps['available_materials_doors']);


	    $project_settings->selected_materials = new stdClass();
	    $project_settings->selected_materials->corpus = (int)$ps['default_material_corpus'];
	    $project_settings->selected_materials->back_wall = (int)$ps['default_back_wall_material'];
	    $project_settings->selected_materials->profile = (int)$ps['default_material_profile'];
	    $project_settings->selected_materials->doors = (int)$ps['default_material_corpus'];
	    $project_settings->selected_materials->walls = (int)$ps['default_material_corpus'];
	    $project_settings->selected_materials->floor = (int)$ps['default_material_corpus'];


	    $project_settings->doors = new stdClass();
	    $project_settings->doors->min_width = 300;
	    $project_settings->doors->max_width = 1000;
	    $project_settings->doors->preferable = 600;

	    $mat_result->prices = json_decode($ps['prices']);



	    $templates = $this->templates_model->get_all_active_coupe();


	    $profiles = array();
	    $profiles['categories'] = $this->common_model->get_data_all_by_order('Coupe_profile_categories', 'ASC');
	    $profiles['items'] = $this->common_model->get_data_all_by_order('Coupe_profile_items', 'ASC');
        foreach ($profiles['items'] as &$item){
            if (strpos($item['icon'], 'common_assets') === false) $item['icon'] = $this->config->item('const_path') . $item['icon'];
            $item['params'] = json_decode($item['params']);
        }

	    $profiles_data = $profiles;


        $accessories = new stdClass();
        $accessories->categories = $this->common_model->get_data_all_by_order('Coupe_accessories_categories', 'ASC');
        $accessories->items = $this->common_model->get_data_all_by_order('Coupe_accessories_items', 'ASC');
        foreach ($accessories->items as &$item){
            $item['params'] = json_decode($item['params']);
        }
        $accessories_data = $accessories;


	    if (!is_dir($save_path)) mkdir($save_path);



        if($this->config->item('sub_account') != true){
            file_put_contents( $save_path . 'coupe_constructor_settings.json', json_encode($coupe_constructor_settings));
            file_put_contents( $save_path . 'constructor_settings.json', json_encode($settings));
            file_put_contents( $save_path . 'materials.json', json_encode($mat_result));
            file_put_contents( $save_path . 'project_settings.json', json_encode($project_settings));
            file_put_contents( $save_path . 'templates.json', json_encode($templates));
            file_put_contents( $save_path . 'accessories.json', json_encode($accessories_data));
            file_put_contents( $save_path . 'profiles.json', json_encode($profiles_data));
        } else {

            $tmp = file_get_contents(dirname(FCPATH).'/data_coupe/coupe_constructor_settings.json');
            $tmp = json_decode($tmp, true);
            $tmp['sub'] = true;
            $tmp['price_available'] = $coupe_constructor_settings['price_available'];
            $tmp['price_modificator'] = $coupe_constructor_settings['price_modificator'];
            $tmp['use_custom_reflection'] = $coupe_constructor_settings['use_custom_reflection'];
            $tmp['custom_reflection_image'] = $coupe_constructor_settings['custom_reflection_image'];
            $tmp['fixed_back_wall'] = $coupe_constructor_settings['fixed_back_wall'];
            file_put_contents( $save_path . 'coupe_constructor_settings.json', json_encode($tmp));
        }




	    if(!$this->config->item('sub_accounts')) $this->config->set_item('sub_accounts', array());
	    if($this->config->item('sub_accounts')[0] != ''){
		    if( count($this->config->item('sub_accounts')) > 0 ){


			    foreach ($this->config->item('sub_accounts') as $sub_acc){
				    if(!file_exists(dirname(dirname(FCPATH)) . '/' . $sub_acc)) continue;
				    if($sub_acc == null) continue;

				    $tmp = file_get_contents(dirname(dirname(FCPATH)) . '/' . $sub_acc.'/data_coupe/coupe_constructor_settings.json');
				    $tmp = json_decode($tmp, true);
				    $tmp['sub'] = true;
				    $tmp['res_path'] = $this->config->item('const_path');


				    file_put_contents( dirname(dirname(FCPATH)) . '/' . $sub_acc.'/data_coupe/coupe_constructor_settings.json', json_encode($tmp));
			    }
		    }
	    }

	    echo '<p>Настройки успешно сохранены</p>';

    }


    private function save_accs()
    {
        $this->load->model('accessories_model');

        $this->load->model('constructor_model');
        $cs = $this->constructor_model->get();

        $data = array();
        $data['items'] = $this->accessories_model->get_all();
        $data['types'] = $this->accessories_model->get_types_items_data();

        $result = array();
        $result['default'] = array();
        $result['tree'] = array();
        $result['tags'] = array();
        $result['types'] = array();
        $result['items'] = array();
        $result['type_names'] = array();


        foreach ($data['types'] as $type){
            $result['type_names'][$type['key']] = $type['name'];
        }



        $categories = array();
        $tags = array();

        $res_cat = array();

        foreach ($data['items'] as $item){

            $item['description'] = htmlspecialchars_decode($item['description']);


            $result['items'][$item['id']] = $item;

            $result['items'][$item['id']]['price'] = floatval(preg_replace('/\s+/', '', $item['price']));

            $categories[] = $item['category'];

            if($item['tags'] != ''){
                $tmp_tags = explode(',', $item['tags']);

                foreach ($tmp_tags as $tag){
                    $tags[] = trim($tag);
                }
            }



            if($item['type'] != 'common'){
                $result['types'][$item['type']][$item['id']] = 0;

            }
        }

        foreach ($data['types'] as $type){


            if($type['auto'] == 1){
                $result['default'][$type['key']] = $type['default'];
            } else {
                $result['default'][$type['key']] = null;
            }


        }



        $categories = array_values(array_unique($categories));
        $tags = array_values(array_unique($tags));

        foreach ($categories as $cat){
            $res_cat[] = array('category'=> $cat, 'items' => array());
        }



        foreach ($data['items'] as &$item){
            foreach ($res_cat as $key=>$cat){
                if($item['category'] == $cat['category']){
                    $res_cat[$key]['items'][] = $item['id'];
                }
            }
        }

        $result['tags'] = $tags;
        $result['tree'] = $res_cat;



        file_put_contents(dirname(FCPATH).'/data/accessories.json', json_encode($result));

//        if($this->config->item('username') === 'leko-sale@yandex.ru'){
//
//            $cs = json_decode($cs['settings'], true);
//
//
//            foreach ($result['items'] as &$item){
//                if($item['type'] == 'common'){
//                    $item['price'] = floatval($item['price']) * floatval($cs['price_modificator_acc']);
//                } else {
//                    $item['price'] = floatval($item['price']) * floatval($cs['price_modificator_furni']);
//                }
//            }
//        }



        if(!empty($cs['settings'])) $cs = json_decode($cs['settings'], true);
        if(empty($cs['accessories_sub_copy'])) $cs['accessories_sub_copy'] = 0;



        if($this->config->item('username') === 'leko-sale@yandex.ru' || $this->config->item('username') === '6011626@mail.ru' || $cs['accessories_sub_copy'] == 1){
            foreach ($this->config->item('sub_accounts') as $sub_acc){
                if($sub_acc == '') continue;
                if(is_dir(dirname(dirname(FCPATH)) . '/' . $sub_acc)){
                    file_put_contents(dirname(dirname(FCPATH)) . '/' . $sub_acc.'/data/accessories.json', json_encode($result, JSON_UNESCAPED_UNICODE));
                }

            }
        }
    }


}

function array_non_empty_items($input) {
    // If it is an element, then just return it
    if (!is_array($input)) {
        return $input;
    }
    $non_empty_items = array();

    foreach ($input as $key => $value) {
        // Ignore empty cells
        if($value) {
            // Use recursion to evaluate cells
            $non_empty_items[$key] = array_non_empty_items($value);
        }
    }

    // Finally return the array without empty items
    return $non_empty_items;
}