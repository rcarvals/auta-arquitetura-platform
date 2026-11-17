<?php
// src/config.php

// 1. Inicia a sessão para controle do login administrativo
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// 2. Caminho Base e URL
define('BASE_DIR', dirname(__DIR__));
define('URL_BASE', 'https://www.autaarquitetura.com.br');

// 3. Credenciais do Painel Administrativo
define('ADMIN_USER', 'xxxxxxxxxxxxxxxxxxxxxxxxxx');
define('ADMIN_PASS', 'xxxxxxxxxxxxxxxxxxxxxxxxxx

// 4. Configurações do Banco de Dados (Definidas uma única vez)
define('DB_HOST', '127.0.0.1'); 
define('DB_NAME', 'xxxxxxxxxxxxx');
define('DB_USER', 'xxxxxxxxxxxxxxxxxxx');
define('DB_PASS', 'xxxxxxxxxxxxxxxxxxxxx');

// 5. Conexão PDO
try {
    $pdo = new PDO(
        "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=utf8mb4", 
        DB_USER, 
        DB_PASS, 
        [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
        ]
    );
} catch (PDOException $e) {
    die("Erro na conexão com o banco de dados: " . $e->getMessage());
}