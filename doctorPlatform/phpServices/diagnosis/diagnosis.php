<?php

session_start();
include('../config.inc');
include('../commonServices/prescriptionService.php');
include('../commonServices/prescriptionInsertService.php');
include('../commonServices/parentInsertService.php');
include('../commonServices/diagnosisService.php');
include('../commonServices/autoCopmpleteService.php');

if (!isset($_SESSION['username'])) {
	header('Location: index.php');
}
$username = $_SESSION['username'];

if (isset($_SESSION['appointmentID'])) {
	$appointmentID = $_SESSION['appointmentID'];
}

if (isset($_SESSION['patientCode'])) {
	$patientCode = $_SESSION['patientCode'];
}

$query_no=  $_POST['query'];

if($query_no== 0){
	
	
		$queryString = $_POST['queryString'];
			
		return getDiseaseList($queryString);
	
}

else if($query_no== 1){


	$result = getPrescribedDiagnosis($appointmentID);
	
	$rec = mysql_fetch_assoc($result);
	
	echo json_encode($rec);
		

}
elseif ($query_no == 2){
	
	$diseaseName = $_POST['diagnosisName'];
	
	$diseaseID = getDiseasIDByName($diseaseName); 
	
	$note = $_POST['note'];
	
	insertPrescribedDiagnosis($appointmentID, $diseaseID, $note);
	
	addToPrescriptionSetings($diseaseID, $username, $appointmentID);
	
	
}

elseif ($query_no == 3){

	$diseaseName = $_POST['diagnosisName'];
	
	$note = $_POST['note'];
	
	$id = $_POST['id'];

	$diseaseID = getDiseasIDByName($diseaseName);
	
	mysql_query("UPDATE `diagnosis` SET `diseaseID`='$diseaseID' ,`note`= '$note' WHERE id= '$id'");
}
		
?>

