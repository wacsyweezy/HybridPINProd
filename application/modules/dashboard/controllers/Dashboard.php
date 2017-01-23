<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Dashboard extends MX_Controller {

    function __construct() {
        parent::__construct();
        $this->load->library('form_validation');
        //$this->load->library('MY_Log');
        //$this->load->library('log');
    }

    function index() {
	$data['title'] = "Dashboard - Hybrid Management System";
        if(isset($_SESSION['usercode']) && strlen($_SESSION['usercode'])>4) {
		redirect('/dashboard/external', $data);
	}
	else {
		redirect('/dashboard/internal', $data);
	}
    }
    
    function external() {
        $data['title'] = 'Dashboard - Hybrid Management System';
		$data['orders_count'] = $this->_custom_num_rows_query("select * from hy_orders where PARTNER_CODE='".$_SESSION['usercode']."'");
		//$data['stock_chart'];
		$data['customer_name'] = $this->_custom_query("select PARTYNAME from hy_party where PARTYCODE='".$_SESSION['usercode']."'")->result();
		$data['stocks'] = $this->_custom_query("select * from hy_stock_master order by evc_units asc")->result();
		$data['getAccount'] = $this->_custom_query("select EVC_ACCT_CODE from hy_party where PARTYCODE='".$_SESSION['usercode']."'")->result();
		foreach($data['getAccount'] as $account) {
			$account = $account->EVC_ACCT_CODE;
		}
		$data['evcBalance'] = $this->getEVCAccount($account);
		$data['evcNumber'] = $account;
		$total_item = 1;
		$item_id = "";
		$counter = 1;
		foreach($data['stocks'] as $stock) {
			$stock_id = $stock->STOCK_ID;
			$item_id = "item".$total_item;
			$data[$item_id] = $this->_custom_query("select trunc(a.order_date) dates, sum(b.quantity) quantity, b.item_code item from hy_orders a, hy_order_details b
where to_char(a.order_date, 'YYYYMM') = '201610' and b.item_code = '$stock_id' 
and a.order_no = b.order_no 
group by b.item_code, trunc(a.order_date) order by trunc(a.order_date) asc")->result();	
			$total_item++;
			$counter++;
		}
		$data['total_items'] = $counter;
		foreach($data['customer_name'] as $name) {
			$data['customer_name'] = $name->PARTYNAME;
		}
		$sql = "
		select 
			a.order_no, 
			a.partner_code, 
			sum(c.EVC_UNITS*b.QUANTITY) as total, 
			a.order_date, 
			a.order_status 
		from
			hy_orders a, hy_order_details b, hy_stock_master c
		where 
			a.order_no = b.order_no and b.ITEM_CODE = c.STOCK_ID and partner_code = '".$_SESSION['usercode']."'
		group by 
			a.order_no, 
			a.partner_code, 
			a.order_date, 
			a.order_status 
		order by a.ORDER_DATE desc";
		$data['last_five_order'] = $this->_custom_query($sql)->result();

	// log audit trail
	$user = $_SESSION['username'];
	$this->fire_audit_log($user, '/'.$this->router->fetch_class().'/'.$this->router->fetch_method(), 'External-Dashboard', 'VIEW');

        $data['view_file'] = 'external';
        $this->loadView($data);
    }
    
    function internal() {
        $data['title'] = 'Dashboard - Hybrid Management System';
        $data['view_file'] = 'internal';
        $data['all_user'] = $this->_custom_num_rows_query("select * from hy_user_profile");
        $data['internal_user'] = $this->_custom_num_rows_query("select * from hy_user_profile where USERCODE is NULL or USERCODE = ''");
        $data['external_user'] = $this->_custom_num_rows_query("select * from hy_user_profile where length(USERCODE)>2");

	// log audit trail
	$user = $_SESSION['username'];
	$this->fire_audit_log($user, '/'.$this->router->fetch_class().'/'.$this->router->fetch_method(), 'Internal-Dashboard', 'VIEW');

        $this->loadView($data);
    }

    function profile() {
		if(isset($_POST['ppin']) && strlen($_POST['ppin'])>0) {
			$pin = $this->input->post('ppin', TRUE);
			$code = $_SESSION['usercode'];
			$user = $_SESSION['username'];
			$query = "UPDATE HY_OLTP_PIN_CODES SET PIN_CODE = '$pin', MODIFIED_BY = '$user', LAST_MODIFIED_DATE = sysdate WHERE 
					DEALERCODE = '$code'";
					
			$this->_custom_query($query);
			
			
			// log audit trail
			$this->fire_audit_log($user, '/'.$this->router->fetch_class().'/'.$this->router->fetch_method(), 'Vending PIN - '.$pin, 'PIN-UPDATE');
			
			$data['msg'] = '<div class="alert alert-success"><i class="fa fa-check-square-o"></i> PIN was updated successfully</div>';			
		}
	$partner = $_SESSION['usercode'];

	$data['partner_detail'] = $this->partnerDetail($partner)->result();

	$data['getAccount'] = $this->_custom_query("select EVC_ACCT_CODE from hy_party where PARTYCODE='".$_SESSION['usercode']."'")->result();
	foreach($data['getAccount'] as $account) {
		$account = $account->EVC_ACCT_CODE;
	}
	$data['evcBalance'] = $this->getEVCAccount($account);

        $data['title'] = 'Dashboard - Hybrid Management System';
        $data['view_file'] = 'profile';

        $data['info'] = $this->_custom_query("select a.created_date, a.display_name, a.email_address, a.username, a.usercode, b.pin_code, b.msisdn from hy_user_profile a, HY_OLTP_PIN_CODES b
where a.username = '".$_SESSION['username']."' and A.USERCODE = B.DEALERCODE")->result();

		// log audit trail
		$user = $_SESSION['username'];
		$this->fire_audit_log($user, '/'.$this->router->fetch_class().'/'.$this->router->fetch_method(), 'Profile', 'VIEW');

        $this->loadView($data);
    }

	function getEVCAccount($account) {
		$url = "http://10.158.6.80:8186/HybridLite/QueryDealerBalance?USERNAME=hpin&PASSWORD=Emts1234&USER_TYPE=0&USER_ACCOUNT=$account";
		$open=curl_init($url);
		curl_setopt($open, CURLOPT_URL, $url);
		curl_setopt($open, CURLOPT_RETURNTRANSFER, 1);
		$data=curl_exec($open);
		$split = explode('|', $data);
		$balance = $split[2];
		return '&#8358;'.number_format($balance/100);
	}

    function loadView($data) {
        $data['module'] = 'dashboard';
        echo Modules::run('templ/templContent', $data);
    }

    function cancel() {
        // redirect to record list page
        redirect('staff/index/', 'refresh');
    }

	function partnerDetail($partner) {
		$this->load->model('mdl_dashboard');
		$sql = "select PARTYCODE, EVC_ACCT_CODE, PARTYNAME, AUTHORIZED_BY from hy_party where partycode = '$partner'";
		$query = $this->_custom_query($sql);
       	 	return $query;
	}


    function get_where($id) {
        $this->load->model('mdl_dashboard');
        $query = $this->mdl_dashboard->get_where($id);
        return $query;
    }

    function get_where_custom($col, $value) {
        $this->load->model('mdl_dashboard');
        $query = $this->mdl_dashboard->get_where_custom($col, $value);
        return $query;
    }

    function count_where($column, $value) {
        $this->load->model('mdl_dashboard');
        $count = $this->mdl_dashboard->count_where($column, $value);
        return $count;
    }

    function get_max() {
        $this->load->model('mdl_dashboard');
        $max_id = $this->mdl_dashboard->get_max();
        return $max_id;
    }

    function _custom_num_rows_query($mysql_query) {
        $this->load->model('mdl_dashboard');
        $query = $this->mdl_dashboard->_custom_num_rows_query($mysql_query);
        return $query;
    }

    function _custom_query($mysql_query) {
        $this->load->model('mdl_dashboard');
        $query = $this->mdl_dashboard->_custom_query($mysql_query);
        return $query;
    }

	function fire_audit_log($user, $url, $auditdata, $action) {
		$sql = "insert into hy_audit_trail (AUDITID, SESSIONID, IPADDRESS, USERID, URLACCESSED, TIMEACCESSED, AUDITDATA, AUDITACTION)
		values(AUDIT_TRAIL_ID_SEQ.nextval, '".session_id()."', '".$_SERVER["REMOTE_ADDR"]."', '".$user."', '".$url."', sysdate, '".$auditdata."', '".$action."')";
		$this->_custom_query($sql);
    	}

}

?>