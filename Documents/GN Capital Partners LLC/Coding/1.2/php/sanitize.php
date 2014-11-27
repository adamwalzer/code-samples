<?php

function cleanInput($input)
{
  $search = array(
    '@<script[^>]*?>.*?</script>@si',   // Strip out javascript
    '@<[\/\!]*?[^<>]*?>@si',            // Strip out HTML tags
    '@<style[^>]*?>.*?</style>@siU',    // Strip style tags properly
    '@<![\s\S]*?--[ \t\n\r]*>@'         // Strip multi-line comments
  );
 
    $output = preg_replace($search, '', $input);
    return $output;
}
  
function sanitize($dbc,$input)
{
    if (is_array($input))
    {
        foreach($input as $var=>$val) {
            $output[$var] = sanitize($dbc,$val);
        }
    }
    elseif($input)
    {
        if (get_magic_quotes_gpc())
        {
            $input = stripslashes($input);
        }
        $input  = cleanInput($input);
        $output = mysqli_real_escape_string($dbc, $input);
    }
    return $output;
}

if($_POST)
{
	$_POST = sanitize($dbc,$_POST);
}

if($_GET)
{
	$_GET = sanitize($dbc,$_GET);
}

?>