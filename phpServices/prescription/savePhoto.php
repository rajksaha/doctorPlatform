<?php

	session_start();
	include('../config.inc');
	include('../commonServices/prescriptionInsertService.php');
	$username = $_SESSION['username'];
	$patientCode = $_SESSION['patientCode'];
    $target_dir = "./../../images/patientImage/";
    $name = $patientCode;
     print_r($_FILES);
     $temp = explode(".", $_FILES["file"]["name"]);
	$newfilename = $patientCode . '.' . end($temp);
     $target_file = $target_dir . $newfilename;

     move_uploaded_file($_FILES["file"]["tmp_name"], $target_file);

	$rec = mysql_query("SELECT `contentDetailID` FROM `contentdetail` WHERE `contentType`='PATIENTIMG' AND `entityID` = $patientCode");
	$data = mysql_fetch_assoc($rec);
	if($data){
		mysql_query("UPDATE `contentdetail` SET `detail`= '$target_file' WHERE `contentType` = 'PATIENTIMG' AND `entityID` = $appointmentNO");
	}else{
		insertContentDetail($patientCode, "PATIENTIMG", $target_file, "");		
	}

?>