<?php
$host = 'localhost';
$user = 'root';
$password = '';
$dbname = 'angel_divino';
$puerto = 3306;

$mysqli = new mysqli($host, $user, $password, $dbname, $puerto);
if ($mysqli->connect_error) {
    die('Error de conexión (' . $mysqli->connect_errno . ') ' . $mysqli->connect_error);
}
$mysqli->set_charset('utf8');
?>
