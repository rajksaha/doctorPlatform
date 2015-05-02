<?php

session_start();
include('../config.inc');
include('../commonServices/prescriptionService.php');
include('../commonServices/prescriptionInsertService.php');
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
	
	$queryString=$dataObject->data;
	$sql ="SELECT `symptomID`, `name` FROM `symptom` WHERE name LIKE '" . $queryString . "%' LIMIT 10";
	$result=mysql_query($sql);
	//echo $sql;
	$data = array();
	while ($row=mysql_fetch_array($result)){
		array_push($data,$row);
	}
	echo json_encode($data);
	
}else if($query_no==2){// insert in complain
	
	$symptomID=$dataObject->symptomID;
	$durationNum=$dataObject->durationNum;
	$durationType=$dataObject->durationType;
	
	insertPrescribedCC($appointmentID, $symptomID, $durationNum, $durationType);
	
	
}else if($query_no==3){// get all added CC 
	
	$result = getPrescribedComplain($appointmentID);
	
	$data = array();
	while ($row=mysql_fetch_array($result)){
		array_push($data,$row);
	}
	echo json_encode($data);
}

?>