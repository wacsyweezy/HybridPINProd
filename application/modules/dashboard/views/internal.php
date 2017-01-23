<div class="content animate-panel">
	<div class="row">
        <div class="col-lg-12 text-center m-t-md">
            <h2>
                Welcome <?php print $_SESSION['displayname']; ?>
            </h2>
            <p>
                Hybrid Management System Dashboard
            </p>
        </div>
    </div>
</div>
<div class="col-lg-12">
    <div class="hpanel">
        <div class="panel-body">
            <div class="row">
                <div class="col-md-4 text-center">
                    <div class="small">
                        <i class="fa fa-user"></i> ALL USER
                    </div>
                    <div>
                        <h1 class="font-extra-bold m-t-xl m-b-xs">
                            <?php echo number_format($all_user); ?>
                        </h1>
                        <small>Total number of all system user</small>
                    </div>
                </div>
				<div class="col-md-4 text-center">
                    <div class="small">
                        <i class="fa fa-user"></i> INTERNAL USER
                    </div>
                    <div>
                        <h1 class="font-extra-bold m-t-xl m-b-xs">
                            <?php echo number_format($internal_user); ?>
                        </h1>
                        <small>Total number of all internal system user</small>
                    </div>
                </div>
				<div class="col-md-4 text-center">
                    <div class="small">
                        <i class="fa fa-user"></i> EXTERNAL USER
                    </div>
                    <div>
                        <h1 class="font-extra-bold m-t-xl m-b-xs">
                            <?php echo number_format($external_user); ?>
                        </h1>
                        <small>Total number of all external system user</small>
                    </div>
                </div>
            </div>
        </div>
        <div class="panel-footer">
            Last Updated: <b><?php echo date('jS ').' of '. date('F, Y'); ?></b>
        </div>
    </div>
</div>