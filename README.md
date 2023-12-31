# VanillaPHP Framework 🚀

Um microframework MVC desenvolvido em PHP com foco na performance e simplicidade, sem dependências de bibliotecas externas. O objetivo principal é proporcionar uma compreensão aprofundada sobre o funcionamento interno de frameworks populares de mercado.

É altamente recomendado para estudos, mas também pode ser utilizado em projetos comerciais.

## Estrutura do Projeto 🏗️
```plaintext
/
|-- config/
|   |-- app.php
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
|   |   |   |-- RouteDesptacher.php
|   |   |   |-- Router.php
|   |   |-- Bootstrap.php
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

### Exemplo de Configuração (app/config/app.php)
```php
<?php

define('APP_URL',   'http://localhost:8000');
define('ROOT_PATH', realpath(__DIR__ . '/..'));
define('APP_PATH',  ROOT_PATH . '/app');
define('VIEW_PATH', ROOT_PATH . '/resources/views');

define('DB_HOST',    'db');
define('DB_NAME',    'php-mvc');
define('DB_USER',    'admin');
define('DB_PASS',    'password');
define('DB_CHARSET', 'utf8');
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

    protected static string $table = 'products';
    protected static array $attributes = ['name', 'price', 'stock'];

    public function __construct(array $fields = [])
    {
        parent::__construct(fields: $fields);

        self::cacheInit();
    }

    public static function onInsert()
    {
        if (self::$cache->get('productList')) {
            self::$cache->forget('productList');
        }
    }

    public static function onUpdate()
    {
        if (self::$cache->get('productList')) {
            self::$cache->forget('productList');
        }
    }

    public static function onDelete()
    {
        if (self::$cache->get('productList')) {
            self::$cache->forget('productList');
        }
    }
}
```
### Exemplo de Controlador (src/Http/Controllers/ProductController.php)
```php
<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Core\Http\Request;
use App\Models\Product;
use App\Support\Session;
use App\Services\ProductService;

class ProductController extends Controller
{
    public function index(): void
    {
        $products = ProductService::gerProductsFromCache();

        view('products/index', ['products' => $products]);
    }

    public function create(): void
    {
        view('products/create');
    }

    public function store(Request $request): void
    {
        $data = $request::getInputs();

        Product::fill($data)->insert();

        Session::flash('success', 'O produto foi criado!');

        redirect('/products');
    }

    public function show(Request $request): void
    {
        $product = Product::find($request->id);

        view('products/show', ['product' => $product]);
    }

    public function edit(Request $request): void
    {
        $product = Product::find($request->id);

        view('products/edit', ['product' => $product]);
    }

    public function update(Request $request): void
    {
        $product = Product::find($request->id);

        $product->update($request::getInputs(), $product->id);

        Session::flash('success', 'O produto foi salvo!');

        redirect('/products');
    }

    public function delete(Request $request): void
    {
        $product = Product::find($request->id);
        $product->delete();

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
use \Closure;

class ExampleMiddleware implements MiddlewareInterface
{
    public function handle(Request $request, Response $response, Closure $next): void
    {
        // Faça algo antes do request passar pelo middleware seguinte
        $next();
        // Faça algo depois do request passar pelo middleware seguinte
    }
}
```

### Rotas de Exemplo (routes/routes.php)
```php
<?php

use App\Core\Http\Router;
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
```

## Contribuições e Suporte 🤝

Sinta-se à vontade para contribuir! Caso encontre algum problema ou queira adicionar novas funcionalidades, abra uma issue ou envie um pull request.

## Autor 👨‍💻
Bruno Oliveira - [https://github.com/bholiveiradev](https://github.com/bholiveiradev)

## Licença 📝
Este projeto é licenciado sob a [Licença MIT](LICENSE.md).