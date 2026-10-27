<!-- About Hero Section -->
<?php $aboutBg = $settingModel->get('about_hero_bg', 'https://images.unsplash.com/photo-1454165804606-c3d57bc86b40?auto=format&fit=crop&q=80&w=2000'); ?>
<section class="hero position-relative text-white d-flex align-items-center" style="min-height: 50vh; overflow: hidden;">
    <div class="hero-bg-animated" style="background-image: url('<?= htmlspecialchars($aboutBg) ?>');"></div>
    <div class="container text-center py-5">
        <h1 class="display-4 fw-bold mb-3 text-white">About Our Firm</h1>
        <p class="lead opacity-75 mx-auto" style="max-width: 600px; font-weight: 300;">A legacy of excellence, integrity, and relentless advocacy for our clients since 1995.</p>
    </div>
</section>

<!-- Company History -->
<section class="section-padding bg-white">
    <div class="container">
        <div class="row align-items-center g-5">
            <div class="col-lg-6">
                <h6 class="text-gold text-uppercase fw-bold letter-spacing-1">Our History</h6>
                <h2 class="display-5 mb-4">Over Two Decades of Legal Excellence</h2>
                <p class="text-muted mb-4">Founded in 1995 by Jonathan Doe and Sarah Jenkins, Lawyerson & Partners started as a boutique firm specializing in corporate law. Over the years, we have grown into a full-service law firm with a team of over 50 dedicated legal professionals.</p>
                <p class="text-muted mb-4">Our journey has been defined by landmark victories, relentless dedication to our clients, and a commitment to upholding the highest standards of the legal profession. We have expanded our practice areas to meet the diverse needs of our clients, always maintaining the personalized approach that has been our hallmark since day one.</p>
                <div class="d-flex align-items-center mt-4">
                    <?php $founderImg = $settingModel->get('about_founder_img', 'https://images.unsplash.com/photo-1560250097-0b93528c311a?auto=format&fit=crop&q=80&w=100'); ?>
                    <img src="<?= htmlspecialchars($founderImg) ?>" class="rounded-circle me-3" style="width: 60px; height: 60px; object-fit: cover;" alt="Founder">
                    <div>
                        <h6 class="mb-0 fw-bold">Jonathan Doe</h6>
                        <small class="text-gold">Founding Partner</small>
                    </div>
                </div>
            </div>
            <div class="col-lg-6">
                <div class="row g-3">
                    <div class="col-6">
                        <?php $aboutHistory1 = $settingModel->get('about_history_img_1', 'https://images.unsplash.com/photo-1589829085413-56de8ae18c73?auto=format&fit=crop&q=80&w=400'); ?>
                        <img src="<?= htmlspecialchars($aboutHistory1) ?>" alt="History" class="img-fluid rounded shadow-sm w-100" style="height: 250px; object-fit: cover;">
                    </div>
                    <div class="col-6 mt-5">
                        <?php $aboutHistory2 = $settingModel->get('about_history_img_2', 'https://images.unsplash.com/photo-1505664154772-a5e475094bc0?auto=format&fit=crop&q=80&w=400'); ?>
                        <img src="<?= htmlspecialchars($aboutHistory2) ?>" alt="History" class="img-fluid rounded shadow-sm w-100" style="height: 250px; object-fit: cover;">
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Mission and Vision -->
<section class="section-padding bg-law-bg">
    <div class="container">
        <div class="row g-5">
            <div class="col-lg-6">
                <div class="bg-white p-5 h-100 shadow-sm border-top border-gold border-3">
                    <div class="text-gold fs-1 mb-4"><i class="fas fa-bullseye"></i></div>
                    <h3 class="mb-3">Our Mission</h3>
                    <p class="text-muted mb-0">To provide exceptional, strategic, and aggressive legal representation while maintaining the highest ethical standards. We are dedicated to understanding our clients' unique objectives and delivering results that protect their interests and secure their future.</p>
                </div>
            </div>
            <div class="col-lg-6">
                <div class="bg-white p-5 h-100 shadow-sm border-top border-primary-law border-3">
                    <div class="text-primary-law fs-1 mb-4"><i class="fas fa-eye"></i></div>
                    <h3 class="mb-3">Our Vision</h3>
                    <p class="text-muted mb-0">To be the most trusted and respected law firm in the nation, known for our unwavering commitment to justice, innovative legal solutions, and the profound impact we make in the lives of our clients and our community.</p>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Core Values -->
<section class="section-padding bg-primary-law text-white">
    <div class="container">
        <div class="row mb-5 text-center">
            <div class="col-lg-6 mx-auto">
                <h6 class="text-gold text-uppercase fw-bold letter-spacing-1">What Drives Us</h6>
                <h2 class="display-5 text-white">Our Core Values</h2>
                <div class="mx-auto bg-gold mt-3" style="height: 3px; width: 60px;"></div>
            </div>
        </div>
        <div class="row g-4 text-center mt-2">
            <div class="col-md-3">
                <div class="display-4 text-gold mb-3"><i class="fas fa-balance-scale-left"></i></div>
                <h4 class="text-white mb-3">Integrity</h4>
                <p class="opacity-75 small">We conduct ourselves with the utmost honesty and ethical standards in every matter we handle.</p>
            </div>
            <div class="col-md-3">
                <div class="display-4 text-gold mb-3"><i class="fas fa-medal"></i></div>
                <h4 class="text-white mb-3">Excellence</h4>
                <p class="opacity-75 small">We strive for perfection and continuous improvement in our legal knowledge and strategy.</p>
            </div>
            <div class="col-md-3">
                <div class="display-4 text-gold mb-3"><i class="fas fa-users"></i></div>
                <h4 class="text-white mb-3">Compassion</h4>
                <p class="opacity-75 small">We understand the stress our clients face and offer empathetic, supportive counsel.</p>
            </div>
            <div class="col-md-3">
                <div class="display-4 text-gold mb-3"><i class="fas fa-fist-raised"></i></div>
                <h4 class="text-white mb-3">Tenacity</h4>
                <p class="opacity-75 small">We are relentless in pursuing the best possible outcomes for those we represent.</p>
            </div>
        </div>
    </div>
</section>

<!-- Achievements Section -->
<section class="py-5 bg-gold text-primary-law">
    <div class="container py-4">
        <div class="row text-center g-4">
            <div class="col-6 col-md-3 border-end border-primary-law border-opacity-25">
                <h2 class="display-4 fw-bold mb-0">28+</h2>
                <p class="text-uppercase fw-bold letter-spacing-1 mb-0 small">Years Experience</p>
            </div>
            <div class="col-6 col-md-3 border-end-md border-primary-law border-opacity-25">
                <h2 class="display-4 fw-bold mb-0">98%</h2>
                <p class="text-uppercase fw-bold letter-spacing-1 mb-0 small">Success Rate</p>
            </div>
            <div class="col-6 col-md-3 border-end border-primary-law border-opacity-25">
                <h2 class="display-4 fw-bold mb-0">$500M</h2>
                <p class="text-uppercase fw-bold letter-spacing-1 mb-0 small">Recovered</p>
            </div>
            <div class="col-6 col-md-3">
                <h2 class="display-4 fw-bold mb-0">15k+</h2>
                <p class="text-uppercase fw-bold letter-spacing-1 mb-0 small">Cases Won</p>
            </div>
        </div>
    </div>
</section>

<!-- Lawyer Team Section -->
<section class="section-padding bg-white">
    <div class="container">
        <div class="row mb-5 text-center">
            <div class="col-lg-6 mx-auto">
                <h6 class="text-gold text-uppercase fw-bold letter-spacing-1">Legal Minds</h6>
                <h2 class="display-5">Our Expert Team</h2>
                <div class="mx-auto bg-gold mt-3" style="height: 3px; width: 60px;"></div>
                <p class="text-muted mt-4">Meet the brilliant legal minds dedicated to securing your rights and fighting for your future. Our attorneys are recognized leaders in their respective fields.</p>
            </div>
        </div>
        
        <?php
        $aboutAttorneys = [];
        try {
            if (isset($attorneyModel)) {
                $aboutAttorneys = $attorneyModel->getAll();
            }
        } catch (Exception $e) {}
        
        if (empty($aboutAttorneys)) {
            $aboutAttorneys = [
                ['name' => 'Jonathan Doe', 'position' => 'Senior Partner', 'bio' => 'Expert in corporate law and complex mergers.', 'image_url' => 'https://images.unsplash.com/photo-1560250097-0b93528c311a?auto=format&fit=crop&q=80&w=400'],
                ['name' => 'Sarah Jenkins', 'position' => 'Managing Partner', 'bio' => 'Top-tier real estate and litigation attorney.', 'image_url' => 'https://images.unsplash.com/photo-1573496359142-b8d87734a5a2?auto=format&fit=crop&q=80&w=400'],
                ['name' => 'Michael Chang', 'position' => 'Associate Attorney', 'bio' => 'Specializes in intellectual property protection.', 'image_url' => 'https://images.unsplash.com/photo-1519085360753-af0119f7cbe7?auto=format&fit=crop&q=80&w=400'],
                ['name' => 'Elena Rostova', 'position' => 'Criminal Defense', 'bio' => 'Compassionate advocate for families.', 'image_url' => 'https://images.unsplash.com/photo-1580489944761-15a19d654956?auto=format&fit=crop&q=80&w=400']
            ];
        }
        ?>
        <div class="row g-4">
            <?php foreach($aboutAttorneys as $attorney): ?>
            <div class="col-md-6 col-lg-3">
                <div class="team-card text-center group">
                    <div class="overflow-hidden position-relative mb-4 team-img-wrapper">
                        <img src="<?= htmlspecialchars($attorney['image_url']) ?>" alt="<?= htmlspecialchars($attorney['name']) ?>" class="img-fluid w-100" style="height: 350px; object-fit: cover;">
                        <div class="team-social position-absolute bottom-0 w-100 p-3 d-flex justify-content-center gap-2">
                            <a href="<?= htmlspecialchars($attorney['linkedin'] ?? '#') ?>" class="btn btn-gold btn-sm rounded-circle"><i class="fab fa-linkedin-in"></i></a>
                            <a href="<?= htmlspecialchars($attorney['twitter'] ?? '#') ?>" class="btn btn-gold btn-sm rounded-circle"><i class="fab fa-twitter"></i></a>
                        </div>
                    </div>
                    <h4 class="mb-1"><?= htmlspecialchars($attorney['name']) ?></h4>
                    <p class="text-gold fw-medium mb-2"><?= htmlspecialchars($attorney['position']) ?></p>
                    <p class="small text-muted px-2 text-truncate" title="<?= htmlspecialchars($attorney['bio']) ?>"><?= htmlspecialchars($attorney['bio']) ?></p>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>
