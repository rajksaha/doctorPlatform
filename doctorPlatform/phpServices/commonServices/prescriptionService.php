<?php
include('../config.inc');

if (!isset($_SESSION['username'])) {
	header('Location: index.php');
}

function getPresCribedDrugs($appointmentID){
	
	
	$sql = "SELECT  
			dp.id, dp.appointMentID, dp.drugTypeID, dp.drugID, dp.drugTimeID, dp.drugDose, dp.drugDoseUnit, dp.drugNoOfDay, dp.drugDayTypeID, dp.drugWhenID, dp.drugAdviceID, dt.initial AS typeInitial, d.drugName AS drugName, d.strength AS drugStrength, ddt.bangla AS dayTypeName, dwt.bangla AS whenTypeName, dat.bangla AS adviceTypeName
			FROM drug_prescription dp 
				JOIN drugtype dt ON dp.drugTypeID = dt.id
				JOIN drug d ON dp.drugID = d.drugID
				JOIN drugdaytype ddt ON dp.drugDayTypeID = ddt.id
				JOIN drugwhentype dwt ON dp.drugWhenID = dwt.id
				JOIN drugadvicetype dat ON dp.drugAdviceID = dat.id
			WHERE dp.appointMentID = '$appointmentID'";
	
	$result=mysql_query($sql);
	
	return $result;
}

function getPrescribedInv($appointmentID){
	
	$sql = "SELECT ip.`id`, ip.`appointMentID`, ip.`invID`, ip.`note`, ip.`checked`, i.name AS invName
	FROM `inv_prescription` ip
	JOIN inv i ON ip.invID = i.id
	WHERE `appointMentID` = '$appointmentID'";
	
	$result=mysql_query($sql);
	
	return $result;
	
}

function getPrescribedAdvice($appointmentID){

	$sql = "SELECT pa.`id`, pa.`appointMentID`, pa.`adviceID` ,a.advice
			FROM `prescription_advice` pa
			JOIN advice a ON pa.adviceID = a.id
			WHERE `appointMentID` = '$appointmentID'";

	$result=mysql_query($sql);

	return $result;

}

function getPrescribedVital($appointmentID){

	$sql = "SELECT vp.`id`, vp.`appointMentID`, vp.`vitalID`, vp.`vitalResult` , IF(v.shortName IS NULL or v.shortName = '', v.vitalName,   v.vitalName) AS vitalDisplayName, v.vitalUnit
			FROM `vital_prescription` vp 
			JOIN vital v ON vp.vitalID = v.vitalId
 			WHERE `appointMentID`= '$appointmentID'";

	$result=mysql_query($sql);
	
	return $result;

}
function getPrescribedHistory($appointmentID, $typeCode, $patientID){

	$sql = "SELECT hp.`id`,  hp.`appointMentID`,  hp.`patientHistoryID` , IF(h.shortName IS NULL or h.shortName = '', h.name,   h.name) AS historyName , ph.historyResult
			FROM `history_prescription` hp
			JOIN patient_history ph ON hp.patientHistoryID = ph.id AND ph.patientID = '$patientID'
			JOIN history h ON ph.historyID = h.id AND  h.typeCode = '$typeCode'
			WHERE hp.`appointMentID`= '$appointmentID'";

	$result=mysql_query($sql);

	return $result;

}

function getPrescribedComplain($appointmentID){

	$sql = "SELECT c.`id`, c.`appointMentID`, c.`symptomID`, c.`durationNum`, c.`durationType` , s.name AS symptomName, ddt.bangla AS durationType
			FROM `complain` c
			JOIN symptom s ON c.symptomID = s.symptomID
			JOIN drugdaytype ddt ON c.durationType= ddt.id
			 WHERE c.`appointMentID` = '$appointmentID'";

	$result=mysql_query($sql);

	return $result;

}
function getPrescribedDiagnosis($appointmentID){

	$sql = "SELECT `id`, `appointMentID`, `diseaseID`, `note` , d.name AS diseaseName
			FROM `diagnosis` dia
			JOIN disease d ON dia.diseaseID = d.id
			WHERE dia.`appointMentID`= '$appointmentID'";

	$result=mysql_query($sql);

	return $result;

}

function getPrescribedPastDisease($appointmentID, $patientID){

	$sql = "SELECT ppd.`id`, ppd.`appointMentID`, ppd.`pastDiseaseID` , d.name AS diseaseName, pas.startDate, pas.endDate, pas.detail
			FROM `prescription_past_disease` ppd
			JOIN patient_past_disease pas ON ppd.pastDiseaseID = pas.id AND pas.patientID = '$patientID'
			JOIN disease d ON pas.diseaseID = d.id
			WHERE ppd.`appointMentID` = '$appointmentID'";

	$result=mysql_query($sql);

	return $result;

}

function getPrescribedFamilyDisease($appointmentID, $patientID){

	$sql = "SELECT pfd.`id`, pfd.`appointMentID`, pfd.`familyDiseaseID` , pfh.relation, pfh.present, pfh.type, pfh.detail
			FROM `prescription_family_disease` pfd
			JOIN patient_family_history pfh ON pfd.familyDiseaseID = pfh.id AND pfh.patientID = '$patientID'
			JOIN disease d ON pfh.diseaseID = d.id
			WHERE `appointMentID` =  '$appointmentID'";

	$result=mysql_query($sql);

	return $result;

}

function getPrescribedNextVisit($appointmentID){

	$sql = "SELECT `appointmentID`, `date` FROM `next_visit` WHERE `appointmentID` =  '$appointmentID'";

	$result=mysql_query($sql);

	return $result;

}

function getPrescribedReffredDoctor($appointmentID){

	$sql = "SELECT pr.`id`, pr.`appointMentID`, pr.`refferedDoctorID` , rd.doctorName , rd.doctorAdress
			FROM `prescription_reference` pr 
			JOIN reffered_doctor rd ON pr.refferedDoctorID = rd.id
			WHERE `appointMentID` =  '$appointmentID'";

	$result=mysql_query($sql);

	return $result;

}
?>