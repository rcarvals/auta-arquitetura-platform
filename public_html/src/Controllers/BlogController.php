<?php
// src/Controllers/BlogController.php

require_once BASE_DIR . '/src/Models/PostModel.php';

class BlogController {
    private $postModel;

    public function __construct() {
        $this->postModel = new PostModel();
    }

    // Exibe a listagem principal do Blog com Busca, Categorias e Tags
    public function index() {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        // Captura os parâmetros GET da URL (Busca, Categoria e Tag)
        $search   = filter_input(INPUT_GET, 's', FILTER_SANITIZE_SPECIAL_CHARS);
        $category = filter_input(INPUT_GET, 'cat', FILTER_SANITIZE_SPECIAL_CHARS);
        $tag      = filter_input(INPUT_GET, 'tag', FILTER_SANITIZE_SPECIAL_CHARS);

        // Busca os posts filtrados e as listas dinâmicas para a sidebar
        $posts      = $this->postModel->getAllPosts($search, $category, $tag);
        $categories = $this->postModel->getCategories();
        $allTags    = $this->postModel->getAllTags();

        $title = "Blog & Publicações — Autá AD";

        require_once BASE_DIR . '/src/Views/partials/header.php';
        require_once BASE_DIR . '/src/Views/pages/blog.php';
        require_once BASE_DIR . '/src/Views/partials/footer.php';
    }

    // Exibe o artigo individual (Single Post)
    public function detail($slug) {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        $post = $this->postModel->getPostBySlug($slug);

        if (!$post) {
            header("Location: " . URL_BASE . "/blog");
            exit;
        }

        $title = htmlspecialchars($post['title']) . " — Autá AD";

        require_once BASE_DIR . '/src/Views/partials/header.php';
        require_once BASE_DIR . '/src/Views/pages/blog-detail.php';
        require_once BASE_DIR . '/src/Views/partials/footer.php';
    }

    // Processa o cadastro de leitores na newsletter (Tabela blog_cadastros)
    public function subscribe() {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        $nome  = trim($_POST['name'] ?? '');
        $email = filter_input(INPUT_POST, 'email', FILTER_VALIDATE_EMAIL);
        
        $nome = trim($_POST['nome'] ?? '');
        $tipoProjeto = trim($_POST['tipo_projeto'] ?? '');
        $itens = trim($_POST['itens_planeados'] ?? $_POST['itens'] ?? '');
        
        // Função para verificar se uma string parece um texto aleatório inválido (sem vogais ou sem espaços)
        function isSpamText($text) {
            if (empty($text)) return false;
            
            // Se o texto não contiver pelo menos uma vogal (a, e, i, o, u)
            if (!preg_match('/[aeiouáéíóúâêîôûãõ]/i', $text)) {
                return true;
            }
            
            // Se for uma palavra única longa sem espaços e com mais de 3 consoantes seguidas
            if (!strpos($text, ' ') && preg_match('/[bcdfghjklmnpqrstvwxyz]{4,}/i', $text)) {
                return true;
            }
        
            return false;
        }
        
        // Interrompe a execução silenciosamente se for identificado como spam
        if (isSpamText($nome) || isSpamText($tipoProjeto) || isSpamText($itens)) {
            // Retorna mensagem de sucesso fictícia para enganar o bot e não forçar tentativas
            exit(json_encode(['status' => 'success', 'message' => 'Obrigado!']));
        }

        if (!empty($nome) && !empty($email)) {
            global $pdo;
            try {
                $stmt = $pdo->prepare("INSERT INTO blog_cadastros (nome, email) VALUES (:nome, :email)");
                $stmt->execute([
                    ':nome'  => $nome,
                    ':email' => $email
                ]);

                $_SESSION['blog_notice'] = "Subscrição efetuada com sucesso!";
            } catch (\PDOException $e) {
                $_SESSION['blog_notice'] = "Este e-mail já se encontra registado.";
            }
        } else {
            $_SESSION['blog_notice'] = "Por favor, preencha um e-mail válido.";
        }

        header("Location: " . URL_BASE . "/blog");
        exit;
    }
    
    // Processa a submissão do Checklist Interativo de Obra/Reforma
    public function submitChecklist() {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        $nome  = filter_input(INPUT_POST, 'nome', FILTER_SANITIZE_SPECIAL_CHARS);
        $email = filter_input(INPUT_POST, 'email', FILTER_VALIDATE_EMAIL);
        $whatsapp = filter_input(INPUT_POST, 'whatsapp', FILTER_SANITIZE_SPECIAL_CHARS);
        $tipo_servico = filter_input(INPUT_POST, 'tipo_servico', FILTER_SANITIZE_SPECIAL_CHARS);
        $itens = filter_input(INPUT_POST, 'itens_selecionados', FILTER_SANITIZE_SPECIAL_CHARS);

        if ($nome && $email) {
            global $pdo;

            // 1. Grava no banco de dados
            try {
                $stmt = $pdo->prepare("INSERT INTO blog_checklists (nome, email, whatsapp, tipo_servico, itens_selecionados) VALUES (:nome, :email, :whatsapp, :tipo, :itens)");
                $stmt->execute([
                    ':nome'     => $nome,
                    ':email'    => $email,
                    ':whatsapp' => $whatsapp,
                    ':tipo'     => $tipo_servico,
                    ':itens'    => $itens
                ]);
            } catch (\PDOException $e) {
                // Silencioso se der erro na tabela
            }

            // 2. Disparo de E-mails via Socket SMTP Titan
            $smtpHost = 'smtp.titan.email';
            $smtpPort = 465;
            $smtpUser = 'xxxxxx@xxxxxxxxxxxx.com.br';
            $smtpPass = 'xxxxxxxxxx';

            // MENSAGEM 1: Para o Cliente (Agradecimento + Cópia do Checklist)
            $msgCliente = "
            <html>
            <body style='font-family: Arial, sans-serif; color: #333; line-height: 1.6;'>
                <div style='max-width: 600px; margin: 0 auto; padding: 25px; border: 1px solid #eee;'>
                    <h2 style='font-weight: 300; color: #111; margin-bottom: 10px;'>Olá, {$nome}!</h2>
                    <p>Obrigado por utilizar o nosso <strong>Checklist de Obra & Reforma</strong> no site da Autá AD.</p>
                    <p>Recebemos as suas preferências de projeto e a nossa equipa entrará em contacto muito em breve para dar seguimento ao seu atendimento.</p>
                    
                    <div style='background: #f9f9f9; padding: 15px; margin: 20px 0; border-left: 3px solid #111;'>
                        <p style='margin: 0 0 8px 0;'><strong>Intervenção:</strong> {$tipo_servico}</p>
                        <p style='margin: 0;'><strong>Itens Selecionados:</strong> {$itens}</p>
                    </div>

                    <p style='font-size: 0.85rem; color: #666;'>Atenciosamente,<br><strong>Autá Arquitetura + Design</strong></p>
                </div>
            </body>
            </html>";

            // MENSAGEM 2: Para o Escritório (Notificação de Lead Novo)
            $msgAuta = "
            <html>
            <body style='font-family: Arial, sans-serif; color: #333; line-height: 1.6;'>
                <div style='max-width: 600px; margin: 0 auto; padding: 25px; border: 1px solid #eee;'>
                    <h3 style='font-weight: 400; color: #111;'>Novo Checklist de Obra Recebido</h3>
                    <p><strong>Nome:</strong> {$nome}</p>
                    <p><strong>E-mail:</strong> {$email}</p>
                    <p><strong>WhatsApp:</strong> " . ($whatsapp ?: 'Não informado') . "</p>
                    <p><strong>Tipo de Projeto:</strong> {$tipo_servico}</p>
                    <p><strong>Itens Planeados:</strong> {$itens}</p>
                </div>
            </body>
            </html>";

            // Envio para o Cliente
            $this->sendSocketEmail($smtpHost, $smtpPort, $smtpUser, $smtpPass, $email, "Autá AD — Seu Checklist de Obra", $msgCliente);

            // Envio para a Autá AD
            $this->sendSocketEmail($smtpHost, $smtpPort, $smtpUser, $smtpPass, "contato@autaarquitetura.com.br", "NOVO LEAD: Checklist Obra - {$nome}", $msgAuta);

            $_SESSION['blog_notice'] = "Obrigado! Seu checklist foi enviado para o seu e-mail. Entraremos em contato em breve.";
        } else {
            $_SESSION['blog_notice'] = "Por favor, preencha nome e e-mail válidos.";
        }

        header("Location: " . URL_BASE . "/blog");
        exit;
    }

    // Função Auxiliar de Envio Socket
    private function sendSocketEmail($host, $port, $user, $pass, $to, $subject, $html) {
        $socket = @fsockopen("ssl://" . $host, $port, $errno, $errstr, 15);
        if ($socket) {
            fgets($socket, 512);
            fputs($socket, "EHLO " . $_SERVER['HTTP_HOST'] . "\r\n"); fgets($socket, 512);
            fputs($socket, "AUTH LOGIN\r\n"); fgets($socket, 512);
            fputs($socket, base64_encode($user) . "\r\n"); fgets($socket, 512);
            fputs($socket, base64_encode($pass) . "\r\n"); fgets($socket, 512);

            fputs($socket, "MAIL FROM: <{$user}>\r\n"); fgets($socket, 512);
            fputs($socket, "RCPT TO: <{$to}>\r\n"); fgets($socket, 512);
            fputs($socket, "DATA\r\n"); fgets($socket, 512);

            $headers  = "MIME-Version: 1.0\r\n";
            $headers .= "Content-Type: text/html; charset=UTF-8\r\n";
            $headers .= "From: Autá AD <{$user}>\r\n";
            $headers .= "To: <{$to}>\r\n";
            $headers .= "Subject: {$subject}\r\n";

            fputs($socket, $headers . "\r\n" . $html . "\r\n.\r\n");
            fgets($socket, 512);
            fputs($socket, "QUIT\r\n");
            fclose($socket);
        }
    }
}