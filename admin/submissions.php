<?php
require "../config/db.php";
require "../includes/auth.php";
require_role("admin");

$sql = "SELECT projects.*, users.full_name, users.email, categories.category_name 
        FROM projects 
        LEFT JOIN users ON projects.user_id = users.id 
        LEFT JOIN categories ON projects.category_id = categories.id 
        ORDER BY projects.id DESC";
$submissions = $conn->query($sql);
$total = $submissions ? $submissions->num_rows : 0;

$page_title = "All Student Submissions";
$asset_prefix = "../";
include "../includes/header.php";
?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <h2>All Student Submissions</h2>
    <span class="badge bg-primary fs-6">Total: <?= $total ?> Projects</span>
</div>

<div class="card border-0 shadow-sm p-3">
    <div class="table-responsive">
        <table class="table table-hover align-middle">
            <thead class="table-dark">
                <tr>
                    <th>Student Name</th>
                    <th>Project Title</th>
                    <th>Category</th>
                    <th>Tech Stack</th>
                    <th>Submitted File</th>
                    <th>Date Submitted</th>
                </tr>
            </thead>
            <tbody>
                <?php if ($submissions && $submissions->num_rows > 0): ?>
                    <?php while ($s = $submissions->fetch_assoc()): ?>
                        <tr>
                            <td>
                                <div class="fw-bold"><?= htmlspecialchars($s['full_name']) ?></div>
                                <small class="text-muted"><?= htmlspecialchars($s['email']) ?></small>
                            </td>
                            <td class="fw-bold text-primary"><?= htmlspecialchars($s['title']) ?></td>
                            <td><span class="badge bg-secondary"><?= htmlspecialchars($s['category_name'] ?? 'General') ?></span></td>
                            <td><?= htmlspecialchars($s['tech_stack'] ?? '-') ?></td>
                            <td>
                                <?php if (!empty($s['file_path'])): ?>
                                    <a href="../download.php?id=<?= $s['id'] ?>" class="btn btn-sm btn-outline-primary"><i class="bi bi-download"></i> File</a>
                                <?php else: ?>
                                    <span class="text-muted small">None</span>
                                <?php endif; ?>
                            </td>
                            <td><?= date("d M Y, h:i A", strtotime($s['created_at'])) ?></td>
                        </tr>
                    <?php endwhile; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="6" class="text-center text-muted">No student submissions found.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<?php include "../includes/footer.php"; ?>