<?php
session_start();

require '../config.php';

// Generate CSRF Token
if (empty($_SESSION['__csrf'])) {
    $_SESSION['__csrf'] = bin2hex(random_bytes(32));
}


$id = htmlspecialchars($_GET['id']);
$table = 'product_tbl';
$rows = '*';
$join = null;
$where = "id = $id";
$order = null;
$limit = null;
$redirect = 'index.php';


if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['issSubmitted'])) {
    // Verify CSRF Token
    if (!hash_equals($_SESSION['__csrf'], $_POST['__csrf'])) {
        header('Location: ' . $_SERVER['PHP_SELF']);
        exit;
    }

    $pid = htmlspecialchars($_POST['id']);
    $name = htmlspecialchars($_POST['name']);
    $code = htmlspecialchars($_POST['code']);
    $price = htmlspecialchars($_POST['price']);
    $description = htmlspecialchars($_POST['description']);
    $oldImage = htmlspecialchars($_POST['oldImage']);
    $newImage = $database->file('image', 'uploads/products/');
    $image = null;

    if (!empty($newImage)) {
        $image = $newImage;
    } else {
        $image = $oldImage;
    }

    $isValidate = $database->validate([
        'product_name' => $name,
        'product_code' => $code,
        'product_price' => $price,
        'product_description' => $description,
        'product_image' => $image,
    ]);


    $params = [
        'product_name' => $name,
        'product_code' => $code,
        'product_price' => $price,
        'product_description' => $description,
        'product_image' => $image,
    ];

    if (!empty($newImage)) {
        unlink($oldImage);
    }

    if ($isValidate) {
        $database->update($table, $params, "id = $pid", $redirect);
    }
}


// Fetch Record for specific product
$product = $database->select($table, $rows, $join, $where, $order, $limit);







require 'header.php';
?>

<main class="page-content">
    <!--breadcrumb-->
    <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
        <div class="breadcrumb-title pe-3">Shopping<span class="text-primary">Cart</span></div>
    </div>
    <div class="card">
        <div class="card-header py-3">
            <h6 class="mb-0">Edit Product</h6>

            <div class="card-body">
                <div class="row">
                    <div class="col-12 col-lg-6 d-flex">
                        <div class="card border shadow-none w-100">
                            <div class="card-body">
                                <form method="post" action="<?= htmlspecialchars($_SERVER['PHP_SELF']) ?>"
                                    class="row g-3" enctype="multipart/form-data">
                                    <input type="hidden" name="__csrf"
                                        value="<?= htmlspecialchars($_SESSION['__csrf']) ?>">
                                    <input type="hidden" name="id" value="<?= htmlspecialchars($product['id']) ?>">
                                    <input type="hidden" name="oldImage"
                                        value="<?= htmlspecialchars($product['product_image']) ?>">
                                    <div class="col-12">
                                        <label class="form-label">Product Name</label>
                                        <input type="text" class="form-control" name="name" placeholder="Product name"
                                            value="<?= htmlspecialchars($product['product_name']) ?>">
                                    </div>
                                    <div class="col-12">
                                        <label class="form-label">Product Code</label>
                                        <input type="text" class="form-control" name="code" placeholder="Product name"
                                            value="<?= htmlspecialchars($product['product_code']) ?>">
                                    </div>
                                    <div class="col-12">
                                        <label class="form-label">Product Price</label>
                                        <input type="number" class="form-control" name="price"
                                            placeholder="Product name"
                                            value="<?= htmlspecialchars($product['product_price']) ?>">
                                    </div>
                                    <div class="col-12">
                                        <label class="form-label">Description</label>
                                        <textarea class="form-control" rows="3" cols="3" name="description"
                                            placeholder="Product Description"><?= htmlspecialchars($product['product_description']) ?></textarea>
                                    </div>
                                    <div class="col-12">
                                        <label class="form-label">Product Image</label>
                                        <input type="file" name="image" class="form-control">
                                        <img width="150" src="<?= htmlspecialchars($product['product_image']) ?>"
                                            alt="Product Image">
                                    </div>
                                    <div class="col-12">
                                        <div>
                                            <button name="issSubmitted" class="btn btn-dark text-white">Save
                                                Changes</button>
                                            <a class="btn btn-outline-danger m-3" href="index.php">Cancel</a>
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

<?php require 'footer.php'; ?>