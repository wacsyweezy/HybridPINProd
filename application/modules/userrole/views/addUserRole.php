<div class="small-header">
    <div class="hpanel">
        <div class="panel-body">
            <div id="hbreadcrumb" class="pull-right">
                <ol class="hbreadcrumb breadcrumb">
                    <li><a href="index.html">Dashboard</a></li>
                    <li>
                        <?php echo anchor('rolepermission/rolepermissionlist', 'RolePermission List', array('class' => 'label label-primary')); ?>  

                        <span>Forms</span>
                    </li>
                    <li class="active">
                        <span>Validation </span>
                    </li>
                </ol>
            </div>
            <h2 class="font-light m-b-xs">
                Validation
            </h2>
            <small>Build a form with validation functionality</small>
        </div>
    </div>
</div>

<div class="content">

    <div class="row">
        <div class="col-lg-8">
            <div class="hpanel">
                <!--<div class="panel-heading">
                      <div class="panel-tools">
                         <a class="showhide"><i class="fa fa-chevron-up"></i></a>
                         <a class="closebox"><i class="fa fa-times"></i></a>
                     </div>
                     jQuery Validation Plugin
                 </div>-->
                <div class="panel-body">
                  <!--  <p>
                        The jQuery Validation Plugin provides drop-in validation for your existing forms, while making all kinds of customizations to fit your application really easy.
                    </p>-->

                    <?php echo form_open('rolepermission/submit', array('class' => '')); ?>
                    <p style="color:red; size: 9px" id="msg-container">
                        <?php if (isset($msg)) {
                            echo $msg;
                        } ?>
                    </p>
                    <div class="form-group">
                        <label>Roles </label> 
                        <select   class="form-control" name="role">
                            <option value="">Select Role</option>
                            <?php
                            foreach ($roles as $rec) {
                                echo '<option value="' . $rec->ROLE_ID . '">' . $rec->ROLE_NAME . '</option>';
                            }
                            ?>
                        </select>
                    </div>
                    
                    <div class="form-group">
			<label>Permissions :</label>
                        <select  multiple="multiple" id="example-post" style="z-index:1000000"  class="form-control" name="permissions[]" size="50">
				<?php
						foreach ($permissions as $rec) {
							echo '<option value="' . $rec->PERMISSION_ID . '">' . $rec->DISPLAY_NAME . '</option>';
						}
						?>
					</select>
				</div>

                    <div class="form-group">
                        <input type="text"  value="<?php if (isset($rolepermissionid)) { echo $rolepermissionid; } ?>" class="form-control" name="rolepermissionid">
                    </div>
                <!-- <div class="form-group"><label>Password</label> <input type="password" placeholder="Password" class="form-control" name="password"></div>
                <div class="form-group"><label>Url</label> <input type="text" placeholder="Enter email" class="form-control" name="url"></div>
                <div class="form-group"><label>Number</label> <input type="text" placeholder="Enter email" class="form-control" name="number"></div>
                <div class="form-group"><label>MaxLength</label> <input type="text" placeholder="Enter email" class="form-control" name="max"></div>
                    -->
                    <div>
                        <button type="submit"  name="save" class="btn btn-success btn-small pull-left">Submit</button>
<?php echo anchor('rolepermission/index', 'Cancel', array('class' => 'btn btn-default btn-small pull-right')); ?>  
                        <div class="clearfix"></div>  </div>
                    </form>

                </div>
            </div>
        </div>

    </div>
</div>