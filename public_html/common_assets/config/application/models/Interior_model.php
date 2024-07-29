<?php
class Interior_model extends CI_Model {

    public function __construct()
    {
        $this->load->database();
        $this->db_common = $this->load->database('common', TRUE);
    }

    public function get_categories()
    {
        $this->db->order_by('order','ASC');
        $query = $this->db->get('Interior_categories');
        return $query->result_array();
    }

    public function get_category($id)
    {
        $query = $this->db->get_where('Interior_categories', array('id' => $id));
        return $query->row_array();
    }

    public function get_active_categories()
    {
        $this->db->from('Interior_categories');
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

        return $this->db->insert('Interior_categories', $data);
    }

    //todo copy
    public function add_category_ajax($data)
    {
        $this->db->insert('Interior_categories', $data);
        return $this->db->insert_id();
    }

    //todo copy
    public function categories_order_update($data)
    {
        $this->db->update_batch('Interior_categories', $data, 'id');
    }

    //todo copy
    public function update_category_ajax($id, $data)
    {
        $this->db->where('id', $id);
        $this->db->update('Interior_categories', $data);
        return $id;
    }

    public function update_category($id)
    {
        $data = array(
            'name' => $this->input->post('name'),
            'parent' => $this->input->post('parent'),
            'active' => $this->input->post('active')
        );

        $this->db->where('id', $id);
        $this->db->update('Interior_categories', $data);


        $children = $this->get_category_children($id);

        if(count($children) > 0){
            foreach ($children as $child){
                $this->db->where('id', $child['id']);
                $this->db->update('Interior_categories', array('active' => $this->input->post('active')));
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
        $delete = $this->db->delete('Interior_categories');
        return $delete?true:false;
    }

    public function check_category_for_children($id)
    {
        $this->db->limit(1);
        $query = $this->db->get_where('Interior_categories', array('parent' => $id));
        return count($query->result_array());
    }

    public function get_category_children($id)
    {
        $query = $this->db->get_where('Interior_categories', array('parent' => $id));
        return $query->result_array();
    }

	public function set_category_active( $id, $val ) {
		$cat = $this->get_category($id);
		if($cat['parent']){
			$parent = $this->get_category($cat['parent']);

			if($parent['active']){
				$this->db->where('id', $id);
				$this->db->update('Interior_categories', array('active'=>$val));

				echo 'ok';

			} else {
				echo 'no';
			}
		} else {
			$this->db->where('id', $id);
			$this->db->update('Interior_categories', array('active'=>$val));
			$children = $this->get_category_children($id);
			if(count($children) > 0){
				foreach ($children as $child){
					$this->db->where('id', $child['id']);
					$this->db->update('Interior_categories', array('active' => $val));
				}
			}
			echo 'ok';
		}
	}


    public function get_items($category, $offset, $limit)
    {
        if($category == 0){
	        $this->db->order_by('order','ASC');
	        $query = $this->db->get('Interior_items',$limit,$offset);
            return $query->result_array();
        } else {

            $children = $this->get_category_children($category);

            $this->db->from('Interior_items');
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
        $query = $this->db->get('Interior_items');
        return $query->result_array();
    }

    public function get_all_active_items()
    {
	    $this->db->order_by('order','ASC');
        $this->db->from('Interior_items');
        $this->db->where('active',1);
        $query = $this->db->get();
        return $query->result_array();
    }

    public function get_item($id)
    {
        $query = $this->db->get_where('Interior_items', array('id' => $id));
        return $query->row_array();
    }



    public function add_item()
    {
        $this->db->insert('Interior_items', array('name'=>''));
        return $this->db->insert_id();
    }


    public function add_item_data($id, $data)
    {
        $this->db->where('id', $id);
        $this->db->update('Interior_items', $data);
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
        $this->db->update('Interior_items', $data);
    }


    public function remove_items($id)
    {
        if(is_array($id)){
            $this->db->where_in('id', $id);
        }else{
            $this->db->where('id', $id);
        }
        $delete = $this->db->delete('Interior_items');
        return $delete?true:false;
    }

    public function get_items_count($category)
    {
        if($category == 0){
            return $this->db->count_all('Interior_items');
        } else {
            $children = $this->get_category_children($category);
            $this->db->from("Interior_items");
            $this->db->where('category', $category);
            if(count($children) > 0){
                foreach ($children as $child){
                    $this->db->or_where('category',$child['id']);
                }
            }
            return $this->db->count_all_results();
        }

    }

	public function set_active( $id, $val ) {
		$this->db->where('id', $id);
		$this->db->update('Interior_items', array('active'=>$val));
	}


    public function get_catalog_items()
    {
        $this->db_common->order_by('id','DESC');
        $query = $this->db_common->get('Interior_items');
        return $query->result_array();
    }

    public function get_catalog_item($id)
    {
        $query = $this->db_common->get_where('Interior_items', array('id' => $id));
        return $query->row_array();
    }

    public function get_catalog_categories()
    {
//        $this->db_common->order_by('id','DESC');
        $query = $this->db_common->get('Interior_categories');
        return $query->result_array();
    }

}