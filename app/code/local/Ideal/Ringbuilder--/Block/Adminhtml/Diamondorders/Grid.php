<?php

class Ideal_Ringbuilder_Block_Adminhtml_Diamondorders_Grid extends Mage_Adminhtml_Block_Widget_Grid
{
  public function __construct()
  {
      parent::__construct();
      $this->setId('diamondordersGrid');
      $this->setDefaultSort('item_id');
      $this->setDefaultDir('desc');
      $this->setSaveParametersInSession(true);
  }

  protected function _prepareCollection()
  {
      $collection = Mage::getModel('ringbuilder/diamondorders')->getCollection();
      
      if($this->getRequest()->getParam('order_id') && $this->getRequest()->getParam('order_id') != '') {
      		$collection->addFieldToFilter('order_id',$this->getRequest()->getParam('order_id'));
      }
      
      $this->setCollection($collection);
      return parent::_prepareCollection();
  }

  protected function _prepareColumns()
  {
      $this->addColumn('item_id', array(
          'header'    => Mage::helper('ringbuilder')->__('ID'),
          'align'     =>'right',
          'width'     => '50px',
          'index'     => 'item_id',
      ));

      /* $this->addColumn('order_id', array(
      		'header'    => Mage::helper('ringbuilder')->__('Order ID'),
      		'align'     =>'left',
      		'index'     => 'order_id',
      )); */
      $this->addColumn('increment_id', array(
      		'header'    => Mage::helper('ringbuilder')->__('Order Increment ID'),
      		'align'     =>'left',
      		'index'     => 'increment_id',
      ));
      $this->addColumn('lotno', array(
      		'header'    => Mage::helper('ringbuilder')->__('Rockher Lot#'),
      		'align'     =>'left',
      		'index'     => 'lotno',
      ));
      $this->addColumn('stock_number', array(
      		'header'    => Mage::helper('ringbuilder')->__('Vendor Lot#'),
      		'align'     =>'left',
      		'index'     => 'stock_number',
      ));
      $this->addColumn('owner', array(
      		'header'    => Mage::helper('ringbuilder')->__('Owner'),
      		'align'     =>'left',
      		'index'     => 'owner',
      ));
      $this->addColumn('shape', array(
          'header'    => Mage::helper('ringbuilder')->__('Shape'),
          'align'     =>'left',
          'index'     => 'shape',
      ));

      $this->addColumn('carat', array(
      		'header'    => Mage::helper('ringbuilder')->__('Carat'),
      		'align'     =>'left',
      		'index'     => 'carat',
      ));
      
      $this->addColumn('color', array(
      		'header'    => Mage::helper('ringbuilder')->__('Color'),
      		'align'     =>'left',
      		'index'     => 'color',
      ));
      
      $this->addColumn('clarity', array(
      		'header'    => Mage::helper('ringbuilder')->__('Clarity'),
      		'align'     =>'left',
      		'index'     => 'clarity',
      ));
      
      $this->addColumn('price_per_carat', array(
      		'header'    => Mage::helper('ringbuilder')->__('Price/Ct'),
      		'align'     =>'left',
      		'type'  => 'price',
      		'currency_code' =>(string)Mage::getStoreConfig(Mage_Directory_Model_Currency::XML_PATH_CURRENCY_BASE),
      		'index'     => 'price_per_carat',
      ));
      
      $this->addColumn('totalprice', array(
      		'header'    => Mage::helper('ringbuilder')->__('Total Price'),
      		'align'     =>'left',
      		'type'  => 'price',
      		'currency_code' =>(string)Mage::getStoreConfig(Mage_Directory_Model_Currency::XML_PATH_CURRENCY_BASE),
      		'index'     => 'totalprice',
      ));
      
      $this->addColumn('certificate', array(
      		'header'    => Mage::helper('ringbuilder')->__('Lab'),
      		'align'     =>'left',
      		'index'     => 'certificate',
      ));
      
      $this->addColumn('cert_number', array(
      		'header'    => Mage::helper('ringbuilder')->__('Cert #'),
      		'align'     =>'left',
      		'index'     => 'cert_number',
      ));
      
      $this->addColumn('cut', array(
      		'header'    => Mage::helper('ringbuilder')->__('Cut'),
      		'align'     =>'left',
      		'index'     => 'cut',
      ));
      
      $this->addColumn('culet', array(
      		'header'    => Mage::helper('ringbuilder')->__('Culet'),
      		'align'     =>'left',
      		'index'     => 'culet',
      ));
      $this->addColumn('crown', array(
      		'header'    => Mage::helper('ringbuilder')->__('Crown'),
      		'align'     =>'left',
      		'index'     => 'crown',
      ));
      $this->addColumn('pavilion', array(
      		'header'    => Mage::helper('ringbuilder')->__('Pavilion'),
      		'align'     =>'left',
      		'index'     => 'pavilion',
      ));
      $this->addColumn('dimensions', array(
      		'header'    => Mage::helper('ringbuilder')->__('Dimensions'),
      		'align'     =>'left',
      		'index'     => 'dimensions',
      ));
      $this->addColumn('depth', array(
      		'header'    => Mage::helper('ringbuilder')->__('Depth'),
      		'align'     =>'left',
      		'index'     => 'depth',
      ));
      $this->addColumn('tabl', array(
      		'header'    => Mage::helper('ringbuilder')->__('Table'),
      		'align'     =>'left',
      		'index'     => 'tabl',
      ));
      $this->addColumn('polish', array(
      		'header'    => Mage::helper('ringbuilder')->__('Polish'),
      		'align'     =>'left',
      		'index'     => 'polish',
      ));
      $this->addColumn('symmetry', array(
      		'header'    => Mage::helper('ringbuilder')->__('Symmetry'),
      		'align'     =>'left',
      		'index'     => 'symmetry',
      ));
      $this->addColumn('fluorescence', array(
      		'header'    => Mage::helper('ringbuilder')->__('Fluorescence'),
      		'align'     =>'left',
      		'index'     => 'fluorescence',
      ));
      $this->addColumn('girdle', array(
      		'header'    => Mage::helper('ringbuilder')->__('Girdle'),
      		'align'     =>'left',
      		'index'     => 'girdle',
      ));
      
      $this->addColumn('fancy_intensity', array(
      		'header'    => Mage::helper('ringbuilder')->__('Fancy Intensity'),
      		'align'     =>'left',
      		'index'     => 'fancy_intensity',
      ));
      
      $this->addColumn('fancycolor', array(
      		'header'    => Mage::helper('ringbuilder')->__('Fancy Color'),
      		'align'     =>'left',
      		'index'     => 'fancycolor',
      ));
      
      
      
        
		
		$this->addExportType('*/*/exportCsv', Mage::helper('ringbuilder')->__('CSV'));
		$this->addExportType('*/*/exportXml', Mage::helper('ringbuilder')->__('XML'));
	  
      return parent::_prepareColumns();
  }

    protected function _prepareMassaction()
    {
    	return $this;
    	
        $this->setMassactionIdField('item_id');
        $this->getMassactionBlock()->setFormFieldName('diamondorders');

        $this->getMassactionBlock()->addItem('delete', array(
             'label'    => Mage::helper('ringbuilder')->__('Delete'),
             'url'      => $this->getUrl('*/*/massDelete'),
             'confirm'  => Mage::helper('ringbuilder')->__('Are you sure?')
        ));

        
        return $this;
    }

  public function getRowUrl($row)
  {
      return false;
  }

}