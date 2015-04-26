<?php
include('../config.inc');
session_start();
     $username=mysql_real_escape_String($_POST['username']);
     $password=mysql_real_escape_String($_POST['password']);
     
$sql=mysql_query("SELECT * FROM doctor WHERE doctorCode='$username'");
      $rec=  mysql_fetch_array($sql);
		if($rec == ""){
			echo -1;
		}else{
			if(($rec['doctorCode']==$username)&&($rec['password']==$password)){
				$_SESSION['username'] = $username;
				$_SESSION['doctorID'] = $rec['doctorID'];
				 
				echo 1;
			}else{
				echo 0;
			}
		}
      	

      
      
?>
