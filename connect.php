<?php
$mysql_host = 'localhost'; //lub jakis adres: np sql.nazwa_bazy.nazwa.pl
$port = ''; //domyslnie jest to port 3306
$username = 'root';
$password = '';
$database = 'blog'; //'produkty'

try{
	$pdo = new PDO('mysql:host='.$mysql_host.';dbname='.$database.';port='.$port, $username, $password );
}catch(PDOException $e){
	echo 'Polaczenie nie moglo zostac utworzone.<br />';
    exit;
}
?>