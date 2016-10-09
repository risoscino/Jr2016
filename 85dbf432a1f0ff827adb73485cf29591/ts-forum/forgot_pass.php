<?php
require 'database.php';
require 'class-ts-query.php';
require 'header.php';
$ts_query = new TSQuery( $conn );

if ( $_GET['code'] ) {
	$get_user_id = $_GET['user_id'];
	$get_code = $_GET['code'];
	$user_by_id = $ts_query->get_user_by_id( $get_user_id );

	if( $user_by_id ) {
		$db_code = $user_by_id['pass_reset'];
		$db_username = $user_by_id['user_name'];
		$db_user_id = $user_by_id['user_id'];
		if ( $get_user_id == $db_user_id && $get_code == $db_code ) {
			echo '
				<form action="forgot_pass_complete.php?code="'. $get_code .'" method="POST">
				<div class="form-group">
					<label for="new_pass">Enter a new password</label>
					<input type="password" class="form-control" name="new_pass" placeholder="New Password">
					<input type="hidden" name="code" value="'. $get_code .'">
					<label for="new_pass">Retype new password</label>
					<input type="password" class="form-control" name="new_pass_retype" placeholder="Retype Password">
					<input type="hidden" name="user_id" value="'. $db_user_id .'">
					<input class="btn btn-primary" name="submit" type="submit" value="Update Password" />
				</div>
			</form>';
		}
	} else {
		echo "Couldn't find user in Database";
	}

}