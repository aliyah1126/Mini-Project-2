<?php
require "../config/db.php";
require "../includes/auth.php";
require_role("admin");

$student_count = $conn->query("SELECT COUNT(*) as cnt FROM users WHERE role='student'")->fetch_assoc()['cnt'];
$category_count = $conn->query("SELECT COUNT(*) as cnt FROM categories")->fetch_assoc()['cnt'];
$submission_count = $conn->query("SELECT COUNT(*) as cnt FROM projects")->fetch_assoc()['cnt'];

$recent = $conn->query("SELECT projects.*, users.full_name, categories.category_name FROM projects LEFT JOIN users ON projects.user_id = users.id LEFT JOIN categories ON projects.category_id = categories.id ORDER BY projects.id DESC LIMIT 5");

$page_title = "Admin Dashboard";
$asset_prefix = "../";
include "../includes/header.php";
?>

<div class="mb-4">
    <h2>Admin Overview</h2>
    <p class="text-muted">Lecturer & Coordinator Evaluation Portal</p>
</div>

<div class="row g-4 mb-4">
    <div class="col-md-4">
        <div class="card bg-primary text-white p-3 border-0 shadow-sm">
            <h6 class="text-uppercase">Students</h6>
            <h2 class="fw-bold"><?= $student_count ?></h2>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card bg-success text-white p-3 border-0 shadow-sm">
            <h6 class="text-uppercase">Categories</h6>
            <h2 class="fw-bold"><?= $category_count ?></h2>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card bg-info text-white p-3 border-0 shadow-sm">
            <h6 class="text-uppercase">Submissions</h6>
            <h2 class="fw-bold"><?= $submission_count ?></h2>
        </div>
    </div>
</div>

<div class="card border-0 shadow-sm p-4">
    <h4 class="fw-bold mb-3">Recent Submissions</h4>
    <div class="table-responsive">
        <table class="table table-hover align-middle">
            <thead class="table-light">
                <tr>
                    <th>Student Name</th>
                    <th>Title</th>
                    <th>Category</th>
                    <th>Date</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($r = $recent->fetch_assoc()): ?>
                    <tr>
                        <td class="fw-bold"><?= htmlspecialchars($r['full_name']) ?></td>
                        <td><?= htmlspecialchars($r['title']) ?></td>
                        <td><span class="badge bg-secondary"><?= htmlspecialchars($r['category_name'] ?? 'General') ?></span></td>
                        <td><?= date("d M Y", strtotime($r['created_at'])) ?></td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>
</div>

<?php include "../includes/footer.php"; ?>