<?php
class Accessories_model extends CI_Model {

	public function __construct()
	{
		$this->load->database();
	}

	public function get_all()
	{
		return $this->db->get('Accessories')->result_array();
	}

    public function get_images(){
        $this->db->select('id, images');
        return $this->db->get('Accessories')->result_array();
    }


	public function get($id) {
		$this->db->from("Accessories");
		$this->db->where('id', $id);
		return $this->db->count_all_results();
	}

    public function get_code($code) {
        $this->db->from("Accessories");
        $this->db->where('code', $code);
        return $this->db->count_all_results();
    }
    public function update_code($code, $data)
    {
        $this->db->where('code', $code);
        $this->db->update('Accessories', $data);
    }

	public function add( $data ) {


		if($data['type'] != 'common'){



			if(!$this->check_default($data['type'])){
				$data['default'] = "1";
			}
		}

		$this->db->insert('Accessories', $data);

		$result = array();
		$result['id'] = $this->db->insert_id();
		$result['default'] = $data['default'];

		return json_encode($result);
	}


	public function remove( $id ) {
        if(is_array($id)){
            $this->db->where_in('id', $id);
        }else{
            $this->db->where('id', $id);
        }
        $delete = $this->db->delete('Accessories');
        return $delete?true:false;
//		$this->db->delete('Accessories', array('id' => $id));
	}

    public function remove_items($id)
    {
        return $this->remove($id);
	}

	public function set_default($id, $type, $checked) {

		if($checked == 'false'){
			$this->db->where('type', $type);
			$this->db->where('default', "1");
			if($this->db->count_all_results() > 0){
				$this->db->update('Accessories', array('default' => "0"));
			} else {
				return 0;
			}
		} else {
			$this->db->from("Accessories");
			$this->db->where('type', $type);
			$this->db->update('Accessories', array('default' => null));

			$this->db->where('id', $id);
			$this->db->update('Accessories', array('default' => "1"));
		}

		return 1;

	}

	public function check_default( $type ) {
		$this->db->from("Accessories");
		$this->db->where('type', $type);
		$this->db->where('default', "1");
		return $this->db->count_all_results();
	}

	public function check_type_items_exists($type) {
		$this->db->from("Accessories");
		$this->db->where('type', $type);
		return $this->db->count_all_results();
	}

	public function check_if_default_selected($type) {

	}

	public function update($id, $data)
	{
		$this->db->where('id', $id);
		$this->db->update('Accessories', $data);
	}

	public function get_types_data() {
		return $this->db->get('Accessories_types')->row_array();
	}

    public function get_types_items_data() {

        if ($this->db->table_exists('Accessories_types_items') )
        {
            return $this->db->get('Accessories_types_items')->result_array();
        } else {
            return [];
        }


    }


	public function set_type_selection( $type, $value ) {

		if($value == 1){
			if( !$this->check_type_items_exists($type) ){
				echo 'no_items';
				return;
			}
		}

		$this->db->update('Accessories_types', array($type => $value));
	}

	public function clear_db() {
		$this->db->empty_table('Accessories');
	}


    public function clear_items()
    {
        $this->db->empty_table('Accessories');
    }


}

