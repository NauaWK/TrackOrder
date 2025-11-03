<?php
session_start();
if (!isset($_SESSION['funcao']) || $_SESSION['funcao'] !== 'gerente') {
  echo "<script>alert('Acesso restrito aos gerentes.'); window.location.href='dashboard.php';</script>";
  exit;
}

include('db/conexao.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $nome = $_POST['nome'];
  $quantidade = $_POST['quantidade'];
  $preco = $_POST['preco_unitario'];

  $sql = "INSERT INTO produto (nome, quantidade, preco_unitario) VALUES (?, ?, ?)";
  $stmt = $conn->prepare($sql);
  $stmt->bind_param("sid", $nome, $quantidade, $preco);
  $stmt->execute();

  echo "<script>alert('Produto cadastrado com sucesso!'); window.location.href='estoque.php';</script>";
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
  <meta charset="UTF-8">
  <title>Novo Produto</title>
  <style>
    body {
      width: 100vw;
      height: 100vh;
      display: flex;
      justify-content: center;
      align-items: center;
      background-color: hsl(60, 31%, 94%);
      font-family: Verdana, Geneva, Tahoma, sans-serif;
    }
    #formCard {
      width: 40%;
      padding: 3%;
      border-radius: 20px;
      background-color: hsl(60, 30%, 96%);
      display: flex;
      flex-direction: column;
      align-items: center;
    }
    form {
      display: flex;
      flex-direction: column;
      width: 100%;
    }
    label {
      margin-top: 10%;
    }
    input {
      font-size: 1em;
      padding: 5%;
      margin-top: 2%;
      border: none;
    }
    button {
      margin-top: 10%;
      color: #ffffff;
      background-color: hsla(240, 100%, 50%, 0.7);
      font-size: 1em;
      padding: 5% 12%;
      border-radius: 5px;
      border: none;
      cursor: pointer;
      transition: background-color 0.3s ease;
    }
    button:hover {
      background-color: hsla(240, 100%, 50%, 0.5);
    }
    a {
      margin-top: 5%;
      text-decoration: none;
      color: #000000;
    }
  </style>
</head>
<body>
  <div id="formCard">
    <h2>Novo Produto</h2>
    <form method="POST">
      <label for="nome">Nome do Produto:</label>
      <input type="text" name="nome" required>

      <label for="quantidade">Quantidade:</label>
      <input type="number" name="quantidade" min="1" required>

      <label for="preco_unitario">Preço Unitário (R$):</label>
      <input type="number" step="0.01" name="preco_unitario" required>

      <button type="submit">Cadastrar Produto</button>
    </form>
    <a href="estoque.php">Voltar ao Estoque</a>
  </div>
</body>
</html>
