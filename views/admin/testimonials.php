<?php
if (!isset($_SESSION['admin_logged_in'])) {
    header("Location: index.php?page=admin_login");
    exit;
}

// Handle form submissions
$message = '';
$messageType = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!isset($_POST['csrf_token']) || !hash_equals($_SESSION['csrf_token'], $_POST['csrf_token'])) {
        $message = 'Invalid security token';
        $messageType = 'danger';
    } else {
        $action = $_POST['action'] ?? '';
        
        try {
            if ($action === 'create') {
                $data = [
                    'client_name' => $_POST['client_name'] ?? '',
                    'client_title' => $_POST['client_title'] ?? '',
                    'client_company' => $_POST['client_company'] ?? '',
                    'practice_area' => $_POST['practice_area'] ?? '',
                    'rating' => (int) ($_POST['rating'] ?? 5),
                    'review_text' => $_POST['review_text'] ?? '',
                    'status' => $_POST['status'] ?? 'active',
                    'display_order' => (int) ($_POST['display_order'] ?? 0)
                ];
                
                if ($testimonialModel->create($data)) {
                    $message = 'Testimonial created successfully!';
                    $messageType = 'success';
                } else {
                    $message = 'Failed to create testimonial';
                    $messageType = 'danger';
                }
            } elseif ($action === 'update') {
                $id = (int) ($_POST['testimonial_id'] ?? 0);
                $data = [
                    'client_name' => $_POST['client_name'] ?? '',
                    'client_title' => $_POST['client_title'] ?? '',
                    'client_company' => $_POST['client_company'] ?? '',
                    'practice_area' => $_POST['practice_area'] ?? '',
                    'rating' => (int) ($_POST['rating'] ?? 5),
                    'review_text' => $_POST['review_text'] ?? '',
                    'status' => $_POST['status'] ?? 'active',
                    'display_order' => (int) ($_POST['display_order'] ?? 0)
                ];
                
                if ($testimonialModel->update($id, $data)) {
                    $message = 'Testimonial updated successfully!';
                    $messageType = 'success';
                } else {
                    $message = 'Failed to update testimonial';
                    $messageType = 'danger';
                }
            } elseif ($action === 'delete') {
                $id = (int) ($_POST['testimonial_id'] ?? 0);
                if ($testimonialModel->delete($id)) {
                    $message = 'Testimonial deleted successfully!';
                    $messageType = 'success';
                } else {
                    $message = 'Failed to delete testimonial';
                    $messageType = 'danger';
                }
            }
        } catch (Exception $e) {
            $message = 'Error: ' . htmlspecialchars($e->getMessage());
            $messageType = 'danger';
        }
    }
}

// Fetch all testimonials
$testimonials = [];
try {
    $testimonials = $testimonialModel->getAll(false); // Get all, including inactive
} catch (Exception $e) {
    $message = 'Error loading testimonials: ' . htmlspecialchars($e->getMessage());
    $messageType = 'danger';
}

// Get testimonial for editing if requested
$editTestimonial = null;
if (isset($_GET['edit'])) {
    $editId = (int) $_GET['edit'];
    $editTestimonial = $testimonialModel->getById($editId);
}
?>

<div class="container-fluid px-4 py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 mb-1 text-primary-law fw-bold">Testimonials Management</h1>
            <p class="text-muted mb-0">Manage client reviews and testimonials</p>
        </div>
        <button class="btn btn-primary-law rounded-0" data-bs-toggle="modal" data-bs-target="#addTestimonialModal">
            <i class="fas fa-plus me-2"></i>Add New Testimonial
        </button>
    </div>

    <?php if ($message): ?>
        <div class="alert alert-<?= $messageType ?> alert-dismissible fade show rounded-0" role="alert">
            <?= htmlspecialchars($message) ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php endif; ?>

    <!-- Statistics Cards -->
    <div class="row g-3 mb-4">
        <div class="col-md-3">
            <div class="card border-0 shadow-sm rounded-1">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-shrink-0 bg-primary-law bg-opacity-10 p-3 rounded">
                            <i class="fas fa-star text-primary-law fs-4"></i>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <h6 class="text-muted mb-0 small">Total Testimonials</h6>
                            <h3 class="mb-0 fw-bold"><?= count($testimonials) ?></h3>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-0 shadow-sm rounded-1">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-shrink-0 bg-success bg-opacity-10 p-3 rounded">
                            <i class="fas fa-check-circle text-success fs-4"></i>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <h6 class="text-muted mb-0 small">Active</h6>
                            <h3 class="mb-0 fw-bold"><?= count(array_filter($testimonials, fn($t) => $t['status'] === 'active')) ?></h3>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-0 shadow-sm rounded-1">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-shrink-0 bg-warning bg-opacity-10 p-3 rounded">
                            <i class="fas fa-pause-circle text-warning fs-4"></i>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <h6 class="text-muted mb-0 small">Inactive</h6>
                            <h3 class="mb-0 fw-bold"><?= count(array_filter($testimonials, fn($t) => $t['status'] === 'inactive')) ?></h3>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-0 shadow-sm rounded-1">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-shrink-0 bg-gold bg-opacity-10 p-3 rounded">
                            <i class="fas fa-star-half-alt text-gold fs-4"></i>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <h6 class="text-muted mb-0 small">Avg Rating</h6>
                            <h3 class="mb-0 fw-bold">
                                <?php 
                                $avgRating = count($testimonials) > 0 
                                    ? number_format(array_sum(array_column($testimonials, 'rating')) / count($testimonials), 1)
                                    : '0.0';
                                echo $avgRating;
                                ?>
                            </h3>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Testimonials Table -->
    <div class="card border-0 shadow-sm rounded-1">
        <div class="card-header bg-white border-bottom py-3">
            <h5 class="mb-0 fw-bold text-primary-law">All Testimonials</h5>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead class="table-light">
                        <tr>
                            <th class="px-4 py-3">Order</th>
                            <th class="py-3">Client Name</th>
                            <th class="py-3">Practice Area</th>
                            <th class="py-3">Rating</th>
                            <th class="py-3">Status</th>
                            <th class="py-3">Date Added</th>
                            <th class="py-3 text-end pe-4">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($testimonials)): ?>
                            <tr>
                                <td colspan="7" class="text-center py-5 text-muted">
                                    <i class="fas fa-inbox fs-1 d-block mb-3 opacity-25"></i>
                                    No testimonials found. Click "Add New Testimonial" to create one.
                                </td>
                            </tr>
                        <?php else: ?>
                            <?php foreach ($testimonials as $testimonial): ?>
                                <tr>
                                    <td class="px-4 py-3">
                                        <span class="badge bg-secondary">#<?= $testimonial['display_order'] ?></span>
                                    </td>
                                    <td class="py-3">
                                        <div>
                                            <div class="fw-bold text-primary-law"><?= htmlspecialchars($testimonial['client_name']) ?></div>
                                            <small class="text-muted">
                                                <?= htmlspecialchars($testimonial['client_title'] ?? '') ?>
                                                <?php if ($testimonial['client_company']): ?>
                                                    , <?= htmlspecialchars($testimonial['client_company']) ?>
                                                <?php endif; ?>
                                            </small>
                                        </div>
                                    </td>
                                    <td class="py-3">
                                        <span class="badge bg-primary-law bg-opacity-10 text-primary-law">
                                            <?= htmlspecialchars($testimonial['practice_area']) ?>
                                        </span>
                                    </td>
                                    <td class="py-3">
                                        <div class="text-gold">
                                            <?php for ($i = 0; $i < $testimonial['rating']; $i++): ?>
                                                <i class="fas fa-star"></i>
                                            <?php endfor; ?>
                                        </div>
                                    </td>
                                    <td class="py-3">
                                        <?php if ($testimonial['status'] === 'active'): ?>
                                            <span class="badge bg-success">Active</span>
                                        <?php else: ?>
                                            <span class="badge bg-secondary">Inactive</span>
                                        <?php endif; ?>
                                    </td>
                                    <td class="py-3 text-muted small">
                                        <?= date('M d, Y', strtotime($testimonial['created_at'])) ?>
                                    </td>
                                    <td class="py-3 text-end pe-4">
                                        <button class="btn btn-sm btn-outline-primary-law rounded-0 me-1" 
                                                onclick="editTestimonial(<?= htmlspecialchars(json_encode($testimonial)) ?>)">
                                            <i class="fas fa-edit"></i>
                                        </button>
                                        <button class="btn btn-sm btn-outline-danger rounded-0" 
                                                onclick="deleteTestimonial(<?= $testimonial['id'] ?>, '<?= htmlspecialchars($testimonial['client_name']) ?>')">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Add/Edit Testimonial Modal -->
<div class="modal fade" id="addTestimonialModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content rounded-1">
            <div class="modal-header border-bottom">
                <h5 class="modal-title fw-bold text-primary-law" id="modalTitle">Add New Testimonial</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form method="POST" id="testimonialForm">
                <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($_SESSION['csrf_token']) ?>">
                <input type="hidden" name="action" value="create" id="formAction">
                <input type="hidden" name="testimonial_id" value="" id="testimonialId">
                
                <div class="modal-body">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label fw-bold">Client Name <span class="text-danger">*</span></label>
                            <input type="text" class="form-control rounded-0" name="client_name" id="clientName" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-bold">Client Title</label>
                            <input type="text" class="form-control rounded-0" name="client_title" id="clientTitle" placeholder="e.g., CEO, Managing Director">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-bold">Company Name</label>
                            <input type="text" class="form-control rounded-0" name="client_company" id="clientCompany" placeholder="Leave blank for private clients">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-bold">Practice Area <span class="text-danger">*</span></label>
                            <select class="form-select rounded-0" name="practice_area" id="practiceArea" required>
                                <option value="">Select practice area...</option>
                                <option value="Corporate Law">Corporate Law</option>
                                <option value="Real Estate">Real Estate</option>
                                <option value="Family Law">Family Law</option>
                                <option value="Criminal Defense">Criminal Defense</option>
                                <option value="Estate Planning">Estate Planning</option>
                                <option value="Personal Injury">Personal Injury</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-bold">Rating <span class="text-danger">*</span></label>
                            <select class="form-select rounded-0" name="rating" id="rating" required>
                                <option value="5" selected>5 Stars (Excellent)</option>
                                <option value="4">4 Stars (Very Good)</option>
                                <option value="3">3 Stars (Good)</option>
                                <option value="2">2 Stars (Fair)</option>
                                <option value="1">1 Star (Poor)</option>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label fw-bold">Display Order</label>
                            <input type="number" class="form-control rounded-0" name="display_order" id="displayOrder" value="0" min="0">
                        </div>
                        <div class="col-md-3">
                            <label class="form-label fw-bold">Status</label>
                            <select class="form-select rounded-0" name="status" id="status">
                                <option value="active">Active</option>
                                <option value="inactive">Inactive</option>
                            </select>
                        </div>
                        <div class="col-12">
                            <label class="form-label fw-bold">Review Text <span class="text-danger">*</span></label>
                            <textarea class="form-control rounded-0" name="review_text" id="reviewText" rows="6" required placeholder="Enter the client's testimonial..."></textarea>
                            <small class="text-muted">Write a compelling, professional review that highlights specific benefits and outcomes.</small>
                        </div>
                    </div>
                </div>
                <div class="modal-footer border-top">
                    <button type="button" class="btn btn-secondary rounded-0" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary-law rounded-0">
                        <i class="fas fa-save me-2"></i><span id="submitBtnText">Create Testimonial</span>
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Delete Confirmation Modal -->
<div class="modal fade" id="deleteModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content rounded-1">
            <div class="modal-header border-bottom bg-danger text-white">
                <h5 class="modal-title fw-bold">Confirm Delete</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form method="POST" id="deleteForm">
                <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($_SESSION['csrf_token']) ?>">
                <input type="hidden" name="action" value="delete">
                <input type="hidden" name="testimonial_id" id="deleteTestimonialId">
                
                <div class="modal-body">
                    <p class="mb-0">Are you sure you want to delete the testimonial from <strong id="deleteClientName"></strong>?</p>
                    <p class="text-muted small mb-0 mt-2">This action cannot be undone.</p>
                </div>
                <div class="modal-footer border-top">
                    <button type="button" class="btn btn-secondary rounded-0" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-danger rounded-0">
                        <i class="fas fa-trash me-2"></i>Delete Testimonial
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
function editTestimonial(testimonial) {
    document.getElementById('modalTitle').textContent = 'Edit Testimonial';
    document.getElementById('formAction').value = 'update';
    document.getElementById('testimonialId').value = testimonial.id;
    document.getElementById('clientName').value = testimonial.client_name;
    document.getElementById('clientTitle').value = testimonial.client_title || '';
    document.getElementById('clientCompany').value = testimonial.client_company || '';
    document.getElementById('practiceArea').value = testimonial.practice_area;
    document.getElementById('rating').value = testimonial.rating;
    document.getElementById('displayOrder').value = testimonial.display_order;
    document.getElementById('status').value = testimonial.status;
    document.getElementById('reviewText').value = testimonial.review_text;
    document.getElementById('submitBtnText').textContent = 'Update Testimonial';
    
    new bootstrap.Modal(document.getElementById('addTestimonialModal')).show();
}

function deleteTestimonial(id, name) {
    document.getElementById('deleteTestimonialId').value = id;
    document.getElementById('deleteClientName').textContent = name;
    new bootstrap.Modal(document.getElementById('deleteModal')).show();
}

// Reset form when modal is closed
document.getElementById('addTestimonialModal').addEventListener('hidden.bs.modal', function() {
    document.getElementById('modalTitle').textContent = 'Add New Testimonial';
    document.getElementById('formAction').value = 'create';
    document.getElementById('testimonialId').value = '';
    document.getElementById('testimonialForm').reset();
    document.getElementById('submitBtnText').textContent = 'Create Testimonial';
});
</script>
