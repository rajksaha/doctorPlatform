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
			WHERE dp.appointMentID = '$appointmentID' ORDER BY dp.id" ;
	
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

	$sql = "SELECT c.`id`, c.`appointMentID`, c.`symptomID`, c.`durationNum`, c.`durationType` AS durationID, s.name AS symptomName, ddt.english AS durationType 
			FROM `complain` c
			JOIN symptom s ON c.symptomID = s.symptomID
			JOIN drugdaytype ddt ON c.durationType= ddt.id
			 WHERE c.`appointMentID` = '$appointmentID' ORDER BY c.id";

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

function getPastDisease($appointmentID, $patientID){

	$sql = "SELECT pas.`id`, ppd.`appointMentID`, ppd.`pastDiseaseID` , d.name AS diseaseName, pas.startDate, pas.endDate, pas.detail, ppd.id AS prescribedID, IF(ppd.id  IS NULL, false, true) AS addedToPres
			FROM patient_past_disease pas
			JOIN disease d ON pas.diseaseID = d.id
			LEFT JOIN `prescription_past_disease` ppd ON ppd.pastDiseaseID = pas.id AND ppd .appointMentID = '$appointmentID'
			WHERE pas.patientID = '$patientID'";

	$result=mysql_query($sql);

	return $result;

}

function getFamilyDisease($appointmentID, $patientID){

	$sql = "SELECT pfh.`id`, pfh.`patientID`, pfh.`diseaseID`, d.name AS diseaseName, pfh.`relation`, pfh.`present`, pfh.`type`, pfh.`detail`, r.name AS relationName, IF(pfd.id  IS NULL, false, true) AS addedToPres
				FROM `patient_family_history` pfh
				JOIN disease d ON pfh.diseaseID = d.id
				JOIN relation r ON r.id = pfh.relation
				LEFT JOIN prescription_family_disease pfd ON pfd.familyDiseaseID = pfh.id AND pfd.appointMentID = '$appointmentID'
				WHERE pfh.patientID =  '$patientID'";

	$result=mysql_query($sql);

	return $result;

}

function getPrescribedPastDisease($appointmentID){

	$sql = "SELECT pas.`id`, ppd.`appointMentID`, ppd.`pastDiseaseID` , d.name AS diseaseName, pas.startDate, pas.endDate, pas.detail, ppd.id AS prescribedID, IF(ppd.id  IS NULL, false, true) AS addedToPres
	FROM patient_past_disease pas
	JOIN disease d ON pas.diseaseID = d.id
	JOIN `prescription_past_disease` ppd ON ppd.pastDiseaseID = pas.id
	WHERE ppd.appointMentID = '$appointmentID'";

	$result=mysql_query($sql);

	return $result;

}

function getPrescribedFamilyDisease($appointmentID){

	$sql = "SELECT pfh.`id`, pfh.`patientID`, pfh.`diseaseID`, d.name AS diseaseName, pfh.`relation`, pfh.`present`, pfh.`type`, pfh.`detail`, r.name AS relationName, IF(pfd.id  IS NULL, false, true) AS addedToPres
	FROM `patient_family_history` pfh
	JOIN disease d ON pfh.diseaseID = d.id
	JOIN relation r ON r.id = pfh.relation
	JOIN prescription_family_disease pfd ON pfd.familyDiseaseID = pfh.id 
	WHERE pfd.appointMentID = '$appointmentID'";

	$result=mysql_query($sql);

	return $result;

}

function getPrescribedNextVisit($appointmentID){

	$sql = "SELECT nv.`appointmentID`, nv.`nextVisitType`, nv.`date`, nv.`numOfDay`, nv.`dayType`, ddt.pdf, ddt.english  
			FROM `next_visit` nv
			LEFT JOIN drugdaytype ddt ON nv.dayType = ddt.id
			WHERE `appointmentID` =  '$appointmentID'";

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
	$sql = "SELECT app.`appointmentID`, app.`doctorCode`, app.`patientCode`, app.`date`, app.`time`, app.`status`, app.`appointmentType`, app.`addedBy`, p.patientID, p.name, at.name AS appointmentTypeName, at.shortName AS appointmentTypeShortName
			FROM `appointment` app
			JOIN patient p ON app.patientCode = p.patientCode
			JOIN appointment_type at ON at.id = app.appointmentType
			WHERE p.patientID = '$patientID' AND app.doctorCode = '$doctorCode' AND app.appointmentID <> '$appointmentID'
			ORDER BY app.date DESC";
	
	$result=mysql_query($sql);
	
	return $result;
}

function getDoctorsDrugSettingByDisease($doctorCode, $diseaseID){
	
	
	$result = mysql_query("SELECT sd.`id` , sd.`doctorID` , sd.`diseaseID` , sd.`drugTypeID` , sd.`drugID` , sd.`drugTimeID` , sd.`drugDose` , sd.`drugDoseUnit` , sd.`drugNoOfDay` , sd.`drugDayTypeID` , sd.`drugWhenID` , sd.`drugAdviceID` , dt.initial AS typeInitial, d.drugName AS drugName, d.strength AS drugStrength, ddt.bangla AS dayTypeName, dwt.bangla AS whenTypeName, dat.bangla AS adviceTypeName
							FROM `settings_drug` sd
							JOIN drugtype dt ON sd.drugTypeID = dt.id
							JOIN drug d ON sd.drugID = d.drugID
							JOIN drugadvicetype dat ON sd.drugAdviceID = dat.id
							JOIN drugdaytype ddt ON sd.drugDayTypeID = ddt.id
							JOIN drugwhentype dwt ON sd.`drugWhenID` = dwt.id
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