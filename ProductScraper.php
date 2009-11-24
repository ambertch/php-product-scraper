<?php

/**
 * Configuration parameters
 */
define('HANDLERS_LOCATION', 'Handlers/');

/**
 * Autoload for Handler classes 
 */ 
function __autoload($class) 
{
	@include(HANDLERS_LOCATION . $class . '.php');
} 
 
/**
 * Product scraper
 * This class has one public static method getInfo() which takes a product url
 * and parses out title, price info, description, images, and a normalized url
 * and returns them in an array
 * 
 * Custom Handlers must implement:
 *   1. XPath queries for all scraping functions 
 *   2. parsing for the DOMNodeList returned by getDescription() 
 *   3. their own url normalization 
 *   4. setting their own value for $imageWidthThreshold
 *   5. any postprocessing of scraped values 
 * 
 */
class ProductScraper
{
	/** 
	 * Loads up product page and either calls site specific Handler, or uses default
	 * Handler to scrape page. 
	 * 
	 * The Handler returns an array (values NULL if failed) containing:
	 *   - [0] Title of object
	 *   - [1] Price of object
	 *   - [2] Description of object
	 *   - [3] Array() of likely product images
	 *   - [4] Normalized url
	 * 
	 * 
	 * @param string $url  
	 * 
	 * return array
	 */
	static public function getInfo($url)
	{
		// initialize working variables
		$urlComponents = parse_url($url);

		$pageData = self::getPage($url);
			
		$pageDOM = new DOMDocument();
		@$pageDOM->loadHTML($pageData);
		$xpath = new DOMXPath($pageDOM);	

		/* 
		 * Use domain/subdomain Handlers if they exist, else use default handler
		 */
		$domain = $urlComponents['host'];
		$handlerName = preg_replace('/[.]/', '_', $domain);
	  	
		if (class_exists($handlerName))
		{
			$handlerExists = TRUE;
		} 
		else 
		{
			$handlerName = preg_replace('/\b[a-z0-9A-Z]+_/', '', $handlerName);
			(class_exists($handlerName)) ?	$handlerExists = TRUE : 0;
		}	
		
		if ($handlerExists)
		{
		  	$handler = new $handlerName();
	  		return $handler->customScraper($xpath, $urlComponents);  
		}			
		
		//Unlike custom Handlers, we pass the page string data to the default handler b/c it parses price with text processing 
		return self::defaultScraper($xpath, $urlComponents, $pageData);
	}


	/**
	 * @param string $url
	 * 
	 * @return string
	 */
	static protected function getPage($url)
	{
		$pageData = curl_init($url);
		// user agent is set to Chrome
		$userAgent = "Mozilla/5.0 (Windows; U; Windows NT 5.1; en-US) AppleWebKit/525.13 (KHTML, like Gecko) Chrome/0.A.B.C Safari/525.13";
		
		curl_setopt($pageData, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($pageData, CURLOPT_FOLLOWLOCATION, 1);
		curl_setopt($pageData, CURLOPT_USERAGENT, $userAgent);
		return curl_exec($pageData);
	}

	/**
	 * @param DOMXPath $xpath
	 * @param string $xpathQuery
	 * 
	 * return string
	 */
	static protected function getTitle($xpath, $xpathQuery)
	{
		$title = $xpath->evaluate($xpathQuery);
		
		// could use textContent too, but nodeValue is DOM level 1
		return $title->item(0)->nodeValue;
	}

	/**
	 * For getting the price, we just query and return a DOMNodeList because 
	 * we don't know what kind of processing the Handlers need to do. 
	 * 
	 * @param DOMXPath $xpath
	 * @param string $xpathQuery
	 * 
	 * return DOMNodeList
	 */
	static protected function getPrice($xpath, $xpathQuery)
	{		
		return $xpath->evaluate($xpathQuery);		
	}
	
	/**
	 * For getting the description, we just query and return a DOMNodeList because 
	 * we don't know what kind of processing the Handlers need to do. 
	 * 
	 * @param DOMXPath $xpath
	 * @param string $xpathQuery
	 * 
	 * return DOMNodeList
	 */
	static protected function getDescription($xpath, $xpathQuery)
	{
		return $xpath->evaluate($xpathQuery);		
	}

	/**
	 * @param DOMXPath $xpath
	 * @param string $xpathQuery
	 * 
	 * @return array
	 */
	static protected function getImages($xpath, $xpathQuery, $imageWidthThreshold)
	{
		$productImages = Array();
		$allImages = $xpath->evaluate($xpathQuery);
		
		for($i = 0; $i < $allImages->length; $i++)
		{
			$image = $allImages->item($i);
			$imageWidth = $image->getAttribute('width');
			($imageWidth > $imageWidthThreshold) ? $productImages[] = $image->getAttribute('src') : 0;
		}
		
		return $productImages;	
	}

	/**
	 * default url normalization function
	 * 
	 * $param array $urlComponents
	 * 
	 * return string
	 */
	static protected function defaultNormalize($urlComponents)
	{	
		$normalizedUrl = preg_replace('/^www./', '', $urlComponents['host']);
		$normalizedUrl .= $urlComponents['path'];
		($urlComponents['query']) ? ($normalizedUrl .= '?') : 0;
		$normalizedUrl .= $urlComponents['query'];
		return $normalizedUrl;	
	}


	/**
	 * This default scraper goes for the most general implementation: 
	 *   - $title contains the contents of the <title> tag in <head>
	 *   - $price contains the first $ in the page
	 *   - $description contains the contents of the description meta tag
	 *   - $productImages finds where <img> width attribute > $imageWidthThreshold
	 *   - $normalizedUrl just strips 'http://www.'
	 * 
	 * @param DOMXPath $xpath
	 * 
	 * return array
	 */
	 static private function defaultScraper($xpath, $urlComponents, $pageData)
	 {
	 	$xpathQuery = '/html/head/title';
		$title = self::getTitle($xpath, $xpathQuery);
		
		$price = self::defaultGetPrice($pageData);
		
		$xpathQuery = '/html/head/meta';
		$descriptionNodeArray = self::getDescription($xpath, $xpathQuery);
		$description = "a description metatag has not been found";
		for($i = 0; $i < $descriptionNodeArray->length; $i++)
		{			
			$descriptionNode = $descriptionNodeArray->item($i);
			(($descriptionNode->getAttribute('name') == 'description') ||
			 ($descriptionNode->getAttribute('name') == 'Description')) ? $description=$descriptionNode->getAttribute('content') : 0;		
		}
		
		$imageWidthThreshold = 200;
		$xpathQuery = '/html/body/descendant::img';
		$productImages = self::getImages($xpath, $xpathQuery, $imageWidthThreshold);
		
		$normalizedUrl = self::defaultNormalize($urlComponents);
		
		//assemble return array
		$scrapedValues = Array();
		$scrapedValues[] = $title;
		$scrapedValues[] = $price;
		$scrapedValues[] = $description;
		$scrapedValues[] = $productImages;
		$scrapedValues[] = $normalizedUrl;		

		return $scrapedValues;	
	 }

	/**
	 * @param string $pageData
	 * 
	 * return float
	 */
	static private function defaultGetPrice($pageData)
	{
		$priceMatches = Array();
		preg_match('/\$\d+\.\d\d/', $pageData, $priceMatches);
		$firstPrice = $priceMatches[0];
		return $firstPrice;		
	}

} 
 
?>
