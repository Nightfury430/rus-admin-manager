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

        $data['js_include'] = [
            'libs/vue3/vue.global.js',
            'libs/vue3/vueuse.shared.iife.min.js',
            'libs/vue3/vueuse.core.iife.min.js',
            'libs/vue3/vue-slicksort.umd.js',
            'libs/vue3/vue-select.umd.js',
            'libs/vue3/pp-tree.js',
            'assets/vendor/libs/i18n/i18n.js',
            'assets/vendor/libs/@form-validation/popular.js',
            'assets/vendor/libs/@form-validation/bootstrap5.js',
            'assets/vendor/libs/@form-validation/auto-focus.js',
            'admin_js/vue/user/users.js',
        ];

        $data['css_include'] = [];

        $data['include'] = 'user/index';
        $this->load->view('templates/layout', $data);
    }

    public function insert_user(){
        $user = json_decode($this->input->post('data'));
        $inserted_user = $this->User_model->insert_user($user);
        echo json_encode($inserted_user);
    }

    public function delete_user(){
        $data = json_decode($this->input->post('data'));
        $this->User_model->delete_user($data->id);
        echo json_encode($data->id);
    }

    public function edit_user(){
        $user = json_decode($this->input->post('data'));
        $changed_user = $this->User_model->edit_user($user);
        echo json_encode($changed_user);
    }

    public function get_all_users(){
        $users = $this->User_model->get_all_users();
        echo json_encode($users);
    }
}