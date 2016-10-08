<?php
session_start();
include 'database.php';
include 'class-ts-query.php';
include 'header.php';
$ts_query = new TSQuery( $conn );

echo '<h2>Create a topic</h2>';
if( $_SESSION['signed_in'] == false) {
	echo 'Sorry, you have to be <a href="signin.php">signed in</a> to create a topic.';
} else {

	if($_SERVER['REQUEST_METHOD'] != 'POST') {
		$result = $ts_query->display_categories();

		if ( !$result ) {
			echo 'Error while selecting from database. Please try again later.';
		} else {
			if( $result->num_rows == 0 ) {
				//there are no categories, so a topic can't be posted

				if( $_SESSION['user_level'] == 1 ) {
					echo 'You have not created categories yet.';
				} else {
					echo 'Before you can post a topic, you must wait for an admin to create some categories.';
				}
			} else {

				echo '<form method="post" action="">
						<div class="form-group">
						<label for="topic_subject">Topic Subject</label>
							<input type="text" class="form-control" name="topic_subject" placeholder="Topic Subject">
						</div>
							<label for="topic_cat">Topic Category</label>';
					echo '<select name="topic_cat">';
					while( $row = $result->fetch_assoc() )
					{
						echo '<option value="' . $row['cat_id'] . '">' . $row['cat_name'] . '</option>';
					}
				echo '</select>'; 
					echo
					'<br /><br /><label for="post_content">Subject Message</label>
						<textarea class="form-control" name"post_content rows="3"></textarea>
						<input class="btn btn-primary" type="submit" value="Create topic" />
				 	</form>';
			}
		}
	} else {
		//start the transaction
		$result = $ts_query->begin_work();
		if ( !$result ) {
			echo 'An error occured while creating your topic. Please try again later.';
		} else {
			//the form has been posted, so save it
			//insert the topic into the topics table first, then we'll save the post into the posts table
			$ts_query->add_topic( $_POST['topic_subject'], $_POST['topic_cat'], $_SESSION['user_id'] );
			if ( !$result ) {
				//something went wrong, display the error
				echo 'An error occured while inserting your data. Please try again later.' . mysql_error();
				$result = $ts_query->roll_back();
			} else {
				//the first query worked, now start the second, posts query
				//retrieve the id of the freshly created topic for usage in the posts query
				$topicid = mysqli_insert_id ( $ts_query->db_conn );
				$result = $ts_query->add_post( $_POST['post_content'], $topicid, $_SESSION['user_id'], 1, 0 );
				if( !$result ) {
					//something went wrong, display the error
					echo 'An error occured while inserting your post. Please try again later.' . mysql_error();
					$sql = "ROLLBACK;";
					$result = $ts_query->roll_back();
				} else {
					$result = $ts_query->commit();
					echo 'You have successfully created <a href="topic.php?id='. $topicid . '">your new topic</a>.';
				}
			}
		}
	}
}
 
include 'footer.php';
?>