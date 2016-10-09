<?php
$servername = "127.0.0.1";
$username = isset($_SERVER['DB_USERNAME']) ? $_SERVER['DB_USERNAME'] : 'root';
$password = isset($_SERVER['DB_PASSWORD']) ? $_SERVER['DB_PASSWORD'] : '';
$db_name = isset($_SERVER['DB_NAME']) ? $_SERVER['DB_NAME'] : 'team_stream';
// set database connection and test
$conn = new mysqli($servername, $username, $password, $db_name);
/* check connection */
if ( !$conn ) {
	printf("Connect failed: %s\n", mysqli_connect_error());
	exit();
}
