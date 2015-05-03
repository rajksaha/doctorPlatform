<?php
require('tfpdf.php');
session_start();
include('../phpServices/config.inc');
$username=$_SESSION['username'];
$appointmentID = $_SESSION['appointmentID'];
$patientCode = $_SESSION['patientCode'];
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
function show_Days($pres_id,$x,$y){

}
function ShowPatInfo($patientCode,$yAxis){
	
	$resultData = getPatientInformaition($patientCode);
	
	$rec = mysql_fetch_assoc($resultData);
	
	$name = $rec['name'];
	
	$age = $rec['age'];
	
	$sex = $rec['sex'];
	
	$date = date('d M, Y');
	
	$this->SetXY(10,$yAxis);
	$this->Write(5, "Name: $name");
	
	$this->SetXY(80, $yAxis);
	$this->Write(5, "Age: $age");
	
	$this->SetXY(100, $yAxis);
	$this->Write(5, "Gender: $sex");
	
	$this->SetXY(140, $yAxis);
	$this->Write(5, "Date: $date");
	
}

function Show_med($appointmentID, $xAxis, $yAxis, $size){

	$resultData = getPresCribedDrugs($appointmentID);

	if(mysql_num_rows($resultData) > 10){
		$size = $size - 2;
	}
	
	if(mysql_num_rows($resultData) > 0){
		
		$this->SetXY($xAxis - 5, $yAxis);
		$this->MultiCell(20,5,"RX");
		$yAxis += 6;
		
	}
	
	$nameCell = 40;
	$doseeCell = 25;
	$durationCell = 70;
	$whenCell = 15;
	while($row=  mysql_fetch_array($resultData)){
		
		$this->SetFont('Times','',$size);
		
		$drugType = $row['typeInitial'];
		$drugName = $row['drugName'];
		$drugStr = $row['drugStrength'];
		$drugDose = $row['drugDose'];
		$drugDoseInitial = $row['drugDoseUnit'];
		$drugNoDay = $row['drugNoOfDay'];
		$drugNoDayType = $row['dayTypePdf'];
		$drugWhen = $row['whenTypePdf'];
		$drugAdvice = $row['adviceTypePdf'];
		
		
		$yAxis =  $this->GetY() + 3;
		
		$this->SetXY($xAxis, $yAxis);
		$this->MultiCell($nameCell,5,"$drugType. $drugName-$drugStr");
		$xInnerAxis = $xAxis + $nameCell + 5;
		
		$this->SetFont('prolog','',$size-2);
		
		$this->SetXY($xInnerAxis, $yAxis);
		
		if($drugDoseInitial != ""){
			
			$drugDoseInitial = str_replace("ampl","G¤cj", $drugDoseInitial);
			$drugDoseInitial = str_replace("vial","fvqvj", $drugDoseInitial);
			$drugDoseInitial = str_replace("s","Pv", $drugDoseInitial);
			$drugDoseInitial = str_replace("puff","cvd", $drugDoseInitial);
			$drugDoseInitial = str_replace("d","Wªc", $drugDoseInitial);
		}
		if($drugDoseInitial == ""){
			
			$this->MultiCell($doseeCell,5,"$drugDose");
		}else{
			$this->MultiCell($doseeCell,5,"($drugDose) $drugDoseInitial");
		}
		
		
		if($drugNoDay == 0){
			$drugNoDay = "";
		}
		$restOftheString = "$drugWhen $drugAdvice $drugNoDay $drugNoDayType";
		$xInnerAxis = $xInnerAxis + $doseeCell + 5;
		$this->SetXY($xInnerAxis, $yAxis);
		$this->MultiCell($durationCell,5,"$restOftheString |");
		
		
		
		//$yAxis += 8;
	}
	
	return $this->GetY();

}

function Show_inv($appointmentID, $xAxis,$yAxis,$maxX,$size) {
	
	$this->SetFont('Times','',$size);
	
	$resultData = getPrescribedInv($appointmentID);
	
	if(mysql_num_rows($resultData) > 0){
	
		$this->SetXY($xAxis - 2, $yAxis);
		$this->MultiCell(20,5,"INV");
		$yAxis += 6;
	
	}
	
	while($row=  mysql_fetch_array($resultData)){
		
		$invName = $row['invName'];
		
		$yAxis =  $this->GetY();
		$this->SetXY($xAxis, $yAxis);
		$this->MultiCell($maxX,5,"$invName");
	}
	
	return $this->GetY();
	
}

function   Show_vital($appointmentID,$xAxis, $yAxis, $maxX, $size){

	
	$this->SetFont('Times','',$size);
	
	$resultData = getPrescribedVital($appointmentID);
	
	if(mysql_num_rows($resultData) > 0){
	
		$this->SetXY($xAxis - 2, $yAxis);
		$this->MultiCell(20,5,"Vital");
		$yAxis += 6;
	
	}
	
	while($row=  mysql_fetch_array($resultData)){
	
		$vitalResult = $row['vitalResult'];
		$vitalDisplayName = $row['vitalDisplayName'];

		$yAxis =  $this->GetY();
		$this->SetXY($xAxis, $yAxis);
		$this->MultiCell($maxX,5,"$vitalDisplayName:  $vitalResult");

	}
	
	return $this->GetY();
}

function Show_advice($appointmentID,$xAxis,$yAxis,$size,$maxX){
	
	
	
	
	$resultData = getPrescribedAdvice($appointmentID);
	
	if(mysql_num_rows($resultData) > 0){
	
		$this->SetFont('Times','',$size);
		
		$this->SetXY($xAxis - 2, $yAxis);
		$this->MultiCell(20,5,"Advice");
	
	}
	
	while($row=  mysql_fetch_array($resultData)){
	
		$advice = $row['advice'];
		$lang = $row['lang'];
		$pdf = $row['pdf'];
		
		
		
		if($lang == 0){
			
			$this->SetFont('Times','',$size);
			
			$yAxis =  $this->GetY();
			$this->SetXY($xAxis, $yAxis);
			$this->MultiCell($maxX,5,"$advice");
			
		}else {
			$this->SetFont('prolog','',$size);
			
			$yAxis =  $this->GetY();
			$this->SetXY($xAxis, $yAxis);
			$this->MultiCell($maxX,5,"$pdf");
		}
	
	}
	
	return $this->GetY();
}

function show_nextVisit($appointmentID,$xAxis,$yAxis,$size){
	
	$this->SetFont('Times','',$size);
	
	$resultData = getPrescribedNextVisit($appointmentID);
	
	$rec = mysql_fetch_assoc($resultData);
	
	$this->SetXY($xAxis, $yAxis);
	$this->MultiCell(30,5, $rec['date']);
	
}

function show_ref_doc($appointmentID,$xAxis,$yAxis,$size){
	
	$this->SetFont('Times','',$size);
	
	$resultData = getPrescribedReffredDoctor($appointmentID);
	
	$rec = mysql_fetch_assoc($resultData);
	
	$this->SetXY($xAxis, $yAxis);
	$this->MultiCell(50,5, $rec['doctorName'] . "-" . $rec['doctorAdress']);
}

}

$pdf = new PDF();
$pdf->AddPage();
$pdf->AddFont('prolog','','prolog1.TTF',true);
$pdf->SetFont('Times','',10);
$pdf->SetFillColor(200,220,255);
//$pdf->ShowDocInfo($user);
$pdf->ShowPatInfo($patientCode,73);

$leftYaxis = 80;
$rightYaxis = 80;
$size = 12;

$leftXaxis = 5;
$rightXaxis = 75;
$maxX = 60;
$rightYaxis = $pdf->Show_med($appointmentID,$rightXaxis,$rightYaxis,$size);
$rightYaxis = $pdf->Show_advice($appointmentID,$rightXaxis,$rightYaxis + 5,$size - 2,$maxX);
$leftYaxis=$pdf->Show_vital($appointmentID,$leftXaxis,$leftYaxis, $maxX , $size -2);
$leftYaxis=$pdf->Show_inv($appointmentID,$leftXaxis,$leftYaxis + 5, $maxX, $size - 2);


/* $pdf->Show_fact($pres_id, 5,$y+10);
$y=$pdf->Show_med($pres_id,70,88,15);
$pdf->Show_advice($pres_id,70,$y+10,100);
$pdf->Show_days($pres_id,10,240); */
$pdf-> show_nextVisit($appointmentID,15,250,$size);
$pdf-> show_ref_doc($appointmentID,15,260,$size);
$pdf->Line(150,250,180,250);
$pdf->SetXY(155,252);
$pdf->Write(5,'Signature');
$pdf->Output();

?>
