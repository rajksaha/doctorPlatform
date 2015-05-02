<?php
include('../config.inc');

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


?>