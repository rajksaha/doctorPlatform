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

	if(mysql_num_rows($resultData) > 35){
		$size = $size - 2;
	}
	
	if(mysql_num_rows($resultData) > 0){
		
		$this->SetFont('Times','BU',$size + 2);
		$this->SetXY($xAxis , $yAxis);
		$this->MultiCell(20,5,"RX");
		$yAxis += 6;
		
	}if(mysql_num_rows($resultData) == 0){
		return $yAxis - 5;
	}
	
	$nameCell = 50;
	$doseeCell = 35;
	$durationCell = 70;
	$whenCell = 15;
	
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
		
		
		$yAxis =  $this->GetY() + 2;
		
		
		$this->SetXY($xAxis, $yAxis);
		if($drugStr == ''){
			$this->Write(5," $drugType. $drugName - ");
		}else{
			$this->Write(5," $drugType. $drugName-$drugStr - ");	
		}
		
		$xInnerAxis = $this->GetX();
		
		$this->SetFont('prolog','',$size + 2);
		
		
		
		$this->SetXY($xInnerAxis, $yAxis);
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
				
			
			if($drugNoDay == 0){
				$drugNoDay = "";
			}
			
			$restOftheString = "$drugWhen $drugAdvice - $drugNoDay $drugNoDayType";
			if($drugDoseInitial == ""){
					
				$this->MultiCell(90,5,"($drugDose) $restOftheString|");
			}else if($drugDose == ''){
				
				$this->MultiCell(90,5,"($drugDose) $restOftheString|");
			}else{
				$this->MultiCell(90,5,"($drugDose)$drugDoseInitial $restOftheString|");
			}
		}else{
			$realY =  $yAxis;
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
				$this->SetXY($xInnerAxis, $yAxis);
		
				if($drugDoseInitial == ""){
		
					$this->MultiCell($doseeCell,5,"($drugDose)");
				}else{
					$this->MultiCell($doseeCell,5,"($drugDose) $drugDoseInitial");
				}
		
				$xInnerAxis = $xInnerAxis + $doseeCell + 5;
				$this->SetXY($xInnerAxis, $yAxis);
				$this->MultiCell(15,5," $drugNoDay $drugNoDayType");
		
				$xInnerAxis = $xInnerAxis - $doseeCell - 5;
			}
				
			$restOftheString = "$drugWhen $drugAdvice";
			$xInnerAxis = $xInnerAxis + $doseeCell + 18;
			$this->SetXY($xInnerAxis, $realY);
			$this->MultiCell($durationCell,10,"$restOftheString |");
			
			$this->SetY($yAxis + 5);
		}
		//$yAxis += 8;
	}
	
	return $this->GetY();

}

function ShowPatInfo($patientCode,$yAxis, $appointmentID){
	
	$resultData = getPatientInformaition($patientCode);
	
	$visitData = getPdfDetail($patientCode, "aua");
	
	$rec1 = mysql_fetch_assoc($visitData);
	
	$rec = mysql_fetch_assoc($resultData);
	
	$name = $rec['name'];
	
	$age = $rec['age'];
	
	$sex = $rec['sex'];
	
	$date = date('d-m-Y');
	
	$visit =  $rec1['visitNo'];
	
	$phone =  $rec1['phone'];
	
	$address =  $rec1['address'];
	
	$patientCode = $rec['patientCode'];
	
	
	$this->SetXY(85,$yAxis + 4);
	$this->Write(5, "ID No: $patientCode");
	
	$this->SetXY(160,$yAxis + 12);
	$this->Write(5, "Visit No: $visit");
	
	$this->SetXY(10,$yAxis + 4);
	$this->Write(5, "Name: $name");
	
	$this->SetXY(130, $yAxis + 4);
	$this->Write(5, "Age: $age Yrs");
	
		
	$this->SetXY(160, $yAxis + 4);
	$this->Write(5, "Date: $date");
	
		
	return $rec['patientImage'];
	
}
function showDocInfo($username, $yAxis, $size){

	$resultData = getDoctorInfo($username);
	
	

	if($resultData['prescriptionStyle'] == 2){

		$this->SetXY(10, $yAxis);
		$this->SetFont('prolog','',$size + 5);
		$contentData = getContentDetailForPres(20, "DOCTORPRINT", "LINE18");
		$con = mysql_fetch_assoc($contentData);
		$data = $con['code'];
		$this->MultiCell(100,5, $data, 0);
		$this->SetXY(130, $yAxis);
		$this->SetFont('Times','B',$size + 3);
		$this->MultiCell(100,5, "Dr. Ashraf Uddin Ahammed", 0);

		$yAxis = $yAxis + 5;
		$this->SetXY(10, $yAxis);
		$this->SetFont('prolog','',$size);
		$contentData = getContentDetailForPres(20, "DOCTORPRINT", "LINE19");
		$con = mysql_fetch_assoc($contentData);
		$data = $con['code'];
		$this->MultiCell(100,5, $data, 0);
		$this->SetXY(130, $yAxis);
		$this->SetFont('Times','',$size);
		$this->MultiCell(100,5, "MBBS, DCP (Australia), CCD (BIRDEM)", 0);

		$yAxis = $yAxis + 5;
		$this->SetXY(10, $yAxis);
		$this->SetFont('prolog','',$size);
		$contentData = getContentDetailForPres(20, "DOCTORPRINT", "LINE20");
		$con = mysql_fetch_assoc($contentData);
		$data = $con['code'];
		$this->MultiCell(100,5, $data, 0);
		$this->SetXY(130, $yAxis);
		$this->SetFont('Times','',$size);
		$this->MultiCell(100,5, "FRSH (London), FCGP (Family Medicine)", 0);
		
		$yAxis = $yAxis + 5;
		$this->SetXY(10, $yAxis);
		$this->SetFont('prolog','',$size);
		$contentData = getContentDetailForPres(20, "DOCTORPRINT", "LINE21");
		$con = mysql_fetch_assoc($contentData);
		$data = $con['code'];
		$this->MultiCell(100,5, $data, 0);
		$this->SetXY(130, $yAxis);
		$this->SetFont('Times','',$size);
		$this->MultiCell(100,5, "D. Asthma (UK), CCCD (Heart Foundation)", 0);
		
		$yAxis = $yAxis + 5;
		$this->SetXY(10, $yAxis);
		$this->SetFont('prolog','',$size + 2);
		$contentData = getContentDetailForPres(20, "DOCTORPRINT", "LINE22");
		$con = mysql_fetch_assoc($contentData);
		$data = $con['code'];
		$this->MultiCell(100,5, $data, 0);
		$this->SetXY(130, $yAxis);
		$this->SetFont('Times','B',$size );
		$this->MultiCell(100,5, "Consultant Physician & Diabetologist", 0);
		
		$yAxis = $yAxis + 5;
		$this->SetXY(10, $yAxis);
		$this->SetFont('prolog','',$size);
		$contentData = getContentDetailForPres(20, "DOCTORPRINT", "LINE23");
		$con = mysql_fetch_assoc($contentData);
		$data = $con['code'];
		$this->MultiCell(100,5, $data, 0);
		$this->SetXY(110, $yAxis);
		$this->SetFont('Times','',$size - 2);
		$this->MultiCell(100,5, "(Medicine, Diabetes, Hypertension, Asthma, Paediatric, Skin & VD)", 0);
		
		

		$yAxis = $yAxis + 5;
		$this->SetXY(130, $yAxis);
		$this->SetFont('Times','',$size);
		//$this->MultiCell(100,5, "BMDC Reg. No. A-15979", 0);

		$yAxis = $yAxis + 5;
		$linestyle = array('width' => 0.1, 'cap' => 'butt', 'join' => 'miter', 'dash' => '', 'phase' => 0, 'color' => array(255, 0, 0));
		//$this->Line(5, $yAxis +2 , 205, $yAxis +2, $linestyle);

		$size =$size;
		$yAxis = $yAxis + 5;
		$this->SetXY(10, 235);
		$this->SetFont('prolog','',$size );
		$contentData = getContentDetailForPres(20, "DOCTORPRINT", "LINE2");
		$con = mysql_fetch_assoc($contentData);
		$data = $con['code'];
		$this->MultiCell(60,5, $data, 0);

		$yAxis = 250;
		$this->Line(5, $yAxis, 205, $yAxis, $linestyle);

		

		$yAxis = $yAxis ;
		$this->SetXY(75, $yAxis);
		$this->SetFont('prolog','',$size + 3);
		$contentData = getContentDetailForPres(20, "DOCTORPRINT", "LINE24");
		$con = mysql_fetch_assoc($contentData);
		$data = $con['code'];
		$this->Write(5, $data);

		$yAxis = $yAxis + 6;
		$this->SetXY(25, $yAxis);
		$contentData = getContentDetailForPres(20, "DOCTORPRINT", "LINE25");
		$con = mysql_fetch_assoc($contentData);
		$data = $con['code'];
		$this->Write(5, $data);

		$yAxis = $yAxis + 6;
		$this->SetXY(35, $yAxis);
		$contentData = getContentDetailForPres(20, "DOCTORPRINT", "LINE26");
		$con = mysql_fetch_assoc($contentData);
		$data = $con['code'];
		$this->Write(5, $data);

		$yAxis = $yAxis + 6;
		$this->SetXY(40, $yAxis);
		$contentData = getContentDetailForPres(20, "DOCTORPRINT", "LINE27");
		$con = mysql_fetch_assoc($contentData);
		$data = $con['code'];
		$this->Write(5, $data);


	}

}

}

$pdf = new PDF();
$pdf->AddPage();
$pdf->AddFont('prolog','','prolog1.TTF',true);
$pdf->SetFont('Times', 'BI', 13, '', 'false');
$pdf->SetFillColor(200,220,255);

$res = getAppointmentInfo($appointmentID);
$appData = mysql_fetch_assoc($res);
$appType = $appData['appointmentType'];
if($appType != 4){
	$patientImage = $pdf->ShowPatInfo($patientCode, 45, $username);
}
$linestyle = array('width' => 20, 'cap' => 'butt', 'join' => 'miter', 'dash' => '', 'phase' => 0, 'color' => array(255, 0, 0));
$pdf->Line(5, 55, 205, 55, $linestyle);
$pdf->Line(5, 48, 205, 48, $linestyle);

$leftYaxis = 60;
$rightYaxis = 60;
$size = 10;

$leftXaxis = 15;
$rightXaxis = 70;
$maxX = 50;
$maxXForRight = 120;

$gap = 5;
$photoSize = 15;



$rightYaxis = $pdf->Show_med($appointmentID,$rightXaxis, $rightYaxis,$size );
$rightYaxis = $pdf->Show_advice($appointmentID,$rightXaxis + 5,$rightYaxis + 5,$size + 2,$maxXForRight);

$rightYaxis = $pdf-> show_nextVisit($appointmentID,$rightXaxis + 5,$rightYaxis + 5 ,$size + 3);
$rightYaxis = $pdf-> show_ref_doc($appointmentID,$rightXaxis + 5,$rightYaxis + 5 ,$size + 2);

//$pdf->Line($rightXaxis - 5 , 50, $rightXaxis - 5, $rightYaxis + 25, $linestyle);


if($appType != 4){
	
	if($patientImage != null){
		$pdf->displayImage($username, $patientImage,$leftXaxis + 6,$leftYaxis,$photoSize);
		$gap = $gap + $photoSize;
	}
}

$leftYaxis=$pdf->Show_Complain($appointmentID,$leftXaxis,$leftYaxis + $gap, $maxX , $size);
$leftYaxis=$pdf->Show_vital($appointmentID,$leftXaxis,$leftYaxis + 5, $maxX , $size);

$leftYaxis=$pdf->Show_Past_History($appointmentID,$leftXaxis,$leftYaxis + 5, $maxX, $size , 0 , "Past Disease");
$leftYaxis=$pdf->Show_Past_History($appointmentID,$leftXaxis,$leftYaxis + 5, $maxX, $size , 1 , "Associated Illness");
$leftYaxis=$pdf->Show_Drug_History($appointmentID,$leftXaxis,$leftYaxis + 5, $maxX, $size , "OLDDRUGS" , "Old Drugs");
$leftYaxis=$pdf->Show_Drug_History($appointmentID,$leftXaxis,$leftYaxis + 5, $maxX, $size , "CURRDRUGS" , "Current Drugs");
$leftYaxis=$pdf->Show_Family_History($appointmentID,$leftXaxis,$leftYaxis + 5, $maxX, $size);
$leftYaxis=$pdf->Show_inv($appointmentID,$leftXaxis,$leftYaxis + 5, $maxX , $size);
$leftYaxis = $pdf->Show_diagnosis($appointmentID, $leftXaxis ,$leftYaxis + 5,$size , $maxX);

$leftYaxis=$pdf->showComment($appointmentID,$leftXaxis,$leftYaxis + 5, $maxX, $size);
$leftYaxis = $pdf->Show_History($appointmentID,$leftXaxis,$leftYaxis + 5, $maxX , $size, "MH", "Referance");

if($leftYaxis > $rightYaxis){
	$rightYaxis = $leftYaxis;
}
$pdf->Line($rightXaxis - 5 , 55, $rightXaxis - 5, $rightYaxis, $linestyle);
//$pdf->Line(5, $rightYaxis , 205, $rightYaxis , $linestyle);

//$pdf-> show_ref_doc($appointmentID,15,260,$size);
//$pdf->Line(5, 268, 205, 268, $linestyle);
$pdf->showDocInfo($username, 15, $size + 2);
$pdf->Output();

?>

