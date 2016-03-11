<?php
/* include('../config.inc'); */

if (!isset($_SESSION['username'])) {
	header('Location: index.php');
}

function insertPrescriptionDrugs($appointmentID, $drugType, $drugID, $drugTime, $doseUnit, $drugWhen, $drugAdvice){
	
	
	$sql = "INSERT 
				INTO `drug_prescription`
					(
						`appointMentID`,
						 `drugTypeID`,
						 `drugID`, 
						`drugTimeID`, 
						`drugDoseUnit`, 
						`drugWhenID`, 
						`drugAdviceID`
					)
	 			VALUES 
					(
						'$appointmentID',
						'$drugType',
						'$drugID',
						'$drugTime',
						'$doseUnit',
						'$drugWhen',
						'$drugAdvice'
					)";
	
	mysql_query($sql);
	
	return mysql_insert_id();
}

function insertPrescriptionInv($appointmentID, $invID, $note){
	
	$sql = "INSERT INTO `inv_prescription`(`appointMentID`, `invID`, `note`, `checked`) VALUES ('$appointmentID','$invID','$note',0)";

	mysql_query($sql);

	
	return mysql_insert_id();
	
}

function insertPrescriptionAdvice($appointmentID, $adviceID){

	$sql = "INSERT INTO `prescription_advice`( `appointMentID`, `adviceID`) VALUES ('$appointmentID','$adviceID')";

	mysql_query($sql);

	return mysql_insert_id();

}

function insertPrescribedCC($appointmentID ,$symptomID, $durationNum, $durationType, $detail){

	$sql = "INSERT INTO `complain`(`appointMentID`, `symptomID`, `durationNum`, `durationType`, `detail`) VALUES ('$appointmentID','$symptomID','$durationNum','$durationType', '$detail')";

	mysql_query($sql);
	
	return mysql_insert_id();

}

function insertPrescribedVital($appointmentID,$vitalID, $vitalResult ){
	
	$sql = "INSERT INTO `vital_prescription`( `appointMentID`, `vitalID`, `vitalResult`) VALUES ('$appointmentID','$vitalID','$vitalResult')";
	
	mysql_query($sql);
	
	return mysql_insert_id();
}

function insertPrescribedHistory($appointmentID, $patientHistoryID ){

	$sql = "INSERT INTO `history_prescription`( `appointMentID`, `patientHistoryID`) VALUES ('$appointmentID', '$patientHistoryID')";

	mysql_query($sql);

	return mysql_insert_id();
}

function insertPrescribedDiagnosis($appointmentID, $diseaseID, $note){
	$sql = mysql_query("INSERT INTO `diagnosis`( `appointMentID`, `diseaseID`, `note`) VALUES ('$appointmentID', '$diseaseID','$note')");
}

function addToDoctorSetting($appointmentID ,$doctorID, $diseaseID){
	
	$drugResult = getPresCribedDrugs($appointmentID);
	
	while ($row = mysql_fetch_array($drugResult)){
	
	
		$drugType = $row['drugTypeID'];
		$drugID = $row['drugID'];
		$drugTime = $row['drugTimeID'];
		$doseUnit = $row['drugDoseUnit'];
		$drugWhen = $row['drugWhenID'];
		$drugAdvice = $row['drugAdviceID'];
		$requestedID = $row['id'];
		
		$drugSetID = insertSingleDrugsToSetting($doctorID, $diseaseID, $drugID, $drugType, $drugTime, $doseUnit, $drugWhen, $drugAdvice, $requestedID);
		
		$dose123 = mysql_query("SELECT `drugPrescribeID`, `dose`, `numOfDay`, `durationType` FROM `dose_period` WHERE `drugPrescribeID` = $requestedID");
		
		while ($test=mysql_fetch_array($dose123)){
		
			$drugDose = $test['dose'];
			$drugNoDay = $test['numOfDay'];
			$drugNoDayType = $test['durationType'];
		
			if($drugNoDay == NULL || $drugNoDay == ''){
				mysql_query("INSERT INTO `settings_dose_drug`(`drugSettingID`, `dose`, `numOfDay`, `durationType`) VALUES ($drugSetID, '$drugDose', NULL, $drugNoDayType)");
			}else{
				mysql_query("INSERT INTO `settings_dose_drug`(`drugSettingID`, `dose`, `numOfDay`, `durationType`) VALUES ($drugSetID, '$drugDose', $drugNoDay, $drugNoDayType)");
			}
		}
	}
	
	
	
	
	$invResult = getPrescribedInv($appointmentID);
	
	
	while ($row = mysql_fetch_array($invResult)){
	
	
		$invID = $row['invID'];
		$note = $row['note'];
		
		insertSingleInvToSetting($doctorID, $diseaseID, $invID, $note);
	
	}
	
	
	
	$adviceResult = $result = getPrescribedAdvice($appointmentID);
	
	while ($row = mysql_fetch_array($adviceResult)){
	
	
		$adviceID = $row['adviceID'];
	
		insertSingleAdviceToSetting($doctorID, $diseaseID, $adviceID);
	}
	
	
	
}


function insertSingleDrugsToSetting($doctorID, $diseaseID, $drugID, $drugType, $drugTime, $doseUnit, $drugWhen, $drugAdvice){
	
	mysql_query("INSERT INTO `settings_drug`(`doctorID`, `diseaseID`, `drugTypeID`, `drugID`, `drugTimeID`, `drugDoseUnit`, `drugWhenID`, `drugAdviceID`) 
			VALUES
			('$doctorID', '$diseaseID', '$drugType', '$drugID', '$drugTime', '$doseUnit', '$drugWhen', '$drugAdvice')");
	
	
	
	return mysql_insert_id();
	
}


function insertSingleInvToSetting ($doctorID, $diseaseID, $invID, $note){


	mysql_query("INSERT INTO `settings_inv`(`doctorID`, `diseaseID`, `invID`, `note`, `checked`) VALUES ('$doctorID', '$diseaseID', '$invID', '$note', 0)");
}

function insertSingleAdviceToSetting ($doctorID, $diseaseID, $adviceID){


	mysql_query("INSERT INTO `settings_advice`(`doctorID`, `diseaseID`, `adviceID`) VALUES ('$doctorID', '$diseaseID', '$adviceID')");
	
	return mysql_insert_id();
}

function insertFamilyHistory($patientID, $diseaseID, $relation, $present, $type, $detail){
	
	mysql_query("INSERT INTO `patient_family_history`(`patientID`, `diseaseID`, `relation`, `present`, `type`, `detail`) 
				VALUES ('$patientID','$diseaseID','$relation','$present','$type','$detail')");
	
	return mysql_insert_id();
	
}

function insertPastHistory($patientID, $diseaseID, $isPresent, $detail){

	mysql_query("INSERT INTO `patient_past_disease`(`patientID`, `diseaseID`, `isPresent`, `detail`) VALUES ('$patientID','$diseaseID', $isPresent ,'$detail')");
	
	return mysql_insert_id();

}

function insertContentDetail($entityID, $entityType, $detail, $code){
	
	mysql_query("INSERT INTO `contentdetail`(`contentType`, `entityID`, `detail`, `code`) VALUES ('$entityType', $entityID,'$detail','$code')");
	
	return mysql_insert_id();
}


?>