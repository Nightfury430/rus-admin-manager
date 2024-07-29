<?php
class Facades_model extends CI_Model {

    public function __construct()
    {
        $this->load->database();
        $this->db_common = $this->load->database('common', TRUE);
    }

    public function get_categories()
    {
        $this->db->order_by('order','ASC');
        $query = $this->db->get('Facades_categories');
        return $query->result_array();
    }

    public function get_first_active_item()
    {
        $this->db->from('Facades_items');
        $this->db->where('active',1);
        $query = $this->db->get();
        return $query->result_array()[0];
    }


    public function get_category($id)
    {
        $query = $this->db->get_where('Facades_categories', array('id' => $id));
        return $query->row_array();
    }

    public function get_active_categories()
    {
        $this->db->from('Facades_categories');
        $this->db->where('active',1);
        $query = $this->db->get();
        return $query->result_array();
    }

    public function add_category()
    {
        $data = array(
            'name' => $this->input->post('name'),
            'parent' => $this->input->post('parent'),
            'active' => $this->input->post('active'),
            'description' => $this->input->post('description')
        );

        return $this->db->insert('Facades_categories', $data);
    }

    //todo copy
    public function add_category_ajax($data)
    {
        $this->db->insert('Facades_categories', $data);
        return $this->db->insert_id();
    }

    //todo copy
    public function categories_order_update($data)
    {
        $this->db->update_batch('Facades_categories', $data, 'id');
    }

    //todo copy
    public function update_category_ajax($id, $data)
    {
        $this->db->where('id', $id);
        $this->db->update('Facades_categories', $data);
        return $id;
    }


    public function update_category($id)
    {
        $data = array(
            'name' => $this->input->post('name'),
            'parent' => $this->input->post('parent'),
            'active' => $this->input->post('active'),
            'description' => $this->input->post('description')
        );

        $this->db->where('id', $id);
        $this->db->update('Facades_categories', $data);


        $children = $this->get_category_children($id);

        if(count($children) > 0){
            foreach ($children as $child){
                $this->db->where('id', $child['id']);
                $this->db->update('Facades_categories', array('active' => $this->input->post('active')));
            }
        }


    }

    public function remove_category($id)
    {
        if(is_array($id)){
            $this->db->where_in('id', $id);
        }else{
            $this->db->where('id', $id);
        }
        $delete = $this->db->delete('Facades_categories');
        return $delete?true:false;
//        $this->db->delete('Facades_categories', array('id' => $id));
    }

    public function check_category_for_children($id)
    {
        $this->db->limit(1);
        $query = $this->db->get_where('Facades_categories', array('parent' => $id));
        return count($query->result_array());
    }

    public function get_category_children($id)
    {
        $query = $this->db->get_where('Facades_categories', array('parent' => $id));
        return $query->result_array();
    }

    public function update_category_children($id)
    {

    }

    public function check_category_name($name, $id = false)
    {
        $this->db->from('Facades_categories');
        if($id != null){
            $this->db->where('id !=', $id);
        }
        $this->db->where('name',$name);
        $query = $this->db->get();
        return $query->num_rows();
    }

	public function set_category_active( $id, $val ) {
		$cat = $this->get_category($id);
		if($cat['parent']){
			$parent = $this->get_category($cat['parent']);

			if($parent['active']){
				$this->db->where('id', $id);
				$this->db->update('Facades_categories', array('active'=>$val));

				echo 'ok';

			} else {
				echo 'no';
			}
		} else {
			$this->db->where('id', $id);
			$this->db->update('Facades_categories', array('active'=>$val));
			$children = $this->get_category_children($id);
			if(count($children) > 0){
				foreach ($children as $child){
					$this->db->where('id', $child['id']);
					$this->db->update('Facades_categories', array('active' => $val));
				}
			}
			echo 'ok';
		}
	}



	public function get_items($category, $offset, $limit)
    {
        if($category == 0){
            $this->db->order_by('order','ASC');
            $query = $this->db->get('Facades_items',$limit,$offset);
            return $query->result_array();
        } else {

            $children = $this->get_category_children($category);

            $this->db->from('Facades_items');
            $this->db->where('category',$category);

            if(count($children) > 0){
                foreach ($children as $child){
                    $this->db->or_where('category',$child['id']);
                }
            }

            $this->db->limit($limit, $offset);
            $this->db->order_by('order','ASC');
            $query = $this->db->get();
            return $query->result_array();
        }
    }

    public function get_all_items()
    {
        $this->db->order_by('order','ASC');
        $query = $this->db->get('Facades_items');
        return $query->result_array();
    }

    public function get_all_active_items()
    {
        $this->db->order_by('order','ASC');
        $this->db->where('active',1);
        $query = $this->db->get('Facades_items');
        return $query->result_array();
    }

    public function get_all_active_items_columns()
    {
        $this->db->order_by('order','ASC');
        $this->db->select('id, name, category, icon, materials');


        $query = $this->db->get('Facades_items');
        return $query->result_array();
    }


    public function get_item($id)
    {
        $query = $this->db->get_where('Facades_items', array('id' => $id));
        return $query->row_array();
    }

    public function add_item()
    {
        $data = array(
            'name' => $this->input->post('name'),
            'category' => $this->input->post('category'),
            'active' => $this->input->post('active'),
            'materials' => json_encode($this->input->post('materials'))

        );

        $this->db->insert('Facades_items', $data);
        return $this->db->insert_id();
    }

    public function add_item_id()
    {
        $this->db->insert('Facades_items', array("name"=>""));
        return $this->db->insert_id();
    }



    public function update_item($id, $data)
    {
        $this->db->where('id', $id);
        $this->db->update('Facades_items', $data);
    }


    public function remove_items($id)
    {
        if(is_array($id)){
            $this->db->where_in('id', $id);
        }else{
            $this->db->where('id', $id);
        }
        $delete = $this->db->delete('Facades_items');
        return $delete?true:false;
//        $this->db->delete('Facades_items', array('id' => $id));
    }

    public function get_items_count($category)
    {
        if($category == 0){
            return $this->db->count_all('Facades_items');
        } else {
            $children = $this->get_category_children($category);
            $this->db->from("Facades_items");
            $this->db->where('category', $category);
            if(count($children) > 0){
                foreach ($children as $child){
                    $this->db->or_where('category',$child['id']);
                }
            }
            return $this->db->count_all_results();
        }

    }


    public function get_catalog_items()
    {
        $this->db_common->order_by('id','DESC');
        $query = $this->db_common->get('Facades_items');
        return $query->result_array();
    }

    public function get_catalog_item($id)
    {
        $query = $this->db_common->get_where('Facades_items', array('id' => $id));
        return $query->row_array();
    }

    public function get_catalog_categories()
    {
//        $this->db_common->order_by('id','DESC');
        $query = $this->db_common->get('Facades_categories');
        return $query->result_array();
    }

	public function check_active( $id ) {
		return $this->get_item($id)['active'] == 1;
    }

	public function set_active( $id, $val ) {
		$this->db->where('id', $id);
		$this->db->update('Facades_items', array('active'=>$val));
	}


    public function clear_items()
    {
        $this->db->empty_table('Facades_items');
    }

    public function clear_categories()
    {
        $this->db->empty_table('Facades_categories');
    }

}