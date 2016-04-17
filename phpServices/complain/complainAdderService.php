<?php

session_start();
include('../config.inc');
include('../commonServices/prescriptionService.php');
include('../commonServices/prescriptionInsertService.php');
include('../commonServices/parentInsertService.php');
//include('../JSON.php');
if (!isset($_SESSION['username'])) {
	header('Location: index.php');
}
$username=$_SESSION['username'];
$date=date("Y-m-d");
$appointmentID = $_SESSION['appointmentID'];


$someJSON = $_POST['json'];


$someObject = json_decode($someJSON);

for ($i= 0;$i <count($someObject);$i++){
	
	$symptomName = $someObject[$i]->complainName;
	$symptomID = getSymptomIDByName($symptomName);
	$durationNum=$someObject[$i]->numOfDay;
	$durationType=$someObject[$i]->dayType;
	$complainPrescribeID=$someObject[$i]->complainPrescribeID;
	$detail = "";
	
	//echo $complainPrescribeID;
	if($complainPrescribeID != 0){
		//echo "";
		mysql_query("UPDATE `complain` SET `symptomID`= '$symptomID',`durationNum`= '$durationNum',`durationType`='$durationType',`detail`='$detail' WHERE `id` = '$complainPrescribeID'");
	}else{
		//echo insertPrescribedCC($appointmentID, $symptomID, $durationNum, $durationType , $detail);
		insertPrescribedCC($appointmentID, $symptomID, $durationNum, $durationType , $detail);
	}
	
}

//echo "done";






?>