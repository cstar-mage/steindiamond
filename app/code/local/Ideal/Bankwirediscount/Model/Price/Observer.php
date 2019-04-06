<?php 
class Ideal_Bankwirediscount_Model_Price_Observer extends Mage_Sales_Model_Quote_Address_Total_Abstract{
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
	
		$quote = $address->getQuote();
	
		if(Ideal_Bankwirediscount_Model_Bankwirediscount::canApply($address)){ //your business logic
			$exist_amount = $quote->getBankwirediscount();
			$fee = Ideal_Bankwirediscount_Model_Bankwirediscount::getFee();
			$balance = $fee - $exist_amount;
			$balance =  (($quote->getSubtotal()*3)/100);
			$address->setBankwirediscount($balance);
			$address->setBaseBankwirediscountAmount($balance);
			$quote->setBankwirediscount($balance);
			$address->setGrandTotal($address->getGrandTotal() - $address->getBankwirediscount());
			$address->setBaseGrandTotal($address->getBaseGrandTotal() - $address->getBaseBankwirediscountAmount());
		}
	}
	public function fetch(Mage_Sales_Model_Quote_Address $address)
	{
		$amt = $address->getBankwirediscount();
		$address->addTotal(array(
				'code'=>$this->getCode(),
				'title'=>'Bankwire Discount',
				'value'=> $amt
		));
		return $this;
	}
	
	public function collectTotalsBankwirediscount($observer) {
		
		/* $quote = Mage::getModel('checkout/session')->getQuote();
		$quote->setTotalsCollectedFlag(true);
		$quote->getShippingAddress()->unsetData('cached_items_all');
		$quote->getShippingAddress()->unsetData('cached_items_nominal');
		$quote->getShippingAddress()->unsetData('cached_items_nonnominal');
		$quote->collectTotals();
		$quote->save(); */
		//Mage::getModel('checkout/session')->getQuote()->setTotalsCollectedFlag(true)->collectTotals()->save();
		return $this;
	}
	
}
	 
 /*  public function setDiscount($observer)
    {
       $quote=$observer->getEvent()->getQuote();
       $quoteid=$quote->getId();
    //check condition here if need to apply Discount
        if($disocuntApply) $discountAmount =(($quote->getSubtotal()*3)/100);

     if($quoteid) {
               if($discountAmount>0) {
           $total=$quote->getBaseSubtotal();
           $quote->setSubtotal(0);
           $quote->setBaseSubtotal(0);

           $quote->setSubtotalWithDiscount(0);
           $quote->setBaseSubtotalWithDiscount(0);

           $quote->setGrandTotal(0);
           $quote->setBaseGrandTotal(0);


           $canAddItems = $quote->isVirtual()? ('billing') : ('shipping'); 
           foreach ($quote->getAllAddresses() as $address) {

                    $address->setSubtotal(0);
                    $address->setBaseSubtotal(0);

                    $address->setGrandTotal(0);
                    $address->setBaseGrandTotal(0);

                    $address->collectTotals();

                    $quote->setSubtotal((float) $quote->getSubtotal() + $address->getSubtotal());
                    $quote->setBaseSubtotal((float) $quote->getBaseSubtotal() + $address->getBaseSubtotal());

                    $quote->setSubtotalWithDiscount(
                        (float) $quote->getSubtotalWithDiscount() + $address->getSubtotalWithDiscount()
                    );
                    $quote->setBaseSubtotalWithDiscount(
                        (float) $quote->getBaseSubtotalWithDiscount() + $address->getBaseSubtotalWithDiscount()
                    );

                    $quote->setGrandTotal((float) $quote->getGrandTotal() + $address->getGrandTotal());
                    $quote->setBaseGrandTotal((float) $quote->getBaseGrandTotal() + $address->getBaseGrandTotal());

           $quote ->save(); 

              $quote->setGrandTotal($quote->getBaseSubtotal()-$discountAmount)
              ->setBaseGrandTotal($quote->getBaseSubtotal()-$discountAmount)
              ->setSubtotalWithDiscount($quote->getBaseSubtotal()-$discountAmount)
              ->setBaseSubtotalWithDiscount($quote->getBaseSubtotal()-$discountAmount)
              ->save(); 


            if($address->getAddressType()==$canAddItems) {

             $address->setSubtotalWithDiscount((float) $address->getSubtotalWithDiscount()-$discountAmount);
             $address->setGrandTotal((float) $address->getGrandTotal()-$discountAmount);
             $address->setBaseSubtotalWithDiscount((float) $address->getBaseSubtotalWithDiscount()-$discountAmount);
             $address->setBaseGrandTotal((float) $address->getBaseGrandTotal()-$discountAmount);
             if($address->getDiscountDescription()){
             $address->setDiscountAmount(-($address->getDiscountAmount()-$discountAmount));
             $address->setDiscountDescription($address->getDiscountDescription().', Amount Waived');
             $address->setBaseDiscountAmount(-($address->getBaseDiscountAmount()-$discountAmount));
             }else {
             $address->setDiscountAmount(-($discountAmount));
             $address->setDiscountDescription('Amount Waived');
             $address->setBaseDiscountAmount(-($discountAmount));
             }
             $address->save();
            }//end: if
           } //end: foreach
           //echo $quote->getGrandTotal();

          foreach($quote->getAllItems() as $item){
                         //We apply discount amount based on the ratio between the GrandTotal and the RowTotal
                         $rat=$item->getPriceInclTax()/$total;
                         $ratdisc=$discountAmount*$rat;
                         $item->setDiscountAmount(($item->getDiscountAmount()+$ratdisc) * $item->getQty());
                         $item->setBaseDiscountAmount(($item->getBaseDiscountAmount()+$ratdisc) * $item->getQty())->save();

                       }


                    }

            }
 } 
	
	public function collect(Mage_Sales_Model_Quote_Address $address)
	{
		parent::collect($address);
		$this->_setAmount(0);
		$this->_setBaseAmount(0);
		$items = $this->_getAddressItems($address);
		if (!count($items)) {
			return $this; //this makes only address type shipping to come through
		}
		$quote = $address->getQuote();
		if(Ideal_Bankwirediscount_Model_Bankwirediscount::canApply($address)){ //your business logic
			$exist_amount = $quote->getBankwirediscount();
			$fee = Ideal_Bankwirediscount_Model_Bankwirediscount::getFee();
			$balance = $fee - $exist_amount;
			$address->setBankwirediscount($balance);
			$address->setBaseBankwirediscountAmount($balance);
			 
			$quote->setBankwirediscount($balance);
	
			$address->setGrandTotal($address->getGrandTotal() - $address->getBankwirediscount());
			$address->setBaseGrandTotal($address->getBaseGrandTotal() - $address->getBaseBankwirediscountAmount());
		}
	}
	public function fetch(Mage_Sales_Model_Quote_Address $address)
	{
		$amt = $address->getBankwirediscount();
		$address->addTotal(array(
				'code'=>$this->getCode(),
				'title'=>Mage::helper('catalog')->__( 'Bankwire Discount'),
				'value'=> $amt
		));
		return $this;
	}
	
    }*/
