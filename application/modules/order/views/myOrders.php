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
						<span>My Orders</span>
					</li>
				</ol>
			</div>
			<h2 class="font-light m-b-xs">
				Order History
			</h2>
			<small>My Orders</small>
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
									echo '<i class="fa fa-calendar"></i> '.date('F, Y').' Bulk Order History';
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
							<p style="margin-top:4px;">Seearch Transactions</p>
						</div>
						<div class="col-md-3" style="padding-right:0px;">
							<div class="pull-left input-daterange input-group">
								<span class="fa fa-calendar input-group-addon"></span>
								<input type="text" class="input-sm date_picker form-control" name="start" id="datapicker1" value="<?php echo date('m/d/Y'); ?>"/>
								<span class="input-group-addon">to</span>
								<input type="text" class="input-sm date_picker form-control" name="end" id="datapicker2" value="<?php echo date('m/d/Y'); ?>"/>
							</div>
						</div>
						<div class="col-md-1" style="padding-left:0px;">
							<button class="applyBtn btn btn-sm pull-right"><i class="fa fa-search"></i> Submit</button>
						</div>
						<div class="clearfix"></div>
					</div>
				</div>
				<div class="panel-body">
					<?php
						if(sizeof($get_pending)>=1) {
						?>
					<table id='my-order' class='table table-striped table-bordered' style='width:100%;'>
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
							foreach($get_pending as $pending) {
							echo '
							<tr>
								<td>'.$pending->ORDER_NO.'</td>
								<td>'.$pending->ORDER_DATE.'</td>
								<td>'.$pending->APPROVED_BY.'</td>
								<td>'.$pending->APPROVAL_DATE.'</td>
								<td>'.$pending->CANCELLED_BY.'</td>
								<td>'.$pending->DATE_CANCELLED.'</td>
								<td>'.$pending->TIME_DELIVERED.'</td>
								<td>'.$pending->ORDER_STATUS.'</td>
								<td style="width:70px;">
									<a target="_new" class="pull-right btn btn-xs btn-primary" href="'.base_url().'order/vieworder/?order='.$pending->ORDER_NO.'"><i class="fa fa-laptop"></i> Details</a>
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
							echo '<div class="alert alert-info"><i class="fa fa-info"></i> No order placed at the moment</div>';
						}
					?>
				</div>
			</div>
		</div>
	</div>
</div>