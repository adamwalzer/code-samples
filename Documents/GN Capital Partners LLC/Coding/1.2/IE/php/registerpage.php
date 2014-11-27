<?php

$outputtext .= "<div id='top'>\n";

	include "topbarregister.php";

$outputtext .= "</div>\n";

$outputtext .= "<div id='main'>\n";

	include "registerdiv.php";

$outputtext .= "</div>\n";

echo $outputtext;

?>