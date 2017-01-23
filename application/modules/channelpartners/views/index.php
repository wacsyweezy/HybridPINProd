<link rel="stylesheet" href="<?php print base_url()?>assets/vendor/select2-3.5.2/select2.css" />
<link rel="stylesheet" href="<?php print base_url()?>assets/vendor/select2-bootstrap/select2-bootstrap.css" />
<div class="small-header transition animated fadeIn">
	<div class="hpanel">
		<div class="panel-body">
			<div id="hbreadcrumb" class="pull-right">
				<ol class="hbreadcrumb breadcrumb">
					<li><a href="<?php echo base_url(); ?>">Dashboard</a></li>
					<li>
						<span>Dealer Management</span>
					</li>
					<li class="active">
						<span>Channel Partner Registration</span>
					</li>
				</ol>
			</div>
			<h2 class="font-light m-b-xs">
				Channel Partners
			</h2>
			<small>Hybrid Portal Channel Partner Registration</small>
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
					<span><i class="fa fa-user"></i> Add New Channel Partner</span>
					<a class="pull-right" href="<?php echo base_url(); ?>channelpartners/partners"><i class="fa fa-th"></i> List Channel Partners</a>
					<div class="clearfix"></div>
				</div>
				<div class="panel-body">
					<?php echo form_open('channelpartners/addpartner', array('class' => 'form-horizontal form-label-left', 'id'=>'demo-form2')); ?>
						<div class="form-group">
							<label class="control-label col-md-3 col-sm-3 col-xs-12 text-right">Partner Type</label>
							<div class="col-md-6 col-sm-6 col-xs-12">
								<select class="js-source-states" style="width: 100%" class="form-control" name="partytype" id="partytype">
									<option selected="selected" value="null">Please select type</option>
									<option value="1">Dealer</option>
									<option value="0">Reseller</option>
								</select>
							</div>
						</div>
						<div class="form-group">
							<label class="control-label col-md-3 col-sm-3 col-xs-12 text-right">Partner Code</label>
							<div class="col-md-6 col-sm-6 col-xs-12">
								<select class="js-source-states" style="width: 100%" class="form-control" name="partnercode" id="partnercode">
									<option selected="selected" value="null">Please select item type</option>
									<?php
										if(count($dealers)>=1) {
											foreach($dealers as $dealer) {
												echo '<option value="'.$dealer->USERCODE.'"><b>'.$dealer->USERCODE.'</b> - '.$dealer->DISPLAY_NAME.'</option>';
											}
										}
										else {
											echo '<option value="">No dealer information found</option>';
										}
									?>
								</select>
							</div>
						</div>
						<div class="form-group">
							<label class="control-label col-md-3 col-sm-3 col-xs-12 text-right">Partner Name</label>
							<div class="col-md-6 col-sm-6 col-xs-12">
								<input type="text" class="form-control" placeholder="" name="partnername" id="partnername"/>
							</div>
						</div>
						<div class="form-group">
							<label class="control-label col-md-3 col-sm-3 col-xs-12 text-right">EVC Account Code</label>
							<div class="col-md-6 col-sm-6 col-xs-12">
								<input type="text" class="form-control" placeholder="" name="evcaccount" id="evcaccount"/>
							</div>
						</div>
						<div class="form-group">
							<div class="col-md-6 col-sm-6 col-xs-12 col-md-offset-3">
								<a href="<?php echo $_SERVER['PHP_SELF']; ?>" class="btn btn-warning"><i class="fa fa-history"></i> Cancel</a> &nbsp; &nbsp; 
								<button type="submit" class="btn btn-success"><i class="fa fa-check"></i> Add Partner</button>
							</div>
						</div>
					</form>
				</div>
			</div>
		</div>
	</div>
</div>