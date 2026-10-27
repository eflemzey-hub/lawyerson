<!-- Top Header Bar -->
<div class="bg-gold border-bottom border-dark border-opacity-10">
    <div class="container">
        <div class="d-flex justify-content-between align-items-center">
            <div class="d-flex w-100 w-lg-auto justify-content-between justify-content-lg-start align-items-center text-primary-law fw-semibold py-2 top-contact-text">
                <div class="me-lg-4 text-truncate">
                    <a href="mailto:<?= htmlspecialchars($contactEmail ?? 'lawyersongroupllp@gmail.com') ?>" class="text-primary-law text-decoration-none">
                        <i class="fas fa-envelope me-1 me-lg-2"></i> 
                        <?= htmlspecialchars($contactEmail ?? 'lawyersongroupllp@gmail.com') ?>
                    </a>
                </div>
                <div class="text-truncate">
                    <a href="tel:96664568998" class="text-primary-law text-decoration-none">
                        <i class="fas fa-phone-alt me-1 me-lg-2"></i> 
                        9(666) 456-89-98
                    </a>
                </div>
            </div>
            <div class="d-none d-lg-flex align-items-center h-100">
                <div class="me-4 py-2">
                    <?php $variant = 'header'; require __DIR__ . '/social_icons.php'; ?>
                </div>
                <a href="index.php?page=consultation" class="btn btn-primary-law rounded-0 h-100 px-4 py-2 fw-bold d-flex align-items-center border-0 text-white" style="background-color: #1a2538; font-size: 0.85rem; letter-spacing: 0.5px;">CONSULTATION</a>
            </div>
        </div>
    </div>
</div>

<!-- Main Navbar -->
<style>
  .brand-title-text { font-size: 1.15rem; }
  .brand-icon { font-size: 1.25rem; }
  .top-contact-text { font-size: 0.75rem; }
  @media(min-width: 768px) {
      .brand-title-text { font-size: 1.75rem; }
      .brand-icon { font-size: 1.75rem; }
      .top-contact-text { font-size: 0.85rem; }
  }
  @media(max-width: 991px) {
      .navbar-collapse {
          background-color: #0B1B3D;
          position: absolute;
          top: 100%;
          left: 0;
          right: 0;
          padding: 1rem;
          box-shadow: 0 4px 6px rgba(0,0,0,0.1);
      }
      .navbar-nav {
          text-align: center;
      }
      .navbar-nav .nav-item {
          margin-bottom: 0.5rem;
      }
  }
</style>
<nav class="navbar navbar-expand-lg navbar-dark bg-primary-law sticky-top py-3 shadow-sm">
  <div class="container">
    <a class="navbar-brand d-flex align-items-center" href="index.php">
      <?php if (!empty($siteLogoUrl)): ?>
      <img src="<?= htmlspecialchars($siteLogoUrl) ?>" alt="<?= htmlspecialchars(strip_tags($siteName ?? 'Lawyerson & Partners')) ?>" class="me-2 me-md-3 brand-logo" style="height: 42px; width: auto; max-width: 120px; object-fit: contain;">
      <?php else: ?>
      <div class="me-2 me-md-3 text-white brand-icon opacity-75"><i class="fas fa-gavel"></i></div>
      <?php endif; ?>
      <div>
        <div class="fw-bold text-white mb-0 brand-title-text" style="line-height: 1; letter-spacing: 0.5px;"><?= format_site_name_html($siteName ?? 'Lawyerson & Partners') ?></div>
        <small class="text-light opacity-75 d-block mt-1" style="font-size: 0.7rem; letter-spacing: 1px;">Best Law Office</small>
      </div>
    </a>
    <button class="navbar-toggler border-0" type="button" data-bs-toggle="collapse" data-bs-target="#mainNav" aria-controls="mainNav" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="mainNav">
      <ul class="navbar-nav ms-auto mb-2 mb-lg-0 fw-semibold text-uppercase" style="font-size: 0.8rem; letter-spacing: 0.5px;">
        <li class="nav-item px-2">
          <a class="nav-link <?= (!isset($_GET['page']) || $_GET['page'] === 'home') ? 'active text-gold' : 'text-white' ?>" href="index.php">HOME</a>
        </li>
        <li class="nav-item px-2">
          <a class="nav-link <?= (isset($_GET['page']) && $_GET['page'] === 'about') ? 'active text-gold' : 'text-white' ?>" href="index.php?page=about">ABOUT US</a>
        </li>
        <li class="nav-item px-2">
          <a class="nav-link <?= (isset($_GET['page']) && $_GET['page'] === 'practices') ? 'active text-gold' : 'text-white' ?>" href="index.php?page=practices">SERVICES</a>
        </li>
        <li class="nav-item px-2">
          <a class="nav-link <?= (isset($_GET['page']) && $_GET['page'] === 'attorneys') ? 'active text-gold' : 'text-white' ?>" href="index.php?page=attorneys">ATTORNEYS</a>
        </li>
        <li class="nav-item px-2">
          <a class="nav-link <?= (isset($_GET['page']) && $_GET['page'] === 'blog') ? 'active text-gold' : 'text-white' ?>" href="index.php?page=blog">BLOG</a>
        </li>
        <li class="nav-item px-2">
          <a class="nav-link <?= (isset($_GET['page']) && $_GET['page'] === 'contact') ? 'active text-gold' : 'text-white' ?>" href="index.php?page=contact">CONTACT</a>
        </li>
      </ul>
      <div class="d-flex align-items-center ms-3 d-none d-lg-block">
        <a href="#" data-bs-toggle="modal" data-bs-target="#searchModal" class="text-white opacity-75 text-decoration-none"><i class="fas fa-search"></i></a>
      </div>
    </div>
  </div>
</nav>

<!-- Search Modal -->
<div class="modal fade" id="searchModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-lg modal-dialog-centered">
    <div class="modal-content bg-transparent border-0">
      <div class="modal-body p-0">
        <div class="bg-white rounded-3 shadow-lg p-4 position-relative">
          <button type="button" class="btn-close position-absolute top-0 end-0 m-3" data-bs-dismiss="modal" aria-label="Close"></button>
          <h4 class="fw-bold text-primary-law mb-4 text-center">Search Legal Insights & Articles</h4>
          <form action="index.php" method="GET">
            <input type="hidden" name="page" value="blog">
            <div class="input-group input-group-lg">
              <span class="input-group-text bg-light border-0 text-gold"><i class="fas fa-search"></i></span>
              <input type="text" name="search" class="form-control bg-light border-0 px-3" placeholder="Type your keywords... (e.g. Corporate Law)" required autofocus>
              <button class="btn btn-primary-law px-5 fw-bold text-uppercase letter-spacing-1" type="submit">Search</button>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
</div>
