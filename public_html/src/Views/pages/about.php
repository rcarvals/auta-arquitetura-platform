<?php require_once BASE_DIR . '/src/Views/partials/header.php'; ?>

<main class="page-container internal-page-wrapper" style="max-width: 1200px; margin: 0 auto; padding-top: 140px; padding-bottom: 100px; padding-left: 30px; padding-right: 30px;">
    
    <!-- SEÇÃO INSTITUCIONAL EXISTENTE -->
    <section class="about-hero-section" style="margin-bottom: 100px;">
        <span style="font-size: 0.75rem; text-transform: uppercase; letter-spacing: 2px; color: #888; display: block; margin-bottom: 15px;">
            SOBRE A AUTÁ AD
        </span>
        <h1 style="font-weight: 300; font-size: 2.8rem; color: #111; margin: 0 0 50px 0; line-height: 1.2;">
            Arquitetura que pertence ao lugar!
        </h1>

        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 60px; align-items: start;">
            <div style="font-size: 1.05rem; line-height: 1.8; color: #444; font-weight: 300; display: flex; flex-direction: column; gap: 20px;">
                <p>A Autá AD é um studio de arquitetura e design pautado pela observação atenta, decisões conscientes e pela busca constante da atemporalidade.</p>
                <p>Acreditamos que a boa arquitetura não se impõe, mas emerge da compreensão profunda do lugar, da luz natural e das necessidades de quem vai habitar o espaço. Cada projeto é tratado como uma obra única, aliando rigor técnico, sensibilidade estética e seleção criteriosa de materiais nobres.</p>
                <p>Projetamos espaços contemporâneos a partir da leitura do território, da luz e das formas de viver. Cada projeto nasce de uma relação particular entre matéria, tempo e paisagem.</p>
            </div>
            <div>
                <img src="<?= URL_BASE ?>/assets/uploads/capa-home.jpg" alt="Autá Arquitetura + Design" style="width: 100%; height: auto; display: block; object-fit: cover;">
            </div>
        </div>
    </section>

    <hr style="border: none; border-top: 1px solid #eee; margin: 80px 0;">

<!-- SEÇÃO EQUIPE / COLABORADORES DINÂMICA -->
    <?php
        global $pdo;
        $teamMembers = [];
        if (isset($pdo)) {
            try {
                $stmtTeam = $pdo->query("SELECT * FROM team_members ORDER BY order_index ASC, id ASC");
                if ($stmtTeam) {
                    $teamMembers = $stmtTeam->fetchAll(PDO::FETCH_ASSOC);
                }
            } catch (\PDOException $e) {}
        }
    ?>

    <?php if (!empty($teamMembers)): ?>
        <section class="team-section">
            <span style="font-size: 0.75rem; text-transform: uppercase; letter-spacing: 2px; color: #888; display: block; margin-bottom: 15px;">
                EQUIPE & AUTORIA
            </span>
            <h1 style="font-weight: 300; font-size: 2.2rem; color: #111; margin: 0 0 60px 0;">
                Conheça nossa equipe!
            </h1>

            <div class="team-grid" style="display: flex; flex-direction: column; gap: 70px;">
                <?php foreach ($teamMembers as $member): ?>
                    <article class="team-member-card" style="display: grid; grid-template-columns: 380px 1fr; gap: 50px; align-items: center;">
                        <!-- Coluna Imagem -->
                        <div class="member-image" style="width: 100%; aspect-ratio: 3/4; overflow: hidden; background: #f4f4f4;">
                            <img src="<?= URL_BASE ?>/assets/uploads/<?= htmlspecialchars($member['photo']) ?>" 
                                 alt="<?= htmlspecialchars($member['name']) ?>" 
                                 style="width: 100%; height: 100%; object-fit: cover; filter: grayscale(100%); transition: filter 0.4s ease;"
                                 onmouseover="this.style.filter='grayscale(0%)'" 
                                 onmouseout="this.style.filter='grayscale(100%)'"
                                 onerror="this.src='<?= URL_BASE ?>/assets/uploads/capa-home.jpg';">
                        </div>
                        <!-- Coluna Texto -->
                        <div class="member-info" style="display: flex; flex-direction: column; justify-content: center;">
                            <h3 style="font-family: 'Times New Roman', Georgia, serif; font-size: 1.8rem; font-weight: 400; color: #111; margin: 0 0 8px 0;">
                                <?= htmlspecialchars($member['name']) ?>
                            </h3>
                            <span style="font-size: 0.75rem; text-transform: uppercase; letter-spacing: 2px; color: #777; margin-bottom: 20px; display: block;">
                                <?= htmlspecialchars($member['role']) ?>
                            </span>
                            <p style="font-size: 1rem; line-height: 1.8; color: #444; font-weight: 300; margin: 0;">
                                <?= nl2br(htmlspecialchars($member['bio'])) ?>
                            </p>
                        </div>
                    </article>
                <?php endforeach; ?>
            </div>
        </section>
    <?php endif; ?>

</main>

<style>
    @media (max-width: 868px) {
        .about-hero-section > div,
        .team-member-card {
            grid-template-columns: 1fr !important;
            gap: 30px !important;
        }
        .member-image {
            aspect-ratio: 4/3 !important;
        }
    }
</style>

<?php require_once BASE_DIR . '/src/Views/partials/footer.php'; ?>
