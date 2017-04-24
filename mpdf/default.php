<?php
include("BasicFunction.php");
session_start();
include('../phpServices/config.inc');
$username=$_SESSION['username'];
$appointmentID = $_SESSION['printAppointmentID'];
$patientCode = $_SESSION['printPatientCode'];
$date = date('d M, Y');
include('../phpServices/commonServices/appointmentService.php');
include('../phpServices/commonServices/prescriptionService.php');

class PDF extends BasicFunction{


}

$pdf = new mPDF('','A4',10,'nikosh');
$pdf->WriteHTML('');



$res = getAppointmentInfo($appointmentID);
$appData = mysql_fetch_assoc($res);
$appType = $appData['appointmentType'];
if($appType != 4){
    $patientImage = $pdf->ShowPatInfo($patientCode, 45, $username);
}
$lineStyle = array('width' => 20, 'cap' => 'butt', 'join' => 'miter', 'dash' => '', 'phase' => 0, 'color' => array(255, 0, 0));
//$pdf->Line(10, 53, 195, 53, $linestyle);
//$pdf->Line(10, 43, 195, 43, $linestyle);

$leftYaxis = 55;
$rightYaxis = 65;
$size = 10;

$leftXaxis = 15;
$rightXaxis = 90;
$maxX = 60;
$maxXForRight = 100;

$gap = 15;
$photoSize = 25;

$pageNum = 1;
$pdf->page = $pageNum;

$rightYaxis = $pdf->Show_med($appointmentID,$rightXaxis, $rightYaxis,$size + 2);
$rightYaxis = $pdf->checkForPageChange($rightYaxis, $pageNum);
$rightYaxis = $pdf->Show_advice($appointmentID,$rightXaxis,$rightYaxis + 10,$size,$maxXForRight);
$rightYaxis = $pdf->checkForPageChange($rightYaxis, $pageNum);
$rightYaxis = $pdf-> show_nextVisit($appointmentID,$rightXaxis,$rightYaxis + 10 ,$size +2);

$pageNum = 1;
$pdf->page = $pageNum;

if($appType != 4){

    if($patientImage != null){
        $pdf->displayImage($username, $patientImage,$leftXaxis,$leftYaxis,$photoSize);
        $gap = $gap + $photoSize;
    }
}

$leftYaxis=$pdf->Show_Complain($appointmentID,$leftXaxis,$leftYaxis + $gap, $maxX , $size);
$leftYaxis = $pdf->checkForPageChange($leftYaxis, $pageNum);
$leftYaxis=$pdf->Show_vital($appointmentID,$leftXaxis,$leftYaxis + 5, $maxX , $size);
$leftYaxis = $pdf->checkForPageChange($leftYaxis, $pageNum);
$leftYaxis=$pdf->Show_History($appointmentID,$leftXaxis,$leftYaxis +5, $maxX , $size, "RISK", "Risk Factors");
$leftYaxis = $pdf->checkForPageChange($leftYaxis, $pageNum);
$leftYaxis=$pdf->Show_Past_History($appointmentID,$leftXaxis,$leftYaxis + 5, $maxX, $size , 0 , "Past Disease");
$leftYaxis = $pdf->checkForPageChange($leftYaxis, $pageNum);
$leftYaxis=$pdf->Show_Past_History($appointmentID,$leftXaxis,$leftYaxis + 5, $maxX, $size , 1 , "Associated Illness");
$leftYaxis = $pdf->checkForPageChange($leftYaxis, $pageNum);
$leftYaxis=$pdf->Show_Family_History($appointmentID,$leftXaxis,$leftYaxis + 5, $maxX, $size);
$leftYaxis = $pdf->checkForPageChange($leftYaxis, $pageNum);
$leftYaxis=$pdf->Show_Drug_History($appointmentID,$leftXaxis,$leftYaxis + 5, $maxX, $size , "OLDDRUGS" , "Old Drugs");
$leftYaxis = $pdf->checkForPageChange($leftYaxis, $pageNum);
$leftYaxis=$pdf->Show_Drug_History($appointmentID,$leftXaxis,$leftYaxis + 5, $maxX, $size , "CURRDRUGS" , "Current Drugs");
$leftYaxis = $pdf->checkForPageChange($leftYaxis, $pageNum);
$leftYaxis=$pdf->showClinicalRecord($appointmentID,$leftXaxis,$leftYaxis + 5, $maxX, $size);
$leftYaxis = $pdf->checkForPageChange($leftYaxis, $pageNum);
$leftYaxis=$pdf->Show_inv($appointmentID,$leftXaxis,$leftYaxis + 5 , $maxX , $size);
$leftYaxis = $pdf->checkForPageChange($leftYaxis, $pageNum);
$leftYaxis = $pdf->Show_diagnosis($appointmentID, $leftXaxis ,$leftYaxis + 5 ,$size , $maxX);
$leftYaxis = $pdf->checkForPageChange($leftYaxis, $pageNum);
$leftYaxis=$pdf->showComment($appointmentID,$leftXaxis,$leftYaxis + 5, $maxX, $size);
$leftYaxis = $pdf->checkForPageChange($leftYaxis, $pageNum);
$leftYaxis=$pdf-> show_ref_doc($appointmentID,$leftXaxis,$leftYaxis + 5,$size);
$leftYaxis = $pdf->checkForPageChange($leftYaxis, $pageNum);

/*if($leftYaxis > $rightYaxis){
    $rightYaxis = $leftYaxis;
}
$pdf->Line($rightXaxis - 10 , 65, $rightXaxis - 10, $rightYaxis, $lineStyle);
$pdf->Line(5, $rightYaxis , 205, $rightYaxis , $lineStyle);*/

//$pdf-> show_diagnosis($appointmentID,15,55,$size);
//$pdf-> show_ref_doc($appointmentID,15,260,$size);
//$pdf->showDocInfo($username, 15, $size + 2);

$pdf->Output('');
exit;
?>
