<?php require_once BASE_DIR . '/src/Views/partials/header.php'; ?>

<?php
    // Busca fotos e legendas da galeria do artigo
    $postModel = new PostModel();
    $galleryImages = $postModel->getPostImages($post['id']);
?>

<main class="page-container internal-page-wrapper blog-detail-container" style="max-width: 1200px; margin: 0 auto; padding-top: 140px; padding-bottom: 80px;">
    
    <!-- CABEÇALHO DO ARTIGO (ALINHADO À ESQUERDA JUNTO À MARGEM DA CAPA) -->
    <header class="blog-detail-header" style="margin-bottom: 40px; text-align: left; width: 100%;">
        
        <!-- Título alinhado à esquerda -->
        <h1 style="font-weight: 300; font-size: 2rem; line-height: 1.25; color: #444; margin: 0 0 15px 0; letter-spacing: -0.3px; text-transform: capitalize;">
            <?= htmlspecialchars($post['title']) ?>
        </h1>

        <!-- Categoria • Data • Autor em linha única alinhados à esquerda -->
        <div style="font-size: 0.8rem; color: #777; text-transform: uppercase; letter-spacing: 1.5px; font-weight: 400; display: flex; align-items: center; justify-content: flex-start; gap: 8px;">
            <span><?= htmlspecialchars($post['category'] ?? 'Arquitetura') ?></span>
            <span>&bull;</span>
            <span><?= date('d.m.Y', strtotime($post['created_at'])) ?></span>
            <span>&bull;</span>
            <span>POR <strong><?= htmlspecialchars($post['author_name'] ?? 'AUTÁ AD') ?></strong></span>
        </div>
    </header>

    <!-- IMAGEM PRINCIPAL IMERSIVA (CAPA) -->
    <?php if (!empty($post['cover_image'])): ?>
        <div class="blog-detail-cover" style="width: 100%; margin-bottom: 50px; overflow: hidden;">
            <img src="<?= URL_BASE ?>/assets/uploads/<?= htmlspecialchars($post['cover_image']) ?>" 
                 alt="<?= htmlspecialchars($post['title']) ?>" 
                 style="width: 100%; height: auto; max-height: 750px; display: block; object-fit: cover;">
        </div>
    <?php endif; ?>

    <!-- CONTEÚDO CORRIDO / TEXTO PRINCIPAL (ALINHADO À ESQUERDA) -->
    <article class="blog-detail-body" style="font-size: 1.05rem; line-height: 1.9; color: #222; font-weight: 300; margin-bottom: 70px; width: 100%; text-align: left;">
        <?= nl2br($post['content']) ?>
    </article>

    <!-- GALERIA IMERSIVA ALTERNADA (LARGURAS E POSICIONAMENTOS DINÂMICOS) -->
    <?php if (!empty($galleryImages)): ?>
        <section class="blog-gallery-section" style="margin-top: 80px; margin-bottom: 80px; display: flex; flex-direction: column; gap: 80px;">
            
            <?php 
                $index = 0;
                foreach ($galleryImages as $img): 
                    $pattern = $index % 4;
                    $index++;

                    if ($pattern === 0) {
                        $imgWidth = '60%'; $textWidth = '37%'; $direction = 'row';
                    } elseif ($pattern === 1) {
                        $imgWidth = '60%'; $textWidth = '37%'; $direction = 'row-reverse';
                    } elseif ($pattern === 2) {
                        $imgWidth = '40%'; $textWidth = '57%'; $direction = 'row';
                    } else {
                        $imgWidth = '40%'; $textWidth = '57%'; $direction = 'row-reverse';
                    }
            ?>
                <figure class="gallery-item-editorial" style="margin: 0; display: flex; flex-direction: <?= $direction ?>; align-items: center; justify-content: space-between; gap: 3%; width: 100%;">
                    
                    <!-- Bloco da Imagem -->
                    <div style="width: <?= $imgWidth ?>; overflow: hidden; background: #f9f9f9;">
                        <img src="<?= URL_BASE ?>/assets/uploads/<?= htmlspecialchars($img['image_path']) ?>" 
                             alt="<?= htmlspecialchars($img['caption'] ?? $post['title']) ?>" 
                             style="width: 100%; height: auto; display: block; object-fit: cover;">
                    </div>

                    <!-- Bloco do Texto / Legenda na Lateral -->
                    <?php if (!empty($img['caption'])): ?>
                        <figcaption style="width: <?= $textWidth ?>; font-size: 0.95rem; line-height: 1.7; color: #555; font-weight: 300; letter-spacing: 0.3px;">
                            <span style="display: block; width: 30px; height: 1px; background: #111; margin-bottom: 15px;"></span>
                            <?= htmlspecialchars($img['caption']) ?>
                        </figcaption>
                    <?php else: ?>
                        <div style="width: <?= $textWidth ?>;"></div>
                    <?php endif; ?>

                </figure>
            <?php endforeach; ?>

        </section>
    <?php endif; ?>

    <!-- RODAPÉ DA PÁGINA COM NAVEGAÇÃO MINIMALISTA -->
    <footer style="border-top: 1px solid #eee; padding-top: 50px; margin-top: 80px; text-align: center;">
        <a href="<?= URL_BASE ?>/blog" style="text-decoration: none; color: #111; font-size: 0.8rem; text-transform: uppercase; letter-spacing: 2px; border: 1px solid #111; padding: 12px 28px; display: inline-block; transition: all 0.3s ease;">
            &larr; Voltar para todos os artigos
        </a>
    </footer>

</main>

<!-- Responsividade Mobile -->
<style>
    @media (max-width: 768px) {
        .blog-detail-header,
        .blog-detail-body {
            text-align: left !important;
        }
        .blog-detail-header div {
            justify-content: flex-start !important;
        }
        .gallery-item-editorial {
            flex-direction: column !important;
            gap: 20px !important;
        }
        .gallery-item-editorial > div, 
        .gallery-item-editorial > figcaption {
            width: 100% !important;
        }
    }
</style>

<?php require_once BASE_DIR . '/src/Views/partials/footer.php'; ?>