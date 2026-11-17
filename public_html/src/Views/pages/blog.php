<?php require_once BASE_DIR . '/src/Views/partials/header.php'; ?>

<!-- APLICADO PADDING-TOP DE 140PX PARA DESCOLAR DO HEADER FIXO -->
<main class="page-container internal-page-wrapper" style="max-width: 1140px; margin: 0 auto; padding: 140px 20px 60px 20px;">
    
    <!-- CABEÇALHO DO BLOG -->
    <header style="text-align: center; margin-bottom: 50px;">
        <h1 style="font-weight: 300; font-size: 2rem; color: #222; margin-bottom: 10px;">
            Artigos inspiradores e informativos sobre projetos residenciais modernos e personalizados.
        </h1>
        <p style="color: #666; font-size: 0.95rem;">Confira nossas últimas publicações abaixo.</p>
    </header>

    <!-- ESTRUTURA DE 2 COLUNAS (ARTIGOS + SIDEBAR LATERAL) -->
    <div style="display: grid; grid-template-columns: 1fr 300px; gap: 50px; align-items: start;">
        
        <!-- COLUNA DA ESQUERDA: LISTA DE POSTS -->
        <section class="yr-posts-list" style="display: flex; flex-direction: column; gap: 35px;">
            <?php if (!empty($posts)): ?>
                <?php foreach ($posts as $post): ?>
                    <article style="display: grid; grid-template-columns: 240px 1fr; gap: 20px; align-items: start;">
                        
                        <!-- IMAGEM COM TÍTULO OVERLAY -->
                        <a href="<?= URL_BASE ?>/blog/<?= htmlspecialchars($post['slug']) ?>" style="position: relative; display: block; overflow: hidden; background: #000;">
                            <img src="<?= URL_BASE ?>/assets/uploads/<?= htmlspecialchars($post['cover_image']) ?>" 
                                 alt="<?= htmlspecialchars($post['title']) ?>" 
                                 style="width: 100%; height: 180px; object-fit: cover; opacity: 0.85; display: block;">
                            <div style="position: absolute; bottom: 0; left: 0; right: 0; padding: 12px; background: linear-gradient(transparent, rgba(0,0,0,0.8));">
                                <h4 style="color: #fff; font-size: 0.85rem; margin: 0; font-weight: 500; line-height: 1.3;">
                                    <?= htmlspecialchars($post['title']) ?>
                                </h4>
                            </div>
                        </a>

                        <!-- TÍTULO E RESUMO -->
                        <div>
                            <h3 style="font-size: 1.15rem; font-weight: 400; margin: 0 0 10px 0; line-height: 1.3;">
                                <a href="<?= URL_BASE ?>/blog/<?= htmlspecialchars($post['slug']) ?>" style="color: #4a6b82; text-decoration: none;">
                                    <?= htmlspecialchars($post['title']) ?>
                                </a>
                            </h3>
                            <p style="font-size: 0.88rem; color: #666; line-height: 1.6; margin: 0;">
                                <?= htmlspecialchars($post['summary'] ?? substr(strip_tags($post['content']), 0, 180) . '...') ?>
                            </p>
                        </div>

                    </article>
                <?php endforeach; ?>
            <?php else: ?>
                <p style="color: #888;">Nenhum artigo encontrado.</p>
            <?php endif; ?>
        </section>

        <!-- COLUNA DA DIREITA: SIDEBAR LATERAL (YR ARCHITECTURE STYLE) -->
        <aside class="yr-sidebar" style="display: flex; flex-direction: column; gap: 35px;">
            
            <!-- 1. BARRA DE BUSCA -->
            <form action="<?= URL_BASE ?>/blog" method="GET" style="display: flex; border: 1px solid #ccc;">
                <input type="text" name="s" placeholder="Search..." value="<?= htmlspecialchars($_GET['s'] ?? '') ?>" style="border: none; padding: 10px 12px; font-size: 0.85rem; width: 100%; outline: none;">
                <button type="submit" style="background: #111; color: #fff; border: none; padding: 0 15px; cursor: pointer;">&#128269;</button>
            </form>

            <!-- 2. CHECKLIST INTERATIVO DE OBRA / REFORMA (DIAGNÓSTICO RÁPIDO) -->
            <div style="background: #f8f8f8; padding: 25px 20px; border: 1px solid #eee;">
                <h4 style="font-weight: 400; font-size: 0.95rem; text-transform: uppercase; letter-spacing: 1.5px; color: #111; margin: 0 0 8px 0; text-align: center;">
                    Checklist de Obra &amp; Reforma
                </h4>
                <p style="font-size: 0.8rem; color: #666; font-weight: 300; text-align: center; margin: 0 0 20px 0; line-height: 1.4;">
                    Selecione os itens desejados e receba um diagnóstico para o seu projeto:
                </p>
            
                <form id="checklist-form" onsubmit="openChecklistModal(event)">
                    <!-- TIPO DE PROJETO -->
                    <div style="margin-bottom: 15px;">
                        <label style="display: block; font-size: 0.75rem; text-transform: uppercase; font-weight: 600; color: #333; margin-bottom: 8px;">
                            Tipo de Intervenção
                        </label>
                        <select id="tipo_servico" name="tipo_servico" required style="width: 100%; padding: 8px; font-size: 0.8rem; border: 1px solid #ccc; background: #fff; color: #333;">
                            <option value="">Selecione uma opção...</option>
                            <option value="Construção do Zero">Construção Residencial do Zero</option>
                            <option value="Reforma de Interiores">Reforma de Interiores / Layout</option>
                            <option value="Projeto Comercial">Projeto Comercial / Arquitetura Corporativa</option>
                            <option value="Consultoria Arquitetônica">Consultoria de Iluminação / Acabamentos</option>
                        </select>
                    </div>
            
                    <!-- CHECKBOXES -->
                    <div style="margin-bottom: 20px; display: flex; flex-direction: column; gap: 8px;">
                        <label style="display: block; font-size: 0.75rem; text-transform: uppercase; font-weight: 600; color: #333;">
                            O que inclui no seu planejamento?
                        </label>
            
                        <label style="font-size: 0.8rem; color: #444; display: flex; align-items: center; gap: 8px; cursor: pointer;">
                            <input type="checkbox" class="chk-item" value="Projeto Arquitetônico Completo"> Projeto Arquitetônico Completo
                        </label>
                        <label style="font-size: 0.8rem; color: #444; display: flex; align-items: center; gap: 8px; cursor: pointer;">
                            <input type="checkbox" class="chk-item" value="Design de Interiores &amp; Marcenaria"> Design de Interiores &amp; Marcenaria
                        </label>
                        <label style="font-size: 0.8rem; color: #444; display: flex; align-items: center; gap: 8px; cursor: pointer;">
                            <input type="checkbox" class="chk-item" value="Projeto de Iluminação (Luminotécnico)"> Projeto de Iluminação
                        </label>
                        <label style="font-size: 0.8rem; color: #444; display: flex; align-items: center; gap: 8px; cursor: pointer;">
                            <input type="checkbox" class="chk-item" value="Acompanhamento e Gestão de Obra"> Gestão / Acompanhamento de Obra
                        </label>
                    </div>
            
                    <button type="submit" style="width: 100%; background: #111; color: #fff; padding: 12px; font-size: 0.75rem; text-transform: uppercase; letter-spacing: 1.5px; border: none; cursor: pointer; transition: background 0.3s ease;">
                        AVANÇAR E RECEBER DIAGNÓSTICO &rarr;
                    </button>
                </form>
            </div>
            
            <!-- MODAL DE CAPTURA DE DADOS DO CLIENTE -->
            <div id="modal-checklist" style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.6); z-index: 9999; justify-content: center; align-items: center;">
                <div style="background: #fff; max-width: 450px; width: 90%; padding: 30px; position: relative; box-shadow: 0 10px 30px rgba(0,0,0,0.2);">
                    <button onclick="closeChecklistModal()" style="position: absolute; top: 15px; right: 15px; background: none; border: none; font-size: 1.2rem; cursor: pointer;">&times;</button>
                    
                    <h3 style="font-weight: 300; font-size: 1.3rem; margin: 0 0 10px 0; color: #111;">Para onde enviamos o diagnóstico?</h3>
                    <p style="font-size: 0.85rem; color: #666; margin-bottom: 20px; line-height: 1.4;">
                        Preencha seus dados para receber o checklist detalhado por e-mail.
                    </p>
            
                    <form action="<?= URL_BASE ?>/blog/checklist/enviar" method="POST">
                        <!-- CAMPO HONEYPOT ANTI-SPAM (OCULTO PARA HUMANOS) -->
                        <div style="display:none !important;" aria-hidden="true">
                            <input type="text" name="website_hp" value="" tabindex="-1" autocomplete="off">
                        </div>

                        <!-- DADOS OCULTOS DO CHECKLIST -->
                        <input type="hidden" id="modal_tipo_servico" name="tipo_servico">
                        <input type="hidden" id="modal_itens_selecionados" name="itens_selecionados">
            
                        <div style="margin-bottom: 15px;">
                            <label style="display: block; font-size: 0.75rem; text-transform: uppercase; color: #333; margin-bottom: 5px;">Seu Nome *</label>
                            <input type="text" name="nome" required placeholder="Digite seu nome completo" style="width: 100%; padding: 10px; border: 1px solid #ccc; font-size: 0.85rem;">
                        </div>
            
                        <div style="margin-bottom: 15px;">
                            <label style="display: block; font-size: 0.75rem; text-transform: uppercase; color: #333; margin-bottom: 5px;">Seu E-mail *</label>
                            <input type="email" name="email" required placeholder="exemplo@email.com" style="width: 100%; padding: 10px; border: 1px solid #ccc; font-size: 0.85rem;">
                        </div>
            
                        <div style="margin-bottom: 20px;">
                            <label style="display: block; font-size: 0.75rem; text-transform: uppercase; color: #333; margin-bottom: 5px;">WhatsApp / Telefone (Opcional)</label>
                            <input type="tel" name="whatsapp" placeholder="(00) 00000-0000" style="width: 100%; padding: 10px; border: 1px solid #ccc; font-size: 0.85rem;">
                        </div>
            
                        <button type="submit" style="width: 100%; background: #111; color: #fff; padding: 12px; font-size: 0.8rem; text-transform: uppercase; letter-spacing: 1.5px; border: none; cursor: pointer;">
                            ENVIAR MEU CHECKLIST
                        </button>
                    </form>
                </div>
            </div>
            
            <script>
                function openChecklistModal(e) {
                    e.preventDefault();
                    const tipo = document.getElementById('tipo_servico').value;
                    const checkboxes = document.querySelectorAll('.chk-item:checked');
                    
                    let selectedItems = [];
                    checkboxes.forEach(item => selectedItems.push(item.value));
            
                    document.getElementById('modal_tipo_servico').value = tipo;
                    document.getElementById('modal_itens_selecionados').value = selectedItems.join(', ');
            
                    document.getElementById('modal-checklist').style.display = 'flex';
                }
            
                function closeChecklistModal() {
                    document.getElementById('modal-checklist').style.display = 'none';
                }
            </script>

            <!-- 3. ÁREA DE SUBSCRIÇÃO DE EMAIL -->
            <div style="background: #fafafa; border: 1px solid #eee; padding: 20px;">
                <h5 style="font-size: 0.85rem; font-weight: 600; color: #333; margin: 0 0 15px 0;">Subscribe to our email list</h5>
                
                <?php if (!empty($_SESSION['blog_notice'])): ?>
                    <p style="font-size: 0.8rem; color: #4a6b82; margin-bottom: 10px;">
                        <?= htmlspecialchars($_SESSION['blog_notice']); unset($_SESSION['blog_notice']); ?>
                    </p>
                <?php endif; ?>

                <form action="<?= URL_BASE ?>/blog/subcrever" method="POST" style="display: flex; flex-direction: column; gap: 10px;">
                    <!-- CAMPO HONEYPOT ANTI-SPAM (OCULTO PARA HUMANOS) -->
                    <div style="display:none !important;" aria-hidden="true">
                        <input type="text" name="website_hp" value="" tabindex="-1" autocomplete="off">
                    </div>

                    <input type="text" name="name" placeholder="Name" required style="border: 1px solid #ccc; padding: 8px; font-size: 0.8rem;">
                    <input type="email" name="email" placeholder="Email address" required style="border: 1px solid #ccc; padding: 8px; font-size: 0.8rem;">
                    <button type="submit" style="background: #4a6b82; color: #fff; border: none; padding: 10px; font-size: 0.8rem; cursor: pointer; text-transform: uppercase;">Subscribe</button>
                </form>
            </div>

            <!-- 4. CATEGORIAS -->
            <div>
                <h5 style="font-size: 0.85rem; font-weight: 600; color: #333; margin: 0 0 12px 0; border-bottom: 1px solid #eee; padding-bottom: 6px;">Categories</h5>
                <ul style="list-style: none; padding: 0; margin: 0; font-size: 0.85rem;">
                    <li style="margin-bottom: 8px;"><a href="<?= URL_BASE ?>/blog" style="color: #4a6b82; text-decoration: none;">&bull; Todas as Categorias</a></li>
                    <?php if (!empty($categories)): ?>
                        <?php foreach ($categories as $cat): ?>
                            <li style="margin-bottom: 8px;">
                                <a href="<?= URL_BASE ?>/blog?cat=<?= urlencode($cat) ?>" style="color: #4a6b82; text-decoration: none;">
                                    &bull; <?= htmlspecialchars($cat) ?>
                                </a>
                            </li>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </ul>
            </div>

            <!-- 5. ÁREA DE TAGS DINÂMICAS -->
            <div>
                <h5 style="font-size: 0.85rem; font-weight: 600; color: #333; margin: 0 0 12px 0; border-bottom: 1px solid #eee; padding-bottom: 6px;">Tags</h5>
                <div style="display: flex; flex-wrap: wrap; gap: 6px;">
                    <?php if (!empty($allTags)): ?>
                        <?php foreach ($allTags as $t): ?>
                            <a href="<?= URL_BASE ?>/blog?tag=<?= urlencode($t) ?>" 
                               style="border: 1px solid #ddd; padding: 4px 8px; font-size: 0.75rem; color: #666; text-decoration: none; transition: all 0.2s ease; display: inline-block;">
                                <?= htmlspecialchars($t) ?>
                            </a>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <span style="font-size: 0.75rem; color: #999;">Nenhuma tag cadastrada.</span>
                    <?php endif; ?>
                </div>
            </div>

        </aside>

    </div>

</main>

<?php require_once BASE_DIR . '/src/Views/partials/footer.php'; ?>