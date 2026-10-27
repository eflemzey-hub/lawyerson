<footer class="law-footer">
    <div class="container">
        <div class="row g-4">
            <!-- Brand & About -->
            <div class="col-lg-4 col-md-6">
                <h3 class="text-white mb-4" style="font-family: var(--font-heading);">
                    <?= format_site_name_html($siteName ?? 'Lawyerson & Partners') ?>
                </h3>
                <p>Providing exceptional legal counsel and aggressive representation with a commitment to integrity, excellence, and justice.</p>
                <div class="mt-4">
                    <?php $variant = 'footer'; require __DIR__ . '/social_icons.php'; ?>
                </div>
            </div>

            <!-- Practice Areas -->
            <div class="col-lg-3 col-md-6">
                <h5>Practice Areas</h5>
                <ul class="list-unstyled">
                    <li class="mb-2"><a href="index.php?page=practices">Corporate Law</a></li>
                    <li class="mb-2"><a href="index.php?page=practices">Real Estate</a></li>
                    <li class="mb-2"><a href="index.php?page=practices">Intellectual Property</a></li>
                    <li class="mb-2"><a href="index.php?page=practices">Family Law</a></li>
                    <li class="mb-2"><a href="index.php?page=practices">Criminal Defense</a></li>
                </ul>
            </div>

            <!-- Quick Links -->
            <div class="col-lg-2 col-md-6">
                <h5>Quick Links</h5>
                <ul class="list-unstyled">
                    <li class="mb-2"><a href="index.php?page=about">About Us</a></li>
                    <li class="mb-2"><a href="index.php?page=attorneys">Our Attorneys</a></li>
                    <li class="mb-2"><a href="index.php?page=blog">Case Studies</a></li>
                    <li class="mb-2"><a href="index.php?page=contact">Careers</a></li>
                    <li class="mb-2"><a href="index.php?page=contact">Contact</a></li>
                </ul>
            </div>

            <!-- Contact -->
            <div class="col-lg-3 col-md-6">
                <h5>Contact Us</h5>
                <ul class="list-unstyled text-light">
                    <li class="mb-3">
                        <i class="fas fa-map-marker-alt text-gold me-2"></i> 
                        123 Legal Avenue, Suite 500<br>
                        <span class="ms-4">New York, NY 10001</span>
                    </li>
                    <li class="mb-3">
                        <a href="tel:5551234567" class="text-light text-decoration-none"><i class="fas fa-phone-alt text-gold me-2"></i> (555) 123-4567</a>
                    </li>
                    <li class="mb-3">
                        <a href="mailto:<?= htmlspecialchars($contactEmail ?? 'lawyersongroupllp@gmail.com') ?>" class="text-light text-decoration-none"><i class="fas fa-envelope text-gold me-2"></i> <?= htmlspecialchars($contactEmail ?? 'lawyersongroupllp@gmail.com') ?></a>
                    </li>
                </ul>
            </div>
        </div>

        <div class="row footer-bottom text-center">
            <div class="col-12">
                <p class="mb-0"><?= htmlspecialchars($footerText ?? ('© ' . date('Y') . ' Lawyerson & Partners. All rights reserved.')) ?> | <a href="index.php?page=about">Privacy Policy</a></p>
            </div>
        </div>
    </div>
</footer>
