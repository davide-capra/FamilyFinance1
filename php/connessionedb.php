<?php
// connfigurazione del database
$config = [

'db_engine' => 'mysql',

'db_host' => '127.0.0.1',

'db_name' => 'familyfinance2',

'db_user' => 'root',

'db_password' => '',

];

 // Impostiamo la stringa di connessione al database

$db_config = $config['db_engine'] . ":host=".$config['db_host'] . ";dbname=" . $config['db_name'];



try { // Connessione al database

$pdo = new PDO($db_config, $config['db_user'], $config['db_password'], [

    PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8"

]);


// Impostiamo il modo di gestione degli errori
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

$pdo->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);

} catch (PDOException $e) {

exit("Impossibile connettersi al database: " . $e->getMessage());

}
?>