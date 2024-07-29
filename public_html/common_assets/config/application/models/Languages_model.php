<?php
class Languages_model extends CI_Model {

	public function __construct()
	{
		$this->load->database();
		$this->db_common = $this->load->database('common', TRUE);
	}

	public function get($code)
	{
		$query = $this->db->get_where('Languages', array('code' => $code));
		return $query->row_array()['data'];
	}

	public function get_front($code)
	{
		$query = $this->db->get_where('Languages', array('code' => $code));
		return $query->row_array()['data_frontend'];
	}

	public function update($code, $data) {
		$this->db->where('code', $code);
		$this->db->update('Languages', $data);
	}

}