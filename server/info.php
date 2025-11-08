<?php
session_start();
if (!isset($_SESSION['usuario_id'])) {
  echo "<script>alert('Você precisa estar logado.'); window.location.href='../templates/index.html';</script>";
  exit;
}
if (!isset($_SESSION['funcao']) || $_SESSION['funcao'] !== 'gerente') {
  echo "<script>alert('Acesso restrito aos gerentes.'); window.location.href='dashboard.php';</script>";
  exit;
}

include('db/conexao.php');
$result = $conn->query("SELECT * FROM comanda WHERE status_comanda = 'Aberta'");

// Estatísticas
$total_produtos = $conn->query("SELECT COUNT(*) AS total FROM produto")->fetch_assoc()['total'];

$estoque = $conn->query("SELECT SUM(quantidade) AS qtd, SUM(quantidade * preco_unitario) AS valor FROM produto")->fetch_assoc();

$abertas = $conn->query("SELECT COUNT(*) AS total FROM comanda WHERE status_comanda = 'Aberta'")->fetch_assoc()['total'];

$fechadas = $conn->query("SELECT COUNT(*) AS total FROM comanda WHERE status_comanda = 'Fechada'")->fetch_assoc()['total'];

$media_comandas = $conn->query("SELECT AVG(valor_total) AS media FROM comanda WHERE valor_total IS NOT NULL")->fetch_assoc()['media'];

$mais_pedido = $conn->query("
    SELECT p.nome, SUM(pp.produto_quantidade) AS total_vendidos
    FROM produto_pedido pp
    JOIN produto p ON p.id = pp.produto_id
    GROUP BY p.id
    ORDER BY total_vendidos DESC
    LIMIT 1
")->fetch_assoc();

$grafico = $conn->query("
    SELECT p.nome, IFNULL(SUM(pp.produto_quantidade), 0) AS total
    FROM produto p
    LEFT JOIN produto_pedido pp ON p.id = pp.produto_id
    GROUP BY p.id
");

$produtos = [];
$quantidades = [];
while ($row = $grafico->fetch_assoc()) {
    $produtos[] = $row['nome'];
    $quantidades[] = $row['total'];
}
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
  <meta charset="UTF-8">
  <title>Dashboard - TrackOrder</title>
  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
  <style>
   body {
        margin: 0;
        font-family: Verdana, Geneva, Tahoma, sans-serif;
        background-color: #F7F7F9;
        display: flex;
        flex-direction: row;

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

    .container {
      width: 90%;
      max-width: 1200px;
      margin: 30px auto;
    }

    h2 {
      text-align: center;
      color: #333;
    }

    .cards {
      display: grid;
      grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
      gap: 20px;
      margin-top: 30px;
    }

    .card {
      background-color: white;
      padding: 20px;
      border-radius: 15px;
      box-shadow: 0 4px 10px rgba(0,0,0,0.1);
      text-align: center;
      transition: transform 0.2s ease;
    }

    .card:hover {
      transform: scale(1.03);
    }

    .card h5 {
      color: #555;
      margin-bottom: 10px;
    }

    .card p {
      font-size: 28px;
      color: #222;
      margin: 0;
    }

    .card small {
      color: #777;
    }

    .grafico {
      margin-top: 60px;
      text-align: center;
    }

    canvas {
      margin-top: 20px;
      background-color: white;
      border-radius: 15px;
      box-shadow: 0 4px 10px rgba(0,0,0,0.1);
      padding: 10px;
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
  <div class="container">
    <h2>📊 Dashboard - TrackOrder</h2>

    <div class="cards">
      <div class="card">
        <h5>Produtos</h5>
        <p><?= $total_produtos ?></p>
        <small>Total cadastrados</small>
      </div>

      <div class="card">
        <h5>Estoque</h5>
        <p><?= $estoque['qtd'] ?? 0 ?></p>
        <small>Itens disponíveis</small>
      </div>

      <div class="card">
        <h5>Valor em Estoque</h5>
        <p>R$ <?= number_format($estoque['valor'] ?? 0, 2, ',', '.') ?></p>
        <small>Valor total</small>
      </div>

      <div class="card">
        <h5>Comandas Abertas</h5>
        <p><?= $abertas ?></p>
        <small>Em andamento</small>
      </div>

      <div class="card">
        <h5>Comandas Fechadas</h5>
        <p><?= $fechadas ?></p>
        <small>Finalizadas</small>
      </div>

      <div class="card">
        <h5>Valor Médio</h5>
        <p>R$ <?= number_format($media_comandas ?? 0, 2, ',', '.') ?></p>
        <small>por comanda</small>
      </div>

      <div class="card">
        <h5>Mais Pedido</h5>
        <p><?= $mais_pedido['nome'] ?? 'Nenhum pedido ainda' ?></p>
        <small><?= $mais_pedido ? $mais_pedido['total_vendidos'].' vendidos' : '' ?></small>
      </div>
    </div>

    <div class="grafico">
      <h3>📈 Produtos Mais Vendidos</h3>
      <canvas id="graficoProdutos" height="120"></canvas>
    </div>
  </div>
  </main>

  <script>
    const ctx = document.getElementById('graficoProdutos');
    new Chart(ctx, {
      type: 'bar',
      data: {
        labels: <?= json_encode($produtos) ?>,
        datasets: [{
          label: 'Quantidade Vendida',
          data: <?= json_encode($quantidades) ?>,
          backgroundColor: ['#36A2EB', '#FF6384', '#FFCE56', '#4BC0C0', '#9966FF', '#FF9F40'],
        }]
      },
      options: {
        plugins: { legend: { display: false } },
        scales: {
          y: { beginAtZero: true, ticks: { stepSize: 1 } }
        }
      }
    });
  </script>
</body>
</html>
