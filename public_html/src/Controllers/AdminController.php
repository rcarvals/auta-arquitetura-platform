<?php
// src/Controllers/AdminController.php

require_once BASE_DIR . '/src/Models/ProjectModel.php';
require_once BASE_DIR . '/src/Models/PostModel.php';

class AdminController {
    private $projectModel;

    public function __construct() {
        $this->projectModel = new ProjectModel();
    }

    // Método auxiliar para redirecionamento seguro
    private function redirect($route) {
        $url = URL_BASE . $route;
        if (!headers_sent()) {
            header("Location: " . $url);
        } else {
            echo "<script>window.location.href='" . $url . "';</script>";
        }
        exit;
    }

    // Método auxiliar para verificar autenticação de sessão
    private function checkAuth() {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        if (empty($_SESSION['admin_logged_in'])) {
            $this->redirect("/admin/login");
        }
    }

    // Tela de login
    public function login() {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        if (!empty($_SESSION['admin_logged_in'])) {
            $this->redirect("/admin");
        }

        $title = "Login Administrativo — Autá AD";
        require_once BASE_DIR . '/src/Views/pages/admin-login.php';
    }

    // Processa a autenticação
    public function authenticate() {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        $user = filter_input(INPUT_POST, 'username', FILTER_SANITIZE_SPECIAL_CHARS);
        $pass = $_POST['password'] ?? '';

        if ($user === ADMIN_USER && $pass === ADMIN_PASS) {
            $_SESSION['admin_logged_in'] = true;
            $this->redirect("/admin");
        } else {
            $_SESSION['login_error'] = "Usuário ou senha incorretos.";
            $this->redirect("/admin/login");
        }
    }

    // Encerra a sessão
    public function logout() {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        unset($_SESSION['admin_logged_in']);
        session_destroy();
        $this->redirect("/admin/login");
    }

    // Dashboard
    public function index() {
        $this->checkAuth();
        $title = "Painel Autá AD - Gerenciar Projetos";
        $projects = $this->projectModel->getAllProjects();

        require_once BASE_DIR . '/src/Views/pages/admin-dashboard.php';
    }

    // Formulário de novo projeto
    public function create() {
        $this->checkAuth();
        $title = "Painel Autá AD - Novo Projeto";
        $categories = $this->projectModel->getAllCategories();

        require_once BASE_DIR . '/src/Views/pages/admin-create.php';
    }

    // Salvar novo projeto com capa, galeria e legendas tratadas
    public function store() {
        $this->checkAuth();

        $title       = filter_input(INPUT_POST, 'title', FILTER_SANITIZE_SPECIAL_CHARS);
        $categoryId  = filter_input(INPUT_POST, 'category_id', FILTER_VALIDATE_INT);
        $location    = filter_input(INPUT_POST, 'location', FILTER_SANITIZE_SPECIAL_CHARS);
        $year        = filter_input(INPUT_POST, 'year', FILTER_VALIDATE_INT);
        $description = filter_input(INPUT_POST, 'description', FILTER_SANITIZE_SPECIAL_CHARS);

        $coverInput = isset($_FILES['cover_image']) ? 'cover_image' : 'cover_image_file';

        if (!isset($_FILES[$coverInput]) || $_FILES[$coverInput]['error'] !== UPLOAD_ERR_OK) {
            die("<div style='padding:20px; font-family:sans-serif;'>
                    <h3>Erro no Upload da Imagem de Capa</h3>
                    <a href='" . URL_BASE . "/admin/novo'>Voltar ao Painel</a>
                 </div>");
        }

        $uploadFileDir = BASE_DIR . DIRECTORY_SEPARATOR . 'assets' . DIRECTORY_SEPARATOR . 'uploads' . DIRECTORY_SEPARATOR;
        if (!is_dir($uploadFileDir)) {
            mkdir($uploadFileDir, 0755, true);
        }

        // 1. Processa a foto de capa
        $coverExt     = strtolower(pathinfo($_FILES[$coverInput]['name'], PATHINFO_EXTENSION));
        $newCoverName = uniqid('proj_capa_') . '.' . $coverExt;
        
        if (!move_uploaded_file($_FILES[$coverInput]['tmp_name'], $uploadFileDir . $newCoverName)) {
            die("Erro crítico ao salvar a imagem de capa no servidor.");
        }

        // 2. Grava o projeto no MySQL
        if ($title && $categoryId && $newCoverName) {
            $projectId = $this->projectModel->createProject([
                'category_id' => $categoryId,
                'title'       => $title,
                'location'    => $location,
                'year'        => $year,
                'description' => $description,
                'cover_image' => $newCoverName
            ]);

            // 3. Processa e grava as fotos da galeria com sanitização de legenda
            if ($projectId && !empty($_FILES['gallery_images']['name'][0])) {
                $files    = $_FILES['gallery_images'];
                $captions = $_POST['gallery_captions'] ?? [];
                $count    = count($files['name']);

                for ($i = 0; $i < $count; $i++) {
                    if ($files['error'][$i] === UPLOAD_ERR_OK) {
                        $galExt     = strtolower(pathinfo($files['name'][$i], PATHINFO_EXTENSION));
                        $galNewName = uniqid('gal_') . '.' . $galExt;
                        
                        // Validação e higienização da legenda
                        $captionRaw = !empty($captions[$i]) ? trim($captions[$i]) : null;
                        if ($captionRaw && strpos($captionRaw, '<?php') === false) {
                            $caption = filter_var($captionRaw, FILTER_SANITIZE_SPECIAL_CHARS);
                        } else {
                            $caption = null;
                        }

                        if (move_uploaded_file($files['tmp_name'][$i], $uploadFileDir . $galNewName)) {
                            $this->projectModel->addProjectImage($projectId, $galNewName, $caption);
                        }
                    }
                }
            }
        }

        $this->redirect("/admin");
    }

    // Excluir projeto
    public function delete($id) {
        $this->checkAuth();
        if ($id) {
            $this->projectModel->deleteProject($id);
        }
        $this->redirect("/admin");
    }
    
    // Formulário de novo artigo do Blog
    public function createPost() {
        $this->checkAuth();

        $title = "Novo Artigo — Admin Autá AD";
        require_once BASE_DIR . '/src/Views/partials/header.php';
        require_once BASE_DIR . '/src/Views/pages/admin-post-create.php';
        require_once BASE_DIR . '/src/Views/partials/footer.php';
    }

    // Processa o salvamento do post do Blog no banco
    public function storePost() {
        $this->checkAuth();
        
        $tags        = filter_input(INPUT_POST, 'tags', FILTER_SANITIZE_SPECIAL_CHARS);
        $title       = filter_input(INPUT_POST, 'title', FILTER_SANITIZE_SPECIAL_CHARS);
        $category    = filter_input(INPUT_POST, 'category', FILTER_SANITIZE_SPECIAL_CHARS) ?: 'Arquitetura';
        $summary     = filter_input(INPUT_POST, 'summary', FILTER_SANITIZE_SPECIAL_CHARS);
        $content     = $_POST['content'] ?? '';
        $author_name = filter_input(INPUT_POST, 'author_name', FILTER_SANITIZE_SPECIAL_CHARS) ?: 'Autá AD';

        $uploadFileDir = BASE_DIR . DIRECTORY_SEPARATOR . 'assets' . DIRECTORY_SEPARATOR . 'uploads' . DIRECTORY_SEPARATOR;
        if (!is_dir($uploadFileDir)) {
            mkdir($uploadFileDir, 0755, true);
        }

        $cover_image = '';
        if (!empty($_FILES['cover_image']['name']) && $_FILES['cover_image']['error'] === UPLOAD_ERR_OK) {
            $ext = strtolower(pathinfo($_FILES['cover_image']['name'], PATHINFO_EXTENSION));
            $cover_image = 'blog_' . time() . '.' . $ext;
            move_uploaded_file($_FILES['cover_image']['tmp_name'], $uploadFileDir . $cover_image);
        }

        if ($title && $content && $cover_image) {
            $postModel = new PostModel();
            
            // Grava o post e recupera o ID
            $postModel->createPost([
                'title'       => $title,
                'category'    => $category,
                'tags'        => $tags,
                'summary'     => $summary,
                'content'     => $content,
                'cover_image' => $cover_image,
                'author_name' => $author_name
            ]);

            global $pdo;
            $postId = $pdo->lastInsertId();

            // Processa imagens adicionais com as respetivas legendas
            if ($postId && !empty($_FILES['gallery_images']['name'][0])) {
                $files    = $_FILES['gallery_images'];
                $captions = $_POST['gallery_captions'] ?? [];
                $count    = count($files['name']);

                for ($i = 0; $i < $count; $i++) {
                    if ($files['error'][$i] === UPLOAD_ERR_OK) {
                        $ext     = strtolower(pathinfo($files['name'][$i], PATHINFO_EXTENSION));
                        $newName = 'blog_gal_' . uniqid() . '.' . $ext;
                        
                        // Validação e higienização da legenda
                        $captionRaw = !empty($captions[$i]) ? trim($captions[$i]) : null;
                        if ($captionRaw && strpos($captionRaw, '<?php') === false) {
                            $caption = filter_var($captionRaw, FILTER_SANITIZE_SPECIAL_CHARS);
                        } else {
                            $caption = null;
                        }

                        if (move_uploaded_file($files['tmp_name'][$i], $uploadFileDir . $newName)) {
                            $postModel->addPostImage($postId, $newName, $caption);
                        }
                    }
                }
            }

            $_SESSION['admin_success'] = "Artigo publicado com sucesso!";
            $this->redirect("/admin");
        } else {
            $_SESSION['admin_error'] = "Preencha todos os campos obrigatórios e envie a imagem de capa.";
            $this->redirect("/admin/blog/novo");
        }
    }
    
    // Dispara a publicação por e-mail via Socket SMTP da HostGator/Titan
    public function dispararPost($id) {
        $this->checkAuth();

        global $pdo;

        // 1. Busca os dados do post
        $stmtPost = $pdo->prepare("SELECT * FROM posts WHERE id = :id LIMIT 1");
        $stmtPost->execute(['id' => $id]);
        $post = $stmtPost->fetch(PDO::FETCH_ASSOC);

        if (!$post) {
            $_SESSION['admin_error'] = "Artigo não encontrado.";
            $this->redirect("/admin");
        }

        // 2. Busca os cadastros do banco
        $stmtSub = $pdo->query("SELECT nome, email FROM blog_cadastros");
        $inscritos = $stmtSub->fetchAll(PDO::FETCH_ASSOC);

        if (empty($inscritos)) {
            $_SESSION['admin_error'] = "Nenhum e-mail cadastrado na tabela blog_cadastros.";
            $this->redirect("/admin");
        }

        $linkPost = URL_BASE . '/blog/' . $post['slug'];
        $assunto  = "Novo Artigo: " . $post['title'];
        $enviados = 0;

        // Configurações do SMTP Titan HostGator
        $smtpHost = 'smtp.titan.email';
        $smtpPort = 465;
        $smtpUser = 'xxxxxx@xxxxxxxxxxxx.com.br';
        $smtpPass = 'xxxxxxxxxx';

        foreach ($inscritos as $leitor) {
            $emailDestino = str_replace('xxxxxx@xxxxxxxxxxxx.com.br', 'xxxxxx@xxxxxxxxxxxx.com.br', trim($leitor['email']));

            $mensagemHtml = "
            <html>
            <body style='font-family: Arial, sans-serif; color: #333; line-height: 1.6;'>
                <div style='max-width: 600px; margin: 0 auto; padding: 20px; border: 1px solid #eee;'>
                    <h2 style='font-weight: 300; color: #111;'>Olá, " . htmlspecialchars($leitor['nome']) . "!</h2>
                    <p>Publicámos um novo artigo no blog da <strong>Autá AD</strong>:</p>
                    
                    <h3 style='margin-top: 20px;'>
                        <a href='{$linkPost}' style='color: #4a6b82; text-decoration: none;'>" . htmlspecialchars($post['title']) . "</a>
                    </h3>
                    <p style='color: #666;'>" . htmlspecialchars($post['summary']) . "</p>
                    
                    <p style='margin-top: 30px;'>
                        <a href='{$linkPost}' style='background: #111; color: #fff; padding: 12px 24px; text-decoration: none; font-size: 0.85rem; display: inline-block;'>LER ARTIGO COMPLETO &rarr;</a>
                    </p>
                    <hr style='border: none; border-top: 1px solid #eee; margin-top: 40px;'>
                    <p style='font-size: 0.75rem; color: #999;'>Recebeu este e-mail porque se inscreveu no site da Autá AD.</p>
                </div>
            </body>
            </html>";

            // Envio direto por Socket SSL
            $socket = @fsockopen("ssl://" . $smtpHost, $smtpPort, $errno, $errstr, 15);
            if ($socket) {
                fgets($socket, 512);
                fputs($socket, "EHLO " . $_SERVER['HTTP_HOST'] . "\r\n"); fgets($socket, 512);
                fputs($socket, "AUTH LOGIN\r\n"); fgets($socket, 512);
                fputs($socket, base64_encode($smtpUser) . "\r\n"); fgets($socket, 512);
                fputs($socket, base64_encode($smtpPass) . "\r\n"); fgets($socket, 512);

                fputs($socket, "MAIL FROM: <{$smtpUser}>\r\n"); fgets($socket, 512);
                fputs($socket, "RCPT TO: <{$emailDestino}>\r\n"); fgets($socket, 512);
                fputs($socket, "DATA\r\n"); fgets($socket, 512);

                $headers  = "MIME-Version: 1.0\r\n";
                $headers .= "Content-Type: text/html; charset=UTF-8\r\n";
                $headers .= "From: Autá AD <{$smtpUser}>\r\n";
                $headers .= "To: {$leitor['nome']} <{$emailDestino}>\r\n";
                $headers .= "Subject: {$assunto}\r\n";

                fputs($socket, $headers . "\r\n" . $mensagemHtml . "\r\n.\r\n");
                fgets($socket, 512);
                fputs($socket, "QUIT\r\n");
                fclose($socket);

                $enviados++;
            }
        }

        if ($enviados > 0) {
            $_SESSION['admin_success'] = "Artigo disparado com sucesso para {$enviados} e-mail(s)!";
        } else {
            $_SESSION['admin_error'] = "Erro ao conectar com o servidor SMTP Titan.";
        }

        $this->redirect("/admin");
    }
    
    // Tela de configuração do Hero da Homepage
    public function manageHero() {
        $this->checkAuth();
        global $pdo;

        $stmtConfig = $pdo->query("SELECT * FROM home_hero WHERE id = 1 LIMIT 1");
        $heroConfig = $stmtConfig->fetch(PDO::FETCH_ASSOC);

        $stmtSlides = $pdo->query("SELECT * FROM home_hero_slides ORDER BY order_index ASC, id ASC");
        $slides = $stmtSlides->fetchAll(PDO::FETCH_ASSOC);

        $title = "Capa da Homepage — Admin Autá AD";
        require_once BASE_DIR . '/src/Views/pages/admin-hero.php';
    }

// Salva as configurações da Hero Section e da Secção de Conceito
    public function storeHero() {
        $this->checkAuth();
        global $pdo;

        $type         = filter_input(INPUT_POST, 'hero_type', FILTER_SANITIZE_SPECIAL_CHARS) ?: 'slider';
        $conceptTitle = filter_input(INPUT_POST, 'concept_title', FILTER_SANITIZE_SPECIAL_CHARS);
        $conceptText  = filter_input(INPUT_POST, 'concept_text', FILTER_SANITIZE_SPECIAL_CHARS);

        $uploadDir = BASE_DIR . '/assets/uploads/';
        if (!is_dir($uploadDir)) { mkdir($uploadDir, 0755, true); }

        // 1. Processa upload do Vídeo se enviado
        if ($type === 'video' && !empty($_FILES['video_file']['name']) && $_FILES['video_file']['error'] === UPLOAD_ERR_OK) {
            $videoDir = BASE_DIR . '/assets/videos/';
            if (!is_dir($videoDir)) { mkdir($videoDir, 0755, true); }

            $ext = strtolower(pathinfo($_FILES['video_file']['name'], PATHINFO_EXTENSION));
            if ($ext === 'mp4') {
                $videoName = 'hero_' . time() . '.mp4';
                if (move_uploaded_file($_FILES['video_file']['tmp_name'], $videoDir . $videoName)) {
                    $stmt = $pdo->prepare("UPDATE home_hero SET video_file = :v WHERE id = 1");
                    $stmt->execute(['v' => $videoName]);
                }
            }
        }

        // 2. Processa upload das Imagens do Slider se enviadas
        if ($type === 'slider' && !empty($_FILES['slide_images']['name'][0])) {
            $files = $_FILES['slide_images'];
            $count = count($files['name']);

            for ($i = 0; $i < $count; $i++) {
                if ($files['error'][$i] === UPLOAD_ERR_OK) {
                    $ext = strtolower(pathinfo($files['name'][$i], PATHINFO_EXTENSION));
                    $imgName = 'hero_slide_' . uniqid() . '.' . $ext;
                    if (move_uploaded_file($files['tmp_name'][$i], $uploadDir . $imgName)) {
                        $stmt = $pdo->prepare("INSERT INTO home_hero_slides (image_path) VALUES (:img)");
                        $stmt->execute(['img' => $imgName]);
                    }
                }
            }
        }

        // 3. Processa upload da Imagem Estática do Conceito
        if (!empty($_FILES['concept_image']['name']) && $_FILES['concept_image']['error'] === UPLOAD_ERR_OK) {
            $ext = strtolower(pathinfo($_FILES['concept_image']['name'], PATHINFO_EXTENSION));
            $conceptImgName = 'conceito_' . time() . '.' . $ext;
            
            if (move_uploaded_file($_FILES['concept_image']['tmp_name'], $uploadDir . $conceptImgName)) {
                $stmtConceptImg = $pdo->prepare("UPDATE home_hero SET concept_image = :cimg WHERE id = 1");
                $stmtConceptImg->execute(['cimg' => $conceptImgName]);
            }
        }

        // 4. Atualiza o tipo, o título e o texto de conceito na tabela home_hero
        $stmtUpdate = $pdo->prepare("UPDATE home_hero SET type = :type, concept_title = :ctitle, concept_text = :ctext WHERE id = 1");
        $stmtUpdate->execute([
            'type'   => $type,
            'ctitle' => $conceptTitle,
            'ctext'  => $conceptText
        ]);

        $_SESSION['hero_success'] = "Conteúdo da Homepage atualizado com sucesso!";
        $this->redirect("/admin/hero");
    }

    // Excluir um slide individual
    public function deleteSlide($id) {
        $this->checkAuth();
        global $pdo;

        if ($id) {
            $stmt = $pdo->prepare("DELETE FROM home_hero_slides WHERE id = :id");
            $stmt->execute(['id' => $id]);
        }
        $this->redirect("/admin/hero");
    }
    
    // Tela de gerenciamento da equipe
    public function manageTeam() {
        $this->checkAuth();
        global $pdo;

        $stmt = $pdo->query("SELECT * FROM team_members ORDER BY order_index ASC, id ASC");
        $teamMembers = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $title = "Gerenciar Equipe — Admin Autá AD";
        require_once BASE_DIR . '/src/Views/pages/admin-team.php';
    }

    // Salva novo colaborador
    public function storeTeamMember() {
        $this->checkAuth();
        global $pdo;

        $name = filter_input(INPUT_POST, 'name', FILTER_SANITIZE_SPECIAL_CHARS);
        $role = filter_input(INPUT_POST, 'role', FILTER_SANITIZE_SPECIAL_CHARS);
        $bio  = filter_input(INPUT_POST, 'bio', FILTER_SANITIZE_SPECIAL_CHARS);

        if ($name && $role && !empty($_FILES['photo']['name']) && $_FILES['photo']['error'] === UPLOAD_ERR_OK) {
            $uploadDir = BASE_DIR . '/assets/uploads/';
            if (!is_dir($uploadDir)) { mkdir($uploadDir, 0755, true); }

            $ext = strtolower(pathinfo($_FILES['photo']['name'], PATHINFO_EXTENSION));
            $photoName = 'equipe_' . time() . '_' . uniqid() . '.' . $ext;

            if (move_uploaded_file($_FILES['photo']['tmp_name'], $uploadDir . $photoName)) {
                $stmt = $pdo->prepare("INSERT INTO team_members (name, role, bio, photo) VALUES (:name, :role, :bio, :photo)");
                $stmt->execute([
                    'name'  => $name,
                    'role'  => $role,
                    'bio'   => $bio,
                    'photo' => $photoName
                ]);

                $_SESSION['team_success'] = "Colaborador cadastrado com sucesso!";
            }
        }

        $this->redirect("/admin/equipe");
    }

    // Exclui um colaborador
    public function deleteTeamMember($id) {
        $this->checkAuth();
        global $pdo;

        if ($id) {
            $stmt = $pdo->prepare("DELETE FROM team_members WHERE id = :id");
            $stmt->execute(['id' => $id]);
            $_SESSION['team_success'] = "Colaborador removido!";
        }

        $this->redirect("/admin/equipe");
    }
}