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
	
	$address = $rec['address'];
	
	$phone = $rec['phone'];
	
	$date = date('d M,y');
	
	
	$this->SetXY(15,$yAxis - 4);
	$this->Write(5, "Patient ID No: $patientCode");
	
		
	$this->SetXY(15,$yAxis );
	$this->Write(5, "$name");
	
	$this->SetXY(70, $yAxis);
	$this->Write(5, "$age Yrs");
	
	$this->SetXY(160, $yAxis);
	$this->Write(5, "$date");
	
	$this->SetXY(130, $yAxis);
	$this->Write(5, "$sex");
	
	$this->SetXY(100, $yAxis);
	$this->Write(5, "$address");
	
	$this->SetXY(15, $yAxis - 8);
	$this->Write(5, "$phone");
	
	
	return $rec['patientImage'];
	
}

function Show_med($appointmentID, $xAxis, $yAxis, $size){

	$resultData = getPresCribedDrugs($appointmentID);

	if(mysql_num_rows($resultData) > 10){
		$size = $size - 2;
	}
	
	if(mysql_num_rows($resultData) > 0){
		$this->SetFont('Times','B',$size + 3);
		$this->SetXY($xAxis - 5, $yAxis);
		$this->MultiCell(40,5,"Rx");
		$yAxis += 6;
		
	}if(mysql_num_rows($resultData) == 0){
		return $yAxis - 5;
	}
	
	$nameCell = 100;
	$doseeCell = 40;
	$durationCell = 70;
	$whenCell = 15;
	$var = 1;
	while($row=  mysql_fetch_array($resultData)){
		
		$this->SetFont('Times','',$size + 2);
		
		$drugType = $row['typeInitial'];
		$drugTypeID = $row['drugTypeID'];
		$drugName = $row['drugName'];
		$drugStr = $row['drugStrength'];
		$drugPrescribeID = $row['id'];
		$drugTime = $row['drugTimeID'];
		$drugDoseInitial = $row['drugDoseUnit'];
		$drugWhen = $row['whenTypePdf'];
		$drugWhenID = $row['drugWhenID'];
		$drugAdvice = $row['adviceTypePdf'];
		$drugAdviceID = $row['drugAdviceID'];
		
		
		$yAxis =  $this->GetY() +2;
		
		$this->SetXY($xAxis, $yAxis);
		if($drugStr  == ""){
				$this->MultiCell($nameCell,5,"$var. $drugType. $drugName");
		}else{
			$this->MultiCell($nameCell,5,"$var. $drugType. $drugName- $drugStr");
		}
		$var = $var + 1;
		$xInnerAxis = $nameCell + 5;
		
		$this->SetFont('prolog','',$size );
		
		
		
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
			}else if($drugTypeID == 6){
				$drugDoseInitial = str_replace("u","BDwbU", $drugDoseInitial);	
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
				if($drugAdviceID == 14 || $drugWhenID == 11){
					$drugDose =  "$drugDose + 0 + 0";
				}else if ($drugAdviceID == 15 || $drugWhenID == 13){
					$drugDose =  "0 + 0 + $drugDose";
				}else if($drugAdviceID == 16 || $drugWhenID == 12){
					$drugDose =  "0 + $drugDose + 0";
				}else{
					$drugDose =  "0 + 0 + $drugDose";
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


}
$pdf = new PDF();
$pdf->AddPage();
$pdf->AddFont('prolog','','prolog1.TTF',true);
$pdf->SetFont('Times', 'U', 12, '', 'false');
$pdf->SetFillColor(200,220,255);

$res = getAppointmentInfo($appointmentID);
$appData = mysql_fetch_assoc($res);
$appType = $appData['appointmentType'];
if($appType != 4){
	$patientImage = $pdf->ShowPatInfo($patientCode, 45, $username);
}
$linestyle = array('width' => 20, 'cap' => 'butt', 'join' => 'miter', 'dash' => '', 'phase' => 0, 'color' => array(255, 0, 0));
//$pdf->Line(10, 53, 195, 53, $linestyle);
//$pdf->Line(10, 43, 195, 43, $linestyle);

$leftYaxis = 55;
$rightYaxis = 65;
$size = 10;

$leftXaxis = 15;
$rightXaxis = 90;
$maxX = 60;
$maxXForRight = 100;

$gap = 10;
$photoSize = 25;

$rightYaxis = $pdf->Show_med($appointmentID,$rightXaxis, $rightYaxis,$size + 2);
$rightYaxis = $pdf->Show_advice($appointmentID,$rightXaxis,$rightYaxis + 10,$size,$maxXForRight);

$rightYaxis = $pdf-> show_nextVisit($appointmentID,$rightXaxis,$rightYaxis + 10 ,$size +2);



if($appType != 4){
	
	if($patientImage != null){
		$pdf->displayImage($username, $patientImage,$leftXaxis,$leftYaxis,$photoSize);
		$gap = $gap + $photoSize;
	}
}

$leftYaxis=$pdf->Show_Complain($appointmentID,$leftXaxis,$leftYaxis + $gap, $maxX , $size);
$leftYaxis=$pdf->Show_vital($appointmentID,$leftXaxis,$leftYaxis + 5, $maxX , $size);
$leftYaxis=$pdf->Show_History($appointmentID,$leftXaxis,$leftYaxis +5, $maxX , $size, "RISK", "Risk Factors");

$leftYaxis=$pdf->Show_Past_History($appointmentID,$leftXaxis,$leftYaxis + 5, $maxX, $size , 0 , "Past Disease");
$leftYaxis=$pdf->Show_Past_History($appointmentID,$leftXaxis,$leftYaxis + 5, $maxX, $size , 1 , "Associated Illness");
$leftYaxis=$pdf->Show_Family_History($appointmentID,$leftXaxis,$leftYaxis + 5, $maxX, $size);
$leftYaxis=$pdf->Show_Drug_History($appointmentID,$leftXaxis,$leftYaxis + 5, $maxX, $size , "OLDDRUGS" , "Old Drugs");
$leftYaxis=$pdf->Show_Drug_History($appointmentID,$leftXaxis,$leftYaxis + 5, $maxX, $size , "CURRDRUGS" , "Current Drugs");
$leftYaxis=$pdf->Show_inv($appointmentID,$leftXaxis,$leftYaxis + 5 , $maxX , $size);
$leftYaxis = $pdf->Show_diagnosis($appointmentID, $leftXaxis ,$leftYaxis + 5 ,$size , $maxX);

$leftYaxis=$pdf->showComment($appointmentID,$leftXaxis,$leftYaxis + 5, $maxX, $size);
$leftYaxis=$pdf-> show_ref_doc($appointmentID,$leftXaxis,$leftYaxis + 5,$size);

if($leftYaxis > $rightYaxis){
	$rightYaxis = $leftYaxis;
}
$pdf->Line($rightXaxis - 10 , 65, $rightXaxis - 10, $rightYaxis, $linestyle);
$pdf->Line(5, $rightYaxis , 205, $rightYaxis , $linestyle);

//$pdf-> show_diagnosis($appointmentID,15,55,$size);
//$pdf-> show_ref_doc($appointmentID,15,260,$size);
$pdf->showDocInfo($username, 15, $size + 2);
//$pdf->Line(15, 248, 195, 248, $linestyle);
$pdf->Output();

?>

