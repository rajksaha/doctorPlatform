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
$date=date("Y-m-d");
$query_no=  $_POST['query'];


if($query_no== 0){

    $queryString = $_POST['invName'];

    $sql = "SELECT i.`id` , i.`name`
			FROM `inv` i
			WHERE i.`name` LIKE '" . $queryString . "%' LIMIT 10";

    $result=mysql_query($sql);
    $data = array();
    while ($row=mysql_fetch_array($result)){
        array_push($data,$row);
    }

    echo json_encode($data);
}else if ($query_no == 11){

    $invName= $_POST['invName'];

    $invID = getInvIDByName($invName);

    $sql = "INSERT INTO `doctor_followUp_setteing`( `doctorID`, `invID`) VALUES ('$doctorID','$invID')";

    mysql_query($sql);

}else if ($query_no == 12){
    $sql = "SELECT dfs.`followUpSerttingID`, dfs.`doctorID`, dfs.`invID`, i.name AS invName
			FROM `doctor_followup_setteing` dfs
			JOIN inv i ON i.id = dfs.invID
			WHERE dfs.doctorID = '$doctorID'";

    $result=mysql_query($sql);

    $data = array();
    while ($row=mysql_fetch_array($result)){
        array_push($data,$row);
    }

    echo json_encode($data);
}else if ($query_no == 13){

    $id = $_POST['id'];

    $sql = "DELETE FROM `doctor_followup_setteing` WHERE `followUpSerttingID` = '$id'";

    mysql_query($sql);

}else if ($query_no == 14){

    $perPage = $_POST['perPage'];
    $from = $_POST['from'];

    $sql = "SELECT `invCategoryID`, `name` FROM `inv_categoty`";

    $result = mysql_query("SELECT i.`id`, i.`categoryID`, i.`name`, ic.name AS invCategoryName 
                            FROM `inv` i 
                            LEFT JOIN inv_category ic ON ic.invCategoryID = i.id
                            LIMIT $perPage OFFSET $from");

    $data = array();
    while ($row=mysql_fetch_array($result)){
        array_push($data,$row);
    }

    echo json_encode($data);

}else if ($query_no == 15){


    $sql = "SELECT `invCategoryID`, `name` FROM `inv_category`";

    $result = mysql_query($sql);

    $data = array();
    while ($row=mysql_fetch_array($result)){
        array_push($data,$row);
    }

    echo json_encode($data);

}else if ($query_no == 16){


    $jsonArray = $_POST['invList'];

    $arr = json_decode($jsonArray, true);

    foreach ($arr as $item) {
        $categoryID = $item['categoryID'];
        $id = $item['id'];
        $name = $item['name'];
        mysql_query("UPDATE `inv` SET `categoryID`= $categoryID, `name` = '$name' WHERE id = $id");
    }

}



?>