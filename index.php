<?php

require_once('ProductScraper.php');
//ProductScraper::getInfo('http://www.zappos.com/lucky-brand-abbey-road-dune?zlfid=111');
ProductScraper::getInfo('http://www.llbean.com/webapp/wcs/stores/servlet/CategoryDisplay?storeId=1&catalogId=1&langId=-1&categoryId=18584&productId=91702&qs=3009652');

//ProductScraper::getInfo('http://www.amazon.com/gp/product/0385504225/ref=s9_k2a_gw_ir02?pf_rd_m=ATVPDKIKX0DER&pf_rd_s=center-2&pf_rd_r=1ZRK7TJAFTJTABPJGGRT&pf_rd_t=101&pf_rd_p=470938631&pf_rd_i=507846');

/*for a general purpose one, maybe the best thing to do for description is grab the description metadata 
 * if it exists and isn't empty, and if that doesn't work try to scan for the first <p> tag and just use that. 
 * this data will be given to users more out of convenience and not accuracy--and in the end they can make edits 
 * that are sensible. don't worry accuracy--i'm more interested in the whole approach. things can always be tweaked.
 */

/* i would say 200 pixels wide should be a minimum threshold.  <-- config file*/

?>

