<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Permission extends MX_Controller {

    function __construct() {
        parent::__construct();
        $this->load->library('form_validation');
    }

    function index() {
        $data['title'] = 'Access Management - Hybrid Management System';
        $data['view_file'] = 'addPermission';
        $this->loadView($data);
    }

    function loadView($data) {
        $data['module'] = 'permission';
        $data['mainmenurec'] = $this->getAllMainMenu()->result();
        // print_r($this->getAllMainMenu()->result());
        echo Modules::run('templ/templContent', $data);
    }

    public function submit() {
        $update_id = $this->input->post('permissionid', TRUE);

        $this->form_validation->set_rules('displayname', 'Display Name', 'trim|required');
        $this->form_validation->set_rules('actionname', 'Action Name', 'trim|required');
        $this->form_validation->set_rules('mainmenuid', 'Main Menu', 'trim|required');
        $this->form_validation->set_rules('controllername', 'Controller Name', 'trim|required');

        if ($this->form_validation->run() === FALSE) {

            $data['title'] = 'Access Management - Hybrid Management System';
            $data['msg'] = '<div class="alert alert-warning"><i class="fa fa-bolt"></i> Error! Empty Box(es).</div>';
            $data['view_file'] = 'addPermission';
            $this->loadView($data);
        } else {
            //echo 'yess';
            $displayname = $this->input->post('displayname', TRUE);
          //  $actionname = $this->input->post('actionname', TRUE);
            if ($this->check_permission($displayname) != true) {
                $name = "";
                $data = $this->get_data_from_post();
                //    $name= $data['firstname'].'  '. $data['lastname'];
                if ($update_id > 0) {
					$data['title'] = 'Access Management - Hybrid Management System';
                    $this->_update($update_id, $data);
                    $process = $data['msg'] = "<div class='alert alert-success'><i class='fa fa-check'></i> Access was updated successfully</div>";
                    //   $this->log($process);
                    $this->get_permission_list($data);
                } else {
                    $this->_insert($data);
					$data['title'] = 'Access Management - Hybrid Management System';
                    $process = $data['msg'] = "<div class='alert alert-success'><i class='fa fa-check'></i> Access was saved successfully</div>";
                    // $this->log($process);
                    $data['view_file'] = 'addPermission';
                    $this->loadView($data);
                }
            } else {
                $data['title'] = 'Access Management - Hybrid Management System';
                $data['msg'] = "<div class='alert alert-warning'><i class='fa fa-warning'></i> Display name or already exist.</div>";
                $data['view_file'] = 'addPermission';
                $this->loadView($data);
            }
        }
    }

    public function permissionlist() {
        $this->get_permission_list();
    }

    public function get_permission_list($data) {
        $data['title'] = 'Access Management - Hybrid Management System';
        $data['record'] = $this->raw_value($this->getAll()->result());

	// log audit trail
	$user = $_SESSION['username'];
	$this->fire_audit_log($user, '/'.$this->router->fetch_class().'/'.$this->router->fetch_method(), '-', 'VIEW');

        //  print_r($data);
        $data['view_file'] = 'listPermission';
        $data['module'] = 'permission';
        echo Modules::run('templ/templContent', $data);
        // $this->loadView($data);
    }

    public function raw_value($record) {
        $data1 = array();
        for ($i = 0; $i < count($record); $i++) {
            $data1[$i]['PERMISSION_ID'] = $record[$i]->PERMISSION_ID;
            $data1[$i]['DISPLAY_NAME'] = $record[$i]->DISPLAY_NAME;
            $data1[$i]['CONTROLLER_NAME'] = $record[$i]->CONTROLLER_NAME;
            $data1[$i]['ACTION_NAME'] = $record[$i]->ACTION_NAME;
            $data1[$i]['DESCRIPTION'] = $record[$i]->DESCRIPTION;
            $data1[$i]['MAIN_MENU_ID'] = $record[$i]->MAIN_MENU_ID;
            $data1[$i]['MAIN1_MENU_ID'] = $this->getMainMenuVal($record[$i]->MAIN_MENU_ID);
        }
        //  print_r($data1);
        return $data1;
    }

    public function set_up() {
            $update_id = $this->uri->segment(3);
        if (is_numeric($update_id)) {
            $data = $this->get_data_from_db($update_id);
            $data['mainmenurec'] = $this->getAllMainMenu()->result();
            $data['title'] = 'Access Management - Hybrid Management System';
            $data['view_file'] = 'addPermission';
            $data['module'] = 'permission';
            echo Modules::run('templ/templContent', $data);
        } else {
            $data = $this->get_data_from_post();
        }
    }

    public function get_data_from_post() {
        $data = array();
        $data['MAIN_MENU_ID'] = $this->input->post('mainmenuid', TRUE);
        $data['DISPLAY_NAME'] = $this->input->post('displayname', TRUE);
        $data['ACTION_NAME'] = $this->input->post('actionname', TRUE);
        $data['CONTROLLER_NAME'] = $this->input->post('controllername', TRUE);
        $data['DESCRIPTION'] = $this->input->post('description', TRUE);
        //DESCRIPTION
        // $data['createdby'] = $_SESSION['userid'];
        // $data['datecreated'] = date('y-m-d h:i:s');

        return $data;
    }

    public function get_data_from_db($id) {
        $query = $this->get_where($id)->result();
        foreach ($query as $row) {
            $data['permissionid'] = $row->PERMISSION_ID;
            $data['displayname'] = $row->DISPLAY_NAME;
            $data['actionname'] = $row->ACTION_NAME;
            $data['controllername'] = $row->CONTROLLER_NAME;
            $data['description'] = $row->DESCRIPTION;
            $data['mainmenuid'] = $row->MAIN_MENU_ID;
        }
        return $data;
    }

    function delete($id) {
        $value = "";
        $query = $this->get_where($id)->result();
        $this->_delete($id);
        foreach ($query as $row) {
            $value = $row->DISPLAY_NAME;
        }
        $process = $data['msg'] = "<div class='alert alert-success'><i class='fa fa-check'></i> $value was deleted Sucessfully</div>";

        //$this->log($process);
        $this->get_permission_list($data);
    }

    function cancel() {
        // redirect to record list page
        redirect('staff/index/', 'refresh');
    }

    function check_permission($displayname ) {
        $query = "select * from hy_permissions where display_name = '$displayname'";
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
        $this->load->model('mdl_permission');
        $query = $this->mdl_permission->getAll();
        //print_r($query->result());

        return $query;
    }

    function getAllMainMenu() {
        $this->load->model('mdl_permission');
        $query = $this->mdl_permission->getMainMenu();
        return $query;
    }

    function getMainMenuVal($id) {
        $menu = "";
        $this->load->model('mdl_permission');
        $query = $this->mdl_permission->getMainMenuValue($id);
        foreach ($query as $row) {
            $menu = $row->MAIN_MENU_NAME;
        }
        return $menu;
    }

    function get($order_by) {
        $this->load->model('mdl_permission');
        $query = $this->mdl_permission->get($order_by);
        return $query;
    }

    function get_with_limit($limit, $offset, $order_by) {
        $this->load->model('mdl_permission');
        $query = $this->mdl_permission->get_with_limit($limit, $offset, $order_by);
        return $query;
    }

    function get_where($id) {
        $this->load->model('mdl_permission');
        $query = $this->mdl_permission->get_where($id);
        return $query;
    }

    function get_where_custom($col, $value) {
        $this->load->model('mdl_permission');
        $query = $this->mdl_permission->get_where_custom($col, $value);
        return $query;
    }

    function _insert($data) {
        $this->load->model('mdl_permission');
        $this->mdl_permission->_insert($data);
    }

    function _update($id, $data) {
        $this->load->model('mdl_permission');
        $this->mdl_permission->_update($id, $data);
    }

    function _delete($id) {
        $this->load->model('mdl_permission');
        $this->mdl_permission->_delete($id);
    }

    function count_where($column, $value) {
        $this->load->model('mdl_permission');
        $count = $this->mdl_permission->count_where($column, $value);
        return $count;
    }

    function get_max() {
        $this->load->model('mdl_permission');
        $max_id = $this->mdl_permission->get_max();
        return $max_id;
    }

    function _custom_num_rows_query($mysql_query) {
        $this->load->model('mdl_permission');
        $query = $this->mdl_permission->_custom_num_rows_query($mysql_query);
        return $query;
    }

    function _custom_query($mysql_query) {
        $this->load->model('mdl_permission');
        $query = $this->mdl_permission->_custom_query($mysql_query);
        return $query;
    }

	function fire_audit_log($user, $url, $auditdata, $action) {
		$sql = "insert into hy_audit_trail (AUDITID, SESSIONID, IPADDRESS, USERID, URLACCESSED, TIMEACCESSED, AUDITDATA, AUDITACTION)
		values(AUDIT_TRAIL_ID_SEQ.nextval, '".session_id()."', '".$_SERVER["REMOTE_ADDR"]."', '".$user."', '".$url."', sysdate, '".$auditdata."', '".$action."')";
		$this->_custom_query($sql);
	}

}

?>