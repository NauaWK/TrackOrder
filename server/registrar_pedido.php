<?php
session_start();
if (!isset($_SESSION['usuario_id'])) {
  echo "<script>alert('Você precisa estar logado.'); window.location.href='../templates/index.html';</script>";
  exit;
}

include('db/conexao.php');

// Verifica se o ID da comanda foi passado na URL
$comanda_id = $_GET['comanda_id'] ?? 0;
if ($comanda_id == 0) {
  echo "<script>alert('Comanda não especificada.'); window.location.href='dashboard.php';</script>";
  exit;
}

// Buscar produtos disponíveis
$produtos = $conn->query("SELECT id, nome FROM produto");

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $produto_id = $_POST['produto_id'] ?? 0;
  $quantidade = $_POST['quantidade'] ?? 1;
  $observacao = $_POST['observacao'] ?? null;

  // Verifica se a comanda está aberta
  $check = $conn->prepare("SELECT status_comanda FROM comanda WHERE id = ?");
  $check->bind_param("i", $comanda_id);
  $check->execute();
  $status = $check->get_result()->fetch_assoc()['status_comanda'];
  if ($status !== 'Aberta') {
    echo "<script>alert('Comanda já foi fechada.'); window.location.href='dashboard.php';</script>";
    exit;
  }

  // Verifica estoque e busca preço
  $res = $conn->prepare("SELECT quantidade, preco_unitario FROM produto WHERE id = ?");
  $res->bind_param("i", $produto_id);
  $res->execute();
  $produto_info = $res->get_result()->fetch_assoc();

  if ($produto_info['quantidade'] < $quantidade) {
    echo "<script>alert('Estoque insuficiente para esse produto.'); window.location.href='dashboard.php';</script>";
    exit;
  }

  $preco = $produto_info['preco_unitario'];
  $total = $preco * $quantidade;

  // Criar pedido
  $stmt = $conn->prepare("INSERT INTO pedido (total_pedido, observacao) VALUES (?, ?)");
  $stmt->bind_param("ds", $total, $observacao);
  $stmt->execute();
  $pedido_id = $conn->insert_id;

  // Vincular produto ao pedido
  $stmt = $conn->prepare("INSERT INTO produto_pedido (pedido_id, produto_id, produto_quantidade) VALUES (?, ?, ?)");
  $stmt->bind_param("iii", $pedido_id, $produto_id, $quantidade);
  $stmt->execute();

  // Atualizar estoque do produto
  $stmt = $conn->prepare("UPDATE produto SET quantidade = quantidade - ? WHERE id = ?");
  $stmt->bind_param("ii", $quantidade, $produto_id);
  $stmt->execute();

  // Vincular pedido à comanda
  $stmt = $conn->prepare("INSERT INTO pedido_comanda (pedido_id, comanda_id) VALUES (?, ?)");
  $stmt->bind_param("ii", $pedido_id, $comanda_id);
  $stmt->execute();

  // Atualizar valor total da comanda
  $stmt = $conn->prepare("UPDATE comanda SET valor_total = valor_total + ? WHERE id = ?");
  $stmt->bind_param("di", $total, $comanda_id);
  $stmt->execute();

  header("Location: dashboard.php");
  exit;
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
  <meta charset="UTF-8">
  <title>Novo Pedido</title>
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
    select, input {
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
    <h2>Novo Pedido - Comanda #<?= htmlspecialchars($comanda_id) ?></h2>
    <form method="POST">
      <input type="hidden" name="comanda_id" value="<?= htmlspecialchars($comanda_id) ?>">

      <label for="produto_id">Produto:</label>
      <select name="produto_id" required>
        <option value="" disabled selected>Selecione um produto</option>
        <?php while($p = $produtos->fetch_assoc()): ?>
          <option value="<?= $p['id'] ?>"><?= $p['nome'] ?></option>
        <?php endwhile; ?>
      </select>

      <label for="quantidade">Quantidade:</label>
      <input type="number" name="quantidade" min="1" required>

      <label for="observacao">Observação:</label>
      <textarea name="observacao" rows="3" placeholder="Ex: sem cebola, ponto da carne..."></textarea>

      <button type="submit">Registrar Pedido</button>
    </form>
    <a href="dashboard.php">Voltar ao Dashboard</a>
  </div>
</body>
</html>
