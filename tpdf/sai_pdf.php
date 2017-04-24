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
		$this->SetFont('Times','BI',$size + 1);
		$this->SetXY($xAxis - 5, $yAxis);
		$this->MultiCell(40,5,"Treatment");
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
		$drugTypeID = $row['drugTypeID'];
		$drugName = $row['drugName'];
		$drugStr = $row['drugStrength'];
		$drugPrescribeID = $row['id'];
		$drugTime = $row['drugTimeID'];
		$drugDoseInitial = $row['drugDoseUnit'];
		$drugWhen = $row['whenTypePdf'];
		$drugAdvice = $row['adviceTypePdf'];
		$drugAdviceID = $row['drugAdviceID'];
		
		
		$yAxis =  $this->GetY() +2;
		
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
			if($drugTypeID == 3 || $drugTypeID == 15 || $drugTypeID == 41){
				$drugDoseInitial = str_replace("s"," Pv PvgP ", $drugDoseInitial);
			}else if($drugTypeID == 4){
				$drugDoseInitial = str_replace("ampl","G¤cj", $drugDoseInitial);
				$drugDoseInitial = str_replace("vial","fvqvj", $drugDoseInitial);
			}else if($drugTypeID == 10 || $drugTypeID == 14){
				$drugDoseInitial = str_replace("puff","cvd", $drugDoseInitial);
			}else if($drugTypeID == 7){
				$drugDoseInitial = str_replace("d","Wªc", $drugDoseInitial);
			}
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
					$drugDose =  "0 + $drugDose + 0";
				}
					
			}else if($drugTime == 2){
				list($num,$type) = explode('+', $drugDose, 2);
				$drugDose =  "$num + 0 + $type";
			}
				
			
			$yAxis =  $this->GetY();
			$this->SetXY($xAxis + 5, $yAxis);
			
			if($drugDoseInitial == ""){
					
				$this->MultiCell($doseeCell,5,"$drugDose");
			}else{
				$this->MultiCell($doseeCell,5,"($drugDose) $drugDoseInitial");
			}
				
				
			if($drugNoDay == 0){
				$drugNoDay = "";
			}
			$restOftheString = "$drugWhen $drugAdvice - $drugNoDay $drugNoDayType";
			$xInnerAxis = $xAxis + 5 +  $doseeCell;
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
						$drugDose =  "0 + $drugDose + 0";
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
			
			$this->SetY($yAxis + 5);
		}
		//$yAxis += 8;
	}
	
	return $this->GetY();

}

function ShowPatInfo($patientCode,$yAxis, $appointmentID){
	
	$resultData = getPatientInformaition($patientCode);
	
	$rec = mysql_fetch_assoc($resultData);
	
	$name = $rec['name'];
	
	$age = $rec['age'];
	
	$sex = $rec['sex'];
	
	$date = date('d M, Y');
	
	$patientCode = $rec['patientCode'];
	
	
	$this->SetXY(10,$yAxis);
	$this->Write(5, "ID: $patientCode");
	
	$this->SetXY(50,$yAxis);
	$this->Write(5, "Name: $name");
	
	$this->SetXY(130, $yAxis);
	$this->Write(5, "Age: $age");
	
	$this->SetXY(150, $yAxis);
	$this->Write(5, "Date: $date");
	
}


}

$pdf = new PDF();
$pdf->AddPage();
$pdf->AddFont('prolog','','prolog1.TTF',true);
$pdf->SetFont('Times', 'BI', 14, '', 'false');
$pdf->SetFillColor(200,220,255);

$pdf->ShowPatInfo($patientCode,46,$username);
$linestyle = array('width' => 20, 'cap' => 'butt', 'join' => 'miter', 'dash' => '', 'phase' => 0, 'color' => array(255, 0, 0));
$pdf->Line(10, 53, 195, 53, $linestyle);
//$pdf->Line(10, 43, 195, 43, $linestyle);

$leftYaxis = 55;
$rightYaxis = 60;
$size = 12;

$leftXaxis = 15;
$rightXaxis = 90;
$maxX = 60;
$maxXForRight = 100;





$rightYaxis = $pdf->Show_med($appointmentID,$rightXaxis, $rightYaxis,$size + 2);
$rightYaxis = $pdf->Show_advice($appointmentID,$rightXaxis,$rightYaxis + 10,$size,$maxXForRight);

$pdf-> show_nextVisit($appointmentID,$rightXaxis,$rightYaxis + 10 ,$size +2);

$pdf->Line($rightXaxis - 10 , 55, $rightXaxis - 10, $rightYaxis + 20, $linestyle);

$leftYaxis=$pdf->Show_Complain($appointmentID,$leftXaxis,$leftYaxis + 5, $maxX , $size);
$leftYaxis=$pdf->Show_vital($appointmentID,$leftXaxis,$leftYaxis +5, $maxX , $size);
$leftYaxis=$pdf->Show_History($appointmentID,$leftXaxis,$leftYaxis + 5, $maxX , $size, "RISK", "Risk Factors");

$leftYaxis=$pdf->Show_Past_History($appointmentID,$leftXaxis,$leftYaxis + 5, $maxX, $size , 0 , "Past Disease");
$leftYaxis=$pdf->Show_Past_History($appointmentID,$leftXaxis,$leftYaxis + 5, $maxX, $size , 1 , "Associated Illness");
$leftYaxis=$pdf->Show_Family_History($appointmentID,$leftXaxis,$leftYaxis + 3, $maxX, $size);
$leftYaxis=$pdf->Show_inv($appointmentID,$leftXaxis,$leftYaxis + 5, $maxX , $size);
$leftYaxis = $pdf->Show_diagnosis($appointmentID, $leftXaxis ,$leftYaxis + 5,$size + 1, $maxX);

$leftYaxis=$pdf->showComment($appointmentID,$leftXaxis,$leftYaxis + 5, $maxX, $size);

$leftYaxis=$pdf->Show_Drug_History($appointmentID,$leftXaxis,$leftYaxis + 5, $maxX, $size , "OLDDRUGS" , "Old Drugs");
$leftYaxis=$pdf->Show_Drug_History($appointmentID,$leftXaxis,$leftYaxis + 5, $maxX, $size , "CURRDRUGS" , "Current Drugs");

$pdf-> show_ref_doc($appointmentID,15,260,$size);
//$pdf->Line(15, 248, 195, 248, $linestyle);
$pdf->Output();

?>

