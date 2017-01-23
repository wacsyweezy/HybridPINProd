<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Rules extends MX_Controller {
    function __construct() {
        parent::__construct();
        $this->load->library('form_validation');
		parse_str($_SERVER['QUERY_STRING'], $_GET);
        //$this->load->library('MY_Log');
        //$this->load->library('log');
    }

    function index() {
        $data['title'] = 'Order - Hybrid Management System';
        $data['view_file'] = 'index';
        $this->loadView($data);
    }

    function loadView($data) {
        $data['module'] = 'rules';
        echo Modules::run('templ/templContent', $data);
    }

	# DEALER RULES PAGE
	function dealer() {
		$data['title'] = 'Dealer Rules - Hybrid Management System';
       	 	$data['view_file'] = 'dealer';
		$data['rules'] = $this->getDealerRules()->result();
		$data['dealers'] = $this->getDealers()->result();
		$data['items'] = $this->getStock()->result();

		// log audit trail
		$user = $_SESSION['username'];
		$this->fire_audit_log($user, '/'.$this->router->fetch_class().'/'.$this->router->fetch_method(), '-', 'VIEW');
        	
		$this->loadView($data);
	}

	# ADD DEALER RULE
	public function adddealerrule() {
		$this->form_validation->set_rules('itemtype', 'itemtype', 'trim|required');
		$this->form_validation->set_rules('partnercode', 'partnercode', 'trim|required');
		$this->form_validation->set_rules('maxdailytxn', 'maxdailytxn', 'trim|required');
		$this->form_validation->set_rules('maxmonthlytxn', 'maxmonthlytxn', 'trim|required');
		$this->form_validation->set_rules('maxdailyvolume', 'maxdailyvolume', 'trim|required');
		$this->form_validation->set_rules('maxmonthlyvolume', 'maxmonthlyvolume', 'trim|required');
		$this->form_validation->set_rules('maxtxnvolume', 'maxtxnvolume', 'trim|required');
		$this->form_validation->set_rules('mintxnvolume', 'mintxnvolume', 'trim|required');
		if ($this->form_validation->run() === FALSE) {
            		$data['title'] = 'Dealer Rules - Hybrid Management System';
            		$data['msg'] = '<div class="alert alert-warning"><i class="fa fa-bolt"></i> Error please fill in all the fields.</div>';
            		$data['view_file'] = 'dealer';
			$data['dealers'] = $this->getDealers()->result();
			$data['items'] = $this->getStock()->result();
			$data['rules'] = $this->getDealerRules()->result();
            		$this->loadView($data);
        	} 
		else {
			$partnercode = $this->input->post('partnercode', TRUE);
			$itemtype = $this->input->post('itemtype', TRUE);	
			$data['addResult'] = $this->addDealerRules()->result();
		
			// log audit trail
			$user = $_SESSION['username'];
			$this->fire_audit_log($user, '/'.$this->router->fetch_class().'/'.$this->router->fetch_method(), 'Dealer: '.$partnercode.' - Item: '.$itemtype, 'ADD-DEALER-RULE');

			$data['rules'] = $this->getDealerRules()->result();
			$data['dealers'] = $this->getDealers()->result();
			$data['items'] = $this->getStock()->result();			
			$data['title'] = 'Dealer Rules - Hybrid Management System';
			$data['view_file'] = 'dealer';
			$this->loadView($data);
		}	
	}
	# GET ALL DEALERS RULES
	function getDealerRules() {
		$this->load->model('mdl_rules');
		$sql = "select 
				ruleid,
				dealercode dealer,
				itemcode item,
				max_daily_txn_count MX_DAY_TXN,
				max_monthly_txn_count MX_MONTH_TXN,
				max_daily_volume MX_DAY_VOL,
				max_monthly_volume MX_MONTH_VOL,
				min_txn_volume MN_TXN_VOL,
				max_txn_volume MX_TXN_VOL,
				to_char(created_date, 'YYYY-MM-DD HH24:MI:SS') ddate,
				created_by creator
			from hy_dealerrules";
		$query = $this->_custom_query($sql);
        	return $query;
	}
	# ADD DEALER RULE
	function addDealerRules() {
		$this->load->model('mdl_rules');
		$partnercode = $this->input->post('partnercode', TRUE);
		$itemtype = $this->input->post('itemtype', TRUE);		
		$maxdailytxn = $this->input->post('maxdailytxn', TRUE);
		$maxmonthlytxn = $this->input->post('maxmonthlytxn', TRUE);
		$maxdailyvolume = $this->input->post('maxdailyvolume', TRUE);
		$maxmonthlyvolume = $this->input->post('maxmonthlyvolume', TRUE);
		$maxtxnvolume = $this->input->post('maxtxnvolume', TRUE);
		$mintxnvolume = $this->input->post('mintxnvolume', TRUE);
		
		$sql = "select PKG_HY_RULES_ENGINE.FN_ADD_DEALER_RULE(
				'".$_SESSION['username']."', 
				'".$partnercode."', 
				'".$itemtype."', 
				$maxdailytxn, 
				$maxmonthlytxn,
				$maxdailyvolume,
				$maxmonthlyvolume,
				$maxtxnvolume,
				$mintxnvolume) as result from dual";
		$query = $this->_custom_query($sql);
        	return $query;
	}
	# REMOVE DEALER RULE
	function removedealer() {
		$itemtype = $this->input->post('itemtype', TRUE);	
		$data['deleteResult'] = $this->deleteDealerRules()->result();
			
		// log audit trail
		$user = $_SESSION['username'];
		$this->fire_audit_log($user, '/'.$this->router->fetch_class().'/'.$this->router->fetch_method(), '-', 'DELETE-DEALER-RULE');
		
		$data['rules'] = $this->getDealerRules()->result();
		$data['dealers'] = $this->getDealers()->result();
		$data['items'] = $this->getStock()->result();			
		$data['title'] = 'Dealer Rules - Hybrid Management System';
		$data['view_file'] = 'dealer';
		$this->loadView($data);
	}
	# DELETE DEALER RULE
	function deleteDealerRules() {
		$this->load->model('mdl_rules');
		$ruleid = $_GET['rule'];
		$sql = "select PKG_HY_RULES_ENGINE.FN_REMOVE_DEALER_RULE(
				'".$ruleid."', 
				'".$_SESSION['username']."') as result from dual";
		$query = $this->_custom_query($sql);
        	return $query;
	}	
	# ITEM RULES PAGE
	function item() {
		$data['title'] = 'Item Rules - Hybrid Management System';
        	$data['view_file'] = 'item';
		$data['rules'] = $this->getItemRules()->result();
		$data['items'] = $this->getStock()->result();

		// log audit trail
		$user = $_SESSION['username'];
		$this->fire_audit_log($user, '/'.$this->router->fetch_class().'/'.$this->router->fetch_method(), '-', 'VIEW');

        	$this->loadView($data);
	}
	# ADD ITEM RULE
	public function additemrule() {
		$this->form_validation->set_rules('itemtype', 'itemtype', 'trim|required');
		$this->form_validation->set_rules('maxdailytxn', 'maxdailytxn', 'trim|required');
		$this->form_validation->set_rules('maxmonthlytxn', 'maxmonthlytxn', 'trim|required');
		$this->form_validation->set_rules('maxdailyvolume', 'maxdailyvolume', 'trim|required');
		$this->form_validation->set_rules('maxmonthlyvolume', 'maxmonthlyvolume', 'trim|required');
		$this->form_validation->set_rules('maxtxnvolume', 'maxtxnvolume', 'trim|required');
		$this->form_validation->set_rules('mintxnvolume', 'mintxnvolume', 'trim|required');
		if ($this->form_validation->run() === FALSE) {
            		$data['title'] = 'Item Rules - Hybrid Management System';
            		$data['msg'] = '<div class="alert alert-warning"><i class="fa fa-bolt"></i> Error please fill in all the fields.</div>';
            		$data['view_file'] = 'item';
			$data['items'] = $this->getStock()->result();
			$data['rules'] = $this->getItemRules()->result();
            		$this->loadView($data);
        	} 
		else {
			$itemtype = $this->input->post('itemtype', TRUE);	
			$data['addResult'] = $this->addItemRules()->result();

			// log audit trail
			$user = $_SESSION['username'];
			$this->fire_audit_log($user, '/'.$this->router->fetch_class().'/'.$this->router->fetch_method(), 'Item: '.$itemtype, 'ADD-ITEM-RULE');

			$data['items'] = $this->getStock()->result();
			$data['rules'] = $this->getItemRules()->result();			
			$data['title'] = 'Item Rules - Hybrid Management System';
			$data['view_file'] = 'item';
			$this->loadView($data);
		}	
	}
	# GET ALL ITEMS RULES
	function getItemRules() {
		$this->load->model('mdl_rules');
		$sql = "select 
				RULEID,
				itemcode item,
				max_daily_txn_count MX_DAY_TXN,
				max_monthly_txn_count MX_MONTH_TXN,
				max_daily_volume MX_DAY_VOL,
				max_monthly_volume MX_MONTH_VOL,
				min_txn_volume MN_TXN_VOL,
				max_txn_volume MX_TXN_VOL,
				to_char(created_date, 'YYYY-MM-DD HH24:MI:SS') ddate,
				created_by creator
			from hy_itemrules";
		$query = $this->_custom_query($sql);
        	return $query;
	}
	# REMOVE ITEM RULE
	function itemremove() {
		$itemtype = $this->input->post('itemtype', TRUE);	
		$data['deleteResult'] = $this->deleteItemRules()->result();

		// log audit trail
		$user = $_SESSION['username'];
		$this->fire_audit_log($user, '/'.$this->router->fetch_class().'/'.$this->router->fetch_method(), '-', 'DELETE-ITEM-RULE');

		$data['items'] = $this->getStock()->result();
		$data['rules'] = $this->getItemRules()->result();			
		$data['title'] = 'Item Rules - Hybrid Management System';
		$data['view_file'] = 'item';
		$this->loadView($data);
	}
	# ADD ITEM RULE
	function addItemRules() {
		$this->load->model('mdl_rules');
		$itemtype = $this->input->post('itemtype', TRUE);		
		$maxdailytxn = $this->input->post('maxdailytxn', TRUE);
		$maxmonthlytxn = $this->input->post('maxmonthlytxn', TRUE);
		$maxdailyvolume = $this->input->post('maxdailyvolume', TRUE);
		$maxmonthlyvolume = $this->input->post('maxmonthlyvolume', TRUE);
		$maxtxnvolume = $this->input->post('maxtxnvolume', TRUE);
		$mintxnvolume = $this->input->post('mintxnvolume', TRUE);
		
		$sql = "select PKG_HY_RULES_ENGINE.FN_ADD_ITEM_RULE(
				'".$_SESSION['username']."', 
				'".$itemtype."', 
				$maxdailytxn, 
				$maxmonthlytxn,
				$maxdailyvolume,
				$maxmonthlyvolume,
				$maxtxnvolume,
				$mintxnvolume) as result from dual";
		$query = $this->_custom_query($sql);
        	return $query;
	}
	# DELETE ITEM RULE
	function deleteItemRules() {
		$this->load->model('mdl_rules');
		$ruleid = $_GET['rule'];
		$sql = "select PKG_HY_RULES_ENGINE.FN_REMOVE_ITEM_RULE(
				'".$ruleid."', 
				'".$_SESSION['username']."') as result from dual";
		$query = $this->_custom_query($sql);
        	return $query;
	}
	# GET ALL STOCK ITEMS
	function getStock() {
		$this->load->model('mdl_rules');
		$sql = "select * from hy_stock_master order by facevalue asc";
		$query = $this->_custom_query($sql);
        	return $query;
	}

	# GET ALL DEALER CODE
	function getDealers() {
		$this->load->model('mdl_rules');
        	$sql = "select partycode, partyname from hy_party where partytypeid = 1";
		$query = $this->_custom_query($sql);
        	return $query;
	}
	
	# ITEM RULES PAGE
	function hybrid() {
		$data['title'] = 'Hybrid Engine - Hybrid Management System';
        	$data['view_file'] = 'hybrid';
		$data['default_rule'] = $this->getDefaultRules()->result();

		// log audit trail
		$user = $_SESSION['username'];
		$this->fire_audit_log($user, '/'.$this->router->fetch_class().'/'.$this->router->fetch_method(), '-', 'VIEW');

        	$this->loadView($data);
	}
	# ADD ITEM RULE
	public function updateDefault() {
		$this->form_validation->set_rules('pct', 'pct', 'trim|required');
		$this->form_validation->set_rules('bal', 'bal', 'trim|required');
		if ($this->form_validation->run() === FALSE) {
            		$data['title'] = 'Hybrid Engine - Hybrid Management System';
            		$data['msg'] = '<div class="alert alert-warning"><i class="fa fa-bolt"></i> Error please fill in all the fields.</div>';
            		$data['view_file'] = 'hybrid';
			$data['default_rule'] = $this->getDefaultRules()->result();
            		$this->loadView($data);
        	} 
		else {	
			$data['updateResult'] = $this->updateDefaultRule()->result();

			$pct = $this->input->post('pct', TRUE);		
			$bal = $this->input->post('bal', TRUE);

			// log audit trail
			$user = $_SESSION['username'];
			$this->fire_audit_log($user, '/'.$this->router->fetch_class().'/'.$this->router->fetch_method(), 'Percent: '.$pct.' - MinBal: '.$bal, 'UPDATE-DEFAULT-RULE');
			
			$data['title'] = 'Hybrid Engine - Hybrid Management System';
            		$data['msg'] = '<div class="alert alert-success"><i class="fa fa-check"></i> Default rule was updated successfully.</div>';
            		$data['view_file'] = 'hybrid';
			$data['default_rule'] = $this->getDefaultRules()->result();
            		$this->loadView($data);
		}	
	}
	# GET HYBRID DEFAULT RULE
	function getDefaultRules() {
		$this->load->model('mdl_rules');
		$sql = "select * from hy_hpin_rules";
		$query = $this->_custom_query($sql);
        	return $query;
	}
	# UPDATE HYBRID DEFAULT RULE
	function updateDefaultRule() {
		$this->load->model('mdl_rules');
		$pct = $this->input->post('pct', TRUE);		
		$bal = $this->input->post('bal', TRUE);

		$sql = "select PKG_HY_RULES_ENGINE.FN_DEFAULT_RULE(
				$pct, 
				$bal) as result from dual";
		$query = $this->_custom_query($sql);
        	return $query;
	}
	# ADD ITEM RULE
	function addHybridRules() {
		$this->load->model('mdl_rules');
		$itemtype = $this->input->post('itemtype', TRUE);		
		$maxdailytxn = $this->input->post('maxdailytxn', TRUE);
		$maxmonthlytxn = $this->input->post('maxmonthlytxn', TRUE);
		$maxdailyvolume = $this->input->post('maxdailyvolume', TRUE);
		$maxmonthlyvolume = $this->input->post('maxmonthlyvolume', TRUE);
		$maxtxnvolume = $this->input->post('maxtxnvolume', TRUE);
		$mintxnvolume = $this->input->post('mintxnvolume', TRUE);
		
		$sql = "select PKG_HY_RULES_ENGINE.FN_ADD_HPIN_RULE(
				'".$_SESSION['username']."', 
				'".$itemtype."', 
				$maxdailytxn, 
				$maxmonthlytxn,
				$maxdailyvolume,
				$maxmonthlyvolume,
				$maxtxnvolume,
				$mintxnvolume) as result from dual";
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
        $this->load->model('mdl_rules');
        $query = $this->mdl_rules->_custom_num_rows_query($mysql_query);
        return $query;
    }

    function _custom_query($mysql_query) {
        $this->load->model('mdl_rules');
        $query = $this->mdl_rules->_custom_query($mysql_query);
        return $query;
    }

	function fire_audit_log($user, $url, $auditdata, $action) {
		$sql = "insert into hy_audit_trail (AUDITID, SESSIONID, IPADDRESS, USERID, URLACCESSED, TIMEACCESSED, AUDITDATA, AUDITACTION)
		values(AUDIT_TRAIL_ID_SEQ.nextval, '".session_id()."', '".$_SERVER["REMOTE_ADDR"]."', '".$user."', '".$url."', sysdate, '".$auditdata."', '".$action."')";
		$this->_custom_query($sql);
	}

}

?>