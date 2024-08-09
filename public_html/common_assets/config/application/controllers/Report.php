<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Report extends CI_Controller {

	public function __construct()
	{
		parent::__construct();
		$this->load->helper('url_helper');
		$this->load->model('common_model');
		$this->load->library('session');
		$this->load->model('Menu_model');
	}

	public function index()
	{
		if(!$this->session->username || $this->session->username != $this->config->item('username')){
			redirect('login', 'refresh');
		}


		$this->load->model('common_model');

		$data['settings'] = $this->common_model->get_row('Report');
		$data['email_settings'] = $this->common_model->get_row('Email');

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


		$data['js_include'] = [
		];
		$data['css_include'] = [
		];
		$data['include'] = 'report';
		$data['modules'] = [];
		$data['menus_list'] = $this->Menu_model->get_all_menus();
		$this->load->view('templates/layout', $data);
	}

	public function update_report_settings() {
		if($this->input->post()){

			$this->common_model->update_row('Report', $this->input->post('data'));
			echo json_encode(['success' => 'true']);

		}
	}

	public function pdf() {



//		print_r($_POST);
//		exit;
		if(isset($_POST['body'])){
			require_once $_SERVER['DOCUMENT_ROOT'] . '/vendor/autoload.php';

			$settings = json_decode($_POST['settings'], true);

			$mpdf = new \Mpdf\Mpdf([
				'mode' => 'utf-8',
				'format' => 'A4',
				'orientation' => $settings['report_page_orientation'],
				'margin_top'=> $settings['report_margin_top'],
				'margin_left' => $settings['report_margin_left'],
				'margin_right' => $settings['report_margin_right'],
				'margin_bottom' => $settings['report_margin_bottom'],
				'margin_header' => $settings['report_margin_header'],
				'margin_footer' => $settings['report_margin_footer'],
			]);
			$mpdf->autoLangToFont = true;
//			$mpdf->setAutoTopMargin = 'stretch';



			if(!empty($_POST['header'])){
				$mpdf->SetHTMLHeader($_POST['header']);
			}

			if ( $settings['custom_report_show_logo'] ) {
				$this->load->model('settings_model');
				$c_settings = $this->settings_model->get_settings();
				if ( ! empty( $c_settings['logo'] ) ) {

					$mpdf->SetWatermarkImage(
						$c_settings['logo'],
						0.5,
						'',
						"TR"
					);

					$mpdf->showWatermarkImage = true;
				}
			}


			if(!empty($_POST['footer'])){
				$mpdf->SetHTMLFooter($_POST['footer']);
			}
//			$mpdf->SetHTMLHeader('<div style="text-align: right"><img style="opacity: 0.2" src="/common_assets/images/bplanner_logo.png"></div>');
//			$mpdf->SetHTMLFooter('<div dir="rtl">www.modulr.co.il | info@modulr.co.il | 0733-744744 | מטבחים מודולר<br>הופק באמצעות ״מודולית״ אונליין דיזיינר כל הזכויות שמורות  <br>לחברת גלובל אדג׳ סורסינג בע״מ</div>');



//			$mpdf->SetWatermarkImage(
//				$_SERVER['DOCUMENT_ROOT'] . '/common_assets/images/bplanner_logo.png',
//				0.5,
//				'',
//				array(160,10)
//			);
//			$mpdf->showWatermarkImage = true;

//		$mpdf->SetDirectionality('rtl');
			$mpdf->autoScriptToLang = true;
//		$mpdf->baseScript = 1;
			$mpdf->WriteHTML($_POST['body']);
//		$mpdf->Output();
//			$mpdf->Output($_SERVER['DOCUMENT_ROOT'] . '/filename.pdf', \Mpdf\Output\Destination::FILE);
			echo base64_encode($mpdf->Output('test.pdf', \Mpdf\Output\Destination::STRING_RETURN));

//		$mem_usage = memory_get_usage();
//		$mem_peak = memory_get_peak_usage();
//		echo 'The script is now using: <strong>' . round($mem_usage / 1024 / 1024) . 'Mb</strong> of memory.<br>';
//		echo 'Peak usage: <strong>' . round($mem_peak / 1024 / 1024) . 'Mb</strong> of memory.<br><br>';
		}


	}

	public function replace_template() {
//		$mpdf = new \Mpdf\Mpdf( [
//			'mode'          => 'utf-8',
//			'format'        => 'A4',
//			'orientation'   => 'L',
//			//				'autoMarginPadding' => 0,
//			//				'bleedMargin' => 0,
//			//				'crossMarkMargin' => 0,
//			//				'cropMarkMargin' => 0,
//			//				'cropMarkLength' => 0,
//			//				'nonPrintMargin' => 0,
//			//				'margBuffer' => 0,
//			'margin_top'    => 5,
//			'margin_left'   => 5,
//			'margin_right'  => 5,
//			'margin_bottom' => 40,
//			'margin_header' => 0,
//			'margin_footer' => 10,
//		] );
//
//		$mpdf->autoLangToFont = true;
//		$mpdf->autoScriptToLang = true;

		$template_data = file_get_contents($_SERVER['DOCUMENT_ROOT'] . '/pdf_template.html');

//		$dom = new DOMDocument();
////		@$dom->loadHTML($template_data);
//		@$dom->loadHTML(mb_convert_encoding($template_data, 'HTML-ENTITIES', 'UTF-8'));
//
//		$tags = $dom->getElementsByTagName('facades_data');
//
//		foreach ($tags as $tag){
////			print_pre($tag);
//			$tables = $tag->getElementsByTagName('table');
//
//			foreach ($tables as $table){
//				print_pre($table->nodeValue);
//			}
//
//		}
//
//		echo $dom->saveHTML();
//
//		exit;

		preg_match_all('/<facades_data>(.*?)<\/facades_data>/s', $template_data, $matches);
//		echo htmlspecialchars($match[1]);

		$result = $template_data;


		$tmp = array();
		$tmp['1'] = array();
		$tmp['1']['c'] = 'Категория 1';
		$tmp['1']['n'] = 'Название 1';
		$tmp['2'] = array();
		$tmp['2']['c'] = 'Категория 2';
		$tmp['2']['n'] = 'Название 2';



		foreach ($matches[1] as $key=>$match){
			$new_data = '';

//			print_pre($match);
//			echo '---';

			if(preg_match_all("#<\s*?table\b[^>]*>(.*?)</table\b[^>]*>#s", $match, $table_matches)){


				foreach ($table_matches[0] as $table_match){
					$table_tag = preg_match('/<table (.*?)>/s', $table_match,$tmp_res );
					print_pre($table_match);
					print_pre($tmp_res[0]);



				}

//				print_pre($table_match);
//				echo '<br>';
//				print_pre($table_match[0][0]);
//				echo '<br>';
//
//				echo '<br>';
			} else {
				foreach ($tmp as $item){
					$rep_data = $match;



					$rep_data = str_replace('{{facade_set_category}}', $item['c'] , $rep_data);
					$rep_data = str_replace('{{facade_set_name}}', $item['n'] , $rep_data);


					$new_data .= $rep_data;
				}
			}




//				print_pre($rep_data);

			$result = str_replace($matches[0][$key], $new_data, $result);


		}





		echo ($result);

	}

	public function get_pdf() {
		if (!isset($_POST)) exit;

		require_once $_SERVER['DOCUMENT_ROOT'] . '/vendor/autoload.php';
		try {

			$this->load->model('common_model');

			$report_settings = $this->common_model->get_row('Report');
			$email_settings = $this->common_model->get_row('Email');

			$report_custom_settings = json_decode($report_settings['report_custom_settings'],true);


			$mpdf = new \Mpdf\Mpdf( [
				'mode' => 'utf-8',
				'format' => 'A4',
				'orientation' => $report_custom_settings['report_page_orientation'],
				'margin_top'=> $report_custom_settings['report_margin_top'],
				'margin_left' => $report_custom_settings['report_margin_left'],
				'margin_right' => $report_custom_settings['report_margin_right'],
				'margin_bottom' => $report_custom_settings['report_margin_bottom'],
				'margin_header' => $report_custom_settings['report_margin_header'],
				'margin_footer' => $report_custom_settings['report_margin_footer'],
			] );

			$mpdf->autoLangToFont = true;
			$mpdf->autoScriptToLang = true;

			if ( $report_custom_settings['custom_report_show_logo'] ) {
				$this->load->model('settings_model');
				$c_settings = $this->settings_model->get_settings();
				if ( ! empty( $c_settings['logo'] ) ) {

					$mpdf->SetWatermarkImage(
						$c_settings['logo'],
						0.5,
						'',
						"TR"
					);

					$mpdf->showWatermarkImage = true;
				}
			}

			$stylesheet = 'html,body{width: 100%}';
			$html = '';
			$images = json_decode($_POST['images'], true);
			$data = json_decode($_POST['data'], true);
			$order_data = json_decode($_POST['order_data'], true);



			if(!empty($report_settings['report_template_header'])) $mpdf->SetHTMLHeader($report_settings['report_template_header']);
			if(!empty($report_settings['report_template_footer'])) $mpdf->SetHTMLFooter($report_settings['report_template_footer']);

//			print_r($images);
//			print_r($email_settings);

			foreach ($images as $key=>$image){
				$html .= '<div style="width: 100%; text-align: center"><img style="width: 100%; margin: 0 auto" src="'. $image .'"></div>';
			}


			if(!empty($report_settings['report_template_body'])){

				$body = $report_settings['report_template_body'];

				if($email_settings['use_custom_form'] == 1){
					$cf_data = json_decode($email_settings['custom_form_data'], true);
				} else {
					$cf_data = array();
					$cf_data['name'] = array('id' => 'name');
					$cf_data['email'] = array('id' => 'email');
					$cf_data['phone'] = array('id' => 'phone');
					$cf_data['comments'] = array('id' => 'comments');
				}

				foreach ($cf_data as $cf_item){

					if($cf_item['type'] == 'checkbox'){
						if(isset($order_data[$cf_item['id']])){
							$body = str_replace('{{client_'. $cf_item['id'] .'}}', '&#10003;', $body);
						} else {
							$body = str_replace('{{client_'. $cf_item['id'] .'}}', '&#10005;', $body);
						}
					} else {
						if(isset($order_data[$cf_item['id']])){
							$body = str_replace('{{client_'. $cf_item['id'] .'}}', $order_data[$cf_item['id']], $body);
						}
					}


				}

				if(!empty($_POST['custom_userfields'])){
					$custom_userfields = json_decode($_POST['custom_userfields'], true);

					foreach ($custom_userfields as $field){
						$body = str_replace('{{'. $field['tag'] .'}}', $field['data'], $body);
					}

				}

				$html .= $body;
			}



			$mpdf->WriteHTML($stylesheet,\Mpdf\HTMLParserMode::HEADER_CSS);
			$mpdf->WriteHTML($html);

			file_put_contents($_SERVER['DOCUMENT_ROOT'] . '/pdf.html', $html);

			echo base64_encode($mpdf->Output('test.pdf', \Mpdf\Output\Destination::STRING_RETURN));

		} catch ( \Mpdf\MpdfException $e ) {
		}



	}

	public function tst(){
		$data = array();
		$data['ads'] =123;
		$data['bsd'] = 12345;
		$data['sss'] = 444;

		unset($data['bsd']);

		print_pre($data);
	}

}


