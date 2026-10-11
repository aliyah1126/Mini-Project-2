<?php
require "../config/db.php";
require "../includes/auth.php";
require_role("student");

$user_id = $_SESSION["user_id"];
$count_res = $conn->query("SELECT COUNT(*) as total FROM projects WHERE user_id = $user_id");
$total_projects = $count_res->fetch_assoc()['total'] ?? 0;

$page_title = "Student Dashboard";
$asset_prefix = "../";
include "../includes/header.php";
?>

<div class="row g-4 mb-4">
    <div class="col-md-6">
        <div class="card bg-primary text-white p-4 border-0 shadow-sm">
            <h3>Welcome back, <?= htmlspecialchars($_SESSION["full_name"]) ?>!</h3>
            <p class="mb-0">You have submitted <strong><?= $total_projects ?></strong> project(s) to the portfolio system.</p>
        </div>
    </div>
    <div class="col-md-6 d-flex align-items-center justify-content-end">
        <a href="submit.php" class="btn btn-lg btn-success fw-bold shadow-sm"><i class="bi bi-plus-circle"></i> + Submit New Project</a>
    </div>
</div>

<div class="text-center mt-5">
    <a href="../index.php" class="btn btn-outline-primary"><i class="bi bi-globe"></i> Explore Public Project Showcase Directory</a>
</div>

<?php include "../includes/footer.php"; ?>