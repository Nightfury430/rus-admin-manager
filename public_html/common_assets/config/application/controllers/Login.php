<?php
defined('BASEPATH') OR exit('No direct script access allowed');



class Login extends CI_Controller {

    public function __construct()
    {
        parent::__construct();
        $this->load->helper('url_helper');
        $this->load->library('session');
		$this->load->model('User_model');
    }

    public function index($login_message = false)
    {
        
    }

	public function generate_pwd() {

		$userdata = explode(';', file_get_contents (dirname(FCPATH) . '/config/data/usr.txt'));

		$pwd = password_generate(12);

		$userdata[1] = crypt($pwd, 'JL68XT@!9%OUN#_ZG:R!2PhxuOFUDKrQPtK"!b~u+um@$4^KE:@AR=8_S;lfd#U');

		file_put_contents(dirname(FCPATH) . '/config/data/usr.txt', $userdata[0].';'.$userdata[1]);
		file_put_contents(dirname(FCPATH) . '/pwdrst', $pwd);

		if($this->input->post('email_type') == 1){
			$email = $userdata[0];
	    } else {
			$this->load->model('settings_model');
			$email = $this->settings_model->get_settings()['order_mail'];
		}

        if (strpos($userdata[0], 'mfleko') !== false) {
            $email = 'leko-sale@yandex.ru';
        }

		$this->send_password_mail($email, $pwd);


		if(file_exists(dirname(FCPATH) . '/pwdrst')){
			unlink(dirname(FCPATH) . '/pwdrst');
		}


		redirect('login/index/' . $this->input->post('email_type'), 'refresh');
    }

    public function logout()
    {
        session_destroy();
        redirect('login', 'refresh');
    }

	public function change_constructor() {
		if(!$this->input->post()) exit;


		$_SESSION['constructor'] = $this->input->post('constructor');



		if($_SESSION['constructor'] == 'kitchen'){
			echo site_url('settings/');
		}
		if($_SESSION['constructor'] == 'coupe'){
			echo site_url('settings/coupe_account_settings_index/');
		}


    }

    public function ext_login()
    {


        if(
            $this->config->item('username') == $this->input->post('login') &&
            crypt($this->input->post('pass'), 'JL68XT@!9%OUN#_ZG:R!2PhxuOFUDKrQPtK"!b~u+um@$4^KE:@AR=8_S;lfd#U') == $this->config->item('password')
        ) {


            $this->session->set_userdata([
                'username' => $this->config->item('username'),
                'constructor' => 'kitchen',
            ]);




            file_put_contents($_SERVER['DOCUMENT_ROOT'] . '/logs/' . $this->config->item('username') . '_login2.txt', print_r($_SESSION, true));

            $data = [
                'url' => base_url('index.php/settings'),
                'sid'=> $this->session->session_id,
                'exp'=> 'Expires='.gmdate('D, d-M-Y H:i:s T', time() + 7200)
            ];




            return $this->output
                ->set_content_type('application/json')
                ->set_output(json_encode($data));

//            echo json_encode($data);
//            redirect('updater');
        }

        exit;




    }

    private function send_password_mail($to, $data){
        $msg = '';
        $from = $this->config->item('sv_email_support_from');

        $msg.= 'Здравствуйте<br>';
        $msg.='<br>';
        $msg.= 'Ваш пароль от личного кабинета сброшен<br>';
        $msg.='<br>';
        $msg.= 'Новый пароль: '. $data .'<br>';



        send_mail($to, 'PlanPlace - пароль от аккаунта', $msg, $from, array());
    }

}

function password_generate($chars)
{
	$data = '1234567890ABCDEFGHIJKLMNOPQRSTUVWXYZabcefghijklmnopqrstuvwxyz';
	return substr(str_shuffle($data), 0, $chars);
}

