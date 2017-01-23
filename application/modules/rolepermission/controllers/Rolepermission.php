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
//        $data['title'] = 'Config Role Permission';
//        $data['view_file'] = 'addRolePermission';
//        $this->loadView($data);
        $this->get_rolepermission_list();
    }

    function loadView($data) {
        $data['module'] = 'rolepermission';
        // $data['roles'] = $this->getAllRoles()->result();
        // $data['permissions'] = $this->getAllPermissions()->result();
        echo Modules::run('templ/templContent', $data);
    }

    public function raw_value($record) {
        $data1 = array();
        for ($i = 0; $i < count($record); $i++) {
            $data1[$i] = $record[$i]->PERMISSION_ID;
        }
        //  print_r($data1);
        return $data1;
    }

    public function submit() {
        $permid[] = Array();
        //check for delete first....

        $this->form_validation->set_rules('permissionids[]', 'Role Permission', 'trim|required');
        if ($this->form_validation->run() === FALSE) {
           $data['title'] = 'Role Access Permission Settings - Hybrid Management System';
            $data['msg'] = '<div class="alert alert-warning"><i class="fa fa-warning"></i> Error. permissions not selected</div>';
            $data['view_file'] = 'addRolePermission';
            $this->loadView($data);
        } else {
            //echo 'yess';
            //check for delete first....
            $permid = $this->input->post('permissionids[]', TRUE);
            $roleid = $this->input->post('roleid', TRUE);
            
            if ($this->check_rolepermission($roleid) == true) {
                $this->_delete($roleid);
            }
            foreach ($permid as $pid) {
                $data = $this->get_data_from_post($pid);
                $this->_insert($data);
            }


            //  print_r($permid);
            //$process = $data['msg'] = "Record Saved Successfully";
            // $data['view_file'] = 'addRolePermission';
            $this->reset_up($roleid);
            //  $this->loadView($data);
            //}
        }
    }

    public function rolepermissionlist() {
        $this->get_rolepermission_list();
    }

    public function get_rolepermission_list($data) {
		$data['title'] = 'Role Access Permission Settings - Hybrid Management System'; 
        $data['record'] = $this->getAllRoles()->result();
        $data['view_file'] = 'listRolePermission';
        $data['module'] = 'rolepermission';
        echo Modules::run('templ/templContent', $data);
        // $this->loadView($data);
    }

    public function set_up() {
        $role_id = $this->uri->segment(3);
        if (is_numeric($role_id)) {
            $data['rolename'] = $this->getRoleName($role_id);
            $data['roleid'] = $role_id;
            $data['permissions'] = $this->raw_value1($this->getAllPermissions()->result(), $role_id);
            // print_r($data);
            $data['title'] = 'Role Access Permission Settings - Hybrid Management System';
            $data['view_file'] = 'addRolePermission';
            $data['module'] = 'rolepermission';
            echo Modules::run('templ/templContent', $data);
        } else {
            $data = $this->get_data_from_post();
            // for return access Level records 
            // $data['accessLevelRecords'] = $this->getAccessLevel()->result();
        }
    }

    public function reset_up($role_id) {
        //print $role_id;
        $data['msg'] = '<div class="alert alert-success"><i class="fa fa-check"></i> Record saved successfully</div>';
        if (is_numeric($role_id)) {
            $data['rolename'] = $this->getRoleName($role_id);
            $data['roleid'] = $role_id;
            $data['permissions'] = $this->raw_value1($this->getAllPermissions()->result(), $role_id);
            // print_r($data);
            $data['title'] = 'Role Access Permission Settings - Hybrid Management System';
            $data['view_file'] = 'addRolePermission';
            $data['module'] = 'rolepermission';
            echo Modules::run('templ/templContent', $data);
        } else {
            $data = $this->get_data_from_post();
        }
    }

    public function getRoleName($id) {
        $query = $this->get_role_where($id)->result();
        foreach ($query as $row) {
            $data = $row->ROLE_NAME;
        }
        return $data;
    }

    public function raw_value1($record, $role_id) {
        $data1 = array();
        for ($i = 0; $i < count($record); $i++) {
            $data1[$i]['PERMISSION_ID'] = $record[$i]->PERMISSION_ID;
            $data1[$i]['DISPLAY_NAME'] = $record[$i]->DISPLAY_NAME;
			$data1[$i]['DESCRIPTION'] = $record[$i]->DESCRIPTION;
            // $data1[$i]['ACTION_NAME'] = $record[$i]->ACTION_NAME;
            // $data1[$i]['DESCRIPTION'] = $record[$i]->DESCRIPTION;
            // $data1[$i]['MAIN_MENU_ID'] = $record[$i]->MAIN_MENU_ID;
            $data1[$i]['CHECK_VALUE'] = $this->check_value($record[$i]->PERMISSION_ID, $role_id);
        }
//        print_r($data1);
        return $data1;
    }

    public function getAllAssignedPermissions($id) {
        $record = $this->get_all_assigned_perm_where($id)->result();
        $data = array();
        for ($i = 0; $i < count($record); $i++) {
            $data[$i]['PERM_ID'] = $record[$i]->PERM_ID;
        }
        //   print_r($data);
        return $data;
    }

    function getAllPermissions() {
        $this->load->model('mdl_rolepermission');
        $query = $this->mdl_rolepermission->getPermissions();
        return $query;
    }

    public function get_data_from_post($pid) {
        $data = array();
        $data['ROLE_ID'] = $this->input->post('roleid', TRUE);
        $data['PERM_ID'] = $pid;
        return $data;
    }

    public function get_data_from_db($id) {
        $record = $this->get_where($id)->result();
        // print_r($record);
        $data = array();
        for ($i = 0; $i < count($record); $i++) {
            $data[$i]['PERMISSION_ID'] = $record[$i]->PERM_ID;
            //  $data = $record[$i]['PERM_ID']->PERM_ID;
        }
        print_r($data);
        return $data;
    }

    function delete($id) {
        //  $value = "";
        //  $query = $this->get_where($id)->result();
        $this->_delete($id);
//        foreach ($query as $row) {
//            $value = $row->firstname . '  ' . $row->lastname;
//        }
//        $process = $data['msg'] = "$value, Deleted Sucessfully";
//
//        $this->log($process);
//      //  $this->get_rolepermission_list($data);
    }

    function cancel() {
        // redirect to record list page
        redirect('staff/index/', 'refresh');
    }

    function check_rolepermission($roleid) {
        $query = "select * from hy_role_perm_xref where role_id = '$roleid'";
        $result = $this->_custom_num_rows_query($query);
        if ($result > 0) {
            return true;
        }
    }

    function check_value($permid, $roleid) {
        $query = "select * from hy_role_perm_xref where role_id = $roleid and perm_id = $permid";
        $result = $this->_custom_num_rows_query($query);
        if ($result > 0) {
            return true;
        } else {
            return false;
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

    function get_role_where($id) {
        $this->load->model('mdl_rolepermission');
        $query = $this->mdl_rolepermission->get_role_where($id);
        return $query;
    }

    function get_all_assigned_perm_where($id) {
        $this->load->model('mdl_rolepermission');
        $query = $this->mdl_rolepermission->get_all_assigned_perm_where($id);
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