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
						<span>Order Details </span>
					</li>
				</ol>
			</div>
			<h2 class="font-light m-b-xs">
				Order Details
			</h2>
			<small>Order Details for Hybrid PIN</small>
		</div>
	</div>
</div>
<div class="content animate-panel">
	<div class="row">
		<div style="" class="col-lg-12">
			<?php
				if(sizeof($order>=1)) {
					foreach ($order as $detail) {
				?>
			<div class="hpanel ">
				<div class="panel-heading hbuilt">
					<span><?php print stripslashes($detail->PARTNER_CODE); ?></span>
					<span class="pull-right">Order No: <?php print stripslashes($detail->ORDER_NO); ?></span>
					<div class="clearfix"></div>
				</div>
				<div class="panel-body">
					<div class="row top_tiles" style="margin-bottom:30px;">
						<div class="col-md-2 col-sm-3 col-xs-6 tile">
							<span>Dealer Code</span>
							<h3 style="font-size:18px;"><?php print stripslashes($detail->PARTNER_CODE); ?></h3>
						</div>
						<div class="col-md-4 col-sm-3 col-xs-6 tile">
							<span>Order Date / Time </span>
							<h3 style="font-size:18px;"><?php print $detail->ORD_DATE; ?></h3>
						</div>
						<div class="col-md-4 col-sm-3 col-xs-6 tile">
							<span>Delivery Date / Time</span>
							<?php
								if(strlen($details['TIME_DELIVERED'])>4) {
									print '<h3 style="font-size:18px;">'.$detail->TIME_DELIVERED.'</h3>';
								}
								else {
									print '<h3 style="font-size:18px;">Not delivered</h3>';
								}
							?>
						</div>
						<div class="col-md-2 col-sm-3 col-xs-6 tile text-right">
							<span>Order Status</span>
							<h3 style="font-size:18px;"><?php print stripslashes($detail->ORDER_STATUS); ?></h3>
						</div>
					</div>
				</div>
				<?php 
					}
				?>
				<table class='table table-striped table-bordered'>
					<thead>
						<tr>
							<th>Line No</th>
							<th>Description</th>
							<th>Quantity</th>
							<th>Unit Price</th>
							<th>Amount</th>
						</tr>
					</thead>
					<tbody>
					<?php
						$total = 0;
						$vat = 0;
						foreach ($order_details as $details) {
				echo '	<tr>
							<td>'.$details->LINE_ITEM_NO.'</td>
							<td>'.$details->DESCRIPTION.'</td>
							<td>'.$details->QUANTITY.'</td>
							<td>'.$details->UNIT_PRICE.'</td>
							<td>'.number_format($details->AMOUNT).'</td>
						</tr>';
						$total += $details->AMOUNT;
						}
					?>
						<tr>
							<td colspan='3'></td>
							<td colspan='2' class='text-center'><strong>Summary</strong></td>
						</tr>
						<tr>
							<td colspan='3'></td>
							<td class='text-right'>Total</td>
							<td class='text-left'>NGN <?php echo number_format($total); ?></td>
						</tr>
					</tbody>
				</table>
			</div>
				<?php
				}
			?>
		</div>
	</div>
</div>