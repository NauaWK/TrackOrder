<?php
include 'db.php';
session_start();

if (!isset($_SESSION['usuario_id'])) {
  http_response_code(403);
  echo json_encode(["erro" => "Não autorizado"]);
  exit();
}

$input = json_decode(file_get_contents("php://input"), true);
$comanda_id = $input['comanda_id'];

$sql = "UPDATE comanda SET status_pedidos = 'finalizado', status_pagamento = 'paga' WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $comanda_id);

if ($stmt->execute()) {
  echo json_encode(["sucesso" => true]);
} else {
  http_response_code(500);
  echo json_encode(["erro" => "Erro ao fechar comanda"]);
}
?>
