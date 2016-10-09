<?php
include 'database.php';
include 'header.php';
include 'class-ts-query.php';

$ts_query = new TSQuery( $conn );

echo '<h3>Sign up</h3>';

if ($_SERVER['REQUEST_METHOD'] != 'POST') {
	echo '<form method="post" action="">
			<div class="form-horizontal">
				<div class="form-group">
					<label for="user_name">Username</label>
					<input type="text" class="form-control" name="user_name" id="user_name" placeholder="Oomph">
				</div>

			<div class="form-group">
				 <label for="user_pass">Password</label>
				 <input type="password" class="form-control" name="user_pass" id="user_pass" placeholder="Password">
			</div>
			<div class="form-group">
				 <label for="user_pass_again">Password</label>
				 <input type="password" class="form-control" name="user_pass_check" id="user_pass_again" placeholder="Password">
			</div>
			<div class="form-group">
				 <label for="email">Email</label>
				 <input type="email" class="form-control" name ="user_email" id="user_email" placeholder="Email">
			</div>
		<input class="btn btn-primary" type="submit" value="Add User" />
		</div>
	 </form>';
} else {
	$errors = array();
	if ( isset($_POST['user_name'] ) )
	{
		//the user name exists
		if ( !ctype_alnum( $_POST['user_name'] ) )
		{
			$errors[] = 'The username can only contain letters and digits.';
		}
		if ( strlen( $_POST['user_name'] ) > 30 )
		{
			$errors[] = 'The username cannot be longer than 30 characters.';
		}
	}
	else
	{
		$errors[] = 'The username field must not be empty.';
	}
	if( isset( $_POST['user_pass'] ) )
	{
		if( $_POST['user_pass'] != $_POST['user_pass_check'] )
		{
			$errors[] = 'The two passwords did not match.';
		}
	}
	else
	{
		$errors[] = 'The password field cannot be empty.';
	}
	 
	if( !empty( $errors ) ) 
	{
		echo 'Uh-oh.. a couple of fields are not filled in correctly..';
		echo '<ul>';
		foreach( $errors as $key => $value )
		{
			echo '<li>' . $value . '</li>'; 
		}
		echo '</ul>';
	}
	else
	{
		$ts_query->add_user ( $_POST['user_name'], sha1($_POST['user_pass']), $_POST['user_email'], 0 );
	}
}
include 'footer.php';
?>