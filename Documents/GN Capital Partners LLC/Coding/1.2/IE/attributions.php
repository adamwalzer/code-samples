<?php

$outputtext .= "<html>\n";

include "php/head.php";

$outputtext .= "<body>\n";

$outputtext .= "<div id='content'>\n";

include "php/topnavintro.php";

include "php/topbarattributions.php";

$outputtext .= "<div id='maincontainer'>\n";
			
$outputtext .= "<div id='mainbody'>\n";

$outputtext .= "
	<br>
	<p class='title2'>Special thanks to the suppliers of our background images</p>
	<p>Food Mob! mac and cheese<br>
	<a href='javascript:window.open(".'"http://www.flickr.com/photos/ugod/4820240355/"'.")'>http://www.flickr.com/photos/ugod/4820240355/</a></p>
	<p>Chicken and Beef Satay - Vivo City Food Republic food court<br>
	<a href='javascript:window.open(".'"http://www.flickr.com/photos/avlxyz/2498452237/"'.")'>http://www.flickr.com/photos/avlxyz/2498452237/</a></p>
	<p>Sesame Chicken Chinese Food Macro 12-6-08 9<br>
	<a href='javascript:window.open(".'"http://www.flickr.com/photos/stevendepolo/3090990459/"'.")'>http://www.flickr.com/photos/stevendepolo/3090990459/</a></p>
	<p>Product Shots of Food<br>
	<a href='javascript:window.open(".'"http://www.flickr.com/photos/nicktakespics/3658284817/"'.")'>http://www.flickr.com/photos/nicktakespics/3658284817/</a></p>
	<p>Fast Food<br>
	<a href='javascript:window.open(".'"http://www.flickr.com/photos/stephen-oung/6319155216/"'.")'>http://www.flickr.com/photos/stephen-oung/6319155216/</a></p>
	";

$outputtext .= "<br>\n";

$outputtext .= "</div>\n";

$outputtext .= "</div>\n";

$outputtext .= "</div>\n";

$outputtext .= "</body>\n";

$outputtext .= "</html>\n";

echo $outputtext;

?>