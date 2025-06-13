-- Criação do banco de dados
CREATE DATABASE IF NOT EXISTS streamingSN_db
CHARACTER SET utf8mb4
COLLATE utf8mb4_unicode_ci;

-- Seleciona o banco de dados
USE streamingSN_db;

-- Criação da tabela de usuários
CREATE TABLE IF NOT EXISTS usuario (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL UNIQUE,
    email VARCHAR(255) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    perfil VARCHAR(20) NOT NULL,
    data_criacao DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

-- Criação da tabela de serie
CREATE TABLE IF NOT EXISTS serie (
    id INT AUTO_INCREMENT PRIMARY KEY,
    titulo VARCHAR(255) NOT NULL UNIQUE,
    imagem_path VARCHAR(255) NOT NULL COMMENT 'Caminho da imagem ilustrativa',
    sinopse VARCHAR(255) NOT NULL,
    release_date YEAR NOT NULL,
    generos VARCHAR(100) NOT NULL,
    preco FLOAT NOT NULL DEFAULT 4.99,
    disponivel BOOLEAN NOT NULL DEFAULT TRUE
) ENGINE=InnoDB;

-- Criação da tabela de filme
CREATE TABLE IF NOT EXISTS filme (
    id INT AUTO_INCREMENT PRIMARY KEY,
    titulo VARCHAR(255) NOT NULL UNIQUE,
    imagem_path VARCHAR(255) NOT NULL COMMENT 'Caminho da imagem ilustrativa',
    sinopse VARCHAR(255) NOT NULL,
    release_date YEAR NOT NULL,
    generos VARCHAR(100) NOT NULL,
    duracao_minutos INT NOT NULL,
    preco FLOAT NOT NULL DEFAULT 19.99,
    disponivel BOOLEAN NOT NULL DEFAULT TRUE
) ENGINE=InnoDB;

-- Criação da tabela de filme alugados
CREATE TABLE IF NOT EXISTS filme_alugados (
    id INT AUTO_INCREMENT PRIMARY KEY,
    data_aluguel DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    expira_em DATETIME NOT NULL,
    preco_pago FLOAT NOT NULL,
    usuario_id INT NOT NULL,
    filme_id INT NOT NULL,
    FOREIGN KEY (usuario_id) REFERENCES usuario(id),
    FOREIGN KEY (filme_id) REFERENCES filme(id)
) ENGINE=InnoDB;

-- Criação da tabela de serie alugados
CREATE TABLE IF NOT EXISTS serie_alugados (
    id INT AUTO_INCREMENT PRIMARY KEY,
    data_aluguel DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    expira_em DATETIME NOT NULL,
    preco_pago FLOAT NOT NULL,
    usuario_id INT NOT NULL,
    serie_id INT NOT NULL,
    FOREIGN KEY (usuario_id) REFERENCES usuario(id),
    FOREIGN KEY (serie_id) REFERENCES serie(id)
) ENGINE=InnoDB;

-- Criação da tabela de temporada
CREATE TABLE IF NOT EXISTS temporada (
    id INT AUTO_INCREMENT PRIMARY KEY,
    serie_id INT NOT NULL,
    number INT NOT NULL,
    FOREIGN KEY (serie_id) REFERENCES serie(id)
) ENGINE=InnoDB;

-- Criação da tabela de episodio
CREATE TABLE IF NOT EXISTS episodio (
    id INT AUTO_INCREMENT PRIMARY KEY,
    titulo VARCHAR(255) NOT NULL,
    temporada_id INT NOT NULL,
    FOREIGN KEY (temporada_id) REFERENCES temporada(id)
) ENGINE=InnoDB;

-- Insere usuários padrão
-- Nota: As senhas estão em formato hash, geradas com password_hash()
-- admin123 e user123 são as senhas em texto puro
INSERT INTO usuario (username, password, perfil) VALUES 
    ('admin', '$2y$10$4gAzJ/Kq4NFc.K3nXi.l0OQsRHxqZJ8/Z2MtMrjorJX66IvPZOOym', 'admin'),
    ('usuario', '$2y$10$reDVMnCMBItvD.Ru13M/Heqn0K5C3t8cL7.jxvAfLk1xEFXbqB9HG', 'usuario');
