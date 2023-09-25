<?php 
// Credenciales de la BD predeterminadas.
define('DB_NAME','sb-database');
$DB_HOST = 'gestion-db.database.windows.net';
$DB_USER = 'fabrizzio';
$DB_PASS = '*8e#0@32V*';

//Cambiar credenciales según el usuario
/*
if(isset($_SESSION['id'])){
    if($_SESSION['id']===0){
        $DB_USER = "root";
        $DB_PASS = "";
    }else{
        $DB_USER = "recepcionista";
        $DB_PASS = "123";
    }
}
*/

// Conectarse a la BD
$connectionOptions = array(
    "Database" => DB_NAME,
    "Uid" => $DB_USER,
    "PWD" => $DB_PASS
);
try{
    $dbh = new PDO("sqlsrv:Server=$DB_HOST;Database=".DB_NAME, $DB_USER, $DB_PASS, $connectionOptions);
}catch (PDOException $e){
    exit("Error: " . $e->getMessage());
}
?>