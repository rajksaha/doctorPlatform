<?php

session_start();
include('../config.inc');
include('../commonServices/appointmentService.php');
include('../commonServices/prescriptionService.php');
include('../commonServices/prescriptionInsertService.php');
if (!isset($_SESSION['username'])) {
	header('Location: index.php');
}
$username = $_SESSION['username'];
$appointmentNO = $_SESSION['appointmentID'];
$patientCode = $_SESSION['patientCode'];
$date=date("Y-m-d");
$query_no=  $_POST['query'];


if($query_no== 0){
	
	$result = getPatientInformaition($patientCode);
	
	$rec=mysql_fetch_assoc($result);
	
	$patineCode = $rec['patientCode'];
	$patineCode = substr($patineCode,4);
	$rec['patientCode'] = $patineCode;
	
	echo json_encode($rec);
	
}else if($query_no==1){
	$sql = "SELECT 
				ms.menuHeader, ms.order, m.menuURL, ds.category
			FROM `menusettings` ms
			JOIN doctor doc ON ms.doctorID = doc.doctorID
			JOIN  doctorsettings ds ON ds.doctorID = doc.doctorID
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
	
	$result  = getAppointmentInfo($appointmentNO);
	
	$rec=mysql_fetch_assoc($result);
	
	
	
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
	
	$numOfDay = $_POST['numOfDay'];
	$dayType = $_POST['dayType'];
	$nextVisitType = $_POST['nextVisitType'];
	
	$sql = mysql_query("UPDATE `next_visit` SET `date`= '$nextVisitDate', `nextVisitType`='$nextVisitType',`numOfDay`='$numOfDay',`dayType`='$dayType' WHERE appointmentID = '$appointmentNO'");
	
	echo "UPDATE `next_visit` SET `date`= '$nextVisitDate', `nextVisitType`='$nextVisitType',`numOfDay`='$numOfDay',`dayType`='$dayType' WHERE appointmentID = '$appointmentNO'";
	
	
}elseif ($query_no == 8){
	
	mysql_query("DELETE FROM `next_visit` WHERE `appointmentID` = $appointmentNO");
	
	$nextVisitDate = mysql_real_escape_string($_POST['nextVisitDate']);
	
	$numOfDay = $_POST['numOfDay'];
	$dayType = $_POST['dayType'];
	$nextVisitType = $_POST['nextVisitType'];
	
	$sql = mysql_query("INSERT INTO `next_visit`(`appointmentID`, `nextVisitType`, `date`, `numOfDay`, `dayType`) VALUES ('$appointmentNO','$nextVisitType', '$nextVisitDate', '$numOfDay','$dayType')");
	
	echo "INSERT INTO `next_visit`(`appointmentID`, `nextVisitType`, `date`, `numOfDay`, `dayType`) VALUES ('$appointmentNO','$nextVisitType', 'DATE('$nextVisitDate')', '$numOfDay','$dayType')";
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
	
	
}elseif ($query_no == 13){
	
	$doctorID  =$_POST['doctorID'];
	$diseaseID  =$_POST['diseaseID'];
	
	$added = 0;
	$drugResult = mysql_query("SELECT sa.doctorID FROM `settings_advice` sa WHERE sa.diseaseID = '$diseaseID' AND sa.doctorID ='$doctorID'");	
	
	if(mysql_num_rows($drugResult) > 0){
		$added = 1;
	}else{
		$adviceResult = mysql_query("SELECT sa.doctorID FROM `settings_advice` sa WHERE sa.diseaseID = '$diseaseID' AND sa.doctorID ='$doctorID'");
		
		if (mysql_num_rows($adviceResult) > 0){
			$added = 1;
		}else{
			$invResult = mysql_query("SELECT sa.doctorID FROM `settings_inv` sa WHERE sa.diseaseID = '$diseaseID' AND sa.doctorID ='$doctorID'");
			
			if (mysql_num_rows($invResult) > 0){
				$added = 1;
			}else{
				$added = -1;
			}
		}
	}
	
	echo $added;
}

elseif ($query_no == 14){

	$doctorID  =$_POST['doctorID'];
	$diseaseID  =$_POST['diseaseID'];
	addToDoctorSetting($appointmentNO, $doctorID, $diseaseID);
}

elseif ($query_no == 15){

	
	mysql_query("UPDATE `appointment` SET `status`= 1 WHERE `appointmentID` = '$appointmentNO'");
	
	$_SESSION['printAppointmentID']=$appointmentNO;
	$_SESSION['printPatientCode']=$patientCode;
	
	$_SESSION['patientID']= "";
	$_SESSION['appointmentID']= "";
	$_SESSION['patientCode']= "";
	
	
}elseif ($query_no == 16){

	$name = $_POST['name'];
	$address = $_POST['address'];
	$age = $_POST['age'];
	$sex= $_POST['sex'];
	$phone = $_POST['phone'];
	$id = $_POST['id'];
	
	mysql_query("UPDATE `patient` SET `name`='$name',`age`='$age',`sex`='$sex',`address`='$address',`phone`='$phone' WHERE `patientID` = '$id'");
	
}elseif ($query_no == 17){

	$comment = $_POST['comment'];
	
	$rec = mysql_query("SELECT `contentDetailID`, `contentType`, `entityID`, `detail`, `code` FROM `contentdetail` WHERE `contentType`='COMMENT' AND `entityID` = $appointmentNO");
	$data = mysql_fetch_assoc($rec);
	if($data){
		if($comment == ''){
			mysql_query("DELETE FROM `contentdetail` WHERE `contentType` = 'COMMENT' AND `entityID` = $appointmentNO");
		}else{
			mysql_query("UPDATE `contentdetail` SET `detail`= '$comment' WHERE `contentType` = 'COMMENT' AND `entityID` = $appointmentNO");
		}
		
	}else{
		insertContentDetail($appointmentNO, "COMMENT", $comment, "");		
	}
	
	echo $data;
	
}

elseif ($query_no == 18){


	$appointmentID = $_POST['appointmentID'];
	$patientCode = $_POST['patientCode'];
	
	$_SESSION['printAppointmentID']=$appointmentID;
	$_SESSION['printPatientCode']=$patientCode;



}
?>