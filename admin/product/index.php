<?php
require '../config.php';

$sl = 1;
$table = 'product_tbl';
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


$products = $database->selectAll($table, $rows, $join, $where, $order, $limit, $offset);



require 'header.php';

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
                                            <th scope="col">Image</th>
                                            <th scope="col">Name</th>
                                            <th scope="col">Code</th>
                                            <th scope="col">Price</th>
                                            <th scope="col">Created</th>
                                            <th scope="col">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($products as $product): ?>
                                            <tr>
                                                <th scope="row"><?= $sl++ ?></th>
                                                <td><img src="<?= htmlspecialchars($product['product_image']) ?>"
                                                        width="120" alt="Product image"></td>
                                                <td><?= htmlspecialchars($product['product_name']) ?></td>
                                                <td><?= htmlspecialchars($product['product_code']) ?></td>
                                                <td><?= '$' .  number_format(htmlspecialchars($product['product_price']), 2) ?>
                                                </td>
                                                <td><?= date('d-m-Y', strtotime($product['created_at'])) ?></td>
                                                <td>
                                                    <div class="d-flex align-items-center gap-3 fs-6">
                                                        <!-- Edit Button -->
                                                        <a href="edit.php?id=<?= $product['id'] ?>" class="text-primary"
                                                            data-bs-toggle="tooltip" data-bs-placement="bottom"
                                                            title="Edit info">
                                                            <i class="bi bi-pencil-fill"></i>
                                                        </a>

                                                        <!-- Delete Button -->
                                                        <a href="delete.php?id=<?= $product['id'] ?>" class="text-danger"
                                                            data-bs-toggle="tooltip" data-bs-placement="bottom"
                                                            title="Delete">
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
</main>

<?php require 'footer.php'; ?>