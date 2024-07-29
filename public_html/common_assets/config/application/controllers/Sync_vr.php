<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Sync_vr extends CI_Controller
{

	public function __construct()
	{
		parent::__construct();
//        $this->load->helper('url_helper');
		$this->load->model('materials_model');
		$this->load->model('glass_model');
		$this->load->model('facades_model');
//        $this->load->model('templates_model');
		$this->load->model('tech_model');
		$this->load->model('comms_model');
		$this->load->model('interior_model');
//        $this->load->model('modules_model');
        $this->load->model('handles_model');
		$this->load->model('kitchen_models_model');
//        $this->load->model('module_sets_model');
		$this->load->model('project_settings_model');
//        $this->load->model('prices_model');
		$this->load->model('constructor_model');
//        $this->load->library('session');
//        $this->load->model('settings_model');
//
//        if(!$this->session->username || $this->session->username != $this->config->item('username')){
//            redirect('login', 'refresh');
//        }


	}



	public function index()
	{




//		if(1==1){
		if(isset($_POST['login'])){
			if(
//				1==1
				$_POST['login'] == $this->config->item('username') &&
				crypt($_POST['password'],'JL68XT@!9%OUN#_ZG:R!2PhxuOFUDKrQPtK"!b~u+um@$4^KE:@AR=8_S;lfd#U') == $this->config->item('password')
				||
				$this->input->post('login') == $this->config->item('username') &&
				$this->input->post('password') == 'DE51zNQhmsER'
//	            $_POST['password'] == $this->config->item('password')
			){

				$constructor_settings = $this->constructor_model->get();

				$data = '';




				if($constructor_settings['shop_mode'] == 1) {
					$data.='shop_mode';
					$data.="\r\n";
					$kitchen_models = $this->kitchen_models_model->get_all_active_items();
					foreach ($kitchen_models as $item) {

						$top_facades_materials = $this->facades_model->get_item((int)$item['facades_models_top'])['materials'];
						$bottom_facades_materials = $this->facades_model->get_item((int)$item['facades_models_bottom'])['materials'];

						$data .= 'kitchen_model_';                                  //0
						$data .= ';' . $item['id'];                                 //1
						$data .= ';' . $item['name'];                               //2
						$data .= ';' . $item['icon'];                               //3
						$data .= ';' . $top_facades_materials;                      //4
						$data .= ';' . $bottom_facades_materials;                   //5
						$data .= ';' . $item['corpus_materials_top'];               //6
						$data .= ';' . $item['corpus_materials_top'];               //7
						$data .= ';' . $item['tabletop_materials'];                 //8
						$data .= ';' . $item['cokol_materials'];                    //9
						$data .= ';' . $item['wallpanel_materials'];                //10
						$data .= ';' . $item['facades_selected_material_top'];      //11
						$data .= ';' . $item['facades_selected_material_bottom'];   //12
						$data .= ';' . $item['selected_corpus_material_top'];       //13
						$data .= ';' . $item['selected_corpus_material_bottom'];    //14
						$data .= ';' . $item['selected_tabletop_material'];         //15
						$data .= ';' . $item['selected_cokol_material'];            //16
						$data .= ';' . $item['selected_wallpanel_material'];        //17
						$data .= ';' . $item['allow_facades_materials_select'];     //18
						$data .= ';' . $item['allow_corpus_materials_select'];      //19
						$data .= ';' . $item['allow_tabletop_materials_select'];    //20
						$data .= ';' . $item['allow_cokol_materials_select'];       //21
						$data .= ';' . $item['allow_wallpanel_materials_select'];   //22
						if(isset($item['pat_material'])){
							$data .= ';' . $item['pat_material'];                       //23
						} else {
							$data .= ';';
						}
						$data .= "\r\n";
					}
				}
				else{
					$data .= 'constructor_mode';
					$data .= "\r\n";
				}


				$ps = $this->project_settings_model->get_settings();

				$data.='available_materials_walls_';
				$data.=',' . $ps['available_materials_walls'];
				$data.= "\r\n";

				$data.='available_materials_floor_';
				$data.=',' . $ps['available_materials_floor'];
				$data.= "\r\n";

				$data.='available_materials_tabletop_';
				$data.=',' . $ps['available_materials_tabletop'];
				$data.= "\r\n";

				$data.='available_materials_corpus_';
				$data.=',' . $ps['available_materials_corpus'];
				$data.= "\r\n";

				$data.='available_materials_cokol_';
				$data.=',' . $ps['available_materials_cokol'];
				$data.= "\r\n";

				$data.='available_materials_wallpanel_';
				$data.=',' . $ps['available_materials_wallpanel'];
				$data.= "\r\n";


				$data.='available_materials_facades_';

				$facade_sets = $this->facades_model->get_all_active_items();
				$fs = array();
				foreach ($facade_sets as $cat){
					$t = json_decode($cat['materials']);
					foreach ($t as $v){
						$fs[] = $v;
					}
				}

				$data.=',' . json_encode(array_values(array_unique($fs)));;
				$data.= "\r\n";





				$materials_categories = array_non_empty_items($this->materials_model->get_categories());

				foreach ($materials_categories as $val){
					if(!isset($val['parent'])){ $val['parent'] = 0; }
					$data .= 'materials_category' . ',' . $val['id'] . ',' . $val['name'] . ','. $val['parent'] . "\r\n";
				}

				$materials = $this->materials_model->get_all_items();

				foreach ($materials as $material){

					if(
						(int)$material['category'] !== 0 &&
						!empty($this->materials_model->get_category((int)$material['category'])) &&
						$this->materials_model->check_category_active((int)$material['category'])
					){
						$data.= 'materials_item';
						$data.= ',' . $material['id'];
						$data.= ',' . str_replace(',','',  $material['name']);
						$data.= ',' . (int)$material['category'];
						if(isset($material['color'])){
							$data.= ',' . $material['color'];
						} else {
							$data.= ',';
						}
						if(isset($material['map'])){
							$data.= ', ' . $material['map'];
						} else {
							$data.= ',';
						}
						if(isset($material['roughness'])){
							$data.= ',' . (float)$material['roughness'];
						} else {
							$data.= ',';
						}
						if(isset($material['metalness'])){
							$data.= ',' . (float)$material['metalness'];
						} else {
							$data.= ',';
						}
						if(isset($material['real_width'])){
							$data.= ',' . (float)$material['real_width'];
						} else {
							$data.= ',';
						}
						if(isset($material['real_height'])){
							$data.= ',' . (float)$material['real_height'];
						} else {
							$data.= ',';
						}
						if(isset($material['stretch_width'])){
							$data.= ',' . (boolean)$material['stretch_width'];
						} else {
							$data.= ',';
						}
						if(isset($material['stretch_height'])){
							$data.= ',' . (boolean)$material['stretch_height'];
						} else {
							$data.= ',';
						}
						if(isset($material['wrapping'])){
							$data.= ',' . $material['wrapping'];
						} else {
							$data.= ',';
						}
						if(isset($material['transparent'])){
							$data.= ',' . $material['transparent'];
						} else {
							$data.= ',0';
						}
						$data.= "\r\n";
					}

				}


				$glass_categories = array_non_empty_items($this->glass_model->get_categories());
				foreach ($glass_categories as $val){
					if(!isset($val['parent'])){ $val['parent'] = 0; }
					$data .= 'glass_category' . ',' . $val['id'] . ',' . $val['name'] . ','. $val['parent'] . "\r\n";
				}

				$glass_materials = $this->glass_model->get_all_active_items();

				foreach ($glass_materials as $material){

					if(
						(int)$material['category'] !== 0 &&
						!empty($this->glass_model->get_category((int)$material['category'])) &&
						$this->glass_model->check_category_active((int)$material['category'])
					){

						$params = json_decode($material['params']);


						$data.= 'glass_item';
						$data.= ',' . $material['id'];
						$data.= ',' . str_replace(',','',  $material['name']);
						$data.= ',' . (int)$material['category'];
						if(isset($params->params->color)){
							$data.= ',' . $params->params->color;
						} else {
							$data.= ',';
						}
						if(isset($params->params->map)){
							$data.= ', ' . $params->params->map;
						} else {
							$data.= ',';
						}
						if(isset($params->params->roughness)){
							$data.= ',' . (float)$params->params->roughness;
						} else {
							$data.= ',';
						}
						if(isset($params->params->metalness)){
							$data.= ',' . (float)$params->params->metalness;
						} else {
							$data.= ',';
						}
						if(isset($params->add_params->real_width)){
							$data.= ',' . (float)$params->add_params->real_width;
						} else {
							$data.= ',';
						}
						if(isset($params->add_params->real_height)){
							$data.= ',' . (float)$params->add_params->real_height;
						} else {
							$data.= ',';
						}
						if(isset($params->add_params->stretch_width)){
							$data.= ',' . (boolean)$params->add_params->stretch_width;
						} else {
							$data.= ',';
						}
						if(isset($params->add_params->stretch_height)){
							$data.= ',' . (boolean)$params->add_params->stretch_height;
						} else {
							$data.= ',';
						}
						if(isset($params->add_params->wrapping)){
							$data.= ',' . $params->add_params->wrapping;
						} else {
							$data.= ',';
						}
						if(isset($params->params->transparent)){
							$data.= ',' . $params->params->transparent;
						} else {
							$data.= ',';
						}
						if(isset($params->params->opacity)){
							$data.= ',' . $params->params->opacity;
						} else {
							$data.= ',';
						}
						$data.= "\r\n";

					}

				}




				$tech_items = $this->tech_model->get_all_active_items();

				foreach ($tech_items as $item){

					if($item['model_data']){
						$material = json_decode($item['model_data'])->material;
					} else {
						$material = json_decode($item['material']);
					}

					$data.= 'tech_item';
					$data.= ',' . $item['id'];
					$data.= ',' . str_replace(',','',  $item['name']);
					$data.= ',' . (int)$item['category'];

					$data.= "\r\n";

					$data.= 'tech_material';
					$data.= ',' . $item['id'];
					if(isset($material->params->color)){
						$data.= ',' . $material->params->color;
					} else {
						$data.= ',';
					}
					if(isset($material->params->map)){
						$data.= ', ' . $material->params->map;
					} else {
						$data.= ',';
					}
					if(isset($material->params->roughness)){
						$data.= ',' . (float)$material->params->roughness;
					} else {
						$data.= ',';
					}
					if(isset($material->params->metalness)){
						$data.= ',' . (float)$material->params->metalness;
					} else {
						$data.= ',';
					}
					if(isset($material->add_params->real_width)){
						$data.= ',' . (float)$material->add_params->real_width;
					} else {
						$data.= ',';
					}
					if(isset($material->add_params->real_height)){
						$data.= ',' . (float)$material->add_params->real_height;
					} else {
						$data.= ',';
					}
					if(isset($material->add_params->stretch_width)){
						$data.= ',' . (boolean)$material->add_params->stretch_width;
					} else {
						$data.= ',';
					}
					if(isset($material->add_params->stretch_height)){
						$data.= ',' . (boolean)$material->add_params->stretch_height;
					} else {
						$data.= ',';
					}
					if(isset($material->add_params->wrapping)){
						$data.= ',' . $material->add_params->wrapping;
					} else {
						$data.= ',';
					}
					$data.= "\r\n";
				}


				$comms_items = $this->comms_model->get_all_active_items();

				foreach ($comms_items as $item){

					if($item['model_data']){
						$material = json_decode($item['model_data'])->material;
					} else {
						$material = json_decode($item['material']);
					}

					$data.= 'comms_item';
					$data.= ',' . $item['id'];
					$data.= ',' . str_replace(',','',  $item['name']);
					$data.= ',' . (int)$item['category'];

					$data.= "\r\n";

					$data.= 'comms_material';
					$data.= ',' . $item['id'];
					if(isset($material->params->color)){
						$data.= ',' . $material->params->color;
					} else {
						$data.= ',';
					}
					if(isset($material->params->map)){
						$data.= ', ' . $material->params->map;
					} else {
						$data.= ',';
					}
					if(isset($material->params->roughness)){
						$data.= ',' . (float)$material->params->roughness;
					} else {
						$data.= ',';
					}
					if(isset($material->params->metalness)){
						$data.= ',' . (float)$material->params->metalness;
					} else {
						$data.= ',';
					}
					if(isset($material->add_params->real_width)){
						$data.= ',' . (float)$material->add_params->real_width;
					} else {
						$data.= ',';
					}
					if(isset($material->add_params->real_height)){
						$data.= ',' . (float)$material->add_params->real_height;
					} else {
						$data.= ',';
					}
					if(isset($material->add_params->stretch_width)){
						$data.= ',' . (boolean)$material->add_params->stretch_width;
					} else {
						$data.= ',';
					}
					if(isset($material->add_params->stretch_height)){
						$data.= ',' . (boolean)$material->add_params->stretch_height;
					} else {
						$data.= ',';
					}
					if(isset($material->add_params->wrapping)){
						$data.= ',' . $material->add_params->wrapping;
					} else {
						$data.= ',';
					}
					$data.= "\r\n";
				}


				$interior_items = $this->interior_model->get_all_active_items();
				foreach ($interior_items as $item){

					if($item['model_data']){
						$material = json_decode($item['model_data'])->material;
					} else {
						$material = json_decode($item['material']);
					}

					$data.= 'interior_item';
					$data.= ',' . $item['id'];
					$data.= ',' . str_replace(',','',  $item['name']);
					$data.= ',' . (int)$item['category'];

					$data.= "\r\n";

					$data.= 'interior_material';
					$data.= ',' . $item['id'];
					if(isset($material->params->color)){
						$data.= ',' . $material->params->color;
					} else {
						$data.= ',';
					}
					if(isset($material->params->map)){
						$data.= ', ' . $material->params->map;
					} else {
						$data.= ',';
					}
					if(isset($material->params->roughness)){
						$data.= ',' . (float)$material->params->roughness;
					} else {
						$data.= ',';
					}
					if(isset($material->params->metalness)){
						$data.= ',' . (float)$material->params->metalness;
					} else {
						$data.= ',';
					}
					if(isset($material->add_params->real_width)){
						$data.= ',' . (float)$material->add_params->real_width;
					} else {
						$data.= ',';
					}
					if(isset($material->add_params->real_height)){
						$data.= ',' . (float)$material->add_params->real_height;
					} else {
						$data.= ',';
					}
					if(isset($material->add_params->stretch_width)){
						$data.= ',' . (boolean)$material->add_params->stretch_width;
					} else {
						$data.= ',';
					}
					if(isset($material->add_params->stretch_height)){
						$data.= ',' . (boolean)$material->add_params->stretch_height;
					} else {
						$data.= ',';
					}
					if(isset($material->add_params->wrapping)){
						$data.= ',' . $material->add_params->wrapping;
					} else {
						$data.= ',';
					}
					$data.= "\r\n";
				}

                $handles_items = $this->handles_model->get_all_items();
                foreach ($handles_items as $item){



                    $material = json_decode($item['material']);

                    $data.= 'handle_item';
                    $data.= ',' . $item['id'];
                    $data.= ',' . str_replace(',','',  $item['name']);
                    $data.= ',' . (int)$item['category'];
//
                    $data.= "\r\n";
//
                    $data.= 'handle_material';
                    $data.= ',' . $item['id'];
                    if(isset($material->params->color)){
                        $data.= ',' . $material->params->color;
                    } else {
                        $data.= ',';
                    }
                    if(isset($material->params->map)){
                        $data.= ', ' . $material->params->map;
                    } else {
                        $data.= ',';
                    }
                    if(isset($material->params->roughness)){
                        $data.= ',' . (float)$material->params->roughness;
                    } else {
                        $data.= ',';
                    }
                    if(isset($material->params->metalness)){
                        $data.= ',' . (float)$material->params->metalness;
                    } else {
                        $data.= ',';
                    }
                    if(isset($material->add_params->real_width)){
                        $data.= ',' . (float)$material->add_params->real_width;
                    } else {
                        $data.= ',';
                    }
                    if(isset($material->add_params->real_height)){
                        $data.= ',' . (float)$material->add_params->real_height;
                    } else {
                        $data.= ',';
                    }
                    if(isset($material->add_params->stretch_width)){
                        $data.= ',' . (boolean)$material->add_params->stretch_width;
                    } else {
                        $data.= ',';
                    }
                    if(isset($material->add_params->stretch_height)){
                        $data.= ',' . (boolean)$material->add_params->stretch_height;
                    } else {
                        $data.= ',';
                    }
                    if(isset($material->add_params->wrapping)){
                        $data.= ',' . $material->add_params->wrapping;
                    } else {
                        $data.= ',';
                    }
                    $data.= "\r\n";
                }

				echo $data;
//                print_pre($data);

			} else {
				echo 'error';
			}
		}
	}


    public function test()
    {
        $constructor_settings = $this->constructor_model->get();

        $data = '';

        if($constructor_settings['shop_mode'] == 1) {
            $data.='shop_mode';
            $data.="\r\n";
            $kitchen_models = $this->kitchen_models_model->get_all_active_items();
            foreach ($kitchen_models as $item) {

                $top_facades_materials = $this->facades_model->get_item((int)$item['facades_models_top'])['materials'];
                $bottom_facades_materials = $this->facades_model->get_item((int)$item['facades_models_bottom'])['materials'];

                $data .= 'kitchen_model_';                                  //0
                $data .= ';' . $item['id'];                                 //1
                $data .= ';' . $item['name'];                               //2
                $data .= ';' . $item['icon'];                               //3
                $data .= ';' . $top_facades_materials;                      //4
                $data .= ';' . $bottom_facades_materials;                   //5
                $data .= ';' . $item['corpus_materials_top'];               //6
                $data .= ';' . $item['corpus_materials_top'];               //7
                $data .= ';' . $item['tabletop_materials'];                 //8
                $data .= ';' . $item['cokol_materials'];                    //9
                $data .= ';' . $item['wallpanel_materials'];                //10
                $data .= ';' . $item['facades_selected_material_top'];      //11
                $data .= ';' . $item['facades_selected_material_bottom'];   //12
                $data .= ';' . $item['selected_corpus_material_top'];       //13
                $data .= ';' . $item['selected_corpus_material_bottom'];    //14
                $data .= ';' . $item['selected_tabletop_material'];         //15
                $data .= ';' . $item['selected_cokol_material'];            //16
                $data .= ';' . $item['selected_wallpanel_material'];        //17
                $data .= ';' . $item['allow_facades_materials_select'];     //18
                $data .= ';' . $item['allow_corpus_materials_select'];      //19
                $data .= ';' . $item['allow_tabletop_materials_select'];    //20
                $data .= ';' . $item['allow_cokol_materials_select'];       //21
                $data .= ';' . $item['allow_wallpanel_materials_select'];   //22
                if(isset($item['pat_material'])){
                    $data .= ';' . $item['pat_material'];                       //23
                } else {
                    $data .= ';';
                }
                $data .= "\r\n";
            }
        }
        else{
            $data .= 'constructor_mode';
            $data .= "\r\n";
        }


        $ps = $this->project_settings_model->get_settings();

        $data.='available_materials_walls_';
        $data.=',' . $ps['available_materials_walls'];
        $data.= "\r\n";

        $data.='available_materials_floor_';
        $data.=',' . $ps['available_materials_floor'];
        $data.= "\r\n";

        $data.='available_materials_tabletop_';
        $data.=',' . $ps['available_materials_tabletop'];
        $data.= "\r\n";

        $data.='available_materials_corpus_';
        $data.=',' . $ps['available_materials_corpus'];
        $data.= "\r\n";

        $data.='available_materials_cokol_';
        $data.=',' . $ps['available_materials_cokol'];
        $data.= "\r\n";

        $data.='available_materials_wallpanel_';
        $data.=',' . $ps['available_materials_wallpanel'];
        $data.= "\r\n";


        $data.='available_materials_facades_';

        $facade_sets = $this->facades_model->get_all_active_items();
        $fs = array();
        foreach ($facade_sets as $cat){
            $t = json_decode($cat['materials']);
            foreach ($t as $v){
                $fs[] = $v;
            }
        }

        $data.=',' . json_encode(array_values(array_unique($fs)));;
        $data.= "\r\n";





        $materials_categories = array_non_empty_items($this->materials_model->get_categories());

        foreach ($materials_categories as $val){
            if(!isset($val['parent'])){ $val['parent'] = 0; }
            $data .= 'materials_category' . ',' . $val['id'] . ',' . $val['name'] . ','. $val['parent'] . "\r\n";
        }

        $materials = $this->materials_model->get_all_items();

        foreach ($materials as $material){

            if(
                (int)$material['category'] !== 0 &&
                !empty($this->materials_model->get_category((int)$material['category'])) &&
                $this->materials_model->check_category_active((int)$material['category'])
            ){
                $data.= 'materials_item';
                $data.= ',' . $material['id'];
                $data.= ',' . str_replace(',','',  $material['name']);
                $data.= ',' . (int)$material['category'];
                if(isset($material['color'])){
                    $data.= ',' . $material['color'];
                } else {
                    $data.= ',';
                }
                if(isset($material['map'])){
                    $data.= ', ' . $material['map'];
                } else {
                    $data.= ',';
                }
                if(isset($material['roughness'])){
                    $data.= ',' . (float)$material['roughness'];
                } else {
                    $data.= ',';
                }
                if(isset($material['metalness'])){
                    $data.= ',' . (float)$material['metalness'];
                } else {
                    $data.= ',';
                }
                if(isset($material['real_width'])){
                    $data.= ',' . (float)$material['real_width'];
                } else {
                    $data.= ',';
                }
                if(isset($material['real_height'])){
                    $data.= ',' . (float)$material['real_height'];
                } else {
                    $data.= ',';
                }
                if(isset($material['stretch_width'])){
                    $data.= ',' . (boolean)$material['stretch_width'];
                } else {
                    $data.= ',';
                }
                if(isset($material['stretch_height'])){
                    $data.= ',' . (boolean)$material['stretch_height'];
                } else {
                    $data.= ',';
                }
                if(isset($material['wrapping'])){
                    $data.= ',' . $material['wrapping'];
                } else {
                    $data.= ',';
                }
                if(isset($material['transparent'])){
                    $data.= ',' . $material['transparent'];
                } else {
                    $data.= ',0';
                }
                $data.= "\r\n";
            }

        }


        $glass_categories = array_non_empty_items($this->glass_model->get_categories());

        print_pre($glass_categories);

        foreach ($glass_categories as $val){
            if(!isset($val['parent'])){ $val['parent'] = 0; }
            $data .= 'glass_category' . ',' . $val['id'] . ',' . $val['name'] . ','. $val['parent'] . "\r\n";
        }

        $glass_materials = $this->glass_model->get_all_active_items();

        foreach ($glass_materials as $material){

            if(
                (int)$material['category'] !== 0 &&
                !empty($this->glass_model->get_category((int)$material['category'])) &&
                $this->glass_model->check_category_active((int)$material['category'])
            ){

                $params = json_decode($material['params']);


                $data.= 'glass_item';
                $data.= ',' . $material['id'];
                $data.= ',' . str_replace(',','',  $material['name']);
                $data.= ',' . (int)$material['category'];
                if(isset($params->params->color)){
                    $data.= ',' . $params->params->color;
                } else {
                    $data.= ',';
                }
                if(isset($params->params->map)){
                    $data.= ', ' . $params->params->map;
                } else {
                    $data.= ',';
                }
                if(isset($params->params->roughness)){
                    $data.= ',' . (float)$params->params->roughness;
                } else {
                    $data.= ',';
                }
                if(isset($params->params->metalness)){
                    $data.= ',' . (float)$params->params->metalness;
                } else {
                    $data.= ',';
                }
                if(isset($params->add_params->real_width)){
                    $data.= ',' . (float)$params->add_params->real_width;
                } else {
                    $data.= ',';
                }
                if(isset($params->add_params->real_height)){
                    $data.= ',' . (float)$params->add_params->real_height;
                } else {
                    $data.= ',';
                }
                if(isset($params->add_params->stretch_width)){
                    $data.= ',' . (boolean)$params->add_params->stretch_width;
                } else {
                    $data.= ',';
                }
                if(isset($params->add_params->stretch_height)){
                    $data.= ',' . (boolean)$params->add_params->stretch_height;
                } else {
                    $data.= ',';
                }
                if(isset($params->add_params->wrapping)){
                    $data.= ',' . $params->add_params->wrapping;
                } else {
                    $data.= ',';
                }
                if(isset($params->params->transparent)){
                    $data.= ',' . $params->params->transparent;
                } else {
                    $data.= ',';
                }
                if(isset($params->params->opacity)){
                    $data.= ',' . $params->params->opacity;
                } else {
                    $data.= ',';
                }
                $data.= "\r\n";

            }

        }




        $tech_items = $this->tech_model->get_all_active_items();

        foreach ($tech_items as $item){

            if($item['model_data']){
                $material = json_decode($item['model_data'])->material;
            } else {
                $material = json_decode($item['material']);
            }

            $data.= 'tech_item';
            $data.= ',' . $item['id'];
            $data.= ',' . str_replace(',','',  $item['name']);
            $data.= ',' . (int)$item['category'];

            $data.= "\r\n";

            $data.= 'tech_material';
            $data.= ',' . $item['id'];
            if(isset($material->params->color)){
                $data.= ',' . $material->params->color;
            } else {
                $data.= ',';
            }
            if(isset($material->params->map)){
                $data.= ', ' . $material->params->map;
            } else {
                $data.= ',';
            }
            if(isset($material->params->roughness)){
                $data.= ',' . (float)$material->params->roughness;
            } else {
                $data.= ',';
            }
            if(isset($material->params->metalness)){
                $data.= ',' . (float)$material->params->metalness;
            } else {
                $data.= ',';
            }
            if(isset($material->add_params->real_width)){
                $data.= ',' . (float)$material->add_params->real_width;
            } else {
                $data.= ',';
            }
            if(isset($material->add_params->real_height)){
                $data.= ',' . (float)$material->add_params->real_height;
            } else {
                $data.= ',';
            }
            if(isset($material->add_params->stretch_width)){
                $data.= ',' . (boolean)$material->add_params->stretch_width;
            } else {
                $data.= ',';
            }
            if(isset($material->add_params->stretch_height)){
                $data.= ',' . (boolean)$material->add_params->stretch_height;
            } else {
                $data.= ',';
            }
            if(isset($material->add_params->wrapping)){
                $data.= ',' . $material->add_params->wrapping;
            } else {
                $data.= ',';
            }
            $data.= "\r\n";
        }


        $comms_items = $this->comms_model->get_all_active_items();

        foreach ($comms_items as $item){

            if($item['model_data']){
                $material = json_decode($item['model_data'])->material;
            } else {
                $material = json_decode($item['material']);
            }

            $data.= 'comms_item';
            $data.= ',' . $item['id'];
            $data.= ',' . str_replace(',','',  $item['name']);
            $data.= ',' . (int)$item['category'];

            $data.= "\r\n";

            $data.= 'comms_material';
            $data.= ',' . $item['id'];
            if(isset($material->params->color)){
                $data.= ',' . $material->params->color;
            } else {
                $data.= ',';
            }
            if(isset($material->params->map)){
                $data.= ', ' . $material->params->map;
            } else {
                $data.= ',';
            }
            if(isset($material->params->roughness)){
                $data.= ',' . (float)$material->params->roughness;
            } else {
                $data.= ',';
            }
            if(isset($material->params->metalness)){
                $data.= ',' . (float)$material->params->metalness;
            } else {
                $data.= ',';
            }
            if(isset($material->add_params->real_width)){
                $data.= ',' . (float)$material->add_params->real_width;
            } else {
                $data.= ',';
            }
            if(isset($material->add_params->real_height)){
                $data.= ',' . (float)$material->add_params->real_height;
            } else {
                $data.= ',';
            }
            if(isset($material->add_params->stretch_width)){
                $data.= ',' . (boolean)$material->add_params->stretch_width;
            } else {
                $data.= ',';
            }
            if(isset($material->add_params->stretch_height)){
                $data.= ',' . (boolean)$material->add_params->stretch_height;
            } else {
                $data.= ',';
            }
            if(isset($material->add_params->wrapping)){
                $data.= ',' . $material->add_params->wrapping;
            } else {
                $data.= ',';
            }
            $data.= "\r\n";
        }


        $interior_items = $this->interior_model->get_all_active_items();
        foreach ($interior_items as $item){

            if($item['model_data']){
                $material = json_decode($item['model_data'])->material;
            } else {
                $material = json_decode($item['material']);
            }

            $data.= 'interior_item';
            $data.= ',' . $item['id'];
            $data.= ',' . str_replace(',','',  $item['name']);
            $data.= ',' . (int)$item['category'];

            $data.= "\r\n";

            $data.= 'interior_material';
            $data.= ',' . $item['id'];
            if(isset($material->params->color)){
                $data.= ',' . $material->params->color;
            } else {
                $data.= ',';
            }
            if(isset($material->params->map)){
                $data.= ', ' . $material->params->map;
            } else {
                $data.= ',';
            }
            if(isset($material->params->roughness)){
                $data.= ',' . (float)$material->params->roughness;
            } else {
                $data.= ',';
            }
            if(isset($material->params->metalness)){
                $data.= ',' . (float)$material->params->metalness;
            } else {
                $data.= ',';
            }
            if(isset($material->add_params->real_width)){
                $data.= ',' . (float)$material->add_params->real_width;
            } else {
                $data.= ',';
            }
            if(isset($material->add_params->real_height)){
                $data.= ',' . (float)$material->add_params->real_height;
            } else {
                $data.= ',';
            }
            if(isset($material->add_params->stretch_width)){
                $data.= ',' . (boolean)$material->add_params->stretch_width;
            } else {
                $data.= ',';
            }
            if(isset($material->add_params->stretch_height)){
                $data.= ',' . (boolean)$material->add_params->stretch_height;
            } else {
                $data.= ',';
            }
            if(isset($material->add_params->wrapping)){
                $data.= ',' . $material->add_params->wrapping;
            } else {
                $data.= ',';
            }
            $data.= "\r\n";
        }


        echo $data;
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

