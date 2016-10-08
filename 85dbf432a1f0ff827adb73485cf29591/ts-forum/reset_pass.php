<?php
include 'header.php';
include 'class-ts-query.php';

$ts_query = new TSQuery( $conn );
echo '<form action="reset_pass.php" method="POST">
	<div class="form-group">
		<label for="email">Email for forgetten password</label>
		<input type="email" class="form-control" name="user_email" placeholder="Email">
		<input class="btn btn-primary" name="submit" type="submit" value="Submit" />
	</div>
</div>';

$email = $_POST['user_email'];
$email = mysqli_real_escape_string( $conn, $email );

echo "<br><br>";
if( $_POST['submit'] ) { // validation passed now we will check the tables
	$results = $ts_query->get_user_email( $email );
	$user_email = $results['user_email'];
	$user_id = $results['user_id'];
	if ( $user_email ) {
		$code = rand( 200000,1000000 );
		$subject = "Password Reset From Oomph Forums :D";
		$server_name =
		$server_name = dirname( $_SERVER['PHP_SELF']);
		$uri = '/forgot_pass.php';
		$server = $_SERVER['SERVER_NAME'];
		$url = $server . $server_name . $uri;
		$headers = 'From: Oomph Froums' . "\r\n";
		$body = "
		This is an automated email.  Do not reply. 
		click the link below to reset your password or paste link into your browser " . $url . "?code=" . $code . "&user_id=" . $user_id;
		$pass_reset = $ts_query->reset_password( $code, $user_id );
		echo "Check your email!";
	} else {
		echo "<center><font face='Verdana' size='2' color=red><b>No Password</b><br>
		Sorry Your address is not there in our database . You can signup and login to use our site. 
		<BR><BR><a href='signup.php'> Sign UP </a> </center>";
		exit;
 	}
 }