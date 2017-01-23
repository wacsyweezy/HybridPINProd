<!DOCTYPE html>
<html lang="en">
	<head>

		<meta charset="utf-8">
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
		<meta http-equiv="X-UA-Compatible" content="IE=edge">

		<!-- Page title -->
		<title><?php echo $title; ?></title>

		<!-- Place favicon.ico and apple-touch-icon.png in the root directory -->
		<link rel="shortcut icon" type="image/ico" href="<?php echo base_url(); ?>assets/images/favicon.ico" />

		<!-- Vendor styles -->
		<link rel="stylesheet" href="<?php echo base_url(); ?>assets/vendor/fontawesome/css/font-awesome.css" />
		<link rel="stylesheet" href="<?php echo base_url(); ?>assets/vendor/metisMenu/dist/metisMenu.css" />
		<link rel="stylesheet" href="<?php echo base_url(); ?>assets/vendor/animate.css/animate.css" />
		<link rel="stylesheet" href="<?php echo base_url(); ?>assets/vendor/bootstrap/dist/css/bootstrap.css" />

		<!-- App styles -->
		<link rel="stylesheet" href="<?php echo base_url(); ?>assets/fonts/pe-icon-7-stroke/css/pe-icon-7-stroke.css" />
		<link rel="stylesheet" href="<?php echo base_url(); ?>assets/fonts/pe-icon-7-stroke/css/helper.css" />
		<link rel="stylesheet" href="<?php echo base_url(); ?>assets/styles/style.css">

		<link rel="stylesheet" href="<?php print base_url()?>assets/vendor/select2-3.5.2/select2.css" />
		<link rel="stylesheet" href="<?php print base_url()?>assets/vendor/select2-bootstrap/select2-bootstrap.css" />

	</head>
    <!-- BEGIN BODY -->
    <body>
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
		<!-- PAGE CONTENT -->
		<div class="color-line"></div>
		<div class="login-container">
			<div class="row">
				<div class="col-md-12">
					<div class="text-center m-b-md">
						<img src="<?php print base_url(); ?>/assets/images/logo.jpg" />
						<h3>Please Login to Hybrid Portal</h3>
					</div>
					<div class="hpanel">
						<div class="panel-body">
                            			<?php 
    							if (isset($msg)) {
    								echo '<div class="login-err">'.$msg.'<br /></div>';
    							} 
    							if (isset($_GET['info'])) {
    								echo '<div class="alert alert-danger">'.base64_decode($_GET['info']).'</div><br />';
    							}
    						?>
								<!--  CREATE NEW FORM WITH CodeIgniter -->
							<?php echo form_open('auth/login', 'id=loginForm'); ?>

								<div class="form-group">
									<label class="control-label" for="username">Username</label>
									<input type="text" placeholder="enter your username" title="Please enter you username" required="" value="" name="username" id="username" class="form-control">
									<span class="help-block small">Your unique username to app</span>
								</div>
								<div class="form-group">
									<label class="control-label" for="password" id="passwd-label">Account Type</label>
									<input type="password" title="Please enter your password" placeholder="******" required="" value="" name="password" id="password" class="hidden form-control">
									<select class="js-source-states" style="width: 100%" class="form-control" name="accounttype" id="accounttype">
										<option selected="selected" value="2">Please select type</option>
										<option value="1">Channel Partners</option>
										<option value="0">Internal Account</option>
									</select>
									<span class="passwd-help hidden help-block small">Your password</span>
								</div>
								<!-- <div class="checkbox">
									<input type="checkbox" class="i-checks" id="remember">
										 Remember login
									<p class="help-block small">(if this is a private computer)</p>
								</div> -->
								<button class="do-login btn btn-success pull-left"><i class="fa fa-login"></i> Sign in</button>
								<a href="https://login.microsoftonline.com/etisalatad.onmicrosoft.com/oauth2/v2.0/authorize?client_id=9d3ce206-4595-4e91-9dc5-043186b3b427&redirect_uri=https://hybridvirtual.etisalat.com.ng/hybrid/auth/validate&scope=openid&response_mode=form_post&state=signup&nonce=signup&response_type=id_token&p=B2C_1_Hybrid2Pin" class="btn btn-default pull-right">Sign up</a>
							</form>
						</div>
					</div>
				</div>
			</div>
			<div class="row">
				<div class="col-md-12 text-center">
					Hybrid Management System <strong>v1.0</strong> <br/> <?php print date('Y'); ?> Powered by Bluechip Technologies
				</div>
			</div>
		</div> 
		<!--END PAGE CONTENT -->     
		<script src="<?php echo base_url(); ?>assets/vendor/jquery/dist/jquery.min.js"></script>
		<script src="<?php echo base_url(); ?>assets/vendor/jquery-ui/jquery-ui.min.js"></script>
		<script src="<?php echo base_url(); ?>assets/vendor/slimScroll/jquery.slimscroll.min.js"></script>
		<script src="<?php echo base_url(); ?>assets/vendor/bootstrap/dist/js/bootstrap.min.js"></script>
		<script src="<?php echo base_url(); ?>assets/vendor/metisMenu/dist/metisMenu.min.js"></script>
		<script src="<?php echo base_url(); ?>assets/vendor/iCheck/icheck.min.js"></script>
		<script src="<?php echo base_url(); ?>assets/vendor/sparkline/index.js"></script>
		<script src="<?php print base_url(); ?>assets/vendor/select2-3.5.2/select2.min.js"></script

		<!-- App scripts -->
		<script src="<?php echo base_url(); ?>assets/scripts/homer.js"></script>
        <script>
        $(function () {
		$(".js-source-states").select2();

		$('select#accounttype').change(function() {
			var type = $(this).val();
			if(type==0) {
				$('select#accounttype').addClass('hidden');
				$('label#passwd-label').html('Password');
				$('input[type=password], span.passwd-help').removeClass('hidden');
			}
			if(type==1) {
				$('select#accounttype').addClass('hidden');
				$('label#passwd-label').remove();
				$('span.passwd-help').html('Please click proceed to sign in');
				$('button.do-login').html('<i class="fa fa-login"></i> Proceed');
				$('span.passwd-help').removeClass('hidden');
				$('input[type=password]').remove();
			}
		});

            var msgp = $('.login-err');
             setTimeout(function () {
                msgp.fadeOut('slow', function () {
                    $(this).fadeOut();
                });
            }, 30000);
        });
        </script>
        <!--END PAGE LEVEL SCRIPTS -->
    </body>
    <!-- END BODY -->
</html>
