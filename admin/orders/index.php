<?php
require '../guard.php';


require '../layout/header.php';
?>

<main class="page-content">
    <!--breadcrumb-->
    <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
        <div class="breadcrumb-title pe-3">Shopping<span class="text-primary">Cart</span></div>
    </div>
    <div class="card">
        <div class="card-header py-3">
            <h6 class="mb-0">All Orders</h6>
            <div class="card-body">
                <div class="row">
                    <div class="col-12 col-lg-12 d-flex">
                        <div class="card border shadow-none w-100">
                            <div class="card-body">
                                <table class="table">
                                    <thead>
                                        <tr>
                                            <th scope="col">#</th>
                                            <th scope="col">User</th>
                                            <th scope="col">Order Item</th>
                                            <th scope="col">Quantity</th>
                                            <th scope="col">Total Amount</th>
                                        </tr>
                                    </thead>
                                    <tbody>

                                    </tbody>
                                </table>
                                <!-- Pagination -->

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