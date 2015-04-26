<?php

session_start();
include('../config.inc');
if (!isset($_SESSION['username'])) {
	header('Location: index.php');
}
$username = $_SESSION['username'];
$doctorID = $_SESSION['doctorID'];
$appointmentID = $_SESSION['appointmentID'];
$patientCode = $_SESSION['patientCode'];
$date=date("Y-m-d");
$query_no=  $_POST['query'];


if($query_no== 0){
	$typeCode = $_POST['typeCode'];
	$sql = "SELECT h.`id` AS historyID, h.`typeCode`, h.`name`, h.`shortName`, ph.historyResult, ph.patientID AS patientHistoryID, hp.id AS prescriptionID, hp.appointMentID
			FROM `history` h
			JOIN doctor_history_settings dhs ON h.id = dhs.historyID
			LEFT JOIN patient_history ph ON h.id = ph.historyID
			LEFT JOIN history_prescription hp ON ph.id = hp.patientHistoryID AND hp.appointMentID = '$appointmentID'
			WHERE 
			dhs.doctorID = '$doctorID' AND h.typeCode = '$typeCode'
			ORDER BY dvs.displayOrder";
	$result=mysql_query($sql);
	$data = array();
	while ($row=mysql_fetch_array($result)){
		array_push($data,$row);
	}
	
	echo json_encode($data); 
	
}else if($query_no==1){
	$historyID = $_POST['historyID'];
	$sql = "SELECT `id`, `historyID`, `optionName` FROM `history_option` WHERE `historyID` = '$historyID'";
	$result=mysql_query($sql);
	
	$data = array();
	while ($row=mysql_fetch_array($result)){
		array_push($data,$row);
	}
	
	echo json_encode($data);
}else if($query_no==2){
	
	
	$historyID = $_POST['historyID'];
	$historyOptionName = $_POST['historyOptionName'];
	$historyOptionNameList = explode(",",$historyOptionName);
	for ($i = 0; $i< sizeof($historyOptionNameList);$i++){
		$sql = "INSERT INTO `history_option`( `historyID`, `optionName`) VALUES ('$historyID','$historyOptionNameList[$i]')";
		mysql_query($sql);
	}
	
	
}else if($query_no==3){
	
	$historyID = $_POST['historyID'];
	$patientHistoryID = $_POST['patientHistoryID'];
	$sql = "INSERT INTO `history_prescription`( `appointMentID`, `patientHistoryID`) VALUES ('$appointmentID', 'patientHistoryID')";
	if(mysql_query($sql)){
		return true;
	}else{
		return false;
	}
}else if($query_no==4){
	
	$vitalID = $_POST['vitalID'];
	$vitalResult = $_POST['vitalResult'];
	$sql = "UPDATE `history_prescription` SET `vitalResult`='$vitalResult' WHERE `appointMentID` = '$appointmentID' AND `vitalID` = '$vitalID'";
	if(mysql_query($sql)){
		return true;
	}else{
		return false;
	}
}
else if($query_no==5){
	
	echo "githubTest";
	
}else if($query_no==6){
	
	
}elseif ($query_no == 7){
	$drugPrescribeID = $_POST['drugPrescribeID'];
	
	mysql_query("DELETE FROM `drug_prescription` WHERE id='$drugPrescribeID'");
}
	
?>