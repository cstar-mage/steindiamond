<?php
class Ideal_Bankwirediscount_Model_Sales_Quote_Address_Total_Bankwirediscount extends Mage_Sales_Model_Quote_Address_Total_Abstract{
    protected $_code = 'bankwirediscount';
 
    public function collect(Mage_Sales_Model_Quote_Address $address)
    {
        parent::collect($address);
        $this->_setAmount(0); 
        $this->_setBaseAmount(0);
        $items = $this->_getAddressItems($address);
       
        if (!count($items)) {
        return $this;  
        }
        $session  = Mage::getModel('checkout/session');
        $quote_id = $session->getQuoteId();
        $quote = Mage::getModel('sales/quote')->load($quote_id );
        
        $subotal = $quote->getSubtotal();
         $quotes = $address->getQuote();
          if(Ideal_Bankwirediscount_Model_Bankwirediscount::canApply($address)){ //your business logic
            $exist_amount = $quotes->getBankwirediscount(); 
            $fee = Ideal_Bankwirediscount_Model_Bankwirediscount::getFee(); 
           // $balance = $fee - $exist_amount;
            $balance =  (($subotal*3)/100);
            $address->setBankwirediscount($balance);
            $address->setBaseBankwirediscountAmount($balance);
           
            $quote->setBankwirediscount($balance);
            $quote->setBaseBankwirediscountAmount($balance);
            $quotes->setBankwirediscount($balance); 
           
            $address->setGrandTotal($address->getGrandTotal() - $address->getBankwirediscount());
            $address->setBaseGrandTotal($address->getBaseGrandTotal() - $address->getBaseBankwirediscountAmount());
            $quote->save();
            $address->save();
            
        }
        else{
        	
        	$balance =  0;
        	$address->setBankwirediscount($balance);
        	$address->setBaseBankwirediscountAmount($balance);
        	$quote->setBankwirediscount($balance);
        	$quote->setBaseBankwirediscountAmount($balance);
        	$address->setGrandTotal($address->getGrandTotal() - $address->getBankwirediscount());
        	$address->setBaseGrandTotal($address->getBaseGrandTotal() - $address->getBaseBankwirediscountAmount());
        	$quote->save();
        	$address->save();
        }
    }
    public function fetch(Mage_Sales_Model_Quote_Address $address) 
    {
		/* if(!Mage::getStoreConfig('bankwirediscount/general/enabled')) {
			return parent::fetch($address);
		} */
		
    	if(Ideal_Bankwirediscount_Model_Bankwirediscount::canApply($address)){ 
        $amt = $address->getBankwirediscount();
        $address->addTotal(array(
                'code'=>$this->getCode(),
                'title'=>'Bankwire Discount',
                'value'=> $amt
        ));
        return $this;
    	}
    }
 
}
