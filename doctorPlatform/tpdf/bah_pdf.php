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
                       //echo"<span id='view123'>Next Visit:<b>$some</b><br/></span><br/>";
                       }
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
                             }
}
function ShowPatInfo($user,$pid,$y){
	$sql = "SELECT sex,name,phone,dob from person where phone='$pid'";
        $result= mysql_fetch_array(mysql_query("SELECT count(id) as num FROM app WHERE pid='$pid' and doctor_id='$user'"));
        $num=$result['num'];
$result=mysql_query($sql);
$row = mysql_fetch_array($result);
$name=$row['name'];
$age= $row['dob'];
$phone=$row['phone'];
$date = date('d M, Y');

             $this->SetXY(120,35);
            $this->Write(5, "ID:$phone");
            $x=  $this->GetX();
            $this->SetXY($x+5,35);
            $this->Write(5, "Visit:$num");
            $this->SetXY(10,$y);
            $this->Write(5, "Name:$name");
             $x=  $this->GetX();
            $this->SetXY($x+10, $y);
            $this->Write(5, "Age:$age");
            $x=  $this->GetX();
            $this->SetXY(140, $y);
            $this->Write(5, "Date:$date");
        }

function  Show_fact($pres_id,$x,$y) {
    $f = mysql_query("select * from diagnosis where pres_id='$pres_id'");
    $y+=5;
    $v=1;
    while($row=  mysql_fetch_array($f)){
        $d_id=$row['dis_id'];
        $ad1 = mysql_query("select * from  disease where id=$d_id");
        $name= mysql_fetch_array($ad1);
        $nam=$name['name'];
        $this->SetXY($x, $y);
        $this->Write(5,"/\\ $nam");
        $y+=5;
        $v++;
    }
return $y;
}

}

$pdf = new PDF();
$pdf->AddPage();
//$pdf->AddFont('akaash','','akaash.ttf',true);
$pdf->AddFont('prolog','','prolog1.TTF',true);
$pdf->SetFont('Times','',10);
$pdf->SetFillColor(200,220,255);
$pdf->ShowDocInfo($user);
$pdf->ShowPatInfo($user,$pid,73);
$y=$pdf->Show_Complain($pres_id,5,88);
$y=$pdf->Show_vital($pid,$pres_id,5,$y+10);
$y=$pdf->Show_inv($pres_id,5,$y+10,65);
$pdf->Show_fact($pres_id, 5,$y+10);
$y=$pdf->Show_med($pres_id,70,88,15);
$pdf->Show_advice($pres_id,70,$y+10,100);
$pdf->Show_days($pres_id,10,240);
$pdf->Line(150,250,180,250);
$pdf->SetXY(155,252);
$pdf->Write(5,'Signature');
$pdf->Output();

?>

