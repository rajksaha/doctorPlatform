<?php
require('tfpdf.php');
session_start();
include('../phpServices/config.inc');
$username=$_SESSION['username'];
$appointmentID = $_SESSION['printAppointmentID'];
$patientCode = $_SESSION['printPatientCode'];
$date = date('d M, Y');
include('../phpServices/commonServices/appointmentService.php');
include('../phpServices/commonServices/prescriptionService.php');
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
$pdf->SetFont('Times','',10);
$pdf->SetFillColor(200,220,255);
$res = getAppointmentInfo($appointmentID);
$appData = mysql_fetch_assoc($res);
$appType = $appData['appointmentType'];
if($appType != 4){
	$patientImage = $pdf->ShowPatInfo($patientCode, 73, $username);
	if($patientImage != null){
		$pdf->displayImage($username, $patientImage,5,5,20);
	}
	
}
$leftYaxis = 90;
$rightYaxis = 90;
$size = 12;

$leftXaxis = 5;
$rightXaxis = 75;
$maxX = 60;

$rightYaxis = $pdf->Show_diagnosis($appointmentID,$rightXaxis,$rightYaxis + 5,$size);
$rightYaxis = $pdf->Show_med($appointmentID,$rightXaxis,$rightYaxis + 10,$size);
$rightYaxis = $pdf->Show_advice($appointmentID,$rightXaxis,$rightYaxis + 5,$size - 2,$maxX);


$leftYaxis=$pdf->Show_Complain($appointmentID,$leftXaxis,$leftYaxis + 3, $maxX , $size -3);
$leftYaxis=$pdf->Show_History($appointmentID,$leftXaxis,$leftYaxis + 3, $maxX , $size -3, "MH");
$leftYaxis=$pdf->Show_History($appointmentID,$leftXaxis,$leftYaxis + 3, $maxX , $size -3, "OBS");
$leftYaxis=$pdf->Show_vital($appointmentID,$leftXaxis,$leftYaxis + 3, $maxX , $size -3);
$leftYaxis=$pdf->Show_inv($appointmentID,$leftXaxis,$leftYaxis + 3, $maxX , $size -3);
$leftYaxis=$pdf->Show_Past_History($appointmentID,$leftXaxis,$leftYaxis + 3, $maxX, $size - 3, true);
$leftYaxis=$pdf->Show_Family_History($appointmentID,$leftXaxis,$leftYaxis + 3, $maxX, $size - 3);

$pdf-> show_nextVisit($appointmentID,15,250,$size);
$pdf-> show_ref_doc($appointmentID,15,260,$size);
$pdf->Line(150,250,180,250);
$pdf->SetXY(155,252);
$pdf->Write(5,'Signature');
$pdf->Output();

?>

