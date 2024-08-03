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
        $data = array(
            'name' => $user->name,
            // 'gender' => $user->gender,
            'address' => $user->address,
            'phone_number' => $user->phoneNumber,
            'role' => $user->role,
            'email' => $user->email,
            'password' => $user->password
        );
        $this->db->insert('Users', $data);
        $insert_id = $this->db->insert_id();

        // Fetch the newly inserted data
        $this->db->where('id', $insert_id);
        $query = $this->db->get('users');
        $insert_user = $query->row();
        return $insert_user;
    }

    public function update_user($user){
        $this->db->where('id', $user->id);
        $this->db->update('Users', $user);
        return true;
    }

    public function delete_user($user){
        $this->db->where('id', $user->id);
        $this->db->delete('Users');
        return true;
    }
}

?>