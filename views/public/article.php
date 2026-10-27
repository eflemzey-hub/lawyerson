<?php
$articleId = isset($_GET['id']) ? (int) $_GET['id'] : null;
$article = null;
$defaultPostImage = 'https://images.unsplash.com/photo-1450101499163-c8848c66ca85?auto=format&fit=crop&q=80&w=1200';

try {
    if (isset($postModel) && $articleId) {
        $article = $postModel->getPublishedById($articleId);
    }
} catch (Exception $e) {}

if (!$article) {
    echo '<section class="section-padding bg-law-bg"><div class="container text-center py-5"><h2 class="text-primary-law fw-bold mb-3">Article not found</h2><p class="text-muted mb-4">This post may be unpublished or no longer available.</p><a href="index.php?page=blog" class="btn btn-primary-law rounded-0">Back to Blog</a></div></section>';
    return;
}

$featuredImage = resolve_post_image_url($article['image_url'] ?? '', $defaultPostImage);

// Fetch related articles
$relatedArticles = [];
try {
    if (isset($postModel)) {
        $relatedArticles = $postModel->getRelatedArticles($articleId, $article['category'] ?? '', 3);
    }
} catch (Exception $e) {}
?>

<section class="bg-law-bg py-5">
    <div class="container-fluid px-lg-5">
        <div class="row justify-content-center">
            <div class="col-lg-10 col-xl-9 text-center py-4">
                <span class="badge bg-gold text-primary-law p-2 mb-3 fw-bold"><?= htmlspecialchars($article['category']) ?></span>
                <h1 class="display-4 fw-bold text-primary-law mb-4 mx-auto"><?= htmlspecialchars($article['title']) ?></h1>
                <div class="text-muted fw-bold text-uppercase letter-spacing-1 small">
                    <i class="far fa-calendar-alt text-gold me-2"></i> Published on <?= date('F d, Y', strtotime($article['created_at'])) ?>
                </div>
            </div>
        </div>
    </div>
</section>

<section class="bg-law-bg pb-5">
    <div class="container-fluid px-lg-5">
        <div class="row justify-content-center">
            <div class="col-lg-10 col-xl-9">
                <img src="<?= htmlspecialchars($featuredImage) ?>" alt="<?= htmlspecialchars($article['title']) ?>" class="img-fluid w-100 rounded-1 shadow-sm object-fit-cover" style="max-height: 500px;" onerror="this.src='<?= htmlspecialchars($defaultPostImage) ?>'">
            </div>
        </div>
    </div>
</section>

<section class="section-padding bg-white pt-2">
    <div class="container-fluid px-lg-5">
        <div class="row justify-content-center">
            <div class="col-lg-10 col-xl-9">
                <div class="article-content text-muted" style="line-height: 1.8; font-size: 1.125rem;">
                    <?= $article['content'] ?>
                </div>

                <style>
                    .article-content h2 {
                        color: #0b1b3d;
                        font-weight: 700;
                        font-size: 2rem;
                        margin-top: 3rem;
                        margin-bottom: 1.5rem;
                        padding-bottom: 0.75rem;
                        border-bottom: 3px solid #c5a572;
                    }
                    
                    .article-content h3 {
                        color: #0b1b3d;
                        font-weight: 600;
                        font-size: 1.5rem;
                        margin-top: 2.5rem;
                        margin-bottom: 1.25rem;
                    }
                    
                    .article-content p {
                        margin-bottom: 1.5rem;
                        text-align: justify;
                    }
                    
                    .article-content ul,
                    .article-content ol {
                        margin-bottom: 1.75rem;
                        padding-left: 2.5rem;
                    }
                    
                    .article-content li {
                        margin-bottom: 0.875rem;
                        line-height: 1.8;
                    }
                    
                    .article-content strong {
                        color: #0b1b3d;
                        font-weight: 600;
                    }
                    
                    .article-content a {
                        color: #c5a572;
                        text-decoration: underline;
                    }
                    
                    .article-content a:hover {
                        color: #0b1b3d;
                    }
                    
                    .article-content blockquote {
                        border-left: 4px solid #c5a572;
                        padding-left: 2rem;
                        margin: 2.5rem 0;
                        font-style: italic;
                        color: #555;
                    }
                    
                    .article-content code {
                        background-color: #f5f5f5;
                        padding: 0.25rem 0.5rem;
                        border-radius: 3px;
                        font-size: 0.9em;
                        color: #c5a572;
                    }
                    
                    /* Responsive adjustments */
                    @media (max-width: 991px) {
                        .article-content {
                            font-size: 1rem;
                        }
                        
                        .article-content h2 {
                            font-size: 1.75rem;
                            margin-top: 2.5rem;
                        }
                        
                        .article-content h3 {
                            font-size: 1.4rem;
                            margin-top: 2rem;
                        }
                    }
                </style>

                <hr class="my-5 opacity-25">

                <div class="d-flex flex-column flex-md-row justify-content-between align-items-center bg-law-bg p-4 rounded-1 gap-3">
                    <h5 class="mb-0 fw-bold text-primary-law">Share this insight:</h5>
                    <div class="d-flex gap-2">
                        <?php
                        $host = preg_replace('/[^A-Za-z0-9.\-:]/', '', $_SERVER['HTTP_HOST'] ?? 'localhost');
                        $path = strtok($_SERVER['REQUEST_URI'] ?? '/lawyerson/index.php?page=blog', "\r\n");
                        $shareUrl = urlencode((isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off' ? 'https' : 'http') . '://' . $host . $path);
                        $shareTitle = urlencode($article['title']);
                        ?>
                        <a href="https://www.linkedin.com/sharing/share-offsite/?url=<?= $shareUrl ?>" target="_blank" rel="noopener noreferrer" class="btn btn-outline-primary-law rounded-circle"><i class="fab fa-linkedin-in"></i></a>
                        <a href="https://twitter.com/intent/tweet?url=<?= $shareUrl ?>&text=<?= $shareTitle ?>" target="_blank" rel="noopener noreferrer" class="btn btn-outline-primary-law rounded-circle"><i class="fab fa-twitter"></i></a>
                        <a href="https://www.facebook.com/sharer/sharer.php?u=<?= $shareUrl ?>" target="_blank" rel="noopener noreferrer" class="btn btn-outline-primary-law rounded-circle"><i class="fab fa-facebook-f"></i></a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<?php if (!empty($relatedArticles)): ?>
<section class="section-padding bg-law-bg">
    <div class="container-fluid px-lg-5">
        <div class="row justify-content-center">
            <div class="col-lg-10 col-xl-9">
                <div class="text-center mb-5">
                    <h2 class="display-6 fw-bold text-primary-law mb-2">Related Articles</h2>
                    <p class="text-muted">Continue exploring legal insights on related topics</p>
                </div>
                
                <div class="row g-4">
                    <?php foreach ($relatedArticles as $relatedPost): ?>
                        <?php
                        $relatedImage = resolve_post_image_url($relatedPost['image_url'] ?? '', $defaultPostImage);
                        $relatedExcerpt = strip_tags($relatedPost['content'] ?? '');
                        if (strlen($relatedExcerpt) > 120) {
                            $relatedExcerpt = substr($relatedExcerpt, 0, 120) . '...';
                        }
                        ?>
                        <div class="col-md-4">
                            <a href="index.php?page=article&id=<?= (int) $relatedPost['id'] ?>" class="text-decoration-none d-block h-100">
                                <div class="card h-100 border-0 shadow-sm rounded-1 overflow-hidden related-article-card" style="transition: transform 0.3s ease, box-shadow 0.3s ease;">
                                    <div class="position-relative overflow-hidden" style="height: 180px;">
                                        <img src="<?= htmlspecialchars($relatedImage) ?>" 
                                             class="img-fluid w-100 h-100 object-fit-cover" 
                                             alt="<?= htmlspecialchars($relatedPost['title']) ?>" 
                                             style="transition: transform 0.5s ease;" 
                                             onerror="this.src='<?= htmlspecialchars($defaultPostImage) ?>'">
                                        <span class="badge bg-gold text-primary-law position-absolute top-0 start-0 m-2 px-2 py-1 rounded-0 shadow-sm fw-bold small">
                                            <?= htmlspecialchars($relatedPost['category']) ?>
                                        </span>
                                    </div>
                                    <div class="card-body p-3 d-flex flex-column">
                                        <div class="small text-muted fw-bold mb-2 text-uppercase" style="font-size: 0.75rem; letter-spacing: 0.5px;">
                                            <i class="far fa-calendar-alt me-1 text-gold"></i>
                                            <?= date('M d, Y', strtotime($relatedPost['created_at'])) ?>
                                        </div>
                                        <h5 class="card-title fw-bold mb-2 text-primary-law" style="font-size: 1rem; line-height: 1.4;">
                                            <?= htmlspecialchars($relatedPost['title']) ?>
                                        </h5>
                                        <p class="card-text text-muted flex-grow-1" style="font-size: 0.875rem;">
                                            <?= htmlspecialchars($relatedExcerpt) ?>
                                        </p>
                                        <span class="text-primary-law fw-bold d-inline-block mt-2 read-more-link" style="font-size: 0.875rem;">
                                            Read More <i class="fas fa-arrow-right ms-1"></i>
                                        </span>
                                    </div>
                                </div>
                            </a>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
    </div>
</section>

<style>
    .related-article-card {
        cursor: pointer;
    }
    
    .related-article-card:hover {
        transform: translateY(-8px);
        box-shadow: 0 0.75rem 2rem rgba(0, 0, 0, 0.15) !important;
    }
    
    .related-article-card:hover img {
        transform: scale(1.1);
    }
    
    .related-article-card .read-more-link {
        transition: padding-left 0.3s ease;
    }
    
    .related-article-card:hover .read-more-link {
        padding-left: 6px;
    }
    
    .related-article-card:hover .read-more-link i {
        transform: translateX(4px);
        transition: transform 0.3s ease;
    }
</style>
<?php endif; ?>

<section class="py-5 bg-primary-law text-white text-center">
    <div class="container py-3">
        <h2 class="display-6 mb-3 fw-bold">Need legal advice on this matter?</h2>
        <p class="lead opacity-75 mb-4">Contact our experts to discuss how this affects your specific situation.</p>
        <a href="index.php?page=consultation" class="btn btn-gold btn-lg rounded-0 px-5 py-3 shadow-sm">Schedule Consultation</a>
    </div>
</section>
