<div class="small-header">
    <div class="hpanel">
        <div class="panel-body">
            <div id="hbreadcrumb" class="pull-right">
                <ol class="hbreadcrumb breadcrumb">
                    <li><a href="<?php echo base_url(); ?>">Dashboard</a></li>
                    <li>
                        <span>User Management</span>
                    </li>
                    <li class="active">
                        <span>All users </span>
                    </li>
                </ol>
            </div>
            <h2 class="font-light m-b-xs">
                User Management
            </h2>
            <small>All Users</small>
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
					<span class="pull-left"><i class="fa fa-user"></i> All User</span>
					<a class="pull-right" href="<?php echo base_url(); ?>user"><i class="fa fa-plus"></i> Add New User</a>
					<div class="clearfix"></div>
				</div>
				<div class="panel-body">
					<table id="user-table" class="table table-striped table-bordered table-hover">
						<thead>
							<tr>
								<th>S/N</th>
								<th>Username</th>
								<th>Display Name</th>
								<th>Role Name</th>
								<th class="text-right">Task</th>
							</tr>
						</thead>
						<tbody> 
							<?php $sn = 0;
						   // foreach ($record as $rec) {
							for ($i = 0; $i < count($record); $i++) {
								?>
								<tr> 
									<td style="width:0px;"><?php echo ++$sn; ?></td> 
									<td><?php echo $record[$i]['USERNAME'].' <b>'.$record[$i]['USERCODE'].'</b>'; ?></td>
									<td><?php echo $record[$i]['DISPLAY_NAME']; ?></td>
									<td><?php echo $record[$i]['ROLE_NAME']; ?></td>
									<td class="text-right">
										<div class="btn-group">
											<a href="<?php echo base_url(); ?>user/set_up/<?php echo $record[$i]['USER_ID']; ?>" class="btn btn-default btn-xs" title="Edit <?php echo $record[$i]['DISPLAY_NAME']; ?> details">
												<i class="fa fa-pencil"></i>
											</a>
											<a href="<?php echo base_url(); ?>user/delete/<?php echo $record[$i]['USER_ID']; ?>" class="btn btn-danger btn-xs" title="Delete <?php echo $record[$i]['DISPLAY_NAME']; ?> account" onclick="return confirm('Are you sure want to delete this user?')">
												<i class="fa fa-trash"></i>
											</a>
										</div>
									</td>
								</tr> 
								<?php } ?>
						</tbody> 
					</table>
				</div>
			</div>
		</div>
    </div>
</div>



