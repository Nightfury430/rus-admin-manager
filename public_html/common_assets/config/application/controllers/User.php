<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class User extends CI_Controller {
    public function __construct() {
        parent::__construct();
        $this->load->helper('url_helper');
        $this->load->library('session');
        $this->load->model('theme_model');
        $this->load->model('User_model');
    }

    public function index() {
        $data = array();
        $data['theme'] = $this->theme_model->get();
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
        $this->load->view('user/index');
        $this->load->view('templates/footer', $data);
    }

    public function insert_user(){
        $user = json_decode($this->input->post('data'));
        $user = $this->User_model->insert_user($user);
        echo json_encode($user);
    }

    public function delete_user(){

    }

    public function edit_user(){
    
    }

    public function get_all_users(){
        $users = $this->User_model->get_all_users();
        echo json_encode($users);
    }
}