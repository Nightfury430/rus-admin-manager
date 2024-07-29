<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Furniture extends CI_Controller {

    public function __construct()
    {
        parent::__construct();
        $this->load->helper('url_helper');
        $this->load->model('furniture_model');
        $this->load->library('session');

        if(!$this->session->username || $this->session->username != $this->config->item('username')){
            redirect('login', 'refresh');
        }

        if(!$this->config->item('sub_account')) $this->config->set_item('sub_account', false);
        if($this->config->item('sub_account') == true) redirect('settings', 'refresh');
    }



    public function categories_index()
    {

        $data['items'] = $this->furniture_model->get_categories();


        $this->load->view('templates/header');
        $this->load->view('furniture/categories/index', $data);
        $this->load->view('templates/footer');
    }

    public function categories_add()
    {

        if(!$this->input->post()){
            $this->load->view('templates/header');
            $this->load->view('furniture/categories/add');
            $this->load->view('templates/footer');
        } else {
            $data = array();
            $data['name'] = no_xss($this->input->post('name'));

            $this->furniture_model->add_category($data);

            redirect('furniture/categories_index/', 'refresh');
        }


    }

    public function categories_edit()
    {

    }

    public function categories_remove()
    {

    }


    public function items_index()
    {
        $data['items'] = $this->furniture_model->get_items_temp();
        $data['categories'] = $this->furniture_model->get_categories();

        $this->load->view('templates/header');
        $this->load->view('furniture/items/index', $data);
        $this->load->view('templates/footer');
    }

    public function items_add()
    {
        if(!$this->input->post()) {
            $data['categories'] = $this->furniture_model->get_categories();

            $this->load->view('templates/header');
            $this->load->view('furniture/items/add', $data);
            $this->load->view('templates/footer');
        } else{
            $data = array();
            $data['name'] = no_xss($this->input->post('name'));
            $data['code'] = no_xss($this->input->post('code'));
            $data['price'] = no_xss($this->input->post('price'));
            $data['category'] = no_xss($this->input->post('category'));

            $this->furniture_model->add_item($data);

            redirect('furniture/items_index/', 'refresh');
        }
    }

    public function items_remove($id)
    {
        $this->furniture_model->remove_items($id);
        redirect('furniture/items_index/', 'refresh');
    }


    public function sets_index()
    {
        if(!$this->input->post()) {

            $data['items'] = $this->furniture_model->get_sets();

            $this->load->view('templates/header');
            $this->load->view('furniture/sets/index', $data);
            $this->load->view('templates/footer');

        } else {

        }

    }

    public function sets_add()
    {
        if(!$this->input->post()) {

            $data['items'] = $this->furniture_model->get_items_with_categories();
            $data['categories'] = $this->furniture_model->get_categories();

            $this->load->view('templates/header');
            $this->load->view('furniture/sets/add', $data);
            $this->load->view('templates/footer');

        } else {

            $data = array();
            $data['name'] = no_xss($this->input->post('name'));
            $data['data'] = $this->input->post('data');

            $this->furniture_model->add_set($data);

            echo json_encode(['success' => 'true']);

        }
    }

    public function sets_edit()
    {

    }

    public function sets_remove()
    {

    }


    public function categories_index_old()
    {
        $data['categories'] = buildTree($this->furniture_model->get_categories(), 'parent', 'id');

        $this->load->view('templates/header');
        $this->load->view('furniture/categories/index', $data);
        $this->load->view('templates/footer');
    }

    public function categories_add_old()
    {
        $this->load->helper('form');
        $this->load->library('form_validation');


        $this->form_validation->set_rules('name', 'Название', 'required');

        if ($this->form_validation->run() === FALSE)
        {
            $data['categories'] = $this->furniture_model->get_categories();

            $this->load->view('templates/header');
            $this->load->view('furniture/categories/add', $data);
            $this->load->view('templates/footer');
        }
        else
        {
            $this->furniture_model->add_category();
            redirect('furniture/categories_index/', 'refresh');
        }
    }

    public function categories_edit_old($id)
    {

        $this->load->helper('form');
        $this->load->library('form_validation');

        $this->form_validation->set_rules('name', 'Название', 'required');

        if ($this->form_validation->run() === FALSE)
        {
            $data['category'] = $this->furniture_model->get_category($id);
            $data['has_children'] = $this->furniture_model->check_category_for_children($id);

            if($data['has_children'] == 0){
                $data['categories'] = $this->furniture_model->get_categories();
            }

            $this->load->view('templates/header');
            $this->load->view('furniture/categories/edit', $data);
            $this->load->view('templates/footer');
        }
        else
        {
            $this->furniture_model->update_category($id);
            redirect('furniture/categories_index/', 'refresh');
        }
    }

    public function categories_remove_old($id)
    {
        if($this->furniture_model->check_category_for_children($id) == 0){
            $this->furniture_model->remove_category($id);
        }

        redirect('furniture/categories_index/', 'refresh');
    }


    public function items_index_old($category = false, $per_page = false, $start = false)
    {

        if($category == null || $per_page == null){

            $category = 0;
            $per_page = 20;

            redirect('furniture/items_index/'.$category .'/'.$per_page.'/', 'refresh');
        }

        $_SESSION['selected_furniture_items_category'] = $category;
        $_SESSION['selected_furniture_items_per_page'] = $per_page;
        $_SESSION['selected_furniture_items_pagination'] = $start;


        $this->load->library('pagination');
        $config['base_url'] = site_url('furniture/items_index/'.$category .'/'.$per_page.'/');
        $config['total_rows'] = $this->furniture_model->get_items_count($category);
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


        $data['items'] = $this->furniture_model->get_items($category, $start, $config['per_page']);
        $data['categories'] = $this->furniture_model->get_categories();
        $data['materials_categories'] = buildTree($this->furniture_model->get_categories(), 'parent', 'id');

        $this->pagination->initialize($config);

        $data['pagination'] =  $this->pagination->create_links();


        $this->load->view('templates/header');
        $this->load->view('furniture/items/index', $data);
        $this->load->view('templates/footer');
    }

    public function items_add_old()
    {

        $this->load->helper('form');
        $this->load->library('form_validation');


        $this->form_validation->set_rules('name', 'Название', 'required');


        if ($this->form_validation->run() === FALSE)
        {
            $data['materials_categories'] = buildTree($this->furniture_model->get_categories(), 'parent', 'id');

            $this->load->view('templates/header');
            $this->load->view('furniture/items/add', $data);
            $this->load->view('templates/footer');
        }
        else
        {
            $data = array();

            $item_id = $this->furniture_model->add_item();

            mkdir(dirname(FCPATH).'/models/furniture/'.$item_id);

            $material = new stdClass();
            $material->params = new stdClass();
            $material->add_params = new stdClass();

            $material->type = "Standart";


            $material->params->color = $this->input->post('color');
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

                $variants[] = $var;
            }

            $data['variants'] = json_encode($variants);

            $config['upload_path'] = dirname(FCPATH).'/models/furniture/'. $item_id;
	        $config['allowed_types'] = 'jpg|jpeg|png|fbx';
            $config['file_name'] = 'icon_'.$item_id;
            $config['file_ext_tolower'] = true;
            $config['overwrite'] = true;

            $this->load->library('upload', $config);

            if(isset($_FILES['icon']) && $_FILES['icon']['size'] > 0) {
                if ($this->upload->do_upload('icon'))
                {
                    $fdata = array('upload_data' => $this->upload->data());
                    $data['icon'] = 'models/furniture/'. $item_id .'/' . $fdata['upload_data']['file_name'];

                } else {
	                print_r($this->upload->display_errors());
	                $this->furniture_model->remove_items($item_id);
	                exit;
                }
            }

            $config['file_name'] = 'model_'.$item_id;
            $this->upload->initialize($config);
            if(isset($_FILES['model']) && $_FILES['model']['size'] > 0) {
                if ($this->upload->do_upload('model'))
                {
                    $fdata = array('upload_data' => $this->upload->data());
                    $data['model'] = 'models/furniture/'. $item_id .'/' . $fdata['upload_data']['file_name'];

                } else {
	                print_r($this->upload->display_errors());
	                $this->furniture_model->remove_items($item_id);
	                exit;
                }
            };

            $config['file_name'] = 'map_'.$item_id;
            $this->upload->initialize($config);
            if(isset($_FILES['map']) && $_FILES['map']['size'] > 0) {
                if ($this->upload->do_upload('map'))
                {
                    $fdata = array('upload_data' => $this->upload->data());
                    $material->params->map = 'models/furniture/'. $item_id .'/' . $fdata['upload_data']['file_name'];

                } else {
	                print_r($this->upload->display_errors());
	                $this->furniture_model->remove_items($item_id);
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


//            echo '<pre>';
//            print_r($data);
//            echo '</pre>';
//            echo '<br>';
//            echo json_encode($material);

//            exit;

            $this->furniture_model->add_item_data($item_id, $data);


            redirect('furniture/items_index/'.$_SESSION['selected_furniture_items_category'].'/'.$_SESSION['selected_furniture_items_per_page'], 'refresh');
        }
    }


    public function items_edit_old($id)
    {

        $this->load->helper('form');
        $this->load->library('form_validation');

        $this->form_validation->set_rules('name', 'Название', 'required');

        if ($this->form_validation->run() === FALSE)
        {
            $data['materials_categories'] = buildTree($this->furniture_model->get_categories(), 'parent', 'id');
            $data['item'] = $this->furniture_model->get_item($id);

            $this->load->view('templates/header');
            $this->load->view('furniture/items/edit', $data);
            $this->load->view('templates/footer');
        }
        else
        {

            $data = array();

            $item_id = $id;


            $material = new stdClass();
            $material->params = new stdClass();
            $material->add_params = new stdClass();

            $material->type = "Standart";


            $material->params->color = $this->input->post('color');
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

                $variants[] = $var;
            }

            $data['variants'] = json_encode($variants);

            $config['upload_path'] = dirname(FCPATH).'/models/furniture/'. $item_id;
	        $config['allowed_types'] = 'jpg|jpeg|png|fbx';
	        $config['file_name'] = 'icon_'.$item_id;
            $config['file_ext_tolower'] = true;
            $config['overwrite'] = true;

            $this->load->library('upload', $config);

            if(isset($_FILES['icon']) && $_FILES['icon']['size'] > 0) {
                if ($this->upload->do_upload('icon'))
                {
                    $fdata = array('upload_data' => $this->upload->data());
                    $data['icon'] = 'models/furniture/'. $item_id .'/' . $fdata['upload_data']['file_name'];

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
                    $data['model'] = 'models/furniture/'. $item_id .'/' . $fdata['upload_data']['file_name'];

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
                    $material->params->map = 'models/furniture/'. $item_id .'/' . $fdata['upload_data']['file_name'];

                } else {
                    print_r($this->upload->display_errors());
	                exit;
                }
            } else if($this->input->post('delete_map') == 1){
                unset($material->params->map);
            }

            $data['material'] = json_encode($material);

            $data['name'] = $this->input->post('name');
            $data['category'] = $this->input->post('category');
            $data['active'] = $this->input->post('active');
            $data['group'] = $this->input->post('group');
            $data['code'] = $this->input->post('code');
            $data['wall_panel'] = $this->input->post('wall_panel');


//            echo '<pre>';
//            print_r($data);
//            echo '</pre>';
//            echo '<br>';
//            echo json_encode($material);

//            exit;

            $this->furniture_model->add_item_data($item_id, $data);


            redirect('furniture/items_index/'.$_SESSION['selected_furniture_items_category'].'/'.$_SESSION['selected_furniture_items_per_page'], 'refresh');



        }
    }


    public function items_remove_old($id)
    {
        $this->load->helper('file');
        delete_files(dirname(FCPATH).'/models/furniture/'. $id, true);
        rmdir(dirname(FCPATH).'/models/furniture/'. $id);
        $this->furniture_model->remove_items($id);
        redirect('furniture/items_index/'.$_SESSION['selected_furniture_items_category'].'/'.$_SESSION['selected_furniture_items_per_page'], 'refresh');
    }

}

