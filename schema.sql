CREATE TABLE categorias (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(100) NOT NULL
) ENGINE=InnoDB;

CREATE TABLE produtos (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(150) NOT NULL,
    descricao TEXT,
    preco DECIMAL(10,2) NOT NULL,
    categoria_id INT NOT NULL,

    CONSTRAINT fk_produto_categoria
        FOREIGN KEY (categoria_id)
        REFERENCES categorias(id)
        ON DELETE RESTRICT
        ON UPDATE CASCADE
) ENGINE=InnoDB;

CREATE TABLE clientes (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(150) NOT NULL,
    email VARCHAR(150) NOT NULL UNIQUE,
    telefone VARCHAR(20),
    endereco VARCHAR(200)
) ENGINE=InnoDB;


INSERT INTO categorias (nome) VALUES
('HQs'),
('Colecionáveis'),
('Roupas'),
('Acessórios'),
('Filmes e Séries');


INSERT INTO produtos (nome, descricao, preco, categoria_id) VALUES
('HQ O Soldado Invernal', 'História em quadrinhos com Bucky Barnes', 39.90, 1),
('HQ Homem-Aranha', 'História em quadrinhos do Homem-Aranha', 42.90, 1),
('HQ Guardiões da Galáxia', 'História em quadrinhos dos Guardiões da Galáxia', 44.90, 1),

('Action Figure Bucky Barnes', 'Figura colecionável do Bucky Barnes', 159.90, 2),
('Funko Pop Homem de Ferro', 'Figura colecionável do Homem de Ferro', 129.90, 2),
('Funko Pop Groot', 'Figura colecionável do Groot', 119.90, 2),
('Action Figure Thor', 'Figura colecionável do Thor', 149.90, 2),

('Camiseta Winter Soldier', 'Camiseta temática do Soldado Invernal', 79.90, 3),
('Camiseta Capitão América', 'Camiseta temática do Capitão América', 84.90, 3),
('Moletom Wanda', 'Moletom inspirado na Wanda Maximoff', 119.90, 3),

('Chaveiro Homem-Aranha', 'Chaveiro temático do Homem-Aranha', 24.90, 4),
('Caneca Groot', 'Caneca temática do Groot', 49.90, 4),
('Caneca Loki', 'Caneca temática do Loki', 49.90, 4),

('Blu-ray Guardiões da Galáxia', 'Filme dos Guardiões da Galáxia', 54.90, 5),
('Blu-ray Vingadores: Ultimato', 'Filme dos Vingadores', 64.90, 5),
('Blu-ray Thor: Ragnarok', 'Filme do Thor', 59.90, 5);


INSERT INTO clientes (nome, email, telefone, endereco) VALUES
('Peter Parker', 'peter@marvel.com', '(11) 99999-1111', 'Nova York'),
('James Barnes', 'bucky@marvel.com', '(11) 99999-2222', 'Nova York'),
('Peter Quill', 'quill@marvel.com', '(11) 99999-3333', 'Milano'),
('Wanda Maximoff', 'wanda@marvel.com', '(11) 99999-4444', 'Westview'),
('Tony Stark', 'tony@marvel.com', '(11) 99999-5555', 'Malibu');