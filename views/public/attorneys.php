<!-- Hero Section -->
<?php $attorneysBg = $settingModel->get('attorneys_hero_bg', 'https://images.unsplash.com/photo-1528605248644-14dd04022da1?auto=format&fit=crop&q=80&w=2000'); ?>
<section class="hero position-relative bg-primary-law text-white d-flex align-items-center" style="min-height: 50vh; background: linear-gradient(rgba(11, 27, 61, 0.85), rgba(11, 27, 61, 0.95)), url('<?= htmlspecialchars($attorneysBg) ?>') no-repeat center center/cover;">
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
                <div class="card border-0 shadow-sm h-100 overflow-hidden">
                    <div class="row g-0 h-100">
                        <div class="col-md-5">
                            <img src="<?= htmlspecialchars($attorney['image_url']) ?>" alt="<?= htmlspecialchars($attorney['name']) ?>" class="img-fluid h-100 w-100 object-fit-cover">
                        </div>
                        <div class="col-md-7">
                            <div class="card-body p-4 h-100 d-flex flex-column">
                                <div class="d-flex justify-content-between align-items-start mb-2">
                                    <div>
                                        <h3 class="card-title fw-bold mb-1"><?= htmlspecialchars($attorney['name']) ?></h3>
                                        <h6 class="text-gold fw-bold text-uppercase letter-spacing-1 mb-0"><?= htmlspecialchars($attorney['position']) ?></h6>
                                    </div>
                                </div>
                                <p class="text-muted mt-3 mb-4 flex-grow-1"><?= htmlspecialchars($attorney['bio']) ?></p>
                                
                                <div class="d-flex align-items-center gap-3 mt-auto pt-3 border-top">
                                    <?php if(!empty($attorney['linkedin'])): ?>
                                        <a href="<?= htmlspecialchars($attorney['linkedin']) ?>" target="_blank" class="text-primary-law fs-5 hover-gold transition-all"><i class="fab fa-linkedin"></i></a>
                                    <?php endif; ?>
                                    <?php if(!empty($attorney['twitter'])): ?>
                                        <a href="<?= htmlspecialchars($attorney['twitter']) ?>" target="_blank" class="text-primary-law fs-5 hover-gold transition-all"><i class="fab fa-twitter"></i></a>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>
