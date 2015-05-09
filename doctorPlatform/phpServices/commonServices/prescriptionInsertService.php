<?php
/* include('../config.inc'); */

if (!isset($_SESSION['username'])) {
	header('Location: index.php');
}

function insertPrescriptionDrugs($appointmentID, $drugType, $drugID, $drugTime, $drugDose, $doseUnit, $drugNoOfDay, $drugDayType, $drugWhen, $drugAdvice){
	
	
	$sql = "INSERT 
				INTO `drug_prescription`
					(
						`appointMentID`,
						 `drugTypeID`,
						 `drugID`, 
						`drugTimeID`, 
						`drugDose`, 
						`drugDoseUnit`, 
						`drugNoOfDay`, 
						`drugDayTypeID`, 
						`drugWhenID`, 
						`drugAdviceID`
					)
	 			VALUES 
					(
						'$appointmentID',
						'$drugType',
						'$drugID',
						'$drugTime',
						'$drugDose',
						'$doseUnit',
						'$drugNoOfDay',
						'$drugDayType',
						'$drugWhen',
						'$drugAdvice'
					)";
	
	$result=mysql_query($sql);
	
	return $result;
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

function insertPrescribedCC($appointmentID ,$symptomID, $durationNum, $durationType){

	$sql = "INSERT INTO `complain`(`appointMentID`, `symptomID`, `durationNum`, `durationType`) VALUES ('$appointmentID','$symptomID','$durationNum','$durationType')";

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

function addToDoctorSetting($appointmentID ,$doctorID){
	
	$result = getPrescribedDiagnosis($appointmentID);
	
	$rec = mysql_fetch_assoc($result);
	
	$diseaseID = $rec['diseasID'];
	
	
	insertDrugsToSetting($appointmentID, $doctorID, $diseaseID);
	
	insertInvToSetting($appointmentID, $doctorID, $diseaseID);
	
	insertAdviceToSetting($appointmentID, $doctorID, $diseaseID);
}

function insertDrugsToSetting ($appointmentID,$doctorID, $diseaseID, $result){
	
	
	
	while ($row = mysql_fetch_array($result)){
	
		
		$drugType = $row['drugTypeID'];
		$drugID = $row['drugID'];
		$drugTime = $row['drugTimeID'];
		$drugDose = $row['drugDose'];
		$doseUnit = $row['drugDoseUnit'];
		$drugNoOfDay = $row['drugNoOfDay'];
		$drugDayType = $row['drugDayTypeID'];
		$drugWhen = $row['drugWhenID'];
		$drugAdvice = $row['drugAdviceID'];
		
		mysql_query("INSERT INTO 
				`settings_drug`
				(
					 `doctorID`,
					 `diseaseID`,
					`drugTypeID`, 
					`drugID`, 
					`drugTimeID`, 
					`drugDose`, 
					`drugDoseUnit`, 
					`drugNoOfDay`, 
					`drugDayTypeID`, 
					` drugWhenID`, 
					`drugAdviceID`
				) VALUES (
				'$doctorID', '$diseaseID', '$drugType', '$drugID', '$drugTime', '$drugDose', '$doseUnit', '$drugNoOfDay', '$drugDayType', '$drugWhen', '$drugAdvice')");
	}
}

function insertInvToSetting ($appointmentID,$doctorID, $diseaseID, $result){


	while ($row = mysql_fetch_array($result)){


		$invID = $row['invID'];
		$note = $row['note'];

		mysql_query("INSERT INTO `settings_inv`(`doctorID`, `diseaseID`, `invID`, `note`, `checked`) VALUES ('$doctorID', '$diseaseID', '$invID', '$note', 0)");
	}
}

function insertAdviceToSetting ($appointmentID,$doctorID, $diseaseID, $result){


	while ($row = mysql_fetch_array($result)){


		$adviceID = $row['adviceID'];

		mysql_query("INSERT INTO `settings_advice`(`doctorID`, `diseaseID`, `adviceID`) VALUES ('$doctorID', '$diseaseID', '$adviceID')");
	}
}


?>