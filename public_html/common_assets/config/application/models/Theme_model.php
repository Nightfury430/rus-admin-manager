<?php
class Theme_model extends CI_Model {

	public function __construct()
	{
		$this->load->database();
	}

	public function get()
	{
		return $this->db->get('Theme')->row_array();
	}

	public function update($data)
	{



		$this->db->update('Theme', $data);


	}
}

