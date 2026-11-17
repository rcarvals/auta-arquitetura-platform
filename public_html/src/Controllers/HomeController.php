<?php
// src/Controllers/HomeController.php

require_once BASE_DIR . '/src/Models/ProjectModel.php';

class HomeController {

    public function index() {
        $title = "Autá AD — Arquitetura e Interiores";

        $projectModel = new ProjectModel();
        $projects = $projectModel->getAllProjects();

        require_once BASE_DIR . '/src/Views/partials/header.php';
        require_once BASE_DIR . '/src/Views/pages/home.php';
        require_once BASE_DIR . '/src/Views/partials/footer.php';
    }

    public function about() {
        $title = "About — Autá AD";

        require_once BASE_DIR . '/src/Views/partials/header.php';
        require_once BASE_DIR . '/src/Views/pages/about.php';
        require_once BASE_DIR . '/src/Views/partials/footer.php';
    }

    public function contact() {
        $title = "Contact — Autá AD";

        require_once BASE_DIR . '/src/Views/partials/header.php';
        require_once BASE_DIR . '/src/Views/pages/contact.php';
        require_once BASE_DIR . '/src/Views/partials/footer.php';
    }
    
    // Processa o formulário de contato enviado do site
    public function sendContact() {
        $name    = filter_input(INPUT_POST, 'name', FILTER_SANITIZE_SPECIAL_CHARS);
        $email   = filter_input(INPUT_POST, 'email', FILTER_VALIDATE_EMAIL);
        $message = filter_input(INPUT_POST, 'message', FILTER_SANITIZE_SPECIAL_CHARS);
        
        $nome = trim($_POST['nome'] ?? '');
        $tipoProjeto = trim($_POST['tipo_projeto'] ?? '');
        $itens = trim($_POST['itens_planejados'] ?? $_POST['itens'] ?? '');
        
        // Função para verificar se uma string parece um texto aleatório inválido (sem vogais ou sem espaços)
        function isSpamText($text) {
            if (empty($text)) return false;
            
            // Se o texto não contiver pelo menos uma vogal (a, e, i, o, u)
            if (!preg_match('/[aeiouáéíóúâêîôûãõ]/i', $text)) {
                return true;
            }
            
            // Se for uma palavra única longa sem espaços e com mais de 5 consoantes seguidas
            if (!strpos($text, ' ') && preg_match('/[bcdfghjklmnpqrstvwxyz]{6,}/i', $text)) {
                return true;
            }
        
            return false;
        }

// Interrompe a execução silenciosamente se for identificado como spam
if (isSpamText($nome) || isSpamText($tipoProjeto) || isSpamText($itens)) {
    // Retorna mensagem de sucesso fictícia para enganar o bot e não forçar tentativas
    exit(json_encode(['status' => 'success', 'message' => 'Obrigado!']));
}

        if ($name && $email && $message) {
            $to      = "xxxxxx@xxxxxxxxxxxx.com.br"; // Insira seu e-mail de destino
            $subject = "Novo contato pelo site - " . $name;
            $body    = "Nome: $name\nE-mail: $email\n\nMensagem:\n$message";
            $headers = "From: " . $email . "\r\n" .
                       "Reply-To: " . $email . "\r\n" .
                       "X-Mailer: PHP/" . phpversion();

            // Envia o e-mail
            @mail($to, $subject, $body, $headers);

            $_SESSION['contact_success'] = "Mensagem enviada com sucesso! Em breve entraremos em contato.";
        } else {
            $_SESSION['contact_error'] = "Por favor, preencha todos os campos corretamente.";
        }

        header("Location: " . URL_BASE . "/contato");
        exit;
    }
}