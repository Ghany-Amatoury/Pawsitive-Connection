<?php
	$servername = "localhost";
	$username = "root";
	$password = "";
	$dbname = "pawsitive_connection";

	// Create connection
	$db = new mysqli($servername, $username, $password, $dbname);
	// Check connection
	if ($db->connect_error) {
		die('Connection error: ' . $db->connect_error);
	}
?>