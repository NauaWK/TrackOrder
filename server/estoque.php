<?php
session_start();
if (!isset($_SESSION['funcao']) || $_SESSION['funcao'] !== 'gerente') {
  echo "<script>alert('Acesso restrito aos gerentes.'); window.location.href='dashboard.php';</script>";
  exit;
}

include('db/conexao.php');
$produtos = $conn->query("SELECT * FROM produto");
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
  <meta charset="UTF-8">
  <title>Estoque</title>
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
      margin: 1% 0.5%;
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
    .btn-container {
      display: flex;
      gap: 10px;
      margin-bottom: 1%;
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
  </style>
</head>
<body>
  <h1>Estoque de Produtos</h1>

  <div class="btn-container">
    <a href="registrar_produto.php"><button class="btn">Novo Produto</button></a>
    <a href="dashboard.php"><button class="btn">Voltar ao Dashboard</button></a>
  </div>

  <table>
    <tr>
      <th>ID</th>
      <th>Nome</th>
      <th>Quantidade</th>
      <th>Preço Unitário (R$)</th>
    </tr>
    <?php while($p = $produtos->fetch_assoc()): ?>
    <tr>
      <td><?= $p['id'] ?></td>
      <td><?= $p['nome'] ?></td>
      <td><?= $p['quantidade'] ?></td>
      <td><?= number_format($p['preco_unitario'], 2, ',', '.') ?></td>
    </tr>
    <?php endwhile; ?>
  </table>
</body>
</html>
