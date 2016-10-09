<?php
session_start();
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
"http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
 	<meta http-equiv="X-UA-Compatible" content="IE=edge">
 	<meta name="viewport" content="width=device-width, initial-scale=1">
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
	<meta name="description" content="" />
	<meta name="keywords" content="put, keywords, here" />
	<title>Hack Day Forums</title>
	<link rel="stylesheet" href="ts-forum.css" type="text/css">
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.3/jquery.min.js"></script>
	<!-- Latest compiled and minified CSS -->
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/css/bootstrap.min.css">

	<!-- Optional theme -->
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/css/bootstrap-theme.min.css">

	<!-- Latest compiled and minified JavaScript -->
	<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/js/bootstrap.min.js"></script>
</head>
<body>
<h1>Hack Day Forums</h1>
	<div id="wrapper">
	<div id="menu">
		<a class="item" href="index.php">Home</a> -
		<a class="item" href="create_topic.php">Create a topic</a> -
		<a class="item" href="create_cat.php">Create a category</a>
		<div id="userbar">
	<?php
		if( $_SESSION['signed_in'] ) {
			echo 'Hello ' . $_SESSION['user_name'] . '. Not you? <a href="signout.php">Sign out</a>';
		} else {
			echo '<a href="signin.php">Sign in</a> or <a href="signup.php">create an account</a>.';
		}
		?>
		</div>
	</div>
		<div id="content">