<?php
require '../guard.php';


// Generate CSRF Token
if (empty($_SESSION['__csrf'])) {
    $_SESSION['__csrf'] = bin2hex(random_bytes(32));
}


if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['issSubmitted'])) {
    // Verify CSRF Token
    if (!hash_equals($_SESSION['__csrf'], $_POST['__csrf'])) {
        header('Location: ' . $_SERVER['PHP_SELF']);
        exit;
    }

    // Collect Data
    $table = 'product_tbl';
    $name = htmlspecialchars($_POST['name']);
    $code = htmlspecialchars($_POST['code']);
    $price = htmlspecialchars($_POST['price']);
    $description = htmlspecialchars($_POST['description']);
    $image = $database->file('image', 'uploads/product/');
    $redirect = 'index.php';

    // Validations
    $isValid = $database->validate([
        'name' => $name,
        'code' => $code,
        'price' => $price,
        'description' => $description,
        'image' => $image
    ]);

    $params = [
        'product_name' => $name,
        'product_code' => $code,
        'product_price' => $price,
        'product_description' => $description,
        'product_image' => $image,
    ];

    if ($isValid) {
        $database->save($table, $params, $redirect);
    }
}

require '../layout/header.php';
?>

<main class="page-content">
    <!--breadcrumb-->
    <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
        <div class="breadcrumb-title pe-3">Shopping<span class="text-primary">Cart</span></div>
    </div>
    <div class="card">
        <div class="card-header py-3">
            <h6 class="mb-0">Add Product</h6>

            <div class="card-body">
                <div class="row">
                    <div class="col-12 col-lg-8 d-flex">
                        <div class="card border shadow-none w-100">
                            <div class="card-body">
                                <?php if ($database->getErrors()): ?>
                                <?php foreach ($database->getErrors() as $error): ?>
                                <div class="alert alert-danger"><?= $error ?></div>
                                <?php endforeach; ?>
                                <?php endif; ?>
                                <form method="post" action="<?= htmlspecialchars($_SERVER['PHP_SELF']) ?>"
                                    class="row g-3" enctype="multipart/form-data">
                                    <input type="hidden" name="__csrf"
                                        value="<?= htmlspecialchars($_SESSION['__csrf']) ?>">
                                    <div class="col-12">
                                        <label class="form-label">Product Name</label>
                                        <input type="text" class="form-control" name="name" placeholder="Product name">
                                    </div>
                                    <div class="col-12">
                                        <label class="form-label">Product Code</label>
                                        <input type="text" class="form-control" name="code" placeholder="Product name">
                                    </div>
                                    <div class="col-12">
                                        <label class="form-label">Product Price</label>
                                        <input type="number" class="form-control" name="price"
                                            placeholder="Product name">
                                    </div>
                                    <div class="col-12">
                                        <label class="form-label">Description</label>
                                        <textarea class="form-control" rows="3" cols="3" name="description"
                                            placeholder="Product Description"></textarea>
                                    </div>
                                    <div class="col-12">
                                        <label class="form-label">Product Image</label>
                                        <input type="file" name="image" class="form-control">
                                    </div>
                                    <div class="col-12">
                                        <div>
                                            <button name="issSubmitted" class="btn btn-primary">Add Product</button>
                                            <a class="btn btn-outline-dark m-3" href="index.php">Cancel</a>
                                        </div>
                                    </div>
                                </form>
                            </div>

                        </div>
                    </div>
                </div>
            </div>
            <!--end row-->
        </div>
    </div>
    </div>
</main>

<?php require '../layout/footer.php'; ?>