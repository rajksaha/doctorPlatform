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
function ShowPatInfo($user,$pid,$y){
   $b = mysql_fetch_array(mysql_query("select * from doc_info where d_id='$user'"));
        $sql = "SELECT sex,name,phone,dob from person where phone='$pid'";

$result=mysql_query($sql);
$row = mysql_fetch_array($result);
$name=$row['name'];
$age= $row['dob'];
$phone=$row['phone'];
$date = date('d M, Y');
	
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
function Show_med($pres_id,$x,$y,$size) {
$var=1;
$d_y=$y;
$y=$y+8;
$real_x=$x;
$this->SetFont('Times','',$size);
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
$this->SetFont('Times','',$size);
$this->MultiCell(50,5,"$types.$d_name$str",0); //Thats the line prints types +name+ str;
$this->SetFont('prolog','',$size);
$x_o=$this->GetX();
$check=explode(',', $drug_day);
$h_x=175;
$sc=$h_x-$x_o;
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
        $this->MultiCell($sc,5,"($drug_when)|");
    }else{
    $this->MultiCell($sc,5,"-($drug_when)|");
    }
    $y=$o_y+5;
    $var++;
}else if(sizeof($check)>1){
	$this->SetFont('prolog','',$size-3);
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
    $this->MultiCell($sc,5,"-$advice($drug_when)|");
$y=$this->GetY()+5;
$var++;
}else{
    if(1==1){
    	$x_o=80+50;
    	$this->SetXY($x_o,$y);
    	$h_x=205;
    	
        $this->MultiCell(20,5,"$dose");
		$x_o=80+50+20;
    	$this->SetXY($x_o,$y);
    	$h_x=208;
		$sc=$h_x-$x_o;
		$this->MultiCell($sc,5,"$drug_when, $advice - $drug_day |");
    }
//$x-=30;
$y=$this->GetY()+5;
$var++;
}
}
if($var!=1){
$this->SetFont('Times','',$size+2);
$this->SetXY($real_x,$d_y);
$this->Write(5,'Rx');
}else{
    $y-=8;
}
return $y;
}



}

$pdf = new PDF();
$pdf->AddPage();
$pdf->AddFont('prolog','','prolog1.TTF',true);
$pdf->SetFont('Times','',12);
$pdf->SetFillColor(200,220,255);
$pdf->ShowDocInfo($user);
//$pdf->ShowPatInfo($user,$pid,55);

$y=$pdf->Show_Complain($pres_id,10,80);
$y=$pdf->show_comment($pres_id,10,$y+10);
$y=$pdf->Show_vital($pres_id,10,$y+5);
$y=$pdf->Show_inv($pres_id,10,$y+5,100);
if($y>240){
    $pdf->Show_days($pres_id,1,$y+5);
}else{
    $pdf->Show_days($pres_id,1,240);
}

$y=$pdf->Show_Status($pres_id,160,80,$pid,$user);

$y=$pdf->Show_diagnosis($pres_id,60,80);
$y=$pdf->Show_fact($pres_id,60,$y+5,$pid,$user);
$y=$pdf->Show_boh(60,$y,$pid,$user);
$y=$pdf->Show_med($pres_id,60,$y+5,15);

$y=$pdf->Show_advice($pres_id,60,$y+10,130);
if($y>240){
    $pdf->Line(150,$y+8,180,$y+8);
$pdf->SetXY(155,$y+10);
$pdf->Write(5,'Signature');
}else{
    $pdf->Line(150,248,180,248);
$pdf->SetXY(155,250);
$pdf->Write(5,'Signature');
}

$pdf->Output();

?>

