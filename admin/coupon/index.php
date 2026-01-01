<?php
require '../guard.php';

$sl = 1;
$table = 'coupon_tbl';
$rows = '*';
$join = null;
$where = null;
$order = 'id DESC';
$limit = 5;



if (isset($_GET['page'])) {
    $pageNo = $_GET['page'];
} else {
    $pageNo = 1;
}

$offset = ($pageNo - 1) * $limit;


$coupons = $database->selectAll($table, $rows, $join, $where, $order, $limit, $offset);
$today = strtotime(date('Y-m-d'));

require '../layout/header.php';
?>

<main class="page-content">
    <!--breadcrumb-->
    <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
        <div class="breadcrumb-title pe-3">Shopping<span class="text-primary">Cart</span></div>
    </div>
    <div class="card">
        <div class="card-header py-3">
            <h6 class="mb-0">All Product</h6>

            <div class="card-body">
                <div class="row">
                    <div class="col-12 col-lg-12 d-flex">
                        <div class="card border shadow-none w-100">
                            <div class="card-body">
                                <table class="table">
                                    <thead>
                                        <tr>
                                            <th scope="col">#</th>
                                            <th scope="col">Coupon Name</th>
                                            <th scope="col">Discount</th>
                                            <th scope="col">Validity</th>
                                            <th scope="col">Status</th>
                                            <th scope="col">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($coupons as $coupon): ?>
                                            <tr>
                                                <th scope="row"><?= $sl++ ?></th>
                                                <td><?= htmlspecialchars($coupon['coupon_name']) ?></td>
                                                <td><?= htmlspecialchars($coupon['coupon_discount']) ?>%</td>
                                                <td><?= htmlspecialchars($coupon['coupon_validity']) ?></td>
                                                <td>
                                                    <?php if (strtotime($coupon['coupon_validity']) < $today): ?>
                                                        <span class="badge bg-danger fs-6">Expired</span>
                                                    <?php else: ?>
                                                        <span class="badge bg-success fs-6">Active</span>
                                                    <?php endif; ?>


                                                </td>
                                                <td>
                                                    <div class="d-flex align-items-center gap-2 fs-4">

                                                        <!-- Edit Button -->
                                                        <a href="edit.php?id=<?= htmlspecialchars($coupon['id']) ?>"
                                                            class="text-primary" data-bs-toggle="tooltip"
                                                            data-bs-placement="bottom" title="Edit info">
                                                            <i class="bi bi-pencil-fill"></i>
                                                        </a>

                                                        <!-- Delete Button -->
                                                        <a onclick="return confirm('Are you sure you want to delete this coupon?')"
                                                            href="delete.php?id=<?= htmlspecialchars($coupon['id']) ?>"
                                                            class="text-danger" data-bs-toggle="tooltip"
                                                            data-bs-placement="bottom" title="Delete">
                                                            <i class="bi bi-trash-fill"></i>
                                                        </a>
                                                    </div>
                                                </td>
                                            </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                                <?php
                                $database->paginator($table, $pageNo, $limit);
                                ?>
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