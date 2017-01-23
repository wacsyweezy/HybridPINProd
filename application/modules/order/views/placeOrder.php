<link rel="stylesheet" href="<?php print base_url()?>assets/vendor/sweetalert/lib/sweet-alert.css" />
<link rel="stylesheet" href="<?php print base_url()?>assets/vendor/select2-3.5.2/select2.css" />
<link rel="stylesheet" href="<?php print base_url()?>assets/vendor/select2-bootstrap/select2-bootstrap.css" />

<div class="small-header transition animated fadeIn">
	<div class="hpanel">
		<div class="panel-body">
			<div id="hbreadcrumb" class="pull-right">
				<ol class="hbreadcrumb breadcrumb">
					<li><a href="<?php echo base_url(); ?>">Dashboard</a></li>
					<li>
						<span>Order Management</span>
					</li>
					<li class="active">
						<span>Order Creation</span>
					</li>
				</ol>
			</div>
			<h2 class="font-light m-b-xs">
				Create Order
			</h2>
			<small>Order for Hybrid PIN</small>
		</div>
	</div>
</div>
<div class="content animate-panel">
	<div class="row">
<?php
	if(isset($_GET['info'])) {
		$info = $_GET['info'];
		echo '<div class="col-lg-12">
			<div class="alert alert-info"><i class="fa fa-warning"></i> '.base64_decode($info).'</div>
		</div>';
	}
?>
		<div class="">

		</div>
		<div style="" class="col-lg-5">
			<div class="hpanel ">
				<div class="panel-heading hbuilt">
					Bulk Item Selection
				</div>
				<div class="panel-body">
					<p><strong>Stock Item</strong></p>
					<select class="js-source-states" style="width: 100%">
						<optgroup label="Hybrid PIN">
							<option value="null">Hybrid PIN Type</option>
							<?php
								if(isset($stocks) && count($stocks)>=1) {
									foreach($stocks as $stock) {
										echo '<option value="'.$stock->STOCK_ID.'">'.$stock->STOCK_ID.' - '.$stock->DESCRIPTION.'</option>';
									}
								}
							?>
						</optgroup>
					</select>
					<br /><br />
					<div class="qty-action hidden">
						<p><strong>Quantity</strong></p>
						<input type="text" class="form-control" id="qty" name="qty" onkeyup="this.value=this.value.replace(/[^0-9]/g,'');"/>
						<br />
						<button class="btn btn-success add-to-cart"><i class="fa fa-plus"></i>  Add Item</button>
					</div>
				</div>
			</div>
		</div>
		<div class="col-lg-7 hidden cart-manager" id="cart">
			<div class="hpanel ">
				<div class="panel-heading hbuilt">
					Order Summary
				</div>
				<div class="panel-body order-cart">
					<?php
						if(sizeof($cart_item)>=1) {
							?>
								<script>
									var cart = document.getElementById("cart").classList;
									cart.remove("hidden");;
								</script>
							<?php
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
							foreach ($cart_item as $item) {
								echo '<tr>
									<td>'.$count.'</td>
									<td>'.$item->ITEM_TYPE.'</td>
									<td>'.$item->ITEM_QTY.'</td>
									<td>'.$item->ITEM_UNIT_PRICE.'</td>
									<td>'.number_format($item->ITEM_UNIT_PRICE*$item->ITEM_QTY).'
										<a href="javascript:;" title="Remove item" id="'.$item->ITEM_TYPE.'" class="pull-right text-danger remove-item">
											<i class="fa fa-times"></i>
										</a>
									</td>
								</tr>';
								$count++;
								$total += $item->ITEM_UNIT_PRICE*$item->ITEM_QTY;
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
					?>
				</div>
				<div class='panel-footer'>
					<button class='btn btn-success btn-sm font-uppercase place-order'><i class='fa fa-cogs'></i> Process Order</button>
					<button class='btn btn-danger btn-sm font-uppercase empty-cart pull-right'><i class='fa fa-times'></i> Remove all</button>
				</div>
			</div>
		</div>
	</div>
</div>