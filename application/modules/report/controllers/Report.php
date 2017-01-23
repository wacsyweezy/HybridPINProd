<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Report extends MX_Controller {

    function __construct() {
        parent::__construct();
        $this->load->library('form_validation');
        //$this->load->library('MY_Log');
        //$this->load->library('log');
    }

    function index() {
        $data['title'] = 'Report System - Hybrid Management System';
        $data['view_file'] = 'getReport';

	// log audit trail
	$user = $_SESSION['username'];
	$this->fire_audit_log($user, '/'.$this->router->fetch_class().'/'.$this->router->fetch_method(), '-', 'VIEW');

        $this->loadView($data);
    }
	
	function getReport() {
        $data['title'] = 'Report System - Hybrid Management System';
        $data['view_file'] = 'getReport';
	$data['dealers'] = $this->getDealers()->result();

	// log audit trail
	$user = $_SESSION['username'];
	$this->fire_audit_log($user, '/'.$this->router->fetch_class().'/'.$this->router->fetch_method(), '-', 'VIEW');

        $this->loadView($data);
    }

	function myReport() {
        	$data['title'] = 'Report System - Hybrid Management System';
        	$data['view_file'] = 'getDealerReport';

		// log audit trail
		$user = $_SESSION['username'];
		$this->fire_audit_log($user, '/'.$this->router->fetch_class().'/'.$this->router->fetch_method(), '-', 'VIEW');

        	$this->loadView($data);
    	}
	
	function reportList() {
        $data['title'] = 'Report System - Hybrid Management System';
        $data['view_file'] = 'listReport';
        $this->loadView($data);
    }
	
    function loadView($data) {
        $data['module'] = 'report';
        echo Modules::run('templ/templContent', $data);
    }
	function generateMyReport() {
		$dealer = $_SESSION['usercode'];
		$dealer_name = $_SESSION['usercode'];
		$begin = $_GET['start'];
		$end = $_GET['end'];

		// log audit trail
		$user = $_SESSION['username'];
		$this->fire_audit_log($user, '/'.$this->router->fetch_class().'/'.$this->router->fetch_method(), 'Begin: '.$begin.' - End: '.$end, 'SPOOL-REPORT');

		$sql = "
			select 
			o.partner_code,
			o.order_no,
			d.item_code,
			D.QUANTITY,
			to_char(o.order_date,'YYYY-MM-DD HH24:MI:SS') order_date,
			o.order_status,
			o.approved_by,
			to_char(o.approval_date,'YYYY-MM-DD HH24:MI:SS') approval_date,
			o.date_cancelled,
			o.cancelled_by
			from hy_orders o, hy_order_details d
			where partner_code = '$dealer' and trunc(order_date) between to_date('$begin', 'MM/DD/YYYY') and to_date('$end', 'MM/DD/YYYY')
			and o.order_no = d.order_no order by order_date asc";
		$data['spooled'] = $this->_custom_query($sql)->result();
		$data['output'] = "
			<table id='example1' class='table table-striped table-bordered table-hover' width='100%'>
				<thead>
					<tr>
						<th>Order Date</th>
						<th>Order No</th>
						<th>Stock Item</th>
						<th>Quantity</th>
						<th>Approved By</th>
						<th>Delivery Date</th>
						<th>Status</th>
					</tr>
				</thead>
				<tbody>";
		foreach($data['spooled'] as $spool) {
			$data['output'] .= '
				<tr>
					<td>'.$spool->ORDER_DATE.'</td>
					<td>'.$spool->ORDER_NO.'</td>
					<td>'.$spool->ITEM_CODE.'</td>
					<td>'.number_format($spool->QUANTITY).'</td>
					<td>'.$spool->APPROVED_BY.'</td>
					<td>'.$spool->TIME_DELIVERED.'</td>
					<td>'.$spool->ORDER_STATUS.'</td>
				</tr>';
		}
		$data['output'] .= "
					</tbody>
				</table>";
		$this->load->view('outputReport', $data);
	}

	function generateDealerReport() {
		$dealer = $_GET['dealer'];
		$dealer_name = $_GET['title'];
		$dealer_name = explode(' - ', $dealer_name);
		$begin = $_GET['start'];
		$end = $_GET['end'];

		// log audit trail
		$user = $_SESSION['username'];
		$this->fire_audit_log($user, '/'.$this->router->fetch_class().'/'.$this->router->fetch_method(), 'Dealer: '.$dealer.' - Begin: '.$begin.' - End: '.$end, 'SPOOL-REPORT');

		$sql = "
			select 
			o.partner_code,
			o.order_no,
			d.item_code,
			D.QUANTITY,
			to_char(o.order_date,'YYYY-MM-DD HH24:MI:SS') order_date,
			o.order_status,
			o.approved_by,
			to_char(o.approval_date,'YYYY-MM-DD HH24:MI:SS') approval_date,
			o.date_cancelled,
			o.cancelled_by
			from hy_orders o, hy_order_details d
			where partner_code = '$dealer' and trunc(order_date) between to_date('$begin', 'MM/DD/YYYY') and to_date('$end', 'MM/DD/YYYY')
			and o.order_no = d.order_no order by order_date asc";
		$data['spooled'] = $this->_custom_query($sql)->result();
		$data['output'] = "
			<table id='example1' class='table table-striped table-bordered table-hover' width='100%'>
				<thead>
					<tr>
						<th colspan='8'><h4>Orders Placed By ".$dealer_name[1]."</h4></th>
					</tr>
					<tr>
						<th>Order Date</th>
						<th>Order No</th>
						<th>Stock Item</th>
						<th>Quantity</th>
						<th>Approved By</th>
						<th>Delivery Date</th>
						<th>Status</th>
					</tr>
				</thead>
				<tbody>";
		foreach($data['spooled'] as $spool) {
			$data['output'] .= '
				<tr>
					<td>'.$spool->ORDER_DATE.'</td>
					<td>'.$spool->ORDER_NO.'</td>
					<td>'.$spool->ITEM_CODE.'</td>
					<td>'.number_format($spool->QUANTITY).'</td>
					<td>'.$spool->APPROVED_BY.'</td>
					<td>'.$spool->TIME_DELIVERED.'</td>
					<td>'.$spool->ORDER_STATUS.'</td>
				</tr>';
		}
		$data['output'] .= "
					</tbody>
				</table>";
		$this->load->view('outputReport', $data);
	}

	function generateOrderReport() {
		$begin = $_GET['start'];
		$end = $_GET['end'];

		// log audit trail
		$user = $_SESSION['username'];
		$this->fire_audit_log($user, '/'.$this->router->fetch_class().'/'.$this->router->fetch_method(), 'Begin: '.$begin.' - End: '.$end, 'SPOOL-REPORT');

		$sql = "select 
			o.partner_code,
			hp.PARTYNAME,
			o.order_no,
			d.item_code,
			D.QUANTITY,
			to_char(o.order_date,'YYYY-MM-DD HH24:MI:SS') order_date,
			o.order_status,
			o.approved_by,
			to_char(o.approval_date,'YYYY-MM-DD HH24:MI:SS') approval_date,
			o.date_cancelled,
			o.cancelled_by
			from hy_orders o, hy_order_details d, hy_party hp
			where trunc(order_date) between to_date('$begin', 'MM/DD/YYYY') and to_date('$end', 'MM/DD/YYYY')
			and o.order_no = d.order_no and hp.partycode = o.partner_code order by order_date asc";
		$data['spooled'] = $this->_custom_query($sql)->result();
		$data['output'] = "
			<table id='example1' class='table table-striped table-bordered table-hover' width='100%'>
				<thead>
					<tr>
						<th>Order Date</th>
						<th>Dealer Code</th>
						<th>Dealer Name</th>
						<th>Order No</th>
						<th>Stock Item</th>
						<th>Quantity</th>
						<th>Approved By</th>
						<th>Delivery Date</th>
						<th>Status</th>
					</tr>
				</thead>
				<tbody>";
		foreach($data['spooled'] as $spool) {
			$data['output'] .= '
				<tr>
					<td>'.$spool->ORDER_DATE.'</td>
					<td>'.$spool->PARTNER_CODE.'</td>
					<td>'.$spool->PARTYNAME.'</td>
					<td>'.$spool->ORDER_NO.'</td>
					<td>'.$spool->ITEM_CODE.'</td>
					<td>'.number_format($spool->QUANTITY).'</td>
					<td>'.$spool->APPROVED_BY.'</td>
					<td>'.$spool->TIME_DELIVERED.'</td>
					<td>'.$spool->ORDER_STATUS.'</td>
				</tr>';
		}
		$data['output'] .= "
					</tbody>
				</table>";
		$this->load->view('outputReport', $data);
	}

	function generateOnlineReport() {
		$begin = $_GET['start'];
		$end = $_GET['end'];

		// log audit trail
		$user = $_SESSION['username'];
		$this->fire_audit_log($user, '/'.$this->router->fetch_class().'/'.$this->router->fetch_method(), 'Begin: '.$begin.' - End: '.$end, 'SPOOL-REPORT');

		$sql = " select transid, request_msisdn msisdn, sequence_number serial, request_amount amount, REQUEST_DATE, 
    			case 
        			when REQUEST_STATUS = 'P' then 'Delivered' 
        			when REQUEST_STATUS = 'N' then 'Pending'
    			end REQUEST_STATUS from hy_oltp_pin_requests where trunc(REQUEST_DATE) between to_date('$begin', 'MM/DD/YYYY') and to_date('$end', 'MM/DD/YYYY')
			 order by REQUEST_DATE asc";
		$data['spooled'] = $this->_custom_query($sql)->result();
		$data['output'] = "
			<table id='example1' class='table table-striped table-bordered table-hover' width='100%'>
				<thead>
					<tr>
						<th>Ref ID</th>
						<th>e-Purse Number</th>
						<th>Serial Number</th>
						<th>Amount</th>
						<th>Date/Time</th>
						<th>Status</th>
					</tr>
				</thead>
				<tbody>";
		foreach($data['spooled'] as $spool) {
			$data['output'] .= '
				<tr>
					<td>'.$spool->TRANSID.'</td>
					<td>'.$spool->MSISDN.'</td>
					<td>'.$spool->SERIAL.'</td>
					<td>'.$spool->AMOUNT.'</td>
					<td>'.$spool->REQUEST_DATE.'</td>
					<td>'.$spool->REQUEST_STATUS.'</td>
				</tr>';
		}
		$data['output'] .= "
					</tbody>
				</table>";
		$this->load->view('outputReport', $data);
	}

    function log($process) {
        $date = date('y-m-d h:i:s');
        $userid = $_SESSION['userid'];
        $process = "RECIPIENT  : " . $process;
        $query = "INSERT INTO log VALUES ('', '$process','$date', $userid)";
        $this->_custom_query($query);
    }
	
    function getDealers() {
        $this->load->model('mdl_report');
        $query = $this->mdl_report->getAllDealers();
        return $query;
    }

    function get($order_by) {
        $this->load->model('mdl_report');
        $query = $this->mdl_report->get($order_by);
        return $query;
    }

    function get_with_limit($limit, $offset, $order_by) {
        $this->load->model('mdl_report');
        $query = $this->mdl_report->get_with_limit($limit, $offset, $order_by);
        return $query;
    }

    function get_where($id) {
        $this->load->model('mdl_report');
        $query = $this->mdl_report->get_where($id);
        return $query;
    }

    function get_where_custom($col, $value) {
        $this->load->model('mdl_report');
        $query = $this->mdl_report->get_where_custom($col, $value);
        return $query;
    }

    function count_where($column, $value) {
        $this->load->model('mdl_report');
        $count = $this->mdl_report->count_where($column, $value);
        return $count;
    }

    function get_max() {
        $this->load->model('mdl_report');
        $max_id = $this->mdl_report->get_max();
        return $max_id;
    }

    function _custom_num_rows_query($mysql_query) {
        $this->load->model('mdl_report');
        $query = $this->mdl_report->_custom_num_rows_query($mysql_query);
        return $query;
    }

    function _custom_query($mysql_query) {
        $this->load->model('mdl_report');
        $query = $this->mdl_report->_custom_query($mysql_query);
        return $query;
    }

	function fire_audit_log($user, $url, $auditdata, $action) {
		$sql = "insert into hy_audit_trail (AUDITID, SESSIONID, IPADDRESS, USERID, URLACCESSED, TIMEACCESSED, AUDITDATA, AUDITACTION)
		values(AUDIT_TRAIL_ID_SEQ.nextval, '".session_id()."', '".$_SERVER["REMOTE_ADDR"]."', '".$user."', '".$url."', sysdate, '".$auditdata."', '".$action."')";
		$this->_custom_query($sql);
	}

}

?>