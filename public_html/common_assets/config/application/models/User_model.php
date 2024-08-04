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
        $user->password = crypt($user->password,'JL68XT@!9%OUN#_ZG:R!2PhxuOFUDKrQPtK"!b~u+um@$4^KE:@AR=8_S;lfd#U');
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
            $data['user_name'] = $user->user_name;
            $data['first_name'] = $user->first_name;
            $data['middle_name'] = $user->middle_name;
            $data['last_name'] = $user->last_name;
            $data['email'] = $user->email;
            $data['address'] = $user->address;
            $data['phone_number'] = $user->phone_number;
            $data['role'] = $user->role;
        } else {
            $user->password = crypt($user->password,'JL68XT@!9%OUN#_ZG:R!2PhxuOFUDKrQPtK"!b~u+um@$4^KE:@AR=8_S;lfd#U');
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