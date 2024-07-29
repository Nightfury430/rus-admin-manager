<?php
class Rendering_model extends CI_Model {

    public function __construct()
    {
        $this->load->database();
    }

    public function get_settings()
    {
        return $this->db->get('Rendering')->row_array();
    }

    public function get_amount()
    {
        return $this->db->get('Rendering')->row_array()['amount'];
    }



    public function add_amount($count)
    {
        $current = $this->get_amount();
        $result = (int)$current + (int)$count;
        $this->db->update('Rendering', array("amount"=>$result));
    }

    public function get_password()
    {
        return $this->db->get('Rendering')->row_array()['password'];
    }

    public function set_password($pass)
    {
        $this->db->update('Rendering', array("password"=>$pass));
    }

    public function get_items_count()
    {
        return $this->db->count_all('Rendering_list');
    }

    public function get_items($offset, $limit)
    {
        $this->db->from("Rendering_list");
        $this->db->limit($limit, $offset);
        $this->db->order_by('id', 'DESC');
        $query = $this->db->get();
        return $query->result_array();
    }






    public function add($data)
    {
        return $this->db->insert('Rendering_list', $data);
    }

    public function update($data)
    {
        $this->db->update('Rendering_list', $data);
    }

    public function remove($id)
    {
        $this->db->delete('Clients_orders', array('id' => $id));
    }


    public function get_all_statuses()
    {
        $query = $this->db->get('Clients_orders_statuses');
        return $query->result_array();
    }

    public function get_last_number() {
        $query =  $this->db->order_by('id',"desc")->limit(1)->get('Clients_orders')->row();
        if(isset( $query->number )){
            return $query->number;
        } else {
            return 0;
        }
    }

}