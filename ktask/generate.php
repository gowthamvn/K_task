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

function WriteHTML($html)
{
    //HTML parser
    $html=str_replace("\n",' ',$html);
    $a=preg_split('/<(.*)>/U',$html,-1,PREG_SPLIT_DELIM_CAPTURE);
    foreach($a as $i=>$e)
    {
        if($i%2==0)
        {
            //Text
            if($this->HREF)
                $this->PutLink($this->HREF,$e);
            else
                $this->Write(5,$e);
        }
        else
        {
            //Tag
            if($e{0}=='/')
                $this->CloseTag(strtoupper(substr($e,1)));
            else
            {
                //Extract attributes
                $a2=explode(' ',$e);
                $tag=strtoupper(array_shift($a2));
                $attr=array();
                foreach($a2 as $v)
                    if(ereg('^([^=]*)=["\']?([^"\']*)["\']?$',$v,$a3))
                        $attr[strtoupper($a3[1])]=$a3[2];
                $this->OpenTag($tag,$attr);
            }
        }
    }
}

function OpenTag($tag,$attr)
{
    //Opening tag
    if($tag=='B' or $tag=='I' or $tag=='U')
        $this->SetStyle($tag,true);
    if($tag=='A')
        $this->HREF=$attr['HREF'];
    if($tag=='BR')
        $this->Ln(5);
}

function CloseTag($tag)
{
    //Closing tag
    if($tag=='B' or $tag=='I' or $tag=='U')
        $this->SetStyle($tag,false);
    if($tag=='A')
        $this->HREF='';
}

function SetStyle($tag,$enable)
{
    //Modify style and select corresponding font
    $this->$tag+=($enable ? 1 : -1);
    $style='';
    foreach(array('B','I','U') as $s)
        if($this->$s>0)
            $style.=$s;
    $this->SetFont('',$style);
}

function PutLink($URL,$txt)
{
    //Put a hyperlink
    $this->SetTextColor(0,0,255);
    $this->SetStyle('U',true);
    $this->Write(5,$txt,$URL);
    $this->SetStyle('U',false);
    $this->SetTextColor(0);
}
}


$html='You can now easily print text mixing different
styles : <B>bold</B>, <I>italic</I>, <U>underlined</U>, or
<B><I><U>all at once</U></I></B>!<BR>You can also insert links
on text, such as <A HREF="http://www.fpdf.org">www.fpdf.org</A>,
or on an image: click on the logo.';

$pdf=new PDF();
//First page
$pdf->AddPage();
    $pdf->SetFont('Times','',10);
    
$year="KQ";
$x=substr($_REQUEST['data'], 2);
$tname=$x;
$x1=10;$y1=10;
$filename="h";
$count=0;
$tx=10;$ty=10;
while(true)
{
    $count+=1;
    $tx=$x1;
    $ty=$y1+20;
    for($i=1;$i<=10;$i++)
        {
            $filename=$year.$x;
            $pdf->Image('temp/'.$filename.'.png',$x1,$y1);
            $x1+=25;
        }
        for($i=1;$i<=10;$i++)
        {
            
            $pdf->SetXY($tx, $ty);
            $pdf->Write(2,$filename);        
            $tx+=25;
        }
        
        
        if(!strcmp($filename,$_REQUEST['end_data']))
            break;        
       $x+=1;
       $y1+=24;
       $x1=10;
       if($count==16)
       {
            $pdf->AddPage();
            $count=0;
            $x1=10;$y1=10;
       }
} 
$pdf->Output($start.'-'.$end.'.pdf','D');
?> 
