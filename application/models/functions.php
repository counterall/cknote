<?php
// used to sanitize the keywords to do Sphinx index search
function sanitizeSearch($var)
{
  $var = trim($var);
  $var = strip_tags($var);
  $var = htmlentities($var, ENT_NOQUOTES);
  $var = stripslashes($var);
  return $var;
}

// $index is used to set both user cpu time("utime") and system cpu time("stime")
function rutime($ru, $rus, $index) {
    return ($ru["ru_$index.tv_sec"]*1000000 + intval($ru["ru_$index.tv_usec"]))
     -  ($rus["ru_$index.tv_sec"]*1000000 + intval($rus["ru_$index.tv_usec"]));
}


?>
