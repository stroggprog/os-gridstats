<?php
header("Content-type:text/plain");
include_once("../lib/sendMessage.php");
include_once("../lib/params.php");

$p = new parameters();

if( $p->format == "" ) $p->format = "json";
if( $p->format == "json" ){
    header("Content-type:application/json");
}
else if( $p->format == "xml" ){
    header("Content-type: text/xml");
}
else if( $p->format == "text" || $p->format == "wtext" || $p->format == "wiki" ){
    header("Content-type:text/plain");
}

$data = "pw=".urlencode($p->pw)."&format=$p->format";
$r = sendMessage("http://your_url/partnership.php", $data);
echo $r;

?>
