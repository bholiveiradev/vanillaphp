<!doctype html>
<html lang="en">

<head>
  <title>Atualizar Produto</title>
  <!-- Required meta tags -->
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

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
      <h1 class="mb-4">Atualizar Produto # <?= $product->id ?></h1>
      <form action="<?= APP_URL . '/products/' . $product->id ?>" method="POST">
        <input type="hidden" name="_method" value="PUT">
        <div class="mb-3">
          <label for="name" class="form-label">Nome do produto</label>
          <input name="name" type="text" class="form-control" value="<?= $product->name ?>" />
        </div>
        <div class="mb-3">
          <label for="price" class="form-label">Preço</label>
          <input name="price" type="text" class="form-control" value="<?= $product->price ?>" />
        </div>
        <div class="mb-3">
          <label for="stock" class="form-label">Qtd Estoque</label>
          <input name="stock" type="text" class="form-control" value="<?= $product->stock ?>" />
        </div>
        <button type="submit" class="btn btn-success">Salvar</button>
      </form>
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