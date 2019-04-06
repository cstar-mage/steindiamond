<?php

/**
 * Sales Order Invoice PDF model
 *
 * @category   Studio4
 * @package    Studio4_InvoiceConfig
 * @author     Studio4 <info@studio4.lt>
 */
 
abstract class Studio4_InvoiceConfig_Model_Downloadable_Sales_Order_Pdf_Items_Abstract extends Mage_Downloadable_Model_Sales_Order_Pdf_Items_Abstract {

	public function getItemPricesForDisplay()
    {
        $prices = parent::getItemPricesforDisplay();
		
		if (is_null($prices))
		{
			$prices = array();
			$order  = $this->getOrder();
			$item   = $this->getItem();
			$data = array();
			$data['price'] =  $order->formatPriceTxt($item->getPrice());
			$data['subtotal'] = $order->formatPriceTxt($item->getRowTotal());
			$prices[] = $data;
		}
        return $prices;
    }
}
?>
