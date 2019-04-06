<?php 
class Ideal_UrlRewriteImporter_Helper_Data extends Mage_Core_Helper_Abstract
{
	public function checkRepairUrlRewrites(){
		try
		{
			$resource = Mage::getConfig()->getNode('global/resources')->asArray();
			$magento_db = $resource['default_setup']['connection']['host'];
			$mdb_user = $resource['default_setup']['connection']['username'];
			$mdb_passwd = $resource['default_setup']['connection']['password'];
			$mdb_name = $resource['default_setup']['connection']['dbname'];
			
			$magento_connection = @mysql_connect($magento_db, $mdb_user, $mdb_passwd);

			if (!$magento_connection)
			{
				die('Unable to connect to the database');
			}
			@mysql_select_db($mdb_name, $magento_connection) or die ("Database not found.");
		
			$core_url_rewrite = Mage::getSingleton("core/resource")->getTableName('core_url_rewrite');
			$eav_attribute = Mage::getSingleton("core/resource")->getTableName('eav_attribute');
			$eav_entity_type = Mage::getSingleton("core/resource")->getTableName('eav_entity_type');
			$catalog_product_entity_varchar = Mage::getSingleton("core/resource")->getTableName('catalog_product_entity_varchar');
			$catalog_category_entity_varchar = Mage::getSingleton("core/resource")->getTableName('catalog_category_entity_varchar');
			$eav_attribute = Mage::getSingleton("core/resource")->getTableName('eav_attribute');
			$eav_entity_type = Mage::getSingleton("core/resource")->getTableName('eav_entity_type');
			$cms_page = Mage::getSingleton("core/resource")->getTableName('cms_page');

			//Repair Empty/Null url_keys of PRODUCTS
			$cntNullUrls = 0;
			$prodsNullUrl = Mage::getModel("catalog/product")->getCollection()
				->addAttributeToFilter('url_key',array('null' => true));
			if($prodsNullUrl ->getSize()>0){
				foreach($prodsNullUrl as $prod){
					$product = Mage::getModel('catalog/product')->load($prod->getId());
					$url_key = Mage::getModel('catalog/product_url')->formatUrlKey($prod->getSku());
					$product->setUrlKey($url_key);
					$product->getResource()->saveAttribute($product, 'url_key');
					$cntNullUrls++;
				}
			}
			//END Repair Empty/Null url_keys of PRODUCTS
			
			//Repair Empty/Null url_keys of CATEGORIES
			$cntNullUrls = 0;
			$catsNullUrl = Mage::getModel("catalog/category")->getCollection()
				->addAttributeToSelect('name')
				->addAttributeToFilter('url_key',array('null' => true));
			
			//echo $catsNullUrl->getSize();
			if($catsNullUrl->getSize()>0){
				foreach($catsNullUrl as $cat){
					$category = Mage::getModel('catalog/category')->load($cat->getId());
					$url_key = Mage::getModel('catalog/product_url')->formatUrlKey($cat->getName());
					$category->setUrlKey($url_key);
					$category->getResource()->saveAttribute($category, 'url_key');
					$cntNullUrls++;
				}
			}
			//END Repair Empty/Null url_keys of PRODUCTS
			
			/* Repairing Products */
			$query = 'SELECT entity_id, `value`
			FROM '.$catalog_product_entity_varchar.' v
			WHERE EXISTS (
			  SELECT *
			  FROM '.$eav_attribute.' a
			  WHERE attribute_code = "url_key"
			  AND v.attribute_id = a.attribute_id
			  AND EXISTS (
				 SELECT *
				 FROM '.$eav_entity_type.' e
				 WHERE entity_type_code = "catalog_product"
				 AND a.entity_type_id = e.entity_type_id
			  )
			)
			';
			$all_prods_with_urlkeys = array();
			
			//echo $query;
			$result= mysql_query($query);
			
			if (!$result) {
				$result = array("status"=>false, "msg"=>'Could not run query.');
				return $result;
			}
			
			if(mysql_num_rows($result) > 0){
				while($row = mysql_fetch_array($result))
				{
					$all_prods_with_urlkeys[$row['entity_id']] = $row['value'];
				}
			}
			
			//echo "<pre>";
			//echo "Total Products: ".count($all_prods_with_urlkeys)."<br>";
			//print_r($all_prods_with_urlkeys);
			$all_prods_with_dupl_count = (array_count_values($all_prods_with_urlkeys));
		
			$cnt_url_updated = 0;
			
			$query_update_urlkey = array();
			foreach($all_prods_with_dupl_count as $url_key => $dupl_count){
				if($dupl_count > 1){
					
					$arr_url_entity = array_keys($all_prods_with_urlkeys, $url_key);
					
					if(count($arr_url_entity) > 1){
						for($i=1;$i<count($arr_url_entity);$i++){
							$product = Mage::getModel("catalog/product")->load($arr_url_entity[$i]);
							$sku = $product->getSku();
							$old_url_key = $product->getUrlKey();
							$new_url_key = Mage::getModel('catalog/product_url')->formatUrlKey($old_url_key."-".$sku);
							$product->setUrlKey($new_url_key);
							$product->getResource()->saveAttribute($product, 'url_key');
							$cnt_url_updated++;
						}
					}
				}
			}

			$result_msg = $cnt_url_updated." Product Url Key(s) Conflicts Removed.<br>";
			/* ENDS Repairing Products */
			
			/* Repairing Categories */
			$query = 'SELECT entity_id, `value`
			FROM '.$catalog_category_entity_varchar.' v
			WHERE EXISTS (
			  SELECT *
			  FROM '.$eav_attribute.' a
			  WHERE attribute_code = "url_key"
			  AND v.attribute_id = a.attribute_id
			  AND EXISTS (
				 SELECT *
				 FROM '.$eav_entity_type.' e
				 WHERE entity_type_code = "catalog_category"
				 AND a.entity_type_id = e.entity_type_id
			  )
			)
			';
			$all_cats_with_urlkeys = array();
			//echo $query;
			$result= mysql_query($query);
			
			if (!$result) {
				$result = array("status"=>false, "msg"=>'Could not run query.');
				return $result;
			}
			
			if(mysql_num_rows($result) > 0){
				while($row = mysql_fetch_array($result))
				{
					$all_cats_with_urlkeys[$row['entity_id']] = $row['value'];
				}
			}
			
			//echo "<pre>";
			//echo "Total Products: ".count($all_cats_with_urlkeys)."<br>";
			//print_r($all_cats_with_urlkeys);
			$all_cats_with_dupl_count = (array_count_values($all_cats_with_urlkeys));
		
			$cnt_url_updated = 0;
			
			$query_update_urlkey = array();
			foreach($all_cats_with_dupl_count as $url_key => $dupl_count){
				if($dupl_count > 1){
					
					$arr_url_entity = array_keys($all_cats_with_urlkeys, $url_key);
					
					if(count($arr_url_entity) > 1){
						for($i=1;$i<count($arr_url_entity);$i++){
							$category = Mage::getModel("catalog/category")->load($arr_url_entity[$i]);
							$sku = $category->getId();
							$old_url_key = $category->getUrlKey();
							$new_url_key = Mage::getModel('catalog/product_url')->formatUrlKey($old_url_key."-".$sku);
							$category->setUrlKey($new_url_key);
							$category->getResource()->saveAttribute($category, 'url_key');
							$cnt_url_updated++;
						}
					}
				}
			}

			$result_msg .= $cnt_url_updated." Category Url Key(s) Conflicts Removed.<br>";
			/* ENDS Repairing Categories */
			
			/*Getting conflicts of Products, Categories and CMS pages*/
			$all_urlkeys = array();
			//product
			$query = 'SELECT entity_id, `value`
			FROM '.$catalog_product_entity_varchar.' v
			WHERE EXISTS (
			  SELECT *
			  FROM '.$eav_attribute.' a
			  WHERE attribute_code = "url_key"
			  AND v.attribute_id = a.attribute_id
			  AND EXISTS (
				 SELECT *
				 FROM '.$eav_entity_type.' e
				 WHERE entity_type_code = "catalog_product"
				 AND a.entity_type_id = e.entity_type_id
			  )
			)
			';
			
			$result= mysql_query($query);
			if (!$result) {
				$result = array("status"=>false, "msg"=>'Could not run query.');
				return $result;
			}
			if(mysql_num_rows($result) > 0)
				while($row = mysql_fetch_array($result))
				
				$all_urlkeys["pro".$row['entity_id']] = $row['value'];
				
				 

			//category
			$query = 'SELECT entity_id, `value`
			FROM '.$catalog_category_entity_varchar.' v
			WHERE EXISTS (
			  SELECT *
			  FROM '.$eav_attribute.' a
			  WHERE attribute_code = "url_key"
			  AND v.attribute_id = a.attribute_id
			  AND EXISTS (
				 SELECT *
				 FROM '.$eav_entity_type.' e
				 WHERE entity_type_code = "catalog_category"
				 AND a.entity_type_id = e.entity_type_id
			  )
			)
			';

			$result= mysql_query($query);
			if (!$result) {
				$result = array("status"=>false, "msg"=>'Could not run query.');
				return $result;
			}
			if(mysql_num_rows($result) > 0)
				while($row = mysql_fetch_array($result))
					$all_urlkeys["cat".$row['entity_id']] = $row['value'];
			
				
			//cms page
			$query = 'SELECT `page_id` as `entity_id`, `identifier` as `value` FROM `'.$cms_page.'`';
			$result= mysql_query($query);
			if (!$result) {
				$result = array("status"=>false, "msg"=>'Could not run query.');
				return $result;
			}
			if(mysql_num_rows($result) > 0)
				while($row = mysql_fetch_array($result))
					$all_urlkeys["cms".$row['entity_id']] = $row['value'];

				
			//~ echo "<pre>";
			//~ print_r($all_urlkeys);
			//~ exit;
			$all_urls_with_dupl_count = (array_count_values($all_urlkeys));
			
			$conflicted_urlkeys = array();
			
			foreach($all_urls_with_dupl_count as $url_key => $dupl_count){
				if($dupl_count > 1){
					$arr_url_entity = array_keys($all_urlkeys, $url_key);
					if(count($arr_url_entity) > 1){
						$conflicted_urlkey = "Conflict between ";
						for($i=0;$i<count($arr_url_entity);$i++){
							$conflicted_urlkey .= str_replace(array("pro","cat","cms"), array("Product Id = ","Categoty Id = ","CMS Page Id = "), $arr_url_entity[$i])." ( ".$url_key." )   , ";
						}
						$conflicted_urlkeys[] = $conflicted_urlkey;
					}
				}
			}
			
			
			$emailname = Mage::getStoreConfig('cronjobs/check_repair_product_rewrites/recipient_email');
			$result_email = implode("<br>", $conflicted_urlkeys);
			$result_emailhtml .= "<html><body>";
			$result_emailhtml .= "<h3>There are some url which is conflict with other Url</h3><br>";
			$result_emailhtml .= $result_email;
			$result_emailhtml .= "</body></html>";
			
			$emailcount = count($conflicted_urlkeys);
			$headers .= "MIME-Version: 1.0\r\n";
			$headers .= "Content-Type: text/html; charset=ISO-8859-1\r\n";
			
			// send email
			if($emailcount > 0 && $emailname != ""){
				mail($emailname,"Url Rewrite",$result_emailhtml,$headers);
			}
			
			$result_msg .= implode("<br>", $conflicted_urlkeys);
			
			
			return array("status"=>true, "msg"=>$result_msg);

		}
		catch (Exception $e) {
			$result = array("status"=>false, "msg"=>$e->getMessage());
			return $result;
		}
	}
}
