<?php

session_start();
include('../config.inc');
include('../commonServices/prescriptionService.php');
include('../commonServices/prescriptionInsertService.php');
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
	
	$reqAppointmentID = $_POST['appointmentID'];
	
	$sql = mysql_query("SELECT ip.`id` , ip.`appointMentID` , ip.`invID` , ip.`note` , ip.`checked` , ir.result, ir.status, i.name AS invName, ir.id AS savedreportID
	FROM `inv_prescription` ip
	JOIN inv i ON ip.invID = i.id
	LEFT JOIN inv_report ir ON ir.invPrescribeID = ip.id
	WHERE ip.`appointMentID` = $reqAppointmentID ");

	
	$data = array();
	while ($row=mysql_fetch_array($sql)){
		array_push($data,$row);
	}
	
	echo json_encode($data);
}
?>