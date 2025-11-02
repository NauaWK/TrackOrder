<?php
include 'db.php';
session_start();

//verifica se o usuário está logado
if (!isset($_SESSION['usuario_id'])) {
  http_response_code(403);
  echo json_encode(["erro" => "Não autorizado"]);
  exit();
}

//lê os dados enviados em JSON
$input = json_decode(file_get_contents("php://input"), true);
$mesa_id = $input['mesa_id'];
$garcom_id = $_SESSION['usuario_id'];
$hora_inicio = date("Y-m-d H:i:s");
$status_pedidos = "em andamento";
$status_pagamento = "aberta";
$quantidade_pedidos = 0;
$total_comanda = 0.00;

//insere a comanda no banco
$sql = "INSERT INTO comanda (mesa_id, garcom_id, hora_de_inicio, status_pedidos, status_pagamento, quantidade_pedidos, total_da_comanda)
        VALUES (?, ?, ?, ?, ?, ?, ?)";
$stmt = $conn->prepare($sql);
$stmt->bind_param("iisssid", $mesa_id, $garcom_id, $hora_inicio, $status_pedidos, $status_pagamento, $quantidade_pedidos, $total_comanda);

if ($stmt->execute()) {
  $id = $stmt->insert_id;
  echo json_encode([
    "id" => $id,
    "mesa_id" => $mesa_id,
    "status_pedidos" => $status_pedidos,
    "status_pagamento" => $status_pagamento
  ]);
} else {
  http_response_code(500);
  echo json_encode(["erro" => "Erro ao criar comanda"]);
}
?>
