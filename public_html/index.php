<?php
// public_html/index.php

// 1. Diagnóstico de Erros
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// 2. Carrega as Configurações
if (file_exists(__DIR__ . '/src/config.php')) {
    require_once __DIR__ . '/src/config.php';
} else {
    die("Erro Crítico: O arquivo 'src/config.php' não foi encontrado.");
}

// Garante a constante BASE_DIR
if (!defined('BASE_DIR')) {
    define('BASE_DIR', __DIR__);
}

// 3. Carregamento Direto dos Controllers com Verificação Explicita
$homeControllerPath    = BASE_DIR . '/src/Controllers/HomeController.php';
$projectControllerPath = BASE_DIR . '/src/Controllers/ProjectController.php';
$adminControllerPath   = BASE_DIR . '/src/Controllers/AdminController.php';
$blogControllerPath    = BASE_DIR . '/src/Controllers/BlogController.php';

if (file_exists($homeControllerPath)) {
    require_once $homeControllerPath;
} else {
    die("Erro Crítico: O arquivo HomeController.php não foi encontrado no caminho: " . $homeControllerPath);
}

if (file_exists($projectControllerPath)) {
    require_once $projectControllerPath;
}

if (file_exists($adminControllerPath)) {
    require_once $adminControllerPath;
}

if (file_exists($blogControllerPath)) {
    require_once $blogControllerPath;
}

// 4. Captura e Limpa a URL
$requestUri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$path = rtrim($requestUri, '/');
if (empty($path)) {
    $path = '/';
}

// 5. Mapeamento de Rotas
switch ($path) {
    case '/':
        $controller = new HomeController();
        $controller->index();
        break;

    case '/projetos':
    case '/work':
        $controller = new ProjectController();
        $controller->index();
        break;

    case '/sobre':
        $controller = new HomeController();
        $controller->about();
        break;

    case '/contato':
        if (session_status() === PHP_SESSION_NONE) { session_start(); }
        require_once BASE_DIR . '/src/Controllers/ContactController.php';
        $controller = new ContactController();
        $controller->index();
        break;

    case '/contato/enviar':
        if (session_status() === PHP_SESSION_NONE) { session_start(); }
        require_once BASE_DIR . '/src/Controllers/ContactController.php';
        $controller = new ContactController();
        $controller->send();
        break;

    case (preg_match('/^\/projeto\/([a-zA-Z0-9-]+)$/', $path, $matches) ? true : false):
        $controller = new ProjectController();
        $controller->show($matches[1]);
        break;

    // --- ROTAS PÚBLICAS DO BLOG ---
    case '/blog':
        $controller = new BlogController();
        $controller->index();
        break;

    case '/blog/subcrever':
        $controller = new BlogController();
        $controller->subscribe();
        break;

    // PASSO 4: Rota para processamento do Checklist Interativo de Obra/Reforma
    case '/blog/checklist/enviar':
        $controller = new BlogController();
        $controller->submitChecklist();
        break;

    case (preg_match('/^\/blog\/([a-zA-Z0-9-]+)$/', $path, $matches) ? true : false):
        $controller = new BlogController();
        $controller->detail($matches[1]);
        break;

    // --- ROTAS ADMINISTRATIVAS DO BLOG ---
    case '/admin/blog/novo':
        $controller = new AdminController();
        $controller->createPost();
        break;

    case '/admin/blog/salvar':
        $controller = new AdminController();
        $controller->storePost();
        break;

    // --- ROTAS ADMINISTRATIVAS DE PROJETOS & USUÁRIO ---
    case '/admin':
        $controller = new AdminController();
        $controller->index();
        break;

    case '/admin/novo':
        $controller = new AdminController();
        $controller->create();
        break;

    case '/admin/salvar':
        $controller = new AdminController();
        $controller->store();
        break;

    case (preg_match('/^\/admin\/excluir\/([0-9]+)$/', $path, $matches) ? true : false):
        $controller = new AdminController();
        $controller->delete($matches[1]);
        break;

    // --- ROTAS DE AUTENTICAÇÃO DO ADMIN ---
    case '/admin/login':
        $controller = new AdminController();
        $controller->login();
        break;

    case '/admin/autenticar':
        $controller = new AdminController();
        $controller->authenticate();
        break;

    case '/admin/logout':
        $controller = new AdminController();
        $controller->logout();
        break;

    case (preg_match('/^\/admin\/blog\/disparar\/([0-9]+)$/', $path, $matches) ? true : false):
        $controller = new AdminController();
        $controller->dispararPost($matches[1]);
        break;
        
    // --- ROTAS DA CAPA DA HOMEPAGE ---
    case '/admin/hero':
        $controller = new AdminController();
        $controller->manageHero();
        break;

    case '/admin/hero/salvar':
        $controller = new AdminController();
        $controller->storeHero();
        break;

    case (preg_match('/^\/admin\/hero\/slide\/excluir\/([0-9]+)$/', $path, $matches) ? true : false):
        $controller = new AdminController();
        $controller->deleteSlide($matches[1]);
        break;    
    
    // --- ROTAS DE GERENCIAMENTO DE EQUIPE ---
    case '/admin/equipe':
        $controller = new AdminController();
        $controller->manageTeam();
        break;

    case '/admin/equipe/salvar':
        $controller = new AdminController();
        $controller->storeTeamMember();
        break;

    case (preg_match('/^\/admin\/equipe\/excluir\/([0-9]+)$/', $path, $matches) ? true : false):
        $controller = new AdminController();
        $controller->deleteTeamMember($matches[1]);
        break;    

    default:
        http_response_code(404);
        if (file_exists(BASE_DIR . '/src/Views/pages/404.php')) {
            require_once BASE_DIR . '/src/Views/pages/404.php';
        } else {
            echo "Página não encontrada (404).";
        }
        break;
}