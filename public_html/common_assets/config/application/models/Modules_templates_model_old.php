<?php
class Modules_templates_model extends CI_Model {

    public function __construct()
    {
        $this->load->database();
        $this->db2 = $this->load->database('common', TRUE);
    }


    public function get_all_items()
    {

        $query = $this->db2->get('Modules_templates');
        return $query->result_array();
    }

    public function get_items($category, $offset, $limit)
    {

        if($category == 0){
            $this->db2->order_by('order', 'ASC');
            $query = $this->db2->get('Modules_templates', $limit, $offset);
            return $query->result_array();
        } else {

            $this->db2->from('Modules_templates');
            $this->db2->where('category',$category);
            $this->db2->limit($limit, $offset);
            $this->db2->order_by('order','ASC');
            $query = $this->db2->get();
            return $query->result_array();
        }

    }

    public function get_items_by_category($category)
    {
        $this->db2->from('Modules_templates');
        $this->db2->where('category',$category);
        $this->db2->order_by('order','ASC');
        $query = $this->db2->get();
        return $query->result_array();
    }

    public function get_one($id)
    {
        $this->db2->from('Modules_templates');
        $this->db2->where('id',$id);
        $query = $this->db2->get();
        return $query->row_array();
    }


    public function add()
    {
        $this->db2->insert('Modules_templates', array('name'=>''));
        return $this->db2->insert_id();
    }

    public function update($id, $data)
    {
        $this->db2->where('id', $id);
        $this->db2->update('Modules_templates', $data);
    }

    public function remove($id)
    {
        $this->db2->delete('Modules_templates', array('id' => $id));
    }


    public function get_all_items_count()
    {
        return $this->db2->count_all('Modules_templates');
    }

    public function get_all_category_items_count($category)
    {
        if($category == 0){
            return $this->db2->count_all('Modules_templates');
        }else{
            $this->db2->from('Modules_templates');
            $this->db2->where('category',$category);
            return $this->db2->count_all_results();
        }

    }






    public function get_items_custom($category, $offset, $limit)
    {

        if($category == 0){
            $this->db->order_by('order', 'ASC');
            $query = $this->db->get('Modules_templates', $limit, $offset);
            return $query->result_array();
        } else {

            $this->db->from('Modules_templates');
            $this->db->where('category',$category);
            $this->db->limit($limit, $offset);
            $this->db->order_by('order','ASC');
            $query = $this->db->get();
            return $query->result_array();
        }

    }
    
    
    
    
    
    
    public function get_all_category_items_count_custom($category)
    {
        if($category == 0){
            return $this->db->count_all('Modules_templates');
        }else{
            $this->db->from('Modules_templates');
            $this->db->where('category',$category);
            return $this->db->count_all_results();
        }
    }

}