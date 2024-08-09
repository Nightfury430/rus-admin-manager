<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Accessories extends CI_Controller {

    public function __construct()
    {
        parent::__construct();
        $this->load->helper('url_helper');
        $this->load->library('session');
        $this->load->model('Menu_model');

        if(!$this->session->username || $this->session->username != $this->config->item('username')){
            redirect('login', 'refresh');
        }
    }

    public function index_old()
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

	    $this->load->model('accessories_model');
		$data['items'] = $this->accessories_model->get_all();
		$data['types'] = $this->accessories_model->get_types_data();

	    $this->load->view('templates/header', $data);
        $this->load->view('/accessories/index', $data);
        $this->load->view('templates/footer', $data);
    }

    public function index()
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
        $data['js_include'] = [
        ];

        $data['css_include'] = [
        ];

        $data['include'] = 'accessories/index_new';
        $data['modules'] = [];
        $data['menus_list'] = $this->Menu_model->get_all_menus();
        $this->load->view('templates/layout', $data);

    }

	public function get_accessories_data() {
		$this->load->model('accessories_model');

		echo json_encode($this->accessories_model->get_all());
    }

	public function set_type_selection( $type, $value ) {
		$this->load->model( 'accessories_model' );
		$this->accessories_model->set_type_selection( $type, $value );
	}


	public function add_item( ) {
		if( isset($_POST) ){
			$this->load->model('accessories_model');
			echo $this->accessories_model->add($_POST['data']);
		}
    }

	public function update_item( ) {
		if( isset($_POST) ){
			$this->load->model('accessories_model');
			echo $this->accessories_model->update($_POST['id'], $_POST['data']);
		}
	}



	public function remove_item( ) {
		if( isset($_POST) ){
			$this->load->model('accessories_model');
			echo $this->accessories_model->remove($_POST['id']);
		}
	}

	public function set_default($id, $type, $checked) {
		$this->load->model('accessories_model');




			$cnt = $this->accessories_model->set_default($id, $type, $checked);


			if($cnt == 0){
				echo 'no';
				exit;
			}

			echo 'done';



//
//		if($this->accessories_model->check_default($type)){
//			$this->accessories_model->set_default($id, $type);
//		}
//
//		echo $this->accessories_model->check_default($type);
//    	exit;
//
//		$this->load->model('accessories_model');
//		$this->accessories_model->set_default($id, $type);
	}

	public function check_default( $type ) {
		$this->load->model('accessories_model');
		echo $this->accessories_model->check_default();
	}


	public function get_data_ajax() {
		$this->load->model('accessories_model');
		$data = array();
		$data['items'] = $this->accessories_model->get_all();
		$data['types'] = $this->accessories_model->get_types_data();

		$result = array();
		$result['default'] = array();
		$result['tree'] = array();
		$result['tags'] = array();
		$result['types'] = array();

		$result['items'] = array();



		$categories = array();
		$tags = array();

		$res_cat = array();

		foreach ($data['items'] as $item){

			$item['description'] = htmlspecialchars_decode($item['description']);


			$result['items'][$item['id']] = $item;

            $result['items'][$item['id']]['price'] = floatval(preg_replace('/\s+/', '', $item['price']));

			$categories[] = $item['category'];

			if($item['tags'] != ''){
				$tmp_tags = explode(',', $item['tags']);

				foreach ($tmp_tags as $tag){
					$tags[] = trim($tag);
				}
			}



			if($item['type'] != 'common'){
				$result['types'][$item['type']][$item['id']] = 0;
				if($item['default'] == "1"){
					$result['types'][$item['type']][$item['id']] = 1;
					$result['default'][$item['type']] = $item['id'];
				}
			}
		}

		foreach ($data['types'] as $key=>$val){
			if($val == "1"){
//				$result['types'][$key] = array();
//				$result['default'][$key] = '';
			} else {
//				$result['types'][$key] = null;
				$result['default'][$key] = null;
			}
		}

		$categories = array_values(array_unique($categories));
		$tags = array_values(array_unique($tags));

		foreach ($categories as $cat){
			$res_cat[] = array('category'=> $cat, 'items' => array());
		}

		foreach ($data['items'] as $item){
			foreach ($res_cat as $key=>$cat){
				if($item['category'] == $cat['category']){
					$res_cat[$key]['items'][] = $item['id'];
				}
			}
		}

		$result['tags'] = $tags;
		$result['tree'] = $res_cat;

        $this->load->model('constructor_model');
        $cs = $this->constructor_model->get();
//        print_pre($cs);



        file_put_contents(dirname(FCPATH).'/data/accessories.json', json_encode($result));

        if(empty($cs['accessories_sub_copy'])) $cs['accessories_sub_copy'] = 0;


        if($this->config->item('username') === 'leko-sale@yandex.ru' || $this->config->item('username') === '6011626@mail.ru' || $cs['accessories_sub_copy'] == 1){
            foreach ($this->config->item('sub_accounts') as $sub_acc){
                if($sub_acc == '') continue;
                if(is_dir(dirname(dirname(FCPATH)) . '/' . $sub_acc)){
                    file_put_contents(dirname(dirname(FCPATH)) . '/' . $sub_acc.'/data/accessories.json', json_encode($result));
                }
            }
        }

		echo json_encode($result);

	}


    public function save_data_ajax() {
        $this->load->model('accessories_model');

        $this->load->model('constructor_model');
        $cs = $this->constructor_model->get();

        $data = array();
        $data['items'] = $this->accessories_model->get_all();
        $data['types'] = $this->accessories_model->get_types_items_data();

        $result = array();
        $result['default'] = array();
        $result['tree'] = array();
        $result['tags'] = array();
        $result['types'] = array();
        $result['items'] = array();
        $result['type_names'] = array();


        foreach ($data['types'] as $type){
            $result['type_names'][$type['key']] = $type['name'];
        }



        $categories = array();
        $tags = array();

        $res_cat = array();

        foreach ($data['items'] as $item){

            $item['description'] = htmlspecialchars_decode($item['description']);


            $result['items'][$item['id']] = $item;

            $result['items'][$item['id']]['price'] = floatval(preg_replace('/\s+/', '', $item['price']));

            $categories[] = $item['category'];

            if($item['tags'] != ''){
                $tmp_tags = explode(',', $item['tags']);

                foreach ($tmp_tags as $tag){
                    $tags[] = trim($tag);
                }
            }



            if($item['type'] != 'common'){
                $result['types'][$item['type']][$item['id']] = 0;

            }
        }

        foreach ($data['types'] as $type){


            if($type['auto'] == 1){
                $result['default'][$type['key']] = $type['default'];
            } else {
                $result['default'][$type['key']] = null;
            }


        }



        $categories = array_values(array_unique($categories));
        $tags = array_values(array_unique($tags));

        foreach ($categories as $cat){
            $res_cat[] = array('category'=> $cat, 'items' => array());
        }



        foreach ($data['items'] as &$item){
            foreach ($res_cat as $key=>$cat){
                if($item['category'] == $cat['category']){
                    $res_cat[$key]['items'][] = $item['id'];
                }
            }
        }

        $result['tags'] = $tags;
        $result['tree'] = $res_cat;



        file_put_contents(dirname(FCPATH).'/data/accessories.json', json_encode($result));

//        if($this->config->item('username') === 'leko-sale@yandex.ru'){
//
//            $cs = json_decode($cs['settings'], true);
//
//
//            foreach ($result['items'] as &$item){
//                if($item['type'] == 'common'){
//                    $item['price'] = floatval($item['price']) * floatval($cs['price_modificator_acc']);
//                } else {
//                    $item['price'] = floatval($item['price']) * floatval($cs['price_modificator_furni']);
//                }
//            }
//        }



        if(!empty($cs['settings'])) $cs = json_decode($cs['settings'], true);
        if(empty($cs['accessories_sub_copy'])) $cs['accessories_sub_copy'] = 0;



        if($this->config->item('username') === 'leko-sale@yandex.ru' || $this->config->item('username') === '6011626@mail.ru' || $cs['accessories_sub_copy'] == 1){
            foreach ($this->config->item('sub_accounts') as $sub_acc){
                if($sub_acc == '') continue;
                if(is_dir(dirname(dirname(FCPATH)) . '/' . $sub_acc)){
                    file_put_contents(dirname(dirname(FCPATH)) . '/' . $sub_acc.'/data/accessories.json', json_encode($result, JSON_UNESCAPED_UNICODE));
                }

            }
        }

        echo json_encode($result);

    }


	public function export_csv() {
		$this->load->model('accessories_model');
		$items = $this->accessories_model->get_all();

		$csv_data = array();

		foreach ($items as $item){
			$csv_array = array();
			$csv_array[] = $item['id'];
			$csv_array[] = $item['code'];
			$csv_array[] = $item['name'];
			$csv_array[] = $item['category'];
			$csv_array[] = $item['price'];
			$csv_array[] = $item['images'];
			$csv_array[] = $item['description'];
			$csv_array[] = $item['tags'];
			$csv_array[] = $item['type'];
			$csv_array[] = $item['default'];
			$csv_array[] = $item['active'];


			$csv_data[] = $csv_array;
		}

		header('Content-Type: text/csv; charset=utf-8');
		header('Content-Disposition: attachment; filename=data.csv');

		$output = fopen('php://output', 'w');

		fputcsv($output, array('Внутренний id (не менять)', 'Артикул', 'Название', 'Категория', 'Цена', 'Изображения', 'Описание', 'Теги', 'Тип', 'По умолчанию', 'Видимость'));

		foreach ($csv_data as $row) {
			fputcsv($output, $row);
		}

	}

	public function import_csv() {
		$this->load->model('accessories_model');

		$no_check_code = 0;
		if(isset($_POST['no_check_code'])) $no_check_code = 1;


		if(isset($_FILES)){
			$data = readCSV($_FILES['modal_csv_file']['tmp_name']);
//			array_shift($data);



			$result = [
			    'by_code' => [],
                'by_id' => [],
                'empty_name' => [],
                'added' => [],
                'count' => count($data),
                'data' => $data
            ];




			foreach ($data as $key=>$item){



				if(empty($item)){
                    continue;
                }

				$item_data = array();

				$item_data['code'] = $item[1];
				$item_data['name'] = $item[2];
				$item_data['category'] = $item[3];
				$item_data['price'] = (float)str_replace(",", ".",$item[4] );
				$item_data['images'] = $item[5];
				$item_data['description'] = $item[6];
				$item_data['tags'] = $item[7];

				if(empty($item[8])) $item[8] = 'common';

				$item_data['type'] = $item[8];
				$item_data['default'] = $item[9];
//                if(empty($item[10])) $item[10] = 1;
                $item_data['active'] = $item[10];


                if($no_check_code == 1){
                    if(!empty($item[0]) && $this->accessories_model->get($item[0])){
                        $result['by_id'][] = $item;
                        $this->accessories_model->update($item[0], $item_data);
                    } else {
                        if(!empty($item_data['name'])){
                            $result['added'][] = $item;
                            $this->accessories_model->add($item_data);
                        } else {
                            $result['empty_name'][] = $item;
                        }
                    }
                } else {
                    if(!empty($item[1]) && $this->accessories_model->get_code($item[1])){
                        $result['by_code'][] = $item;
                        $this->accessories_model->update_code($item[1], $item_data);
                    } else if(!empty($item[1]) && !empty($item[0]) && $this->accessories_model->get($item[0])){
                        $result['by_id'][] = $item;
                        $this->accessories_model->update($item[0], $item_data);
                    } else {
                        if(!empty($item_data['name'])){
                            $result['added'][] = $item;
                            $this->accessories_model->add($item_data);
                        } else {
                            $result['empty_name'][] = $item;
                        }
                    }
                }



			}
			echo(json_encode($result));

//			echo 'ok';
		}


	}


	public function save_data_from_table() {
		$this->load->model('accessories_model');


	}

    public function import_xls() {
        $this->load->model('accessories_model');

        if(!isset($_POST)) exit;

        $data = json_decode($_POST['items'], true);


        $result = [
            'by_code' => [],
            'by_id' => [],
            'empty_name' => [],
            'added' => [],
            'count' => count($data),
            'data' => $data
        ];


        $no_check_code = 0;

        foreach ($data as $key=>$item){

            if(empty($item)){
                continue;
            }

            $item_data = array();

            $item_data['code'] = $item[1];
            $item_data['name'] = $item[2];
            $item_data['category'] = $item[3];
            $item_data['price'] = (float)str_replace(",", ".",$item[4] );
            $item_data['images'] = $item[5];
            $item_data['description'] = $item[6];
            $item_data['tags'] = $item[7];

            if(empty($item[8])) $item[8] = 'common';

            $item_data['type'] = $item[8];
            $item_data['default'] = $item[9];
//            if(empty($item[10])) $item[10] = 1;
            $item_data['active'] = $item[10];


            if($no_check_code == 1){
                if(!empty($item[0]) && $this->accessories_model->get($item[0])){
                    $result['by_id'][] = $item;
                    $this->accessories_model->update($item[0], $item_data);
                } else {
                    if(!empty($item_data['name'])){
                        $result['added'][] = $item;
                        $this->accessories_model->add($item_data);
                    } else {
                        $result['empty_name'][] = $item;
                    }
                }
            } else {
                if(!empty($item[0]) && $this->accessories_model->get($item[0])){
                    $result['by_id'][] = $item;
                    $this->accessories_model->update($item[0], $item_data);
                } else if(!empty($item[1]) && $this->accessories_model->get_code($item[1])){
                    $result['by_code'][] = $item;
                    $this->accessories_model->update_code($item[1], $item_data);
                } else if(!empty($item[1]) && !empty($item[0]) && $this->accessories_model->get($item[0])){
                    $result['by_id'][] = $item;
                    $this->accessories_model->update($item[0], $item_data);
                } else {
                    if(!empty($item_data['name'])){
                        $result['added'][] = $item;

                        $this->accessories_model->add($item_data);
                    } else {
                        $result['empty_name'][] = $item;
                    }
                }
            }



        }
        echo(json_encode($result));



    }



	public function populate_data() {
		$json = json_decode( file_get_contents($_SERVER['DOCUMENT_ROOT'] . '/clients/dev/data/accessories.json') );

		$this->load->model('accessories_model');

		$data = array();

		foreach ($json->tree as $cat){
			foreach ($cat->items as $item){

				$data['code'] = $item->SKU;
				$data['name'] = $item->Name;
				$data['category'] = $item->Categories;
				$data['price'] = $item->{'Regular price'};
				$data['description'] = htmlspecialchars($item->Description);
				$data['images'] = htmlspecialchars($item->Images);
				$data['type'] = 'common';
				$data['tags'] = $item->Tags;

				$this->accessories_model->add($data);

			}
		}

    }

    public function save_data()
    {


        if(isset($_POST)){
            $this->load->helper('file');


            write_file(dirname(FCPATH).'/data/accessories.json', $_POST['data']);

            if($this->config->item('username') === 'leko-sale@yandex.ru' || $this->config->item('username') === '6011626@mail.ru'){
	            foreach ($this->config->item('sub_accounts') as $sub_acc){
		            write_file(dirname(dirname(FCPATH)) . '/' . $sub_acc.'/data/accessories.json', $_POST['data']);
	            }
            }
        }
    }

	public function get_data_from_json() {
		if(isset($_POST['data'])){
			$this->load->helper('file');
			write_file(dirname(FCPATH).'/data/accessories.json', $_POST['data']);
		}
    }

	public function clear_db() {
		if(isset($_POST['true'])) {
			$this->load->model('accessories_model');
			$this->accessories_model->clear_db();

			$this->save_data_ajax();

		}
    }


}

function readCSV($csvFile){
    $file_handle = fopen($csvFile, 'r');


    $delimiters = [";" => 0, "," => 0, "\t" => 0, "|" => 0];

    $firstLine = fgets($file_handle);


    foreach ($delimiters as $delimiter => &$count) {
        $count = count(str_getcsv($firstLine, $delimiter));
    }

    $line_of_text = array();

    $delimiter = array_search(max($delimiters), $delimiters);


    while (!feof($file_handle) ) {
        $line_of_text[] = fgetcsv($file_handle, 0, $delimiter);
    }
    fclose($file_handle);
    return $line_of_text;
}

