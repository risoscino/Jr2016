<?php
include 'database.php';
include 'class-ts-query.php';
include 'header.php';

$ts_query = new TSQuery( $conn );
if( $_SESSION['signed_in'] == false) {
	echo 'Sorry, you have to be <a href="signin.php">signed in</a> to create a category';
} else { 
	if( $_SERVER['REQUEST_METHOD'] != 'POST' ) {
		//the form hasn't been posted yet, display it
		echo '<form method="post"action="">
				<div class="form-group">
					<label for="cat_name">Category Name</label>
					<input class="form-control" type="text" name="cat_name"  placeholder="Category Name">
				</div>
				<div class="form-group">
				<label for="cat_description">Category Description</label>
					<textarea class="form-control" name="cat_description" rows="5"></textarea>
				</div>
			<input class="btn btn-primary" type="submit" value="Category Category" />
			</form>';
	} else {
		//the form has been posted, so save it
		$result = $ts_query->add_category( $_POST['cat_name'], $_POST['cat_description'] );
		if( !$result ) {
			//something went wrong, display the error
			echo 'Error' . mysql_error();
		} else {
			echo 'New category successfully added. <a href="index.php">Check it out here</a>';

		}
	}
}