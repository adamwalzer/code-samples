<?php

$outputtext .= "<html>\n";

include "../php/head.php";

$outputtext .= "<body>\n";

$outputtext .= "<div id='content'>\n";

include "../php/toplogo.php";

include "../php/topbar.php";

include "../php/mainarea.php";

$outputtext .= "</div>\n";

include "../php/bottombar.php";

$outputtext .= "</body>\n";

$outputtext .= "</html>\n";

echo $outputtext;

?>