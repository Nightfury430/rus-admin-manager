<?php
class Settings_model extends CI_Model {

    public function __construct()
    {
        $this->load->database();
    }

    public function get_settings()
    {
        return $this->db->get('Settings')->row_array();
    }

    public function update_settings($logo = false)
    {


        $data = array(
            'order_mail' => $this->input->post('order_mail'),
            'address_line' => $this->input->post('address_line'),
            'site_url' => $this->input->post('site_url'),
            'vk_appid' => $this->input->post('vk_appid'),
//            'default_language' => $this->input->post('default_language')
        );




        if($logo != false){
            $data['logo'] = $logo;
        }

        if($this->input->post('delete_logo_flag') == 1){
            $data['logo'] = null;
        }

        $this->db->update('Settings', $data);


    }


    public function update_settings_data($data)
    {
        $this->db->update('Settings', $data);
    }


	public function get()
	{
		return $this->db->get('Coupe_account_settings')->row_array();
	}


	public function update($data)
	{
		$this->db->update('Coupe_account_settings', $data);
	}

}