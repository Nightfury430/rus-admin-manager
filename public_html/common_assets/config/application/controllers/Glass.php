<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Glass extends CI_Controller {

    private $controller_name = "glass";

    public function __construct()
    {
        parent::__construct();
        $this->load->helper('url_helper');
        $this->load->model('glass_model');
        $this->load->helper('global_helper');
        $this->load->library('session');

        if(!$this->session->username || $this->session->username != $this->config->item('username')){
            redirect('login', 'refresh');
        }

        if(!$this->config->item('sub_account')) $this->config->set_item('sub_account', false);
        if($this->config->item('sub_account') == true) redirect('settings', 'refresh');

    }

    public function categories_index()
    {
//        $data['categories'] = buildTree($this->glass_model->get_categories(), 'parent', 'id');
        $data['categories'] = $this->glass_model->get_categories();
        $data['controller'] = $this->controller_name;

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


	    $data['header'] = $data['lang_arr']['glass_categories_index_heading'];
        $this->load->view('templates/header', $data);
//        $this->load->view('common/categories_index', $data);
        $this->load->view('common/categories_index_new', $data);
        $this->load->view('templates/footer', $data);
    }

    public function categories_add()
    {

        if ($this->input->post()) {

            $this->load->helper('form');
            $this->load->library('form_validation');
            $this->form_validation->set_rules('name', 'Название', 'required');

            if ($this->form_validation->run() === FALSE) {
                $errors = validation_errors();
                echo json_encode(['error' => $errors]);
            } else {
                echo json_encode(['success' => '']);
                $this->glass_model->add_category($this->input->post());
            }

        } else {

            $data['categories'] = $this->glass_model->get_categories();
            $data['controller'] = $this->controller_name;

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
	        $data['header'] = $data['lang_arr']['add_category'];
	        $this->load->view('templates/header', $data);
            $this->load->view('common/categories_add', $data);
            $this->load->view('templates/footer', $data);
        }

    }

    public function categories_edit($id)
    {

        if ($this->input->post()) {

            $this->load->helper('form');
            $this->load->library('form_validation');
            $this->form_validation->set_rules('name', 'Название', 'required');

            if ($this->form_validation->run() === FALSE) {
                $errors = validation_errors();
                echo json_encode(['error' => $errors]);
            } else {
                echo json_encode(['success' => '']);
                $this->glass_model->update_category($id, $this->input->post());
            }

        } else {

            $data['category'] = $this->glass_model->get_category($id);
            $data['has_children'] = $this->glass_model->check_category_for_children($id);

            if ($data['has_children'] == 0) {
                $data['categories'] = $this->glass_model->get_categories();
            }

            $data['controller'] = $this->controller_name;

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
	        $data['header'] = $data['lang_arr']['edit_category'];

	        $this->load->view('templates/header', $data);
            $this->load->view('common/categories_edit', $data);
            $this->load->view('templates/footer', $data);
        }

    }

    public function categories_remove($id)
    {
        if($this->glass_model->check_category_for_children($id) == 0){
            $this->glass_model->remove_category($id);
        }

        redirect( $this->controller_name . '/categories_index/', 'refresh');
    }

	public function categories_set_active_ajax($id, $val) {
		$this->glass_model->set_category_active($id, $val);
	}


    //todo
    public function categories_order_update()
    {
        if($this->input->post()) $this->glass_model->categories_order_update(json_decode($this->input->post('data'), true));
    }

    //todo
    public function categories_get()
    {
        echo json_encode($this->glass_model->get_categories());
    }

    //todo
    public function category_add_ajax()
    {
        if($this->input->post()){
            echo $this->glass_model->add_category_ajax(
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
            echo $this->glass_model->update_category_ajax(
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
        if($this->input->post()) $this->glass_model->remove_category((int)$this->input->post('id'));
    }
    
    

    public function items_index($category = 0, $per_page = 20, $start = false)
    {

        $_SESSION['selected_glass_category'] = $category;
        $_SESSION['selected_glass_per_page'] = $per_page;
        $_SESSION['selected_glass_pagination'] = $start;

        $this->load->library('pagination');
        $config['base_url'] = site_url( $this->controller_name . '/items_index/'.$category .'/'.$per_page.'/');
        $config['total_rows'] = $this->glass_model->get_items_count($category);
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

        $data['items'] = $this->glass_model->get_items($category, $start, $config['per_page']);
        $data['categories'] = $this->glass_model->get_categories();
        $data['materials_categories'] = buildTree($this->glass_model->get_categories(), 'parent', 'id');

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
        $this->load->view('glass/items/index', $data);
        $this->load->view('templates/footer', $data);
    }


    public function items_add()
    {
        if ($this->input->post()) {

            $this->load->helper('form');
            $this->load->library('form_validation');
            $this->form_validation->set_rules('name', 'Название', 'required');

            if ($this->form_validation->run() === FALSE) {
                $errors = validation_errors();
                echo json_encode(['error' => $errors]);
                exit;
            } else {
                $item_id = $this->glass_model->add_item(array("name"=>""));

                if(!$this->config->item('bpconfig')){
                    $save_path = dirname(FCPATH).'/images/glass_materials/';
                    $abs_save_path = 'images/glass_materials/';
                } else {
                    $save_path = $_SERVER["DOCUMENT_ROOT"] . '/common_assets/images/glass_materials/';
                    $abs_save_path = '/common_assets/images/glass_materials/';
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
                $data['order'] = $this->input->post('order');
                $data['category'] = $this->input->post('category');
                $data['active'] = $this->input->post('active');

                $params->id = $item_id;
                $params->name = $data['name'];
                $params->code = $data['code'];
                $params->category = $data['category'];

                $data['params'] = json_encode($params);

                echo json_encode(['success' => '']);
                $this->glass_model->update_item($item_id, $data);
            }

        } else {
            $data['materials_categories'] = buildTree($this->glass_model->get_categories(), 'parent', 'id');
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
            $this->load->view('glass/items/add', $data);
            $this->load->view('templates/footer', $data);
        }
    }

    public function items_edit($id)
    {


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
                    $save_path = dirname(FCPATH) . '/images/glass_materials/';
                    $abs_save_path = 'images/glass_materials/';
                } else {
                    $save_path = $_SERVER["DOCUMENT_ROOT"] . '/common_assets/images/glass_materials/';
                    $abs_save_path = '/common_assets/images/glass_materials/';
                }

                if (!is_dir($save_path)) {
                    mkdir($save_path);
                }

                $cur_params = json_decode($this->glass_model->get_item($item_id)['params']);



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
                $data['order'] = $this->input->post('order');


                $params->id = $item_id;
                $params->name = $data['name'];
                $params->code = $data['code'];
                $params->category = $data['category'];

                $data['params'] = json_encode($params);

                echo json_encode(['success' => '']);
                $this->glass_model->update_item($item_id, $data);
            }


        } else {
            $data['materials_categories'] = buildTree($this->glass_model->get_categories(), 'parent', 'id');
            $data['item'] = $this->glass_model->get_item($id);
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
            $this->load->view('glass/items/edit', $data);
            $this->load->view('templates/footer', $data);
            return;
        }

    }




    public function items_remove($id)
    {
        $item = $this->glass_model->get_item($id);

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


        $this->glass_model->remove_items($id);
        redirect('glass/items_index/', 'refresh');
    }


    public function items_catalog_index($category = false, $per_page = false, $start = false)
    {


        $_SESSION['selected_glass_catalog_category'] = $category;
        $_SESSION['selected_glass_catalog_per_page'] = $per_page;
        $_SESSION['selected_glass_catalog_pagination'] = $start;

        $this->load->library('pagination');
        $config['base_url'] = site_url( $this->controller_name . '/items_catalog_index/'.$category .'/'.$per_page.'/');
        $config['total_rows'] = $this->glass_model->get_items_count_catalog($category);
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

        $data['items'] = $this->glass_model->get_items_catalog($category, $start, $config['per_page']);
        $data['categories'] = $this->glass_model->get_categories_catalog();
        $data['materials_categories'] = buildTree($this->glass_model->get_categories_catalog(), 'parent', 'id');
        $data['acc_categories'] = $this->glass_model->get_categories();
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
        $this->load->view('glass/items/catalog', $data);
        $this->load->view('templates/footer', $data);
    }

    public function add_item_from_catalog($id, $category)
    {
        $cat_item = $this->glass_model->get_item_catalog($id);
        $item_id = $this->glass_model->add_item(array("name"=>""));

        $tmp_params = json_decode($cat_item['params']);
        $tmp_params->category = (int)$category;
        $tmp_params->id = (int)$item_id;
        $tmp_params->name = $cat_item['name'];

        $data = array();
        $data['name'] = $cat_item['name'];
        $data['category'] = (int)$category;
        $data['params'] = json_encode($tmp_params);
        $data['active'] = 1;
        $data['code'] = '';



        $this->glass_model->update_item($item_id, $data);
    }

	public function set_active_ajax($id, $val) {
		$this->glass_model->set_active($id, $val);
	}

}