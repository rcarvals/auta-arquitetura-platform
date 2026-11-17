<?php
    // Busca a configuração do Hero na base de dados
    global $pdo;
    $heroConfig = null;
    $heroSlides = [];

    if (isset($pdo)) {
        try {
            $stmtHero = $pdo->query("SELECT * FROM home_hero WHERE id = 1 LIMIT 1");
            if ($stmtHero) {
                $heroConfig = $stmtHero->fetch(PDO::FETCH_ASSOC);
            }

            $stmtSlides = $pdo->query("SELECT * FROM home_hero_slides ORDER BY order_index ASC, id ASC");
            if ($stmtSlides) {
                $heroSlides = $stmtSlides->fetchAll(PDO::FETCH_ASSOC);
            }
        } catch (\PDOException $e) {
            // Silencioso em caso de tabela ainda não criada
        }
    }
?>

<!-- HERO SECTION DA HOME (DINÂMICA: VÍDEO OU SLIDER) -->
<section class="hero-section" style="position: relative; width: 100%; height: 100vh; overflow: hidden; background: #000;">
    
    <?php if (($heroConfig['type'] ?? '') === 'video' && !empty($heroConfig['video_file'])): ?>
        
        <!-- OPÇÃO 1: VÍDEO MP4 EM LOOP -->
        <video autoplay loop muted playsinline 
               poster="<?= URL_BASE ?>/assets/uploads/capa-home.jpg"
               style="width: 100%; height: 100%; object-fit: cover; display: block; position: absolute; top: 0; left: 0;">
            <source src="<?= URL_BASE ?>/assets/videos/<?= htmlspecialchars($heroConfig['video_file']) ?>" type="video/mp4">
            <img src="<?= URL_BASE ?>/assets/uploads/capa-home.jpg" alt="Autá AD - Arquitetura e Design" style="width: 100%; height: 100%; object-fit: cover;">
        </video>

    <?php elseif (($heroConfig['type'] ?? 'slider') === 'slider' && !empty($heroSlides)): ?>

        <!-- OPÇÃO 2: SEQUÊNCIA DE SLIDES (CARROSSEL AUTOMÁTICO) -->
        <div class="hero-slider-wrapper" style="width: 100%; height: 100%; position: absolute; top: 0; left: 0;">
            <?php foreach ($heroSlides as $k => $slide): ?>
                <div class="hero-slide-item" style="position: absolute; top: 0; left: 0; width: 100%; height: 100%; opacity: <?= $k === 0 ? '1' : '0' ?>; transition: opacity 1.2s ease-in-out;">
                    <img src="<?= URL_BASE ?>/assets/uploads/<?= htmlspecialchars($slide['image_path']) ?>" 
                         alt="Autá AD - Arquitetura e Design" 
                         style="width: 100%; height: 100%; object-fit: cover; display: block;">
                </div>
            <?php endforeach; ?>
        </div>

        <script>
            document.addEventListener('DOMContentLoaded', function() {
                const slides = document.querySelectorAll('.hero-slide-item');
                if (slides.length > 1) {
                    let current = 0;
                    setInterval(() => {
                        slides[current].style.opacity = '0';
                        current = (current + 1) % slides.length;
                        slides[current].style.opacity = '1';
                    }, 5000);
                }
            });
        </script>

    <?php else: ?>

        <!-- OPÇÃO FALLBACK: IMAGEM FIXA PADRÃO -->
        <img src="<?= URL_BASE ?>/assets/uploads/capa-home.jpg" 
             alt="Autá AD - Arquitetura e Design" 
             style="width: 100%; height: 100%; object-fit: cover; display: block;"
             onerror="this.src='<?= URL_BASE ?>/assets/img/home-hero.jpg';">

    <?php endif; ?>

    <!-- BLOCO DE TEXTO ALINHADO À ESQUERDA NO CANTO INFERIOR -->
    <div class="hero-text-block" style="position: absolute; bottom: 50px; left: 50px; text-align: left; z-index: 10; color: #fff; text-shadow: 0 2px 10px rgba(0,0,0,0.6);">
        <h2 style="font-size: 2.8rem; font-weight: 300; letter-spacing: 2px; margin: 0 0 8px 0; text-transform: uppercase;">
            <!--O projeto começa antes da forma.-->
        </h2>
    </div>

    <!-- INDICADOR DE ROLAGEM (SCROLL INDICATOR) -->
    <a href="#conceito" class="scroll-indicator" aria-label="Rolar para o conteúdo">
        <span class="scroll-arrow"></span>
    </a>

</section>

<!-- ESTILOS E ANIMAÇÃO DO INDICADOR DE ROLAGEM -->
<style>
    .scroll-indicator {
        position: absolute;
        bottom: 30px;
        left: 50%;
        transform: translateX(-50%);
        z-index: 10;
        display: flex;
        flex-direction: column;
        align-items: center;
        text-decoration: none;
        opacity: 0.8;
        transition: opacity 0.3s ease;
    }

    .scroll-indicator:hover {
        opacity: 1;
    }

    .scroll-arrow {
        width: 16px;
        height: 16px;
        border-right: 1.5px solid #ffffff;
        border-bottom: 1.5px solid #ffffff;
        transform: rotate(45deg);
        animation: scrollBounce 2s infinite;
        filter: drop-shadow(0 2px 4px rgba(0,0,0,0.5));
    }

    @keyframes scrollBounce {
        0%, 20%, 50%, 80%, 100% {
            transform: translateY(0) rotate(45deg);
        }
        40% {
            transform: translateY(8px) rotate(45deg);
        }
        60% {
            transform: translateY(4px) rotate(45deg);
        }
    }

    @media (max-width: 768px) {
        .hero-text-block {
            bottom: 60px !important;
            left: 20px !important;
            right: 20px !important;
            text-align: left !important;
        }
        .hero-text-block h2 {
            font-size: 2rem !important;
        }
        .scroll-indicator {
            bottom: 20px !important;
        }
    }
</style>

<!-- 3. TEXTO SOBRE O CONCEITO DE CRIAÇÃO -->
<section id="conceito" class="feature-section">
...

<!-- ESTILOS E ANIMAÇÃO DO INDICADOR DE ROLAGEM (COR PRETA) -->
<style>
    .hero-scroll-indicator {
        position: absolute;
        bottom: 35px;
        left: 50%;
        transform: translateX(-50%);
        z-index: 10;
        display: flex;
        flex-direction: column;
        align-items: center;
        gap: 8px;
        text-decoration: none;
        color: #000000;
        filter: drop-shadow(0 0 4px rgba(255, 255, 255, 0.8));
        transition: opacity 0.3s ease;
    }

    .hero-scroll-indicator:hover {
        opacity: 0.7;
    }

    .scroll-label {
        font-size: 0.65rem;
        font-weight: 600;
        letter-spacing: 3px;
        text-transform: uppercase;
        color: #000000;
    }

    /* Pista da linha de rolagem */
    .scroll-line-track {
        width: 1.5px;
        height: 40px;
        background: rgba(0, 0, 0, 0.25);
        position: relative;
        overflow: hidden;
    }

    /* Linha preta que desliza para baixo continuamente */
    .scroll-line-fill {
        width: 100%;
        height: 50%;
        background: #000000;
        position: absolute;
        top: -50%;
        left: 0;
        animation: scrollLineAnim 2s cubic-bezier(0.65, 0, 0.35, 1) infinite;
    }

    @keyframes scrollLineAnim {
        0% {
            top: -50%;
        }
        50% {
            top: 100%;
        }
        100% {
            top: 100%;
        }
    }

    @media (max-width: 768px) {
        .hero-text-block {
            bottom: 70px !important;
            left: 20px !important;
            right: 20px !important;
            text-align: left !important;
        }
        .hero-text-block h2 {
            font-size: 2rem !important;
        }
        .hero-scroll-indicator {
            bottom: 20px !important;
        }
        .scroll-line-track {
            height: 30px;
        }
    }
</style>

<!-- ESTILOS E ANIMAÇÃO DO NOVO INDICADOR DE ROLAGEM -->
<style>
    .hero-scroll-indicator {
        position: absolute;
        bottom: 35px;
        left: 50%;
        transform: translateX(-50%);
        z-index: 10;
        display: flex;
        flex-direction: column;
        align-items: center;
        gap: 8px;
        text-decoration: none;
        color: #ffffff;
        filter: drop-shadow(0 2px 6px rgba(0,0,0,0.6));
        transition: opacity 0.3s ease;
    }

    .hero-scroll-indicator:hover {
        opacity: 0.8;
    }

    .scroll-label {
        font-size: 0.65rem;
        font-weight: 500;
        letter-spacing: 3px;
        text-transform: uppercase;
        color: #ffffff;
    }

    /* Pista da linha de rolagem */
    .scroll-line-track {
        width: 1px;
        height: 40px;
        background: rgba(255, 255, 255, 0.3);
        position: relative;
        overflow: hidden;
    }

    /* Linha que desliza para baixo continuamente */
    .scroll-line-fill {
        width: 100%;
        height: 50%;
        background: #ffffff;
        position: absolute;
        top: -50%;
        left: 0;
        animation: scrollLineAnim 2s cubic-bezier(0.65, 0, 0.35, 1) infinite;
    }

    @keyframes scrollLineAnim {
        0% {
            top: -50%;
        }
        50% {
            top: 100%;
        }
        100% {
            top: 100%;
        }
    }

    @media (max-width: 768px) {
        .hero-text-block {
            bottom: 70px !important;
            left: 20px !important;
            right: 20px !important;
            text-align: left !important;
        }
        .hero-text-block h2 {
            font-size: 2rem !important;
        }
        .hero-scroll-indicator {
            bottom: 20px !important;
        }
        .scroll-line-track {
            height: 30px;
        }
    }
</style>

<!-- ESTILOS E ANIMAÇÃO DO INDICADOR DE ROLAGEM -->
<style>
    .scroll-indicator {
        position: absolute;
        bottom: 30px;
        left: 50%;
        transform: translateX(-50%);
        z-index: 10;
        display: flex;
        flex-direction: column;
        align-items: center;
        text-decoration: none;
        opacity: 0.8;
        transition: opacity 0.3s ease;
    }

    .scroll-indicator:hover {
        opacity: 1;
    }

    .scroll-arrow {
        width: 16px;
        height: 16px;
        border-right: 1.5px solid #ffffff;
        border-bottom: 1.5px solid #ffffff;
        transform: rotate(45deg);
        animation: scrollBounce 2s infinite;
        filter: drop-shadow(0 2px 4px rgba(0,0,0,0.5));
    }

    @keyframes scrollBounce {
        0%, 20%, 50%, 80%, 100% {
            transform: translateY(0) rotate(45deg);
        }
        40% {
            transform: translateY(8px) rotate(45deg);
        }
        60% {
            transform: translateY(4px) rotate(45deg);
        }
    }

    @media (max-width: 768px) {
        .hero-text-block {
            bottom: 60px !important;
            left: 20px !important;
            right: 20px !important;
            text-align: left !important;
        }
        .hero-text-block h2 {
            font-size: 2rem !important;
        }
        .scroll-indicator {
            bottom: 20px !important;
        }
    }
</style>

<!-- 3. TEXTO SOBRE O CONCEITO DE CRIAÇÃO -->
<section id="conceito" class="feature-section">
...

<!-- Ajuste para telas menores/mobile -->
<style>
    @media (max-width: 768px) {
        .hero-text-block {
            bottom: 30px !important;
            left: 20px !important;
            right: 20px !important;
            text-align: left !important;
        }
        .hero-text-block h2 {
            font-size: 2rem !important;
        }
        .hero-text-block p {
            font-size: 0.95rem !important;
        }
    }
</style>

<!-- 3. TEXTO SOBRE O CONCEITO DE CRIAÇÃO COM IMAGEM DINÂMICA À DIREITA -->
<section class="feature-section">
    <div class="feature-container">
        <div class="feature-text">
            <h3><?= htmlspecialchars($heroConfig['concept_title'] ?? 'CONCEITO E IDENTIDADE') ?></h3>
            <p><?= htmlspecialchars($heroConfig['concept_text'] ?? 'Cada projeto nasce da leitura atenta do contexto e das necessidades de quem habita. Buscamos a harmonia entre luz natural, materiais nobres e soluções funcionais, resultando em uma arquitetura autêntica e duradoura.') ?></p>
        </div>
        <div class="feature-image">
            <?php 
                $conceptImg = !empty($heroConfig['concept_image']) ? $heroConfig['concept_image'] : 'capa-home.jpg';
            ?>
            <img src="<?= URL_BASE ?>/assets/uploads/<?= htmlspecialchars($conceptImg) ?>" 
                 alt="<?= htmlspecialchars($heroConfig['concept_title'] ?? 'Conceito Autá Arquitetura') ?>" 
                 loading="lazy"
                 onerror="this.src='<?= URL_BASE ?>/assets/uploads/capa-home.jpg';">
        </div>
    </div>
</section>

<!-- 4. GALERIA DE PROJETOS (TEXTO DE TRANSIÇÃO + 3 IMAGENS LADO A LADO) -->
<section id="portfolio" class="featured-projects-section">
    <div class="featured-projects-container">
        <p class="transition-lead">Veja como nossa abordagem ganha vida!.</p>
        <h3 class="featured-title">Galeria dos nossos projetos</h3>

        <div class="projects-grid-3col">
            <?php 
            // Limita a exibição da home a exatamente 3 projetos
            $featuredProjects = array_slice($projects ?? [], 0, 3);
            if (!empty($featuredProjects)): 
            ?>
                <?php foreach ($featuredProjects as $project): ?>
                    <article class="project-card-compact">
                        <a href="<?= URL_BASE ?>/projeto/<?= $project['slug'] ?>">
                            <div class="project-image-wrapper">
                                <img src="<?= URL_BASE ?>/assets/uploads/<?= htmlspecialchars($project['cover_image']) ?>" 
                                     alt="<?= htmlspecialchars($project['title']) ?>" 
                                     loading="lazy">
                            </div>
                            <div class="project-info-compact">
                                <h4><?= htmlspecialchars($project['title']) ?></h4>
                                <p><?= htmlspecialchars($project['category_name'] ?? 'Projeto') ?> — <?= htmlspecialchars($project['location'] ?? '') ?></p>
                            </div>
                        </a>
                    </article>
                <?php endforeach; ?>
            <?php else: ?>
                <p class="no-projects">Nenhum projeto cadastrado no momento.</p>
            <?php endif; ?>
        </div>
    </div>
</section>

<!-- 5. CHAMADA FINAL PARA CONTATO / PROJETOS -->
<section class="cta-section">
    <div class="cta-container">
        <h3>Quer falar com um dos nossos arquitetos?</h3>
        <p>
            <a href="<?= URL_BASE ?>/projetos" class="cta-link">Explore nossos projetos</a> 
            ou
            <a href="<?= URL_BASE ?>/contato" class="cta-link">saiba mais sobre nossos processos</a>.
        </p>
    </div>
</section>