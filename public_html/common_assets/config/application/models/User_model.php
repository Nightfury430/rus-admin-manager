<?php 
defined('BASEPATH') OR exit('No direct script access allowed');

class User_model extends CI_Model {
    public function __construct()
    {
        $this->load->database();
    }

    public function get_user_by_id($id){
        $user = $this->db->get_row_where('Users', array('id'=> $id));
        return $user;
    }

    public function get_user_by_email($email){
        $user = $this->db->get_row_where('Users', array('email'=> $email));
    }

    public function get_all_users(){
        $users = $this->db->get('Users')->result();
        return $users;
    }

    public function insert_user($user){
        $this->db->insert('Users', $user);
        $insert_id = $this->db->insert_id();
        $this->db->where('id', $insert_id);
        $query = $this->db->get('users');
        $insert_user = $query->row();
        return $insert_user;
    }

    public function edit_user($user){
        $this->db->where('id', $user->id);
        $data = array();
        if($user->password === ''){
            $data['name'] = $user->name;
            $data['email'] = $user->email;
            $data['address'] = $user->address;
            $data['phone_number'] = $user->phone_number;
            $data['role'] = $user->role;
        } else {
            $data = $user;
        }
        $this->db->update('Users', $data);
        return $user;
    }

    public function delete_user($id){
        $this->db->where('id', $id);
        $this->db->delete('Users');
        return true;
    }
}
?>