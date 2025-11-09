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

if (isset($_POST['produto_id'])) {
    
    $id = $_POST['produto_id'];

    $sql = "DELETE FROM produto WHERE id = ?";
    
    $stmt = $conn->prepare($sql);
    
    if ($stmt === false) {
        echo "Erro ao preparar a query: " . $conn->error;
        exit;
    }

    $stmt->bind_param("i", $id);

    if ($stmt->execute()) {
        $_SESSION['mensagem_sucesso'] = "Produto excluído com sucesso!";
    } else {
        $_SESSION['mensagem_erro'] = "Erro ao excluir o produto: " . $stmt->error;
    }

    $stmt->close();

} else {
    $_SESSION['mensagem_erro'] = "ID do produto não fornecido.";
}

$conn->close();

header("Location: estoque.php");
exit;
?>