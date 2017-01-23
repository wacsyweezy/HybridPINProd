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
						<span>Rule Management</span>
					</li>
					<li class="active">
						<span>Item Rules</span>
					</li>
				</ol>
			</div>
			<h2 class="font-light m-b-xs">
				Item Rules
			</h2>
			<small>Rule Definition for Items</small>
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
					<li class="active"><a aria-expanded="true" data-toggle="tab" href="components.html#tab-1"> Available Item Rules</a></li>
					<li class=""><a aria-expanded="false" data-toggle="tab" href="components.html#tab-2"> Add New Item Rule</a></li>
				</ul>
				<div class="tab-content">
					<div id="tab-1" class="tab-pane active">
						<div class="panel-body">
							<?php
								if(sizeof($rules)>=1) {
								?>
							<table id='datatable-buttons' class='table table-striped table-bordered' style='width:100%;'>
								<thead>
									<tr>
										<th>Item</th>
										<th>Max Daily Txn</th>
										<th>Max Monthly Txn</th>
										<th>Max Daily Vol</th>
										<th>Max Monthly Vol</th>
										<th>Max Txn Vol</th>
										<th>Min Txn Vol</th>
										<!-- <th>Status</th> -->
										<th>Created By</th>
										<th>Date</th>
										<th class="text-right">Action</th>
									</tr>
								</thead>
								<tbody>
								<?php
									foreach($rules as $rule) {
									echo '
									<tr>
										<td>'.$rule->ITEM.'</td>
										<td>'.$rule->MX_DAY_TXN.'</td>
										<td>'.$rule->MX_MONTH_TXN.'</td>
										<td>'.$rule->MX_DAY_VOL.'</td>
										<td>'.$rule->MX_MONTH_VOL.'</td>
										<td>'.$rule->MX_TXN_VOL.'</td>
										<td>'.$rule->MN_TXN_VOL.'</td>
										<td>'.$rule->CREATOR.'</td>
										<td>'.date('Y-m-d h:iA', strtotime($rule->DDATE)).'</td>
										<td style="width:70px;">
											<a class="pull-right btn btn-xs btn-danger" title="Remove Rule" href="'.base_url().'rules/itemremove/?rule='.$rule->RULEID.'"><i class="fa fa-times"></i></a>
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
									echo '<div class="alert alert-info"><i class="fa fa-info"></i> No item rule has been added at the moment</div>';
								}
							?>
						</div>
					</div>
					<div id="tab-2" class="tab-pane">
						<div class="panel-body">
							<?php echo form_open('rules/additemrule', array('class' => 'form-horizontal form-label-left', 'id'=>'demo-form2')); ?>
								<div class="form-group">
									<label class="control-label col-md-3 col-sm-3 col-xs-12 text-right">Item Type</label>
									<div class="col-md-6 col-sm-6 col-xs-12">
										<select class="js-source-states" style="width: 100%" class="form-control" name="itemtype" id="itemtype">
											<option selected="selected" value="">Please select item type</option>
											<?php
												if(count($items)>=1) {
													foreach($items as $item) {
														echo '<option value="'.$item->STOCK_ID.'">'.$item->STOCK_ID.'</option>';
													}
												}
												else {
													echo '<option value="">No stock item found</option>';
												}
											?>
										</select>
									</div>
								</div>
								<div class="form-group">
									<label class="control-label col-md-3 col-sm-3 col-xs-12 text-right">Maximum Daily Transaction</label>
									<div class="col-md-6 col-sm-6 col-xs-12">
										<input type="text" class="form-control" placeholder="" name="maxdailytxn" id="maxdailytxn"/>
									</div>
								</div>
								<div class="form-group">
									<label class="control-label col-md-3 col-sm-3 col-xs-12 text-right">Maximum Monthly Transaction</label>
									<div class="col-md-6 col-sm-6 col-xs-12">
										<input type="text" class="form-control" placeholder="" name="maxmonthlytxn" id="maxmonthlytxn"/>
									</div>
								</div>
								<div class="form-group">
									<label class="control-label col-md-3 col-sm-3 col-xs-12 text-right">Maximum Daily Volume</label>
									<div class="col-md-6 col-sm-6 col-xs-12">
										<input type="text" class="form-control" placeholder="" name="maxdailyvolume" id="maxdailyvolume"/>
									</div>
								</div>
								<div class="form-group">
									<label class="control-label col-md-3 col-sm-3 col-xs-12 text-right">Maximum Monthly Volume</label>
									<div class="col-md-6 col-sm-6 col-xs-12">
										<input type="text" class="form-control" placeholder="" name="maxmonthlyvolume" id="maxmonthlyvolume"/>
									</div>
								</div>
								<div class="form-group">
									<label class="control-label col-md-3 col-sm-3 col-xs-12 text-right">Maximum Transaction Volume</label>
									<div class="col-md-6 col-sm-6 col-xs-12">
										<input type="text" class="form-control" placeholder="" name="maxtxnvolume" id="maxtxnvolume"/>
									</div>
								</div>
								<div class="form-group">
									<label class="control-label col-md-3 col-sm-3 col-xs-12 text-right">Minimum Transaction Volume</label>
									<div class="col-md-6 col-sm-6 col-xs-12">
										<input type="text" class="form-control" placeholder="" name="mintxnvolume" id="mintxnvolume"/>
									</div>
								</div>

								<div class="form-group">
									<div class="col-md-6 col-sm-6 col-xs-12 col-md-offset-3">
										<a href="<?php echo $_SERVER['PHP_SELF']; ?>" class="btn btn-warning"><i class="fa fa-history"></i> Cancel</a>
										<button type="submit" class="btn btn-success"><i class="fa fa-check"></i> Add Rule</button>
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