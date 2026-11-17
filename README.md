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
