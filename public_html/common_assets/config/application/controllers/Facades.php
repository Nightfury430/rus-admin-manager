<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Facades extends CI_Controller {

	public function __construct()
	{
		parent::__construct();
		$this->load->helper('url_helper');
		$this->load->model('facades_model');
		$this->load->library('session');

		if(!$this->session->username || $this->session->username != $this->config->item('username')){
			redirect('login', 'refresh');
		}

		if(!$this->config->item('sub_account')) $this->config->set_item('sub_account', false);
		if($this->config->item('sub_account') == true) redirect('settings', 'refresh');
	}


	public function item( $id = false ) {

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

		if($id) $data['id'] = $id;
        $data['controller_name'] = 'facades';
		$this->load->view('templates/header', $data);
		$this->load->view('facades/items/add', $data);
		$this->load->view('templates/footer', $data);
	}

    public function item_common( $id = false ) {

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
        $data['controller_name'] = 'facades';
        $data['common'] = 1;
        if($id) $data['id'] = $id;
        $this->load->view('templates/header', $data);
        $this->load->view('facades/items/add', $data);
        $this->load->view('templates/footer', $data);
    }


	public function items_add_ajax($id = false) {
		if($this->input->post()){

			if(($id)){
				$item_id = $id;
			} else {
				$item_id = $this->facades_model->add_item_id();
			}

			$data = json_decode($_POST['data']);
			$data->id = $item_id;

			$facade_data = array();
			$facade_data['name'] = $data->name;
			$facade_data['order'] = $data->order;
			$facade_data['category'] = $data->category;
			$facade_data['active'] = $data->active;
			$facade_data['icon'] = $data->icon;
			$facade_data['materials'] = json_encode($data->materials);
			$facade_data['data'] = json_encode($data);

			$this->facades_model->update_item($item_id, $facade_data);
		}
	}

    public function items_add_ajax_common($id = false) {
        if($this->input->post()){
            $this->load->model('common_model');

            $data = json_decode($_POST['data']);


            $facade_data = array();
            $facade_data['name'] = $data->name;
            $facade_data['order'] = $data->order;
            $facade_data['category'] = $data->category;
            $facade_data['active'] = $data->active;
            $facade_data['icon'] = $data->icon;
            $facade_data['materials'] = json_encode($data->materials);
            $facade_data['data'] = json_encode($data);

            if(($id)){
                $data->id = $id;
                $this->common_model->update_where(
                    'Facades_items',
                    'id',
                    $id,
                    $facade_data
                );
            } else {
                $this->common_model->add_data( 'Facades_items', $facade_data);
            }



        }
    }


	public function item_get_ajax( $id ) {
		echo json_encode($this->facades_model->get_item($id));
	}

	public function items_catalog_index($category = false, $per_page = false, $start = false)
	{
		$data['items'] = $this->facades_model->get_catalog_items();
		$data['categories'] = $this->facades_model->get_catalog_categories();

//		$data['acc_categories'] = $this->facades_model->get_categories();
		$data['acc_categories'] = buildTree($this->facades_model->get_categories(), 'parent', 'id');

		$this->load->model('materials_model');
		$data['materials'] = $this->materials_model->get_categories();
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
		$this->load->view('facades/items/catalog', $data);
		$this->load->view('templates/footer', $data);
	}

	public function add_item_from_catalog($id, $category)
	{
		$cat_item = $this->facades_model->get_catalog_item($id);

		$data = array();

		if($cat_item['data']){

			$data['name'] = $cat_item['name'];
			$data['icon'] = $cat_item['icon'];
			$data['category'] = $category;
			$data['active'] = 1;
			$data['materials'] = json_encode($this->input->post('materials'));

			$item_data = json_decode($cat_item['data']);

			$item_id = $this->facades_model->add_item();

			$item_data->id = $item_id;
			$item_data->name = $cat_item['name'];
			$item_data->category = $category;
			$item_data->materials = $this->input->post('materials');
			$data['data'] = json_encode($item_data);

			$this->facades_model->update_item($item_id, $data);
		} else {
			$item_id = $this->facades_model->add_item();

			$data['name'] = $cat_item['name'];
			$data['icon'] = $cat_item['icon'];
			$data['full'] = $cat_item['full'];
			$data['window'] = $cat_item['window'];
			$data['frame'] = $cat_item['frame'];
			$data['radius_full'] = $cat_item['radius_full'];
			$data['radius_window'] = $cat_item['radius_window'];
			$data['radius_frame'] = $cat_item['radius_frame'];
			$data['name'] = $cat_item['name'];
			$data['category'] = $category;
			$data['active'] = 1;
			$data['materials'] = json_encode($this->input->post('materials'));

			$this->facades_model->update_item($item_id, $data);
		}

		echo json_encode('ok');

	}




	public function get_categories_ajax() {
		echo json_encode($this->facades_model->get_categories());
	}

	public function set_active_ajax($id, $val) {
		$this->facades_model->set_active($id, $val);
	}
}