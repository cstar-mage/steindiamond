<?php

class Dolphin_Specialorder_Block_Adminhtml_Specialorder_Grid extends Mage_Adminhtml_Block_Widget_Grid
{
  public function __construct()
  {
      parent::__construct();
      $this->setId('specialorderGrid');
      $this->setDefaultSort('customcontact_id');
      $this->setDefaultDir('DESC');
      $this->setUseAjax(true);
      $this->setSaveParametersInSession(true);
  }

  protected function _prepareCollection()
  {
      $collection = Mage::getModel('specialorder/specialorder')->getCollection();
      $this->setCollection($collection);
      return parent::_prepareCollection();
  }

  protected function _prepareColumns()
  {
      $this->addColumn('customcontact_id', array(
          'header'    => Mage::helper('specialorder')->__('ID'),
          'align'     =>'right',
          'width'     => '50px',
          'index'     => 'customcontact_id',
      ));

      $this->addColumn('name', array(
          'header'    => Mage::helper('specialorder')->__('Name'),
          'align'     =>'left',
          'index'     => 'name',
      ));
      $this->addColumn('phone', array(
      		'header'    => Mage::helper('specialorder')->__('Phone'),
      		'align'     =>'left',
      		'index'     => 'phone',
      ));
      $this->addColumn('email', array(
      		'header'    => Mage::helper('specialorder')->__('Email'),
      		'align'     =>'left',
      		'index'     => 'email',
      ));

      $this->addColumn('address', array(
          'header'    => Mage::helper('specialorder')->__('Address'),
          'align'     =>'left',
          'index'     => 'address',
      ));
      $this->addColumn('city', array(
      		'header'    => Mage::helper('specialorder')->__('City'),
      		'align'     =>'left',
      		'index'     => 'city',
      ));
      $this->addColumn('state', array(
      		'header'    => Mage::helper('specialorder')->__('State'),
      		'align'     =>'left',
      		'index'     => 'state',
      ));
      $this->addColumn('country', array(
      		'header'    => Mage::helper('specialorder')->__('Country'),
      		'align'     =>'left',
      		'index'     => 'country',
      ));
	/*    $this->addColumn('Appoint Date', array( 
      		'header'    => Mage::helper('specialorder')->__('Appointment Date'),
      		'align'     =>'left',
      		'index'     => 'appointmentdate',
      ));
	    $this->addColumn('Appoint Time', array(
      		'header'    => Mage::helper('specialorder')->__('Appointment Time'),
      		'align'     =>'left',
      		'index'     => 'appointmenttime',
      ));*/
    
     /* $this->addColumn('status', array(
          'header'    => Mage::helper('specialorder')->__('Status'),
          'align'     => 'left',
          'width'     => '80px',
          'index'     => 'status',
          'type'      => 'options',
          'options'   => array(
              1 => 'Enabled',
              2 => 'Disabled',
          ),
      ));
	  */
        $this->addColumn('action',
            array(
                'header'    =>  Mage::helper('specialorder')->__('Action'),
                'width'     => '100',
                'type'      => 'action',
                'getter'    => 'getId',
                'actions'   => array(
                    array(
                        'caption'   => Mage::helper('specialorder')->__('Delete'),
                        'url'       => array('base'=> '*/*/delete'),
                        'field'     => 'id'
                    ),
                		array(
                				'caption'   => Mage::helper('specialorder')->__('Edit'),
                				'url'       => array('base'=> '*/*/edit'),
                				'field'     => 'id'
                		)
                ),
                'filter'    => false,
                'sortable'  => false,
                'index'     => 'stores',
                'is_system' => true,
        ));
		
		$this->addExportType('*/*/exportCsv', Mage::helper('specialorder')->__('CSV'));
		$this->addExportType('*/*/exportXml', Mage::helper('specialorder')->__('XML'));
	  
      return parent::_prepareColumns();
  }

    protected function _prepareMassaction()
    {
        $this->setMassactionIdField('customcontact_id');
        $this->getMassactionBlock()->setFormFieldName('specialorder');

        $this->getMassactionBlock()->addItem('delete', array(
             'label'    => Mage::helper('specialorder')->__('Delete'),
             'url'      => $this->getUrl('*/*/massDelete'),
             'confirm'  => Mage::helper('specialorder')->__('Are you sure?')
        ));

        $statuses = Mage::getSingleton('specialorder/status')->getOptionArray();

        array_unshift($statuses, array('label'=>'', 'value'=>''));
        $this->getMassactionBlock()->addItem('status', array(
             'label'=> Mage::helper('specialorder')->__('Change status'),
             'url'  => $this->getUrl('*/*/massStatus', array('_current'=>true)),
             'additional' => array(
                    'visibility' => array(
                         'name' => 'status',
                         'type' => 'select',
                         'class' => 'required-entry',
                         'label' => Mage::helper('specialorder')->__('Status'),
                         'values' => $statuses
                     )
             )
        ));
        return $this;
    }
    public function getGridUrl()
    {
    	return $this->getUrl('*/*/grid', array('_current'=>true));
    }

  public function getRowUrl($row)
  {
      return $this->getUrl('*/*/edit', array('id' => $row->getId()));
  }

}
