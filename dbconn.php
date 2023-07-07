<?php
// ajustando la zona horaria
date_default_timezone_set('America/Caracas');

// haciendo la conexión
$server = "localhost";
$username = "root";
$password = "";
$db = "oil_wells_project";

$connection = mysqli_connect($server, $username, $password, $db);

// mostrar mensaje si falla la conexión
if (mysqli_connect_errno()) {
    echo "Conexión Fallida: " . mysqli_connect_error();
}

// función simplificada para hacer queries
function query($sql)
{
    global $connection;
    $query = mysqli_query($connection, $sql);
    if ($query === false) {
        die('Error: ' . mysqli_error($connection));
    } else {
        return $query;
    }

}
?>