<?php
require "config/db.php";

$search = trim($_GET['search'] ?? '');

$sql = "SELECT projects.*, users.full_name, categories.category_name 
        FROM projects 
        LEFT JOIN users ON projects.user_id = users.id 
        LEFT JOIN categories ON projects.category_id = categories.id";

if (!empty($search)) {
    $search_clean = $conn->real_escape_string($search);
    $sql .= " WHERE projects.title LIKE '%$search_clean%' 
              OR projects.tech_stack LIKE '%$search_clean%' 
              OR users.full_name LIKE '%$search_clean%'";
}

$sql .= " ORDER BY projects.id DESC";
$projects = $conn->query($sql);

if ($projects && $projects->num_rows > 0) {
    while ($p = $projects->fetch_assoc()) {
        $date = date("d/m/Y", strtotime($p['created_at']));
        echo '<div class="col-md-4 mb-3">
                <div class="card h-100 shadow-sm border-0">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <span class="badge bg-primary">' . htmlspecialchars($p['category_name'] ?? 'General') . '</span>
                            <small class="text-muted"><i class="bi bi-clock"></i> ' . $date . '</small>
                        </div>
                        <h5 class="card-title fw-bold text-dark">' . htmlspecialchars($p['title']) . '</h5>
                        <p class="card-text small text-secondary mb-2">' . htmlspecialchars($p['description'] ?? 'No description provided.') . '</p>
                        <p class="card-text mb-1"><strong>Tech Stack:</strong> <span class="badge bg-light text-dark border">' . htmlspecialchars($p['tech_stack'] ?? '-') . '</span></p>
                    </div>
                    <div class="card-footer bg-white border-0 d-flex justify-content-between align-items-center">
                        <small class="text-muted"><i class="bi bi-person-fill"></i> ' . htmlspecialchars($p['full_name'] ?? 'Unknown') . '</small>';
        if (!empty($p['file_path'])) {
            echo '<a href="download.php?id=' . $p['id'] . '" class="btn btn-sm btn-outline-primary"><i class="bi bi-download"></i> Report</a>';
        }
        echo '      </div>
                </div>
              </div>';
    }
} else {
    echo '<div class="col-12"><div class="alert alert-warning text-center">No matching projects found.</div></div>';
}
?>