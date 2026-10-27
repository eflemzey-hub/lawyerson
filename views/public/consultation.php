<!-- Hero -->
<section class="hero position-relative bg-primary-law text-white py-5">
    <div class="container text-center py-5">
        <h1 class="display-4 fw-bold mb-3 text-white">Book a Consultation</h1>
        <p class="lead opacity-75 mx-auto" style="max-width: 600px; font-weight: 300;">Take the first step towards resolving your legal matters. Schedule a confidential consultation with our experts.</p>
    </div>
</section>

<!-- Form Section -->
<section class="section-padding bg-law-bg">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <?php if(isset($_GET['success'])): ?>
                    <div class="alert alert-success border-0 rounded-0 shadow-sm p-4 mb-4 d-flex align-items-center">
                        <i class="fas fa-check-circle fs-3 me-3"></i>
                        <div>
                            <h5 class="alert-heading mb-1 fw-bold">Request Submitted!</h5>
                            <p class="mb-0">Your consultation request has been received. Our team will contact you shortly to confirm the appointment.</p>
                        </div>
                    </div>
                <?php endif; ?>

                <div class="card border-0 shadow-sm rounded-1">
                    <div class="card-body p-5">
                        <h3 class="mb-4 text-primary-law">Client Information</h3>
                        <form action="index.php" method="POST">
                            <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?>">
                            <input type="hidden" name="action" value="book_consultation">
                            
                            <div class="row g-3">
                                <div class="col-md-12">
                                    <label class="form-label fw-bold text-muted small">Full Name</label>
                                    <input type="text" name="name" class="form-control rounded-0 p-3 bg-law-bg border-0" required placeholder="John Doe">
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label fw-bold text-muted small">Email Address</label>
                                    <input type="email" name="email" class="form-control rounded-0 p-3 bg-law-bg border-0" required placeholder="john@example.com">
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label fw-bold text-muted small">Phone Number</label>
                                    <input type="tel" name="phone" class="form-control rounded-0 p-3 bg-law-bg border-0" required placeholder="(555) 123-4567">
                                </div>
                                
                                <h3 class="mb-2 mt-5 text-primary-law">Appointment Details</h3>
                                
                                <div class="col-md-6">
                                    <label class="form-label fw-bold text-muted small">Preferred Date</label>
                                    <input type="date" name="date" class="form-control rounded-0 p-3 bg-law-bg border-0" required>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label fw-bold text-muted small">Practice Area</label>
                                    <select name="practice_area" class="form-select rounded-0 p-3 bg-law-bg border-0" required>
                                        <option value="" disabled selected>Select an area...</option>
                                        <option value="Corporate Law">Corporate Law</option>
                                        <option value="Real Estate">Real Estate</option>
                                        <option value="Family Law">Family Law</option>
                                        <option value="Criminal Defense">Criminal Defense</option>
                                        <option value="Immigration">Immigration</option>
                                        <option value="Civil Litigation">Civil Litigation</option>
                                        <option value="Other">Other / Unsure</option>
                                    </select>
                                </div>
                                <div class="col-12 mt-3">
                                    <label class="form-label fw-bold text-muted small">Brief Description of Your Case</label>
                                    <textarea name="details" class="form-control rounded-0 p-3 bg-law-bg border-0" rows="5" placeholder="Please provide some context..." required></textarea>
                                </div>
                                <div class="col-12 mt-4">
                                    <button type="submit" class="btn btn-gold w-100 rounded-0 py-3 fw-bold fs-5">Submit Appointment Request</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
