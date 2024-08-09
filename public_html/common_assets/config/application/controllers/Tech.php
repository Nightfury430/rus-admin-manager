<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Tech extends CI_Controller {

	private $controller_name = "tech";

	public function __construct()
    {
        parent::__construct();
        $this->load->helper('url_helper');
        $this->load->model('tech_model');
        $this->load->library('session');
        $this->load->model('Menu_model');
        if(!$this->session->username || $this->session->username != $this->config->item('username')){
            redirect('login', 'refresh');
        }

        if(!$this->config->item('sub_account')) $this->config->set_item('sub_account', false);
        if($this->config->item('sub_account') == true) redirect('settings', 'refresh');
    }

    public function categories_index()
    {
        $data['categories'] = $this->tech_model->get_categories();
//        $data['categories'] = buildTree($this->tech_model->get_categories(), 'parent', 'id');
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


	    $data['header'] = $data['lang_arr']['tech_categories_list'];


	    $this->load->view('templates/header',$data);
//        $this->load->view('tech/categories/index', $data);
//	    $this->load->view('common/categories_index', $data);
        $this->load->view('common/categories_index_new', $data);
        $this->load->view('templates/footer', $data);
    }

    //todo
    public function categories_order_update()
    {
        if($this->input->post()) $this->tech_model->categories_order_update(json_decode($this->input->post('data'), true));
    }

    //todo
    public function categories_get()
    {
        echo json_encode($this->tech_model->get_categories());
    }

    //todo
    public function category_add_ajax()
    {
        if($this->input->post()){
            echo $this->tech_model->add_category_ajax(
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
            echo $this->tech_model->update_category_ajax(
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
        if($this->input->post()) $this->tech_model->remove_category((int)$this->input->post('id'));
    }


    public function categories_add()
    {
        $this->load->helper('form');
        $this->load->library('form_validation');


        $this->form_validation->set_rules('name', 'Название', 'required');

        if ($this->form_validation->run() === FALSE)
        {
            $data['categories'] = $this->tech_model->get_categories();
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
            $this->load->view('tech/categories/add', $data);
            $this->load->view('templates/footer', $data);
        }
        else
        {
            $this->tech_model->add_category();
            redirect('tech/categories_index/', 'refresh');
        }
    }

    public function categories_edit($id)
    {

        $this->load->helper('form');
        $this->load->library('form_validation');

        $this->form_validation->set_rules('name', 'Название', 'required');

        if ($this->form_validation->run() === FALSE)
        {
            $data['category'] = $this->tech_model->get_category($id);
            $data['has_children'] = $this->tech_model->check_category_for_children($id);

            if($data['has_children'] == 0){
                $data['categories'] = $this->tech_model->get_categories();
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
            $this->load->view('tech/categories/edit', $data);
            $this->load->view('templates/footer', $data);
        }
        else
        {
            $this->tech_model->update_category($id);
            redirect('tech/categories_index/', 'refresh');
        }
    }

    public function categories_remove($id)
    {
        if($this->tech_model->check_category_for_children($id) == 0){
            $this->tech_model->remove_category($id);
        }

        redirect('tech/categories_index/', 'refresh');
    }

	public function categories_set_active_ajax($id, $val) {
		$this->tech_model->set_category_active($id, $val);
	}

    public function items_index($category = false, $per_page = false, $start = false)
    {

        if($category == null || $per_page == null){

            $category = 0;
            $per_page = 20;

            redirect('tech/items_index/'.$category .'/'.$per_page.'/', 'refresh');
        }

        $_SESSION['selected_tech_items_category'] = $category;
        $_SESSION['selected_tech_items_per_page'] = $per_page;
        $_SESSION['selected_tech_items_pagination'] = $start;


        $this->load->library('pagination');
        $config['base_url'] = site_url('tech/items_index/'.$category .'/'.$per_page.'/');
        $config['total_rows'] = $this->tech_model->get_items_count($category);
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

        $config['num_links'] = 2;

        $config['uri_segment'] = 5;


        $data['items'] = $this->tech_model->get_items($category, $start, $config['per_page']);
        $data['categories'] = $this->tech_model->get_categories();
        $data['materials_categories'] = buildTree($this->tech_model->get_categories(), 'parent', 'id');

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
        $this->load->view('tech/items/index', $data);
        $this->load->view('templates/footer', $data);
    }


	public function items_add($id = false)
	{

		$data['materials_categories'] = buildTree($this->tech_model->get_categories(), 'parent', 'id');
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
        $data['controller_name'] = 'tech';
		if($id) $data['id'] = $id;

        $data['js_include'] = [
            'libs/spectrum/spectrum.js',
            'libs/vue.min.js',
            'libs/vue/vue_select/vue-select.js',
            'admin_js/vue/filemanager2.js?'.md5(date('m-d-Y-His A e')),
            'libs/vue/draggable/sortable.min.js',
            'libs/vue/draggable/vuedraggable.min.js',
            'libs/jszip.min.js',
            'libs/jszip.utils.min.js',
            'admin_js/vue/kitchen/3d_model.js',
        ];

        $data['css_include'] = [
            'fonts/icons/new/style.css?'.md5(date('m-d-Y-His A e')),
            'libs/vue/vue_select/vue-select.css',
            'libs/spectrum/spectrum.css'
        ];

        $data['include'] = 'tech/items/add';
        $data['modules'] = ['3d_preview', 'material_picker'];
        $data['menus_list'] = $this->Menu_model->get_all_menus();
        $this->load->view('templates/layout', $data);
	}
    public function items_add_ajax($id = false) {
        if(isset($_POST)){
            if($id){
                echo $id;
                $item_id = $id;
            } else {
                $item_id = $this->tech_model->add_item();
            }

            $data = json_decode($_POST['data']);
            $data->id = $item_id;


            if($data->order == 0) $data->order = $item_id * 100;

            $data->code = $data->variants[0]->code;

            $model_data = array();
            $model_data['name'] = $data->name;
            $model_data['code'] = $data->variants[0]->code;
            $model_data['category'] = $data->category;
            $model_data['active'] = $data->active;
            $model_data['icon'] = $data->icon;
            $model_data['model_data'] = json_encode($data);
            $model_data['order'] = $data->order;


            $this->tech_model->add_item_data($item_id, $model_data);
        }
    }



    public function items_remove($id)
    {
        $this->load->helper('file');
        if (is_dir(dirname(FCPATH).'/models/tech/'. $id)) {
            delete_files(dirname(FCPATH) . '/models/tech/' . $id, true);
            rmdir(dirname(FCPATH) . '/models/tech/' . $id);
        }
        $this->tech_model->remove_items($id);
        redirect('tech/items_index/'.$_SESSION['selected_tech_items_category'].'/'.$_SESSION['selected_tech_items_per_page'], 'refresh');
    }


    public function items_add_common($id = false)
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

        $data['controller_name'] = 'tech';
        $data['common'] = 1;
        if($id) $data['id'] = $id;
        $data['materials_categories'] = [];
        $this->load->view('templates/header', $data);
        $this->load->view('tech/items/add', $data);
        $this->load->view('templates/footer', $data);

    }

    public function items_add_ajax_common($id = false) {
        if($this->input->post()){
            $this->load->model('common_model');

            $data = json_decode($_POST['data']);


            $model_data = array();
            $model_data['name'] = $data->name;
            $model_data['code'] = $data->variants[0]->code;
            $model_data['category'] = $data->category;
            $model_data['active'] = $data->active;
            $model_data['icon'] = $data->icon;
            $model_data['model_data'] = json_encode($data);

            if(($id)){
                $data->id = $id;
                $this->common_model->update_where(
                    'Tech_items',
                    'id',
                    $id,
                    $model_data
                );
            } else {
                $this->common_model->add_data( 'Tech_items', $model_data);
            }



        }
    }




    public function items_catalog_index($category = false, $per_page = false, $start = false)
    {
        $data['items'] = $this->tech_model->get_catalog_items();
        $data['categories'] = $this->tech_model->get_catalog_categories();

        $data['acc_categories'] = $this->tech_model->get_categories();
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
        $this->load->view('tech/items/catalog', $data);
        $this->load->view('templates/footer', $data);
    }


    public function add_item_from_catalog($id, $category)
    {
        $cat_item = $this->tech_model->get_catalog_item($id);

        $data = array();

        $item_id = $this->tech_model->add_item();

        if(isset($cat_item['model_data'])){

            $data['name'] = $cat_item['name'];
            $data['icon'] = $cat_item['icon'];
            $data['category'] = $category;
            $data['active'] = 1;

            $json = json_decode($cat_item['model_data'], true);
            $json['id'] = $item_id;
            $json['category'] = $category;
            $data['model_data'] = json_encode($json);



        } else {

            $data['variants'] = $cat_item['variants'];
            $data['icon'] = $cat_item['icon'];
            $data['model'] = $cat_item['model'];
            $data['material'] = $cat_item['material'];
            $data['name'] = $cat_item['name'];
            $data['category'] = $category;
            $data['code'] = '';
            $data['active'] = 1;
            $data['group'] = $cat_item['group'];
            $data['wall_panel'] = $cat_item['wall_panel'];

            $data['drag_mode'] = $cat_item['drag_mode'];
            $data['sizes_available'] = $cat_item['sizes_available'];
            $data['model_data'] = $cat_item['model_data'];

        }

        $data['order'] = 100000;



        echo json_encode($this->tech_model->add_item_data($item_id, $data));
    }


	public function set_active_ajax($id, $val) {
		$this->tech_model->set_active($id, $val);
	}



	public function get_categories_ajax() {
		echo json_encode($this->tech_model->get_categories());
	}

	public function item_get_ajax( $id ) {
		echo json_encode($this->tech_model->get_item($id));
	}


    public function copy_to_catalog_common($id=0, $category = 1)
    {
        $this->load->model('common_model');

        if(!$id){
            echo 'ID не указан';
            exit;
        }

        $item = $this->tech_model->get_item($id);

        if (strpos($item['icon'], 'common_assets') !== false) {
            echo 'Уже в каталоге';
            exit;
        }
        if(empty($item)){
            echo 'Не найдено';
            exit;
        }





        $item_id = $id;
//        $common_id = 24;
        $common_id = $this->common_model->add_data('Tech_items', array('name'=>''));


        $models_path = dirname(FCPATH).'/models/tech/'. $item_id;
        $common_assets_path = $_SERVER['DOCUMENT_ROOT'] . '/common_assets/models/tech/' . $common_id;

        if(!file_exists($common_assets_path)) mkdir($common_assets_path);

        recursiveCopy($models_path, $common_assets_path, 1);

//        print_pre($models_path);
//        print_pre($common_assets_path);

//        print_r($this->common_model->get_items_count('Facades_items', 'Facades_categories', 0));

        $item['material'] = json_decode($item['material'], true);


        $old_model_string = 'models/tech/' . $item_id;
        $new_model_string = '/common_assets/models/tech/' . $common_id;


        $item['icon'] = str_replace($old_model_string, $new_model_string, $item['icon']);
        $item['model'] = str_replace($old_model_string, $new_model_string, $item['model']);
        if(isset($item['material']['params']['map'])){
            $item['material']['params']['map'] = str_replace($old_model_string, $new_model_string, $item['material']['params']['map']);
        }

        if(isset($item['model_data'])){
            $item['model_data'] = json_decode($item['model_data'], true);
            $item['model_data']['icon'] = str_replace($old_model_string, $new_model_string, $item['model_data']['icon']);
            if(isset($item['model_data']['material']['params']['map'])){
                $item['model_data']['material']['params']['map'] = str_replace($old_model_string, $new_model_string, $item['model_data']['material']['params']['map']);
            }
            if(isset($item['model_data']['variants'])){
                foreach ($item['model_data']['variants'] as &$variant){
                    $variant['model'] = str_replace($old_model_string, $new_model_string, $variant['model']);
                }
            }
        }





        $common_item = $item;
        $common_item['category'] = $category;
        unset($common_item['id']);
//        if(isset($item['model_data'])){
//            $item['model_data']['id'] = $common_id;
//        }


        $common_item['model_data'] = json_encode($common_item['model_data']);
        $common_item['material'] = json_encode($common_item['material']);
        unset($common_item['order']);

        $item['model_data'] = json_encode($item['model_data']);
        $item['material'] = json_encode($item['material']);

        unset($item['id']);

//        print_pre($common_item);


        print_pre($item);



        $this->common_model->update_where('Tech_items', 'id', $common_id, $common_item);
        $this->tech_model->add_item_data($id, $item);

        array_map( 'unlink', array_filter((array) glob($models_path.'/*') ) );

        echo 'Готово';
    }


}