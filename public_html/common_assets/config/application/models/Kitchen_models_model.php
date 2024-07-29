<?php
class Kitchen_models_model extends CI_Model {

    public function __construct()
    {
        $this->load->database();
    }


    public function get_all_items()
    {
        $this->db->order_by('order','ASC');
        $query = $this->db->get('Kitchen_models');
        return $query->result_array();
    }

    public function get_items($category, $offset, $limit)
    {

        if($category == 0){
            $this->db->order_by('order','ASC');
            $query = $this->db->get('Kitchen_models', $limit, $offset);
            return $query->result_array();
        } else {

            $this->db->from('Kitchen_models');
            $this->db->where('category',$category);
            $this->db->limit($limit, $offset);
            $this->db->order_by('order','ASC');
            $query = $this->db->get();
            return $query->result_array();
        }

    }

    public function get_one($id)
    {
        $this->db->from('Kitchen_models');
        $this->db->where('id',$id);
        $query = $this->db->get();
        return $query->row_array();
    }


    public function add()
    {
        $this->db->insert('Kitchen_models', array('name'=>''));
        return $this->db->insert_id();
    }

    public function update($id, $data)
    {
        $this->db->where('id', $id);
        $this->db->update('Kitchen_models', $data);
    }

    public function remove($id)
    {
        if(is_array($id)){
            $this->db->where_in('id', $id);
        }else{
            $this->db->where('id', $id);
        }
        $delete = $this->db->delete('Kitchen_models');
        return $delete?true:false;

    }


    public function get_all_items_count()
    {
        return $this->db->count_all('Kitchen_models');
    }

    public function get_all_active_items()
    {
        $this->db->from('Kitchen_models');
        $this->db->where('active',1);
        $this->db->order_by('order','ASC');
        $query = $this->db->get();
        return $query->result_array();
    }

	public function set_active( $id, $val ) {
		$this->db->where('id', $id);
		$this->db->update('Kitchen_models', array('active'=>$val));
	}

}