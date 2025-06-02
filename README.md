# BakeryHub-API

Uma API RESTful para gestão de padarias desenvolvida com Laravel, focando em autenticação robusta e gerenciamento de produtos.
A documentação foi atualizada para incluir o gerenciamento de produtos, incluindo upload e recuperação de imagens.

```markdown
# BakeryHub-API

Uma API RESTful para gestão de padarias desenvolvida com Laravel, com autenticação robusta, gerenciamento de usuários e produtos (incluindo upload de imagens).

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
git clone https://github.com/nickarab/baketrack-api.git
cd baketrack-api
composer install
npm install
cp .env.example .env
php artisan key:generate
# Configure o banco de dados e email no arquivo .env
php artisan migrate
php artisan storage:link
php artisan serve
```

## Configuração do Email

### 1. Credenciais do Mailtrap
Configure seu arquivo `.env` conforme exemplo anterior.

### 2. Verificação de Email
Após registrar um usuário, verifique o email recebido no Mailtrap.

## Endpoints

### 1. Registro de Usuário
```
POST /api/register
Content-Type: application/json
{
    "name": "Usuario Teste",
    "email": "usuario@teste.com",
    "password": "senha123"
}
```

### 2. Login
```
POST /api/login
Content-Type: application/json
{
    "email": "usuario@teste.com",
    "password": "senha123"
}
```

### 3. Logout
```
POST /api/logout
Authorization: Bearer {token}
```

### 4. Reenvio de Email de Verificação
```
POST /api/email/verification-notification
Content-Type: application/json
{
    "email": "usuario@teste.com"
}
```

### 5. Produtos (autenticado)

#### Listar produtos de um usuário
```
GET /api/user/{userId}/products
Authorization: Bearer {token}
```

#### Visualizar produto específico
```
GET /api/user/{userId}/products/{productId}
Authorization: Bearer {token}
```

#### Criar produto (com upload de imagem)
```
POST /api/user/{userId}/products
Authorization: Bearer {token}
Content-Type: multipart/form-data

Campos:
- name (string, obrigatório)
- description (string, opcional)
- price (decimal, obrigatório)
- stock (integer, opcional)
- is_active (boolean, opcional)
- image (arquivo, opcional)
```
**Resposta:**  
O campo `image_url` retorna a URL pública da imagem.

#### Atualizar produto
```
PUT /api/user/{userId}/products/{productId}
Authorization: Bearer {token}
Content-Type: multipart/form-data

Campos iguais ao POST (todos opcionais)
```

#### Deletar produto
```
DELETE /api/user/{userId}/products/{productId}
Authorization: Bearer {token}
```

## Observações

- Para upload de imagem, envie o campo `image` como arquivo.
- Rode `php artisan storage:link` para acesso público às imagens.
- O campo `image_url` estará disponível nas respostas dos produtos.
- Todos os endpoints de produto exigem autenticação via token.

## Códigos de Status
- 200: OK
- 201: Created
- 400: Bad Request
- 401: Unauthorized
- 404: Not Found
- 500: Internal Server Error

## Testes Automatizados
```bash
php artisan test
php artisan test --coverage
```

Desenvolvido por Manoel Barbosa
