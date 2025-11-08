<?php
session_start();

include('db/conexao.php');

if (!isset($_SESSION['usuario_id'])) {
  echo "<script>alert('Você precisa estar logado.'); window.location.href='../templates/index.html';</script>";
  exit;
}

if (!isset($_POST['comanda_id'])) {
  http_response_code(400);
  echo json_encode(['erro' => 'Comanda nao especificada.']);
  exit;
}

$comanda_id = intval($_POST['comanda_id']);

// Atualiza o status da comanda
$sql = "UPDATE comanda SET status_comanda = 'Fechada' WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $comanda_id);

if ($stmt->execute()) {
  echo json_encode(['sucesso' => true]);
} else {
  http_response_code(500);
  echo json_encode(['erro' => 'Erro ao fechar a comanda.']);
}
?>
