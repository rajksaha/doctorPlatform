<?php

$hostname = 'localhost';        // Your MySQL hostname. Usualy named as 'localhost', so you're NOT necessary to change this even this script has already online on the internet.
$dbname   = 'doctor'; // Your database name.
$username = 'root';             // Your database username.
$password = '';                 // Your database password. If your database has no password, leave it empty.

// Let's connect to host
mysql_connect($hostname, $username, $password) or DIE('Connection to host is failed, perhaps the service is down!');
// Select the database
mysql_select_db($dbname) or DIE('Database name is not available!');
mysql_query('SET CHARACTER SET utf8');
mysql_query("SET SESSION collation_connection ='utf8_general_ci'") or die (mysql_error());

$result = mysql_query("SELECT `id`, `type`, `b_id`, `b_name`, `str` FROM `brand` WHERE 1 = 1");

while($row = mysql_fetch_array($result)){
	
	$type = $row['type'];
	$brand = $row['b_id'];
	$drug = $row['b_name'];
	$str = $row['str'];
	
	$hostname = 'localhost';        // Your MySQL hostname. Usualy named as 'localhost', so you're NOT necessary to change this even this script has already online on the internet.
	$dbname   = 'doctorplatform'; // Your database name.
	$username = 'root';             // Your database username.
	$password = '';                 // Your database password. If your database has no password, leave it empty.

	// Let's connect to host
	mysql_connect($hostname, $username, $password) or DIE('Connection to host is failed, perhaps the service is down!');
	// Select the database
	mysql_select_db($dbname) or DIE('Database name is not available!');
	mysql_query('SET CHARACTER SET utf8');
	mysql_query("SET SESSION collation_connection ='utf8_general_ci'") or die (mysql_error());


	mysql_query("INSERT INTO `drug`(`typeID`, `companyID`, `drugName`, `strength`) VALUES ('$type','$brand','$drug','$str')");
	echo "INSERT INTO `drug`(`typeID`, `companyID`, `drugName`, `strength`) VALUES ('$type','$brand','$drug','$str')";
}

?>