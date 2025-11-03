<?php
session_start();
if (!isset($_SESSION['usuario_id'])) {
  echo "<script>alert('Você precisa estar logado.'); window.location.href='../templates/index.html';</script>";
  exit;
}

include('db/conexao.php');

// Buscar comandas abertas
$comandas = $conn->query("SELECT id, cliente_nome FROM comanda WHERE status_comanda = 'Aberta'");

// Buscar produtos disponíveis
$produtos = $conn->query("SELECT id, nome FROM produto");

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $comanda_id = $_POST['comanda_id'] ?? 0;
  $produto_id = $_POST['produto_id'] ?? 0;
  $quantidade = $_POST['quantidade'] ?? 1;

  // Verifica se a comanda está aberta
  $check = $conn->prepare("SELECT status_comanda FROM comanda WHERE id = ?");
  $check->bind_param("i", $comanda_id);
  $check->execute();
  $status = $check->get_result()->fetch_assoc()['status_comanda'];
  if ($status !== 'Aberta') {
    echo "<script>alert('Comanda já foi fechada.'); window.location.href='dashboard.php';</script>";
    exit;
  }

  // Buscar preço unitário
  $res = $conn->prepare("SELECT preco_unitario FROM produto WHERE id = ?");
  $res->bind_param("i", $produto_id);
  $res->execute();
  $preco = $res->get_result()->fetch_assoc()['preco_unitario'];
  $total = $preco * $quantidade;

  // Criar pedido
  $stmt = $conn->prepare("INSERT INTO pedido (total_pedido) VALUES (?)");
  $stmt->bind_param("d", $total);
  $stmt->execute();
  $pedido_id = $conn->insert_id;

  // Vincular produto ao pedido
  $stmt = $conn->prepare("INSERT INTO produto_pedido (pedido_id, produto_id, produto_quantidade) VALUES (?, ?, ?)");
  $stmt->bind_param("iii", $pedido_id, $produto_id, $quantidade);
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
    select, input {
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
    <h2>Novo Pedido</h2>
    <form method="POST">
      <label for="comanda_id">Comanda:</label>
      <select name="comanda_id" required>
        <option value="" disabled selected>Selecione uma comanda</option>
        <?php while($c = $comandas->fetch_assoc()): ?>
          <option value="<?= $c['id'] ?>">#<?= $c['id'] ?> - <?= $c['cliente_nome'] ?></option>
        <?php endwhile; ?>
      </select>

      <label for="produto_id">Produto:</label>
      <select name="produto_id" required>
        <option value="" disabled selected>Selecione um produto</option>
        <?php while($p = $produtos->fetch_assoc()): ?>
          <option value="<?= $p['id'] ?>"><?= $p['nome'] ?></option>
        <?php endwhile; ?>
      </select>

      <label for="quantidade">Quantidade:</label>
      <input type="number" name="quantidade" min="1" required>

      <button type="submit">Registrar Pedido</button>
    </form>
    <a href="dashboard.php">Voltar ao Dashboard</a>
  </div>
</body>
</html>
