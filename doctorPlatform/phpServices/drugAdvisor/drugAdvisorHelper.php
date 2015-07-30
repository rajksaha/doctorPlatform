<?php

session_start();
include('../config.inc');
include('../commonServices/prescriptionService.php');
include('../commonServices/prescriptionInsertService.php');
include('../commonServices/parentInsertService.php');
if (!isset($_SESSION['username'])) {
	header('Location: index.php');
}
$username=$_SESSION['username'];
$date=date("Y-m-d");
$appointmentID = $_SESSION['appointmentID'];
//$query_no=  mysql_real_escape_string($_POST['query']);

$json = file_get_contents('php://input');
$dataObject = json_decode($json);

$query_no = $dataObject->query;

if($query_no==1){
	
	$sql = "SELECT dat.id AS drugAdviceID, dat.bangla, dat.english, dat.pdf
			FROM `drugAdviceType` dat
			WHERE dat.doctorType =0 AND dat.id <> 0
			UNION
			SELECT dat.id AS drugAdviceID, dat.bangla, dat.english, dat.pdf
			FROM `drugAdviceType` dat
			LEFT JOIN doctorsettings ds ON dat.doctorType = ds.category
			JOIN doctor d ON d.doctorID = ds.doctorID
			WHERE d.doctorCode = '$username'";
	$result=mysql_query($sql);
	
	$data = array();
	while ($row=mysql_fetch_array($result)){
		array_push($data,$row);
	}
	
	echo json_encode($data);
	
}else if($query_no==2){// insert in complain
	
	$bangla = $dataObject->data;
	
	$asciiString = mb_convert_encoding($bangla, "ISO-8859-1", "UTF-8");
	
	mysql_query("INSERT INTO `drugadvicetype`(`doctorType`, `bangla`, `english`, `pdf`) VALUES (0,'$bangla','','$asciiString')");
	
	
	
}else if($query_no==3){// get all added CC 
	
	$result = getPrescribedComplain($appointmentID);
	
	$data = array();
	while ($row=mysql_fetch_array($result)){
		array_push($data,$row);
	}
	echo json_encode($data);
	
}elseif ($query_no == 4){
	
	$id=$dataObject->id;
	
	mysql_query("DELETE FROM `complain` WHERE `id` = '$id'");
}

?>