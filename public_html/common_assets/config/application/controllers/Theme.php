<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Theme extends CI_Controller {

	public function __construct()
	{
		parent::__construct();
		$this->load->helper('url_helper');
		$this->load->library('session');
		$this->load->helper('file');
		$this->load->model('theme_model');

		if(!$this->session->username || $this->session->username != $this->config->item('username')){
			redirect('login', 'refresh');
		}
	}

	public function index()
	{
		$data = array();
		$data['theme'] = $this->theme_model->get();

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
		$this->load->view('/theme', $data);
		$this->load->view('templates/footer', $data);

	}

	public function update()
	{
		if(isset($_POST)){


			if($_POST['active'] == 1){

				$css = '';

				$css.= "#top_panel{background:". $_POST['theme_main_background'] ."; border-color: ". $_POST['theme_border_color'] ."; color: ". $_POST['theme_main_text_color'] .";}";
				$css.= "#left_panel{background:". $_POST['theme_main_background'] ."; border-color: ". $_POST['theme_border_color'] ."; color: ". $_POST['theme_main_text_color'] .";}";
				$css.= "#state_panel{background:". $_POST['theme_main_background'] ."; color: ". $_POST['theme_main_text_color'] .";}";
				$css.= ".accordion_heading{background:". $_POST['theme_main_background'] ."; border-color: ". $_POST['theme_border_color'] ."; color: ". $_POST['theme_main_text_color'] .";}";
				$css.= ".accordion_block{background:". $_POST['theme_acc_body_color'] ."; border-color: ". $_POST['theme_border_color'] ."; color: ". $_POST['theme_main_text_color'] .";}";
				$css.= ".left_subpanel .mask{background:". $_POST['theme_main_background'] ."; border-color: ". $_POST['theme_border_color'] ."; color: ". $_POST['theme_main_text_color'] .";}";
				$css.= ".left_subpanel .left_subpanel_subpanel{background:". $_POST['theme_main_background'] ."; border-color: ". $_POST['theme_border_color'] ."; color: ". $_POST['theme_main_text_color'] .";}";
				$css.= ".slide_button.slide_right .top_submenu{background:". $_POST['theme_main_background'] ."; border-color: ". $_POST['theme_border_color'] ."; color: ". $_POST['theme_main_text_color'] .";}";
				$css.= "#materials_panel{background:". $_POST['theme_main_background'] ."; border-color: ". $_POST['theme_border_color'] ."; color: ". $_POST['theme_main_text_color'] .";}";
				$css.= "#materials_panel_button{background:". $_POST['theme_main_background'] ."; border-color: ". $_POST['theme_border_color'] ."; color: ". $_POST['theme_main_text_color'] .";}";
				$css.= ".top_panel_group{border-color: ". $_POST['theme_border_color'] ."; color: ". $_POST['theme_main_text_color'] .";}";
				$css.= ".acc_block{border-color: ". $_POST['theme_border_color'] ."; color: ". $_POST['theme_main_text_color'] .";}";
				$css.= ".materials_panel_body > div{border-color: ". $_POST['theme_border_color'] ."; color: ". $_POST['theme_main_text_color'] .";}";
				$css.= ".submenu_button{border-color: ". $_POST['theme_border_color'] .";}";
				$css.= "#selected_kitchen{color: ". $_POST['theme_main_text_color'] .";}";
				$css.= ".submenu_button.clicked{background: ". $_POST['theme_send_order_color'] .";}";
				$css.= "#show_order_modal{background: ". $_POST['theme_send_order_color'] ."; color: ". $_POST['theme_send_order_text_color'] ."}";
				$css.= "p.exit_facades_system{background: ". $_POST['theme_send_order_color'] .";}";
				$css.= ".slide_button.slide_right.clicked{border-color: ". $_POST['theme_send_order_color'] .";}";
				$css.= "#show_order_modal:hover, #show_order_modal:focus{background: ". $_POST['theme_send_order_hover_color'] ."; color: ". $_POST['theme_send_order_hover_text_color'] .";}";
				$css.= ".left_subpanel .panel_close{color: ". $_POST['theme_main_text_color'] .";}";
				$css.= ".material_subcategory_heading{color: ". $_POST['theme_main_text_color'] .";}";
				$css.= ".k_sum{color: ". $_POST['theme_main_text_color'] .";}";
				$css.= "#camera_projection_top i{background: ". $_POST['theme_main_text_color'] .";}";
				$css.= "#camera_projection_front i{border-color: ". $_POST['theme_main_text_color'] .";}";
				$css.= "#camera_projection_back i{border-color: ". $_POST['theme_main_text_color'] .";}";
				$css.= "#camera_projection_left i{border-color: ". $_POST['theme_main_text_color'] .";}";
				$css.= "#camera_projection_right i{border-color: ". $_POST['theme_main_text_color'] .";}";
				$css.= ".mat_wrapper.selected{border-color: ". $_POST['theme_selected_material_color'] .";}";
				$css.= ".facade_model.selected{border-color: ". $_POST['theme_selected_material_color'] .";}";
				$css.= "#selected_kitchen{background: ". $_POST['theme_main_background'] .";}";
//				$css.= "#info_panel{background:". $_POST['theme_main_background'] ."; color: ". $_POST['theme_main_text_color'] .";}";




				$css.= ".btn-default{background: ". $_POST['theme_buttons_background'] ."; color: ". $_POST['theme_buttons_text_color'] .";}";
				$css.= ".btn-default:hover{background: ". $_POST['theme_buttons_hover_background'] ."; color: ". $_POST['theme_buttons_hover_text_color'] .";}";
				$css.= ".btn-default.active, .btn-default:active{background: ". $_POST['theme_buttons_hover_background'] ."; color: ". $_POST['theme_buttons_hover_text_color'] .";}";
				$css.= ".btn-default:focus, .btn-default:hover{background: ". $_POST['theme_buttons_hover_background'] ."; color: ". $_POST['theme_buttons_hover_text_color'] .";}";
				$css.= ".btn-default.active.focus, .btn-default.active:focus, .btn-default.active:hover, .btn-default:active.focus, .btn-default:active:focus, .btn-default:active:hover, .open>.dropdown-toggle.btn-default.focus, .open>.dropdown-toggle.btn-default:focus, .open>.dropdown-toggle.btn-default:hover{background: ". $_POST['theme_buttons_hover_background'] ."; color: ". $_POST['theme_buttons_hover_text_color'] .";}";

				$css.= ".theme_main_background_color{background: ". $_POST['theme_main_background'] ."!important;}";
				$css.= ".theme_main_text_color{color: ". $_POST['theme_main_text_color'] ."!important;}";

				$css.= ".theme_main_border_color{border-color: ". $_POST['theme_border_color'] ."!important;}";
				$css.= ".theme_main_acc_body_color{background-color: ". $_POST['theme_acc_body_color'] ."!important;}";
				$css.= "#info_panel .nav-tabs>li.active>a, #info_panel .nav-tabs>li.active>a:focus, #info_panel .nav-tabs>li.active>a:hover{background-color: ". $_POST['theme_acc_body_color'] ."!important;}";
				$css.= "#info_panel .nav>li>a:focus, #info_panel .nav>li>a:hover {background-color: ". $_POST['theme_acc_body_color'] ."!important;}";

				$css.= ".theme_send_order{background: ". $_POST['theme_send_order_color'] ."; color: ". $_POST['theme_send_order_text_color'] ."}";
				$css.= ".theme_send_order:hover, .theme_send_order:focus{background: ". $_POST['theme_send_order_hover_color'] ."; color: ". $_POST['theme_send_order_hover_text_color'] .";}";


				write_file( dirname(FCPATH) .'/assets/theme.css', $css);



			} else {
				write_file( dirname(FCPATH) .'/assets/theme.css', '');
			}

			$this->theme_model->update($_POST);


		}
	}

}
