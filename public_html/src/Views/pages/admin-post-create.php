<?php require_once BASE_DIR . '/src/Views/partials/header.php'; ?>

<main class="page-container internal-page-wrapper admin-container">
    <div class="admin-card">
        <h1 class="admin-title">Publicar Novo Artigo no Blog</h1>

        <?php if (!empty($_SESSION['admin_error'])): ?>
            <div style="background:#f8d7da; color:#721c24; padding:12px; margin-bottom:20px; font-size:0.85rem;">
                <?= $_SESSION['admin_error']; unset($_SESSION['admin_error']); ?>
            </div>
        <?php endif; ?>

        <form action="<?= URL_BASE ?>/admin/blog/salvar" method="POST" enctype="multipart/form-data" class="admin-form">
            
            <div class="form-group">
                <label for="title">Título do Artigo *</label>
                <input type="text" id="title" name="title" required placeholder="Ex: A Importância da Luz Natural na Arquitetura">
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label for="category">Categoria</label>
                    <input type="text" id="category" name="category" placeholder="Ex: Arquitetura, Design, Conceito">
                </div>
                <div class="form-group">
                    <label for="tags">Tags (separadas por vírgula)</label>
                    <input type="text" id="tags" name="tags" placeholder="Ex: iluminação, moderno, residencial, reforma">
                </div>
                <div class="form-group">
                    <label for="author_name">Autor</label>
                    <input type="text" id="author_name" name="author_name" value="Autá AD">
                </div>
            </div>

            <div class="form-group">
                <label for="cover_image">Imagem de Capa *</label>
                <input type="file" id="cover_image" name="cover_image" accept="image/*" required>
            </div>

            <div class="form-group">
                <label for="summary">Resumo Curto (Aparece no Grid)</label>
                <textarea id="summary" name="summary" rows="2" placeholder="Breve introdução sobre o tema..."></textarea>
            </div>

            <div class="form-group">
                <label for="content">Conteúdo Completo *</label>
                <textarea id="content" name="content" rows="12" required placeholder="Escreva aqui o artigo completo..."></textarea>
            </div>

            <!-- PASSO 3: GALERIA DE IMAGENS ADICIONAIS COM LEGENDAS INDIVIDUAIS -->
            <div style="margin-top: 35px; padding-top: 25px; border-top: 1px solid #eee;">
                <label style="display: block; font-size: 0.85rem; text-transform: uppercase; letter-spacing: 1px; font-weight: 600; margin-bottom: 15px;">
                    Galeria de Fotos do Projeto / Artigo (Opcional)
                </label>

                <div id="gallery-container" style="display: flex; flex-direction: column; gap: 15px;">
                    <div class="gallery-row" style="display: grid; grid-template-columns: 1fr 2fr; gap: 15px; background: #f9f9f9; padding: 15px; border: 1px solid #e5e5e5;">
                        <div>
                            <label style="display:block; font-size: 0.75rem; text-transform: uppercase; margin-bottom: 5px;">Imagem</label>
                            <input type="file" name="gallery_images[]" accept="image/*" style="width: 100%; font-size: 0.8rem;">
                        </div>
                        <div>
                            <label style="display:block; font-size: 0.75rem; text-transform: uppercase; margin-bottom: 5px;">Legenda da Fotografia</label>
                            <input type="text" name="gallery_captions[]" placeholder="Ex: Vista frontal da fachada em betão e madeira" style="width: 100%; padding: 8px; border: 1px solid #ccc; font-size: 0.85rem;">
                        </div>
                    </div>
                </div>

                <button type="button" id="add-photo-btn" style="margin-top: 15px; background: #eee; border: 1px solid #ccc; padding: 10px 18px; font-size: 0.8rem; text-transform: uppercase; cursor: pointer; letter-spacing: 0.5px;">
                    + Adicionar Outra Fotografia
                </button>
            </div>

            <div class="form-actions" style="margin-top: 40px;">
                <button type="submit" class="btn-primary">Publicar Artigo</button>
                <a href="<?= URL_BASE ?>/admin" class="btn-secondary">Cancelar</a>
            </div>

        </form>
    </div>
</main>

<script>
    document.getElementById('add-photo-btn').addEventListener('click', function() {
        const container = document.getElementById('gallery-container');
        const row = document.createElement('div');
        row.className = 'gallery-row';
        row.style.cssText = 'display: grid; grid-template-columns: 1fr 2fr; gap: 15px; background: #f9f9f9; padding: 15px; border: 1px solid #e5e5e5;';
        row.innerHTML = `
            <div>
                <label style="display:block; font-size: 0.75rem; text-transform: uppercase; margin-bottom: 5px;">Imagem</label>
                <input type="file" name="gallery_images[]" accept="image/*" style="width: 100%; font-size: 0.8rem;">
            </div>
            <div>
                <label style="display:block; font-size: 0.75rem; text-transform: uppercase; margin-bottom: 5px;">Legenda da Fotografia</label>
                <input type="text" name="gallery_captions[]" placeholder="Ex: Detalhe do acabamento e luz natural" style="width: 100%; padding: 8px; border: 1px solid #ccc; font-size: 0.85rem;">
            </div>
        `;
        container.appendChild(row);
    });
</script>

<?php require_once BASE_DIR . '/src/Views/partials/footer.php'; ?>