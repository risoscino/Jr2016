<?php
session_start();
include 'database.php';
include 'class-ts-query.php';

$ts_query = new TSQuery( $conn );

$user_id = (int)$_SESSION['user_id'];
$vote = (int) $_POST['vote'];
$topic_id = (int)$_POST['topic_id'];
$votes = $ts_query->add_vote( $topic_id, $vote, $user_id );
$users_votes_results = $ts_query->get_total_vote ( $topic_id );
$arr = array ( 'votes' => $votes, 'total_vote' => $users_votes_results['total_vote'], 'users_voted' => $users_votes_results['users_voted'] );
echo json_encode($arr);
