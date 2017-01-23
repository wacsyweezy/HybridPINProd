<link rel="stylesheet" href="<?php print base_url()?>assets/vendor/select2-3.5.2/select2.css" />
<link rel="stylesheet" href="<?php print base_url()?>assets/vendor/select2-bootstrap/select2-bootstrap.css" />
<div class="small-header transition animated fadeIn">
	<div class="hpanel">
		<div class="panel-body">
			<div id="hbreadcrumb" class="pull-right">
				<ol class="hbreadcrumb breadcrumb">
					<li><a href="<?php echo base_url(); ?>">Dashboard</a></li>
					<li>
						<span>Account Details</span>
					</li>
					<li class="active">
						<span>My Profile</span>
					</li>
				</ol>
			</div>
			<h2 class="font-light m-b-xs">
				Account Details
			</h2>
			<small>My Profile</small>
		</div>
	</div>
</div>
<?php
	$name = $code = $evc = $reg_date = $pin = $email = '';
	foreach($info as $user) {
		$name = $user->DISPLAY_NAME;
		$code = $user->USERCODE;
		$evc = $user->MSISDN;
		$reg_date = $user->CREATED_DATE;
		$pin = $user->PIN_CODE;
		$email = $user->EMAIL_ADDRESS;
	}
?>
<div class="content animate-panel" style="background:#fff;">
	<div class="row">
		<div class="col-lg-12">
			<?php
				if(isset($msg)) {
					echo $msg;
				}
			?>
		</div>
		<div class="clearfix"></div>
	<div class="">
		<?php
		if(sizeof($partner_detail)>=1) {
			foreach($partner_detail as $detail) {
			?>
		<div class="col-sm-6 col-xs-12" style="background:#fff; border-right:1px solid #ccc;">
			<div class="pull-left">
				<small class="stat-label"><strong><?php echo ucwords($detail->PARTYNAME); ?></strong></small>
				<h4><?php echo $detail->PARTYCODE; ?> </h4>
			</div>
			<div class="stats-icon pull-right">
				<i class="pe-7s-user fa-4x"></i>
			</div>
			<div class="clearfix"></div>
		</div>
		<div class="col-sm-4 col-xs-12" style="background:#fff; border-right:1px solid #ccc;">
			<div class="pull-left">
				<small class="stat-label"><strong>E-PURSE BALANCE</strong></small>
				<h4><?php echo $evcBalance; ?> </h4>
			</div>
			<div class="stats-icon pull-right">
				<i class="pe-7s-cash fa-4x"></i>
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
	<div class="clearfix"></div>
   		<div class="col-lg-2">
        		<center>
				<i class="fa fa-home" style="font-size:120px;"></i>
			</center>
    		</div>
   		<div class="col-lg-5">
			<div class="form-group">
				<label class="control-label col-md-3 col-sm-3 col-xs-12 text-right">Partner's Name</label>
				<div class="col-md-6 col-sm-6 col-xs-12 lead">
					<?php echo $name; ?>
				</div>
			</div>
			<div class="clearfix"></div>
			<div class="form-group">
				<label class="control-label col-md-3 col-sm-3 col-xs-12 text-right">Trade Code</label>
				<div class="col-md-6 col-sm-6 col-xs-12 lead">
					<?php echo $code; ?>
				</div>
			</div>
			<div class="clearfix"></div>
			<div class="form-group">
				<label class="control-label col-md-3 col-sm-3 col-xs-12 text-right">e-Purse Number</label>
				<div class="col-md-6 col-sm-6 col-xs-12 lead">
					<?php echo $evc; ?>
				</div>
			</div>
    		</div>
    		<div class="col-lg-5">
        		<div class="hpanel hgreen">
						<div class="panel-body">
							<?php echo form_open('dashboard/profile', array('class' => 'form-horizontal form-label-left', 'id'=>'demo-form2')); ?>
								<div class="form-group">
									<label class="control-label col-md-3 col-sm-3 col-xs-12 text-right">Online PIN <span class="text-danger">*</span></label>
									<div class="col-md-6 col-sm-6 col-xs-12">
										<input type="text" class="form-control" placeholder="" name="ppin" id="ppin" value="<?php echo $pin; ?>" />
									</div>
								</div>
								<div class="form-group">
									<div class="col-md-9 col-sm-9 col-xs-12 col-md-offset-3">
										<a href="<?php echo $_SERVER['PHP_SELF']; ?>" class="btn btn-warning"><i class="fa fa-history"></i> Cancel</a>
										&nbsp;
										<button type="submit" class="btn btn-success"><i class="fa fa-check"></i> Update PIN</button>
									</div>
								</div>
							</form>
						</div>
            		</div>
		</div>
	</div>
</div>