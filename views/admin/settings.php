<?php
$settingsData = [];
$mediaItems = [];
try {
    if(isset($settingModel)) {
        $settingsData = $settingModel->getAll();
    }
    if(isset($mediaModel)) {
        $mediaItems = $mediaModel->getAll();
    }
} catch(Exception $e) {}

// Defaults
$siteName = $settingsData['site_name'] ?? 'Lawyerson & Partners';
$contactEmail = $settingsData['contact_email'] ?? 'lawyersongroupllp@gmail.com';
$footerText = $settingsData['footer_text'] ?? '© 2024 Lawyerson & Partners. All rights reserved.';

$mediaAssetFields = [
    ['key' => 'admin_image', 'label' => 'Admin Profile Image', 'default' => 'https://ui-avatars.com/api/?name=Admin&background=0D8ABC&color=fff&rounded=true', 'hint' => 'Shown in the admin sidebar.', 'variant' => 'avatar', 'legacy_key' => ''],
    ['key' => 'site_logo', 'label' => 'Website Logo', 'default' => '', 'hint' => 'Displayed in the site navbar. Use a horizontal logo (PNG/WEBP with transparent background works best).', 'variant' => 'logo', 'legacy_key' => 'logo_media_id'],
    ['key' => 'site_favicon', 'label' => 'Site Favicon', 'default' => '', 'hint' => 'Small icon in browser tabs and bookmarks. Not the same as the logo — use a square image (32×32 or 64×64 PNG/WEBP).', 'variant' => 'favicon', 'legacy_key' => 'favicon_media_id'],
];

$bannerFields = [
    ['key' => 'home_hero_bg', 'label' => 'Home Page Banner', 'default' => 'https://images.unsplash.com/photo-1589829085413-56de8ae18c73?auto=format&fit=crop&q=80&w=2000', 'hint' => 'Full-width hero background on the homepage.'],
    ['key' => 'home_contact_bg', 'label' => 'Home Contact Image', 'default' => 'https://images.unsplash.com/photo-1423592707957-3b827395c9c4?auto=format&fit=crop&q=80&w=800', 'hint' => 'Side image in the homepage consultation form.'],
    ['key' => 'home_about_img', 'label' => 'Home About Section', 'default' => 'https://images.unsplash.com/photo-1505664154772-a5e475094bc0?auto=format&fit=crop&q=80&w=800', 'hint' => 'Photo beside "About Our Firm" on the homepage.'],
    ['key' => 'about_hero_bg', 'label' => 'About Page Banner', 'default' => 'https://images.unsplash.com/photo-1454165804606-c3d57bc86b40?auto=format&fit=crop&q=80&w=2000', 'hint' => 'Top banner on the About page.'],
    ['key' => 'about_founder_img', 'label' => 'About Founder Image', 'default' => 'https://images.unsplash.com/photo-1560250097-0b93528c311a?auto=format&fit=crop&q=80&w=100', 'hint' => 'Founder portrait on the About page.'],
    ['key' => 'about_history_img_1', 'label' => 'About History Image 1', 'default' => 'https://images.unsplash.com/photo-1589829085413-56de8ae18c73?auto=format&fit=crop&q=80&w=400', 'hint' => 'First image in the firm history section.'],
    ['key' => 'about_history_img_2', 'label' => 'About History Image 2', 'default' => 'https://images.unsplash.com/photo-1505664154772-a5e475094bc0?auto=format&fit=crop&q=80&w=400', 'hint' => 'Second image in the firm history section.'],
    ['key' => 'attorneys_hero_bg', 'label' => 'Attorneys Page Banner', 'default' => 'https://images.unsplash.com/photo-1528605248644-14dd04022da1?auto=format&fit=crop&q=80&w=2000', 'hint' => 'Top banner on the Attorneys page.'],
    ['key' => 'contact_hero_bg', 'label' => 'Contact Page Banner', 'default' => 'https://images.unsplash.com/photo-1497366216548-37526070297c?auto=format&fit=crop&q=80&w=2000', 'hint' => 'Top banner on the Contact page.'],
];
?>

<style>
.banner-settings-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(320px, 1fr)); gap: 1.25rem; }
.banner-image-card { display: flex; flex-direction: column; background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 0.5rem; overflow: hidden; transition: box-shadow 0.2s ease, border-color 0.2s ease; }
.banner-image-card:hover { border-color: #d4af37; box-shadow: 0 8px 24px rgba(15, 23, 42, 0.08); }
.banner-image-card__preview { position: relative; aspect-ratio: 16 / 10; background: #0f172a; overflow: hidden; }
.banner-image-card__preview img { width: 100%; height: 100%; object-fit: cover; display: block; }
.banner-image-card__badge { position: absolute; top: 0.65rem; left: 0.65rem; background: rgba(15, 23, 42, 0.75); color: #d4af37; font-size: 0.7rem; font-weight: 700; letter-spacing: 0.05em; text-transform: uppercase; padding: 0.25rem 0.55rem; border-radius: 0.25rem; }
.banner-image-card__body { padding: 1rem 1.1rem 1.15rem; flex: 1; display: flex; flex-direction: column; }
.banner-image-card__title { font-weight: 700; color: #0f172a; margin-bottom: 0.35rem; }
.banner-image-card__hint { font-size: 0.8rem; color: #64748b; margin-bottom: 0.75rem; line-height: 1.4; }
.media-settings-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(280px, 1fr)); gap: 1.25rem; max-width: 960px; }
.banner-image-card--avatar .banner-image-card__preview,
.banner-image-card--logo .banner-image-card__preview,
.banner-image-card--favicon .banner-image-card__preview { aspect-ratio: 1; max-height: 160px; }
.banner-image-card--favicon .banner-image-card__preview { max-height: 110px; background: #fff; }
.banner-image-card--favicon .banner-image-card__preview img { object-fit: contain; padding: 14px; }
.banner-image-card--avatar .banner-image-card__preview img { object-fit: cover; }
.banner-image-card--logo .banner-image-card__preview img { object-fit: contain; padding: 10px; background: #0f172a; }
</style>

<div class="row">
    <div class="col-12">
        <div class="card border-0 shadow-sm">
            <div class="card-body p-4">
                <?php if(isset($_GET['success'])): ?>
                    <div class="alert alert-success border-0 rounded-0"><i class="fas fa-check-circle me-2"></i> Settings updated successfully!</div>
                <?php endif; ?>
                <?php if(isset($_GET['error']) && !empty($_SESSION['settings_error'])): ?>
                    <div class="alert alert-danger border-0 rounded-0"><i class="fas fa-exclamation-circle me-2"></i> <?= htmlspecialchars($_SESSION['settings_error']) ?></div>
                    <?php unset($_SESSION['settings_error']); ?>
                <?php endif; ?>
                
                <form action="index.php" method="POST">
                    <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?>">
                    <input type="hidden" name="action" value="update_settings">
                    
                    <h5 class="fw-bold text-primary-law mb-4">General Settings</h5>
                    
                    <div class="mb-3">
                        <label class="form-label text-muted fw-bold small">Website Name</label>
                        <input type="text" name="settings[site_name]" class="form-control rounded-0 bg-light border-0" value="<?= htmlspecialchars($siteName) ?>">
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label text-muted fw-bold small">Contact Email</label>
                        <input type="email" name="settings[contact_email]" class="form-control rounded-0 bg-light border-0" value="<?= htmlspecialchars($contactEmail) ?>">
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label text-muted fw-bold small">Footer Text</label>
                        <input type="text" name="settings[footer_text]" class="form-control rounded-0 bg-light border-0" value="<?= htmlspecialchars($footerText) ?>">
                    </div>

                    <h6 class="fw-bold text-primary-law mb-3 mt-4">Social Media Links</h6>
                    <p class="text-muted small mb-3">Add full URLs (e.g. https://facebook.com/yourpage). Icons only appear when a URL is set.</p>
                    <div class="mb-3">
                        <label class="form-label text-muted fw-bold small">Facebook URL</label>
                        <input type="url" name="settings[social_facebook]" class="form-control rounded-0 bg-light border-0" placeholder="https://facebook.com/yourpage" value="<?= htmlspecialchars($settingModel->get('social_facebook', '')) ?>">
                    </div>
                    <div class="mb-3">
                        <label class="form-label text-muted fw-bold small">LinkedIn URL</label>
                        <input type="url" name="settings[social_linkedin]" class="form-control rounded-0 bg-light border-0" placeholder="https://linkedin.com/company/yourpage" value="<?= htmlspecialchars($settingModel->get('social_linkedin', '')) ?>">
                    </div>
                    <div class="mb-3">
                        <label class="form-label text-muted fw-bold small">Twitter / X URL</label>
                        <input type="url" name="settings[social_twitter]" class="form-control rounded-0 bg-light border-0" placeholder="https://twitter.com/yourpage" value="<?= htmlspecialchars($settingModel->get('social_twitter', '')) ?>">
                    </div>
                    <div class="mb-3">
                        <label class="form-label text-muted fw-bold small">Website URL</label>
                        <input type="url" name="settings[social_website]" class="form-control rounded-0 bg-light border-0" placeholder="https://yourwebsite.com" value="<?= htmlspecialchars($settingModel->get('social_website', '')) ?>">
                    </div>

                    <div class="mb-4">
                        <label class="form-label text-muted fw-bold small">Tawk.to Livechat ID <span class="text-danger fw-normal">( *If you don't want to use Tawk.to leave it empty )</span></label>
                        <input type="text" name="settings[tawk_id]" class="form-control rounded-0 bg-light border-0" value="<?= htmlspecialchars($settingModel->get('tawk_id', '')) ?>">
                        <div class="form-text">Find this ID in your Tawk.to dashboard -> Administration -> Property Settings -> Property ID.</div>
                    </div>

                    <div class="text-end mb-4">
                        <button type="submit" class="btn btn-primary-law rounded-3 px-4 fw-bold">Save General Settings</button>
                    </div>

                    <hr class="my-4">
                    <div class="d-flex flex-wrap justify-content-between align-items-center gap-2 mb-3">
                        <div>
                            <h5 class="fw-bold text-primary-law mb-1">Media Settings</h5>
                            <p class="text-muted small mb-0">Logo, favicon, and admin profile. Favicon and logo are separate — favicon is the small browser-tab icon.</p>
                        </div>
                        <a href="index.php?page=admin_media" class="btn btn-sm btn-outline-primary-law rounded-0">
                            <i class="fas fa-cloud-upload-alt me-1"></i> Media Manager
                        </a>
                    </div>

                    <div class="media-settings-grid mb-4">
                        <?php foreach ($mediaAssetFields as $field): ?>
                            <?php
                            $bannerKey = $field['key'];
                            $bannerLabel = $field['label'];
                            $bannerDefault = $field['default'];
                            $bannerHint = $field['hint'];
                            $cardVariant = $field['variant'];
                            $bannerValue = resolve_setting_media_path(
                                $settingModel,
                                $mediaModel,
                                $field['key'],
                                $field['legacy_key'] ?? ''
                            );
                            if ($bannerValue === '') {
                                $bannerValue = $settingModel->get($field['key'], $field['default']);
                            }
                            require __DIR__ . '/partials/banner_image_field.php';
                            ?>
                        <?php endforeach; ?>
                    </div>

                    <div class="text-end mb-4">
                        <button type="submit" class="btn btn-primary-law rounded-3 px-4 fw-bold">Save Media Settings</button>
                    </div>

                    <hr class="my-4">
                    <div class="d-flex flex-wrap justify-content-between align-items-center gap-2 mb-3">
                        <div>
                            <h5 class="fw-bold text-primary-law mb-1">Website Banners (Photo Sections)</h5>
                            <p class="text-muted small mb-0">Pick from the Media Library or paste a path. Previews update as you type.</p>
                        </div>
                        <a href="index.php?page=admin_media" class="btn btn-sm btn-outline-primary-law rounded-0">
                            <i class="fas fa-cloud-upload-alt me-1"></i> Media Manager
                        </a>
                    </div>

                    <div class="banner-settings-grid mb-4">
                        <?php foreach ($bannerFields as $field): ?>
                            <?php
                            $bannerKey = $field['key'];
                            $bannerLabel = $field['label'];
                            $bannerDefault = $field['default'];
                            $bannerHint = $field['hint'];
                            $cardVariant = 'banner';
                            unset($bannerValue);
                            require __DIR__ . '/partials/banner_image_field.php';
                            ?>
                        <?php endforeach; ?>
                    </div>

                    <div class="text-end">
                        <button type="submit" class="btn btn-primary-law rounded-3 px-4 fw-bold">Save Banner Settings</button>
                    </div>
                </form>

                <hr class="my-4">

                <!-- API Key Management Section -->
                <div class="mb-4">
                    <div class="d-flex flex-wrap justify-content-between align-items-center gap-2 mb-3">
                        <div>
                            <h5 class="fw-bold text-primary-law mb-1"><i class="fas fa-key me-2"></i>Mobile API Key</h5>
                            <p class="text-muted small mb-0">This key is required for all mobile app API requests. Pass it via the <code>X-API-Key</code> header.</p>
                        </div>
                    </div>

                    <?php
                    $currentApiKey = '';
                    $apiKeyConfigFile = __DIR__ . '/../../config/api_key.php';
                    if (file_exists($apiKeyConfigFile)) {
                        $currentApiKey = include $apiKeyConfigFile;
                        if (!is_string($currentApiKey)) $currentApiKey = '';
                    }
                    if ($currentApiKey === '' && isset($settingModel)) {
                        $currentApiKey = $settingModel->get('api_key', '');
                    }
                    ?>

                    <?php if ($currentApiKey !== ''): ?>
                        <div class="bg-light rounded-3 p-3 mb-3">
                            <label class="form-label text-muted fw-bold small">Current API Key</label>
                            <div class="input-group">
                                <input type="text" id="apiKeyDisplay" class="form-control rounded-0 bg-white border font-monospace small" value="<?= htmlspecialchars($currentApiKey) ?>" readonly>
                                <button type="button" class="btn btn-outline-secondary rounded-0" onclick="navigator.clipboard.writeText(document.getElementById('apiKeyDisplay').value).then(()=>{this.innerHTML='<i class=\'fas fa-check\'></i> Copied';setTimeout(()=>{this.innerHTML='<i class=\'fas fa-copy\'></i> Copy'},2000)})">
                                    <i class="fas fa-copy"></i> Copy
                                </button>
                            </div>
                            <div class="form-text mt-2"><i class="fas fa-info-circle me-1"></i>Use this key in your mobile app. Example: <code>X-API-Key: <?= htmlspecialchars(substr($currentApiKey, 0, 8)) ?>...</code></div>
                        </div>
                    <?php else: ?>
                        <div class="alert alert-warning border-0 rounded-0">
                            <i class="fas fa-exclamation-triangle me-2"></i>
                            <strong>No API key configured.</strong> Generate one to secure your mobile API. Without a key, all API endpoints will reject requests.
                            <br><small class="text-muted">You can also generate a key by visiting <code>http://localhost/lawyerson/api/generate_api_key.php</code> in your browser.</small>
                        </div>
                    <?php endif; ?>

                    <form action="index.php" method="POST" class="d-inline" onsubmit="return confirm('Are you sure? This will invalidate the current API key. Any apps using the old key will stop working until updated.')">
                        <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?>">
                        <input type="hidden" name="action" value="regenerate_api_key">
                        <button type="submit" class="btn btn-outline-warning rounded-3 px-4 fw-bold">
                            <i class="fas fa-sync-alt me-1"></i> <?= $currentApiKey !== '' ? 'Regenerate API Key' : 'Generate API Key' ?>
                        </button>
                    </form>
                </div>

                <hr class="my-4">

                <!-- Admin Security Section -->
                <div class="mb-3">
                    <h5 class="fw-bold text-primary-law mb-3"><i class="fas fa-shield-alt me-2"></i>Admin Security</h5>
                    
                    <?php if(isset($_SESSION['admin_security_error'])): ?>
                        <div class="alert alert-danger py-2 small"><i class="fas fa-exclamation-circle me-1"></i> <?= htmlspecialchars($_SESSION['admin_security_error']); unset($_SESSION['admin_security_error']); ?></div>
                    <?php endif; ?>
                    <?php if(isset($_SESSION['admin_security_flash'])): ?>
                        <div class="alert alert-success py-2 small"><i class="fas fa-check-circle me-1"></i> <?= htmlspecialchars($_SESSION['admin_security_flash']); unset($_SESSION['admin_security_flash']); ?></div>
                    <?php endif; ?>

                    <form action="index.php" method="POST" class="bg-light rounded-3 p-3">
                        <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?>">
                        <input type="hidden" name="action" value="update_admin_password">
                        <input type="hidden" name="redirect" value="index.php?page=admin_settings">

                        <div class="row g-3">
                            <div class="col-md-4">
                                <label class="form-label text-muted fw-bold small">Current Password</label>
                                <input type="password" name="current_password" class="form-control rounded-0 bg-white border" required>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label text-muted fw-bold small">New Password</label>
                                <input type="password" name="new_password" class="form-control rounded-0 bg-white border" required minlength="8">
                            </div>
                            <div class="col-md-4">
                                <label class="form-label text-muted fw-bold small">Confirm New Password</label>
                                <input type="password" name="confirm_password" class="form-control rounded-0 bg-white border" required minlength="8">
                            </div>
                        </div>
                        <div class="mt-3">
                            <button type="submit" class="btn btn-outline-danger rounded-3 px-4 fw-bold">
                                <i class="fas fa-lock me-1"></i> Change Admin Password
                            </button>
                        </div>
                    </form>
                </div>

                <script>
                (function () {
                    document.querySelectorAll('.banner-image-card').forEach(function (card) {
                        const preview = card.querySelector('.banner-preview');
                        const urlInput = card.querySelector('.banner-url-input');
                        const mediaSelect = card.querySelector('.banner-media-select');
                        const defaultSrc = card.dataset.defaultSrc || '';

                        function setPreview(src) {
                            if (!preview) return;
                            preview.src = src || defaultSrc;
                        }

                        if (urlInput) {
                            urlInput.addEventListener('input', function () {
                                const value = urlInput.value.trim();
                                setPreview(value ? value : defaultSrc);
                            });
                        }

                        if (mediaSelect) {
                            mediaSelect.addEventListener('change', function () {
                                const option = mediaSelect.options[mediaSelect.selectedIndex];
                                const path = option && option.dataset.path ? option.dataset.path : '';
                                if (path && urlInput) {
                                    urlInput.value = path;
                                    setPreview(path);
                                }
                            });
                        }
                    });
                })();
                </script>
            </div>
        </div>
    </div>
</div>

