<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Updater extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->load->helper('url_helper');
        $this->load->library('session');
        $this->load->database();
        $this->load->dbforge();
        if (!$this->session->username || $this->session->username != $this->config->item('username')) {
            redirect('login');
        }
    }

    private $current_version = 0;
    private $version_path = FCPATH . 'version';

    public function index()
    {
        if (!file_exists($this->version_path)) file_put_contents($this->version_path, 0);

        $this->current_version = (int)file_get_contents($this->version_path);
        $config_version = (int)file_get_contents($_SERVER['DOCUMENT_ROOT'] . '/common_assets/config/version');

        $diff = $config_version - $this->current_version;

        if ($diff > 0) {

            for ($i = $this->current_version + 1; $i <= $config_version; $i++) {
                $fname = 'upd' . $i;
                $this->$fname();
            }
            file_put_contents($this->version_path, $this->current_version);
            redirect('settings');
        } else {
            redirect('settings');
        }
    }

    private function upd1()
    {

        $config_path = FCPATH;
        $root_path = dirname(FCPATH);

        if (file_exists($config_path . '/license.txt')) unlink($config_path . '/license.txt');
        if (file_exists($config_path . '/config.php')) unlink($config_path . '/config.php');
        if (file_exists($root_path . '/sendmail.php')) unlink($root_path . '/sendmail.php');
        if (file_exists($root_path . '/sendmail_coupe.php')) unlink($root_path . '/sendmail_coupe.php');
        if (file_exists($root_path . '/counter.txt')) unlink($root_path . '/counter.txt');
        if (file_exists($root_path . '/files/index.php')) unlink($root_path . '/files/index.php');

        $from_path = $_SERVER['DOCUMENT_ROOT'] . '/common_assets/config/updates/index_update';

        $this->r_copy($from_path . '/files/index.php', $root_path . '/index.php', 1);

        $this->load->model('accessories_model');
        $data = $this->accessories_model->get_images();
        foreach ($data as $item) {
            $str = str_replace('https://broskokitchenplanner.com', 'https://planplace.ru', $item['images']);
            $str = str_replace('wp-content/uploads/2019/02', 'uploads/images', $str);

            $item['images'] = $str;


            $this->accessories_model->update($item['id'], $item);

        }

        $this->save_accessories();

        $columns = array(
            'image' => array('type' => 'TEXT')
        );

        $tables = [
            'Comms_categories',
            'Facades_categories',
            'Glass_categories',
            'Handles_categories',
            'Interior_categories',
            'Materials_categories',
            'Module_sets_categories',
            'Modules_categories',
            'Tech_categories'
        ];

        foreach ($tables as $table) {
            $res = $this->db->get($table)->row_array();
            if (!array_key_exists('image', $res)) {
                $this->dbforge->add_column($table, $columns);
            }
        }

        $this->current_version++;
    }

    private function upd2()
    {


        $fields = array(
            'name' => array(
                'type' => 'TEXT',
                'null' => TRUE,
                'default' => '',
            ),
            'key' => array(
                'type' => 'TEXT',
                'null' => TRUE,
                'default' => '',
            ),
            'data' => array(
                'type' => 'TEXT',
                'null' => TRUE,
                'default' => '',
            )
        );
        $this->dbforge->add_field('id');
        $this->dbforge->add_field($fields);
        $this->dbforge->create_table('Material_types_items', TRUE);


        $fields = array(
            'name' => array(
                'type' => 'TEXT',
                'null' => TRUE,
                'default' => '',
            ),
            'parent' => array(
                'type' => 'INTEGER',
                'null' => TRUE,
                'default' => 0,
            ),
            'active' => array(
                'type' => 'INTEGER',
                'null' => TRUE,
                'default' => 1
            ),
            'order' => array(
                'type' => 'INTEGER',
                'null' => TRUE,
                'default' => 0
            ),
            'description' => array(
                'type' => 'TEXT',
                'null' => TRUE
            ),
            'image' => array(
                'type' => 'TEXT',
                'null' => TRUE
            ),
            'params' => array(
                'type' => 'TEXT',
                'null' => TRUE
            )
        );
        $this->dbforge->add_field('id');
        $this->dbforge->add_field($fields);
        $this->dbforge->create_table('Catalogue_categories', TRUE);


        $fields = array(
            'name' => array(
                'type' => 'TEXT',
                'null' => TRUE,
                'default' => '',
            ),
            'category' => array(
                'type' => 'TEXT',
                'null' => TRUE,
            ),
            'icon' => array(
                'type' => 'TEXT',
                'null' => TRUE,
            ),
            'data' => array(
                'type' => 'TEXT',
                'null' => TRUE,
            ),
            'active' => array(
                'type' => 'INTEGER',
                'null' => TRUE,
                'default' => 1
            ),
            'order' => array(
                'type' => 'INTEGER',
                'null' => TRUE
            )
        );
        $this->dbforge->add_field('id');
        $this->dbforge->add_field($fields);
        $this->dbforge->create_table('Catalogue_items', TRUE);


        $this->current_version++;
    }

    private function upd3()
    {
        $table = 'Catalogue_items';

        $columns = array(
            'name' => array('type' => 'TEXT')
        );

        $res = $this->db->get($table)->row_array();

        if ($res && !array_key_exists('name', $res)) {
            $this->dbforge->add_column($table, $columns);
        }

        $fields = array(
            'name' => array(
                'type' => 'TEXT',
                'null' => TRUE,
                'default' => '',
            ),
            'parent' => array(
                'type' => 'INTEGER',
                'null' => TRUE,
                'default' => 0,
            ),
            'active' => array(
                'type' => 'INTEGER',
                'null' => TRUE,
                'default' => 1
            ),
            'order' => array(
                'type' => 'INTEGER',
                'null' => TRUE,
                'default' => 0
            ),
            'description' => array(
                'type' => 'TEXT',
                'null' => TRUE
            ),
            'image' => array(
                'type' => 'TEXT',
                'null' => TRUE
            ),
            'params' => array(
                'type' => 'TEXT',
                'null' => TRUE
            )
        );
        $this->dbforge->add_field('id');
        $this->dbforge->add_field($fields);
        $this->dbforge->create_table('Model3d_categories', TRUE);


        $fields = array(
            'name' => array(
                'type' => 'TEXT',
                'null' => TRUE,
                'default' => '',
            ),
            'category' => array(
                'type' => 'TEXT',
                'null' => TRUE,
            ),
            'icon' => array(
                'type' => 'TEXT',
                'null' => TRUE,
            ),
            'data' => array(
                'type' => 'TEXT',
                'null' => TRUE,
            ),
            'active' => array(
                'type' => 'INTEGER',
                'null' => TRUE,
                'default' => 1
            ),
            'order' => array(
                'type' => 'INTEGER',
                'null' => TRUE
            )
        );
        $this->dbforge->add_field('id');
        $this->dbforge->add_field($fields);
        $this->dbforge->create_table('Model3d_items', TRUE);

        $this->current_version++;
    }

    private function upd4(){
        $this->load->model('project_settings_model');
        $this->load->model('common_model');
        $settings = $this->project_settings_model->get_settings();
        $key = 'ldsp';
        $res = $this->common_model->get_row_where('Material_types_items', array('key' => $key));
        if (!$res) {
            $data = array();
            $data['name'] = 'ЛДСП';
            $data['type'] = 'm2';
            $data['default'] = 'v_16';
            $data['key'] = $key;
            $data['variants'] = array();

            $var_arr = [16, 18, 32];

            foreach ($var_arr as $item) {
                $data['variants']['v_' . $item] = array();
                $data['variants']['v_' . $item]['name'] = $item . 'мм';
                $data['variants']['v_' . $item]['key'] = 'v_' . $item;
                $data['variants']['v_' . $item]['id'] = $settings['selected_material_corpus'];
                $data['variants']['v_' . $item]['size'] = array();
                $data['variants']['v_' . $item]['size']['x'] = 0;
                $data['variants']['v_' . $item]['size']['y'] = 0;
                $data['variants']['v_' . $item]['size']['z'] = $item;
                $data['variants']['v_' . $item]['categories'] = json_decode($settings['available_materials_corpus']);
            }

            $add_arr = [];
            $add_arr['name'] = 'ЛДСП';
            $add_arr['key'] = $key;
            $add_arr['data'] = json_encode($data);

            $this->common_model->add_data('Material_types_items', $add_arr);

        }

        $tables = [
            'Comms_categories',
            'Facades_categories',
            'Glass_categories',
            'Handles_categories',
            'Interior_categories',
            'Materials_categories',
            'Module_sets_categories',
            'Modules_categories',
            'Tech_categories'
        ];

        $columns = array(
            'image' => array('type' => 'TEXT')
        );

        foreach ($tables as $table) {
            $res = $this->db->get($table)->row_array();
            if($res){
                if (!array_key_exists('image', $res)) {
                    $this->dbforge->add_column($table, $columns);
                }
            }

        }

        $this->current_version++;
    }

    private function upd5(){
        //Шаблоны корпусной
        $fields = array(
            'name' => array(
                'type' => 'TEXT',
                'null' => TRUE,
                'default' => '',
            ),
            'icon' => array(
                'type' => 'TEXT',
                'null' => TRUE,
                'default' => '',
            ),
            'data' => array(
                'type' => 'TEXT',
                'null' => TRUE,
                'default' => '',
            ),
            'tags' => array(
                'type' => 'TEXT',
                'null' => TRUE,
                'default' => '',
            ),
            'order' => array(
                'type' => 'INTEGER',
                'null' => TRUE
            )
        );
        $this->dbforge->add_field('id');
        $this->dbforge->add_field($fields);
        $this->dbforge->create_table('Catalogue_templates_items', TRUE);

        //Дата в материалы
        $res = $this->db->get('Materials_items')->row_array();
        if (!array_key_exists('data', $res)) {
            $this->dbforge->add_column('Materials_items', array(
                'data' => array('type' => 'TEXT')
            ));
        }

        //Файлы
        $fields = array(
            'name' => array(
                'type' => 'TEXT',
                'null' => TRUE,
                'default' => '',
            ),
            'type' => array(
                'type' => 'TEXT',
                'null' => TRUE,
                'default' => '',
            ),
            'path' => array(
                'type' => 'TEXT',
                'null' => TRUE,
                'default' => '',
            ),
            'order' => array(
                'type' => 'INTEGER',
                'null' => TRUE
            ),
            'active' => array(
                'type' => 'INTEGER',
                'default' => 1,
            )
        );
        $base_dir = dirname(FCPATH) .'/assets';

        if(!file_exists($base_dir .'/html')) mkdir($base_dir .'/html');
        if(!file_exists($base_dir .'/js')) mkdir($base_dir .'/js');
        if(!file_exists($base_dir .'/css')) mkdir($base_dir .'/css');

        $this->dbforge->add_field('id');
        $this->dbforge->add_field($fields);
        $this->dbforge->create_table('Custom_files', TRUE);

        //Params_blocks_items
        $fields = array(
            'name' => array(
                'type' => 'TEXT',
                'null' => TRUE,
                'default' => '',
            ),
            'data' => array(
                'type' => 'TEXT',
                'null' => TRUE,
                'default' => '',
            ),
            'description' => array(
                'type' => 'TEXT',
                'null' => TRUE,
                'default' => '',
            ),
            'order' => array(
                'type' => 'INTEGER',
                'null' => TRUE,
                'default'=>10000
            ),
            'active' => array(
                'type' => 'INTEGER',
                'default' => 1,
            )
        );
        $this->dbforge->add_field('id');
        $this->dbforge->add_field($fields);
        $this->dbforge->create_table('Params_blocks_items', TRUE);


        //Background_items
        $fields = array(
            'name' => array(
                'type' => 'TEXT',
                'null' => TRUE,
                'default' => '',
            ),
            'icon' => array(
                'type' => 'TEXT',
                'null' => TRUE,
                'default' => '',
            ),
            'src' => array(
                'type' => 'TEXT',
                'null' => TRUE,
                'default' => '',
            ),
            'params' => array(
                'type' => 'TEXT',
                'null' => TRUE,
                'default' => '',
            ),
            'order' => array(
                'type' => 'INTEGER',
                'null' => TRUE,
                'default'=>10000
            ),
            'active' => array(
                'type' => 'INTEGER',
                'default' => 1,
            )
        );
        $this->dbforge->add_field('id');
        $this->dbforge->add_field($fields);
        $this->dbforge->create_table('Background_items', TRUE);

        //Ящики
        $fields = array(
            'name' => array(
                'type' => 'TEXT',
                'null' => TRUE,
                'default' => '',
            ),
            'icon' => array(
                'type' => 'TEXT',
                'null' => TRUE,
                'default' => '',
            ),
            'data' => array(
                'type' => 'TEXT',
                'null' => TRUE,
                'default' => '',
            ),
            'active' => array(
                'type' => 'INTEGER',
                'default' => 1,
            ),
            'order' => array(
                'type' => 'INTEGER',
                'null' => TRUE
            )
        );
        $this->dbforge->add_field('id');
        $this->dbforge->add_field($fields);
        $this->dbforge->create_table('Lockers_items', TRUE);
        $this->current_version++;
    }

    private function upd6(){
        $res = $this->db->get('Settings')->row_array();
        $columns = array(
            'interface' => array('type' => 'INTEGER')
        );
        if (!array_key_exists('interface', $res)) {
            $this->dbforge->add_column('Settings', $columns);
        }

        $config = $this->config->item('ini');
        $def_version = 1;
        if($config['tariff']){
            if($config['tariff']['id']){
                if($config['tariff']['id'] > 20){
                    $def_version = 2;
                }
            }
        }

        $this->load->model('settings_model');
        $data = array(
            'interface' => $def_version
        );
        $this->settings_model->update_settings_data($data);
        $this->current_version++;
    }

    private function upd7(){
        $fields = array(
            'name' => array(
                'type' => 'TEXT',
                'null' => TRUE,
                'default' => '',
            ),
            'code' => array(
                'type' => 'TEXT',
                'null' => TRUE,
                'default' => '',
            ),
            'category' => array(
                'type' => 'INTEGER',
                'default' => 0,
            ),
            'icon' => array(
                'type' => 'TEXT',
                'null' => TRUE,
                'default' => '',
            ),
            'data' => array(
                'type' => 'TEXT',
                'null' => TRUE,
                'default' => '',
            ),
            'active' => array(
                'type' => 'INTEGER',
                'default' => 1,
            ),
            'order' => array(
                'type' => 'INTEGER',
                'null' => TRUE
            )
        );
        $this->dbforge->add_field('id');
        $this->dbforge->add_field($fields);
        $this->dbforge->create_table('Cornice_items', TRUE);

        $res = $this->db->get('Constructor_settings')->row_array();
        if($res){
            $columns = array(
                'settings' => array('type' => 'TEXT'),
                'settings_shop' => array('type' => 'TEXT'),
                'settings_common' => array('type' => 'TEXT')
            );

            if (!array_key_exists('settings', $res)) {
                $this->dbforge->add_column('Constructor_settings', $columns);
            }
        }
        $this->current_version++;
    }

    private function upd8(){
        $fields = array(
            'name' => array(
                'type' => 'TEXT',
                'null' => TRUE,
                'default' => '',
            ),
            'parent' => array(
                'type' => 'INTEGER',
                'null' => TRUE,
            ),
            'active' => array(
                'type' => 'INTEGER',
                'default' => 1,
            ),
            'order' => array(
                'type' => 'INTEGER',
                'default' => 0,
                'null' => TRUE
            ),
            'description' => array(
                'type' => 'TEXT',
                'null' => TRUE,
                'default' => '',
            ),
            'image' => array(
                'type' => 'TEXT',
                'null' => TRUE,
                'default' => '',
            ),
        );
        $this->dbforge->add_field('id');
        $this->dbforge->add_field($fields);
        $this->dbforge->create_table('Cornice_categories', TRUE);

        $fields = array(
            'name' => array(
                'type' => 'TEXT',
                'null' => TRUE,
                'default' => '',
            ),
            'parent' => array(
                'type' => 'INTEGER',
                'null' => TRUE,
            ),
            'active' => array(
                'type' => 'INTEGER',
                'default' => 1,
            ),
            'order' => array(
                'type' => 'INTEGER',
                'default' => 0,
                'null' => TRUE
            ),
            'description' => array(
                'type' => 'TEXT',
                'null' => TRUE,
                'default' => '',
            ),
            'image' => array(
                'type' => 'TEXT',
                'null' => TRUE,
                'default' => '',
            ),
        );
        $this->dbforge->add_field('id');
        $this->dbforge->add_field($fields);
        $this->dbforge->create_table('Kitchen_models_categories', TRUE);

        $res = $this->db->get('Kitchen_models')->row_array();
        if($res){
            $columns = array(
                'settings' => array('type' => 'TEXT'),
                'category' => array(
                    'type' => 'INTEGER',
                    'default' => 0,
                )
            );

            if (!array_key_exists('settings', $res)) {
                $this->dbforge->add_column('Kitchen_models', $columns);
            }
        }

        $res = $this->db->get('Project_settings')->row_array();
        if($res){
            $columns = array(
                'settings' => array('type' => 'TEXT')
            );

            if (!array_key_exists('settings', $res)) {
                $this->dbforge->add_column('Project_settings', $columns);
            }
        }


        if (!$this->db->field_exists('category', 'Cornice_items'))
        {
            $columns = array(
                'category' => array(
                    'type' => 'INTEGER',
                    'default' => 0,
                )
            );

            $this->dbforge->add_column('Cornice_items', $columns);

        }



        $this->current_version++;
    }

    private function upd9(){

        if (!$this->db->field_exists('category', 'Cornice_items'))
        {
            $columns = array(
                'category' => array(
                    'type' => 'INTEGER',
                    'default' => 0,
                )
            );

            $this->dbforge->add_column('Cornice_items', $columns);

        }

        $fields = array(
            'name' => array(
                'type' => 'TEXT',
                'null' => TRUE,
                'default' => '',
            ),
            'icon' => array(
                'type' => 'TEXT',
                'null' => TRUE,
                'default' => '',
            ),
            'category' => array(
                'type' => 'INTEGER',
                'default' => 0,
            ),
            'data' => array(
                'type' => 'TEXT',
                'null' => TRUE,
                'default' => '',
            ),
            'active' => array(
                'type' => 'INTEGER',
                'default' => 1,
            ),
            'order' => array(
                'type' => 'INTEGER',
                'null' => TRUE
            )
        );
        $this->dbforge->add_field('id');
        $this->dbforge->add_field($fields);
        $this->dbforge->create_table('Washes_items', TRUE);

        $fields = array(
            'name' => array(
                'type' => 'TEXT',
                'null' => TRUE,
                'default' => '',
            ),
            'icon' => array(
                'type' => 'TEXT',
                'null' => TRUE,
                'default' => '',
            ),
            'category' => array(
                'type' => 'INTEGER',
                'default' => 0,
            ),
            'data' => array(
                'type' => 'TEXT',
                'null' => TRUE,
                'default' => '',
            ),
            'active' => array(
                'type' => 'INTEGER',
                'default' => 1,
            ),
            'order' => array(
                'type' => 'INTEGER',
                'null' => TRUE
            )
        );
        $this->dbforge->add_field('id');
        $this->dbforge->add_field($fields);
        $this->dbforge->create_table('Smes_items', TRUE);

        $fields = array(
            'name' => array(
                'type' => 'TEXT',
                'null' => TRUE,
                'default' => '',
            ),
            'parent' => array(
                'type' => 'INTEGER',
                'null' => TRUE,
            ),
            'active' => array(
                'type' => 'INTEGER',
                'default' => 1,
            ),
            'order' => array(
                'type' => 'INTEGER',
                'default' => 0,
                'null' => TRUE
            ),
            'description' => array(
                'type' => 'TEXT',
                'null' => TRUE,
                'default' => '',
            ),
            'image' => array(
                'type' => 'TEXT',
                'null' => TRUE,
                'default' => '',
            ),
        );
        $this->dbforge->add_field('id');
        $this->dbforge->add_field($fields);
        $this->dbforge->create_table('Washes_categories', TRUE);

        $fields = array(
            'name' => array(
                'type' => 'TEXT',
                'null' => TRUE,
                'default' => '',
            ),
            'parent' => array(
                'type' => 'INTEGER',
                'null' => TRUE,
            ),
            'active' => array(
                'type' => 'INTEGER',
                'default' => 1,
            ),
            'order' => array(
                'type' => 'INTEGER',
                'default' => 0,
                'null' => TRUE
            ),
            'description' => array(
                'type' => 'TEXT',
                'null' => TRUE,
                'default' => '',
            ),
            'image' => array(
                'type' => 'TEXT',
                'null' => TRUE,
                'default' => '',
            ),
        );
        $this->dbforge->add_field('id');
        $this->dbforge->add_field($fields);
        $this->dbforge->create_table('Smes_categories', TRUE);

        $this->current_version++;

    }

    private function upd10(){
        $fields = array(
            'name' => array(
                'type' => 'TEXT',
                'null' => TRUE,
                'default' => '',
            ),
            'parent' => array(
                'type' => 'INTEGER',
                'null' => TRUE,
                'default' => 0,
            ),
            'active' => array(
                'type' => 'INTEGER',
                'null' => TRUE,
                'default' => 1
            ),
            'order' => array(
                'type' => 'INTEGER',
                'null' => TRUE,
                'default' => 0
            ),
            'description' => array(
                'type' => 'TEXT',
                'null' => TRUE
            ),
            'image' => array(
                'type' => 'TEXT',
                'null' => TRUE
            ),
            'params' => array(
                'type' => 'TEXT',
                'null' => TRUE
            )
        );
        $this->dbforge->add_field('id');
        $this->dbforge->add_field($fields);
        $this->dbforge->create_table('Bardesks_categories', TRUE);

        $fields = array(
            'name' => array(
                'type' => 'TEXT',
                'null' => TRUE,
                'default' => '',
            ),
            'category' => array(
                'type' => 'TEXT',
                'null' => TRUE,
            ),
            'icon' => array(
                'type' => 'TEXT',
                'null' => TRUE,
            ),
            'data' => array(
                'type' => 'TEXT',
                'null' => TRUE,
            ),
            'active' => array(
                'type' => 'INTEGER',
                'null' => TRUE,
                'default' => 1
            ),
            'order' => array(
                'type' => 'INTEGER',
                'null' => TRUE
            )
        );
        $this->dbforge->add_field('id');
        $this->dbforge->add_field($fields);
        $this->dbforge->create_table('Bardesks_items', TRUE);

        $this->current_version++;
    }

    private function upd11(){
        $fields = array(
            'name' => array(
                'type' => 'TEXT',
                'null' => TRUE,
                'default' => '',
            ),
            'code' => array(
                'type' => 'TEXT',
                'null' => TRUE,
                'default' => '',
            ),
            'category' => array(
                'type' => 'INTEGER',
                'default' => 0,
            ),
            'icon' => array(
                'type' => 'TEXT',
                'null' => TRUE,
                'default' => '',
            ),
            'data' => array(
                'type' => 'TEXT',
                'null' => TRUE,
                'default' => '',
            ),
            'active' => array(
                'type' => 'INTEGER',
                'default' => 1,
            ),
            'order' => array(
                'type' => 'INTEGER',
                'null' => TRUE
            )
        );
        $this->dbforge->add_field('id');
        $this->dbforge->add_field($fields);
        $this->dbforge->create_table('Cokol_items', TRUE);

        $fields = array(
            'name' => array(
                'type' => 'TEXT',
                'null' => TRUE,
                'default' => '',
            ),
            'parent' => array(
                'type' => 'INTEGER',
                'null' => TRUE,
            ),
            'active' => array(
                'type' => 'INTEGER',
                'default' => 1,
            ),
            'order' => array(
                'type' => 'INTEGER',
                'default' => 0,
                'null' => TRUE
            ),
            'description' => array(
                'type' => 'TEXT',
                'null' => TRUE,
                'default' => '',
            ),
            'image' => array(
                'type' => 'TEXT',
                'null' => TRUE,
                'default' => '',
            ),
        );
        $this->dbforge->add_field('id');
        $this->dbforge->add_field($fields);
        $this->dbforge->create_table('Cokol_categories', TRUE);


        $fields = array(
            'name' => array(
                'type' => 'TEXT',
                'null' => TRUE,
                'default' => '',
            ),
            'code' => array(
                'type' => 'TEXT',
                'null' => TRUE,
                'default' => '',
            ),
            'category' => array(
                'type' => 'INTEGER',
                'default' => 0,
            ),
            'icon' => array(
                'type' => 'TEXT',
                'null' => TRUE,
                'default' => '',
            ),
            'data' => array(
                'type' => 'TEXT',
                'null' => TRUE,
                'default' => '',
            ),
            'active' => array(
                'type' => 'INTEGER',
                'default' => 1,
            ),
            'order' => array(
                'type' => 'INTEGER',
                'null' => TRUE
            )
        );
        $this->dbforge->add_field('id');
        $this->dbforge->add_field($fields);
        $this->dbforge->create_table('Legs_items', TRUE);

        $fields = array(
            'name' => array(
                'type' => 'TEXT',
                'null' => TRUE,
                'default' => '',
            ),
            'parent' => array(
                'type' => 'INTEGER',
                'null' => TRUE,
            ),
            'active' => array(
                'type' => 'INTEGER',
                'default' => 1,
            ),
            'order' => array(
                'type' => 'INTEGER',
                'default' => 0,
                'null' => TRUE
            ),
            'description' => array(
                'type' => 'TEXT',
                'null' => TRUE,
                'default' => '',
            ),
            'image' => array(
                'type' => 'TEXT',
                'null' => TRUE,
                'default' => '',
            ),
        );
        $this->dbforge->add_field('id');
        $this->dbforge->add_field($fields);
        $this->dbforge->create_table('Legs_categories', TRUE);


        $fields = array(
            'name' => array(
                'type' => 'TEXT',
                'null' => TRUE,
                'default' => '',
            ),
            'code' => array(
                'type' => 'TEXT',
                'null' => TRUE,
                'default' => '',
            ),
            'category' => array(
                'type' => 'INTEGER',
                'default' => 0,
            ),
            'icon' => array(
                'type' => 'TEXT',
                'null' => TRUE,
                'default' => '',
            ),
            'data' => array(
                'type' => 'TEXT',
                'null' => TRUE,
                'default' => '',
            ),
            'active' => array(
                'type' => 'INTEGER',
                'default' => 1,
            ),
            'order' => array(
                'type' => 'INTEGER',
                'null' => TRUE
            )
        );
        $this->dbforge->add_field('id');
        $this->dbforge->add_field($fields);
        $this->dbforge->create_table('Tabletop_items', TRUE);

        $fields = array(
            'name' => array(
                'type' => 'TEXT',
                'null' => TRUE,
                'default' => '',
            ),
            'parent' => array(
                'type' => 'INTEGER',
                'null' => TRUE,
            ),
            'active' => array(
                'type' => 'INTEGER',
                'default' => 1,
            ),
            'order' => array(
                'type' => 'INTEGER',
                'default' => 0,
                'null' => TRUE
            ),
            'description' => array(
                'type' => 'TEXT',
                'null' => TRUE,
                'default' => '',
            ),
            'image' => array(
                'type' => 'TEXT',
                'null' => TRUE,
                'default' => '',
            ),
        );
        $this->dbforge->add_field('id');
        $this->dbforge->add_field($fields);
        $this->dbforge->create_table('Tabletop_categories', TRUE);


        $fields = array(
            'name' => array(
                'type' => 'TEXT',
                'null' => TRUE,
                'default' => '',
            ),
            'code' => array(
                'type' => 'TEXT',
                'null' => TRUE,
                'default' => '',
            ),
            'category' => array(
                'type' => 'INTEGER',
                'default' => 0,
            ),
            'icon' => array(
                'type' => 'TEXT',
                'null' => TRUE,
                'default' => '',
            ),
            'data' => array(
                'type' => 'TEXT',
                'null' => TRUE,
                'default' => '',
            ),
            'active' => array(
                'type' => 'INTEGER',
                'default' => 1,
            ),
            'order' => array(
                'type' => 'INTEGER',
                'null' => TRUE
            )
        );
        $this->dbforge->add_field('id');
        $this->dbforge->add_field($fields);
        $this->dbforge->create_table('Tabletop_plinth_items', TRUE);

        $fields = array(
            'name' => array(
                'type' => 'TEXT',
                'null' => TRUE,
                'default' => '',
            ),
            'parent' => array(
                'type' => 'INTEGER',
                'null' => TRUE,
            ),
            'active' => array(
                'type' => 'INTEGER',
                'default' => 1,
            ),
            'order' => array(
                'type' => 'INTEGER',
                'default' => 0,
                'null' => TRUE
            ),
            'description' => array(
                'type' => 'TEXT',
                'null' => TRUE,
                'default' => '',
            ),
            'image' => array(
                'type' => 'TEXT',
                'null' => TRUE,
                'default' => '',
            ),
        );
        $this->dbforge->add_field('id');
        $this->dbforge->add_field($fields);
        $this->dbforge->create_table('Tabletop_plinth_categories', TRUE);

        $this->current_version++;
    }

    private function upd12(){


        if (!$this->db->field_exists('data', 'Settings'))
        {
            $columns = array(
                'data' => array(
                    'type' => 'TEXT',
                    'default' => '',
                )
            );

            $this->dbforge->add_column('Settings', $columns);

        }





        $this->current_version++;
    }

    private function upd13(){

        $fields = array(
            'name' => array(
                'type' => 'TEXT',
                'null' => TRUE,
                'default' => '',
            ),
            'code' => array(
                'type' => 'TEXT',
                'null' => TRUE,
                'default' => '',
            ),
            'category' => array(
                'type' => 'TEXT',
                'default' => 0,
            ),
            'icon' => array(
                'type' => 'TEXT',
                'null' => TRUE,
                'default' => '',
            ),
            'data' => array(
                'type' => 'TEXT',
                'null' => TRUE,
                'default' => '',
            ),
            'active' => array(
                'type' => 'INTEGER',
                'default' => 1,
            ),
            'order' => array(
                'type' => 'INTEGER',
                'null' => TRUE
            )
        );
        $this->dbforge->add_field('id');
        $this->dbforge->add_field($fields);
        $this->dbforge->create_table('Cokol_items_dg', TRUE);

        $this->db->query('insert into Cokol_items_dg(id, name, code, category, icon, data, active, "order")
        select id,
               name,
               code,
               category,
               icon,
               data,
               active,
               "order"
        from Cokol_items;');


        $this->db->query('drop table Cokol_items');
        $this->db->query('alter table Cokol_items_dg rename to Cokol_items;');

        $this->current_version++;
    }

    private function save_accessories()
    {
        $this->load->model('accessories_model');
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


        foreach ($data['types'] as $type) {
            $result['type_names'][$type['key']] = $type['name'];
        }


        $categories = array();
        $tags = array();

        $res_cat = array();

        foreach ($data['items'] as $item) {

            $item['description'] = htmlspecialchars_decode($item['description']);


            $result['items'][$item['id']] = $item;

            $result['items'][$item['id']]['price'] = floatval(preg_replace('/\s+/', '', $item['price']));

            $categories[] = $item['category'];

            if ($item['tags'] != '') {
                $tmp_tags = explode(',', $item['tags']);

                foreach ($tmp_tags as $tag) {
                    $tags[] = trim($tag);
                }
            }


            if ($item['type'] != 'common') {
                $result['types'][$item['type']][$item['id']] = 0;

            }
        }

        foreach ($data['types'] as $type) {
            if ($type['auto'] == 1) {
                $result['default'][$type['key']] = $type['default'];
            } else {
                $result['default'][$type['key']] = null;
            }
        }

        $categories = array_values(array_unique($categories));
        $tags = array_values(array_unique($tags));

        foreach ($categories as $cat) {
            $res_cat[] = array('category' => $cat, 'items' => array());
        }

        foreach ($data['items'] as $item) {
            foreach ($res_cat as $key => $cat) {
                if ($item['category'] == $cat['category']) {
                    $res_cat[$key]['items'][] = $item['id'];
                }
            }
        }

        $result['tags'] = $tags;
        $result['tree'] = $res_cat;

        $this->load->model('constructor_model');
        $cs = $this->constructor_model->get();

        file_put_contents(dirname(FCPATH) . '/data/accessories.json', json_encode($result));

        if (empty($cs['accessories_sub_copy'])) $cs['accessories_sub_copy'] = 0;

        if ($this->config->item('username') === 'leko-sale@yandex.ru' || $this->config->item('username') === '6011626@mail.ru' || $cs['accessories_sub_copy'] == 1) {
            foreach ($this->config->item('sub_accounts') as $sub_acc) {
                if ($sub_acc == '') continue;
                if (is_dir(dirname(dirname(FCPATH)) . '/' . $sub_acc)) {
                    file_put_contents(dirname(dirname(FCPATH)) . '/' . $sub_acc . '/data/accessories.json', json_encode($result));
                }

            }
        }
    }

    public function update_test()
    {

    }

    private function r_copy($source, $dest, $overwrite = 0)
    {
        // Check for symlinks
        if (is_link($source)) {
            return symlink(readlink($source), $dest);
        }

        // Simple copy for a file
        if (is_file($source)) {
            if ($overwrite == 1) {
                return copy($source, $dest);
            } else {
                if (file_exists($dest)) {
                    return false;
                } else {
                    return copy($source, $dest);
                }
            }

        }

        // Make destination directory
        if (!is_dir($dest)) {
            mkdir($dest);
        }

        // Loop through the folder
        $dir = dir($source);
        while (false !== $entry = $dir->read()) {
            // Skip pointers
            if ($entry == '.' || $entry == '..') {
                continue;
            }

            // Deep copy directories
            r_copy("$source/$entry", "$dest/$entry", $overwrite);
        }

        // Clean up
        $dir->close();
        return true;
    }

}