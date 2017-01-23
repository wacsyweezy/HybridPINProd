<?php
class ajax {
	# Database Connection
	public static function db_connect() {
		//$con=oci_connect('SIMPLEX', 'simplex321', '10.161.11.37:1523/epindev');
		$con=oci_connect('hybrid', 'hybrid123', '(DESCRIPTION=(ADDRESS = (PROTOCOL = TCP)(HOST = 10.161.11.56)(PORT = 1522)) (CONNECT_DATA =(SERVER = DEDICATED)(SERVICE_NAME = HPDB)))');
		return $con;
	}
	# Add to Cart
	public static function add_to_cart($item, $qty, $price) {
		self::db_connect();
		@session_start();
		$sql = "select PKG_HY_ORDER_MANAGER.FN_ADD_ITEM('$item', $price, $qty, '".$_SESSION['usercode']."') as result from dual";
		$s = oci_parse(ajax::db_connect(), $sql);
		oci_execute($s);
		$result = oci_fetch_assoc($s);
		$res =  $result['RESULT'];
		if($res==1) {
			@session_start();
			$sql = "select * from hy_order_cart where addedby = '".$_SESSION['usercode']."' and trunc(dateadded) = trunc(sysdate) order by item_unit_price asc";
			$s = oci_parse(ajax::db_connect(), $sql);
			oci_execute($s);
			echo '<table class="table">
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
			while($result = oci_fetch_assoc($s)) {
				echo '<tr>
					<td>'.$count.'</td>
					<td>'.$result['ITEM_TYPE'].'</td>
					<td>'.$result['ITEM_QTY'].'</td>
					<td>'.$result['ITEM_UNIT_PRICE'].'</td>
					<td>'.number_format($result['ITEM_UNIT_PRICE']*$result['ITEM_QTY']).'
						<a href="javascript:;" title="Remove item" id="'.$result['ITEM_TYPE'].'" class="pull-right text-danger remove-item">
							<i class="fa fa-times"></i>
						</a>
					</td>
				</tr>';
				$count++;
				$total += ($result['ITEM_UNIT_PRICE']*$result['ITEM_QTY']);
				//$vat += $result['ITEM_UNIT_PRICE'] * ;
			}
			echo "
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
		}
		oci_close(@ajax::db_connect());	
	}
	# Remove From Cart
	public static function remove_item($item) {
		self::db_connect();
		@session_start();
		$sql = "select PKG_HY_ORDER_MANAGER.FN_REMOVE_ITEM('$item', '".$_SESSION['usercode']."') as result from dual";
		$s = oci_parse(ajax::db_connect(), $sql);
		oci_execute($s);
		$result = oci_fetch_assoc($s);
		$res =  $result['RESULT'];
		if($res==1) {
			@session_start();
			$sql = "select * from hy_order_cart where addedby = '".$_SESSION['usercode']."' and trunc(dateadded) = trunc(sysdate) order by item_unit_price asc";
			$s = oci_parse(ajax::db_connect(), $sql);
			oci_execute($s);
			echo '<table class="table">
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
			while($result = oci_fetch_assoc($s)) {
				echo '<tr>
					<td>'.$count.'</td>
					<td>'.$result['ITEM_TYPE'].'</td>
					<td>'.$result['ITEM_QTY'].'</td>
					<td>'.$result['ITEM_UNIT_PRICE'].'</td>
					<td>'.number_format($result['ITEM_UNIT_PRICE']*$result['ITEM_QTY']).'
						<a href="javascript:;" title="Remove item" id="'.$result['ITEM_TYPE'].'" class="pull-right text-danger remove-item">
							<i class="fa fa-times"></i>
						</a>
					</td>
				</tr>';
				$count++;
				$total += ($result['ITEM_UNIT_PRICE']*$result['ITEM_QTY']);
				//$vat += $result['ITEM_UNIT_PRICE'] * ;
			}
			echo "
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
		}
		oci_close(@ajax::db_connect());	
	}
	# Remove From Cart
	public static function remove_all_item() {
		self::db_connect();
		@session_start();
		$sql = "select PKG_HY_ORDER_MANAGER.FN_EMPTY_CART('".$_SESSION['usercode']."') as result from dual";
		$s = oci_parse(ajax::db_connect(), $sql);
		oci_execute($s);
		$result = oci_fetch_assoc($s);
		$res =  $result['RESULT'];
		echo $res;
		oci_close(@ajax::db_connect());	
	}
	# Place Order
	public static function place_order() {
		self::db_connect();
		@session_start();
		$sql = "select PKG_HY_ORDER_MANAGER.FN_PLACE_ORDER('".$_SESSION['usercode']."') as result from dual";
		$s = oci_parse(ajax::db_connect(), $sql);
		oci_execute($s);
		$result = oci_fetch_assoc($s);
		$res =  $result['RESULT'];
		if($res>0) {
			print 'vieworder/?order='.$res;
		}
		else {
			print $res;
		}
		oci_close(@ajax::db_connect());	
	}
}
?>