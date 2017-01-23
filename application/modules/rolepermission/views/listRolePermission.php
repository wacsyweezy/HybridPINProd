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
                        <span>Role List </span>
                    </li>
                </ol>
            </div>
            <h2 class="font-light m-b-xs">
                Role System
            </h2>
            <small>All Role</small>
        </div>
    </div>
</div>
<div class="content animate-panel">



    <div class="row">
        <div class="col-lg-12">

        <div class="hpanel">
            <div class="panel-body">
                <table id="example2" class="table table-striped table-bordered table-hover">
                    <thead>
                        <tr>
                            <th>S/N</th>
                            <th>ROLE</th>
                        </tr>
                    </thead>
                    <tbody> 
                        <?php $sn = 0;
                        foreach ($record as $rec) {
                            ?>
                            <tr> 
                                <td><?php echo ++$sn; ?></td> 
                                <td><?php echo $rec->ROLE_NAME; ?></td>
                                <td class="text-center">
                                    <div class="btn-group">
                                 <?php echo anchor('rolepermission/set_up/' . $rec->ROLE_ID, "&nbsp", array('class' => 'btn btn-xs btn-default fa fa-pencil', 'title' => 'Edit ' . $rec->shortform . ' details')); ?>
                                 <?php echo anchor('rolepermission/delete/' . $rec->ROLE_ID, "&nbsp", array('class' => 'btn btn-xs btn-danger fa fa-trash', 'onclick' => "return confirm('Are you sure want to delete this role?')")); ?>
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



