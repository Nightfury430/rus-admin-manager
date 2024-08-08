<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Modules extends CI_Controller {

    public function __construct()
    {
        parent::__construct();
        $this->load->helper('url_helper');
        $this->load->model('modules_model');

        $this->load->library('session');
	    $this->load->model('languages_model');
        if(!$this->session->username || $this->session->username != $this->config->item('username')){
            redirect('login', 'refresh');
        }

		$this->load->model('Menu_model');

        if(!$this->config->item('sub_account')) $this->config->set_item('sub_account', false);
        if($this->config->item('sub_account') == true) redirect('settings', 'refresh');
    }


    //todo
    public function categories_index()
    {
//        $data['categories'] = buildTree($this->modules_model->get_categories(), 'parent', 'id');
        $data['categories'] = $this->modules_model->get_categories();


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

        $data['controller'] = 'modules';

        $this->load->view('templates/header', $data);
        $this->load->view('common/categories_index_new', $data);
        $this->load->view('templates/footer', $data);
    }




    public function categories_add()
    {
        $this->load->helper('form');
        $this->load->library('form_validation');


        $this->form_validation->set_rules('name', 'Название', 'required');

        if ($this->form_validation->run() === FALSE)
        {
            $data['categories'] = $this->modules_model->get_categories();

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
            $this->load->view('modules/categories/add', $data);
            $this->load->view('templates/footer', $data);
        }
        else
        {
            $this->modules_model->add_category();
            redirect('modules/categories_index/', 'refresh');
        }
    }

    public function categories_edit($id)
    {

        $this->load->helper('form');
        $this->load->library('form_validation');

        $this->form_validation->set_rules('name', 'Название', 'required');

        if ($this->form_validation->run() === FALSE)
        {
            $data['category'] = $this->modules_model->get_category($id);
            $data['has_children'] = $this->modules_model->check_category_for_children($id);

            if($data['has_children'] == 0){
                $data['categories'] = $this->modules_model->get_categories();
            }

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
            $this->load->view('modules/categories/edit', $data);
            $this->load->view('templates/footer', $data);
        }
        else
        {
            $this->modules_model->update_category($id);
            redirect('modules/categories_index/', 'refresh');
        }
    }

    public function categories_remove($id)
    {
        if($this->modules_model->check_category_for_children($id) == 0){
            $this->modules_model->remove_category($id);
        }

        redirect('modules/categories_index/', 'refresh');
    }

	public function categories_set_active_ajax($id, $val) {
		$this->modules_model->set_category_active($id, $val);
	}

    //todo
    public function categories_order_update()
    {
        if($this->input->post()) $this->modules_model->categories_order_update(json_decode($this->input->post('data'), true));
	}

    //todo
    public function categories_get()
    {
        echo json_encode($this->modules_model->get_categories());
	}

    //todo
    public function category_add_ajax()
    {
        if($this->input->post()){
            echo $this->modules_model->add_category_ajax(
                array(
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
            echo $this->modules_model->update_category_ajax(
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
        if($this->input->post()) $this->modules_model->remove_category((int)$this->input->post('id'));
    }


    public function zetta_import()
    {
        $data = file_get_contents($_SERVER['DOCUMENT_ROOT'] . '/mdata.json');
        $data = json_decode($data, true);

        $m_data = $this->modules_model->get_all_items();

        $this->load->model('accessories_model');
        $a_data = $this->accessories_model->get_all();

        foreach ($m_data as &$item){
            $item['params'] = json_decode($item['params'], true);

            foreach ($item['params']['params']['variants'] as $variant){


                $variant['accessories'] = [];
//                print_pre($variant);

                $flag = 0;

                $from = null;

                foreach ($data as $data_item){
//                    if($data_item['code'] == $variant['code']){
//                        $flag = 1;
//                    }
                    if(strpos($data_item['code'], $variant['code']) !== false){
                        $flag += 1;
                        $from = $data_item;
                    }
                }

                if($flag == 0) {
//                    print_pre($variant['code']);
                } else {

                    print_pre($from);



//                    if($flag > 1){
//                        print_pre($flag);
//                        print_pre($variant['code']);
//                    }
                }



            }

        }



        $a_codes = [];

        foreach ($a_data as $item){
            $a_codes[$item['code']] = $item;
        }

//        print_pre($data);

        foreach ($m_data as &$item){
//            $item['params'] = json_encode($item['params']);
        }


    }


//    public function items_index($top_category = false, $category = false, $per_page = false, $start = false)
//    {
//
//        if($top_category == null){
//            $top_category = 1;
//        }
//
//        if($top_category != 1 && $top_category != 2 && $top_category != 3){
//            $top_category = 1;
//        }
//
//        if($category == null || $per_page == null){
//
//            $category = 0;
//            $per_page = 20;
//
//            redirect('modules/items_index/'.$top_category.'/'.$category .'/'.$per_page.'/', 'refresh');
//        }
//
//	    $_SESSION['selected_modules_category'] = $category;
//	    $_SESSION['selected_modules_per_page'] = $per_page;
//	    $_SESSION['selected_modules_pagination'] = $start;
//
//
//        $this->load->library('pagination');
//        $config['base_url'] = site_url('modules/items_index/'.$top_category.'/'.$category .'/'.$per_page.'/');
//        $config['total_rows'] = $this->modules_model->get_items_count($top_category, $category);
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
//        $config['num_links'] = 5;
//
//        $config['uri_segment'] = 6;
//
////	    $config['per_page'] = 1000;
//
//        $data['items'] = $this->modules_model->get_items($top_category, $category, $start, $config['per_page']);
//
////        print_pre($data['items']);
////        exit;
//
//        $data['categories'] = $this->modules_model->get_categories_by_parent($top_category);
//        $data['top_category'] = $top_category;
//
//
//
////        $data['materials_categories'] = buildTree($this->modules_model->get_categories_by_parent($top_category), 'parent', 'id');
//
//        $this->pagination->initialize($config);
//
//        $data['pagination'] =  $this->pagination->create_links();
//
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
//        $this->load->view('modules/items/index', $data);
//        $this->load->view('templates/footer', $data);
//    }

    public function items_index($category = false, $per_page = false, $start = false)
    {



        if($category == null || $per_page == null){

            $category = 0;
            $per_page = 20;

            redirect('modules/items_index/' .$category .'/'.$per_page.'/', 'refresh');
        }

	    $_SESSION['selected_modules_category'] = $category;
	    $_SESSION['selected_modules_per_page'] = $per_page;
	    $_SESSION['selected_modules_pagination'] = $start;


        $this->load->library('pagination');
        $config['base_url'] = site_url('modules/items_index/' .$category .'/'.$per_page.'/');
        $config['total_rows'] = $this->modules_model->get_items_count($category);
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

        $config['uri_segment'] = 5;

//	    $config['per_page'] = 1000;

        $data['items'] = $this->modules_model->get_items($category, $start, $config['per_page']);

//        print_pre($data['items']);
//        exit;

        $data['categories'] = $this->modules_model->get_categories();
//        $data['top_category'] = $top_category;
        $data['cat_tree'] = buildTree($this->modules_model->get_categories(), 'parent', 'id');


//        $data['materials_categories'] = buildTree($this->modules_model->get_categories_by_parent($top_category), 'parent', 'id');

        $this->pagination->initialize($config);

        $data['pagination'] =  $this->pagination->create_links();


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
        $this->load->view('modules/items/index', $data);
        $this->load->view('templates/footer', $data);
    }

    public function items_add( $id = false)
    {
//	    $this->load->model('modules_templates_model');
//	    $data['categories'] = $this->modules_model->get_categories_by_parent($top_category);
//	    $data['modules_templates'] = $this->modules_templates_model->get_items_by_category_common($top_category);
//	    $data['modules_templates_custom'] = $this->modules_templates_model->get_items_by_category($top_category);

	    if($id) $data['id'] = $id;


	    $this->load->model('languages_model');
	    $data['lang_arr'] = get_default_lang();
	    $data['controller_name'] = 'modules';
	    if($this->config->item('ini')['language']['language'] !== 'default'){
		    $custom_lang = json_decode($this->languages_model->get($this->config->item('ini')['language']['language']));
		    foreach ($data['lang_arr'] as $key=>$value){
			    if(isset($custom_lang->$key)) {
				    if(!empty($custom_lang->$key)) $data['lang_arr'][$key] = $custom_lang->$key;
			    }
		    }
	    }

		$data['js_include'] = [
			'libs/vue.min.js',
			'libs/vue/vue_select/vue-select.js',
			'admin_js/vue/pagination.js',
			'libs/vue/draggable/sortable.min.js',
			'libs/vue/draggable/vuedraggable.min.js',
			'admin_js/vue/filemanager2.js?' . md5(date('m-d-Y-His A e')),
			'admin_js/vue/kitchen/modules.js?' . md5(date('m-d-Y-His A e')),
        ];

        $data['css_include'] = [
			'libs/vue/vue_select/vue-select.css'
        ];
        $data['modules'] = [ '3d_preview' ];
        $data['include'] = 'modules/items/add';
        $data['menus_list'] = $this->Menu_model->get_all_menus();
        $this->load->view('templates/layout', $data);
    }

	public function items_add_ajax($id = false) {

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
			$item_id = $item_id = $this->modules_model->add_item();
		}


		$data = json_decode($_POST['data'], true);

		$save_path = dirname(FCPATH).'/images/modules_icons';
		$abs_save_path = 'images/modules_icons';
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

		$this->modules_model->add_item_data($item_id, $data);

		echo json_encode(array('success'=> '1'));
	}


	public function mass_modules_top() {
    	if(isset($_POST['data'])){
		    $modules = $this->modules_model->get_all_items();

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

                    $this->modules_model->update_item_data($module['id'], array('params'=>json_encode($params)));
                } else {
			        continue;
                }

		    }
	    }
	}
	public function mass_modules_bottom() {

		if(isset($_POST['data'])) {



			$modules = $this->modules_model->get_all_items();

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
                                } else {
                                    $params['params']['tabletop']['offset']['back'] = (int)$_POST['front_offset'];
                                }
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
                                } else {
                                    $params['params']['tabletop']['offset']['front'] = (int)$_POST['back_offset'];
                                }
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

                    $this->modules_model->update_item_data( $module['id'], array( 'params' => json_encode( $params ) ) );
                } else {
                    continue;
                }



			}

		}

	}

	public function mass_modules_penals() {


		$modules = $this->modules_model->get_all_items_by_category(3);

		foreach ($modules as $module){
			$params = json_decode($module['params'], true);

//			if(isset($params['params']['cabinet'])){
//				if(isset($params['params']['cabinet']['back_wall_offset'])){
//					unset($params['params']['cabinet']['back_wall_offset']);
//					if(count($params['params']['cabinet']) == 0) unset($params['params']['cabinet']);
//				}
//			}

			$params['params']['tabletop']['offset']['front'] = 0;
			$params['params']['tabletop']['offset']['back'] = 35;


			$variants = $params['params']['variants'];

			foreach ( $variants as &$variant ) {
				if($variant['depth'] == 530 ){
					$variant['depth'] = 565;
				}
			}

			$params['params']['variants'] = $variants;

			print_pre($params);

			$this->modules_model->update_item_data($module['id'], array('params'=>json_encode($params)));
		}



	}


    public function items_remove($id)
    {

        $item = $this->modules_model->get_item($id);

        if($item['is_custom_template'] != 1){
            if (strpos($item['icon'], 'common_assets') === false) {
                if (file_exists(dirname(FCPATH). '/' . $item['icon'])) {
                    unlink(dirname(FCPATH). '/' . $item['icon']);
                }
            }
        }



        $this->modules_model->remove_items($id);
        redirect('modules/items_index/', 'refresh');
    }


    public function not_active($top_category)
    {

        $this->load->model('modules_templates_model');

        $data = array();

        $modules = $this->modules_model->get_all_items_by_category($top_category);
        $templates = $this->modules_templates_model->get_items_by_category_common($top_category);



        $modules_ids = array();
        $modules_templates_ids = array();

        foreach ($modules as $item){
            $modules_ids[] = $item['template_id'];
        }
        foreach ($templates as $item){
            $modules_templates_ids[] = $item['id'];
        }

        $uniq_ids = array_diff($modules_templates_ids, $modules_ids);

        $uniq = array();


        foreach ($uniq_ids as $item){
            $uniq[] = $this->modules_templates_model->get_one_common($item);
        }

        $data['templates'] = $uniq;
        $data['categories'] = $this->modules_model->get_categories_by_parent($top_category);
        $data['top_category'] = $top_category;


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

		$data['include'] = 'modules/items/not_active';
		$data['modules'] = [];
		$data['menus_list'] = $this->Menu_model->get_all_menus();
		$this->load->view('templates/layout', $data);
    }

    public function not_active_add($id, $cateogry_id, $top_category)
    {
        $this->load->model('modules_templates_model');


        $template = $this->modules_templates_model->get_one_common($id);


        $item_id = $this->modules_model->add_item();

        $data = array();

        $data['category'] = $cateogry_id;
        $data['icon'] = $template['icon'];
        $data['template_id'] = $template['id'];
        $data['params'] = $template['params'];
        $data['active'] = 1;
        $data['order'] = $item_id * 100;

        $this->modules_model->add_item_data($item_id, $data);



        redirect('modules/not_active/' . $top_category, 'refresh');
    }


	public function modules_settings() {

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
		$this->load->model('module_sets_model');

		$data['modules_sets'] = $this->module_sets_model->get_sets();

		$this->load->view('templates/header', $data);
		$this->load->view('modules/settings_new', $data);
		$this->load->view('templates/footer', $data);
    }

    public function get_modules_settings($set_id = false)
    {
        $this->load->model('common_model');
        echo json_encode($this->common_model->get_row('Common_settings')['modules_settings']);
    }

    public function save_modules_settings($set_id = false)
    {
        if($this->input->post('modules_settings')){
            $this->load->model('common_model');
            $this->common_model->update_row('Common_settings', ['modules_settings' => $this->input->post('modules_settings')]);
            echo json_encode(['success' => 'true']);
        }
    }

    public function change_params_all()
    {
        if(!isset($_POST)) die();



        $type = $_POST['type'];
        $group = $_POST['group'];
        $param = $_POST['param'];
        $value = $_POST['value'];

        $modules = $this->modules_model->get_all_items();

        $count = 0;

        foreach ($modules as &$module){

            $flag = 0;

            $module['params'] = json_decode($module['params'], true);

            $params = $module['params']['params'];

            if(!isset($params['cabinet_group'])) $params['cabinet_group'] = 'bottom';

            if(!isset($params['cabinet'])) $params['cabinet'] = [];
            if(!isset($params['cabinet']['type'])) $params['cabinet']['type'] = 'common';

            if($group == 'all' || $group == $params['cabinet_group']){
                if($type == 'all' || $type == $params['cabinet']['type']){

                    switch ($param) {
                        case "side_wall_offset":
                            if(!isset($params['cabinet']['offset'])) $params['cabinet']['offset'] = [];
                            $params['cabinet']['offset']['side'] = (int)$value;
                            $flag = 1;
                            break;
                        case "back_wall_offset":
                            if(!isset($params['cabinet']['offset'])) $params['cabinet']['offset'] = [];
                            $params['cabinet']['offset']['back'] = (int)$value;
                            $flag = 1;
                            break;
                        case "cabinet_depth":
                            $params['cabinet']['depth'] = (int)$value;
                            foreach ($params['variants'] as &$variant){
                                $variant['depth'] = (int)$value;
                            }
                            $flag = 1;
                            break;
                        case "tabletop_depth":
                            if(!isset($params['tabletop'])) $params['tabletop'] = [];
                            $params['tabletop']['depth'] = (int)$value;
                            $flag = 1;
                            break;
                        default:
                            $params['cabinet'][$param] = (int)$value;
                            $flag = 1;
                    }
                }
            }

            if($flag == 1){
                $count++;
                $module['params']['params'] = $params;
                $module['params'] = json_encode($module['params']);
                $this->modules_model->update_item_data($module['id'], $module);
            }
        }

        echo $count;
    }


	public function generate_names() {


    	if(isset($_POST['autogen'])){

		    $modules = $this->modules_model->get_all_items();
		    $categories = $this->modules_model->get_categories();
		    $this->load->model('modules_templates_model');
		    $templates = $this->modules_templates_model->get_all_common();
//		print_pre($categories);
//		print_pre($templates);

		    foreach ($modules as $module){
			    $params = json_decode($module['params']);


			    $template = array();
			    foreach ($templates as $templ){

				    if($module['template_id'] == $templ['id']){
					    $template = $templ;
				    }
			    }



			    $category_name = '';
			    $top_category_name = '';

			    foreach ($categories as $category){
				    if($module['category'] == $category['id']){
					    $category_name = $category['name'];
					    if($category['parent'] != 0){
						    foreach ($categories as $parcat){
							    if($category['parent'] == $parcat['id']){
								    $top_category_name = $parcat['name'];
							    }
						    }
					    }
				    }
			    }



			    if($top_category_name === 'Нижние модули') $top_category_name = 'Нижний модуль';
			    if($top_category_name === 'Верхние модули') $top_category_name = 'Верхний модуль';
			    if($top_category_name === 'Пеналы') $top_category_name = 'Пенал';
			    if($category_name === 'Прямые') $category_name = 'прямой';
			    if($category_name === 'Угловые') $category_name = 'угловой';
			    if($category_name === 'Торцевые') $category_name = 'торцевой';
			    if($category_name === 'Декоративные элементы') $category_name = 'декоративный';


//			print_pre($params);
//			print_pre($top_category_name);

			    $doors_type = '';



			    $doors = '';
			    $doors_code = '';
			    $doors_count = 0;

			    if(isset($params->params->doors)){
				    $doors_count = count($params->params->doors);

				    if($doors_count == 1){
					    $doors = ' ' . $doors_count . ' дверь';
				    }
				    if($doors_count > 1){
					    $doors = ' ' . $doors_count . ' двери';
				    }
				    if($doors_count > 4){
					    $doors = ' ' . $doors_count . ' дверей';
				    }

				    if($doors_count != 0){
					    $doors_code = '-' . $doors_count .'Д';
				    }



			    }


			    $doors_type = '';
			    $doors_type_code = '';

			    if($doors_count > 0){
				    if(isset($params->params->doors[0])){

				    };
			    }

			    $lockers = '';
			    $lockers_code = '';
			    $lockers_count = 0;

			    if(isset($params->params->lockers)){
				    $lockers_count = count($params->params->lockers);

				    if($lockers_count == 1){
					    $lockers = ' ' . $lockers_count . ' ящик';
				    }
				    if($lockers_count > 1){
					    $lockers = ' ' . $lockers_count . ' ящика';
				    }
				    if($lockers_count > 4){
					    $lockers = ' ' . $lockers_count . ' ящиков';
				    }

				    if($lockers_count != 0){
					    $lockers_code = '-' . $lockers_count .'Я';
				    }
			    }

			    foreach ($params->params->variants as $variant){



//				$name = $top_category_name . ' ' . $category_name . $doors . $lockers . ' высота ' . $variant->height;
				    $name = $template['name'];
				    $code = mb_substr(mb_strtoupper($top_category_name), 0, 1,"UTF-8") . mb_substr(mb_strtoupper($category_name), 0, 1,"UTF-8") . $doors_code . $lockers_code . '-' . $variant->width . '-' . $module['id'];

				    $variant->name = $name;
				    $variant->code =  $code;



			    }

			    $module['params'] = json_encode($params);

//			print_pre($module);

			    $this->modules_model->add_item_data($module['id'], $module);

//			print_pre(json_decode($module['params']));
//			print_pre($module);
		    }

		    echo 'done';

	    }



    }

	public function fix_locker_templates() {
//		$this->load->model('modules_templates_model');

		$this->load->model('module_sets_model');


		$templates = $this->modules_model->get_all_items();
		$fl = 0;
		foreach ($templates as $template){

			if($template['is_custom_template'] != 1){
				$params = json_decode($template['params']);
				$t = 0;
				if(!empty($params->params->lockers)){
					foreach ($params->params->lockers as $lock){
						if(!empty($lock->handle_position)){
							if($lock->handle_position == 'middle'){
								$fl++;
								$t = 1;
								unset($lock->handle_position);
							}
						}
					}
				}

				if($t > 0){
					$data = array();
					$data['params'] = json_encode($params);
					$this->modules_model->update_item_data( $template['id'], $data);

					print_pre($params);
				}
			}

		}
		echo '<br>';

		echo $fl;
		echo '<br>';

		$templates = $this->module_sets_model->get_all_items();
		$fl = 0;
		foreach ($templates as $template){

			if($template['is_custom_template'] != 1){
				$params = json_decode($template['params']);
				$t = 0;
				if(!empty($params->params->lockers)){
					foreach ($params->params->lockers as $lock){
						if(!empty($lock->handle_position)){
							if($lock->handle_position == 'middle'){
								$fl++;
								$t = 1;
								unset($lock->handle_position);
							}
						}
					}
				}

				if($t > 0){
					$data = array();
					$data['params'] = json_encode($params);
					$this->module_sets_model->add_item_data( $template['id'], $data);

					print_pre($params);
				}
			}

		}
		echo '<br>';

		echo $fl;
		echo '<br>';

    }


	public function set_active_ajax($id, $val) {
		$this->modules_model->set_active($id, $val);
    }


	public function copy_modules_from_module_set( $id ) {
		$this->load->model('module_sets_model');

		$this->modules_model->clear_modules();
		$cats = $this->modules_model->get_categories();
		foreach ($cats as $cat){
			if((int)$cat['id'] > 3 ) $this->modules_model->remove_category($cat['id']);
		}



		$set = $this->module_sets_model->get_set($id);
		$new_categories = $this->module_sets_model->get_categories_by_set_id($id);
		$new_modules = $this->module_sets_model->get_all_items_by_set_id($id);

		$cat_compat = array();

		foreach ($new_categories as $cat){

			if($cat['parent'] != 0){

				$data = array(
					'name' => $cat['name'],
					'active' => $cat['active']
				);

				if($cat['parent'] == $set['bottom_modules_id']){
					$data['parent'] = 1;
				}

				if($cat['parent'] == $set['top_modules_id']){
					$data['parent'] = 2;
				}

				if($cat['parent'] == $set['penals_id']){
					$data['parent'] = 3;
				}

				$cat_compat[$cat['id']] = $this->modules_model->add_cat($data);

			} else {
				$data = array(
					'name' => $cat['name'],
					'active' => $cat['active']
				);

				if($cat['id'] == $set['bottom_modules_id']){
					$data['parent'] = 1;
				}



				if($cat['id'] == $set['top_modules_id']){
					$data['parent'] = 2;
				}

				if($cat['id'] == $set['penals_id']){
					$data['parent'] = 3;
				}

				$cat_compat[$cat['id']] = $data['parent'];
			}

		}



		print_pre($cat_compat);
//		print_pre($set);
//		print_pre($new_categories);
//		print_pre($new_modules);


		foreach ($new_modules as $module){
			$data = array();

            $data['category'] = $cat_compat[$module['category']];
            $data['icon'] = $module['icon'];
            $data['params'] = $module['params'];
            $data['active'] = $module['active'];
            $data['template_id'] = $module['template_id'];
            $data['order'] = $module['order'];
            $data['is_custom_template'] = $module['is_custom_template'];


//            print_r($module['category']);
//            print_r($data['category']);
//            echo '<br>';


//            continue;

			$this->modules_model->add_item_d($data);



		}

    }




	public function get_item_data_ajax($id) {
		echo json_encode($this->modules_model->get_item($id));
	}

	public function get_data_ajax() {

    	$data = array();
		$this->load->model('modules_templates_model');
        $this->load->model('common_model');
//		$data['categories'] = $this->modules_model->get_categories_by_parent($top_category);
		$data['categories'] = $this->modules_model->get_categories();
//		$data['modules_items'] = $this->modules_model->get_all_items();
//		$data['modules_templates'] = $this->modules_templates_model->get_items_by_category_common($top_category);
		$data['modules_templates'] = $this->modules_templates_model->get_all_common();
//		$data['modules_templates_custom'] = $this->modules_templates_model->get_items_by_category($top_category);
		$data['modules_templates_custom'] = $this->modules_templates_model->get_all_items();

        $data['accessories'] = $this->common_model->get_data_all_by_order('Accessories', 'ASC');
        $data['accessories_types'] = $this->common_model->get_data_all_by_order('Accessories_types_items', 'ASC');

    	echo json_encode($data);
    }

    public function get_modules_for_codes()
    {
        $data = array();
        $data['modules_items'] = $this->modules_model->get_all_items();
        echo json_encode($data);
    }

    public function get_accessories_data_ajax()
    {
        $data = array();
        $data['accessories'] = $this->common_model->get_data_all_by_order('Accessories', 'ASC');
        $data['accessories_types'] = $this->common_model->get_data_all_by_order('Accessories_types_items', 'ASC');

        echo json_encode($data);
    }



	public function export_csv() {


			$modules = $this->modules_model->get_all_active_items();

			$csv_data = array();


			foreach ($modules as $module){
				$params = json_decode($module['params'])->params->variants;

				foreach ($params as $variant){

					$csv_array = array();
					$csv_array[] = isset($variant->code) ? $variant->code : '';
					$csv_array[] = isset($variant->name) ? $variant->name : '';
					$csv_array[] = $variant->width.'x'.$variant->height.'x'.$variant->depth;
//                $csv_array[] = $variant->default;

					$csv_data[] = $csv_array;
				}

			}

			header('Content-Type: text/csv; charset=utf-8');
			header('Content-Disposition: attachment; filename=data.csv');

			$output = fopen('php://output', 'w');
			fputcsv($output, array('Модули ', '', '', ''));
			fputcsv($output, array('Артикул', 'Название', 'Размеры'));

			foreach ($csv_data as $row) {
				fputcsv($output, $row);
			}

	}

	public function change_ovens(){
		$this->load->model('modules_templates_model');

		$this->modules_model->remove_items(160);
		$this->modules_model->remove_items(196);

		$modules = $this->modules_model->get_all_items_by_category(3);


		$ids = array();
		$ids[] = 156;
		$ids[] = 157;
		$ids[] = 158;
		$ids[] = 159;
		$ids[] = 160;
		$ids[] = 161;
		$ids[] = 195;


		$ids[] = 174;
		$ids[] = 175;
		$ids[] = 176;
		$ids[] = 177;
		$ids[] = 178;

		$ids[] = 191;
		$ids[] = 192;
		$ids[] = 193;
		$ids[] = 194;
		$ids[] = 281;

		$items = array();
		$items['items'] = array();

		foreach ($modules as $module){

			$params = json_decode($module['params'], true);

			if( isset($params['params']['oven']) && $params['params']['oven']['active'] == true){

				$flag = 0;

				foreach ($ids as $m_id){
					if($module['id'] == $m_id){
						$flag = 1;

					}
				}

				$template = $this->modules_templates_model->get_one_common($module['template_id']);
				$t_params = json_decode($template['params'], true);

				$p = $t_params['params'];

				$ov_p = '{"model":"/common_assets/models/tech/built_in_cookers/2/model.fbx","material":{"params":{"color":"#ffffff","roughness":"0.8","metalness":"0","map":"/common_assets/models/tech/built_in_cookers/2/map.jpg"},"add_params":{"real_width":"1024","real_height":"1024","stretch_width":"1","stretch_height":"1","wrapping":"mirror"},"type":"Standart"},"width":595,"height":598,"depth":72,"pos":{"y":0},"draggable":"false"}';
				$ov_p = json_decode($ov_p, true);

				if(isset($params['params']['oven']['pY'])){
					$ov_p['pos']['y'] = $params['params']['oven']['pY'];
				} else{
					$ov_p['pos']['y'] = 0;
				}



				$mic_p = '{"model":"/common_assets/models/tech/built_in_cookers/2/mic.fbx","material":{"params":{"color":"#ffffff","roughness":"0.8","metalness":"0","map":"/common_assets/models/tech/built_in_cookers/2/mic_map_w.jpg"},"add_params":{"real_width":"1024","real_height":"1024","stretch_width":"1","stretch_height":"1","wrapping":"mirror"},"type":"Standart"},"width":595,"height":360,"depth":23,"pos":{"y":0},"draggable":"false"}';
				$mic_p = json_decode($mic_p, true);


				if(isset($params['params']['oven']['pY'])){
					$mic_p['pos']['y'] = $params['params']['oven']['pY'] + 600;
				}

				$p['fixed_models'] = array();

				$p['fixed_models'][] = $ov_p;



				if($flag == 1){
					$p['fixed_models'][] = $mic_p;
				}

				unset($p['oven']);


				$variants = json_decode($module['params'], true)['params']['variants'];




				$r_params = array();
				$r_params['params'] = $p;

				$template['params'] = ($p);



//				echo $module['id'] . ' - ' . $template['id'];
//				echo '<br>';

//				print_pre($module['id']);
//				print_pre($template['id']);
				$this->modules_templates_model->remove_common($template['id']);
				$new_template_id = $this->modules_templates_model->add_common();

				$template['id'] = $new_template_id;
				$template['icon'] = '/common_assets/v3/images/icons/modules_templates/'. $new_template_id .'.jpg';

				$items['items'][] = $template;
				$template['params'] = json_encode($r_params);
				$r_params['params']['variants'] = $variants;




				$this->modules_templates_model->update_one_common($template['id'], array(
					'name'=>$template['name'],
					'icon'=>$template['icon'],
					'params'=>$template['params'],
					'category'=>$template['category'],
					'order'=>$template['order'],
					'is_new'=>0,

				));

				$this->modules_model->update_item_data($module['id'], array(
					'template_id' => $new_template_id,
					'icon' => $template['icon'],
					'params' => json_encode($r_params)
				));



			}


		}


		file_put_contents($_SERVER['DOCUMENT_ROOT'] . '/new_items.json', json_encode($items));


		$items = array();
		$items['items'] = array();

		$modules = $this->modules_model->get_all_items_by_category(1);
		foreach ($modules as $module){

			$params = json_decode($module['params'], true);

			if( isset($params['params']['oven']) && $params['params']['oven']['active'] == true){

				$flag = 0;

				foreach ($ids as $m_id){
					if($module['id'] == $m_id){
						$flag = 1;

					}
				}

				$template = $this->modules_templates_model->get_one_common($module['template_id']);
				$t_params = json_decode($template['params'], true);

				$p = $t_params['params'];

				$ov_p = '{"model":"/common_assets/models/tech/built_in_cookers/2/model.fbx","material":{"params":{"color":"#ffffff","roughness":"0.8","metalness":"0","map":"/common_assets/models/tech/built_in_cookers/2/map.jpg"},"add_params":{"real_width":"1024","real_height":"1024","stretch_width":"1","stretch_height":"1","wrapping":"mirror"},"type":"Standart"},"width":595,"height":598,"depth":72,"pos":{"y":0},"draggable":"false"}';
				$ov_p = json_decode($ov_p, true);

				if(isset($params['params']['oven']['pY'])){
					$ov_p['pos']['y'] = $params['params']['oven']['pY'];
				} else{
					$ov_p['pos']['y'] = 0;
					if($module['id'] == 14){
						$ov_p['pos']['y'] = 360;
					}

					if($module['id'] == 272){
						$ov_p['pos']['y'] = 360;
					}

					if($module['id'] == 326){
						$ov_p['pos']['y'] = 120;
					}

					if($module['id'] == 15){
						$ov_p['pos']['y'] = 720;
					}

					if($module['id'] == 293){
						$ov_p['pos']['y'] = 360;
					}
				}



				$mic_p = '{"model":"/common_assets/models/tech/built_in_cookers/2/mic.fbx","material":{"params":{"color":"#ffffff","roughness":"0.8","metalness":"0","map":"/common_assets/models/tech/built_in_cookers/2/mic_map_w.jpg"},"add_params":{"real_width":"1024","real_height":"1024","stretch_width":"1","stretch_height":"1","wrapping":"mirror"},"type":"Standart"},"width":595,"height":360,"depth":23,"pos":{"y":0},"draggable":"false"}';
				$mic_p = json_decode($mic_p, true);


				if(isset($params['params']['oven']['pY'])){
					$mic_p['pos']['y'] = $params['params']['oven']['pY'] + 600;
				}

				$p['fixed_models'] = array();

				$p['fixed_models'][] = $ov_p;



				if($flag == 1){
					$p['fixed_models'][] = $mic_p;
				}

				unset($p['oven']);


				$variants = json_decode($module['params'], true)['params']['variants'];




				$r_params = array();
				$r_params['params'] = $p;

				$template['params'] = ($p);



//				echo $module['id'] . ' - ' . $template['id'];
//				echo '<br>';

//				print_pre($module['id']);
//				print_pre($template['id']);
				$this->modules_templates_model->remove_common($template['id']);
				$new_template_id = $this->modules_templates_model->add_common();

				$template['id'] = $new_template_id;
				$template['icon'] = '/common_assets/v3/images/icons/modules_templates/'. $new_template_id .'.jpg';

				$items['items'][] = $template;
				$template['params'] = json_encode($r_params);
				$r_params['params']['variants'] = $variants;




				$this->modules_templates_model->update_one_common($template['id'], array(
					'name'=>$template['name'],
					'icon'=>$template['icon'],
					'params'=>$template['params'],
					'category'=>$template['category'],
					'order'=>$template['order'],
					'is_new'=>0,

				));

				$this->modules_model->update_item_data($module['id'], array(
					'template_id' => $new_template_id,
					'icon' => $template['icon'],
					'params' => json_encode($r_params)
				));



			}


		}

		file_put_contents($_SERVER['DOCUMENT_ROOT'] . '/new_items_b.json', json_encode($items));

	}


    public function mgs_clean()
    {
        $cfg = array(
            'dsn'	=> 'sqlite:'. $_SERVER['DOCUMENT_ROOT'] .'/clients/dev/data/db',
            'hostname' => 'localhost',
            'username' => '',
            'password' => '',
            'database' => '',
            'dbdriver' => 'pdo',
            'dbprefix' => '',
            'pconnect' => FALSE,
            'db_debug' => (ENVIRONMENT !== 'production'),
            'cache_on' => FALSE,
            'cachedir' => '',
            'char_set' => 'utf8',
            'dbcollat' => 'utf8_general_ci',
            'swap_pre' => '',
            'encrypt' => FALSE,
            'compress' => FALSE,
            'stricton' => FALSE,
            'failover' => array(),
            'save_queries' => TRUE
        );


        $dev_db = $this->load->database($cfg, TRUE);
        $query = $dev_db->get('Modules_items');
        $new_modules = $query->result_array();
        $query = $dev_db->get('Modules_categories');
        $new_categories = $query->result_array();


        $this->load->model('common_model');
        $current_cats = $this->common_model->get_data_all_by_order('Modules_categories', 'ASC');
        $last_cat = $current_cats[count($current_cats)-1];
        $cat_hash = [];

        $current_modules = $this->common_model->get_data_all_by_order('Modules_items', 'ASC');
        foreach ($current_modules as $module){
            $this->common_model->update_where(
                'Modules_items',
                'id',
                $module['id'],
                array(
                    'order'=> 100000 + (int)$module['order']
                )
            );
        }

        //add_new_cat

        $target_cat_id = $this->common_model->add_data('Modules_categories',
            array(
                "name" => 'Cтарые модули',
                "parent" => 0,
                "active" => 1,
                "order" => count($current_cats) + 1
            )
        );
        foreach ($current_cats as $cat){
            if($cat['parent'] == 0){
                $this->common_model->update_where(
                    'Modules_categories',
                    'id',
                    $cat['id'],
                    array(
                        "parent" => $target_cat_id
                    )
                );
            }

        }



        //create_new_cats

        foreach ($new_categories as $cat){
            if($cat['parent'] == 0){
                $new_local_cat_id = $this->common_model->add_data('Modules_categories',
                    array(
                        "name" => $cat['name'],
                        "parent" => $cat['parent'],
                        "active" => $cat['active'],
                        "order" => $cat['order'],
                        "description" => $cat['description'],
                        "image" => $cat['image'],
                    )
                );
                $cat_hash[$cat['id']] = $new_local_cat_id;

            }
        }

        foreach ($new_categories as $cat){
            if($cat['parent'] != 0){
                $new_local_cat_id = $this->common_model->add_data('Modules_categories',
                    array(
                        "name" => $cat['name'],
                        "parent" => $cat_hash[$cat['parent']],
                        "active" => $cat['active'],
                        "order" => $cat['order'],
                        "description" => $cat['description'],
                        "image" => $cat['image'],
                    )
                );
                $cat_hash[$cat['id']] = $new_local_cat_id;

            }
        }


        //copy_modules

        foreach ($new_modules as &$module){
            $this->common_model->add_data('Modules_items', array(
                'category' => $cat_hash[$module['category']],
                'icon' => $module['icon'],
                'params' => $module['params'],
                'active' => $module['active'],
                'template_id' => $module['template_id'],
                'order' => $module['order'],
                'is_custom_template' => $module['is_custom_template'],
            ));

        }


        recursiveCopy($_SERVER['DOCUMENT_ROOT'] . '/clients/dev/images/modules_icons', $_SERVER['DOCUMENT_ROOT'] . '/clients/16458585/images/modules_icons', 1);





        $new_cats = [];






//        $new_cat_id = $this->common_model->add_data('Modules_categories',
//            array(
//                "name" => 'Старые модули',
//                "parent" => 0,
//                "active" => 0,
//                "order" => (int)$last_cat['order'] + 1
//            )
//        );






	}


//	public function convert_to_bmh() {
//		$this->load->model('modules_templates_model');
//
//		$modules = $this->modules_templates_model->get_items_by_category_common(3);
//
//		foreach ($modules as $module){
//			$params = str_replace('720', '"100bmh"', $module['params']);
//			$params = str_replace('360', '"50bmh"', $params);
//			$params = str_replace('180', '"25bmh"', $params);
//			$params = str_replace('540', '"75bmh"', $params);
//			$params = str_replace('270', '"37.5bmh"', $params);
//
//			$this->modules_templates_model->update_one_common($module['id'], array('params'=>$params));
//
//		}
//
//	}
//
//	public function update_templates() {
//		$modules = $this->modules_model->get_all_items_by_category(3);
//		$this->load->model('modules_templates_model');
//		foreach ($modules as $module){
//
//			if($module['is_custom_template'] == 0){
//				$params = json_decode($module['params'], true);
//				$variants = $params['params']['variants'];
//
//				$template =  $this->modules_templates_model->get_one_common($module['template_id']);
//				$template_params = json_decode($template['params'], true);
//
//				$template_params['params']['variants'] = $variants;
//
////				print_pre($template_params);
//
//
//				$this->modules_model->update_item_data($module['id'], array('params'=>json_encode($template_params)));
//			}
//
//		}
//
//	}
}