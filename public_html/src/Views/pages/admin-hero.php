<?php require_once BASE_DIR . '/src/Views/partials/header.php'; ?>

<main class="page-container internal-page-wrapper" style="max-width: 900px; margin: 0 auto; padding-top: 140px; padding-bottom: 80px;">
    
    <header style="margin-bottom: 40px;">
        <h1 style="font-weight: 300; font-size: 2rem; color: #111; text-transform: uppercase; letter-spacing: 1px;">
            Gerir Conteúdo da Homepage
        </h1>
        <p style="color: #666; font-size: 0.9rem;">Altere as mídias da capa (Hero) e a imagem/texto da secção Conceito e Identidade.</p>
    </header>

    <?php if (!empty($_SESSION['hero_success'])): ?>
        <div style="padding: 15px; background: #d4edda; color: #155724; border: 1px solid #c3e6cb; margin-bottom: 25px;">
            <?= $_SESSION['hero_success']; unset($_SESSION['hero_success']); ?>
        </div>
    <?php endif; ?>

    <form action="<?= URL_BASE ?>/admin/hero/salvar" method="POST" enctype="multipart/form-data" style="background: #f9f9f9; padding: 30px; border: 1px solid #eee;">
        
        <!-- ================= BLOCO 1: CAPA PRINCIPAL (HERO) ================= -->
        <h2 style="font-size: 1.2rem; font-weight: 400; text-transform: uppercase; letter-spacing: 1.5px; margin-top: 0; margin-bottom: 20px; color: #111; border-bottom: 1px solid #ddd; padding-bottom: 10px;">
            1. Capa Principal (Hero Section)
        </h2>

        <div style="margin-bottom: 30px;">
            <label style="display: block; font-size: 0.85rem; text-transform: uppercase; letter-spacing: 1px; font-weight: 600; margin-bottom: 12px; color: #111;">
                Modo de Exibição Atual:
            </label>
            <div style="display: flex; gap: 30px;">
                <label style="font-size: 0.95rem; cursor: pointer; display: flex; align-items: center; gap: 8px;">
                    <input type="radio" name="hero_type" value="slider" <?= ($heroConfig['type'] ?? 'slider') === 'slider' ? 'checked' : '' ?> onchange="toggleHeroOptions()">
                    Sequência de Slides (Imagens)
                </label>
                <label style="font-size: 0.95rem; cursor: pointer; display: flex; align-items: center; gap: 8px;">
                    <input type="radio" name="hero_type" value="video" <?= ($heroConfig['type'] ?? '') === 'video' ? 'checked' : '' ?> onchange="toggleHeroOptions()">
                    Vídeo MP4 em Loop
                </label>
            </div>
        </div>

        <!-- SEÇÃO VÍDEO -->
        <div id="section-video" style="display: <?= ($heroConfig['type'] ?? '') === 'video' ? 'block' : 'none' ?>; margin-bottom: 25px;">
            <h3 style="font-weight: 400; font-size: 1rem; margin: 0 0 10px 0;">Upload do Vídeo MP4</h3>
            <?php if (!empty($heroConfig['video_file'])): ?>
                <div style="margin-bottom: 15px;">
                    <p style="font-size: 0.8rem; color: #555; margin-bottom: 5px;">Vídeo atual:</p>
                    <video src="<?= URL_BASE ?>/assets/videos/<?= htmlspecialchars($heroConfig['video_file']) ?>" muted loop style="max-width: 250px; display: block; border: 1px solid #ccc;"></video>
                </div>
            <?php endif; ?>
            <input type="file" name="video_file" accept="video/mp4" style="font-size: 0.85rem;">
        </div>

        <!-- SEÇÃO SLIDES -->
        <div id="section-slider" style="display: <?= ($heroConfig['type'] ?? 'slider') === 'slider' ? 'block' : 'none' ?>; margin-bottom: 25px;">
            <h3 style="font-weight: 400; font-size: 1rem; margin: 0 0 10px 0;">Imagens dos Slides</h3>
            <?php if (!empty($slides)): ?>
                <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(130px, 1fr)); gap: 10px; margin-bottom: 15px;">
                    <?php foreach ($slides as $slide): ?>
                        <div style="position: relative; border: 1px solid #ddd; background: #fff; padding: 3px;">
                            <img src="<?= URL_BASE ?>/assets/uploads/<?= htmlspecialchars($slide['image_path']) ?>" style="width: 100%; height: 80px; object-fit: cover; display: block;">
                            <a href="<?= URL_BASE ?>/admin/hero/slide/excluir/<?= $slide['id'] ?>" onclick="return confirm('Excluir este slide?')" style="position: absolute; top: 5px; right: 5px; background: rgba(200,0,0,0.8); color: #fff; text-decoration: none; padding: 2px 6px; font-size: 0.7rem; border-radius: 2px;">&times;</a>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
            <input type="file" name="slide_images[]" multiple accept="image/*" style="font-size: 0.85rem;">
        </div>

        <hr style="border: none; border-top: 1px solid #e0e0e0; margin: 40px 0;">

        <!-- ================= BLOCO 2: CONCEITO E IDENTIDADE ================= -->
        <h2 style="font-size: 1.2rem; font-weight: 400; text-transform: uppercase; letter-spacing: 1.5px; margin-top: 0; margin-bottom: 20px; color: #111; border-bottom: 1px solid #ddd; padding-bottom: 10px;">
            2. Secção Conceito e Identidade
        </h2>

        <div style="margin-bottom: 20px;">
            <label style="display: block; font-size: 0.8rem; text-transform: uppercase; letter-spacing: 1px; color: #333; margin-bottom: 8px;">
                Título da Secção:
            </label>
            <input type="text" name="concept_title" value="<?= htmlspecialchars($heroConfig['concept_title'] ?? 'CONCEITO E IDENTIDADE') ?>" style="width: 100%; padding: 10px; border: 1px solid #ccc; font-size: 0.9rem;">
        </div>

        <div style="margin-bottom: 20px;">
            <label style="display: block; font-size: 0.8rem; text-transform: uppercase; letter-spacing: 1px; color: #333; margin-bottom: 8px;">
                Texto Explicativo:
            </label>
            <textarea name="concept_text" rows="4" style="width: 100%; padding: 10px; border: 1px solid #ccc; font-size: 0.9rem; font-family: inherit;"><?= htmlspecialchars($heroConfig['concept_text'] ?? 'Cada projeto nasce da leitura atenta do contexto e das necessidades de quem habita. Buscamos a harmonia entre luz natural, materiais nobres e soluções funcionais, resultando em uma arquitetura autêntica e duradoura.') ?></textarea>
        </div>

        <div style="margin-bottom: 30px;">
            <label style="display: block; font-size: 0.8rem; text-transform: uppercase; letter-spacing: 1px; color: #333; margin-bottom: 8px;">
                Imagem Lateral da Secção (Estática):
            </label>
            
            <?php 
                $currentConceptImg = !empty($heroConfig['concept_image']) ? $heroConfig['concept_image'] : 'capa-home.jpg';
            ?>
            <div style="margin-bottom: 15px; display: flex; align-items: center; gap: 20px;">
                <img src="<?= URL_BASE ?>/assets/uploads/<?= htmlspecialchars($currentConceptImg) ?>" 
                     alt="Imagem do Conceito" 
                     style="width: 180px; height: 120px; object-fit: cover; border: 1px solid #ccc; background: #fff;"
                     onerror="this.src='<?= URL_BASE ?>/assets/img/home-hero.jpg';">
                <span style="font-size: 0.8rem; color: #777;">Imagem atualmente em exibição nesta secção.</span>
            </div>

            <input type="file" name="concept_image" accept="image/*" style="font-size: 0.85rem;">
        </div>

        <div style="margin-top: 40px; border-top: 1px solid #eee; padding-top: 20px;">
            <button type="submit" style="background: #111; color: #fff; border: none; padding: 14px 30px; font-size: 0.8rem; text-transform: uppercase; letter-spacing: 1.5px; cursor: pointer;">
                GUARDAR ALTERAÇÕES
            </button>
            <a href="<?= URL_BASE ?>/admin" style="margin-left: 15px; text-decoration: none; color: #666; font-size: 0.85rem;">Cancelar</a>
        </div>

    </form>

</main>

<script>
    function toggleHeroOptions() {
        const type = document.querySelector('input[name="hero_type"]:checked').value;
        document.getElementById('section-video').style.display = (type === 'video') ? 'block' : 'none';
        document.getElementById('section-slider').style.display = (type === 'slider') ? 'block' : 'none';
    }
</script>

<?php require_once BASE_DIR . '/src/Views/partials/footer.php'; ?>