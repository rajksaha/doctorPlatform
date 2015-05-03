<?php
require('tfpdf.php');
include('config.inc');
session_start();
$user=$_SESSION['username'];
class PDF extends tFPDF{
    
}
$pdf = new PDF();
$pdf->AddPage();
$pdf->AddFont('prolog','','prolog1.TTF',true);
$pdf->SetFont('Times','',12);
$pdf->SetFillColor(200,220,255);
$pdf->ShowDocInfo($user);
$pdf->Line(5,52,205,52);
$pdf->SetXY(60,60);
$pdf->SetFont('Times','',30);
$pdf->Write(5,'Postoperative Order');
$pdf->SetFont('Times','',14);
$y=$pdf->GetY();
$pdf->SetXY(10,$y+20);
$order=array("","NBM UFO from ______________.","Infuse Lactoride 1000 cc IV @ 4o drops/mm.","Inj (Ceftra/Diciplin) 18m IV Stal.","Inj Filmnt 1 sa IV Stal.","Inj Partaix __________ IV Stal.","Inj Emistal/Onaceros 8mg IV Stal.");
$i=1;
while($i<sizeof($order)){
    $y=$pdf->GetY();
    $pdf->SetXY(10,$y+10);
    $pdf->MultiCell(120,5,"$i.$order[$i]",0);
    $i++;
}
$pdf->Line(150,250,180,250);
$pdf->SetXY(155,252);
$pdf->Write(5,'Signature');
$pdf->Output();
?>
