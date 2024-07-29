<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Prices extends CI_Controller {

    public function __construct()
    {
        parent::__construct();
        $this->load->helper('url_helper');
        $this->load->model('project_settings_model');
        $this->load->model('facades_model');
        $this->load->model('materials_model');
        $this->load->model('glass_model');
        $this->load->model('handles_model');
        $this->load->model('prices_model');
        $this->load->library('session');

        if(!$this->session->username || $this->session->username != $this->config->item('username')){
            redirect('login', 'refresh');
        }

        if(!$this->config->item('sub_account')) $this->config->set_item('sub_account', false);
        if($this->config->item('sub_account') == true) redirect('settings', 'refresh');
    }

    public function index()
    {

//        $data['facades'] = $this->facades_model->get_all_items();
//        $data['materials'] = $this->materials_model->get_active_categories();
//        $data['handles'] = $this->handles_model->get_all_active_items();
        $data['settings'] = $this->project_settings_model->get_settings();

        if(isset($data['settings']['settings'])){
            $data['settings'] = json_decode($data['settings']['settings'], true);
        } else {
            $data['settings']['available_materials_glass'] = json_decode($data['settings']['available_materials_glass'], true);
            $data['settings']['available_materials_cokol'] = json_decode($data['settings']['available_materials_cokol'], true);
            $data['settings']['available_materials_corpus'] = json_decode($data['settings']['available_materials_corpus'], true);
            $data['settings']['available_materials_tabletop'] = json_decode($data['settings']['available_materials_tabletop'], true);
            $data['settings']['available_materials_wallpanel'] = json_decode($data['settings']['available_materials_wallpanel'], true);
            $data['settings']['available_materials_back_wall'] = json_decode($data['settings']['available_materials_back_wall'], true);
        }




        $data['mat_cats'] = $this->materials_model->get_categories();
        $data['glass_cats'] = $this->glass_model->get_categories();


        $data['glass_sel'] = $data['settings']['available_materials_glass'];
        $data['cokol_sel'] = $data['settings']['available_materials_cokol'];
        $data['corpus_sel'] = $data['settings']['available_materials_corpus'];
        $data['tabletop_sel'] = $data['settings']['available_materials_tabletop'];
        $data['wallpanel_sel'] = $data['settings']['available_materials_wallpanel'];
        $data['back_wall_sel'] = $data['settings']['available_materials_back_wall'];
        if(isset($data['settings']['available_materials_glass_shelves'])){
            $data['glass_shelves_sel'] = $data['settings']['available_materials_glass_shelves'];
        } else {
            $data['glass_shelves_sel'] = json_encode(new stdClass());
        }



        $data['materials_categories'] = buildTree($this->materials_model->get_categories(), 'parent', 'id');
        $data['glass_materials_categories'] = buildTree($this->glass_model->get_categories(), 'parent', 'id');




//        $available_materials_glass = $data['settings']['available_materials_glass'];


//        if($available_materials_glass){
//            $glass_tree = array();
//
//            if(is_string($available_materials_glass)) $available_materials_glass = json_decode($available_materials_glass, true);
//
//            foreach ($available_materials_glass as $av){
//                $glass_tree[] = get_cat_by_id($data['glass_materials_categories'], $av);
//            }
//        } else {
//            $glass_tree = array();
//        }
//
//
//        $available_materials_cokol = $data['settings']['available_materials_cokol'];
//        if(is_string($available_materials_cokol)) $available_materials_cokol = json_decode($available_materials_cokol, true);
//        $cokol_tree = array();
//        foreach ($available_materials_cokol as $av){
//            $cokol_tree[] = get_cat_by_id($data['materials_categories'], $av);
//        }
//
//        $available_materials_corpus = $data['settings']['available_materials_corpus'];
//        if(is_string($available_materials_corpus)) $available_materials_corpus = json_decode($available_materials_corpus, true);
//        $corpus_tree = array();
//        foreach ($available_materials_corpus as $av){
//            $corpus_tree[] = get_cat_by_id($data['materials_categories'], $av);
//        }
//
//        $available_materials_tabletop = $data['settings']['available_materials_tabletop'];
//        if(is_string($available_materials_tabletop)) $available_materials_tabletop = json_decode($available_materials_tabletop, true);
//        $tabletop_tree = array();
//        foreach ($available_materials_tabletop as $av){
//            $tabletop_tree[] = get_cat_by_id($data['materials_categories'], $av);
//        }
//
//        $available_materials_wallpanel = $data['settings']['available_materials_wallpanel'];
//        if(is_string($available_materials_wallpanel)) $available_materials_wallpanel = json_decode($available_materials_wallpanel, true);
//        $wallpanel_tree = array();
//        foreach ($available_materials_wallpanel as $av){
//            $wallpanel_tree[] = get_cat_by_id($data['materials_categories'], $av);
//        }



//        $data['glass_tree'] = $glass_tree;
//        $data['cokol_tree'] = $cokol_tree;
//        $data['corpus_tree'] = $corpus_tree;
//        $data['tabletop_tree'] = $tabletop_tree;
//        $data['wallpanel_tree'] = $wallpanel_tree;


        $data['prices'] = json_decode($this->prices_model->get()['data']);
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
        $this->load->view('prices/index', $data);
        $this->load->view('templates/footer');

    }

    public function get_data()
    {
        $data = [];
        $data['settings'] = $this->project_settings_model->get_settings();
    }

    public function save_data()
    {
        if(isset($_POST)){

            $data = array();
            $data['data'] = $_POST['data'];

            $this->prices_model->update($data);

        }
    }


    public function coupe(){

    }

    public function builtin()
    {
        $this->load->model('common_model');
    }

    public function temp()
    {
        $this->load->model('prices_model');
        $data = $this->prices_model->get();



        echo json_encode($data);
    }

    public function temp_fac()
    {
        $this->load->model('facades_model');
        $data = $this->facades_model->get_all_items();

        echo json_encode($data);
    }

}



function get_cat_by_id($array, $id){
    for ( $i=0; $i<count($array); $i++ ){
        if($array[$i]['id'] == $id){
            return $array[$i];
        }
    }
}