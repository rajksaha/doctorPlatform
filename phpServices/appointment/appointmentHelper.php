<?php

session_start();
include('../config.inc');
include('../commonServices/appointmentService.php');

if (!isset($_SESSION['username'])) {
	header('Location: index.php');
}

$username=$_SESSION['username'];
$date=date("Y-m-d");
$query_no=  $_POST['query'];
$time_now=mktime(date('h')+6,date('i'),date('s'));
$time=date('h:i:s',$time_now);
$date=date("y-m-d");

if($query_no== 0){
	
	$sql=mysql_query("SELECT 
						d.doctorID, d.doctorCode, d.password, d.name, d.sex, d.age, d.phone, ds.category, ds.state, ds.prescriptionStyle, 
						ds.patientType, ds.patientState, ds.hospitalID, ds.photoSupport, ds.personCodeInitial, dc.name AS categoreyName, ds.pdfPage, ds.photoSupport
					FROM doctor d
					JOIN doctorsettings ds ON d.doctorID = ds.doctorID
					JOIN doctorcategory dc ON ds.category = dc.id
					WHERE d.doctorCode ='$username'");
	$rec=mysql_fetch_array($sql);
	echo json_encode($rec);
}

if($query_no==1){
	
	$filteredDate = $_POST['filteredDate'];
	
	echo getAppointment($username, $filteredDate);
}

if($query_no==2){
	
	$patientCode = mysql_real_escape_string($_POST['dotorPatInitial']);
	$doctorCode=$_POST['doctorCode'];
	$doctorID=$_POST['doctorID'];
	$name = $_POST['name'];
	$address = $_POST['address'];
	$age = $_POST['age'];
	$sex= $_POST['sex'];
	$phone = $_POST['phone'];
    $occupation = $_POST['occupation'];
    $referredBy = $_POST['referredBy'];
	
	$appointmentType =  0;
	
	$sql="INSERT INTO `patient`( `patientCode`, `name`, `age`, `sex`, `address`, `phone`, `occupation`, `referredBy`) VALUES ( '$patientCode', '$name', '$age' , '$sex', '$address', '$phone', '$occupation', '$referredBy')";
	
	mysql_query($sql);
	
	addFollowUpSetting($doctorID, mysql_insert_id());
	
	$data = addAppointMent($doctorCode, $patientCode, $appointmentType,$doctorID, $date, $time, $username);
	
	mysql_query("UPDATE `doctorsettings` SET `personCodeInitial`=  personCodeInitial + 1 WHERE doctorID = $doctorID");
	
	echo $data;
}
else if($query_no==3){
	
	
	$date = $_POST['filteredDate'];
	$doctorCode= $_POST['doctorCode'];
	$patientCode = $_POST['patientCode'];
	$doctorID=$_POST['doctorID'];
	
	$appointmentType =  1;
	
	$data = addAppointMent($doctorCode, $patientCode, $appointmentType,$doctorID, $date, $time, $username);
	
	
	
	echo $data;

	
}
else if($query_no==4){
            $_SESSION['appointmentID']=$_POST['appointmentID'];
            $_SESSION['patientCode']=$_POST['patientCode'];
            $_SESSION['patientID']=$_POST['patientID'];
	
}else if($query_no==5){
	$queryString=$_POST['data'];
	$sql ="SELECT `patientID`, `patientCode`, `name`, `age`, `sex`, `address`, `phone` FROM `patient` WHERE name LIKE '%" . $queryString . "%' LIMIT 10";
	$result=mysql_query($sql);
	//echo $sql;
	 $data = array();
	while ($row=mysql_fetch_array($result)){
		array_push($data,$row);
	}
	echo json_encode($data); 
}else if($query_no==6){
	session_destroy();
	
}else if($query_no==7){
	$queryString=$_POST['data'];
	$sql ="SELECT `patientID`, `patientCode`, `name`, `age`, `sex`, `address`, `phone` FROM `patient` WHERE patientCode LIKE '%" . $queryString . "%' LIMIT 10";
	$result=mysql_query($sql);
	//echo $sql;
	 $data = array();
	while ($row=mysql_fetch_array($result)){
		array_push($data,$row);
	}
	echo json_encode($data); 
	
	
}else if($query_no==8){
	$queryString=$_POST['data'];
	$sql ="SELECT `patientID`, `patientCode`, `name`, `age`, `sex`, `address`, `phone` FROM `patient` WHERE phone LIKE '%" . $queryString . "%' LIMIT 10";
	$result=mysql_query($sql);
	//echo $sql;
	 $data = array();
	while ($row=mysql_fetch_array($result)){
		array_push($data,$row);
	}
	echo json_encode($data); 
}else if($query_no==9){
	$appointmentID=$_POST['appointmentID'];
	mysql_query("DELETE FROM `appointment` WHERE `appointmentID` = $appointmentID");
}else if($query_no==10){
	$appointmentID=$_POST['appointmentID'];
	mysql_query("UPDATE `appointment` SET`status`= 2 WHERE `appointmentID` = $appointmentID");
}
?>
