<?php require_once BASE_DIR . '/src/Views/partials/header.php'; ?>

<main class="page-container internal-page-wrapper projects-wrapper" style="max-width: 1300px; margin: 0 auto; padding-top: 140px; padding-bottom: 100px; padding-left: 30px; padding-right: 30px;">
    
    <!-- CABEÇALHO DA PÁGINA (TÍTULO "Projetos" + METADADO DE SELEÇÃO À DIREITA) -->
    <header class="projects-header" style="display: flex; justify-content: space-between; align-items: flex-end; margin-bottom: 60px; border-bottom: 1px solid #eee; padding-bottom: 25px;">
        <h1 style="font-family: 'Times New Roman', Georgia, serif; font-weight: 400; font-size: 3.5rem; color: #111; margin: 0; line-height: 1;">
            Projetos
        </h1>
        <span style="font-size: 0.75rem; text-transform: uppercase; letter-spacing: 2px; color: #888; font-weight: 400;">
            SELEÇÃO 2022—2026
        </span>
    </header>

    <!-- GRID ASSIMÉTRICO DE PROJETOS -->
    <?php if (!empty($projects)): ?>
        <section class="projects-asymmetric-grid">
            <?php 
                $index = 0;
                foreach ($projects as $project): 
                    // Alterna o estilo entre a coluna maior (esquerda) e menor (direita)
                    $isLarge = ($index % 2 === 0);
                    $index++;
            ?>
                <article class="project-item <?= $isLarge ? 'item-large' : 'item-small' ?>">
                    <a href="<?= URL_BASE ?>/projeto/<?= htmlspecialchars($project['slug']) ?>" class="project-link">
                        
                        <!-- CARD DA IMAGEM COM EFEITO HOVER -->
                        <div class="project-image-box">
                            <img src="<?= URL_BASE ?>/assets/uploads/<?= htmlspecialchars($project['cover_image']) ?>" 
                                 alt="<?= htmlspecialchars($project['title']) ?>" 
                                 loading="lazy">
                        </div>

                        <!--INFORMAÇÕES INFERIORES: TÍTULO À ESQUERDA | METADADOS À DIREITA -->
                        <div class="project-meta-bar">
                            <h2 class="project-title-text">
                                <?= htmlspecialchars($project['title']) ?>
                            </h2>
                            <div class="project-sub-info">
                                <span><?= htmlspecialchars($project['category_name'] ?? 'RESIDENCIAL') ?></span>
                                <span><?= htmlspecialchars($project['location'] ?? 'CURITIBA') ?></span>
                                <span><?= htmlspecialchars($project['year'] ?? '2026') ?></span>
                            </div>
                        </div>

                    </a>
                </article>
            <?php endforeach; ?>
        </section>
    <?php else: ?>
        <p style="text-align: center; color: #888; font-size: 0.9rem; margin-top: 60px;">Nenhum projeto cadastrado no momento.</p>
    <?php endif; ?>

</main>

<!-- ESTILOS DO GRID ASSIMÉTRICO E EFEITO HOVER -->
<style>
    /* Estrutura do Grid de 2 Colunas Assimétricas */
    .projects-asymmetric-grid {
        display: grid;
        grid-template-columns: 1.35fr 1fr;
        gap: 50px 40px;
        align-items: start;
    }

    .project-item {
        width: 100%;
    }

    .project-link {
        text-decoration: none;
        display: block;
        color: inherit;
    }

    /* Contêiner da Imagem com proporções e efeito de transição */
    .project-image-box {
        width: 100%;
        overflow: hidden;
        background-color: #f2f2f2;
        margin-bottom: 15px;
    }

    .item-large .project-image-box {
        aspect-ratio: 16 / 11;
    }

    .item-small .project-image-box {
        aspect-ratio: 1 / 1;
    }

    .project-image-box img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        display: block;
        transition: transform 0.6s cubic-bezier(0.25, 1, 0.5, 1), opacity 0.4s ease;
    }

    /* Efeito Hover na Imagem */
    .project-link:hover .project-image-box img {
        transform: scale(1.03);
        opacity: 0.92;
    }

    /* Barra de informações abaixo da fotografia */
    .project-meta-bar {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        padding-top: 5px;
        border-top: 1px solid #e0e0e0;
    }

    .project-title-text {
        font-family: 'Times New Roman', Georgia, serif;
        font-weight: 400;
        font-size: 1.25rem;
        color: #111;
        margin: 0;
        letter-spacing: -0.2px;
    }

    .project-sub-info {
        font-size: 0.68rem;
        text-transform: uppercase;
        letter-spacing: 1.2px;
        color: #777;
        text-align: right;
        display: flex;
        flex-direction: column;
        gap: 2px;
        line-height: 1.3;
    }

    /* Adaptação para Telas Menores (Móveis) */
    @media (max-width: 868px) {
        .projects-asymmetric-grid {
            grid-template-columns: 1fr !important;
            gap: 40px !important;
        }
        .projects-header h1 {
            font-size: 2.5rem !important;
        }
        .item-large .project-image-box,
        .item-small .project-image-box {
            aspect-ratio: 4 / 3 !important;
        }
    }
</style>

<?php require_once BASE_DIR . '/src/Views/partials/footer.php'; ?>