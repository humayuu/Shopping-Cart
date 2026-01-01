<?php
require '../guard.php';

$sl = 1;
$table = 'user_tbl';
$rows = '*';
$join = null;
$where = null;
$order = 'id DESC';
$limit = 5;
$offset = null;


if (isset($_GET['page'])) {
    $pageNo = $_GET['page'];
} else {
    $pageNo = 1;
}

$offset = ($pageNo - 1) * $limit;

$users = $database->selectAll($table, $rows, $join, $where, $order, $limit, $offset);
require '../layout/header.php';
?>

<main class="page-content">
    <!--breadcrumb-->
    <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
        <div class="breadcrumb-title pe-3">Shopping<span class="text-primary">Cart</span></div>
    </div>
    <div class="card">
        <div class="card-header py-3">
            <h6 class="mb-0">All Users</h6>
            <div class="card-body">
                <div class="row">
                    <div class="col-12 col-lg-12 d-flex">
                        <div class="card border shadow-none w-100">
                            <div class="card-body">
                                <table class="table">
                                    <thead>
                                        <tr>
                                            <th scope="col">#</th>
                                            <th scope="col">Fullname</th>
                                            <th scope="col">Email</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($users as $user): ?>
                                        <tr>
                                            <th scope="row"><?= $sl++ ?></th>
                                            <td><?= htmlspecialchars($user['user_fullname']) ?></td>
                                            <td><?= htmlspecialchars($user['user_email']) ?></td>
                                        </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                                <!-- Pagination -->
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