<?php
class Glass_model extends CI_Model {

    public function __construct()
    {
        $this->load->database();
        $this->db_common = $this->load->database('common', TRUE);
    }

    public function get_categories()
    {
        $this->db->order_by('order','ASC');
        $query = $this->db->get('Glass_categories');
        return $query->result_array();
    }

    public function get_category($id)
    {
        $query = $this->db->get_where('Glass_categories', array('id' => $id));
        return $query->row_array();
    }

    public function get_active_categories()
    {
        $this->db->from('Glass_categories');
        $this->db->where('active',1);
        $query = $this->db->get();
        return $query->result_array();
    }

    public function add_category($data)
    {
        return $this->db->insert('Glass_categories', $data);
    }


    //todo copy
    public function add_category_ajax($data)
    {
        $this->db->insert('Glass_categories', $data);
        return $this->db->insert_id();
    }

    //todo copy
    public function categories_order_update($data)
    {
        $this->db->update_batch('Glass_categories', $data, 'id');
    }

    //todo copy
    public function update_category_ajax($id, $data)
    {
        $this->db->where('id', $id);
        $this->db->update('Glass_categories', $data);
        return $id;
    }
    
    
    public function update_category($id, $data)
    {
        $this->db->where('id', $id);
        $this->db->update('Glass_categories', $data);

        $children = $this->get_category_children($id);

        if(count($children) > 0){
            foreach ($children as $child){
                $this->db->where('id', $child['id']);
                $this->db->update('Glass_categories', array('active' => $data('active')));
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
        $delete = $this->db->delete('Glass_categories');
        return $delete?true:false;
    }

    public function check_category_for_children($id)
    {
        $this->db->limit(1);
        $query = $this->db->get_where('Glass_categories', array('parent' => $id));
        return count($query->result_array());
    }

    public function get_category_children($id)
    {
        $query = $this->db->get_where('Glass_categories', array('parent' => $id));
        return $query->result_array();
    }

    public function check_category_name($name, $id = false)
    {
        $this->db->from('Glass_categories');
        if($id != null){
            $this->db->where('id !=', $id);
        }
        $this->db->where('name',$name);
        $query = $this->db->get();
        return $query->num_rows();
    }


	public function check_category_active($id)
	{
		$query = $this->db->get_where('Glass_categories', array('id' => $id))->row_array();
		if($query['active'] == 0){
			return false;
		} else {
			return true;
		}
	}

	public function set_category_active( $id, $val ) {
		$cat = $this->get_category($id);
		if($cat['parent']){
			$parent = $this->get_category($cat['parent']);

			if($parent['active']){
				$this->db->where('id', $id);
				$this->db->update('Glass_categories', array('active'=>$val));

				echo 'ok';

			} else {
				echo 'no';
			}
		} else {
			$this->db->where('id', $id);
			$this->db->update('Glass_categories', array('active'=>$val));
			$children = $this->get_category_children($id);
			if(count($children) > 0){
				foreach ($children as $child){
					$this->db->where('id', $child['id']);
					$this->db->update('Glass_categories', array('active' => $val));
				}
			}
			echo 'ok';
		}
	}


    public function get_items($category, $offset, $limit)
    {
        if($category == 0){
            $this->db->order_by('order','ASC');
            $query = $this->db->get('Glass_items',$limit,$offset);
            return $query->result_array();
        } else {
            $children = $this->get_category_children($category);

            $this->db->from('Glass_items');
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

    public function get_all_active_items()
    {
        $this->db->order_by('order','ASC');
        $this->db->where('active',1);
        $query = $this->db->get('Glass_items');
        return $query->result_array();
    }

    public function get_item($id)
    {
        $query = $this->db->get_where('Glass_items', array('id' => $id));
        return $query->row_array();
    }

    public function add_item($data)
    {
        $this->db->insert('Glass_items', $data);
        return $this->db->insert_id();
    }

    public function update_item($id, $data)
    {
        $this->db->where('id', $id);
        $this->db->update('Glass_items', $data);
    }

    public function remove_items($id)
    {
        if(is_array($id)){
            $this->db->where_in('id', $id);
        }else{
            $this->db->where('id', $id);
        }
        $delete = $this->db->delete('Glass_items');
        return $delete?true:false;
    }

    public function get_items_count($category)
    {
        if($category == 0){
            return $this->db->count_all('Glass_items');
        } else {
            $children = $this->get_category_children($category);
            $this->db->from("Glass_items");
            $this->db->where('category', $category);
            if(count($children) > 0){
                foreach ($children as $child){
                    $this->db->or_where('category',$child['id']);
                }
            }
            return $this->db->count_all_results();
        }
    }


    public function get_active_items_by_category($category)
    {
        $children = $this->get_category_children($category);

        $this->db->from('Glass_items');
        $this->db->where('category',$category);
        $this->db->where('active',1);

        if(count($children) > 0){
            foreach ($children as $child){
                $this->db->or_where('category',$child['id']);
                $this->db->where('active',1);
            }
        }

        $this->db->order_by('id','DESC');
        $query = $this->db->get();
        return $query->result_array();
    }


	public function set_active( $id, $val ) {
		$this->db->where('id', $id);
		$this->db->update('Glass_items', array('active'=>$val));
	}




    public function get_item_catalog($id)
    {
        $query = $this->db_common->get_where('Glass_items', array('id' => $id));
        return $query->row_array();
    }

    public function get_categories_catalog()
    {
        $query = $this->db_common->get('Glass_categories');
        return $query->result_array();
    }

    public function get_items_count_catalog($category)
    {
        if($category == 0){
            return $this->db_common->count_all('Glass_items');
        } else {
            $children = $this->get_category_children($category);
            $this->db_common->from("Glass_items");
            $this->db_common->where('category', $category);
            if(count($children) > 0){
                foreach ($children as $child){
                    $this->db_common->or_where('category',$child['id']);
                }
            }
            return $this->db_common->count_all_results();
        }
    }

    public function get_items_catalog($category, $offset, $limit)
    {
        if($category == 0){
            $this->db_common->order_by('id','DESC');
            $query = $this->db_common->get('Glass_items',$limit,$offset);
            return $query->result_array();
        } else {
            $children = $this->get_category_children($category);

            $this->db_common->from('Glass_items');
            $this->db_common->where('category',$category);


            if(count($children) > 0){
                foreach ($children as $child){
                    $this->db_common->or_where('category',$child['id']);
                }
            }

            $this->db_common->limit($limit, $offset);
            $this->db_common->order_by('id','DESC');
            $query = $this->db_common->get();
            return $query->result_array();
        }
    }

}