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
                        <span>Report List</span>
                    </li>
                </ol>
            </div>
            <h2 class="font-light m-b-xs">
                Report System
            </h2>
            <small>Report Type List</small>
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
				<div class="panel-heading hbuilt">
					<span class="pull-left"><i class="fa fa-th"></i> All Report</span>
					<a class="pull-right" href="<?php echo base_url(); ?>getReport"><i class="fa fa-plus"></i> Generate Report</a>
					<div class="clearfix"></div>
				</div>
				<div class="panel-body" style="padding-bottom:0px;">
					<table id="example2" class="table table-striped table-bordered table-hover">
						<thead>
							<tr>
								<th>S/N</th>
								<th>Report Name</th>
								<th>Report Type</th>
								<th>Report Description</th>
								<th class="text-right">Task</th>
							</tr>
						</thead>
						<tbody> 
							<?php $sn = 0;
							foreach ($record as $rec) {/*
								?>
								<tr> 
									<td style="width:0px;"><?php echo ++$sn; ?></td> 
									<td><?php echo $rec->ROLE_NAME; ?></td>
									<td><?php   if ($rec->USERTYPE == 0 ) { echo "Internal" ;}else { echo "External";} ?></td>
									<td class="text-right">
										<div class="btn-group">
											<a href="<?php echo base_url(); ?>role/set_up/<?php echo $rec->ROLE_ID; ?>" class="btn btn-default btn-xs" title="Edit <?php echo $rec->ROLE_NAME; ?> details">
												<i class="fa fa-pencil"></i> Edit
											</a>
											<a href="<?php echo base_url(); ?>rolepermission/set_up/<?php echo $rec->ROLE_ID; ?>" class="btn btn-info btn-xs" title="Edit <?php echo $rec->ROLE_NAME; ?> permission details">
												<i class="fa fa-cogs"></i> Access Permission
											</a>
											<a href="<?php echo base_url(); ?>role/delete/<?php echo $rec->ROLE_ID; ?>" class="btn btn-danger btn-xs" title="Edit <?php echo $rec->ROLE_NAME; ?> details" onclick="return confirm('Are you sure want to delete this role?')">
												<i class="fa fa-trash"></i> Delete
											</a>
										</div>
									</td>
								</tr> 
								<?php */} ?>
						</tbody> 
					</table>
				</div>
			</div>
		</div>
    </div>                      
</div>



