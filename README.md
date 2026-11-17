# Plataforma Institucional e Painel Administrativo - Autá Arquitetura

Sistema Web Full-Stack desenvolvido sob medida para gestão de portfólio de arquitetura, contendo área administrativa protegida, sistema dinâmico de postagens/projetos, integração com banco de dados SQL e disparos automatizados de e-mails.

## Tecnologias Utilizadas
* **PHP Nativo (Arquitetura em Camadas / MVC)**
* **Banco de Dados Relacional (SQL)**
* **PHPMailer** (para automação e envio de e-mails de contato e formulários)
* **HTML5, CSS3, JavaScript** (Front-end responsivo)

## Arquitetura do Projeto
A aplicação foi estruturada separando a lógica de negócio, controladores, modelos e views:
- `/src/Controllers`: Lógica de rotas e processamento de dados.
- `/src/Models`: Camada de comunicação e persistência com o banco SQL.
- `/src/Views/pages`: Páginas públicas e administrativas (Login, Dashboard, Gestão de Projetos e Blog).
- `/src/vendor`: Gerenciamento de dependências externas (PHPMailer).

## Principais Funcionalidades
- **Painel Administrativo Seguro:** Autenticação de administradores para gerenciar conteúdos do site sem precisar alterar código-fonte.
- **Gerenciamento de Conteúdo (CRUD):** Criação, edição e listagem dinâmica de projetos de arquitetura e posts de blog (`admin-create.php`, `admin-post-create.php`).
- **Automação de E-mails:** Envio automatizado de mensagens de contato utilizando PHPMailer integrado ao servidor.
- **Persistência SQL:** Armazenamento seguro de dados institucionais, enquetes e leads capturados.
