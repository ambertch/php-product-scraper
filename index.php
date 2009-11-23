<?php

require_once('ProductScraper.php');
ProductScraper::getInfo('http://www.zappos.com/lucky-brand-abbey-road-dune?zlfid=111');

/*for a general purpose one, maybe the best thing to do for description is grab the description metadata 
 * if it exists and isn't empty, and if that doesn't work try to scan for the first <p> tag and just use that. 
 * this data will be given to users more out of convenience and not accuracy--and in the end they can make edits 
 * that are sensible. don't worry accuracy--i'm more interested in the whole approach. things can always be tweaked.
 */

/* i would say 200 pixels wide should be a minimum threshold.  <-- config file*/

?>

