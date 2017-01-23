<div class="small-header">
    <div class="hpanel">
        <div class="panel-body">
            <div id="hbreadcrumb" class="pull-right">
                <ol class="hbreadcrumb breadcrumb">
                    <li><a href="<?php echo base_url(); ?>">Dashboard</a></li>
                    <li>
                        <span>Menu Manamenent</span>
                    </li>
                    <li class="active">
                        <span>All Menu </span>
                    </li>
                </ol>
            </div>
            <h2 class="font-light m-b-xs">
                Menu Manamenent
            </h2>
            <small>Add New Menu</small>
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
					<span class="pull-left"><i class="fa fa-plus"></i> Add New Menu</span>
					<a class="pull-right" href="<?php echo base_url(); ?>mainmenu/mainmenulist"><i class="fa fa-th"></i> List All Menu</a>
					<div class="clearfix"></div>
				</div>
                <div class="panel-body">    
                    <?php echo form_open('mainmenu/submit', array('class' => 'form-horizontal form-label-left', 'id'=>'demo-form2')); ?>
                        <div class="form-group">
							<label class="control-label col-md-3 col-sm-3 col-xs-12 text-right">Menu Name</label>
							<div class="col-md-6 col-sm-6 col-xs-12">
								 <input type="text" placeholder="Enter Main Menu" 
                                 value="<?php if (isset($mainmenuid)) { echo $mainmenuname; } ?>" 
                                 class="form-control" name="mainmenuname" />
							</div>
						</div>
                        <div class="form-group">
							<label class="control-label col-md-3 col-sm-3 col-xs-12 text-right">Menu Short Name</label>
							<div class="col-md-6 col-sm-6 col-xs-12">
								 <input type="text" placeholder="Enter Short Name" 
                                 value="<?php if (isset($mainmenuid)) { echo $shortname; } ?>" 
                                 class="form-control" name="shortname" />
							</div>
						</div>
                        <div class="form-group">
							<label class="control-label col-md-3 col-sm-3 col-xs-12 text-right">Menu Description</label>
							<div class="col-md-6 col-sm-6 col-xs-12">
								 <input type="text" placeholder="Enter Description" 
                                 value="<?php if (isset($mainmenuid)) { echo $description; } ?>" 
                                 class="form-control" name="description" />
                            
                            </div>
						</div>
                        <input type="hidden"  value="<?php if (isset($mainmenuid)) { echo $mainmenuid; } ?>" 
                        class="form-control" name="mainmenuid" />
                        <div class="form-group">
							<div class="col-md-6 col-sm-6 col-xs-12 col-md-offset-3">
								<a href="<?php echo base_url(); ?>mainmenu/index" class="btn btn-warning"><i class="fa fa-history"></i> Cancel</a>&nbsp; &nbsp;
								<button type="submit" class="btn btn-success"><i class="fa fa-check"></i> Add Menu</button>
							</div>
						</div>
                        <div class="clearfix"></div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>