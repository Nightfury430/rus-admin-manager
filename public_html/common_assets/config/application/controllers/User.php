<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class User extends CI_Controller {
    public function __construct() {
        parent::__construct();
        $this->load->helper('url_helper');
        $this->load->model('User_model');
        $this->load->library('session');
    }

    public function index() {
        $this->load->view('user/index');
    }
}