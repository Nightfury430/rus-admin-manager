<?php
class Prices_model extends CI_Model {

    public function __construct()
    {
        $this->load->database();
    }

    public function get()
    {
        return $this->db->get('Prices')->row_array();
    }

    public function update($data)
    {
        $this->db->update('Prices', $data);
    }
}