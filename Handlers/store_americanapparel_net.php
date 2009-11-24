<?php

class store_americanapparel_net extends ProductScraper
{
	protected function customScraper($xpath, $urlComponents)
	{
		
		// This sample is really just a copy of the code from the default Handler
		// with arbitrary values filled in for the parent's private functions
		// All logic should be implemented in this function 
		
		
		echo "Bam! We're in the custom Handler. I actually didn't know in php a superclass" .
				" can call a protected method from a subclass since that should violate encapsulation. " .
				"That really works out for me though becuase I " .
				"was originally planning to make customScraper a static method, but php's syntax" .
				" does not allow you to call a class's static method using syntax like " .
				"\$ClassName::\$staticMethod so I was forced to instantiate an object and call a member function" .
				"<br /><br /><br /> These below are of course fake values," .
				"but logic can be implemented very modularly <br /> <br />";
		
		
		$xpathQuery = '/html/head/title';
		$title = parent::getTitle($xpath, $xpathQuery);
		
		$price = 10000000.00; //arbitrary
		
		$xpathQuery = '/html/head/meta';
		$descriptionNodeArray = parent::getDescription($xpath, $xpathQuery);
		$description = "a description metatag has not been found";
		for($i = 0; $i < $descriptionNodeArray->length; $i++)
		{			
			$descriptionNode = $descriptionNodeArray->item($i);
			(($descriptionNode->getAttribute('name') == 'description') ||
			 ($descriptionNode->getAttribute('name') == 'Description')) ? $description=$descriptionNode->getAttribute('content') : 0;		
		}
		
		$imageWidthThreshold = 200;
		$xpathQuery = '/html/body/descendant::img';
		$productImages = parent::getImages($xpath, $xpathQuery, $imageWidthThreshold);
		
		$normalizedUrl = "This is a fake normalized URL. Unimplemented"; //arbitrary
		
		//assemble return array
		$scrapedValues = Array();
		$scrapedValues[] = $title;
		$scrapedValues[] = $price;
		$scrapedValues[] = $description;
		$scrapedValues[] = $productImages;
		$scrapedValues[] = $normalizedUrl;		

		return $scrapedValues;	
		
		
	}
}

?>

