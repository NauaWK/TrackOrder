<?php
session_start();
if (!isset($_SESSION['usuario_id'])) {
  echo "<script>alert('Você precisa estar logado.'); window.location.href='../templates/index.html';</script>";
  exit;
}

if (!isset($_SESSION['funcao']) || $_SESSION['funcao'] !== 'gerente') {
  echo "<script>alert('Ação restrita aos gerentes.'); window.location.href='estoque.php';</script>";
  exit;
}

include('db/conexao.php');

$produto = null;
$id = 0;

if (isset($_GET['id'])) {
    $id = $_GET['id'];

    $sql = "SELECT * FROM produto WHERE id = ?";
    
    $stmt = $conn->prepare($sql);
    
    if ($stmt === false) {
        die("Erro ao preparar a query: " . $conn->error);
    }

    $stmt->bind_param("i", $id);
    $stmt->execute();
    $resultado = $stmt->get_result();

    if ($resultado->num_rows === 1) {
        $produto = $resultado->fetch_assoc();
    } else {
        echo "<script>alert('Produto não encontrado.'); window.location.href='estoque.php';</script>";
        exit;
    }

    $stmt->close();
} else {
    echo "<script>alert('ID do produto não fornecido.'); window.location.href='estoque.php';</script>";
    exit;
}

?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
  <meta charset="UTF-8">
  <title>Editar Produto</title>
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
      text-decoration: none;
      display: inline-block;
    }
    .btn:hover {
      background-color: hsla(240, 100%, 50%, 0.5);
    }
    .btn-cancelar {
        background-color: #6c757d;
    }
    .btn-cancelar:hover {
        background-color: #5a6268;
    }
    
    .form-container {
        background-color: white;
        padding: 30px;
        border-radius: 10px;
        box-shadow: 0 2px 6px rgba(0, 0, 0, 0.1);
        width: 50%;
        max-width: 600px;
    }
    .form-container div {
        margin-bottom: 15px;
    }
    .form-container label {
        display: block;
        margin-bottom: 5px;
        font-weight: bold;
    }
    .form-container input[type="text"],
    .form-container input[type="number"] {
        width: 95%;
        padding: 10px;
        border: 1px solid #ccc;
        border-radius: 5px;
        font-size: 1em;
    }
  </style>
</head>
<body>
  <h1>Editar Produto: <?php echo htmlspecialchars($produto['nome']); ?></h1>

  <div class="form-container">
    <form action="atualizar_produto.php" method="POST">
    
      <input type="hidden" name="produto_id" value="<?php echo $produto['id']; ?>">

      <div>
        <label for="nome">Nome do Produto:</label>
        <input type="text" id="nome" name="nome" 
               value="<?php echo htmlspecialchars($produto['nome']); ?>" required>
      </div>

      <div>
        <label for="quantidade">Quantidade:</label>
        <input type="number" id="quantidade" name="quantidade" 
               value="<?php echo htmlspecialchars($produto['quantidade']); ?>" required>
      </div>

      <div>
        <label for="preco">Preço Unitário (R$):</label>
        <input type="number" step="0.01" id="preco" name="preco_unitario" 
               value="<?php echo htmlspecialchars($produto['preco_unitario']); ?>" required>
      </div>

      <div>
        <button type="submit" class="btn">Salvar Alterações</button>
        <a href="estoque.php" class="btn btn-cancelar">Cancelar</a>
      </div>

    </form>
  </div>
</body>
</html>