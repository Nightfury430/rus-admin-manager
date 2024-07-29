<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Tabletop_plints extends CI_Controller {

    private $table_cats_name = 'Tabletop_plints_categories';
    private $table_items_name = 'Tabletop_plints_items';

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

    public function categories_index()
    {
        $data['categories'] = $this->common_model->get_data_all_by_order($this->table_cats_name, 'ASC');

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

        $data['controller'] = 'tabletop_plints';


        $this->load->view('templates/header', $data);
        $this->load->view('common/categories_index_new', $data);
        $this->load->view('templates/footer', $data);
    }

    public function categories_order_update()
    {
        if($this->input->post()) $this->common_model->update_batch($this->table_cats_name, json_decode($this->input->post('data'), true), 'id');
    }

    public function categories_get()
    {
        echo json_encode($this->common_model->get_data_all_by_order($this->table_cats_name, 'ASC'));
    }

    public function category_add_ajax()
    {
        if($this->input->post()){
            echo $this->common_model->add_data( $this->table_cats_name,
                array(
                    "name" =>  no_xss($this->input->post('name')),
                    "parent" =>  (int)$this->input->post('parent'),
                    "active" =>  (int)$this->input->post('active'),
                    "order" =>  (int)$this->input->post('order')
                )
            );
        }
    }

    public function category_edit_ajax()
    {
        if($this->input->post()){
            echo $this->facades_model->update_where(
                $this->table_cats_name,
                'id',
                (int)$this->input->post('id'),
                array(
                    "name" =>  no_xss($this->input->post('name')),
                    "description" =>  no_xss($this->input->post('description'))
                )
            );
        }
    }

    public function category_delete_ajax()
    {
        if($this->input->post()) $this->common_model->remove_where($this->table_cats_name, 'id', (int)$this->input->post('id'));
    }

    public function categories_set_active_ajax($id, $val) {
        $this->common_model->set_category_active($this->table_cats_name, $id, $val);
    }




    public function items_index()
    {

        $data['items'] = $this->common_model->get_data_all_by_order($this->table_items_name,'ASC');
        $data['categories'] = $this->common_model->get_data_all_by_order($this->table_cats_name, 'ASC');
        $data['controller'] = 'tabletop_plints';


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
        $this->load->view('tabletop_plints/items_index', $data);
        $this->load->view('templates/footer', $data);
    }

    public function items_get()
    {
        echo json_encode($this->common_model->get_data_all_by_order($this->table_items_name, 'ASC'));
    }

    public function item()
    {
        if ($this->input->post()) {
            $data = $this->input->post();
            $item_id = 0;
            $item_data = null;
            $update_data = null;

            if($data['id'] != 0){
                $item_data = $this->common_model->get_where($this->table_items_name, array( 'id' => $data['id']));
                if(!empty($item_data)) $item_id = $data['id'];
            } else {
                $item_id = $this->common_model->add_data($this->table_items_name, array( 'name' => '' ));
//                $item_id = 0;
            }

            if(isset($_FILES)){
                $uploaded_files = array();

                $save_dir = dirname(FCPATH) . '/models/tabletop_plints/';
                if (!is_dir($save_dir)) mkdir($save_dir);
                $save_path = $save_dir . $item_id;
                if (!is_dir($save_path)) mkdir($save_path);
                $abs_save_path = 'models/tabletop_plints/' . $item_id;

                foreach ($_FILES as $key => $file) {
                    $new_file = $save_path . '/' . strtolower($file['name']);
                    if (move_uploaded_file($file['tmp_name'], $new_file)) {
                        $uploaded_files[$key] = $abs_save_path . '/' . strtolower($file['name']);
                    } else {
                        $errors[] = 'Ошибка при загрузке файла ' . $file['name'] . '\\n';
                    }
                }

                foreach ($uploaded_files as $key=>$val) {
                    if($key == 'model'){
                        $params = json_decode($data['params'], true);
                        $params['model'] = $val;
                        $data['params'] = json_encode($params);
                    } else {
                        $data[$key] = $val;
                    }

                }

                $files = array_diff(scandir($save_path), array('.', '..'));

                foreach ($files as $file){
                    $remove_flag = 1;
                    foreach ($uploaded_files as $up_file){
                        if(basename($up_file) == $file) $remove_flag = 0;
                    }
                    if($remove_flag == 1){
                        unlink($save_path . '/' . $file);
                    }
                }

            }


            if($item_data){
                foreach ($item_data as $key=>$val){
                    $item_data[$key] = $data[$key];
                }
                $update_data = $item_data;
            } else {
                $update_data = $data;
            }

            if($update_data['order'] == 0) $update_data['order'] = $item_id * 100;

            unset($update_data['id']);




            $nid = $this->common_model->update_where($this->table_items_name, 'id', $item_id, $update_data);
            $update_data['id'] = $nid;
            echo json_encode($update_data);

        }
    }












    public function items_remove()
    {
        if($this->input->post()){
            removeDirectory( dirname(FCPATH) . '/models/tabletop_plints/' . (int)$this->input->post('id'));
            $this->common_model->remove_where($this->table_items_name, 'id', (int)$this->input->post('id'));
        }
    }

    public function items_catalog_index($category = false, $per_page = false, $start = false)
    {
        $data['items'] = $this->facades_model->get_catalog_items();
        $data['categories'] = $this->facades_model->get_catalog_categories();

//		$data['acc_categories'] = $this->facades_model->get_categories();
        $data['acc_categories'] = buildTree($this->facades_model->get_categories(), 'parent', 'id');

        $this->load->model('materials_model');
        $data['materials'] = $this->materials_model->get_categories();
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
        $this->load->view('facades/items/catalog', $data);
        $this->load->view('templates/footer', $data);
    }

    public function add_item_from_catalog($id, $category)
    {
        $cat_item = $this->facades_model->get_catalog_item($id);

        $data = array();


        if($cat_item['data']){

            $data['name'] = $cat_item['name'];
            $data['icon'] = $cat_item['icon'];
            $data['category'] = $category;
            $data['active'] = 1;
            $data['materials'] = json_encode($this->input->post('materials'));

            $item_data = json_decode($cat_item['data']);

            $item_id = $this->facades_model->add_item();

            $item_data->id = $item_id;
            $item_data->name = $cat_item['name'];
            $item_data->category = $category;
            $item_data->materials = $this->input->post('materials');
            $data['data'] = json_encode($item_data);

            $this->facades_model->update_item($item_id, $data);





        } else {




            $item_id = $this->facades_model->add_item();

            $save_path = $this->config->item('models_upload'). $item_id;
            if (!is_dir($save_path)) {
                mkdir($save_path);
            }

            $data['name'] = $cat_item['name'];
            $data['icon'] = $cat_item['icon'];
            $data['full'] = $cat_item['full'];
            $data['window'] = $cat_item['window'];
            $data['frame'] = $cat_item['frame'];
            $data['radius_full'] = $cat_item['radius_full'];
            $data['radius_window'] = $cat_item['radius_window'];
            $data['radius_frame'] = $cat_item['radius_frame'];
            $data['name'] = $cat_item['name'];
            $data['category'] = $category;
            $data['active'] = 1;
            $data['materials'] = json_encode($this->input->post('materials'));




            $this->facades_model->update_item($item_id, $data);
        }





    }


    public function convert_to_ca() {
        $data = $this->facades_model->get_all_items();

        foreach ($data as $item){

            $models_full = json_decode($item['frame']);

            if(!empty($models_full)){
                foreach ($models_full as $val){
                    if (strpos($val->model, 'common_assets') === false) {


                        print_pre($item['id']);
                    }
                }

//				$item['frame'] = $models_full;

            }




        }



    }

    public function get_categories_ajax() {
        echo json_encode($this->facades_model->get_categories());
    }

    public function set_active_ajax($id, $val) {
        $this->facades_model->set_active($id, $val);
    }


    public function copy_facade_by_id($id) {

        $for_copy = $this->facades_model->get_item($id);


        $item_id = $this->facades_model->add_item_id();
//		$item_id = 1;
        $dta = json_decode($for_copy['data']);
        $dta->id = $item_id;


        $new = array();
        $new['name'] = $for_copy['name'];
        $new['category'] = $for_copy['category'];
        $new['active'] = $for_copy['active'];
        $new['icon'] = $for_copy['icon'];
        $new['full'] = $for_copy['full'];
        $new['window'] = $for_copy['window'];
        $new['frame'] = $for_copy['frame'];
        $new['radius_full'] = $for_copy['radius_full'];
        $new['radius_window'] = $for_copy['radius_window'];
        $new['radius_frame'] = $for_copy['radius_frame'];
        $new['materials'] = $for_copy['materials'];
        $new['data'] = json_encode($dta);

        $this->facades_model->update_item($item_id, $new);

        redirect('facades/items_index/0/20/', 'refresh');
    }
}