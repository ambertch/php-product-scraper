<?php

/**
 * Handler classes - AUTOLOAD THIS
 */
//require_once "config/Handlers.php";
 

define('IMAGE_WIDTH_THRESHOLD', 200);
 
 
 
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
	 * Handlers classes are named 
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
		  	else $error[] = "Handler $handler exists for $url but 
		}
		else
		{
			$pageData = self::getPageData($normalizedUrl);
			$title = self::getTitle($pageData);
			$price = self::getPrice($pageData);
			$description = self::getDescription($pageData);
			$images = self::getImages($pageData);
			$normalizedUrl = self::defaultNormalize($urlComponents);	
		}
			

		*/
		
		
	}


	private static function getPageData($normalizedUrl)
	{
		$pageData = curl_init($normalizedUrl);
		curl_setopt($pageData, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($pageData, CURLOPT_FOLLOWLOCATION, 1);
		return curl_exec($pageData);
	}
	
	private static function getTitle($pageData)
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
	
	private static function getPrice($pageData)
	{
		return 0;	
	}
	
	
	private static function getDescription($pageData)
	{
		return 0;	
	}


	/**
	 * returns an array of string urls
	 * 
	 * @param
	 * 
	 * @return array
	 */
	private static function getImages($pageData)
	{
		return 0;
	}

	/**
	 * a very mild default normalization, just strip the 'http://www.'
	 * and take out fragments
	 * 
	 * $param array $urlComponents
	 */
	private static function defaultNormalize($urlComponents)
	{
		$normalizedUrl = preg_replace('/^www./', '', $urlComponents['host']);
		$normalizedUrl .= $urlComponents['path'];
		$normalizedUrl .= '?';
		$normalizedUrl .= $urlComponents['query'];
		return $normalizedUrl;
	}

}
 
?>
