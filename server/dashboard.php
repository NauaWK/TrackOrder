<?php
session_start();
if (!isset($_SESSION['usuario_id'])) {
  echo "<script>alert('Você precisa estar logado.'); window.location.href='../templates/index.html';</script>";
  exit;
}

include('db/conexao.php');
$result = $conn->query("SELECT * FROM comanda WHERE status_comanda = 'Aberta'");
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
  <meta charset="UTF-8">
  <title>Dashboard</title>
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
    .btn {
      margin: 1% 0;
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
    .actions {
      display: flex;
      gap: 10px;
      justify-content: center;
    }
  </style>
</head>
<body>
  <h1>Comandas Abertas</h1>
  <a href="registrar_comanda.php"><button class="btn">Nova Comanda</button></a>
  <a href="estoque.php"><button class="btn">Estoque</button></a>
  <a href="logout.php"><button class="btn">Sair</button></a>

  <table>
    <tr>
      <th>ID</th>
      <th>Cliente</th>
      <th>Mesa</th>
      <th>Início</th>
      <th>Status</th>
      <th>Ações</th>
    </tr>
    <?php while($row = $result->fetch_assoc()): ?>
    <tr>
      <td><?= $row['id'] ?></td>
      <td><?= $row['cliente_nome'] ?></td>
      <td><?= $row['numero_mesa'] ?></td>
      <td><?= $row['hora_inicio'] ?></td>
      <td><?= $row['status_comanda'] ?></td>
      <td class="actions">
        <a href="pedidos.php?comanda_id=<?= $row['id'] ?>"><button class="btn">Ver Pedidos</button></a>
        <a href="registrar_pedido.php?comanda_id=<?= $row['id'] ?>"><button class="btn">Novo Pedido</button></a>
        <a href="fechar_comanda.php?comanda_id=<?= $row['id'] ?>"><button class="btn">Fechar Comanda</button></a>
      </td>
    </tr>
    <?php endwhile; ?>
  </table>
</body>
</html>
