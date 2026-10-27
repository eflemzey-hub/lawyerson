<?php
/**
 * Appointments table — used on Dashboard and Booking page.
 * Expects: $appointments, $tableRedirect (optional, default admin_appointments)
 */
$tableRedirect = $tableRedirect ?? 'index.php?page=admin_appointments';
$compact = !empty($compactTable);
?>
<div class="table-responsive">
    <table class="table table-hover mb-0 align-middle admin-table">
        <thead>
            <tr>
                <th class="py-3 px-3 fw-bold">ID</th>
                <th class="py-3 px-3 fw-bold">Client</th>
                <th class="py-3 px-3 fw-bold">Date</th>
                <?php if (!$compact): ?>
                <th class="py-3 px-3 fw-bold">Practice Area</th>
                <?php endif; ?>
                <th class="py-3 px-3 fw-bold">Status</th>
                <th class="py-3 px-3 fw-bold text-end">Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php if (empty($appointments)): ?>
                <tr>
                    <td colspan="<?= $compact ? 5 : 6 ?>" class="text-center py-5 text-muted">
                        <i class="fas fa-calendar-times fa-2x mb-3 d-block opacity-25"></i>
                        No consultation requests yet.
                    </td>
                </tr>
            <?php else: ?>
                <?php foreach ($appointments as $appt): ?>
                    <tr>
                        <td class="px-3 text-muted small">#<?= str_pad((int) $appt['id'], 4, '0', STR_PAD_LEFT) ?></td>
                        <td class="px-3">
                            <div class="fw-bold text-primary-law"><?= htmlspecialchars($appt['name']) ?></div>
                            <div class="small text-muted"><?= htmlspecialchars($appt['email']) ?></div>
                            <?php if (!$compact): ?>
                            <div class="small text-muted"><i class="fas fa-phone me-1"></i><?= htmlspecialchars($appt['phone']) ?></div>
                            <?php endif; ?>
                        </td>
                        <td class="px-3">
                            <div class="fw-medium small">
                                <i class="far fa-calendar-alt text-gold me-1"></i>
                                <?= admin_format_date($appt['appointment_date'] ?? '') ?>
                            </div>
                            <div class="text-muted" style="font-size: 0.72rem;">
                                Requested <?= admin_format_date($appt['created_at'] ?? '', 'M d, Y') ?>
                            </div>
                        </td>
                        <?php if (!$compact): ?>
                        <td class="px-3 small fw-medium"><?= htmlspecialchars(admin_format_practice_area($appt['practice_area'] ?? '')) ?></td>
                        <?php endif; ?>
                        <td class="px-3">
                            <span class="badge <?= admin_status_badge_class($appt['status'] ?? '') ?> px-2 py-1 text-uppercase" style="font-size: 0.65rem; letter-spacing: 0.5px;">
                                <?= htmlspecialchars($appt['status'] ?? 'pending') ?>
                            </span>
                        </td>
                        <td class="px-3 text-end text-nowrap">
                            <button type="button" class="btn btn-sm btn-outline-secondary rounded-0" title="View details"
                                onclick="alert(<?= json_encode('Case details: ' . ($appt['case_details'] ?? 'No additional details provided.')) ?>)">
                                <i class="fas fa-eye"></i>
                            </button>
                            <?php if (($appt['status'] ?? '') === 'pending'): ?>
                            <form action="index.php" method="POST" class="d-inline">
                                <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?>">
                                <input type="hidden" name="action" value="update_appointment_status">
                                <input type="hidden" name="appointment_id" value="<?= (int) $appt['id'] ?>">
                                <input type="hidden" name="status" value="confirmed">
                                <input type="hidden" name="redirect" value="<?= htmlspecialchars($tableRedirect) ?>">
                                <button type="submit" class="btn btn-sm btn-success rounded-0" title="Confirm"><i class="fas fa-check"></i></button>
                            </form>
                            <?php endif; ?>
                            <?php if (($appt['status'] ?? '') === 'confirmed'): ?>
                            <form action="index.php" method="POST" class="d-inline">
                                <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?>">
                                <input type="hidden" name="action" value="update_appointment_status">
                                <input type="hidden" name="appointment_id" value="<?= (int) $appt['id'] ?>">
                                <input type="hidden" name="status" value="completed">
                                <input type="hidden" name="redirect" value="<?= htmlspecialchars($tableRedirect) ?>">
                                <button type="submit" class="btn btn-sm btn-primary-law rounded-0" title="Mark completed"><i class="fas fa-check-double"></i></button>
                            </form>
                            <?php endif; ?>
                            <form action="index.php" method="POST" class="d-inline" onsubmit="return confirm('Delete this booking?');">
                                <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?>">
                                <input type="hidden" name="action" value="delete_appointment">
                                <input type="hidden" name="appointment_id" value="<?= (int) $appt['id'] ?>">
                                <input type="hidden" name="redirect" value="<?= htmlspecialchars($tableRedirect) ?>">
                                <button type="submit" class="btn btn-sm btn-outline-danger rounded-0" title="Delete"><i class="fas fa-trash-alt"></i></button>
                            </form>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php endif; ?>
        </tbody>
    </table>
</div>
