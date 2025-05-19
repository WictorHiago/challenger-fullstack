# challenger-fullstack
Sistema de Gerenciamento de Produtos desenvolvido como desafio. O projeto fullstack utiliza Vue 3 + TypeScript no front-end e Laravel 10 + MySQL no back-end.

## Instalação do projeto

### Crie o container docker com banco de dados MySQL
```
docker-compose up -d
```

### Comandos de instalação
Você deve fazer um split do terminal ou usar duas janelas de terminais. Um para o backend e outro para o frontend.

### backend

```
# no diretorio raiz
composer install
```

```
# no diretorio do backend
cd laravel
php artisan migrate
php artisan db:seed
```

### frontend

```
# no diretorio frontend
npm install
npm run dev
```

## Documentação da API

### Controle de Acesso

A API implementa controle de acesso baseado em roles:

- **Usuários com role "user"** podem:
  - Listar produtos e categorias
  - Ver detalhes de produtos e categorias
  - Ver seu próprio perfil

- **Usuários com role "admin"** podem:
  - Realizar todas as operações que um usuário comum pode
  - Gerenciar usuários (listar, ver detalhes, editar, excluir)
  - Criar, editar e excluir produtos
  - Criar, editar e excluir categorias

### Autenticação

#### Register
```
POST http://localhost:8000/api/register

Body
{
  "name": "oliver souza",
  "email": "oliver.souza@gmail.com",
  "role": "admin",  // "admin" ou "user"
  "password": "admin@admin",
  "password_confirmation": "admin@admin"
}

Response
{
  "message": "User registered successfully",
  "user": {
    "name": "oliver souza",
    "email": "oliver.souza@gmail.com",
    "role": "admin"
  },
  "token": "1|laravel_sanctum_token..."
}
```

#### Login
```
POST http://localhost:8000/api/login

Body
{
  "email": "oliver.souza@gmail.com",
  "password": "admin@admin"
}

Response
{
  "user": {
    "id": 1,
    "name": "oliver souza",
    "email": "oliver.souza@gmail.com",
    "role": "admin"
  },
  "token": "1|laravel_sanctum_token..."
}
```

#### Logout
```
POST http://localhost:8000/api/logout

Headers
Authorization: Bearer {token}

Response
{
  "message": "Logout realizado com sucesso"
}
```

#### Get User Profile
```
GET http://localhost:8000/api/profile

Headers
Authorization: Bearer {token}

Response
{
  "user": {
    "id": 1,
    "name": "John Doe",
    "email": "john@example.com",
    "role": "user",
    "created_at": "2025-05-17T14:00:00.000000Z",
    "updated_at": "2025-05-17T14:00:00.000000Z"
  },
  "token": "1|abcdefghijklmnopqrstuvwxyz1234567890"
}
```

### Produtos

#### Listar Produtos (com paginação) [user, admin]
```
GET http://localhost:8000/api/products

Headers
Authorization: Bearer {token}

Response
{
  "products": [
    {
      "id": 1,
      "name": "Produto Exemplo",
      "description": "Descrição do produto exemplo",
      "price": "99.99",
      "image_url": "https://exemplo.com/images/produto.jpg",
      "category_id": 1,
      "created_at": "2025-05-17T14:00:00.000000Z",
      "updated_at": "2025-05-17T14:00:00.000000Z"
    }
  ]
}
```

#### Buscar Produtos [user, admin]
```
GET http://localhost:8000/api/products/search/{termo}

Headers
Authorization: Bearer {token}

Exemplo: GET http://localhost:8000/api/products/search/exemplo

Response: Similar à listagem de produtos
```

#### Obter Produto Específico [user, admin]
```
GET http://localhost:8000/api/products/{id}

Headers
Authorization: Bearer {token}

Exemplo: GET http://localhost:8000/api/products/1

Response
{
  "product": {
    "id": 1,
    "name": "Produto Exemplo",
    "description": "Descrição do produto exemplo",
    "price": "99.99",
    "image_url": "https://exemplo.com/images/produto.jpg",
    "category_id": 1,
    "created_at": "2025-05-17T14:00:00.000000Z",
    "updated_at": "2025-05-17T14:00:00.000000Z"
  }
}
```

#### Criar Produto [user, admin]
```
POST http://localhost:8000/api/products

Headers
Authorization: Bearer {token}
Content-Type: application/json

Body
{
  "name": "Novo Produto",
  "description": "Descrição do novo produto",
  "price": 129.99,
  "validity_date": "2025-12-31",
  "image_url": "https://exemplo.com/images/novo-produto.jpg",
  "category_id": 1
}

Response
{
  "product": {
    "name": "Novo Produto",
    "description": "Descrição do novo produto",
    "price": "129.99",
    "image_url": "https://exemplo.com/images/novo-produto.jpg",
    "category_id": 1,
    "updated_at": "2025-05-17T14:35:00.000000Z",
    "created_at": "2025-05-17T14:35:00.000000Z",
    "id": 2
  }
}
```

#### Atualizar Produto [admin]
```
PUT http://localhost:8000/api/products/{id}

Headers
Authorization: Bearer {token}
Content-Type: application/json

Exemplo: PUT http://localhost:8000/api/products/1

Body
{
  "name": "Produto Atualizado",
  "price": 149.99
}

Response
{
  "product": {
    "id": 1,
    "name": "Produto Atualizado",
    "description": "Descrição do produto exemplo",
    "price": "149.99",
    "image_url": "https://exemplo.com/images/produto.jpg",
    "category_id": 1,
    "created_at": "2025-05-17T14:00:00.000000Z",
    "updated_at": "2025-05-17T14:35:00.000000Z"
  }
}
```

#### Excluir Produto [admin]
```
DELETE http://localhost:8000/api/products/{id}

Headers
Authorization: Bearer {token}

Exemplo: DELETE http://localhost:8000/api/products/1

Response
{
  "message": "Produto deletado com sucesso"
}
```

### Categorias

#### Listar Categorias [user, admin]
```
GET http://localhost:8000/api/categories

Headers
Authorization: Bearer {token}

Response
{
  "categories": [
    {
      "id": 1,
      "name": "Categoria Exemplo",
      "created_at": "2025-05-17T14:00:00.000000Z",
      "updated_at": "2025-05-17T14:00:00.000000Z"
    }
  ]
}
```

#### Obter Categoria Específica [user, admin]
```
GET http://localhost:8000/api/categories/{id}

Headers
Authorization: Bearer {token}

Exemplo: GET http://localhost:8000/api/categories/1

Response
{
  "category": {
    "id": 1,
    "name": "Categoria Exemplo",
    "created_at": "2025-05-17T14:00:00.000000Z",
    "updated_at": "2025-05-17T14:00:00.000000Z"
  }
}
```

#### Criar Categoria [admin]
```
POST http://localhost:8000/api/categories

Headers
Authorization: Bearer {token}
Content-Type: application/json

Body
{
  "name": "Nova Categoria"
}

Response
{
  "category": {
    "name": "Nova Categoria",
    "updated_at": "2025-05-17T14:35:00.000000Z",
    "created_at": "2025-05-17T14:35:00.000000Z",
    "id": 2
  }
}
```

#### Atualizar Categoria [admin]
```
PUT http://localhost:8000/api/categories/{id}

Headers
Authorization: Bearer {token}
Content-Type: application/json

Exemplo: PUT http://localhost:8000/api/categories/1

Body
{
  "name": "Categoria Atualizada"
}

Response
{
  "category": {
    "id": 1,
    "name": "Categoria Atualizada",
    "created_at": "2025-05-17T14:00:00.000000Z",
    "updated_at": "2025-05-17T14:40:00.000000Z"
  }
}
```

#### Excluir Categoria [admin]
```
DELETE http://localhost:8000/api/categories/{id}

Headers
Authorization: Bearer {token}

Exemplo: DELETE http://localhost:8000/api/categories/1

Response
{
  "message": "Categoria deletada com sucesso"
}
```

### Usuários (Apenas para Administradores)

#### Listar Usuários [admin]
```
GET http://localhost:8000/api/users

Headers
Authorization: Bearer {token}

Response
{
  "users": [
    {
      "id": 1,
      "name": "Admin User",
      "email": "admin@example.com",
      "role": "admin",
      "created_at": "2025-05-17T14:00:00.000000Z",
      "updated_at": "2025-05-17T14:00:00.000000Z"
    },
    {
      "id": 2,
      "name": "Regular User",
      "email": "user@example.com",
      "role": "user",
      "created_at": "2025-05-17T14:05:00.000000Z",
      "updated_at": "2025-05-17T14:05:00.000000Z"
    }
  ],
  "per_page": 10,
  "total": 2
}
```

#### Obter Usuário Específico [admin]
```
GET http://localhost:8000/api/users/{id}

Headers
Authorization: Bearer {token}

Exemplo: GET http://localhost:8000/api/users/2

Response
{
  "user": {
    "id": 2,
    "name": "Regular User",
    "email": "user@example.com",
    "role": "user",
    "created_at": "2025-05-17T14:05:00.000000Z",
    "updated_at": "2025-05-17T14:05:00.000000Z"
  }
}
```

#### Criar Usuário [admin]
```
POST http://localhost:8000/api/users

Headers
Authorization: Bearer {token}
Content-Type: application/json

Body
{
  "name": "Novo Usuário",
  "email": "novo@example.com",
  "password": "senha123",
  "role": "user"
}

Response
{
  "name": "Novo Usuário",
  "email": "novo@example.com",
  "role": "user",
  "updated_at": "2025-05-17T14:45:00.000000Z",
  "created_at": "2025-05-17T14:45:00.000000Z",
  "id": 3
}
```

#### Atualizar Usuário [admin]
```
PUT http://localhost:8000/api/users/{id}

Headers
Authorization: Bearer {token}
Content-Type: application/json

Exemplo: PUT http://localhost:8000/api/users/3

Body
{
  "name": "Usuário Atualizado",
  "role": "admin"
}

Response
{
  "id": 3,
  "name": "Usuário Atualizado",
  "email": "novo@example.com",
  "role": "admin",
  "created_at": "2025-05-17T14:45:00.000000Z",
  "updated_at": "2025-05-17T14:50:00.000000Z"
}
```

#### Excluir Usuário [admin]
```
DELETE http://localhost:8000/api/users/{id}

Headers
Authorization: Bearer {token}

Exemplo: DELETE http://localhost:8000/api/users/3

Response
{
  "message": "Usuário deletado com sucesso"
}
```

### Respostas de Erro

#### Erro de Autenticação (401 Unauthorized)
```
{
  "message": "Unauthenticated. Invalid or expired token.",
  "error": "Unauthorized",
  "status_code": 401
}
```

#### Erro de Permissão (403 Forbidden)
```
{
  "message": "You do not have permission to access this resource",
  "error": "Forbidden",
  "status_code": 403
}
```

#### Erro de Validação (422 Unprocessable Entity)
```
{
  "message": "Validation error",
  "errors": {
    "name": [
      "The name field is required."
    ],
    "email": [
      "The email has already been taken."
    ]
  },
  "status_code": 422
}
```