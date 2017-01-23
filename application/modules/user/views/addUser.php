<link rel="stylesheet" href="<?php print base_url()?>assets/vendor/select2-3.5.2/select2.css" />
<link rel="stylesheet" href="<?php print base_url()?>assets/vendor/select2-bootstrap/select2-bootstrap.css" />
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
            <small>User Registration</small>
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
					<span class="pull-left"><i class="fa fa-user"></i> User Registration</span>
					<a class="pull-right" href="<?php echo base_url(); ?>user/userlist"><i class="fa fa-th"></i> All User List</a>
					<div class="clearfix"></div>
				</div>
                <div class="panel-body">
                    <?php echo form_open('user/submit', array('class' => 'form-horizontal form-label-left', 'id'=>'demo-form2')); ?>
						<div class="form-group">
							<label class="control-label col-md-3 col-sm-3 col-xs-12 text-right">Display Name</label>
							<div class="col-md-6 col-sm-6 col-xs-12">
								<input type="text" placeholder="Enter Display Name" value="<?php if (isset($userid)) {
                            echo $displaynames;
                        } ?>" class="form-control" name="displayname" />
							</div>
						</div>
						<div class="form-group">
							<label class="control-label col-md-3 col-sm-3 col-xs-12 text-right">First Name</label>
							<div class="col-md-6 col-sm-6 col-xs-12">
								<input type="text" placeholder="Enter first name" onkeyup="this.value=this.value.replace(/[^a-z]/g,'');" value="<?php if (isset($userid)) {
                            echo $firstname;
                        } ?>" class="form-control" name="firstname" />
							</div>
						</div>
						<div class="form-group">
							<label class="control-label col-md-3 col-sm-3 col-xs-12 text-right">Last Name</label>
							<div class="col-md-6 col-sm-6 col-xs-12">
								<input type="text" placeholder="Enter last name" onkeyup="this.value=this.value.replace(/[^a-z]/g,'');" value="<?php if (isset($userid)) {
                            echo $lastname;
                        } ?>" class="form-control" name="lastname" />
							</div>
						</div>
						<div class="form-group">
							<label class="control-label col-md-3 col-sm-3 col-xs-12 text-right">Email Address</label>
							<div class="col-md-6 col-sm-6 col-xs-12">
								<input type="text" placeholder="Enter email address" value="<?php if (isset($userid)) {
                            echo $emailaddress;
                        } ?>" class="form-control" name="emailaddress" />
							</div>
						</div>
						<div class="form-group">
							<label class="control-label col-md-3 col-sm-3 col-xs-12 text-right">User Role</label>
							<div class="col-md-6 col-sm-6 col-xs-12">
								<select class="js-source-states form-control" style="width: 100%" name="roleid" id="roleid">
								<option value="">Select User Role</option>
								<?php
								foreach ($roles as $rec) {
								 ?>
								   <option value="<?php echo $rec->ROLE_ID.'|'.$rec->USERTYPE; ?>"
												 <?php     if (isset($userid)) {
														if ($rec->ROLE_ID == $roleid) {
															?>selected="selected"
												 <?php  }} ?>> 
											   <?php
											echo $rec->ROLE_NAME  ?></option>;
							   <?php
								   }
								?>
								</select>
							</div>
						</div>
<?php
	if(isset($userid) && strlen($usercode)>3) {
?>
<div class="form-group">
							<label class="control-label col-md-3 col-sm-3 col-xs-12 text-right">User Code</label>
							<div class="col-md-6 col-sm-6 col-xs-12">
								<input type="text" placeholder="Enter Dealer Code / Retail Code etc.." 
                                value="<?php if (isset($userid)) {
                                echo $usercode;
                                } ?>" class="form-control" name="usercode" />
							</div>
						</div>
<?php
	}
	else {
?>
<div class="form-group hidden" id="external">
							<label class="control-label col-md-3 col-sm-3 col-xs-12 text-right">User Code</label>
							<div class="col-md-6 col-sm-6 col-xs-12">
								<input type="text" placeholder="Enter Dealer Code / Retail Code etc.." 
                                value="<?php if (isset($userid)) {
                                echo $usercode;
                                } ?>" class="form-control" name="usercode" />
							</div>
						</div>

<?php
	}
?>
						<input type="hidden"  value="<?php if (isset($userid)) {
                            echo $userid;
                        } ?>" class="form-control" name="userid" />
						<div class="form-group">
							<div class="col-md-6 col-sm-6 col-xs-12 col-md-offset-3">
								<a href="<?php echo base_url(); ?>user/userlist" class="btn btn-warning"><i class="fa fa-history"></i> Cancel</a> &nbsp; &nbsp;
								<?php
if(isset($userid)) {
	echo '<button type="submit" class="btn btn-success"><i class="fa fa-check"></i> Update</button>';
}
else {
	echo '<button type="submit" class="btn btn-success"><i class="fa fa-check"></i> Add User</button>';
}
								?>
							</div>
						</div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
filter = document.getElementById('roleid');
filter.onchange = function() {
    var role = this.value.split("|");
    if(role[1]==1) {
        document.getElementById("external").classList.remove("hidden");
    }
    else {
        document.getElementById("external").classList.add("hidden");
    }
}
</script>