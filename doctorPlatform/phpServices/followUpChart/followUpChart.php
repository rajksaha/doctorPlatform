<?php

session_start();
include('../config.inc');
include('../commonServices/prescriptionService.php');
if (!isset($_SESSION['username'])) {
	header('Location: index.php');
}
$username = $_SESSION['username'];
$doctorID = $_SESSION['doctorID'];
$appointmentID = $_SESSION['appointmentID'];
$patientCode = $_SESSION['patientCode'];
$patientID = $_SESSION['patientID'];
$date=date("Y-m-d");
$query_no=  $_POST['query'];


if($query_no== 0){
	
	
	$result = getPatientOldPrecription($appointmentID, $patientID, $username);
	
	$data = array();
	while ($row=mysql_fetch_array($result)){
		
		$row['invReportList'] = getinvReport($row['appointmentID'], $patientID);
		array_push($data,$row);
	}
	
	echo json_encode($data);
}

function getinvReport($appointmentID, $patientID){
	
	$sql = mysql_query("SELECT i.name AS invName, i.id AS invID, ir.result, ir.status
			FROM `patient` p
			JOIN appointment a ON p.patientCode = a.patientCode
			JOIN inv_prescription ip ON a.appointMentID = ip.appointMentID
			LEFT JOIN inv_report ir ON ip.id = ir.invPrescribeID
			JOIN inv i ON ip.invID = i.id
			WHERE p.patientID ='$patientID' AND  a.appointmentID = '$appointmentID'");
	
	$data = array();
	while ($row=mysql_fetch_array($sql)){
		array_push($data,$row);
	}
	
	return $data;
}
?>