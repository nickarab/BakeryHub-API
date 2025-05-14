# BakeryHub-API

Uma API RESTful para gestão de padarias desenvolvida com Laravel, focando em autenticação robusta e gerenciamento de produtos.

## Requisitos
- PHP 8.1+
- Composer
- NPM
- MySQL/MariaDB
- Laravel 10.x
- Postman (para testes)
- Conta no Mailtrap (para testes de email)

## Instalação

```bash
# Clone o repositório
git clone https://github.com/nickarab/baketrack-api.git

# Entre na pasta do projeto
cd baketrack-api

# Instale as dependências
composer install
npm install

# Configure o ambiente
cp .env.example .env
php artisan key:generate

# Configure o banco de dados e email no arquivo .env
# Execute as migrações
php artisan migrate

# Inicie o servidor
php artisan serve
```

## Configuração do Email

### 1. Credenciais do Mailtrap
Configure seu arquivo `.env` com:
```dotenv
MAIL_MAILER=smtp
MAIL_SCHEME=null
MAIL_HOST=sandbox.smtp.mailtrap.io
MAIL_PORT=2525
MAIL_ENCRYPTION=tls
MAIL_USERNAME=2b1922562003bb
MAIL_PASSWORD=828f03ff1460da
MAIL_FROM_ADDRESS="no-reply@yourapp.com"
MAIL_FROM_NAME="${APP_NAME}"
```

### 2. Verificação de Email
Após registrar um usuário:
- O sistema envia automaticamente um email de verificação
- Acesse [mailtrap.io](https://mailtrap.io) e verifique sua inbox
- Clique no link de verificação recebido
- O email será marcado como verificado no sistema

## Endpoints e Como Testar (Postman)

### 1. Registro de Usuário
```
POST http://localhost:8000/api/register
Content-Type: application/json

{
    "name": "Usuario Teste",
    "email": "usuario@teste.com",
    "password": "senha123",
    "password_confirmation": "senha123"
}
```

**Resposta Esperada**:
```json
{
    "success": true,
    "message": "User registered successfully",
    "status": 201
}
```

### 2. Login
```
POST http://localhost:8000/api/login
Content-Type: application/json

{
    "email": "usuario@teste.com",
    "password": "senha123"
}
```

**Resposta Esperada**:
```json
{
    "success": true,
    "message": "Login successful",
    "status": 200,
    "token": "seu_token_aqui"
}
```

### 3. Logout
```
POST http://localhost:8000/api/logout
Authorization: Bearer {token}
```

**Resposta Esperada**:
```json
{
    "success": true,
    "message": "Logout successfully",
    "status": 200
}
```

### 4. Reenvio de Email de Verificação
```
POST http://localhost:8000/api/email/verification-notification
Content-Type: application/json

{
    "email": "usuario@teste.com"
}
```

**Resposta Esperada**:
```json
{
    "success": true,
    "message": "Email verification has been sent",
    "status": 200
}
```

## Como Testar no Postman

1. **Configuração Inicial**
   - Crie uma nova coleção no Postman chamada "BakeTrack API"
   - Configure uma variável de ambiente chamada `base_url` com valor `http://localhost:8000/api`
   - Configure uma variável `token` para armazenar o token de autenticação

2. **Fluxo de Testes**
   - Registre um novo usuário
   - Faça login e salve o token retornado na variável de ambiente
   - Use o token nas requisições autenticadas
   - Teste o logout
   - Teste o reenvio de verificação de email

3. **Headers Comuns**
   ```
   Content-Type: application/json
   Accept: application/json
   Authorization: Bearer {{token}} (para rotas autenticadas)
   ```

## Códigos de Status
- 200: OK
- 201: Created
- 400: Bad Request
- 401: Unauthorized
- 404: Not Found
- 500: Internal Server Error

## Possíveis Erros

1. **Registro**
   - Email já existe
   - Senhas não conferem
   - Campos obrigatórios faltando

2. **Login**
   - Credenciais inválidas
   - Email não verificado

3. **Verificação de Email**
   - Email não encontrado
   - Email já verificado

## Logs e Debug
- Logs disponíveis em `storage/logs/laravel.log`
- Use o Postman Console para debug das requisições
- Verifique o Laravel Log para erros do servidor

## Testes Automatizados
```bash
# Executar testes
php artisan test

# Executar testes com cobertura
php artisan test --coverage
```

---
Desenvolvido por Manoel Barbosa
