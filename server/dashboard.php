<?php
include 'db.php';
session_start();

//verifica se o usuário está logado
if (!isset($_SESSION['usuario_id'])) {
  http_response_code(403);
  echo json_encode(["erro" => "Não autorizado"]);
  exit();
}

//consulta comandas com status de pagamento "aberta"
$sql = "SELECT id, mesa_id, status_pedidos, status_pagamento, quantidade_pedidos, total_da_comanda 
        FROM comanda 
        WHERE status_pagamento = 'aberta' 
        ORDER BY hora_de_inicio DESC";

$result = $conn->query($sql);

$comandas = [];
while ($row = $result->fetch_assoc()) {
  $comandas[] = $row;
}

//retorna os dados como JSON
header('Content-Type: application/json');
echo json_encode($comandas);
?>
