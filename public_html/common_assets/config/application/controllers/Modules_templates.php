<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Modules_templates extends CI_Controller {

    public function __construct()
    {
        parent::__construct();
        $this->load->helper('url_helper');
        $this->load->model('modules_templates_model');
        $this->load->library('session');



	    if(!$this->session->username || $this->session->username != $this->config->item('username')){
            redirect('login', 'refresh');
        }


        if(!$this->config->item('sub_account')) $this->config->set_item('sub_account', false);
        if($this->config->item('sub_account') == true) redirect('settings', 'refresh');
    }

    public function index($category = false, $per_page = false, $start = false)
    {



        if($per_page == null){

            $category = 0;
            $per_page = 20;

            redirect('modules_templates/index/'.$category .'/'.$per_page .'/', 'refresh');
        }

        $_SESSION['selected_modules_templates_category'] = $category;
        $_SESSION['selected_modules_templates_per_page'] = $per_page;
        $_SESSION['selected_modules_templates_pagination'] = $start;

        $this->load->library('pagination');
        $config['base_url'] = site_url('modules_templates/index/'.$category.'/'.$per_page.'/');
        $config['total_rows'] = $this->modules_templates_model->get_all_category_items_count($category);
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



        $data['items'] = $this->modules_templates_model->get_items($category,$start,$per_page);


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
        $this->load->view('modules_templates/index', $data);
        $this->load->view('templates/footer', $data);
    }

    public function add()
    {




	    $this->load->helper('form');
        $this->load->library('form_validation');


        $this->form_validation->set_rules('name', 'название', 'required');
        $this->form_validation->set_rules('category', 'категория', 'required');
        $this->form_validation->set_rules('template', 'шаблон модуля', 'required');




        if ($this->form_validation->run() === FALSE)
        {
        	$data = array();
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
            $this->load->view('modules_templates/add', $data);
            $this->load->view('templates/footer', $data);
        }
        else
        {

            $item_id = $this->modules_templates_model->add();

            $item_data = array();

//            $icon_name = '';
//
//
//            if(isset($_FILES['icon']) && $_FILES['icon']['size'] > 0) {
//
//                $config['upload_path'] = $_SERVER['DOCUMENT_ROOT'].'/common_assets/v3/images/icons/modules_templates';
//                $config['allowed_types'] = '*';
//                $config['file_name'] = $item_id;
//                $config['file_ext_tolower'] = true;
//                $config['overwrite'] = true;
//
//                $this->load->library('upload', $config);
//
//                if ($this->upload->do_upload('icon'))
//                {
//                    $data = array('upload_data' => $this->upload->data());
//                    $icon_name = '/common_assets/v3/images/icons/modules_templates/'. $data['upload_data']['file_name'];
//                }
//            }



//            if($icon_name != ''){
//                $item_data['icon'] = $icon_name;
//            }



            base64ToImage(
                $this->input->post('module_icon_input'), dirname(FCPATH) . '/images/modules_icons/templ_'. $item_id .'.png'
            );



            $item_data['name'] = $this->input->post('name');
            $item_data['icon'] = 'images/modules_icons/templ_'. $item_id .'.png';
            $item_data['category'] = $this->input->post('category');
            $item_data['params'] = $this->input->post('template');
            if($this->input->post('order')){
                $item_data['order'] = $this->input->post('order');
            } else {
                $item_data['order'] = $item_id * 100;
            }



            $this->modules_templates_model->update($item_id, $item_data);

            redirect('modules_templates/index/'.$_SESSION['selected_modules_templates_category'].'/'.$_SESSION['selected_modules_templates_per_page'].'/'.$_SESSION['selected_modules_templates_pagination'], 'refresh');
        }
    }


	public function add_new() {
		$data = array();
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
		$this->load->view('modules_templates/add_new', $data);
		$this->load->view('templates/footer', $data);
    }


    public function edit($id)
    {

        $this->load->helper('form');
        $this->load->library('form_validation');


        $this->form_validation->set_rules('name', 'название', 'required');
        $this->form_validation->set_rules('category', 'категория', 'required');
        $this->form_validation->set_rules('template', 'шаблон модуля', 'required');




        if ($this->form_validation->run() === FALSE)
        {
            $data['item'] = $this->modules_templates_model->get_one($id);

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
            $this->load->view('modules_templates/edit', $data);
            $this->load->view('templates/footer', $data);
        }
        else
        {

            $item_id = $id;

            $item_data = array();


	        if (strpos($this->input->post('module_icon_input'), 'base64') !== false) {
		        base64ToImage(
			        $this->input->post('module_icon_input'), dirname(FCPATH) . '/images/modules_icons/templ_'. $item_id .'.png'
		        );

		        $item_data['icon'] = 'images/modules_icons/templ_'. $item_id .'.png';
	        }



            $item_data['name'] = $this->input->post('name');
            $item_data['category'] = $this->input->post('category');
            $item_data['params'] = $this->input->post('template');
            if($this->input->post('order')){
                $item_data['order'] = $this->input->post('order');
            } else {
                $item_data['order'] = $item_id * 100;
            }


            $this->modules_templates_model->update($item_id, $item_data);

            redirect('modules_templates/index/'.$_SESSION['selected_modules_templates_category'].'/'.$_SESSION['selected_modules_templates_per_page'].'/'.$_SESSION['selected_modules_templates_pagination'], 'refresh');
        }
    }


    public function remove($id)
    {

        $item = $this->modules_templates_model->get_one($id);

        unlink(dirname(FCPATH). '/' . $item['icon']);

        $this->modules_templates_model->remove($id);
        redirect('modules_templates/index/'.$_SESSION['selected_modules_templates_category'].'/'.$_SESSION['selected_modules_templates_per_page'].'/'.$_SESSION['selected_modules_templates_pagination'], 'refresh');
    }





    public function get_all_ajax()
    {
        print_r(json_encode($this->modules_templates_model->get_all_items()));
    }

}