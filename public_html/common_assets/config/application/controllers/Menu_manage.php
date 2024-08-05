<?php
    class Menu_manage extends CI_Controller{
        function __construct(){
            parent::__construct();
            $this->load->helper('url_helper');
            $this->load->library('session');
            $this->load->model('User_model');
            $this->load->model("Menu_model");
        }

        function index(){
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
                'admin_js/vue/menu/menu_manage.js',
            ];

            $data['css_include'] = [
                'assets/vendor/libs/jstree/jstree.css'
            ];

            $data['include'] = 'menu/menu_manage';
            $this->load->view('templates/layout', $data);
        }

        function get_all_menus(){
            $menus = $this->Menu_model->get_all_menus();
            echo json_encode(array('status'=> 'success', 'menus' => $menus));
        }

        function get_menus_by_id(){
            $menu = $this->Menu_model->get_menus_by_id();
        }

        function insert_menu(){
            $menu = $this->input->post();
            $menu['parent_id'] = $menu['node_id'];
            unset($menu['node_id']);
            $inserted_menu = $this->Menu_model->insert_menu($menu);
            echo json_encode(array('status'=> 'success', 'menu' => $inserted_menu));
        }

        function update_menu(){
            $menu = $this->input->post();
            $node_id = $menu['node_id'];
            unset($menu['node_id']);
            $updated_menu = $this->Menu_model->update_menu($node_id, $menu);
            echo json_encode(array('status'=> 'success','menu'=> $updated_menu[0]));
        }

        function delete_menu(){
            $id = $this->input->post();
            $this->Menu_model->delete_menu($id['id']);
            echo json_encode(array('status'=> 'success', 'id' => $id['id']));
        }
    }

?>