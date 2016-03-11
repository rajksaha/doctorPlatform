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
	
	$invID = $_POST['invID'];
	
	$result = mysql_query("SELECT ip.`id`, ip.`appointMentID`, ip.`invID`, ip.`note`, ip.`checked`, ir.result, a.date, i.name AS invName
						FROM `inv_prescription` ip
						JOIN inv i ON i.id = ip.invID
						JOIN appointment a ON a.appointMentID = ip.appointMentID
						JOIN patient p ON p.patientCode = a.patientCode
						LEFT JOIN inv_report ir ON ir.invPrescribeID = ip.id
						WHERE ip.invID = '$invID' AND p.patientID = '$patientID' ORDER BY a.date DESC");
	
	$data = array();
	while ($row=mysql_fetch_array($result)){
		
		array_push($data,$row);
	}
	
	echo json_encode($data);
}

function getinvReport($appointmentID, $patientID){
	
	$sql = mysql_query("SELECT ip.`id`, ip.`appointMentID`, ip.`invID`, ip.`note`, ip.`checked`, ir.result, a.date
						FROM `inv_prescription` ip
						JOIN appointment a ON a.appointMentID = ip.appointMentID
						LEFT JOIN inv_report ir ON ir.invPrescribeID = ip.id
						WHERE ip.invID = 713");
	
	$data = array();
	while ($row=mysql_fetch_array($sql)){
		array_push($data,$row);
	}
	
	return $data;
}
?>