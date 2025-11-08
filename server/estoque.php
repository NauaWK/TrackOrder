<?php
session_start();
if (!isset($_SESSION['usuario_id'])) {
  echo "<script>alert('Você precisa estar logado.'); window.location.href='../templates/index.html';</script>";
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

      .estoque {
  width: 90%;
  margin-top: 30px;
  border-collapse: collapse;
  background-color: white;
  border-radius: 10px;
  overflow: hidden;
  box-shadow: 0 2px 6px rgba(0, 0, 0, 0.1);
}

.estoque thead {
  background-color: #2B2F36;
  color: white;
}

.estoque th, .estoque td {
  padding: 15px;
  text-align: left;
}

.estoque tr:nth-child(even) {
  background-color: #f2f2f2;
}

.estoque tbody tr:hover {
  background-color: #e0e0e0;
  cursor: pointer;
}
    
  </style>
</head>
<body>
  <h1>Estoque de Produtos</h1>

  <div class="btn-container">
    <a href="registrar_produto.php"><button class="btn">Novo Produto</button></a>
    <a href="dashboard.php"><button class="btn">Voltar ao Dashboard</button></a>
  </div>

  <table class="estoque">
    <thead>
    <tr>
      <th>ID</th>
      <th>Nome</th>
      <th>Quantidade</th>
      <th>Preço Unitário (R$)</th>
    </tr>
    </thead>
    <?php while($p = $produtos->fetch_assoc()): ?>
      <tbody>
    <tr>
      <td><?= $p['id'] ?></td>
      <td><?= $p['nome'] ?></td>
      <td><?= $p['quantidade'] ?></td>
      <td><?= number_format($p['preco_unitario'], 2, ',', '.') ?></td>
    </tr>
    </tbody>
    <?php endwhile; ?>
  </table>
</body>
</html>
