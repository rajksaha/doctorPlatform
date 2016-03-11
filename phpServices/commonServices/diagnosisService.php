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
	
		$requestedID = $row['id'];
		 
		$drugPrescribeID  = insertPrescriptionDrugs($appointmentID, $row['drugTypeID'], $row['drugID'], $row['drugTimeID'], $row['drugDoseUnit'], $row['drugWhenID'], $row['drugAdviceID']);
		
		$dose = mysql_query("SELECT `drugSettingID`, `dose`, `numOfDay`, `durationType` FROM `settings_dose_drug` WHERE `drugSettingID` = $requestedID");
		
		while ($test=mysql_fetch_array($dose)){
		
			$drugDose = $test['dose'];
			$drugNoDay = $test['numOfDay'];
			$drugNoDayType = $test['durationType'];
		
			if($drugNoDay == NULL || $drugNoDay == ''){
				mysql_query("INSERT INTO `dose_period`(`drugPrescribeID`, `dose`, `numOfDay`, `durationType`) VALUES ($drugPrescribeID, '$drugDose', NULL, $drugNoDayType)");
			}else{
				mysql_query("INSERT INTO `dose_period`(`drugPrescribeID`, `dose`, `numOfDay`, `durationType`) VALUES ($drugPrescribeID, '$drugDose', $drugNoDay, $drugNoDayType)");
			}
		}
	}	
}

function addInvToPrescription ($appointmentID, $result){

	
	while ($row = mysql_fetch_array($result)){
	
		insertPrescriptionInv($appointmentID, $row['invID'], $row['note']);
	}
}

function addAdviceToPrescription ($appointmentID, $result){

	
	while ($row = mysql_fetch_array($result)){
		
		insertPrescriptionAdvice($appointmentID, $row['adviceID']);
	}
	
}




?>