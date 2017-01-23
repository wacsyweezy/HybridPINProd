<link rel="stylesheet" href="<?php print base_url()?>assets/vendor/select2-3.5.2/select2.css" />
<link rel="stylesheet" href="<?php print base_url()?>assets/vendor/select2-bootstrap/select2-bootstrap.css" />

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
						<span>Channel Partner List</span>
					</li>
				</ol>
			</div>
			<h2 class="font-light m-b-xs">
				Channel Partners
			</h2>
			<small>Hybrid Portal Channel Partners</small>
		</div>
	</div>
</div>
<div class="content animate-panel">
	<div class="row">
		<div class="col-md-12 col-sm-12 col-xs-12">
			<?php 
			if (isset($msg)) {
				echo $msg;
			} 
			?>
			<div class="hpanel">
				<div class="panel-heading hbuilt">
					<span class="pull-left"><i class="fa fa-th"></i> All Channel Partner</span>
					<div class="clearfix"></div>
				</div>
				<div class="panel-body">
					<?php
						if(sizeof($partner_list)>=1) {
							echo '<table id="channel-partner" class="table table-striped table-bordered" style="width:100%;">
									<thead>
										<tr>
											<th>Type</th>
											<th>Partner Code</th>
											<th>EVC Account</th>
											<th>Name</th>
											<th>Created By</th>
											<th>Date Created</th>
											<th>Authorized By</th>
											<th>Date Authorized</th>
											<th>Action</th>
										</tr>
									</thead>
									<tbody>';
							foreach ($partner_list as $partner) {
								if($partner->PARTYTYPEID==1) {
									$type = "Dealer";
								}
								else {
									$type = "Reseller";
								}
								echo '<tr>
									<td>'.$type.'</td>
									<td>'.$partner->PARTYCODE.'</td>
									<td>'.$partner->EVC_ACCT_CODE.'</td>
									<td>'.$partner->PARTYNAME.'</td>
									<td>'.$partner->CREATED_BY.'</td>
									<td>'.$partner->CREATED_DATE.'</td>
									<td>'.$partner->AUTHORIZED_BY.'</td>
									<td>'.$partner->AUTHORIZED_DATE.'</td>
									<td>';
									if(strlen($partner->AUTHORIZED_BY)<=0) { 
										echo '
										<div class="btn-group">
											<a title="Reject Authorization" href="'.base_url().'channelpartners/partners/?reject='.base64_encode($partner->PARTYCODE).'" class="btn btn-danger reject-partner btn-xs"><i class="fa fa-times"></i></a>
											<a title="Approve Authorization" href="'.base_url().'channelpartners/partners/?authorize='.base64_encode($partner->PARTYCODE).'" class="btn btn-success btn-xs"><i class="fa fa-check"></i></a>
										</div>';
									}
									else { 
										echo '<a href="'.base_url().'channelpartners/transactions/?party='.base64_encode($partner->PARTYCODE).'&account='.base64_encode($partner->EVC_ACCT_CODE).'" class="btn btn-xs btn-primary">Details</a>';
									}
									echo '
									</td>
								</tr>';
							}
							echo "
							</tbody>
						</table>";
						}
					?>
				</div>
			</div>
		</div>
	</div>
</div>