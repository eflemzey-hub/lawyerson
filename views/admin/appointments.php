<?php
require_once __DIR__ . '/../../includes/admin_helpers.php';

$appointments = [];
try {
    if (isset($pdo)) {
        $stmt = $pdo->query("SELECT * FROM appointments ORDER BY created_at DESC");
        $appointments = $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
} catch (Exception $e) {}

$tableRedirect = 'index.php?page=admin_appointments';
?>

<div class="d-flex flex-wrap justify-content-between align-items-start gap-2 mb-4">
    <div>
        <h3 class="fw-bold text-primary-law mb-1">Consultation Bookings</h3>
        <p class="text-muted small mb-0">All requests from the website consultation and contact forms.</p>
    </div>
    <a href="index.php?page=admin_dashboard" class="btn btn-sm btn-outline-secondary rounded-0"><i class="fas fa-th-large me-1"></i> Back to Dashboard</a>
</div>

<?php if (!empty($_SESSION['admin_flash'])): ?>
<div class="alert alert-success border-0 rounded-3 mb-4"><i class="fas fa-check-circle me-2"></i> <?= htmlspecialchars($_SESSION['admin_flash']) ?></div>
<?php unset($_SESSION['admin_flash']); endif; ?>

<div class="panel-card p-0 overflow-hidden" style="background:#fff;border:1px solid var(--admin-border);border-radius:16px;">
    <?php require __DIR__ . '/partials/appointments_table.php'; ?>
</div>
