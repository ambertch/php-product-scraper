<?php
require_once('ProductScraper.php');

// Test urls
/*
 http://www.zappos.com/lucky-brand-abbey-road-dune?zlfid=111 
 http://store.americanapparel.net/rsa642sg.html 
 http://www.llbean.com/webapp/wcs/stores/servlet/CategoryDisplay?storeId=1&catalogId=1&langId=-1&categoryId=18584&productId=91702&qs=3009652
*/

$scrapedValues = Array();
$scrapedValues = ProductScraper::getInfo('http://store.americanapparel.net/rsa642sg.html '); 


//testing output
echo "Title: $scrapedValues[0] <br />";
echo "Price: " . $scrapedValues[1] . "<br />";
echo "Description: $scrapedValues[2] <br />";
echo "Images: "; print_r($scrapedValues[3]); echo '<br />';
echo "Normalized url: $scrapedValues[4] <br />";

?>

