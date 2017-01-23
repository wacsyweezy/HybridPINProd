<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Order extends MX_Controller {

    function __construct() {
        parent::__construct();
        $this->load->library('form_validation');
		parse_str($_SERVER['QUERY_STRING'], $_GET);
        //$this->load->library('MY_Log');
        //$this->load->library('log');
    }

    function index() {
        $data['title'] = 'Order - Hybrid Management System';
        $data['view_file'] = 'placeOrder';

	// log audit trail
	$user = $_SESSION['username'];
	$this->fire_audit_log($user, '/'.$this->router->fetch_class().'/'.$this->router->fetch_method(), '-', 'VIEW');

        $this->loadView($data);
    }
    
    function placeOrder() {
        $data['title'] = 'Order - Hybrid Management System';
        $data['view_file'] = 'placeOrder';
	$data['cart_item'] = $this->checkCart($_SESSION['usercode'])->result();
	$data['stocks'] = $this->getStock()->result();

	// log audit trail
	$user = $_SESSION['username'];
	$this->fire_audit_log($user, '/'.$this->router->fetch_class().'/'.$this->router->fetch_method(), '-', 'VIEW');

        $this->loadView($data);
    }

	function engine() {
        	$data['title'] = 'Order - Hybrid Management System';
        	$data['view_file'] = 'placeOrder';
		$data['cart_item'] = $this->checkCart($_SESSION['usercode'])->result();

		// log audit trail
		$user = $_SESSION['username'];
		$this->fire_audit_log($user, '/'.$this->router->fetch_class().'/'.$this->router->fetch_method(), '-', 'VIEW');

		$data['stocks'] = $this->getStock()->result();
        	$this->loadView($data);
	}

    function loadView($data) {
        $data['module'] = 'order';
        echo Modules::run('templ/templContent', $data);
    }
	
	# LOAD CART ITEMS
	function checkCart($partner) {
        $this->load->model('mdl_order');
        $query = $this->mdl_order->checkCart($partner);
        return $query;
    }
	# GET ALL STOCK ITEMS
	function getStock() {
		$this->load->model('mdl_order');
		$sql = "select * from hy_stock_master order by facevalue asc";
		$query = $this->_custom_query($sql);
        return $query;
	}
	# ORDER CONFIRMATION LANDING PAGE
	function vieworder() {
		$order_no = $_GET['order'];
		$data['title'] = 'Order - Hybrid Management System';
        	$data['view_file'] = 'orderDetails';
		$data['order'] = $this->order($order_no)->result();
		$data['order_details'] = $this->orderDetails($order_no)->result();

		// log audit trail
		$user = $_SESSION['username'];
		$this->fire_audit_log($user, '/'.$this->router->fetch_class().'/'.$this->router->fetch_method(), 'Order - '.$order_no, 'VIEW-ORDER');

        	$this->loadView($data);
	}
	# PENDING APPROVAL ORDER LIST PAGE
	function approvalList() {
		# Fire Approval Operation
		if(isset($_GET['approve'])) {
			$order_no = base64_decode($_GET['approve']);
			$data['approved'] = $this->approveOrder($order_no)->result();

			// log audit trail
			$user = $_SESSION['username'];
			$this->fire_audit_log($user, '/'.$this->router->fetch_class().'/'.$this->router->fetch_method(), 'Order: '.$order_no, 'APPROVE-ORDER');

		}
		if(isset($_GET['cancel'])) {
			$order_no = base64_decode($_GET['cancel']);
			$sql = "select * from hy_orders where order_status = 'CANCELLED' and order_no = $order_no";
			if($this->_custom_num_rows_query($sql)==0) {
				# GET DEALER TRADE CODE
				$data['tradeCode'] = $this->getTradeCode($order_no)->result();
				foreach($data['tradeCode'] as $code) {
					$tradecode = $code->PARTNER_CODE;
				}

				# GET EVC ACCOUNT
				$data['getEVC'] = $this->getEVC($tradecode)->result();
				foreach($data['getEVC'] as $evc) {
					$account = $evc->EVC_ACCT_CODE;
				}

				# GET ORDER TOTAL
				$data['getTotal'] = $this->getTotal($order_no)->result();
				foreach($data['getTotal'] as $get) {
					$total = $get->TOTAL*100;
				}

				# ENSURE EVC Wallet Refund
				if($this->refundEVCAccount($account, $total)==="SUCC") {
					$data['cancelled'] = $this->cancelOrder($order_no)->result();				
				}

				// log audit trail
				$user = $_SESSION['username'];
				$this->fire_audit_log($user, '/'.$this->router->fetch_class().'/'.$this->router->fetch_method(), 'Order: '.$order_no, 'CANCEL-ORDER');
			}
			else {
				$data['cancelled'] = $this->cancelOrder($order_no)->result();

				// log audit trail
				$user = $_SESSION['username'];
				$this->fire_audit_log($user, '/'.$this->router->fetch_class().'/'.$this->router->fetch_method(), 'Order: '.$order_no, 'CANCEL-ORDER');
			}
		}
		$order_no = $_GET['order'];
		$data['title'] = 'Pending Approval Order List - Hybrid Management System';
        	$data['view_file'] = 'pendingApproval';
		$data['get_pending'] = $this->pendingOrder('sysdate')->result();
        	$this->loadView($data);
	}
	# DEALER ORDER APPROVAL PAGE
	function orderApprove() {
		# Fire Approval Operation
		if(isset($_GET['approve'])) {
			$order_no = base64_decode($_GET['approve']);
			$data['approved'] = $this->approveOrder($order_no)->result();
				
			// log audit trail
			$user = $_SESSION['username'];
			$this->fire_audit_log($user, '/'.$this->router->fetch_class().'/'.$this->router->fetch_method(), 'Order - '.$order_no, 'APPROVE-ORDER');
		}
		if(isset($_GET['cancel'])) {
			$order_no = base64_decode($_GET['cancel']);

			# GET DEALER TRADE CODE
			$data['tradeCode'] = $this->getTradeCode($order_no)->result();
			foreach($data['tradeCode'] as $code) {
				$code = $code->PARTNER_CODE;
			}

			# GET EVC ACCOUNT
			$data['getEVC'] = $this->getEVC($code)->result();
			foreach($data['getEVC'] as $evc) {
				$account = $evc->EVC_ACCT_CODE;
			}

			# GET ORDER TOTAL
			$data['getTotal'] = $this->getTotal($order_no)->result();
			foreach($data['getTotal'] as $get) {
				$total = $get->TOTAL*100;
			}
			# ENSURE EVC Wallet Refund
			$this->refundEVCAccount($account, $total);
			if($this->refundEVCAccount($account, $total)==="SUCC") {
				$data['cancelled'] = $this->cancelOrder($order_no)->result();	
				// log audit trail
				$user = $_SESSION['username'];
				$this->fire_audit_log($user, '/'.$this->router->fetch_class().'/'.$this->router->fetch_method(), 'Order - '.$order_no, 'CANCEL-ORDER');			
			}
		}
		$order_no = $_GET['order'];
		$data['title'] = 'Order Approval - Hybrid Management System';
        	$data['view_file'] = 'orderApproval';
		$data['get_approval'] = $this->getApprovalList(date('Ym'), $_SESSION['usercode'])->result();

		// log audit trail
		$user = $_SESSION['username'];
		$this->fire_audit_log($user, '/'.$this->router->fetch_class().'/'.$this->router->fetch_method(), 'Order-Approval', 'VIEW');

        	$this->loadView($data);
	}
	# My Orders
	function myOrders() {
		$data['title'] = 'My Order List - Hybrid Management System';
        	$data['view_file'] = 'myOrders';
		
		// log audit trail
		$user = $_SESSION['username'];
		$this->fire_audit_log($user, '/'.$this->router->fetch_class().'/'.$this->router->fetch_method(), 'Dealer-Order', 'VIEW');

		$data['get_pending'] = $this->orderHistory($_SESSION['usercode'])->result();
       	 	$this->loadView($data);
	}
	# GET PENDING APPROVAL ORDERS
	function pendingOrder($date) {
		$this->load->model('mdl_order');
        	if(!isset($_GET['start']) && !isset($_GET['end'])) {
    			$sql = "select 
    				partner_code,
    				order_no, 
    				to_char(order_date,'YYYY-MM-DD HH24:MI:SS') order_date,
    				order_status,
    				approved_by,
    				to_char(approval_date,'YYYY-MM-DD HH24:MI:SS') approval_date,
    				to_char(date_cancelled,'YYYY-MM-DD HH24:MI:SS') date_cancelled,
    				cancelled_by
    				from hy_orders where trunc(order_date) = trunc($date) order by order_date desc";
        	}
        	else {
           		$begin = $_GET['start'];
           		$end = $_GET['end'];
           		$sql = "select 
    				partner_code,
    				order_no, 
    				to_char(order_date,'YYYY-MM-DD HH24:MI:SS') order_date,
    				order_status,
    				approved_by,
    				to_char(approval_date,'YYYY-MM-DD HH24:MI:SS') approval_date,
    				to_char(date_cancelled,'YYYY-MM-DD HH24:MI:SS') date_cancelled,
    				cancelled_by
    				from hy_orders where trunc(order_date) between to_date('$begin', 'MM/DD/YYYY') and to_date('$end', 'MM/DD/YYYY') order by order_date asc";
        	}
		$query = $this->_custom_query($sql);
        	return $query;
	}

	# GET DELER PENDING APPROVED ORDERS
	function getApprovalList($date, $dealer) {
		$this->load->model('mdl_order');
        	if(!isset($_GET['start']) && !isset($_GET['end'])) {
			$dealer = $_SESSION['usercode'];
    			$sql = "select 
    				o.partner_code,
    				o.order_no, 
    				to_char(o.order_date,'YYYY-MM-DD HH24:MI:SS') order_date,
    				o.order_status,
    				o.approved_by,
    				to_char(o.approval_date,'YYYY-MM-DD HH24:MI:SS') approval_date,
    				o.date_cancelled,
    				o.cancelled_by
    				from hy_orders o where o.partner_code = '$dealer' and o.order_status not in ('BOOKED', 'CANCELLED') and to_char(order_date, 'YYYYMM') = $date";
        	}
        	else {
           		$begin = $_GET['start'];
           		$end = $_GET['end'];
			$dealer = $_SESSION['usercode'];
           		$sql = "select 
    				o.partner_code,
    				o.order_no, 
    				to_char(o.order_date,'YYYY-MM-DD HH24:MI:SS') order_date,
    				o.order_status,
    				o.approved_by,
    				to_char(o.approval_date,'YYYY-MM-DD HH24:MI:SS') approval_date,
    				o.date_cancelled,
    				o.cancelled_by
    				from hy_orders o where o.partner_code = '$dealer' and o.order_status not in ('BOOKED', 'CANCELLED') and trunc(order_date) between to_date('$begin', 'MM/DD/YYYY') and to_date('$end', 'MM/DD/YYYY') order by order_date asc";
        	}
		$query = $this->_custom_query($sql);
        	return $query;
	}

	# GET DEALER ORDERS HISTORY
	function orderHistory($dealer) {
		$this->load->model('mdl_order');
        	if(!isset($_GET['start']) && !isset($_GET['end'])) {
			$dealer = $_SESSION['usercode'];
    			$sql = "select 
    				o.order_no, 
    				to_char(o.order_date,'YYYY-MM-DD HH24:MI:SS') order_date,
    				o.order_status,
    				o.approved_by,
    				to_char(o.approval_date,'YYYY-MM-DD HH24:MI:SS') approval_date,
    				o.date_cancelled,
    				o.cancelled_by
    				from hy_orders o where partner_code = '$dealer' and to_char(order_date, 'YYYYMM') = to_char(sysdate, 'YYYYMM') order by order_date desc";
        	}
        	else {
           		$begin = $_GET['start'];
           		$end = $_GET['end'];
			$dealer = $_SESSION['usercode'];

          		 $sql = "select 
    				o.order_no, 
    				to_char(o.order_date,'YYYY-MM-DD HH24:MI:SS') order_date,
    				o.order_status,
    				o.approved_by,
    				to_char(o.approval_date,'YYYY-MM-DD HH24:MI:SS') approval_date,
    				o.date_cancelled,
    				o.cancelled_by
    				from hy_orders o where partner_code = '$dealer' and trunc(order_date) between to_date('$begin', 'MM/DD/YYYY') and to_date('$end', 'MM/DD/YYYY') order by order_date asc";
        	}
		$query = $this->_custom_query($sql);
        return $query;
	}
	# GET ORDER DETAILS
	function order($order_no) {
		$this->load->model('mdl_order');	
		$sql = "select 
					o.partner_code,
					o.order_no, 
					to_char(o.order_date,'YYYY-MM-DD HH24:MI:SS') ord_date,
					o.order_status, 
					to_char(o.time_delivered,'YYYY-MM-DD HH24:MI:SS') time_delivered
				from hy_orders o
				where o.order_no = $order_no order by order_date asc";
		$query = $this->_custom_query($sql);
        return $query;
	}
	# GET ORDER DETAILS WITH ITEMS
	function orderDetails($order_no) {
		$this->load->model('mdl_order');
        $sql = "select 
					od.LINE_ITEM_NO,
					od.DESCRIPTION,
					od.quantity,
					sm.EVC_UNITS Unit_price,
					(sm.EVC_UNITS*od.quantity) Amount
				from hy_order_details od, hy_stock_master sm
				where sm.stock_id = od.item_code and od.order_no = $order_no order by od.line_item_no asc";
		$query = $this->_custom_query($sql);
        return $query;
	}
	function approveOrder($order_no) {
		$this->load->model('mdl_order');
		$sql = "select PKG_HY_ORDER_MANAGER.FN_APPROVE_ORDER('".$_SESSION['username']."',  $order_no) as result from dual";
		$query = $this->_custom_query($sql);
		return $query;
	}
	function cancelOrder($order_no) {
		$this->load->model('mdl_order');
		$sql = "select PKG_HY_ORDER_MANAGER.FN_CANCEL_ORDER('".$_SESSION['username']."',  $order_no) as result from dual";
		$query = $this->_custom_query($sql);
		return $query;
	}

	# Modify EVC Balance REST
	function refundEVCAccount($account, $debit) {
		$url = "http://10.158.6.80:8186/HybridLite/ModifyEVCInventory?USERNAME=hpin&PASSWORD=Emts1234&MSISDN=$account&ACCOUNT=$debit";
		$open=curl_init($url);
		curl_setopt($open, CURLOPT_URL, $url);
		curl_setopt($open, CURLOPT_RETURNTRANSFER, 1);
		$data=curl_exec($open);
		$split = explode('|', $data);
		$response = $split[0];
		return $response;
	}
	
	# GET DEALER TRADE CODE
	function getTradeCode($order) {
		$sql = "select PARTNER_CODE from hy_orders where order_no = $order";
		$query = $this->_custom_query($sql);
		return $query;
	}

	# GET EVC Account
	function getEVC($tradecode) {
		$sql = "select EVC_ACCT_CODE from hy_party where PARTYCODE = '$tradecode'";
		$query = $this->_custom_query($sql);
		return $query;
	}

	# GET ORDER ITEM TOTAL
	function getTotal($order) {
		$sql = "select sum(a.QUANTITY*b.EVC_UNITS) TOTAL from hy_order_details a, hy_stock_master b where a.order_no = $order and a.ITEM_CODE = B.STOCK_ID";
		$query = $this->_custom_query($sql);
		return $query;
	}


    function log($process) {
        $date = date('y-m-d h:i:s');
        $userid = $_SESSION['userid'];
        $process = "RECIPIENT  : " . $process;
        $query = "INSERT INTO log VALUES ('', '$process','$date', $userid)";
        $this->_custom_query($query);
    }

    function _custom_num_rows_query($mysql_query) {
        $this->load->model('mdl_order');
        $query = $this->mdl_order->_custom_num_rows_query($mysql_query);
        return $query;
    }

    function _custom_query($mysql_query) {
        $this->load->model('mdl_order');
        $query = $this->mdl_order->_custom_query($mysql_query);
        return $query;
    }

	function fire_audit_log($user, $url, $auditdata, $action) {
		$sql = "insert into hy_audit_trail (AUDITID, SESSIONID, IPADDRESS, USERID, URLACCESSED, TIMEACCESSED, AUDITDATA, AUDITACTION)
		values(AUDIT_TRAIL_ID_SEQ.nextval, '".session_id()."', '".$_SERVER["REMOTE_ADDR"]."', '".$user."', '".$url."', sysdate, '".$auditdata."', '".$action."')";
		$this->_custom_query($sql);
    	}

}

?>