<?php
$messages = [];
$filter = $_GET['filter'] ?? 'all';
$search = trim($_GET['search'] ?? '');

try {
    if (isset($pdo)) {
        $whereParts = [];
        $params = [];

        if ($search !== '') {
            $whereParts[] = "(first_name LIKE ? OR last_name LIKE ? OR email LIKE ? OR phone LIKE ? OR subject LIKE ? OR message LIKE ?)";
            $params = array_fill(0, 6, "%$search%");
        }

        if (in_array($filter, ['unread', 'read', 'replied'], true)) {
            $whereParts[] = "status = ?";
            $params[] = $filter;
        }

        $where = $whereParts ? 'WHERE ' . implode(' AND ', $whereParts) : '';
        $stmt = $pdo->prepare("SELECT * FROM messages $where ORDER BY created_at DESC");
        $stmt->execute($params);
        $messages = $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
} catch (Exception $e) {}

$messageCounts = ['all' => count($messages), 'unread' => 0, 'read' => 0, 'replied' => 0];
try {
    if (isset($pdo)) {
        $messageCounts['all'] = (int) $pdo->query("SELECT COUNT(*) FROM messages")->fetchColumn();
        $statusCounts = $pdo->query("SELECT status, COUNT(*) AS total FROM messages GROUP BY status")->fetchAll(PDO::FETCH_KEY_PAIR);
        foreach (['unread', 'read', 'replied'] as $status) {
            $messageCounts[$status] = (int) ($statusCounts[$status] ?? 0);
        }
    }
} catch (Exception $e) {}

function getStatusBadge($status) {
    $status = $status ?: 'read';
    $labels = [
        'unread' => ['New', 'danger'],
        'read' => ['Read', 'muted'],
        'replied' => ['Replied', 'success'],
    ];
    $badge = $labels[$status] ?? $labels['read'];
    return '<span class="message-status status-' . htmlspecialchars($status) . '"><span></span>' . $badge[0] . '</span>';
}

function messageInitials($firstName, $lastName) {
    $first = trim((string) $firstName);
    $last = trim((string) $lastName);
    return strtoupper(substr($first, 0, 1) . substr($last, 0, 1)) ?: '?';
}

function activeFilterUrl($status, $search) {
    $query = ['page' => 'admin_messages', 'filter' => $status];
    if ($search !== '') {
        $query['search'] = $search;
    }
    return '?' . http_build_query($query);
}
?>

<style>
.messages-page {
    color: var(--admin-text-main);
}
.messages-hero {
    display: grid;
    grid-template-columns: minmax(0, 1fr) auto;
    gap: 1rem;
    align-items: center;
    padding: 1.35rem;
    margin-bottom: 1.25rem;
    border: 1px solid #e5e7eb;
    border-radius: 20px;
    background:
        radial-gradient(circle at top right, rgba(212, 175, 55, 0.18), transparent 32%),
        linear-gradient(135deg, #ffffff 0%, #f8fafc 100%);
}
.messages-hero h3 {
    margin: 0;
    color: var(--admin-primary);
    font-weight: 800;
    letter-spacing: -0.03em;
}
.messages-hero p {
    margin: 0.35rem 0 0;
    color: var(--admin-text-muted);
    font-size: 0.9rem;
}
.message-kpis {
    display: grid;
    grid-template-columns: repeat(3, minmax(92px, 1fr));
    gap: 0.65rem;
}
.message-kpi {
    min-width: 92px;
    padding: 0.85rem 0.95rem;
    border: 1px solid #e5e7eb;
    border-radius: 14px;
    background: rgba(255,255,255,0.75);
    text-align: center;
}
.message-kpi span {
    display: block;
    color: var(--admin-text-muted);
    font-size: 0.72rem;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: 0.05em;
}
.message-kpi strong {
    display: block;
    color: var(--admin-primary);
    font-size: 1.4rem;
    line-height: 1.2;
}
.message-toolbar {
    display: grid;
    grid-template-columns: minmax(240px, 1fr) auto;
    gap: 0.85rem;
    align-items: center;
    padding: 0.85rem;
    margin-bottom: 1rem;
    border: 1px solid #e5e7eb;
    border-radius: 16px;
    background: #fff;
}
.message-search {
    display: flex;
    align-items: center;
    gap: 0.75rem;
    min-width: 0;
    padding: 0 0.85rem;
    border: 1px solid #dbe3ee;
    border-radius: 12px;
    background: #f8fafc;
}
.message-search i {
    color: #64748b;
}
.message-search input {
    width: 100%;
    min-width: 0;
    border: 0;
    outline: 0;
    background: transparent;
    padding: 0.8rem 0;
    color: var(--admin-text-main);
}
.message-filters {
    display: flex;
    flex-wrap: wrap;
    gap: 0.45rem;
    justify-content: flex-end;
}
.message-filter {
    display: inline-flex;
    align-items: center;
    gap: 0.45rem;
    min-height: 40px;
    padding: 0.55rem 0.8rem;
    border: 1px solid #dbe3ee;
    border-radius: 999px;
    color: #475569;
    background: #fff;
    font-size: 0.82rem;
    font-weight: 700;
    text-decoration: none;
    transition: all 0.2s ease;
}
.message-filter:hover,
.message-filter.active {
    border-color: #0f172a;
    color: #fff;
    background: #0f172a;
}
.message-filter small {
    padding: 0.05rem 0.4rem;
    border-radius: 999px;
    background: rgba(100,116,139,0.12);
    font-size: 0.68rem;
}
.message-filter.active small,
.message-filter:hover small {
    background: rgba(255,255,255,0.18);
}
.message-list {
    display: grid;
    gap: 0.85rem;
}
.message-card {
    position: relative;
    overflow: hidden;
    border: 1px solid #e5e7eb;
    border-radius: 18px;
    background: #fff;
    transition: transform 0.2s ease, box-shadow 0.2s ease, border-color 0.2s ease;
}
.message-card:before {
    content: "";
    position: absolute;
    inset: 0 auto 0 0;
    width: 4px;
    background: #cbd5e1;
}
.message-card.unread:before { background: #d93025; }
.message-card.read:before { background: #94a3b8; }
.message-card.replied:before { background: #1e8e3e; }
.message-card:hover {
    transform: translateY(-2px);
    border-color: #d4af37;
    box-shadow: 0 18px 42px rgba(15, 23, 42, 0.08);
}
.message-card-inner {
    display: grid;
    grid-template-columns: minmax(0, 1fr) auto;
    gap: 1rem;
    padding: 1.1rem 1.2rem 1.1rem 1.35rem;
}
.message-main {
    display: grid;
    grid-template-columns: auto minmax(0, 1fr);
    gap: 0.9rem;
    min-width: 0;
}
.message-avatar {
    width: 46px;
    height: 46px;
    border-radius: 14px;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    color: #fff;
    background: linear-gradient(135deg, #0f172a, #334155);
    font-size: 0.82rem;
    font-weight: 800;
    flex-shrink: 0;
}
.message-meta {
    display: flex;
    align-items: center;
    gap: 0.55rem;
    flex-wrap: wrap;
    margin-bottom: 0.2rem;
}
.message-name {
    margin: 0;
    color: var(--admin-primary);
    font-family: var(--font-admin);
    font-size: 1rem;
    font-weight: 800;
}
.message-time {
    color: var(--admin-text-muted);
    font-size: 0.78rem;
}
.message-status {
    display: inline-flex;
    align-items: center;
    gap: 0.35rem;
    padding: 0.22rem 0.55rem;
    border-radius: 999px;
    font-size: 0.68rem;
    font-weight: 800;
    text-transform: uppercase;
    letter-spacing: 0.04em;
}
.message-status span {
    width: 0.42rem;
    height: 0.42rem;
    border-radius: 999px;
}
.message-status.status-unread { color: #b42318; background: #fee4e2; }
.message-status.status-unread span { background: #d93025; }
.message-status.status-read { color: #475569; background: #f1f5f9; }
.message-status.status-read span { background: #94a3b8; }
.message-status.status-replied { color: #166534; background: #dcfce7; }
.message-status.status-replied span { background: #1e8e3e; }
.message-subject {
    color: var(--admin-primary);
    font-size: 0.96rem;
    font-weight: 800;
    margin: 0.35rem 0 0.25rem;
    overflow-wrap: anywhere;
}
.message-preview {
    color: #64748b;
    margin: 0;
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
    overflow-wrap: anywhere;
}
.message-contact-row {
    display: flex;
    flex-wrap: wrap;
    gap: 0.55rem;
    margin-top: 0.85rem;
}
.message-contact-row a {
    display: inline-flex;
    align-items: center;
    gap: 0.4rem;
    max-width: 100%;
    padding: 0.35rem 0.55rem;
    border-radius: 999px;
    color: #334155;
    background: #f8fafc;
    font-size: 0.78rem;
    font-weight: 700;
    text-decoration: none;
}
.message-contact-row a span {
    overflow: hidden;
    text-overflow: ellipsis;
    white-space: nowrap;
}
.message-actions {
    display: flex;
    align-items: flex-start;
    gap: 0.5rem;
    white-space: nowrap;
}
.message-actions .btn {
    border-radius: 12px;
    font-size: 0.78rem;
    font-weight: 800;
}
.message-actions form {
    margin: 0;
}
.empty-inbox {
    padding: 4rem 1.25rem;
    border: 1px dashed #cbd5e1;
    border-radius: 18px;
    background: #fff;
    text-align: center;
}
.empty-inbox i {
    color: #cbd5e1;
    font-size: 2.5rem;
    margin-bottom: 1rem;
}
.message-modal .modal-content {
    border: 0;
    border-radius: 18px;
    overflow: hidden;
}
.message-modal-header {
    padding: 1.25rem 1.5rem;
    color: #fff;
    background: linear-gradient(135deg, #0f172a, #243041);
}
.message-modal-header h5 {
    color: #fff;
    font-family: var(--font-admin);
    font-weight: 800;
    overflow-wrap: anywhere;
}
.message-thread-box {
    border: 1px solid #e5e7eb;
    border-radius: 16px;
    background: #f8fafc;
    padding: 1rem;
}
.reply-history-item {
    border: 1px solid #e5e7eb;
    border-radius: 14px;
    background: #fff;
    padding: 0.9rem;
}
.reply-box {
    border: 1px solid #dbe3ee;
    border-radius: 16px;
    background: #fff;
    transition: border-color 0.2s ease, box-shadow 0.2s ease;
}
.reply-box:focus-within {
    border-color: #d4af37;
    box-shadow: 0 0 0 4px rgba(212,175,55,0.14);
}
.reply-box textarea {
    min-height: 130px;
    resize: vertical;
}
@media (max-width: 1199px) {
    .messages-hero,
    .message-toolbar {
        grid-template-columns: 1fr;
    }
    .message-kpis,
    .message-filters {
        justify-content: stretch;
    }
    .message-filter {
        flex: 1 1 auto;
        justify-content: center;
    }
}
@media (max-width: 767px) {
    .messages-hero {
        padding: 1rem;
        border-radius: 16px;
    }
    .message-kpis {
        grid-template-columns: repeat(3, 1fr);
        gap: 0.45rem;
    }
    .message-kpi {
        min-width: 0;
        padding: 0.7rem 0.45rem;
    }
    .message-card-inner,
    .message-main {
        grid-template-columns: 1fr;
    }
    .message-avatar {
        width: 42px;
        height: 42px;
    }
    .message-actions {
        width: 100%;
        flex-wrap: wrap;
    }
    .message-actions .btn,
    .message-actions form {
        flex: 1 1 140px;
    }
    .message-actions form .btn {
        width: 100%;
    }
    .message-contact-row a {
        width: 100%;
    }
    .message-toolbar {
        padding: 0.65rem;
    }
    .message-filters {
        display: grid;
        grid-template-columns: repeat(2, 1fr);
    }
}
</style>

<div class="messages-page">
    <?php if (!empty($_SESSION['admin_flash'])): ?>
        <div class="alert alert-success border-0 rounded-3 mb-4"><i class="fas fa-check-circle me-2"></i> <?= htmlspecialchars($_SESSION['admin_flash']) ?></div>
        <?php unset($_SESSION['admin_flash']); ?>
    <?php endif; ?>

    <div class="messages-hero">
        <div>
            <h3>Client Messages</h3>
            <p>Review website enquiries, reply from the dashboard, and keep follow-ups organized.</p>
        </div>
        <div class="message-kpis" aria-label="Message status totals">
            <div class="message-kpi"><span>Total</span><strong><?= $messageCounts['all'] ?></strong></div>
            <div class="message-kpi"><span>Unread</span><strong><?= $messageCounts['unread'] ?></strong></div>
            <div class="message-kpi"><span>Replied</span><strong><?= $messageCounts['replied'] ?></strong></div>
        </div>
    </div>

    <form class="message-toolbar" method="GET" action="index.php">
        <input type="hidden" name="page" value="admin_messages">
        <input type="hidden" name="filter" value="<?= htmlspecialchars($filter) ?>">
        <label class="message-search" for="messageSearchInput">
            <i class="fas fa-search"></i>
            <input type="search" name="search" value="<?= htmlspecialchars($search) ?>" id="messageSearchInput" placeholder="Search by name, email, phone, subject, or message">
        </label>
        <div class="message-filters" role="group" aria-label="Filter messages">
            <?php foreach (['all' => 'All', 'unread' => 'Unread', 'read' => 'Read', 'replied' => 'Replied'] as $key => $label): ?>
                <a href="<?= htmlspecialchars(activeFilterUrl($key, $search)) ?>" class="message-filter <?= $filter === $key ? 'active' : '' ?>">
                    <?= $label ?> <small><?= $messageCounts[$key] ?? 0 ?></small>
                </a>
            <?php endforeach; ?>
        </div>
    </form>

    <?php if (empty($messages)): ?>
        <div class="empty-inbox">
            <i class="fas fa-inbox"></i>
            <h5 class="fw-bold mb-1">No messages found</h5>
            <p class="text-muted mb-0">New website contact submissions will appear here.</p>
        </div>
    <?php else: ?>
        <div class="message-list">
            <?php foreach ($messages as $msg): ?>
                <?php
                $fullName = trim(($msg['first_name'] ?? '') . ' ' . ($msg['last_name'] ?? ''));
                $fullName = $fullName !== '' ? $fullName : 'Unknown Sender';
                $safeId = (int) $msg['id'];
                $messageStatus = htmlspecialchars($msg['status'] ?? 'read');
                ?>
                <article class="message-card <?= $messageStatus ?>">
                    <div class="message-card-inner">
                        <div class="message-main">
                            <div class="message-avatar"><?= htmlspecialchars(messageInitials($msg['first_name'] ?? '', $msg['last_name'] ?? '')) ?></div>
                            <div class="min-width-0">
                                <div class="message-meta">
                                    <h5 class="message-name"><?= htmlspecialchars($fullName) ?></h5>
                                    <?= getStatusBadge($msg['status'] ?? 'read') ?>
                                    <span class="message-time"><?= date('M d, Y g:i A', strtotime($msg['created_at'])) ?></span>
                                </div>
                                <h6 class="message-subject"><?= htmlspecialchars($msg['subject'] ?: 'No subject') ?></h6>
                                <p class="message-preview"><?= htmlspecialchars($msg['message']) ?></p>
                                <div class="message-contact-row">
                                    <a href="mailto:<?= htmlspecialchars($msg['email']) ?>"><i class="far fa-envelope"></i><span><?= htmlspecialchars($msg['email']) ?></span></a>
                                    <?php if (!empty($msg['phone'])): ?>
                                        <a href="tel:<?= htmlspecialchars($msg['phone']) ?>"><i class="fas fa-phone"></i><span><?= htmlspecialchars($msg['phone']) ?></span></a>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                        <div class="message-actions">
                            <button type="button" class="btn btn-primary-law btn-sm" data-bs-toggle="modal" data-bs-target="#messageModal<?= $safeId ?>">
                                <i class="far fa-comment-dots me-1"></i> Reply
                            </button>
                            <form method="POST" action="index.php?page=admin_messages">
                                <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?? '' ?>">
                                <input type="hidden" name="action" value="delete_message">
                                <input type="hidden" name="message_id" value="<?= $safeId ?>">
                                <button type="submit" class="btn btn-outline-danger btn-sm" onclick="return confirm('Delete this message?')">
                                    <i class="far fa-trash-alt me-1"></i> Delete
                                </button>
                            </form>
                        </div>
                    </div>
                </article>

                <div class="modal fade message-modal" id="messageModal<?= $safeId ?>" tabindex="-1" aria-labelledby="messageModalLabel<?= $safeId ?>" aria-hidden="true">
                    <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
                        <div class="modal-content shadow">
                            <div class="message-modal-header d-flex justify-content-between gap-3">
                                <div class="min-width-0">
                                    <h5 class="modal-title mb-1" id="messageModalLabel<?= $safeId ?>"><?= htmlspecialchars($msg['subject'] ?: 'Client message') ?></h5>
                                    <div class="small opacity-75">
                                        <?= htmlspecialchars($fullName) ?> &middot; <?= date('M d, Y g:i A', strtotime($msg['created_at'])) ?>
                                    </div>
                                </div>
                                <button type="button" class="btn-close btn-close-white flex-shrink-0" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body p-4">
                                <div class="message-thread-box mb-4">
                                    <div class="d-flex gap-3 mb-3">
                                        <div class="message-avatar"><?= htmlspecialchars(messageInitials($msg['first_name'] ?? '', $msg['last_name'] ?? '')) ?></div>
                                        <div class="min-width-0">
                                            <div class="fw-bold"><?= htmlspecialchars($fullName) ?></div>
                                            <div class="small text-muted">
                                                <a href="mailto:<?= htmlspecialchars($msg['email']) ?>" class="text-decoration-none"><?= htmlspecialchars($msg['email']) ?></a>
                                                <?php if (!empty($msg['phone'])): ?>
                                                    &middot; <a href="tel:<?= htmlspecialchars($msg['phone']) ?>" class="text-decoration-none"><?= htmlspecialchars($msg['phone']) ?></a>
                                                <?php endif; ?>
                                            </div>
                                        </div>
                                    </div>
                                    <p class="mb-0" style="white-space: pre-wrap; overflow-wrap: anywhere;"><?= htmlspecialchars($msg['message']) ?></p>
                                </div>

                                <?php
                                $replies = [];
                                try {
                                    if (isset($messageModel)) {
                                        $replies = $messageModel->getReplies($safeId);
                                    }
                                } catch (Exception $e) {}
                                ?>
                                <?php if (!empty($replies)): ?>
                                    <div class="mb-4">
                                        <h6 class="text-muted small text-uppercase fw-bold mb-3">Reply History</h6>
                                        <div class="d-grid gap-2">
                                            <?php foreach ($replies as $reply): ?>
                                                <div class="reply-history-item">
                                                    <div class="d-flex justify-content-between gap-3 mb-2">
                                                        <small class="fw-bold text-primary-law">Dashboard Reply</small>
                                                        <small class="text-muted"><?= date('M d, Y g:i A', strtotime($reply['created_at'])) ?></small>
                                                    </div>
                                                    <p class="mb-0 small" style="white-space: pre-wrap; overflow-wrap: anywhere;"><?= htmlspecialchars($reply['reply_text']) ?></p>
                                                </div>
                                            <?php endforeach; ?>
                                        </div>
                                    </div>
                                <?php endif; ?>

                                <form method="POST" action="index.php?page=admin_messages">
                                    <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?? '' ?>">
                                    <input type="hidden" name="action" value="reply_to_message">
                                    <input type="hidden" name="message_id" value="<?= $safeId ?>">
                                    <div class="reply-box p-3">
                                        <label class="form-label fw-bold small text-muted" for="replyText<?= $safeId ?>">Write a Reply</label>
                                        <textarea name="reply_text" id="replyText<?= $safeId ?>" class="form-control border-0 shadow-none p-0" rows="4" placeholder="Type your professional response..." required></textarea>
                                    </div>
                                    <div class="mt-3 d-flex flex-wrap justify-content-between align-items-center gap-2">
                                        <small class="text-muted">Saved to this message history after sending.</small>
                                        <button type="submit" class="btn btn-primary-law">
                                            <i class="fas fa-paper-plane me-1"></i> Send Reply
                                        </button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</div>
