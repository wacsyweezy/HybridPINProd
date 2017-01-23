<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Channelpartners extends MX_Controller {

    function __construct() {
        parent::__construct();
        $this->load->library('form_validation');
        //$this->load->library('MY_Log');
        //$this->load->library('log');
    }
	# Add New Channel Partner Page
    function index() {
        $data['title'] = 'Channel Partners - Hybrid Management System';
	$data['dealers'] = $this->getDealers()->result();
				
	// log audit trail
	$user = $_SESSION['username'];
	$this->fire_audit_log($user, '/'.$this->router->fetch_class().'/'.$this->router->fetch_method(), '-', 'VIEW');

        $data['view_file'] = 'index';
        $this->loadView($data);
    }
	# List Channel Partners
    function partners() {
		if(isset($_GET['authorize'])) {
			$partner = base64_decode($_GET['authorize']);
			$sql="update hy_party set AUTHORIZED_BY='".$_SESSION['username']."', AUTHORIZED_DATE=sysdate where partycode='$partner'";
			$query = $this->_custom_query($sql);

			// log audit trail
			$user = $_SESSION['username'];
			$this->fire_audit_log($user, '/'.$this->router->fetch_class().'/'.$this->router->fetch_method(), 'DEALER - '.$partner, 'AUTHORIZE');

			$data['msg'] = '<div class="alert alert-success"><i class="fa fa-check-square-o"></i> Partner ('.$partner.') was authorized successfully</div>';
		}
		if(isset($_GET['reject'])) {
			$partner = base64_decode($_GET['reject']);
			$sql="delete from hy_party where partycode='$partner'";
			$query = $this->_custom_query($sql);

			// log audit trail
			$user = $_SESSION['username'];
			$this->fire_audit_log($user, '/'.$this->router->fetch_class().'/'.$this->router->fetch_method(), 'DEALER - '.$partner, 'REJECT');

			$data['msg'] = '<div class="alert alert-success"><i class="fa fa-check-square-o"></i> Partner ('.$partner.') was removed successfully</div>';
		}
        $data['title'] = 'All Channel Partners - Hybrid Management System';
        $data['view_file'] = 'partnersList';
	$data['partner_list'] = $this->getPartner()->result();

	// log audit trail
	$user = $_SESSION['username'];
	$this->fire_audit_log($user, '/'.$this->router->fetch_class().'/'.$this->router->fetch_method(), '-', 'VIEW');

        $this->loadView($data);
    }
	
    function loadView($data) {
        $data['module'] = 'channelpartners';
        echo Modules::run('templ/templContent', $data);
    }

	function getPartner() {
        $this->load->model('mdl_channelpartners');
        $query = $this->mdl_channelpartners->getPartner();
        return $query;
    }

	# Manage Partners PIN
    function pinsystem() {
	if(isset($_GET['pin'])) {
		$pin = base64_decode($_GET['pin']);
		$query = "delete from hy_oltp_pin_codes where msisdn = '".$pin."'";
		$this->_custom_query($query);

		// log audit trail
		$user = $_SESSION['username'];
		$this->fire_audit_log($user, '/'.$this->router->fetch_class().'/'.$this->router->fetch_method(), 'MSISDN PIN - '.$pin, 'DELETE');

		$data['msg'] = '<div class="alert alert-warning"><i class="fa fa-bolt"></i> PIN was removed successfully.</div>';

        	$data['title'] = 'Channel Partners - Hybrid Management System';
		$data['pins'] = $this->getPIN()->result();
        	$data['view_file'] = 'pinsystem';
        	$this->loadView($data);

	}
	else {

	if(isset($_POST['ppin']) && isset($_POST['epurse'])) {
		$this->form_validation->set_rules('ppin', 'ppin', 'trim|required');
		$this->form_validation->set_rules('epurse', 'epurse', 'trim|required');
		if ($this->form_validation->run() === FALSE) {
			$data['pins'] = $this->getPIN()->result();
            		$data['title'] = 'Channel Partners - Hybrid Management System';
            		$data['msg'] = '<div class="alert alert-warning"><i class="fa fa-bolt"></i> Error please fill in all the fields.</div>';
            		$data['view_file'] = 'pinsystem';
            		$this->loadView($data);
        	}
		else {
			$epurse = $this->input->post('epurse', TRUE);
			$pin = $this->input->post('ppin', TRUE);
			$code = $this->input->post('pcode', TRUE);
			if ($this->check_onlinecode($code) != true && $this->check_epurse($epurse)!= true) {
				$query = "INSERT INTO HY_OLTP_PIN_CODES
				(DEALERCODE,PIN_CODE, CREATED_BY, CREATED_DATE, MODIFIED_BY, LAST_MODIFIED_DATE, MSISDN, STATUS) 
				VALUES('".$code."','".$pin."','".$_SESSION['username']."', sysdate, '', '', '".$epurse."', 0)";
				
				$this->_custom_query($query);

				// log audit trail
				$user = $_SESSION['username'];
				$this->fire_audit_log($user, '/'.$this->router->fetch_class().'/'.$this->router->fetch_method(), 'MSISDN PIN - '.$pin, 'SETUP');

				$data['pins'] = $this->getPIN()->result();

				$data['title'] = 'Channel Partners - Hybrid Management System';
				$data['msg'] = '<div class="alert alert-success"><i class="fa fa-check-square-o"></i> Partner PIN registration was successfully</div>';
				$data['view_file'] = 'pinsystem';
				$this->loadView($data);

			}
			else {
				$epurse = $this->input->post('epurse', TRUE);
				$code = $this->input->post('pcode', TRUE);

				$data['title'] = 'Channel Partners - Hybrid Management System';
				if($this->check_onlinecode($code) == true) {
					$data['msg'] = '<div class="alert alert-warning"><i class="fa fa-bolt"></i> Partner code ('.$code.') already exist!</div>';
				}
				if($this->check_epurse($epurse) == true) {
					$data['msg'] = '<div class="alert alert-warning"><i class="fa fa-bolt"></i> e-Purse Number '.$epurse.' already exist!</div>';
				}

				$data['pins'] = $this->getPIN()->result();

				$data['view_file'] = 'pinsystem';
				$this->loadView($data);
			}
		}
	}
	else {

        	$data['title'] = 'Channel Partners - Hybrid Management System';
		$data['pins'] = $this->getPIN()->result();

		// log audit trail
		$user = $_SESSION['username'];
		$this->fire_audit_log($user, '/'.$this->router->fetch_class().'/'.$this->router->fetch_method(), '-', 'VIEW');

        	$data['view_file'] = 'pinsystem';
        	$this->loadView($data);
	}
	}
    }

	# GET ALL DEALER CODE
	public function getDealers() {
        	$sql = "select usercode, display_name from hy_user_profile where length(usercode)>3";
		$query = $this->_custom_query($sql);
        	return $query;
	}

	# GET ALL DEALER PIN
	public function getPIN() {
        	$sql = "select * from HY_OLTP_PIN_CODES";
		$query = $this->_custom_query($sql);
        	return $query;
	}
	
	function getEVCAccount($partner) {
		$url = "http://10.158.6.80:8186/HybridLite/QueryDealerBalance?USERNAME=hpin&PASSWORD=Emts1234&USER_TYPE=0&USER_ACCOUNT=$partner";
		$open=curl_init($url);
		curl_setopt($open, CURLOPT_URL, $url);
		curl_setopt($open, CURLOPT_RETURNTRANSFER, 1);
		$data=curl_exec($open);
		$split = explode('|', $data);
		$balance = $split[2];
		return '&#8358;'.number_format($balance/100);
	}
	
	function transactions() {
		if(isset($_GET['party'])) {
			$partner = base64_decode($_GET['party']);
			$evc_account = base64_decode($_GET['account']);
			$data['evcaccount'] = $this->getEVCAccount($evc_account);
			$data['partner_detail'] = $this->partnerDetail($partner)->result();
			$data['transaction_list'] = $this->getTransactions($partner)->result();

			// log audit trail
			$user = $_SESSION['username'];
			$this->fire_audit_log($user, '/'.$this->router->fetch_class().'/'.$this->router->fetch_method(), 'DEALER - '.$partner, 'VIEW-DETAILS');

			$data['title'] = 'Channel Partner ('.$partner.') Transactions - Hybrid Management System';
			$data['view_file'] = 'partnerTransaction';
			$this->loadView($data);
		}
		else {
			
		}
	}
	function getTransactions($partner) {
		$this->load->model('mdl_channelpartners');
		$sql = "select 
				o.partner_code,
				o.order_no, 
				to_char(o.order_date,'YYYY-MM-DD HH24:MI:SS') order_date,
				o.order_status,
				o.approved_by,
				o.approval_date,
				o.date_cancelled,
				o.cancelled_by
				from hy_orders o where o.partner_code = '$partner'";
		$query = $this->_custom_query($sql);
        return $query;
	}
	function partnerDetail($partner) {
		$this->load->model('mdl_channelpartners');
		$sql = "select PARTYCODE, EVC_ACCT_CODE, PARTYNAME, AUTHORIZED_BY from hy_party where partycode = '$partner'";
		$query = $this->_custom_query($sql);
        return $query;
	}
	public function addpartner() {
		$this->form_validation->set_rules('partytype', 'partytype', 'trim|required');
		$this->form_validation->set_rules('partnercode', 'partnercode', 'trim|required');
		$this->form_validation->set_rules('partnername', 'partnername', 'trim|required');
		$this->form_validation->set_rules('evcaccount', 'evcaccount', 'trim|required');
		if ($this->form_validation->run() === FALSE) {
            		$data['title'] = 'Channel Partners - Hybrid Management System';
            		$data['msg'] = '<div class="alert alert-warning"><i class="fa fa-bolt"></i> Error please fill in all the fields.</div>';
            		$data['view_file'] = 'index';
            		$this->loadView($data);
        	} 
		else {
			$partnercode = $this->input->post('partnercode', TRUE);
			$evccode = $this->input->post('evcaccount', TRUE);
			if ($this->check_partnercode($partnercode) != true && $this->check_evcaccount($evccode)!= true) {
				$datas = $this->get_data_from_post();
				
				$query = "INSERT INTO HY_PARTY
				(AUTHORIZED_BY, AUTHORIZED_DATE, CREATED_BY, CREATED_DATE, EVC_ACCT_CODE, PARTYCODE, PARTYNAME, PARTYTYPEID) 
				VALUES('','','".$_SESSION['username']."', sysdate, '".$datas['EVC_CODE']."', '".$datas['PARTNER_CODE']."', '".$datas['PARTNER_NAME']."', ".$datas['PARTY_TYPE'].")";
				
				$this->_custom_query($query);

				// log audit trail
				$user = $_SESSION['username'];
				$this->fire_audit_log($user, '/'.$this->router->fetch_class().'/'.$this->router->fetch_method(), $datas['PARTNER_CODE']." - ".$datas['PARTNER_NAME'], 'REGISTER');
				
				$data['title'] = 'Channel Partners - Hybrid Management System';
				$data['msg'] = '<div class="alert alert-success"><i class="fa fa-check-square-o"></i> Partner '.$datas['PARTNER_NAME'].'('.$datas['PARTNER_CODE'] .') registration was submitted successfully</div>';
				$data['view_file'] = 'index';
				$this->loadView($data);
			}
			else {
				$datas = $this->get_data_from_post();
				$data['title'] = 'Channel Partners - Hybrid Management System';
				if($this->check_partnercode($partnercode) == true) {
					$data['msg'] = '<div class="alert alert-warning"><i class="fa fa-bolt"></i> Partner '.$datas['PARTNER_NAME'].'('.$datas['PARTNER_CODE'].') already exist!</div>';
				}
				if($this->check_evcaccount($evccode) == true) {
					$data['msg'] = '<div class="alert alert-warning"><i class="fa fa-bolt"></i> EVC Account '.$datas['EVC_CODE'].' already exist!</div>';
				}
				$data['view_file'] = 'index';
				$this->loadView($data);
			}
		}	
	}
	
	function check_partnercode($code) {
        $query = "SELECT * FROM HY_PARTY WHERE PARTYCODE = '$code'";
        $result = $this->_custom_num_rows_query($query);
        if ($result > 0) {
            return true;
        }
	else {
		return false;
	}
    }

	function check_onlinecode($code) {
        $query = "SELECT * FROM HY_OLTP_PIN_CODES WHERE DEALERCODE = '$code'";
        $result = $this->_custom_num_rows_query($query);
        if ($result > 0) {
            return true;
        }
	else {
		return false;
	}
    }

	function check_epurse($code) {
        $query = "SELECT * FROM HY_OLTP_PIN_CODES WHERE MSISDN = '$code'";
        $result = $this->_custom_num_rows_query($query);
        if ($result > 0) {
            return true;
        }
	else {
		return false;
	}
    }
	
	function check_evcaccount($code) {
        $query = "SELECT * FROM HY_PARTY WHERE EVC_ACCT_CODE = '$code'";
        $result = $this->_custom_num_rows_query($query);
        if ($result > 0) {
            return true;
        }
	else {
		return false;
	}
    }

    public function get_data_from_post() {
        $datas = array();
        $datas['PARTY_TYPE'] = $this->input->post('partytype', TRUE);
        $datas['PARTNER_CODE'] = $this->input->post('partnercode', TRUE);
        $datas['PARTNER_NAME'] = $this->input->post('partnername', TRUE);
		$datas['EVC_CODE'] = $this->input->post('evcaccount', TRUE);
        return $datas;
    }

    function log($process) {
        $date = date('y-m-d h:i:s');
        $userid = $_SESSION['userid'];
        $process = "RECIPIENT  : " . $process;
        $query = "INSERT INTO log VALUES ('', '$process','$date', $userid)";
        $this->_custom_query($query);
    }

    function _custom_num_rows_query($mysql_query) {
        $this->load->model('mdl_channelpartners');
        $query = $this->mdl_channelpartners->_custom_num_rows_query($mysql_query);
        return $query;
    }

    function _custom_query($mysql_query) {
        $this->load->model('mdl_channelpartners');
        $query = $this->mdl_channelpartners->_custom_query($mysql_query);
        return $query;
    }

	function fire_audit_log($user, $url, $auditdata, $action) {
		$sql = "insert into hy_audit_trail (AUDITID, SESSIONID, IPADDRESS, USERID, URLACCESSED, TIMEACCESSED, AUDITDATA, AUDITACTION)
		values(AUDIT_TRAIL_ID_SEQ.nextval, '".session_id()."', '".$_SERVER["REMOTE_ADDR"]."', '".$user."', '".$url."', sysdate, '".$auditdata."', '".$action."')";
		$this->_custom_query($sql);
    	}

}

?>