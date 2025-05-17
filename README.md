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
- Autenticação em dois fatores (2FA)
- **Notificações personalizadas**:
  - Verificação de e-mail
  - Envio de código 2FA
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
# Clonar o repositório
git clone https://github.com/seu-usuario/seu-projeto.git
cd seu-projeto

# Instalar dependências
composer install

# Copiar .env e configurar
docker cp .env.example .env
php artisan key:generate

# Rodar migrações
docker-compose exec app php artisan migrate

# (Opcional) Popular o banco com dados de exemplo
docker-compose exec app php artisan db:seed
```

---

## 🧪 Testes

Para rodar os testes, use o comando abaixo:

```bash
php artisan test
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

Além disso, conta com **autenticação em dois fatores (2FA)** e **notificações personalizadas** para envio do código de autenticação e verificação de e-mail.

---

## ✍️ Licença

Este projeto é open-source e está sob a licença [MIT](LICENSE).
