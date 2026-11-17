# 🏛️ Autá Arquitetura & Design — Plataforma Web Full-Stack

![PHP](https://img.shields.io/badge/PHP-777BB4?style=for-the-badge&logo=php&logoColor=white)
![MySQL](https://img.shields.io/badge/MySQL-005C84?style=for-the-badge&logo=mysql&logoColor=white)
![HTML5](https://img.shields.io/badge/HTML5-E34F26?style=for-the-badge&logo=html5&logoColor=white)
![CSS3](https://img.shields.io/badge/CSS3-1572B6?style=for-the-badge&logo=css3&logoColor=white)
![JavaScript](https://img.shields.io/badge/JavaScript-F7DF1E?style=for-the-badge&logo=javascript&logoColor=black)

Plataforma web institucional e sistema de gerenciamento de conteúdo (CMS) desenvolvido sob medida para escritório de arquitetura e design. A aplicação integra apresentação de portfólio, blog dinâmico, captação automatizada de leads e painel administrativo protegido para gestão completa sem intervenção no código-fonte.

🔗 **Acesse em produção:** [www.autaarquitetura.com.br](https://www.autaarquitetura.com.br)

---

## 🏗️ Arquitetura do Sistema

O projeto foi estruturado seguindo o padrão de **separação de responsabilidades (MVC simplificado)**, garantindo organização, segurança e facilidade de manutenção:

```text
public_html/
├── assets/          # Recursos estáticos (CSS, JS, uploads)
├── config/          # Configurações de ambiente e conexão com banco de dados
├── public/          # Ponto de entrada público e arquivos expostos
└── src/
    ├── Controllers/ # Regras de negócio, controle de rotas e fluxo de dados
    ├── Models/      # Camada de persistência e consultas SQL
    ├── Views/       # Interfaces públicas e telas do painel administrativo
    └── vendor/      # Bibliotecas e dependências externas (PHPMailer) 


## ⚙️ Funcionalidades Principais

* **🔐 Autenticação e Painel Administrativo (`CMS`):** Área restrita com controle de sessão (`admin-login.php`, `admin-dashboard.php`) para gestão interna da plataforma.
* **📁 Gestão Dinâmica de Portfólio e Blog (`CRUD`):** Criação, edição, publicação e exclusão de projetos arquitetônicos, artigos de blog, banners principais (*hero*) e membros da equipe.
* **🗄️ Persistência em Banco de Dados Relacional:** Estruturação de tabelas em SQL para armazenamento seguro de projetos, postagens, leads e métricas de enquetes.
* **✉️ Automação de E-mails e Anti-Spam:** Sistema de contato integrado via **PHPMailer** para disparos automatizados, aliado a validações de segurança contra bots e spam.
* **📱 Interface Responsiva (UI/UX):** Layout projetado com foco em hierarquia visual, tipografia limpa e fluidez de navegação em dispositivos móveis e desktops.

---

## 🛡️ Boas Práticas Aplicadas

* **Separação entre Lógica e Apresentação:** Isolamento de consultas SQL na camada `Models` e renderização visual na camada `Views`.
* **Segurança de Credenciais:** Arquivos sensíveis de configuração desacoplados da camada pública de visualização.
* **Código Limpo e Modular:** Reutilização de componentes visuais (`partials`) para cabeçalhos, rodapés e menus.

---

## 👨‍💻 Autor

Desenvolvido e arquitetado por **Ricardo** — *Arquiteto & Desenvolvedor Full-Stack*.    
