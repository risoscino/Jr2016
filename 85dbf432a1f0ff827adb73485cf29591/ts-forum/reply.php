<?php
session_start();
include 'database.php';
include 'header.php';
include 'class-ts-query.php';
$ts_query = new TSQuery( $conn );

if ( $_SERVER['REQUEST_METHOD'] != 'POST' ) {
	echo 'This file cannot be called directly.';
} else {
	if ( !$_SESSION['signed_in'] ) {
		echo 'You must be signed in to post a reply.';
	} else {
		$result = $ts_query->add_reply( $_POST['reply-content'], $_GET['id'], $_SESSION['user_id'] );
		if ( !$result ) {
			echo 'Your reply has not been saved, please try again later.';
		} else {
			echo 'Your reply has been saved, check out <a href="topic.php?id=' . htmlentities($_GET['id']) . '">the topic</a>.';
		}
	}
}
 
include 'footer.php';
?>