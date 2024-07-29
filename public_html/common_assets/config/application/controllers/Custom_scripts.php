<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Custom_scripts extends CI_Controller {

    public function __construct()
    {
        parent::__construct();
        $this->load->helper('url_helper');
        $this->load->library('session');
        $this->load->helper('file');

        if(!$this->session->username || $this->session->username != $this->config->item('username')){
            redirect('login', 'refresh');
        }
    }

    public function index()
    {
        $data['custom_js'] = file_get_contents( dirname(FCPATH) .'/assets/custom.js' );
        $data['custom_css'] = file_get_contents( dirname(FCPATH) .'/assets/custom.css' );
        $data['custom_html'] = file_get_contents( dirname(FCPATH) .'/assets/custom.html' );

        if(!file_exists(dirname(FCPATH) .'/assets/custom_admin.js')) touch (dirname(FCPATH) .'/assets/custom_admin.js');

        $data['custom_js_admin'] = file_get_contents( dirname(FCPATH) .'/assets/custom_admin.js' );

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
        $this->load->view('templates/header', $data);
        $this->load->view('/custom_scripts/index', $data);
        $this->load->view('templates/footer', $data);

    }

    public function save_data()
    {
        if(isset($_POST)){

//        	print_r(htmlspecialchars ($_POST['custom_html']));
//
//        	exit;

            if(!file_exists(dirname(FCPATH) .'/assets/custom_admin.js')) touch (dirname(FCPATH) .'/assets/custom_admin.js');

            write_file( dirname(FCPATH) .'/assets/custom.js', $_POST['custom_js']);
            write_file( dirname(FCPATH) .'/assets/custom_admin.js', $_POST['custom_js_admin']);
            write_file( dirname(FCPATH) .'/assets/custom.css', $_POST['custom_css']);
            write_file( dirname(FCPATH) .'/assets/custom.html', $_POST['custom_html']);


            redirect('custom_scripts/', 'refresh');
        }
    }

	public function coupe_index() {

    	$data['custom_js'] = '';
    	$data['custom_css'] = '';
    	$data['custom_html'] = '';

    	if(file_exists( dirname(FCPATH) .'/assets/coupe_custom.js' )){
		    $data['custom_js'] = file_get_contents( dirname(FCPATH) .'/assets/coupe_custom.js' );
		    $data['custom_css'] = file_get_contents( dirname(FCPATH) .'/assets/coupe_custom.css' );
		    $data['custom_html'] = file_get_contents( dirname(FCPATH) .'/assets/coupe_custom.html' );
	    }



		$data['coupe'] = true;

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
		$this->load->view('coupe/header', $data);
		$this->load->view('/custom_scripts', $data);
		$this->load->view('templates/footer', $data);
    }

	public function save_data_coupe()
	{
		if(isset($_POST)){

			write_file( dirname(FCPATH) .'/assets/coupe_custom.js', $_POST['custom_js']);
			write_file( dirname(FCPATH) .'/assets/coupe_custom.css', $_POST['custom_css']);
			write_file( dirname(FCPATH) .'/assets/coupe_custom.html', $_POST['custom_html']);


			redirect('custom_scripts/coupe_index', 'refresh');
		}
	}

    public function pro()
    {
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

        $this->load->view('templates/header', $data);
        $this->load->view('/custom_scripts/index_pro', $data);
        $this->load->view('templates/footer', $data);
	}

    public function get_files()
    {

	}

    public function get_file($path)
    {
        $base_path = dirname(FCPATH) . '/assets/';
        if(file_exists($base_path . $path)){
            echo file_get_contents($base_path . $path);
        } else {
            echo 'no_file';
        }
	}

    public function get_items()
    {
        $this->load->model('common_model');

	}

    public function add_file()
    {
        $this->load->model('common_model');
	}

    public function update_file()
    {

    }

    public function remove_file()
    {

    }

}
