<?php

class Ideal_Brandmanager_Helper_Data extends Mage_Core_Helper_Abstract
{
	const DISP_HOME_PAGE = '0';
	const DISP_CATEGORY = '1';

	public function getDisplayOption(){
		return array(
			array('value'=>self::DISP_HOME_PAGE, 'label'=>$this->__('Home page')),
			array('value'=>self::DISP_CATEGORY, 'label'=>$this->__('Category')),
		);
	}

	public function getCatOptionArray(){
		$options = array();

        $options[] = array(
            'label' => Mage::helper('brandmanager')->__('-- None --'),
            'value' => ''
        );


	 	if($_SERVER['HTTP_HOST'] != 'r.beautycollection.com' && $_SERVER['HTTP_HOST'] != 'beautycollection.com' )
		{
			$categories=array();
			$category = Mage::getModel('catalog/category');
			$catTree = $category->getTreeModel()->load();
			$catIds = $catTree->getCollection()->getAllIds();
			$cats = array();
			    if ($catIds)
					{
			        foreach ($catIds as $id)
							{
			        	$cat = Mage::getModel('catalog/category');
			        	$cat->load($id);
								if($cat->getId()=='1' || $cat->getId()=='2') continue;
			        	$categories[$cat->getName()] =$cat->getId() ;
			    		}
					}
		}
		else
		{
				$categories = array("Bath & Body"=>10,"Fragrances"=>11,"Gifts"=>12,"Hair Care"=>13,"Holiday"=>14,"Home Decor"=>15,"Makeup"=>16,"Mani & Pedi"=>17,"Men"=>159,"Mother & Child"=>160,"Pet Care"=>161,"Skin Care"=>162,"Smile"=>163,"Tools & Accessories"=>164);
		}



		foreach($categories as $key=>$value){
			$options[] = array(
               'label' => $key,
               'value' => $value
            );
		}
		return $options;
	}



	public function getImportBrandCSV()
	{
		$brand_csv_url = Mage::getStoreConfig('brandmanager/settings/doc_csv_url');
		//$brand_csv_url = "https://docs.google.com/spreadsheets/d/e/2PACX-1vTle9v8Xvca04EwnMxPafPqwOzeZqy6ZFNDcy0nQfYwB_r0xcXP0FDb_QcCX64jjooIkSP9S9pc5u6i/pub?gid=0&single=true&output=csv";

		$url = $brand_csv_url;

		$ch = curl_init();
		curl_setopt($ch,CURLOPT_URL, $url);
		curl_setopt($ch,CURLOPT_RETURNTRANSFER,1);
		curl_setopt($ch,CURLOPT_CONNECTTIMEOUT,5);

		$result = curl_exec($ch);
		curl_close($ch);


		$lines = explode( "\n", $result );
		$lines = array_map('str_getcsv', $lines);
		$headerRow = $lines[0];
		unset($lines[0]);

		$finalImportArray = array();
		$k = 0;
		foreach($lines as $key=>$_val)
		{
			for($s=0;$s<count($headerRow);$s++){
				$finalImportArray[$k][$headerRow[$s]] = $_val[$s];
			}
			$k++;
		}

		//echo "<pre>";print_r($finalImportArray);die;

		try{
			foreach($finalImportArray as $brand)
			{
				$brandCollection = Mage::getModel("brandmanager/brandmanager")->load($brand['brandmanager_id']);
				if($brandCollection->getData() != array()){
					$brandCollection->setId($brand['brandmanager_id'])
									->setData('title',$brand['title'])
									->setData('filename',$brand['filename'])
									->setData('sortno',$brand['sortno'])
									->setData('status',$brand['status'])
									->setData('weblink',$brand['weblink'])
									->save();
				}else{
					$collection = Mage::getModel("brandmanager/brandmanager");
					$collection->setData('title',$brand['title'])
								->setData('sortno',$brand['sortno'])
								->setData('filename',$brand['filename'])
								->setData('status',$brand['status'])
								->setData('weblink',$brand['weblink'])
								->save();
				}
			}
			return array("success"=>1,"message"=>"Import CSV successfully.");

		}catch(Exception $e){
			return array("success"=>0,"message"=>$e->getMessage());
		}
	}


}
