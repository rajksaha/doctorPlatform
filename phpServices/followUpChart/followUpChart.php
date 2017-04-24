<?php

session_start();
include('../config.inc');
include('../commonServices/prescriptionService.php');
include('../commonServices/parentInsertService.php');
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
	echo getPatientFollowUpSetting($patientID, $doctorID);
}elseif ($query_no == 1){

    $invName= $_POST['invName'];

    $invID = getInvIDByName($invName);

    mysql_query("INSERT INTO `patient_follow_up`( `patientID`, `doctorID`, `invID`) VALUES ($patientID, $doctorID, $invID)");

    echo mysql_insert_id();
}elseif ($query_no == 4){

    $jsonArray= $_POST['jsonArray'];

    $arr = json_decode($jsonArray, true );

    foreach($arr as $item) {
        $followUpID = $item['followUpID'];
        $entryDate = $item['entryDate'];
        $data = $item['data'];
        mysql_query("DELETE FROM follow_up_result where followUpID = $followUpID AND entryDate = '$entryDate'");
        mysql_query("INSERT INTO `follow_up_result`( `followUpID`, `entryDate`, `data`) VALUES ($followUpID, '$entryDate', '$data')");
    }
    echo $arr;

}elseif ($query_no == 2){
    $followUpID = $_POST['patientFollowUpID'];
    $result = mysql_query("SELECT `resultID`, `followUpID`, `data`, `entryDate` FROM `follow_up_result` WHERE `followUpID` = $followUpID ORDER BY entryDate DESC ");

    $data = array();
    while ($row=mysql_fetch_array($result)){
        array_push($data,$row);
    }

    echo json_encode($data);
}elseif ($query_no == 3){

    $result = mysql_query("SELECT DISTINCT (fur.`entryDate`)
                            FROM `follow_up_result` fur
                            JOIN patient_follow_up pfu ON fur.followUpID = pfu.patientFollowUpID
                            WHERE pfu.patientID = $patientID AND pfu.doctorID = $doctorID ORDER BY entryDate DESC");

    $data = array();
    while ($row=mysql_fetch_array($result)){
        array_push($data,$row);
    }

    echo json_encode($data);
}elseif ($query_no == 5) {

    $jsonArray = $_POST['jsonArray'];

    $arr = json_decode($jsonArray, true);

    foreach ($arr as $item) {
        $entryDate = $item['entryDate'];
        $data = $item['data'];
        mysql_query("DELETE FROM contentdetail where entityID = $appointmentID AND entityType = 'CLINICAL_RECORD' AND code = '$entryDate'");
        insertContentDetail($appointmentID, "CLINICAL_RECORD", $data, $entryDate);
    }
    echo true;
}



    function getPatientFollowUpSetting($patientID, $doctorID){
	
	$sql = mysql_query("SELECT pfu.`patientFollowUpID`, pfu.`patientID`, pfu.`doctorID`, pfu.`invID`, i.name AS invName 
			FROM `patient_follow_up` pfu
			JOIN inv i ON pfu.invID = i.id
			WHERE pfu.patientID = $patientID AND pfu.doctorID = $doctorID");
	
	$data = array();
	while ($row=mysql_fetch_array($sql)){
		array_push($data,$row);
	}
	
	return json_encode($data);
	
}

?>