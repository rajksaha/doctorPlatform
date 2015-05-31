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
	
	$result = getPrescribedFamilyDisease($appointmentID, $patientID);
	
	$data = array();
	while ($row=mysql_fetch_array($result)){
		array_push($data,$row);
	}
	
	echo json_encode($data);
	
}elseif ($query_no == 1){
	
	$diseaseName = $_POST['diseaseName'];
	$diseaseID = getDiseasIDByName($diseaseName);
	$relation = $_POST['relation'];
	$present = $_POST['present'];
	$type = $_POST['type'];
	$detail = $_POST['detail'];
	
	 insertFamilyHistory($patientID, $diseaseID, $relation, $present, $type, $detail);
	
	
}elseif ($query_no == 2){
	
	$familyHistoryID = $_POST['familyHistoryID'];
	
	mysql_query("INSERT INTO `prescription_family_disease`(`appointMentID`, `familyDiseaseID`) VALUES ('$appointmentID', '$familyHistoryID')");
	
	echo "INSERT INTO `prescription_family_disease`(`appointMentID`, `familyDiseaseID`) VALUES ('$appointmentID', '$familyHistoryID')";
	
	
}elseif ($query_no == 3){
	
	$familyHistoryID = $_POST['familyHistoryID'];
	
	mysql_query("DELETE FROM `prescription_family_disease` WHERE `appointMentID` = $appointmentID AND `familyDiseaseID` = $familyHistoryID");
	
}elseif ($query_no == 4){
	
	
	$diseaseName = $_POST['diseaseName'];
	$diseaseID = getDiseasIDByName($diseaseName);
	$relation = $_POST['relation'];
	$present = $_POST['present'];
	$type = $_POST['type'];
	$detail = $_POST['detail'];
	$familyHistoryID = $_POST['familyHistoryID'];
	
	mysql_query("UPDATE `patient_family_history` SET `diseaseID`= '$diseaseID',`relation`= '$relation',`present`= '$present',`type`= '$type',`detail`= '$detail' WHERE `id` = '$familyHistoryID'");
}elseif ($query_no == 5){
	
	$familyHistoryID = $_POST['familyHistoryID'];
	mysql_query("DELETE FROM `patient_family_history` WHERE `id` =$familyHistoryID");
	
	mysql_query("DELETE FROM `prescription_family_disease` WHERE `appointMentID` = $appointmentID AND `familyDiseaseID` = $familyHistoryID");
	
}elseif ($query_no == 6){
	
	$result = mysql_query("SELECT `id`, `name` FROM `relation` WHERE 1 = 1");
	
	$data = array();
	while ($row=mysql_fetch_array($result)){
		array_push($data,$row);
	}
	
	echo json_encode($data);
}
?>