<?php
defined('BASEPATH') OR exit('No direct script access allowed');


class Api extends CI_Controller {

    public function __construct()
    {
        parent::__construct();
    }

    function download_zip(){

    }

    public function apply_data()
    {

        if(!isset($_GET['sync_key'])){
            echo 'no key';
            exit;
        }

        if($_GET['sync_key'] != crypt($this->config->item('username'), 'RTKtGEh%d$f4`r6w1*zCsdg53sq234qs56')){
            echo 'keys don\'t match';
            exit;
        }

        $url = $this->config->item( 'const_path' ) . 'config/index.php/save_data';

        $data = array();
        $data['sync_key'] = $_GET['sync_key'];
        $data['save'] = 1;


        $options = array(
            'http' => array(
                'method'  => 'POST',
                'header'  => 'Content-type: application/x-www-form-urlencoded',
                'content' => http_build_query($data)
            )
        );
        $context  = stream_context_create($options);
        $result = file_get_contents($url, false, $context);
        echo $result;
//
//        $url2 = $this->config->item( 'const_path' ) . 'config/index.php/accessories/save_data_ajax';
//        $options2 = array(
//            'http' => array(
//                'method'  => 'GET',
//                'content' => http_build_query(array())
//            )
//        );
//        $context  = stream_context_create($options);
//        $result = file_get_contents($url2, false, $context);
    }


    public function get_categories($model_name)
    {
        if(!isset($_GET['sync_key'])){
            echo 'no key';
            exit;
        }

        if($_GET['sync_key'] != crypt($this->config->item('username'), 'RTKtGEh%d$f4`r6w1*zCsdg53sq234qs56')){
            echo 'keys don\'t match';
            exit;
        }
        if($model_name == 'modules_set') $model_name = 'module_sets';

        $model = $model_name.'_model';


        $this->load->model($model);

        if(isset($_GET['setId'])){
            echo json_encode($this->$model->get_categories_by_set_id($_GET['setId']));
        } else {
            echo json_encode($this->$model->get_categories());
        }


    }

    public function add_categories($model_name)
    {
        if(!isset($_POST['json']) && !isset($_POST['data']) ){
            echo 'no data';
            exit;
        }

        if(!isset($_POST['sync_key'])){
            echo 'no key';
            exit;
        }

        if($_POST['sync_key'] != crypt($this->config->item('username'), 'RTKtGEh%d$f4`r6w1*zCsdg53sq234qs56')){
            echo 'keys don\'t match';
            exit;
        }
        if($model_name == 'modules_sets') $model_name = 'module_sets';

        $model = $model_name . '_model';
        $this->load->model($model);


        $data = json_decode($_POST['data'], true);

        $errors = array();

        $added = [];
        $failed = [];
        $updated = [];

        foreach ($data as $input){

            if(!isset($input['name'])){
                $errors[] = $input;
                continue;
            }

            $item_id = 0;

            if($input['id'] != null){
                $item_id = $input['id'];
            }

            if(!isset($input['parent'])) $input['parent'] = 0;
            if(!isset($input['active'])) $input['active'] = 1;

            $add = array();
            $add['name'] = $input['name'];
            $add['parent'] = $input['parent'];
            $add['active'] = $input['active'];

            if($model_name == 'module_sets'){
                if(isset($_POST['setId'])){
                    $add['set_id'] = $_POST['setId'];
                }
            }


            if(isset($input['order'])) $add['order'] = $input['order'];
            if(isset($input['description'])) $add['description'] = $input['description'];


            try {
                if($item_id == 0){
                    $id = $this->$model->add_category_ajax($add);
                    $temp = $input;
                    $temp['id'] = (int)$id;
                    $added[] = $temp;
                } else {

                    if(!empty($this->$model->get_category($input['id']))){
                        $this->$model->update_category_ajax($input['id'], $add);
                        $temp = $input;
                        $temp['id'] = (int)$item_id;
                        $updated[] = $temp;
                    } else {
                        $failed[] = $input;
                    }


                }

            } catch (Exception $e) {
                $failed[] = $input;
            }

        }

        if(count($added) == 0) $added = null;
        if(count($updated) == 0) $updated = null;
        if(count($failed) == 0) $failed = null;

        $result = array();
        $result['added'] = $added;
        $result['updated'] = $updated;
        $result['failed'] = $failed;


        echo json_encode($result);

    }

    public function update_categories($model_name)
    {
        if(!isset($_POST['json']) && !isset($_POST['data']) ){
            echo 'no data';
            exit;
        }

        if(!isset($_POST['sync_key'])){
            echo 'no key';
            exit;
        }

        if($_POST['sync_key'] != crypt($this->config->item('username'), 'RTKtGEh%d$f4`r6w1*zCsdg53sq234qs56')){
            echo 'keys don\'t match';
            exit;
        }

        $model = $model_name . '_model';
        $this->load->model($model);

        $data = json_decode($_POST['data'], true);

        $errors = array();

        foreach ($data as $input){

            if(!isset($input['id'])){
                $errors[] = $input;
                continue;
            }

            $add = array();
            $add['name'] = $input['name'];
            $add['parent'] = $input['parent'];
            $add['active'] = $input['active'];
            if(isset($input['order'])) $add['order'] = $input['order'];
            if(isset($input['description'])) $add['description'] = $input['description'];

            try {
                $this->$model->update_category_ajax($input['id'], $add);
            } catch (Exception $e) {
                $errors[] = $input;
            }
        }

        if(count($errors)){
            echo json_encode(['result' => 'error', 'failed'=>$errors]);
        } else {
            echo json_encode(['result' => 'success', 'failed' => null]);
        }

    }


    public function get_items($model_name)
    {

        if(!isset($_GET['sync_key'])){
            echo 'no key';
            exit;
        }

        if($_GET['sync_key'] != crypt($this->config->item('username'), 'RTKtGEh%d$f4`r6w1*zCsdg53sq234qs56')){
            echo 'keys don\'t match';
            exit;
        }

        switch ($model_name) {
            case 'materials':
                $this->get_materials();
                break;
            case 'materials_v1':
                $this->get_old_materials();
                break;
            case "facades":
                $this->get_facades();
                break;
            case "handles":
                $this->get_handles();
                break;
            case "accessories":
                $this->get_accessories();
                break;
            case "modules":
                $this->get_modules();
                break;
            case "modules_set":
                $this->get_modules_set($_GET['setId']);
                break;
            case "tech":
                $this->get_tech();
                break;
            case "interior":
                $this->get_interior();
                break;
            case "comms":
                $this->get_comms();
                break;
            case "kitchen_models":
                $this->get_kitchen_models();
                break;
            case "orders":
                $this->get_clients_orders();
                break;
        }
    }

    private function get_materials(){
        $this->load->model('materials_model');
        $materials = $this->materials_model->get_all_items();
        $result = [];
        foreach ($materials as $material){

            $item['id'] = (int)$material['id'];
            $item['name'] = $material['name'];
            $item['code'] = $material['code'];
            $item['active'] = (int)$material['active'];
            $item['category'] = (int)$material['category'];
            $item['order'] = (int)$material['order'];
            $item['params'] = array();
            $item['params']['color'] = $material['color'];
            $item['params']['roughness'] = (float)($material['roughness']);
            $item['params']['metalness'] = (float)$material['metalness'];
            $item['params']['transparent'] = (int)$material['transparent'];
            $item['params']['map'] = $material['map'];
            $item['add_params'] = array();
            $item['add_params']['real_width'] = (float)$material['real_width'];
            $item['add_params']['real_height'] = (float)$material['real_height'];
            $item['add_params']['stretch_width'] = (int)$material['stretch_width'];
            $item['add_params']['stretch_height'] = (int)$material['stretch_height'];
            $item['add_params']['wrapping'] = $material['wrapping'];

            $result[] = $item;

        };

        echo json_encode($result);

    }

    private function get_old_materials(){
        $this->load->model('materials_model');
        echo json_encode($this->materials_model->get_all_items());
    }

    private function get_facades(){
        $this->load->model('facades_model');

        $data = $this->facades_model->get_all_items();
        foreach ($data as &$item){
            $item = json_decode($item['data'], true);

            if(isset($item['types'])){
                $item['types'] = (object) $item['types'];
                foreach ($item['types'] as &$type){
                    foreach ($type['items'] as &$it){
                        $it['min_width'] = (int)$it['min_width'];
                        $it['min_height'] = (int)$it['min_height'];
                    }
                }
            }

            if(isset($item['types_double'])){
                $item['types_double'] = (object) $item['types_double'];
                foreach ($item['types_double'] as &$type){
                    foreach ($type['items'] as &$it){
                        $it['min_width'] = (int)$it['min_width'];
                        $it['min_height'] = (int)$it['min_height'];
                    }
                }
            }

            if(isset($item['types_triple'])){
                $item['types_triple'] = (object) $item['types_triple'];
                foreach ($item['types_triple'] as &$type){
                    foreach ($type['items'] as &$it){
                        $it['min_width'] = (int)$it['min_width'];
                        $it['min_height'] = (int)$it['min_height'];
                    }
                }
            }

            if(isset($item['prices'])){
                foreach ($item['prices'] as &$type){
                    foreach ($type as &$it){
                        $it['price'] = (float)$it['price'];
                        $it['parent'] = (int)$it['parent'];
                    }
                }
            }

        }
        echo json_encode($data);
    }

    public function get_old_facades()
    {
        if(!isset($_GET['sync_key'])){
            echo 'no key';
            exit;
        }

        if($_GET['sync_key'] != crypt($this->config->item('username'), 'RTKtGEh%d$f4`r6w1*zCsdg53sq234qs56')){
            echo 'keys don\'t match';
            exit;
        }

        $data = $this->facades_model->get_all_items();
        $res = array();
        foreach ($data as &$item){

            if(!$item['data']){
                $res[] = $item;
            }



        }
        echo json_encode($res);


    }

    private function get_handles(){
        $this->load->model('handles_model');

        $data = $this->handles_model->get_all_items();

        foreach ($data as &$item){
            $item['material'] = json_decode($item['material']);
            $item['variants'] = json_decode($item['variants']);
        }

        echo json_encode($data);
    }

    private function get_accessories(){
        $this->load->model('accessories_model');
        echo json_encode($this->accessories_model->get_all());
    }

    private function get_modules(){
        $this->load->model('modules_model');

        $data = $this->modules_model->get_all_items();

        foreach ($data as &$item){
            $item['params'] = json_decode($item['params'], true)['params'];
        }

        echo json_encode($data);
    }

    private function get_modules_set($set_id){
        $this->load->model('module_sets_model');

        $data = $this->module_sets_model->get_all_items_by_set_id($set_id);

        foreach ($data as &$item){
            $item['params'] = json_decode($item['params'], true)['params'];
            if(isset($item['params']['doors'])){
                foreach ($item['params']['doors'] as &$door){
                    $door = (object)$door;
                }
            }
        }



        echo json_encode($data);
    }

    public function get_sets()
    {
        $this->load->model('module_sets_model');
        echo json_encode($this->module_sets_model->get_sets());
    }

    private function get_tech(){
        $this->load->model('tech_model');
        echo json_encode($this->tech_model->get_all_items());
    }

    private function get_interior(){
        $this->load->model('interior_model');
        echo json_encode($this->interior_model->get_all_items());
    }

    private function get_comms(){
        $this->load->model('comms_model');
        echo json_encode($this->comms_model->get_all_items());
    }

    private function get_kitchen_models(){
        $this->load->model('kitchen_models_model');
        echo json_encode($this->kitchen_models_model->get_all_items());
    }

    private function get_clients_orders(){
        $this->load->model('clients_orders_model');
        echo json_encode($this->clients_orders_model->get_all());
    }

    public function add_items($model_name)
    {
        if(!isset($_POST['sync_key'])){
            echo json_encode('no key');
            exit;
        }

        if($_POST['sync_key'] != crypt($this->config->item('username'), 'RTKtGEh%d$f4`r6w1*zCsdg53sq234qs56')){
            echo json_encode('keys don\'t match');
            exit;
        }

        if(!isset($_POST['json']) && !isset($_POST['data']) ){
            echo json_encode('no data');
            exit;
        }


        switch ($model_name) {
            case 'materials':
                $this->add_materials();
                break;
            case "facades":
                $this->add_facades();
                break;
            case "handles":
                $this->add_handles();
                break;
            case "accessories":
                $this->add_accessories();
                break;
            case "modules":
                $this->add_modules();
                break;
            case "modules_set":
                $this->add_modules_set();
                break;
            case "tech":
                $this->add_models($model_name);
                break;
            case "interior":
                $this->add_models($model_name);
                break;
            case "comms":
                $this->add_models($model_name);
                break;
            case "kitchen_models":
                $this->add_kitchen_models($model_name);
                break;
        }
    }

    public function add_items_test($model_name)
    {
        print_pre($_POST);

        if(!isset($_POST['sync_key'])){
            echo json_encode('no key');
            exit;
        }

        if($_POST['sync_key'] != crypt($this->config->item('username'), 'RTKtGEh%d$f4`r6w1*zCsdg53sq234qs56')){
            echo json_encode('keys don\'t match');
            exit;
        }

        if(!isset($_POST['json']) && !isset($_POST['data']) ){
            echo json_encode('no data');
            exit;
        }



    }

    private function add_materials(){
        $this->load->model('materials_model');
        $data = json_decode($_POST['data'], true);

        $added = [];
        $failed = [];
        $updated = [];

        $ext_arr = array();
        $ext_arr[] = '.jpg';
        $ext_arr[] = '.png';
        $ext_arr[] = '.gif';

        $base_data = array();
        $base_data['id'] = null;
        $base_data['name'] = '';
        $base_data['code'] = '';
        $base_data['active'] = 1;
        $base_data['category'] = 0;
        $base_data['order'] = 100000;
        $base_data['params'] = array();
        $base_data['params']['color'] = '#ffffff';
        $base_data['params']['roughness'] = 0.8;
        $base_data['params']['metalness'] = 0;
        $base_data['params']['transparent'] = 0;
        $base_data['params']['map'] = null;
        $base_data['add_params'] = array();
        $base_data['add_params']['real_width'] = 256;
        $base_data['add_params']['real_height'] = 256;
        $base_data['add_params']['stretch_width'] = 0;
        $base_data['add_params']['stretch_height'] = 0;
        $base_data['add_params']['wrapping'] = 'repeat';

        file_put_contents($_SERVER['DOCUMENT_ROOT'] .'/logs/add_materials_' . basename($this->config->item( 'const_path' )) . '_' . time() . '.txt', print_r($data, true));

        $in_d = [];

        foreach ($data as $input){
            $item_id = 0;
            $update_flag = 0;



            if(empty($input)){
                $failed[] = $input;
                continue;
            }


            $input_data = array_replace_recursive($base_data, $input);

            $in_d[] = $input_data;


            if($input_data['id'] != null){
                $item_data = $this->materials_model->get_item($input_data['id']);
                if(!empty($item_data)){
                    $update_flag = 1;
                    $item_id = $input['id'];
                }
            }
            if($item_id == 0) $item_id = $this->materials_model->add_item_id_only();

            $save_path = dirname(FCPATH).'/images/materials/' . $item_id;

            if(!empty($input_data['params']['map'])){
                $input_data['params']['map'] = download_file($input_data['params']['map'], $item_id);
            } else {
                foreach ($ext_arr as $ext){
                    if(file_exists($save_path . $ext)) unlink( $save_path . $ext);
                }
            }


            $data = array();
            $data['name'] = $input_data['name'] != null ? $input_data['name'] : '';
            $data['code'] = $input_data['code'] != null ? $input_data['code'] : '';
            $data['category'] = $input_data['category'] != null ? $input_data['category'] : 0;
            $data['active'] = $input_data['active'] != null ? $input_data['active'] : 1;
            $data['order'] = $input_data['order'] != null ? $input_data['order'] : 100000;

            $data['color'] = $input_data['params']['color'] != null ? $input_data['params']['color'] : '#ffffff';
            $data['roughness'] = $input_data['params']['roughness'] != null ? $input_data['params']['roughness'] : 0.8;
            $data['metalness'] = $input_data['params']['metalness'] != null ? $input_data['params']['metalness'] : 0;
            $data['transparent'] = $input_data['params']['transparent'] != null ? (int)$input_data['params']['transparent'] : 0;
            $data['map'] = $input_data['params']['map'];


            $data['real_width'] = $input_data['add_params']['real_width'] != null ? (int)$input_data['add_params']['real_width'] : 256;
            $data['real_height'] = $input_data['add_params']['real_height'] != null ? (int)$input_data['add_params']['real_height'] : 256;
            $data['stretch_width'] = $input_data['add_params']['stretch_width'] != null ? (int)$input_data['add_params']['stretch_width'] : 0;
            $data['stretch_height'] = $input_data['add_params']['stretch_height'] != null ? (int)$input_data['add_params']['stretch_height'] : 0;
            $data['wrapping'] = $input_data['add_params']['wrapping'] != null ? $input_data['add_params']['wrapping'] : 'repeat';


            try {
                $this->materials_model->update_item_data($item_id, $data);

                if($update_flag == 1){
                    $updated[] = $input;
                } else {
                    $temp = $input;
                    $temp['id'] = (int)$item_id;
                    $added[] = $temp;
                }

            } catch (Exception $e) {
                $this->materials_model->remove_items($item_id, $data);
                $failed[] = $input;
            }

        }

        file_put_contents($_SERVER['DOCUMENT_ROOT'] .'/logs/add_materials_conv' . basename($this->config->item( 'const_path' )) . '_' . time() . '.txt', print_r($data, true));

        if(count($added) == 0) $added = null;
        if(count($updated) == 0) $updated = null;
        if(count($failed) == 0) $failed = null;

        $result = array();
        $result['added'] = $added;
        $result['updated'] = $updated;
        $result['failed'] = $failed;


        echo json_encode($result);
    }

    private function add_facades(){
        $this->load->model('facades_model');
        $data = json_decode($_POST['data'], true);
        $errors = array();

        $added = [];
        $failed = [];
        $updated = [];

        $base_data = array();
        $base_data['id'] = null;
        $base_data['name'] = '';
        $base_data['active'] = 1;
        $base_data['category'] = 0;
        $base_data['icon'] = '';
        $base_data['materials'] = array();
        $base_data['order'] = 10000;
        $base_data['prices'] = new stdClass();
        $base_data['handle'] = 1;
        $base_data['handle_offset'] = 30;
        $base_data['types'] =  new stdClass();
        $base_data['types_double'] =  new stdClass();
        $base_data['types_triple'] =  new stdClass();
        $base_data['double_offset'] = 240;
        $base_data['triple_decor_model'] =  new stdClass();
        $base_data['compatibility_types'] =  new stdClass();
        $base_data['additional_materials'] =  new stdClass();



        foreach ($data as &$input){

            $item_id = 0;
            $update_flag = 0;


            $facade_data = array_replace_recursive($base_data, $input);

            if($facade_data['materials'] == null){
                $failed[] = $input;
                continue;
            }
            if($facade_data['types'] == null) $facade_data['types'] = new stdClass();
            if($facade_data['types_double'] == null) $facade_data['types_double'] = new stdClass();
            if($facade_data['types_triple'] == null) $facade_data['types_triple'] = new stdClass();
            if($facade_data['triple_decor_model'] == null) $facade_data['triple_decor_model'] = new stdClass();
            if($facade_data['triple_decor_model'] == null) $facade_data['compatibility_types'] = new stdClass();
            if($facade_data['additional_materials'] == null) $facade_data['additional_materials'] = new stdClass();

            if($facade_data['id'] != null){
                $item_data = $this->facades_model->get_item($facade_data['id']);
                if(!empty($item_data)){
                    $update_flag = 1;
                    $item_id = $facade_data['id'];
                }

            }


            if($item_id == 0) $item_id = $this->facades_model->add_item_id();



            if(!empty($facade_data['icon'])) {
                $facade_data['icon'] = download_file_facade($facade_data['icon'], $item_id);
            }

            $facade_data['id'] = $item_id;


            foreach ($facade_data['types'] as $key=>&$type){
                if(!empty($type['icon'])) $type['icon'] = download_file_facade($type['icon'], $item_id);
                foreach ($type['items'] as &$item){
                    if(!empty($item['model'])) $item['model'] = download_file_facade($item['model'], $item_id);
                }
            }

            foreach ($facade_data['types_double'] as &$type){
                if(!empty($type['icon'])) $type['icon'] = download_file_facade($type['icon'], $item_id);
                foreach ($type['items'] as &$item){
                    if(!empty($item['model'])) $item['model'] = download_file_facade($item['model'], $item_id);
                }
            }

            foreach ($facade_data['types_triple'] as &$type){
                if(!empty($type['icon'])) $type['icon'] = download_file_facade($type['icon'], $item_id);
                foreach ($type['items'] as &$item){
                    if(!empty($item['model'])) $item['model'] = download_file_facade($item['model'], $item_id);
                }
            }

            if(!empty($facade_data->triple_decor_model->model)) $facade_data->triple_decor_model->model = download_file_facade($facade_data->triple_decor_model->model, $item_id);

            $facade_data['types'] = (object) $facade_data['types'];
            $facade_data['types_double'] = (object) $facade_data['types_double'];
            $facade_data['types_triple'] = (object) $facade_data['types_triple'];
            $facade_data['compatibility_types'] = (object) $facade_data['compatibility_types'];
//            $facade_data['prices'] = null;

            $facade_data['materials'] = array_map('strval',$facade_data['materials']);
            $res_data = array();
            $res_data['name'] = $facade_data['name'];
            $res_data['category'] = $facade_data['category'];
            $res_data['active'] = 1;
            if(!empty($facade_data['icon'])) $res_data['icon'] = $facade_data['icon'];
            $res_data['materials'] = json_encode($facade_data['materials']);
            $res_data['order'] = $facade_data['order'];
            $res_data['data'] = json_encode($facade_data);



//            print_pre(json_encode($facade_data,JSON_FORCE_OBJECT));
//            continue;

            $input['types'] = (object) $input['types'];
            $input['types_double'] = (object) $input['types_double'];
            $input['types_triple'] = (object) $input['types_triple'];


            try {

                $this->facades_model->update_item($item_id, $res_data);

                if($update_flag == 1){
                    $updated[] = $input;
                } else {
                    $temp = $input;
                    $temp['id'] = (int)$item_id;
                    $added[] = $temp;
                }
            } catch (Exception $e) {
                $this->facades_model->remove_items($item_id);
                $failed[] = $input;
            }

        }

        if(count($added) == 0) $added = null;
        if(count($updated) == 0) $updated = null;
        if(count($failed) == 0) $failed = null;

        $result = array();
        $result['added'] = $added;
        $result['updated'] = $updated;
        $result['failed'] = $failed;

        echo json_encode($result);
    }

    private function add_handles(){
        $this->load->model('handles_model');
        $data = json_decode($_POST['data'], true);


        $added = [];
        $failed = [];
        $updated = [];

        $ext_arr = array();
        $ext_arr[] = '.jpg';
        $ext_arr[] = '.png';
        $ext_arr[] = '.gif';
        $ext_arr[] = '.fbx';

        foreach ($data as $input){

            $item_id = 0;
            $update_flag = 0;

            $input_data = $input;

            if($input_data['id'] != null){
                $item_data = $this->handles_model->get_item($input_data['id']);
                if(!empty($item_data)){
                    $update_flag = 1;
                    $item_id = $input['id'];
                }
            }
            if($item_id == 0) $item_id = $this->handles_model->add_item();

            $data = array();
            $data['name'] = $input['name'];
            $data['category'] = $input['category'];

            if(!empty($input['icon'])){
                $data['icon'] = download_file_handle($input['icon'], $item_id);
            }

            if(!empty($input['model'])){
                $data['model'] = download_file_handle($input['model'], $item_id);
            }
            $data['material'] = json_encode($input['material']);
            $data['variants'] = json_encode($input['variants']);
            $data['type'] = $input['type'];
            $data['order'] = $input['order'];
            $data['active'] = 1;

            try {
//                $this->handles_model->add_item_data($item_id, $data);

                $this->handles_model->add_item_data($item_id, $data);

                if($update_flag == 1){
                    $updated[] = $input;
                } else {
                    $temp = $input;
                    $temp['id'] = (int)$item_id;
                    $added[] = $temp;
                }

            } catch (Exception $e) {
                $this->handles_model->remove_items($item_id, $data);
                $failed[] = $input;
            }

        }

        if(count($added) == 0) $added = null;
        if(count($updated) == 0) $updated = null;
        if(count($failed) == 0) $failed = null;

        $result = array();
        $result['added'] = $added;
        $result['updated'] = $updated;
        $result['failed'] = $failed;


        echo json_encode($result);
    }

    private function add_accessories(){
        $this->load->model('accessories_model');

        $current_accessories = $this->accessories_model->get_all();

        $input_data = json_decode($_POST['data'], true);

        $update_arr = array();
        $add_arr = array();

        $added = [];
        $failed = [];
        $updated = [];


        foreach ($input_data as $item){

            $flag = 1;

            foreach ($current_accessories as $current_item){
                if($current_item['code'] == $item['code']){
                    $item['id'] = $current_item['id'];
                    $update_arr[] = $item;
                    $flag = 0;
                }
            }

            if($flag == 1){
                $add_arr[] = $item;
            }
        }

        foreach ($update_arr as $item){
            $data = array();
            $data['name'] = $item['name'];
            $data['code'] = $item['code'];
            $data['category'] = $item['category'];
            $data['description'] = $item['description'];
            $data['price'] = $item['price'];
            $data['images'] = $item['images'];
            $data['tags'] = $item['tags'];
            $data['type'] = $item['type'];
            if(isset($item['default'])){
                $data['default'] = $item['default'];
            } else {
                $data['default'] = 0;
            }

            try {
                $this->accessories_model->update($item['id'], $data );
                $updated[] = $item;
            } catch (Exception $e) {
                $failed[] = $item;
            }
        }

        foreach ($add_arr as $item){
            $data = array();
            $data['name'] = $item['name'];
            $data['code'] = $item['code'];
            $data['category'] = $item['category'];
            $data['description'] = $item['description'];
            $data['price'] = $item['price'];
            $data['images'] = $item['images'];
            $data['tags'] = $item['tags'];
            $data['type'] = $item['type'];
            if(isset($item['default'])){
                $data['default'] = $item['default'];
            } else {
                $data['default'] = 0;
            }


            try {
                $res = $this->accessories_model->add($data);
                $item['id'] = (int)json_decode($res, true)['id'];
                $added[] = $item;
            } catch (Exception $e) {
                $failed[] = $item;
            }

        }

        if(count($added) == 0) $added = null;
        if(count($updated) == 0) $updated = null;
        if(count($failed) == 0) $failed = null;

        $result = array();
        $result['added'] = $added;
        $result['updated'] = $updated;
        $result['failed'] = $failed;

        echo json_encode($result);
    }

    private function add_modules(){
        $this->load->model('modules_model');
        $data = json_decode($_POST['data'], true);

        $added = [];
        $failed = [];
        $updated = [];

        $ext_arr = array();
        $ext_arr[] = '.jpg';
        $ext_arr[] = '.png';
        $ext_arr[] = '.gif';

        foreach ($data as $input){
            $item_id = 0;
            $update_flag = 0;


            $input_data = $input;



            if(!empty($input_data['id'])){
                $item_data = $this->modules_model->get_item($input_data['id']);
                if(!empty($item_data)){
                    $update_flag = 1;
                    $item_id = $input['id'];
                }

            }
            if($item_id == 0) $item_id = $this->modules_model->add_item();

            $save_path = dirname(FCPATH).'/images/modules_icons/' . $item_id;

            if(!empty($input_data['icon'])){
                $input_data['icon'] = download_file_module($input_data['icon'], $item_id);
            } else {
                foreach ($ext_arr as $ext){
                    if(file_exists($save_path . $ext)) unlink( $save_path . $ext);
                }
            }

            if(!isset($input_data['active'])) $input_data['active'] = 1;
            if(!isset($input_data['order'])) $input_data['order'] = $item_id * 100;
            if(!isset($input_data['category'])) $input_data['category'] = 0;
            if(!isset($input_data['icon'])) $input_data['icon'] = '';
            if(!isset($input_data['params'])) $input_data['params'] = new stdClass();

            $dta = array();
            $dta['category'] = $input_data['category'];
            $dta['icon'] = $input_data['icon'];
            $dta['params'] = json_encode(array('params'=>$input_data['params']));
            $dta['active'] = $input_data['active'];
            $dta['order'] = $input_data['order'];


            try {
                $this->modules_model->add_item_data($item_id, $dta);

                if($update_flag == 1){
                    $updated[] = $input;
                } else {
                    $temp = $input;
                    $temp['id'] = (int)$item_id;
                    $added[] = $temp;
                }

            } catch (Exception $e) {
                $this->modules_model->remove_items($item_id, $dta);
                $failed[] = $input;
            }

        }

        if(count($added) == 0) $added = null;
        if(count($updated) == 0) $updated = null;
        if(count($failed) == 0) $failed = null;

        $result = array();
        $result['added'] = $added;
        $result['updated'] = $updated;
        $result['failed'] = $failed;


        echo json_encode($result);
    }

    private function add_modules_set(){
        $this->load->model('module_sets_model');
        $data = json_decode($_POST['data'], true);

        $added = [];
        $failed = [];
        $updated = [];

        $ext_arr = array();
        $ext_arr[] = '.jpg';
        $ext_arr[] = '.png';
        $ext_arr[] = '.gif';

        foreach ($data as $input){
            $item_id = 0;
            $update_flag = 0;

            if(!isset($_POST['setId']) || empty($_POST['setId'])) {
                $failed[] = $input;
                continue;
            }

            $input_data = $input;



            if(!empty($input_data['id'])){
                $item_data = $this->module_sets_model->get_item($input_data['id']);
                if(!empty($item_data)){
                    $update_flag = 1;
                    $item_id = $input['id'];
                }

            }
            if($item_id == 0) $item_id = $this->module_sets_model->add_item();

            $save_path = dirname(FCPATH).'/images/modules_icons/' . $item_id;

            if(!empty($input_data['icon'])){
                $input_data['icon'] = download_file_module_set($input_data['icon'], $item_id, $_POST['setId']);
            } else {
                foreach ($ext_arr as $ext){
                    if(file_exists($save_path . $ext)) unlink( $save_path . $ext);
                }
            }

            if(!isset($input_data['active'])) $input_data['active'] = 1;

            if(!isset($input_data['order'])) $input_data['order'] = $item_id * 100;
            if(!isset($input_data['category'])) $input_data['category'] = 0;
            if(!isset($input_data['icon'])) $input_data['icon'] = '';
            if(!isset($input_data['params'])) $input_data['params'] = new stdClass();

            $dta = array();
            $dta['category'] = $input_data['category'];
            $dta['icon'] = $input_data['icon'];
            $dta['params'] = json_encode(array('params'=>$input_data['params']));
            $dta['active'] = $input_data['active'];
            $dta['order'] = $input_data['order'];
            $dta['set_id'] = $_POST['setId'];


            try {
                $this->module_sets_model->add_item_data($item_id, $dta);

                if($update_flag == 1){
                    $updated[] = $input;
                } else {
                    $temp = $input;
                    $temp['id'] = (int)$item_id;
                    $added[] = $temp;
                }

            } catch (Exception $e) {
                $this->module_sets_model->remove_items($item_id, $dta);
                $failed[] = $input;
            }

        }

        if(count($added) == 0) $added = null;
        if(count($updated) == 0) $updated = null;
        if(count($failed) == 0) $failed = null;

        $result = array();
        $result['added'] = $added;
        $result['updated'] = $updated;
        $result['failed'] = $failed;


        echo json_encode($result);
    }

    public function add_sets()
    {

        if(!isset($_POST['json']) && !isset($_POST['data']) ){
            echo 'no data';
            exit;
        }

        if(!isset($_POST['sync_key'])){
            echo 'no key';
            exit;
        }

        if($_POST['sync_key'] != crypt($this->config->item('username'), 'RTKtGEh%d$f4`r6w1*zCsdg53sq234qs56')){
            echo 'keys don\'t match';
            exit;
        }



        $this->load->model('module_sets_model');
        $data = json_decode($_POST['data'], true);

        $added = [];
        $failed = [];
        $updated = [];

        foreach ($data as $input){

            if(!isset($input['name'])){
                $errors[] = $input;
                continue;
            }

            $item_id = 0;

            if($input['id'] != null){
                $item_id = $input['id'];
            }

            $add = array();
            $add['name'] = $input['name'];

            try {
                if($item_id == 0){
                    $id = $this->module_sets_model->add_set($add);
                    $temp = $input;
                    $temp['id'] = (int)$id;
                    $added[] = $temp;
                } else {
                    $this->module_sets_model->edit_set($input['id'], $add);
                    $temp = $input;
                    $temp['id'] = (int)$item_id;
                    $updated[] = $temp;
                }

            } catch (Exception $e) {
                $failed[] = $input;
            }

        }




        if(count($added) == 0) $added = null;
        if(count($updated) == 0) $updated = null;
        if(count($failed) == 0) $failed = null;

        $result = array();
        $result['added'] = $added;
        $result['updated'] = $updated;
        $result['failed'] = $failed;

        echo json_encode($result);

    }

    private function add_models($model_name){

        $model = $model_name.'_model';
        $this->load->model($model);

        $data = json_decode($_POST['data'], true);

        $added = [];
        $failed = [];
        $updated = [];

        $ext_arr = array();
        $ext_arr[] = '.jpg';
        $ext_arr[] = '.png';
        $ext_arr[] = '.gif';
        $ext_arr[] = '.fbx';

        foreach ($data as $input){

            $item_id = 0;
            $update_flag = 0;

//            $item_id = $this->$model->add_item();

            $input_data = $input;

            if($input_data['id'] != null){
                $item_data = $this->$model->get_item($input_data['id']);
                if(!empty($item_data)){
                    $update_flag = 1;
                    $item_id = $input['id'];
                }

            }
            if($item_id == 0) $item_id = $this->$model->add_item();

            $data = array();
            $data['name'] = $input['name'];
            $data['category'] = $input['category'];
            $data['group'] = $input['group'];
            $data['drag_mode'] = $input['drag_mode'];
            $data['sizes_available'] = $input['sizes_available'];

            $data['model'] = '';
            $data['icon'] = '';

            if(!empty($input['icon'])){
                $data['icon'] = download_file_model($input['icon'], $item_id, $model_name);
            }

            if(!empty($input['model'])){
                $data['model'] = download_file_model($input['model'], $item_id, $model_name);
            }
            $data['material'] = $input['material'];
            $data['variants'] = $input['variants'];

            if(!empty($data['material']['params']['map'])){
                $data['material']['params']['map'] = download_file_model($input['model'], $item_id, $model_name);
            }

            if(!empty($data['variants'])){
                foreach ($data['variants'] as &$var){
                    if(!empty($var['model'])){
                        $var['model'] = download_file_model($input['model'], $item_id, $model_name);
                    }
                }
            }



            $data['order'] = json_encode($input['order']);
            $data['active'] = 1;

            $add_data = array();
            $add_data['name'] = $data['name'];
            $add_data['category'] = $data['category'];
            $add_data['active'] = $data['active'];
            $add_data['icon'] = $data['icon'];
            $add_data['model'] = $data['model'];
            $add_data['material'] = json_encode($data['material']);
            $add_data['variants'] = json_encode($data['variants']);
            $add_data['group'] = $data['group'];
            $add_data['model_data'] = json_encode($data);

            try {
//                $this->handles_model->add_item_data($item_id, $data);

                $this->$model->add_item_data($item_id, $add_data);

                if($update_flag == 1){
                    $updated[] = $input;
                } else {
                    $temp = $input;
                    $temp['id'] = (int)$item_id;
                    $added[] = $temp;
                }

            } catch (Exception $e) {
                $this->$model->remove_items($item_id);
                $failed[] = $input;
            }

        }

        if(count($added) == 0) $added = null;
        if(count($updated) == 0) $updated = null;
        if(count($failed) == 0) $failed = null;

        $result = array();
        $result['added'] = $added;
        $result['updated'] = $updated;
        $result['failed'] = $failed;


        echo json_encode($result);
    }

    private function add_kitchen_models($model_name){
        $this->load->model('kitchen_models_model');
        $data = json_decode($_POST['data'], true);

        $added = [];
        $failed = [];
        $updated = [];

        $ext_arr = array();
        $ext_arr[] = '.jpg';
        $ext_arr[] = '.png';
        $ext_arr[] = '.gif';

        foreach ($data as $input){
            $item_id = 0;
            $update_flag = 0;
            $input_data = $input;



            if(!empty($input_data['id'])){
                $item_data = $this->kitchen_models_model->get_one($input_data['id']);
                if(!empty($item_data)){
                    $update_flag = 1;
                    $item_id = $input['id'];
                }

            }
            if($item_id == 0) $item_id = $this->kitchen_models_model->add();


            if(!empty($input_data['icon'])){
                $input_data['icon'] = download_file_kitchen_model($input_data['icon'], $item_id);
            }


            if(!isset($input_data['active'])) $input_data['active'] = 1;
            if(!isset($input_data['icon'])) $input_data['icon'] = '';
            if(!isset($input_data['has_handle'])) $input_data['no_handle'] = 0;

            if($input_data['has_handle'] == 1){
                $input_data['no_handle'] = 0;
            } else {
                $input_data['no_handle'] = 1;
            }

            $dta = $input_data;
            $dta['corpus_materials_top'] = json_encode($input_data['corpus_materials']);
            unset($dta['corpus_materials']);
            unset($dta['has_handle']);
            unset($dta['id']);
            $dta['facades_categories'] = json_encode($input_data['facades_categories']);
            $dta['cokol_materials'] = json_encode($input_data['cokol_materials']);
            $dta['tabletop_materials'] = json_encode($input_data['tabletop_materials']);
            $dta['wallpanel_materials'] = json_encode($input_data['wallpanel_materials']);
            $dta['wallpanel_active'] = 0;
            $dta['wallpanel_height'] = 550;
            $dta['allow_wallpanel_materials_select'] = 1;

            if(isset($dta['price_data'])) $dta['price_data'] = json_encode($dta['price_data']);



            try {
                $this->kitchen_models_model->update($item_id, $dta);

                if($update_flag == 1){
                    $updated[] = $input;
                } else {
                    $temp = $input;
                    $temp['id'] = (int)$item_id;
                    $added[] = $temp;
                }

            } catch (Exception $e) {
                $this->kitchen_models_model->remove($item_id, $dta);
                $failed[] = $input;
            }

        }

        if(count($added) == 0) $added = null;
        if(count($updated) == 0) $updated = null;
        if(count($failed) == 0) $failed = null;

        $result = array();
        $result['added'] = $added;
        $result['updated'] = $updated;
        $result['failed'] = $failed;


        echo json_encode($result);
    }


    public function update_items($model_name){

        if(!isset($_POST['json']) && !isset($_POST['data']) ){
            echo 'no data';
            exit;
        }

        if(!isset($_POST['sync_key'])){
            echo 'no key';
            exit;
        }

        if($_POST['sync_key'] != crypt($this->config->item('username'), 'RTKtGEh%d$f4`r6w1*zCsdg53sq234qs56')){
            echo 'keys don\'t match';
            exit;
        }

        switch ($model_name) {
            case 'materials':
                $this->update_materials();
                break;
            case "facades":
                break;
            case "handles":
                break;
            case "accessories":
        }
    }

    private function update_materials(){
        $this->load->model('materials_model');



        $data = json_decode($_POST['data'], true);


        file_put_contents($_SERVER['DOCUMENT_ROOT'] .'/logs/update_materials_' . basename($this->config->item( 'const_path' )) . '_' . time() . '.txt', print_r($data, true));


        $err = [];

        $ext_arr = array();
        $ext_arr[] = '.jpg';
        $ext_arr[] = '.png';
        $ext_arr[] = '.gif';


        foreach ($data as $input){
            if(!$input['id']){
                $err[] = $input;
            }
            $item_id = $input['id'];

            if(empty($this->materials_model->get_item($item_id))){
                $err[] = $input;
                continue;
            }

            $save_path = dirname(FCPATH).'/images/materials/' . $item_id;

            $data = array();
            $data['name'] = $input['name'];
            $data['code'] = $input['code'];
            $data['category'] = $input['category'];
            $data['active'] = 1;

            $data['color'] = $input['params']['color'];
            $data['roughness'] = $input['params']['roughness'];
            $data['metalness'] = $input['params']['metalness'];
            $data['transparent'] = $input['params']['transparent'];


            $data['real_width'] = $input['add_params']['real_width'];
            $data['real_height'] = $input['add_params']['real_height'];
            $data['stretch_width'] = $input['add_params']['stretch_width'];
            $data['wrapping'] = $input['add_params']['wrapping'];



            if(!empty($input['params']['map'])){

                if ($input['params']['map'] === 'delete') {
                    foreach ($ext_arr as $ext) {
                        if (file_exists($save_path . $ext)) unlink($save_path . $ext);
                    }
                } else {
                    $data['map'] = download_file($input['params']['map'], $item_id);
                }


            } else {
//                foreach ($ext_arr as $ext){
//                    if(file_exists($save_path . $ext)) unlink( $save_path . $ext);
//                }
            }




            try {
                $this->materials_model->update_item_data($item_id, $data);
            } catch (Exception $e) {
                $err[] = $input;
            }

        }

        if(count($err)){
            echo json_encode(['result' => 'error', 'failed'=>$err]);
        } else {
            echo json_encode(['result' => 'success', 'failed' => null]);
        }
    }



    public function update_items_code($model_name){

        if(!isset($_POST['json']) && !isset($_POST['data']) ){
            echo 'no data';
            exit;
        }

        if(!isset($_POST['sync_key'])){
            echo 'no key';
            exit;
        }

        if($_POST['sync_key'] != crypt($this->config->item('username'), 'RTKtGEh%d$f4`r6w1*zCsdg53sq234qs56')){
            echo 'keys don\'t match';
            exit;
        }

        switch ($model_name) {
            case 'materials':
                $this->update_materials_code();
                break;
            case "facades":
                break;
            case "handles":
                break;
            case "accessories":
        }
    }

    private function update_materials_code(){
        $this->load->model('materials_model');



        $data = json_decode($_POST['data'], true);

        $err = [];

        $ext_arr = array();
        $ext_arr[] = '.jpg';
        $ext_arr[] = '.png';
        $ext_arr[] = '.gif';


        foreach ($data as $input){
            if(!$input['code']){
                $err[] = $input;
            }


            $it = $this->materials_model->get_item_by_code($input['code']);

            if(empty($it)){
                $err[] = $input;
                continue;
            }

            $item_id = $it['id'];


            $save_path = dirname(FCPATH).'/images/materials/' . $item_id;

            $data = array();
            $data['name'] = $input['name'];
            $data['code'] = $input['code'];
            $data['category'] = $input['category'];
            $data['active'] = 1;

            $data['color'] = $input['params']['color'];
            $data['roughness'] = $input['params']['roughness'];
            $data['metalness'] = $input['params']['metalness'];
            $data['transparent'] = $input['params']['transparent'];


            $data['real_width'] = $input['add_params']['real_width'];
            $data['real_height'] = $input['add_params']['real_height'];
            $data['stretch_width'] = $input['add_params']['stretch_width'];
            $data['wrapping'] = $input['add_params']['wrapping'];

            if(!empty($input['params']['map'])){
                $data['map'] = download_file($input['params']['map'], $item_id);
            } else {
                foreach ($ext_arr as $ext){
                    if(file_exists($save_path . $ext)) unlink( $save_path . $ext);
                }
            }




            try {
                $this->materials_model->update_item_data($item_id, $data);
            } catch (Exception $e) {
                $err[] = $input;
            }

        }

        if(count($err)){
            echo json_encode(['result' => 'error', 'failed'=>$err]);
        } else {
            echo json_encode(['result' => 'success', 'failed' => null]);
        }
    }


    public function remove_categories($model_name)
    {
        $chk = $this->check_post();

        if($chk != 'ok'){
            echo $chk;
            exit;
        }

        $data = json_decode($_POST['data'], true);
        if(is_array($data)){
            $model = $model_name.'_model';
            $this->load->model($model);
            $this->$model->remove_category($data);
        } else {
            echo 'data is not an array';
        }



    }

    public function remove_items($model_name)
    {
        $chk = $this->check_post();

        if($chk != 'ok'){
            echo $chk;
            exit;
        }

        $data = json_decode($_POST['data'], true);
        if(is_array($data)){
            $model = $model_name.'_model';
            $this->load->model($model);
            $this->$model->remove_items($data);

        } else {
            echo json_encode(array('msg' => 'data is not an array', 'data'=>$data));
        }

    }

    private function remove_materials(){

    }

    private function remove_facades(){

    }

    private function remove_handles(){

    }

    private function remove_accessories(){

    }

    public function clear_categories($model_name)
    {
        if(!isset($_GET['sync_key'])){
            echo 'no key';
            exit;
        }

        if($_GET['sync_key'] != crypt($this->config->item('username'), 'RTKtGEh%d$f4`r6w1*zCsdg53sq234qs56')){
            echo 'keys don\'t match';
            exit;
        }

        $model = $model_name.'_model';
        $this->load->model($model);
        $this->$model->clear_categories();
    }

    public function clear_items($model_name)
    {
        if(!isset($_GET['sync_key'])){
            echo 'no key';
            exit;
        }

        if($_GET['sync_key'] != crypt($this->config->item('username'), 'RTKtGEh%d$f4`r6w1*zCsdg53sq234qs56')){
            echo 'keys don\'t match';
            exit;
        }

        $model = $model_name.'_model';
        $this->load->model($model);
        $this->$model->clear_items();

    }


    public function test_post()
    {
        print_pre($_POST);

//        $data = json_decode($_POST['data'], true);
//        foreach ($data as $input){
//            print_pre($input);
//        }

        exit;
    }

    public function test_extend()
    {
        $arr = array();

        $arr['name'] = '';
        $arr['types'] = array();

        $arr2 = array();
        $arr2['name'] = '123';


        $arr3 = array_replace_recursive($arr, $arr2);
        print_pre($arr3);

    }


    public function materials_add()
    {

        if(!isset($_POST['json']) && !isset($_POST['data']) ){
            echo 'no data';
            exit;
        }

        if(!isset($_POST['sync_key'])){
            echo 'no key';
            exit;
        }

        if($_POST['sync_key'] != crypt($this->config->item('username'), 'RTKtGEh%d$f4`r6w1*zCsdg53sq234qs56')){
            echo 'keys don\'t match';
            exit;
        }

        $this->load->model('materials_model');

        $data = json_decode($_POST['data'], true);

        $err = [];

        foreach ($data as $input){
            $item_id = $this->materials_model->add_item();

            $data = array();
            $data['name'] = $input['name'];
            $data['code'] = $input['code'];
            $data['category'] = $input['category'];
            $data['active'] = 1;

            $data['color'] = $input['params']['color'];
            $data['roughness'] = $input['params']['roughness'];
            $data['metalness'] = $input['params']['metalness'];
            $data['transparent'] = $input['params']['transparent'];


            $data['real_width'] = $input['add_params']['real_width'];
            $data['real_height'] = $input['add_params']['real_height'];
            $data['stretch_width'] = $input['add_params']['stretch_width'];
            $data['wrapping'] = $input['add_params']['wrapping'];

            if(!empty($input['params']['map'])){
                $data['map'] = download_file($input['params']['map'], $item_id);
            }


            try {
                $this->materials_model->update_item_data($item_id, $data);
            } catch (Exception $e) {
                $this->materials_model->remove_items($item_id, $data);
                $err[] = $input['code'];
            }

        }

        if(count($err)){
            echo json_encode(['result' => 'error', 'failed'=>$err]);
        } else {
            echo json_encode(['result' => 'success', 'failed' => null]);
        }

    }

    public function get_materials_categories()
    {
        if(!isset($_GET['sync_key'])){
            echo 'no key';
            exit;
        }

        if($_GET['sync_key'] != crypt($this->config->item('username'), 'RTKtGEh%d$f4`r6w1*zCsdg53sq234qs56')){
            echo 'keys don\'t match';
            exit;
        }

        $this->load->model('materials_model');

        echo json_encode($this->materials_model->get_categories());

    }

    public function add_materials_categories()
    {
        if(!isset($_POST['json']) && !isset($_POST['data']) ){
            echo 'no data';
            exit;
        }

        if(!isset($_POST['sync_key'])){
            echo 'no key';
            exit;
        }

        if($_POST['sync_key'] != crypt($this->config->item('username'), 'RTKtGEh%d$f4`r6w1*zCsdg53sq234qs56')){
            echo 'keys don\'t match';
            exit;
        }

        $this->load->model('materials_model');

        $data = json_decode($_POST['data'], true);

        $result = array();

        foreach ($data as $input){

            $add = array();
            $add['name'] = $input['name'];
            $add['parent'] = $input['parent'];
            $add['active'] = $input['active'];
            if(isset($input['order'])) $add['order'] = $input['order'];


            $id = $this->materials_model->add_category_data($add);

            $result[] = array(
                "id" => $id,
                "name" => $input['name']
            );

        }

        echo json_encode($result);

    }

    public function accessories() {

        if(!isset($_POST['data']) ){
            echo 'no data';
            exit;
        }

        if(!isset($_POST['sync_key'])){
            echo 'no key';
            exit;
        }

        if($_POST['sync_key'] != crypt($this->config->item('username'), 'RTKtGEh%d$f4`r6w1*zCsdg53sq234qs56')){
            echo 'keys don\'t match';
            exit;
        }


        $this->load->model('accessories_model');

        $current_accessories = $this->accessories_model->get_all();


        $input_data = json_decode($_POST['data'], true);

        $update_arr = array();
        $add_arr = array();

        $result = array();
        $result['result'] = 'success';
        $result['updated'] = array();
        $result['added'] = array();
        $result['failed'] = array();




        foreach ($input_data as $item){

            $flag = 1;

            foreach ($current_accessories as $current_item){

                if($current_item['code'] == $item['code']){
                    $item['id'] = $current_item['id'];
                    $update_arr[] = $item;
                    $flag = 0;
                }
            }

            if($flag == 1){
                $add_arr[] = $item;
            }
        }

        foreach ($update_arr as $item){
            $data = array();
            $data['name'] = $item['name'];
            $data['code'] = $item['code'];
            $data['category'] = $item['category'];
            $data['description'] = $item['description'];
            $data['price'] = $item['price'];
            $data['images'] = $item['images'];
            $data['tags'] = $item['tags'];
            $data['type'] = $item['type'];
            $data['default'] = 0;

            try {
                $this->accessories_model->update($item['id'], $data );
                $result['updated'][] = $item['code'];
            } catch (Exception $e) {
                $result['failed'][] = $item['code'];
            }
        }

        foreach ($add_arr as $item){
            $data = array();
            $data['name'] = $item['name'];
            $data['code'] = $item['code'];
            $data['category'] = $item['category'];
            $data['description'] = $item['description'];
            $data['price'] = $item['price'];
            $data['images'] = $item['images'];
            $data['tags'] = $item['tags'];
            $data['type'] = $item['type'];
            $data['default'] = 0;

            try {
                $this->accessories_model->add($data );
                $result['added'][] = $item['code'];
            } catch (Exception $e) {
                $result['failed'][] = $item['code'];
            }

        }

        if(count($result['failed']) > 0){
            $result['result'] = 'error';
        } else {
            $result['failed'] = null;
        }

        echo json_encode($result);
    }


    public function update_kitchen_model_prices()
    {
        if(!isset($_POST['sync_key'])){
            echo 'no key';
            exit;
        }

        if($_POST['sync_key'] != crypt($this->config->item('username'), 'RTKtGEh%d$f4`r6w1*zCsdg53sq234qs56')){
            echo 'keys don\'t match';
            exit;
        }

        $this->load->model('kitchen_models_model');

        $id = $_POST['id'];
        $data['price_data'] = $_POST['data'];

        $this->kitchen_models_model->update($id, $data);

        $result = array();
        $result['added'] = [];
        $result['updated'] = [$id];
        $result['failed'] = [];


        echo json_encode($result);

    }


    private function check_post(){
        if(!isset($_POST['json']) && !isset($_POST['data']) ){
            return 'no data';

        }

        if(!isset($_POST['sync_key'])){
            return 'no key';

        }

        if($_POST['sync_key'] != crypt($this->config->item('username'), 'RTKtGEh%d$f4`r6w1*zCsdg53sq234qs56')){
            return 'keys don\'t match';

        }

        return 'ok';
    }


    public function clear_models(){
        $this->load->model('common_model');

        $table_names = [
            'interior', 'tech', 'comms'
        ];

        foreach ($table_names as $name){
            $table_name = ucfirst($name) . '_items';
            $items = $this->common_model->get_data_all_by_order($table_name, 'ASC');

            foreach ($items as $item){

                $id = $item['id'];



                $model_path = dirname(FCPATH) . '/models/' . $name . '/' . $id ;
//                print_pre(scandir($model_path));

                if(file_exists($model_path)){

                    $files = array_diff(scandir($model_path), array('.', '..'));


//                    foreach ($files as &$file){
//                        $file = iconv("UTF-8","Windows-1251",$file);
//                    }
//                    print_pre($files);

                    $used_files = [];

                    $used_files[basename($item['icon'])] = basename($item['icon']);

                    if(!empty($item['model_data'])){
                        $data = json_decode($item['model_data'], true);
                        foreach ($data['variants'] as $variant){
                            $used_files[basename($variant['model'])] = basename($variant['model']);
                        }
                        if($data['material']['params']['map']){
                            $used_files[basename($data['material']['params']['map'])] = basename($data['material']['params']['map']);
                        }
                    }

                    print_pre($files);
                    print_pre($used_files);

                    $to_del = [];

                    foreach ($files as $file){
                        if(!isset($used_files[$file])){
                            $to_del[] = $file;
                        }
                    }
//                    print_pre('to_del');
                    print_pre($to_del);

                    foreach ($to_del as $del){
                        unlink($model_path . '/' . $del);
                    }

                }






            }

//            print_pre($items);

        }



    }




    public function get_project_settings(){}

    public function set_project_settings(){}


    public function prices($method, $key = false){


        switch ($method) {
            case 'get':
                if(!isset($_GET['sync_key'])){
                    echo 'no key';
                    exit;
                }

                if($_GET['sync_key'] != crypt($this->config->item('username'), 'RTKtGEh%d$f4`r6w1*zCsdg53sq234qs56')){
                    echo 'keys don\'t match';
                    exit;
                }


                $this->prices_get();
                break;
            case "update":
                $chk = $this->check_post();
                if($chk != 'ok'){
                    echo $chk;
                    exit;
                }
                if($key == false){
                    $this->prices_update();
                } else {
                    switch ($key){
                        case 'corpus':
                            $this->prices_update_corpus();
                            break;
                        default:
                            $this->prices_update_by_key($key);
                    }
                }
                break;

        }


    }

    private function prices_get(){
        $this->load->model('prices_model');

        $data = $this->prices_model->get();
        $data = json_decode($data['data'], true);

        unset($data['v2']);
        foreach ($data as $key=>&$val){
            if($key == 'corpus'){
                foreach ($val as &$v) {
                    foreach ($v as &$item) {
                        unset($item['order']);
                        unset($item['active']);
                        unset($item['children']);
                        unset($item['description']);
                    }
                }
            } else {
                foreach ($val as &$item){
                    unset($item['order']);
                    unset($item['active']);
                    unset($item['children']);
                    unset($item['description']);
                }
            }

        }

        echo json_encode($data);

    }
    private function prices_update(){
        $this->load->model('prices_model');
        $data = json_decode($_POST['data'], true);
        $data['v2'] = true;

        $res = array();
        $res['data'] = json_encode($data);

        $this->prices_model->update($res);

        echo json_encode($data);
    }

    private function prices_update_corpus(){
        $this->load->model('prices_model');

        $result = array();
        $result['result'] = 'success';
        $result['updated'] = [];
        $result['failed'] = [];

        $data = json_decode($_POST['data'], true);
        $current_prices = json_decode($this->prices_model->get()['data'], true);
        $corp_prices = $current_prices['corpus'];
        foreach ($data as $key=>$val){
            if(!isset($corp_prices[$key])){
                $result['failed'][] = [$key => $data[$key]];
                continue;
            }
            foreach ($corp_prices[$key] as &$v){
                if(isset($val[$v['id']])){
                    $v['price'] = $val[$v['id']];
                    $result['updated'][$key][$v['id']] = $val[$v['id']];
                    unset($data[$key][$v['id']]);
                }
            }
        }

        $current_prices['corpus'] = $corp_prices;

        $res = array();
        $res['data'] = json_encode($current_prices);

        $this->prices_model->update($res);

        foreach ($data as $key=>$val){
            if(empty($val)) unset($data[$key]);
        }

        $result['failed'] = $data;

        if(count($result['failed']) > 0){
            $result['result'] = 'error';
        }

        echo json_encode($result);




    }

    private function prices_update_by_key($key){
        $this->load->model('prices_model');



        $result = array();
        $result['result'] = 'success';
        $result['updated'] = [];
        $result['failed'] = [];
        $data = json_decode($_POST['data'], true);
        $current_prices = json_decode($this->prices_model->get()['data'], true);

        if(!isset($current_prices[$key])){
            $result['result'] = 'error';
            echo json_encode($result);
            exit;
        }

        $prices = $current_prices[$key];


        foreach ($prices as &$v){
            if(isset($data[$v['id']])){
                $v['price'] = $data[$v['id']];
                $result['updated'][$v['id']] = $data[$v['id']];
                unset($data[$v['id']]);
            }
        }

        $result['failed'] = $data;

        if(count($result['failed']) > 0){
            $result['result'] = 'error';
        }

        $current_prices[$key] = $prices;

        $res = array();
        $res['data'] = json_encode($current_prices);
        $this->prices_model->update($res);


        echo json_encode($result);


    }

    public function get_prices_template(){

    }


    public function test_download(){

        $url = 'https://planplace.ru/common_assets/images/materials/4142.jpg';
        $item_id = 4104;

        if(strpos($url, "http") !== 0){
            return $url;
        }



        $file_name = basename($url);
        $ext ='.' . file_ext($file_name);

        $save_path = dirname(FCPATH).'/images/materials/';
        $abs_save_path = 'images/materials/';
        if (!is_dir($save_path)) { mkdir($save_path); }

        if($ext != ''){

//        $ch = curl_init($url);

            $save_file_loc = $save_path . $item_id . $ext;

//        $fp = fopen($save_file_loc, 'wb');
//        curl_setopt($ch, CURLOPT_FILE, $fp);
//        curl_setopt($ch, CURLOPT_HEADER, 0);
//        curl_exec($ch);
//        curl_close($ch);
//        fclose($fp);


            file_put_contents($save_file_loc, file_get_contents($url));
            return $abs_save_path . $item_id . $ext;
        }


    }


    public function set_custom_prices()
    {


        if(!isset($_POST['sync_key'])){
            echo 'no key';
            exit;
        }

        if($_POST['sync_key'] != crypt($this->config->item('username'), 'RTKtGEh%d$f4`r6w1*zCsdg53sq234qs56')){
            echo 'keys don\'t match';
            exit;
        }

        if(!isset($_POST['format'])){
            echo 'no_format';
            exit;
        }

        if(!isset($_POST['account'])){
            echo 'no account';
            exit;
        }


        switch ($_POST['format']) {
            case 'xlsx':
                $this->set_cp_xlsx();
                break;
            case "json":
                $this->set_cp_json();
                break;
            default:
                $this->set_cp_json();
        }

//        echo 'success';


    }

    private function set_cp_xlsx(){
        if(!isset($_FILES)){
            echo 'no files';
        }
        $res = array();
        $save_path = dirname(FCPATH).'/data/custom_input/';
        if(!is_dir($save_path)) mkdir($save_path);
        foreach ($_FILES as $file){
            $new_file_name = $save_path . $file['name'];
            if (move_uploaded_file($file['tmp_name'], $new_file_name)) {
                $res[$file['name']] = 'ok';
            } else {
                $res[$file['name']] = 'error';
            }


        }



        echo json_encode($res);

    }
    private function set_cp_json(){
        if(!isset($_POST['data'])){
            echo 'no data';
            exit;
        }
        $save_path = dirname(FCPATH).'/data/custom_input/';
        if(!is_dir($save_path)) mkdir($save_path);
        $result = 'success';
        try {
            file_put_contents($save_path.'data.json', json_encode(json_decode($_POST['data']), JSON_UNESCAPED_UNICODE));
            error_reporting(0);
            if(file_exists($_SERVER['DOCUMENT_ROOT'] . '/clients/' . basename(dirname(FCPATH)) . '/parser.php')){
                include $_SERVER['DOCUMENT_ROOT'] . '/clients/' . basename(dirname(FCPATH)) . '/parser.php';
            }
        } catch (Exception $e) {
            $result = 'error';
        }

        echo $result;
    }

    public function custom_input_test()
    {
        if(!isset($_POST['sync_key'])){
            echo 'no key';
            exit;
        }

        if($_POST['sync_key'] != crypt($this->config->item('username'), 'RTKtGEh%d$f4`r6w1*zCsdg53sq234qs56')){
            echo 'keys don\'t match';
            exit;
        }

        if(!isset($_POST['format'])){
            echo 'no_format';
            exit;
        }

        if(!isset($_POST['account'])){
            echo 'no account';
            exit;
        }


        switch ($_POST['format']) {
            case 'xlsx':
                $this->set_cp_xlsx();
                break;
            case "json":
                $this->set_cp_json_test();
                break;
            default:
                $this->set_cp_json_test();
        }

//        echo 'success';


    }
    private function set_cp_json_test(){
        if(!isset($_POST['data'])){
            echo 'no data';
            exit;
        }
        $save_path = dirname(FCPATH).'/data/custom_input_test/';
        if(!is_dir($save_path)) mkdir($save_path);
        $result = 'success';
        try {
            file_put_contents($save_path.'data.json', json_encode(json_decode($_POST['data']), JSON_UNESCAPED_UNICODE|JSON_PRETTY_PRINT));
//            error_reporting(0);
            if(file_exists($_SERVER['DOCUMENT_ROOT'] . '/clients/' . basename(dirname(FCPATH)) . '/parser_test.php')){
                include $_SERVER['DOCUMENT_ROOT'] . '/clients/' . basename(dirname(FCPATH)) . '/parser_test.php';
            }
        } catch (Exception $e) {
            $result = 'error';
        }

        echo $result;
    }

}

function download_file($url, $item_id){
    if(strpos($url, "http") !== 0){
        return $url;
    }
    $file_name = basename($url);
    $ext ='.' . file_ext($file_name);

    $save_path = dirname(FCPATH).'/images/materials/';
    $abs_save_path = 'images/materials/';
    if (!is_dir($save_path)) { mkdir($save_path); }

    if($ext != ''){
//        $ch = curl_init($url);

        $save_file_loc = $save_path . $item_id . $ext;
//        $fp = fopen($save_file_loc, 'wb');
//        curl_setopt($ch, CURLOPT_FILE, $fp);
//        curl_setopt($ch, CURLOPT_HEADER, 0);
//        curl_exec($ch);
//        curl_close($ch);
//        fclose($fp);
        file_put_contents($save_file_loc, file_get_contents($url));
        return $abs_save_path . $item_id . $ext;
    }

    return null;
}

function download_file_facade($url, $item_id){

    if(strpos($url, "http") !== 0){
        return $url;
    }

    $file_name = basename($url);
    $ext ='.' . file_ext($file_name);

    $save_path = dirname(FCPATH).'/models/facades/' . $item_id . '/';

    $uniq = uniqid();


    $abs_save_path = 'models/facades/' . $item_id . '/';
    if (!is_dir($save_path)) { mkdir($save_path); }

    if($ext != ''){
//        $ch = curl_init($url);

        $save_file_loc = $save_path . $item_id . '_' . $uniq . $ext;
        file_put_contents($save_file_loc, file_get_contents($url));
//        $fp = fopen($save_file_loc, 'wb');
//        curl_setopt($ch, CURLOPT_FILE, $fp);
//        curl_setopt($ch, CURLOPT_HEADER, 0);
//        curl_exec($ch);
//        curl_close($ch);
//        fclose($fp);
        return $abs_save_path . $item_id . '_' . $uniq . $ext;
    }

    return null;
}

function download_file_handle($url, $item_id){
    if(strpos($url, "http") !== 0){
        return $url;
    }
    $file_name = basename($url);
    $ext ='.' . file_ext($file_name);

    $save_path = dirname(FCPATH).'/models/handles/';
    $abs_save_path = 'models/handles/';
    if (!is_dir($save_path)) { mkdir($save_path); }

    if($ext != ''){
//        $ch = curl_init($url);

        $save_file_loc = $save_path . $item_id . $ext;
        file_put_contents($save_file_loc, file_get_contents($url));
//        $fp = fopen($save_file_loc, 'wb');
//        curl_setopt($ch, CURLOPT_FILE, $fp);
//        curl_setopt($ch, CURLOPT_HEADER, 0);
//        curl_exec($ch);
//        curl_close($ch);
//        fclose($fp);
        return $abs_save_path . $item_id . $ext;
    }

    return null;
}

function download_file_module($url, $item_id){
    if(strpos($url, "http") !== 0){
        return $url;
    }
    $file_name = basename($url);
    $ext ='.' . file_ext($file_name);

    $save_path = dirname(FCPATH).'/images/module_sets_modules_icons/';
    $abs_save_path = 'images/module_sets_modules_icons/';
    if (!is_dir($save_path)) { mkdir($save_path); }

    if($ext != ''){
        $save_file_loc = $save_path . $item_id . $ext;
        file_put_contents($save_file_loc, file_get_contents($url));
        return $abs_save_path . $item_id . $ext;
    }

    return null;
}

function download_file_module_set($url, $item_id, $set_id){
    if(strpos($url, "http") !== 0){
        return $url;
    }
    $file_name = basename($url);
    $ext ='.' . file_ext($file_name);

    $save_path = dirname(FCPATH).'/images/module_sets_modules_icons/';
    $abs_save_path = 'images/module_sets_modules_icons/';
    if (!is_dir($save_path)) { mkdir($save_path); }

    if($ext != ''){
        $save_file_loc = $save_path . $set_id . '_' . $item_id . $ext;;
        file_put_contents($save_file_loc, file_get_contents($url));
        return $abs_save_path . $set_id . '_' . $item_id . $ext;
    }

    return null;
}

function download_file_model($url, $item_id, $name){
    if(strpos($url, "http") !== 0){
        return $url;
    }
    $file_name = basename($url);
    $ext ='.' . file_ext($file_name);

    if(!is_dir(dirname(FCPATH) . '/files/api/')){ mkdir(dirname(FCPATH) . '/files/api/'); }
    if(!is_dir(dirname(FCPATH) . '/files/api/models/')){ mkdir(dirname(FCPATH) . '/files/api/models/'); }

    $save_path = dirname(FCPATH) . '/files/api/models/';
    $abs_save_path = 'files/api/models/';


    $uniq = uniqid();

    if($ext != ''){
//        $ch = curl_init($url);

        $save_file_loc = $save_path . $item_id . '_' . $uniq . $ext;
        file_put_contents($save_file_loc, file_get_contents($url));
//        $fp = fopen($save_file_loc, 'wb');
//        curl_setopt($ch, CURLOPT_FILE, $fp);
//        curl_setopt($ch, CURLOPT_HEADER, 0);
//        curl_exec($ch);
//        curl_close($ch);
//        fclose($fp);
        return $abs_save_path . $item_id . '_' . $uniq . $ext;
    }

    return null;
}

function download_file_kitchen_model($url, $item_id){
    if(strpos($url, "http") !== 0){
        return $url;
    }
    $file_name = basename($url);
    $ext ='.' . file_ext($file_name);

    $save_path = dirname(FCPATH).'/images/kitchen_models/';
    $abs_save_path = 'images/kitchen_models/';
    if (!is_dir($save_path)) { mkdir($save_path); }

    if($ext != ''){
//        $ch = curl_init($url);

        $save_file_loc = $save_path . $item_id . $ext;
//        $fp = fopen($save_file_loc, 'wb');
//        curl_setopt($ch, CURLOPT_FILE, $fp);
//        curl_setopt($ch, CURLOPT_HEADER, 0);
//        curl_exec($ch);
//        curl_close($ch);
//        fclose($fp);
        file_put_contents($save_file_loc, file_get_contents($url));
        return $abs_save_path . $item_id . $ext;
    }

    return null;
}


function file_ext($filename) {
    return preg_match('/\./', $filename) ? preg_replace('/^.*\./', '', $filename) : '';
}



