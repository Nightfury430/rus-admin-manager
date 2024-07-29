<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Email extends CI_Controller {

	public function __construct()
	{
		parent::__construct();
		$this->load->helper('url_helper');
		$this->load->model('common_model');
		$this->load->library('session');

		if(!$this->session->username || $this->session->username != $this->config->item('username')){
			redirect('login', 'refresh');
		}
	}

	public function index()
	{
		$data['lang_arr'] = get_default_lang();
		$data['settings'] = $this->common_model->get_row('Email');

		$this->load->model('settings_model');
		$data['logo'] = $this->settings_model->get_settings()['logo'];

		if($this->config->item('ini')['language']['language'] !== 'default'){
			$this->load->model('languages_model');

			$custom_lang = json_decode($this->languages_model->get($this->config->item('ini')['language']['language']));
			foreach ($data['lang_arr'] as $key=>$value){
				if(isset($custom_lang->$key)) {
					if(!empty($custom_lang->$key)) $data['lang_arr'][$key] = $custom_lang->$key;
				}
			}
		}



		$this->load->view('templates/header', $data);
		$this->load->view('/email_templates', $data);
		$this->load->view('templates/footer', $data);
	}

	public function preview() {
		$data['settings'] = $this->common_model->get_row('Email');
	}

	public function update_settings() {



		if(isset($_POST)){

			print_r(($_POST));

			$data = $_POST;

			$this->common_model->update_row('Email', $data);
		}
	}

}
