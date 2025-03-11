# Task API

Este é um projeto de API para gerenciar tarefas, desenvolvido com Laravel.

## Estrutura do Projeto

```
.editorconfig
.env
.env.example
.env.testing
.gitattributes
.gitignore
.phpunit.result.cache
artisan
composer.json
composer.lock
config.txt
docker-compose.yml
Dockerfile
package.json
phpunit.xml
README.md
update
vite.config.js
.github/
    workflows/
app/
    Http/
    Models/
    Policies/
    Providers/
    Services/
bootstrap/
    app.php
    providers.php
    cache/
config/
    app.php
    auth.php
    cache.php
    database.php
    filesystems.php
    ...
database/
    ...
nginx/
php/
public/
resources/
routes/
storage/
tests/
vendor/
```

## Requisitos

- PHP 8.0 ou superior
- Composer
- MySQL
- Docker (opcional)

## Instalação

1. Clone o repositório:

```sh
git clone https://github.com/seu-usuario/task-api.git
cd task-api
```

2. Instale as dependências do Composer:

```sh
composer install
```

3. Copie o arquivo `.env.example` para `.env` e configure suas variáveis de ambiente:

```sh
cp .env.example .env

DB_CONNECTION=mysql
DB_HOST=db
DB_PORT=3306
DB_DATABASE=taskdb
DB_USERNAME=user
DB_PASSWORD=pass
```


4. Gere a chave da aplicação:

```sh
php artisan key:generate
```

5. Execute as migrações do banco de dados:

```sh
php artisan migrate
```

## Executando o Projeto

### Localmente

Para executar o projeto localmente, você pode usar o servidor embutido do Laravel:

```sh
php artisan key:generate
```

```sh
php artisan migrate
```

```sh
php artisan serve
```

### Usando Docker

Se preferir usar Docker, você pode usar o arquivo `docker-compose.yml` fornecido:

```sh
docker-compose up -d
```

```sh
docker exec -it task-api bash
php artisan key:generate
```

## Testes

Para executar os testes, use o seguinte comando:

```sh
php artisan test
```

## CI/CD

Este projeto utiliza GitHub Actions para CI/CD. O arquivo de configuração está localizado em [`.github/workflows/ci-cd.yml`](.github/workflows/ci-cd.yml).

## Estrutura do Código

- `app/Http/`: Contém os controladores da aplicação.
- `app/Models/`: Contém os modelos Eloquent.
- `app/Policies/`: Contém as políticas de autorização.
- `app/Providers/`: Contém os provedores de serviço.
- `app/Services/`: Contém os serviços da aplicação.
- `config/`: Contém os arquivos de configuração.
- `database/`: Contém as migrações e seeders do banco de dados.
- `routes/`: Contém os arquivos de rotas.
- `tests/`: Contém os testes da aplicação.

## Contribuição

Se você deseja contribuir com este projeto, por favor, abra uma issue ou envie um pull request.

## Licença

Este projeto está licenciado sob a licença MIT. Veja o arquivo [LICENSE](LICENSE) para mais detalhes.