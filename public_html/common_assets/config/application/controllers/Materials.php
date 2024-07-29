<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Materials extends CI_Controller {

    public function __construct()
    {
        parent::__construct();
        $this->load->helper('url_helper');
        $this->load->model('materials_model');
        $this->load->library('session');

        if(!$this->session->username || $this->session->username != $this->config->item('username')){
            redirect('login', 'refresh');
        }

        if(!$this->config->item('sub_account')) $this->config->set_item('sub_account', false);
        if($this->config->item('sub_account') == true) redirect('settings', 'refresh');
    }

	public function categories_index_new() {
		$data['materials_categories'] = $this->materials_model->get_categories();

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
		$this->load->view('materials/categories/index_new', $data);
		$this->load->view('templates/footer', $data);
    }

    public function categories_index()
    {

        $data['materials_categories'] = $this->materials_model->get_categories();
//        $data['materials_categories'] = buildTree($this->materials_model->get_categories(), 'parent', 'id');
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

        $data['controller'] = 'materials';


        $this->load->view('templates/header', $data);
        $this->load->view('common/categories_index_new', $data);
//        $this->load->view('materials/categories/index', $data);
        $this->load->view('templates/footer', $data);
    }

    public function categories_add()
    {

        $this->load->helper('form');
        $this->load->library('form_validation');


        $this->form_validation->set_rules('name', 'Название', 'required');

        if ($this->form_validation->run() === FALSE)
        {
            $data['materials_categories'] = $this->materials_model->get_categories();
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
            $this->load->view('materials/categories/add', $data);
            $this->load->view('templates/footer', $data);
        }
        else
        {
            $this->materials_model->add_category();
            redirect('materials/categories_index/', 'refresh');
        }
    }


    public function categories_edit($id)
    {
        $this->load->helper('form');
        $this->load->library('form_validation');

        $this->form_validation->set_rules('name', 'Название', 'required');

        if ($this->form_validation->run() === FALSE)
        {
            $data['category'] = $this->materials_model->get_category($id);
            $data['has_children'] = $this->materials_model->check_category_for_children($id);

            if($data['has_children'] == 0){
                $data['materials_categories'] = $this->materials_model->get_categories();
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
            $this->load->view('materials/categories/edit', $data);
            $this->load->view('templates/footer', $data);
        }
        else
        {
            $this->materials_model->update_category($id);
            redirect('materials/categories_index/', 'refresh');
        }
    }

    public function category_name_check($name, $id = false)
    {
        if($this->materials_model->check_category_name($name, $id) > 0){
            $this->form_validation->set_message('category_name_check', 'Такое название уже существует');
            return false;
        } else {
            return true;
        }
    }

    public function categories_remove($id)
    {
        if($this->materials_model->check_category_for_children($id) == 0){
            $this->materials_model->remove_category($id);
        }

        redirect('materials/categories_index/', 'refresh');
    }

	public function categories_set_active_ajax($id, $val) {
		$this->materials_model->set_category_active($id, $val);
	}


    //todo
    public function categories_order_update()
    {
        if($this->input->post()) $this->materials_model->categories_order_update(json_decode($this->input->post('data'), true));
    }

    //todo
    public function categories_get()
    {
        echo json_encode($this->materials_model->get_categories());
    }

    //todo
    public function category_add_ajax()
    {
        if($this->input->post()){
            echo $this->materials_model->add_category_ajax(
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
            echo $this->materials_model->update_category_ajax(
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
        if($this->input->post()) $this->materials_model->remove_category((int)$this->input->post('id'));
    }

	
    public function items_index($category = false, $per_page = false, $start = false)
    {

        if($category == null || $per_page == null){

            $category = 0;
            $per_page = 20;

            redirect('materials/items_index/'.$category .'/'.$per_page.'/', 'refresh');
        }

        $_SESSION['selected_items_category'] = $category;
        $_SESSION['selected_items_per_page'] = $per_page;
        $_SESSION['selected_items_pagination'] = $start;


        $this->load->library('pagination');
        $config['base_url'] = site_url('materials/items_index/'.$category .'/'.$per_page.'/');
        $config['total_rows'] = $this->materials_model->get_items_count($category);
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


        $data['items'] = $this->materials_model->get_items($category, $start, $config['per_page']);
        $data['categories'] = $this->materials_model->get_categories();
        $data['materials_categories'] = buildTree($this->materials_model->get_categories(), 'parent', 'id');

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
        $this->load->view('materials/items/index', $data);
        $this->load->view('templates/footer', $data);
    }

//    public function items_index_common($category = false, $per_page = false, $start = false)
//    {
//
//        if($category == null || $per_page == null){
//
//
//
//            $category = 0;
//            $per_page = 20;
//
//            if(isset($_SESSION['selected_items_category'])) $category = $_SESSION['selected_items_category'];
//            if(isset($_SESSION['selected_items_per_page'])) $per_page = $_SESSION['selected_items_per_page'];
//
//            redirect('materials/items_index_common/'.$category .'/'.$per_page.'/', 'refresh');
//        }
//
//        $_SESSION['selected_items_category'] = $category;
//        $_SESSION['selected_items_per_page'] = $per_page;
//        $_SESSION['selected_items_pagination'] = $start;
//
//
//        $this->load->library('pagination');
//        $config['base_url'] = site_url('materials/items_index_common/'.$category .'/'.$per_page.'/');
//        $config['total_rows'] = $this->materials_model->get_items_count_common($category);
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
//        $config['uri_segment'] = 5;
//
//
//        $data['items'] = $this->materials_model->get_items_common($category, $start, $config['per_page']);
//        $data['categories'] = $this->materials_model->get_categories_common();
//        $data['materials_categories'] = buildTree($this->materials_model->get_categories_common(), 'parent', 'id');
//
//        $this->pagination->initialize($config);
//
//        $data['pagination'] =  $this->pagination->create_links();
//        $data['common'] = 1;
//        $this->load->model('languages_model');
//        $data['lang_arr'] = get_default_lang();
//        if($this->config->item('ini')['language']['language'] !== 'default'){
//            $custom_lang = json_decode($this->languages_model->get($this->config->item('ini')['language']['language']));
//            foreach ($data['lang_arr'] as $key=>$value){
//                if(isset($custom_lang->$key)) {
//                    if(!empty($custom_lang->$key)) $data['lang_arr'][$key] = $custom_lang->$key;
//                }
//            }
//        }
//        $this->load->view('templates/header', $data);
//        $this->load->view('materials/items/index', $data);
//        $this->load->view('templates/footer', $data);
//    }




    public function copy_category($id)
    {



        $this->load->model('common_model');
        $category = $this->materials_model->get_category($id);
        print_pre($category);

        $new_category_data = array(
            'name'=>$category['name'] . ' (копия)',
            'parent'=>0,
            'active'=>$category['active'],
            'order'=>$category['order'],
        );
        $new_category_id = $this->materials_model->add_category_ajax($new_category_data);

        $children_categories = $this->materials_model->get_category_children($id);
        foreach ($children_categories as $child_category){

//                print_pre($child_category);

            $new_child_category_data = array(
                'name'=>$child_category['name'],
                'parent'=> $new_category_id,
                'active'=>1,
                'order'=>$child_category['order'],
            );

            $new_child_category_id = $this->materials_model->add_category_ajax($new_child_category_data);

            $materials = $this->materials_model->get_items_by_category($child_category['id']);

            foreach ($materials as $material){

                $this->copy_material($material['id'], $new_child_category_id);

//                $new_material_data = array(
//                    'name'=>$material['name'],
//                    'category'=>$new_child_category_id,
//                    'code'=>$material['code'],
//                    'active'=>$material['active'],
//                    'map'=>$material['map'],
//                    'color'=>$material['color'],
//                    'roughness'=>$material['roughness'],
//                    'metalness'=>$material['metalness'],
//                    'real_width'=>$material['real_width'],
//                    'real_height'=>$material['real_height'],
//                    'stretch_width'=>$material['stretch_width'],
//                    'stretch_height'=>$material['stretch_height'],
//                    'wrapping'=>$material['wrapping'],
//                    'transparent'=>$material['transparent']
//                );
//                $material = $this->materials_model->add_item_ajax($new_material_data);


            }

//            $new_category_id = $this->materials_model->add_category_ajax();


//            print_pre($materials);
        }

    }

    public function copy_material($id, $category_id){
        $material = $this->materials_model->get_item($id);

        if($material['map']){
            if (strpos($material['map'], 'common_assets') === false) {
                $cid = $category_id;
                $arr = explode('.',$material['map']);
                $arr[0] .= '_c' . $cid;
                $bd = dirname(FCPATH);
                $old_file = $bd . '/' . $material['map'];
                $new_file = $bd . '/' . implode('.', $arr);
                copy($old_file, $new_file);
            }
        }

        $new_material_data = array(
            'name'=>$material['name'],
            'category'=>$category_id,
            'code'=>$material['code'],
            'active'=>$material['active'],
            'map'=>$material['map'],
            'color'=>$material['color'],
            'roughness'=>$material['roughness'],
            'metalness'=>$material['metalness'],
            'real_width'=>$material['real_width'],
            'real_height'=>$material['real_height'],
            'stretch_width'=>$material['stretch_width'],
            'stretch_height'=>$material['stretch_height'],
            'wrapping'=>$material['wrapping'],
            'transparent'=>$material['transparent']
        );
        $material = $this->materials_model->add_item_ajax($new_material_data);




    }

    public function items_add()
    {

        $this->load->helper('form');
        $this->load->library('form_validation');


        $this->form_validation->set_rules('name', 'Название', 'required');


        if ($this->form_validation->run() === FALSE)
        {
            $data['materials_categories'] = buildTree($this->materials_model->get_categories(), 'parent', 'id');
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
            $this->load->view('materials/items/add', $data);
            $this->load->view('templates/footer', $data);
        }
        else
        {
            $item_id = $this->materials_model->add_item();

            if(isset($_FILES['map']) && $_FILES['map']['size'] > 0) {

                $config['upload_path'] = $this->config->item('images_upload');
	            $config['allowed_types'] = 'jpg|jpeg|png';
	            $config['file_name'] = $item_id;
                $config['file_ext_tolower'] = true;
                $config['overwrite'] = true;

                $this->load->library('upload', $config);

                if ($this->upload->do_upload('map'))
                {
                    $data = array('upload_data' => $this->upload->data());
                    $http_name = $this->config->item('images_path') . $data['upload_data']['file_name'];

                    $this->materials_model->add_item_image($item_id, $http_name);
                }

            }

            redirect('catalog/items/materials', 'refresh');
        }
    }

    public function items_add_ajax($id = false)
    {
        if(!isset($_POST)) exit;



        $data = json_decode($_POST['data'], true);
        if($id == false){
            $item_id = $this->materials_model->create_item();
        } else {
            $item_id = $id;
        }


        $data['id'] = $item_id;
        $data['data'] = json_encode($data);
        unset($data['params']);
        unset($data['add_params']);
        unset($data['type']);
        $this->materials_model->update_item_data($item_id, $data);


    }

    public function item($id = false)
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
        $data['controller_name'] = 'materials';
        $data['id'] = $id;

        $this->load->view('templates/header', $data);
        $this->load->view('materials/items/add_new', $data);
        $this->load->view('templates/footer', $data);
    }

//    public function items_add_common()
//    {
//
//        $this->load->helper('form');
//        $this->load->library('form_validation');
//
//
//        $this->form_validation->set_rules('name', 'Название', 'required');
//
//
//        if ($this->form_validation->run() === FALSE)
//        {
//            $data['materials_categories'] = buildTree($this->materials_model->get_categories_common(), 'parent', 'id');
//            $this->load->model('languages_model');
//            $data['lang_arr'] = get_default_lang();
//            $data['common'] = 1;
//            if($this->config->item('ini')['language']['language'] !== 'default'){
//                $custom_lang = json_decode($this->languages_model->get($this->config->item('ini')['language']['language']));
//                foreach ($data['lang_arr'] as $key=>$value){
//                    if(isset($custom_lang->$key)) {
//                        if(!empty($custom_lang->$key)) $data['lang_arr'][$key] = $custom_lang->$key;
//                    }
//                }
//            }
//            $this->load->view('templates/header', $data);
//            $this->load->view('materials/items/add', $data);
//            $this->load->view('templates/footer', $data);
//        }
//        else
//        {
//            $item_id = $this->materials_model->add_item_common();
//
//            if(isset($_FILES['map']) && $_FILES['map']['size'] > 0) {
//
////                $config['upload_path'] = $this->config->item('images_upload');
//                $config['upload_path'] = $_SERVER['DOCUMENT_ROOT'] . '/common_assets/images/materials';
//                $config['allowed_types'] = 'jpg|jpeg|png';
//                $config['file_name'] = $item_id;
//                $config['file_ext_tolower'] = true;
//                $config['overwrite'] = true;
//
//                $this->load->library('upload', $config);
//
//                if ($this->upload->do_upload('map'))
//                {
//                    $data = array('upload_data' => $this->upload->data());
//                    $http_name = '/common_assets/images/materials/' . $data['upload_data']['file_name'];
//
//                    $this->materials_model->add_item_image_common($item_id, $http_name);
//                }
//
//            }
//
//            redirect('materials/items_index_common/', 'refresh');
//        }
//    }

    public function items_add_multiple()
    {
        $this->load->helper('form');
        $this->load->library('form_validation');

        if (empty($_FILES['archive']['name']))
        {
            $data['materials_categories'] = buildTree($this->materials_model->get_categories(), 'parent', 'id');
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
            $this->load->view('materials/items/add_multiple', $data);
            $this->load->view('templates/footer', $data);
        } else {

        	$archive_file = $this->config->item('zip_upload') . 'tmp.zip';
			$extract_path = $this->config->item('zip_upload') . 'tmp';


	        if(isset($_FILES['archive']) && $_FILES['archive']['size'] > 0) {
				if(is_zip($_FILES['archive'])){
					if (move_uploaded_file($_FILES['archive']['tmp_name'], $archive_file)) {
						$zip = new ZipArchive;
                        $res = $zip->open($archive_file);

                        if(!is_dir($extract_path)) mkdir($extract_path);

						if ( $res === true ) {
							$length = $zip->numFiles;

							if($length > 100){
								removeDirectory($extract_path);
								unlink($archive_file);
								redirect('materials/items_index/', 'refresh');
								return;
							}

							for($i = 0; $i < $length; $i++)
							{
								$zip->renameName($zip->getNameIndex($i),mb_convert_encoding($zip->getNameIndex($i, 64), 'UTF-8', 'UTF-8, CP866'));
								$zip->extractTo( $extract_path, $zip->getNameIndex($i, 64) );
							}
							$zip->close();
						}

						$files = glob($extract_path . '/*');

                        $files = find_all_files($extract_path);


						foreach ($files as $file){

							$file_info = pathinfo($file);

							$img_info = is_image_uploaded($file);


							if($img_info){

								if(filesize($file) <= 1024 * 1024){
									$name = $file_info['filename'];
									$ext = $file_info['extension'];

									$mat_name_exp = explode('_', $name);

									$name = $mat_name_exp[0];
									$code = null;
									if(isset($mat_name_exp[1])){
										$code = $mat_name_exp[1];
									}

									$item_data = array(
										'name' => $name,
										'category' => no_xss($this->input->post('category')),
										'code' => $code,
										'active' => 1,
										'color' => '#ffffff',
										'roughness' => no_xss($this->input->post('roughness')),
										'metalness' => no_xss($this->input->post('metalness')),
										'real_width' => $img_info[0],
										'real_height' => $img_info[1],
										'stretch_width' => no_xss($this->input->post('stretch_width')),
										'stretch_height' => no_xss($this->input->post('stretch_height')),
										'wrapping' => no_xss($this->input->post('wrapping'))
									);

									if(isset($_POST['real_width']) && $_POST['real_width'] != 0){
                                        $item_data['real_width'] = $_POST['real_width'];
                                    }

                                    if(isset($_POST['real_height']) && $_POST['real_height'] != 0){
                                        $item_data['real_height'] = $_POST['real_height'];
                                    }


		                        $item_id = $this->materials_model->add_item_with_data($item_data);
								rename( $file, $this->config->item( 'images_upload' ) . $item_id . '.' . $ext );
								$http_name = $this->config->item( 'images_path' ) . $item_id . '.' . $ext;
								$this->materials_model->add_item_image( $item_id, $http_name );
								}


							}
						}

						removeDirectory($extract_path);
						unlink($archive_file);
                        redirect('catalog/items/materials', 'refresh');
					}
				}
	        }


        }
    }



	public function items_add_multiple_csv() {
		$data = readCSV($_SERVER['DOCUMENT_ROOT'] . '/ral_1950.csv');

		array_shift($data);

		foreach ($data as $item){

			if(empty($item)) continue;



			$item_data = array(
				'name' => $item[1],
				'category' => $item[3],
				'code' => $item[0],
				'active' => 1,
				'color' => $item[2],
				'roughness' => $item[4],
				'metalness' => $item[5],
				'real_width' => 256,
				'real_height' => 256,
				'stretch_width' => 0,
				'stretch_height' => 0,
				'wrapping' => 'mirror'
			);

//			print_pre($item_data);
			$this->materials_model->add_item_with_data($item_data);

//			$item_data['code'] = $item[1];
//			$item_data['name'] = $item[2];
//			$item_data['category'] = $item[3];
//			$item_data['price'] = $item[4];
//			$item_data['images'] = $item[5];
//			$item_data['description'] = $item[6];
//			$item_data['tags'] = $item[7];
//
//			if(empty($item[8])) $item[8] = 'common';
//
//			$item_data['type'] = $item[8];
//			$item_data['default'] = $item[9];



//			if($this->accessories_model->get($item[0])){
//				$this->accessories_model->update($item[0], $item_data);
//
//
//			} else {
//				if(!empty($item_data['name'])){
//					$this->accessories_model->add($item_data);
//				}
//			}
		}
		echo 'ok';
    }

//    public function items_add_multiple_csv_common() {
//        $data = readCSV($_SERVER['DOCUMENT_ROOT'] . '/ral_1950.csv');
//
//        array_shift($data);
//
//        $this->load->model('common_model');
//
//        foreach ($data as $item){
//
//            if(empty($item)) continue;
//
//
//
//            $item_data = array(
//                'name' => $item[1],
//                'category' => 212,
//                'code' => $item[0],
//                'active' => 1,
//                'color' => $item[2],
//                'roughness' => $item[4],
//                'metalness' => $item[5],
//                'real_width' => 256,
//                'real_height' => 256,
//                'stretch_width' => 0,
//                'stretch_height' => 0,
//                'wrapping' => 'mirror'
//            );
//
////			print_pre($item_data);
//            $this->common_model->add_data('Materials_items', $item_data);
//
////			$item_data['code'] = $item[1];
////			$item_data['name'] = $item[2];
////			$item_data['category'] = $item[3];
////			$item_data['price'] = $item[4];
////			$item_data['images'] = $item[5];
////			$item_data['description'] = $item[6];
////			$item_data['tags'] = $item[7];
////
////			if(empty($item[8])) $item[8] = 'common';
////
////			$item_data['type'] = $item[8];
////			$item_data['default'] = $item[9];
//
//
//
////			if($this->accessories_model->get($item[0])){
////				$this->accessories_model->update($item[0], $item_data);
////
////
////			} else {
////				if(!empty($item_data['name'])){
////					$this->accessories_model->add($item_data);
////				}
////			}
//        }
//        echo 'ok';
//    }

    public function check_files()
    {

    }

    public function items_edit($id)
    {

        $this->load->helper('form');
        $this->load->library('form_validation');

        $this->form_validation->set_rules('name', 'Название', 'required');

        if ($this->form_validation->run() === FALSE)
        {
            $data['materials_categories'] = buildTree($this->materials_model->get_categories(), 'parent', 'id');
            $data['item'] = $this->materials_model->get_item($id);
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
            $this->load->view('materials/items/edit', $data);
            $this->load->view('templates/footer', $data);
        }
        else
        {
            $item = $this->materials_model->get_item($id);




            if($item['map'] != null && $this->input->post('old_map') == null){
                if (strpos($item['map'], 'common_assets') === false){
                    unlink($this->config->item('images_upload') . basename($item['map']));
                }

                $this->materials_model->remove_item_image($id);
            }

            $this->materials_model->update_item($id);

            if(isset($_FILES['map']) && $_FILES['map']['size'] > 0) {

                $config['upload_path'] = $this->config->item('images_upload');
	            $config['allowed_types'] = 'jpg|jpeg|png';
	            $config['file_name'] = $id;
                $config['file_ext_tolower'] = true;
                $config['overwrite'] = true;

                $this->load->library('upload', $config);

                if ($this->upload->do_upload('map'))
                {
                    $data = array('upload_data' => $this->upload->data());
                    $http_name = $this->config->item('images_path') . $data['upload_data']['file_name'];

                    $this->materials_model->add_item_image($id, $http_name);
                }
            }



            redirect('catalog/items/materials', 'refresh');
        }
    }



    public function items_remove($id)
    {
        $item = $this->materials_model->get_item($id);


        if($item['map'] != null){
            if (strpos($item['map'], 'common_assets') === false) {
                unlink($this->config->item('images_upload') . basename($item['map']));
            }
        }

        $this->materials_model->remove_items($id);
        redirect('materials/items_index/'.$_SESSION['selected_items_category'].'/'.$_SESSION['selected_items_per_page'].'/'.$_SESSION['selected_items_pagination'], 'refresh');
    }



    public function remove_all_items($code)
    {
        if($code == 'drop_true'){
            $materials = $this->materials_model->get_all_items();

            foreach ($materials as $mat){

                $item = $this->materials_model->get_item($mat['id']);
                if($item['map'] != null){
                    if (strpos($item['map'], 'common_assets') !== false) {
                        unlink($this->config->item('images_upload') . basename($item['map']));
                    }
                }

                $this->materials_model->remove_items($mat['id']);

            }
        }
    }

    public function remove_items_by_cat_id($id, $code)
    {
        if($code == 'drop_true'){
            $materials = $this->materials_model->get_items_by_category($id);

            foreach ($materials as $mat){

                $item = $this->materials_model->get_item($mat['id']);
                if($item['map'] != null){
                    unlink($this->config->item('images_upload') . basename($item['map']));
                }

                $this->materials_model->remove_items($mat['id']);

            }
        }
    }






//    public function convert_to_common_assets()
//    {
//        exit;
//        if($this->input->post()){
//            if(!$this->input->post('password')){
//                exit;
//            }
//
//            if($this->input->post('password') !== 'l5r1755s'){
//                exit;
//            }
//
//            $materials = $this->materials_model->get_all_items();
//
//            foreach ($materials as $mat){
//
//                if($mat['map']){
////                    echo $mat['map'];
//                    $this->materials_model->update_item_data($mat['id'], array('map'=>'/common_assets/' . $mat['map']));
//                }
//
//
//
//            }
//
//            echo 'done';
//        }
//    }

	public function get_materials_ajax($id) {


    		echo json_encode($this->materials_model->get_items_by_category($id));

    }

	public function get_all_data_ajax() {

    	$result = array();
    	$result['categories'] = $this->materials_model->get_categories();
    	$result['items'] = $this->materials_model->get_all_items();

		echo json_encode($result);
    }

	public function set_active_ajax($id, $val) {
		$this->materials_model->set_active($id, $val);
	}




	public function coupe_categories_index()
	{
		if($this->config->item('ini')['coupe']['available']!=1) redirect('/settings/coupe_account_settings_index', 'refresh');

		$data['materials_categories'] = buildTree($this->materials_model->coupe_get_categories(), 'parent', 'id');
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
		$this->load->view('coupe/materials/categories/index', $data);
		$this->load->view('templates/footer', $data);
	}

	public function coupe_categories_add()
	{
		if($this->config->item('ini')['coupe']['available']!=1) redirect('/settings/coupe_account_settings_index', 'refresh');

		$this->load->helper('form');
		$this->load->library('form_validation');


		$this->form_validation->set_rules('name', 'Название', 'required');

		if ($this->form_validation->run() === FALSE)
		{
			$data['materials_categories'] = $this->materials_model->coupe_get_categories();
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
			$this->load->view('coupe/materials/categories/add', $data);
			$this->load->view('templates/footer', $data);
		}
		else
		{
			$data = array(
				'name' => $this->input->post('name'),
				'parent' => $this->input->post('parent'),
				'active' => $this->input->post('active')
			);
			$this->materials_model->coupe_add_category($data);
			redirect('materials/coupe_categories_index/', 'refresh');
		}

	}

	public function coupe_categories_edit($id)
	{
		if($this->config->item('ini')['coupe']['available']!=1) redirect('/settings/coupe_account_settings_index', 'refresh');

		$this->load->helper('form');
		$this->load->library('form_validation');

		$this->form_validation->set_rules('name', 'Название', 'required');

		if ($this->form_validation->run() === FALSE)
		{
			$data['category'] = $this->materials_model->coupe_get_category($id);
			$data['has_children'] = $this->materials_model->coupe_check_category_for_children($id);

			if($data['has_children'] == 0){
				$data['materials_categories'] = $this->materials_model->coupe_get_categories();
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
			$this->load->view('coupe/header', $data);
			$this->load->view('coupe/materials/categories/edit', $data);
			$this->load->view('templates/footer', $data);
		}
		else
		{
			$data = array(
				'name' => $this->input->post('name'),
				'parent' => $this->input->post('parent'),
				'active' => $this->input->post('active')
			);

			$this->materials_model->coupe_update_category($id,$data);
			redirect('materials/coupe_categories_index/', 'refresh');
		}

	}

	public function coupe_categories_remove($id)
	{
		if($this->config->item('ini')['coupe']['available']!=1) redirect('/settings/coupe_account_settings_index', 'refresh');
		if($this->materials_model->coupe_check_category_for_children($id) == 0){
			$this->materials_model->coupe_remove_category($id);
		}

		redirect('materials/coupe_categories_index/', 'refresh');
	}



	public function coupe_materials_items_index($category = 0, $per_page = 20, $start = false)
	{
		if($this->config->item('ini')['coupe']['available']!=1) redirect('/settings/coupe_account_settings_index', 'refresh');
		$_SESSION['coupe_selected_materials_category'] = $category;
		$_SESSION['coupe_selected_materials_per_page'] = $per_page;
		$_SESSION['coupe_selected_materials_pagination'] = $start;

		$this->load->library('pagination');
		$config['base_url'] = site_url( '/materials/coupe_materials_items_index/'.$category .'/'.$per_page.'/');
		$config['total_rows'] = $this->materials_model->coupe_get_items_count($category);
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

		$config['num_links'] = 12;

		$config['uri_segment'] = 5;

		$data['items'] = $this->materials_model->coupe_get_items($category, $start, $config['per_page']);
		$data['categories'] = $this->materials_model->coupe_get_categories();
		$data['materials_categories'] = buildTree($this->materials_model->coupe_get_categories(), 'parent', 'id');

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
		$this->load->view('coupe/header', $data);
		$this->load->view('coupe/materials/items/index', $data);
		$this->load->view('templates/footer', $data);
	}



	public function coupe_materials_add_from_csv() {

		if(isset($_FILES)){
			$data = readCSV($_FILES['modal_csv_file']['tmp_name']);



			$save_path = dirname(FCPATH).'/images_coupe/materials/';
			$abs_save_path = 'images_coupe/materials/';
			if (!is_dir($save_path)) { mkdir($save_path); }


			foreach ($data as $item){


				if(!is_array($item)) continue;
				if(count($item) != 21 ) continue;

				$add_flag = 1;



				if($item[0] != '' && $item[0] != 0){
					$item_id = $item[0];
					$tmp = $this->materials_model->coupe_get_item($item[0]);
					if(empty($tmp)){
						$add_flag = 1;
					} else {
						$add_flag = 0;
					}
				}

				if($add_flag == 1){
					$item_id = $this->materials_model->coupe_add_item(array("name"=>""));
				}



				$update_data = array();

//				$item[2] = iconv(mb_detect_encoding($item[2], mb_detect_order(), true), "UTF-8", $item[2]);
//				$item[3] = iconv(mb_detect_encoding($item[3], mb_detect_order(), true), "UTF-8", $item[3]);


				$real_width = 256;
				$real_height = 256;
				$stretch_width = 0;
				$stretch_height = 0;
				$wrapping = 'mirror';

				$update_data['category'] = 0;
				$update_data['name'] = '';
				$update_data['code'] = '';
				$item_data = array();
				$item_data['type'] = "Standart";

				$item_data['params'] = array();
				$item_data['params']['color'] = '#ffffff';
				$item_data['params']['roughness'] = 0.8;
				$item_data['params']['metalness'] = 0;
				$item_data['params']['transparent'] = 0;
				$item_data['params']['opacity'] = 1;




				if($item[1] != '') $update_data['category'] = json_encode(  array_filter(explode(' ', $item[1])) );
				if($item[2] != '') $update_data['name'] = $item[2];
				if($item[3] != '') $update_data['code'] = $item[3];



				if($item[4] != '') $item_data['params']['color'] = $item[4];
				if($item[5] != '') $item_data['params']['roughness'] = $item[5];
				if($item[6] != '') $item_data['params']['metalness'] = $item[6];
				if($item[7] != '') $item_data['params']['transparent'] = $item[7];
				if($item[8] != '') $item_data['params']['opacity'] = $item[8];



				if($item[9] != ''){
					$url = $item[9];
//					$url = iconv(mb_detect_encoding($item[9], mb_detect_order(), true), "UTF-8", $item[9]);
					$file_name = basename($url);
					$ext = file_ext($file_name);


					if($ext != ''){
						$ch = curl_init($url);


						$save_file_loc = $save_path . $item_id . '_map.' . $ext;
						$fp = fopen($save_file_loc, 'wb');
						curl_setopt($ch, CURLOPT_FILE, $fp);
						curl_setopt($ch, CURLOPT_HEADER, 0);
						curl_exec($ch);
						curl_close($ch);
						fclose($fp);

						if($item[10] != '') $real_width = $item[10];
						if($item[11] != '') $real_height = $item[11];
						if($item[12] != '') $stretch_width = $item[12];
						if($item[13] != '') $stretch_height = $item[13];
						if($item[14] != '') $wrapping = $item[14];

						$item_data['params']['map'] = $abs_save_path . $item_id . '_map.' . $ext;
					}

				}

				if($item[15] != ''){
					$url = $item[15];
//					$url = iconv(mb_detect_encoding($item[15], mb_detect_order(), true), "UTF-8", $item[15]);
					$file_name = basename($url);
					$ext = file_ext($file_name);

					if($ext != ''){
						$ch = curl_init($url);
						$save_file_loc = $save_path . $item_id . '_alphamap.' . $ext;
						$fp = fopen($save_file_loc, 'wb');
						curl_setopt($ch, CURLOPT_FILE, $fp);
						curl_setopt($ch, CURLOPT_HEADER, 0);
						curl_exec($ch);
						curl_close($ch);
						fclose($fp);


						if($item[16] != '') $real_width = $item[16];
						if($item[17] != '') $real_height = $item[17];
						if($item[18] != '') $stretch_width = $item[18];
						if($item[19] != '') $stretch_height = $item[19];
						if($item[20] != '') $wrapping = $item[20];

						$item_data['params']['alphaMap'] = $abs_save_path . $item_id . '_alphamap.' . $ext;
					}



				}



				$item_data['add_params'] = array();
				$item_data['add_params']['real_width'] = $real_width;
				$item_data['add_params']['real_height'] = $real_height;
				$item_data['add_params']['stretch_width'] = $stretch_width;
				$item_data['add_params']['stretch_height'] = $stretch_height;
				$item_data['add_params']['wrapping'] = $wrapping;






				$update_data['active'] = 1;
				$update_data['params'] = json_encode($item_data);



				$this->materials_model->coupe_update_item($item_id, $update_data);



			}
			echo 'ok';
		}

	}
	

	public function coupe_materials_items_add()
	{
		if($this->config->item('ini')['coupe']['available']!=1) redirect('/settings/coupe_account_settings_index', 'refresh');
		if ($this->input->post()) {

			$this->load->helper('form');
			$this->load->library('form_validation');
			$this->form_validation->set_rules('name', 'Название', 'required');

			if ($this->form_validation->run() === FALSE) {
				$errors = validation_errors();
				echo json_encode(['error' => $errors]);
				exit;
			} else {
				$item_id = $this->materials_model->coupe_add_item(array("name"=>""));

				if(!$this->config->item('bpconfig')){
					$save_path = dirname(FCPATH).'/images_coupe/materials/';
					$abs_save_path = 'images_coupe/materials/';
				} else {
					$save_path = $_SERVER["DOCUMENT_ROOT"] . '/common_assets_kupe/images/materials/';
					$abs_save_path = '/common_assets_kupe/images/materials/';
				}

				if (!is_dir($save_path)) { mkdir($save_path); }

				$errors = array();

				$params = json_decode($this->input->post('data'));

				$new_file_icon = null;
				$new_file_alphamap = null;
				$new_file_map = null;

				if( isset( $_FILES['icon'] ) ){
					$extension = pathinfo( $_FILES['icon']['name'] )['extension'];
					if(strtolower($extension) == 'jpg' || strtolower($extension) == 'png' || strtolower($extension) == 'gif'){
						$new_file_icon = $save_path . $item_id . '_icon.' . $extension;
						if (move_uploaded_file($_FILES['icon']['tmp_name'], $new_file_icon)) {
							$icon = $abs_save_path . $item_id . '_icon.' . $extension;
							$params->params->icon = $icon;
						} else {
							$errors[] = 'Ошибка при загрузке файла ' . $_FILES['icon']['name'] . '<br>';
						}
					}
				}

				if( isset( $_FILES['alpha_map'] ) ){
					$extension = pathinfo( $_FILES['alpha_map']['name'] )['extension'];
					if(strtolower($extension) == 'jpg' || strtolower($extension) == 'png' || strtolower($extension) == 'gif'){
						$new_file_alphamap = $save_path . $item_id . '_alphamap.' . $extension;
						if (move_uploaded_file($_FILES['alpha_map']['tmp_name'], $new_file_alphamap)) {
							$alpha_map = $abs_save_path . $item_id . '_alphamap.' . $extension;
							$params->params->alphaMap = $alpha_map;
						} else {
							$errors[] = 'Ошибка при загрузке файла ' . $_FILES['alpha_map']['name'] . '<br>';
						}
					}
				}

				if( isset( $_FILES['map'] ) ){
					$extension = pathinfo( $_FILES['map']['name'] )['extension'];
					if(strtolower($extension) == 'jpg' || strtolower($extension) == 'png' || strtolower($extension) == 'gif'){
						$new_file_map = $save_path . $item_id . '_map.' . $extension;
						if (move_uploaded_file($_FILES['map']['tmp_name'], $new_file_map)) {
							$map = $abs_save_path . $item_id . '_map.' . $extension;
							$params->params->map = $map;
						} else {
							$errors[] = 'Ошибка при загрузке файла ' . $_FILES['map']['name'] . '<br>';
						}
					}
				}

				if( count ($errors) > 0 ){
					if($new_file_icon != null){
						if (file_exists($new_file_icon)) { unlink ($new_file_icon); }
					}
					if($new_file_map != null){
						if (file_exists($new_file_map)) { unlink ($new_file_map); }
					}

					if($new_file_map != null){
						if (file_exists($new_file_map)) { unlink ($new_file_map); }
					}

					$this->glass_model->remove_items($item_id);
					echo json_encode(['error' => $errors]);
					exit;
				}

				$data = array();

				$data['name'] = $this->input->post('name');
				$data['code'] = $this->input->post('code');
				$data['category'] = $this->input->post('category');
				$data['active'] = $this->input->post('active');

				$params->id = $item_id;
				$params->name = $data['name'];
				$params->code = $data['code'];
				$params->category = $data['category'];

				$data['params'] = json_encode($params);

				echo json_encode(['success' => '']);
				$this->materials_model->coupe_update_item($item_id, $data);
			}

		} else {
			$data['materials_categories'] = buildTree($this->materials_model->coupe_get_categories(), 'parent', 'id');
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
			$this->load->view('coupe/materials/items/add', $data);
			$this->load->view('templates/footer', $data);
		}
	}

	public function coupe_materials_items_edit($id)
	{
		if($this->config->item('ini')['coupe']['available']!=1) redirect('/settings/coupe_account_settings_index', 'refresh');
		if ($this->input->post()) {

			$this->load->helper('form');
			$this->load->library('form_validation');
			$this->form_validation->set_rules('name', 'Название', 'required');

			if ($this->form_validation->run() === FALSE) {
				$errors = validation_errors();
				echo json_encode(['error' => $errors]);
				exit;
			} else {
				$item_id = $id;

				if (!$this->config->item('bpconfig')) {
					$save_path = dirname(FCPATH) . '/images_coupe/materials/';
					$abs_save_path = 'images_coupe/materials/';
				} else {
					$save_path = $_SERVER["DOCUMENT_ROOT"] . '/common_assets_coupe/images/materials/';
					$abs_save_path = '/common_assets_coupe/images/glass_materials/';
				}

				if (!is_dir($save_path)) {
					mkdir($save_path);
				}

				$cur_params = json_decode($this->materials_model->coupe_get_item($item_id)['params']);



				$errors = array();

				$params = json_decode($this->input->post('data'));



				if(isset($params->params->alphaMap)){
					$params->params->alphaMap = str_replace($this->config->item('const_path'), "", $params->params->alphaMap);
				}

				if(isset($params->params->map)){
					$params->params->map = str_replace($this->config->item('const_path'), "", $params->params->map);
				}

				if( $this->input->post('icon') ){
					$params->params->icon = str_replace($this->config->item('const_path'), "", $this->input->post('icon'));
				}



				if($this->input->post('remove_current_icon')){
					if (strpos($cur_params->params->icon, 'common_assets') !== false){

					} else {
						if ( file_exists( dirname(FCPATH) . '/' . $cur_params->params->icon ) ) {
							unlink(dirname(FCPATH). '/' . $cur_params->params->icon);
						}
					}
					unset($params->params->icon);
				}

				if($this->input->post('remove_current_alphamap')){
					if (strpos($cur_params->params->alphaMap, 'common_assets') !== false){

					} else {
						if ( file_exists( dirname(FCPATH). '/' . $cur_params->params->alphaMap ) ) {
							unlink(dirname(FCPATH). '/' . $cur_params->params->alphaMap);
						}
					}
					unset($params->params->alphaMap);
				}


				if($this->input->post('remove_current_map')){
					if (strpos($cur_params->params->map, 'common_assets') !== false){

					} else {
						if ( file_exists( dirname(FCPATH). '/' . $cur_params->params->map ) ) {
							unlink(dirname(FCPATH). '/' . $cur_params->params->map);
						}
					}
					unset($params->params->map);
				}





				$new_file_icon = null;
				$new_file_alphamap = null;
				$new_file_map = null;

				if (isset($_FILES['icon'])) {
					$extension = pathinfo($_FILES['icon']['name'])['extension'];
					if (strtolower($extension) == 'jpg' || strtolower($extension) == 'png' || strtolower($extension) == 'gif') {
						$new_file_icon = $save_path . $item_id . '_icon.' . $extension;
						if (move_uploaded_file($_FILES['icon']['tmp_name'], $new_file_icon)) {
							$icon = $abs_save_path . $item_id . '_icon.' . $extension;
							$params->params->icon = $icon;
						} else {
							$errors[] = 'Ошибка при загрузке файла ' . $_FILES['icon']['name'] . '<br>';
						}
					}
				}

				if (isset($_FILES['alpha_map'])) {
					$extension = pathinfo($_FILES['alpha_map']['name'])['extension'];
					if (strtolower($extension) == 'jpg' || strtolower($extension) == 'png' || strtolower($extension) == 'gif') {
						$new_file_alphamap = $save_path . $item_id . '_alphamap.' . $extension;
						if (move_uploaded_file($_FILES['alpha_map']['tmp_name'], $new_file_alphamap)) {
							$alpha_map = $abs_save_path . $item_id . '_alphamap.' . $extension;
							$params->params->alphaMap = $alpha_map;
						} else {
							$errors[] = 'Ошибка при загрузке файла ' . $_FILES['alpha_map']['name'] . '<br>';
						}
					}
				}

				if (isset($_FILES['map'])) {
					$extension = pathinfo($_FILES['map']['name'])['extension'];
					if (strtolower($extension) == 'jpg' || strtolower($extension) == 'png' || strtolower($extension) == 'gif') {
						$new_file_map = $save_path . $item_id . '_map.' . $extension;
						if (move_uploaded_file($_FILES['map']['tmp_name'], $new_file_map)) {
							$map = $abs_save_path . $item_id . '_map.' . $extension;
							$params->params->map = $map;
						} else {
							$errors[] = 'Ошибка при загрузке файла ' . $_FILES['map']['name'] . '<br>';
						}
					}
				}


				$data = array();

				$data['name'] = $this->input->post('name');
				$data['code'] = $this->input->post('code');

				$data['category'] = $this->input->post('category');


				$data['active'] = $this->input->post('active');

				$params->id = $item_id;
				$params->name = $data['name'];
				$params->code = $data['code'];
				$params->category = $data['category'];

				$data['params'] = json_encode($params);

				echo json_encode(['success' => '']);
				$this->materials_model->coupe_update_item($item_id, $data);
			}


		} else {
			$data['materials_categories'] = buildTree($this->materials_model->coupe_get_categories(), 'parent', 'id');




			$data['item'] = $this->materials_model->coupe_get_item($id);




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
			$this->load->view('coupe/materials/items/edit', $data);
			$this->load->view('templates/footer', $data);
			return;
		}
	}


	public function coupe_materials_items_remove($id)
	{
		if($this->config->item('ini')['coupe']['available']!=1) redirect('/settings/coupe_account_settings_index', 'refresh');
		$item = $this->materials_model->coupe_get_item($id);

		$params = json_decode($item['params']);

		if( isset($params->params->map)){
			if (strpos($params->params->map, 'common_assets') !== false){
			} else {
				$filename = dirname(FCPATH). '/' . $params->params->map;
				if(file_exists($filename)) unlink($filename);
			}
		}

		if( isset($params->params->icon)){
			if (strpos($params->params->icon, 'common_assets') !== false){
			} else {
				$filename = dirname(FCPATH). '/' . $params->params->icon;
				if(file_exists($filename)) unlink($filename);
			}
		}

		if( isset($params->params->alphaMap)){
			if (strpos($params->params->alphaMap, 'common_assets') !== false){
			} else {
				$filename = dirname(FCPATH). '/' . $params->params->alphaMap;
				if(file_exists($filename)) unlink($filename);
			}
		}


		$this->materials_model->coupe_remove_items($id);
		redirect('materials/coupe_materials_items_index/', 'refresh');
	}


    public function coupe_fix_db()
    {
        $materials = $this->materials_model->coupe_get_all_items();

        foreach ($materials as $material){
            $cat = json_decode($material['category'], true);
            if(is_array($cat)) continue;
            $arr = [];
            $arr[] = strval($cat);
            $data = array('category'=>json_encode($arr));
            $this->materials_model->coupe_update_item($material['id'], $data);
        }

        echo 'ok';
	}

    public function coupe_clear_db()
    {
        $this->materials_model->coupe_clear_all();
        $folder_path = dirname(FCPATH) . '/images_coupe/materials';
        $files = glob($folder_path . '/*');

        foreach ($files as $file) {
            if (is_file($file) && pathinfo($file)['extension'] != 'html') unlink($file);
        }

        echo 'ok';
	}

    public function coupe_export_csv()
    {
        $items = $this->materials_model->coupe_get_all_items();

        $csv_data = array();

        foreach ($items as $item) {

            $params = json_decode($item['params'], true);
            $cats = json_decode($item['category'], true);

            $cat_data = '';


            if(is_array($cats)){
                foreach ($cats as $cat){
                    $cat_data .= intval($cat) . ' ';
                }
                trim($cat_data);
            } else {
                $cat_data = $cats;
            }

            if(!isset($params['params']['transparent'])) $params['params']['transparent'] = 0;
            if(!isset($params['params']['opacity'])) $params['params']['opacity'] = 1;


            $csv_array = array();
            $csv_array[] = $item['id'];
            $csv_array[] = $cat_data;
            $csv_array[] = $item['name'];
            $csv_array[] = $item['code'];
            $csv_array[] = $params['params']['color'];
            $csv_array[] = $params['params']['roughness'];
            $csv_array[] = $params['params']['metalness'];
            $csv_array[] = (int)$params['params']['transparent'];
            $csv_array[] = $params['params']['opacity'];
            $csv_array[] = '';

            if(empty($params['add_params']['real_width'])) $params['add_params']['real_width'] = '';
            if(empty($params['add_params']['real_height'])) $params['add_params']['real_height'] = '';
            if(empty($params['add_params']['stretch_width'])) $params['add_params']['stretch_width'] = '';
            if(empty($params['add_params']['stretch_height'])) $params['add_params']['stretch_height'] = '';
            if(empty($params['add_params']['wrapping'])) $params['add_params']['wrapping'] = '';

            $csv_array[] = $params['add_params']['real_width'];
            $csv_array[] = $params['add_params']['real_height'];
            $csv_array[] = $params['add_params']['stretch_width'];
            $csv_array[] = $params['add_params']['stretch_height'];
            $csv_array[] = $params['add_params']['wrapping'];

            if(!empty($item['add_params']['alphaMap'])){
                $csv_array[] = $params['add_params']['alphaMap'];
                $csv_array[] = $params['add_params']['real_width'];
                $csv_array[] = $params['add_params']['real_height'];
                $csv_array[] = $params['add_params']['stretch_width'];
                $csv_array[] = $params['add_params']['stretch_height'];
                $csv_array[] = $params['add_params']['wrapping'];
            } else {
                $csv_array[] = '';
                $csv_array[] = '';
                $csv_array[] = '';
                $csv_array[] = '';
                $csv_array[] = '';
                $csv_array[] = '';
            }

            $csv_data[] = $csv_array;
        }

        header('Content-Type: text/csv; charset=utf-8');
        header('Content-Disposition: attachment; filename=coupe_materials_data.csv');

        $output = fopen('php://output', 'w');

        fputcsv($output, array(
            'ID',
            'ID категорий (через пробел)',
            'Название',
            'Артикул',
            'Цвет (HEX)',
            'Шероховатость',
            'Metallness',
            'Прозрачность (1 или 0)',
            'Значение прозрачности (от 0 до 1 через .)',
            'Ссылка на текстуру',
            'Реальная ширина текстуры (мм)',
            'Реальная высота текстуры (мм)',
            'Растягивать по ширине (0 или 1)',
            'Растягивать по высоте (0 или 1)',
            'Тип заполнения текстурой (mirror или repeat)',
            'Карта прозрачности (ссылка)',
            'Реальная ширина карты (мм)',
            'Реальная высота карты (мм)',
            'Растягивать по ширине (0 или 1)',
            'Растягивать по высоте (0 или 1)',
            'Тип заполнения текстурой (mirror или repeat)'
        ));

        foreach ($csv_data as $row) {
            fputcsv($output, $row);
        }

    }


//    public function copy_category_to_common($category_from, $category_to)
//    {
//
////        $this->load->model('common_model');
//        $materials = $this->materials_model->get_items_by_category($category_from);
//
////        $data = $this->common_model->get_where('Materials_items', array('category'=>$id));
////        print_pre($data);
//
//        foreach ($materials as $material){
//
////            $new_id = $this->materials_model->add_item_common_data('Materials_items', array('name'=>''));
//            if(!empty($material['map'])){
//
//            }
//        }
//
////        $this->materials_model->add_item_common_data('Materials_items', $data);
//
//    }

	//Api

    public function remove_blank()
    {
        $this->materials_model->remove_blank();
    }


    //Catalog



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

function file_ext($filename) {
	return preg_match('/\./', $filename) ? preg_replace('/^.*\./', '', $filename) : '';
}
