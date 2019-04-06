<?php

/**
 * @category   Studio4
 * @package    Studio4_InvoiceConfig
 * @author     Studio4 <info@studio4.lt>
 */

class Studio4_InvoiceConfig_Helper_Common extends Mage_Core_Helper_Abstract
{
	const MODULE_NAME = 'Studio4_InvoiceConfig';
	const MEDIA_SUBDIR = 's4-invoice';
	const SKIN_SUBDIR = 'adminhtml/default/default/studio4_invoiceconfig';
	
	public function getModulePath($type = '')
	{
		return Mage::getModuleDir($type, self::MODULE_NAME) . DS;
	}
	
	public function getAssetsPath()
	{
		return $this->getModulePath() . 'assets' . DS;
	}
	
	public function getFontsPath()
	{
		return $this->getAssetsPath() . 'fonts' . DS;
	}
	
	public function getFontPath($font = 'OpenSans', $variation = 'Regular')
	{
		return $this->getFontsPath() . $font . DS . $font . '-' . $variation . '.ttf';
	}
	
	public function getImagesPath()
	{
		return $this->getAssetsPath() . 'images' . DS;
	}
	
	public function getIconsPath()
	{
		return $this->getImagesPath() . 'icons' . DS;
	}
	
	public function getIconPath($icon, $ext = 'png')
	{
		return $this->getIconsPath() . $icon . '.' . $ext;
	}
	
	public function getPathToMedia($subPath = null)
	{
		if (is_null($subPath))
			return null;
		
		return Mage::getBaseDir('media') . DS. self::MEDIA_SUBDIR . DS . $subPath;
	}
	
	public function getStoreConfig($path, $store = null, $default = null)
	{
		$res = Mage::getStoreConfig($path,  $store);
		
		if (empty($res))
			return $default;
		
		return $res;
	}
	
	public function getSkinFileUrl($file)
	{
		return Mage::getBaseUrl(Mage_Core_Model_Store::URL_TYPE_SKIN) . self::SKIN_SUBDIR . DS . $file;
	}
	
	public function drawIconOnPage(&$page, $x, $y, $icon)
	{
		$image = $this->getIconPath($icon);
		$image = Zend_Pdf_Image::imageWithPath($image);
		$page -> drawImage($image, $x, $y - 2, $x + 10, $y - 2 + 10);
		
		return $page;
	}
	
	//skice text into chunks by max width in points
	public function sliceStringByPoints($string, $width, $font, $fontSize)
	{
		 $drawingString = '"libiconv"' == ICONV_IMPL ?
            iconv('UTF-8', 'UTF-16BE//IGNORE', $string) :
            @iconv('UTF-8', 'UTF-16BE', $string);
			
		//convert max width
		$width2 = ($width * $font->getUnitsPerEm()) / $fontSize;
		
		$res = array();

		$currLine = '';
		$currWidth = 0;
		
		for ($i = 0; $i < strlen($drawingString); $i++) {
			$ch = iconv_substr($string, $i / 2, 1, 'UTF-8');
            $char = (ord($drawingString[$i++]) << 8) | ord($drawingString[$i]);
			
			$w = $font->widthForGlyph($char);
			
			if ($currWidth + $w > $width2)
			{
				$res[] = $currLine;
				$currLine = '';
				$currWidth = 0;
			}
			
			$currLine .= $ch;
			$currWidth += $w;
        }
		
		if (iconv_strlen(trim($currLine), 'UTF-8') > 0)
			$res[] = $currLine;
		
		return $res;
	}
	
	//not in use
	/*public function getScaledImageHeight($image, $newWidth)
	{
		$w = $image->getPixelWidth() * 0.75;
		$h = $image->getPixelHeight() * 0.75;
		
		$ratio = $w / $newWidth;
		
		return $h / $ratio;
	}*/
}
