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
		$drugPrescribeID = $row['id'];
		$drugTime = $row['drugTimeID'];
		$drugDoseInitial = $row['drugDoseUnit'];
		$drugWhen = $row['whenTypePdf'];
		$drugAdvice = $row['adviceTypePdf'];
		
		
		$yAxis =  $this->GetY() + 7;
		
		$this->SetXY($xAxis, $yAxis);
		if($drugStr  == ""){
				$this->MultiCell($nameCell,5,".$drugType. $drugName");
		}else{
			$this->MultiCell($nameCell,5,".$drugType. $drugName- $drugStr");
		}
		
		$xInnerAxis = $nameCell + 5;
		
		$this->SetFont('prolog','',$size-2);
		
		
		
		$this->SetXY($xAxis + 5, $yAxis + 6);
		$realY =  $this->GetY();
		if($drugDoseInitial != ""){
			
			$drugDoseInitial = str_replace("ampl","G¤cj", $drugDoseInitial);
			$drugDoseInitial = str_replace("vial","fvqvj", $drugDoseInitial);
			$drugDoseInitial = str_replace("s"," Pv PvgP ", $drugDoseInitial);
			$drugDoseInitial = str_replace("puff","cvd", $drugDoseInitial);
			$drugDoseInitial = str_replace("d","Wªc", $drugDoseInitial);
		}
		
		$doseData = getPreiodicListforPdf($drugPrescribeID);
		
		
		
		if($drugTime != -1){
				
			$dose = mysql_fetch_assoc($doseData);
			$drugDose = $dose['dose'];
			$drugNoDay = $dose['numOfDay'];
			$drugNoDayType = $dose['pdf'];
				
			$drugDose = str_replace("-","+", $drugDose);
			if($drugTime == 1){
				if($drugAdviceID == 14){
					$drugDose =  "$drugDose + 0 + 0";
				}else if ($drugAdviceID == 15){
					$drugDose =  "0 + 0 + $drugDose";
				}else{
					$drugDose =  "0 + 0 + $drugDose";
				}
					
			}else if($drugTime == 2){
				list($num,$type) = explode('+', $drugDose, 2);
				$drugDose =  "$num + 0 + $type";
			}
				
			
			$yAxis =  $this->GetY();
			$this->SetXY($xInnerAxis + 5, $yAxis);
			
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
			$this->SetXY($xInnerAxis, $realY);
			$this->MultiCell($durationCell,5,"$restOftheString |");
		}else{
			
			while ($dose = mysql_fetch_array($doseData)){
		
				$drugDose = $dose['dose'];
				$drugNoDay = $dose['numOfDay'];
				$drugNoDayType = $dose['pdf'];
					
				$drugDose = str_replace("-","+", $drugDose);
				if($drugTime == 1){
					if($drugAdviceID == 14){
						$drugDose =  "$drugDose + 0 + 0";
					}else if ($drugAdviceID == 15){
						$drugDose =  "0 + 0 + $drugDose";
					}else{
						$drugDose =  "0 + 0 + $drugDose";
					}
		
				}else if($drugTime == 2){
					list($num,$type) = explode('+', $drugDose, 2);
					$drugDose =  "$num + 0 + $type";
				}
		
				$yAxis =  $this->GetY();
				$this->SetXY($xInnerAxis + 5, $yAxis);
		
				if($drugDoseInitial == ""){
		
					$this->MultiCell($doseeCell,5,"$drugDose");
				}else{
					$this->MultiCell($doseeCell,5,"($drugDose) $drugDoseInitial");
				}
		
				$xInnerAxis = $xInnerAxis + $doseeCell + 5;
				$this->SetXY($xInnerAxis, $yAxis);
				$this->MultiCell(15,5," $drugNoDay $drugNoDayType");
		
				$xInnerAxis = $xInnerAxis - $doseeCell - 5;
			}
				
			$restOftheString = "$drugWhen $drugAdvice";
			$xInnerAxis = $xInnerAxis + $doseeCell + 20;
			$this->SetXY($xInnerAxis, $realY);
			$this->MultiCell($durationCell,5,"$restOftheString |");
			$this->SetY($yAxis);
		}
		
		
		
		
		
		
		//$yAxis += 8;
	}
	
	return $this->GetY();

}

function ShowPatInfo($patientCode,$yAxis){
	
	$resultData = getPatientInformaition($patientCode);
	
	$rec = mysql_fetch_assoc($resultData);
	
	$name = $rec['name'];
	
	$age = $rec['age'];
	
	$sex = $rec['sex'];
	
	$patientCode = $rec['patientCode'];
	
	$patientCode = substr($patientCode, -5);
	
	$date = date('d M, Y');
	
	$this->SetFont('Times','', 13);
	
	$this->SetXY(150,35);
    $this->Write(5, "ID:$patientCode");
	
	$this->SetXY(32,$yAxis);
	$this->Write(5, "$name");
	
	$this->SetXY(138, $yAxis);
	$this->Write(5, "$age yrs");
	
	$this->SetXY(170, $yAxis);
	$this->Write(5, "$date");
	
			//$this->SetXY(150,35);
            //$this->Write(5, "ID:$phone");
            //$x=  $this->GetX();
            //$this->SetXY($x+5,35);
            //$this->Write(5, "Visit:$num");
            //$this->SetXY(32,$y);
            //$this->Write(5, "$name");
            //$this->SetXY(138, $y);
            //$this->Write(5,"$age yrs");
            //$this->SetXY(170, $y);
            //$this->Write(5, "$date");
	
}





}

$pdf = new PDF();
$pdf->AddPage();
$pdf->AddFont('prolog','','prolog1.TTF',true);
$pdf->SetFont('Times','',10);
$pdf->SetFillColor(200,220,255);
//$pdf->ShowDocInfo($user);
$pdf->ShowPatInfo($patientCode,55);

$leftYaxis = 75;
$rightYaxis = 65;
$size = 12;

$leftXaxis = 15;
$rightXaxis = 105;
$maxX = 60;
$maxXForRight = 100;

$pdf->Show_diagnosis($appointmentID,15,230 + 5,$size);

$rightYaxis = $pdf->Show_med($appointmentID,$rightXaxis,$rightYaxis + 5,$size+3);
$rightYaxis = $pdf->Show_advice($appointmentID,$rightXaxis,$rightYaxis + 5,$size,$maxXForRight);


$leftYaxis=$pdf->Show_Complain($appointmentID,$leftXaxis,$leftYaxis + 3, $maxX , $size);
$leftYaxis=$pdf->Show_vital($appointmentID,$leftXaxis,$leftYaxis + 15, $maxX , $size);
$leftYaxis=$pdf->Show_inv($appointmentID,$leftXaxis,$leftYaxis + 10, $maxX , $size);
$leftYaxis=$pdf->Show_Past_History($appointmentID,$leftXaxis,$leftYaxis + 3, $maxX, $size);
$leftYaxis=$pdf->Show_Family_History($appointmentID,$leftXaxis,$leftYaxis + 3, $maxX, $size);

$pdf-> show_nextVisit($appointmentID,5,270,$size);


$pdf-> show_ref_doc($appointmentID,15,250,$size);
$pdf->Output();

?>

