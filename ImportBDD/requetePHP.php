<?php
                $bdd_host = "localhost";
                $bdd_name = "geoip";
                $bdd_login = "lucas";
                $bdd_password = "toto";

                $pdo = new PDO("mysql:host=$bdd_host;dbname=$bdd_name;charset=utf8", $bdd_login, $bdd_password);

                function searchIp ($pdo,$ip) {
                    $search = $pdo -> prepare("SELECT country_code,country_name,region_name,city_name,latitude,longitude FROM GEOIP WHERE ip_from < ? AND ip_to > ?");
                    $search -> execute(array($ip,$ip));
        
                    $infos = $search -> fetch(PDO::FETCH_ASSOC);
        
                    $res = [];
        
                    foreach($infos as $elmt) {
                        array_push($res, $elmt);
                    }
                    return $res;
                }

                $tab = searchIp($pdo,16777300);
                print_r($tab);
?>