<?php
define("SECRET", "0TN@Z6E7**1)U'?MH81:[)z|;nj#3N&Ayb@Ql~.4XE+eR$)Dbg-}Omp_f*2iem=" );

function setDBParameters( $db ){
    $db->Host = "localhost";
    $db->Database = "opensim";
    $db->User = "opensimuser";
    $db->Password = "opensimuserPassword"; // honestly, if this is your password, change it
    return $db;
}
?>
