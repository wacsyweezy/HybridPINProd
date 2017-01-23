<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');
/*
 * This file is part of Auth_Ldap.

  Auth_Ldap is free software: you can redistribute it and/or modify
  it under the terms of the GNU Lesser General Public License as published by
  the Free Software Foundation, either version 3 of the License, or
  (at your option) any later version.

  Auth_Ldap is distributed in the hope that it will be useful,
  but WITHOUT ANY WARRANTY; without even the implied warranty of
  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
  GNU General Public License for more details.

  You should have received a copy of the GNU General Public License
  along with Auth_Ldap.  If not, see <http://www.gnu.org/licenses/>.
 * 
 */

/**
 * @author      Greg Wojtak <gwojtak@techrockdo.com>
 * @copyright   Copyright © 2010,2011 by Greg Wojtak <gwojtak@techrockdo.com>
 * @package     Auth_Ldap
 * @subpackage  auth demo
 * @license     GNU Lesser General Public License
 */
class Auth extends MX_Controller {

    function __construct() {
        parent::__construct();
        $this->load->library('Form_validation');
    }

    function index() {
        if(isset($_SESSION['username'])) {
		if(isset($_SESSION['usercode']) && strlen($_SESSION['usercode'])>4) {
			redirect('/dashboard/external', $data);
		}
		else {
			redirect('/dashboard/internal', $data);
		}
	}
	else {
		$data['title'] = 'Authentication System - Hybrid Management System';
        	$this->load->view('login', $data);
	}
    }

    function loadView() {
        $data['module'] = 'dashboard';
        $data['view_file'] = 'index';
        echo Modules::run('templ/templContent', $data);
    }
	
    function login() {
	//Note::::::  0 REP SUCCESS --######-- 1 REP FAILURE
        $values = Array();
        $rules = $this->form_validation;
        $rules->set_rules('username', 'Username', 'required|trim');

	$allvalues = $this->check_user($_POST['username']);

	$vals = explode("_", $allvalues);
        $islocked = $vals[0];
	$isusertype = $vals[1];
       	$ischgpwd = $vals[2];
        $roleid = $vals[3];
        $usercode = $vals[4];

	$accounttype = $_POST['accounttype'];

	if(empty($usercode) && $accounttype==1 ||  $accounttype==2) {
		$rules->set_rules('password', 'Password', 'required|trim');
	}

	// Do the login...
        if ($rules->run()) {
            	$lgprocess = $this->loginprocess($rules->set_value('username'), $rules->set_value('password'));
        } 
	else {
		// Login FAIL 
            	$this->load->view('login', array('msg' => '<div class="alert alert-danger"><i class="fa fa-warning"></i> Error, Login failed.</div>'));
        }
    }

	function preload() {
		if(isset($_POST['task'])) {
			if($_POST['task']=="azure-register") {
				$name = $_POST['name'];
				$phone = $_POST['name'];
				$usr = $_POST['usr'];
				$evc = $_POST['evc'];
				$code = $_POST['code'];

				$code = strtoupper($code);

				$pname = $_POST['pname'];

        			$query = $this->get_where($usr);

       				if ($query->num_rows() > 0) {
					session_destroy();
					$info = "The Email ".$usr." has previously been registered. If this was not done by you, please contact your Account Manager for resolution";
					$response = base64_encode($info);
					?>
					<script>
						document.location.href="<?php echo base_url(); ?>auth?info=<?php echo $response; ?>";
					</script>
					<?php
				}

				$query = $this->get_code_custom('PARTYCODE', $code);
       				if ($query->num_rows() > 0) {
					session_destroy();
					$info = "The Trade Code ".$code." has previously been registered. If this was not done by you, please contact your Account Manager for resolution";
					$response = base64_encode($info);
					?>
					<script>
						document.location.href="<?php echo base_url(); ?>auth?info=<?php echo $response; ?>";
					</script>
					<?php
            			}

				$query = $this->get_code_custom('EVC_ACCT_CODE', $evc);
       				if ($query->num_rows() > 0) {
					session_destroy();
					$info = "The e-Purse ".$evc." has previously been registered. If this was not done by you, please contact your Account Manager for resolution";
					$response = base64_encode($info);
					?>
					<script>
						document.location.href="<?php echo base_url(); ?>auth?info=<?php echo $response; ?>";
					</script>
					<?php
				}
				else {
					$query = "INSERT INTO HY_PARTY
					(AUTHORIZED_BY, AUTHORIZED_DATE, CREATED_BY, CREATED_DATE, EVC_ACCT_CODE, PARTYCODE, PARTYNAME, PARTYTYPEID) 
					VALUES('bluechip.support',sysdate,'Azure-AD', sysdate, '".$evc."', '".$code."', '".$pname."', 1)";

					$this->_custom_query($query);

                    			$query = "INSERT INTO HY_OLTP_PIN_CODES
                    			(DEALERCODE, PIN_CODE, CREATED_BY, CREATED_DATE, MODIFIED_BY, LAST_MODIFIED_DATE, MSISDN, STATUS)
                    			VALUES('".$code."', '00000', 'Azure-AD', sysdate, '', '', '".$evc."', 0)";

                    			$this->_custom_query($query);

					$code = strtoupper($code);

 					$datas = array();
        				$datas['USERNAME'] = $usr;
        				$datas['FIRSTNAME'] = $name;
        				$datas['LASTNAME'] = $name;
        				$datas['DISPLAY_NAME'] = $pname;
        				$datas['EMAIL_ADDRESS'] = $usr;
        				$datas['CHANGE_PASSWORD'] = 1;
        				$datas['COD_PASSWORD'] = 1;
        				$datas['PASSWORDSALT'] = "HYRBID";
       	 				$datas['CREATED_BY'] = "Azure-AD";
					if(
						$code=="DPLS0004" ||
						$code=="DPLS0070" ||
						$code=="DPLS0071" ||
						$code=="DPLN0121" ||
						$code=="DPSW0020" ||
						$code=="DPSW0078" ||
						$code=="DPSS0057" ||
						$code=="DPSS0138" ||
						$code=="DPNC0040" ||
						$code=="DPNC0102" ||
						$code=="DPNC0045" ||
						$code=="DPNC0142" ||
						$code=="DPSE0064" ||
						$code=="DPSE0031" ||
						$code=="TKLA0259" ||
						$code=="TKLA0146" ||
						$code=="TKNC0335" ||
						$code=="TKLA0068" ||
						$code=="TKSS0262" ||
						$code=="TKSW0287"
					) {
        					$datas['ISLOCKED'] = 0;
					}
					else {
        					$datas['ISLOCKED'] = 1;
					}

        				$datas['ROLE_ID'] = 1;
        				$datas['USERCODE'] = $code;
		
					$this->_insert($datas);

					// lock user session
					$this->lock_session($user);

					// log audit trail
					$this->fire_audit_log($usr, '/'.$this->router->fetch_class().'/'.$this->router->fetch_method(), $code.' - '.$pname.' - '.$evc, 'AZURE-REGISTRATION');

					$this->hybrid_setup(1, $usr);

					$data['msg'] = "Account registration was successful";
					redirect('/auth/logout', $data);
				}
			}
		}
	}

	function validate() {
		if(isset($_POST['error'])) {
			$err = $_POST['error'];
			$tmp_session = $_POST['state'];
			$description = $_POST['error_description'];		
			if($err=="access_denied") {
				unset($_SESSION[$tmp_session]);
				$this->load->view('login', array('title' =>'Authentication System - Hybrid Management System', 'msg' => "<div class='alert alert-danger'><i class='fa fa-warning'></i> Authentication failed! Please try again.</div>"));
			}
		}
		if(isset($_POST['id_token']) && isset($_POST['state']) && $_POST['state']!="signup") {
			$nonce = $_POST['state'];
			$user = $_SESSION['tmp_user'];
			$roleid = $_SESSION['tmp_role'];
			$state = $_SESSION['tmp_txn'];
			if($state!=$nonce) {
				session_destroy();
				$this->load->view('login', array('title' =>'Authentication System - Hybrid Management System', 'msg' => "<div class='alert alert-danger'><i class='fa fa-warning'></i> Authentication failed! Please try again.</div>"));
			}
			else {
				// lock user session
				$this->lock_session($user);
			
				// log audit trail
				$this->fire_audit_log($user, '/'.$this->router->fetch_class().'/'.$this->router->fetch_method(), 'Azure nonce - '.$state, 'AZURE-LOGIN');

				$this->hybrid_setup($roleid, $user);
				redirect('/dashboard/external');
			}
			
		}
		if(isset($_POST['id_token']) && isset($_POST['state']) && $_POST['state']=="signup") {
			$token = $_POST['id_token'];
			?>
			<input type="hidden" value="<?php print $token; ?>" id="token" />
			<span id="decodedToken" style="display:none;"></span>
			<script src="https://hybridvirtual.etisalat.com.ng/hybrid/assets/vendor/jquery/dist/jquery.min.js"></script>
			<script src="https://hybridvirtual.etisalat.com.ng/hybrid/assets/bluechip/jwt.js"></script>
			<form action="https://hybridvirtual.etisalat.com.ng/hybrid/auth/preload" method="post" name="preload" id="preload">
				<input type="hidden" name="task" id="task" />
				<input type="hidden" name="name" id="name" />
				<input type="hidden" name="phone" id="phone" />
				<input type="hidden" name="code" id="code" />
				<input type="hidden" name="evc" id="evc" />
				<input type="hidden" name="pname" id="pname" />
				<input type="hidden" name="usr" id="usr" />
			</form>
			<script>
				DisplayToken($('#token').val());
				var params = $('span#decodedToken').text();
				var clean = params.replace("{", "");
				clean = clean.replace("}", "");
				clean = clean.replace(" ", "");
				var all = clean.split(',');
				var data = {};
				for(i=0; i<all.length; i++) {
					var fetch = all[i].split(':');
					var name = $.trim(fetch[0].replace(" ", ""));
					data[name] = $.trim(fetch[1].replace(/[^a-zA-Z 0-9@._]+/g,''));
				}
				$('input#task').val('azure-register');
				$('input#name').val(data['family_name']);
				$('input#phone').val(data['extension_Telephone']);
				$('input#code').val(data['extension_Code']);
				$('input#evc').val(data['extension_EVCAccount']);
				$('input#pname').val(data['extension_Partner']);
				$('input#usr').val(data['emails']);
				$('#preload').submit();
			</script>
			<?php
		}
	}

    function enforeConcurrentCheck($user) {
	$query = $this->_custom_query("select * from hy_session_lock where username='$user'");
       	if ($query->num_rows() > 0) {
		return true;
	}
    }

    function loginprocess($username, $password) {
        $allvalues = $this->check_user($username);
        
        if ($allvalues != '1') {
		
		/* check concurrent login
		if($this->enforeConcurrentCheck($username)==true) {
            		$this->load->view('login', array('title' =>'Authentication System - Hybrid Management System', 'msg' => "<div class='alert alert-danger'><i class='fa fa-warning'></i> You are logged in on another computer already</div>"));
		} */

            	$values = explode("_", $allvalues);
            	$islocked = $values[0];
		$isusertype = $values[1];
            	$ischgpwd = $values[2];
            	$roleid = $values[3];
            	$usercode = $values[4];
			
			// Check if System User Account is Locked
			if($islocked==1) {
				$this->load->view('login', array('title' =>'Authentication System - Hybrid Management System', 'msg' => "<div class='alert alert-danger'><i class='fa fa-warning'></i> Your account is currently locked or inactive.</div>"));
			}

			else if($this->enforeConcurrentCheck($username)==true) {
            			$this->load->view('login', array('title' =>'Authentication System - Hybrid Management System', 'msg' => "<div class='alert alert-danger'><i class='fa fa-warning'></i> You are logged in on another computer already</div>"));
			}

			else {
				// Perform AD Authentication Based on User Type
				// if variable $usercode length is > than 1 then the system user is an external user.
				
				if(strlen($usercode)>5) {
					// External User - Azure AD
					$nonce = mt_rand();
					$username = $_POST['username'];
					$this->session->set_userdata('tmp_txn', $nonce);
					$this->session->set_userdata('tmp_user', $username);
					$this->session->set_userdata('tmp_role', $roleid);
					$this->session->set_userdata('tmp_code', $usercode);
					$b2c_tenant_domain = 'etisalatad.onmicrosoft.com';
					$app_id = '9d3ce206-4595-4e91-9dc5-043186b3b427';
					$policy = 'B2C_1_Hybrid2Pin';
					$url= "https://login.microsoftonline.com/$b2c_tenant_domain/oauth2/v2.0/authorize?client_id=$app_id&response_type=id_token&redirect_uri=https://hybridvirtual.etisalat.com.ng/hybrid/auth/validate&scope=openid&response_mode=form_post&state=$nonce&nonce=$nonce&p=$policy";
					echo "<script>document.location.href='$url';</script>";
				}
				else {
					if(!isset($_POST['password']) || $_POST['password']=="hybrid") {
						// Password bypass lookup.
						$this->load->view('login', array('title' =>'Authentication System - Hybrid Management System', 'msg' => "<div class='alert alert-danger'><i class='fa fa-warning'></i> Username or password is incorrect.</div>"));
					}
					
					// Internal User - Etisalat AD
					if ($this->check_ldap(addslashes($_POST['username']), addslashes($_POST['password']))) {	
						// lock user session
						$this->lock_session(addslashes($_POST['username']));

						// log audit trail
						$this->fire_audit_log(addslashes($_POST['username']), '/'.$this->router->fetch_class().'/'.$this->router->fetch_method(), 'Etisalat AD', 'LOGIN');
					
						$this->hybrid_setup($roleid, addslashes($_POST['username']));
						redirect('/dashboard/internal', $data);
					}
					else {
						// Internal AD Failed.
						$this->load->view('login', array('title' =>'Authentication System - Hybrid Management System', 'msg' => "<div class='alert alert-danger'><i class='fa fa-warning'></i> Username or password is incorrect.</div>"));
					}
				}
			}
        } 
		else {
            $this->load->view('login', array('title' =>'Authentication System - Hybrid Management System', 'msg' => "<div class='alert alert-danger'><i class='fa fa-warning'></i> Username does not exist.</div>"));
        }
    }

	function lock_session($usr) {
		$query = "INSERT INTO HY_SESSION_LOCK(USERNAME, LOGGED_TIME) VALUES('".$usr."', sysdate)";
		$this->_custom_query($query);
	}

	function unlock_session($usr) {
		$query = "DELETE FROM HY_SESSION_LOCK WHERE USERNAME = '".$usr."'";
		$this->_custom_query($query);
	}
	
	function hybrid_setup($roleid, $username) {
		$data[mm] = $this->user_permission($roleid);
		$data[sm] = $this->user_permission1($roleid);

		$this->user_session($data[mm], $data[sm], $username);
		 
		$all_main_menu = "";
		$main_apps = [];
		$all_main_apps = "";
		$all_apps = [];
					  
		foreach ($_SESSION[AAA] as $value) { 
			foreach ($_SESSION[BBB] as $value1) { 
				$str = explode(":", $value1) ;
				$menu = $str[3];
				if($value==$menu) {
					$actn = $str[0];
					$displayname = $str[1];
					$ctrller = $str[2];
					if(strpos($all_main_menu, $ctrller) !== false ) {
						continue;
					}
					else {
						$all_main_menu .= $ctrller.'|';
					}
				}
			}
		}
		$main_apps = explode('|', $all_main_menu);
		unset($main_apps[count($main_apps)-1]);
		
		foreach ($_SESSION[AAA] as $value) { 
			foreach ($_SESSION[BBB] as $value1) { 
				$str = explode(":", $value1) ;
				$menu = $str[3];
				if($value==$menu) {
					$actn = $str[0];
					$displayname = $str[1];
					$ctrller = $str[2];
					if(strpos($all_main_apps, $actn) !== false ) {
						continue;
					}
					else {
						$all_main_apps .= $actn.'|';
					}
				}
			}
		}
		$all_apps.="login|index";
		$all_apps = explode('|', $all_main_apps);
		unset($all_apps[count($all_apps)-1]);

		$this->session->set_userdata('CORE_APP', $main_apps);
		$this->session->set_userdata('ALL_APP', $all_apps);
	}

    function check_ldap($username, $password) {
        $ldap = ldap_connect("10.150.140.11");
        if ($bind = ldap_bind($ldap, "etisalatng\\$username", $password)) {
            return true;
        } 
		else {
            return false;
        }
    }

    function log($process) {
        $userid = $_SESSION['userid'];
        $date = date('y-m-d h:i:s');
        $process = "Auth  : " . $process;
        $query = "INSERT INTO log VALUES ('', '$process','$date', $userid)";

        $this->_custom_query($query);
    }

    function check_user($username) {
        $islocked = $isusertype = $ischgpwd = "";
        $query = $this->get_where($username);
        if ($query->num_rows() > 0) {
            foreach ($query->result() as $row) {
                $islocked = $row->ISLOCKED;
                $isusertype = $row->USER_TYPE;
                $ischgpwd = $row->CHANGE_PASSWORD;
                $roleid = $row->ROLE_ID;
                $usercode = $row->USERCODE;
            }
            return $islocked . '_' . $isusertype . '_' . $ischgpwd . '_' . $roleid . '_' . $usercode;
        } else {
            return "1";
        }
    }

    function logout() {
	$check_code = $_SESSION['usercode'];

	$user = $_SESSION['username'];
	
	// unlock user session
	$this->unlock_session($user);

	if(strlen($check_code)>2) {
		// log audit trail
		$this->fire_audit_log($user, '/'.$this->router->fetch_class().'/'.$this->router->fetch_method(), '-', 'AZURE-LOGOUT');

		session_destroy();
		echo "<script>document.location.href='https://login.microsoftonline.com/etisalatad.onmicrosoft.com/oauth2/v2.0/logout?p=B2C_1_Hybrid2Pin&redirect_uri=https://hybridvirtual.etisalat.com.ng/hybrid';</script>";
	}
	else {
		// log audit trail
		$this->fire_audit_log($user, '/'.$this->router->fetch_class().'/'.$this->router->fetch_method(), '-', 'LOGOUT');

        	session_destroy();
        	redirect('/auth/');
	}
    }

    function user_permission($roleid) {
        $data5 = $data4 = $data3 = $data1 = $data2 = array();
		
        $query1 = "SELECT DISTINCT P.MAIN_MENU_ID FROM HY_ROLE_PERM_XREF RP , HY_PERMISSIONS P where P.PERMISSION_ID =  RP.PERM_ID and RP.ROLE_ID = $roleid ORDER BY P.MAIN_MENU_ID ASC";
        $record1 = $this->_custom_query($query1)->result();
		
        for ($i = 0; $i < count($record1); $i++) {
            $data2[$i] = ($record1[$i]->MAIN_MENU_ID);
        }

        for ($i = 0; $i < count($data2); $i++) {
            $data3[$i] = $this->getMainMenuName($data2[$i]);
        } 

        for ($i = 0; $i < count($data3); $i++) {
            $data4[$i] = $data3[$i][MAIN_MENU_NAME];
        }

        foreach ($data4 as $value) {
            if (in_array($value, $data4)) {
                $data[$value] = $value;
            }
        }
        return $data;
    }

    function user_permission1($roleid) {
        $query = "SELECT P.MAIN_MENU_ID, P.ACTION_NAME,P.DISPLAY_NAME,P.CONTROLLER_NAME FROM HY_ROLE_PERM_XREF RP , HY_PERMISSIONS P where   P.PERMISSION_ID =  RP.PERM_ID and RP.ROLE_ID = $roleid ORDER BY P.MAIN_MENU_ID ASC";
        $record = $this->_custom_query($query)->result();
        for ($i = 0; $i < count($record); $i++) {
            $data1[$i] = $record[$i]->ACTION_NAME . ":" . $record[$i]->DISPLAY_NAME . ":" . $record[$i]->CONTROLLER_NAME . ":" . $this->getMainMenuName1($record[$i]->MAIN_MENU_ID);
        }

        foreach ($data1 as $value) {
            if (in_array($value, $data1)) {
                $data[$value] = $value;
            }
        }
        return $data;
    }

    function getMainMenuName($id) {
        $query = $this->get_mm_name_where($id)->result();
        foreach ($query as $row) {
            $data['MAIN_MENU_NAME'] = $row->MAIN_MENU_NAME;
        }
        return $data;
    }

    function getMainMenuName1($id) {
        $query = $this->get_mm_name_where($id)->result();
        foreach ($query as $row) {
            $data = $row->MAIN_MENU_NAME;
        }
        return $data;
    }

    function user_session($d1, $d2, $username) {
        $query = $this->get_where($username)->result();
        foreach ($query as $row) {
            $newdata = array(
                'userid' => $row->USER_ID,
                'username' => $row->USERNAME,
                'usercode' => $row->USERCODE,
                'displayname' => $row->DISPLAY_NAME,
                'logged_in' => TRUE
            );
        }
        $this->session->set_userdata($newdata);
        $this->session->set_userdata('AAA', $d1);
        $this->session->set_userdata('BBB', $d2);    
    }

    function _custom_num_rows_query($mysql_query) {
        $this->load->model('mdl_auth');
        $query = $this->mdl_auth->_custom_num_rows_query($mysql_query);
        return $query;
    }

    function _custom_query($mysql_query) {
        $this->load->model('mdl_auth');
        $query = $this->mdl_auth->_custom_query($mysql_query);
        return $query;
    }

    function _insert($data) {
        $this->load->model('mdl_auth');
        $this->mdl_auth->_insert($data);
    }

    function get_where($id) {
        $this->load->model('mdl_auth');
        $query = $this->mdl_auth->get_where($id);
        return $query;
    }

    function get_where_code($code) {
        $this->load->model('mdl_auth');
        $query = $this->mdl_auth->get_where_code($code);
        return $query;
    }

    function get_evc_custom($col, $val) {
        $this->load->model('mdl_auth');
        $query = $this->mdl_auth->get_evc_custom($col, $val);
        return $query;
    }

    function get_code_custom($col, $val) {
        $this->load->model('mdl_auth');
        $query = $this->mdl_auth->get_code_custom($col, $val);
        return $query;
    }

    function get_role_permissions_where($id) {
        $this->load->model('mdl_auth');
        $query = $this->mdl_auth->get_role_permissions_where($id);
        return $query;
    }

    function get_permission_detail($id) {
        $this->load->model('mdl_auth');
        $query = $this->mdl_auth->get_permissions_where($id);
        return $query;
    }

    function get_shortname_where($id) {
        $this->load->model('mdl_auth');
        $query = $this->mdl_auth->get_MainMenu_where($id);
        return $query;
    }

    function get_mm_name_where($id) {
        $this->load->model('mdl_auth');
        $query = $this->mdl_auth->get_MainMenuName_where($id);
        return $query;
    }

    function fire_audit_log($user, $url, $auditdata, $action) {
	$sql = "insert into hy_audit_trail (AUDITID, SESSIONID, IPADDRESS, USERID, URLACCESSED, TIMEACCESSED, AUDITDATA, AUDITACTION)
	values(AUDIT_TRAIL_ID_SEQ.nextval, '".session_id()."', '".$_SERVER["REMOTE_ADDR"]."', '".$user."', '".$url."', sysdate, '".$auditdata."', '".$action."')";
	$this->_custom_query($sql);
    }

}

?>