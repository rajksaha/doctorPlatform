<?php
include '../config.inc';
function check_bp($bp){
    if($bp == "")
        return 0;
    $bp_array = (explode("/",$bp));
    if (($bp_array[0] >= 140) || ($bp_array[1] >= 90)){
       return 1;
    }
    else
    return 0;
}
function getHrp($pres_id,$pid,$user){
    $bool="";
    $a = mysql_fetch_array(mysql_query("select * from doc_info where d_id='$user'"));
$type_doc=$a['cat'];
$b = mysql_fetch_array(mysql_query("select * from high_risk_info where pid='$pid'"));
$bp_check = mysql_fetch_array(mysql_query("select * from vital where pres_id='$pres_id'"));
$gen_check = mysql_fetch_array(mysql_query("select * from gen_info where p_id='$pid'"));
if ($b != ""){
// working
$ad = mysql_query("select * from  high_risk_info where pid='$pid'");
   while($row=  mysql_fetch_array($ad)){
         $high_id=$row['high_id'];
         if($high_id!="")
         $hrf_check = mysql_fetch_array(mysql_query("select * from  high_risk where id=$high_id")); 
         
         $type=$hrf_check['type'];
        if ($type_doc == $type){
        $b_check = "found";      
        }
        else{
            $b_check = "";          
        }
    }
//checking for bp
if ($bp_check != "")
$bp = check_bp($bp_check[1]);
else
$bp = 0;


if(($b_check!="found") && ($bp == 0) && ( $gen_check == ""))
    $bool= "NO";
else
    $bool= "YES";


if($bool=="NO"){
    $b = mysql_fetch_array(mysql_query("select * from test_result where `pres_id`='$pres_id' and `condition`=1"));
    if($b==""){
    $bool= "NO";
}else{
    $bool= "YES";
}
}
            }
            if($bool=="YES"){
                            return true;
                            }else{
                            return false;
                 }
}

function checkBoh($pid,$user){
$a = mysql_fetch_array(mysql_query("select * from doc_info where d_id='$user'"));
$type_doc=$a['cat'];
$boh = mysql_query("select * from risk_info where pid='$pid'");
while($row=  mysql_fetch_array($boh)){
$risk_id=$row['risk_id'];
$ad1 = mysql_query("select * from  risk_factor where id=$risk_id AND type=$type_doc");
$name= mysql_fetch_array($ad1);
if($name!=""){
return true;
}
}
return false;

}
function getPdf($day){
$rome=  explode(',',$day);
        if(sizeof($rome)>1){
        $i=0;
        $go="";
        while($i<sizeof($rome)){
            list($y, $m, $d) = explode('-', $rome[$i]);
            $go="$go$d-$m-20$y,";
             $i++;
        }
        return $go;
    }else{
         $dim=explode('-',$day);
        if(sizeof($dim)==1 && $day!=""){
            $res = mysql_query("SELECT * FROM what_helper WHERE id='5'");
        $ro = mysql_fetch_array($res);
        return $ro['pdf'];
        }else if($day==""){
            return $day;
        }else{
            $temp=explode('-',$day);
           
            $res = mysql_query("SELECT * FROM what_helper WHERE id=$temp[1]");
                $ro = mysql_fetch_array($res);
                $day11 = $ro['pdf'];
                return "$temp[0] $day11";
    }
    
    }
}
function getDose($dose,$ival,$when,$advice_id){
        if($ival==9 || $ival==1 || $ival==2){
            if($ival==1){
                if($when==4){
                    return "0+0+$dose";
                }else if($when==5){
                    return "$dose+0+0";
                }else if($advice_id==14){
                    return "$dose+0+0";
                }elseif ($advice_id==15) {
                     return "0+0+$dose";
            }else{
                return "0+$dose+0";
            }
            }else if($ival==2){
                $num=substr($dose, 0, 1);
                $type=substr($dose, 2, strlen($dose));
                list($num,$type) = explode('+', $dose, 2);
                return "$num+0+$type";
            }else if($ival==9){
                $temp=  explode(',', $dose);
                $i=0;
                $go="";
                while($i<sizeof($temp)){
                    $g_day=explode('(', $temp[$i]);
                    $d=$g_day[0];
                    $num=$g_day[1];
                   $dim=explode('-',$num);
                   if(sizeof($dim)==1 && $num!=""){
                        $res = mysql_query("SELECT * FROM what_helper WHERE id='5'");
                            $ro = mysql_fetch_array($res);
                            $day_full = $ro['pdf'];
                    $go="$go$d($day_full)";
                    }else{
                    $typ=explode('-', $g_day[1]);
                    $num=$typ[0];
                    $p=explode('-', $typ[1]);
                    $ret=$p[0];
                    $res = mysql_query("SELECT * FROM what_helper WHERE id='$ret'");
                            $ro = mysql_fetch_array($res);
                            $day_full = $ro['pdf'];
                    $go="$go$d($num-$day_full)";
                    }
                    $i++;
                }
                return $go;
            }
        }else{
            return $dose;
        }
    }
    function changeFormet($date){
        $dim=explode('-',$date);
        return "$dim[2]/$dim[1]/$dim[0]";
    }
?>
