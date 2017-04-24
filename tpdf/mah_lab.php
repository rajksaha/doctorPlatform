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

function ShowPatInfo($patientCode,$yAxis, $appointmentID){
	
	$resultData = getPatientInformaition($patientCode);
	
	$visitData = getPdfDetail($patientCode, "mah");
	
	$rec1 = mysql_fetch_assoc($visitData);
	
	$rec = mysql_fetch_assoc($resultData);
	
	$name = $rec['name'];
	
	$age = $rec['age'];
	
	$sex = $rec['sex'];
	
	$date = date('d M,y');
	
	$visit =  $rec1['visitNo'];
	
	$patientCode = $rec['patientCode'];
	
	
	$this->SetXY(15,$yAxis - 4);
	$this->Write(5, "Patient ID No: $patientCode");
	
	$this->SetXY(15,$yAxis - 8);
	$this->Write(5, "Visit No: $visit");
	
	$this->SetXY(15,$yAxis );
	$this->Write(5, "Name: $name");
	
	$this->SetXY(120, $yAxis);
	$this->Write(5, "Age: $age Years");
	
	$this->SetXY(160, $yAxis);
	$this->Write(5, "Date: $date");
	
	return $rec['patientImage'];
	
}
function showDocInfo($username, $yAxis, $size){

	$resultData = getDoctorInfo($username);
	
	

	if($resultData['prescriptionStyle'] == 2){

		$this->SetXY(15, $yAxis);
		$this->SetFont('prolog','',$size + 4);
		$contentData = getContentDetailForPres(3, "DOCTORPRINT", "LINE1");
		$con = mysql_fetch_assoc($contentData);
		$data = $con['code'];
		$this->MultiCell(100,5, $data, 0);

		$this->SetXY(130, $yAxis);
		$this->SetFont('Times','B',$size + 3);
		$this->MultiCell(100,5, "Prof. Md. Ali Hossain", 0);

		$yAxis = $yAxis + 5;
		$this->SetXY(15, $yAxis);
		$this->SetFont('prolog','',$size);
		$contentData = getContentDetailForPres(3, "DOCTORPRINT", "LINE2");
		$con = mysql_fetch_assoc($contentData);
		$data = $con['code'];
		$this->MultiCell(100,5, $data, 0);
		$this->SetXY(130, $yAxis);
		$this->SetFont('Times','',$size);
		$this->MultiCell(100,5, "MBBS FCPS(Med), MD(Chest)", 0);

		$yAxis = $yAxis + 5;
		$this->SetXY(15, $yAxis);
		$this->SetFont('prolog','',$size);
		$contentData = getContentDetailForPres(3, "DOCTORPRINT", "LINE3");
		$con = mysql_fetch_assoc($contentData);
		$data = $con['code'];
		$this->MultiCell(100,5, $data, 0);
		$this->SetXY(130, $yAxis);
		$this->SetFont('Times','',$size);
		$this->MultiCell(100,5, "Medicine Specialist and Pulmonologist", 0);

		$yAxis = $yAxis + 5;
		$this->SetXY(130, $yAxis);
		$this->SetFont('Times','',$size);
		$this->MultiCell(100,5, "BMDC Reg. No. A-15979", 0);

		$yAxis = $yAxis + 5;
		$linestyle = array('width' => 0.1, 'cap' => 'butt', 'join' => 'miter', 'dash' => '', 'phase' => 0, 'color' => array(255, 0, 0));
		$this->Line(5, $yAxis +2 , 205, $yAxis +2, $linestyle);

		$size =$size;
		$yAxis = $yAxis + 5;
		$this->SetXY(10, 235);
		$this->SetFont('prolog','',$size );
		$contentData = getContentDetailForPres(3, "DOCTORPRINT", "LINE4");
		$con = mysql_fetch_assoc($contentData);
		$data = $con['code'];
		$this->MultiCell(60,5, $data, 0);

		$yAxis = 245;
		$this->Line(5, $yAxis, 205, $yAxis, $linestyle);

		$yAxis = $yAxis + 2;
		$this->SetXY(10, $yAxis);
		$this->SetFont('Times','',$size - 1);
		$this->Write(5, "Chamber: Lab Aid, Dhanmondi, Road No-4, House No-1, Dhanmondi, Dhaka-1205, Phone: 58610793 Ex: 618");

		$yAxis = $yAxis + 6;
		$this->SetXY(20, $yAxis);
		$this->SetFont('prolog','',$size);
		$contentData = getContentDetailForPres(3, "DOCTORPRINT", "LINE5");
		$con = mysql_fetch_assoc($contentData);
		$data = $con['code'];
		$this->Write(5, $data);

		$yAxis = $yAxis + 6;
		$this->SetXY(20, $yAxis);
		$contentData = getContentDetailForPres(3, "DOCTORPRINT", "LINE6");
		$con = mysql_fetch_assoc($contentData);
		$data = $con['code'];
		$this->Write(5, $data);

		$yAxis = $yAxis + 6;
		$this->SetXY(20, $yAxis);
		$contentData = getContentDetailForPres(3, "DOCTORPRINT", "LINE7");
		$con = mysql_fetch_assoc($contentData);
		$data = $con['code'];
		$this->Write(5, $data);

		$yAxis = $yAxis + 6;
		$this->SetXY(20, $yAxis);
		$contentData = getContentDetailForPres(3, "DOCTORPRINT", "LINE8");
		$con = mysql_fetch_assoc($contentData);
		$data = $con['code'];
		$this->Write(5, $data);


	}

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
$rightYaxis = $pdf->Show_advice($appointmentID,$rightXaxis,$rightYaxis + 5,$size,$maxXForRight);

$pdf-> show_nextVisit($appointmentID,$rightXaxis,$rightYaxis + 5 ,$size +2);



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

if($leftYaxis > $rightYaxis){
	$rightYaxis = $leftYaxis;
}
$pdf->Line($rightXaxis - 10 , 65, $rightXaxis - 10, $rightYaxis, $linestyle);

//$pdf-> show_diagnosis($appointmentID,15,55,$size);
$pdf-> show_ref_doc($appointmentID,15,260,$size);
$pdf->showDocInfo($username, 15, $size + 2);
//$pdf->Line(15, 248, 195, 248, $linestyle);
$pdf->Output();

?>

