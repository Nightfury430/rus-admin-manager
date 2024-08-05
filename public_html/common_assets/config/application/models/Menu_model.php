<?php 
defined('BASEPATH') OR exit('No direct script access allowed');

class Menu_model extends CI_Model {
    public function __construct(){
        parent::__construct();
        $this->load->database();
    }

    public function get_menus_by_id($menu_id){

    }

    public function get_all_menus(){
        return $this->db->get('menus')->result();
    }

    public function insert_menu($menu){
        $this->db->insert('menus', $menu);
        $insert_id = $this->db->insert_id();
        $this->db->where('id', $insert_id);
        $query = $this->db->get('menus');
        $insert_menu = $query->row();
        return $insert_menu;
    }

    public function update_menu($menu_id, $data){
        $this->db->where('id', $menu_id);
        $this->db->update('menus', $data);
        return $this->db->affected_rows();
    }

    public function delete_menu($menu_id){
        $this->db->where('id', $menu_id);
        $this->db->delete('menus');
        return $this->db->affected_rows();
    }
}

?>