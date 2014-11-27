<?php

$page_name = $_GET['page'] . '.php';

include $page_name;

echo $outputtext;

?>