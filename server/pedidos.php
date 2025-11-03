<?php
session_start();
if (!isset($_SESSION['usuario_id'])) {
  echo "<script>alert('Você precisa estar logado.'); window.location.href='../templates/index.html';</script>";
  exit;
}

include('db/conexao.php');

$comanda_id = isset($_GET['comanda_id']) ? intval($_GET['comanda_id']) : 0;

// Buscar pedidos
$stmt = $conn->prepare("SELECT pedido.id, pedido.total_pedido 
                        FROM pedido 
                        JOIN pedido_comanda ON pedido.id = pedido_comanda.pedido_id 
                        WHERE pedido_comanda.comanda_id = ?");
$stmt->bind_param("i", $comanda_id);
$stmt->execute();
$pedidos = $stmt->get_result();

// Buscar info da comanda
$stmt = $conn->prepare("SELECT cliente_nome, numero_mesa FROM comanda WHERE id = ?");
$stmt->bind_param("i", $comanda_id);
$stmt->execute();
$info = $stmt->get_result()->fetch_assoc();
?>


<!DOCTYPE html>
<html lang="pt-br">
<head>
  <meta charset="UTF-8">
  <title>Pedidos da Comanda</title>
  <style>
    body {
      margin: 0;
      font-family: Verdana, Geneva, Tahoma, sans-serif;
      background-color: hsl(60, 31%, 94%);
      display: flex;
      flex-direction: column;
      align-items: center;
      padding: 2%;
    }
    h1 {
      color: hsla(240, 100%, 50%, 0.7);
    }
    table {
      width: 80%;
      margin-top: 2%;
      border-collapse: collapse;
      background-color: white;
    }
    th, td {
      padding: 1%;
      border: 1px solid #ccc;
      text-align: center;
    }
    .btn {
      margin-top: 2%;
      padding: 1% 2%;
      font-size: 1em;
      border: none;
      border-radius: 5px;
      background-color: hsla(240, 100%, 50%, 0.7);
      color: white;
      cursor: pointer;
      transition: background-color 0.3s ease;
    }
    .btn:hover {
      background-color: hsla(240, 100%, 50%, 0.5);
    }
    a {
      text-decoration: none;
      color: white;
    }
  </style>
</head>
<body>
  <h1>Pedidos da Comanda #<?= $comanda_id ?></h1>
  <p>Cliente: <strong><?= $info['cliente_nome'] ?></strong> | Mesa: <strong><?= $info['numero_mesa'] ?></strong></p>

  <table>
    <tr>
      <th>ID do Pedido</th>
      <th>Total (R$)</th>
    </tr>
    <?php while($row = $pedidos->fetch_assoc()): ?>
    <tr>
      <td><?= $row['id'] ?></td>
      <td><?= number_format($row['total_pedido'], 2, ',', '.') ?></td>
    </tr>
    <?php endwhile; ?>
  </table>

  <button class="btn"><a href="dashboard.php">Voltar ao Dashboard</a></button>
</body>
</html>
