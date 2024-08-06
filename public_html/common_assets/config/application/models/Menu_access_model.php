<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Menu_access_model extends CI_Model {
    public function __construct(){
        parent::__construct();
        $this->load->database();
    }

    public function get_menu_access_list($user_id){
        $this->db->where('user_id',$user_id);
        return $this->db->get('Menu_access')->result();
    }

    public function save_menu_access_list($data){
        $this->db->where('user_id', $data['user_id']);
        $this->db->delete('Menu_access');
        $this->db->insert_batch('menu_access', $data['new_select_menus']);
        return true;
    }

    public function insert_access($user_id, $menus){

    }


}
?>