<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Decorations extends CI_Controller {

	public function __construct()
	{
		parent::__construct();
		$this->load->helper('url_helper');
		$this->load->model('decorations_model');
		$this->load->library('session');

		if(!$this->session->username || $this->session->username != $this->config->item('username')){
			redirect('login', 'refresh');
		}

		if(!$this->config->item('sub_account')) $this->config->set_item('sub_account', false);
		if($this->config->item('sub_account') == true) redirect('settings', 'refresh');
	}

	public function categories_index()
	{
//        $data['categories'] = $this->decorations_model->get_categories();
		$data['categories'] = buildTree($this->decorations_model->get_categories(), 'parent', 'id');

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
		$this->load->view('templates/header',$data);
		$this->load->view('decorations/categories/index', $data);
		$this->load->view('templates/footer', $data);
	}

	public function categories_add()
	{
		$this->load->helper('form');
		$this->load->library('form_validation');


		$this->form_validation->set_rules('name', 'Название', 'required');

		if ($this->form_validation->run() === FALSE)
		{
			$data['categories'] = $this->decorations_model->get_categories();
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
			$this->load->view('decorations/categories/add', $data);
			$this->load->view('templates/footer', $data);
		}
		else
		{
			$this->decorations_model->add_category();
			redirect('decorations/categories_index/', 'refresh');
		}
	}

	public function categories_edit($id)
	{

		$this->load->helper('form');
		$this->load->library('form_validation');

		$this->form_validation->set_rules('name', 'Название', 'required');

		if ($this->form_validation->run() === FALSE)
		{
			$data['category'] = $this->decorations_model->get_category($id);
			$data['has_children'] = $this->decorations_model->check_category_for_children($id);

			if($data['has_children'] == 0){
				$data['categories'] = $this->decorations_model->get_categories();
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
			$this->load->view('decorations/categories/edit', $data);
			$this->load->view('templates/footer', $data);
		}
		else
		{
			$this->decorations_model->update_category($id);
			redirect('decorations/categories_index/', 'refresh');
		}
	}

	public function categories_remove($id)
	{
		if($this->decorations_model->check_category_for_children($id) == 0){
			$this->decorations_model->remove_category($id);
		}

		redirect('decorations/categories_index/', 'refresh');
	}

	public function get_categories_ajax() {
		echo json_encode($this->decorations_model->get_categories());
	}


	public function items_index($category = false, $per_page = false, $start = false)
	{

		if($category == null || $per_page == null){

			$category = 0;
			$per_page = 20;

			redirect('decorations/items_index/'.$category .'/'.$per_page.'/', 'refresh');
		}

		$_SESSION['selected_decorations_items_category'] = $category;
		$_SESSION['selected_decorations_items_per_page'] = $per_page;
		$_SESSION['selected_decorations_items_pagination'] = $start;


		$this->load->library('pagination');
		$config['base_url'] = site_url('decorations/items_index/'.$category .'/'.$per_page.'/');
		$config['total_rows'] = $this->decorations_model->get_items_count($category);
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


		$data['items'] = $this->decorations_model->get_items($category, $start, $config['per_page']);
		$data['categories'] = $this->decorations_model->get_categories();
		$data['materials_categories'] = buildTree($this->decorations_model->get_categories(), 'parent', 'id');

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
		$this->load->view('decorations/items/index', $data);
		$this->load->view('templates/footer', $data);
	}

	public function items_add()
	{

		$this->load->helper('form');
		$this->load->library('form_validation');


		$this->form_validation->set_rules('name', 'Название', 'required');


		if ($this->form_validation->run() === FALSE)
		{
			$data['materials_categories'] = buildTree($this->decorations_model->get_categories(), 'parent', 'id');
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
			$this->load->view('decorations/items/add', $data);
			$this->load->view('templates/footer', $data);
		}
		else
		{
			$data = array();

			$item_id = $this->decorations_model->add_item();

			mkdir(dirname(FCPATH).'/models/decorations/'.$item_id);

			$material = new stdClass();
			$material->params = new stdClass();
			$material->add_params = new stdClass();

			$material->type = "Standart";


			$material->params->color = $this->input->post('color');
			$material->params->roughness = $this->input->post('roughness');
			$material->params->metalness = $this->input->post('metalness');
			$material->params->transparent = $this->input->post('transparent');


			if($this->input->post('real_width') != ''){
				$material->add_params->real_width = $this->input->post('real_width');
			}

			if($this->input->post('real_height') != ''){
				$material->add_params->real_height = $this->input->post('real_height');
			}

			if($this->input->post('stretch_width') != ''){
				$material->add_params->stretch_width = $this->input->post('stretch_width');
			}

			if($this->input->post('stretch_height') != ''){
				$material->add_params->stretch_height = $this->input->post('stretch_height');
			}

			if($this->input->post('wrapping') != ''){
				$material->add_params->wrapping = $this->input->post('wrapping');
			}


			$variants = [];
			foreach ($this->input->post('variants') as $variant){
				$temp_var = explode(';', $variant);

				$var = new stdClass();
				$var->width = $temp_var[0];
				$var->height = $temp_var[1];
				$var->depth = $temp_var[2];

				$variants[] = $var;
			}

			$data['variants'] = json_encode($variants);

			$config['upload_path'] = dirname(FCPATH).'/models/decorations/'. $item_id;
			$config['allowed_types'] = 'jpg|jpeg|png|fbx';
			$config['file_name'] = 'icon_'.$item_id;
			$config['file_ext_tolower'] = true;
			$config['overwrite'] = true;

			$this->load->library('upload', $config);

			if(isset($_FILES['icon']) && $_FILES['icon']['size'] > 0) {
				if ($this->upload->do_upload('icon'))
				{
					$fdata = array('upload_data' => $this->upload->data());
					$data['icon'] = 'models/decorations/'. $item_id .'/' . $fdata['upload_data']['file_name'];

				} else {
					print_r($this->upload->display_errors());
					$this->decorations_model->remove_items($item_id);
					exit;
				}
			}

			$config['file_name'] = 'model_'.$item_id;
			$this->upload->initialize($config);
			if(isset($_FILES['model']) && $_FILES['model']['size'] > 0) {
				if ($this->upload->do_upload('model'))
				{
					$fdata = array('upload_data' => $this->upload->data());
					$data['model'] = 'models/decorations/'. $item_id .'/' . $fdata['upload_data']['file_name'];

				} else {
					print_r($this->upload->display_errors());
					$this->decorations_model->remove_items($item_id);
					exit;
				}
			};

			$config['file_name'] = 'map_'.$item_id;
			$this->upload->initialize($config);
			if(isset($_FILES['map']) && $_FILES['map']['size'] > 0) {
				if ($this->upload->do_upload('map'))
				{
					$fdata = array('upload_data' => $this->upload->data());
					$material->params->map = 'models/decorations/'. $item_id .'/' . $fdata['upload_data']['file_name'];

				} else {
					print_r($this->upload->display_errors());
					$this->decorations_model->remove_items($item_id);
					exit;
				}
			};

			$data['material'] = json_encode($material);

			$data['name'] = $this->input->post('name');
			$data['category'] = $this->input->post('category');
			$data['active'] = $this->input->post('active');
			$data['group'] = $this->input->post('group');
			$data['code'] = $this->input->post('code');
			$data['wall_panel'] = $this->input->post('wall_panel');


			$data['drag_mode'] = $this->input->post('drag_mode');
			$data['sizes_available'] = $this->input->post('sizes_available');

//            echo '<pre>';
//            print_r($data);
//            echo '</pre>';
//            echo '<br>';
//            echo json_encode($material);

//            exit;

			$this->decorations_model->add_item_data($item_id, $data);


			redirect('decorations/items_index/'.$_SESSION['selected_decorations_items_category'].'/'.$_SESSION['selected_decorations_items_per_page'], 'refresh');
		}
	}


	public function items_edit($id)
	{

		$this->load->helper('form');
		$this->load->library('form_validation');

		$this->form_validation->set_rules('name', 'Название', 'required');

		if ($this->form_validation->run() === FALSE)
		{
			$data['materials_categories'] = buildTree($this->decorations_model->get_categories(), 'parent', 'id');
			$data['item'] = $this->decorations_model->get_item($id);
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
			$this->load->view('decorations/items/edit', $data);
			$this->load->view('templates/footer', $data);
		}
		else
		{

			$data = array();

			$item_id = $id;

			$curr_item = $this->decorations_model->get_item($id);

			$cur_item_material = json_decode($curr_item['material']);


			$material = new stdClass();
			$material->params = new stdClass();
			$material->add_params = new stdClass();

			$material->type = "Standart";


			$material->params->color = $this->input->post('color');
			$material->params->roughness = $this->input->post('roughness');
			$material->params->metalness = $this->input->post('metalness');
			$material->params->transparent = $this->input->post('transparent');


			if($this->input->post('real_width') != ''){
				$material->add_params->real_width = $this->input->post('real_width');
			}

			if($this->input->post('real_height') != ''){
				$material->add_params->real_height = $this->input->post('real_height');
			}

			if($this->input->post('stretch_width') != ''){
				$material->add_params->stretch_width = $this->input->post('stretch_width');
			}

			if($this->input->post('stretch_height') != ''){
				$material->add_params->stretch_height = $this->input->post('stretch_height');
			}

			if($this->input->post('wrapping') != ''){
				$material->add_params->wrapping = $this->input->post('wrapping');
			}



			$variants = [];
			foreach ($this->input->post('variants') as $variant){
				$temp_var = explode(';', $variant);

				$var = new stdClass();
				$var->width = $temp_var[0];
				$var->height = $temp_var[1];
				$var->depth = $temp_var[2];

				$variants[] = $var;
			}

			$data['variants'] = json_encode($variants);

			$config['upload_path'] = dirname(FCPATH).'/models/decorations/'. $item_id;
			$config['allowed_types'] = 'jpg|jpeg|png|fbx';
			$config['file_name'] = 'icon_'.$item_id;
			$config['file_ext_tolower'] = true;
			$config['overwrite'] = true;

			$this->load->library('upload', $config);

			if(isset($_FILES['icon']) && $_FILES['icon']['size'] > 0) {
				if ($this->upload->do_upload('icon'))
				{
					$fdata = array('upload_data' => $this->upload->data());
					$data['icon'] = 'models/decorations/'. $item_id .'/' . $fdata['upload_data']['file_name'];

				} else {
					print_r($this->upload->display_errors());
					exit;
				}
			}


			if(isset($_FILES['model']) && $_FILES['model']['size'] > 0) {

				$config['file_name'] = 'model_'.$item_id;
				$this->upload->initialize($config);

				if ($this->upload->do_upload('model'))
				{
					$fdata = array('upload_data' => $this->upload->data());
					$data['model'] = 'models/decorations/'. $item_id .'/' . $fdata['upload_data']['file_name'];

				} else {
					print_r($this->upload->display_errors());
					exit;
				}
			};


			if(isset($_FILES['map']) && $_FILES['map']['size'] > 0) {

				$config['file_name'] = 'map_'.$item_id;
				$this->upload->initialize($config);

				if ($this->upload->do_upload('map'))
				{
					$fdata = array('upload_data' => $this->upload->data());
					$material->params->map = 'models/decorations/'. $item_id .'/' . $fdata['upload_data']['file_name'];

				} else {
					print_r($this->upload->display_errors());
					exit;
				}
			} else if($this->input->post('delete_map') == 1){
				unset($material->params->map);
			} else if(isset($cur_item_material->params->map)){
				$material->params->map = $cur_item_material->params->map;
			}

			$data['material'] = json_encode($material);

			$data['name'] = $this->input->post('name');
			$data['category'] = $this->input->post('category');
			$data['active'] = $this->input->post('active');
			$data['group'] = $this->input->post('group');
			$data['code'] = $this->input->post('code');
			$data['wall_panel'] = $this->input->post('wall_panel');

			$data['drag_mode'] = $this->input->post('drag_mode');
			$data['sizes_available'] = $this->input->post('sizes_available');
//            echo '<pre>';
//            print_r($data);
//            echo '</pre>';
//            echo '<br>';
//            echo json_encode($material);

//            exit;

			$this->decorations_model->add_item_data($item_id, $data);


			redirect('decorations/items_index/'.$_SESSION['selected_decorations_items_category'].'/'.$_SESSION['selected_decorations_items_per_page'], 'refresh');



		}
	}


	public function items_remove($id)
	{
		$this->load->helper('file');
		if (is_dir(dirname(FCPATH).'/models/decorations/'. $id)) {
			delete_files(dirname(FCPATH) . '/models/decorations/' . $id, true);
			rmdir(dirname(FCPATH) . '/models/decorations/' . $id);
		}
		$this->decorations_model->remove_items($id);
		redirect('decorations/items_index/'.$_SESSION['selected_decorations_items_category'].'/'.$_SESSION['selected_decorations_items_per_page'], 'refresh');
	}




	public function items_catalog_index($category = false, $per_page = false, $start = false)
	{
		$data['items'] = $this->decorations_model->get_catalog_items();
		$data['categories'] = $this->decorations_model->get_catalog_categories();

		$data['acc_categories'] = $this->decorations_model->get_categories();
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
		$this->load->view('decorations/items/catalog', $data);
		$this->load->view('templates/footer', $data);
	}


	public function add_item_from_catalog($id, $category)
	{
		$cat_item = $this->decorations_model->get_catalog_item($id);

		$data = array();

		$item_id = $this->decorations_model->add_item();

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


		$this->decorations_model->add_item_data($item_id, $data);
	}

}




