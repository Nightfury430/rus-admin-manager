<?php
class Materials_model extends CI_Model {

    public function __construct()
    {
        $this->load->database();

        $this->db_common = $this->load->database('common', TRUE);
    }

    public function get_categories()
    {
        $this->db->order_by('order','ASC');
        $query = $this->db->get('Materials_categories');
        return $query->result_array();
    }

    public function get_categories_common()
    {
        $this->db_common->order_by('order','ASC');
        $query = $this->db_common->get('Materials_categories');
        return $query->result_array();
    }

    public function get_active_categories()
    {
        $this->db->from('Materials_categories');
        $this->db->where('active',1);
        $query = $this->db->get();
        return $query->result_array();
    }

    public function get_category($id)
    {
        $query = $this->db->get_where('Materials_categories', array('id' => $id));
        return $query->row_array();
    }

    public function check_category_active($id)
    {
        $query = $this->db->get_where('Materials_categories', array('id' => $id))->row_array();
        if(empty($query)) return false;
        if($query['active'] == 0){
            return false;
        } else {
            return true;
        }
    }

    public function add_category()
    {
        $data = array(
            'name' => $this->input->post('name'),
            'parent' => $this->input->post('parent'),
            'active' => $this->input->post('active')
        );

        return $this->db->insert('Materials_categories', $data);
    }

    public function add_category_data($data)
    {
        $this->db->insert('Materials_categories', $data);
        return $this->db->insert_id();
    }


    //todo copy
    public function add_category_ajax($data)
    {
        $this->db->insert('Materials_categories', $data);
        return $this->db->insert_id();
    }

    //todo copy
    public function categories_order_update($data)
    {
        $this->db->update_batch('Materials_categories', $data, 'id');
    }

    //todo copy
    public function update_category_ajax($id, $data)
    {
        $this->db->where('id', $id);
        $this->db->update('Materials_categories', $data);
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
        $this->db->update('Materials_categories', $data);


        $children = $this->get_category_children($id);

        if(count($children) > 0){
            foreach ($children as $child){
                $this->db->where('id', $child['id']);
                $this->db->update('Materials_categories', array('active' => $this->input->post('active')));
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
        $delete = $this->db->delete('Materials_categories');
        return $delete?true:false;

//        $this->db->delete('Materials_categories', array('id' => $id));
    }

    public function check_category_for_children($id)
    {
        $this->db->limit(1);
        $query = $this->db->get_where('Materials_categories', array('parent' => $id));
        return count($query->result_array());
    }

    public function get_category_children($id)
    {
        $query = $this->db->get_where('Materials_categories', array('parent' => $id));
        return $query->result_array();
    }

    public function get_category_children_common($id)
    {
        $query = $this->db_common->get_where('Materials_categories', array('parent' => $id));
        return $query->result_array();
    }


    public function update_category_children($id)
    {

    }

    public function check_category_name($name, $id = false)
    {
        $this->db->from('Materials_categories');
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
				$this->db->update('Materials_categories', array('active'=>$val));

				echo 'ok';

			} else {
				echo 'no';
			}
		} else {
			$this->db->where('id', $id);
			$this->db->update('Materials_categories', array('active'=>$val));
			$children = $this->get_category_children($id);
			if(count($children) > 0){
				foreach ($children as $child){
					$this->db->where('id', $child['id']);
					$this->db->update('Materials_categories', array('active' => $val));
				}
			}
			echo 'ok';
		}
	}


    public function get_items($category, $offset, $limit)
    {
        if($category == 0){
            $this->db->order_by('order','ASC');
            $query = $this->db->get('Materials_items',$limit,$offset);
            return $query->result_array();
        } else {

            $children = $this->get_category_children($category);

            $this->db->from('Materials_items');
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

    public function get_items_common($category, $offset, $limit)
    {
        if($category == 0){
            $this->db_common->order_by('order','ASC');
            $query = $this->db_common->get('Materials_items',$limit,$offset);
            return $query->result_array();
        } else {

            $children = $this->get_category_children_common($category);

            $this->db_common->from('Materials_items');
            $this->db_common->where('category',$category);


            if(count($children) > 0){
                foreach ($children as $child){
                    $this->db_common->or_where('category',$child['id']);
                }
            }

            $this->db_common->limit($limit, $offset);
            $this->db_common->order_by('order','ASC');
            $query = $this->db_common->get();
            return $query->result_array();
        }
    }


    public function get_items_by_category($category)
    {
        $children = $this->get_category_children($category);

        $this->db->from('Materials_items');
        $this->db->where('category',$category);
//        $this->db->like('category', '"' . $category . '"');

        if(count($children) > 0){
            foreach ($children as $child){
                $this->db->or_where('category',$child['id']);
//                $this->db->or_like('category','"'.$child['id'].'"');
            }
        }

        $this->db->order_by('order','ASC');
        $query = $this->db->get();
        return $query->result_array();
    }

    public function get_items_by_category_common($category)
    {
        $children = $this->get_category_children_common($category);

        $this->db_common->from('Materials_items');
        $this->db_common->where('category',$category);
//        $this->db->like('category', '"' . $category . '"');

        if(count($children) > 0){
            foreach ($children as $child){
                $this->db_common->or_where('category',$child['id']);
//                $this->db->or_like('category','"'.$child['id'].'"');
            }
        }

        $this->db_common->order_by('order','ASC');
        $query = $this->db_common->get();
        return $query->result_array();
    }

    public function get_active_items_by_category($category)
    {
        $children = $this->get_category_children($category);

        $this->db->from('Materials_items');
        $this->db->where('category',$category);
        $this->db->where('active',1);

        if(count($children) > 0){
            foreach ($children as $child){
                $this->db->or_where('category',$child['id']);
                $this->db->where('active',1);
            }
        }

        $this->db->order_by('order','ASC');
        $query = $this->db->get();
        return $query->result_array();
    }

    public function get_all_items()
    {
        $this->db->order_by('order','ASC');
        $query = $this->db->get('Materials_items');
        return $query->result_array();
    }

    public function get_item($id)
    {
        $query = $this->db->get_where('Materials_items', array('id' => $id));
        return $query->row_array();
    }

    public function get_item_common($id)
    {
        $query = $this->db_common->get_where('Materials_items', array('id' => $id));
        return $query->row_array();
    }

    public function get_item_by_code($code)
    {
        $query = $this->db->get_where('Materials_items', array('code' => $code));
        return $query->result();
    }

    public function get_items_by_codes($codes)
    {
        $query = $this->db->select('*')->from('Materials_items')->where_in('code',$codes)->get();
        return $query->result();
    }

    public function get_all_active_items()
    {
        $this->db->order_by('order','ASC');
        $this->db->where('active',1);
        $query = $this->db->get('Materials_items');
        return $query->result_array();
    }


    public function check_mat_active($id)
    {
        if(!$id)  return false;
        $mat = $this->get_item($id);
        if(!$mat) return false;
        if( $mat['active'] == 0 ){
            return false;
        } else {
            $cat = $this->get_category($mat['category']);
            if(!$cat) return false;
            if( $cat['active'] == 0 ){
                return false;
            } else {
                return true;
            }
        }

    }


    public function add_item()
    {
        $data = array(
            'name' => $this->input->post('name'),
            'category' => $this->input->post('category'),
            'code' => $this->input->post('code'),
            'active' => $this->input->post('active'),
            'color' => $this->input->post('color'),
            'roughness' => $this->input->post('roughness'),
            'metalness' => $this->input->post('metalness'),
            'real_width' => $this->input->post('real_width'),
            'real_height' => $this->input->post('real_height'),
            'stretch_width' => $this->input->post('stretch_width'),
            'stretch_height' => $this->input->post('stretch_height'),
            'wrapping' => $this->input->post('wrapping'),
            'transparent' => $this->input->post('transparent'),
            'order' => $this->input->post('order'),
        );

        $this->db->insert('Materials_items', $data);
        return $this->db->insert_id();
    }

    public function create_item()
    {
        $this->db->insert('Materials_items', array('name'=>''));
        return $this->db->insert_id();
    }

    public function add_item_ajax($data)
    {
        $this->db->insert('Materials_items', $data);
        return $this->db->insert_id();
    }

    public function add_item_common()
    {
        $data = array(
            'name' => $this->input->post('name'),
            'category' => $this->input->post('category'),
            'code' => $this->input->post('code'),
            'active' => $this->input->post('active'),
            'color' => $this->input->post('color'),
            'roughness' => $this->input->post('roughness'),
            'metalness' => $this->input->post('metalness'),
            'real_width' => $this->input->post('real_width'),
            'real_height' => $this->input->post('real_height'),
            'stretch_width' => $this->input->post('stretch_width'),
            'stretch_height' => $this->input->post('stretch_height'),
            'wrapping' => $this->input->post('wrapping'),
            'transparent' => $this->input->post('transparent'),
            'order' => $this->input->post('order'),
        );

        $this->db_common->insert('Materials_items', $data);
        return $this->db_common->insert_id();
    }

    public function add_item_common_data($data)
    {
        $this->db_common->insert('Materials_items', $data);
        return $this->db_common->insert_id();
    }


    public function add_item_id_only()
    {
        $this->db->insert('Materials_items', array('name'=>''));
        return $this->db->insert_id();
    }

    public function add_item_id()
    {
        $this->db->insert('Coupe_materials_items', array('name'=>''));
        return $this->db->insert_id();
    }

    public function update_item($id)
    {
        $data = array(
            'name' => $this->input->post('name'),
            'category' => $this->input->post('category'),
            'code' => $this->input->post('code'),
            'active' => $this->input->post('active'),
            'color' => $this->input->post('color'),
            'roughness' => $this->input->post('roughness'),
            'metalness' => $this->input->post('metalness'),
            'real_width' => $this->input->post('real_width'),
            'real_height' => $this->input->post('real_height'),
            'stretch_width' => $this->input->post('stretch_width'),
            'stretch_height' => $this->input->post('stretch_height'),
            'wrapping' => $this->input->post('wrapping'),
            'transparent' => $this->input->post('transparent'),
            'order' => $this->input->post('order'),
        );

        $this->db->where('id', $id);
        $this->db->update('Materials_items', $data);

    }



    public function update_item_common($id)
    {
        $data = array(
            'name' => $this->input->post('name'),
            'category' => $this->input->post('category'),
            'code' => $this->input->post('code'),
            'active' => $this->input->post('active'),
            'color' => $this->input->post('color'),
            'roughness' => $this->input->post('roughness'),
            'metalness' => $this->input->post('metalness'),
            'real_width' => $this->input->post('real_width'),
            'real_height' => $this->input->post('real_height'),
            'stretch_width' => $this->input->post('stretch_width'),
            'stretch_height' => $this->input->post('stretch_height'),
            'wrapping' => $this->input->post('wrapping'),
            'transparent' => $this->input->post('transparent'),
            'order' => $this->input->post('order'),
        );

        $this->db_common->where('id', $id);
        $this->db_common->update('Materials_items', $data);

    }

    public function update_item_data($id, $data)
    {
        $this->db->where('id', $id);
        $this->db->update('Materials_items', $data);
    }


    public function add_item_with_data($data)
    {
        $this->db->insert('Materials_items', $data);
        return $this->db->insert_id();
    }

    public function add_item_with_data_common($data)
    {
        $this->db_common->insert('Materials_items', $data);
        return $this->db_common->insert_id();
    }

    public function add_item_image($id, $path)
    {
        $this->db->where('id', $id);
        $this->db->update('Materials_items', array('map' => $path));
    }

    public function add_item_image_common($id, $path)
    {
        $this->db_common->where('id', $id);
        $this->db_common->update('Materials_items', array('map' => $path));
    }

    public function remove_item_image($id)
    {
        $this->db->where('id', $id);
        $this->db->update('Materials_items', array('map' => null));
    }

    public function remove_item_image_common($id)
    {
        $this->db_common->where('id', $id);
        $this->db_common->update('Materials_items', array('map' => null));
    }

    public function remove_items($id)
    {
        if(is_array($id)){
            $this->db->where_in('id', $id);
        }else{
            $this->db->where('id', $id);
        }
        $delete = $this->db->delete('Materials_items');
        return $delete?true:false;

//        $this->db->delete('Materials_items', array('id' => $id));
    }

    public function remove_items_common($id)
    {
        if(is_array($id)){
            $this->db_common->where_in('id', $id);
        }else{
            $this->db_common->where('id', $id);
        }
        $delete = $this->db_common->delete('Materials_items');
        return $delete?true:false;

//        $this->db->delete('Materials_items', array('id' => $id));
    }

    public function get_items_count($category)
    {
        if($category == 0){
            return $this->db->count_all('Materials_items');
        } else {
            $children = $this->get_category_children($category);
            $this->db->from("Materials_items");
            $this->db->where('category', $category);
            if(count($children) > 0){
                foreach ($children as $child){
                    $this->db->or_where('category',$child['id']);
                }
            }
            return $this->db->count_all_results();
        }

    }

    public function get_items_count_common($category)
    {
        if($category == 0){
            return $this->db_common->count_all('Materials_items');
        } else {
            $children = $this->get_category_children_common($category);
            $this->db_common->from("Materials_items");
            $this->db_common->where('category', $category);
            if(count($children) > 0){
                foreach ($children as $child){
                    $this->db_common->or_where('category',$child['id']);
                }
            }
            return $this->db_common->count_all_results();
        }

    }


	public function set_active( $id, $val ) {
		$this->db->where('id', $id);
		$this->db->update('Materials_items', array('active'=>$val));
	}


    public function clear_items()
    {
        $this->db->empty_table('Materials_items');
	}

    public function clear_categories()
    {
        $this->db->empty_table('Materials_categories');
	}


	public function coupe_get_categories()
	{
		$query = $this->db->get('Coupe_materials_categories');
		return $query->result_array();
	}

	public function coupe_get_category($id)
	{
		$query = $this->db->get_where('Coupe_materials_categories', array('id' => $id));
		return $query->row_array();
	}

	public function coupe_get_active_categories()
	{
		$this->db->from('Coupe_materials_categories');
		$this->db->where('active',1);
		$query = $this->db->get();
		return $query->result_array();
	}

	public function coupe_add_category($data)
	{
		return $this->db->insert('Coupe_materials_categories', $data);
	}

	public function coupe_update_category($id, $data)
	{
		$this->db->where('id', $id);
		$this->db->update('Coupe_materials_categories', $data);

		$children = $this->coupe_get_category_children($id);

		if(count($children) > 0){
			foreach ($children as $child){
				$this->db->where('id', $child['id']);
				$this->db->update('Coupe_materials_categories', array('active' => $data['active']));
			}
		}
	}

	public function coupe_remove_category($id)
	{
		$this->db->delete('Coupe_materials_categories', array('id' => $id));
	}

	public function coupe_check_category_for_children($id)
	{
		$this->db->limit(1);
		$query = $this->db->get_where('Coupe_materials_categories', array('parent' => $id));
		return count($query->result_array());
	}

	public function coupe_get_category_children($id)
	{
		$query = $this->db->get_where('Coupe_materials_categories', array('parent' => $id));
		return $query->result_array();
	}

	public function coupe_check_category_name($name, $id = false)
	{
		$this->db->from('Coupe_materials_categories');
		if($id != null){
			$this->db->where('id !=', $id);
		}
		$this->db->where('name',$name);
		$query = $this->db->get();
		return $query->num_rows();
	}





	public function coupe_get_item($id)
	{
		$query = $this->db->get_where('Coupe_materials_items', array('id' => $id));
		return $query->row_array();
	}


	public function coupe_get_items($category, $offset, $limit)
	{
		if($category == 0){
			$this->db->order_by('id','DESC');
			$query = $this->db->get('Coupe_materials_items',$limit,$offset);
			return $query->result_array();
		} else {
			$children = $this->get_category_children($category);

			$this->db->from('Coupe_materials_items');
//			$this->db->where('category',$category);
            $this->db->like('category', '"' . $category . '"');
			if(count($children) > 0){
				foreach ($children as $child){
//					$this->db->or_where('category',$child['id']);
                    $this->db->or_like('category','"'.$child['id'].'"');
				}
			}


			$this->db->limit($limit, $offset);
			$this->db->order_by('id','DESC');
			$query = $this->db->get();
			return $query->result_array();
		}
	}


	public function coupe_get_items_count($category)
	{
		if($category == 0){
			return $this->db->count_all('Coupe_materials_items');
		} else {
			$children = $this->get_category_children($category);
			$this->db->from("Coupe_materials_items");
			$this->db->where('category', $category);
			if(count($children) > 0){
				foreach ($children as $child){
					$this->db->or_where('category',$child['id']);
				}
			}
			return $this->db->count_all_results();
		}

	}


	public function coupe_add_item($data)
	{
		$this->db->insert('Coupe_materials_items', $data);
		return $this->db->insert_id();
	}

	public function coupe_update_item($id, $data)
	{
		$this->db->where('id', $id);
		$this->db->update('Coupe_materials_items', $data);
	}

	public function coupe_get_all_active_items()
	{
		$this->db->order_by('id','DESC');
		$this->db->where('active',1);
		$query = $this->db->get('Coupe_materials_items');
		return $query->result_array();
	}

	public function coupe_get_all_items()
	{
		$query = $this->db->get('Coupe_materials_items');
		return $query->result_array();
	}

	public function coupe_remove_items($id)
	{
		$this->db->delete('Coupe_materials_items', array('id' => $id));
	}

    public function coupe_clear_all()
    {
        $this->db->empty_table('Coupe_materials_items');
	}

	public function remove_blank(){
        for ($i = 3431; $i < 122038; $i++){
            $this->db->delete('Materials_items', array('id' => $i));
        }
//        $this->db->where('name', '');
//        $this->db->where('category', 0);
//        $this->db->delete('Materials_items');
    }

}