-- Criação do banco de dados
CREATE DATABASE IF NOT EXISTS trackorder;
USE trackorder;

-- Tabela de usuários (funcionários)
CREATE TABLE usuario (
  id INT AUTO_INCREMENT PRIMARY KEY,
  nome VARCHAR(50) NOT NULL,
  senha VARCHAR(255) NOT NULL,
  funcao VARCHAR(20) NOT NULL
);

-- Tabela de produtos
CREATE TABLE produto (
  id INT AUTO_INCREMENT PRIMARY KEY,
  nome VARCHAR(100) NOT NULL,
  quantidade INT NOT NULL,
  preco_unitario DECIMAL(10,2) NOT NULL
);

-- Tabela de comandas
CREATE TABLE comanda (
  id INT AUTO_INCREMENT PRIMARY KEY,
  cliente_nome VARCHAR(100),
  numero_mesa INT,
  observacao TEXT,
  hora_inicio DATETIME,
  valor_total DECIMAL(10,2),
  status_comanda VARCHAR(20)
);

-- Tabela de pedidos
CREATE TABLE pedido (
  id INT AUTO_INCREMENT PRIMARY KEY,
  total_pedido DECIMAL(10,2)
);

-- Tabela de vínculo entre pedido e produto
CREATE TABLE produto_pedido (
  id INT AUTO_INCREMENT PRIMARY KEY,
  pedido_id INT,
  produto_id INT,
  produto_quantidade INT
);

-- Tabela de vínculo entre pedido e comanda
CREATE TABLE pedido_comanda (
  id INT AUTO_INCREMENT PRIMARY KEY,
  pedido_id INT,
  comanda_id INT
);

-- Inserção de produtos de exemplo
INSERT INTO produto (nome, quantidade, preco_unitario) VALUES
('Hambúrguer Clássico', 50, 18.90),
('Refrigerante Lata', 100, 6.50),
('Batata Frita Média', 80, 12.00),
('Suco Natural', 60, 8.00);
