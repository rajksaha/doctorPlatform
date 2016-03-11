<?php

session_start();
include('../config.inc');
include('../commonServices/prescriptionService.php');
include('../commonServices/parentInsertService.php');
include('../commonServices/prescriptionInsertService.php');
include('../commonServices/autoCopmpleteService.php');

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
	
	return getdrugList($queryString, $drugType);
	
}elseif ($query_no == 5){
	
	//$drugPrescribeID = $_POST['drugPrescribeID'];
	$doctorID = $_POST['doctorID'];
	$drugType = $_POST['drugType'];
	$drugName = $_POST['drugName'];
	$drugTime = $_POST['drugTime'];
	$doseUnit = $_POST['doseUnit'];
	$drugWhen = $_POST['drugWhen'];
	$drugAdvice = $_POST['drugAdvice'];
	$diseaseID = $_POST['diseaseID'];
	$drugStr = $_POST['drugStr'];
	$drugID = getDrugIDByName($drugName, $drugType, $drugStr);
	
	$drugSettingID = insertSingleDrugsToSetting($doctorID, $diseaseID, $drugID, $drugType, $drugTime, $doseUnit, $drugWhen, $drugAdvice);
	echo  $drugSettingID;
		
	
	
}elseif ($query_no == 6){
	
	$doctorID = $_POST['doctorID'];
	$invName = $_POST['invName'];
	$note = $_POST['note'];
	$diseaseID = $_POST['diseaseID'];
	
	$invID = getInvIDByName($invName);
	
		insertSingleInvToSetting($doctorID, $diseaseID, $invID, $note);
	
	
}

elseif ($query_no == 7){

	$doctorID = $_POST['doctorID'];
	$advice = $_POST['adviceName'];
	$result = mysql_query("SELECT ds.`category` 
				FROM `doctor` d
				JOIN  doctorsettings ds ON d.doctorID = ds.doctorID
				WHERE d.`doctorCode` = '$username'");
	
	$rec = mysql_fetch_assoc($result);
	$type = $rec['category'];
	$diseaseID = $_POST['diseaseID'];

	$adviceID = getAdivceIDByName($advice, $type);

	 insertSingleAdviceToSetting($doctorID, $diseaseID, $adviceID);
	 
	 echo $adviceID;



}else if($query_no == 8){

	$queryString=$_POST['queryString'];
	return getInvList($queryString);
	
}else if($query_no == 9){

	$queryString=$_POST['queryString'];
	$result = mysql_query("SELECT ds.`category` 
				FROM `doctor` d
				JOIN  doctorsettings ds ON d.doctorID = ds.doctorID
				WHERE d.`doctorCode` = '$username'");
	
	$rec = mysql_fetch_assoc($result);
	$type = $rec['category'];
	$lang = $_POST['lang'];
	
	return getAdvcieList($queryString, $type, $lang);
	
}else if($query_no == 10){

	$advciceSettingID =$_POST['advciceSettingID'];
	mysql_query("DELETE FROM `settings_advice` WHERE `id` = '$advciceSettingID'");
	
}else if($query_no == 11){

	$invSettingID =$_POST['invSettingID'];
	mysql_query("DELETE FROM `settings_inv` WHERE `id` = '$invSettingID'");
	
}else if($query_no == 12){

	$drugSettingID =$_POST['drugSettingID'];
	mysql_query("DELETE FROM `settings_drug` WHERE `id` = '$drugSettingID'");
	
}else if($query_no == 13){

	$drugDose = $_POST['dose'];
	$drugNoOfDay = $_POST['numOfDay'];
	$drugDayType = $_POST['durationType'];
	$drugPrescribeID = $_POST['drugPrescribeID'];
	
	mysql_query("INSERT INTO `settings_dose_drug`(`drugSettingID`, `dose`, `numOfDay`, `durationType`) VALUES ($drugPrescribeID, '$drugDose', $drugNoOfDay, $drugNoDayType)");

}


?>