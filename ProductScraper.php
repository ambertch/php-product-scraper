<?php

/**
 * Configuration parameters
 */

define('HANDLERS_LOCATION', 'Handlers/');
define('IMAGE_WIDTH_THRESHOLD', 200);
 
function __autoload($class)
{
	require_once(HANDLERS_LOCATION . $class . '.php');
} 
 
/**
 * Product scraper
 * This class has one public static method getInfo() which takes a product url
 * and parses out title, price info, description, images, and a normalized url,
 * invoking a custom handler if one is present
 */
class ProductScraper
{
	
	private $errors = array();
	
	/** 
	 *  
	 * 
	 * @param string $url  
	 */
	static public function getInfo($url)
	{

		/* 
		 * Use domain/subdomain Handlers if they exist, else use default handler
		 * Handler classes are named by replacing the '.' in the hostname with
		 * a '_', thus generating a valid class name
		 */
		$urlComponents = parse_url($url);
		$domain = $urlComponents['host'];
		$handlerName = preg_replace('/[.]/', '_', $domain);
	  
	    /*
		// check if we use a subdomain, or domain level handler
		if (class_exists($handlerName))
		{
			$handlerExists = TRUE;
		} 
		else 
		{
			$handlerName = preg_replace('/\b[a-z0-9A-Z]+_/', '', $handlerName);
			if (class_exists($handlerName))
			{
				$handlerExists = TRUE;
			}	
		} 
		
		if ($handlerExists)
		{
			if ((method_exists, $handlerName, 'customGetInfo') &&
				(method_exists, $handlerName, 'customGetInfo') 
		  		$results = Handler::customGetInfo($pageData, $urlComponents);
		  	else $error[] = "Handler $handler exists for $url but is incomplete/unformated"; 
		}
		else
		{
		}*/
			$pageData = self::getPageData($url);
			$title = self::getTitle($pageData, $startPattern, $endPattern);
			$price = self::getPrice($pageData, $startPattern, $endPattern);
			$description = self::getDescription($pageData, $startPattern, $endPattern);
			$images = self::getImages($pageData);
			$normalizedUrl = self::defaultNormalize($urlComponents);	
		
		echo $pageData;
		
	}

	/**
	 * @param string $url
	 * 
	 * @return string
	 */
	protected static function getPageData($url)
	{
		$pageData = curl_init($url);
		curl_setopt($pageData, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($pageData, CURLOPT_FOLLOWLOCATION, 1);
		return curl_exec($pageData);
	}
	
	/**
	 * @param string $pageData
	 * @param string $startPattern
	 * @param string $endPattern
	 * 
	 * return string
	 */
	private static function getTitle($pageData, $startPattern, $endPattern)
	{
		/*
		$oHTMLDom = str_get_html($result);
		$oTitleTag = $oHTMLDom->find('title');
		$sPageTitle = $oTitleTag->innertext;
		$oHTMLDom->__destruct();
		unset($oHTMLDom);
		*/
		return 0;
	}
	
	/**
	 * @param string $pageData
	 * @param string $startPattern
	 * @param string $endPattern
	 * 
	 * return float
	 */
	protected static function getPrice($pageData, $startPattern, $endPattern)
	{
		return 0;	
	}
	
	/**
	 * @param string $pageData
	 * @param string $startPattern
	 * @param string $endPattern
	 * 
	 * return string
	 */
	protected static function getDescription($pageData, $startPattern, $endPattern)
	{
		return 0;	
	}

	/**
	 * returns an array of urls of images wider than the defined constant 
	 * IMAGE_WIDTH_THRESHOLD
	 * 
	 * @param string $pageData
	 * 
	 * @return array
	 */
	protected static function getImages($pageData)
	{
		return 0;
	}

	/**
	 * a very mild default normalization, just strip the 'http://www.' and the
	 * fragments
	 * 
	 * $param array $urlComponents
	 * 
	 * return string
	 */
	protected static function defaultNormalize($urlComponents)
	{
		$normalizedUrl = preg_replace('/^www./', '', $urlComponents['host']);
		$normalizedUrl .= $urlComponents['path'];
		$normalizedUrl .= '?';
		$normalizedUrl .= $urlComponents['query'];
		return $normalizedUrl;
	}

}
 
?>
