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
		$this->SetFont('Times','B',$size + 3);
		$this->SetXY($xAxis , $yAxis);
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
		
		$this->SetFont('Times','',$size + 4);
		
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
		
		$this->SetFont('prolog','',$size + 2);
		
		
		
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
			}else if($drugTypeID == 11){
				$drugDoseInitial = str_replace("drp/min","Wªc/wgwbU", $drugDoseInitial);	
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
			$this->SetXY($xAxis + 5, $yAxis );
			
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

function showDocInfo($username, $yAxis, $size){

	$resultData = getDoctorInfo($username);

	
if($resultData['prescriptionStyle'] == 2){ 
$this->SetXY(10, $yAxis - 5); $this->SetFont('prolog','',$size + 6); 
$contentData = getContentDetailForPres(16, "DOCTORPRINT", "LINE1"); 
$con = mysql_fetch_assoc($contentData); $data = $con['code']; 
$this->MultiCell(100,5, $data, 0); $this->SetXY(110, $yAxis - 5); 
$this->SetFont('Times','B',$size + 6); $this->MultiCell(100,5, "Dr Shahjada Selim", 0); 
$yAxis = $yAxis + 5 ; $this->SetXY(10, $yAxis - 5); $this->SetFont('prolog','',$size - 2); 
$contentData = getContentDetailForPres(16, "DOCTORPRINT", "LINE2"); 
$con = mysql_fetch_assoc($contentData); $data = $con['code']; 
$this->MultiCell(100,5, $data, 0); $this->SetXY(110, $yAxis - 5); 
$this->SetFont('Times','',$size - 4); $this->MultiCell(100,5, "MBBS, MD (Endocrinology and Metabolism), MACE (USA)", 0); 
$yAxis = $yAxis ; $this->SetXY(10, $yAxis ); $this->SetFont('prolog','',$size + 4); 
$contentData = getContentDetailForPres(16, "DOCTORPRINT", "LINE3"); 
$con = mysql_fetch_assoc($contentData); $data = $con['code']; 
$this->MultiCell(100,5, $data, 0); $this->SetXY(110, $yAxis ); $this->SetFont('Times','',$size );
 $this->MultiCell(100,5, "Assistant Professor, Department of Endocrinology", 0); $yAxis = $yAxis + 5; $this->SetXY(110, $yAxis); 
 $yAxis = $yAxis ; $this->SetXY(10, $yAxis); $this->SetFont('prolog','',$size); 
$contentData = getContentDetailForPres(16, "DOCTORPRINT", "LINE4"); 
$con = mysql_fetch_assoc($contentData); $data = $con['code']; 
$this->MultiCell(100,5, $data, 0); $this->SetXY(110, $yAxis ); $this->SetFont('Times','',$size - 2 );
 $this->MultiCell(100,5, "Bangabandhu Shaikh Mujib Medical University, Dhaka", 0); $yAxis = $yAxis + 5; $this->SetXY(110, $yAxis); 
 $this->SetFont('Times','B',$size + 4); $this->MultiCell(100,5, "Hormone and Diabetes Specialist", 0);
$this->SetXY(10, $yAxis ); $this->SetFont('prolog','',$size + 4 ); 
$contentData = getContentDetailForPres(16, "DOCTORPRINT", "LINE5"); 
$con = mysql_fetch_assoc($contentData); $data = $con['code']; 
$this->MultiCell(100,5, $data, 0); $this->SetXY(110, $yAxis ); 
//$this->SetFont('Times','',$size); $this->MultiCell(100,5, "Dr Shahjada Selim", 0); 
 //$yAxis = $yAxis + 5; $linestyle = array('width' => 0.1, 'cap' => 'butt', 'join' => 'miter', 'dash' => '', 'phase' => 0, 'color' => array(255, 0, 0)); 
 //$this->Line(15, $yAxis +2 , 195, $yAxis +2, $linestyle); $size =$size-2; $yAxis = $yAxis ; 
 $this->SetXY(15, 225); $this->SetFont('prolog','',$size -1); 
 //$contentData = getContentDetailForPres(16, "DOCTORPRINT", "LINE4"); 
 $con = mysql_fetch_assoc($contentData); $data = $con['code']; 
 $this->MultiCell(50,5, $data, 0); $yAxis = 270; 
 
 //$this->Line(15, $yAxis, 195, $yAxis, $linestyle); $yAxis = $yAxis + 2; 
 $this->SetXY(10, $yAxis); $this->SetFont('Times','',$size -1); 
 //$this->Write(5, "Chamber: Green Life Hospital Ltd. Road No-32, Green Road, Dhaka-1205, Phone: 9612345-54");
 $yAxis = $yAxis ; $this->SetXY(40, $yAxis - 10); $this->SetFont('prolog','',$size ); 
 $contentData = getContentDetailForPres(16, "DOCTORPRINT", "LINE6"); 
 $con = mysql_fetch_assoc($contentData); $data = $con['code']; 
 $this->Write(5, $data); $yAxis = $yAxis  ; $this->SetXY(20, $yAxis - 5); 
 $contentData = getContentDetailForPres(16, "DOCTORPRINT", "LINE7"); 
 $con = mysql_fetch_assoc($contentData); $data = $con['code']; 
 $this->Write(5, $data); $yAxis = $yAxis ; $this->SetXY(45, $yAxis ); 
 $contentData = getContentDetailForPres(16, "DOCTORPRINT", "LINE8"); 
 $con = mysql_fetch_assoc($contentData); $data = $con['code']; 
 $this->Write(5, $data); $yAxis = $yAxis + 6; $this->SetXY(20, $yAxis); 
// $contentData = getContentDetailForPres(16, "DOCTORPRINT", "LINE7"); 
 //$con = mysql_fetch_assoc($contentData); $data = $con['code']; $this->Write(5, $data); 
 }


}

}


function ShowPatInfo($patientCode,$yAxis, $appointmentID){
	
	$resultData = getPatientInformaition($patientCode);
	
	$visitData = getPdfDetail($patientCode, "shs");
	
	$rec1 = mysql_fetch_assoc($visitData);
	
	$rec = mysql_fetch_assoc($resultData);
	
	$name = $rec['name'];
	
	$age = $rec['age'];
	
	$sex = $rec['sex'];
	
	$date = date('d M,y');
	
	$visit =  $rec1['visitNo'];
	
	$patientCode = $rec['patientCode'];
	
	
	$this->SetXY(10,$yAxis + 3 );
	$this->Write(5, "ID: $patientCode");
	
	$this->SetXY(10,$yAxis - 2);
	$this->Write(5, "Visit no: $visit");
	
	$this->SetXY(50,$yAxis);
	$this->Write(5, "Name: $name");
	
	$this->SetXY(130, $yAxis);
	$this->Write(5, "Age: $age yr");
	
	$this->SetXY(155, $yAxis);
	$this->Write(5, "Date: $date");
	
	return $rec['patientImage'];
	
}

function Show_advice($appointmentID,$xAxis,$yAxis,$size,$maxX){
	
	
	
	
	$resultData = getPrescribedAdvice($appointmentID);
	
	if(mysql_num_rows($resultData) > 0){
	
		$this->SetFont('Times','B',$size + 3);
		
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
		$this->SetFont('Times','B',$size + 1);
		$this->SetXY($xAxis, $yAxis);
		$this->MultiCell($maxX,5,"Chief Complaints");
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
		$this->SetFont('Times','B',$size + 1);
		$this->SetXY($xAxis, $yAxis);
		$this->MultiCell($maxX,5,"On Examination");
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
	
	$this->SetFont('Times','',$size);
	
	$resultData = getPrescribedReffredDoctor($appointmentID);
	
	$rec = mysql_fetch_assoc($resultData);
	
	if($rec['doctorName'] != ""){
		$this->SetXY($xAxis, $yAxis);
		$this->MultiCell(90,5, "Refd. to: " . $rec['doctorName']);
		$yAxis =  $this->GetY();
		$this->SetXY($xAxis, $yAxis);
		$this->MultiCell(90,5, $rec['doctorAdress']);
	}
	
	return $this->GetY();
	
}

function Show_History($appointmentID,$xAxis,$yAxis, $maxX , $size, $typeCode, $headerText){

	
	$resultData = getPrescribedHistory($appointmentID, $typeCode);
	
	
	
	
	if(mysql_num_rows($resultData) > 0){
		$this->SetFont('Times','B',$size + 1);
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
		$this->SetFont('Times','B',$size + 1);
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
	
	$this->SetFont('Times','B',$size + 1);
	
	
	if(mysql_num_rows($resultData) > 0){
		
		$this->SetFont('Times','B',$size + 1);
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
		$this->SetFont('Times','B',$size + 1);
		$this->SetXY($leftXaxis, $leftYaxis);
		$this->MultiCell($maxX,5,"Comment");
		$leftYaxis += 5;
		$data = $con['detail'];
		$this->SetXY($leftXaxis, $leftYaxis);
		$this->SetFont('Times','',$size );
		$this->MultiCell($maxX,5, "$data", 0);
	}
	
	return $this->GetY();
}

function Show_Drug_History($appointmentID,$xAxis,$yAxis, $maxX , $size, $conentType , $hedearText){


	$contentData = getContentDetail($appointmentID, $conentType);

	if(mysql_num_rows($contentData) > 0){
		$this->SetFont('Times','B',$size + 1);
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
	
	$this->SetFont('Times','B',$size + 1);
	
	$resultData = getPrescribedInv($appointmentID);
	
	if(mysql_num_rows($resultData) > 0){
	
		$this->SetFont('Times','B',$size + 1);
		$this->SetXY($xAxis, $yAxis);
		$this->MultiCell($maxX,5,"Test Adviced");
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
		$this->SetFont('Times','B',$size + 1);
		$this->SetXY($xAxis, $yAxis);
		$this->MultiCell($maxX,5,"Diagnosis");
		$yAxis += 5;
		$this->SetFont('Times','',$size );
		$this->SetXY($xAxis, $yAxis);
		$diseaseName = $con['diseaseName'];
		$this->MultiCell($maxX, 5,"$diseaseName");
	}
	
	return $this->GetY();
	 
}



$pdf = new PDF();
$pdf->AddPage();
$pdf->AddFont('prolog','','prolog1.TTF',true);
$pdf->SetFont('Times', '', 14, '', 'false');
$pdf->SetFillColor(200,220,255);

$res = getAppointmentInfo($appointmentID);
$appData = mysql_fetch_assoc($res);
$appType = $appData['appointmentType'];
if($appType != 4){
	$patientImage = $pdf->ShowPatInfo($patientCode, 45, $username);
}
$linestyle = array('width' => 20, 'cap' => 'butt', 'join' => 'miter', 'dash' => '', 'phase' => 0, 'color' => array(255, 0, 0));
$pdf->Line(5, 50, 205, 50, $linestyle);
$pdf->Line(5, 40, 205, 40, $linestyle);

$leftYaxis = 52;
$rightYaxis = 55;
$size = 10;

$leftXaxis = 10;
$rightXaxis = 85;
$maxX = 65;
$maxXForRight = 110;

$gap = 7;
$photoSize = 15;



$rightYaxis = $pdf->Show_med($appointmentID,$rightXaxis, $rightYaxis,$size );
$rightYaxis = $pdf->Show_advice($appointmentID,$rightXaxis,$rightYaxis + 10,$size ,$maxXForRight);

$pdf-> show_nextVisit($appointmentID,$rightXaxis,$rightYaxis + 5 ,$size + 2);
$pdf-> show_ref_doc($appointmentID,$rightXaxis,$rightYaxis + 15 ,$size + 2);





if($appType != 4){
	
	if($patientImage != null){
		$pdf->displayImage($username, $patientImage,$leftXaxis,$leftYaxis,$photoSize);
		$gap = $gap + $photoSize;
	}
}

$leftYaxis=$pdf->Show_Complain($appointmentID,$leftXaxis,$leftYaxis + $gap, $maxX , $size);
$leftYaxis=$pdf->Show_vital($appointmentID,$leftXaxis,$leftYaxis +5, $maxX , $size);
$leftYaxis=$pdf->Show_History($appointmentID,$leftXaxis,$leftYaxis + 5, $maxX , $size, "RISK", "Risk Factors");

$leftYaxis=$pdf->Show_Past_History($appointmentID,$leftXaxis,$leftYaxis + 5, $maxX, $size , 0 , "Past Disease");
$leftYaxis=$pdf->Show_Past_History($appointmentID,$leftXaxis,$leftYaxis + 5, $maxX, $size , 1 , "Associated Illness");
$leftYaxis=$pdf->Show_Drug_History($appointmentID,$leftXaxis,$leftYaxis + 5, $maxX, $size , "OLDDRUGS" , "Old Drugs");
$leftYaxis=$pdf->Show_Drug_History($appointmentID,$leftXaxis,$leftYaxis + 5, $maxX, $size , "CURRDRUGS" , "Current Drugs");
$leftYaxis=$pdf->Show_Family_History($appointmentID,$leftXaxis,$leftYaxis + 3, $maxX, $size);
$leftYaxis=$pdf->Show_inv($appointmentID,$leftXaxis,$leftYaxis + 5, $maxX , $size);
$leftYaxis = $pdf->Show_diagnosis($appointmentID, $leftXaxis ,$leftYaxis + 5,$size , $maxX);

$leftYaxis=$pdf->showComment($appointmentID,$leftXaxis,$leftYaxis + 5, $maxX, $size);

if($leftYaxis > $rightYaxis){
	$rightYaxis = $leftYaxis;
}
$pdf->Line($rightXaxis - 10 , 50, $rightXaxis - 10, $rightYaxis, $linestyle);

//$pdf-> show_ref_doc($appointmentID,15,260,$size);
//$pdf->Line(5, 260, 205, 260, $linestyle);
$pdf->showDocInfo($username, 15, $size + 2);
$pdf->Output();

?>

