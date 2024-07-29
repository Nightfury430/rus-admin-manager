<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Constructor extends CI_Controller {

    public function __construct()
    {
        parent::__construct();
        $this->load->helper('url_helper');
        $this->load->model('constructor_model');
        $this->load->library('session');

        if(!$this->session->username || $this->session->username != $this->config->item('username')){
            redirect('login', 'refresh');
        }


    }

    public function index()
    {

        $this->load->model('kitchen_models_model');

        $data['kitchen_models'] = $this->kitchen_models_model->get_all_active_items();
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
        $this->load->view('templates/header', $data);
        $this->load->view('/constructor_settings', $data);
        $this->load->view('templates/footer', $data);

    }

	public function index_coupe() {

		$data['settings'] = $this->constructor_model->get_coupe();


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
		$this->load->view('coupe/header', $data);
		$this->load->view('coupe/constructor_settings', $data);
		$this->load->view('templates/footer', $data);
    }

	public function get_coupe_settings_ajax() {
		$data['settings'] = $this->constructor_model->get_coupe();

		echo json_encode($data);
    }

	public function get_settings_ajax() {
		$this->load->model('kitchen_models_model');

		$data['kitchen_models'] = $this->kitchen_models_model->get_all_active_items();
		$data['settings'] = $this->constructor_model->get();

		echo json_encode($data);
	}

    public function get_constructor_settings() {

        $data = $this->constructor_model->get();

        echo json_encode($data);
    }


    public function save_data()
    {
        if(isset($_POST)){

//        	print_r( json_decode($_POST['settings']) );
//        	print_r($_FILES);
//        	$data =  array();
//	        foreach (json_decode($_POST['settings'])  as $key=>$value){
//		        $data[$key] = $value;
//	        }

            $data['settings'] = $_POST['settings'];


//	        if($data['shop_mode'] == 1){
//
//	        } else {
//		        $data['allow_common_mode'] = 0;
//	        }
//	        if($data['use_custom_reflection'] != 1){
//		        $data['custom_reflection_image'] = null;
//	        }
//	        print_r($data);
//            $data['sizes_limit'] = json_encode($data['sizes_limit']);
	        $this->constructor_model->update($data);

        }
    }

	public function save_data_coupe()
	{
		if(isset($_POST)){

			print_r( json_decode($_POST['settings']) );
			print_r($_FILES);

			$data =  array();

			foreach (json_decode($_POST['settings'])  as $key=>$value){
				$data[$key] = $value;
			}



			if(isset($_FILES['reflection_image']) && $_FILES['reflection_image']['size'] > 0) {
				$icon_info = is_image($_FILES['reflection_image']);
				if(!$icon_info) $errors[] = 'Недопустимый файл иконки';
			}

			if(isset($_FILES['reflection_image']) && $_FILES['reflection_image']['size'] > 0) {
				$save_path     = dirname( FCPATH ) . '/images_coupe/';
				$abs_save_path = 'images_coupe/';
				if ( ! is_dir( $save_path ) ) {
					mkdir( $save_path );
				}

				$extension     = strtolower( pathinfo( $_FILES['reflection_image']['name'] )['extension'] );
				$new_file_icon = $save_path . 'reflect.' . $extension;

				if ( move_uploaded_file( $_FILES['reflection_image']['tmp_name'], $new_file_icon ) ) {
					$data['custom_reflection_image'] = $this->config->item('const_path') . $abs_save_path . 'reflect.' . $extension;
				} else {
					$errors[] = 'Ошибка при загрузке файла ' . $_FILES['reflection_image']['name'];
				}
			}

			if($data['use_custom_reflection'] != 1){
				$data['custom_reflection_image'] = null;
			}

			print_r($data);

			$this->constructor_model->update_coupe($data);

		}
	}

}
