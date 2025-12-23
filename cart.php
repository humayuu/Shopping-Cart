<?php
session_start();

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

                    <?php foreach ($_SESSION['cart'] as $product): ?>
                    <tr>
                        <th scope="row">
                            <p class="mb-0 py-4"><img width="100"
                                    src="./admin/product/<?= htmlspecialchars($product['image']) ?>" alt=""></p>
                        </th>
                        <td>
                            <p class="mb-0 py-4"><?= $product['name'] ?></p>
                        </td>
                        <td>
                            <p class="mb-0 py-4"><?= number_format($product['price'], 2) ?> $</p>
                        </td>
                        <td>
                            <div class="input-group quantity py-4" style="width: 100px;">
                                <div class="input-group-btn">
                                    <button class="btn btn-sm btn-minus rounded-circle bg-light border">
                                        <i class="fa fa-minus"></i>
                                    </button>
                                </div>
                                <input type="text" class="form-control form-control-sm text-center border-0"
                                    value="<?= $product['quantity'] ?>">
                                <div class="input-group-btn">
                                    <button class="btn btn-sm btn-plus rounded-circle bg-light border">
                                        <i class="fa fa-plus"></i>
                                    </button>
                                </div>
                            </div>
                        </td>
                        <td>
                            <p class="mb-0 py-4"><?= number_format($product['subTotal'], 2) ?> $</p>
                        </td>
                        <td class="py-4">
                            <button class="btn btn-md rounded-circle bg-light border">
                                <i class="fa fa-times text-danger"></i>
                            </button>
                        </td>
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