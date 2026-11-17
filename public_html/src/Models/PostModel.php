<?php
// src/Models/PostModel.php

class PostModel {
    private $pdo;

    public function __construct() {
        global $pdo;
        $this->pdo = $pdo;
    }

// Busca todos os posts (garantindo que cada ID de post aparece apenas uma vez)
    public function getAllPosts($search = null, $category = null, $tag = null) {
        $sql = "SELECT p.* FROM posts p WHERE 1=1";
        $params = [];

        if (!empty($search)) {
            $sql .= " AND (p.title LIKE :search OR p.content LIKE :search OR p.summary LIKE :search)";
            $params['search'] = '%' . $search . '%';
        }

        if (!empty($category)) {
            $sql .= " AND p.category = :category";
            $params['category'] = $category;
        }

        if (!empty($tag)) {
            $sql .= " AND p.tags LIKE :tag";
            $params['tag'] = '%' . $tag . '%';
        }

        $sql .= " GROUP BY p.id ORDER BY p.created_at DESC";

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getCategories() {
        $stmt = $this->pdo->query("SELECT DISTINCT category FROM posts WHERE category IS NOT NULL AND category != ''");
        return $stmt->fetchAll(PDO::FETCH_COLUMN);
    }

    public function getAllTags() {
        $stmt = $this->pdo->query("SELECT tags FROM posts WHERE tags IS NOT NULL AND tags != ''");
        $rawTags = $stmt->fetchAll(PDO::FETCH_COLUMN);
        
        $allTags = [];
        foreach ($rawTags as $row) {
            $items = explode(',', $row);
            foreach ($items as $item) {
                $cleaned = trim($item);
                if (!empty($cleaned) && !in_array($cleaned, $allTags)) {
                    $allTags[] = $cleaned;
                }
            }
        }
        return $allTags;
    }

    public function getPostBySlug($slug) {
        $stmt = $this->pdo->prepare("SELECT * FROM posts WHERE slug = :slug LIMIT 1");
        $stmt->execute(['slug' => $slug]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // GRAVAÇÃO COMPLETA DE ARTIGOS
    public function createPost($data) {
        $slug = $this->generateSlug($data['title']);
        $stmt = $this->pdo->prepare("
            INSERT INTO posts (title, slug, category, tags, cover_image, summary, content, author_name) 
            VALUES (:title, :slug, :category, :tags, :cover_image, :summary, :content, :author_name)
        ");
        return $stmt->execute([
            'title'       => $data['title'],
            'slug'        => $slug,
            'category'    => $data['category'] ?? 'Arquitetura',
            'tags'        => $data['tags'] ?? '',
            'cover_image' => $data['cover_image'],
            'summary'     => $data['summary'] ?? '',
            'content'     => $data['content'],
            'author_name' => $data['author_name'] ?? 'Autá AD'
        ]);
    }

    // Adiciona uma imagem com legenda à galeria do artigo
    public function addPostImage($postId, $imagePath, $caption = null) {
        $stmt = $this->pdo->prepare("INSERT INTO post_images (post_id, image_path, caption) VALUES (:post_id, :image_path, :caption)");
        return $stmt->execute([
            'post_id'    => $postId,
            'image_path' => $imagePath,
            'caption'    => $caption
        ]);
    }

    // Busca as imagens e legendas da galeria
    public function getPostImages($postId) {
        $stmt = $this->pdo->prepare("SELECT image_path, caption FROM post_images WHERE post_id = :post_id ORDER BY id ASC");
        $stmt->execute(['post_id' => $postId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function deletePost($id) {
        $stmt = $this->pdo->prepare("DELETE FROM posts WHERE id = :id");
        return $stmt->execute(['id' => $id]);
    }

    // GERA SLUG ÚNICO AUTOMATICAMENTE EVITANDO ERROS DE DUPLICAÇÃO
    private function generateSlug($text) {
        $text = preg_replace('~[^\pL\d]+~u', '-', $text);
        $text = iconv('utf-8', 'us-ascii//TRANSLIT', $text);
        $text = preg_replace('~[^-\w]+~', '', $text);
        $text = trim($text, '-');
        $text = preg_replace('~-+~', '-', $text);
        $baseSlug = strtolower($text) ?: 'post-' . time();

        $slug = $baseSlug;
        $i = 1;

        while ($this->slugExists($slug)) {
            $slug = $baseSlug . '-' . $i;
            $i++;
        }

        return $slug;
    }

    // MÉTODO AUXILIAR PARA VERIFICAR SE O SLUG JÁ EXISTE NO BANCO
    private function slugExists($slug) {
        $stmt = $this->pdo->prepare("SELECT COUNT(*) FROM posts WHERE slug = :slug");
        $stmt->execute(['slug' => $slug]);
        return $stmt->fetchColumn() > 0;
    }
}