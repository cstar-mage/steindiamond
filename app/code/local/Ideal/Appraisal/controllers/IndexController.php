<?php

class Ideal_Appraisal_IndexController extends Mage_Adminhtml_Controller_Action
{
    protected function _isAllowed()
    {
        return true;
    }

    public function indexAction()
    {
        $renderData = array();

        $renderData['properties'] = $this->getRequest()->getParam('properties');
        empty($renderData['properties']) && $renderData['properties'] = explode(',', Mage::getStoreConfig('appraisal/main/properties'));

        $renderData['upper'] = $this->getRequest()->getParam('upper');
        empty($renderData['upper']) && $renderData['upper'] = Mage::getStoreConfig('appraisal/main/upper');

        $renderData['lower'] = $this->getRequest()->getParam('lower');
        empty($renderData['lower']) && $renderData['lower'] = Mage::getStoreConfig('appraisal/main/lower');

        $renderData['additional'] = $this->getRequest()->getParam('additional');

        $renderData['sign'] = $this->getRequest()->getParam('sign');
        $renderData['order_id'] = $this->getRequest()->getParam('order_id');

        $renderer = Mage::getModel('IdealAppraisal/PdfRenderer');

        require_once(Mage::getBaseDir('lib') .'/html2pdf/vendor/autoload.php');

        $html2pdf = new HTML2PDF('P','A4','fr');
        $html2pdf->WriteHTML($renderer->renderPdf($renderData));
        $html2pdf->Output('output.pdf');
//
//
//        $pdf = $renderer->renderPdf($renderData);
//
//        $this->getResponse()->setHeader('Content-Type', 'application/pdf');
//        $this->getResponse()->setHeader('Cache-Control', 'public, must-revalidate, max-age=0');
//        $this->getResponse()->setHeader('Pragma', 'public');
//        $this->getResponse()->setHeader('Expires', 'Sat, 26 Jul 1997 05:00:00 GMT');
//        $this->getResponse()->setHeader('Last-Modified', gmdate('D, d M Y H:i:s').' GMT');
//        $this->getResponse()->setHeader('Content-Length', strlen($pdf));
//        $this->getResponse()->setHeader('Content-Disposition', 'inline; filename="Appraisal";');
//        $this->getResponse()->setHeader('Content-Type', 'application/pdf');

//        $this->getResponse()->setBody($pdf);
    }
}