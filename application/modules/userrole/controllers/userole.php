<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class RolePermission extends MX_Controller {

    function __construct() {
        parent::__construct();
        $this->load->library('form_validation');
        //$this->load->library('MY_Log');
        //$this->load->library('log');
    }

    function index() {
        $data['title'] = 'Config Role Permission';
        $data['view_file'] = 'addRolePermission';
        $this->loadView($data);
    }

    function loadView($data) {
        $data['module'] = 'rolepermission';
        $data['roles'] = $this->getAllRoles()->result();
        $data['permissions'] = $this->getAllPermissions()->result();
        echo Modules::run('templ/templContent', $data);
    }

    public function submit() {
        $update_id = $this->input->post('rolepermissionid', TRUE);

        $this->form_validation->set_rules('role', 'Role', 'trim|required');
        $this->form_validation->set_rules('permissions', 'Role Permission', 'trim|required');
        // $this->form_validation->set_rules('phonenumber', 'Phone Number', 'trim|required');

        if ($this->form_validation->run() === FALSE) {

            $data['title'] = 'Config RolePermission';
            $data['msg'] = 'Error Fillname/Phone Number is Empty.';
            $data['view_file'] = 'addRolePermission';
            $this->loadView($data);
        } else {
            //echo 'yess';
            $rolepermissionname = $this->input->post('rolepermissionname', TRUE);
            if ($this->check_phonenumber($rolepermissionname) != true) {
                $name = "";
                $data = $this->get_data_from_post();
                // $name= $data['firstname'].'  '. $data['lastname'];
                if ($update_id > 0) {
                    $this->_update($update_id, $data);
                    $process = $data['msg'] = "$name, Record Updated Successfully";
                    // $this->log($process);
                    $this->get_rolepermission_list($data);
                } else {
                    $this->_insert($data);
                    $process = $data['msg'] = "$name, Record Saved Successfully";
                    // $this->log($process);
                    $data['view_file'] = 'addRolePermission';
                    $this->loadView($data);
                }
            } else {
                $data['title'] = 'Config RolePermission';
                $data['msg'] = 'RolePermission already exist.';
                $data['view_file'] = 'addRolePermission';
                $this->loadView($data);
            }
        }
    }

    public function rolepermissionlist() {
        $this->get_rolepermission_list();
    }

    public function get_rolepermission_list($data) {
        $data['title'] = 'List of RolePermissions';
        $data['record'] = $this->getAll()->result();
        $data['view_file'] = 'listRolePermission';
        $data['module'] = 'rolepermission';
        echo Modules::run('templ/templContent', $data);
        // $this->loadView($data);
    }

    public function set_up() {
        //  $data['base_url'] = 'staff/create';

        $update_id = $this->uri->segment(3);
        if (is_numeric($update_id)) {
            $data = $this->get_data_from_db($update_id);
            $data['title'] = 'RolePermission Update.';
            $data['view_file'] = 'addRolePermission';
            $data['module'] = 'rolepermission';
            echo Modules::run('templ/templContent', $data);
        } else {
            $data = $this->get_data_from_post();
            // for return access Level records 
            // $data['accessLevelRecords'] = $this->getAccessLevel()->result();
        }
    }

    public function get_data_from_post() {
        $data = array();

        $data['ROLE_NAME'] = $this->input->post('rolepermissionname', TRUE);
        $data['PSWD_LIFE_DAYS'] = 30;
        $data['IS_DEFAULT'] = 0;
        // $data['lastname'] = $this->input->post('lastname', TRUE);
        //$data['phonenumber'] = $this->input->post('phonenumber', TRUE);
        //$data['createdby'] = $_SESSION['userid'];
        //$data['datecreated'] = date('y-m-d h:i:s');

        return $data;
    }

    public function get_data_from_db($id) {
        $query = $this->get_where($id)->result();
        foreach ($query as $row) {
            $data['rolepermissionid'] = $row->ROLE_ID;
            $data['rolepermissionname'] = $row->ROLE_NAME;
            // $data['lastname'] = $row->lastname;
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
            $value = $row->firstname . '  ' . $row->lastname;
        }
        $process = $data['msg'] = "$value, Deleted Sucessfully";

        $this->log($process);
        $this->get_rolepermission_list($data);
    }

    function cancel() {
        // redirect to record list page
        redirect('staff/index/', 'refresh');
    }

    function check_phonenumber($var) {
        $query = "select * from hy_user_rolepermissions where rolepermission_name = '$var'";
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

    function getAllRoles() {
        $this->load->model('mdl_rolepermission');
        $query = $this->mdl_rolepermission->getUserRoles();
        return $query;
    }

    function getAllPermissions() {
        $this->load->model('mdl_rolepermission');
        $query = $this->mdl_rolepermission->getPermissions();
        return $query;
    }

    function getAll() {
        $this->load->model('mdl_rolepermission');
        $query = $this->mdl_rolepermission->getAll();
        return $query;
    }

    function get($order_by) {
        $this->load->model('mdl_rolepermission');
        $query = $this->mdl_rolepermission->get($order_by);
        return $query;
    }

    function get_with_limit($limit, $offset, $order_by) {
        $this->load->model('mdl_rolepermission');
        $query = $this->mdl_rolepermission->get_with_limit($limit, $offset, $order_by);
        return $query;
    }

    function get_where($id) {
        $this->load->model('mdl_rolepermission');
        $query = $this->mdl_rolepermission->get_where($id);
        return $query;
    }

    function get_where_custom($col, $value) {
        $this->load->model('mdl_rolepermission');
        $query = $this->mdl_rolepermission->get_where_custom($col, $value);
        return $query;
    }

    function _insert($data) {
        $this->load->model('mdl_rolepermission');
        $this->mdl_rolepermission->_insert($data);
    }

    function _update($id, $data) {
        $this->load->model('mdl_rolepermission');
        $this->mdl_rolepermission->_update($id, $data);
    }

    function _delete($id) {
        $this->load->model('mdl_rolepermission');
        $this->mdl_rolepermission->_delete($id);
    }

    function count_where($column, $value) {
        $this->load->model('mdl_rolepermission');
        $count = $this->mdl_rolepermission->count_where($column, $value);
        return $count;
    }

    function get_max() {
        $this->load->model('mdl_rolepermission');
        $max_id = $this->mdl_rolepermission->get_max();
        return $max_id;
    }

    function _custom_num_rows_query($mysql_query) {
        $this->load->model('mdl_rolepermission');
        $query = $this->mdl_rolepermission->_custom_num_rows_query($mysql_query);
        return $query;
    }

    function _custom_query($mysql_query) {
        $this->load->model('mdl_rolepermission');
        $query = $this->mdl_rolepermission->_custom_query($mysql_query);
        return $query;
    }

}

?>