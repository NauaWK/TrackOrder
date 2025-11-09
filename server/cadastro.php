<?php
include('db/conexao.php'); 

$nome = $_POST['nome'];
$senha = password_hash($_POST['senha'], PASSWORD_DEFAULT);
$cargo = $_POST['cargo'];

$check = $conn->prepare("SELECT id FROM usuario WHERE nome = ?");
$check->bind_param("s", $nome);
$check->execute();
$result = $check->get_result();

if ($result->num_rows > 0) {
    echo "Nome de usuário já cadastrado.";
    exit();
}

$sql = "INSERT INTO usuario (nome, senha, funcao) VALUES (?, ?, ?)";
$stmt = $conn->prepare($sql);
$stmt->bind_param("sss", $nome, $senha, $cargo);

if ($stmt->execute()) {
    header("Location: ../index.html");
    exit;
} else {
    echo "Erro ao cadastrar: " . $conn->error;
}
?>
