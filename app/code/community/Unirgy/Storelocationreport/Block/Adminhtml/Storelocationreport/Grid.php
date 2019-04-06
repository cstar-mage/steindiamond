<?php

class Unirgy_Storelocationreport_Block_Adminhtml_Storelocationreport_Grid extends Mage_Adminhtml_Block_Widget_Grid
{
  public function __construct()
  {
      parent::__construct();
      $this->setId('storelocationreportGrid');
      $this->setDefaultSort('storelocationreport_id');
      $this->setDefaultDir('ASC');
      $this->setSaveParametersInSession(true);
  }

  protected function _prepareCollection()
  {
      $collection = Mage::getModel('storelocationreport/storelocationreport')->getCollection();
      $this->setCollection($collection);
      return parent::_prepareCollection();
  }

  protected function _prepareColumns()
  {
      $this->addColumn('storelocationreport_id', array(
          'header'    => Mage::helper('storelocationreport')->__('ID'),
          'align'     =>'right',
          'width'     => '50px',
          'index'     => 'storelocationreport_id',
      ));

      $this->addColumn('search_string', array(
          'header'    => Mage::helper('storelocationreport')->__('Searched Address'),
          'align'     =>'left',
          'index'     => 'search_string',
      ));
      
      $this->addColumn('created_time', array(
      		'header'    => Mage::helper('storelocationreport')->__('Created Time'),
      		'align'     =>'left',
      		'type'      => 'datetime',
      		'index'     => 'created_time',
      ));
  
        
		
		$this->addExportType('*/*/exportCsv', Mage::helper('storelocationreport')->__('CSV'));
		$this->addExportType('*/*/exportXml', Mage::helper('storelocationreport')->__('XML'));
	  
      return parent::_prepareColumns();
  }

    protected function _prepareMassaction()
    {
        $this->setMassactionIdField('storelocationreport_id');
        $this->getMassactionBlock()->setFormFieldName('storelocationreport');

        $this->getMassactionBlock()->addItem('delete', array(
             'label'    => Mage::helper('storelocationreport')->__('Delete'),
             'url'      => $this->getUrl('*/*/massDelete'),
             'confirm'  => Mage::helper('storelocationreport')->__('Are you sure?')
        ));

        
        return $this;
    }

  public function getRowUrl($row)
  {
      return $this->getUrl('*/*/edit', array('id' => $row->getId()));
  }

}