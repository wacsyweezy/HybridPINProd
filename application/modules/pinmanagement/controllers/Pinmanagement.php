<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Pinmanagement extends MX_Controller {
    function __construct() {
        parent::__construct();
        $this->load->library('form_validation');
		parse_str($_SERVER['QUERY_STRING'], $_GET);
        //$this->load->library('MY_Log');
        //$this->load->library('log');
    }

    function index() {
        $data['title'] = 'H-PIN Management - Hybrid Management System';
        $data['view_file'] = 'index';
        $this->loadView($data);
    }

    function loadView($data) {
        $data['module'] = 'pinmanagement';
        echo Modules::run('templ/templContent', $data);
    }

	function makeRequest() {
		$data['title'] = 'H-PIN Management - Hybrid Management System';
		$data['tasks'] = $this->getTask()->result();
		$data['view_file'] = 'request';
		$this->loadView($data);
	}
	
	function conversionRate() {
		$data['title'] = 'H-PIN Management - Hybrid Management System';
		$data['stocks'] = $this->getStock()->result();

		// log audit trail
		$user = $_SESSION['username'];
		$this->fire_audit_log($user, '/'.$this->router->fetch_class().'/'.$this->router->fetch_method(), '-', 'VIEW');

		$data['view_file'] = 'rate';
		$this->loadView($data);
	}

	function getTask() {
		$this->load->model('mdl_pinmanagement');
		$sql = "select * from hy_task_types where task_desc not in 'Generation'";
		$query = $this->_custom_query($sql);
		return $query;
	}
	
	public function addStock() {
		$this->form_validation->set_rules('stock', 'stock', 'trim|required');
		$this->form_validation->set_rules('description', 'description', 'trim');
		$this->form_validation->set_rules('rate', 'rate', 'trim|required');
		$this->form_validation->set_rules('fvalue', 'fvalue', 'trim|required');
		if($this->form_validation->run() === FALSE) {
            $data['title'] = 'H-PIN Management - Hybrid Management System';
            $data['msg'] = '<div class="alert alert-warning"><i class="fa fa-bolt"></i> Error please fill in all the fields.</div>';
            $data['view_file'] = 'rate';
			$data['stocks'] = $this->getStock()->result();
            $this->loadView($data);
        } 
		else {
			$partnercode = $this->input->post('partnercode', TRUE);
			$itemtype = $this->input->post('itemtype', TRUE);	
			$data['msg'] = $this->addStockItem()->result();
			foreach($data['msg'] as $msg) {
				$data['msg'] = $msg->RESULT;
			}

			// log audit trail
			$user = $_SESSION['username'];
			$this->fire_audit_log($user, '/'.$this->router->fetch_class().'/'.$this->router->fetch_method(), '-', 'VIEW');

			$data['stocks'] = $this->getStock()->result();		
			$data['title'] = 'H-PIN Management - Hybrid Management System';
			$data['view_file'] = 'rate';
			$this->loadView($data);
		}
	}
	
	public function removeStock() {	
		$data['msg'] = $this->removeStockItem()->result();
		foreach($data['msg'] as $msg) {
			$data['msg'] = $msg->RESULT;
		}
		$data['stocks'] = $this->getStock()->result();		
		$data['title'] = 'H-PIN Management - Hybrid Management System';
		$data['view_file'] = 'rate';
		$this->loadView($data);
	}
	
	# GET ALL STOCK ITEMS
	function getStock() {
		$this->load->model('mdl_pinmanagement');
		$sql = "select * from hy_stock_master order by facevalue asc";
		$query = $this->_custom_query($sql);
        return $query;
	}
	
	function addStockItem() {
		$this->load->model('mdl_pinmanagement');
		$stock = $this->input->post('stock', TRUE);
		$description = $this->input->post('description', TRUE);		
		$rate = $this->input->post('rate', TRUE);
		$fvalue = $this->input->post('fvalue', TRUE);		
		$sql = "select PKG_HYBRID_PIN.FN_ADD_STOCK(
							'".$stock."', 
							'".$description."', 
							$rate, 
							$fvalue,
							'".$_SESSION['username']."') as result from dual";
		$query = $this->_custom_query($sql);

		// log audit trail
		$user = $_SESSION['username'];
		$this->fire_audit_log($user, '/'.$this->router->fetch_class().'/'.$this->router->fetch_method(), 'Stock: '.$stock.' - EVC: '.$rate.' - Face: '.$fvalue, 'ADD-STOCK');

        return $query;
	}
	
	function removeStockItem() {
		$this->load->model('mdl_pinmanagement');
		$stock = $_GET['stock'];
		$stock = base64_decode($stock);		
		$sql = "select PKG_HYBRID_PIN.FN_DELETE_STOCK(
							'".$stock."', 
							'".$_SESSION['username']."') as result from dual";
		$query = $this->_custom_query($sql);

		// log audit trail
		$user = $_SESSION['username'];
		$this->fire_audit_log($user, '/'.$this->router->fetch_class().'/'.$this->router->fetch_method(), 'Stock: '.$stock, 'DELETE-STOCK');

        return $query;
	}

    function _custom_num_rows_query($mysql_query) {
        $this->load->model('mdl_pinmanagement');
        $query = $this->mdl_pinmanagement->_custom_num_rows_query($mysql_query);
        return $query;
    }

    function _custom_query($mysql_query) {
        $this->load->model('mdl_pinmanagement');
        $query = $this->mdl_pinmanagement->_custom_query($mysql_query);
        return $query;
    }

	function fire_audit_log($user, $url, $auditdata, $action) {
		$sql = "insert into hy_audit_trail (AUDITID, SESSIONID, IPADDRESS, USERID, URLACCESSED, TIMEACCESSED, AUDITDATA, AUDITACTION)
		values(AUDIT_TRAIL_ID_SEQ.nextval, '".session_id()."', '".$_SERVER["REMOTE_ADDR"]."', '".$user."', '".$url."', sysdate, '".$auditdata."', '".$action."')";
		$this->_custom_query($sql);
	}

}

?>