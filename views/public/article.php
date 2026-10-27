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
?>

<section class="bg-law-bg py-5">
    <div class="container py-4 text-center">
        <span class="badge bg-gold text-primary-law p-2 mb-3 fw-bold"><?= htmlspecialchars($article['category']) ?></span>
        <h1 class="display-4 fw-bold text-primary-law mb-4 mx-auto" style="max-width: 900px;"><?= htmlspecialchars($article['title']) ?></h1>
        <div class="text-muted fw-bold text-uppercase letter-spacing-1 small">
            <i class="far fa-calendar-alt text-gold me-2"></i> Published on <?= date('F d, Y', strtotime($article['created_at'])) ?>
        </div>
    </div>
</section>

<section class="bg-law-bg pb-5">
    <div class="container">
        <img src="<?= htmlspecialchars($featuredImage) ?>" alt="<?= htmlspecialchars($article['title']) ?>" class="img-fluid w-100 rounded-1 shadow-sm object-fit-cover" style="max-height: 500px;" onerror="this.src='<?= htmlspecialchars($defaultPostImage) ?>'">
    </div>
</section>

<section class="section-padding bg-white pt-2">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-8">
<section class="section-padding bg-white pt-2">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <div class="article-content fs-5 text-muted" style="line-height: 1.8;">
                    <?= $article['content'] ?>
                </div>

                <style>
                    .article-content h2 {
                        color: #0b1b3d;
                        font-weight: 700;
                        font-size: 1.75rem;
                        margin-top: 2.5rem;
                        margin-bottom: 1.25rem;
                        padding-bottom: 0.5rem;
                        border-bottom: 2px solid #c5a572;
                    }
                    
                    .article-content h3 {
                        color: #0b1b3d;
                        font-weight: 600;
                        font-size: 1.4rem;
                        margin-top: 2rem;
                        margin-bottom: 1rem;
                    }
                    
                    .article-content p {
                        margin-bottom: 1.25rem;
                        text-align: justify;
                    }
                    
                    .article-content ul,
                    .article-content ol {
                        margin-bottom: 1.5rem;
                        padding-left: 2rem;
                    }
                    
                    .article-content li {
                        margin-bottom: 0.75rem;
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
                        padding-left: 1.5rem;
                        margin: 2rem 0;
                        font-style: italic;
                        color: #555;
                    }
                    
                    .article-content code {
                        background-color: #f5f5f5;
                        padding: 0.2rem 0.4rem;
                        border-radius: 3px;
                        font-size: 0.9em;
                        color: #c5a572;
                    }
                </style>

                <hr class="my-5 opacity-25">

                <div class="d-flex justify-content-between align-items-center bg-law-bg p-4 rounded-1">
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

<section class="py-5 bg-primary-law text-white text-center">
    <div class="container py-3">
        <h2 class="display-6 mb-3 fw-bold">Need legal advice on this matter?</h2>
        <p class="lead opacity-75 mb-4">Contact our experts to discuss how this affects your specific situation.</p>
        <a href="index.php?page=consultation" class="btn btn-gold btn-lg rounded-0 px-5 py-3 shadow-sm">Schedule Consultation</a>
    </div>
</section>
