<?php    
    $PNG_TEMP_DIR = dirname(__FILE__).DIRECTORY_SEPARATOR.'temp'.DIRECTORY_SEPARATOR;
    
    $PNG_WEB_DIR = 'temp/';
    $start=$_REQUEST['data'];
	$end=$_REQUEST['end_data'];
    $x=substr($_REQUEST['data'], 2);

    include "qrlib.php";    
    
    if (!file_exists($PNG_TEMP_DIR))
        mkdir($PNG_TEMP_DIR);
    
    
    $filename = $PNG_TEMP_DIR.'test.png';
    
    $errorCorrectionLevel = 'L';
    
    $matrixPointSize = 3;


    if (isset($_REQUEST['data'])) { 
    
            
        $filename =$PNG_TEMP_DIR.$_REQUEST['data'].'.png';
        $year="KQ";
    	
    	
    	while(true)
        {
        	$filename=$PNG_TEMP_DIR.$year.$x.'.png';
        	QRcode::png($year.$x, $filename, $errorCorrectionLevel, $matrixPointSize, 2);    
        	if(!strcmp($year.$x,$_REQUEST['end_data']))
        		break;
        	$x+=1;
        }
	}

    else {    
        
        echo "No data";
    }    

    //Generating pdf


define('FPDF_FONTPATH','font/');
require('fpdf.php');

class PDF extends FPDF
{
var $B;
var $I;
var $U;
var $HREF;

function PDF($orientation='P',$unit='mm',$format='A3')
{
    //Call parent constructor
    $this->FPDF($orientation,$unit,$format);
    //Initialization
    $this->B=0;
    $this->I=0;
    $this->U=0;
    $this->HREF='';
}

}

$pdf=new PDF();
$pdf->AddPage();
$year="KQ";
$x=substr($_REQUEST['data'], 2);
$x1=10;$y1=10;
$filename="h";
$count=0;

while(true)
{
	$count+=1;
	for($i=1;$i<=10;$i++)
        {
        	$filename=$year.$x;
        	$pdf->Image('temp/'.$filename.'.png',$x1,$y1);
        	$x1+=25;
        }
       	if(!strcmp($filename,$_REQUEST['end_data']))
      		break;        
       $x+=1;
       $y1+=25;
       $x1=10;
       if($count==16)
       {
        //$pdf->Write(5,'Congratulations! You have generated a PDF. ');
       		$pdf->AddPage();
       		$count=0;
			$x1=10;$y1=10;
	   }
} 
$pdf->Output();
?> 
