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
        if($pref==1){
            $this->SetXY(32,$y);
            $this->Write(5,"$name");
            $this->SetXY(136, $y);
            $this->Write(5, "$age yrs");
            $this->SetXY(170, $y);
            $this->Write(5, "$date");
        }else if($pref==0){
        }
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
$y=$pdf->Show_med($pres_id,70,60);
$pdf->Show_advice($pres_id,70,$y+10,100);
$y=$pdf->Show_days($pres_id,10,220);
$y=$pdf->show_ref_doc($pres_id,10,$y);
$pdf->Line(150,230,180,230);
$pdf->SetXY(155,232);
$pdf->Write(5,'Signature');
$pdf->Output();

?>

