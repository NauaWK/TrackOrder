<?php
session_start();
if (!isset($_SESSION['usuario_id'])) {
  echo "<script>alert('Acesso negado. Você precisa estar logado.'); window.location.href='../templates/index.html';</script>";
  exit;
}

if (!isset($_SESSION['funcao']) || $_SESSION['funcao'] !== 'gerente') {
  echo "<script>alert('Ação restrita aos gerentes.'); window.location.href='estoque.php';</script>";
  exit;
}

include('db/conexao.php');

if (isset($_POST['produto_id'], $_POST['nome'], $_POST['quantidade'], $_POST['preco_unitario'])) {
    
    $id = $_POST['produto_id'];
    $nome = $_POST['nome'];
    $quantidade = $_POST['quantidade'];
    $preco = $_POST['preco_unitario'];

    $sql = "UPDATE produto SET nome = ?, quantidade = ?, preco_unitario = ? WHERE id = ?";
    
    $stmt = $conn->prepare($sql);

    if ($stmt === false) {
        echo "Erro ao preparar a query: " . $conn->error;
        exit;
    }

    $stmt->bind_param("sdii", $nome, $quantidade, $preco, $id);

    if ($stmt->execute()) {
        $_SESSION['mensagem_sucesso'] = "Produto atualizado com sucesso!";
    } else {
        $_SESSION['mensagem_erro'] = "Erro ao atualizar o produto: " . $stmt->error;
    }

    $stmt->close();

} else {
    $_SESSION['mensagem_erro'] = "Dados incompletos para atualizar o produto.";
}

$conn->close();

header("Location: estoque.php");
exit;
?>