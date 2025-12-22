<?php
session_start();
require './admin/config.php';

// create and empty array in session variable
if (empty($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}

// Generate CSRF token
if (empty($_SESSION['__csrf'])) {
    $_SESSION['__csrf'] = bin2hex(random_bytes(32));
}


$table = 'product_tbl';
$rows = '*';
$join = null;
$where = null;
$order = 'id DESC';
$limit = null;
$offset = null;

$products = $database->selectAll($table, $rows, $join, $where, $order, $limit, $offset);



// Add to Cart

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['issSubmitted'])) {
    // Verify csrf token
    if (!hash_equals($_SESSION['__csrf'], $_POST['__csrf'])) {
        header('Location: ' . $_SERVER['PHP_SELF']);
        exit;
    }

    $id = htmlspecialchars($_POST['id']);
    $name = htmlspecialchars($_POST['name']);
    $price = htmlspecialchars($_POST['price']);
    $img = htmlspecialchars($_POST['image']);

    $productData = [
        'id' => $id,
        'name' => $name,
        'price' => $price,
        'image' => $img,
    ];

    $_SESSION['cart'][$id] = [$productData];
}
print_r($_SESSION['cart']);
require './header.php';
?>

<!-- Our Products Start -->
<div class="container-fluid product py-5">
    <div class="container py-5">
        <div class="tab-class">
            <div class="row g-5 mb-5">
                <div class="col-lg-4 text-start wow fadeInLeft" data-wow-delay="0.1s">
                    <h1>Our Products</h1>
                </div>
            </div>
            <div class="tab-content">
                <div id="tab-1" class="tab-pane fade show p-0 active">
                    <div class="row g-4">
                        <?php if ($products): ?>
                        <?php foreach ($products as $product): ?>
                        <div class="col-md-6 col-lg-4 col-xl-3">
                            <div class="product-item rounded wow fadeInUp shadow-sm" data-wow-delay="0.1s">
                                <form method="post" action="<?= htmlspecialchars($_SERVER['PHP_SELF']) ?>">
                                    <input type="hidden" name="id" value="<?= htmlspecialchars($product['id']) ?>">
                                    <input type="hidden" name="name"
                                        value="<?= htmlspecialchars($product['product_name']) ?>">
                                    <input type="hidden" name="price"
                                        value="<?= htmlspecialchars($product['product_price']) ?>">
                                    <input type="hidden" name="image"
                                        value="<?= htmlspecialchars($product['product_image']) ?>">
                                    <input type="hidden" name="__csrf"
                                        value="<?= htmlspecialchars($_SESSION['__csrf']) ?>">

                                    <div class="product-item-inner border rounded">
                                        <div class="product-item-inner-item p-3">
                                            <img src="./admin/product/<?= htmlspecialchars($product['product_image']) ?>"
                                                class="img-fluid w-100" style="height: 200px; object-fit: contain;"
                                                alt="<?= htmlspecialchars($product['product_name']) ?>">
                                        </div>
                                        <div class="text-center rounded-bottom p-4 pt-0">
                                            <div class="text-muted small mb-2">SmartPhone</div>
                                            <h5 class="mb-2" style="height: 48px; overflow: hidden;">
                                                <?= htmlspecialchars($product['product_name']) ?></h5>
                                            <span
                                                class="text-primary fs-5 fw-bold">$<?= number_format($product['product_price'], 2) ?></span>
                                        </div>
                                    </div>

                                    <div class="product-item-add text-center p-3">
                                        <button name="issSubmitted" type="submit"
                                            class="btn btn-primary border-secondary rounded-pill py-2 px-4 w-100">
                                            <i class="fas fa-shopping-cart me-2"></i> Add To Cart
                                        </button>
                                    </div>
                                </form>
                                <!-- END FORM -->

                            </div>
                        </div>
                        <?php endforeach; ?>
                        <?php else: ?>
                        <div class="col-12">
                            <div class="alert alert-danger">No Product Found!</div>
                        </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>
</div>
</div>
<!-- Our Products End -->

<?php require './footer.php'; ?>