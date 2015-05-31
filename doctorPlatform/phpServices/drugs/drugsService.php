<?php

session_start();
include('../config.inc');
include('../commonServices/prescriptionService.php');
include('../commonServices/prescriptionInsertService.php');
include('../commonServices/parentInsertService.php');

if (!isset($_SESSION['username'])) {
	header('Location: index.php');
}
if (isset($_SESSION['appointmentID'])) {
	$appointmentID = $_SESSION['appointmentID'];
}
if (isset($_SESSION['patientCode'])) {
	$patientCode = $_SESSION['patientCode'];
}
$username = $_SESSION['username'];


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
	$drugName = $_POST['drugName'];
	$drugID = getDrugIDByName($drugName, $drugType);
	$drugTime = $_POST['drugTime'];
	$drugDose = $_POST['drugDose'];
	$doseUnit = $_POST['doseUnit'];
	$drugNoOfDay = $_POST['drugNoOfDay'];
	$drugDayType = $_POST['drugDayType'];
	$drugWhen = $_POST['drugWhen'];
	$drugAdvice = $_POST['drugAdvice'];
	
	$result = insertPrescriptionDrugs($appointmentID, $drugType, $drugID, $drugTime, $drugDose, $doseUnit, $drugNoOfDay, $drugDayType, $drugWhen, $drugAdvice);
	
	echo $result;
	
}
else if($query_no==4){
	
	$result = getPresCribedDrugs($appointmentID);
	$data = array();
	while ($row=mysql_fetch_array($result)){
		array_push($data,$row);
	}
	echo json_encode($data);
            
	
}else if($query_no==5){
	
	$drugPrescribeID = $_POST['drugPrescribeID'];
	$drugType = $_POST['drugType'];
	$drugName = $_POST['drugName'];
	$drugID = getDrugIDByName($drugName, $drugType);
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
}elseif ($query_no == 8){
	
	$queryString=$_POST['drugName'];
	$drugType = $_POST['drugType'];
	
	$sql ="SELECT d.`drugID`, d.`typeID`, d.`companyID`, d.`drugName`, d.`strength`
			FROM `drug` d 
			LEFT JOIN drug_prescription dp  ON d.drugID = dp.drugID AND dp.appointMentID = '$appointmentID' AND  IFNULL(dp.appointMentID , 0)  = 0
			WHERE d.`drugName` LIKE '" . $queryString . "%' AND d.typeID = '$drugType'
			LIMIT 10";
	$result=mysql_query($sql);
	 $data = array();
	while ($row=mysql_fetch_array($result)){
		array_push($data,$row);
	}
	echo json_encode($data); 
}elseif ($query_no == 9){
	
	$queryString=$_POST['drugName'];
	$drugType = $_POST['drugType'];
	$drugName = "";
	$drugStr = "";
	$drugNameStr = explode(",",$queryString);
	for ($i = 0; $i< sizeof($drugNameStr);$i++){
		if($i == 0){
			$drugName = $drugNameStr[$i];
		}else if($i == 1){
			$drugStr = $drugNameStr[$i];
		}
	}
	
	$sql ="INSERT INTO `drug`( `typeID`, `companyID`, `drugName`, `strength`) VALUES ('$drugType',0,'$drugName','$drugStr')";
	mysql_query($sql);
	echo mysql_insert_id();
}elseif ($query_no == 10){
	
	$drugID = $_POST['drugID'];
	
	$sql ="DELETE FROM `drug` WHERE `drugID` = '$drugID'";
	mysql_query($sql);
}elseif ($query_no == 11){
	
	$drugID = $_POST['drugID'];
	$queryString=$_POST['drugName'];
	$sql ="UPDATE `drug` SET `drugName`='$queryString'  WHERE `drugID` = '$drugID'";
	mysql_query($sql);
}
	
?>