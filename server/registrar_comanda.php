<?php
session_start();
if (!isset($_SESSION['usuario_id'])) {
  echo "<script>alert('Você precisa estar logado.'); window.location.href='../templates/index.html';</script>";
  exit;
}

include('db/conexao.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $nome = $_POST['cliente_nome'] ?? '';
  $mesa = $_POST['numero_mesa'] ?? 0;

  $sql = "INSERT INTO comanda (cliente_nome, numero_mesa, hora_inicio, valor_total, status_comanda)
          VALUES (?, ?, NOW(), 0, 'Aberta')";
  $stmt = $conn->prepare($sql);
  $stmt->bind_param("si", $nome, $mesa);
  $stmt->execute();

  header("Location: dashboard.php");
  exit;
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
  <meta charset="UTF-8">
  <title>Nova Comanda</title>
  <style>
    body {
      width: 100vw;
      height: 100vh;
      display: flex;
      justify-content: center;
      align-items: center;
      background-color: hsl(60, 31%, 94%);
      font-family: Verdana, Geneva, Tahoma, sans-serif;
      overflow: hidden;
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
      margin-top: 2%;
    }
    input, textarea {
      font-size: 1em;
      padding: 3%;
      margin-top: 1%;
      border: none;
    }
    button {
      margin-top: 5%;
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
    a:hover{
      text-decoration: underline;
    }
  </style>
</head>
<body>
  <div id="formCard">
    <h2>Nova Comanda</h2>
    <form method="POST">
      <label for="cliente_nome">Nome do Cliente:</label>
      <input type="text" name="cliente_nome" required>

      <label for="numero_mesa">Número da Mesa:</label>
      <input type="number" name="numero_mesa" required>

      <button type="submit">Criar Comanda</button>
    </form>
    <a href="dashboard.php">Voltar ao Dashboard</a>
  </div>
</body>
</html>
