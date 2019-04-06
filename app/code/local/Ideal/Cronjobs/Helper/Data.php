<?php 
class Ideal_Cronjobs_Helper_Data extends Mage_Core_Helper_Abstract
{
	public function idealinfoCron(){

		$config  = Mage::getConfig()->getResourceConnectionConfig('default_setup');
		
		$dbinfo = array('host' => $config->host,
					'user' => $config->username,
					'pass' => $config->password,
					'dbname' => $config->dbname
		);
		
		$dbData['host'] = $dbinfo['host'];
		$dbData['username'] = $dbinfo['user'];
		$dbData['password'] = $dbinfo['pass'];
		$dbData['dbname'] = $dbinfo['dbname'];
		$tblprefix = Mage::getConfig()->getTablePrefix();

		$pdo = new PDO('mysql:host='.$dbData['host'].';dbname='.$dbData['dbname'], $dbData['username'], $dbData['password']);
		
		$result = array();
		
		// System -----------------
		
		$result['system'] = [];
		
		$result['system']['magento_core']['version'] = Mage::getVersion();
		
		//echo $current_dir = dirname(__FILE__);
		$appDir = Mage::getBaseDir('app').DS;
		$localDir = $appDir.'code'.DS.'local'.DS;
		$communityDir = $appDir.'code'.DS.'community'.DS;

		// Evolved Core
		
		$xml = file_exists($localDir.'Ideal/Evolved/etc/config.xml') ? simplexml_load_file($localDir.'Ideal/Evolved/etc/config.xml', NULL, LIBXML_NOCDATA) : '';
		
		$result['system']['evolved_core']['version'] = !empty($xml) ? (string)$xml->modules->Ideal_Evolved->version : '';
		
		// LiteMage
		
		$xml = file_exists($communityDir.'Litespeed/Litemage/etc/config.xml') ? simplexml_load_file($communityDir.'Litespeed/Litemage/etc/config.xml', NULL, LIBXML_NOCDATA) : '';
		
		$result['system']['litemage_cache']['version'] = !empty($xml) ? (string)$xml->modules->Litespeed_Litemage->version : '';
		
		
		
		// Training
		
		$xml = file_exists($localDir.'Ideal/Videogallery/etc/config.xml') ? simplexml_load_file($localDir.'Ideal/Videogallery/etc/config.xml', NULL, LIBXML_NOCDATA) : '';
		
		$result['system']['training']['version'] = !empty($xml) ? (string)$xml->modules->Ideal_Videogallery->version : '';
		
		// Messages
		
		$xml = file_exists($localDir.'Message/Contactform/etc/config.xml') ? simplexml_load_file($localDir.'Message/Contactform/etc/config.xml', NULL, LIBXML_NOCDATA) : '';
		
		$result['system']['messages']['version'] = !empty($xml) ? (string)$xml->modules->Message_Contactform->version : '';
		
		
		
		// Wordpress
		
		$xml = file_exists($communityDir.'Fishpig_Wordpress/etc/config.xml') ? simplexml_load_file($communityDir.'Fishpig_Wordpress/etc/config.xml', NULL, LIBXML_NOCDATA) : '';
		
		$result['system']['wordpress']['version'] = !empty($xml) ? (string)$xml->modules->Fishpig_Wordpress->version : '';
		
		
		
		// Aheadworks Blog
		
		$xml = file_exists($communityDir.'AW/Blog/etc/config.xml') ? simplexml_load_file($communityDir.'AW/Blog/etc/config.xml', NULL, LIBXML_NOCDATA) : '';
		
		$result['system']['aheadworks_blog']['version'] = !empty($xml) ? (string)$xml->modules->AW_Blog->version : '';
		
		
		
		// Cron Scheduler
		
		$xml = file_exists($communityDir.'Aoe/Scheduler/etc/config.xml') ? simplexml_load_file($communityDir.'Aoe/Scheduler/etc/config.xml', NULL, LIBXML_NOCDATA) : '';
		
		$result['system']['cron_scheduler']['version'] = !empty($xml) ? (string)$xml->modules->Aoe_Scheduler->version : '';
		
		
		
		// Image Clean Up
		
		$xml = file_exists($localDir.'Mage/Imaclean/etc/config.xml') ? simplexml_load_file($localDir.'Mage/Imaclean/etc/config.xml', NULL, LIBXML_NOCDATA) : '';
		
		$result['system']['image_cleanup']['version'] = !empty($xml) ? (string)$xml->modules->Mage_Imaclean->version : '';
		
		
		
		// CDN
		
		$xml = file_exists($communityDir.'OnePica/ImageCdn/etc/config.xml') ? simplexml_load_file($communityDir.'OnePica/ImageCdn/etc/config.xml', NULL, LIBXML_NOCDATA) : '';
		
		$result['system']['cdn']['version'] = !empty($xml) ? (string)$xml->modules->OnePica_ImageCdn->version : '';
		
		
		
		// ??? Transactional Emails
		
		
		
		//-------------------------
		
		
		
		// Catalog ----------------
		
		
		
		$result['catalog'] = [];
		
		
		
		// Image 2 Product
		
		$xml = file_exists($localDir.'Mage/Image2Product/etc/config.xml') ? simplexml_load_file($localDir.'Mage/Image2Product/etc/config.xml', NULL, LIBXML_NOCDATA) : '';
		
		$result['catalog']['image_2_product']['version'] = !empty($xml) ? (string)$xml->modules->Mage_Image2Product->version : '';
		
		
		
		// Upload Product
		
		$xml = file_exists($localDir.'Mage/Uploadproduct/etc/config.xml') ? simplexml_load_file($localDir.'Mage/Uploadproduct/etc/config.xml', NULL, LIBXML_NOCDATA) : '';
		
		$result['catalog']['uploadproduct']['version'] = !empty($xml) ? (string)$xml->modules->Mage_Uploadproduct->version : '';
		
		
		
		// Google Sheet Import
		
		$xml = file_exists($localDir.'Ideal/Googlesheetimport/etc/config.xml') ? simplexml_load_file($localDir.'Ideal/Googlesheetimport/etc/config.xml', NULL, LIBXML_NOCDATA) : '';
		
		$result['catalog']['google_sheet_import']['version'] = !empty($xml) ? (string)$xml->modules->Ideal_Googlesheetimport->version : '';
		
		
		
		// Advanced Product Options
		
		$xml = file_exists($localDir.'MageWorx/CustomOptions/etc/config.xml') ? simplexml_load_file($localDir.'MageWorx/CustomOptions/etc/config.xml', NULL, LIBXML_NOCDATA) : '';
		
		$result['catalog']['advanced_product_options']['version'] = !empty($xml) ? (string)$xml->modules->MageWorx_CustomOptions->version : '';
		
		
		
		// Custom Price Steping
		
		$xml = file_exists($localDir.'Mycomp/Pricemanager/etc/config.xml') ? simplexml_load_file($localDir.'Mycomp/Pricemanager/etc/config.xml', NULL, LIBXML_NOCDATA) : '';
		
		$result['catalog']['custom_price_steping']['version'] = !empty($xml) ? (string)$xml->modules->Mycomp_Pricemanager->version : '';
		
		
		
		// External Linking Categories
		
		$xml = file_exists($localDir.'LC/CategoryExternalLink/etc/config.xml') ? simplexml_load_file($localDir.'LC/CategoryExternalLink/etc/config.xml', NULL, LIBXML_NOCDATA) : '';
		
		$result['catalog']['external_linking_categories']['version'] = !empty($xml) ? (string)$xml->modules->LC_CategoryExternalLink->version : '';
		
		
		
		// Category Auto-Assigning
		
		$xml = file_exists($localDir.'Ideal/Categoryassign/etc/config.xml') ? simplexml_load_file($localDir.'Ideal/Categoryassign/etc/config.xml', NULL, LIBXML_NOCDATA) : '';
		
		$result['catalog']['category_auto_assigning']['version'] = !empty($xml) ? (string)$xml->modules->Ideal_Categoryassign->version : '';
		
		
		
		// Call For Price
		
		$xml = file_exists($localDir.'Mfmc/Mfmcallforprice/etc/config.xml') ? simplexml_load_file($localDir.'Mfmc/Mfmcallforprice/etc/config.xml', NULL, LIBXML_NOCDATA) : '';
		
		$result['catalog']['call_for_price']['version'] = !empty($xml) ? (string)$xml->modules->Mfmc_Mfmcallforprice->version : '';
		
		
		
		// ??? Catalog Management Plus
		
		
		
		//-------------------------
		
		
		
		// Marketing --------------
		
		
		
		$result['marketing'] = [];
		
		
		
		// Mailchimp
		
		$xml = file_exists($communityDir.'Ebizmarts/MageMonkey/etc/config.xml') ? simplexml_load_file($communityDir.'Ebizmarts/MageMonkey/etc/config.xml', NULL, LIBXML_NOCDATA) : '';
		
		$result['marketing']['mailchimp_integration']['version'] = !empty($xml) ? (string)$xml->modules->Ebizmarts_MageMonkey->version : '';
		
		
		
		// Live Chat
		
		$xml = file_exists($localDir.'Ideal/Livechatadmin/etc/config.xml') ? simplexml_load_file($localDir.'Ideal/Livechatadmin/etc/config.xml', NULL, LIBXML_NOCDATA) : '';
		
		$result['marketing']['live_chat']['version'] = !empty($xml) ? (string)$xml->modules->Ideal_Livechatadmin->version : '';
		
		
		
		// Newsletter Pop-up
		
		$xml = file_exists($communityDir.'ES/Newssubscribers/etc/config.xml') ? simplexml_load_file($communityDir.'ES/Newssubscribers/etc/config.xml', NULL, LIBXML_NOCDATA) : '';
		
		$result['marketing']['newsletter_popup']['version'] = !empty($xml) ? (string)$xml->modules->ES_Newssubscribers->version : '';
		
		
		
		// ??? Evo SEO, Custom Headers, Custom Attributes, Guest 2 Customer, Order By Customer, Social Media Tags
		
		
		
		//-------------------------
		
		
		
		// Front End --------------
		
		
		
		$result['frontend'] = [];
		
		
		
		// Easy Tabs
		
		$xml = file_exists($communityDir.'TM/EasyTabs/etc/config.xml') ? simplexml_load_file($communityDir.'TM/EasyTabs/etc/config.xml', NULL, LIBXML_NOCDATA) : '';
		
		$result['frontend']['see_it_person']['version'] = !empty($xml) ? (string)$xml->modules->TM_EasyTabs->version : '';
		
		
		
		// See It Person
		
		$xml = file_exists($localDir.'Ideal/Seeitperson/etc/config.xml') ? simplexml_load_file($localDir.'Ideal/Seeitperson/etc/config.xml', NULL, LIBXML_NOCDATA) : '';
		
		$result['frontend']['easy_tabs']['version'] = !empty($xml) ? (string)$xml->modules->Ideal_Seeitperson->version : '';
		
		
		
		//-------------------------
		
		
		
		// Jewelry ----------------
		
		
		
		$result['jewelry'] = [];
		
		
		
		// Diamond Search
		
		$xml = file_exists($localDir.'Ideal/Diamondsearch/etc/config.xml') ? simplexml_load_file($localDir.'Ideal/Diamondsearch/etc/config.xml', NULL, LIBXML_NOCDATA) : '';
		
		$result['jewelry']['diamond_search']['version'] = !empty($xml) ? (string)$xml->modules->Ideal_Diamondsearch->version : '';
		
		
		
		// Jewelers Link / Share
		
		$xml = file_exists($communityDir.'Jewelerslink/Jewelryshare/etc/config.xml') ? simplexml_load_file($communityDir.'Jewelerslink/Jewelryshare/etc/config.xml', NULL, LIBXML_NOCDATA) : '';
		
		$result['jewelry']['jewelry_share']['version'] = !empty($xml) ? (string)$xml->modules->Jewelerslink_Jewelryshare->version : '';
		
		
		
		// Eternity Builder
		
		$xml = file_exists($localDir.'Mage/Eternity/etc/config.xml') ? simplexml_load_file($localDir.'Mage/Eternity/etc/config.xml', NULL, LIBXML_NOCDATA) : '';
		
		$result['jewelry']['eternity_builder']['version'] = !empty($xml) ? (string)$xml->modules->Mage_Eternity->version : '';
		
		
		
		// Stud Builder
		
		$xml = file_exists($localDir.'Mage/Stud/etc/config.xml') ? simplexml_load_file($localDir.'Mage/Stud/etc/config.xml', NULL, LIBXML_NOCDATA) : '';
		
		$result['jewelry']['stud_builder']['version'] = !empty($xml) ? (string)$xml->modules->Mage_Stud->version : '';
		
		
		
		//-------------------------
		
		
		
		// Sales ------------------
		
		
		
		$result['sales'] = [];
		
		
		
		// Guest Wishlist
		
		$xml = file_exists($localDir.'IBM/Gwishlish/etc/config.xml') ? simplexml_load_file($localDir.'IBM/Gwishlish/etc/config.xml', NULL, LIBXML_NOCDATA) : '';
		
		$result['sales']['guest_wishlist']['version'] = !empty($xml) ? (string)$xml->modules->IBM_Gwishlist->version : '';
		
		
		
		// Price Waiter
		
		$xml = file_exists($communityDir.'PriceWaiter/NYPWidget/etc/config.xml') ? simplexml_load_file($communityDir.'PriceWaiter/NYPWidget/etc/config.xml', NULL, LIBXML_NOCDATA) : '';
		
		$result['sales']['price_waiter']['version'] = !empty($xml) ? (string)$xml->modules->PriceWaiter_NYPWidget->version : '';
		
		
		
		// ??? Wishlist Notification, Appraisals, Gift Cards, Affirm
		
		
		
		//-------------------------
		
		
		
		// Cms --------------------
		
		
		
		$result['cms'] = [];
		
		
		
		// Revisions
		
		$xml = file_exists($localDir.'Plugincompany/Cmsrevisions/etc/config.xml') ? simplexml_load_file($localDir.'Plugincompany/Cmsrevisions/etc/config.xml', NULL, LIBXML_NOCDATA) : '';
		
		$result['cms']['revisions']['version'] = !empty($xml) ? (string)$xml->modules->Plugincompany_Cmsrevisions->version : '';
		
		
		
		// Slideshow Manager
		
		$xml = file_exists($communityDir.'Dolphin/Slideshow/etc/config.xml') ? simplexml_load_file($communityDir.'Dolphin/Slideshow/etc/config.xml', NULL, LIBXML_NOCDATA) : '';
		
		$result['cms']['slideshow_manager']['version'] = !empty($xml) ? (string)$xml->modules->Dolphin_Slideshow->version : '';
		
		
		
		// Store Locator
		
		$xml = file_exists($communityDir.'Unirgy/StoreLocator/etc/config.xml') ? simplexml_load_file($communityDir.'Unirgy/StoreLocator/etc/config.xml', NULL, LIBXML_NOCDATA) : '';
		
		$result['cms']['store_locator']['version'] = !empty($xml) ? (string)$xml->modules->Unirgy_StoreLocator->version : '';
		
		
		
		// Price Waiter
		
		$xml = file_exists($communityDir.'Dolphin/Slideshow/etc/config.xml') ? simplexml_load_file($communityDir.'Dolphin/Slideshow/etc/config.xml', NULL, LIBXML_NOCDATA) : '';
		
		$result['cms']['slideshow_manager']['version'] = !empty($xml) ? (string)$xml->modules->Dolphin_Slideshow->version : '';
		
		
		
		// Brand Manager
		
		$xml = file_exists($localDir.'Ideal/Brandmanager/etc/config.xml') ? simplexml_load_file($localDir.'Ideal/Brandmanager/etc/config.xml', NULL, LIBXML_NOCDATA) : '';
		
		$result['cms']['revisions']['version'] = !empty($xml) ? (string)$xml->modules->Ideal_Brandmanager->version : '';
		
		
		
		// Gallery
		
		$xml = file_exists($localDir.'Ideal/Videogallery/etc/config.xml') ? simplexml_load_file($localDir.'Ideal/Videogallery/etc/config.xml', NULL, LIBXML_NOCDATA) : '';
		
		$result['cms']['gallery']['version'] = !empty($xml) ? (string)$xml->modules->Ideal_Videogallery->version : '';
		
		
		
		// Events
		
		$xml = file_exists($localDir.'Ideal/Dcevent/etc/config.xml') ? simplexml_load_file($localDir.'Ideal/Dcevent/etc/config.xml', NULL, LIBXML_NOCDATA) : '';
		
		$result['cms']['events']['version'] = !empty($xml) ? (string)$xml->modules->Ideal_Dcevent->version : '';
		
		
		
		// Testimonials
		
		$xml = file_exists($communityDir.'Magebuzz/Testimonial/etc/config.xml') ? simplexml_load_file($communityDir.'Magebuzz/Testimonial/etc/config.xml', NULL, LIBXML_NOCDATA) : '';
		
		$result['cms']['testimonials']['version'] = !empty($xml) ? (string)$xml->modules->Magebuzz_Testimonial->version : '';
		
		
		
		//-------------------------
		
		
		
		// Theme ------------------
		
		
		
		$result['theme'] = [];
		
		
		
		// Evolved Theme
		
		$xml = file_exists($localDir.'Ideal/Evolved/etc/config.xml') ? simplexml_load_file($localDir.'Ideal/Evolved/etc/config.xml', NULL, LIBXML_NOCDATA) : '';
		
		$result['theme']['evolved_theme']['version'] = !empty($xml) ? (string)$xml->modules->Ideal_Evolved->version : '';
		
		
		
		// Builder
		
		$xml = file_exists($localDir.'IBM/Builder/etc/config.xml') ? simplexml_load_file($localDir.'IBM/Builder/etc/config.xml', NULL, LIBXML_NOCDATA) : '';
		
		$result['theme']['builder']['version'] = !empty($xml) ? (string)$xml->modules->IBM_Builder->version : '';
		
		
		
		// ??? Css Editor
		
		
		
		//-------------------------
		
		// SMTP Pro mail count
		
		$todaymonth = date('Y-m');
		
		$qtyQuery = $pdo->prepare("SELECT COUNT(`email_id`) as `mail_qty` FROM `smtppro_email_log` WHERE `log_at` like '".$todaymonth."%' " );
		//echo "SELECT COUNT(`email_id`) as `mail_qty` FROM `smtppro_email_log` WHERE `log_at` like '".$todaymonth."%' ";
		
		$qtyQuery->execute();
		
		$qtyQueryResult = $qtyQuery->fetch(PDO::FETCH_ASSOC);
		
		$result['email']['email_qty']['count'] = $qtyQueryResult['mail_qty'];
		
		
		// SMTP Pro Version
		
		$xml = file_exists($localDir.'Aschroder/SMTPPro/etc/config.xml') ? simplexml_load_file($localDir.'Aschroder/SMTPPro/etc/config.xml', NULL, LIBXML_NOCDATA) : '';
		
		$result['Aschroder']['SMTPPro']['version'] = !empty($xml) ? (string)$xml->modules->Aschroder_SMTPPro->version : '';
		
		
		// SMTP Pro mail setting
		
		$smtp_is_sendgrid = 'smtppro/general/option';
		$smtp_is_sendgrid_username = 'smtppro/general/sendgrid_email';
		
		$smtp_option_Query = $pdo->prepare("SELECT `value` FROM " . $tblprefix . "`core_config_data` WHERE `path` = '".$smtp_is_sendgrid."' " );
		
		$smtp_option_Query->execute();
		
		$smtp_option_QueryResult = $smtp_option_Query->fetch(PDO::FETCH_ASSOC);
		
		$smtp_sendgrid_option = $smtp_option_QueryResult['value'];
		$result['Aschroder']['SMTPPro']['connection'] = $smtp_sendgrid_option;
		
		if ($result['Aschroder']['SMTPPro']['connection'] == 'sendgrid') {
			
			$smtp_sendgrid_Query = $pdo->prepare("SELECT `value` FROM " . $tblprefix . "`core_config_data` WHERE `path` = '".$smtp_is_sendgrid_username."' " );
		
			$smtp_sendgrid_Query->execute();
		
			$smtp_sendgrid_QueryResult = $smtp_sendgrid_Query->fetch(PDO::FETCH_ASSOC);
		
			$smtp_sendgrid_username = $smtp_sendgrid_QueryResult['value'];
		
			$result['Aschroder']['SMTPPro']['username'] = $smtp_sendgrid_username;
		
		}
		
		$isSecure = false;
		if (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] == 'on') {
			$isSecure = true;
		}
		elseif (!empty($_SERVER['HTTP_X_FORWARDED_PROTO']) && $_SERVER['HTTP_X_FORWARDED_PROTO'] == 'https' || !empty($_SERVER['HTTP_X_FORWARDED_SSL']) && $_SERVER['HTTP_X_FORWARDED_SSL'] == 'on') {
			$isSecure = true;
		}
		$REQUEST_PROTOCOL = $isSecure ? 'https' : 'http';
		$result['domain']['request']['protocol'] = $REQUEST_PROTOCOL;
		$REQUEST_URL = $REQUEST_PROTOCOL . '://' . $_SERVER['HTTP_HOST'];
		$result['domain']['request']['url'] = $REQUEST_URL;
		
		/*
		$pro_name = substr($_SERVER['SERVER_NAME'], strpos($_SERVER['SERVER_NAME'], "www.")+4 );
		$QueryOne = $pdo->prepare("UPDATE `projects` SET `smtp_version_info`='".$result['Aschroder']['SMTPPro']['version']."',
			`smtp_connection_info`='".$result['Aschroder']['SMTPPro']['username']."',
			`project_url_info`='".$result['domain']['request']['url']."' WHERE `pro_title` like '%".$pro_name."%' " );
		$QueryOne->execute();
		*/
		//------------------------
		
		
		// New Relic Version
		
		$xml = file_exists($communityDir.'Yireo/NewRelic/etc/config.xml') ? simplexml_load_file($communityDir.'Yireo/NewRelic/etc/config.xml', NULL, LIBXML_NOCDATA) : '';
		
		$result['Yireo']['NewRelic']['version'] = !empty($xml) ? (string)$xml->modules->Yireo_NewRelic->version : '';
		
		// Lite Mage Cache Status
		
		$litemage_cache_main = $pdo->prepare("SELECT `value` FROM " . $tblprefix . "`core_config_data` WHERE `path` = 'advanced/modules_disable_output/Litespeed_Litemage' " );
		$litemage_cache_main->execute();
		$litemage_cache_mainResult = $litemage_cache_main->fetch(PDO::FETCH_ASSOC);
		$litemage_cache_main_option = $litemage_cache_mainResult['value'];
		if($litemage_cache_main_option == 0){
			$litemage_cache_Query = $pdo->prepare("SELECT `value` FROM " . $tblprefix . "`core_config_data` WHERE `path` = 'litemage/general/enabled' " );
			$litemage_cache_Query->execute();
			$litemage_cache_QueryResult = $litemage_cache_Query->fetch(PDO::FETCH_ASSOC);
			$litemage_cache_option = $litemage_cache_QueryResult['value'];
			$result['Litespeed']['Litemage']['status'] = $litemage_cache_option == '1' ? 'true' : 'false';
		} else {
			$result['Litespeed']['Litemage']['status'] = $litemage_cache_main_option == '0' ? 'true' : 'false';
		}
		
		// Lite Mage Cache Version
		
		$xml = file_exists($communityDir.'Litespeed/Litemage/etc/config.xml') ? simplexml_load_file($communityDir.'Litespeed/Litemage/etc/config.xml', NULL, LIBXML_NOCDATA) : '';
		
		$result['Litespeed']['Litemage']['version'] = !empty($xml) ? (string)$xml->modules->Litespeed_Litemage->version : '';
		
		// Url Rewrites Count
		
		$core_url_rewrite = $pdo->prepare("select count(*) as `url_total` from " . $tblprefix . "`core_url_rewrite` " );
		$core_url_rewrite->execute();
		$core_url_rewriteResult = $core_url_rewrite->fetch(PDO::FETCH_ASSOC);
		$core_url_rewrite_option = $core_url_rewriteResult['url_total'];
		$result['core']['url_rewrite']['count'] = $core_url_rewrite_option;
		
		
		// All Product Count
		
		$count_products = $pdo->prepare("SELECT COUNT(`entity_id`) as `qty` FROM " . $tblprefix . "`catalog_product_entity` " );
		//$count_products = $pdo->prepare("SELECT count(entity_id) as prod_total FROM " . $tblprefix . "`catalog_product_entity_int` WHERE attribute_id = ( SELECT attribute_id FROM `eav_attribute` WHERE `attribute_code` LIKE 'status') " );
		$count_products->execute();
		$count_productsResult = $count_products->fetch(PDO::FETCH_ASSOC);
		$count_products_total = $count_productsResult['qty'];
		$result['mage']['product']['count'] = $count_products_total;
		
		echo '2';
		
		// Flat Product Status
		
		$flat_product_status = $pdo->prepare("SELECT * FROM " . $tblprefix . "`core_config_data` WHERE `path` = 'catalog/frontend/flat_catalog_product' " );
		$flat_product_status->execute();
		$flat_product_statusResult = $flat_product_status->fetch(PDO::FETCH_ASSOC);
		$flat_product_status_option = $flat_product_statusResult['value'];
		$result['core']['flat_product']['status'] = $flat_product_status_option == '1' ? 'true' : 'false';
		
		
		// Modules Info ----------
		
		$modulesQueryResult = $pdo->query('SELECT * FROM `core_resource`');
		
		$modules = array();
		
		foreach ($modulesQueryResult as $row) {
			$modules[$row['code']] = array('version' => $row['version'], 'data_version' => $row['data_version']);
		}
		
		$result['installed_modules_dbdata'] = $modules;
		
		$modulesData = Mage::getConfig()->getNode('modules')->children();
		
		$modules = array();
		foreach ($modulesData as $moduleName => $moduleSettings) {
			$modules[$moduleName] = array('is_active' => (string)$moduleSettings->is('active'), 'code_pool' => (string)$moduleSettings->codePool, 'version' => (string)$moduleSettings->version);
		}
		
		$result['installed_modules'] = $modules;
		
		// Builder
		
		/*$xml = simplexml_load_file($localDir.'IBM/Builder/etc/config.xml', NULL, LIBXML_NOCDATA);
		
		$result['modules']['builder']['version'] = !empty($xml) ? (string)$xml->modules->IBM_Builder->version : '';
		
		//------------------------
		
		
		if (!empty($result['modules']['litemage']['version'])) {
		
			$xml = simplexml_load_file('../app/etc/modules/Litespeed_Litemage.xml', NULL, LIBXML_NOCDATA);
		
			$result['modules']['litemage']['is_active'] = !empty($xml) ? (string)$xml->modules->Litespeed_Litemage->active : '';
		
		}*/
		
		// Cron Status
		
		
		
		$cron_status = $pdo->prepare("SELECT * FROM " . $tblprefix . "`cron_schedule` WHERE `status`='success' and `job_code`='aoescheduler_heartbeat' ORDER BY executed_at DESC LIMIT 1 " );
		$cron_status->execute();
		$cron_statusResult = $cron_status->fetch(PDO::FETCH_ASSOC);
		//$cron_status_option = $cron_statusResult['executed_at'];
		$lastHeartbeat = $cron_statusResult['executed_at'];
		if ($lastHeartbeat === false) {
			// no heartbeat task found
			$cron_status_option = 'false';
		} else {
			$timespan = $this->dateDiff($lastHeartbeat);
			$days=floor($timespan/(60*60*24));//seconds/minute*minutes/hour*hours/day)
			$hours=round(($timespan-$days*60*60*24)/(60*60));
			$minutes = floor(($timespan - ($days*24*60*60)-($hours*60*60)) / 60);
			$seconds = ($timespan - ($days*24*60*60) - ($hours*60*60) - ($minutes*60)) % 60;
			if ($timespan <= 5 * 60 * 60) {
				//echo 'Scheduler is working. (Last heart beat: %s minute(s) ago)', round($timespan / 60);
				//echo "Scheduler is working. (Last heartbeat is older than $days days $hours hours $minutes minutes $seconds seconds).<br>";
				$cron_status_option = 'true';
			/*} elseif ($timespan > 5 * 60 && $timespan <= 60 * 60) {
				// heartbeat wasn't executed in the last 5 minutes. Heartbeat schedule could have been modified to not run every five minutes!
				//echo 'Last heartbeat is older than %s minutes.', round($timespan / 60);
				echo "Last heartbeat is older than $days days $hours hours $minutes minutes $seconds seconds.<br>";
			$cron_status_option = 'false2';*/
			} else {
				// everything ok
				//echo 'Last heartbeat is older than one hour. Please check your settings and your configuration!';
				echo "Last heartbeat is older than $days days $hours hours $minutes minutes $seconds seconds.<br>";
				$cron_status_option = 'false';
			}
		}
		$result['core']['cron']['status'] = $cron_status_option;
		
		//------------------------
		
		
		
		$result['build_time'] = time();
		
		
		
		$preparedResult = json_encode($result);
		
		
		
		$res = file_put_contents('info.json', $preparedResult);
		
		if (!$res) {
			echo 'Settings file can not be saved! Check permissions for folder.';
		} else {
			echo '<h3>Settings file successfully saved!</h3>';
		}

	}
	
	public function syshealthCron_old()
	{
		date_default_timezone_set('UTC');

		$config  = Mage::getConfig()->getResourceConnectionConfig('default_setup');
		
		$dbinfo = array('host' => $config->host,
					'user' => $config->username,
					'pass' => $config->password,
					'dbname' => $config->dbname
		);
		
		$dbhost = $dbinfo['host'];
		$dbuser = $dbinfo['user'];
		$dbpass = $dbinfo['pass'];
		$dbname = $dbinfo['dbname'];
		$tblprefix = Mage::getConfig()->getTablePrefix();
		
		$response = array("status"=>false,"msg"=>"");
		
		$pdo = new PDO('mysql:host='.$dbhost.';dbname='.$dbname, $dbuser, $dbpass);
		
		$result = array();
		
		// System -----------------
		
		$result['system'] = [];
		
		// Magento Core
		
		//require '../app/Mage.php';
		
		$result['system']['magento_core']['version'] = Mage::getVersion();
		
		//echo $current_dir = dirname(__FILE__);
		$baseDir = Mage::getBaseDir().DS;
		$ideal_infoDir = $baseDir.'ideal_info'.DS;
		$appDir = $baseDir.'app'.DS;
		$localDir = $appDir.'code'.DS.'local'.DS;
		$communityDir = $appDir.'code'.DS.'community'.DS;
		
		// Evolved Core
		
		$xml = file_exists($localDir.'Ideal/Evolved/etc/config.xml') ? simplexml_load_file($localDir.'Ideal/Evolved/etc/config.xml', NULL, LIBXML_NOCDATA) : '';
		
		$result['system']['evolved_core']['version'] = !empty($xml) ? (string)$xml->modules->Ideal_Evolved->version : '';
		
		
		// LiteMage
		
		$xml = file_exists($communityDir.'Litespeed/Litemage/etc/config.xml') ? simplexml_load_file($communityDir.'Litespeed/Litemage/etc/config.xml', NULL, LIBXML_NOCDATA) : '';
		
		$result['system']['litemage_cache']['version'] = !empty($xml) ? (string)$xml->modules->Litespeed_Litemage->version : '';
		
		
		
		// Training
		
		$xml = file_exists($localDir.'Ideal/Videogallery/etc/config.xml') ? simplexml_load_file($localDir.'Ideal/Videogallery/etc/config.xml', NULL, LIBXML_NOCDATA) : '';
		
		$result['system']['training']['version'] = !empty($xml) ? (string)$xml->modules->Ideal_Videogallery->version : '';
		
		
		
		// Messages
		
		$xml = file_exists($localDir.'Message/Contactform/etc/config.xml') ? simplexml_load_file($localDir.'Message/Contactform/etc/config.xml', NULL, LIBXML_NOCDATA) : '';
		
		$result['system']['messages']['version'] = !empty($xml) ? (string)$xml->modules->Message_Contactform->version : '';
		
		
		
		// Wordpress
		
		$xml = file_exists($communityDir.'Fishpig_Wordpress/etc/config.xml') ? simplexml_load_file($communityDir.'Fishpig_Wordpress/etc/config.xml', NULL, LIBXML_NOCDATA) : '';
		
		$result['system']['wordpress']['version'] = !empty($xml) ? (string)$xml->modules->Fishpig_Wordpress->version : '';
		
		
		
		// Aheadworks Blog
		
		$xml = file_exists($communityDir.'AW/Blog/etc/config.xml') ? simplexml_load_file($communityDir.'AW/Blog/etc/config.xml', NULL, LIBXML_NOCDATA) : '';
		
		$result['system']['aheadworks_blog']['version'] = !empty($xml) ? (string)$xml->modules->AW_Blog->version : '';
		
		
		
		// Cron Scheduler
		
		$xml = file_exists($communityDir.'Aoe/Scheduler/etc/config.xml') ? simplexml_load_file($communityDir.'Aoe/Scheduler/etc/config.xml', NULL, LIBXML_NOCDATA) : '';
		
		$result['system']['cron_scheduler']['version'] = !empty($xml) ? (string)$xml->modules->Aoe_Scheduler->version : '';
		
		
		
		// Image Clean Up
		
		$xml = file_exists($localDir.'Mage/Imaclean/etc/config.xml') ? simplexml_load_file($localDir.'Mage/Imaclean/etc/config.xml', NULL, LIBXML_NOCDATA) : '';
		
		$result['system']['image_cleanup']['version'] = !empty($xml) ? (string)$xml->modules->Mage_Imaclean->version : '';
		
		
		
		// CDN
		
		$xml = file_exists($communityDir.'OnePica/ImageCdn/etc/config.xml') ? simplexml_load_file($communityDir.'OnePica/ImageCdn/etc/config.xml', NULL, LIBXML_NOCDATA) : '';
		
		$result['system']['cdn']['version'] = !empty($xml) ? (string)$xml->modules->OnePica_ImageCdn->version : '';
		
		
		
		// ??? Transactional Emails
		
		
		
		//-------------------------
		
		
		
		// Catalog ----------------
		
		
		
		$result['catalog'] = [];
		
		
		
		// Image 2 Product
		
		$xml = file_exists($localDir.'Mage/Image2Product/etc/config.xml') ? simplexml_load_file($localDir.'Mage/Image2Product/etc/config.xml', NULL, LIBXML_NOCDATA) : '';
		
		$result['catalog']['image_2_product']['version'] = !empty($xml) ? (string)$xml->modules->Mage_Image2Product->version : '';
		
		
		
		// Upload Product
		
		$xml = file_exists($localDir.'Mage/Uploadproduct/etc/config.xml') ? simplexml_load_file($localDir.'Mage/Uploadproduct/etc/config.xml', NULL, LIBXML_NOCDATA) : '';
		
		$result['catalog']['uploadproduct']['version'] = !empty($xml) ? (string)$xml->modules->Mage_Uploadproduct->version : '';
		
		
		
		// Google Sheet Import
		
		$xml = file_exists($localDir.'Ideal/Googlesheetimport/etc/config.xml') ? simplexml_load_file($localDir.'Ideal/Googlesheetimport/etc/config.xml', NULL, LIBXML_NOCDATA) : '';
		
		$result['catalog']['google_sheet_import']['version'] = !empty($xml) ? (string)$xml->modules->Ideal_Googlesheetimport->version : '';
		
		
		
		// Advanced Product Options
		
		$xml = file_exists($localDir.'MageWorx/CustomOptions/etc/config.xml') ? simplexml_load_file($localDir.'MageWorx/CustomOptions/etc/config.xml', NULL, LIBXML_NOCDATA) : '';
		
		$result['catalog']['advanced_product_options']['version'] = !empty($xml) ? (string)$xml->modules->MageWorx_CustomOptions->version : '';
		
		
		
		// Custom Price Steping
		
		$xml = file_exists($localDir.'Mycomp/Pricemanager/etc/config.xml') ? simplexml_load_file($localDir.'Mycomp/Pricemanager/etc/config.xml', NULL, LIBXML_NOCDATA) : '';
		
		$result['catalog']['custom_price_steping']['version'] = !empty($xml) ? (string)$xml->modules->Mycomp_Pricemanager->version : '';
		
		
		
		// External Linking Categories
		
		$xml = file_exists($localDir.'LC/CategoryExternalLink/etc/config.xml') ? simplexml_load_file($localDir.'LC/CategoryExternalLink/etc/config.xml', NULL, LIBXML_NOCDATA) : '';
		
		$result['catalog']['external_linking_categories']['version'] = !empty($xml) ? (string)$xml->modules->LC_CategoryExternalLink->version : '';
		
		
		
		// Category Auto-Assigning
		
		$xml = file_exists($localDir.'Ideal/Categoryassign/etc/config.xml') ? simplexml_load_file($localDir.'Ideal/Categoryassign/etc/config.xml', NULL, LIBXML_NOCDATA) : '';
		
		$result['catalog']['category_auto_assigning']['version'] = !empty($xml) ? (string)$xml->modules->Ideal_Categoryassign->version : '';
		
		
		
		// Call For Price
		
		$xml = file_exists($localDir.'Mfmc/Mfmcallforprice/etc/config.xml') ? simplexml_load_file($localDir.'Mfmc/Mfmcallforprice/etc/config.xml', NULL, LIBXML_NOCDATA) : '';
		
		$result['catalog']['call_for_price']['version'] = !empty($xml) ? (string)$xml->modules->Mfmc_Mfmcallforprice->version : '';
		
		
		
		// ??? Catalog Management Plus
		
		
		
		//-------------------------
		
		
		
		// Marketing --------------
		
		
		
		$result['marketing'] = [];
		
		
		
		// Mailchimp
		
		$xml = file_exists($communityDir.'Ebizmarts/MageMonkey/etc/config.xml') ? simplexml_load_file($communityDir.'Ebizmarts/MageMonkey/etc/config.xml', NULL, LIBXML_NOCDATA) : '';
		
		$result['marketing']['mailchimp_integration']['version'] = !empty($xml) ? (string)$xml->modules->Ebizmarts_MageMonkey->version : '';
		
		
		
		// Live Chat
		
		$xml = file_exists($localDir.'Ideal/Livechatadmin/etc/config.xml') ? simplexml_load_file($localDir.'Ideal/Livechatadmin/etc/config.xml', NULL, LIBXML_NOCDATA) : '';
		
		$result['marketing']['live_chat']['version'] = !empty($xml) ? (string)$xml->modules->Ideal_Livechatadmin->version : '';
		
		
		
		// Newsletter Pop-up
		
		$xml = file_exists($communityDir.'ES/Newssubscribers/etc/config.xml') ? simplexml_load_file($communityDir.'ES/Newssubscribers/etc/config.xml', NULL, LIBXML_NOCDATA) : '';
		
		$result['marketing']['newsletter_popup']['version'] = !empty($xml) ? (string)$xml->modules->ES_Newssubscribers->version : '';
		
		
		
		// ??? Evo SEO, Custom Headers, Custom Attributes, Guest 2 Customer, Order By Customer, Social Media Tags
		
		
		
		//-------------------------
		
		
		
		// Front End --------------
		
		
		
		$result['frontend'] = [];
		
		
		
		// Easy Tabs
		
		$xml = file_exists($communityDir.'TM/EasyTabs/etc/config.xml') ? simplexml_load_file($communityDir.'TM/EasyTabs/etc/config.xml', NULL, LIBXML_NOCDATA) : '';
		
		$result['frontend']['see_it_person']['version'] = !empty($xml) ? (string)$xml->modules->TM_EasyTabs->version : '';
		
		
		
		// See It Person
		
		$xml = file_exists($localDir.'Ideal/Seeitperson/etc/config.xml') ? simplexml_load_file($localDir.'Ideal/Seeitperson/etc/config.xml', NULL, LIBXML_NOCDATA) : '';
		
		$result['frontend']['easy_tabs']['version'] = !empty($xml) ? (string)$xml->modules->Ideal_Seeitperson->version : '';
		
		
		
		//-------------------------
		
		
		
		// Jewelry ----------------
		
		
		
		$result['jewelry'] = [];
		
		
		
		// Diamond Search
		
		$xml = file_exists($localDir.'Ideal/Diamondsearch/etc/config.xml') ? simplexml_load_file($localDir.'Ideal/Diamondsearch/etc/config.xml', NULL, LIBXML_NOCDATA) : '';
		
		$result['jewelry']['diamond_search']['version'] = !empty($xml) ? (string)$xml->modules->Ideal_Diamondsearch->version : '';
		
		
		
		// Jewelers Link / Share
		
		$xml = file_exists($communityDir.'Jewelerslink/Jewelryshare/etc/config.xml') ? simplexml_load_file($communityDir.'Jewelerslink/Jewelryshare/etc/config.xml', NULL, LIBXML_NOCDATA) : '';
		
		$result['jewelry']['jewelry_share']['version'] = !empty($xml) ? (string)$xml->modules->Jewelerslink_Jewelryshare->version : '';
		
		
		
		// Eternity Builder
		
		$xml = file_exists($localDir.'Mage/Eternity/etc/config.xml') ? simplexml_load_file($localDir.'Mage/Eternity/etc/config.xml', NULL, LIBXML_NOCDATA) : '';
		
		$result['jewelry']['eternity_builder']['version'] = !empty($xml) ? (string)$xml->modules->Mage_Eternity->version : '';
		
		
		
		// Stud Builder
		
		$xml = file_exists($localDir.'Mage/Stud/etc/config.xml') ? simplexml_load_file($localDir.'Mage/Stud/etc/config.xml', NULL, LIBXML_NOCDATA) : '';
		
		$result['jewelry']['stud_builder']['version'] = !empty($xml) ? (string)$xml->modules->Mage_Stud->version : '';
		
		
		
		//-------------------------
		
		
		
		// Sales ------------------
		
		
		
		$result['sales'] = [];
		
		
		
		// Guest Wishlist
		
		$xml = file_exists($localDir.'IBM/Gwishlish/etc/config.xml') ? simplexml_load_file($localDir.'IBM/Gwishlish/etc/config.xml', NULL, LIBXML_NOCDATA) : '';
		
		$result['sales']['guest_wishlist']['version'] = !empty($xml) ? (string)$xml->modules->IBM_Gwishlist->version : '';
		
		
		
		// Price Waiter
		
		$xml = file_exists($communityDir.'PriceWaiter/NYPWidget/etc/config.xml') ? simplexml_load_file($communityDir.'PriceWaiter/NYPWidget/etc/config.xml', NULL, LIBXML_NOCDATA) : '';
		
		$result['sales']['price_waiter']['version'] = !empty($xml) ? (string)$xml->modules->PriceWaiter_NYPWidget->version : '';
		
		
		
		// ??? Wishlist Notification, Appraisals, Gift Cards, Affirm
		
		
		
		//-------------------------
		
		
		
		// Cms --------------------
		
		
		
		$result['cms'] = [];
		
		
		
		// Revisions
		
		$xml = file_exists($localDir.'Plugincompany/Cmsrevisions/etc/config.xml') ? simplexml_load_file($localDir.'Plugincompany/Cmsrevisions/etc/config.xml', NULL, LIBXML_NOCDATA) : '';
		
		$result['cms']['revisions']['version'] = !empty($xml) ? (string)$xml->modules->Plugincompany_Cmsrevisions->version : '';
		
		
		
		// Slideshow Manager
		
		$xml = file_exists($communityDir.'Dolphin/Slideshow/etc/config.xml') ? simplexml_load_file($communityDir.'Dolphin/Slideshow/etc/config.xml', NULL, LIBXML_NOCDATA) : '';
		
		$result['cms']['slideshow_manager']['version'] = !empty($xml) ? (string)$xml->modules->Dolphin_Slideshow->version : '';
		
		
		
		// Store Locator
		
		$xml = file_exists($communityDir.'Unirgy/StoreLocator/etc/config.xml') ? simplexml_load_file($communityDir.'Unirgy/StoreLocator/etc/config.xml', NULL, LIBXML_NOCDATA) : '';
		
		$result['cms']['store_locator']['version'] = !empty($xml) ? (string)$xml->modules->Unirgy_StoreLocator->version : '';
		
		
		
		// Price Waiter
		
		$xml = file_exists($communityDir.'Dolphin/Slideshow/etc/config.xml') ? simplexml_load_file($communityDir.'Dolphin/Slideshow/etc/config.xml', NULL, LIBXML_NOCDATA) : '';
		
		$result['cms']['slideshow_manager']['version'] = !empty($xml) ? (string)$xml->modules->Dolphin_Slideshow->version : '';
		
		
		
		// Brand Manager
		
		$xml = file_exists($localDir.'Ideal/Brandmanager/etc/config.xml') ? simplexml_load_file($localDir.'Ideal/Brandmanager/etc/config.xml', NULL, LIBXML_NOCDATA) : '';
		
		$result['cms']['revisions']['version'] = !empty($xml) ? (string)$xml->modules->Ideal_Brandmanager->version : '';
		
		
		
		// Gallery
		
		$xml = file_exists($localDir.'Ideal/Videogallery/etc/config.xml') ? simplexml_load_file($localDir.'Ideal/Videogallery/etc/config.xml', NULL, LIBXML_NOCDATA) : '';
		
		$result['cms']['gallery']['version'] = !empty($xml) ? (string)$xml->modules->Ideal_Videogallery->version : '';
		
		
		
		// Events
		
		$xml = file_exists($localDir.'Ideal/Dcevent/etc/config.xml') ? simplexml_load_file($localDir.'Ideal/Dcevent/etc/config.xml', NULL, LIBXML_NOCDATA) : '';
		
		$result['cms']['events']['version'] = !empty($xml) ? (string)$xml->modules->Ideal_Dcevent->version : '';
		
		
		
		// Testimonials
		
		$xml = file_exists($communityDir.'Magebuzz/Testimonial/etc/config.xml') ? simplexml_load_file($communityDir.'Magebuzz/Testimonial/etc/config.xml', NULL, LIBXML_NOCDATA) : '';
		
		$result['cms']['testimonials']['version'] = !empty($xml) ? (string)$xml->modules->Magebuzz_Testimonial->version : '';
		
		
		
		//-------------------------
		
		
		
		// Theme ------------------
		
		
		
		$result['theme'] = [];
		
		
		
		// Evolved Theme
		
		$xml = file_exists($localDir.'Ideal/Evolved/etc/config.xml') ? simplexml_load_file($localDir.'Ideal/Evolved/etc/config.xml', NULL, LIBXML_NOCDATA) : '';
		
		$result['theme']['evolved_theme']['version'] = !empty($xml) ? (string)$xml->modules->Ideal_Evolved->version : '';
		
		
		
		// Builder
		
		$xml = file_exists($localDir.'IBM/Builder/etc/config.xml') ? simplexml_load_file($localDir.'IBM/Builder/etc/config.xml', NULL, LIBXML_NOCDATA) : '';
		
		$result['theme']['builder']['version'] = !empty($xml) ? (string)$xml->modules->IBM_Builder->version : '';
		
		
		
		// ??? Css Editor
		
		
		
		//-------------------------
		
		// SMTP Pro mail count
		
		$todaymonth = date('Y-m');
		
		$qtyQuery = $pdo->prepare("SELECT COUNT(`email_id`) as `mail_qty` FROM `" . $tblprefix . "smtppro_email_log` WHERE `log_at` like '".$todaymonth."%' " );
		//echo "SELECT COUNT(`email_id`) as `mail_qty` FROM `smtppro_email_log` WHERE `log_at` like '".$todaymonth."%' ";
		
		$qtyQuery->execute();
		
		$qtyQueryResult = $qtyQuery->fetch(PDO::FETCH_ASSOC);
		
		$result['email']['email_qty']['count'] = $qtyQueryResult['mail_qty'];
		
		
		// SMTP Pro mail count
		
		
		
		// Products
		
		/*$qtyQuery = $pdo->prepare('SELECT COUNT(`entity_id`) as `qty` FROM `catalog_product_entity`');
		
		$qtyQuery->execute();
		
		$qtyQueryResult = $qtyQuery->fetch(PDO::FETCH_ASSOC);
		
		$result['magento']['products_qty'] = $qtyQueryResult['qty'];
		
		
		
		// SSL
		
		$useSSLQuery = $pdo->prepare('SELECT `value` FROM `core_config_data` WHERE `path` = \'web/secure/use_in_frontend\'');
		
		$useSSLQuery->execute();
		
		$useSSLQueryResult = $useSSLQuery->fetch(PDO::FETCH_ASSOC);
		
		$result['magento']['ssl_on'] = $useSSLQueryResult['value'] == '1' ? 'true' : 'false';
		
		
		
		// URL Rewrites
		
		$rewritesQuery = $pdo->prepare('SELECT `value` FROM `core_config_data` WHERE `path` = \'web/seo/use_rewrites\'');
		
		$rewritesQuery->execute();
		
		$rewritesQueryResult = $rewritesQuery->fetch(PDO::FETCH_ASSOC);
		
		$result['magento']['url_rewrites_on'] = $rewritesQueryResult['value'] == '1' ? 'true' : 'false';
		
		
		
		// Count URL Rewrites
		
		$rewritesQueryCount = $pdo->prepare('SELECT count(*) FROM `core_url_rewrite`');
		
		$rewritesQueryCount->execute();
		
		$rewritesQueryCountResult = $rewritesQueryCount->fetch(PDO::FETCH_ASSOC);
		
		$result['magento']['url_rewrites_count'] = $rewritesQueryCountResult;
		
		
		
		// Cache
		
		$cacheQuery = $pdo->prepare('SELECT `value` FROM `core_cache_option` WHERE `code` = \'block_html\'');
		
		$cacheQuery->execute();
		
		$cacheQueryResult = $cacheQuery->fetch(PDO::FETCH_ASSOC);
		
		$result['magento']['cache'] = $cacheQueryResult['value'] == '1' ? 'true' : 'false';*/
		
		
		
		//------------------------
		
		
		
		// Modules Info ----------
		
		
		
		//$modulesQueryResult = $pdo->query('SELECT * FROM `core_resource`');
		
		//
		
		//$modules = array();
		
		//foreach ($modulesQueryResult as $row) {
		
		//    $modules[$row['code']] = array('version' => $row['version'], 'data_version' => $row['data_version']);
		
		//}
		
		$modulesQueryResult = $pdo->query('SELECT * FROM `' . $tblprefix . 'core_resource`');
		
		$modules = array();
		
		foreach ($modulesQueryResult as $row) {
			$modules[$row['code']] = array('version' => $row['version'], 'data_version' => $row['data_version']);
		}
		
		$result['installed_modules'] = $modules;
		
		
		
		// Builder
		
		/*$xml = simplexml_load_file($localDir.'IBM/Builder/etc/config.xml', NULL, LIBXML_NOCDATA);
		
		$result['modules']['builder']['version'] = !empty($xml) ? (string)$xml->modules->IBM_Builder->version : '';
		
		//------------------------
		
		
		
		
		
		
		
		if (!empty($result['modules']['litemage']['version'])) {
		
			$xml = simplexml_load_file('../app/etc/modules/Litespeed_Litemage.xml', NULL, LIBXML_NOCDATA);
		
			$result['modules']['litemage']['is_active'] = !empty($xml) ? (string)$xml->modules->Litespeed_Litemage->active : '';
		
		}*/
		
		// SMTP Pro Version
		
		$xml = file_exists($localDir.'Aschroder/SMTPPro/etc/config.xml') ? simplexml_load_file($localDir.'Aschroder/SMTPPro/etc/config.xml', NULL, LIBXML_NOCDATA) : '';
		
		$result['Aschroder']['SMTPPro']['version'] = !empty($xml) ? (string)$xml->modules->Aschroder_SMTPPro->version : '';
		
		
		// SMTP Pro mail setting
		
		$smtp_is_sendgrid = 'smtppro/general/option';
		$smtp_is_sendgrid_username = 'smtppro/general/sendgrid_email';
		
		$smtp_option_Query = $pdo->prepare("SELECT `value` FROM `" . $tblprefix . "core_config_data` WHERE `path` = '".$smtp_is_sendgrid."' " );
		
		$smtp_option_Query->execute();
		
		$smtp_option_QueryResult = $smtp_option_Query->fetch(PDO::FETCH_ASSOC);
		
		$smtp_sendgrid_option = $smtp_option_QueryResult['value'];
		$result['Aschroder']['SMTPPro']['connection'] = $smtp_sendgrid_option;
		
		if ($result['Aschroder']['SMTPPro']['connection'] == 'sendgrid') {
			
			$smtp_sendgrid_Query = $pdo->prepare("SELECT `value` FROM `" . $tblprefix . "core_config_data` WHERE `path` = '".$smtp_is_sendgrid_username."' " );
		
			$smtp_sendgrid_Query->execute();
		
			$smtp_sendgrid_QueryResult = $smtp_sendgrid_Query->fetch(PDO::FETCH_ASSOC);
		
			$smtp_sendgrid_username = $smtp_sendgrid_QueryResult['value'];
		
			$result['Aschroder']['SMTPPro']['username'] = $smtp_sendgrid_username;
		
		}
		
		$isSecure = false;
		
		if (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] == 'on') {
			$isSecure = true;
		}
		elseif (!empty($_SERVER['HTTP_X_FORWARDED_PROTO']) && $_SERVER['HTTP_X_FORWARDED_PROTO'] == 'https' || !empty($_SERVER['HTTP_X_FORWARDED_SSL']) && $_SERVER['HTTP_X_FORWARDED_SSL'] == 'on') {
			$isSecure = true;
		}
		$REQUEST_PROTOCOL = $isSecure ? 'https' : 'http';
		$result['domain']['request']['protocol'] = $REQUEST_PROTOCOL;
		$REQUEST_URL = $REQUEST_PROTOCOL . '://' . $_SERVER['HTTP_HOST'];
		$result['domain']['request']['url'] = $REQUEST_URL;
		
		/*
		$pro_name = substr($_SERVER['SERVER_NAME'], strpos($_SERVER['SERVER_NAME'], "www.")+4 );
		$QueryOne = $pdo->prepare("UPDATE `projects` SET `smtp_version_info`='".$result['Aschroder']['SMTPPro']['version']."',
			`smtp_connection_info`='".$result['Aschroder']['SMTPPro']['username']."',
			`project_url_info`='".$result['domain']['request']['url']."' WHERE `pro_title` like '%".$pro_name."%' " );
		$QueryOne->execute();
		*/
		//------------------------
		
		
		// New Relic Version
		
		$xml = file_exists($communityDir.'Yireo/NewRelic/etc/config.xml') ? simplexml_load_file($communityDir.'Yireo/NewRelic/etc/config.xml', NULL, LIBXML_NOCDATA) : '';
		
		$result['Yireo']['NewRelic']['version'] = !empty($xml) ? (string)$xml->modules->Yireo_NewRelic->version : '';
		
		$New_Relic_status = $pdo->prepare("SELECT * FROM `" . $tblprefix . "core_config_data` WHERE `path` = 'newrelic/settings/enabled' " );
		$New_Relic_status->execute();
		$New_Relic_statusResult = $New_Relic_status->fetch(PDO::FETCH_ASSOC);
		$New_Relic_status_option = $New_Relic_statusResult['value'];
		$result['Yireo']['NewRelic']['status'] = $New_Relic_status_option == '1' ? 'true' : 'false';
		
		
		// Lite Mage Cache Status
		// Check Status in .htaccess file
		$litemage_htaccess_Enabled = $this->litemage_htaccess_licenseEnabled();
		/*if ( ! $litemage_htaccess_Enabled ) {
			echo '<br>Your installation of LiteSpeed Web Server does not have LiteMage Cache enabled. Please make sure your LiteSpeed license includes the LiteMage cache module, and LiteMage is turned on in  the .htaccess file in the root directory of your Magento installation.<br>' ;
		} else {
			echo "<br>lite mage enabled.<br>";
		}*/
		
		$litemage_cache_main = $pdo->prepare("SELECT `value` FROM `" . $tblprefix . "core_config_data` WHERE `path` = 'advanced/modules_disable_output/Litespeed_Litemage' " );
		$litemage_cache_main->execute();
		$litemage_cache_mainResult = $litemage_cache_main->fetch(PDO::FETCH_ASSOC);
		$litemage_cache_main_option = $litemage_cache_mainResult['value'];
		if($litemage_cache_main_option == 0){
			$litemage_cache_Query = $pdo->prepare("SELECT `value` FROM `" . $tblprefix . "core_config_data` WHERE `path` = 'litemage/general/enabled' " );
			$litemage_cache_Query->execute();
			$litemage_cache_QueryResult = $litemage_cache_Query->fetch(PDO::FETCH_ASSOC);
			$litemage_cache_option = $litemage_cache_QueryResult['value'];
				//$result['Litespeed']['Litemage']['status'] = $litemage_cache_option == '1' ? 'true' : 'false';
			if(($litemage_cache_option == 0) || ( ! $litemage_htaccess_Enabled )){
				$result['Litespeed']['Litemage']['status'] = 'false';
			} else {
				$result['Litespeed']['Litemage']['status'] = 'true';
			}
		} else {
			$result['Litespeed']['Litemage']['status'] = $litemage_cache_main_option == '0' ? 'true' : 'false';
		}
		
		// Lite Mage Cache Version
		
		$xml = file_exists($communityDir.'Litespeed/Litemage/etc/config.xml') ? simplexml_load_file($communityDir.'Litespeed/Litemage/etc/config.xml', NULL, LIBXML_NOCDATA) : '';
		
		$result['Litespeed']['Litemage']['version'] = !empty($xml) ? (string)$xml->modules->Litespeed_Litemage->version : '';
		
		
		// Url Rewrites Count
		
		$core_url_rewrite = $pdo->prepare("select count(*) as `url_total` from `" . $tblprefix . "core_url_rewrite` " );
		$core_url_rewrite->execute();
		$core_url_rewriteResult = $core_url_rewrite->fetch(PDO::FETCH_ASSOC);
		$core_url_rewrite_option = $core_url_rewriteResult['url_total'];
		$result['core']['url_rewrite']['count'] = $core_url_rewrite_option;
		
		
		// All Product Count
		
		$count_products = $pdo->prepare("SELECT COUNT(`entity_id`) as `qty` FROM `" . $tblprefix . "catalog_product_entity` " );
		//$count_products = $pdo->prepare("SELECT count(entity_id) as prod_total FROM `" . $tblprefix . "catalog_product_entity_int` WHERE attribute_id = ( SELECT attribute_id FROM `eav_attribute` WHERE `attribute_code` LIKE 'status') " );
		$count_products->execute();
		$count_productsResult = $count_products->fetch(PDO::FETCH_ASSOC);
		$count_products_total = $count_productsResult['qty'];
		$result['mage']['product']['count'] = $count_products_total;
		
		// Diamond Upldated Date
		
		//$flat_product_status = $pdo->prepare("SELECT * FROM `" . $tblprefix . "core_config_data` WHERE `path` = 'catalog/frontend/flat_catalog_product' " );
		//~ $flat_product_status = $pdo->prepare("SELECT * information_schema.tables WHERE TABLE_NAME = `" . $tblprefix . "uploadtool_diamonds_inventory`" );
		//~ $flat_product_status->execute();
		//~ $flat_product_statusResult = $flat_product_status->fetch(PDO::FETCH_ASSOC);
		//~ print_r($flat_product_statusResult);
		$uploadtool_diamonds_inventory = Mage::getSingleton("core/resource")->getTableName('uploadtool_diamonds_inventory');
		$connection = Mage::getSingleton('core/resource')->getConnection('core_read');
		$sql = "SELECT * FROM information_schema.tables WHERE TABLE_NAME = '".$uploadtool_diamonds_inventory."' AND TABLE_SCHEMA ='".$dbname."'";
		$update = $connection->fetchAll($sql);
		$lastUpdate = $update[0]['UPDATE_TIME']; 
		if($lastUpdate == "") $lastUpdate = $update[0]['CREATE_TIME'];
		$lastUpdate = explode(' ',$lastUpdate);
		$result['core']['diamond_updated']['date'] = $lastUpdate[0];
		
		
		
		// Flat Product Status
		
		$flat_product_status = $pdo->prepare("SELECT * FROM `" . $tblprefix . "core_config_data` WHERE `path` = 'catalog/frontend/flat_catalog_product' " );
		$flat_product_status->execute();
		$flat_product_statusResult = $flat_product_status->fetch(PDO::FETCH_ASSOC);
		$flat_product_status_option = $flat_product_statusResult['value'];
		$result['core']['flat_product']['status'] = $flat_product_status_option == '1' ? 'true' : 'false';
		
		
		// CDN
		
		$cdn_base_url_tmp = Mage::getBaseUrl();
		$cdn_base_url_pos1 = strpos($cdn_base_url_tmp, ".") + 1;
		$cdn_base_url_pos2 = strrpos($cdn_base_url_tmp, "/");
		$cdn_base_url = substr($cdn_base_url_tmp,$cdn_base_url_pos1,$cdn_base_url_pos2 - $cdn_base_url_pos1);
		
		$cdn_base_media_url_tmp = Mage::getBaseUrl(Mage_Core_Model_Store::URL_TYPE_MEDIA);
		$cdn_base_media_url_pos1 = strpos($cdn_base_media_url_tmp, ".") + 1;
		$cdn_base_media_url_tmp1 = substr($cdn_base_media_url_tmp,$cdn_base_media_url_pos1);
		
		$cdn_base_media_url_tmp_pos1 = strpos($cdn_base_media_url_tmp1, "/");
		$cdn_base_media_url = substr($cdn_base_media_url_tmp1,0,$cdn_base_media_url_tmp_pos1);
		
		if($cdn_base_url == $cdn_base_media_url){
			$result['core']['cdn']['status'] = 'false';
		} else {
			$result['core']['cdn']['status'] = 'true';
		}
		
		
		// Cron Status
		
		
		$cron_status = $pdo->prepare("SELECT * FROM `" . $tblprefix . "cron_schedule` WHERE `status`='success' and `job_code`='aoescheduler_heartbeat' ORDER BY executed_at DESC LIMIT 1 " );
		$cron_status->execute();
		$cron_statusResult = $cron_status->fetch(PDO::FETCH_ASSOC);
		//$cron_status_option = $cron_statusResult['executed_at'];
		$lastHeartbeat = $cron_statusResult['executed_at'];
		if ($lastHeartbeat === false) {
			// no heartbeat task found
			$cron_status_option = 'false';
		} else {
			$timespan = $this->dateDiff($lastHeartbeat);
			$days=floor($timespan/(60*60*24));//seconds/minute*minutes/hour*hours/day)
			$hours=round(($timespan-$days*60*60*24)/(60*60));
			$minutes = floor(($timespan - ($days*24*60*60)-($hours*60*60)) / 60);
			$seconds = ($timespan - ($days*24*60*60) - ($hours*60*60) - ($minutes*60)) % 60;
			if ($timespan <= 5 * 60 * 60) {

				//echo "Scheduler is working. (Last heartbeat is older than $days days $hours hours $minutes minutes $seconds seconds).<br>";
				$response["msg"] = $response["msg"]." Scheduler is working. (Last heartbeat is older than $days days $hours hours $minutes minutes $seconds seconds).";
				
				$cron_status_option = 'true';
			/*} elseif ($timespan > 5 * 60 && $timespan <= 60 * 60) {
				// heartbeat wasn't executed in the last 5 minutes. Heartbeat schedule could have been modified to not run every five minutes!
				//echo 'Last heartbeat is older than %s minutes.', round($timespan / 60);
				echo "Last heartbeat is older than $days days $hours hours $minutes minutes $seconds seconds.<br>";
			$cron_status_option = 'false2';*/
			} else {
				// everything ok
				
				//echo "Last heartbeat is older than $days days $hours hours $minutes minutes $seconds seconds.<br>";
				
				$response["msg"] = $response["msg"]." Last heartbeat is older than $days days $hours hours $minutes minutes $seconds seconds.";
				
				$cron_status_option = 'false';
			}
		}
		$result['core']['cron']['status'] = $cron_status_option;
		
		
		// Self Sign SSL
		
		//echo $_SERVER['HTTP_HOST'];
		
		// Build Time
		
		$result['build_time'] = time();
		
		
		$preparedResult = json_encode($result);
		//echo "<pre>"; print_r($preparedResult); echo "</pre>";
		
		
		//$res = file_put_contents('system_health.json', $preparedResult);
		$res = file_put_contents($ideal_infoDir.'system_health.json', $preparedResult);
		
		echo "<pre>"; print_r($response); exit;
		if (!$result) {
			$response["msg"] = $response["msg"]." Settings file can not be saved! Check permissions for folder.";
			return $response;
		} else {
			$response["msg"] = $response["msg"]." Settings file successfully saved";
			$response["status"] = true;
			return $response;
		}
	}
	
	public function litemage_htaccess_licenseEnabled()
	{
		if ( (isset($_SERVER['X-LITEMAGE']) && $_SERVER['X-LITEMAGE']) // for lsws
				|| (isset($_SERVER['HTTP_X_LITEMAGE']) && $_SERVER['HTTP_X_LITEMAGE'])) { // lslb
			return true;
		}
		else {
			return false ;
		}
	}

	
	
	/**
	 * Diff between to times;
	 *
	 * @param $time1
	 * @param $time2
	 * @return int
	 */
	public function dateDiff($time1, $time2 = null)
	{
		if (is_null($time2)) {
			$time2 = date('Y-m-d h:m:s');
		}
		$time1 = strtotime($time1);
		$time2 = strtotime($time2);
		return $time2 - $time1;
	}
	
	
	public function syshealthCron()
	{
		 
		//do something
		date_default_timezone_set('UTC');
		//require_once($baseDir.'/app/Mage.php'); //Path to Magento
		//umask(0);
		//Mage::app();
		$baseDir=Mage::getBaseDir();
		//echo $baseDir;
		if (file_exists($baseDir.'/app/etc/local.xml')) {

		$xml = simplexml_load_file($baseDir.'/app/etc/local.xml');

		$tblprefix = $xml->global->resources->db->table_prefix;
		$dbhost = $xml->global->resources->default_setup->connection->host;
		$dbuser = $xml->global->resources->default_setup->connection->username;
		$dbpass = $xml->global->resources->default_setup->connection->password;
		$dbname = $xml->global->resources->default_setup->connection->dbname;

		}

		else {
			exit('Failed to open local.xml');
		}


		$pdo = new PDO('mysql:host='.$dbhost.';dbname='.$dbname, $dbuser, $dbpass);
		$result = array();

	
		$result['system'] = [];


		$result['system']['magento_core']['version'] = Mage::getVersion();



		// Evolved Core

		$xml = file_exists($baseDir.'app/code/local/Ideal/Evolved/etc/config.xml') ? simplexml_load_file($baseDir.'/app/code/local/Ideal/Evolved/etc/config.xml', NULL, LIBXML_NOCDATA) : '';

		$result['system']['evolved_core']['version'] = !empty($xml) ? (string)$xml->modules->Ideal_Evolved->version : '';



		// LiteMage

		$xml = file_exists($baseDir.'/app/code/community/Litespeed/Litemage/etc/config.xml') ? simplexml_load_file($baseDir.'/app/code/community/Litespeed/Litemage/etc/config.xml', NULL, LIBXML_NOCDATA) : '';

		$result['system']['litemage_cache']['version'] = !empty($xml) ? (string)$xml->modules->Litespeed_Litemage->version : '';



		// Training

		$xml = file_exists($baseDir.'/app/code/local/Ideal/Videogallery/etc/config.xml') ? simplexml_load_file($baseDir.'/app/code/local/Ideal/Videogallery/etc/config.xml', NULL, LIBXML_NOCDATA) : '';

		$result['system']['training']['version'] = !empty($xml) ? (string)$xml->modules->Ideal_Videogallery->version : '';



		// Messages

		$xml = file_exists($baseDir.'/app/code/local/Message/Contactform/etc/config.xml') ? simplexml_load_file($baseDir.'/app/code/local/Message/Contactform/etc/config.xml', NULL, LIBXML_NOCDATA) : '';

		$result['system']['messages']['version'] = !empty($xml) ? (string)$xml->modules->Message_Contactform->version : '';



		// Wordpress

		$xml = file_exists($baseDir.'/app/code/community/Fishpig_Wordpress/etc/config.xml') ? simplexml_load_file($baseDir.'/app/code/community/Fishpig_Wordpress/etc/config.xml', NULL, LIBXML_NOCDATA) : '';

		$result['system']['wordpress']['version'] = !empty($xml) ? (string)$xml->modules->Fishpig_Wordpress->version : '';



		// Aheadworks Blog

		$xml = file_exists($baseDir.'/app/code/community/AW/Blog/etc/config.xml') ? simplexml_load_file($baseDir.'/app/code/community/AW/Blog/etc/config.xml', NULL, LIBXML_NOCDATA) : '';

		$result['system']['aheadworks_blog']['version'] = !empty($xml) ? (string)$xml->modules->AW_Blog->version : '';



		// Cron Scheduler

		$xml = file_exists($baseDir.'/app/code/community/Aoe/Scheduler/etc/config.xml') ? simplexml_load_file($baseDir.'/app/code/community/Aoe/Scheduler/etc/config.xml', NULL, LIBXML_NOCDATA) : '';

		$result['system']['cron_scheduler']['version'] = !empty($xml) ? (string)$xml->modules->Aoe_Scheduler->version : '';



		// Image Clean Up

		$xml = file_exists($baseDir.'/app/code/local/Mage/Imaclean/etc/config.xml') ? simplexml_load_file($baseDir.'/app/code/local/Mage/Imaclean/etc/config.xml', NULL, LIBXML_NOCDATA) : '';

		$result['system']['image_cleanup']['version'] = !empty($xml) ? (string)$xml->modules->Mage_Imaclean->version : '';



		// CDN

		$xml = file_exists($baseDir.'/app/code/community/OnePica/ImageCdn/etc/config.xml') ? simplexml_load_file($baseDir.'/app/code/community/OnePica/ImageCdn/etc/config.xml', NULL, LIBXML_NOCDATA) : '';

		$result['system']['cdn']['version'] = !empty($xml) ? (string)$xml->modules->OnePica_ImageCdn->version : '';



		// ??? Transactional Emails



		//-------------------------



		// Catalog ----------------



		$result['catalog'] = [];



		// Image 2 Product

		$xml = file_exists($baseDir.'/app/code/local/Mage/Image2Product/etc/config.xml') ? simplexml_load_file($baseDir.'/app/code/local/Mage/Image2Product/etc/config.xml', NULL, LIBXML_NOCDATA) : '';

		$result['catalog']['image_2_product']['version'] = !empty($xml) ? (string)$xml->modules->Mage_Image2Product->version : '';



		// Upload Product

		$xml = file_exists($baseDir.'/app/code/local/Mage/Uploadproduct/etc/config.xml') ? simplexml_load_file($baseDir.'/app/code/local/Mage/Uploadproduct/etc/config.xml', NULL, LIBXML_NOCDATA) : '';

		$result['catalog']['uploadproduct']['version'] = !empty($xml) ? (string)$xml->modules->Mage_Uploadproduct->version : '';



		// Google Sheet Import

		$xml = file_exists($baseDir.'/app/code/local/Ideal/Googlesheetimport/etc/config.xml') ? simplexml_load_file($baseDir.'/app/code/local/Ideal/Googlesheetimport/etc/config.xml', NULL, LIBXML_NOCDATA) : '';

		$result['catalog']['google_sheet_import']['version'] = !empty($xml) ? (string)$xml->modules->Ideal_Googlesheetimport->version : '';



		// Advanced Product Options

		$xml = file_exists($baseDir.'/app/code/local/MageWorx/CustomOptions/etc/config.xml') ? simplexml_load_file($baseDir.'/app/code/local/MageWorx/CustomOptions/etc/config.xml', NULL, LIBXML_NOCDATA) : '';

		$result['catalog']['advanced_product_options']['version'] = !empty($xml) ? (string)$xml->modules->MageWorx_CustomOptions->version : '';



		// Custom Price Steping

		$xml = file_exists($baseDir.'/app/code/local/Mycomp/Pricemanager/etc/config.xml') ? simplexml_load_file($baseDir.'/app/code/local/Mycomp/Pricemanager/etc/config.xml', NULL, LIBXML_NOCDATA) : '';

		$result['catalog']['custom_price_steping']['version'] = !empty($xml) ? (string)$xml->modules->Mycomp_Pricemanager->version : '';



		// External Linking Categories

		$xml = file_exists($baseDir.'/app/code/local/LC/CategoryExternalLink/etc/config.xml') ? simplexml_load_file($baseDir.'/app/code/local/LC/CategoryExternalLink/etc/config.xml', NULL, LIBXML_NOCDATA) : '';

		$result['catalog']['external_linking_categories']['version'] = !empty($xml) ? (string)$xml->modules->LC_CategoryExternalLink->version : '';



		// Category Auto-Assigning

		$xml = file_exists($baseDir.'/app/code/local/Ideal/Categoryassign/etc/config.xml') ? simplexml_load_file($baseDir.'/app/code/local/Ideal/Categoryassign/etc/config.xml', NULL, LIBXML_NOCDATA) : '';

		$result['catalog']['category_auto_assigning']['version'] = !empty($xml) ? (string)$xml->modules->Ideal_Categoryassign->version : '';



		// Call For Price

		$xml = file_exists($baseDir.'/app/code/local/Mfmc/Mfmcallforprice/etc/config.xml') ? simplexml_load_file($baseDir.'/app/code/local/Mfmc/Mfmcallforprice/etc/config.xml', NULL, LIBXML_NOCDATA) : '';

		$result['catalog']['call_for_price']['version'] = !empty($xml) ? (string)$xml->modules->Mfmc_Mfmcallforprice->version : '';



		// ??? Catalog Management Plus



		//-------------------------



		// Marketing --------------



		$result['marketing'] = [];



		// Mailchimp

		$xml = file_exists($baseDir.'/app/code/community/Ebizmarts/MageMonkey/etc/config.xml') ? simplexml_load_file($baseDir.'/app/code/community/Ebizmarts/MageMonkey/etc/config.xml', NULL, LIBXML_NOCDATA) : '';

		$result['marketing']['mailchimp_integration']['version'] = !empty($xml) ? (string)$xml->modules->Ebizmarts_MageMonkey->version : '';



		// Live Chat

		$xml = file_exists($baseDir.'/app/code/local/Ideal/Livechatadmin/etc/config.xml') ? simplexml_load_file($baseDir.'/app/code/local/Ideal/Livechatadmin/etc/config.xml', NULL, LIBXML_NOCDATA) : '';

		$result['marketing']['live_chat']['version'] = !empty($xml) ? (string)$xml->modules->Ideal_Livechatadmin->version : '';



		// Newsletter Pop-up

		$xml = file_exists($baseDir.'/app/code/community/ES/Newssubscribers/etc/config.xml') ? simplexml_load_file($baseDir.'/app/code/community/ES/Newssubscribers/etc/config.xml', NULL, LIBXML_NOCDATA) : '';

		$result['marketing']['newsletter_popup']['version'] = !empty($xml) ? (string)$xml->modules->ES_Newssubscribers->version : '';



		// ??? Evo SEO, Custom Headers, Custom Attributes, Guest 2 Customer, Order By Customer, Social Media Tags



		//-------------------------



		// Front End --------------



		$result['frontend'] = [];



		// Easy Tabs

		$xml = file_exists($baseDir.'/app/code/community/TM/EasyTabs/etc/config.xml') ? simplexml_load_file($baseDir.'/app/code/community/TM/EasyTabs/etc/config.xml', NULL, LIBXML_NOCDATA) : '';

		$result['frontend']['see_it_person']['version'] = !empty($xml) ? (string)$xml->modules->TM_EasyTabs->version : '';



		// See It Person

		$xml = file_exists($baseDir.'/app/code/local/Ideal/Seeitperson/etc/config.xml') ? simplexml_load_file($baseDir.'/app/code/local/Ideal/Seeitperson/etc/config.xml', NULL, LIBXML_NOCDATA) : '';

		$result['frontend']['easy_tabs']['version'] = !empty($xml) ? (string)$xml->modules->Ideal_Seeitperson->version : '';



		//-------------------------



		// Jewelry ----------------



		$result['jewelry'] = [];



		// Diamond Search

		$xml = file_exists($baseDir.'/app/code/local/Ideal/Diamondsearch/etc/config.xml') ? simplexml_load_file($baseDir.'/app/code/local/Ideal/Diamondsearch/etc/config.xml', NULL, LIBXML_NOCDATA) : '';

		$result['jewelry']['diamond_search']['version'] = !empty($xml) ? (string)$xml->modules->Ideal_Diamondsearch->version : '';



		// Jewelers Link / Share

		$xml = file_exists($baseDir.'/app/code/community/Jewelerslink/Jewelryshare/etc/config.xml') ? simplexml_load_file($baseDir.'/app/code/community/Jewelerslink/Jewelryshare/etc/config.xml', NULL, LIBXML_NOCDATA) : '';

		$result['jewelry']['jewelry_share']['version'] = !empty($xml) ? (string)$xml->modules->Jewelerslink_Jewelryshare->version : '';



		// Eternity Builder

		$xml = file_exists($baseDir.'/app/code/local/Mage/Eternity/etc/config.xml') ? simplexml_load_file($baseDir.'/app/code/local/Mage/Eternity/etc/config.xml', NULL, LIBXML_NOCDATA) : '';

		$result['jewelry']['eternity_builder']['version'] = !empty($xml) ? (string)$xml->modules->Mage_Eternity->version : '';



		// Stud Builder

		$xml = file_exists($baseDir.'/app/code/local/Mage/Stud/etc/config.xml') ? simplexml_load_file($baseDir.'/app/code/local/Mage/Stud/etc/config.xml', NULL, LIBXML_NOCDATA) : '';

		$result['jewelry']['stud_builder']['version'] = !empty($xml) ? (string)$xml->modules->Mage_Stud->version : '';



		//-------------------------



		// Sales ------------------



		$result['sales'] = [];



		// Guest Wishlist

		$xml = file_exists($baseDir.'/app/code/local/IBM/Gwishlish/etc/config.xml') ? simplexml_load_file($baseDir.'/app/code/local/IBM/Gwishlish/etc/config.xml', NULL, LIBXML_NOCDATA) : '';

		$result['sales']['guest_wishlist']['version'] = !empty($xml) ? (string)$xml->modules->IBM_Gwishlist->version : '';



		// Price Waiter

		$xml = file_exists($baseDir.'/app/code/community/PriceWaiter/NYPWidget/etc/config.xml') ? simplexml_load_file($baseDir.'/app/code/community/PriceWaiter/NYPWidget/etc/config.xml', NULL, LIBXML_NOCDATA) : '';

		$result['sales']['price_waiter']['version'] = !empty($xml) ? (string)$xml->modules->PriceWaiter_NYPWidget->version : '';



		// ??? Wishlist Notification, Appraisals, Gift Cards, Affirm



		//-------------------------



		// Cms --------------------



		$result['cms'] = [];



		// Revisions

		$xml = file_exists($baseDir.'/app/code/local/Plugincompany/Cmsrevisions/etc/config.xml') ? simplexml_load_file($baseDir.'/app/code/local/Plugincompany/Cmsrevisions/etc/config.xml', NULL, LIBXML_NOCDATA) : '';

		$result['cms']['revisions']['version'] = !empty($xml) ? (string)$xml->modules->Plugincompany_Cmsrevisions->version : '';



		// Slideshow Manager

		$xml = file_exists($baseDir.'/app/code/community/Dolphin/Slideshow/etc/config.xml') ? simplexml_load_file($baseDir.'/app/code/community/Dolphin/Slideshow/etc/config.xml', NULL, LIBXML_NOCDATA) : '';

		$result['cms']['slideshow_manager']['version'] = !empty($xml) ? (string)$xml->modules->Dolphin_Slideshow->version : '';



		// Store Locator

		$xml = file_exists($baseDir.'/app/code/community/Unirgy/StoreLocator/etc/config.xml') ? simplexml_load_file($baseDir.'/app/code/community/Unirgy/StoreLocator/etc/config.xml', NULL, LIBXML_NOCDATA) : '';

		$result['cms']['store_locator']['version'] = !empty($xml) ? (string)$xml->modules->Unirgy_StoreLocator->version : '';



		// Price Waiter

		$xml = file_exists($baseDir.'/app/code/community/Dolphin/Slideshow/etc/config.xml') ? simplexml_load_file($baseDir.'/app/code/community/Dolphin/Slideshow/etc/config.xml', NULL, LIBXML_NOCDATA) : '';

		$result['cms']['slideshow_manager']['version'] = !empty($xml) ? (string)$xml->modules->Dolphin_Slideshow->version : '';



		// Brand Manager

		$xml = file_exists($baseDir.'/app/code/local/Ideal/Brandmanager/etc/config.xml') ? simplexml_load_file($baseDir.'/app/code/local/Ideal/Brandmanager/etc/config.xml', NULL, LIBXML_NOCDATA) : '';

		$result['cms']['revisions']['version'] = !empty($xml) ? (string)$xml->modules->Ideal_Brandmanager->version : '';



		// Gallery

		$xml = file_exists($baseDir.'/app/code/local/Ideal/Videogallery/etc/config.xml') ? simplexml_load_file($baseDir.'/app/code/local/Ideal/Videogallery/etc/config.xml', NULL, LIBXML_NOCDATA) : '';

		$result['cms']['gallery']['version'] = !empty($xml) ? (string)$xml->modules->Ideal_Videogallery->version : '';



		// Events

		$xml = file_exists($baseDir.'/app/code/local/Ideal/Dcevent/etc/config.xml') ? simplexml_load_file($baseDir.'/app/code/local/Ideal/Dcevent/etc/config.xml', NULL, LIBXML_NOCDATA) : '';

		$result['cms']['events']['version'] = !empty($xml) ? (string)$xml->modules->Ideal_Dcevent->version : '';



		// Testimonials

		$xml = file_exists($baseDir.'/app/code/community/Magebuzz/Testimonial/etc/config.xml') ? simplexml_load_file($baseDir.'/app/code/community/Magebuzz/Testimonial/etc/config.xml', NULL, LIBXML_NOCDATA) : '';

		$result['cms']['testimonials']['version'] = !empty($xml) ? (string)$xml->modules->Magebuzz_Testimonial->version : '';



		//-------------------------



		// Theme ------------------



		$result['theme'] = [];



		// Evolved Theme

		$xml = file_exists($baseDir.'/app/code/local/Ideal/Evolved/etc/config.xml') ? simplexml_load_file($baseDir.'/app/code/local/Ideal/Evolved/etc/config.xml', NULL, LIBXML_NOCDATA) : '';

		$result['theme']['evolved_theme']['version'] = !empty($xml) ? (string)$xml->modules->Ideal_Evolved->version : '';



		// Builder

		$xml = file_exists($baseDir.'/app/code/local/IBM/Builder/etc/config.xml') ? simplexml_load_file($baseDir.'/app/code/local/IBM/Builder/etc/config.xml', NULL, LIBXML_NOCDATA) : '';

		$result['theme']['builder']['version'] = !empty($xml) ? (string)$xml->modules->IBM_Builder->version : '';



		// ??? Css Editor



		//-------------------------

		// SMTP Pro mail count

		$todaymonth = date('Y-m');

		$qtyQuery = $pdo->prepare("SELECT COUNT(`email_id`) as `mail_qty` FROM `" . $tblprefix . "smtppro_email_log` WHERE `log_at` like '".$todaymonth."%' " );
		//echo "SELECT COUNT(`email_id`) as `mail_qty` FROM `smtppro_email_log` WHERE `log_at` like '".$todaymonth."%' ";

		$qtyQuery->execute();

		$qtyQueryResult = $qtyQuery->fetch(PDO::FETCH_ASSOC);

		$result['email']['email_qty']['count'] = $qtyQueryResult['mail_qty'];


		// SMTP Pro mail count




		$modulesQueryResult = $pdo->query('SELECT * FROM `' . $tblprefix . 'core_resource`');

		$modules = array();

		foreach ($modulesQueryResult as $row) {
			$modules[$row['code']] = array('version' => $row['version'], 'data_version' => $row['data_version']);
		}

		$result['installed_modules'] = $modules;





 



		// SMTP Pro Version

		$xml = file_exists($baseDir.'/app/code/local/Aschroder/SMTPPro/etc/config.xml') ? simplexml_load_file($baseDir.'/app/code/local/Aschroder/SMTPPro/etc/config.xml', NULL, LIBXML_NOCDATA) : '';

		$result['Aschroder']['SMTPPro']['version'] = !empty($xml) ? (string)$xml->modules->Aschroder_SMTPPro->version : '';


		// SMTP Pro mail setting

		$smtp_is_sendgrid = 'smtppro/general/option';
		$smtp_is_sendgrid_username = 'smtppro/general/sendgrid_email';

		$smtp_option_Query = $pdo->prepare("SELECT `value` FROM `" . $tblprefix . "core_config_data` WHERE `path` = '".$smtp_is_sendgrid."' " );

		$smtp_option_Query->execute();

		$smtp_option_QueryResult = $smtp_option_Query->fetch(PDO::FETCH_ASSOC);

		$smtp_sendgrid_option = $smtp_option_QueryResult['value'];
		$result['Aschroder']['SMTPPro']['connection'] = $smtp_sendgrid_option;

		if ($result['Aschroder']['SMTPPro']['connection'] == 'sendgrid') {

			$smtp_sendgrid_Query = $pdo->prepare("SELECT `value` FROM `" . $tblprefix . "core_config_data` WHERE `path` = '".$smtp_is_sendgrid_username."' " );

			$smtp_sendgrid_Query->execute();

			$smtp_sendgrid_QueryResult = $smtp_sendgrid_Query->fetch(PDO::FETCH_ASSOC);

			$smtp_sendgrid_username = $smtp_sendgrid_QueryResult['value'];

			$result['Aschroder']['SMTPPro']['username'] = $smtp_sendgrid_username;

		}

		$isSecure = false;
		if (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] == 'on') {
			$isSecure = true;
		}
		elseif (!empty($_SERVER['HTTP_X_FORWARDED_PROTO']) && $_SERVER['HTTP_X_FORWARDED_PROTO'] == 'https' || !empty($_SERVER['HTTP_X_FORWARDED_SSL']) && $_SERVER['HTTP_X_FORWARDED_SSL'] == 'on') {
			$isSecure = true;
		}
		$REQUEST_PROTOCOL = $isSecure ? 'https' : 'http';
		$result['domain']['request']['protocol'] = $REQUEST_PROTOCOL;
		$REQUEST_URL = $REQUEST_PROTOCOL . '://' . $_SERVER['HTTP_HOST'];
		$result['domain']['request']['url'] = $REQUEST_URL;

 


		// New Relic Version

		$xml = file_exists($baseDir.'/app/code/community/Yireo/NewRelic/etc/config.xml') ? simplexml_load_file($baseDir.'/app/code/community/Yireo/NewRelic/etc/config.xml', NULL, LIBXML_NOCDATA) : '';

		$result['Yireo']['NewRelic']['version'] = !empty($xml) ? (string)$xml->modules->Yireo_NewRelic->version : '';

		$New_Relic_status = $pdo->prepare("SELECT * FROM `" . $tblprefix . "core_config_data` WHERE `path` = 'newrelic/settings/enabled' " );
		$New_Relic_status->execute();
		$New_Relic_statusResult = $New_Relic_status->fetch(PDO::FETCH_ASSOC);
		$New_Relic_status_option = $New_Relic_statusResult['value'];
		$result['Yireo']['NewRelic']['status'] = $New_Relic_status_option == '1' ? 'true' : 'false';



		$litemage_htaccess_Enabled = $this->litemage_htaccess_licenseEnabled();
		/*if ( ! $litemage_htaccess_Enabled ) {
			echo '<br>Your installation of LiteSpeed Web Server does not have LiteMage Cache enabled. Please make sure your LiteSpeed license includes the LiteMage cache module, and LiteMage is turned on in  the .htaccess file in the root directory of your Magento installation.<br>' ;
		} else {
			echo "<br>lite mage enabled.<br>";
		}*/

		$litemage_cache_main = $pdo->prepare("SELECT `value` FROM `" . $tblprefix . "core_config_data` WHERE `path` = 'advanced/modules_disable_output/Litespeed_Litemage' " );
		$litemage_cache_main->execute();
		$litemage_cache_mainResult = $litemage_cache_main->fetch(PDO::FETCH_ASSOC);
		$litemage_cache_main_option = $litemage_cache_mainResult['value'];
		if($litemage_cache_main_option == 0){
			$litemage_cache_Query = $pdo->prepare("SELECT `value` FROM `" . $tblprefix . "core_config_data` WHERE `path` = 'litemage/general/enabled' " );
			$litemage_cache_Query->execute();
			$litemage_cache_QueryResult = $litemage_cache_Query->fetch(PDO::FETCH_ASSOC);
			$litemage_cache_option = $litemage_cache_QueryResult['value'];
				//$result['Litespeed']['Litemage']['status'] = $litemage_cache_option == '1' ? 'true' : 'false';
			if(($litemage_cache_option == 0) || ( ! $litemage_htaccess_Enabled )){
				$result['Litespeed']['Litemage']['status'] = 'false';
			} else {
				$result['Litespeed']['Litemage']['status'] = 'true';
			}
		} else {
			$result['Litespeed']['Litemage']['status'] = $litemage_cache_main_option == '0' ? 'true' : 'false';
		}

		// Lite Mage Cache Version

		$xml = file_exists($baseDir.'/app/code/community/Litespeed/Litemage/etc/config.xml') ? simplexml_load_file($baseDir.'/app/code/community/Litespeed/Litemage/etc/config.xml', NULL, LIBXML_NOCDATA) : '';

		$result['Litespeed']['Litemage']['version'] = !empty($xml) ? (string)$xml->modules->Litespeed_Litemage->version : '';


		// Url Rewrites Count

		$core_url_rewrite = $pdo->prepare("select count(*) as `url_total` from `" . $tblprefix . "core_url_rewrite` " );
		$core_url_rewrite->execute();
		$core_url_rewriteResult = $core_url_rewrite->fetch(PDO::FETCH_ASSOC);
		$core_url_rewrite_option = $core_url_rewriteResult['url_total'];
		$result['core']['url_rewrite']['count'] = $core_url_rewrite_option;


		// All Product Count

		$count_products = $pdo->prepare("SELECT COUNT(`entity_id`) as `qty` FROM `" . $tblprefix . "catalog_product_entity` " );
		//$count_products = $pdo->prepare("SELECT count(entity_id) as prod_total FROM `" . $tblprefix . "catalog_product_entity_int` WHERE attribute_id = ( SELECT attribute_id FROM `eav_attribute` WHERE `attribute_code` LIKE 'status') " );
		$count_products->execute();
		$count_productsResult = $count_products->fetch(PDO::FETCH_ASSOC);
		$count_products_total = $count_productsResult['qty'];
		$result['mage']['product']['count'] = $count_products_total;

		// Diamond Upldated Date

		//$flat_product_status = $pdo->prepare("SELECT * FROM `" . $tblprefix . "core_config_data` WHERE `path` = 'catalog/frontend/flat_catalog_product' " );
		//~ $flat_product_status = $pdo->prepare("SELECT * information_schema.tables WHERE TABLE_NAME = `" . $tblprefix . "uploadtool_diamonds_inventory`" );
		//~ $flat_product_status->execute();
		//~ $flat_product_statusResult = $flat_product_status->fetch(PDO::FETCH_ASSOC);
		//~ print_r($flat_product_statusResult);
		$uploadtool_diamonds_inventory = Mage::getSingleton("core/resource")->getTableName('uploadtool_diamonds_inventory');
		$connection = Mage::getSingleton('core/resource')->getConnection('core_read');
		$sql = "SELECT * FROM information_schema.tables WHERE TABLE_NAME = '".$uploadtool_diamonds_inventory."' AND TABLE_SCHEMA ='".$dbname."'";
		$update = $connection->fetchAll($sql);
		$lastUpdate = $update[0]['UPDATE_TIME']; 
		if($lastUpdate == "") $lastUpdate = $update[0]['CREATE_TIME'];
		$lastUpdate = explode(' ',$lastUpdate);
		$result['core']['diamond_updated']['date'] = $lastUpdate[0];



		// Flat Product Status

		$flat_product_status = $pdo->prepare("SELECT * FROM `" . $tblprefix . "core_config_data` WHERE `path` = 'catalog/frontend/flat_catalog_product' " );
		$flat_product_status->execute();
		$flat_product_statusResult = $flat_product_status->fetch(PDO::FETCH_ASSOC);
		$flat_product_status_option = $flat_product_statusResult['value'];
		$result['core']['flat_product']['status'] = $flat_product_status_option == '1' ? 'true' : 'false';


		// CDN

		$cdn_base_url_tmp = Mage::getBaseUrl();
		$cdn_base_url_pos1 = strpos($cdn_base_url_tmp, ".") + 1;
		$cdn_base_url_pos2 = strrpos($cdn_base_url_tmp, "/");
		$cdn_base_url = substr($cdn_base_url_tmp,$cdn_base_url_pos1,$cdn_base_url_pos2 - $cdn_base_url_pos1);

		$cdn_base_media_url_tmp = Mage::getBaseUrl(Mage_Core_Model_Store::URL_TYPE_MEDIA);
		$cdn_base_media_url_pos1 = strpos($cdn_base_media_url_tmp, ".") + 1;
		$cdn_base_media_url_tmp1 = substr($cdn_base_media_url_tmp,$cdn_base_media_url_pos1);

		$cdn_base_media_url_tmp_pos1 = strpos($cdn_base_media_url_tmp1, "/");
		$cdn_base_media_url = substr($cdn_base_media_url_tmp1,0,$cdn_base_media_url_tmp_pos1);

		if($cdn_base_url == $cdn_base_media_url){
			$result['core']['cdn']['status'] = 'false';
		} else {
			$result['core']['cdn']['status'] = 'true';
		}

		


				$cron_status = $pdo->prepare("SELECT * FROM `" . $tblprefix . "cron_schedule` WHERE `status`='success' and `job_code`='aoescheduler_heartbeat' ORDER BY executed_at DESC LIMIT 1 " );
		$cron_status->execute();
		$cron_statusResult = $cron_status->fetch(PDO::FETCH_ASSOC);
		//$cron_status_option = $cron_statusResult['executed_at'];
		$lastHeartbeat = $cron_statusResult['executed_at'];
		if ($lastHeartbeat === false) {
			// no heartbeat task found
			$cron_status_option = 'false';
		} else {
			$timespan = $this->dateDiff($lastHeartbeat);
			$days=floor($timespan/(60*60*24));//seconds/minute*minutes/hour*hours/day)
			$hours=round(($timespan-$days*60*60*24)/(60*60));
			$minutes = floor(($timespan - ($days*24*60*60)-($hours*60*60)) / 60);
			$seconds = ($timespan - ($days*24*60*60) - ($hours*60*60) - ($minutes*60)) % 60;
			if ($timespan <= 5 * 60 * 60) {
				//echo 'Scheduler is working. (Last heart beat: %s minute(s) ago)', round($timespan / 60);
			//	echo "Scheduler is working. (Last heartbeat is older than $days days $hours hours $minutes minutes $seconds seconds).<br>";
				$cron_status_option = 'true';
			/*} elseif ($timespan > 5 * 60 && $timespan <= 60 * 60) {
				// heartbeat wasn't executed in the last 5 minutes. Heartbeat schedule could have been modified to not run every five minutes!
				//echo 'Last heartbeat is older than %s minutes.', round($timespan / 60);
				echo "Last heartbeat is older than $days days $hours hours $minutes minutes $seconds seconds.<br>";
			$cron_status_option = 'false2';*/
			} else {
				// everything ok
				//echo 'Last heartbeat is older than one hour. Please check your settings and your configuration!';
			//	echo "Last heartbeat is older than $days days $hours hours $minutes minutes $seconds seconds.<br>";
				$cron_status_option = 'false';
			}
		}
		$result['core']['cron']['status'] = $cron_status_option;


		// Self Sign SSL

		//echo $_SERVER['HTTP_HOST'];

		// Build Time

		$result['build_time'] = time();


		$preparedResult = json_encode($result);
		//echo "<pre>"; print_r($preparedResult); echo "</pre>";


		$res = file_put_contents('system_health.json', $preparedResult);


		/*
		if (!$result) {

			echo 'Settings file can not be saved! Check permissions for folder.';

		} else {

			echo '<h3>Settings file successfully saved!</h3>';

		}*/
		
		if (!$result) {
			$response["msg"] = $response["msg"]." Settings file can not be saved! Check permissions for folder.";
			return $response;
		} else {
			$response["msg"] = $response["msg"]." Settings file successfully saved";
			$response["status"] = true;
			return $response;
		}
		
		
		
	} 
}