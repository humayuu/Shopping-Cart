<?php
session_start();
require './admin/config.php';


// Generate CSRF Token
if (empty($_SESSION['__csrf'])) $_SESSION['__csrf'] = bin2hex(random_bytes(32));

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['isSubmitted'])) {
    // Verify CSRF Token
    if (!isset($_SESSION['__csrf']) || !hash_equals($_SESSION['__csrf'], $_POST['__csrf'])) {
        header('Location' . $_SERVER['PHP_SELF']);
        exit;
    }

    $table = 'user_tbl';
    $email = htmlspecialchars($_POST['email']);
    $password = htmlspecialchars($_POST['password']);
    $role = 'customer';
    $redirect = './index.php';

    $database->attempt($table, $email, $password, $redirect, $role);
}
?>
<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Shopping Cart</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;600&display=swap" rel="stylesheet">
</head>

<body class="bg-light">
    <div class="container">
        <div class="row justify-content-center align-items-center min-vh-100">
            <div class="col-12 col-md-6 col-lg-5">
                <div class="card shadow-lg border-0 rounded-4">
                    <div class="card-body p-4 p-sm-5">
                        <div class="text-center mb-4">
                            <div class="bg-primary bg-gradient text-white rounded-circle d-inline-flex align-items-center justify-content-center mb-3"
                                style="width: 80px; height: 80px;">
                                <i class="bi bi-newspaper fs-1"></i>
                            </div>
                            <h4 class="fw-semibold text-dark">Shopping Cart</h4>
                        </div>
                        <form method="post" action="<?= htmlspecialchars($_SERVER['PHP_SELF']) ?>">
                            <input type="hidden" name="__csrf" value="<?= htmlspecialchars($_SESSION['__csrf']) ?>">
                            <div class="mb-3">
                                <label for="username" class="form-label fw-medium">Email</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light border-end-0">
                                        <i class="bi bi-person-fill text-muted"></i>
                                    </span>
                                    <input type="email" class="form-control border-start-0 ps-0" id="email" name="email"
                                        placeholder="Enter your email" autofocus>
                                </div>
                            </div>

                            <div class="mb-3">
                                <label for="password" class="form-label fw-medium">Password</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light border-end-0">
                                        <i class="bi bi-lock-fill text-muted"></i>
                                    </span>
                                    <input type="password" class="form-control border-start-0 ps-0" id="password"
                                        name="password" placeholder="Enter your password">
                                </div>
                            </div>

                            <div class="d-flex justify-content-between align-items-center mb-4">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" id="rememberMe" name="remember">
                                    <label class="form-check-label" for="rememberMe">
                                        Remember Me
                                    </label>
                                </div>
                                <a href="#" class="text-primary text-decoration-none small">Forgot
                                    Password?</a>
                            </div>

                            <div class="d-grid">
                                <button type="submit" name="isSubmitted" class="btn btn-primary btn-lg">
                                    <i class="bi bi-box-arrow-in-right me-2"></i>Log in
                                </button>
                                <a href="./register.php" class="btn btn-outline-dark mt-2 fs-6">
                                    <i class="bi bi-box-arrow-in-right me-2"></i>Create an Account
                                </a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>

</html>
<?php $conn = null; ?>