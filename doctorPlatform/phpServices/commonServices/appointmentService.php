<?php


if (!isset($_SESSION['username'])) {
	header('Location: index.php');
}



function getAppointment($username, $date){
	
	
	$sql = "SELECT
	app.appointmentID, app.doctorCode, app.patientCode, app.date, app.time, app.status, app.addedBy, p.patientID, p.name, p.age, p.address, p.phone, p.sex, IFNULL(p.name, 0) AS patientState, app.appointmentType
	FROM `appointment` app
	LEFT JOIN patient p ON app.patientCode = p.patientCode
	WHERE app.doctorCode = '$username' AND app.date='$date' order by app.time";
	
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

	
	mysql_query("UPDATE `doctorsettings` SET `personCodeInitial`=  personCodeInitial + 1 WHERE doctorID = $doctorID");
	
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
	
	$sql = "SELECT p.`patientID` , p.`patientCode` , p.`name` , p.`age` , p.`sex` , p.`address` ,p.`phone`,  pd.`type`, pd.`tri`, pd.`triStatus`, pd.`edb`, pd.id AS patientDetailID
	FROM `patient` p
	LEFT join patient_detail pd ON p.`patientID` = pd.`patientID`
	WHERE `patientCode` = '$patientCode'";
	
	$result=mysql_query($sql);
	
	return $result;
}
?>