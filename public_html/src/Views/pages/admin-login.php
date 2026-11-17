<?php require_once BASE_DIR . '/src/Views/partials/header.php'; ?>

<main class="page-container internal-page-wrapper admin-container">
    <div class="admin-card" style="max-width: 420px; margin: 60px auto;">
        <span class="section-subtitle">Área Restrita</span>
        <h1 class="admin-title" style="margin-bottom: 20px;">Acesso ao Painel</h1>

        <?php if (!empty($_SESSION['login_error'])): ?>
            <div style="background: #fdf2f2; color: #d9534f; padding: 12px; font-size: 0.85rem; margin-bottom: 20px; border: 1px solid #f5c6cb;">
                <?= $_SESSION['login_error']; unset($_SESSION['login_error']); ?>
            </div>
        <?php endif; ?>

        <form action="<?= URL_BASE ?>/admin/autenticar" method="POST" class="admin-form">
            <div class="form-group">
                <label for="username">Usuário *</label>
                <input type="text" id="username" name="username" required placeholder="Digite seu usuário">
            </div>

            <div class="form-group">
                <label for="password">Senha *</label>
                <input type="password" id="password" name="password" required placeholder="Digite sua senha" style="width: 100%; padding: 12px 14px; border: 1px solid #ccc; font-size: 0.95rem; background-color: #fafafa;">
            </div>

            <div class="form-actions" style="margin-top: 10px;">
                <button type="submit" class="btn-primary" style="width: 100%;">Entrar</button>
            </div>
        </form>
    </div>
</main>

<?php require_once BASE_DIR . '/src/Views/partials/footer.php'; ?>