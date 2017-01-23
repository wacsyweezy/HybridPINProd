<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class MainMenu extends MX_Controller {

    function __construct() {
        parent::__construct();
        $this->load->library('form_validation');
        //$this->load->library('MY_Log');
        //$this->load->library('log');
    }

    function index() {
        $data['title'] = 'Menu Management - Hybrid Management System';
        $data['view_file'] = 'addMainMenu';
        $this->loadView($data);
    }

    function loadView($data) {
        $data['module'] = 'mainmenu';
        echo Modules::run('templ/templContent', $data);
    }

    
    public function submit() {
        $update_id = $this->input->post('mainmenuid', TRUE);

        $this->form_validation->set_rules('mainmenuname', 'mainmenuname', 'trim|required');
        $this->form_validation->set_rules('shortname', 'Short Name', 'trim|required');
       // $this->form_validation->set_rules('lastname', 'Lastname', 'trim|required');
       // $this->form_validation->set_rules('phonenumber', 'Phone Number', 'trim|required');

        if ($this->form_validation->run() === FALSE) {

            $data['title'] = 'Menu Management - Hybrid Management System';
            $data['msg'] = '<div class="alert alert-warning"><i class="fa fa-warning"></i> Error,  Main Menu Name is Empty.</div>';
            $data['view_file'] = 'addMainMenu';
            $this->loadView($data);
        } else {
            //echo 'yess';
            $mainmenuname = $this->input->post('mainmenuname', TRUE);
            $shortname = $this->input->post('shortname', TRUE);
            if ($this->check_mainmenu($mainmenuname, $shortname) != true) {
                $data = $this->get_data_from_post();
                if ($update_id > 0) {
                    $this->_update($update_id, $data);
                    $process = $data['msg'] = "<div class='alert alert-success'><i class='fa fa-check'></i> $mainmenuname menu was updated successfully</div>";
                   // $this->log($process);
                    $this->get_mainmenu_list($data);
                } else {
                    $this->_insert($data);
                    $process = $data['msg'] = "<div class='alert alert-success'><i class='fa fa-check'></i> $mainmenuname menu was saved Successfully</div>";
                   // $this->log($process);
                    $data['view_file'] = 'addMainMenu';
                    $this->loadView($data);
                }
            } else {
                $data['title'] = 'Menu Management - Hybrid Management System';
                $data['msg'] = '<div class="alert alert-warning"><i class="fa fa-warning"></i> Menu already exist.</div>';
                $data['view_file'] = 'addMainMenu';
                $this->loadView($data);
            }
        }
    }

    public function mainmenulist() {
        $this->get_mainmenu_list();
    }

    public function get_mainmenu_list($data) {
        $data['title'] = 'Menu Management - Hybrid Management System';
        $data['record'] = $this->getAll()->result();
        $data['view_file'] = 'listMainMenu';
        $data['module'] = 'mainmenu';
        echo Modules::run('templ/templContent', $data);
    }

    public function set_up() {
       
        $update_id = $this->uri->segment(3);
        if (is_numeric($update_id)) {
            $data = $this->get_data_from_db($update_id);
            $data['title'] = 'Menu Management - Hybrid Management System';
            $data['view_file'] = 'addMainMenu';
            $data['module'] = 'mainmenu';
            echo Modules::run('templ/templContent', $data);
        } else {
            $data = $this->get_data_from_post();
            // for return access Level records 
            // $data['accessLevelRecords'] = $this->getAccessLevel()->result();
        }
    }

    public function get_data_from_post() {
        $data = array();

        $data['MAIN_MENU_NAME'] = $this->input->post('mainmenuname', TRUE);
        $data['SHORT_NAME'] = $this->input->post('shortname', TRUE);
        $data['DESCRIPTION'] = $this->input->post('description', TRUE);;
       // $data['lastname'] = $this->input->post('lastname', TRUE);
        //$data['phonenumber'] = $this->input->post('phonenumber', TRUE);
        //$data['createdby'] = $_SESSION['userid'];
        //$data['datecreated'] = date('y-m-d h:i:s');

        return $data;
    }

    public function get_data_from_db($id) {
        $query = $this->get_where($id)->result();
        foreach ($query as $row) {
            $data['mainmenuname'] = $row->MAIN_MENU_NAME;
            $data['description'] = $row->DESCRIPTION;
            $data['mainmenuid'] = $row->MAIN_MENU_ID;
             $data['shortname'] = $row->SHORT_NAME;
           // $data['phonenumber'] = $row->phonenumber;
            //$data['createdBy'] = user;
        }
        return $data;
    }

    function delete($id) {
        $value = "";
        $query = $this->get_where($id)->result();
        $this->_delete($id);
        foreach ($query as $row) {
            $value = $row->MAIN_MENU_NAME;
        }
        $data['msg'] = "<div class='alert alert-success'><i class='fa fa-check'></i> $value was deleted Sucessfully</div>";

        //$this->log($process);
        $this->get_mainmenu_list($data);
    }

    function cancel() {
        // redirect to record list page
        redirect('staff/index/', 'refresh');
    }

    function check_mainmenu($var,$var1) {
        $query = "select * from hy_main_menu where main_menu_name = '$var' or short_name = '$var1'";
        $result = $this->_custom_num_rows_query($query);
        if ($result > 0) {
            return true;
        }
    }

    function log($process) {
        $date = date('y-m-d h:i:s');
        $userid = $_SESSION['userid'];
        $process = "RECIPIENT  : " . $process;
        $query = "INSERT INTO log VALUES ('', '$process','$date', $userid)";
        $this->_custom_query($query);
    }

    function getAll() {
        $this->load->model('mdl_mainmenu');
        $query = $this->mdl_mainmenu->getAll();
        return $query;
    }

    function get($order_by) {
        $this->load->model('mdl_mainmenu');
        $query = $this->mdl_mainmenu->get($order_by);
        return $query;
    }

    function get_with_limit($limit, $offset, $order_by) {
        $this->load->model('mdl_mainmenu');
        $query = $this->mdl_mainmenu->get_with_limit($limit, $offset, $order_by);
        return $query;
    }

    function get_where($id) {
        $this->load->model('mdl_mainmenu');
        $query = $this->mdl_mainmenu->get_where($id);
        return $query;
    }

    function get_where_custom($col, $value) {
        $this->load->model('mdl_mainmenu');
        $query = $this->mdl_mainmenu->get_where_custom($col, $value);
        return $query;
    }

    function _insert($data) {
        $this->load->model('Mdl_mainmenu');
        $this->mdl_mainmenu->_insert($data);
    }

    function _update($id, $data) {
        $this->load->model('mdl_mainmenu');
        $this->mdl_mainmenu->_update($id, $data);
    }

    function _delete($id) {
        $this->load->model('mdl_mainmenu');
        $this->mdl_mainmenu->_delete($id);
    }

    function count_where($column, $value) {
        $this->load->model('mdl_mainmenu');
        $count = $this->mdl_mainmenu->count_where($column, $value);
        return $count;
    }

    function get_max() {
        $this->load->model('mdl_mainmenu');
        $max_id = $this->mdl_mainmenu->get_max();
        return $max_id;
    }

    function _custom_num_rows_query($mysql_query) {
        $this->load->model('mdl_mainmenu');
        $query = $this->mdl_mainmenu->_custom_num_rows_query($mysql_query);
        return $query;
    }

    function _custom_query($mysql_query) {
        $this->load->model('mdl_mainmenu');
        $query = $this->mdl_mainmenu->_custom_query($mysql_query);
        return $query;
    }

}

?>