<?php
session_start();
if (!isset($_SESSION['usuario_id'])) {
  echo "<script>alert('Você precisa estar logado.'); window.location.href='../templates/index.html';</script>";
  exit;
}

include('db/conexao.php');
$result = $conn->query("SELECT * FROM comanda WHERE status_comanda = 'Aberta'");
?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <title>Dashboard</title>
    <style>
    body {
        margin: 0;
        font-family: Verdana, Geneva, Tahoma, sans-serif;
        background-color: #F7F7F9;
        display: flex;
        flex-direction: row;
        overflow: hidden;
    }

    main {
        display: flex;
        flex-direction: column;
        align-items: center;
        width: 80%;
        height: 100vh;
        padding-top: 3%;
        box-sizing: border-box;
        overflow: auto;
    }

    header {
        width: 20%;
        height: 100vh;
        background-color: #2B2F36;

    }

    .logo {
        margin: 5%;
    }

    nav {
        display: flex;
        flex-direction: column;
        min-height: 90vh;

    }

    .link {
        display: flex;
        flex-direction: column;

    }

    a {
        text-decoration: none;
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
    
    h1 {
        color: hsla(240, 100%, 50%, 0.7);
    }
    .Nbtn {
        display: block;
        font-size: 1em;
        border-radius: 10px;
        width: 100%;
        height: 100%;
        border: none;
        outline: none;
        padding: 20px;
        background-color: hsla(240, 100%, 50%, 0.7);
        color: white;
        cursor: pointer;
        transition: background-color 0.3s ease;
    }

    .Nbtn:hover {
        background-color: hsla(240, 100%, 50%, 0.5);
    }
    
    .Nbtn:active {
        background-color: hsla(240, 100%, 50%, 0.3);
    }

    .btn:hover {
        background-color: #404650ff;
    }

    #closeBtn{
        background-color: hsla(0, 100%, 50%, 0.7);
    }
    #closeBtn:hover{
        background-color: hsla(0, 100%, 50%, 0.5);
    }
    #closeBtn:active{
        background-color: hsla(0, 100%, 50%, 0.3);
    }

    div.sair {
        margin-top: auto;
    }

    div.barra {
        width: 100%;
        height: 100%;
    }

    .cards-container {
        display: flex;
        flex-wrap: wrap;
        justify-content: center;
        gap: 20px;
        margin-top: 20px;
        width: 90%;
        margin-bottom: 30px;
    }

    .card {
        background-color: white;
        border-radius: 12px;
        box-shadow: 0 2px 6px rgba(0, 0, 0, 0.1);
        padding: 20px;
        width: 250px;
        height: 350px;
        display: flex;
        flex-direction: column;
        justify-content: space-between;
        transition: transform 0.2s ease;
        margin-top: 30px;
        background-color: hsl(60, 31%, 94%);

    }

    .card:hover {
        transform: scale(1.03);
        background-color: hsla(60, 8%, 83%, 1.00);
    }

    .card div {
        margin-bottom: 8px;
        color: #333;

    }

    .card strong {
        color: #0040afff;

    }

    .actions {
        display: flex;
        flex-direction: column;
        gap: 10px;
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
    </style>
</head>

<body>
    <header>
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
        <h1>Comandas Abertas</h1>

        <a href="registrar_comanda.php"><button class="Nbtn">Nova Comanda</button></a>

        <div class="cards-container">
            <?php while ($row = $result->fetch_assoc()): ?>
            <div class="card">
                <div><strong>ID:</strong> <?= $row['id'] ?></div>
                <div><strong>Cliente:</strong> <?= $row['cliente_nome'] ?></div>
                <div><strong>Mesa:</strong> <?= $row['numero_mesa'] ?></div>
                <div><strong>Início:</strong> <?= $row['hora_inicio'] ?></div>
                <div><strong>Status:</strong> <?= $row['status_comanda'] ?></div>

                <div class="actions">
                    <a href="pedidos.php?comanda_id=<?= $row['id'] ?>"><button class="Nbtn">Ver Pedidos</button></a>
                    <a href="registrar_pedido.php?comanda_id=<?= $row['id'] ?>"><button class="Nbtn">Novo
                            Pedido</button></a>
                    <button class="Nbtn" id="closeBtn" onclick="fecharComanda(<?= $row['id'] ?>, this)">Fechar</button>

                </div>
            </div>

            <?php endwhile; ?>
        </div>
    </main>
    <script>
function fecharComanda(id, botao) {
  

  fetch('fechar_comanda.php', {
    method: 'POST',
    headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
    body: 'comanda_id=' + encodeURIComponent(id)
  })
  .then(response => response.json())
  .then(data => {
    if (data.sucesso) {
      // Mostra mensagem de sucesso
      mostrarAviso('✅ Comanda fechada com sucesso!', 'sucesso');
      // Remove o card da comanda fechada
      botao.closest('.card').remove();
    } else {
      mostrarAviso('❌ Erro: ' + data.erro, 'erro');
    }
  })
  .catch(() => {
    mostrarAviso('❌ Erro na comunicação com o servidor.', 'erro');
  });
}

function mostrarAviso(texto, tipo) {
  let aviso = document.createElement('div');
  aviso.textContent = texto;
  aviso.style.position = 'fixed';
  aviso.style.top = '20px';
  aviso.style.right = '20px';
  aviso.style.padding = '15px 25px';
  aviso.style.borderRadius = '8px';
  aviso.style.color = 'white';
  aviso.style.fontWeight = 'bold';
  aviso.style.zIndex = '1000';
  aviso.style.backgroundColor = tipo === 'sucesso' ? 'green' : 'red';
  aviso.style.boxShadow = '0 2px 10px rgba(0,0,0,0.2)';
  document.body.appendChild(aviso);

  setTimeout(() => aviso.remove(), 3000);
}
</script>

</body>

</html>