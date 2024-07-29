<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Catalogue extends CI_Controller {

    public function __construct()
    {
        parent::__construct();
        $this->load->helper('url_helper');
        $this->load->model('common_model');
        $this->load->library('session');

        if(!$this->session->username || $this->session->username != $this->config->item('username')){
            redirect('login', 'refresh');
        }

        if(!$this->config->item('sub_account')) $this->config->set_item('sub_account', false);
        if($this->config->item('sub_account') == true) redirect('settings', 'refresh');
    }

    public function item($id = false)
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

        if($id) $data['id'] = $id;
        $data['controller_name'] = 'catalogue';
        $this->load->view('templates/header', $data);
        $this->load->view('catalogue/item', $data);
        $this->load->view('templates/footer', $data);
    }

    public function item_add($id = false)
    {
        if(!isset($_POST)) die();

        $table_name = 'Catalogue_items';
        if($id){
            $item_id = $id;
        } else {
            $item_id = $this->common_model->add_data( $table_name, array('category'=>''));
        }

        $input = json_decode($_POST['data'],true);

        $data = array();
        $input['id'] = $item_id;
        $data['name'] = $input['name'];
        $data['icon'] = $input['icon'];
        $data['active'] = $input['active'];
        if(isset($input['order'])){
            $data['order'] = (int)$input['order'];
        }
        $data['category'] = json_encode($input['category']);
        $data['data'] = json_encode($input['params']);


        $this->common_model->update_where(
            $table_name,
            'id',
            $item_id,
            $data
        );

        echo json_encode('ok');


    }

}




