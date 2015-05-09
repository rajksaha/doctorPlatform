<?php
/* include('../config.inc'); */

if (!isset($_SESSION['username'])) {
	header('Location: index.php');
}

function getDiseasIDByName($diseaseName){
	
	
	$rec = mysql_query("SELECT `id` FROM `disease` WHERE `name` = '$diseaseName'");
	$result = mysql_fetch_assoc($rec);
	
	if($result['id'] != null && $result['id'] > 0){
		
		return $result['id'];
	}else{
		mysql_query("INSERT INTO `disease`( `name`) VALUES ('$diseaseName')");
		
		return mysql_insert_id();
	}
	
}


?>