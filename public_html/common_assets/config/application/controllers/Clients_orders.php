<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Clients_orders extends CI_Controller
{

	public function __construct()
	{
		parent::__construct();
		$this->load->helper('url_helper');
		$this->load->model("Menu_model");
	}


    public function test_mail()
    {
        echo FCPATH;

//        $this->load->library('email');
//        $this->email->from('your@example.com', 'Your Name');
//        $this->email->to('someone@example.com');
//        $this->email->cc('another@another-example.com');
//        $this->email->bcc('them@their-example.com');
//
//        $this->email->subject('Email Test');
//        $this->email->message('Testing the email class.');
//
//        $this->email->send();
	}

    public function get_bazis()
    {

//        $url = 'http://194.87.99.226:88/test.php';
        $url = 'http://194.87.238.88/test.php';

        $data = $_POST;

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 2);
        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER,array (
            "Content-type: application/x-www-form-urlencoded"
        ));
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
        $result = curl_exec($ch);
        curl_close($ch);

        $res = json_decode($result, true);
        file_put_contents(dirname(FCPATH) . '/' . 'bazis.b3d', file_get_contents($res['url']));

        $res = [
            'url' => $this->config->item( 'const_path' ) .  'bazis.b3d',
            'filename' => $res['original_filename']
        ];

        echo json_encode($res);

        exit;


	}


    public function test_smtp()
    {
        $this->load->library('email');
        $e_config['protocol'] = 'smtp';
        $e_config['smtp_host'] = 'electro-cuisine.be';
        $e_config['smtp_user'] = 'offre@electro-cuisine.be';
        $e_config['smtp_pass'] = 'planplaceoffre';
        $e_config['smtp_port'] = "465";
        $e_config['smtp_timeout'] = "20";
        $e_config['smtp_keepalive'] = TRUE;
        $e_config['smtp_crypto'] = 'ssl';
        $this->email->initialize($e_config);
        $this->email->set_newline("\r\n");
        $this->email->from('offre@electro-cuisine.be');
        $this->email->to('mae1storm2305@gmail.com');
        $this->email->set_mailtype("html");
        $this->email->subject('Test smtp mail');
        $this->email->message('this is test smtp mail');

        print_r($e_config);


        $this->email->attach($_SERVER['DOCUMENT_ROOT'] . '/clients/15824722/1.pdf', 'attachment', 'Condition preparation du chantier.pdf');
        $this->email->attach($_SERVER['DOCUMENT_ROOT'] . '/clients/15824722/1.pdf', 'attachment', 'Voorwaarde voor voorbereiding van de locatie.pdf');

        $res = $this->email->send();

        file_put_contents($_SERVER['DOCUMENT_ROOT'] . '/logs/0_smtp_last3.txt', print_r($res, true));
        file_put_contents($_SERVER['DOCUMENT_ROOT'] . '/logs/0_smtp_last4.txt', print_r($this->email->print_debugger(), true));


    }

	public function index($start = false)
	{
		$this->load->library('session');

		if(!$this->session->username || $this->session->username != $this->config->item('username')){
			redirect('login', 'refresh');
		}

		$this->load->model('clients_orders_model');

		$this->load->library('pagination');
		$config['base_url'] = site_url('clients_orders/index/');
		$config['total_rows'] = $this->clients_orders_model->get_items_count();
		$config['per_page'] = 20;

		$config['first_link'] = false;
		$config['last_link'] = false;

		$config['full_tag_open'] = '<ul class="pagination">';
		$config['full_tag_close'] = '</ul>';

		$config['next_tag_open'] = '<li>';
		$config['next_tag_close'] = '</li>';

		$config['num_tag_open'] = '<li>';
		$config['num_tag_close'] = '</li>';

		$config['cur_tag_open'] = '<li class="active"><a href="#">';
		$config['cur_tag_close'] = '</a></li>';

		$config['prev_tag_open'] = '<li>';
		$config['prev_tag_close'] = '</li>';

		$config['num_links'] = 8;

		$config['uri_segment'] = 3;

		$this->pagination->initialize($config);
		$data['pagination'] =  $this->pagination->create_links();


		$this->load->model('constructor_model');

		$data['items'] = $this->clients_orders_model->get_items($start, 20);
		$data['settings'] = $this->constructor_model->get();

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

		$data['js_include'] = [
			'admin_js/vue/clients_order/index.js',
		];
		$data['css_include'] = [];
		$data['include'] = 'clients_orders/index';
		$data['menus_list'] = $this->Menu_model->get_all_menus();
		$this->load->view('templates/layout', $data);
	}

	public function remove($id)
	{
		$this->load->model('clients_orders_model');
		$item = $this->clients_orders_model->get_one($id);

		unlink(dirname(FCPATH) . '/clients_orders/' . $item['project_file']);

		$this->clients_orders_model->remove($id);
		redirect('clients_orders/index/', 'refresh');
	}


    public function remove_multiple_by_year()
    {
        $this->load->model('clients_orders_model');
        $orders = $this->clients_orders_model->get_all();

        $sy = strtotime('01.01.2023');

        foreach ($orders as $item){
            $date = $item['date'];
            $date = explode('-', $date)[0];
            $datestr = strtotime($date);

            $arr = [];

            if($datestr < $sy){
                $item = $this->clients_orders_model->get_one($item['id']);
                if(file_exists(dirname(FCPATH) . '/clients_orders/' . $item['project_file'])){
                    unlink(dirname(FCPATH) . '/clients_orders/' . $item['project_file']);
                }
                $arr[] = $item['id'];
                echo $date;
                echo '<br>';
            }

            $this->clients_orders_model->remove_batch($arr);


        }


	}

    public function test_multi()
    {
           file_put_contents(dirname(FCPATH) . '/clients_orders/' .'123.txt', 123);
           echo  123;
	}


	public function sendkm() {
//		$this->load->model('common_model');
//		$email_templates = $this->common_model->get_row('Email');
//		$this->load->model('settings_model');
//		$settings = $this->settings_model->get_settings();
//
//
//		print_pre(json_decode($email_templates['custom_form_data'], true));

//		if(isset($_POST['name'])){
		if(isset($_POST)){
            $this->load->library('email');


//			include_once dirname(FCPATH) . '/config/config.php';
			$this->load->model('settings_model');
			$this->load->model('clients_orders_model');
			$this->load->model('constructor_model');
			$this->load->model('common_model');

			$settings = $this->settings_model->get_settings();
			$order_number = $this->clients_orders_model->get_last_number();
			$constructor_settings = $this->constructor_model->get();
			$email_templates = $this->common_model->get_row('Email');
			$pdf_settings = $this->common_model->get_row('Report');



			$admin_mail = str_replace(' ', '', $settings['order_mail']);
			$site_url = $settings['site_url'];

			$client_mail = '';

			$data = $_POST;



			if(isset($data['email'])) $client_mail = $data['email'];

			$order_number += 1;

            $co_name = '';

			if($email_templates['use_custom_form'] == 1){
				$cl_data = array();
//				$cl_data['name'] = $this->input->post('name');
//				$cl_data['email'] = $this->input->post('email');
//				$cl_data['phone'] = $this->input->post('phone');
//				$cl_data['comments'] = $this->input->post('comments');
				$cl_data['date'] = date("d.m.Y - H:i");
				$cl_data['price'] = $this->input->post('price');
				$cl_data['referred'] = $this->input->post('referred');
				$cl_data['number'] = $order_number;

				$cf_data = json_decode($email_templates['custom_form_data'], true);

				foreach ($cf_data as $cf_item){
					if($cf_item['compatibility'] == 'name'){
						if(isset($_POST[$cf_item['id']])){
							$cl_data['name'] = $_POST[$cf_item['id']];
						}
					}

					if($cf_item['compatibility'] == 'email'){
						if(isset($_POST[$cf_item['id']])){
							$cl_data['email'] = $_POST[$cf_item['id']];
							$client_mail = $_POST[$cf_item['id']];
						}
					}

					if($cf_item['compatibility'] == 'phone'){
						if(isset($_POST[$cf_item['id']])){
							$cl_data['phone'] = $_POST[$cf_item['id']];
						}
					}

					if($cf_item['compatibility'] == 'comments'){
						if(isset($_POST[$cf_item['id']])){
							$cl_data['comments'] = $_POST[$cf_item['id']];
						}
					}
				}

				$dir = dirname(FCPATH) . '/clients_orders/';
				$file = date("d.m.Y_H.i")  . '.dbs';

                $co_name = $file;

				$cl_data['project_file'] = $file;
				$this->load->helper('file');
				write_file($dir . $file, $this->input->post('save_data'));
				$this->clients_orders_model->add($cl_data);

			} else {
				$cl_data = array();
				$cl_data['name'] = $this->input->post('name');
				$cl_data['email'] = $this->input->post('email');
				$cl_data['phone'] = $this->input->post('phone');
				$cl_data['comments'] = $this->input->post('comments');
				$cl_data['date'] = date("d.m.Y - H:i");
				$cl_data['price'] = $this->input->post('price');
				$cl_data['referred'] = $this->input->post('referred');
				$cl_data['number'] = $order_number;

				$dir = dirname(FCPATH) . '/clients_orders/';
				$file = date("d.m.Y_H.i") . '_' . $cl_data['email'] . '_' . $cl_data['phone'] . '.dbs';

                $co_name = $file;

				$cl_data['project_file'] = $file;
				$this->load->helper('file');
				write_file($dir . $file, $this->input->post('save_data'));
				$this->clients_orders_model->add($cl_data);
			}






			if($site_url != ''){
				if (strpos($site_url, 'http://') === false && strpos($site_url, 'https://') === false) {
					$site_url = 'http://'. $site_url;
				}
				$true_url = parse_url($site_url);
			} else {
				$true_url = array();
				$true_url['host'] = basename(dirname(FCPATH));
			}

			$from = $this->config->item('sv_email_order_from');
			if(isset($override_from_address)){
				$from = $override_from_address;
			}

			if(isset($_POST['force_vr_address'])){
				$from = $_POST['force_vr_address'];
			}





			$date = date("d.m.Y_H.i");
			$filename_prefix = 'N' . $order_number . '_' . $date;

			$msg = '';
			$subject = '';



			if($email_templates['use_email_template'] == 1){

				$subject = $email_templates['email_template_heading'];
				$msg = $email_templates['email_template'];

				if($email_templates['use_custom_form'] == 1){
					$cf_data = json_decode($email_templates['custom_form_data'], true);
				} else {
					$cf_data = array();
					$cf_data['name'] = array('id' => 'name');
					$cf_data['email'] = array('id' => 'email');
					$cf_data['phone'] = array('id' => 'phone');
					$cf_data['comments'] = array('id' => 'comments');
				}



				foreach ($cf_data as $cf_item){

					if(isset($cf_item['type']) && $cf_item['type'] == 'checkbox'){
						if(isset($data[$cf_item['id']])){
							$msg = str_replace('{{'. $cf_item['id'] .'}}', '&#10003;', $msg);
						} else {
							$msg = str_replace('{{'. $cf_item['id'] .'}}', '&#10005;', $msg);
						}
					} else {
						if(isset($data[$cf_item['id']])){
							$subject = str_replace('{{'. $cf_item['id'] .'}}', $data[$cf_item['id']], $subject);
							$msg = str_replace('{{'. $cf_item['id'] .'}}', $data[$cf_item['id']], $msg);
						}
					}
				}



				if ( isset( $data['price'] ) ) {
					if ( $data['price'] && $data['price'] != 'NaN р.' ) {
						$subject = str_replace( '{{price}}', $data['price'], $subject );
						$msg = str_replace( '{{price}}', $data['price'], $msg );
					}
				}

                foreach ($data as $key=>$val){
                    if(substr($key, 0, 7) == 'custom_'){
                        $subject = str_replace('{{'. $key .'}}', $val, $subject);
                        $msg = str_replace('{{'. $key .'}}', $val, $msg);
                    }
                }

				$subject = str_replace('{{project_filename}}', $filename_prefix . '.dbs', $subject);
				$subject = str_replace('{{order_number}}', $order_number, $subject);

				$msg = str_replace('{{project_filename}}', $filename_prefix . '.dbs', $msg);
				$msg = str_replace('{{order_number}}', $order_number, $msg);





			} else {

				if($order_number == ''){
					$subject = "Заявка с конструктора с сайта " . $true_url['host'];
				} else {
					$subject = "Заявка № ". $order_number . " с конструктора с сайта " . $true_url['host'];
				}

				$msg .= '<strong>Заявка с конструктора №' . $order_number . ':</strong><br><br><br>';

				$msg .= '<strong>Заявка с конструктора:</strong><br><br><br>';
				if(isset($data['name'])) $msg .= 'Имя: ' . $data['name'] . '<br>';
				if(isset($data['surname'])) $msg .= 'Фамилия: ' . $data['surname']. '<br>';
				if(isset($data['email'])) $msg .= 'Email: ' . $data['email'] . '<br>';
				if(isset($data['phone'])) $msg .= 'Телефон: ' . $data['phone']. '<br>';
				if(isset($data['city'])) $msg .= 'Город: ' . $data['city']. '<br>';
				if(isset($data['comments'])) $msg .= 'Комментарии: ' . $data['comments']. '<br>';
				if(isset($data['price'])){
					if($data['price'] && $data['price'] != 'NaN р.'){
						$msg.= '<b>Цена</b>: ' . $data['price'];
					}
				}
				if(isset($data['referred'])) $msg.= 'Страница с которой был открыт конструктор: ' . $data['referred'] . '<br>';
				if(isset($data['url'])) $msg.= 'Url страницы: ' . $data['url'] . '<br>';
				if(isset($data['custom_userdata'])) $msg.= '<br>' . $data['custom_userdata'] . '<br>';

				if(isset($override_help_text)){
					$msg.=$override_help_text;
				} else {
					$msg.= '<br>В приложении к письму есть файл ' . $filename_prefix . '.dbs, вы можете сохранить его на компьютер и загрузить в конструктор с помощью кнопки "Загрузить проект" на верхней панели (Иконка с папкой)<br>';
				}

				if(isset($data['form_mats'])) $msg.= $data['form_mats'];
			}



			$attached_files_settings = json_decode($email_templates['attached_files'], true);
			if($attached_files_settings == ''){
				$attached_files_settings = array();

				$attached_files_settings['designer'] = array();
				$attached_files_settings['designer']['screen'] = 1;
				$attached_files_settings['designer']['project'] = 1;
				$attached_files_settings['designer']['pdf'] = 1;

				$attached_files_settings['client'] = array();
				$attached_files_settings['client']['screen'] = 1;
				$attached_files_settings['client']['project'] = 1;
				$attached_files_settings['client']['pdf'] = 0;
			}

			$arr = array();
			$client_arr = array();

			if(!isset($send_project_image)) $send_project_image = true;
			if($send_project_image === true){
				$project_image = base64ToImage($data['screen'], $filename_prefix .'.png');

				if($attached_files_settings['designer']['screen'] == 1) $arr[] = $project_image;
				if($attached_files_settings['client']['screen'] == 1) $client_arr[] = $project_image;

			}

			if(!isset($send_project_file)) $send_project_file = true;
			if($send_project_file === true){
				$fp = fopen($filename_prefix . ".dbs", "wb");
				fwrite($fp, $data['save_data']);
				fclose($fp);
				if($attached_files_settings['designer']['project'] == 1) $arr[] = $filename_prefix . '.dbs';
				if($attached_files_settings['client']['project'] == 1) $client_arr[] = $filename_prefix . '.dbs';
			}


			if(isset ($_POST['pdf_file']) ){
				$project_pdf = base64ToPdf($_POST['pdf_file'], $filename_prefix .'.pdf');

				if($attached_files_settings['designer']['pdf'] == 1) $arr[] = $project_pdf;
				if($attached_files_settings['client']['pdf'] == 1) $client_arr[] = $project_pdf;
			}

            if(isset ($_FILES['pdf_file_blob']) ){

                $_FILES['pdf_file_blob']['name'] = $filename_prefix .'.pdf';

                if($attached_files_settings['designer']['pdf'] == 1) $arr[] = $_FILES['pdf_file_blob'];
                if($attached_files_settings['client']['pdf'] == 1) $client_arr[] = $_FILES['pdf_file_blob'];


            }

            if(isset ($_FILES['xlsx_file']) ){
                $_FILES['xlsx_file']['name'] = $filename_prefix .'.xlsx';
                $arr[] = $_FILES['xlsx_file'];
            }

			if(!isset($send_csv)) $send_csv = false;
			if($send_csv === true){
				file_put_contents($filename_prefix . '.csv',$data['modules_csv']);
				$arr[] = $filename_prefix . '.csv';
			}

			if(isset ($_FILES) ){
				foreach ($_FILES as $key=>$file){
				    if($key != 'xlsx_file' && $key != 'pdf_file_blob' && $file['name'] != ''){
                        if (is_array($file['name'])) {

                            $f_arr = array();
                            $file_count = count($file['name']);
                            $file_keys = array_keys($file);

                            for ($i=0; $i<$file_count; $i++) {
                                foreach ($file_keys as $key) {
                                    $f_arr[$i][$key] = $file[$key][$i];
                                }
                            }

                            foreach ($f_arr as $f_one){
                                if($f_one['name'] != '') $arr[] = $f_one;
                            }
                        } else {
                            $arr[] = $file;
                        }
                    }
				}
			}

//			send_mail($admin_mail, $subject, $msg, $from, $arr);





            if($this->config->item('username') === 'info@electro-cuisine.be') {
                $e_config['protocol'] = 'smtp';
                $e_config['smtp_host'] = 'electro-cuisine.be';
                $e_config['smtp_user'] = 'offre@electro-cuisine.be';
                $e_config['smtp_pass'] = 'planplaceoffre';
                $e_config['smtp_port'] = "465";
                $e_config['smtp_crypto'] = 'ssl';
                $e_config['smtp_timeout'] = "20";
                $e_config['smtp_keepalive'] = TRUE;
                $this->email->initialize($e_config);
                $this->email->set_newline("\r\n");
                $this->email->from('offre@electro-cuisine.be');
                $this->email->to($admin_mail);
                $this->email->set_mailtype("html");
                $this->email->subject($subject);
                $this->email->message($msg);


                foreach ($arr as $f) {
                    if (is_string($f)) {
                        $this->email->attach($f, 'attachment', $f);
                    } else {
                        $this->email->attach($f['tmp_name'], 'attachment', $f['name']);
                    }
                }


                $res = $this->email->send();

                file_put_contents($_SERVER['DOCUMENT_ROOT'] . '/logs/0_smtp_last.txt', print_r($res, true));
            } else {
                $this->email->from($from);
                $this->email->to($admin_mail);
                $this->email->set_mailtype("html");
                $this->email->subject($subject);
                $this->email->message($msg);

                foreach ($arr as $f) {
                    if (is_string($f)) {
                        $this->email->attach($f, 'attachment', $f);
                    } else {
                        $this->email->attach($f['tmp_name'], 'attachment', $f['name']);
                    }
                }
                $this->email->send();
            }


			if(!isset($send_to_client)) $send_to_client = true;

			if($email_templates['send_to_client'] == 0) $send_to_client = false;

			if ($send_to_client === true) {
				$msg1 = '';
				$subject1 = 'Проект кухни';

				if($email_templates['use_email_template_client'] == 1){

					$subject1 = $email_templates['email_template_heading_client'];
					$msg1 = $email_templates['email_template_client'];

					if($email_templates['use_custom_form'] == 1){
						$cf_data = json_decode($email_templates['custom_form_data'], true);
					} else {
						$cf_data = array();
						$cf_data['name'] = array('id' => 'name');
						$cf_data['email'] = array('id' => 'email');
						$cf_data['phone'] = array('id' => 'phone');
						$cf_data['comments'] = array('id' => 'comments');
					}


					foreach ($cf_data as $cf_item){

                        if(isset($cf_item['type']) && $cf_item['type'] == 'checkbox'){
							if(isset($data[$cf_item['id']])){
								$msg1 = str_replace('{{'. $cf_item['id'] .'}}', '&#10003;', $msg1);
							} else {
								$msg1 = str_replace('{{'. $cf_item['id'] .'}}', '&#10005;', $msg1);
							}
						} else {
							if(isset($data[$cf_item['id']])){
								$subject1 = str_replace('{{'. $cf_item['id'] .'}}', $data[$cf_item['id']], $subject1);
								$msg1 = str_replace('{{'. $cf_item['id'] .'}}', $data[$cf_item['id']], $msg1);
							}
						}

					}

					if ( isset( $data['price'] ) ) {
						if ( $data['price'] && $data['price'] != 'NaN р.' ) {
							$subject1 = str_replace( '{{price}}', $data['price'], $subject1 );
							$msg1 = str_replace( '{{price}}', $data['price'], $msg1 );
						}
					}

                    foreach ($data as $key=>$val){
                        if(substr($key, 0, 7) == 'custom_'){
                            $subject1 = str_replace('{{'. $key .'}}', $val, $subject1);
                            $msg1 = str_replace('{{'. $key .'}}', $val, $msg1);
                        }
                    }

					$subject1 = str_replace('{{project_filename}}', $filename_prefix . '.dbs', $subject1);
					$subject1 = str_replace('{{order_number}}', $order_number, $subject1);

					$msg1 = str_replace('{{project_filename}}', $filename_prefix . '.dbs', $msg1);
					$msg1 = str_replace('{{order_number}}', $order_number, $msg1);
				} else {
					$msg1 .= '<strong>Проект кухни с конструктора:</strong><br>';
					$msg1 .= 'Имя: ' . $data['name'] . '<br>';
					$msg1 .= 'Email: ' . $data['email'] . '<br>';
					if ( $data['phone'] ) {
						$msg1 .= 'Телефон: ' . $data['phone'] . '<br>';
					}
					if ( $data['comments'] ) {
						$msg1 .= 'Комментарии: ' . $data['comments'] . '<br>';
					}
					if ( isset( $override_help_text ) ) {
						$msg1 .= $override_help_text;
					} else {
						$msg1 .= '<br>В приложении к письму есть файл ' . $filename_prefix . '.dbs, вы можете сохранить его на компьютер и загрузить в конструктор с помощью кнопки "Загрузить проект" на верхней панели (Иконка с папкой)<br>';
					}
				}

                $this->email->clear(true);

                if($this->config->item('username') === 'info@electro-cuisine.be') {
                    $e_config['protocol'] = 'smtp';
                    $e_config['smtp_host'] = 'electro-cuisine.be';
                    $e_config['smtp_user'] = 'offre@electro-cuisine.be';
                    $e_config['smtp_pass'] = 'planplaceoffre';
                    $e_config['smtp_port'] = "465";
                    $e_config['smtp_crypto'] = 'ssl';
                    $e_config['smtp_timeout'] = "20";
                    $e_config['smtp_keepalive'] = TRUE;
                    $this->email->initialize($e_config);
                    $this->email->set_newline("\r\n");
                    $this->email->from('offre@electro-cuisine.be');
                    $this->email->to($client_mail);
                    $this->email->set_mailtype("html");
                    $this->email->subject($subject1);
                    $this->email->message($msg1);


                    foreach ($client_arr as $f){
                        if(is_string($f)){
                            $this->email->attach($f, 'attachment', $f);
                        } else {
                            $this->email->attach($f['tmp_name'], 'attachment', $f['name']);
                        }
                    }
                    $this->email->attach($_SERVER['DOCUMENT_ROOT'] . '/clients/15824722/1.pdf', 'attachment', 'Condition preparation du chantier.pdf');
                    $this->email->attach($_SERVER['DOCUMENT_ROOT'] . '/clients/15824722/1.pdf', 'attachment', 'Voorwaarde voor voorbereiding van de locatie.pdf');

                    $res = $this->email->send();

                    file_put_contents($_SERVER['DOCUMENT_ROOT'] . '/logs/0_smtp_last2.txt', print_r($res, true));



//                    $this->email->from($from);
//                    $this->email->to($client_mail);
//                    $this->email->set_mailtype("html");
//                    $this->email->subject($subject1);
//                    $this->email->message($msg1);
//
//                    foreach ($client_arr as $f){
//                        if(is_string($f)){
//                            $this->email->attach($f, 'attachment', $f);
//                        } else {
//                            $this->email->attach($f['tmp_name'], 'attachment', $f['name']);
//                        }
//                    }
//
//                    $this->email->attach($_SERVER['DOCUMENT_ROOT'] . '/clients/15824722/1.pdf', 'attachment', 'Condition preparation du chantier.pdf');
//                    $this->email->attach($_SERVER['DOCUMENT_ROOT'] . '/clients/15824722/1.pdf', 'attachment', 'Voorwaarde voor voorbereiding van de locatie.pdf');
//
//                    $this->email->send();
                } else {
                    $this->email->from($from);
                    $this->email->to($client_mail);
                    $this->email->set_mailtype("html");
                    $this->email->subject($subject1);
                    $this->email->message($msg1);

                    foreach ($client_arr as $f){
                        if(is_string($f)){
                            $this->email->attach($f, 'attachment', $f);
                        } else {
                            $this->email->attach($f['tmp_name'], 'attachment', $f['name']);
                        }
                    }

                    $this->email->send();
                }



//				send_mail($client_mail, $subject1, $msg1, $from, $client_arr);
			}

			if(file_exists($filename_prefix .'.png'))unlink($filename_prefix .'.png');
			if(file_exists($filename_prefix .'.dbs'))unlink($filename_prefix .'.dbs');
			if(file_exists($filename_prefix .'.pdf'))unlink($filename_prefix .'.pdf');
			if(file_exists($filename_prefix .'.csv'))unlink($filename_prefix .'.csv');
			if(file_exists($filename_prefix .'.xlsx'))unlink($filename_prefix .'.xlsx');

			if(isset($constructor_settings['custom_order_url'])){
				if(!empty($constructor_settings['custom_order_url'])){

                    $data['order_number'] = $order_number;
                    $data['client_order_filename'] = $co_name;
                    if( isset($_FILES) && isset($_FILES['pdf_file_blob'])) {
                        $data['pdf'] = base64_encode( file_get_contents( $_FILES['pdf_file_blob']['tmp_name'] ) );
                    }


                    $url = $constructor_settings['custom_order_url'];



                    if(isset($data['referred']) && $data['referred'] == 'boas_test'){
                        $url = 'https://mebel.newsite.by/1c_exchange/1c_order_planplace.php?key=Wr3U8XXLW';
                    }
                    if($this->config->item('username') === 'ecom.1mf@gmail.com'){
                        if(isset($data['site_id']) && $data['site_id'] == 2){
                            $url = 'https://www.1mf.ru/ajax/PlanPlacePF/sendOrder.php';
                        }
                    }








                    $options = array(
                        'http' => array(
                            'header'  => "Content-type: application/x-www-form-urlencoded\r\n",
                            'method'  => 'POST',
                            'content' => http_build_query($data),
                            "ssl" => array(
                                "verify_peer" => false,
                                "verify_peer_name" => false,
                            )
                        ),
                    );

                    $context  = stream_context_create($options);
                    $result = file_get_contents($url, false, $context);

//                    $log = array(
//                        'request_data' => $data,
//                        'request_url' => $url,
//                        'request_result' => $result
//                    );
//
//                    file_put_contents($_SERVER['DOCUMENT_ROOT'] . '/logs/' . $this->config->item('username') . '_last.txt', print_r($log, true));
//                    file_put_contents($_SERVER['DOCUMENT_ROOT'] . '/logs/' . $this->config->item('username') . '_post.txt', print_r($data, true));


                    echo($result);

//					if($this->config->item('username') === 'www@ksk.by'){
//
//						$url = $constructor_settings['custom_order_url'];
////					    $cookie = $data['cookie']; //1.Вариант | Берем строку cookie из тела POST запроса
//						$cookie = cookie_str(); //2.Вариант | Получаем строку cookie из $_COOKIE
//						$data['test'] = 'Все круто!)';
//
//						$ch = curl_init();
//						curl_setopt($ch, CURLOPT_URL, $url);
//						curl_setopt($ch, CURLOPT_POST, true);
//						curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 2);
//						curl_setopt($ch, CURLOPT_COOKIE, $cookie);
//						curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data));
//						curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
//						curl_setopt($ch, CURLOPT_HTTPHEADER,array (
//							"Content-type: application/x-www-form-urlencoded"
//						));
//						curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
//						curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
//						$result = curl_exec($ch);
//
//						curl_close($ch);
//
//						echo $result;
//
//						function cookie_str() {
//							$cookie = '';
//							foreach ($_COOKIE as $key => $val) {
//								$cookie .= $key . '=' . $val . ';';
//							}
//							$cookie = substr($cookie, 0, -1);
//
//							return $cookie;
//						}
//
//					} else if($this->config->item('username') === 'shop@avtobardak.net'){
//						$url = $constructor_settings['custom_order_url'];
//
//						$options = array(
//							'http' => array(
//								'header'  => "Content-type: application/x-www-form-urlencoded\r\n",
//								'method'  => 'POST',
//								'content' => http_build_query($data),
//							),
////					    "ssl"=>array(
////						    "verify_peer"=>false,
////						    "verify_peer_name"=>false,
////					    )
//
//						);
//						$context  = stream_context_create($options);
//						$result = file_get_contents($url, false, $context);
//
//
//						echo($result);
//					} else {
//
//
//
//
//					}
				}
			}

		}






	}

	public function test_order() {
		if(isset($_POST['name'])){
			$url = $_POST['url'];

			$options = array(
				'http' => array(
					'header'  => "Content-type: application/x-www-form-urlencoded\r\n",
					'method'  => 'POST',
					'content' => http_build_query($_POST),
				)
			);
			$context  = stream_context_create($options);
			$result = file_get_contents($url, false, $context);


			echo($result);
		}
	}

	public function ajax_add()
	{

		if(isset($_POST)){

			$this->load->model('clients_orders_model');

			$data = array();

			$order_number = $this->clients_orders_model->get_last_number();

			if(file_exists(dirname(FCPATH) . '/counter.txt')){
				$order_number = intval(file_get_contents(dirname(FCPATH) . '/counter.txt'));
				$data['number'] = $order_number+1;
			} else {
				$order_number = '';
			}


			$data['name'] = $this->input->post('name');
			$data['email'] = $this->input->post('email');
			$data['phone'] = $this->input->post('phone');
			$data['comments'] = $this->input->post('comments');
			$data['date'] = date("d.m.Y - H:i");
			$data['price'] = $this->input->post('price');
			$data['referred'] = $this->input->post('referred');

			$dir = dirname(FCPATH) . '/clients_orders/';
			$file = date("d.m.Y_H.i") . '_' . $data['email'] . '_' . $data['phone'] . '.dbs';
			$data['project_file'] = $file;
			$this->load->helper('file');
			write_file($dir . $file, $this->input->post('save_data'));
			$this->clients_orders_model->add($data);

		}


	}


	public function statuses_index()
	{
		$this->load->library('session');

		if(!$this->session->username || $this->session->username != $this->config->item('username')){
			redirect('login', 'refresh');
		}

		$this->load->model('clients_orders_model');

		$data['items'] = $this->clients_orders_model->get_all_statuses();

		$this->load->view('templates/header');
		$this->load->view('clients_orders/statuses_index', $data);
		$this->load->view('templates/footer');
	}

	public function statuses_add()
	{

	}

	public function statuses_update()
	{

	}

	public function statuses_remove()
	{

	}


	public function coupe_send() {

		if (isset($_POST)) {

			$data = $_POST;




			$this->load->model('settings_model');

			$settings = $this->settings_model->get();

			$date = date("d.m.Y_H.i");
			$filename_prefix = $date;

			$to = $settings['order_mail'];

			if(empty($settings['order_mail'])){
				$to = $this->settings_model->get_settings()['order_mail'];
			}



			if(empty($settings['site_url'])){
				$surl = $this->settings_model->get_settings()['site_url'];
			} else {
				$surl = $settings['site_url'];
			}

			$true_url = parse_url($surl);

			if(empty($true_url['host'])){
				$true_url = array();
				$true_url['host'] = basename(dirname(FCPATH));
			}



			$from = $this->config->item('sv_email_order_from');

			$msg = '<strong>Заявка с конструктора:</strong><br><br><br>';
			$msg .= 'Имя: ' . $data['name'] . '<br>';
			$msg .= 'Email: ' . $data['email'] . '<br>';
			if(isset($data['phone'])){
				$msg .= 'Телефон: ' . $data['phone']. '<br>';
			}
			if(isset($data['comments'])){
				$msg .= 'Комментарии: ' . $data['comments']. '<br>';
			}
			if(isset($data['city'])){
				$msg .= 'Город: ' . $data['city']. '<br>';
			}
			if(isset($data['price']) && $data['price'] != 'NaN р.'){
				$msg.= '<b>Цена</b>: ' . $data['price'];
			}
			if(isset($data['referred'])){
				$msg.= 'Страница с которой был открыт конструктор: ' . $data['referred'] . '<br>';
			}
			if(isset($data['url'])){
				$msg.= 'Url страницы: ' . $data['url'] . '<br>';
			}
			if(isset($data['form_mats'])){
				$msg.= $data['form_mats'];
			}

			if(isset($data['specs'])){
			    $msg.='<br>';
			    $msg.= $data['specs'];
            }

			$arr = array();


			$arr[] = base64ToImage($data['screen'], $filename_prefix . '_coupe_project.jpg');
			$arr[] = savedataToFile( $data['save_data'], $filename_prefix . '_coupe_project.dbs');

			if(isset ($_POST['pdf_file']) ){
				$arr[] = base64ToPdf($_POST['pdf_file'], $filename_prefix . '_coupe_project.pdf');
			}

            if(isset ($_POST['screen_cabinet']) ){
                $arr[] = base64ToImage($data['screen_cabinet'], $filename_prefix . '_cabinet_coupe_project.jpg');
            }

            if(isset ($_POST['screen_doors']) ){
                $arr[] = base64ToImage($data['screen_doors'], $filename_prefix . '_doors_coupe_project.jpg');
            }

			$subject = "Заявка с конструктора шкафов-купе с сайта " . $true_url['host'];
			send_mail($to, $subject, $msg, $from, $arr);


			$msg1 = '<strong>Проект с конструктора шкафов-купе:</strong><br>';
			$msg1 .= 'Имя: ' . $data['name'] . '<br>';
			$msg1 .= 'Email: ' . $data['email'] . '<br>';
			if ( $data['phone'] ) {
				$msg1 .= 'Телефон: ' . $data['phone'] . '<br>';
			}
			if ( $data['comments'] ) {
				$msg1 .= 'Комментарии: ' . $data['comments'] . '<br>';
			}

			if ( $data['form_mats'] ) {
				$msg1 .= $data['form_mats'];
			}

			if(isset($arr['pdf_file'])) unset($arr['pdf_file']);


			if($this->config->item('username') != 'a.ergashev@skm-mebel.ru') {
                send_mail($data['email'], 'Проект шкафа купе с сайта ' . $true_url['host'], $msg1, $from, $arr);
            }


			if(file_exists($filename_prefix . '_coupe_project.jpg')) unlink($filename_prefix . '_coupe_project.jpg');
			if(file_exists($filename_prefix . '_coupe_project.dbs')) unlink($filename_prefix . '_coupe_project.dbs');
			if(file_exists($filename_prefix . '_cabinet_coupe_project.jpg')) unlink($filename_prefix . '_cabinet_coupe_project.jpg');
			if(file_exists($filename_prefix . '_doors_coupe_project.jpg')) unlink($filename_prefix . '_doors_coupe_project.jpg');
            if(file_exists($filename_prefix .'_coupe_project.pdf'))unlink($filename_prefix .'_coupe_project.pdf');


			if($this->config->item('username') == 'marketing.mebli@gmail.com'){
				$url = 'https://fayni-mebli.com/bpwardrobe.php';

				$options = array(
					'http' => array(
						'header'  => "Content-type: application/x-www-form-urlencoded\r\n",
						'method'  => 'POST',
						'content' => http_build_query($data)
					)
				);
				$context  = stream_context_create($options);
				$result = file_get_contents($url, false, $context);

				echo($result);
			}


		}



	}

    public function repair()
    {
        $this->load->model('clients_orders_model');
        $this->clients_orders_model->repair();
	}

}