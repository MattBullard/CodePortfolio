<?php

// Using this get_headers function we can get the HTTP header information of the given URL.
// If you set second parameter of the get_headers() to true then you will get result in associative array.

$url = "http://www.domain.com/demo.jpg";
$headers = @get_headers($url);
if(strpos($headers[0],'404') === false)
{
  echo "URL Exists";
}
else
{
  echo "URL Not Exists";
}


?>
