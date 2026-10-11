<?php
require "config/db.php";
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$page_title = "Project Showcase Directory";
$asset_prefix = "";
include "includes/header.php";
?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h2>Project Showcase Directory</h2>
        <p class="text-muted">Explore student portfolio submissions and FYP projects.</p>
    </div>
    <?php if (isset($_SESSION["user_id"]) && $_SESSION["role"] === "student"): ?>
        <a href="student/submit.php" class="btn btn-primary"><i class="bi bi-plus-lg"></i> Submit New Project</a>
    <?php endif; ?>
</div>

<!-- Live Search Input -->
<div class="mb-4">
    <div class="input-group">
        <span class="input-group-text bg-white"><i class="bi bi-search"></i></span>
        <input type="text" id="searchInput" class="form-control" placeholder="Live search by project title, tech stack, or student name...">
    </div>
</div>

<!-- Container Hasil Projek (Dimuatkan secara dinamik / AJAX) -->
<div id="projectResults" class="row">
    <!-- Diisi melalui JavaScript -->
</div>

<!-- Skrip AJAX untuk Live Search -->
<script>
document.addEventListener("DOMContentLoaded", function() {
    const searchInput = document.getElementById("searchInput");
    const resultsContainer = document.getElementById("projectResults");

    function fetchProjects(query = "") {
        fetch("fetch_projects.php?search=" + encodeURIComponent(query))
            .then(response => response.text())
            .then(data => {
                resultsContainer.innerHTML = data;
            })
            .catch(error => console.error("Error loading projects:", error));
    }

    // Muat turun data asal semasa mula buka halaman
    fetchProjects();

    // Event listener untuk carian masa nyata (live search)
    searchInput.addEventListener("input", function() {
        fetchProjects(this.value);
    });
});
</script>

<?php include "includes/footer.php"; ?>