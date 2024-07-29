<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Kitchen_models extends CI_Controller {

    public function __construct()
    {
        parent::__construct();
        $this->load->helper('url_helper');
        $this->load->model('kitchen_models_model');
        $this->load->model('constructor_model');
        $this->load->library('session');

        if(!$this->session->username || $this->session->username != $this->config->item('username')){
            redirect('login', 'refresh');
        }

        if(!$this->config->item('sub_account')) $this->config->set_item('sub_account', false);
        if($this->config->item('sub_account') == true) redirect('settings', 'refresh');
    }

    public function index()
    {
	    $this->load->model('module_sets_model');

        $data['items'] = $this->kitchen_models_model->get_all_items();
        $data['settings'] = $this->constructor_model->get();
        if(isset($data['settings']['settings'])){
            $data['settings'] = json_decode($data['settings']['settings'], true);
        }
		$data['modules_sets'] = $this->db->count_all('Module_sets');

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
        $this->load->view('kitchen_models/index', $data);
        $this->load->view('templates/footer', $data);
    }



    public function add()
    {

        $this->load->helper('form');
        $this->load->library('form_validation');

        $this->form_validation->set_rules('name', 'название', 'required');


        if ($this->form_validation->run() === FALSE)
        {

            $this->load->model('facades_model');
            $this->load->model('materials_model');
            $this->load->model('handles_model');
            $this->load->model('module_sets_model');
            $this->load->model('constructor_model');
            $this->load->model('glass_model');
            $data['facades_categories'] = $this->facades_model->get_active_categories();
            $data['facades'] = $this->facades_model->get_all_items();
            $data['settings'] = $this->constructor_model->get();
            if(isset($data['settings']['settings'])){
                $data['settings'] = json_decode($data['settings']['settings'], true);
            }
            $data['materials'] = $this->materials_model->get_active_categories();
            $data['glass_materials'] = $this->glass_model->get_active_categories();
            $data['handles'] = $this->handles_model->get_all_active_items();
            $data['handles_categories'] = $this->handles_model->get_categories();
            $data['module_sets'] = $this->module_sets_model->get_sets();


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
            $this->load->view('kitchen_models/add', $data);
            $this->load->view('templates/footer', $data);
        }
        else
        {

            $item_id = $this->kitchen_models_model->add();

            $data = array();

            $data['name'] = $this->input->post('name');
            $data['door_offset'] = $this->input->post('door_offset');
            $data['shelve_offset'] = $this->input->post('shelve_offset');
            $data['corpus_thickness'] = $this->input->post('corpus_thickness');
            $data['bottom_modules_height'] = $this->input->post('bottom_modules_height');
            $data['active'] = $this->input->post('active');

            if($this->input->post('order')){
                $data['order'] = $this->input->post('order');
            } else {
                $data['order'] = $item_id * 100;
            }


            //facades
            $data['bottom_as_top_facade_models'] = $this->input->post('bottom_as_top_facade_models');
            if($this->input->post('bottom_as_top_facade_materials') != null){
                $data['bottom_as_top_facade_materials'] = $this->input->post('bottom_as_top_facade_materials');
            } else {
                $data['bottom_as_top_facade_materials'] = 1;
            }

            $data['facades_models_top'] = $this->input->post('facades_models_top');
            $data['facades_models_bottom'] = $this->input->post('facades_models_bottom');
            $data['facades_selected_material_top'] = $this->input->post('facades_selected_material_top');
            $data['facades_selected_material_bottom'] = $this->input->post('facades_selected_material_bottom');
            $data['allow_facades_materials_select'] = $this->input->post('allow_facades_materials_select');

            //glass
            if (is_array($this->input->post('glass_materials'))) {
                $glass_materials = $this->input->post('glass_materials');
            } else {
                $glass_materials = array();
                $glass_materials[] = $this->input->post('glass_materials');
            }

            $data['glass_materials'] = json_encode($glass_materials);

            $data['selected_glass_material'] = $this->input->post('selected_glass_material');
            $data['allow_glass_material_select'] = $this->input->post('allow_glass_materials_select');

            //corpus
            $data['bottom_as_top_corpus_materials'] = $this->input->post('bottom_as_top_corpus_materials');

            if(is_array($this->input->post('corpus_materials_top'))){
                $corpus_materials_top = $this->input->post('corpus_materials_top');
            } else {
                $corpus_materials_top = array();
                $corpus_materials_top[] = $this->input->post('corpus_materials_top');
            }

            $data['corpus_materials_top'] = json_encode($corpus_materials_top);

            $data['selected_corpus_material_top'] = $this->input->post('selected_corpus_material_top');
            $data['selected_corpus_material_bottom'] = $this->input->post('selected_corpus_material_bottom');
            $data['allow_corpus_materials_select'] = $this->input->post('allow_corpus_materials_select');


            //cokol
            $data['cokol_as_corpus'] = $this->input->post('cokol_as_corpus');

            $data['cokol_active'] = $this->input->post('cokol_active');



            if (is_array($this->input->post('cokol_materials'))) {
                $cokol_materials = $this->input->post('cokol_materials');
            } else {
                $cokol_materials = array();
                $cokol_materials[] = $this->input->post('cokol_materials');
            }

            $data['cokol_materials'] = json_encode($cokol_materials);


            if($data['cokol_as_corpus'] == 1){
                $data['selected_cokol_material'] = $this->input->post('selected_corpus_material_bottom');
            } else {
                $data['selected_cokol_material'] = $this->input->post('selected_cokol_material');
            }

            $data['allow_cokol_materials_select'] = $this->input->post('allow_cokol_materials_select');


            //tabletop
            $data['tabletop_thickness'] = $this->input->post('tabletop_thickness');

            if (is_array($this->input->post('tabletop_materials'))) {
                $tabletop_materials = $this->input->post('tabletop_materials');
            } else {
                $tabletop_materials = array();
                $tabletop_materials[] = $this->input->post('tabletop_materials');
            }

            $data['tabletop_materials'] = json_encode($tabletop_materials);

            $data['selected_tabletop_material'] = $this->input->post('selected_tabletop_material');
            $data['allow_tabletop_materials_select'] = $this->input->post('allow_tabletop_materials_select');







            //wallpanel
            $data['wallpanel_active'] = $this->input->post('wallpanel_active');
            $data['wallpanel_height'] = $this->input->post('wallpanel_height');

            if (is_array($this->input->post('wallpanel_materials'))) {
                $wallpanel_materials = $this->input->post('wallpanel_materials');
            } else {
                $wallpanel_materials = array();
                $wallpanel_materials[] = $this->input->post('wallpanel_materials');
            }

            $data['wallpanel_materials'] = json_encode($wallpanel_materials);

            $data['selected_wallpanel_material'] = $this->input->post('selected_wallpanel_material');
            $data['allow_wallpanel_materials_select'] = $this->input->post('allow_wallpanel_materials_select');

            $data['cornice_available'] = $this->input->post('cornice_available');
            $data['cornice_active'] = $this->input->post('cornice_active');


            //handles
            $data['no_handle'] = $this->input->post('no_handle');
            $data['handle_orientation'] = $this->input->post('handle_orientation');
            $data['handle_lockers_position'] = $this->input->post('handle_lockers_position');
            $data['handle_selected_model'] = $this->input->post('handle_selected_model');
            $data['handle_preferable_size'] = $this->input->post('handle_preferable_size');
            $data['allow_handles_select'] = $this->input->post('allow_handles_select');
            $data['available_modules'] = $this->input->post('available_modules');
            $data['cokol_height'] = $this->input->post('cokol_height');

            if(isset($_FILES['icon']) && $_FILES['icon']['size'] > 0) {

                $config['upload_path'] = dirname(FCPATH).'/images/kitchen_models/';
	            $config['allowed_types'] = 'jpg|jpeg|png';
	            $config['file_name'] = $item_id;
                $config['file_ext_tolower'] = true;
                $config['overwrite'] = true;

                $this->load->library('upload', $config);

                if ($this->upload->do_upload('icon'))
                {
                    $fdata = array('upload_data' => $this->upload->data());
                    $data['icon'] = 'images/kitchen_models/' . $fdata['upload_data']['file_name'];

                } else {
                    print_r($this->upload->display_errors());
                    $this->kitchen_models_model->remove($item_id);
	                exit;
                }
            }


//            echo '<pre>';
//            print_r($data);
//            echo '</pre>';
//            exit;


//            $item_id = $this->kitchen_models_model->add();

            if(is_array($this->input->post('facades_categories'))){
                $facades_categories = $this->input->post('facades_categories');
            } else {
                $facades_categories = array();
                $facades_categories[] = $this->input->post('facades_categories');
            }
            $data['facades_categories'] = json_encode($facades_categories);


	        $data['fixed_materials'] = $this->input->post('fixed_materials');


	        $this->kitchen_models_model->update($item_id, $data);

            redirect('kitchen_models/index/', 'refresh');
        }
    }

    public function add_new($id = 0)
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

        $data['controller_name'] = 'kitchen_models';
        $data['id'] = $id;

        $this->load->model('common_model');

        $this->load->view('templates/header', $data);
        $this->load->view('kitchen_models/item', $data);
        $this->load->view('templates/footer', $data);


    }

    public function get_data($id = 0)
    {

        if($id != 0){}
    }

    public function prices_edit($id)
    {
        $this->load->model('facades_model');
        $this->load->model('module_sets_model');

        $data['item'] = $this->kitchen_models_model->get_one($id);

        $tmp = json_decode($data['item']['facades_categories']);

        $tmp2 = array();

        foreach ($tmp as $val){

            $tmp2 = array_merge($tmp2, $this->facades_model->get_items($val,0,100));
        }

        $data['facades'] = $tmp2;



        $data['categories'] = $this->facades_model->get_categories();
        $data['materials'] = buildTree($this->facades_model->get_categories(), 'parent', 'id');

        $data['modules'] = $this->module_sets_model->get_all_items_by_set_id($data['item']['available_modules']);

        $data['id'] = $id;
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
        $this->load->view('kitchen_models/edit prices', $data);
        $this->load->view('templates/footer', $data);
    }

    public function prices_edit2($id)
    {
        $this->load->model('facades_model');
        $this->load->model('module_sets_model');

        $data['item'] = $this->kitchen_models_model->get_one($id);
        $data['id'] = $id;

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
        $this->load->view('kitchen_models/edit prices', $data);
        $this->load->view('templates/footer', $data);
    }


    public function get_prices_data($id)
    {
        $this->load->model('facades_model');
        $this->load->model('module_sets_model');
        $data['item'] = $this->kitchen_models_model->get_one($id);

        $tmp = json_decode($data['item']['facades_categories']);



        $tmp2 = array();

        foreach ($tmp as $val){

            $tmp2 = array_merge($tmp2, $this->facades_model->get_items($val,0,100));
        }


        $data['facades'] = $tmp2;
        $data['categories'] = $this->facades_model->get_categories();
        $data['materials'] = buildTree($this->facades_model->get_categories(), 'parent', 'id');
        $data['modules'] = $this->module_sets_model->get_all_items_by_set_id($data['item']['available_modules']);

        echo json_encode($data);


    }

    public function prices_edit_save($id)
    {
        $data['price_data'] = $this->input->post('data');
        $this->kitchen_models_model->update($id, $data);
    }

    public function edit($id)
    {
        $this->load->helper('form');
        $this->load->library('form_validation');


        $this->form_validation->set_rules('name', 'название', 'required');




        if ($this->form_validation->run() === FALSE)
        {
            $this->load->model('facades_model');
            $this->load->model('materials_model');
            $this->load->model('glass_model');
            $this->load->model('handles_model');
            $this->load->model('module_sets_model');
            $data['facades'] = $this->facades_model->get_all_items();
            $data['settings'] = $this->constructor_model->get();
            if(isset($data['settings']['settings'])){
                $data['settings'] = json_decode($data['settings']['settings'], true);
            }
            $data['facades_categories'] = $this->facades_model->get_active_categories();
            $data['materials'] = $this->materials_model->get_active_categories();
            $data['glass_materials'] = $this->glass_model->get_active_categories();
            $data['handles'] = $this->handles_model->get_all_active_items();
            $data['handles_categories'] = $this->handles_model->get_categories();
            $data['module_sets'] = $this->module_sets_model->get_sets();
            $data['item'] = $this->kitchen_models_model->get_one($id);


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
            $this->load->view('kitchen_models/edit', $data);
            $this->load->view('templates/footer', $data);
        }
        else
        {

            $item_id = $id;

            $data = array();

            $data['name'] = $this->input->post('name');
            $data['door_offset'] = $this->input->post('door_offset');
            $data['shelve_offset'] = $this->input->post('shelve_offset');
            $data['corpus_thickness'] = $this->input->post('corpus_thickness');
            $data['bottom_modules_height'] = $this->input->post('bottom_modules_height');
            $data['active'] = $this->input->post('active');

            if($this->input->post('order')){
                $data['order'] = $this->input->post('order');
            } else {
                $data['order'] = $item_id * 100;
            }

            //facades
            $data['bottom_as_top_facade_models'] = $this->input->post('bottom_as_top_facade_models');
            if($this->input->post('bottom_as_top_facade_materials') != null){
                $data['bottom_as_top_facade_materials'] = $this->input->post('bottom_as_top_facade_materials');
            } else {
                $data['bottom_as_top_facade_materials'] = 1;
            }

            $data['facades_models_top'] = $this->input->post('facades_models_top');
            $data['facades_models_bottom'] = $this->input->post('facades_models_bottom');
            $data['facades_selected_material_top'] = $this->input->post('facades_selected_material_top');
            $data['facades_selected_material_bottom'] = $this->input->post('facades_selected_material_bottom');
            $data['allow_facades_materials_select'] = $this->input->post('allow_facades_materials_select');


            //glass
            if (is_array($this->input->post('glass_materials'))) {
                $glass_materials = $this->input->post('glass_materials');
            } else {
                $glass_materials = array();
                $glass_materials[] = $this->input->post('glass_materials');
            }

            $data['glass_materials'] = json_encode($glass_materials);

            $data['selected_glass_material'] = $this->input->post('selected_glass_material');
            $data['allow_glass_material_select'] = $this->input->post('allow_glass_materials_select');


            //corpus
            $data['bottom_as_top_corpus_materials'] = $this->input->post('bottom_as_top_corpus_materials');

            if(is_array($this->input->post('corpus_materials_top'))){
                $corpus_materials_top = $this->input->post('corpus_materials_top');
            } else {
                $corpus_materials_top = array();
                $corpus_materials_top[] = $this->input->post('corpus_materials_top');
            }

            $data['corpus_materials_top'] = json_encode($corpus_materials_top);

            $data['selected_corpus_material_top'] = $this->input->post('selected_corpus_material_top');
            $data['selected_corpus_material_bottom'] = $this->input->post('selected_corpus_material_bottom');
            $data['allow_corpus_materials_select'] = $this->input->post('allow_corpus_materials_select');


            //cokol
            $data['cokol_as_corpus'] = $this->input->post('cokol_as_corpus');

            $data['cokol_active'] = $this->input->post('cokol_active');

            if (is_array($this->input->post('cokol_materials'))) {
                $cokol_materials = $this->input->post('cokol_materials');
            } else {
                $cokol_materials = array();
                $cokol_materials[] = $this->input->post('cokol_materials');
            }

            $data['cokol_materials'] = json_encode($cokol_materials);


            if($data['cokol_as_corpus'] == 1){
                $data['selected_cokol_material'] = $this->input->post('selected_corpus_material_bottom');
            } else {
                $data['selected_cokol_material'] = $this->input->post('selected_cokol_material');
            }

            $data['allow_cokol_materials_select'] = $this->input->post('allow_cokol_materials_select');


            //tabletop
            $data['tabletop_thickness'] = $this->input->post('tabletop_thickness');

            if (is_array($this->input->post('tabletop_materials'))) {
                $tabletop_materials = $this->input->post('tabletop_materials');
            } else {
                $tabletop_materials = array();
                $tabletop_materials[] = $this->input->post('tabletop_materials');
            }

            $data['tabletop_materials'] = json_encode($tabletop_materials);

            $data['selected_tabletop_material'] = $this->input->post('selected_tabletop_material');
            $data['allow_tabletop_materials_select'] = $this->input->post('allow_tabletop_materials_select');


            //wallpanel
            $data['wallpanel_active'] = $this->input->post('wallpanel_active');
            $data['wallpanel_height'] = $this->input->post('wallpanel_height');

            if (is_array($this->input->post('wallpanel_materials'))) {
                $wallpanel_materials = $this->input->post('wallpanel_materials');
            } else {
                $wallpanel_materials = array();
                $wallpanel_materials[] = $this->input->post('wallpanel_materials');
            }

            $data['wallpanel_materials'] = json_encode($wallpanel_materials);

            $data['selected_wallpanel_material'] = $this->input->post('selected_wallpanel_material');
            $data['allow_wallpanel_materials_select'] = $this->input->post('allow_wallpanel_materials_select');


            $data['cornice_available'] = $this->input->post('cornice_available');
            $data['cornice_active'] = $this->input->post('cornice_active');

            //handles
            $data['no_handle'] = $this->input->post('no_handle');
            $data['handle_orientation'] = $this->input->post('handle_orientation');
            $data['handle_lockers_position'] = $this->input->post('handle_lockers_position');
            $data['handle_selected_model'] = $this->input->post('handle_selected_model');
            $data['handle_preferable_size'] = $this->input->post('handle_preferable_size');
//            $data['handles_categories'] = $this->input->post('handles_categories');
            $data['allow_handles_select'] = $this->input->post('allow_handles_select');
            $data['available_modules'] = $this->input->post('available_modules');
            $data['cokol_height'] = $this->input->post('cokol_height');

            if(isset($_FILES['icon']) && $_FILES['icon']['size'] > 0) {

                $config['upload_path'] = dirname(FCPATH).'/images/kitchen_models/';
	            $config['allowed_types'] = 'jpg|jpeg|png';
	            $config['file_name'] = $item_id;
                $config['file_ext_tolower'] = true;
                $config['overwrite'] = true;

                $this->load->library('upload', $config);

                if ($this->upload->do_upload('icon'))
                {
                    $fdata = array('upload_data' => $this->upload->data());
                    $data['icon'] = 'images/kitchen_models/' . $fdata['upload_data']['file_name'];

                } else {
                    print_r($this->upload->display_errors());
                }
            }


//            echo '<pre>';
//            print_r($data);
//            echo '</pre>';
//            exit;


//            $item_id = $this->kitchen_models_model->add();


            if(is_array($this->input->post('facades_categories'))){
                $facades_categories = $this->input->post('facades_categories');
            } else {
                $facades_categories = array();
                $facades_categories[] = $this->input->post('facades_categories');
            }
            $data['facades_categories'] = json_encode($facades_categories);

	        $data['fixed_materials'] = $this->input->post('fixed_materials');


	        $this->kitchen_models_model->update($item_id, $data);



	        redirect('kitchen_models/index/', 'refresh');
        }
    }


    public function remove($id)
    {
        $item = $this->kitchen_models_model->get_one($id);

        if(file_exists(dirname(FCPATH). '/' . $item['icon'])){
            unlink(dirname(FCPATH). '/' . $item['icon']);
        }

        $this->kitchen_models_model->remove($id);
        redirect('kitchen_models/index/', 'refresh');
    }


    public function get_materials_by_facade_model_ajax($id){
        $this->load->model('materials_model');
        $this->load->model('facades_model');

        $facade = $this->facades_model->get_item($id);

        $facade_materials = json_decode($facade['materials']);

        $materials = array();

        foreach ($facade_materials as $cat){



            $category = $this->materials_model->get_category($cat);
            $items = $this->materials_model->get_items_by_category($cat);

            $tmp = new stdClass();
            $tmp->category = $category;
            $tmp->items = $items;

            $materials[] = $tmp;
        }

        echo json_encode($materials);
    }

    public function get_materials_by_category_ajax()
    {

        $this->load->model('materials_model');

        if($this->input->post('data')){
            $id_array = $this->input->post('data');
        } else {
            $id_array = array();
        }

        $materials = array();

        foreach ($id_array as $cat){
            $category = $this->materials_model->get_category($cat);
            $items = $this->materials_model->get_active_items_by_category($cat);

            $tmp = new stdClass();
            $tmp->category = $category;
            $tmp->items = $items;

            $materials[] = $tmp;
        }


        echo json_encode($materials);
    }

    public function get_glass_materials_by_category_ajax()
    {

        $this->load->model('glass_model');

        if($this->input->post('data')){
            $id_array = $this->input->post('data');
        } else {
            $id_array = array();
        }

        $materials = array();

        foreach ($id_array as $cat){
            $category = $this->glass_model->get_category($cat);
            $items = $this->glass_model->get_active_items_by_category($cat);

            $tmp = new stdClass();
            $tmp->category = $category;
            $tmp->items = $items;

            $materials[] = $tmp;
        }


        echo json_encode($materials);
    }

    public function get_handle_ajax($id)
    {
        $this->load->model('handles_model');

        echo $this->handles_model->get_item($id)['variants'];
    }


	public function check_models_ajax() {
    	echo $this->kitchen_models_model->get_all_items_count();
    }


	public function set_active_ajax($id, $val) {
		$this->kitchen_models_model->set_active($id, $val);
	}


}