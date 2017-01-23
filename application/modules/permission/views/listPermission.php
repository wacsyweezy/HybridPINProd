<div class="small-header">
    <div class="hpanel">
        <div class="panel-body">
            <div id="hbreadcrumb" class="pull-right">
                <ol class="hbreadcrumb breadcrumb">
                    <li><a href="<?php echo base_url(); ?>">Dashboard</a></li>
                    <li>
                        <span>Access Management</span>
                    </li>
                    <li class="active">
                        <span> Access List</span>
                    </li>
                </ol>
            </div>
            <h2 class="font-light m-b-xs">
                Access Management
            </h2>
            <small>Available Access</small>
        </div>
    </div>
</div>
<div class="content animate-panel"  style="padding-bottom:30px;">
    <div class="row">
        <div class="col-lg-12">
			<?php 
			if (isset($msg)) {
				echo $msg;
			} 
			?>
			<div class="hpanel">
				<div class="panel-heading hbuilt">
					<span class="pull-left"><i class="fa fa-exchange"></i> All Access</span>
					<!-- <a class="pull-right" href="<?php echo base_url(); ?>permission"><i class="fa fa-plus"></i> Add New Access</a> -->
					<div class="clearfix"></div>
				</div>
				<div class="panel-body">
					<table id="example2" class="table table-striped table-bordered table-hover" style="margin-bottom:0px;">
						<thead>
							<tr>
								<th>S/N</th>
								<th>Menu Name</th>
								<th>Display Name</th>
								<th>Controller Name</th>
								<th>Action Name</th>
								<!-- <th class="text-right">Task</th> -->
							</tr>
						</thead>
						<tbody> 
							<?php $sn = 0;
							for ($i = 0; $i < count($record); $i++) {
								?>
								<tr> 
									<td><?php echo ++$sn; ?></td> 
									<td><?php echo $record[$i]['MAIN1_MENU_ID']; ?></td>
									<td><?php echo $record[$i]['DISPLAY_NAME']; ?></td>
									<td><?php echo $record[$i]['CONTROLLER_NAME']; ?></td>
									<td><?php echo $record[$i]['ACTION_NAME']; ?></td>
									<!-- <td class="text-center">
										<div class="btn-group pull-right">
											<a class="btn btn-default btn-xs" href="<?php echo base_url(); ?>permission/set_up/
												<?php echo $record[$i]['PERMISSION_ID']; ?>"
												title="Edit <?php echo $record->shortform; ?> details">
												<i class="fa fa-pencil"></i>
											</a>
											<a class="btn btn-danger btn-xs" href="<?php echo base_url(); ?>permission/delete/
												<?php echo $record[$i]['PERMISSION_ID']; ?>"
												title="Delete <?php echo $record->shortform; ?> details"
												onclick="return confirm('Are you sure want to delete this permission?')">
												<i class="fa fa-trash"></i>
											</a>
										</div>
										<div class="clearfix"></div>
									</td> -->
								</tr> 
								<?php } ?>
						</tbody> 
					</table>
				</div>
			</div>
		</div>
		<div class="clearfix"></div>
    </div>
</div>



