<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($pageTitle ?? 'Admin Dashboard') ?></title>
    <?php if (!empty($siteFaviconUrl)): ?>
    <link rel="icon" href="<?= htmlspecialchars($siteFaviconUrl) ?>">
    <?php endif; ?>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        :root {
            --admin-bg: #f8f9fa;
            --admin-surface: #ffffff;
            --admin-text-main: #1e293b;
            --admin-text-muted: #64748b;
            --admin-primary: #0f172a;
            --admin-accent: #d4af37; /* Gold */
            --admin-border: #e2e8f0;
            --font-admin: 'Plus Jakarta Sans', sans-serif;
        }
        body {
            background-color: var(--admin-bg);
            font-family: var(--font-admin);
            color: var(--admin-text-main);
            overflow-x: hidden;
        }
        .min-width-0 {
            min-width: 0 !important;
        }
        .admin-wrapper {
            padding: 2.5rem 3rem;
            max-width: 1600px;
            margin: 0 auto;
            width: 100%;
        }
        .greeting-section {
            margin-bottom: 2.5rem;
        }
        .greeting-section h2 {
            font-weight: 700;
            color: var(--admin-primary);
            letter-spacing: -0.5px;
        }
        .greeting-section p {
            color: var(--admin-text-muted);
            font-size: 0.95rem;
        }
        .layout-container {
            display: flex;
            gap: 2.5rem;
            align-items: flex-start;
            min-width: 0;
        }
        /* Sidebar */
        .modern-sidebar {
            width: 280px;
            background: var(--admin-surface);
            border-radius: 24px;
            padding: 1.5rem;
            box-shadow: 0 4px 20px rgba(0,0,0,0.03);
            flex-shrink: 0;
            height: calc(100vh - 180px);
            position: sticky;
            top: 2.5rem;
            display: flex;
            flex-direction: column;
        }
        .user-profile {
            display: flex;
            align-items: center;
            gap: 1rem;
            padding-bottom: 1.5rem;
            border-bottom: 1px solid var(--admin-border);
            margin-bottom: 1.5rem;
        }
        .user-avatar {
            width: 48px;
            height: 48px;
            border-radius: 12px;
            background: var(--admin-primary);
            color: white;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 700;
            font-size: 1.2rem;
        }
        .user-profile-details {
            min-width: 0;
            flex: 1;
        }
        .user-profile-details small {
            display: block;
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: nowrap;
        }
        .security-trigger {
            display: inline-flex;
            align-items: center;
            gap: 0.4rem;
            margin-top: 0.65rem;
            padding: 0.42rem 0.65rem;
            border: 1px solid #e7d7a2;
            border-radius: 999px;
            background: #fff8eb;
            color: #9a7413;
            font-size: 0.72rem;
            font-weight: 800;
            line-height: 1;
            transition: all 0.2s ease;
        }
        .security-trigger:hover {
            background: #0f172a;
            border-color: #0f172a;
            color: #ffffff;
        }
        .security-modal .modal-content {
            border: 0;
            border-radius: 20px;
            overflow: hidden;
            box-shadow: 0 26px 70px rgba(15, 23, 42, 0.22);
        }
        .security-modal-header {
            display: flex;
            gap: 1rem;
            align-items: flex-start;
            padding: 1.35rem 1.5rem;
            color: #fff;
            background:
                radial-gradient(circle at top right, rgba(212, 175, 55, 0.35), transparent 34%),
                linear-gradient(135deg, #0f172a, #273449);
        }
        .security-modal-header h5 {
            margin: 0;
            color: #fff;
            font-family: var(--font-admin);
            font-weight: 800;
        }
        .security-modal-icon {
            width: 44px;
            height: 44px;
            border-radius: 14px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            color: #0f172a;
            background: #d4af37;
            flex-shrink: 0;
        }
        .security-field {
            border: 1px solid #dbe3ee;
            border-radius: 14px;
            background: #f8fafc;
            padding: 0.78rem 0.9rem;
        }
        .security-field label {
            margin-bottom: 0.4rem;
            color: #64748b;
            font-size: 0.72rem;
            font-weight: 800;
            letter-spacing: 0.04em;
            text-transform: uppercase;
        }
        .security-field input {
            padding: 0;
            border: 0;
            box-shadow: none !important;
            background: transparent;
            font-weight: 600;
        }
        .password-guidance {
            display: grid;
            gap: 0.45rem;
            margin-top: 0.35rem;
            color: #64748b;
            font-size: 0.78rem;
        }
        .password-guidance span {
            display: flex;
            align-items: center;
            gap: 0.45rem;
        }
        .password-guidance i {
            color: #1e8e3e;
        }
        .nav-link-modern {
            display: flex;
            align-items: center;
            gap: 1rem;
            padding: 0.875rem 1.25rem;
            color: var(--admin-text-muted);
            text-decoration: none;
            border-radius: 12px;
            font-weight: 600;
            transition: all 0.2s ease;
            margin-bottom: 0.25rem;
        }
        .nav-link-modern:hover {
            background: #f8fafc;
            color: var(--admin-primary);
        }
        .nav-link-modern.active {
            background: #fff8eb; /* Light gold background */
            color: #b48c1e; /* Darker gold */
        }
        .nav-link-modern i {
            font-size: 1.1rem;
            width: 24px;
            text-align: center;
        }
        
        /* Main Content */
        .modern-main {
            flex-grow: 1;
            min-width: 0;
            background: var(--admin-surface);
            border-radius: 24px;
            padding: 2.5rem;
            box-shadow: 0 4px 20px rgba(0,0,0,0.03);
            min-height: calc(100vh - 180px);
        }

        @media (max-width: 991px) {
            .admin-wrapper { padding: 1.5rem; }
            .greeting-section { margin-bottom: 1.25rem; }
            .layout-container { flex-direction: column; align-items: stretch; gap: 1.25rem; }
            .modern-sidebar { width: 100%; height: auto; position: static; padding: 1rem; border-radius: 18px; }
            .modern-sidebar nav { display: grid !important; grid-template-columns: repeat(2, minmax(0, 1fr)); gap: 0.35rem; }
            .nav-link-modern { margin: 0; padding: 0.8rem 0.9rem; }
            .nav-link-modern.mt-auto { margin-top: 0 !important; }
            .modern-main { padding: 1.5rem; }
        }
        @media (max-width: 575px) {
            .admin-wrapper { padding: 1rem; }
            .greeting-section h2 { font-size: 1.55rem; }
            .greeting-section p { font-size: 0.86rem; }
            .user-profile { align-items: flex-start; gap: 0.75rem; }
            .user-profile small { white-space: normal; word-break: break-word; }
            .modern-sidebar nav { grid-template-columns: 1fr; }
            .modern-main { padding: 1rem; border-radius: 18px; }
        }
    </style>
</head>
<body>

<div class="admin-wrapper">
    <div class="greeting-section">
        <?php
        if (!function_exists('admin_greeting')) {
            require_once __DIR__ . '/../../includes/admin_helpers.php';
        }
        ?>
        <h2><?= admin_greeting() ?>, <span style="color: #212529;">Admin</span></h2>
        <p>Here's a quick overview of your firm's performance.<br>Let's make today another successful day!</p>
    </div>

    <div class="layout-container">
        <!-- Sidebar -->
        <aside class="modern-sidebar">
            <div class="user-profile">
                <div class="user-avatar">
                    <?php $adminImage = isset($settingModel) ? $settingModel->get('admin_image', 'https://ui-avatars.com/api/?name=Admin&background=0D8ABC&color=fff&rounded=true') : 'https://ui-avatars.com/api/?name=Admin&background=0D8ABC&color=fff&rounded=true'; ?>
                    <img src="<?= htmlspecialchars($adminImage) ?>" alt="Admin" style="width: 100%; border-radius: 12px;">
                </div>
                <div class="user-profile-details">
                    <h6 class="mb-0 fw-bold">System Admin</h6>
                    <small class="text-muted">admin@lawyerson.com</small>
                    <button type="button" class="security-trigger" data-bs-toggle="modal" data-bs-target="#adminPasswordModal">
                        <i class="fas fa-lock"></i> Change Password
                    </button>
                </div>
            </div>
            
            <nav class="d-flex flex-column flex-grow-1">
                <a href="index.php?page=admin_dashboard" class="nav-link-modern <?= (isset($_GET['page']) && $_GET['page'] == 'admin_dashboard') ? 'active' : '' ?>">
                    <i class="fas fa-th-large"></i> Dashboard
                </a>
                <a href="index.php?page=admin_appointments" class="nav-link-modern <?= (isset($_GET['page']) && $_GET['page'] == 'admin_appointments') ? 'active' : '' ?>">
                    <i class="fas fa-calendar-check"></i> Booking
                    <?php
                    try {
                        if (isset($pdo)) {
                            $pendingBookings = (int) $pdo->query("SELECT COUNT(*) FROM appointments WHERE status = 'pending'")->fetchColumn();
                            if ($pendingBookings > 0) {
                                echo "<span class='badge bg-warning text-dark ms-auto rounded-pill'>$pendingBookings</span>";
                            }
                        }
                    } catch (Exception $e) {}
                    ?>
                </a>
                <a href="index.php?page=admin_blog" class="nav-link-modern <?= (isset($_GET['page']) && $_GET['page'] == 'admin_blog') ? 'active' : '' ?>">
                    <i class="fas fa-file-alt"></i> My Cases (Blog)
                </a>
                <a href="index.php?page=admin_attorneys" class="nav-link-modern <?= (isset($_GET['page']) && $_GET['page'] == 'admin_attorneys') ? 'active' : '' ?>">
                    <i class="fas fa-user-tie"></i> Attorneys
                </a>
                <a href="index.php?page=admin_media" class="nav-link-modern <?= (isset($_GET['page']) && $_GET['page'] == 'admin_media') ? 'active' : '' ?>">
                    <i class="fas fa-image"></i> Media
                </a>
                <a href="index.php?page=admin_messages" class="nav-link-modern <?= (isset($_GET['page']) && $_GET['page'] == 'admin_messages') ? 'active' : '' ?>">
                    <i class="fas fa-envelope"></i> Messages
                    <?php
                    try {
                        if(isset($pdo)) {
                            $msgCount = $pdo->query("SELECT COUNT(*) FROM messages WHERE status = 'unread'")->fetchColumn();
                            if($msgCount > 0) echo "<span class='badge bg-warning text-dark ms-auto rounded-pill'>$msgCount</span>";
                        }
                    } catch(Exception $e) {}
                    ?>
                </a>
                <a href="index.php?page=admin_settings" class="nav-link-modern <?= (isset($_GET['page']) && $_GET['page'] == 'admin_settings') ? 'active' : '' ?>">
                    <i class="fas fa-cog"></i> Settings
                </a>
                <a href="index.php" target="_blank" class="nav-link-modern mt-auto">
                    <i class="fas fa-external-link-alt"></i> View Site
                </a>
                <form action="index.php" method="POST" class="mt-2">
                    <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?>">
                    <input type="hidden" name="action" value="admin_logout">
                    <button type="submit" class="nav-link-modern text-danger border-0 bg-transparent w-100 text-start">
                        <i class="fas fa-sign-out-alt"></i> Logout
                    </button>
                </form>
            </nav>
        </aside>

        <!-- Main Content -->
        <main class="modern-main">
            <?php if (!empty($_SESSION['admin_security_flash'])): ?>
                <div class="alert alert-success border-0 rounded-3 mb-4">
                    <i class="fas fa-shield-alt me-2"></i> <?= htmlspecialchars($_SESSION['admin_security_flash']) ?>
                </div>
                <?php unset($_SESSION['admin_security_flash']); ?>
            <?php endif; ?>
            <?php if (!empty($_SESSION['admin_security_error'])): ?>
                <div class="alert alert-danger border-0 rounded-3 mb-4">
                    <i class="fas fa-exclamation-triangle me-2"></i> <?= htmlspecialchars($_SESSION['admin_security_error']) ?>
                </div>
                <?php unset($_SESSION['admin_security_error']); ?>
            <?php endif; ?>
            <?= $content ?>
        </main>
    </div>
</div>

<div class="modal fade security-modal" id="adminPasswordModal" tabindex="-1" aria-labelledby="adminPasswordModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="security-modal-header">
                <div class="security-modal-icon"><i class="fas fa-shield-alt"></i></div>
                <div class="min-width-0 flex-grow-1">
                    <h5 id="adminPasswordModalLabel">Admin Security</h5>
                    <p class="mb-0 small opacity-75">Update the dashboard password for System Admin access.</p>
                </div>
                <button type="button" class="btn-close btn-close-white flex-shrink-0" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="index.php" method="POST" class="modal-body p-4">
                <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?>">
                <input type="hidden" name="action" value="update_admin_password">
                <input type="hidden" name="redirect" value="<?= htmlspecialchars($_SERVER['REQUEST_URI'] ?? 'index.php?page=admin_dashboard') ?>">

                <div class="security-field mb-3">
                    <label for="currentAdminPassword" class="form-label">Current Password</label>
                    <input type="password" name="current_password" id="currentAdminPassword" class="form-control" autocomplete="current-password" required>
                </div>

                <div class="security-field mb-3">
                    <label for="newAdminPassword" class="form-label">New Password</label>
                    <input type="password" name="new_password" id="newAdminPassword" class="form-control" autocomplete="new-password" minlength="8" required>
                </div>

                <div class="security-field mb-3">
                    <label for="confirmAdminPassword" class="form-label">Confirm New Password</label>
                    <input type="password" name="confirm_password" id="confirmAdminPassword" class="form-control" autocomplete="new-password" minlength="8" required>
                </div>

                <div class="password-guidance">
                    <span><i class="fas fa-check-circle"></i> Use at least 8 characters.</span>
                    <span><i class="fas fa-check-circle"></i> Avoid reusing your current dashboard password.</span>
                </div>

                <div class="d-flex flex-wrap justify-content-end gap-2 mt-4">
                    <button type="button" class="btn btn-outline-secondary rounded-3 fw-bold" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary-law rounded-3 fw-bold px-4">
                        <i class="fas fa-key me-1"></i> Update Password
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<!-- Chart.js for Dashboard -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</body>
</html>
