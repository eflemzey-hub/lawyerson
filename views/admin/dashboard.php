<?php
require_once __DIR__ . '/../../includes/admin_helpers.php';

$stats = [
    'confirmed' => 0,
    'pending' => 0,
    'completed' => 0,
    'total_bookings' => 0,
    'unread_messages' => 0,
    'total_messages' => 0,
    'blog_published' => 0,
    'blog_draft' => 0,
    'attorneys' => 0,
    'media' => 0,
];
$appointments = [];
$upcomingAppointments = [];
$recentClients = [];
$recentActivity = [];
$monthlyBookings = [];
$practiceBreakdown = [];
$nextHearing = null;

try {
    if (isset($pdo)) {
        $stmt = $pdo->query("SELECT status, COUNT(*) AS count FROM appointments GROUP BY status");
        foreach ($stmt->fetchAll(PDO::FETCH_ASSOC) as $row) {
            $status = $row['status'] ?? '';
            $count = (int) $row['count'];
            $stats['total_bookings'] += $count;
            if ($status === 'confirmed') {
                $stats['confirmed'] = $count;
            } elseif ($status === 'pending') {
                $stats['pending'] = $count;
            } elseif ($status === 'completed') {
                $stats['completed'] = $count;
            }
        }

        $stats['unread_messages'] = (int) $pdo->query("SELECT COUNT(*) FROM messages WHERE status = 'unread'")->fetchColumn();
        $stats['total_messages'] = (int) $pdo->query("SELECT COUNT(*) FROM messages")->fetchColumn();

        $postCounts = $pdo->query("SELECT status, COUNT(*) AS count FROM posts GROUP BY status")->fetchAll(PDO::FETCH_KEY_PAIR);
        $stats['blog_published'] = (int) ($postCounts['published'] ?? 0);
        $stats['blog_draft'] = (int) ($postCounts['draft'] ?? 0);

        $stats['attorneys'] = (int) $pdo->query("SELECT COUNT(*) FROM attorneys")->fetchColumn();
        $stats['media'] = (int) $pdo->query("SELECT COUNT(*) FROM media")->fetchColumn();

        $stmtAppt = $pdo->query("SELECT * FROM appointments ORDER BY created_at DESC");
        $appointments = $stmtAppt->fetchAll(PDO::FETCH_ASSOC);

        $stmtUpcoming = $pdo->query("
            SELECT * FROM appointments
            WHERE status IN ('pending', 'confirmed')
              AND appointment_date IS NOT NULL
              AND appointment_date <> '0000-00-00'
              AND appointment_date >= CURDATE()
            ORDER BY appointment_date ASC
            LIMIT 5
        ");
        $upcomingAppointments = $stmtUpcoming->fetchAll(PDO::FETCH_ASSOC);

        if (empty($upcomingAppointments)) {
            $stmtUpcoming = $pdo->query("
                SELECT * FROM appointments
                WHERE status IN ('pending', 'confirmed')
                ORDER BY created_at DESC
                LIMIT 5
            ");
            $upcomingAppointments = $stmtUpcoming->fetchAll(PDO::FETCH_ASSOC);
        }

        $stmtClients = $pdo->query("
            SELECT name, email, practice_area, COUNT(*) AS case_count, MAX(created_at) AS last_seen
            FROM appointments
            GROUP BY email, name, practice_area
            ORDER BY last_seen DESC
            LIMIT 5
        ");
        $recentClients = $stmtClients->fetchAll(PDO::FETCH_ASSOC);

        $stmtMonths = $pdo->query("
            SELECT DATE_FORMAT(created_at, '%b') AS label, COUNT(*) AS total
            FROM appointments
            WHERE created_at >= DATE_SUB(CURDATE(), INTERVAL 6 MONTH)
            GROUP BY YEAR(created_at), MONTH(created_at)
            ORDER BY YEAR(created_at), MONTH(created_at)
        ");
        $monthlyBookings = $stmtMonths->fetchAll(PDO::FETCH_ASSOC);

        $stmtPractice = $pdo->query("
            SELECT practice_area, COUNT(*) AS total
            FROM appointments
            GROUP BY practice_area
            ORDER BY total DESC
            LIMIT 5
        ");
        $practiceBreakdown = $stmtPractice->fetchAll(PDO::FETCH_ASSOC);

        foreach (array_slice($appointments, 0, 4) as $a) {
            $recentActivity[] = [
                'type' => 'booking',
                'title' => $a['name'] . ' requested a consultation',
                'meta' => admin_format_practice_area($a['practice_area'] ?? ''),
                'time' => $a['created_at'] ?? '',
                'status' => $a['status'] ?? 'pending',
            ];
        }

        $stmtMsgs = $pdo->query("SELECT first_name, last_name, subject, created_at FROM messages ORDER BY created_at DESC LIMIT 3");
        foreach ($stmtMsgs->fetchAll(PDO::FETCH_ASSOC) as $m) {
            $recentActivity[] = [
                'type' => 'message',
                'title' => trim($m['first_name'] . ' ' . $m['last_name']) . ' sent a message',
                'meta' => $m['subject'] ?? 'Contact form',
                'time' => $m['created_at'] ?? '',
                'status' => 'new',
            ];
        }

        if (isset($postModel)) {
            foreach (array_slice($postModel->getAll(false), 0, 2) as $p) {
                $recentActivity[] = [
                    'type' => 'blog',
                    'title' => 'Article: ' . ($p['title'] ?? 'Untitled'),
                    'meta' => ucfirst($p['status'] ?? 'draft'),
                    'time' => $p['created_at'] ?? '',
                    'status' => $p['status'] ?? 'draft',
                ];
            }
        }

        usort($recentActivity, function ($a, $b) {
            return strtotime($b['time'] ?? '0') <=> strtotime($a['time'] ?? '0');
        });
        $recentActivity = array_slice($recentActivity, 0, 8);

        if (!empty($upcomingAppointments[0])) {
            $nextHearing = $upcomingAppointments[0];
        }
    }
} catch (Exception $e) {}

$chartLabels = [];
$chartValues = [];
foreach ($monthlyBookings as $row) {
    $chartLabels[] = $row['label'];
    $chartValues[] = (int) $row['total'];
}
if (empty($chartLabels)) {
    $chartLabels = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun'];
    $chartValues = [0, 0, 0, 0, 0, 0];
}

$tableRedirect = 'index.php?page=admin_dashboard#bookings';
$dashboardAppointments = array_slice($appointments, 0, 8);
?>

<style>
.dash-top { display: flex; flex-wrap: wrap; justify-content: space-between; align-items: flex-start; gap: 1rem; margin-bottom: 1.75rem; }
.dash-top h3 { font-weight: 800; color: var(--admin-primary); margin: 0; letter-spacing: -0.5px; }
.dash-top p { color: var(--admin-text-muted); margin: 0.35rem 0 0; font-size: 0.9rem; }
.stat-card-link { text-decoration: none; color: inherit; display: block; height: 100%; }
.stat-card {
    background: #fff; border: 1px solid var(--admin-border); border-radius: 16px;
    padding: 1.15rem 1.25rem; display: flex; align-items: center; gap: 1rem;
    transition: transform 0.2s, box-shadow 0.2s, border-color 0.2s; height: 100%;
}
.stat-card-link:hover .stat-card { transform: translateY(-3px); box-shadow: 0 12px 28px rgba(15,23,42,0.08); border-color: #d4af37; }
.stat-icon { width: 48px; height: 48px; border-radius: 14px; display: flex; align-items: center; justify-content: center; font-size: 1.15rem; flex-shrink: 0; }
.stat-icon.green { background: #e6f4ea; color: #1e8e3e; }
.stat-icon.orange { background: #feefe3; color: #e37400; }
.stat-icon.blue { background: #e8f0fe; color: #1a73e8; }
.stat-icon.red { background: #fce8e6; color: #d93025; }
.stat-icon.gold { background: #fff8eb; color: #b48c1e; }
.stat-icon.navy { background: #e8ecf4; color: #0f172a; }
.stat-info h6 { font-size: 0.78rem; text-transform: uppercase; letter-spacing: 0.04em; color: var(--admin-text-muted); margin-bottom: 0.2rem; font-weight: 700; }
.stat-info h3 { font-weight: 800; margin: 0; font-size: 1.65rem; color: var(--admin-primary); }
.panel-card { background: #fff; border: 1px solid var(--admin-border); border-radius: 16px; padding: 1.35rem 1.5rem; height: 100%; }
.panel-card, .dash-top, .stat-card, .client-row, .appt-row, .activity-row { min-width: 0; }
.panel-card canvas { max-width: 100% !important; }
.modern-main .row > * { min-width: 0; }
.panel-title { font-weight: 700; font-size: 1rem; margin-bottom: 1rem; color: var(--admin-primary); display: flex; align-items: center; justify-content: space-between; gap: 0.5rem; }
.panel-title a { font-size: 0.78rem; font-weight: 600; color: #b48c1e; text-decoration: none; }
.hearing-day { font-size: 3rem; font-weight: 800; line-height: 1; color: #1a73e8; }
.hearing-month { font-weight: 700; color: var(--admin-text-muted); text-transform: uppercase; letter-spacing: 0.06em; font-size: 0.85rem; }
.client-row, .appt-row, .activity-row { display: flex; align-items: center; gap: 0.85rem; padding: 0.65rem 0; border-bottom: 1px solid #f1f5f9; }
.client-row:last-child, .appt-row:last-child, .activity-row:last-child { border-bottom: 0; }
.avatar-circle { width: 40px; height: 40px; border-radius: 12px; display: flex; align-items: center; justify-content: center; font-weight: 700; font-size: 0.85rem; color: #fff; background: linear-gradient(135deg, #0f172a, #334155); flex-shrink: 0; }
.quick-actions { display: flex; flex-wrap: wrap; gap: 0.5rem; }
.quick-actions .btn { font-size: 0.8rem; font-weight: 600; border-radius: 10px; }
.admin-table thead th { font-size: 0.7rem; text-transform: uppercase; letter-spacing: 0.05em; color: var(--admin-text-muted); background: #f8fafc; border-bottom: 1px solid var(--admin-border); }
.admin-table { min-width: 760px; }
.table-responsive { max-width: 100%; contain: paint; }
#bookings { scroll-margin-top: 1rem; }
@media (max-width: 767px) {
    .dash-top { margin-bottom: 1rem; }
    .dash-top h3 { font-size: 1.25rem; }
    .quick-actions { width: 100%; display: grid; grid-template-columns: 1fr; }
    .stat-card { padding: 1rem; }
    .stat-info h3 { font-size: 1.35rem; }
    .panel-card { padding: 1rem; border-radius: 14px; }
    .client-row, .appt-row, .activity-row { align-items: flex-start; }
    .panel-title { align-items: flex-start; }
    #bookings > .p-4 { padding: 1rem !important; }
}
@media (max-width: 575px) {
    #bookings .table-responsive { overflow: visible; }
    #bookings .admin-table { min-width: 0; width: 100%; }
    #bookings .admin-table thead { display: none; }
    #bookings .admin-table,
    #bookings .admin-table tbody,
    #bookings .admin-table tr,
    #bookings .admin-table td {
        display: block;
        width: 100%;
    }
    #bookings .admin-table tr {
        padding: 0.9rem;
        border-bottom: 1px solid #e5e7eb;
    }
    #bookings .admin-table td {
        padding: 0.35rem 0 !important;
        border: 0;
        text-align: left !important;
        white-space: normal !important;
    }
    #bookings .admin-table td.text-nowrap form {
        margin-top: 0.35rem;
    }
}
</style>

<?php if (!empty($_SESSION['admin_flash'])): ?>
<div class="alert alert-success border-0 rounded-3 mb-4"><i class="fas fa-check-circle me-2"></i> <?= htmlspecialchars($_SESSION['admin_flash']) ?></div>
<?php unset($_SESSION['admin_flash']); endif; ?>

<div class="dash-top">
    <div>
        <h3>Firm Command Center</h3>
        <p>Live overview of bookings, cases, messages, and site content — manage everything from one place.</p>
    </div>
    <div class="quick-actions">
        <a href="index.php" target="_blank" class="btn btn-outline-secondary btn-sm"><i class="fas fa-external-link-alt me-1"></i> View Site</a>
        <a href="index.php?page=admin_blog" class="btn btn-outline-secondary btn-sm"><i class="fas fa-pen me-1"></i> New Article</a>
        <a href="index.php?page=admin_settings" class="btn btn-sm text-white" style="background:#0f172a;"><i class="fas fa-cog me-1"></i> Settings</a>
    </div>
</div>

<!-- Stat cards -->
<div class="row g-3 mb-4">
    <div class="col-xl-2 col-lg-4 col-md-6">
        <a href="#bookings" class="stat-card-link">
            <div class="stat-card">
                <div class="stat-icon green"><i class="fas fa-check-circle"></i></div>
                <div class="stat-info"><h6>Confirmed</h6><h3><?= str_pad($stats['confirmed'], 2, '0', STR_PAD_LEFT) ?></h3></div>
            </div>
        </a>
    </div>
    <div class="col-xl-2 col-lg-4 col-md-6">
        <a href="#bookings" class="stat-card-link">
            <div class="stat-card">
                <div class="stat-icon orange"><i class="fas fa-clock"></i></div>
                <div class="stat-info"><h6>Pending</h6><h3><?= str_pad($stats['pending'], 2, '0', STR_PAD_LEFT) ?></h3></div>
            </div>
        </a>
    </div>
    <div class="col-xl-2 col-lg-4 col-md-6">
        <a href="index.php?page=admin_appointments" class="stat-card-link">
            <div class="stat-card">
                <div class="stat-icon blue"><i class="fas fa-calendar-check"></i></div>
                <div class="stat-info"><h6>Total Bookings</h6><h3><?= str_pad($stats['total_bookings'], 2, '0', STR_PAD_LEFT) ?></h3></div>
            </div>
        </a>
    </div>
    <div class="col-xl-2 col-lg-4 col-md-6">
        <a href="index.php?page=admin_messages" class="stat-card-link">
            <div class="stat-card">
                <div class="stat-icon red"><i class="fas fa-envelope"></i></div>
                <div class="stat-info"><h6>Unread Msgs</h6><h3><?= str_pad($stats['unread_messages'], 2, '0', STR_PAD_LEFT) ?></h3></div>
            </div>
        </a>
    </div>
    <div class="col-xl-2 col-lg-4 col-md-6">
        <a href="index.php?page=admin_blog" class="stat-card-link">
            <div class="stat-card">
                <div class="stat-icon gold"><i class="fas fa-file-alt"></i></div>
                <div class="stat-info"><h6>Published Posts</h6><h3><?= str_pad($stats['blog_published'], 2, '0', STR_PAD_LEFT) ?></h3></div>
            </div>
        </a>
    </div>
    <div class="col-xl-2 col-lg-4 col-md-6">
        <a href="index.php?page=admin_attorneys" class="stat-card-link">
            <div class="stat-card">
                <div class="stat-icon navy"><i class="fas fa-user-tie"></i></div>
                <div class="stat-info"><h6>Attorneys</h6><h3><?= str_pad($stats['attorneys'], 2, '0', STR_PAD_LEFT) ?></h3></div>
            </div>
        </a>
    </div>
</div>

<!-- Charts + hearing -->
<div class="row g-4 mb-4">
    <div class="col-lg-4">
        <div class="panel-card">
            <h5 class="panel-title">Booking Status</h5>
            <div style="height: 210px; position: relative;">
                <canvas id="caseOverviewChart"></canvas>
            </div>
        </div>
    </div>
    <div class="col-lg-3">
        <div class="panel-card d-flex flex-column">
            <h5 class="panel-title">Next Consultation</h5>
            <?php if ($nextHearing): ?>
                <?php $hearingTs = strtotime($nextHearing['appointment_date'] ?? ''); ?>
                <div class="hearing-day"><?= $hearingTs ? date('d', $hearingTs) : '—' ?></div>
                <p class="hearing-month mb-3"><?= $hearingTs ? date('F Y', $hearingTs) : 'Schedule TBD' ?></p>
                <h6 class="fw-bold mb-1"><?= htmlspecialchars($nextHearing['name']) ?></h6>
                <p class="small text-muted mb-2"><?= htmlspecialchars(admin_format_practice_area($nextHearing['practice_area'] ?? '')) ?></p>
                <p class="small text-muted mb-0 flex-grow-1"><?= htmlspecialchars(mb_strimwidth($nextHearing['case_details'] ?? 'No case details provided.', 0, 120, '…')) ?></p>
                <a href="#bookings" class="btn btn-sm btn-outline-primary-law rounded-0 mt-3 align-self-start">Manage booking</a>
            <?php else: ?>
                <p class="text-muted small mb-0">No upcoming consultations. New requests from the website will appear here.</p>
            <?php endif; ?>
        </div>
    </div>
    <div class="col-lg-5">
        <div class="panel-card">
            <h5 class="panel-title">Consultation Requests (6 mo.)</h5>
            <div style="height: 220px; position: relative;">
                <canvas id="caseRequestChart"></canvas>
            </div>
        </div>
    </div>
</div>

<!-- Lists row -->
<div class="row g-4 mb-4">
    <div class="col-lg-4">
        <div class="panel-card">
            <h5 class="panel-title">Recent Clients <a href="#bookings">View all</a></h5>
            <?php if (empty($recentClients)): ?>
                <p class="text-muted small mb-0">No client data yet.</p>
            <?php else: ?>
                <?php foreach ($recentClients as $client): ?>
                    <?php $initials = strtoupper(substr($client['name'], 0, 1) . substr(strstr($client['name'], ' ') ?: '', 1, 1)); ?>
                    <div class="client-row">
                        <div class="avatar-circle"><?= htmlspecialchars(trim($initials) ?: '?') ?></div>
                        <div class="flex-grow-1 min-width-0">
                            <div class="fw-bold small"><?= htmlspecialchars($client['name']) ?></div>
                            <div class="text-muted" style="font-size:0.75rem;"><?= htmlspecialchars(admin_format_practice_area($client['practice_area'] ?? '')) ?></div>
                        </div>
                        <span class="badge bg-light text-dark border"><?= (int) $client['case_count'] ?> <?= (int) $client['case_count'] === 1 ? 'case' : 'cases' ?></span>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </div>
    <div class="col-lg-4">
        <div class="panel-card">
            <h5 class="panel-title">Upcoming Appointments <a href="index.php?page=admin_appointments">Full list</a></h5>
            <?php if (empty($upcomingAppointments)): ?>
                <p class="text-muted small mb-0">No pending or confirmed appointments.</p>
            <?php else: ?>
                <?php foreach ($upcomingAppointments as $appt): ?>
                    <?php $initials = strtoupper(substr($appt['name'], 0, 1)); ?>
                    <div class="appt-row">
                        <div class="avatar-circle" style="background:linear-gradient(135deg,#b48c1e,#d4af37);"><?= htmlspecialchars($initials) ?></div>
                        <div class="flex-grow-1">
                            <div class="fw-bold small"><?= htmlspecialchars($appt['name']) ?></div>
                            <div class="text-muted" style="font-size:0.75rem;">
                                <i class="far fa-calendar me-1"></i><?= admin_format_date($appt['appointment_date'] ?? '') ?>
                            </div>
                        </div>
                        <span class="badge <?= admin_status_badge_class($appt['status'] ?? '') ?>"><?= htmlspecialchars($appt['status'] ?? '') ?></span>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </div>
    <div class="col-lg-4">
        <div class="panel-card">
            <h5 class="panel-title">Firm Pulse <span class="text-muted fw-normal" style="font-size:0.75rem;">Live feed</span></h5>
            <?php if (empty($recentActivity)): ?>
                <p class="text-muted small mb-0">Activity from bookings, messages, and blog will show here.</p>
            <?php else: ?>
                <?php foreach ($recentActivity as $item): ?>
                    <?php
                    $icon = 'fa-bell';
                    $color = '#64748b';
                    if ($item['type'] === 'booking') { $icon = 'fa-calendar-plus'; $color = '#1a73e8'; }
                    if ($item['type'] === 'message') { $icon = 'fa-envelope'; $color = '#d93025'; }
                    if ($item['type'] === 'blog') { $icon = 'fa-file-alt'; $color = '#b48c1e'; }
                    ?>
                    <div class="activity-row">
                        <div class="stat-icon" style="width:36px;height:36px;border-radius:10px;background:<?= $color ?>18;color:<?= $color ?>;"><i class="fas <?= $icon ?>"></i></div>
                        <div class="flex-grow-1 min-width-0">
                            <div class="small fw-semibold text-truncate"><?= htmlspecialchars($item['title']) ?></div>
                            <div class="text-muted" style="font-size:0.72rem;"><?= htmlspecialchars($item['meta']) ?> · <?= admin_format_date($item['time'] ?? '', 'M d, h:i A') ?></div>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </div>
</div>

<!-- Practice areas mini chart -->
<?php if (!empty($practiceBreakdown)): ?>
<div class="row g-4 mb-4">
    <div class="col-12">
        <div class="panel-card">
            <h5 class="panel-title">Top Practice Areas (from bookings)</h5>
            <div class="row g-2">
                <?php foreach ($practiceBreakdown as $pb): ?>
                <div class="col-md-4 col-lg-2">
                    <div class="border rounded-3 p-3 text-center h-100" style="background:#f8fafc;">
                        <div class="fw-bold text-primary-law small"><?= htmlspecialchars(admin_format_practice_area($pb['practice_area'] ?? '')) ?></div>
                        <div class="display-6 fw-bold text-gold mb-0" style="font-size:1.5rem;"><?= (int) $pb['total'] ?></div>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
        </div>
    </div>
</div>
<?php endif; ?>

<!-- Embedded bookings -->
<section id="bookings" class="panel-card p-0 overflow-hidden">
    <div class="p-4 border-bottom d-flex flex-wrap justify-content-between align-items-center gap-2" style="background:#f8fafc;">
        <div>
            <h5 class="panel-title mb-1">Consultation Bookings</h5>
            <p class="small text-muted mb-0">Confirm, complete, or remove requests submitted from your website forms.</p>
        </div>
        <a href="index.php?page=admin_appointments" class="btn btn-sm btn-outline-primary-law rounded-0">Open full booking page</a>
    </div>
    <?php
    $compactTable = false;
    require __DIR__ . '/partials/appointments_table.php';
    ?>
    <?php if (count($appointments) > 8): ?>
    <div class="p-3 border-top text-center bg-white">
        <a href="index.php?page=admin_appointments" class="small fw-bold text-decoration-none" style="color:#b48c1e;">View all <?= count($appointments) ?> bookings <i class="fas fa-arrow-right ms-1"></i></a>
    </div>
    <?php endif; ?>
</section>

<script>
document.addEventListener('DOMContentLoaded', function () {
    if (typeof Chart === 'undefined') return;

    const pending = <?= (int) $stats['pending'] ?>;
    const confirmed = <?= (int) $stats['confirmed'] ?>;
    const completed = <?= (int) $stats['completed'] ?>;

    new Chart(document.getElementById('caseOverviewChart'), {
        type: 'doughnut',
        data: {
            labels: ['Pending', 'Confirmed', 'Completed'],
            datasets: [{
                data: [pending, confirmed, completed],
                backgroundColor: ['#f29900', '#1e8e3e', '#1a73e8'],
                borderWidth: 0,
                cutout: '72%'
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: { position: 'bottom', labels: { boxWidth: 10, usePointStyle: true, font: { size: 11 } } }
            }
        }
    });

    const ctx = document.getElementById('caseRequestChart').getContext('2d');
    const gradient = ctx.createLinearGradient(0, 0, 0, 220);
    gradient.addColorStop(0, 'rgba(99, 179, 237, 0.55)');
    gradient.addColorStop(1, 'rgba(99, 179, 237, 0)');

    new Chart(ctx, {
        type: 'line',
        data: {
            labels: <?= json_encode($chartLabels) ?>,
            datasets: [{
                label: 'Requests',
                data: <?= json_encode($chartValues) ?>,
                borderColor: '#3182ce',
                backgroundColor: gradient,
                borderWidth: 2,
                fill: true,
                tension: 0.35,
                pointRadius: 3,
                pointBackgroundColor: '#3182ce'
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: { legend: { display: false } },
            scales: {
                x: { grid: { display: false }, ticks: { font: { size: 11 } } },
                y: { beginAtZero: true, ticks: { stepSize: 1, precision: 0 }, grid: { borderDash: [4, 4] } }
            }
        }
    });
});
</script>
