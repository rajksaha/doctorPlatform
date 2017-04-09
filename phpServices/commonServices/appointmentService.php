<?php


if (!isset($_SESSION['username'])) {
	header('Location: index.php');
}



function getAppointment($username, $date){
	
	
	$sql = "SELECT
	app.appointmentID, app.doctorCode, app.patientCode, app.date, app.time, app.status, app.addedBy, p.patientID, p.name, p.age, p.address, p.phone, p.sex, IFNULL(p.name, 0) AS patientState, 
		app.appointmentType, at.shortName AS appointmentTypeName
	FROM `appointment` app
	JOIN appointment_type at ON at.id= app.appointmentType
	LEFT JOIN patient p ON app.patientCode = p.patientCode
	WHERE app.doctorCode = '$username' AND app.date='$date' order by app.appointmentID ASC";
	
	$result=mysql_query($sql);
	
	$data = array();
	while ($row=mysql_fetch_array($result)){
		array_push($data,$row);
	}
	return  json_encode($data);
}

function addAppointMent($doctorCode, $patientCode, $appointmentType, $doctorID, $date, $time, $username){


	$sql =  mysql_query("INSERT INTO `appointment`( `doctorCode`, `patientCode`, `date`, `time`, `status`,`appointmentType`, `addedBy`) 
			VALUES 
			('$doctorCode','$patientCode','$date','$time',0,  '$appointmentType', '$username')");

	
	
	
}
function getAppointmentInfo($appointmentID){
	
	$sql = "SELECT a.`appointmentID`, a.`doctorCode`, a.`patientCode`, a.`date`, a.`time`, a.`status`, a.`appointmentType`, a.`addedBy`, p.patientID
	FROM `appointment` a
	JOIN patient p ON a.patientCode = p.patientCode
	WHERE appointmentID = '$appointmentID'";
	
	$result = mysql_query($sql);
	
	return $result;
}

function getPatientInformaition($patientCode){
	
	$sql = "SELECT p.`patientID` , p.`patientCode` , p.`name` , p.`age` , p.`sex` , p.`address` ,p.`phone`,  pd.`type`, pd.`tri`, pd.`triStatus`, 
	pd.`edb`, pd.id AS patientDetailID, cd.detail as patientImage
	FROM `patient` p
	LEFT JOIN contentdetail cd ON cd.contentType = 'PATIENTIMG'  AND cd.entityID = '$patientCode'
	LEFT join patient_detail pd ON p.`patientID` = pd.`patientID`
	WHERE `patientCode` = '$patientCode'"  ;
	
	$result=mysql_query($sql);
	
	return $result;
}

function getDoctorInfo ($username){
	
	$sql=mysql_query("SELECT
			d.doctorID, d.doctorCode, d.password, d.name, d.sex, d.age, d.phone, ds.category, ds.state, ds.prescriptionStyle,
			ds.patientType, ds.patientState, ds.hospitalID, ds.photoSupport, ds.personCodeInitial, dc.name AS categoreyName, ds.pdfPage
			FROM doctor d
			JOIN doctorsettings ds ON d.doctorID = ds.doctorID
			JOIN doctorcategory dc ON ds.category = dc.id
			WHERE d.doctorCode ='$username'");
	$result=mysql_fetch_assoc($sql);
	
	return $result;
	
}

function getPdfDetail($patientCode, $username){
	
	$sql = "SELECT p.`patientID` , p.`patientCode` , p.`name` , p.`age` , p.`sex` , p.`address` , p.`phone` , pd.`type` , pd.`tri` , pd.`triStatus` , pd.`edb` , pd.id AS patientDetailID, COUNT( a.appointmentID ) AS visitNo
FROM `patient` p
JOIN appointment a ON a.patientCode = p.patientCode AND a.doctorCode = '$username'
LEFT JOIN patient_detail pd ON p.`patientID` = pd.`patientID`
WHERE p.patientCode = '$patientCode'";
	
	$result=mysql_query($sql);
	
	return $result;
}

function addFollowUpSetting($doctorID, $patientID){
	
	
	$sql = "SELECT dfs.`followUpSerttingID`, dfs.`doctorID`, dfs.`invID`, i.name AS invName
			FROM `doctor_followup_setteing` dfs
			JOIN inv i ON i.id = dfs.invID
			WHERE dfs.doctorID = '$doctorID'";
	
	$result=mysql_query($sql);
	
	while ($row=mysql_fetch_array($result)){
		$invID = $row['invID'];
		$innerSql = "INSERT INTO `patient_follow_up`(`patientID`, `doctorID`, `invID`) VALUES ($patientID,$doctorID, $invID)";
		mysql_query($innerSql);
	}
	
}
?>