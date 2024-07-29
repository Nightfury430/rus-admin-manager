<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Catalog extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->load->helper('url_helper');
        $this->load->model('common_model');
        $this->load->library('session');

        if (!$this->session->username || $this->session->username != $this->config->item('username')) {
            redirect('login', 'refresh');
        }

        if (!$this->config->item('sub_account')) $this->config->set_item('sub_account', false);

        if (
            strpos($this->router->fetch_method(), 'filemanager_') !== false ||
            strpos($this->router->uri->uri_string, 'filemanager_') !== false
        ) {

        } else {
            if ($this->config->item('sub_account') == true) redirect('settings', 'refresh');
        }


        $this->bp_header = 'templates/header';

        if (
            strpos($this->router->fetch_method(), '_coupe') !== false ||
            strpos($this->router->uri->uri_string, 'coupe') !== false

        ) {
            $this->bp_header = 'coupe/header';
        }

    }

    private $no_items_tables = [
        "Kitchen_models" => 1,
        "Accessories" => 1
    ];

    public function items($name, $set_id = false)
    {


        $header = 'templates/header';
        if (strpos($name, 'coupe') === false) $header = 'coupe/header';


//        echo $views_arr[$name];

        $data['controller_name'] = $name;


        $data['set_id'] = $set_id;

        $this->load->model('languages_model');
        $data['lang_arr'] = get_default_lang();
        if ($this->config->item('ini')['language']['language'] !== 'default') {
            $custom_lang = json_decode($this->languages_model->get($this->config->item('ini')['language']['language']));
            foreach ($data['lang_arr'] as $key => $value) {
                if (isset($custom_lang->$key)) {
                    if (!empty($custom_lang->$key)) $data['lang_arr'][$key] = $custom_lang->$key;
                }
            }
        }

        $this->load->view($this->bp_header, $data);
        $this->load->view('catalog/items_index', $data);
        $this->load->view('templates/footer', $data);
    }

    public function item($name, $id = 0)
    {
        $data['controller_name'] = $name;
        $data['id'] = $id;

        $this->load->model('languages_model');
        $data['lang_arr'] = get_default_lang();
        if ($this->config->item('ini')['language']['language'] !== 'default') {
            $custom_lang = json_decode($this->languages_model->get($this->config->item('ini')['language']['language']));
            foreach ($data['lang_arr'] as $key => $value) {
                if (isset($custom_lang->$key)) {
                    if (!empty($custom_lang->$key)) $data['lang_arr'][$key] = $custom_lang->$key;
                }
            }
        }


        $this->load->view($this->bp_header, $data);
        $this->load->view('catalog/' . $name . '_item', $data);
        $this->load->view('templates/footer', $data);

    }

    public function items_get($name, $do_not_add_items = false)
    {
        $table_name = ucfirst($name);
        if ($do_not_add_items == false) $table_name = ucfirst($name) . '_items';
        echo json_encode($this->common_model->get_data_all_by_order($table_name, 'ASC'));
    }

    public function items_get_where($name, $do_not_add_items = false)
    {

        if ($this->input->post()) {
            $where = json_decode($this->input->post('data'), true);

            $table_name = ucfirst($name);
            if ($do_not_add_items == false) $table_name = ucfirst($name) . '_items';
            echo json_encode($this->common_model->get_where($table_name, $where));
        }
    }

    public function get_items_by_id($table_name, $do_not_add_items = false)
    {
        if ($this->input->post()) {

            $id_arr = json_decode($this->input->post('data'), true);
            if ($do_not_add_items == false) $table_name = ucfirst($table_name) . '_items';
            echo json_encode($this->common_model->get_items_by_id($table_name, $id_arr));
        }
    }

    //search is set_id for module_sets
    public function items_get_pagination($name, $category = 0, $per_page = 20, $start = 0, $search = false)
    {
        $table_name = ucfirst($name) . '_items';
        $table_name_categories = ucfirst($name) . '_categories';

        if ($name == 'accessories') {
            $table_name = 'Accessories';
        }
        if ($name == 'kitchen_models') {
            $table_name = 'Kitchen_models';
        }
        $result = array();

        if ($search) $search = urldecode($search);

        $search = str_replace('^pp_lb^', '(', $search);
        $search = str_replace('^pp_rb^', ')', $search);
        $search = str_replace('^pp_com^', ',', $search);
        $search = str_replace('^pp_exc^', '!', $search);
        $search = str_replace('^pp_dog^', '@', $search);
        $search = str_replace('^pp_dol^', '$', $search);
        $search = str_replace('^pp_per^', '%', $search);
        $search = str_replace('^pp_amp^', '&', $search);
        $search = str_replace('^pp_mul^', '*', $search);
        $search = str_replace('^pp_plu^', '+', $search);
        $search = str_replace('^pp_slb^', '[', $search);
        $search = str_replace('^pp_srb^', ']', $search);
        $search = str_replace('^pp_eq^', '=', $search);
        $search = str_replace('^pp_qu^', '', $search);


        if ($name == 'module_sets') {
            $result['items'] = $this->common_model->get_data_pagination_module_set($table_name, $table_name_categories, $search, $category, $per_page, $start);
            $result['count'] = $this->common_model->get_items_count_module_set($table_name, $table_name_categories, $search, $category);
        } else {
            $result['items'] = $this->common_model->get_data_pagination($table_name, $table_name_categories, $category, $per_page, $start, $search);
            $result['count'] = $this->common_model->get_items_count($table_name, $table_name_categories, $category, $search);
        }


        echo json_encode($result);

    }

    public function items_get_pagination_multi()
    {


        if (!isset($_POST)) die();

        $name = $_POST['name'];
        $category = $_POST['category'];
        $categories = $_POST['categories'];
        $per_page = $_POST['per_page'];
        $start = $_POST['start'];
        $search = $_POST['search'];
        $cats_multi = json_decode($_POST['categories_multi']);
        $set_id = null;
        if (isset($_POST['set_id'])) {
            $set_id = $_POST['set_id'];
        }

        $table_name = ucfirst($name) . '_items';
        $table_name_categories = ucfirst($name) . '_categories';


        if ($name == 'accessories') {
            $table_name = 'Accessories';
        }
        if ($name == 'kitchen_models') {
            $table_name = 'Kitchen_models';
        }


        $result = array();

//        if($search) $search = urldecode($search);

//        $search = str_replace('^pp_lb^', '(', $search);
//        $search = str_replace('^pp_rb^', ')', $search);
//        $search = str_replace('^pp_com^', ',', $search);
//        $search = str_replace('^pp_exc^', '!', $search);
//        $search = str_replace('^pp_dog^', '@', $search);
//        $search = str_replace('^pp_dol^', '$', $search);
//        $search = str_replace('^pp_per^', '%', $search);
//        $search = str_replace('^pp_amp^', '&', $search);
//        $search = str_replace('^pp_mul^', '*', $search);
//        $search = str_replace('^pp_plu^', '+', $search);
//        $search = str_replace('^pp_slb^', '[', $search);
//        $search = str_replace('^pp_srb^', ']', $search);
//        $search = str_replace('^pp_eq^', '=', $search);
//        $search = str_replace('^pp_qu^', '', $search);

        $params = [];
        $params['table'] = $table_name;
        $params['cat_table'] = $table_name_categories;
        $params['category'] = $category;
        $params['limit'] = $per_page;
        $params['offset'] = $start;
        $params['search'] = $search;
        $params['cats_multi'] = $cats_multi;
        $params['categories'] = json_decode($categories);
        $params['set_id'] = $set_id;

        $result['items'] = $this->common_model->get_data_pagination_multi($params);
        $result['count'] = $this->common_model->get_items_count_multi($params);


//        if($name == 'module_sets22' ){
////            $result['items'] = $this->common_model->get_data_pagination_module_set($table_name, $table_name_categories, $search, $category, $per_page, $start);
////            $result['count'] = $this->common_model->get_items_count_module_set($table_name, $table_name_categories, $search, $category);
//        } else {
//
//        }

        echo json_encode($result);
    }

    public function items_get_pagination_common_multi()
    {
        if (!isset($_POST)) die();

        $name = $_POST['name'];
        $category = $_POST['category'];
        $categories = $_POST['categories'];
        $per_page = $_POST['per_page'];
        $start = $_POST['start'];
        $search = $_POST['search'];
        $cats_multi = $_POST['categories_multi'];
        $set_id = null;

        $table_name = ucfirst($name) . '_items';
        $table_name_categories = ucfirst($name) . '_categories';


        if ($name == 'accessories') {
            $table_name = 'Accessories';
        }

        $result = array();

        $params = [];
        $params['table'] = $table_name;
        $params['cat_table'] = $table_name_categories;
        $params['category'] = $category;
        $params['limit'] = $per_page;
        $params['offset'] = $start;
        $params['search'] = $search;
        $params['cats_multi'] = $cats_multi;
        $params['categories'] = json_decode($categories);
        $params['set_id'] = $set_id;

        $result['items'] = $this->common_model->get_data_pagination_multi($params);
        $result['count'] = $this->common_model->get_items_count_multi($params);

//        if($name == 'module_sets' ){
//            $result['items'] = $this->common_model->get_data_pagination_module_set($table_name, $table_name_categories, $search, $category, $per_page, $start);
//            $result['count'] = $this->common_model->get_items_count_module_set($table_name, $table_name_categories, $search, $category);
//        } else {
//
//        }

        echo json_encode($result);

//        if($name == 'module_sets' || $name == 'modules_sets_modules'){
//
//
//
//
//            $result['items'] = $this->common_model->get_data_pagination_module_set($table_name, $table_name_categories, $search, $category, $per_page, $start);
//            $result['count'] = $this->common_model->get_items_count_module_set($table_name, $table_name_categories, $search, $category);
//        } else {
//
//
//
//            $result['items'] = $this->common_model->get_data_pagination($table_name, $table_name_categories, $category, $per_page, $start, $search);
//            $result['count'] = $this->common_model->get_items_count($table_name, $table_name_categories, $category, $search);
//        }
//
//
////            $result['items'] = $this->common_model->get_data_pagination($table_name, $table_name_categories, $category, $per_page, $start, $search);
////            $result['count'] = $this->common_model->get_items_count($table_name, $table_name_categories, $category, $search);
//
//
//
//        echo json_encode($result);

    }

    public function item_add($name, $id = 0)
    {
        if (!isset($_POST)) die();
        $table_name = ucfirst($name) . '_items';

        $data = $this->input->post();

        if ($id && $id != 0) {
            $item_id = $id;
        } else {
            $item_id = $this->common_model->add_data($table_name, array('name' => ''));
        }
        $data['id'] = $item_id;
        $input = json_decode($_POST['data'], true);
        $input['id'] = $item_id;
        $data['data'] = json_encode($input);


        $this->common_model->update_where(
            $table_name,
            'id',
            $item_id,
            $data
        );

        echo json_encode('ok');

    }

    public function item_add_multi_cats($name, $id = false)
    {
        if(!isset($_POST)) die();

        $table_name = ucfirst($name) . '_items';
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


    public function mass_category_change($name)
    {
        if ($this->input->post('data')) {
            $data = json_decode($this->input->post('data'), true);
            $table_name = ucfirst($name) . '_items';
            $this->common_model->update_batch($table_name, $data, 'id');
            echo json_encode('ok');
        }
    }

    public function mass_copy_to_category($name)
    {
        if ($this->input->post('data')) {
            $data = json_decode($this->input->post('data'), true);
            $table_name = ucfirst($name) . '_items';
            $this->common_model->add_batch($table_name, $data);
            echo json_encode('ok');
        }
    }

    public function mass_items_remove($name)
    {
        if ($this->input->post('data')) {
            $data = json_decode($this->input->post('data'), true);
            $table_name = ucfirst($name) . '_items';
            $this->common_model->remove_batch($table_name, $data);
            echo json_encode('ok');
        }
    }


    public function item_add_common($name, $id = 0)
    {
        if (!isset($_POST)) die();
        $table_name = ucfirst($name) . '_items';
        $data = $this->input->post();

        if ($id && $id != 0) {
            $item_id = $id;
        } else {
            $item_id = $this->common_model->add_data($table_name, array('name' => ''));
        }
        $data['id'] = $item_id;
        $input = json_decode($_POST['data'], true);
        $input['id'] = $item_id;
        $data['data'] = json_encode($input);
        $this->common_model->update_where(
            $table_name,
            'id',
            $item_id,
            $data
        );
        echo json_encode('ok');
    }

    public function item_add_from_catalog($name, $id = 0)
    {
        if (!isset($_POST)) die();

        $table_name = ucfirst($name) . '_items';
        $item_id = $this->common_model->add_data($table_name, array('name' => ''));
        $data = $this->input->post();
        $data['id'] = $item_id;
        $input = json_decode($_POST['data'], true);

        $input['id'] = $item_id;
        $input['category'] = (int)$data['new_cat'];
        $data['data'] = json_encode($input);
        $data['category'] = (int)$data['new_cat'];
        unset($data['new_cat']);

        $this->common_model->update_where(
            $table_name,
            'id',
            $item_id,
            $data
        );
        echo json_encode('ok');
    }

    public function add_item_v2($table_name, $do_not_add_items = false)
    {
        if ($this->input->post()) {


            if ($do_not_add_items == false) $table_name = ucfirst($table_name) . '_items';

            if ($table_name == 'accessories') {
                $table_name = 'Accessories';
            }





            $data = json_decode($this->input->post('data'), true);


            if (!empty($data['id'])) {
                $item_id = (int)$data['id'];
                unset($data['id']);
            } else {
                $item_id = $this->common_model->add_data($table_name, array('name' => ''));
            }

            $this->common_model->update_where(
                $table_name,
                'id',
                $item_id,
                $data
            );

            echo json_encode('success');

        }
    }


    public function add_item_ajax($name)
    {
        if ($this->input->post()) {
            $table_name = ucfirst($name) . '_items';

            if ($name == 'accessories') {
                $table_name = 'Accessories';
            }

            $data = json_decode($this->input->post('data'), true);

            if (!empty($data['id'])) {
                $item_id = (int)$data['id'];
                unset($data['id']);
            } else {
                $item_id = $this->common_model->add_data($table_name, array('name' => ''));
            }

            $data['id'] = $item_id;

            if (!empty($_FILES)) {
                $upload_function = 'upload_' . $name;
                $data = $this->$upload_function($item_id, $name, $data);
            }


//            foreach ($data as &$field) {
//                if (!is_array($field)) $field = xssafe($field);
//            }
            if (isset($data['params'])) $data['params'] = json_encode($data['params']);
            if ($name == 'builtin') {
                $data = [
                    'name' => $data['name'],
                    'category' => $data['category'],
                    'active' => $data['active'],
                    'icon' => $data['icon'],
                    'model_data' => json_encode($data),
                    'order' => $data['order'],
                ];
            }
            $this->common_model->update_where(
                $table_name,
                'id',
                $item_id,
                $data
            );
            if ($name == 'builtin') {
                $item = $this->common_model->get_row_where($table_name, array('id' => $item_id));
                $model_path = dirname(FCPATH) . '/models/' . $name . '/' . $item_id;

                if (file_exists($model_path)) {
                    $files = array_diff(scandir($model_path), array('.', '..'));

                    $used_files = [];
                    $used_files[basename($item['icon'])] = basename($item['icon']);

                    if (!empty($item['model_data'])) {
                        $data = json_decode($item['model_data'], true);
                        foreach ($data['variants'] as $variant) {
                            $used_files[basename($variant['model'])] = basename($variant['model']);
                        }
                        if ($data['material']['params']['map']) {
                            $used_files[basename($data['material']['params']['map'])] = basename($data['material']['params']['map']);
                        }
                    }

                    $to_del = [];

                    for ($i = 2; $i < count($files) + 2; $i++) {
                        if (!isset($used_files[$files[$i]])) {
                            $to_del[] = $files[$i];
                        }
                    }

                    foreach ($to_del as $del) {
                        unlink($model_path . '/' . $del);
                    }

                }
            }
        }


    }

    public function add_item_ajax_common($name)
    {
        $this->add_item_ajax($name);
    }

    public function add_item_ajax_no_files($name)
    {
        if ($this->input->post()) {


            $table_name = ucfirst($name) . '_items';
            print_pre($this->input->post());
//            $item_id = $this->common_model->add_data( $table_name, array('name'=>''));
        }

    }

    private function upload_builtin($item_id, $name, $data)
    {

        $save_dir = 'models';
        if (isset($_POST['save_dir'])) $save_dir = $_POST['save_dir'];

        $save_path = dirname(FCPATH) . '/' . $save_dir . '/' . $name . '/' . $item_id;
        $abs_save_path = $save_dir . '/' . $name . '/' . $item_id;
        if (!is_dir(dirname(FCPATH) . '/' . $save_dir . '/' . $name)) mkdir(dirname(FCPATH) . '/' . $save_dir . '/' . $name);
        if (!is_dir($save_path)) mkdir($save_path);

        $uploaded_files = array();

        foreach ($_FILES as $key => $file) {
            $new_file = $save_path . '/' . strtolower($file['name']);

            if (move_uploaded_file($file['tmp_name'], $new_file)) {
                $uploaded_files['file_' . $key] = array(
                    'model' => $abs_save_path . '/' . strtolower($file['name'])
                );
            } else {
                $errors[] = 'Ошибка при загрузке файла ' . $file['name'] . '\\n';
            }
        }

        print_pre($uploaded_files);
        foreach ($data['variants'] as &$variant) {
            if (isset($uploaded_files['file_' . $variant['id']])) {
                $variant['model'] = $uploaded_files['file_' . $variant['id']]['model'];
            }
        }

        if (isset($uploaded_files['file_icon_file'])) {
            $data['icon'] = $uploaded_files['file_icon_file']['model'];
        }

        if (isset($uploaded_files['file_map_file'])) {
            $data['material']['params']['map'] = $uploaded_files['file_map_file']['model'];
        }

        print_pre($data);


        return $data;

    }

    private function upload_coupe_profile($item_id, $name, $data)
    {
        $save_dir = 'models';
        if (isset($_POST['save_dir'])) $save_dir = $_POST['save_dir'];

        $save_path = dirname(FCPATH) . '/' . $save_dir . '/' . $name . '/' . $item_id;
        $abs_save_path = $save_dir . '/' . $name . '/' . $item_id;
        if (!is_dir(dirname(FCPATH) . '/' . $save_dir . '/' . $name)) mkdir(dirname(FCPATH) . '/' . $save_dir . '/' . $name);
        if (!is_dir($save_path)) mkdir($save_path);


        if (isset($_FILES)) {
            foreach ($_FILES as $key => $file) {
                $new_file = $save_path . '/' . strtolower($file['name']);

                if (move_uploaded_file($file['tmp_name'], $new_file)) {
                    $exp = explode('_', $key);
                    $model_val = &$data;
                    foreach ($exp as $v) {
                        $model_val = &$model_val[$v];
                    }
                    $model_val = $abs_save_path . '/' . strtolower($file['name']);

                } else {
                    $errors[] = 'Ошибка при загрузке файла ' . $file['name'] . '\\n';
                }
            }
        }
        if (isset($_POST['key_files'])) {
            $kf = json_decode($_POST['key_files'], true);
            $fnames = array();

            foreach ($kf as $f) {
                $exp = explode('_', $f);

                $model_val = &$data;
                foreach ($exp as $v) {
                    $model_val = &$model_val[$v];
                }

                if (!empty(basename($model_val))) $fnames[] = basename($model_val);

            }


            $current_files = array_diff(scandir($save_path), array('.', '..'));
            $ex_files = array_merge(array_diff($current_files, $fnames), array_diff($fnames, $current_files));
            foreach ($ex_files as $f) {
                unlink($save_path . '/' . $f);
            }
        }
        return $data;
    }


    public function item_copy($name, $id)
    {
        $table_name = ucfirst($name) . '_items';
        $data = $this->common_model->get_row_where($table_name, array('id' => $id));
        unset($data['id']);
        if (isset($data['name'])) {
            $data['name'] = $data['name'] . '(копия)';
        }
        if (isset($data['order'])) {
            $data['order'] = (int)$data['order'] - 1;
        }

        $resp = $this->common_model->add_data($table_name, $data);
        echo json_encode($resp);
    }


    public function remove_item($name)
    {
        $table_name = ucfirst($name) . '_items';

        if ($name == 'accessories') {
            $table_name = 'Accessories';
        }

        if ($this->input->post()) $this->common_model->remove_where($table_name, 'id', (int)$this->input->post('id'));
    }

    public function get_item($name, $id)
    {
        $table_name = ucfirst($name);
        if (!isset($this->no_items_tables[$table_name])) {
            $table_name .= '_items';
        }
        echo json_encode($this->common_model->get_row_where($table_name, array('id' => $id)));
    }

    public function item_set_active($name, $id, $val)
    {
        $table_name = ucfirst($name) . '_items';

        if ($name == 'accessories') {
            $table_name = 'Accessories';

        }

        $this->common_model->update_where(
            $table_name,
            'id',
            $id,
            array('active' => $val)
        );
    }

    public function categories($name, $set_id = false)
    {


        $data['controller_name'] = $name;

        $data['set_id'] = $set_id;

        $this->load->model('languages_model');
        $data['lang_arr'] = get_default_lang();
        if ($this->config->item('ini')['language']['language'] !== 'default') {
            $custom_lang = json_decode($this->languages_model->get($this->config->item('ini')['language']['language']));
            foreach ($data['lang_arr'] as $key => $value) {
                if (isset($custom_lang->$key)) {
                    if (!empty($custom_lang->$key)) $data['lang_arr'][$key] = $custom_lang->$key;
                }
            }
        }

        $this->load->view($this->bp_header, $data);
        $this->load->view('catalog/categories_index_new_v', $data);
        $this->load->view('templates/footer', $data);
    }

    public function categories_v($name, $set_id = false)
    {


        $data['controller_name'] = $name;

        $data['set_id'] = $set_id;

        $this->load->model('languages_model');
        $data['lang_arr'] = get_default_lang();
        if ($this->config->item('ini')['language']['language'] !== 'default') {
            $custom_lang = json_decode($this->languages_model->get($this->config->item('ini')['language']['language']));
            foreach ($data['lang_arr'] as $key => $value) {
                if (isset($custom_lang->$key)) {
                    if (!empty($custom_lang->$key)) $data['lang_arr'][$key] = $custom_lang->$key;
                }
            }
        }

        $this->load->view($this->bp_header, $data);
        $this->load->view('catalog/categories_index_new_v', $data);
        $this->load->view('templates/footer', $data);
    }

    public function categories_new($name, $set_id = false)
    {


        $data['controller_name'] = $name;

        $data['set_id'] = $set_id;

        $this->load->model('languages_model');
        $data['lang_arr'] = get_default_lang();
        if ($this->config->item('ini')['language']['language'] !== 'default') {
            $custom_lang = json_decode($this->languages_model->get($this->config->item('ini')['language']['language']));
            foreach ($data['lang_arr'] as $key => $value) {
                if (isset($custom_lang->$key)) {
                    if (!empty($custom_lang->$key)) $data['lang_arr'][$key] = $custom_lang->$key;
                }
            }
        }

        $this->load->view($this->bp_header, $data);
        $this->load->view('catalog/categories_index_new', $data);
        $this->load->view('templates/footer', $data);
    }

    public function categories_order_update($name)
    {
        if ($this->input->post()) {
            $table_name = ucfirst($name) . '_categories';
            $this->common_model->update_batch($table_name, json_decode($this->input->post('data'), true), 'id');
        }
    }

    public function categories_get($name, $set_id = false)
    {
        $table_name = ucfirst($name) . '_categories';

        if ($name == 'module_sets' || $name == 'modules_sets_modules') {
            echo json_encode($this->common_model->get_data_all_by_order_module_set($table_name, $set_id, 'ASC'));
        } else {
            echo json_encode($this->common_model->get_data_all_by_order($table_name, 'ASC'));
        }


    }

    public function categories_get_obid($name, $set_id = false)
    {
        $table_name = ucfirst($name) . '_categories';

        if ($name == 'module_sets' || $name == 'modules_sets_modules') {
            echo json_encode($this->common_model->get_data_all_by_order_module_set($table_name, $set_id, 'ASC'));
        } else {
            echo json_encode($this->common_model->get_data_all_by_id($table_name, 'ASC'));
        }


    }

    public function categories_get_multi()
    {
        if (!isset($_POST)) die();

        $params = [];
        $params['categories'] = json_decode($_POST['categories']);
        $params['table'] = ucfirst($_POST['name']) . '_categories';

        if ($_POST['name'] == 'module_sets' || $_POST['name'] == 'modules_sets_modules') {
//            echo json_encode($this->common_model->get_data_all_by_order_module_set($table_name, $set_id, 'ASC'));
        } else {
            echo json_encode($this->common_model->get_categories_multi($params));
        }


    }

    public function categories_get_where($name, $set_id = false)
    {
        if ($this->input->post()) {
            $where = json_decode($this->input->post('data'), true);
            $table_name = ucfirst($name) . '_categories';
            echo json_encode($this->common_model->get_where($table_name, $where));
        }
    }

    public function categories_add($name, $set_id = false)
    {
        $table_name = ucfirst($name) . '_categories';
        if ($this->input->post()) {

            if ($name == 'module_sets' || $name == 'modules_sets_modules') {
                echo $this->common_model->add_data($table_name,
                    array(
                        "name" => no_xss($this->input->post('name')),
                        "set_id" => $set_id,
                        "parent" => (int)$this->input->post('parent'),
                        "active" => (int)$this->input->post('active'),
                        "order" => (int)$this->input->post('order')
                    )
                );
            } else {
                echo $this->common_model->add_data($table_name,
                    array(
                        "name" => no_xss($this->input->post('name')),
                        "parent" => (int)$this->input->post('parent'),
                        "active" => (int)$this->input->post('active'),
                        "order" => (int)$this->input->post('order')
                    )
                );
            }


        }
    }

    public function categories_update($name)
    {
        $table_name = ucfirst($name) . '_categories';
        if ($this->input->post()) {
            $this->common_model->update_where(
                $table_name,
                'id',
                (int)$this->input->post('id'),
                array(
                    "name" => no_xss($this->input->post('name')),
                    "order" => no_xss($this->input->post('order')),
                    "parent" => no_xss($this->input->post('parent')),
                    "description" => no_xss($this->input->post('description')),
                    "image" => no_xss($this->input->post('image'))
                )
            );
        }



    }

    public function categories_set_active($name, $id, $val)
    {
        $table_name = ucfirst($name) . '_categories';
        $this->common_model->set_category_active($table_name, $id, $val);
    }

    public function category_delete_ajax($name)
    {
        $table_name = ucfirst($name) . '_categories';
        if ($this->input->post()) $this->common_model->remove_where($table_name, 'id', (int)$this->input->post('id'));
    }


    public function prices($name)
    {
        $data['controller_name'] = $name;

        $this->load->model('languages_model');
        $data['lang_arr'] = get_default_lang();
        if ($this->config->item('ini')['language']['language'] !== 'default') {
            $custom_lang = json_decode($this->languages_model->get($this->config->item('ini')['language']['language']));
            foreach ($data['lang_arr'] as $key => $value) {
                if (isset($custom_lang->$key)) {
                    if (!empty($custom_lang->$key)) $data['lang_arr'][$key] = $custom_lang->$key;
                }
            }
        }

        $this->load->view($this->bp_header, $data);
        $this->load->view('catalog/prices_index', $data);
        $this->load->view('templates/footer', $data);
    }


    //common_catalog

    public function get_catalog_items($name)
    {
        $table_name_categories = ucfirst($name) . '_categories';
        $table_name_items = ucfirst($name) . '_items';

        $result = array();
        $result['items'] = array();
        $result['categories'] = $this->common_model->get_data_all_by_order_common($table_name_categories, 'ASC');
        if ($name != 'materials') {
            $result['items'] = $this->common_model->get_data_all_by_order_common($table_name_items, 'ASC');
        }


        echo json_encode($result);
    }

    public function add_catalog_items($name)
    {
        if (!isset($_POST)) exit;

        if ($name == 'materials') {
            $this->add_catalog_materials($_POST['id']);
            exit;
        }


    }

    private function add_catalog_materials($id)
    {

        $category = $this->common_model->get_where_common('Materials_categories', array('id' => $id));

        if (!isset($category[0])) {
            echo 'error';
            exit;
        }

        $category = $category[0];


        $old_id = $category['id'];
        unset($category['id']);
        $category['order'] = 10000;

        $new_id = $this->common_model->add_data('Materials_categories', $category);
//        $new_id = 100;

        $items = $this->common_model->get_where_common('Materials_items', array('category' => $old_id));

        foreach ($items as &$item) {
            unset($item['id']);
            unset($item['params']);
            $item['category'] = $new_id;
            $this->common_model->add_data('Materials_items', $item);
        }

        $categories = $this->common_model->get_where_common('Materials_categories', array('parent' => $id));

        foreach ($categories as &$cat) {
            $oid = $cat['id'];


            unset($cat['id']);
            $cat['parent'] = $new_id;


            $nid = $this->common_model->add_data('Materials_categories', $cat);
            $items = $this->common_model->get_where_common('Materials_items', array('category' => $oid));
            foreach ($items as &$item) {
                unset($item['id']);
                unset($item['params']);
                $item['category'] = $nid;
                $this->common_model->add_data('Materials_items', $item);
            }


        }

    }

    public function clear_models()
    {

        $table_names = [
            'interior', 'tech', 'comms'
        ];

        foreach ($table_names as $name) {
            $table_name = ucfirst($name) . '_items';
            $items = $this->common_model->get_data_all_by_order($table_name, 'ASC');

            foreach ($items as $item) {

                $id = $item['id'];


                $model_path = dirname(FCPATH) . '/models/' . $name . '/' . $id;
//                print_pre(scandir($model_path));

                if (file_exists($model_path)) {

                    $files = array_diff(scandir($model_path), array('.', '..'));


//                    foreach ($files as &$file){
//                        $file = iconv("UTF-8","Windows-1251",$file);
//                    }
//                    print_pre($files);

                    $used_files = [];

                    $used_files[basename($item['icon'])] = basename($item['icon']);

                    if (!empty($item['model_data'])) {
                        $data = json_decode($item['model_data'], true);
                        foreach ($data['variants'] as $variant) {
                            $used_files[basename($variant['model'])] = basename($variant['model']);

                        }
                    }

                    print_pre($files);
                    print_pre($used_files);

                    $to_del = [];

                    foreach ($files as $file) {
                        if (!isset($used_files[$file])) {
                            $to_del[] = $file;
                        }
                    }
//                    print_pre('to_del');
                    print_pre($to_del);

                    foreach ($to_del as $del) {
//                        unlink($model_path . '/' . $del);
                    }

                }


            }

//            print_pre($items);

        }


    }


    //common_edits

    public function categories_common($name, $set_id = false)
    {


        $data['controller_name'] = $name;

        $data['set_id'] = $set_id;
        $data['common'] = 1;
        $this->load->model('languages_model');
        $data['lang_arr'] = get_default_lang();
        if ($this->config->item('ini')['language']['language'] !== 'default') {
            $custom_lang = json_decode($this->languages_model->get($this->config->item('ini')['language']['language']));
            foreach ($data['lang_arr'] as $key => $value) {
                if (isset($custom_lang->$key)) {
                    if (!empty($custom_lang->$key)) $data['lang_arr'][$key] = $custom_lang->$key;
                }
            }
        }

        $this->load->view($this->bp_header, $data);
        $this->load->view('catalog/categories_index', $data);
        $this->load->view('templates/footer', $data);
    }

    public function categories_order_update_common($name)
    {
        $this->categories_order_update($name);
    }

    public function categories_get_common($name, $set_id = false)
    {
        $this->categories_get($name, $set_id);
    }

    public function categories_add_common($name, $set_id = false)
    {
        $this->categories_add($name, $set_id);
    }

    public function categories_update_common($name)
    {
        $this->categories_update($name);


    }

    public function categories_set_active_common($name, $id, $val)
    {
        $this->categories_set_active($name, $id, $val);
    }

    public function category_delete_ajax_common($name)
    {
        $this->category_delete_ajax($name);
    }


    public function items_get_pagination_common($name, $category = 0, $per_page = 20, $start = 0, $search = false)
    {
        $table_name = ucfirst($name) . '_items';
        $table_name_categories = ucfirst($name) . '_categories';

        $result = array();

        if ($search) $search = urldecode($search);


        if ($name == 'module_sets' || $name == 'modules_sets_modules') {


            $result['items'] = $this->common_model->get_data_pagination_module_set($table_name, $table_name_categories, $search, $category, $per_page, $start);
            $result['count'] = $this->common_model->get_items_count_module_set($table_name, $table_name_categories, $search, $category);
        } else {


            $result['items'] = $this->common_model->get_data_pagination($table_name, $table_name_categories, $category, $per_page, $start, $search);
            $result['count'] = $this->common_model->get_items_count($table_name, $table_name_categories, $category, $search);
        }


//            $result['items'] = $this->common_model->get_data_pagination($table_name, $table_name_categories, $category, $per_page, $start, $search);
//            $result['count'] = $this->common_model->get_items_count($table_name, $table_name_categories, $category, $search);


        echo json_encode($result);

    }


    public function items_common($name, $set_id = false)
    {
        if (!check_access_to_common_db()) exit;
        $header = 'templates/header';
        if (strpos($name, 'coupe') === false) $header = 'coupe/header';


//        echo $views_arr[$name];

        $data['controller_name'] = $name;
        $data['common'] = 1;

        $data['set_id'] = $set_id;


        $this->load->model('languages_model');
        $data['lang_arr'] = get_default_lang();
        if ($this->config->item('ini')['language']['language'] !== 'default') {
            $custom_lang = json_decode($this->languages_model->get($this->config->item('ini')['language']['language']));
            foreach ($data['lang_arr'] as $key => $value) {
                if (isset($custom_lang->$key)) {
                    if (!empty($custom_lang->$key)) $data['lang_arr'][$key] = $custom_lang->$key;
                }
            }
        }

        $this->load->view($this->bp_header, $data);
        $this->load->view('catalog/items_index', $data);
        $this->load->view('templates/footer', $data);
    }

    public function items_catalog($name, $set_id = false)
    {

        $header = 'templates/header';
        if (strpos($name, 'coupe') === false) $header = 'coupe/header';


//        echo $views_arr[$name];

        $data['controller_name'] = $name;
        $data['common'] = 1;
        $data['catalog_add'] = 1;

        $data['set_id'] = $set_id;


        $this->load->model('languages_model');
        $data['lang_arr'] = get_default_lang();
        if ($this->config->item('ini')['language']['language'] !== 'default') {
            $custom_lang = json_decode($this->languages_model->get($this->config->item('ini')['language']['language']));
            foreach ($data['lang_arr'] as $key => $value) {
                if (isset($custom_lang->$key)) {
                    if (!empty($custom_lang->$key)) $data['lang_arr'][$key] = $custom_lang->$key;
                }
            }
        }

        $this->load->view($this->bp_header, $data);
        $this->load->view('catalog/items_index', $data);
        $this->load->view('templates/footer', $data);
    }

    public function item_common($name, $id = 0, $set_id = 0)
    {

//        if($id == 0){
//            if($name == 'facades_systems'){
//                $table_name = ucfirst($name) . '_items';
//                $id = $this->common_model->add_data( $table_name, array('name'=>''));
//
//                redirect('/catalog/item_common/' . $name . '/' . $id, 'refresh');
//
//            }
//        }


        $data['controller_name'] = $name;
        $data['id'] = $id;
        $data['set_id'] = $set_id;
        $data['common'] = 1;


        $this->load->model('languages_model');
        $data['lang_arr'] = get_default_lang();
        if ($this->config->item('ini')['language']['language'] !== 'default') {
            $custom_lang = json_decode($this->languages_model->get($this->config->item('ini')['language']['language']));
            foreach ($data['lang_arr'] as $key => $value) {
                if (isset($custom_lang->$key)) {
                    if (!empty($custom_lang->$key)) $data['lang_arr'][$key] = $custom_lang->$key;
                }
            }
        }


        $this->load->view($this->bp_header, $data);
        $this->load->view('catalog/' . $name . '_item', $data);
        $this->load->view('templates/footer', $data);

    }

    public function remove_item_common($name)
    {
        $table_name = ucfirst($name) . '_items';
        if ($this->input->post()) $this->common_model->remove_where($table_name, 'id', (int)$this->input->post('id'));

        if ($name == 'modules_sets') {
            $this->common_model->remove_where('Modules_sets_modules_categories', 'set_id', (int)$this->input->post('id'));
            $this->common_model->remove_where('Modules_sets_modules_items', 'set_id', (int)$this->input->post('id'));
        }

    }

    public function items_get_common($name, $do_not_add_items = false)
    {
        $table_name = ucfirst($name);
        if ($do_not_add_items == false) $table_name = ucfirst($name) . '_items';
        echo json_encode($this->common_model->get_data_all_by_order($table_name, 'ASC'));
    }

    public function get_item_common($name, $id)
    {
        $table_name = ucfirst($name) . '_items';
        echo json_encode($this->common_model->get_row_where($table_name, array('id' => $id)));
    }


    public function add_item_ajax_no_files_common($name, $id = false)
    {
        if ($this->input->post()) {
            $table_name = ucfirst($name) . '_items';

            if ($name == 'accessories') {
                $table_name = 'Accessories';
            }

            if ($id) {
                $this->common_model->update_where(
                    $table_name,
                    'id',
                    $id,
                    $this->input->post()
                );
            } else {
                $this->common_model->add_data($table_name, $this->input->post());
            }


            echo json_encode('success');

//            $item_id = $this->common_model->add_data( $table_name, array('name'=>''));
        }

    }

    public function add_modules_data_from_json_common()
    {
        if (isset($_POST)) {
            print_pre($_POST);
            $data = array(
                'categories' => array(),
                'items' => array()
            );
            if (isset($_POST['data'])) $data = json_decode($_POST['data'], true);

            $set_id = $_POST['id'];

            $categories = $data['categories'];
            $items = $data['items'];

            $ord = 1;

            $cat_ids = array();

            foreach ($categories as $category) {


                $cat_id = $this->common_model->add_data('Modules_sets_modules_categories',
                    array(
                        "name" => $category['name'],
                        "set_id" => $set_id,
                        "parent" => 0,
                        "active" => 1,
                        "order" => $ord
                    )
                );
                $cat_ids[$category['id']] = $cat_id;

                $ord++;

                foreach ($category['children'] as $subcat) {
                    $subcat_id = $this->common_model->add_data('Modules_sets_modules_categories',
                        array(
                            "name" => $subcat['name'],
                            "set_id" => $set_id,
                            "parent" => $cat_id,
                            "active" => 1,
                            "order" => $ord
                        )
                    );
                    $cat_ids[$subcat['id']] = $subcat_id;
                    $ord++;
                }


            }


            foreach ($items as $item) {


                $params = array(
                    'params' => $item['params']
                );
                $item_cat = 0;

                if (isset($cat_ids[$item['category']])) $item_cat = $cat_ids[$item['category']];

                $this->common_model->add_data('Modules_sets_modules_items',
                    array(
                        "category" => $item_cat,
                        "icon" => $item['icon'],
                        "params" => json_encode($params),
                        "active" => 1,
                        "set_id" => $set_id,
                        "order" => 10000
                    )
                );
            }

        }
    }

    public function upload_files()
    {
        if (!isset($_POST['save_dir'])) exit;
        $save_dir = $_POST['save_dir'];
        $save_path = $_SERVER['DOCUMENT_ROOT'] . $save_dir;

        foreach ($_FILES as $key => $file) {
            $new_file = $save_path . '/' . strtolower($file['name']);
            move_uploaded_file($file['tmp_name'], $new_file);
        }
        echo json_encode('success');
    }

    public function create_folder()
    {

        if (!isset($_POST)) exit;
        $save_dir = $_POST['save_dir'];
        $name = $_POST['name'];

        $save_path = $_SERVER['DOCUMENT_ROOT'] . $save_dir . '/' . $name;


        if (!is_dir($save_path)) mkdir($save_path);
        echo json_encode('success');

    }

    public function rename_folder()
    {
        if (!isset($_POST)) exit;

        $old_name = $_SERVER['DOCUMENT_ROOT'] . $_POST['old_name'];
        $new_name = $_SERVER['DOCUMENT_ROOT'] . $_POST['new_name'];

        echo json_encode(rename($old_name, $new_name));

    }

    public function remove_files()
    {
        if (isset($_POST['path'])) {

            if (isset($_POST['is_dir'])) {
                rmdir($_SERVER['DOCUMENT_ROOT'] . $_POST['path']);
            } else {
                unlink($_SERVER['DOCUMENT_ROOT'] . $_POST['path']);
            }

            echo json_encode('success');

        }
    }

    public function upload_model_common($rewrite = false)
    {
//        print_pre($_FILES['file']);
//        print_pre($_POST);


        if (!isset($_FILES['file'])) {
            echo json_encode(
                array(
                    'result' => 'fail',
                    'message' => 'no_file'
                )
            );
            exit;
        }
        if (!isset($_POST)) {
            echo json_encode(
                array(
                    'result' => 'fail',
                    'message' => 'no_data'
                )
            );
            exit;
        }

        $allowed = array('fbx');
        $file = $_FILES['file'];

        $filename = strtolower($file['name']);
        $ext = pathinfo($filename, PATHINFO_EXTENSION);
        if (!in_array($ext, $allowed)) {
            echo json_encode(
                array(
                    'result' => 'fail',
                    'message' => 'wrong_extension'
                )
            );
            exit;
        }

        $save_dir = '/common_assets/models/common';
        if (isset($_POST['save_dir'])) $save_dir = $_POST['save_dir'];

        $filename = strtolower($file['name']);

        $save_path = $_SERVER['DOCUMENT_ROOT'] . $save_dir;
        $new_file = $save_path . '/' . $filename;
        $abs_save_path = $save_dir . '/' . $filename;


        $new_file = $save_path . '/' . $filename;

        if ($rewrite == false && file_exists($new_file)) {
            echo json_encode(
                array(
                    'result' => 'fail',
                    'message' => 'file_exists'
                )
            );
            exit;
        }

        if (!move_uploaded_file($file['tmp_name'], $new_file)) {
            echo json_encode(
                array(
                    'result' => 'fail',
                    'message' => 'unknown_error'
                )
            );
            exit;
        }

        $data = array();
        $data['name'] = $_POST['name'];
        if ($data['name'] == '') $data['name'] = $filename;
        $data['category'] = $_POST['category'];
        $data['url'] = $abs_save_path;

        if ($rewrite == false) {
            $item_id = $this->common_model->add_data('Uploaded_models_items', $data);
        }


        echo json_encode(
            array(
                'result' => 'success',
                'message' => 'file_uploaded',
                'data' => $abs_save_path
            )
        );


    }


    public function model_common($name, $item_id)
    {
        if (isset($_POST)) {

            $save_dir = 'models';
            if (isset($_POST['save_dir'])) $save_dir = $_POST['save_dir'];

            $controller_models_path = $_SERVER['DOCUMENT_ROOT'] . '/common_assets/' . $save_dir . '/' . $name;

            $save_path = $_SERVER['DOCUMENT_ROOT'] . '/common_assets/' . $save_dir . '/' . $name;

            $save_path = dirname(FCPATH) . '/' . $save_dir . '/' . $name . '/' . $item_id;
            $abs_save_path = $save_dir . '/' . $name . '/' . $item_id;
            if (!is_dir(dirname(FCPATH) . '/' . $save_dir . '/' . $name)) mkdir(dirname(FCPATH) . '/' . $save_dir . '/' . $name);
            if (!is_dir($save_path)) mkdir($save_path);

        }
    }


    public function test()
    {

        $save_dir = '/common_assets/models';
        if (isset($_POST['save_dir'])) $save_dir = $_POST['save_dir'];


        $save_path = $_SERVER['DOCUMENT_ROOT'] . $save_dir;

        $arr = [];
        $arr['cats'] = [];
        $arr['files'] = [];


        if ($handle = opendir($save_path)) {
            while (false !== ($filename = readdir($handle))) {
                if ($filename != "." && $filename != "..") {  //ignoring dot paths under linux


                    if (is_dir($save_path . '/' . $filename)) {
                        $arr['cats'][] = $filename;
                    } else {
                        $arr['files'][] = $filename;
                    }


                    //dostuff
                } //endif linux dir filename
            }//endwhile
        } //endif opendir

//        print_pre($arr);

//        var_dump(getDirContents($save_path));
    }


    public function export_facades_systems()
    {

        file_put_contents($_SERVER['DOCUMENT_ROOT'] . '/common_assets/fs/russta/fs.json', $_POST['data']);
        echo json_encode('success');

    }


    public function file_manager()
    {
        exit;
        $save_dir = '/common_assets/models/';


        if (isset($_POST['save_dir'])) {
            $save_dir = $_POST['save_dir'];
        }

        $allowed = array('fbx');

        if (isset($_POST['mode'])) {
            $allowed = json_decode($_POST['mode']);
        }


        $dirpath = $_SERVER['DOCUMENT_ROOT'] . $save_dir;


        if (!is_dir($dirpath) || !is_readable($dirpath)) {
            error_log(__FUNCTION__ . ": Argument should be a path to valid, readable directory (" . var_export($dirpath, true) . " provided)");
            return null;
        }

        $result = array(
            'categories' => array(),
            'files' => array()
        );

        $it = -1;


        $dir = realpath($dirpath);
        $di = new DirectoryIterator($dir);
        foreach ($di as $fileinfo) {
            if (!$fileinfo->isDot()) {

                $path = $fileinfo->getPathname();
                $p = str_replace('\\', '/', $path);
                $p = str_replace($_SERVER['DOCUMENT_ROOT'], '', $p);

                if ($fileinfo->isDir()) {
                    $it++;
                    $result['categories'][$it] = array(
                        'name' => $fileinfo->getBasename(),
                        'path' => $p,
                        'type' => 'folder',
                        'children' => array()
                    );

                    $d2 = new DirectoryIterator($path);

                    foreach ($d2 as $finfo) {
                        if (!$finfo->isDot()) {

                            $path2 = $finfo->getPathname();
                            $p2 = str_replace('\\', '/', $path2);
                            $p2 = str_replace($_SERVER['DOCUMENT_ROOT'], '', $p2);
                            if ($finfo->isDir()) {
                                $result['categories'][$it]['children'][] = array(
                                    'name' => $finfo->getBasename(),
                                    'path' => $p2,
                                    'type' => 'folder',
                                    'children' => array()
                                );
                            }
                        }
                    }

                } else {
                    $result['files'][] = array(
                        'name' => $fileinfo->getBasename(),
                        'path' => $p,
                        'ext' => strtolower($fileinfo->getExtension()),
                        'type' => 'file'
                    );

                }
            }
        }

        echo json_encode($result);

//            return $paths;


    }


    public function filemanager_tree()
    {
        if (!isset($_POST['type'])) {
            echo json_encode('error no type');
            exit;
        }
        $root_dir = $this->get_root($_POST['type']);


        if (!file_exists($root_dir)) mkdir($root_dir);

        $current_dir = '';

        if (isset($_POST['current_dir'])) $current_dir = $_POST['current_dir'];

        $current_path = $root_dir . $current_dir;


        if (!is_dir($current_path) || !is_readable($current_path)) {
            return null;
        }

        $mode = array();
        $mode['images'] = array();
        $mode['models'] = array();

        $result = array(
            'folders' => array(),
            'files' => array()
        );

        $dir = realpath($current_path);

        $directory_iterator = new DirectoryIterator($dir);
        $it = -1;

        $tmp = array();

        foreach ($directory_iterator as $file_info) {
            if ($file_info->isDot()) continue;
            $path = $file_info->getPathname();

            $root_dir = str_replace('\\', '/', $root_dir);
            $path = str_replace('\\', '/', $path);
            $rel_path = str_replace($root_dir, '', $path);

            if ($file_info->isDir()) {
                $it++;

                $result['folders'][$it] = array(
                    'name' => $file_info->getBasename(),
                    'path' => $rel_path,
                    'type' => 'folder',
                    'id' => uniqid(),
                    'children' => array()
                );


                $sub_directory_iterator = new DirectoryIterator($path);

                foreach ($sub_directory_iterator as $sub_file_info) {
                    if ($sub_file_info->isDot()) continue;
                    $sub_path = $sub_file_info->getPathname();
                    $sub_rel_path = str_replace($root_dir, '', $sub_path);

                    if ($sub_file_info->isDir()) {
                        $result['folders'][$it]['children'][] = array(
                            'name' => $sub_file_info->getBasename(),
                            'path' => $sub_rel_path,
                            'type' => 'folder',
                            'id' => uniqid(),
                            'children' => array()
                        );
                    }
                }


            } else {
                $result['files'][] = array(
                    'name' => $file_info->getBasename(),
                    'path' => $rel_path,
                    'ext' => strtolower($file_info->getExtension()),
                    'type' => 'file'
                );
            }


            $tmp[] = array('root' => $root_dir, 'rel' => $rel_path);
        }

        echo json_encode($result);

    }

    public function filemanager_create_folder()
    {
        if (!isset($_POST)) exit;

        if (!isset($_POST['type'])) {
            echo json_encode('error no type');
            exit;
        }
        $root_dir = $this->get_root($_POST['type']);


        if (!file_exists($root_dir)) mkdir($root_dir);

        $result = array(
            'errors' => array()
        );

        $path = $_POST['save_dir'];
        $name = trim($_POST['name']);
        $new_folder_path = $root_dir . $path . DIRECTORY_SEPARATOR . $name;


        if ($name == '') {
            echo json_encode('is_blank');
            exit;
        }

        if (preg_match('/[^a-z_\-0-9]/i', $name)) {
            echo json_encode('is_invalid');
            exit;
        }

        if (file_exists($new_folder_path)) {
            echo json_encode('is_exists');
            exit;
        }

        mkdir($new_folder_path);
        echo json_encode('');

    }

    public function filemanager_rename()
    {
        if (!isset($_POST)) exit;
        if (!isset($_POST['type'])) {
            echo json_encode('error no type');
            exit;
        }

        $root_dir = $this->get_root($_POST['type']);
        $old_name = $root_dir . $_POST['old_path'];
        $new_name = $root_dir . $_POST['new_path'];

        if (file_exists($new_name)) {
            echo json_encode('is_exists');
            exit;
        }


        if (rename($old_name, $new_name)) {
            echo json_encode('');
        }


    }

    public function filemanager_remove()
    {

        if (!isset($_POST)) exit;
        if (!isset($_POST['type'])) {
            echo json_encode('error no type');
            exit;
        }

        $root_dir = $this->get_root($_POST['type']);

        if (isset($_POST['path'])) {

            if (isset($_POST['is_dir'])) {
                removeDirectory($root_dir . $_POST['path']);
            } else {
                unlink($root_dir . $_POST['path']);
            }

            echo json_encode('success');

        }
    }

    public function filemanager_upload()
    {
        if (!isset($_POST)) exit;

        if (!isset($_POST['type'])) {
            echo json_encode('error no type');
            exit;
        }
        $root_dir = $this->get_root($_POST['type']);
        if (!file_exists($root_dir)) mkdir($root_dir);

        $save_dir = $root_dir . $_POST['save_dir'];

        $allowed = array('jpg', 'jpeg', 'png', 'gif', 'fbx', 'hdr');


        $errs = [];
        $uploaded = [];
        foreach ($_FILES as $key => $file) {

            $ext = pathinfo($file['name'], PATHINFO_EXTENSION);
            if (!in_array($ext, $allowed)) {
                $errs[] = [
                    'error' => 'wrong_ext',
                    'name' => $file['name']
                ];
                continue;
            }

            if ($file['size'] > 52428800) {
                $errs[] = [
                    'error' => 'error_max_size',
                    'name' => $file['name']
                ];
                continue;
            }

            $new_file = $save_dir . '/' . strtolower($file['name']);
            move_uploaded_file($file['tmp_name'], $new_file);
            $uploaded[] = $file['name'];
        }

        $res = [
            'errors' => $errs,
            'uploaded' => $uploaded
        ];


        echo json_encode($res);
    }


    public function fix_materials()
    {
        $arr = [];
        $arr['ldsp_fresh'] = [145, 146, 147, 148, 149, 150, 151, 152, 153, 154, 155, 156, 157, 164, 167, 169];
        $arr['ldsp_optima'] = [89, 90, 91, 92, 93, 95, 96, 97, 98, 99, 100, 101, 102, 103, 104, 105, 106, 107, 108, 109, 110, 111, 112, 113, 114, 115, 116, 117, 118, 119, 120, 121, 122, 123, 124, 125, 126, 127, 128, 129, 165, 166];
        $arr['ldsp_matrica'] = [80, 81, 82];

        $mat_arr = [];
        $mat_arr['ldsp_fresh'] = 17;
        $mat_arr['ldsp_optima'] = 16;
        $mat_arr['ldsp_matrica'] = 14;

        foreach ($arr as $key => $val) {
            $items = $this->common_model->get_items_by_id('Catalogue_items', $val);
            print_pre($key);
            $material = $this->common_model->get_item_by_id('Material_types_items', $mat_arr[$key]);
            $material['data'] = json_decode($material['data'], true);

            $mat = $material['data']['variants']['v_16'];

            $i_mat = [];
            $i_mat['key'] = $key;
            $i_mat['id'] = $mat['id'];
            $i_mat['variant'] = 'v_16';


            foreach ($items as &$item) {
                $item['data'] = json_decode($item['data'], true);
                $item['data']['spec']['materials'][] = $i_mat;
                $children = json_encode($item['data']['children']);
                $children = str_replace('ldsp', $key, $children);
                $item['data']['children'] = json_decode($children);
                $item['data'] = json_encode($item['data']);

                $table_name = 'Catalogue_items';


                $item_id = $item['id'];

                $data['data'] = $item['data'];


                $this->common_model->update_where(
                    $table_name,
                    'id',
                    $item_id,
                    $data
                );


            }


        }


    }

    public function ren_mat_mod()
    {
        $items = $this->common_model->get_data_all_by_order('Model3d_items', 'ASC');

        $keys = json_decode(file_get_contents($_SERVER['DOCUMENT_ROOT'] . '/tmp.json'), true);

//        print_pre($keys);


        foreach ($items as &$item) {
            $data = json_decode($item['data'], true);
            $mats = $data['materials'];


            $new_mats = [];

            foreach ($data['materials'] as $key => $val) {
                if (isset($keys[$key])) {
                    $val['name'] = $keys[$key];
                }
                $new_mats[$key] = $val;
            }


            $data['materials'] = $new_mats;


            $upd = [
                'data' => json_encode($data)
            ];


            $this->common_model->update_where(
                'Model3d_items',
                'id',
                $item['id'],
                $upd
            );
        }

    }

    public function clean_links()
    {
        $items = $this->common_model->get_data_all_by_order('Catalogue_items', 'ASC');

        function recurse(&$arr)
        {

            foreach ($arr as &$item) {
                if(json_encode($item['spec']) == '[]'){
                    $item['spec'] = new stdClass();
                }

                if (isset($item['children'])) {
                    recurse($item['children']);
                }

            }


        }

        foreach ($items as &$item) {
            $data = json_decode($item['data'], true);
            recurse($data['children']);


            $upd = [
                'data' => json_encode($data)
            ];


            $this->common_model->update_where(
                'Catalogue_items',
                'id',
                $item['id'],
                $upd
            );

        }


    }

    public function clean_models()
    {
        $items = $this->common_model->get_data_all_by_order('Catalogue_items', 'ASC');

        function recurse(&$arr)
        {

            foreach ($arr as &$item) {
                if ($item['type'] == 'model') {
                    $temp = [];
                    $temp['id'] = $item['spec']['id'];
                    $temp['version'] = 2;
                    $item['spec'] = $temp;
                } else {
                    if (isset($item['children'])) {
                        recurse($item['children']);
                    }
                }
            }
        }

        foreach ($items as &$item) {
            $data = json_decode($item['data'], true);
            recurse($data['children']);

            $upd = [
                'data' => json_encode($data)
            ];

            $this->common_model->update_where(
                'Catalogue_items',
                'id',
                $item['id'],
                $upd
            );

        }
    }

    public function clean_materials()
    {
        $items = $this->common_model->get_data_all_by_order('Model3d_items', 'ASC');

        $keys = json_decode(file_get_contents($_SERVER['DOCUMENT_ROOT'] . '/tmp.json'), true);

//        print_pre($keys);


        foreach ($items as &$item) {
            $data = json_decode($item['data'], true);
            $mats = $data['materials'];


            $new_mats = [];

            $flag = 0;

            foreach ($data['materials'] as $key => $val) {
                if ($key == 'gen' && $val['selected'] == 'ldsp' && $val['mode'] == 'm' && $val['name'] == 'Основной материал') {
                    $flag = 1;
                    $val['mode'] = 'v';
                    $val['selected'] = null;
                    $val['params'] = json_decode('{"type":"Standard","params":{"icon":null,"alphaMap":null,"color":"#ffffff","map":null,"metalness":0,"normalMap":null,"roughness":1,"roughnessMap":null,"transparent":0,"opacity":1},"add_params":{"real_width":256,"real_height":256,"rotation":"normal","stretch_width":0,"stretch_height":0,"normal_scale":false,"wrapping":"mirror","normal_wrapping":"mirror","roughness_wrapping":"mirror"}}');

                }
                $new_mats[$key] = $val;
            }

            if($flag == 1){
                $data['materials'] = $new_mats;
                $upd = [
                    'data' => json_encode($data)
                ];
                $this->common_model->update_where(
                    'Model3d_items',
                    'id',
                    $item['id'],
                    $upd
                );
            }





        }



    }

    public function replace_mats()
    {
//        $id_arr = [191,192,193,194,195,196,197,199,200,201,202,203,204,205,206,207,208,209,210,213,217,331];
        $id_arr = [22, 23, 24, 25, 26, 27, 28, 29, 30, 31, 32, 33, 34, 35, 36, 37];
        $items = $this->common_model->get_items_by_id('Catalogue_items', $id_arr);

        function recurse(&$arr)
        {
            foreach ($arr as &$item) {
                if ($item['type'] == 'wall') {
                    $tmp = json_encode($item);
                    $tmp = str_replace('ldsp', 'ldsp_korpus', $tmp);
                    $item = json_decode($tmp, true);
//                    $item['material']['key'] = 'ldsp_garderob';


                } else {
                    if (isset($item['children'])) {
                        recurse($item['children']);
                    }
                }
            }
        }

        foreach ($items as &$item){

            $data = json_decode($item['data'], true);

            $params = '{
                "key": "ldsp_korpus",
                "group": 0,
                "fixed": 0,
                "label": "ЛДСП корпус",
                "order": 0,
                "variant": "v_16",
                "id": "7962"
               }';


            $data['spec']['materials'] = array_filter($data['spec']['materials'], function ($element) {
                return $element['key'] != "ldsp" && $element['key'] != 'ldsp_korpus';
            });

            $data['spec']['materials'][] = json_decode($params, true);

            $tmp = json_encode($data['children']);
            $tmp = str_replace('ldsp', 'ldsp_korpus', $tmp);
            $data['children'] = json_decode($tmp, true);

//            recurse($data['children']);
            $upd = [
                'data' => json_encode($data)
            ];
            $this->common_model->update_where(
                'Catalogue_items',
                'id',
                $item['id'],
                $upd
            );
            print_pre($data);

        }



    }

    public function replace_specs()
    {
        $items = $this->common_model->get_data_all_by_order('Catalogue_items', 'ASC');

        function recurse(&$arr)
        {
            foreach ($arr as &$item) {
                if ($item['type'] == 'section') {
                    if(!isset($item['spec']['drag_enabled'])){
                        $item['spec']['drag_enabled'] = false;
                        $item['spec']['drag_single'] = false;
                        $item['spec']['drag_items'] = [];
                    }
                }

                if (isset($item['children'])) {
                    recurse($item['children']);
                }

            }
        }

        foreach ($items as &$item) {
            $data = json_decode($item['data'], true);
            recurse($data['children']);

            $upd = [
                'data' => json_encode($data)
            ];
            $this->common_model->update_where(
                'Catalogue_items',
                'id',
                $item['id'],
                $upd
            );
        }
    }

    public function replace_matrix()
    {
//        $id_arr = [191,192,193,194,195,196,197,199,200,201,202,203,204,205,206,207,208,209,210,213,217,331];
        $id_arr = [78,83,84,85,86,87];
        $items = $this->common_model->get_items_by_id('Catalogue_items', $id_arr);


        function recurse(&$arr)
        {
            foreach ($arr as &$item) {
                if ($item['type'] == 'section' && strpos($item['name'], 'Ячейка') !== false) {
                    $item['spec'] = [];
                    $item['spec']['drag_enabled'] = true;
                    $item['spec']['drag_single'] = true;
                    $item['spec']['drag_items'] = ["80","81","82"];
                }

                if (isset($item['children'])) {
                    recurse($item['children']);
                }

            }
        }

        foreach ($items as &$item){

            $data = json_decode($item['data'], true);
            recurse($data['children']);

            $upd = [
                'data' => json_encode($data)
            ];
            $this->common_model->update_where(
                'Catalogue_items',
                'id',
                $item['id'],
                $upd
            );


        }



    }

    public function ag_default_mats()
    {
        $items = $this->common_model->get_data_all_by_order('Catalogue_items', 'ASC');



        foreach ($items as &$item) {
            $data = json_decode($item['data'], true);



            foreach($data['spec']['materials'] as &$mat){
                if($mat['label'] == 'ЛДСП корпус' || $mat['label'] == 'ЛДСП'){
                    if($mat['fixed'] == 0){
                        $mat['default'] = 1;
                    }

                }
            }

            print_pre($data);

            $upd = [
                'data' => json_encode($data)
            ];

            $this->common_model->update_where(
                'Catalogue_items',
                'id',
                $item['id'],
                $upd
            );

        }
    }


    private function get_root($type)
    {
        switch ($type) {
            case 'files':
                $root_dir = dirname(FCPATH) . DIRECTORY_SEPARATOR . 'files' . DIRECTORY_SEPARATOR;
                break;
            case 'view_files':
                $root_dir = dirname(FCPATH) . DIRECTORY_SEPARATOR . 'models' . DIRECTORY_SEPARATOR;
                break;
            case 'view_images':
                $root_dir = dirname(FCPATH) . DIRECTORY_SEPARATOR . 'images' . DIRECTORY_SEPARATOR;
                break;
            case 'common':
                $root_dir = $_SERVER['DOCUMENT_ROOT'] . DIRECTORY_SEPARATOR . 'common_assets' . DIRECTORY_SEPARATOR . 'assets' . DIRECTORY_SEPARATOR;
                break;
            default:
                echo json_encode('error wrong type');
                exit;
        }

        return $root_dir;

    }


}