<?php
class Project_settings_model extends CI_Model {

    public function __construct()
    {
        $this->load->database();
    }

    public function get_settings()
    {
        return $this->db->get('Project_settings')->row_array();
    }

    public function update_settings($data)
    {
        $this->db->update('Project_settings', $data);
    }


	public function get_coupe() {
		return $this->db->get('Coupe_project_settings')->row_array();
    }

	public function update_coupe($data) {
		$this->db->update('Coupe_project_settings', $data);
    }

}