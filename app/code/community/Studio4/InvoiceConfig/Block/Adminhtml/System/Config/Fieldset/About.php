<?php

/**
 * @author	studio4.lt
 */
class Studio4_InvoiceConfig_Block_Adminhtml_System_Config_Fieldset_About extends Mage_Adminhtml_Block_Abstract implements Varien_Data_Form_Element_Renderer_Interface
{
    public function render(Varien_Data_Form_Element_Abstract $element)
    {
		ob_start();
		?>
		<fieldset>
			<table><tr>
				<td style="vertical-align: middle;">
					<img src="<?php echo Mage::helper('studio4_invoiceconfig/common')->getSkinFileUrl('studio4_logo.png'); ?>" alt="studio4" style="margin: 0px 15px 0px 0px;" />
				</td>
				<td style="vertical-align: middle;"><table>
					<tr>
						<td style="width: 100px;">Version</td>
						<td><?php echo Mage::getConfig()->getNode('modules/Studio4_InvoiceConfig/version'); ?><span id="studio4InvoiceConfigNewVersionHolder" style="color: red;"></span></td>
					</tr>
					<tr>
						<td>Get it at</td>
						<td><a href="http://shop.studio4.lt/product/15/professional-and-easily-customisable-invoice-for-magento" target="_blank">Studio4 shop</a></td>
					</tr>
					<tr>
						<td>Write a review</td>
						<td><a href="http://www.magentocommerce.com/magento-connect/s4-customizable-invoice-template.html" target="_blank">Magento Connect</a></td>
					</tr>
					<tr>
						<td>Created by</td>
						<td><a href="http://www.studio4.lt" target="_blank">Studio4</a></td>
					</tr>
					<tr>
						<td>Get in touch</td>
						<td><a href="mailto:shop@studio4.lt">shop@studio4.lt</a></td>
					</tr>
				</table></td>
			</tr></table>
    	</fieldset>
    	<script type="text/javascript">
    		function compareStudio4InvoiceConfigVersion(remoteV)
    		{
    			var localV = '<?php echo Mage::getConfig()->getNode('modules/Studio4_InvoiceConfig/version'); ?>';
    			var versionformatRegex = /^\d[\d\.]+$/;
    			if (!versionformatRegex.test(localV) || !versionformatRegex.test(remoteV))
    				return;
    				
    			var locParts = localV.split('.');
        		var remParts = remoteV.split('.');
        		
        		var len = locParts.length;
        		if (remParts.length > len)
        			len = remParts.length;
        			
        		var isNew = false;
        			
        		for (var i = 0; i < len; i++)
        		{
        			var locPart = parseInt(locParts[i], 10);
        			var remPart = parseInt(remParts[i], 10);
        			
        			if (isNaN(locPart)) locPart = 0;
        			if (isNaN(remPart)) remPart = 0;
        			
        			if (remPart < locPart)
        				return;
        			
        			if (remPart > locPart)
        				isNew = true;
        		}
        		
        		if (isNew)
        		{
        			var holder = document.getElementById('studio4InvoiceConfigNewVersionHolder');
        			holder.innerHTML = ' (new version available: <b><a href="http://shop.studio4.lt/product/15/professional-and-easily-customisable-invoice-for-magento" target="_blank">' + remoteV + '</a>!</b>)';
        		}
    		}
    	
    		function checkForStudio4InvoiceConfigUpdates() {
    			var ajaxReq;

			    if (window.XMLHttpRequest)
			        ajaxReq = new XMLHttpRequest();
			    else
			        ajaxReq = new ActiveXObject('Microsoft.XMLHTTP');
			
			    ajaxReq.onreadystatechange = function() {
			        if (ajaxReq.readyState == 4 && ajaxReq.status == 200)
			            compareStudio4InvoiceConfigVersion(ajaxReq.responseText);
			    }
			
			    ajaxReq.open('GET', '//shop.studio4.lt/product/15/professional-and-easily-customisable-invoice-for-magento?action=getCurrentVersion', true);
			    ajaxReq.send();
    		}
    		
    		if (window.addEventListener)
  				window.addEventListener('load', checkForStudio4InvoiceConfigUpdates, false);
			else if (window.attachEvent)
  				window.attachEvent('onload', checkForStudio4InvoiceConfigUpdates);
    	</script>
		<?php
		$str = ob_get_contents();
		ob_end_clean();
		
        return $str;
    }
}

