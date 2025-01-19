<!doctype html>
<html lang="en">

<head>
  <title>Title</title>
  <!-- Required meta tags -->
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <link rel="icon" type="image/png" href="/assets/images/favicon.png">

  <!-- Bootstrap CSS v5.2.1 -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.1/dist/css/bootstrap.min.css" rel="stylesheet"
    integrity="sha384-iYQeCzEYFbKjA/T2uDLTpkwGzCiq6soy8tYaI1GyVh/UjpbCx/TYkiZhlZB6+fzT" crossorigin="anonymous">

</head>

<body>
  <header>
    <!-- place navbar here -->
  </header>
  <main>
    <section class="container">
      <h1>Produtos</h1>
      <a href="/products/create" class="btn btn-success">
        Novo produto
      </a>

      <?php if (has_flash('success')): ?>
      <div class="alert alert-success alert-dismissible fade show mt-4" role="alert">
        <h4 class="alert-heading">Tudo certo 👍</h4>
        <?= flash('success') ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
      </div>
      <?php endif ?>

      <div class="card mt-4">
        <div class="card-body">
          <div class="table-responsive">
            <table class="table">
              <thead>
                <tr>
                  <th>#</th>
                  <th>Nome</th>
                  <th>Preço</th>
                  <th>Estoque</th>
                  <th></th>
                </tr>
              </thead>
              <tbody>
                <?php if(!empty($products)): ?>
                  <?php foreach ($products as $product): ?>
                  <tr>
                    <td><?= $product->id ?></td>
                    <td><?= $product->name ?></td>
                    <td><?= $product->price ?></td>
                    <td><?= $product->stock ?></td>
                    <td class="d-flex">
                      <a class="btn btn-sm btn-primary mx-1"
                        href="<?= APP_URL . '/products/' . $product->id ?>">Visualizar</a>
                      <a class="btn btn-sm btn-warning mx-1"
                        href="<?= APP_URL . '/products/' . $product->id . '/edit' ?>">Editar</a>
                      <form action="<?= APP_URL . '/products/' . $product->id ?>" method="POST">
                        <input type="hidden" name="_method" value="DELETE">
                        <button type="submit" class="btn btn-sm btn-danger mx-1"
                          onclick="return confirm('Deseja realmente remover?')">Remover</button>
                      </form>
                    </td>
                  </tr>
                  <?php endforeach ?>
                <?php else: ?>
                  <tr>
                    <td colspan="4">
                      Nenhum produto cadastrado
                    </td>
                  </tr>
                <?php endif ?>
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </section>
  </main>
  <footer>
    <!-- place footer here -->
  </footer>
  <!-- Bootstrap JavaScript Libraries -->
  <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"
    integrity="sha384-oBqDVmMz9ATKxIep9tiCxS/Z9fNfEXiDAYTujMAeBAsjFuCZSmKbSSUnQlmh/jp3" crossorigin="anonymous">
    </script>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.1/dist/js/bootstrap.min.js"
    integrity="sha384-7VPbUDkoPSGFnVtYi0QogXtr74QeVeeIs99Qfg5YCF+TidwNdjvaKZX19NZ/e6oz" crossorigin="anonymous">
    </script>
</body>

</html>