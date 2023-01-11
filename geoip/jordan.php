<?php
require_once("tab_ip.php");

$bdd_host = "localhost";
$bdd_name = "geoip";
$bdd_login = "lucas";
$bdd_password = "toto";
$req_sql = "SELECT * FROM GEOIP WHERE ip_from <= ? AND ip_to >= ?";

try {
    $pdo = new PDO("mysql:host=$bdd_host;dbname=$bdd_name;charset=utf8",$bdd_login, $bdd_password);
} catch (PDOException $e) {
    echo $e;
}
$start_time = microtime(true);
foreach($aTab as $ip) {
    $tab_remote = explode(".", $ip);
    $calcul_ip = $tab_remote[0] * 256 * 256 * 256 + $tab_remote[1] * 256 * 256 + $tab_remote[2] * 256 + $tab_remote[3];

    $search_sql = $pdo -> prepare($req_sql);
    if(
        $search_sql -> execute(array($calcul_ip, $calcul_ip)) 
    ) {
        $infos_ip = $search_sql -> fetch(PDO::FETCH_ASSOC);
        print_r($infos_ip);
    }
}
$end_time = microtime(true);

echo ($end_time - $start_time) / count($aTab) * 1000;