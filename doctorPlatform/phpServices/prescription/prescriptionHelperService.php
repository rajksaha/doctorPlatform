<?php

session_start();
include('../config.inc');
if (!isset($_SESSION['username'])) {
	header('Location: index.php');
}
$username = $_SESSION['username'];
$appointmentNO = $_SESSION['appointmentID'];
$patientCode = $_SESSION['patientCode'];
$date=date("Y-m-d");
$query_no=  $_POST['query'];


if($query_no== 0){
	$sql = "SELECT p.`patientID` , p.`patientCode` , p.`name` , p.`age` , p.`sex` , p.`address` ,p.`phone`,  pd.`type`, pd.`tri`, pd.`triStatus`, pd.`edb`, pd.id AS patientDetailID
			FROM `patient` p
			LEFT join patient_detail pd ON p.`patientID` = pd.`patientID`
			WHERE `patientCode` = '$patientCode'";
	$result=mysql_query($sql);
	$rec=mysql_fetch_array($result);
	
	echo json_encode($rec);
	
}else if($query_no==1){
	$sql = "SELECT 
				ms.menuHeader, ms.order, m.menuURL
			FROM `menusettings` ms
			JOIN doctor doc ON ms.doctorID = doc.doctorID
			JOIN menu m ON ms.menuID = m.menuID
			WHERE doc.doctorCode = '$username'
			ORDER BY ms.order ASC";
	$result=mysql_query($sql);
	
	$data = array();
	while ($row=mysql_fetch_array($result)){
		array_push($data,$row);
	}
	
	echo json_encode($data);
}

if($query_no==2){
	
	$doctorType = $_POST['doctorType'];
	$sql = "SELECT `id`, `doctorType`, `typeName` FROM `patient_type` WHERE doctorType = '$doctorType'";
	$result=mysql_query($sql);
	
	$data = array();
	while ($row=mysql_fetch_array($result)){
		array_push($data,$row);
	}
	
	echo json_encode($data);
	
	
}
else if($query_no==3){
	$patientType = $_POST['patientType'];
	$patientDetailID = $_POST['patientDetailID'];
	$patientID = $_POST['patientID'];
	
	if($patientDetailID != 'null'){
		$sql = "UPDATE `patient_detail` SET `type`='$patientType' WHERE patientID = '$patientID'";
	}else{
		$sql = "INSERT INTO `patient_detail`(`patientID`, `type`, `tri`, `triStatus`, `edb`) VALUES ('$patientID','$patientType','','','')";
	}
	
	mysql_query($sql);
}
else if($query_no==4){
	
	$sql = "SELECT a.`appointmentID`, a.`doctorCode`, a.`patientCode`, a.`date`, a.`time`, a.`status`, a.`appointmentType`, a.`addedBy`, p.patientID 
			FROM `appointment` a
			JOIN patient p ON a.patientCode = p.patientCode
			WHERE appointmentID = '$appointmentNO'";
	$result = mysql_query($sql);
	$rec=mysql_fetch_array($result);
	echo json_encode($rec);
	
}else if($query_no==5){
	
	$sql = "SELECT `id`, `name`, `shortName` FROM `appointment_type` WHERE 1 = 1";
	$result=mysql_query($sql);
	
	$data = array();
	while ($row=mysql_fetch_array($result)){
		array_push($data,$row);
	}
	
	echo json_encode($data);
}elseif ($query_no==6){
	$patientState = $_POST['patientState'];
	$sql = mysql_query("UPDATE `appointment` SET `appointmentType`= '$patientState'  WHERE  `appointmentID` = '$appointmentNO'");
	
}elseif ($query_no == 7){
	
	$nextVisitDate = mysql_real_escape_string($_POST['nextVisitDate']);
	
	$sql = mysql_query("UPDATE `next_visit` SET `date`= '$nextVisitDate' WHERE appointmentID = '$appointmentNO'");
	
	
}elseif ($query_no == 8){
	
	$nextVisitDate = mysql_real_escape_string($_POST['nextVisitDate']);
	
	$sql = mysql_query("INSERT INTO `next_visit`(`appointmentID`, `date`) VALUES ('$appointmentNO',DATE('$nextVisitDate'))");
}
elseif ($query_no == 9){

	$queryString=$_POST['refDocName'];
	
	$sql ="SELECT `id`, `doctorName`, `doctorAdress` FROM `reffered_doctor` WHERE doctorName LIKE '" . $queryString . "%' LIMIT 10";
	$result=mysql_query($sql);
	//echo $sql;
	 $data = array();
	while ($row=mysql_fetch_array($result)){
		array_push($data,$row);
	}
	echo json_encode($data); 
}elseif ($query_no == 10){
	
	$refDocName=$_POST['refDocName'];
	$refDocAdress=$_POST['refDocAdress'];
	
	$sql = "INSERT INTO `reffered_doctor`( `doctorName`, `doctorAdress`) VALUES ('$refDocName','$refDocAdress')";
	mysql_query($sql);
	echo mysql_insert_id();
}
elseif ($query_no == 11){
	
	$refDocID=$_POST['refDocID'];
	
	$sql = "INSERT INTO `prescription_reference`( `appointMentID`, `refferedDoctorID`) VALUES ('$appointmentNO','$refDocID')";
	
	mysql_query($sql);
}elseif ($query_no == 12){
	
	$sql = mysql_query("DELETE FROM `prescription_reference` WHERE `appointMentID` = '$appointmentNO'");
}
	
?>