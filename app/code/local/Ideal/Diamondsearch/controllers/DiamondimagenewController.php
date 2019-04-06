<?php
class Ideal_Diamondsearch_DiamondimagenewController extends Mage_Core_Controller_Front_Action
{
    public function indexAction()
    {
		
		try{
	    	$shp = $this->getRequest()->getParam("shp");	 			
			$ddepth = $this->getRequest()->getParam("ddepth");
			$dtable = $this->getRequest()->getParam("dtable");	
			$dculet = $this->getRequest()->getParam("dculet");	
			$dgirdle = $this->getRequest()->getParam("dgirdle");
	    	$this->getResponse()->setHeader('Content-type', 'png');
	    	$string1=$dtable;
			$string2=$ddepth;
			$string3=$dculet;
			$string4=$dgirdle;			
			$surl = Mage::getBaseDir()."/skin/frontend/base/default/dsearch/shapes/diamond-common-out_new.jpg";			
	    	echo $this->writeover($surl,$string1,$string2,$string3,$string4);			
				
		}
		catch (Exception $ex){ return false;}
	    exit;
    }
    

    function writeover($img,$txt1,$txt2,$txt3,$txt4)
    {
    	$txt1=$txt1."%";
    	$txt2=$txt2."%";
		$txt3=$txt3;
		$txt4=$txt4;
    	// Path to our ttf font file

    	$font_file = Mage::getBaseDir()."/skin/frontend/base/default/dsearch/css/fonts/Arialnb.ttf";
    	$im_base = imagecreatefromjpeg($img);
    
    	//initialize black sqare to write into
    	$im1 = imagecreate(36, 25);
    	$background_color = imagecolorallocate($im1, 237, 237, 237);
    	$text_color = imagecolorallocate($im1, 0, 0, 0);
        $text_color_width = imagecolorallocate($im1, 0, 0, 0); 
    	//another method to write text
    	//imagestring($im2, 5, 5, 5,  "width:", $text_color);
    
    	// Draw the text using font size 10
    	imagefttext($im1, 10, 0, 2, 10, $text_color_width,$font_file, 'Depth');
    	imagefttext($im1, 10, 0, 2, 23, $text_color,$font_file, $txt1);
    
    	//initialize black square to write into
    	$im2 = imagecreate(36, 25);
    	$background_color = imagecolorallocate($im2, 237, 237, 237);
    	$text_color = imagecolorallocate($im2, 0, 0, 0);
		$text_color_width = imagecolorallocate($im2, 0, 0, 0);
    
    	// Draw the text using font size 10
    	imagefttext($im2, 10, 0, 2, 10, $text_color_width,$font_file, 'Table');
    	imagefttext($im2, 10, 0, 2, 23, $text_color,$font_file, $txt2); 
		
    	//initialize black square to write into
    	$im3 = imagecreate(36, 25);
    	$background_color = imagecolorallocate($im3, 237, 237, 237);
    	$text_color = imagecolorallocate($im3, 0, 0, 0);
		$text_color_width = imagecolorallocate($im3, 0, 0, 0);		
		
		imagefttext($im3, 10, 0, 2, 10, $text_color_width,$font_file, 'Culet');
    	imagefttext($im3, 10, 0, 2, 23, $text_color,$font_file, $txt3); 

    	//initialize black square to write into
    	$im4 = imagecreate(120, 25);
    	$background_color = imagecolorallocate($im4, 237, 237, 237);
    	$text_color = imagecolorallocate($im4, 0, 0, 0);
		$text_color_width = imagecolorallocate($im4, 0, 0, 0);		
		
		imagefttext($im4, 10, 0, 2, 10, $text_color_width,$font_file, 'Girdle');
    	imagefttext($im4, 7, 0, 2, 23, $text_color,$font_file, $txt4); 			
		
		
     
    	//copy each square over the baseimage 
    	imagecopy($im_base,$im1, 230,170, 0, 0, 36, 25);
    	imagecopy($im_base,$im2, 120,0, 0, 0, 36, 25);
		imagecopy($im_base,$im3, 125,250, 0, 0, 36, 25);
		imagecopy($im_base,$im4, 0,250, 0, 0, 120, 25);
    
    	//destroy image handlers
    	imagepng($im_base);
    	imagedestroy($im_base);
    	imagedestroy($im1);
    	imagedestroy($im2);
    
    	return $im_base;
    }
    
    
}