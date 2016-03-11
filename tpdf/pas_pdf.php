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
function   Show_vital($pid,$pres_id,$x,$y){
$result=(mysql_query("select * from vital where pres_id=$pres_id"));
$row=  mysql_fetch_array($result);
$result2=(mysql_query("select * from oe_extra where pres=$pres_id"));
$row2 = mysql_fetch_array($result2);
$d_y=$y;
$y+=5;
$this->SetY($y);
$this->SetFont('Times','',11);
          if($row!=""){
          $bp=$row['bp'];
          $height=$row['height'];
          $weight=$row['weight'];
          $temp=$row['temp'];
          $pulse=$row['pulse'];
          $bmi=$row['bmi'];
          $other=$row['other'];
          $hrp=$row['heart'];
          $sugar=$row['jaundice'];
          $hb=$row['liver'];
          $lunge=$row['lunge'];
          $ani=$row['anemia'];
          $ode=$row['oedema'];
          $sql = mysql_query("SELECT * from  patient where pid=$pid");
        	$lot = mysql_fetch_array($sql);
            $status=$lot['status'];
            if($status==0){
                $vital=array($weight,$bp,$pulse,$hrp,$temp,$lunge,$bmi,$ani,$ode,$sugar,$other);
                $vital_name=array("Weight",'BP',"H/L","P/A","HT OF UT","PRE","FM","Anaemia","Oedema","FHS","Other");
                $vital_ini=array("Kg","mm of hg","","","","","","","","","","");
                $extra=array("",$row2['data1'],$row2['data2'],$row2['data3']);
            }else{
                 $vital=array($weight,$height,$bp,$temp,$pulse,$bmi,$hrp,$lunge,$hb,$other);
          $vital_name=array("Weight","BP",'P/A',"Discharge","P/S-cx","P/S-Vagina","B/M-UT","B/M-OS","SSC","Other");
          $vital_ini=array("Kg","mm of hg",'',"","","","","","","");
          $extra=array("",$row2['data4'],$row2['data2'],$row2['data3'],$row2['data1']);
          }
          $var=0;
          $v=0;
          while($var<  sizeof($vital)){
            if($vital[$var]!=""){
                $y=  $this->GetY();
                $this->SetXY($x, $y);
                 $this->MultiCell(40,5,"$vital_name[$var]:$vital[$var] $vital_ini[$var]",1);
                    //$y+=4;
                $v++;
            }
            $var++;
          }
          for($i=1;$i<sizeof($extra);$i++){
              
                if($i%2!=0){
                        $data = (explode("`",$extra[$i]));
                        if($data[2]!=""){
                            $s_data=str_replace("`","-", $extra[$i]);
                            $y=  $this->GetY();
                            $this->SetXY($x, $y);
                            $this->MultiCell(40,5,"$s_data",1);
                            $y+=5;
                        }
                }else{
                    $data = (explode("`",$extra[$i]));
                        if($data[1]!=""){
                            $s_data=str_replace("`","-", $extra[$i]);
                            $y=  $this->GetY();
                            $this->SetXY($x, $y);
                            $this->MultiCell(40,5,"$s_data",1);
                             $y+=5;
                        }
                }
            }
          
          if($v!=0){
              $this->SetFont('Times','',12);
              $this->SetXY($x, $d_y);
              $this->Write(5,"O/E:");
          }else{
              $y-=5;
          }
            
      
          }else{
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
$pdf->ShowPatInfo($user,$pid,55);

$y=$pdf->Show_Complain($pres_id,1,70);
$y=$pdf->Show_vital($pid,$pres_id,1,$y+10);
$y=$pdf->show_comment($pres_id,1,$y+5);
if($y>240){
    $pdf->Show_days($pres_id,1,$y+5);
}else{
    $pdf->Show_days($pres_id,1,240);
}

$y=$pdf->Show_Status($pres_id,160,60,$pid,$user);

$y=$pdf->Show_diagnosis($pres_id,60,70);
$y=$pdf->Show_fact($pres_id,60,$y+5,$pid,$user);
$y=$pdf->Show_boh(60,$y,$pid,$user);
$y=$pdf->Show_med($pres_id,60,$y+5);
$y=$pdf->Show_inv($pres_id,60,$y,100);
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

