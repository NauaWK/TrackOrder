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
      padding: 10px 15px;
    }
    .btn:hover {
      background-color: hsla(240, 100%, 50%, 0.5);
    }
    .btn-container {
      display: flex;
      gap: 10px;
      margin-bottom: 1%;
    }    
    .btn-editar {
      background-color: #007bff;
      padding: 5px 10px;
      font-size: 0.9em;
      margin: 0 2px;
    }
    .btn-editar:hover {
      background-color: #0056b3;
    }
    #editLink{
      text-decoration: none;
    }
    .btn-excluir {
      background-color: #dc3545;
      padding: 5px 10px;
      font-size: 0.9em;
      margin: 0 2px;
    }
    .btn-excluir:hover {
      background-color: #c82333;
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
      vertical-align: middle;
    }
    
    .estoque th:last-child,
    .estoque td:last-child {
        text-align: center;
    }

    .estoque tr:nth-child(even) {
      background-color: #f2f2f2;
    }

    .estoque tbody tr:hover {
      background-color: #e0e0e0;
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
      <th>Ações</th>
    </tr>
    </thead>
    
    <tbody>
      <?php while($p = $produtos->fetch_assoc()): ?>
      <tr>
        <td><?= $p['id'] ?></td>
        <td><?= $p['nome'] ?></td>
        <td><?= $p['quantidade'] ?></td>
        <td><?= number_format($p['preco_unitario'], 2, ',', '.') ?></td>
        
        <td>
          <a href="editar_produto.php?id=<?= $p['id'] ?>" id="editLink">
            <button class="btn btn-editar">Editar</button>
          </a>
          <form action="excluir_produto.php" method="POST" style="display: inline-block;" onsubmit="return confirm('Tem certeza que deseja excluir?');">
            <input type="hidden" name="produto_id" value="<?= $p['id'] ?>">
            <button type="submit" class="btn btn-excluir">Excluir</button>
          </form>
        </td>
      </tr>
      <?php endwhile; ?>
    </tbody>
  </table>
</body>
</html>