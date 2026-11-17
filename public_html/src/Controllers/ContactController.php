<?php
// src/Controllers/ContactController.php

class ContactController {

    public function index() {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        $title = "Contato — Autá AD";
        require_once BASE_DIR . '/src/Views/partials/header.php';
        require_once BASE_DIR . '/src/Views/pages/contact.php';
        require_once BASE_DIR . '/src/Views/partials/footer.php';
    }

    public function send() {
        $nome = $_POST['nome'] ?? '';

// Rejeita se o nome tiver menos de 3 caracteres ou for uma palavra contínua estranha sem espaços nem estrutura real
        if (strlen($nome) < 3 || preg_match('/^[bcdfghjklmnpqrstvwxyz]{4,}$/i', $nome)) {
            die("Preenchimento inválido.");
        }
                if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        if (!empty($_POST['website_url'])) {
        // Interrompe o envio do e-mail silenciosamente
        exit('Envio ignorado.');
        }

        $name    = filter_input(INPUT_POST, 'name', FILTER_SANITIZE_SPECIAL_CHARS);
        $email   = filter_input(INPUT_POST, 'email', FILTER_VALIDATE_EMAIL);
        $message = filter_input(INPUT_POST, 'message', FILTER_SANITIZE_SPECIAL_CHARS);

        if (!empty($name) && !empty($email) && !empty($message)) {

            // Configurações exatas do Titan Email (Hostgator)
            $smtpHost = 'ssl://smtp.titan.email';
            $smtpPort = 465;
            $smtpUser = 'xxxxxx@xxxxxxxxxxxx.com.br';
            $smtpPass = 'xxxxxx@xxxxxxxxxxxx.com.br'; // Insira a senha atualizada da conta Titan

            $to      = 'contato@autaarquitetura.com.br';
            $subject = "Novo Contato via Site — " . $name;
            
            $body  = "Você recebeu uma nova mensagem enviada pelo site:\n\n";
            $body .= "Nome: " . $name . "\n";
            $body .= "E-mail: " . $email . "\n\n";
            $body .= "Mensagem:\n" . $message . "\n";

            // Envio direto via Socket SSL com autenticação AUTH LOGIN
            $sent = $this->sendTitanSmtp($smtpHost, $smtpPort, $smtpUser, $smtpPass, $to, $email, $name, $subject, $body);

            if ($sent) {
                $_SESSION['contact_success'] = "Sua mensagem foi enviada com sucesso! Entraremos em contato em breve.";
            } else {
                // Fallback via mail() nativo caso ocorra bloqueio de socket na hospedagem
                $headers  = "From: Autá AD <contato@autaarquitetura.com.br>\r\n";
                $headers .= "Reply-To: " . $email . "\r\n";
                $headers .= "Content-Type: text/plain; charset=UTF-8\r\n";

                if (@mail($to, $subject, $body, $headers, "-fcontato@autaarquitetura.com.br")) {
                    $_SESSION['contact_success'] = "Sua mensagem foi enviada com sucesso! Entraremos em contato em breve.";
                } else {
                    $_SESSION['contact_error'] = "Não foi possível enviar a mensagem no momento. Tente novamente mais tarde.";
                }
            }

        } else {
            $_SESSION['contact_error'] = "Por favor, preencha todos os campos do formulário.";
        }

        header("Location: " . URL_BASE . "/contato");
        exit;
    }

    // Função de envio Socket SSL nativo para o Titan Email
    private function sendTitanSmtp($host, $port, $user, $pass, $to, $replyTo, $replyName, $subject, $body) {
        $socket = @fsockopen($host, $port, $errno, $errstr, 10);
        if (!$socket) {
            return false;
        }

        $getResponse = function($socket) {
            $response = "";
            while ($str = fgets($socket, 512)) {
                $response .= $str;
                if (substr($str, 3, 1) == " ") { break; }
            }
            return $response;
        };

        $getResponse($socket);

        fputs($socket, "EHLO " . $_SERVER['SERVER_NAME'] . "\r\n");
        $getResponse($socket);

        fputs($socket, "AUTH LOGIN\r\n");
        $getResponse($socket);

        fputs($socket, base64_encode($user) . "\r\n");
        $getResponse($socket);

        fputs($socket, base64_encode($pass) . "\r\n");
        $authRes = $getResponse($socket);

        if (strpos($authRes, '235') === false) {
            fclose($socket);
            return false; // Erro de autenticação de senha
        }

        fputs($socket, "MAIL FROM: <" . $user . ">\r\n");
        $getResponse($socket);

        fputs($socket, "RCPT TO: <" . $to . ">\r\n");
        $getResponse($socket);

        fputs($socket, "DATA\r\n");
        $getResponse($socket);

        $headers  = "From: Autá AD <" . $user . ">\r\n";
        $headers .= "Reply-To: " . $replyName . " <" . $replyTo . ">\r\n";
        $headers .= "To: <" . $to . ">\r\n";
        $headers .= "Subject: =?UTF-8?B?" . base64_encode($subject) . "?=\r\n";
        $headers .= "MIME-Version: 1.0\r\n";
        $headers .= "Content-Type: text/plain; charset=UTF-8\r\n\r\n";

        fputs($socket, $headers . $body . "\r\n.\r\n");
        $getResponse($socket);

        fputs($socket, "QUIT\r\n");
        fclose($socket);

        return true;
    }
}