<link href="<?php echo base_url(); ?>assets/frontend/datatables.net-bs/css/dataTables.bootstrap.min.css" rel="stylesheet">
<link href="<?php echo base_url(); ?>assets/frontend/datatables.net-buttons-bs/css/buttons.bootstrap.min.css" rel="stylesheet">
<link href="<?php echo base_url(); ?>assets/frontend/datatables.net-responsive-bs/css/responsive.bootstrap.min.css" rel="stylesheet">

<link rel="stylesheet" href="<?php echo base_url(); ?>assets/vendor/bootstrap-datepicker-master/dist/css/bootstrap-datepicker3.min.css" />
<link rel="stylesheet" href="<?php echo base_url(); ?>assets/vendor/awesome-bootstrap-checkbox/awesome-bootstrap-checkbox.css" />
<link rel="stylesheet" href="<?php echo base_url(); ?>assets/vendor/clockpicker/dist/bootstrap-clockpicker.min.css" />
<link rel="stylesheet" href="<?php echo base_url(); ?>assets/vendor/eonasdan-bootstrap-datetimepicker/build/css/bootstrap-datetimepicker.min.css" />
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
						<span>Order Approval</span>
					</li>
				</ol>
			</div>
			<h2 class="font-light m-b-xs">
				Order Approval
			</h2>
			<small>Orders Awaiting Approval</small>
		</div>
	</div>
</div>
<div class="content animate-panel">
	<div class="row">
		<div style="" class="col-lg-12">
			<div class="hpanel"  style="padding-bottom:15px;">
				<div class="panel-heading hbuilt">
					<div class="row">
						<div class="col-md-5">
							<h5>
							<?php
								if(!isset($_GET['start']) && !isset($_GET['end'])) {
									echo '<i class="fa fa-calendar"></i> '.date('F').' Orders Awaiting Approval';
								}
								else {
									$start = date('D F jS, Y', strtotime(addslashes($_GET['start'])));
									$end = date('D F jS, Y', strtotime(addslashes($_GET['end'])));
									echo '<i class="fa fa-calendar"></i> '.$start .' - '.$end.' Bulk Orders';
								}
							?>
							</h5>
						</div>
						<div class="col-md-3 text-right">
							<p style="margin-top:4px;">Transactions Lookup</p>
						</div>
						<div class="col-md-3" style="padding-right:0px;">
							<div class="pull-left input-daterange input-group">
								<span class="fa fa-calendar input-group-addon"></span>
								<input type="text" class="date_picker input-sm form-control" name="start" id="datapicker1" value="<?php echo date('m/d/Y'); ?>"/>
								<span class="input-group-addon">to</span>
								<input type="text" class="date_picker input-sm form-control" name="end" id="datapicker2" value="<?php echo date('m/d/Y'); ?>"/>
							</div>
						</div>
						<div class="col-md-1" style="padding-left:0px;">
							<button class="applyBtn btn btn-sm pull-right"><i class="fa fa-search"></i> Submit</button>
						</div>
						<div class="clearfix"></div>
					</div>
				</div>
				<div class="panel-body table table-responsive">
					<?php
						if(isset($approved)) {
							foreach($approved as $done) {
								if($done->RESULT==1) {
									$order_no = base64_decode($_GET['approve']);
									echo "<div class='alert alert-success'><i class='fa fa-check'></i> Order $order_no was successfully approved</div><br />";
								}
								if($done->RESULT==0) {
									$order_no = base64_decode($_GET['approve']);
									echo "<div class='alert alert-warning'><i class='fa fa-check'></i> Order $order_no was not approved successfully</div><br />";
								}
							}
						}
						if(isset($cancelled)) {
							foreach($cancelled as $done) {
								if($done->RESULT==1) {
									$order_no = base64_decode($_GET['approve']);
									echo "<div class='alert alert-success'><i class='fa fa-times'></i> Order $order_no was cancelled successfully</div><br />";
								}
								if($done->RESULT==0) {
									$order_no = base64_decode($_GET['approve']);
									echo "<div class='alert alert-warning'><i class='fa fa-bolt'></i> Order $order_no could not be approved at the moment</div><br />";
								}
								if($done->RESULT==2) {
									$order_no = base64_decode($_GET['approve']);
									echo "<div class='alert alert-info'><i class='fa fa-bolt'></i> Order $order_no has been approved initially</div><br />";
								}
							}
						}
						if(sizeof($get_approval)>=1) {
						?>
					<table id='datatable-buttons' class='table table-striped table-bordered' style='width:100%;'>
						<thead>
							<tr>
								<th>Order No</th>
								<th>Order Date</th>
								<th>Approved By</th>
								<th>Approval Date</th>
								<th>Cancelled By</th>
								<th>Date Cancelled</th>
								<th>Delivery Date</th>
								<th>Status</th>
								<th class="text-right">Action</th>
							</tr>
						</thead>
						<tbody>
						<?php
							foreach($get_approval as $pending) {
							echo '
							<tr>
								<td>'.$pending->ORDER_NO.'</td>
								<td>'.date('Y-m-d h:iA', strtotime($pending->ORDER_DATE)).'</td>
								<td>'.$pending->APPROVED_BY.'</td>
								<td>'.$pending->APPROVAL_DATE.'</td>
								<td>'.$pending->TIME_DELIVERED.'</td>
								<td>'.$pending->CANCELLED_BY.'</td>
								<td>'.$pending->DATE_CANCELLED.'</td>
								<td>'.$pending->ORDER_STATUS.'</td>
								<td style="width:95px;">';
								if($pending->ORDER_STATUS!="BOOKED" and $pending->ORDER_STATUS!="CANCELLED" ) {
									echo '
									<div class="btn-group">
										<a class="btn btn-xs btn-primary" target="_new" href="'.base_url().'order/vieworder/?order='.$pending->ORDER_NO.'" title="View Order"><i class="fa fa-desktop"></i></a>
										<a title="Cancel Order" type="cancle_transaction" href="'.base_url().'order/orderApprove/?perform='.Sha1(mt_rand().mt_rand().date().'BLUECHIP'.'/'.'TECH').'&cancel='.base64_encode($pending->ORDER_NO).'" class="action btn btn-danger btn-xs">
											<i class="fa fa-times"></i>
										</a>
										<a title="Approve Order" type="approve_transaction" href="'.base_url().'order/orderApprove/?perform='.Sha1(mt_rand().mt_rand().date().'BLUECHIP'.'/'.'TECH').'&approve='.base64_encode($pending->ORDER_NO).'" class="action btn btn-success btn-xs">
											<i class="fa fa-check"></i>
										</a>
									</div>';
								}
								else {	
									echo '<a target="_new" class="pull-right btn btn-xs btn-primary" href="'.base_url().'order/vieworder/?order='.$pending->ORDER_NO.'"><i class="fa fa-laptop"></i> Details</a>';
								}
								echo '
									<div class="clearfix"></div> 
								</td>
							</tr>';
						}
						?>
						</tbody>
					</table>
						<?php
						}
						else {
							echo '<div class="alert alert-info"><i class="fa fa-info"></i> No unapproved orders found at the moment</div>';
						}
					?>
				</div>
			</div>
		</div>
	</div>
</div>