<?php

session_start();
include('../config.inc');
include('../commonServices/prescriptionService.php');
include('../commonServices/prescriptionInsertService.php');
if (!isset($_SESSION['username'])) {
	header('Location: index.php');
}
$username = $_SESSION['username'];
$query_no=  $_POST['query'];
$appointmentID = $_SESSION['appointmentID'];

if($query_no== 0){
	
	
	$patientID=  $_POST['patientID'];
	$result = getPatientOldPrecription($appointmentID, $patientID, $username);
	
	$data = array();
	while ($row=mysql_fetch_array($result)){
		array_push($data,$row);
	}
	
	echo json_encode($data);
	
	
}else if($query_no==1){
	
	$requestedID = $_POST['requestedID'];
	
	$result = mysql_query("SELECT `id`, `appointMentID`, `vitalID`, `vitalResult` FROM `vital_prescription` WHERE `id` = $requestedID");
	
	$row = mysql_fetch_assoc($result);
	
	insertPrescribedVital($appointmentID, $row['vitalID'], $row['vitalResult']);
	
}

if($query_no==2){
	
	$requestedID = $_POST['requestedID'];
	
	$result = mysql_query("SELECT `id`, `appointMentID`, `patientHistoryID` FROM `history_prescription` WHERE `id` = $requestedID");
	
	$row = mysql_fetch_assoc($result);
	
	insertPrescribedHistory($appointmentID, $row['patientHistoryID']);
	
}
else if($query_no==3){
    $requestedID = $_POST['requestedID'];
    $result = mysql_query("SELECT `id`, `appointMentID`, `diseaseID`, `note` FROM diagnosis WHERE id = $requestedID");
    $row = mysql_fetch_assoc($result);
    echo insertPrescribedDiagnosis($appointmentID, $row['diseaseID'], $row['note']);
}
else if($query_no==4){
    $requestedID = $_POST['requestedID'];
    $sql = mysql_query("SELECT `contentDetailID`, `contentType`, `entityID`, `detail`, `code` FROM `contentdetail` WHERE contentDetailID = $requestedID");
    $row = mysql_fetch_assoc($sql);
    insertContentDetail($appointmentID, $row['contentType'], $row['detail'], null);
}else if($query_no==5){
	
	
	
}elseif ($query_no==6){
	
	
	
}elseif ($query_no == 7){
	
	$requestedID = $_POST['requestedID'];
	
	$result = mysql_query("SELECT `id`, `appointMentID`, `drugTypeID`, `drugID`, `drugTimeID`, `drugDoseUnit`, `drugWhenID`, `drugAdviceID` FROM `drug_prescription` WHERE `id` = '$requestedID'");
	
	$row = mysql_fetch_assoc($result);
	
	$drugPrescribeID = insertPrescriptionDrugs($appointmentID, $row['drugTypeID'], $row['drugID'], $row['drugTimeID'], $row['drugDoseUnit'], $row['drugWhenID'], $row['drugAdviceID']);
	
	$dose = mysql_query("SELECT `drugPrescribeID`, `dose`, `numOfDay`, `durationType` FROM `dose_period` WHERE `drugPrescribeID` = $requestedID");
	
	while ($test=mysql_fetch_array($dose)){
		
		$drugDose = $test['dose'];
		$drugNoDay = $test['numOfDay'];
		$drugNoDayType = $test['durationType'];
		
		if($drugNoDay == ''){
			mysql_query("INSERT INTO `dose_period`(`drugPrescribeID`, `dose`, `numOfDay`, `durationType`) VALUES ($drugPrescribeID, '$drugDose', NULL, $drugNoDayType)");
		}else{
			mysql_query("INSERT INTO `dose_period`(`drugPrescribeID`, `dose`, `numOfDay`, `durationType`) VALUES ($drugPrescribeID, '$drugDose', $drugNoDay, $drugNoDayType)");
		}
		
	}
	
	
}elseif ($query_no == 8){
	
	$requestedID = $_POST['requestedID'];
	
	$result = mysql_query("SELECT `id`, `appointMentID`, `invID`, `note`, `checked` FROM `inv_prescription` WHERE `id` = $requestedID");
	
	$row = mysql_fetch_assoc($result);
	
	insertPrescriptionInv($appointmentID, $row['invID'], $row['note']);
	
}elseif ($query_no == 9){
	
	$requestedID = $_POST['requestedID'];
	
	$result = mysql_query("SELECT `id`, `appointMentID`, `adviceID` FROM `prescription_advice` WHERE `id` = $requestedID");
	
	$row = mysql_fetch_assoc($result);
	
	insertPrescriptionAdvice($appointmentID, $row['adviceID']);
	
}elseif ($query_no == 10){
	
	$requestedID = $_POST['requestedID'];
	
	$result = mysql_query("SELECT `id`, `appointMentID`, `symptomID`, `durationNum`, `durationType`, `detail` FROM `complain` WHERE `id` = $requestedID");
	
	$row = mysql_fetch_assoc($result);
	
	insertPrescribedCC($appointmentID, $row['symptomID'], $row['durationNum'], $row['durationType'],$row['detail']);
}



	
?>