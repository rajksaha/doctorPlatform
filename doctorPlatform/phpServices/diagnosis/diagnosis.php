<?php

session_start();
include('../config.inc');
include('../commonServices/prescriptionService.php');
include('../commonServices/prescriptionInsertService.php');
include('../commonServices/parentInsertService.php');

if (!isset($_SESSION['username'])) {
	header('Location: index.php');
}
$username = $_SESSION['username'];
$appointmentID = $_SESSION['appointmentID'];
$patientCode = $_SESSION['patientCode'];

$query_no=  $_POST['query'];

if($query_no== 0){
	
	
		$queryString = $_POST['queryString'];
			
		$res=  mysql_query("select * from disease WHERE name LIKE '" . $queryString . "%'  ORDER BY name LIMIT 10");
		$data ="<p id='searchresults'>";
		
		while($row1=  mysql_fetch_array($res)){
			$name=$row1['name'];
			
			$data.= '<a href="javascript:autocomplete(\''.$name.'\')">';
			
			$data.= '<span class="searchheading">'.$name.'</span>';
		}
		
		$data.= "</p>";
		echo $data;
	
}

else if($query_no== 1){


	$result = getPrescribedDiagnosis($appointmentID);
	
	
	
	echo json_encode($result);
		

}
elseif ($query_no == 2){
	
	$diagnosisName = $_POST['diagnosisName'];
	
	$diseaseID = getDiseasIDByName($diseaseName); 
	
	$note = $_POST['note'];
	
	insertPrescribedDiagnosis($appointmentID, $diseaseID, $note);
	
	addToPrescription($diseaseID, $doctorCode, $appointmentID);
	
	
}

elseif ($query_no == 3){

	$diagnosisName = $_POST['diagnosisName'];
	
	$note = $_POST['note'];
	
	$id = $_POST['id'];

	$diseaseID = getDiseasIDByName($diagnosisName);
	
	mysql_query("UPDATE `diagnosis` SET `diseaseID`='$diseaseID' ,`note`= '$note' WHERE id= '$id'");
}
		
?>

