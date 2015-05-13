<?php

function getdrugList($queryString, $drugType){
	
	$sql ="SELECT d.`drugID`, d.`typeID`, d.`companyID`, d.`drugName`, d.`strength`
			FROM `drug` d
			WHERE d.`drugName` LIKE '" . $queryString . "%' AND d.typeID = '$drugType'
				LIMIT 10";
	$result=mysql_query($sql);
	$data ="<p id='searchresults'>";
	
	while($row=  mysql_fetch_array($result)){
		$name = $row['drugName'];
		$str = $row['strength'];
		$display = "$name - $str";
		$data.= '<a href="javascript:autocompleteDrugs(\''.$name.'\')">';
			
		$data.= '<span class="searchheading">'.$display.'</span>';
	}
	
	$data.= "</p>";
	echo $data;
}

function getDiseaseList($queryString){

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

function getInvList($queryString){
	
	$sql = "SELECT i.`id` , i.`name`
	FROM `inv` i
	WHERE i.`name` LIKE '" . $queryString . "%' LIMIT 10";
	
	$result = mysql_query($sql);
	$data ="<p id='searchresults'>";

	while($row1=  mysql_fetch_array($result)){
		$name=$row1['name'];
			
		$data.= '<a href="javascript:autocompleteInv(\''.$name.'\')">';
			
		$data.= '<span class="searchheading">'.$name.'</span>';
	}

	$data.= "</p>";
	echo $data;
}

function getInvListForSetting($queryString){

	$sql = "SELECT i.`id` , i.`name`
	FROM `inv` i
	WHERE i.`name` LIKE '" . $queryString . "%' LIMIT 10";

	$data ="<p id='searchresults'>";

	while($row1=  mysql_fetch_array($result)){
		$name=$row1['name'];
			
		$data.= '<a href="javascript:autocompleteInvForSetting(\''.$name.'\')">';
			
		$data.= '<span class="searchheading">'.$name.'</span>';
	}

	$data.= "</p>";
	echo $data;
}

function getAdvcieList($queryString, $type, $lang){

	$sql = "SELECT a.`id` , a.`type` , a.`lang` , a.`advice` , a.`pdf`
			FROM `advice` a
			WHERE a.lang = '$lang' AND a.type = '$type' AND  a.`advice` LIKE '" . $queryString . "%'
			
			UNION 

			SELECT a.`id` , a.`type` , a.`lang` , a.`advice` , a.`pdf`
			FROM `advice` a
			WHERE a.lang = '$lang' AND a.type = 0 AND  a.`advice` LIKE '" . $queryString . "%' LIMIT 10";

	$result=mysql_query($sql);

	$data ="<p id='searchresults'>";

	while($row1=  mysql_fetch_array($result)){
		$name=$row1['advice'];
			
		$data.= '<a href="javascript:autocompleteAdvice(\''.$name.'\')">';
			
		$data.= '<span class="searchheading">'.$name.'</span>';
	}

	$data.= "</p>";
	echo $data;
}
?>