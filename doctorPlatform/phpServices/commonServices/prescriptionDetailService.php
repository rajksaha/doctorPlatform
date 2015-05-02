<?php

session_start();
include('../config.inc');
include('../commonServices/prescriptionService.php');
if (!isset($_SESSION['username'])) {
	header('Location: index.php');
}
$username = $_SESSION['username'];
$query_no=  $_POST['query'];


if($query_no== 0){
	
	$appointmentID = $_POST['appointmentID'];
	
	
	$result = getPresCribedDrugs($appointmentID);
	
	$data = array();
	while ($row=mysql_fetch_array($result)){
		array_push($data,$row);
	}
	
	echo json_encode($data);
	
	
}else if($query_no==1){
	
	$appointmentID = $_POST['appointmentID'];
	
	
	$result = getPrescribedInv($appointmentID);
	
	$data = array();
	while ($row=mysql_fetch_array($result)){
		array_push($data,$row);
	}
	
	echo json_encode($data);
}

if($query_no==2){
	
	$appointmentID = $_POST['appointmentID'];
	
	
	$result = getPrescribedAdvice($appointmentID);
	
	$data = array();
	while ($row=mysql_fetch_array($result)){
		array_push($data,$row);
	}
	
	echo json_encode($data);
	
	
}
else if($query_no==3){
	
	$appointmentID = $_POST['appointmentID'];
	
	
	$result = getPrescribedVital($appointmentID);
	
	$data = array();
	while ($row=mysql_fetch_array($result)){
		array_push($data,$row);
	}
	
	echo json_encode($data);
}
else if($query_no==4){
	
	$appointmentID = $_POST['appointmentID'];
	
	
	$result = getPrescribedComplain($appointmentID);
	
	$data = array();
	while ($row=mysql_fetch_array($result)){
		array_push($data,$row);
	}
	
	echo json_encode($data);
	
}else if($query_no==5){
	
	$appointmentID = $_POST['appointmentID'];
	$typeCode = $_POST['typeCode'];
	$patientID = $_POST['patientID'];
	
	
	$result = getPrescribedHistory($appointmentID, $typeCode, $patientID);
	
	$data = array();
	while ($row=mysql_fetch_array($result)){
		array_push($data,$row);
	}
	
	echo json_encode($data);
	
}elseif ($query_no==6){
	
	$appointmentID = $_POST['appointmentID'];
	
	
	$result = getPrescribedDiagnosis($appointmentID);
	
	$data = array();
	while ($row=mysql_fetch_array($result)){
		array_push($data,$row);
	}
	
	echo json_encode($data);
	
}elseif ($query_no==7){

	$appointmentID = $_POST['appointmentID'];


	$result = getPrescribedNextVisit($appointmentID);
	
	$rec = mysql_fetch_assoc($result);
	
	echo json_encode($rec);
	
}elseif ($query_no==8){

	$appointmentID = $_POST['appointmentID'];


	$result = getPrescribedReffredDoctor($appointmentID);
	
	$rec = mysql_fetch_assoc($result);
	
	echo json_encode($rec);
}
	
?>