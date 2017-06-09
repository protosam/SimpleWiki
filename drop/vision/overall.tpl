<!-- BEGIN: header -->
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>{wikiname}</title>



	<script src='https://www.google.com/recaptcha/api.js'></script>
	<script src="https://code.jquery.com/jquery-3.2.1.min.js" integrity="sha256-hwg4gsxgFZhOsEEamdOYGBf13FyQuiTwlAQgxVSNgt4=" crossorigin="anonymous"></script>
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-alpha.6/css/bootstrap.min.css" integrity="sha384-rwoIResjU2yc3z8GV/NPeZWAv56rSmLldC3R/AZzGRnGxQQKnKkoFVhFQhNUwEyJ" crossorigin="anonymous">	
	<script src="https://cdnjs.cloudflare.com/ajax/libs/tether/1.4.0/js/tether.min.js" integrity="sha384-DztdAPBWPRXSA/3eYEEUWrWCy7G5KFbe8fFjk5JAIxUYHKkDx6Qin1DkWx51bBrb" crossorigin="anonymous"></script>
	<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-alpha.6/js/bootstrap.min.js" integrity="sha384-vBWWzlZJ8ea9aCX4pEW3rVHjgjt7zpkNpZk+02D9phzyeVkE+jo0ieGizqPLForn" crossorigin="anonymous"></script>


	<script src="/resources/ace/ace.js"></script>
	<script src="/resources/ace/ext-modelist.js"></script>
	<script src="/resources/js/common.js"></script>
	
	<link href="/resources/octicons/octicons.css" rel="stylesheet">
	<link href="/resources/style.css" rel="stylesheet">

	
	<meta name="apple-mobile-web-app-capable" content="yes">
	<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">

</head>
<body>
	<!-- begin navbar -->
	<nav class="navbar navbar-toggleable-sm navbar-inverse bg-inverse fixed-top">
		<button class="navbar-toggler navbar-toggler-right" type="button" data-toggle="collapse" data-target="#nvbar" aria-controls="nvbar" aria-expanded="false" aria-label="Toggle navigation">
			<span class="navbar-toggler-icon"></span>
		</button>
		<a class="navbar-brand" href="/">{wikiname}</a>

		<div class="collapse navbar-collapse" id="nvbar">
			<ul class="navbar-nav mr-auto">
				<li class="nav-item dropdown">
					<!-- BEGIN: logged_out -->
					<a class="nav-link dropdown-toggle" href="#" id="dropdown01" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Not Logged In</a>
					<div class="dropdown-menu" aria-labelledby="dropdown01">
						<a class="dropdown-item" href="/login.php">Login</a>
						<a class="dropdown-item" href="/login.php?signup">Create a New Account</a>
					</div>
					<!-- END: logged_out -->

					<!-- BEGIN: logged_in -->
					<a class="nav-link dropdown-toggle" href="#" id="dropdown01" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Logged in as {username}</a>
					<div class="dropdown-menu" aria-labelledby="dropdown01">
						<a class="dropdown-item" href="/login.php?logout" onclick="return confirm('Are you sure you want to logout?')">Logout</a>
					</div>
					<!-- END: logged_in -->
				</li>
			</ul>
			
			<form class="form-inline" action="/search/" method="post">
				<input class="form-control" name="query" type="text" placeholder="Search">
				<button class="btn btn-outline-success" type="submit">Search</button>
			</form>
		</div>
	</nav>
	
	<!-- end navbar -->

<div class="container">
	<!-- BEGIN: info -->
	<div class="alert alert-success alert-dismissible fade show" role="alert">
		<button type="button" class="close" data-dismiss="alert" aria-label="Close">
			<span aria-hidden="true">&times;</span>
		</button>
		{message}
	</div>
	<!-- END: info -->
	<!-- BEGIN: error -->
	<div class="alert alert-warning alert-dismissible fade show" role="alert">
		<button type="button" class="close" data-dismiss="alert" aria-label="Close">
			<span aria-hidden="true">&times;</span>
		</button>
		{message}
	</div>
	<!-- END: error -->
<!-- END: header -->
<!-- BEGIN: footer -->

</div>
	<div class="container marketing">
		<hr class="featurette-divider">
		<footer>
			<p class="float-right">
				<!-- BEGIN: edit_page -->
				<a href="/edit/{page}">Edit Current Page</a> &middot;
				<!-- END: edit_page -->
				<!-- BEGIN: admin_panel -->
				<a href="/admin/">Admin Panel</a> &middot;
				<!-- END: admin_panel -->
				<a href="#">Back to top</a>
			</p>
			<p class="float-left">Beta 0001a &middot; Copyright &copy; scripted.ninja</p>
		</footer>
	</div>

</body>
</html>
<!-- END: footer -->
