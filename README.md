# 💰 Carteira Financeira Laravel

Este é um projeto de teste desenvolvido com Laravel + Breeze com o objetivo de gerenciar uma **carteira financeira** pessoal, onde usuários podem se registrar, autenticar e organizar suas finanças.

---

## ✅ Funcionalidades

- Registro e login com Laravel Breeze
- Cadastro de usuários com dados adicionais:
  - Data de nascimento
  - Telefone
  - Endereço
- Validação completa de dados
- Middleware de autenticação
- Dashboard de usuário
- Observabilidade com **Log Viewer** e **Laravel Telescope**

---

## 🔧 Tecnologias e Ferramentas

- Laravel 11.x
- Laravel Breeze (Blade)
- PHP 8.2+
- Laravel Telescope (Observabilidade)
- Laravel Log Viewer (Visualização de Logs)
- Tailwind CSS (Frontend)

---

## 📦 Instalação

```bash
git clone https://github.com/seu-usuario/carteira-financeira-laravel.git
cd carteira-financeira-laravel

# Instale as dependências
composer install
npm install && npm run dev

# Copie o arquivo .env e configure as variáveis
cp .env.example .env

# Gere a chave da aplicação
php artisan key:generate

# Execute as migrations
php artisan migrate

# (Opcional) Popule com dados de teste
php artisan db:seed
```

---

## 🧭 Observabilidade

### 🔍 Laravel Log Viewer

Interface web para leitura e análise de logs da aplicação.

- **URL:** `/log-viewer`
- **Pacote:** [`opcodesio/log-viewer`](https://github.com/opcodesio/log-viewer)

### 🚀 Laravel Telescope

Ferramenta oficial do Laravel para inspeção de:

- Requisições HTTP
- Exceções
- Jobs
- Consultas SQL
- E mais...

- **URL:** `/telescope`
- **Pacote:** [`laravel/telescope`](https://laravel.com/docs/telescope)

> Recomendado somente para ambientes de desenvolvimento.

---

## 🔐 Autenticação

O projeto utiliza **Laravel Breeze** com Blade como motor de templates, já com rotas e views de login, registro, redefinição de senha e verificação de e-mail.

---

## ✍️ Licença

Este projeto é open-source e está sob a licença [MIT](LICENSE).
