<?php
require('tfpdf.php');
include('../config.inc');
include('hrpCheck.php');
session_start();
$pid=$_SESSION['prev_pat'];
$pres_id=$_SESSION['prev_pres'];
$user=$_SESSION['username'];
class PDF extends tFPDF{

    function BasicTable($header, $data,$unit)
{
    // Header
        $this->Ln();
    for($i=0;$i<sizeof($data);$i++){
        
        if($data[$i]!=""){
            $header[$i]="$header[$i]:$data[$i] $unit[$i]";
        $this->MultiCell(45,3,$header[$i],1);
    }
    }
}
function Head()
{
    $this->Image('head.jpg',5,5,200);
}

function Foot()
{
    //$this->SetY(-15);
   $this->Image('foot.jpg',5,260,200);
}

function show_Days($pres_id,$x,$y){
$this->SetFont('Times','',10);
    $sql= mysql_query("select * from nextvisit where pres_id=$pres_id");
                       $goal=  mysql_fetch_array($sql);
                       $some=$goal['time'];
                       if($some!=""){
                           $this->SetXY($x, $y);
                           $some=getPdf($some);
                           $this->SetFont('prolog','',14);
                           $this->write(5,"$some ci †`Lv‡Z Avm‡eb|");
                           $y+=5;
                       }
                       return $y;
                       
                             }
       
function ShowPatInfo($user,$pid,$y){
   $b = mysql_fetch_array(mysql_query("select * from doc_info where d_id='$user'"));
    $doctor_id=$b['id'];
	$a = mysql_fetch_array(mysql_query("select * from doc_set where id='$doctor_id'"));
	$pref=$a['style'];
        $sql = "SELECT sex,name,phone,dob from person where phone='$pid'";
$result=mysql_query($sql);
$row = mysql_fetch_array($result);
$name=$row['name'];
$age= $row['dob'];
$date = date('d M, Y');

$pres_id=$_SESSION['pres'];
        //weight
       $result2=(mysql_query("select * from vital_vals where presId=$pres_id AND vitalId=81"));
       $row2=  mysql_fetch_array($result2);
       $weight=  $row2["vitalVal"];
       
           
        if($pref==1){
            $this->SetXY(32,48);
            $this->Write(5,"$name");
            $this->SetXY(100, 48);
            $this->Write(5, "$age Years");
            $this->SetXY(150, 48);
            $this->Write(5, "$weight kg");
            $this->SetXY(175, 48);
            $this->Write(5, "$date");
        }else{
            //$this->Line(5,52,205,52);
            $this->SetXY(10,$y);
            $this->Write(5, "ID:$ass_id");
            $x=  $this->GetX();
            $this->SetXY($x+5,$y);
            $this->Write(5, "Name:$name");
             $x=  $this->GetX();
            $this->SetXY($x+10, $y);
            $this->Write(5, "Age:$age");
            $x=  $this->GetX();
            //$this->Line(130,52,130,60);
            //$this->Line(132,52,132,60);
            $this->SetXY(140, $y);
            $this->Write(5, "Date:$date");
            //$this->Line(5,60,205,60);
            //$this->Line(5,62,205,62);
        }
}


function   Show_vital($pres_id,$x,$y){
$d_y=$y;
$y+=5;
$this->SetY($y);
$this->SetFont('Times','',11);
$var=0;
          $result=(mysql_query("select * from vital_vals where presId=$pres_id"));
          while($row=  mysql_fetch_array($result)){
              $v_id=$row['vitalId'];
              $sql=mysql_query("SELECT * FROM vital_definition WHERE vitalId=$v_id");
              $get = mysql_fetch_assoc($sql);
              $v_name=$get['vitalName'];
              $v_data=$row['vitalVal'];
            if(($v_data!="") && ($v_name != 'Weight')){
                $var=1;
                $this->SetXY($x, $y);
                $this->MultiCell(65,5,"$v_name-$v_data",0);
                $y+=5;
            }
          }
          if($var==1){
              $this->SetFont('Times','',12);
            $this->SetXY($x, $d_y);
            $this->Write(5,"O.E");
          }else{
              $y-=5;
          }
          return $y;
}

function Show_diagnosis($pres_id,$x,$y){
    $this->SetFont('Times','',12);
    $f = mysql_query("select * from diagnosis where pres_id='$pres_id'");
    $v=1;
    while($row=  mysql_fetch_array($f)){
        $d_id=$row['dis_id'];
        $ad1 = mysql_query("select * from  disease where id=$d_id");
        $name= mysql_fetch_array($ad1);
        $nam=$name['name'];
        $this->SetXY($x, $y);
        $this->Write(5,"$nam");
        $y+=5;
        $v++;
    }
return $y;
}
function show_ref_doc($pres_id,$x,$y){
$this->SetFont('Times','',12);
$sql= mysql_query("select * from reference where pres_id=$pres_id");
$goal=  mysql_fetch_array($sql);
    $doc_id=$goal['doctor_id'];
        $sql= mysql_query("select * from ref_doc where id='$doc_id'");
        $goal=  mysql_fetch_array($sql);
        $ref_name=$goal['name'];
        $ref_add=$goal['adress'];
        if($ref_name!=""){
            $this->SetXY($x, $y);
            $this->write(5,"Referred Doctor:$ref_name");
        $y+=5;
            $this->SetXY($x, $y);
        $this->write(5,"Doctor's Address:$ref_add");
        $y+=5;
        return $y;
}
}
}
$pdf = new PDF();
$pdf->AddPage();
$pdf->AddFont('prolog','','prolog1.TTF',true);
$pdf->SetFont('Times','',10);
$pdf->SetFillColor(200,220,255);
$pdf->ShowDocInfo($user);
$pdf->ShowPatInfo($user,$pid,35);
$y=$pdf->Show_Complain($pres_id,5,60);
$y=$pdf->Show_diagnosis($pres_id, 5,$y+5);
$y=$pdf->Show_vital($pres_id,5,$y+10);
$y=$pdf->Show_inv($pres_id,5,$y+10,65);
$y=$pdf->Show_med($pres_id,70,60,15);
$pdf->Show_advice($pres_id,70,$y+10,100);
$y=$pdf->Show_days($pres_id,10,220);
$y=$pdf->show_ref_doc($pres_id,10,$y);
$pdf->Line(150,230,180,230);
$pdf->SetXY(155,232);
$pdf->Write(5,'Signature');
$pdf->Output();

?>

