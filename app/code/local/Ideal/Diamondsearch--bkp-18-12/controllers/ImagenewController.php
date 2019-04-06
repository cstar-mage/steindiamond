<?php
class Ideal_Diamondsearch_ImagenewController extends Mage_Core_Controller_Front_Action
{
    public function indexAction()
    {
		
		try{
	    	$shp = $this->getRequest()->getParam("shp");				
			$diml = $this->getRequest()->getParam("diml");
			$dimw = $this->getRequest()->getParam("dimw");	
	    	$this->getResponse()->setHeader('Content-type', 'png');
	    	$string1=$dimw;
			$string2=$diml;
	    	//$surl = Mage::getBaseUrl(Mage_Core_Model_Store::URL_TYPE_SKIN)."frontend/base/default/dsearch/shapes/".strtolower($shp)."_out.jpg";
			$surl = Mage::getBaseDir()."/skin/frontend/base/default/dsearch/shapes/".strtolower($shp)."_out_new.jpg";
			
	    	echo $this->writeover($surl,$string1,$string2);			
				
		}
		catch (Exception $ex){ return false;}
	    exit;
    }
    

    function writeover($img,$txt1,$txt2)
    {
    	$txt1=$txt1."mm";
    	$txt2=$txt2."mm";
    	// Path to our ttf font file

    	$font_file = Mage::getBaseDir()."/skin/frontend/base/default/dsearch/css/fonts/Arialnb.ttf";
    	$im_base = imagecreatefromjpeg($img);
    
    	//initialize black sqare to write into
    	$im1 = imagecreate(45, 50);
    	$background_color = imagecolorallocate($im1, 237, 237, 237);
    	$text_color = imagecolorallocate($im1, 0, 0, 0);
        $text_color_width = imagecolorallocate($im1, 0, 0, 0); 
    	//another method to write text
    	//imagestring($im2, 5, 5, 5,  "width:", $text_color);
    
    	// Draw the text using font size 10
    	imagefttext($im1, 10, 0, 2, 10, $text_color_width,$font_file, 'Width');
    	imagefttext($im1, 10, 0, 2, 23, $text_color,$font_file, $txt1);
    
    	//initialize black square to write into
    	$im2 = imagecreate(60, 25);
    	$background_color = imagecolorallocate($im2, 237, 237, 237);
    	$text_color = imagecolorallocate($im2, 0, 0, 0);
		$text_color_width = imagecolorallocate($im2, 0, 0, 0);
    
    	// Draw the text using font size 10
    	imagefttext($im2, 10, 0, 2, 10, $text_color_width,$font_file, 'Length');
    	imagefttext($im2, 10, 0, 2, 23, $text_color,$font_file, $txt2); 
     
    	//copy each square over the baseimage 
    	imagecopy($im_base,$im1, 210,35, 0, 0, 60, 40);
    	imagecopy($im_base,$im2, 160,0, 0, 0, 55, 25);
    
    	//destroy image handlers
    	imagepng($im_base);
    	imagedestroy($im_base);
    	imagedestroy($im1);
    	imagedestroy($im2);
    
    	return $im_base;
    }
    
    
}