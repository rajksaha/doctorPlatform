<?php


if (!isset($_SESSION['username'])) {
	header('Location: index.php');
}

function getPresCribedDrugs($appointmentID){
	
	
	$sql = "SELECT  
			dp.id, dp.appointMentID, dp.drugTypeID, dp.drugID, dp.drugTimeID, dp.drugDose, dp.drugDoseUnit, dp.drugNoOfDay, dp.drugDayTypeID, dp.drugWhenID, dp.drugAdviceID, dt.initial AS typeInitial, 
			d.drugName AS drugName, d.strength AS drugStrength, 
			ddt.bangla AS dayTypeName, ddt.pdf AS dayTypePdf, dwt.bangla AS whenTypeName, dwt.pdf AS whenTypePdf, dat.bangla AS adviceTypeName, dat.pdf AS adviceTypePdf
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

	$sql = "SELECT pa.`id`, pa.`appointMentID`, pa.`adviceID`, a.advice, a.lang, a.pdf 
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
function getPrescribedHistory($appointmentID, $typeCode){

	$sql = "SELECT hp.`id`,  hp.`appointMentID`,  hp.`patientHistoryID` , IF(h.shortName IS NULL or h.shortName = '', h.name,   h.name) AS historyName , ph.historyResult
			FROM `history_prescription` hp
			JOIN patient_history ph ON hp.patientHistoryID = ph.id
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

	$sql = "SELECT dia.`id`, dia.`appointMentID`, dia.`diseaseID`, `note` , d.name AS diseaseName
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

function getPatientOldPrecription($appointmentID, $patientID, $doctorCode){
	$sql = "SELECT app.`appointmentID`, app.`doctorCode`, app.`patientCode`, app.`date`, app.`time`, app.`status`, app.`appointmentType`, app.`addedBy`, p.patientID, p.name
			FROM `appointment` app
			JOIN patient p ON app.patientCode = p.patientCode
			WHERE p.patientID = '$patientID' AND app.doctorCode = '$doctorCode' AND app.appointmentID <> '$appointmentID'
			ORDER BY app.date DESC";
	
	$result=mysql_query($sql);
	
	return $result;
}

function getDoctorsDrugSettingByDisease($doctorCode, $diseaseID){
	
	
	$result = mysql_query("SELECT sd.`id`, sd.`doctorID`, sd.`diseaseID`, sd.`drugTypeID`, sd.`drugID`, sd.`drugTimeID`, sd.`drugDose`, sd.`drugDoseUnit`, sd.`drugNoOfDay`, sd.`drugDayTypeID`, ` drugWhenID`, `drugAdviceID`,
							dt.initial AS typeInitial, d.drugName AS drugName, d.strength AS drugStrength, ddt.bangla AS dayTypeName, dwt.bangla AS whenTypeName, dat.bangla AS adviceTypeName
			FROM `settings_drug` sd
			JOIN drugtype dt ON sd.drugTypeID = dt.id
			JOIN drug d ON sd.drugID = d.drugID
			JOIN drugdaytype ddt ON sd.drugDayTypeID = ddt.id
			JOIN drugwhentype dwt ON sd.` drugWhenID` = dwt.id
			JOIN drugadvicetype dat ON sd.drugAdviceID = dat.id
			JOIN doctor doc ON sd.doctorID = doc.doctorID
			WHERE  doc.doctorCode = '$doctorCode' AND sd.diseaseID = '$diseaseID'");
	
	return $result;
	
	
}

function getDoctorsInvSettingByDisease($doctorCode, $diseaseID){


	$result = mysql_query("SELECT si.`id`, si.`doctorID`, si.`diseaseID`, si.`invID`, si.`note`, i.name AS invName
						FROM `settings_inv` si
						JOIN doctor d ON si.doctorID = d.doctorID
						JOIN inv i ON si.invID = i.id
						WHERE  d.doctorCode = '$doctorCode' AND si.diseaseID = '$diseaseID'");

	return $result;


}

function getDoctorsAdviceSettingByDisease($doctorCode, $diseaseID){


	$result = mysql_query("SELECT sa.`id`, sa.`doctorID`, sa.`diseaseID`, sa.`adviceID`, a.advice
						FROM `settings_advice` sa
						JOIN doctor d ON sa.doctorID = d.doctorID
						JOIN advice a ON sa.`adviceID` = a.id	
						WHERE  d.doctorCode = '$doctorCode' AND sa.diseaseID = '$diseaseID'");

	return $result;


}
?>