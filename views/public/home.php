<!-- Hero Section -->
<?php $homeBg = $settingModel->get('home_hero_bg', 'https://images.unsplash.com/photo-1589829085413-56de8ae18c73?auto=format&fit=crop&q=80&w=2000'); ?>
<section class="hero position-relative text-white d-flex align-items-center" style="min-height: 80vh; overflow: hidden;">
    <div class="hero-bg-animated" style="background-image: url('<?= htmlspecialchars($homeBg) ?>');"></div>
    <div class="container position-relative z-1">
        <div class="row align-items-center">
            <div class="col-lg-8 col-xl-7 py-5">
                <div class="badge bg-gold text-primary-law px-3 py-2 text-uppercase letter-spacing-1 mb-4">Established 1995</div>
                <h1 class="display-3 fw-bold mb-4 text-white">Justice & Excellence in Every Case.</h1>
                <p class="lead mb-5 opacity-75" style="font-weight: 300;">We provide world-class legal representation for individuals and corporations. Our experienced attorneys fight tirelessly for your rights and future.</p>
                <div class="d-flex gap-3 flex-wrap">
                    <a href="index.php?page=consultation" class="btn btn-gold btn-lg rounded-0 px-4 py-3">Consultation</a>
                    <a href="index.php?page=practices" class="btn btn-outline-light btn-lg rounded-0 px-4 py-3">Our Practice Areas</a>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- About the Firm -->
<section class="section-padding bg-white">
    <div class="container">
        <div class="row align-items-center g-5">
            <div class="col-lg-6">
                <div class="position-relative">
                    <?php
                    $homeAboutDefault = 'https://images.unsplash.com/photo-1505664154772-a5e475094bc0?auto=format&fit=crop&q=80&w=800';
                    $homeAboutImg = resolve_post_image_url($settingModel->get('home_about_img', $homeAboutDefault), $homeAboutDefault);
                    ?>
                    <img src="<?= htmlspecialchars($homeAboutImg) ?>" alt="Law Firm Office" class="img-fluid shadow-lg" onerror="this.src='<?= htmlspecialchars($homeAboutDefault) ?>'">
                    <div class="position-absolute bottom-0 end-0 bg-gold p-4 text-primary-law translate-middle-y me-n4 d-none d-lg-block">
                        <h2 class="display-4 fw-bold mb-0">25+</h2>
                        <p class="mb-0 fw-bold text-uppercase">Years of Experience</p>
                    </div>
                </div>
            </div>
            <div class="col-lg-6">
                <h6 class="text-gold text-uppercase fw-bold letter-spacing-1">About Our Firm</h6>
                <h2 class="display-5 mb-4">Dedicated to Protecting Your Legal Rights</h2>
                <p class="text-muted mb-4">At Lawyerson & Partners, we believe that everyone deserves exceptional legal representation. Our team of seasoned attorneys brings decades of experience to the table, ensuring that your case is handled with the utmost care, precision, and aggressive advocacy.</p>
                <ul class="list-unstyled mb-4">
                    <li class="mb-3 d-flex align-items-center"><i class="fas fa-check-circle text-gold fs-5 me-3"></i> <span>Award-winning legal team with proven track records.</span></li>
                    <li class="mb-3 d-flex align-items-center"><i class="fas fa-check-circle text-gold fs-5 me-3"></i> <span>Personalized attention and strategy for every client.</span></li>
                    <li class="mb-3 d-flex align-items-center"><i class="fas fa-check-circle text-gold fs-5 me-3"></i> <span>Transparent communication and honest assessment.</span></li>
                </ul>
                <a href="index.php?page=about" class="btn btn-outline-primary-law rounded-0 px-4 py-2 mt-2">Learn More About Us</a>
            </div>
        </div>
    </div>
</section>

<!-- Practice Areas Section -->
<section class="section-padding bg-law-bg">
    <div class="container">
        <div class="row mb-5 text-center">
            <div class="col-lg-6 mx-auto">
                <h6 class="text-gold text-uppercase fw-bold letter-spacing-1">What We Do</h6>
                <h2 class="display-5">Our Practice Areas</h2>
                <div class="mx-auto bg-gold mt-3" style="height: 3px; width: 60px;"></div>
            </div>
        </div>
        
        <div class="row g-4">
            <!-- Card 1 -->
            <div class="col-md-6 col-lg-4">
                <div class="card law-card h-100 p-4">
                    <div class="card-body text-center">
                        <div class="icon-box mx-auto mb-4 bg-law-bg text-gold d-flex align-items-center justify-content-center rounded-circle" style="width: 80px; height: 80px; font-size: 2rem;">
                            <i class="fas fa-briefcase"></i>
                        </div>
                        <h4 class="card-title">Corporate Law</h4>
                        <p class="card-text text-muted">Comprehensive legal solutions for businesses, from formation to complex mergers and acquisitions.</p>
                        <a href="index.php?page=practices" class="text-primary-law fw-bold text-decoration-none mt-3 d-inline-block read-more">Read More <i class="fas fa-arrow-right ms-1 fs-6"></i></a>
                    </div>
                </div>
            </div>
            
            <!-- Card 2 -->
            <div class="col-md-6 col-lg-4">
                <div class="card law-card h-100 p-4">
                    <div class="card-body text-center">
                        <div class="icon-box mx-auto mb-4 bg-law-bg text-gold d-flex align-items-center justify-content-center rounded-circle" style="width: 80px; height: 80px; font-size: 2rem;">
                            <i class="fas fa-home"></i>
                        </div>
                        <h4 class="card-title">Real Estate</h4>
                        <p class="card-text text-muted">Expert guidance in commercial and residential real estate transactions, leasing, and litigation.</p>
                        <a href="index.php?page=practices" class="text-primary-law fw-bold text-decoration-none mt-3 d-inline-block read-more">Read More <i class="fas fa-arrow-right ms-1 fs-6"></i></a>
                    </div>
                </div>
            </div>
            
            <!-- Card 3 -->
            <div class="col-md-6 col-lg-4">
                <div class="card law-card h-100 p-4">
                    <div class="card-body text-center">
                        <div class="icon-box mx-auto mb-4 bg-law-bg text-gold d-flex align-items-center justify-content-center rounded-circle" style="width: 80px; height: 80px; font-size: 2rem;">
                            <i class="fas fa-users"></i>
                        </div>
                        <h4 class="card-title">Family Law</h4>
                        <p class="card-text text-muted">Compassionate and discreet representation in divorce, child custody, and family dispute matters.</p>
                        <a href="index.php?page=practices" class="text-primary-law fw-bold text-decoration-none mt-3 d-inline-block read-more">Read More <i class="fas fa-arrow-right ms-1 fs-6"></i></a>
                    </div>
                </div>
            </div>
            
            <!-- Card 4 -->
            <div class="col-md-6 col-lg-4">
                <div class="card law-card h-100 p-4">
                    <div class="card-body text-center">
                        <div class="icon-box mx-auto mb-4 bg-law-bg text-gold d-flex align-items-center justify-content-center rounded-circle" style="width: 80px; height: 80px; font-size: 2rem;">
                            <i class="fas fa-gavel"></i>
                        </div>
                        <h4 class="card-title">Criminal Defense</h4>
                        <p class="card-text text-muted">Aggressive defense strategies to protect your freedom and reputation in local and federal courts.</p>
                        <a href="index.php?page=practices" class="text-primary-law fw-bold text-decoration-none mt-3 d-inline-block read-more">Read More <i class="fas fa-arrow-right ms-1 fs-6"></i></a>
                    </div>
                </div>
            </div>

            <!-- Card 5 -->
            <div class="col-md-6 col-lg-4">
                <div class="card law-card h-100 p-4">
                    <div class="card-body text-center">
                        <div class="icon-box mx-auto mb-4 bg-law-bg text-gold d-flex align-items-center justify-content-center rounded-circle" style="width: 80px; height: 80px; font-size: 2rem;">
                            <i class="fas fa-car-crash"></i>
                        </div>
                        <h4 class="card-title">Personal Injury</h4>
                        <p class="card-text text-muted">Securing maximum compensation for victims of accidents, medical malpractice, and negligence.</p>
                        <a href="index.php?page=practices" class="text-primary-law fw-bold text-decoration-none mt-3 d-inline-block read-more">Read More <i class="fas fa-arrow-right ms-1 fs-6"></i></a>
                    </div>
                </div>
            </div>

            <!-- Card 6 -->
            <div class="col-md-6 col-lg-4">
                <div class="card law-card h-100 p-4">
                    <div class="card-body text-center">
                        <div class="icon-box mx-auto mb-4 bg-law-bg text-gold d-flex align-items-center justify-content-center rounded-circle" style="width: 80px; height: 80px; font-size: 2rem;">
                            <i class="fas fa-lightbulb"></i>
                        </div>
                        <h4 class="card-title">Intellectual Property</h4>
                        <p class="card-text text-muted">Protecting your patents, trademarks, copyrights, and trade secrets in a competitive market.</p>
                        <a href="index.php?page=practices" class="text-primary-law fw-bold text-decoration-none mt-3 d-inline-block read-more">Read More <i class="fas fa-arrow-right ms-1 fs-6"></i></a>
                    </div>
                </div>
            </div>
        </div>
        <div class="text-center mt-5">
            <a href="index.php?page=practices" class="btn btn-outline-primary-law btn-lg rounded-0 px-4 py-2">View All Practices</a>
        </div>
    </div>
</section>

<!-- Why Choose Us -->
<section class="section-padding bg-primary-law text-white position-relative" style="background: linear-gradient(rgba(11, 27, 61, 0.95), rgba(11, 27, 61, 0.95)), url('https://images.unsplash.com/photo-1589391886645-d51941baf7fb?auto=format&fit=crop&q=80&w=2000') fixed center center/cover;">
    <div class="container">
        <div class="row mb-5 text-center">
            <div class="col-lg-6 mx-auto">
                <h6 class="text-gold text-uppercase fw-bold letter-spacing-1">Our Advantages</h6>
                <h2 class="display-5 text-white">Why Choose Lawyerson</h2>
                <div class="mx-auto bg-gold mt-3" style="height: 3px; width: 60px;"></div>
            </div>
        </div>
        <div class="row g-5 text-center mt-2">
            <div class="col-md-4">
                <div class="p-4 border border-secondary h-100 advantage-box">
                    <div class="display-4 text-gold mb-4"><i class="fas fa-balance-scale"></i></div>
                    <h4 class="text-white mb-3">Unmatched Expertise</h4>
                    <p class="opacity-75">Our team comprises former prosecutors and judges with deep insights into the judicial system.</p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="p-4 border border-secondary h-100 advantage-box bg-white text-primary-law transform-scale">
                    <div class="display-4 text-gold mb-4"><i class="fas fa-trophy"></i></div>
                    <h4 class="text-primary-law mb-3">Record of Success</h4>
                    <p class="text-muted">We have recovered over $500 Million in settlements and verdicts for our clients.</p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="p-4 border border-secondary h-100 advantage-box">
                    <div class="display-4 text-gold mb-4"><i class="fas fa-handshake"></i></div>
                    <h4 class="text-white mb-3">Client-First Approach</h4>
                    <p class="opacity-75">We prioritize your well-being, offering 24/7 support and transparent billing practices.</p>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Lawyer Profiles -->
<section class="section-padding bg-white">
    <div class="container">
        <div class="row align-items-end mb-5">
            <div class="col-md-8">
                <h6 class="text-gold text-uppercase fw-bold letter-spacing-1">Our Experts</h6>
                <h2 class="display-5">Meet Our Attorneys</h2>
            </div>
            <div class="col-md-4 text-md-end mt-4 mt-md-0">
                <a href="index.php?page=attorneys" class="btn btn-outline-primary-law rounded-0 px-4 py-2">View All Attorneys</a>
            </div>
        </div>
        <?php
        $homeAttorneys = [];
        try {
            if (isset($attorneyModel)) {
                $homeAttorneys = array_slice($attorneyModel->getAll(), 0, 4);
            }
        } catch (Exception $e) {}
        
        if (empty($homeAttorneys)) {
            $homeAttorneys = [
                ['name' => 'Jonathan Doe', 'position' => 'Senior Partner', 'image_url' => 'https://images.unsplash.com/photo-1560250097-0b93528c311a?auto=format&fit=crop&q=80&w=400'],
                ['name' => 'Sarah Jenkins', 'position' => 'Managing Partner', 'image_url' => 'https://images.unsplash.com/photo-1573496359142-b8d87734a5a2?auto=format&fit=crop&q=80&w=400'],
                ['name' => 'Michael Chang', 'position' => 'Associate Attorney', 'image_url' => 'https://images.unsplash.com/photo-1519085360753-af0119f7cbe7?auto=format&fit=crop&q=80&w=400'],
                ['name' => 'Elena Rostova', 'position' => 'Criminal Defense', 'image_url' => 'https://images.unsplash.com/photo-1580489944761-15a19d654956?auto=format&fit=crop&q=80&w=400']
            ];
        }
        ?>
        <div class="row g-4">
            <?php foreach($homeAttorneys as $attorney): ?>
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
                    <p class="text-gold fw-medium mb-0"><?= htmlspecialchars($attorney['position']) ?></p>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<!-- Testimonials -->
<section class="section-padding bg-law-bg">
    <div class="container">
        <div class="row mb-5 text-center">
            <div class="col-lg-6 mx-auto">
                <h6 class="text-gold text-uppercase fw-bold letter-spacing-1">Client Stories</h6>
                <h2 class="display-5">What Our Clients Say</h2>
                <div class="mx-auto bg-gold mt-3" style="height: 3px; width: 60px;"></div>
            </div>
        </div>
        
        <div class="row g-4">
            <div class="col-md-6 col-lg-4">
                <div class="card testimonial-card border-0 p-4 h-100">
                    <div class="card-body">
                        <div class="text-gold mb-3 fs-5">
                            <i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i>
                        </div>
                        <p class="fst-italic text-muted mb-4">"Lawyerson & Partners provided exceptional service during my corporate merger. Their attention to detail and strategic foresight saved us millions."</p>
                        <div class="d-flex align-items-center">
                            <div class="bg-primary-law text-white rounded-circle d-flex align-items-center justify-content-center fw-bold me-3" style="width: 50px; height: 50px;">RP</div>
                            <div>
                                <h6 class="mb-0 fw-bold">Robert Peterson</h6>
                                <small class="text-muted">CEO, TechCorp</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="col-md-6 col-lg-4">
                <div class="card testimonial-card border-0 p-4 h-100">
                    <div class="card-body">
                        <div class="text-gold mb-3 fs-5">
                            <i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i>
                        </div>
                        <p class="fst-italic text-muted mb-4">"I was facing a highly complex real estate dispute, and Sarah Jenkins handled it brilliantly. She kept me informed every step of the way."</p>
                        <div class="d-flex align-items-center">
                            <div class="bg-primary-law text-white rounded-circle d-flex align-items-center justify-content-center fw-bold me-3" style="width: 50px; height: 50px;">AM</div>
                            <div>
                                <h6 class="mb-0 fw-bold">Amanda Miller</h6>
                                <small class="text-muted">Real Estate Investor</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="col-md-6 col-lg-4">
                <div class="card testimonial-card border-0 p-4 h-100">
                    <div class="card-body">
                        <div class="text-gold mb-3 fs-5">
                            <i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i>
                        </div>
                        <p class="fst-italic text-muted mb-4">"The compassion and professionalism shown by the family law team made a very difficult time in my life manageable. Highly recommended."</p>
                        <div class="d-flex align-items-center">
                            <div class="bg-primary-law text-white rounded-circle d-flex align-items-center justify-content-center fw-bold me-3" style="width: 50px; height: 50px;">DB</div>
                            <div>
                                <h6 class="mb-0 fw-bold">David Brooks</h6>
                                <small class="text-muted">Private Client</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Call to Action Section -->
<section class="py-5 bg-gold text-primary-law text-center">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-8 text-lg-start mb-4 mb-lg-0">
                <h2 class="mb-2 fw-bold text-primary-law">Need Immediate Legal Assistance?</h2>
                <p class="lead mb-0 fw-medium">Contact our experienced attorneys today for a confidential review of your case.</p>
            </div>
            <div class="col-lg-4 text-lg-end">
                <a href="tel:5551234567" class="btn btn-primary-law btn-lg rounded-0 px-5 py-3 shadow-sm d-inline-flex align-items-center">
                    <i class="fas fa-phone-alt me-2"></i> (555) 123-4567
                </a>
            </div>
        </div>
    </div>
</section>

<!-- Blog Preview -->
<section class="section-padding bg-white">
    <div class="container">
        <div class="row align-items-end mb-5">
            <div class="col-md-8">
                <h6 class="text-gold text-uppercase fw-bold letter-spacing-1">Legal Insights</h6>
                <h2 class="display-5">Latest News & Articles</h2>
            </div>
            <div class="col-md-4 text-md-end mt-4 mt-md-0">
                <a href="index.php?page=blog" class="btn btn-outline-primary-law rounded-0 px-4 py-2">View All Articles</a>
            </div>
        </div>
        
        <?php
        $homePosts = [];
        try {
            if (isset($postModel)) {
                $homePosts = array_slice($postModel->getAll(true), 0, 3);
            }
        } catch(Exception $e) {}
        
        $homeDefaultImage = 'https://images.unsplash.com/photo-1450101499163-c8848c66ca85?auto=format&fit=crop&q=80&w=600';
        ?>

        <?php if (!empty($homePosts)): ?>
        <div class="row g-4">
            <?php foreach($homePosts as $post): ?>
            <div class="col-md-6 col-lg-4">
                <div class="card border-0 shadow-sm h-100 blog-card">
                    <?php $homeImage = resolve_post_image_url($post['image_url'] ?? '', $homeDefaultImage); ?>
                    <img src="<?= htmlspecialchars($homeImage) ?>" class="card-img-top rounded-0" alt="<?= htmlspecialchars($post['title']) ?>" style="height: 250px; object-fit: cover;" onerror="this.src='<?= htmlspecialchars($homeDefaultImage) ?>'">
                    <div class="card-body p-4 position-relative d-flex flex-column">
                        <div class="bg-gold text-primary-law position-absolute top-0 translate-middle-y px-3 py-1 fw-bold text-uppercase" style="font-size: 0.8rem;"><?= htmlspecialchars($post['category']) ?></div>
                        <div class="text-muted small mb-2 mt-2"><i class="far fa-calendar-alt me-2"></i> <?= date('M d, Y', strtotime($post['created_at'])) ?></div>
                        <h5 class="card-title fw-bold mb-3"><a href="index.php?page=article&id=<?= $post['id'] ?>" class="text-primary-law text-decoration-none"><?= htmlspecialchars($post['title']) ?></a></h5>
                        <p class="card-text text-muted mb-4 flex-grow-1"><?= htmlspecialchars(substr(strip_tags($post['content']), 0, 120)) ?>...</p>
                        <a href="index.php?page=article&id=<?= $post['id'] ?>" class="text-gold fw-bold text-decoration-none read-more mt-auto">Read Article <i class="fas fa-arrow-right ms-1"></i></a>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
        <?php endif; ?>
    </div>
</section>

<!-- Contact Section -->
<section class="section-padding bg-law-bg">
    <div class="container">
        <div class="row bg-white shadow-sm overflow-hidden rounded-1">
            <div class="col-lg-5 p-0 d-none d-lg-block">
                <?php $homeContactBg = $settingModel->get('home_contact_bg', 'https://images.unsplash.com/photo-1423592707957-3b827395c9c4?auto=format&fit=crop&q=80&w=800'); ?>
                <img src="<?= htmlspecialchars($homeContactBg) ?>" alt="Contact Us" class="img-fluid h-100 w-100 object-fit-cover">
            </div>
            <div class="col-lg-7 p-5">
                <h6 class="text-gold text-uppercase fw-bold letter-spacing-1 mb-2">Get In Touch</h6>
                <h2 class="display-6 mb-4">Request a Consultation</h2>
                <form action="index.php" method="POST">
                    <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?>">
                    <input type="hidden" name="action" value="book_consultation">
                    <input type="hidden" name="redirect_page" value="home">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <input type="text" name="name" class="form-control rounded-0 p-3 bg-law-bg border-0" placeholder="Your Name" required>
                        </div>
                        <div class="col-md-6">
                            <input type="email" name="email" class="form-control rounded-0 p-3 bg-law-bg border-0" placeholder="Email Address" required>
                        </div>
                        <div class="col-md-6">
                            <input type="tel" name="phone" class="form-control rounded-0 p-3 bg-law-bg border-0" placeholder="Phone Number" required>
                        </div>
                        <div class="col-md-6">
                            <select name="practice_area" class="form-select rounded-0 p-3 bg-law-bg border-0" required>
                                <option value="" disabled selected>Practice Area</option>
                                <option value="corporate">Corporate Law</option>
                                <option value="real_estate">Real Estate</option>
                                <option value="family">Family Law</option>
                                <option value="criminal">Criminal Defense</option>
                                <option value="injury">Personal Injury</option>
                            </select>
                        </div>
                        <div class="col-12">
                            <textarea name="case_details" class="form-control rounded-0 p-3 bg-law-bg border-0" rows="5" placeholder="Case Details" required></textarea>
                        </div>
                        <div class="col-12 mt-4">
                            <button type="submit" class="btn btn-primary-law w-100 rounded-0 py-3 fw-bold fs-5">Submit Request</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</section>
