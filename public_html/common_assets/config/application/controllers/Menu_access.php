<?php
    class Menu_access extends CI_Controller {
        public function __construct() {
            parent::__construct();
            $this->load->helper('url_helper');
            $this->load->library('session');
            $this->load->model('User_model');
            $this->load->model("Menu_model");
            $this->load->model("Menu_access_model");
        }

        public function index() {
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

            $data['js_include'] = [
                'assets/vendor/libs/i18n/i18n.js',
                'assets/vendor/libs/@form-validation/popular.js',
                'assets/vendor/libs/@form-validation/bootstrap5.js',
                'assets/vendor/libs/@form-validation/auto-focus.js',
                'assets/vendor/libs/jstree/jstree.js',
                'admin_js/vue/menu/menu_access.js',
            ];

            $data['css_include'] = [
                'assets/vendor/libs/jstree/jstree.css'
            ];

            $data['include'] = 'menu_access/index';

            $data['users'] = $this->User_model->get_all_users();

            $this->load->view('templates/layout', $data);
        }

        public function save_menu_access() {
            $data = json_decode(file_get_contents('php://input'), true);
            $this->Menu_access_model->save_menu_access_list($data);
            return true;
        }

        public function get_menu_access() {
            $data = json_decode(file_get_contents('php://input'), true);
            $menus = $this->Menu_access_model->get_menu_access_list($data['user_id']);
            echo json_encode($menus);
        }
    }
    
?>