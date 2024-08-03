<?php
    class Menu_manage extends CI_Controller{
        function __construct(){
            parent::__construct();
            $this->load->model("Menu_model");
        }

        function index(){
            print_r('asdgasdg'); exit;
            // $this->load->view("");
        }

        function get_all_menu(){
            $menus = $this->Menu_model->get_all_menu();
            echo json_encode(array('status'=> 'success', 'menus' => $menus));
        }

        function get_menus_by_id(){
            $menu = $this->Menu_model->get_menus_by_id();
        }

        function insert_menu(){
            $menu = $this->input->post();
            $inserted_menu = $this->Menu_model->insert_menu($menu);
            echo json_encode(array('status'=> 'success', 'menu' => $inserted_menu));
        }

        function update_menu(){
            $menu = $this->input->post();
            $updated_menu = $this->Menu_model->update_menu($menu->id, $menu);
            echo json_encode(array('status'=> 'success','menu'=> $updated_menu));
        }

        function delete_menu(){
            $menu = $this->input->post();
            $deleted_menu = $this->Menu_model->delete_menu($menu->id);
            echo json_encode(array('status'=> 'success', 'menu' => $deleted_menu));
        }
    }

?>