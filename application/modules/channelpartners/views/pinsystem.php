<link href="<?php echo base_url(); ?>assets/frontend/datatables.net-bs/css/dataTables.bootstrap.min.css" rel="stylesheet">
<link href="<?php echo base_url(); ?>assets/frontend/datatables.net-buttons-bs/css/buttons.bootstrap.min.css" rel="stylesheet">
<link href="<?php echo base_url(); ?>assets/frontend/datatables.net-responsive-bs/css/responsive.bootstrap.min.css" rel="stylesheet">

<link rel="stylesheet" href="<?php echo base_url(); ?>assets/vendor/bootstrap-datepicker-master/dist/css/bootstrap-datepicker3.min.css" />
<link rel="stylesheet" href="<?php echo base_url(); ?>assets/vendor/awesome-bootstrap-checkbox/awesome-bootstrap-checkbox.css" />
<link rel="stylesheet" href="<?php echo base_url(); ?>assets/vendor/clockpicker/dist/bootstrap-clockpicker.min.css" />
<link rel="stylesheet" href="<?php echo base_url(); ?>assets/vendor/eonasdan-bootstrap-datetimepicker/build/css/bootstrap-datetimepicker.min.css" />

<link rel="stylesheet" href="<?php print base_url()?>assets/vendor/select2-3.5.2/select2.css" />
<link rel="stylesheet" href="<?php print base_url()?>assets/vendor/select2-bootstrap/select2-bootstrap.css" />
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
						<span>Online PIN System</span>
					</li>
				</ol>
			</div>
			<h2 class="font-light m-b-xs">
				Channel Partners 
			</h2>
			<small>PIN System</small>
		</div>
	</div>
</div>
<div class="content animate-panel">
	<div class="row">
		<div style="" class="col-lg-12">
			<?php
				if(isset($msg)) {
					echo $msg;
				}
				if(isset($addResult)) {
					foreach($addResult as $result) {
						echo $result->RESULT;
					}
				}
				if(isset($deleteResult)) {
					foreach($deleteResult as $result) {
						echo $result->RESULT;
					}
				}
			?>
			<div class="hpanel">
				<ul class="nav nav-tabs">
					<li class="active"><a aria-expanded="true" data-toggle="tab" href="components.html#tab-1"> All Dealer PIN</a></li>
					<li class=""><a aria-expanded="false" data-toggle="tab" href="components.html#tab-2"> Add New PIN</a></li>
				</ul>
				<div class="tab-content">
					<div id="tab-1" class="tab-pane active">
						<div class="panel-body">
							<?php
								if(sizeof($pins)>=1) {
								?>
							<table id='channel-partner' class='table table-striped table-bordered' style='width:100%;'>
								<thead>
									<tr>
										<th>Trade Code</th>
										<th>e-Purse</th>
										<th>Online PIN</th>
										<th>Created By</th>
										<th>Date</th>
										<th>Status</th>
										<th class="text-right">Action</th>
									</tr>
								</thead>
								<tbody>
								<?php
									foreach($pins as $pin) {
									echo '
									<tr>
										<td>'.$pin->DEALERCODE.'</td>
										<td>'.$pin->MSISDN.'</td>
										<td>'.$pin->PIN_CODE.'</td>
										<td>'.$pin->CREATED_BY.'</td>
										<td>'.$pin->CREATED_DATE.'</td>
										<td>';
									if($pin->STATUS==0) {
										echo 'Active';
									}
									else {
										echo 'Inactive';
									}
									echo 	'</td>
										<td style="width:70px;">
											<a class="pull-right btn btn-xs btn-danger" title="Remove PIN" href="'.base_url().'channelpartners/pinsystem/?pin='.base64_encode($pin->MSISDN).'"><i class="fa fa-times"></i></a>
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
									echo '<div class="alert alert-info"><i class="fa fa-info"></i> No dealer rule has been added at the moment</div>';
								}
							?>
						</div>
					</div>
					<div id="tab-2" class="tab-pane">
						<div class="panel-body">
							<?php echo form_open('channelpartners/pinsystem', array('class' => 'form-horizontal form-label-left', 'id'=>'demo-form2')); ?>
								<div class="form-group">
									<label class="control-label col-md-3 col-sm-3 col-xs-12 text-right">Trade Code</label>
									<div class="col-md-6 col-sm-6 col-xs-12">
										<input type="text" class="form-control" placeholder="" name="pcode" id="pcode"/>
									</div>
								</div>
								<div class="form-group">
									<label class="control-label col-md-3 col-sm-3 col-xs-12 text-right">Online PIN <span class="text-danger">*</span></label>
									<div class="col-md-6 col-sm-6 col-xs-12">
										<input type="text" class="form-control" placeholder="" name="ppin" id="ppin"/>
									</div>
								</div>
								<div class="form-group">
									<label class="control-label col-md-3 col-sm-3 col-xs-12 text-right">e-Purse Number <span class="text-danger">*</span></label>
									<div class="col-md-6 col-sm-6 col-xs-12">
										<input type="text" class="form-control" placeholder="" name="epurse" id="epurse"/>
									</div>
								</div>
								<div class="form-group">
									<div class="col-md-6 col-sm-6 col-xs-12 col-md-offset-3">
										<a href="<?php echo $_SERVER['PHP_SELF']; ?>" class="btn btn-warning"><i class="fa fa-history"></i> Cancel</a>
										&nbsp; &nbsp; 
										<button type="submit" class="btn btn-success"><i class="fa fa-check"></i> Add PIN</button>
									</div>
								</div>
							</form>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>