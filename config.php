<?php

$secretsFile = "../../../secrets/GDI_Secrets.env";
if (!file_exists($secretsFile)) die("Couldn't find the secrets file!");
$secrets = parse_ini_file($secretsFile);
$databaseServerIP = $secrets['DATABASE_IP'];
$databaseName = $secrets['DATABASE_NAME'];
$databaseUser = $secrets['DATABASE_USER'];
$databasePassword = $secrets['DATABASE_PASS'];
$databaseOptions = [
  PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
  PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
  PDO::ATTR_EMULATE_PREPARES => false
];

try {
  $conn = new PDO("mysql:host=$databaseServerIP;dbname=$databaseName", $databaseUser, $databasePassword, $databaseOptions);
} catch (Exception $error) {
  die("Couldn't establish a connection with the database!");
}