<?php
/**
 * Banner / settings image field with live preview and media library picker.
 *
 * Expected vars: $bannerKey, $bannerLabel, $bannerDefault, $bannerHint (optional),
 *                $mediaItems, $settingModel
 */
$bannerHint = $bannerHint ?? '';
$cardVariant = $cardVariant ?? 'banner';
if (!isset($bannerValue)) {
    $bannerValue = $settingModel->get($bannerKey, $bannerDefault);
}
$placeholderSvg = 'data:image/svg+xml,' . rawurlencode('<svg xmlns="http://www.w3.org/2000/svg" width="240" height="160" viewBox="0 0 240 160"><rect fill="#e2e8f0" width="240" height="160"/><text x="120" y="84" text-anchor="middle" fill="#64748b" font-family="sans-serif" font-size="13">No image set</text></svg>');
if ($bannerDefault === '' && in_array($cardVariant, ['logo', 'favicon'], true)) {
    $bannerDefault = $placeholderSvg;
}
$bannerSrc = trim((string) $bannerValue) !== ''
    ? resolve_post_image_url($bannerValue, $bannerDefault)
    : $bannerDefault;
$previewFallback = resolve_post_image_url($bannerDefault, $placeholderSvg);
$fieldSlug = preg_replace('/[^a-z0-9_]/i', '-', $bannerKey);
$selectedMediaId = '';
foreach ($mediaItems as $media) {
    if (!empty($media['file_path']) && $media['file_path'] === $bannerValue) {
        $selectedMediaId = (string) $media['id'];
        break;
    }
}
?>
<div class="banner-image-card banner-image-card--<?= htmlspecialchars($cardVariant) ?>" data-default-src="<?= htmlspecialchars($previewFallback) ?>">
    <div class="banner-image-card__preview">
        <img
            src="<?= htmlspecialchars($bannerSrc) ?>"
            alt="<?= htmlspecialchars($bannerLabel) ?> preview"
            class="banner-preview img-fluid"
            data-banner-key="<?= htmlspecialchars($fieldSlug) ?>"
            onerror="this.src=this.closest('.banner-image-card').dataset.defaultSrc"
        >
        <span class="banner-image-card__badge"><i class="fas fa-image me-1"></i> Preview</span>
    </div>
    <div class="banner-image-card__body">
        <h6 class="banner-image-card__title"><?= htmlspecialchars($bannerLabel) ?></h6>
        <?php if ($bannerHint !== ''): ?>
            <p class="banner-image-card__hint"><?= htmlspecialchars($bannerHint) ?></p>
        <?php endif; ?>

        <?php if (!empty($mediaItems)): ?>
        <div class="mb-2">
            <label class="form-label small fw-bold text-muted mb-1">Media Library</label>
            <select class="form-select form-select-sm rounded-0 bg-law-bg border-0 banner-media-select" data-target="<?= htmlspecialchars($fieldSlug) ?>">
                <option value="">— Choose uploaded image —</option>
                <?php foreach ($mediaItems as $media): ?>
                    <option value="<?= (int) $media['id'] ?>" data-path="<?= htmlspecialchars($media['file_path']) ?>" <?= ($selectedMediaId === (string) $media['id']) ? 'selected' : '' ?>>
                        <?= htmlspecialchars($media['file_name']) ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>
        <?php endif; ?>

        <div>
            <label class="form-label small fw-bold text-muted mb-1">Image path or URL</label>
            <input
                type="text"
                name="settings[<?= htmlspecialchars($bannerKey) ?>]"
                class="form-control form-control-sm rounded-0 bg-law-bg border-0 banner-url-input"
                data-banner-key="<?= htmlspecialchars($fieldSlug) ?>"
                placeholder="uploads/your-image.jpg"
                value="<?= htmlspecialchars($bannerValue) ?>"
            >
        </div>
    </div>
</div>
