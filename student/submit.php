<?php
require "../config/db.php";
require "../includes/auth.php";
require_role("student");

$error = "";
$success = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $title = trim($_POST["title"] ?? "");
    $category_id = intval($_POST["category_id"] ?? "");
    $tech_stack = trim($_POST["tech_stack"] ?? "");
    $description = trim($_POST["description"] ?? "");
    $user_id = $_SESSION["user_id"];

    // Pengesahan Server-side
    if (empty($title) || empty($category_id)) {
        $error = "Please fill in all required fields (Project Title and Category 2).";
    } else {
        $file_path = NULL;
        // Proses Muat Naik Fail (File Upload)
        if (isset($_FILES["document"]) && $_FILES["document"]["error"] === UPLOAD_ERR_OK) {
            $file_tmp = $_FILES["document"]["tmp_name"];
            $file_name = basename($_FILES["document"]["name"]);
            $file_size = $_FILES["document"]["size"];
            $file_ext = strtolower(pathinfo($file_name, PATHINFO_EXTENSION));

            $allowed_exts = ["pdf", "docx", "txt", "zip"];
            $max_size = 5 * 1024 * 1024; // 5MB

            if (!in_array($file_ext, $allowed_exts)) {
                $error = "Invalid file type. Only PDF, DOCX, TXT, and ZIP files are allowed.";
            } elseif ($file_size > $max_size) {
                $error = "File size exceeds the maximum limit of 5MB.";
            } else {
                $upload_dir = "../uploads/";
                if (!is_dir($upload_dir)) {
                    mkdir($upload_dir, 0777, true);
                }
                $new_file_name = time() . "_" . preg_replace("/[^a-zA-Z0-9_\.-]/", "_", $file_name);
                $destination = $upload_dir . $new_file_name;

                if (move_uploaded_file($file_tmp, $destination)) {
                    $file_path = "uploads/" . $new_file_name;
                } else {
                    $error = "Failed to upload file.";
                }
            }
        }

        if (empty($error)) {
            $stmt = $conn->prepare("INSERT INTO projects (user_id, category_id, title, description, tech_stack, file_path) VALUES (?, ?, ?, ?, ?, ?)");
            $stmt->bind_param("iissss", $user_id, $category_id, $title, $description, $tech_stack, $file_path);

            if ($stmt->execute()) {
                header("Location: my_submissions.php?status=success");
                exit;
            } else {
                $error = "Failed to submit project entry.";
            }
        }
    }
}

$categories = $conn->query("SELECT * FROM categories ORDER BY category_name ASC");

$page_title = "Submit Portfolio Project";
$asset_prefix = "../";
include "../includes/header.php";
?>

<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card p-4 border-0 shadow-sm">
            <h4 class="fw-bold text-primary mb-3"><i class="bi bi-upload"></i> Submit Portfolio Project</h4>

            <?php if ($error): ?><div class="alert alert-danger"><?= htmlspecialchars($error) ?></div><?php endif; ?>

            <form method="POST" enctype="multipart/form-data" novalidate>
                <div class="mb-3">
                    <label class="form-label fw-bold">Project Title <span class="text-danger">*</span></label>
                    <input type="text" name="title" class="form-control" placeholder="e.g. FYP" required>
                </div>

                <div class="mb-3">
                    <label class="form-label fw-bold">Category <span class="text-danger">*</span></label>
                    <select name="category_id" class="form-select" required>
    <option value="">-- Select Category --</option>
    <option value="Web Application">Web Application</option>
    <option value="Mobile Application">Mobile Application</option>
    <option value="System Administration">System Administration</option>
    <option value="Database & Analytics">Database & Analytics</option>
    <option value="Networking & Security">Networking & Security</option>

    <!-- Pilihan dari Database -->
    <?php if ($categories && $categories->num_rows > 0): ?>
        <?php while ($cat = $categories->fetch_assoc()): ?>
            <option value="<?= $cat['id'] ?>"><?= htmlspecialchars($cat['category_name']) ?></option>
        <?php endwhile; ?>
    <?php endif; ?>
</select>
                        <?php while ($cat = $categories->fetch_assoc()): ?>
                            <option value="<?= $cat['id'] ?>"><?= htmlspecialchars($cat['category_name']) ?></option>
                        <?php endwhile; ?>
                    </select>
                </div>

                <div class="mb-3">
                    <label class="form-label fw-bold">Tech Stack (Comma Separated)</label>
                    <input type="text" name="tech_stack" class="form-control" placeholder="e.g. PHP, MySQL, Bootstrap 5, AJAX">
                </div>

                <div class="mb-3">
                    <label class="form-label fw-bold">Project Description</label>
                    <textarea name="description" class="form-control" rows="4" placeholder="Brief explanation of your project..."></textarea>
                </div>

                <div class="mb-3">
                    <label class="form-label fw-bold">Upload Documentation (PDF, DOCX, TXT, or ZIP - Max 5MB)</label>
                    <input type="file" name="document" class="form-control">
                </div>

                <button type="submit" class="btn btn-primary fw-bold w-100"><i class="bi bi-send"></i> Submit Entry</button>
            </form>
        </div>
    </div>
</div>

<?php include "../includes/footer.php"; ?>