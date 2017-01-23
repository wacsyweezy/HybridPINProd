<link rel="stylesheet" href="<?php print base_url(); ?>assets/vendor/c3/c3.min.css" />
<div class="content">
	<div class="row">
        <div class="col-lg-12 text-center m-t-md">
            <h2>
                Welcome <?php print $_SESSION['displayname']; ?>
            </h2>
            <p>
                Hybrid Management System Dashboard
            </p>
        </div>
    </div>
</div>
<div class="content animate-panel">
	<div class="row">
		<div class="col-md-7">
			<div class="col-lg-4">
				<div class="hpanel stats">
					<div class="panel-body">
						<div class="stats-title pull-left">
							<h4>Trade Code</h4>
						</div>

						<div class="m-t-xl">
							<h1 class="text-success" style="word-wrap: break-word; font-size:25px;"><?php echo $_SESSION['usercode']; ?></h1>
							<span class="font-bold no-margins">
								DEALER CODE
							</span>
						</div>
					</div>
				</div>
			</div>
			<div class="col-lg-8">
				<div class="hpanel stats">
					<div class="panel-body">
						<div class="stats-title pull-left">
							<h4>Customer</h4>
						</div>
						<div class="stats-icon pull-right">
							<i class="pe-7s-monitor fa-4x"></i>
						</div>
						<div class="m-t-xl">
							<h1 class="text-success" style="word-wrap: break-word; font-size:25px;"><?php echo $customer_name; ?></h1>
							<span class="font-bold no-margins">
								Welcome back
							</span>
						</div>
					</div>
				</div>
			</div>
			<div class="clearfix"></div>
			<div class="col-lg-8">
				<div class="hpanel stats">
					<div class="panel-body">
						<div class="stats-title pull-left">
							<h4>e-Purse Balance</h4>
						</div>
						<div class="stats-icon pull-right">
							<i class="pe-7s-phone fa-4x"></i>
						</div>
						<div class="m-t-xl">
							<h1 class="text-success" style="word-wrap: break-word; font-size:25px;">
								<?php echo $evcBalance; ?>
							</h1>
							<span class="font-bold no-margins">
								NGN (Naira) <span class="pull-right"><?php echo $evcNumber; ?></span>
								<div class="clearfix"></div>
							</span>
						</div>
					</div>
				</div>
			</div>
			<div class="col-lg-4">
				<a href="<?php echo base_url(); ?>order/myOrders">
				<div class="hpanel stats">
					<div class="panel-body">
						<div class="stats-title pull-left">
							<h4>Orders</h4>
						</div>
						<div class="stats-icon pull-right">
							<i class="pe-7s-shopbag fa-4x"></i>
						</div>
						<div class="m-t-xl">
							<h1 class="text-success" style="word-wrap: break-word; font-size:25px;"><?php echo $orders_count; ?></h1>
							<span class="font-bold no-margins">
								My H-PIN Orders
							</span>
						</div>
					</div>
				</div>
				</a>
			</div>
		</div>
		<div class="col-md-5">
			<div class="col-md-12">
				<div class="hpanel">
					<div class="panel-heading hbuilt">
						Orders Snapshot
					</div>
					<?php
					if(isset($last_five_order) && count($last_five_order)>=1) {
						echo '	<div class="panel-body" style="padding:0px;">
									<div class="table table-responsive" style="margin-bottom:10px;">
										<table class="table table-responsive table-hover" style="margin-bottom:0px; border:none;">
											<thead>
												<tr>
													<th>S/N</th>
													<th>Order No</th>
													<th>Order Date</th>
													<th>Total EVC</th>
													<th class="text-right">Status</td>
												</tr>
											</thead>
											<tbody>';
						$sn = 1;
						foreach($last_five_order as $order) {
							echo 	'			<tr>
													<td>'.$sn.'</td>
													<td><a href="'.base_url().'order/vieworder/?order='.$order->ORDER_NO.'">'.$order->ORDER_NO.'</a></td>
													<td>'.$order->ORDER_DATE.'</td>
													<td>'.number_format($order->TOTAL).'</td>
													<td class="text-right">';
													if($order->ORDER_STATUS == 'Planned') {
														echo "<span class='label label-default'>";
													}
													if($order->ORDER_STATUS == 'CANCELLED') {
														echo "<span class='label label-danger'>";
													}
													if($order->ORDER_STATUS == 'BOOKED') {
														echo "<span class='label label-info'>";
													}
													if($order->ORDER_STATUS == 'Delivered') {
														echo "<span class='label label-success'>";
													}
													echo $order->ORDER_STATUS.'</span>
													</td>
												</tr>';
							$sn++;
						}
						echo '
											
											</tbody>

										</table>
									</div>
								</div>';
					}
					else {
						
					}
				?>
					<div class="panel-footer text-center">
						Last 5 Hybrid Orders Created
					</div>
				</div>
			</div>
			<div class="clearfix"></div>
		</div>
	</div>
	<!-- <div class="">	
		<div class="col-lg-12">
			<div class="hpanel">
				<div class="panel-body" style="padding:10px;">
					<div>
						<div id="lineOptions"></div>
						<?php /*
							$item_name = array();
							$dates = "";
							$stock = "";
							$i = 1;
							$stock_item = "";
							foreach($stocks as $item) {
								array_push($item_name, $item->STOCK_ID);
								$stock_item .=str_replace(' ', '_', $item->STOCK_ID)."|";
								$data =  "'$item->STOCK_ID'";
								$qty_array = array();
								print '<input type="hidden" id="stock_items" class="'.str_replace(' ', '_', $item->STOCK_ID).'"';
								foreach(${'item'.$i} as $all) {
									if($item->STOCK_ID==$all->ITEM) {
										array_push($qty_array, $all->QUANTITY);
									}
								}
								$concat = implode(',', $qty_array);
								$data.=",$concat";
								print ' value="'.$data.'"/>';
								$i++;
							}
							for($i=1; $i<$total_items; $i++) {
								foreach(${'item'.$i} as $all) {
									if (stripos ($dates, $all->DATES) !== false) {
										//
									}
									else {
										$dates .= $all->DATES."|";
									}
								}
							}
							$dates = explode("|", $dates);
							unset($dates[$total_items+1]);
							asort($dates);
							$dates = implode(',', $dates);
							print '<input type="hidden" value="'.$dates.'" id="chart_date" class="chart_date" name="chart_date" />';
							
							print '<input type="hidden" value="'.$stock_item.'" id="items" class="items" name="items" />';
							*/
						?>
					</div>
				</div>
				<div class="panel-footer text-center">
					<b><?php echo date('F Y'); ?> EVC - H-PIN conversation statics</b>
				</div>
			</div>
		</div>
		
		<div class="clearfix"></div>
	</div>
	-->
	<br /><br />
</div>
