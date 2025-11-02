<?php
include 'db.php';

$nome = $_POST['nome'];
//hash de senha como camada extra de segurança
$senha = password_hash($_POST['senha'], PASSWORD_DEFAULT);
$cargo = $_POST['cargo'];

//verificação de nome duplicado
$check = $conn->prepare("SELECT id FROM usuario WHERE nome = ?");
$check->bind_param("s", $nome);
$check->execute();
$result = $check->get_result();

if ($result->num_rows > 0) {
    echo "Nome de usuário já cadastrado.";
    exit();
}

//inserção segura com prepared statements
$sql = "INSERT INTO usuario (nome, senha, funcao) VALUES (?, ?, ?)";
$stmt = $conn->prepare($sql);
$stmt->bind_param("sss", $nome, $senha, $cargo);

if ($stmt->execute()) {
    header("Location: ../frontend/index.html");
} else {
    echo "Erro ao cadastrar: " . $conn->error;
}
?>
