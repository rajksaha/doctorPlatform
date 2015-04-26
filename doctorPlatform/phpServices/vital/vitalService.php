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
$query_no=  mysql_real_escape_String($_POST['query']);


if($query_no== 0){
	$sql = "SELECT v.`vitalId` , v.`vitalName` , v.`shortName` , v.`vitalUnit` , vp.vitalResult, vp.id AS prescribedVitalID
			FROM `vital` v
			JOIN `doctor_vital_settings` dvs ON v.vitalID = dvs.vitalID
			LEFT JOIN vital_prescription vp ON v.vitalID = vp.vitalID AND vp.appointMentID ='$appointmentID'
			WHERE dvs.doctorID ='$doctorID'
			ORDER BY dvs.displayOrder";
	$result=mysql_query($sql);
	$data = array();
	while ($row=mysql_fetch_array($result)){
		array_push($data,$row);
	}
	
	echo json_encode($data); 
	
}else if($query_no==1){
	$vitalID = $_POST['vitalID'];
	$sql = "SELECT `id`, `vitalId`, `name` FROM `vital_option` WHERE `vitalId` = '$vitalID'";
	$result=mysql_query($sql);
	
	$data = array();
	while ($row=mysql_fetch_array($result)){
		array_push($data,$row);
	}
	
	echo json_encode($data);
}else if($query_no==2){
	$vitalID = $_POST['vitalID'];
	$vitalOptionName = $_POST['vitalOptionName'];
	$vitalOptionNameList = explode(",",$vitalOptionName);
	for ($i = 0; $i< sizeof($vitalOptionNameList);$i++){
		$sql = "INSERT INTO `vital_option`( `vitalId`, `name`) VALUES ('$vitalID','$vitalOptionNameList[$i]')";
		mysql_query($sql);
	}
}else if($query_no==3){
	$vitalID = $_POST['vitalID'];
	$vitalResult = $_POST['vitalResult'];
	$sql = "INSERT INTO `vital_prescription`( `appointMentID`, `vitalID`, `vitalResult`) VALUES ('$appointmentID','$vitalID','$vitalResult')";
	if(mysql_query($sql)){
		return true;
	}else{
		return false;
	}
}else if($query_no==4){
	
	$vitalID = $_POST['vitalID'];
	$vitalResult = $_POST['vitalResult'];
	$sql = "UPDATE `vital_prescription` SET `vitalResult`='$vitalResult' WHERE `appointMentID` = '$appointmentID' AND `vitalID` = '$vitalID'";
	if(mysql_query($sql)){
		return true;
	}else{
		return false;
	}
}
else if($query_no==5){
	
}else if($query_no==6){
	
	
}elseif ($query_no == 7){
	$drugPrescribeID = $_POST['drugPrescribeID'];
	
	mysql_query("DELETE FROM `drug_prescription` WHERE id='$drugPrescribeID'");
}
	
?>