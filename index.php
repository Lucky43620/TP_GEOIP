<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>GEOIP</title>
    <link rel="shortcut icon" type="image/png" href="client.png"/>
    <link rel="stylesheet" href="stylesheet.css">
</head>
<body>
    <div class="Container">
        <div class="Bordure">
            <h1 class="display-4 font-weight-bold">Vous vous situez :</h1>
            <!-- Lecture adresse IP -->
            <?php
                function getIp(){
                    if(!empty($_SERVER['HTTP_CLIENT_IP'])){
                    $ip = $_SERVER['HTTP_CLIENT_IP'];
                    }elseif(!empty($_SERVER['HTTP_X_FORWARDED_FOR'])){
                    $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
                    }else{
                    $ip = $_SERVER['REMOTE_ADDR'];
                    }
                    return $ip;
                }
                $ipadecouper = getIp(); 
                echo 'L adresse IP de l utilisateur est : ' . $ipadecouper . '<br>';
                $ipadecouper = '37.58.179.26';
                echo '<br>';

                list($ip0, $ip1, $ip2, $ip3) = explode(".", $ipadecouper);
                echo 'ip0 = ' . $ip0 . '<br>';
                echo 'ip1 = ' . $ip1 . '<br>';
                echo 'ip2 = ' . $ip2 . '<br>';
                echo 'ip3 = ' . $ip3 . '<br>';

                echo '<br>';

                $calculip = $ip3 + $ip2 * 256 + $ip1 * 256 * 256 + $ip0 * 256 * 256 * 256;
                echo 'calcul = ' . $calculip . '<br>';

                echo '<br>';
                
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

                $start_time = microtime(true);

                $tab = searchIp($pdo,$calculip);

                $end_time = microtime(true);

                if ($tab and $tab[0] == "FR")
                {
                    foreach ($tab as $valeur) {
                        echo ($valeur . "\n". '<br>');
                    }
                }
                else
                {
                    http_response_code(403);
                }
                
                echo '<br>';

                echo 'Temps recherche : ' . ($end_time - $start_time) * 1000 . ' ms';

                echo '<br>';

                mysql_close();
            ?>
        </div>
    </div>
</body>
</html>