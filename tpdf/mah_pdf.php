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
		
		$drugDose = str_replace(".","/", $drugDose);
		
		if($drugDoseInitial == ""){
			
			$this->MultiCell(100,5,"$drugDose $restOftheString |");
		}else{
			$this->MultiCell(100,5,"($drugDose) $drugDoseInitial $restOftheString |");
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
    
    $this->SetXY(100,$yAxis-15);
    $this->Write(5, "Visit: $visit");
	
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
		$this->MultiCell(60,5, "$numOfday - $dayType ci Avevi Avm‡eb|", 0);
	}else if($nextVisitType == 1){
		$date = $rec['date'];
		$newDate = date("d-m-Y", strtotime($date));
		$this->SetFont('prolog','',$size + 2);
		$this->MultiCell(60,5, "$newDate Zvwi‡L †`Lv Ki‡eb|", 0);
	}
	
	
	
}

function showDocInfo($username, $yAxis, $size){
	
	$resultData = getDoctorInfo($username);
	
	if($resultData['prescriptionStyle'] == 2){
		
		$this->SetXY(15, $yAxis);
		$this->SetFont('prolog','',$size + 2);
		$this->MultiCell(100,5, "Aa¨vcK †gvt Avjx †nv‡mb ", 0);
		
		$this->SetXY(130, $yAxis);
		$this->SetFont('Times','',$size);
		$this->MultiCell(100,5, "Prof. Md. Ali Hossain", 0);
		
		$yAxis = $yAxis + 5;
		$this->SetXY(15, $yAxis);
		$this->SetFont('prolog','',$size);
		$this->MultiCell(100,5, "GgweweGm, Gdwmwc Gm (‡gwWwmb), GgwW(‡P÷) ", 0);
		$this->SetXY(130, $yAxis);
		$this->SetFont('Times','',$size);
		$this->MultiCell(100,5, "MBBS FCPS(Med), MD(Chest)", 0);
		
		$yAxis = $yAxis + 5;
		$this->SetXY(15, $yAxis);
		$this->SetFont('prolog','',$size);
		$this->MultiCell(100,5, "†gwWwmb we‡klÁ I cvjg‡bvjwR‡P÷ ", 0);
		$this->SetXY(130, $yAxis);
		$this->SetFont('Times','',$size);
		$this->MultiCell(100,5, "Medicine Specialist and Pulmonologist", 0);
		
		$yAxis = $yAxis + 5;
		$this->SetXY(130, $yAxis);
		$this->SetFont('Times','',$size);
		$this->MultiCell(100,5, "BMDC Reg. No. A-15979", 0);
		
		$yAxis = $yAxis + 5;
		$linestyle = array('width' => 0.1, 'cap' => 'butt', 'join' => 'miter', 'dash' => '', 'phase' => 0, 'color' => array(255, 0, 0));
		$this->Line(15, $yAxis, 195, $yAxis, $linestyle);
		
		$yAxis = $yAxis + 5;
		$this->SetXY(15, 220);
		$this->SetFont('prolog','',$size);
		$this->MultiCell(40,5, "Jl‡ai bvg cwieZ©b A_ev GKB Jla Ab¨ bv‡g cÖ`vb MÖnY‡hvM¨ bq|", 0);
		
		$yAxis = 235;
		$this->Line(15, $yAxis, 195, $yAxis, $linestyle);
		
		$yAxis = $yAxis + 2;
		$this->SetXY(10, $yAxis);
		$this->SetFont('Times','',$size);
		$this->Write(5, "Chamber: Lab Aid, Dhanmondi, Road No-4, House No-1, Dhanmondi, Dhaka-1205, Phone: 8610793 Ex: 618");
		
		$yAxis = $yAxis + 6;
		$this->SetXY(20, $yAxis);
		$this->SetFont('prolog','',$size);
		$this->Write(5, ".wet `ªt cÖwZevi mv¶v‡Zi Rb¨ bvg †jLv‡eb Ges e¨e¯’vcÎ m‡½ Avb‡eb|");
		
		$yAxis = $yAxis + 6;
		$this->SetXY(20, $yAxis);
		$this->Write(5, ".†ivMx †`Lvi mgqt weKvj 2t30Uv †_‡K 4t30Uv, mÜ¨v 6Zv †_‡K ivZ 10Uv");
		
		$yAxis = $yAxis + 6;
		$this->SetXY(20, $yAxis);
		$this->Write(5, ".cÖwZw`‡bi wmwiqvj Av‡Mi w`b mKvj 10Uv †_‡K †bIqv nq|");
		
		$yAxis = $yAxis + 6;
		$this->SetXY(20, $yAxis);
		$this->Write(5, ".wmwiqv‡ji Rb¨ nUjvBb-10606, ïµevi eÜ|  ");
		
		
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
$size = 10;

$leftXaxis = 15;
$rightXaxis = 105;
$maxX = 60;
$maxXForRight = 100;

$rightYaxis = $pdf->Show_diagnosis($appointmentID,$rightXaxis,$rightYaxis,$size);

$rightYaxis = $pdf->Show_med($appointmentID,$rightXaxis,$rightYaxis + 2,$size+3);
$rightYaxis = $pdf->Show_advice($appointmentID,$rightXaxis,$rightYaxis + 5,$size,$maxXForRight);


$leftYaxis=$pdf->Show_Complain($appointmentID,$leftXaxis,$leftYaxis + 3, $maxX , $size);
$leftYaxis=$pdf->Show_vital($appointmentID,$leftXaxis,$leftYaxis +3, $maxX , $size);
$leftYaxis=$pdf->Show_inv($appointmentID,$leftXaxis,$leftYaxis + 3, $maxX , $size);
$leftYaxis=$pdf->Show_Past_History($appointmentID,$leftXaxis,$leftYaxis + 3, $maxX, $size);
$leftYaxis=$pdf->Show_Family_History($appointmentID,$leftXaxis,$leftYaxis + 3, $maxX, $size);

$pdf-> show_nextVisit($appointmentID,80,230,$size);

$pdf->showDocInfo($username, 15, $size + 2);
$pdf-> show_ref_doc($appointmentID,60,220,$size);
$pdf->Output();

?>

