<?php
$posts = [];
$mediaItems = [];
try {
    if (isset($postModel)) {
        $posts = $postModel->getAll(false);
    }
    if (isset($mediaModel)) {
        $mediaItems = $mediaModel->getAll();
    }
} catch (Exception $e) {}

$defaultPostImage = 'https://images.unsplash.com/photo-1450101499163-c8848c66ca85?auto=format&fit=crop&q=80&w=600';
?>

<!-- Admin Header -->
<div class="bg-primary-law text-white py-4">
    <div class="container d-flex justify-content-between align-items-center">
        <h2 class="mb-0 fw-bold">Admin Dashboard</h2>
        <div>
            <a href="index.php?page=admin_appointments" class="btn btn-outline-light rounded-0 me-2">Appointments</a>
            <a href="index.php?page=admin_blog" class="btn btn-gold rounded-0 text-primary-law fw-bold">Blog Management</a>
        </div>
    </div>
</div>

<section class="section-padding bg-law-bg" style="min-height: 70vh;">
    <div class="container">

        <?php if (isset($_GET['success'])): ?>
            <div class="alert alert-success border-0 rounded-0 shadow-sm p-3 mb-4 d-flex align-items-center">
                <i class="fas fa-check-circle fs-4 me-3"></i> Post successfully created!
            </div>
        <?php endif; ?>
        <?php if (isset($_GET['error']) && !empty($_SESSION['post_error'])): ?>
            <div class="alert alert-danger border-0 rounded-0 shadow-sm p-3 mb-4 d-flex align-items-center">
                <i class="fas fa-exclamation-circle fs-4 me-3"></i> <?= htmlspecialchars($_SESSION['post_error']) ?>
            </div>
            <?php unset($_SESSION['post_error']); ?>
        <?php endif; ?>

        <div class="row g-4">
            <div class="col-lg-4">
                <div class="card border-0 shadow-sm rounded-1">
                    <div class="card-body p-4">
                        <h4 class="text-primary-law fw-bold mb-4">Create New Post</h4>
                        <form action="index.php" method="POST" enctype="multipart/form-data">
                            <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?>">
                            <input type="hidden" name="action" value="create_post">
                            <div class="mb-3">
                                <label class="form-label small fw-bold text-muted">Title</label>
                                <input type="text" name="title" class="form-control rounded-0 bg-law-bg border-0" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label small fw-bold text-muted">Category</label>
                                <select name="category" class="form-select rounded-0 bg-law-bg border-0" required>
                                    <option value="Corporate Law">Corporate Law</option>
                                    <option value="Family Law">Family Law</option>
                                    <option value="Real Estate">Real Estate</option>
                                    <option value="Criminal Defense">Criminal Defense</option>
                                    <option value="Firm News">Firm News</option>
                                </select>
                            </div>
                            <?php $currentImage = ''; require __DIR__ . '/partials/post_image_fields.php'; ?>
                            <div class="mb-3">
                                <label class="form-label small fw-bold text-muted">Status</label>
                                <select name="status" class="form-select rounded-0 bg-law-bg border-0">
                                    <option value="published">Published</option>
                                    <option value="draft">Draft</option>
                                </select>
                            </div>
                            <div class="mb-4">
                                <label class="form-label small fw-bold text-muted">Content</label>
                                <textarea name="content" class="form-control rounded-0 bg-law-bg border-0" rows="6" required></textarea>
                            </div>
                            <button type="submit" class="btn btn-primary-law w-100 rounded-0 fw-bold">Publish Post</button>
                        </form>
                    </div>
                </div>
            </div>

            <div class="col-lg-8">
                <div class="card border-0 shadow-sm rounded-1 overflow-hidden">
                    <div class="table-responsive">
                        <table class="table table-hover mb-0 align-middle">
                            <thead class="bg-white text-muted small text-uppercase letter-spacing-1">
                                <tr>
                                    <th class="py-3 px-4 fw-bold">Post</th>
                                    <th class="py-3 px-4 fw-bold">Category</th>
                                    <th class="py-3 px-4 fw-bold">Status</th>
                                    <th class="py-3 px-4 fw-bold text-end">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="border-top-0">
                                <?php if (empty($posts)): ?>
                                    <tr>
                                        <td colspan="4" class="px-4 py-5 text-center text-muted">No blog posts yet. Create your first post using the form on the left.</td>
                                    </tr>
                                <?php else: ?>
                                    <?php foreach ($posts as $post): ?>
                                        <?php $thumb = resolve_post_image_url($post['image_url'] ?? '', $defaultPostImage); ?>
                                        <tr>
                                            <td class="px-4 py-3">
                                                <div class="d-flex align-items-center gap-3">
                                                    <img src="<?= htmlspecialchars($thumb) ?>" alt="" class="rounded" style="width: 72px; height: 54px; object-fit: cover;">
                                                    <div>
                                                        <div class="fw-bold text-primary-law"><?= htmlspecialchars($post['title']) ?></div>
                                                        <div class="small text-muted">Created: <?= date('M d, Y', strtotime($post['created_at'])) ?></div>
                                                    </div>
                                                </div>
                                            </td>
                                            <td class="px-4 text-muted fw-medium small"><?= htmlspecialchars($post['category']) ?></td>
                                            <td class="px-4">
                                                <?php $badgeClass = ($post['status'] === 'published') ? 'bg-success' : 'bg-warning text-dark'; ?>
                                                <span class="badge <?= $badgeClass ?> px-2 py-1 text-uppercase" style="font-size: 0.7rem; letter-spacing: 1px;">
                                                    <?= htmlspecialchars($post['status']) ?>
                                                </span>
                                            </td>
                                            <td class="px-4 text-end">
                                                <a href="index.php?page=article&id=<?= (int) $post['id'] ?>" class="btn btn-sm btn-outline-secondary rounded-0 me-1" title="View" target="_blank"><i class="fas fa-eye"></i></a>
                                                <a href="index.php?page=admin_edit_post&id=<?= (int) $post['id'] ?>" class="btn btn-sm btn-outline-primary-law rounded-0" title="Edit"><i class="fas fa-edit"></i></a>
                                                <form action="index.php" method="POST" class="d-inline">
                                                    <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?>">
                                                    <input type="hidden" name="action" value="delete_post">
                                                    <input type="hidden" name="post_id" value="<?= (int) $post['id'] ?>">
                                                    <button type="submit" class="btn btn-sm btn-outline-danger rounded-0" title="Delete" onclick="return confirm('Are you sure you want to delete this post?');"><i class="fas fa-trash-alt"></i></button>
                                                </form>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

    </div>
</section>
