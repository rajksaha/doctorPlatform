<?php

session_start();
include('../config.inc');
include('../commonServices/prescriptionService.php');
include('../commonServices/prescriptionInsertService.php');
include('../commonServices/parentInsertService.php');
if (!isset($_SESSION['username'])) {
	header('Location: index.php');
}
$username=$_SESSION['username'];
$date=date("Y-m-d");
$appointmentID = $_SESSION['appointmentID'];
//$query_no=  mysql_real_escape_string($_POST['query']);

$json = file_get_contents('php://input');
$dataObject = json_decode($json);

$query_no = $dataObject->query;

if($query_no==1){
	
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
	
}else if($query_no==2){// insert in complain
	
	$symptomName=$dataObject->complainName;
	
	$symptomID = getSymptomIDByName($symptomName);
	$durationNum=$dataObject->numOfDay;
	$durationType=$dataObject->dayType;
	$complainPrescribeID=$dataObject->complainPrescribeID;
	$detail = "";
	
	if($complainPrescribeID != 0){
		mysql_query("UPDATE `complain` SET `symptomID`= '$symptomID',`durationNum`= '$durationNum',`durationType`='$durationType',`detail`='$detail' WHERE `id` = '$complainPrescribeID'");
	}else{
		insertPrescribedCC($appointmentID, $symptomID, $durationNum, $durationType , $detail);
	}
	
	echo $durationNum;
	
	
	
}else if($query_no==3){// get all added CC 
	
	$result = getPrescribedComplain($appointmentID);
	
	$data = array();
	while ($row=mysql_fetch_array($result)){
		array_push($data,$row);
	}
	echo json_encode($data);
	
}elseif ($query_no == 4){
	
	$id=$dataObject->id;
	
	mysql_query("DELETE FROM `complain` WHERE `id` = '$id'");
}

?>