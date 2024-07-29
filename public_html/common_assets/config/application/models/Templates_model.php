<?php
class Templates_model extends CI_Model {

    private $table_name = 'Templates';


    public function __construct()
    {
        $this->load->database();
    }

    public function get_all()
    {
	    $this->db->order_by('id','DESC');
	    $query = $this->db->get($this->table_name);
        return $query->result_array();
    }

    public function get_all_active()
    {
	    $this->db->order_by('id','DESC');
	    $query = $this->db->get_where($this->table_name, array('active' => 1));
        return $query->result_array();
    }

    public function get_one($id)
    {
        $query = $this->db->get_where($this->table_name, array('id' => $id));
        return $query->row_array();
    }

    public function add()
    {
        $this->db->insert($this->table_name, array('name' => ''));
        return $this->db->insert_id();
    }


    public function add_files($id, $icon, $file)
    {
        $this->db->where('id', $id);
        $this->db->update($this->table_name, array('file' => $file,'icon' => $icon));
    }

    public function update($id, $data)
    {
        $this->db->where('id', $id);
        $this->db->update($this->table_name, $data);
    }


    public function remove($id)
    {
        $this->db->delete($this->table_name, array('id' => $id));
    }

	public function set_active( $id, $val ) {
		$this->db->where('id', $id);
		$this->db->update($this->table_name, array('active'=>$val));
	}


	public function get_all_coupe()
	{
		$query = $this->db->get('Coupe_templates');
		return $query->result_array();
	}

	public function get_all_active_coupe()
	{
		$query = $this->db->get_where('Coupe_templates', array('active' => 1));
		return $query->result_array();
	}

	public function get_one_coupe($id)
	{
		$query = $this->db->get_where('Coupe_templates', array('id' => $id));
		return $query->row_array();
	}

	public function add_coupe()
	{
		$this->db->insert('Coupe_templates', array('name' => ''));
		return $this->db->insert_id();
	}


	public function add_files_coupe($id, $icon, $file)
	{
		$this->db->where('id', $id);
		$this->db->update('Coupe_templates', array('file' => $file,'icon' => $icon));
	}

	public function update_coupe($id, $data)
	{
		$this->db->where('id', $id);
		$this->db->update('Coupe_templates', $data);
	}


	public function remove_coupe($id)
	{
		$this->db->delete('Coupe_templates', array('id' => $id));
	}




    public function update_files($id, $icon = '', $file = '')
    {
        $this->db->where('id', $id);

        if($icon == '' && $file == ''){
            return;
        }

        $data = array();
        if($icon != '' ){
            $data['icon'] = $icon;
        }
        if($file != '' ){
            $data['file'] = $file;
        }



        $this->db->update($this->table_name, $data);
    }

}