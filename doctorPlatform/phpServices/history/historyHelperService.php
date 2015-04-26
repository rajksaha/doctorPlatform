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
$patientID = $_SESSION['patientID'];
$date=date("Y-m-d");
$query_no=  $_POST['query'];


if($query_no== 0){
	$typeCode = $_POST['typeCode'];
	$sql = "SELECT h.`id` AS historyID, h.`typeCode`, h.`name`, h.`shortName`, ph.historyResult, ph.historyID AS patientHistoryID, hp.id AS savedHistorysID, hp.appointMentID, dhs.displayOrder
			FROM `history` h
			JOIN doctor_history_settings dhs ON h.id = dhs.historyID
			LEFT JOIN patient_history ph ON h.id = ph.historyID AND ph.patientID = '$patientID'
			LEFT JOIN history_prescription hp ON ph.id = hp.patientHistoryID AND hp.appointMentID = '$appointmentID'
			WHERE 
			dhs.doctorID = '$doctorID' AND h.typeCode = '$typeCode'
			ORDER BY dhs.displayOrder";
	
	$result=mysql_query($sql);
	$data = array();
	while ($row=mysql_fetch_array($result)){
		array_push($data,$row);
	}
	
	echo json_encode($data); 
	
}else if($query_no==1){
	
	$historyID = $_POST['historyID'];
	$sql = "SELECT `id` AS historyOptionID, `historyID`, `optionName` FROM `history_option` WHERE `historyID` = '$historyID'";
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
	echo $sql;
	
}else if($query_no==3){
	
	$historyID = $_POST['historyID'];
	
	$sql=mysql_query("SELECT `id` FROM `patient_history` WHERE `patientID` = '$patientID' AND `historyID` = '$historyID'");
	$result = mysql_fetch_assoc($sql);
	$patientHistoryID = $result['id'];
	
	
	
	$sql = "INSERT INTO `history_prescription`( `appointMentID`, `patientHistoryID`) VALUES ('$appointmentID', '$patientHistoryID')";
	if(mysql_query($sql)){
		return true;
	}else{
		return false;
	}
}else if($query_no==4){
	
	$savedHistorysID = $_POST['savedHistorysID'];
	
	$sql = "DELETE FROM `history_prescription` WHERE `id` = '$savedHistorysID'";
	if(mysql_query($sql)){
		return true;
	}else{
		return false;
	}
}
else if($query_no==5){
	
	$name=$_POST['name'];
	$typeCode=$_POST['typeCode'];
	
	$sql ="SELECT h.`id` , h.`typeCode` , h.`name` , h.`shortName` , IFNULL(dhs.doctorID, 0) AS inDoctor
			FROM `history` h
			LEFT JOIN doctor_history_settings dhs ON h.id = dhs.historyID AND dhs.doctorID = '$doctorID' WHERE typeCode = '$typeCode' AND name LIKE '" . $name . "%' LIMIT 10";
	$result=mysql_query($sql);
	//echo $sql;
	$data = array();
	while ($row=mysql_fetch_array($result)){
		if ($row['inDoctor'] == 0){
			array_push($data,$row);
		}
		
	}
	echo json_encode($data);
	
}else if($query_no==6){
	$historyName = $_POST['historyName'];
	$shortName = $_POST['shortName'];
	$typeCode = $_POST['typeCode'];
	
	mysql_query("INSERT INTO `history`( `typeCode`, `name`, `shortName`) VALUES ('$typeCode','$historyName','$shortName')");
	
	echo mysql_insert_id();
	
}elseif ($query_no == 7){
	
	$historyID = $_POST['historyID'];
	$displayOrder = $_POST['displayOrder'];
	$sql = mysql_query("INSERT INTO `doctor_history_settings`( `doctorID`, `historyID`, `displayOrder`) VALUES ('$doctorID','$historyID','$displayOrder')");
	
}elseif ($query_no == 8){
	
	$drugPrescribeID = $_POST['drugPrescribeID'];
	
	mysql_query("DELETE FROM `drug_prescription` WHERE id='$drugPrescribeID'");
	
}elseif ($query_no == 9){
	
	$savedHistorysID = $_POST['savedHistorysID'];
	
	mysql_query("DELETE FROM `patient_history` WHERE id='$savedHistorysID'");
	
}elseif ($query_no == 10){
	
	$historyID = $_POST['historyID'];
	$historyResult = $_POST['historyResult'];
	
	mysql_query("INSERT INTO `patient_history`(`patientID`, `historyID`, `historyResult`) VALUES ('$patientID','$historyID','$historyResult')");
	
}elseif ($query_no == 11){
	
	$historyID = $_POST['historyID'];
	$historyResult = $_POST['historyResult'];
	
	mysql_query("UPDATE `patient_history` SET `historyResult`= '$historyResult' WHERE `patientID` = '$patientID' AND `historyID`= '$historyID'");
}
	
?>