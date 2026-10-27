<?php
$postId = isset($_GET['id']) ? (int) $_GET['id'] : null;
$post = null;
$mediaItems = [];
try {
    if (isset($postModel) && $postId) {
        $post = $postModel->getById($postId);
    }
    if (isset($mediaModel)) {
        $mediaItems = $mediaModel->getAll();
    }
} catch (Exception $e) {}

if (!$post) {
    echo "<div class='container py-5 text-center'><h3>Post not found.</h3><a href='index.php?page=admin_blog' class='btn btn-primary-law mt-3'>Back to Blog</a></div>";
    return;
}
?>

<div class="bg-primary-law text-white py-4">
    <div class="container d-flex justify-content-between align-items-center">
        <h2 class="mb-0 fw-bold">Edit Post</h2>
        <a href="index.php?page=admin_blog" class="btn btn-outline-light rounded-0"><i class="fas fa-arrow-left me-2"></i>Back to Blog</a>
    </div>
</div>

<section class="section-padding bg-law-bg" style="min-height: 70vh;">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <?php if (isset($_GET['error']) && !empty($_SESSION['post_error'])): ?>
                    <div class="alert alert-danger border-0 rounded-0 mb-4"><?= htmlspecialchars($_SESSION['post_error']) ?></div>
                    <?php unset($_SESSION['post_error']); ?>
                <?php endif; ?>
                <div class="card border-0 shadow-sm rounded-1">
                    <div class="card-body p-5">
                        <h4 class="text-primary-law fw-bold mb-4">Update Post Details</h4>
                        <form action="index.php" method="POST" enctype="multipart/form-data">
                            <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?>">
                            <input type="hidden" name="action" value="update_post">
                            <input type="hidden" name="post_id" value="<?= (int) $post['id'] ?>">
                            <input type="hidden" name="existing_image_url" value="<?= htmlspecialchars($post['image_url']) ?>">

                            <div class="mb-3">
                                <label class="form-label small fw-bold text-muted">Title</label>
                                <input type="text" name="title" class="form-control rounded-0 bg-law-bg border-0" value="<?= htmlspecialchars($post['title']) ?>" required>
                            </div>

                            <div class="mb-3">
                                <label class="form-label small fw-bold text-muted">Category</label>
                                <select name="category" class="form-select rounded-0 bg-law-bg border-0" required>
                                    <option value="Corporate Law" <?= $post['category'] === 'Corporate Law' ? 'selected' : '' ?>>Corporate Law</option>
                                    <option value="Family Law" <?= $post['category'] === 'Family Law' ? 'selected' : '' ?>>Family Law</option>
                                    <option value="Real Estate" <?= $post['category'] === 'Real Estate' ? 'selected' : '' ?>>Real Estate</option>
                                    <option value="Criminal Defense" <?= $post['category'] === 'Criminal Defense' ? 'selected' : '' ?>>Criminal Defense</option>
                                    <option value="Firm News" <?= $post['category'] === 'Firm News' ? 'selected' : '' ?>>Firm News</option>
                                </select>
                            </div>

                            <?php $currentImage = $post['image_url']; require __DIR__ . '/partials/post_image_fields.php'; ?>

                            <div class="mb-3">
                                <label class="form-label small fw-bold text-muted">Status</label>
                                <select name="status" class="form-select rounded-0 bg-law-bg border-0">
                                    <option value="published" <?= $post['status'] === 'published' ? 'selected' : '' ?>>Published</option>
                                    <option value="draft" <?= $post['status'] === 'draft' ? 'selected' : '' ?>>Draft</option>
                                </select>
                            </div>

                            <div class="mb-4">
                                <label class="form-label small fw-bold text-muted">Content</label>
                                <textarea name="content" class="form-control rounded-0 bg-law-bg border-0" rows="10" required><?= htmlspecialchars($post['content']) ?></textarea>
                            </div>

                            <button type="submit" class="btn btn-gold w-100 rounded-0 fw-bold py-3 shadow-sm">Update Post</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
