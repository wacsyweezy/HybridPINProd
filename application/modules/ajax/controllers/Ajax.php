<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Ajax extends MX_Controller {
    function __construct() {
        parent::__construct();
        $this->load->library('form_validation');
		parse_str($_SERVER['QUERY_STRING'], $_GET);
        //$this->load->library('MY_Log');
        //$this->load->library('log');
    }

    function index() {
		$this->load->view('request-handler');
    }
	
	function addItem() {
		$this->form_validation->set_rules('item', 'item', 'trim|required');
		$this->form_validation->set_rules('qty', 'qty', 'trim|required');
		$this->form_validation->set_rules('task', 'task', 'trim|required');
		if ($this->form_validation->run() === FALSE) {
			$data['output'] = "Invalid request";
			$this->load->view('request-handler', $data);
        } 
		else {

			// log audit trail
			$user = $_SESSION['username'];
			$item = $this->input->post('item', TRUE);
			$this->fire_audit_log($user, '/'.$this->router->fetch_class().'/'.$this->router->fetch_method(), 'Item - '.$item, 'ADD-CART-ITEM');

			$task = $this->input->post('task', TRUE);
			if($task == "add-to-cart") {
				$data['addResult'] = $this->addToCart()->result();
				//foreach ($data['addResult'] as $result) {
					//if($result->RESULT == 1) {
						$data['cartitem'] = $this->getCartItem()->result();
					//}
                    //else {
                       // $data['cartitem'] = $this->getCartItem()->result();
                    //}
			//	}
				$data['output'] = '
				<table class="table">
					<thead>
						<tr>
							<th>Line Item</th>
							<th>Item Type</th>
							<th>Qty</th>
							<th>Unit Price</th>
							<th>Total</th>
						</tr>
					</thead>
					<tbody>';
				$count = 1;
				$total = 0;
				$vat = 0;
				foreach ($data['cartitem'] as $cart) {
					$data['output'] .= '<tr>
							<td>'.$count.'</td>
							<td>'.$cart->ITEM_TYPE.'</td>
							<td>'.$cart->ITEM_QTY.'</td>
							<td>'.$cart->ITEM_UNIT_PRICE.'</td>
							<td>'.number_format($cart->ITEM_UNIT_PRICE*$cart->ITEM_QTY).'
								<a href="javascript:;" title="Remove item" id="'.$cart->ITEM_TYPE.'" class="pull-right text-danger remove-item">
									<i class="fa fa-times"></i>
								</a>
							</td>
						</tr>';
						$count++;
						$total += ($cart->ITEM_UNIT_PRICE*$cart->ITEM_QTY);
						//$vat += $result['ITEM_UNIT_PRICE'] * ;
				}	
				
				$data['output'] .= "
					</tbody>
				</table>
				<div class='hpanel' style='margin-bottom:0px; margin:-20px -20px -30px -20px;'>
					<div class='panel-body' style='padding:7px 10px; border-bottom:none; border-left:none; border-right:none;'>
						<div class='row'>
							<div class='col-xs-12'>
								<div class='pull-left'>
									<small class='stat-label'><strong>TOTAL AMOUNT SUMMARY</strong></small>
									<h2>&#8358;".number_format($total)." </h2>
								</div>
								<div class='stats-icon pull-right'>
									<i class='pe-7s-cash fa-5x'></i>
								</div>
							</div>
						</div>
					</div>
				</div>";
				
				//$data['output'] = $_SESSION['usercode']." It handshaked";
				$this->load->view('request-handler', $data);
			}
		}
    }
	
	# ADD ITEM TO CART
	function addToCart() {
		$this->load->model('mdl_ajax');		
		$item = $this->input->post('item', TRUE);
		$qty = $this->input->post('qty', TRUE);
		$sql = "select PKG_HY_ORDER_MANAGER.FN_ADD_ITEM('$item', $qty, '".$_SESSION['usercode']."') as result from dual";
		$query = $this->_custom_query($sql);
        return $query;
	}
	
	function getCartItem() {
        $this->_custom_query("commit");
		$sql = "select * from hy_order_cart where addedby = '".$_SESSION['usercode']."' and trunc(dateadded) = trunc(sysdate) order by item_unit_price asc";
		$query = $this->_custom_query($sql);
		return $query;
	}
    
    function deleteItem() {
		$this->form_validation->set_rules('item', 'item', 'trim|required');
		if ($this->form_validation->run() === FALSE) {
			$data['output'] = "Invalid request";
			$this->load->view('request-handler', $data);
        } 
		else {
			$task = $this->input->post('task', TRUE);
			if($task == "remove-item") {

				// log audit trail
				$user = $_SESSION['username'];
				$item = $this->input->post('item', TRUE);
				$this->fire_audit_log($user, '/'.$this->router->fetch_class().'/'.$this->router->fetch_method(), 'Item - '.$item, 'DELETE-CART-ITEM');

				$data['addResult'] = $this->removeFromCart()->result();
				foreach ($data['addResult'] as $result) {
					if($result->RESULT == 1) {
						$data['cartitem'] = $this->getCartItem()->result();
					}
                    else {
                        $data['cartitem'] = $this->getCartItem()->result();
                    }
				}
				$data['output'] = '
				<table class="table">
					<thead>
						<tr>
							<th>Line Item</th>
							<th>Item Type</th>
							<th>Qty</th>
							<th>Unit Price</th>
							<th>Total</th>
						</tr>
					</thead>
					<tbody>';
				$count = 1;
				$total = 0;
				$vat = 0;
				foreach ($data['cartitem'] as $cart) {
					$data['output'] .= '<tr>
							<td>'.$count.'</td>
							<td>'.$cart->ITEM_TYPE.'</td>
							<td>'.$cart->ITEM_QTY.'</td>
							<td>'.$cart->ITEM_UNIT_PRICE.'</td>
							<td>'.number_format($cart->ITEM_UNIT_PRICE*$cart->ITEM_QTY).'
								<a href="javascript:;" title="Remove item" id="'.$cart->ITEM_TYPE.'" class="pull-right text-danger remove-item">
									<i class="fa fa-times"></i>
								</a>
							</td>
						</tr>';
						$count++;
						$total += ($cart->ITEM_UNIT_PRICE*$cart->ITEM_QTY);
						//$vat += $result['ITEM_UNIT_PRICE'] * ;
				}	
				
				$data['output'] .= "
					</tbody>
				</table>
				<div class='hpanel' style='margin-bottom:0px; margin:-20px -20px -30px -20px;'>
					<div class='panel-body' style='padding:7px 10px; border-bottom:none; border-left:none; border-right:none;'>
						<div class='row'>
							<div class='col-xs-12'>
								<div class='pull-left'>
									<small class='stat-label'><strong>TOTAL AMOUNT SUMMARY</strong></small>
									<h2>&#8358;".number_format($total)." </h2>
								</div>
								<div class='stats-icon pull-right'>
									<i class='pe-7s-cash fa-5x'></i>
								</div>
							</div>
						</div>
					</div>
				</div>";				
				//$data['output'] = $_SESSION['usercode']." It handshaked";
				$this->load->view('request-handler', $data);
			}
		}
    }
	
	# REMOVE ITEM FROM CART
	function removeFromCart() {
		$this->load->model('mdl_ajax');		
		$item = $this->input->post('item', TRUE);
        $sql = "select PKG_HY_ORDER_MANAGER.FN_REMOVE_ITEM('$item', '".$_SESSION['usercode']."') as result from dual";
		$query = $this->_custom_query($sql);
        return $query;
	}
    # EMPTY CART
    function emptyItem() {
    	$data['addResult'] = $this->emptyCart()->result();
	
	// log audit trail
	$user = $_SESSION['username'];
	$this->fire_audit_log($user, '/'.$this->router->fetch_class().'/'.$this->router->fetch_method(), 'All Item', 'EMPTY-CART');

    	$this->load->view('request-handler', $data);
    }
    
    # EMPTY CART TRIGGER
	function emptyCart() {
		$this->load->model('mdl_ajax');		
        $sql = "select PKG_HY_ORDER_MANAGER.FN_EMPTY_CART('".$_SESSION['usercode']."') as result from dual";
		$query = $this->_custom_query($sql);
        return $query;
	}
    # PLACE ORDER
    function placeOrder() {
	/* Begine Rule Checking and EVC Modify EVC Inventory and Query Balance */
	$data['getEVC'] = $this->getEVC()->result();
	foreach($data['getEVC'] as $evc) {
		$account = $evc->EVC_ACCT_CODE;
	}

	$balance = $this->getEVCAccount($account);

	$data['getTotal'] = $this->getTotal()->result();
	foreach($data['getTotal'] as $get) {
		$total = $get->TOTAL;
	}

	$data['defaultRule'] = $this->getEVCDefaultRule()->result();
	foreach($data['defaultRule'] as $default) {
		$max_percent = $default->MAX_PCT_TOTAL;
		$min_balance = $default->MIN_EVC_BALANCE;
	}

	$spendable = (($balance/100)/100)*$max_percent;

	$leftBalance = round(($balance/100) - ($total/100));

	# CHECK MINIMUM BALANCE RULE 
	if($min_balance > ($balance/100)) {
		$err = base64_encode("Your E-Wallet balance is below the required minimum E-Wallet balance");
		echo "<script>document.location.href='../order/engine/?info=$err';</script>";
		die();
	} 

	# CHECK TOTAL AGAINST BALANCE 
	if(($total/100) > ($balance/100)) {
		$err = base64_encode('Your E-Wallet balance is not sufficient to complete this order.');
		echo "<script>document.location.href='../order/engine/?info=$err';</script>";
		die();	
	} 

	# CHECK MAXIMUM PERCETAGE RULE
	if(($total/100) > $spendable) {
		$err = base64_encode('Your order is above the convertable E-Wallet percentage of your balance');
		echo "<script>document.location.href='../order/engine/?info=$err';</script>";
		die();
	}

	# CHECK BALANCE RULE AFTER DEDUCTING
	if($leftBalance < $min_balance) {
		$err = base64_encode('Order cannot be processed. Your balance after processing the order is below the required minimum E-Wallet balance');
		echo "<script>document.location.href='../order/engine/?info=$err';</script>";
		die();
	}

	$dealerRule = $this->getDealerRule()->result();

	$itemRule = $this->getItemRule()->result();

	foreach($itemRule as $rule) {
		$stock = $rule->ITEMCODE;
		$max_daily_count = $rule->MAX_DAILY_TXN_COUNT;
		$max_monthly_count = $rule->MAX_MONTHLY_TXN_COUNT;

		$max_daily_qty = $rule->MAX_DAILY_VOLUME;
		$max_monthly_qty = $rule->MAX_MONTHLY_VOLUME;

		$min_qty = $rule->MIN_TXN_VOLUME;
		$max_qty = $rule->MAX_TXN_VOLUME;

		$this->enforceItemRule($stock, $max_daily_count, $max_monthly_count, $max_daily_qty, $max_monthly_qty, $min_qty, $max_qty);
	}

	foreach($dealerRule as $rule) {
		$stock = $rule->ITEMCODE;
		$max_daily_count = $rule->MAX_DAILY_TXN_COUNT;
		$max_monthly_count = $rule->MAX_MONTHLY_TXN_COUNT;

		$max_daily_qty = $rule->MAX_DAILY_VOLUME;
		$max_monthly_qty = $rule->MAX_MONTHLY_VOLUME;

		$min_qty = $rule->MIN_TXN_VOLUME;
		$max_qty = $rule->MAX_TXN_VOLUME;

		$this->enforceDealerRule($stock, $max_daily_count, $max_monthly_count, $max_daily_qty, $max_monthly_qty, $min_qty, $max_qty);
	}

	// CHECK IF EVC ACCOUNT WAS DEBITED SUCCESSFULLY!
	if($this->modifyEVCAccount($account, $total)) {
        	$data['addResult'] = $this->saveOrder()->result();
        	foreach ($data['addResult'] as $result) {
            		if($result->RESULT>0) {
				$order_no = $result->RESULT;
				
				$user = $_SESSION['username'];
				$this->fire_audit_log($user, '/'.$this->router->fetch_class().'/'.$this->router->fetch_method(), 'Order - '.$order_no, 'PLAN-ORDER');

				echo "<script>document.location.href='../order/vieworder/?order=$order_no';</script>";
				die();
            		}
            		else {
                		$err = base64_encode('Error, System busy. Please try after some time');
				echo "<script>document.location.href='../order/engine/?info=$err';</script>";
				die();
            		}
        	}
    		$this->load->view('request-handler', $data);
	}
	else {
		$err = base64_encode('Cannot connect to EVC at the moment, please try after some time');
		echo "<script>document.location.href='../order/engine/?info=$err';</script>";
		die();
		$this->load->view('request-handler', $data);
	}
    }
	# GET EVC Account
	function getEVC() {
		$sql = "select EVC_ACCT_CODE from hy_party where PARTYCODE = '".$_SESSION['usercode']."'";
		$query = $this->_custom_query($sql);
		return $query;
	}
	# Query EVC Balance REST
	function getEVCAccount($account) {
		$url = "http://10.158.6.80:8186/HybridLite/QueryDealerBalance?USERNAME=hpin&PASSWORD=Emts1234&USER_TYPE=0&USER_ACCOUNT=$account";
		$open=curl_init($url);
		curl_setopt($open, CURLOPT_URL, $url);
		curl_setopt($open, CURLOPT_RETURNTRANSFER, 1);
		$data=curl_exec($open);
		$split = explode('|', $data);
		$balance = $split[2];
		return $balance;
	}
	# Modify EVC Balance REST
	function modifyEVCAccount($account, $debit) {
		$url = "http://10.158.6.80:8186/HybridLite/ModifyEVCInventory?USERNAME=hpin&PASSWORD=Emts1234&MSISDN=$account&ACCOUNT=-$debit";
		$open=curl_init($url);
		curl_setopt($open, CURLOPT_URL, $url);
		curl_setopt($open, CURLOPT_RETURNTRANSFER, 1);
		$data=curl_exec($open);
		$split = explode('|', $data);
		$response = $split[0];
		return $response;
	}

	# GET CART ITEM TOTAL
	function getTotal() {
		$sql = "select sum(item_unit_price*item_qty)*100 total from hy_order_cart where addedby = '".$_SESSION['usercode']."'";
		$query = $this->_custom_query($sql);
		return $query;
	}

	# GET DEFAULT RULE
	function getEVCDefaultRule() {
		$sql = "select MAX_PCT_TOTAL, MIN_EVC_BALANCE from hy_hpin_rules where ruleid=1";
		$query = $this->_custom_query($sql);
		return $query;
	}

	# GET DEALER RULE
	function getDealerRule() {
		$sql = "select dealercode, itemcode, max_daily_txn_count, max_monthly_txn_count, MAX_DAILY_VOLUME, MAX_MONTHLY_VOLUME, MIN_TXN_VOLUME, MAX_TXN_VOLUME from hy_dealerrules where dealercode = '".$_SESSION['usercode']."' and itemcode in (select ITEM_TYPE from hy_order_cart where addedby = '".$_SESSION['usercode']."')";
		$query = $this->_custom_query($sql);
		return $query;
	}

	# GET ITEM RULE
	function getItemRule() {
		$sql = "select itemcode, max_daily_txn_count, max_monthly_txn_count, MAX_DAILY_VOLUME, MAX_MONTHLY_VOLUME, MIN_TXN_VOLUME, MAX_TXN_VOLUME from hy_itemrules where itemcode in (select ITEM_TYPE from hy_order_cart where addedby = '".$_SESSION['usercode']."')";
		$query = $this->_custom_query($sql);
		return $query;
	}

	# GET DEALER TRANSACTION BY ITEM COUNT FOR CURRENT MONTH
	function getDealerMonthly($item) {
		$dealer = $_SESSION['usercode'];
		$sql= "select count(1) item_count, b.item_code from hy_orders a, hy_order_details b where a.PARTNER_CODE='$dealer' and a.ORDER_STATUS not in ('CANCELLED') and b.order_no = a.order_no and b.item_code = '$item' and to_char(a.ORDER_DATE, 'YYYYMM') = to_char(sysdate, 'YYYYMM') group by b.item_code";
		$query = $this->_custom_query($sql);
		return $query;
	}

	# GET DEALER TRANSACTION BY ITEM COUNT FOR SYSDATE
	function getDealerDaily($item) {
		$dealer = $_SESSION['usercode'];
		$sql= "select count(1) item_count, b.item_code from hy_orders a, hy_order_details b where a.PARTNER_CODE='$dealer' and a.ORDER_STATUS not in ('CANCELLED') and b.order_no = a.order_no and b.item_code = '$item' and to_char(a.ORDER_DATE, 'YYYYMMDD') = to_char(sysdate, 'YYYYMMDD') group by b.item_code";
		$query = $this->_custom_query($sql);
		return $query;
	}

	# GET DEALER TOTAL QUANTITIES BY STOCK ITEM FOR CURRENT MONTH
	function getDealerMonthlyVol($item) {
		$dealer = $_SESSION['usercode'];
		$sql= "select sum(b.quantity) item_count from hy_orders a, hy_order_details b where a.PARTNER_CODE='$dealer' and a.ORDER_STATUS not in ('CANCELLED') and b.order_no = a.order_no and b.item_code = '$item' and to_char(a.ORDER_DATE, 'YYYYMM') = to_char(sysdate, 'YYYYMM') group by b.item_code";
		$query = $this->_custom_query($sql);
		return $query;
	}

	# GET DEALER TOTAL QUANTITIES BY STOCK ITEM FOR CURRENT SYSDATE
	function getDealerDailyVol($item) {
		$dealer = $_SESSION['usercode'];
		$sql= "select sum(b.quantity) item_count from hy_orders a, hy_order_details b where a.PARTNER_CODE='$dealer' and a.ORDER_STATUS not in ('CANCELLED') and b.order_no = a.order_no and b.item_code = '$item' and to_char(a.ORDER_DATE, 'YYYYMMDD') = to_char(sysdate, 'YYYYMMDD') group by b.item_code";
		$query = $this->_custom_query($sql);
		return $query;
	}
	
	# GET STOCK ITEM QUNTITY IN CART
	function getCartItemQty($item) {
		$dealer = $_SESSION['usercode'];
		$sql="select item_qty from hy_order_cart where addedby = '$dealer' and item_type = '$item'";
		$query = $this->_custom_query($sql);
		return $query;
	}

    # PLACE ORDER TRIGGER
    function saveOrder() {
        $this->load->model('mdl_ajax');		
        $sql = "select PKG_HY_ORDER_MANAGER.FN_PLACE_ORDER('".$_SESSION['usercode']."') as result from dual";
		$query = $this->_custom_query($sql);
        return $query;
    }

    function loadView($data) {
        $data['module'] = 'ajax';
        echo Modules::run('templ/templContent', $data);
    }

    function _custom_num_rows_query($mysql_query) {
        $this->load->model('mdl_ajax');
        $query = $this->mdl_ajax->_custom_num_rows_query($mysql_query);
        return $query;
    }

    function _custom_query($mysql_query) {
        $this->load->model('mdl_ajax');
        $query = $this->mdl_ajax->_custom_query($mysql_query);
        return $query;
    }


	# DEALER RULE ENFOREMENT
	function enforceDealerRule($stock, $max_daily_count, $max_monthly_count, $max_daily_qty, $max_monthly_qty, $min_qty, $max_qty) {
		$dealer = $_SESSION['usercode'];
		$current_monthly = $this->getDealerMonthly($stock)->result();
		$current_daily = $this->getDealerDaily($stock)->result();
		$cartStockQty = $this->getCartItemQty($stock)->result();
		$monthVol = $this->getDealerMonthlyVol($stock)->result();
		$dayVol = $this->getDealerDailyVol($stock)->result();

		foreach($cartStockQty as $cartItem) {
			$cartQty = $cartItem->ITEM_QTY;
		}
		
		foreach($current_monthly as $month) {
			$monthCount = $month->ITEM_COUNT;
		}

		foreach($current_daily as $day) {
			$dayCount = $day->ITEM_COUNT;
		}

		foreach($monthVol as $mVol) {
			$monthlyVol = $mVol->ITEM_COUNT;
		}

		foreach($dayVol as $dVol) {
			$dailyVol = $dVol->ITEM_COUNT;
		}

		if($cartQty < $min_qty) {
			$err = base64_encode("The selected quantity for $stock stock item is below the minimum quantity you can purchase");
			echo "<script>document.location.href='../order/engine/?info=$err';</script>";
			die();
		}

		if($cartQty > $max_qty) {
			$err = base64_encode("The selected quantity for $stock stock item is above the maximum quantity you can purchase");;
			echo "<script>document.location.href='../order/engine/?info=$err';</script>";
			die();
		}

		if($dayCount >= $max_daily_count) {
			$err = base64_encode("You have reached your maximum daily transaction for $stock stock item");
			echo "<script>document.location.href='../order/engine/?info=$err';</script>";
			die();
		}

		if($monthCount >= $max_monthly_count) {
			$err = base64_encode("You have reached your maximum monthly transaction for $stock stock item");
			echo "<script>document.location.href='../order/engine/?info=$err';</script>";
			die();
		}

		if($dailyVol >= $max_daily_qty) {
			$err = base64_encode("You have reached your maximum daily quantities for $stock stock item");
			echo "<script>document.location.href='../order/engine/?info=$err';</script>";
			die();
		}

		if($monthlyVol >= $max_monthly_qty) {
			$err = base64_encode("You have reached your maximum monthly quantities for $stock stock item");
			echo "<script>document.location.href='../order/engine/?info=$err';</script>";
			die();
		}
	} 

	# ITEM RULE ENFOREMENT
	function enforceItemRule($stock, $max_daily_count, $max_monthly_count, $max_daily_qty, $max_monthly_qty, $min_qty, $max_qty) {
		$current_monthly = $this->getDealerMonthly($stock)->result();
		$current_daily = $this->getDealerDaily($stock)->result();
		$cartStockQty = $this->getCartItemQty($stock)->result();
		$monthVol = $this->getDealerMonthlyVol($stock)->result();
		$dayVol = $this->getDealerDailyVol($stock)->result();

		foreach($cartStockQty as $cartItem) {
			$cartQty = $cartItem->ITEM_QTY;
		}
		
		foreach($current_monthly as $month) {
			$monthCount = $month->ITEM_COUNT;
		}

		foreach($current_daily as $day) {
			$dayCount = $day->ITEM_COUNT;
		}

		foreach($monthVol as $mVol) {
			$monthlyVol = $mVol->ITEM_COUNT;
		}

		foreach($dayVol as $dVol) {
			$dailyVol = $dVol->ITEM_COUNT;
		}

		if($cartQty < $min_qty) {
			$err = base64_encode("The selected quantity for $stock stock item is below the minimum quantity you can purchase");
			echo "<script>document.location.href='../order/engine/?info=$err';</script>";
			die();
		}

		if($cartQty > $max_qty) {
			$err = base64_encode("The selected quantity for $stock stock item is above the maximum quantity you can purchase");;
			echo "<script>document.location.href='../order/engine/?info=$err';</script>";
			die();
		}

		if($dayCount >= $max_daily_count) {
			$err = base64_encode("You have reached the maximum daily transaction for $stock stock item");
			echo "<script>document.location.href='../order/engine/?info=$err';</script>";
			die();
		}

		if($monthCount >= $max_monthly_count) {
			$err = base64_encode("You have reached the maximum monthly transaction for $stock stock item");
			echo "<script>document.location.href='../order/engine/?info=$err';</script>";
			die();
		}

		if($dailyVol >= $max_daily_qty) {
			$err = base64_encode("You have reached the maximum daily quantities for $stock stock item");
			echo "<script>document.location.href='../order/engine/?info=$err';</script>";
			die();
		}

		if($monthlyVol >= $max_monthly_qty) {
			$err = base64_encode("You have reached the maximum monthly quantities for $stock stock item");
			echo "<script>document.location.href='../order/engine/?info=$err';</script>";
			die();
		}
	}

	function fire_audit_log($user, $url, $auditdata, $action) {
		$sql = "insert into hy_audit_trail (AUDITID, SESSIONID, IPADDRESS, USERID, URLACCESSED, TIMEACCESSED, AUDITDATA, AUDITACTION)
		values(AUDIT_TRAIL_ID_SEQ.nextval, '".session_id()."', '".$_SERVER["REMOTE_ADDR"]."', '".$user."', '".$url."', sysdate, '".$auditdata."', '".$action."')";
		$this->_custom_query($sql);
    	}
}

?>