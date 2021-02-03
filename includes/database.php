<?php
require dirname(__FILE__) . "/utils.php";

// admin account
$ADMIN_ACCOUNT_NAME = "admin";				// username
$ADMIN_ACCOUNT_PASSWORD = "admin";				// password

// static global variables
$panel_name = "Simple Tournament";					// site name
$welcome_msg = "Welcome to Our Tournament"; // description

//sql db details
$db_server = 'localhost';							// database ip
$db_name = 'tournament';								// database name
$db_username = 'gus';							// database username
$db_password = '123';							// database password

try {
	$GLOBALS['db'] = new PDO('mysql:host=' . $db_server . ';dbname=' . $db_name, $db_username, $db_password,[PDO::ATTR_DEFAULT_FETCH_MODE=>PDO::FETCH_ASSOC]);
}
catch(PDOException $e) 
{
	die('error');
}

?>