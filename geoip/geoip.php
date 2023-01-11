<?php
require_once("fonctions.php");
require_once("tab_ip.php");

define("MYSQL_HOST", "localhost");
define("MYSQL_DATABASE", "geoip");
define("MYSQL_USER", "lucas");
define("MYSQL_PASSWORD", "toto");

define("SQL_FIND", "SELECT * FROM GEOIP WHERE ip_to >= :ip1 AND ip_from <= :ip2");

$sPDOConnectString = sprintf( "mysql:host=%s;dbname=%s;charset=utf8", MYSQL_HOST, MYSQL_DATABASE );

$dbh = new PDO( $sPDOConnectString, MYSQL_USER, MYSQL_PASSWORD );
$dbh->setAttribute( PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION );

$debut = microtime(true);

foreach ($aTab as $sIP) {
    get_localisation($sIP);
}

$fin = microtime(true);

echo ($fin - $debut) / count($aTab) * 1000;

// Fonctions
function get_localisation($sIP)
{
    global $dbh;

    $nIp = IPtoInt($sIP);
    
    $stmt = $dbh->prepare( SQL_FIND );
    if (
        $stmt !== false &&
        $stmt->bindValue(':ip1', $nIp, PDO::PARAM_INT) &&
        $stmt->bindValue(':ip2', $nIp, PDO::PARAM_INT) &&
        $stmt->execute()
    ) {
        $aRow = $stmt->fetch(PDO::FETCH_ASSOC);   // recuperer un seul enregistrement
    
        print_r($aRow);
    }
    
}