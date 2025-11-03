<?php
session_start();

include('db/conexao.php');

// Verifica se o usuário está logado
if (!isset($_SESSION['usuario_id'])) {
  echo "<script>alert('Você precisa estar logado.'); window.location.href='../templates/index.html';</script>";
  exit;
}

// Verifica se foi passado o ID da comanda
if (!isset($_GET['comanda_id'])) {
  echo "<script>alert('Comanda não especificada.'); window.location.href='dashboard.php';</script>";
  exit;
}

$comanda_id = intval($_GET['comanda_id']);

// Atualiza o status da comanda
$sql = "UPDATE comanda SET status_comanda = 'Fechada' WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $comanda_id);
$stmt->execute();

echo "<script>alert('Comanda fechada com sucesso!'); window.location.href='dashboard.php';</script>";
?>
