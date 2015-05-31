<?php
/* include('../config.inc'); */

if (!isset($_SESSION['username'])) {
	header('Location: index.php');
}

function getDiseasIDByName($diseaseName){
	
	
	$rec = mysql_query("SELECT `id` FROM `disease` WHERE `name` = '$diseaseName'");
	$result = mysql_fetch_assoc($rec);
	
	if($result['id'] != null && $result['id'] > 0){
		
		return $result['id'];
	}else{
		mysql_query("INSERT INTO `disease`( `name`) VALUES ('$diseaseName')");
		
		return mysql_insert_id();
	}
	
}

function getDrugIDByName($drugNameStr, $drugType){


	$rec = mysql_query("SELECT `drugID` FROM `drug` WHERE `drugName` = '$drugNameStr'");
	$result = mysql_fetch_assoc($rec);

	if($result['drugID'] != null && $result['drugID'] > 0){

		return $result['drugID'];
	}else{
		
		$drugName = "";
		$drugStr = "";
		$drugNameStr = explode(",",$drugNameStr);
		for ($i = 0; $i< sizeof($drugNameStr);$i++){
			if($i == 0){
				$drugName = $drugNameStr[$i];
			}else if($i == 1){
				$drugStr = $drugNameStr[$i];
			}
		}
		
		$sql ="INSERT INTO `drug`( `typeID`, `companyID`, `drugName`, `strength`) VALUES ('$drugType',0,'$drugName','$drugStr')";
		mysql_query($sql);

		return mysql_insert_id();
	}

}

function getInvIDByName($invName){


	$rec = mysql_query("SELECT `id` FROM `inv` WHERE `name` = '$invName'");
	$result = mysql_fetch_assoc($rec);

	if($result['id'] != null && $result['id'] > 0){

		return $result['id'];
	}else{
		mysql_query("INSERT INTO `inv`( `name`) VALUES ('$invName')");

		return mysql_insert_id();
	}

}

function getAdivceIDByName($adviceName, $type){


	$rec = mysql_query("SELECT `id` FROM `advice` WHERE `advice`  = '$adviceName'");
	$result = mysql_fetch_assoc($rec);

	if($result['id'] != null && $result['id'] > 0){

		return $result['id'];
	}else{
		$sql = "INSERT INTO `advice`(`type`, `lang`, `advice`, `pdf`) VALUES ('$type',1,'$adviceName','')";
		
		mysql_query($sql);
		
		return mysql_insert_id();
	}

}

function getSymptomIDByName($symptomName){
	
	
	$rec = mysql_query("SELECT `symptomID`, `name` FROM `symptom` WHERE `name`  = '$symptomName'");
	$result = mysql_fetch_assoc($rec);
	
	if($result['symptomID'] != null && $result['symptomID'] > 0){
	
		return $result['symptomID'];
	}else{
		$sql = "INSERT INTO `symptom`( `name`) VALUES ('$symptomName')";
	
		mysql_query($sql);
	
		return mysql_insert_id();
	}
}


?>