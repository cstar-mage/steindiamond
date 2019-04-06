<?php 
class Ideal_Bankwirediscount_Model_Price_Observer extends Mage_Sales_Model_Quote_Address_Total_Abstract{ 
	 
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
 } */
	
	public function collect(Mage_Sales_Model_Quote_Address $address,Mage_Sales_Model_Quote $quote){
		$quoteid=$quote->getId();
		//check condition here if need to apply Discount
	    $discountAmount =(($quote->getSubtotal()*3)/100);
		$baseDiscount = -$discountAmount;
		$discount = Mage::app()->getStore()->convertPrice($baseDiscount);
	
		$address->setCustomDiscount($baseDiscount);
	
		$address->setBaseGrandTotal($address->getBaseGrandTotal() - $baseDiscount);
		$address->setGrandTotal($address->getGrandTotal() - $discount);
	
		return $this;
	}
	
	public function fetch(Mage_Sales_Model_Quote_Address $address){
		$this->setCode('aver');
		$amount = $address->getCustomDiscount();
		if ($amount != 0){
			$address->addTotal(array(
					'code'  => $this->getCode(),
					'title' => 'Custom Discount',
					'value' => $amount
			));
		}
		return $this;
	}
     
        
    }
