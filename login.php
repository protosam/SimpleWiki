<?php
/* Welcome to the acid example file!
 *
 * All we have to do to start using acid is require it.
 */
require('drop/acid.php');

# the response from reCAPTCHA
$resp = null;
# the error code from reCAPTCHA, if any
$error = null;


if(isset($_REQUEST['signup']) && $CONF['wiki']['allow_registration']){
	$tpl = new vision("drop/vision/signup.tpl");

	if(isset($_REQUEST['username']) && isset($_REQUEST['email']) && isset($_REQUEST['password']) && isset($_REQUEST['repassword']) && isset($_POST['g-recaptcha-response'])){
		$newuser = new users();

		$successful = true;



		# was there a reCAPTCHA response?
		$response=json_decode(file_get_contents("https://www.google.com/recaptcha/api/siteverify?secret=".$CONF['recaptcha']['private_key']."&response=".$_POST['g-recaptcha-response']."&remoteip=".$_SERVER['REMOTE_ADDR']), true);
		if (!$response['success']) {
			$tpl->assign("reason", "Humanity check failed. Way to go buddy.");
			$tpl->parse("signup.fail");
			$successful = false;
		}

		if(!isset($_REQUEST['agreedtermsofservice'])){
			$tpl->assign("reason", "You did not agree to the terms of service.");
			$tpl->parse("signup.fail");
			$successful = false;
		}

		$valid_username = $newuser->valid_username($_REQUEST['username']);
		if($valid_username != "ok"){
			$tpl->assign("reason", $valid_username);
			$tpl->parse("signup.fail");
			$successful = false;
		}

		$valid_email = $newuser->valid_email($_REQUEST['email']);
		if($valid_email != "ok"){
			$tpl->assign("reason", $valid_email);
			$tpl->parse("signup.fail");
			$successful = false;
		}

		if($_REQUEST['password'] != $_REQUEST['repassword'] || strlen($_REQUEST['password']) < 6){
				$tpl->assign("reason", "Passwords must match and be 6 or more characters long.");
				$tpl->parse("signup.fail");
				$successful = false;
		}

		// check if taken
		$lookup = new users();
		$lookup->findby('username', $_REQUEST['username']);
		if($lookup->count() > 0){
				$tpl->assign("reason", "Username already taken.");
				$tpl->parse("signup.fail");
				$successful = false;
		}
		$lookup->findby('email', $_REQUEST['email']);
		if($lookup->count() > 0){
				$tpl->assign("reason", "Email address already in use.");
				$tpl->parse("signup.fail");
				$successful = false;
		}

		if($successful){
			$newuser->set("username", $_REQUEST['username']);
			$newuser->set("email", $_REQUEST['email']);
			$hpass = $newuser->hash_pass($_REQUEST['password'], true);
			$newuser->set("password_hash", $hpass);
			$token = $newuser->mktoken();
			$newuser->set("login_token", $token);
			$newuser->save();


			redirect("/login.php?creatednewuser");
		}

	}


	$tpl->assign('google_recaptcha', '<div class="g-recaptcha" data-sitekey="'.$CONF['recaptcha']['public_key'].'"></div>');
	$tpl->parse('signup');
	$tpl->out('signup');

	die;
}

$tpl = new vision("drop/vision/login.tpl");


if((isset($_REQUEST['creatednewuser']) || isset($_REQUEST['signup'])) && !$CONF['wiki']['allow_registration']){
	$tpl->assign("reason", "User creation is currently disabled.");
	$tpl->parse('login.fail');
}

if(isset($_REQUEST['creatednewuser']) && $CONF['wiki']['allow_registration']){
	$tpl->parse('login.newuser');
}


if(isset($_REQUEST['logout'])){
	$logoutuser = new users();
	$logoutuser->logged_in();
	$logoutuser->logout();
	$tpl->parse('login.logged_out');
}

if(isset($_REQUEST['email']) && isset($_REQUEST['password'])){

	# was there a reCAPTCHA response?
	$response=json_decode(file_get_contents("https://www.google.com/recaptcha/api/siteverify?secret=".$CONF['recaptcha']['private_key']."&response=".$_POST['g-recaptcha-response']."&remoteip=".$_SERVER['REMOTE_ADDR']), true);
	if (!$response['success']) {
		$tpl->assign("reason", "Humanity check failed. Way to go buddy.");
		$tpl->parse("login.fail");
	}else{

		$loginuser = new users();

		if(isset($_REQUEST['rememberme'])){
			$login = $loginuser->login($_REQUEST['email'], $_REQUEST['password'], true);
		}else{
			$login = $loginuser->login($_REQUEST['email'], $_REQUEST['password']);
		}
		if($login){
			$_SESSION['info'] = "Welcome back ".$loginuser->get("username")."! It is nice to see you again.";
			redirect("/");
		}else{
			$tpl->assign("reason", "You typed in a bad username/password combination.");
			$tpl->parse('login.fail');
		}
	} // humanity check
}

$tpl->assign('google_recaptcha', '<div class="g-recaptcha" data-sitekey="'.$CONF['recaptcha']['public_key'].'"></div>');
$tpl->parse('login');
$tpl->out('login');
