<?php  

class System
{

	public static function createXML($list)
	{
		$xml = new SimpleXMLElement('<yml_catalog/>');
		foreach ($list as $product) {
			$offer = $xml->addChild('offer');
			$offer->addAttribute('available', "true");
			$offer->addAttribute('id', $product['product_id']);
			$offer->addChild('picture', $product['image']);
			$offer->addChild('price', $product['price']);
			$offer->addChild('currencyId', 'UAH');
			$offer->addChild('categoryId', '""');
			$offer->addChild('name', $product['name']);
			$offer->addChild('description', $product['description']);
			$offer->addChild('vendorCode', $product['vendor_code']);
			$offer->addChild('vendor', $product['vendor']);
			$param = $offer->addChild('param', $product['product_id'] + 3000000000);
			$param->addAttribute('name', 'internal code');
		}    
		Header('Content-type: text/xml');
		return $xml->asXML();
	}

	public static function createUpdate($list)
	{
//echo'<pre>'; var_dump($list);die;		
    $xml = new SimpleXMLElement('<yml_catalog/>');
    foreach ($list as $product) {
        $offer = $xml->addChild('offer');
        $offer->addAttribute('available', $product['@attributes']['available']);
        $offer->addAttribute('id', $product['@attributes']['id']);
        $offer->addChild('price', $product['price']);
        $offer->addChild('name', $product['name']);
        $offer->addChild('vendorCode', $product['vendorCode']);
    }    
    Header('Content-type: text/xml');
    return $xml->asXML();
	}

	public static function createDisable($list)
	{
    $xml = new SimpleXMLElement('<yml_catalog/>');
    foreach ($list as $product) {
        $offer = $xml->addChild('offer');
        $offer->addAttribute('available', "");
        $offer->addAttribute('id', $product['@attributes']['id']);
        $offer->addChild('name', $product['name']);
    }    
    Header('Content-type: text/xml');
    return $xml->asXML();
	}	

	public static function createUpdateDescription($list)
	{
    $xml = new SimpleXMLElement('<yml_catalog/>');
    foreach ($list as $product) {
        $offer = $xml->addChild('offer');
        $offer->addAttribute('available', $product['@attributes']['available']);
        $offer->addAttribute('id', $product['@attributes']['id']);
        $offer->addChild('name', $product['name']);
        $offer->addChild('description', $product['description']);
        //$offer->addChild('vendorCode', $product['vendorCode']);
        //break;
    }    
    Header('Content-type: text/xml');
    return $xml->asXML();
	}


	public static function write($import)
	{
		$filename = ROOT.'/tmp/import.xml';
		$fp = fopen($filename, "w");
		fwrite($fp, $import);
		fclose($fp);
	}
}