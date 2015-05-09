<?php
include('../config.inc');
include('../commonServices/prescriptionService.php');
include('../commonServices/prescriptionInsertService.php');
if (!isset($_SESSION['username'])) {
	header('Location: index.php');
}

function addToPrescription($diseaseID, $doctorCode, $appointmentID){
	
	$drugResult = getDoctorsDrugSettingByDisease($doctorCode, $diseaseID);
	
	addDrugsToPrescription($appointmentID, $drugResult);
	
	
	$invResult = getDoctorsInvSettingByDisease($doctorCode, $diseaseID);

	addInvToPrescription($diseaseID, $doctorCode, $appointmentID);
	
	
	$adviceResult = getDoctorsAdviceSettingByDisease($doctorCode, $diseaseID); 
	
	addAdviceToPrescription($diseaseID, $doctorCode, $appointmentID);
	
}

function addDrugsToPrescription ($appointmentID, $result){
	
	
	
	
	while ($row = mysql_fetch_array($result)){
	
		insertPrescriptionDrugs($appointmentID, $row['drugTypeID'], $row['drugID'], $row['drugTimeID'], $row['drugDose'], $row['drugDoseUnit'], $row['drugNoOfDay'], $row['drugDayTypeID'], $row['drugWhenID'], $row['drugAdviceID']);
	}
}

function addInvToPrescription ($diseaseID, $doctorCode, $appointmentID){

	$sql = mysql_query("SELECT si.`id`, si.`doctorID`, si.`diseaseID`, si.`invID`, si.`note` 
						FROM `settings_inv` si
						JOIN doctor d ON si.doctorID = d.doctorID
						WHERE  d.doctorCode = '$doctorCode' AND si.diseaseID = '$diseaseID'");
	
	while ($row = mysql_fetch_array($sql)){
	
		insertPrescriptionInv($appointmentID, $row['invID'], $row['note']);
	}
}

function addAdviceToPrescription ($diseaseID, $doctorCode, $appointmentID){

	$sql = mysql_query("SELECT sa.`id`, sa.`doctorID`, sa.`diseaseID`, sa.`adviceID` 
						FROM `settings_advice` sa
						JOIN doctor d ON sa.doctorID = d.doctorID
						WHERE  d.doctorCode = '$doctorCode' AND sa.diseaseID = '$diseaseID'");
	
	while ($row = mysql_fetch_array($sql)){
		
		insertPrescriptionAdvice($appointmentID, $row['adviceID']);
	}
	
}

function addToDoctorSetting($appointmentID ,$doctorID){
	
	$result = getPrescribedDiagnosis($appointmentID);
	
	$rec = mysql_fetch_assoc($result);
	
	$diseaseID = $rec['diseasID'];
	
	
	$drugResult = $result = getPresCribedDrugs($appointmentID);
	
	insertDrugsToSetting($appointmentID, $doctorID, $diseaseID, $drugResult);
	
	
	
	$invResult = $result = getPrescribedInv($appointmentID);
	
	insertInvToSetting($appointmentID, $doctorID, $diseaseID, $invResult);
	
	
	
	$adviceResult = $result = getPrescribedAdvice($appointmentID);
	
	insertAdviceToSetting($appointmentID, $doctorID, $diseaseID, $adviceResult);
}


?>