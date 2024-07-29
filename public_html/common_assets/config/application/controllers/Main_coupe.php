<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Main_coupe extends CI_Controller {

	public function __construct()
	{
		parent::__construct();
		$this->load->helper('url_helper');
		$this->load->library('session');


	}


	public function index()
	{



		$data = array();


		if(!$this->session->username || $this->session->username != $this->config->item('username')){

		} else {
			$data['asdf'] = 'asdsdsdsadsads';
		}


		$data['base_url'] =  $this->config->item('const_path');
		$data['parent'] = null;
		if(isset($this->config->item('ini')['parent']) && $this->config->item('ini')['parent']['folder']){
			$host = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://$_SERVER[HTTP_HOST]";
			$data['parent'] = $host . '/clients/' . $this->config->item('ini')['parent']['folder'] . '/';
		}



		$this->load->model('settings_model');

		$settings =  $this->settings_model->get();

		$data['logo'] = $settings['logo'];
		$data['address_line'] = $settings['address_line'];



		if(empty($settings['site_url'])){
			$data['def_site_url'] = $this->settings_model->get_settings()['site_url'];
		} else {
			$data['def_site_url']  = $settings['site_url'];
		}
		$data['vk_appid'] = $settings['vk_appid'];


		$this->load->model('languages_model');
		$data['lang_arr'] = get_default_lang();
		if($this->config->item('ini')['language']['language'] !== 'default'){
			$custom_lang = json_decode($this->languages_model->get($this->config->item('ini')['language']['language']));
			foreach ($data['lang_arr'] as $key=>$value){
				if(isset($custom_lang->$key)) {
					if(!empty($custom_lang->$key)) $data['lang_arr'][$key] = $custom_lang->$key;
				}
			}
		}


		if($this->config->item('ini')['coupe']['available']==1){
			$this->load->view('coupe/frontend/header', $data);
			$this->load->view('coupe/frontend/index', $data);
			$this->load->view('coupe/frontend/footer', $data);
			$this->load->view('coupe/frontend/form', $data);
		} else {
			$this->load->view('coupe/frontend/index_not_available', $data);
		}


	}


}
