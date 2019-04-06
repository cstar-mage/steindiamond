<?php
class Ideal_Diamondsearch_Block_Diamondsearch extends Mage_Core_Block_Template
{
	public function _prepareLayout()
    {
		return parent::_prepareLayout();
    }
    
     public function getDiamondsearch()     
     { 
        if (!$this->hasData('diamondsearch')) {
            $this->setData('diamondsearch', Mage::registry('diamondsearch'));
        }
        return $this->getData('diamondsearch');
        
    }
	
    public function getImage($productimage)
    {
		$url=Mage::getBaseDir('media');
		if (!file_exists($url.'/dsearch/shape')) 	{    mkdir($url.'dsearch/shape', 0777, true);}
		$url=$url.'/dsearch/shape/'.$this->clean(basename($productimage));
		
		//file_put_contents($url, file_get_contents($productimage));
		
		//Using CURL, instead file_get_contents
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $productimage);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false); // ignore SSL verifying
		curl_setopt($ch, CURLOPT_HEADER, 0);
		$response = curl_exec($ch);
		curl_close($ch);

		file_put_contents($url, $response);
		
		$resizeImage=Mage::helper('diamondsearch')->resizeImage($this->clean(basename($productimage)),270,270, 'dsearch/shape');	
		return $resizeImage;
	}

	//added this function for URLs like http://stuller.scene7.com/is/image/Stuller/22bf53e9-f85b-4f0c-9d5d-a60a009946d9?bgc=000000&.jpg
	//without extensions
	function clean($string) {
	   $string = str_replace(' ', '-', $string); // Replaces all spaces with hyphens.
	   $string = preg_replace('/[^A-Za-z0-9.\-]/', '', $string); // Removes special chars.
	
	   return preg_replace('/-+/', '-', $string); // Replaces multiple hyphens with single one.
	}
	
}
