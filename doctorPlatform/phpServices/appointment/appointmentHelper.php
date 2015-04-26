<?php

session_start();
include('../config.inc');
if (!isset($_SESSION['username'])) {
	header('Location: index.php');
}
$username=$_SESSION['username'];
$date=date("Y-m-d");
$query_no=  $_POST['query'];


if($query_no== 0){
	
	$sql=mysql_query("SELECT 
						d.doctorID, d.doctorCode, d.password, d.name, d.sex, d.age, d.phone, ds.category, ds.state, ds.prescriptionStyle, ds.patientType, ds.patientState, ds.hospitalID, ds.photoSupport, ds.personCodeInitial, dc.name AS categoreyName
					FROM doctor d
					JOIN doctorsettings ds ON d.doctorID = ds.doctorID
					JOIN doctorcategory dc ON ds.category = dc.id
					WHERE d.doctorCode ='$username'");
	$rec=mysql_fetch_array($sql);
	echo json_encode($rec);
}

if($query_no==1){
	$sql = "SELECT 
				app.appointmentID, app.doctorCode, app.patientCode, app.date, app.time, app.status, app.addedBy, p.patientID, p.name, p.age, p.address, p.phone, p.sex, IFNULL(p.name, 0) AS patientState
			FROM `appointment` app
			LEFT JOIN patient p ON app.patientCode = p.patientCode 
			WHERE app.doctorCode = '$username' AND app.date='$date' order by app.time";
	$result=mysql_query($sql);
	
	$data = array();
	while ($row=mysql_fetch_array($result)){
		array_push($data,$row);
	}
	
	echo json_encode($data);
}

if($query_no==2){
	
	$patientCode = mysql_real_escape_string($_POST['patientCode']);
	$name = $_POST['name'];
	$address = $_POST['address'];
	$age = $_POST['age'];
	$sex= $_POST['sex'];
	$phone = $_POST['phone'];
	
	$sql="INSERT INTO `patient`( `patientCode`, `name`, `age`, `sex`, `address`, `phone`) VALUES ( '$patientCode', '$name', '$age' , '$sex', '$address', '$phone')";
	if(mysql_query($sql)){
		echo $sql;
	}else{
		echo $sql;
	}
}
else if($query_no==3){
	$doctorCode=$_POST['doctorCode'];
	$patientCode = $_POST['patientCode'];
	$doctorID = $_POST['doctorID'];
	$time_now=mktime(date('h')+6,date('i'),date('s'));
	$time=date('h:i:s',$time_now);
	$date=date("y-m-d");
	$sql=  mysql_query("INSERT INTO `appointment`( `doctorCode`, `patientCode`, `date`, `time`, `status`, `addedBy`) VALUES ('$doctorCode','$patientCode','$date','$time',0,'$username')");
	mysql_query($sql);

	mysql_query("UPDATE `doctorsettings` SET `personCodeInitial`=  personCodeInitial + 1 WHERE doctorID = $doctorID");
}
else if($query_no==4){
            $_SESSION['appointmentID']=$_POST['appointmentID'];
            $_SESSION['patientCode']=$_POST['patientCode'];
            $_SESSION['patientID']=$_POST['patientID'];
	
}else if($query_no==5){
	$queryString=$_POST['data'];
	$sql ="SELECT `patientID`, `patientCode`, `name`, `age`, `sex`, `address`, `phone` FROM `patient` WHERE name LIKE '" . $queryString . "%' LIMIT 10";
	$result=mysql_query($sql);
	//echo $sql;
	 $data = array();
	while ($row=mysql_fetch_array($result)){
		array_push($data,$row);
	}
	echo json_encode($data); 
}
?>
