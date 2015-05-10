<?php
include('../config.inc');
if (!isset($_SESSION['username'])) {
	header('Location: index.php');
}

function addToPrescriptionSetings($diseaseID, $doctorCode, $appointmentID){
	
	$drugResult = getDoctorsDrugSettingByDisease($doctorCode, $diseaseID);
	
	addDrugsToPrescription($appointmentID, $drugResult);
	
	
	$invResult = getDoctorsInvSettingByDisease($doctorCode, $diseaseID);

	addInvToPrescription($appointmentID, $invResult);
	
	
	$adviceResult = getDoctorsAdviceSettingByDisease($doctorCode, $diseaseID); 
	
	addAdviceToPrescription($appointmentID, $adviceResult);
	
}

function addDrugsToPrescription ($appointmentID, $result){
	
	
	
	
	while ($row = mysql_fetch_array($result)){
	
		insertPrescriptionDrugs($appointmentID, $row['drugTypeID'], $row['drugID'], $row['drugTimeID'], $row['drugDose'], $row['drugDoseUnit'], $row['drugNoOfDay'], $row['drugDayTypeID'], $row['drugWhenID'], $row['drugAdviceID']);
	}
}

function addInvToPrescription ($appointmentID, $result){

	
	while ($row = mysql_fetch_array($result)){
	
		insertPrescriptionInv($appointmentID, $row['invID'], $row['note']);
	}
}

function addAdviceToPrescription ($diseaseID, $result){

	
	while ($row = mysql_fetch_array($result)){
		
		insertPrescriptionAdvice($appointmentID, $row['adviceID']);
	}
	
}




?>