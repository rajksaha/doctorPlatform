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
		$drugAdviceID = $row['drugAdviceID'];
		
		
		$yAxis =  $this->GetY();
		
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
			
			$drugDoseInitial = str_replace("ampl","GÂ¤cj", $drugDoseInitial);
			$drugDoseInitial = str_replace("vial","fvqvj", $drugDoseInitial);
			$drugDoseInitial = str_replace("s"," Pv PvgP ", $drugDoseInitial);
			$drugDoseInitial = str_replace("puff","cvd", $drugDoseInitial);
			$drugDoseInitial = str_replace("d","WÂªc", $drugDoseInitial);
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
			
			$this->SetY($yAxis + 5);
		}
		
		
		
		
		
		
		//$yAxis += 8;
	}
	
	return $this->GetY();

}

function ShowPatInfo($patientCode,$yAxis,$username){
	
	$resultData = getPdfDetail($patientCode,$username);
	
	$rec = mysql_fetch_assoc($resultData);
	
	$name = $rec['name'];
	
	$age = $rec['age'];
	
	$sex = $rec['sex'];
	
	$visit = $rec['visitNo'];
	
	$patientCode = $rec['patientCode'];
	
	$patientCode = substr($patientCode, -5);
	
	$date = date('d M, Y');
	
	$this->SetFont('Times','', 13);
	
	$this->SetXY(100,$yAxis);
    $this->Write(5, "ID:$patientCode");
    
    //$this->SetXY(100,$yAxis-15);
    //$this->Write(5, "Visit: $visit");
	
	$this->SetXY(15,$yAxis);
	$this->Write(5, "Name :$name");
	
	$this->SetXY(130, $yAxis);
	$this->Write(5, "Age: $age yrs");
	
	$this->SetXY(160, $yAxis);
	$this->Write(5, "Date: $date");
	
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


function showDocInfo($username, $yAxis, $size){
	
	$resultData = getDoctorInfo($username);
	
	if($resultData['prescriptionStyle'] == 2){
		
		$this->SetXY(15, $yAxis);
		$this->SetFont('prolog','',$size + 2);
		$this->MultiCell(100,5, "Wvt †gvt AvwRRyj Kvnnvi", 0);
		
		$this->SetXY(150, $yAxis);
		$this->SetFont('Times','',$size);
		$this->MultiCell(100,5, "Dr. Md. Azizul Kahhar", 0);
		
		$yAxis = $yAxis + 5;
		$this->SetXY(15, $yAxis);
		$this->SetFont('prolog','',$size);
		$this->MultiCell(100,5, "GgAviwmwc(BD‡K), GdwmwcGm ", 0);
		$this->SetXY(150, $yAxis);
		$this->SetFont('Times','',$size);
		$this->MultiCell(100,5, "MRCP (UK), FCPS", 0);
		
		$yAxis = $yAxis + 5;
		$this->SetXY(15, $yAxis);
		$this->SetFont('prolog','',$size);
		$this->MultiCell(100,5, "†gwWwmb we‡klÁ ", 0);
		$this->SetXY(150, $yAxis);
		$this->SetFont('Times','',$size);
		$this->MultiCell(100,5, "Consultant Physician", 0);
		
		$yAxis = $yAxis + 5;
		$this->SetXY(15, $yAxis);
		$this->SetFont('prolog','',$size);
		$this->MultiCell(100,5, "†iwR‡÷ªkb t G-11488 ", 0);
		
		$yAxis = $yAxis + 5;
		$linestyle = array('width' => 0.1, 'cap' => 'butt', 'join' => 'miter', 'dash' => '', 'phase' => 0, 'color' => array(255, 0, 0));
		$this->Line(15, $yAxis, 195, $yAxis, $linestyle);
		
		
		$yAxis = 235;
		$this->Line(15, $yAxis, 195, $yAxis, $linestyle);
		
		$yAxis = $yAxis + 5;
		$this->SetXY(15, $yAxis);
		$this->SetFont('prolog','',$size + 2);
		$this->MultiCell(100,5, "†P¤^vit gWvb© WvqvMbmwUK †m›Uvi wjwg‡UW ", 0);
		
		$size = $size - 2;
		$yAxis = $yAxis + 5;
		$this->SetXY(15, $yAxis);
		$this->SetFont('prolog','',$size);
		$this->MultiCell(100,5, "moK bst 8, evwo bst 17, avbgwÊ AvevwmK GjvKv ", 0);
		
		$this->SetXY(160, $yAxis);
		$this->SetFont('prolog','',$size);
		$this->MultiCell(100,5, "†dvbt †ivMxi wmwiqvj †bqvi Rb¨ ", 0);
		
		
		
		$yAxis = $yAxis + 5;
		$this->SetXY(15, $yAxis);
		$this->SetFont('prolog','',$size);
		$this->MultiCell(100,5, "XvKv-1205| ", 0);
		
		$this->SetXY(160, $yAxis);
		$this->SetFont('prolog','',$size);
		$this->MultiCell(100,5, "†gvevBjt 01190668719 ", 0);
		
		$yAxis = $yAxis + 5;
		$this->SetXY(15, $yAxis);
		$this->SetFont('prolog','',$size);
		$this->MultiCell(100,5, "mv¶v‡Zi mgq t weKvj 5t00-ivZ 9t00 ", 0);
		
		$this->SetXY(80, $yAxis);
		$this->SetFont('prolog','',$size);
		$this->MultiCell(100,5, "me mgq †dv‡b wmwiqvj wb‡q Avm‡eb ", 0);
		
		$this->SetXY(171, $yAxis);
		$this->SetFont('prolog','',$size);
		$this->MultiCell(100,5, "01768764232 ", 0);
		
		
		
		$yAxis = $yAxis + 5;
		$this->SetXY(15, $yAxis);
		$this->SetFont('prolog','',$size);
		$this->MultiCell(100,5, "e„n¯cwZ I ïµevi eÜ| ", 0);
		
		$this->SetXY(80, $yAxis);
		$this->SetFont('prolog','',$size);
		$this->MultiCell(100,5, "Ri“wi cÖ‡qvR‡b wbKU¯’ nv¯cvZv‡ji Ri“wi wefv‡M hv‡eb ", 0);
		
		$this->SetXY(160, $yAxis);
		$this->SetFont('Times','',$size);
		$this->MultiCell(100,5, "Email: makahhar@gmail.com  ", 0);
		
		/* $yAxis = $yAxis + 2;
		$this->SetXY(10, $yAxis);
		$this->SetFont('Times','',$size);
		$this->Write(5, "Chamber: Lab Aid, Dhanmondi, Road No-4, House No-1, Dhanmondi, Dhaka-1205, Phone: 8610793 Ex: 618");
		
		$yAxis = $yAxis + 6;
		$this->SetXY(20, $yAxis);
		$this->SetFont('prolog','',$size);
		$this->Write(5, ".wet `Âªt cÃ–wZevi mvÂ¶vâ€¡Zi RbÂ¨ bvg â€ jLvâ€¡eb Ges eÂ¨eÂ¯â€™vcÃŽ mâ€¡Â½ Avbâ€¡eb|");
		
		$yAxis = $yAxis + 6;
		$this->SetXY(20, $yAxis);
		$this->Write(5, ".â€ ivMx â€ `Lvi mgqt weKvj 2t30Uv â€ _â€¡K 4t30Uv, mÃœÂ¨v 6Zv â€ _â€¡K ivZ 10Uv");
		
		$yAxis = $yAxis + 6;
		$this->SetXY(20, $yAxis);
		$this->Write(5, ".cÃ–wZw`â€¡bi wmwiqvj Avâ€¡Mi w`b mKvj 10Uv â€ _â€¡K â€ bIqv nq|");
		
		$yAxis = $yAxis + 6;
		$this->SetXY(20, $yAxis);
		$this->Write(5, ".wmwiqvâ€¡ji RbÂ¨ nUjvBb-10606, Ã¯Âµevi eÃœ|  "); */
		
		
	}

}




}

$pdf = new PDF();
$pdf->AddPage();
$pdf->AddFont('prolog','','prolog1.TTF',true);
$pdf->AddFont('akaash','','akaash.ttf',true);
$pdf->SetFont('Times','',10);
$pdf->SetFillColor(200,220,255);

$pdf->ShowPatInfo($patientCode,37,$username);
$linestyle = array('width' => 0.1, 'cap' => 'butt', 'join' => 'miter', 'dash' => '', 'phase' => 0, 'color' => array(255, 0, 0));
$pdf->Line(15, 44, 195, 44, $linestyle);

$leftYaxis = 46;
$rightYaxis = 46;
$size = 12;

$leftXaxis = 15;
$rightXaxis = 105;
$maxX = 60;
$maxXForRight = 100;

$rightYaxis = $pdf->Show_diagnosis($appointmentID,$rightXaxis,$rightYaxis,$size);

$rightYaxis = $pdf->Show_med($appointmentID,$rightXaxis, 56 + 2,$size);
$rightYaxis = $pdf->Show_advice($appointmentID,$rightXaxis,$rightYaxis + 5,$size,$maxXForRight);


$leftYaxis=$pdf->Show_Complain($appointmentID,$leftXaxis,$leftYaxis + 3, $maxX , $size);
$leftYaxis=$pdf->Show_vital($appointmentID,$leftXaxis,$leftYaxis +3, $maxX , $size);
$leftYaxis=$pdf->Show_inv($appointmentID,$leftXaxis,$leftYaxis + 3, $maxX , $size);
$leftYaxis=$pdf->Show_Past_History($appointmentID,$leftXaxis,$leftYaxis + 3, $maxX, $size);
$leftYaxis=$pdf->Show_Family_History($appointmentID,$leftXaxis,$leftYaxis + 3, $maxX, $size);

$pdf-> show_nextVisit($appointmentID,15,230,$size);

$pdf->showDocInfo($username, 15, 12);
$pdf-> show_ref_doc($appointmentID,60,225,$size);
$pdf->Output();

?>

