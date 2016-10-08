<?php
session_start();
include 'database.php';
include 'header.php';
include 'class-ts-query.php';

$ts_query = new TSQuery( $conn );
echo '<h3>Sign in</h3>';
// if signed in dont show 
if( isset( $_SESSION['signed_in'] ) && $_SESSION['signed_in'] == true) {
	echo 'You are already signed in, you can <a href="signout.php">sign out</a> if you want.';
} else {
	if( $_SERVER['REQUEST_METHOD'] != 'POST' ) {
		/*the form hasn't been posted yet, display it
		  note that the action="" will cause the form to post to the same page it is on */
		echo '<form method="post" action="" class="form-inline">
			<div class="form-group">
				 <label for="user_name">Username</label>
				 <input type="text" class="form-control" name="user_name" id="user_name">
			</div>
			<div class="form-group">
				 <label for="user_pass">Password</label>
				 <input type="password" class="form-control" name="user_pass" id="user_pass">
			</div>
			<a href=reset_pass.php>Forgot Password?<a>
			<input class="btn btn-primary" type="submit" value="Sign in" />
		 </form>';
	} else {
		/* so, the form has been posted, we'll process the data in three steps:
			1.  Check the data
			2.  Let the user refill the wrong fields (if necessary)
			3.  Varify if the data is correct and return the correct response
		*/
		$errors = array();
		 
		if( !isset( $_POST['user_name'] ) ) {
			$errors[] = 'The username field must not be empty.';
		} 

		if( !isset( $_POST['user_pass'] ) ) {
			$errors[] = 'The password field must not be empty.';
		}
		if( !empty( $errors ) ) {
			echo 'Uh-oh.. a couple of fields are not filled in correctly..';
			echo '<ul>';
			foreach($errors as $key => $value) 
			{
				echo '<li>' . $value . '</li>'; /* this generates an error list */
			}
			echo '</ul>';
		} else {
			$result = $ts_query -> sign_in( $_POST['user_name'], sha1( $_POST['user_pass'] ) );
			if( !$result )
			{
				echo 'Something went wrong while signing in. Please try again later.';
				//echo mysql_error();
			} else {
				//the query was successfully executed, there are 2 possibilities
				//1. the query returned data, the user can be signed in
				//2. the query returned an empty result set, the credentials were wrong
				if( mysqli_num_rows( $result ) == 0 )
				{
					echo 'You have supplied a wrong user/password combination. Please try again.';
				}
				else
				{
					$_SESSION['signed_in'] = true;
					// Store session values so we can use again
					while( $row = mysqli_fetch_assoc($result) )
					{
						$_SESSION['user_id'] = $row['user_id'];
						$_SESSION['user_name']  = $row['user_name'];
						$_SESSION['user_level'] = $row['user_level'];
					}
					echo 'Welcome, ' . $_SESSION['user_name'] . '. <a href="index.php">Proceed to the forum overview</a>.';
				}
			}$(document).ready(function () {
	$("#basic_signup_button").click(function(){
		$('html, body').animate({scrollTop: $(".basic_form").height()});
		$(".basic_form").css("visibility","visible");
		$(".admin_form").css("visibility","hidden");
	});
});
$(document).ready(function () {
	$("#admin_signup_button").click(function(){
		$('html, body').animate({scrollTop: $(".admin_form").height()});
		$(".admin_form").css("visibility","visible");
		$(".basic_form").css("visibility","hidden");
	});
});
		}
	}
}
 
include 'footer.php';
?>