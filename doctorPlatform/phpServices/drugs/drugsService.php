<?php

session_start();
include('../config.inc');
if (!isset($_SESSION['username'])) {
	header('Location: index.php');
}
$username = $_SESSION['username'];
$appointmentID = $_SESSION['appointmentID'];
$patientCode = $_SESSION['patientCode'];
$date=date("Y-m-d");
$query_no=  mysql_real_escape_string($_POST['query']);


if($query_no== 1){
	$sql = "SELECT `id`, `bangla`, `pdf`, `english` FROM `drugdaytype` WHERE 1 = 1";
	$result=mysql_query($sql);
	$data = array();
	while ($row=mysql_fetch_array($result)){
		array_push($data,$row);
	}
	
	echo json_encode($data);
	
}else if($query_no==0){
	$sql = "SELECT `id`, `name`, `initial`, `unit`, `unitInitial`, `optionalUnitInitial` FROM `drugtype` WHERE 1 = 1";
	$result=mysql_query($sql);
	
	$data = array();
	while ($row=mysql_fetch_array($result)){
		array_push($data,$row);
	}
	
	echo json_encode($data);
}else if($query_no==2){
	$sql = "SELECT `id`, `bangla`, `english`, `pdf` FROM `drugWhenType` WHERE 1 = 1";
	$result=mysql_query($sql);
	
	$data = array();
	while ($row=mysql_fetch_array($result)){
		array_push($data,$row);
	}
	
	echo json_encode($data);
}else if($query_no==3){
	$sql = "SELECT dat.id AS drugAdviceID, dat.bangla, dat.english, dat.pdf
			FROM `drugAdviceType` dat
			WHERE dat.doctorType =0
			UNION
			SELECT dat.id AS drugAdviceID, dat.bangla, dat.english, dat.pdf
			FROM `drugAdviceType` dat
			LEFT JOIN doctorsettings ds ON dat.doctorType = ds.category
			JOIN doctor d ON d.doctorID = ds.doctorID
			WHERE d.doctorCode = '$username'";
	$result=mysql_query($sql);
	
	$data = array();
	while ($row=mysql_fetch_array($result)){
		array_push($data,$row);
	}
	
	echo json_encode($data);
}else if($query_no==6){
	
	
	$drugType = $_POST['drugType'];
	$drugID = $_POST['drugID'];
	$drugTime = $_POST['drugTime'];
	$drugDose = $_POST['drugDose'];
	$doseUnit = $_POST['doseUnit'];
	$drugNoOfDay = $_POST['drugNoOfDay'];
	$drugDayType = $_POST['drugDayType'];
	$drugWhen = $_POST['drugWhen'];
	$drugAdvice = $_POST['drugAdvice'];
	
	$sql = "INSERT 
				INTO `drug_prescription`
					(
						`appointMentID`,
						 `drugTypeID`,
						 `drugID`, 
						`drugTimeID`, 
						`drugDose`, 
						`drugDoseUnit`, 
						`drugNoOfDay`, 
						`drugDayTypeID`, 
						`drugWhenID`, 
						`drugAdviceID`
					)
	 			VALUES 
					(
						'$appointmentID',
						'$drugType',
						'$drugID',
						'$drugTime',
						'$drugDose',
						'$doseUnit',
						'$drugNoOfDay',
						'$drugDayType',
						'$drugWhen',
						'$drugAdvice'
					)";
	
	if(mysql_query($sql)){
		echo true;
	}else{
		echo false;
	}
	
}
else if($query_no==4){
	
	$sql = "SELECT  
			dp.id, dp.appointMentID, dp.drugTypeID, dp.drugID, dp.drugTimeID, dp.drugDose, dp.drugDoseUnit, dp.drugNoOfDay, dp.drugDayTypeID, dp.drugWhenID, dp.drugAdviceID, dt.initial AS typeInitial, d.drugName AS drugName, d.strength AS drugStrength, ddt.bangla AS dayTypeName, dwt.bangla AS whenTypeName, dat.bangla AS adviceTypeName
			FROM drug_prescription dp 
				JOIN drugtype dt ON dp.drugTypeID = dt.id
				JOIN drug d ON dp.drugID = d.drugID
				JOIN drugdaytype ddt ON dp.drugDayTypeID = ddt.id
				JOIN drugwhentype dwt ON dp.drugWhenID = dwt.id
				JOIN drugadvicetype dat ON dp.drugAdviceID = dat.id
			WHERE dp.appointMentID = '$appointmentID'";
	
	$result=mysql_query($sql);
	
	$data = array();
	while ($row=mysql_fetch_array($result)){
		array_push($data,$row);
	}
	
	echo json_encode($data);
            
	
}else if($query_no==5){
	
	$drugPrescribeID = $_POST['drugPrescribeID'];
	$drugType = $_POST['drugType'];
	$drugID = $_POST['drugID'];
	$drugTime = $_POST['drugTime'];
	$drugDose = $_POST['drugDose'];
	$doseUnit = $_POST['doseUnit'];
	$drugNoOfDay = $_POST['drugNoOfDay'];
	$drugDayType = $_POST['drugDayType'];
	$drugWhen = $_POST['drugWhen'];
	$drugAdvice = $_POST['drugAdvice'];
	
	$sql = "UPDATE `drug_prescription` SET  
				`drugTypeID`='$drugType',
				`drugID`='$drugID',
				`drugTimeID`='$drugTime',
				`drugDose`= '$drugDose',
				`drugDoseUnit`='$doseUnit',
				`drugNoOfDay`='$drugNoOfDay',
				`drugDayTypeID`='$drugDayType',
				`drugWhenID`='$drugWhen',
				`drugAdviceID`='$drugAdvice' 
			WHERE `id` = '$drugPrescribeID'";
	
	if(mysql_query($sql)){
		echo true;
	}else{
		echo $sql;
	}
}elseif ($query_no == 7){
	$drugPrescribeID = $_POST['drugPrescribeID'];
	
	mysql_query("DELETE FROM `drug_prescription` WHERE id='$drugPrescribeID'");
}
	
?>