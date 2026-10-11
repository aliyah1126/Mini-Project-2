<?php
require "../config/db.php";
require "../includes/auth.php";
require_role("student");

$user_id = $_SESSION["user_id"];

// Proses Padam Projek Sendiri
if (isset($_GET['delete_id'])) {
    $del_id = intval($_GET['delete_id']);
    $st = $conn->prepare("SELECT file_path FROM projects WHERE id = ? AND user_id = ?");
    $st->bind_param("ii", $del_id, $user_id);
    $st->execute();
    $res = $st->get_result()->fetch_assoc();
    if ($res) {
        if (!empty($res['file_path']) && file_exists("../" . $res['file_path'])) {
            unlink("../" . $res['file_path']);
        }
        $del = $conn->prepare("DELETE FROM projects WHERE id = ? AND user_id = ?");
        $del->bind_param("ii", $del_id, $user_id);
        $del->execute();
    }
    header("Location: my_submissions.php");
    exit;
}

$stmt = $conn->prepare("SELECT projects.*, categories.category_name FROM projects LEFT JOIN categories ON projects.category_id = categories.id WHERE projects.user_id = ? ORDER BY projects.id DESC");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$projects = $stmt->get_result();

$page_title = "My Portfolio Submissions";
$asset_prefix = "../";
include "../includes/header.php";
?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <h2>My Portfolio Submissions</h2>
    <a href="submit.php" class="btn btn-primary"><i class="bi bi-plus-lg"></i> Add Submission</a>
</div>

<div class="card border-0 shadow-sm p-3">
    <div class="table-responsive">
        <table class="table table-hover align-middle">
            <thead class="table-light">
                <tr>
                    <th>Project Title</th>
                    <th>Category</th>
                    <th>Tech Stack</th>
                    <th>File</th>
                    <th>Date</th>
                    <th class="text-center">Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php if ($projects && $projects->num_rows > 0): ?>
                    <?php while ($p = $projects->fetch_assoc()): ?>
                        <tr>
                            <td class="fw-bold text-primary"><?= htmlspecialchars($p['title']) ?></td>
                            <td><span class="badge bg-secondary"><?= htmlspecialchars($p['category_name'] ?? 'General') ?></span></td>
                            <td><?= htmlspecialchars($p['tech_stack'] ?? '-') ?></td>
                            <td>
                                <?php if (!empty($p['file_path'])): ?>
                                    <a href="../download.php?id=<?= $p['id'] ?>" class="btn btn-sm btn-outline-secondary"><i class="bi bi-download"></i> Download</a>
                                <?php else: ?>
                                    <span class="text-muted small">No file</span>
                                <?php endif; ?>
                            </td>
                            <td><?= date("d M Y", strtotime($p['created_at'])) ?></td>
                            <td class="text-center">
                                <a href="my_submissions.php?delete_id=<?= $p['id'] ?>" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure you want to delete this submission?');"><i class="bi bi-trash"></i></a>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="6" class="text-center text-muted">No portfolio submissions found.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<?php include "../includes/footer.php"; ?>