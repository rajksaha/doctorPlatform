<?php

session_start();
include('../config.inc');
include('../commonServices/prescriptionService.php');
if (!isset($_SESSION['username'])) {
	header('Location: index.php');
}
$username = $_SESSION['username'];
$doctorID = $_SESSION['doctorID'];
$appointmentID = $_SESSION['appointmentID'];
$patientCode = $_SESSION['patientCode'];
$patientID = $_SESSION['patientID'];
$date=date("Y-m-d");
$query_no=  $_POST['query'];


if($query_no== 0){
	
	$queryString = $_POST['adviceName'];
	$lang = $_POST['lang'];
	$type = $_POST['type'];
	
	$sql = "SELECT a.`id` , a.`type` , a.`lang` , a.`advice` , a.`pdf` , das.id AS adviceSettingID, das.displayOrder
			FROM `advice` a
			LEFT JOIN doctor_advice_settings das ON a.id = das.adviceID
			AND das.doctorID = '$doctorID'
			AND IFNULL( das.id, 0 ) =0
			WHERE a.lang = '$lang' AND a.type = '$type' AND  a.`advice` LIKE '" . $queryString . "%'
			
			UNION 

			SELECT a.`id` , a.`type` , a.`lang` , a.`advice` , a.`pdf` , das.id AS adviceSettingID, das.displayOrder
			FROM `advice` a
			LEFT JOIN doctor_advice_settings das ON a.id = das.adviceID
			AND das.doctorID = '$doctorID'
			AND IFNULL( das.id, 0 ) =0
			WHERE a.lang = '$lang' AND a.type = 0 AND  a.`advice` LIKE '" . $queryString . "%' LIMIT 10";

	$result=mysql_query($sql);
	$data = array();
	while ($row=mysql_fetch_array($result)){
		array_push($data,$row);
	}

	echo json_encode($data);
}else if ($query_no == 1){
	
	$sql = "SELECT a.`id` , a.`type` , a.`lang` , a.`advice` , a.`pdf` , das.id AS adviceSettingID, das.displayOrder, pa.id AS prescribedAdviceID
			FROM `advice` a
			JOIN doctor_advice_settings das ON a.id = das.adviceID 
			LEFT JOIN prescription_advice pa ON das.adviceID = pa.adviceID AND pa.appointMentID = '$appointmentID'
			WHERE das.doctorID = '$doctorID'";
	
	$result=mysql_query($sql);
	$data = array();
	while ($row=mysql_fetch_array($result)){
		array_push($data,$row);
	}
	
	echo json_encode($data);
	
	
}else if ($query_no == 2){
	
	$advice = $_POST['adviceName'];
	$type = $_POST['type'];
	
	$sql = "INSERT INTO `advice`(`type`, `lang`, `advice`, `pdf`) VALUES ('$type',1,'$advice','')";
	
	mysql_query($sql);
	
	echo mysql_insert_id();
	
}else if ($query_no == 3){
	
	$displayOrder = $_POST['displayOrder'];
	$adviceID = $_POST['adviceID'];
	$sql = "INSERT INTO `doctor_advice_settings`(`doctorID`, `adviceID`, `displayOrder`) VALUES ('$doctorID','$adviceID','$displayOrder')";
	
	mysql_query($sql);
	
	
}else if ($query_no == 4){

	$adviceID = $_POST['adviceID'];

	$sql = "INSERT INTO `prescription_advice`( `appointMentID`, `adviceID`) VALUES ('$appointmentID','$adviceID')";

	mysql_query($sql);

	echo mysql_insert_id();

}else if ($query_no == 5){

	$adviceID = $_POST['adviceID'];

	$sql = "DELETE FROM `prescription_advice` WHERE `appointMentID` = '$appointmentID' AND `adviceID` = '$adviceID'";

	mysql_query($sql);

}else if ($query_no == 6){

	$adviceSettingID = $_POST['adviceSettingID'];

	$sql = "DELETE FROM `doctor_advice_settings` WHERE `id` = '$adviceSettingID'";

	mysql_query($sql);
	
	echo $sql;

}


?>