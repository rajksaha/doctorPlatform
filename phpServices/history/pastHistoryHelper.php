<?php

session_start();
include('../config.inc');
include('../commonServices/prescriptionService.php');
include('../commonServices/prescriptionInsertService.php');
include('../commonServices/parentInsertService.php');

if (!isset($_SESSION['username'])) {
	header('Location: index.php');
}
if (isset($_SESSION['appointmentID'])) {
	$appointmentID = $_SESSION['appointmentID'];
}
if (isset($_SESSION['patientCode'])) {
	$patientCode = $_SESSION['patientCode'];
}

if (isset($_SESSION['patientID'])) {
	$patientID = $_SESSION['patientID'];
}
$username = $_SESSION['username'];


$date=date("Y-m-d");
$query_no=  mysql_real_escape_string($_POST['query']);


if($query_no== 0){
	$result = getPastDisease($appointmentID, $patientID);
	
	$data = array();
	while ($row=mysql_fetch_array($result)){
		array_push($data,$row);
	}
	
	echo json_encode($data);
	
}elseif ($query_no == 1){
	
	$diseaseName = $_POST['diseaseName'];
	$diseaseID = getDiseasIDByName($diseaseName);
	$isPresent = $_POST['isPresent'];
	$detail = $_POST['detail'];
	
	
	insertPastHistory($patientID, $diseaseID, $isPresent, $detail);
	
	
}elseif ($query_no == 2){
	
	$pastHistoryID = $_POST['pastHistoryID'];
	
	mysql_query("INSERT INTO `prescription_past_disease`(`appointMentID`, `pastDiseaseID`) VALUES ('$appointmentID', '$pastHistoryID')");
	
	echo "INSERT INTO `prescription_past_disease`(`appointMentID`, `pastDiseaseID`) VALUES ('$appointmentID', '$pastHistoryID')";
	
	
}elseif ($query_no == 3){
	
	$pastHistoryID = $_POST['pastHistoryID'];
	
	mysql_query("DELETE FROM `prescription_past_disease` WHERE `appointMentID` = $appointmentID AND `pastDiseaseID` = $pastHistoryID");
	
}elseif ($query_no == 4){
	
	
	$diseaseName = $_POST['diseaseName'];
	$diseaseID = getDiseasIDByName($diseaseName);
	$isPresent = $_POST['isPresent'];
	$detail = $_POST['detail'];
	$pastHistoryID = $_POST['pastHistoryID'];
	
	mysql_query("UPDATE `patient_past_disease` SET `diseaseID`= '$diseaseID', `detail`= '$detail' WHERE `id` = '$pastHistoryID'");
}elseif ($query_no == 5){
	
	$pastHistoryID = $_POST['pastHistoryID'];
	mysql_query("DELETE FROM `patient_past_disease` WHERE `id` =$pastHistoryID");
	
	mysql_query("DELETE FROM `prescription_past_disease` WHERE `appointMentID` = $appointmentID AND `pastHistoryID` = $pastHistoryID");
	
}
?>