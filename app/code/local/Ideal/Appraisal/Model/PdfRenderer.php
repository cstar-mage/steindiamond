<?php

class Ideal_Appraisal_Model_PdfRenderer
{
    public function renderPdf($renderData)
    {
        $counter = Mage::getModel('IdealAppraisal/Counter')->load(0, 'id');

        $order = Mage::getModel('sales/order')->load($renderData['order_id']);

        $items = $order->getAllItems();

        $productMediaConfig = Mage::getModel('catalog/product_media_config');

        if (!empty($_FILES['sign']['name'] )) {
            $signPath = Mage::getBaseDir('var') . basename($_FILES['sign']['name']);
            move_uploaded_file($_FILES['sign']['tmp_name'], $signPath);
        }

        $result = '';

        foreach ($items as $item) {
            $product = Mage::getModel('catalog/product')->load($item->getProductId());

            $appraisalNum = $counter->getCounter();
            $counter->setCounter($appraisalNum + 1)->setId(0)->save();

            $result .= '<page backtop="20mm" backbottom="10mm" backleft="20mm" backright="20mm">';

            $result .= '<table style="width: 100%">'
                . '<tr><td style="width: 410px"></td><td style="text-align: right; font-size: 7mm">Stein Diamonds</td></tr>'
                . '<tr><td style="width: 410px"></td><td style="text-align: right; font-size: 5mm"><span style="font-weight: bold">Date:</span> <span>' . date("m-d-y") . '</span></td></tr>'
                . '<tr><td style="width: 410px"></td><td style="text-align: right; font-size: 5mm"><span style="font-weight: bold">Appraisal:</span> <span>' . $appraisalNum . '</span></td></tr>'
                . '</table>';

            $result .= '<table style="margin-top: 25px;">';

            $result .= '<tr><td style="text-align: left; font-size: 5mm"><span style="font-weight: bold">Name:</span> <span>' . ucfirst($order->getCustomerFirstname()) . ' ' . ucfirst($order->getCustomerLastname()) . '</span></td></tr>';

            $address = $order->getShippingAddress();

            $states = Mage::getModel('directory/country')->load('US')->getRegions();
            $regionCode = '';
            foreach($states as $state){
                if($state['default_name'] == $address->getRegion()) {
                    $regionCode = $state['code'];
                }
            }
            $result .= '<tr><td style="text-align: left; font-size: 5mm"><span style="font-weight: bold">Address:</span> <span style="font-size: 3.5mm"><i>' . array_shift($address->getStreet()) . '</i></span></td></tr>';
            $result .= '<tr><td style="text-align: left; font-size: 5mm"><span style="font-weight: bold; margin-left: 82px"></span> <span style="font-size: 3.5mm"><i>' . $address->getCity() . ', ' . $regionCode . ' ' . $address->getPostcode() . '</i></span></td></tr>';
            $result .= '</table>';

            $result .= '<table style="width: 100%; border-top: solid 2px black;">';
            $result .= '<tr><td style="width: 100%"></td></tr>';
            $result .= '</table>';

            $result .= '<table style="width: 100%; font-size: 3mm; margin-top: 5px">';
            $result .= '<tr><td style="width: 100%; text-align: justify"><i>' . strip_tags($renderData['upper'], '<br>') . '</i></td></tr>';
            $result .= '</table>';

            $result .= '<table style="width: 100%; margin-top: 7px">';
            $result .= '<tr>';
            $result .= '<td style="width: 50%; height: 50mm; padding: 10mm"><table style="width: 100%;"><tr><td style="height: 70%"><img style="width: 90%;" src="' . $productMediaConfig->getBaseMediaPath() . $product->getThumbnail() . '" /></td></tr></table></td>';
            $result .= '<td style="width: 50%"><table>';
            if (!is_null($renderData['properties'])) {
                foreach ($renderData['properties'] as $prop) {
                    $propValue = $product->getData($prop);
                    $propName = Mage::getModel('eav/entity_attribute')->loadByCode('catalog_product', $prop)->getStoreLabel();

                    if (empty($propName)) {
                        continue;
                    }

                    $result .= '<tr><td style="font-weight: bold; width: 45%">' . $propName . ': ' . $propValue . '</td></tr>';
                }
            }
            $result .= '</table></td>';
            $result .= '</tr>';
            $result .= '</table>';

            $result .= '<table style="width: 100%; font-size: 3mm; margin-top: 7px">';
            $result .= '<tr><td style="width: 100%; text-align: justify"><i>' . strip_tags($renderData['lower'], '<br>') . '</i></td></tr>';
            if (!empty($renderData['additional'])) {
                $result .= '<tr><td style="width: 100%; text-align: justify; height: 55px;"><i>' . strip_tags($renderData['additional'], '<br>') . '</i></td></tr>';
            }
            $result .= '</table>';

            if (!is_null($signPath)) {
                $result .= '<img src="' . $signPath . '" style="width: 100px; position: absolute; bottom: 140px; left: 450px" />';
            }

            $result .= '<table align="right" style="width: 100%; border-top: solid 1px black; position: absolute; bottom: 100px;"><tr><td></td><td></td></tr>';
            $result .= '<tr style="font-weight: bold"><td style="width: 40mm">' . date('F d, Y') . '</td><td>Signature</td></tr>';
            $result .= '</table>';

            $result .= '</page>';
        }

        return $result;
    }
}