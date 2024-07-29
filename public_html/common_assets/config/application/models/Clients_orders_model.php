<?php
class Clients_orders_model extends CI_Model {

    public function __construct()
    {
        $this->load->database();
    }

    public function get_all()
    {
        $this->db->order_by('id','DESC');
        $query = $this->db->get('Clients_orders');
        return $query->result_array();
//        return $this->db->get('Clients_orders')->result_array();
    }


    public function get_items($offset, $limit)
    {
        $this->db->from("Clients_orders");
        $this->db->limit($limit, $offset);
        $this->db->order_by('id', 'DESC');
        $query = $this->db->get();
        return $query->result_array();
    }

    public function get_items_count()
    {
        return $this->db->count_all('Clients_orders');
    }


    public function get_one($id)
    {
        $query = $this->db->get_where('Clients_orders', array('id' => $id));
        return $query->row_array();
    }

    public function add($data)
    {
        return $this->db->insert('Clients_orders', $data);
    }

    public function update($data)
    {
        $this->db->update('Clients_orders', $data);
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

    public function repair()
    {
        $this->db15 = $this->load->database('antar', TRUE);

        $this->db15->order_by('id','ASC');
        $query = $this->db15->get('Clients_orders');
        $old_ones = $query->result_array();

        $this->db->order_by('id','ASC');
        $query = $this->db->get('Clients_orders');
        $new_ones = $query->result_array();

        $this->db->empty_table('Clients_orders');

        $this->db->insert_batch('Clients_orders', $old_ones);
        $this->db->insert_batch('Clients_orders', $new_ones);

    }

}