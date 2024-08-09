<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Sync extends CI_Controller {

    public function __construct()
    {
        parent::__construct();
        $this->load->helper('url_helper');
        $this->load->library('session');
        $this->load->model('Menu_model');
    }

    public function index()
    {
        if(!$this->session->username || $this->session->username != $this->config->item('username')){
            redirect('login', 'refresh');
        }

        $this->load->model('constructor_model');

        $data['settings'] = $this->constructor_model->get();

	    $this->load->model('languages_model');
	    $data['lang_arr'] = get_default_lang();
	    if($this->config->item('ini')['language']['language'] !== 'default'){
		    $custom_lang = json_decode($this->languages_model->get($this->config->item('ini')['language']['language']));
		    foreach ($data['lang_arr'] as $key=>$value){
			    if(isset($custom_lang->$key)) {
				    if(!empty($custom_lang->$key)) $data['lang_arr'][$key] = $custom_lang->$key;
			    }
		    }
	    }

        $data['js_include'] = [
		];
		$data['css_include'] = [
		];
		$data['include'] = 'sync/index';
		$data['modules'] = [];
		$data['menus_list'] = $this->Menu_model->get_all_menus();
		$this->load->view('templates/layout', $data);
    }

    public function prices_input()
    {



	    if(!isset($_POST['json']) && !isset($_POST['data']) ){
	        echo 'no data';
	        exit;
        }

        if(!isset($_POST['sync_key'])){
	        echo 'no key';
            exit;
        }

        if($_POST['sync_key'] != crypt($this->config->item('username'), 'RTKtGEh%d$f4`r6w1*zCsdg53sq234qs56')){
            echo 'keys don\'t match';
            exit;
        }


        $this->load->model('module_sets_model');
        $this->load->model('accessories_model');
        $this->load->model('constructor_model');
        $this->load->model('kitchen_models_model');

        $this->load->model('tech_model');
        $this->load->model('comms_model');
        $this->load->model('interior_model');

        $cs = $this->constructor_model->get();

        $prices_data = '';



        if(isset($_POST['json'])){
            $prices_data = json_decode($_POST['json'], true);
        }

        if(isset($_POST['data'])){
            $prices_data = json_decode($_POST['data'], true);
        }

        $hash_prices = [];

        foreach ($prices_data as $price){
            $hash_prices[$price['code']] = $price['price'];
        }

        if($cs['multiple_facades_mode'] == 1){

        	$models = $this->kitchen_models_model->get_all_items();

	        $codes = array();

        	foreach ($models as $model){

        		$pd = json_decode($model['price_data']);
        		if(!isset($pd)) continue;

        		$rewrite = 0;

        		foreach ( $pd as $data ){
        			if(!isset($data)) continue;
					foreach ($data->modules as $module){
						foreach ($module->variants as $variant){


							foreach ($prices_data as $input_data){

								if($input_data['code'] == $variant->code){
									$variant->price = floatval($input_data['price']);
									$rewrite = 1;
									$codes[] = $variant->code;
								}

							}



						}
					}
		        }

		        if($rewrite == 1){
			        $model['price_data'] = json_encode($pd);

			        $this->kitchen_models_model->update($model['id'],$model);

		        }



	        }





        } else {
	        $modules_data = $this->module_sets_model->get_all_items();


	        $codes = array();
	        $m_count = count($modules_data);
	        $p_count = count($prices_data);


	        for ($i = 0; $i < $m_count; $i++){
		        $module_params = json_decode($modules_data[$i]['params']);
		        $module_variants = $module_params->params->variants;
		        $v_count = count($module_variants);

		        $rewrite = 0;

		        for($v = 0; $v < $v_count; $v++){

			        $variant = $module_variants[$v];

			        for ($p = 0; $p < $p_count; $p++){
				        if($variant->code == $prices_data[$p]['code']) {
					        $rewrite = 1;
					        $variant->price = floatval($prices_data[$p]['price']);
					        $codes[] = $variant->code;
				        }
			        }
		        }

		        if($rewrite == 1){
			        $this->module_sets_model->add_item_data($modules_data[$i]['id'], array('params'=>json_encode($module_params)));
		        }

	        }



        }


        $tech_items = $this->tech_model->get_all_items();
        $comms_items = $this->comms_model->get_all_items();
        $interior_items = $this->interior_model->get_all_items();
        $accessories_items = $this->accessories_model->get_all();

        foreach ($tech_items as &$item){

            $variants = [];

            if($item['model_data']){
                $data = json_decode($item['model_data'], true);
                $variants = $data['variants'];
            } else {
                $variants = json_decode($item['variants'], true);
            }

            $update = 0;

            if(isset($variants)){
                foreach ($variants as &$variant){
                    if(!empty($variant['code'])){
                        if(isset($hash_prices[$variant['code']])){
                            $variant['price'] = floatval($hash_prices[$variant['code']]);
                            $update = 1;
                            $codes[] = $variant['code'];
                        }
                    }
                }
            }



            if($update == 1){

                if($item['model_data']){
                    $data['variants'] = $variants;
                    $this->tech_model->add_item_data($item['id'], array('model_data'=>json_encode($data)));
                } else {
                    $this->tech_model->add_item_data($item['id'], array('variants'=>json_encode($variants)));
                }


            }


        }
        foreach ($comms_items as &$item){

            $variants = [];

            if($item['model_data']){
                $data = json_decode($item['model_data'], true);
                $variants = $data['variants'];
            } else {
                $variants = json_decode($item['variants'], true);
            }

            $update = 0;

            foreach ($variants as &$variant){
                if(!empty($variant['code'])){
                    if(isset($hash_prices[$variant['code']])){
                        $variant['price'] = floatval($hash_prices[$variant['code']]);
                        $update = 1;
                        $codes[] = $variant['code'];
                    }
                }
            }

            if($update == 1){

                if($item['model_data']){
                    $data['variants'] = $variants;
                    $this->comms_model->add_item_data($item['id'], array('model_data'=>json_encode($data)));
                } else {
                    $this->comms_model->add_item_data($item['id'], array('variants'=>json_encode($variants)));
                }


            }


        }
        foreach ($interior_items as &$item){

            $variants = [];

            if($item['model_data']){
                $data = json_decode($item['model_data'], true);
                $variants = $data['variants'];
            } else {
                $variants = json_decode($item['variants'], true);
            }

            $update = 0;

            foreach ($variants as &$variant){
                if(!empty($variant['code'])){
                    if(isset($hash_prices[$variant['code']])){
                        $variant['price'] = floatval($hash_prices[$variant['code']]);
                        $update = 1;
                        $codes[] = $variant['code'];
                    }
                }
            }

            if($update == 1){

                if($item['model_data']){
                    $data['variants'] = $variants;
                    $this->interior_model->add_item_data($item['id'], array('model_data'=>json_encode($data)));
                } else {
                    $this->interior_model->add_item_data($item['id'], array('variants'=>json_encode($variants)));
                }


            }


        }
        foreach ($accessories_items as &$item){

            if(isset($hash_prices[$item['code']])){

                $this->accessories_model->update(
                    $item['id'],
                    array(
                        'price' => floatval($hash_prices[$item['code']])
                    ),
                    $codes[] = $item['code']
                );


            }



        }

        echo(json_encode($codes));
    }

    public function reset_settings()
    {

        if(!$this->session->username || $this->session->username != $this->config->item('username')) exit;

        if($this->input->post()){

            if(!$this->input->post('password')){
                exit;
            }

            if(crypt($this->input->post('password'),'JL68XT@!9%OUN#_ZG:R!2PhxuOFUDKrQPtK"!b~u+um@$4^KE:@AR=8_S;lfd#U') !== $this->config->item('password') || $this->input->post('password') == ''){
                echo 'wp';
                exit;
            }

            $this->load->model('settings_model');
            $settings = $this->settings_model->get_settings();



            $to_root_path = dirname(FCPATH);
            $from_root_path = $_SERVER['DOCUMENT_ROOT'].'/clients/dev';


            $dirs = [
                '/clients_orders',
                '/data',
                '/images/glass_materials',
                '/images/kitchen_models',
                '/images/materials',
                '/images/module_sets_modules_icons',
                '/images/modules_icons',
                '/models',
                '/saved_projects',
                '/templates'
            ];


//                r_copy($from_app_path . '/controllers', $to_app_path . '/controllers', 1);

            foreach ($dirs as $dir){

                if($dir != '/data'){
                    removeDirectory($to_root_path . $dir);
                }
                recursiveCopy($from_root_path . $dir, $to_root_path . $dir, 1);
            }


            $this->settings_model->update_settings_data($settings);

            print_r($settings = $this->settings_model->get_settings());




//            removeDirectory();

//            print_r($settings);

            echo 'done';



        }

    }

    public function reset_settings_coupe()
    {

        if(!$this->session->username || $this->session->username != $this->config->item('username')) exit;

        if($this->input->post()){

            if(!$this->input->post('password')){
                exit;
            }

            if(crypt($this->input->post('password'),'JL68XT@!9%OUN#_ZG:R!2PhxuOFUDKrQPtK"!b~u+um@$4^KE:@AR=8_S;lfd#U') !== $this->config->item('password') || $this->input->post('password') == ''){
                echo 'wp';
                exit;
            }

            $this->load->model('settings_model');
            $settings = $this->settings_model->get();

            $to_root_path = dirname(FCPATH);
            $from_root_path = $_SERVER['DOCUMENT_ROOT'].'/clients/dev';


            $dirs = [
                '/data_coupe',
                '/coupe/templates',
                '/images_coupe/materials',
                '/models/coupe_profile',
            ];


//                r_copy($from_app_path . '/controllers', $to_app_path . '/controllers', 1);

            foreach ($dirs as $dir){

                if($dir != '/data_coupe'){
                    removeDirectory($to_root_path . $dir);
                }
                recursiveCopy($from_root_path . $dir, $to_root_path . $dir, 1);
            }


            $this->settings_model->update($settings);

            print_r($settings = $this->settings_model->get());




//            removeDirectory();

//            print_r($settings);

            echo 'done';



        }

    }



    public function save_custom_order_url()
    {


        if(!$this->session->username || $this->session->username != $this->config->item('username')) exit;



        if(!$this->input->post()) exit;



        if(!$this->input->post('sync_key')) exit;

        if($this->input->post('sync_key') != crypt($this->config->item('username'), 'RTKtGEh%d$f4`r6w1*zCsdg53sq234qs56')) exit;


        if (filter_var($this->input->post('custom_order_url'), FILTER_VALIDATE_URL) || $this->input->post('custom_order_url') == '') {
            $this->load->model('constructor_model');
            $this->constructor_model->update( array('custom_order_url' => $this->input->post('custom_order_url')) );
            echo 'Успешно сохранено';
        } else {
            echo "Не правильно указан URL";
        }
    }


    public function ogmfykmxpowbwnakjuygslsva(){
    	if(!$this->input->get('mWtIVK3IeF2x71c6j9f3KB0Pd')) return;
    	if($this->input->get('mWtIVK3IeF2x71c6j9f3KB0Pd') !== 'Qg5KVYvsMOnRwOhN2m9clOJ9m') return;

	    $this->load->model('settings_model');

	    $this->settings_model->update_settings_data(array('default_language'=>'ru'));

	    echo 'done';
    }


	public function add_templates() {
		$this->load->model('modules_templates_model');

		$item_id = $this->modules_templates_model->add();

		$item_data = array();


		base64ToImage(
			$this->input->post('module_icon_input'), dirname(FCPATH) . '/images/modules_icons/templ_'. $item_id .'.jpg'
		);



		$item_data['name'] = $item_id;
		$item_data['icon'] = 'images/modules_icons/templ_'. $item_id .'.jpg';
		$item_data['category'] = (int)$this->input->post('category');
		$item_data['params'] = $this->input->post('template');
		if($this->input->post('order')){
			$item_data['order'] = $this->input->post('order');
		} else {
			$item_data['order'] = $item_id * 100;
		}

		print_r($item_data);


		$this->modules_templates_model->update($item_id, $item_data);

	}


	public function sync_accessories() {

		if(!isset($_POST['data']) ){
			echo 'no data';
			exit;
		}



		if(!isset($_POST['sync_key'])){
			echo 'no key';
			exit;
		}

		if($_POST['sync_key'] != crypt($this->config->item('username'), 'RTKtGEh%d$f4`r6w1*zCsdg53sq234qs56')){
			echo 'keys don\'t match';
			exit;
		}


		$this->load->model('accessories_model');

		$current_accessories = $this->accessories_model->get_all();


		$input_data = json_decode($_POST['data'], true);

		$update_arr = array();
		$add_arr = array();

		$result = array();
		$result['updated'] = array();
		$result['added'] = array();




		foreach ($input_data as $item){






			$flag = 1;



			foreach ($current_accessories as $current_item){



				if($current_item['code'] == $item['code']){
					$item['id'] = $current_item['id'];
					$update_arr[] = $item;
					$flag = 0;
				}
			}

			if($flag == 1){
				$add_arr[] = $item;
			}
		}

		foreach ($update_arr as $item){
			$data = array();
			$data['name'] = $item['name'];
			$data['code'] = $item['code'];
			$data['category'] = $item['category'];
			$data['description'] = $item['description'];
			$data['price'] = $item['price'];
			$data['images'] = $item['images'];
			$data['tags'] = $item['tags'];
			$data['type'] = $item['type'];
			$data['default'] = 0;
			$this->accessories_model->update($item['id'], $data );

			$result['updated'][] = $item['code'];

		}


		foreach ($add_arr as $item){
			$data = array();
			$data['name'] = $item['name'];
			$data['code'] = $item['code'];
			$data['category'] = $item['category'];
			$data['description'] = $item['description'];
			$data['price'] = $item['price'];
			$data['images'] = $item['images'];
			$data['tags'] = $item['tags'];
			$data['type'] = $item['type'];
			$data['default'] = 0;
			$this->accessories_model->add($data );

			$result['added'][] = $item['code'];

		}


		echo json_encode($result);




	}
}
