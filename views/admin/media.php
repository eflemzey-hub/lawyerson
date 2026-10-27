<?php
// We have $mediaModel instantiated in index.php
$mediaItems = [];
try {
    if(isset($mediaModel)) {
        $mediaItems = $mediaModel->getAll();
    }
} catch(Exception $e) {}

// Dummy data for UI showcase if empty
if(empty($mediaItems)) {
    $mediaItems = [
        ['id' => 1, 'file_name' => 'hero-banner.jpg', 'file_path' => 'https://images.unsplash.com/photo-1497366216548-37526070297c?w=400', 'file_size' => 1024000],
        ['id' => 2, 'file_name' => 'lawyer-1.jpg', 'file_path' => 'https://images.unsplash.com/photo-1560250097-0b93528c311a?w=400', 'file_size' => 512000],
    ];
}
?>

<div class="row mb-4">
    <div class="col-md-12">
        <div class="card border-0 shadow-sm">
            <div class="card-body p-4">
                <h5 class="card-title fw-bold text-primary-law mb-4"><i class="fas fa-cloud-upload-alt me-2 text-gold"></i> Upload New Media</h5>
                <?php if(isset($_SESSION['error'])): ?>
                    <div class="alert alert-danger"><?= htmlspecialchars($_SESSION['error']); unset($_SESSION['error']); ?></div>
                <?php endif; ?>
                <form action="index.php" method="POST" enctype="multipart/form-data" class="d-flex align-items-center gap-3">
                    <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?>">
                    <input type="hidden" name="action" value="upload_media">
                    <input type="file" name="files[]" class="form-control rounded-0" accept=".jpg,.jpeg,.png,.webp" multiple required>
                    <button type="submit" class="btn btn-primary-law rounded-0 px-4">Upload All</button>
                </form>
                <div class="form-text mt-2">Allowed types: JPG, PNG, WEBP. Max size: 5MB.</div>
            </div>
        </div>
    </div>
</div>

<div class="row g-4">
    <?php foreach($mediaItems as $media): ?>
    <div class="col-md-3 col-sm-4 col-6">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-img-top bg-light d-flex align-items-center justify-content-center overflow-hidden" style="height: 150px;">
                <img src="<?= htmlspecialchars($media['file_path']) ?>" class="img-fluid w-100 h-100 object-fit-cover" alt="Media">
            </div>
            <div class="card-body p-3">
                <p class="small text-truncate mb-1 fw-bold" title="<?= htmlspecialchars($media['file_name']) ?>"><?= htmlspecialchars($media['file_name']) ?></p>
                <p class="small text-muted mb-3"><?= number_format($media['file_size'] / 1024, 2) ?> KB</p>
                
                <div class="input-group input-group-sm mb-2">
                    <input type="text" class="form-control bg-light text-muted border-0" value="<?= htmlspecialchars($media['file_path']) ?>" id="url-<?= (int) $media['id'] ?>" readonly>
                    <button class="btn btn-outline-primary-law" type="button" onclick="copyToClipboard('url-<?= (int) $media['id'] ?>')" title="Copy URL"><i class="far fa-copy"></i></button>
                </div>

                <form action="index.php" method="POST" onsubmit="return confirm('Delete this image?');">
                    <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?>">
                    <input type="hidden" name="action" value="delete_media">
                    <input type="hidden" name="media_id" value="<?= (int) $media['id'] ?>">
                    <button type="submit" class="btn btn-sm btn-outline-danger rounded-0 w-100"><i class="fas fa-trash-alt me-1"></i> Delete</button>
                </form>
            </div>
        </div>
    </div>
    <?php endforeach; ?>
</div>

<script>
function copyToClipboard(elementId) {
    var copyText = document.getElementById(elementId);
    copyText.select();
    copyText.setSelectionRange(0, 99999); /* For mobile devices */
    document.execCommand("copy");
    alert("URL Copied: " + copyText.value);
}
</script>
