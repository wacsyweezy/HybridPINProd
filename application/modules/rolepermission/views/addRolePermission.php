<div class="small-header">
    <div class="hpanel">
        <div class="panel-body">
            <div id="hbreadcrumb" class="pull-right">
                <ol class="hbreadcrumb breadcrumb">
                    <li><a href="<?php echo base_url(); ?>">Dashboard</a></li>
                    <li>
                        <span>Role System</span>
                    </li>
                    <li class="active">
                        <span>New Role </span>
                    </li>
                </ol>
            </div>
            <h2 class="font-light m-b-xs">
                Role System
            </h2>
            <small>Permission Access Setting</small>
        </div>
    </div>
</div>
<div class="content">
    <div class="row">
        <div class="col-lg-12" style="margin-bottom:15px;">
			<?php 
			if (isset($msg)) {
				echo $msg;
			} 
			?>
            <div class="hpanel">
				<div class="panel-heading hbuilt">
					<span class="pull-left"><i class="fa fa-list"></i> <?php echo $rolename ?> Role Access Settings</span>
					<a class="pull-right" href="<?php echo base_url(); ?>role"><i class="fa fa-plus"></i> Add New Role</a>
					<div class="clearfix"></div>
				</div>              
				<div class="panel-body" style="padding-bottom:0px;">
                    <?php echo form_open('rolepermission/submit', array('class' => '')); ?>
						<div class="form-group">
							<table class="table table-bordered table-striped">
								<thead>
									<tr>
										<th class="text-center text-success"><i class="fa fa-check"></i></th>
										<th>Access Permission</th>
										<th>Access Description</th>
									</tr>
								</thead>
								<tbody>
							<?php
							 for ($i = 0; $i < count($permissions); $i++) {
								 echo '<tr>';
							  if  ( $permissions[$i]['CHECK_VALUE'] == 1){
								//  echo "true...........";
							 ?>
								<td style="width:0px;">
							<input type="checkbox" class="i-checks" name="permissionids[]"   value="<?php echo $permissions[$i]['PERMISSION_ID']   ?>" checked="true"  >
							   </td>
								<?php
							  }
								else{
								 //   echo 'false ..........';
								 ?>
								 <td>
							   <input type="checkbox" class="i-checks" name="permissionids[]"   value="<?php echo $permissions[$i]['PERMISSION_ID']   ?>"   >
								</td>
							<?php
								}
								echo '<td>';
								echo $permissions[$i]['DISPLAY_NAME']; 
								echo '</td>';
								echo '<td><i>';
								echo $permissions[$i]['DESCRIPTION']; 
								echo '</i></td>';

							}
							 echo '</tr>';
							?>
								</tbody>
							</table>
						</div>

					<input type="text"  value="<?php
					if (isset($roleid)) {
						echo $roleid;
					}
					?>" class="form-control hidden" name="roleid">
				</div>
				<div class="panel-footer">
					<button type="submit"  name="save" class="btn btn-success btn-sm pull-left">
						<i class="fa fa-check"></i> Submit
					</button>
					<a class="btn btn-primary btn-sm pull-right" href="<?php echo base_url(); ?>role/rolelist">
						<i class="fa fa-reply"></i> Cancel
					</a> 
					<div class="clearfix"></div>  
				</div>      
			</div>
		</form>
	</div>
</div>