<div class="small-header">
    <div class="hpanel">
        <div class="panel-body">
            <div id="hbreadcrumb" class="pull-right">
                <ol class="hbreadcrumb breadcrumb">
                    <li><a href="index.html">Dashboard</a></li>
                    <li>
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
<div class="content animate-panel">



    <div class="row">
        <div class="col-lg-12">
            <!--<div class="hpanel">
                <div class="panel-heading">
                    <div class="panel-tools">
                        <a class="showhide"><i class="fa fa-chevron-up"></i></a>
                        <a class="closebox"><i class="fa fa-times"></i></a>
                    </div>
                    Basic example with Ajax (json file)
                </div>
                <div class="panel-body">
                    <table id="example1" class="table table-striped table-bordered table-hover" width="100%">
                        <thead>
                        <tr>
                            <th>Name</th>
                            <th>Position</th>
                            <th>Office</th>
                            <th width="15%">Age</th>
                            <th width="15%">Start date</th>
                            <th width="15%">Salary</th>
                        </tr>
                        </thead>
                    </table>

                </div>-->
        </div>

        <div class="hpanel">
            <!--<div class="panel-heading">
                <div class="panel-tools">
                    <a class="showhide"><i class="fa fa-chevron-up"></i></a>
                    <a class="closebox"><i class="fa fa-times"></i></a>
                </div>
                Standard table
            </div>-->
            <div class="panel-body">
                <table id="example2" class="table table-striped table-bordered table-hover">
                    <thead>
                        <tr>
                            <th>S/N</th>
                            <th>USER-ROLE</th>
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
                                        <?php echo anchor('role/set_up/' . $rec->ROLE_ID, "&nbsp", array('class' => 'btn btn-sm btn-default icon-pencil', 'title' => 'Edit ' . $rec->shortform . ' details')); ?>
                                 <?php echo anchor('role/delete/' . $rec->ROLE_ID, "&nbsp", array('class' => 'btn btn-sm btn-danger icon-trash', 'onclick' => "return confirm('Are you sure want to delete this role?')")); ?>
                                    </div>
                                </td>
                            </tr> 
                            <?php } ?>
                    </tbody> 
                </table>

            </div>
        </div>
    </div>
 <tbody> 
                      
</div>



