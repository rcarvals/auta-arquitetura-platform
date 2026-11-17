<?php
// src/Models/ProjectModel.php

class ProjectModel {
    private $pdo;

    public function __construct() {
        global $pdo;
        $this->pdo = $pdo;
    }

    // Retorna projetos ÚNICOS com GROUP BY
    public function getAllProjects() {
        $stmt = $this->pdo->query("
            SELECT p.id, p.title, p.slug, p.location, p.year, p.cover_image, c.name as category_name 
            FROM projects p 
            LEFT JOIN categories c ON p.category_id = c.id 
            GROUP BY p.id
            ORDER BY p.id DESC
        ");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getAllCategories() {
        $stmt = $this->pdo->query("SELECT * FROM categories ORDER BY name ASC");
        return $stmt->fetchAll();
    }

    public function getProjectBySlug($slug) {
        $stmt = $this->pdo->prepare("
            SELECT p.*, c.name as category_name 
            FROM projects p 
            LEFT JOIN categories c ON p.category_id = c.id 
            WHERE p.slug = :slug
        ");
        $stmt->execute(['slug' => $slug]);
        return $stmt->fetch();
    }



// Cria o projeto garantindo slug único no banco
    public function createProject($data) {
        // Garante que o slug enviado seja único antes do INSERT
        $uniqueSlug = $this->generateUniqueSlug($data['title']);

        $stmt = $this->pdo->prepare("
            INSERT INTO projects (category_id, title, slug, location, year, description, cover_image) 
            VALUES (:category_id, :title, :slug, :location, :year, :description, :cover_image)
        ");

        $stmt->execute([
            'category_id' => $data['category_id'],
            'title'       => $data['title'],
            'slug'        => $uniqueSlug,
            'location'    => $data['location'],
            'year'        => $data['year'],
            'description' => $data['description'],
            'cover_image' => $data['cover_image']
        ]);

        return $this->pdo->lastInsertId();
    }
    
    // Detecta automaticamente o nome da coluna de imagem na tabela project_images
    private function getGalleryImageColumn() {
        $stmt = $this->pdo->query("DESCRIBE project_images");
        $columns = $stmt->fetchAll(PDO::FETCH_COLUMN);

        // Retorna a coluna que armazena o caminho da foto
        foreach ($columns as $column) {
            if ($column !== 'id' && $column !== 'project_id' && $column !== 'created_at') {
                return $column;
            }
        }
        return 'image'; // Fallback
    }

    // Busca todas as fotos da galeria com suas respectivas legendas
    public function getProjectImages($projectId) {
        $stmt = $this->pdo->prepare("
            SELECT id, project_id, image_url AS image_path, caption 
            FROM project_images 
            WHERE project_id = :project_id 
            ORDER BY id ASC
        ");
        $stmt->execute(['project_id' => $projectId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Insere a imagem da galeria juntamente com a sua legenda
    public function addProjectImage($projectId, $imageName, $caption = null) {
        $stmt = $this->pdo->prepare("
            INSERT INTO project_images (project_id, image_url, caption) 
            VALUES (:project_id, :image_url, :caption)
        ");
        return $stmt->execute([
            'project_id' => $projectId,
            'image_url'  => $imageName,
            'caption'    => $caption
        ]);
    }

    public function deleteProject($id) {
        // Remove imagens da galeria primeiro
        $stmt = $this->pdo->prepare("DELETE FROM project_images WHERE project_id = :id");
        $stmt->execute(['id' => $id]);

        // Remove o projeto
        $stmt = $this->pdo->prepare("DELETE FROM projects WHERE id = :id");
        return $stmt->execute(['id' => $id]);
    }
    
    // Gera um slug único no MySQL testando duplicidades
    public function generateUniqueSlug($title) {
        $baseSlug = strtolower(trim(preg_replace('/[^A-Za-z0-9-]+/', '-', $title)));
        if (empty($baseSlug)) {
            $baseSlug = 'projeto';
        }

        $slug = $baseSlug;
        $counter = 1;

        while (true) {
            $stmt = $this->pdo->prepare("SELECT COUNT(*) FROM projects WHERE slug = :slug");
            $stmt->execute(['slug' => $slug]);
            $exists = $stmt->fetchColumn();

            if (!$exists) {
                return $slug;
            }

            $slug = $baseSlug . '-' . $counter;
            $counter++;
        }
    }

}