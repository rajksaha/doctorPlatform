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
		
		$this->SetXY($xAxis, $yAxis);
		$this->MultiCell(20,5,"RX");
		$yAxis += 6;
		
	}
	
	$nameCell = 50;
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
			
			$drugDoseInitial = str_replace("ampl","GÂ¤cj", $drugDoseInitial);
			$drugDoseInitial = str_replace("vial","fvqvj", $drugDoseInitial);
			$drugDoseInitial = str_replace("s","Pv", $drugDoseInitial);
			$drugDoseInitial = str_replace("puff","cvd", $drugDoseInitial);
			$drugDoseInitial = str_replace("d","WÂªc", $drugDoseInitial);
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
	
		$this->SetXY($xAxis, $yAxis);
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
	
		$this->SetXY($xAxis, $yAxis);
		$this->MultiCell(20,5,"O.E");
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
		
		$this->SetXY($xAxis, $yAxis);
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
	
	$this->SetFont('prolog','',$size);
	
	$resultData = getPrescribedNextVisit($appointmentID);
	
	$rec = mysql_fetch_assoc($resultData);
	
	$nextVisitType = $rec['nextVisitType'];
	
	$this->SetXY($xAxis, $yAxis);
	
	if($nextVisitType == 2){
		
		
		$numOfday = $rec['numOfDay'];
		$dayType = $rec['pdf'];
		
		$this->MultiCell(60,5, "$numOfday - $dayType ci Avevi Avm‡eb |");
	}else{
		$date = $rec['date'];
		$newDate = date("d-m-Y", strtotime($date));
		$this->MultiCell(60,5, "$newDate Zvwi‡L Avevi Avm‡eb |");
	}
	
	
	
	
	
}

function show_ref_doc($appointmentID,$xAxis,$yAxis,$size){
	
	$this->SetFont('Times','',$size);
	
	$resultData = getPrescribedReffredDoctor($appointmentID);
	
	$rec = mysql_fetch_assoc($resultData);
	
	if($rec['doctorName'] != ""){
		$this->SetXY($xAxis, $yAxis);
		$this->MultiCell(50,5, $rec['doctorName'] . "-" . $rec['doctorAdress']);
	}
	
}

function Show_History($appointmentID,$xAxis,$yAxis, $maxX , $size, $typeCode){

	
	$resultData = getPrescribedHistory($appointmentID, $typeCode);
	
	$this->SetFont('Times','',$size);
	
	
	if(mysql_num_rows($resultData) > 0){
	
		$this->SetXY($xAxis, $yAxis);
		$this->MultiCell(20,5,$typeCode);
		$yAxis += 6;
	
	}
	
	while($row=  mysql_fetch_array($resultData)){
	
		$historyResult = $row['historyResult'];
		$historylDisplayName = $row['historyName'];
	
		$yAxis =  $this->GetY();
		$this->SetXY($xAxis, $yAxis);
		$this->MultiCell($maxX,5,"$historylDisplayName:  $historyResult");
	
	}
	
	return $this->GetY();
	
}

function Show_Past_History($appointmentID,$xAxis,$yAxis, $maxX , $size){
	
	
	$resultData = getPrescribedPastDisease($appointmentID);
	
	$this->SetFont('Times','',$size);
	
	
	if(mysql_num_rows($resultData) > 0){
	
		$this->SetXY($xAxis, $yAxis);
		$this->MultiCell($maxX,5,"Past Disease");
		$yAxis += 6;
	
	}
	
	while($row=  mysql_fetch_array($resultData)){
	
		$diseaseName = $row['diseaseName'];
	
		$yAxis =  $this->GetY();
		$this->SetXY($xAxis, $yAxis);
		$this->MultiCell($maxX,5,"$diseaseName");
	
	}
	
	return $this->GetY();
}
function Show_Family_History($appointmentID,$xAxis,$yAxis, $maxX , $size){
	
	$resultData = getPrescribedFamilyDisease($appointmentID);
	
	$this->SetFont('Times','',$size);
	
	
	if(mysql_num_rows($resultData) > 0){
	
		$this->SetXY($xAxis, $yAxis);
		$this->MultiCell($maxX,5,"Family Disease");
		$yAxis += 6;
	
	}
	
	while($row=  mysql_fetch_array($resultData)){
	
		$diseaseName = $row['diseaseName'];
		$relationName = $row['relationName'];
	
		$yAxis =  $this->GetY();
		$this->SetXY($xAxis, $yAxis);
		$this->MultiCell($maxX,5,"$diseaseName - $relationName");
	
	}
	
	return $this->GetY();
}

function Show_Complain($appointmentID,$xAxis,$yAxis, $maxX , $size) {
	
	$resultData = getPrescribedComplain($appointmentID);
	
	$this->SetFont('Times','',$size);
	
	
	if(mysql_num_rows($resultData) > 0){
	
		$this->SetXY($xAxis, $yAxis);
		$this->MultiCell($maxX,5,"C.C");
		$yAxis += 6;
	
	}
	
	while($row=  mysql_fetch_array($resultData)){
	
		$symptomName = $row['symptomName'];
		$durationNum = $row['durationNum'];
		$durationType = $row['durationType'];
	
		$yAxis =  $this->GetY();
		$this->SetXY($xAxis, $yAxis);
		$this->MultiCell($maxX,5,"$symptomName - $durationNum - $durationType");
	
	}
	
	return $this->GetY();
	
}

function Show_diagnosis($appointmentID,$xAxis,$yAxis,  $size){
	
	$resultData = getPrescribedDiagnosis($appointmentID);
	
	$this->SetFont('Times','',$size);
	
	
	
	
	while($row=  mysql_fetch_array($resultData)){
	
		$diseaseName = $row['diseaseName'];
	
		$this->SetXY($xAxis, $yAxis);
		$this->MultiCell(120,5,"Diagnosis : $diseaseName");
	
	}
	
	return $this->GetY();
	 
}

}

$pdf = new PDF();
$pdf->AddPage();
$pdf->AddFont('prolog','','prolog1.TTF',true);
$pdf->SetFont('Times','',10);
$pdf->SetFillColor(200,220,255);
//$pdf->ShowDocInfo($user);
$pdf->ShowPatInfo($patientCode,73);

$leftYaxis = 90;
$rightYaxis = 90;
$size = 12;

$leftXaxis = 5;
$rightXaxis = 75;
$maxX = 60;

$rightYaxis = $pdf->Show_diagnosis($appointmentID,$rightXaxis,$rightYaxis + 5,$size);
$rightYaxis = $pdf->Show_med($appointmentID,$rightXaxis,$rightYaxis + 5,$size);
$rightYaxis = $pdf->Show_advice($appointmentID,$rightXaxis,$rightYaxis + 5,$size - 2,$maxX);


$leftYaxis=$pdf->Show_Complain($appointmentID,$leftXaxis,$leftYaxis + 3, $maxX , $size -3);
$leftYaxis=$pdf->Show_History($appointmentID,$leftXaxis,$leftYaxis + 3, $maxX , $size -3, "MH");
$leftYaxis=$pdf->Show_History($appointmentID,$leftXaxis,$leftYaxis + 3, $maxX , $size -3, "OBS");
$leftYaxis=$pdf->Show_vital($appointmentID,$leftXaxis,$leftYaxis + 3, $maxX , $size -3);
$leftYaxis=$pdf->Show_inv($appointmentID,$leftXaxis,$leftYaxis + 3, $maxX , $size -3);
$leftYaxis=$pdf->Show_Past_History($appointmentID,$leftXaxis,$leftYaxis + 3, $maxX, $size - 3);
$leftYaxis=$pdf->Show_Family_History($appointmentID,$leftXaxis,$leftYaxis + 3, $maxX, $size - 3);


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

