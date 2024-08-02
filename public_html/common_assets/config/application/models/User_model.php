<?php 
defined('BASEPATH') OR exit('No direct script access allowed');

class User_model extends CI_Model {
    public function __construct()
    {
        $this->load->database();
    }

    public function get_user_by_id($id){
        $user = $this->db->get_row_where('User', array('id'=> $id));
        return $user;
    }

    public function get_user_by_email($email){
        $user = $this->db->get_row_where('User', array('email'=> $email));
    }

    public function get_all_users(){
        $users = $this->db->get('User');
    }

    public function insert_user($user){
        $this->db->insert('User', $user);
        return true;
    }

    public function update_user($user){
        $this->db->where('id', $user->id);
        $this->db->update('User', $user);
        return true;
    }

    public function delete_user($user){
        $this->db->where('id', $user->id);
        $this->db->delete('User');
        return true;
    }
}

?>