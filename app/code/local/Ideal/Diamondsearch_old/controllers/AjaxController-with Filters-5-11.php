<?php
class Ideal_Diamondsearch_AjaxController extends Mage_Core_Controller_Front_Action
{
    public function indexAction()
    {
    	$diamondsCollection = Mage::getModel("diamondsearch/diamondsearch")->getCollection();

			$diamondsCollection->addFieldToFilter('shape', array('in' => explode(",", $_REQUEST["shapes"])));
			$diamondsCollection->addFieldToFilter('id', array(
			'from' => 1200,
			'to' => 1203,
			));
			$diamondsCollection->setOrder("id", 'desc');
			//echo $diamondsCollection->getSelect();
			
			//echo "<pre>";
			//print_r($diamondsCollection->getData());
    }
    public function listAction()
    {
		/***** fetching configs *****/
    	//shape
    	$shapeConfig = unserialize(Mage::getStoreConfig("diamondsearch/shape_settings/shape_available"));
    	//FOR PHP 5.3 usort($shapeConfig, function($a, $b) {return $a['sortorder'] - $b['sortorder'];});
    	$shapesLabelArray = array();
    	foreach ($shapeConfig as $_item):
    		$shapesArray[] = $_item["label"];
    	endforeach;
		//color
		$colorSliderConfig = unserialize(Mage::getStoreConfig("diamondsearch/slider_settings/color_slider"));
		//usort($colorSliderConfig, 'sortByOrder');
		$colorsArray = array();
		foreach ($colorSliderConfig as $_item):
			 $colorsArray[] = $_item["label"];
		endforeach;
		//fancycolor
		$fancycolorSliderConfig = unserialize(Mage::getStoreConfig("diamondsearch/slider_settings/fancycolor_slider"));
		$fancycolorsArray = array();
		foreach ($fancycolorSliderConfig as $_item):
			 $fancycolorsArray[] = $_item["label"];
		endforeach;
		//clarity
		$claritySliderConfig = unserialize(Mage::getStoreConfig("diamondsearch/slider_settings/clarity_slider"));
		$claritiesArray = array();
		foreach ($claritySliderConfig as $_item):
			 $claritiesArray[] = $_item["label"];
		endforeach;
		//cut
		$cutSliderConfig = unserialize(Mage::getStoreConfig("diamondsearch/slider_settings/cut_slider"));
		$cutsArray = array();
		foreach ($cutSliderConfig as $_item):
			 $cutsArray[] = $_item["label"];
		endforeach;
		//fluorescence
		$fluorescenceSliderConfig = unserialize(Mage::getStoreConfig("diamondsearch/slider_settings/fluorescence_slider"));
		$fluorescencesArray = array();
		foreach ($fluorescenceSliderConfig as $_item):
			 $fluorescencesArray[] = $_item["label"];
		endforeach;
		//certificate
		$certificateSliderConfig = unserialize(Mage::getStoreConfig("diamondsearch/slider_settings/certificate_slider"));
		$certificatesArray = array();
		foreach ($certificateSliderConfig as $_item):
			 $certificatesArray[] = $_item["label"];
		endforeach;
		/***** fetching configs ENDS *****/
		
		$diamondsCollection = Mage::getModel("diamondsearch/diamondsearch")->getCollection();
		
		if(isset($_REQUEST["shapes"]))
		{
			//print_r(explode(",", $_REQUEST["shapes"]));
			if(!in_array("All", explode(",", $_REQUEST["shapes"])))
				$diamondsCollection->addFieldToFilter('trim(shape)', array('in' => explode(",", $_REQUEST["shapes"])));
			else
				$diamondsCollection->addFieldToFilter('trim(shape)', array('in' => $shapesArray));
		}
		if(isset($_REQUEST["min_carat"]) && isset($_REQUEST["max_carat"]) )
		{
			$diamondsCollection->addFieldToFilter('carat', array(
				'from' => $_REQUEST['min_carat'],
				'to' => $_REQUEST['max_carat'],
			));
		}
		if(isset($_REQUEST["min_price"]) && isset($_REQUEST["max_price"]) )
		{
			$diamondsCollection->addFieldToFilter('totalprice', array(
				'from' => $_REQUEST['min_price'],
				'to' => $_REQUEST['max_price'],
			));
		}
		if(isset($_REQUEST["colors"]) && $_REQUEST["is_fancy"] == 0)
		{
			/*if($_REQUEST["colors"] == "L-Z")
				$diamondsCollection->addFieldToFilter('color', array('nin' => array("D", "E", "F", "G", "H", "I", "J", "K")));
			else
				$diamondsCollection->addFieldToFilter('color', array('in' => explode(",", $_REQUEST["colors"])));*/
			$colors = explode(",", $_REQUEST["colors"]);
			$likeColors = array();
			for($i=0;$i<count($colors);$i++)
			{
				if (strpos($colors[$i],'-') !== false) { //check for range, eg. L-Z
					$clrs = explode("-", $colors[$i]);
					$clrs = range($clrs[0], $clrs[1]);
					for($r = 0; $r< count($clrs); $r++)
						//$likeColors[] = array('like' => $clrs[$r]."%");
						$likeColors[] = array('eq' => $clrs[$r]);
				}
				else{
					//$likeColors[] = array('like' => $colors[$i]."%");
					$likeColors[] = array('eq' => $colors[$i]);
				}
			}
			$diamondsCollection->addFieldToFilter('trim(color)', $likeColors);
		}
		elseif(isset($_REQUEST["fancycolors"]) && $_REQUEST["is_fancy"] == 1)
		{
			$diamondsCollection->addFieldToFilter('trim(fancycolor)', array('in' => Mage::helper("diamondsearch")->getShortCodes("fancycolor", explode(",", $_REQUEST["fancycolors"])) ));
		}
		//if(isset($_REQUEST["clarities"]) && count($claritiesArray) != count(explode(",", $_REQUEST["clarities"]))) 
		if(isset($_REQUEST["clarities"]))
		{
			$diamondsCollection->addFieldToFilter('trim(clarity)', array('in' => explode(",", $_REQUEST["clarities"])));
		}
		//if(isset($_REQUEST["cuts"]) && count($cutsArray) != count(explode(",", $_REQUEST["cuts"])))
		if(isset($_REQUEST["cuts"]))
		{
			$diamondsCollection->addFieldToFilter('trim(cut)', array('in' => Mage::helper("diamondsearch")->getShortCodes("cut", explode(",", $_REQUEST["cuts"])) ));
		}
		//if(isset($_REQUEST["fluorescences"]) && count($fluorescencesArray) != count(explode(",", $_REQUEST["fluorescences"])))
		if(isset($_REQUEST["fluorescences"]))
		{
			$diamondsCollection->addFieldToFilter('trim(fluorescence)', array('in' => Mage::helper("diamondsearch")->getShortCodes("fluorescence", explode(",", $_REQUEST["fluorescences"])) ));
		}
		/*if(isset($_REQUEST["min_ratio"]) && isset($_REQUEST["max_ratio"]) )
		{
			$where[] = " AND `ratio` BETWEEN '".$_REQUEST['min_price']."' AND '".$_REQUEST['max_price']."' ";
		}*/
		//if(isset($_REQUEST["reports"]) && count($certificatesArray) != count(explode(",", $_REQUEST["reports"])))
		if(isset($_REQUEST["reports"]))
		{
			/*if($_REQUEST["reports"] == "Non-Certified")
				$diamondsCollection->addFieldToFilter('certificate', array('eq' => ""));
			else
				$diamondsCollection->addFieldToFilter('certificate', array('in' => explode(",", $_REQUEST["reports"])));*/
			$reports = explode(",", $_REQUEST["reports"]);
			$likeReports = array();
			for($i=0;$i<count($reports);$i++)
			{
				if($reports[$i] == "Non-Certified")
				{
					$likeReports[] = array('like' => "NONE%");
					$likeReports[] = array('like' => "");
				}
				else
					$likeReports[] = array('like' => $reports[$i]."%");
			}
			$diamondsCollection->addFieldToFilter('trim(certificate)', $likeReports);
		}
		else
		{
			$reports = $certificatesArray;
			for($i=0;$i<count($reports);$i++)
			{
				if($reports[$i] == "Non-Certified")
				{
					$likeReports[] = array('like' => "NONE%");
					$likeReports[] = array('like' => "");
				}
				else
					$likeReports[] = array('like' => $reports[$i]."%");
			}
			$diamondsCollection->addFieldToFilter('trim(certificate)', $likeReports);
		}
		
		if(isset($_REQUEST["shipping_day"])) //shipping_day is STOCK_NUMBER here
		{
			$diamondsCollection->addFieldToFilter('trim(lotno)', array('like' => "%".$_REQUEST["shipping_day"]."%"));
		}
		
		$orderby = "totalprice";
		if($_REQUEST["order_by"])
		{
			$orderby = $_REQUEST["order_by"];
			if($_REQUEST["order_by"] == "price")
				$orderby = "totalprice";
			else if($_REQUEST["order_by"] == "report")
				$orderby = "certificate";
			else if($_REQUEST["order_by"] == "color" && $_REQUEST["is_fancy"] == 1)
				$orderby = "fancycolor";
		}
		
		$orderMethod = "ASC";
		if($_REQUEST["order_method"])
		{
			$orderMethod = $_REQUEST["order_method"];
		}
		$diamondsCollection->setOrder($orderby, $orderMethod);
		//$coll1 = clone $diamondsCollection;
		$total_count = $diamondsCollection->getsize();
		//echo "ROW: ".$_REQUEST["row"];
		$diamondsCollection->getSelect()->limit($_REQUEST["requestedDataSize"], (int)$_REQUEST["row"]);
		$stones = $diamondsCollection->getData();
	
		//echo $selQuery = $diamondsCollection->getSelect()." CNT: ".count($stones);
		/*echo $diamondsCollection->getSelect();
		echo "CNT: ".count($stones);
		echo "<pre>";
		//print_r($stones);
		exit;*/
		
		if($_REQUEST["is_mobile"]){
			$dHtml = '<div class="container top-tweny-list" style="">';
			$dHtml .= Mage::helper("diamondsearch")->getDiamondsHtml($stones, $_REQUEST["is_fancy"]);
			$dHtml .= '</div>';
			$rw = ( ((int)$_REQUEST["row"]) + 10);
			$rds = $_REQUEST["requestedDataSize"];
			if($total_count - $rw > $_REQUEST["requestedDataSize"])
				$rds = $_REQUEST["requestedDataSize"];
			else 
				$rds = $total_count - $rw;
			$dHtml .= '<input class="freshLastOnPage hiddenData" type="hidden" value="'.$rw.'" />';
			$dHtml .= '<input class="freshTotalOnPage hiddenData" type="hidden" value="'.$total_count.'" />';
			$smd = "{'row': ".$rw.", 'requestedDataSize': ".$rds.", 'formerUrl': '?".htmlspecialchars($_SERVER['QUERY_STRING'])."','show_more': 'True'}";
			
			if($rw >= $total_count){
				$dHtml .= '<script type="text/javascript">
							window.onscroll = null
							</script>';
			}else{
				$dHtml .= '<script type="text/javascript">
						  var show = true;
						  window.onscroll = function() {
						    var window_height = jQuery(window).height();
						    var body_height = (jQuery(".wrapper").height())*0.80;
						    var scroll_top = jQuery(window).scrollTop();
							//console.log("window_height: " + window_height + "body_height: " + body_height + "scroll_top: " + scroll_top);
						    if (window_height + scroll_top > body_height && show) {
						      show = false
						      showMoreDiamonds('.$smd.');
						    }
						  }
						</script>';
			}
			echo $dHtml;
		}
		else{
			$diamonds = Mage::helper("diamondsearch")->getDiamondsArray($stones, $_REQUEST["is_fancy"]);
			$list = array(
				"cyo_shapes" => explode(",", $_REQUEST["shapes"]),
				"sid" => "",
				"order_method" => $orderMethod,
				"direct" => "none",
				"scroll_show" => true,
				"colors" => $_REQUEST["colors"],
				"cuts" => $_REQUEST["cuts"],
				"path" => "?".$_SERVER['QUERY_STRING'],
				"row" => (int)$_REQUEST["row"],
				"order_by" => $orderby,
				"total_count" => $total_count,
				"collections" => array(),
				"diamonds" => $diamonds,
				"clarities" => $_REQUEST["clarities"],
				"fluorescence" => $_REQUEST["fluorescences"],
				"similar" => array(),
				"first" => ""
			);
			echo json_encode($list);
		}
	}

			public function listrecentAction()
			{
				$diamondsCollection = Mage::getModel("diamondsearch/diamondsearch")->getCollection();

				//STATIC setting ids
				//$diamonds_id = array(1200,853,855);
				$diamonds_id = Mage::getSingleton('core/session')->getRecentlyViewed();

				$diamondsCollection->addFieldToFilter('id', array('in' => $diamonds_id));

				$stones = $diamondsCollection->getData();
				$diamonds = Mage::helper("diamondsearch")->getDiamondsArray($stones, $_REQUEST["is_fancy"]);
				//$total_count = (count($diamonds) > 20) ? 20 : count($diamonds);
				$total_count = count($diamonds);
				$list = array(
					"total_count" => $total_count,
					"diamonds_id" => $diamonds_id,
					"diamonds" => $diamonds,
				);
				
				echo json_encode($list);
			}
    public function listrequestAction()
    {
    	$diamondsCollection = Mage::getModel("diamondsearch/diamondsearch")->getCollection();
    
    	$diamonds_id = array();
    
    	if(isset($_REQUEST["request_diamonds"]))
    	{
    		$diamonds_id = explode("-", $_REQUEST["request_diamonds"]);
    		$diamondsCollection->addFieldToFilter('id', array('in' => $diamonds_id));
    	}
    
    	$stones = $diamondsCollection->getData();
    	$diamonds = Mage::helper("diamondsearch")->getDiamondsArray($stones, $_REQUEST["is_fancy"]);
    
    	array_map("intval", $diamonds);
    	//$total_count = (count($diamonds) > 20) ? 20 : count($diamonds);
    	$total_count = count($diamonds);
    	$list = array(
    			"total_count" => $total_count,
    			"diamonds_id" =>  array_map("intval", $diamonds_id),
    			"diamonds" => $diamonds,
    	);
    
    	echo json_encode($list);
    }
    public function listcompareAction()
    {
		$diamondsCollection = Mage::getModel("diamondsearch/diamondsearch")->getCollection();

		$diamonds_id = array();

		if(isset($_REQUEST["comparison_diamonds"]))
		{
			$diamonds_id = explode("-", $_REQUEST["comparison_diamonds"]);
			$diamondsCollection->addFieldToFilter('id', array('in' => $diamonds_id));
		}
		
		$stones = $diamondsCollection->getData();
		$diamonds = Mage::helper("diamondsearch")->getDiamondsArray($stones, $_REQUEST["is_fancy"]);
		
		array_map("intval", $diamonds);
		//$total_count = (count($diamonds) > 20) ? 20 : count($diamonds);
		$total_count = count($diamonds);
		$list = array(
			"total_count" => $total_count,
			"diamonds_id" =>  array_map("intval", $diamonds_id),
			"diamonds" => $diamonds,
		);
		
		echo json_encode($list);
    }

    public function sortrcAction()
    {
		$post = $this->getRequest()->getPost();
		
		$orderby = $post["order_by"];
		if($post["order_by"] == "price")
			$orderby = "totalprice";
		else if($post["order_by"] == "report")
			$orderby = "certificate";

		//print_r($post);
		if($post["table_name"] == "search_recently_header_table")
		{
			$diamondsCollection = Mage::getModel("diamondsearch/diamondsearch")->getCollection();
			$diamonds_id = Mage::getSingleton('core/session')->getRecentlyViewed();
			$diamondsCollection->addFieldToFilter('id', array('in' => $diamonds_id));
			
			$diamondsCollection->setOrder($orderby, $post["order_method"]);
			
			//echo $diamondsCollection->getSelect();

			$stones = $diamondsCollection->getData();
			
			$diamonds = Mage::helper("diamondsearch")->getDiamondsArray($stones, $_REQUEST["is_fancy"]);
			$list = array(
				"total_count" => count($diamonds),
				"diamonds_id" => $diamonds_id,
				"diamonds" => $diamonds,
			);
			
			echo json_encode($list);
		}
		else if($post["table_name"] == "search_comparison_header_table")
		{
			$diamondsCollection = Mage::getModel("diamondsearch/diamondsearch")->getCollection();
			$diamonds_id = array();
			//echo "FFF: <pre>";print_r($post["comparison_diamonds"]);

			if($post["comparison_diamonds"])
			{
				//$diamonds_id = explode("-", $post["comparison_diamonds"]);
				$diamonds_id = $post["comparison_diamonds"];
				$diamondsCollection->addFieldToFilter('id', array('in' => $diamonds_id));
			
			}
			//echo " IN ";print_r($diamonds_id);
			
			$diamondsCollection->setOrder($orderby, $post["order_method"]);
			
			$stones = $diamondsCollection->getData();
			$diamonds = Mage::helper("diamondsearch")->getDiamondsArray($stones, $_REQUEST["is_fancy"]);
			array_map("intval", $diamonds);
			$list = array(
				"total_count" => count($diamonds),
				"diamonds_id" =>  array_map("intval", $diamonds_id),
				"diamonds" => $diamonds,
			);

			echo json_encode($list);
		}
	}
    
}
