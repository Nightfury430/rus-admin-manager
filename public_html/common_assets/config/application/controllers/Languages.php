<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Languages extends CI_Controller {

	public function __construct()
	{
		parent::__construct();
		$this->load->helper('url_helper');
		$this->load->model('languages_model');
		$this->load->library('session');

		if(!$this->session->username || $this->session->username != $this->config->item('username')){
			redirect('login', 'refresh');
		}
	}

	public function index()
	{
		$data = array();


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

		$this->load->view( 'templates/header' , $data);
		$this->load->view( '/languages', $data );
		$this->load->view( 'templates/footer', $data );
	}

	public function get_data_ajax($code) {
		echo $this->languages_model->get($code);
	}

    public function get_converted_lang_ajax()
    {
        $data = array();
        $data['original'] = get_default_lang();
        if($this->config->item('ini')['language']['language'] !== 'default') {
            $data['custom'] = json_decode($this->languages_model->get($this->config->item('ini')['language']['language']));
        }
        echo json_encode($data);
	}


	public function get_data_ajax_front($code){
		echo $this->languages_model->get_front($code);
	}

	public function update_data_ajax($code) {
		if($this->input->post()){

			$data = array();
			$data['data'] = $this->input->post('data');
			$data['data_frontend'] = $this->input->post('data_frontend');

			$ini = $this->config->item('ini');

			if($this->input->post('use_custom_lang') == 'true'){

				if( !array_key_exists('language',$ini) ){
					$ini['language'] = array('language'=>'custom');
					config_write($ini, (dirname(FCPATH) . '/config/data/config.ini'));
				} else {
					$ini['language']['language'] = 'custom';
					config_write($ini, (dirname(FCPATH) . '/config/data/config.ini'));
				}

			} else {
				if( !array_key_exists('language',$ini) ){
					$ini['language'] = array('language'=>'default');
					config_write($ini, (dirname(FCPATH) . '/config/data/config.ini'));
				} else {
					$ini['language']['language'] = 'default';
					config_write($ini, (dirname(FCPATH) . '/config/data/config.ini'));
				}
			}


			$this->languages_model->update($code, $data);
			echo json_encode(['success' => 'true']);
		}
	}

	public function export_csv($front = false) {
		$data = array();


		if($front) {
			$data['lang_arr'] = get_default_lang_front();
		} else {
			$data['lang_arr'] = get_default_lang();
		}




		$csv_data = array();


		foreach ($data['lang_arr'] as $value){
			$csv_array = array();
			$csv_array[] = $value;
			$csv_array[] = '';

			$csv_data[] = $csv_array;
		}


		header('Content-Type: text/csv; charset=utf-8');
		header('Content-Disposition: attachment; filename=data.csv');

		$output = fopen('php://output', 'w');

		fputcsv($output, array('На русском', 'Перевод'));

		foreach ($csv_data as $row) {
			fputcsv($output, $row);
		}

	}

}
