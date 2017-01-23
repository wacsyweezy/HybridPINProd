<link rel="stylesheet" href="<?php print base_url()?>assets/vendor/select2-3.5.2/select2.css" />
<link rel="stylesheet" href="<?php print base_url()?>assets/vendor/select2-bootstrap/select2-bootstrap.css" />
<div class="small-header transition animated fadeIn">
	<div class="hpanel">
		<div class="panel-body">
			<div id="hbreadcrumb" class="pull-right">
				<ol class="hbreadcrumb breadcrumb">
					<li><a href="<?php echo base_url(); ?>">Dashboard</a></li>
					<li>
						<span>H-PIN Management</span>
					</li>
					<li class="active">
						<span>EVC - ePIN Conversion Rate</span>
					</li>
				</ol>
			</div>
			<h2 class="font-light m-b-xs">
				H-PIN Management
			</h2>
			<small>EVC Conversion Rate</small>
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
								<i class="fa fa-file-o"></i> New Stock Item
							</div>
							<div class="panel-body">
								<?php echo form_open('pinmanagement/addStock', array('class' => 'form-horizontal form-label-left', 'id'=>'demo-form2')); ?>
									<div class="form-group">
										<label class="control-label col-md-3 col-sm-3 col-xs-12 text-right">Stock Item</label>
										<div class="col-md-9 col-sm-9 col-xs-12">
											 <input type="text" placeholder="Enter Stock Item Eg. EP 100" class="form-control" name="stock" id="stock" />
										</div>
									</div>
									<div class="form-group">
										<label class="control-label col-md-3 col-sm-3 col-xs-12 text-right">Description</label>
										<div class="col-md-9 col-sm-9 col-xs-12">
											 <input type="text" placeholder="Stock Item Description" class="form-control" name="description" id="description" />
										</div>
									</div>
									<div class="form-group">
										<label class="control-label col-md-3 col-sm-3 col-xs-12 text-right">EVC Units</label>
										<div class="col-md-9 col-sm-9 col-xs-12">
											 <input type="text" placeholder="EVC Conversion Rate" class="form-control" name="rate" id="rate" />
										</div>
									</div>
									<div class="form-group">
										<label class="control-label col-md-3 col-sm-3 col-xs-12 text-right">Face Value</label>
										<div class="col-md-9 col-sm-9 col-xs-12">
											 <input type="text" placeholder="Actuall Face Value" class="form-control" name="fvalue" id="fvalue" />
										</div>
									</div>
									<div class="form-group">
										<div class="col-md-9 col-sm-9 col-xs-12 col-md-offset-3">
											<a href="<?php echo base_url(); ?>pinmanagement/index" class="btn btn-warning"><i class="fa fa-history"></i> Cancel</a>&nbsp; &nbsp;
											<button type="submit" class="btn btn-success"><i class="fa fa-check"></i> Add Item</button>
										</div>
									</div>
									<div class="clearfix"></div>
								</form>
							</div>
						</div>
					</div>
					<div class="col-md-7">
						<?php
							if(isset($stocks) && count($stocks)>=1) {
								echo '	<div class="table table-responsive">
											<table class="table table-responsive table-hover">
												<thead>
													<tr>
														<th>S/N</th>
														<th>Stock Item</th>
														<th>Description</th>
														<th>EVC Units</th>
														<th>Face Value</th>
														<th>Added By</th>
													</tr>
												</thead>
												<tbody>';
								$sn = 1;
								foreach($stocks as $stock) {
									echo '			<tr>
														<td>'.$sn.'</td>
														<td>'.$stock->STOCK_ID.'</td>
														<td>'.$stock->DESCRIPTION.'</td>
														<td>'.$stock->EVC_UNITS.'</td>
														<td>'.$stock->FACEVALUE.'</td>
														<td>'.$stock->ADDED_BY.'
															<a href="'.base_url().'pinmanagement/removeStock?stock='.base64_encode($stock->STOCK_ID).'" title="Remove '.$stock->STOCK_ID.' stock item" class="pull-right text-danger"> <i class="fa fa-times"></i></a>
														</td>
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