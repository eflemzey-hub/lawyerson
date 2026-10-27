<?php
$posts = [];
$searchQuery = $_GET['search'] ?? '';
$defaultPostImage = 'https://images.unsplash.com/photo-1450101499163-c8848c66ca85?auto=format&fit=crop&q=80&w=600';

$categoryFilter = $_GET['category'] ?? '';

try {
    if (isset($postModel)) {
        $posts = $postModel->getAll(true, $searchQuery);
        if ($categoryFilter !== '') {
            $posts = array_values(array_filter($posts, function ($post) use ($categoryFilter) {
                return strcasecmp($post['category'], $categoryFilter) === 0;
            }));
        }
    }
} catch (Exception $e) {}
?>
<!-- Hero -->
<section class="hero position-relative text-white py-5" style="overflow: hidden;">
    <div class="hero-bg-animated" style="background-image: url('https://images.unsplash.com/photo-1454165804606-c3d57bc86b40?auto=format&fit=crop&q=80&w=2000');"></div>
    <div class="container text-center py-5">
        <h1 class="display-4 fw-bold mb-3 text-white">Legal Insights</h1>
        <p class="lead opacity-75 mx-auto" style="max-width: 600px; font-weight: 300;">Stay informed with the latest legal news, expert analysis, and practical guidance from our attorneys.</p>
    </div>
</section>

<section class="section-padding bg-law-bg">
    <div class="container">
        <div class="row mb-5">
            <div class="col-12">
                <?php if (!empty($searchQuery)): ?>
                    <div class="d-flex justify-content-between align-items-center bg-white p-4 shadow-sm border-start border-4 border-gold">
                        <h4 class="mb-0 text-primary-law fw-bold">Search Results for: <span class="text-muted">"<?= htmlspecialchars($searchQuery) ?>"</span></h4>
                        <a href="index.php?page=blog" class="btn btn-outline-secondary rounded-0 btn-sm">Clear Search</a>
                    </div>
                <?php else: ?>
                    <?php
                    // Define available categories
                    $categories = [
                        '' => 'All',
                        'Corporate Law' => 'Corporate Law',
                        'Family Law' => 'Family Law',
                        'Real Estate' => 'Real Estate',
                        'Criminal Defense' => 'Criminal Defense'
                    ];
                    ?>
                    <div class="d-flex justify-content-center flex-wrap gap-2">
                        <?php foreach ($categories as $catValue => $catLabel): ?>
                            <?php
                            $isActive = ($categoryFilter === $catValue);
                            $btnClass = $isActive ? 'btn btn-primary-law rounded-0 px-4' : 'btn btn-outline-primary-law rounded-0 px-4 bg-white';
                            $url = $catValue === '' ? 'index.php?page=blog' : 'index.php?page=blog&category=' . urlencode($catValue);
                            ?>
                            <a href="<?= $url ?>" class="<?= $btnClass ?>">
                                <?= htmlspecialchars($catLabel) ?>
                            </a>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
            </div>
        </div>

        <?php if (empty($posts)): ?>
            <div class="text-center py-5">
                <h4 class="text-primary-law fw-bold mb-2">No articles published yet</h4>
                <p class="text-muted mb-0">Check back soon for legal insights from our team.</p>
            </div>
        <?php else: ?>
            <div class="row g-4">
                <?php foreach ($posts as $post): ?>
                    <?php
                    $imageSrc = resolve_post_image_url($post['image_url'] ?? '', $defaultPostImage);
                    $excerpt = strip_tags($post['content'] ?? '');
                    if (strlen($excerpt) > 140) {
                        $excerpt = substr($excerpt, 0, 140) . '...';
                    }
                    ?>
                    <div class="col-md-6 col-lg-4">
                        <a href="index.php?page=article&id=<?= (int) $post['id'] ?>" class="text-decoration-none d-block h-100">
                            <div class="card h-100 border-0 shadow-sm rounded-1 overflow-hidden blog-card" style="transition: transform 0.3s ease, box-shadow 0.3s ease;">
                                <div class="position-relative overflow-hidden" style="height: 200px;">
                                    <img src="<?= htmlspecialchars($imageSrc) ?>" class="img-fluid w-100 h-100 object-fit-cover" alt="<?= htmlspecialchars($post['title']) ?>" style="transition: transform 0.5s ease;" onerror="this.src='<?= htmlspecialchars($defaultPostImage) ?>'">
                                    <span class="badge bg-gold text-primary-law position-absolute top-0 start-0 m-3 p-2 rounded-0 shadow-sm fw-bold"><?= htmlspecialchars($post['category']) ?></span>
                                </div>
                                <div class="card-body p-4 d-flex flex-column">
                                    <div class="small text-muted fw-bold mb-2 text-uppercase letter-spacing-1"><i class="far fa-calendar-alt me-2 text-gold"></i><?= date('M d, Y', strtotime($post['created_at'])) ?></div>
                                    <h4 class="card-title fw-bold mb-3 text-primary-law"><?= htmlspecialchars($post['title']) ?></h4>
                                    <p class="card-text text-muted small flex-grow-1"><?= htmlspecialchars($excerpt) ?></p>
                                    <span class="text-primary-law fw-bold border-top pt-3 mt-2 d-inline-block read-more">Read Article <i class="fas fa-arrow-right ms-1 fs-6"></i></span>
                                </div>
                            </div>
                        </a>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>
</section>
<style>
    .blog-card {
        cursor: pointer;
    }
    
    .blog-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 0.5rem 1.5rem rgba(0, 0, 0, 0.15) !important;
    }
    
    .blog-card:hover img {
        transform: scale(1.05);
    }
    
    .blog-card .read-more {
        transition: padding-left 0.3s ease;
    }
    
    .blog-card:hover .read-more {
        padding-left: 8px;
    }
    
    .blog-card:hover .read-more i {
        transform: translateX(4px);
        transition: transform 0.3s ease;
    }
</style>
