<link rel="stylesheet" href="<?php print base_url()?>assets/vendor/select2-3.5.2/select2.css" />
<link rel="stylesheet" href="<?php print base_url()?>assets/vendor/select2-bootstrap/select2-bootstrap.css" />
<div class="small-header transition animated fadeIn">
	<div class="hpanel">
		<div class="panel-body">
			<div id="hbreadcrumb" class="pull-right">
				<ol class="hbreadcrumb breadcrumb">
					<li><a href="./">Dashboard</a></li>
					<li>
						<span>H-PIN Management</span>
					</li>
					<li class="active">
						<span>New Task</span>
					</li>
				</ol>
			</div>
			<h2 class="font-light m-b-xs">
				H-PIN Management
			</h2>
			<small>H-PIN Task</small>
		</div>
	</div>
</div>
<div class="content animate-panel">
	<div class="row">
		<div class="col-lg-12">
			<div class="hpanel">
				<div class="panel-heading hbuilt">
					<span class="pull-left"><i class="fa fa-plus"></i> Add New Task</span>
					<a class="pull-right" href="<?php echo base_url(); ?>pinmanagement/index"><i class="fa fa-th"></i> Task Status Inquiry</a>
					<div class="clearfix"></div>
				</div>
				<div class="panel-body">
					<?php echo form_open('pinmanagement/addRequest', array('class' => 'form-horizontal form-label-left', 'id'=>'demo-form2')); ?>
						<div class="form-group">
							<label class="control-label col-md-3 col-sm-3 col-xs-12 text-right">Task Type</label>
							<div class="col-md-6 col-sm-6 col-xs-12">
								<?php
								if(isset($tasks) && count($tasks)>=1) {
									echo '	<select class="form-control js-source-states task_type" id="task_type" name="task_type">
											<option selected="selected" value="">Please select task type</option>';
									foreach($tasks as $task) {
										echo '<option value="'.$task->TASK_CODE.'">'.stripslashes($task->TASK_DESC).'</option>';
									}
									echo '	</select>';
								}
								else {
									echo 'No task defined';
								}
								?>
							</div>
						</div>
						<div class="form-group">
							<label class="control-label col-md-3 col-sm-3 col-xs-12 text-right">Task Mode</label>
							<div class="col-md-6 col-sm-6 col-xs-12">
								<select class="form-control js-source-states task_mode" id="task_mode" name="task_mode">
									<option selected="selected" value="">Please select task mode</option>
									<option value="BATCH">By Batch Number</option>
									<option value="SERIAL">By Serial Number</option>
								</select>
							</div>
						</div>
						<div class="form-group">
							<label class="control-label col-md-3 col-sm-3 col-xs-12 text-right">Batch No</label>
							<div class="col-md-6 col-sm-6 col-xs-12">
								 <input type="text" placeholder="Enter Batch number" class="form-control" name="batch" id="batch" />
							</div>
						</div>
						<div class="form-group">
							<label class="control-label col-md-3 col-sm-3 col-xs-12 text-right">Start Serial</label>
							<div class="col-md-6 col-sm-6 col-xs-12">
								 <input type="text" placeholder="Enter Start serial number" class="form-control" name="start" id="start" />
							</div>
						</div>
						<div class="form-group">
							<label class="control-label col-md-3 col-sm-3 col-xs-12 text-right">End Serial</label>
							<div class="col-md-6 col-sm-6 col-xs-12">
								 <input type="text" placeholder="Enter End serial number" class="form-control" name="end" id="end" />
							</div>
						</div>
						<div class="form-group">
							<div class="col-md-6 col-sm-6 col-xs-12 col-md-offset-3">
								<a href="<?php echo base_url(); ?>pinmanagement/index" class="btn btn-warning"><i class="fa fa-history"></i> Cancel</a>&nbsp; &nbsp;
								<button type="submit" class="btn btn-success"><i class="fa fa-check"></i> Submit Request</button>
							</div>
						</div>
						<div class="clearfix"></div>
                    </form>
				</div>
			</div>
		</div>
	</div>
</div>