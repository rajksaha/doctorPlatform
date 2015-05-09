<?php

session_start();
include('../config.inc');
include('../commonServices/prescriptionService.php');

if (!isset($_SESSION['username'])) {
	header('Location: index.php');
}
$username = $_SESSION['username'];
$appointmentID = $_SESSION['appointmentID'];
$patientCode = $_SESSION['patientCode'];
$date=date("Y-m-d");
$query_no=  mysql_real_escape_string($_POST['query']);

if($query_no == 0){

	$diseaseID = $_POST['diseaseID'];
	
	$result = getDoctorsDrugSettingByDisease($username, $diseaseID);
	
	while ($row=mysql_fetch_array($result)){
		array_push($data,$row);
	}
	
	echo json_encode($data);
}

if($query_no == 1){

	$diseaseID = $_POST['diseaseID'];

	$result = getDoctorsInvSettingByDisease($doctorCode, $diseaseID);

	while ($row=mysql_fetch_array($result)){
		array_push($data,$row);
	}

	echo json_encode($data);
}

if($query_no == 2){

	$diseaseID = $_POST['diseaseID'];

	$result = getDoctorsAdviceSettingByDisease($doctorCode, $diseaseID);

	while ($row=mysql_fetch_array($result)){
		array_push($data,$row);
	}

	echo json_encode($data);
}

?>