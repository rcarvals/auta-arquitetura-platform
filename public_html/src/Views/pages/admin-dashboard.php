<?php require_once BASE_DIR . '/src/Views/partials/header.php'; ?>

<main class="page-container internal-page-wrapper" style="max-width: 1200px; margin: 0 auto; padding-top: 140px; padding-bottom: 100px; padding-left: 30px; padding-right: 30px;">

    <!-- CABEÇALHO COM APARÊNCIA ORIGINAL -->
    <div style="display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 60px; border-bottom: 1px solid #eee; padding-bottom: 25px;">
        <div>
            <span style="font-size: 0.75rem; text-transform: uppercase; letter-spacing: 2px; color: #888; display: block; margin-bottom: 10px;">
                PAINEL DE CONTROLE
            </span>
            <h1 style="font-family: 'Times New Roman', Georgia, serif; font-weight: 400; font-size: 2.8rem; color: #111; margin: 0; line-height: 1;">
                Visão Geral
            </h1>
        </div>

        <div style="display: flex; gap: 15px; align-items: center;">
            <a href="<?= URL_BASE ?>/admin/hero" style="background: #2b2b2b; color: #fff; text-decoration: none; font-size: 0.75rem; text-transform: uppercase; letter-spacing: 1.5px; padding: 12px 20px; display: inline-block;">
                + CAPA DA HOMEPAGE
            </a>
            <a href="<?= URL_BASE ?>/admin/equipe" style="background: #2b2b2b; color: #fff; text-decoration: none; font-size: 0.75rem; text-transform: uppercase; letter-spacing: 1.5px; padding: 12px 20px; display: inline-block;">
                + EQUIPE / COLABORADORES
            </a>
            <a href="<?= URL_BASE ?>/" target="_blank" style="color: #666; font-size: 0.75rem; text-transform: uppercase; letter-spacing: 1.5px; text-decoration: none; margin-left: 10px;">
                VER SITE &rarr;
            </a>
            <a href="<?= URL_BASE ?>/admin/logout" style="color: #d9534f; font-size: 0.75rem; text-transform: uppercase; letter-spacing: 1.5px; text-decoration: none; margin-left: 10px;">
                SAIR
            </a>
        </div>
    </div>

    <!-- MENSAGENS DE NOTIFICAÇÃO -->
    <?php if (!empty($_SESSION['admin_success'])): ?>
        <div style="padding: 15px; background: #d4edda; color: #155724; border: 1px solid #c3e6cb; margin-bottom: 30px; font-size: 0.9rem;">
            <?= $_SESSION['admin_success']; unset($_SESSION['admin_success']); ?>
        </div>
    <?php endif; ?>

    <?php if (!empty($_SESSION['admin_error'])): ?>
        <div style="padding: 15px; background: #f8d7da; color: #721c24; border: 1px solid #f5c6cb; margin-bottom: 30px; font-size: 0.9rem;">
            <?= $_SESSION['admin_error']; unset($_SESSION['admin_error']); ?>
        </div>
    <?php endif; ?>

    <!-- SEÇÃO GERENCIAR PROJETOS -->
    <section style="margin-bottom: 70px;">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 25px;">
            <h2 style="font-weight: 300; font-size: 1.8rem; color: #111; margin: 0;">
                Gerenciar Projetos
            </h2>
            <a href="<?= URL_BASE ?>/admin/novo" style="background: #111; color: #fff; text-decoration: none; font-size: 0.75rem; text-transform: uppercase; letter-spacing: 1.5px; padding: 12px 24px; display: inline-block;">
                + NOVO PROJETO
            </a>
        </div>

        <table style="width: 100%; border-collapse: collapse; text-align: left;">
            <thead>
                <tr style="border-bottom: 1px solid #111; font-size: 0.75rem; text-transform: uppercase; letter-spacing: 1.5px; color: #666;">
                    <th style="padding: 12px 10px; width: 60px;">ID</th>
                    <th style="padding: 12px 10px;">TÍTULO</th>
                    <th style="padding: 12px 10px;">CATEGORIA</th>
                    <th style="padding: 12px 10px;">ANO</th>
                    <th style="padding: 12px 10px; text-align: right;">AÇÕES</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($projects)): ?>
                    <?php foreach ($projects as $project): ?>
                        <tr style="border-bottom: 1px solid #eee; font-size: 0.9rem; color: #333;">
                            <td style="padding: 15px 10px; color: #888;"><?= htmlspecialchars($project['id']) ?></td>
                            <td style="padding: 15px 10px; font-weight: 500;"><?= htmlspecialchars($project['title']) ?></td>
                            <td style="padding: 15px 10px; color: #666;"><?= htmlspecialchars($project['category_name'] ?? '—') ?></td>
                            <td style="padding: 15px 10px; color: #666;"><?= htmlspecialchars($project['year'] ?? '—') ?></td>
                            <td style="padding: 15px 10px; text-align: right;">
                                <a href="<?= URL_BASE ?>/projeto/<?= $project['slug'] ?>" target="_blank" style="color: #666; text-decoration: none; margin-right: 15px; font-size: 0.8rem; text-transform: uppercase; letter-spacing: 1px;">Ver</a>
                                <a href="<?= URL_BASE ?>/admin/excluir/<?= $project['id'] ?>" onclick="return confirm('Tem certeza que deseja excluir este projeto?')" style="color: #c00; text-decoration: none; font-size: 0.8rem; text-transform: uppercase; letter-spacing: 1px;">Excluir</a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="5" style="padding: 30px; text-align: center; color: #888; font-size: 0.9rem;">
                            Nenhum projeto cadastrado.
                        </td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </section>

    <!-- SEÇÃO GERENCIAR ARTIGOS DO BLOG -->
    <section>
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 25px;">
            <h2 style="font-weight: 300; font-size: 1.8rem; color: #111; margin: 0;">
                Gerenciar Artigos (Blog)
            </h2>
            <a href="<?= URL_BASE ?>/admin/blog/novo" style="background: #2b2b2b; color: #fff; text-decoration: none; font-size: 0.75rem; text-transform: uppercase; letter-spacing: 1.5px; padding: 12px 24px; display: inline-block;">
                + NOVO ARTIGO
            </a>
        </div>

        <table style="width: 100%; border-collapse: collapse; text-align: left;">
            <thead>
                <tr style="border-bottom: 1px solid #111; font-size: 0.75rem; text-transform: uppercase; letter-spacing: 1.5px; color: #666;">
                    <th style="padding: 12px 10px; width: 60px;">ID</th>
                    <th style="padding: 12px 10px;">TÍTULO</th>
                    <th style="padding: 12px 10px;">CATEGORIA</th>
                    <th style="padding: 12px 10px;">DATA</th>
                    <th style="padding: 12px 10px; text-align: right;">AÇÕES</th>
                </tr>
            </thead>
            <tbody>
                <?php 
                    global $pdo;
                    $posts = [];
                    if (isset($pdo)) {
                        try {
                            $stmtPosts = $pdo->query("SELECT * FROM posts ORDER BY id DESC");
                            if ($stmtPosts) { $posts = $stmtPosts->fetchAll(PDO::FETCH_ASSOC); }
                        } catch (\PDOException $e) {}
                    }
                ?>
                <?php if (!empty($posts)): ?>
                    <?php foreach ($posts as $post): ?>
                        <tr style="border-bottom: 1px solid #eee; font-size: 0.9rem; color: #333;">
                            <td style="padding: 15px 10px; color: #888;"><?= htmlspecialchars($post['id']) ?></td>
                            <td style="padding: 15px 10px; font-weight: 500;"><?= htmlspecialchars($post['title']) ?></td>
                            <td style="padding: 15px 10px; color: #666;"><?= htmlspecialchars($post['category'] ?? 'Geral') ?></td>
                            <td style="padding: 15px 10px; color: #666;"><?= !empty($post['created_at']) ? date('d/m/Y', strtotime($post['created_at'])) : '—' ?></td>
                            <td style="padding: 15px 10px; text-align: right;">
                                <a href="<?= URL_BASE ?>/admin/blog/disparar/<?= $post['id'] ?>" onclick="return confirm('Deseja disparar este artigo por e-mail para todos os leitores cadastrados?')" style="color: #4a6b82; text-decoration: none; margin-right: 15px; font-size: 0.8rem; text-transform: uppercase; letter-spacing: 1px;">Disparar E-mail</a>
                                <a href="<?= URL_BASE ?>/blog/<?= $post['slug'] ?>" target="_blank" style="color: #666; text-decoration: none; margin-right: 15px; font-size: 0.8rem; text-transform: uppercase; letter-spacing: 1px;">Ver</a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="5" style="padding: 30px; text-align: center; color: #888; font-size: 0.9rem;">
                            Nenhum artigo publicado no blog.
                        </td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </section>

</main>

<?php require_once BASE_DIR . '/src/Views/partials/footer.php'; ?>