<?php
require 'database.php';
require 'class-ts-query.php';
require 'header.php';
$ts_query = new TSQuery( $conn );
$new_password = $_POST['new_pass'];
$new_pass_retype = $_POST['new_pass_retype'];
$user_id = $_POST['user_id'];
$code = $_POST['code'];

if ( $new_password == $new_pass_retype ) {
	$encoded_new_pass = sha1( $new_password );
	$update_password = $ts_query->update_password( $encoded_new_pass, $user_id );
	if ( $update_password ) {
		$update_reset_pass = $ts_query->update_pass_reset ( $user_id ); 
		echo "Password Updated! Click here to <a href='signin.php'>sign in.</a>";
	}
} else {
	echo "Passwords do not match click to <a href='forgot_pass.php?code=$code&user_id=$user_id'>reset</a>";
}