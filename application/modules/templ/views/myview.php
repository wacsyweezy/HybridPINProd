<?php
    if(
	isset($_SESSION['username']) && isset($_SESSION['CORE_APP']) && 
        in_array($_ci_vars['module'], $_SESSION['CORE_APP']) 
        && 
        in_array($this->router->fetch_method(), $_SESSION['ALL_APP'])
		|| 
		$_ci_vars['module'] == "ajax"
		|| 
		$_ci_vars['module'] == "permission"
		|| 
		$_ci_vars['module'] == "role"
		|| 
		$_ci_vars['module'] == "user"
        ||
        $this->router->fetch_method() == "login"
        ||
        $this->router->fetch_method() == "index"
        ||
        $this->router->fetch_method() == "set_up"
        ||
        $this->router->fetch_method() == "delete"
        ||
        $this->router->fetch_method() == "submit"
		||
        $this->router->fetch_method() == "addItem"
		||
        $this->router->fetch_method() == "removeItem"
        ||
        $this->router->fetch_method() == "vieworder"
		||
        $this->router->fetch_method() == "generateDealerReport"
		||
        $this->router->fetch_method() == "addpartner"
		||
        $this->router->fetch_method() == "transactions"
		||
        $this->router->fetch_method() == "addStock"
		||
        $this->router->fetch_method() == "removeStock"
		||
        $this->router->fetch_method() == "additemrule"
		||
        $this->router->fetch_method() == "adddealerrule"
		||
        $this->router->fetch_method() == "hybrid"
		||
        $this->router->fetch_method() == "itemremove"
		||
        $this->router->fetch_method() == "removedealer"
		||
        $this->router->fetch_method() == "updateDefault"
		||
        $this->router->fetch_method() == "engine"  
		||
        $this->router->fetch_method() == "generateOnlineReport"
    ) {
?>
<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <!-- Page title -->
        <title><?php print $title; ?></title>
        <link rel="shortcut icon" type="image/ico" href="<?php echo base_url(); ?>assets/images/favicon.ico" />

        <!-- Vendor styles -->
        <link rel="stylesheet" href="<?php echo base_url(); ?>assets/vendor/fontawesome/css/font-awesome.css" />
        <link rel="stylesheet" href="<?php echo base_url(); ?>assets/vendor/metisMenu/dist/metisMenu.css" />
        <link rel="stylesheet" href="<?php echo base_url(); ?>assets/vendor/animate.css/animate.css" />
        <link rel="stylesheet" href="<?php echo base_url(); ?>assets/vendor/bootstrap/dist/css/bootstrap.css" />
        <link rel="stylesheet" href="<?php echo base_url(); ?>assets/vendor/sweetalert/lib/sweet-alert.css" />

        <!-- App styles -->
        <link rel="stylesheet" href="<?php echo base_url(); ?>assets/fonts/pe-icon-7-stroke/css/pe-icon-7-stroke.css" />
        <link rel="stylesheet" href="<?php echo base_url(); ?>assets/fonts/pe-icon-7-stroke/css/helper.css" />
        <link rel="stylesheet" href="<?php echo base_url(); ?>assets/styles/style.css">

        <link rel="stylesheet" href="<?php echo base_url(); ?>assets/vendor/datatables.net-bs/css/dataTables.bootstrap.min.css" />

    </head>
    <body class="fixed-navbar fixed-sidebar">
        <!-- Simple splash screen-->
        <div class="splash"> 
            <div class="color-line"></div>
            <div class="splash-title">
                <img src="<?php print base_url(); ?>/assets/images/logo.jpg" />
                <p>Special Admin Software for Hybrid Management System </p>
                <div class="spinner"> 
                    <div class="rect1"></div> 
                    <div class="rect2"></div> 
                    <div class="rect3"></div> 
                    <div class="rect4"></div> 
                    <div class="rect5"></div> 
                </div> 
            </div> 
        </div>
        <!-- End Splace Screen -->
        <!--[if lt IE 7]>
        <p class="alert alert-danger">
                You are using an <strong>outdated</strong> browser. 
                Please <a href="http://browsehappy.com/">upgrade your browser</a> to improve your experience.
        </p>
        <![endif]-->
        <!-- Header -->
        <div id="header">
            <div class="color-line"></div>
            <div id="logo" class="dark-version" style="padding:0px; ">
                <img src="<?php echo base_url(); ?>/assets/images/logo.png" class="img img-responsive"/>
            </div>
            <nav role="navigation">
                <div class="header-link hide-menu">
                    <i class="fa fa-bars"></i>
                </div>
                <div class="small-logo" style="padding:0px;">
                    <img src="<?php echo base_url(); ?>/assets/images/logo.jpg" class="img img-responsive"/>
                </div>
                <!-- <form role="search" class="navbar-form-custom" method="post" action="index.html#">
                    <div class="form-group">
                        <input type="text" placeholder="Search something special" class="form-control" name="search">
                    </div>
                </form> -->
                <div class="mobile-menu">
                    <button type="button" class="navbar-toggle mobile-menu-toggle" data-toggle="collapse" data-target="#mobile-collapse">
                        <i class="fa fa-chevron-down"></i>
                    </button>
                    <div class="collapse mobile-navbar" id="mobile-collapse">
                        <ul class="nav navbar-nav">
                            <li>
                                <a class="" href="<?php echo base_url(); ?>auth/logout">Logout</a>
                            </li>
                        </ul>
                    </div>
                </div>
                <div class="navbar-right">
                    <ul class="nav navbar-nav no-borders">
                        <!-- <li class="dropdown">
                            <a class="dropdown-toggle" href="#" data-toggle="dropdown">
                                <i class="pe-7s-keypad"></i>
                            </a>
                            <div class="dropdown-menu hdropdown bigmenu animated flipInX">
                                <table>
                                    <tbody>
                                        <tr>
                                            <td>
                                                <a href="projects.html">
                                                    <i class="pe pe-7s-portfolio text-info"></i>
                                                    <h5>Projects</h5>
                                                </a>
                                            </td>
                                            <td>
                                                <a href="mailbox.html">
                                                    <i class="pe pe-7s-mail text-warning"></i>
                                                    <h5>Email</h5>
                                                </a>
                                            </td>
                                            <td>
                                                <a href="contacts.html">
                                                    <i class="pe pe-7s-users text-success"></i>
                                                    <h5>Contacts</h5>
                                                </a>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>
                                                <a href="forum.html">
                                                    <i class="pe pe-7s-comment text-info"></i>
                                                    <h5>Forum</h5>
                                                </a>
                                            </td>
                                            <td>
                                                <a href="analytics.html">
                                                    <i class="pe pe-7s-graph1 text-danger"></i>
                                                    <h5>Analytics</h5>
                                                </a>
                                            </td>
                                            <td>
                                                <a href="file_manager.html">
                                                    <i class="pe pe-7s-box1 text-success"></i>
                                                    <h5>Files</h5>
                                                </a>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </li> -->
                        <li class="dropdown">
                            <a href="<?php echo base_url(); ?>auth/logout">
                                <i class="pe-7s-upload pe-rotate-90"></i>
                            </a>
                        </li>
                    </ul>
                </div>
            </nav>
        </div>
        <aside id="menu" style="padding-top:0px;">
            <div id="navigation">
                <div class="profile-picture" style="padding:10px 10px;">
                    <a href="#">
                        <img src="<?php print base_url(); ?>/assets/images/user.png" class="img-circle m-b" alt="logo">
                    </a>
                    <div class="stats-label text-color">
                        <span class="font-uppercase"><?php echo $_SESSION[displayname]; ?></span>
                    </div>
                </div>
                <h4 style="padding:5px; font-size:10px; font-weight:bold; margin:0px; background:#212121; color:#BCD50B; border-top:2px solid #BCD50B">NAVIGATION 
                    <i style="font-size:14px;" class="pull-right fa fa-laptop"></i> </h4>
                <ul class="nav" id="side-menu">
					<?php  	
						foreach ($_SESSION[AAA] as $value) { ?>
						<li>
							<a href="#"><span class="nav-label"><i class="fa fa-desktop"></i> <?php  print $value; ?></span><span class="fa arrow"></span></a>
							<ul class="nav nav-second-level">
						  <?php foreach ($_SESSION[BBB] as $value1) { 
									$str = explode(":", $value1) ;
									$menu = $str[3];
									if($value==$menu) {
										$actn = $str[0];
										$displayname = $str[1];
										$ctrller = $str[2];
										?>
											<li> <?php echo anchor("$ctrller/$actn", "$displayname", array('class' => '', 'style' => '')); ?> </li>
										<?php
									}
                           } ?>
							</ul>
						</li>
					<?php } ?>
			<li>
                      		<a href="<?php print base_url(); ?>auth/logout"> <span class="nav-label"><i class="fa fa-power-off"></i> Logout</span></a>
                    	</li>
                </ul>
            </div>
        </aside>

        <!-- Main Wrapper -->
        <div id="wrapper">
            <?php
            $this->load->view($module . '/' . $view_file);
            ?>

            <!-- Footer-->
            <footer class="footer">
                <span class="pull-right">
                    App Title V1.0 - Powered by <a href="https://bluechiptech.biz">Bluechip Technologies</a>
                </span>
                Company <?php print date('Y'); ?>
            </footer>
        </div>
        <script src="<?php print base_url(); ?>assets/vendor/jquery/dist/jquery.min.js"></script>
        <script src="<?php print base_url(); ?>assets/vendor/jquery-ui/jquery-ui.min.js"></script>
        <script src="<?php print base_url(); ?>assets/vendor/slimScroll/jquery.slimscroll.min.js"></script>
        <script src="<?php print base_url(); ?>assets/vendor/bootstrap/dist/js/bootstrap.min.js"></script>
        <script src="<?php print base_url(); ?>assets/vendor/metisMenu/dist/metisMenu.min.js"></script>
        <script src="<?php print base_url(); ?>assets/vendor/iCheck/icheck.min.js"></script>
        <script src="<?php print base_url(); ?>assets/vendor/peity/jquery.peity.min.js"></script>
        <script src="<?php print base_url(); ?>assets/vendor/sparkline/index.js"></script>
        <script src="<?php print base_url(); ?>assets/vendor/chartjs/Chart.min.js"></script>
		<script src="<?php print base_url(); ?>assets/vendor/d3/d3.min.js"></script>
		<script src="<?php print base_url(); ?>assets/vendor/c3/c3.min.js"></script>
        <!-- App scripts -->
        <script src="<?php print base_url(); ?>assets/scripts/homer.js"></script>
        <script src="<?php print base_url(); ?>assets/scripts/charts.js"></script>
        <script src="<?php print base_url(); ?>assets/vendor/select2-3.5.2/select2.min.js"></script>
		<script src="<?php print base_url(); ?>assets/vendor/bootstrap-datepicker-master/dist/js/bootstrap-datepicker.min.js"></script>
		
		<script src="<?php print base_url(); ?>assets/vendor/chartjs/Chart.min.js"></script>
		<script>
			$(document).ready(function() {
				$(".js-source-states").select2();
				$('.date_picker').datepicker();	
			});
		</script>
        <?php
        /** FOR ORDER MANAGEMENT MODULE [Added by Wasiu]
         * * This check if the present view is the order module, then it includes the form select plugin 
         * * and the ajax script to manage the cart and order processing 
         * */
		 if (isset($_ci_vars['module']) && $_ci_vars['module'] == "dashboard") {
          ?>
		  <script>
			$(document).ready(function() {
				/*
				var chart_date = $('input.chart_date').val();
				var get_items = $('input#items').val();
				var items = get_items.split('|');
				var all_data = [];
				var myData = {};
				var stock_data = [];
				
				for(i=0; i<items.length-1; i++) {				
					var stock = items[i];
					var datas = $('input.'+stock).val();
					stock_data.push(datas);
				}
				var array_date = [];
				
				var stocks = $.makeArray(stock_data);

				for(i=0; i<stocks.length; i++) {
					var tempArray = stocks[i];
					var newTempArray = tempArray.split(',');
					var item = newTempArray[0];
					for(j=1; j<newTempArray.length-1; j++) {
						all_data.push(newTempArray[j]);
					}
					myData[item+''] = all_data;
				}
				
				array_date = chart_date.split(",");
				
				var lineOptions = c3.generate({
					bindto: '#lineOptions',
					data: {
						columns:[
							myData
						]
					},
					axis: {
						x: {
							type: 'category',
							tick: {
								rotate: -45,
								multiline: false
							},
							height: 60,
							categories: array_date
						}
					}
				});  */
				
		  /*
		  $(document).ready(function() {
			  
			var get_items = $('input#items').val();

			var stock_detail = [];
			var main_data = [];
			var date_detail = [];
			var main_date = [];
			var big_data = [];
			var my_data = "";
			var items = get_items.split('|');
			
			function cleanArray(actual) {
				var newArray = new Array();
				for (var i = 0; i < actual.length; i++) {
					if (actual[i]) {
						newArray.push(actual[i]);
					}
				}
				return newArray.sort();
			}
			
			for(i=0; i<items.length; i++) {
				var stock = items[i].replace(' ', '_');	
				
				var datas = $('input.'+stock).attr('all_data').split('|');
				
				for(k=0; k<datas.length; k++) {
					main_data.push(datas[k]);
				}
				
				var dates = $('input.'+stock).attr('all_date').split('|');
				
				for(j=0; j<dates.length; j++) {
					var dates = $('input.'+stock).attr('all_date').split('|');
					if($.inArray(dates[j], main_date) !== -1) {
						//
					}
					else {
						main_date.push(dates[j]);
					}
				}
				big_data.push(main_data);
			}
			
			
			main_date = cleanArray(main_date);
			
			my_data = big_data[big_data.length-1].toString();	
				
			var pure_data = my_data.split(',,');
			
			var modData = [];

			//alert(modData);
			//var pure_data = my_data.split(",,");
			
			var my_data = JSON.stringify(pure_data);

			var lineOptions = c3.generate({
				bindto: '#lineOptions',
				data: {
					columns: [
						[pure_data[1]]
					]
				},
				axis: {
					x: {
						type: 'category',
						tick: {
							rotate: -45,
							multiline: false
						},
						height: 60,
						categories: [
							main_date.forEach(function(element) {
								''+element+'';
							})
						]
					}
				}
			});	*/		
		})
		  </script>
		  <?php
        }
        if (isset($_ci_vars['module']) && $_ci_vars['module'] == "order") {
            echo '
		<script src="' . base_url() . 'assets/vendor/sweetalert/lib/sweet-alert.min.js"></script>
		<script src="' . base_url() . 'assets/bluechip/cart.js"></script>
		<script src="' . base_url() . 'assets/vendor/datatables/media/js/jquery.dataTables.min.js"></script>
		<script src="' . base_url() . 'assets/vendor/datatables.net-bs/js/dataTables.bootstrap.min.js"></script>
<!-- DataTables buttons scripts -->
		<script src="' . base_url() . 'assets/vendor/pdfmake/build/pdfmake.min.js"></script>
		<script src="' . base_url() . 'assets/vendor/pdfmake/build/vfs_fonts.js"></script>
		<script src="' . base_url() . 'assets/vendor/datatables.net-buttons/js/buttons.html5.min.js"></script>
		<script src="' . base_url() . 'assets/vendor/datatables.net-buttons/js/buttons.print.min.js"></script>
		<script src="' . base_url() . 'assets/vendor/datatables.net-buttons/js/dataTables.buttons.min.js"></script>
		<script src="' . base_url() . 'assets/vendor/datatables.net-buttons-bs/js/buttons.bootstrap.min.js"></script>';
?>
<script>
	$('#pending-order').dataTable( {
            dom: "<'row'<'col-sm-4'l><'col-sm-4 text-center'B><'col-sm-4'f>>tp",
            "lengthMenu": [ [10, 25, 50, -1], [10, 25, 50, "All"] ],
            buttons: [
                {extend: 'copy',className: 'btn-sm'},
                {extend: 'csv',title: 'Pending Orders - Hybrid Portal', className: 'btn-sm'},
                {extend: 'pdf', title: 'Pending Orders - Hybrid Portal', className: 'btn-sm'},
                {extend: 'print',className: 'btn-sm'}
            ]
        });
	$('#my-order').dataTable( {
            dom: "<'row'<'col-sm-4'l><'col-sm-4 text-center'B><'col-sm-4'f>>tp",
            "lengthMenu": [ [10, 25, 50, -1], [10, 25, 50, "All"] ],
            buttons: [
                {extend: 'copy',className: 'btn-sm'},
                {extend: 'csv',title: 'Pending Orders - Hybrid Portal', className: 'btn-sm'},
                {extend: 'pdf', title: 'Pending Orders - Hybrid Portal', className: 'btn-sm'},
                {extend: 'print',className: 'btn-sm'}
            ]
        });
</script>
<?php
        }
        if (isset($_ci_vars['module']) && $_ci_vars['module'] == "channelpartners") {
            echo '
		<script src="' . base_url() . 'assets/bluechip/partners.js"></script>
		<script src="' . base_url() . 'assets/vendor/datatables/media/js/jquery.dataTables.min.js"></script>
		<script src="' . base_url() . 'assets/vendor/datatables.net-bs/js/dataTables.bootstrap.min.js"></script>
<!-- DataTables buttons scripts -->
		<script src="' . base_url() . 'assets/vendor/pdfmake/build/pdfmake.min.js"></script>
		<script src="' . base_url() . 'assets/vendor/pdfmake/build/vfs_fonts.js"></script>
		<script src="' . base_url() . 'assets/vendor/datatables.net-buttons/js/buttons.html5.min.js"></script>
		<script src="' . base_url() . 'assets/vendor/datatables.net-buttons/js/buttons.print.min.js"></script>
		<script src="' . base_url() . 'assets/vendor/datatables.net-buttons/js/dataTables.buttons.min.js"></script>
		<script src="' . base_url() . 'assets/vendor/datatables.net-buttons-bs/js/buttons.bootstrap.min.js"></script>';
?>
<script>
$('#channel-partner').dataTable( {
            dom: "<'row'<'col-sm-4'l><'col-sm-4 text-center'B><'col-sm-4'f>>tp",
            "lengthMenu": [ [10, 25, 50, -1], [10, 25, 50, "All"] ],
            buttons: [
                {extend: 'copy',className: 'btn-sm'},
                {extend: 'csv',title: 'Hybrid Portal Channel Partners', className: 'btn-sm'},
                {extend: 'pdf', title: 'Hybrid Portal Channel Partners', className: 'btn-sm'},
                {extend: 'print',className: 'btn-sm'}
            ]
        });
</script>
<?php
        }
		if (isset($_ci_vars['module']) && $_ci_vars['module'] == "rolepermission") {
            echo '
		<script src="' . base_url() . 'assets/bluechip/rolepermission.js"></script>';
        }
        if (isset($_ci_vars['module']) && $_ci_vars['module'] == "user") {
            echo '
		<script src="' . base_url() . 'assets/bluechip/mainmenu.js"></script>
		<script src="' . base_url() . 'assets/vendor/datatables/media/js/jquery.dataTables.min.js"></script>
		<script src="' . base_url() . 'assets/vendor/datatables.net-bs/js/dataTables.bootstrap.min.js"></script>
<!-- DataTables buttons scripts -->
		<script src="' . base_url() . 'assets/vendor/pdfmake/build/pdfmake.min.js"></script>
		<script src="' . base_url() . 'assets/vendor/pdfmake/build/vfs_fonts.js"></script>
		<script src="' . base_url() . 'assets/vendor/datatables.net-buttons/js/buttons.html5.min.js"></script>
		<script src="' . base_url() . 'assets/vendor/datatables.net-buttons/js/buttons.print.min.js"></script>
		<script src="' . base_url() . 'assets/vendor/datatables.net-buttons/js/dataTables.buttons.min.js"></script>
		<script src="' . base_url() . 'assets/vendor/datatables.net-buttons-bs/js/buttons.bootstrap.min.js"></script>';
?>
<script>
$('#user-table').dataTable( {
            dom: "<'row'<'col-sm-4'l><'col-sm-4 text-center'B><'col-sm-4'f>>tp",
            "lengthMenu": [ [10, 25, 50, -1], [10, 25, 50, "All"] ],
            buttons: [
                {extend: 'copy',className: 'btn-sm'},
                {extend: 'csv',title: 'Hybrid Portal System Users', className: 'btn-sm'},
                {extend: 'pdf', title: 'Hybrid Portal System Users', className: 'btn-sm'},
                {extend: 'print',className: 'btn-sm'}
            ]
        });
</script>
<?php
        }
	if (isset($_ci_vars['module']) && $_ci_vars['module'] == "report") {
            echo '
		<script src="' . base_url() . 'assets/vendor/datatables/media/js/jquery.dataTables.min.js"></script>
		<script src="' . base_url() . 'assets/vendor/datatables.net-bs/js/dataTables.bootstrap.min.js"></script>
		<!-- DataTables buttons scripts -->
		<script src="' . base_url() . 'assets/vendor/pdfmake/build/pdfmake.min.js"></script>
		<script src="' . base_url() . 'assets/vendor/pdfmake/build/vfs_fonts.js"></script>
		<script src="' . base_url() . 'assets/vendor/datatables.net-buttons/js/buttons.html5.min.js"></script>
		<script src="' . base_url() . 'assets/vendor/datatables.net-buttons/js/buttons.print.min.js"></script>
		<script src="' . base_url() . 'assets/vendor/datatables.net-buttons/js/dataTables.buttons.min.js"></script>
		<script src="' . base_url() . 'assets/vendor/datatables.net-buttons-bs/js/buttons.bootstrap.min.js"></script>
		<script src="' . base_url() . 'assets/bluechip/report.js"></script>';
        }
        ?>
		<script> 
			setTimeout(
				function() { 
					window.location.href = "<?php print base_url(); ?>auth/logout"; 
				}
			, 300000);

		</script>
    </body>
</html>
<?php
    }
    else {
        echo '<script>document.location.href="'.base_url().'auth/logout";</script>';
    }
?>