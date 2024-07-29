<?php
class Common_model extends CI_Model {

	public function __construct()
	{
		$this->load->database();
        $this->db_common = $this->load->database('common', TRUE);

        $this->bp_def_db = $this->db;
        $this->is_common = 0;
        if (strpos($this->router->fetch_method(), '_common') !== false) {
//            if( basename(dirname(FCPATH)) !== 'dev' ) exit;


            $this->is_common = 1;

            $this->bp_def_db = $this->db_common;
        }

//        print_pre($this->router->fetch_method());


	}

	private $no_code = array(
        'Facades_items' => 1,
        'Handles_items' => 1,
        'Modules_items' => 1,
        'Module_sets_items' => 1,
        'Material_types_items' => 1,
        'Catalogue_items' => 1,
        'Model3d_items' => 1,
        'Kitchen_models' => 1,
    );

	public function get_row($table)
	{
		return $this->bp_def_db->get($table)->row_array();
	}

    public function get_row_where($table, $where)
    {
        $query = $this->bp_def_db->get_where($table, $where);
        return $query->row_array();
	}

	public function update_row($table, $data)
	{
	    if($this->is_common == 1) if (!check_access_to_common_db()) exit;
		$this->bp_def_db->update($table, $data);
	}


    public function get_where($table, $where)
    {
        $query = $this->bp_def_db->get_where($table, $where);
        return $query->result_array();
    }


    public function get_item_by_id($table, $id)
    {
        $this->bp_def_db->where('id', $id);
        $query = $this->bp_def_db->get($table);
        return $query->row_array();
    }
    public function get_items_by_id($table, $ids)
    {
        $this->bp_def_db->where_in('id', $ids);
        $query = $this->bp_def_db->get($table);
        return $query->result_array();
    }

    public function get_data_all_by_order($table, $order)
    {
        if(!$this->bp_def_db->table_exists($table)){
            return array();
        }

        $this->bp_def_db->order_by('order',$order);
        $query = $this->bp_def_db->get($table);
        return $query->result_array();
    }

    public function get_data_all_by_id($table, $order)
    {
        if(!$this->bp_def_db->table_exists($table)){
            return array();
        }

        $this->bp_def_db->order_by('id',$order);
        $query = $this->bp_def_db->get($table);
        return $query->result_array();
    }


    public function get_data_all_by_order_module_set($table, $set_id, $order)
    {

        if(!$this->bp_def_db->table_exists($table)){
            return array();
        }

        $this->bp_def_db->order_by('order',$order);
        $this->bp_def_db->from($table);
        $this->bp_def_db->where('set_id',$set_id);
        $query = $this->bp_def_db->get();
        return $query->result_array();
    }

    public function get_data_all_by_order_module_set_temp($table, $set_id, $order)
    {



        $this->db_common->order_by('order',$order);
        $this->db_common->from($table);
        $this->db_common->where('set_id',$set_id);
        $query = $this->db_common->get();
        return $query->result_array();
    }



    public function get_data_pagination($table, $cat_table, $category, $limit, $offset, $search = false)
    {
        if($category == 0){
            if($table == 'Accessories'){
                $this->bp_def_db->order_by('id','DESC');
            } else {
                $this->bp_def_db->order_by('order','ASC');
            }

            $this->bp_def_db->from($table);

            if($search){

                if(isset($this->no_code[$table])){
                    $this->bp_def_db->where("(`name` LIKE '%$search%')", NULL, FALSE);
                } else {

                    if($table == 'Accessories'){
                        $this->bp_def_db->where("(`name` LIKE '%$search%' OR `code` LIKE '%$search%' OR `category` LIKE '%$search%')", NULL, FALSE);
                    } else {
                        $this->bp_def_db->where("(`name` LIKE '%$search%' OR `code` LIKE '%$search%' )", NULL, FALSE);
                    }


                }

            }
            $this->bp_def_db->limit($limit, $offset);
            $query = $this->bp_def_db->get();


            return $query->result_array();

        } else {
            $children = $this->get_where($cat_table, array('parent' => $category));

            $this->bp_def_db->from($table);

            if(count($children) > 0){

                $str = "(`category` = '" . $category . "'";

                foreach ($children as $child){
                    $str .= " OR `category` = '" . $child['id'] . "'";
                }
                $str.=")";

                $this->bp_def_db->where($str, NULL, FALSE);
            } else {

                if($table == 'Catalogue_items'){
                    $this->db->like('category', '"' . $category . '"');
                } else {
                    $this->bp_def_db->where('category', $category);
                }



            }

            if($search){
                if(isset($this->no_code[$table])){
                    $this->bp_def_db->where("(`name` LIKE '%$search%')", NULL, FALSE);
                } else {
                    $this->bp_def_db->where("(`name` LIKE '%$search%' OR `code` LIKE '%$search%' )", NULL, FALSE);
                }
            }

            $this->bp_def_db->limit($limit, $offset);
            $this->bp_def_db->order_by('order','ASC');


            $query = $this->bp_def_db->get();

            return $query->result_array();
        }
    }
    public function get_items_count($table, $cat_table, $category, $search = false)
    {

        if($category == 0){

            $this->bp_def_db->from($table);

            if($search){
                if(isset($this->no_code[$table])){
                    $this->bp_def_db->where("(`name` LIKE '%$search%')", NULL, FALSE);
                } else {
                    $this->bp_def_db->where("(`name` LIKE '%$search%' OR `code` LIKE '%$search%' )", NULL, FALSE);
                }
            }

            return $this->bp_def_db->count_all_results();

//            return $this->bp_def_db->count_all($table);
        } else {
            $children = $this->get_where($cat_table, array('parent' => $category));

            $this->bp_def_db->from($table);

            if(count($children) > 0){

                $str = "(`category` = '" . $category . "'";

                foreach ($children as $child){

                    $str .= " OR `category` = '" . $child['id'] . "'";



//                    $this->bp_def_db->or_where('category',$child['id']);
                }
                $str.=")";

                $this->bp_def_db->where($str, NULL, FALSE);
            } else {
                $this->bp_def_db->where('category', $category);
            }

            if($search){
                if(isset($this->no_code[$table])){
                    $this->bp_def_db->where("(`name` LIKE '%$search%')", NULL, FALSE);
                } else {
                    $this->bp_def_db->where("(`name` LIKE '%$search%' OR `code` LIKE '%$search%' )", NULL, FALSE);
                }

//                $this->bp_def_db->like('name', $search);
//                $this->bp_def_db->or_like('code', $search);
            }


//            $this->bp_def_db->where('category', $category);
//
//            if(count($children) > 0){
//                foreach ($children as $child){
//                    $this->bp_def_db->or_where('category',$child['id']);
//                }
//            }
//
//            if($search){
//                $this->bp_def_db->like('name', $search);
//                $this->bp_def_db->or_like('code', $search);
//            }

            return $this->bp_def_db->count_all_results();
        }
    }

    public function get_data_pagination_module_set($table, $cat_table, $set_id, $category, $limit, $offset, $search = false)
    {



        if($category == 0){
            $this->bp_def_db->order_by('order','ASC');
            $this->bp_def_db->from($table);
            $this->bp_def_db->where('set_id',$set_id);


            $this->bp_def_db->limit($limit, $offset);
            $query = $this->bp_def_db->get();

            return $query->result_array();

        } else {
            $children = $this->get_where($cat_table, array('parent' => $category));

            $this->bp_def_db->from($table);
            $this->bp_def_db->where('set_id',$set_id);

            if(count($children) > 0){

                $str = "(`category` = '" . $category . "'";

                foreach ($children as $child){
                    $str .= " OR `category` = '" . $child['id'] . "'";
                }
                $str.=")";

                $this->bp_def_db->where($str, NULL, FALSE);
            } else {
                $this->bp_def_db->where('category', $category);
            }



            $this->bp_def_db->limit($limit, $offset);
            $this->bp_def_db->order_by('order','ASC');


            $query = $this->bp_def_db->get();

            return $query->result_array();
        }
    }
    public function get_items_count_module_set($table, $cat_table, $set_id, $category, $search = false)
    {

        if($category == 0){

            $this->bp_def_db->from($table);
            $this->bp_def_db->where('set_id',$set_id);



            return $this->bp_def_db->count_all_results();

//            return $this->bp_def_db->count_all($table);
        } else {
            $children = $this->get_where($cat_table, array('parent' => $category));

            $this->bp_def_db->from($table);
            $this->bp_def_db->where('set_id',$set_id);

            if(count($children) > 0){

                $str = "(`category` = '" . $category . "'";

                foreach ($children as $child){

                    $str .= " OR `category` = '" . $child['id'] . "'";



//                    $this->bp_def_db->or_where('category',$child['id']);
                }
                $str.=")";

                $this->bp_def_db->where($str, NULL, FALSE);
            } else {
                $this->bp_def_db->where('category', $category);
            }




//            $this->bp_def_db->where('category', $category);
//
//            if(count($children) > 0){
//                foreach ($children as $child){
//                    $this->bp_def_db->or_where('category',$child['id']);
//                }
//            }
//
//            if($search){
//                $this->bp_def_db->like('name', $search);
//                $this->bp_def_db->or_like('code', $search);
//            }

            return $this->bp_def_db->count_all_results();
        }
    }

    public function get_data_pagination_multi($params)
    {

        $table = $params['table'];
        $cat_table = $params['cat_table'];
        $category = $params['category'];
        $categories = $params['categories'];
        $limit = $params['limit'];
        $offset = $params['offset'];
        $search = $params['search'];
        $cats_multi = $params['cats_multi'];
        $set_id = $params['set_id'];



//        print_pre($params);


        if($category == 0){
            if($table == 'Accessories'){
                $this->bp_def_db->order_by('id','DESC');
            } else {
                $this->bp_def_db->order_by('order','ASC');
            }

            $this->bp_def_db->from($table);
            if(isset($set_id)) $this->bp_def_db->where('set_id',$set_id);

            if(count($categories)){
                $this->bp_def_db->where_in('category', $categories);
            }


            if($search){

                if(isset($this->no_code[$table])){
                    $this->bp_def_db->where("(`name` LIKE '%$search%')", NULL, FALSE);
                } else {
                    if($table == 'Accessories'){
                        $this->bp_def_db->where("(`name` LIKE '%$search%' OR `code` LIKE '%$search%' OR `category` LIKE '%$search%')", NULL, FALSE);
                    } else {
                        $this->bp_def_db->where("(`name` LIKE '%$search%' OR `code` LIKE '%$search%' )", NULL, FALSE);
                    }


                }

            }
            $this->bp_def_db->limit($limit, $offset);
            $query = $this->bp_def_db->get();


            return $query->result_array();

        } else {



            $children = $this->get_where($cat_table, array('parent' => $category));



            $this->bp_def_db->from($table);
            if(isset($set_id)) $this->bp_def_db->where('set_id',$set_id);

            if(count($categories)){
                $this->bp_def_db->where_in('category', $categories);

            }
            if(count($children) > 0){


                if($cats_multi){

                    $str = "(`category` LIKE '%\"" . $category . "\"%'";

                    foreach ($children as $child){
                        $str .= " OR `category` LIKE '%\"" . $child['id'] . "\"%'";
                    }
                    $str.=")";
                } else {

                    $str = "(`category` = '" . $category . "'";

                    foreach ($children as $child){
                        $str .= " OR `category` = '" . $child['id'] . "'";
                    }
                    $str.=")";
                }



                $this->bp_def_db->where($str, NULL, FALSE);
            } else {

                if($table == 'Catalogue_items'){
                    $this->bp_def_db->like('category', '"' . $category . '"');
                } else {

                    $this->bp_def_db->where('category', $category);
                }
            }

            if($search){
                if(isset($this->no_code[$table])){
                    $this->bp_def_db->where("(`name` LIKE '%$search%')", NULL, FALSE);
                } else {
                    $this->bp_def_db->where("(`name` LIKE '%$search%' OR `code` LIKE '%$search%' )", NULL, FALSE);
                }
            }

            $this->bp_def_db->limit($limit, $offset);
            $this->bp_def_db->order_by('order','ASC');


            $query = $this->bp_def_db->get();

            return $query->result_array();
        }
    }
    public function get_items_count_multi($params)
    {

        $table = $params['table'];
        $cat_table = $params['cat_table'];
        $category = $params['category'];
        $categories = $params['categories'];
        $search = $params['search'];
        $cats_multi = $params['cats_multi'];
        $set_id = $params['set_id'];




        if($category == 0){

            $this->bp_def_db->from($table);
            if(isset($set_id)) $this->bp_def_db->where('set_id',$set_id);
            if(count($categories)){
                $this->bp_def_db->where_in('category', $categories);
            }


            if($search){
                if(isset($this->no_code[$table])){
                    $this->bp_def_db->where("(`name` LIKE '%$search%')", NULL, FALSE);
                } else {
                    $this->bp_def_db->where("(`name` LIKE '%$search%' OR `code` LIKE '%$search%' )", NULL, FALSE);
                }
            }

            return $this->bp_def_db->count_all_results();

//            return $this->bp_def_db->count_all($table);
        } else {
            $children = $this->get_where($cat_table, array('parent' => $category));

            $this->bp_def_db->from($table);
            if(isset($set_id)) $this->bp_def_db->where('set_id',$set_id);
            if(count($categories)){
                $this->bp_def_db->where_in('category', $categories);
            }

            if(count($children) > 0){

                if($cats_multi){
                    $str = "(`category` LIKE '%\"" . $category . "\"%'";

                    foreach ($children as $child){
                        $str .= " OR `category` LIKE '%\"" . $child['id'] . "\"%'";
                    }
                    $str.=")";
                } else {
                    $str = "(`category` = '" . $category . "'";

                    foreach ($children as $child){
                        $str .= " OR `category` = '" . $child['id'] . "'";
                    }
                    $str.=")";
                }

//                $str = "(`category` = '" . $category . "'";
//
//                foreach ($children as $child){
//
//                    $str .= " OR `category` = '" . $child['id'] . "'";
//
//
//
////                    $this->bp_def_db->or_where('category',$child['id']);
//                }
//                $str.=")";

                $this->bp_def_db->where($str, NULL, FALSE);
            } else {
                if($table == 'Catalogue_items'){
                    $this->bp_def_db->like('category', '"' . $category . '"');
                } else {
                    $this->bp_def_db->where('category', $category);
                }
            }

            if($search){
                if(isset($this->no_code[$table])){
                    $this->bp_def_db->where("(`name` LIKE '%$search%')", NULL, FALSE);
                } else {
                    $this->bp_def_db->where("(`name` LIKE '%$search%' OR `code` LIKE '%$search%' )", NULL, FALSE);
                }

//                $this->bp_def_db->like('name', $search);
//                $this->bp_def_db->or_like('code', $search);
            }


//            $this->bp_def_db->where('category', $category);
//
//            if(count($children) > 0){
//                foreach ($children as $child){
//                    $this->bp_def_db->or_where('category',$child['id']);
//                }
//            }
//
//            if($search){
//                $this->bp_def_db->like('name', $search);
//                $this->bp_def_db->or_like('code', $search);
//            }

            return $this->bp_def_db->count_all_results();
        }
    }

    public function get_categories_multi($params)
    {
        $table = $params['table'];
        $categories = $params['categories'];
        if(count($categories)){
            $this->bp_def_db->from($table);

//            print_pre($params);

            $this->bp_def_db->where_in('id', $categories);
            $this->bp_def_db->or_where_in('parent', $categories);
            $query = $this->bp_def_db->get();
            return $query->result_array();
        } else {
            return $this->get_data_all_by_order($table, 'ASC');
        }
    }



    public function add_data($table, $data)
    {
        if($this->is_common == 1) if (!check_access_to_common_db()) exit;
        $this->bp_def_db->insert($table, $data);
        return $this->bp_def_db->insert_id();
    }




    public function update_where($table, $where, $field, $data)
    {
        if($this->is_common == 1) if (!check_access_to_common_db()) exit;
        $this->bp_def_db->where($where, $field);
        $this->bp_def_db->update($table, $data);
        return $field;
    }



    public function update_batch($table, $data, $field)
    {
        if($this->is_common == 1) if (!check_access_to_common_db()) exit;
        $this->bp_def_db->update_batch($table, $data, $field);
    }

    public function add_batch($table, $data)
    {
        if($this->is_common == 1) if (!check_access_to_common_db()) exit;
        $this->bp_def_db->insert_batch($table, $data);
    }

    public function remove_batch($table, $data)
    {
        if($this->is_common == 1) if (!check_access_to_common_db()) exit;
        if (!empty($data)) {
            $this->bp_def_db->where_in('id', $data);
            $this->bp_def_db->delete($table);
        }
    }


    public function remove_where($table, $where, $field)
    {
        if($this->is_common == 1) if (!check_access_to_common_db()) exit;
        if(is_array($field)){
            $this->bp_def_db->where_in($where, $field);
        }else{
            $this->bp_def_db->where($where, $field);
        }
        $delete = $this->bp_def_db->delete($table);
        return $delete ? true : false;
    }


    public function set_category_active( $table, $id, $val ) {
        if($this->is_common == 1) if (!check_access_to_common_db()) exit;
        $cat = $this->get_where( $table, array('id' => $id) );
        if($cat[0]){
            $cat = $cat[0];
        }

        if($cat['parent']){
            $parent = $this->get_where( $table, array('id' => $cat['parent']) );

            if($parent[0]){
                $parent = $parent[0];
            }

            if($parent['active']){
                $this->bp_def_db->where('id', $id);
                $this->bp_def_db->update($table, array('active'=>$val));

                echo 'ok';

            } else {
                echo 'no';
            }
        } else {
            $this->bp_def_db->where('id', $id);
            $this->bp_def_db->update($table, array('active'=>$val));
            $children = $this->get_where($table, array('parent' => $id));
            if(count($children) > 0){
                foreach ($children as $child){
                    $this->bp_def_db->where('id', $child['id']);
                    $this->bp_def_db->update($table, array('active' => $val));
                }
            }
            echo 'ok';
        }
    }


    public function get_all_tables()
    {
        return $this->db_dev->get('sqlite_master')->result_array();

    }


    public function get_row_common($table)
    {
        return $this->db_common->get($table)->row_array();
    }

    public function get_row_where_common($table, $where)
    {
        $query = $this->db_common->get_where($table, $where);
        return $query->row_array();
    }

    public function update_row_common($table, $data)
    {
        $this->db_common->update($table, $data);
    }

    public function get_where_common($table, $where)
    {
        $query = $this->db_common->get_where($table, $where);
        return $query->result_array();
    }

    public function get_data_all_by_order_common($table, $order)
    {
        $this->db_common->order_by('order',$order);
        $query = $this->db_common->get($table);
        return $query->result_array();
    }

    public function update_where_common($table, $where, $field, $data)
    {
        if($this->is_common == 1) if (!check_access_to_common_db()) exit;
        $this->db_common->where($where, $field);
        $this->db_common->update($table, $data);
        return $field;
    }



    public function get_data_all_by_order_select($table, $select = '*', $order = 'ASC')
    {
        if(!$this->bp_def_db->table_exists($table)){
            return array();
        }

        $this->bp_def_db->order_by('order',$order);
        $this->bp_def_db->select($select);
        $query = $this->bp_def_db->get($table);
        return $query->result_array();
    }


}