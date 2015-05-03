<?php
require('tfpdf.php');
include('../config.inc');
include('hrpCheck.php');
session_start();
$pid=$_SESSION['prev_pat'];
$pres_id=$_SESSION['prev_pres'];
$user=$_SESSION['username'];
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



function ShowPatInfo($user,$pid,$y,$ch_status,$pres_id){
     $b = mysql_fetch_array(mysql_query("select * from doc_info where d_id='$user'"));
    $doctor_id=$b['id'];
	$a = mysql_fetch_array(mysql_query("select * from doc_set where id='$doctor_id'"));
	$pref=$a['style'];
	$sql = "SELECT sex,name,phone,dob from person where phone='$pid'";
	
        $result=mysql_query($sql);
        $row = mysql_fetch_array($result);
        $name=$row['name'];
        $age= $row['dob'];
        $phone=$row['phone'];
        $date = date('d/m/Y');
		$this->SetFont('Times','',12);
        if($pref==0){
            if($ch_status==1){
			$sql = mysql_query("select * from indication where pres_id='$pres_id' and num=7 ");
			$in=mysql_fetch_array($sql);
			$a_date=$in['data'];
			$sql = mysql_query("select * from indication where pres_id='$pres_id' and num=6 ");
			$in=mysql_fetch_array($sql);
			$a_bed=$in['data'];
			$sql = mysql_query("select * from indication where pres_id='$pres_id' and num=5 ");
			$in=mysql_fetch_array($sql);
			$a_hrn=$in['data'];
            $this->SetXY(5,$y);
            $this->Write(5," Name: $name");
            $this->SetXY(5, $y+5);
            $this->Write(5, "Age: $age yrs");
            $this->SetXY(70, $y);
            $this->Write(5, "Admission Date: $a_date");
            $this->SetXY(70, $y+5);
            $this->Write(5, "Discharge Date: $date");
            $this->SetXY(135, $y);
            $this->Write(5, "ID: $phone");
            $this->SetXY(135, $y+5);
            $this->Write(5, "HRN,  Bed/Cabin: $a_hrn, $a_bed");
            }
            else{
            $this->SetXY(10,$y);
            $this->Write(5, "Name: $name");
             $x=  $this->GetX();
            $this->SetXY($x+10, $y);
            $this->Write(5, "Age: $age yrs");
            $x=  $this->GetX();
            $this->SetXY($x+10, $y);
            $this->Write(5, "Date: $date");
            $x=  $this->GetX();
            $this->SetXY(140, $y);
            $this->Write(5, "ID: $phone");
           
            }
        }
}
//changes made







//end


function Show_med($pres_id,$x,$y) {
$var=1;
$d_y=$y;
$y=$y+5;
$real_x=$x;
$this->SetFont('Times','',12);
$d = mysql_query("select * from prescription where pres_id='$pres_id'");
while($e=  mysql_fetch_array($d)){
$drug_id = $e['d_id'];
$his=mysql_query("select * from brand where id='$drug_id'");
$h=mysql_fetch_array($his);
$d_name=$h['b_name'];
$str=$h['str'];
$when_id = $e['when'];
$advice_id=$e['advice'];
 $res = mysql_query("SELECT * FROM drug_advice WHERE id='$advice_id'");
        $ro = mysql_fetch_array($res);
        $advice = $ro['pdf'];
$res = mysql_query("SELECT * FROM when_helper WHERE id='$when_id'");
        $ro = mysql_fetch_array($res);
        $drug_when = $ro['pdf'];
$drug_day = $e['nodays'];
$times = $e['ival'];
$dose = $e['dose'];
$type = $e['type'];
$ty=  mysql_query("select * from medicinetype where id=$type");
$typ=  mysql_fetch_array($ty);
$types=$typ['symbol'];

$phrase=getDose($dose,$times,$when_id,$advice_id);
if($type==4){
    $dose = str_replace("ampl","G¤cj", $phrase);
    $dose = str_replace("vial","fvqvj", $dose);
}else if($type==3 ||$type==9 || $type==15){
    $dose = str_replace("s","Pv", $phrase);
}else if($type==10 ||$type==14){
    $dose = str_replace("puff","cvd", $phrase);
}else if($type==7){
    $dose = str_replace("d","Wªc", $phrase);
}else{
    $dose=$phrase;
}
$drug_day=getPdf($drug_day);
$this->SetXY($real_x,$y);
$this->SetFont('Times','',12);
$this->Write(5,"$var. $types. $d_name $str");
$this->SetFont('prolog','',12);
$x_o=$this->GetX();
$check=explode(',', $drug_day);
if($times==9){
    $temp=  explode(')', $dose);
    $i=0;
    $o_y=$y;
    while($i<sizeof($temp)-1){
        $this->SetXY($x_o+2,$o_y);
        $this->Write(5,"$temp[$i])");
        $o_y+=5;
        $i++;
    }
    $x_o=$this->GetX();
    $this->SetXY($x_o+2,$y);
    if($advice==""){
        $this->Write(5,"($drug_when)|");
    }else{
    $this->Write(5," -$advice($drug_when)");
    }
    $y=$o_y;
    $var++;
}else if(sizeof($check)>1){
	$this->SetFont('prolog','',11);
	$x_o=$this->GetX();
	$this->SetXY($x_o+2,$y);
    $this->Write(5,"[$dose]");
    $i=0;
    $o_y=$y;
    $x_o=$this->GetX();
    while($i<sizeof($check)-1){
    	$this->SetXY($x_o+2,$o_y);
        $this->Write(5,"($check[$i])");
        $o_y+=5;
        $i++;
    }
    $x_o=$this->GetX();
    $this->SetXY($x_o+2,$y);
    $h_x=205;
    	$sc=$h_x-$x_o;
    $this->MultiCell($sc,5,"-$advice($drug_when)|");
$y=$this->GetY()+5;
$var++;
}else{
    if($advice==""){
    	$x_o=$this->GetX();
    	$this->SetXY($x_o+2,$y);
    	$h_x=205;
    	$sc=$h_x-$x_o;
        $this->MultiCell($sc,5," $dose  $drug_when - $drug_day|");
    }else{
    	
    	$x_o=$this->GetX();
    	$h_x=205;
    	$sc=$h_x-$x_o;
    	$this->SetXY($x_o+2,$y);
		$this->MultiCell($sc, 5, " $dose  $drug_when $advice $drug_day");
    }
//$x-=30;
$y=$this->GetY()+2;
$var++;
}
}
if($var!=1){
$this->SetFont('Times','',12);
$this->SetXY($real_x,$d_y);
$this->Write(5,'Rx');
}else{
    $y-=5;
}
return $y;
}
function show_comment($pres_id,$x,$y){
    $row=  mysql_fetch_array(mysql_query("select * from comment where pres_id=$pres_id"));
    $c_fact=$row['c_fact'];
    $history=$row['history'];
    //$family=$row['family'];
    $this->SetFont('Times','',12);
    $this->SetXY($x, $y);
    $c_fact =  trim($c_fact);
    if($history!=""){
     $this->Write(5, "Surgery History:");
      $this->SetXY($x, $y+5);
     //$c_fact = str_replace(",","\n", trim($c_fact));
		$this->SetXY($x, $y+5);
      $history = str_replace(",","\n", trim($history));
    $this->MultiCell(65,5,"$history",0);
     return $y;
}
return $y;
}
}
$pdf = new PDF();
$pdf->AddPage();
$pdf->AddFont('prolog','','prolog1.TTF',true);
$pdf->SetFont('Times','',10);
$pdf->SetFillColor(200,220,255);
$pdf->ShowDocInfo($user);

$check_status= mysql_query("select status from patient where pid = $pid") ;
$ch_status=mysql_fetch_array($check_status);
$ch_status =$ch_status["status"];


if($ch_status == 1){
$pdf->SetFont('Times','',15);
$pdf->SetXY(50,30);
$pdf->Write(5,'Department of ENT - Head & Neck Surgery');
$pdf->SetXY(65,35);
$pdf->Write(5,'Discharge Certificate');
$pdf->SetFont('Times','',10);
$pdf->SetXY(5,45);
$pdf->Write(5,'Consultant:');
$pdf->SetXY(5,50);
$pdf->SetFont('Times','',13);
$pdf->Write(5,'Prof. Dr. M. Alamgir Chowdhury');
$pdf->SetXY(5,55);
$pdf->SetFont('Times','',10);
$pdf->Write(5,'MBBS, DLO, MS, FICS, Gold Medalist Bangladesh & USA');
$pdf->SetXY(5,60);
$pdf->Write(5,'Professor & Head, ENT - Head & Neck Surgery');
$pdf->ShowPatInfo($user,$pid,75,$ch_status,$pres_id);
    $y=$pdf->Show_OppNote($pres_id,5,95);
    $y=$pdf->Show_Indication($pres_id,5,115);
    $y= 125;
    $y=$pdf->Show_diagnosis($pres_id,5,$y);
$y=$pdf->Show_vital($pres_id,5,$y+10);
$y=$pdf->show_comment($pres_id,5,$y+10);
$y=$pdf->Show_inv($pres_id,5,$y+10,65);
$y=$pdf->Show_surgery($pres_id,5,$y+10,65);
$y=$pdf->Show_days($pres_id,5,$y+10);
$y=$pdf->show_ref_doc($pres_id,5,$y);

$y=$pdf->Show_med($pres_id,70,125);
$y=$pdf->Show_advice($pres_id,70,$y+10,100);

}else{
$pdf->ShowPatInfo($user,$pid,75,$ch_status,$pres_id);
    $y=90;
    $y=$pdf->Show_diagnosis($pres_id, 5,$y+5);
    $y=$pdf->Show_Complain($pres_id,5,$y+5);

$y=$pdf->Show_vital($pres_id,5,$y+10);
$y=$pdf->show_comment($pres_id,5,$y+10);

$y=$pdf->Show_inv($pres_id,5,$y+10,65);

$y=$pdf->Show_surgery($pres_id,5,$y+10,65);

$y=$pdf->Show_days($pres_id,5,240);
$y=$pdf->show_ref_doc($pres_id,5,$y);

$y=$pdf->Show_med($pres_id,70,90);
$y=$pdf->Show_advice($pres_id,70,$y+10,100);

}








$pdf->SetFont('Times','',12);
$pdf->SetXY(155,252);
if($ch_status == 1){
//doc info
$pdf->SetXY(140,240);
$pdf->Write(5,'Dr. Afroza Suraya Majumder');
$pdf->SetXY(156,247);
$pdf->Write(0,'Registrar');
$pdf->SetXY(140,250);
$pdf->Write(5,'ENT Head & Neck Surgery');
$pdf->SetXY(140,262);
$pdf->Write(5,'Composed by: Md. Fazlul Karim');

//mdical officer
$pdf->SetXY(5,240);
$pdf->Write(5,'Medical Officer');
$pdf->SetXY(5,245);
$pdf->Write(5,'Name');
$pdf->SetXY(5,255);
$pdf->Write(5,'Date:');
}
else{
    $pdf->Line(150,250,180,250);
    $pdf->Write(5,'Signature');
}

$pdf->Output();

?>

