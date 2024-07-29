<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Templates extends CI_Controller {

    public function __construct()
    {
        parent::__construct();
        $this->load->helper('url_helper');
        $this->load->model('templates_model');
        $this->load->library('session');

        if(!$this->session->username || $this->session->username != $this->config->item('username')){
            redirect('login', 'refresh');
        }

//        if(!$this->config->item('sub_account')) $this->config->set_item('sub_account', false);
//        if($this->config->item('sub_account') == true) redirect('settings', 'refresh');
    }

    public function index()
    {

        $data['templates'] = $this->templates_model->get_all();

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
        $this->load->view('kitchen_templates/index', $data);
        $this->load->view('templates/footer', $data);
    }

    public function add()
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
        $this->load->view('templates/header', $data);
        $this->load->view('kitchen_templates/add', $data);
        $this->load->view('templates/footer', $data);

    }

    public function add_ajax()
    {
        if(isset($_POST)){

            $errors = [];

            if(isset($_FILES['icon']) && $_FILES['icon']['size'] > 0) {

	            if($_FILES['icon']['size'] > 100 * 1024) $errors[] = 'Размер файла иконки не должен быть больше 100Кб';

                $icon_info = is_image($_FILES['icon']);

                if(!$icon_info) $errors[] = 'Недопустимый файл иконки';

                if($icon_info[0] > 300) $errors[] = 'Ширина иконки не должна быть больше 300px';
                if($icon_info[1] > 300) $errors[] = 'Высота иконки не должна быть больше 300px';

            } else {
                $errors[] = "Отсутствует файл иконки";
            }

            if(isset($_FILES['project']) && $_FILES['project']['size'] > 0) {
                if(!is_dbs($_FILES['project'])) $errors[] = "Некорректный файл проекта";
            } else {
                $errors[] = "Отсутствует файл проекта";
            }


            if( no_xss( $this->input->post('name') ) == "" ) $errors[] = "Название не должно быть пустым";


            if( count($errors) > 0 ){
                echo json_encode(['error' => $errors]);
                return;
            }

            $data = array();

            $data['name'] = no_xss($_POST['name']);
            $data['active'] = intval($_POST['active']);


            $item_id = $this->templates_model->add();

            $spath = dirname(FCPATH).'/templates/';

	        if (!is_dir($spath)) mkdir($spath);

            $save_path = dirname(FCPATH).'/templates/'. $item_id . '/';
            $abs_save_path = 'templates/' . $item_id . '/';
            if (!is_dir($save_path)) mkdir($save_path);

            $extension = strtolower(pathinfo( $_FILES['icon']['name'] )['extension']);
            $new_file_icon = $save_path . $item_id . '_icon.' . $extension;

            if (move_uploaded_file($_FILES['icon']['tmp_name'], $new_file_icon)) {
                $data['icon'] = $abs_save_path . $item_id . '_icon.' . $extension;
            } else {
                $errors[] = 'Ошибка при загрузке файла ' . $_FILES['icon']['name'];
            }



            $new_file_project = $save_path . $item_id . '_project.dbs';

            if (move_uploaded_file($_FILES['project']['tmp_name'], $new_file_project)) {
                $data['file'] = $abs_save_path . $item_id . '_project.dbs';
            } else {
                $errors[] = 'Ошибка при загрузке файла ' . $_FILES['project']['name'];
            }


            if( count ($errors) > 0 ){

                removeDirectory($save_path);
                $this->templates_model->remove($item_id);
                echo json_encode(['error' => $errors]);
                return;
            }


            $this->templates_model->update($item_id, $data);


            echo json_encode(['success' => 'true']);

        }
    }

    public function edit($id)
    {


        $data['item'] = $this->templates_model->get_one($id);

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
        $this->load->view('kitchen_templates/edit', $data);
        $this->load->view('templates/footer', $data);


    }

    public function edit_ajax($id)
    {

        if(isset($_POST)){

            $errors = [];

            if(isset($_FILES['icon']) && $_FILES['icon']['size'] > 0) {

                if($_FILES['icon']['size'] > 100 * 1024) $errors[] = 'Размер файла иконки не должен быть больше 100Кб';

                $icon_info = is_image($_FILES['icon']);

	            if(!$icon_info) $errors[] = 'Недопустимый файл иконки';


	            if($icon_info[0] > 300) $errors[] = 'Ширина иконки не должна быть больше 300px';
                if($icon_info[1] > 300) $errors[] = 'Высота иконки не должна быть больше 300px';

            }

            if(isset($_FILES['project']) && $_FILES['project']['size'] > 0) {
                if(!is_dbs($_FILES['project'])) $errors[] = "Некорректный файл проекта";
            }


            if( no_xss( $this->input->post('name') ) == "" ) $errors[] = "Название не должно быть пустым";


            if( count($errors) > 0 ){
                echo json_encode(['error' => $errors]);
                return;
            }

            $data = array();

            $data['name'] = no_xss($_POST['name']);
            $data['active'] = intval($_POST['active']);


            $item_id = $id;



            $save_path = dirname(FCPATH).'/templates/'. $item_id . '/';
            $abs_save_path = 'templates/' . $item_id . '/';
            if (!is_dir($save_path)) mkdir($save_path);


            if(isset($_FILES['icon']) && $_FILES['icon']['size'] > 0) {
                $extension = strtolower(pathinfo($_FILES['icon']['name'])['extension']);
                $new_file_icon = $save_path . $item_id . '_icon.' . $extension;

                if (move_uploaded_file($_FILES['icon']['tmp_name'], $new_file_icon)) {
                    $data['icon'] = $abs_save_path . $item_id . '_icon.' . $extension;
                } else {
                    $errors[] = 'Ошибка при загрузке файла ' . $_FILES['icon']['name'];
                }
            }

            if(isset($_FILES['project']) && $_FILES['project']['size'] > 0) {
                $new_file_project = $save_path . $item_id . '_project.dbs';

                if (move_uploaded_file($_FILES['project']['tmp_name'], $new_file_project)) {
                    $data['file'] = $abs_save_path . $item_id . '_project.dbs';
                } else {
                    $errors[] = 'Ошибка при загрузке файла ' . $_FILES['project']['name'];
                }
            }


            if( count ($errors) > 0 ){
                echo json_encode(['error' => $errors]);
                return;
            }


            $this->templates_model->update($item_id, $data);

            echo json_encode(['success' => 'true']);

        }

    }

    public function remove($id)
    {
        removeDirectory(dirname(FCPATH).'/templates/'.$id);
        $this->templates_model->remove($id);
        redirect('templates/index/', 'refresh');
    }

	public function set_active_ajax($id, $val) {
		$this->templates_model->set_active($id, $val);
	}


	public function index_coupe() {
		$data['templates'] = $this->templates_model->get_all_coupe();

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
		$this->load->view('coupe/header', $data);
		$this->load->view('coupe/templates/index', $data);
		$this->load->view('templates/footer', $data);
    }

	public function add_coupe() {

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

		$this->load->view('coupe/header', $data);
		$this->load->view('coupe/templates/add', $data);
		$this->load->view('templates/footer', $data);
    }

	public function add_coupe_ajax() {
		if(isset($_POST)){

			$errors = [];

			if(isset($_FILES['icon']) && $_FILES['icon']['size'] > 0) {

				if($_FILES['icon']['size'] > 100 * 1024) $errors[] = 'Размер файла иконки не должен быть больше 100Кб';

				$icon_info = is_image($_FILES['icon']);

				if(!$icon_info) $errors[] = 'Недопустимый файл иконки';

				if($icon_info[0] > 300) $errors[] = 'Ширина иконки не должна быть больше 300px';
				if($icon_info[1] > 300) $errors[] = 'Высота иконки не должна быть больше 300px';

			} else {
				$errors[] = "Отсутствует файл иконки";
			}

			if(isset($_FILES['project']) && $_FILES['project']['size'] > 0) {
				if(!is_dbs($_FILES['project'])) $errors[] = "Некорректный файл проекта";
			} else {
				$errors[] = "Отсутствует файл проекта";
			}


			if( no_xss( $this->input->post('name') ) == "" ) $errors[] = "Название не должно быть пустым";


			if( count($errors) > 0 ){
				echo json_encode(['error' => $errors]);
				return;
			}

			$data = array();

			$data['name'] = no_xss($_POST['name']);
			$data['active'] = intval($_POST['active']);


			$item_id = $this->templates_model->add_coupe();

			$save_path = dirname(FCPATH).'/coupe/templates/'. $item_id . '/';
			$abs_save_path = '/coupe/templates/' . $item_id . '/';
			if (!is_dir($save_path)) mkdir($save_path);

			$extension = strtolower(pathinfo( $_FILES['icon']['name'] )['extension']);
			$new_file_icon = $save_path . $item_id . '_icon.' . $extension;

			if (move_uploaded_file($_FILES['icon']['tmp_name'], $new_file_icon)) {
				$data['icon'] = $abs_save_path . $item_id . '_icon.' . $extension;
			} else {
				$errors[] = 'Ошибка при загрузке файла ' . $_FILES['icon']['name'];
			}



			$new_file_project = $save_path . $item_id . '_project.dbs';

			if (move_uploaded_file($_FILES['project']['tmp_name'], $new_file_project)) {
				$data['file'] = $abs_save_path . $item_id . '_project.dbs';
			} else {
				$errors[] = 'Ошибка при загрузке файла ' . $_FILES['project']['name'];
			}


			if( count ($errors) > 0 ){
				removeDirectory($save_path);
				$this->templates_model->remove_coupe($item_id);
				echo json_encode(['error' => $errors]);
				return;
			}


			$this->templates_model->update_coupe($item_id, $data);


			echo json_encode(['success' => 'true']);

		}

    }

	public function edit_coupe($id) {
		$data['item'] = $this->templates_model->get_one_coupe($id);

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
		$this->load->view('coupe/header', $data);
		$this->load->view('coupe/templates/edit', $data);
		$this->load->view('templates/footer', $data);
    }

	public function edit_coupe_ajax($id) {
		if(isset($_POST)){

			$errors = [];

			if(isset($_FILES['icon']) && $_FILES['icon']['size'] > 0) {

				if($_FILES['icon']['size'] > 100 * 1024) $errors[] = 'Размер файла иконки не должен быть больше 100Кб';

				$icon_info = is_image($_FILES['icon']);

				if(!$icon_info) $errors[] = 'Недопустимый файл иконки';


				if($icon_info[0] > 300) $errors[] = 'Ширина иконки не должна быть больше 300px';
				if($icon_info[1] > 300) $errors[] = 'Высота иконки не должна быть больше 300px';

			}

			if(isset($_FILES['project']) && $_FILES['project']['size'] > 0) {
				if(!is_dbs($_FILES['project'])) $errors[] = "Некорректный файл проекта";
			}


			if( no_xss( $this->input->post('name') ) == "" ) $errors[] = "Название не должно быть пустым";


			if( count($errors) > 0 ){
				echo json_encode(['error' => $errors]);
				return;
			}

			$data = array();

			$data['name'] = no_xss($_POST['name']);
			$data['active'] = intval($_POST['active']);


			$item_id = $id;



			$save_path = dirname(FCPATH).'/coupe/templates/'. $item_id . '/';
			$abs_save_path = '/coupe/templates/' . $item_id . '/';
			if (!is_dir($save_path)) mkdir($save_path);


			if(isset($_FILES['icon']) && $_FILES['icon']['size'] > 0) {
				$extension = strtolower(pathinfo($_FILES['icon']['name'])['extension']);
				$new_file_icon = $save_path . $item_id . '_icon.' . $extension;

				if (move_uploaded_file($_FILES['icon']['tmp_name'], $new_file_icon)) {
					$data['icon'] = $abs_save_path . $item_id . '_icon.' . $extension;
				} else {
					$errors[] = 'Ошибка при загрузке файла ' . $_FILES['icon']['name'];
				}
			}

			if(isset($_FILES['project']) && $_FILES['project']['size'] > 0) {
				$new_file_project = $save_path . $item_id . '_project.dbs';

				if (move_uploaded_file($_FILES['project']['tmp_name'], $new_file_project)) {
					$data['file'] = $abs_save_path . $item_id . '_project.dbs';
				} else {
					$errors[] = 'Ошибка при загрузке файла ' . $_FILES['project']['name'];
				}
			}


			if( count ($errors) > 0 ){
				echo json_encode(['error' => $errors]);
				return;
			}


			$this->templates_model->update_coupe($item_id, $data);

			echo json_encode(['success' => 'true']);

		}

    }

	public function remove_coupe($id) {
		removeDirectory(dirname(FCPATH).'/coupe/templates/'.$id);
		$this->templates_model->remove_coupe($id);
		redirect('templates/index_coupe/', 'refresh');
    }


}