<?php
// src/Controllers/ProjectController.php

require_once BASE_DIR . '/src/Models/ProjectModel.php';

class ProjectController {

    private $projectModel;

    public function __construct() {
        $this->projectModel = new ProjectModel();
    }

    // Listagem geral de todos os projetos (página /projetos ou /work)
    public function index() {
        $title = "Projetos — Autá AD";
        $projects = $this->projectModel->getAllProjects();

        require_once BASE_DIR . '/src/Views/partials/header.php';
        require_once BASE_DIR . '/src/Views/pages/projects.php';
        require_once BASE_DIR . '/src/Views/partials/footer.php';
    }

    // Exibe o detalhe do projeto baseado na URL amigável /projeto/{slug}
    public function show($slug) {
        if (!$slug) {
            header("Location: " . URL_BASE . "/");
            exit;
        }

        // 1. Busca os dados do projeto exato no MySQL pelo slug
        $project = $this->projectModel->getProjectBySlug($slug);

        if (!$project) {
            http_response_code(404);
            echo "<div style='padding:50px; text-align:center; font-family:sans-serif;'>
                    <h1>Projeto não encontrado</h1>
                    <a href='" . URL_BASE . "/'>Voltar para a home</a>
                  </div>";
            return;
        }

        $title = "Autá AD — " . htmlspecialchars($project['title']);
        
        // 2. Busca todas as imagens secundárias com suas legendas ($galleryImages)
        $galleryImages = $this->projectModel->getProjectImages($project['id']);

        // 3. Renderiza a view de detalhes passando as variáveis $project e $galleryImages
        require_once BASE_DIR . '/src/Views/partials/header.php';
        require_once BASE_DIR . '/src/Views/pages/project-detail.php';
        require_once BASE_DIR . '/src/Views/partials/footer.php';
    }
}