<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
$prefix = $asset_prefix ?? "";
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $page_title ?? "Portfolio Showcase" ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
</head>
<body class="bg-light d-flex flex-column min-vh-100">
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <div class="container">
            <a class="navbar-brand fw-bold" href="<?= $prefix ?>index.php"><i class="bi bi-mortarboard-fill"></i> Portfolio Showcase</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse justify-content-end" id="navbarNav">
                <ul class="navbar-nav align-items-center">
                    <?php if (isset($_SESSION["user_id"])): ?>
                        <?php if ($_SESSION["role"] === "admin"): ?>
                            <li class="nav-item"><a class="nav-link" href="<?= $prefix ?>admin/dashboard.php"><i class="bi bi-speedometer2"></i> Admin Dashboard</a></li>
                            <li class="nav-item"><a class="nav-link" href="<?= $prefix ?>admin/categories.php"><i class="bi bi-folder"></i> Categories</a></li>
                            <li class="nav-item"><a class="nav-link" href="<?= $prefix ?>admin/submissions.php"><i class="bi bi-file-earmark-text"></i> All Submissions</a></li>
                        <?php else: ?>
                            <li class="nav-item"><a class="nav-link" href="<?= $prefix ?>student/dashboard.php"><i class="bi bi-speedometer2"></i> Dashboard</a></li>
                            <li class="nav-item"><a class="nav-link" href="<?= $prefix ?>student/submit.php"><i class="bi bi-upload"></i> Submit Project</a></li>
                            <li class="nav-item"><a class="nav-link" href="<?= $prefix ?>student/my_submissions.php"><i class="bi bi-folder2-open"></i> My Portfolio</a></li>
                        <?php endif; ?>
                        <li class="nav-item ms-3">
                            <span class="badge bg-secondary p-2"><?= htmlspecialchars($_SESSION["full_name"]) ?> (<?= ucfirst($_SESSION["role"]) ?>)</span>
                        </li>
                        <li class="nav-item ms-2">
                            <a class="btn btn-outline-danger btn-sm" href="<?= $prefix ?>logout.php"><i class="bi bi-box-arrow-right"></i> Logout</a>
                        </li>
                    <?php else: ?>
                        <li class="nav-item"><a class="nav-link" href="<?= $prefix ?>login.php"><i class="bi bi-box-arrow-in-right"></i> Login</a></li>
                        <li class="nav-item"><a class="nav-link" href="<?= $prefix ?>register.php"><i class="bi bi-person-plus"></i> Register</a></li>
                    <?php endif; ?>
                </ul>
            </div>
        </div>
    </nav>
    <div class="container my-4 flex-grow-1">