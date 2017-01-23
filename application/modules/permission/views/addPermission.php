<link rel="stylesheet" href="<?php print base_url()?>assets/vendor/select2-3.5.2/select2.css" />
<link rel="stylesheet" href="<?php print base_url()?>assets/vendor/select2-bootstrap/select2-bootstrap.css" />
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
                        <span>New Access </span>
                    </li>
                </ol>
            </div>
            <h2 class="font-light m-b-xs">
                Access Management
            </h2>
            <small>Add New Access</small>
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
					<span class="pull-left"><i class="fa fa-exchange"></i> Add New Access</span>
					<a class="pull-right" href="<?php echo base_url(); ?>permission/permissionlist"><i class="fa fa-th"></i> List All Access</a>
					<div class="clearfix"></div>
				</div>			
                <div class="panel-body">
                    <?php echo form_open('permission/submit', array('class' => 'form-horizontal form-label-left', 'id'=>'demo-form2')); ?>
						<div class="form-group">
							<label class="control-label col-md-3 col-sm-3 col-xs-12 text-right">Select Main Menu</label>
							<div class="col-md-6 col-sm-6 col-xs-12">
							<select class="js-source-states form-control" style="width: 100%" name="mainmenuid">
									<option value="">Select menu</option>
									<?php
									foreach ($mainmenurec as $rec) {
									 ?>
									 <option value="<?php echo $rec->MAIN_MENU_ID  ?>"
													 <?php     if (isset($permissionid)) {
															if ($rec->MAIN_MENU_ID == $mainmenuid) {
																?>selected="selected"
													 <?php  }} ?>> 
												   <?php
									  echo $rec->MAIN_MENU_NAME  ?></option>
								   <?php
									   }
									?>
								</select>
							</div>
						</div>
						<div class="form-group">
							<label class="control-label col-md-3 col-sm-3 col-xs-12 text-right">Controller Name</label>
							<div class="col-md-6 col-sm-6 col-xs-12">
								<input type="text" placeholder="Enter Controller Name" value="<?php
								if (isset($permissionid)) {
									echo $controllername;
								}
								?>" class="form-control" name="controllername">
								</div>
						</div>
						<div class="form-group">
							<label class="control-label col-md-3 col-sm-3 col-xs-12 text-right">Display Name</label>
							<div class="col-md-6 col-sm-6 col-xs-12">
								<input type="text" placeholder="Enter Display Name" value="<?php
								if (isset($permissionid)) {
									echo $displayname;
								}
								?>" class="form-control" name="displayname">
							</div>
						</div>
						<div class="form-group">
							<label class="control-label col-md-3 col-sm-3 col-xs-12 text-right">Action Name</label>
							<div class="col-md-6 col-sm-6 col-xs-12">
								<input type="text" placeholder="Enter Action Name" value="<?php
								if (isset($permissionid)) {
									echo $actionname;
								}
								?>" class="form-control" name="actionname">
							</div>
						</div>
						<div class="form-group">
							<label class="control-label col-md-3 col-sm-3 col-xs-12 text-right">Description</label>
							<div class="col-md-6 col-sm-6 col-xs-12">
								<input type="text" placeholder="Enter Description"  value="<?php
								if (isset($permissionid)) {
									echo $description;
								}
								?>" class="form-control" name="description">
							</div>
						</div>
					   
						<input type="hidden"  value="<?php
						if (isset($permissionid)) {
							echo $permissionid;
						}
						?>" class="form-control" name="permissionid">
						<div class="form-group">
							<div class="col-md-6 col-sm-6 col-xs-12 col-md-offset-3">
								<a href="<?php echo base_url(); ?>permission/index" class="btn btn-warning"><i class="fa fa-history"></i> Cancel</a>
								<button type="submit" class="btn btn-success"><i class="fa fa-check"></i> Add Access</button>
							</div>
						</div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>