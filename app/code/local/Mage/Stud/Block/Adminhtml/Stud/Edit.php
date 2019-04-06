<?php

class Mage_Stud_Block_Adminhtml_Stud_Edit extends Mage_Adminhtml_Block_Widget_Form_Container
{
    public function __construct()
    {
        parent::__construct();
                 
        $this->_objectId = 'id';
        $this->_blockGroup = 'stud';
        $this->_controller = 'adminhtml_stud';
		$this->_removeButton('reset');
		$this->_removeButton('back');
        $this->_updateButton('save', 'label', Mage::helper('stud')->__('Save Rules'));
       
		/*$this->_addButton('run_in_popup', array(
           'label'     => Mage::helper('adminhtml')->__('Get New List'),
            'onclick'   => 'setLocation(\''.$this->getUrl('stud/adminhtml_stud/insertinpopup', array('_current'=>true)).'\')',
            'class'     => 'save', 
        ), -100);

		
		$this->_addButton('run_in_popup', array(
           'label'     => Mage::helper('adminhtml')->__('Insert Diamond In PopUp'),
            'onclick'   => 'setLocation(\''.$this->getUrl('stud/adminhtml_stud/insertinpopup', array('_current'=>true)).'\')',
            'class'     => 'save', 
        ), -100);
	
		
		$this->_addButton('reindex', array(
           'label'     => Mage::helper('adminhtml')->__('Update Diamonds'),
            'onclick'   => 'setLocation(\''.$this->getUrl('stud/adminhtml_stud/reindex', array('_current'=>true)).'\')',
            'class'     => 'save', 
        ), -100);
		
		$this->_addButton('download', array(
           'label'     => Mage::helper('adminhtml')->__('Download CSV'),
            'onclick'   => 'setLocation(\''.Mage::getBaseUrl(Mage_Core_Model_Store::URL_TYPE_WEB).'var/import/products.csv'.'\')',
            'class'     => 'save', 
        ), -100);
		
/*		
		$this->_addButton('update_inventory', array(
           'label'     => Mage::helper('adminhtml')->__('Update Inventory'),
            'onclick'   => 'setLocation(\''.$this->getUrl('stud/adminhtml_stud/inventoryupdate', array('_current'=>true)).'\')',
            'class'     => 'save',
        ), -100);
*/    }
	  protected function _prepareLayout()
    {
		  return parent::_prepareLayout();
	}
    public function getHeaderText()
    {
		/*$write = Mage::getSingleton("core/resource")->getConnection("core_write");

		$magento_db 	= 	Mage::getStoreConfig('stud/db_detail/db_database'); 
		$mdb_name 		= 	Mage::getStoreConfig('stud/db_detail/db_name');
		$mdb_user 		= 	Mage::getStoreConfig('stud/db_detail/db_username');
		$mdb_passwd 	= 	Mage::getStoreConfig('stud/db_detail/db_userpassword');
		$magento_connection = @mysql_connect($magento_db, $mdb_user, $mdb_passwd);
		
		if (!$magento_connection)
		{
			die('Unable to connect to the database');
		}
		@mysql_select_db($mdb_name, $magento_connection) or die ("Database not found.");
		$Result = mysql_query("SELECT UPDATE_TIME FROM information_schema.tables WHERE  TABLE_SCHEMA = '".$mdb_name."' AND TABLE_NAME = 'stud_price'");
		$update = mysql_fetch_array($Result);
		
		return Mage::helper('stud')->__('Add Item')."<span style='font-size:12px; margin-left:20px; color:#2F2F2F;'>".Mage::helper('stud')->__('Last Updated:') . "&nbsp;<basefont>" . $update['UPDATE_TIME'] . "</basefont></span>";

        if( Mage::registry('stud_data') && Mage::registry('stud_data')->getId() ) {
            return Mage::helper('stud')->__("Edit Item '%s'", $this->htmlEscape(Mage::registry('stud_data')->getTitle()));
        } else {
            return Mage::helper('stud')->__('Add Item');
        } */
    }
}