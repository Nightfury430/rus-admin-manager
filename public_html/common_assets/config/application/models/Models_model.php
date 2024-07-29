<?php
class Models_model extends CI_Model {

	public function __construct()
	{
		$this->load->database();
		$this->db_common = $this->load->database('common', TRUE);
	}


	public function init($params){
		if($params['type'] == 'tech'){
			$this->categories_table = $this->categories_table;
			$this->items_table = $this->items_table;
		}

		if($params['type'] == 'interior'){
			$this->categories_table = 'Interior_categories';
			$this->items_table = 'Interior_items';
		}

		if($params['type'] == 'comms'){
			$this->categories_table = 'Comms_categories';
			$this->items_table = 'Comms_items';
		}

		if($params['type'] == 'decoration'){
			$this->categories_table = 'Decoration_categories';
			$this->items_table = 'Decoration_items';
		}
	}

	public function test_get() {
		$query = $this->db->get($this->categories_table);
		return $query->result_array();
	}

	public function get_categories()
	{
		$query = $this->db->get($this->categories_table);
		return $query->result_array();
	}

	public function get_category($id)
	{
		$query = $this->db->get_where($this->categories_table, array('id' => $id));
		return $query->row_array();
	}

	public function get_active_categories()
	{
		$this->db->from($this->categories_table);
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

		return $this->db->insert($this->categories_table, $data);
	}

	public function update_category($id)
	{
		$data = array(
			'name' => $this->input->post('name'),
			'parent' => $this->input->post('parent'),
			'active' => $this->input->post('active')
		);

		$this->db->where('id', $id);
		$this->db->update($this->categories_table, $data);


		$children = $this->get_category_children($id);

		if(count($children) > 0){
			foreach ($children as $child){
				$this->db->where('id', $child['id']);
				$this->db->update($this->categories_table, array('active' => $this->input->post('active')));
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
        $delete = $this->db->delete($this->categories_table);
        return $delete?true:false;
	}

	public function check_category_for_children($id)
	{
		$this->db->limit(1);
		$query = $this->db->get_where($this->categories_table, array('parent' => $id));
		return count($query->result_array());
	}

	public function get_category_children($id)
	{
		$query = $this->db->get_where($this->categories_table, array('parent' => $id));
		return $query->result_array();
	}

	public function set_category_active( $id, $val ) {
		$cat = $this->get_category($id);
		if($cat['parent']){
			$parent = $this->get_category($cat['parent']);

			if($parent['active']){
				$this->db->where('id', $id);
				$this->db->update($this->categories_table, array('active'=>$val));

				echo 'ok';

			} else {
				echo 'no';
			}
		} else {
			$this->db->where('id', $id);
			$this->db->update($this->categories_table, array('active'=>$val));
			$children = $this->get_category_children($id);
			if(count($children) > 0){
				foreach ($children as $child){
					$this->db->where('id', $child['id']);
					$this->db->update($this->categories_table, array('active' => $val));
				}
			}
			echo 'ok';
		}
	}


	public function get_items($category, $offset, $limit)
	{
		if($category == 0){
			$this->db->order_by('id','DESC');
			$query = $this->db->get($this->items_table,$limit,$offset);
			return $query->result_array();
		} else {

			$children = $this->get_category_children($category);

			$this->db->from($this->items_table);
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
		$query = $this->db->get($this->items_table);
		return $query->result_array();
	}

	public function get_all_active_items()
	{
		$this->db->order_by('id','DESC');
		$this->db->from($this->items_table);
		$this->db->where('active',1);
		$query = $this->db->get();
		return $query->result_array();
	}

	public function get_item($id)
	{
		$query = $this->db->get_where($this->items_table, array('id' => $id));
		return $query->row_array();
	}



	public function add_item()
	{
		$this->db->insert($this->items_table, array('name'=>''));
		return $this->db->insert_id();
	}

	public function add_item_data($id, $data)
	{
		$this->db->where('id', $id);
		$this->db->update($this->items_table, $data);
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
		$this->db->update($this->items_table, $data);
	}


	public function remove_items($id)
	{
        if(is_array($id)){
            $this->db->where_in('id', $id);
        }else{
            $this->db->where('id', $id);
        }
        $delete = $this->db->delete($this->items_table);
        return $delete?true:false;
	}

	public function get_items_count($category)
	{
		if($category == 0){
			return $this->db->count_all($this->items_table);
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

	public function set_active( $id, $val ) {
		$this->db->where('id', $id);
		$this->db->update($this->items_table, array('active'=>$val));
	}


	public function get_catalog_items()
	{
		$this->db_common->order_by('category','DESC');
		$query = $this->db_common->get($this->items_table);
		return $query->result_array();
	}

	public function get_catalog_item($id)
	{
		$query = $this->db_common->get_where($this->items_table, array('id' => $id));
		return $query->row_array();
	}

	public function get_catalog_categories()
	{
//        $this->db_common->order_by('id','DESC');
		$query = $this->db_common->get($this->categories_table);
		return $query->result_array();
	}

}