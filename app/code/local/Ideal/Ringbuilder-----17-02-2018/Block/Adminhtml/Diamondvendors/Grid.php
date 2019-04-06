<?php

class Ideal_Ringbuilder_Block_Adminhtml_Diamondvendors_Grid extends Mage_Adminhtml_Block_Widget_Grid
{
  public function __construct()
  {
      parent::__construct();
      $this->setId('diamondvendorsGrid');
      $this->setDefaultSort('vendor_id');
      $this->setDefaultDir('ASC');
      $this->setSaveParametersInSession(true);
  }

  protected function _prepareCollection()
  {
      $collection = Mage::getModel('ringbuilder/diamondvendors')->getCollection();
      $this->setCollection($collection);
      return parent::_prepareCollection();
  }

  protected function _prepareColumns()
  {
      $this->addColumn('vendor_id', array(
          'header'    => Mage::helper('ringbuilder')->__('ID'),
          'align'     =>'right',
          'width'     => '50px',
          'index'     => 'vendor_id',
      ));

      $this->addColumn('name', array(
          'header'    => Mage::helper('ringbuilder')->__('Name'),
          'align'     =>'left',
          'index'     => 'name',
      ));

      $this->addColumn('contact_person', array(
      		'header'    => Mage::helper('ringbuilder')->__('Contact Person'),
      		'align'     =>'left',
      		'index'     => 'contact_person',
      ));
      
      $this->addColumn('email', array(
      		'header'    => Mage::helper('ringbuilder')->__('Email'),
      		'align'     =>'left',
      		'index'     => 'email',
      ));
      
        $this->addColumn('action',
            array(
                'header'    =>  Mage::helper('ringbuilder')->__('Action'),
                'width'     => '100',
                'type'      => 'action',
                'getter'    => 'getId',
                'actions'   => array(
                    array(
                        'caption'   => Mage::helper('ringbuilder')->__('Edit'),
                        'url'       => array('base'=> '*/*/edit'),
                        'field'     => 'id'
                    )
                ),
                'filter'    => false,
                'sortable'  => false,
                'index'     => 'stores',
                'is_system' => true,
        ));
		
		$this->addExportType('*/*/exportCsv', Mage::helper('ringbuilder')->__('CSV'));
		$this->addExportType('*/*/exportXml', Mage::helper('ringbuilder')->__('XML'));
	  
      return parent::_prepareColumns();
  }

    protected function _prepareMassaction()
    {
        $this->setMassactionIdField('vendor_id');
        $this->getMassactionBlock()->setFormFieldName('diamondvendors');

        $this->getMassactionBlock()->addItem('delete', array(
             'label'    => Mage::helper('ringbuilder')->__('Delete'),
             'url'      => $this->getUrl('*/*/massDelete'),
             'confirm'  => Mage::helper('ringbuilder')->__('Are you sure?')
        ));

        
        return $this;
    }

  public function getRowUrl($row)
  {
      return $this->getUrl('*/*/edit', array('id' => $row->getId()));
  }

}