<link rel="stylesheet" href="<?php print base_url()?>assets/vendor/select2-3.5.2/select2.css" />
<link rel="stylesheet" href="<?php print base_url()?>assets/vendor/select2-bootstrap/select2-bootstrap.css" />
<link rel="stylesheet" href="<?php print base_url()?>assets/vendor/bootstrap-datepicker-master/dist/css/bootstrap-datepicker3.min.css" />
<link rel="stylesheet" href="<?php print base_url()?>assets/vendor/datatables.net-bs/css/dataTables.bootstrap.min.css" />
<div class="small-header">
    <div class="hpanel">
        <div class="panel-body">
            <div id="hbreadcrumb" class="pull-right">
                <ol class="hbreadcrumb breadcrumb">
                    <li><a href="<?php echo base_url(); ?>">Dashboard</a></li>
                    <li>
                        <span>Report System</span>
                    </li>
                    <li class="active">
                        <span>Generate Report </span>
                    </li>
                </ol>
            </div>
            <h2 class="font-light m-b-xs">
                Report System
            </h2>
            <small>Report Generation</small>
        </div>
    </div>
</div>

<div class="content">
    <div class="row">
        <div class="col-lg-12">
			<?php 
			if (isset($msg)) {
				echo $msg;
			} 
			?>
            <div class="hpanel">
				<div class="panel-heading hbuilt">
					<span class="pull-left"><i class="fa fa-book"></i> Report System</span>
					<!-- <a class="pull-right" href="<?php echo base_url(); ?>report/reportList"><i class="fa fa-th"></i> List All Report</a> -->
					<div class="clearfix"></div>
				</div>
                <div class="panel-body">
                    <?php echo form_open('report/fetchReport', array('class' => 'form-horizontal form-label-left', 'id'=>'demo-form2')); ?>
						<div class="dealer_order params panel">
							<div class="panel-body">
								<h3 class="text-center">Orders Report</h3>
								<div class="form-group">
									<label class="control-label col-md-3 col-sm-3 col-xs-12 text-right">Report Start Date</label>
									<div class="col-md-6 col-sm-6 col-xs-12">
										<input type="text" class="form-control date_picker" name="dealer_start" id="dealer_start" value="<?php echo date('m/d/Y'); ?>" />
									</div>
								</div>
								<div class="form-group">
									<label class="control-label col-md-3 col-sm-3 col-xs-12 text-right">Report End Date</label>
									<div class="col-md-6 col-sm-6 col-xs-12">
										<input type="text" class="form-control date_picker" name="dealer_end" id="dealer_end" value="<?php echo date('m/d/Y'); ?>" />
									</div>
<input type="hidden" id="tradecode" value="<?php echo $_SESSION['usercode']; ?>" />
								</div>
								<div class="form-group">
									<div class="col-md-6 col-sm-6 col-xs-12 col-md-offset-3">
										<a href="" class="btn btn-warning"><i class="fa fa-history"></i> Cancel</a>&nbsp; &nbsp;
										<button type="button" class="btn btn-success generate" id="dealerOrder"><i class="fa fa-check"></i> Generate Report</button>
									</div>
								</div> 
							</div>
						</div>
						
                    </form>
			<div class="report-page hidden panel panel-body" style="padding:10px;"></div>
                </div>
            </div>
        </div>
    </div>
</div>