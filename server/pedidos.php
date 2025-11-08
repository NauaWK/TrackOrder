<?php
session_start();
if (!isset($_SESSION['usuario_id'])) {
    echo "<script>alert('Você precisa estar logado.'); window.location.href='../templates/index.html';</script>";
    exit;
}

include('db/conexao.php');

$comanda_id = isset($_GET['comanda_id']) ? intval($_GET['comanda_id']) : 0;

// Buscar pedidos
$stmt = $conn->prepare("SELECT pedido.id, pedido.total_pedido, pedido.observacao 
                        FROM pedido 
                        JOIN pedido_comanda ON pedido.id = pedido_comanda.pedido_id 
                        WHERE pedido_comanda.comanda_id = ?");

$stmt->bind_param("i", $comanda_id);
$stmt->execute();
$pedidos = $stmt->get_result();

// Buscar info da comanda
$stmt = $conn->prepare("SELECT cliente_nome, numero_mesa FROM comanda WHERE id = ?");
$stmt->bind_param("i", $comanda_id);
$stmt->execute();
$info = $stmt->get_result()->fetch_assoc();
?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <title>Pedidos da Comanda</title>
    <style>
        body {
            margin: 0;
            font-family: Verdana, Geneva, Tahoma, sans-serif;
            background-color: hsl(60, 31%, 94%);
            display: flex;
            flex-direction: row;
            overflow: hidden;
        }

        header {
            width: 20%;
            height: 100vh;
            background-color: #2B2F36;
            position: fixed;
            left: -20%;
            top: 0;
            transition: left 0.3s ease;
            z-index: 10;
        }

        #menuToggle:checked~header {
            left: 0;
        }

        #menuToggle:checked~main {
            transform: translateX(20%);
        }

        .menu-btn {
            position: fixed;
            top: 20px;
            left: 20px;
            font-size: 2em;
            cursor: pointer;
            color: #0040afff;
            z-index: 10;
        }

        .close-btn {
            position: absolute;
            top: 20px;
            right: 20px;
            font-size: 28px;
            color: white;
            background: transparent;
            border: none;
            cursor: pointer;
            z-index: 15;
            transition: transform 0.2s ease;
        }

        .close-btn:hover {
            transform: scale(1.1);
        }

        .logo {
            margin: 5%;
        }

        nav {
            display: flex;
            flex-direction: column;
            min-height: 90vh;
        }

        .btn {
            display: block;
            font-size: 1em;
            width: 100%;
            height: 100%;
            border: none;
            outline: none;
            padding: 50px;
            background-color: #2B2F36;
            color: white;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        .btn:hover {
            background-color: #404650ff;
        }

        div.sair {
            margin-top: auto;
        }

        div.barra {
            width: 100%;
            height: 100%;
        }

        #trackText {
            color: hsla(0, 100%, 50%, 0.7);
        }

        #orderText {
            color: hsla(240, 100%, 50%, 0.7);
        }

        .spanTextTitle {
            font-size: 2.5em;
            font-weight: bold;
        }

        main {
            display: flex;
            flex-direction: column;
            align-items: center;
            width: 80%;
            height: 100vh;
            padding-top: 3%;
            box-sizing: border-box;
            margin-left: auto;
            margin-right: auto;
            transition: transform 0.3s ease;
        }

        .pedidos {
          width: 90%;
          margin-top: 30px;
          border-collapse: collapse;
          background-color: white;
          border-radius: 10px;
          overflow: hidden;
          box-shadow: 0 2px 6px rgba(0, 0, 0, 0.1);
        }

        .pedidos thead {
          background-color: #2B2F36;
          color: white;
        }

        .pedidos th, .pedidos    td {
          padding: 15px;
          text-align: left;
        }

        .pedidos tr:nth-child(even) {
          background-color: #f2f2f2;
        }

        .pedidos tbody tr:hover {
          background-color: #e0e0e0;
          
        }

        h1 {
            color: hsla(240, 100%, 50%, 0.7);
        }

        table {
            width: 80%;
            margin-top: 2%;
            border-collapse: collapse;
            background-color: white;
        }

        ul {
            margin: 0;
            padding-left: 20px;
        }

        .Nbtn {
            margin-top: 20px;
            padding: 10px 20px;
            font-size: 1em;
            border: none;
            border-radius: 5px;
            background-color: hsla(240, 100%, 50%, 0.7);
            color: white;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        .Nbtn:hover {
            background-color: hsla(240, 100%, 50%, 0.5);
        }

        a {
            text-decoration: none;
            color: white;
        }
    </style>
</head>

<body>
    <input type="checkbox" id="menuToggle" hidden>
    <label for="menuToggle" class="menu-btn">☰</label>

    <header>
        <label for="menuToggle" class="close-btn">×</label>
        <div class="logo">
            <span id="trackText" class="spanTextTitle">Track</span><span id="orderText"
                class="spanTextTitle">Order</span>
        </div>
        <nav class="links">
            <div class="barra">
                <a href="dashboard.php"><button class="btn">Dashboard</button></a>
                <a href="estoque.php"><button class="btn">Estoque</button></a>
                <a href="historico.php"><button class="btn">Historico</button></a>
                <a href="info.php"><button class="btn">Estatística </button></a>
            </div>
            <div class="sair">
                <a href="logout.php"><button class="btn">Sair</button></a>
            </div>
        </nav>
    </header>

    <main>
        <h1>Pedidos da Comanda #<?= $comanda_id ?></h1>
        <p>Cliente: <strong><?= $info['cliente_nome'] ?></strong> | Mesa: <strong><?= $info['numero_mesa'] ?></strong>
        </p>

        <table class="pedidos">
            <thead>
            <tr>
                <th>ID do Pedido</th>
                <th>Total (R$)</th>
                <th>Produtos</th>
                <th>Observação</th>
            </tr>
            </thead>
            <tbody>
            <?php while ($row = $pedidos->fetch_assoc()): ?>
            <?php
            $pedido_id = $row['id'];
            $stmt_produtos = $conn->prepare("SELECT produto.nome, produto_pedido.produto_quantidade 
                                            FROM produto_pedido 
                                            JOIN produto ON produto.id = produto_pedido.produto_id 
                                            WHERE produto_pedido.pedido_id = ?");
            $stmt_produtos->bind_param("i", $pedido_id);
            $stmt_produtos->execute();
            $produtos = $stmt_produtos->get_result();
            ?>
            <tr>
                <td><?= $row['id'] ?></td>
                <td><?= number_format($row['total_pedido'], 2, ',', '.') ?></td>
                <td>
                    <?php while ($prod = $produtos->fetch_assoc()): ?>
                        <?= $prod['produto_quantidade'] ?>x <?= $prod['nome'] ?><br>
                    <?php endwhile; ?>
                </td>
                <td><?= htmlspecialchars($row['observacao']) ?></td>
            </tr>
            <?php endwhile; ?> 
            </tbody>
        </table>

        <a href="dashboard.php"><button class="Nbtn">Voltar ao Dashboard</button></a>
    </main>
</body>

</html>