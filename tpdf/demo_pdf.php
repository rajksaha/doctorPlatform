
<?php
require('tfpdf.php');
include('../config.inc');
include('hrpCheck.php');
session_start();
$pid=$_SESSION['prev_pat'];
$pres_id=$_SESSION['prev_pres'];
$user=$_SESSION['username'];

class PDF extends tFPDF{
   
function get_Head()
{
    $this->Image('head.jpg',5,5,200);
}

function get_Foot()
{
    //$this->SetY(-15);
   $this->Image('foot.jpg',5,260,200);
}
function ShowPatInfo($user,$pid,$y){
   $b = mysql_fetch_array(mysql_query("select * from doc_info where d_id='$user'"));
    $doctor_id=$b['id'];
	$a = mysql_fetch_array(mysql_query("select * from doc_set where id='$doctor_id'"));
	$pref=$a['style'];
        $sql = "SELECT sex,name,phone,dob from person where phone='$pid'";
$result=mysql_query($sql);
$row = mysql_fetch_array($result);
$name=$row['name'];
$age= $row['dob'];
$date = date('d M, Y');
	
        if($pref==1){
            $this->SetXY(32,48);
            $this->Write(5,"$name");
            $this->SetXY(136, 48);
            $this->Write(5, "$age Years");
            $this->SetXY(175, 48);
            $this->Write(5, "$date");
        }else{
            $this->SetXY(15,$y);
            $this->Write(5, "Name:$name");
             $x=  $this->GetX();
            $this->SetXY($x+10, $y);
            $this->Write(5, "Age:$age yrs");
            $x=  $this->GetX();
            $this->SetXY(140, $y);
            $this->Write(5, "Date:$date");
        }
}
function Show_Complain($pres_id,$x,$y) {
$u=1;
$d_y=$y;
$this->SetY($y+5);
$this->SetX($x);
$this->SetFont('Times','',11);
          $result=mysql_query("select * from complain where pres_id='$pres_id'");        
                    while($row=mysql_fetch_array($result)){
              $sym_id=$row['sym_id'];
              $duration=$row['duration'];
               // $con=$row['prime'];
              $test=(mysql_query("select * from sym where sym_id=$sym_id"));
              while($toe=$row=mysql_fetch_array($test)){
                  $testy=$toe['name'];
                  if($duration==""){
                      $y=  $this->GetY();
                      $this->SetXY($x, $y);
                      $this->MultiCell(45,5,". $testy ",0);

                  }else{
                      $y=  $this->GetY();
                     $this->SetXY($x, $y);
                      $this->MultiCell(45,5,". $testy : $duration",0);

                  }
              $u++;
              }
          }
          if($u!=1){
              $this->SetFont('Times','',16);
            $this->SetXY($x, $d_y);
            $this->Write(5,"C.C:");
          }else{
              $y-=5;
          }
          return $y;
    
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
$this->MultiCell(60,5,".$types $d_name $str",0); //Thats the line prints types +name+ str;
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
    	$x_o=80+60;
    	$this->SetXY($x_o+2,$y);
    	$h_x=205;
    	
        $this->MultiCell(20,5,"$dose");
		$x_o=80+60+20;
    	$this->SetXY($x_o+2,$y);
    	$h_x=205;
		$sc=$h_x-$x_o;
		$this->MultiCell($sc,5,"$drug_day,$drug_when|");
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
function Show_advice($pres_id,$x,$y,$high) {
    $d_y=$y;

$y+=5;
$this->SetFont('Times','',12);
$a=1;
$this->SetY($y);
$this->SetX($x);
$ad = mysql_query("select * from pres_advice where pres_id='$pres_id'");
while($row=  mysql_fetch_array($ad)){
$advice=$row['advice_id'];
$ad1 = mysql_query("select * from advice_tab where id=$advice");
$name= mysql_fetch_array($ad1);
$nam=$name['advice'];
$lang=$name['lang'];
if($lang==0){
$this->SetFont('Times','',14);
$ad1 = mysql_query("select * from advice_tab where id=$advice");
$name= mysql_fetch_array($ad1);
$nam=$name['advice'];
$y=  $this->GetY();
$this->SetXY($x,$y+2);
$this->MultiCell($high,5,".$nam",0);
$a++;
}else if($lang==2){
                $y= $this->GetY();
		$this->SetXY($x,$y);
            $ad1 = mysql_query("select * from advice_tab where id=$advice");
                    $name= mysql_fetch_array($ad1);
                    $this->SetFont('prolog','',12);
                    $nam=$name['pdf'];
                    $date=$row['date'];
                    $date=changeFormet($date);
                    $this->MultiCell($high,5,".$date-$nam",0);
                    $a++;
}else{
                    $ad1 = mysql_query("select * from advice_tab where id=$advice");
                    $name= mysql_fetch_array($ad1);
                    $this->SetFont('prolog','',14);
                    $nam=$name['pdf'];
                     $y= $this->GetY();
                    $this->SetXY($x,$y);
                    if($advice==90){
                        $this->Write(5,".$nam-");
                        $this->SetFont('Times','',12);
                        $x_o=$this->GetX();
                        $this->SetXY($x_o,$y);
                        $this->Write(5,"FSH,LH,E2");
                    }else{
                    $this->MultiCell($high,5,".$nam",0);
                    }
                
		
		$a++;
	}
        $y=$this->GetY();
}
if($a!=1){
$this->SetFont('Times','',14);
$this->SetXY($x, $d_y);
$this->Write(5,"Advice:");
}else{
    $y-=5;
}
return $y;
}
function Show_inv($pres_id,$x,$y,$high) {
    $v=1;
    $d_y=$y;
    $this->SetY($y+5);
    $this->SetX($x);
$this->SetFont('Times','',12);
                    $f = mysql_query("select * from prescription_test where pres_id='$pres_id'");
                    while($g= mysql_fetch_array($f)){
            $test_id = $g['test_id'];
            $note=$g['note'];
            $i=mysql_query("select * from test where id='$test_id'");
            $h=mysql_fetch_array($i);
                $test_name = $h['name'];
                if($note!=""){
                    $y=  $this->GetY();
                    $this->SetXY($x, $y);
                    $this->MultiCell($high,5,".$test_name-$note",0);
                     //$y+=5;
                 $v++;
                }else{
                    $y=  $this->GetY();
                   $this->SetXY($x, $y);
                   $n=trim($test_name);
                    $this->MultiCell($high,5,".$n",0);
                     //$y+=5;
                 $v++; 
                }
}
if($v!=1){
    $this->SetFont('Times','',16);
$this->SetXY($x, $d_y);
$this->write(5,"Inv:");
}else{
    $y-=5;
}
return $y;
}
}

$pdf = new PDF();
$pdf->AddPage();
$pdf->AddFont('prolog','','prolog1.TTF',true);
$pdf->SetFont('Times','',14);
$pdf->SetFillColor(200,220,255);
$pdf->ShowDocInfo($user);
$pdf->ShowPatInfo($user,$pid,52);
$y=$pdf->Show_Complain($pres_id,5,75);
$y=$pdf->Show_vital($pres_id,5,$y+10);
$y=$pdf->Show_inv($pres_id,5,$y+10,70);

$y=$pdf->Show_med($pres_id,80,75,15);
$pdf->Show_advice($pres_id,80,$y+10,120);
$y=$pdf->Show_days($pres_id,10,240);
$y=$pdf->show_ref_doc($pres_id,10,$y);
$pdf->SetFont('Times','',12);
$pdf->Line(150,250,180,250);
$pdf->SetXY(155,252);
$pdf->Write(5,'Signature');
$pdf->Output();

?>
