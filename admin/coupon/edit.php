<?php
require '../guard.php';


// Generate CSRF Token
if (empty($_SESSION['__csrf'])) {
    $_SESSION['__csrf'] = bin2hex(random_bytes(32));
}


$id = htmlspecialchars($_GET['id']);
$table = 'coupon_tbl';
$rows = '*';
$join = null;
$where = "id = $id";
$order = null;
$limit = null;
$redirect = 'index.php';


if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['issSubmitted'])) {
    // Verify CSRF Token
    if (!isset($_SESSION['__csrf']) || !hash_equals($_SESSION['__csrf'], $_POST['__csrf'])) {
        header('Location: ' . $_SERVER['PHP_SELF']);
        exit;
    }

    $cid = htmlspecialchars($_POST['id']);
    $table = 'coupon_tbl';
    $redirect = 'index.php';
    $couponName = htmlspecialchars($_POST['name']);
    $couponExpiry = htmlspecialchars($_POST['expiry']);
    $couponDiscount = htmlspecialchars($_POST['discount']);

    // Validation 
    $isValidate = $database->validate([
        'coupon_name' => $couponName,
        'coupon_discount' => $couponDiscount,
        'coupon_validity' => $couponExpiry,
    ]);

    $params = [
        'coupon_name' => $couponName,
        'coupon_discount' => $couponDiscount,
        'coupon_validity' => $couponExpiry,
    ];

    if ($isValidate) {
        $database->update($table, $params, "id = $cid", $redirect);
    }
}


// Fetch Record for specific product
$coupon = $database->select($table, $rows, $join, $where, $order, $limit);

require '../layout/header.php';
?>

<main class="page-content">
    <!--breadcrumb-->
    <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
        <div class="breadcrumb-title pe-3">Shopping<span class="text-primary">Cart</span></div>
    </div>
    <div class="card">
        <div class="card-header py-3">
            <h6 class="mb-0">Edit Coupon</h6>

            <div class="card-body">
                <div class="row">
                    <div class="col-12 col-lg-8 mx-auto d-flex">
                        <div class="card border shadow-none w-100">
                            <div class="card-body">
                                <?php if ($database->getErrors()): ?>
                                    <?php foreach ($database->getErrors() as $error): ?>
                                        <div class="alert alert-danger"><?= $error ?></div>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                                <form method="post" action="<?= htmlspecialchars($_SERVER['PHP_SELF']) ?> ?>"
                                    class="row g-3">
                                    <input type="hidden" name="__csrf"
                                        value="<?= htmlspecialchars($_SESSION['__csrf']) ?>">
                                    <input type="hidden" name="id" value="<?= htmlspecialchars($coupon['id']) ?>">

                                    <div class="col-12">
                                        <label class="form-label">Coupon Validity</label>
                                        <input type="date" class="form-control" name="expiry" id="expiry"
                                            value="<?= htmlspecialchars($coupon['coupon_validity']) ?>">
                                    </div>
                                    <div class="col-12">
                                        <label class="form-label">Coupon Name</label>
                                        <input type="text" class="form-control" name="name" placeholder="Coupon name"
                                            value="<?= htmlspecialchars($coupon['coupon_name']) ?>">
                                    </div>
                                    <div class="col-12">
                                        <label class="form-label">Coupon Discount</label>
                                        <input type="number" class="form-control" name="discount"
                                            placeholder="Coupon Discount" max='100'
                                            value="<?= htmlspecialchars($coupon['coupon_discount']) ?>">
                                    </div>
                                    <div class="col-12">
                                        <div>
                                            <button name="issSubmitted" class="btn btn-primary">Save Changes</button>
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
<script>
    document.getElementById("expiry").min = new Date().getFullYear() + "-" + parseInt(new Date().getMonth() + 1) + "-" +
        new Date().getDate()
</script>
<?php require '../layout/footer.php'; ?>