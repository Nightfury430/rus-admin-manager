<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Module_sets extends CI_Controller {

    public function __construct()
    {
        parent::__construct();
        $this->load->helper('url_helper');
        $this->load->model('module_sets_model');
        $this->load->library('session');

        if(!$this->session->username || $this->session->username != $this->config->item('username')){
            redirect('login', 'refresh');
        }

        if(!$this->config->item('sub_account')) $this->config->set_item('sub_account', false);
        if($this->config->item('sub_account') == true) redirect('settings', 'refresh');
    }

    public function sets_index()
    {
        $sets = $this->module_sets_model->get_sets();

        $data = array();
        $data['items'] = array();

        foreach ($sets as $set){
            $set['is_empty'] = $this->module_sets_model->check_set_is_empty($set['id']);
            $data['items'][] = $set;
        }

        $data['username'] = $this->config->item('username');

//        $data['items'] = $this->module_sets_model->get_sets();


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
        $this->load->view('module_sets/sets/index', $data);
        $this->load->view('templates/footer', $data);
    }

    public function sets_add()
    {
        $this->load->helper('form');
        $this->load->library('form_validation');


        $this->form_validation->set_rules('name', 'Название', 'required');

        if ($this->form_validation->run() === FALSE)
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
            $this->load->view('module_sets/sets/add');
            $this->load->view('templates/footer', $data);
        }
        else
        {
            $data = array();
            $data['name'] = $this->input->post('name');

            $this->module_sets_model->add_set($data);
            redirect('module_sets/sets_index/', 'refresh');
        }
    }

    public function sets_edit($id)
    {
        $this->load->helper('form');
        $this->load->library('form_validation');


        $this->form_validation->set_rules('name', 'Название', 'required');

        if ($this->form_validation->run() === FALSE)
        {

            $data['item'] = $this->module_sets_model->get_set($id);

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
            $this->load->view('module_sets/sets/edit', $data);
            $this->load->view('templates/footer', $data);
        }
        else
        {
            $data = array();
            $data['name'] = $this->input->post('name');

            $this->module_sets_model->edit_set($id, $data);
            redirect('module_sets/sets_index/', 'refresh');
        }
    }

    public function sets_remove($id)
    {
        $this->module_sets_model->remove_set($id);
        redirect('module_sets/sets_index/', 'refresh');
    }

    public function categories_index($set_id)
    {
//        $data['categories'] = buildTree($this->module_sets_model->get_categories_by_set_id($set_id), 'parent', 'id');
        $data['categories'] = $this->module_sets_model->get_categories_by_set_id($set_id);
        $data['set'] = $this->module_sets_model->get_set($set_id);
        $data['set_id'] = $set_id;

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

	    $data['controller'] = 'module_sets';

        $this->load->view('templates/header', $data);
        $this->load->view('common/categories_index_new', $data);
//        $this->load->view('module_sets/categories/index', $data);
        $this->load->view('templates/footer', $data);
    }

    public function categories_add($set_id)
    {
        $this->load->helper('form');
        $this->load->library('form_validation');


        $this->form_validation->set_rules('name', 'название', 'required');
        $this->form_validation->set_rules('parent', 'категория', 'required');

        if ($this->form_validation->run() === FALSE)
        {
            $data['categories'] = $this->module_sets_model->get_categories_by_set_id($set_id);
            $data['set'] = $this->module_sets_model->get_set($set_id);

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
            $this->load->view('module_sets/categories/add', $data);
            $this->load->view('templates/footer', $data);
        }
        else
        {
            $data = array();
            $data['name'] = $this->input->post('name');
            $data['parent'] = $this->input->post('parent');
            $data['active'] = $this->input->post('active');
            $data['set_id'] = $set_id;

            $this->module_sets_model->add_category($data);

            redirect('module_sets/categories_index/'.$set_id, 'refresh');
        }
    }

    public function categories_edit($set_id, $id)
    {

        $this->load->helper('form');
        $this->load->library('form_validation');


        $this->form_validation->set_rules('name', 'название', 'required');
        $this->form_validation->set_rules('parent', 'категория', 'required');

        if ($this->form_validation->run() === FALSE)
        {
            $data['categories'] = $this->module_sets_model->get_categories_by_set_id($set_id);
            $data['item'] = $this->module_sets_model->get_category($id);
            $data['set'] = $this->module_sets_model->get_set($set_id);

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
            $this->load->view('module_sets/categories/edit', $data);
            $this->load->view('templates/footer', $data);
        }
        else
        {
            $data = array();
            $data['name'] = $this->input->post('name');
            $data['parent'] = $this->input->post('parent');
            $data['active'] = $this->input->post('active');
            $data['set_id'] = $set_id;

            $this->module_sets_model->update_category($id, $data);

            redirect('module_sets/categories_index/'.$set_id, 'refresh');
        }
    }

    public function categories_remove($set_id, $id)
    {
        $this->module_sets_model->remove_category($id);
        redirect('module_sets/categories_index/'.$set_id, 'refresh');
    }


    //todo
    public function categories_order_update($set_id)
    {
        if($this->input->post()) $this->module_sets_model->categories_order_update($set_id, json_decode($this->input->post('data'), true));
    }

    //todo
    public function categories_get($set_id)
    {
        echo json_encode($this->module_sets_model->get_categories_by_set_id($set_id));
    }

    //todo
    public function category_add_ajax($set_id)
    {
        if($this->input->post()){
            echo $this->module_sets_model->add_category_ajax(
                array(
                    "set_id" => (int)$set_id,
                    "name" =>  no_xss($this->input->post('name')),
                    "parent" =>  (int)$this->input->post('parent'),
                    "active" =>  (int)$this->input->post('active'),
                    "order" =>  (int)$this->input->post('order')
                )
            );
        }
    }

    //todo
    public function category_edit_ajax()
    {
        if($this->input->post()){
            echo $this->module_sets_model->update_category_ajax(
                (int)$this->input->post('id'),
                array(
                    "name" =>  no_xss($this->input->post('name')),
                    "description" =>  no_xss($this->input->post('description'))
                )
            );
        }
    }

    //todo
    public function category_delete_ajax()
    {
        if($this->input->post()) $this->module_sets_model->remove_category((int)$this->input->post('id'));
    }

    public function categories_set_active_ajax($id, $val) {
        $this->module_sets_model->set_active($id, $val);
    }



//    public function items_index($set_id = false, $top_category = false, $category = false, $per_page = false, $start = false)
//    {
//
//        if($category == null || $per_page == null){
//
//            $category = 0;
//            $per_page = 20;
//
//            redirect('module_sets/items_index/'.$set_id.'/'.$top_category.'/'.$category .'/'.$per_page.'/', 'refresh');
//        }
//
//        $this->load->library('pagination');
//        $config['base_url'] = site_url('module_sets/items_index/'.$set_id.'/'.$top_category.'/'.$category .'/'.$per_page.'/');
//        $config['total_rows'] = $this->module_sets_model->get_items_count($set_id, $top_category, $category);
//        $config['per_page'] = $per_page;
//
//        $config['first_link'] = false;
//        $config['last_link'] = false;
//
//        $config['full_tag_open'] = '<ul class="pagination">';
//        $config['full_tag_close'] = '</ul>';
//
//        $config['next_tag_open'] = '<li>';
//        $config['next_tag_close'] = '</li>';
//
//        $config['num_tag_open'] = '<li>';
//        $config['num_tag_close'] = '</li>';
//
//        $config['cur_tag_open'] = '<li class="active"><a href="#">';
//        $config['cur_tag_close'] = '</a></li>';
//
//        $config['prev_tag_open'] = '<li>';
//        $config['prev_tag_close'] = '</li>';
//
//        $config['num_links'] = 2;
//
//        $config['uri_segment'] = 7;
//
//
//        $data['items'] = $this->module_sets_model->get_items($set_id, $top_category, $category, $start, $config['per_page']);
//
//        $data['set_data'] = $this->module_sets_model->get_set($set_id);
//
//        $data['categories'] = $this->module_sets_model->get_categories_by_parent($top_category);
//        $data['top_category'] = $top_category;
//        $data['set_id'] = $set_id;
//
////        $data['materials_categories'] = buildTree($this->modules_model->get_categories_by_parent($top_category), 'parent', 'id');
//
//        $this->pagination->initialize($config);
//
//        $data['pagination'] = $this->pagination->create_links();
//
//        $data['conf'] = $config;
//
//	    $this->load->model('languages_model');
//	    $data['lang_arr'] = get_default_lang();
//	    if($this->config->item('ini')['language']['language'] !== 'default'){
//		    $custom_lang = json_decode($this->languages_model->get($this->config->item('ini')['language']['language']));
//		    foreach ($data['lang_arr'] as $key=>$value){
//			    if(isset($custom_lang->$key)) {
//				    if(!empty($custom_lang->$key)) $data['lang_arr'][$key] = $custom_lang->$key;
//			    }
//		    }
//	    }
//        $this->load->view('templates/header', $data);
//        $this->load->view('module_sets/modules/index', $data);
//        $this->load->view('templates/footer', $data);
//    }

    public function items_index($set_id = false, $category = false, $per_page = false, $start = false)
    {

        if($category == null || $per_page == null){

            $category = 0;
            $per_page = 20;

            redirect('module_sets/items_index/'.$set_id.'/'.$category .'/'.$per_page.'/', 'refresh');
        }

        $this->load->library('pagination');
        $config['base_url'] = site_url('module_sets/items_index/'.$set_id.'/'.$category .'/'.$per_page.'/');
        $config['total_rows'] = $this->module_sets_model->get_items_count($set_id, $category);
        $config['per_page'] = $per_page;
        $config['first_link'] = false;
        $config['last_link'] = false;

        $config['full_tag_open'] = '<ul class="pagination">';
        $config['full_tag_close'] = '</ul>';

        $config['next_tag_open'] = '<li>';
        $config['next_tag_close'] = '</li>';

        $config['num_tag_open'] = '<li>';
        $config['num_tag_close'] = '</li>';

        $config['cur_tag_open'] = '<li class="active"><a href="#">';
        $config['cur_tag_close'] = '</a></li>';

        $config['prev_tag_open'] = '<li>';
        $config['prev_tag_close'] = '</li>';

        $config['num_links'] = 5;

        $config['uri_segment'] = 6;


        $data['items'] = $this->module_sets_model->get_items($set_id, $category, $start, $config['per_page']);

//        print_pre($data['items']);

        $data['set_data'] = $this->module_sets_model->get_set($set_id);

        $data['categories'] = $this->module_sets_model->get_categories_by_set_id($set_id);
//        $data['top_category'] = $top_category;
        $data['cat_tree'] = buildTree($data['categories'], 'parent', 'id');
        $data['set_id'] = $set_id;

//        $data['materials_categories'] = buildTree($this->modules_model->get_categories_by_parent($top_category), 'parent', 'id');

        $this->pagination->initialize($config);

        $data['pagination'] = $this->pagination->create_links();

        $data['conf'] = $config;

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
        $this->load->view('module_sets/modules/index', $data);
        $this->load->view('templates/footer', $data);
    }


    public function items_remove($set_id, $id)
    {

        $item = $this->module_sets_model->get_item($id);

        if($item['is_custom_template'] != 1) {
            if (strpos($item['icon'], 'common_assets') === false) {
                if (file_exists(dirname(FCPATH) . '/' . $item['icon'])) {
                    unlink(dirname(FCPATH) . '/' . $item['icon']);
                }
            }
        }

        $this->module_sets_model->remove_items($id);
        redirect('module_sets/items_index/'.$set_id.'/', 'refresh');
    }

    public function ajax_export($id)
    {
        $set = $this->module_sets_model->get_set($id);

        $modules = $this->module_sets_model->get_all_items_by_set_id($id);

        $csv_data = array();

        foreach ($modules as $module){
            $params = json_decode($module['params'])->params->variants;

            foreach ($params as $variant){
                $csv_array = array();
                $csv_array[] = $module['id'];
                $csv_array[] = $variant->code;
                $csv_array[] = $variant->name;
                $csv_array[] = $variant->width.'x'.$variant->height.'x'.$variant->depth;
                $csv_array[] = $variant->price;

                if($variant->default == 1){
                    $csv_array[] = $variant->default;
                } else {
                    $csv_array[] = 0;
                }



                $csv_data[] = $csv_array;
            }


        }

        header('Content-Type: text/csv; charset=utf-8');
        header('Content-Disposition: attachment; filename=data.csv');

        $output = fopen('php://output', 'w');
        fputcsv($output, array('Модули '. $set['name'], '', '', ''));
        fputcsv($output, array('Внутренний id (не менять)', 'Артикул', 'Название', 'Размеры', 'Цена', 'Размер по умолчанию?'));

        foreach ($csv_data as $row) {
            fputcsv($output, $row);
        }

    }

    public function ajax_import($id)
    {
        if(isset($_FILES)){
            $modules_data = readCSV($_FILES['file']['tmp_name']);
            array_shift($modules_data);
            $id_arr = array();

            foreach($modules_data as $data){

                if(!isset($data[0])) continue;

                if( (int)$data[0] != 0 ){
                    $id_arr[] = (int)$data[0];
                }
            }

            $id_arr = array_unique($id_arr);

            $final_arr = array();

            foreach ( $id_arr as $id ){
                $final_arr[$id] = array();
            }



            foreach ($modules_data as &$mod){
                if(empty($mod)) continue;
                foreach ($mod as &$m){
                    if(mb_detect_encoding($m, 'windows-1251, UTF-8') != 'UTF-8'){
                        $m = iconv('windows-1251',"UTF-8", $m);
                    }
                }
                if((int)$mod[0] != 0){
                    $final_arr[$mod[0]][] = $mod;
                }
            }
            foreach ( $final_arr as $key=>$value ){
                $module = $this->module_sets_model->get_item($key);
                if($module != null){
                    $params = json_decode($module['params']);
                    $variants = array();
                    foreach ($value as $ind=>&$mod){
                        if(!isset($mod[5])) $mod[5] = 0;
                        $var = new stdClass();
                        $var->code = $mod[1];
                        $var->name =  mb_convert_encoding($mod[2], 'UTF-8');
                        $sizes = explode('x', $mod[3]);
                        $var->width = $sizes[0];
                        $var->height = $sizes[1];
                        $var->depth = $sizes[2];
                        $var->price = $mod[4];
                        $var->default = $mod[5];

                        if(isset($params->params->variants[$ind])){
                            if(isset($params->params->variants[$ind]->width2)){
                                $var->width2 = $params->params->variants[$ind]->width2;
                            }
                        }

                        $variants[] = $var;
                    }
                    $params->params->variants = $variants;

                    $data = array();
                    $data['params'] = json_encode($params);



                    $this->module_sets_model->add_item_data($key, $data);


                }
            }

            echo 'ok';
        }
    }

    public function copy_set($from_id, $to_id)
    {
//        echo $from_id;
//        echo '<br>';
//        echo $to_id;
//
//
//        exit;

        $from_set = $this->module_sets_model->get_set($from_id);
        $to_set = $this->module_sets_model->get_set($to_id);

        $from_set_categories = $this->module_sets_model->get_categories_by_set_id($from_id);
        $to_set_categories = $this->module_sets_model->get_categories_by_set_id($to_id);

//        $from_set_bottom_categories = $this->module_sets_model->get_categories_by_parent($from_set['bottom_modules_id']);
//        $from_set_top_categories = $this->module_sets_model->get_categories_by_parent($from_set['top_modules_id']);
//        $from_set_penals_categories = $this->module_sets_model->get_categories_by_parent($from_set['penals_id']);



        foreach ($to_set_categories as $cat){
            $this->module_sets_model->remove_category($cat['id']);
        }

        $cat_hash = [];

        $child_cats = [];

        foreach ($from_set_categories as $cat){
            $new_cat = $cat;
            $new_cat['set_id'] = $to_set['id'];
            $old_id = $new_cat['id'];

            if($new_cat['parent']){
                $child_cats[] = $new_cat;
            } else {
                unset($new_cat['id']);
                $new_id = $this->module_sets_model->add_category_ajax($new_cat);
                $cat_hash[$old_id] = $new_id;
            }
//            $this->module_sets_model->add_category($data);
        }
        foreach ($child_cats as $cat){
            $new_cat = $cat;
            $new_cat['set_id'] = $to_set['id'];
            $new_cat['parent'] = $cat_hash[$cat['parent']];
            $old_id = $new_cat['id'];
            unset($new_cat['id']);
            $new_id = $this->module_sets_model->add_category_ajax($new_cat);
            $cat_hash[$old_id] = $new_id;
        }

        $modules = $this->module_sets_model->get_all_items_by_set_id($from_id);

        foreach ($modules as $module){
            $new_module = $module;

            unset($module['id']);
            $module['category'] = $cat_hash[$module['category']];
            $module['set_id'] = $to_id;
            $this->module_sets_model->add_item_with_data($module);
        }

        $from_set_categories = $this->module_sets_model->get_categories_by_set_id($from_id);
        $to_set_categories = $this->module_sets_model->get_categories_by_set_id($to_id);



//        print_pre($from_set_categories);
//        print_pre($to_set_categories);
//        print_pre($cat_hash);

        exit;

        if(count($to_set_categories) != count($from_set_categories)){

            $to_set_add_bottom_categories = array();
            $to_set_add_top_categories = array();
            $to_set_add_penals_categories = array();


            foreach ($from_set_bottom_categories as $cat){
                $to_set_add_bottom_categories[] = $cat['name'];
            }

            foreach ($from_set_top_categories as $cat){
                $to_set_add_top_categories[] = $cat['name'];
            }

            foreach ($from_set_penals_categories as $cat){
                $to_set_add_penals_categories[] = $cat['name'];
            }

            foreach ($to_set_add_bottom_categories as $cat){
                $data = array();
                $data['name'] = $cat;
                $data['parent'] = $to_set['bottom_modules_id'];
                $data['active'] = 1;
                $data['set_id'] = $to_set['id'];

                $this->module_sets_model->add_category($data);
            }

            foreach ($to_set_add_top_categories as $cat){
                $data = array();
                $data['name'] = $cat;
                $data['parent'] = $to_set['top_modules_id'];
                $data['active'] = 1;
                $data['set_id'] = $to_set['id'];

                $this->module_sets_model->add_category($data);
            }

            foreach ($to_set_add_penals_categories as $cat){
                $data = array();
                $data['name'] = $cat;
                $data['parent'] = $to_set['penals_id'];
                $data['active'] = 1;
                $data['set_id'] = $to_set['id'];

                $this->module_sets_model->add_category($data);
            }

        }

        $to_set_categories = $this->module_sets_model->get_categories_by_set_id($to_id);

        $to_set_bottom_categories = $this->module_sets_model->get_categories_by_parent($to_set['bottom_modules_id']);
        $to_set_top_categories = $this->module_sets_model->get_categories_by_parent($to_set['top_modules_id']);
        $to_set_penals_categories = $this->module_sets_model->get_categories_by_parent($to_set['penals_id']);


        $from_set_modules = $this->module_sets_model->get_all_items_by_set_id($from_id);
        $to_set_modules = $this->module_sets_model->get_all_items_by_set_id($to_id);

        $from_set_bottom_categories[] = $this->module_sets_model->get_category($from_set['bottom_modules_id']);
        $to_set_bottom_categories[] = $this->module_sets_model->get_category($to_set['bottom_modules_id']);

        $from_set_top_categories[] = $this->module_sets_model->get_category($from_set['top_modules_id']);
        $to_set_top_categories[] = $this->module_sets_model->get_category($to_set['top_modules_id']);

        $from_set_top_categories[] = $this->module_sets_model->get_category($from_set['penals_id']);
        $to_set_top_categories[] = $this->module_sets_model->get_category($to_set['penals_id']);


        if(count($from_set_modules) != count($to_set_modules)){

            $from_set_bottom_modules = array();
            $from_set_top_modules = array();
            $from_set_penals = array();

            foreach ($from_set_modules as $module){
                foreach ($from_set_bottom_categories as $cat){
                    if($module['category'] == $cat['id']){
                        $from_set_bottom_modules[] = $module;
                    }
                }

                foreach ($from_set_top_categories as $cat){
                    if($module['category'] == $cat['id']){
                        $from_set_top_modules[] = $module;
                    }
                }

                foreach ($from_set_penals_categories as $cat){
                    if($module['category'] == $cat['id']){
                        $from_set_penals[] = $module;
                    }
                }
            }






            foreach ($from_set_bottom_modules as $module){
                $cat_name = '';
                $to_cat_id = 0;


                foreach ($from_set_bottom_categories as $cat){

                    if($module['category'] == $cat['id']){
                        $cat_name = $cat['name'];
                    }
                }



                foreach ($to_set_bottom_categories as $cat){
                    if($cat['name'] == $cat_name){
                        $to_cat_id = $cat['id'];
                    }
                }

                $item_id = $this->module_sets_model->add_item();

//                copy(dirname(FCPATH).'/images/module_sets_modules_icons/'.$module['id'].'.png', dirname(FCPATH).'/images/module_sets_modules_icons/'.$item_id.'.png');

                $new_module_data = array();
                $new_module_data['category'] = $to_cat_id;
//                $new_module_data['icon'] = 'images/module_sets_modules_icons/'.$item_id.'.png';
                $new_module_data['icon'] = $module['icon'];

                $params = json_decode($module['params']);
//                $params->params->variants[0]->code = $item_id;

                $new_module_data['params'] = json_encode($params);
                $new_module_data['active'] = $module['active'];
                $new_module_data['template_id'] = $module['template_id'];
                $new_module_data['order'] = $item_id * 100;
                $new_module_data['set_id'] = $to_set['id'];
                $this->module_sets_model->add_item_data($item_id, $new_module_data);
            }

            foreach ($from_set_top_modules as $module){
                $cat_name = '';
                $to_cat_id = 0;

                foreach ($from_set_top_categories as $cat){
                    if($module['category'] == $cat['id']){
                        $cat_name = $cat['name'];
                    }
                }

                foreach ($to_set_top_categories as $cat){
                    if($cat['name'] == $cat_name){
                        $to_cat_id = $cat['id'];
                    }
                }

                $item_id = $this->module_sets_model->add_item();

//                copy(dirname(FCPATH).'/images/module_sets_modules_icons/'.$module['id'].'.png', dirname(FCPATH).'/images/module_sets_modules_icons/'.$item_id.'.png');

                $new_module_data = array();
                $new_module_data['category'] = $to_cat_id;
//                $new_module_data['icon'] = 'images/module_sets_modules_icons/'.$item_id.'.png';
                $new_module_data['icon'] = $module['icon'];

                $params = json_decode($module['params']);
//                $params->params->variants[0]->code = $item_id;

                $new_module_data['params'] = json_encode($params);
                $new_module_data['active'] = $module['active'];
                $new_module_data['template_id'] = $module['template_id'];
                $new_module_data['order'] = $item_id * 100;
                $new_module_data['set_id'] = $to_set['id'];
                $this->module_sets_model->add_item_data($item_id, $new_module_data);
            }

            foreach ($from_set_penals as $module){
                $cat_name = '';
                $to_cat_id = 0;

                foreach ($from_set_penals_categories as $cat){
                    if($module['category'] == $cat['id']){
                        $cat_name = $cat['name'];
                    }
                }

                foreach ($to_set_penals_categories as $cat){
                    if($cat['name'] == $cat_name){
                        $to_cat_id = $cat['id'];
                    }
                }

                $item_id = $this->module_sets_model->add_item();

//                copy(dirname(FCPATH).'/images/module_sets_modules_icons/'.$module['id'].'.png', dirname(FCPATH).'/images/module_sets_modules_icons/'.$item_id.'.png');

                $new_module_data = array();
                $new_module_data['category'] = $to_cat_id;
//                $new_module_data['icon'] = 'images/module_sets_modules_icons/'.$item_id.'.png';
                $new_module_data['icon'] = $module['icon'];

                $params = json_decode($module['params']);
//                $params->params->variants[0]->code = $item_id;

                $new_module_data['params'] = json_encode($params);
                $new_module_data['active'] = $module['active'];
                $new_module_data['template_id'] = $module['template_id'];
                $new_module_data['order'] = $item_id * 100;
                $new_module_data['set_id'] = $to_set['id'];
                $this->module_sets_model->add_item_data($item_id, $new_module_data);
            }

        }






//        echo '<pre>';
//        print_r($to_set_bottom_modules);
//        echo '</pre>';


    }

    public function return_set_icons($id)
    {

        $this->load->model('modules_templates_model');

        $data = $this->module_sets_model->get_all_items_by_set_id($id);

        foreach ($data as $item){

            $template = $this->modules_templates_model->get_one_common($item['template_id']);

//            $item['icon'] = '/common_assets/v3/images/icons/modules_templates/'.$item['template_id'].'.png';
            $item['icon'] = $template['icon'];

            $this->module_sets_model->add_item_data($item['id'], array('icon'=>$item['icon']));
        }

//        echo '<pre>';
//        print_r($data);
    }

    public function prices_sync()
    {
        if(isset($_POST['iwanttosyncbplannerprices'])){
            $data = $this->module_sets_model->get_all_items();

            echo json_encode($data);
        }
        if(isset($_POST['writealldata'])){

            $data = json_decode($_POST['writealldata']);

            foreach ($data as $item){
               $this->module_sets_model->add_item_data($item->id, array('params'=>$item->params));
            }

            echo 'done';

        }
    }

	public function check_sets_ajax() {
		echo $this->module_sets_model->get_sets_count();
    }

	public function remove_decoration_materials($id) {

		$data = $this->module_sets_model->get_all_items_by_set_id($id);

		foreach ($data as $item){

			$params = json_decode($item['params']);


			if(isset($params->params->decorations)){
				foreach ($params->params->decorations as $decoration){
					unset($decoration->material);
				}


				$item['params'] = json_encode($params);

				print_pre($item);


				$this->module_sets_model->add_item_data($item['id'], array('params'=>$item['params']));
			}
		}
    }


	public function items_add( $set_id, $id = false ) {
    	$this->load->model('modules_templates_model');

//            $data['top_category'] = $top_category;
            $data['set_id'] = $set_id;
            if($id) $data['id'] = $id;


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
            $this->load->view('module_sets/modules/add', $data);
            $this->load->view('templates/footer', $data);
    }


	public function items_add_ajax($set_id, $id = false) {
		$errors = array();

		if(isset($_FILES['icon']) && $_FILES['icon']['size'] > 0){
			$icon_info = is_image($_FILES['icon']);
			if(!$icon_info){
				$errors[] = 'error_not_an_image';
				echo json_encode(array('errors'=> $errors));
				exit;
			}

		}

		if($id){
			$item_id = $id;
		} else {
			$item_id = $item_id = $this->module_sets_model->add_item();
		}


		$data = json_decode($_POST['data'], true);

		$data['set_id'] = $set_id;

		$save_path = dirname(FCPATH).'/images/module_sets_modules_icons';
		$abs_save_path = 'images/module_sets_modules_icons';
		if (!is_dir($save_path)) mkdir($save_path);

		$uploaded_files = array();

		if(isset($_FILES['icon']) && $_FILES['icon']['size'] > 0){
			$ext = pathinfo($_FILES['icon']['name'], PATHINFO_EXTENSION);
			$new_file = $save_path . '/' . $item_id . '.' . $ext;
			if (move_uploaded_file($_FILES['icon']['tmp_name'], $new_file)) {
				$uploaded_files['file_icon'] = array(
					'file' => $abs_save_path . '/' . $item_id . '.' . $ext
				);
			} else {
				$errors[] = 'Error loading file ' . $_FILES['icon']['name'] . '\\n';
			}
		}

		if(isset($uploaded_files['file_icon'])) $data['icon'] = $uploaded_files['file_icon']['file'];

		$data['is_custom_template'] = (int)$data['is_custom_template'];
		if($data['order'] == 0) $data['order'] = $item_id * 100;

        if(isset($_POST['icon'])){
            base64ToImage($_POST['icon'], $save_path . '/' . $item_id . '.jpg');
            $data['icon'] = $abs_save_path . '/' . $item_id . '.jpg';
        }

		$this->module_sets_model->add_item_data($item_id, $data);

		echo json_encode(array('success'=> '1'));
    }


	public function get_item_data_ajax($id) {
		echo json_encode($this->module_sets_model->get_item($id));
	}

//	public function get_data_ajax($set_id, $top_category) {
//
//		$data = array();
//		$this->load->model('modules_templates_model');
//		$data['categories'] = $this->module_sets_model->get_categories_by_parent($top_category);
//		$set = $this->module_sets_model->get_set($set_id);
//		$true_top_category = '';
//		if($set['bottom_modules_id'] == $top_category){
//			$true_top_category = 1;
//		}
//		if($set['top_modules_id'] == $top_category){
//			$true_top_category = 2;
//		}
//		if($set['penals_id'] == $top_category){
//			$true_top_category = 3;
//		}
//
//		$data['modules_templates'] = $this->modules_templates_model->get_items_by_category_common($true_top_category);
//		$data['modules_templates_custom'] = $this->modules_templates_model->get_items_by_category($true_top_category);
//
//		$data['top_category'] = $top_category;
//		$data['set_id'] = $set_id;
//		echo json_encode($data);
//	}




    public function get_data_ajax($set_id) {

        $data = array();
        $this->load->model('modules_templates_model');
        $this->load->model('common_model');
        $data['categories'] = $this->module_sets_model->get_categories_by_set_id($set_id);
        $set = $this->module_sets_model->get_set($set_id);

        $data['accessories'] = $this->common_model->get_data_all_by_order('Accessories', 'ASC');
        $data['accessories_types'] = $this->common_model->get_data_all_by_order('Accessories_types_items', 'ASC');

        $data['modules_templates'] = $this->modules_templates_model->get_all_common();
        $data['modules_templates_custom'] = $this->modules_templates_model->get_all_items();

        $data['set_id'] = $set_id;
        echo json_encode($data);
    }


    public function change_temp()
    {
        $sets = $this->module_sets_model->get_sets();

        foreach ($sets as $set){
            $modules = $this->module_sets_model->get_all_items_by_set_id($set['id']);

            foreach ($modules as $module){
                if (strpos($module['params'], 'mcsshop') !== false) {

                    $params = str_replace('mcsshop', 'mcstyle', $module['params']);

                    $data = array();
                    $data['params'] = $params;

                    $this->module_sets_model->add_item_data($module['id'], $data);

                    print_pre($params);
                }
            }

        }

    }


	public function mass_modules_top($set_id) {

		if(isset($_POST['data'])){
			$modules = $this->module_sets_model->get_items($set_id, 0, 0, 10000);


			foreach ($modules as $module){

                $params = json_decode($module['params'], true);

                if(isset($params['params']['cabinet_group']) && $params['params']['cabinet_group'] == 'top'){
                    $variants = $params['params']['variants'];

                    foreach ( $variants as &$variant ) {
                        if(isset($_POST['depth']) && isset($_POST['depth_to'])){
                            if($variant['depth'] == $_POST['depth'] ){
                                $variant['depth'] = (int)$_POST['depth_to'];
                            }
                        }

                        if(isset($_POST['height']) && isset($_POST['height_to'])){
                            if($variant['height'] == $_POST['height'] ){


                                $variant['height'] = (int)$_POST['height_to'];
                            }
                        }

                    }

                    $params['params']['variants'] = $variants;

                    $this->module_sets_model->add_item_data($module['id'], array('params'=>json_encode($params)));
                } else {
                    continue;
                }

			}
		}

	}
	public function mass_modules_bottom($set_id) {
		if(isset($_POST['data'])) {
			$modules = $this->module_sets_model->get_items($set_id, 0, 0, 10000000);

			foreach ( $modules as $module ) {
                $params = json_decode( $module['params'], true );

                if(!isset($params['params']['cabinet_group']) || $params['params']['cabinet_group'] == 'bottom'){

                    if( isset($params['params']['tabletop']) && isset($params['params']['tabletop']['active']) && $params['params']['tabletop']['active'] == false) continue;

                    $variants = $params['params']['variants'];

                    if(isset($_POST['front_offset'])) {

                        if(isset($params['params']['cabinet'])){
                            if(isset($params['params']['cabinet']['type'])){
                                if(
                                    $params['params']['cabinet']['type'] == 'corner_open' ||
                                    $params['params']['cabinet']['type'] == 'end_radius_open'
                                ){
                                    $params['params']['tabletop']['offset']['back'] = 0;
                                } else if($params['params']['cabinet']['type'] == 'corner'){
                                    $params['params']['tabletop']['offset']['back'] = (int)$_POST['front_offset'];
                                    $params['params']['tabletop']['offset']['right'] = (int)$_POST['front_offset'];
                                }  else if($params['params']['cabinet']['type'] == 'false_facade_facade'){
                                    $params['params']['tabletop']['offset']['front'] = (int)$_POST['front_offset'];
                                } else {
                                    $params['params']['tabletop']['offset']['back'] = (int)$_POST['front_offset'];
                                }
                            } else {
                                $params['params']['tabletop']['offset']['back'] = (int)$_POST['front_offset'];
                            }
                        } else {
                            $params['params']['tabletop']['offset']['back'] = (int)$_POST['front_offset'];
                        }
                    }

                    if(isset($_POST['back_offset'])) {
                        if(isset($params['params']['cabinet'])){
                            if(isset($params['params']['cabinet']['type'])){
                                if(
                                    $params['params']['cabinet']['type'] == 'corner_open' ||
                                    $params['params']['cabinet']['type'] == 'end_radius_open'
                                ){
                                    $params['params']['tabletop']['offset']['front'] = 35;
                                } else if($params['params']['cabinet']['type'] == 'false_facade_facade'){
                                    $params['params']['tabletop']['offset']['back'] = (int)$_POST['back_offset'];
                                }  else {
                                    $params['params']['tabletop']['offset']['front'] = (int)$_POST['back_offset'];
                                }
                            } else {
                                $params['params']['tabletop']['offset']['front'] = (int)$_POST['back_offset'];
                            }
                        } else {
                            $params['params']['tabletop']['offset']['front'] = (int)$_POST['back_offset'];
                        }
                    }

                    foreach ( $variants as &$variant ) {
                        if(isset($_POST['depth']) && isset($_POST['depth_to'])){
                            if($variant['depth'] == $_POST['depth'] ){
                                $variant['depth'] = (int)$_POST['depth_to'];
                            }
                        }

                        if(isset($_POST['height']) && isset($_POST['height_to'])){
                            if($variant['height'] == $_POST['height'] ){


                                $variant['height'] = (int)$_POST['height_to'];
                            }
                        }

//					if(isset($_POST['height'])) $variant['height'] = (int)$_POST['height'];
                    }

                    $params['params']['variants'] = $variants;


                    $this->module_sets_model->add_item_data($module['id'], array('params'=>json_encode($params)));
                } else {
                    continue;
                }



			}

		}

	}


    public function change_params()
    {
        $mods = $this->module_sets_model->get_all_items();

        foreach ($mods as &$m) {
//            print_pre($m['id']);
            $pars = json_decode($m['params']);
            if (!isset($pars->params->decorations) || !count($pars->params->decorations)) continue;
            if (!isset($pars->params->decorations[0]->starting_point_x)) continue;
            if ($pars->params->decorations[0]->starting_point_y == -14) {

                $pars->params->decorations[0]->starting_point_y .= '%';
                $pars->params->decorations[0]->starting_point_x .= '%';

//                print_pre($pars->params->variants[0]->name);

//                $pars->params->decorations[0]->starting_point_x = '70%';
//                $pars->params->decorations[0]->starting_point_y = '-14%';

                $this->module_sets_model->add_item_data($m['id'], array('params'=>json_encode($pars)));
                print_pre($m['id']);
            }




        }

    }

    public function modules_visibility()
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
        $this->load->view('module_sets/modules_visibility');
        $this->load->view('templates/footer', $data);
    }

    public function save_modules_visibility()
    {
        if(isset($_POST)){

            $data = json_decode($_POST['data'], true);


            file_put_contents(dirname(FCPATH).'/data/module_sets/'. $data['id'] .'.json', json_encode($data['set_data']));
            file_put_contents(dirname(FCPATH).'/data/modules_sets_active/'. $data['id'] .'.json', json_encode($data['hash']));

            echo json_encode("");

        }
    }


    public function remove_prices()
    {
        $mods = $this->module_sets_model->get_all_items();

        foreach ($mods as &$m) {
            $pars = json_decode($m['params'], true);

            foreach ($pars['params']['variants'] as &$variant){
                $variant['price'] = 0;
            }

            $this->module_sets_model->add_item_data($m['id'], array('params'=>json_encode($pars)));

        }



    }


    public function mass_by_cat($set_id, $cat_id)
    {
        $modules = $this->module_sets_model->get_items($set_id, $cat_id, 0, 10000);

        print_pre($modules);



        foreach ($modules as &$module){



            $params = json_decode($module['params'], true)['params'];


            if(isset($params['tabletop'])){
                if(isset($params['tabletop']['offset'])){
                    if(isset($params['tabletop']['offset']['front'])){
                        $params['tabletop']['offset']['front'] = 0;
                    }
                }
            }

            if(isset($params['variants'])){
                foreach ($params['variants'] as &$variant){
                    $variant['depth'] = 550;
                }
            }

            if(isset($params['cabinet'])){
                if(isset($params['cabinet']['back_wall_offset'])){
                    $params['cabinet']['back_wall_offset'] = 0;
                }
            }

            $module['params'] = json_encode(array(
                'params' => $params
            ));


            $this->module_sets_model->add_item_data($module['id'], array('params'=>$module['params']));

        }




    }

}


function readCSV($csvFile){
    $file_handle = fopen($csvFile, 'r');


    $delimiters = [";" => 0, "," => 0, "\t" => 0, "|" => 0];

    $firstLine = fgets($file_handle);
    foreach ($delimiters as $delimiter => &$count) {
        $count = count(str_getcsv($firstLine, $delimiter));
    }

    $line_of_text = array();

    $delimiter = array_search(max($delimiters), $delimiters);


    while (!feof($file_handle) ) {
        $line_of_text[] = fgetcsv($file_handle, 0, $delimiter);
    }
    fclose($file_handle);
    return $line_of_text;
}