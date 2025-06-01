# Catálogo de Filmes - Full Stack

Uma aplicação completa de catálogo de filmes que integra com a API do The Movie Database (TMDB), permitindo buscar filmes e gerenciar uma lista de favoritos personalizada.

## Tecnologias Utilizadas

### Backend

- **Laravel 12.0** - Framework PHP para desenvolvimento da API REST
- **PHP 8.2+** - Linguagem de programação
- **MySQL 8.0** - Banco de dados relacional
- **Laravel Sanctum** - Autenticação de API
- **Docker** - Containerização da aplicação

### Frontend

- **Vue.js 3.5** - Framework JavaScript reativo
- **Vue Router 4.5** - Roteamento SPA
- **Pinia 3.0** - Gerenciamento de estado
- **Tailwind CSS 4.1** - Framework CSS utilitário
- **Axios 1.9** - Cliente HTTP para requisições
- **Vite 6.3** - Build tool e servidor de desenvolvimento

### Infraestrutura

- **Docker Compose** - Orquestração de containers
- **Nginx** - Servidor web

## Funcionalidades Implementadas

### Funcionalidades Principais

- [x] **Busca de filmes** - Integração com API do TMDB para buscar filmes por nome
- [x] **Adicionar aos favoritos** - Salvar filmes favoritos localmente no banco de dados
- [x] **Listar favoritos** - Tela dedicada para visualizar filmes favoritos
- [x] **Remover dos favoritos** - Remover filmes da lista de favoritos
- [x] **Autenticação** - Sistema de login/registro de usuários
- [x] **Filtro por gênero** - Filtrar filmes favoritos por categoria

### Funcionalidades Técnicas

- [x] **API REST** - Endpoints para gerenciamento de favoritos
- [x] **SPA (Single Page Application)** - Frontend Vue.js separado
- [x] **Containerização** - Aplicação totalmente dockerizada
- [x] **Migrations** - Estrutura de banco versionada
- [x] **Seeders** - Dados de exemplo para testes

## Arquitetura do Projeto

```
MoviesLWSA/
├── api/                           # Backend Laravel
│   ├── app/
│   │   ├── Http/Controllers/      # Controllers da API
│   │   │   ├── AuthController.php
│   │   │   └──  MovieController.php
│   │   │   Http/Requests/         # Validações de requests
│   │   │   ├── LoginUserRequest.php
│   │   │   ├── RegisterUserRequest.php
│   │   │   └── StoreMovieRequest.php
│   │   ├── Models/                # Models Eloquent
│   │   │   ├── User.php
│   │   │   ├── Movie.php
│   │   │   └── Favorite.php
│   │   ├── Services/              # Lógica de negócio
│   │   │   ├── MovieService.php
│   │   │   └── UserService.php
│   │   └── Repositories/          # Camada de dados
│   │       ├── FavoriteRepository.php
│   │       ├── MovieRepository.php
│   │       └── UserRepository.php
│   ├── database/
│   │   ├── migrations/            # Estrutura do banco
│   │   └── seeders/               # Dados de exemplo
│   ├── routes/
│   │   └── api.php                # Rotas da API
│   ├── Dockerfile
│   └── .env
│
├── web/                           # Frontend Vue.js
│   ├── src/
│   │   ├── components/            # Componentes reutilizáveis
│   │   ├── pages/                 # Páginas da aplicação
│   │   │   ├── Home.vue           # Busca de filmes
│   │   │   ├── Favorites.vue      # Lista de favoritos
│   │   │   ├── Login.vue          # Autenticação
│   │   │   └── Register.vue       # Cadastro
│   │   ├── services/              # Integração com APIs
│   │   ├── stores/                # Gerenciamento de estado
│   │   ├── composables/           # Lógica reutilizável (Vue Composition API)
│   │   │   └── useGenres.js       # Composable para gêneros TMDB
│   │   └── router/                # Configuração de rotas
│   ├── Dockerfile
│   └── .env
└── docker-compose.yml             # Orquestração dos serviços
```

## Estrutura do Banco de Dados

### Tabelas Principais

#### `users`

- `id` - Chave primária
- `name` - Nome do usuário
- `email` - Email único
- `password` - Senha criptografada
- `created_at`, `updated_at` - Timestamps

#### `movies`

- `id` - Chave primária
- `id_tmdb` - ID único do filme no TMDB
- `title` - Título do filme
- `poster_path` - Caminho do poster
- `release_date` - Data de lançamento
- `overview` - Sinopse do filme
- `genre_ids_tmdb` - IDs dos gêneros (JSON)
- `created_at`, `updated_at` - Timestamps

#### `favorites`

- `id` - Chave primária
- `user_id` - Referência ao usuário (FK)
- `movie_id` - Referência ao filme (FK)
- `created_at`, `updated_at` - Timestamps

## API Endpoints

### Autenticação

```
POST /api/register    # Cadastro de usuário
POST /api/login       # Login
POST /api/logout      # Logout (autenticado)
GET  /api/user        # Dados do usuário (autenticado)
```

### Filmes Favoritos

```
POST   /api/favorite              # Adicionar aos favoritos
DELETE /api/favorite/{movieId}    # Remover dos favoritos
GET    /api/favorites             # Listar favoritos do usuário
```

## Configuração da API do TMDB

### Obter Chave da API

    1. Acesse The Movie Database (TMDB) na URL: https://www.themoviedb.org/
    2. Crie uma conta
    3. No canto superior direito, clique na imagem do seu usuário
    4. Vá em "Configurações" e depois em "API"
    5. Vai aparecer a opção "Request an API Key", abaixo terá um link para você criar sua API Key
    6. Preencha os dados solicitados
    7. Copie a chave correspondente em "API Read Access Token"

## Como rodar o projeto com Docker

### Pré-requisitos

    - Docker
    - Docker Compose
    - Git

### 1. Clone o repositório

```bash
git clone https://github.com/andr-vini/MoviesLWSA.git
cd MoviesLWSA                    # Entra na pasta do projeto
cp api/.env.example api/.env     # Copia o arquivo .env.example e renomeia para .env na pasta API
cp web/.env.example web/.env     # Copia o arquivo .env.example e renomeia para .env na pasta WEB
```

### 2. Configure a chave da API do TMDB (Caso já tenha criado a API Key no TMDB, continue essas intruções no passo 7)

    1. Acesse The Movie Database (TMDB) na URL: https://www.themoviedb.org/
    2. Crie uma conta
    3. No canto superior direito, clique na imagem do seu usuário
    4. Vá em "Configurações" e depois em "API"
    5. Vai aparecer a opção "Request an API Key", abaixo terá um link para você criar sua API Key
    6. Preencha os dados solicitados
    7. Copie a chave correspondente em "API Read Access Token"
    8. Abra o projeto e vá no arquivo ".env" dentro da pasta "web"
       8.1. Caso o arquivo não exista, copie o arquivo ".env.example" e renomeie a cópia para ".env"
    9. Cole a chave (API Read Access Token) na variável "VITE_TMDB_API_READ_ACCESS_TOKEN"

### 3. Configure as variáveis de ambiente

O projeto está configurado para funcionar com as variáveis de ambiente definidas no `docker-compose.yml`. As principais configurações são:

**Backend (Laravel):**

- Certifique-se de que o arquivo ".env" foi criado dentro da pasta "api"
- `DB_HOST=db`
- `DB_PORT=3306`
- `DB_DATABASE=movies_db`
- `DB_USERNAME=root`
- `DB_PASSWORD=root`

**Frontend (Vue.js):**

- Certifique-se de que o arquivo ".env" foi criado dentro da pasta "web"
- A chave da API do TMDB deve ser configurada no frontend

### 4. Suba a aplicação

```bash
# Caso use alguma versão do docker anterior a 2022 o seu comando deve ser:
docker-compose up --build -d

# Caso use versões mais atualizadas, seu comando deve ser:
docker compose up --build -d
```

### 5. Aguarde a inicialização

O processo de inicialização inclui:

- Instalação das dependências do Laravel
- Geração da chave da aplicação
- Execução das migrations
- Execução dos seeders
- Garantia de permissões para pastas storage e bootstrap/cache do laravel
- Instalação das dependências do Vue.js

### 6. Acesse a aplicação

- **Frontend:** http://localhost:5173
- **Backend API:** http://localhost:8000
- **Banco de dados:** localhost:4306

## Como rodar o frontend separadamente

O frontend já roda separado do backend, ele é configurado pelo docker e fica disponível na url: http://localhost:5173

O próprio docker se encarrega de instalar as dependências do Vue.js e rodar a aplicação na url informada anteriormente

Caso o docker não tenha instalado a pasta node_modules, rode o seguinte comando
```
docker exec -it vue_frontend npm install
```
## Como importar o banco de dados

### Usando Migrations

O projeto utiliza migrations do Laravel que são executadas automaticamente durante a inicialização do Docker. As migrations criam:

    1. Tabela `users` - Usuários do sistema
    2. Tabela `movies` - Filmes do TMDB
    3. Tabela `favorites` - Relacionamento muitos para muitos entre usuário e filme
    4. Tabelas do sistema (cache, jobs, tokens)

- Caso as migrations não tenham sido criadas por algum motivo, siga os seguintes passos:

```
1. Navegue até a pasta do projeto
2. Execute o comando: docker exec -it laravel_backend php artisan migrate --seed
```

### Dados de Exemplo

A seeder cria um usuário de teste durante a inicialização do Docker:

- **Email:** test@example.com
- **Senha:** 12345

Mas você pode criar um usuário próprio na tela de registros

## Localização do CRUD

### Backend (Laravel)

- **Routes:** `api/routes/api.php`
- **Controllers:** `api/app/Http/Controllers/MovieController.php`
- **Models:** `api/app/Models/(User.php, Movie.php, Favorite.php)`
- **FormRequests:** `api/app/Http/Requests/(LoginUserRequest.php, RegisterUserRequest.php, StoreMovieRequest.php)`
- **Services:** `api/app/Services/MovieService.php`
- **Repositories:** `api/app/Repositories/(FavoriteRepository.php, MovieRepository.php, UserRepository.php)`

### Frontend (Vue.js)

- **Páginas:** `web/src/pages/`
  - `Home.vue` - Busca e adição de favoritos
  - `Favorites.vue` - Listagem e remoção de favoritos
- **Serviços:** `web/src/services/`
- **Stores:** `web/src/stores/`
- **Composables:** `web/src/composables/`
  - `useGenres.js` - Gerenciamento de gêneros TMDB com cache

## Como testar a aplicação

### 1. Teste Manual da Interface

    1. Acesse http://localhost:5173
    2. Registre um novo usuário ou use as credenciais de teste:
    - Email: test@example.com
    - Senha: 12345
    3. Busque por filmes na página inicial
    4. Adicione filmes aos favoritos clicando no coração no centro do poster
    5. Navegue para a página de favoritos no menu lateral ou no ícone de coração vermelho no header
    6. Filtre por gênero
    7. Remova filmes dos favoritos clicando no coração no centro do poster

### 2. Testes Automatizados

Para executar os testes do Laravel:

```bash
docker exec -it laravel_backend php artisan test
```

### Documentação da API

- **Documentação oficial:** https://developer.themoviedb.org/reference/intro/getting-started
- **Endpoints utilizados:**
  - Busca de filmes: `/search/movie`
  - Gêneros: `/genre/movie/list`

## Comandos Úteis

### Docker

```bash
# Subir todos os serviços
docker-compose up -d

# Ver logs dos serviços
docker-compose logs -f

# Parar todos os serviços
docker-compose down

# Rebuild dos containers
docker-compose up -d --build
```

### Laravel (dentro do container)

```bash
# Acessar o container do backend
docker exec -it laravel_backend bash

# Executar migrations
php artisan migrate

# Executar seeders
php artisan db:seed

# Limpar cache
php artisan cache:clear
php artisan config:clear
```

### Vue.js (dentro do container)

```bash
# Acessar o container do frontend
docker exec -it vue_frontend sh

# Instalar dependências
npm install

# Build para produção
npm run build
```

## Problemas comuns

### Problema: Containers não sobem

**Solução:** Verifique se as portas 5173, 8000 e 4306 estão livres

### Problema: Erro de conexão com banco

**Solução:** Aguarde um pouco para que o MySQL inicialize completamente

### Problema: Frontend não conecta com backend

**Solução:** Aguarde um pouco, dependendo do seu sistema operacional, o docker demora um pouco para subir os serviços do backend

### Problema: Erro de permissão no Laravel

**Solução:** Execute os seguintes comandos dentro do container:

```bash
chown -R www-data:www-data /var/www/storage /var/www/bootstrap/cache
chmod -R 775 /var/www/storage /var/www/bootstrap/cache
```

## Notas de Desenvolvimento

- O projeto utiliza **Laravel Sanctum** para autenticação de API
- O frontend é uma **SPA completa** totalmente separada do backend, se comunicando através de API
- **Proxy configurado** no Vite para comunicação com a API através do nginx
- **Migrations automáticas** na inicialização do Docker
- **Criação de Keys automáticas** na inicialização do Docker
- **Estrutura modular** com Services e Repositories seguindo os principios SOLID
- **Tratamento de erros** implementado em todas as camadas
