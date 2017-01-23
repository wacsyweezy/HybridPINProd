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
						<span>Default Rule</span>
					</li>
				</ol>
			</div>
			<h2 class="font-light m-b-xs">
				Defaulr Rule
			</h2>
			<small>Default Hybrid Rule</small>
		</div>
	</div>
</div>
<div class="content animate-panel">
	<div class="row">
		<div class="col-lg-12">
			<?php 
			if (isset($msg)) {
				echo $msg;
			} 
			?>
			<div class="hpanel">
				<div class="panel-body">
					<div class="col-md-5">
						<div class="hpanel row">
							<div class="panel-heading hbuilt">
								<i class="fa fa-file-o"></i> Hybrid Default Rule
							</div>
							<div class="panel-body">
								<?php echo form_open('rules/updateDefault', array('class' => 'form-horizontal form-label-left', 'id'=>'demo-form2')); ?>
									<div class="form-group">
										<label class="control-label col-md-3 col-sm-3 col-xs-12 text-right">Max EVC Percentage</label>
										<div class="col-md-9 col-sm-9 col-xs-12">
											 <input type="text" placeholder="Maximum EVC Percentage" class="form-control" name="pct" id="pct" />
										</div>
									</div>
									<div class="form-group">
										<label class="control-label col-md-3 col-sm-3 col-xs-12 text-right">Minimum EVC Balance</label>
										<div class="col-md-9 col-sm-9 col-xs-12">
											 <input type="text" placeholder="Minimum EVC Wallet Account" class="form-control" name="bal" id="bal" />
										</div>
									</div>
									<div class="form-group">
										<div class="col-md-9 col-sm-9 col-xs-12 col-md-offset-3">
											<a href="<?php echo base_url(); ?>" class="btn btn-warning"><i class="fa fa-history"></i> Cancel</a>&nbsp; &nbsp;
											<button type="submit" class="btn btn-success"><i class="fa fa-check"></i> Update Rule</button>
										</div>
									</div>
									<div class="clearfix"></div>
								</form>
							</div>
						</div>
					</div>
					<div class="col-md-7">
						<?php
							if(isset($default_rule) && count($default_rule)>=1) {
								echo '	<div class="table table-responsive">
											<table class="table table-responsive table-hover">
												<thead>
													<tr>
														<th>S/N</th>
														<th>Max EVC Percentage</th>
														<th>Min EVC Balance</th>
													</tr>
												</thead>
												<tbody>';
								$sn = 1;
								foreach($default_rule as $default) {
									echo '			<tr>
														<td>'.$sn.'</td>
														<td>'.$default->MAX_PCT_TOTAL.'</td>
														<td>'.$default->MIN_EVC_BALANCE.'</td>														<td>'.$stock->EVC_UNITS.'</td>
													</tr>';
									$sn ++;
								}
								echo '			</tbody>
											</table>
										</div>';
							}
							else {
								
							}
						?>
					</div>
					<div class="clearfix"></div>
				</div>
			</div>
		</div>
	</div>
</div>