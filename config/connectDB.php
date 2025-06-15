<?php
// Arquivo de configuração com constantes do sistema

// Configurações do Banco de Dados
define('DB_HOST', 'localhost');
define('DB_USER', 'root');         // Altere para seu usuário do MySQL
define('DB_PASS', 'LukeSo2711@');    // Altere para sua senha do MySQL
define('DB_NAME', 'streamingsn_db');  // Nome do banco de dados

// Constantes de diárias
define('DIARIA_CARRO', 100.00);
define('DIARIA_MOTO', 50.00);

// Definir senhas padrão para facilitar debug - use apenas em desenvolvimento
define('ADMIN_PASSWORD', 'admin123');
define('USER_PASSWORD', 'user123');

/**
 * Função para criar a conexão com o banco de dados
 * @return PDO Objeto de conexão PDO
 */
function getConnection() {
    try {
        $pdo = new PDO(
            'mysql:host=' . DB_HOST . ';dbname=' . DB_NAME . ';charset=utf8mb4',
            DB_USER,
            DB_PASS,
            [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::ATTR_EMULATE_PREPARES => false
            ]
        );
        return $pdo;
    } catch (PDOException $e) {
        // Em produção, evite mostrar detalhes do erro
        die('Erro de conexão com o banco de dados: ' . $e->getMessage());
    }
}