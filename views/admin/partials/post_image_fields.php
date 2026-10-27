<?php
$mediaItems = $mediaItems ?? [];
$currentImage = $currentImage ?? '';
$currentImageSrc = $currentImage !== '' ? resolve_post_image_url($currentImage) : '';
$placeholderImage = 'https://images.unsplash.com/photo-1450101499163-c8848c66ca85?auto=format&fit=crop&q=80&w=600';
?>
<div class="mb-3">
    <label class="form-label small fw-bold text-muted">Featured Image</label>
    <input type="file" name="featured_image" class="form-control rounded-0 bg-law-bg border-0" accept="image/jpeg,image/png,image/webp">
    <div class="form-text">Upload JPG, PNG, or WEBP (max 5MB).</div>
</div>
<?php if (!empty($mediaItems)): ?>
<div class="mb-3">
    <label class="form-label small fw-bold text-muted">Or select from Media Library</label>
    <select name="image_media_id" class="form-select rounded-0 bg-law-bg border-0">
        <option value="">-- Select uploaded image --</option>
        <?php foreach ($mediaItems as $media): ?>
            <option value="<?= (int) $media['id'] ?>"><?= htmlspecialchars($media['file_name']) ?></option>
        <?php endforeach; ?>
    </select>
</div>
<?php endif; ?>
<div class="mb-3">
    <label class="form-label small fw-bold text-muted">Or paste image URL</label>
    <input type="text" name="image_url" class="form-control rounded-0 bg-law-bg border-0" placeholder="https://example.com/image.jpg" value="<?= htmlspecialchars($currentImage) ?>">
</div>
<?php if ($currentImageSrc !== ''): ?>
<div class="mb-3">
    <label class="form-label small fw-bold text-muted">Current image</label>
    <img src="<?= htmlspecialchars($currentImageSrc) ?>" alt="Current featured image" class="img-fluid rounded border" style="max-height: 160px; object-fit: cover;">
</div>
<?php endif; ?>
<div class="mb-3">
    <label class="form-label small fw-bold text-muted">Preview</label>
    <img id="postImagePreview" src="<?= htmlspecialchars($currentImageSrc !== '' ? $currentImageSrc : $placeholderImage) ?>" alt="Image preview" class="img-fluid rounded border" style="max-height: 160px; object-fit: cover;">
</div>
<script>
(function () {
    const preview = document.getElementById('postImagePreview');
    const fileInput = document.querySelector('input[name="featured_image"]');
    const urlInput = document.querySelector('input[name="image_url"]');
    const mediaSelect = document.querySelector('select[name="image_media_id"]');
    const mediaPaths = <?= json_encode(array_column($mediaItems, 'file_path', 'id'), JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP) ?>;

    function setPreview(src) {
        if (preview && src) preview.src = src;
    }

    if (fileInput) {
        fileInput.addEventListener('change', function () {
            const file = fileInput.files && fileInput.files[0];
            if (!file) return;
            setPreview(URL.createObjectURL(file));
        });
    }

    if (urlInput) {
        urlInput.addEventListener('input', function () {
            const value = urlInput.value.trim();
            if (value) setPreview(value);
        });
    }

    if (mediaSelect) {
        mediaSelect.addEventListener('change', function () {
            const path = mediaPaths[mediaSelect.value];
            if (path) setPreview(path);
        });
    }
})();
</script>
