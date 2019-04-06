<?php
class Ideal_Diamondsearch_Helper_Rapnet extends Mage_Core_Helper_Abstract
{
	public function getRapnetInstantInvAPIResponse($user,$passwd,$request_json,$page_number=1,$page_size=50,$search_type="White"){
		$data_string = $request_json;

		$ch = curl_init('https://technet.rapaport.com/HTTP/JSON/RetailFeed/GetDiamonds.aspx');
		curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
		curl_setopt($ch, CURLOPT_POSTFIELDS, $data_string);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/x-www-form-urlencoded'));
		$result = curl_exec($ch);
		$response = json_decode($result, true);
		return $response;
	}	
	public function getRapnetInstantInvSingleDiamondAPIResponse($user,$passwd,$request_json){
		$data_string = $request_json;

		$ch = curl_init('https://technet.rapaport.com/HTTP/JSON/RetailFeed/GetSingleDiamond.aspx');
		curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
		curl_setopt($ch, CURLOPT_POSTFIELDS, $data_string);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/x-www-form-urlencoded'));
		$result = curl_exec($ch);
		$response = json_decode($result, true);
		return $response;
	}	
	
}
