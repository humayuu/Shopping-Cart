<?php
session_start();

// Generate CSRF token
if (empty($_SESSION['__csrf'])) {
    $_SESSION['__csrf'] = bin2hex(random_bytes(32));
}



// Delete specific cart data from session
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete'])) {
    // Verify csrf token
    if (!isset($_SESSION['__csrf']) || !hash_equals($_SESSION['__csrf'], $_POST['__csrf'])) {
        header('Location: ' . $_SERVER['PHP_SELF']);
        exit;
    }
    $id = (int) $_POST['productId'];

    if (isset($_SESSION['cart'][$id])) {
        unset($_SESSION['cart'][$id]);
    }

    header('Location: ' . $_SERVER['PHP_SELF']);
    exit;
}


// Increment or Decrement in cart
if ($_SERVER['REQUEST_METHOD'] === 'POST' && (isset($_POST['minus']) || isset($_POST['plus']))) {
    // Verify csrf token
    if (!isset($_SESSION['__csrf']) || !hash_equals($_SESSION['__csrf'], $_POST['__csrf'])) {
        header('Location: ' . $_SERVER['PHP_SELF']);
        exit;
    }

    $id = (int) $_POST['id'];

    if (isset($_POST['minus']) && $_SESSION['cart'][$id]['quantity'] > 1) {

        $_SESSION['cart'][$id]['quantity']--;
        $_SESSION['cart'][$id]['subTotal'] = $_SESSION['cart'][$id]['price'] * $_SESSION['cart'][$id]['quantity'];
    }


    if (isset($_POST['plus'])) {
        $_SESSION['cart'][$id]['quantity']++;
        $_SESSION['cart'][$id]['subTotal'] = $_SESSION['cart'][$id]['price'] * $_SESSION['cart'][$id]['quantity'];
    }


    header('Location: ' . $_SERVER['PHP_SELF']);
    exit;
}


require './header.php';
?>

<!-- Cart Page Start -->
<div class="container-fluid py-5">
    <div class="container py-5">
        <div class="table-responsive">
            <?php if (!empty($_SESSION['cart'])): ?>
            <table class="table">
                <thead>
                    <tr>
                        <th scope="col">image</th>
                        <th scope="col">Name</th>
                        <th scope="col">Price</th>
                        <th scope="col">Quantity</th>
                        <th scope="col">Total</th>
                        <th scope="col">Handle</th>
                    </tr>
                </thead>
                <tbody>

                    <?php foreach ($_SESSION['cart'] as $id => $product):
                        ?>
                    <tr>
                        <th scope="row">
                            <p class="mb-0 py-4"><img width="100"
                                    src="./admin/product/<?= htmlspecialchars($product['image']) ?>" alt=""></p>
                        </th>
                        <td>
                            <p class="mb-0 py-4"><?= htmlspecialchars($product['name']) ?></p>
                        </td>
                        <td>
                            <p class="mb-0 py-4"><?= number_format($product['price'], 2) ?> $</p>
                        </td>

                        <form method="post" action="<?= htmlspecialchars($_SERVER['PHP_SELF']) ?>">
                            <input type="hidden" name="__csrf" value="<?= htmlspecialchars($_SESSION['__csrf']) ?>">
                            <input type="hidden" name="id" value="<?= $id ?>">
                            <td>
                                <div class="input-group quantity py-4" style="width: 100px;">
                                    <div class="input-group-btn">
                                        <button type="submit" name="minus"
                                            class="btn btn-sm btn-minus rounded-circle bg-light border">
                                            <i class="fa fa-minus"></i>
                                        </button>
                                    </div>

                                    <input type="text" class="form-control form-control-sm text-center border-0"
                                        value="<?= $product['quantity'] ?>">

                                    <div class="input-group-btn">
                                        <button type="submit" name="plus"
                                            class="btn btn-sm btn-plus rounded-circle bg-light border">
                                            <i class="fa fa-plus"></i>
                                        </button>
                                    </div>

                                </div>
                            </td>
                        </form>

                        <td>
                            <p class="mb-0 py-4"><?= number_format($product['subTotal'], 2) ?> $</p>
                        </td>
                        <form method="post" action="<?= htmlspecialchars($_SERVER['PHP_SELF']) ?>">
                            <input type="hidden" name="__csrf" value="<?= htmlspecialchars($_SESSION['__csrf']) ?>">
                            <input type="hidden" name="productId" value="<?= $id ?>">
                            <td class="py-4">
                                <button name="delete" class="btn btn-md rounded-circle bg-light border">
                                    <i class="fa fa-times text-danger"></i>
                                </button>
                            </td>
                        </form>

                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
            <?php else: ?>
            <div class="alert alert-danger">No Product Found</div>
            <?php endif; ?>
        </div>
        <div class="mt-5">
            <input type="text" class="border-0 border-bottom rounded me-5 py-3 mb-4" placeholder="Coupon Code">
            <button class="btn btn-primary rounded-pill px-4 py-3" type="button">Apply Coupon</button>
        </div>
        <div class="row g-4 justify-content-end">
            <div class="col-8"></div>
            <div class="col-sm-8 col-md-7 col-lg-6 col-xl-4">
                <div class="bg-light rounded">
                    <div class="py-4 mb-4 border-top border-bottom d-flex justify-content-between">
                        <h5 class="mb-0 ps-4 me-4 fs-3 mt-2">Total</h5>
                        <p class="mb-0 pe-4 fw-bold fs-3">$<?= number_format($total, 2) ?></p>
                    </div>
                    <button class="btn btn-primary rounded-pill px-4 py-3 text-uppercase mb-4 ms-4"
                        type="button">Proceed Checkout</button>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- Cart Page End -->

<?php require './footer.php'; ?>