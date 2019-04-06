<?php
class Ideal_Bankwirediscount_Model_Sales_Order_Total_Creditmemo_Bankwirediscount extends Mage_Sales_Model_Order_Creditmemo_Total_Abstract
{
	public function collect(Mage_Sales_Model_Order_Creditmemo $creditmemo)
	{
		$order = $creditmemo->getOrder();
		$bankwirediscountAmountLeft = $order->getBankwirediscountInvoiced() - $order->getBankwirediscountRefunded();
		$basebankwirediscountAmountLeft = $order->getBaseBankwirediscountAmountInvoiced() - $order->getBaseBankwirediscountAmountRefunded();
		if ($baseBankwirediscountAmountLeft > 0) {
			$creditmemo->setGrandTotal($creditmemo->getGrandTotal() + $bankwirediscountAmountLeft);
			$creditmemo->setBaseGrandTotal($creditmemo->getBaseGrandTotal() + $basebankwirediscountAmountLeft);
			$creditmemo->setBankwirediscount($bankwirediscountAmountLeft);
			$creditmemo->setBaseBankwirediscountAmount($basebankwirediscountAmountLeft);
		}
		return $this;
	}
}
