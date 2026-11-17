<?php require_once BASE_DIR . '/src/Views/partials/header.php'; ?>

<main class="page-container internal-page-wrapper" style="max-width: 900px; margin: 0 auto; padding-top: 140px; padding-bottom: 80px;">
    
    <header style="margin-bottom: 40px; display: flex; justify-content: space-between; align-items: flex-end;">
        <div>
            <h1 style="font-weight: 300; font-size: 2rem; color: #111; text-transform: uppercase; letter-spacing: 1px; margin: 0;">
                Gerenciar Equipe / Colaboradores
            </h1>
            <p style="color: #666; font-size: 0.9rem; margin: 5px 0 0 0;">Cadastre os membros da equipe que aparecerão na página Sobre.</p>
        </div>
        <a href="<?= URL_BASE ?>/admin" style="text-decoration: none; color: #666; font-size: 0.85rem;">&larr; Voltar ao Painel</a>
    </header>

    <?php if (!empty($_SESSION['team_success'])): ?>
        <div style="padding: 15px; background: #d4edda; color: #155724; border: 1px solid #c3e6cb; margin-bottom: 25px;">
            <?= $_SESSION['team_success']; unset($_SESSION['team_success']); ?>
        </div>
    <?php endif; ?>

    <!-- FORMULÁRIO DE NOVO COLABORADOR -->
    <form action="<?= URL_BASE ?>/admin/equipe/salvar" method="POST" enctype="multipart/form-data" style="background: #f9f9f9; padding: 30px; border: 1px solid #eee; margin-bottom: 50px;">
        <h3 style="font-weight: 400; font-size: 1.1rem; margin-top: 0; margin-bottom: 20px; color: #111;">+ Adicionar Novo Colaborador</h3>
        
        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px; margin-bottom: 20px;">
            <div>
                <label style="display: block; font-size: 0.8rem; text-transform: uppercase; letter-spacing: 1px; margin-bottom: 8px;">Nome Completo:</label>
                <input type="text" name="name" required style="width: 100%; padding: 10px; border: 1px solid #ccc; font-size: 0.9rem;">
            </div>
            <div>
                <label style="display: block; font-size: 0.8rem; text-transform: uppercase; letter-spacing: 1px; margin-bottom: 8px;">Cargo / Função:</label>
                <input type="text" name="role" required placeholder="Ex: Sócio-Fundador & Arquiteto Principal" style="width: 100%; padding: 10px; border: 1px solid #ccc; font-size: 0.9rem;">
            </div>
        </div>

        <div style="margin-bottom: 20px;">
            <label style="display: block; font-size: 0.8rem; text-transform: uppercase; letter-spacing: 1px; margin-bottom: 8px;">Biografia / Resumo Profissional:</label>
            <textarea name="bio" rows="4" required style="width: 100%; padding: 10px; border: 1px solid #ccc; font-size: 0.9rem; font-family: inherit;"></textarea>
        </div>

        <div style="margin-bottom: 25px;">
            <label style="display: block; font-size: 0.8rem; text-transform: uppercase; letter-spacing: 1px; margin-bottom: 8px;">Foto do Colaborador (Vertical/Proporcional):</label>
            <input type="file" name="photo" accept="image/*" required style="font-size: 0.85rem;">
        </div>

        <button type="submit" style="background: #111; color: #fff; border: none; padding: 12px 28px; font-size: 0.8rem; text-transform: uppercase; letter-spacing: 1.5px; cursor: pointer;">
            CADASTRAR COLABORADOR
        </button>
    </form>

    <!-- LISTA DE COLABORADORES CADASTRADOS -->
    <h3 style="font-weight: 400; font-size: 1.2rem; margin-bottom: 20px; color: #111;">Membros Cadastrados</h3>
    
    <?php if (!empty($teamMembers)): ?>
        <div style="display: flex; flex-direction: column; gap: 20px;">
            <?php foreach ($teamMembers as $member): ?>
                <div style="display: flex; align-items: center; justify-content: space-between; background: #fff; padding: 15px; border: 1px solid #eee;">
                    <div style="display: flex; align-items: center; gap: 20px;">
                        <img src="<?= URL_BASE ?>/assets/uploads/<?= htmlspecialchars($member['photo']) ?>" style="width: 60px; height: 80px; object-fit: cover; display: block; background: #eee;">
                        <div>
                            <h4 style="margin: 0 0 5px 0; font-size: 1.1rem; font-weight: 500; color: #111;"><?= htmlspecialchars($member['name']) ?></h4>
                            <span style="font-size: 0.75rem; text-transform: uppercase; color: #777; letter-spacing: 1px;"><?= htmlspecialchars($member['role']) ?></span>
                        </div>
                    </div>
                    <div>
                        <a href="<?= URL_BASE ?>/admin/equipe/excluir/<?= $member['id'] ?>" onclick="return confirm('Deseja realmente excluir este colaborador?')" style="color: #c00; font-size: 0.8rem; text-decoration: none; text-transform: uppercase; letter-spacing: 1px;">Excluir</a>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php else: ?>
        <p style="color: #888; font-size: 0.9rem;">Nenhum colaborador cadastrado até o momento.</p>
    <?php endif; ?>

</main>

<?php require_once BASE_DIR . '/src/Views/partials/footer.php'; ?>