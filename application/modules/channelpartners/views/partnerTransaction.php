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
						<span>Channel Partners</span>
					</li>
					<li class="active">
						<span>Partner Transaction</span>
					</li>
				</ol>
			</div>
			<h2 class="font-light m-b-xs">
				Transaction History
			</h2>
			<small>Channel Partner Orders </small>
		</div>
	</div>
</div>
<div class="content animate-panel">
	<div class="">
		<?php
		if(sizeof($partner_detail)>=1) {
			foreach($partner_detail as $detail) {
			?>
		<div class="col-sm-4 col-xs-12" style="background:#fff; border-right:1px solid #ccc;">
			<div class="pull-left">
				<small class="stat-label"><strong><?php echo ucwords($detail->PARTYNAME); ?></strong></small>
				<h4><?php echo $detail->PARTYCODE; ?> </h4>
			</div>
			<div class="stats-icon pull-right">
				<i class="pe-7s-user fa-4x"></i>
			</div>
			<div class="clearfix"></div>
		</div>
		<div class="col-sm-3 col-xs-12" style="background:#fff; border-right:1px solid #ccc;">
			<div class="pull-left">
				<small class="stat-label"><strong>EVC WALLET BALANCE</strong></small>
				<h4><?php echo $evcaccount; ?> </h4>
			</div>
			<div class="stats-icon pull-right">
				<i class="pe-7s-cash fa-4x"></i>
			</div>
			<div class="clearfix"></div>
		</div>
		<div class="col-sm-3 col-xs-12" style="background:#fff; border-right:1px solid #ccc;">
			<div class="pull-left">
				<small class="stat-label"><strong>AUTHORIZED BY</strong></small>
				<h4><?php echo $detail->AUTHORIZED_BY; ?> </h4>
			</div>
			<div class="stats-icon pull-right">
				<i class="pe-7s-check fa-4x"></i>
			</div>
			<div class="clearfix"></div>
		</div>
		<div class="col-sm-2 col-xs-12" style="background:#fff;">
			<div class="pull-left">
				<small class="stat-label"><strong>TRANSACTIONS</strong></small>
				<h4><?php echo sizeof($transaction_list); ?> </h4>
			</div>
			<div class="stats-icon pull-right">
				<i class="pe-7s-keypad fa-4x"></i>
			</div>
			<div class="clearfix"></div>
		</div>
			<?php
			}
		}
	?>
	</div>
	<div class="row">
		<div style="" class="col-lg-12">
			<div class="hpanel"  style="padding-bottom:15px;">
				<div class="panel-body">
					<?php
						if(sizeof($transaction_list)>=1) {
						?>
					<table id='datatable-buttons' class='table table-striped table-bordered' style='width:100%;'>
						<thead>
							<tr>
								<th>Order No</th>
								<th>Customer Name</th>
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
							foreach($transaction_list as $list) {
							echo '
							<tr>
								<td>'.$list->ORDER_NO.'</td>
								<td>'.$list->PARTNER_CODE.'</td>
								<td>'.date('Y-m-d h:iA', strtotime($list->ORDER_DATE)).'</td>
								<td>'.$list->APPROVED_BY.'</td>
								<td>'.$list->APPROVAL_DATE.'</td>
								<td>'.$list->TIME_DELIVERED.'</td>
								<td>'.$list->CANCELLED_BY.'</td>
								<td>'.$list->DATE_CANCELLED.'</td>
								<td>'.$list->ORDER_STATUS.'</td>
								<td style="width:70px;">
									<a target="_new" class="pull-right btn btn-xs btn-primary" href="'.base_url().'order/vieworder/?order='.$list->ORDER_NO.'"><i class="fa fa-laptop"></i> Details</a>
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