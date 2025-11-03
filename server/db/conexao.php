<?php

//arquivo de conexão com o banco que será incluído nos outros arquivos .php
$host = 'localhost:3307';
$user = 'root';
$pass = 'naua123';
$dbname = 'trackorder';

$conn = new mysqli($host, $user, $pass, $dbname);

if ($conn->connect_error) {
    die("Erro na conexão: " . $conn->connect_error);
}
?>
