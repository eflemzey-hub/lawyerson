<!-- Hero Section -->
<?php $attorneysBg = $settingModel->get('attorneys_hero_bg', 'https://images.unsplash.com/photo-1528605248644-14dd04022da1?auto=format&fit=crop&q=80&w=2000'); ?>
<section class="hero position-relative text-white d-flex align-items-center" style="min-height: 50vh; overflow: hidden;">
    <div class="hero-bg-animated" style="background-image: url('<?= htmlspecialchars($attorneysBg) ?>');"></div>
    <div class="container text-center">
        <h1 class="display-4 fw-bold mb-3 text-white">Our Attorneys</h1>
        <p class="lead opacity-75 mx-auto" style="max-width: 600px;">Meet our dedicated team of legal professionals committed to fighting for your rights.</p>
    </div>
</section>

<!-- Lawyer Profiles Section -->
<section class="section-padding bg-law-bg">
    <?php
    $attorneysList = [];
    try {
        if (isset($attorneyModel)) {
            $attorneysList = $attorneyModel->getAll();
        }
    } catch (Exception $e) {}
    
    if (empty($attorneysList)) {
        $attorneysList = [
            ['name' => 'Jonathan Doe', 'position' => 'Senior Partner', 'bio' => 'Expert in corporate law with over 20 years of experience.', 'image_url' => 'https://images.unsplash.com/photo-1560250097-0b93528c311a?auto=format&fit=crop&q=80&w=400'],
            ['name' => 'Sarah Jenkins', 'position' => 'Managing Partner', 'bio' => 'Specializes in family law and civil litigation.', 'image_url' => 'https://images.unsplash.com/photo-1573496359142-b8d87734a5a2?auto=format&fit=crop&q=80&w=400'],
            ['name' => 'Michael Chang', 'position' => 'Associate Attorney', 'bio' => 'Focuses on real estate and property disputes.', 'image_url' => 'https://images.unsplash.com/photo-1519085360753-af0119f7cbe7?auto=format&fit=crop&q=80&w=400'],
            ['name' => 'Elena Rostova', 'position' => 'Criminal Defense', 'bio' => 'Dedicated defense attorney with a track record of success.', 'image_url' => 'https://images.unsplash.com/photo-1580489944761-15a19d654956?auto=format&fit=crop&q=80&w=400']
        ];
    }
    ?>
    <div class="container">
        <div class="row g-5">
            <?php foreach($attorneysList as $attorney): ?>
            <div class="col-lg-6">
                <a href="index.php?page=attorney&id=<?= $attorney['id'] ?>" class="text-decoration-none d-block h-100">
                    <div class="card border-0 shadow-sm h-100 overflow-hidden attorney-card" style="transition: transform 0.3s ease, box-shadow 0.3s ease;">
                        <div class="row g-0 h-100">
                            <div class="col-md-5 position-relative overflow-hidden">
                                <img src="<?= htmlspecialchars($attorney['image_url']) ?>" 
                                     alt="<?= htmlspecialchars($attorney['name']) ?>" 
                                     class="img-fluid h-100 w-100 object-fit-cover"
                                     style="transition: transform 0.5s ease;">
                            </div>
                            <div class="col-md-7">
                                <div class="card-body p-4 h-100 d-flex flex-column">
                                    <div class="mb-3">
                                        <h3 class="card-title fw-bold mb-1 text-primary-law"><?= htmlspecialchars($attorney['name']) ?></h3>
                                        <h6 class="text-gold fw-bold text-uppercase letter-spacing-1 mb-0" style="font-size: 0.85rem;">
                                            <?= htmlspecialchars($attorney['position']) ?>
                                        </h6>
                                    </div>
                                    
                                    <?php if ($attorney['specialty']): ?>
                                        <div class="mb-3">
                                            <small class="text-muted d-block mb-1"><i class="fas fa-briefcase me-2"></i>Specialties:</small>
                                            <p class="mb-0 text-muted small">
                                                <?= htmlspecialchars(substr($attorney['specialty'], 0, 100)) ?><?= strlen($attorney['specialty']) > 100 ? '...' : '' ?>
                                            </p>
                                        </div>
                                    <?php endif; ?>
                                    
                                    <p class="text-muted mb-4 flex-grow-1">
                                        <?= htmlspecialchars(substr($attorney['bio'], 0, 150)) ?><?= strlen($attorney['bio']) > 150 ? '...' : '' ?>
                                    </p>
                                    
                                    <?php if ($attorney['years_experience']): ?>
                                        <div class="mb-3 text-muted small">
                                            <i class="fas fa-award text-gold me-2"></i><?= $attorney['years_experience'] ?> Years Experience
                                        </div>
                                    <?php endif; ?>
                                    
                                    <div class="d-flex align-items-center justify-content-between mt-auto pt-3 border-top">
                                        <div class="d-flex align-items-center gap-2">
                                            <?php if(!empty($attorney['linkedin'])): ?>
                                                <span class="text-primary-law fs-6"><i class="fab fa-linkedin"></i></span>
                                            <?php endif; ?>
                                            <?php if(!empty($attorney['twitter'])): ?>
                                                <span class="text-primary-law fs-6"><i class="fab fa-twitter"></i></span>
                                            <?php endif; ?>
                                            <?php if(!empty($attorney['facebook'])): ?>
                                                <span class="text-primary-law fs-6"><i class="fab fa-facebook-f"></i></span>
                                            <?php endif; ?>
                                        </div>
                                        <span class="text-gold fw-bold small">
                                            View Profile <i class="fas fa-arrow-right ms-1"></i>
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </a>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<style>
.attorney-card {
    cursor: pointer;
}

.attorney-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 1rem 2rem rgba(0, 0, 0, 0.15) !important;
}

.attorney-card:hover img {
    transform: scale(1.05);
}

.attorney-card:hover .text-gold {
    padding-left: 5px;
    transition: padding-left 0.3s ease;
}

.attorney-card:hover .fa-arrow-right {
    transform: translateX(4px);
    transition: transform 0.3s ease;
}
</style>
