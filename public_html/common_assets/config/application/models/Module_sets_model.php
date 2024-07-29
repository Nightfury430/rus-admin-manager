<?php
class Module_sets_model extends CI_Model {

    public function __construct()
    {
        $this->load->database();
    }

    public function get_sets()
    {
        $query = $this->db->get('Module_sets');
        return $query->result_array();
    }

    public function get_set($id)
    {
        $query = $this->db->get_where('Module_sets', array('id' => $id));
        return $query->row_array();
    }

    public function check_set_is_empty($set_id)
    {
        $cats = $this->get_categories_by_set_id($set_id);
        $modules = $this->get_all_items_by_set_id($set_id);


        if( count($cats) == 3 && count($modules) ==0 ){
            return true;
        } else {
            return false;
        }

//        print_r( count($cats) );
//        echo '<br>';
//        print_r( count($modules) );
    }

    public function add_set($data)
    {
        $this->db->insert('Module_sets', $data);
        $set_id = $this->db->insert_id();

        $cat_data = array();

        $cat_data['bottom_modules_id'] = $this->add_set_categories_top($set_id, 'Нижние модули');
        $cat_data['top_modules_id'] = $this->add_set_categories_top($set_id, 'Верхние модули');
        $cat_data['penals_id'] = $this->add_set_categories_top($set_id, 'Пеналы');

        $this->db->where('id', $set_id);
        $this->db->update('Module_sets', $cat_data);

        return $set_id;
    }

    public function edit_set($id, $data)
    {
        $this->db->where('id', $id);
        $this->db->update('Module_sets', $data);
    }

    public function remove_set($id)
    {
        $this->db->delete('Module_sets', array('id' => $id));
        $this->db->delete('Module_sets_categories', array('set_id' => $id));
        $this->db->delete('Module_sets_items', array('set_id' => $id));
    }

    public function add_set_categories_top($set_id, $name)
    {

        $data = array();

        $data['set_id'] = $set_id;
        $data['name'] = $name;
        $data['parent'] = 0;
        $data['active'] = 1;

        $this->db->insert('Module_sets_categories', $data);
        return $this->db->insert_id();
    }

    public function get_categories_by_set_id($id)
    {
        $this->db->order_by('order','ASC');
        $query = $this->db->get_where('Module_sets_categories', array('set_id' => $id));
        return $query->result_array();
    }

    public function add_category($data)
    {
        return $this->db->insert('Module_sets_categories', $data);
    }

    public function get_category($id)
    {
        $query = $this->db->get_where('Module_sets_categories', array('id' => $id));
        return $query->row_array();
    }


    //todo copy
    public function add_category_ajax($data)
    {
        $this->db->insert('Module_sets_categories', $data);
        return $this->db->insert_id();
    }

    //todo copy
    public function categories_order_update($set_id, $data)
    {
        $this->db->where('set_id', $set_id);
        $this->db->update_batch('Module_sets_categories', $data, 'id');
    }

    //todo copy
    public function update_category_ajax($id, $data)
    {
        $this->db->where('id', $id);
        $this->db->update('Module_sets_categories', $data);
        return $id;
    }

    public function set_active( $id, $val ) {
        $this->db->where('id', $id);
        $this->db->update('Module_sets_categories', array('active'=>$val));
    }

    public function update_category($id, $data)
    {
        $this->db->where('id', $id);
        $this->db->update('Module_sets_categories', $data);
    }

    public function get_active_categories_by_set_id($set_id)
    {
        $this->db->from('Module_sets_categories');
        $this->db->where('active',1);
        $this->db->where('set_id',$set_id);
        $query = $this->db->get();
        return $query->result_array();
    }


    public function remove_category($id)
    {
        if(is_array($id)){
            $this->db->where_in('id', $id);
        }else{
            $this->db->where('id', $id);
        }
        $delete = $this->db->delete('Module_sets_categories');
        return $delete?true:false;
    }

    public function get_categories_by_parent($id)
    {
        $query = $this->db->get_where('Module_sets_categories', array('parent' => $id));
        return $query->result_array();
    }

    public function get_category_children($id)
    {
        $query = $this->db->get_where('Module_sets_categories', array('parent' => $id));
        return $query->result_array();
    }

//    public function get_items($set_id, $top_category, $category, $offset, $limit)
//    {
//        if($category == 0){
//
//            $children = $this->get_categories_by_parent($top_category);
//
//            $this->db->from('Module_sets_items');
//            $this->db->where('category',$top_category);
//            $this->db->where('set_id',$set_id);
//            if(count($children) > 0){
//                foreach ($children as $child){
//                    $this->db->or_where('category',$child['id']);
//                    $this->db->where('set_id',$set_id);
//                }
//            }
//
//            $this->db->limit($limit, $offset);
//            $this->db->order_by('order','ASC');
//            $query = $this->db->get();
//
////            echo '<pre>';
////            print_r($query);
////            echo '</pre>';
////            exit;
//            return $query->result_array();
//
//        } else {
//
//            $children = $this->get_categories_by_parent($category);
//
//            $this->db->from('Module_sets_items');
//            $this->db->where('category',$category);
//            $this->db->where('set_id',$set_id);
//
//            if(count($children) > 0){
//                foreach ($children as $child){
//                    $this->db->or_where('category',$child['id']);
//                    $this->db->where('set_id',$set_id);
//                }
//            }
//
//            $this->db->limit($limit, $offset);
//            $this->db->order_by('order','ASC');
//            $query = $this->db->get();
//            return $query->result_array();
//        }
//    }

    public function get_items($set_id, $category, $offset, $limit)
    {

        if($category == 0){

            $this->db->from('Module_sets_items');
            $this->db->where('set_id',$set_id);

            $this->db->limit($limit, $offset);
//            $this->db->limit($offset, $limit);
            $this->db->order_by('order','ASC');
            $query = $this->db->get();

//            print_pre($query);

//            echo '<pre>';
//            print_r($query);
//            echo '</pre>';
//            exit;
            return $query->result_array();

        } else {

            $children = $this->get_categories_by_parent($category);

            $this->db->from('Module_sets_items');
            $this->db->where('category',$category);
            $this->db->where('set_id',$set_id);

            if(count($children) > 0){
                foreach ($children as $child){
                    $this->db->or_where('category',$child['id']);
                    $this->db->where('set_id',$set_id);
                }
            }

            $this->db->limit($limit, $offset);
            $this->db->order_by('order','ASC');
            $query = $this->db->get();
            return $query->result_array();
        }
    }

    public function get_all_items_by_set_id($set_id)
    {
        $this->db->order_by('order','ASC');
        $this->db->from('Module_sets_items');
        $this->db->where('set_id',$set_id);
        $query = $this->db->get();
        return $query->result_array();
    }



    public function get_active_items_by_set_id($set_id)
    {
        $this->db->order_by('order','ASC');
        $this->db->from('Module_sets_items');
        $this->db->where('active',1);
        $this->db->where('set_id',$set_id);
        $query = $this->db->get();
        return $query->result_array();
    }

    public function get_all_items()
    {
        $this->db->order_by('order','ASC');
        $query = $this->db->get('Module_sets_items');
        return $query->result_array();
    }

    public function get_all_categories()
    {
        $this->db->order_by('order','ASC');
        $query = $this->db->get('Module_sets_categories');
        return $query->result_array();
    }


    public function get_all_active_items()
    {
        $this->db->order_by('order','ASC');
        $this->db->from('Module_sets_items');
        $this->db->where('active',1);
        $query = $this->db->get();
        return $query->result_array();
    }

    public function get_item($id)
    {
        $query = $this->db->get_where('Module_sets_items', array('id' => $id));
        return $query->row_array();
    }


    public function add_item()
    {
        $this->db->insert('Module_sets_items', array('params'=>''));
        return $this->db->insert_id();
    }

    public function add_item_with_data($data)
    {
        $this->db->insert('Module_sets_items', $data);
        return $this->db->insert_id();
    }

    public function add_item_data($id, $data)
    {
        $this->db->where('id', $id);
        $this->db->update('Module_sets_items', $data);
    }

    public function remove_items($id)
    {
        if(is_array($id)){
            $this->db->where_in('id', $id);
        }else{
            $this->db->where('id', $id);
        }
        $delete = $this->db->delete('Module_sets_items');
        return $delete?true:false;
    }

//    public function get_items_count($set_id, $top_category, $category)
//    {
//        if($category == 0){
//
//            $children = $this->get_category_children($top_category);
//            $this->db->from("Module_sets_items");
//            $this->db->where('set_id', $set_id);
//            $this->db->where('category', $category);
//            if(count($children) > 0){
//                foreach ($children as $child){
//                    $this->db->or_where('category',$child['id']);
//                    $this->db->where('set_id', $set_id);
//                }
//            }
//            $this->db->or_where('category', $top_category);
//            $this->db->where('set_id', $set_id);
//
//            return $this->db->count_all_results();
//
////            return $this->db->count_all('Modules_items');
//        } else {
//
//
//
//            $children = $this->get_category_children($category);
//            $this->db->from("Module_sets_items");
//            $this->db->where('category', $category);
//            $this->db->where('set_id', $set_id);
//            if(count($children) > 0){
//                foreach ($children as $child){
//                    $this->db->or_where('category',$child['id']);
//                    $this->db->where('set_id', $set_id);
//                }
//            }
//            return $this->db->count_all_results();
//        }
//
//    }

    public function get_items_count($set_id,  $category)
    {
        if($category == 0){

            $this->db->from("Module_sets_items");
            $this->db->where('set_id', $set_id);
            return $this->db->count_all_results();
        } else {



            $children = $this->get_category_children($category);
            $this->db->from("Module_sets_items");
            $this->db->where('category', $category);
            $this->db->where('set_id', $set_id);
            if(count($children) > 0){
                foreach ($children as $child){
                    $this->db->or_where('category',$child['id']);
                    $this->db->where('set_id', $set_id);
                }
            }
            return $this->db->count_all_results();
        }

    }


    public function get_top_categories($set_id)
    {
        $this->db->from('Module_sets_categories');
        $this->db->where('parent',0);
        $this->db->where('set_id',$set_id);
        $query = $this->db->get();
        return $query->result_array();
    }

	public function get_sets_count() {
		return $this->db->count_all('Module_sets');
    }

}