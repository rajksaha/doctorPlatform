<?php

session_start();
include('../config.inc');
include('../commonServices/prescriptionService.php');
include('../commonServices/parentInsertService.php');

if (!isset($_SESSION['username'])) {
	header('Location: index.php');
}
$username = $_SESSION['username'];
$date=date("Y-m-d");
$query_no=  mysql_real_escape_string($_POST['query']);

if($query_no == 0){

	$diseaseID = $_POST['diseaseID'];
	
	$data = array();
	$result = getDoctorsDrugSettingByDisease($username, $diseaseID);
	
	while ($row=mysql_fetch_array($result)){
		array_push($data,$row);
	}
	
	echo json_encode($data);
}

if($query_no == 1){

	$diseaseID = $_POST['diseaseID'];

	$result = getDoctorsInvSettingByDisease($username, $diseaseID);
	
	$data = array();
	while ($row=mysql_fetch_array($result)){
		array_push($data,$row);
	}

	echo json_encode($data);
}

if($query_no == 2){

	$diseaseID = $_POST['diseaseID'];

	$result = getDoctorsAdviceSettingByDisease($username, $diseaseID);
	
	$data = array();
	while ($row=mysql_fetch_array($result)){
		array_push($data,$row);
	}

	echo json_encode($data);
}

else if($query_no == 3){

	$diseaseName = $_POST['diagnosisName'];
	
	$diseaseID = getDiseasIDByName($diseaseName);
	
	echo $diseaseID;
}

else if($query_no == 4){


	$queryString=$_POST['drugName'];
	$drugType = $_POST['drugType'];
	
	$sql ="SELECT d.`drugID`, d.`typeID`, d.`companyID`, d.`drugName`, d.`strength`
			FROM `drug` d 
			LEFT JOIN drug_prescription dp  ON d.drugID = dp.drugID
			WHERE d.`drugName` LIKE '" . $queryString . "%' AND d.typeID = '$drugType'
			LIMIT 10";
	$result=mysql_query($sql);
	$data ="<p id='searchresults'>";
		
		while($row=  mysql_fetch_array($result)){
			$name = $row['drugName'];
			$str = $row['strength'];
			$display = "$name - $str";
			$data.= '<a href="javascript:autocomplete(\''.$name.'\')">';
			
			$data.= '<span class="searchheading">'.$display.'</span>';
		}
		
		$data.= "</p>";
		echo $data;
}elseif ($query_no == 5){
	
	$drugPrescribeID = $_POST['drugPrescribeID'];
	$drugType = $_POST['drugType'];
	$drugName = $_POST['drugName'];
	$drugTime = $_POST['drugTime'];
	$drugDose = $_POST['drugDose'];
	$doseUnit = $_POST['doseUnit'];
	$drugNoOfDay = $_POST['drugNoOfDay'];
	$drugDayType = $_POST['drugDayType'];
	$drugWhen = $_POST['drugWhen'];
	$drugAdvice = $_POST['drugAdvice'];
	$diseaseID = $_POST['diseaseID'];
	
	$drugID = getDrugIDByName($drugName);
	
	insertSingleDrugsToSetting($doctorID, $diseaseID,$drugID, $drugType, $drugTime, $drugDose, $doseUnit, $drugNoOfDay, $drugDayType, $drugDayType, $drugWhen,$drugAdvice);
	
}


?>