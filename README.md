# SimplePHP Framework 🚀

Um microframework MVC desenvolvido em PHP com foco na performance e simplicidade, sem dependências de bibliotecas externas. O objetivo principal é proporcionar uma compreensão aprofundada sobre o funcionamento interno de frameworks populares de mercado.

É altamente recomendado para estudos, mas também pode ser utilizado em projetos comerciais.

## Estrutura do Projeto 🏗️

```plaintext
/
|-- bootstrap/
|   |-- app.php
|-- config/
|   |-- app.php
|   |-- config.php
|   |-- database.php
|-- docker/
|   |-- data/
|   |   |-- cache/
|   |   |-- mysql/
|   |-- dump/
|   |   |-- dump.sql
|   |-- php/
|   |   |-- Dockerfile
|   |   |-- php-override.ini
|-- src/
|   |-- Core/
|   |   |-- DB/
|   |   |   |-- Builder.php
|   |   |   |-- DB.php
|   |   |   |-- Model.php
|   |   |-- Http/
|   |   |   |-- Request.php
|   |   |   |-- Response.php
|   |   |   |-- Router.php
|   |   |-- Bootstrap.php
|   |   |-- Container.php
|   |-- Http/
|   |   |-- Controllers/
|   |   |   |-- Controller.php
|   |   |-- Middlewares/
|   |   |   |-- Contracts/
|   |-- Helpers/
|   |   |-- Helpers.php
|   |-- Models/
|   |-- Services/
|   |-- Support/
|   |   |-- Contracts/
|   |   |   |-- CacheInterface.php
|   |   |   |-- ViewInterface.php
|   |   |-- CacheRedis.php
|   |   |-- Session.php
|   |   |-- ViewHtml.php
|   |-- Traits/
|   |   |-- Cache.php
|   |   |-- View.php
|-- public/
|   |-- assets/
|   |-- .htaccess
|   |-- index.php
|-- resources/
|   |-- views/
|-- routes/
|   |-- routes.php
|-- tests/
|   |-- Feature/
|   |-- Unit/
|-- .env.example
|-- .gitignore
|-- composer.json
|-- docker-compose.yml
|-- phpunit.xml
|-- README.md
```

## Como Usar 🚀

Certifique-se de ter instalado os seguintes itens antes de começar:

- [Docker](https://www.docker.com/) com [Docker Compose](https://docs.docker.com/compose/)

- [Composer](https://getcomposer.org/)

1. Clone o repositório.

2. Configure o ambiente Docker com docker-compose up.

3. Acesse o aplicativo em [http://localhost:8000](http://localhost:8000).

## Exemplos e Demonstração 🌐

### Exemplo de Configuração (.env)

Copiar e colar o arquivo ".env.example" para ".env" e configurar as variáveis.

```sh
#[Application]
APP_URL=http://localhost:8000
# MAINTENANCE=true

#[Database]
DB_HOST=db
DB_NAME=forgedb
DB_USER=admin
DB_PASS=secret
DB_CHARSET=utf8
```

### Exemplo de Modelo (src/Models/Product.php)

```php
<?php

declare(strict_types=1);

namespace App\Models;

use App\Core\DB\Model;
use App\Traits\Cache;

class Product extends Model
{
    use Cache;

    protected string $table = 'products';
    protected array $attributes = ['name', 'price', 'stock'];

    public function __construct(array $fields = [])
    {
        parent::__construct(fields: $fields);

        $this->cacheInit();
    }

    public function onInsert()
    {
        if ($this->cache->get('product-list')) {
            $this->cache->forget('product-list');
        }
    }

    public function onUpdate()
    {
        if ($this->cache->get('product-list')) {
            $this->cache->forget('product-list');
        }
    }

    public function onDelete()
    {
        if ($this->cache->get('product-list')) {
            $this->cache->forget('product-list');
        }
    }
}
```

### Exemplo de Controller (src/Http/Controllers/ProductController.php)

```php
<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Core\Http\Request;
use App\Services\ProductService;

final class ProductController extends Controller
{
    public function __construct(
        private ProductService $productService
    ) {}

    public function index(): void
    {
        $products = $this->productService->getFromCache();

        view('products/index', ['products' => $products]);
    }

    public function create(): void
    {
        view('products/create');
    }

    public function store(Request $request): void
    {
        $data = $request->getInputs();

        $this->productService->save($data);

        redirect('/products');
    }

    public function show(Request $request): void
    {
        $product = $this->productService->findOne($request->id);

        view('products/show', ['product' => $product]);
    }

    public function edit(Request $request): void
    {
        $product = $this->productService->findOne($request->id);

        view('products/edit', ['product' => $product]);
    }

    public function update(Request $request): void
    {
        $this->productService->update($request->id, $request::getInputs());

        redirect('/products');
    }

    public function delete(Request $request): void
    {
        $this->productService->remove($request->id);

        redirect('/products');
    }
}
```
### Exemplo de Middleware (src/Http/Middlewares/ExampleMiddleware.php)

```php
<?php

declare(strict_types=1);

namespace App\Http\Middlewares;

use App\Core\Http\Request;
use App\Core\Http\Response;
use App\Http\Middlewares\Contracts\MiddlewareInterface;
use Closure;

class ExampleMiddleware implements MiddlewareInterface
{
    public function handle(Request $request, Response $response, Closure $next): void
    {
        // Faça algo antes do próximo middleware
        $next();
        // Faça algo depois do próximo middleware
    }
}
```

### Rotas de Exemplo (routes/routes.php)

```php
<?php

use App\Core\Http\Router;
use App\Http\Controllers\Api\ProductController as ApiProductController;
use App\Http\Controllers\ProductController;
use App\Http\Middlewares\ExampleMiddleware;

Router::get('/', fn () => view('home/index'));

Router::middlewares([ExampleMiddleware::class])
    ->group('/products', function () {
        Router::get('/', [ProductController::class, 'index']);
        Router::get('/create', [ProductController::class, 'create']);
        Router::post('/', [ProductController::class, 'store']);
        Router::get('/{id}', [ProductController::class, 'show']);
        Router::get('/{id}/edit', [ProductController::class, 'edit']);
        Router::put('/{id}', [ProductController::class, 'update']);
        Router::delete('/{id}', [ProductController::class, 'delete']);
    });

Router::group('/api', function () {
    Router::get('/products', [ApiProductController::class, 'index']);
    Router::post('/products', [ApiProductController::class, 'store']);
    Router::get('/products/{id}', [ApiProductController::class, 'show']);
    Router::put('/products/{id}', [ApiProductController::class, 'update']);
    Router::delete('/products/{id}', [ApiProductController::class, 'delete']);
});
```

## Contribuições e Suporte 🤝

Sinta-se à vontade para contribuir! Caso encontre algum problema ou queira adicionar novas funcionalidades, abra uma issue ou envie um pull request.

## Autor 👨‍💻
Bruno Oliveira - [https://github.com/bholiveiradev](https://github.com/bholiveiradev)

## Licença 📝
Este projeto é licenciado sob a [Licença MIT](LICENSE.md).
