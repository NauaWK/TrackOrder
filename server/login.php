<?php
include('db/conexao.php');

//comando necessário para usar $_SESSION
session_start();

$nome = $_POST['nome'];
$senha = $_POST['senha'];

$sql = "SELECT id, senha, funcao FROM usuario WHERE nome = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $nome);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 1) {
    //pegando os dados do usuário retornados pela query com fetch_assoc
    $user = $result->fetch_assoc();
    //verificando senha recebida com a senha guardada no banco (com hash)
    if (password_verify($senha, $user['senha'])) {
        //se as senhas conincidirem, cria nova sessão para o usuário
        $_SESSION['usuario_id'] = $user['id'];
        $_SESSION['funcao'] = $user['funcao'];
        //redireciona para o dashboard (tela principal)
        header("Location: dashboard.php");
    } else {
        echo "<script>alert('Credenciais inválidas.'); window.location.href='../templates/index.html';</script>";
    }
} else {
    echo "<script>alert('Credenciais inválidas.'); window.location.href='../templates/index.html';</script>";
}
?>
