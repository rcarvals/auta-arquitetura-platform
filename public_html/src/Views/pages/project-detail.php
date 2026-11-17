<?php require_once BASE_DIR . '/src/Views/partials/header.php'; ?>

<?php
    // Fallback: se a variável $galleryImages não vier do controller, busca diretamente pelo model
    if (!isset($galleryImages)) {
        $projectModel = new ProjectModel();
        $galleryImages = $projectModel->getProjectImages($project['id']);
    }
?>

<main class="page-container internal-page-wrapper project-detail-container" style="max-width: 1200px; margin: 0 auto; padding-top: 140px; padding-bottom: 80px;">
    
    <!-- CABEÇALHO DO PROJETO -->
    <header class="project-detail-header" style="margin-bottom: 40px; text-align: left; width: 100%;">
        <span style="text-transform: uppercase; letter-spacing: 2px; font-size: 0.75rem; color: #888; display: block; margin-bottom: 10px;">
            <?= htmlspecialchars($project['category_name'] ?? 'Comercial') ?>
        </span>

        <h1 style="font-weight: 300; font-size: 2.2rem; line-height: 1.2; color: #444; margin: 0 0 15px 0; letter-spacing: -0.3px; text-transform: capitalize;">
            <?= htmlspecialchars($project['title']) ?>
        </h1>

        <div style="font-size: 0.8rem; color: #777; text-transform: uppercase; letter-spacing: 1.5px; font-weight: 400; display: flex; align-items: center; justify-content: flex-start; gap: 8px;">
            <?php if (!empty($project['location'])): ?>
                <span>LOCAL: <strong><?= htmlspecialchars($project['location']) ?></strong></span>
            <?php endif; ?>
            
            <?php if (!empty($project['location']) && !empty($project['year'])): ?>
                <span>&bull;</span>
            <?php endif; ?>

            <?php if (!empty($project['year'])): ?>
                <span>ANO: <strong><?= htmlspecialchars($project['year']) ?></strong></span>
            <?php endif; ?>
        </div>
    </header>

    <!-- IMAGEM DE CAPA DO PROJETO -->
    <?php if (!empty($project['cover_image'])): ?>
        <div class="project-detail-cover" style="width: 100%; margin-bottom: 50px; overflow: hidden;">
            <img src="<?= URL_BASE ?>/assets/uploads/<?= htmlspecialchars($project['cover_image']) ?>" 
                 alt="<?= htmlspecialchars($project['title']) ?>" 
                 style="width: 100%; height: auto; max-height: 750px; display: block; object-fit: cover;">
        </div>
    <?php endif; ?>

    <!-- MEMORIAL DESCRITIVO / TEXTO DO PROJETO -->
    <?php if (!empty($project['description'])): ?>
        <article class="project-detail-body" style="font-size: 1.05rem; line-height: 1.9; color: #222; font-weight: 300; margin-bottom: 70px; width: 100%; text-align: left;">
            <?= nl2br($project['description']) ?>
        </article>
    <?php endif; ?>

    <!-- GALERIA DE IMAGENS COMPLEMENTARES COM LEGENDAS / TEXTOS -->
    <?php if (!empty($galleryImages)): ?>
        <section class="project-gallery-section" style="margin-top: 80px; margin-bottom: 80px; display: flex; flex-direction: column; gap: 80px;">
            
            <?php 
                $index = 0;
                foreach ($galleryImages as $img): 
                    $pattern = $index % 4;
                    $index++;

                    // Mapeia o caminho da imagem e a legenda
                    $imgPath = $img['image_path'] ?? $img['image_url'] ?? '';
                    $imgCaption = $img['caption'] ?? '';

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
                    
                    <!-- Imagem Complementar -->
                    <div style="width: <?= $imgWidth ?>; overflow: hidden; background: #f9f9f9;">
                        <img src="<?= URL_BASE ?>/assets/uploads/<?= htmlspecialchars($imgPath) ?>" 
                             alt="<?= htmlspecialchars($imgCaption ?: $project['title']) ?>" 
                             style="width: 100%; height: auto; display: block; object-fit: cover;">
                    </div>

                    <!-- Texto / Legenda da Imagem -->
                    <?php if (!empty($imgCaption)): ?>
                        <figcaption style="width: <?= $textWidth ?>; font-size: 0.95rem; line-height: 1.7; color: #555; font-weight: 300; letter-spacing: 0.3px;">
                            <span style="display: block; width: 30px; height: 1px; background: #111; margin-bottom: 15px;"></span>
                            <?= htmlspecialchars_decode($imgCaption) ?>
                        </figcaption>
                    <?php else: ?>
                        <div style="width: <?= $textWidth ?>;"></div>
                    <?php endif; ?>

                </figure>
            <?php endforeach; ?>

        </section>
    <?php endif; ?>

    <!-- NAVEGAÇÃO DE RETORNO -->
    <footer style="border-top: 1px solid #eee; padding-top: 50px; margin-top: 80px; text-align: center;">
        <a href="<?= URL_BASE ?>/projetos" style="text-decoration: none; color: #111; font-size: 0.8rem; text-transform: uppercase; letter-spacing: 2px; border: 1px solid #111; padding: 12px 28px; display: inline-block;">
            &larr; Voltar para o Portfólio
        </a>
    </footer>

</main>

<style>
    @media (max-width: 768px) {
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
