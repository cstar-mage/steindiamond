<?php
class Ideal_Bankwirediscount_Model_Bankwirediscount {
	function getFee(){
		return 3;
	}
	
	public static function canApply($address){
	
		$session  = Mage::getModel('checkout/session');
		
		$quote_id = $session->getQuoteId();
		
		$quote = Mage::getModel('sales/quote')->load($quote_id );
		$mcode = "";
		try
		{
			$mcode = $quote->getPayment()->getMethodInstance()->getCode();
		}
		catch(Exception $e)
		{
			;
		}
		
	   if($mcode == 'banktransfer'){
		return true;
		 }
		 else {return false;}
	
	}
}
