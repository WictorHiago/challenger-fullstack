# challenger-fullstack
Sistema de Gerenciamento de Produtos desenvolvido como desafio. O projeto fullstack utiliza Vue 3 + TypeScript no front-end e Laravel 10 + MySQL no back-end.

## Instalação do projeto

### Crie o container docker com banco de dados MySQL
```
docker-compose up -d
```

### Comandos de instalação
```
cd backend
composer install
php artisan install:api
php artisan migrate
```

## Documentação da API

### Autenticação

#### Register
```
POST http://localhost:8000/api/register

Body
{
  "name": "oliver souza",
  "email": "oliver.souza@gmail.com",
  "role": "admin",
  "password": "admin@admin",
  "password_confirmation": "admin@admin"
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
  "message": "Login successful",
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
  "message": "Successfully logged out"
}
```

#### Get User Profile
```
GET http://localhost:8000/api/user

Headers
Authorization: Bearer {token}

Response
{
  "message": "User retrieved successfully",
  "user": {
    "id": 1,
    "name": "oliver souza",
    "email": "oliver.souza@gmail.com",
    "role": "admin"
  }
}
```

### Produtos

#### Listar Produtos (com paginação)
```
GET http://localhost:8000/api/products

Headers
Authorization: Bearer {token}

Response
{
  "current_page": 1,
  "data": [
    {
      "id": 1,
      "name": "Produto Exemplo",
      "description": "Descrição do produto exemplo",
      "price": "99.99",
      "validity_date": "2025-12-31",
      "image": "produto.jpg",
      "image_url": "https://exemplo.com/images/produto.jpg",
      "category_id": 1,
      "category": {
        "id": 1,
        "name": "Categoria Exemplo"
      }
    }
  ],
  "per_page": 10,
  "total": 1
}
```

#### Buscar Produtos
```
GET http://localhost:8000/api/products/search/{termo}

Headers
Authorization: Bearer {token}

Exemplo: GET http://localhost:8000/api/products/search/exemplo

Response: Similar à listagem de produtos
```

#### Obter Produto Específico
```
GET http://localhost:8000/api/products/{id}

Headers
Authorization: Bearer {token}

Exemplo: GET http://localhost:8000/api/products/1

Response
{
  "id": 1,
  "name": "Produto Exemplo",
  "description": "Descrição do produto exemplo",
  "price": "99.99",
  "validity_date": "2025-12-31",
  "image": "produto.jpg",
  "image_url": "https://exemplo.com/images/produto.jpg",
  "category_id": 1,
  "category": {
    "id": 1,
    "name": "Categoria Exemplo"
  }
}
```

#### Criar Produto
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
  "name": "Novo Produto",
  "description": "Descrição do novo produto",
  "price": "129.99",
  "validity_date": "2025-12-31",
  "image": "novo-produto.jpg",
  "image_url": "https://exemplo.com/images/novo-produto.jpg",
  "category_id": 1,
  "updated_at": "2025-05-17T14:30:00.000000Z",
  "created_at": "2025-05-17T14:30:00.000000Z",
  "id": 2
}
```

#### Atualizar Produto
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
  "id": 1,
  "name": "Produto Atualizado",
  "description": "Descrição do produto exemplo",
  "price": "149.99",
  "validity_date": "2025-12-31",
  "image": "produto.jpg",
  "image_url": "https://exemplo.com/images/produto.jpg",
  "category_id": 1
}
```

#### Excluir Produto
```
DELETE http://localhost:8000/api/products/{id}

Headers
Authorization: Bearer {token}

Exemplo: DELETE http://localhost:8000/api/products/1

Response
{
  "message": "Product deleted successfully"
}
```

### Categorias

#### Listar Categorias
```
GET http://localhost:8000/api/categories

Headers
Authorization: Bearer {token}

Response
[
  {
    "id": 1,
    "name": "Categoria Exemplo",
    "created_at": "2025-05-17T14:00:00.000000Z",
    "updated_at": "2025-05-17T14:00:00.000000Z"
  }
]
```

#### Obter Categoria Específica
```
GET http://localhost:8000/api/categories/{id}

Headers
Authorization: Bearer {token}

Exemplo: GET http://localhost:8000/api/categories/1

Response
{
  "id": 1,
  "name": "Categoria Exemplo",
  "created_at": "2025-05-17T14:00:00.000000Z",
  "updated_at": "2025-05-17T14:00:00.000000Z"
}
```

#### Criar Categoria
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
  "name": "Nova Categoria",
  "updated_at": "2025-05-17T14:35:00.000000Z",
  "created_at": "2025-05-17T14:35:00.000000Z",
  "id": 2
}
```

#### Atualizar Categoria
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
  "id": 1,
  "name": "Categoria Atualizada",
  "created_at": "2025-05-17T14:00:00.000000Z",
  "updated_at": "2025-05-17T14:40:00.000000Z"
}
```

#### Excluir Categoria
```
DELETE http://localhost:8000/api/categories/{id}

Headers
Authorization: Bearer {token}

Exemplo: DELETE http://localhost:8000/api/categories/1

Response
{
  "message": "Category deleted successfully"
}