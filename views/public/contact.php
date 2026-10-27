<!-- Hero Section -->
<?php $contactBg = $settingModel->get('contact_hero_bg', 'https://images.unsplash.com/photo-1497366216548-37526070297c?auto=format&fit=crop&q=80&w=2000'); ?>
<section class="hero position-relative bg-primary-law text-white py-5" style="background: linear-gradient(rgba(11, 27, 61, 0.9), rgba(11, 27, 61, 0.9)), url('<?= htmlspecialchars($contactBg) ?>') no-repeat center center/cover;">
    <div class="container text-center py-5 mt-5">
        <h1 class="display-4 fw-bold mb-3 text-white">Contact Us</h1>
        <p class="lead opacity-75 mx-auto" style="max-width: 600px; font-weight: 300;">Reach out to our team of legal experts. We are here to answer your questions and provide the guidance you need.</p>
    </div>
</section>

<section class="section-padding bg-law-bg">
    <div class="container">
        <div class="row g-5">
            <!-- Contact Info Column -->
            <div class="col-lg-5">
                <h6 class="text-gold text-uppercase fw-bold letter-spacing-1 mb-2">Get in Touch</h6>
                <h2 class="display-6 mb-4 text-primary-law">Our Offices</h2>
                
                <p class="text-muted mb-5">Whether you're facing a complex corporate dispute or need personal legal counsel, our doors are open. Contact us using the details below or fill out the form.</p>

                <!-- Headquarters -->
                <div class="d-flex mb-4">
                    <div class="text-gold fs-3 me-4"><i class="fas fa-map-marker-alt"></i></div>
                    <div>
                        <h5 class="fw-bold text-primary-law mb-1">Headquarters</h5>
                        <p class="text-muted mb-0">1200 Legal Avenue, Suite 400<br>New York, NY 10001</p>
                    </div>
                </div>

                <!-- Secondary Office -->
                <div class="d-flex mb-4">
                    <div class="text-gold fs-3 me-4"><i class="fas fa-building"></i></div>
                    <div>
                        <h5 class="fw-bold text-primary-law mb-1">Washington D.C. Office</h5>
                        <p class="text-muted mb-0">450 Justice Blvd, Floor 12<br>Washington, DC 20004</p>
                    </div>
                </div>

                <!-- Phone -->
                <div class="d-flex mb-4">
                    <div class="text-gold fs-3 me-4"><i class="fas fa-phone-alt"></i></div>
                    <div>
                        <h5 class="fw-bold text-primary-law mb-1">Phone</h5>
                        <p class="text-muted mb-0">Main: (212) 555-0198<br>Toll-Free: 1-800-LAW-FIRM</p>
                    </div>
                </div>

                <!-- Email -->
                <div class="d-flex mb-4">
                    <div class="text-gold fs-3 me-4"><i class="fas fa-envelope"></i></div>
                    <div>
                        <h5 class="fw-bold text-primary-law mb-1">Email</h5>
                        <p class="text-muted mb-0">General: <?= htmlspecialchars($contactEmail ?? 'lawyersongroupllp@gmail.com') ?><br>Support: <?= htmlspecialchars($contactEmail ?? 'lawyersongroupllp@gmail.com') ?></p>
                    </div>
                </div>
            </div>

            <!-- Contact Form Column -->
            <div class="col-lg-7">
                <div class="card border-0 shadow-sm rounded-1 h-100">
                    <div class="card-body p-5">
                        <h3 class="mb-4 text-primary-law">Send Us a Message</h3>
                        
                        <?php if(isset($_GET['sent'])): ?>
                            <div class="alert alert-success border-0 rounded-0 mb-4 small fw-medium">
                                <i class="fas fa-check-circle me-2"></i> Thank you! Your message has been sent successfully.
                            </div>
                        <?php endif; ?>

                        <form action="index.php" method="POST">
                            <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?>">
                            <input type="hidden" name="action" value="send_contact">
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label class="form-label text-muted small fw-bold">First Name</label>
                                    <input type="text" name="first_name" class="form-control rounded-0 p-3 bg-law-bg border-0" required>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label text-muted small fw-bold">Last Name</label>
                                    <input type="text" name="last_name" class="form-control rounded-0 p-3 bg-law-bg border-0" required>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label text-muted small fw-bold">Email Address</label>
                                    <input type="email" name="email" class="form-control rounded-0 p-3 bg-law-bg border-0" required>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label text-muted small fw-bold">Phone (Optional)</label>
                                    <input type="tel" name="phone" class="form-control rounded-0 p-3 bg-law-bg border-0">
                                </div>
                                <div class="col-12">
                                    <label class="form-label text-muted small fw-bold">Subject</label>
                                    <input type="text" name="subject" class="form-control rounded-0 p-3 bg-law-bg border-0" required>
                                </div>
                                <div class="col-12">
                                    <label class="form-label text-muted small fw-bold">Message</label>
                                    <textarea name="message" class="form-control rounded-0 p-3 bg-law-bg border-0" rows="5" required></textarea>
                                </div>
                                <div class="col-12 mt-4">
                                    <button type="submit" class="btn btn-primary-law w-100 rounded-0 py-3 fw-bold letter-spacing-1 text-uppercase">Send Message <i class="fas fa-paper-plane ms-2"></i></button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- OpenStreetMap Integration -->
<section class="bg-light">
    <div class="w-100" style="height: 450px;">
        <iframe 
            width="100%" 
            height="100%" 
            frameborder="0" 
            scrolling="no" 
            marginheight="0" 
            marginwidth="0" 
            src="https://www.openstreetmap.org/export/embed.html?bbox=-73.9981%2C40.7384%2C-73.9781%2C40.7584&amp;layer=mapnik&amp;marker=40.7484%2C-73.9881" 
            style="border: 1px solid #ccc;">
        </iframe>
    </div>
</section>
<small>
    <a href="https://www.openstreetmap.org/?mlat=40.7484&amp;mlon=-73.9881#map=15/40.7484/-73.9881" target="_blank">View Larger Map</a>
</small>
