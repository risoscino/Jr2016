<?php
include 'database.php';
include 'class-ts-query.php';

$ts_query = new TSQuery( $conn );

$ts_query->create_categories_table();
$ts_query->create_posts_table();
$ts_query->create_topics_table();
$ts_query->create_user_table();
$ts_query->create_votes_table();

echo "Finished!";