<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class User extends MX_Controller {

    function __construct() {
        parent::__construct();
        $this->load->library('form_validation');
    }

    function index() {
        $data['title'] = 'User Management - Hybrid Management System';
        $data['view_file'] = 'addUser';
        $this->loadView($data);
    }

    function loadView($data) {
        $data['module'] = 'user';
        $data['roles'] = $this->getAllRoles()->result();
        echo Modules::run('templ/templContent', $data);
    }

    public function submit() {
        $update_id = $this->input->post('userid', TRUE);
        $this->form_validation->set_rules('displayname', 'Display Name', 'trim|required');
        $this->form_validation->set_rules('firstname', 'Firstname', 'trim|required');
        $this->form_validation->set_rules('lastname', 'Lastname', 'trim|required');
       // $this->form_validation->set_rules('usertype', 'User Type', 'trim|required');
        $this->form_validation->set_rules('emailaddress', 'Email Address', 'trim|required');

        if ($this->form_validation->run() === FALSE) {
            $data['title'] = 'User Management - Hybrid Management System';
            $data['msg'] = '<div class="alert alert-warning"><i class="fa fa-bolt"></i> Error! Fill the box(es).</div>';
            $data['view_file'] = 'addUser';
            $this->loadView($data);
        } else {
            $username = $this->get_username();
            if ($this->check_username($username) != true) {
                $data = $this->get_data_from_post($username);
                if ($update_id > 0) {
                    $this->_update($update_id, $data);

			// log audit trail
			$user = $_SESSION['username'];
			$this->fire_audit_log($user, '/'.$this->router->fetch_class().'/'.$this->router->fetch_method(), 'User: '.$username, 'UPDATE-USER');

                    $process = $data['msg'] = "<div class='alert alert-success'><i class='fa fa-check'></i> $username was updated successfully</div>";
                    //   $this->log($process);
                    $this->get_user_list($data);
                } else {
                    $this->_insert($data);

			// log audit trail
			$user = $_SESSION['username'];
			$this->fire_audit_log($user, '/'.$this->router->fetch_class().'/'.$this->router->fetch_method(), 'User: '.$username, 'ADD-USER');

                    $process = $data['msg'] = "<div class='alert alert-success'><i class='fa fa-check'></i> $username was saved successfully</div>";
                    $data['view_file'] = 'addUser';
                    //  $this->log($process);
                    $this->loadView($data);
                }
            } else {
                $data['title'] = 'User Management - Hybrid Management System';
                $data['msg'] = '<div class="alert alert-warning"><i class="fa fa-warning"></i> Username already exist.</div>';
                $data['view_file'] = 'addUser';
                $this->loadView($data);
            }
        }
    }

    public function userlist() {
        $this->get_user_list();
    }

    public function get_user_list($data) {
        $data['title'] = 'User Management - Hybrid Management System';
        $data['record'] = $this->raw_value($this->getAll()->result());

	// log audit trail
	$user = $_SESSION['username'];
	$this->fire_audit_log($user, '/'.$this->router->fetch_class().'/'.$this->router->fetch_method(), '-', 'VIEW');

        $data['view_file'] = 'userlist';
        $data['module'] = 'user';
        echo Modules::run('templ/templContent', $data);
    }

    public function raw_value($record) {
        $data1 = array();
        for ($i = 0; $i < count($record); $i++) {
            $data1[$i]['USERNAME'] = $record[$i]->USERNAME;
            $data1[$i]['DISPLAY_NAME'] = $record[$i]->DISPLAY_NAME;
            $data1[$i]['USERCODE'] = $record[$i]->USERCODE;
            $data1[$i]['USER_ID'] = $record[$i]->USER_ID;
            $data1[$i]['ROLE_NAME'] = $this->getRoleValue($record[$i]->ROLE_ID);
        }
        //  print_r($data1);
        return $data1;
    }

    public function set_up() {
        $update_id = $this->uri->segment(3);
        if (is_numeric($update_id)) {
            $data = $this->get_data_from_db($update_id);
            $data['roles'] = $this->getAllRoles()->result();
            $data['title'] = 'User Management - Hybrid Management System';
            $data['view_file'] = 'addUser';
            $data['module'] = 'user';
            echo Modules::run('templ/templContent', $data);
        } else {
            $data = $this->get_data_from_post();
        }
    }

    public function get_data_from_post($username) {
        $data = array();
        $data['USERNAME'] = $username;
        $data['FIRSTNAME'] = $this->input->post('firstname', TRUE);
        $data['LASTNAME'] = $this->input->post('lastname', TRUE);
        $data['DISPLAY_NAME'] = $this->input->post('displayname', TRUE);
        $data['EMAIL_ADDRESS'] = $this->input->post('emailaddress', TRUE);
       // $data['USER_TYPE'] = $this->input->post('usertype', TRUE);
        $data['CHANGE_PASSWORD'] = $this->get_usertype($this->input->post('usertype', TRUE));
        $data['COD_PASSWORD'] = $this->get_default($this->input->post('usertype', TRUE)); //$this->input->post('firstname', TRUE);
        $data['PASSWORDSALT'] = "1AAAA"; //$this->input->post('firstname', TRUE);
        $data['CREATED_BY'] = $_SESSION['username'];
        $data['ISLOCKED'] = 0;
        $type = $this->input->post('roleid', TRUE);
        $role = explode('|', $type);
        $data['ROLE_ID'] = $role[0];
        $data['USERCODE'] = $this->input->post('usercode', TRUE);
        //$data['CREATED_DATE'] = date('m/d/Y H:i:s A');
        return $data;
    }

    public function get_username() {
        $firstname = strtolower($this->input->post('firstname', TRUE));
        $lastname = strtolower($this->input->post('lastname', TRUE));
        return $firstname . "." . $lastname;
    }

    public function get_usertype($usertype) {
        if ($usertype == 0) {
            return 0;
        } else {
            return 1;
        }
    }

    public function get_default($usertype) {
        if ($usertype != 0) {
            return "E";
        } else {
            return "I";
        }
    }

    public function get_data_from_db($id) {
        $query = $this->get_where($id)->result();
        foreach ($query as $row) {
            $data['userid'] = $row->USER_ID;
            $data['displayname'] = $row->DISPLAY_NAME;
            $data['displaynames'] = $row->DISPLAY_NAME;
            $data['firstname'] = $row->FIRSTNAME;
            $data['lastname'] = $row->LASTNAME;
            $data['emailaddress'] = $row->EMAIL_ADDRESS;
            $data['usertype'] = $row->USER_TYPE;
            $data['roleid'] = $row->ROLE_ID;
            $data['usercode'] = $row->USERCODE;
        }
        return $data;
    }

    function delete($id) {
        $value = "";
        $username = "";
        $query = $this->get_where($id)->result();
        $this->_delete($id);
        foreach ($query as $row) {
            $value = $row->DISPLAY_NAME;
            $username = $row->USERNAME;
        }

	// log audit trail
	$user = $_SESSION['username'];
	$this->fire_audit_log($user, '/'.$this->router->fetch_class().'/'.$this->router->fetch_method(), 'User: '.$username, 'DELETE-USER');

        $process = $data['msg'] = "<div class='alert alert-success'><i class='fa fa-warning'></i> $value was deleted successfully</div>";
        //$this->log($process);
        $this->get_user_list($data);
    }

    function log($process) {
        $date = date('y-m-d h:i:s');
        $userid = $_SESSION['userid'];
        $process = "USER  :" . $process;
        $query = "INSERT INTO log VALUES ('', '$process','$date', $userid)";
        $this->_custom_query($query);
    }

    function check_username($name) {
        $query = "select * from hy_user_profile where username = '$name'";
        $result = $this->_custom_num_rows_query($query);
        if ($result > 0) {
            return true;
        }
    }

    function change_status($id) {
        $status = "";
        $value = "";
        $query = $this->get_where($id)->result();
        foreach ($query as $row) {
            $status = $row->status;
            $value = $row->username;
        }
        //echo $status;
        if ($status == '1') {
            $mysql_query = "update smsusers set status = '0' where smsuserid = $id";
            $result = $this->_custom_query($mysql_query);
        } else {
            $mysql_query = "update smsusers set status = '1' where smsuserid = $id";
            $result = $this->_custom_query($mysql_query);
        }
        if ($result == true) {
            $process = $data['msg'] = "<div class='alert alert-warning'><i class='fa fa-warning'></i> $value status was changed Successfully</div>";
            //$this->log($process);
            $this->get_user_list($data);
        }
    }

    function cancel() {
        // redirect to record list page
        redirect('staff/index/', 'refresh');
    }

    function getAll() {
        $this->load->model('mdl_user');
        $query = $this->mdl_user->getAll();
        return $query;
    }

    function getRoleValue($id) {
        $name = "";
        $this->load->model('mdl_user');
        $query = $this->mdl_user->getRoleValue($id);
        foreach ($query as $row) {
            $name = $row->ROLE_NAME;
        }
        return $name;
    }

    function getAllRoles() {
        $this->load->model('mdl_user');
        $query = $this->mdl_user->getUserRoles();
        return $query;
    }

    function get($order_by) {
        $this->load->model('mdl_user');
        $query = $this->mdl_user->get($order_by);
        return $query;
    }

    function get_with_limit($limit, $offset, $order_by) {
        $this->load->model('mdl_user');
        $query = $this->mdl_user->get_with_limit($limit, $offset, $order_by);
        return $query;
    }

    function get_where($id) {
        $this->load->model('mdl_user');
        $query = $this->mdl_user->get_where($id);
        return $query;
    }

    function get_where_custom($col, $value) {
        $this->load->model('mdl_user');
        $query = $this->mdl_user->get_where_custom($col, $value);
        return $query;
    }

    function _insert($data) {
        $this->load->model('mdl_user');
        $this->mdl_user->_insert($data);
    }

    function _update($id, $data) {
        $this->load->model('mdl_user');
        $this->mdl_user->_update($id, $data);
    }

    function _delete($id) {
        $this->load->model('mdl_user');
        $this->mdl_user->_delete($id);
    }

    function count_where($column, $value) {
        $this->load->model('mdl_user');
        $count = $this->mdl_user->count_where($column, $value);
        return $count;
    }

    function get_max() {
        $this->load->model('mdl_user');
        $max_id = $this->mdl_user->get_max();
        return $max_id;
    }

    function _custom_num_rows_query($mysql_query) {
        $this->load->model('mdl_user');
        $query = $this->mdl_user->_custom_num_rows_query($mysql_query);
        return $query;
    }

    function _custom_query($mysql_query) {
        $this->load->model('mdl_user');
        $query = $this->mdl_user->_custom_query($mysql_query);
        return $query;
    }

	function fire_audit_log($user, $url, $auditdata, $action) {
		$sql = "insert into hy_audit_trail (AUDITID, SESSIONID, IPADDRESS, USERID, URLACCESSED, TIMEACCESSED, AUDITDATA, AUDITACTION)
		values(AUDIT_TRAIL_ID_SEQ.nextval, '".session_id()."', '".$_SERVER["REMOTE_ADDR"]."', '".$user."', '".$url."', sysdate, '".$auditdata."', '".$action."')";
		$this->_custom_query($sql);
	}

}

?>