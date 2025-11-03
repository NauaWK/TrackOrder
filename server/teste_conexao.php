<?php
$host = 'localhost:3307';
$user = 'root';
$pass = 'naua123'; // ou sua senha, se tiver
$dbname = 'trackorder';

$conn = new mysqli($host, $user, $pass, $dbname);

if ($conn->connect_error) {
    die("Erro na conexão: " . $conn->connect_error);
}
echo "Conexão bem-sucedida!";
?>
