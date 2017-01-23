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
            <small>List of All Menu</small>
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
					<span class="pull-left"><i class="fa fa-th"></i> All Menu</span>
					<a class="pull-right" href="<?php echo base_url(); ?>mainmenu"><i class="fa fa-plus"></i> Add New Menu</a>
					<div class="clearfix"></div>
				</div>
                <div class="panel-body">
                    <table id="example2" class="table table-striped table-bordered table-hover">
                        <thead>
                            <tr>
                                <th>S/N</th>
                                <th>MAIN MENU</th>
                                <th>DESCRIPTION</th>
                            </tr>
                        </thead>
                        <tbody> 
                            <?php $sn = 0;
                            foreach ($record as $rec) {
                                ?>
                                <tr> 
                                    <td><?php echo ++$sn; ?></td> 
                                    <td><?php echo $rec->MAIN_MENU_NAME; ?></td>
                                     <td><?php echo $rec->DESCRIPTION; ?></td>
                                    <td class="text-center">
                                        <div class="btn-group">
                                            <?php echo anchor('mainmenu/set_up/' . $rec->MAIN_MENU_ID, "&nbsp", array('class' => 'btn btn-xs btn-default fa fa-pencil', 'title' => 'Edit ' . $rec->shortform . ' details')); ?>
                                     <?php echo anchor('mainmenu/delete/' . $rec->MAIN_MENU_ID, "&nbsp", array('class' => 'btn btn-xs btn-danger fa fa-trash', 'onclick' => "return confirm('Are you sure want to delete this mainmenu?')")); ?>
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