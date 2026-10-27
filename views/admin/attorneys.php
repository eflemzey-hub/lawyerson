<?php
$attorneys = [];
try {
    if (isset($attorneyModel)) {
        $attorneys = $attorneyModel->getAll();
    }
} catch (Exception $e) {}

// Fallback dummy data if no DB
if (empty($attorneys)) {
    $attorneys = [
        ['id' => 1, 'name' => 'Jonathan Doe', 'position' => 'Senior Partner', 'bio' => 'Expert in corporate law with over 20 years of experience.', 'image_url' => 'https://images.unsplash.com/photo-1560250097-0b93528c311a?auto=format&fit=crop&q=80&w=400'],
        ['id' => 2, 'name' => 'Sarah Jenkins', 'position' => 'Managing Partner', 'bio' => 'Specializes in family law and civil litigation.', 'image_url' => 'https://images.unsplash.com/photo-1573496359142-b8d87734a5a2?auto=format&fit=crop&q=80&w=400'],
        ['id' => 3, 'name' => 'Michael Chang', 'position' => 'Associate Attorney', 'bio' => 'Focuses on real estate and property disputes.', 'image_url' => 'https://images.unsplash.com/photo-1519085360753-af0119f7cbe7?auto=format&fit=crop&q=80&w=400'],
        ['id' => 4, 'name' => 'Elena Rostova', 'position' => 'Criminal Defense', 'bio' => 'Dedicated defense attorney with a track record of success.', 'image_url' => 'https://images.unsplash.com/photo-1580489944761-15a19d654956?auto=format&fit=crop&q=80&w=400']
    ];
}
?>

<h3 class="dash-header mb-4">Manage Attorneys</h3>

<div class="row g-4">
    <!-- Add Attorney Form -->
    <div class="col-lg-4">
        <div class="card border-0 shadow-sm rounded-4">
            <div class="card-body p-4">
                <h5 class="fw-bold mb-4">Add New Attorney</h5>
                <form action="index.php" method="POST">
                    <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?>">
                    <input type="hidden" name="action" value="create_attorney">
                    
                    <div class="mb-3">
                        <label class="form-label fw-bold small">Full Name</label>
                        <input type="text" name="name" class="form-control bg-light border-0" required>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label fw-bold small">Position / Title</label>
                        <input type="text" name="position" class="form-control bg-light border-0" required>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label fw-bold small">Photo URL (From Media Manager)</label>
                        <input type="text" name="image_url" class="form-control bg-light border-0" required placeholder="uploads/123_photo.jpg">
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label fw-bold small">Short Bio</label>
                        <textarea name="bio" class="form-control bg-light border-0" rows="3" required></textarea>
                    </div>

                    <div class="mb-4">
                        <label class="form-label fw-bold small text-muted">Social Links (Optional)</label>
                        <input type="text" name="linkedin" class="form-control bg-light border-0 mb-2" placeholder="LinkedIn URL">
                        <input type="text" name="twitter" class="form-control bg-light border-0" placeholder="Twitter URL">
                    </div>
                    
                    <button type="submit" class="btn btn-primary-law w-100 rounded-3 py-2 fw-bold">Add Attorney</button>
                </form>
            </div>
        </div>
    </div>
    
    <!-- Attorneys List -->
    <div class="col-lg-8">
        <div class="row g-3">
            <?php foreach($attorneys as $attorney): ?>
            <div class="col-md-6">
                <div class="card border-0 shadow-sm rounded-4 h-100">
                    <div class="d-flex p-3">
                        <img src="<?= htmlspecialchars($attorney['image_url']) ?>" alt="<?= htmlspecialchars($attorney['name']) ?>" class="rounded-3 object-fit-cover" style="width: 80px; height: 80px;">
                        <div class="ms-3 flex-grow-1">
                            <h6 class="fw-bold mb-1"><?= htmlspecialchars($attorney['name']) ?></h6>
                            <p class="text-muted small mb-2"><?= htmlspecialchars($attorney['position']) ?></p>
                            <form action="index.php" method="POST" onsubmit="return confirm('Delete this attorney?');">
                                <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?>">
                                <input type="hidden" name="action" value="delete_attorney">
                                <input type="hidden" name="attorney_id" value="<?= $attorney['id'] ?>">
                                <button type="submit" class="btn btn-sm btn-outline-danger px-3 py-1 rounded-pill" style="font-size: 0.75rem;"><i class="fas fa-trash-alt me-1"></i> Delete</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
</div>
