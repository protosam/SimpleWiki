<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>Setup Wizard</title>



	<script src='https://www.google.com/recaptcha/api.js'></script>
	<script src="https://code.jquery.com/jquery-3.2.1.min.js" integrity="sha256-hwg4gsxgFZhOsEEamdOYGBf13FyQuiTwlAQgxVSNgt4=" crossorigin="anonymous"></script>
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-alpha.6/css/bootstrap.min.css" integrity="sha384-rwoIResjU2yc3z8GV/NPeZWAv56rSmLldC3R/AZzGRnGxQQKnKkoFVhFQhNUwEyJ" crossorigin="anonymous">	
	<script src="https://cdnjs.cloudflare.com/ajax/libs/tether/1.4.0/js/tether.min.js" integrity="sha384-DztdAPBWPRXSA/3eYEEUWrWCy7G5KFbe8fFjk5JAIxUYHKkDx6Qin1DkWx51bBrb" crossorigin="anonymous"></script>
	<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-alpha.6/js/bootstrap.min.js" integrity="sha384-vBWWzlZJ8ea9aCX4pEW3rVHjgjt7zpkNpZk+02D9phzyeVkE+jo0ieGizqPLForn" crossorigin="anonymous"></script>

	
	<meta name="apple-mobile-web-app-capable" content="yes">
	<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
</head>
<body>
<?php
if(file_exists('drop/config.php')){
	echo '<h2>Forbidden!</h2>';
	echo '<p>We see that drop/config.php already exists. You can not re-run this setup wizard without deleting that file.</p>';
	echo '</body></html>';
	die;
}
?>


	<div class="container">
		<h2>Setup Wizard</h2>
<?php
require_once('drop/config.example.php');

function arrayMap($array, $nameString = '', $keysString = ''){
	if (is_array($array)) {
		foreach ($array as $key => $value) {
			$ks = $keysString . '[' . $key . ']';
			$ns = (strlen($nameString) > 0)? $nameString . ' / ' . $key : $key;
			arrayMap($value, $ns, $ks);
		}
	} else {
		echo '<small>' . $nameString . '</small> ';
		switch(gettype($array)){
			case 'boolean':
				echo '<input type="checkbox" name="conf' . $keysString . '" ' . (($array)? 'checked' : '') . '>';
				break;
			default:
				echo '<input type="text" class="form-control" name="conf' . $keysString . '" value="' . $array . '">';
				break;
			
		}
		echo '<br>';
	}
}
?>

<?php
$stage = (isset($_REQUEST['stage']))? $_REQUEST['stage'] : '0';
$saveto = 'drop/config.php';

switch($stage){
	case '2':
		file_put_contents($saveto, "<?php\n".$_REQUEST['configfile'].';');
		echo 'Wrote file '.$saveto.' <br>';
		echo 'Loading file '.$saveto.' <br>';
		require_once($saveto);
		
		echo 'Connecting to MySQL database.<br>';
		
		$db = new mysqli($CONF['database']['hostname'], $CONF['database']['username'], $CONF['database']['password'], $CONF['database']['database'], $CONF['database']['port'], $CONF['database']['socket']);
		if ($db->connect_error){
			echo 'Failed to connect to MySQL database. Go back and ensure your database settings are correct. <br>';
		}else{
			echo 'Successfully connected to MySQL database! <br>';
			
			$query = file_get_contents('drop/wiki.sql');
			$db->multi_query($query);
			while ($db->next_result()) {;}
		
			require('drop/components/catalyst.class.php');
			require('drop/database/users.php');
			catalyst::setlink($db);
		
		
		
			$newuser = new users();
			$newuser->set("username", $_REQUEST['username']);
			$newuser->set("email", $_REQUEST['email']);
			$hpass = $newuser->hash_pass($_REQUEST['password'], true);
			$newuser->set("password_hash", $hpass);
			$token = $newuser->mktoken();
			$newuser->set("login_token", $token);
			$newuser->set("admin", 'Y');
			$newuser->save();
			
			$db->close();
		}
		
		
		
		?>
		<br>
		Setup is now completed. If you see no errors here, then you're done!
		<hr class="featurette-divider">
		<a class="btn btn-success" href="/login.php">Go to Login</a>
			
		<?php break;
	case '1':
		// set these ones as booleans
		$_REQUEST['conf']['wiki'] = (isset($_REQUEST['conf']['wiki']))? $_REQUEST['conf']['wiki'] : array();
		$_REQUEST['conf']['wiki']['allow_registration'] = (isset($_REQUEST['conf']['wiki']['allow_registration']))? true : false;
		$_REQUEST['conf']['wiki']['page_edit_require_login'] = (isset($_REQUEST['conf']['wiki']['page_edit_require_login']))? true : false;
		
		$_REQUEST['conf']['database']['port'] = ($_REQUEST['conf']['database']['port'] == '')? null : $_REQUEST['conf']['database']['port'];
		$_REQUEST['conf']['database']['socket'] = ($_REQUEST['conf']['database']['socket'] == '')? null : $_REQUEST['conf']['database']['socket'];
		
		?>
		<form method="post" action="/setup.php?stage=2">
			<?php
			$db = new mysqli($_REQUEST['conf']['database']['hostname'], $_REQUEST['conf']['database']['username'], $_REQUEST['conf']['database']['password'], $_REQUEST['conf']['database']['database'], $_REQUEST['conf']['database']['port'], $_REQUEST['conf']['database']['socket']);
			if ($db->connect_error){
				echo '<br>Failed to connect to MySQL database. Go back and ensure your database settings are correct.';
			}else{
				echo 'Successfully connected to MySQL database! Fill out the user form below.';
				$db->close();
			}

			
			echo '<hr class="featurette-divider">';
			?>
			<small>Username (used as a display name)</small>
			<input type="text" name="username" class="form-control">
			<small>Email Address (used for login)</small>
			<input type="email" name="email" class="form-control">
			<small>Password</small>
			<input type="password" name="password" class="form-control">
			
			<?php
			
			echo '<hr class="featurette-divider">';
			
			$CONFIG_FILE = '$CONF = '.var_export($_REQUEST['conf'], true);
			echo '<small>Resulting Config File</small><br>';
			echo '<textarea style="width: 100%; height: 150px;" name="configfile">'.$CONFIG_FILE.'</textarea>';

			?>
			<hr class="featurette-divider">
			<button class="btn btn-success">Continue</button>
		</form>
		<?php break;
	default: ?>
		<p></p>
		<form method="post" action="/setup.php?stage=1">
			<?php arrayMap($CONF); ?>
			<hr class="featurette-divider">
			<button class="btn btn-success">Continue</button>
		</form>
		<?php break;
}
?>


	</div>
</body>
</html>
