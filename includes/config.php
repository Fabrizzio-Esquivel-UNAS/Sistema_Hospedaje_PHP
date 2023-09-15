<?php 
// DB credentials.
define('DB_NAME','hospedaje');
define('DB_HOST','localhost');
$DB_USER = 'invitado';
$DB_PASS = '';
// Establish database connection.
try{
    $dbh = new PDO("mysql:host=".DB_HOST.";dbname=".DB_NAME, $DB_USER, $DB_PASS ,array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES 'utf8'"));
}catch (PDOException $e){
    exit("Error: " . $e->getMessage());
}
?>