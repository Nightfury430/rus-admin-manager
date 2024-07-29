<?php
class Constructor_model extends CI_Model {

    public function __construct()
    {
        $this->load->database();
    }

    public function get()
    {
        return $this->db->get('Constructor_settings')->row_array();
    }

    public function update($data)
    {
        $this->db->update('Constructor_settings', $data);
    }


	public function get_coupe() {
		return $this->db->get('Coupe_constructor_settings')->row_array();
    }

	public function update_coupe($data) {
		$this->db->update('Coupe_constructor_settings', $data);

	}
}