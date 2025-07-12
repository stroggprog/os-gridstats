<?php
define("LOGIN_URL",     "http://moss.mossgrid.uk:8002");
define("WEBSITE",       "https:/mossgrid.uk");
define("LOGIN_SCREEN",  "https://mossgrid.uk/welcome");
define("ROBUST_URL",    "localhost");
define("ROBUST_PORT",   8002);
define("GRID_STATS",    "https://mossgrid.uk/gridstats");

// == don't edit below here == //

define("SRC_VERSION", "1.0.3");


include_once("lib/db_mysql.php");
include_once("lib/params.php");
include_once("lib/db_params.php");
include_once("lib/simplexml.php");
include_once("land-flags.php");

$publicAccess = UseAccessGroup | UseAccessList | UsePassList;
/*
    $pub = $r->flags & $publicAccess;
    $xreg["public"] =  $pub == 0;
*/

$result = array( "error" => 0,
                 "version" => SRC_VERSION,
                 "date-time" => date("Y-m-d H:i:s") ); // set defaults

$p = new parameters();

if( $p->pw != SECRET ){
    $result["error"] = 418;
    $result["errormsg"] = "I'm a teapot";
    echo json_encode( $result, JSON_PRETTY_PRINT );
    exit(0);
}

$oneMonth = time() - (86400*30);
$online = false;
$socket = @fsockopen( ROBUST_URL, ROBUST_PORT, $errno, $errstr, 1 );
if( is_resource( $socket ) ){
    $online = true;
    @fclose( $socket );
}

if( $p->format == "" ) $p->format = "json";

$db = new DB_Sql();
$db = setDBParameters( $db );
$db->connect();


$r = $db->exec_as_obj("select count(*) as c from regions");
$result["regions"] = (int) $r->c;

$r = $db->exec_as_obj("select count(*) as c from regions where sizeX=256");
$result["single_regions"] = (int) $r->c;

$r = $db->exec_as_obj("select count(*) as c from regions where sizeX>256");
$result["var_regions"] = (int) $r->c;

$r = $db->exec_as_obj("select sum(sizeX) as x from regions");
$result["total_size_sq_meters"] = $r->x * $r->x;
$result["total_size_sq_km"] = $r->x/1000;

$r = $db->exec_as_obj("select count(*) as c from GridUser WHERE `Logout`>$oneMonth and UserID like '%http%'");
$result["hg_visitors_last_30_days"] = (int) $r->c;

$r = $db->exec_as_obj("select count(*) as c from GridUser WHERE `Login`>`Logout` and UserID like '%;http%'");
$result["hg_visitors_online_now"] = (int) $r->c;

$r = $db->exec_as_obj("select count(*) as c from UserAccounts");
$result["registered_users"] = (int) $r->c;

$r = $db->exec_as_obj("select count(*) as c from GridUser WHERE `Logout`>$oneMonth and UserID not like '%http%'");
$result["local_users_last_30_days"] = (int) $r->c;

$sql = "select count(*) as c from (select p.UserID, u.PrincipalID from Presence as p".
        ", UserAccounts as u where u.PrincipalID=p.UserID) as v";
$r = $db->exec_as_obj( $sql );
$result["local_users_online_now"] = (int) $r->c;

$result["total_active_last_30_days"] = $result["hg_visitors_last_30_days"] + $result["local_users_last_30_days"];
$result["total_active_online_now"] = $result["hg_visitors_online_now"] + $result["local_users_online_now"];

$result["login_url"] = LOGIN_URL;
$result["website"] = WEBSITE;
$result["login_screen"] = LOGIN_SCREEN;
$result["grid_status"] = $online;

if( $p->format == "json" ){
    header("Content-type:application/json");
    echo json_encode( $result, JSON_PRETTY_PRINT );
}
else if( $p->format == "xml" ){
    header("Content-type: text/xml");
    $xml_data = new SimpleXMLElement('<?xml version="1.0"?><data></data>');
    array_to_xml( $result, $xml_data );
    echo $xml_data->asXML();
}
else if( $p->format == "text" || $p->format == "wtext" ){
    $eol = "\n";
    if( $p->format == "wtext" ){
        $eol = "\r\n"; // windows text format
    }
    header("Content-type:text/plain");
    foreach( $result as $key => $value ){
        echo "$key: $value$eol";
    }

}
else if( $p->format == "html" ){
    foreach( $result as $key => $value ){
        echo "<b>$key:</b> $value<br>";
    }
}
else if( $p->format == "table" ){
    echo "<table>";
    foreach( $result as $key => $value ){
        echo "<tr><th>$key</th><td>$value</td></tr>\n";
    }
    echo "</table>";
}
else if( $p->format == "wiki" ){
    header("Content-type:text/plain");
    foreach( $result as $key => $value ){
        echo "^$key  |  $value|\n";
    }
}
?>
