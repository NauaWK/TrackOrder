<?php
session_start();
if (!isset($_SESSION['usuario_id'])) {
  echo "<script>alert('Você precisa estar logado.'); window.location.href='../templates/index.html';</script>";
  exit;
}
if (!isset($_SESSION['funcao']) || $_SESSION['funcao'] !== 'gerente') {
  echo "<script>alert('Acesso restrito aos gerentes.'); window.location.href='dashboard.php';</script>";
  exit;
}

include('db/conexao.php');
$result = $conn->query("SELECT * FROM comanda WHERE status_comanda = 'Fechada'");
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Histórico</title>
<style>
  body {
    margin: 0;
    font-family: Verdana, Geneva, Tahoma, sans-serif;
    background-color: #F7F7F9;
    display: flex;
    flex-direction: row;
  }

  main {
    display: flex;
    flex-direction: column;
    align-items: center;
    width: 80%;
    height: 100vh;
    padding-top: 3%;
    box-sizing: border-box;
    overflow-y: auto;
  }

  header {
    width: 20%;
    height: 100vh;
    background-color: #2B2F36;
  }

  .logo {
    margin: 5%;
  }

  nav {
    display: flex;
    flex-direction: column;
    min-height: 90vh;
  }

  div.barra {
      width: 100%;
      height: 100%;
  }

  a {
    text-decoration: none;
  }

  h1 {
    color: hsla(240, 100%, 50%, 0.7);
  }

  .btn {
      display: block;
      font-size: 1em;
      width: 100%;
      height: 100%;
      border: none;
      outline: none;
      padding: 50px;
      background-color: #2B2F36;
      color: white;
      cursor: pointer;
      transition: background-color 0.3s ease;
  }

  .btn:hover {
    background-color: #404650ff;
  }

  .sair {
    margin-top: auto;
  }

  #trackText {
      color: hsla(0, 100%, 50%, 0.7);
  }

  #orderText {
      color: hsla(240, 100%, 50%, 0.7);
  }

  .spanTextTitle {
      font-size: 2.5em;
      font-weight: bold;
  }


  .historico {
    width: 90%;
    margin-top: 30px;
    border-collapse: collapse;
    background-color: white;
    border-radius: 10px;
    overflow: hidden;
    box-shadow: 0 2px 6px rgba(0, 0, 0, 0.1);
  }

  .historico thead {
    background-color: #2B2F36;
    color: white;
  }

  .historico th, .historico    td {
    padding: 15px;
    text-align: left;
  }

  .historico tr:nth-child(even) {
    background-color: #f2f2f2;
  }

  .historico tbody tr:hover {
    background-color: #e0e0e0;
    cursor: pointer;
  }
</style>
</head>
<body>
<header>
  <div class="logo">
    <span id="trackText" class="spanTextTitle">Track</span><span id="orderText" class="spanTextTitle">Order</span>
  </div>

  <nav class="links">
    <div class="barra">
      <a href="dashboard.php"><button class="btn">Dashboard</button></a>
      <a href="estoque.php"><button class="btn">Estoque</button></a>
      <a href=""><button class="btn">Histórico</button></a>
      <a href="info.php"><button class="btn">Estatística </button></a>
    </div>
    <div class="sair">
      <a href="logout.php"><button class="btn">Sair</button></a>
    </div>
  </nav>
</header>

<main>
  <h1>Histórico de Comandas</h1>

  <table class="historico">
    <thead>
      <tr>
        <th>ID</th>
        <th>Cliente</th>
        <th>Mesa</th>
        <th>Início</th>
        <th>Status</th>
      </tr>
    </thead>
    <tbody>
      <?php while ($row = $result->fetch_assoc()): ?>
        <tr onclick="window.location.href='pedidos.php?comanda_id=<?= $row['id'] ?>'">
          <td><?= $row['id'] ?></td>
          <td><?= $row['cliente_nome'] ?></td>
          <td><?= $row['numero_mesa'] ?></td>
          <td><?= $row['hora_inicio'] ?></td>
          <td><?= $row['status_comanda'] ?></td>
        </tr>
      <?php endwhile; ?>
    </tbody>
  </table>
</main>
</body>
</html>
