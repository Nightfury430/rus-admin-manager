<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Params_blocks extends CI_Controller {

    public function __construct()
    {
        parent::__construct();
        $this->load->helper('url_helper');
        $this->load->model('common_model');
        $this->load->library('session');
        $this->load->model('Menu_model');

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
        $data['controller_name'] = 'params_blocks';

        $data['js_include'] = [
        ];

        $data['css_include'] = [
        ];

        $data['include'] = 'params_blocks/item';
        $data['modules'] = [];
        $data['menus_list'] = $this->Menu_model->get_all_menus();
        $this->load->view('templates/layout', $data);
    }

    public function item_add($id = false)
    {
        if (!isset($_POST)) exit;

        if ($id) {
            $item_id = $id;
        } else {
            $item_id = $this->common_model->add_data( 'Params_blocks_items', array('name'=>''));
        }

        $data = json_decode($_POST['data'],true);
        $data['id'] = $item_id;
        $data['data'] = json_encode($data['data']);
        $data['description'] = 'asd';



        $this->common_model->update_where(
            'Params_blocks_items',
            'id',
            $item_id,
            $data
        );

        echo json_encode('success');

    }

}