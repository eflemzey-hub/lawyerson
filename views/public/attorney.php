<?php
$attorneyId = isset($_GET['id']) ? (int) $_GET['id'] : null;
$attorney = null;
$defaultAttorneyImage = 'https://images.unsplash.com/photo-1560250097-0b93528c311a?auto=format&fit=crop&q=80&w=800';

try {
    if (isset($attorneyModel) && $attorneyId) {
        $attorney = $attorneyModel->getById($attorneyId);
    }
} catch (Exception $e) {}

if (!$attorney) {
    echo '<section class="section-padding bg-law-bg"><div class="container text-center py-5"><h2 class="text-primary-law fw-bold mb-3">Attorney Not Found</h2><p class="text-muted mb-4">This attorney profile may not exist or has been removed.</p><a href="index.php?page=attorneys" class="btn btn-primary-law rounded-0">View All Attorneys</a></div></section>';
    return;
}

$attorneyImage = $attorney['image_url'] ?? $defaultAttorneyImage;
$specialties = !empty($attorney['specialty']) ? explode(',', $attorney['specialty']) : [];
?>

<!-- Attorney Hero Section -->
<section class="bg-law-bg py-5">
    <div class="container py-4">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="index.php" class="text-gold text-decoration-none">Home</a></li>
                <li class="breadcrumb-item"><a href="index.php?page=attorneys" class="text-gold text-decoration-none">Attorneys</a></li>
                <li class="breadcrumb-item active text-muted" aria-current="page"><?= htmlspecialchars($attorney['name']) ?></li>
            </ol>
        </nav>
    </div>
</section>

<!-- Attorney Profile Section -->
<section class="section-padding bg-white">
    <div class="container">
        <div class="row g-5">
            <!-- Attorney Photo & Quick Info -->
            <div class="col-lg-4">
                <div class="position-sticky" style="top: 100px;">
                    <div class="card border-0 shadow-sm rounded-1 overflow-hidden">
                        <img src="<?= htmlspecialchars($attorneyImage) ?>" 
                             alt="<?= htmlspecialchars($attorney['name']) ?>" 
                             class="img-fluid w-100"
                             style="height: 450px; object-fit: cover;"
                             onerror="this.src='<?= htmlspecialchars($defaultAttorneyImage) ?>'">
                        
                        <div class="card-body p-4">
                            <h3 class="fw-bold text-primary-law mb-1"><?= htmlspecialchars($attorney['name']) ?></h3>
                            <p class="text-gold fw-medium mb-3"><?= htmlspecialchars($attorney['position']) ?></p>
                            
                            <?php if ($attorney['years_experience']): ?>
                                <div class="d-flex align-items-center mb-3 text-muted">
                                    <i class="fas fa-briefcase text-primary-law me-3"></i>
                                    <span><?= $attorney['years_experience'] ?> Years Experience</span>
                                </div>
                            <?php endif; ?>
                            
                            <?php if ($attorney['email']): ?>
                                <div class="d-flex align-items-center mb-3">
                                    <i class="fas fa-envelope text-primary-law me-3"></i>
                                    <a href="mailto:<?= htmlspecialchars($attorney['email']) ?>" class="text-decoration-none text-muted">
                                        <?= htmlspecialchars($attorney['email']) ?>
                                    </a>
                                </div>
                            <?php endif; ?>
                            
                            <?php if ($attorney['phone']): ?>
                                <div class="d-flex align-items-center mb-4">
                                    <i class="fas fa-phone text-primary-law me-3"></i>
                                    <a href="tel:<?= htmlspecialchars($attorney['phone']) ?>" class="text-decoration-none text-muted">
                                        <?= htmlspecialchars($attorney['phone']) ?>
                                    </a>
                                </div>
                            <?php endif; ?>
                            
                            <!-- Social Links -->
                            <?php if ($attorney['linkedin'] || $attorney['twitter'] || $attorney['facebook']): ?>
                                <div class="d-flex gap-2 mb-4">
                                    <?php if ($attorney['linkedin']): ?>
                                        <a href="<?= htmlspecialchars($attorney['linkedin']) ?>" target="_blank" rel="noopener" class="btn btn-outline-primary-law btn-sm rounded-circle" style="width: 40px; height: 40px;">
                                            <i class="fab fa-linkedin-in"></i>
                                        </a>
                                    <?php endif; ?>
                                    <?php if ($attorney['twitter']): ?>
                                        <a href="<?= htmlspecialchars($attorney['twitter']) ?>" target="_blank" rel="noopener" class="btn btn-outline-primary-law btn-sm rounded-circle" style="width: 40px; height: 40px;">
                                            <i class="fab fa-twitter"></i>
                                        </a>
                                    <?php endif; ?>
                                    <?php if ($attorney['facebook']): ?>
                                        <a href="<?= htmlspecialchars($attorney['facebook']) ?>" target="_blank" rel="noopener" class="btn btn-outline-primary-law btn-sm rounded-circle" style="width: 40px; height: 40px;">
                                            <i class="fab fa-facebook-f"></i>
                                        </a>
                                    <?php endif; ?>
                                </div>
                            <?php endif; ?>
                            
                            <!-- CTA Button -->
                            <a href="index.php?page=consultation" class="btn btn-gold w-100 rounded-0 py-3 fw-bold">
                                <i class="fas fa-calendar-check me-2"></i>Book Consultation
                            </a>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Attorney Details -->
            <div class="col-lg-8">
                <!-- Biography -->
                <?php if ($attorney['bio']): ?>
                    <div class="mb-5">
                        <h4 class="fw-bold text-primary-law mb-3 pb-2 border-bottom border-2 border-gold">Professional Overview</h4>
                        <p class="text-muted" style="line-height: 1.8; text-align: justify;">
                            <?= nl2br(htmlspecialchars($attorney['bio'])) ?>
                        </p>
                    </div>
                <?php endif; ?>
                
                <!-- Practice Specialties -->
                <?php if (!empty($specialties)): ?>
                    <div class="mb-5">
                        <h4 class="fw-bold text-primary-law mb-3 pb-2 border-bottom border-2 border-gold">Practice Specialties</h4>
                        <div class="d-flex flex-wrap gap-2">
                            <?php foreach ($specialties as $specialty): ?>
                                <span class="badge bg-primary-law bg-opacity-10 text-primary-law px-3 py-2 fw-normal" style="font-size: 0.95rem;">
                                    <?= htmlspecialchars(trim($specialty)) ?>
                                </span>
                            <?php endforeach; ?>
                        </div>
                    </div>
                <?php endif; ?>
                
                <!-- Education -->
                <?php if ($attorney['education']): ?>
                    <div class="mb-5">
                        <h4 class="fw-bold text-primary-law mb-3 pb-2 border-bottom border-2 border-gold">
                            <i class="fas fa-graduation-cap me-2"></i>Education
                        </h4>
                        <p class="text-muted" style="line-height: 1.8;">
                            <?= nl2br(htmlspecialchars($attorney['education'])) ?>
                        </p>
                    </div>
                <?php endif; ?>
                
                <!-- Bar Admissions -->
                <?php if ($attorney['bar_admissions']): ?>
                    <div class="mb-5">
                        <h4 class="fw-bold text-primary-law mb-3 pb-2 border-bottom border-2 border-gold">
                            <i class="fas fa-gavel me-2"></i>Bar Admissions
                        </h4>
                        <p class="text-muted" style="line-height: 1.8;">
                            <?= nl2br(htmlspecialchars($attorney['bar_admissions'])) ?>
                        </p>
                    </div>
                <?php endif; ?>
                
                <!-- Professional Achievements -->
                <?php if ($attorney['achievements']): ?>
                    <div class="mb-5">
                        <h4 class="fw-bold text-primary-law mb-3 pb-2 border-bottom border-2 border-gold">
                            <i class="fas fa-award me-2"></i>Professional Achievements
                        </h4>
                        <p class="text-muted" style="line-height: 1.8;">
                            <?= nl2br(htmlspecialchars($attorney['achievements'])) ?>
                        </p>
                    </div>
                <?php endif; ?>
                
                <!-- Notable Cases -->
                <?php if ($attorney['notable_cases']): ?>
                    <div class="mb-5">
                        <h4 class="fw-bold text-primary-law mb-3 pb-2 border-bottom border-2 border-gold">
                            <i class="fas fa-balance-scale me-2"></i>Notable Cases
                        </h4>
                        <p class="text-muted" style="line-height: 1.8;">
                            <?= nl2br(htmlspecialchars($attorney['notable_cases'])) ?>
                        </p>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</section>

<!-- CTA Section -->
<section class="py-5 bg-primary-law text-white text-center">
    <div class="container py-4">
        <h2 class="display-6 mb-3 fw-bold">Schedule a Consultation with <?= htmlspecialchars($attorney['name']) ?></h2>
        <p class="lead opacity-75 mb-4">Get expert legal advice tailored to your specific needs</p>
        <a href="index.php?page=consultation" class="btn btn-gold btn-lg rounded-0 px-5 py-3 shadow-sm">
            <i class="fas fa-calendar-check me-2"></i>Book Your Consultation
        </a>
    </div>
</section>

<!-- Other Attorneys Section -->
<section class="section-padding bg-law-bg">
    <div class="container">
        <h4 class="fw-bold text-primary-law mb-4 text-center">Meet Our Other Attorneys</h4>
        
        <?php
        $otherAttorneys = [];
        try {
            if (isset($attorneyModel)) {
                $allAttorneys = $attorneyModel->getAll(true);
                $otherAttorneys = array_filter($allAttorneys, function($atty) use ($attorneyId) {
                    return $atty['id'] != $attorneyId;
                });
                $otherAttorneys = array_slice($otherAttorneys, 0, 3);
            }
        } catch (Exception $e) {}
        ?>
        
        <?php if (!empty($otherAttorneys)): ?>
            <div class="row g-4">
                <?php foreach ($otherAttorneys as $otherAtty): ?>
                    <div class="col-md-4">
                        <a href="index.php?page=attorney&id=<?= $otherAtty['id'] ?>" class="text-decoration-none">
                            <div class="card border-0 shadow-sm h-100 attorney-card-mini" style="transition: transform 0.3s ease;">
                                <div class="position-relative overflow-hidden" style="height: 300px;">
                                    <img src="<?= htmlspecialchars($otherAtty['image_url'] ?? $defaultAttorneyImage) ?>" 
                                         class="img-fluid w-100 h-100 object-fit-cover" 
                                         alt="<?= htmlspecialchars($otherAtty['name']) ?>"
                                         style="transition: transform 0.5s ease;">
                                </div>
                                <div class="card-body text-center p-4">
                                    <h5 class="fw-bold text-primary-law mb-1"><?= htmlspecialchars($otherAtty['name']) ?></h5>
                                    <p class="text-gold mb-0"><?= htmlspecialchars($otherAtty['position']) ?></p>
                                </div>
                            </div>
                        </a>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>
</section>

<style>
.attorney-card-mini:hover {
    transform: translateY(-5px);
}

.attorney-card-mini:hover img {
    transform: scale(1.05);
}

.breadcrumb-item + .breadcrumb-item::before {
    content: "›";
    color: var(--law-secondary);
}
</style>
