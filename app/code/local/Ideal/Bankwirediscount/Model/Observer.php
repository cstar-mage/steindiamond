<?php
class DIdeal_Bankwirediscount_Model_Observer {
	public function invoiceSaveAfter(Varien_Event_Observer $observer)
	{
		$invoice = $observer->getEvent()->getInvoice();
		if ($invoice->getBaseBankwirediscountTotal()) {
			$order = $invoice->getOrder();
			$order->setBankwirediscountTotalInvoiced($order->getBankwirediscountTotalInvoiced() + $invoice->getBankwirediscountTotal());
			$order->setBaseBankwirediscountTotalInvoiced($order->getBaseBankwirediscountTotalInvoiced() + $invoice->getBaseBankwirediscountTotal());
		}
		return $this;
	}
	public function creditmemoSaveAfter(Varien_Event_Observer $observer)
	{
		/* @var $creditmemo Mage_Sales_Model_Order_Creditmemo */
		$creditmemo = $observer->getEvent()->getCreditmemo();
		if ($creditmemo->getBankwirediscountTotal()) {
			$order = $creditmemo->getOrder();
			$order->setBankwirediscountTotalRefunded($order->getBankwirediscountTotalRefunded() + $creditmemo->getBankwirediscountTotal());
			$order->setBaseBankwirediscountTotalRefunded($order->getBaseBankwirediscountTotalRefunded() + $creditmemo->getBaseBankwirediscountTotal());
		}
		return $this;
	}
	public function updatePaypalTotal($evt){
		$cart = $evt->getPaypalCart();
		$cart->updateTotal(Mage_Paypal_Model_Cart::TOTAL_SUBTOTAL,$cart->getSalesEntity()->getBankwirediscountTotal());
	}
}
