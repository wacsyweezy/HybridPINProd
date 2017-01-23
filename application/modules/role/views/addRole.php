<link rel="stylesheet" href="<?php print base_url()?>assets/vendor/select2-3.5.2/select2.css" />
<link rel="stylesheet" href="<?php print base_url()?>assets/vendor/select2-bootstrap/select2-bootstrap.css" />
<div class="small-header">
    <div class="hpanel">
        <div class="panel-body">
            <div id="hbreadcrumb" class="pull-right">
                <ol class="hbreadcrumb breadcrumb">
                    <li><a href="<?php echo base_url(); ?>">Dashboard</a></li>
                    <li>
                        <span>Role Management</span>
                    </li>
                    <li class="active">
                        <span>Add New Role </span>
                    </li>
                </ol>
            </div>
            <h2 class="font-light m-b-xs">
                Role Management
            </h2>
            <small>New Role</small>
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
					<span class="pull-left"><i class="fa fa-plus"></i> Add New Role</span>
					<a class="pull-right" href="<?php echo base_url(); ?>role/rolelist"><i class="fa fa-th"></i> List All Role</a>
					<div class="clearfix"></div>
				</div>
                <div class="panel-body">
                    <?php echo form_open('role/submit', array('class' => 'form-horizontal form-label-left', 'id'=>'demo-form2')); ?>
						<div class="form-group">
							<label class="control-label col-md-3 col-sm-3 col-xs-12 text-right">Role Name</label>
							<div class="col-md-6 col-sm-6 col-xs-12">
								 <input type="text" placeholder="Role name" value="<?php if (isset($roleid)) {
								echo $rolename; } ?>" class="form-control" name="rolename">
							</div>
						</div>
						<div class="form-group">
							<label class="control-label col-md-3 col-sm-3 col-xs-12 text-right">Access Type</label>
							<div class="col-md-6 col-sm-6 col-xs-12">
								 <select name="usertype" class="js-source-states form-control" style="width: 100%">
									  <option value="">Select User Type</option>
									<option value="0"
									<?php if (isset($roleid)){ if ($usertype == "0"){ ?>selected="selected"<?php } } ?>>
										Internal  </option>
									<option value="1"
									<?php if (isset($roleid)){ if ($usertype == "1"){ ?>selected="selected"<?php } } ?>>
										External</option>
								</select>
							</div>
						</div>
                        <input type="hidden"  value="<?php if (isset($roleid)) {
                                echo $roleid;
						} ?>" class="form-control" name="roleid">					
						<div class="form-group">
							<div class="col-md-6 col-sm-6 col-xs-12 col-md-offset-3">
								<a href="<?php echo base_url(); ?>role/rolelist" class="btn btn-warning"><i class="fa fa-history"></i> Cancel</a>&nbsp; &nbsp;
								<button type="submit" class="btn btn-success"><i class="fa fa-check"></i> Add Role</button>
							</div>
						</div>   
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>