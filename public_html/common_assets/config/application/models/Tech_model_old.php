<?php
class Tech_model extends CI_Model {

    public function __construct()
    {
        $this->load->database();
    }

    public function get_categories()
    {
        $query = $this->db->get('Tech_categories');
        return $query->result_array();
    }

    public function get_category($id)
    {
        $query = $this->db->get_where('Tech_categories', array('id' => $id));
        return $query->row_array();
    }

    public function get_active_categories()
    {
        $this->db->from('Tech_categories');
        $this->db->where('active',1);
        $query = $this->db->get();
        return $query->result_array();
    }

    public function add_category()
    {
        $data = array(
            'name' => $this->input->post('name'),
            'parent' => $this->input->post('parent'),
            'active' => $this->input->post('active')
        );

        return $this->db->insert('Tech_categories', $data);
    }

    public function update_category($id)
    {
        $data = array(
            'name' => $this->input->post('name'),
            'parent' => $this->input->post('parent'),
            'active' => $this->input->post('active')
        );

        $this->db->where('id', $id);
        $this->db->update('Tech_categories', $data);


        $children = $this->get_category_children($id);

        if(count($children) > 0){
            foreach ($children as $child){
                $this->db->where('id', $child['id']);
                $this->db->update('Tech_categories', array('active' => $this->input->post('active')));
            }
        }
    }

    public function remove_category($id)
    {
        $this->db->delete('Tech_categories', array('id' => $id));
    }

    public function check_category_for_children($id)
    {
        $this->db->limit(1);
        $query = $this->db->get_where('Tech_categories', array('parent' => $id));
        return count($query->result_array());
    }

    public function get_category_children($id)
    {
        $query = $this->db->get_where('Tech_categories', array('parent' => $id));
        return $query->result_array();
    }



    public function get_items($category, $offset, $limit)
    {
        if($category == 0){
            $this->db->order_by('id','DESC');
            $query = $this->db->get('Tech_items',$limit,$offset);
            return $query->result_array();
        } else {

            $children = $this->get_category_children($category);

            $this->db->from('Tech_items');
            $this->db->where('category',$category);


            if(count($children) > 0){
                foreach ($children as $child){
                    $this->db->or_where('category',$child['id']);
                }
            }

            $this->db->limit($limit, $offset);
            $this->db->order_by('id','DESC');
            $query = $this->db->get();
            return $query->result_array();
        }
    }

    public function get_all_items()
    {
        $this->db->order_by('id','DESC');
        $query = $this->db->get('Tech_items');
        return $query->result_array();
    }

    public function get_all_active_items()
    {
        $this->db->order_by('id','DESC');
        $this->db->from('Tech_items');
        $this->db->where('active',1);
        $query = $this->db->get();
        return $query->result_array();
    }


    public function get_item($id)
    {
        $query = $this->db->get_where('Tech_items', array('id' => $id));
        return $query->row_array();
    }



    public function add_item()
    {
        $this->db->insert('Tech_items', array('name'=>''));
        return $this->db->insert_id();
    }

    public function add_item_data($id, $data)
    {
        $this->db->where('id', $id);
        $this->db->update('Tech_items', $data);
    }




    public function update_item($id)
    {
        $data = array(
            'name' => $this->input->post('name'),
            'category' => $this->input->post('category'),
            'active' => $this->input->post('active'),
            'materials' => json_encode($this->input->post('materials'))

        );

        $this->db->where('id', $id);
        $this->db->update('Tech_items', $data);
    }


    public function remove_items($id)
    {
        $this->db->delete('Tech_items', array('id' => $id));
    }

    public function get_items_count($category)
    {
        if($category == 0){
            return $this->db->count_all('Tech_items');
        } else {
            $children = $this->get_category_children($category);
            $this->db->from("Tech_items");
            $this->db->where('category', $category);
            if(count($children) > 0){
                foreach ($children as $child){
                    $this->db->or_where('category',$child['id']);
                }
            }
            return $this->db->count_all_results();
        }

    }

}