# VanillaPHP Framework 🚀

Um microframework MVC desenvolvido em PHP com foco na performance e simplicidade, sem dependências de bibliotecas externas. O objetivo principal é proporcionar uma compreensão aprofundada sobre o funcionamento interno de frameworks populares de mercado.

É altamente recomendado para estudos, mas também pode ser utilizado em projetos comerciais.

## Estrutura do Projeto 🏗️
```plaintext
Copy code
/
|-- app/
|   |-- Controllers/
|   |   |-- Controller.php
|   |-- Database/
|   |   |-- ActiveRecord.php
|   |   |-- DB.php
|   |   |-- QueryBuilder.php
|   |-- Http/
|   |   |-- Request.php
|   |   |-- Response.php
|   |   |-- RouteDesptacher.php
|   |   |-- Router.php
|   |-- Middlewares/
|   |   |-- Contracts/
|   |-- Models/
|   |-- Support/
|   |   |-- Contracts/
|   |-- Traits/
|   |   |-- Cache.php
|   |   |-- View.php
|   |-- UseCases/
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
|-- helpers/
|   |-- helpers.php
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

### Exemplo de Modelo (app/models/Product.php)
```php
<?php

declare(strict_types=1);

namespace App\Models;

use App\Database\ActiveRecord;
use App\Traits\Cache;

class Product extends ActiveRecord
{
    use Cache;

    protected string $table = 'products';
    protected array $attributes = ['name', 'price', 'stock'];

    public function __construct(array $fields = [])
    {
        $this->cacheInit();
        parent::__construct($fields);
    }

    public function onInsert()
    {
        if ($this->cache->get('productList')) {
            $this->cache->forget('productList');
        }
    }

    public function onUpdate()
    {
        if ($this->cache->get('productList')) {
            $this->cache->forget('productList');
        }
    }

    public function onDelete()
    {
        if ($this->cache->get('productList')) {
            $this->cache->forget('productList');
        }
    }
}
```
### Exemplo de Controlador (app/controllers/ProductController.php)
```php
<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Controllers\Controller;
use App\Http\Request;
use App\Models\Product;
use App\Support\Session;
use App\UseCases\ProductUseCase;

class ProductController extends Controller
{
    public function index()
    {
        $products = (new ProductUseCase())->all();
        // Exemplo de retorno de view
        return view('products/index', ['products' => $products]);
    }

    public function store(Request $request)
    {
        $product = new Product();
        $product->fill($request->getAll());
        $product->insert();

        Session::flash('success', 'O produto foi criado!');

        // Exemplo de redirecionamento após uma operação
        return redirect('/admin/products');
    }

    public function show(Request $request)
    {
        $product = (new Product())->find($request->id);

        // Exemplo de retorno de view
        return view('products/show', ['product' => $product]);
    }

    public function delete(Request $request)
    {
        $product = (new Product())->find($request->id);
        $product->delete();

        // Exemplo de resposta JSON
        return $this->response->json(['message' => 'Produto removido com sucesso']);
    }
    
    // Outros métodos do controlador...
}
```
### Exemplo de Middleware (app/middlewares/ExampleMiddleware.php)
```php
<?php

declare(strict_types=1);

namespace App\Middlewares;

use App\Middlewares\Contracts\MiddlewareInterface;
use App\Http\Request;
use App\Http\Response;
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

### Rotas de Exemplo (app/routes/routes.php)
```php
<?php

use App\Controllers\ProductController;
use App\Middlewares\ExampleMiddleware;

$router->get('/', fn () => view('home/index'));

$router->middlewares([ExampleMiddleware::class])
    ->group('/products', function ($router) {
        $router->get('', [ProductController::class, 'index']);
        $router->get('/create', [ProductController::class, 'create']);
        $router->post('', [ProductController::class, 'store']);
        $router->get('/{id}', [ProductController::class, 'show'], [ExampleMiddleware::class]);
        $router->get('/{id}/edit', [ProductController::class, 'edit']);
        $router->put('/{id}', [ProductController::class, 'update'], [ExampleMiddleware::class]);
        $router->delete('/{id}', [ProductController::class, 'delete'], [ExampleMiddleware::class]);
    });

```

## Contribuições e Suporte 🤝

Sinta-se à vontade para contribuir! Caso encontre algum problema ou queira adicionar novas funcionalidades, abra uma issue ou envie um pull request.

## Autor 👨‍💻
Bruno Oliveira - [https://github.com/bholiveiradev](https://github.com/bholiveiradev)

## Licença 📝
Este projeto é licenciado sob a [Licença MIT](LICENSE.md).