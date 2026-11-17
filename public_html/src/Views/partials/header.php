<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $title ?? 'Autá AD — ARQUITETURA + DESIGN' ?></title>
    
    <!-- Fonte Minimalista -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@300;400;500&display=swap" rel="stylesheet">
    
    <link rel="stylesheet" href="<?= URL_BASE ?>/assets/css/style.css">
</head>
<body>

<header class="site-header">
    <div class="header-container">
        <!-- TÍTULO / LOGO DA MARCA -->
        <div class="site-branding">
            <a href="<?= URL_BASE ?>">AUTÁ ARQUITETURA + DESIGN</a>
        </div>

        <!-- MENU DE NAVEGAÇÃO -->
        <nav class="main-nav">
            <ul>
                <li><a href="<?= URL_BASE ?>/">Home</a></li>
                <li><a href="<?= URL_BASE ?>/projetos">Portfólio</a></li>
                <li><a href="<?= URL_BASE ?>/sobre">Nós</a></li>
                <li><a href="<?= URL_BASE ?>/blog">Blog</a></li>
                <li><a href="<?= URL_BASE ?>/contato">Contato</a></li>
            </ul>
        </nav>
    </div>
</header>

<script>
document.addEventListener('scroll', function() {
    const header = document.querySelector('.site-header');
    if (window.scrollY > 20) {
        header.classList.add('scrolled');
    } else {
        header.classList.remove('scrolled');
    }
});
</script>