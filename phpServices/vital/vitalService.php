<?php

session_start();
include('../config.inc');
include('../commonServices/prescriptionInsertService.php');
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
	$sql = "SELECT v.`vitalId` , v.`vitalName` , v.`shortName` , v.`vitalUnit` , vp.vitalResult, vp.id AS prescribedVitalID, dvs.displayOrder, dvs.id AS vitalSettingID
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
	
	insertPrescribedVital($appointmentID, $vitalID, $vitalResult);
	
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
	
	$queryString =  $_POST['name'];
	
	$result = mysql_query("SELECT v.`vitalId` , v.`vitalName` , v.`shortName` , v.`vitalUnit`, IFNULL(dvs.doctorID, 0) AS inDoctor
								FROM `vital` v
								LEFT JOIN doctor_vital_settings dvs ON v.vitalId = dvs.vitalID AND dvs.doctorID = '$doctorID'
								WHERE  v.`vitalName` LIKE '" . $queryString . "%' AND  IFNULL(dvs.doctorID, 0) = 0");
	
	$data = array();
	while ($row=mysql_fetch_array($result)){
		array_push($data,$row);
	}
	
	echo json_encode($data);
	
}else if($query_no==6){
	
	$vitalName = $_POST['vitalName'];
	$shortName = $_POST['shortName'];
	$unit = $_POST['unit'];
	
	$sql =  mysql_query("INSERT INTO `vital`(`vitalName`, `shortName`, `vitalUnit`) VALUES ('$vitalName','$shortName','$unit')");
	
	echo mysql_insert_id();
	
}elseif ($query_no == 7){
	$vitalID = $_POST['vitalID'];
	$displayOrder = $_POST['displayOrder'];
	
	mysql_query("INSERT INTO `doctor_vital_settings`( `doctorID`, `vitalID`, `displayOrder`) VALUES ('$doctorID','$vitalID','$displayOrder')");
	 echo  true;
}elseif ($query_no == 9){
	
	$prescribedVitalID = $_POST['prescribedVitalID'];
	$sql = mysql_query("DELETE FROM `vital_prescription` WHERE `id` = '$prescribedVitalID'");
}elseif ($query_no == 8){
	
	$vitalSettingID = $_POST['vitalSettingID'];
	$sql = mysql_query("DELETE FROM `doctor_vital_settings` WHERE `id` = '$vitalSettingID'");
	
}
	
?>