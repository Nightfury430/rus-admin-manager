<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Sections extends CI_Controller {

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
		$this->load->view('sections', $data);
		$this->load->view('templates/footer', $data);
	}

	public function get_data() {
		$this->load->model('interior_model');

		$data = array();

		$data['sections'] = $this->common_model->get_row('Sections')['data'];
		$data['categories'] = $this->interior_model->get_categories();

		echo json_encode($data);


	}

	public function save_data() {


		$this->common_model->update_row('Sections', array('data'=> $this->input->post('data')));

		file_put_contents(dirname(FCPATH).'/data/sections.json', $this->input->post('data'));

		echo json_encode(['success' => 'true']);



	}

//	public function update_report_settings() {
//		if($this->input->post()){
//
//			$this->common_model->update_row('Report', $this->input->post('data'));
//			echo json_encode(['success' => 'true']);
//
//		}
//	}

}
