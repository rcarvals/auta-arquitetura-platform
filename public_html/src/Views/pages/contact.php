<?php require_once BASE_DIR . '/src/Views/partials/header.php'; ?>

<main class="page-container internal-page-wrapper">
    <section class="contact-section">
        <div class="contact-header">
            <span class="section-subtitle">Contact</span>
            <h2>Vamos conversar sobre seu próximo projeto?</h2>
            <p>Estamos disponíveis para novos projetos, consultorias e parcerias.</p>
        </div>

        <div class="contact-grid">
            <!-- INFORMAÇÕES DE CONTATO -->
            <div class="contact-info">
                <div class="info-block">
                    <h4>Localização</h4>
                    <p>Curitiba, PR — Brasil</p>
                </div>
                <div class="info-block">
                    <h4>E-mail</h4>
                    <p><a href="mailto:contato@autaarquitetura.com.br">contato@autaarquitetura.com.br</a></p>
                </div>
                
                <!-- REDES SOCIAIS -->
                <div class="info-block">
                    <h4>Redes Sociais</h4>
                    <p>
                        <a href="https://www.instagram.com/autaarquitetura" target="_blank">Instagram</a> / 
                        <a href="https://www.linkedin.com/company/autaarquitetura" target="_blank">LinkedIn</a> / 
                        <a href="https://www.behance.net/autaarquitetura" target="_blank">Behance</a>
                    </p>
                </div>
            </div>

            <!-- FORMULÁRIO -->
            <div class="contact-form-wrapper">
                <!-- MENSAGEM DE CONFIRMAÇÃO PARA O CLIENTE -->
                <?php if (!empty($_SESSION['contact_success'])): ?>
                    <div style="padding: 15px; background-color: #d4edda; color: #155724; border: 1px solid #c3e6cb; border-radius: 4px; margin-bottom: 20px;">
                        <?= htmlspecialchars($_SESSION['contact_success']); ?>
                    </div>
                    <?php unset($_SESSION['contact_success']); ?>
                <?php endif; ?>
                
                <?php if (!empty($_SESSION['contact_error'])): ?>
                    <div style="padding: 15px; background-color: #f8d7da; color: #721c24; border: 1px solid #f5c6cb; border-radius: 4px; margin-bottom: 20px;">
                        <?= htmlspecialchars($_SESSION['contact_error']); ?>
                    </div>
                    <?php unset($_SESSION['contact_error']); ?>
                <?php endif; ?>
                
                <form action="<?= URL_BASE ?>/contato/enviar" method="POST" class="minimal-form">
                    
                    <!-- CAMPO HONEYPOT ANTI-SPAM (OCULTO PARA HUMANOS) -->
                    <div style="display:none !important;" aria-hidden="true">
                        <input type="text" name="website_hp" value="" tabindex="-1" autocomplete="off">
                    </div>
                    
                    <!-- Campo armadilha para bots (invisível para humanos) -->
                    <div style="display: none !important;">
                        <input type="text" name="website_url" tabindex="-1" autocomplete="off">
                    </div>

                    <div class="form-group">
                        <label for="name">Nome</label>
                        <input type="text" id="name" name="name" required placeholder="Seu nome completo">
                    </div>
                    <div class="form-group">
                        <label for="email">E-mail</label>
                        <input type="email" id="email" name="email" required placeholder="seu@email.com">
                    </div>
                    <div class="form-group">
                        <label for="message">Mensagem</label>
                        <textarea id="message" name="message" rows="5" required placeholder="Conte-nos um pouco sobre o seu projeto..."></textarea>
                    </div>
                    <button type="submit" class="btn-line-link">Enviar Mensagem &rarr;</button>
                </form>
            </div>
        </div>
    </section>
</main>

<?php require_once BASE_DIR . '/src/Views/partials/footer.php'; ?>