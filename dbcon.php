<?php
$server = "localhost"; //pas aan indien nodig
$username = "root"; //pas aan indien nodig
$password = ""; //pas aan indien nodig
$db = "escaperoom"; //pas aan indien nodig

try {
  $db_connection = new PDO("mysql:host=$server; dbname=$db", $username, $password);
  $db_connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
  echo "Verbinding mislukt" . $e->getMessage();
}
