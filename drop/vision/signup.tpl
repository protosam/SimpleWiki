<!-- BEGIN: signup -->
<!DOCTYPE html>
<html lang="en">
  <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Login</title>

        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/css/bootstrap.min.css">
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/css/bootstrap-theme.min.css">
        <link href="/resources/style.css" rel="stylesheet">
        <link href="/resources/octicons/octicons.css" rel="stylesheet">
        <link href="/resources/signup.css" rel="stylesheet">

        <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.4/jquery.min.js"></script>
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/js/bootstrap.min.js"></script>
		<script src='https://www.google.com/recaptcha/api.js'></script>
		<script src="/resources/js/common.js"></script>

        <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
        <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
        <!--[if lt IE 9]>
                <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
                <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
        <![endif]-->
  </head>

  <body>

    <div class="container">

      <form class="form-signin" method="post" action="/login.php?signup">
        <h2 class="form-signin-heading">Create New Account</h2>

	<!-- BEGIN: fail -->
	<div class="alert alert-danger" role="alert">
		<strong>Failed!</strong> {reason}
	</div>
	<!-- END: fail -->
        <label for="inputUsername" class="sr-only">Username</label>
        <input type="text" id="inputUsername" class="form-control" name="username" placeholder="Username" required autofocus>

        <label for="inputEmail" class="sr-only">Username</label>
        <input type="email" id="inputEmail" class="form-control" name="email" placeholder="Email" required>

        <label for="inputPassword" class="sr-only">Password</label>
        <input type="password" id="inputPassword" class="form-control" name="password" placeholder="Password" required>

        <label for="inputRePassword" class="sr-only">Password</label>
        <input type="password" id="inputRePassword" class="form-control" name="repassword" placeholder="Retype Password" required>

		<p><input type="checkbox" id="agreedtermsofservice" name="agreedtermsofservice" required> Check the box to confirm you understand and agree to the <a href="/termsofservice.php">Terms of Service</a></p>

		{google_recaptcha}


        <button class="btn btn-lg btn-primary btn-block" type="submit">Create Account</button>
		<a href="/login.php" class="btn btn-lg btn-warning btn-block">Cancel</a>
      </form>

    </div> <!-- /container -->


    <!-- IE10 viewport hack for Surface/desktop Windows 8 bug -->
    <script src="/resources/js/ie10-viewport-bug-workaround.js"></script>
  </body>
</html>
<!-- END: signup -->
