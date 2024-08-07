<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Settings extends CI_Controller {

    public function __construct()
    {
        parent::__construct();
        $this->load->helper('url_helper');
        $this->load->model('settings_model');
        $this->load->library('session');
		$this->load->model("Menu_model");

        if(!$this->session->username || $this->session->username != $this->config->item('username')){
            file_put_contents($_SERVER['DOCUMENT_ROOT'] . '/logs/' . $this->config->item('username') . '_login.txt', print_r($this->session, true));
            file_put_contents($_SERVER['DOCUMENT_ROOT'] . '/logs/' . $this->config->item('username') . '_sid.txt', print_r($this->session->session_id, true));
            redirect('login', 'refresh');
        }
    }



    public function index()
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

        // $this->load->view('templates/header', $data);
        // $this->load->view('/settings', $data);
        // $this->load->view('templates/footer', $data);

		$data['js_include'] = [
            'libs/vue.min.js',
			'admin_js/vue/kitchen/account_settings.js?'. md5(date('m-d-Y-His A e')),
			'admin_js/vue/filemanager2.js?'.md5(date('m-d-Y-His A e')),
			'theme/js/plugins/clipboard/clipboard.min.js'
        ];

        $data['css_include'] = [];
		$data['menus_list'] = $this->Menu_model->get_all_menus();
        $data['include'] = 'settings';
		$data['modules'] = [ 'image_picker' ];
        $this->load->view('templates/layout', $data);
    }

    public function get_settings()
    {
        $data = $this->settings_model->get_settings();
//        foreach ($data as &$val){
//            $val = xssafe($val);
//        }
//        if (strpos($data['logo'], '/images/logo') != false ){
//            $arr = explode('/', $data['logo']);
//            $logo_filename = end($arr);
//            if(!file_exists( $this->config->item('const_path') . 'files' )) mkdir($this->config->item('const_path') . 'files');
//            copy($this->config->item('logo_upload') . $logo_filename,dirname(FCPATH) . '/files/' . $logo_filename );
//            $data['logo']  = 'files/' . $logo_filename;
//        }
        echo json_encode($data);
    }

    public function update_settings()
    {
        if($this->input->post()){
            if(!$this->input->post('data')) exit;
//            $data = json_decode($this->input->post('data'), true);
            $data['data'] = $this->input->post('data');
            $dec = json_decode($this->input->post('data'), true);
            $data['order_mail'] = $dec['order_mail'];

            $this->settings_model->update_settings_data($data);
            file_put_contents(FCPATH.'/data/config.json', $this->input->post('data'));
            echo 'success';
        }
    }

	public function kitchen_update_account_settings() {
		if($this->input->post()){


			$errors = [];

			$logo_upload_path = dirname(FCPATH).'/images/';

			if(isset($_FILES['logo']) && $_FILES['logo']['size'] > 0) {
				if($_FILES['logo']['size'] > 100 * 1024) $errors[] = 'Размер файла иконки не должен быть больше 100Кб';

				$icon_info = is_image($_FILES['logo']);
				if(!$icon_info) $errors[] = 'Недопустимый файл логотипа';

				if($icon_info[0] > 300) $errors[] = 'Ширина логотипа не должна быть больше 300px';
				if($icon_info[1] > 300) $errors[] = 'Высота логотипа не должна быть больше 300px';
			}


			if( $this->input->post('order_mail') == "" ) $errors[] = "Название не должно быть пустым";

			if( count($errors) > 0 ){
				echo json_encode(['error' => $errors]);
				return;
			}

			$data = array();


			if($this->input->post('delete_logo') == 1){
				$data = $this->settings_model->get_settings();

				if($data['logo']){
					$filename = get_filename_from_url($data['logo']);;
					if(file_exists($logo_upload_path . $filename)){
						unlink($logo_upload_path . $filename);
					}

				}

				$data['logo'] = null;

			} else {
				$data['logo'] = $this->settings_model->get_settings()['logo'];
			}






			if(isset($_FILES['logo']) && $_FILES['logo']['size'] > 0) {

				$extension     = strtolower( pathinfo( $_FILES['logo']['name'] )['extension'] );
				$new_file_icon = $logo_upload_path . '/logo.' . $extension;

				if ( move_uploaded_file( $_FILES['logo']['tmp_name'], $new_file_icon ) ) {
					$data['logo'] = $this->config->item( 'const_path' ) . 'images/logo.' . $extension;
				} else {
					$errors[] = 'Ошибка при загрузке файла ' . $_FILES['logo']['name'];
					$data['logo'] = null;
				}
			}



			$data['order_mail'] = $this->input->post('order_mail');
			$data['address_line'] = $this->input->post('address_line');
			$data['vk_appid'] = $this->input->post('vk_appid');
			$data['site_url'] = $this->input->post('site_url');
//			$data['default_language'] = $this->input->post('default_language');





			$this->settings_model->update_settings_data($data);

			$config = new stdClass();
			$config->order_mail = $this->input->post('order_mail');
			$config->addres_line = $this->input->post('address_line');
			$config->site_url = $this->input->post('site_url');
			$config->vk_appid = $this->input->post('vk_appid');
//			$config->default_language = $this->input->post('default_language');

			if(isset($data['logo'])){

				if($data['logo'] != null){
					$config->logo = $data['logo'];
				} else {
					$config->logo = "";
				}

			}

			$this->load->helper('file');
			write_file(FCPATH.'/data/config.json', json_encode($config));


//			$this->settings_model->update_settings_data($data);

			echo json_encode(['success' => 'true']);

		}
    }

	public function coupe_account_settings_index() {

    	if($this->config->item('ini')['coupe']['available']){
		    $data['settings'] = $this->settings_model->get();

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
		    $this->load->view('/coupe/header', $data);
		    $this->load->view('/coupe/account_settings', $data);
		    $this->load->view('/templates/footer', $data);
	    } else {
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
		    $this->load->view('/coupe/header', $data);
		    $this->load->view('/coupe/backend_not_available', $data);
		    $this->load->view('/templates/footer', $data);
	    }


    }

	public function coupe_update_account_settings() {

		if($this->config->item('ini')['coupe']['available']!=1) exit;

    	if($this->input->post()){
		    $errors = [];

		    $logo_upload_path = dirname(FCPATH).'/images/';

		    if(isset($_FILES['logo']) && $_FILES['logo']['size'] > 0) {
			    if($_FILES['logo']['size'] > 100 * 1024) $errors[] = 'Размер файла иконки не должен быть больше 100Кб';

			    $icon_info = is_image($_FILES['logo']);
			    if(!$icon_info) $errors[] = 'Недопустимый файл логотипа';

			    if($icon_info[0] > 300) $errors[] = 'Ширина логотипа не должна быть больше 300px';
			    if($icon_info[1] > 300) $errors[] = 'Высота логотипа не должна быть больше 300px';
		    }


		    if( $this->input->post('order_mail') == "" ) $errors[] = "Название не должно быть пустым";

		    if( count($errors) > 0 ){
			    echo json_encode(['error' => $errors]);
			    return;
		    }

		    $data = array();

		    if(isset($_FILES['logo']) && $_FILES['logo']['size'] > 0) {

			    $extension     = strtolower( pathinfo( $_FILES['logo']['name'] )['extension'] );
			    $new_file_icon = $logo_upload_path . '/coupe_logo.' . $extension;

			    if ( move_uploaded_file( $_FILES['logo']['tmp_name'], $new_file_icon ) ) {
				    $data['logo'] = $this->config->item( 'const_path' ) . 'images/coupe_logo.' . $extension;
			    } else {
				    $errors[] = 'Ошибка при загрузке файла ' . $_FILES['logo']['name'];
			    }
		    }



		    $data['order_mail'] = $this->input->post('order_mail');
		    $data['address_line'] = $this->input->post('address_line');
		    $data['vk_appid'] = $this->input->post('vk_appid');
		    $data['site_url'] = $this->input->post('site_url');



		    if($this->input->post('delete_logo') == 1){
			    $data = $this->settings_model->get();

			    if($data['logo']){
			    	$filename = get_filename_from_url($data['logo']);;
				    if(file_exists($logo_upload_path . $filename)){
					    unlink($logo_upload_path . $filename);
				    }

			    }

			    $data['logo'] = null;

		    }

		    $this->settings_model->update($data);

		    echo json_encode(['success' => 'true']);

	    }

    }

    public function change_password()
    {
        if(!isset($_POST['new_pass'])) exit;
        if(!isset($_POST['old_pass'])) exit;

        $userdata = explode(';', file_get_contents (dirname(FCPATH) . '/config/data/usr.txt'));

        $pass = $userdata[1];





        if(crypt($_POST['old_pass'],'JL68XT@!9%OUN#_ZG:R!2PhxuOFUDKrQPtK"!b~u+um@$4^KE:@AR=8_S;lfd#U') != $pass){
            echo json_encode('old_pass_wrong');
            exit;
        }

        $data = $userdata[0] . ';' . crypt($_POST['new_pass'],'JL68XT@!9%OUN#_ZG:R!2PhxuOFUDKrQPtK"!b~u+um@$4^KE:@AR=8_S;lfd#U');

        file_put_contents(dirname(FCPATH) . '/config/data/usr.txt', $data);


        echo json_encode('success');

    }

    public function make_db_backup()
    {
        $from = dirname(FCPATH) . '/data/db';
        $to = dirname(FCPATH) . '/backup/db_backup';
        $info =  dirname(FCPATH) . '/backup/info.txt';



        if(copy($from, $to)){
            echo json_encode('success');
            date_default_timezone_set('Europe/Moscow');
            $date = date('d.m.Y h:i:s', time());
            file_put_contents($info, $date);
        } else {
            echo json_encode('error');
        }
    }

    public function restore_db_backup()
    {
        $from = dirname(FCPATH) . '/backup/db_backup';
        $to = dirname(FCPATH) . '/data/db';

        if(copy($from, $to)){
            echo json_encode('success');
        } else {
            echo json_encode('error');
        }
    }

    public function get_backup_info()
    {

        $result = [];

        $dir = dirname(FCPATH) . '/backup/';
        $backup = dirname(FCPATH) . '/backup/db_backup';
        $info =  dirname(FCPATH) . '/backup/info.txt';
        if (!is_dir($dir)) {
            mkdir($dir);
        }

        if(!file_exists($backup)){
            echo json_encode('no_backup');
            return;
        } else {
            $date = file_get_contents($info);

            echo json_encode($date);
        }

//        if(!is_dir(dirname(FCPATH) .'/backup')) mkdir(!is_dir(dirname(FCPATH) .'/backup'));


    }
}
