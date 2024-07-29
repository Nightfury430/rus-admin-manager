<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Handles extends CI_Controller {

    public function __construct()
    {
        parent::__construct();
        $this->load->helper('url_helper');
        $this->load->model('handles_model');
        $this->load->library('session');

        if(!$this->session->username || $this->session->username != $this->config->item('username')){
            redirect('login', 'refresh');
        }

        if(!$this->config->item('sub_account')) $this->config->set_item('sub_account', false);
        if($this->config->item('sub_account') == true) redirect('settings', 'refresh');
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

        if($id) $data['id'] = $id;
        $data['controller_name'] = 'handles';

        $this->load->view('templates/header', $data);
        $this->load->view('handles/item', $data);
        $this->load->view('templates/footer', $data);
    }

    public function item_common($id = false)
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

        if($id) $data['id'] = $id;
        $data['controller_name'] = 'handles';
        $data['common'] = 1;
        $this->load->view('templates/header', $data);
        $this->load->view('handles/item', $data);
        $this->load->view('templates/footer', $data);
    }


    public function items_add_ajax($id = false)
    {
        if(isset($_POST)){

            $data = json_decode($_POST['data'], true);

            $model_data = array();

            $model_data['name'] = $data['name'];
            $model_data['category'] = $data['category'];
            $model_data['icon'] = $data['icon'];
            $model_data['model'] = $data['model'];
            $model_data['material'] = json_encode($data['material']);
            $model_data['variants'] = json_encode($data['variants']);
            $model_data['active'] = $data['active'];
            $model_data['type'] = $data['type'];
            $model_data['order'] = $data['order'];


            if($id){
                $this->handles_model->add_item_data($id, $model_data);
            } else {
                $this->handles_model->add_item_new($id, $model_data);
            }
        }
    }

    public function items_add_ajax_common($id = false)
    {
        if(isset($_POST)){
            $this->load->model('common_model');
            $data = json_decode($_POST['data'], true);

            $model_data = array();

            $model_data['name'] = $data['name'];
            $model_data['category'] = $data['category'];
            $model_data['icon'] = $data['icon'];
            $model_data['model'] = $data['model'];
            $model_data['material'] = json_encode($data['material']);
            $model_data['variants'] = json_encode($data['variants']);
            $model_data['active'] = $data['active'];
            $model_data['type'] = $data['type'];


            if($id){
                $this->common_model->update_where(
                    'Handles_items',
                    'id',
                    $id,
                    $model_data
                );
            } else {
                $this->common_model->add_data( 'Handles_items', $model_data);
            }
        }
    }



    public function items_add()
    {

        $this->load->helper('form');
        $this->load->library('form_validation');


        $this->form_validation->set_rules('name', 'Название', 'required');


        if ($this->form_validation->run() === FALSE)
        {
            $data['materials_categories'] = buildTree($this->handles_model->get_categories(), 'parent', 'id');
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
            $this->load->view('handles/items/add', $data);
            $this->load->view('templates/footer', $data);
        }
        else
        {
            $data = array();

            $item_id = $this->handles_model->add_item();
            if (!file_exists(dirname(FCPATH).'/models/handles/'.$item_id)){
                mkdir(dirname(FCPATH).'/models/handles/'.$item_id);
            }


            $material = new stdClass();
            $material->params = new stdClass();
            $material->add_params = new stdClass();

            $material->type = "Standart";


            $material->params->color = $this->input->post('color');
            $material->params->transparent = $this->input->post('transparent');
            $material->params->roughness = $this->input->post('roughness');
            $material->params->metalness = $this->input->post('metalness');


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
                $var->code = $temp_var[3];
                $var->axis_size = $temp_var[4];
                $var->price = $temp_var[5];
                $var->offset_y = $temp_var[6];

                $variants[] = $var;
            }

            $data['variants'] = json_encode($variants);

            $config['upload_path'] = dirname(FCPATH).'/models/handles/'. $item_id;
	        $config['allowed_types'] = 'jpg|jpeg|png|fbx';
	        $config['file_name'] = 'icon_'.$item_id;
            $config['file_ext_tolower'] = true;
            $config['overwrite'] = true;

            $this->load->library('upload', $config);

            if(!file_exists(dirname(FCPATH).'/models/handles/'. $item_id)){
                mkdir(dirname(FCPATH).'/models/handles/'. $item_id);
            }

            if(isset($_FILES['icon']) && $_FILES['icon']['size'] > 0) {
                if ($this->upload->do_upload('icon'))
                {
                    $fdata = array('upload_data' => $this->upload->data());
                    $data['icon'] = 'models/handles/'. $item_id .'/' . $fdata['upload_data']['file_name'];

                } else {
                    print_r($this->upload->display_errors());
	                $this->handles_model->remove_items($item_id);
                    exit;
                }
            }

            $config['file_name'] = 'model_'.$item_id;
            $this->upload->initialize($config);
            if(isset($_FILES['model']) && $_FILES['model']['size'] > 0) {
                if ($this->upload->do_upload('model'))
                {
                    $fdata = array('upload_data' => $this->upload->data());
                    $data['model'] = 'models/handles/'. $item_id .'/' . $fdata['upload_data']['file_name'];

                } else {
                    print_r($this->upload->display_errors());
	                $this->handles_model->remove_items($item_id);
	                exit;
                }
            };

            $config['file_name'] = 'map_'.$item_id;
            $this->upload->initialize($config);
            if(isset($_FILES['map']) && $_FILES['map']['size'] > 0) {
                if ($this->upload->do_upload('map'))
                {
                    $fdata = array('upload_data' => $this->upload->data());
                    $material->params->map = 'models/handles/'. $item_id .'/' . $fdata['upload_data']['file_name'];

                } else {
                    print_r($this->upload->display_errors());
	                $this->handles_model->remove_items($item_id);
	                exit;
                }
            };

            $data['material'] = json_encode($material);

            $data['name'] = $this->input->post('name');
            $data['order'] = $this->input->post('order');
            $data['type'] = $this->input->post('type');
            $data['category'] = $this->input->post('category');
            $data['active'] = $this->input->post('active');


//            echo '<pre>';
//            print_r($data);
//            echo '</pre>';
//            echo '<br>';
//            echo json_encode($material);

//            exit;

            $this->handles_model->add_item_data($item_id, $data);


            redirect('catalog/items/handles', 'refresh');
        }
    }


    public function items_edit($id)
    {

        $this->load->helper('form');
        $this->load->library('form_validation');

        $this->form_validation->set_rules('name', 'Название', 'required');

        if ($this->form_validation->run() === FALSE)
        {
            $data['materials_categories'] = buildTree($this->handles_model->get_categories(), 'parent', 'id');
            $data['item'] = $this->handles_model->get_item($id);
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
            $this->load->view('handles/items/edit', $data);
            $this->load->view('templates/footer', $data);
        }
        else
        {

            $data = array();

            $item_id = $id;

	        $curr_item = $this->handles_model->get_item($id);

	        $cur_item_material = json_decode($curr_item['material']);

            $material = new stdClass();
            $material->params = new stdClass();
            $material->add_params = new stdClass();

            $material->type = "Standart";


            $material->params->color = $this->input->post('color');
            $material->params->transparent = $this->input->post('transparent');
            $material->params->roughness = $this->input->post('roughness');
            $material->params->metalness = $this->input->post('metalness');


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
                $var->code = $temp_var[3];
                $var->axis_size = $temp_var[4];
                $var->price = $temp_var[5];
                $var->offset_y = $temp_var[6];

                $variants[] = $var;
            }

            $data['variants'] = json_encode($variants);

            $config['upload_path'] = dirname(FCPATH).'/models/handles/'. $item_id;
	        $config['allowed_types'] = 'jpg|jpeg|png|fbx';
	        $config['file_name'] = 'icon_'.$item_id;
            $config['file_ext_tolower'] = true;
            $config['overwrite'] = true;

            if(!file_exists(dirname(FCPATH).'/models/handles/'. $item_id)){
                mkdir(dirname(FCPATH).'/models/handles/'. $item_id);
            }

            $this->load->library('upload', $config);

            if(isset($_FILES['icon']) && $_FILES['icon']['size'] > 0) {
                if ($this->upload->do_upload('icon'))
                {
                    $fdata = array('upload_data' => $this->upload->data());
                    $data['icon'] = 'models/handles/'. $item_id .'/' . $fdata['upload_data']['file_name'];

                } else {
                    print_r($this->upload->display_errors());
                }
            }


            if(isset($_FILES['model']) && $_FILES['model']['size'] > 0) {

                $config['file_name'] = 'model_'.$item_id;
                $this->upload->initialize($config);

                if ($this->upload->do_upload('model'))
                {
                    $fdata = array('upload_data' => $this->upload->data());
                    $data['model'] = 'models/handles/'. $item_id .'/' . $fdata['upload_data']['file_name'];

                } else {
                    print_r($this->upload->display_errors());
                }
            };


            if(isset($_FILES['map']) && $_FILES['map']['size'] > 0) {

                $config['file_name'] = 'map_'.$item_id;
                $this->upload->initialize($config);

                if ($this->upload->do_upload('map'))
                {
                    $fdata = array('upload_data' => $this->upload->data());
                    $material->params->map = 'models/handles/'. $item_id .'/' . $fdata['upload_data']['file_name'];

                } else {
                    print_r($this->upload->display_errors());
                }
            } else if($this->input->post('delete_map') == 1){
                unset($material->params->map);
            } else if(isset($cur_item_material->params->map)){
	            $material->params->map = $cur_item_material->params->map;
            }

            $data['material'] = json_encode($material);

            $data['type'] = $this->input->post('type');
            $data['name'] = $this->input->post('name');
            $data['order'] = $this->input->post('order');
            $data['category'] = $this->input->post('category');
            $data['active'] = $this->input->post('active');


//            echo '<pre>';
//            print_r($data);
//            echo '</pre>';
//            echo '<br>';
//            echo json_encode($material);

//            exit;

            $this->handles_model->add_item_data($item_id, $data);


            redirect('catalog/items/handles', 'refresh');



        }
    }


    public function items_remove($id)
    {
        $this->load->helper('file');
        delete_files(dirname(FCPATH).'/models/handles/'. $id, true);
        rmdir(dirname(FCPATH).'/models/handles/'. $id);
        $this->handles_model->remove_items($id);
        redirect('handles/items_index/'.$_SESSION['selected_handles_items_category'].'/'.$_SESSION['selected_handles_items_per_page'], 'refresh');
    }

    public function items_catalog_index($category = false, $per_page = false, $start = false)
    {
        $data['items'] = $this->handles_model->get_catalog_items();
        $data['categories'] = $this->handles_model->get_catalog_categories();

        $data['acc_categories'] = $this->handles_model->get_categories();

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
        $this->load->view('handles/items/catalog', $data);
        $this->load->view('templates/footer', $data);
    }

    public function add_item_from_catalog($id, $category)
    {
        $cat_item = $this->handles_model->get_catalog_item($id);

        $data = array();

        $item_id = $this->handles_model->add_item();

        $data['name'] = $cat_item['name'];
        $data['category'] = $category;
        $data['icon'] = $cat_item['icon'];
        $data['model'] = $cat_item['model'];
        $data['material'] = $cat_item['material'];
        $data['variants'] = $cat_item['variants'];
        $data['type'] = $cat_item['type'];
        $data['active'] = 1;




        echo json_encode($this->handles_model->add_item_data($item_id, $data));
    }

	public function set_active_ajax($id, $val) {
		$this->handles_model->set_active($id, $val);
	}


    public function copy_to_catalog_common($id=0, $category = 1)
    {
        $this->load->model('common_model');

        if(!$id){
            echo 'ID не указан';
            exit;
        }

        $item = $this->handles_model->get_item($id);

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
        $common_id = $this->common_model->add_data('Handles_items', array('name'=>''));


        $models_path = dirname(FCPATH).'/models/handles/'. $item_id;
        $common_assets_path = $_SERVER['DOCUMENT_ROOT'] . '/common_assets/models/handles/' . $common_id;

        if(!file_exists($common_assets_path)) mkdir($common_assets_path);

        recursiveCopy($models_path, $common_assets_path, 1);

//        print_pre($models_path);
//        print_pre($common_assets_path);

//        print_r($this->common_model->get_items_count('Facades_items', 'Facades_categories', 0));

        $item['material'] = json_decode($item['material'], true);


        $old_model_string = 'models/handles/' . $item_id;
        $new_model_string = '/common_assets/models/handles/' . $common_id;


        $item['icon'] = str_replace($old_model_string, $new_model_string, $item['icon']);
        $item['model'] = str_replace($old_model_string, $new_model_string, $item['model']);
        if(isset($item['material']['params']['map'])){
            $item['material']['params']['map'] = str_replace($old_model_string, $new_model_string, $item['material']['params']['map']);
        }





        $common_item = $item;
        $common_item['category'] = $category;
        unset($common_item['id']);

        $common_item['material'] = json_encode($common_item['material']);
        unset($common_item['order']);

        $item['material'] = json_encode($item['material']);

        unset($item['id']);

//        print_pre($common_item);


        $this->common_model->update_where('Handles_items', 'id', $common_id, $common_item);
        $this->handles_model->update_item_data($id, $item);

        array_map( 'unlink', array_filter((array) glob($models_path.'/*') ) );

        echo 'Готово';
    }

}