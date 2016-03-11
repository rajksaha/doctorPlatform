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

function ShowPatInfo($patientCode,$yAxis, $appointmentID){

	$resultData = getPatientInformaition($patientCode);
	
	$weight = 0;
	
	$vitalResultData = getPrescribedVital($appointmentID);
	
	while($row=  mysql_fetch_array($vitalResultData)){
	
		
		$vitalID = $row['vitalID'];
	
		if($vitalID == 81){
			$vitalResult = $row['vitalResult'];
			//$vitalDisplayName = $row['vitalDisplayName'];
			$weight = $vitalResult;
		}
	
	}

	$rec = mysql_fetch_assoc($resultData);
	
	$patientCode = $rec['patientCode'];
	
	$patientCode = substr($patientCode, -5);

	$name = $rec['name'];

	$age = $rec['age'];

	$sex = $rec['sex'];

	$date = date('d-m-y');

	$this->SetXY(30,$yAxis);
	$this->Write(5, " $name");
	
	$this->SetXY(100, $yAxis);
	$this->Write(5, "ID:$patientCode");

	$this->SetXY(133, $yAxis);
	$this->Write(5, " $age y");

	 $this->SetXY(157, $yAxis);
	$this->Write(5, "$weight kg"); 

	$this->SetXY(178, $yAxis);
	$this->Write(5, " $date");

}

function Show_med($appointmentID, $xAxis, $yAxis, $size){

	$resultData = getPresCribedDrugs($appointmentID);

	if(mysql_num_rows($resultData) > 10){
		$size = $size - 2;
	}

	if(mysql_num_rows($resultData) > 0){
		$this->SetFont('Times','',$size + 1);
		$this->SetXY($xAxis - 5, $yAxis);
		$this->MultiCell(20,5,"Rx");
		$yAxis += 6;

	}if(mysql_num_rows($resultData) == 0){
		return $yAxis - 5;
	}

	$nameCell = 100;
	$doseeCell = 40;
	$durationCell = 70;
	$whenCell = 15;

	while($row=  mysql_fetch_array($resultData)){

		$this->SetFont('Times','',$size);

		$drugType = $row['typeInitial'];
		$drugName = $row['drugName'];
		$drugStr = $row['drugStrength'];
		$drugDose = $row['drugDose'];
		$drugTime = $row['drugTimeID'];
		$drugDoseInitial = $row['drugDoseUnit'];
		$drugNoDay = $row['drugNoOfDay'];
		$drugNoDayType = $row['dayTypePdf'];
		$drugWhen = $row['whenTypePdf'];
		$drugAdvice = $row['adviceTypePdf'];
		$drugAdviceID = $row['drugAdviceID'];


		$yAxis =  $this->GetY() + 3;

		$this->SetXY($xAxis, $yAxis);
		if($drugStr  == ""){
			$this->MultiCell($nameCell,5,".$drugType. $drugName");
		}else{
			$this->MultiCell($nameCell,5,".$drugType. $drugName- $drugStr");
		}

		$xInnerAxis = $nameCell + 5;

		$this->SetFont('prolog','',$size-2);



		$this->SetXY($xAxis + 3, $yAxis + 5);

		if($drugDoseInitial != ""){
				
			$drugDoseInitial = str_replace("ampl","GÂ¤cj", $drugDoseInitial);
			$drugDoseInitial = str_replace("vial","fvqvj", $drugDoseInitial);
			$drugDoseInitial = str_replace("s"," Pv PvgP ", $drugDoseInitial);
			$drugDoseInitial = str_replace("d","WÂªc", $drugDoseInitial);
			$drugDoseInitial = str_replace("puff","cvd", $drugDoseInitial);
				
		}

		$drugDose = str_replace("-","+", $drugDose);
		if($drugTime == 1){
			if($drugAdviceID == 14){
				$drugDose =  "$drugDose + 0 + 0";
			}else if ($drugAdviceID == 15){
				$drugDose =  "0 + 0 + $drugDose";
			}else{
				$drugDose =  "0 + $drugDose + 0";
			}
				
		}else if($drugTime == 2){
			list($num,$type) = explode('+', $drugDose, 2);
			$drugDose =  "$num + 0+ $type";
		}

		if($drugNoDay == 0){
			$drugNoDay = "";
		}
		$restOftheString = "$drugWhen $drugAdvice $drugNoDay $drugNoDayType";
		//$xInnerAxis = $xInnerAxis + $doseeCell + 5;
		//$this->SetXY($xAxis + $doseeCell, $yAxis + 5 );
		//$this->MultiCell($durationCell,5,"$restOftheString |");


		if($drugDoseInitial == ""){
				
			$this->MultiCell(100,5,"$drugDose $restOftheString |");
		}else{
			$this->MultiCell(100,5,"($drugDose) $drugDoseInitial $restOftheString |");
		}






		//$yAxis += 8;
	}

	return $this->GetY();

}


function show_nextVisit($appointmentID,$xAxis,$yAxis,$size){


	$resultData = getPrescribedNextVisit($appointmentID);

	$rec = mysql_fetch_assoc($resultData);

	$nextVisitType = $rec['nextVisitType'];

	$this->SetXY($xAxis, $yAxis);

	//$this->SetFont('prolog','',$size + 2);

	if($nextVisitType == 2){


		$numOfday = $rec['numOfDay'];
		$dayType = $rec['pdf'];
		$this->SetFont('prolog','',$size + 2);
		$this->MultiCell(60,5, "$numOfday - $dayType ci Avevi Avm�eb|", 0);
	}else if($nextVisitType == 1){
		$date = $rec['date'];
		$newDate = date("d-m-Y", strtotime($date));
		$this->SetFont('prolog','',$size + 2);
		$this->MultiCell(60,5, "$newDate Zvwi‡L †`Lv Ki‡eb|", 0);
	}



}



}

$pdf = new PDF();
$pdf->AddPage();
$pdf->AddFont('prolog','','prolog1.TTF',true);
$pdf->SetFont('Times','',10);
$pdf->SetFillColor(200,220,255);
//$pdf->ShowDocInfo($user);
$pdf->ShowPatInfo($patientCode,65, $appointmentID);

$leftYaxis = 75;
$rightYaxis = 75;
$size = 10;

$leftXaxis = 20;
$rightXaxis = 100;
$maxX = 65;

$rightYaxis = $pdf->Show_diagnosis($appointmentID,$rightXaxis,$rightYaxis + 5,$size);
$rightYaxis = $pdf->Show_med($appointmentID,$rightXaxis,$rightYaxis + 5,$size);
$rightYaxis = $pdf->Show_advice($appointmentID,$rightXaxis,$rightYaxis + 5,$size - 2,$maxX);


$leftYaxis=$pdf->Show_Complain($appointmentID,$leftXaxis,$leftYaxis + 3, $maxX , $size -3);
$leftYaxis=$pdf->Show_History($appointmentID,$leftXaxis,$leftYaxis + 3, $maxX , $size -3, "M/H");
$leftYaxis=$pdf->Show_History($appointmentID,$leftXaxis,$leftYaxis + 3, $maxX , $size -3, "OBS");
$leftYaxis=$pdf->Show_vital($appointmentID,$leftXaxis,$leftYaxis + 3, $maxX , $size -3);

$leftYaxis=$pdf->Show_Past_History($appointmentID,$leftXaxis,$leftYaxis + 3, $maxX, $size - 3);
$leftYaxis=$pdf->Show_Family_History($appointmentID,$leftXaxis,$leftYaxis + 3, $maxX, $size - 3);

$leftYaxis=$pdf->Show_inv($appointmentID,$leftXaxis,$leftYaxis + 3, $maxX , $size -3);

$pdf-> show_nextVisit($appointmentID,15,250,$size);
$pdf-> show_ref_doc($appointmentID,15,260,$size);
$pdf->SetXY(155,252);
$pdf->Output();

?>

