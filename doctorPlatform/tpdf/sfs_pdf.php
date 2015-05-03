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



}

$pdf = new PDF();
$pdf->AddPage();
$pdf->AddFont('prolog','','prolog1.TTF',true);
$pdf->SetFont('Times','',12);
$pdf->SetFillColor(200,220,255);
$pdf->ShowDocInfo($user);
$pdf->ShowPatInfo($user,$pid,55);

$y=$pdf->Show_Complain($pres_id,1,70);
$y=$pdf->Show_vital($pres_id,1,$y+10);
$y=$pdf->show_comment($pres_id,1,$y+5);
if($y>240){
    $pdf->Show_days($pres_id,1,$y+5);
}else{
    $pdf->Show_days($pres_id,1,240);
}

$y=$pdf->Show_Status($pres_id,160,60,$pid,$user);

$y=$pdf->Show_diagnosis($pres_id,60,70);
$y=$pdf->Show_fact($pres_id,60,$y+5,$pid,$user);
$y=$pdf->Show_boh(60,$y,$pid,$user);
$y=$pdf->Show_med($pres_id,60,$y+5,15);
$y=$pdf->Show_inv($pres_id,60,$y,100);
$y=$pdf->Show_advice($pres_id,60,$y+10,130);
if($y>240){
    $pdf->Line(150,$y+8,180,$y+8);
$pdf->SetXY(155,$y+10);
$pdf->Write(5,'Signature');
}else{
    $pdf->Line(150,248,180,248);
$pdf->SetXY(155,250);
$pdf->Write(5,'Signature');
}

$pdf->Output();

?>

