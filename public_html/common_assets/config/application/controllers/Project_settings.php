<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Project_settings extends CI_Controller {

    public function __construct()
    {
        parent::__construct();
        $this->load->helper('url_helper');
        $this->load->model('project_settings_model');
        $this->load->model('facades_model');
        $this->load->model('materials_model');
        $this->load->model('glass_model');
        $this->load->model('handles_model');
        $this->load->model('common_model');
        $this->load->library('session');

        if(!$this->session->username || $this->session->username != $this->config->item('username')){
            redirect('login', 'refresh');
        }

        if(!$this->config->item('sub_account')) $this->config->set_item('sub_account', false);
        if($this->config->item('sub_account') == true) redirect('settings', 'refresh');
    }

    public function index()
    {

//        $data['facades'] = $this->facades_model->get_all_active_items();
//        $data['materials'] = $this->materials_model->get_active_categories();
//        $data['glass_materials'] = $this->glass_model->get_active_categories();
//        $data['handles'] = $this->handles_model->get_all_active_items();
//        $data['settings'] = $this->project_settings_model->get_settings();



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
        $this->load->view('project_settings_new', $data);
        $this->load->view('templates/footer', $data);

    }

    public function index_new()
    {
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
        $this->load->view('project_settings_new', $data);
        $this->load->view('templates/footer', $data);
    }

    public function get_data_ajax()
    {
        $data = array();
        $data['facades_categories'] = $this->common_model->get_data_all_by_order_select('Facades_categories', 'id, name, parent, active, order');
        $data['facades_items'] = $this->common_model->get_data_all_by_order_select('Facades_items', 'id, name, icon, category, active, materials');
        $data['materials_categories'] = $this->common_model->get_data_all_by_order_select('Materials_categories', 'id, name, parent, active, order');
        $data['materials_items'] = $this->common_model->get_data_all_by_order_select('Materials_items', 'id, name, map, color, category, active, order');
        $data['glass_categories'] = $this->common_model->get_data_all_by_order_select('Glass_categories', 'id, name, parent, active, order');
        $data['glass_items'] = $this->common_model->get_data_all_by_order_select('Glass_items');


        $data['cornice_categories'] = $this->common_model->get_data_all_by_order_select('Cornice_categories', 'id, name, parent, active, order');
        $data['cornice_items'] = $this->common_model->get_data_all_by_order_select('Cornice_items', 'id, name, icon, category, active');


        $data['handles'] = $this->common_model->get_data_all_by_order_select('Handles_items', 'id, name, icon, category, active, variants');
        $data['settings'] = $this->project_settings_model->get_settings();



        echo json_encode($data);

    }

    public function save_data()
    {
        if(isset($_POST)){

            $data['selected_facade_model'] = $_POST['selected_facade_model'];
            $data['selected_material_facade'] = $_POST['selected_material_facade'];

            $data['available_materials_corpus'] = json_encode($_POST['available_materials_corpus']);
            $data['selected_material_corpus'] = $_POST['selected_material_corpus'];

            print_pre($_POST['available_corpus_thickness']);

            $data['available_corpus_thickness'] = json_encode($_POST['available_corpus_thickness']);
            $data['default_corpus_thickness'] = $_POST['default_corpus_thickness'];

            $data['available_materials_glass'] = json_encode($_POST['available_materials_glass']);
            $data['selected_material_glass'] = $_POST['selected_material_glass'];

            $data['cokol_height'] = $_POST['cokol_height'];
            $data['available_materials_cokol'] = json_encode($_POST['available_materials_cokol']);
            $data['cokol_as_corpus'] = $_POST['cokol_as_corpus'];
            if($data['cokol_as_corpus'] == 1){
                $data['selected_material_cokol'] = $data['selected_material_corpus'];
            } else {
                $data['selected_material_cokol'] = $_POST['selected_material_cokol'];
            }

            $data['tabletop_thickness'] = $_POST['tabletop_thickness'];
            $data['available_materials_tabletop'] = json_encode($_POST['available_materials_tabletop']);
            $data['selected_material_tabletop'] = $_POST['selected_material_tabletop'];

            $data['wallpanel_active'] = $_POST['wallpanel_active'];
            $data['wallpanel_height'] = $_POST['wallpanel_height'];
            $data['available_materials_wallpanel'] = json_encode($_POST['available_materials_wallpanel']);
            $data['selected_material_wallpanel'] = $_POST['selected_material_wallpanel'];

            $data['handle_orientation'] = $_POST['handle_orientation'];
            $data['handle_lockers_position'] = $_POST['handle_lockers_position'];
            $data['handle_selected_model'] = $_POST['handle_selected_model'];
            $data['handle_preferable_size'] = $_POST['handle_preferable_size'];

            $data['available_materials_walls'] = json_encode($_POST['available_materials_walls']);
            $data['selected_material_walls'] = $_POST['selected_material_walls'];

            $data['available_materials_floor'] = json_encode($_POST['available_materials_floor']);
            $data['selected_material_floor'] = $_POST['selected_material_floor'];

            print_pre($data);

            $this->project_settings_model->update_settings($data);
        }
    }

    public function save_data_new()
    {
        if(isset($_POST)){
//            print_pre($_POST);

            $this->project_settings_model->update_settings($_POST);
            echo json_encode('ok');
        }
    }

	public function coupe_project_settings_index() {

		if($this->config->item('ini')['coupe']['available']!=1) redirect('/settings/coupe_account_settings_index', 'refresh');

    	$data = array();

		$data['categories'] = $this->materials_model->coupe_get_active_categories();
		$data['materials'] = $this->materials_model->coupe_get_all_active_items();
		$data['settings'] = $this->project_settings_model->get_coupe();
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
		$this->load->view('/coupe/project_settings', $data);
		$this->load->view('templates/footer', $data);
    }

	public function coupe_project_settings_update() {
		if($this->config->item('ini')['coupe']['available']!=1) exit;

		if(!$this->input->post()) exit;

		$this->project_settings_model->update_coupe($this->input->post());

		echo json_encode(['success' => 'true']);
    }

}
