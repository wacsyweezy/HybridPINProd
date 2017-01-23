<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Role extends MX_Controller {

    function __construct() {
        parent::__construct();
        $this->load->library('form_validation');
        //$this->load->library('MY_Log');
        //$this->load->library('log');
    }

    function index() {
        $data['title'] = 'Role Management - Hybrid Management System';
        $data['view_file'] = 'addRole';
        $this->loadView($data);
    }

    function loadView($data) {
        $data['module'] = 'role';
        echo Modules::run('templ/templContent', $data);
    }

    
    public function submit() {
        $update_id = $this->input->post('roleid', TRUE);

        $this->form_validation->set_rules('rolename', 'Role Name', 'trim|required');
        $this->form_validation->set_rules('usertype', 'User Type', 'trim|required');
       // $this->form_validation->set_rules('lastname', 'Lastname', 'trim|required');
       // $this->form_validation->set_rules('phonenumber', 'Phone Number', 'trim|required');

        if ($this->form_validation->run() === FALSE) {
            $data['title'] = 'Role Management - Hybrid Management System';
            $data['msg'] = '<div class="alert alert-warning"><i class="fa fa-bolt"></i> Error, all fields must not be empty.</div>';
            $data['view_file'] = 'addRole';
            $this->loadView($data);
        } else {
            //echo 'yess';
            $name = $this->input->post('rolename', TRUE);
            if ($this->check_role($rolename) != true) {
                $data = $this->get_data_from_post();
                if ($update_id > 0) {
                    $this->_update($update_id, $data);


			// log audit trail
			$user = $_SESSION['username'];
			$this->fire_audit_log($user, '/'.$this->router->fetch_class().'/'.$this->router->fetch_method(), 'Role: '.$name, 'UPDATE-ROLE');

                    $process = $data['msg'] = "<div class='alert alert-success'><i class='fa fa-check'></i> $name was updated successfully";
                   // $this->log($process);
                    $this->get_role_list($data);
                } else {
                    $this->_insert($data);

			// log audit trail
			$user = $_SESSION['username'];
			$this->fire_audit_log($user, '/'.$this->router->fetch_class().'/'.$this->router->fetch_method(), 'Role: '.$name, 'ADD-ROLE');

                    $process = $data['msg'] = "<div class='alert alert-success'><i class='fa fa-check'></i> $name role was saved successfully</div>";
                   // $this->log($process);
                    $data['view_file'] = 'addRole';
                    $this->loadView($data);
                }
            } else {
                $data['title'] = 'Role Management - Hybrid Management System';
                $data['msg'] = "<div class='alert alert-warning'><i class='fa fa-warning'></i> Role already exist.</div>";
                $data['view_file'] = 'addRole';
                $this->loadView($data);
            }
        }
    }

    public function rolelist() {
        $this->get_role_list();
    }

    public function get_role_list($data) {
        $data['title'] = 'Role Management - Hybrid Management System';
        $data['record'] = $this->getAll()->result();

	// log audit trail
	$user = $_SESSION['username'];
	$this->fire_audit_log($user, '/'.$this->router->fetch_class().'/'.$this->router->fetch_method(), '-', 'VIEW');

        $data['view_file'] = 'listRole';
        $data['module'] = 'role';
        echo Modules::run('templ/templContent', $data);
        // $this->loadView($data);
    }

    public function set_up() {
        //  $data['base_url'] = 'staff/create';
       
        $update_id = $this->uri->segment(3);
        if (is_numeric($update_id)) {
            $data = $this->get_data_from_db($update_id);
            $data['title'] = 'Role Management - Hybrid Management System';
            $data['view_file'] = 'addRole';
            $data['module'] = 'role';
            echo Modules::run('templ/templContent', $data);
        } else {
            $data = $this->get_data_from_post();
            // for return access Level records 
            // $data['accessLevelRecords'] = $this->getAccessLevel()->result();
        }
    }

    public function get_data_from_post() {
        $data = array();

        $data['ROLE_NAME'] = $this->input->post('rolename', TRUE);
        $data['USERTYPE'] = $this->input->post('usertype', TRUE);
        $data['PSWD_LIFE_DAYS'] = 30;
	//$data['ROLE_ID'] = mt_rand();
       // $data['lastname'] = $this->input->post('lastname', TRUE);
        //$data['phonenumber'] = $this->input->post('phonenumber', TRUE);
        //$data['createdby'] = $_SESSION['userid'];
        //$data['datecreated'] = date('y-m-d h:i:s');

        return $data;
    }

    public function get_data_from_db($id) {
        $query = $this->get_where($id)->result();
        foreach ($query as $row) {
            $data['roleid'] = $row->ROLE_ID;
            $data['rolename'] = $row->ROLE_NAME;
            $data['usertype'] = $row->USERTYPE;
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
            $value = $row->ROLE_NAME;
        }

	// log audit trail
	$user = $_SESSION['username'];
	$this->fire_audit_log($user, '/'.$this->router->fetch_class().'/'.$this->router->fetch_method(), 'Role: '.$value, 'DELETE-ROLE');

        $process = $data['msg'] = "<div class='alert alert-warning'><i class='fa fa-warning'></i> $value deleted sucessfully</div>";

        //$this->log($process);
        $this->get_role_list($data);
    }

    function cancel() {
        // redirect to record list page
        redirect('staff/index/', 'refresh');
    }

    function check_role($var) {
        $query = "select * from hy_user_roles where role_name = '$var'";
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
        $this->load->model('mdl_role');
        $query = $this->mdl_role->getAll();
        return $query;
    }

    function get($order_by) {
        $this->load->model('mdl_role');
        $query = $this->mdl_role->get($order_by);
        return $query;
    }

    function get_with_limit($limit, $offset, $order_by) {
        $this->load->model('mdl_role');
        $query = $this->mdl_role->get_with_limit($limit, $offset, $order_by);
        return $query;
    }

    function get_where($id) {
        $this->load->model('mdl_role');
        $query = $this->mdl_role->get_where($id);
        return $query;
    }

    function get_where_custom($col, $value) {
        $this->load->model('mdl_role');
        $query = $this->mdl_role->get_where_custom($col, $value);
        return $query;
    }

    function _insert($data) {
        $this->load->model('mdl_role');
        $this->mdl_role->_insert($data);
    }

    function _update($id, $data) {
        $this->load->model('mdl_role');
        $this->mdl_role->_update($id, $data);
    }

    function _delete($id) {
        $this->load->model('mdl_role');
        $this->mdl_role->_delete($id);
    }

    function count_where($column, $value) {
        $this->load->model('mdl_role');
        $count = $this->mdl_role->count_where($column, $value);
        return $count;
    }

    function get_max() {
        $this->load->model('mdl_role');
        $max_id = $this->mdl_role->get_max();
        return $max_id;
    }

    function _custom_num_rows_query($mysql_query) {
        $this->load->model('mdl_role');
        $query = $this->mdl_role->_custom_num_rows_query($mysql_query);
        return $query;
    }

    function _custom_query($mysql_query) {
        $this->load->model('mdl_role');
        $query = $this->mdl_role->_custom_query($mysql_query);
        return $query;
    }

	function fire_audit_log($user, $url, $auditdata, $action) {
		$sql = "insert into hy_audit_trail (AUDITID, SESSIONID, IPADDRESS, USERID, URLACCESSED, TIMEACCESSED, AUDITDATA, AUDITACTION)
		values(AUDIT_TRAIL_ID_SEQ.nextval, '".session_id()."', '".$_SERVER["REMOTE_ADDR"]."', '".$user."', '".$url."', sysdate, '".$auditdata."', '".$action."')";
		$this->_custom_query($sql);
	}

}

?>