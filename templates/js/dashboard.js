const modal = document.getElementById("modalNovaComanda");
const btnAbrir = document.getElementById("novaComandaBtn");
const formNovaComanda = document.getElementById("formNovaComanda");
const container = document.getElementById("comandasContainer");

btnAbrir.addEventListener("click", () => {
  modal.style.display = "flex";
});

modal.addEventListener("click", (e) => {
  if (e.target === modal) {
    modal.style.display = "none";
  }
});

formNovaComanda.addEventListener("submit", (e) => {
  e.preventDefault();
  const mesa = document.getElementById("mesaSelect").value;

  fetch("../../server/criar_comanda.php", {
    method: "POST",
    headers: { "Content-Type": "application/json" },
    body: JSON.stringify({ mesa_id: mesa })
  })
  .then(res => res.json())
  .then(() => {
    modal.style.display = "none";
    carregarComandas();
  });
});

function criarCardComanda(comanda) {
  const card = document.createElement("div");
  card.className = "comandaCard";

  const statusPedidosHTML = comanda.status_pedidos === "finalizado"
    ? `<span style="color: green;">✅ ${comanda.status_pedidos}</span>`
    : `<span style="color: orange;">🕒 ${comanda.status_pedidos}</span>`;

  const statusPagamentoHTML = comanda.status_pagamento === "paga"
    ? `<span style="color: green;">✔️ ${comanda.status_pagamento}</span>`
    : `<span style="color: red;">💰 ${comanda.status_pagamento}</span>`;

  card.innerHTML = `
    <h3>Mesa ${comanda.mesa_id}</h3>
    <p>Status dos pedidos: ${statusPedidosHTML}</p>
    <p>Status do pagamento: ${statusPagamentoHTML}</p>
    <button class="btnPedido" data-id="${comanda.id}">Adicionar Pedido</button>
    <button class="btnVer" data-id="${comanda.id}">Ver Pedidos</button>
    <button class="btnFechar" data-id="${comanda.id}">Fechar Comanda</button>
  `;

  return card;
}

function carregarComandas() {
  fetch("../../server/dashboard.php")
    .then(res => res.json())
    .then(data => {
      container.innerHTML = "";
      data.forEach(comanda => {
        const card = criarCardComanda(comanda);
        container.appendChild(card);
      });
      adicionarEventos();
    });
}

function adicionarEventos() {
  document.querySelectorAll(".btnFechar").forEach(btn => {
    btn.addEventListener("click", () => {
      const id = btn.getAttribute("data-id");
      fetch("../../server/fechar_comanda.php", {
        method: "POST",
        headers: { "Content-Type": "application/json" },
        body: JSON.stringify({ comanda_id: id })
      })
      .then(res => res.json())
      .then(() => carregarComandas());
    });
  });

  document.querySelectorAll(".btnPedido").forEach(btn => {
    btn.addEventListener("click", () => {
      const id = btn.getAttribute("data-id");
      console.log("Adicionar pedido para comanda", id);
    });
  });

  document.querySelectorAll(".btnVer").forEach(btn => {
    btn.addEventListener("click", () => {
      const id = btn.getAttribute("data-id");
      console.log("Ver pedidos da comanda", id);
    });
  });
}

carregarComandas();
