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
		$drugWhenID = $row['drugWhenID'];
		$drugAdvice = $row['adviceTypePdf'];
		$drugAdviceID = $row['drugAdviceID'];
		
		
		$yAxis =  $this->GetY() +2;
		
		$this->SetXY($xAxis, $yAxis);
		if($drugStr  == ""){
				$this->MultiCell($nameCell,5,"$drugType. $drugName");
		}else{
			$this->MultiCell($nameCell,5,"$drugType. $drugName- $drugStr");
		}
		
		$xInnerAxis = $nameCell + 5;
		
		$this->SetFont('prolog','',$size-2);
		
		
		
		$this->SetXY($xAxis + 5, $yAxis + 6);
		$realY =  $this->GetY();
		if($drugDoseInitial != ""){
			if($drugTypeID == 3 || $drugTypeID == 15 || $drugTypeID == 41){
				$drugDoseInitial = str_replace("s"," Pv PvgP ", $drugDoseInitial);
			}else if($drugTypeID == 4){
				$drugDoseInitial = str_replace("ampl","G�cj", $drugDoseInitial);
				$drugDoseInitial = str_replace("vial","fvqvj", $drugDoseInitial);
			}else if($drugTypeID == 10 || $drugTypeID == 14){
				$drugDoseInitial = str_replace("puff","cvd", $drugDoseInitial);
			}else if($drugTypeID == 7){
				$drugDoseInitial = str_replace("d","W�c", $drugDoseInitial);
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
	
	return $rec['patientImage'];
	
}

function Show_advice($appointmentID,$xAxis,$yAxis,$size,$maxX){
	
	
	
	
	$resultData = getPrescribedAdvice($appointmentID);
	
	if(mysql_num_rows($resultData) > 0){
	
		$this->SetFont('Times','BI',$size + 2);
		
		$this->SetXY($xAxis - 3, $yAxis);
		$this->MultiCell(20,5,"Advice");
	
	}
	
	while($row=  mysql_fetch_array($resultData)){
	
		$advice = $row['advice'];
		$lang = $row['lang'];
		$pdf = $row['pdf'];
		
		
		
		if($lang == 0){
			
			$this->SetFont('Times','',$size);
			
			$yAxis =  $this->GetY();
			$this->SetXY($xAxis, $yAxis + 2);
			$this->MultiCell($maxX,5,".$advice");
			
		}else {
			$this->SetFont('prolog','',$size + 2);
			
			$yAxis =  $this->GetY();
			$this->SetXY($xAxis, $yAxis + 2);
			$this->MultiCell($maxX,5,"$pdf");
		}
	
	}
	
	return $this->GetY();
}

function Show_Complain($appointmentID,$xAxis,$yAxis, $maxX , $size) {
	
	$resultData = getPrescribedComplain($appointmentID);
	
	
	
	
	if(mysql_num_rows($resultData) > 0){
		$this->SetFont('Times','BI',$size + 1);
		$this->SetXY($xAxis, $yAxis);
		$this->MultiCell($maxX,5,"C/C");
		$yAxis += 6;
	
	}if(mysql_num_rows($resultData) == 0){
		return $yAxis - 5;
	}
	$this->SetFont('Times','',$size);
	$var = 1;
	while($row=  mysql_fetch_array($resultData)){
	
		$symptomName = $row['symptomName'];
		$durationNum = $row['durationNum'];
		$durationType = $row['durationType'];
		$durationID = $row['durationID'];
	
		$yAxis =  $this->GetY();
		$this->SetXY($xAxis, $yAxis);
		if($durationID < 5){
			$this->MultiCell($maxX,5,"$symptomName - $durationNum - $durationType");
		}elseif ($durationID == 7){
			$this->MultiCell($maxX,5,"$symptomName - $durationType");
		}else{
			$this->MultiCell($maxX,5,"$symptomName");
		}
			
		$var++;
	}
	
	return $this->GetY();
	
}

function   Show_vital($appointmentID,$xAxis, $yAxis, $maxX, $size){

	
	
	
	$resultData = getPrescribedVital($appointmentID);
	
	if(mysql_num_rows($resultData) > 0){
		$this->SetFont('Times','BI',$size + 1);
		$this->SetXY($xAxis, $yAxis);
		$this->MultiCell(20,5,"O/E");
		$yAxis += 6;
	
	}if(mysql_num_rows($resultData) == 0){
		return $yAxis - 5;
	}
	$this->SetFont('Times','',$size);
	while($row=  mysql_fetch_array($resultData)){
	
		$vitalResult = $row['vitalResult'];
		$vitalDisplayName = $row['vitalDisplayName'];

		$yAxis =  $this->GetY();
		$this->SetXY($xAxis, $yAxis);
		$this->MultiCell($maxX,5,"$vitalDisplayName:  $vitalResult");

	}
	
	return $this->GetY();
}

function show_ref_doc($appointmentID,$xAxis,$yAxis,$size){
	
	$this->SetFont('Times','BI',$size);
	
	$resultData = getPrescribedReffredDoctor($appointmentID);
	
	$rec = mysql_fetch_assoc($resultData);
	
	if($rec['doctorName'] != ""){
		$this->SetXY($xAxis, $yAxis);
		$this->MultiCell(100,5, "Refd. to: ".  $rec['doctorName'] . "-" . $rec['doctorAdress']);
	}
	
	return $this->GetY();
	
}

function Show_History($appointmentID,$xAxis,$yAxis, $maxX , $size, $typeCode, $headerText){

	
	$resultData = getPrescribedHistory($appointmentID, $typeCode);
	
	
	
	
	if(mysql_num_rows($resultData) > 0){
		$this->SetFont('Times','BI',$size);
		$this->SetXY($xAxis, $yAxis);
		$this->MultiCell(40,5,$headerText);
		$yAxis += 6;
	
	}if(mysql_num_rows($resultData) == 0){
		return $yAxis - 5;
	}
	$this->SetFont('Times','',$size);
	while($row=  mysql_fetch_array($resultData)){
	
		$historyResult = $row['historyResult'];
		$historylDisplayName = $row['historyName'];
	
		$yAxis =  $this->GetY();
		$this->SetXY($xAxis, $yAxis);
		$this->MultiCell($maxX,5,"$historylDisplayName:  $historyResult");
	
	}
	
	return $this->GetY();
	
}

function Show_Past_History($appointmentID,$xAxis,$yAxis, $maxX , $size, $status , $hedearText){
	
	
	$resultData = getPrescribedPastDisease2($appointmentID, $status);
	
	
	
	
	if(mysql_num_rows($resultData) > 0){
		$this->SetFont('Times','BI',$size + 1);
		$this->SetXY($xAxis, $yAxis);
		$this->MultiCell($maxX,5,$hedearText);
		$yAxis += 6;
	
	}if(mysql_num_rows($resultData) == 0){
		return $yAxis - 5;
	}
	
	$this->SetFont('Times','',$size);
	
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
		
		$this->SetFont('Times','BI',$size + 1);
		$this->SetXY($xAxis, $yAxis);
		$this->MultiCell($maxX,5,"Family Disease");
		$yAxis += 6;
	
	}if(mysql_num_rows($resultData) == 0){
		return $yAxis - 5;
	}
	
	$this->SetFont('Times','',$size);
	while($row=  mysql_fetch_array($resultData)){
	
		$diseaseName = $row['diseaseName'];
		$relationName = $row['relationName'];
	
		$yAxis =  $this->GetY();
		$this->SetXY($xAxis, $yAxis);
		$this->MultiCell($maxX,5,"$diseaseName - $relationName");
	
	}
	
	return $this->GetY();
}

function showComment($appointmentID,$leftXaxis,$leftYaxis, $maxX, $size){

	$contentData = getContentDetail($appointmentID, "COMMENT");

	$con = mysql_fetch_assoc($contentData);
	if($con){
		$this->SetFont('Times','BI',$size + 1);
		$this->SetXY($leftXaxis, $leftYaxis);
		$this->MultiCell($maxX,5,"Comment");
		$leftYaxis += 5;
		$data = $con['detail'];
		$this->SetXY($leftXaxis, $leftYaxis);
		$this->SetFont('Times','',$size + 1);
		$this->MultiCell($maxX,5, "$data", 0);
	}
	
	return $this->GetY();
}

function Show_Drug_History($appointmentID,$xAxis,$yAxis, $maxX , $size, $conentType , $hedearText){


	$contentData = getContentDetail($appointmentID, $conentType);

	if(mysql_num_rows($contentData) > 0){
		$this->SetFont('Times','BI',$size + 1);
		$this->SetXY($xAxis, $yAxis);
		$this->MultiCell($maxX,5,$hedearText);
		$yAxis += 6;

	}if(mysql_num_rows($contentData) == 0){
		return $yAxis - 5;
	}

	$this->SetFont('Times','',$size);

	while($row=  mysql_fetch_array($contentData)){

		$data = $row['detail'];

		$yAxis =  $this->GetY();
		$this->SetXY($xAxis, $yAxis);
		$this->MultiCell($maxX,5,"$data");

	}

	return $this->GetY();
}

function Show_inv($appointmentID, $xAxis,$yAxis,$maxX,$size) {
	
	$this->SetFont('Times','',$size);
	
	$resultData = getPrescribedInv($appointmentID);
	
	if(mysql_num_rows($resultData) > 0){
	
		$this->SetFont('Times','BI',$size + 1);
		$this->SetXY($xAxis, $yAxis);
		$this->MultiCell(20,5,"INV");
		$yAxis += 6;
	
	}if(mysql_num_rows($resultData) == 0){
		return $yAxis - 5;
	}
	
	$var = 1;
	$this->SetFont('Times','',$size);
	while($row=  mysql_fetch_array($resultData)){
		
		$invName = $row['invName'];
		
		$yAxis =  $this->GetY();
		$this->SetXY($xAxis, $yAxis);
		$this->MultiCell($maxX,5,"$invName");
		$var++;
	}
	
	return $this->GetY();
	
}

function Show_diagnosis($appointmentID,$xAxis,$yAxis, $size, $maxX){
	
	$resultData = getPrescribedDiagnosis($appointmentID);
	
	
	
	$con = mysql_fetch_assoc($resultData);
	if($con){
		$this->SetFont('Times','BI',$size + 1);
		$this->SetXY($xAxis, $yAxis);
		$this->MultiCell($maxX,5,"Diagnosis");
		$yAxis += 5;
		$this->SetFont('Times','',$size + 1);
		$this->SetXY($xAxis, $yAxis);
		$diseaseName = $con['diseaseName'];
		$this->MultiCell($maxX, 5,"$diseaseName");
	}
	
	return $this->GetY();
	 
}

}

$pdf = new PDF();
$pdf->AddPage();
$pdf->AddFont('prolog','','prolog1.TTF',true);
$pdf->SetFont('Times', 'BI', 14, '', 'false');
$pdf->SetFillColor(200,220,255);

$res = getAppointmentInfo($appointmentID);
$appData = mysql_fetch_assoc($res);
$appType = $appData['appointmentType'];
if($appType != 4){
	$patientImage = $pdf->ShowPatInfo($patientCode, 46, $username);
	if($patientImage != null){
		$pdf->displayImage($username, $patientImage,5,5,20);
	}

}
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

