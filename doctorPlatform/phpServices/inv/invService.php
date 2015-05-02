<?php

session_start();
include('../config.inc');
include('../commonServices/prescriptionService.php');
include('../commonServices/prescriptionInsertService.php');
if (!isset($_SESSION['username'])) {
	header('Location: index.php');
}
$username = $_SESSION['username'];
$doctorID = $_SESSION['doctorID'];
$appointmentID = $_SESSION['appointmentID'];
$patientCode = $_SESSION['patientCode'];
$patientID = $_SESSION['patientID'];
$date=date("Y-m-d");
$query_no=  $_POST['query'];


if($query_no== 0){
	
	$queryString = $_POST['invName'];
	
	$sql = "SELECT i.`id` , i.`name`
			FROM `inv` i
			LEFT JOIN doctor_inv_setteing dis ON i.id = dis.invID
			AND dis.doctorID = '$doctorID' AND IFNULL( dis.id, 0 ) = 0
			WHERE i.`name` LIKE '" . $queryString . "%' LIMIT 10";

	$result=mysql_query($sql);
	$data = array();
	while ($row=mysql_fetch_array($result)){
		array_push($data,$row);
	}

	echo json_encode($data);
}else if ($query_no == 1){
	
	$sql = "SELECT i.`id` , i.`name` , IFNULL( ip.id, 0 ) AS prescribedInvID, dis.id AS invSettingID
			FROM `inv` i
			JOIN doctor_inv_setteing dis ON i.id = dis.invID
			LEFT JOIN inv_prescription ip ON dis.invID = ip.invID  AND ip.appointMentID = '$appointmentID'
			AND dis.doctorID = '$doctorID' AND IFNULL( dis.id, 0 )
			WHERE 1 = 1";
	
	$result=mysql_query($sql);
	$data = array();
	while ($row=mysql_fetch_array($result)){
		array_push($data,$row);
	}
	
	echo json_encode($data);
	
	
}else if ($query_no == 2){
	
	$invID = $_POST['invID'];
	$displayOrder = $_POST['displayOrder'];
	
	$sql = "INSERT INTO `doctor_inv_setteing`( `doctorID`, `invID`, `displayOrder`) VALUES ('$doctorID','$invID','$displayOrder')";
	
	mysql_query($sql);
	
}else if ($query_no == 3){
	
	$invName = $_POST['invName'];
	
	$sql = "INSERT INTO `inv`( `name`) VALUES ('$invName')";
	
	mysql_query($sql);
	
	echo mysql_insert_id();
	
}else if ($query_no == 4){

	$invID = $_POST['invID'];
	$note = $_POST['note'];

	echo insertPrescriptionInv($appointmentID, $invID, $note);

}else if ($query_no == 5){

	$invID = $_POST['invID'];

	$sql = "DELETE FROM `inv_prescription` WHERE `appointMentID` = '$appointmentID' AND `invID` = '$invID'";

	mysql_query($sql);

}else if ($query_no == 6){

	$invSettingID = $_POST['invSettingID'];

	$sql = "DELETE FROM `doctor_inv_setteing` WHERE `id` = '$invSettingID'";

	mysql_query($sql);
	
	echo $sql;

}else if ($query_no == 7){
	
	$result=getPrescribedInv($appointmentID);
	$data = array();
	while ($row=mysql_fetch_array($result)){
		array_push($data,$row);
	}
	echo json_encode($data);
	
	
}else if($query_no== 8){
	
	$queryString = $_POST['invName'];
	
	$sql = "SELECT i.`id` , i.`name`
			FROM `inv` i
			LEFT JOIN inv_prescription ip ON i.id = ip.invID
			AND ip.appointMentID = '$appointmentID' AND IFNULL( ip.id, 0 ) = 0
			WHERE i.`name` LIKE '" . $queryString . "%' LIMIT 10";

	$result=mysql_query($sql);
	$data = array();
	while ($row=mysql_fetch_array($result)){
		array_push($data,$row);
	}

	echo json_encode($data);
}else if ($query_no == 9){

	$invID = $_POST['invID'];
	$note = $_POST['note'];
	$ID = $_POST['ID'];

	$sql = "UPDATE `inv_prescription` SET `invID`= '$invID' ,`note`= '$note'  WHERE `id` = '$ID'";

	mysql_query($sql);
	
	echo $sql;

}


?>