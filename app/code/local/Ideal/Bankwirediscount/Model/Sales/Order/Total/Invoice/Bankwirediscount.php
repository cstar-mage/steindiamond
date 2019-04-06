<?php
class Ideal_Bankwirediscount_Model_Sales_Order_Total_Invoice_Bankwirediscount extends Mage_Sales_Model_Order_Invoice_Total_Abstract
{
	public function collect(Mage_Sales_Model_Order_Invoice $invoice)
	{
		$order = $invoice->getOrder();
		$bankwirediscountAmountLeft = $order->getBankwirediscount() - $order->getBankwirediscountInvoiced();
		$baseBankwirediscountLeft = $order->getBaseBankwirediscountAmount() - $order->getBaseBankwirediscountAmountInvoiced();
		if (abs($baseBankwirediscountLeft) < $invoice->getBaseGrandTotal()) {
			$invoice->setGrandTotal($invoice->getGrandTotal() - $bankwirediscountAmountLeft);
			$invoice->setBaseGrandTotal($invoice->getBaseGrandTotal() - $baseBankwirediscountLeft);
		} else {
			$bankwirediscountAmountLeft = $invoice->getGrandTotal() * -1;
			$baseBankwirediscountLeft = $invoice->getBaseGrandTotal() * -1;

			$invoice->setGrandTotal(0);
			$invoice->setBaseGrandTotal(0);
		}
			
		$invoice->setBankwirediscount($bankwirediscountAmountLeft);
		$invoice->setBaseBankwirediscountAmount($baseBankwirediscountLeft);
		return $this;
	}
}
