<?php require_once BASE_DIR . '/src/Views/partials/header.php'; ?>

<main class="page-container internal-page-wrapper admin-container">
    <div class="admin-card">
        <h1 class="admin-title">Cadastrar Novo Projeto</h1>

        <form action="<?= URL_BASE ?>/admin/salvar" method="POST" enctype="multipart/form-data" class="admin-form">
            
            <div class="form-group">
                <label for="title">Título do Projeto *</label>
                <input type="text" id="title" name="title" placeholder="Ex: Casa da Montanha" required>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label for="category_id">Categoria *</label>
                    <select id="category_id" name="category_id" required>
                        <?php if (!empty($categories)): ?>
                            <?php foreach ($categories as$cat): ?>
                                <option value="<?= $cat['id'] ?>"><?= htmlspecialchars($cat['name']) ?></option>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <option value="1">Comercial</option>
                            <option value="2">Residencial</option>
                            <option value="3">Interiores</option>
                        <?php endif; ?>
                    </select>
                </div>

                <div class="form-group">
                    <label for="location">Localização</label>
                    <input type="text" id="location" name="location" placeholder="Ex: Curitiba, PR">
                </div>

                <div class="form-group">
                    <label for="year">Ano</label>
                    <input type="text" id="year" name="year" value="<?= date('Y') ?>">
                </div>
            </div>

            <div class="form-group">
                <label for="cover_image">Foto de Capa (Imagem Principal) *</label>
                <input type="file" id="cover_image" name="cover_image" accept="image/*" required>
            </div>

            <!-- GALERIA COM LEGENDA INDIVIDUAL -->
            <div class="form-group">
                <label>Galeria de Fotos (com Legenda para cada Imagem)</label>
                <div id="gallery-inputs-container">
                    <div class="gallery-upload-row">
                        <input type="file" name="gallery_images[]" accept="image/*">
                        <input type="text" name="gallery_captions[]" placeholder="Legenda/Descrição (ex: Fachada principal ao entardecer)">
                    </div>
                </div>
                <button type="button" id="add-more-images" class="btn-secondary" style="margin-top: 10px; cursor: pointer;">+ Adicionar mais uma imagem</button>
            </div>

            <div class="form-group">
                <label for="description">Descrição Geral do Projeto</label>
                <textarea id="description" name="description" rows="5" placeholder="Escreva sobre o conceito e materiais do projeto..."></textarea>
            </div>

            <div class="form-actions">
                <button type="submit" class="btn-primary">Salvar Projeto</button>
                <a href="<?= URL_BASE ?>/admin" class="btn-secondary">Cancelar</a>
            </div>

        </form>
    </div>
</main>

<script>
document.getElementById('add-more-images').addEventListener('click', function() {
    const container = document.getElementById('gallery-inputs-container');
    const newRow = document.createElement('div');
    newRow.className = 'gallery-upload-row';
    newRow.style.marginTop = '10px';
    newRow.innerHTML = `
        <input type="file" name="gallery_images[]" accept="image/*">
        <input type="text" name="gallery_captions[]" placeholder="Legenda/Descrição da imagem">
    `;
    container.appendChild(newRow);
});
</script>

<?php require_once BASE_DIR . '/src/Views/partials/footer.php'; ?>